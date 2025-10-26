# NM06-INDEX-Learned Experiences.md

**Topic:** Learned Experiences - Bugs, Lessons, Wisdom  
**Type:** Interface Index (Router)  
**Version:** 2.1.0 (METRICS Phase 1 Added)  
**Updated:** 2025-10-21  
**Lines:** ~320  
**Pattern:** SIMA Dispatch Router (like interface_cache.py)

---

## Purpose

This is the **Interface Index** for NM06 (Learned Experiences). It routes queries about bugs, lessons learned, and wisdom to the correct Implementation file.

**Think of this as `interface_learned.py` for knowledge.**

Routes:
- Bug queries → NM06-BUGS-Critical.md
- Core lessons → NM06-LESSONS-Core.md
- Deployment lessons → NM06-LESSONS-Deployment.md
- Documentation lessons → NM06-LESSONS-Documentation.md
- Recent lessons (2025.10.20) → NM06-LESSONS-Recent Updates 2025.10.20.md
- **METRICS Phase 1 (2025.10.21) → NM06-LESSONS-2025.10.21-METRICS-Phase1.md** ✅ NEW
- Wisdom synthesis → NM06-WISDOM-Synthesized Insights.md

---

## Dispatch Table

| Query Type | Route To | Lines | Priority |
|------------|----------|-------|----------|
| Critical bugs | NM06-BUGS-Critical Bugs Fixed.md | 450 | CRITICAL |
| Core lessons | NM06-LESSONS-Core Architecture Lessons.md | 580 | HIGH |
| Deployment lessons | NM06-LESSONS-Deployment_and_Operations.md | 420 | MEDIUM |
| Documentation lessons | NM06-LESSONS-Documentation and Knowledge.md | 380 | MEDIUM |
| Recent lessons (2025.10.20) | NM06-LESSONS-Recent Updates 2025.10.20.md | 600 | CRITICAL |
| **METRICS Phase 1 (2025.10.21)** | **NM06-LESSONS-2025.10.21-METRICS-Phase1.md** | **~750** | **CRITICAL** | ✅ NEW
| Wisdom synthesis | NM06-WISDOM-Synthesized Insights.md | 320 | MEDIUM |

**Total Files:** 7 Implementation files

---

## Quick Reference

### By REF ID

#### Bugs (NM06-BUG-##)
```
NM06-BUG-01 → NM06-BUGS-Critical Bugs Fixed.md (Sentinel leak - 535ms)
NM06-BUG-02 → NM06-BUGS-Critical Bugs Fixed.md (_CacheMiss validation)
NM06-BUG-03 → NM06-BUGS-Critical Bugs Fixed.md (Cascading failures)
NM06-BUG-04 → NM06-BUGS-Critical Bugs Fixed.md (Config param mismatch)
```

#### Core Lessons (NM06-LESS-01 to LESS-08)
```
NM06-LESS-01 → NM06-LESSONS-Core Architecture Lessons.md (Gateway prevents problems)
NM06-LESS-02 → NM06-LESSONS-Core Architecture Lessons.md (Measure don't guess)
NM06-LESS-03 → NM06-LESSONS-Core Architecture Lessons.md (Infrastructure vs business)
NM06-LESS-04 → NM06-LESSONS-Core Architecture Lessons.md (Consistency over cleverness)
NM06-LESS-05 → NM06-LESSONS-Core Architecture Lessons.md (Graceful degradation)
NM06-LESS-06 → NM06-LESSONS-Core Architecture Lessons.md (Pay small costs early)
NM06-LESS-07 → NM06-LESSONS-Core Architecture Lessons.md (Base layers no dependencies)
NM06-LESS-08 → NM06-LESSONS-Core Architecture Lessons.md (Test failure paths)
```

#### Deployment Lessons (NM06-LESS-09 to LESS-10)
```
NM06-LESS-09 → NM06-LESSONS-Deployment_and_Operations.md (Partial deployment danger)
NM06-LESS-10 → NM06-LESSONS-Deployment_and_Operations.md (Cold start monitoring)
```

#### Documentation Lessons (NM06-LESS-11 to LESS-13)
```
NM06-LESS-11 → NM06-LESSONS-Documentation and Knowledge.md (Document decisions)
NM06-LESS-12 → NM06-LESSONS-Documentation and Knowledge.md (Comments vs docs)
NM06-LESS-13 → NM06-LESSONS-Documentation and Knowledge.md (Architecture teachable)
```

#### Recent Lessons (NM06-LESS-14 to LESS-16)
```
NM06-LESS-14 → NM06-LESSONS-Recent Updates 2025.10.20.md (Evolution is normal)
NM06-LESS-15 → NM06-LESSONS-Recent Updates 2025.10.20.md (File verification mandatory)
NM06-LESS-16 → NM06-LESSONS-Recent Updates 2025.10.20.md (Adaptation over rewriting)
```

#### METRICS Phase 1 Lessons (NM06-LESS-17 to LESS-21) ✅ NEW
```
NM06-LESS-17 → NM06-LESSONS-2025.10.21-METRICS-Phase1.md (Threading locks unnecessary)
NM06-LESS-18 → NM06-LESSONS-2025.10.21-METRICS-Phase1.md (SINGLETON pattern lifecycle)
NM06-LESS-19 → NM06-LESSONS-2025.10.21-METRICS-Phase1.md (Security validations prevent injection)
NM06-LESS-20 → NM06-LESSONS-2025.10.21-METRICS-Phase1.md (Memory limits prevent DoS)
NM06-LESS-21 → NM06-LESSONS-2025.10.21-METRICS-Phase1.md (Rate limiting essential)
```

#### Wisdom Synthesis (NM06-WISD-##)
```
NM06-WISD-01 → NM06-WISDOM-Synthesized Insights.md (Architecture prevents problems)
NM06-WISD-02 → NM06-WISDOM-Synthesized Insights.md (Measure don't guess)
NM06-WISD-03 → NM06-WISDOM-Synthesized Insights.md (Small costs early)
NM06-WISD-04 → NM06-WISDOM-Synthesized Insights.md (Consistency > Cleverness)
NM06-WISD-05 → NM06-WISDOM-Synthesized Insights.md (Document everything)
```

---

## Keyword Reference

### Performance Keywords
```
"sentinel" → NM06-BUGS-Critical Bugs Fixed.md (BUG-01)
"535ms" → NM06-BUGS-Critical Bugs Fixed.md (BUG-01)
"cold start" → NM06-BUGS-Critical Bugs Fixed.md (BUG-01) + NM06-LESSONS-Deployment_and_Operations.md (LESS-10)
"memory leak" → NM06-BUGS-Critical Bugs Fixed.md (BUG-01)
"measure performance" → NM06-LESSONS-Core Architecture Lessons.md (LESS-02)
```

### Architecture Keywords
```
"circular import" → NM06-BUGS-Critical Bugs Fixed.md (BUG-02)
"gateway pattern" → NM06-LESSONS-Core Architecture Lessons.md (LESS-01)
"infrastructure" → NM06-LESSONS-Core Architecture Lessons.md (LESS-03)
"consistency" → NM06-LESSONS-Core Architecture Lessons.md (LESS-04)
"base layer" → NM06-LESSONS-Core Architecture Lessons.md (LESS-07)
```

### Threading Keywords ✅ UPDATED
```
"threading" → NM04-DEC-04 + NM06-LESSONS-2025.10.21-METRICS-Phase1.md (LESS-17)
"locks" → NM04-DEC-04 + NM06-LESSONS-2025.10.21-METRICS-Phase1.md (LESS-17)
"Lambda execution model" → NM06-LESSONS-2025.10.21-METRICS-Phase1.md (LESS-17)
"single-threaded" → NM04-DEC-04 + NM06-LESSONS-2025.10.21-METRICS-Phase1.md (LESS-17)
```

### SINGLETON Keywords ✅ UPDATED
```
"SINGLETON" → NM01-INT-06 + NM06-LESSONS-2025.10.21-METRICS-Phase1.md (LESS-18)
"lifecycle management" → NM06-LESSONS-2025.10.21-METRICS-Phase1.md (LESS-18)
"get_X_manager" → NM06-LESSONS-2025.10.21-METRICS-Phase1.md (LESS-18)
"LUGS" → NM01-ARCH-07 + NM06-LESSONS-2025.10.21-METRICS-Phase1.md (LESS-18)
```

### METRICS Keywords ✅ NEW
```
"metrics validation" → NM06-LESSONS-2025.10.21-METRICS-Phase1.md (LESS-19)
"metric injection" → NM06-LESSONS-2025.10.21-METRICS-Phase1.md (LESS-19)
"rate limiting" → NM06-LESSONS-2025.10.21-METRICS-Phase1.md (LESS-21)
"memory limits" → NM06-LESSONS-2025.10.21-METRICS-Phase1.md (LESS-20)
"FIFO eviction" → NM06-LESSONS-2025.10.21-METRICS-Phase1.md (LESS-20)
"sliding window" → NM06-LESSONS-2025.10.21-METRICS-Phase1.md (LESS-21)
"DoS protection" → NM06-LESSONS-2025.10.21-METRICS-Phase1.md (LESS-20, LESS-21)
"unbounded growth" → NM06-LESSONS-2025.10.21-METRICS-Phase1.md (LESS-20)
```

### Security Keywords ✅ UPDATED
```
"injection attack" → NM06-LESSONS-2025.10.21-METRICS-Phase1.md (LESS-19)
"path traversal" → NM06-LESSONS-2025.10.21-METRICS-Phase1.md (LESS-19)
"NaN validation" → NM06-LESSONS-2025.10.21-METRICS-Phase1.md (LESS-19)
"validate_metric_name" → NM06-LESSONS-2025.10.21-METRICS-Phase1.md (LESS-19)
"validate_dimension_value" → NM06-LESSONS-2025.10.21-METRICS-Phase1.md (LESS-19)
"validate_metric_value" → NM06-LESSONS-2025.10.21-METRICS-Phase1.md (LESS-19)
```

### Failure & Resilience Keywords
```
"cascading failure" → NM06-BUGS-Critical Bugs Fixed.md (BUG-03)
"graceful degradation" → NM06-LESSONS-Core Architecture Lessons.md (LESS-05)
"error handling" → NM06-LESSONS-Core Architecture Lessons.md (LESS-08)
"test failures" → NM06-LESSONS-Core Architecture Lessons.md (LESS-08)
```

### Deployment Keywords
```
"partial deployment" → NM06-LESSONS-Deployment_and_Operations.md (LESS-09)
"atomic deployment" → NM06-LESSONS-Deployment_and_Operations.md (LESS-09)
"deployment danger" → NM06-LESSONS-Deployment_and_Operations.md (LESS-09)
"monitoring" → NM06-LESSONS-Deployment_and_Operations.md (LESS-10)
```

### Documentation Keywords
```
"document decisions" → NM06-LESSONS-Documentation and Knowledge.md (LESS-11)
"comments" → NM06-LESSONS-Documentation and Knowledge.md (LESS-12)
"teachable" → NM06-LESSONS-Documentation and Knowledge.md (LESS-13)
"neural maps" → NM06-LESSONS-Documentation and Knowledge.md (LESS-11)
```

### Recent Keywords (2025.10.20)
```
"file verification" → NM06-LESSONS-Recent Updates 2025.10.20.md (LESS-15)
"truncation" → NM06-LESSONS-Recent Updates 2025.10.20.md (LESS-15)
"adaptation" → NM06-LESSONS-Recent Updates 2025.10.20.md (LESS-16)
"evolution" → NM06-LESSONS-Recent Updates 2025.10.20.md (LESS-14)
```

---

## Usage Patterns

### Example 1: Bug Query
```
User: "What was the sentinel bug?"

Routing:
1. NM00A triggers "sentinel" → NM06-INDEX-Learned
2. NM06-INDEX routes "sentinel" → NM06-BUGS-Critical Bugs Fixed.md
3. Find NM06-BUG-01 section
4. Read complete section
5. Respond with full context (root cause, solution, impact)
```

### Example 2: Architectural Lesson
```
User: "Why does SIMA use gateway pattern?"

Routing:
1. NM00A triggers "gateway pattern" → NM06-INDEX-Learned
2. NM06-INDEX routes "gateway pattern" → NM06-LESSONS-Core Architecture Lessons.md
3. Find NM06-LESS-01 section
4. Read complete section
5. Respond with rationale, examples, impact
```

### Example 3: Recent Update
```
User: "How do I verify files before deploying?"

Routing:
1. NM00A triggers "file verification" → NM06-INDEX-Learned
2. NM06-INDEX routes "file verification" → NM06-LESSONS-Recent Updates 2025.10.20.md
3. Find NM06-LESS-15 section
4. Read verification protocol (5 steps)
5. Respond with complete checklist
```

### Example 4: METRICS Security ✅ NEW
```
User: "Do I need to validate metric names?"

Routing:
1. NM00A triggers "metric validation" → NM06-INDEX-Learned
2. NM06-INDEX routes to NM06-LESSONS-2025.10.21-METRICS-Phase1.md
3. Find NM06-LESS-19 section
4. Read three-layer validation
5. Respond with attack vectors prevented + code examples
```

### Example 5: Threading Question ✅ NEW
```
User: "Can I use threading locks in Lambda?"

Routing:
1. NM00A triggers "threading locks" → NM06-INDEX-Learned
2. NM06-INDEX routes to NM06-LESSONS-2025.10.21-METRICS-Phase1.md
3. Find NM06-LESS-17 section
4. Read Lambda execution model
5. Respond with NO + rationale + performance impact
```

### Example 6: SINGLETON Pattern ✅ NEW
```
User: "How do I access the metrics manager?"

Routing:
1. NM00A triggers "get_X_manager" → NM06-INDEX-Learned
2. NM06-INDEX routes to NM06-LESSONS-2025.10.21-METRICS-Phase1.md
3. Find NM06-LESS-18 section
4. Read SINGLETON pattern
5. Respond with get_metrics_manager() pattern + benefits
```

---

## File Status

### Current Status (Updated 2025-10-21) ✅
```
✅ NM06-INDEX-Learned Experiences.md (THIS FILE - Updated)
✅ NM06-BUGS-Critical Bugs Fixed.md (Exists)
✅ NM06-LESSONS-Core Architecture Lessons.md (Exists)
✅ NM06-LESSONS-Deployment_and_Operations.md (Exists)
✅ NM06-LESSONS-Documentation and Knowledge.md (Exists)
✅ NM06-LESSONS-Recent Updates 2025.10.20.md (Exists)
✅ NM06-LESSONS-2025.10.21-METRICS-Phase1.md (NEW - Ready to upload) ✅
✅ NM06-WISDOM-Synthesized Insights.md (Exists)
```

**Total:** 8 files (7 Implementation + 1 Index)

---

## Cross-References

### Related Indexes
```
NM01-INDEX-Architecture.md → INT-04 (METRICS), INT-06 (SINGLETON)
NM04-INDEX-Decisions.md → DEC-04 (No threading)
NM05-INDEX-Anti-Patterns.md → AP-08 (Threading primitives)
NM06-INDEX-Learned.md → LESS-17 to LESS-21 (METRICS Phase 1)
```

### Common Query Paths
```
"Why X design decision?"
└─ NM04-INDEX-Decisions → Then reference NM06 for context

"How to avoid Y mistake?"
└─ NM05-INDEX-Anti-Patterns → Then reference NM06 for examples

"What went wrong with Z?"
└─ NM06-INDEX-Learned (START HERE) → Bugs section

"Why no threading locks in Lambda?"
└─ NM06-INDEX-Learned → NM06-LESSONS-2025.10.21-METRICS-Phase1.md (LESS-17)

"How to prevent metric injection?"
└─ NM06-INDEX-Learned → NM06-LESSONS-2025.10.21-METRICS-Phase1.md (LESS-19)

"Why use SINGLETON pattern?"
└─ NM06-INDEX-Learned → NM06-LESSONS-2025.10.21-METRICS-Phase1.md (LESS-18)
```

---

## Maintenance Notes

### When to Update This Index

**Add new bug/lesson:**
1. Add entry to Dispatch Table
2. Add REF ID to Quick Reference
3. Add keywords to Keyword Reference
4. Update line counts

**Recent Update (2025-10-21):**
- Added NM06-LESSONS-2025.10.21-METRICS-Phase1.md
- Added REF-IDs: LESS-17 to LESS-21
- Added 20+ keywords (threading, SINGLETON, metrics, security)
- Priority: CRITICAL (security & compliance fixes)

---

## Pattern Notes

This Interface Index follows the SIMA pattern from interface_cache.py:

```
Code: interface_cache.py
├─ _OPERATION_DISPATCH dictionary
├─ Maps operation → implementation
├─ execute_cache_operation() routes
└─ Sanitizes results

Knowledge: NM06-INDEX-Learned.md
├─ Dispatch Table
├─ Maps query type → Implementation file
├─ Routes based on keywords/REF IDs
└─ Claude reads complete section
```

**Same architecture. Different domain.**

---

**END OF FILE**

**Version:** 2.1.0  
**Updated:** 2025-10-21  
**New Content:** METRICS Phase 1 lessons (LESS-17 to LESS-21)  
**Ready:** Upload NM06-LESSONS-2025.10.21-METRICS-Phase1.md to complete integration
