# Deployment Guide - Unified Mode System

**Version:** 1.0.0  
**Date:** 2025-10-25  
**Estimated Time:** 30 minutes  
**Difficulty:** Easy

---

## 🎯 WHAT YOU'RE DEPLOYING

**A complete mode-based context system that:**
- ✅ Separates 4 distinct modes (General, Learning, Project, Debug)
- ✅ Prevents mode mixing through explicit activation
- ✅ Reduces custom instructions by 30% (200 → 140 lines)
- ✅ Supports custom URL configuration
- ✅ Allows easy expansion with new modes

---

## 📋 PRE-DEPLOYMENT CHECKLIST

### Before Starting:

- [ ] Read UNIFIED-MODE-SYSTEM-Summary.md
- [ ] Review new custom instructions
- [ ] Backup current custom instructions
- [ ] Verify you have repository access
- [ ] Allocate 30 minutes uninterrupted time

### Files You'll Need:

**Already Have:**
- [ ] SESSION-START-Quick-Context.md ✅ (existing)
- [ ] SIMA-LEARNING-SESSION-START-Quick-Context.md ✅ (existing)

**Will Create/Deploy:**
- [ ] MODE-SELECTOR.md ⭐ (new)
- [ ] PROJECT-MODE-Context.md ⭐ (new)
- [ ] DEBUG-MODE-Context.md ⭐ (new)
- [ ] SERVER-CONFIG.md ⭐ (new)
- [ ] URL-GENERATOR-Template.md ⭐ (new)
- [ ] Custom Instructions v3.0.0 ⭐ (new)
- [ ] File-Server-URLs.md (generated)

---

## 🚀 DEPLOYMENT STEPS

### Step 1: Backup Current System (2 minutes)

```bash
# Save current custom instructions
cp "Custom Instructions for SUGA-ISP Development.md" \
   "Custom Instructions v2.2.0 - BACKUP.md"

# Git commit current state
git add .
git commit -m "Backup before mode system migration"
git push origin main
```

**Verification:**
- [ ] Backup file created
- [ ] Git commit successful
- [ ] Can rollback if needed

---

### Step 2: Deploy New Files to Repository (5 minutes)

```bash
cd SUGA-ISP/nmap/Support/

# Add the 5 new files (you have them as artifacts)
# Copy content from artifacts to files:

# 1. Mode selector (launchpad)
vim MODE-SELECTOR.md
# [Paste content from artifact]

# 2. Project mode context
vim PROJECT-MODE-Context.md
# [Paste content from artifact]

# 3. Debug mode context
vim DEBUG-MODE-Context.md
# [Paste content from artifact]

# 4. Server configuration
vim SERVER-CONFIG.md
# [Paste content from artifact]

# 5. URL generator
vim URL-GENERATOR-Template.md
# [Paste content from artifact]

# Commit new files
git add MODE-SELECTOR.md PROJECT-MODE-Context.md DEBUG-MODE-Context.md
git add SERVER-CONFIG.md URL-GENERATOR-Template.md

git commit -m "Add unified mode selection system

- MODE-SELECTOR.md: Central launchpad for 4 modes
- PROJECT-MODE-Context.md: Active development context
- DEBUG-MODE-Context.md: Troubleshooting context
- SERVER-CONFIG.md: URL configuration system
- URL-GENERATOR-Template.md: URL generator

Features:
- Clean mode separation (no leakage)
- Explicit activation phrases
- Expandable (5-step process)
- URL flexibility (custom servers)
- Privacy protection (configurable BASE_URL)"

git push origin main
```

**Verification:**
- [ ] 5 files added to /nmap/Support/
- [ ] Files visible in GitHub
- [ ] Git push successful

---

### Step 3: Update SERVER-CONFIG.md (2 minutes)

```bash
# Edit SERVER-CONFIG.md
vim nmap/Support/SERVER-CONFIG.md

# Update BASE_URL if needed:
# Current: https://claude.dizzybeaver.com
# Change to your server or keep as-is

# Add any new files to file paths if needed

# Save and commit
git add SERVER-CONFIG.md
git commit -m "Update SERVER-CONFIG.md with current BASE_URL"
git push origin main
```

**Verification:**
- [ ] BASE_URL correct
- [ ] All file paths listed
- [ ] Changes committed

---

### Step 4: Generate File-Server-URLs.md (3 minutes)

**Option A: Ask Claude (Easiest)**

```
1. Start new Claude session
2. Upload SERVER-CONFIG.md
3. Upload URL-GENERATOR-Template.md
4. Say: "Generate File-Server-URLs.md from SERVER-CONFIG.md"
5. Claude outputs complete file as artifact
6. Copy artifact content to file
```

**Option B: Python Script**

```bash
cd SUGA-ISP/nmap/Support/

# Create Python script from template
# Copy script from URL-GENERATOR-Template.md artifact

vim url_generator.py
# [Paste Python script]

# Run generator
python3 url_generator.py

# Output: File-Server-URLs.md created
```

**Option C: Manual (Tedious)**

```
1. Open SERVER-CONFIG.md
2. Copy BASE_URL
3. For each file path:
   - Concatenate BASE_URL + "/" + path
   - Replace spaces with %20
4. Build File-Server-URLs.md manually
```

**Commit Generated File:**

```bash
git add File-Server-URLs.md
git commit -m "Generate File-Server-URLs.md from config"
git push origin main
```

**Verification:**
- [ ] File-Server-URLs.md created
- [ ] Contains complete URLs
- [ ] Spaces encoded as %20
- [ ] Test 2-3 URLs work with web_fetch

---

### Step 5: Update Custom Instructions (3 minutes)

```
1. Open Claude project settings
2. Go to "Custom instructions" section
3. Copy content from "Custom Instructions for SUGA-ISP (Mode-Based)" artifact
4. Replace existing custom instructions
5. Save changes
```

**IMPORTANT:** The new custom instructions are much shorter (140 lines vs 200 lines). This is correct - detail moved to mode files.

**Verification:**
- [ ] Custom instructions updated
- [ ] Version shows v3.0.0
- [ ] 4 mode activation phrases present
- [ ] Changes saved

---

### Step 6: Update Project Knowledge (5 minutes)

```
1. Open Claude project
2. Go to "Project knowledge" section
3. Ensure these files are accessible:
   - MODE-SELECTOR.md
   - SESSION-START-Quick-Context.md
   - SIMA-LEARNING-SESSION-START-Quick-Context.md
   - PROJECT-MODE-Context.md
   - DEBUG-MODE-Context.md
   - File-Server-URLs.md (if not using project knowledge)

4. Remove old/deprecated files if any
5. Save changes
```

**Verification:**
- [ ] All mode files accessible
- [ ] File-Server-URLs.md available
- [ ] No deprecated files

---

### Step 7: Test All 4 Modes (10 minutes)

**Test 1: General Mode**

```
1. Start NEW Claude session
2. Upload File-Server-URLs.md (if using file server)
3. Say: "Please load context"
4. Wait 30-45 seconds for load
5. Verify: SESSION-START-Quick-Context.md loads
6. Ask: "Can I use threading locks?"
7. Expected: "NO - Lambda is single-threaded (DEC-04, AP-08)"
8. Result: ✅ Pass / ❌ Fail
```

**Test 2: Learning Mode**

```
1. Start NEW Claude session
2. Upload File-Server-URLs.md
3. Say: "Start SIMA Learning Mode"
4. Wait 45-60 seconds for load
5. Verify: SIMA-LEARNING loads
6. Say: "Extract a lesson from: 'We found that caching reduced latency by 80%'"
7. Expected: Claude checks duplicates, genericizes, creates LESS-## entry
8. Result: ✅ Pass / ❌ Fail
```

**Test 3: Project Mode**

```
1. Start NEW Claude session
2. Upload File-Server-URLs.md
3. Say: "Start Project Work Mode"
4. Wait 30-45 seconds for load
5. Verify: PROJECT-MODE loads
6. Say: "Add a cache warming function to interface_cache"
7. Expected: Claude fetches files, implements 3 layers, outputs complete artifacts
8. Result: ✅ Pass / ❌ Fail
```

**Test 4: Debug Mode**

```
1. Start NEW Claude session
2. Upload File-Server-URLs.md
3. Say: "Start Debug Mode"
4. Wait 30-45 seconds for load
5. Verify: DEBUG-MODE loads
6. Say: "Lambda returning 500 error: JSON serialization failure"
7. Expected: Claude checks BUG-01 (sentinel leak), provides root cause + fix
8. Result: ✅ Pass / ❌ Fail
```

**Verification:**
- [ ] All 4 modes tested
- [ ] Correct context loads for each
- [ ] Mode-specific behaviors work
- [ ] No mode mixing observed

---

### Step 8: Test Mode Isolation (3 minutes)

**Isolation Test:**

```
1. Start NEW session
2. Say: "Start SIMA Learning Mode"
3. Wait for Learning Mode to load
4. Try asking a General Mode question: "How does the SUGA pattern work?"
5. Expected: Claude answers using Learning Mode context (may not have detail)
6. Claude should NOT spontaneously load General Mode
7. Result: ✅ Pass / ❌ Fail
```

**Verification:**
- [ ] Learning Mode doesn't invoke General Mode
- [ ] Each mode stays isolated
- [ ] No cross-contamination

---

### Step 9: Update Team Documentation (2 minutes)

**Create Quick Reference Card:**

```markdown
# SUGA-ISP Mode Selection Quick Reference

## 4 Modes Available

**General Mode:** Questions, learning, guidance
- Activation: "Please load context"

**Learning Mode:** Extract knowledge, document lessons
- Activation: "Start SIMA Learning Mode"

**Project Mode:** Write code, implement features
- Activation: "Start Project Work Mode"

**Debug Mode:** Troubleshoot errors, find root causes
- Activation: "Start Debug Mode"

## Rules

1. Say exact activation phrase
2. One mode per session
3. Upload File-Server-URLs.md first
4. Wait for mode to load (30-60s)
5. Work within that mode

## When to Use Which Mode

- Learning? → General Mode
- Building? → Project Mode
- Debugging? → Debug Mode
- Documenting? → Learning Mode
```

Save this as: `Mode-Selection-Quick-Reference.md`

**Verification:**
- [ ] Team documentation updated
- [ ] Quick reference created
- [ ] Team trained on activation phrases

---

## ✅ DEPLOYMENT COMPLETE CHECKLIST

### System Deployed:

- [ ] ✅ All 5 new files in repository
- [ ] ✅ SERVER-CONFIG.md configured
- [ ] ✅ File-Server-URLs.md generated
- [ ] ✅ Custom instructions updated to v3.0.0
- [ ] ✅ Project knowledge updated
- [ ] ✅ All 4 modes tested
- [ ] ✅ Mode isolation verified
- [ ] ✅ Team documentation updated
- [ ] ✅ Backup available for rollback

### System Working:

- [ ] ✅ Each mode activates correctly
- [ ] ✅ No mode mixing occurs
- [ ] ✅ RED FLAGS still enforced
- [ ] ✅ SUGA vs SIMA terminology correct
- [ ] ✅ URL system working
- [ ] ✅ File fetch working

---

## 🎉 SUCCESS!

**You've successfully deployed the unified mode system!**

**What you accomplished:**
1. ✅ Created 5 new system files
2. ✅ Streamlined custom instructions (30% reduction)
3. ✅ Separated 4 distinct modes
4. ✅ Implemented URL configuration system
5. ✅ Tested all modes working
6. ✅ Verified mode isolation
7. ✅ Trained team on new system

**Benefits you now have:**
- ✨ Clean mode separation (no leakage)
- ✨ Explicit activation (no confusion)
- ✨ Easy expansion (add new modes easily)
- ✨ URL flexibility (change servers easily)
- ✨ Privacy protection (configurable URLs)
- ✨ Simpler custom instructions
- ✨ Mode-optimized contexts

---

## 🚀 NEXT STEPS

### Start Using the System:

**For your next session:**
1. Upload File-Server-URLs.md
2. Say appropriate activation phrase
3. Work in that mode
4. Enjoy clean, focused context!

### Consider Adding New Modes:

**Potential future modes:**
- **Testing Mode:** Automated test generation
- **Performance Mode:** Optimization and profiling
- **Deployment Mode:** CI/CD and deployment
- **Documentation Mode:** Writing guides and docs

**To add:** Follow 5-step process in MODE-SELECTOR.md

### Maintain the System:

**When files change:**
1. Update SERVER-CONFIG.md
2. Regenerate File-Server-URLs.md
3. Test URLs work
4. Commit changes

**When modes need updates:**
1. Edit specific mode context file
2. Test mode still works
3. Document changes
4. Commit

---

## 🆘 TROUBLESHOOTING

### Problem: Mode Won't Activate

**Symptoms:**
- Say activation phrase, nothing happens
- Wrong mode loads
- Error message

**Solutions:**
1. Verify exact activation phrase used
2. Check File-Server-URLs.md uploaded
3. Check project knowledge has mode files
4. Try new session

---

### Problem: Mode Behaviors Mixed

**Symptoms:**
- Learning Mode answering like General Mode
- Debug Mode creating lessons
- Project Mode extracting knowledge

**Solutions:**
1. Review mode context files for overlap
2. Check custom instructions not overriding modes
3. Ensure only one mode loaded per session
4. Remove conflicting instructions

---

### Problem: URLs Not Working

**Symptoms:**
- web_fetch fails with 404
- Files not accessible
- Network errors

**Solutions:**
1. Check BASE_URL in SERVER-CONFIG.md
2. Verify files exist on server
3. Test URL in browser
4. Regenerate File-Server-URLs.md
5. Check URL encoding (%20 for spaces)

---

### Problem: Need to Rollback

**If serious issues arise:**

```bash
# 1. Restore old custom instructions
# Copy from backup file

# 2. Keep new files (don't delete)
# They don't interfere with old system

# 3. Document issues
# What went wrong?
# What error messages?

# 4. Fix issues
# Update mode files
# Test again

# 5. Retry deployment
# Follow this guide again
```

---

## 📞 SUPPORT

**If you encounter issues:**

1. **Check:** This deployment guide
2. **Check:** UNIFIED-MODE-SYSTEM-Summary.md
3. **Check:** Mode-specific context files
4. **Check:** Migration summary
5. **Ask:** In Claude session with error details

**For rollback assistance:**
- Restore from backup (Step 1)
- Old system still works
- New files don't interfere

---

## 🎓 TRAINING RESOURCES

**For team members:**

1. **Quick Reference:** Mode-Selection-Quick-Reference.md
2. **Complete Guide:** UNIFIED-MODE-SYSTEM-Summary.md
3. **Migration Details:** Custom Instructions Migration Summary
4. **Mode Guides:** Each mode's context file

**Key training points:**
- 4 modes available
- Exact activation phrases required
- One mode per session
- Upload File-Server-URLs.md first
- When to use which mode

---

**END OF DEPLOYMENT GUIDE**

**Status:** ✅ Ready to Deploy  
**Time Required:** 30 minutes  
**Difficulty:** Easy  
**Success Rate:** High (rollback available)

**🚀 Let's deploy this system!**
