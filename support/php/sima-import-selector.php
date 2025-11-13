<?php
/**
 * sima-import-selector.php
 * 
 * SIMA Import Selection Tool
 * Upload import-instructions.md and modify installation selections
 * 
 * Version: 1.0.0
 * Date: 2025-11-12
 * SIMA Version: 4.2.2
 */

/**
 * Parse import-instructions.md file
 */
function parseImportInstructions($content) {
    $data = [
        'metadata' => [],
        'selected' => [],
        'unselected' => [],
        'packages' => [],
        'changelog' => []
    ];
    
    $lines = explode("\n", $content);
    $currentSection = null;
    $currentCategory = null;
    
    foreach ($lines as $line) {
        $line = trim($line);
        
        // Parse metadata
        if (preg_match('/\*\*Archive:\*\*\s*(.+)/', $line, $matches)) {
            $data['metadata']['archive'] = trim($matches[1]);
        } elseif (preg_match('/\*\*Created:\*\*\s*(.+)/', $line, $matches)) {
            $data['metadata']['created'] = trim($matches[1]);
        } elseif (preg_match('/\*\*Modified:\*\*\s*(.+)/', $line, $matches)) {
            $data['metadata']['modified'] = trim($matches[1]);
        } elseif (preg_match('/\*\*SIMA Version:\*\*\s*(.+)/', $line, $matches)) {
            $data['metadata']['sima_version'] = trim($matches[1]);
        } elseif (preg_match('/\*\*Total Files:\*\*\s*(\d+)/', $line, $matches)) {
            $data['metadata']['total_files'] = intval($matches[1]);
        } elseif (preg_match('/\*\*Selected:\*\*\s*(\d+)/', $line, $matches)) {
            $data['metadata']['selected_count'] = intval($matches[1]);
        }
        
        // Detect sections
        if (strpos($line, '### Selected for Install') !== false) {
            $currentSection = 'selected';
        } elseif (strpos($line, '### Not Selected for Install') !== false) {
            $currentSection = 'unselected';
        } elseif (strpos($line, '## Change Log') !== false) {
            $currentSection = 'changelog';
        } elseif (strpos($line, '## Packages') !== false) {
            $currentSection = 'packages';
        }
        
        // Parse category headers
        if (preg_match('/^####\s+(.+?)\s+\((\d+)\s+files?\)/', $line, $matches)) {
            $currentCategory = [
                'path' => trim($matches[1]),
                'count' => intval($matches[2]),
                'files' => []
            ];
        }
        
        // Parse file entries
        if (preg_match('/^-\s+\[(x| )\]\s+(.+?)\s+→\s+(.+?)(\s+\((.+?)\))?$/', $line, $matches)) {
            $isSelected = ($matches[1] === 'x');
            $filename = trim($matches[2]);
            $targetPath = trim($matches[3]);
            $status = isset($matches[5]) ? trim($matches[5]) : '';
            
            $fileData = [
                'filename' => $filename,
                'target_path' => $targetPath,
                'status' => $status,
                'selected' => $isSelected
            ];
            
            if ($currentCategory) {
                $currentCategory['files'][] = $fileData;
            }
        }
        
        // End of category - save it
        if ($currentCategory && $line === '') {
            if ($currentSection === 'selected') {
                $data['selected'][] = $currentCategory;
            } elseif ($currentSection === 'unselected') {
                $data['unselected'][] = $currentCategory;
            }
            $currentCategory = null;
        }
        
        // Parse packages
        if ($currentSection === 'packages' && preg_match('/^-\s+(.+?):\s+(.+?)\s+\((\d+)\s+files?\)/', $line, $matches)) {
            $data['packages'][] = [
                'type' => trim($matches[1]),
                'filename' => trim($matches[2]),
                'file_count' => intval($matches[3])
            ];
        }
        
        // Parse changelog
        if ($currentSection === 'changelog' && preg_match('/^-\s+(.+?):\s+(.+)/', $line, $matches)) {
            $data['changelog'][] = [
                'date' => trim($matches[1]),
                'description' => trim($matches[2])
            ];
        }
    }
    
    return $data;
}

/**
 * Generate updated import-instructions.md
 */
function generateUpdatedInstructions($originalData, $newSelections) {
    $md = "# Import Instructions - {$originalData['metadata']['archive']}\n\n";
    $md .= "**Archive:** {$originalData['metadata']['archive']}\n";
    $md .= "**Created:** {$originalData['metadata']['created']}\n";
    $md .= "**Modified:** " . date('Y-m-d') . "\n";
    
    // Calculate new totals
    $selectedCount = count($newSelections);
    $totalFiles = $originalData['metadata']['total_files'];
    $changeCount = abs($selectedCount - ($originalData['metadata']['selected_count'] ?? $totalFiles));
    
    $md .= "**SIMA Version:** {$originalData['metadata']['sima_version']}\n";
    $md .= "**Total Files:** {$totalFiles}\n";
    $md .= "**Selected:** {$selectedCount}";
    
    if ($changeCount > 0) {
        $changeDir = $selectedCount > ($originalData['metadata']['selected_count'] ?? $totalFiles) ? '+' : '-';
        $md .= " (Changed: {$changeDir}{$changeCount} since last version)";
    }
    $md .= "\n\n";
    
    $md .= "## Installation State\n\n";
    
    // Build selected and unselected lists
    $selectedFiles = [];
    $unselectedFiles = [];
    
    // Combine all categories
    $allCategories = array_merge(
        $originalData['selected'] ?? [],
        $originalData['unselected'] ?? []
    );
    
    foreach ($allCategories as $category) {
        foreach ($category['files'] as $file) {
            $filePath = $file['target_path'];
            $isSelected = in_array($filePath, $newSelections);
            
            if ($isSelected) {
                if (!isset($selectedFiles[$category['path']])) {
                    $selectedFiles[$category['path']] = [];
                }
                $selectedFiles[$category['path']][] = $file;
            } else {
                if (!isset($unselectedFiles[$category['path']])) {
                    $unselectedFiles[$category['path']] = [];
                }
                $unselectedFiles[$category['path']][] = $file;
            }
        }
    }
    
    // Write selected files
    $md .= "### Selected for Install ({$selectedCount} files)\n\n";
    foreach ($selectedFiles as $categoryPath => $files) {
        $md .= "#### {$categoryPath} (" . count($files) . " files)\n";
        foreach ($files as $file) {
            $md .= "- [x] {$file['filename']} → {$file['target_path']}";
            if (!empty($file['status'])) {
                $md .= " ({$file['status']})";
            }
            $md .= "\n";
        }
        $md .= "\n";
    }
    
    // Write unselected files
    $unselectedCount = $totalFiles - $selectedCount;
    $md .= "### Not Selected for Install ({$unselectedCount} files)\n\n";
    
    if ($unselectedCount > 0) {
        foreach ($unselectedFiles as $categoryPath => $files) {
            $md .= "#### {$categoryPath} (" . count($files) . " files)\n";
            foreach ($files as $file) {
                $md .= "- [ ] {$file['filename']} (SKIP - user excluded " . date('Y-m-d') . ")\n";
            }
            $md .= "\n";
        }
    } else {
        $md .= "(None - all files selected)\n\n";
    }
    
    // Change log
    $md .= "## Change Log\n";
    $md .= "- " . date('Y-m-d') . ": User modified selections ({$selectedCount} files selected)\n";
    
    if (!empty($originalData['changelog'])) {
        foreach ($originalData['changelog'] as $entry) {
            $md .= "- {$entry['date']}: {$entry['description']}\n";
        }
    }
    $md .= "\n";
    
    // Packages
    $md .= "## Packages\n";
    if (!empty($originalData['packages'])) {
        foreach ($originalData['packages'] as $package) {
            $md .= "- {$package['type']}: {$package['filename']} ({$package['file_count']} files)\n";
        }
    }
    $md .= "\n";
    
    // Import process
    $md .= "## Import Process\n";
    $md .= "1. Extract all package files\n";
    $md .= "2. Copy ONLY selected files to target locations\n";
    $md .= "3. Skip files marked with [ ] (not selected)\n";
    $md .= "4. Use SIMA Import Mode for index updates\n";
    $md .= "5. Verify checksums against manifest\n";
    
    return $md;
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $action = $_POST['action'] ?? '';
    
    if ($action === 'parse') {
        try {
            if (!isset($_FILES['import_instructions'])) {
                throw new Exception("No file uploaded");
            }
            
            $content = file_get_contents($_FILES['import_instructions']['tmp_name']);
            $data = parseImportInstructions($content);
            
            echo json_encode(['success' => true, 'data' => $data]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }
    
    if ($action === 'generate') {
        try {
            $originalData = json_decode($_POST['original_data'], true);
            $newSelections = json_decode($_POST['new_selections'], true);
            
            $updatedContent = generateUpdatedInstructions($originalData, $newSelections);
            
            // Save to temporary file
            $tmpFile = tempnam(sys_get_temp_dir(), 'import-instructions-');
            file_put_contents($tmpFile, $updatedContent);
            
            echo json_encode([
                'success' => true,
                'content' => $updatedContent,
                'download_url' => '?download=' . basename($tmpFile)
            ]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }
}

// Handle downloads
if (isset($_GET['download'])) {
    $filename = basename($_GET['download']);
    $filepath = sys_get_temp_dir() . '/' . $filename;
    
    if (file_exists($filepath)) {
        header('Content-Type: text/markdown');
        header('Content-Disposition: attachment; filename="import-instructions.md"');
        readfile($filepath);
        unlink($filepath);
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMA Import Selection Tool</title>
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
        
        .upload-area {
            border: 2px dashed #ddd;
            border-radius: 8px;
            padding: 40px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .upload-area:hover {
            border-color: #3498db;
            background: #f8f9fa;
        }
        
        .upload-area.dragover {
            border-color: #3498db;
            background: #e3f2fd;
        }
        
        input[type="file"] {
            display: none;
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
        
        .metadata-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .metadata-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
        }
        
        .metadata-item label {
            display: block;
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .metadata-item .value {
            font-size: 16px;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .file-list {
            max-height: 500px;
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 15px;
        }
        
        .file-group {
            margin-bottom: 20px;
        }
        
        .file-group h3 {
            font-size: 16px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .file-item {
            display: flex;
            align-items: center;
            padding: 8px;
            border-radius: 4px;
            transition: background 0.2s;
        }
        
        .file-item:hover {
            background: #f8f9fa;
        }
        
        .file-item input[type="checkbox"] {
            margin-right: 10px;
        }
        
        .file-item .filename {
            flex: 1;
        }
        
        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 500;
            margin-left: 8px;
        }
        
        .status-badge.current {
            background: #d4edda;
            color: #155724;
        }
        
        .status-badge.excluded {
            background: #f8d7da;
            color: #721c24;
        }
        
        .changes-summary {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 4px;
            padding: 15px;
            margin-top: 20px;
        }
        
        .changes-summary h3 {
            font-size: 16px;
            margin-bottom: 10px;
        }
        
        .changes-summary .stat {
            display: inline-block;
            margin-right: 20px;
        }
        
        .changes-summary .stat strong {
            color: #2c3e50;
        }
        
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>📥 SIMA Import Selection Tool</h1>
        </header>
        
        <div class="section" id="upload-section">
            <h2>Upload Import Instructions</h2>
            <div class="upload-area" id="upload-area">
                <p style="font-size: 48px; margin-bottom: 10px;">📄</p>
                <p style="font-size: 18px; margin-bottom: 5px;">Drop import-instructions.md here</p>
                <p style="color: #666;">or click to browse</p>
                <input type="file" id="file-input" accept=".md">
            </div>
            <div style="margin-top: 20px; text-align: center;">
                <button onclick="document.getElementById('file-input').click()">📂 Select File</button>
            </div>
        </div>
        
        <div class="section hidden" id="metadata-section">
            <h2>Archive Information</h2>
            <div class="metadata-grid">
                <div class="metadata-item">
                    <label>Archive</label>
                    <div class="value" id="meta-archive"></div>
                </div>
                <div class="metadata-item">
                    <label>Created</label>
                    <div class="value" id="meta-created"></div>
                </div>
                <div class="metadata-item">
                    <label>SIMA Version</label>
                    <div class="value" id="meta-version"></div>
                </div>
                <div class="metadata-item">
                    <label>Total Files</label>
                    <div class="value" id="meta-total"></div>
                </div>
            </div>
        </div>
        
        <div class="section hidden" id="selection-section">
            <h2>Modify Installation Selections</h2>
            <div id="file-list" class="file-list">
                <!-- Dynamically populated -->
            </div>
            
            <div class="changes-summary" id="changes-summary">
                <h3>Changes</h3>
                <div class="stat">Added: <strong id="added-count">0</strong></div>
                <div class="stat">Removed: <strong id="removed-count">0</strong></div>
                <div class="stat">New Total: <strong id="new-total">0</strong></div>
            </div>
        </div>
        
        <div class="section hidden" id="actions-section">
            <button onclick="saveUpdatedInstructions()">💾 Save Updated Instructions</button>
        </div>
    </div>
    
    <script>
        let originalData = null;
        let originalSelections = new Set();
        let currentSelections = new Set();
        
        // Setup drag and drop
        const uploadArea = document.getElementById('upload-area');
        const fileInput = document.getElementById('file-input');
        
        uploadArea.addEventListener('click', () => fileInput.click());
        
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });
        
        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('dragover');
        });
        
        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            
            if (e.dataTransfer.files.length > 0) {
                handleFile(e.dataTransfer.files[0]);
            }
        });
        
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                handleFile(e.target.files[0]);
            }
        });
        
        function handleFile(file) {
            const formData = new FormData();
            formData.append('action', 'parse');
            formData.append('import_instructions', file);
            
            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    originalData = data.data;
                    displayMetadata();
                    buildFileList();
                    
                    document.getElementById('metadata-section').classList.remove('hidden');
                    document.getElementById('selection-section').classList.remove('hidden');
                    document.getElementById('actions-section').classList.remove('hidden');
                } else {
                    alert('Error: ' + data.error);
                }
            });
        }
        
        function displayMetadata() {
            document.getElementById('meta-archive').textContent = originalData.metadata.archive || 'N/A';
            document.getElementById('meta-created').textContent = originalData.metadata.created || 'N/A';
            document.getElementById('meta-version').textContent = originalData.metadata.sima_version || 'N/A';
            document.getElementById('meta-total').textContent = originalData.metadata.total_files || 'N/A';
        }
        
        function buildFileList() {
            const container = document.getElementById('file-list');
            container.innerHTML = '';
            
            originalSelections.clear();
            currentSelections.clear();
            
            // Combine selected and unselected categories
            const allCategories = [
                ...(originalData.selected || []),
                ...(originalData.unselected || [])
            ];
            
            allCategories.forEach(category => {
                const groupDiv = document.createElement('div');
                groupDiv.className = 'file-group';
                
                const heading = document.createElement('h3');
                heading.textContent = `${category.path} (${category.files.length} files)`;
                groupDiv.appendChild(heading);
                
                category.files.forEach(file => {
                    const itemDiv = document.createElement('div');
                    itemDiv.className = 'file-item';
                    
                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.id = `file-${file.target_path}`;
                    checkbox.checked = file.selected;
                    checkbox.onchange = () => updateSelection(file.target_path, checkbox.checked);
                    
                    const label = document.createElement('label');
                    label.className = 'filename';
                    label.htmlFor = checkbox.id;
                    label.textContent = file.filename;
                    
                    if (file.selected) {
                        originalSelections.add(file.target_path);
                        currentSelections.add(file.target_path);
                        
                        const badge = document.createElement('span');
                        badge.className = 'status-badge current';
                        badge.textContent = '✓ Currently Selected';
                        label.appendChild(badge);
                    } else {
                        const badge = document.createElement('span');
                        badge.className = 'status-badge excluded';
                        badge.textContent = '✗ Excluded';
                        label.appendChild(badge);
                    }
                    
                    itemDiv.appendChild(checkbox);
                    itemDiv.appendChild(label);
                    groupDiv.appendChild(itemDiv);
                });
                
                container.appendChild(groupDiv);
            });
            
            updateChangesSummary();
        }
        
        function updateSelection(path, checked) {
            if (checked) {
                currentSelections.add(path);
            } else {
                currentSelections.delete(path);
            }
            updateChangesSummary();
        }
        
        function updateChangesSummary() {
            let added = 0;
            let removed = 0;
            
            currentSelections.forEach(path => {
                if (!originalSelections.has(path)) {
                    added++;
                }
            });
            
            originalSelections.forEach(path => {
                if (!currentSelections.has(path)) {
                    removed++;
                }
            });
            
            document.getElementById('added-count').textContent = added;
            document.getElementById('removed-count').textContent = removed;
            document.getElementById('new-total').textContent = currentSelections.size;
        }
        
        function saveUpdatedInstructions() {
            const formData = new FormData();
            formData.append('action', 'generate');
            formData.append('original_data', JSON.stringify(originalData));
            formData.append('new_selections', JSON.stringify(Array.from(currentSelections)));
            
            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    // Trigger download
                    window.location.href = data.download_url;
                } else {
                    alert('Error: ' + data.error);
                }
            });
        }
    </script>
</body>
</html>
