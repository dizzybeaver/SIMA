#!/bin/bash
# Phase-0.5.1-File-Movement-Script.sh
#
# Version: 1.0.0
# Date: 2025-10-28
# Purpose: Restructure SIMA from flat structure to multi-project hierarchy
# Phase: 0.5 - Project Structure Organization
# Session: 0.5.1 - Directory Restructure
#
# Usage: bash Phase-0.5.1-File-Movement-Script.sh
#
# IMPORTANT: Review and test in non-production environment first!

set -e  # Exit on error
set -u  # Exit on undefined variable

# Color codes for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Counters
DIRS_CREATED=0
FILES_MOVED=0
ERRORS=0

# Logging
LOG_FILE="phase-0.5.1-migration.log"
echo "=== SIMA v4 Phase 0.5.1 Migration Log ===" > "$LOG_FILE"
echo "Started: $(date)" >> "$LOG_FILE"
echo "" >> "$LOG_FILE"

log() {
    echo -e "$1"
    echo "$1" >> "$LOG_FILE"
}

success() {
    log "${GREEN}✓${NC} $1"
}

warning() {
    log "${YELLOW}⚠${NC} $1"
}

error() {
    log "${RED}✗${NC} $1"
    ERRORS=$((ERRORS + 1))
}

# Verify we're in the right directory
if [ ! -d "nmap" ] && [ ! -d "src" ]; then
    error "ERROR: Not in repository root. Please run from project root directory."
    exit 1
fi

log "${GREEN}=== Phase 0.5.1: Directory Restructure ===${NC}"
log ""

# ==========================================
# PHASE 1: CREATE DIRECTORY STRUCTURE
# ==========================================

log "${YELLOW}Phase 1: Creating Directory Structure${NC}"
log ""

create_dir() {
    if [ ! -d "$1" ]; then
        mkdir -p "$1"
        DIRS_CREATED=$((DIRS_CREATED + 1))
        success "Created: $1"
    else
        warning "Exists: $1"
    fi
}

# Base SIMA directories
log "Creating base SIMA directories..."
create_dir "sima/config/templates/project_template"
create_dir "sima/config/templates/entry_templates"
create_dir "sima/config/templates/index_templates"
create_dir "sima/gateways"
create_dir "sima/interfaces"
create_dir "sima/entries/core"
create_dir "sima/entries/architectures/suga"
create_dir "sima/entries/architectures/lmms"
create_dir "sima/entries/architectures/dd"
create_dir "sima/entries/architectures/zaph"
create_dir "sima/entries/languages/python"
create_dir "sima/zaph"
create_dir "sima/support/tools"
create_dir "sima/docs"
create_dir "sima/planning"

# SUGA-ISP project directories
log ""
log "Creating SUGA-ISP project directories..."
create_dir "projects/SUGA-ISP/src"
create_dir "projects/SUGA-ISP/sima/config"
create_dir "projects/SUGA-ISP/sima/nmp/NMP00"
create_dir "projects/SUGA-ISP/sima/nmp/constraints"
create_dir "projects/SUGA-ISP/sima/nmp/combinations"
create_dir "projects/SUGA-ISP/sima/nmp/lessons"
create_dir "projects/SUGA-ISP/sima/nmp/decisions"
create_dir "projects/SUGA-ISP/sima/support/tools"

# LEE project directories
log ""
log "Creating LEE project directories..."
create_dir "projects/LEE/src"
create_dir "projects/LEE/sima/config"
create_dir "projects/LEE/sima/nmp/NMP00"
create_dir "projects/LEE/sima/nmp/constraints"
create_dir "projects/LEE/sima/nmp/combinations"
create_dir "projects/LEE/sima/nmp/lessons"
create_dir "projects/LEE/sima/nmp/decisions"
create_dir "projects/LEE/sima/support/tools"

log ""
success "Phase 1 Complete: $DIRS_CREATED directories created"
log ""

# ==========================================
# PHASE 2: MOVE BASE SIMA FILES
# ==========================================

log "${YELLOW}Phase 2: Moving Base SIMA Files${NC}"
log ""

move_files() {
    local source="$1"
    local dest="$2"
    local description="$3"
    
    if [ -d "$source" ]; then
        log "Moving $description..."
        if mv "$source"/* "$dest/" 2>/dev/null; then
            local count=$(ls -1 "$dest" 2>/dev/null | wc -l)
            FILES_MOVED=$((FILES_MOVED + count))
            success "Moved $count files: $source → $dest"
        else
            warning "No files to move from $source"
        fi
    else
        warning "Source not found: $source"
    fi
}

# Generic Architecture (SUGA patterns)
log "Moving generic SUGA architecture files..."
move_files "nmap/NM01" "sima/entries/architectures/suga" "NM01 (Architecture)"
move_files "nmap/NM02" "sima/entries/architectures/suga" "NM02 (Dependencies)"
move_files "nmap/NM03" "sima/entries/architectures/suga" "NM03 (Operations)"

# Configuration
log ""
log "Moving configuration files..."
move_files "nmap/Context" "sima/config" "Context"

# Support
log ""
log "Moving support files..."
move_files "nmap/Support" "sima/support" "Support"

# Documentation
log ""
log "Moving documentation files..."
move_files "nmap/Docs" "sima/docs" "Docs"

# Planning
log ""
log "Moving planning/testing files..."
move_files "nmap/Testing" "sima/planning" "Testing/Planning"

log ""
success "Phase 2 Complete: Base SIMA files moved"
log ""

# ==========================================
# PHASE 3: MOVE SUGA-ISP PROJECT FILES
# ==========================================

log "${YELLOW}Phase 3: Moving SUGA-ISP Project Files${NC}"
log ""

# Source code
log "Moving SUGA-ISP source code..."
move_files "src" "projects/SUGA-ISP/src" "Source Code"

# Decisions (NM04)
log ""
log "Moving SUGA-ISP decisions..."
move_files "nmap/NM04" "projects/SUGA-ISP/sima/nmp/decisions" "NM04 (Decisions)"

# Constraints/Anti-Patterns (NM05)
log ""
log "Moving SUGA-ISP constraints..."
move_files "nmap/NM05" "projects/SUGA-ISP/sima/nmp/constraints" "NM05 (Constraints)"

# Lessons (NM06)
log ""
log "Moving SUGA-ISP lessons..."
move_files "nmap/NM06" "projects/SUGA-ISP/sima/nmp/lessons" "NM06 (Lessons)"

# Decision Logic/Combinations (NM07)
log ""
log "Moving SUGA-ISP combinations..."
move_files "nmap/NM07" "projects/SUGA-ISP/sima/nmp/combinations" "NM07 (Combinations)"

# AWS-Specific (AWS06)
log ""
log "Moving AWS-specific lessons..."
if [ -d "nmap/AWS/AWS06" ]; then
    move_files "nmap/AWS/AWS06" "projects/SUGA-ISP/sima/nmp/lessons" "AWS06 (AWS Lessons)"
fi

# Master Indexes (NM00)
log ""
log "Moving SUGA-ISP master indexes..."
move_files "nmap/NM00" "projects/SUGA-ISP/sima/nmp/NMP00" "NM00 (Master Indexes)"

# AWS Master Indexes (AWS00)
log ""
if [ -d "nmap/AWS/AWS00" ]; then
    log "Moving AWS master indexes..."
    if [ -d "nmap/AWS/AWS00" ] && [ "$(ls -A nmap/AWS/AWS00)" ]; then
        cp -r nmap/AWS/AWS00/* projects/SUGA-ISP/sima/nmp/NMP00/ 2>/dev/null || warning "No AWS00 files to copy"
        success "Copied AWS00 indexes to SUGA-ISP"
    fi
fi

log ""
success "Phase 3 Complete: SUGA-ISP project files moved"
log ""

# ==========================================
# PHASE 4: CLEANUP EMPTY DIRECTORIES
# ==========================================

log "${YELLOW}Phase 4: Cleanup${NC}"
log ""

# Remove empty nmap subdirectories
log "Removing empty old directories..."
for dir in nmap/NM* nmap/AWS* nmap/Context nmap/Support nmap/Docs nmap/Testing; do
    if [ -d "$dir" ] && [ ! "$(ls -A $dir)" ]; then
        rmdir "$dir" 2>/dev/null && success "Removed empty: $dir" || warning "Could not remove: $dir"
    fi
done

# Remove empty src directory
if [ -d "src" ] && [ ! "$(ls -A src)" ]; then
    rmdir "src" 2>/dev/null && success "Removed empty: src" || warning "Could not remove: src"
fi

# Check if nmap and AWS parent dirs are empty
if [ -d "nmap/AWS" ] && [ ! "$(ls -A nmap/AWS)" ]; then
    rmdir "nmap/AWS" 2>/dev/null && success "Removed empty: nmap/AWS"
fi

if [ -d "nmap" ] && [ ! "$(ls -A nmap)" ]; then
    rmdir "nmap" 2>/dev/null && success "Removed empty: nmap" || warning "nmap directory not empty (expected if some files remain)"
fi

log ""
success "Phase 4 Complete: Cleanup finished"
log ""

# ==========================================
# FINAL REPORT
# ==========================================

log "${GREEN}=== Migration Complete ===${NC}"
log ""
log "Statistics:"
log "  Directories Created: $DIRS_CREATED"
log "  Files Moved: $FILES_MOVED"
log "  Errors: $ERRORS"
log ""
log "Finished: $(date)"
log ""

if [ $ERRORS -eq 0 ]; then
    success "Migration completed successfully with no errors!"
    log ""
    log "Next Steps:"
    log "  1. Review Phase-0.5.1-Verification-Report.md"
    log "  2. Verify no base SIMA contamination"
    log "  3. Test file access via web_fetch"
    log "  4. Proceed to Session 0.5.2 (Projects Config)"
else
    warning "Migration completed with $ERRORS errors. Please review log."
fi

log ""
log "Full log saved to: $LOG_FILE"

exit 0
