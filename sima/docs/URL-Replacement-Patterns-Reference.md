# URL Replacement Patterns Reference

**Version:** 1.0.0  
**Date:** 2025-10-28  
**Purpose:** Complete guide for replacing hardcoded URLs across all file types  
**Scope:** All documentation, testing, workflow, and neural map files

---

## ðŸŽ¯ OVERVIEW

This reference provides standardized patterns for replacing hardcoded file server URLs with generic `[BASE_URL]` placeholders.

**Goal:** Enable flexible server configuration without editing hundreds of files.

---

## ðŸ"‹ FILE TYPES COVERED

### High Priority (Must Update)
1. Workflow files (*.md in /nmap/Docs/)
2. Context files (SESSION-START, MODE-SELECTOR, etc.)
3. Testing files (Phase-*.md)
4. Support documentation (guides, checklists)

### Medium Priority (Should Update)
5. Neural map files (examples sections)
6. Implementation plans (SIMAv4-Phase-*.md)
7. Deployment guides

### Low Priority (Optional)
8. Historical documents
9. Archive files
10. Internal notes

---

## 🔧 REPLACEMENT PATTERNS BY CONTEXT

### Pattern 1: Python Code Blocks

**Use Case:** Code examples showing web_fetch usage

**BEFORE:**
````markdown
```python
web_fetch("https://claude.dizzybeaver.com/src/gateway.py")
```
````

**AFTER:**
````markdown
```python
# Configure BASE_URL in SERVER-CONFIG.md
web_fetch("[BASE_URL]/src/gateway.py")
```
````

**WITH SETUP INSTRUCTIONS:**
````markdown
```python
# Setup: Upload File-Server-URLs.md to session first
web_fetch("[BASE_URL]/src/gateway.py")
```

**Configuration Required:**
1. Edit SERVER-CONFIG.md with your server URL
2. Run: `python3 generate-urls.py`
3. Upload File-Server-URLs.md to Claude session
````

---

### Pattern 2: Inline Code References

**Use Case:** References within paragraphs

**BEFORE:**
```markdown
To fetch the gateway, use `web_fetch("https://claude.dizzybeaver.com/src/gateway.py")`.
```

**AFTER:**
```markdown
To fetch the gateway, use `web_fetch("[BASE_URL]/src/gateway.py")` (configure BASE_URL in SERVER-CONFIG.md).
```

**OR (Simpler):**
```markdown
To fetch the gateway, use `web_fetch("[BASE_URL]/src/gateway.py")`.

*Note: Configure BASE_URL in SERVER-CONFIG.md and upload File-Server-URLs.md.*
```

---

### Pattern 3: Markdown Links

**Use Case:** Documentation hyperlinks

**BEFORE:**
```markdown
See [Quick Index](https://claude.dizzybeaver.com/nmap/NM00/NM00-Quick_Index.md) for details.
```

**AFTER (Non-clickable):**
```markdown
See Quick Index at [BASE_URL]/nmap/NM00/NM00-Quick_Index.md for details.
```

**OR (With Instruction):**
```markdown
See Quick Index (fetch with `web_fetch("[BASE_URL]/nmap/NM00/NM00-Quick_Index.md")`).

*Configure BASE_URL in SERVER-CONFIG.md*
```

---

### Pattern 4: Bullet Lists

**Use Case:** Lists of file references

**BEFORE:**
```markdown
Key files:
- Gateway: https://claude.dizzybeaver.com/src/gateway.py
- Interface: https://claude.dizzybeaver.com/src/interface_http.py
- Core: https://claude.dizzybeaver.com/src/http_client_core.py
```

**AFTER:**
```markdown
Key files (configure BASE_URL in SERVER-CONFIG.md):
- Gateway: [BASE_URL]/src/gateway.py
- Interface: [BASE_URL]/src/interface_http.py
- Core: [BASE_URL]/src/http_client_core.py
```

---

### Pattern 5: Testing Instructions

**Use Case:** Test cases with URL validation

**BEFORE:**
```markdown
### Test 1: Fetch Gateway

**Steps:**
1. Run: `web_fetch("https://claude.dizzybeaver.com/src/gateway.py")`
2. Verify: File content returned
3. Check: No errors

**Expected:** File fetched successfully
```

**AFTER:**
```markdown
### Test 1: Fetch Gateway

**Prerequisites:**
- SERVER-CONFIG.md configured with BASE_URL
- File-Server-URLs.md uploaded to session

**Steps:**
1. Run: `web_fetch("[BASE_URL]/src/gateway.py")`
2. Verify: File content returned
3. Check: No errors

**Expected:** File fetched successfully

**Note:** Replace [BASE_URL] with your actual configured URL.
```

---

### Pattern 6: Multi-File Fetches

**Use Case:** Fetching multiple related files

**BEFORE:**
```python
# Fetch HTTP client stack
files = [
    "https://claude.dizzybeaver.com/src/gateway.py",
    "https://claude.dizzybeaver.com/src/interface_http.py",
    "https://claude.dizzybeaver.com/src/http_client_core.py",
    "https://claude.dizzybeaver.com/src/http_client_state.py"
]

for url in files:
    web_fetch(url)
```

**AFTER:**
```python
# Fetch HTTP client stack
# Configure BASE_URL in SERVER-CONFIG.md and upload File-Server-URLs.md
BASE_URL = "[BASE_URL]"  # Set to your server

files = [
    f"{BASE_URL}/src/gateway.py",
    f"{BASE_URL}/src/interface_http.py",
    f"{BASE_URL}/src/http_client_core.py",
    f"{BASE_URL}/src/http_client_state.py"
]

for url in files:
    web_fetch(url)
```

---

### Pattern 7: Example Sections

**Use Case:** Tutorial examples showing real usage

**BEFORE:**
```markdown
## Example: Fetching Architecture Docs

```python
# Fetch SUGA architecture overview
web_fetch("https://claude.dizzybeaver.com/nmap/NM01/NM01-SUGA_Architecture_Overview.md")
```

This returns the complete architecture documentation.
```

**AFTER:**
```markdown
## Example: Fetching Architecture Docs

**Setup:**
1. Configure SERVER-CONFIG.md with your BASE_URL
2. Generate File-Server-URLs.md: `python3 generate-urls.py`
3. Upload File-Server-URLs.md to session

**Code:**
```python
# Fetch SUGA architecture overview
web_fetch("[BASE_URL]/nmap/NM01/NM01-SUGA_Architecture_Overview.md")
```

This returns the complete architecture documentation.

**Note:** The example URL [BASE_URL] will be replaced with your actual configured server URL.
```

---

### Pattern 8: Comment Markers for Examples

**Use Case:** Distinguish examples from actual config

**AFTER:**
```markdown
```python
# Example URL (not actual configuration - configure in SERVER-CONFIG.md)
web_fetch("https://example.com/src/gateway.py")

# Or use configured BASE_URL:
web_fetch("[BASE_URL]/src/gateway.py")
```

**Note:** The scanner tool ignores lines with "# Example URL" comment.
```

---

## ðŸ"„ FILE-SPECIFIC PATTERNS

### Workflow Files (Workflow-*.md)

**Standard Template:**

````markdown
# Workflow-XX: [Title]

---

## âš™ï¸ CONFIGURATION REQUIRED

Before using this workflow:

1. **Configure Server:**
   - Edit `SERVER-CONFIG.md`
   - Set `BASE_URL: https://your-server.com`

2. **Generate URLs:**
   ```bash
   python3 generate-urls.py
   ```

3. **Upload to Session:**
   - Upload `File-Server-URLs.md`

**See:** SERVER-CONFIG.md for full setup instructions.

---

## Step 1: [Title]

```python
# All URLs use [BASE_URL] placeholder
web_fetch("[BASE_URL]/src/gateway.py")
```
````

---

### Context Files (SESSION-START-*.md)

**Pattern:**

```markdown
## File Access

To access project files:

1. **Upload File-Server-URLs.md** to session
2. **Use web_fetch** with URLs from that file
3. **URLs use BASE_URL** configured in SERVER-CONFIG.md

**Example:**
```python
web_fetch("[BASE_URL]/nmap/Support/SESSION-START-Quick-Context.md")
```

**Setup:** See SERVER-CONFIG.md and URL-GENERATOR-Template.md
```

---

### Testing Files (Phase-*-Tests.md)

**Pattern:**

```markdown
## Test: URL Configuration

**Prerequisites:**
- [ ] SERVER-CONFIG.md configured
- [ ] File-Server-URLs.md generated
- [ ] File-Server-URLs.md uploaded to session

**Test Steps:**
1. Configure BASE_URL in SERVER-CONFIG.md:
   ```
   BASE_URL: https://test-server.com
   ```

2. Generate URLs:
   ```bash
   python3 generate-urls.py
   ```

3. Test fetch:
   ```python
   web_fetch("[BASE_URL]/src/gateway.py")
   ```

**Expected:**
- âœ… File fetched successfully
- âœ… URL uses configured BASE_URL
- âœ… No hardcoded references
```

---

### Neural Maps (NM*.md)

**Pattern (for examples only):**

```markdown
## Example Usage

**Note:** This is a generic example. Configure BASE_URL in SERVER-CONFIG.md for your deployment.

```python
# Example (replace [BASE_URL] with your server)
web_fetch("[BASE_URL]/src/interface_http.py")
```

See SERVER-CONFIG.md for configuration details.
```

---

### Implementation Plans (SIMAv4-Phase-*.md)

**Pattern:**

```markdown
## Deliverables

- [ ] Updated SERVER-CONFIG.md with BASE_URL
- [ ] Generated File-Server-URLs.md
- [ ] All files use [BASE_URL] placeholders
- [ ] Zero hardcoded URLs (verified with scanner)

## Testing

Test URL generation:
```bash
# Update SERVER-CONFIG.md
vim SERVER-CONFIG.md
# Set BASE_URL: https://your-server.com

# Generate
python3 generate-urls.py

# Verify
cat File-Server-URLs.md | head -20
```
```

---

## âœ… VALIDATION PATTERNS

### Pattern: Pre-commit Validation

Add to top of file:

```markdown
---

**URL Configuration Status:**
- [ ] All hardcoded URLs replaced with [BASE_URL]
- [ ] Configuration notes added
- [ ] Passed URL scanner (scan-hardcoded-urls.py)
- [ ] Tested with custom BASE_URL

**Last Scan:** [Date]
**Scanner Result:** [Pass/Fail]

---
```

---

### Pattern: Documentation Header

```markdown
---

## ðŸŒ Server Configuration

This document uses generic `[BASE_URL]` placeholders for file server URLs.

**To use:**
1. Configure `SERVER-CONFIG.md` with your server URL
2. Generate `File-Server-URLs.md`
3. Upload to Claude session
4. Replace `[BASE_URL]` in examples with your URL

**See:** SERVER-CONFIG.md and URL-GENERATOR-Template.md

---
```

---

## ðŸš¨ SPECIAL CASES

### Case 1: GitHub Raw URLs

**When:** Documentation hosted on GitHub

**Pattern:**
```markdown
BASE_URL: https://github.com/username/repo/raw/main

# File becomes:
https://github.com/username/repo/raw/main/src/gateway.py
```

**Note:** GitHub rate limits may apply

---

### Case 2: CDN URLs

**When:** Using content delivery network

**Pattern:**
```markdown
BASE_URL: https://cdn.example.com/project-name

# File becomes:
https://cdn.example.com/project-name/src/gateway.py
```

**Note:** Ensure CDN caching configured properly

---

### Case 3: Localhost Development

**When:** Local testing

**Pattern:**
```markdown
BASE_URL: http://localhost:8000

# File becomes:
http://localhost:8000/src/gateway.py
```

**Setup:**
```bash
# Start local file server
cd /path/to/project
python3 -m http.server 8000
```

---

### Case 4: Multiple Environments

**When:** Dev/staging/prod deployments

**Pattern:**
```markdown
# Development
BASE_URL: http://localhost:8000

# Staging
BASE_URL: https://staging.example.com

# Production
BASE_URL: https://prod.example.com

# Generate separate URL files:
python3 generate-urls.py --config SERVER-CONFIG-dev.md --output URLs-dev.md
python3 generate-urls.py --config SERVER-CONFIG-staging.md --output URLs-staging.md
python3 generate-urls.py --config SERVER-CONFIG-prod.md --output URLs-prod.md
```

---

## ðŸ"Š REPLACEMENT CHECKLIST

For each file type:

### Workflow Files
- [ ] All web_fetch calls use [BASE_URL]
- [ ] Configuration section added at top
- [ ] Examples include setup instructions
- [ ] No hardcoded URLs remain
- [ ] Passed scanner validation

### Context Files
- [ ] File access section uses [BASE_URL]
- [ ] Upload instructions included
- [ ] Examples genericized
- [ ] Passed scanner validation

### Testing Files
- [ ] Test prerequisites mention configuration
- [ ] Test steps use [BASE_URL]
- [ ] Expected results mention configuration
- [ ] Passed scanner validation

### Neural Maps
- [ ] Examples use [BASE_URL]
- [ ] Notes explain configuration
- [ ] No hardcoded URLs in examples
- [ ] Passed scanner validation

### Implementation Plans
- [ ] Deliverables mention URL configuration
- [ ] Testing includes URL generation
- [ ] All references use [BASE_URL]
- [ ] Passed scanner validation

---

## ðŸ"§ AUTOMATION TOOLS

### Tool 1: Batch Replace Script

```bash
#!/bin/bash
# batch-replace-urls.sh

# Replace hardcoded URLs with [BASE_URL] in all .md files

DOMAIN="claude.dizzybeaver.com"
REPLACEMENT="[BASE_URL]"

find nmap/ -name "*.md" -type f | while read file; do
    if [[ "$file" != *"File-Server-URLs.md"* ]]; then
        sed -i "s|https://${DOMAIN}|${REPLACEMENT}|g" "$file"
        echo "Updated: $file"
    fi
done

echo "âœ… Batch replacement complete"
echo "Run scan-hardcoded-urls.py to verify"
```

**Usage:**
```bash
chmod +x batch-replace-urls.sh
./batch-replace-urls.sh
```

---

### Tool 2: Validation Script

```bash
#!/bin/bash
# validate-url-replacement.sh

# Check if all URLs replaced successfully

echo "Scanning for remaining hardcoded URLs..."
python3 scan-hardcoded-urls.py

VIOLATIONS=$(grep -c "hardcoded URL found" url-audit-report.md)

if [ $VIOLATIONS -eq 0 ]; then
    echo "âœ… No hardcoded URLs found (except File-Server-URLs.md)"
    exit 0
else
    echo "âŒ Found $VIOLATIONS hardcoded URLs"
    echo "Review url-audit-report.md for details"
    exit 1
fi
```

**Usage:**
```bash
chmod +x validate-url-replacement.sh
./validate-url-replacement.sh
```

---

## ðŸ"š QUICK REFERENCE

### Common Replacements

| Original | Replacement | Context |
|----------|-------------|---------|
| `https://claude.dizzybeaver.com/src/gateway.py` | `[BASE_URL]/src/gateway.py` | Code examples |
| `web_fetch("https://claude.dizzybeaver.com/...")` | `web_fetch("[BASE_URL]/...")` | Python code |
| `https://claude.dizzybeaver.com/nmap/NM00/...` | `[BASE_URL]/nmap/NM00/...` | Documentation links |
| Direct URL in text | `[BASE_URL]/path` + config note | Inline references |

### Must Include

âœ… Configuration note in every file  
âœ… Setup instructions in examples  
âœ… Scanner validation passed  
âœ… No hardcoded URLs (except output files)

---

## ðŸ"„ VERSION HISTORY

**v1.0.0 (2025-10-28):** Initial comprehensive reference for all file types

---

**END OF URL REPLACEMENT PATTERNS REFERENCE**
