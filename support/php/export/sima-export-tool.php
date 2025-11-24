<?php
/**
 * sima-export-tool.php
 * 
 * Version: 1.6.0
 * Date: 2025-11-23
 * Purpose: SIMA directory scanner and export tool
 * 
 * FIXED: open_basedir violations, proper file structure
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
 * CRITICAL: Loads sima-common.php FIRST
 */
function loadRequiredFiles() {
    $required = [
        'sima-common.php',        // FIRST - has sendJsonResponse()
        'sima-scanner.php',       // Recursive directory scanning
        'sima-tree-formatter.php', // Tree formatting for UI
        'sima-version-utils.php', // Version detection (needs spec files)
        'sima-export-helpers.php' // Export archive creation
    ];
    
    // All files in same directory as this script
    $phpDir = __DIR__;
    
    // Check all files exist first
    $missing = [];
    foreach ($required as $file) {
        $filepath = $phpDir . '/' . $file;
        if (!file_exists($filepath)) {
            $missing[] = $file;
        }
    }
    
    // If any missing, return clean JSON error
    if (!empty($missing)) {
        ob_clean();
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'error' => 'Missing required files: ' . implode(', ', $missing)
        ]);
        exit;
    }
    
    // Load all files
    foreach ($required as $file) {
        require_once $phpDir . '/' . $file;
    }
}

/**
 * Detect asset paths based on script location
 * js/ and css/ are subdirectories adjacent to PHP files
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

$ASSET_PATHS = getAssetPaths();

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    ob_clean();
    header('Content-Type: application/json');
    
    try {
        // Load helper files FIRST (includes sima-common.php with sendJsonResponse)
        loadRequiredFiles();
        
        $action = $_POST['action'] ?? '';
        
        if ($action === 'scan') {
            $directory = $_POST['directory'] ?? '';
            
            if (empty($directory)) {
                sendJsonResponse(false, 'Please enter a SIMA directory path');
            }
            
            $directory = rtrim($directory, '/');
            
            // Validate directory exists and is readable
            if (!is_dir($directory)) {
                sendJsonResponse(false, "Directory not found: {$directory}");
            }
            if (!is_readable($directory)) {
                sendJsonResponse(false, "Directory not readable: {$directory}");
            }
            
            $validatedDir = realpath($directory);
            
            // Detect SIMA version (uses spec files)
            $version = SIMAVersionUtils::detectVersion($validatedDir);
            
            if ($version === 'unknown') {
                sendJsonResponse(false, 'Could not detect SIMA version (not a valid SIMA directory)');
            }
            
            // Scan directory with version awareness
            $scanResult = SIMAVersionUtils::scanWithVersion($validatedDir, $version);
            
            // Get statistics
            $stats = SIMAVersionUtils::getStats($scanResult);
            
            // Format for UI
            $formattedTree = SIMATreeFormatter::formatForUI($scanResult);
            
            sendJsonResponse(true, 'Scan complete', [
                'version' => $version,
                'path' => $validatedDir,
                'stats' => $stats,
                'tree' => $formattedTree
            ]);
            
        } elseif ($action === 'export') {
            $directory = $_POST['directory'] ?? '';
            $format = $_POST['format'] ?? 'zip';
            $selectedItems = json_decode($_POST['selectedItems'] ?? '[]', true);
            
            if (empty($directory)) {
                sendJsonResponse(false, 'No directory specified');
            }
            
            $validatedDir = realpath($directory);
            if (!$validatedDir || !is_dir($validatedDir)) {
                sendJsonResponse(false, 'Invalid directory');
            }
            
            // Create export directory if needed
            if (!is_dir(EXPORT_DIR)) {
                if (!mkdir(EXPORT_DIR, 0777, true)) {
                    sendJsonResponse(false, 'Could not create export directory');
                }
            }
            
            // Generate export archive
            $exportFile = SIMAExportHelpers::createExport(
                $validatedDir,
                $selectedItems,
                $format,
                EXPORT_DIR
            );
            
            if (!$exportFile) {
                sendJsonResponse(false, 'Export creation failed');
            }
            
            $downloadUrl = '/tmp/sima-exports/' . basename($exportFile);
            
            sendJsonResponse(true, 'Export created successfully', [
                'file' => basename($exportFile),
                'downloadUrl' => $downloadUrl,
                'format' => $format
            ]);
            
        } else {
            sendJsonResponse(false, "Unknown action: {$action}");
        }
        
    } catch (Exception $e) {
        sendJsonResponse(false, $e->getMessage());
    }
    
    exit;
}

// Render HTML interface
ob_clean();
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
                <button onclick="scanDirectory()" class="btn-primary">Scan Directory</button>
            </div>
            <p class="help-text">
                Enter the full absolute path to your SIMA directory<br>
                Examples: /home/joe/sima, /home/joe/simav4, /home/joe/simav3
            </p>
        </div>
        
        <!-- Status Messages -->
        <div id="statusMessage" class="status-message" style="display: none;"></div>
        
        <!-- Scan Results -->
        <div id="scanResults" style="display: none;">
            <div class="section">
                <h2>2. Scan Results</h2>
                <div id="versionInfo" class="info-box"></div>
                <div id="statsInfo" class="stats-grid"></div>
            </div>
            
            <!-- File Tree -->
            <div class="section">
                <h2>3. Select Files to Export</h2>
                <div class="tree-controls">
                    <button onclick="selectAll()" class="btn-secondary">Select All</button>
                    <button onclick="deselectAll()" class="btn-secondary">Deselect All</button>
                    <button onclick="expandAll()" class="btn-secondary">Expand All</button>
                    <button onclick="collapseAll()" class="btn-secondary">Collapse All</button>
                </div>
                <div id="fileTree" class="file-tree"></div>
            </div>
            
            <!-- Export Options -->
            <div class="section">
                <h2>4. Export Options</h2>
                <div class="export-options">
                    <label>
                        <input type="radio" name="format" value="zip" checked> 
                        ZIP Archive
                    </label>
                    <label>
                        <input type="radio" name="format" value="tar"> 
                        TAR Archive
                    </label>
                    <label>
                        <input type="radio" name="format" value="json"> 
                        JSON Manifest
                    </label>
                </div>
                <button onclick="exportSelected()" class="btn-success">Export Selected Files</button>
            </div>
        </div>
        
        <!-- Progress Indicator -->
        <div id="progressIndicator" class="progress" style="display: none;">
            <div class="progress-bar"></div>
            <div class="progress-text">Processing...</div>
        </div>
    </div>
    
    <script src="<?php echo $ASSET_PATHS['js']; ?>sima-export-tool.js"></script>
</body>
</html>
