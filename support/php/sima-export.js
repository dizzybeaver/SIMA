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
                
                // Show sections
                document.getElementById('tree-section').classList.remove('hidden');
                document.getElementById('export-section').classList.remove('hidden');
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
        
        this.updateSelectionSummary();
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
    },
    
    /**
     * Update selection summary
     */
    updateSelectionSummary() {
        // Count total files in tree
        let totalFiles = 0;
        for (const domain of Object.values(this.knowledgeTree)) {
            totalFiles += domain.total_files || 0;
        }
        
        const selected = this.selectedFiles.size;
        const summary = document.getElementById('summary');
        summary.innerHTML = `
            <strong>Selection:</strong> ${selected} of ${totalFiles} files selected for export
            ${selected === 0 ? '<br><span style="color: #dc3545;">⚠️ No files selected</span>' : ''}
        `;
    },
    
    /**
     * Export selected files
     */
    exportFiles() {
        if (this.selectedFiles.size === 0) {
            alert('Please select at least one file to export');
            return;
        }
        
        const archiveName = document.getElementById('archiveName').value.trim();
        const description = document.getElementById('description').value.trim();
        
        if (!archiveName) {
            alert('Please enter an archive name');
            return;
        }
        
        document.getElementById('loading').style.display = 'block';
        document.getElementById('error').classList.remove('active');
        document.getElementById('export-btn').disabled = true;
        
        const formData = new FormData();
        formData.append('action', 'export');
        formData.append('base_directory', this.currentBasePath);
        formData.append('archive_name', archiveName);
        formData.append('description', description);
        formData.append('selected_files', JSON.stringify(Array.from(this.selectedFiles)));
        
        // Get version settings
        let sourceVersion = document.getElementById('sourceVersion').value;
        let targetVersion = document.getElementById('targetVersion').value;
        
        if (sourceVersion !== 'auto') {
            formData.append('source_version', sourceVersion);
        }
        
        if (targetVersion !== 'auto') {
            formData.append('target_version', targetVersion);
        }
        
        fetch('', {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            document.getElementById('loading').style.display = 'none';
            document.getElementById('export-btn').disabled = false;
            
            if (data.success) {
                // Show success message
                const successMsg = document.createElement('div');
                successMsg.className = 'section';
                successMsg.innerHTML = `
                    <h2>✅ Export Complete</h2>
                    <p>Successfully exported ${data.file_count} files.</p>
                    <a href="${data.download_url}" class="button" download>
                        ⬇️ Download Export Package
                    </a>
                `;
                
                const exportSection = document.getElementById('export-section');
                exportSection.insertAdjacentElement('afterend', successMsg);
                
                // Trigger download
                window.location.href = data.download_url;
            } else {
                document.getElementById('error-text').textContent = 'Error: ' + data.error;
                document.getElementById('error').classList.add('active');
            }
        })
        .catch(err => {
            document.getElementById('loading').style.display = 'none';
            document.getElementById('export-btn').disabled = false;
            document.getElementById('error-text').textContent = 'Error: ' + err.message;
            document.getElementById('error').classList.add('active');
        });
    }
};
