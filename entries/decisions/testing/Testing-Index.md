# Testing-Index.md

**Category:** Decision Logic  
**Subcategory:** Testing  
**Files:** 2  
**Created:** 2024-10-30  
**Updated:** 2024-10-30

---

## Overview

Testing decisions guide test coverage priorities and mocking strategies - what to test (coverage), and how to test (mocking level) based on test type and goals.

---

## Files in This Category

### DT-08: What Should I Test
**REF-ID:** DT-08  
**Priority:** High  
**File:** `DT-08.md`

**Summary:**
Decision tree for test coverage priorities - MUST test (success/failure/edge), SHOULD test (integration), OPTIONAL (performance/load).

**Key Questions:**
- Success path tested?
- Failure path tested?
- Edge cases covered?
- Integration tested?
- Performance critical?

**Use When:**
- Writing test suite
- Prioritizing test effort
- Assessing coverage

---

### DT-09: How Much to Mock
**REF-ID:** DT-09  
**Priority:** Medium  
**File:** `DT-09.md`

**Summary:**
Decision tree for mocking strategies - heavy mocking for unit tests, selective for integration, minimal for E2E.

**Key Questions:**
- Unit test? → Mock heavily
- Integration test? → Mock external only
- E2E test? → Mock remote APIs only
- Performance test? → No mocking

**Use When:**
- Writing tests
- Deciding mock strategy
- Debugging test failures

---

## Quick Decision Guide

### Test Coverage Priority
```
MUST TEST:
- Success path (happy path)
- Failure path (error handling)
- Edge cases (boundary conditions)

SHOULD TEST:
- Integration (cross-interface)

OPTIONAL:
- Performance (if critical)
- Load testing (if high-traffic)
```

### Mocking Strategy
```
Unit Test       → Mock all dependencies
Integration     → Mock external services only
E2E Test        → Mock remote APIs only  
Performance     → No mocking (real components)
```

---

## Common Scenarios

### Scenario 1: Testing New Feature

**Workflow:**
1. **DT-08:** Determine what to test
   - Write success path tests
   - Write failure path tests
   - Write edge case tests
2. **DT-09:** Determine mocking strategy
   - Unit tests: Mock all gateway calls
   - Integration tests: Mock only external APIs

### Scenario 2: Cache Operation Testing

**Unit Test (DT-09: Heavy Mocking):**
```python
with patch('gateway.log_info'):
    with patch('gateway.record_metric'):
        result = cache_set("key", "value")
```

**Integration Test (DT-09: Selective Mocking):**
```python
with patch('external_api.fetch'):  # Only external
    # Real cache used
    result = get_with_cache("key")
```

### Scenario 3: Coverage Assessment

**DT-08 Checklist:**
- [✅] Success path tested
- [✅] Failure path tested  
- [✅] Edge cases tested
- [✅] Integration tested
- [❌] Performance tested (not critical)
- [❌] Load tested (low traffic)

**Result:** Adequate coverage for non-critical feature

---

## Related Content

**Design Decisions:**
- **DEC-18:** Test strategy decisions

**Anti-Patterns:**
- **AP-23:** No unit tests
- **AP-24:** Testing only success paths

**Lessons:**
- **LESS-08:** Test failure paths
- **LESS-15:** Test-driven development

---

## Verification Checklist

**Test Coverage (DT-08):**
- [ ] Success path tested
- [ ] Failure path tested
- [ ] Edge cases tested
- [ ] Invalid input tested
- [ ] Integration tested (if applicable)

**Mocking Strategy (DT-09):**
- [ ] Unit tests mock all dependencies
- [ ] Integration tests mock only external
- [ ] E2E tests mock only remote APIs
- [ ] Performance tests use real components
- [ ] No over-mocking in integration tests

---

## Keywords

testing, test coverage, mocking, test strategy, unit tests, integration tests, E2E tests, test priorities

---

**File:** `Testing-Index.md`  
**Location:** `/sima/entries/decisions/testing/`  
**End of Category Index**
