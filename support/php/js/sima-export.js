/**
 * sima-export.js
 * 
 * JavaScript for SIMA Export Tool
 * Version: 4.3.0
 * Date: 2025-11-23
 * 
 * Purpose: Client-side logic for SIMA knowledge export
 * Location: /support/php/js/
 * 
 * MODIFIED:
 * - Extracted from sima-export-tool.php
 * - Universal/reusable functions
 * - ≤350 lines
 */

// Global state
let knowledgeTree = {};
let selectedFiles = new Set();
let currentBasePath = '';

/**
 * Scan directory for SIMA knowledge
 */
function scanDirectory() {
    const directory = document.getElementById('simaDirectory').value.trim();
    if (!directory) {
        alert('Please enter a SIMA directory path');
        return;
    }
    
    document.getElementById('loading').style.display = 'block';
    document.getElementById('error').classList.remove('active');
    document.getElementById('scan-btn').disabled = true;
    
    const formData = new FormData();
    formData.append('action', 'scan');
    formData.append('directory', directory);
    
    fetch('', { method: 'POST', body: formData })
    .then(r => r.json())
    .then(data => {
        document.getElementById('loading').style.display = 'none';
        document.getElementById('scan-btn').disabled = false;
        
        if (data.success) {
            knowledgeTree = data.tree;
            currentBasePath = data.base_path;
            
            // Display version info
            if (data.version_info && data.version_info.version !== 'unknown') {
                const stats = data.stats;
                document.getElementById('detectedVersion').textContent = 
                    `✓ Detected: SIMA v${data.version_info.version} - ${stats.total_files} files found`;
                document.getElementById('detectedVersion').style.display = 'block';
                document.getElementById('sourceVersion').value = data.version_info.version;
            }
            
            renderTree();
            document.getElementById('tree-section').classList.remove('hidden');
            document.getElementById('export-section').classList.remove('hidden');
            updateSummary();
        } else {
            document.getElementById('error-text').textContent = data.error;
            document.getElementById('error').classList.add('active');
        }
    })
    .catch(err => {
        document.getElementById('loading').style.display = 'none';
        document.getElementById('scan-btn').disabled = false;
        document.getElementById('error-text').textContent = 'Error: ' + err.message;
        document.getElementById('error').classList.add('active');
    });
}

/**
 * Render knowledge tree
 */
function renderTree() {
    const container = document.getElementById('tree');
    container.innerHTML = '';
    
    if (!knowledgeTree || knowledgeTree.length === 0) {
        container.innerHTML = '<p>No files found</p>';
        return;
    }
    
    knowledgeTree.forEach(node => {
        container.appendChild(renderNode(node, 0));
    });
}

/**
 * Render individual tree node
 */
function renderNode(node, depth) {
    const div = document.createElement('div');
    div.style.marginLeft = (depth * 20) + 'px';
    div.style.padding = '5px';
    
    if (node.type === 'directory') {
        const label = document.createElement('div');
        label.style.cursor = 'pointer';
        label.style.fontWeight = 'bold';
        label.style.padding = '5px';
        label.style.background = '#f0f0f0';
        label.style.marginBottom = '5px';
        label.style.borderRadius = '3px';
        
        const toggle = document.createElement('span');
        toggle.textContent = '▼ ';
        label.appendChild(toggle);
        
        const name = document.createElement('span');
        name.textContent = `📁 ${node.name} (${node.total_files} files)`;
        label.appendChild(name);
        div.appendChild(label);
        
        const childrenDiv = document.createElement('div');
        if (node.children) {
            node.children.forEach(child => {
                childrenDiv.appendChild(renderNode(child, depth + 1));
            });
        }
        div.appendChild(childrenDiv);
        
        // Toggle expand/collapse
        label.onclick = () => {
            const isHidden = childrenDiv.style.display === 'none';
            childrenDiv.style.display = isHidden ? 'block' : 'none';
            toggle.textContent = isHidden ? '▼ ' : '▶ ';
        };
    } else {
        // File node
        const label = document.createElement('label');
        label.style.display = 'block';
        label.style.padding = '3px';
        label.style.cursor = 'pointer';
        
        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.value = node.path;
        checkbox.checked = selectedFiles.has(node.path);
        checkbox.onchange = () => {
            if (checkbox.checked) {
                selectedFiles.add(node.path);
            } else {
                selectedFiles.delete(node.path);
            }
            updateSummary();
        };
        
        label.appendChild(checkbox);
        label.appendChild(document.createTextNode(` 📄 ${node.name}`));
        
        if (node.ref_id) {
            const refId = document.createElement('span');
            refId.textContent = ` [${node.ref_id}]`;
            refId.style.color = '#666';
            refId.style.fontSize = '0.9em';
            label.appendChild(refId);
        }
        
        div.appendChild(label);
    }
    
    return div;
}

/**
 * Update selection summary
 */
function updateSummary() {
    const count = selectedFiles.size;
    document.getElementById('summary').textContent = 
        `Selection: ${count} file${count !== 1 ? 's' : ''} selected`;
}

/**
 * Expand all tree nodes
 */
function expandAll() {
    document.querySelectorAll('.tree-container > div').forEach(d => {
        setExpanded(d, true);
    });
}

/**
 * Collapse all tree nodes
 */
function collapseAll() {
    document.querySelectorAll('.tree-container > div').forEach(d => {
        setExpanded(d, false);
    });
}

/**
 * Set expanded state for a node
 */
function setExpanded(div, expanded) {
    const childDiv = div.querySelector('div > div');
    const toggle = div.querySelector('span');
    
    if (childDiv && toggle) {
        childDiv.style.display = expanded ? 'block' : 'none';
        toggle.textContent = expanded ? '▼ ' : '▶ ';
    }
}

/**
 * Select all files
 */
function selectAll() {
    document.querySelectorAll('#tree input[type="checkbox"]').forEach(cb => {
        cb.checked = true;
        selectedFiles.add(cb.value);
    });
    updateSummary();
}

/**
 * Clear all selections
 */
function clearSelection() {
    document.querySelectorAll('#tree input[type="checkbox"]').forEach(cb => {
        cb.checked = false;
    });
    selectedFiles.clear();
    updateSummary();
}

/**
 * Export selected files
 */
function exportFiles() {
    if (selectedFiles.size === 0) {
        alert('Please select at least one file');
        return;
    }
    
    document.getElementById('loading').style.display = 'block';
    document.getElementById('error').classList.remove('active');
    
    const formData = new FormData();
    formData.append('action', 'export');
    formData.append('base_directory', currentBasePath);
    formData.append('archive_name', document.getElementById('archiveName').value.trim() || 'SIMA-Export');
    formData.append('description', document.getElementById('description').value.trim());
    formData.append('source_version', document.getElementById('sourceVersion').value);
    formData.append('target_version', document.getElementById('targetVersion').value);
    formData.append('selected_files', JSON.stringify(Array.from(selectedFiles)));
    
    fetch('', { method: 'POST', body: formData })
    .then(r => r.json())
    .then(data => {
        document.getElementById('loading').style.display = 'none';
        
        if (data.success) {
            document.getElementById('result-content').innerHTML = `
                <div class="success">
                    <h3>✓ Export Created Successfully!</h3>
                    <p><strong>Archive:</strong> ${data.archive_name}</p>
                    <p><strong>Files:</strong> ${data.file_count}</p>
                    <p><strong>Converted:</strong> ${data.converted_count}</p>
                    <p><a href="${data.download_url}" download>
                        <button>📥 Download Export</button>
                    </a></p>
                </div>`;
            document.getElementById('result-section').classList.remove('hidden');
            document.getElementById('result-section').scrollIntoView({ behavior: 'smooth' });
        } else {
            document.getElementById('error-text').textContent = data.error;
            document.getElementById('error').classList.add('active');
        }
    })
    .catch(err => {
        document.getElementById('loading').style.display = 'none';
        document.getElementById('error-text').textContent = 'Error: ' + err.message;
        document.getElementById('error').classList.add('active');
    });
}
