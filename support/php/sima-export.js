/**
 * sima-export.js
 * 
 * Export-specific JavaScript for SIMA Export Tool
 * Version: 1.0.0
 * Date: 2025-11-21
 * Location: /support/php/
 */

const SIMAExport = {
    knowledgeTree: {},
    selectedFiles: new Set(),
    currentBasePath: '',
    currentVersionInfo: null,
    
    /**
     * Load knowledge tree from directory
     */
    loadKnowledgeTree() {
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
                this.knowledgeTree = data.tree;
                this.currentBasePath = data.base_path;
                this.currentVersionInfo = data.version_info;
                
                // Display detected version
                const versionEl = document.getElementById('detectedVersion');
                if (this.currentVersionInfo && this.currentVersionInfo.version !== 'unknown') {
                    versionEl.textContent = `✓ Detected: SIMA v${this.currentVersionInfo.version} (${this.currentVersionInfo.version_string})`;
                    versionEl.style.display = 'block';
                    
                    // Set source version dropdown
                    document.getElementById('sourceVersion').value = this.currentVersionInfo.version;
                }
                
                this.renderTree();
            } else {
                document.getElementById('error-text').textContent = 'Error: ' + data.error;
                document.getElementById('error').classList.add('active');
            }
        })
        .catch(err => {
            document.getElementById('loading').style.display = 'none';
            document.getElementById('scan-btn').disabled = false;
            document.getElementById('error-text').textContent = 'Error: ' + err.message;
            document.getElementById('error').classList.add('active');
        });
    },
    
    /**
     * Render tree using shared SIMATree renderer
     */
    renderTree() {
        const container = document.getElementById('tree');
        
        SIMATree.renderKnowledgeTree(container, this.knowledgeTree, {
            onFileToggle: (path, checked) => this.toggleFile(path, checked),
            onSelectionChange: () => this.updateSelectionSummary(),
            domainExpanded: true,
            categoryExpanded: false
        });
    },
    
    /**
     * Toggle file selection
     */
    toggleFile(path, checked) {
        if (checked) {
            this.selectedFiles.add(path);
        } else {
            this.selectedFiles.delete(path);
        }
        this.updateSelectionSummary();
    },
    
    /**
     * Update selection summary
     */
    updateSelectionSummary() {
        document.getElementById('selectionSummary').textContent = `Selected: ${this.selectedFiles.size}`;
        document.getElementById('exportButton').disabled = this.selectedFiles.size === 0;
    },
    
    /**
     * Create export archive
     */
    createExport() {
        const archiveName = document.getElementById('exportName').value.trim();
        const description = document.getElementById('description').value.trim();
        const sourceVersion = document.getElementById('sourceVersion').value;
        const targetVersion = document.getElementById('targetVersion').value;
        
        if (!archiveName) {
            alert('Please enter an archive name');
            return;
        }
        
        if (this.selectedFiles.size === 0) {
            alert('Please select at least one file');
            return;
        }
        
        document.getElementById('exportButton').disabled = true;
        document.getElementById('exportButton').textContent = 'Creating...';
        
        const formData = new FormData();
        formData.append('action', 'export');
        formData.append('base_directory', this.currentBasePath);
        formData.append('archive_name', archiveName);
        formData.append('description', description);
        formData.append('selected_files', JSON.stringify(Array.from(this.selectedFiles)));
        
        // Send version parameters
        if (sourceVersion !== 'auto') {
            formData.append('source_version', sourceVersion);
        }
        if (targetVersion !== 'same') {
            formData.append('target_version', targetVersion);
        }
        
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
        })
        .catch(err => {
            document.getElementById('exportButton').disabled = false;
            document.getElementById('exportButton').textContent = '📦 Create Archive';
            alert('Error: ' + err.message);
        });
    }
};

// Global convenience functions for onclick handlers
function loadKnowledgeTree() {
    SIMAExport.loadKnowledgeTree();
}

function createExport() {
    SIMAExport.createExport();
}
