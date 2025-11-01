# SIMA-Mode-Comparison-Guide.md

**Version:** 1.0.0  
**Date:** 2025-11-01  
**Purpose:** Complete comparison of all 4 SIMAv4 modes  
**Audience:** All SIMA users  
**Status:** Production Ready

---

## 📋 OVERVIEW

SIMAv4 uses a **mode-based context system** with 4 specialized modes. Each mode is optimized for specific tasks and loads different context files. This guide explains the differences and helps you choose the right mode.

**Key Principle:** One mode per session. To switch modes, end your current session and start a new one with a different activation phrase.

---

## 🎯 MODE COMPARISON SUMMARY

| Mode | Activation Phrase | Primary Use | Session Type |
|------|------------------|-------------|--------------|
| **General** | `"Please load context"` | Questions, learning | Understanding |
| **Learning** | `"Start SIMA Learning Mode"` | Document knowledge | Documentation |
| **Project** | `"Start Project Work Mode"` | Write code | Development |
| **Debug** | `"Start Debug Mode"` | Fix issues | Troubleshooting |

---

## 🔍 MODE 1: GENERAL PURPOSE MODE

### Activation
```
"Please load context"
```

### Context File
- **File:** `SESSION-START-Quick-Context.md`
- **Load Time:** 30-45 seconds
- **Size:** ~400 lines

### Purpose
Answer questions, provide guidance, explain architecture patterns.

### What It Does
- ✅ Answers questions about SUGA/SIMA architecture
- ✅ Provides guidance on patterns and best practices
- ✅ Cites REF-IDs for verifiable answers
- ✅ Routes queries using workflow patterns
- ✅ Checks instant answers first (top 10 common questions)
- ✅ Shows examples in artifacts (complete files only)

### Best For
- "How does X work?"
- "What is ARCH-01?"
- "Explain the SUGA pattern"
- "Show me the 12 interfaces"
- "Can I use threading locks?"
- Architecture exploration and learning
- Understanding design decisions

### Output Style
- **Explanations** with REF-ID citations
- **Code examples** in artifacts (when needed)
- **Workflow routing** to specific patterns
- **Quick answers** for common questions

### Verification
- RED FLAGS checklist
- Anti-pattern checks
- Citation requirements
- Artifact rules (complete files only)

---

## 📚 MODE 2: LEARNING MODE

### Activation
```
"Start SIMA Learning Mode"
```

### Context File
- **File:** `SIMA-LEARNING-SESSION-START-Quick-Context.md`
- **Load Time:** 45-60 seconds
- **Size:** ~500 lines

### Purpose
Extract knowledge from conversations and create neural map entries.

### What It Does
- ✅ Creates new neural map entries (LESS/BUG/DEC/WISD)
- ✅ Extracts patterns from conversations
- ✅ Genericizes content (removes project-specifics)
- ✅ Updates indexes and cross-references
- ✅ Ensures entries are ≤400 lines
- ✅ Checks for duplicates before creating
- ✅ Maintains SIMA v4 standards

### Best For
- "Document this lesson learned"
- "Create a LESS entry for this pattern"
- "Extract knowledge from our discussion"
- "Add this to the knowledge base"
- Building the knowledge base itself
- Converting project-specific knowledge to generic patterns

### Output Style
- **Neural map entries** as artifacts (LESS-##, BUG-##, DEC-##, WISD-##)
- **Index updates** for quick reference
- **Cross-reference updates** for navigation
- **Brief summaries** in chat (minimal)
- **Complete files** (never fragments)

### Quality Standards
- Generic (no project-specific details)
- Brief (≤400 lines per file)
- Separate files (never condense multiple topics)
- Filename in header
- Proper REF-ID format
- Complete cross-references

---

## 💻 MODE 3: PROJECT MODE

### Activation
```
"Start Project Work Mode"
```

### Context File
- **File:** `PROJECT-MODE-Context.md`
- **Load Time:** 30-45 seconds
- **Size:** ~450 lines

### Purpose
Active development, feature implementation, code modification.

### What It Does
- ✅ Implements all 3 SUGA layers (Gateway → Interface → Core)
- ✅ **ALWAYS fetches current files first** (LESS-01)
- ✅ Outputs **COMPLETE files** as artifacts (never snippets)
- ✅ Verifies with LESS-15 checklist (5-step verification)
- ✅ Follows strict implementation patterns
- ✅ Marks changes with comments (# ADDED:, # MODIFIED:)
- ✅ Minimal chat output (brief status updates only)

### Best For
- "Implement this feature"
- "Add a new interface"
- "Modify this function"
- "Create a new gateway wrapper"
- Active code development work
- Production code implementation

### Output Style
- **Complete source files** as artifacts
- **All existing code** + modifications
- **Change markers** (# ADDED:, # MODIFIED:)
- **Brief status** in chat only
- **Deployable code** (user can copy/paste immediately)

### Critical Rules
1. **ALWAYS fetch current file first** (no assumptions)
2. **ALWAYS include ALL existing code** (never fragments)
3. **ALWAYS output as artifacts** (never in chat)
4. **ALWAYS verify with LESS-15** before suggesting
5. **ALWAYS implement all 3 layers** (Gateway → Interface → Core)

### Verification (LESS-15)
```
[ ] Fetched current file?
[ ] Read complete file?
[ ] Implemented all 3 layers?
[ ] Followed SUGA pattern?
[ ] Checked RED FLAGS?
```

---

## 🐛 MODE 4: DEBUG MODE

### Activation
```
"Start Debug Mode"
```

### Context File
- **File:** `DEBUG-MODE-Context.md`
- **Load Time:** 30-45 seconds
- **Size:** ~400 lines

### Purpose
Systematic troubleshooting, root cause analysis, bug fixing.

### What It Does
- ✅ Checks known bugs first (BUG-01 to BUG-04)
- ✅ Uses systematic investigation (not guessing)
- ✅ Traces through SUGA layers
- ✅ Identifies root causes
- ✅ Provides complete fix artifacts
- ✅ Documents new bugs if needed
- ✅ Minimal chat (brief analysis, artifacts show fixes)

### Best For
- "Why is this failing?"
- "Debug this error"
- "Find the root cause"
- "This isn't working as expected"
- Systematic problem-solving
- Performance issues

### Output Style
- **Root cause analysis** (brief)
- **Complete fix** as artifact (full file)
- **Prevention strategy**
- **Related bugs** (if any)
- **Trace through layers** (Gateway → Interface → Core)

### Investigation Process
1. Check known bugs (BUG-01 to BUG-04)
2. Match error pattern
3. Trace through SUGA layers
4. Identify root cause
5. Provide complete fix
6. Document prevention

---

## 🔄 KEY DIFFERENCES TABLE

| Aspect | General | Learning | Project | Debug |
|--------|---------|----------|---------|-------|
| **Purpose** | Understand | Document | Build | Fix |
| **Outputs** | Explanations + citations | Neural map entries | Complete code files | Root cause + fix |
| **Code Output** | Examples (in artifacts) | None | Production code | Fixed code |
| **File Operations** | Read-only | Create/update neural maps | Create/modify source | Modify source |
| **Verification** | RED FLAGS check | Quality standards | LESS-15 (5 steps) | Known bugs check |
| **Session Duration** | Any length | 30-60 min typical | Variable | Until resolved |
| **Chat Verbosity** | Moderate | Minimal | Minimal | Brief analysis |
| **Primary Users** | Everyone | Knowledge creators | Developers | Troubleshooters |

---

## 🚦 QUICK DECISION GUIDE

**Use this flowchart to pick the right mode:**

```
What do you need to do?
│
├─ Understand something? ─────────────► General Mode
│   • How does X work?
│   • What is REF-ID Y?
│   • Explain pattern Z
│
├─ Document knowledge? ───────────────► Learning Mode
│   • Create LESS entry
│   • Extract pattern
│   • Add to knowledge base
│
├─ Write/modify code? ────────────────► Project Mode
│   • Implement feature
│   • Add interface
│   • Modify function
│
└─ Fix an error? ─────────────────────► Debug Mode
    • Error message
    • Not working
    • Performance issue
```

---

## ⚡ ACTIVATION QUICK REFERENCE

```
╔═══════════════════════════════════════╗
║     MODE ACTIVATION PHRASES           ║
╠═══════════════════════════════════════╣
║                                       ║
║ GENERAL MODE                          ║
║ "Please load context"                 ║
║ → Questions, learning                 ║
║                                       ║
║ LEARNING MODE                         ║
║ "Start SIMA Learning Mode"            ║
║ → Document knowledge                  ║
║                                       ║
║ PROJECT MODE                          ║
║ "Start Project Work Mode"             ║
║ → Write code                          ║
║                                       ║
║ DEBUG MODE                            ║
║ "Start Debug Mode"                    ║
║ → Fix issues                          ║
║                                       ║
╚═══════════════════════════════════════╝
```

---

## ⚠️ CRITICAL RULES (ALL MODES)

These rules apply **regardless of which mode** you're in:

### 1. Terminology
- **SUGA** = Lambda gateway architecture pattern (3 layers)
- **SIMA** = Neural maps documentation system
- **Never confuse these terms**

### 2. RED FLAGS (Never Suggest)
- [X] Threading locks (Lambda is single-threaded)
- [X] Direct core imports (always via gateway)
- [X] Bare except clauses (use specific exceptions)
- [X] Sentinel objects crossing boundaries
- [X] Heavy libraries without justification (128MB limit)
- [X] New subdirectories (flat structure except home_assistant/)
- [X] Skipping verification

### 3. Artifact Rules (SIMAv4)
- [OK] **Code ALWAYS in artifacts** (never in chat)
- [OK] **Complete files only** (never fragments)
- [OK] **Filename in header** (every artifact)
- [OK] **Files ≤400 lines** (neural maps)
- [OK] **Minimal chat output** (brief status only)
- [OK] **Verify encoding** (emojis, charts work)

### 4. Session Rules
- **One mode per session** (no mixing)
- **Explicit activation required** (exact phrase)
- **No mode switching mid-session** (end and restart)
- **Mode context is complete** (don't add extra rules)

---

## 📊 WHEN TO USE EACH MODE

### General Mode - Use When:
- ✅ You have a question about architecture
- ✅ You need to understand a pattern
- ✅ You want to explore the knowledge base
- ✅ You need guidance on best practices
- ✅ You want to verify a design decision
- ✅ You're learning the system

### Learning Mode - Use When:
- ✅ You discovered a new pattern
- ✅ You learned a valuable lesson
- ✅ You need to document a decision
- ✅ You want to create a neural map entry
- ✅ You need to update the knowledge base
- ✅ You're extracting knowledge from discussions

### Project Mode - Use When:
- ✅ You're implementing a feature
- ✅ You're modifying existing code
- ✅ You're adding a new interface
- ✅ You need production-ready code
- ✅ You're doing active development
- ✅ You need complete, deployable files

### Debug Mode - Use When:
- ✅ You have an error message
- ✅ Something isn't working
- ✅ You need root cause analysis
- ✅ Performance is degraded
- ✅ You're troubleshooting an issue
- ✅ You need systematic investigation

---

## 🎓 BEST PRACTICES

### Do:
- ✅ Choose the right mode for your task
- ✅ Use exact activation phrase
- ✅ Wait for mode to load completely (30-60s)
- ✅ Stay in one mode per session
- ✅ Follow mode-specific guidelines
- ✅ Check mode confirmation before proceeding

### Don't:
- ❌ Mix behaviors from different modes
- ❌ Try to switch modes mid-session
- ❌ Skip mode activation
- ❌ Ignore mode-specific verification
- ❌ Output code in chat (any mode)
- ❌ Create code fragments (always complete files)

---

## 🔗 RELATED DOCUMENTATION

**For More Information:**
- **User Guide:** `/sima/docs/SIMAv4-User-Guide.md`
- **Quick Start:** `/sima/docs/SIMAv4-Quick-Start-Guide.md`
- **Developer Guide:** `/sima/docs/SIMAv4-Developer-Guide.md`
- **Custom Instructions:** `/sima/context/Custom-Instructions.md`

**Mode Context Files:**
- General: `/sima/context/SESSION-START-Quick-Context.md`
- Learning: `/sima/context/SIMA-LEARNING-SESSION-START-Quick-Context.md`
- Project: `/sima/context/PROJECT-MODE-Context.md`
- Debug: `/sima/context/DEBUG-MODE-Context.md`

---

## ❓ FAQ

**Q: Can I switch modes mid-session?**  
A: No. End your session, start a new one, and use the new activation phrase.

**Q: What happens if I don't activate a mode?**  
A: The assistant will ask you to choose a mode or help you determine which mode fits your needs.

**Q: Can I use multiple modes for one task?**  
A: No, but you can complete the task in one mode, end the session, and use another mode for a different aspect.

**Q: Which mode is most common?**  
A: General Mode is most frequently used for questions and guidance.

**Q: Which mode for code examples?**  
A: General Mode for learning examples, Project Mode for production code.

**Q: How do I know which mode I'm in?**  
A: The mode confirmation message appears after activation (e.g., "[OK] General Mode loaded").

---

## ✅ SUCCESS CHECKLIST

You understand the mode system when you can:

- [ ] Name all 4 modes and their purposes
- [ ] Recite the activation phrase for each mode
- [ ] Explain when to use each mode
- [ ] Understand the key differences
- [ ] Know the rules that apply to all modes
- [ ] Choose the appropriate mode for a given task

---

**END OF GUIDE**

**Version:** 1.0.0  
**Date:** 2025-11-01  
**Status:** Production Ready  
**Next Review:** Quarterly
