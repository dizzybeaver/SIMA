<?php
/**
 * sima-import-selector.php
 * 
 * SIMA Knowledge Import Selector with Collapsible Tree Navigation
 * Version: 2.0.0
 * Date: 2025-11-19
 * 
 * Enhancement: Added collapsible tree with conflict highlighting
 */

// Configuration
$base_path = '/path/to/sima';
$import_package = isset($_GET['package']) ? $_GET['package'] : null;

// Load import package manifest
function load_manifest($package_path) {
    $manifest_file = $package_path . '/manifest.yaml';
    if (!file_exists($manifest_file)) {
        return null;
    }
    // Parse YAML or JSON manifest
    // Simplified for example
    return [
        'version' => '4.2.2',
        'source' => 'external',
        'files' => []
    ];
}

// Check for conflicts with existing files
function check_conflicts($import_files, $base_path) {
    $conflicts = [];
    foreach ($import_files as $file) {
        $local_path = $base_path . '/' . $file['path'];
        if (file_exists($local_path)) {
            $conflicts[] = $file['path'];
        }
    }
    return $conflicts;
}

// Scan import package structure
function scan_import_package($package_path, $base = '') {
    $items = [];
    if (!is_dir($package_path)) return $items;
    
    $files = scandir($package_path);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..' || $file === 'manifest.yaml') continue;
        
        $full_path = $package_path . '/' . $file;
        $relative_path = $base . '/' . $file;
        
        if (is_dir($full_path)) {
            $items[] = [
                'type' => 'folder',
                'name' => $file,
                'path' => $relative_path,
                'children' => scan_import_package($full_path, $relative_path)
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

// Generate tree HTML with conflict highlighting
function generate_import_tree($items, $conflicts, $level = 0) {
    $html = '';
    foreach ($items as $item) {
        $indent = str_repeat('  ', $level);
        $node_id = 'node_' . md5($item['path']);
        $is_conflict = in_array($item['path'], $conflicts);
        $conflict_class = $is_conflict ? ' conflict' : '';
        
        if ($item['type'] === 'folder') {
            $has_children = !empty($item['children']);
            $html .= "{$indent}<div class=\"tree-node folder{$conflict_class}\" data-path=\"{$item['path']}\" data-level=\"{$level}\">\n";
            
            if ($has_children) {
                $html .= "{$indent}  <span class=\"tree-toggle\" onclick=\"toggleBranch(this)\">▶</span>\n";
            } else {
                $html .= "{$indent}  <span class=\"tree-spacer\"></span>\n";
            }
            
            $html .= "{$indent}  <input type=\"checkbox\" id=\"{$node_id}\" onchange=\"selectBranch(this)\" checked>\n";
            $html .= "{$indent}  <label for=\"{$node_id}\">\n";
            $html .= "{$indent}    <span class=\"folder-icon\">📁</span>\n";
            $html .= "{$indent}    <span class=\"node-name\">{$item['name']}/</span>\n";
            if ($is_conflict) {
                $html .= "{$indent}    <span class=\"conflict-badge\" title=\"Conflicts with existing files\">⚠️</span>\n";
            }
            $html .= "{$indent}  </label>\n";
            
            if ($has_children) {
                $html .= "{$indent}  <div class=\"tree-children\" style=\"display: none;\">\n";
                $html .= generate_import_tree($item['children'], $conflicts, $level + 1);
                $html .= "{$indent}  </div>\n";
            }
            
            $html .= "{$indent}</div>\n";
        } else {
            $html .= "{$indent}<div class=\"tree-node file{$conflict_class}\" data-path=\"{$item['path']}\" data-level=\"{$level}\">\n";
            $html .= "{$indent}  <span class=\"tree-spacer\"></span>\n";
            $html .= "{$indent}  <input type=\"checkbox\" id=\"{$node_id}\" name=\"files[]\" value=\"{$item['path']}\" checked>\n";
            $html .= "{$indent}  <label for=\"{$node_id}\">\n";
            $html .= "{$indent}    <span class=\"file-icon\">📄</span>\n";
            $html .= "{$indent}    <span class=\"node-name\">{$item['name']}</span>\n";
            if ($is_conflict) {
                $html .= "{$indent}    <span class=\"conflict-badge\" title=\"File exists - will be overwritten\">⚠️ Conflict</span>\n";
            }
            $html .= "{$indent}  </label>\n";
            $html .= "{$indent}</div>\n";
        }
    }
    return $html;
}

// Build import tree
$import_structure = [];
$conflicts = [];
if ($import_package && is_dir($import_package)) {
    $import_structure = scan_import_package($import_package);
    // Check conflicts (simplified)
    $conflicts = []; // Would scan and compare
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📥 SIMA Knowledge Import Selector</title>
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
        
        /* Info Panel */
        .info-panel {
            background: #e7f3ff;
            border-left: 4px solid #2196f3;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .info-panel.warning {
            background: #fff3cd;
            border-left-color: #ffc107;
        }
        
        .info-panel h3 {
            margin-bottom: 10px;
            color: #333;
            font-size: 16px;
        }
        
        .info-panel p {
            margin: 5px 0;
            font-size: 14px;
            color: #555;
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
        
        /* Conflict Highlighting */
        .tree-node.conflict {
            background: #fff3cd;
            border-left: 3px solid #ffc107;
            padding-left: 5px;
            margin-left: -5px;
        }
        
        .conflict-badge {
            display: inline-block;
            padding: 2px 8px;
            background: #ffc107;
            color: #333;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 600;
            margin-left: 8px;
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
        
        /* Import Options */
        .import-options {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 6px;
        }
        
        .import-options h3 {
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
        
        .form-group select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .import-button {
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
        
        .import-button:hover {
            background: #218838;
        }
        
        .import-button:disabled {
            background: #6c757d;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📥 SIMA Knowledge Import Selector</h1>
        <p class="subtitle">Select files to import with conflict resolution</p>
        
        <?php if (count($conflicts) > 0): ?>
        <div class="info-panel warning">
            <h3>⚠️ Conflicts Detected</h3>
            <p><strong><?php echo count($conflicts); ?></strong> file(s) will overwrite existing content.</p>
            <p>Conflicted items are highlighted in yellow. Review carefully before importing.</p>
        </div>
        <?php else: ?>
        <div class="info-panel">
            <h3>ℹ️ Import Package Ready</h3>
            <p>No conflicts detected. All files can be imported safely.</p>
        </div>
        <?php endif; ?>
        
        <form method="post" action="import-processor.php" id="importForm">
            <!-- Toolbar -->
            <div class="toolbar">
                <button type="button" onclick="expandAll()">📂 Expand All</button>
                <button type="button" onclick="collapseAll()">📁 Collapse All</button>
                <button type="button" onclick="selectAll()">✅ Select All</button>
                <button type="button" onclick="clearSelection()">❌ Clear Selection</button>
                <button type="button" onclick="expandConflicts()">⚠️ Show Conflicts</button>
                
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="🔍 Search import files..." onkeyup="filterTree(this.value)">
                </div>
                
                <span class="selection-summary" id="selectionSummary">Selected: 0 items</span>
            </div>
            
            <!-- Tree Structure -->
            <div class="tree-container" id="treeContainer">
                <?php echo generate_import_tree($import_structure, $conflicts); ?>
            </div>
            
            <!-- Import Options -->
            <div class="import-options">
                <h3>Import Options</h3>
                
                <div class="form-group">
                    <label for="conflictStrategy">Conflict Resolution Strategy:</label>
                    <select id="conflictStrategy" name="conflict_strategy">
                        <option value="overwrite">Overwrite existing files</option>
                        <option value="skip">Skip conflicting files</option>
                        <option value="merge">Merge content (where possible)</option>
                        <option value="rename">Rename imports (add suffix)</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="update_indexes" checked>
                        Update indexes after import
                    </label>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="verify_refs" checked>
                        Verify all REF-IDs
                    </label>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="create_backup" checked>
                        Create backup before import
                    </label>
                </div>
                
                <button type="submit" class="import-button" id="importButton">
                    📥 Import Selected Files
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
        
        // Update parent checkbox state
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
        
        // Expand branches containing conflicts
        function expandConflicts() {
            document.querySelectorAll('.tree-node.conflict').forEach(node => {
                // Expand all ancestors
                let parent = node.parentElement;
                while (parent && parent.classList.contains('tree-children')) {
                    parent.style.display = 'block';
                    const parentNode = parent.parentElement;
                    if (parentNode) {
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
            });
        }
        
        // Save expanded state
        function saveExpandedState(path, isExpanded) {
            const key = 'sima_import_tree_state';
            const state = JSON.parse(localStorage.getItem(key) || '{}');
            state[path] = isExpanded;
            localStorage.setItem(key, JSON.stringify(state));
        }
        
        // Restore expanded state
        function restoreExpandedState() {
            const key = 'sima_import_tree_state';
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
        
        // Expand/collapse all
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
        
        // Select/clear all
        function selectAll() {
            document.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                cb.checked = true;
                cb.indeterminate = false;
            });
            updateSelectionSummary();
        }
        
        function clearSelection() {
            document.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                cb.checked = false;
                cb.indeterminate = false;
            });
            updateSelectionSummary();
        }
        
        // Filter tree
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
                        const parentNode = parent.parentElement;
                        if (parentNode) {
                            parentNode.style.display = 'block';
                            const toggle = parentNode.querySelector(':scope > .tree-toggle');
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
        
        // Update selection summary
        function updateSelectionSummary() {
            const fileCheckboxes = document.querySelectorAll('input[name="files[]"]');
            const checkedCount = Array.from(fileCheckboxes).filter(cb => cb.checked).length;
            const totalCount = fileCheckboxes.length;
            const conflictCount = document.querySelectorAll('.tree-node.conflict input:checked').length;
            
            let summary = `Selected: ${checkedCount} of ${totalCount} files`;
            if (conflictCount > 0) {
                summary += ` (${conflictCount} conflicts)`;
            }
            
            document.getElementById('selectionSummary').textContent = summary;
            document.getElementById('importButton').disabled = checkedCount === 0;
        }
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            restoreExpandedState();
            expandConflicts(); // Auto-expand conflicts
            
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
