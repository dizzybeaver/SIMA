<?php
/**
 * spec_simav4.1.php
 * 
 * SIMA v4.1 Structure Specification
 * Version: 1.0.0
 * Date: 2025-11-21
 * Location: /support/php/
 * 
 * Defines directory structure, index locations, and metadata format for SIMA v4.1
 */

class SIMAv4_1_Spec {
    const VERSION = '4.1.0';
    const VERSION_SHORT = 'v4.1';
    
    /**
     * Get domain directories
     */
    public static function getDomains() {
        return ['generic', 'platforms', 'languages', 'projects'];
    }
    
    /**
     * Get category directories for a domain
     */
    public static function getCategories() {
        return [
            'lessons',
            'decisions',
            'anti-patterns',
            'specs',  // v4.1 uses 'specs' not 'specifications'
            'core',
            'wisdom',
            'workflow',  // v4.1 uses 'workflow' not 'workflows'
            'frameworks'
        ];
    }
    
    /**
     * Get index file naming pattern
     */
    public static function getIndexPattern($category) {
        $patterns = [
            'lessons' => 'lessons-index.md',  // v4.1 lowercase
            'decisions' => 'decisions-index.md',
            'anti-patterns' => 'anti-patterns-index.md',
            'specs' => 'specs-index.md',
            'core' => 'core-index.md',
            'wisdom' => 'wisdom-index.md',
            'workflow' => 'workflow-index.md',
            'frameworks' => 'frameworks-index.md'
        ];
        
        return $patterns[$category] ?? $category . '-index.md';
    }
    
    /**
     * Get master index locations
     */
    public static function getMasterIndexes() {
        return [
            'root' => 'master-index.md',  // v4.1 lowercase
            'domain' => '{domain}-master-index.md',
            'router' => '{domain}-router.md'
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
            'category' => '**Category:**',
            'ref_id' => '**REF-ID:**',
            'project' => '**Project:**',
            'platform' => '**Platform:**',
            'language' => '**Language:**'
            // Note: v4.1 does not have **Type:** field
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
            'specification' => 'SPEC-{name}.md',
            'wisdom' => 'WISD-{number}.md',
            'workflow' => 'workflow-{number}-{name}.md',  // v4.1 lowercase
            'bug' => 'BUG-{number}.md',
            'architecture' => 'ARCH-{name}.md'
        ];
    }
    
    /**
     * Get required directories for a domain
     */
    public static function getRequiredDirectories($domain) {
        $base = [
            $domain,
            $domain . '/lessons',
            $domain . '/decisions',
            $domain . '/anti-patterns',
            $domain . '/specs'
        ];
        
        // Platform-specific additions
        if ($domain === 'platforms') {
            $base = array_merge($base, [
                $domain . '/aws',
                $domain . '/aws/lambda',
                $domain . '/aws/lambda/lessons',
                $domain . '/aws/lambda/decisions'
            ]);
        }
        
        // Language-specific additions
        if ($domain === 'languages') {
            $base = array_merge($base, [
                $domain . '/python',
                $domain . '/python/lessons',
                $domain . '/python/decisions'
            ]);
        }
        
        return $base;
    }
    
    /**
     * Get header template
     */
    public static function getHeaderTemplate($type) {
        $templates = [
            'lesson' => [
                '**Version:**',
                '**Date:**',
                '**Purpose:**',
                '**Category:**',
                '**REF-ID:**'
            ],
            'decision' => [
                '**Version:**',
                '**Date:**',
                '**Purpose:**',
                '**Category:**',
                '**REF-ID:**'
            ],
            'specification' => [
                '**Version:**',
                '**Date:**',
                '**Purpose:**',
                '**Category:**'
            ]
        ];
        
        return $templates[$type] ?? $templates['lesson'];
    }
    
    /**
     * Detect if directory is SIMA v4.1
     */
    public static function detectVersion($basePath) {
        // Check for v4.1 markers
        $markers = [
            '/master-index.md',  // lowercase in v4.1
            '/generic/generic-master-index.md',
            '/support/workflow'  // 'workflow' not 'workflows'
        ];
        
        $score = 0;
        foreach ($markers as $marker) {
            if (file_exists($basePath . $marker)) {
                $score++;
            }
        }
        
        // Check version in master index
        $masterIndex = $basePath . '/master-index.md';
        if (file_exists($masterIndex)) {
            $content = file_get_contents($masterIndex);
            if (preg_match('/\*\*Version:\*\*\s*4\.1/', $content)) {
                $score += 2;
            }
        }
        
        // Check for specs directory (not specifications)
        if (is_dir($basePath . '/generic/specs')) {
            $score++;
        }
        
        return $score >= 3;
    }
    
    /**
     * Get conversion rules to v4.2
     */
    public static function getConversionToV42() {
        return [
            'directory_mapping' => [
                'specs' => 'specifications',
                'workflow' => 'workflows'
            ],
            'index_mapping' => [
                'lessons-index.md' => 'Lessons-Master-Index.md',
                'decisions-index.md' => 'Decisions-Master-Index.md',
                'anti-patterns-index.md' => 'Anti-Patterns-Master-Index.md',
                'master-index.md' => 'Master-Index-of-Indexes.md'
            ],
            'metadata_transform' => [
                'add_fields' => ['**Type:** Shared Context'],
                'rename_fields' => []
            ],
            'filename_transform' => [
                'workflow-' => 'Workflow-'  // Capitalize
            ]
        ];
    }
    
    /**
     * Get conversion rules from v4.2
     */
    public static function getConversionFromV42() {
        return [
            'directory_mapping' => [
                'specifications' => 'specs',
                'workflows' => 'workflow'
            ],
            'index_mapping' => [
                'Lessons-Master-Index.md' => 'lessons-index.md',
                'Decisions-Master-Index.md' => 'decisions-index.md',
                'Anti-Patterns-Master-Index.md' => 'anti-patterns-index.md',
                'Master-Index-of-Indexes.md' => 'master-index.md'
            ],
            'metadata_transform' => [
                'remove_fields' => ['**Type:**'],
                'rename_fields' => []
            ],
            'filename_transform' => [
                'Workflow-' => 'workflow-'  // Lowercase
            ]
        ];
    }
    
    /**
     * Validate file structure
     */
    public static function validateFile($filePath, $type) {
        if (!file_exists($filePath)) {
            return ['valid' => false, 'error' => 'File does not exist'];
        }
        
        $content = file_get_contents($filePath);
        $lines = explode("\n", $content);
        
        // Check line limit
        if (count($lines) > 350) {
            return ['valid' => false, 'error' => 'Exceeds 350 line limit'];
        }
        
        // Check required headers
        $requiredHeaders = self::getHeaderTemplate($type);
        foreach ($requiredHeaders as $header) {
            if (strpos($content, $header) === false) {
                return ['valid' => false, 'error' => "Missing header: $header"];
            }
        }
        
        return ['valid' => true];
    }
    
    /**
     * Get support file locations
     */
    public static function getSupportFiles() {
        return [
            'checklists' => '/support/checklists',
            'quick_reference' => '/support/quick-reference',
            'tools' => '/support/tools',
            'workflow' => '/support/workflow',  // singular in v4.1
            'utilities' => '/support/utilities',
            'templates' => '/templates',
            'php' => '/support/php'
        ];
    }
}
