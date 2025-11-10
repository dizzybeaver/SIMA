<?php
// fileserver.php
// Version: 2.2.0
// Date: 2025-11-04
// Purpose: Generate File Server URLs.md with cache-busting parameters (FAST - uses filesystem)
// MODIFIED: Added flexible domain configuration system
//           - Separate domain_catalog and domain_fetch for each directory
//           - Support for multiple directories with individual configurations
//           - Configurable subdirectory grouping per directory
//           - Generic code that processes any number of directory configs

// =============================================================================
// CONFIGURATION
// =============================================================================

/**
 * Directory Configuration
 * 
 * Each entry defines a directory to scan with separate catalog and fetch domains.
 * 
 * - path: Local filesystem path to scan (relative or absolute)
 * - url_path: URL path component (e.g., '/src', '/sima')
 * - domain_catalog: Domain where files are cataloged/listed (metadata)
 * - domain_fetch: Domain to use in generated URLs (where files will be fetched from)
 * - label: Human-readable label for the directory section
 * - group_subdirs: Whether to group files by subdirectories (true/false)
 * 
 * Example use cases:
 * - Scan local files, generate URLs for same domain
 * - Scan local files, generate URLs for GitHub or CDN
 * - Catalog from one domain, fetch from another (mirror scenarios)
 */
$DIRECTORY_CONFIGS = [
    [
        'path' => __DIR__ . '/src',
        'url_path' => '/src',
        'domain_catalog' => 'claude.dizzybeaver.com',
        'domain_fetch' => 'claude.dizzybeaver.com',
        'label' => 'PYTHON SOURCE FILES',
        'group_subdirs' => true
    ],
    [
        'path' => __DIR__ . '/sima',
        'url_path' => '/sima',
        'domain_catalog' => 'claude.dizzybeaver.com',
        'domain_fetch' => 'claude.dizzybeaver.com',
        'label' => 'DOCUMENTATION',
        'group_subdirs' => true
    ],
    [
        'path' => __DIR__ . '/simav4',
        'url_path' => '/simav4',
        'domain_catalog' => 'claude.dizzybeaver.com',
        'domain_fetch' => 'claude.dizzybeaver.com',
        'label' => 'SIMAv4_EXISTING',
        'group_subdirs' => true
    ]
    // Add more directories here as needed:
    // [
    //     'path' => __DIR__ . '/custom',
    //     'url_path' => '/custom',
    //     'domain_catalog' => 'example.com',
    //     'domain_fetch' => 'cdn.example.com',
    //     'label' => 'CUSTOM FILES',
    //     'group_subdirs' => false
    // ]
];

// File extensions to include (empty array = all files)
$ALLOWED_EXTENSIONS = []; // e.g., ['.md', '.py', '.yml'] or [] for all

// Excluded directories (directory names to skip)
$EXCLUDED_DIRS = ['.git', 'node_modules', '.svn', '.DS_Store'];

// Excluded files (filenames to skip)
$EXCLUDED_FILES = ['.htaccess', '.gitignore', 'fileserver.php'];

// =============================================================================
// FUNCTIONS
// =============================================================================

/**
 * Generate random 10-digit cache-busting number
 */
function generateCacheBust(): string {
    return str_pad((string)random_int(1000000000, 9999999999), 10, '0', STR_PAD_LEFT);
}

/**
 * Recursively scan directory and return all file paths
 */
function scanDirectoryRecursive(string $dir, array $excludedDirs, array $excludedFiles): array {
    $files = [];
    
    if (!is_dir($dir) || !is_readable($dir)) {
        return [];
    }
    
    $items = @scandir($dir);
    if ($items === false) {
        return [];
    }
    
    foreach ($items as $item) {
        // Skip . and ..
        if ($item === '.' || $item === '..') {
            continue;
        }
        
        $path = $dir . DIRECTORY_SEPARATOR . $item;
        
        if (is_dir($path)) {
            // Skip excluded directories
            if (in_array($item, $excludedDirs)) {
                continue;
            }
            
            // Recursively scan subdirectory
            $subFiles = scanDirectoryRecursive($path, $excludedDirs, $excludedFiles);
            $files = array_merge($files, $subFiles);
        } else {
            // Skip excluded files
            if (in_array($item, $excludedFiles)) {
                continue;
            }
            
            // Add file to list
            $files[] = $path;
        }
    }
    
    return $files;
}

/**
 * Convert filesystem path to URL path
 */
function pathToUrl(string $filePath, string $documentRoot): string {
    // Normalize paths
    $filePath = str_replace('\\', '/', $filePath);
    $documentRoot = str_replace('\\', '/', rtrim($documentRoot, '/'));
    
    // Remove document root from file path
    if (str_starts_with($filePath, $documentRoot)) {
        $urlPath = substr($filePath, strlen($documentRoot));
    } else {
        $urlPath = $filePath;
    }
    
    // Ensure leading slash
    if (!str_starts_with($urlPath, '/')) {
        $urlPath = '/' . $urlPath;
    }
    
    return $urlPath;
}

/**
 * Filter files by extension
 */
function filterByExtension(array $files, array $allowedExtensions): array {
    if (empty($allowedExtensions)) {
        return $files; // No filter
    }
    
    return array_filter($files, function($file) use ($allowedExtensions) {
        foreach ($allowedExtensions as $ext) {
            if (str_ends_with(strtolower($file), strtolower($ext))) {
                return true;
            }
        }
        return false;
    });
}

/**
 * Process a directory configuration and return structured data
 */
function processDirectoryConfig(array $config, string $documentRoot, array $excludedDirs, array $excludedFiles): array {
    // Scan files from the path
    $files = scanDirectoryRecursive($config['path'], $excludedDirs, $excludedFiles);
    
    if (empty($files)) {
        return [
            'config' => $config,
            'files' => [],
            'urlFiles' => [],
            'grouped' => []
        ];
    }
    
    // Convert filesystem paths to URL paths
    $urlFiles = [];
    foreach ($files as $file) {
        // Convert to URL path relative to document root
        $relativePath = str_replace($config['path'], $config['url_path'], $file);
        $relativePath = str_replace('\\', '/', $relativePath);
        $urlFiles[] = $relativePath;
    }
    
    // Sort URLs
    sort($urlFiles);
    
    // Group files by subdirectory if requested
    $grouped = [];
    if ($config['group_subdirs']) {
        foreach ($urlFiles as $urlPath) {
            $parts = explode('/', trim($urlPath, '/'));
            
            // Remove the base path component (e.g., 'src' or 'sima')
            array_shift($parts);
            
            if (count($parts) > 1) {
                // File is in a subdirectory
                $subdir = $parts[0];
                $grouped[$subdir][] = $urlPath;
            } else {
                // File is at root level
                $grouped['root'][] = $urlPath;
            }
        }
    }
    
    return [
        'config' => $config,
        'files' => $files,
        'urlFiles' => $urlFiles,
        'grouped' => $grouped
    ];
}

/**
 * Generate output in File Server URLs.md format
 */
function generateOutput(array $directoryConfigs, array $allFilesData, string $documentRoot): string {
    $totalFiles = 0;
    foreach ($allFilesData as $data) {
        $totalFiles += count($data['files']);
    }
    
    $output = "# SUGA-ISP Lambda - File Server URLs (Cache-Busted)\n\n";
    $output .= "**Generated:** " . date('Y-m-d H:i:s T') . "\n";
    $output .= "**Purpose:** Dynamic URL inventory with cache-busting\n";
    $output .= "**Total Files:** " . $totalFiles . "\n\n";
    $output .= "---\n\n";
    
    // Process each directory configuration
    foreach ($allFilesData as $configIndex => $data) {
        $config = $data['config'];
        $files = $data['files'];
        $urlFiles = $data['urlFiles'];
        $grouped = $data['grouped'];
        
        if (empty($urlFiles)) {
            continue; // Skip if no files
        }
        
        // Output header with domain info
        $label = strtoupper($config['label']);
        $domainInfo = '';
        if ($config['domain_catalog'] !== $config['domain_fetch']) {
            $domainInfo = " [Catalog: {$config['domain_catalog']}, Fetch: {$config['domain_fetch']}]";
        }
        
        $output .= "## 📂 {$label} ({$config['url_path']} - " . count($urlFiles) . " files){$domainInfo}\n\n";
        
        if ($config['group_subdirs'] && !empty($grouped)) {
            // Output grouped by subdirectories
            ksort($grouped);
            
            foreach ($grouped as $subdir => $dirFiles) {
                $dirTitle = strtoupper(str_replace(['-', '_'], ' ', $subdir));
                $dirPath = ($subdir === 'root') ? "{$config['url_path']} (root)" : "{$config['url_path']}/{$subdir}";
                $output .= "### {$dirTitle} ({$dirPath} - " . count($dirFiles) . " files)\n\n";
                $output .= "```\n";
                foreach ($dirFiles as $urlPath) {
                    $cacheBust = generateCacheBust();
                    // URL encode the path (spaces become %20)
                    $encodedPath = implode('/', array_map('rawurlencode', explode('/', $urlPath)));
                    $output .= "https://{$config['domain_fetch']}{$encodedPath}?v={$cacheBust}\n";
                }
                $output .= "```\n\n";
            }
        } else {
            // Output flat list (no subdirectory grouping)
            $output .= "```\n";
            foreach ($urlFiles as $urlPath) {
                $cacheBust = generateCacheBust();
                // URL encode the path (spaces become %20)
                $encodedPath = implode('/', array_map('rawurlencode', explode('/', $urlPath)));
                $output .= "https://{$config['domain_fetch']}{$encodedPath}?v={$cacheBust}\n";
            }
            $output .= "```\n\n";
        }
        
        $output .= "---\n\n";
    }
    
    $output .= "**END OF FILE**\n\n";
    $output .= "**Cache-Busting:** Enabled (random 10-digit parameters)\n";
    $output .= "**Regenerate:** Access this URL to get fresh cache-busting values\n";
    
    return $output;
}

// =============================================================================
// MAIN EXECUTION
// =============================================================================

// Set headers for plain text output
header('Content-Type: text/plain; charset=utf-8');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// Start time tracking
$startTime = microtime(true);

// Document root (where fileserver.php lives)
$documentRoot = __DIR__;

// Process each directory configuration
$allFilesData = [];
foreach ($DIRECTORY_CONFIGS as $config) {
    // Validate configuration
    if (!isset($config['path']) || !isset($config['url_path']) || 
        !isset($config['domain_fetch']) || !isset($config['label'])) {
        continue; // Skip invalid configs
    }
    
    // Set defaults
    if (!isset($config['domain_catalog'])) {
        $config['domain_catalog'] = $config['domain_fetch'];
    }
    if (!isset($config['group_subdirs'])) {
        $config['group_subdirs'] = false;
    }
    
    // Process this directory
    $data = processDirectoryConfig($config, $documentRoot, $EXCLUDED_DIRS, $EXCLUDED_FILES);
    
    // Filter by extension if configured
    $data['files'] = filterByExtension($data['files'], $ALLOWED_EXTENSIONS);
    
    // Regenerate urlFiles after filtering
    if (!empty($data['files'])) {
        $data['urlFiles'] = [];
        foreach ($data['files'] as $file) {
            $relativePath = str_replace($config['path'], $config['url_path'], $file);
            $relativePath = str_replace('\\', '/', $relativePath);
            $data['urlFiles'][] = $relativePath;
        }
        sort($data['urlFiles']);
        
        // Regroup if needed
        if ($config['group_subdirs']) {
            $data['grouped'] = [];
            foreach ($data['urlFiles'] as $urlPath) {
                $parts = explode('/', trim($urlPath, '/'));
                array_shift($parts);
                
                if (count($parts) > 1) {
                    $subdir = $parts[0];
                    $data['grouped'][$subdir][] = $urlPath;
                } else {
                    $data['grouped']['root'][] = $urlPath;
                }
            }
        }
    }
    
    $allFilesData[] = $data;
}

// Generate output
$output = generateOutput($DIRECTORY_CONFIGS, $allFilesData, $documentRoot);

// Output
echo $output;

// Add timing info as comment
$endTime = microtime(true);
$duration = round(($endTime - $startTime) * 1000, 2);

// Calculate total file count
$totalFiles = 0;
foreach ($allFilesData as $data) {
    $totalFiles += count($data['files']);
}

echo "\n<!-- Generated in {$duration}ms -->\n";
echo "<!-- Total files: {$totalFiles} -->\n";

