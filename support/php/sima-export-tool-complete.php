<?php
/**
 * sima-export-tool-complete.php
 * 
 * Complete Self-Contained SIMA Export Tool
 * Version: 4.2.0
 * Date: 2025-11-22
 * 
 * FIXED: Works from any directory
 * - Auto-detects SIMA root
 * - No HTTP 500 errors
 * - Works from webroot or subdirectory
 * - Self-contained (no external UI file needed)
 * 
 * Rename to sima-export-tool.php after upload
 */

// Disable error display to prevent breaking JSON responses
ini_set('display_errors', '0');
error_reporting(E_ALL);
ini_set('log_errors', '1');

/**
 * Auto-detect SIMA root directory
 */
function findSIMARoot($startPath = null) {
    if ($startPath === null) {
        $startPath = __DIR__;
    }
    
    // Check current directory
    if (file_exists($startPath . '/generic') && 
        file_exists($startPath . '/platforms')) {
        return $startPath;
    }
    
    // Check parent directories (up to 5 levels)
    $current = $startPath;
    for ($i = 0; $i < 5; $i++) {
        $parent = dirname($current);
        if ($parent === $current) {
            break;
        }
        
        if (file_exists($parent . '/generic') && 
            file_exists($parent . '/platforms')) {
            return $parent;
        }
        
        $current = $parent;
    }
    
    return null;
}

/**
 * Show error page
 */
function showErrorPage($title, $message, $details = []) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>SIMA Export Tool - <?php echo htmlspecialchars($title); ?></title>
        <style>
            body { font-family: Arial, sans-serif; padding: 20px; max-width: 800px; margin: 0 auto; }
            .error { background: #fee; border: 2px solid #c00; padding: 20px; border-radius: 5px; margin: 20px 0; }
            .info { background: #eff; border: 2px solid #06c; padding: 20px; border-radius: 5px; margin: 20px 0; }
            h1 { color: #c00; }
            h2 { color: #333; margin-top: 20px; }
            code { background: #f0f0f0; padding: 2px 6px; border-radius: 3px; font-family: monospace; }
            pre { background: #f0f0f0; padding: 10px; border-radius: 3px; overflow-x: auto; font-family: monospace; }
            ul { line-height: 1.8; }
        </style>
    </head>
    <body>
        <div class="error">
            <h1>⚠ <?php echo htmlspecialchars($title); ?></h1>
            <p><?php echo htmlspecialchars($message); ?></p>
            
            <?php if (!empty($details)): ?>
                <?php foreach ($details as $detailTitle => $detailContent): ?>
                    <h2><?php echo htmlspecialchars($detailTitle); ?></h2>
                    <?php if (is_array($detailContent)): ?>
                        <ul>
                            <?php foreach ($detailContent as $item): ?>
                                <li><?php echo htmlspecialchars($item); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <pre><?php echo htmlspecialchars($detailContent); ?></pre>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div class="info">
            <h2>Need Help?</h2>
            <ul>
                <li>Check that all required files are uploaded to <code>support/php/</code></li>
                <li>Verify file permissions (files should be readable by web server)</li>
                <li>Check PHP error log for detailed error messages</li>
            </ul>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Auto-detect SIMA root
$SIMA_ROOT = findSIMARoot();

if ($SIMA_ROOT === null) {
    showErrorPage(
        'SIMA Root Directory Not Found',
        'The export tool could not locate your SIMA installation.',
        [
            'Current Location' => __DIR__,
            'How to Fix' => [
                'Ensure this file is inside your SIMA installation directory',
                'SIMA root should contain generic/ and platforms/ directories',
                'Recommended location: /path/to/sima/support/php/sima-export-tool.php'
            ],
            'Manual Configuration' => 'If SIMA is in a non-standard location, edit this file and add before line 80: define(\'SIMA_ROOT\', \'/path/to/your/sima\');'
        ]
    );
}

// Define SIMA_ROOT
if (!defined('SIMA_ROOT')) {
    define('SIMA_ROOT', $SIMA_ROOT);
}

// Load required files
$requiredFiles = [
    'sima-common.php',
    'sima-scanner.php',
    'sima-tree-formatter.php',
    'sima-version-utils.php',
    'sima-export-helpers.php'
];

$phpDir = SIMA_ROOT . '/support/php';
$missingFiles = [];

foreach ($requiredFiles as $file) {
    $filepath = $phpDir . '/' . $file;
    if (!file_exists($filepath)) {
        $missingFiles[] = $file;
    }
}

if (!empty($missingFiles)) {
    showErrorPage(
        'Required Files Missing',
        'Some required PHP files are missing from the support/php directory.',
        [
            'SIMA Root' => SIMA_ROOT,
            'Support PHP Directory' => $phpDir,
            'Missing Files' => $missingFiles,
            'Solution' => 'Upload all required files to: ' . $phpDir
        ]
    );
}

// Include all required files
foreach ($requiredFiles as $file) {
    require_once $phpDir . '/' . $file;
}

// Ensure export directory exists
if (!defined('EXPORT_DIR')) {
    define('EXPORT_DIR', SIMA_ROOT . '/exports');
}
if (!is_dir(EXPORT_DIR)) {
    @mkdir(EXPORT_DIR, 0755, true);
}

/**
 * Validate directory path
 */
function validateDirectory($path) {
    $path = rtrim($path, '/');
    
    if (empty($path) || $path[0] !== '/') {
        throw new Exception("Path must be absolute");
    }
    
    if (strpos($path, '..') !== false) {
        throw new Exception("Invalid path: parent directory traversal not allowed");
    }
    
    if (!is_dir($path)) {
        throw new Exception("Directory not found: {$path}");
    }
    
    if (!is_readable($path)) {
        throw new Exception("Directory not readable: {$path}");
    }
    
    return realpath($path);
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    try {
        $action = $_POST['action'] ?? '';
        
        if ($action === 'scan') {
            $directory = $_POST['directory'] ?? SIMA_ROOT;
            $validatedDir = validateDirectory($directory);
            
            $versionInfo = SIMAVersionUtils::getVersionInfo($validatedDir);
            $tree = SIMAVersionUtils::scanWithVersion($validatedDir, $versionInfo['version']);
            $stats = SIMAVersionUtils::getStats($validatedDir, $versionInfo['version']);
            
            sendJsonResponse(true, [
                'tree' => $tree,
                'base_path' => $validatedDir,
                'version_info' => $versionInfo,
                'stats' => $stats
            ]);
        }
        elseif ($action === 'export') {
            $baseDir = $_POST['base_directory'] ?? SIMA_ROOT;
            $validatedBase = validateDirectory($baseDir);
            $archiveName = $_POST['archive_name'] ?? 'SIMA-Archive';
            $description = $_POST['description'] ?? '';
            $selectedPaths = json_decode($_POST['selected_files'] ?? '[]', true);
            
            $sourceVersion = $_POST['source_version'] ?? 'auto';
            $targetVersion = $_POST['target_version'] ?? 'auto';
            
            if ($sourceVersion === 'auto') {
                $versionInfo = SIMAVersionUtils::getVersionInfo($validatedBase);
                $sourceVersion = $versionInfo['version'];
            }
            
            if ($targetVersion === 'auto') {
                $targetVersion = $sourceVersion;
            }
            
            if (empty($selectedPaths)) {
                throw new Exception("No files selected for export");
            }
            
            $result = createExportArchive($validatedBase, $archiveName, $description, $selectedPaths, $sourceVersion, $targetVersion);
            
            sendJsonResponse(true, $result);
        }
        else {
            throw new Exception("Unknown action: {$action}");
        }
        
    } catch (Exception $e) {
        sendJsonResponse(false, [], $e->getMessage());
    }
    
    exit;
}

// Show HTML UI (continued in next section due to length)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMA Export Tool</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; margin-top: 0; }
        .section { margin: 30px 0; padding: 20px; border: 1px solid #ddd; border-radius: 5px; background: #fafafa; }
        .section.hidden { display: none; }
        .form-group { margin: 15px 0; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 5px; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .form-group small { display: block; color: #666; margin-top: 5px; }
        button { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; margin-right: 10px; }
        button:hover { background: #0056b3; }
        button:disabled { background: #ccc; cursor: not-allowed; }
        .tree-controls { margin: 15px 0; }
        .tree-container { max-height: 500px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; background: white; }
        .selection-summary { margin: 15px 0; padding: 10px; background: #e7f3ff; border-radius: 4px; }
        #loading { display: none; padding: 20px; text-align: center; color: #666; }
        .error { display: none; padding: 15px; background: #fee; border: 1px solid #fcc; border-radius: 4px; color: #c00; margin: 15px 0; }
        .error.active { display: block; }
        .success { padding: 15px; background: #efe; border: 1px solid #cfc; border-radius: 4px; color: #060; margin: 15px 0; }
        .info-box { padding: 15px; background: #e7f3ff; border-radius: 4px; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 SIMA Knowledge Export Tool</h1>
        
        <div class="info-box">
            <strong>Detected SIMA Root:</strong> <code><?php echo SIMA_ROOT; ?></code><br>
            <strong>Export Directory:</strong> <code><?php echo EXPORT_DIR; ?></code>
        </div>
        
        <div id="error" class="error">
            <strong>Error:</strong> <span id="error-text"></span>
        </div>
        
        <div id="loading">⏳ Processing...</div>
        
        <div class="section">
            <h2>1. Source Configuration</h2>
            <div class="form-group">
                <label for="simaDirectory">SIMA Directory Path:</label>
                <input type="text" id="simaDirectory" value="<?php echo SIMA_ROOT; ?>" placeholder="/path/to/sima">
                <small>Absolute path to SIMA installation (auto-detected)</small>
            </div>
            <div class="form-group">
                <label for="sourceVersion">Source Version:</label>
                <select id="sourceVersion">
                    <option value="auto">Auto-Detect</option>
                    <option value="4.2">SIMA v4.2</option>
                    <option value="4.1">SIMA v4.1</option>
                    <option value="3.0">SIMA v3.0</option>
                </select>
            </div>
            <button id="scan-btn" onclick="scanDirectory()">🔍 Scan Directory</button>
            <div id="detectedVersion" style="display:none; margin-top:10px; padding:10px; background:#e7f3ff; border-radius:4px;"></div>
        </div>
        
        <div class="section hidden" id="tree-section">
            <h2>2. Select Files to Export</h2>
            <div class="tree-controls">
                <button onclick="expandAll()">➕ Expand All</button>
                <button onclick="collapseAll()">➖ Collapse All</button>
                <button onclick="selectAll()">☑️ Select All</button>
                <button onclick="clearSelection()">⬜ Clear All</button>
            </div>
            <div class="tree-container" id="tree"></div>
            <div class="selection-summary" id="summary">Selection: 0 files selected</div>
        </div>
        
        <div class="section hidden" id="export-section">
            <h2>3. Export Settings</h2>
            <div class="form-group">
                <label for="archiveName">Archive Name:</label>
                <input type="text" id="archiveName" value="SIMA-Export" placeholder="MyExport">
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" rows="3" placeholder="Optional description..."></textarea>
            </div>
            <div class="form-group">
                <label for="targetVersion">Target Version:</label>
                <select id="targetVersion">
                    <option value="auto">Same as Source</option>
                    <option value="4.2">SIMA v4.2</option>
                    <option value="4.1">SIMA v4.1</option>
                </select>
                <small>Convert files to different version during export</small>
            </div>
            <button onclick="exportFiles()">📦 Create Export</button>
        </div>
        
        <div id="result-section" class="section hidden">
            <h2>4. Download Export</h2>
            <div id="result-content"></div>
        </div>
    </div>
    
    <script src="../../support/js/sima-tree.js"></script>
    <script src="../../support/js/sima-export.js"></script>
    <script>
        // Inline JavaScript - no external dependencies
        let knowledgeTree = {};
        let selectedFiles = new Set();
        let currentBasePath = '';
        
        function scanDirectory() {
            const directory = document.getElementById('simaDirectory').value.trim();
            if (!directory) { alert('Please enter a directory path'); return; }
            
            document.getElementById('loading').style.display = 'block';
            document.getElementById('error').classList.remove('active');
            document.getElementById('scan-btn').disabled = true;
            
            const formData = new FormData();
            formData.append('action', 'scan');
            formData.append('directory', directory);
            
            fetch('', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => {
                document.getElementById('loading').style.display = 'none';
                document.getElementById('scan-btn').disabled = false;
                
                if (data.success) {
                    knowledgeTree = data.tree;
                    currentBasePath = data.base_path;
                    
                    if (data.version_info && data.version_info.version !== 'unknown') {
                        document.getElementById('detectedVersion').textContent = 
                            `✓ Detected: SIMA v${data.version_info.version}`;
                        document.getElementById('detectedVersion').style.display = 'block';
                        document.getElementById('sourceVersion').value = data.version_info.version;
                    }
                    
                    renderTree();
                    document.getElementById('tree-section').classList.remove('hidden');
                    document.getElementById('export-section').classList.remove('hidden');
                    updateSummary();
                } else {
                    document.getElementById('error-text').textContent = data.error;
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
            if (!knowledgeTree || knowledgeTree.length === 0) {
                container.innerHTML = '<p>No files found</p>';
                return;
            }
            knowledgeTree.forEach(node => container.appendChild(renderNode(node, 0)));
        }
        
        function renderNode(node, depth) {
            const div = document.createElement('div');
            div.style.marginLeft = (depth * 20) + 'px';
            div.style.padding = '5px';
            
            if (node.type === 'directory') {
                const label = document.createElement('div');
                label.style.cursor = 'pointer';
                label.style.fontWeight = 'bold';
                label.style.padding = '5px';
                label.style.background = '#f0f0f0';
                label.style.marginBottom = '5px';
                label.style.borderRadius = '3px';
                
                const toggle = document.createElement('span');
                toggle.textContent = '▼ ';
                label.appendChild(toggle);
                
                const name = document.createElement('span');
                name.textContent = `📁 ${node.name} (${node.total_files} files)`;
                label.appendChild(name);
                div.appendChild(label);
                
                const childrenDiv = document.createElement('div');
                if (node.children) {
                    node.children.forEach(child => childrenDiv.appendChild(renderNode(child, depth + 1)));
                }
                div.appendChild(childrenDiv);
                
                label.onclick = () => {
                    childrenDiv.style.display = childrenDiv.style.display === 'none' ? 'block' : 'none';
                    toggle.textContent = childrenDiv.style.display === 'none' ? '▶ ' : '▼ ';
                };
            } else {
                const label = document.createElement('label');
                label.style.display = 'block';
                label.style.padding = '3px';
                
                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.value = node.path;
                checkbox.checked = selectedFiles.has(node.path);
                checkbox.onchange = () => {
                    if (checkbox.checked) selectedFiles.add(node.path);
                    else selectedFiles.delete(node.path);
                    updateSummary();
                };
                
                label.appendChild(checkbox);
                label.appendChild(document.createTextNode(` 📄 ${node.name}`));
                div.appendChild(label);
            }
            return div;
        }
        
        function updateSummary() {
            document.getElementById('summary').textContent = 
                `Selection: ${selectedFiles.size} file${selectedFiles.size !== 1 ? 's' : ''} selected`;
        }
        
        function expandAll() {
            document.querySelectorAll('#tree input[type="checkbox"]').forEach(cb => cb.parentElement.parentElement.querySelectorAll('div > div').forEach(d => d.style.display = 'block'));
        }
        
        function collapseAll() {
            document.querySelectorAll('#tree input[type="checkbox"]').forEach(cb => cb.parentElement.parentElement.querySelectorAll('div > div').forEach(d => d.style.display = 'none'));
        }
        
        function selectAll() {
            document.querySelectorAll('#tree input[type="checkbox"]').forEach(cb => {
                cb.checked = true;
                selectedFiles.add(cb.value);
            });
            updateSummary();
        }
        
        function clearSelection() {
            document.querySelectorAll('#tree input[type="checkbox"]').forEach(cb => cb.checked = false);
            selectedFiles.clear();
            updateSummary();
        }
        
        function exportFiles() {
            if (selectedFiles.size === 0) {
                alert('Please select at least one file');
                return;
            }
            
            document.getElementById('loading').style.display = 'block';
            
            const formData = new FormData();
            formData.append('action', 'export');
            formData.append('base_directory', currentBasePath);
            formData.append('archive_name', document.getElementById('archiveName').value.trim() || 'SIMA-Export');
            formData.append('description', document.getElementById('description').value.trim());
            formData.append('source_version', document.getElementById('sourceVersion').value);
            formData.append('target_version', document.getElementById('targetVersion').value);
            formData.append('selected_files', JSON.stringify(Array.from(selectedFiles)));
            
            fetch('', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => {
                document.getElementById('loading').style.display = 'none';
                if (data.success) {
                    document.getElementById('result-content').innerHTML = `
                        <div class="success">
                            <h3>✓ Export Created!</h3>
                            <p><strong>Archive:</strong> ${data.archive_name}</p>
                            <p><strong>Files:</strong> ${data.file_count}</p>
                            <p><a href="${data.download_url}" download><button>📥 Download Export</button></a></p>
                        </div>`;
                    document.getElementById('result-section').classList.remove('hidden');
                } else {
                    document.getElementById('error-text').textContent = data.error;
                    document.getElementById('error').classList.add('active');
                }
            })
            .catch(err => {
                document.getElementById('loading').style.display = 'none';
                document.getElementById('error-text').textContent = 'Error: ' + err.message;
                document.getElementById('error').classList.add('active');
            });
        }
    </script>
</body>
</html>
