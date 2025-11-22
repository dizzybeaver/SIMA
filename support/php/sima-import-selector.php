<?php
/**
 * sima-import-selector.php
 * 
 * SIMA Import Selection Tool - Version-Aware
 * Version: 3.0.0
 * Date: 2025-11-21
 * 
 * PHP backend only - JavaScript in sima-import.js
 * 
 * MODIFIED: Added version compatibility system
 * - Directory selection for target import location
 * - Source/target version detection
 * - Automatic conversion during import
 * - Shared tree renderer from sima-tree.js
 */

require_once __DIR__ . '/sima/support/php/sima-common.php';
require_once __DIR__ . '/sima/support/php/sima-version-utils.php';

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
    
    if (!is_dir($path) || !is_writable($path)) {
        throw new Exception("Directory not found or not writable");
    }
    
    return realpath($path);
}

/**
 * Parse import instructions with version detection
 */
function parseImportInstructions($content) {
    $lines = explode("\n", $content);
    $metadata = [];
    $selectedFiles = [];
    $unselectedFiles = [];
    $currentCategory = null;
    $inSelected = false;
    
    foreach ($lines as $line) {
        $line = trim($line);
        
        // Extract metadata
        if (preg_match('/^\*\*Archive:\*\* (.+)$/', $line, $matches)) {
            $metadata['archive'] = $matches[1];
        } elseif (preg_match('/^\*\*Created:\*\* (.+)$/', $line, $matches)) {
            $metadata['created'] = $matches[1];
        } elseif (preg_match('/^\*\*SIMA Version:\*\* (.+)$/', $line, $matches)) {
            $metadata['sima_version'] = $matches[1];
        } elseif (preg_match('/^\*\*Total Files:\*\* (\d+)$/', $line, $matches)) {
            $metadata['total_files'] = (int)$matches[1];
        } elseif (preg_match('/^\*\*Selected:\*\* (\d+)$/', $line, $matches)) {
            $metadata['selected_count'] = (int)$matches[1];
        }
        
        // Track sections
        if (strpos($line, '### Selected for Install') !== false) {
            $inSelected = true;
        } elseif (strpos($line, '### Not Selected for Install') !== false) {
            $inSelected = false;
        }
        
        // Track categories
        if (preg_match('/^#### (.+) \((\d+) files\)$/', $line, $matches)) {
            $currentCategory = $matches[1];
        }
        
        // Parse file entries
        if (preg_match('/^- \[([ x])\] (.+?) → (.+?)(?: \((.+)\))?$/', $line, $matches)) {
            $file = [
                'checked' => $matches[1] === 'x',
                'filename' => $matches[2],
                'target_path' => $matches[3],
                'status' => $matches[4] ?? '',
                'category' => $currentCategory
            ];
            
            if ($inSelected) {
                $selectedFiles[] = $file;
            } else {
                $unselectedFiles[] = $file;
            }
        } elseif (preg_match('/^- \[ \] (.+?) \(SKIP\)$/', $line, $matches)) {
            $file = [
                'checked' => false,
                'filename' => $matches[1],
                'target_path' => '',
                'status' => 'SKIP',
                'category' => $currentCategory
            ];
            $unselectedFiles[] = $file;
        }
    }
    
    // Organize by category for tree display
    $selectedByCategory = [];
    foreach ($selectedFiles as $file) {
        $cat = $file['category'] ?? 'Uncategorized';
        if (!isset($selectedByCategory[$cat])) {
            $selectedByCategory[$cat] = [
                'path' => $cat,
                'files' => []
            ];
        }
        $selectedByCategory[$cat]['files'][] = $file;
    }
    
    $unselectedByCategory = [];
    foreach ($unselectedFiles as $file) {
        $cat = $file['category'] ?? 'Uncategorized';
        if (!isset($unselectedByCategory[$cat])) {
            $unselectedByCategory[$cat] = [
                'path' => $cat,
                'files' => []
            ];
        }
        $unselectedByCategory[$cat]['files'][] = $file;
    }
    
    return [
        'metadata' => $metadata,
        'selected' => $selectedByCategory,
        'unselected' => $unselectedByCategory
    ];
}

/**
 * Generate updated import instructions
 */
function generateUpdatedInstructions($originalData, $newSelections, $targetVersion = null) {
    $sourceVersion = $originalData['metadata']['sima_version'] ?? '4.2';
    $targetVersion = $targetVersion ?? $sourceVersion;
    
    $md = "# Import Instructions - {$originalData['metadata']['archive']}\n\n";
    $md .= "**Archive:** {$originalData['metadata']['archive']}\n";
    $md .= "**Created:** {$originalData['metadata']['created']}\n";
    $md .= "**Modified:** " . date('Y-m-d') . "\n";
    
    $selectedCount = count($newSelections);
    $totalFiles = $originalData['metadata']['total_files'];
    
    $md .= "**SIMA Version:** {$sourceVersion}";
    if ($sourceVersion !== $targetVersion) {
        $md .= " → {$targetVersion} (converted)";
    }
    $md .= "\n";
    $md .= "**Total Files:** {$totalFiles}\n";
    $md .= "**Selected:** {$selectedCount}\n\n";
    
    $md .= "## Installation State\n\n";
    
    $selectedFiles = [];
    $unselectedFiles = [];
    
    $allCategories = array_merge(
        $originalData['selected'] ?? [],
        $originalData['unselected'] ?? []
    );
    
    foreach ($allCategories as $category) {
        foreach ($category['files'] as $file) {
            $targetPath = $file['target_path'];
            
            // Apply version conversion to path if needed
            if ($sourceVersion !== $targetVersion && 
                SIMAVersionUtils::canConvert($sourceVersion, $targetVersion)) {
                $targetPath = SIMAVersionUtils::convertPath(
                    $targetPath, 
                    $sourceVersion, 
                    $targetVersion
                );
            }
            
            if (in_array($file['target_path'], $newSelections)) {
                if (!isset($selectedFiles[$category['path']])) {
                    $selectedFiles[$category['path']] = [];
                }
                $file['converted_path'] = $targetPath;
                $selectedFiles[$category['path']][] = $file;
            } else {
                if (!isset($unselectedFiles[$category['path']])) {
                    $unselectedFiles[$category['path']] = [];
                }
                $unselectedFiles[$category['path']][] = $file;
            }
        }
    }
    
    $md .= "### Selected for Install ({$selectedCount} files)\n\n";
    foreach ($selectedFiles as $categoryPath => $files) {
        $md .= "#### {$categoryPath} (" . count($files) . " files)\n";
        foreach ($files as $file) {
            $displayPath = $file['converted_path'] ?? $file['target_path'];
            $md .= "- [x] {$file['filename']} → {$displayPath}";
            if (!empty($file['status'])) {
                $md .= " ({$file['status']})";
            }
            if (isset($file['converted_path']) && $file['converted_path'] !== $file['target_path']) {
                $md .= " [converted]";
            }
            $md .= "\n";
        }
        $md .= "\n";
    }
    
    $unselectedCount = $totalFiles - $selectedCount;
    $md .= "### Not Selected for Install ({$unselectedCount} files)\n\n";
    
    if ($unselectedCount > 0) {
        foreach ($unselectedFiles as $categoryPath => $files) {
            $md .= "#### {$categoryPath} (" . count($files) . " files)\n";
            foreach ($files as $file) {
                $md .= "- [ ] {$file['filename']} (SKIP)\n";
            }
            $md .= "\n";
        }
    } else {
        $md .= "(None)\n\n";
    }
    
    return $md;
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'scan_target') {
        // ADDED: Scan target directory for version info
        try {
            $directory = $_POST['directory'] ?? SIMA_ROOT;
            $validatedDir = validateDirectory($directory);
            
            $versionInfo = SIMAVersionUtils::getVersionInfo($validatedDir);
            
            sendJsonResponse(true, [
                'version_info' => $versionInfo,
                'directory' => $validatedDir
            ]);
        } catch (Exception $e) {
            sendJsonResponse(false, [], $e->getMessage());
        }
    }
    
    if ($action === 'parse') {
        try {
            if (!isset($_FILES['import_instructions'])) {
                throw new Exception("No file uploaded");
            }
            
            $content = file_get_contents($_FILES['import_instructions']['tmp_name']);
            $data = parseImportInstructions($content);
            
            sendJsonResponse(true, ['data' => $data]);
        } catch (Exception $e) {
            sendJsonResponse(false, [], $e->getMessage());
        }
    }
    
    if ($action === 'generate') {
        try {
            $originalData = json_decode($_POST['original_data'], true);
            $newSelections = json_decode($_POST['new_selections'], true);
            $targetVersion = $_POST['target_version'] ?? null;
            
            $updatedContent = generateUpdatedInstructions(
                $originalData, 
                $newSelections,
                $targetVersion
            );
            
            $tmpFile = tempnam(sys_get_temp_dir(), 'import-instructions-');
            file_put_contents($tmpFile, $updatedContent);
            
            sendJsonResponse(true, [
                'content' => $updatedContent,
                'download_url' => '?download=' . basename($tmpFile)
            ]);
        } catch (Exception $e) {
            sendJsonResponse(false, [], $e->getMessage());
        }
    }
}

// Handle downloads
if (isset($_GET['download'])) {
    $filename = basename($_GET['download']);
    $filepath = sys_get_temp_dir() . '/' . $filename;
    
    if (file_exists($filepath)) {
        header('Content-Type: text/markdown');
        header('Content-Disposition: attachment; filename="import-instructions.md"');
        readfile($filepath);
        unlink($filepath);
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📥 SIMA Import Selection Tool</title>
    <link rel="stylesheet" href="/sima/support/php/sima-styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>📥 SIMA Import Selection Tool</h1>
            <p>Version-aware import with automatic conversion</p>
        </header>
        
        <!-- ADDED: Target directory configuration -->
        <div class="section">
            <h2>1. Target SIMA Installation</h2>
            <div class="form-group">
                <label for="targetDirectory">Target Directory:</label>
                <input type="text" id="targetDirectory" value="<?= SIMA_ROOT ?>" 
                       placeholder="/path/to/sima">
                <button id="scan-target-btn" onclick="SIMAImport.scanTargetDirectory()">
                    🔍 Detect Version
                </button>
            </div>
            <div id="targetVersion" class="info-box" style="display: none;"></div>
        </div>
        
        <div class="section" id="upload-section">
            <h2>2. Upload Import Instructions</h2>
            <div class="upload-area" id="upload-area">
                <p style="font-size: 48px; margin-bottom: 10px;">📄</p>
                <p style="font-size: 18px;">Drop import-instructions.md here</p>
                <p style="color: #666;">or click to browse</p>
                <input type="file" id="file-input" accept=".md">
            </div>
            <div style="margin-top: 20px; text-align: center;">
                <button onclick="document.getElementById('file-input').click()">
                    📂 Select File
                </button>
            </div>
        </div>
        
        <div class="section hidden" id="metadata-section">
            <h2>3. Archive Information</h2>
            <!-- ADDED: Source/target version display -->
            <div class="metadata-grid">
                <div class="metadata-item">
                    <label>Archive</label>
                    <div class="value" id="meta-archive"></div>
                </div>
                <div class="metadata-item">
                    <label>Source Version</label>
                    <div class="value" id="meta-source-version"></div>
                </div>
                <div class="metadata-item">
                    <label>Target Version</label>
                    <div class="value" id="meta-target-version"></div>
                </div>
                <div class="metadata-item">
                    <label>Total Files</label>
                    <div class="value" id="meta-total"></div>
                </div>
            </div>
            <!-- ADDED: Conversion warning -->
            <div id="conversion-warning" class="warning-box" style="display: none;">
                ⚠️ Version conversion will be applied during import
            </div>
        </div>
        
        <div class="section hidden" id="tree-section">
            <h2>4. Select Files to Import</h2>
            <div class="tree-controls">
                <button onclick="SIMATree.expandAll()">➕ Expand All</button>
                <button onclick="SIMATree.collapseAll()">➖ Collapse All</button>
                <button onclick="SIMATree.selectAll()">☑️ Select All</button>
                <button onclick="SIMATree.clearSelection()">⬜ Clear All</button>
            </div>
            <div class="tree-container" id="tree"></div>
            <div class="selection-summary" id="summary"></div>
        </div>
        
        <div class="section hidden" id="generate-section">
            <h2>5. Generate Updated Instructions</h2>
            <button id="generate-btn" onclick="SIMAImport.generateInstructions()">
                💾 Generate Import Instructions
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
    <script src="/sima/support/php/sima-import.js"></script>
</body>
</html>
