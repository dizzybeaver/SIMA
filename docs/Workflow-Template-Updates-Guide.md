# Workflow Template Updates Guide

**Version:** 1.0.0  
**Date:** 2025-10-28  
**Purpose:** Standard patterns for using [BASE_URL] in workflow files  
**Scope:** All Workflow-*.md files in /nmap/Docs/

---

## ðŸŽ¯ OVERVIEW

This guide shows how to update workflow files to use generic `[BASE_URL]` placeholders instead of hardcoded URLs.

**Goal:** Enable users to configure their own file server URL without editing multiple workflow files.

---

## ðŸ"‹ AFFECTED FILES

All 11 workflow files need updating:

```
nmap/Docs/Workflow-01-AddFeature.md
nmap/Docs/Workflow-02-ModifyExisting.md
nmap/Docs/Workflow-03-Debug.md
nmap/Docs/Workflow-04-Refactor.md
nmap/Docs/Workflow-05-Documentation.md
nmap/Docs/Workflow-06-Testing.md
nmap/Docs/Workflow-07-Deployment.md
nmap/Docs/Workflow-08-Monitoring.md
nmap/Docs/Workflow-09-Optimization.md
nmap/Docs/Workflow-10-Learning.md
nmap/Docs/Workflow-11-Architecture-Questions.md
```

---

## ðŸ"§ REPLACEMENT PATTERNS

### Pattern 1: web_fetch() Calls

**BEFORE (Hardcoded):**
```python
web_fetch("https://claude.dizzybeaver.com/src/gateway.py")
```

**AFTER (Generic):**
```python
web_fetch("[BASE_URL]/src/gateway.py")
```

**With Configuration Note:**
```python
# Configure BASE_URL in SERVER-CONFIG.md, then generate File-Server-URLs.md
web_fetch("[BASE_URL]/src/gateway.py")
```

---

### Pattern 2: Example Code Blocks

**BEFORE (Hardcoded):**
````markdown
```python
# Fetch current code
web_fetch("https://claude.dizzybeaver.com/src/gateway.py")
web_fetch("https://claude.dizzybeaver.com/src/interface_http.py")
```
````

**AFTER (Generic with Note):**
````markdown
```python
# Fetch current code (replace [BASE_URL] with your configured server)
web_fetch("[BASE_URL]/src/gateway.py")
web_fetch("[BASE_URL]/src/interface_http.py")
```

**Note:** Configure BASE_URL in SERVER-CONFIG.md before use.
````

---

### Pattern 3: Step-by-Step Instructions

**BEFORE (Hardcoded):**
```markdown
1. Fetch the current gateway code:
   ```
   web_fetch("https://claude.dizzybeaver.com/src/gateway.py")
   ```

2. Review the code
```

**AFTER (Generic):**
```markdown
1. Fetch the current gateway code:
   ```
   web_fetch("[BASE_URL]/src/gateway.py")
   ```
   *(Configure BASE_URL in SERVER-CONFIG.md)*

2. Review the code
```

---

### Pattern 4: Multiple File Fetches

**BEFORE (Hardcoded):**
```python
# Fetch all related files
files = [
    "https://claude.dizzybeaver.com/src/gateway.py",
    "https://claude.dizzybeaver.com/src/interface_http.py",
    "https://claude.dizzybeaver.com/src/http_client_core.py"
]

for url in files:
    web_fetch(url)
```

**AFTER (Generic):**
```python
# Fetch all related files (configure BASE_URL in SERVER-CONFIG.md)
BASE_URL = "[BASE_URL]"  # Set in File-Server-URLs.md

files = [
    f"{BASE_URL}/src/gateway.py",
    f"{BASE_URL}/src/interface_http.py",
    f"{BASE_URL}/src/http_client_core.py"
]

for url in files:
    web_fetch(url)
```

---

### Pattern 5: Documentation References

**BEFORE (Hardcoded):**
```markdown
For more information, see the architecture guide:
https://claude.dizzybeaver.com/nmap/NM01/NM01-SUGA_Architecture_Overview.md
```

**AFTER (Generic):**
```markdown
For more information, see the architecture guide:
[BASE_URL]/nmap/NM01/NM01-SUGA_Architecture_Overview.md

*(Upload File-Server-URLs.md to access documentation)*
```

---

### Pattern 6: Testing Examples

**BEFORE (Hardcoded):**
```markdown
Test by fetching:
- Gateway: https://claude.dizzybeaver.com/src/gateway.py
- Interface: https://claude.dizzybeaver.com/src/interface_http.py
- Core: https://claude.dizzybeaver.com/src/http_client_core.py
```

**AFTER (Generic):**
```markdown
Test by fetching (use your configured BASE_URL):
- Gateway: [BASE_URL]/src/gateway.py
- Interface: [BASE_URL]/src/interface_http.py
- Core: [BASE_URL]/src/http_client_core.py

*Configure BASE_URL in SERVER-CONFIG.md and generate File-Server-URLs.md*
```

---

## ðŸ"„ WORKFLOW-SPECIFIC UPDATES

### Workflow-01-AddFeature.md

**Section to Update:** "Step 1: Fetch Current Code"

**Original:**
```markdown
### Step 1: Fetch Current Code

```python
web_fetch("https://claude.dizzybeaver.com/src/gateway.py")
```
```

**Updated:**
```markdown
### Step 1: Fetch Current Code

```python
# Configure BASE_URL in SERVER-CONFIG.md first
web_fetch("[BASE_URL]/src/gateway.py")
```

**Setup Required:**
1. Edit SERVER-CONFIG.md with your server URL
2. Run: `python3 generate-urls.py`
3. Upload File-Server-URLs.md to this session
```

---

### Workflow-03-Debug.md

**Section to Update:** "Fetch Relevant Files"

**Original:**
```markdown
Fetch files related to the error:

```python
web_fetch("https://claude.dizzybeaver.com/src/gateway.py")
web_fetch("https://claude.dizzybeaver.com/src/interface_config.py")
web_fetch("https://claude.dizzybeaver.com/src/config_core.py")
```
```

**Updated:**
```markdown
Fetch files related to the error:

```python
# Replace [BASE_URL] with your configured server
web_fetch("[BASE_URL]/src/gateway.py")
web_fetch("[BASE_URL]/src/interface_config.py")
web_fetch("[BASE_URL]/src/config_core.py")
```

**Note:** Ensure File-Server-URLs.md is uploaded to access these files.
```

---

### Workflow-11-Architecture-Questions.md

**Section to Update:** "Quick Reference Access"

**Original:**
```markdown
Access quick references:

- Quick Index: https://claude.dizzybeaver.com/nmap/NM00/NM00-Quick_Index.md
- Top 10 Answers: https://claude.dizzybeaver.com/nmap/NM00/NM00B-TOP-10-Instant_Answers.md
- Workflow Router: https://claude.dizzybeaver.com/nmap/NM00/NM00C-Workflow_Router.md
```

**Updated:**
```markdown
Access quick references (configure BASE_URL in SERVER-CONFIG.md):

- Quick Index: [BASE_URL]/nmap/NM00/NM00-Quick_Index.md
- Top 10 Answers: [BASE_URL]/nmap/NM00/NM00B-TOP-10-Instant_Answers.md
- Workflow Router: [BASE_URL]/nmap/NM00/NM00C-Workflow_Router.md

**To use:** Upload File-Server-URLs.md to enable web_fetch access.
```

---

## ðŸ"Š STANDARD CONFIGURATION NOTE

Add this note at the beginning of each workflow file:

```markdown
---

## âš™ï¸ CONFIGURATION REQUIRED

**Before using this workflow, configure file server access:**

1. Edit `SERVER-CONFIG.md`:
   - Set BASE_URL to your server
   - Verify file paths are current

2. Generate URLs:
   ```bash
   python3 generate-urls.py
   ```

3. Upload to Claude session:
   - `File-Server-URLs.md`

4. Replace `[BASE_URL]` in examples:
   - Use your actual configured URL
   - Or let Claude read from File-Server-URLs.md

**See:** SERVER-CONFIG.md and URL-GENERATOR-Template.md for details.

---
```

---

## âœ… VALIDATION CHECKLIST

For each workflow file:

- [ ] All hardcoded URLs replaced with `[BASE_URL]`
- [ ] Configuration note added at top
- [ ] Examples include setup instructions
- [ ] web_fetch calls use generic placeholders
- [ ] Documentation references use placeholders
- [ ] Testing examples use placeholders
- [ ] No private server URLs remain
- [ ] File passes URL scan (scan-hardcoded-urls.py)

---

## ðŸ"§ IMPLEMENTATION STEPS

### Step 1: Backup Original Files (5 minutes)

```bash
cd nmap/Docs/
mkdir backup-originals
cp Workflow-*.md backup-originals/
```

### Step 2: Update Each Workflow (30 minutes)

For each of 11 workflow files:

1. Open file in editor
2. Search for "claude.dizzybeaver.com"
3. Replace with "[BASE_URL]"
4. Add configuration note at top
5. Add setup instructions to examples
6. Save changes

### Step 3: Validate Updates (10 minutes)

```bash
# Run URL scanner
cd /nmap/Support/tools/
python3 scan-hardcoded-urls.py

# Check workflow files in report
grep "Workflow-" url-audit-report.md

# Expected: No matches (all URLs genericized)
```

### Step 4: Test One Workflow (10 minutes)

1. Start new Claude session
2. Upload File-Server-URLs.md
3. Follow one workflow (e.g., Workflow-01)
4. Verify all web_fetch calls work
5. Confirm instructions are clear

### Step 5: Commit Changes (5 minutes)

```bash
git add nmap/Docs/Workflow-*.md
git commit -m "Genericize URLs in workflow files

- Replace hardcoded URLs with [BASE_URL] placeholders
- Add configuration notes to each workflow
- Update examples with setup instructions
- Enable custom server configuration"

git push origin main
```

---

## ðŸ" EXAMPLE: Complete Updated Workflow

**Workflow-01-AddFeature.md (Excerpt)**

```markdown
# Workflow-01: Add New Feature

**Version:** 2.0.0  
**Date:** 2025-10-28  
**Purpose:** Systematic process for adding new features to SUGA-ISP

---

## âš™ï¸ CONFIGURATION REQUIRED

**Before using this workflow, configure file server access:**

1. Edit `SERVER-CONFIG.md`:
   - Set BASE_URL to your server
   - Verify file paths are current

2. Generate URLs:
   ```bash
   python3 generate-urls.py
   ```

3. Upload to Claude session:
   - `File-Server-URLs.md`

**See:** SERVER-CONFIG.md and URL-GENERATOR-Template.md for details.

---

## ðŸŽ¯ WORKFLOW STEPS

### Step 1: Fetch Current Code

Fetch relevant files based on feature location:

```python
# Configure BASE_URL in SERVER-CONFIG.md first
web_fetch("[BASE_URL]/src/gateway.py")
web_fetch("[BASE_URL]/src/interface_http.py")
web_fetch("[BASE_URL]/src/http_client_core.py")
```

**Note:** Replace `[BASE_URL]` with your configured server URL, or let Claude read from uploaded File-Server-URLs.md.

### Step 2: Review Architecture

Consult architecture documentation:

```python
# Access neural maps (ensure File-Server-URLs.md uploaded)
web_fetch("[BASE_URL]/nmap/NM01/NM01-SUGA_Architecture_Overview.md")
web_fetch("[BASE_URL]/nmap/NM02/NM02-Implementation_Patterns.md")
```

### Step 3: Plan Implementation

... (rest of workflow)
```

---

## ðŸš¨ COMMON ISSUES

### Issue 1: Forgot to Replace URLs

**Symptom:** Workflow still has hardcoded URLs

**Solution:**
```bash
# Search for remaining hardcoded URLs
grep -r "claude.dizzybeaver.com" nmap/Docs/Workflow-*.md

# Replace each occurrence with [BASE_URL]
```

### Issue 2: Missing Configuration Note

**Symptom:** Users confused about [BASE_URL]

**Solution:**
```
Add configuration section at top of file:
## âš™ï¸ CONFIGURATION REQUIRED
(see standard note template above)
```

### Issue 3: Examples Don't Work

**Symptom:** Users can't fetch files

**Solution:**
```
Ensure instructions include:
1. Configure SERVER-CONFIG.md
2. Generate File-Server-URLs.md
3. Upload to session
4. Then use workflows
```

---

## ðŸ"š RELATED DOCUMENTATION

- SERVER-CONFIG.md - Base URL configuration
- URL-GENERATOR-Template.md - URL generation methods
- scan-hardcoded-urls.py - Validation scanner
- Phase-0-File-Server-Config.md - Complete implementation plan

---

## ðŸ"„ VERSION HISTORY

**v1.0.0 (2025-10-28):** Initial version with [BASE_URL] patterns and configuration notes

---

**END OF WORKFLOW TEMPLATE UPDATES GUIDE**
