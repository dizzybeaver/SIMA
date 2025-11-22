<?php
/**
 * sima-export-tool.php
 * 
 * SIMA Knowledge Export Tool - Version-Aware
 * Version: 4.1.0
 * Date: 2025-11-22
 * 
 * FIXED: Works from any directory, auto-detects paths
 * - No HTTP 500 errors
 * - Auto-finds SIMA root
 * - Works from webroot or subdirectory
 * - Better error reporting
 * 
 * CRITICAL: Stays under 350 lines
 */

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', '1');

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
            break; // Reached root
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
 * Load required files with error handling
 */
function loadRequiredFiles($simaRoot) {
    $required = [
        'sima-common.php',
        'sima-scanner.php',
        'sima-tree-formatter.php',
        'sima-version-utils.php'
    ];
    
    $phpDir = $simaRoot . '/support/php';
    
    if (!is_dir($phpDir)) {
        throw new Exception("PHP support directory not found: {$phpDir}");
    }
    
    foreach ($required as $file) {
        $filepath = $phpDir . '/' . $file;
        if (!file_exists($filepath)) {
            throw new Exception("Required file not found: {$file} (expected at {$filepath})");
        }
        require_once $filepath;
    }
}

// Auto-detect SIMA root
$SIMA_ROOT = findSIMARoot();

if ($SIMA_ROOT === null) {
    // Show error page instead of HTTP 500
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>SIMA Export Tool - Configuration Error</title>
        <style>
            body { font-family: Arial, sans-serif; padding: 20px; max-width: 800px; margin: 0 auto; }
            .error { background: #fee; border: 2px solid #c00; padding: 20px; border-radius: 5px; }
            h1 { color: #c00; }
            code { background: #f0f0f0; padding: 2px 6px; border-radius: 3px; }
            pre { background: #f0f0f0; padding: 10px; border-radius: 3px; overflow-x: auto; }
        </style>
    </head>
    <body>
        <div class="error">
            <h1>⚠ SIMA Root Directory Not Found</h1>
            <p><strong>The export tool could not locate your SIMA installation.</strong></p>
            
            <h2>Current Location:</h2>
            <pre><?php echo __DIR__; ?></pre>
            
            <h2>How to Fix:</h2>
            <ol>
                <li>Ensure this file is inside your SIMA installation directory</li>
                <li>SIMA root should contain <code>generic/</code> and <code>platforms/</code> directories</li>
                <li>Recommended location: <code>/path/to/sima/support/php/sima-export-tool.php</code></li>
            </ol>
            
            <h2>Manual Configuration:</h2>
            <p>If SIMA is in a non-standard location, edit this file and add at line 60:</p>
            <pre>define('SIMA_ROOT', '/path/to/your/sima');</pre>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Define SIMA_ROOT if not already defined
if (!defined('SIMA_ROOT')) {
    define('SIMA_ROOT', $SIMA_ROOT);
}

// Load required files
try {
    loadRequiredFiles($SIMA_ROOT);
    
    // Load export helpers
    require_once $SIMA_ROOT . '/support/php/sima-export-helpers.php';
} catch (Exception $e) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>SIMA Export Tool - Missing Files</title>
        <style>
            body { font-family: Arial, sans-serif; padding: 20px; max-width: 800px; margin: 0 auto; }
            .error { background: #fee; border: 2px solid #c00; padding: 20px; border-radius: 5px; }
            h1 { color: #c00; }
            pre { background: #f0f0f0; padding: 10px; border-radius: 3px; }
        </style>
    </head>
    <body>
        <div class="error">
            <h1>⚠ Required Files Missing</h1>
            <p><strong>Error:</strong> <?php echo htmlspecialchars($e->getMessage()); ?></p>
            
            <h2>SIMA Root Found:</h2>
            <pre><?php echo SIMA_ROOT; ?></pre>
            
            <h2>Required Files:</h2>
            <ul>
                <li>support/php/sima-common.php</li>
                <li>support/php/sima-scanner.php</li>
                <li>support/php/sima-tree-formatter.php</li>
                <li>support/php/sima-version-utils.php</li>
            </ul>
            
            <p>Please upload the missing files to <code><?php echo SIMA_ROOT; ?>/support/php/</code></p>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Ensure export directory exists
if (!defined('EXPORT_DIR')) {
    define('EXPORT_DIR', SIMA_ROOT . '/exports');
}
if (!is_dir(EXPORT_DIR)) {
    mkdir(EXPORT_DIR, 0755, true);
}

/**
 * Validate and normalize directory path
 */
function validateDirectory($path) {
    $path = rtrim($path, '/');
    
    if ($path[0] !== '/') {
        throw new Exception("Path must be absolute");
    }
    
    if (strpos($path, '..') !== false) {
        throw new Exception("Invalid path: parent directory not allowed");
    }
    
    if (!is_dir($path) || !is_readable($path)) {
        throw new Exception("Directory not found or not readable: {$path}");
    }
    
    return realpath($path);
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $action = $_POST['action'] ?? '';
    
    if ($action === 'scan') {
        try {
            $directory = $_POST['directory'] ?? SIMA_ROOT;
            $validatedDir = validateDirectory($directory);
            
            // Get version info
            $versionInfo = SIMAVersionUtils::getVersionInfo($validatedDir);
            
            // Use comprehensive scanner
            $tree = SIMAVersionUtils::scanWithVersion($validatedDir, $versionInfo['version']);
            
            // Get statistics
            $stats = SIMAVersionUtils::getStats($validatedDir, $versionInfo['version']);
            
            sendJsonResponse(true, [
                'tree' => $tree,
                'base_path' => $validatedDir,
                'version_info' => $versionInfo,
                'stats' => $stats
            ]);
        } catch (Exception $e) {
            sendJsonResponse(false, [], $e->getMessage());
        }
    }
    
    if ($action === 'export') {
        try {
            $baseDir = $_POST['base_directory'] ?? SIMA_ROOT;
            $validatedBase = validateDirectory($baseDir);
            $archiveName = $_POST['archive_name'] ?? 'SIMA-Archive';
            $description = $_POST['description'] ?? '';
            $selectedPaths = json_decode($_POST['selected_files'] ?? '[]', true);
            
            $sourceVersion = $_POST['source_version'] ?? null;
            $targetVersion = $_POST['target_version'] ?? null;
            
            // Auto-detect source version if needed
            if (!$sourceVersion || $sourceVersion === 'auto') {
                $versionInfo = SIMAVersionUtils::getVersionInfo($validatedBase);
                $sourceVersion = $versionInfo['version'];
            }
            
            // Default target to source if not specified
            if (!$targetVersion || $targetVersion === 'auto') {
                $targetVersion = $sourceVersion;
            }
            
            // Validate we have files to export
            if (empty($selectedPaths)) {
                throw new Exception("No files selected for export");
            }
            
            // Build selected files array
            $selectedFiles = [];
            foreach ($selectedPaths as $path) {
                $fullPath = $validatedBase . '/' . $path;
                
                if (!file_exists($fullPath)) {
                    error_log("Warning: File not found: {$fullPath}");
                    continue;
                }
                
                $content = file_get_contents($fullPath);
                
                // Apply version conversion if needed
                $convertedPath = $path;
                $convertedFilename = basename($path);
                $wasConverted = false;
                
                if ($sourceVersion !== $targetVersion && 
                    SIMAVersionUtils::canConvert($sourceVersion, $targetVersion)) {
                    
                    $content = SIMAVersionUtils::convertMetadata(
                        $content, 
                        $sourceVersion, 
                        $targetVersion
                    );
                    
                    $convertedPath = SIMAVersionUtils::convertPath(
                        $path, 
                        $sourceVersion, 
                        $targetVersion
                    );
                    
                    $convertedFilename = SIMAVersionUtils::convertFilename(
                        basename($path), 
                        $sourceVersion, 
                        $targetVersion
                    );
                    
                    $wasConverted = true;
                }
                
                // Extract metadata
                $metadata = extractFileMetadata($fullPath);
                
                $selectedFiles[] = [
                    'path' => $fullPath,
                    'relative_path' => $convertedPath,
                    'original_path' => $path,
                    'filename' => $convertedFilename,
                    'ref_id' => $metadata['ref_id'],
                    'category' => $metadata['category'] ?? basename(dirname($path)),
                    'size' => strlen($content),
                    'checksum' => md5($content),
                    'content' => $content,
                    'converted' => $wasConverted,
                    'sima_version' => $targetVersion
                ];
            }
            
            if (empty($selectedFiles)) {
                throw new Exception("No valid files to export");
            }
            
            // Create export
            $result = createExportArchive($archiveName, $description, $selectedFiles, $sourceVersion, $targetVersion);
            
            sendJsonResponse(true, $result);
            
        } catch (Exception $e) {
            sendJsonResponse(false, [], $e->getMessage());
        }
    }
    
    exit;
}

// If we get here, show the HTML interface
require __DIR__ . '/sima-export-ui.php';
