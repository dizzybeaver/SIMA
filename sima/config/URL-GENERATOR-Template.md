# URL-GENERATOR-Template.md

**Version:** 2.0.0  
**Date:** 2025-10-28  
**Purpose:** Generate File-Server-URLs.md from SERVER-CONFIG.md  
**Usage:** Process this template to create complete URL file

---

## ðŸŽ¯ WHAT THIS IS

This is a **template and instructions** for generating `File-Server-URLs.md` from `SERVER-CONFIG.md`.

**Inputs:**
- SERVER-CONFIG.md (base URL + file paths)

**Outputs:**
- File-Server-URLs.md (complete URLs for web_fetch)

---

## ðŸ"§ GENERATION METHODS

### Method 1: Manual Generation (Simple)

**Step 1: Open SERVER-CONFIG.md**
```
Copy BASE_URL value
Example: https://claude.dizzybeaver.com
```

**Step 2: For Each File Path**
```
Concatenate: BASE_URL + "/" + filepath
Example:
  BASE_URL: https://claude.dizzybeaver.com
  Path: src/gateway.py
  Result: https://claude.dizzybeaver.com/src/gateway.py
```

**Step 3: Handle Spaces in Filenames**
```
Replace spaces with %20
Example:
  "File Server URLs.md" → "File%20Server%20URLs.md"
  "ANTI-PATTERNS CHECKLIST.md" → "ANTI-PATTERNS%20CHECKLIST.md"
```

**Step 4: Build File-Server-URLs.md**
```
Copy template structure (see below)
Insert generated URLs
Save as File-Server-URLs.md
```

---

### Method 2: Claude-Assisted Generation

**Step 1: Upload FILES to Claude**
```
1. SERVER-CONFIG.md
2. URL-GENERATOR-Template.md
```

**Step 2: Request Generation**
```
Say to Claude:
"Generate File-Server-URLs.md from SERVER-CONFIG.md using URL-GENERATOR-Template.md"
```

**Step 3: Claude Processes**
```
Claude will:
1. Read BASE_URL from config
2. Read file paths from config
3. Concatenate URLs
4. Handle spaces (%20 encoding)
5. Generate complete file
```

**Step 4: Review and Save**
```
Review generated file
Save as File-Server-URLs.md
Test a few URLs
```

---

### Method 3: Python Script (Automated)

**Script:** `generate-urls.py`

```python
#!/usr/bin/env python3
"""
Generate File-Server-URLs.md from SERVER-CONFIG.md

Usage:
    python3 generate-urls.py
    python3 generate-urls.py --config custom-config.md
    python3 generate-urls.py --output custom-urls.md
"""

import re
import sys
from datetime import datetime
from urllib.parse import quote
from pathlib import Path

def extract_base_url(content):
    """Extract BASE_URL from SERVER-CONFIG.md"""
    
    # Look for BASE_URL: pattern
    match = re.search(r'^BASE_URL:\s*(.+)$', content, re.MULTILINE)
    if not match:
        raise ValueError("BASE_URL not found in config file")
    
    base_url = match.group(1).strip()
    
    # Remove trailing slash
    base_url = base_url.rstrip('/')
    
    return base_url

def extract_file_paths(content):
    """Extract file paths from FILE PATHS section"""
    
    file_paths = []
    in_file_section = False
    
    for line in content.split('\n'):
        line_stripped = line.strip()
        
        # Start of file paths section
        if '## ðŸ"„ FILE PATHS' in line or '## FILE PATHS' in line:
            in_file_section = True
            continue
        
        # End of file paths section
        if in_file_section and line_stripped.startswith('## ') and 'FILE PATHS' not in line:
            break
        
        # Skip empty lines, comments, markdown formatting
        if not in_file_section:
            continue
        
        if (not line_stripped or 
            line_stripped.startswith('#') or 
            line_stripped.startswith('```') or
            line_stripped.startswith('**') or
            line_stripped.startswith('---') or
            line_stripped.startswith('BASE_URL:')):
            continue
        
        # Valid file path (contains / or has file extension)
        if '/' in line_stripped or any(line_stripped.endswith(ext) for ext in ['.py', '.md', '.yml', '.yaml', '.html']):
            file_paths.append(line_stripped)
    
    return file_paths

def generate_url(base_url, file_path):
    """Generate complete URL with proper encoding"""
    
    # Split path into parts
    parts = file_path.split('/')
    
    # Encode each part (handles spaces, special chars)
    encoded_parts = [quote(part, safe='') for part in parts]
    
    # Rejoin with /
    encoded_path = '/'.join(encoded_parts)
    
    # Create full URL
    return f"{base_url}/{encoded_path}"

def group_files_by_directory(file_paths):
    """Group file paths by directory"""
    
    grouped = {}
    
    for path in file_paths:
        # Extract directory (everything before last /)
        if '/' in path:
            dir_name = path.rsplit('/', 1)[0]
        else:
            dir_name = 'root'
        
        if dir_name not in grouped:
            grouped[dir_name] = []
        
        grouped[dir_name].append(path)
    
    return grouped

def generate_output(base_url, file_paths, grouped_files):
    """Generate the complete File-Server-URLs.md content"""
    
    now = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
    
    output = []
    output.append("# SUGA-ISP Lambda - File Server URLs\n")
    output.append(f"**Generated:** {now}\n")
    output.append(f"**Base URL:** {base_url}\n")
    output.append(f"**Total Files:** {len(file_paths)}\n")
    output.append("**Purpose:** URL inventory for web_fetch access\n")
    output.append("\n---\n\n")
    
    # Add grouped URLs
    for directory in sorted(grouped_files.keys()):
        files = sorted(grouped_files[directory])
        output.append(f"## ðŸ"‚ {directory} ({len(files)} files)\n\n")
        
        for path in files:
            url = generate_url(base_url, path)
            output.append(f"{url}\n")
        
        output.append("\n")
    
    # Add summary
    output.append("---\n\n")
    output.append("## ðŸ"Š SUMMARY\n\n")
    output.append(f"**Total URLs:** {len(file_paths)}\n")
    output.append(f"**Directories:** {len(grouped_files)}\n")
    output.append(f"**Generated:** {now}\n")
    output.append(f"**Server:** {base_url}\n\n")
    output.append("---\n\n")
    output.append("## ðŸ"§ USAGE\n\n")
    output.append("Upload this file to Claude sessions for web_fetch access to all files.\n\n")
    output.append("**Example:**\n")
    output.append("```python\n")
    output.append('web_fetch("' + generate_url(base_url, 'nmap/Support/SESSION-START-Quick-Context.md') + '")\n')
    output.append("```\n\n")
    output.append("---\n\n")
    output.append("**END OF FILE SERVER URLS**\n")
    
    return ''.join(output)

def main():
    """Main execution"""
    
    # Parse arguments
    config_file = 'SERVER-CONFIG.md'
    output_file = 'File-Server-URLs.md'
    
    if len(sys.argv) > 1:
        if '--config' in sys.argv:
            idx = sys.argv.index('--config')
            if idx + 1 < len(sys.argv):
                config_file = sys.argv[idx + 1]
        
        if '--output' in sys.argv:
            idx = sys.argv.index('--output')
            if idx + 1 < len(sys.argv):
                output_file = sys.argv[idx + 1]
    
    # Check config file exists
    if not Path(config_file).exists():
        print(f"âŒ Error: {config_file} not found")
        sys.exit(1)
    
    # Read config
    print(f"ðŸ"„ Reading {config_file}...")
    with open(config_file, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Extract data
    try:
        base_url = extract_base_url(content)
        file_paths = extract_file_paths(content)
    except Exception as e:
        print(f"âŒ Error parsing config: {e}")
        sys.exit(1)
    
    if not file_paths:
        print("âŒ Error: No file paths found in config")
        sys.exit(1)
    
    print(f"âœ… Found BASE_URL: {base_url}")
    print(f"âœ… Found {len(file_paths)} file paths")
    
    # Group files
    grouped_files = group_files_by_directory(file_paths)
    print(f"âœ… Grouped into {len(grouped_files)} directories")
    
    # Generate output
    print(f"ðŸ"„ Generating {output_file}...")
    output_content = generate_output(base_url, file_paths, grouped_files)
    
    # Write output
    with open(output_file, 'w', encoding='utf-8') as f:
        f.write(output_content)
    
    print(f"âœ… Generated {output_file}")
    print(f"âœ… Total URLs: {len(file_paths)}")
    print(f"âœ… File size: {len(output_content)} bytes")
    print("\nðŸŽ¯ Next steps:")
    print("  1. Review the generated file")
    print("  2. Test a few URLs with web_fetch")
    print("  3. Upload to Claude sessions")
    print("  4. Run scan-hardcoded-urls.py to verify")

if __name__ == '__main__':
    main()
```

**Installation:**

```bash
# Save script to file
cd /nmap/Support/tools/
vim generate-urls.py
# Paste script above

# Make executable
chmod +x generate-urls.py
```

**Usage:**

```bash
# Basic usage (uses default files)
python3 generate-urls.py

# Custom config file
python3 generate-urls.py --config my-config.md

# Custom output file
python3 generate-urls.py --output my-urls.md

# Both custom
python3 generate-urls.py --config dev-config.md --output dev-urls.md
```

**Expected Output:**

```
ðŸ"„ Reading SERVER-CONFIG.md...
âœ… Found BASE_URL: https://claude.dizzybeaver.com
âœ… Found 287 file paths
âœ… Grouped into 15 directories
ðŸ"„ Generating File-Server-URLs.md...
âœ… Generated File-Server-URLs.md
âœ… Total URLs: 287
âœ… File size: 45632 bytes

ðŸŽ¯ Next steps:
  1. Review the generated file
  2. Test a few URLs with web_fetch
  3. Upload to Claude sessions
  4. Run scan-hardcoded-urls.py to verify
```

**Features:**

- âœ… Automatic BASE_URL extraction
- âœ… Automatic file path extraction
- âœ… Proper URL encoding (spaces → %20)
- âœ… Directory grouping
- âœ… Statistics and summary
- âœ… Error handling
- âœ… Custom config/output files
- âœ… Detailed progress output

---

## ðŸ"„ OUTPUT FILE TEMPLATE

### File-Server-URLs.md Structure

```markdown
# SUGA-ISP Lambda - File Server URLs

**Generated:** 2025-10-28 14:32:15  
**Base URL:** https://claude.dizzybeaver.com  
**Total Files:** 287  
**Purpose:** URL inventory for web_fetch access

---

## ðŸ"‚ src (80 files)

https://claude.dizzybeaver.com/src/__init__.py
https://claude.dizzybeaver.com/src/cache_core.py
https://claude.dizzybeaver.com/src/gateway.py
... (all src files)

---

## ðŸ"‚ nmap/NM00 (4 files)

https://claude.dizzybeaver.com/nmap/NM00/NM00-Quick_Index.md
https://claude.dizzybeaver.com/nmap/NM00/NM00A-Master_Index.md
... (all NM00 files)

---

## ðŸ"‚ nmap/Support (20 files)

https://claude.dizzybeaver.com/nmap/Support/SESSION-START-Quick-Context.md
https://claude.dizzybeaver.com/nmap/Support/File%20Server%20URLs.md
https://claude.dizzybeaver.com/nmap/Support/ANTI-PATTERNS%20CHECKLIST.md
... (all Support files)

---

## ðŸ"Š SUMMARY

**Total URLs:** 287
**Directories:** 15
**Generated:** 2025-10-28 14:32:15
**Server:** https://claude.dizzybeaver.com

---

## ðŸ"§ USAGE

Upload this file to Claude sessions for web_fetch access to all files.

**Example:**
```python
web_fetch("https://claude.dizzybeaver.com/nmap/Support/SESSION-START-Quick-Context.md")
```

---

**END OF FILE SERVER URLS**
```

---

## âœ… VALIDATION CHECKLIST

### Before Generating:

- [ ] BASE_URL correct in SERVER-CONFIG.md
- [ ] All file paths listed
- [ ] No duplicate paths
- [ ] Spaces will be encoded as %20

### After Generating:

- [ ] File-Server-URLs.md created
- [ ] BASE_URL correct in output
- [ ] URL count matches expected
- [ ] Test sample URLs work
- [ ] Spaces properly encoded

### For Public Release:

- [ ] BASE_URL changed to public server
- [ ] Private files removed from config
- [ ] URLs accessible without auth
- [ ] Test from fresh environment
- [ ] Documentation updated

---

## ðŸŽ¯ GENERATION WORKFLOW

### Complete Process:

```
1. Edit SERVER-CONFIG.md
   └→ Update BASE_URL if needed
   └→ Add/remove file paths
   └→ Save changes

2. Choose Generation Method
   └→ Manual (simple, low file count)
   └→ Claude-assisted (medium complexity)
   └→ Python script (automated, high file count)

3. Generate File-Server-URLs.md
   └→ Run chosen method
   └→ Verify output

4. Validate Output
   └→ Check URL count
   └→ Test sample URLs
   └→ Verify encoding

5. Distribute
   └→ Upload to Claude sessions
   └→ Add to project documentation
   └→ Version control
```

---

## ðŸ"„ UPDATING PROCESS

### When to Regenerate:

- âœ… BASE_URL changes
- âœ… New files added
- âœ… Files renamed/moved
- âœ… Files deleted
- âœ… Public release preparation
- âœ… Environment switch (dev/staging/prod)

### Quick Update:

```bash
# Edit config
vim SERVER-CONFIG.md
# Add/remove files or change BASE_URL

# Regenerate
python3 generate-urls.py

# Test
# Pick 2-3 random URLs and test with web_fetch

# Commit
git add File-Server-URLs.md SERVER-CONFIG.md
git commit -m "Update file server URLs"
git push
```

---

## ðŸ'¡ TIPS & BEST PRACTICES

### Tip 1: Version Control Both Files
```
Keep in repository:
- SERVER-CONFIG.md (source of truth)
- File-Server-URLs.md (generated)
- URL-GENERATOR-Template.md (this file)
- generate-urls.py (script)

Track changes to all files.
```

### Tip 2: Automate in CI/CD
```
In deployment pipeline:
1. Regenerate File-Server-URLs.md
2. Test URLs
3. Deploy to server
4. Verify accessibility
```

### Tip 3: Multiple Configurations
```
For different environments:
- SERVER-CONFIG-dev.md
- SERVER-CONFIG-staging.md
- SERVER-CONFIG-prod.md

Generate separate URL files for each:
python3 generate-urls.py --config SERVER-CONFIG-dev.md --output File-Server-URLs-dev.md
```

### Tip 4: Test Before Distributing
```
After generation:
1. Pick 5 random URLs from different directories
2. Test with web_fetch in Claude session
3. Verify content correct
4. Check encoding (spaces, special chars)
5. Confirm file sizes reasonable
```

### Tip 5: Monitor File Count
```
Keep track of file count:
- Under 300: Excellent (fast load)
- 300-500: Good (acceptable load time)
- 500-1000: Slow (consider splitting)
- Over 1000: Too many (split into multiple configs)
```

---

## ðŸš¨ TROUBLESHOOTING

### Issue 1: BASE_URL Not Found

**Error:** "BASE_URL not found in config file"

**Solution:**
```
1. Check SERVER-CONFIG.md has line:
   BASE_URL: https://your-domain.com
   
2. Verify format (no extra spaces):
   BASE_URL: https://example.com  ✅
   BASE_URL:https://example.com   âŒ
   BASE URL: https://example.com  âŒ
```

### Issue 2: No File Paths Found

**Error:** "No file paths found in config"

**Solution:**
```
1. Check FILE PATHS section exists:
   ## ðŸ"„ FILE PATHS
   
2. Verify file paths not in code blocks:
   WRONG:
   ```
   src/gateway.py
   ```
   
   RIGHT:
   src/gateway.py
```

### Issue 3: Spaces Not Encoded

**Problem:** URLs with spaces fail

**Solution:**
```
Script automatically encodes spaces to %20.
If manual generation, ensure:
  "File Server URLs.md" → "File%20Server%20URLs.md"
```

### Issue 4: Wrong URL Format

**Problem:** Double slashes or missing slashes

**Solution:**
```
Script removes trailing slash from BASE_URL.
Ensure file paths don't start with /:
  WRONG: /src/gateway.py
  RIGHT: src/gateway.py
```

### Issue 5: Script Fails to Run

**Error:** "Permission denied" or "python3: command not found"

**Solution:**
```bash
# Make executable
chmod +x generate-urls.py

# Check Python installed
python3 --version

# Run with full path
/usr/bin/python3 generate-urls.py
```

---

## ðŸ"š ADDITIONAL RESOURCES

### Related Files:
- SERVER-CONFIG.md - Source configuration
- File-Server-URLs.md - Generated output
- file-server-config-ui.html - Web interface
- scan-hardcoded-urls.py - URL validation scanner

### Documentation:
- Phase-0-File-Server-Config.md - Full implementation plan
- Deployment-Guide-SIMA-Mode-System.md - Deployment instructions

### Testing:
- Phase-0-Test-Report.md - Test procedures
- url-audit-report.md - Scan results

---

## ðŸ"„ VERSION HISTORY

**v2.0.0 (2025-10-28):** # ADDED: Complete Python script method with full documentation  
**v1.0.0 (2025-10-25):** Initial version with manual and Claude-assisted methods

---

**END OF URL-GENERATOR-TEMPLATE.md**
