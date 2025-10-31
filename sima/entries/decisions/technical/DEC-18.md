# File: DEC-18.md

**REF-ID:** DEC-18  
**Category:** Technical Decision  
**Priority:** Medium  
**Status:** Active  
**Date Decided:** 2024-05-10  
**Created:** 2024-05-10  
**Last Updated:** 2025-10-30 (SIMAv4 migration)

---

## 📋 SUMMARY

Prefer Python standard library over third-party dependencies to minimize memory usage, reduce cold start time, and avoid version conflicts.

**Decision:** Use stdlib first, add dependencies only when necessary  
**Impact Level:** Medium  
**Reversibility:** Moderate (removing dependencies easier than adding)

---

## 🎯 CONTEXT

### Problem Statement
Lambda has 128MB memory limit. Heavy third-party libraries (pandas ~100MB, numpy ~50MB) consume most available memory and increase cold start time significantly.

### Background
- Lambda memory limit: 128MB
- Third-party libraries add overhead
- Import time affects cold start
- Dependency management complexity
- Version conflicts risk

### Requirements
- Stay under 128MB memory
- Minimize cold start time
- Reduce maintenance burden
- Avoid dependency conflicts

---

## 💡 DECISION

### What We Chose
Always prefer standard library. Add third-party dependencies only when:
1. Functionality not available in stdlib
2. Critical for feature (not convenience)
3. Lightweight (< 5MB uncompressed)
4. Well-maintained and stable

### Implementation Pattern
```python
# ✅ GOOD - Use stdlib
import json          # Parsing
import urllib.request # HTTP
import datetime      # Time
import re           # Regex
import collections  # Data structures
import statistics   # Math stats

# ❌ AVOID - Heavy dependencies
import pandas       # 100MB - use csv + collections
import numpy        # 50MB - use math + statistics
import scipy        # 80MB - rarely needed

# ✅ ALLOWED - Lightweight, critical
import requests     # Only in HA extension (justified)
```

### Rationale
1. **Memory Efficiency**
   - Stdlib built into Python (no extra memory)
   - Third-party libs add 5MB-100MB each
   - Can't fit pandas + numpy in 128MB

2. **Faster Cold Start**
   - Stdlib loads fast (part of runtime)
   - Heavy libraries add 50-200ms each
   - Every millisecond counts

3. **No Version Conflicts**
   - Stdlib version matches Python
   - No pip dependency resolution
   - No conflicting requirements

4. **Lower Maintenance**
   - No dependency updates
   - No security patches to track
   - Fewer breaking changes

---

## 📄 ALTERNATIVES CONSIDERED

### Alternative 1: Allow All Dependencies
**Pros:**
- Developer convenience
- Familiar libraries

**Cons:**
- Can't fit in 128MB
- Slow cold starts
- Maintenance burden

**Why Rejected:** Violates memory constraint.

---

### Alternative 2: Increase Lambda Memory
**Pros:**
- More room for dependencies

**Cons:**
- Higher AWS costs
- Not free tier
- Doesn't solve cold start

**Why Rejected:** Constraint drives good design.

---

### Alternative 3: Vendor Light Alternatives
**Pros:**
- Some convenience

**Cons:**
- Still adds dependencies
- Maintenance burden
- Not always lighter

**Why Rejected:** Stdlib preferred when sufficient.

---

## ⚖️ TRADE-OFFS

### What We Gained
- Under 128MB memory (fits free tier)
- Fast cold start (minimal imports)
- Zero dependency conflicts
- Lower maintenance burden
- More robust deployment

### What We Accepted
- More verbose code (urllib vs requests)
- Learning curve (stdlib APIs)
- Some features unavailable
- Occasional manual implementations

---

## 📊 IMPACT ANALYSIS

### Technical Impact
- **Memory:** ~50MB saved vs typical setup
- **Cold Start:** ~150ms faster
- **Deployment:** Simpler (fewer files)
- **Code:** Slightly more verbose

### Stdlib Solutions
```python
# Instead of pandas
import csv
import statistics
with open('data.csv') as f:
    reader = csv.DictReader(f)
    values = [float(row['value']) for row in reader]
    mean = statistics.mean(values)

# Instead of requests
import urllib.request
import json
req = urllib.request.Request(url, json.dumps(data).encode())
with urllib.request.urlopen(req) as response:
    result = json.loads(response.read())

# Instead of numpy
import math
import statistics
std_dev = statistics.stdev(values)
mean = statistics.mean(values)
```

---

## 🔮 FUTURE CONSIDERATIONS

### When to Revisit
- If Lambda memory limit increases
- If critical feature requires heavy library
- If performance becomes issue

### Evolution Path
- Evaluate each dependency request carefully
- Document justification for exceptions
- Consider Lambda layers for shared deps

---

## 🔗 RELATED

### Related Decisions
- **DEC-07:** 128MB Memory Limit (constraint)

### SIMA Entries
- **AP-09:** Heavy Dependencies (what to avoid)
- **LESS-06:** Pay Small Costs Early

---

## 🏷️ KEYWORDS

`stdlib`, `standard-library`, `dependencies`, `memory-limit`, `cold-start`, `lightweight`, `maintenance`

---

## 📝 VERSION HISTORY

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 3.0.0 | 2025-10-30 | Migration | SIMAv4 migration, under 400 lines |
| 2.0.0 | 2025-10-24 | System | SIMA v3 format |
| 1.0.0 | 2024-05-10 | Original | Decision made |

---

**END OF DECISION**

**Status:** Active - Consistently applied across project  
**Effectiveness:** Under 128MB, fast cold start, no conflicts
