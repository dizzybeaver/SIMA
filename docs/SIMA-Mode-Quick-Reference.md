# SIMA-Mode-Quick-Reference.md

**Version:** 1.0.0  
**Purpose:** One-page reference for mode activation and selection  
**Print:** 1 page, keep visible during sessions

---

## 🎯 ACTIVATION COMMANDS

```
┌─────────────────────────────────────────────────────────┐
│ ALWAYS START WITH:                                      │
│ [Upload File Server URLs.md]                            │
└─────────────────────────────────────────────────────────┘

MODE 1: GENERAL          "Please load context"
MODE 2: LEARNING         "Start SIMA Learning Mode"
MODE 3: MAINTENANCE      "Start SIMA Maintenance Mode"
MODE 4: PROJECT          "Start Project Mode for [PROJECT]"
MODE 5: DEBUG            "Start Debug Mode for [PROJECT]"
MODE 6: NEW PROJECT      "Start New Project Mode: [NAME]"
```

---

## 🔀 MODE SELECTION FLOWCHART

```
START
  ↓
  Question? ────────────────→ General Mode
  ↓
  Extract knowledge? ────────→ Learning Mode
  ↓
  Update/organize? ──────────→ Maintenance Mode
  ↓
  Build feature? ────────────→ Project Mode (specify project)
  ↓
  Fix bug? ──────────────────→ Debug Mode (specify project)
  ↓
  New project? ──────────────→ New Project Mode (name it)
```

---

## ⚡ COMMON SCENARIOS

| I want to... | Use this mode | Example |
|--------------|---------------|---------|
| Understand how X works | General | "Please load context" |
| Add a feature to LEE | Project (LEE) | "Start Project Mode for LEE" |
| Fix LEE Lambda error | Debug (LEE) | "Start Debug Mode for LEE" |
| Document a pattern | Learning | "Start SIMA Learning Mode" |
| Update neural map indexes | Maintenance | "Start SIMA Maintenance Mode" |
| Create new project | New Project | "Start New Project Mode: MyApp" |
| Modify SIMA structure | Project (SIMA) | "Start Project Mode for SIMA" |

---

## 🎨 MODE CHARACTERISTICS

```
┌──────────────┬──────────┬─────────────┬──────────────┐
│ Mode         │ Load     │ Output      │ Best For     │
├──────────────┼──────────┼─────────────┼──────────────┤
│ General      │ 20-30s   │ Answers     │ Learning     │
│ Learning     │ 45-60s   │ Entries     │ Documenting  │
│ Maintenance  │ 30-45s   │ Indexes     │ Organizing   │
│ Project      │ 35-50s   │ Code        │ Building     │
│ Debug        │ 35-50s   │ Fixes       │ Fixing       │
│ New Project  │ 30-45s   │ Structure   │ Starting     │
└──────────────┴──────────┴─────────────┴──────────────┘
```

---

## ✅ SESSION CHECKLIST

```
Before Starting:
[ ] File Server URLs.md uploaded
[ ] Know which mode needed
[ ] Have clear task/question

During Session:
[ ] Wait for mode confirmation
[ ] Work on single focus
[ ] Switch modes if task changes

After Session:
[ ] Document learnings (Learning Mode)
[ ] Update indexes if needed (Maintenance)
```

---

## 🚫 COMMON MISTAKES

❌ Forgetting to upload File Server URLs.md  
✅ Always upload first

❌ "Start Project Mode" (no project)  
✅ "Start Project Mode for LEE"

❌ Mixing mode purposes  
✅ One mode = one type of work

❌ Not waiting for confirmation  
✅ Wait for "ready" message

---

## 🔄 MODE SWITCHING

**You can switch modes mid-session:**

```
"Start Project Mode for LEE"    # Work on LEE
[...some work...]
"Start Debug Mode for LEE"      # Fix an issue
[...debugging...]
"Start SIMA Learning Mode"      # Document pattern
```

**Each switch reloads appropriate context**

---

## 📞 PROJECT NAMES

**Current projects:**
- **LEE** - Lambda Execution Engine (Home Assistant)
- **SIMA** - Neural maps documentation system

**Your projects:**
- Create with: "Start New Project Mode: [NAME]"
- Use with: "Start Project Mode for [NAME]"

---

## 🎯 SUCCESS PATTERNS

**Pattern 1: Learn → Build → Document**
```
1. General Mode      (understand architecture)
2. Project Mode      (implement feature)
3. Learning Mode     (capture patterns)
```

**Pattern 2: Build → Break → Fix → Learn**
```
1. Project Mode      (build feature)
2. Debug Mode        (fix issue)
3. Learning Mode     (document bug)
```

**Pattern 3: Regular Maintenance**
```
Weekly:    Maintenance Mode (update indexes)
Monthly:   Maintenance Mode (clean deprecated)
```

---

## 🆘 QUICK HELP

**Mode won't activate?**
1. Re-upload File Server URLs.md
2. Use EXACT activation phrase
3. Wait for confirmation

**Wrong project context?**
1. Specify project name: "for LEE"
2. Check project exists
3. Create if needed: New Project Mode

**Need different mode?**
1. Just say new activation phrase
2. Wait for new mode to load
3. Continue work

---

## 📚 DETAILED DOCS

**Full examples:** SIMA-Context-System-Usage-Examples.md  
**Mode details:** MODE-SELECTOR.md  
**Architecture:** SIMA-Context-System-Optimization-Plan.md

---

**REMEMBER:**
1. Upload File Server URLs.md FIRST
2. Say activation phrase EXACTLY
3. Wait for "ready" confirmation
4. One mode = one focus
5. Switch modes as needed

---

**Print this page → Keep visible → Reference during sessions**

**END OF QUICK REFERENCE**
