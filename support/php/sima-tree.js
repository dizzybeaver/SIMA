/**
 * sima-tree.js
 * 
 * Shared JavaScript for SIMA collapsible tree functionality
 * Version: 1.0.0
 * Date: 2025-11-19
 * Location: /support/js/
 */

const SIMATree = {
    // State storage key prefix
    storageKeyPrefix: 'sima_tree_state_',
    
    /**
     * Toggle branch expand/collapse
     */
    toggleBranch(element) {
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
            this.saveExpandedState(node.dataset.path, true);
        } else {
            children.style.display = 'none';
            toggle.classList.remove('expanded');
            toggle.textContent = '▶';
            if (folderIcon) folderIcon.textContent = '📁';
            this.saveExpandedState(node.dataset.path, false);
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
        });
    },
    
    /**
     * Clear all selections
     */
    clearSelection() {
        document.querySelectorAll('input[type="checkbox"]').forEach(cb => {
            cb.checked = false;
            cb.indeterminate = false;
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
        
        // Setup checkbox change listeners
        document.querySelectorAll('input[type="checkbox"]').forEach(cb => {
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
