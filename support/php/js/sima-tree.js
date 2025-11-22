/**
 * sima-tree.js
 * 
 * Shared JavaScript for SIMA collapsible tree functionality
 * Version: 1.2.0
 * Date: 2025-11-21
 * Location: /support/php/
 * 
 * MODIFIED: Added renderKnowledgeTree and createTreeNode for shared rendering
 */

const SIMATree = {
    // State storage key prefix
    storageKeyPrefix: 'sima_tree_state_',
    
    /**
     * Render knowledge tree from data structure
     * ADDED: Shared renderer for all SIMA tools
     */
    renderKnowledgeTree(container, treeData, options = {}) {
        const {
            onFileToggle = null,
            onSelectionChange = null,
            domainExpanded = true,
            categoryExpanded = false
        } = options;
        
        container.innerHTML = '';
        
        for (const [domainName, domain] of Object.entries(treeData)) {
            if (domain.total_files === 0) continue;
            
            // Create domain folder
            const domainNode = this.createTreeNode('folder', domainName, container, {
                path: domainName,
                startExpanded: domainExpanded
            });
            const domainChildren = domainNode.querySelector('.tree-children');
            
            if (domain.categories) {
                for (const [catName, category] of Object.entries(domain.categories)) {
                    // Create category folder
                    const catNode = this.createTreeNode('folder', `${catName} (${category.file_count})`, domainChildren, {
                        path: `${domainName}/${catName}`,
                        startExpanded: categoryExpanded
                    });
                    const catChildren = catNode.querySelector('.tree-children');
                    
                    // Add files
                    category.files.forEach(file => {
                        this.createTreeNode('file', file.filename, catChildren, {
                            path: file.relative_path,
                            onToggleFile: (path, checked) => {
                                if (onFileToggle) onFileToggle(path, checked);
                                if (onSelectionChange) onSelectionChange();
                            }
                        });
                    });
                }
            }
        }
    },
    
    /**
     * Create a tree node programmatically
     * ADDED: Helper for building trees in JavaScript
     */
    createTreeNode(type, name, parent, options = {}) {
        const {
            path = null,
            onToggleFile = null,
            startExpanded = false
        } = options;
        
        const div = document.createElement('div');
        div.className = `tree-node ${type}`;
        if (path) div.dataset.path = path;
        
        if (type === 'folder') {
            // Create toggle arrow
            const toggle = document.createElement('span');
            toggle.className = startExpanded ? 'tree-toggle expanded' : 'tree-toggle';
            toggle.textContent = startExpanded ? '▼' : '▶';
            toggle.onclick = () => this.toggleBranch(toggle);
            div.appendChild(toggle);
            
            // Create checkbox
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.onchange = () => this.selectBranch(checkbox);
            div.appendChild(checkbox);
            
            // Create label
            const label = document.createElement('label');
            label.innerHTML = `<span class="folder-icon">📁</span><span class="node-name">${name}</span>`;
            div.appendChild(label);
            
            // Create children container
            const children = document.createElement('div');
            children.className = 'tree-children';
            children.style.display = startExpanded ? 'block' : 'none';
            div.appendChild(children);
            
        } else {
            // File node - spacer instead of toggle
            const spacer = document.createElement('span');
            spacer.className = 'tree-spacer';
            div.appendChild(spacer);
            
            // Create checkbox
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            if (onToggleFile) {
                checkbox.onchange = () => onToggleFile(path, checkbox.checked);
            }
            div.appendChild(checkbox);
            
            // Create label
            const label = document.createElement('label');
            label.innerHTML = `<span class="file-icon">📄</span><span class="node-name">${name}</span>`;
            div.appendChild(label);
        }
        
        parent.appendChild(div);
        return div;
    },
    
    /**
     * Toggle branch expand/collapse
     */
    toggleBranch(element) {
        const node = element.parentElement;
        const children = node.querySelector('.tree-children');
        const toggle = element;
        const folderIcon = node.querySelector('.folder-icon');
        
        if (!children) return;
        
        if (children.style.display === 'none' || children.style.display === '') {
            children.style.display = 'block';
            toggle.classList.add('expanded');
            toggle.textContent = '▼';
            if (folderIcon) folderIcon.textContent = '📂';
            if (node.dataset.path) {
                this.saveExpandedState(node.dataset.path, true);
            }
        } else {
            children.style.display = 'none';
            toggle.classList.remove('expanded');
            toggle.textContent = '▶';
            if (folderIcon) folderIcon.textContent = '📁';
            if (node.dataset.path) {
                this.saveExpandedState(node.dataset.path, false);
            }
        }
    },
    
    /**
     * Select/deselect entire branch
     */
    selectBranch(checkbox) {
        const node = checkbox.closest('.tree-node');
        const children = node.querySelectorAll('.tree-children input[type="checkbox"]');
        
        children.forEach(child => {
            child.checked = checkbox.checked;
            // Trigger change event so onToggleFile handlers fire
            child.dispatchEvent(new Event('change'));
        });
        
        this.updateParentCheckboxes(checkbox);
    },
    
    /**
     * Update parent checkbox state (indeterminate/checked/unchecked)
     */
    updateParentCheckboxes(checkbox) {
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
        
        this.updateParentCheckboxes(parentCheckbox);
    },
    
    /**
     * Expand all branches
     */
    expandAll() {
        document.querySelectorAll('.tree-toggle:not(.expanded)').forEach(toggle => {
            this.toggleBranch(toggle);
        });
    },
    
    /**
     * Collapse all branches
     */
    collapseAll() {
        document.querySelectorAll('.tree-toggle.expanded').forEach(toggle => {
            this.toggleBranch(toggle);
        });
    },
    
    /**
     * Select all checkboxes
     */
    selectAll() {
        document.querySelectorAll('input[type="checkbox"]').forEach(cb => {
            cb.checked = true;
            cb.indeterminate = false;
            // Trigger change event
            cb.dispatchEvent(new Event('change'));
        });
    },
    
    /**
     * Clear all selections
     */
    clearSelection() {
        document.querySelectorAll('input[type="checkbox"]').forEach(cb => {
            cb.checked = false;
            cb.indeterminate = false;
            // Trigger change event
            cb.dispatchEvent(new Event('change'));
        });
    },
    
    /**
     * Filter tree based on search term
     */
    filterTree(searchTerm) {
        const nodes = document.querySelectorAll('.tree-node');
        const term = searchTerm.toLowerCase().trim();
        
        if (term === '') {
            nodes.forEach(node => node.style.display = 'block');
            return;
        }
        
        // Hide all nodes first
        nodes.forEach(node => node.style.display = 'none');
        
        // Show matches and ancestors
        nodes.forEach(node => {
            const label = node.querySelector('label .node-name');
            if (!label) return;
            
            if (label.textContent.toLowerCase().includes(term)) {
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
                            parent.style.display = 'block';
                            const folderIcon = parentNode.querySelector('.folder-icon');
                            if (folderIcon) folderIcon.textContent = '📂';
                        }
                    }
                    parent = parentNode ? parentNode.parentElement : null;
                }
            }
        });
    },
    
    /**
     * Save expanded state to localStorage
     */
    saveExpandedState(path, isExpanded) {
        const key = this.getStorageKey();
        const state = JSON.parse(localStorage.getItem(key) || '{}');
        state[path] = isExpanded;
        localStorage.setItem(key, JSON.stringify(state));
    },
    
    /**
     * Restore expanded state from localStorage
     */
    restoreExpandedState() {
        const key = this.getStorageKey();
        const state = JSON.parse(localStorage.getItem(key) || '{}');
        
        Object.keys(state).forEach(path => {
            if (state[path]) {
                const node = document.querySelector(`[data-path="${path}"]`);
                if (node) {
                    const toggle = node.querySelector('.tree-toggle');
                    if (toggle && !toggle.classList.contains('expanded')) {
                        this.toggleBranch(toggle);
                    }
                }
            }
        });
    },
    
    /**
     * Clear saved state
     */
    clearState() {
        if (confirm('Clear all saved expand/collapse state?')) {
            localStorage.removeItem(this.getStorageKey());
            location.reload();
        }
    },
    
    /**
     * Get storage key for current page
     */
    getStorageKey() {
        const page = window.location.pathname.split('/').pop().replace('.php', '');
        return this.storageKeyPrefix + page;
    },
    
    /**
     * Initialize tree functionality
     */
    init() {
        // Restore expanded state
        this.restoreExpandedState();
        
        // Setup checkbox change listeners for parent updates
        document.querySelectorAll('input[type="checkbox"]').forEach(cb => {
            const originalHandler = cb.onchange;
            cb.addEventListener('change', () => {
                if (cb.closest('.tree-node.file')) {
                    this.updateParentCheckboxes(cb);
                }
            });
        });
    }
};

// Global convenience functions for inline onclick handlers
function toggleBranch(element) {
    SIMATree.toggleBranch(element);
}

function selectBranch(checkbox) {
    SIMATree.selectBranch(checkbox);
}

function expandAll() {
    SIMATree.expandAll();
}

function collapseAll() {
    SIMATree.collapseAll();
}

function selectAll() {
    SIMATree.selectAll();
}

function clearSelection() {
    SIMATree.clearSelection();
}

function filterTree(term) {
    SIMATree.filterTree(term);
}

function clearState() {
    SIMATree.clearState();
}

// Auto-initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    SIMATree.init();
});
