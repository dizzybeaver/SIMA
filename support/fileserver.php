<?php
// fileserver.php
// Version: 2.1.0
// Date: 2025-11-04
// Purpose: Generate File Server URLs.md with cache-busting parameters (FAST - uses filesystem)
// MODIFIED: Added subdirectory grouping for /src files (similar to /sima)

// =============================================================================
// CONFIGURATION
// =============================================================================

// Domain (no trailing slash)
$DOMAIN = 'claude.dizzybeaver.com';

// Base paths to crawl (relative to document root, or absolute paths)
// If your document root is /var/www/html, these will be /var/www/html/src, etc.
$BASE_PATHS = [
    __DIR__ . '/src',    // Assuming fileserver.php is in web root
    __DIR__ . '/sima'
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
 * Generate output in File Server URLs.md format
 */
function generateOutput(string $domain, array $files, string $documentRoot): string {
    $output = "# SUGA-ISP Lambda - File Server URLs (Cache-Busted)\n\n";
    $output .= "**Generated:** " . date('Y-m-d H:i:s T') . "\n";
    $output .= "**Purpose:** Dynamic URL inventory with cache-busting\n";
    $output .= "**Total Files:** " . count($files) . "\n\n";
    $output .= "---\n\n";
    
    // Convert filesystem paths to URLs and group
    $urlFiles = [];
    foreach ($files as $file) {
        $urlPath = pathToUrl($file, $documentRoot);
        $urlFiles[] = $urlPath;
    }
    
    // Sort URLs
    sort($urlFiles);
    
    // Group files by base directory
    $grouped = [];
    foreach ($urlFiles as $urlPath) {
        // MODIFIED: Added subdirectory grouping for /src files
        if (str_starts_with($urlPath, '/src/')) {
            // Further group by first subdirectory
            $parts = explode('/', trim($urlPath, '/'));
            if (count($parts) >= 2) {
                // Check if there's a subdirectory (parts[1] is subdirectory name)
                if (count($parts) > 2) {
                    // File is in a subdirectory like /src/home_assistant/file.py
                    $subdir = $parts[1];
                    $grouped['src'][$subdir][] = $urlPath;
                } else {
                    // File is at root level like /src/file.py
                    $grouped['src']['root'][] = $urlPath;
                }
            } else {
                $grouped['src']['root'][] = $urlPath;
            }
        } elseif (str_starts_with($urlPath, '/sima/')) {
            // Further group by first subdirectory
            $parts = explode('/', trim($urlPath, '/'));
            if (count($parts) >= 2) {
                $subdir = $parts[1];
                $grouped['sima'][$subdir][] = $urlPath;
            } else {
                $grouped['sima']['root'][] = $urlPath;
            }
        } else {
            $grouped['other'][] = $urlPath;
        }
    }
    
    // MODIFIED: Output /src files grouped by subdirectory
    if (!empty($grouped['src'])) {
        ksort($grouped['src']);
        
        // Count total files
        $totalSrcFiles = 0;
        foreach ($grouped['src'] as $dirFiles) {
            $totalSrcFiles += count($dirFiles);
        }
        
        $output .= "## 📂 PYTHON SOURCE FILES (/src - {$totalSrcFiles} files)\n\n";
        
        foreach ($grouped['src'] as $subdir => $dirFiles) {
            $dirTitle = strtoupper(str_replace(['-', '_'], ' ', $subdir));
            $dirPath = ($subdir === 'root') ? '/src (root)' : "/src/{$subdir}";
            $output .= "### {$dirTitle} ({$dirPath} - " . count($dirFiles) . " files)\n\n";
            $output .= "```\n";
            foreach ($dirFiles as $urlPath) {
                $cacheBust = generateCacheBust();
                // URL encode the path (spaces become %20)
                $encodedPath = implode('/', array_map('rawurlencode', explode('/', $urlPath)));
                $output .= "https://{$domain}{$encodedPath}?v={$cacheBust}\n";
            }
            $output .= "```\n\n";
        }
        
        $output .= "---\n\n";
    }
    
    // Output /sima files grouped by subdirectory
    if (!empty($grouped['sima'])) {
        ksort($grouped['sima']);
        
        foreach ($grouped['sima'] as $subdir => $dirFiles) {
            $dirTitle = strtoupper(str_replace(['-', '_'], ' ', $subdir));
            $output .= "## 📂 {$dirTitle} (/sima/{$subdir} - " . count($dirFiles) . " files)\n\n";
            $output .= "```\n";
            foreach ($dirFiles as $urlPath) {
                $cacheBust = generateCacheBust();
                // URL encode the path (spaces become %20)
                $encodedPath = implode('/', array_map('rawurlencode', explode('/', $urlPath)));
                $output .= "https://{$domain}{$encodedPath}?v={$cacheBust}\n";
            }
            $output .= "```\n\n";
        }
        
        $output .= "---\n\n";
    }
    
    // Output other files if any
    if (!empty($grouped['other'])) {
        $output .= "## 📂 OTHER FILES (" . count($grouped['other']) . " files)\n\n";
        $output .= "```\n";
        foreach ($grouped['other'] as $urlPath) {
            $cacheBust = generateCacheBust();
            $encodedPath = implode('/', array_map('rawurlencode', explode('/', $urlPath)));
            $output .= "https://{$domain}{$encodedPath}?v={$cacheBust}\n";
        }
        $output .= "```\n\n";
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

// Collect all files
$allFiles = [];
foreach ($BASE_PATHS as $basePath) {
    $files = scanDirectoryRecursive($basePath, $EXCLUDED_DIRS, $EXCLUDED_FILES);
    $allFiles = array_merge($allFiles, $files);
}

// Filter by extension if configured
$allFiles = filterByExtension($allFiles, $ALLOWED_EXTENSIONS);

// Generate output
$output = generateOutput($DOMAIN, $allFiles, $documentRoot);

// Output
echo $output;

// Add timing info as comment
$endTime = microtime(true);
$duration = round(($endTime - $startTime) * 1000, 2);
echo "\n<!-- Generated in {$duration}ms -->\n";
echo "<!-- Total files: " . count($allFiles) . " -->\n";
