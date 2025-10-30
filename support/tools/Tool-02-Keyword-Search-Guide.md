# File: Tool-02-Keyword-Search-Guide.md

**REF-ID:** TOOL-02  
**Version:** 1.0.0  
**Category:** Support Tools  
**Type:** Search and Navigation  
**Purpose:** Guide for effective keyword-based searches in SIMA

---

## 📋 OVERVIEW

**Use this tool to:** Find entries when you don't know the REF-ID but know the topic or problem

**Method:** Problem description → Keywords → project_knowledge_search → Results  
**Time:** < 1 minute per search

---

## 🎯 SEARCH STRATEGIES

### Strategy 1: Problem-Based Search

**When:** You have a specific problem to solve

**Process:**
```
1. Describe problem: "How do I avoid circular imports?"
2. Extract keywords: "circular imports", "gateway"
3. Search: project_knowledge_search: "circular imports"
4. Scan results for relevant entries
```

**Common Problems → Keywords:**
```
Problem: How to add feature?
Keywords: "add feature", "workflow", "SUGA"

Problem: Circular import error?
Keywords: "circular import", "gateway", "lazy loading"

Problem: Lambda cold start slow?
Keywords: "cold start", "ZAPH", "optimization"

Problem: Cache not working?
Keywords: "cache", "INT-01", "CACHE interface"

Problem: How to log errors?
Keywords: "logging", "INT-02", "error handling"

Problem: Security best practices?
Keywords: "security", "INT-03", "encryption"
```

---

### Strategy 2: Category-Based Search

**When:** You want to explore a category

**Process:**
```
1. Identify category: Architecture, Gateway, Interface, etc.
2. Search: project_knowledge_search: "[category]"
3. Browse results
```

**Category Keywords:**
```
Architecture:
- "architecture", "SUGA", "LMMS", "ZAPH", "patterns"

Gateway:
- "gateway", "lazy loading", "imports", "dispatch"

Interface:
- "interface", "INT-", "catalog", specific interface names

Language:
- "python", "PEP 8", "exceptions", "type hints"

Project:
- "NMP", "LEE", "implementation", "catalog"

Lessons:
- "lesson", "LESS", "mistake", "learning"

Bugs:
- "bug", "error", "issue", "fix"
```

---

### Strategy 3: Implementation-Based Search

**When:** You need to implement something specific

**Process:**
```
1. What are you implementing?: "Cache function"
2. Search implementation details: "cache implementation"
3. Search examples: "cache example"
4. Search interface: "INT-01" or "CACHE interface"
```

**Implementation Scenarios:**
```
Scenario: Add cache function
Search: "INT-01", "cache catalog", "cache example"

Scenario: Add logging
Search: "INT-02", "logging catalog", "log error"

Scenario: Security validation
Search: "INT-03", "security", "validation"

Scenario: Gateway entry
Search: "gateway", "lazy imports", "LAZY_IMPORTS"

Scenario: Home Assistant API
Search: "HA", "home assistant", "NMP01-LEE-17"
```

---

### Strategy 4: Pattern-Based Search

**When:** Looking for architectural or code patterns

**Process:**
```
1. Pattern type: "Three-layer architecture"
2. Search pattern name: "SUGA"
3. Or search problem it solves: "separation of concerns"
```

**Pattern Categories:**
```
Architecture Patterns:
- "SUGA", "three-layer", "gateway pattern"
- "LMMS", "multi-layer", "dependencies"
- "ZAPH", "performance", "cold start"

Gateway Patterns:
- "lazy loading", "circular imports", "dispatch"
- "cross-interface", "wrappers"

Interface Patterns:
- Specific interfaces: "CACHE", "LOGGING", "SECURITY"
- Dependencies: "L0", "L1", "L2", "L3", "L4"

Code Patterns:
- "exception handling", "type hints", "PEP 8"
- "function design", "imports"
```

---

## 🔍 EFFECTIVE KEYWORDS

### High-Value Keywords

**Architecture:**
```
✅ GOOD: "SUGA", "gateway", "three-layer", "separation"
❌ AVOID: "code", "structure", "system" (too generic)
```

**Interfaces:**
```
✅ GOOD: "CACHE", "INT-01", "cache_get", "cache operations"
❌ AVOID: "interface", "function" (too generic without context)
```

**Performance:**
```
✅ GOOD: "ZAPH", "cold start", "lazy loading", "optimization"
❌ AVOID: "fast", "slow", "performance" (need specifics)
```

**Errors:**
```
✅ GOOD: "circular import", "ImportError", "sentinel bug"
❌ AVOID: "error", "problem", "issue" (too vague)
```

### Keyword Combinations

**Single keyword** (broad):
```
"cache" → Returns all cache-related entries
```

**Two keywords** (focused):
```
"cache implementation" → Returns implementation guides
"cache example" → Returns code examples
```

**Three+ keywords** (specific):
```
"cache INT-01 catalog" → Returns specific interface catalog
"cache function usage LEE" → Returns project-specific usage
```

---

## 📊 SEARCH RESULT INTERPRETATION

### Understanding Results

**Result Types:**

**1. Direct Match:**
```
Result contains exact keyword in title
→ Primary resource for that topic
```

**2. Context Match:**
```
Result mentions keyword in content
→ Related information, may be helpful
```

**3. Cross-Reference Match:**
```
Result links to keyword topic
→ Tangentially related
```

### Filtering Results

**By Relevance:**
```
High Relevance:
- Keyword in title
- Keyword in purpose statement
- Multiple keyword matches

Medium Relevance:
- Keyword in main content
- Single keyword match
- Related cross-references

Low Relevance:
- Keyword in footnotes
- Brief mention only
```

**By Type:**
```
Need Implementation?
→ Look for: INT-##, NMP-##, examples

Need Concepts?
→ Look for: ARCH-##, GATE-##, patterns

Need Troubleshooting?
→ Look for: BUG-##, LESS-##, DEBUG

Need Process?
→ Look for: WF-##, CHK-##
```

---

## 🎯 SEARCH TEMPLATES

### Template 1: "How do I...?"

```
Problem: "How do I add a cache function?"

Search Strategy:
1. "add feature workflow" → Find WF-01
2. "cache interface" → Find INT-01
3. "cache example LEE" → Find NMP01-LEE-02

Result: Complete implementation path
```

### Template 2: "Why is...?"

```
Problem: "Why is there a circular import error?"

Search Strategy:
1. "circular import" → Find BUG-01
2. "gateway lazy loading" → Find GATE-03
3. "import rules" → Find RULE-01

Result: Root cause and solution
```

### Template 3: "What is...?"

```
Problem: "What is ZAPH?"

Search Strategy:
1. "ZAPH" → Find ARCH-04
2. "ZAPH example" → Find NMP01-LEE-16
3. "performance optimization" → Related patterns

Result: Pattern explanation and usage
```

### Template 4: "Where can I find...?"

```
Problem: "Where can I find cache usage examples?"

Search Strategy:
1. "cache catalog" → Find INT-01 or NMP01-LEE-02
2. "cache examples" → Code samples
3. "INT-01 LEE" → Project-specific usage

Result: Real-world examples
```

---

## 💡 ADVANCED SEARCH TECHNIQUES

### Boolean Logic (Conceptual)

**AND (both terms):**
```
"cache validation" → Results with both words
Finds: Cache validation functions
```

**OR (either term):**
```
Search twice:
"cache" → All cache entries
"storage" → All storage entries
Combine results mentally
```

**NOT (exclude term):**
```
Can't exclude in simple search
Instead: Scan results, skip irrelevant
```

### Phrase Search

**Exact phrase:**
```
"lazy loading pattern" → Exact match preferred
Better than: "lazy", "loading", "pattern" separately
```

**Partial phrase:**
```
"gateway dispatch" → Find specific gateway topic
Better than: "gateway" (too broad)
```

### Hierarchical Search

**Broad to Narrow:**
```
1. "interface" → See all interfaces
2. "CACHE interface" → Narrow to cache
3. "cache_get function" → Specific function
```

**Category Drill-Down:**
```
1. "architecture" → All architecture entries
2. "SUGA architecture" → Specific pattern
3. "SUGA verification" → Implementation checklist
```

---

## 🎓 SEARCH EXAMPLES

### Example 1: Find How to Add Feature

**Query:** "How do I add a new feature to the CACHE interface?"

**Search Process:**
```
Step 1: project_knowledge_search: "add feature workflow"
→ Result: WF-01 (Add Feature Workflow)

Step 2: project_knowledge_search: "CACHE interface"
→ Result: INT-01 (CACHE Interface)

Step 3: project_knowledge_search: "cache catalog LEE"
→ Result: NMP01-LEE-02 (CACHE Function Catalog)

Outcome: Complete path from workflow to implementation example
```

### Example 2: Debug Circular Import

**Query:** "Getting circular import error, how to fix?"

**Search Process:**
```
Step 1: project_knowledge_search: "circular import"
→ Result: BUG-01 (Circular Import Sentinel Bug)

Step 2: project_knowledge_search: "gateway lazy loading"
→ Result: GATE-03 (Lazy Loading Pattern)

Step 3: project_knowledge_search: "import rules"
→ Result: RULE-01 (Always Import Via Gateway)

Outcome: Root cause identified, solution clear
```

### Example 3: Optimize Cold Start

**Query:** "Lambda cold start is too slow, how to optimize?"

**Search Process:**
```
Step 1: project_knowledge_search: "cold start optimization"
→ Result: ARCH-04 (ZAPH Pattern)

Step 2: project_knowledge_search: "ZAPH implementation"
→ Result: NMP01-LEE-16 (Fast Path ZAPH)

Step 3: project_knowledge_search: "lazy loading"
→ Result: GATE-03 (Lazy Loading)

Outcome: Optimization strategy and implementation guide
```

---

## ⚠️ SEARCH PITFALLS

### Pitfall 1: Too Generic

```
❌ Search: "code"
Result: Everything mentions code

✅ Search: "SUGA code structure"
Result: Architecture pattern with code examples
```

### Pitfall 2: Too Specific

```
❌ Search: "cache_get timeout parameter validation"
Result: Too narrow, may miss relevant content

✅ Search: "cache_get" then "validation"
Result: Find function, then find validation patterns
```

### Pitfall 3: Wrong Category

```
❌ Search: "interface" expecting architecture
Result: Gets interface patterns, not architecture

✅ Search: "architecture patterns"
Result: Gets ARCH-## entries
```

### Pitfall 4: Assuming Terminology

```
❌ Search: "controller" (wrong term)
Result: No matches (SIMA uses "gateway")

✅ Search: "gateway" (correct term)
Result: Finds gateway patterns
```

---

## 📚 KEYWORD GLOSSARY

### Core Terms

**SUGA:** Three-layer architecture (Search: "SUGA")  
**Gateway:** Entry point, lazy loading (Search: "gateway")  
**Interface:** Public API layer (Search: "interface", "INT-")  
**Core:** Implementation layer (Search: "core", "_private")  
**ZAPH:** Performance optimization (Search: "ZAPH", "cold start")  
**LMMS:** Multi-layer separation (Search: "LMMS", "dependencies")

### Interface Names

**CACHE:** Caching operations (Search: "CACHE", "INT-01")  
**LOGGING:** Logging operations (Search: "LOGGING", "INT-02")  
**SECURITY:** Security operations (Search: "SECURITY", "INT-03")  
**CONFIG:** Configuration (Search: "CONFIG", "INT-04")  
**SINGLETON:** Singleton pattern (Search: "SINGLETON", "INT-05")  
**UTILITY:** Utilities (Search: "UTILITY", "INT-06")  
**METRICS:** Metrics (Search: "METRICS", "INT-07")  
**WEBSOCKET:** Websockets (Search: "WEBSOCKET", "INT-08")  
**DATA:** Data operations (Search: "DATA", "INT-09")  
**BLE:** Bluetooth (Search: "BLE", "INT-10")  
**API:** API operations (Search: "API", "INT-11")  
**HA:** Home Assistant (Search: "HA", "INT-12")

### Common Patterns

**Lazy Loading:** Import inside function (Search: "lazy loading")  
**Circular Import:** Import cycle error (Search: "circular import")  
**Sentinel Object:** Marker object (Search: "sentinel")  
**Type Hints:** Python types (Search: "type hints")  
**Exception Handling:** Error handling (Search: "exception")  
**PEP 8:** Python style (Search: "PEP 8")

---

## ✅ SEARCH SUCCESS CHECKLIST

**Before searching:**
- [ ] Problem clearly defined
- [ ] Keywords identified (2-4 words)
- [ ] Category known (Architecture, Interface, etc.)

**During search:**
- [ ] Results reviewed systematically
- [ ] Multiple searches if needed
- [ ] Cross-references followed

**After search:**
- [ ] Solution found or narrowed down
- [ ] Related entries noted
- [ ] Next steps clear

---

## 🔗 RELATED TOOLS

**Reference Tools:**
- TOOL-01: REF-ID Lookup (when you know the ID)
- Quick Indexes (problem-based navigation)
- Cross-Reference Matrices (relationship mapping)

**Support Documents:**
- SESSION-START-Quick-Context.md (General Mode)
- PROJECT-MODE-Context.md (Project Mode)
- DEBUG-MODE-Context.md (Debug Mode)

---

**END OF TOOL-02**

**Related tools:** TOOL-01 (REF-ID Lookup), Quick Indexes (all categories)
