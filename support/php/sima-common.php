<?php
/**
 * sima-common.php
 * 
 * Shared PHP functions for SIMA tools
 * Version: 1.0.0
 * Date: 2025-11-19
 * Location: /support/php/
 */

// Configuration constants
if (!defined('SIMA_ROOT')) {
    define('SIMA_ROOT', '/home/joe/web/claude.dizzybeaver.com/public_html/sima');
}
if (!defined('SIMA_VERSION')) {
    define('SIMA_VERSION', '4.2.2');
}
if (!defined('EXPORT_DIR')) {
    define('EXPORT_DIR', '/tmp/sima-exports');
}

/**
 * Scan SIMA directory structure and build knowledge tree
 */
function scanKnowledgeTree($basePath) {
    $tree = [];
    $domains = ['generic', 'platforms', 'languages', 'projects'];
    
    foreach ($domains as $domain) {
        $domainPath = $basePath . '/' . $domain;
        if (!is_dir($domainPath)) continue;
        
        $tree[$domain] = scanDomain($domainPath, $domain);
    }
    
    return $tree;
}

/**
 * Scan a domain directory for categories and files
 */
function scanDomain($domainPath, $domainName) {
    $domain = [
        'name' => $domainName,
        'path' => $domainPath,
        'categories' => [],
        'total_files' => 0
    ];
    
    $subdirs = glob($domainPath . '/*', GLOB_ONLYDIR);
    
    foreach ($subdirs as $subdir) {
        $subdirName = basename($subdir);
        
        $categories = ['lessons', 'decisions', 'anti-patterns', 'specifications', 
                      'core', 'wisdom', 'workflows', 'frameworks'];
        
        if (in_array($subdirName, $categories)) {
            $categoryData = scanCategory($subdir, $subdirName);
            if ($categoryData['file_count'] > 0) {
                $domain['categories'][$subdirName] = $categoryData;
                $domain['total_files'] += $categoryData['file_count'];
            }
        } else {
            $subdomainData = scanDomain($subdir, $subdirName);
            if ($subdomainData['total_files'] > 0) {
                $domain['subdomains'][$subdirName] = $subdomainData;
                $domain['total_files'] += $subdomainData['total_files'];
            }
        }
    }
    
    return $domain;
}

/**
 * Scan a category directory for knowledge files
 */
function scanCategory($categoryPath, $categoryName) {
    $category = [
        'name' => $categoryName,
        'path' => $categoryPath,
        'files' => [],
        'file_count' => 0
    ];
    
    $files = glob($categoryPath . '/*.md');
    
    foreach ($files as $file) {
        $filename = basename($file);
        
        if (strpos($filename, 'Index') !== false || 
            strpos($filename, 'index') !== false) {
            continue;
        }
        
        $metadata = extractFileMetadata($file);
        
        $category['files'][] = [
            'filename' => $filename,
            'path' => $file,
            'relative_path' => str_replace(SIMA_ROOT . '/', '', $file),
            'ref_id' => $metadata['ref_id'],
            'title' => $metadata['title'],
            'size' => filesize($file),
            'checksum' => md5_file($file)
        ];
        
        $category['file_count']++;
    }
    
    return $category;
}

/**
 * Extract metadata from file
 */
function extractFileMetadata($filePath) {
    $content = file_get_contents($filePath);
    $lines = explode("\n", $content);
    
    $metadata = [
        'ref_id' => null,
        'title' => basename($filePath, '.md'),
        'purpose' => null
    ];
    
    if (isset($lines[0]) && strpos($lines[0], '#') === 0) {
        $metadata['title'] = trim(str_replace('#', '', $lines[0]));
    }
    
    if (preg_match('/([A-Z]+)-(\d+)/', basename($filePath), $matches)) {
        $metadata['ref_id'] = $matches[0];
    }
    
    foreach ($lines as $line) {
        if (strpos($line, '**Purpose:**') !== false) {
            $metadata['purpose'] = trim(str_replace('**Purpose:**', '', $line));
            break;
        }
    }
    
    return $metadata;
}

/**
 * Parse import-instructions.md file
 */
function parseImportInstructions($content) {
    $data = [
        'metadata' => [],
        'selected' => [],
        'unselected' => [],
        'packages' => [],
        'changelog' => []
    ];
    
    $lines = explode("\n", $content);
    $currentSection = null;
    $currentCategory = null;
    
    foreach ($lines as $line) {
        $line = trim($line);
        
        if (preg_match('/\*\*Archive:\*\*\s*(.+)/', $line, $matches)) {
            $data['metadata']['archive'] = trim($matches[1]);
        } elseif (preg_match('/\*\*Created:\*\*\s*(.+)/', $line, $matches)) {
            $data['metadata']['created'] = trim($matches[1]);
        } elseif (preg_match('/\*\*Updated:\*\*\s*(.+)/', $line, $matches)) {
            $data['metadata']['updated'] = trim($matches[1]);
        } elseif (preg_match('/\*\*SIMA Version:\*\*\s*(.+)/', $line, $matches)) {
            $data['metadata']['sima_version'] = trim($matches[1]);
        } elseif (preg_match('/\*\*Total Files:\*\*\s*(\d+)/', $line, $matches)) {
            $data['metadata']['total_files'] = intval($matches[1]);
        }
        
        if (strpos($line, '### Selected for Install') !== false) {
            $currentSection = 'selected';
        } elseif (strpos($line, '### Not Selected for Install') !== false) {
            $currentSection = 'unselected';
        } elseif (strpos($line, '## Change Log') !== false) {
            $currentSection = 'changelog';
        } elseif (strpos($line, '## Packages') !== false) {
            $currentSection = 'packages';
        }
        
        if (preg_match('/^####\s+(.+?)\s+\((\d+)\s+files?\)/', $line, $matches)) {
            $currentCategory = [
                'path' => trim($matches[1]),
                'count' => intval($matches[2]),
                'files' => []
            ];
        }
        
        if (preg_match('/^-\s+\[(x| )\]\s+(.+?)\s+→\s+(.+?)(\s+\((.+?)\))?$/', $line, $matches)) {
            $isSelected = ($matches[1] === 'x');
            $filename = trim($matches[2]);
            $targetPath = trim($matches[3]);
            $status = isset($matches[5]) ? trim($matches[5]) : '';
            
            $fileData = [
                'filename' => $filename,
                'target_path' => $targetPath,
                'status' => $status,
                'selected' => $isSelected
            ];
            
            if ($currentCategory) {
                $currentCategory['files'][] = $fileData;
            }
        }
        
        if ($currentCategory && $line === '') {
            if ($currentSection === 'selected') {
                $data['selected'][] = $currentCategory;
            } elseif ($currentSection === 'unselected') {
                $data['unselected'][] = $currentCategory;
            }
            $currentCategory = null;
        }
    }
    
    return $data;
}

/**
 * Add SIMA version tags to file headers
 */
function addVersionTags($content, $packageName) {
    $lines = explode("\n", $content);
    
    $headerEnd = 0;
    $inHeader = false;
    
    for ($i = 0; $i < count($lines); $i++) {
        if (strpos($lines[$i], '**') === 0) {
            $inHeader = true;
        } elseif ($inHeader && trim($lines[$i]) === '') {
            $headerEnd = $i;
            break;
        }
    }
    
    $versionTags = [
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
        $content = file_get_contents($file['path']);
        $content = addVersionTags($content, $packageName);
        $zip->addFromString($file['relative_path'], $content);
    }
    
    $zip->close();
    
    return true;
}

/**
 * Generate manifest.yaml
 */
function generateManifest($archiveName, $description, $selectedFiles) {
    $manifest = [
        'archive' => [
            'name' => $archiveName,
            'description' => $description,
            'created' => date('c'),
            'sima_version' => SIMA_VERSION,
            'type' => 'initial'
        ],
        'structure' => [
            'base_package' => 'knowledge-base.zip',
            'increments' => []
        ],
        'inventory' => [
            'total_files' => count($selectedFiles),
            'domains' => [],
            'categories' => []
        ],
        'files' => []
    ];
    
    $domains = [];
    $categories = [];
    
    foreach ($selectedFiles as $file) {
        $parts = explode('/', $file['relative_path']);
        $domain = $parts[0];
        
        if (!in_array($domain, $domains)) {
            $domains[] = $domain;
        }
        
        $category = $file['category'] ?? 'unknown';
        if (!isset($categories[$category])) {
            $categories[$category] = 0;
        }
        $categories[$category]++;
        
        $manifest['files'][] = [
            'path' => $file['relative_path'],
            'ref_id' => $file['ref_id'],
            'category' => $category,
            'checksum' => $file['checksum'],
            'size' => $file['size'],
            'sima_version' => SIMA_VERSION,
            'exported' => date('Y-m-d')
        ];
    }
    
    $manifest['inventory']['domains'] = $domains;
    $manifest['inventory']['categories'] = $categories;
    
    return yaml_emit($manifest);
}

/**
 * Generate import-instructions.md
 */
function generateImportInstructions($archiveName, $selectedFiles) {
    $md = "# Import Instructions - {$archiveName}\n\n";
    $md .= "**Archive:** {$archiveName}\n";
    $md .= "**Created:** " . date('Y-m-d') . "\n";
    $md .= "**SIMA Version:** " . SIMA_VERSION . "\n";
    $md .= "**Total Files:** " . count($selectedFiles) . "\n\n";
    
    $md .= "## Installation State\n\n";
    $md .= "### Selected for Install (" . count($selectedFiles) . " files)\n\n";
    
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
            $targetPath = '/sima/' . $file['relative_path'];
            $md .= "- [x] {$file['filename']} → {$targetPath}\n";
        }
        $md .= "\n";
    }
    
    $md .= "### Not Selected for Install (0 files)\n";
    $md .= "(None - all files selected in initial export)\n\n";
    
    $md .= "## Packages\n";
    $md .= "- Base: knowledge-base.zip (" . count($selectedFiles) . " files)\n\n";
    
    $md .= "## Import Process\n";
    $md .= "1. Extract knowledge-base.zip\n";
    $md .= "2. Copy files to target locations\n";
    $md .= "3. Use SIMA Import Mode for index updates\n";
    $md .= "4. Verify checksums against manifest\n";
    
    return $md;
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
