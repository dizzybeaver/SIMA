<?php
/**
 * sima-export-tool.php
 * 
 * SIMA Knowledge Export Tool with Collapsible Tree Navigation
 * Version: 2.0.0
 * Date: 2025-11-19
 * 
 * Enhancement: Added collapsible tree structure for better navigation
 */

// Configuration
$base_path = '/path/to/sima';  // Update with actual path
$domains = ['generic', 'platforms', 'languages', 'projects'];

// Scan directory structure
function scan_directory($path, $base = '') {
    $items = [];
    if (!is_dir($path)) return $items;
    
    $files = scandir($path);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        
        $full_path = $path . '/' . $file;
        $relative_path = $base . '/' . $file;
        
        if (is_dir($full_path)) {
            $items[] = [
                'type' => 'folder',
                'name' => $file,
                'path' => $relative_path,
                'children' => scan_directory($full_path, $relative_path)
            ];
        } else if (pathinfo($file, PATHINFO_EXTENSION) === 'md') {
            $items[] = [
                'type' => 'file',
                'name' => $file,
                'path' => $relative_path
            ];
        }
    }
    
    return $items;
}

// Generate tree HTML
function generate_tree($items, $level = 0) {
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
                $html .= generate_tree($item['children'], $level + 1);
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

// Build tree structure
$tree_structure = [];
foreach ($domains as $domain) {
    $domain_path = $base_path . '/' . $domain;
    if (is_dir($domain_path)) {
        $tree_structure[] = [
            'type' => 'folder',
            'name' => $domain,
            'path' => $domain,
            'children' => scan_directory($domain_path, $domain)
        ];
    }
}
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
        
        /* Toolbar */
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
        
        .toolbar button.primary {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }
        
        .toolbar button.primary:hover {
            background: #0056b3;
            border-color: #0056b3;
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
        
        /* Tree Structure */
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
        
        .tree-node input[type="checkbox"]:indeterminate {
            opacity: 0.5;
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
        
        /* Export Form */
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
        
        .form-group input[type="text"],
        .form-group select {
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
    </style>
</head>
<body>
    <div class="container">
        <h1>🎯 SIMA Knowledge Export Tool</h1>
        <p class="subtitle">Select files and folders to export with collapsible tree navigation</p>
        
        <form method="post" action="export-processor.php" id="exportForm">
            <!-- Toolbar -->
            <div class="toolbar">
                <button type="button" onclick="expandAll()">📂 Expand All</button>
                <button type="button" onclick="collapseAll()">📁 Collapse All</button>
                <button type="button" onclick="selectAll()">✅ Select All</button>
                <button type="button" onclick="clearSelection()">❌ Clear Selection</button>
                <button type="button" onclick="clearState()">🔄 Reset State</button>
                
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="🔍 Search files and folders..." onkeyup="filterTree(this.value)">
                </div>
                
                <span class="selection-summary" id="selectionSummary">Selected: 0 items</span>
            </div>
            
            <!-- Tree Structure -->
            <div class="tree-container" id="treeContainer">
                <?php echo generate_tree($tree_structure); ?>
            </div>
            
            <!-- Export Options -->
            <div class="export-options">
                <h3>Export Options</h3>
                
                <div class="form-group">
                    <label for="exportName">Export Package Name:</label>
                    <input type="text" id="exportName" name="export_name" placeholder="my-export-package" required>
                </div>
                
                <div class="form-group">
                    <label for="exportFormat">Export Format:</label>
                    <select id="exportFormat" name="export_format">
                        <option value="zip">ZIP Archive</option>
                        <option value="tar">TAR Archive</option>
                        <option value="folder">Folder Structure</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="include_manifest" checked>
                        Include manifest.yaml
                    </label>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="include_instructions" checked>
                        Include import-instructions.md
                    </label>
                </div>
                
                <button type="submit" class="export-button" id="exportButton">
                    📦 Export Selected Files
                </button>
            </div>
        </form>
    </div>
    
    <script>
        // Toggle branch expand/collapse
        function toggleBranch(element) {
            const node = element.parentElement;
            const children = node.querySelector('.tree-children');
            const toggle = element;
            const folderIcon = node.querySelector('.folder-icon');
            
            if (!children) return;
            
            if (children.style.display === 'none') {
                children.style.display = 'block';
                toggle.classList.add('expanded');
                toggle.textContent = '▼';
                if (folderIcon) folderIcon.textContent = '📂';
                saveExpandedState(node.dataset.path, true);
            } else {
                children.style.display = 'none';
                toggle.classList.remove('expanded');
                toggle.textContent = '▶';
                if (folderIcon) folderIcon.textContent = '📁';
                saveExpandedState(node.dataset.path, false);
            }
        }
        
        // Select/deselect entire branch
        function selectBranch(checkbox) {
            const node = checkbox.closest('.tree-node');
            const children = node.querySelectorAll('.tree-children input[type="checkbox"]');
            
            children.forEach(child => {
                child.checked = checkbox.checked;
            });
            
            updateParentCheckboxes(checkbox);
            updateSelectionSummary();
        }
        
        // Update parent checkbox state (indeterminate/checked/unchecked)
        function updateParentCheckboxes(checkbox) {
            const parentChildren = checkbox.closest('.tree-children');
            if (!parentChildren) return;
            
            const parentNode = parentChildren.parentElement;
            const parentCheckbox = parentNode.querySelector(':scope > input[type="checkbox"]');
            if (!parentCheckbox) return;
            
            const siblings = parentChildren.querySelectorAll(':scope > .tree-node > input[type="checkbox"]');
            const checkedCount = Array.from(siblings).filter(cb => cb.checked).length;
            const indeterminateCount = Array.from(siblings).filter(cb => cb.indeterminate).length;
            
            if (checkedCount === 0 && indeterminateCount === 0) {
                parentCheckbox.checked = false;
                parentCheckbox.indeterminate = false;
            } else if (checkedCount === siblings.length) {
                parentCheckbox.checked = true;
                parentCheckbox.indeterminate = false;
            } else {
                parentCheckbox.checked = false;
                parentCheckbox.indeterminate = true;
            }
            
            updateParentCheckboxes(parentCheckbox);
        }
        
        // Save expanded state to localStorage
        function saveExpandedState(path, isExpanded) {
            const key = 'sima_export_tree_state';
            const state = JSON.parse(localStorage.getItem(key) || '{}');
            state[path] = isExpanded;
            localStorage.setItem(key, JSON.stringify(state));
        }
        
        // Restore expanded state on page load
        function restoreExpandedState() {
            const key = 'sima_export_tree_state';
            const state = JSON.parse(localStorage.getItem(key) || '{}');
            
            Object.keys(state).forEach(path => {
                if (state[path]) {
                    const node = document.querySelector(`[data-path="${path}"]`);
                    if (node) {
                        const toggle = node.querySelector('.tree-toggle');
                        if (toggle && !toggle.classList.contains('expanded')) {
                            toggleBranch(toggle);
                        }
                    }
                }
            });
        }
        
        // Clear saved state
        function clearState() {
            if (confirm('Clear all saved expand/collapse state?')) {
                localStorage.removeItem('sima_export_tree_state');
                location.reload();
            }
        }
        
        // Expand all branches
        function expandAll() {
            document.querySelectorAll('.tree-toggle:not(.expanded)').forEach(toggle => {
                toggleBranch(toggle);
            });
        }
        
        // Collapse all branches
        function collapseAll() {
            document.querySelectorAll('.tree-toggle.expanded').forEach(toggle => {
                toggleBranch(toggle);
            });
        }
        
        // Select all checkboxes
        function selectAll() {
            document.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                cb.checked = true;
                cb.indeterminate = false;
            });
            updateSelectionSummary();
        }
        
        // Clear all selections
        function clearSelection() {
            document.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                cb.checked = false;
                cb.indeterminate = false;
            });
            updateSelectionSummary();
        }
        
        // Filter tree based on search term
        function filterTree(searchTerm) {
            const nodes = document.querySelectorAll('.tree-node');
            const term = searchTerm.toLowerCase().trim();
            
            if (term === '') {
                // Show all nodes
                nodes.forEach(node => {
                    node.style.display = 'block';
                });
                return;
            }
            
            // First pass: hide all
            nodes.forEach(node => {
                node.style.display = 'none';
            });
            
            // Second pass: show matches and their ancestors
            nodes.forEach(node => {
                const label = node.querySelector('label .node-name');
                if (!label) return;
                
                const matches = label.textContent.toLowerCase().includes(term);
                
                if (matches) {
                    // Show this node
                    node.style.display = 'block';
                    
                    // Show and expand all ancestors
                    let parent = node.parentElement;
                    while (parent && parent.classList.contains('tree-children')) {
                        parent.style.display = 'block';
                        const parentNode = parent.parentElement;
                        if (parentNode) {
                            parentNode.style.display = 'block';
                            const toggle = parentNode.querySelector(':scope > .tree-toggle');
                            if (toggle && !toggle.classList.contains('expanded')) {
                                toggle.classList.add('expanded');
                                toggle.textContent = '▼';
                                const folderIcon = parentNode.querySelector('.folder-icon');
                                if (folderIcon) folderIcon.textContent = '📂';
                            }
                        }
                        parent = parentNode ? parentNode.parentElement : null;
                    }
                }
            });
        }
        
        // Update selection summary
        function updateSelectionSummary() {
            const fileCheckboxes = document.querySelectorAll('input[name="files[]"]');
            const checkedCount = Array.from(fileCheckboxes).filter(cb => cb.checked).length;
            const totalCount = fileCheckboxes.length;
            
            document.getElementById('selectionSummary').textContent = 
                `Selected: ${checkedCount} of ${totalCount} files`;
            
            document.getElementById('exportButton').disabled = checkedCount === 0;
        }
        
        // Add change listeners to all checkboxes
        document.addEventListener('DOMContentLoaded', function() {
            restoreExpandedState();
            
            document.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                cb.addEventListener('change', function() {
                    if (this.closest('.tree-node.file')) {
                        updateParentCheckboxes(this);
                    }
                    updateSelectionSummary();
                });
            });
            
            updateSelectionSummary();
        });
    </script>
</body>
</html>
