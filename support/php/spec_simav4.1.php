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
    const VERSION = '4.1.0';
    const VERSION_SHORT = 'v4.1';
    const BASE_DIR = '/simav4';
    
    /**
     * Get main directories (no domain separation in v4.1)
     */
    public static function getMainDirectories() {
        return ['context', 'docs', 'entries', 'integration', 'projects', 'support'];
    }
    
    /**
     * Get entry categories (all under /entries/)
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
     * Get index file patterns
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
     * Get required directories for v4.1
     */
    public static function getRequiredDirectories() {
        return [
            self::BASE_DIR,
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
     * Get the directory structure for category
     */
    public static function getCategoryPath($category) {
        return self::BASE_DIR . '/entries/' . $category;
    }
    
    /**
     * Check if this is a v4.1 installation
     */
    public static function isV41Installation($root_path = '') {
        if (empty($root_path)) {
            $root_path = $_SERVER['DOCUMENT_ROOT'] ?? '';
        }
        
        $markers = [
            self::BASE_DIR . '/entries/core',
            self::BASE_DIR . '/entries/gateways',
            self::BASE_DIR . '/integration'
        ];
        
        foreach ($markers as $marker) {
            if (!is_dir($root_path . $marker)) {
                return false;
            }
        }
        
        // v4.1 should NOT have domain separation
        $v42_markers = [
            self::BASE_DIR . '/generic',
            self::BASE_DIR . '/languages',
            self::BASE_DIR . '/platforms'
        ];
        
        foreach ($v42_markers as $marker) {
            if (is_dir($root_path . $marker)) {
                return false; // Has v4.2 structure
            }
        }
        
        return true;
    }
    
    /**
     * Get differences from v4.2
     */
    public static function getDifferencesFromV42() {
        return [
            'base_dir' => 'Uses /simav4/ not /sima/',
            'structure' => 'Uses /entries/ not domain separation',
            'domains' => 'No /generic/, /languages/, /platforms/, /projects/ domains',
            'integration' => 'Has /integration/ directory',
            'context' => 'Flat /context/ directory structure'
        ];
    }
}

// Return spec as JSON for export tool
header('Content-Type: application/json');

$spec = [
    'version' => SIMAv4_1_Spec::VERSION,
    'base_dir' => SIMAv4_1_Spec::BASE_DIR,
    'main_directories' => SIMAv4_1_Spec::getMainDirectories(),
    'categories' => SIMAv4_1_Spec::getCategories(),
    'index_patterns' => array_map(
        [SIMAv4_1_Spec::class, 'getIndexPattern'],
        SIMAv4_1_Spec::getCategories()
    ),
    'metadata_fields' => SIMAv4_1_Spec::getMetadataFields(),
    'file_naming' => SIMAv4_1_Spec::getFileNamingPatterns(),
    'required_directories' => SIMAv4_1_Spec::getRequiredDirectories(),
    'differences_from_v42' => SIMAv4_1_Spec::getDifferencesFromV42()
];

echo json_encode($spec, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
