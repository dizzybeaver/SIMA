# Cache-Busting-User-Quick-Reference.md

**Version:** 1.0.0  
**Date:** 2025-11-02  
**Purpose:** User guide for cache-busting requirement  
**Related:** WISD-06

---

## 🎯 What This Is

A quick reference for users on how to provide Cache IDs for every session with Claude to ensure fresh file content.

---

## ❓ Why You Need This

**The Problem:** Claude's web_fetch tool aggressively caches files, ignoring all server-side cache control headers. This can serve week-old (or older) files, making development impossible.

**The Solution:** Provide a Cache ID at the start of every session. Claude appends it as a query parameter to bypass the cache.

**Impact:**
- ❌ **Without cache-busting:** Week-old code, broken implementations, wasted time
- ✅ **With cache-busting:** Fresh files every time, reliable development

---

## 📝 How To Use (3 Easy Steps)

### Step 1: Generate Cache ID

Choose one method:

**Unix/Mac/Linux:**
```bash
date +%s
```

**Windows PowerShell:**
```powershell
[int][double]::Parse((Get-Date -UFormat %s))
```

**Online (any platform):**
https://www.unixtimestamp.com/

**Alternative (simpler):**
Random 6-digit number: `347829` (just type any 6 digits)

### Step 2: Start Session With Cache ID

Include in your first message:

```
[Mode activation phrase]
Cache ID: [your_number]
[paste File Server URLs if needed]
```

**Example:**
```
Start Project Work Mode
Cache ID: 1730486400
```

### Step 3: Verify Confirmation

Claude should respond:

```
✅ [Mode Name] loaded.
✅ Cache ID: [your_number] registered.
   All file fetches will use cache-busting.
```

---

## 📋 Session Templates

### General Mode Session
```
Please load context
Cache ID: [run: date +%s]
```

### Learning Mode Session
```
Start SIMA Learning Mode
Cache ID: [run: date +%s]
```

### Project Mode Session
```
Start Project Work Mode
Cache ID: [run: date +%s]

[paste File Server URLs.md]
```

### Debug Mode Session
```
Start Debug Mode
Cache ID: [run: date +%s]
```

---

## 🔧 How It Works (Technical)

**Your File Server URLs (clean):**
```
https://claude.dizzybeaver.com/src/gateway.py
https://claude.dizzybeaver.com/sima/context/SESSION-START-Quick-Context.md
```

**Claude fetches with cache-busting (automatic):**
```
https://claude.dizzybeaver.com/src/gateway.py?v=1730486400
https://claude.dizzybeaver.com/sima/context/SESSION-START-Quick-Context.md?v=1730486400
```

**Result:**
- Your URL inventory stays clean (no maintenance burden)
- Claude adds `?v=timestamp` only when actually fetching
- Server ignores the parameter (serves normal file)
- Claude's cache treats it as a new URL (bypasses cache)
- You get fresh content every time ✅

---

## ⚡ Quick Start (30 Seconds)

**Terminal open? Run this:**

```bash
# Generate Cache ID
date +%s

# Copy the number

# Start session with:
"Start Project Work Mode
Cache ID: [paste number]"
```

**No terminal? Use this:**

```
# Pick random 6-digit number: 483921

# Start session:
"Start Project Work Mode
Cache ID: 483921"
```

---

## ❗ Common Issues

### Issue: "I forgot to provide Cache ID"

**Solution:** Claude will prompt you:
```
⚠️ Cache ID required for file fetching.

Please provide: Cache ID: [run: date +%s]
```

Just generate one and reply with it.

---

### Issue: "Do I need a new Cache ID every session?"

**Answer:** 
- **Yes** for maximum freshness
- **But:** Same Cache ID for multiple rapid sessions is OK (if files haven't changed)
- **Best practice:** New timestamp per session (takes 5 seconds)

---

### Issue: "Can I use the same number as last time?"

**Answer:**
- **Yes, technically** - but you might get cached content
- **Better:** Fresh timestamp each session
- **If files haven't changed:** Reusing is fine

---

### Issue: "My timestamp is too long/short"

**Answer:**
- Unix timestamps are 10 digits (1730486400)
- Too short? Use random 6-digit number instead (347829)
- Too long? Check your command syntax

---

## 🎯 Best Practices

**1. New timestamp per session:**
```bash
date +%s  # Run this fresh each time
```

**2. Keep File Server URLs clean:**
```
✅ https://claude.dizzybeaver.com/src/gateway.py
❌ https://claude.dizzybeaver.com/src/gateway.py?v=1730486400
```
Claude adds cache-busting automatically.

**3. Verify confirmation:**
```
✅ Cache ID: [number] registered.
```
If you don't see this, Claude might not have the protocol enabled yet.

**4. One Cache ID per session:**
- Don't change mid-session
- Claude stores it and reuses for all fetches
- Consistency = predictable behavior

**5. Document your Cache ID (optional):**
```
# 2025-11-02 - Session 1
Cache ID: 1730571840
Purpose: Implementing feature X
```
Helps track which session had which files.

---

## 📊 Benefits Summary

**Time Investment:**
- ~5 seconds to generate Cache ID
- ~2 seconds to paste in message
- **Total: 7 seconds per session**

**Benefits Received:**
- ✅ Always fresh file content
- ✅ No week-old code surprises
- ✅ Reliable development workflow
- ✅ No infrastructure changes needed
- ✅ No server-side configuration
- ✅ Works immediately

**ROI:**
- **Cost:** 7 seconds
- **Saves:** Hours of debugging cached content
- **Value:** Reliable, predictable development

---

## 🔍 Troubleshooting

### "Claude isn't using cache-busting"

**Check:**
1. Did you provide Cache ID?
2. Did Claude confirm receipt?
3. Are you using updated mode contexts?

**Solution:**
1. Verify mode context files updated (v3.2.0+)
2. Start fresh session with Cache ID
3. Check for confirmation message

---

### "I got old content even with Cache ID"

**Possible causes:**
1. Cache ID not actually applied
2. Server-side caching (different issue)
3. Wrong file URL

**Solution:**
1. Verify Claude confirms cache-busting active
2. Check URL is correct (from File Server URLs.md)
3. Try different Cache ID (fresh timestamp)

---

### "Do I need this for every mode?"

**Answer:** YES

All 4 modes enforce cache-busting:
- ✅ General Mode (questions, learning)
- ✅ Learning Mode (documentation)
- ✅ Project Mode (development)
- ✅ Debug Mode (troubleshooting)

**Why:** All modes fetch files. All need fresh content.

---

## 📚 Related Documentation

**For deeper understanding:**
- **WISD-06:** Technical wisdom entry on cache-busting technique
- **Cache-Busting-Integration-Guide.md:** Implementation details
- **Session context files:** See cache-busting sections in all 4 modes

**For implementation:**
- MODE-SELECTOR.md: Cache-busting protocol section
- Each mode context: Cache-busting requirement section

---

## 🎉 Success Checklist

You're using cache-busting correctly when:

- [ ] Generated fresh Cache ID before session
- [ ] Included it in first message
- [ ] Saw confirmation from Claude
- [ ] Claude mentions cache-busting when fetching
- [ ] Getting current file versions (check dates/versions)
- [ ] Development workflow reliable

---

## 💡 Pro Tips

**1. Alias the command:**
```bash
# Add to .bashrc or .zshrc
alias cacheid='date +%s'

# Now just run:
cacheid
```

**2. Store in clipboard:**
```bash
# Mac
date +%s | pbcopy

# Linux
date +%s | xclip

# Windows
date +%s | clip
```

**3. Template your prompts:**
```bash
# Create templates/project_mode.txt:
Start Project Work Mode
Cache ID: [REPLACE]

[paste File Server URLs]
```

**4. Use random numbers if simpler:**
```
Just type 6 random digits: 739284
Still bypasses cache, easier to remember
```

---

## 🚀 Quick Reference Card

```
┌──────────────────────────────────────┐
│     CACHE-BUSTING QUICK GUIDE       │
├──────────────────────────────────────┤
│                                      │
│ 1. Generate Cache ID:                │
│    $ date +%s                        │
│    → 1730486400                      │
│                                      │
│ 2. Start session:                    │
│    "Start [Mode]                     │
│     Cache ID: 1730486400"            │
│                                      │
│ 3. Verify:                           │
│    ✅ Cache ID registered            │
│                                      │
│ That's it! Cache-busting active.     │
│                                      │
└──────────────────────────────────────┘
```

---

**END OF USER GUIDE**

**Version:** 1.0.0  
**Estimated read time:** 5 minutes  
**Estimated setup time:** 30 seconds  
**Value:** Hours saved debugging cached content  
**Related:** WISD-06 (Session-Level Cache-Busting for AI Tool Constraints)
