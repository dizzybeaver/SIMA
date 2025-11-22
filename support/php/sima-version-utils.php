<?php
/**
 * sima-version-utils.php
 * 
 * SIMA Version Detection and Conversion Utilities
 * Version: 1.0.0
 * Date: 2025-11-21
 * Location: /support/php/
 * 
 * Handles version detection and conversion between SIMA versions
 */

require_once __DIR__ . '/spec_simav4.2.php';
require_once __DIR__ . '/spec_simav4.1.php';

class SIMAVersionUtils {
    
    /**
     * Auto-detect SIMA version from directory
     */
    public static function detectVersion($basePath) {
        // Try v4.2 first
        if (SIMAv4_2_Spec::detectVersion($basePath)) {
            return '4.2';
        }
        
        // Try v4.1
        if (SIMAv4_1_Spec::detectVersion($basePath)) {
            return '4.1';
        }
        
        // Check for generic v4 markers
        if (file_exists($basePath . '/generic') && 
            file_exists($basePath . '/platforms')) {
            return '4.0';  // Generic v4
        }
        
        // Check for v3
        if (file_exists($basePath . '/entries')) {
            return '3.0';
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
            '4.1' => 'SIMA v4.1'
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
            '4.2' => ['4.1']
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
            if (!is_dir($domainPath)) continue;
            
            $tree[$domain] = self::scanDomain($domainPath, $domain, $categories, $basePath);
        }
        
        return $tree;
    }
    
    /**
     * Scan domain directory
     */
    private static function scanDomain($domainPath, $domainName, $categories, $basePath) {
        $domain = [
            'name' => $domainName,
            'path' => $domainPath,
            'categories' => [],
            'total_files' => 0
        ];
        
        foreach ($categories as $category) {
            $categoryPath = $domainPath . '/' . $category;
            if (!is_dir($categoryPath)) continue;
            
            $files = glob($categoryPath . '/*.md');
            $fileList = [];
            
            foreach ($files as $file) {
                $filename = basename($file);
                
                // Skip index files
                if (stripos($filename, 'index') !== false || 
                    stripos($filename, 'master') !== false) {
                    continue;
                }
                
                $fileList[] = [
                    'filename' => $filename,
                    'path' => $file,
                    'relative_path' => str_replace($basePath . '/', '', $file)
                ];
            }
            
            if (!empty($fileList)) {
                $domain['categories'][$category] = [
                    'name' => $category,
                    'path' => $categoryPath,
                    'files' => $fileList,
                    'file_count' => count($fileList)
                ];
                $domain['total_files'] += count($fileList);
            }
        }
        
        return $domain;
    }
}
