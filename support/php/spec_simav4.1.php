<?php
/**
 * spec_simav4.1.php
 * 
 * SIMA v4.1 Structure Specification
 * Version: 4.1.1
 * Date: 2025-11-22
 * Location: /support/php/
 * 
 * FIXED: Corrected detectVersion() path markers
 * FIXED: Made getCategories() domain-aware
 */

class SIMAv4_1_Spec {
    const VERSION = '4.1.1';
    const VERSION_SHORT = 'v4.1';
    
    /**
     * Get domain directories
     */
    public static function getDomains() {
        return ['context', 'docs', 'entries', 'integration', 'projects', 'support'];
    }
    
    /**
     * Get category directories for domains
     * Returns domain-keyed array of categories
     * 
     * FIXED: Changed from flat array to domain-keyed structure
     */
    public static function getCategories() {
        return [
            'entries' => [
                'core',
                'gateways',
                'interfaces',
                'languages',
                'anti-patterns',
                'decisions',
                'lessons',
                'platforms'
            ],
            'context' => [],
            'docs' => [],
            'integration' => [],
            'projects' => [],
            'support' => []
        ];
    }
    
    /**
     * Get index file naming pattern
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
     * Get master index locations
     */
    public static function getMasterIndexes() {
        return [
            'root' => 'Master-Index-of-Indexes.md'
        ];
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
     * Get required directories for a domain
     * 
     * MODIFIED: Updated to use new getCategories() structure
     */
    public static function getRequiredDirectories($domain) {
        $base = [
            $domain
        ];
        
        if ($domain === 'entries') {
            $categories = self::getCategories()['entries'];
            foreach ($categories as $category) {
                $base[] = $domain . '/' . $category;
            }
        }
        
        return $base;
    }
    
    /**
     * Get support files
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
     * Detect if this is v4.1
     * 
     * FIXED: Removed /simav4 prefix from markers since $basePath already points there
     */
    public static function detectVersion($basePath) {
        $markers = [
            '/entries/core',
            '/integration'
        ];
        
        foreach ($markers as $marker) {
            if (!is_dir($basePath . $marker)) {
                return false;
            }
        }
        
        // v4.1 should NOT have v4.2 domain structure
        if (is_dir($basePath . '/generic')) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Get conversion rules to v4.2
     */
    public static function getConversionToV42() {
        return [
            'directory_mapping' => [
                'entries/core' => 'generic/core',
                'entries/gateways' => 'generic/core',
                'entries/interfaces' => 'generic/core',
                'entries/anti-patterns' => 'generic/anti-patterns',
                'entries/decisions' => 'generic/decisions',
                'entries/lessons' => 'generic/lessons',
                'entries/languages' => 'languages/python',
                'entries/platforms' => 'platforms/aws'
            ],
            'metadata_transform' => [
                'remove_fields' => [],
                'add_fields' => []
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
                'remove_fields' => [],
                'add_fields' => []
            ],
            'filename_transform' => [],
            'ref_id_mapping' => []
        ];
    }
}
