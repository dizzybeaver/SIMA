# Phase 7 - Integration Tests

**Version:** 1.0.0  
**Date:** 2025-10-24  
**Purpose:** Validate SIMA v3 + Support Tools integration  
**Estimated Time:** 30 minutes

---

## 🎯 TEST OBJECTIVES

Verify that:
1. ✅ All tool files are accessible
2. ✅ Cross-references work correctly
3. ✅ Routing is fast and accurate
4. ✅ SESSION-START loads properly
5. ✅ Workflows execute correctly
6. ✅ Anti-pattern checks function
7. ✅ REF-ID lookups work

---

## 📋 TEST SUITE

### Test 1: SESSION-START Loading (CRITICAL)

**Objective:** Verify SESSION-START loads in < 45 seconds

**Procedure:**
```
1. Start fresh Claude session
2. Upload File-Server-URLs.md
3. Search project knowledge: "SESSION-START-Quick-Context"
4. Time the load
5. Verify critical sections present
```

**Expected Results:**
- ✅ Load time: < 45 seconds
- ✅ SIMA pattern section present
- ✅ 12 interfaces table present
- ✅ Top 10 instant answers present
- ✅ RED FLAGS table present
- ✅ Top 20 REF-IDs present
- ✅ Query routing map present

**Success Criteria:**
- Load completes without truncation
- All sections readable
- Time under 45 seconds

**Status:** ⬜ Not tested

---

### Test 2: Anti-Pattern Quick Check

**Objective:** Verify fast anti-pattern verification

**Test Cases:**

**Test 2a: "Can I use threading locks?"**
```
User query: "Can I use threading locks?"
Expected route: Workflow-05-CanIQuestions → AP-Checklist-Critical
Expected result: NO with DEC-04, AP-08 citations
Expected time: < 15 seconds
```

**Expected Response:**
```
NO - Lambda is single-threaded (DEC-04, AP-08)

According to DEC-04, Lambda runs in a single-threaded environment,
making threading locks unnecessary and harmful. This is documented
as AP-08 (Threading primitives anti-pattern).

Use atomic operations or external coordination instead.
```

**Success Criteria:**
- ✅ Instant NO answer
- ✅ Cites DEC-04 and AP-08
- ✅ Explains rationale
- ✅ Provides alternative
- ✅ Time < 15 seconds

**Status:** ⬜ Not tested

---

**Test 2b: "Can I import cache_core directly?"**
```
User query: "Can I import cache_core directly?"
Expected route: Workflow-05 → AP-Checklist-Critical
Expected result: NO with RULE-01, AP-01 citations
Expected time: < 15 seconds
```

**Expected Response:**
```
NO - Always import via gateway (RULE-01, AP-01)

Direct cross-interface imports violate SIMA pattern and cause
circular imports (AP-01). According to RULE-01, all operations
must go through the gateway layer.

Correct pattern:
import gateway
value = gateway.cache_get(key)
```

**Success Criteria:**
- ✅ Instant NO answer
- ✅ Cites RULE-01 and AP-01
- ✅ Shows correct pattern
- ✅ Time < 15 seconds

**Status:** ⬜ Not tested

---

### Test 3: REF-ID Directory Lookup

**Objective:** Verify fast REF-ID cross-reference

**Test Cases:**

**Test 3a: Look up DEC-04**
```
User query: "Tell me about DEC-04"
Expected route: REF-ID-Directory-DEC.md
Expected result: "No threading locks" with file location
Expected time: < 10 seconds
```

**Expected Response:**
```
DEC-04: No Threading Locks

File: NM04/NM04-Decisions-Technical_DEC-04.md

Lambda runs in a single-threaded environment, making threading
locks unnecessary. This decision prevents unnecessary overhead
and architectural complexity.

Related: AP-08 (Threading anti-pattern), LESS-06
```

**Success Criteria:**
- ✅ Correct REF-ID identified
- ✅ File location provided
- ✅ Summary accurate
- ✅ Related REF-IDs cited
- ✅ Time < 10 seconds

**Status:** ⬜ Not tested

---

**Test 3b: Look up BUG-01**
```
User query: "What was BUG-01?"
Expected route: REF-ID-Directory-AP-BUG.md
Expected result: "Sentinel leak" with impact
Expected time: < 10 seconds
```

**Expected Response:**
```
BUG-01: Sentinel Leak (535ms penalty)

File: NM06/NM06-Bugs-Critical_BUG-01.md

Sentinel objects (_CacheMiss, _NotFound) leaked past router layer,
causing JSON serialization failures. Each occurrence added 535ms
penalty per request. Fixed via DEC-05 (Sanitize at router).

Related: DEC-05, AP-19
```

**Success Criteria:**
- ✅ Correct BUG identified
- ✅ Impact quantified (535ms)
- ✅ Solution referenced (DEC-05)
- ✅ Time < 10 seconds

**Status:** ⬜ Not tested

---

### Test 4: Workflow Execution

**Objective:** Verify workflows execute correctly

**Test Cases:**

**Test 4a: Add Feature Workflow**
```
User query: "I want to add a new caching function"
Expected route: Workflow-01-AddFeature.md
Expected result: Complete 3-layer implementation guide
Expected time: < 60 seconds
```

**Expected Workflow Steps:**
1. ✅ Understand requirements
2. ✅ Check if already exists (gateway search)
3. ✅ Choose interface (INT-01 CACHE)
4. ✅ Design SIMA implementation
5. ✅ Verify no anti-patterns
6. ✅ Implement all 3 layers
7. ✅ LESS-15 verification

**Success Criteria:**
- ✅ All steps followed
- ✅ Complete code provided
- ✅ Gateway + Interface + Core layers
- ✅ Verification checklist included
- ✅ Time < 60 seconds

**Status:** ⬜ Not tested

---

**Test 4b: Error Report Workflow**
```
User query: "Lambda is returning 500 error"
Expected route: Workflow-02-ReportError.md
Expected result: Systematic troubleshooting steps
Expected time: < 60 seconds
```

**Expected Workflow Steps:**
1. ✅ Gather context (error message, logs)
2. ✅ Check known bugs (NM06)
3. ✅ Analyze error type
4. ✅ Trace through layers
5. ✅ Diagnose root cause
6. ✅ Provide solution

**Success Criteria:**
- ✅ Asks for context
- ✅ Checks known bugs first
- ✅ Systematic diagnosis
- ✅ Solution with citations
- ✅ Time < 60 seconds

**Status:** ⬜ Not tested

---

**Test 4c: Cold Start Workflow**
```
User query: "Cold start is taking 4 seconds"
Expected route: Workflow-08-ColdStart.md
Expected result: Profiling and optimization steps
Expected time: < 60 seconds
```

**Expected Workflow Steps:**
1. ✅ Measure current state
2. ✅ Categorize imports (hot/cold)
3. ✅ Check LMMS system
4. ✅ Design optimization
5. ✅ Implement changes
6. ✅ Measure again

**Success Criteria:**
- ✅ Asks to profile first (LESS-02)
- ✅ Categorizes imports
- ✅ Suggests lazy loading
- ✅ Shows before/after
- ✅ Time < 60 seconds

**Status:** ⬜ Not tested

---

### Test 5: Cross-Reference Navigation

**Objective:** Verify cross-references work correctly

**Test Cases:**

**Test 5a: Follow DEC-04 to AP-08**
```
Start: Read DEC-04 (No threading locks)
Cross-reference: "Related: AP-08"
Expected: Can navigate to AP-08
Expected result: AP-08 details with examples
Expected time: < 15 seconds total
```

**Success Criteria:**
- ✅ DEC-04 mentions AP-08
- ✅ AP-08 file accessible
- ✅ Consistent information
- ✅ Navigation works

**Status:** ⬜ Not tested

---

**Test 5b: Follow BUG-01 to DEC-05**
```
Start: Read BUG-01 (Sentinel leak)
Cross-reference: "Fixed via DEC-05"
Expected: Can navigate to DEC-05
Expected result: DEC-05 sanitization decision
Expected time: < 15 seconds total
```

**Success Criteria:**
- ✅ BUG-01 references DEC-05
- ✅ DEC-05 file accessible
- ✅ Solution matches problem
- ✅ Navigation works

**Status:** ⬜ Not tested

---

### Test 6: Query Routing Performance

**Objective:** Verify fast routing to correct files

**Test Matrix:**

| Query | Expected File | Expected Time |
|-------|---------------|---------------|
| "Why no threading?" | NM04/DEC-04 | < 20s |
| "How to add feature?" | Workflow-01 | < 15s |
| "Can I use bare except?" | AP-Checklist-Critical | < 10s |
| "What is SIMA?" | Workflow-10 (Architecture) | < 20s |
| "Optimize cold start" | Workflow-08 | < 15s |
| "Import error help" | Workflow-07 | < 15s |
| "What interfaces exist?" | NM01/Interfaces | < 20s |
| "Verify my changes" | LESS-15 | < 15s |

**Success Criteria:**
- ✅ All queries route correctly
- ✅ All times under target
- ✅ Responses complete
- ✅ Citations accurate

**Status:** ⬜ Not tested

---

### Test 7: RED FLAGS Detection

**Objective:** Verify RED FLAGS prevent bad suggestions

**Test Cases:**

**Test 7a: Threading Suggestion**
```
Scenario: User asks about concurrent access
Expected: Claude should NOT suggest threading locks
Expected: Should cite DEC-04, AP-08
Expected: Should suggest alternatives
```

**Success Criteria:**
- ✅ NO threading locks suggested
- ✅ Alternative provided
- ✅ Citations included
- ✅ Explanation clear

**Status:** ⬜ Not tested

---

**Test 7b: Direct Import Suggestion**
```
Scenario: User wants to import from cache_core
Expected: Claude should NOT approve direct import
Expected: Should show gateway pattern
Expected: Should cite RULE-01, AP-01
```

**Success Criteria:**
- ✅ NO direct import approved
- ✅ Gateway pattern shown
- ✅ Citations included
- ✅ Example code provided

**Status:** ⬜ Not tested

---

**Test 7c: Bare Except Suggestion**
```
Scenario: User wants to catch all exceptions
Expected: Claude should NOT suggest bare except
Expected: Should show specific exceptions
Expected: Should cite AP-14
```

**Success Criteria:**
- ✅ NO bare except suggested
- ✅ Specific exceptions shown
- ✅ Citation included
- ✅ Error handling pattern provided

**Status:** ⬜ Not tested

---

## 📊 TEST SUMMARY TEMPLATE

**Test Date:** [YYYY-MM-DD]  
**Tester:** [Name]  
**Session ID:** [Session identifier]

### Results Overview

| Test | Status | Time | Notes |
|------|--------|------|-------|
| 1. SESSION-START Load | ⬜ | ___ s | |
| 2a. Threading locks? | ⬜ | ___ s | |
| 2b. Direct import? | ⬜ | ___ s | |
| 3a. DEC-04 lookup | ⬜ | ___ s | |
| 3b. BUG-01 lookup | ⬜ | ___ s | |
| 4a. Add feature | ⬜ | ___ s | |
| 4b. Report error | ⬜ | ___ s | |
| 4c. Cold start | ⬜ | ___ s | |
| 5a. DEC-04 → AP-08 | ⬜ | ___ s | |
| 5b. BUG-01 → DEC-05 | ⬜ | ___ s | |
| 6. Routing matrix | ⬜ | ___ s | |
| 7a. RED FLAG thread | ⬜ | ___ s | |
| 7b. RED FLAG import | ⬜ | ___ s | |
| 7c. RED FLAG except | ⬜ | ___ s | |

**Legend:**
- ✅ Pass
- ❌ Fail
- ⚠️ Partial
- ⬜ Not tested

### Overall Status

**Pass Rate:** ___% (___/14 tests passed)  
**Average Time:** ___ seconds  
**Critical Issues:** [None / List issues]

### Issues Found

| # | Test | Issue | Severity | Resolution |
|---|------|-------|----------|------------|
| 1 | | | | |
| 2 | | | | |

### Recommendations

1. [Recommendation 1]
2. [Recommendation 2]
3. [Recommendation 3]

---

## 🔧 TROUBLESHOOTING GUIDE

### Common Test Failures

**Issue: SESSION-START doesn't load**
- **Cause:** File not in project knowledge or wrong search term
- **Fix:** Verify file uploaded, search for exact filename
- **Expected:** File appears in search results

**Issue: Routing takes too long**
- **Cause:** Multiple file searches instead of using routing map
- **Fix:** Check SESSION-START routing map is loaded
- **Expected:** Single targeted search < 15 seconds

**Issue: Cross-references broken**
- **Cause:** File paths outdated or incorrect
- **Fix:** Verify all paths use v3 format (NM##/)
- **Expected:** All paths valid and accessible

**Issue: Workflows incomplete**
- **Cause:** Workflow file truncated or steps missing
- **Fix:** Verify workflow files < 300 lines
- **Expected:** All workflow steps present

**Issue: RED FLAGS not preventing**
- **Cause:** SESSION-START not loaded or RED FLAGS not in memory
- **Fix:** Re-load SESSION-START at session start
- **Expected:** RED FLAGS block bad suggestions

---

## ✅ ACCEPTANCE CRITERIA

**Phase 7 Integration is successful when:**

**Critical Tests (Must Pass):**
1. ✅ SESSION-START loads in < 45 seconds
2. ✅ Anti-pattern checks < 15 seconds
3. ✅ REF-ID lookups < 10 seconds
4. ✅ RED FLAGS prevent bad suggestions

**Important Tests (Should Pass):**
5. ✅ Workflows execute correctly
6. ✅ Cross-references work
7. ✅ Query routing < 20 seconds

**Overall:**
- ✅ Pass rate ≥ 90% (13/14 tests)
- ✅ No critical issues
- ✅ Average response time < 30 seconds

---

## 📝 NEXT STEPS AFTER TESTING

**If tests pass (≥ 90%):**
1. ✅ Mark Phase 7 Integration complete
2. ✅ Proceed to User Documentation
3. ✅ Begin performance monitoring

**If tests fail (< 90%):**
1. ❌ Document all failures
2. ❌ Identify root causes
3. ❌ Fix issues
4. ❌ Re-test
5. ❌ Update relevant files

---

**END OF INTEGRATION TESTS**

**Version:** 1.0.0  
**Status:** Ready for execution  
**Estimated Duration:** 30 minutes  
**Required:** Fresh Claude session + File-Server-URLs.md
