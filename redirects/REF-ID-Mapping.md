# REF-ID-Mapping.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Map old REF-IDs and paths to new SIMAv4 structure  
**Location:** `/sima/redirects/`

---

## OVERVIEW

This file maintains backward compatibility by mapping old file paths and REF-IDs to their new locations in the SIMAv4 structure. Use this when encountering references to legacy paths or when updating documentation.

**Status:** Active redirect mappings  
**Coverage:** All migrated files from SIMAv3 → SIMAv4

---

## ARCHITECTURE CORE PATTERNS

### SUGA Architecture

| Old Path (SIMAv3) | New Path (SIMAv4) | Notes |
|-------------------|-------------------|-------|
| `/NM##/core/ARCH-SUGA.md` | `/languages/python/architectures/suga/core/ARCH-01-Gateway-Trinity.md` | Split into multiple files |
| `/NM##/core/ARCH-SUGA.md` | `/languages/python/architectures/suga/core/ARCH-02-Layer-Separation.md` | Layer concepts |
| `/NM##/core/ARCH-SUGA.md` | `/languages/python/architectures/suga/core/ARCH-03-Interface-Pattern.md` | Interface patterns |
| `/entries/core/ARCH-SUGA.md` | `/languages/python/architectures/suga/core/ARCH-01-Gateway-Trinity.md` | Alternative old path |

### LMMS Architecture

| Old Path (SIMAv3) | New Path (SIMAv4) | Notes |
|-------------------|-------------------|-------|
| `/NM##/core/ARCH-LMMS.md` | `/languages/python/architectures/lmms/core/LMMS-01-Core-Concept.md` | To be created |
| `/entries/core/ARCH-LMMS.md` | `/languages/python/architectures/lmms/core/LMMS-01-Core-Concept.md` | Alternative old path |

### ZAPH Architecture

| Old Path (SIMAv3) | New Path (SIMAv4) | Notes |
|-------------------|-------------------|-------|
| `/NM##/core/ARCH-ZAPH.md` | `/languages/python/architectures/zaph/core/ZAPH-01-Tier-System.md` | To be created |
| `/entries/core/ARCH-ZAPH.md` | `/languages/python/architectures/zaph/core/ZAPH-01-Tier-System.md` | Alternative old path |

### DD Architecture (Split into DD-1 and DD-2)

| Old Path (SIMAv3) | New Path (SIMAv4) | Notes |
|-------------------|-------------------|-------|
| `/NM##/core/ARCH-DD.md` | `/languages/python/architectures/dd-1/core/DD1-01-Core-Concept.md` | Dictionary Dispatch (performance) |
| `/NM##/core/ARCH-DD.md` | `/languages/python/architectures/dd-2/core/DD2-01-Core-Concept.md` | Dependency Disciplines (architecture) |
| `/entries/core/ARCH-DD.md` | See above | DD split into two distinct patterns |

**Important:** The old "DD" (Dictionary Dispatch) has been split into:
- **DD-1:** Dictionary Dispatch (performance pattern)
- **DD-2:** Dependency Disciplines (architecture pattern)

---

## GATEWAY PATTERNS

| Old REF-ID | Old Path | New Path | Notes |
|------------|----------|----------|-------|
| GATE-01 | `/NM##/gateways/GATE-01.md` | `/languages/python/architectures/suga/gateways/GATE-01-Gateway-Entry.md` | Gateway entry point |
| GATE-02 | `/NM##/gateways/GATE-02.md` | `/languages/python/architectures/suga/gateways/GATE-02-Lazy-Imports.md` | Lazy import pattern |
| GATE-03 | `/NM##/gateways/GATE-03.md` | `/languages/python/architectures/suga/gateways/GATE-03-Circular-Prevention.md` | Circular import prevention |
| GATE-04 | `/NM##/gateways/GATE-04.md` | `/languages/python/architectures/suga/gateways/GATE-04-Cross-Interface.md` | Cross-interface calls |
| GATE-05 | `/NM##/gateways/GATE-05.md` | `/languages/python/architectures/suga/gateways/GATE-05-Wrapper-Pattern.md` | Wrapper functions |

---

## INTERFACE PATTERNS

| Old REF-ID | Old Path | New Path | Notes |
|------------|----------|----------|-------|
| INT-01 | `/NM##/interfaces/INT-01-CACHE.md` | `/languages/python/architectures/suga/interfaces/INT-01-CACHE-Interface.md` | Cache interface |
| INT-02 | `/NM##/interfaces/INT-02-LOGGING.md` | `/languages/python/architectures/suga/interfaces/INT-02-LOGGING-Interface.md` | Logging interface |
| INT-03 | `/NM##/interfaces/INT-03-SECURITY.md` | `/languages/python/architectures/suga/interfaces/INT-03-SECURITY-Interface.md` | Security interface |
| INT-04 | `/NM##/interfaces/INT-04-METRICS.md` | `/languages/python/architectures/suga/interfaces/INT-04-METRICS-Interface.md` | Metrics interface |
| INT-05 | `/NM##/interfaces/INT-05-CONFIG.md` | `/languages/python/architectures/suga/interfaces/INT-05-CONFIG-Interface.md` | Config interface |
| INT-06 | `/NM##/interfaces/INT-06-VALIDATION.md` | `/languages/python/architectures/suga/interfaces/INT-06-VALIDATION-Interface.md` | Validation interface |
| INT-07 | `/NM##/interfaces/INT-07-PERSISTENCE.md` | `/languages/python/architectures/suga/interfaces/INT-07-PERSISTENCE-Interface.md` | Persistence interface |
| INT-08 | `/NM##/interfaces/INT-08-COMMUNICATION.md` | `/languages/python/architectures/suga/interfaces/INT-08-COMMUNICATION-Interface.md` | Communication interface |
| INT-09 | `/NM##/interfaces/INT-09-TRANSFORMATION.md` | `/languages/python/architectures/suga/interfaces/INT-09-TRANSFORMATION-Interface.md` | Transform interface |
| INT-10 | `/NM##/interfaces/INT-10-SCHEDULING.md` | `/languages/python/architectures/suga/interfaces/INT-10-SCHEDULING-Interface.md` | Scheduling interface |
| INT-11 | `/NM##/interfaces/INT-11-MONITORING.md` | `/languages/python/architectures/suga/interfaces/INT-11-MONITORING-Interface.md` | Monitoring interface |
| INT-12 | `/NM##/interfaces/INT-12-ERROR.md` | `/languages/python/architectures/suga/interfaces/INT-12-ERROR-Interface.md` | Error handling interface |

---

## DECISIONS

### SUGA Decisions

| Old REF-ID | Old Path | New Path | Notes |
|------------|----------|----------|-------|
| DEC-01 | `/NM##/decisions/architecture/DEC-01.md` | `/languages/python/architectures/suga/decisions/DEC-01-SUGA-Choice.md` | SUGA pattern choice |
| DEC-02 | `/NM##/decisions/architecture/DEC-02.md` | `/languages/python/architectures/suga/decisions/DEC-02-Three-Layer-Pattern.md` | Three-layer pattern |
| DEC-03 | `/NM##/decisions/architecture/DEC-03.md` | `/languages/python/architectures/suga/decisions/DEC-03-Gateway-Mandatory.md` | Gateway requirement |
| DEC-04 | `/NM##/decisions/architecture/DEC-04.md` | `/platforms/aws/lambda/decisions/AWS-Lambda-DEC-Threading.md` | No threading (Lambda-specific) |
| DEC-05 | `/NM##/decisions/architecture/DEC-05.md` | `/languages/python/architectures/suga/decisions/DEC-05-Sentinel-Sanitization.md` | Sentinel handling |

### AWS Lambda Decisions

| Old REF-ID | Old Path | New Path | Notes |
|------------|----------|----------|-------|
| DEC-04 | `/NM##/decisions/architecture/DEC-04.md` | `/platforms/aws/lambda/decisions/AWS-Lambda-DEC-Threading.md` | Moved to platform |
| DEC-07 | `/NM##/decisions/technical/DEC-07.md` | `/platforms/aws/lambda/decisions/AWS-Lambda-DEC-Memory-Limit.md` | 128MB limit |
| DEC-08 | `/NM##/decisions/technical/DEC-08.md` | `/languages/python/architectures/suga/decisions/DEC-08-Flat-Structure.md` | Flat file structure |
| DEC-21 | `/NM##/decisions/operational/DEC-21.md` | `/platforms/aws/ssm/decisions/AWS-SSM-DEC-Token-Storage.md` | SSM token storage |

### DynamoDB Decisions

| Old REF-ID | Old Path | New Path | Notes |
|------------|----------|----------|-------|
| N/A | N/A | `/platforms/aws/dynamodb/decisions/AWS-DynamoDB-DEC-01-NoSQL-Choice.md` | New file, no old path |

---

## ANTI-PATTERNS

### SUGA Anti-Patterns

| Old REF-ID | Old Path | New Path | Notes |
|------------|----------|----------|-------|
| AP-01 | `/NM##/anti-patterns/import/AP-01.md` | `/languages/python/architectures/suga/anti-patterns/AP-01-Direct-Core-Import.md` | Direct core import |
| AP-05 | `/NM##/anti-patterns/structure/AP-05.md` | `/languages/python/architectures/suga/anti-patterns/AP-05-Subdirectories.md` | Unnecessary subdirs |
| AP-08 | `/NM##/anti-patterns/concurrency/AP-08.md` | `/platforms/aws/lambda/anti-patterns/AWS-Lambda-AP-Threading.md` | Threading in Lambda |
| AP-14 | `/NM##/anti-patterns/error-handling/AP-14.md` | `/languages/python/architectures/suga/anti-patterns/AP-14-Bare-Except.md` | Bare except clauses |
| AP-19 | `/NM##/anti-patterns/security/AP-19.md` | `/languages/python/architectures/suga/anti-patterns/AP-19-Sentinel-Leak.md` | Sentinel leak |

### AWS Lambda Anti-Patterns

| Old REF-ID | Old Path | New Path | Notes |
|------------|----------|----------|-------|
| AP-08 | `/NM##/anti-patterns/concurrency/AP-08.md` | `/platforms/aws/lambda/anti-patterns/AWS-Lambda-AP-Threading.md` | Moved to platform |
| AP-21 | `/NM##/anti-patterns/lambda/AP-21.md` | `/platforms/aws/lambda/anti-patterns/AWS-Lambda-AP-Heavy-Imports.md` | Heavy module imports |
| AP-22 | `/NM##/anti-patterns/lambda/AP-22.md` | `/platforms/aws/lambda/anti-patterns/AWS-Lambda-AP-Stateful-Design.md` | Stateful Lambda |

### DynamoDB Anti-Patterns

| Old REF-ID | Old Path | New Path | Notes |
|------------|----------|----------|-------|
| N/A | N/A | `/platforms/aws/dynamodb/anti-patterns/AWS-DynamoDB-AP-01-Using-Scan.md` | New file, no old path |

---

## LESSONS LEARNED

### SUGA Lessons

| Old REF-ID | Old Path | New Path | Notes |
|------------|----------|----------|-------|
| LESS-01 | `/NM##/lessons/core-architecture/LESS-01.md` | `/languages/python/architectures/suga/lessons/LESS-01-Read-Complete-Files.md` | Read complete files |
| LESS-15 | `/NM##/lessons/operations/LESS-15.md` | `/languages/python/architectures/suga/lessons/LESS-15-Verification-Protocol.md` | 5-step verification |

### AWS Lambda Lessons

| Old REF-ID | Old Path | New Path | Notes |
|------------|----------|----------|-------|
| LESS-02 | `/NM##/lessons/performance/LESS-02.md` | `/platforms/aws/lambda/lessons/AWS-Lambda-LESS-Measure-Dont-Guess.md` | Performance measurement |
| AWS-LESS-01 | `/NM##/lessons/lambda/AWS-LESS-01.md` | `/platforms/aws/lambda/lessons/AWS-Lambda-LESS-01-Cold-Start-Optimization.md` | Cold start optimization |

### DynamoDB Lessons

| Old REF-ID | Old Path | New Path | Notes |
|------------|----------|----------|-------|
| AWS-LESS-05 | `/NM##/lessons/dynamodb/AWS-LESS-05.md` | `/platforms/aws/dynamodb/lessons/AWS-DynamoDB-LESS-01-Partition-Key-Design.md` | Partition key design |
| AWS-LESS-06 | `/NM##/lessons/dynamodb/AWS-LESS-06.md` | Legacy (not yet migrated) | Secondary indexes |
| AWS-LESS-07 | `/NM##/lessons/dynamodb/AWS-LESS-07.md` | Legacy (referenced in AP-01) | Query vs Scan |
| AWS-LESS-08 | `/NM##/lessons/dynamodb/AWS-LESS-08.md` | Legacy (not yet migrated) | Item collections |
| N/A | N/A | `/platforms/aws/dynamodb/lessons/AWS-DynamoDB-LESS-02-Access-Pattern-First.md` | New file, no old path |

---

## BUGS

| Old REF-ID | Old Path | New Path | Notes |
|------------|----------|----------|-------|
| BUG-01 | `/NM##/lessons/bugs/BUG-01.md` | `/languages/python/architectures/suga/lessons/BUG-01-Sentinel-Leak.md` | Sentinel JSON failure |
| BUG-02 | `/NM##/lessons/bugs/BUG-02.md` | `/languages/python/architectures/suga/lessons/BUG-02-CacheMiss-Validation.md` | Cache miss handling |
| BUG-03 | `/NM##/lessons/bugs/BUG-03.md` | `/languages/python/architectures/suga/lessons/BUG-03-Circular-Import.md` | Circular import error |
| BUG-04 | `/NM##/lessons/bugs/BUG-04.md` | `/languages/python/architectures/lmms/lessons/BUG-04-Cold-Start-Spike.md` | Cold start spike |

---

## WISDOM ENTRIES

| Old REF-ID | Old Path | New Path | Notes |
|------------|----------|----------|-------|
| WISD-01 | `/NM##/lessons/wisdom/WISD-01.md` | `/entries/lessons/wisdom/WISD-01-Gateway-Pattern-Universal.md` | Gateway pattern universal |
| WISD-06 | `/NM##/lessons/wisdom/WISD-06.md` | `/entries/lessons/wisdom/WISD-06-Cache-Busting.md` | Cache-busting platform limitation |

---

## SPECIFICATIONS

| Old Path | New Path | Notes |
|----------|----------|-------|
| N/A | `/sima/entries/specifications/SPEC-FILE-STANDARDS.md` | New in SIMAv4 |
| N/A | `/sima/entries/specifications/SPEC-LINE-LIMITS.md` | New in SIMAv4 |
| N/A | `/sima/entries/specifications/SPEC-HEADERS.md` | New in SIMAv4 |
| N/A | `/sima/entries/specifications/SPEC-NAMING.md` | New in SIMAv4 |
| N/A | `/sima/entries/specifications/SPEC-ENCODING.md` | New in SIMAv4 |
| N/A | `/sima/entries/specifications/SPEC-STRUCTURE.md` | New in SIMAv4 |
| N/A | `/sima/entries/specifications/SPEC-MARKDOWN.md` | New in SIMAv4 |
| N/A | `/sima/entries/specifications/SPEC-CHANGELOG.md` | New in SIMAv4 |
| N/A | `/sima/entries/specifications/SPEC-FUNCTION-DOCS.md` | New in SIMAv4 |
| N/A | `/sima/entries/specifications/SPEC-CONTINUATION.md` | New in SIMAv4 |
| N/A | `/sima/entries/specifications/SPEC-KNOWLEDGE-CONFIG.md` | New in SIMAv4 |

---

## CONTEXT FILES

| Old Path | New Path | Notes |
|----------|----------|-------|
| `/sima/SESSION-START-Quick-Context.md` | `/sima/context/SESSION-START-Quick-Context.md` | General mode context |
| `/sima/PROJECT-MODE-Context.md` | `/sima/context/PROJECT-MODE-Context.md` | Project mode context |
| `/sima/DEBUG-MODE-Context.md` | `/sima/context/DEBUG-MODE-Context.md` | Debug mode context |
| `/sima/SIMA-LEARNING-SESSION-START-Quick-Context.md` | `/sima/context/SIMA-LEARNING-SESSION-START-Quick-Context.md` | Learning mode context |
| `/sima/MODE-SELECTOR.md` | `/sima/context/MODE-SELECTOR.md` | Mode selector |
| `/sima/Custom Instructions for SUGA-ISP Development.md` | `/sima/context/Custom Instructions for SUGA-ISP Development.md` | Custom instructions |

---

## LEE PROJECT

| Old Path | New Path | Notes |
|----------|----------|-------|
| N/A | `/sima/projects/lee/config/knowledge-config.yaml` | New in SIMAv4 |
| N/A | `/sima/projects/lee/interfaces/INT-01-CACHE-LEE.md` | LEE-specific interface docs (12 files) |
| N/A | `/sima/projects/lee/function-references/INT-01-CACHE-Functions.md` | Function reference docs (12 files) |

---

## USAGE EXAMPLES

### Example 1: Updating Documentation

**Old reference found:**
```markdown
See DEC-01 in /NM##/decisions/architecture/DEC-01.md
```

**Update to:**
```markdown
See DEC-01 in /languages/python/architectures/suga/decisions/DEC-01-SUGA-Choice.md
```

### Example 2: Following Links

**Old link:**
```markdown
[SUGA Architecture](/entries/core/ARCH-SUGA.md)
```

**New link (primary file):**
```markdown
[SUGA Gateway Trinity](/languages/python/architectures/suga/core/ARCH-01-Gateway-Trinity.md)
```

### Example 3: REF-ID Search

**Looking for:** LESS-01

**Old location:** `/NM##/lessons/core-architecture/LESS-01.md`

**New location:** `/languages/python/architectures/suga/lessons/LESS-01-Read-Complete-Files.md`

### Example 4: Ambiguous DD References

**Old reference:**
```markdown
See ARCH-DD for dependency patterns
```

**Clarification needed:**
- **Dictionary Dispatch (performance)?** → `/languages/python/architectures/dd-1/core/DD1-01-Core-Concept.md`
- **Dependency Disciplines (architecture)?** → `/languages/python/architectures/dd-2/core/DD2-01-Core-Concept.md`

---

## AUTOMATED REDIRECT SCRIPT

**Filename:** `redirect-path.sh`

```bash
#!/bin/bash
# Usage: ./redirect-path.sh <old-path>
# Returns new path from mapping

OLD_PATH=$1

case "$OLD_PATH" in
  "/NM##/core/ARCH-SUGA.md")
    echo "/languages/python/architectures/suga/core/ARCH-01-Gateway-Trinity.md"
    ;;
  "/entries/core/ARCH-SUGA.md")
    echo "/languages/python/architectures/suga/core/ARCH-01-Gateway-Trinity.md"
    ;;
  "/NM##/gateways/GATE-01.md")
    echo "/languages/python/architectures/suga/gateways/GATE-01-Gateway-Entry.md"
    ;;
  # Add more mappings as needed
  *)
    echo "No redirect found for: $OLD_PATH"
    echo "Check REF-ID-Mapping.md manually"
    exit 1
    ;;
esac
```

---

## MAINTENANCE

**When adding new redirects:**
1. Add entry to appropriate section above
2. Include old REF-ID, old path, new path
3. Add usage notes if helpful
4. Update automated script if needed

**Version history:**
- v1.0.0 (2025-11-08): Initial mapping creation, core redirects

---

**END OF MAPPING FILE**

**Purpose:** Maintain backward compatibility across migration  
**Coverage:** All migrated files from SIMAv3 → SIMAv4  
**Status:** Active, production-ready
