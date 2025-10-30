# URL-GENERATOR-Template.md

**Version:** 1.0.0  
**Date:** 2025-10-25  
**Purpose:** Generate File-Server-URLs.md from SERVER-CONFIG.md  
**Usage:** Process this template to create complete URL file

---

## 🎯 WHAT THIS IS

This is a **template and instructions** for generating `File-Server-URLs.md` from `SERVER-CONFIG.md`.

**Inputs:**
- SERVER-CONFIG.md (base URL + file paths)

**Outputs:**
- File-Server-URLs.md (complete URLs for web_fetch)

---

## 🔧 GENERATION METHODS

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

```python
#!/usr/bin/env python3
"""
Generate File-Server-URLs.md from SERVER-CONFIG.md
"""

import re
from urllib.parse import quote

def read_config(config_file):
    """Extract BASE_URL and file paths from config"""
    with open(config_file, 'r') as f:
        content = f.read()
    
    # Extract BASE_URL
    base_url_match = re.search(r'BASE_URL:\s*(.+)', content)
    if not base_url_match:
        raise ValueError("BASE_URL not found in config")
    base_url = base_url_match.group(1).strip()
    
    # Extract file paths (lines not starting with #, --, or empty)
    file_paths = []
    in_file_section = False
    
    for line in content.split('\n'):
        line = line.strip()
        
        # Skip headers, comments, empty lines
        if (line.startswith('#') or 
            line.startswith('```') or
            line.startswith('**') or
            line.startswith('---') or
            not line):
            continue
        
        # Skip config lines
        if line.startswith('BASE_URL:'):
            continue
        
        # File path lines (contain / or end with .py, .md, .yml)
        if '/' in line or line.endswith(('.py', '.md', '.yml')):
            file_paths.append(line)
    
    return base_url, file_paths

def generate_url(base_url, file_path):
    """Generate complete URL with proper encoding"""
    # Split into directory and filename
    parts = file_path.split('/')
    
    # URL encode each part (handles spaces and special chars)
    encoded_parts = [quote(part, safe='') for part in parts]
    
    # Rejoin with /
    encoded_path = '/'.join(encoded_parts)
    
    # Concatenate with base URL
    return f"{base_url}/{encoded_path}"

def generate_file_server_urls(config_file, output_file):
    """Main generation function"""
    
    # Read config
    base_url, file_paths = read_config(config_file)
    
    # Group paths by directory
    grouped = {}
    for path in file_paths:
        dir_name = path.split('/')[0] if '/' in path else 'root'
        if dir_name not in grouped:
            grouped[dir_name] = []
        grouped[dir_name].append(path)
    
    # Generate output
    with open(output_file, 'w') as f:
        f.write("# File Server URLs\n\n")
        f.write(f"**Generated from:** SERVER-CONFIG.md\n")
        f.write(f"**Base URL:** {base_url}\n")
        f.write(f"**Total Files:** {len(file_paths)}\n\n")
        f.write("---\n\n")
        
        # Output URLs grouped by directory
        for dir_name in sorted(grouped.keys()):
            f.write(f"## {dir_name.upper()}\n\n")
            for path in sorted(grouped[dir_name]):
                url = generate_url(base_url, path)
                f.write(f"{url}\n")
            f.write("\n")
    
    print(f"Generated {output_file}")
    print(f"Total URLs: {len(file_paths)}")

if __name__ == "__main__":
    generate_file_server_urls("SERVER-CONFIG.md", "File-Server-URLs.md")
```

**Usage:**
```bash
python url_generator.py
```

---

## 📄 OUTPUT FILE TEMPLATE

### File-Server-URLs.md Structure

```markdown
# File Server URLs

**Generated from:** SERVER-CONFIG.md  
**Base URL:** [BASE_URL]  
**Date:** [GENERATION_DATE]  
**Total Files:** [FILE_COUNT]

---

## 📁 DIRECTORY URLS

```
[BASE_URL]/src
[BASE_URL]/nmap
[BASE_URL]/nmap/NM00
[BASE_URL]/nmap/NM01
... (all directories)
```

---

## 🐍 Python Source Files (/src)

[BASE_URL]/src/__init__.py
[BASE_URL]/src/cache_core.py
[BASE_URL]/src/gateway.py
... (all src files)

---

## 📂 Neural Map Files - Gateway Layer (/nmap/NM00)

[BASE_URL]/nmap/NM00/NM00-Quick_Index.md
[BASE_URL]/nmap/NM00/NM00A-Master_Index.md
... (all NM00 files)

---

## 📂 Neural Map Files - Support (/nmap/Support)

[BASE_URL]/nmap/Support/SESSION-START-Quick-Context.md
[BASE_URL]/nmap/Support/File%20Server%20URLs.md
[BASE_URL]/nmap/Support/ANTI-PATTERNS%20CHECKLIST.md
... (all Support files)

---

## 📊 SUMMARY

**Total URLs:** [COUNT]
**Last Updated:** [DATE]
**Server:** [BASE_URL]

---

## 🔧 USAGE

Upload this file to Claude sessions for web_fetch access to all files.

```
Example:
web_fetch("[BASE_URL]/nmap/Support/SESSION-START-Quick-Context.md")
```
```

---

## ✅ VALIDATION CHECKLIST

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

## 🎯 GENERATION WORKFLOW

### Complete Process:

```
1. Edit SERVER-CONFIG.md
   └─> Update BASE_URL if needed
   └─> Add/remove file paths
   └─> Save changes

2. Choose Generation Method
   └─> Manual (simple, low file count)
   └─> Claude-assisted (medium complexity)
   └─> Python script (automated, high file count)

3. Generate File-Server-URLs.md
   └─> Run chosen method
   └─> Verify output

4. Validate Output
   └─> Check URL count
   └─> Test sample URLs
   └─> Verify encoding

5. Distribute
   └─> Upload to Claude sessions
   └─> Add to project documentation
   └─> Version control
```

---

## 🔄 UPDATING PROCESS

### When to Regenerate:

- ✅ BASE_URL changes
- ✅ New files added
- ✅ Files renamed/moved
- ✅ Files deleted
- ✅ Public release preparation

### Quick Update:

```
1. Edit SERVER-CONFIG.md (add/remove files)
2. Run: python url_generator.py
3. Upload new File-Server-URLs.md
4. Test changed URLs
```

---

## 💡 TIPS & BEST PRACTICES

### Tip 1: Version Control Both Files
```
Keep in repository:
- SERVER-CONFIG.md (source of truth)
- File-Server-URLs.md (generated)
- URL-GENERATOR-Template.md (this file)

Track changes to all three.
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

Generate separate URL files for each.
```

### Tip 4: Test Before Distributing
```
After generation:
1. Pick 5 random URLs
2. Test with web_fetch
3. Verify content correct
4. Check encoding worked
```

### Tip 5: Document Changes
```
In commit message:
"Updated File-Server-URLs.md
- Added 3 new neural map files
- Changed BASE_URL to production
- Removed 2 deprecated files"
```

---

## 🚨 COMMON ISSUES

### Issue: Spaces in URLs Not Working
**Cause:** Spaces not encoded to %20  
**Fix:** Ensure encoding function used  
**Prevention:** Use Python script with quote()

### Issue: URLs 404 Not Found
**Cause:** File path wrong or BASE_URL wrong  
**Fix:** Verify paths in SERVER-CONFIG.md  
**Prevention:** Test URLs after generation

### Issue: Too Many Files to Update Manually
**Cause:** Large file list, manual method chosen  
**Fix:** Switch to Python script method  
**Prevention:** Automate from the start

### Issue: Public URLs Exposing Private Info
**Cause:** Didn't remove private files  
**Fix:** Edit SERVER-CONFIG.md, remove private paths, regenerate  
**Prevention:** Create separate config for public release

---

## 📋 EXAMPLE GENERATION SESSION

### User Request to Claude:

```
[Uploads SERVER-CONFIG.md]
[Uploads URL-GENERATOR-Template.md]

"Please generate File-Server-URLs.md from SERVER-CONFIG.md.

Requirements:
1. Extract BASE_URL: https://claude.dizzybeaver.com
2. For each file path in config, create complete URL
3. Encode spaces as %20
4. Group URLs by directory
5. Output as File-Server-URLs.md

Include header with:
- Base URL
- Total file count
- Generation date
- Usage instructions"
```

### Claude Response:

```
I'll generate File-Server-URLs.md from your config.

[Processes files]

Generated file contains:
- 230 complete URLs
- Properly encoded (spaces → %20)
- Grouped by directory (src, NM00-NM07, Support, Docs, Testing)
- Ready to upload to sessions

[Outputs complete File-Server-URLs.md as artifact]

Would you like me to test a few URLs to verify they work?
```

---

## 🎯 SUCCESS CRITERIA

### Generation Successful When:

- ✅ File-Server-URLs.md created
- ✅ All paths converted to URLs
- ✅ BASE_URL correct
- ✅ Spaces encoded (%20)
- ✅ Directory grouping maintained
- ✅ Count matches expected
- ✅ Sample URLs tested and work

---

**END OF URL GENERATOR TEMPLATE**

**Use this to generate File-Server-URLs.md from SERVER-CONFIG.md**  
**Choose method based on complexity and preference**  
**Validate output before distributing**

**Methods available:** Manual, Claude-assisted, Python script
