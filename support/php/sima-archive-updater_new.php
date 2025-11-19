<?php
/**
 * sima-archive-updater.php
 * 
 * SIMA Archive Management with Collapsible Tree Navigation
 * Version: 2.0.0
 * Date: 2025-11-19
 * 
 * Enhancement: Added collapsible tree with archive/active state visualization
 */

// Configuration
$base_path = '/path/to/sima';
$archive_path = '/path/to/sima/archives';

// Scan directory and check archive status
function scan_with_archive_status($path, $archive_path, $base = '') {
    $items = [];
    if (!is_dir($path)) return $items;
    
    $files = scandir($path);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        
        $full_path = $path . '/' . $file;
        $relative_path = $base . '/' . $file;
        $archived_path = $archive_path . $relative_path;
        $is_archived = file_exists($archived_path);
        
        if (is_dir($full_path)) {
            $items[] = [
                'type' => 'folder',
                'name' => $file,
                'path' => $relative_path,
                'archived' => $is_archived,
                'children' => scan_with_archive_status($full_path, $archive_path, $relative_path)
            ];
        } else if (pathinfo($file, PATHINFO_EXTENSION) === 'md') {
            $items[] = [
                'type' => 'file',
                'name' => $file,
                'path' => $relative_path,
                'archived' => $is_archived,
                'size' => filesize($full_path),
                'modified' => filemtime($full_path)
            ];
        }
    }
    
    return $items;
}

// Generate tree HTML with archive status
function generate_archive_tree($items, $level = 0) {
    $html = '';
    foreach ($items as $item) {
        $indent = str_repeat('  ', $level);
        $node_id = 'node_' . md5($item['path']);
        $archived_class = $item['archived'] ? ' archived' : ' active';
        
        if ($item['type'] === 'folder') {
            $has_children = !empty($item['children']);
            $html .= "{$indent}<div class=\"tree-node folder{$archived_class}\" data-path=\"{$item['path']}\" data-level=\"{$level}\" data-archived=\"" . ($item['archived'] ? 'true' : 'false') . "\">\n";
            
            if ($has_children) {
                $html .= "{$indent}  <span class=\"tree-toggle\" onclick=\"toggleBranch(this)\">▶</span>\n";
            } else {
                $html .= "{$indent}  <span class=\"tree-spacer\"></span>\n";
            }
            
            $html .= "{$indent}  <input type=\"checkbox\" id=\"{$node_id}\" onchange=\"selectBranch(this)\">\n";
            $html .= "{$indent}  <label for=\"{$node_id}\">\n";
            $html .= "{$indent}    <span class=\"folder-icon\">📁</span>\n";
            $html .= "{$indent}    <span class=\"node-name\">{$item['name']}/</span>\n";
            if ($item['archived']) {
                $html .= "{$indent}    <span class=\"archive-badge\">📦 Archived</span>\n";
            }
            $html .= "{$indent}  </label>\n";
            
            if ($has_children) {
                $html .= "{$indent}  <div class=\"tree-children\" style=\"display: none;\">\n";
                $html .= generate_archive_tree($item['children'], $level + 1);
                $html .= "{$indent}  </div>\n";
            }
            
            $html .= "{$indent}</div>\n";
        } else {
            $size = round($item['size'] / 1024, 1) . ' KB';
            $modified = date('Y-m-d H:i', $item['modified']);
            
            $html .= "{$indent}<div class=\"tree-node file{$archived_class}\" data-path=\"{$item['path']}\" data-level=\"{$level}\" data-archived=\"" . ($item['archived'] ? 'true' : 'false') . "\">\n";
            $html .= "{$indent}  <span class=\"tree-spacer\"></span>\n";
            $html .= "{$indent}  <input type=\"checkbox\" id=\"{$node_id}\" name=\"files[]\" value=\"{$item['path']}\">\n";
            $html .= "{$indent}  <label for=\"{$node_id}\">\n";
            $html .= "{$indent}    <span class=\"file-icon\">📄</span>\n";
            $html .= "{$indent}    <span class=\"node-name\">{$item['name']}</span>\n";
            if ($item['archived']) {
                $html .= "{$indent}    <span class=\"archive-badge\">📦</span>\n";
            }
            $html .= "{$indent}    <span class=\"file-info\">{$size} • {$modified}</span>\n";
            $html .= "{$indent}  </label>\n";
            $html .= "{$indent}</div>\n";
        }
    }
    return $html;
}

// Build tree structure
$domains = ['generic', 'platforms', 'languages', 'projects'];
$tree_structure = [];
foreach ($domains as $domain) {
    $domain_path = $base_path . '/' . $domain;
    if (is_dir($domain_path)) {
        $tree_structure[] = [
            'type' => 'folder',
            'name' => $domain,
            'path' => $domain,
            'archived' => false,
            'children' => scan_with_archive_status($domain_path, $archive_path, $domain)
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📦 SIMA Archive Manager</title>
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
        
        /* Stats Panel */
        .stats-panel {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .stat-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            border-left: 4px solid #007bff;
        }
        
        .stat-card.archived {
            border-left-color: #6c757d;
        }
        
        .stat-card h3 {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .stat-card .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: #333;
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
        
        .filter-buttons {
            display: flex;
            gap: 5px;
        }
        
        .filter-buttons button {
            padding: 6px 12px;
            font-size: 13px;
        }
        
        .filter-buttons button.active {
            background: #007bff;
            color: white;
            border-color: #007bff;
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
        
        /* Archive Status Styling */
        .tree-node.archived {
            opacity: 0.6;
        }
        
        .tree-node.archived > label {
            font-style: italic;
        }
        
        .archive-badge {
            display: inline-block;
            padding: 2px 8px;
            background: #6c757d;
            color: white;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 600;
            margin-left: 8px;
        }
        
        .file-info {
            font-size: 11px;
            color: #999;
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
        
        /* Action Panel */
        .action-panel {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 6px;
        }
        
        .action-panel h3 {
            margin-bottom: 15px;
            color: #333;
            font-size: 18px;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .action-button {
            padding: 12px 30px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }
        
        .action-button.archive {
            background: #6c757d;
            color: white;
        }
        
        .action-button.archive:hover {
            background: #5a6268;
        }
        
        .action-button.restore {
            background: #28a745;
            color: white;
        }
        
        .action-button.restore:hover {
            background: #218838;
        }
        
        .action-button.delete {
            background: #dc3545;
            color: white;
        }
        
        .action-button.delete:hover {
            background: #c82333;
        }
        
        .action-button:disabled {
            background: #e9ecef;
            color: #6c757d;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📦 SIMA Archive Manager</h1>
        <p class="subtitle">Manage active and archived knowledge with collapsible tree view</p>
        
        <!-- Stats Panel -->
        <div class="stats-panel">
            <div class="stat-card">
                <h3>Total Files</h3>
                <div class="stat-value" id="totalFiles">0</div>
            </div>
            <div class="stat-card archived">
                <h3>Archived</h3>
                <div class="stat-value" id="archivedFiles">0</div>
            </div>
            <div class="stat-card">
                <h3>Active</h3>
                <div class="stat-value" id="activeFiles">0</div>
            </div>
            <div class="stat-card">
                <h3>Selected</h3>
                <div class="stat-value" id="selectedFiles">0</div>
            </div>
        </div>
        
        <form method="post" action="archive-processor.php" id="archiveForm">
            <!-- Toolbar -->
            <div class="toolbar">
                <button type="button" onclick="expandAll()">📂 Expand All</button>
                <button type="button" onclick="collapseAll()">📁 Collapse All</button>
                <button type="button" onclick="selectAll()">✅ Select All</button>
                <button type="button" onclick="clearSelection()">❌ Clear Selection</button>
                
                <div class="filter-buttons">
                    <button type="button" class="active" onclick="filterByStatus('all')">All</button>
                    <button type="button" onclick="filterByStatus('active')">Active</button>
                    <button type="button" onclick="filterByStatus('archived')">Archived</button>
                </div>
                
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="🔍 Search files..." onkeyup="filterTree(this.value)">
                </div>
                
                <span class="selection-summary" id="selectionSummary"></span>
            </div>
            
            <!-- Tree Structure -->
            <div class="tree-container" id="treeContainer">
                <?php echo generate_archive_tree($tree_structure); ?>
            </div>
            
            <!-- Action Panel -->
            <div class="action-panel">
                <h3>Archive Actions</h3>
                
                <div class="action-buttons">
                    <button type="button" class="action-button archive" onclick="archiveSelected()" id="archiveBtn">
                        📦 Archive Selected
                    </button>
                    <button type="button" class="action-button restore" onclick="restoreSelected()" id="restoreBtn">
                        ♻️ Restore Selected
                    </button>
                    <button type="button" class="action-button delete" onclick="deleteSelected()" id="deleteBtn">
                        🗑️ Delete Selected
                    </button>
                </div>
            </div>
        </form>
    </div>
    
    <script>
        let currentFilter = 'all';
        
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
            updateStats();
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
        
        // Filter by status
        function filterByStatus(status) {
            currentFilter = status;
            
            // Update button states
            document.querySelectorAll('.filter-buttons button').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
            
            // Show/hide nodes
            const nodes = document.querySelectorAll('.tree-node');
            nodes.forEach(node => {
                if (status === 'all') {
                    node.style.display = 'block';
                } else if (status === 'active') {
                    node.style.display = node.dataset.archived === 'false' ? 'block' : 'none';
                } else if (status === 'archived') {
                    node.style.display = node.dataset.archived === 'true' ? 'block' : 'none';
                }
            });
        }
        
        // Archive selected files
        function archiveSelected() {
            const selected = Array.from(document.querySelectorAll('input[name="files[]"]:checked'))
                .filter(cb => cb.closest('.tree-node').dataset.archived === 'false');
            
            if (selected.length === 0) {
                alert('No active files selected');
                return;
            }
            
            if (confirm(`Archive ${selected.length} file(s)?`)) {
                document.getElementById('archiveForm').action = 'archive-processor.php?action=archive';
                document.getElementById('archiveForm').submit();
            }
        }
        
        // Restore selected files
        function restoreSelected() {
            const selected = Array.from(document.querySelectorAll('input[name="files[]"]:checked'))
                .filter(cb => cb.closest('.tree-node').dataset.archived === 'true');
            
            if (selected.length === 0) {
                alert('No archived files selected');
                return;
            }
            
            if (confirm(`Restore ${selected.length} file(s)?`)) {
                document.getElementById('archiveForm').action = 'archive-processor.php?action=restore';
                document.getElementById('archiveForm').submit();
            }
        }
        
        // Delete selected files
        function deleteSelected() {
            const selected = document.querySelectorAll('input[name="files[]"]:checked');
            
            if (selected.length === 0) {
                alert('No files selected');
                return;
            }
            
            if (confirm(`Permanently delete ${selected.length} file(s)? This cannot be undone!`)) {
                document.getElementById('archiveForm').action = 'archive-processor.php?action=delete';
                document.getElementById('archiveForm').submit();
            }
        }
        
        // Save/restore state
        function saveExpandedState(path, isExpanded) {
            const key = 'sima_archive_tree_state';
            const state = JSON.parse(localStorage.getItem(key) || '{}');
            state[path] = isExpanded;
            localStorage.setItem(key, JSON.stringify(state));
        }
        
        function restoreExpandedState() {
            const key = 'sima_archive_tree_state';
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
            updateStats();
        }
        
        function clearSelection() {
            document.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                cb.checked = false;
                cb.indeterminate = false;
            });
            updateStats();
        }
        
        // Filter tree
        function filterTree(searchTerm) {
            const nodes = document.querySelectorAll('.tree-node');
            const term = searchTerm.toLowerCase().trim();
            
            if (term === '') {
                filterByStatus(currentFilter);
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
                                toggleBranch(toggle);
                            }
                        }
                        parent = parentNode ? parentNode.parentElement : null;
                    }
                }
            });
        }
        
        // Update statistics
        function updateStats() {
            const allFiles = document.querySelectorAll('.tree-node.file');
            const archivedFiles = document.querySelectorAll('.tree-node.file[data-archived="true"]');
            const activeFiles = document.querySelectorAll('.tree-node.file[data-archived="false"]');
            const selectedFiles = document.querySelectorAll('input[name="files[]"]:checked');
            const selectedActive = Array.from(selectedFiles).filter(cb => 
                cb.closest('.tree-node').dataset.archived === 'false'
            );
            const selectedArchived = Array.from(selectedFiles).filter(cb => 
                cb.closest('.tree-node').dataset.archived === 'true'
            );
            
            document.getElementById('totalFiles').textContent = allFiles.length;
            document.getElementById('archivedFiles').textContent = archivedFiles.length;
            document.getElementById('activeFiles').textContent = activeFiles.length;
            document.getElementById('selectedFiles').textContent = selectedFiles.length;
            
            // Update summary
            let summary = `Selected: ${selectedFiles.length}`;
            if (selectedActive.length > 0) summary += ` (${selectedActive.length} active`;
            if (selectedArchived.length > 0) {
                summary += selectedActive.length > 0 ? `, ${selectedArchived.length} archived)` : ` (${selectedArchived.length} archived)`;
            } else if (selectedActive.length > 0) {
                summary += ')';
            }
            document.getElementById('selectionSummary').textContent = summary;
            
            // Enable/disable buttons
            document.getElementById('archiveBtn').disabled = selectedActive.length === 0;
            document.getElementById('restoreBtn').disabled = selectedArchived.length === 0;
            document.getElementById('deleteBtn').disabled = selectedFiles.length === 0;
        }
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            restoreExpandedState();
            
            document.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                cb.addEventListener('change', function() {
                    if (this.closest('.tree-node.file')) {
                        updateParentCheckboxes(this);
                    }
                    updateStats();
                });
            });
            
            updateStats();
        });
    </script>
</body>
</html>
