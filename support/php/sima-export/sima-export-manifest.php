<?php
/**
 * sima-export-manifest.php
 * 
 * Manifest Generation Functions
 * Version: 1.0.0
 * Date: 2025-11-27
 */

/**
 * Generate manifest.yaml
 */
function generateManifest($archiveName, $description, $selectedFiles, $sourceVersion, $targetVersion) {
    $manifest = [
        'archive' => [
            'name' => $archiveName,
            'created' => date('Y-m-d H:i:s'),
            'description' => $description,
            'source_version' => $sourceVersion,
            'target_version' => $targetVersion,
            'total_files' => count($selectedFiles),
            'converted_files' => count(array_filter($selectedFiles, fn($f) => $f['converted']))
        ],
        'files' => []
    ];
    
    foreach ($selectedFiles as $file) {
        $manifest['files'][] = [
            'filename' => $file['filename'],
            'path' => $file['relative_path'],
            'original_path' => $file['original_path'],
            'ref_id' => $file['ref_id'],
            'category' => $file['category'],
            'size' => $file['size'],
            'checksum' => $file['checksum'],
            'converted' => $file['converted'],
            'sima_version' => $file['sima_version']
        ];
    }
    
    $manifest['packages'] = [
        [
            'name' => 'knowledge-base.zip',
            'type' => 'base',
            'files' => count($selectedFiles)
        ]
    ];
    
    return arrayToYaml($manifest);
}
?>
