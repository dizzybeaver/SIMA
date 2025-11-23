<?php
/**
 * sima-export-tool.php
 * 
 * SIMA Knowledge Export Tool
 * Version: 4.3.1
 * Date: 2025-11-23
 * 
 * FINAL: All fixes maintained, NO JavaScript, NO CSS
 * Location: /support/php/
 */

ini_set('display_errors', '0');
error_reporting(E_ALL);
ini_set('log_errors', '1');

/**
 * Auto-detect SIMA root
 */
function findSIMARoot($startPath = null) {
    if ($startPath === null) {
        $startPath = __DIR__;
    }
    
    if (file_exists($startPath . '/generic') && 
        file_exists($startPath . '/platforms')) {
        return $startPath;
    }
    
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
 * Load required files
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
 * Get asset paths
 */
function getAssetPaths() {
    $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
    
    if (strpos($scriptDir, '/support/php') !== false) {
        return [
            'css' => $scriptDir . '/css/',
            'js' => $scriptDir . '/js/'
        ];
    }
    
    return [
        'css' => '/css/',
        'js' => '/js/'
    ];
}

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
            
            $directory = rtrim($directory, '/');
            if (!is_dir($directory)) {
                throw new Exception("Directory not found: {$directory}");
            }
            if (!is_readable($directory)) {
                throw new Exception("Directory not readable: {$directory}");
            }
            
            $validatedDir = realpath($directory);
            
            // Try to load required files - this validates it's a SIMA directory
            try {
                loadRequiredFiles($validatedDir);
            } catch (Exception $e) {
                throw new Exception("Not a valid SIMA directory: " . $e->getMessage());
            }
            
            // Detect version
            $detectedVersion = SIMAVersionUtils::detectVersion($validatedDir);
            if ($detectedVersion === 'unknown') {
                throw new Exception("Could not detect SIMA version");
            }
            
            $versionInfo = SIMAVersionUtils::getVersionInfo($validatedDir);
            $tree = SIMAVersionUtils::scanWithVersion($validatedDir, $versionInfo['version']);
            $stats = SIMAVersionUtils::getStats($validatedDir, $versionInfo['version']);
            
            echo json_encode([
                'success' => true,
                'tree' => $tree,
                'base_path' => $validatedDir,
                'version_info' => $versionInfo,
                'stats' => $stats
            ]);
            exit;
        }
        elseif ($action === 'export') {
            $baseDir = $_POST['base_directory'] ?? '';
            
            if (empty($baseDir) || !is_dir($baseDir)) {
                throw new Exception("Invalid base directory");
            }
            
            $validatedBase = realpath($baseDir);
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
            
            $result = createExportArchive($validatedBase, $archiveName, $description, $selectedPaths, $sourceVersion, $targetVersion);
            
            echo json_encode([
                'success' => true,
                'archive_name' => $result['archive_name'],
                'file_count' => $result['file_count'],
                'converted_count' => $result['converted_count'],
                'download_url' => $result['download_url']
            ]);
            exit;
        }
        else {
            throw new Exception("Unknown action: {$action}");
        }
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
        exit;
    }
}

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
                <input type="text" id="simaDirectory" value="<?= htmlspecialchars($defaultPath) ?>" placeholder="/home/joe/sima">
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
    
    <script src="<?= $ASSET_PATHS['js'] ?>sima-export-export.js"></script>
    <script src="<?= $ASSET_PATHS['js'] ?>sima-export-selection.js"></script>
    <script src="<?= $ASSET_PATHS['js'] ?>sima-export-render.js"></script>
    <script src="<?= $ASSET_PATHS['js'] ?>sima-export-scan.js"></script>
</body>
</html>
