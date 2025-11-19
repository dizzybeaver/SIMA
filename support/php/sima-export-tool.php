<?php
/**
 * sima-export-tool.php
 * 
 * SIMA Knowledge Export Tool - Complete Version
 * Version: 2.0.0
 * Date: 2025-11-19
 * 
 * Combines: Original backend functionality + Collapsible tree UI
 */

// Configuration
define('SIMA_ROOT', '/home/joe/sima');
define('SIMA_VERSION', '4.2.2');
define('EXPORT_DIR', '/tmp/sima-exports');

// Ensure export directory exists
if (!is_dir(EXPORT_DIR)) {
    mkdir(EXPORT_DIR, 0755, true);
}

/**
 * Scan SIMA directory structure and build knowledge tree
 */
function scanKnowledgeTree($basePath) {
    $tree = [];
    $domains = ['generic', 'platforms', 'languages', 'projects'];
    
    foreach ($domains as $domain) {
        $domainPath = $basePath . '/' . $domain;
        if (!is_dir($domainPath)) continue;
        
        $tree[$domain] = scanDomain($domainPath, $domain);
    }
    
    return $tree;
}

/**
 * Scan a domain directory for categories and files
 */
function scanDomain($domainPath, $domainName) {
    $domain = [
        'name' => $domainName,
        'path' => $domainPath,
        'categories' => [],
        'total_files' => 0
    ];
    
    $subdirs = glob($domainPath . '/*', GLOB_ONLYDIR);
    
    foreach ($subdirs as $subdir) {
        $subdirName = basename($subdir);
        
        $categories = ['lessons', 'decisions', 'anti-patterns', 'specifications', 
                      'core', 'wisdom', 'workflows', 'frameworks'];
        
        if (in_array($subdirName, $categories)) {
            $categoryData = scanCategory($subdir, $subdirName);
            if ($categoryData['file_count'] > 0) {
                $domain['categories'][$subdirName] = $categoryData;
                $domain['total_files'] += $categoryData['file_count'];
            }
        } else {
            $subdomainData = scanDomain($subdir, $subdirName);
            if ($subdomainData['total_files'] > 0) {
                $domain['subdomains'][$subdirName] = $subdomainData;
                $domain['total_files'] += $subdomainData['total_files'];
            }
        }
    }
    
    return $domain;
}

/**
 * Scan a category directory for knowledge files
 */
function scanCategory($categoryPath, $categoryName) {
    $category = [
        'name' => $categoryName,
        'path' => $categoryPath,
        'files' => [],
        'file_count' => 0
    ];
    
    $files = glob($categoryPath . '/*.md');
    
    foreach ($files as $file) {
        $filename = basename($file);
        
        if (strpos($filename, 'Index') !== false || 
            strpos($filename, 'index') !== false) {
            continue;
        }
        
        $metadata = extractFileMetadata($file);
        
        $category['files'][] = [
            'filename' => $filename,
            'path' => $file,
            'relative_path' => str_replace(SIMA_ROOT . '/', '', $file),
            'ref_id' => $metadata['ref_id'],
            'title' => $metadata['title'],
            'size' => filesize($file),
            'checksum' => md5_file($file)
        ];
        
        $category['file_count']++;
    }
    
    return $category;
}

/**
 * Extract metadata from file
 */
function extractFileMetadata($filePath) {
    $content = file_get_contents($filePath);
    $lines = explode("\n", $content);
    
    $metadata = [
        'ref_id' => null,
        'title' => basename($filePath, '.md'),
        'purpose' => null
    ];
    
    if (isset($lines[0]) && strpos($lines[0], '#') === 0) {
        $metadata['title'] = trim(str_replace('#', '', $lines[0]));
    }
    
    if (preg_match('/([A-Z]+)-(\d+)/', basename($filePath), $matches)) {
        $metadata['ref_id'] = $matches[0];
    }
    
    foreach ($lines as $line) {
        if (strpos($line, '**Purpose:**') !== false) {
            $metadata['purpose'] = trim(str_replace('**Purpose:**', '', $line));
            break;
        }
    }
    
    return $metadata;
}

/**
 * Generate manifest.yaml
 */
function generateManifest($archiveName, $description, $selectedFiles) {
    $manifest = [
        'archive' => [
            'name' => $archiveName,
            'description' => $description,
            'created' => date('c'),
            'sima_version' => SIMA_VERSION,
            'type' => 'initial'
        ],
        'structure' => [
            'base_package' => 'knowledge-base.zip',
            'increments' => []
        ],
        'inventory' => [
            'total_files' => count($selectedFiles),
            'domains' => [],
            'categories' => []
        ],
        'files' => []
    ];
    
    $domains = [];
    $categories = [];
    
    foreach ($selectedFiles as $file) {
        $parts = explode('/', $file['relative_path']);
        $domain = $parts[0];
        
        if (!in_array($domain, $domains)) {
            $domains[] = $domain;
        }
        
        $category = $file['category'] ?? 'unknown';
        if (!isset($categories[$category])) {
            $categories[$category] = 0;
        }
        $categories[$category]++;
        
        $manifest['files'][] = [
            'path' => $file['relative_path'],
            'ref_id' => $file['ref_id'],
            'category' => $category,
            'checksum' => $file['checksum'],
            'size' => $file['size'],
            'sima_version' => SIMA_VERSION,
            'exported' => date('Y-m-d')
        ];
    }
    
    $manifest['inventory']['domains'] = $domains;
    $manifest['inventory']['categories'] = $categories;
    
    return yaml_emit($manifest);
}

/**
 * Generate import-instructions.md
 */
function generateImportInstructions($archiveName, $selectedFiles) {
    $md = "# Import Instructions - {$archiveName}\n\n";
    $md .= "**Archive:** {$archiveName}\n";
    $md .= "**Created:** " . date('Y-m-d') . "\n";
    $md .= "**SIMA Version:** " . SIMA_VERSION . "\n";
    $md .= "**Total Files:** " . count($selectedFiles) . "\n\n";
    
    $md .= "## Installation State\n\n";
    $md .= "### Selected for Install (" . count($selectedFiles) . " files)\n\n";
    
    $grouped = [];
    foreach ($selectedFiles as $file) {
        $category = dirname($file['relative_path']);
        if (!isset($grouped[$category])) {
            $grouped[$category] = [];
        }
        $grouped[$category][] = $file;
    }
    
    foreach ($grouped as $category => $files) {
        $md .= "#### {$category} (" . count($files) . " files)\n";
        foreach ($files as $file) {
            $targetPath = '/sima/' . $file['relative_path'];
            $md .= "- [x] {$file['filename']} → {$targetPath}\n";
        }
        $md .= "\n";
    }
    
    $md .= "### Not Selected for Install (0 files)\n";
    $md .= "(None - all files selected in initial export)\n\n";
    
    $md .= "## Packages\n";
    $md .= "- Base: knowledge-base.zip (" . count($selectedFiles) . " files)\n";
    $md .= "- Increments: (none)\n\n";
    
    $md .= "## Import Process\n";
    $md .= "1. Extract knowledge-base.zip\n";
    $md .= "2. Copy files to target locations (see above)\n";
    $md .= "3. Use SIMA Import Mode for index updates\n";
    $md .= "4. Verify checksums against manifest\n";
    
    return $md;
}

/**
 * Add SIMA version tags to file headers
 */
function addVersionTags($content, $packageName) {
    $lines = explode("\n", $content);
    
    $headerEnd = 0;
    $inHeader = false;
    
    for ($i = 0; $i < count($lines); $i++) {
        if (strpos($lines[$i], '**') === 0) {
            $inHeader = true;
        } elseif ($inHeader && trim($lines[$i]) === '') {
            $headerEnd = $i;
            break;
        }
    }
    
    $versionTags = [
        "**SIMA Version:** " . SIMA_VERSION,
        "**Exported:** " . date('Y-m-d'),
        "**Export Package:** {$packageName}"
    ];
    
    array_splice($lines, $headerEnd, 0, $versionTags);
    
    return implode("\n", $lines);
}

/**
 * Create ZIP archive with selected files
 */
function createArchiveZip($selectedFiles, $zipPath, $packageName) {
    $zip = new ZipArchive();
    
    if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
        throw new Exception("Cannot create ZIP file: {$zipPath}");
    }
    
    foreach ($selectedFiles as $file) {
        $content = file_get_contents($file['path']);
        $content = addVersionTags($content, $packageName);
        $zip->addFromString($file['relative_path'], $content);
    }
    
    $zip->close();
    
    return true;
}

/**
 * Generate tree HTML with collapsible structure
 */
function generateTree($items, $level = 0) {
    $html = '';
    foreach ($items as $item) {
        $indent = str_repeat('  ', $level);
        $node_id = 'node_' . md5($item['path']);
        
        if ($item['type'] === 'folder') {
            $has_children = !empty($item['children']);
            $html .= "{$indent}<div class=\"tree-node folder\" data-path=\"{$item['path']}\" data-level=\"{$level}\">\n";
            
            if ($has_children) {
                $html .= "{$indent}  <span class=\"tree-toggle\" onclick=\"toggleBranch(this)\">▶</span>\n";
            } else {
                $html .= "{$indent}  <span class=\"tree-spacer\"></span>\n";
            }
            
            $html .= "{$indent}  <input type=\"checkbox\" id=\"{$node_id}\" onchange=\"selectBranch(this)\">\n";
            $html .= "{$indent}  <label for=\"{$node_id}\">\n";
            $html .= "{$indent}    <span class=\"folder-icon\">📁</span>\n";
            $html .= "{$indent}    <span class=\"node-name\">{$item['name']}/</span>\n";
            $html .= "{$indent}  </label>\n";
            
            if ($has_children) {
                $html .= "{$indent}  <div class=\"tree-children\" style=\"display: none;\">\n";
                $html .= generateTree($item['children'], $level + 1);
                $html .= "{$indent}  </div>\n";
            }
            
            $html .= "{$indent}</div>\n";
        } else {
            $html .= "{$indent}<div class=\"tree-node file\" data-path=\"{$item['path']}\" data-level=\"{$level}\">\n";
            $html .= "{$indent}  <span class=\"tree-spacer\"></span>\n";
            $html .= "{$indent}  <input type=\"checkbox\" id=\"{$node_id}\" name=\"files[]\" value=\"{$item['path']}\">\n";
            $html .= "{$indent}  <label for=\"{$node_id}\">\n";
            $html .= "{$indent}    <span class=\"file-icon\">📄</span>\n";
            $html .= "{$indent}    <span class=\"node-name\">{$item['name']}</span>\n";
            $html .= "{$indent}  </label>\n";
            $html .= "{$indent}</div>\n";
        }
    }
    return $html;
}

/**
 * Convert tree to flat structure for tree generation
 */
function convertTreeForGeneration($tree) {
    $flat = [];
    foreach ($tree as $domainName => $domain) {
        $domainItem = [
            'type' => 'folder',
            'name' => $domainName,
            'path' => $domainName,
            'children' => []
        ];
        
        if (isset($domain['categories'])) {
            foreach ($domain['categories'] as $catName => $category) {
                $catItem = [
                    'type' => 'folder',
                    'name' => $catName,
                    'path' => $domainName . '/' . $catName,
                    'children' => []
                ];
                
                foreach ($category['files'] as $file) {
                    $catItem['children'][] = [
                        'type' => 'file',
                        'name' => $file['filename'],
                        'path' => $file['relative_path']
                    ];
                }
                
                $domainItem['children'][] = $catItem;
            }
        }
        
        if (isset($domain['subdomains'])) {
            foreach ($domain['subdomains'] as $subName => $subdomain) {
                $domainItem['children'][] = convertSubdomainForGeneration($subdomain, $domainName . '/' . $subName);
            }
        }
        
        $flat[] = $domainItem;
    }
    return $flat;
}

function convertSubdomainForGeneration($subdomain, $basePath) {
    $item = [
        'type' => 'folder',
        'name' => $subdomain['name'],
        'path' => $basePath,
        'children' => []
    ];
    
    if (isset($subdomain['categories'])) {
        foreach ($subdomain['categories'] as $catName => $category) {
            $catItem = [
                'type' => 'folder',
                'name' => $catName,
                'path' => $basePath . '/' . $catName,
                'children' => []
            ];
            
            foreach ($category['files'] as $file) {
                $catItem['children'][] = [
                    'type' => 'file',
                    'name' => $file['filename'],
                    'path' => $file['relative_path']
                ];
            }
            
            $item['children'][] = $catItem;
        }
    }
    
    return $item;
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $action = $_POST['action'] ?? '';
    
    if ($action === 'scan') {
        $tree = scanKnowledgeTree(SIMA_ROOT);
        echo json_encode(['success' => true, 'tree' => $tree]);
        exit;
    }
    
    if ($action === 'export') {
        try {
            $archiveName = $_POST['archive_name'] ?? 'SIMA-Archive';
            $description = $_POST['description'] ?? '';
            $selectedPaths = json_decode($_POST['selected_files'] ?? '[]', true);
            
            $selectedFiles = [];
            foreach ($selectedPaths as $path) {
                $fullPath = SIMA_ROOT . '/' . $path;
                if (file_exists($fullPath)) {
                    $metadata = extractFileMetadata($fullPath);
                    $selectedFiles[] = [
                        'path' => $fullPath,
                        'relative_path' => $path,
                        'filename' => basename($path),
                        'ref_id' => $metadata['ref_id'],
                        'category' => basename(dirname($path)),
                        'size' => filesize($fullPath),
                        'checksum' => md5_file($fullPath)
                    ];
                }
            }
            
            if (empty($selectedFiles)) {
                throw new Exception("No valid files selected");
            }
            
            $exportId = uniqid();
            $exportPath = EXPORT_DIR . '/' . $exportId;
            mkdir($exportPath, 0755, true);
            
            $manifestContent = generateManifest($archiveName, $description, $selectedFiles);
            file_put_contents($exportPath . '/manifest.yaml', $manifestContent);
            
            $instructionsContent = generateImportInstructions($archiveName, $selectedFiles);
            file_put_contents($exportPath . '/import-instructions.md', $instructionsContent);
            
            $zipPath = $exportPath . '/knowledge-base.zip';
            createArchiveZip($selectedFiles, $zipPath, 'knowledge-base.zip');
            
            $finalZipPath = $exportPath . '/SIMA-Archive-' . $archiveName . '-' . date('Y-m-d') . '.zip';
            $finalZip = new ZipArchive();
            $finalZip->open($finalZipPath, ZipArchive::CREATE);
            $finalZip->addFile($exportPath . '/manifest.yaml', 'manifest.yaml');
            $finalZip->addFile($exportPath . '/import-instructions.md', 'import-instructions.md');
            $finalZip->addFile($zipPath, 'knowledge-base.zip');
            $finalZip->close();
            
            echo json_encode([
                'success' => true,
                'download_url' => '/sima-exports/' . $exportId . '/' . basename($finalZipPath),
                'file_count' => count($selectedFiles)
            ]);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }
}

// Build tree structure for HTML
$tree_structure = [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🎯 SIMA Knowledge Export Tool</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 30px;
        }
        
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
        }
        
        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        
        .toolbar {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }
        
        .toolbar button {
            padding: 8px 16px;
            border: 1px solid #ddd;
            background: white;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s;
        }
        
        .toolbar button:hover {
            background: #e9ecef;
            border-color: #adb5bd;
        }
        
        .search-box {
            flex: 1;
            min-width: 250px;
        }
        
        .search-box input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .selection-summary {
            color: #666;
            font-size: 14px;
            font-weight: 500;
        }
        
        .tree-container {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 20px;
            max-height: 600px;
            overflow-y: auto;
            background: #fafafa;
        }
        
        .tree-node {
            line-height: 2;
            user-select: none;
        }
        
        .tree-node.folder {
            margin-bottom: 4px;
        }
        
        .tree-node.file {
            margin-left: 20px;
        }
        
        .tree-toggle {
            display: inline-block;
            width: 20px;
            text-align: center;
            cursor: pointer;
            transition: transform 0.2s;
            font-size: 12px;
        }
        
        .tree-toggle.expanded {
            transform: rotate(90deg);
        }
        
        .tree-spacer {
            display: inline-block;
            width: 20px;
        }
        
        .tree-children {
            margin-left: 20px;
            border-left: 1px dashed #ccc;
            padding-left: 10px;
        }
        
        .tree-node label {
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 2px 6px;
            border-radius: 4px;
            transition: background 0.2s;
        }
        
        .tree-node label:hover {
            background: #e9ecef;
        }
        
        .tree-node input[type="checkbox"] {
            cursor: pointer;
            width: 16px;
            height: 16px;
        }
        
        .folder-icon {
            font-size: 16px;
        }
        
        .file-icon {
            font-size: 14px;
        }
        
        .node-name {
            font-size: 14px;
            color: #333;
        }
        
        .tree-node.folder > label .node-name {
            font-weight: 500;
        }
        
        .export-options {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 6px;
        }
        
        .export-options h3 {
            margin-bottom: 15px;
            color: #333;
            font-size: 18px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #555;
        }
        
        .form-group input[type="text"] {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .export-button {
            margin-top: 20px;
            padding: 12px 30px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }
        
        .export-button:hover {
            background: #218838;
        }
        
        .export-button:disabled {
            background: #6c757d;
            cursor: not-allowed;
        }
        
        .loading {
            display: none;
            text-align: center;
            padding: 20px;
        }
        
        .loading.active {
            display: block;
        }
        
        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 10px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .success-message {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
            display: none;
        }
        
        .success-message.active {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎯 SIMA Knowledge Export Tool</h1>
        <p class="subtitle">Select files and folders to export with collapsible tree navigation</p>
        
        <div class="toolbar">
            <button type="button" onclick="loadKnowledgeTree()" id="scan-btn">🔍 Scan SIMA Knowledge Base</button>
            <button type="button" onclick="expandAll()">📂 Expand All</button>
            <button type="button" onclick="collapseAll()">📁 Collapse All</button>
            <button type="button" onclick="selectAll()">✅ Select All</button>
            <button type="button" onclick="clearSelection()">❌ Clear Selection</button>
            
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="🔎 Search files and folders..." onkeyup="filterTree(this.value)">
            </div>
            
            <span class="selection-summary" id="selectionSummary">Selected: 0 items</span>
        </div>
        
        <div class="loading" id="loading">
            <div class="spinner"></div>
            <p>Scanning knowledge base...</p>
        </div>
        
        <div id="knowledge-tree" class="tree-container" style="display:none;">
            <!-- Dynamically populated -->
        </div>
        
        <div class="export-options" id="export-options" style="display:none;">
            <h3>Export Options</h3>
            
            <div class="form-group">
                <label for="exportName">Export Package Name:</label>
                <input type="text" id="exportName" name="export_name" placeholder="my-export-package" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description:</label>
                <input type="text" id="description" name="description" placeholder="AWS Lambda, DynamoDB knowledge">
            </div>
            
            <button type="button" class="export-button" id="exportButton" onclick="createExport()">
                📦 Create Archive
            </button>
            
            <div class="success-message" id="success">
                <strong>✓ Export Complete!</strong>
                <p id="success-text"></p>
                <a href="#" id="download-link" style="color: #155724; font-weight: 600;">Download Archive</a>
            </div>
        </div>
    </div>
    
    <script>
        let knowledgeTree = null;
        let selectedFiles = new Set();
        
        function loadKnowledgeTree() {
            document.getElementById('loading').classList.add('active');
            document.getElementById('scan-btn').disabled = true;
            
            fetch('', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'action=scan'
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    knowledgeTree = data.tree;
                    renderTree();
                    document.getElementById('loading').classList.remove('active');
                    document.getElementById('knowledge-tree').style.display = 'block';
                    document.getElementById('export-options').style.display = 'block';
                }
            });
        }
        
        function renderTree() {
            const container = document.getElementById('knowledge-tree');
            container.innerHTML = '';
            
            for (const [domainName, domain] of Object.entries(knowledgeTree)) {
                if (domain.total_files === 0) continue;
                
                const domainDiv = createFolderNode(domainName, domain, container);
                renderDomainContent(domain, container, domainName);
            }
        }
        
        function createFolderNode(name, data, container) {
            const div = document.createElement('div');
            div.className = 'tree-node folder';
            div.dataset.path = name;
            
            const toggle = document.createElement('span');
            toggle.className = 'tree-toggle';
            toggle.textContent = '▶';
            toggle.onclick = () => toggleBranch(toggle);
            
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.id = `node-${name}`;
            checkbox.onchange = () => selectBranch(checkbox);
            
            const label = document.createElement('label');
            label.htmlFor = checkbox.id;
            label.innerHTML = `<span class="folder-icon">📁</span><span class="node-name">${name}/</span>`;
            
            div.appendChild(toggle);
            div.appendChild(checkbox);
            div.appendChild(label);
            container.appendChild(div);
            
            return div;
        }
        
        function renderDomainContent(domain, container, domainName) {
            if (domain.categories) {
                for (const [catName, category] of Object.entries(domain.categories)) {
                    const catDiv = document.createElement('div');
                    catDiv.className = 'tree-node folder';
                    catDiv.style.marginLeft = '20px';
                    
                    const toggle = document.createElement('span');
                    toggle.className = 'tree-toggle';
                    toggle.textContent = '▶';
                    toggle.onclick = () => toggleBranch(toggle);
                    
                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.id = `cat-${domainName}-${catName}`;
                    checkbox.onchange = () => selectBranch(checkbox);
                    
                    const label = document.createElement('label');
                    label.htmlFor = checkbox.id;
                    label.innerHTML = `<span class="folder-icon">📁</span><span class="node-name">${catName}/</span>`;
                    
                    catDiv.appendChild(toggle);
                    catDiv.appendChild(checkbox);
                    catDiv.appendChild(label);
                    
                    const childrenDiv = document.createElement('div');
                    childrenDiv.className = 'tree-children';
                    childrenDiv.style.display = 'none';
                    
                    for (const file of category.files) {
                        const fileDiv = document.createElement('div');
                        fileDiv.className = 'tree-node file';
                        
                        const spacer = document.createElement('span');
                        spacer.className = 'tree-spacer';
                        
                        const fileCheckbox = document.createElement('input');
                        fileCheckbox.type = 'checkbox';
                        fileCheckbox.id = `file-${file.relative_path}`;
                        fileCheckbox.dataset.path = file.relative_path;
                        fileCheckbox.onchange = () => toggleFile(file.relative_path, fileCheckbox.checked);
                        
                        const fileLabel = document.createElement('label');
                        fileLabel.htmlFor = fileCheckbox.id;
                        fileLabel.innerHTML = `<span class="file-icon">📄</span><span class="node-name">${file.filename}</span>`;
                        
                        fileDiv.appendChild(spacer);
                        fileDiv.appendChild(fileCheckbox);
                        fileDiv.appendChild(fileLabel);
                        childrenDiv.appendChild(fileDiv);
                    }
                    
                    catDiv.appendChild(childrenDiv);
                    container.appendChild(catDiv);
                }
            }
        }
        
        function toggleBranch(element) {
            const node = element.parentElement;
            const children = node.nextElementSibling;
            
            if (children && children.classList.contains('tree-children')) {
                if (children.style.display === 'none') {
                    children.style.display = 'block';
                    element.classList.add('expanded');
                    element.textContent = '▼';
                } else {
                    children.style.display = 'none';
                    element.classList.remove('expanded');
                    element.textContent = '▶';
                }
            }
        }
        
        function selectBranch(checkbox) {
            const node = checkbox.closest('.tree-node');
            const nextSibling = node.nextElementSibling;
            
            if (nextSibling && nextSibling.classList.contains('tree-children')) {
                const children = nextSibling.querySelectorAll('input[type="checkbox"]');
                children.forEach(child => {
                    child.checked = checkbox.checked;
                    if (child.dataset.path) {
                        toggleFile(child.dataset.path, checkbox.checked);
                    }
                });
            }
            
            updateSelectionSummary();
        }
        
        function toggleFile(path, checked) {
            if (checked) {
                selectedFiles.add(path);
            } else {
                selectedFiles.delete(path);
            }
            updateSelectionSummary();
        }
        
        function expandAll() {
            document.querySelectorAll('.tree-toggle:not(.expanded)').forEach(toggle => {
                toggleBranch(toggle);
            });
        }
        
        function collapseAll() {
            document.querySelectorAll('.tree-toggle.expanded').forEach(toggle => {
                toggleBranch(toggle);
            });
        }
        
        function selectAll() {
            document.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                cb.checked = true;
                if (cb.dataset.path) {
                    selectedFiles.add(cb.dataset.path);
                }
            });
            updateSelectionSummary();
        }
        
        function clearSelection() {
            document.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                cb.checked = false;
            });
            selectedFiles.clear();
            updateSelectionSummary();
        }
        
        function filterTree(searchTerm) {
            const nodes = document.querySelectorAll('.tree-node');
            const term = searchTerm.toLowerCase().trim();
            
            if (term === '') {
                nodes.forEach(node => node.style.display = 'block');
                return;
            }
            
            nodes.forEach(node => node.style.display = 'none');
            
            nodes.forEach(node => {
                const label = node.querySelector('label .node-name');
                if (!label) return;
                
                if (label.textContent.toLowerCase().includes(term)) {
                    node.style.display = 'block';
                    
                    let parent = node.parentElement;
                    while (parent && parent.classList.contains('tree-children')) {
                        parent.style.display = 'block';
                        const parentNode = parent.previousElementSibling;
                        if (parentNode) {
                            parentNode.style.display = 'block';
                            const toggle = parentNode.querySelector('.tree-toggle');
                            if (toggle && !toggle.classList.contains('expanded')) {
                                toggle.classList.add('expanded');
                                toggle.textContent = '▼';
                            }
                        }
                        parent = parentNode ? parentNode.parentElement : null;
                    }
                }
            });
        }
        
        function updateSelectionSummary() {
            document.getElementById('selectionSummary').textContent = 
                `Selected: ${selectedFiles.size} files`;
            document.getElementById('exportButton').disabled = selectedFiles.size === 0;
        }
        
        function createExport() {
            const archiveName = document.getElementById('exportName').value.trim();
            const description = document.getElementById('description').value.trim();
            
            if (!archiveName) {
                alert('Please enter an archive name');
                return;
            }
            
            if (selectedFiles.size === 0) {
                alert('Please select at least one file');
                return;
            }
            
            document.getElementById('exportButton').disabled = true;
            document.getElementById('exportButton').textContent = 'Creating Archive...';
            
            const formData = new FormData();
            formData.append('action', 'export');
            formData.append('archive_name', archiveName);
            formData.append('description', description);
            formData.append('selected_files', JSON.stringify(Array.from(selectedFiles)));
            
            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                document.getElementById('exportButton').disabled = false;
                document.getElementById('exportButton').textContent = '📦 Create Archive';
                
                if (data.success) {
                    document.getElementById('success-text').textContent = 
                        `Successfully exported ${data.file_count} files.`;
                    document.getElementById('download-link').href = data.download_url;
                    document.getElementById('success').classList.add('active');
                } else {
                    alert('Error: ' + data.error);
                }
            });
        }
    </script>
</body>
</html>
