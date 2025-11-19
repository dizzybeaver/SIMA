<?php
/**
 * sima-import-selector.php
 * 
 * SIMA Import Selection Tool - Refactored
 * Version: 2.1.0
 * Date: 2025-11-19
 */

require_once __DIR__ . '/sima/support/php/sima-common.php';

/**
 * Generate updated import instructions
 */
function generateUpdatedInstructions($originalData, $newSelections) {
    $md = "# Import Instructions - {$originalData['metadata']['archive']}\n\n";
    $md .= "**Archive:** {$originalData['metadata']['archive']}\n";
    $md .= "**Created:** {$originalData['metadata']['created']}\n";
    $md .= "**Modified:** " . date('Y-m-d') . "\n";
    
    $selectedCount = count($newSelections);
    $totalFiles = $originalData['metadata']['total_files'];
    
    $md .= "**SIMA Version:** {$originalData['metadata']['sima_version']}\n";
    $md .= "**Total Files:** {$totalFiles}\n";
    $md .= "**Selected:** {$selectedCount}\n\n";
    
    $md .= "## Installation State\n\n";
    
    $selectedFiles = [];
    $unselectedFiles = [];
    
    $allCategories = array_merge(
        $originalData['selected'] ?? [],
        $originalData['unselected'] ?? []
    );
    
    foreach ($allCategories as $category) {
        foreach ($category['files'] as $file) {
            if (in_array($file['target_path'], $newSelections)) {
                if (!isset($selectedFiles[$category['path']])) {
                    $selectedFiles[$category['path']] = [];
                }
                $selectedFiles[$category['path']][] = $file;
            } else {
                if (!isset($unselectedFiles[$category['path']])) {
                    $unselectedFiles[$category['path']] = [];
                }
                $unselectedFiles[$category['path']][] = $file;
            }
        }
    }
    
    $md .= "### Selected for Install ({$selectedCount} files)\n\n";
    foreach ($selectedFiles as $categoryPath => $files) {
        $md .= "#### {$categoryPath} (" . count($files) . " files)\n";
        foreach ($files as $file) {
            $md .= "- [x] {$file['filename']} → {$file['target_path']}";
            if (!empty($file['status'])) {
                $md .= " ({$file['status']})";
            }
            $md .= "\n";
        }
        $md .= "\n";
    }
    
    $unselectedCount = $totalFiles - $selectedCount;
    $md .= "### Not Selected for Install ({$unselectedCount} files)\n\n";
    
    if ($unselectedCount > 0) {
        foreach ($unselectedFiles as $categoryPath => $files) {
            $md .= "#### {$categoryPath} (" . count($files) . " files)\n";
            foreach ($files as $file) {
                $md .= "- [ ] {$file['filename']} (SKIP)\n";
            }
            $md .= "\n";
        }
    } else {
        $md .= "(None)\n\n";
    }
    
    return $md;
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'parse') {
        try {
            if (!isset($_FILES['import_instructions'])) {
                throw new Exception("No file uploaded");
            }
            
            $content = file_get_contents($_FILES['import_instructions']['tmp_name']);
            $data = parseImportInstructions($content);
            
            sendJsonResponse(true, ['data' => $data]);
        } catch (Exception $e) {
            sendJsonResponse(false, [], $e->getMessage());
        }
    }
    
    if ($action === 'generate') {
        try {
            $originalData = json_decode($_POST['original_data'], true);
            $newSelections = json_decode($_POST['new_selections'], true);
            
            $updatedContent = generateUpdatedInstructions($originalData, $newSelections);
            
            $tmpFile = tempnam(sys_get_temp_dir(), 'import-instructions-');
            file_put_contents($tmpFile, $updatedContent);
            
            sendJsonResponse(true, [
                'content' => $updatedContent,
                'download_url' => '?download=' . basename($tmpFile)
            ]);
        } catch (Exception $e) {
            sendJsonResponse(false, [], $e->getMessage());
        }
    }
}

// Handle downloads
if (isset($_GET['download'])) {
    $filename = basename($_GET['download']);
    $filepath = sys_get_temp_dir() . '/' . $filename;
    
    if (file_exists($filepath)) {
        header('Content-Type: text/markdown');
        header('Content-Disposition: attachment; filename="import-instructions.md"');
        readfile($filepath);
        unlink($filepath);
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📥 SIMA Import Selection Tool</title>
    <link rel="stylesheet" href="/sima/support/php/sima-styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>📥 SIMA Import Selection Tool</h1>
        </header>
        
        <div class="section" id="upload-section">
            <h2>Upload Import Instructions</h2>
            <div class="upload-area" id="upload-area">
                <p style="font-size: 48px; margin-bottom: 10px;">📄</p>
                <p style="font-size: 18px;">Drop import-instructions.md here</p>
                <p style="color: #666;">or click to browse</p>
                <input type="file" id="file-input" accept=".md">
            </div>
            <div style="margin-top: 20px; text-align: center;">
                <button onclick="document.getElementById('file-input').click()">📂 Select File</button>
            </div>
        </div>
        
        <div class="section hidden" id="metadata-section">
            <h2>Archive Information</h2>
            <div class="metadata-grid">
                <div class="metadata-item">
                    <label>Archive</label>
                    <div class="value" id="meta-archive"></div>
                </div>
                <div class="metadata-item">
                    <label>Created</label>
                    <div class="value" id="meta-created"></div>
                </div>
                <div class="metadata-item">
                    <label>Total Files</label>
                    <div class="value" id="meta-total"></div>
                </div>
            </div>
        </div>
        
        <div class="section hidden" id="selection-section">
            <h2>Modify Selections</h2>
            
            <div class="toolbar">
                <button onclick="expandAll()">📂 Expand All</button>
                <button onclick="collapseAll()">📁 Collapse All</button>
                <button onclick="selectAll()">✅ Select All</button>
                <button onclick="clearSelection()">❌ Clear</button>
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="🔎 Search..." onkeyup="filterTree(this.value)">
                </div>
                <span id="selectionSummary">Selected: 0</span>
            </div>
            
            <div id="file-list" class="file-list"></div>
            
            <div class="changes-summary">
                <h3>Changes</h3>
                <div class="stat">Added: <strong id="added-count">0</strong></div>
                <div class="stat">Removed: <strong id="removed-count">0</strong></div>
                <div class="stat">New Total: <strong id="new-total">0</strong></div>
            </div>
        </div>
        
        <div class="section hidden" id="actions-section">
            <button onclick="saveUpdatedInstructions()">💾 Save Updated Instructions</button>
        </div>
    </div>
    
    <script src="/sima/support/php/sima-tree.js"></script>
    <script>
        let originalData = null;
        let originalSelections = new Set();
        let currentSelections = new Set();
        
        const uploadArea = document.getElementById('upload-area');
        const fileInput = document.getElementById('file-input');
        
        uploadArea.addEventListener('click', () => fileInput.click());
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });
        uploadArea.addEventListener('dragleave', () => uploadArea.classList.remove('dragover'));
        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            if (e.dataTransfer.files.length > 0) handleFile(e.dataTransfer.files[0]);
        });
        
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) handleFile(e.target.files[0]);
        });
        
        function handleFile(file) {
            const formData = new FormData();
            formData.append('action', 'parse');
            formData.append('import_instructions', file);
            
            fetch('', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    originalData = data.data;
                    displayMetadata();
                    buildFileList();
                    document.getElementById('metadata-section').classList.remove('hidden');
                    document.getElementById('selection-section').classList.remove('hidden');
                    document.getElementById('actions-section').classList.remove('hidden');
                } else {
                    alert('Error: ' + data.error);
                }
            });
        }
        
        function displayMetadata() {
            document.getElementById('meta-archive').textContent = originalData.metadata.archive || 'N/A';
            document.getElementById('meta-created').textContent = originalData.metadata.created || 'N/A';
            document.getElementById('meta-total').textContent = originalData.metadata.total_files || 'N/A';
        }
        
        function buildFileList() {
            const container = document.getElementById('file-list');
            container.innerHTML = '';
            
            const allCategories = [...(originalData.selected || []), ...(originalData.unselected || [])];
            
            allCategories.forEach(category => {
                const catDiv = createTreeNode('folder', category.path, container);
                const catChildren = document.createElement('div');
                catChildren.className = 'tree-children';
                catChildren.style.display = 'none';
                
                category.files.forEach(file => {
                    createTreeNode('file', file.filename, catChildren, file.target_path, file.selected);
                    if (file.selected) {
                        originalSelections.add(file.target_path);
                        currentSelections.add(file.target_path);
                    }
                });
                
                catDiv.appendChild(catChildren);
            });
            
            updateChangesSummary();
        }
        
        function createTreeNode(type, name, parent, path = null, checked = false) {
            const div = document.createElement('div');
            div.className = `tree-node ${type}`;
            
            if (type === 'folder') {
                const toggle = document.createElement('span');
                toggle.className = 'tree-toggle';
                toggle.textContent = '▶';
                toggle.onclick = () => toggleBranch(toggle);
                div.appendChild(toggle);
            } else {
                div.appendChild(document.createElement('span')).className = 'tree-spacer';
            }
            
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.checked = checked;
            if (path) checkbox.dataset.path = path;
            checkbox.onchange = type === 'folder' ? 
                () => selectBranch(checkbox) : 
                () => updateSelection(path, checkbox.checked);
            div.appendChild(checkbox);
            
            const label = document.createElement('label');
            label.innerHTML = `<span class="${type}-icon">${type === 'folder' ? '📁' : '📄'}</span><span class="node-name">${name}</span>`;
            div.appendChild(label);
            
            parent.appendChild(div);
            return div;
        }
        
        function updateSelection(path, checked) {
            checked ? currentSelections.add(path) : currentSelections.delete(path);
            updateChangesSummary();
        }
        
        function updateChangesSummary() {
            let added = 0, removed = 0;
            currentSelections.forEach(path => { if (!originalSelections.has(path)) added++; });
            originalSelections.forEach(path => { if (!currentSelections.has(path)) removed++; });
            
            document.getElementById('added-count').textContent = added;
            document.getElementById('removed-count').textContent = removed;
            document.getElementById('new-total').textContent = currentSelections.size;
            document.getElementById('selectionSummary').textContent = `Selected: ${currentSelections.size}`;
        }
        
        function saveUpdatedInstructions() {
            const formData = new FormData();
            formData.append('action', 'generate');
            formData.append('original_data', JSON.stringify(originalData));
            formData.append('new_selections', JSON.stringify(Array.from(currentSelections)));
            
            fetch('', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.download_url;
                } else {
                    alert('Error: ' + data.error);
                }
            });
        }
    </script>
</body>
</html>
