<?php
/**
 * SIMAv4.1 Directory Specification
 * 
 * Version: 4.1.0
 * Date: 2025-11-22
 * Purpose: Define expected directory structure for SIMAv4.1 installations
 * Base Directory: /simav4/
 * 
 * Key Difference from v4.2:
 * - Uses /entries/ instead of domain separation (/generic/, /languages/, /platforms/, /projects/)
 * - Has /integration/ directory
 * - Different context file organization
 */

// CRITICAL: Base directory for v4.1 is /simav4/
define('SIMA_BASE', '/simav4');

// Core required directories for v4.1
$required_directories = [
    SIMA_BASE . '/context',
    SIMA_BASE . '/docs',
    SIMA_BASE . '/entries',
    SIMA_BASE . '/entries/core',
    SIMA_BASE . '/entries/gateways',
    SIMA_BASE . '/entries/interfaces',
    SIMA_BASE . '/entries/languages',
    SIMA_BASE . '/entries/anti-patterns',
    SIMA_BASE . '/entries/decisions',
    SIMA_BASE . '/entries/lessons',
    SIMA_BASE . '/integration',
    SIMA_BASE . '/projects',
    SIMA_BASE . '/support'
];

// Optional directories (may not exist in all installations)
$optional_directories = [
    SIMA_BASE . '/To_Be_Added',
    SIMA_BASE . '/entries/platforms',
    SIMA_BASE . '/docs/planning',
    SIMA_BASE . '/docs/deployment'
];

// Required files in /context/
$required_context_files = [
    'MODE-SELECTOR.md',
    'PROJECT-MODE-Context.md',
    'DEBUG-MODE-Context.md',
    'SESSION-START-Quick-Context.md',
    'SIMA-LEARNING-SESSION-START-Quick-Context.md'
];

// Required core architecture files
$required_core_files = [
    '/entries/core/ARCH-SUGA_ Single Universal Gateway Architecture.md',
    '/entries/core/ARCH-ZAPH_ Zero-Abstraction Path for Hot Operations.md',
    '/entries/core/ARCH-LMMS_ Lambda Memory Management System.md',
    '/entries/core/ARCH-DD_ Dispatch Dictionary Pattern.md'
];

// Required gateway pattern files
$required_gateway_files = [
    '/entries/gateways/GATE-01_Gateway-Layer-Structure.md',
    '/entries/gateways/GATE-02_Lazy-Import-Pattern.md',
    '/entries/gateways/GATE-03_Cross-Interface-Communication-Rule.md'
];

// Root required files
$required_root_files = [
    '/README.md',
    '/LICENSE'
];

/**
 * Validation function
 * 
 * @param string $base_path Actual filesystem base path to check
 * @return array Validation results
 */
function validate_simav4_1_structure($base_path) {
    global $required_directories, $optional_directories;
    global $required_context_files, $required_core_files;
    global $required_gateway_files, $required_root_files;
    
    $results = [
        'valid' => true,
        'errors' => [],
        'warnings' => [],
        'info' => []
    ];
    
    // Check base directory exists
    if (!is_dir($base_path . SIMA_BASE)) {
        $results['valid'] = false;
        $results['errors'][] = "Base directory not found: " . $base_path . SIMA_BASE;
        return $results;
    }
    
    // Check required directories
    foreach ($required_directories as $dir) {
        $full_path = $base_path . $dir;
        if (!is_dir($full_path)) {
            $results['valid'] = false;
            $results['errors'][] = "Required directory missing: $dir";
        }
    }
    
    // Check optional directories (warnings only)
    foreach ($optional_directories as $dir) {
        $full_path = $base_path . $dir;
        if (!is_dir($full_path)) {
            $results['warnings'][] = "Optional directory missing: $dir";
        }
    }
    
    // Check required context files
    foreach ($required_context_files as $file) {
        $full_path = $base_path . SIMA_BASE . '/context/' . $file;
        if (!file_exists($full_path)) {
            $results['valid'] = false;
            $results['errors'][] = "Required context file missing: /context/$file";
        }
    }
    
    // Check required core files
    foreach ($required_core_files as $file) {
        $full_path = $base_path . SIMA_BASE . $file;
        if (!file_exists($full_path)) {
            $results['valid'] = false;
            $results['errors'][] = "Required core file missing: $file";
        }
    }
    
    // Check required gateway files
    foreach ($required_gateway_files as $file) {
        $full_path = $base_path . SIMA_BASE . $file;
        if (!file_exists($full_path)) {
            $results['valid'] = false;
            $results['errors'][] = "Required gateway file missing: $file";
        }
    }
    
    // Check required root files
    foreach ($required_root_files as $file) {
        $full_path = $base_path . SIMA_BASE . $file;
        if (!file_exists($full_path)) {
            $results['valid'] = false;
            $results['errors'][] = "Required root file missing: $file";
        }
    }
    
    // Add info about structure
    $results['info'][] = "SIMAv4.1 uses /entries/ for knowledge organization";
    $results['info'][] = "Base directory: " . SIMA_BASE;
    $results['info'][] = "Key difference from v4.2: No domain separation (generic/languages/platforms)";
    
    return $results;
}

/**
 * Get expected file count ranges for v4.1
 * 
 * @return array Expected counts by category
 */
function get_simav4_1_file_counts() {
    return [
        'context' => ['min' => 5, 'max' => 15, 'note' => 'Mode files and custom instructions'],
        'docs' => ['min' => 15, 'max' => 25, 'note' => 'Documentation including planning and deployment'],
        'entries' => ['min' => 200, 'max' => 250, 'note' => 'All knowledge entries (core, patterns, decisions, etc.)'],
        'integration' => ['min' => 3, 'max' => 10, 'note' => 'E2E workflow examples'],
        'projects' => ['min' => 20, 'max' => 50, 'note' => 'Project-specific configurations and templates'],
        'support' => ['min' => 30, 'max' => 50, 'note' => 'Tools, checklists, workflows'],
        'total' => ['min' => 280, 'max' => 350, 'note' => 'Total files in installation']
    ];
}

/**
 * Display structure information
 */
function display_simav4_1_info() {
    $counts = get_simav4_1_file_counts();
    
    echo "<h2>SIMAv4.1 Directory Structure Specification</h2>\n";
    echo "<p><strong>Base Directory:</strong> /simav4/</p>\n";
    echo "<p><strong>Version:</strong> 4.1.0</p>\n";
    
    echo "<h3>Key Characteristics</h3>\n";
    echo "<ul>\n";
    echo "<li>Uses <code>/entries/</code> for all knowledge organization</li>\n";
    echo "<li>Has <code>/integration/</code> directory for E2E workflows</li>\n";
    echo "<li>No domain separation (unlike v4.2 which has /generic/, /languages/, /platforms/)</li>\n";
    echo "<li>Context files organized in flat /context/ directory</li>\n";
    echo "</ul>\n";
    
    echo "<h3>Expected File Counts</h3>\n";
    echo "<table border='1' cellpadding='5'>\n";
    echo "<tr><th>Category</th><th>Min</th><th>Max</th><th>Note</th></tr>\n";
    
    foreach ($counts as $category => $range) {
        echo "<tr>";
        echo "<td><strong>$category</strong></td>";
        echo "<td>{$range['min']}</td>";
        echo "<td>{$range['max']}</td>";
        echo "<td>{$range['note']}</td>";
        echo "</tr>\n";
    }
    
    echo "</table>\n";
    
    echo "<h3>Required Directories</h3>\n";
    global $required_directories;
    echo "<ul>\n";
    foreach ($required_directories as $dir) {
        echo "<li><code>$dir</code></li>\n";
    }
    echo "</ul>\n";
}

// If executed directly, display info
if (php_sapi_name() === 'cli' || !empty($_GET['info'])) {
    display_simav4_1_info();
}

// If validation requested
if (!empty($_GET['validate'])) {
    $base_path = $_GET['base_path'] ?? $_SERVER['DOCUMENT_ROOT'];
    $results = validate_simav4_1_structure($base_path);
    
    echo "<h2>Validation Results</h2>\n";
    echo "<p><strong>Valid:</strong> " . ($results['valid'] ? 'YES' : 'NO') . "</p>\n";
    
    if (!empty($results['errors'])) {
        echo "<h3>Errors</h3>\n";
        echo "<ul>\n";
        foreach ($results['errors'] as $error) {
            echo "<li style='color:red'>$error</li>\n";
        }
        echo "</ul>\n";
    }
    
    if (!empty($results['warnings'])) {
        echo "<h3>Warnings</h3>\n";
        echo "<ul>\n";
        foreach ($results['warnings'] as $warning) {
            echo "<li style='color:orange'>$warning</li>\n";
        }
        echo "</ul>\n";
    }
    
    if (!empty($results['info'])) {
        echo "<h3>Information</h3>\n";
        echo "<ul>\n";
        foreach ($results['info'] as $info) {
            echo "<li>$info</li>\n";
        }
        echo "</ul>\n";
    }
}
?>
