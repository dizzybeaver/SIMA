# SIMA Manager Usage Guide

**Version:** 1.0.0  
**Date:** 2025-11-29  
**Purpose:** Quick start guide for SIMA Manager Flask app

---

## Quick Start

### 1. Install Dependencies

```bash
pip install -r requirements.txt
```

### 2. Run the Application

```bash
python sima_manager.py
```

### 3. Open Browser

Navigate to: `http://localhost:5000`

---

## Features

### 🔄 Export to JSON

**Purpose:** Convert MD files to universal JSON format

**Steps:**
1. Enter directory path (e.g., `./sima/generic`)
2. Click "Export"
3. Download the generated JSON file

**Output Format:**
```json
{
  "manifest": {
    "version": "1.0.0",
    "sima_version": "4.2.2",
    "created": "2025-11-29T...",
    "file_count": 42
  },
  "files": [
    {
      "title": "Lesson Title",
      "ref_id": "LESS-01",
      "languages": ["python", "bash"],
      "content": {...}
    }
  ]
}
```

---

### 📥 Import from JSON

**Purpose:** Convert JSON exports back to MD files

**Steps:**
1. Click "Choose File" and select JSON export
2. Enter target directory path
3. Click "Import"

**Result:** Creates MD files in target directory with proper formatting

---

### 📑 Generate Index

**Purpose:** Auto-generate index files for directories

**Steps:**
1. Enter directory path
2. Enter index title (optional)
3. Click "Generate"

**Output:** Creates `{directory-name}-Index.md` with:
- All files grouped by category
- Language tags for each file
- REF-IDs and descriptions
- Auto-sorted alphabetically

---

### 🔍 Analyze File

**Purpose:** Detect languages and extract metadata

**Steps:**
1. Enter file path
2. Click "Analyze"

**Shows:**
- Detected programming languages
- REF-ID
- Line count
- Whether it exceeds 350-line limit
- All metadata

---

## Language Detection

**Automatically detects:**
- Python
- JavaScript/TypeScript
- Java
- Go
- Rust
- C/C++/C#
- Ruby
- PHP
- Swift
- Kotlin
- SQL
- Bash
- YAML

**How it works:** Scans for code blocks like:
```python
# Code here
```

**Result:** Adds language tags to exports and indexes

---

## Directory Structure

```
.
├── sima_manager.py          # Main application
├── requirements.txt         # Dependencies
├── exports/                 # JSON exports saved here
├── archives/               # (Future use)
└── sima/                   # Your SIMA knowledge base
    ├── generic/
    ├── platforms/
    ├── languages/
    └── projects/
```

---

## Common Workflows

### Export Entire Domain

```
1. Path: ./sima/generic
2. Click Export
3. Download: sima_export_20251129_143022.json
```

### Import to New Instance

```
1. Upload: sima_export_20251129_143022.json
2. Target: ./sima/generic
3. Click Import
4. ✅ All files converted to MD
```

### Update All Indexes

```
For each domain:
1. Path: ./sima/generic/lessons
2. Title: Lessons Index
3. Click Generate
4. Repeat for decisions, anti-patterns, etc.
```

### Check File Compliance

```
1. Path: ./sima/generic/lessons/LESS-01.md
2. Click Analyze
3. Review:
   - Line count (must be ≤350)
   - Languages detected
   - Metadata completeness
```

---

## API Endpoints

### Export
```
POST /api/export
Body: {"path": "./sima/generic"}
Returns: {file_count, output_file, filename}
```

### Import
```
POST /api/import
Form: file=export.json, target=./sima/generic
Returns: {imported_count, files[]}
```

### Generate Index
```
POST /api/index
Body: {"path": "./sima/generic", "title": "Index"}
Returns: {output_file, entry_count}
```

### Analyze
```
POST /api/analyze
Body: {"path": "./sima/generic/LESS-01.md"}
Returns: {languages[], line_count, metadata}
```

---

## JSON Format Specification

**Universal format for cross-version compatibility:**

```json
{
  "format_version": "1.0.0",
  "sima_version": "4.2.2",
  "title": "File Title",
  "ref_id": "LESS-01",
  "metadata": {
    "version": "1.0.0",
    "date": "2025-11-29",
    "purpose": "...",
    "category": "Lessons",
    "created": "2025-11-29T...",
    "modified": "2025-11-29T..."
  },
  "languages": ["python", "bash"],
  "keywords": ["caching", "performance"],
  "related": ["LESS-02", "DEC-01"],
  "content": {
    "markdown": "# Full MD content...",
    "sections": [
      {
        "heading": "Section Title",
        "content": "Section content..."
      }
    ]
  },
  "flags": {
    "has_code": true,
    "line_count": 245,
    "exceeds_limit": false
  }
}
```

---

## Tips

**Performance:**
- Export processes ~100 files/second
- Large domains (500+ files) take 5-10 seconds

**File Size:**
- MD files: ~2-5 KB average
- JSON exports: ~3x larger (includes metadata)
- Zip archives: ~70% compression

**Best Practices:**
1. Export before major changes (backup)
2. Generate indexes after adding files
3. Analyze files before committing
4. Use JSON for cross-version transfers

---

## Troubleshooting

**"Module not found"**
```bash
pip install -r requirements.txt
```

**"Permission denied"**
```bash
chmod +w ./sima
```

**"File not found"**
- Use absolute paths or paths relative to where you run the app
- Example: `./sima/generic` not `/sima/generic`

**Port 5000 in use**
```python
# Edit sima_manager.py line 349:
app.run(debug=True, port=5001)  # Changed port
```

---

## Future Enhancements

**Planned:**
- Archive creation (zip bundles)
- Batch file validation
- Duplicate detection
- Cross-reference verification
- Migration tools (v4.2 → v5.0)
- Web-based MD editor

---

**END OF GUIDE**

**Questions?** Check analyze output for file details  
**Issues?** Verify paths and permissions  
**Ready?** Run `python sima_manager.py` and open browser
