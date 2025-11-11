# SIMA Quick Start Guide

**Version:** 4.2.2-blank  
**Time Required:** 15 minutes  
**Difficulty:** Easy

---

## STEP 1: EXTRACT (2 minutes)

### Option A: TAR.GZ
```bash
tar -xzf blank-sima-v4.2.2.tar.gz
cd sima/
```

### Option B: ZIP
```bash
unzip blank-sima-v4.2.2.zip
cd sima/
```

**Result:** You now have a /sima/ directory with 137 files

---

## STEP 2: CONFIGURE FILE SERVER (5 minutes)

### A. Set Up File Server

**Requirements:**
- Web server (Apache, Nginx, etc.)
- Directory listing enabled
- HTTPS recommended

**Copy SIMA to server:**
```bash
# Example
scp -r sima/ user@yourserver.com:/var/www/sima/
```

### B. Update File Server URLs.md

**Edit:** `/sima/File-Server-URLs.md`

**Change:**
```markdown
https://fileserver.example.com/fileserver.php?v=0072
```

**To:**
```markdown
https://your-server.com/sima/fileserver.php?v=0072
```

**Save file**

---

## STEP 3: VERIFY INSTALLATION (3 minutes)

### Check Directory Structure
```bash
ls -la /sima/
```

**Should see:**
- context/
- docs/
- generic/
- languages/
- platforms/
- projects/
- support/
- templates/
- README.md
- File-Server-URLs.md

### Check File Count
```bash
find /sima -name "*.md" | wc -l
```

**Should show:** ~137 files

---

## STEP 4: ACTIVATE FIRST MODE (5 minutes)

### Upload to AI Assistant

1. Open your AI assistant (Claude recommended)
2. Upload **File-Server-URLs.md**
3. Wait for upload confirmation

### Activate General Mode

**Say:**
```
Please load context
```

**Expected Response:**
Claude loads General Mode context and confirms ready

### Test with Question

**Ask:**
```
What modes are available in SIMA?
```

**Expected:** Claude lists 6 modes with descriptions

---

## STEP 5: CREATE FIRST KNOWLEDGE ENTRY (10 minutes)

### Activate Learning Mode

**Say:**
```
Start SIMA Learning Mode
```

### Provide Source Material

**Example:**
```
I learned that always reading complete files before 
modifying them prevents errors and reduces rework.
```

### Review Output

**Expected:**
- Claude creates LESS-01 entry as artifact
- Updates lesson index
- Brief confirmation message

### Verify Entry Created

**Ask:**
```
Show me the lessons index
```

**Expected:** Claude shows index with your new entry

---

## YOU'RE READY!

**What You Can Do Now:**

### Learn About System
```
Please load context
Ask questions about SIMA
```

### Add More Knowledge
```
Start SIMA Learning Mode
Provide experiences to document
```

### Build a Project
```
Start New Project Mode: MyProject
Follow prompts to create structure
```

### Maintain System
```
Start SIMA Maintenance Mode
Update indexes
Verify structure
```

---

## NEXT STEPS

### 1. Read User Guide
**Location:** `/sima/docs/user/SIMAv4.2.2-User-Guide.md`

### 2. Review Mode Comparison
**Location:** `/sima/docs/user/SIMAv4.2.2-Mode-Comparison-Guide.md`

### 3. Explore Templates
**Location:** `/sima/templates/`

### 4. Check Support Resources
**Location:** `/sima/support/`

---

## COMMON ISSUES

### Issue: Mode won't activate
**Solution:**
1. Verify File-Server-URLs.md uploaded
2. Use exact activation phrase
3. Check file server accessible

### Issue: Files not fresh
**Solution:**
1. Verify cache-busting parameters
2. Check fileserver.php working
3. Clear AI assistant cache

### Issue: Navigation broken
**Solution:**
1. Check indexes exist
2. Verify file paths correct
3. Run structure verification

---

## QUICK REFERENCE CARD

**Mode Activation Phrases:**
```
"Please load context"                    → General Mode
"Start SIMA Learning Mode"               → Learning Mode
"Start SIMA Maintenance Mode"            → Maintenance Mode
"Start Project Mode for [PROJECT]"       → Project Mode
"Start Debug Mode for [PROJECT]"         → Debug Mode
"Start New Project Mode: [NAME]"         → New Project
```

**Key Locations:**
```
/docs/                → All documentation
/support/workflows/   → Step-by-step workflows
/support/tools/       → Quick references and tools
/templates/           → Entry templates
```

---

## HELP RESOURCES

### In SIMA
- Quick Answer Index: `/support/tools/TOOL-02-Quick-Answer-Index.md`
- Navigation Guide: `/support/quick-reference/QRC-02-Navigation-Guide.md`
- Mode Comparison: `/support/quick-reference/QRC-01-Mode-Comparison.md`

### Documentation
- User Guide: `/docs/user/SIMAv4.2.2-User-Guide.md`
- FAQ: Inside user guide
- Troubleshooting: Inside user guide

---

**SETUP COMPLETE!**

**Time Spent:** ~15 minutes  
**Status:** ✅ Ready to use  
**Next:** Start adding your knowledge!

**Welcome to SIMA!** 🎉