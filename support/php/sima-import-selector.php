<?php
/**
 * sima-import-selector.php
 * 
 * SIMA Import Selection Tool - Version-Aware
 * Version: 3.0.0
 * Date: 2025-11-21
 * 
 * PHP backend only - JavaScript in sima-import.js
 * 
 * MODIFIED: Added version compatibility system
 * - Directory selection for target location
 * - Source/target version detection
 * - Automatic conversion during import
 * - Shared tree renderer from sima-tree.js
 */

require_once __DIR__ . '/sima/support/php/sima-common.php';
require_once __DIR__ . '/sima/support/php/sima-version-utils.php';

/**
 * Validate and normalize directory path
 */
function validateDirectory($path) {
    $path = rtrim($path, '/');
    
    if ($path[0] !== '/') {
        throw new Exception("Path must be absolute");
    }
    
    if (strpos($path, '..') !== false) {
        throw new Exception("Invalid path: parent directory not allowed");
    }
    
    if (!is_dir($path) || !is_writable($path)) {
        throw new Exception("Directory not found or not writable");
    }
    
    return realpath($path);
}

/**
 * Parse import instructions with version detection
 */
function parseImportInstructions($content) {
    $lines
