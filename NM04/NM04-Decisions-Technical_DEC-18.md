# NM04-Decisions-Technical_DEC-18.md - DEC-18

# DEC-18: Interface-Level Mocking

**Category:** Decisions
**Topic:** Technical
**Priority:** Medium
**Status:** Active
**Date Decided:** 2024-07-15
**Created:** 2025-10-23
**Last Updated:** 2025-10-23

---

## Summary

Mock at interface router level for unit tests rather than individual functions, providing 3x faster test setup with more realistic behavior and less brittle tests.

---

## Context

Early unit tests mocked individual core functions (e.g., `cache_core.get_value`, `logging_core.log_message`). This required extensive setup for each test, with 10-15 mock declarations per test function. Tests were brittle - any refactoring of core modules broke many tests.

The SIMA architecture provides natural seams at interface routers. Mocking at this level could simplify tests while providing more realistic behavior.

---

## Content

### The Decision

**What We Chose:**
Mock entire interface routers for unit tests instead of individual core functions. Use a `mock_interface` context manager that patches the router's operation dispatch.

**Implementation:**
```python
# Test code
def test_cache_operations():
    with mock_interface('cache') as cache_mock:
        cache_mock.get.return_value = "test_value"
        result = gateway.cache_get("key")
        assert result == "test_value"
```

### Rationale

**Why We Chose This:**

1. **Faster Test Setup (3x improvement):**
   - Old way: Mock 10-15 individual functions per test
   - New way: Mock 1 interface
   - Setup time: 30 seconds → 10 seconds per test
   - Cumulative savings across test suite significant

2. **Less Brittle Tests:**
   - Tests don't care about internal core function structure
   - Refactoring core modules doesn't break tests
   - Only interface contract matters
   - Tests survive major implementation changes

3. **More Realistic Behavior:**
   - Tests exercise actual gateway → router pathway
   - Router logic (dispatch, validation) included in test
   - Error handling paths tested automatically
   - Closer to production execution

4. **Leverages SIMA Architecture:**
   - Interface routers are natural test seams
   - Architecture enables better testing
   - Tests validate architectural boundaries
   - Encourages thinking at interface level

### Alternatives Considered

**Alternative 1: Function-Level Mocking (Original)**
- **Description:** Mock individual core functions
- **Pros:**
  - Maximum control over behavior
  - Can test very specific scenarios
  - Most granular testing
- **Cons:**
  - Extremely brittle (breaks on refactoring)
  - Tedious setup (10-15 mocks per test)
  - Tests become maintenance burden
  - Doesn't validate router logic
- **Why Rejected:** Too brittle and time-consuming

**Alternative 2: Integration Tests Only**
- **Description:** Skip unit tests, test entire Lambda end-to-end
- **Pros:**
  - Most realistic
  - Tests complete system
  - No mocking needed
- **Cons:**
  - Slow (requires Lambda deployment)
  - Hard to isolate failures
  - Difficult to test error paths
  - Expensive to run frequently
- **Why Rejected:** Need fast feedback loop

**Alternative 3: Inject Dependencies**
- **Description:** Pass dependencies explicitly, inject mocks
- **Pros:**
  - Clean dependency injection
  - Very testable
  - Industry standard pattern
- **Cons:**
  - Requires refactoring entire codebase
  - Conflicts with singleton pattern (DEC-10)
  - More complex calling code
  - Architectural change for testing
- **Why Rejected:** Too invasive, conflicts with design decisions

### Trade-offs

**Accepted:**
- **Less granular than function mocking:** Can't control individual core functions
  - But interface level is right abstraction
  - If need function-level, can still do it selectively
  - Most tests don't need that granularity

- **Requires mock_interface helper:** Need testing infrastructure
  - But it's simple (~20 lines of code)
  - Reusable across all tests
  - Easy to enhance

**Benefits:**
- **3x faster test setup:** 10 seconds vs 30 seconds per test
- **More maintainable tests:** Survive refactoring
- **Better architecture validation:** Tests exercise router layer
- **Encourages good design:** Think at interface level

**Net Assessment:**
Strongly positive. Tests are faster to write, more maintainable, and more realistic. This decision makes testing enjoyable rather than tedious, which increases test coverage and quality.

### Impact

**On Architecture:**
- Validates interface layer as proper abstraction
- Router layer proves useful for testing
- Reinforces thinking at interface level

**On Development:**
- Tests easier to write (developers write more tests)
- Refactoring safer (tests don't break unnecessarily)
- Faster development cycle (faster test runs)

**On Performance:**
- No production impact (testing only)
- Test suite runs faster (less setup overhead)

**On Maintenance:**
- Tests require less maintenance
- New interfaces automatically testable
- Testing pattern consistent across interfaces
- Lower barrier to entry for test writing

### Future Considerations

**When to Revisit:**
- If need more granular control (add function-level option)
- If mock_interface becomes complex (simplify)
- If integration tests show gaps (adjust strategy)

**Potential Evolution:**
- **Smart mocks:** Record/replay actual interface calls
- **Mock validation:** Verify mocks match actual interface
- **Performance mocks:** Simulate timing behavior
- **Chaos testing:** Random failures at interface level

**Monitoring:**
- Track test coverage per interface
- Measure test execution time
- Monitor test brittleness (how often broken by refactoring)
- Gather developer satisfaction with testing

---

## Related Topics

- **DEC-01**: SIMA pattern - enables this testing approach
- **DEC-02**: Gateway centralization - tests exercise gateway
- **ARCH-01**: Gateway trinity - routers are test seams
- **LESS-06**: Testing strategies - interface mocking is key strategy
- **INT-01 to INT-12**: All interfaces - all benefit from this pattern
- **PATH-01**: Operation pathways - tests validate pathways

---

## Keywords

testing, mocking, unit-tests, interface-routers, test-strategy, maintainable-tests, SIMA-benefits, developer-experience

---

## Version History

- **2025-10-23**: Migrated to SIMA v3 individual file format with complete template
- **2024-07-15**: Decision documented in NM04-TECHNICAL-Decisions.md

---

**File:** `NM04-Decisions-Technical_DEC-18.md`
**End of Document**
