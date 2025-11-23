<?php
/**
 * sima-export-tool.php
 * 
 * SIMA Knowledge Export Tool
 * Version: 4.3.0
 * Date: 2025-11-23
 * 
 * Purpose: Export SIMA knowledge with version awareness
 * Location: /support/php/
 * 
 * MODIFIED: 
 * - Extracted JS to sima-export.js
 * - Extracted CSS to sima-styles.css
 * - Smart path detection for CSS/JS loading
 * - Directory validation on scan (not on load)
 * - ≤350 lines
 */

// Disable error display for clean JSON responses
ini_set('display_errors', '0');
error_reporting(E_ALL);
ini_set('log_errors', '1');

/**
 * Auto-detect SIMA root (optional - doesn't block UI)
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
        if ($parent === $current) break;
        
        if (file_exists($parent . '/generic') && 
            file_exists($parent . '/platforms')) {
            return $parent;
        }
        
        $current = $parent;
    }
    
    return null;
}

/**
 * Load required PHP files
 */
function loadRequiredFiles($simaRoot) {
    $required = [
        'sima-common.php',
        'sima-scanner.php',
        'sima-tree-formatter.php',
        'sima-version-utils.php',
        'sima-export-helpers.php'
    ];
    
    $phpDir = $simaRoot . '/support/php';
    
    if (!is_dir($phpDir)) {
        throw new Exception("Support directory not found: {$phpDir}");
    }
    
    $missing = [];
    foreach ($required as $file) {
        $filepath = $phpDir . '/' . $file;
        if (!file_exists($filepath)) {
            $missing[] = $file;
        }
    }
    
    if (!empty($missing)) {
        throw new Exception("Missing files: " . implode(', ', $missing));
    }
    
    foreach ($required as $file) {
        require_once $phpDir . '/' . $file;
    }
}

/**
 * Detect asset paths (CSS/JS) based on script location
 */
function getAssetPaths() {
    $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
    
    // If in /support/php/ directory
    if (strpos($scriptDir, '/support/php') !== false) {
        return [
            'css' => $scriptDir . '/css/',
            'js' => $scriptDir . '/js/'
        ];
    }
    
    // If in root or other location
    return [
        'css' => '/css/',
        'js' => '/js/'
    ];
}

// Try to auto-detect, but don't block if it fails
$AUTO_DETECTED_ROOT = findSIMARoot();
$ASSET_PATHS = getAssetPaths();

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    try {
        $action = $_POST['action'] ?? '';
        
        if ($action === 'scan') {
            $directory = $_POST['directory'] ?? '';
            
            if (empty($directory)) {
                throw new Exception("Please enter a SIMA directory path");
            }
            
            // Validate directory
            $directory = rtrim($directory, '/');
            if (!is_dir($directory)) {
                throw new Exception("Directory not found: {$directory}");
            }
            if (!is_readable($directory)) {
                throw new Exception("Directory not readable: {$directory}");
            }
            
            $validatedDir = realpath($directory);
            
            // Check if it looks like SIMA
            if (!file_exists($validatedDir . '/generic') || 
                !file_exists($validatedDir . '/platforms')) {
                throw new Exception("This doesn't appear to be a SIMA directory (missing generic/ or platforms/)");
            }
            
            // Load required files
            loadRequiredFiles($validatedDir);
            
            // Scan
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
            $baseDir = $_POST['base_directory'] ?? '';
            
            if (empty($baseDir) || !is_dir($baseDir)) {
                throw new Exception("Invalid base directory");
            }
            
            $validatedBase = realpath($baseDir);
            
            // Load required files
            loadRequiredFiles($validatedBase);
            
            $archiveName = $_POST['archive_name'] ?? 'SIMA-Export';
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
            
            $result = createExportArchive(
                $validatedBase, 
                $archiveName, 
                $description, 
                $selectedPaths, 
                $sourceVersion, 
                $targetVersion
            );
            
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

// Show HTML UI
$defaultPath = $AUTO_DETECTED_ROOT ?? '/home/joe/sima';
$autoDetectMsg = $AUTO_DETECTED_ROOT 
    ? "✓ Auto-detected: {$AUTO_DETECTED_ROOT}" 
    : "⚠ Could not auto-detect SIMA location - please enter path manually";
$autoDetectClass = $AUTO_DETECTED_ROOT ? 'success-msg' : 'warning-msg';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMA Export Tool</title>
    <link rel="stylesheet" href="<?= $ASSET_PATHS['css'] ?>sima-styles.css">
</head>
<body>
    <div class="container">
        <h1>📦 SIMA Knowledge Export Tool</h1>
        
        <div class="<?= $autoDetectClass ?>">
            <?= htmlspecialchars($autoDetectMsg) ?>
        </div>
        
        <div id="error" class="error">
            <strong>Error:</strong> <span id="error-text"></span>
        </div>
        
        <div id="loading">⏳ Processing...</div>
        
        <div class="section">
            <h2>1. SIMA Directory</h2>
            <div class="form-group">
                <label for="simaDirectory">SIMA Root Path:</label>
                <input type="text" 
                       id="simaDirectory" 
                       value="<?= htmlspecialchars($defaultPath) ?>" 
                       placeholder="/home/joe/sima">
                <small>Enter the full path to your SIMA installation (must contain generic/ and platforms/ directories)</small>
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
            <div id="detectedVersion" style="display:none; margin-top:10px;" class="success-msg"></div>
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
                <input type="text" 
                       id="archiveName" 
                       value="SIMA-Export" 
                       placeholder="MyExport">
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" 
                          rows="3" 
                          placeholder="Optional description..."></textarea>
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
    
    <script src="<?= $ASSET_PATHS['js'] ?>sima-export.js"></script>
</body>
</html>
