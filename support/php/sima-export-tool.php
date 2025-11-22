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
            
            if (!$sourceVersion) {
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
            
            $exportId = uniqid();
            $exportPath = EXPORT_DIR . '/' . $exportId;
            mkdir($exportPath, 0755, true);
            
            $manifestContent = generateManifest($archiveName, $description, $selectedFiles);
            file_put_contents($exportPath . '/manifest.yaml', $manifestContent);
            
            $instructionsContent = generateImportInstructions($archiveName, $selectedFiles);
            file_put_contents($exportPath . '/import-instructions.md', $instructionsContent);
            
            $zipPath = $exportPath . '/knowledge-base.zip';
            createArchiveZip($selectedFiles, $zipPath, 'knowledge-base.zip');
            
            $finalZipPath = $exportPath . '/SIMA-Archive-' . $archiveName . '-' . date('Y-m-d') . '.zip';
            $finalZip = new ZipArchive();
            $finalZip->open($finalZipPath, ZipArchive::CREATE);
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
        </header>
        
        <div class="section">
            <h2>Source Configuration</h2>
            <div class="form-group">
                <label for="simaDirectory">SIMA Directory Path:</label>
                <input type="text" id="simaDirectory" value="<?= SIMA_ROOT ?>" 
                       placeholder="/path/to/sima" style="font-family: monospace;">
                <small>Absolute path to SIMA installation (e.g., /home/user/simav4)</small>
            </div>
            <div class="form-group">
                <label for="sourceVersion">Source Version:</label>
                <select id="sourceVersion">
                    <option value="auto">Auto-Detect</option>
                    <option value="4.2">SIMA v4.2</option>
                    <option value="4.1">SIMA v4.1</option>
                </select>
                <small id="detectedVersion" style="display:none; color: #27ae60; margin-top: 5px;"></small>
            </div>
            <div class="form-group">
                <label for="targetVersion">Export As (Target Version):</label>
                <select id="targetVersion">
                    <option value="same">Same as Source</option>
                    <option value="4.2">SIMA v4.2</option>
                    <option value="4.1">SIMA v4.1</option>
                </select>
                <small>Convert files to different SIMA version format</small>
            </div>
        </div>
        
        <div class="section">
            <h2>Export Configuration</h2>
            <div class="form-group">
                <label for="exportName">Archive Name:</label>
                <input type="text" id="exportName" placeholder="my-export-package" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <input type="text" id="description" placeholder="AWS Lambda, DynamoDB knowledge">
            </div>
        </div>
        
        <div class="section">
            <div class="toolbar">
                <button onclick="loadKnowledgeTree()" id="scan-btn">🔍 Scan SIMA</button>
                <button onclick="expandAll()">📂 Expand All</button>
                <button onclick="collapseAll()">📁 Collapse All</button>
                <button onclick="selectAll()">✅ Select All</button>
                <button onclick="clearSelection()">❌ Clear</button>
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="🔎 Search..." onkeyup="filterTree(this.value)">
                </div>
                <span class="selection-summary" id="selectionSummary">Selected: 0</span>
            </div>
            
            <div class="loading" id="loading">
                <div class="spinner"></div>
                <p>Scanning SIMA directory...</p>
            </div>
            
            <div class="error" id="error">
                <p id="error-text"></p>
            </div>
            
            <div class="tree-container" id="tree"></div>
        </div>
        
        <div class="section">
            <button id="exportButton" onclick="createExport()" disabled>📦 Create Archive</button>
        </div>
        
        <div class="success" id="success">
            <h3>✅ Export Complete</h3>
            <p id="success-text"></p>
            <a id="download-link" class="download-btn" download>⬇️ Download Archive</a>
        </div>
    </div>

    <script src="/sima/support/php/sima-tree.js"></script>
    <script src="/sima/support/php/sima-export.js"></script>
</body>
</html>
