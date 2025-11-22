<?php
/**
 * spec_simav4.1.php - SIMA v4.1 Structure Specification
 * Version: 4.1.0 | Date: 2025-11-22 | Base: /simav4/
 */

// Prevent any output before JSON
ob_start();
error_reporting(0);

// Specification data
$spec = [
    'version' => '4.1.0',
    'base_dir' => '/simav4',
    
    'main_directories' => [
        'context',
        'docs',
        'entries',
        'integration',
        'projects',
        'support'
    ],
    
    'entry_categories' => [
        'core',
        'gateways',
        'interfaces',
        'languages',
        'anti-patterns',
        'decisions',
        'lessons',
        'platforms'
    ],
    
    'required_directories' => [
        '/simav4/context',
        '/simav4/docs',
        '/simav4/entries',
        '/simav4/entries/core',
        '/simav4/entries/gateways',
        '/simav4/entries/interfaces',
        '/simav4/entries/languages',
        '/simav4/entries/anti-patterns',
        '/simav4/entries/decisions',
        '/simav4/entries/lessons',
        '/simav4/integration',
        '/simav4/projects',
        '/simav4/support'
    ],
    
    'expected_counts' => [
        'context' => ['min' => 5, 'max' => 15],
        'docs' => ['min' => 15, 'max' => 25],
        'entries' => ['min' => 200, 'max' => 250],
        'integration' => ['min' => 3, 'max' => 10],
        'projects' => ['min' => 20, 'max' => 50],
        'support' => ['min' => 30, 'max' => 50],
        'total' => ['min' => 280, 'max' => 350]
    ],
    
    'index_patterns' => [
        'core' => 'Core-Architecture-Quick-Index.md',
        'gateways' => 'Gateway-Quick-Index.md',
        'interfaces' => 'Interface-Quick-Index.md',
        'languages' => 'Python-Language-Patterns-Quick-Index.md',
        'anti-patterns' => 'Anti-Patterns-Master-Index.md',
        'decisions' => 'Decisions-Master-Index.md',
        'lessons' => 'Lessons-Master-Index.md',
        'platforms' => 'Platforms-Master-Index.md'
    ],
    
    'file_naming' => [
        'lesson' => 'LESS-##.md',
        'decision' => 'DEC-##.md',
        'anti-pattern' => 'AP-##.md',
        'wisdom' => 'WISD-##.md',
        'bug' => 'BUG-##.md',
        'architecture' => 'ARCH-{name}.md',
        'interface' => 'INT-##.md',
        'gateway' => 'GATE-##.md'
    ],
    
    'metadata_fields' => [
        'version' => '**Version:**',
        'date' => '**Date:**',
        'purpose' => '**Purpose:**',
        'type' => '**Type:**',
        'category' => '**Category:**',
        'ref_id' => '**REF-ID:**'
    ],
    
    'differences_from_v42' => [
        'base_dir' => 'Uses /simav4/ not /sima/',
        'structure' => 'Uses /entries/ not domain separation',
        'domains' => 'No /generic/, /languages/, /platforms/, /projects/ domains',
        'integration' => 'Has /integration/ directory',
        'context' => 'Flat /context/ directory'
    ]
];

// Clear any previous output
ob_end_clean();

// Output JSON
header('Content-Type: application/json');
echo json_encode($spec, JSON_PRETTY_PRINT);
exit;
