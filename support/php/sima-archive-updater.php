<?php
/**
 * sima-archive-updater.php
 * 
 * SIMA Archive Updater Tool - Refactored
 * Version: 2.1.0
 * Date: 2025-11-19
 */

require_once __DIR__ . '/../support/php/sima-common.php';

// Ensure export directory exists
if (!is_dir(EXPORT_DIR)) {
    mkdir(EXPORT_DIR, 0755, true);
}

/**
 * Parse manifest.yaml
 */
function parseManifest($content) {
    return yaml_parse($content);
}

/**
 * Build archived file inventory
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
 * Discover changes
 */
function discoverChanges($archivedInventory) {
    $changes = ['new' => [], 'modified' => [], 'unchanged' => []];
    $tree = scanKnowledgeTree(SIMA_ROOT);
    
    foreach ($tree as $domain) {
        scanDomainForChanges($domain, $archivedInventory, $changes);
    }
    
    return $changes;
}

/**
 * Scan domain for changes
 */
function scanDomainForChanges($domain, $archivedInventory, &$changes) {
    if (isset($domain['categories'])) {
        foreach ($domain['categories'] as $category) {
            foreach ($category['files'] as $file) {
                $path = $file['relative_path'];
                $checksum = $file['checksum'];
                
                if (!isset($archivedInventory[$path])) {
                    $changes['new'][] = $file;
                } elseif ($archivedInventory[$path]['checksum'] !== $checksum) {
                    $file['previous_checksum'] = $archivedInventory[$path]['checksum'];
                    $changes['modified'][] = $file;
                } else {
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
 * Update manifest with increment
 */
function updateManifest($manifest, $selectedFiles, $incrementNumber) {
    $manifest['archive']['updated'] = date('c');
    $manifest['archive']['version'] = ($manifest['archive']['version'] ?? 1) + 1;
    
    $incrementPackage = "knowledge-increment-{$incrementNumber}.zip";
    $manifest['structure']['increments'][] = [
        'package' => $incrementPackage,
        'created' => date('c'),
        'files' => count($selectedFiles)
    ];
    
    foreach ($selectedFiles as $file) {
        if ($file['change_type'] === 'new') {
            $manifest['inventory']['total_files']++;
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
                throw new Exception("Missing files");
            }
            
            $manifestContent = file_get_contents($_FILES['manifest']['tmp_name']);
            $instructionsContent = file_get_contents($_FILES['import_instructions']['tmp_name']);
            
            $manifest = parseManifest($manifestContent);
            $instructions = parseImportInstructions($instructionsContent);
            
            sendJsonResponse(true, ['manifest' => $manifest, 'instructions' => $instructions]);
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
            
            $incrementNumber = count($manifest['structure']['increments'] ?? []) + 1;
            $exportId = uniqid();
            $exportPath = EXPORT_DIR . '/' . $exportId;
            mkdir($exportPath, 0755, true);
            
            $updatedManifest = updateManifest($manifest, $selectedFiles, $incrementNumber);
            file_put_contents($exportPath . '/manifest.yaml', yaml_emit($updatedManifest));
            
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
    <link rel="stylesheet" href="../support/css/sima-styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>📦 SIMA Archive Updater</h1>
        </header>
        
        <div class="section" id="upload-section">
            <h2>Load Archive</h2>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="upload-box" id="manifest-box" onclick="document.getElementById('manifest-input').click()">
                    <p style="font-size: 36px;">📄</p>
                    <p style="font-weight: 600;">manifest.yaml</p>
                    <input type="file" id="manifest-input" accept=".yaml,.yml">
                </div>
                <div class="upload-box" id="instructions-box" onclick="document.getElementById('instructions-input').click()">
                    <p style="font-size: 36px;">📋</p>
                    <p style="font-weight: 600;">import-instructions.md</p>
                    <input type="file" id="instructions-input" accept=".md">
                </div>
            </div>
            <div style="text-align: center;">
                <button onclick="loadArchive()" id="load-btn" disabled>Load Archive</button>
            </div>
        </div>
        
        <div class="section hidden" id="info-section">
            <h2>Archive Info</h2>
            <div class="info-grid">
                <div class="info-item"><label>Name</label><div class="value" id="info-name"></div></div>
                <div class="info-item"><label>Created</label><div class="value" id="info-created"></div></div>
                <div class="info-item"><label>Files</label><div class="value" id="info-files"></div></div>
                <div class="info-item"><label>Increments</label><div class="value" id="info-increments"></div></div>
            </div>
        </div>
        
        <div class="section hidden" id="discovery-section">
            <button onclick="scanChanges()" id="scan-btn">🔍 Scan Current SIMA</button>
        </div>
        
        <div class="section hidden" id="selection-section">
            <h2>Select Changes</h2>
            <div class="summary-cards">
                <div class="summary-card new"><h3>New Files</h3><div class="count" id="new-count">0</div></div>
                <div class="summary-card modified"><h3>Modified</h3><div class="count" id="modified-count">0</div></div>
                <div class="summary-card"><h3>Selected</h3><div class="count" id="selected-count">0</div></div>
            </div>
            <div id="changes-tree" class="tree"></div>
        </div>
        
        <div class="section hidden" id="actions-section">
            <button onclick="createIncrement()" id="create-btn">📦 Create Increment</button>
            <div class="success-message hidden" id="success-message">
                <strong>✓ Increment Created!</strong>
                <p id="success-text"></p>
                <a href="#" id="download-link">Download</a>
            </div>
        </div>
    </div>
    
    <script src="../support/js/sima-tree.js"></script>
    <script>
        let manifestData = null, instructionsContent = null, changesData = null, selectedFiles = new Set();
        let manifestFile = null, instructionsFile = null;
        
        document.getElementById('manifest-input').addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                manifestFile = e.target.files[0];
                document.getElementById('manifest-box').classList.add('uploaded');
                checkReady();
            }
        });
        
        document.getElementById('instructions-input').addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                instructionsFile = e.target.files[0];
                document.getElementById('instructions-box').classList.add('uploaded');
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
            
            fetch('', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    manifestData = data.manifest;
                    instructionsContent = data.instructions;
                    displayInfo();
                    document.getElementById('info-section').classList.remove('hidden');
                    document.getElementById('discovery-section').classList.remove('hidden');
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
            const formData = new FormData();
            formData.append('action', 'scan');
            formData.append('manifest', JSON.stringify(manifestData));
            
            fetch('', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    changesData = data.changes;
                    displayChanges();
                    document.getElementById('selection-section').classList.remove('hidden');
                    document.getElementById('actions-section').classList.remove('hidden');
                }
            });
        }
        
        function displayChanges() {
            document.getElementById('new-count').textContent = changesData.new.length;
            document.getElementById('modified-count').textContent = changesData.modified.length;
            
            const container = document.getElementById('changes-tree');
            container.innerHTML = '';
            
            [...changesData.new, ...changesData.modified].forEach(file => {
                const div = document.createElement('div');
                div.className = 'tree-item';
                div.innerHTML = `
                    <input type="checkbox" id="file-${file.relative_path}" onchange="toggleFile('${file.relative_path}', this.checked)">
                    <label for="file-${file.relative_path}">${file.filename}
                        <span class="badge ${file.previous_checksum ? 'modified' : 'new'}">
                            ${file.previous_checksum ? 'MODIFIED' : 'NEW'}
                        </span>
                    </label>
                `;
                container.appendChild(div);
            });
        }
        
        function toggleFile(path, checked) {
            checked ? selectedFiles.add(path) : selectedFiles.delete(path);
            document.getElementById('selected-count').textContent = selectedFiles.size;
        }
        
        function createIncrement() {
            if (selectedFiles.size === 0) {
                alert('Select at least one file');
                return;
            }
            
            const formData = new FormData();
            formData.append('action', 'update');
            formData.append('manifest', JSON.stringify(manifestData));
            formData.append('selected_files', JSON.stringify(Array.from(selectedFiles)));
            formData.append('all_changes', JSON.stringify(changesData));
            
            fetch('', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('success-text').textContent = 
                        `Increment ${data.increment_number}: ${data.file_count} files`;
                    document.getElementById('download-link').href = data.download_url;
                    document.getElementById('success-message').classList.remove('hidden');
                }
            });
        }
    </script>
</body>
</html>
