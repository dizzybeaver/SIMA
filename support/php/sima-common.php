<?php
/**
 * sima-common.php
 * 
 * Shared functions for SIMA PHP tools
 * Version: 2.1.0
 * Date: 2025-11-27
 * 
 * UPDATED: Made generateManifest() generic for import/export/update
 * FIXED: Removed duplicate function conflict
 */

// Define SIMA version
if (!defined('SIMA_VERSION')) {
    define('SIMA_VERSION', '4.2');
}

/**
 * Send JSON response
 */
function sendJsonResponse($success, $data = [], $error = null) {
    header('Content-Type: application/json');
    $response = ['success' => $success];
    if ($success) {
        $response = array_merge($response, $data);
    } else {
        $response['error'] = $error;
    }
    echo json_encode($response);
    exit;
}

/**
 * Extract file metadata from markdown file
 */
function extractFileMetadata($filepath) {
    $content = file_get_contents($filepath);
    $lines = explode("\n", $content);
    
    $metadata = [
        'ref_id' => '',
        'version' => '',
        'date' => '',
        'purpose' => '',
        'type' => ''
    ];
    
    foreach ($lines as $line) {
        if (preg_match('/^\*\*REF-ID:\*\*\s*(.+)$/', $line, $matches)) {
            $metadata['ref_id'] = trim($matches[1]);
        } elseif (preg_match('/^\*\*Version:\*\*\s*(.+)$/', $line, $matches)) {
            $metadata['version'] = trim($matches[1]);
        } elseif (preg_match('/^\*\*Date:\*\*\s*(.+)$/', $line, $matches)) {
            $metadata['date'] = trim($matches[1]);
        } elseif (preg_match('/^\*\*Purpose:\*\*\s*(.+)$/', $line, $matches)) {
            $metadata['purpose'] = trim($matches[1]);
        } elseif (preg_match('/^\*\*Type:\*\*\s*(.+)$/', $line, $matches)) {
            $metadata['type'] = trim($matches[1]);
        }
    }
    
    // Extract REF-ID from filename if not in metadata
    if (empty($metadata['ref_id'])) {
        $filename = basename($filepath, '.md');
        if (preg_match('/^([A-Z]+-[A-Z0-9-]+)/', $filename, $matches)) {
            $metadata['ref_id'] = $matches[1];
        }
    }
    
    return $metadata;
}

/**
 * Add version tags to file content
 */
function addVersionTags($content, $packageName) {
    $lines = explode("\n", $content);
    
    // Find header end (after first ---)
    $headerEnd = 0;
    $dashCount = 0;
    foreach ($lines as $i => $line) {
        if (trim($line) === '---') {
            $dashCount++;
            if ($dashCount === 2) {
                $headerEnd = $i + 1;
                break;
            }
        }
    }
    
    // Add version tags after header
    $versionTags = [
        "",
        "**SIMA Version:** " . SIMA_VERSION,
        "**Exported:** " . date('Y-m-d'),
        "**Export Package:** {$packageName}"
    ];
    
    array_splice($lines, $headerEnd, 0, $versionTags);
    
    return implode("\n", $lines);
}

/**
 * Create ZIP archive with selected files
 */
function createArchiveZip($selectedFiles, $zipPath, $packageName) {
    $zip = new ZipArchive();
    
    if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
        throw new Exception("Cannot create ZIP file: {$zipPath}");
    }
    
    foreach ($selectedFiles as $file) {
        $content = $file['content'] ?? file_get_contents($file['path']);
        $content = addVersionTags($content, $packageName);
        $zip->addFromString($file['relative_path'], $content);
    }
    
    $zip->close();
    
    return true;
}

/**
 * Generate generic manifest.yaml for any SIMA operation
 */
function generateManifest($operation, $archiveName, $description, $files, $sourceVersion = null, $targetVersion = null) {
    $manifest = [
        'operation' => $operation, // 'export', 'import', 'update'
        'archive' => [
            'name' => $archiveName,
            'created' => date('Y-m-d H:i:s'),
            'description' => $description,
            'total_files' => count($files)
        ],
        'files' => []
    ];
    
    // Add version info if provided
    if ($sourceVersion) {
        $manifest['archive']['source_version'] = $sourceVersion;
    }
    if ($targetVersion) {
        $manifest['archive']['target_version'] = $targetVersion;
    }
    
    // Count converted files if available
    $convertedCount = 0;
    foreach ($files as $file) {
        if (isset($file['converted']) && $file['converted']) {
            $convertedCount++;
        }
    }
    if ($convertedCount > 0) {
        $manifest['archive']['converted_files'] = $convertedCount;
    }
    
    foreach ($files as $file) {
        $fileEntry = [
            'filename' => $file['filename'] ?? basename($file['path'] ?? ''),
            'path' => $file['relative_path'] ?? $file['path'] ?? '',
            'ref_id' => $file['ref_id'] ?? '',
            'category' => $file['category'] ?? 'unknown',
            'size' => $file['size'] ?? 0,
            'checksum' => $file['checksum'] ?? ''
        ];
        
        // Add optional fields if they exist
        if (isset($file['original_path'])) {
            $fileEntry['original_path'] = $file['original_path'];
        }
        if (isset($file['converted'])) {
            $fileEntry['converted'] = $file['converted'];
        }
        if (isset($file['sima_version'])) {
            $fileEntry['sima_version'] = $file['sima_version'];
        }
        
        $manifest['files'][] = $fileEntry;
    }
    
    return arrayToYaml($manifest);
}

/**
 * Convert array to YAML format manually
 */
function arrayToYaml($array, $indent = 0) {
    $yaml = '';
    $prefix = str_repeat('  ', $indent);
    
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $yaml .= $prefix . $key . ":\n";
            $yaml .= arrayToYaml($value, $indent + 1);
        } else {
            if (is_string($value) && (strpos($value, ':') !== false || strpos($value, '#') !== false)) {
                $value = '"' . addslashes($value) . '"';
            }
            $yaml .= $prefix . $key . ': ' . $value . "\n";
        }
    }
    
    return $yaml;
}

/**
 * Generate import-instructions.md
 */
function generateImportInstructions($archiveName, $selectedFiles) {
    $md = "# Import Instructions - {$archiveName}\n\n";
    $md .= "**Archive:** {$archiveName}\n";
    $md .= "**Created:** " . date('Y-m-d') . "\n";
    $md .= "**SIMA Version:** " . SIMA_VERSION . "\n";
    $md .= "**Total Files:** " . count($selectedFiles) . "\n";
    $md .= "**Selected:** " . count($selectedFiles) . "\n\n";
    
    $md .= "## Installation State\n\n";
    $md .= "### Selected for Install (" . count($selectedFiles) . " files)\n\n";
    
    // Group by category
    $grouped = [];
    foreach ($selectedFiles as $file) {
        $category = dirname($file['relative_path']);
        if (!isset($grouped[$category])) {
            $grouped[$category] = [];
        }
        $grouped[$category][] = $file;
    }
    
    foreach ($grouped as $category => $files) {
        $md .= "#### {$category} (" . count($files) . " files)\n";
        foreach ($files as $file) {
            $targetPath = $file['relative_path'];
            $status = isset($file['converted']) && $file['converted'] ? 'converted' : 'new';
            $md .= "- [x] {$file['filename']} → {$targetPath} ({$status})\n";
        }
        $md .= "\n";
    }
    
    $md .= "### Not Selected for Install (0 files)\n\n";
    $md .= "(None - all files selected in initial export)\n\n";
    
    $md .= "## Packages\n\n";
    $md .= "- Base: knowledge-base.zip (" . count($selectedFiles) . " files)\n\n";
    
    $md .= "## Import Process\n\n";
    $md .= "1. Extract knowledge-base.zip\n";
    $md .= "2. Copy files to target locations\n";
    $md .= "3. Use SIMA Import Tool to select files\n";
    $md .= "4. Verify checksums against manifest\n";
    $md .= "5. Update indexes as needed\n";
    
    return $md;
}

/**
 * Parse import instructions markdown file
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
?>
