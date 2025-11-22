/**
 * sima-export-inline.js
 * 
 * Self-Contained Export Tool JavaScript
 * Version: 1.0.0
 * Date: 2025-11-22
 * 
 * No external dependencies - everything inline
 * Paste this into <script> tags in HTML file
 */

let knowledgeTree = {};
let selectedFiles = new Set();
let currentBasePath = '';

/**
 * Scan directory
 */
function scanDirectory() {
    const directory = document.getElementById('simaDirectory').value.trim();
    
    if (!directory) {
        alert('Please enter a directory path');
        return;
    }
    
    document.getElementById('loading').style.display = 'block';
    document.getElementById('error').classList.remove('active');
    document.getElementById('tree').innerHTML = '';
    document.getElementById('scan-btn').disabled = true;
    
    const formData = new FormData();
    formData.append('action', 'scan');
    formData.append('directory', directory);
    
    fetch('', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('loading').style.display = 'none';
        document.getElementById('scan-btn').disabled = false;
        
        if (data.success) {
            knowledgeTree = data.tree;
            currentBasePath = data.base_path;
            
            // Show detected version
            if (data.version_info && data.version_info.version !== 'unknown') {
                document.getElementById('detectedVersion').textContent = 
                    `✓ Detected: SIMA v${data.version_info.version} (${data.version_info.version_string})`;
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
        document.getElementById('error-text').textContent = 'Network error: ' + err.message;
        document.getElementById('error').classList.add('active');
    });
}

/**
 * Render tree
 */
function renderTree() {
    const container = document.getElementById('tree');
    container.innerHTML = '';
    
    if (!knowledgeTree || knowledgeTree.length === 0) {
        container.innerHTML = '<p>No files found</p>';
        return;
    }
    
    knowledgeTree.forEach(node => {
        const element = renderNode(node, 0);
        container.appendChild(element);
    });
}

/**
 * Render single node
 */
function renderNode(node, depth) {
    const div = document.createElement('div');
    div.style.marginLeft = (depth * 20) + 'px';
    div.style.padding = '5px';
    
    if (node.type === 'directory') {
        // Directory node
        const label = document.createElement('div');
        label.style.cursor = 'pointer';
        label.style.fontWeight = 'bold';
        label.style.padding = '5px';
        label.style.background = '#f0f0f0';
        label.style.marginBottom = '5px';
        label.style.borderRadius = '3px';
        
        const toggle = document.createElement('span');
        toggle.textContent = '▼ ';
        toggle.style.display = 'inline-block';
        toggle.style.width = '20px';
        
        const name = document.createElement('span');
        name.textContent = `📁 ${node.name} (${node.total_files} files)`;
        
        label.appendChild(toggle);
        label.appendChild(name);
        div.appendChild(label);
        
        const childrenDiv = document.createElement('div');
        childrenDiv.style.display = 'block';
        
        if (node.children && node.children.length > 0) {
            node.children.forEach(child => {
                const childElement = renderNode(child, depth + 1);
                childrenDiv.appendChild(childElement);
            });
        }
        
        div.appendChild(childrenDiv);
        
        // Toggle functionality
        label.onclick = () => {
            if (childrenDiv.style.display === 'none') {
                childrenDiv.style.display = 'block';
                toggle.textContent = '▼ ';
            } else {
                childrenDiv.style.display = 'none';
                toggle.textContent = '▶ ';
            }
        };
        
    } else if (node.type === 'file') {
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
        
        const name = document.createElement('span');
        name.textContent = ` 📄 ${node.name}`;
        if (node.ref_id) {
            name.textContent += ` [${node.ref_id}]`;
        }
        
        label.appendChild(checkbox);
        label.appendChild(name);
        div.appendChild(label);
    }
    
    return div;
}

/**
 * Update selection summary
 */
function updateSummary() {
    document.getElementById('summary').textContent = 
        `Selection: ${selectedFiles.size} file${selectedFiles.size !== 1 ? 's' : ''} selected`;
}

/**
 * Expand all directories
 */
function expandAll() {
    document.querySelectorAll('#tree > div').forEach(div => {
        setExpanded(div, true);
    });
}

/**
 * Collapse all directories
 */
function collapseAll() {
    document.querySelectorAll('#tree > div').forEach(div => {
        setExpanded(div, false);
    });
}

/**
 * Set expanded state recursively
 */
function setExpanded(div, expanded) {
    const childrenDiv = div.querySelector('div > div');
    const toggle = div.querySelector('span');
    
    if (childrenDiv && toggle) {
        childrenDiv.style.display = expanded ? 'block' : 'none';
        toggle.textContent = expanded ? '▼ ' : '▶ ';
    }
    
    // Recurse into children
    div.querySelectorAll(':scope > div > div').forEach(child => {
        setExpanded(child, expanded);
    });
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
 * Export files
 */
function exportFiles() {
    if (selectedFiles.size === 0) {
        alert('Please select at least one file to export');
        return;
    }
    
    const archiveName = document.getElementById('archiveName').value.trim() || 'SIMA-Export';
    const description = document.getElementById('description').value.trim();
    const sourceVersion = document.getElementById('sourceVersion').value;
    const targetVersion = document.getElementById('targetVersion').value;
    
    document.getElementById('loading').style.display = 'block';
    document.getElementById('error').classList.remove('active');
    
    const formData = new FormData();
    formData.append('action', 'export');
    formData.append('base_directory', currentBasePath);
    formData.append('archive_name', archiveName);
    formData.append('description', description);
    formData.append('source_version', sourceVersion);
    formData.append('target_version', targetVersion);
    formData.append('selected_files', JSON.stringify(Array.from(selectedFiles)));
    
    fetch('', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('loading').style.display = 'none';
        
        if (data.success) {
            const resultDiv = document.getElementById('result-content');
            resultDiv.innerHTML = `
                <div class="success">
                    <h3>✓ Export Created Successfully!</h3>
                    <p><strong>Archive:</strong> ${data.archive_name}</p>
                    <p><strong>Files:</strong> ${data.file_count}</p>
                    <p><strong>Converted:</strong> ${data.converted_count}</p>
                    <p><strong>Source Version:</strong> ${data.source_version}</p>
                    <p><strong>Target Version:</strong> ${data.target_version}</p>
                    <p><a href="${data.download_url}" download><button>📥 Download Export</button></a></p>
                </div>
            `;
            document.getElementById('result-section').classList.remove('hidden');
            
            // Scroll to result
            document.getElementById('result-section').scrollIntoView({ behavior: 'smooth' });
        } else {
            document.getElementById('error-text').textContent = data.error;
            document.getElementById('error').classList.add('active');
        }
    })
    .catch(err => {
        document.getElementById('loading').style.display = 'none';
        document.getElementById('error-text').textContent = 'Network error: ' + err.message;
        document.getElementById('error').classList.add('active');
    });
}
