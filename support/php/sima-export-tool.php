<?php
/**
 * sima-export-tool.php
 * 
 * SIMA Knowledge Export Tool - Refactored
 * Version: 2.1.0
 * Date: 2025-11-19
 */

require_once __DIR__ . '/sima/support/php/sima-common.php';

// Ensure export directory exists
if (!is_dir(EXPORT_DIR)) {
    mkdir(EXPORT_DIR, 0755, true);
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'scan') {
        $tree = scanKnowledgeTree(SIMA_ROOT);
        sendJsonResponse(true, ['tree' => $tree]);
    }
    
    if ($action === 'export') {
        try {
            $archiveName = $_POST['archive_name'] ?? 'SIMA-Archive';
            $description = $_POST['description'] ?? '';
            $selectedPaths = json_decode($_POST['selected_files'] ?? '[]', true);
            
            $selectedFiles = [];
            foreach ($selectedPaths as $path) {
                $fullPath = SIMA_ROOT . '/' . $path;
                if (file_exists($fullPath)) {
                    $metadata = extractFileMetadata($fullPath);
                    $selectedFiles[] = [
                        'path' => $fullPath,
                        'relative_path' => $path,
                        'filename' => basename($path),
                        'ref_id' => $metadata['ref_id'],
                        'category' => basename(dirname($path)),
                        'size' => filesize($fullPath),
                        'checksum' => md5_file($fullPath)
                    ];
                }
            }
            
            if (empty($selectedFiles)) {
                throw new Exception("No valid files selected");
            }
            
            $exportId = uniqid();
            $exportPath = EXPORT_DIR . '/' . $exportId;
            mkdir($exportPath, 0755, true);
            
            $manifestContent = generateManifest($archiveName, $description, $selectedFiles);
            file_put_contents($exportPath . '/manifest.yaml', $manifestContent);
            
            $instructionsContent = generateImportInstructions($archiveName, $selectedFiles);
            file_put_contents($exportPath . '/import-instructions.md', $instructionsContent);
            
            $zipPath = $exportPath . '/knowledge-base.zip';
            createArchiveZip($selectedFiles, $zipPath, 'knowledge-base.zip');
            
            $finalZipPath = $exportPath . '/SIMA-Archive-' . $archiveName . '-' . date('Y-m-d') . '.zip';
            $finalZip = new ZipArchive();
            $finalZip->open($finalZipPath, ZipArchive::CREATE);
            $finalZip->addFile($exportPath . '/manifest.yaml', 'manifest.yaml');
            $finalZip->addFile($exportPath . '/import-instructions.md', 'import-instructions.md');
            $finalZip->addFile($zipPath, 'knowledge-base.zip');
            $finalZip->close();
            
            sendJsonResponse(true, [
                'download_url' => '/sima-exports/' . $exportId . '/' . basename($finalZipPath),
                'file_count' => count($selectedFiles)
            ]);
            
        } catch (Exception $e) {
            sendJsonResponse(false, [], $e->getMessage());
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🎯 SIMA Knowledge Export Tool</title>
    <link rel="stylesheet" href="/sima/support/php/sima-styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>🎯 SIMA Knowledge Export Tool</h1>
        </header>
        
        <div class="section">
            <h2>Export Configuration</h2>
            <div class="form-group">
                <label for="exportName">Archive Name:</label>
                <input type="text" id="exportName" placeholder="my-export-package" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <input type="text" id="description" placeholder="AWS Lambda, DynamoDB knowledge">
            </div>
        </div>
        
        <div class="section">
            <div class="toolbar">
                <button onclick="loadKnowledgeTree()" id="scan-btn">🔍 Scan SIMA</button>
                <button onclick="expandAll()">📂 Expand All</button>
                <button onclick="collapseAll()">📁 Collapse All</button>
                <button onclick="selectAll()">✅ Select All</button>
                <button onclick="clearSelection()">❌ Clear</button>
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="🔎 Search..." onkeyup="filterTree(this.value)">
                </div>
                <span class="selection-summary" id="selectionSummary">Selected: 0</span>
            </div>
            
            <div class="loading" id="loading">
                <div class="spinner"></div>
                <p>Scanning...</p>
            </div>
            
            <div id="knowledge-tree" class="tree-container" style="display:none;"></div>
        </div>
        
        <div class="section" id="actions" style="display:none;">
            <button onclick="createExport()" id="exportButton">📦 Create Archive</button>
            <div class="success-message" id="success">
                <strong>✓ Export Complete!</strong>
                <p id="success-text"></p>
                <a href="#" id="download-link">Download Archive</a>
            </div>
        </div>
    </div>
    
    <script src="/sima/support/php/sima-tree.js"></script>
    <script>
        let knowledgeTree = null;
        let selectedFiles = new Set();
        
        function loadKnowledgeTree() {
            document.getElementById('loading').classList.add('active');
            document.getElementById('scan-btn').disabled = true;
            
            fetch('', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'action=scan'
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    knowledgeTree = data.tree;
                    renderTree();
                    document.getElementById('loading').classList.remove('active');
                    document.getElementById('knowledge-tree').style.display = 'block';
                    document.getElementById('actions').style.display = 'block';
                }
            });
        }
        
        function renderTree() {
            const container = document.getElementById('knowledge-tree');
            container.innerHTML = '';
            
            for (const [domainName, domain] of Object.entries(knowledgeTree)) {
                if (domain.total_files === 0) continue;
                renderDomain(container, domainName, domain);
            }
        }
        
        function renderDomain(container, name, domain) {
            const div = createTreeNode('folder', name, container);
            const children = document.createElement('div');
            children.className = 'tree-children';
            children.style.display = 'none';
            
            if (domain.categories) {
                for (const [catName, category] of Object.entries(domain.categories)) {
                    const catDiv = createTreeNode('folder', catName, children);
                    const catChildren = document.createElement('div');
                    catChildren.className = 'tree-children';
                    catChildren.style.display = 'none';
                    
                    category.files.forEach(file => {
                        createTreeNode('file', file.filename, catChildren, file.relative_path);
                    });
                    
                    catDiv.appendChild(catChildren);
                }
            }
            
            div.appendChild(children);
        }
        
        function createTreeNode(type, name, parent, path = null) {
            const div = document.createElement('div');
            div.className = `tree-node ${type}`;
            if (path) div.dataset.path = path;
            
            if (type === 'folder') {
                const toggle = document.createElement('span');
                toggle.className = 'tree-toggle';
                toggle.textContent = '▶';
                toggle.onclick = () => toggleBranch(toggle);
                div.appendChild(toggle);
            } else {
                const spacer = document.createElement('span');
                spacer.className = 'tree-spacer';
                div.appendChild(spacer);
            }
            
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.onchange = type === 'folder' ? 
                () => selectBranch(checkbox) : 
                () => toggleFile(path, checkbox.checked);
            div.appendChild(checkbox);
            
            const label = document.createElement('label');
            label.innerHTML = `<span class="${type}-icon">${type === 'folder' ? '📁' : '📄'}</span><span class="node-name">${name}${type === 'folder' ? '/' : ''}</span>`;
            div.appendChild(label);
            
            parent.appendChild(div);
            return div;
        }
        
        function toggleFile(path, checked) {
            checked ? selectedFiles.add(path) : selectedFiles.delete(path);
            updateSelectionSummary();
        }
        
        function updateSelectionSummary() {
            document.getElementById('selectionSummary').textContent = `Selected: ${selectedFiles.size}`;
            document.getElementById('exportButton').disabled = selectedFiles.size === 0;
        }
        
        function createExport() {
            const archiveName = document.getElementById('exportName').value.trim();
            const description = document.getElementById('description').value.trim();
            
            if (!archiveName) {
                alert('Please enter an archive name');
                return;
            }
            
            if (selectedFiles.size === 0) {
                alert('Please select at least one file');
                return;
            }
            
            document.getElementById('exportButton').disabled = true;
            document.getElementById('exportButton').textContent = 'Creating...';
            
            const formData = new FormData();
            formData.append('action', 'export');
            formData.append('archive_name', archiveName);
            formData.append('description', description);
            formData.append('selected_files', JSON.stringify(Array.from(selectedFiles)));
            
            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                document.getElementById('exportButton').disabled = false;
                document.getElementById('exportButton').textContent = '📦 Create Archive';
                
                if (data.success) {
                    document.getElementById('success-text').textContent = 
                        `Successfully exported ${data.file_count} files.`;
                    document.getElementById('download-link').href = data.download_url;
                    document.getElementById('success').classList.add('active');
                } else {
                    alert('Error: ' + data.error);
                }
            });
        }
    </script>
</body>
</html>
