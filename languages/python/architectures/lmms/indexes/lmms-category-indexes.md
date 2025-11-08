# lmms-category-indexes.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Category indexes for LMMS architecture  
**Architecture:** LMMS (Python)

---

## CORE CONCEPTS INDEX

### By Topic

**Cold Start Optimization:**
- LMMS-01: Core Concept
- LMMS-02: Cold Start Optimization
- LMMS-03: Import Strategy

**Performance Measurement:**
- LMMS-02: Cold Start Optimization (profiling section)
- LMMS-03: Import Strategy (profiling workflow)
- LMMS-LESS-01: Profile First Always
- LMMS-LESS-02: Measure Impact Always

**Import Classification:**
- LMMS-03: Import Strategy (tier system)
- LMMS-DEC-01: Function-Level Imports (criteria)
- LMMS-DEC-02: Hot Path Exceptions (thresholds)

---

## DECISIONS INDEX

### By Impact Level

**Critical (High Impact):**
1. LMMS-DEC-01: Function-Level Imports (60% improvement)
2. LMMS-DEC-03: Import Profiling Required (prevents waste)
3. LMMS-DEC-04: Fast Path File Required (prevents regressions)

**Important (Moderate Impact):**
4. LMMS-DEC-02: Hot Path Exceptions (balances performance)

### By Category

**Strategy:**
- LMMS-DEC-01: Function-Level Imports
- LMMS-DEC-02: Hot Path Exceptions
- LMMS-DEC-03: Import Profiling Required

**Code Organization:**
- LMMS-DEC-04: Fast Path File Required

---

## LESSONS INDEX

### By Cost

**High Cost Lessons (>10 hours wasted):**
- LMMS-LESS-01: Profile First Always (10 hours)
- LMMS-LESS-02: Measure Impact Always (10 hours)

**Moderate Cost Lessons (significant impact):**
- LMMS-LESS-03: Hot Path Worth Cost (user experience)
- LMMS-LESS-04: Fast Path File Essential (prevention)

### By Category

**Measurement:**
- LMMS-LESS-01: Profile First Always
- LMMS-LESS-02: Measure Impact Always

**Optimization:**
- LMMS-LESS-03: Hot Path Worth Cost

**Code Organization:**
- LMMS-LESS-04: Fast Path File Essential

---

## ANTI-PATTERNS INDEX

### By Severity

**Critical (High Impact):**
- LMMS-AP-03: Ignoring Metrics (can't verify anything)
- LMMS-AP-04: Hot Path Heavy Imports (defeats purpose)

**Important (Moderate Impact):**
- LMMS-AP-01: Premature Optimization (wastes effort)
- LMMS-AP-02: Over-Lazy-Loading (hurts performance)

### By Category

**Measurement Issues:**
- LMMS-AP-01: Premature Optimization
- LMMS-AP-03: Ignoring Metrics

**Strategy Issues:**
- LMMS-AP-02: Over-Lazy-Loading
- LMMS-AP-04: Hot Path Heavy Imports

---

## TOPIC CROSS-REFERENCE

### Profiling

**Core:**
- LMMS-02: Cold Start Optimization (profiling section)
- LMMS-03: Import Strategy (profiling workflow)

**Decisions:**
- LMMS-DEC-03: Import Profiling Required

**Lessons:**
- LMMS-LESS-01: Profile First Always

**Anti-Patterns:**
- LMMS-AP-01: Premature Optimization (lack of profiling)
- LMMS-AP-03: Ignoring Metrics

### Hot Path

**Core:**
- LMMS-01: Core Concept (hot/cold path definition)
- LMMS-03: Import Strategy (classification)

**Decisions:**
- LMMS-DEC-02: Hot Path Exceptions
- LMMS-DEC-04: Fast Path File Required

**Lessons:**
- LMMS-LESS-03: Hot Path Worth Cost
- LMMS-LESS-04: Fast Path File Essential

**Anti-Patterns:**
- LMMS-AP-02: Over-Lazy-Loading (hot path issues)
- LMMS-AP-04: Hot Path Heavy Imports

### Measurement

**Core:**
- LMMS-02: Cold Start Optimization (measurement section)

**Decisions:**
- LMMS-DEC-03: Import Profiling Required

**Lessons:**
- LMMS-LESS-01: Profile First Always
- LMMS-LESS-02: Measure Impact Always

**Anti-Patterns:**
- LMMS-AP-03: Ignoring Metrics

### fast_path.py

**Decisions:**
- LMMS-DEC-04: Fast Path File Required

**Lessons:**
- LMMS-LESS-04: Fast Path File Essential

**Anti-Patterns:**
- LMMS-AP-04: Hot Path Heavy Imports

---

## WORKFLOW GUIDES

### Initial LMMS Implementation

**Step 1: Profile**
→ LMMS-03: Import Strategy (profiling workflow)
→ LMMS-DEC-03: Import Profiling Required

**Step 2: Classify**
→ LMMS-03: Import Strategy (tier system)
→ LMMS-DEC-01: Function-Level Imports

**Step 3: Create fast_path.py**
→ LMMS-DEC-04: Fast Path File Required
→ LMMS-LESS-04: Fast Path File Essential

**Step 4: Implement**
→ LMMS-01: Core Concept (patterns)
→ LMMS-DEC-02: Hot Path Exceptions (if needed)

**Step 5: Verify**
→ LMMS-LESS-02: Measure Impact Always

**Avoid:**
→ LMMS-AP-01: Premature Optimization
→ LMMS-AP-02: Over-Lazy-Loading
→ LMMS-AP-03: Ignoring Metrics

### Troubleshooting LMMS Issues

**Cold start still slow:**
→ LMMS-LESS-01: Profile First Always
→ Check for LMMS-AP-04: Hot Path Heavy Imports

**Request latency increased:**
→ LMMS-LESS-03: Hot Path Worth Cost
→ Check for LMMS-AP-02: Over-Lazy-Loading

**Unclear what's in hot path:**
→ LMMS-DEC-04: Fast Path File Required
→ LMMS-LESS-04: Fast Path File Essential

**Performance regressions:**
→ LMMS-LESS-02: Measure Impact Always
→ Check for LMMS-AP-03: Ignoring Metrics

---

## KEYWORD INDEX

**cold start:** LMMS-01, LMMS-02, LMMS-03, LMMS-DEC-01, LMMS-LESS-02  
**hot path:** LMMS-01, LMMS-03, LMMS-DEC-02, LMMS-DEC-04, LMMS-LESS-03, LMMS-LESS-04, LMMS-AP-02, LMMS-AP-04  
**profiling:** LMMS-02, LMMS-03, LMMS-DEC-03, LMMS-LESS-01, LMMS-AP-01  
**measurement:** LMMS-02, LMMS-DEC-03, LMMS-LESS-01, LMMS-LESS-02, LMMS-AP-03  
**fast_path.py:** LMMS-DEC-04, LMMS-LESS-04, LMMS-AP-04  
**lazy loading:** LMMS-01, LMMS-02, LMMS-03, LMMS-DEC-01, LMMS-AP-02  
**optimization:** LMMS-01, LMMS-02, LMMS-03, All Decisions, LMMS-LESS-03, All Anti-Patterns  
**performance:** All files  
**usage patterns:** LMMS-03, LMMS-DEC-02, LMMS-LESS-03, LMMS-AP-02  
**exceptions:** LMMS-DEC-02, LMMS-LESS-03, LMMS-AP-04  

---

**END OF FILE**

**Architecture:** LMMS (Lazy Module Management System)  
**Type:** Category Indexes  
**Status:** Complete
