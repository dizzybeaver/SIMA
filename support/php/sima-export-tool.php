<?php
/**
 * sima-export-tool.php
 * 
 * SIMA Knowledge Export Tool - Directory Selection Version
 * Version: 2.2.0
 * Date: 2025-11-21
 * 
 * MODIFIED: Added directory selection capability
 */

require_once __DIR__ . '/sima/support/php/sima-common.php';

// Ensure export directory exists
if (!is_dir(EXPORT_DIR)) {
    mkdir(EXPORT_DIR, 0755, true);
}

/**
 * Validate and normalize directory path
 * ADDED: Security validation for user-selected directories
 */
function validateDirectory($path) {
    $path = rtrim($path, '/');
    
    // Security: Must be absolute path
    if ($path[0] !== '/') {
        throw new Exception("Path must be absolute");
    }
    
    // Security: No parent directory traversal
    if (strpos($path, '..') !== false) {
        throw new Exception("Invalid path: parent directory not allowed");
    }
    
    // Must exist and be readable
    if (!is_dir($path) || !is_readable($path)) {
        throw new Exception("Directory not found or not readable");
    }
    
    return realpath($path);
}

/**
 * Scan knowledge tree from specified directory
 * MODIFIED: Accept custom base path
 */
function scanKnowledgeTreeFrom($basePath) {
    $tree = [];
    $domains = ['generic', 'platforms', 'languages', 'projects'];
    
    foreach ($domains as $domain) {
        $domainPath = $basePath . '/' . $domain;
        if (!is_dir($domainPath)) continue;
        
        $tree[$domain] = scanDomainFrom($domainPath, $domain, $basePath);
    }
    
    return $tree;
}

/**
 * Scan domain with base path reference
 * MODIFIED: Track base path for relative path calculation
 */
function scanDomainFrom($domainPath, $domainName, $basePath) {
    $domain = [
        'name' => $domainName,
        'path' => $domainPath,
        'categories' => [],
        'total_files' => 0
    ];
    
    $subdirs = glob($domainPath . '/*', GLOB_ONLYDIR);
    
    foreach ($subdirs as $subdir) {
        $subdirName = basename($subdir);
        
        $categories = ['lessons', 'decisions', 'anti-patterns', 'specifications', 
                      'core', 'wisdom', 'workflows', 'frameworks'];
        
        if (in_array($subdirName, $categories)) {
            $categoryData = scanCategoryFrom($subdir, $subdirName, $basePath);
            if ($categoryData['file_count'] > 0) {
                $domain['categories'][$subdirName] = $categoryData;
                $domain['total_files'] += $categoryData['file_count'];
            }
        } else {
            $subdomainData = scanDomainFrom($subdir, $subdirName, $basePath);
            if ($subdomainData['total_files'] > 0) {
                $domain['subdomains'][$subdirName] = $subdomainData;
                $domain['total_files'] += $subdomainData['total_files'];
            }
        }
    }
    
    return $domain;
}

/**
 * Scan category with base path reference
 * MODIFIED: Calculate relative paths from custom base
 */
function scanCategoryFrom($categoryPath, $categoryName, $basePath) {
    $category = [
        'name' => $categoryName,
        'path' => $categoryPath,
        'files' => [],
        'file_count' => 0
    ];
    
    $files = glob($categoryPath . '/*.md');
    
    foreach ($files as $file) {
        $filename = basename($file);
        
        if (strpos($filename, 'Index') !== false || 
            strpos($filename, 'index') !== false) {
            continue;
        }
        
        $metadata = extractFileMetadata($file);
        
        $category['files'][] = [
            'filename' => $filename,
            'path' => $file,
            'relative_path' => str_replace($basePath . '/', '', $file),
            'ref_id' => $metadata['ref_id'],
            'version' => $metadata['version'],
            'size' => filesize($file)
        ];
        $category['file_count']++;
    }
    
    return $category;
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'scan') {
        try {
            // ADDED: Get directory from request
            $directory = $_POST['directory'] ?? SIMA_ROOT;
            $validatedDir = validateDirectory($directory);
            
            $tree = scanKnowledgeTreeFrom($validatedDir);
            sendJsonResponse(true, ['tree' => $tree, 'base_path' => $validatedDir]);
        } catch (Exception $e) {
            sendJsonResponse(false, [], $e->getMessage());
        }
    }
    
    if ($action === 'export') {
        try {
            // ADDED: Get and validate base directory
            $baseDir = $_POST['base_directory'] ?? SIMA_ROOT;
            $validatedBase = validateDirectory($baseDir);
            
            $archiveName = $_POST['archive_name'] ?? 'SIMA-Archive';
            $description = $_POST['description'] ?? '';
            $selectedPaths = json_decode($_POST['selected_files'] ?? '[]', true);
            
            $selectedFiles = [];
            foreach ($selectedPaths as $path) {
                // MODIFIED: Use validated base directory
                $fullPath = $validatedBase . '/' . $path;
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
        
        <!-- ADDED: Directory Selection Section -->
        <div class="section">
            <h2>Source Directory</h2>
            <div class="form-group">
                <label for="simaDirectory">SIMA Directory Path:</label>
                <input type="text" id="simaDirectory" value="<?= SIMA_ROOT ?>" 
                       placeholder="/path/to/sima" style="font-family: monospace;">
                <small>Absolute path to SIMA installation (e.g., /home/user/sima)</small>
            </div>
        </div>
        
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
                <!-- MODIFIED: Scan button now uses selected directory -->
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
                <p>Scanning SIMA directory...</p>
            </div>
            
            <div class="error" id="error">
                <p id="error-text"></p>
            </div>
            
            <div class="tree-container" id="tree"></div>
        </div>
        
        <div class="section">
            <button id="exportButton" onclick="createExport()" disabled>📦 Create Archive</button>
        </div>
        
        <div class="success" id="success">
            <h3>✅ Export Complete</h3>
            <p id="success-text"></p>
            <a id="download-link" class="download-btn" download>⬇️ Download Archive</a>
        </div>
    </div>

    <script src="/sima/support/php/sima-tree.js"></script>
    <script>
        let knowledgeTree = {};
        let selectedFiles = new Set();
        let currentBasePath = ''; // ADDED: Track current base path
        
        // MODIFIED: Load tree from specified directory
        function loadKnowledgeTree() {
            const directory = document.getElementById('simaDirectory').value.trim();
            
            if (!directory) {
                alert('Please enter a directory path');
                return;
            }
            
            document.getElementById('loading').style.display = 'block';
            document.getElementById('error').classList.remove('active');
            document.getElementById('tree').innerHTML = '';
            document.getElementById('scan-btn').disabled = true;
            
            const formData = new FormData();
            formData.append('action', 'scan');
            formData.append('directory', directory); // ADDED: Send directory
            
            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                document.getElementById('loading').style.display = 'none';
                document.getElementById('scan-btn').disabled = false;
                
                if (data.success) {
                    knowledgeTree = data.tree;
                    currentBasePath = data.base_path; // ADDED: Store base path
                    renderTree();
                } else {
                    document.getElementById('error-text').textContent = 'Error: ' + data.error;
                    document.getElementById('error').classList.add('active');
                }
            })
            .catch(err => {
                document.getElementById('loading').style.display = 'none';
                document.getElementById('scan-btn').disabled = false;
                document.getElementById('error-text').textContent = 'Error: ' + err.message;
                document.getElementById('error').classList.add('active');
            });
        }
        
        function renderTree() {
            const container = document.getElementById('tree');
            container.innerHTML = '';
            
            for (const [domainName, domain] of Object.entries(knowledgeTree)) {
                const domainNode = createNode(domainName, 'folder', container);
                domainNode.classList.add('expanded');
                
                if (domain.categories) {
                    for (const [catName, category] of Object.entries(domain.categories)) {
                        const catNode = createNode(catName + ` (${category.file_count})`, 'folder', domainNode);
                        
                        category.files.forEach(file => {
                            createNode(file.filename, 'file', catNode, file.relative_path);
                        });
                    }
                }
            }
        }
        
        function createNode(name, type, parent, path = null) {
            const div = document.createElement('div');
            div.className = `tree-node ${type}`;
            if (path) div.dataset.path = path;
            
            if (type === 'folder') {
                const toggle = document.createElement('span');
                toggle.className = 'tree-toggle expanded';
                toggle.textContent = '▼';
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
        
        // MODIFIED: Include base directory in export request
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
            formData.append('base_directory', currentBasePath); // ADDED: Send base path
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
