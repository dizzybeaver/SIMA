# Encoding-Standards.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** UTF-8 encoding and special character standards  
**Location:** `/sima/shared/`

---

## UTF-8 REQUIREMENT

**All text files must use UTF-8 encoding**

**Includes:**
- Markdown files
- Python source files
- Configuration files
- Documentation files
- YAML/JSON files

---

## EMOJI SUPPORT

### Standard Emoji

**Commonly used:**
- ✅ Success/correct
- ❌ Failure/wrong
- ⚠️ Warning/caution
- 🎯 Target/goal
- 📂 Directory/folder
- 📄 File/document
- 🔧 Tool/utility
- 🚀 Launch/deploy
- 🐛 Bug/issue
- ⏱️ Time/performance

### Verification

**Test emoji rendering:**
```markdown
✅ This should show a checkmark
❌ This should show an X
🎯 This should show a target
```

**If broken:**
- Check file encoding (must be UTF-8)
- Verify editor settings
- Test in different viewer

---

## SPECIAL CHARACTERS

### Allowed Characters

**Arrows:**
- → (right arrow)
- ← (left arrow)
- ↑ (up arrow)
- ↓ (down arrow)

**Bullets:**
- • (bullet point)
- ◦ (hollow bullet)
- ‣ (triangular bullet)

**Symbols:**
- © (copyright)
- ® (registered)
- ™ (trademark)
- § (section)

### Verification

**Test special characters:**
```
Gateway → Interface → Core
Item • Subitem ◦ Detail
```

---

## CHARTS AND DIAGRAMS

### Markdown Tables

**Format:**
```markdown
| Column 1 | Column 2 | Column 3 |
|----------|----------|----------|
| Value A  | Value B  | Value C  |
```

**Verification:**
- Columns align correctly
- Borders render
- Content displays

### ASCII Diagrams

**Format:**
```
┌─────────────┐
│   Gateway   │
└──────┬──────┘
       │
┌──────▼──────┐
│  Interface  │
└──────┬──────┘
       │
┌──────▼──────┐
│    Core     │
└─────────────┘
```

**Verification:**
- Box drawing characters work
- Layout preserved
- Arrows display

---

## COMMON ISSUES

### Problem: Broken Emoji

**Cause:** File not UTF-8 encoded

**Solution:**
1. Convert file to UTF-8
2. Re-save with UTF-8 encoding
3. Verify emoji display

### Problem: Garbled Text

**Cause:** Mixed encodings

**Solution:**
1. Identify encoding (usually Latin-1 or Windows-1252)
2. Convert to UTF-8
3. Re-save all files

### Problem: Charts Don't Render

**Cause:** Markdown formatting errors

**Solution:**
1. Check table syntax
2. Verify column alignment
3. Test in markdown previewer

---

## RELATED STANDARDS

**Complete Details:**
- SPEC-ENCODING.md - Full encoding requirements
- SPEC-MARKDOWN.md - Markdown standards
- SPEC-FILE-STANDARDS.md - File requirements

**Location:** `/sima/entries/specifications/`

---

**END OF FILE**

**Summary:** UTF-8 encoding required, emoji support verified, special characters tested, charts formatted correctly.
