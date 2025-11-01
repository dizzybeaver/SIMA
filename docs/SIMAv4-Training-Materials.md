# File: SIMAv4-Training-Materials.md

**Version:** 1.0.0  
**Date:** 2025-10-29  
**Purpose:** Complete training materials and video tutorial scripts  
**Audience:** Trainers, new users, team leads  
**Status:** Production Ready

---

## 📖 TABLE OF CONTENTS

### Part 1: Training Materials
1. [Training Program Overview](#training-program-overview)
2. [Session 1: Introduction to SIMAv4](#session-1-introduction)
3. [Session 2: Mode-Based Workflow](#session-2-modes)
4. [Session 3: Working with Entries](#session-3-entries)
5. [Session 4: Support Tools](#session-4-tools)
6. [Session 5: Hands-On Practice](#session-5-practice)

### Part 2: Video Tutorial Scripts
7. [Video 1: Quick Start Guide](#video-1-quick-start)
8. [Video 2: Mode System Deep Dive](#video-2-modes)
9. [Video 3: Entry System](#video-3-entries)
10. [Video 4: Search and Navigation](#video-4-search)
11. [Video 5: Common Workflows](#video-5-workflows)

### Part 3: Resources
12. [Training Cheat Sheets](#training-cheat-sheets)
13. [Practice Exercises](#practice-exercises)
14. [Assessment Quizzes](#assessment-quizzes)

---

# PART 1: TRAINING MATERIALS

## 🎓 TRAINING PROGRAM OVERVIEW

### Program Structure

**Total Duration:** 6-8 hours (can be split across days)

**Format Options:**
- **Intensive:** Full day training (8 hours with breaks)
- **Extended:** 5 sessions of 90 minutes each
- **Self-Paced:** Online materials with practice exercises

**Target Audience:**
- New users starting with SIMAv4
- Existing users migrating from v3
- Team members needing refresher

### Learning Objectives

By the end of training, participants will be able to:
1. Understand SIMAv4 architecture and benefits
2. Activate and use all 4 modes effectively
3. Find and read entries using search tools
4. Create new entries following standards
5. Use support tools (workflows, checklists)
6. Apply best practices and avoid anti-patterns

### Prerequisites

**Required:**
- Access to Claude AI (Sonnet 4.5+)
- File Server URLs document
- Basic understanding of knowledge management

**Recommended:**
- Familiarity with markdown
- Experience with previous SIMA version (if migrating)
- Understanding of software architecture concepts

### Materials Provided

**Each Participant Gets:**
- [ ] User Guide (PDF)
- [ ] Quick Start Guide (PDF)
- [ ] Mode activation cheat sheet (printed)
- [ ] REF-ID directory (printed)
- [ ] Quick reference cards (printed)
- [ ] Practice exercises (digital)
- [ ] Access to training project

---

## 📚 SESSION 1: INTRODUCTION TO SIMAv4 {#session-1-introduction}

**Duration:** 60 minutes  
**Format:** Presentation + Demo  
**Materials:** Slides, User Guide

### Session Outline

**1. Welcome and Introductions (10 min)**
- Trainer introduction
- Participant introductions
- Training objectives
- Schedule overview
- Questions

**2. What is SIMAv4? (15 min)**

**Talking Points:**
- Knowledge management system for software architecture
- Documents patterns, decisions, lessons learned
- Hierarchical organization (Gateway → Category → Topic → Individual)
- AI-powered search and retrieval
- Multi-project support

**Key Concepts to Cover:**
- **SIMA:** Documentation system (this)
- **SUGA:** Lambda architecture pattern (different!)
- **Neural Maps:** Knowledge entries
- **REF-IDs:** Unique entry identifiers
- **Modes:** Specialized contexts for different tasks

**Demo:**
Show the directory structure in File Server URLs

**3. Why SIMAv4? Benefits and Improvements (15 min)**

**Present Benefits:**
- **Organization:** Clean separation of generic and project-specific
- **Speed:** Faster context loading with modes (30-60s)
- **Navigation:** Enhanced cross-references and indexes
- **Tools:** Comprehensive workflows and checklists
- **Scale:** Multi-project support
- **Quality:** Better validation and testing

**Show Before/After:**
- v3 vs v4 structure comparison
- v3 vs v4 workflow comparison
- v3 vs v4 search comparison

**4. Architecture Overview (15 min)**

**Present Components:**

```
User (You)
    ↓
Mode System (4 specialized contexts)
    ↓
Entry System (Knowledge base)
    ↓
Search System (Find information)
    ↓
Support Tools (Workflows, checklists)
```

**Explain Hierarchy:**

```
Gateway (NM##): Top-level category
  └─ Category: Group of topics
      └─ Topic: Specific subject
          └─ Entry: Atomic knowledge
```

**Examples:**
- NM01 (Architecture) → CoreArchitecture → SUGA → ARCH-01
- NM01 (Architecture) → InterfacesCore → Cache → INT-01

**5. Q&A and Setup (5 min)**

**Verify:**
- [ ] Everyone has Claude access
- [ ] Everyone has File Server URLs
- [ ] Everyone understands basic concepts
- [ ] Everyone ready for Session 2

### Session 1 Handouts

**Provide:**
1. Architecture diagram (printed)
2. Hierarchy example (printed)
3. SIMA vs SUGA comparison (printed)

---

## 🎮 SESSION 2: MODE-BASED WORKFLOW {#session-2-modes}

**Duration:** 90 minutes  
**Format:** Interactive demonstration + Practice  
**Materials:** Mode cheat sheet, practice scenarios

### Session Outline

**1. Mode System Introduction (15 min)**

**Explain Concept:**
- Different tasks need different context
- One mode per session
- Explicit activation required
- Can't mix modes

**Present 4 Modes:**

| Mode | Phrase | Use Case |
|------|--------|----------|
| General | "Please load context" | Questions, learning |
| Learning | "Start SIMA Learning Mode" | Document knowledge |
| Project | "Start Project Work Mode" | Write code |
| Debug | "Start Debug Mode" | Fix issues |

**2. General Purpose Mode (15 min)**

**Demo Live:**

```
Trainer: "Please load context"
[Wait for mode to load]
Claude: "✅ General Purpose Mode loaded..."

Trainer: "Explain the SUGA pattern"
[Show how Claude searches and responds]

Trainer: "What's the cache interface?"
[Show another search and response]
```

**Participants Practice:**
- Each person activates General Mode
- Each asks 1-2 questions
- Group shares results

**Common Questions to Try:**
- "Explain SUGA pattern"
- "What are the 12 interfaces?"
- "What's lazy loading?"
- "How does cross-interface communication work?"

**3. Learning Mode (15 min)**

**Demo Live:**

```
Trainer: [End session, start new]
Trainer: "Start SIMA Learning Mode"
[Wait for mode to load]
Claude: "✅ Learning Mode loaded..."

Trainer: "I want to document a lesson about caching"
[Show how Claude checks for duplicates]
[Show how Claude creates entry]
```

**Participants Practice:**
- End current session
- Start new session
- Activate Learning Mode
- Try documenting a simple lesson

**Practice Exercise:**
"Document a lesson: Always validate input before processing"

**4. Project Work Mode (20 min)**

**Demo Live:**

```
Trainer: [End session, start new]
Trainer: "Start Project Work Mode"
[Wait for mode to load]
Claude: "✅ Project Mode loaded..."

Trainer: "Add a cache get operation"
[Show how Claude fetches files]
[Show how Claude implements all layers]
[Show complete artifact output]
```

**Participants Practice:**
- End current session
- Start new session
- Activate Project Mode
- Try requesting a simple code change

**Practice Exercise:**
"Add a logging function that logs with timestamp"

**5. Debug Mode (15 min)**

**Demo Live:**

```
Trainer: [End session, start new]
Trainer: "Start Debug Mode"
[Wait for mode to load]
Claude: "✅ Debug Mode loaded..."

Trainer: "Lambda is slow on first request"
[Show how Claude checks known bugs]
[Show systematic investigation]
[Show root cause + fix]
```

**Participants Practice:**
- End current session
- Start new session
- Activate Debug Mode
- Try debugging a simple issue

**Practice Exercise:**
"Debug: Function returns None unexpectedly"

**6. Mode Selection Practice (10 min)**

**Present Scenarios:**

For each scenario, participants vote on which mode:

1. "I need to understand the gateway pattern" → ?
2. "I want to add a new feature" → ?
3. "I have an error I can't figure out" → ?
4. "I learned something important today" → ?
5. "How do I structure my code?" → ?

**Answers:**
1. General Mode
2. Project Mode
3. Debug Mode
4. Learning Mode
5. General Mode

**7. Review and Q&A (10 min)**

**Key Takeaways:**
- One mode per session
- Explicit activation phrase required
- Choose mode based on task
- Can't switch mid-session

**Common Pitfalls:**
- Forgetting to activate mode
- Using wrong activation phrase
- Trying to mix modes
- Not ending session before switching

### Session 2 Handouts

**Provide:**
1. Mode activation cheat sheet (printed)
2. Mode selection decision tree (printed)
3. Practice scenarios (digital)

---

## 📖 SESSION 3: WORKING WITH ENTRIES {#session-3-entries}

**Duration:** 90 minutes  
**Format:** Presentation + Practice  
**Materials:** Entry examples, templates

### Session Outline

**1. Entry System Overview (15 min)**

**Present Entry Types:**

| Type | Format | Purpose | Example |
|------|--------|---------|---------|
| ARCH | ARCH-## | Architecture | ARCH-01 (SUGA) |
| GATE | GATE-## | Gateway | GATE-01 (Three-File) |
| INT | INT-## | Interface | INT-01 (Cache) |
| LANG | LANG-PY-## | Language | LANG-PY-03 (Exceptions) |
| NMP | NMP##-CODE-## | Project | NMP01-LEE-02 (Cache Impl) |

**Explain Distinction:**
- **Base Entries** (entries/): Generic, reusable patterns
- **Project NMPs** (nmp/): Project-specific implementations

**2. Reading Entries (15 min)**

**Demo Entry Structure:**

Show sample entry and walk through:

```markdown
# File: ARCH-01-SUGA-Pattern.md  ← Filename
**REF-ID:** ARCH-01  ← Identifier
**Category:** Core/Architecture/SUGA  ← Hierarchy
**Version:** 2.0.0  ← Version number

**Inherits From:** -  ← Parent entries
**Related To:** ARCH-02, GATE-01  ← Sibling entries
**Used In:** NMP01-LEE-*  ← Implementations

## Overview  ← What it covers
## Core Content  ← Main information
## Examples  ← Practical examples
## Cross-References  ← Relationships
```

**Participants Practice:**
- Load SESSION-START-Quick-Context.md
- Search for and read ARCH-01
- Identify key sections
- Follow a cross-reference

**3. Understanding Cross-References (20 min)**

**Explain Relationships:**

**Inherits From (Parent-Child):**
```
Child extends parent
Example: NMP01-LEE-02 inherits from INT-01
Meaning: Project cache implements base cache interface
```

**Related To (Siblings):**
```
Entries share common themes
Example: GATE-01 related to GATE-02
Meaning: Both about gateway optimization
```

**Used In (Generic-to-Specific):**
```
Shows where pattern applied
Example: ARCH-01 used in NMP01-LEE-15
Meaning: SUGA pattern used in this project function
```

**Demo Navigation:**
- Start at ARCH-01
- Follow "Used In" to NMP01-LEE-15
- Follow "Inherits From" back to ARCH-01
- Follow "Related To" to ARCH-02

**Participants Practice:**
- Pick an entry
- Map its relationships
- Navigate the graph
- Share findings

**4. Creating Entries (20 min)**

**Present Process:**

1. Activate Learning Mode
2. Check for duplicates
3. Choose entry type
4. Use template
5. Fill all sections
6. Add cross-references
7. Validate

**Demo Entry Creation:**

Live creation of simple lesson:

```
Trainer: "Start SIMA Learning Mode"
Trainer: "Create a lesson about input validation"
[Show duplication check]
[Show entry creation]
[Show cross-reference addition]
```

**Participants Practice:**
- Activate Learning Mode
- Create a simple lesson
- Add cross-references
- Validate structure

**Practice Exercise:**
"Document: Always log errors before raising"

**5. Entry Quality Standards (10 min)**

**Present Standards:**

**Structure:**
- Filename in header (mandatory)
- REF-ID unique and formatted
- All required sections
- Version number
- Cross-references

**Content:**
- Clear and concise
- At least 2 examples
- Generic (unless NMP)
- Technically accurate
- < 200 lines for base entries

**Format:**
- Valid markdown
- Code blocks with language
- Proper header nesting
- Tables formatted
- Links work

**6. Entry Types Deep Dive (10 min)**

**When to Create Each Type:**

**ARCH:** New architecture pattern
- Example: "Document microservices pattern"

**GATE:** New gateway technique
- Example: "Document caching at gateway"

**INT:** New interface pattern
- Example: "Document rate limiting interface"

**LANG:** New language best practice
- Example: "Document Python async patterns"

**NMP:** Project-specific implementation
- Example: "Document our specific cache config"

### Session 3 Handouts

**Provide:**
1. Entry structure diagram (printed)
2. Entry templates (digital)
3. Entry type decision tree (printed)
4. Quality checklist (printed)

---

## 🛠️ SESSION 4: SUPPORT TOOLS {#session-4-tools}

**Duration:** 60 minutes  
**Format:** Tool demonstrations + Practice  
**Materials:** Tool documentation, examples

### Session Outline

**1. Support Tools Overview (10 min)**

**Present Tool Categories:**

| Category | Count | Purpose |
|----------|-------|---------|
| Workflows | 5 | Process templates |
| Checklists | 4 | Verification |
| Search Tools | 3 | Find information |
| Quick Refs | 3 | Rapid lookup |
| Utilities | 1 | Helpers |

**2. Workflow Templates (15 min)**

**Present Available Workflows:**

1. **Workflow-01: Add Feature**
   - When: Implementing new functionality
   - Steps: Plan → Fetch → Implement → Verify → Document

2. **Workflow-02: Debug Issue**
   - When: Troubleshooting problems
   - Steps: Reproduce → Check Known → Trace → Fix → Prevent

3. **Workflow-03: Update Interface**
   - When: Modifying interface functions
   - Steps: Check Dependencies → Update All Layers → Verify

4. **Workflow-04: Add Gateway Function**
   - When: Adding gateway wrapper
   - Steps: Plan → Interface First → Gateway Wrapper → Test

5. **Workflow-05: Create NMP Entry**
   - When: Documenting project-specific patterns
   - Steps: Check Duplicates → Create → Cross-Reference

**Demo Workflow Usage:**

Show Workflow-01 in action:
- Read workflow
- Follow steps
- Use checkboxes
- Complete verification

**Participants Practice:**
- Choose a workflow
- Read through it
- Identify when to use
- Try one step

**3. Verification Checklists (10 min)**

**Present Available Checklists:**

1. **Checklist-01: Code Review**
   - SUGA compliance
   - Import rules
   - Error handling
   - Documentation

2. **Checklist-02: Deployment Readiness**
   - Tests pass
   - Docs updated
   - Dependencies verified

3. **Checklist-03: Documentation Quality**
   - Completeness
   - Accuracy
   - Examples
   - Cross-references

4. **CHK-04: Tool Integration**
   - All tools work
   - Integration verified
   - E2E scenarios pass

**Demo Checklist Usage:**

Show using Code Review checklist:
- Go through each item
- Mark completed items
- Note issues found
- Track completion

**4. Search and Navigation Tools (15 min)**

**Present Search Tools:**

1. **Tool-01: REF-ID Lookup**
   - Find by identifier
   - Direct access
   - Usage: "Find ARCH-01"

2. **Tool-02: Keyword Search**
   - Find by concepts
   - Fuzzy matching
   - Usage: "Search for caching"

3. **Tool-03: Cross-Reference Validator**
   - Check relationships
   - Find broken links
   - Validate structure

**Demo Each Tool:**

**REF-ID Lookup:**
```
"Show me INT-06"
[Demonstrates direct entry access]
```

**Keyword Search:**
```
"Find entries about lazy loading"
[Demonstrates concept search]
```

**Cross-Reference Validator:**
```
"Validate cross-references for ARCH-01"
[Demonstrates relationship checking]
```

**Participants Practice:**
- Try REF-ID lookup (3 entries)
- Try keyword search (2 concepts)
- Explore cross-references

**5. Quick Reference Cards (10 min)**

**Present QRCs:**

1. **QRC-01: Interfaces Overview**
   - All 12 interfaces
   - Dependency layers
   - Common use cases
   - Print and keep at desk!

2. **QRC-02: Gateway Patterns**
   - 5 gateway patterns
   - When to use each
   - Implementation tips

3. **QRC-03: Common Patterns**
   - Most used patterns
   - Decision matrix
   - Anti-patterns to avoid

**Demo Usage:**

Show how to use QRC-01:
- Need caching? → INT-01
- Need logging? → INT-06
- Need security? → INT-08
- Quick decision making

**Distribute:**
- Print QRCs for everyone
- Keep at desk
- Use for quick lookups

### Session 4 Handouts

**Provide:**
1. All 3 Quick Reference Cards (printed)
2. Workflow summary sheet (printed)
3. Checklist overview (printed)
4. Tool usage guide (digital)

---

## 🎯 SESSION 5: HANDS-ON PRACTICE {#session-5-practice}

**Duration:** 120 minutes  
**Format:** Guided practice exercises  
**Materials:** Practice scenarios, solution guides

### Session Outline

**1. Practice Setup (10 min)**

**Prepare Environment:**
- [ ] Everyone has Claude open
- [ ] File Server URLs loaded
- [ ] Practice scenarios distributed
- [ ] Teams of 2-3 formed

**Review Available Resources:**
- User Guide
- Quick Reference Cards
- Cheat sheets
- Trainer for questions

**2. Exercise 1: General Mode Practice (20 min)**

**Scenario:**
You're new to the project and need to understand the architecture.

**Tasks:**
1. Activate General Mode
2. Find information about SUGA architecture
3. Understand the 3 layers
4. Identify the 12 interfaces
5. Read about gateway patterns

**Deliverable:**
- Summary of SUGA pattern
- List of 12 interfaces
- 3 gateway patterns explained

**Debrief (5 min):**
- Share findings
- Discuss challenges
- Review solutions

**3. Exercise 2: Learning Mode Practice (20 min)**

**Scenario:**
You discovered an important lesson about error handling.

**Tasks:**
1. Activate Learning Mode
2. Document lesson: "Always validate external API responses"
3. Check for duplicates
4. Create proper entry
5. Add cross-references
6. Validate structure

**Deliverable:**
- Complete LESS-## entry
- Cross-references added
- Validation passed

**Debrief (5 min):**
- Compare entries
- Review quality
- Discuss variations

**4. Exercise 3: Project Mode Practice (25 min)**

**Scenario:**
Need to add a new cache operation: clear_pattern (clear keys matching pattern).

**Tasks:**
1. Activate Project Mode
2. Follow Workflow-01 (Add Feature)
3. Plan the implementation
4. Request implementation from Claude
5. Review artifact output
6. Verify with checklist

**Deliverable:**
- Implementation plan
- Complete code artifacts (3 layers)
- Verification checklist completed

**Debrief (5 min):**
- Review implementations
- Discuss approaches
- Check verifications

**5. Exercise 4: Debug Mode Practice (20 min)**

**Scenario:**
Lambda function returns error: "Config not initialized"

**Tasks:**
1. Activate Debug Mode
2. Follow Workflow-02 (Debug Issue)
3. Check known bugs
4. Trace through layers
5. Identify root cause
6. Get fix and prevention

**Deliverable:**
- Root cause identified
- Fix proposed
- Prevention strategy

**Debrief (5 min):**
- Share diagnoses
- Compare solutions
- Discuss learnings

**6. Exercise 5: End-to-End Scenario (25 min)**

**Scenario:**
Complete feature implementation from scratch.

**Tasks:**
1. Understand requirement: Add rate limiting to API
2. Research patterns (General Mode)
3. Plan implementation (Project Mode)
4. Document learnings (Learning Mode)
5. Use appropriate workflows
6. Verify with checklists

**Deliverable:**
- Research notes
- Implementation plan
- Code artifacts
- Lesson documented

**Debrief (10 min):**
- Share experiences
- Discuss challenges
- Review best practices

**7. Wrap-Up and Assessment (15 min)**

**Review Key Learnings:**
- Mode system mastery
- Entry navigation
- Tool utilization
- Workflow adherence
- Quality standards

**Assessment Quiz (10 questions):**
See Assessment Quizzes section

**Feedback Collection:**
- What worked well?
- What was confusing?
- What needs more practice?
- Suggestions for improvement?

**Next Steps:**
- Continue practicing
- Reference User Guide
- Use Quick Reference Cards
- Ask questions in team chat
- Schedule follow-up if needed

### Session 5 Handouts

**Provide:**
1. Practice scenarios (digital)
2. Solution guides (digital)
3. Assessment quiz (printed)
4. Feedback form (printed)
5. Certificate of completion

---

# PART 2: VIDEO TUTORIAL SCRIPTS

## 🎥 VIDEO 1: QUICK START GUIDE {#video-1-quick-start}

**Duration:** 10 minutes  
**Format:** Screen recording with voiceover  
**Target:** Absolute beginners

### Script

**[0:00-0:30] Introduction**

"Welcome to SIMAv4! I'm going to show you how to get started in just 10 minutes. By the end of this video, you'll be able to activate a mode and find information in the knowledge base. Let's dive in!"

**[Screen: Title card "SIMAv4 Quick Start"]**

**[0:30-1:30] What is SIMAv4?**

"SIMAv4 is a knowledge management system that helps you document and find information about software architecture patterns, design decisions, and lessons learned."

**[Screen: Show architecture diagram]**

"Think of it as your project's memory. Instead of searching through old chats or documentation, you can quickly find what you need using specialized search tools and AI-powered retrieval."

**[1:30-2:30] The Mode System**

"SIMAv4 uses 4 specialized modes for different tasks. Let me show you the most common one: General Purpose Mode."

**[Screen: Show mode activation phrases]**

"To activate General Mode, you simply say: 'Please load context'"

**[Screen: Claude interface]**

"Watch what happens..."

**[Type in Claude]** "Please load context"

**[Show loading... then confirmation]**

"It takes about 30-60 seconds to load, then you're ready to go!"

**[2:30-4:00] Finding Information**

"Let's say I want to learn about the SUGA architecture pattern. I'll just ask:"

**[Type]** "Explain the SUGA pattern"

**[Show Claude searching and responding]**

"Notice how Claude searches the knowledge base and provides a clear explanation with references. Those REF-IDs like 'ARCH-01' are unique identifiers for entries."

**[4:00-5:30] Following Cross-References**

"I can follow those references to learn more. Let me search for ARCH-01 directly:"

**[Type]** "Show me ARCH-01"

**[Show full entry loading]**

"Here's the complete entry with all the details. I can see it has sections for Overview, Implementation, Examples, and Cross-References."

**[Scroll through entry]**

"The Cross-References section shows related entries I might want to explore."

**[5:30-7:00] Using Other Modes**

"When you need to do something specific, you can use specialized modes."

**[Screen: Show 4 mode cards]**

"Learning Mode for documenting knowledge, Project Mode for writing code, and Debug Mode for troubleshooting. Each has its own activation phrase."

**[Show activation phrases]**

"Just end your current session, start a new one, and say the appropriate phrase."

**[7:00-8:30] Quick Reference Cards**

"To help you remember everything, we have Quick Reference Cards."

**[Screen: Show QRC-01]**

"This one shows all 12 interfaces and when to use them. Print it out and keep it at your desk!"

**[Screen: Show QRC-02]**

"This one covers gateway patterns."

**[Screen: Show QRC-03]**

"And this one has the most common patterns you'll use daily."

**[8:30-9:30] Your First Task**

"Now it's your turn! Here's a simple exercise:"

**[Screen: Show exercise]**

"1. Activate General Mode
2. Ask about the cache interface
3. Find INT-01
4. Read the overview section"

**[Show demonstration of each step]**

"Try it yourself! It should take about 5 minutes."

**[9:30-10:00] Wrap-Up**

"That's it! You now know how to:
- Activate General Mode
- Ask questions
- Find entries by REF-ID
- Use Quick Reference Cards"

**[Screen: Show resources]**

"Check out the User Guide for more details, and watch our other tutorials for Learning Mode, Project Mode, and Debug Mode. Happy learning!"

**[Screen: End card with links]**

### Production Notes

**Visuals Needed:**
- Architecture diagram
- Mode activation cards
- Quick Reference Cards
- Example entries
- Exercise card

**Screen Recording:**
- Claude interface
- Search results
- Entry navigation
- Mode switching

**Post-Production:**
- Add captions
- Add timestamps
- Add chapter markers
- Add resource links in description

---

## 🎥 VIDEO 2: MODE SYSTEM DEEP DIVE {#video-2-modes}

**Duration:** 20 minutes  
**Format:** Screen recording with voiceover  
**Target:** Users ready for advanced features

### Script

**[0:00-1:00] Introduction**

"Welcome back! In this video, we're doing a deep dive into SIMAv4's mode system. You'll learn when to use each mode, how to switch between them, and best practices for mode-based workflows."

**[Screen: Title "Mode System Deep Dive"]**

**[1:00-2:00] Why Modes?**

"SIMAv4 has 4 modes because different tasks need different context and tools."

**[Screen: Show comparison]**

"Asking questions needs access to all entries. Writing code needs templates and verification tools. Debugging needs known bugs database and tracing tools. Documenting needs duplication checking and entry templates."

"Loading everything at once would be slow and overwhelming. Modes give you exactly what you need, when you need it."

**[2:00-6:00] General Purpose Mode**

"Let's start with General Mode - your default mode for learning and questions."

**[Screen: Activate General Mode]**

**[Type]** "Please load context"

**[Wait for load, show confirmation]**

"General Mode gives you:
- Access to all base patterns
- Top 10 instant answers
- Workflow routing
- Cross-reference navigation"

**[Demonstrate each feature]**

"Perfect for:
- Understanding architecture
- Learning patterns
- Answering 'why' questions
- Exploring relationships"

**[Show 3 example questions with responses]**

**[6:00-10:00] Learning Mode**

"Next is Learning Mode - for capturing knowledge."

**[End session, start new]**

**[Type]** "Start SIMA Learning Mode"

**[Wait for load]**

"Learning Mode gives you:
- Entry creation templates
- Duplication checker
- Genericization guidelines
- Cross-reference tools"

**[Demonstrate creating an entry]**

"Let's document a lesson:"

**[Type]** "Document lesson: Always validate configuration before using"

**[Show duplication check]**

"See how it checked for existing entries first? This prevents duplicates."

**[Show entry creation]**

"Now it's creating a properly structured entry with all required sections."

**[Show cross-reference addition]**

"And it's adding appropriate cross-references."

**[10:00-14:00] Project Work Mode**

"Project Mode is for active development."

**[End session, start new]**

**[Type]** "Start Project Work Mode"

**[Wait for load]**

"Project Mode gives you:
- Implementation templates
- LESS-15 verification
- Complete file artifacts
- Anti-pattern checking"

**[Demonstrate feature implementation]**

"Let's add a feature:"

**[Type]** "Add a cache operation to get multiple keys at once"

**[Show file fetching]**

"First, it fetches current files. Critical step - never skip this!"

**[Show implementation]**

"Then it implements all 3 SUGA layers:
- Gateway wrapper
- Interface function
- Core implementation"

**[Show artifact]**

"Notice it outputs complete files, not fragments. You can copy and deploy immediately."

**[Show verification]**

"Finally, it runs through LESS-15 verification."

**[14:00-17:00] Debug Mode**

"Debug Mode is for troubleshooting."

**[End session, start new]**

**[Type]** "Start Debug Mode"

**[Wait for load]**

"Debug Mode gives you:
- Known bugs database
- Systematic investigation
- Layer tracing
- Root cause analysis"

**[Demonstrate debugging]**

"Let's debug an issue:"

**[Type]** "Lambda function times out on cold start"

**[Show bug check]**

"First, it checks known bugs. BUG-01 covers cold start issues!"

**[Show investigation]**

"It traces through the initialization sequence, checking each step."

**[Show root cause]**

"Root cause identified: lazy loading not configured properly."

**[Show fix]**

"Here's the fix and how to prevent it in the future."

**[17:00-19:00] Mode Selection Best Practices**

"Here's how to choose the right mode:"

**[Screen: Decision flowchart]**

"Ask yourself:
- Am I learning? → General Mode
- Am I documenting? → Learning Mode
- Am I coding? → Project Mode
- Am I debugging? → Debug Mode"

**[Show examples]**

"Example scenarios:
- 'How does caching work?' → General
- 'I learned something important' → Learning
- 'Add this feature' → Project
- 'Why is this broken?' → Debug"

**[19:00-20:00] Wrap-Up**

"Remember:
- One mode per session
- Exact activation phrase
- End session to switch
- Choose mode for task"

**[Screen: Resources]**

"Practice with the mode cheat sheet, and check out our other tutorials for specific workflows. See you in the next video!"

---

## 🎥 VIDEO 3: ENTRY SYSTEM {#video-3-entries}

**Duration:** 15 minutes  
**Format:** Screen recording with voiceover  
**Target:** Users creating or updating entries

### Script

**[0:00-0:45] Introduction**

"In this video, you'll learn how to work with entries in SIMAv4 - reading them, understanding their structure, and creating new ones. Let's get started!"

**[0:45-3:00] Entry Structure**

"Every entry follows a standard structure. Let me show you:"

**[Screen: Open ARCH-01]**

"At the top, we have the filename header - always included."

**[Highlight]** "# File: ARCH-01-SUGA-Pattern.md"

"Then metadata:"

**[Highlight each]**
"- REF-ID: Unique identifier
- Category: Hierarchy path
- Version: Version number
- Inherits From: Parent entries
- Related To: Sibling entries
- Used In: Implementations"

**[Scroll down]**

"Then content sections:
- Overview: What it covers
- Main content: Details
- Examples: Practical use
- Cross-References: Relationships"

**[3:00-6:00] Understanding Cross-References**

"Cross-references are key to navigation. There are 3 types:"

**[Screen: Relationship diagram]**

"1. Inherits From - parent-child relationships"

**[Show example]**

"NMP01-LEE-02 inherits from INT-01. The project cache implements the base cache interface."

"2. Related To - sibling relationships"

**[Show example]**

"GATE-01 and GATE-02 are related - both about gateway optimization."

"3. Used In - implementations"

**[Show example]**

"ARCH-01 used in multiple NMP entries - shows where pattern is applied."

**[Demonstrate navigation]**

"I can follow these links to explore the knowledge graph."

**[6:00-9:00] Reading Entries Effectively**

"Here's how to read entries efficiently:"

**[Show process]**

"1. Start with the Overview
2. Skim the section headers
3. Read sections relevant to you
4. Check Examples
5. Explore Cross-References"

**[Demonstrate on actual entry]**

"Don't read linearly! Use the structure to find what you need."

**[Show cross-reference matrix]**

"Quick indexes and matrices help you find entries by problem, not just by name."

**[9:00-12:00] Creating New Entries**

"Ready to create an entry? Use Learning Mode:"

**[Activate Learning Mode]**

**[Type]** "Create a lesson about error logging"

**[Show duplication check]**

"First, it checks for duplicates."

**[Show template usage]**

"Then uses appropriate template."

**[Show genericization]**

"Notice how it keeps content generic - no project-specific details."

**[Show cross-references]**

"It adds appropriate cross-references automatically."

**[12:00-14:00] Entry Quality**

"High-quality entries have:"

**[Screen: Checklist]**

"✓ Clear structure
✓ Practical examples (at least 2)
✓ Valid cross-references
✓ Generic content (unless NMP)
✓ Concise (< 200 lines)"

**[Show good vs bad examples]**

"Good example: Clear, generic, well-referenced"

"Bad example: Project-specific, no examples, missing cross-references"

**[14:00-15:00] Wrap-Up**

"Remember:
- Entries follow standard structure
- Cross-references create knowledge graph
- Read strategically, not linearly
- Use Learning Mode to create
- Keep quality high"

**[Screen: Resources]**

"Check the User Guide for templates and examples. Next video covers search and navigation!"

---

## 🎥 VIDEO 4: SEARCH AND NAVIGATION {#video-4-search}

**Duration:** 12 minutes  
**Format:** Screen recording with voiceover  
**Target:** All users

### Script

**[0:00-0:30] Introduction**

"Finding information quickly is crucial. In this video, you'll learn three powerful ways to search and navigate SIMAv4. Let's explore!"

**[0:30-4:00] Method 1: REF-ID Lookup**

"The fastest way to find an entry is by its REF-ID."

**[Screen: Claude interface]**

**[Type]** "Show me ARCH-01"

**[Entry loads immediately]**

"Direct access! No searching needed."

**[Show more examples]**

"Find INT-06" → Logging Interface
"Show GATE-03" → Cross-Interface Communication

**[Show REF-ID directory]**

"Keep the REF-ID directory handy for quick lookups."

**[Show Quick Reference Cards]**

"Or use Quick Reference Cards - they have the most common REF-IDs."

**[4:00-7:00] Method 2: Keyword Search**

"When you don't know the REF-ID, use keyword search:"

**[Type]** "Find entries about caching"

**[Show search results]**

"Returns multiple relevant entries. Notice it found:
- INT-01: Cache Interface
- NMP01-LEE-02: Cache Implementation
- GATE-05: Gateway Optimization (mentions caching)"

**[Show search tips]**

"Better keywords = better results"

**[Show examples]**

"Good: 'lazy loading gateway'
Better: 'GATE-02 lazy loading'
Best: 'GATE-02'"

"Good: 'exception handling'
Better: 'Python exception handling'
Best: 'LANG-PY-03 exceptions'"

**[7:00-10:00] Method 3: Cross-Reference Navigation**

"The most powerful method: following the knowledge graph."

**[Start at ARCH-01]**

"Start at SUGA pattern."

**[Show cross-references]**

"See 'Used In' - shows all implementations."

**[Click to NMP01-LEE-15]**

"Jump to project implementation."

**[Show its cross-references]**

"From here, I can explore related patterns."

**[Show cross-reference matrix]**

"Or use the cross-reference matrix to see all relationships at once."

**[Demonstrate traversal]**

"I can traverse the entire knowledge graph this way."

**[10:00-11:30] Quick Indexes**

"For problem-based lookup, use quick indexes:"

**[Show Interface Quick Index]**

"Need caching? → INT-01
Need logging? → INT-06
Need security? → INT-08"

**[Show Gateway Quick Index]**

"How to structure files? → GATE-01
How to optimize imports? → GATE-02"

**[Show Common Patterns Quick Index]**

"Most frequent patterns with usage counts."

**[11:30-12:00] Wrap-Up**

"Three methods:
1. REF-ID lookup - fastest
2. Keyword search - flexible
3. Cross-reference navigation - most powerful

Use all three together for best results!"

---

## 🎥 VIDEO 5: COMMON WORKFLOWS {#video-5-workflows}

**Duration:** 18 minutes  
**Format:** Screen recording with voiceover  
**Target:** Active users

### Script

**[0:00-0:45] Introduction**

"SIMAv4 has 5 workflow templates for common tasks. In this video, I'll demonstrate each one so you know exactly when and how to use them."

**[0:45-4:00] Workflow 1: Add Feature**

"Most common workflow - implementing new functionality."

**[Screen: Open Workflow-01]**

"Five phases: Plan, Fetch, Implement, Verify, Document"

**[Demonstrate]**

"Let's add a cache operation:"

**[Activate Project Mode]**

**[Follow workflow]**

"Phase 1: Plan
- What operation? get_multiple
- Which interface? Cache
- Which layers? All 3"

**[Fetch files]**

"Phase 2: Fetch current files
Critical! Always do this first."

**[Implement]**

"Phase 3: Implement all layers
Watch how it does Gateway → Interface → Core"

**[Verify]**

"Phase 4: Verify with LESS-15
5-step verification catches issues"

**[Document]**

"Phase 5: Document changes
Update relevant NMP entries"

**[4:00-7:00] Workflow 2: Debug Issue**

"Troubleshooting problems systematically."

**[Screen: Open Workflow-02]**

"Six steps: Reproduce, Check Known, Trace, Identify, Fix, Prevent"

**[Demonstrate]**

"Issue: Function returns None unexpectedly"

**[Activate Debug Mode]**

**[Follow workflow]**

"Step 1: Reproduce
Create minimal test case"

**[Check bugs]**

"Step 2: Check known bugs
Is this BUG-01, 02, 03, or 04?"

**[Trace]**

"Step 3: Trace through layers
Gateway → Interface → Core
Where does it break?"

**[Identify]**

"Step 4: Identify root cause
Missing error handling in core"

**[Fix]**

"Step 5: Implement fix
Add proper error handling"

**[Prevent]**

"Step 6: Prevent recurrence
Add to anti-patterns, update checklist"

**[7:00-10:00] Workflow 3: Update Interface**

"Modifying interface functions - requires care!"

**[Screen: Open Workflow-03]**

"Four phases: Check Dependencies, Update All, Verify, Document"

**[Demonstrate]**

"Updating INT-01 cache interface:"

**[Check dependencies]**

"Phase 1: Check dependencies
What uses this interface?"

**[Show dependency graph]**

"Gateway, multiple NMPs - need to update all"

**[Update]**

"Phase 2: Update all layers
Interface first, then gateway, then NMPs"

**[Verify]**

"Phase 3: Verify everything
Test each layer, run integration tests"

**[Document]**

"Phase 4: Update documentation
Interface entry, gateway patterns, NMPs"

**[10:00-13:00] Workflow 4: Add Gateway Function**

"Creating new gateway wrappers."

**[Screen: Open Workflow-04]**

"Five steps: Plan, Interface First, Gateway Wrapper, Test, Document"

**[Demonstrate]**

"Adding gateway.clear_all_caches():"

**[Plan]**

"Step 1: Plan
- Purpose: Clear all caches
- Interface: call interface_cache.clear_all()
- Return: Success boolean"

**[Interface first]**

"Step 2: Interface first!
Always implement interface before gateway"

**[Gateway wrapper]**

"Step 3: Gateway wrapper
Simple wrapper in gateway.py"

**[Test]**

"Step 4: Test
Verify wrapper works, handles errors"

**[Document]**

"Step 5: Document
Update GATE patterns, create NMP if project-specific"

**[13:00-16:00] Workflow 5: Create NMP Entry**

"Documenting project-specific patterns."

**[Screen: Open Workflow-05]**

"Five steps: Check Duplicates, Create Entry, Cross-Reference, Index, Validate"

**[Demonstrate]**

"Documenting our cache configuration:"

**[Activate Learning Mode]**

**[Check duplicates]**

"Step 1: Check for duplicates
Search for similar NMP entries"

**[Create]**

"Step 2: Create entry
Use NMP template, fill all sections"

**[Cross-reference]**

"Step 3: Add cross-references
Links to INT-01, GATE-01, other NMPs"

**[Index]**

"Step 4: Update indexes
Add to NMP cross-reference matrix and quick index"

**[Validate]**

"Step 5: Validate
Run validation checklist"

**[16:00-17:30] Choosing the Right Workflow**

"Quick decision guide:"

**[Screen: Decision tree]**

"Building something new? → Workflow 1
Something broken? → Workflow 2
Changing interface? → Workflow 3
Adding gateway function? → Workflow 4
Documenting project code? → Workflow 5"

**[Show examples]**

"Example: 'Add rate limiting'
- New feature → Workflow 1"

"Example: 'Cache not working'
- Issue → Workflow 2"

"Example: 'Document our HA setup'
- Project-specific → Workflow 5"

**[17:30-18:00] Wrap-Up**

"Five workflows for common tasks:
1. Add Feature
2. Debug Issue
3. Update Interface
4. Add Gateway Function
5. Create NMP Entry

Use them as checklists, don't skip steps! See the User Guide for detailed examples."

---

# PART 3: RESOURCES

## 📋 TRAINING CHEAT SHEETS {#training-cheat-sheets}

### Cheat Sheet 1: Mode Activation

```
╔════════════════════════════════════════╗
║        MODE ACTIVATION GUIDE           ║
╠════════════════════════════════════════╣
║                                        ║
║ GENERAL MODE                           ║
║ Phrase: "Please load context"         ║
║ Use for: Questions, learning           ║
║                                        ║
║ LEARNING MODE                          ║
║ Phrase: "Start SIMA Learning Mode"    ║
║ Use for: Document knowledge            ║
║                                        ║
║ PROJECT MODE                           ║
║ Phrase: "Start Project Work Mode"     ║
║ Use for: Write code                    ║
║                                        ║
║ DEBUG MODE                             ║
║ Phrase: "Start Debug Mode"            ║
║ Use for: Fix issues                    ║
║                                        ║
╠════════════════════════════════════════╣
║ REMEMBER:                              ║
║ • One mode per session                 ║
║ • Exact phrase required                ║
║ • End session to switch                ║
╚════════════════════════════════════════╝
```

### Cheat Sheet 2: Entry Type Quick Reference

```
╔═══════════════════════════════════════════╗
║          ENTRY TYPE GUIDE                 ║
╠═══════════════════════════════════════════╣
║                                           ║
║ ARCH-##: Architecture patterns            ║
║ Example: ARCH-01 (SUGA Pattern)           ║
║                                           ║
║ GATE-##: Gateway patterns                 ║
║ Example: GATE-01 (Three-File Structure)   ║
║                                           ║
║ INT-##: Interface patterns                ║
║ Example: INT-01 (Cache Interface)         ║
║                                           ║
║ LANG-PY-##: Python patterns               ║
║ Example: LANG-PY-03 (Exception Handling)  ║
║                                           ║
║ NMP##-CODE-##: Project-specific           ║
║ Example: NMP01-LEE-02 (Cache Functions)   ║
║                                           ║
╠═══════════════════════════════════════════╣
║ USE BASE ENTRIES FOR:                     ║
║ • Generic patterns                        ║
║ • Reusable knowledge                      ║
║                                           ║
║ USE NMPs FOR:                             ║
║ • Project implementations                 ║
║ • Specific configurations                 ║
╚═══════════════════════════════════════════╝
```

### Cheat Sheet 3: Workflow Selection

```
╔══════════════════════════════════════╗
║       WORKFLOW SELECTOR              ║
╠══════════════════════════════════════╣
║                                      ║
║ TASK: Build new feature              ║
║ → Workflow-01: Add Feature           ║
║                                      ║
║ TASK: Fix broken functionality       ║
║ → Workflow-02: Debug Issue           ║
║                                      ║
║ TASK: Change interface function      ║
║ → Workflow-03: Update Interface      ║
║                                      ║
║ TASK: Add gateway wrapper            ║
║ → Workflow-04: Add Gateway Function  ║
║                                      ║
║ TASK: Document project code          ║
║ → Workflow-05: Create NMP Entry      ║
║                                      ║
╚══════════════════════════════════════╝
```

---

## 🎯 PRACTICE EXERCISES {#practice-exercises}

### Exercise Set 1: Beginner (General Mode)

**Exercise 1.1: Architecture Understanding**
- Activate General Mode
- Find ARCH-01
- Explain SUGA pattern in your own words
- List the 3 layers

**Exercise 1.2: Interface Exploration**
- Find INT-06 (Logging Interface)
- What's its purpose?
- What are 3 common operations?
- Which layer is it in (L0-L4)?

**Exercise 1.3: Cross-Reference Navigation**
- Start at GATE-01
- Find what it inherits from
- Find what it's related to
- Find where it's used

### Exercise Set 2: Intermediate (Multiple Modes)

**Exercise 2.1: Document a Lesson**
- Activate Learning Mode
- Document: "Always initialize config before first use"
- Check for duplicates
- Create proper entry
- Add cross-references

**Exercise 2.2: Simple Feature**
- Activate Project Mode
- Add cache operation: check_exists(key)
- Plan implementation
- Get Claude to implement
- Verify with checklist

**Exercise 2.3: Debug Scenario**
- Activate Debug Mode
- Issue: "Cache returns stale data"
- Check known bugs
- Trace the problem
- Identify root cause

### Exercise Set 3: Advanced (End-to-End)

**Exercise 3.1: Complete Feature Implementation**
- Research: How does circuit breaker work? (General Mode)
- Plan: Add circuit breaker to API (Project Mode)
- Implement: Get complete artifacts (Project Mode)
- Document: Create NMP entry (Learning Mode)

**Exercise 3.2: Interface Modification**
- Update INT-01 to add batch operations
- Check all dependencies
- Update all layers
- Update documentation
- Verify integration

**Exercise 3.3: Knowledge Transfer**
- Pick a complex topic you know well
- Create comprehensive NMP entry
- Add all cross-references
- Update indexes
- Validate quality

---

## âœ… ASSESSMENT QUIZZES {#assessment-quizzes}

### Quiz 1: Mode System (10 questions)

**Q1:** Which mode do you use for asking architecture questions?
- A) Learning Mode
- B) Project Mode
- C) General Mode ✓
- D) Debug Mode

**Q2:** What's the activation phrase for Project Mode?
- A) "Please load context"
- B) "Start SIMA Learning Mode"
- C) "Start Project Work Mode" ✓
- D) "Start Debug Mode"

**Q3:** Can you switch modes mid-session?
- A) Yes, anytime
- B) No, must end session first ✓
- C) Only from General to others
- D) Only with special command

**Q4:** How many modes can you have active at once?
- A) 4 (all of them)
- B) 2 (any pair)
- C) 1 (only one) ✓
- D) Unlimited

**Q5:** Which mode checks for duplicate entries?
- A) General Mode
- B) Learning Mode ✓
- C) Project Mode
- D) Debug Mode

**Q6:** Which mode provides LESS-15 verification?
- A) General Mode
- B) Learning Mode
- C) Project Mode ✓
- D) Debug Mode

**Q7:** Which mode has access to known bugs database?
- A) General Mode
- B) Learning Mode
- C) Project Mode
- D) Debug Mode ✓

**Q8:** What's the typical mode loading time?
- A) 5-10 seconds
- B) 30-60 seconds ✓
- C) 2-3 minutes
- D) 5-10 minutes

**Q9:** Which mode would you use to add a new feature?
- A) General Mode
- B) Learning Mode
- C) Project Mode ✓
- D) Debug Mode

**Q10:** What happens if you use the wrong activation phrase?
- A) Wrong mode loads
- B) Error message
- C) Nothing happens ✓
- D) All modes load

### Quiz 2: Entry System (10 questions)

**Q1:** What does ARCH stand for in ARCH-01?
- A) Archive
- B) Architecture ✓
- C) Archetype
- D) Architect

**Q2:** Which entries go in /sima/entries/?
- A) All entries
- B) Only architecture
- C) Generic patterns ✓
- D) Project-specific

**Q3:** Which entries go in /sima/nmp/?
- A) All entries
- B) Only new entries
- C) Generic patterns
- D) Project-specific ✓

**Q4:** What does "Inherits From" mean?
- A) Copied from
- B) Parent-child relationship ✓
- C) Similar to
- D) Replaces

**Q5:** How many examples should an entry have minimum?
- A) 0
- B) 1
- C) 2 ✓
- D) 5

**Q6:** What's the max recommended lines for base entries?
- A) 100
- B) 200 ✓
- C) 500
- D) Unlimited

**Q7:** Must entries have filename in header?
- A) Yes, always ✓
- B) No, optional
- C) Only for NMPs
- D) Only for base entries

**Q8:** What's the REF-ID format?
- A) TYPE##
- B) TYPE-## ✓
- C) ##-TYPE
- D) TYPE_##

**Q9:** Can you reuse REF-ID numbers?
- A) Yes, when entry deleted
- B) No, never ✓
- C) Only for NMPs
- D) Only with permission

**Q10:** What's "Related To" used for?
- A) Parent entries
- B) Child entries
- C) Sibling entries ✓
- D) All entries

**Answer Key:**
Quiz 1: C, C, B, C, B, C, D, B, C, C
Quiz 2: B, C, D, B, C, B, A, B, B, C

---

## 📝 VERSION HISTORY

**v1.0.0 (2025-10-29)**
- Initial training materials and video scripts
- 5 training sessions (6-8 hours total)
- 5 video tutorial scripts (75 minutes total)
- Complete cheat sheets
- Practice exercises (3 levels)
- Assessment quizzes (20 questions)
- Production-ready for immediate use

---

**END OF TRAINING MATERIALS**

**Status:** Production Ready  
**Audience:** Trainers, new users, team leads  
**Format:** In-person training, video tutorials, self-paced learning  
**Maintenance:** Update with system changes and user feedback

For reference materials, see User Guide and Developer Guide.
