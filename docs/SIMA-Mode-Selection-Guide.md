# SIMA-Mode-Selection-Guide.md

**Version:** 1.0.0  
**Purpose:** Interactive guide to choosing the right mode  
**Format:** Decision tree with examples

---

## 🎯 START HERE

**What do you want to accomplish?**

```
          ┌─────────────────┐
          │  What's your    │
          │  primary goal?  │
          └────────┬────────┘
                   │
        ┌──────────┼──────────┐
        ↓          ↓          ↓
   UNDERSTAND   CREATE    MAINTAIN
```

---

## 🧠 PATH 1: I WANT TO UNDERSTAND

### Decision Tree

```
"I want to understand something"
          ↓
    ┌─────────────┐
    │ What type?  │
    └──────┬──────┘
           │
     ┌─────┼─────┬─────┐
     ↓     ↓     ↓     ↓
   HOW   WHY  WHERE  CAN
```

### Scenarios → Mode

**"How does X work?"**
```
Example: "How does SUGA architecture work?"
Mode: GENERAL MODE
Command: "Please load context"
```

**"Why was X chosen?"**
```
Example: "Why no threading in Lambda?"
Mode: GENERAL MODE
Command: "Please load context"
```

**"Where is X documented?"**
```
Example: "Where can I find cache interface docs?"
Mode: GENERAL MODE
Command: "Please load context"
```

**"Can I do X?"**
```
Example: "Can I use threading locks?"
Mode: GENERAL MODE
Command: "Please load context"
```

### When NOT to use General Mode

❌ "Add a feature" → Use Project Mode  
❌ "Fix this bug" → Use Debug Mode  
❌ "Update index" → Use Maintenance Mode

---

## 🔨 PATH 2: I WANT TO CREATE

### Decision Tree

```
"I want to create something"
          ↓
    ┌─────────────┐
    │ Create what?│
    └──────┬──────┘
           │
     ┌─────┼─────┬─────┐
     ↓     ↓     ↓     ↓
   CODE  DOCS  PROJ  KNOW
```

### Scenarios → Mode

**"Code - Add feature to existing project"**
```
Example: "Add toggle action to lights interface"
Mode: PROJECT MODE
Command: "Start Project Mode for LEE"

Required:
✓ Know project name (LEE, SIMA, etc.)
✓ Have feature clearly defined
✓ File Server URLs.md uploaded
```

**"Code - Modify existing code"**
```
Example: "Update cache implementation"
Mode: PROJECT MODE
Command: "Start Project Mode for [PROJECT]"

Claude will:
✓ Fetch current file (via fileserver.php)
✓ Read complete code
✓ Make changes
✓ Output complete file artifact
```

**"Documentation - Add neural map entry"**
```
Example: "Document this bug I found"
Mode: LEARNING MODE
Command: "Start SIMA Learning Mode"

Claude will:
✓ Extract knowledge
✓ Check for duplicates
✓ Create entry as artifact
✓ Update indexes
```

**"Documentation - Modify SIMA structure"**
```
Example: "Create new platform directory"
Mode: PROJECT MODE
Command: "Start Project Mode for SIMA"

Note: SIMA is also a project!
```

**"Project - Brand new project"**
```
Example: "Create structure for weather API"
Mode: NEW PROJECT MODE
Command: "Start New Project Mode: WeatherAPI"

Claude will:
✓ Create directory structure
✓ Generate config files
✓ Create mode extensions
✓ Set up indexes
```

**"Knowledge - Extract patterns from experience"**
```
Example: "Document what I learned about Lambda"
Mode: LEARNING MODE
Command: "Start SIMA Learning Mode"

Best for:
✓ After completing a feature
✓ After fixing a bug
✓ After discovering pattern
```

### When NOT to use creation modes

❌ "Why does this error occur?" → Debug Mode  
❌ "Update indexes" → Maintenance Mode  
❌ "How do I...?" → General Mode

---

## 🔧 PATH 3: I WANT TO MAINTAIN

### Decision Tree

```
"I want to maintain something"
          ↓
    ┌─────────────┐
    │ Maintain   │
    │   what?    │
    └──────┬──────┘
           │
     ┌─────┼─────┬─────┐
     ↓     ↓     ↓     ↓
   FIX  ORGAN  CLEAN  UPDATE
```

### Scenarios → Mode

**"Fix - Bug in existing code"**
```
Example: "Lambda returning 500 error"
Mode: DEBUG MODE
Command: "Start Debug Mode for LEE"

Claude will:
✓ Check known bugs
✓ Analyze error
✓ Fetch current code
✓ Provide complete fix
```

**"Fix - Broken reference in docs"**
```
Example: "ARCH-DD reference not working"
Mode: DEBUG MODE
Command: "Start Debug Mode for SIMA"

Claude will:
✓ Identify issue
✓ Explain what's wrong
✓ Provide correct reference
```

**"Organize - Update indexes"**
```
Example: "Add new entries to lessons index"
Mode: MAINTENANCE MODE
Command: "Start SIMA Maintenance Mode"

Claude will:
✓ Scan for new entries
✓ Update indexes
✓ Verify all references
```

**"Clean - Remove outdated content"**
```
Example: "Find entries referencing deprecated DEC-24"
Mode: MAINTENANCE MODE
Command: "Start SIMA Maintenance Mode"

Claude will:
✓ Search for references
✓ Create update plan
✓ Provide migration path
```

**"Update - Verify cross-references"**
```
Example: "Check all BUG entries have valid REF-IDs"
Mode: MAINTENANCE MODE
Command: "Start SIMA Maintenance Mode"

Claude will:
✓ Verify all references
✓ Find broken links
✓ Fix issues
```

### When NOT to use maintenance modes

❌ "Add new feature" → Project Mode  
❌ "Extract knowledge" → Learning Mode  
❌ "Explain architecture" → General Mode

---

## 🎨 VISUAL DECISION TREE

```
                     START
                       │
          ┌────────────┼────────────┐
          │            │            │
      QUESTION?    CREATION?   MAINTENANCE?
          │            │            │
          ↓            │            │
     GENERAL MODE      │            │
                       │            │
              ┌────────┼────────┐   │
              │        │        │   │
           CODE?    DOCS?    PROJECT?
              │        │        │   │
              ↓        ↓        ↓   │
         PROJECT   LEARNING    NEW  │
          MODE      MODE      PROJECT
                               MODE  │
                                     │
                          ┌──────────┼──────────┐
                          │          │          │
                        FIX?    ORGANIZE?   CLEAN?
                          │          │          │
                          ↓          ↓          ↓
                       DEBUG    MAINTENANCE  MAINTENANCE
                        MODE        MODE        MODE
```

---

## 💡 BY TASK TYPE

### Learning Tasks
```
"How does X work?"                → General Mode
"Why was X designed that way?"    → General Mode
"Explain architecture pattern"    → General Mode
"Where can I find...?"            → General Mode
```

### Building Tasks
```
"Add feature X"                   → Project Mode (specify project)
"Modify function Y"               → Project Mode (specify project)
"Create interface Z"              → Project Mode (specify project)
"Update implementation"           → Project Mode (specify project)
```

### Documentation Tasks
```
"Document this pattern"           → Learning Mode
"Extract lessons from bug"        → Learning Mode
"Create decision entry"           → Learning Mode
"Record this wisdom"              → Learning Mode
```

### Organizing Tasks
```
"Update indexes"                  → Maintenance Mode
"Check for outdated entries"      → Maintenance Mode
"Verify cross-references"         → Maintenance Mode
"Clean deprecated content"        → Maintenance Mode
```

### Troubleshooting Tasks
```
"Fix this error"                  → Debug Mode (specify project)
"Why is X failing?"               → Debug Mode (specify project)
"Optimize performance"            → Debug Mode (specify project)
"Diagnose issue"                  → Debug Mode (specify project)
```

### Setup Tasks
```
"Create new project"              → New Project Mode
"Scaffold structure"              → New Project Mode
"Initialize project"              → New Project Mode
```

---

## 🎯 BY QUESTION TYPE

### "How" Questions

**"How do I...?"** → General Mode (guidance)  
**"How does X work?"** → General Mode (explanation)  
**"How to fix Y?"** → Debug Mode (troubleshooting)

### "Why" Questions

**"Why X not Y?"** → General Mode (decisions)  
**"Why is Z failing?"** → Debug Mode (diagnosis)  
**"Why was A chosen?"** → General Mode (rationale)

### "What" Questions

**"What is X?"** → General Mode (definition)  
**"What should I do?"** → General Mode (guidance)  
**"What's wrong?"** → Debug Mode (diagnosis)

### "Can I" Questions

**"Can I use X?"** → General Mode (quick answer)  
**"Can you add Y?"** → Project Mode (implementation)  
**"Can you fix Z?"** → Debug Mode (fixing)

### "Where" Questions

**"Where is X documented?"** → General Mode (navigation)  
**"Where should I put Y?"** → General Mode (guidance)

---

## 🚦 RED FLAGS (Wrong Mode Choice)

### 🔴 Using General Mode for Implementation

**Wrong:**
```
"Please load context"
"Add a toggle function to lights"
```

**Right:**
```
"Start Project Mode for LEE"
"Add a toggle function to lights"
```

### 🔴 Using Learning Mode for Building

**Wrong:**
```
"Start SIMA Learning Mode"
"Create a new interface"
```

**Right:**
```
"Start Project Mode for LEE"
"Create a new interface"
```

### 🔴 Using Project Mode for Questions

**Wrong:**
```
"Start Project Mode for LEE"
"How does caching work?"
```

**Right:**
```
"Please load context"
"How does caching work?"
```

### 🔴 Using Debug Mode for New Features

**Wrong:**
```
"Start Debug Mode for LEE"
"Add a new feature"
```

**Right:**
```
"Start Project Mode for LEE"
"Add a new feature"
```

### 🔴 Forgetting Project Name

**Wrong:**
```
"Start Project Mode"
"Add feature X"
```

**Right:**
```
"Start Project Mode for LEE"
"Add feature X"
```

---

## ✅ DECISION CHECKLIST

**Before choosing mode, answer:**

1. **Am I asking a question?**
   - Yes → General Mode
   - No → Continue

2. **Am I building something new?**
   - Yes → Is it a new project?
     - Yes → New Project Mode
     - No → Project Mode
   - No → Continue

3. **Am I fixing something broken?**
   - Yes → Debug Mode
   - No → Continue

4. **Am I documenting knowledge?**
   - Yes → Learning Mode
   - No → Continue

5. **Am I organizing existing content?**
   - Yes → Maintenance Mode
   - No → Back to question 1

---

## 🎓 EXAMPLE CONVERSATIONS

### Example 1: Clear Path

**User:** "I want to add a new device to LEE"

**Analysis:**
- Not a question → Skip General
- Building something → Project work
- Existing project (LEE) → Project Mode
- Know project name → Ready

**Correct Mode:** Project Mode for LEE

### Example 2: Ambiguous → Clarify

**User:** "I need help with caching"

**Too vague! Need clarification:**
- Understanding how it works? → General Mode
- Adding cache feature? → Project Mode
- Fixing cache bug? → Debug Mode
- Documenting cache pattern? → Learning Mode

**Ask user to clarify intent**

### Example 3: Multiple Tasks

**User:** "I want to add a feature and document what I learn"

**Two tasks = Two modes:**
1. First: "Start Project Mode for LEE" (build)
2. Then: "Start SIMA Learning Mode" (document)

**Do separately, not together**

---

## 🔄 MODE SWITCHING GUIDE

### When to Switch

**Scenario 1: Task changes**
```
Building feature → Hit bug → Need to debug
"Start Debug Mode for LEE"
```

**Scenario 2: Discovery**
```
Debugging → Found interesting pattern → Want to document
"Start SIMA Learning Mode"
```

**Scenario 3: Different project**
```
Working on LEE → Need to update SIMA docs
"Start Project Mode for SIMA"
```

### How to Switch

**Simple:**
1. Say new activation phrase
2. Wait for confirmation
3. Continue with new context

**No need to:**
- End previous mode explicitly
- Restart session
- Re-upload File Server URLs.md

---

## 📊 MODE SELECTION MATRIX

| I want to... | Question? | Building? | Fixing? | Organizing? | Mode |
|--------------|-----------|-----------|---------|-------------|------|
| Understand X | ✓ | | | | General |
| Add feature | | ✓ | | | Project |
| Fix bug | | | ✓ | | Debug |
| Document pattern | | | | | Learning |
| Update index | | | | ✓ | Maintenance |
| Create project | | ✓ | | | New Project |

---

## 🎯 CONFIDENCE LEVELS

### 🟢 High Confidence (Obvious Mode)

- "How does X work?" → General (100%)
- "Add feature to LEE" → Project Mode for LEE (100%)
- "Lambda returning 500 error" → Debug Mode for LEE (100%)
- "Update lessons index" → Maintenance Mode (100%)

### 🟡 Medium Confidence (Likely Mode)

- "Help with caching" → Probably General (80%)
- "Create new directory" → Probably Project Mode (80%)
- "Check references" → Probably Maintenance (80%)

### 🔴 Low Confidence (Need Clarification)

- "I need help" → What kind? (Ask user)
- "Work on project" → Which project? What work? (Ask user)
- "Something's wrong" → What's wrong? Where? (Ask user)

**When in doubt, ask for clarification!**

---

## 🆘 STILL UNSURE?

### Default Strategy

**If you can't decide:**

1. **Start with General Mode**
   ```
   "Please load context"
   [Explain what you want to do]
   ```

2. **Claude will guide you:**
   ```
   Claude: "For that task, you should use Project Mode for LEE.
            Say: 'Start Project Mode for LEE'"
   ```

3. **Switch to correct mode:**
   ```
   "Start Project Mode for LEE"
   ```

### Ask Claude

**It's okay to ask:**
```
"Please load context"
"I want to [task]. Which mode should I use?"
```

**Claude will recommend the best mode**

---

**REMEMBER:**
- When in doubt, start with General Mode
- Claude can guide you to the right mode
- It's easy to switch modes mid-session
- Each mode has clear purpose

---

**END OF MODE SELECTION GUIDE**

**Use this guide to choose confidently**
