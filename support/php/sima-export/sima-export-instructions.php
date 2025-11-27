<?php
/**
 * sima-export-instructions.php
 * 
 * Import Instructions Generation
 * Version: 1.0.0
 * Date: 2025-11-27
 */

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
    $grouped = _groupFilesByDirectory($selectedFiles);
    
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
    
    $md .= _generateImportProcess();
    
    return $md;
}

/**
 * Group files by directory
 */
function _groupFilesByDirectory($selectedFiles) {
    $grouped = [];
    foreach ($selectedFiles as $file) {
        $dir = dirname($file['relative_path']);
        if (!isset($grouped[$dir])) {
            $grouped[$dir] = [];
        }
        $grouped[$dir][] = $file;
    }
    return $grouped;
}

/**
 * Generate import process section
 */
function _generateImportProcess() {
    return "## Import Process\n\n" .
           "1. Extract knowledge-base.zip\n" .
           "2. Use SIMA Import Tool to review files\n" .
           "3. Select target SIMA directory\n" .
           "4. Verify version compatibility\n" .
           "5. Import selected files\n\n";
}
?>
