<?php
/**
 * spec_simav4.1.php
 * 
 * SIMA v4.1 Structure Specification
 * Version: 4.1.0
 * Date: 2025-11-22
 * Location: /support/php/
 * Base Directory: /simav4/
 * 
 * Defines directory structure, index locations, and metadata format for SIMA v4.1
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
     * Get category directories under /entries/
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
            'platforms'  // Optional in v4.1
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
     * Get expected file counts
     */
    public static function getExpectedCounts() {
        return [
            'context' => ['min' => 5, 'max' => 15],
            'docs' => ['min' => 15, 'max' => 25],
            'entries' => ['min' => 200, 'max' => 250],
            'integration' => ['min' => 3, 'max' => 10],
            'projects' => ['min' => 20, 'max' => 50],
            'support' => ['min' => 30, 'max' => 50],
            'total' => ['min' => 280, 'max' => 350]
        ];
    }
    
    /**
     * Get key differences from v4.2
     */
    public static function getDifferencesFromV42() {
        return [
            'base_dir' => 'Uses /simav4/ instead of /sima/',
            'structure' => 'Uses /entries/ instead of domain separation',
            'domains' => 'No /generic/, /languages/, /platforms/, /projects/ domains',
            'integration' => 'Has /integration/ directory (v4.2 integrates into docs)',
            'context' => 'Flat /context/ directory (v4.2 has subdirectories)'
        ];
    }
    
    /**
     * Validate directory structure
     */
    public static function validate($base_path = '') {
        $results = [
            'valid' => true,
            'errors' => [],
            'warnings' => [],
            'version' => self::VERSION
        ];
        
        if (empty($base_path)) {
            $base_path = $_SERVER['DOCUMENT_ROOT'] ?? '';
        }
        
        // Check base directory
        $full_base = $base_path . self::BASE_DIR;
        if (!is_dir($full_base)) {
            $results['valid'] = false;
            $results['errors'][] = 'Base directory not found: ' . self::BASE_DIR;
            return $results;
        }
        
        // Check required directories
        foreach (self::getRequiredDirectories() as $dir) {
            $full_path = $base_path . $dir;
            if (!is_dir($full_path)) {
                $results['valid'] = false;
                $results['errors'][] = 'Required directory missing: ' . $dir;
            }
        }
        
        // Check for required files
        $required_files = [
            '/README.md',
            '/LICENSE',
            '/context/MODE-SELECTOR.md',
            '/context/PROJECT-MODE-Context.md',
            '/entries/core/ARCH-SUGA_ Single Universal Gateway Architecture.md'
        ];
        
        foreach ($required_files as $file) {
            $full_path = $base_path . self::BASE_DIR . $file;
            if (!file_exists($full_path)) {
                $results['valid'] = false;
                $results['errors'][] = 'Required file missing: ' . $file;
            }
        }
        
        return $results;
    }
    
    /**
     * Get specification as JSON
     */
    public static function getSpecJSON() {
        return json_encode([
            'version' => self::VERSION,
            'base_dir' => self::BASE_DIR,
            'main_directories' => self::getMainDirectories(),
            'categories' => self::getCategories(),
            'required_directories' => self::getRequiredDirectories(),
            'expected_counts' => self::getExpectedCounts(),
            'metadata_fields' => self::getMetadataFields(),
            'file_naming_patterns' => self::getFileNamingPatterns(),
            'differences_from_v42' => self::getDifferencesFromV42()
        ], JSON_PRETTY_PRINT);
    }
}

// Handle different request types
header('Content-Type: application/json');

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'validate':
            $base_path = $_GET['base_path'] ?? '';
            echo json_encode(SIMAv4_1_Spec::validate($base_path));
            break;
            
        case 'spec':
        default:
            echo SIMAv4_1_Spec::getSpecJSON();
            break;
    }
} else {
    // Default: return full spec
    echo SIMAv4_1_Spec::getSpecJSON();
}
