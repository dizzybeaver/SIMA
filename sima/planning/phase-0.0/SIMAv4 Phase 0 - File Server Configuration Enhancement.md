# SIMAv4 Phase 0 - File Server Configuration Enhancement

**Version:** 1.0.0  
**Date:** 2025-10-27  
**Duration:** 1 week  
**Priority:** P0 (Foundation - MUST complete before Phase 1)  
**Status:** 🆕 New Phase (Added after original planning)

---

## 🎯 PHASE OBJECTIVES

### Primary Goals

1. **Genericize all hardcoded file server URLs** across the entire project
2. **Create web interface** for easy URL configuration
3. **Automate File-Server-URLs.md generation** with one click
4. **Scan and update all documentation** to reference configurable URLs
5. **Keep files lean** - no bloat, efficient generation

### Success Criteria

- ✅ Zero hardcoded "claude.dizzybeaver.com" references in docs
- ✅ SERVER-CONFIG.md is single source of truth for URLs
- ✅ Web interface generates valid File-Server-URLs.md
- ✅ All documentation references SERVER-CONFIG.md
- ✅ File-Server-URLs.md stays < 300 lines (unbloated)
- ✅ Generation time < 5 seconds

---

## 📋 STEP-BY-STEP IMPLEMENTATION

### Step 1: Audit Hardcoded URLs (Day 1 - 4 hours)

**Task:** Find all hardcoded "claude.dizzybeaver.com" references

**Files to Scan:**

```bash
# Scan all markdown files
grep -r "claude.dizzybeaver.com" nmap/ --include="*.md" > url-audit.txt

# Expected locations:
/nmap/Support/File-Server-URLs.md           # Main file (OK - this is output)
/nmap/Support/Workflow-*.md                 # Example fetches (NEEDS UPDATE)
/nmap/Testing/Phase-*.md                    # Test examples (NEEDS UPDATE)
/nmap/Docs/*.md                             # Documentation (NEEDS UPDATE)
/nmap/Context/*.md                          # Mode contexts (CHECK)
```

**Deliverable:** `url-audit-report.md` with:
- List of all files with hardcoded URLs
- Count per file
- Classification (OK vs NEEDS UPDATE)

---

### Step 2: Create Scanning Tool (Day 1 - 2 hours)

**Task:** Build automated scanner for hardcoded URLs

**Tool:** `scan-hardcoded-urls.py`

```python
#!/usr/bin/env python3
"""
Scan for hardcoded file server URLs in documentation
"""

import os
import re
from pathlib import Path

HARDCODED_PATTERNS = [
    r'https://claude\.dizzybeaver\.com',
    r'claude\.dizzybeaver\.com',
    # Add other patterns to check
]

def scan_file(filepath):
    """Scan single file for hardcoded URLs"""
    matches = []
    with open(filepath, 'r', encoding='utf-8') as f:
        for line_num, line in enumerate(f, 1):
            for pattern in HARDCODED_PATTERNS:
                if re.search(pattern, line):
                    matches.append({
                        'file': filepath,
                        'line': line_num,
                        'content': line.strip()
                    })
    return matches

def scan_directory(directory, extensions=['.md', '.txt']):
    """Scan entire directory tree"""
    all_matches = []
    for root, dirs, files in os.walk(directory):
        for file in files:
            if any(file.endswith(ext) for ext in extensions):
                filepath = os.path.join(root, file)
                matches = scan_file(filepath)
                all_matches.extend(matches)
    return all_matches

def generate_report(matches):
    """Generate markdown report"""
    report = "# Hardcoded URL Audit Report\n\n"
    report += f"**Date:** {datetime.now().strftime('%Y-%m-%d')}\n"
    report += f"**Total Matches:** {len(matches)}\n\n"
    
    # Group by file
    by_file = {}
    for match in matches:
        if match['file'] not in by_file:
            by_file[match['file']] = []
        by_file[match['file']].append(match)
    
    report += "## Files Requiring Updates\n\n"
    for file, file_matches in sorted(by_file.items()):
        report += f"### {file} ({len(file_matches)} matches)\n\n"
        for match in file_matches:
            report += f"- Line {match['line']}: `{match['content'][:80]}...`\n"
        report += "\n"
    
    return report

if __name__ == '__main__':
    matches = scan_directory('nmap/')
    report = generate_report(matches)
    
    with open('url-audit-report.md', 'w') as f:
        f.write(report)
    
    print(f"✅ Scan complete: {len(matches)} hardcoded URLs found")
    print(f"📄 Report saved to: url-audit-report.md")
```

**Deliverable:** `scan-hardcoded-urls.py` in `/nmap/Support/tools/`

---

### Step 3: Create Web Interface (Day 2 - 6 hours)

**Task:** Build HTML interface for URL configuration

**Already Created:** ✅ `file-server-config-ui.html` (artifact provided)

**Features:**
- Input BASE_URL
- Input/paste file paths
- Generate File-Server-URLs.md
- Copy output to clipboard
- Statistics (file count, URL count, directory count)
- Visual feedback

**Deployment:**
```bash
# Save artifact to file
/nmap/Support/tools/file-server-config-ui.html

# Test locally
open file-server-config-ui.html

# Deploy to server (optional)
cp file-server-config-ui.html /var/www/html/config/
```

**Deliverable:** Working web interface at `/nmap/Support/tools/file-server-config-ui.html`

---

### Step 4: Update SERVER-CONFIG.md (Day 2 - 2 hours)

**Task:** Enhance SERVER-CONFIG.md with scanning instructions

**Add Section:**

```markdown
## 🔍 SCANNING FOR HARDCODED URLS

### Automated Scan

Run the scanning tool:

```bash
cd /nmap/Support/tools/
python3 scan-hardcoded-urls.py
```

**Output:** `url-audit-report.md` with all hardcoded URL locations

### Manual Verification

**Critical files to check:**
1. All Workflow-*.md files (examples may have hardcoded URLs)
2. All Phase-*.md files (test instructions may reference URLs)
3. Documentation files (guides may show URL examples)
4. Mode context files (SESSION-START, etc.)

**Replace with:**
```markdown
# WRONG
web_fetch("https://claude.dizzybeaver.com/nmap/Support/SESSION-START-Quick-Context.md")

# RIGHT
web_fetch("[BASE_URL]/nmap/Support/SESSION-START-Quick-Context.md")

# OR (for examples)
web_fetch("https://your-domain.com/nmap/Support/SESSION-START-Quick-Context.md")
```

### Maintenance Schedule

**Weekly:** Run `scan-hardcoded-urls.py` to check for regressions  
**Before Release:** Run full scan + manual verification  
**After New Files:** Scan new files before committing
```

**Deliverable:** Updated `SERVER-CONFIG.md` with scanning section

---

### Step 5: Update Documentation Templates (Day 3 - 4 hours)

**Task:** Update all documentation to use generic URL references

**Files to Update:**

**1. Workflow Files (11 files)**
```bash
nmap/Support/Workflow-*.md

# Replace:
web_fetch("https://claude.dizzybeaver.com/...")

# With:
web_fetch("[BASE_URL]/...")
```

**2. Testing Files**
```bash
nmap/Testing/Phase-*.md

# Replace all test examples with generic URLs
```

**3. Documentation Files**
```bash
nmap/Docs/*.md

# Update all URL examples to use [BASE_URL] placeholder
```

**4. Context Files**
```bash
nmap/Context/*.md

# Check SESSION-START and mode files for hardcoded URLs
```

**Replacement Strategy:**

```markdown
## Before (Hardcoded):
web_fetch("https://claude.dizzybeaver.com/src/gateway.py")

## After (Generic):
web_fetch("[BASE_URL]/src/gateway.py")

## Or (Example with note):
# Example (replace BASE_URL with your server):
web_fetch("https://your-domain.com/src/gateway.py")

Note: Set BASE_URL in SERVER-CONFIG.md
```

**Deliverable:** All 30+ documentation files updated with generic URLs

---

### Step 6: Create URL Generation Script (Day 3 - 2 hours)

**Task:** Python script for automated generation

**Script:** `generate-urls.py`

```python
#!/usr/bin/env python3
"""
Generate File-Server-URLs.md from SERVER-CONFIG.md
"""

import re
from datetime import datetime
from urllib.parse import quote

def load_server_config(config_file='SERVER-CONFIG.md'):
    """Load BASE_URL and file paths from config"""
    with open(config_file, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Extract BASE_URL
    base_url_match = re.search(r'BASE_URL:\s*(.+)', content)
    if not base_url_match:
        raise ValueError("BASE_URL not found in SERVER-CONFIG.md")
    base_url = base_url_match.group(1).strip()
    
    # Extract file paths
    paths_section = re.search(r'## FILE PATHS.*?```(.+?)```', content, re.DOTALL)
    if not paths_section:
        raise ValueError("FILE PATHS section not found")
    
    paths = [line.strip() for line in paths_section.group(1).split('\n') 
             if line.strip() and not line.startswith('#')]
    
    return base_url, paths

def generate_urls(base_url, paths):
    """Generate complete URLs from paths"""
    # Clean base URL (remove trailing slash)
    base_url = base_url.rstrip('/')
    
    # Group by directory
    grouped = {}
    for path in paths:
        dir_name = path.rsplit('/', 1)[0] if '/' in path else 'root'
        if dir_name not in grouped:
            grouped[dir_name] = []
        grouped[dir_name].append(path)
    
    # Generate output
    output = []
    output.append("# SUGA-ISP Lambda - File Server URLs\n")
    output.append(f"**Generated:** {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}\n")
    output.append(f"**Base URL:** {base_url}\n")
    output.append(f"**Total Files:** {len(paths)}\n")
    output.append(f"**Purpose:** URL inventory for web_fetch access\n")
    output.append("\n---\n\n")
    
    # Add grouped URLs
    for directory in sorted(grouped.keys()):
        output.append(f"## 📂 {directory} ({len(grouped[directory])} files)\n\n")
        for path in sorted(grouped[directory]):
            # Encode spaces as %20
            encoded_path = quote(path, safe='/:')
            output.append(f"{base_url}/{encoded_path}\n")
        output.append("\n")
    
    output.append("---\n\n")
    output.append("**END OF FILE SERVER URLS**\n")
    
    return ''.join(output)

def main():
    try:
        # Load configuration
        print("📂 Loading SERVER-CONFIG.md...")
        base_url, paths = load_server_config()
        print(f"✅ Found BASE_URL: {base_url}")
        print(f"✅ Found {len(paths)} file paths")
        
        # Generate URLs
        print("⚡ Generating File-Server-URLs.md...")
        output = generate_urls(base_url, paths)
        
        # Write output
        with open('File-Server-URLs.md', 'w', encoding='utf-8') as f:
            f.write(output)
        
        print(f"✅ Generated File-Server-URLs.md ({len(output)} chars)")
        print("📄 File saved to: File-Server-URLs.md")
        
    except Exception as e:
        print(f"❌ Error: {e}")
        return 1
    
    return 0

if __name__ == '__main__':
    exit(main())
```

**Usage:**
```bash
cd /nmap/Support/
python3 generate-urls.py
```

**Deliverable:** `generate-urls.py` in `/nmap/Support/tools/`

---

### Step 7: Update URL-GENERATOR-Template.md (Day 4 - 2 hours)

**Task:** Add Python script option to URL-GENERATOR-Template.md

**Add Section:**

```markdown
### Method 3: Python Script (Automated)

**Step 1: Install Script**
```bash
cd /nmap/Support/tools/
# Script already provided: generate-urls.py
```

**Step 2: Run Script**
```bash
python3 generate-urls.py
```

**Step 3: Output**
```
✅ Generated File-Server-URLs.md (12,543 chars)
📄 File saved to: File-Server-URLs.md
```

**Step 4: Verify**
```bash
# Check file count
wc -l File-Server-URLs.md

# Test sample URLs
curl -I [first URL from file]
```

**Advantages:**
- ✅ Fastest method (< 5 seconds)
- ✅ Automated (run in CI/CD)
- ✅ No manual errors
- ✅ Consistent formatting
```

**Deliverable:** Updated `URL-GENERATOR-Template.md`

---

### Step 8: Testing & Validation (Day 4-5 - 8 hours)

**Test Suite:**

**Test 1: Web Interface**
```
1. Open file-server-config-ui.html
2. Enter BASE_URL: https://test.example.com
3. Paste sample paths
4. Click Generate
5. Verify output format
6. Test copy to clipboard
7. Verify statistics correct
```

**Test 2: Python Script**
```
1. Update SERVER-CONFIG.md with test URL
2. Run: python3 generate-urls.py
3. Verify File-Server-URLs.md created
4. Check BASE_URL in output
5. Verify all paths converted
6. Check space encoding (%20)
7. Count URLs matches expected
```

**Test 3: Hardcoded URL Scan**
```
1. Run: python3 scan-hardcoded-urls.py
2. Verify scan completes
3. Check url-audit-report.md
4. Verify zero matches (after updates)
5. Test with intentionally added hardcoded URL
6. Verify scanner catches it
```

**Test 4: Documentation Updates**
```
1. Pick 5 random updated files
2. Verify [BASE_URL] placeholders present
3. Verify no hardcoded URLs
4. Check examples use generic format
5. Verify notes explain configuration
```

**Test 5: End-to-End**
```
1. Change BASE_URL in SERVER-CONFIG.md
2. Run generate-urls.py
3. Upload File-Server-URLs.md to Claude
4. Test web_fetch with generated URLs
5. Verify files accessible
6. Test with different BASE_URLs
```

**Deliverable:** `Phase-0-Test-Report.md` with all test results

---

## 📊 DELIVERABLES CHECKLIST

### Code Artifacts

- [ ] `scan-hardcoded-urls.py` - URL scanner tool
- [ ] `generate-urls.py` - URL generator script
- [ ] `file-server-config-ui.html` - Web interface

### Documentation Updates

- [ ] `SERVER-CONFIG.md` - Added scanning section
- [ ] `URL-GENERATOR-Template.md` - Added Python script method
- [ ] All 11 Workflow-*.md files - Generic URLs
- [ ] All Phase-*.md files - Generic URLs
- [ ] All Docs/*.md files - Generic URLs
- [ ] All Context/*.md files - Checked and updated

### Reports

- [ ] `url-audit-report.md` - Initial scan results
- [ ] `Phase-0-Test-Report.md` - Testing results
- [ ] `Phase-0-Completion-Report.md` - Final status

### Deployment

- [ ] Tools deployed to `/nmap/Support/tools/`
- [ ] Web interface tested and accessible
- [ ] Scripts executable and tested
- [ ] Documentation committed to repository

---

## 🎯 SUCCESS VALIDATION

### Automated Checks

```bash
# Check 1: No hardcoded URLs
python3 scan-hardcoded-urls.py
# Expected: 0 matches (except File-Server-URLs.md itself)

# Check 2: Generation works
python3 generate-urls.py
# Expected: File-Server-URLs.md created successfully

# Check 3: File count matches
wc -l File-Server-URLs.md
# Expected: ~300 lines (matches FILE PATHS count)
```

### Manual Validation

- ✅ Web interface generates valid output
- ✅ Python script runs without errors
- ✅ Scanner finds no hardcoded URLs (except output file)
- ✅ All documentation uses [BASE_URL] placeholders
- ✅ SERVER-CONFIG.md has scanning instructions
- ✅ URL-GENERATOR-Template.md has all 3 methods

---

## 🚨 COMMON ISSUES & SOLUTIONS

### Issue 1: Scanner False Positives

**Problem:** Scanner flags legitimate URLs in examples  
**Solution:** Add comment above example: `# Example URL (not hardcoded configuration)`

### Issue 2: Spaces Not Encoded

**Problem:** URLs with spaces fail  
**Solution:** Use `quote()` function in Python script, check %20 encoding

### Issue 3: BASE_URL Trailing Slash

**Problem:** Double slashes in generated URLs  
**Solution:** Script removes trailing slash: `base_url.rstrip('/')`

### Issue 4: Large File Count Slows Generation

**Problem:** 500+ files takes too long  
**Solution:** Keep File-Server-URLs.md under 300 lines, group efficiently

---

## 📞 PHASE 0 SUPPORT

**Questions?**
- Check URL-GENERATOR-Template.md
- Check SERVER-CONFIG.md scanning section
- Review test report examples

**Blockers?**
- Report to phase lead immediately
- Document in Phase-0-Issues.md
- Escalate if blocking Phase 1

---

## ➡️ NEXT PHASE

**After Phase 0 Completion:**

1. Review Phase-0-Completion-Report.md
2. Verify all deliverables met
3. Commit all changes to repository
4. Announce completion to team
5. **Proceed to Phase 1: Categorization**

**Phase 1 Dependency:**
- Requires generic file server configuration ✅
- Requires File-Server-URLs.md generation working ✅
- Requires all tools in place ✅

---

**END OF PHASE 0 PLAN**

**Version:** 1.0.0  
**Duration:** 1 week (5 days)  
**Estimated Hours:** 28 hours  
**Priority:** P0 (Must complete before Phase 1)  
**Status:** 📋 Ready for implementation

**Next Action:** Begin Day 1 - URL Audit
