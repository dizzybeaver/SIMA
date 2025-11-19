<?php
/**
 * sima-archive-updater.php
 * 
 * SIMA Archive Updater Tool - Complete Version
 * Version: 2.0.0
 * Date: 2025-11-19
 * 
 * Combines: Original backend functionality + Collapsible tree UI + Archive visualization
 */

// Configuration
define('SIMA_ROOT', '/home/joe/sima');
define('SIMA_VERSION', '4.2.2');
define('EXPORT_DIR', '/tmp/sima-exports');

// Ensure export directory exists
if (!is_dir(EXPORT_DIR)) {
    mkdir(EXPORT_DIR, 0755, true);
}

// Include functions from export tool
require_once 'sima-export-tool.php';

/**
 * Parse manifest.yaml
 */
function parseManifest($content) {
    return yaml_parse($content);
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
                'ref_id' => $file['ref_id'] ?? null,
                'package' => $file['package'] ?? 'knowledge-base.zip',
                'status' => $file['status'] ?? 'original'
            ];
        }
    }
    
    return $inventory;
}

/**
 * Discover new and modified files
 */
function discoverChanges($archivedInventory) {
    $changes = [
        'new' => [],
        'modified' => [],
        'unchanged' => []
    ];
    
    $tree = scanKnowledgeTree(SIMA_ROOT);
    
    foreach ($tree as $domainName => $domain) {
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
                $relativePath = $file['relative_path'];
                $currentChecksum = $file['checksum'];
                
                if (!isset($archivedInventory[$relativePath])) {
                    $changes['new'][] = $file;
                } elseif ($archivedInventory[$relativePath]['checksum'] !== $currentChecksum) {
                    $file['previous_checksum'] = $archivedInventory[$relativePath]['checksum'];
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
    
    $newFileCount = 0;
    foreach ($selectedFiles as $file) {
        if ($file['change_type'] === 'new') {
            $newFileCount++;
        }
    }
    
    $manifest['inventory']['total_files'] += $newFileCount;
    
    foreach ($selectedFiles as $file) {
        $fileEntry = [
            'path' => $file['relative_path'],
            'ref_id' => $file['ref_id'],
            'category' => $file['category'],
            'checksum' => $file['checksum'],
            'size' => $file['size'],
            'sima_version' => SIMA_VERSION,
            'exported' => date('Y-m-d'),
            'package' => $incrementPackage,
            'status' => $file['change_type']
        ];
        
        if ($file['change_type'] === 'modified') {
            $fileEntry['previous_checksum'] = $file['previous_checksum'];
        }
        
        $found = false;
        for ($i = 0; $i < count($manifest['files']); $i++) {
            if ($manifest['files'][$i]['path'] === $file['relative_path']) {
                $manifest['files'][$i] = $fileEntry;
                $found = true;
                break;
            }
        }
        
        if (!$found) {
            $manifest['files'][] = $fileEntry;
        }
    }
    
    if (!isset($manifest['inventory']['categories'])) {
        $manifest['inventory']['categories'] = [];
    }
    
    foreach ($selectedFiles as $file) {
        $category = $file['category'];
        if (!isset($manifest['inventory']['categories'][$category])) {
            $manifest['inventory']['categories'][$category] = 0;
        }
        if ($file['change_type'] === 'new') {
            $manifest['inventory']['categories'][$category]++;
        }
    }
    
    return $manifest;
}

/**
 * Update import instructions with increment
 */
function updateImportInstructions($instructionsContent, $manifest, $selectedFiles) {
    $data = parseImportInstructions($instructionsContent);
    
    $data['metadata']['updated'] = date('Y-m-d');
    $data['metadata']['version'] = $manifest['archive']['version'];
    $data['metadata']['total_files'] = $manifest['inventory']['total_files'];
    $data['metadata']['selected_count'] = $manifest['inventory']['total_files'];
    
    foreach ($selectedFiles as $file) {
        $categoryPath = dirname($file['relative_path']);
        $found = false;
        
        foreach ($data['selected'] as &$category) {
            if ($category['path'] === $categoryPath) {
                $category['files'][] = [
                    'filename' => $file['filename'],
                    'target_path' => '/sima/' . $file['relative_path'],
                    'status' => ($file['change_type'] === 'new' ? 'NEW' : 'MODIFIED') . ' in v' . $manifest['archive']['version'],
                    'selected' => true
                ];
                $category['count']++;
                $found = true;
                break;
            }
        }
        
        if (!$found) {
            $data['selected'][] = [
                'path' => $categoryPath,
                'count' => 1,
                'files' => [[
                    'filename' => $file['filename'],
                    'target_path' => '/sima/' . $file['relative_path'],
                    'status' => ($file['change_type'] === 'new' ? 'NEW' : 'MODIFIED') . ' in v' . $manifest['archive']['version'],
                    'selected' => true
                ]]
            ];
        }
    }
    
    $newCount = count(array_filter($selectedFiles, fn($f) => $f['change_type'] === 'new'));
    $modCount = count(array_filter($selectedFiles, fn($f) => $f['change_type'] === 'modified'));
    
    $changeDesc = "Added " . count($selectedFiles) . " files ({$newCount} new, {$modCount} modified) in increment-" . 
                  str_pad(count($manifest['structure']['increments']), 2, '0', STR_PAD_LEFT);
    
    array_unshift($data['changelog'], [
        'date' => date('Y-m-d') . ' (v' . $manifest['archive']['version'] . ')',
        'description' => $changeDesc
    ]);
    
    $incrementNumber = count($manifest['structure']['increments']);
    $data['packages'][] = [
        'type' => 'Increment ' . str_pad($incrementNumber, 2, '0', STR_PAD_LEFT),
        'filename' => "knowledge-increment-{$incrementNumber}.zip",
        'file_count' => count($selectedFiles)
    ];
    
    return generateUpdatedInstructionsFromData($data);
}

/**
 * Generate import instructions from parsed data
 */
function generateUpdatedInstructionsFromData($data) {
    $md = "# Import Instructions - {$data['metadata']['archive']}\n\n";
    $md .= "**Archive:** {$data['metadata']['archive']}\n";
    $md .= "**Created:** {$data['metadata']['created']}\n";
    $md .= "**Updated:** {$data['metadata']['updated']}\n";
    $md .= "**Version:** {$data['metadata']['version']}\n";
    $md .= "**SIMA Version:** {$data['metadata']['sima_version']}\n";
    $md .= "**Total Files:** {$data['metadata']['total_files']}";
    
    if (isset($data['metadata']['version']) && $data['metadata']['version'] > 1) {
        $prevTotal = $data['metadata']['total_files'] - count(end($data['packages'])['file_count'] ?? 0);
        $added = $data['metadata']['total_files'] - $prevTotal;
        $md .= " (+{$added} since last version)";
    }
    $md .= "\n\n";
    
    $md .= "## Installation State\n\n";
    $md .= "### Selected for Install ({$data['metadata']['selected_count']} files)\n\n";
    
    foreach ($data['selected'] as $category) {
        $md .= "#### {$category['path']} ({$category['count']} files)\n";
        foreach ($category['files'] as $file) {
            $md .= "- [x] {$file['filename']} → {$file['target_path']}";
            if (!empty($file['status'])) {
                $md .= " ({$file['status']})";
            }
            $md .= "\n";
        }
        $md .= "\n";
    }
    
    $md .= "### Not Selected for Install (0 files)\n";
    $md .= "(None - all files selected)\n\n";
    
    $md .= "## Change Log\n";
    foreach ($data['changelog'] as $entry) {
        $md .= "- {$entry['date']}: {$entry['description']}\n";
    }
    $md .= "\n";
    
    $md .= "## Packages\n";
    foreach ($data['packages'] as $package) {
        $md .= "- {$package['type']}: {$package['filename']} ({$package['file_count']} files)\n";
    }
    $md .= "\n";
    
    $md .= "## Import Process\n";
    $md .= "1. Extract all package files\n";
    for ($i = 1; $i <= count($data['packages']); $i++) {
        if ($i === 1) {
            $md .= "   - knowledge-base.zip (base knowledge)\n";
        } else {
            $md .= "   - knowledge-increment-" . str_pad($i-1, 2, '0', STR_PAD_LEFT) . ".zip (updates/additions)\n";
        }
    }
    $md .= "2. Copy all files to target locations\n";
    $md .= "3. Note: Increment packages will overwrite modified files\n";
    $md .= "4. Use SIMA Import Mode for index updates\n";
    $md .= "5. Verify checksums against manifest\n";
    
    return $md;
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $action = $_POST['action'] ?? '';
    
    if ($action === 'load') {
        try {
            if (!isset($_FILES['manifest']) || !isset($_FILES['import_instructions'])) {
                throw new Exception("Missing required files");
            }
            
            $manifestContent = file_get_contents($_FILES['manifest']['tmp_name']);
            $instructionsContent = file_get_contents($_FILES['import_instructions']['tmp_name']);
            
            $manifest = parseManifest($manifestContent);
            $instructionsData = parseImportInstructions($instructionsContent);
            
            echo json_encode([
                'success' => true,
                'manifest' => $manifest,
                'instructions' => $instructionsData
            ]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }
    
    if ($action === 'scan') {
        try {
            $manifest = json_decode($_POST['manifest'], true);
            
            $archivedInventory = buildArchivedInventory($manifest);
            $changes = discoverChanges($archivedInventory);
            
            echo json_encode([
                'success' => true,
                'changes' => $changes
            ]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }
    
    if ($action === 'update') {
        try {
            $manifest = json_decode($_POST['manifest'], true);
            $instructionsContent = $_POST['instructions_content'];
            $selectedPaths = json_decode($_POST['selected_files'], true);
            
            $selectedFiles = [];
            $allChanges = json_decode($_POST['all_changes'], true);
            
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
                throw new Exception("No valid files selected");
            }
            
            $incrementNumber = count($manifest['structure']['increments'] ?? []) + 1;
            $incrementNumberStr = str_pad($incrementNumber, 2, '0', STR_PAD_LEFT);
            
            $exportId = uniqid();
            $exportPath = EXPORT_DIR . '/' . $exportId;
            mkdir($exportPath, 0755, true);
            
            $updatedManifest = updateManifest($manifest, $selectedFiles, $incrementNumber);
            $manifestYaml = yaml_emit($updatedManifest);
            file_put_contents($exportPath . '/manifest.yaml', $manifestYaml);
            
            $updatedInstructions = updateImportInstructions($instructionsContent, $updatedManifest, $selectedFiles);
            file_put_contents($exportPath . '/import-instructions.md', $updatedInstructions);
            
            $incrementZipPath = $exportPath . "/knowledge-increment-{$incrementNumberStr}.zip";
            $incrementPackageName = "knowledge-increment-{$incrementNumberStr}.zip";
            
            createArchiveZip($selectedFiles, $incrementZipPath, $incrementPackageName);
            
            $bundleZipPath = $exportPath . "/archive-update-{$incrementNumberStr}.zip";
            $bundleZip = new ZipArchive();
            $bundleZip->open($bundleZipPath, ZipArchive::CREATE);
            $bundleZip->addFile($exportPath . '/manifest.yaml', 'manifest.yaml');
            $bundleZip->addFile($exportPath . '/import-instructions.md', 'import-instructions.md');
            $bundleZip->addFile($incrementZipPath, $incrementPackageName);
            $bundleZip->close();
            
            echo json_encode([
                'success' => true,
                'download_url' => '/sima-exports/' . $exportId . '/' . basename($bundleZipPath),
                'file_count' => count($selectedFiles),
                'increment_number' => $incrementNumber
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📦 SIMA Archive Updater</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f5f5f5;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        header {
            background: #2c3e50;
            color: white;
            padding: 20px 30px;
        }
        
        header h1 {
            font-size: 24px;
            font-weight: 600;
        }
        
        .section {
            padding: 30px;
            border-bottom: 1px solid #eee;
        }
        
        .section:last-child {
            border-bottom: none;
        }
        
        h2 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #2c3e50;
        }
        
        .upload-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .upload-box {
            border: 2px dashed #ddd;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .upload-box:hover {
            border-color: #3498db;
            background: #f8f9fa;
        }
        
        .upload-box.uploaded {
            border-color: #27ae60;
            background: #d4edda;
        }
        
        input[type="file"] {
            display: none;
        }
        
        button {
            background: #3498db;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }
        
        button:hover {
            background: #2980b9;
        }
        
        button:disabled {
            background: #95a5a6;
            cursor: not-allowed;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }
        
        .info-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
        }
        
        .info-item label {
            display: block;
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .info-item .value {
            font-size: 16px;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .summary-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
        }
        
        .summary-card.new {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .summary-card.modified {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        .summary-card h3 {
            font-size: 14px;
            margin-bottom: 10px;
            opacity: 0.9;
        }
        
        .summary-card .count {
            font-size: 32px;
            font-weight: 700;
        }
        
        .tree {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 15px;
            max-height: 400px;
            overflow-y: auto;
        }
        
        .tree-item {
            margin: 5px 0;
            padding-left: 20px;
        }
        
        .tree-item.group {
            font-weight: 600;
            margin-top: 15px;
            padding-left: 0;
        }
        
        .tree-item input[type="checkbox"] {
            margin-right: 8px;
        }
        
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 500;
            margin-left: 8px;
        }
        
        .badge.new {
            background: #f8d7da;
            color: #721c24;
        }
        
        .badge.modified {
            background: #fff3cd;
            color: #856404;
        }
        
        .hidden {
            display: none;
        }
        
        .success-message {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
        }
        
        .success-message a {
            color: #155724;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>📦 SIMA Archive Updater</h1>
        </header>
        
        <div class="section" id="upload-section">
            <h2>Load Existing Archive</h2>
            <div class="upload-group">
                <div class="upload-box" id="manifest-box" onclick="document.getElementById('manifest-input').click()">
                    <p style="font-size: 36px; margin-bottom: 10px;">📄</p>
                    <p style="font-weight: 600;">manifest.yaml</p>
                    <p style="font-size: 12px; color: #666;">Click to upload</p>
                    <input type="file" id="manifest-input" accept=".yaml,.yml">
                </div>
                
                <div class="upload-box" id="instructions-box" onclick="document.getElementById('instructions-input').click()">
                    <p style="font-size: 36px; margin-bottom: 10px;">📋</p>
                    <p style="font-weight: 600;">import-instructions.md</p>
                    <p style="font-size: 12px; color: #666;">Click to upload</p>
                    <input type="file" id="instructions-input" accept=".md">
                </div>
            </div>
            <div style="text-align: center;">
                <button onclick="loadArchive()" id="load-btn" disabled>Load Archive</button>
            </div>
        </div>
        
        <div class="section hidden" id="info-section">
            <h2>Archive Information</h2>
            <div class="info-grid">
                <div class="info-item">
                    <label>Name</label>
                    <div class="value" id="info-name"></div>
                </div>
                <div class="info-item">
                    <label>Created</label>
                    <div class="value" id="info-created"></div>
                </div>
                <div class="info-item">
                    <label>Current Files</label>
                    <div class="value" id="info-files"></div>
                </div>
                <div class="info-item">
                    <label>Increments</label>
                    <div class="value" id="info-increments"></div>
                </div>
            </div>
        </div>
        
        <div class="section hidden" id="discovery-section">
            <h2>Discover New Knowledge</h2>
            <button onclick="scanChanges()" id="scan-btn">🔍 Scan Current SIMA</button>
        </div>
        
        <div class="section hidden" id="selection-section">
            <h2>Select Knowledge to Add</h2>
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
            
            <div id="changes-tree" class="tree">
                <!-- Dynamically populated -->
            </div>
        </div>
        
        <div class="section hidden" id="actions-section">
            <button onclick="createIncrement()" id="create-btn">📦 Create Increment Package</button>
            
            <div class="success-message hidden" id="success-message">
                <strong>✓ Increment Created!</strong>
                <p id="success-text"></p>
                <a href="#" id="download-link">Download Update Package</a>
            </div>
        </div>
    </div>
    
    <script>
        let manifestData = null;
        let instructionsContent = null;
        let manifestFile = null;
        let instructionsFile = null;
        let changesData = null;
        let selectedFiles = new Set();
        
        document.getElementById('manifest-input').addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                manifestFile = e.target.files[0];
                document.getElementById('manifest-box').classList.add('uploaded');
                checkReadyToLoad();
            }
        });
        
        document.getElementById('instructions-input').addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                instructionsFile = e.target.files[0];
                document.getElementById('instructions-box').classList.add('uploaded');
                checkReadyToLoad();
            }
        });
        
        function checkReadyToLoad() {
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
            
            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    manifestData = data.manifest;
                    instructionsContent = data.instructions;
                    
                    displayArchiveInfo();
                    
                    document.getElementById('info-section').classList.remove('hidden');
                    document.getElementById('discovery-section').classList.remove('hidden');
                } else {
                    alert('Error: ' + data.error);
                    document.getElementById('load-btn').disabled = false;
                    document.getElementById('load-btn').textContent = 'Load Archive';
                }
            });
        }
        
        function displayArchiveInfo() {
            document.getElementById('info-name').textContent = manifestData.archive.name;
            document.getElementById('info-created').textContent = manifestData.archive.created.split('T')[0];
            document.getElementById('info-files').textContent = manifestData.inventory.total_files;
            document.getElementById('info-increments').textContent = (manifestData.structure.increments || []).length;
        }
        
        function scanChanges() {
            document.getElementById('scan-btn').disabled = true;
            document.getElementById('scan-btn').textContent = 'Scanning...';
            
            const formData = new FormData();
            formData.append('action', 'scan');
            formData.append('manifest', JSON.stringify(manifestData));
            
            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    changesData = data.changes;
                    
                    displayChanges();
                    
                    document.getElementById('selection-section').classList.remove('hidden');
                    document.getElementById('actions-section').classList.remove('hidden');
                } else {
                    alert('Error: ' + data.error);
                }
                
                document.getElementById('scan-btn').disabled = false;
                document.getElementById('scan-btn').textContent = '🔍 Scan Current SIMA';
            });
        }
        
        function displayChanges() {
            document.getElementById('new-count').textContent = changesData.new.length;
            document.getElementById('modified-count').textContent = changesData.modified.length;
            
            const container = document.getElementById('changes-tree');
            container.innerHTML = '';
            
            const grouped = {};
            
            [...changesData.new, ...changesData.modified].forEach(file => {
                const category = dirname(file.relative_path);
                if (!grouped[category]) {
                    grouped[category] = { new: [], modified: [] };
                }
                
                if (file.previous_checksum) {
                    grouped[category].modified.push(file);
                } else {
                    grouped[category].new.push(file);
                }
            });
            
            for (const [category, files] of Object.entries(grouped)) {
                const totalFiles = files.new.length + files.modified.length;
                
                const groupDiv = document.createElement('div');
                groupDiv.className = 'tree-item group';
                groupDiv.innerHTML = `<input type="checkbox" id="group-${category}" onchange="toggleGroup('${category}', this.checked)"> <label for="group-${category}">${category} (${totalFiles} files)</label>`;
                container.appendChild(groupDiv);
                
                files.new.forEach(file => {
                    const fileDiv = document.createElement('div');
                    fileDiv.className = 'tree-item';
                    fileDiv.innerHTML = `<input type="checkbox" id="file-${file.relative_path}" data-path="${file.relative_path}" onchange="toggleFile('${file.relative_path}', this.checked)"> <label for="file-${file.relative_path}">${file.filename}<span class="badge new">NEW</span></label>`;
                    container.appendChild(fileDiv);
                });
                
                files.modified.forEach(file => {
                    const fileDiv = document.createElement('div');
                    fileDiv.className = 'tree-item';
                    fileDiv.innerHTML = `<input type="checkbox" id="file-${file.relative_path}" data-path="${file.relative_path}" onchange="toggleFile('${file.relative_path}', this.checked)"> <label for="file-${file.relative_path}">${file.filename}<span class="badge modified">MODIFIED</span></label>`;
                    container.appendChild(fileDiv);
                });
            }
        }
        
        function dirname(path) {
            return path.substring(0, path.lastIndexOf('/'));
        }
        
        function toggleGroup(category, checked) {
            document.querySelectorAll(`#changes-tree input[type="checkbox"]`).forEach(cb => {
                if (cb.dataset.path && dirname(cb.dataset.path) === category) {
                    cb.checked = checked;
                    toggleFile(cb.dataset.path, checked);
                }
            });
        }
        
        function toggleFile(path, checked) {
            if (checked) {
                selectedFiles.add(path);
            } else {
                selectedFiles.delete(path);
            }
            document.getElementById('selected-count').textContent = selectedFiles.size;
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
            formData.append('instructions_content', JSON.stringify(instructionsContent));
            formData.append('selected_files', JSON.stringify(Array.from(selectedFiles)));
            formData.append('all_changes', JSON.stringify(changesData));
            
            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('success-text').textContent = 
                        `Successfully created increment ${data.increment_number} with ${data.file_count} files.`;
                    document.getElementById('download-link').href = data.download_url;
                    document.getElementById('success-message').classList.remove('hidden');
                } else {
                    alert('Error: ' + data.error);
                }
                
                document.getElementById('create-btn').disabled = false;
                document.getElementById('create-btn').textContent = '📦 Create Increment Package';
            });
        }
    </script>
</body>
</html>
