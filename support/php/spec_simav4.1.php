<?php
/**
 * spec_simav4.1.php
 * 
 * SIMA v4.1 Structure Specification
 * Version: 4.1.0
 * Date: 2025-11-22
 * Base Directory: /simav4/
 * 
 * Defines directory structure for SIMA v4.1 (entries-based organization)
 */

class SIMAv4_1_Spec {
    const VERSION = 'SIMA v4.1.0';
    const VERSION_NUMBER = '4.1';
    const BASE_DIR = '/simav4';
    
    /**
     * Detect if directory is v4.1
     */
    public static function detectVersion($basePath) {
        // v4.1 has /entries/ structure
        $has_entries = is_dir($basePath . self::BASE_DIR . '/entries/core') && 
                       is_dir($basePath . self::BASE_DIR . '/entries/gateways');
        
        // v4.1 does NOT have domain separation
        $no_domains = !is_dir($basePath . self::BASE_DIR . '/generic') &&
                      !is_dir($basePath . self::BASE_DIR . '/languages');
        
        // v4.1 has integration directory
        $has_integration = is_dir($basePath . self::BASE_DIR . '/integration');
        
        return $has_entries && $no_domains && $has_integration;
    }
    
    /**
     * Get domains - v4.1 has no domain separation
     * Returns main directories instead
     */
    public static function getDomains() {
        return ['entries'];  // Only one "domain" in v4.1
    }
    
    /**
     * Get categories under /entries/
     */
    public static function getCategories() {
        return [
            'core',
            'gateways',
            'interfaces',
            'languages',
            'anti-patterns',
            'decisions',
            'lessons',
            'platforms'
        ];
    }
    
    /**
     * Get support files/directories
     */
    public static function getSupportFiles() {
        return [
            'context',
            'docs',
            'integration',
            'projects',
            'support'
        ];
    }
    
    /**
     * Get index patterns for categories
     */
    public static function getIndexPattern($category) {
        $patterns = [
            'core' => 'Core-Architecture-Quick-Index.md',
            'gateways' => 'Gateway-Quick-Index.md',
            'interfaces' => 'Interface-Quick-Index.md',
            'languages' => 'Python-Language-Patterns-Quick-Index.md',
            'anti-patterns' => 'Anti-Patterns-Master-Index.md',
            'decisions' => 'Decisions-Master-Index.md',
            'lessons' => 'Lessons-Master-Index.md',
            'platforms' => 'Platforms-Master-Index.md'
        ];
        
        return $patterns[$category] ?? ucfirst($category) . '-Index.md';
    }
    
    /**
     * Get metadata field mapping
     */
    public static function getMetadataFields() {
        return [
            'version' => '**Version:**',
            'date' => '**Date:**',
            'purpose' => '**Purpose:**',
            'type' => '**Type:**',
            'category' => '**Category:**',
            'ref_id' => '**REF-ID:**'
        ];
    }
    
    /**
     * Get file naming patterns
     */
    public static function getFileNamingPatterns() {
        return [
            'lesson' => 'LESS-{number}.md',
            'decision' => 'DEC-{number}.md',
            'anti-pattern' => 'AP-{number}.md',
            'wisdom' => 'WISD-{number}.md',
            'bug' => 'BUG-{number}.md',
            'architecture' => 'ARCH-{name}.md',
            'interface' => 'INT-{number}.md',
            'gateway' => 'GATE-{number}.md'
        ];
    }
    
    /**
     * Get required directories
     */
    public static function getRequiredDirectories() {
        return [
            self::BASE_DIR . '/context',
            self::BASE_DIR . '/docs',
            self::BASE_DIR . '/entries',
            self::BASE_DIR . '/entries/core',
            self::BASE_DIR . '/entries/gateways',
            self::BASE_DIR . '/entries/interfaces',
            self::BASE_DIR . '/entries/languages',
            self::BASE_DIR . '/entries/anti-patterns',
            self::BASE_DIR . '/entries/decisions',
            self::BASE_DIR . '/entries/lessons',
            self::BASE_DIR . '/integration',
            self::BASE_DIR . '/projects',
            self::BASE_DIR . '/support'
        ];
    }
    
    /**
     * Get master indexes
     */
    public static function getMasterIndexes() {
        return [
            'root' => 'Master-Index-of-Indexes.md',
            'entries' => 'entries/Index-of-Indexes.md'
        ];
    }
    
    /**
     * Get category path
     */
    public static function getCategoryPath($category) {
        return self::BASE_DIR . '/entries/' . $category;
    }
    
    /**
     * Get conversion rules to v4.2
     */
    public static function getConversionToV42() {
        return [
            'directory_mapping' => [
                'entries/core' => 'generic/core',
                'entries/anti-patterns' => 'generic/anti-patterns',
                'entries/decisions' => 'generic/decisions',
                'entries/lessons' => 'generic/lessons',
                'entries/gateways' => 'generic/core',
                'entries/interfaces' => 'generic/core',
                'entries/languages' => 'languages/python',
                'entries/platforms' => 'platforms/aws'
            ],
            'metadata_transform' => [
                'add_fields' => [
                    '**Domain:**'
                ],
                'remove_fields' => []
            ],
            'filename_transform' => [],
            'ref_id_mapping' => []
        ];
    }
    
    /**
     * Get conversion rules from v4.2
     */
    public static function getConversionFromV42() {
        return [
            'directory_mapping' => [
                'generic/core' => 'entries/core',
                'generic/anti-patterns' => 'entries/anti-patterns',
                'generic/decisions' => 'entries/decisions',
                'generic/lessons' => 'entries/lessons',
                'languages/python' => 'entries/languages',
                'platforms/aws' => 'entries/platforms'
            ],
            'metadata_transform' => [
                'add_fields' => [],
                'remove_fields' => ['**Domain:**']
            ],
            'filename_transform' => [],
            'ref_id_mapping' => []
        ];
    }
}

// No output - this is a class definition only
// The version utils will call the methods
