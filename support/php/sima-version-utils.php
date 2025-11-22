<?php
/**
 * sima-version-utils.php
 * 
 * SIMA Version Detection and Conversion Utilities
 * Version: 2.0.0
 * Date: 2025-11-22
 * Location: /support/php/
 * 
 * MODIFIED: Added SIMA v3.0 support
 * 
 * Handles version detection and conversion between SIMA versions
 * Supports: v3.0, v4.1, v4.2
 */

require_once __DIR__ . '/spec_simav3.php';
require_once __DIR__ . '/spec_simav4.1.php';
require_once __DIR__ . '/spec_simav4.2.php';

class SIMAVersionUtils {
    
    /**
     * Auto-detect SIMA version from directory
     */
    public static function detectVersion($basePath) {
        // Try v4.2 first (most recent)
        if (SIMAv4_2_Spec::detectVersion($basePath)) {
            return '4.2';
        }
        
        // Try v4.1
        if (SIMAv4_1_Spec::detectVersion($basePath)) {
            return '4.1';
        }
        
        // Try v3.0
        if (SIMAv3_Spec::detectVersion($basePath)) {
            return '3.0';
        }
        
        // Check for generic v4 markers
        if (file_exists($basePath . '/generic') && 
            file_exists($basePath . '/platforms')) {
            return '4.0';
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
                return 'SIMAv4_2_Spec';
            case '4.1':
            case '4.1.0':
                return 'SIMAv4_1_Spec';
            case '3.0':
            case '3':
                return 'SIMAv3_Spec';
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
            $rules = SIMAv4_1_Spec::getConversionToV42();
        } elseif ($fromVersion === '4.2' && $toVersion === '4.1') {
            $rules = SIMAv4_2_Spec::getConversionToV41();
        } elseif ($fromVersion === '3.0' && $toVersion === '4.2') {
            $rules = SIMAv3_Spec::getConversionToV42();
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
            $rules = SIMAv4_1_Spec::getConversionToV42();
        } elseif ($fromVersion === '4.2' && $toVersion === '4.1') {
            $rules = SIMAv4_2_Spec::getConversionToV41();
        } elseif ($fromVersion === '3.0' && $toVersion === '4.2') {
            $rules = SIMAv3_Spec::getConversionToV42();
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
            $rules = SIMAv4_1_Spec::getConversionToV42();
        } elseif ($fromVersion === '4.2' && $toVersion === '4.1') {
            $rules = SIMAv4_2_Spec::getConversionToV41();
        } elseif ($fromVersion === '3.0' && $toVersion === '4.2') {
            $rules = SIMAv3_Spec::getConversionToV42();
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
            $rules = SIMAv4_1_Spec::getConversionToV42();
        } elseif ($fromVersion === '4.2' && $toVersion === '4.1') {
            $rules = SIMAv4_2_Spec::getConversionToV41();
        } elseif ($fromVersion === '3.0' && $toVersion === '4.2') {
            $rules = SIMAv3_Spec::getConversionToV42();
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
            '3.0' => ['4.2']  // ADDED: v3 to v4.2 conversion
        ];
        
        return isset($supported[$fromVersion]) && 
               in_array($toVersion, $supported[$fromVersion]);
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
            $info['support_files'] = $specClass::getSupportFiles();
        }
        
        return $info;
    }
    
    /**
     * Scan directory with version-aware structure
     */
    public static function scanWithVersion($basePath, $version = null) {
        if ($version === null) {
            $version = self::detectVersion($basePath);
        }
        
        $specClass = self::getSpec($version);
        if (!$specClass) {
            throw new Exception("Unsupported SIMA version: $version");
        }
        
        $tree = [];
        $domains = $specClass::getDomains();
        $categories = $specClass::getCategories();
        
        foreach ($domains as $domain) {
            $domainPath = $basePath . '/' . $domain;
            
            if (!is_dir($domainPath)) {
                continue;
            }
            
            $tree[$domain] = [
                'total_files' => 0,
                'categories' => []
            ];
            
            // Scan for files in domain
            if (isset($categories[$domain])) {
                foreach ($categories[$domain] as $category) {
                    $categoryPath = $domainPath . '/' . $category;
                    
                    if (is_dir($categoryPath)) {
                        $files = glob($categoryPath . '/*.md');
                    } else {
                        // For v3, categories might be part of filename
                        $files = glob($domainPath . '/*' . $category . '*.md');
                    }
                    
                    if (!empty($files)) {
                        $tree[$domain]['categories'][$category] = [
                            'file_count' => count($files),
                            'files' => []
                        ];
                        
                        foreach ($files as $file) {
                            $relativePath = str_replace($basePath . '/', '', $file);
                            $tree[$domain]['categories'][$category]['files'][] = [
                                'filename' => basename($file),
                                'relative_path' => $relativePath
                            ];
                            $tree[$domain]['total_files']++;
                        }
                    }
                }
            } else {
                // Scan all files in domain directly
                $files = glob($domainPath . '/*.md');
                if (!empty($files)) {
                    $tree[$domain]['categories']['root'] = [
                        'file_count' => count($files),
                        'files' => []
                    ];
                    
                    foreach ($files as $file) {
                        $relativePath = str_replace($basePath . '/', '', $file);
                        $tree[$domain]['categories']['root']['files'][] = [
                            'filename' => basename($file),
                            'relative_path' => $relativePath
                        ];
                        $tree[$domain]['total_files']++;
                    }
                }
            }
        }
        
        return $tree;
    }
}
