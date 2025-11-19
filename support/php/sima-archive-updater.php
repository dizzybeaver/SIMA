<?php
/**
 * sima-archive-updater.php
 * 
 * SIMA Archive Updater Tool - Refactored
 * Version: 2.1.0
 * Date: 2025-11-19
 * 
 * Purpose: Update existing knowledge archives with new/modified files
 * Location: /tools/sima-archive-updater.php
 */

require_once __DIR__ . '/sima/support/php/sima-common.php';

// Ensure export directory exists
if (!is_dir(EXPORT_DIR)) {
    mkdir(EXPORT_DIR, 0755, true);
}

/**
 * Build archived file inventory from manifest
 */
function buildArchivedInventory($manifest) {
    $inventory = [];
    if (isset($manifest['files'])) {
        foreach ($manifest['files'] as $file) {
            $inventory[$file['path']] = [
                'checksum' => $file['checksum'],
                'size' => $file['size'],
                'ref_id' => $file['ref_id'] ?? null
            ];
        }
    }
    return $inventory;
}

/**
 * Discover changes between archived and current SIMA
 */
function discoverChanges($archivedInventory) {
    $changes = [
        'new' => [],
        'modified' => [],
        'unchanged' => []
    ];
    
    $tree = scanKnowledgeTree(SIMA_ROOT);
    
    foreach ($tree as $domain) {
        scanDomainForChanges($domain, $archivedInventory, $changes);
    }
    
    return $changes;
}

/**
 * Recursively scan domain for changes
 */
function scanDomainForChanges($domain, $archivedInventory, &$changes) {
    if (isset($domain['categories'])) {
        foreach ($domain['categories'] as $category) {
            foreach ($category['files'] as $file) {
                $path = $file['relative_path'];
                $checksum = $file['checksum'];
                
                if (!isset($archivedInventory[$path])) {
                    // New file not in archive
                    $changes['new'][] = $file;
                } elseif ($archivedInventory[$path]['checksum'] !== $checksum) {
                    // Modified file (checksum changed)
                    $file['previous_checksum'] = $archivedInventory[$path]['checksum'];
                    $changes['modified'][] = $file;
                } else {
                    // Unchanged file
                    $changes['unchanged'][] = $file;
                }
            }
        }
    }
    
    if (isset($domain['subdomains'])) {
        foreach ($domain['subdomains'] as $subdomain) {
            scanDomainForChanges($subdomain, $archivedInventory, $changes);
        }
    }
}

/**
 * Update manifest with new increment
 */
function updateManifest($manifest, $selectedFiles, $incrementNumber) {
    // Update archive metadata
    $manifest['archive']['updated'] = date('c');
    $manifest['archive']['version'] = ($manifest['archive']['version'] ?? 1) + 1;
    
    // Add increment to structure
    $incrementPackage = "knowledge-increment-{$incrementNumber}.zip";
    $manifest['structure']['increments'][] = [
        'package' => $incrementPackage,
        'created' => date('c'),
        'files' => count($selectedFiles)
    ];
    
    // Update inventory counts
    foreach ($selectedFiles as $file) {
        if ($file['change_type'] === 'new') {
            $manifest['inventory']['total_files']++;
        }
    }
    
    // Add files to manifest inventory
    foreach ($selectedFiles as $file) {
        $existingIndex = null;
        foreach ($manifest['files'] as $index => $existingFile) {
            if ($existingFile['path'] === $file['relative_path']) {
                $existingIndex = $index;
                break;
            }
        }
        
        $fileEntry = [
            'path' => $file['relative_path'],
            'ref_id' => $file['ref_id'],
            'category' => basename(dirname($file['relative_path'])),
            'checksum' => $file['checksum'],
            'size' => $file['size'],
            'sima_version' => SIMA_VERSION,
            'exported' => date('Y-m-d')
        ];
        
        if ($existingIndex !== null) {
            $manifest['files'][$existingIndex] = $fileEntry;
        } else {
            $manifest['files'][] = $fileEntry;
        }
    }
    
    return $manifest;
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'load') {
        try {
            if (!isset($_FILES['manifest']) || !isset($_FILES['import_instructions'])) {
                throw new Exception("Missing required files");
            }
            
            $manifestContent = file_get_contents($_FILES['manifest']['tmp_name']);
            $instructionsContent = file_get_contents($_FILES['import_instructions']['tmp_name']);
            
            $manifest = yaml_parse($manifestContent);
            $instructions = parseImportInstructions($instructionsContent);
            
            sendJsonResponse(true, [
                'manifest' => $manifest,
                'instructions' => $instructions
            ]);
        } catch (Exception $e) {
            sendJsonResponse(false, [], $e->getMessage());
        }
    }
    
    if ($action === 'scan') {
        try {
            $manifest = json_decode($_POST['manifest'], true);
            $inventory = buildArchivedInventory($manifest);
            $changes = discoverChanges($inventory);
            
            sendJsonResponse(true, ['changes' => $changes]);
        } catch (Exception $e) {
            sendJsonResponse(false, [], $e->getMessage());
        }
    }
    
    if ($action === 'update') {
        try {
            $manifest = json_decode($_POST['manifest'], true);
            $selectedPaths = json_decode($_POST['selected_files'], true);
            $allChanges = json_decode($_POST['all_changes'], true);
            
            // Build selected files array with change types
            $selectedFiles = [];
            foreach ($selectedPaths as $path) {
                foreach (array_merge($allChanges['new'], $allChanges['modified']) as $file) {
                    if ($file['relative_path'] === $path) {
                        $file['change_type'] = isset($file['previous_checksum']) ? 'modified' : 'new';
                        $selectedFiles[] = $file;
                        break;
                    }
                }
            }
            
            if (empty($selectedFiles)) {
                throw new Exception("No files selected");
            }
            
            // Calculate increment number
            $incrementNumber = count($manifest['structure']['increments'] ?? []) + 1;
            
            // Create export directory
            $exportId = uniqid();
            $exportPath = EXPORT_DIR . '/' . $exportId;
            mkdir($exportPath, 0755, true);
            
            // Update manifest
            $updatedManifest = updateManifest($manifest, $selectedFiles, $incrementNumber);
            file_put_contents($exportPath . '/manifest.yaml', yaml_emit($updatedManifest));
            
            // Create increment ZIP
            $incrementZip = $exportPath . "/knowledge-increment-{$incrementNumber}.zip";
            createArchiveZip($selectedFiles, $incrementZip, basename($incrementZip));
            
            sendJsonResponse(true, [
                'download_url' => '/sima-exports/' . $exportId . '/' . basename($incrementZip),
                'file_count' => count($selectedFiles),
                'increment_number' => $incrementNumber
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
    <title>📦 SIMA Archive Updater</title>
    <link rel="stylesheet" href="/sima/support/php/sima-styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>📦 SIMA Archive Updater</h1>
            <p class="subtitle">Update existing knowledge archives with new/modified files</p>
        </header>
        
        <!-- Upload Section -->
        <div class="section" id="upload-section">
            <h2>Load Existing Archive</h2>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="upload-box" id="manifest-box" onclick="document.getElementById('manifest-input').click()">
                    <p style="font-size: 36px; margin-bottom: 10px;">📄</p>
                    <p style="font-weight: 600; margin-bottom: 5px;">manifest.yaml</p>
                    <p style="font-size: 12px; color: #666;">Archive metadata</p>
                    <input type="file" id="manifest-input" accept=".yaml,.yml">
                </div>
                <div class="upload-box" id="instructions-box" onclick="document.getElementById('instructions-input').click()">
                    <p style="font-size: 36px; margin-bottom: 10px;">📋</p>
                    <p style="font-weight: 600; margin-bottom: 5px;">import-instructions.md</p>
                    <p style="font-size: 12px; color: #666;">Import instructions</p>
                    <input type="file" id="instructions-input" accept=".md">
                </div>
            </div>
            <div style="text-align: center;">
                <button onclick="loadArchive()" id="load-btn" disabled>Load Archive</button>
            </div>
        </div>
        
        <!-- Archive Info Section -->
        <div class="section hidden" id="info-section">
            <h2>Archive Information</h2>
            <div class="info-grid">
                <div class="info-item">
                    <label>Archive Name</label>
                    <div class="value" id="info-name"></div>
                </div>
                <div class="info-item">
                    <label>Created</label>
                    <div class="value" id="info-created"></div>
                </div>
                <div class="info-item">
                    <label>Total Files</label>
                    <div class="value" id="info-files"></div>
                </div>
                <div class="info-item">
                    <label>Increments</label>
                    <div class="value" id="info-increments"></div>
                </div>
            </div>
        </div>
        
        <!-- Discovery Section -->
        <div class="section hidden" id="discovery-section">
            <h2>Compare with Current SIMA</h2>
            <button onclick="scanChanges()" id="scan-btn">🔍 Scan for Changes</button>
            
            <div class="loading" id="loading">
                <div class="spinner"></div>
                <p>Scanning SIMA knowledge base...</p>
            </div>
        </div>
        
        <!-- Selection Section -->
        <div class="section hidden" id="selection-section">
            <h2>Select Changes for Increment</h2>
            
            <div class="summary-cards">
                <div class="summary-card new">
                    <h3>New Files</h3>
                    <div class="count" id="new-count">0</div>
                </div>
                <div class="summary-card modified">
                    <h3>Modified Files</h3>
                    <div class="count" id="modified-count">0</div>
                </div>
                <div class="summary-card">
                    <h3>Selected</h3>
                    <div class="count" id="selected-count">0</div>
                </div>
            </div>
            
            <div class="toolbar">
                <button onclick="selectAllNew()">✅ Select All New</button>
                <button onclick="selectAllModified()">✅ Select All Modified</button>
                <button onclick="selectAllChanges()">✅ Select All Changes</button>
                <button onclick="clearSelection()">❌ Clear Selection</button>
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="🔎 Search..." onkeyup="filterTree(this.value)">
                </div>
            </div>
            
            <div id="changes-tree" class="tree"></div>
        </div>
        
        <!-- Actions Section -->
        <div class="section hidden" id="actions-section">
            <button onclick="createIncrement()" id="create-btn" disabled>📦 Create Increment</button>
            
            <div class="success-message" id="success-message">
                <strong>✓ Increment Created Successfully!</strong>
                <p id="success-text"></p>
                <a href="#" id="download-link">Download Increment Package</a>
            </div>
        </div>
    </div>
    
    <script src="/sima/support/php/sima-tree.js"></script>
    <script>
        let manifestData = null;
        let instructionsContent = null;
        let changesData = null;
        let selectedFiles = new Set();
        let manifestFile = null;
        let instructionsFile = null;
        
        // File input handlers
        document.getElementById('manifest-input').addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                manifestFile = e.target.files[0];
                document.getElementById('manifest-box').classList.add('uploaded');
                document.getElementById('manifest-box').querySelector('p:first-child').textContent = '✓';
                checkReady();
            }
        });
        
        document.getElementById('instructions-input').addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                instructionsFile = e.target.files[0];
                document.getElementById('instructions-box').classList.add('uploaded');
                document.getElementById('instructions-box').querySelector('p:first-child').textContent = '✓';
                checkReady();
            }
        });
        
        function checkReady() {
            if (manifestFile && instructionsFile) {
                document.getElementById('load-btn').disabled = false;
            }
        }
        
        function loadArchive() {
            const formData = new FormData();
            formData.append('action', 'load');
            formData.append('manifest', manifestFile);
            formData.append('import_instructions', instructionsFile);
            
            document.getElementById('load-btn').disabled = true;
            document.getElementById('load-btn').textContent = 'Loading...';
            
            fetch('', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => {
                document.getElementById('load-btn').disabled = false;
                document.getElementById('load-btn').textContent = 'Load Archive';
                
                if (data.success) {
                    manifestData = data.manifest;
                    instructionsContent = data.instructions;
                    displayInfo();
                    document.getElementById('info-section').classList.remove('hidden');
                    document.getElementById('discovery-section').classList.remove('hidden');
                } else {
                    alert('Error: ' + data.error);
                }
            });
        }
        
        function displayInfo() {
            document.getElementById('info-name').textContent = manifestData.archive.name;
            document.getElementById('info-created').textContent = manifestData.archive.created.split('T')[0];
            document.getElementById('info-files').textContent = manifestData.inventory.total_files;
            document.getElementById('info-increments').textContent = (manifestData.structure.increments || []).length;
        }
        
        function scanChanges() {
            document.getElementById('loading').classList.add('active');
            document.getElementById('scan-btn').disabled = true;
            
            const formData = new FormData();
            formData.append('action', 'scan');
            formData.append('manifest', JSON.stringify(manifestData));
            
            fetch('', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => {
                document.getElementById('loading').classList.remove('active');
                document.getElementById('scan-btn').disabled = false;
                
                if (data.success) {
                    changesData = data.changes;
                    displayChanges();
                    document.getElementById('selection-section').classList.remove('hidden');
                    document.getElementById('actions-section').classList.remove('hidden');
                } else {
                    alert('Error: ' + data.error);
                }
            });
        }
        
        function displayChanges() {
            document.getElementById('new-count').textContent = changesData.new.length;
            document.getElementById('modified-count').textContent = changesData.modified.length;
            
            const container = document.getElementById('changes-tree');
            container.innerHTML = '';
            
            // Group files by domain
            const grouped = {};
            [...changesData.new, ...changesData.modified].forEach(file => {
                const domain = file.relative_path.split('/')[0];
                if (!grouped[domain]) grouped[domain] = [];
                grouped[domain].push(file);
            });
            
            // Render grouped tree
            for (const [domain, files] of Object.entries(grouped)) {
                const domainNode = createChangeNode('folder', domain, null);
                const domainChildren = document.createElement('div');
                domainChildren.className = 'tree-children';
                domainChildren.style.display = 'block';
                
                files.forEach(file => {
                    const fileNode = createChangeNode('file', file.filename, file);
                    domainChildren.appendChild(fileNode);
                });
                
                domainNode.appendChild(domainChildren);
                container.appendChild(domainNode);
            }
        }
        
        function createChangeNode(type, name, file) {
            const div = document.createElement('div');
            div.className = `tree-node ${type}`;
            
            if (type === 'folder') {
                const toggle = document.createElement('span');
                toggle.className = 'tree-toggle expanded';
                toggle.textContent = '▼';
                toggle.onclick = () => toggleBranch(toggle);
                div.appendChild(toggle);
                
                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.onchange = () => selectBranch(checkbox);
                div.appendChild(checkbox);
            } else {
                const spacer = document.createElement('span');
                spacer.className = 'tree-spacer';
                div.appendChild(spacer);
                
                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.dataset.path = file.relative_path;
                checkbox.onchange = () => toggleFile(file.relative_path, checkbox.checked);
                div.appendChild(checkbox);
            }
            
            const label = document.createElement('label');
            label.innerHTML = `<span class="${type}-icon">${type === 'folder' ? '📁' : '📄'}</span><span class="node-name">${name}</span>`;
            
            if (file) {
                const badge = document.createElement('span');
                badge.className = `badge ${file.previous_checksum ? 'modified' : 'new'}`;
                badge.textContent = file.previous_checksum ? 'MODIFIED' : 'NEW';
                label.appendChild(badge);
            }
            
            div.appendChild(label);
            return div;
        }
        
        function toggleFile(path, checked) {
            checked ? selectedFiles.add(path) : selectedFiles.delete(path);
            updateSelectionCount();
        }
        
        function updateSelectionCount() {
            document.getElementById('selected-count').textContent = selectedFiles.size;
            document.getElementById('create-btn').disabled = selectedFiles.size === 0;
        }
        
        function selectAllNew() {
            changesData.new.forEach(file => {
                selectedFiles.add(file.relative_path);
                const cb = document.querySelector(`input[data-path="${file.relative_path}"]`);
                if (cb) cb.checked = true;
            });
            updateSelectionCount();
        }
        
        function selectAllModified() {
            changesData.modified.forEach(file => {
                selectedFiles.add(file.relative_path);
                const cb = document.querySelector(`input[data-path="${file.relative_path}"]`);
                if (cb) cb.checked = true;
            });
            updateSelectionCount();
        }
        
        function selectAllChanges() {
            [...changesData.new, ...changesData.modified].forEach(file => {
                selectedFiles.add(file.relative_path);
                const cb = document.querySelector(`input[data-path="${file.relative_path}"]`);
                if (cb) cb.checked = true;
            });
            updateSelectionCount();
        }
        
        function clearSelection() {
            selectedFiles.clear();
            document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
            updateSelectionCount();
        }
        
        function createIncrement() {
            if (selectedFiles.size === 0) {
                alert('Please select at least one file');
                return;
            }
            
            document.getElementById('create-btn').disabled = true;
            document.getElementById('create-btn').textContent = 'Creating...';
            
            const formData = new FormData();
            formData.append('action', 'update');
            formData.append('manifest', JSON.stringify(manifestData));
            formData.append('selected_files', JSON.stringify(Array.from(selectedFiles)));
            formData.append('all_changes', JSON.stringify(changesData));
            
            fetch('', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => {
                document.getElementById('create-btn').disabled = false;
                document.getElementById('create-btn').textContent = '📦 Create Increment';
                
                if (data.success) {
                    document.getElementById('success-text').textContent = 
                        `Increment ${data.increment_number}: ${data.file_count} files packaged successfully.`;
                    document.getElementById('download-link').href = data.download_url;
                    document.getElementById('success-message').classList.add('active');
                } else {
                    alert('Error: ' + data.error);
                }
            });
        }
    </script>
</body>
</html>
