<?php
/**
 * sima-export-tool.php
 * 
 * Version: 1.6.1
 * Date: 2025-11-23
 * Purpose: SIMA directory scanner and export tool
 * 
 * FIXED: Proper modular structure, version detection, and file organization
 */

// Buffer all output
ob_start();

// Error handling
ini_set('display_errors', '0');
error_reporting(E_ALL);
ini_set('log_errors', '1');

// Define export directory in allowed path
if (!defined('EXPORT_DIR')) {
    define('EXPORT_DIR', '/tmp/sima-exports');
}

/**
 * Load required PHP helper files
 */
function loadRequiredFiles() {
    $required = [
        'sima-common.php',        // Shared functions
        'sima-scanner.php',       // Recursive directory scanning
        'sima-tree-formatter.php', // Tree formatting for UI
        'sima-version-utils.php', // Version detection and conversion
        'sima-export-helpers.php' // Export archive creation
    ];
    
    $phpDir = __DIR__;
    
    // Check all files exist first
    $missing = [];
    foreach ($required as $file) {
        $filepath = $phpDir . '/' . $file;
        if (!file_exists($filepath)) {
            $missing[] = $file;
        }
    }
    
    if (!empty($missing)) {
        return ['success' => false, 'error' => 'Missing required files: ' . implode(', ', $missing)];
    }
    
    // Load all files
    foreach ($required as $file) {
        require_once $phpDir . '/' . $file;
    }
    
    return ['success' => true];
}

/**
 * Handle scan action
 */
function handleScanAction($directory) {
    $directory = rtrim($directory, '/');
    
    // Validate directory exists and is readable
    if (!is_dir($directory)) {
        return ['success' => false, 'error' => "Directory not found: {$directory}"];
    }
    if (!is_readable($directory)) {
        return ['success' => false, 'error' => "Directory not readable: {$directory}"];
    }
    
    $validatedDir = realpath($directory);
    
    // Detect SIMA version
    $version = SIMAVersionUtils::detectVersion($validatedDir);
    
    if ($version === 'unknown') {
        return ['success' => false, 'error' => 'Could not detect SIMA version (not a valid SIMA directory)'];
    }
    
    // Scan directory with version awareness
    $scanResult = SIMAVersionUtils::scanWithVersion($validatedDir, $version);
    
    // Get statistics
    $stats = SIMAVersionUtils::getStats($validatedDir, $version);
    
    // Format for UI
    $formattedTree = SIMATreeFormatter::formatForUI($scanResult);
    
    return [
        'success' => true,
        'version_info' => [
            'version' => $version,
            'version_string' => SIMAVersionUtils::getSpec($version) ? SIMAVersionUtils::getSpec($version)::VERSION : 'Unknown'
        ],
        'base_path' => $validatedDir,
        'stats' => $stats,
        'tree' => $formattedTree
    ];
}

/**
 * Handle export action
 */
function handleExportAction($postData) {
    $directory = $postData['base_directory'] ?? '';
    $archiveName = $postData['archive_name'] ?? 'SIMA-Export';
    $description = $postData['description'] ?? '';
    $sourceVersion = $postData['source_version'] ?? '4.2';
    $targetVersion = $postData['target_version'] ?? '4.2';
    $selectedFiles = json_decode($postData['selected_files'] ?? '[]', true);
    
    if (empty($directory)) {
        return ['success' => false, 'error' => 'No directory specified'];
    }
    
    $validatedDir = realpath($directory);
    if (!$validatedDir || !is_dir($validatedDir)) {
        return ['success' => false, 'error' => 'Invalid directory'];
    }
    
    // Create export directory if needed
    if (!is_dir(EXPORT_DIR)) {
        if (!mkdir(EXPORT_DIR, 0777, true)) {
            return ['success' => false, 'error' => 'Could not create export directory'];
        }
    }
    
    // Generate export archive
    try {
        $exportResult = createExportArchive(
            $validatedDir,
            $archiveName,
            $description,
            $selectedFiles,
            $sourceVersion,
            $targetVersion
        );
        
        return [
            'success' => true,
            'archive_name' => $exportResult['archive_name'],
            'file_count' => $exportResult['file_count'],
            'converted_count' => $exportResult['converted_count'],
            'download_url' => $exportResult['download_url']
        ];
    } catch (Exception $e) {
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

/**
 * Get asset paths based on script location
 */
function getAssetPaths() {
    $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
    
    // If in /support/php/, assets are in /support/php/js/ and /support/php/css/
    if (strpos($scriptDir, '/support/php') !== false) {
        return [
            'css' => $scriptDir . '/css/',
            'js' => $scriptDir . '/js/'
        ];
    }
    
    // If in root, assets are in /js/ and /css/
    return [
        'css' => '/css/',
        'js' => '/js/'
    ];
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    ob_clean();
    header('Content-Type: application/json');
    
    // Load helper files
    $loadResult = loadRequiredFiles();
    if (!$loadResult['success']) {
        echo json_encode($loadResult);
        exit;
    }
    
    $action = $_POST['action'] ?? '';
    
    try {
        if ($action === 'scan') {
            $directory = $_POST['directory'] ?? '';
            $result = handleScanAction($directory);
            echo json_encode($result);
            
        } elseif ($action === 'export') {
            $result = handleExportAction($_POST);
            echo json_encode($result);
            
        } else {
            echo json_encode(['success' => false, 'error' => "Unknown action: {$action}"]);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    
    exit;
}

// Render HTML interface
ob_clean();
$ASSET_PATHS = getAssetPaths();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMA Export Tool</title>
    <link rel="stylesheet" href="<?php echo $ASSET_PATHS['css']; ?>sima-export-tool.css">
</head>
<body>
    <div class="container">
        <h1>SIMA Export Tool</h1>
        <p class="subtitle">Scan and export SIMA knowledge base</p>
        
        <!-- Directory Input -->
        <div class="section">
            <h2>1. Select SIMA Directory</h2>
            <div class="input-group">
                <input type="text" 
                       id="simaDirectory" 
                       placeholder="/full/path/to/sima" 
                       class="directory-input">
                <button onclick="scanDirectory()" id="scan-btn" class="btn-primary">Scan Directory</button>
            </div>
            <p class="help-text">
                Enter the full absolute path to your SIMA directory<br>
                Examples: /home/joe/sima, /home/joe/simav4, /home/joe/simav3
            </p>
            <div id="detectedVersion" class="version-info" style="display: none;"></div>
        </div>
        
        <!-- Status Messages -->
        <div id="error" class="error-message">
            <div id="error-text"></div>
        </div>
        
        <div id="loading" class="loading" style="display: none;">
            <div class="spinner"></div>
            <div>Scanning directory...</div>
        </div>
        
        <!-- Scan Results -->
        <div id="tree-section" class="section hidden">
            <h2>2. Select Files to Export</h2>
            <div class="selection-controls">
                <button onclick="selectAll()" class="btn-secondary">Select All</button>
                <button onclick="clearSelection()" class="btn-secondary">Clear Selection</button>
                <span id="summary" class="selection-summary">Selection: 0 files selected</span>
            </div>
            <div id="tree" class="tree-container"></div>
        </div>
        
        <!-- Export Options -->
        <div id="export-section" class="section hidden">
            <h2>3. Export Options</h2>
            <div class="export-form">
                <div class="form-group">
                    <label for="archiveName">Archive Name:</label>
                    <input type="text" id="archiveName" value="SIMA-Export" class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" placeholder="Optional description of this export" class="form-control"></textarea>
                </div>
                
                <div class="version-selection">
                    <div class="form-group">
                        <label for="sourceVersion">Source Version:</label>
                        <select id="sourceVersion" class="form-control">
                            <option value="4.2">SIMA v4.2</option>
                            <option value="4.1">SIMA v4.1</option>
                            <option value="3.0">SIMA v3.0</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="targetVersion">Target Version:</label>
                        <select id="targetVersion" class="form-control">
                            <option value="4.2">SIMA v4.2</option>
                            <option value="4.1">SIMA v4.1</option>
                            <option value="3.0">SIMA v3.0</option>
                        </select>
                    </div>
                </div>
                
                <button onclick="exportFiles()" class="btn-success">Create Export</button>
            </div>
        </div>
        
        <!-- Results Section -->
        <div id="result-section" class="section hidden">
            <h2>Export Results</h2>
            <div id="result-content"></div>
        </div>
    </div>
    
    <!-- JavaScript Files -->
    <script src="<?php echo $ASSET_PATHS['js']; ?>sima-export-scan.js"></script>
    <script src="<?php echo $ASSET_PATHS['js']; ?>sima-export-render.js"></script>
    <script src="<?php echo $ASSET_PATHS['js']; ?>sima-export-selection.js"></script>
    <script src="<?php echo $ASSET_PATHS['js']; ?>sima-export-export.js"></script>
</body>
</html>
