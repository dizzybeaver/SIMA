<?php
/**
 * spec_simav4.2.php
 * 
 * SIMA v4.2 Structure Specification
 * Version: 1.0.0
 * Date: 2025-11-21
 * Location: /support/php/
 * 
 * Defines directory structure, index locations, and metadata format for SIMA v4.2
 */

class SIMAv4_2_Spec {
    const VERSION = '4.2.2';
    const VERSION_SHORT = 'v4.2';
    
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
            'specifications',
            'core',
            'wisdom',
            'workflows',
            'frameworks'
        ];
    }
    
    /**
     * Get index file naming pattern
     */
    public static function getIndexPattern($category) {
        $patterns = [
            'lessons' => 'Lessons-Master-Index.md',
            'decisions' => 'Decisions-Master-Index.md',
            'anti-patterns' => 'Anti-Patterns-Master-Index.md',
            'specifications' => 'Specifications-Index.md',
            'core' => 'Core-Master-Index.md',
            'wisdom' => 'Wisdom-Master-Index.md',
            'workflows' => 'Workflows-Index.md',
            'frameworks' => 'Frameworks-Index.md'
        ];
        
        return $patterns[$category] ?? ucfirst($category) . '-Index.md';
    }
    
    /**
     * Get master index locations
     */
    public static function getMasterIndexes() {
        return [
            'root' => 'Master-Index-of-Indexes.md',
            'domain' => '{domain}-Master-Index-of-Indexes.md',
            'router' => '{domain}-Router.md'
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
            'ref_id' => '**REF-ID:**',
            'project' => '**Project:**',
            'platform' => '**Platform:**',
            'language' => '**Language:**'
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
            'workflow' => 'Workflow-{number}-{name}.md',
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
            $domain . '/specifications'
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
     * Detect if directory is SIMA v4.2
     */
    public static function detectVersion($basePath) {
        // Check for v4.2 markers
        $markers = [
            '/Master-Index-of-Indexes.md',
            '/generic/generic-Master-Index-of-Indexes.md',
            '/support/workflows/Workflow-01-Add-Knowledge-Entry.md'
        ];
        
        $score = 0;
        foreach ($markers as $marker) {
            if (file_exists($basePath . $marker)) {
                $score++;
            }
        }
        
        // Check version in Master Index
        $masterIndex = $basePath . '/Master-Index-of-Indexes.md';
        if (file_exists($masterIndex)) {
            $content = file_get_contents($masterIndex);
            if (preg_match('/\*\*Version:\*\*\s*4\.2/', $content)) {
                $score += 2;
            }
        }
        
        return $score >= 3;
    }
    
    /**
     * Get conversion rules to v4.1
     */
    public static function getConversionToV41() {
        return [
            'directory_mapping' => [
                'specifications' => 'specs',
                'workflows' => 'workflow'
            ],
            'index_mapping' => [
                'Lessons-Master-Index.md' => 'lessons-index.md',
                'Decisions-Master-Index.md' => 'decisions-index.md'
            ],
            'metadata_transform' => [
                'remove_fields' => ['**Type:**'],
                'rename_fields' => []
            ]
        ];
    }
    
    /**
     * Get conversion rules from v4.1
     */
    public static function getConversionFromV41() {
        return [
            'directory_mapping' => [
                'specs' => 'specifications',
                'workflow' => 'workflows'
            ],
            'index_mapping' => [
                'lessons-index.md' => 'Lessons-Master-Index.md',
                'decisions-index.md' => 'Decisions-Master-Index.md'
            ],
            'metadata_transform' => [
                'add_fields' => ['**Type:** Shared Context'],
                'rename_fields' => []
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
            'workflows' => '/support/workflows',
            'utilities' => '/support/utilities',
            'templates' => '/templates',
            'php' => '/support/php'
        ];
    }
}
