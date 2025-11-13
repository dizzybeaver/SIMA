<?php
/**
 * sima-export-tool.php
 * 
 * SIMA Knowledge Export Tool
 * Selects knowledge from current SIMA and creates portable archive
 * 
 * Version: 1.0.0
 * Date: 2025-11-12
 * SIMA Version: 4.2.2
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
    
    // Get subdirectories (platforms/languages may have subdomains)
    $subdirs = glob($domainPath . '/*', GLOB_ONLYDIR);
    
    foreach ($subdirs as $subdir) {
        $subdirName = basename($subdir);
        
        // Check if this is a category or a subdomain
        $categories = ['lessons', 'decisions', 'anti-patterns', 'specifications', 
                      'core', 'wisdom', 'workflows', 'frameworks'];
        
        if (in_array($subdirName, $categories)) {
            // This is a category
            $categoryData = scanCategory($subdir, $subdirName);
            if ($categoryData['file_count'] > 0) {
                $domain['categories'][$subdirName] = $categoryData;
                $domain['total_files'] += $categoryData['file_count'];
            }
        } else {
            // This is a subdomain, recurse
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
    
    // Get all .md files (excluding indexes)
    $files = glob($categoryPath . '/*.md');
    
    foreach ($files as $file) {
        $filename = basename($file);
        
        // Skip index files
        if (strpos($filename, 'Index') !== false || 
            strpos($filename, 'index') !== false) {
            continue;
        }
        
        // Extract REF-ID and metadata
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
    
    // Extract title from first line
    if (isset($lines[0]) && strpos($lines[0], '#') === 0) {
        $metadata['title'] = trim(str_replace('#', '', $lines[0]));
    }
    
    // Look for REF-ID patterns (TYPE-##)
    if (preg_match('/([A-Z]+)-(\d+)/', basename($filePath), $matches)) {
        $metadata['ref_id'] = $matches[0];
    }
    
    // Extract purpose
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
    
    // Build domain and category lists
    $domains = [];
    $categories = [];
    
    foreach ($selectedFiles as $file) {
        // Extract domain from relative path
        $parts = explode('/', $file['relative_path']);
        $domain = $parts[0];
        
        if (!in_array($domain, $domains)) {
            $domains[] = $domain;
        }
        
        // Extract category
        $category = $file['category'] ?? 'unknown';
        if (!isset($categories[$category])) {
            $categories[$category] = 0;
        }
        $categories[$category]++;
        
        // Add file to manifest
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
    
    // Group files by category
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
    
    // Find the end of the header block
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
    
    // Insert version tags before the blank line
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
        
        // Add version tags
        $content = addVersionTags($content, $packageName);
        
        // Add to ZIP with relative path
        $zip->addFromString($file['relative_path'], $content);
    }
    
    $zip->close();
    
    return true;
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $action = $_POST['action'] ?? '';
    
    if ($action === 'scan') {
        // Scan knowledge tree
        $tree = scanKnowledgeTree(SIMA_ROOT);
        echo json_encode(['success' => true, 'tree' => $tree]);
        exit;
    }
    
    if ($action === 'export') {
        try {
            $archiveName = $_POST['archive_name'] ?? 'SIMA-Archive';
            $description = $_POST['description'] ?? '';
            $selectedPaths = json_decode($_POST['selected_files'] ?? '[]', true);
            
            // Build selected files array with metadata
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
            
            // Create export directory
            $exportId = uniqid();
            $exportPath = EXPORT_DIR . '/' . $exportId;
            mkdir($exportPath, 0755, true);
            
            // Generate manifest
            $manifestContent = generateManifest($archiveName, $description, $selectedFiles);
            file_put_contents($exportPath . '/manifest.yaml', $manifestContent);
            
            // Generate import instructions
            $instructionsContent = generateImportInstructions($archiveName, $selectedFiles);
            file_put_contents($exportPath . '/import-instructions.md', $instructionsContent);
            
            // Create knowledge base ZIP
            $zipPath = $exportPath . '/knowledge-base.zip';
            createArchiveZip($selectedFiles, $zipPath, 'knowledge-base.zip');
            
            // Create final archive bundle
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

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMA Knowledge Export Tool</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f5f5f5;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        header {
            background: #2c3e50;
            color: white;
            padding: 20px 30px;
        }
        
        header h1 {
            font-size: 24px;
            font-weight: 600;
        }
        
        .section {
            padding: 30px;
            border-bottom: 1px solid #eee;
        }
        
        .section:last-child {
            border-bottom: none;
        }
        
        h2 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #2c3e50;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
        }
        
        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            font-family: inherit;
        }
        
        textarea {
            resize: vertical;
            min-height: 80px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .tree {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 15px;
            max-height: 500px;
            overflow-y: auto;
        }
        
        .tree-item {
            margin: 5px 0;
            padding-left: 20px;
        }
        
        .tree-item.domain {
            padding-left: 0;
            font-weight: 600;
            margin-top: 15px;
        }
        
        .tree-item.domain:first-child {
            margin-top: 0;
        }
        
        .tree-item.category {
            padding-left: 20px;
            font-weight: 500;
            margin-top: 10px;
        }
        
        .tree-item.file {
            padding-left: 40px;
            font-size: 14px;
        }
        
        .tree-item input[type="checkbox"] {
            margin-right: 8px;
        }
        
        .badge {
            display: inline-block;
            padding: 2px 8px;
            background: #e3f2fd;
            color: #1976d2;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
            margin-left: 8px;
        }
        
        .summary-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 15px;
            margin-top: 20px;
        }
        
        .summary-box strong {
            color: #2c3e50;
        }
        
        button {
            background: #3498db;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }
        
        button:hover {
            background: #2980b9;
        }
        
        button:disabled {
            background: #95a5a6;
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
        
        .error-message {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
            display: none;
        }
        
        .error-message.active {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🎯 SIMA Knowledge Export Tool</h1>
        </header>
        
        <div class="section" id="metadata">
            <h2>Archive Information</h2>
            <div class="form-group">
                <label for="archive_name">Archive Name</label>
                <input type="text" id="archive_name" placeholder="AWS-Platform-Knowledge" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" placeholder="AWS Lambda, DynamoDB, API Gateway knowledge"></textarea>
            </div>
            <div class="form-group">
                <label>SIMA Version</label>
                <input type="text" value="<?php echo SIMA_VERSION; ?>" readonly>
            </div>
        </div>
        
        <div class="section" id="selection">
            <h2>Select Knowledge to Export</h2>
            <button onclick="loadKnowledgeTree()" id="scan-btn">🔍 Scan SIMA Knowledge Base</button>
            
            <div class="loading" id="loading">
                <div class="spinner"></div>
                <p>Scanning knowledge base...</p>
            </div>
            
            <div id="knowledge-tree" class="tree" style="display:none;">
                <!-- Dynamically populated -->
            </div>
            
            <div class="summary-box" id="summary" style="display:none;">
                Selected: <strong id="count">0</strong> files
            </div>
        </div>
        
        <div class="section" id="actions" style="display:none;">
            <button onclick="createExport()" id="export-btn">📦 Create Archive</button>
            
            <div class="success-message" id="success">
                <strong>✓ Export Complete!</strong>
                <p id="success-text"></p>
                <a href="#" id="download-link" style="color: #155724; font-weight: 600;">Download Archive</a>
            </div>
            
            <div class="error-message" id="error">
                <strong>✗ Export Failed</strong>
                <p id="error-text"></p>
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
                    document.getElementById('summary').style.display = 'block';
                    document.getElementById('actions').style.display = 'block';
                }
            });
        }
        
        function renderTree() {
            const container = document.getElementById('knowledge-tree');
            container.innerHTML = '';
            
            for (const [domainName, domain] of Object.entries(knowledgeTree)) {
                if (domain.total_files === 0) continue;
                
                const domainDiv = document.createElement('div');
                domainDiv.className = 'tree-item domain';
                
                const domainCheck = document.createElement('input');
                domainCheck.type = 'checkbox';
                domainCheck.id = `domain-${domainName}`;
                domainCheck.onchange = () => toggleDomain(domainName, domainCheck.checked);
                
                const domainLabel = document.createElement('label');
                domainLabel.htmlFor = domainCheck.id;
                domainLabel.textContent = `${domainName} `;
                
                const badge = document.createElement('span');
                badge.className = 'badge';
                badge.textContent = `${domain.total_files} files`;
                domainLabel.appendChild(badge);
                
                domainDiv.appendChild(domainCheck);
                domainDiv.appendChild(domainLabel);
                container.appendChild(domainDiv);
                
                renderDomainContent(domain, container, domainName);
            }
        }
        
        function renderDomainContent(domain, container, domainName) {
            // Render categories
            if (domain.categories) {
                for (const [catName, category] of Object.entries(domain.categories)) {
                    const catDiv = document.createElement('div');
                    catDiv.className = 'tree-item category';
                    
                    const catCheck = document.createElement('input');
                    catCheck.type = 'checkbox';
                    catCheck.id = `cat-${domainName}-${catName}`;
                    catCheck.onchange = () => toggleCategory(domainName, catName, catCheck.checked);
                    
                    const catLabel = document.createElement('label');
                    catLabel.htmlFor = catCheck.id;
                    catLabel.textContent = `${catName} `;
                    
                    const badge = document.createElement('span');
                    badge.className = 'badge';
                    badge.textContent = `${category.file_count} files`;
                    catLabel.appendChild(badge);
                    
                    catDiv.appendChild(catCheck);
                    catDiv.appendChild(catLabel);
                    container.appendChild(catDiv);
                    
                    // Render files
                    for (const file of category.files) {
                        const fileDiv = document.createElement('div');
                        fileDiv.className = 'tree-item file';
                        
                        const fileCheck = document.createElement('input');
                        fileCheck.type = 'checkbox';
                        fileCheck.id = `file-${file.relative_path}`;
                        fileCheck.dataset.path = file.relative_path;
                        fileCheck.onchange = () => toggleFile(file.relative_path, fileCheck.checked);
                        
                        const fileLabel = document.createElement('label');
                        fileLabel.htmlFor = fileCheck.id;
                        fileLabel.textContent = file.filename;
                        
                        fileDiv.appendChild(fileCheck);
                        fileDiv.appendChild(fileLabel);
                        container.appendChild(fileDiv);
                    }
                }
            }
            
            // Render subdomains recursively
            if (domain.subdomains) {
                for (const [subName, subdomain] of Object.entries(domain.subdomains)) {
                    renderDomainContent(subdomain, container, `${domainName}/${subName}`);
                }
            }
        }
        
        function toggleDomain(domainName, checked) {
            // Toggle all files in domain
            const domain = knowledgeTree[domainName];
            toggleDomainRecursive(domain, checked);
            updateCount();
        }
        
        function toggleDomainRecursive(domain, checked) {
            if (domain.categories) {
                for (const category of Object.values(domain.categories)) {
                    for (const file of category.files) {
                        const checkbox = document.getElementById(`file-${file.relative_path}`);
                        if (checkbox) {
                            checkbox.checked = checked;
                            if (checked) {
                                selectedFiles.add(file.relative_path);
                            } else {
                                selectedFiles.delete(file.relative_path);
                            }
                        }
                    }
                }
            }
            
            if (domain.subdomains) {
                for (const subdomain of Object.values(domain.subdomains)) {
                    toggleDomainRecursive(subdomain, checked);
                }
            }
        }
        
        function toggleCategory(domainName, catName, checked) {
            const domain = knowledgeTree[domainName];
            const category = domain.categories[catName];
            
            for (const file of category.files) {
                const checkbox = document.getElementById(`file-${file.relative_path}`);
                if (checkbox) {
                    checkbox.checked = checked;
                    if (checked) {
                        selectedFiles.add(file.relative_path);
                    } else {
                        selectedFiles.delete(file.relative_path);
                    }
                }
            }
            
            updateCount();
        }
        
        function toggleFile(path, checked) {
            if (checked) {
                selectedFiles.add(path);
            } else {
                selectedFiles.delete(path);
            }
            updateCount();
        }
        
        function updateCount() {
            document.getElementById('count').textContent = selectedFiles.size;
        }
        
        function createExport() {
            const archiveName = document.getElementById('archive_name').value.trim();
            const description = document.getElementById('description').value.trim();
            
            if (!archiveName) {
                alert('Please enter an archive name');
                return;
            }
            
            if (selectedFiles.size === 0) {
                alert('Please select at least one file');
                return;
            }
            
            document.getElementById('export-btn').disabled = true;
            document.getElementById('export-btn').textContent = 'Creating Archive...';
            
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
                document.getElementById('export-btn').disabled = false;
                document.getElementById('export-btn').textContent = '📦 Create Archive';
                
                if (data.success) {
                    document.getElementById('success-text').textContent = 
                        `Successfully exported ${data.file_count} files.`;
                    document.getElementById('download-link').href = data.download_url;
                    document.getElementById('success').classList.add('active');
                } else {
                    document.getElementById('error-text').textContent = data.error;
                    document.getElementById('error').classList.add('active');
                }
            });
        }
    </script>
</body>
</html>
