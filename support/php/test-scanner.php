<?php
/**
 * test-scanner.php
 * 
 * Test Script for New SIMA Scanner
 * Version: 1.0.0
 * Date: 2025-11-22
 * 
 * Run from command line to verify scanner is working
 * Usage: php test-scanner.php /path/to/sima
 */

// Check command line argument
if ($argc < 2) {
    echo "Usage: php test-scanner.php /path/to/sima\n";
    exit(1);
}

$simaPath = $argv[1];

// Verify path exists
if (!is_dir($simaPath)) {
    echo "ERROR: Directory not found: {$simaPath}\n";
    exit(1);
}

echo "====================================\n";
echo "SIMA Scanner Test\n";
echo "====================================\n";
echo "Testing: {$simaPath}\n";
echo "Date: " . date('Y-m-d H:i:s') . "\n";
echo "====================================\n\n";

// Load scanner
require_once __DIR__ . '/sima-scanner.php';
require_once __DIR__ . '/sima-tree-formatter.php';

echo "1. Testing SIMAScanner::scanComplete()...\n";
$startTime = microtime(true);

try {
    $scanResult = SIMAScanner::scanComplete($simaPath, [
        'include_hidden' => false,
        'max_depth' => 20,
        'file_extensions' => ['.md'],
        'exclude_dirs' => ['.git', 'node_modules', '.idea'],
        'include_metadata' => true
    ]);
    
    $scanTime = round((microtime(true) - $startTime) * 1000, 2);
    
    echo "   ✓ Scan completed in {$scanTime}ms\n";
    echo "   ✓ Total files found: {$scanResult['total_files']}\n";
    echo "   ✓ Total directories: {$scanResult['total_directories']}\n\n";
    
} catch (Exception $e) {
    echo "   ✗ FAILED: {$e->getMessage()}\n";
    exit(1);
}

echo "2. Testing directory breakdown...\n";
foreach ($scanResult['directories'] as $dirName => $dirData) {
    $fileCount = SIMATreeFormatter::formatDirectory($dirName, $dirData)['total_files'] ?? 
                 count($dirData['files'] ?? []);
    echo "   - {$dirName}: {$fileCount} files\n";
}
echo "\n";

echo "3. Testing SIMATreeFormatter::formatForUI()...\n";
try {
    $uiTree = SIMATreeFormatter::formatForUI($scanResult);
    echo "   ✓ UI tree generated\n";
    echo "   ✓ Tree nodes: " . count($uiTree) . "\n\n";
} catch (Exception $e) {
    echo "   ✗ FAILED: {$e->getMessage()}\n";
    exit(1);
}

echo "4. Testing SIMATreeFormatter::getFlatFileList()...\n";
try {
    $flatList = SIMATreeFormatter::getFlatFileList($scanResult);
    echo "   ✓ Flat list generated\n";
    echo "   ✓ Files in list: " . count($flatList) . "\n\n";
} catch (Exception $e) {
    echo "   ✗ FAILED: {$e->getMessage()}\n";
    exit(1);
}

echo "5. Testing SIMATreeFormatter::generateStats()...\n";
try {
    $stats = SIMATreeFormatter::generateStats($scanResult);
    echo "   ✓ Statistics generated\n";
    echo "   ✓ Total files: {$stats['total_files']}\n";
    echo "   ✓ Total directories: {$stats['total_directories']}\n";
    echo "   ✓ Files with REF-ID: {$stats['with_ref_id']}\n";
    echo "   ✓ Files with version: {$stats['with_version']}\n\n";
    
    if (!empty($stats['by_domain'])) {
        echo "   Files by domain:\n";
        foreach ($stats['by_domain'] as $domain => $count) {
            echo "     - {$domain}: {$count} files\n";
        }
        echo "\n";
    }
} catch (Exception $e) {
    echo "   ✗ FAILED: {$e->getMessage()}\n";
    exit(1);
}

echo "6. Checking expected directories...\n";
$expectedDirs = [
    'generic' => 'Knowledge base - generic patterns',
    'platforms' => 'Platform-specific knowledge',
    'languages' => 'Language-specific knowledge', 
    'projects' => 'Project-specific knowledge',
    'context' => 'Mode contexts and configurations',
    'docs' => 'Documentation files',
    'support' => 'Support tools and templates',
    'templates' => 'File templates'
];

$allFound = true;
foreach ($expectedDirs as $dir => $description) {
    if (isset($scanResult['directories'][$dir])) {
        $fileCount = 0;
        if (isset($scanResult['directories'][$dir]['files'])) {
            $fileCount = count($scanResult['directories'][$dir]['files']);
        }
        echo "   ✓ {$dir}/: Found ({$fileCount} files) - {$description}\n";
    } else {
        echo "   ✗ {$dir}/: NOT FOUND - {$description}\n";
        $allFound = false;
    }
}
echo "\n";

echo "7. Sample files from each domain:\n";
foreach ($scanResult['directories'] as $dirName => $dirData) {
    if ($dirName === 'root') continue;
    
    $files = $dirData['files'] ?? [];
    if (!empty($files)) {
        $sample = array_slice($files, 0, 3);
        echo "   {$dirName}/\n";
        foreach ($sample as $file) {
            $version = $file['version'] ? " [v{$file['version']}]" : '';
            $refId = $file['ref_id'] ? " [{$file['ref_id']}]" : '';
            echo "     - {$file['filename']}{$version}{$refId}\n";
        }
        if (count($files) > 3) {
            echo "     ... and " . (count($files) - 3) . " more\n";
        }
        echo "\n";
    }
}

echo "====================================\n";
echo "TEST SUMMARY\n";
echo "====================================\n";

if ($scanResult['total_files'] < 100) {
    echo "⚠ WARNING: Only {$scanResult['total_files']} files found\n";
    echo "   Expected 400+ for typical SIMA installation\n";
    echo "   Possible issues:\n";
    echo "   - Wrong directory path\n";
    echo "   - Incomplete SIMA installation\n";
    echo "   - Permission issues\n\n";
}

if (!$allFound) {
    echo "⚠ WARNING: Some expected directories not found\n";
    echo "   This may be normal for partial installations\n\n";
}

if ($scanResult['total_files'] >= 100 && $allFound) {
    echo "✓ ALL TESTS PASSED\n";
    echo "✓ Scanner is working correctly\n";
    echo "✓ Found {$scanResult['total_files']} files in {$scanResult['total_directories']} directories\n";
    echo "✓ Ready to use with export tool\n\n";
    
    echo "Next steps:\n";
    echo "1. Upload updated files to web server\n";
    echo "2. Test export tool in browser\n";
    echo "3. Verify all directories appear in UI\n";
} else {
    echo "⚠ TESTS COMPLETED WITH WARNINGS\n";
    echo "  Check warnings above\n\n";
}

echo "====================================\n";
echo "Test completed: " . date('Y-m-d H:i:s') . "\n";
echo "====================================\n";
