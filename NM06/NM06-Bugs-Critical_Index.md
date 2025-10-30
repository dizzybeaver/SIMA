# NM06-Bugs-Critical_Index.md

# Lessons - Critical Bugs Index

**Category:** NM06 - Lessons
**Topic:** Critical Bugs
**Items:** 4
**Last Updated:** 2025-10-23

---

## Topic Overview

**Description:** Critical bugs discovered during Lambda Execution Engine development. Each bug represents a significant issue that was identified, analyzed, and resolved. These serve as cautionary tales and validation checkpoints for preventing similar issues.

**Keywords:** bugs, issues, problems, fixes, validation, testing, debugging

---

## Individual Files

### BUG-01: Sentinel Leak (535ms Performance Cost)
- **File:** `NM06-Bugs-Critical_BUG-01.md`
- **Summary:** Sentinel object leaked to router causing 535ms cold start performance degradation
- **Priority:** Critical
- **Status:** Resolved
- **Impact:** 62% performance improvement after fix

### BUG-02: Circular Import in Early Architecture
- **File:** `NM06-Bugs-Critical_BUG-02.md`
- **Summary:** _CacheMiss sentinel validation and circular import issues in early architecture
- **Priority:** Critical
- **Status:** Resolved
- **Impact:** Fixed by SIMA gateway pattern

### BUG-03: Cascading Interface Failures
- **File:** `NM06-Bugs-Critical_BUG-03.md`
- **Summary:** One interface failure causing system-wide cascading failures
- **Priority:** Critical
- **Status:** Resolved
- **Impact:** Fixed by error boundaries and graceful degradation

### BUG-04: Configuration Parameter Mismatch
- **File:** `NM06-Bugs-Critical_BUG-04.md`
- **Summary:** Deployment failures due to complex configuration parameter management
- **Priority:** Critical
- **Status:** Resolved
- **Impact:** Fixed by SSM token-only simplification

---

## Common Themes

### Theme 1: Architecture Prevents Problems
- BUG-01: Router sanitization prevents sentinel leaks
- BUG-02: Gateway pattern prevents circular imports
- Pattern: Good architecture makes bugs impossible

### Theme 2: Isolation is Critical
- BUG-03: Error boundaries prevent cascading failures
- Pattern: Defense in depth protects system

### Theme 3: Simplicity Wins
- BUG-04: Simplified configuration prevents deployment errors
- Pattern: Complexity is the enemy of reliability

### Theme 4: Measure and Monitor
- All bugs discovered through: timing logs, import tracing, error logs, deployment verification
- Pattern: You can't fix what you don't measure

---

## Related Topics

- **NM06-Lessons-Performance:** Lessons about performance optimization
- **NM06-Lessons-CoreArchitecture:** Lessons about architectural patterns
- **NM04-Decisions-Technical:** Technical decisions that prevent bugs
- **NM05-AntiPatterns:** Patterns that cause bugs

---

## Prevention Strategies

**From Bug Experiences:**
1. Always sanitize at router layer (BUG-01)
2. Use gateway pattern for all cross-interface operations (BUG-02)
3. Implement error boundaries at multiple layers (BUG-03)
4. Simplify configuration to single source of truth (BUG-04)
5. Measure performance to detect anomalies early
6. Test failure paths, not just happy paths

---

**Navigation:**
- **Up:** NM06-Lessons_Index.md
- **Sibling Topics:** Lessons, Wisdom

---

**End of Index**
