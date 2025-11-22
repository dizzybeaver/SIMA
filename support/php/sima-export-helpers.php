<?php
/**
 * sima-export-helpers.php
 * 
 * Helper Functions for Export Tool
 * Version: 1.0.0
 * Date: 2025-11-22
 * Location: /sima/support/php/
 * 
 * Separated helper functions to keep main file under 350 lines
 */

/**
 * Create complete export archive
 */
function createExportArchive($validatedBase, $archiveName, $description, $selectedPaths, $sourceVersion, $targetVersion) {
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
    
    // Create export directory
    $exportId = uniqid();
    $exportPath = EXPORT_DIR . '/' . $exportId;
    
    if (!is_dir($exportPath)) {
        mkdir($exportPath, 0755, true);
    }
    
    // Generate manifest
    $manifestContent = generateManifest(
        $archiveName, 
        $description, 
        $selectedFiles,
        $sourceVersion,
        $targetVersion
    );
    file_put_contents($exportPath . '/manifest.yaml', $manifestContent);
    
    // Generate import instructions
    $instructionsContent = generateImportInstructions(
        $archiveName, 
        $selectedFiles,
        $sourceVersion,
        $targetVersion
    );
    file_put_contents($exportPath . '/import-instructions.md', $instructionsContent);
    
    // Create knowledge base ZIP
    $zipPath = $exportPath . '/knowledge-base.zip';
    $zip = new ZipArchive();
    
    if ($zip->open($zipPath, ZipArchive::CREATE) !== true) {
        throw new Exception("Could not create ZIP file");
    }
    
    foreach ($selectedFiles as $file) {
        $zip->addFromString($file['relative_path'], $file['content']);
    }
    
    $zip->close();
    
    // Create final archive with all export files
    $finalZipPath = $exportPath . '/' . $archiveName . '.zip';
    $finalZip = new ZipArchive();
    
    if ($finalZip->open($finalZipPath, ZipArchive::CREATE) !== true) {
        throw new Exception("Could not create final archive");
    }
    
    $finalZip->addFile($exportPath . '/manifest.yaml', 'manifest.yaml');
    $finalZip->addFile($exportPath . '/import-instructions.md', 'import-instructions.md');
    $finalZip->addFile($zipPath, 'knowledge-base.zip');
    $finalZip->close();
    
    // Generate download URL relative to script location
    $scriptUrl = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']);
    $downloadUrl = $scriptUrl . '/../../exports/' . $exportId . '/' . $archiveName . '.zip';
    
    return [
        'archive_path' => $finalZipPath,
        'archive_name' => $archiveName . '.zip',
        'file_count' => count($selectedFiles),
        'converted_count' => count(array_filter($selectedFiles, fn($f) => $f['converted'])),
        'source_version' => $sourceVersion,
        'target_version' => $targetVersion,
        'download_url' => $downloadUrl,
        'export_id' => $exportId
    ];
}

/**
 * Generate manifest.yaml
 */
function generateManifest($archiveName, $description, $selectedFiles, $sourceVersion, $targetVersion) {
    $manifest = [
        'archive' => [
            'name' => $archiveName,
            'created' => date('Y-m-d H:i:s'),
            'description' => $description,
            'source_version' => $sourceVersion,
            'target_version' => $targetVersion,
            'total_files' => count($selectedFiles),
            'converted_files' => count(array_filter($selectedFiles, fn($f) => $f['converted']))
        ],
        'files' => []
    ];
    
    foreach ($selectedFiles as $file) {
        $manifest['files'][] = [
            'filename' => $file['filename'],
            'path' => $file['relative_path'],
            'original_path' => $file['original_path'],
            'ref_id' => $file['ref_id'],
            'category' => $file['category'],
            'size' => $file['size'],
            'checksum' => $file['checksum'],
            'converted' => $file['converted'],
            'sima_version' => $file['sima_version']
        ];
    }
    
    $manifest['packages'] = [
        [
            'name' => 'knowledge-base.zip',
            'type' => 'base',
            'files' => count($selectedFiles)
        ]
    ];
    
    return arrayToYaml($manifest);
}

/**
 * Generate import-instructions.md
 */
function generateImportInstructions($archiveName, $selectedFiles, $sourceVersion, $targetVersion) {
    $md = "# Import Instructions - {$archiveName}\n\n";
    $md .= "**Archive:** {$archiveName}\n";
    $md .= "**Created:** " . date('Y-m-d') . "\n";
    $md .= "**Source SIMA Version:** {$sourceVersion}\n";
    $md .= "**Target SIMA Version:** {$targetVersion}\n";
    $md .= "**Total Files:** " . count($selectedFiles) . "\n";
    $md .= "**Converted Files:** " . count(array_filter($selectedFiles, fn($f) => $f['converted'])) . "\n\n";
    
    // Group by directory
    $grouped = [];
    foreach ($selectedFiles as $file) {
        $dir = dirname($file['relative_path']);
        if (!isset($grouped[$dir])) {
            $grouped[$dir] = [];
        }
        $grouped[$dir][] = $file;
    }
    
    $md .= "## Installation State\n\n";
    $md .= "### Selected for Install (" . count($selectedFiles) . " files)\n\n";
    
    foreach ($grouped as $dir => $files) {
        $md .= "#### {$dir} (" . count($files) . " files)\n";
        foreach ($files as $file) {
            $status = $file['converted'] ? 'converted' : 'original';
            $md .= "- [x] {$file['filename']} → {$file['relative_path']} ({$status})\n";
        }
        $md .= "\n";
    }
    
    $md .= "## Import Process\n\n";
    $md .= "1. Extract knowledge-base.zip\n";
    $md .= "2. Use SIMA Import Tool to review files\n";
    $md .= "3. Select target SIMA directory\n";
    $md .= "4. Verify version compatibility\n";
    $md .= "5. Import selected files\n\n";
    
    return $md;
}
