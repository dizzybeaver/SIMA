<?php
/**
 * sima-export-handler.php
 * 
 * Main request handler for SIMA Export Tool
 * Version: 4.4.0
 * Date: 2025-11-23
 */

class SIMAExportHandler {
    private $baseSearchDir;
    private $autoDetectedRoot;
    private $assetPaths;

    public function __construct($baseSearchDir = '/home/joe') {
        $this->baseSearchDir = $baseSearchDir;
        $this->autoDetectedRoot = $this->findSIMARoot();
        $this->assetPaths = $this->getAssetPaths();
    }

    /**
     * Auto-detect SIMA root within base search directory
     */
    private function findSIMARoot() {
        // Check common SIMA directory names
        $possibleDirs = [
            $this->baseSearchDir . '/sima',
            $this->baseSearchDir . '/simav4',
            $this->baseSearchDir . '/simav4.2',
            $this->baseSearchDir . '/simav4.1',
            $this->baseSearchDir . '/simav3',
            $this->baseSearchDir . '/web/claude.dizzybeaver.com/public_html/sima',
            $this->baseSearchDir . '/web/claude.dizzybeaver.com/public_html'
        ];

        foreach ($possibleDirs as $dir) {
            if (is_dir($dir)) {
                // Check for SIMA v4.2+ structure
                if (is_dir($dir . '/generic') && is_dir($dir . '/platforms')) {
                    return $dir;
                }
                // Check for SIMA v4.1 structure
                if (is_dir($dir . '/entries') && is_dir($dir . '/integration')) {
                    return $dir;
                }
                // Check for SIMA v3 structure
                if (is_dir($dir . '/NM00') && is_dir($dir . '/NM01')) {
                    return $dir;
                }
            }
        }

        return null;
    }

    /**
     * Get asset paths for CSS and JS
     */
    private function getAssetPaths() {
        $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
        
        if (strpos($scriptDir, '/support/php') !== false) {
            return [
                'css' => $scriptDir . '/css/',
                'js' => $scriptDir . '/js/'
            ];
        }
        
        return [
            'css' => '/css/',
            'js' => '/js/'
        ];
    }

    /**
     * Handle AJAX requests
     */
    public function handleRequest() {
        header('Content-Type: application/json');
        
        try {
            $action = $_POST['action'] ?? '';
            
            if ($action === 'scan') {
                $this->handleScanAction();
            } elseif ($action === 'export') {
                $this->handleExportAction();
            } else {
                $this->sendJsonResponse(false, [], "Unknown action: {$action}");
            }
            
        } catch (Exception $e) {
            $this->sendJsonResponse(false, [], $e->getMessage());
        }
    }

    /**
     * Handle scan action
     */
    private function handleScanAction() {
        $directory = $_POST['directory'] ?? '';
        
        if (empty($directory)) {
            throw new Exception("Please enter a SIMA directory path");
        }
        
        $directory = rtrim($directory, '/');
        if (!is_dir($directory)) {
            throw new Exception("Directory not found: {$directory}");
        }
        if (!is_readable($directory)) {
            throw new Exception("Directory not readable: {$directory}");
        }
        
        $validatedDir = realpath($directory);
        
        // Load required files
        $this->loadRequiredFiles($validatedDir);
        
        // Get version info and scan
        $versionInfo = SIMAVersionUtils::getVersionInfo($validatedDir);
        $tree = SIMAVersionUtils::scanWithVersion($validatedDir, $versionInfo['version']);
        $stats = SIMAVersionUtils::getStats($validatedDir, $versionInfo['version']);
        
        $this->sendJsonResponse(true, [
            'tree' => $tree,
            'base_path' => $validatedDir,
            'version_info' => $versionInfo,
            'stats' => $stats
        ]);
    }

    /**
     * Handle export action
     */
    private function handleExportAction() {
        $baseDir = $_POST['base_directory'] ?? '';
        
        if (empty($baseDir) || !is_dir($baseDir)) {
            throw new Exception("Invalid base directory");
        }
        
        $validatedBase = realpath($baseDir);
        
        // Load required files
        $this->loadRequiredFiles($validatedBase);
        
        $archiveName = $_POST['archive_name'] ?? 'SIMA-Export';
        $description = $_POST['description'] ?? '';
        $selectedPaths = json_decode($_POST['selected_files'] ?? '[]', true);
        
        $sourceVersion = $_POST['source_version'] ?? 'auto';
        $targetVersion = $_POST['target_version'] ?? 'auto';
        
        // Auto-detect versions if needed
        if ($sourceVersion === 'auto') {
            $versionInfo = SIMAVersionUtils::getVersionInfo($validatedBase);
            $sourceVersion = $versionInfo['version'];
        }
        
        if ($targetVersion === 'auto') {
            $targetVersion = $sourceVersion;
        }
        
        if (empty($selectedPaths)) {
            throw new Exception("No files selected for export");
        }
        
        // Create export
        $result = createExportArchive($validatedBase, $archiveName, $description, $selectedPaths, $sourceVersion, $targetVersion);
        
        $this->sendJsonResponse(true, [
            'archive_name' => $result['archive_name'],
            'file_count' => $result['file_count'],
            'converted_count' => $result['converted_count'],
            'download_url' => $result['download_url']
        ]);
    }

    /**
     * Load required PHP files
     */
    private function loadRequiredFiles($simaRoot = null) {
        $required = [
            'sima-common.php',
            'sima-scanner.php',
            'sima-tree-formatter.php',
            'sima-version-utils.php',
            'sima-export-helpers.php'
        ];
        
        $phpDir = __DIR__;
        $missing = [];
        
        foreach ($required as $file) {
            $filepath = $phpDir . '/' . $file;
            if (!file_exists($filepath)) {
                $missing[] = $file;
            } else {
                require_once $filepath;
            }
        }
        
        if (!empty($missing)) {
            throw new Exception("Missing required files: " . implode(', ', $missing));
        }
        
        return true;
    }

    /**
     * Send JSON response
     */
    private function sendJsonResponse($success, $data = [], $error = null) {
        $response = ['success' => $success];
        if ($success) {
            $response = array_merge($response, $data);
        } else {
            $response['error'] = $error;
        }
        echo json_encode($response);
        exit;
    }

    /**
     * Show HTML interface
     */
    public function showInterface() {
        ob_end_clean();
        
        $defaultPath = $this->autoDetectedRoot ?? '/home/joe/sima';
        $autoDetectMsg = $this->autoDetectedRoot 
            ? "✓ Auto-detected: {$this->autoDetectedRoot}" 
            : "⚠ Could not auto-detect SIMA location - please enter path manually";
        $autoDetectClass = $this->autoDetectedRoot ? 'success-msg' : 'warning-msg';
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMA Export Tool</title>
    <link rel="stylesheet" href="<?= $this->assetPaths['css'] ?>sima-export-tool.css">
</head>
<body>
    <div class="container">
        <h1>🔍 SIMA Knowledge Export Tool</h1>
        
        <div class="<?= $autoDetectClass ?>">
            <?= htmlspecialchars($autoDetectMsg) ?>
        </div>
        
        <div id="error" class="error">
            <strong>Error:</strong> <span id="error-text"></span>
        </div>
        
        <div id="loading">⏳ Processing...</div>
        
        <div class="section">
            <h2>1. SIMA Directory</h2>
            <div class="form-group">
                <label for="simaDirectory">SIMA Root Path:</label>
                <input type="text" id="simaDirectory" value="<?= htmlspecialchars($defaultPath) ?>" placeholder="/home/joe/sima">
                <small>Enter the full path to your SIMA installation</small>
            </div>
            <div class="form-group">
                <label for="sourceVersion">Source Version:</label>
                <select id="sourceVersion">
                    <option value="auto">Auto-Detect</option>
                    <option value="4.2">SIMA v4.2</option>
                    <option value="4.1">SIMA v4.1</option>
                    <option value="3.0">SIMA v3.0</option>
                </select>
            </div>
            <button id="scan-btn" onclick="scanDirectory()">🔍 Scan Directory</button>
            <div id="detectedVersion" style="display:none; margin-top:10px;" class="success-msg"></div>
        </div>
        
        <div class="section hidden" id="tree-section">
            <h2>2. Select Files to Export</h2>
            <div class="tree-controls">
                <button onclick="expandAll()">➕ Expand All</button>
                <button onclick="collapseAll()">➖ Collapse All</button>
                <button onclick="selectAll()">☑️ Select All</button>
                <button onclick="clearSelection()">⬜ Clear All</button>
            </div>
            <div class="tree-container" id="tree"></div>
            <div class="selection-summary" id="summary">Selection: 0 files selected</div>
        </div>
        
        <div class="section hidden" id="export-section">
            <h2>3. Export Settings</h2>
            <div class="form-group">
                <label for="archiveName">Archive Name:</label>
                <input type="text" id="archiveName" value="SIMA-Export" placeholder="MyExport">
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" rows="3" placeholder="Optional description..."></textarea>
            </div>
            <div class="form-group">
                <label for="targetVersion">Target Version:</label>
                <select id="targetVersion">
                    <option value="auto">Same as Source</option>
                    <option value="4.2">SIMA v4.2</option>
                    <option value="4.1">SIMA v4.1</option>
                </select>
                <small>Convert files to different version during export</small>
            </div>
            <button onclick="exportFiles()">📦 Create Export</button>
        </div>
        
        <div id="result-section" class="section hidden">
            <h2>4. Download Export</h2>
            <div id="result-content"></div>
        </div>
    </div>
    
    <!-- JavaScript Files -->
    <script src="<?= $this->assetPaths['js'] ?>sima-export-scan.js"></script>
    <script src="<?= $this->assetPaths['js'] ?>sima-export-render.js"></script>
    <script src="<?= $this->assetPaths['js'] ?>sima-export-selection.js"></script>
    <script src="<?= $this->assetPaths['js'] ?>sima-export-export.js"></script>
</body>
</html>
        <?php
    }
}
?>
