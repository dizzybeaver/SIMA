/**
 * sima-import.js
 * 
 * Import-specific JavaScript for SIMA Import Tool
 * Version: 1.0.0
 * Date: 2025-11-21
 * Location: /support/php/
 * 
 * ADDED: Version-aware import functionality
 * - Target directory scanning
 * - Version detection and conversion
 * - Tree rendering using shared SIMATree
 */

const SIMAImport = {
    importData: null,
    selectedFiles: new Set(),
    targetDirectory: '',
    targetVersionInfo: null,
    
    /**
     * Scan target directory for version info
     * ADDED: Detects target SIMA installation version
     */
    scanTargetDirectory() {
        const directory = document.getElementById('targetDirectory').value.trim();
        
        if (!directory) {
            alert('Please enter a target directory path');
            return;
        }
        
        document.getElementById('loading').style.display = 'block';
        document.getElementById('error').classList.remove('active');
        document.getElementById('scan-target-btn').disabled = true;
        
        const formData = new FormData();
        formData.append('action', 'scan_target');
        formData.append('directory', directory);
        
        fetch('', {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            document.getElementById('loading').style.display = 'none';
            document.getElementById('scan-target-btn').disabled = false;
            
            if (data.success) {
                this.targetDirectory = data.directory;
                this.targetVersionInfo = data.version_info;
                
                // Display detected version
                const versionEl = document.getElementById('targetVersion');
                if (this.targetVersionInfo && this.targetVersionInfo.version !== 'unknown') {
                    versionEl.innerHTML = `
                        <strong>✓ Detected Target:</strong> 
                        SIMA v${this.targetVersionInfo.version} 
                        (${this.targetVersionInfo.version_string})
                        <br>
                        <small>Files will be converted if source version differs</small>
                    `;
                    versionEl.style.display = 'block';
                    
                    // Update metadata display if import already loaded
                    if (this.importData) {
                        this.updateVersionDisplay();
                    }
                } else {
                    versionEl.innerHTML = `
                        <strong>⚠️ Warning:</strong> Could not detect SIMA version
                        <br>
                        <small>Files will be imported without conversion</small>
                    `;
                    versionEl.style.display = 'block';
                    versionEl.style.backgroundColor = '#fff3cd';
                }
            } else {
                document.getElementById('error-text').textContent = 'Error: ' + data.error;
                document.getElementById('error').classList.add('active');
            }
        })
        .catch(err => {
            document.getElementById('loading').style.display = 'none';
            document.getElementById('scan-target-btn').disabled = false;
            document.getElementById('error-text').textContent = 'Error: ' + err.message;
            document.getElementById('error').classList.add('active');
        });
    },
    
    /**
     * Initialize file upload handling
     */
    init() {
        const uploadArea = document.getElementById('upload-area');
        const fileInput = document.getElementById('file-input');
        
        // Drag and drop handlers
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.style.borderColor = '#007bff';
            uploadArea.style.backgroundColor = '#f0f8ff';
        });
        
        uploadArea.addEventListener('dragleave', () => {
            uploadArea.style.borderColor = '#ddd';
            uploadArea.style.backgroundColor = '#fafafa';
        });
        
        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.style.borderColor = '#ddd';
            uploadArea.style.backgroundColor = '#fafafa';
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                this.handleFile(files[0]);
            }
        });
        
        // File input handler
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                this.handleFile(e.target.files[0]);
            }
        });
        
        // Click to select file
        uploadArea.addEventListener('click', () => {
            fileInput.click();
        });
    },
    
    /**
     * Handle uploaded file
     */
    handleFile(file) {
        if (!file.name.endsWith('.md')) {
            alert('Please upload a Markdown (.md) file');
            return;
        }
        
        document.getElementById('loading').style.display = 'block';
        document.getElementById('error').classList.remove('active');
        
        const formData = new FormData();
        formData.append('action', 'parse');
        formData.append('import_instructions', file);
        
        fetch('', {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            document.getElementById('loading').style.display = 'none';
            
            if (data.success) {
                this.importData = data.data;
                this.displayMetadata();
                this.renderTree();
                this.updateVersionDisplay();
                
                // Show sections
                document.getElementById('metadata-section').classList.remove('hidden');
                document.getElementById('tree-section').classList.remove('hidden');
                document.getElementById('generate-section').classList.remove('hidden');
            } else {
                document.getElementById('error-text').textContent = 'Error: ' + data.error;
                document.getElementById('error').classList.add('active');
            }
        })
        .catch(err => {
            document.getElementById('loading').style.display = 'none';
            document.getElementById('error-text').textContent = 'Error: ' + err.message;
            document.getElementById('error').classList.add('active');
        });
    },
    
    /**
     * Display archive metadata
     */
    displayMetadata() {
        const meta = this.importData.metadata;
        
        document.getElementById('meta-archive').textContent = meta.archive || 'Unknown';
        document.getElementById('meta-source-version').textContent = meta.sima_version || 'Unknown';
        document.getElementById('meta-total').textContent = meta.total_files || 0;
    },
    
    /**
     * Update version display and conversion warning
     * ADDED: Shows conversion status
     */
    updateVersionDisplay() {
        if (!this.importData || !this.targetVersionInfo) {
            return;
        }
        
        const sourceVersion = this.importData.metadata.sima_version || 'unknown';
        const targetVersion = this.targetVersionInfo.version || 'unknown';
        
        document.getElementById('meta-target-version').textContent = 
            targetVersion === 'unknown' ? 'Not detected' : `v${targetVersion}`;
        
        // Show conversion warning if versions differ
        const warningEl = document.getElementById('conversion-warning');
        if (sourceVersion !== 'unknown' && 
            targetVersion !== 'unknown' && 
            sourceVersion !== targetVersion) {
            warningEl.innerHTML = `
                ⚠️ <strong>Version Conversion:</strong> 
                Files will be converted from v${sourceVersion} to v${targetVersion} during import
            `;
            warningEl.style.display = 'block';
        } else {
            warningEl.style.display = 'none';
        }
    },
    
    /**
     * Render file tree using shared SIMATree renderer
     * ADDED: Uses shared tree functionality
     */
    renderTree() {
        const container = document.getElementById('tree');
        
        // Build tree structure for renderer
        const treeData = {};
        
        // Add selected files
        if (this.importData.selected) {
            for (const [categoryName, category] of Object.entries(this.importData.selected)) {
                if (!treeData['Selected']) {
                    treeData['Selected'] = {
                        total_files: 0,
                        categories: {}
                    };
                }
                
                treeData['Selected'].categories[categoryName] = {
                    file_count: category.files.length,
                    files: category.files.map(f => ({
                        filename: f.filename,
                        relative_path: f.target_path
                    }))
                };
                treeData['Selected'].total_files += category.files.length;
                
                // Pre-select these files
                category.files.forEach(f => {
                    if (f.checked) {
                        this.selectedFiles.add(f.target_path);
                    }
                });
            }
        }
        
        // Add unselected files
        if (this.importData.unselected) {
            for (const [categoryName, category] of Object.entries(this.importData.unselected)) {
                if (!treeData['Unselected']) {
                    treeData['Unselected'] = {
                        total_files: 0,
                        categories: {}
                    };
                }
                
                treeData['Unselected'].categories[categoryName] = {
                    file_count: category.files.length,
                    files: category.files.map(f => ({
                        filename: f.filename,
                        relative_path: f.target_path || f.filename
                    }))
                };
                treeData['Unselected'].total_files += category.files.length;
            }
        }
        
        // Render using shared tree
        SIMATree.renderKnowledgeTree(container, treeData, {
            onFileToggle: (path, checked) => this.toggleFile(path, checked),
            onSelectionChange: () => this.updateSelectionSummary(),
            domainExpanded: true,
            categoryExpanded: true
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
        const total = this.importData.metadata.total_files || 0;
        const selected = this.selectedFiles.size;
        
        const summary = document.getElementById('summary');
        summary.innerHTML = `
            <strong>Selection:</strong> ${selected} of ${total} files selected for import
            ${selected === 0 ? '<br><span style="color: #dc3545;">⚠️ No files selected</span>' : ''}
        `;
    },
    
    /**
     * Generate updated import instructions
     */
    generateInstructions() {
        if (this.selectedFiles.size === 0) {
            alert('Please select at least one file to import');
            return;
        }
        
        document.getElementById('loading').style.display = 'block';
        document.getElementById('error').classList.remove('active');
        document.getElementById('generate-btn').disabled = true;
        
        const formData = new FormData();
        formData.append('action', 'generate');
        formData.append('original_data', JSON.stringify(this.importData));
        formData.append('new_selections', JSON.stringify(Array.from(this.selectedFiles)));
        
        // ADDED: Include target version for conversion
        if (this.targetVersionInfo && this.targetVersionInfo.version !== 'unknown') {
            formData.append('target_version', this.targetVersionInfo.version);
        }
        
        fetch('', {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            document.getElementById('loading').style.display = 'none';
            document.getElementById('generate-btn').disabled = false;
            
            if (data.success) {
                // Trigger download
                window.location.href = data.download_url;
                
                // Show preview
                const preview = document.createElement('div');
                preview.className = 'section';
                preview.innerHTML = `
                    <h2>✅ Instructions Generated</h2>
                    <p>Your updated import instructions have been downloaded.</p>
                    <details>
                        <summary>Preview</summary>
                        <pre style="background: #f5f5f5; padding: 15px; overflow: auto; max-height: 400px;">${data.content}</pre>
                    </details>
                `;
                
                const generateSection = document.getElementById('generate-section');
                generateSection.insertAdjacentElement('afterend', preview);
            } else {
                document.getElementById('error-text').textContent = 'Error: ' + data.error;
                document.getElementById('error').classList.add('active');
            }
        })
        .catch(err => {
            document.getElementById('loading').style.display = 'none';
            document.getElementById('generate-btn').disabled = false;
            document.getElementById('error-text').textContent = 'Error: ' + err.message;
            document.getElementById('error').classList.add('active');
        });
    }
};

// Auto-initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    SIMAImport.init();
});
