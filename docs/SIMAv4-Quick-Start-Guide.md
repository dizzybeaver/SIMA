# File: SIMAv4-Quick-Start-Guide.md

**Version:** 1.0.0  
**Date:** 2025-10-29  
**Purpose:** Fast-track guide to using SIMAv4 in 15 minutes  
**Audience:** Users who want to start immediately  
**Status:** Production Ready

---

## ⚡ GET STARTED IN 15 MINUTES

This guide gets you productive with SIMAv4 in just 15 minutes. For comprehensive information, see the [User Guide](SIMAv4-User-Guide.md).

---

## 🎯 WHAT IS SIMAv4?

**In 30 seconds:**

SIMAv4 is a knowledge base for software architecture patterns, design decisions, and lessons learned. It helps you:
- Find answers fast (< 2 seconds)
- Document knowledge properly
- Write better code
- Debug systematically

**Key concept:** Use specialized **modes** for different tasks.

---

## 🚀 QUICK START: 3 STEPS

### Step 1: Understand Modes (2 minutes)

SIMAv4 has 4 modes. Pick one based on your task:

| Mode | When | Phrase |
|------|------|--------|
| **General** | Questions, learning | `"Please load context"` |
| **Learning** | Document knowledge | `"Start SIMA Learning Mode"` |
| **Project** | Write code | `"Start Project Work Mode"` |
| **Debug** | Fix issues | `"Start Debug Mode"` |

**Rule:** One mode per session. To switch, end session and start new one.

### Step 2: Try General Mode (5 minutes)

**Open Claude and say:**

```
Please load context
```

**Wait 30-60 seconds for loading.**

**When loaded, try these:**

```
Explain the SUGA pattern
```

```
What are the 12 interfaces?
```

```
Show me ARCH-01
```

**✅ Success!** You can now find information.

### Step 3: Print Quick Reference Cards (3 minutes)

Go to `/sima/support/quick-reference/` and print:

1. **QRC-01: Interfaces Overview**
   - All 12 interfaces
   - When to use each
   - Keep at your desk!

2. **QRC-02: Gateway Patterns**
   - 5 gateway patterns
   - Implementation tips

3. **QRC-03: Common Patterns**
   - Most used patterns
   - Anti-patterns to avoid

**✅ You're ready!** The rest comes with practice.

---

## 📚 CORE CONCEPTS (5 minutes)

### Terminology

**CRITICAL:** Don't confuse these!

- **SIMA** = Documentation system (this!)
- **SUGA** = Lambda architecture pattern (different thing!)

### Entry Types

| Type | Format | Example | What It Is |
|------|--------|---------|------------|
| ARCH | ARCH-## | ARCH-01 | Architecture patterns |
| GATE | GATE-## | GATE-01 | Gateway patterns |
| INT | INT-## | INT-01 | Interface patterns |
| LANG-PY | LANG-PY-## | LANG-PY-03 | Python patterns |
| NMP | NMP##-CODE-## | NMP01-LEE-02 | Project-specific |

**Where they live:**
- Generic patterns → `/sima/entries/`
- Project-specific → `/sima/nmp/`

### Finding Information

**3 ways:**

1. **By REF-ID** (fastest):
   ```
   Show me INT-06
   ```

2. **By keyword** (flexible):
   ```
   Find entries about caching
   ```

3. **By cross-reference** (most powerful):
   - Read an entry
   - Follow "Related To" or "Used In" links
   - Explore the knowledge graph

---

## 🎮 TRY EACH MODE (5 minutes total)

### General Mode (Already tried! ✓)

**Use for:**
- "How does [pattern] work?"
- "Why do we use [technique]?"
- "What's the difference between X and Y?"

### Learning Mode (Try now - 2 min)

**End current session, start new, say:**

```
Start SIMA Learning Mode
```

**Try documenting:**

```
Document a lesson: Always validate config before using
```

**Watch it:**
1. Check for duplicates
2. Create proper entry
3. Add cross-references

### Project Mode (Try next - 2 min)

**End session, start new, say:**

```
Start Project Work Mode
```

**Try requesting:**

```
Add a cache operation to check if key exists
```

**Watch it:**
1. Fetch current files
2. Implement all 3 layers
3. Output complete artifacts
4. Verify with checklist

### Debug Mode (Try last - 1 min)

**End session, start new, say:**

```
Start Debug Mode
```

**Try debugging:**

```
Lambda function returns: "Config not initialized"
```

**Watch it:**
1. Check known bugs
2. Trace through layers
3. Find root cause
4. Provide fix + prevention

---

## ✅ ESSENTIAL RULES (2 minutes)

### The 4 Golden Rules

1. **Search First** - Always check existing entries before asking
2. **Read Complete Sections** - Don't skim, follow cross-references
3. **Cite REF-IDs** - Make answers verifiable
4. **Verify Before Implementing** - Use appropriate checklist

### RED FLAGS - Never Suggest

- ❌ Threading locks (Lambda is single-threaded)
- ❌ Direct core imports (always via gateway)
- ❌ Bare `except:` clauses (use specific exceptions)
- ❌ Heavy libraries without justification (128MB limit)
- ❌ New subdirectories (flat structure except home_assistant/)

### Code Output Rules

**ALWAYS:**
- ✅ Complete files in artifacts (never fragments)
- ✅ All existing code + modifications
- ✅ Changes marked with comments
- ✅ Deployable, copy-paste ready

**NEVER:**
- ❌ Code in chat (always artifacts)
- ❌ Partial snippets
- ❌ "Add this to line X" instructions

---

## 🛠️ ESSENTIAL TOOLS

### Workflows (Use as Checklists)

Located in `/sima/support/workflows/`:

1. **Add Feature** - Implementing new functionality
2. **Debug Issue** - Troubleshooting problems
3. **Update Interface** - Modifying interface functions
4. **Add Gateway Function** - Creating gateway wrappers
5. **Create NMP Entry** - Documenting project code

**How to use:** Follow steps, check boxes, verify at end.

### Quick Search

**By REF-ID:**
```
Show me [REF-ID]
Find ARCH-01
```

**By keyword:**
```
Find entries about [topic]
Search for lazy loading
```

**By problem:**
```
Use Quick Index:
"Need caching?" → INT-01
"Need logging?" → INT-06
```

---

## 💡 COMMON SCENARIOS

### "I need to understand the architecture"

1. Activate: `"Please load context"`
2. Ask: `"Explain SUGA architecture"`
3. Ask: `"What are the 12 interfaces?"`
4. Explore: Follow cross-references

### "I want to add a feature"

1. Activate: `"Start Project Work Mode"`
2. Use: Workflow-01 (Add Feature)
3. Request: Describe the feature
4. Review: Check the artifacts
5. Verify: Use LESS-15 checklist

### "Something's broken"

1. Activate: `"Start Debug Mode"`
2. Use: Workflow-02 (Debug Issue)
3. Describe: The issue symptoms
4. Follow: Systematic investigation
5. Implement: Fix + prevention

### "I learned something important"

1. Activate: `"Start SIMA Learning Mode"`
2. Use: Workflow-05 (Create NMP Entry)
3. Document: The lesson learned
4. Verify: No duplicates, proper format

---

## 🎓 NEXT STEPS

### Now that you can use SIMAv4:

**Day 1:**
- ✅ Use General Mode to explore
- ✅ Ask 5-10 questions
- ✅ Follow cross-references
- ✅ Get comfortable with search

**Day 2:**
- ✅ Try Learning Mode
- ✅ Document 1-2 lessons
- ✅ Practice entry creation

**Day 3:**
- ✅ Try Project Mode
- ✅ Request a simple change
- ✅ Review artifact output

**Day 4:**
- ✅ Try Debug Mode
- ✅ Debug a known issue
- ✅ Follow systematic process

**Week 2:**
- ✅ Use all 4 modes regularly
- ✅ Create quality entries
- ✅ Help teammates learn

### Resources

**Keep handy:**
- [ ] Quick Reference Cards (printed)
- [ ] Mode activation cheat sheet
- [ ] REF-ID directory
- [ ] Workflow templates
- [ ] User Guide (for details)

**Bookmark:**
- User Guide: `SIMAv4-User-Guide.md`
- Developer Guide: `SIMAv4-Developer-Guide.md`
- Training Materials: `SIMAv4-Training-Materials.md`

---

## ❓ QUICK FAQ

### Mode System

**Q: Do I have to use modes?**  
A: Yes! They optimize context and behavior for your task.

**Q: Can I switch modes mid-session?**  
A: No. End session, start new one, activate new mode.

**Q: Which mode for "How does X work?"**  
A: General Mode.

**Q: Which mode for "Build Y"?**  
A: Project Mode.

### Entries

**Q: What's the difference between ARCH and NMP?**  
A: ARCH is generic pattern, NMP is project-specific implementation.

**Q: How do I find an entry?**  
A: Three ways: REF-ID lookup, keyword search, or cross-references.

**Q: Can I edit entries?**  
A: Yes, in Learning Mode. Always increment version number.

### Code

**Q: Why does code go in artifacts?**  
A: To provide complete, deployable files (never fragments).

**Q: Can I get code in chat?**  
A: No. Code always in artifacts, complete files only.

**Q: What's LESS-15?**  
A: 5-step verification checklist in Project Mode.

---

## 🎯 SUCCESS CHECKLIST

You're successful when you can:

- [ ] Activate any of the 4 modes
- [ ] Find entries by REF-ID
- [ ] Search by keywords
- [ ] Follow cross-references
- [ ] Read Quick Reference Cards
- [ ] Know when to use each mode
- [ ] Avoid RED FLAGS
- [ ] Use workflows as checklists
- [ ] Create quality entries
- [ ] Help others learn

---

## 🚨 GETTING HELP

### During Session

```
"I need help with [specific issue]"
"Show me examples of [pattern]"
"What's the workflow for [task]?"
```

### Between Sessions

- Review User Guide
- Check Quick Reference Cards
- Watch video tutorials
- Read workflow templates
- Ask teammates

### Common Issues

**Mode won't load:**
- Check File Server URLs uploaded
- Verify activation phrase exact
- Try again (may be temporary)

**Can't find entry:**
- Try different keywords
- Use REF-ID if known
- Check Quick Indexes

**Getting mixed behaviors:**
- End current session
- Start fresh
- Use exact activation phrase

---

## 📖 REFERENCE: MODE ACTIVATIONS

```
╔════════════════════════════════════════╗
║          QUICK REFERENCE               ║
╠════════════════════════════════════════╣
║                                        ║
║ GENERAL MODE                           ║
║ "Please load context"                  ║
║ → Questions, learning                  ║
║                                        ║
║ LEARNING MODE                          ║
║ "Start SIMA Learning Mode"             ║
║ → Document knowledge                   ║
║                                        ║
║ PROJECT MODE                           ║
║ "Start Project Work Mode"              ║
║ → Write code                           ║
║                                        ║
║ DEBUG MODE                             ║
║ "Start Debug Mode"                     ║
║ → Fix issues                           ║
║                                        ║
╚════════════════════════════════════════╝
```

---

## 📊 ENTRY TYPES

```
╔════════════════════════════════════════╗
║         ENTRY TYPE GUIDE               ║
╠════════════════════════════════════════╣
║                                        ║
║ ARCH-##  → Architecture patterns       ║
║ GATE-##  → Gateway patterns            ║
║ INT-##   → Interface patterns          ║
║ LANG-PY-## → Python patterns           ║
║ NMP##-CODE-## → Project-specific       ║
║                                        ║
║ Generic → /sima/entries/               ║
║ Project → /sima/nmp/                   ║
║                                        ║
╚════════════════════════════════════════╝
```

---

## 🔍 SEARCH METHODS

```
╔════════════════════════════════════════╗
║          SEARCH GUIDE                  ║
╠════════════════════════════════════════╣
║                                        ║
║ BY REF-ID (fastest):                   ║
║ "Show me ARCH-01"                      ║
║                                        ║
║ BY KEYWORD (flexible):                 ║
║ "Find entries about caching"           ║
║                                        ║
║ BY CROSS-REFERENCE (powerful):         ║
║ Read entry → Follow links              ║
║                                        ║
║ BY PROBLEM (quick):                    ║
║ Use Quick Indexes                      ║
║                                        ║
╚════════════════════════════════════════╝
```

---

## ✅ DAILY USAGE PATTERN

```
Morning:
1. Open Claude
2. Activate appropriate mode
3. Review today's tasks
4. Use workflows as checklists

During Work:
1. Search before asking
2. Follow workflows
3. Create artifacts properly
4. Verify with checklists

End of Day:
1. Document learnings (Learning Mode)
2. Update relevant entries
3. Add cross-references
4. End session

Weekly:
1. Review entries created
2. Check for duplicates
3. Improve documentation
4. Share learnings
```

---

## 🎯 REMEMBER

**Most Important:**
1. One mode per session
2. Search first, always
3. Complete files in artifacts
4. Follow workflows
5. Avoid RED FLAGS

**You'll master it with practice!**

---

## 📝 VERSION HISTORY

**v1.0.0 (2025-10-29)**
- Initial quick start guide
- 15-minute fast-track path
- Essential concepts only
- Common scenarios covered
- Quick reference charts included
- Production-ready

---

**END OF QUICK START GUIDE**

**Status:** Production Ready  
**Duration:** 15 minutes to productivity  
**Next:** User Guide for comprehensive information  
**Support:** Training materials and video tutorials available

**Welcome to SIMAv4! 🚀**
