<?php
/**
 * spec_simav3.php
 * 
 * SIMA v3.0 Structure Specification
 * Version: 1.0.0
 * Date: 2025-11-22
 * Location: /support/php/
 * 
 * Defines the directory structure and metadata format for SIMA v3.0
 */

class SIMAv3_Spec {
    
    const VERSION = 'SIMA v3.0 (Neural Maps)';
    const VERSION_NUMBER = '3.0';
    
    /**
     * Get root domains for SIMA v3
     */
    public static function getDomains() {
        return [
            'NM00',      // Gateway/Master Indexes
            'NM01',      // Core Architecture
            'NM02',      // Dependencies
            'NM03',      // Operations
            'NM04',      // Decisions
            'NM05',      // Anti-Patterns
            'NM06',      // Lessons/Bugs/Wisdom
            'NM07',      // Decision Logic
            'AWS',       // AWS-specific patterns
            'Context',   // Context files
            'Docs',      // Documentation
            'Support',   // Support tools
            'Testing'    // Testing files
        ];
    }
    
    /**
     * Get categories for each domain
     */
    public static function getCategories() {
        return [
            // Neural Map categories
            'NM01' => [
                'Architecture-InterfacesCore',
                'Architecture-InterfacesAdvanced',
                'Architecture-CoreArchitecture'
            ],
            'NM02' => [
                'Dependencies-ImportRules',
                'Dependencies-Layers',
                'Dependencies-InterfaceDetail'
            ],
            'NM03' => [
                'Operations-Flows',
                'Operations-Pathways',
                'Operations-ErrorHandling',
                'Operations-Tracing'
            ],
            'NM04' => [
                'Decisions-Architecture',
                'Decisions-Technical',
                'Decisions-Operational'
            ],
            'NM05' => [
                'AntiPatterns-Import',
                'AntiPatterns-Implementation',
                'AntiPatterns-Concurrency',
                'AntiPatterns-Dependencies',
                'AntiPatterns-Critical',
                'AntiPatterns-Performance',
                'AntiPatterns-ErrorHandling',
                'AntiPatterns-Security',
                'AntiPatterns-Quality',
                'AntiPatterns-Testing',
                'AntiPatterns-Documentation',
                'AntiPatterns-Process'
            ],
            'NM06' => [
                'Lessons-CoreArchitecture',
                'Lessons-Documentation',
                'Lessons-Evolution',
                'Lessons-Learning',
                'Lessons-Operations',
                'Lessons-Optimization',
                'Lessons-Performance',
                'Lessons-Quality',
                'Bugs-Critical',
                'Wisdom-Synthesized'
            ],
            'NM07' => [
                'DecisionLogic-Import',
                'DecisionLogic-FeatureAddition',
                'DecisionLogic-ErrorHandling',
                'DecisionLogic-Optimization',
                'DecisionLogic-Testing',
                'DecisionLogic-Refactoring',
                'DecisionLogic-Deployment',
                'DecisionLogic-Architecture',
                'DecisionLogic-Meta'
            ],
            'AWS' => [
                'AWS00',  // Master indexes
                'AWS06'   // Serverless patterns (active)
            ]
        ];
    }
    
    /**
     * Get metadata field names for v3
     */
    public static function getMetadataFields() {
        return [
            'Category',
            'Topic',
            'Priority',
            'Status',
            'Created',
            'Last Updated',
            'Keywords',
            'Related Topics',
            'Version History'
        ];
    }
    
    /**
     * Get file naming pattern for v3
     */
    public static function getFilePattern() {
        return 'NM##-Category-SubCategory_REF-ID.md';
    }
    
    /**
     * Get REF-ID patterns for v3
     */
    public static function getRefIdPatterns() {
        return [
            // Architecture
            'INT-##'      => 'Interface specifications',
            'ARCH-##'     => 'Architecture patterns',
            'GATE-##'     => 'Gateway patterns',
            
            // Decisions
            'DEC-##'      => 'Design decisions',
            'DT-##'       => 'Decision trees',
            'FW-##'       => 'Frameworks/Workflows',
            'META-##'     => 'Meta decisions',
            
            // Lessons & Quality
            'LESS-##'     => 'Lessons learned',
            'WISD-##'     => 'Wisdom/Principles',
            'BUG-##'      => 'Bug reports',
            
            // Anti-Patterns
            'AP-##'       => 'Anti-patterns',
            
            // Dependencies
            'RULE-##'     => 'Import rules',
            'DEP-##'      => 'Dependency patterns',
            
            // Operations
            'PATH-##'     => 'Execution paths',
            'FLOW-##'     => 'Execution flows',
            'ERR-##'      => 'Error handling',
            
            // AWS-specific
            'AWS-LESS-##' => 'AWS lessons learned'
        ];
    }
    
    /**
     * Get support files that should exist
     */
    public static function getSupportFiles() {
        return [
            'Support/REF-ID Complete Directory.md',
            'Support/ANTI-PATTERNS CHECKLIST.md',
            'Support/File Server URLs.md',
            'Support/WORKFLOWS-PLAYBOOK-HUB.md'
        ];
    }
    
    /**
     * Get index file patterns
     */
    public static function getIndexFiles() {
        return [
            'NM00/NM00A-Master_Index.md',
            'NM00/NM00-Quick_Index.md',
            'AWS/AWS00/AWS00-Master_Index.md',
            'AWS/AWS00/AWS00-Quick_Index.md'
        ];
    }
    
    /**
     * Detect if directory is SIMA v3
     */
    public static function detectVersion($basePath) {
        // Check for characteristic v3 structure
        $markers = [
            '/NM00/NM00A-Master_Index.md',
            '/NM01',
            '/NM06',
            '/Support/REF-ID Complete Directory.md'
        ];
        
        foreach ($markers as $marker) {
            if (!file_exists($basePath . $marker)) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Get conversion rules to v4.2
     */
    public static function getConversionToV42() {
        return [
            // Directory mapping NM## -> domains
            'directory_mapping' => [
                'NM01' => 'generic/core',           // Architecture -> Core
                'NM04' => 'generic/decisions',      // Decisions
                'NM05' => 'generic/anti-patterns',  // Anti-patterns
                'NM06' => 'generic/lessons',        // Lessons
                'AWS' => 'platforms/aws',           // AWS patterns
            ],
            
            // Metadata field mapping
            'metadata_transform' => [
                'remove_fields' => [
                    '**Category:**',
                    '**Topic:**',
                    '**Priority:**',
                    '**Status:**',
                    '**Created:**',
                    '**Last Updated:**'
                ],
                'add_fields' => [
                    '**REF-ID:**',
                    '**Type:**',
                    '**Version:**',
                    '**Date:**',
                    '**Purpose:**'
                ]
            ],
            
            // Filename transformation
            'filename_transform' => [
                'NM01-Architecture-' => '',
                'NM04-Decisions-' => '',
                'NM05-AntiPatterns-' => '',
                'NM06-Lessons-' => '',
                '_INT-' => '-',
                '_LESS-' => '-',
                '_AP-' => '-',
                '_DEC-' => '-'
            ],
            
            // REF-ID mapping (stays mostly same)
            'ref_id_mapping' => [
                'INT-##' => 'INT-##',      // Keep same
                'LESS-##' => 'LESS-##',    // Keep same
                'AP-##' => 'AP-##',        // Keep same
                'DEC-##' => 'DEC-##',      // Keep same
                'ARCH-##' => 'ARCH-##',    // Keep same
                'AWS-LESS-##' => 'AWS-LESS-##'  // Keep same
            ]
        ];
    }
    
    /**
     * Get example file structure
     */
    public static function getExampleFile() {
        return [
            'path' => 'NM01/NM01-Architecture-InterfacesCore_INT-01.md',
            'metadata' => [
                'Category' => 'NM01 - Architecture',
                'Topic' => 'Interfaces-Core',
                'Priority' => '🔴 CRITICAL',
                'Status' => 'Active',
                'Created' => '2025-10-20',
                'Last Updated' => '2025-10-24'
            ],
            'ref_id' => 'INT-01',
            'title' => 'CACHE Interface'
        ];
    }
}
