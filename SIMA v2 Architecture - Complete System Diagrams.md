# SIMA v2 Architecture - Complete System Diagrams
**Version:** 2.1.0 (Current State)  
**Purpose:** Visual reference for SIMA v3 upgrade  
**Date:** 2025-10-22

---

## Table of Contents

1. [Overall Architecture (Three-Layer Pattern)](#1-overall-architecture-three-layer-pattern)
2. [Neural Map File Organization](#2-neural-map-file-organization)
3. [Query Routing System](#3-query-routing-system)
4. [Session Workflow](#4-session-workflow)
5. [Tool Ecosystem](#5-tool-ecosystem)
6. [File Dependency Map](#6-file-dependency-map)
7. [REF-ID Cross-Reference System](#7-ref-id-cross-reference-system)
8. [Decision Trees](#8-decision-trees)
9. [Token Management Flow](#9-token-management-flow)
10. [File Server Integration](#10-file-server-integration)

---

## 1. Overall Architecture (Three-Layer Pattern)

### 1.1 SIMA Pattern Core Structure

```
┌─────────────────────────────────────────────────────────────────┐
│                         GATEWAY LAYER                           │
│                    (Fast Entry Points)                          │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌──────────────────────┐    ┌──────────────────────┐         │
│  │  NEURAL_MAP_00-      │    │  NEURAL_MAP_00A-     │         │
│  │  Quick_Index.md      │    │  Master_Index.md     │         │
│  │                      │    │                      │         │
│  │  • Keyword triggers  │    │  • Complete system   │         │
│  │  • Fast lookups      │    │  • All references    │         │
│  │  • Decision trees    │    │  • Full navigation   │         │
│  └──────────────────────┘    └──────────────────────┘         │
│           │                            │                        │
│           └────────────┬───────────────┘                        │
└────────────────────────┼────────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────────┐
│                       INTERFACE LAYER                           │
│                   (Category Organizers)                         │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐           │
│  │ NM01-INDEX- │  │ NM02-INDEX- │  │ NM03-INDEX- │           │
│  │ Architecture│  │ Dependencies│  │ Operations  │           │
│  └─────────────┘  └─────────────┘  └─────────────┘           │
│                                                                 │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐           │
│  │ NM04-INDEX- │  │ NM05-INDEX- │  │ NM06-INDEX- │           │
│  │ Decisions   │  │Anti-Patterns│  │ Learned     │           │
│  └─────────────┘  └─────────────┘  └─────────────┘           │
│                                                                 │
│  ┌─────────────┐                                               │
│  │ NM07-INDEX- │                                               │
│  │DecisionLogic│                                               │
│  └─────────────┘                                               │
│           │                                                     │
│           └───────────────────┬─────────────────────────       │
└───────────────────────────────┼─────────────────────────────────┘
                                │
                                ▼
┌─────────────────────────────────────────────────────────────────┐
│                    IMPLEMENTATION LAYER                         │
│                   (~23 Detailed Files)                          │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  NM01 Files (Architecture):                                     │
│  ├─ NM01-CORE-Architecture.md                                  │
│  ├─ NM01-INTERFACES-Core.md                                    │
│  ├─ NM01-RULES-Foundation.md                                   │
│  └─ ...                                                         │
│                                                                 │
│  NM02 Files (Dependencies):                                     │
│  ├─ NM02-RULES-Import.md                                       │
│  ├─ NM02-DEPENDENCIES-Layers.md                                │
│  └─ ...                                                         │
│                                                                 │
│  NM03 Files (Operations):                                       │
│  ├─ NM03-CORE-Pathways.md                                      │
│  ├─ NM03-ERROR-Handling.md                                     │
│  └─ ...                                                         │
│                                                                 │
│  NM04 Files (Decisions):                                        │
│  ├─ NM04-TECHNICAL-Decisions.md                                │
│  ├─ NM04-DESIGN-Rationale.md                                   │
│  └─ ...                                                         │
│                                                                 │
│  NM05 Files (Anti-Patterns):                                    │
│  ├─ NM05-Anti-Patterns-Part-1.md                               │
│  ├─ NM05-Anti-Patterns-Part-2.md                               │
│  └─ ...                                                         │
│                                                                 │
│  NM06 Files (Learned):                                          │
│  ├─ NM06-BUGS-Critical.md                                      │
│  ├─ NM06-LESSONS-Performance.md                                │
│  └─ ...                                                         │
│                                                                 │
│  NM07 Files (Decision Logic):                                   │
│  ├─ NM07-DECISION-Trees.md                                     │
│  └─ ...                                                         │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

### 1.2 Layer Responsibilities

```
┌──────────────────┬────────────────────────────────────────────────┐
│ LAYER            │ RESPONSIBILITY                                 │
├──────────────────┼────────────────────────────────────────────────┤
│ Gateway Layer    │ • Fast keyword-based routing                   │
│ (2 files)        │ • Instant lookup tables                        │
│                  │ • Quick decision trees                         │
│                  │ • 90% of queries answered here                 │
├──────────────────┼────────────────────────────────────────────────┤
│ Interface Layer  │ • Category organization                        │
│ (7 INDEX files)  │ • Topic navigation                             │
│                  │ • Section summaries                            │
│                  │ • Cross-reference mapping                      │
├──────────────────┼────────────────────────────────────────────────┤
│ Implementation   │ • Detailed content                             │
│ (~23 files)      │ • Complete explanations                        │
│                  │ • Code examples                                │
│                  │ • Full rationale & history                     │
└──────────────────┴────────────────────────────────────────────────┘
```

### 1.3 Access Patterns

```
User Query
    │
    ▼
┌─────────────────────────┐
│ Check SESSION-START     │ ◄── Load once per session
│ Quick-Context.md        │
└─────────────────────────┘
    │
    ▼
┌─────────────────────────┐
│ Check Top 10            │ ◄── Instant answers (5 sec)
│ Instant Answers         │
└─────────────────────────┘
    │
    ├─ Answer found? ──────────► Respond immediately
    │
    ▼
┌─────────────────────────┐
│ Check Keyword Triggers  │ ◄── Routing map (10 sec)
│ in Quick Index          │
└─────────────────────────┘
    │
    ▼
┌─────────────────────────┐
│ Search Specific         │ ◄── Targeted search (10 sec)
│ Interface File          │
└─────────────────────────┘
    │
    ▼
┌─────────────────────────┐
│ Read Complete           │ ◄── Full context (15-20 sec)
│ Implementation File     │
└─────────────────────────┘
    │
    ▼
┌─────────────────────────┐
│ Follow Cross-References │ ◄── If needed (5-10 sec each)
│ (REF-ID lookups)        │
└─────────────────────────┘
    │
    ▼
Respond with citations
```

---

## 2. Neural Map File Organization

### 2.1 File Naming Convention

```
Pattern: NM##-CATEGORY-SubCategory.md

Where:
  NM   = Neural Map prefix
  ##   = Two-digit number (00-99)
  CATEGORY = ALL CAPS category name
  SubCategory = PascalCase sub-category

Examples:
  NM00-Quick-Index.md              ◄── Gateway (no category)
  NM01-CORE-Architecture.md        ◄── Implementation
  NM01-INDEX-Architecture.md       ◄── Interface
  NM04-TECHNICAL-Decisions.md      ◄── Implementation
  NM06-BUGS-Critical.md            ◄── Implementation
```

### 2.2 File Hierarchy

```
NEURAL MAPS/
│
├── GATEWAY LAYER (2 files)
│   ├── NM00-Quick-Index.md           ◄── Keyword triggers
│   └── NM00A-Master-Index.md         ◄── Complete navigation
│
├── INTERFACE LAYER (7 files)
│   ├── NM01-INDEX-Architecture.md    ◄── Architecture topics
│   ├── NM02-INDEX-Dependencies.md    ◄── Dependency topics
│   ├── NM03-INDEX-Operations.md      ◄── Operation topics
│   ├── NM04-INDEX-Decisions.md       ◄── Decision topics
│   ├── NM05-INDEX-Anti-Patterns.md   ◄── Anti-pattern topics
│   ├── NM06-INDEX-Learned.md         ◄── Lessons learned topics
│   └── NM07-INDEX-DecisionLogic.md   ◄── Decision tree topics
│
└── IMPLEMENTATION LAYER (~23 files)
    │
    ├── NM01-* (Architecture - 5 files)
    │   ├── NM01-CORE-Architecture.md
    │   ├── NM01-INTERFACES-Core.md
    │   ├── NM01-RULES-Foundation.md
    │   ├── NM01-ARCH-07-LMMS.md           ◄── NEW
    │   └── NM01-ARCH-08-Future.md         ◄── NEW
    │
    ├── NM02-* (Dependencies - 3 files)
    │   ├── NM02-RULES-Import.md
    │   ├── NM02-DEPENDENCIES-Layers.md
    │   └── NM02-WORKFLOW-Dependency.md
    │
    ├── NM03-* (Operations - 3 files)
    │   ├── NM03-CORE-Pathways.md
    │   ├── NM03-ERROR-Handling.md
    │   └── NM03-MONITORING-Metrics.md
    │
    ├── NM04-* (Decisions - 3 files)
    │   ├── NM04-TECHNICAL-Decisions.md
    │   ├── NM04-DESIGN-Rationale.md
    │   └── NM04-SECURITY-Choices.md
    │
    ├── NM05-* (Anti-Patterns - 2 files)
    │   ├── NM05-Anti-Patterns-Part-1.md   ◄── AP-01 to AP-15
    │   └── NM05-Anti-Patterns-Part-2.md   ◄── AP-16 to AP-28
    │
    ├── NM06-* (Learned - 5+ files)
    │   ├── NM06-BUGS-Critical.md
    │   ├── NM06-LESSONS-Performance.md
    │   ├── NM06-WISDOM-Development.md
    │   ├── NM06-LESS-XX-Header-Verification.md  ◄── NEW
    │   ├── NM06-LESS-YY-Session-Transition.md   ◄── NEW
    │   └── NM06-LESS-##-[Various].md      ◄── LESS-01 to LESS-21
    │
    └── NM07-* (Decision Logic - 2 files)
        ├── NM07-DECISION-Trees.md
        └── NM07-WORKFLOW-Patterns.md
```

### 2.3 Cross-Layer Communication

```
                  User Query
                      │
                      ▼
    ┌─────────────────────────────────────┐
    │      GATEWAY LAYER                  │
    │  (Quick Index / Master Index)       │
    └─────────────────────────────────────┘
                      │
         ┌────────────┼────────────┐
         │            │            │
         ▼            ▼            ▼
    ┌────────┐   ┌────────┐   ┌────────┐
    │ NM01-  │   │ NM02-  │   │ NM03-  │
    │ INDEX  │   │ INDEX  │   │ INDEX  │
    └────────┘   └────────┘   └────────┘
         │            │            │
         ▼            ▼            ▼
    ┌────────┐   ┌────────┐   ┌────────┐
    │ NM01-  │   │ NM02-  │   │ NM03-  │
    │ CORE   │   │ RULES  │   │ ERROR  │
    └────────┘   └────────┘   └────────┘
         │            │            │
         └────────────┼────────────┘
                      │
              Cross-references
              (REF-ID lookups)
```

---

## 3. Query Routing System

### 3.1 Keyword → File Mapping

```
┌──────────────────────┬────────────────────────────────────────┐
│ KEYWORD PATTERN      │ ROUTING PATH                           │
├──────────────────────┼────────────────────────────────────────┤
│ "cache"              │ NM01-INTERFACES-Core.md (INT-01)       │
│ "sentinel"           │ NM06-BUGS-Critical.md (BUG-01, BUG-02) │
│ "threading"          │ NM04-TECHNICAL-Decisions.md (DEC-04)   │
│ "import"             │ NM02-RULES-Import.md (RULE-01)         │
│ "why no [X]"         │ NM04-DESIGN-Rationale.md               │
│ "can I [X]"          │ NM05-Anti-Patterns-*.md                │
│ "cold start"         │ NM03-CORE-Pathways.md (PATH-01)        │
│ "circular import"    │ NM02-RULES-Import.md (RULE-01)         │
│ "gateway"            │ NM01-CORE-Architecture.md (ARCH-01)    │
│ "interface"          │ NM01-INTERFACES-Core.md (INT-01-12)    │
│ "LMMS"               │ NM01-ARCH-07-LMMS.md (ARCH-07)         │
│ "metrics"            │ NM03-MONITORING-Metrics.md (INT-04)    │
│ "bug"                │ NM06-BUGS-Critical.md                  │
│ "lesson"             │ NM06-LESSONS-Performance.md            │
└──────────────────────┴────────────────────────────────────────┘
```

### 3.2 Question Pattern → File Mapping

```
┌──────────────────────────────┬──────────────────────────────┐
│ QUESTION PATTERN             │ PRIMARY FILE                 │
├──────────────────────────────┼──────────────────────────────┤
│ "How do I import [X]?"       │ NM02-RULES-Import.md         │
│ "Why can't I [X]?"           │ NM04-DESIGN-Rationale.md     │
│ "Can I use [X]?"             │ NM05-Anti-Patterns-*.md      │
│ "What happened with [bug]?"  │ NM06-BUGS-Critical.md        │
│ "How does [operation] work?" │ NM03-CORE-Pathways.md        │
│ "What interfaces exist?"     │ NM01-INTERFACES-Core.md      │
│ "What's the decision on [X]?"│ NM04-TECHNICAL-Decisions.md  │
│ "What did we learn about [X]"│ NM06-LESSONS-Performance.md  │
└──────────────────────────────┴──────────────────────────────┘
```

### 3.3 Query Flow Decision Tree

```
                        User Question
                              │
                              ▼
                    ┌──────────────────┐
                    │ Is it in Top 10  │
                    │ Instant Answers? │
                    └──────────────────┘
                         │        │
                    YES  │        │  NO
                         ▼        ▼
                    ┌────────┐  ┌──────────────────┐
                    │Response│  │ Does it match    │
                    │(5 sec) │  │ keyword trigger? │
                    └────────┘  └──────────────────┘
                                     │        │
                                YES  │        │  NO
                                     ▼        ▼
                            ┌────────────┐  ┌──────────────┐
                            │ Search     │  │ Search       │
                            │ specific   │  │ project      │
                            │ file       │  │ knowledge    │
                            │ (10 sec)   │  │ broadly      │
                            └────────────┘  │ (20-30 sec)  │
                                     │      └──────────────┘
                                     │            │
                                     └─────┬──────┘
                                           ▼
                                  ┌────────────────┐
                                  │ Read complete  │
                                  │ section        │
                                  │ (15-20 sec)    │
                                  └────────────────┘
                                           │
                                           ▼
                                  ┌────────────────┐
                                  │ Follow cross-  │
                                  │ references?    │
                                  └────────────────┘
                                     │        │
                                YES  │        │  NO
                                     ▼        ▼
                            ┌────────────┐  ┌────────────┐
                            │ Lookup     │  │ Response   │
                            │ REF-IDs    │  │ with       │
                            │ (5-10 sec) │  │ citations  │
                            └────────────┘  └────────────┘
                                     │            ▲
                                     └────────────┘
```

---

## 4. Session Workflow

### 4.1 Session Initialization

```
┌──────────────────────────────────────────────────────────────┐
│                    SESSION START                             │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│  Step 1: Load Context (MANDATORY - 30-45 sec)               │
│  ┌────────────────────────────────────────────────────────┐ │
│  │ Search: "SESSION-START-Quick-Context"                  │ │
│  │ Read: Complete file                                    │ │
│  │ Load: SUGA pattern, 12 interfaces, RED FLAGS          │ │
│  │       Top 10 instant answers, routing patterns        │ │
│  │       Top 20 REF-IDs, import rules                    │ │
│  └────────────────────────────────────────────────────────┘ │
│                         │                                    │
│                         ▼                                    │
│  Step 2: File Server Setup (IF CODE WORK - 5 sec)          │
│  ┌────────────────────────────────────────────────────────┐ │
│  │ User uploads: File Server URLs.md                     │ │
│  │ Enables: web_fetch for complete Python files          │ │
│  │ Saves: 70K tokens + 4 minutes per modification       │ │
│  └────────────────────────────────────────────────────────┘ │
│                         │                                    │
│                         ▼                                    │
│  Step 3: Ready State                                        │
│  ┌────────────────────────────────────────────────────────┐ │
│  │ ✓ Critical architecture loaded                         │ │
│  │ ✓ Instant answers ready                                │ │
│  │ ✓ Routing patterns loaded                              │ │
│  │ ✓ RED FLAGS memorized                                  │ │
│  │ ✓ File server accessible (if code work)               │ │
│  └────────────────────────────────────────────────────────┘ │
│                                                              │
└──────────────────────────────────────────────────────────────┘
```

### 4.2 Per-Query Workflow

```
┌─────────────────────────────────────────────────────────┐
│                    QUERY RECEIVED                       │
└─────────────────────────────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────┐
│ 1. Check Instant Answers (SESSION-START)                │
│    Time: ~5 seconds                                     │
│    Result: Answer or route to next step                 │
└─────────────────────────────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────┐
│ 2. Check Workflows (if pattern recognized)              │
│    Time: ~10 seconds                                    │
│    Tool: Workflows-Playbook.md                          │
│    Result: Decision tree or route to next step          │
└─────────────────────────────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────┐
│ 3. Use Routing Map (Quick Index)                        │
│    Time: ~5 seconds                                     │
│    Result: Target file identified                       │
└─────────────────────────────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────┐
│ 4. Search Specific File                                 │
│    Time: ~10 seconds                                    │
│    Tool: project_knowledge_search                       │
│    Result: Relevant sections found                      │
└─────────────────────────────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────┐
│ 5. Read Complete Section(s)                             │
│    Time: ~15-20 seconds                                 │
│    Action: Read entire sections, no skimming            │
│    Result: Full context understood                      │
└─────────────────────────────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────┐
│ 6. Follow Cross-References (if needed)                  │
│    Time: ~5-10 seconds each                             │
│    Tool: REF-ID-Directory.md                            │
│    Result: Related context gathered                     │
└─────────────────────────────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────┐
│ 7. Verify Against Anti-Patterns (before suggestions)    │
│    Time: ~5 seconds                                     │
│    Tool: Anti-Patterns-Checklist.md                     │
│    Result: No violations                                │
└─────────────────────────────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────┐
│ 8. Respond with Citations                               │
│    Action: Cite REF-IDs, file locations, rationale      │
│    Result: Complete, verified answer                    │
└─────────────────────────────────────────────────────────┘
```

### 4.3 Code Modification Workflow

```
┌─────────────────────────────────────────────────────────┐
│           CODE MODIFICATION REQUEST                     │
└─────────────────────────────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────┐
│ 1. Verify File Server URLs Uploaded                     │
│    If not: Politely remind user                         │
└─────────────────────────────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────┐
│ 2. Search Project Knowledge (find file)                 │
│    Time: ~5 seconds                                     │
│    Tool: project_knowledge_search                       │
│    Result: File identified                              │
└─────────────────────────────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────┐
│ 3. Fetch Complete File (from file server)               │
│    Time: ~10 seconds                                    │
│    Tool: web_fetch                                      │
│    URL: https://claude.dizzybeaver.com/src/filename.py  │
│    Result: Complete file (header to EOF)                │
└─────────────────────────────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────┐
│ 4. Check Neural Maps (patterns/rules)                   │
│    Time: ~5 seconds                                     │
│    Result: SUGA pattern rules, anti-patterns, etc.      │
└─────────────────────────────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────┐
│ 5. Modify Complete File                                 │
│    Time: ~30 seconds                                    │
│    Context: Full file visible                           │
│    Action: Make changes with full understanding         │
└─────────────────────────────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────┐
│ 6. Verify with LESS-15 Protocol                         │
│    □ Read complete file                                 │
│    □ Verify SUGA pattern                                │
│    □ Check anti-patterns                                │
│    □ Verify dependencies                                │
│    □ Cite sources                                       │
└─────────────────────────────────────────────────────────┘
                          │
                          ▼
┌─────────────────────────────────────────────────────────┐
│ 7. Return Complete File in Artifact                     │
│    Result: Working code, header to EOF                  │
└─────────────────────────────────────────────────────────┘
```

---

## 5. Tool Ecosystem

### 5.1 Tool Hierarchy

```
                    SESSION-START-Quick-Context.md
                    (Load once per session)
                              │
                              │ Loads
                              ▼
         ┌────────────────────────────────────────┐
         │        CORE NEURAL MAPS                │
         │  (Authoritative source of truth)       │
         │                                        │
         │  • NM00-Quick-Index.md                 │
         │  • NM00A-Master-Index.md               │
         │  • NM01-* to NM07-* files              │
         └────────────────────────────────────────┘
                              │
         ┌────────────────────┼────────────────────┐
         │                    │                    │
         ▼                    ▼                    ▼
┌──────────────────┐  ┌──────────────────┐  ┌──────────────────┐
│   REF-ID         │  │   Workflows      │  │  Anti-Patterns   │
│   Directory      │  │   Playbook       │  │  Checklist       │
├──────────────────┤  ├──────────────────┤  ├──────────────────┤
│ • 159+ REF-IDs   │  │ • 10 workflows   │  │ • 28 anti-       │
│ • Alphabetical   │  │ • Decision trees │  │   patterns       │
│ • File locations │  │ • Templates      │  │ • Quick scan     │
│ • One-line desc  │  │ • Step-by-step   │  │ • Alternatives   │
│                  │  │                  │  │                  │
│ Use: Cross-ref   │  │ Use: Common      │  │ Use: Before      │
│      lookups     │  │      scenarios   │  │      suggestions │
│                  │  │                  │  │                  │
│ Saves: 10-20 sec │  │ Saves: 30-60 sec │  │ Saves: Prevents  │
│        per lookup│  │        per task  │  │        mistakes  │
└──────────────────┘  └──────────────────┘  └──────────────────┘
```

### 5.2 Tool Usage Matrix

```
┌────────────────────────┬──────────────────┬────────────────────────┐
│ TOOL NAME              │ WHEN TO USE      │ TIME SAVED             │
├────────────────────────┼──────────────────┼────────────────────────┤
│ SESSION-START-         │ Every session    │ Loads critical context │
│ Quick-Context.md       │ (mandatory)      │ once (4-6 min/session) │
├────────────────────────┼──────────────────┼────────────────────────┤
│ REF-ID-Directory.md    │ Cross-references │ 10-20 sec per lookup   │
│                        │ found (DEC-05,   │                        │
│                        │ BUG-01, etc.)    │                        │
├────────────────────────┼──────────────────┼────────────────────────┤
│ Workflows-Playbook.md  │ Common patterns  │ 30-60 sec per workflow │
│                        │ (add feature,    │                        │
│                        │ fix bug, etc.)   │                        │
├────────────────────────┼──────────────────┼────────────────────────┤
│ Anti-Patterns-         │ Before ANY       │ Prevents mistakes,     │
│ Checklist.md           │ suggestion       │ avoids corrections     │
├────────────────────────┼──────────────────┼────────────────────────┤
│ File Server URLs.md    │ Code modification│ 70K tokens + 4 min     │
│                        │ sessions         │ per modification       │
├────────────────────────┼──────────────────┼────────────────────────┤
│ NM00-Quick-Index.md    │ Keyword routing  │ 10-15 sec vs broad     │
│                        │                  │ search                 │
├────────────────────────┼──────────────────┼────────────────────────┤
│ NM00A-Master-Index.md  │ Complex nav      │ Complete system view   │
├────────────────────────┼──────────────────┼────────────────────────┤
│ NM##-INDEX-*.md        │ Category browse  │ Topic organization     │
├────────────────────────┼──────────────────┼────────────────────────┤
│ NM##-[DETAIL]-*.md     │ Detailed answers │ Complete explanations  │
└────────────────────────┴──────────────────┴────────────────────────┘
```

### 5.3 Tool Interaction Flow

```
                    User Query
                         │
                         ▼
            ┌─────────────────────────┐
            │ SESSION-START loaded?   │
            └─────────────────────────┘
                    │        │
               NO   │        │  YES
                    ▼        ▼
            ┌───────────┐  ┌────────────────────┐
            │ Load now  │  │ Check instant      │
            │ (30-45s)  │  │ answers            │
            └───────────┘  └────────────────────┘
                              │
                              ▼
                    ┌──────────────────────┐
                    │ Instant answer found?│
                    └──────────────────────┘
                         │        │
                    NO   │        │  YES
                         ▼        └──────► Respond
             ┌──────────────────────┐
             │ Workflow pattern?    │
             └──────────────────────┘
                    │        │
               NO   │        │  YES
                    │        ▼
                    │  ┌─────────────────────┐
                    │  │ Workflows-Playbook  │
                    │  └─────────────────────┘
                    │        │
                    ▼        │
             ┌──────────────┴───────┐
             │ Use Quick Index      │
             │ routing map          │
             └──────────────────────┘
                         │
                         ▼
             ┌──────────────────────┐
             │ Search neural maps   │
             └──────────────────────┘
                         │
                         ▼
             ┌──────────────────────┐
             │ REF-ID lookup needed?│
             └──────────────────────┘
                    │        │
               NO   │        │  YES
                    │        ▼
                    │  ┌─────────────────────┐
                    │  │ REF-ID-Directory    │
                    │  └─────────────────────┘
                    │        │
                    ▼        │
         ┌──────────────────┴────────┐
         │ Before suggesting solution│
         │ Check Anti-Patterns       │
         └───────────────────────────┘
                         │
                         ▼
                   Respond with
                   citations
```

---

## 6. File Dependency Map

### 6.1 Core File Dependencies

```
SESSION-START-Quick-Context.md (Always first)
         │
         ├──► References: NM00-Quick-Index.md
         ├──► References: Top 10 Instant Answers
         ├──► References: Query Routing Patterns
         ├──► References: RED FLAGS
         ├──► References: Top 20 REF-IDs
         └──► References: File Server workflow

NM00-Quick-Index.md (Gateway)
         │
         ├──► Routes to: All NM##-INDEX-*.md files
         ├──► Routes to: Specific implementation files
         └──► Cross-refs: REF-IDs throughout

NM00A-Master-Index.md (Gateway)
         │
         ├──► Links to: All neural map files
         ├──► Provides: Complete navigation
         └──► Shows: File relationships

REF-ID-Directory.md (Tool)
         │
         ├──► Lists: 159+ REF-IDs
         ├──► Points to: Specific sections in files
         └──► Used by: All neural maps

Workflows-Playbook.md (Tool)
         │
         ├──► References: Neural maps for decisions
         ├──► Uses: Anti-Patterns-Checklist
         └──► Outputs: Step-by-step guides

Anti-Patterns-Checklist.md (Tool)
         │
         ├──► References: NM05-Anti-Patterns-*.md
         ├──► References: Design decisions (DEC-##)
         └──► Used by: Verification protocol

File Server URLs.md (Tool - User uploaded)
         │
         ├──► Enables: web_fetch for Python files
         └──► Points to: https://claude.dizzybeaver.com/src/
```

### 6.2 Neural Map Index Dependencies

```
NM01-INDEX-Architecture.md
         │
         ├──► NM01-CORE-Architecture.md
         ├──► NM01-INTERFACES-Core.md
         ├──► NM01-RULES-Foundation.md
         ├──► NM01-ARCH-07-LMMS.md
         └──► NM01-ARCH-08-Future.md

NM02-INDEX-Dependencies.md
         │
         ├──► NM02-RULES-Import.md
         ├──► NM02-DEPENDENCIES-Layers.md
         └──► NM02-WORKFLOW-Dependency.md

NM03-INDEX-Operations.md
         │
         ├──► NM03-CORE-Pathways.md
         ├──► NM03-ERROR-Handling.md
         └──► NM03-MONITORING-Metrics.md

NM04-INDEX-Decisions.md
         │
         ├──► NM04-TECHNICAL-Decisions.md
         ├──► NM04-DESIGN-Rationale.md
         └──► NM04-SECURITY-Choices.md

NM05-INDEX-Anti-Patterns.md
         │
         ├──► NM05-Anti-Patterns-Part-1.md (AP-01 to AP-15)
         └──► NM05-Anti-Patterns-Part-2.md (AP-16 to AP-28)

NM06-INDEX-Learned.md
         │
         ├──► NM06-BUGS-Critical.md
         ├──► NM06-LESSONS-Performance.md
         ├──► NM06-WISDOM-Development.md
         └──► NM06-LESS-##-*.md (21+ lesson files)

NM07-INDEX-DecisionLogic.md
         │
         ├──► NM07-DECISION-Trees.md
         └──► NM07-WORKFLOW-Patterns.md
```

### 6.3 Cross-Reference Web

```
                        NM04-DEC-04
                    (No threading locks)
                            │
            ┌───────────────┼───────────────┐
            │               │               │
            ▼               ▼               ▼
    NM05-AP-08      NM06-LESS-06    NM01-ARCH-01
    (Anti-pattern)  (Lesson)        (Architecture)
            │
            └──────────────┐
                           │
                           ▼
                   NM02-RULE-01
                (Import via gateway)
                           │
            ┌──────────────┼──────────────┐
            │              │              │
            ▼              ▼              ▼
    NM05-AP-01      NM01-ARCH-01   NM07-DECISION
    (Direct import) (SUGA pattern) (Import tree)

                    NM06-BUG-01
                  (Sentinel leak)
                         │
            ┌────────────┼────────────┐
            │            │            │
            ▼            ▼            ▼
    NM04-DEC-05    NM06-BUG-02   NM03-PATH-01
    (Sanitize)     (_CacheMiss)  (Cold start)
```

---

## 7. REF-ID Cross-Reference System

### 7.1 REF-ID Structure

```
Format: [CATEGORY]-[NUMBER]

Categories:
  ARCH   = Architecture patterns (ARCH-01 to ARCH-08)
  RULE   = Foundation rules (RULE-01, RULE-02, etc.)
  DEC    = Design decisions (DEC-01 to DEC-24+)
  INT    = Interfaces (INT-01 to INT-12)
  PATH   = Operation pathways (PATH-01, PATH-02, etc.)
  ERR    = Error handling (ERR-01, ERR-02, etc.)
  DEP    = Dependency layers (DEP-01 to DEP-08)
  AP     = Anti-patterns (AP-01 to AP-28)
  BUG    = Bugs (BUG-01, BUG-02, etc.)
  LESS   = Lessons learned (LESS-01 to LESS-21+)
```

### 7.2 Top 20 Critical REF-IDs

```
┌────────────┬──────────────────────────────────────────────────┐
│ REF-ID     │ DESCRIPTION                                      │
├────────────┼──────────────────────────────────────────────────┤
│ ARCH-01    │ Gateway trinity (3-layer pattern)                │
│ RULE-01    │ Cross-interface via gateway only                 │
│ DEC-01     │ SUGA pattern choice (prevents circular imports)  │
│ DEC-04     │ No threading locks (Lambda single-threaded)      │
│ DEC-05     │ Sentinel sanitization (router layer)             │
│ DEC-07     │ Dependencies < 128MB                             │
│ DEC-08     │ Flat file structure (proven simple)              │
│ DEC-21     │ SSM token-only (simplified config)               │
│ AP-01      │ Direct cross-interface imports                   │
│ AP-08      │ Threading primitives                             │
│ AP-14      │ Bare except clauses                              │
│ AP-19      │ Sentinel objects crossing boundaries             │
│ BUG-01     │ Sentinel leak (535ms cost)                       │
│ BUG-02     │ _CacheMiss validation                            │
│ LESS-01    │ Read complete files first                        │
│ LESS-15    │ 5-step verification protocol                     │
│ INT-01     │ CACHE interface                                  │
│ PATH-01    │ Cold start pathway                               │
│ ERR-02     │ Error propagation patterns                       │
│ ARCH-07    │ LMMS (Memory management system)                  │
└────────────┴──────────────────────────────────────────────────┘
```

### 7.3 REF-ID Lookup Flow

```
            REF-ID mentioned in content
                 (e.g., "See DEC-04")
                         │
                         ▼
            ┌─────────────────────────────┐
            │ Search: "REF-ID-Directory   │
            │         DEC-04"              │
            └─────────────────────────────┘
                         │
                         ▼
            ┌─────────────────────────────┐
            │ Result:                      │
            │ DEC-04: NM04-TECHNICAL-      │
            │         Decisions.md         │
            │ "No threading locks"         │
            └─────────────────────────────┘
                         │
                         ▼
            ┌─────────────────────────────┐
            │ Search: "NM04-TECHNICAL-     │
            │         Decisions DEC-04"    │
            └─────────────────────────────┘
                         │
                         ▼
            ┌─────────────────────────────┐
            │ Read: Complete DEC-04        │
            │       section                │
            └─────────────────────────────┘
                         │
                         ▼
            ┌─────────────────────────────┐
            │ Related: AP-08, LESS-06,     │
            │          ARCH-01             │
            └─────────────────────────────┘
                         │
                         ▼
            (Repeat for related REF-IDs if needed)
```

---

## 8. Decision Trees

### 8.1 Import Decision Tree

```
                    "Can I import [X]?"
                            │
                            ▼
                ┌───────────────────────────┐
                │ Is X in same file?        │
                └───────────────────────────┘
                       │           │
                  YES  │           │  NO
                       ▼           ▼
                ┌──────────┐  ┌──────────────────────────┐
                │ Direct   │  │ Is X in gateway?         │
                │ use      │  └──────────────────────────┘
                └──────────┘         │           │
                                YES  │           │  NO
                                     ▼           ▼
                          ┌────────────────┐  ┌────────────────────┐
                          │ import gateway │  │ Is X in core file? │
                          │ gateway.X()    │  └────────────────────┘
                          └────────────────┘         │           │
                                                YES  │           │  NO
                                                     ▼           ▼
                                          ┌────────────────┐  ┌──────────────┐
                                          │ NEVER direct   │  │ import       │
                                          │ Use gateway!   │  │ external lib │
                                          │ (RULE-01)      │  └──────────────┘
                                          └────────────────┘
```

### 8.2 Feature Addition Decision Tree

```
                "I want to add feature [X]"
                            │
                            ▼
            ┌───────────────────────────────────┐
            │ Does gateway already provide it?  │
            └───────────────────────────────────┘
                       │                │
                  YES  │                │  NO
                       ▼                ▼
            ┌────────────────┐   ┌────────────────────────┐
            │ Use existing   │   │ Is it an anti-pattern? │
            │ function       │   └────────────────────────┘
            └────────────────┘          │           │
                                   YES  │           │  NO
                                        ▼           ▼
                            ┌──────────────────┐  ┌──────────────────┐
                            │ Explain why not  │  │ Which interface? │
                            │ Suggest alt      │  └──────────────────┘
                            └──────────────────┘           │
                                                            ▼
                                            ┌────────────────────────────┐
                                            │ Follow SUGA pattern:       │
                                            │ 1. Add to gateway          │
                                            │ 2. Add to interface        │
                                            │ 3. Implement in core       │
                                            └────────────────────────────┘
                                                            │
                                                            ▼
                                            ┌────────────────────────────┐
                                            │ Verify with LESS-15        │
                                            └────────────────────────────┘
```

### 8.3 Bug Report Decision Tree

```
                "I'm getting error [X]"
                            │
                            ▼
            ┌───────────────────────────────┐
            │ Is it a known bug?            │
            │ (Search NM06-BUGS-Critical)   │
            └───────────────────────────────┘
                       │           │
                  YES  │           │  NO
                       ▼           ▼
            ┌────────────────┐  ┌────────────────────────┐
            │ Provide known  │  │ Is it an error pattern?│
            │ solution       │  │ (Search NM03-ERROR)    │
            │ (cite BUG-##)  │  └────────────────────────┘
            └────────────────┘         │           │
                                  YES  │           │  NO
                                       ▼           ▼
                        ┌─────────────────────┐  ┌──────────────────┐
                        │ Provide pattern     │  │ Ask for:         │
                        │ solution            │  │ - Complete error │
                        │ (cite ERR-##)       │  │ - Code context   │
                        └─────────────────────┘  │ - Recent changes │
                                                  └──────────────────┘
                                                           │
                                                           ▼
                                                  ┌─────────────────┐
                                                  │ Debug with user │
                                                  └─────────────────┘
```

---

## 9. Token Management Flow

### 9.1 Token Monitoring Protocol

```
┌─────────────────────────────────────────────────────────────┐
│               SESSION TOKEN MANAGEMENT                      │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  Start of Session                                           │
│  ┌──────────────────────────────────────────────────────┐  │
│  │ Token Budget: 190,000                                 │  │
│  │ Context Load: ~30-40K tokens                          │  │
│  │ Available: ~150-160K for work                         │  │
│  └──────────────────────────────────────────────────────┘  │
│                         │                                   │
│                         ▼                                   │
│  During Work                                                │
│  ┌──────────────────────────────────────────────────────┐  │
│  │ Monitor after each major phase:                       │  │
│  │                                                        │  │
│  │ Phase 1 complete: 150K remaining → Continue           │  │
│  │ Phase 2 complete: 120K remaining → Continue           │  │
│  │ Phase 3 complete: 90K remaining  → Continue           │  │
│  │ Phase 4 complete: 60K remaining  → Continue           │  │
│  │ Phase 5 complete: 30K remaining  → ⚠️ Getting low     │  │
│  │ Phase 6 complete: 15K remaining  → 🛑 STOP            │  │
│  └──────────────────────────────────────────────────────┘  │
│                         │                                   │
│                         ▼                                   │
│  When < 20K Tokens (Automatic Transition)                  │
│  ┌──────────────────────────────────────────────────────┐  │
│  │ 1. Stop current work                                  │  │
│  │ 2. Create transition document:                        │  │
│  │    - Work completed                                   │  │
│  │    - Work remaining                                   │  │
│  │    - Files modified                                   │  │
│  │    - Next steps                                       │  │
│  │ 3. Inform user: "Created transition doc for next      │  │
│  │    session"                                           │  │
│  └──────────────────────────────────────────────────────┘  │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

### 9.2 Token Usage Breakdown

```
┌──────────────────────┬─────────────────┬────────────────────┐
│ ACTIVITY             │ TYPICAL TOKENS  │ CUMULATIVE         │
├──────────────────────┼─────────────────┼────────────────────┤
│ Initial context load │ 30-40K          │ 30-40K             │
│ (SESSION-START)      │                 │                    │
├──────────────────────┼─────────────────┼────────────────────┤
│ Simple query         │ 2-5K            │ 32-45K             │
│ (instant answer)     │                 │                    │
├──────────────────────┼─────────────────┼────────────────────┤
│ Medium query         │ 5-10K           │ 37-55K             │
│ (search + respond)   │                 │                    │
├──────────────────────┼─────────────────┼────────────────────┤
│ Complex query        │ 15-30K          │ 52-85K             │
│ (multiple searches)  │                 │                    │
├──────────────────────┼─────────────────┼────────────────────┤
│ Code modification    │ 20-40K          │ 72-125K            │
│ (old method)         │                 │                    │
├──────────────────────┼─────────────────┼────────────────────┤
│ Code modification    │ 5-10K           │ 77-135K            │
│ (file server)        │                 │                    │
├──────────────────────┼─────────────────┼────────────────────┤
│ Document creation    │ 10-20K          │ 87-155K            │
│                      │                 │                    │
├──────────────────────┼─────────────────┼────────────────────┤
│ Transition doc       │ 5-10K           │ 92-165K            │
│                      │                 │                    │
└──────────────────────┴─────────────────┴────────────────────┘
```

---

## 10. File Server Integration

### 10.1 File Server Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    FILE SERVER WORKFLOW                     │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  Step 1: User Uploads URLs (Session Start)                 │
│  ┌──────────────────────────────────────────────────────┐  │
│  │ File: "File Server URLs.md"                          │  │
│  │ Contains: Directory listing with URLs                 │  │
│  │ Enables: web_fetch permissions for session            │  │
│  │ Time: ~5 seconds                                      │  │
│  └──────────────────────────────────────────────────────┘  │
│                         │                                   │
│                         ▼                                   │
│  Step 2: Find File (project_knowledge_search)               │
│  ┌──────────────────────────────────────────────────────┐  │
│  │ Search: "cache_core.py" or description                │  │
│  │ Result: Identifies which file is needed               │  │
│  │ Time: ~5 seconds                                      │  │
│  └──────────────────────────────────────────────────────┘  │
│                         │                                   │
│                         ▼                                   │
│  Step 3: Fetch Complete File (web_fetch)                   │
│  ┌──────────────────────────────────────────────────────┐  │
│  │ URL: https://claude.dizzybeaver.com/src/[file].py    │  │
│  │ Returns: Complete file (header to EOF)                │  │
│  │ Time: ~10 seconds                                     │  │
│  └──────────────────────────────────────────────────────┘  │
│                         │                                   │
│                         ▼                                   │
│  Step 4: Modify with Full Context                          │
│  ┌──────────────────────────────────────────────────────┐  │
│  │ Context: Entire file visible                          │  │
│  │ Action: Make informed changes                         │  │
│  │ Time: ~30 seconds                                     │  │
│  └──────────────────────────────────────────────────────┘  │
│                         │                                   │
│                         ▼                                   │
│  Step 5: Return Complete File (artifact)                   │
│  ┌──────────────────────────────────────────────────────┐  │
│  │ Format: Complete Python file                          │  │
│  │ Includes: Header, imports, all functions, EOF         │  │
│  │ Result: Working, deployable code                      │  │
│  └──────────────────────────────────────────────────────┘  │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

### 10.2 File Server vs Project Knowledge

```
┌──────────────────────┬───────────────────┬──────────────────┐
│ ASPECT               │ PROJECT KNOWLEDGE │ FILE SERVER      │
├──────────────────────┼───────────────────┼──────────────────┤
│ Content type         │ Snippets/fragments│ Complete files   │
│ Best for             │ Finding files     │ Modifying code   │
│ Returns              │ Relevant sections │ Header to EOF    │
│ Token cost           │ High (50-80K)     │ Low (5-10K)      │
│ Time                 │ Variable          │ Consistent       │
│ Context              │ Partial           │ Complete         │
│ Setup required       │ None              │ URLs upload      │
│ Use case             │ Search/identify   │ Modify/deploy    │
└──────────────────────┴───────────────────┴──────────────────┘
```

### 10.3 Hybrid Workflow

```
        Code Modification Request
                   │
                   ▼
┌──────────────────────────────────────────────────────────┐
│ Phase 1: IDENTIFY (Project Knowledge)                   │
├──────────────────────────────────────────────────────────┤
│ Search project knowledge:                                │
│ - Find which file to modify                              │
│ - Understand current implementation                      │
│ - Gather related context                                 │
│                                                          │
│ Tools: project_knowledge_search                          │
│ Time: ~5-10 seconds                                      │
│ Tokens: ~2-5K                                            │
└──────────────────────────────────────────────────────────┘
                   │
                   ▼
┌──────────────────────────────────────────────────────────┐
│ Phase 2: FETCH (File Server)                            │
├──────────────────────────────────────────────────────────┤
│ Fetch complete file:                                     │
│ - Use web_fetch with URL                                 │
│ - Get entire file (header to EOF)                        │
│ - Load into working context                              │
│                                                          │
│ Tools: web_fetch                                         │
│ Time: ~10 seconds                                        │
│ Tokens: ~3-5K                                            │
└──────────────────────────────────────────────────────────┘
                   │
                   ▼
┌──────────────────────────────────────────────────────────┐
│ Phase 3: MODIFY (With Full Context)                     │
├──────────────────────────────────────────────────────────┤
│ Make changes:                                            │
│ - See all functions                                      │
│ - Understand dependencies                                │
│ - Maintain consistency                                   │
│ - Follow patterns                                        │
│                                                          │
│ Time: ~30 seconds                                        │
│ Tokens: Minimal                                          │
└──────────────────────────────────────────────────────────┘
                   │
                   ▼
┌──────────────────────────────────────────────────────────┐
│ Phase 4: VERIFY (Neural Maps + Checklist)               │
├──────────────────────────────────────────────────────────┤
│ Verification protocol (LESS-15):                         │
│ □ Read complete file                                     │
│ □ Verify SUGA pattern                                    │
│ □ Check anti-patterns                                    │
│ □ Verify dependencies                                    │
│ □ Cite sources                                           │
│                                                          │
│ Time: ~20 seconds                                        │
│ Tokens: ~5K                                              │
└──────────────────────────────────────────────────────────┘
                   │
                   ▼
┌──────────────────────────────────────────────────────────┐
│ Phase 5: DELIVER (Artifact)                             │
├──────────────────────────────────────────────────────────┤
│ Return complete file:                                    │
│ - Artifact with full code                                │
│ - Working from header to EOF                             │
│ - Explanatory comments                                   │
│ - Citations to decisions                                 │
│                                                          │
│ Time: ~10 seconds                                        │
│ Tokens: Minimal                                          │
└──────────────────────────────────────────────────────────┘

Total: ~55 seconds, ~15-20K tokens
Old method: 3-5 minutes, 50-80K tokens
Savings: 75% time, 70% tokens
```

---

## Summary Statistics

### Current SIMA v2 Metrics

```
┌──────────────────────────────┬─────────────────────────────┐
│ METRIC                       │ VALUE                       │
├──────────────────────────────┼─────────────────────────────┤
│ Total neural map files       │ ~32 files                   │
│ - Gateway layer              │ 2 files                     │
│ - Interface layer            │ 7 files                     │
│ - Implementation layer       │ ~23 files                   │
├──────────────────────────────┼─────────────────────────────┤
│ Total REF-IDs                │ 159+                        │
│ - Architecture (ARCH)        │ 8                           │
│ - Rules (RULE)               │ 4+                          │
│ - Decisions (DEC)            │ 24+                         │
│ - Interfaces (INT)           │ 12                          │
│ - Anti-patterns (AP)         │ 28                          │
│ - Bugs (BUG)                 │ 2+                          │
│ - Lessons (LESS)             │ 21+                         │
│ - Others                     │ 60+                         │
├──────────────────────────────┼─────────────────────────────┤
│ Optimization tools           │ 4                           │
│ - SESSION-START              │ Context loader              │
│ - REF-ID-Directory           │ Cross-reference tool        │
│ - Workflows-Playbook         │ Common scenarios            │
│ - Anti-Patterns-Checklist    │ Verification tool           │
├──────────────────────────────┼─────────────────────────────┤
│ Workflows defined            │ 10+                         │
│ Decision trees               │ Multiple per file           │
│ Instant answers              │ 12 (Top 10 + 2 new)         │
├──────────────────────────────┼─────────────────────────────┤
│ Query resolution time        │                             │
│ - Instant (cached)           │ 5 seconds                   │
│ - Simple (routing)           │ 10-15 seconds               │
│ - Medium (search)            │ 30-45 seconds               │
│ - Complex (multiple)         │ 60-90 seconds               │
├──────────────────────────────┼─────────────────────────────┤
│ Code modification time       │                             │
│ - Old method                 │ 3-5 minutes                 │
│ - File server method         │ 55 seconds                  │
│ - Improvement                │ 75% faster                  │
├──────────────────────────────┼─────────────────────────────┤
│ Token efficiency             │                             │
│ - Old method                 │ 50-80K tokens               │
│ - New method                 │ 5-20K tokens                │
│ - Savings                    │ 70K tokens                  │
└──────────────────────────────┴─────────────────────────────┘
```

---

## End of Document

**Purpose:** Visual reference for SIMA v3 upgrade design  
**Current Version:** SIMA v2.1.0  
**Status:** Complete structural documentation  
**Next Step:** Design SIMA v3 improvements based on these diagrams
