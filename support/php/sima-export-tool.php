<?php
/**
 * sima-export-tool.php
 * 
 * SIMA Knowledge Export Tool - Fixed Modular Version
 * Version: 4.4.0
 * Date: 2025-11-23
 * 
 * FIXED: open_basedir issues, missing functions, file loading
 * MODULAR: All PHP logic moved to separate files
 * CONFIG: Base directory configuration for auto-search
 */

// Configuration - Set this to your base search directory
$BASE_SEARCH_DIR = '/home/joe';

// Start output buffering
ob_start();

// Error handling
ini_set('display_errors', '0');
error_reporting(E_ALL);
ini_set('log_errors', '1');

// Define export directory within allowed paths
if (!defined('EXPORT_DIR')) {
    define('EXPORT_DIR', __DIR__ . '/exports');
}

// Load the main handler
require_once __DIR__ . '/sima-export-handler.php';

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $handler = new SIMAExportHandler($BASE_SEARCH_DIR);
    $handler->handleRequest();
    exit;
}

// Show HTML interface
$handler = new SIMAExportHandler($BASE_SEARCH_DIR);
$handler->showInterface();
?>
