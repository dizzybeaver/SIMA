<?php
/**
 * sima-version-utils.php
 * 
 * SIMA Version Detection and Conversion Utilities
 * Version: 4.0.1
 * Date: 2025-11-23
 * Location: /support/php/
 * 
 * FIXED: Safe file loading with error handling
 * UPDATED: Correct version detection for different SIMA structures
 */

// Safely load spec files if they exist
$specFiles = [
    'spec_simav3.php',
    'spec_simav4.1.php', 
    'spec_simav4.2.php'
];

foreach ($specFiles as $specFile) {
    $filepath = __DIR__ . '/' . $specFile;
    if (file_exists($filepath)) {
        require_once $filepath;
    }
}

// Load other required files
if (file_exists(__DIR__ . '/sima-scanner.php')) {
    require_once __DIR__ . '/sima-scanner.php';
}
if (file_exists(__DIR__ . '/sima-tree-formatter.php')) {
    require_once __DIR__ . '/sima-tree-formatter.php';
}

class SIMAVersionUtils {
    
    /**
     * Auto-detect SIMA version from directory
     * UPDATED: Correct detection for different SIMA structures
     */
    public static function detectVersion($basePath) {
        // Check if directory exists and is readable
        if (!is_dir($basePath) || !is_readable($basePath)) {
            return 'unknown';
        }

        // Try v4.2 first (generic/ and platforms/ directories)
        if (is_dir($basePath . '/generic') && is_dir($basePath . '/platforms')) {
            if (class_exists('SIMAv4_2_Spec') && SIMAv4_2_Spec::detectVersion($basePath)) {
                return '4.2';
            }
            return '4.2'; // Fallback if spec class not available
        }
        
        // Try v4.1 (entries/ and integration/ directories)
        if (is_dir($basePath . '/entries') && is_dir($basePath . '/integration')) {
            if (class_exists('SIMAv4_1_Spec') && SIMAv4_1_Spec::detectVersion($basePath)) {
                return '4.1';
            }
            return '4.1'; // Fallback if spec class not available
        }
        
        // Try v3.0 (NM## directories)
        if (is_dir($basePath . '/NM00') && is_dir($basePath . '/NM01')) {
            if (class_exists('SIMAv3_Spec') && SIMAv3_Spec::detectVersion($basePath)) {
                return '3.0';
            }
            return '3.0'; // Fallback if spec class not available
        }
        
        return 'unknown';
    }
    
    /**
     * Get spec class for version
     */
    public static function getSpec($version) {
        switch ($version) {
            case '4.2':
            case '4.2.2':
                return class_exists('SIMAv4_2_Spec') ? 'SIMAv4_2_Spec' : null;
            case '4.1':
            case '4.1.0':
                return class_exists('SIMAv4_1_Spec') ? 'SIMAv4_1_Spec' : null;
            case '3.0':
            case '3':
                return class_exists('SIMAv3_Spec') ? 'SIMAv3_Spec' : null;
            default:
                return null;
        }
    }
    
    /**
     * Get available versions
     */
    public static function getAvailableVersions() {
        return [
            '4.2' => 'SIMA v4.2 (Latest)',
            '4.1' => 'SIMA v4.1',
            '3.0' => 'SIMA v3.0 (Neural Maps)'
        ];
    }
    
    /**
     * Get version info
     */
    public static function getVersionInfo($basePath) {
        $version = self::detectVersion($basePath);
        $specClass = self::getSpec($version);
        
        $info = [
            'version' => $version,
            'version_string' => $specClass ? $specClass::VERSION : 'Unknown',
            'detected' => true,
            'spec_available' => $specClass !== null
        ];
        
        if ($specClass) {
            $info['domains'] = $specClass::getDomains();
            $info['categories'] = $specClass::getCategories();
            $info['support_files'] = method_exists($specClass, 'getSupportFiles') ? $specClass::getSupportFiles() : [];
        }
        
        return $info;
    }
    
    /**
     * Comprehensive scan using new scanner
     */
    public static function scanWithVersion($basePath, $version = null) {
        if ($version === null) {
            $version = self::detectVersion($basePath);
        }
        
        // Use comprehensive scanner
        $scanResult = SIMAScanner::scanComplete($basePath, [
            'include_hidden' => false,
            'max_depth' => 20,
            'file_extensions' => ['.md'],
            'exclude_dirs' => ['.git', 'node_modules', '.idea', '__pycache__'],
            'include_metadata' => true
        ]);
        
        // Convert to UI format
        $uiTree = SIMATreeFormatter::formatForUI($scanResult);
        
        return $uiTree;
    }
    
    /**
     * Get flat file list for export
     */
    public static function getFlatFileList($basePath, $version = null) {
        if ($version === null) {
            $version = self::detectVersion($basePath);
        }
        
        $scanResult = SIMAScanner::scanComplete($basePath);
        return SIMATreeFormatter::getFlatFileList($scanResult);
    }
    
    /**
     * Get statistics about SIMA installation
     */
    public static function getStats($basePath, $version = null) {
        if ($version === null) {
            $version = self::detectVersion($basePath);
        }
        
        $scanResult = SIMAScanner::scanComplete($basePath);
        return SIMATreeFormatter::generateStats($scanResult);
    }
    
    /**
     * Convert file metadata between versions
     */
    public static function convertMetadata($content, $fromVersion, $toVersion) {
        $fromSpec = self::getSpec($fromVersion);
        $toSpec = self::getSpec($toVersion);
        
        if (!$fromSpec || !$toSpec) {
            return $content;
        }
        
        $lines = explode("\n", $content);
        $converted = [];
        
        // Get conversion rules
        $rules = null;
        if ($fromVersion === '4.1' && $toVersion === '4.2') {
            $rules = method_exists($fromSpec, 'getConversionToV42') ? $fromSpec::getConversionToV42() : null;
        } elseif ($fromVersion === '4.2' && $toVersion === '4.1') {
            $rules = method_exists($fromSpec, 'getConversionToV41') ? $fromSpec::getConversionToV41() : null;
        } elseif ($fromVersion === '3.0' && $toVersion === '4.2') {
            $rules = method_exists($fromSpec, 'getConversionToV42') ? $fromSpec::getConversionToV42() : null;
        }
        
        if (!$rules) {
            return $content;
        }
        
        foreach ($lines as $line) {
            // Handle field additions
            if (isset($rules['metadata_transform']['add_fields'])) {
                foreach ($rules['metadata_transform']['add_fields'] as $field) {
                    if (strpos($line, '**Purpose:**') !== false) {
                        $converted[] = $line;
                        $converted[] = $field;
                        continue 2;
                    }
                }
            }
            
            // Handle field removals
            if (isset($rules['metadata_transform']['remove_fields'])) {
                $skip = false;
                foreach ($rules['metadata_transform']['remove_fields'] as $field) {
                    if (strpos($line, $field) !== false) {
                        $skip = true;
                        break;
                    }
                }
                if ($skip) continue;
            }
            
            $converted[] = $line;
        }
        
        return implode("\n", $converted);
    }
    
    /**
     * Convert filename between versions
     */
    public static function convertFilename($filename, $fromVersion, $toVersion) {
        $rules = null;
        
        if ($fromVersion === '4.1' && $toVersion === '4.2') {
            $fromSpec = self::getSpec($fromVersion);
            $rules = method_exists($fromSpec, 'getConversionToV42') ? $fromSpec::getConversionToV42() : null;
        } elseif ($fromVersion === '4.2' && $toVersion === '4.1') {
            $fromSpec = self::getSpec($fromVersion);
            $rules = method_exists($fromSpec, 'getConversionToV41') ? $fromSpec::getConversionToV41() : null;
        } elseif ($fromVersion === '3.0' && $toVersion === '4.2') {
            $fromSpec = self::getSpec($fromVersion);
            $rules = method_exists($fromSpec, 'getConversionToV42') ? $fromSpec::getConversionToV42() : null;
        }
        
        if (!$rules || !isset($rules['filename_transform'])) {
            return $filename;
        }
        
        foreach ($rules['filename_transform'] as $from => $to) {
            $filename = str_replace($from, $to, $filename);
        }
        
        return $filename;
    }
    
    /**
     * Convert directory path between versions
     */
    public static function convertPath($path, $fromVersion, $toVersion) {
        $rules = null;
        
        if ($fromVersion === '4.1' && $toVersion === '4.2') {
            $fromSpec = self::getSpec($fromVersion);
            $rules = method_exists($fromSpec, 'getConversionToV42') ? $fromSpec::getConversionToV42() : null;
        } elseif ($fromVersion === '4.2' && $toVersion === '4.1') {
            $fromSpec = self::getSpec($fromVersion);
            $rules = method_exists($fromSpec, 'getConversionToV41') ? $fromSpec::getConversionToV41() : null;
        } elseif ($fromVersion === '3.0' && $toVersion === '4.2') {
            $fromSpec = self::getSpec($fromVersion);
            $rules = method_exists($fromSpec, 'getConversionToV42') ? $fromSpec::getConversionToV42() : null;
        }
        
        if (!$rules || !isset($rules['directory_mapping'])) {
            return $path;
        }
        
        foreach ($rules['directory_mapping'] as $from => $to) {
            $path = str_replace('/' . $from . '/', '/' . $to . '/', $path);
            $path = str_replace('/' . $from, '/' . $to, $path);
        }
        
        return $path;
    }
    
    /**
     * Convert index filename between versions
     */
    public static function convertIndexName($indexName, $fromVersion, $toVersion) {
        $rules = null;
        
        if ($fromVersion === '4.1' && $toVersion === '4.2') {
            $fromSpec = self::getSpec($fromVersion);
            $rules = method_exists($fromSpec, 'getConversionToV42') ? $fromSpec::getConversionToV42() : null;
        } elseif ($fromVersion === '4.2' && $toVersion === '4.1') {
            $fromSpec = self::getSpec($fromVersion);
            $rules = method_exists($fromSpec, 'getConversionToV41') ? $fromSpec::getConversionToV41() : null;
        } elseif ($fromVersion === '3.0' && $toVersion === '4.2') {
            $fromSpec = self::getSpec($fromVersion);
            $rules = method_exists($fromSpec, 'getConversionToV42') ? $fromSpec::getConversionToV42() : null;
        }
        
        if (!$rules || !isset($rules['index_mapping'])) {
            return $indexName;
        }
        
        return $rules['index_mapping'][$indexName] ?? $indexName;
    }
    
    /**
     * Check if conversion is supported
     */
    public static function canConvert($fromVersion, $toVersion) {
        $supported = [
            '4.1' => ['4.2'],
            '4.2' => ['4.1'],
            '3.0' => ['4.2']
        ];
        
        return isset($supported[$fromVersion]) && 
               in_array($toVersion, $supported[$fromVersion]);
    }
}
?>
