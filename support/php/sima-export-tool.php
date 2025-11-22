<?php
/**
 * sima-export-tool.php
 * 
 * SIMA Knowledge Export Tool - Version-Aware
 * Version: 3.0.0
 * Date: 2025-11-21
 * 
 * PHP backend only - JavaScript in sima-export.js
 */

require_once __DIR__ . '/sima/support/php/sima-common.php';
require_once __DIR__ . '/sima/support/php/sima-version-utils.php';

// Ensure export directory exists
if (!defined('EXPORT_DIR')) {
    define('EXPORT_DIR', '/tmp/sima-exports');
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
        throw new Exception("Directory not found or not readable");
    }
    
    return realpath($path);
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'scan') {
        try {
            $directory = $_POST['directory'] ?? SIMA_ROOT;
            $validatedDir = validateDirectory($directory);
            
            $versionInfo = SIMAVersionUtils::getVersionInfo($validatedDir);
            $tree = SIMAVersionUtils::scanWithVersion($validatedDir, $versionInfo['version']);
            
            sendJsonResponse(true, [
                'tree' => $tree,
                'base_path' => $validatedDir,
                'version_info' => $versionInfo
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
            
            if (!$sourceVersion || $sourceVersion === 'auto') {
                $versionInfo = SIMAVersionUtils::getVersionInfo($validatedBase);
                $sourceVersion = $versionInfo['version'];
            }
            
            if (!$targetVersion) {
                $targetVersion = $sourceVersion;
            }
            
            $selectedFiles = [];
            foreach ($selectedPaths as $path) {
                $fullPath = $validatedBase . '/' . $path;
                if (file_exists($fullPath)) {
                    $content = file_get_contents($fullPath);
                    
                    // Apply version conversion if needed
                    if ($sourceVersion !== $targetVersion && 
                        SIMAVersionUtils::canConvert($sourceVersion, $targetVersion)) {
                        $content = SIMAVersionUtils::convertMetadata($content, $sourceVersion, $targetVersion);
                        $path = SIMAVersionUtils::convertPath($path, $sourceVersion, $targetVersion);
                        $filename = SIMAVersionUtils::convertFilename(basename($path), $sourceVersion, $targetVersion);
                    } else {
                        $filename = basename($path);
                    }
                    
                    $metadata = extractFileMetadata($fullPath);
                    $selectedFiles[] = [
                        'path' => $fullPath,
                        'relative_path' => $path,
                        'filename' => $filename,
                        'ref_id' => $metadata['ref_id'],
                        'category' => basename(dirname($path)),
                        'size' => strlen($content),
                        'checksum' => md5($content),
                        'content' => $content,
                        'converted' => $sourceVersion !== $targetVersion
                    ];
                }
            }
            
            if (empty($selectedFiles)) {
                throw new Exception("No valid files selected");
            }
            
            // Create export directory
            $exportId = uniqid();
            $exportPath = EXPORT_DIR . '/' . $exportId;
            mkdir($exportPath, 0755, true);
            
            // Generate manifest
            $manifestContent = generateManifest($archiveName, $description, $selectedFiles);
            file_put_contents($exportPath . '/manifest.yaml', $manifestContent);
            
            // Generate import instructions
            $instructionsContent = generateImportInstructions($archiveName, $selectedFiles);
            file_put_contents($exportPath . '/import-instructions.md', $instructionsContent);
            
            // Create knowledge base ZIP
            $zipPath = $exportPath . '/knowledge-base.zip';
            createArchiveZip($selectedFiles, $zipPath, $archiveName);
            
            // Create final archive
            $finalZipPath = $exportPath . '/SIMA-Archive-' . $archiveName . '-' . date('Y-m-d') . '.zip';
            $finalZip = new ZipArchive();
            if ($finalZip->open($finalZipPath, ZipArchive::CREATE) !== true) {
                throw new Exception("Cannot create final archive");
            }
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
            <p>Version-aware export with automatic conversion</p>
        </header>
        
        <div class="section">
            <h2>1. Source Configuration</h2>
            <div class="form-group">
                <label for="simaDirectory">SIMA Directory Path:</label>
                <input type="text" id="simaDirectory" value="<?= SIMA_ROOT ?>" 
                       placeholder="/path/to/sima" style="font-family: monospace;">
                <small>Absolute path to SIMA installation</small>
            </div>
            <div class="form-group">
                <label for="sourceVersion">Source Version:</label>
                <select id="sourceVersion">
                    <option value="auto">Auto-Detect</option>
                    <option value="4.2">SIMA v4.2</option>
                    <option value="4.1">SIMA v4.1</option>
                    <option value="3.0">SIMA v3.0 (Neural Maps)</option>
                </select>
            </div>
            <div class="form-group">
                <label for="targetVersion">Target Version:</label>
                <select id="targetVersion">
                    <option value="auto">Same as Source</option>
                    <option value="4.2">SIMA v4.2</option>
                    <option value="4.1">SIMA v4.1</option>
                    <option value="3.0">SIMA v3.0 (Neural Maps)</option>
                </select>
                <small>Convert files to different version during export</small>
            </div>
            <button id="scan-btn" onclick="SIMAExport.loadKnowledgeTree()">
                🔍 Scan Directory
            </button>
            <div id="detectedVersion" style="display: none; margin-top: 10px; padding: 10px; background: #e7f3ff; border-radius: 4px;"></div>
        </div>
        
        <div class="section hidden" id="tree-section">
            <h2>2. Select Files to Export</h2>
            <div class="tree-controls">
                <button onclick="SIMATree.expandAll()">➕ Expand All</button>
                <button onclick="SIMATree.collapseAll()">➖ Collapse All</button>
                <button onclick="SIMATree.selectAll()">☑️ Select All</button>
                <button onclick="SIMATree.clearSelection()">⬜ Clear All</button>
            </div>
            <div class="tree-container" id="tree"></div>
            <div class="selection-summary" id="summary"></div>
        </div>
        
        <div class="section hidden" id="export-section">
            <h2>3. Export Configuration</h2>
            <div class="form-group">
                <label for="archiveName">Archive Name:</label>
                <input type="text" id="archiveName" value="SIMA-Export" placeholder="Archive name">
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" rows="3" placeholder="Describe the contents of this export..."></textarea>
            </div>
            <button id="export-btn" onclick="SIMAExport.exportFiles()">
                💾 Create Export Package
            </button>
        </div>
        
        <div id="loading" style="display: none;">
            <p>⏳ Processing...</p>
        </div>
        
        <div id="error" class="error-box">
            <p id="error-text"></p>
        </div>
    </div>
    
    <script src="/sima/support/php/sima-tree.js"></script>
    <script src="/sima/support/php/sima-export.js"></script>
</body>
</html>
