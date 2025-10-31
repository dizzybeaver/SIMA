# FeatureAddition-Index.md

**Category:** Decision Logic  
**Subcategory:** Feature Addition  
**Files:** 2  
**Created:** 2024-10-30  
**Updated:** 2024-10-30

---

## Overview

Feature addition decisions determine how to handle new functionality requests - whether features already exist, should extend existing interfaces, require new interfaces, or belong in utilities. Also covers when to cache data for performance.

---

## Files in This Category

### DT-03: User Wants Feature X
**REF-ID:** DT-03  
**Priority:** High  
**File:** `DT-03.md`

**Summary:**
Decision tree for handling feature requests - check if exists, extend interface, create new interface, or add to utilities.

**Key Questions:**
- Does feature already exist?
- Fits existing interface?
- Substantial enough for new interface?
- Simple utility helper?

**Use When:**
- User requests new functionality
- Planning feature implementation
- Deciding interface architecture

---

### DT-04: Should This Be Cached
**REF-ID:** DT-04  
**Priority:** High  
**File:** `DT-04.md`

**Summary:**
Decision tree for caching decisions - balance performance gains against memory constraints and data staleness.

**Key Questions:**
- Is operation expensive (>10ms)?
- Accessed frequently?
- Data stable or volatile?
- Size manageable (<1MB)?

**Use When:**
- Considering caching strategy
- Optimizing performance
- Managing memory usage

---

## Quick Decision Guide

### Feature Location
```
Already exists       → Point to existing
Fits existing       → Extend interface
>200 lines + state  → New interface
Simple helper       → Utility interface
```

### Caching Decision
```
Expensive + Frequent + Stable + Small  → Cache (TTL based on volatility)
Fast (<10ms)                          → Don't cache
Volatile (changes each request)        → Don't cache
Large (>1MB)                          → Cache metadata only
```

---

## Common Scenarios

### Scenario 1: Adding Operation to Existing Interface

**Example:** Add `cache.clear_expired()` function

**Decision Path:**
1. DT-03: Already exists? NO
2. DT-03: Fits CACHE? YES
3. **Action:** Extend CACHE interface
4. Add to _OPERATION_DISPATCH
5. Implement in cache_core.py
6. Add gateway wrapper

### Scenario 2: Caching API Response

**Example:** External API call (50ms, accessed 5x per request)

**Decision Path:**
1. DT-04: Expensive? YES (50ms)
2. DT-04: Frequent? YES (5x)
3. DT-04: Stable? YES (updates every 5 min)
4. DT-04: Small? YES (10KB)
5. **Action:** Cache with TTL=300s

### Scenario 3: Creating New Interface

**Example:** Email sending capability

**Decision Path:**
1. DT-03: Already exists? NO
2. DT-03: Fits existing? NO
3. DT-03: Substantial? YES (>200 lines, has state, SMTP logic)
4. **Action:** Create EMAIL interface
5. Follow full interface creation process

---

## Related Content

**Architecture Patterns:**
- **ARCH-01:** Gateway architecture overview
- **INT-01 to INT-12:** Existing interface patterns

**Design Decisions:**
- **DEC-01:** Gateway pattern choice
- **DEC-09:** Cache design

**Anti-Patterns:**
- **AP-06:** God objects (too much in one place)
- **AP-12:** Premature optimization

**Other Decision Trees:**
- **DT-02:** Where function goes (placement)
- **DT-07:** Should I optimize
- **DT-13:** New interface decision
- **FW-01:** Cache vs compute framework

---

## Verification Checklist

**Before Adding Feature:**
- [ ] Checked if feature already exists
- [ ] Identified correct location (interface/utility)
- [ ] Considered caching needs
- [ ] Estimated complexity and size

**Before Caching:**
- [ ] Measured operation cost (>10ms?)
- [ ] Confirmed frequent access
- [ ] Verified data stability
- [ ] Checked size constraints (<1MB?)
- [ ] Selected appropriate TTL

---

## Keywords

feature addition, new feature, extend interface, create interface, caching, cache strategy, TTL, performance optimization

---

**File:** `FeatureAddition-Index.md`  
**Location:** `/sima/entries/decisions/feature-addition/`  
**End of Category Index**
