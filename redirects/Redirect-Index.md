# Redirect-Index.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Navigation aid for finding migrated files  
**Location:** `/sima/redirects/`

---

## OVERVIEW

This index helps navigate the SIMAv3 → SIMAv4 migration by providing quick lookups for REF-IDs and file locations. Use this when you can't find a file or when old references don't work.

**Quick Jump:**
- [By REF-ID](#by-ref-id) - Look up by identifier
- [By Old Path](#by-old-path) - Find new location
- [By Category](#by-category) - Browse by type
- [Common Searches](#common-searches) - Frequently needed

---

## BY REF-ID

### Architecture Patterns (ARCH-##)

| REF-ID | Description | New Location |
|--------|-------------|--------------|
| ARCH-01 | Gateway Trinity | `/languages/python/architectures/suga/core/ARCH-01-Gateway-Trinity.md` |
| ARCH-02 | Layer Separation | `/languages/python/architectures/suga/core/ARCH-02-Layer-Separation.md` |
| ARCH-03 | Interface Pattern | `/languages/python/architectures/suga/core/ARCH-03-Interface-Pattern.md` |
| ARCH-SUGA | (Split) | See ARCH-01, ARCH-02, ARCH-03 |
| ARCH-LMMS | Lazy Module Mgmt | `/languages/python/architectures/lmms/core/LMMS-01-Core-Concept.md` ⏳ |
| ARCH-ZAPH | Zone Access Priority | `/languages/python/architectures/zaph/core/ZAPH-01-Tier-System.md` ⏳ |
| ARCH-DD | (Split into DD-1 and DD-2) | See DD1-01 and DD2-01 |

### Gateway Patterns (GATE-##)

| REF-ID | Description | New Location |
|--------|-------------|--------------|
| GATE-01 | Gateway Entry | `/languages/python/architectures/suga/gateways/GATE-01-Gateway-Entry.md` |
| GATE-02 | Lazy Imports | `/languages/python/architectures/suga/gateways/GATE-02-Lazy-Imports.md` |
| GATE-03 | Circular Prevention | `/languages/python/architectures/suga/gateways/GATE-03-Circular-Prevention.md` |
| GATE-04 | Cross-Interface | `/languages/python/architectures/suga/gateways/GATE-04-Cross-Interface.md` |
| GATE-05 | Wrapper Pattern | `/languages/python/architectures/suga/gateways/GATE-05-Wrapper-Pattern.md` |

### Interfaces (INT-##)

| REF-ID | Interface | New Location |
|--------|-----------|--------------|
| INT-01 | CACHE | `/languages/python/architectures/suga/interfaces/INT-01-CACHE-Interface.md` |
| INT-02 | LOGGING | `/languages/python/architectures/suga/interfaces/INT-02-LOGGING-Interface.md` |
| INT-03 | SECURITY | `/languages/python/architectures/suga/interfaces/INT-03-SECURITY-Interface.md` |
| INT-04 | METRICS | `/languages/python/architectures/suga/interfaces/INT-04-METRICS-Interface.md` |
| INT-05 | CONFIG | `/languages/python/architectures/suga/interfaces/INT-05-CONFIG-Interface.md` |
| INT-06 | VALIDATION | `/languages/python/architectures/suga/interfaces/INT-06-VALIDATION-Interface.md` |
| INT-07 | PERSISTENCE | `/languages/python/architectures/suga/interfaces/INT-07-PERSISTENCE-Interface.md` |
| INT-08 | COMMUNICATION | `/languages/python/architectures/suga/interfaces/INT-08-COMMUNICATION-Interface.md` |
| INT-09 | TRANSFORMATION | `/languages/python/architectures/suga/interfaces/INT-09-TRANSFORMATION-Interface.md` |
| INT-10 | SCHEDULING | `/languages/python/architectures/suga/interfaces/INT-10-SCHEDULING-Interface.md` |
| INT-11 | MONITORING | `/languages/python/architectures/suga/interfaces/INT-11-MONITORING-Interface.md` |
| INT-12 | ERROR_HANDLING | `/languages/python/architectures/suga/interfaces/INT-12-ERROR-Interface.md` |

### Decisions (DEC-##)

| REF-ID | Description | New Location |
|--------|-------------|--------------|
| DEC-01 | SUGA Choice | `/languages/python/architectures/suga/decisions/DEC-01-SUGA-Choice.md` |
| DEC-02 | Three-Layer Pattern | `/languages/python/architectures/suga/decisions/DEC-02-Three-Layer-Pattern.md` |
| DEC-03 | Gateway Mandatory | `/languages/python/architectures/suga/decisions/DEC-03-Gateway-Mandatory.md` |
| DEC-04 | No Threading | `/platforms/aws/lambda/decisions/AWS-Lambda-DEC-Threading.md` |
| DEC-05 | Sentinel Sanitization | `/languages/python/architectures/suga/decisions/DEC-05-Sentinel-Sanitization.md` |
| DEC-07 | Memory Limit | `/platforms/aws/lambda/decisions/AWS-Lambda-DEC-Memory-Limit.md` |
| DEC-08 | Flat Structure | `/languages/python/architectures/suga/decisions/DEC-08-Flat-Structure.md` |
| DEC-21 | SSM Token Storage | `/platforms/aws/ssm/decisions/AWS-SSM-DEC-Token-Storage.md` |

### Anti-Patterns (AP-##)

| REF-ID | Description | New Location |
|--------|-------------|--------------|
| AP-01 | Direct Core Import | `/languages/python/architectures/suga/anti-patterns/AP-01-Direct-Core-Import.md` |
| AP-05 | Subdirectories | `/languages/python/architectures/suga/anti-patterns/AP-05-Subdirectories.md` |
| AP-08 | Threading in Lambda | `/platforms/aws/lambda/anti-patterns/AWS-Lambda-AP-Threading.md` |
| AP-14 | Bare Except | `/languages/python/architectures/suga/anti-patterns/AP-14-Bare-Except.md` |
| AP-19 | Sentinel Leak | `/languages/python/architectures/suga/anti-patterns/AP-19-Sentinel-Leak.md` |
| AP-21 | Heavy Imports | `/platforms/aws/lambda/anti-patterns/AWS-Lambda-AP-Heavy-Imports.md` |
| AP-22 | Stateful Lambda | `/platforms/aws/lambda/anti-patterns/AWS-Lambda-AP-Stateful-Design.md` |

### Lessons (LESS-##)

| REF-ID | Description | New Location |
|--------|-------------|--------------|
| LESS-01 | Read Complete Files | `/languages/python/architectures/suga/lessons/LESS-01-Read-Complete-Files.md` |
| LESS-02 | Measure Don't Guess | `/platforms/aws/lambda/lessons/AWS-Lambda-LESS-Measure-Dont-Guess.md` |
| LESS-15 | Verification Protocol | `/languages/python/architectures/suga/lessons/LESS-15-Verification-Protocol.md` |

### Bugs (BUG-##)

| REF-ID | Description | New Location |
|--------|-------------|--------------|
| BUG-01 | Sentinel JSON Failure | `/languages/python/architectures/suga/lessons/BUG-01-Sentinel-Leak.md` |
| BUG-02 | CacheMiss Validation | `/languages/python/architectures/suga/lessons/BUG-02-CacheMiss-Validation.md` |
| BUG-03 | Circular Import | `/languages/python/architectures/suga/lessons/BUG-03-Circular-Import.md` |
| BUG-04 | Cold Start Spike | `/languages/python/architectures/lmms/lessons/BUG-04-Cold-Start-Spike.md` ⏳ |

### Wisdom (WISD-##)

| REF-ID | Description | New Location |
|--------|-------------|--------------|
| WISD-01 | Gateway Universal | `/entries/lessons/wisdom/WISD-01-Gateway-Pattern-Universal.md` |
| WISD-06 | Cache-Busting | `/entries/lessons/wisdom/WISD-06-Cache-Busting.md` |

### AWS Lambda Lessons (AWS-LESS-##)

| REF-ID | Description | New Location |
|--------|-------------|--------------|
| AWS-LESS-01 | Cold Start Optimization | `/platforms/aws/lambda/lessons/AWS-Lambda-LESS-01-Cold-Start-Optimization.md` |

### AWS DynamoDB (AWS-DynamoDB-##)

| REF-ID | Description | New Location |
|--------|-------------|--------------|
| AWS-DynamoDB-DEC-01 | NoSQL Choice | `/platforms/aws/dynamodb/decisions/AWS-DynamoDB-DEC-01-NoSQL-Choice.md` |
| AWS-DynamoDB-LESS-01 | Partition Key Design | `/platforms/aws/dynamodb/lessons/AWS-DynamoDB-LESS-01-Partition-Key-Design.md` |
| AWS-DynamoDB-LESS-02 | Access Pattern First | `/platforms/aws/dynamodb/lessons/AWS-DynamoDB-LESS-02-Access-Pattern-First.md` |
| AWS-DynamoDB-AP-01 | Using Scan | `/platforms/aws/dynamodb/anti-patterns/AWS-DynamoDB-AP-01-Using-Scan.md` |
| AWS-LESS-05 | (Renamed) | See AWS-DynamoDB-LESS-01 |
| AWS-LESS-06 | Secondary Indexes | Legacy file (not yet migrated) |
| AWS-LESS-07 | Query vs Scan | Legacy file (referenced in AP-01) |
| AWS-LESS-08 | Item Collections | Legacy file (not yet migrated) |

---

## BY OLD PATH

### Old: `/NM##/` Structure

| Old Path | New Location |
|----------|--------------|
| `/NM##/core/ARCH-SUGA.md` | `/languages/python/architectures/suga/core/ARCH-01-Gateway-Trinity.md` (split) |
| `/NM##/core/ARCH-LMMS.md` | `/languages/python/architectures/lmms/core/LMMS-01-Core-Concept.md` ⏳ |
| `/NM##/core/ARCH-ZAPH.md` | `/languages/python/architectures/zaph/core/ZAPH-01-Tier-System.md` ⏳ |
| `/NM##/core/ARCH-DD.md` | `/languages/python/architectures/dd-1/` and `/languages/python/architectures/dd-2/` (split) |
| `/NM##/gateways/*.md` | `/languages/python/architectures/suga/gateways/` |
| `/NM##/interfaces/*.md` | `/languages/python/architectures/suga/interfaces/` |
| `/NM##/decisions/architecture/*.md` | `/languages/python/architectures/suga/decisions/` OR `/platforms/aws/lambda/decisions/` |
| `/NM##/decisions/technical/*.md` | Various (platform or architecture specific) |
| `/NM##/decisions/operational/*.md` | Various (platform specific) |
| `/NM##/anti-patterns/import/*.md` | `/languages/python/architectures/suga/anti-patterns/` |
| `/NM##/anti-patterns/concurrency/*.md` | `/platforms/aws/lambda/anti-patterns/` |
| `/NM##/lessons/core-architecture/*.md` | `/languages/python/architectures/suga/lessons/` |
| `/NM##/lessons/operations/*.md` | `/languages/python/architectures/suga/lessons/` |
| `/NM##/lessons/performance/*.md` | `/platforms/aws/lambda/lessons/` |
| `/NM##/lessons/bugs/*.md` | `/languages/python/architectures/suga/lessons/` OR `/languages/python/architectures/lmms/lessons/` |
| `/NM##/lessons/wisdom/*.md` | `/entries/lessons/wisdom/` |

### Old: `/entries/` Structure

| Old Path | New Location |
|----------|--------------|
| `/entries/core/ARCH-*.md` | `/languages/python/architectures/*/core/` |
| `/entries/gateways/*.md` | `/languages/python/architectures/suga/gateways/` (if SUGA-specific) |
| `/entries/decisions/*.md` | Various (domain-specific location) |
| `/entries/anti-patterns/*.md` | Various (domain-specific location) |
| `/entries/lessons/*.md` | Various (domain-specific location) |

---

## BY CATEGORY

### Python SUGA Architecture

**Base:** `/languages/python/architectures/suga/`

**Contains:**
- Core concepts (3 files)
- Gateway patterns (5 files)
- Interface definitions (12 files)
- Decisions (5 files)
- Anti-patterns (5 files)
- Lessons (8 files)
- Indexes (7 files)

**Total:** 31 files ✅

### Python LMMS Architecture

**Base:** `/languages/python/architectures/lmms/`

**Status:** ⏳ Planned (not yet created)

### Python ZAPH Architecture

**Base:** `/languages/python/architectures/zaph/`

**Status:** ⏳ Planned (not yet created)

### Python DD-1 (Dictionary Dispatch)

**Base:** `/languages/python/architectures/dd-1/`

**Status:** ⏳ Planned (not yet created)

### Python DD-2 (Dependency Disciplines)

**Base:** `/languages/python/architectures/dd-2/`

**Status:** ⏳ Planned (not yet created)

### AWS Lambda Platform

**Base:** `/platforms/aws/lambda/`

**Contains:**
- Lessons (10+ files)
- Decisions (5+ files)
- Anti-patterns (5+ files)
- Core concepts (5+ files)
- Indexes (1+ files)

**Total:** 30+ files ✅

### AWS API Gateway Platform

**Base:** `/platforms/aws/api-gateway/`

**Contains:**
- Lessons
- Decisions
- Anti-patterns
- Integration patterns
- Indexes

**Total:** 10 files ✅

### AWS DynamoDB Platform

**Base:** `/platforms/aws/dynamodb/`

**Contains:**
- Core concepts (1 file)
- Decisions (1 file)
- Lessons (2 files)
- Anti-patterns (1 file)
- Indexes (1 file)

**Total:** 6 files ✅

### LEE Project

**Base:** `/sima/projects/lee/`

**Contains:**
- Configuration (1 file)
- LEE-specific interfaces (12 files)
- Function references (12 files)
- Lessons
- Decisions
- Architecture docs

**Total:** 15+ files ✅

### Specifications

**Base:** `/sima/entries/specifications/`

**Contains:**
- File standards
- Line limits
- Headers
- Naming
- Encoding
- Structure
- Markdown
- Changelog
- Function docs
- Continuation
- Knowledge config

**Total:** 11 files ✅

---

## COMMON SEARCHES

### "Where's the SUGA architecture doc?"

**Answer:** SUGA architecture split into multiple files:
- Core: `/languages/python/architectures/suga/core/ARCH-01-Gateway-Trinity.md`
- Overview: `/languages/python/architectures/suga/core/ARCH-02-Layer-Separation.md`
- Interfaces: `/languages/python/architectures/suga/core/ARCH-03-Interface-Pattern.md`
- Master index: `/languages/python/architectures/suga/indexes/SUGA-Master-Index.md`

### "Where's LESS-01?"

**Answer:** `/languages/python/architectures/suga/lessons/LESS-01-Read-Complete-Files.md`

**Lesson:** Always read complete files before modifying

### "Where's DEC-04 about threading?"

**Answer:** `/platforms/aws/lambda/decisions/AWS-Lambda-DEC-Threading.md`

**Decision:** No threading locks in AWS Lambda (single-threaded runtime)

### "Where are the interface definitions?"

**Answer:** `/languages/python/architectures/suga/interfaces/`

**Files:**
- INT-01 through INT-12 (all 12 interfaces)
- Each has its own file

### "Where's the DynamoDB documentation?"

**Answer:** `/platforms/aws/dynamodb/`

**Key files:**
- Core concepts: `core/AWS-DynamoDB-Core-Concepts.md`
- Master index: `indexes/AWS-DynamoDB-Master-Index.md`
- Partition keys: `lessons/AWS-DynamoDB-LESS-01-Partition-Key-Design.md`

### "Where's the migration plan?"

**Answer:** `/sima/Knowledge-Migration-Plan.4.2.2.md` (root directory)

**Purpose:** Complete migration strategy and roadmap

### "Where's the DD architecture?"

**Important:** DD has been split into two distinct patterns:
- **DD-1 (Dictionary Dispatch):** `/languages/python/architectures/dd-1/` - Performance pattern for function routing
- **DD-2 (Dependency Disciplines):** `/languages/python/architectures/dd-2/` - Architecture pattern for layer management

**Both:** ⏳ Planned (not yet created)

### "Where are the mode context files?"

**Answer:** `/sima/context/`

**Files:**
- MODE-SELECTOR.md (mode selection)
- SESSION-START-Quick-Context.md (general mode)
- PROJECT-MODE-Context.md (project mode)
- DEBUG-MODE-Context.md (debug mode)
- SIMA-LEARNING-SESSION-START-Quick-Context.md (learning mode)
- Custom Instructions for SUGA-ISP Development.md (main instructions)

### "Where's the LEE project config?"

**Answer:** `/sima/projects/lee/config/knowledge-config.yaml`

**Purpose:** Defines which knowledge domains LEE uses

---

## MIGRATION STATUS LEGEND

| Symbol | Meaning |
|--------|---------|
| ✅ | File exists and is complete |
| ⏳ | Planned but not yet created |
| ⚠️ | Partial / needs work |
| 📦 | Legacy file (not yet migrated) |
| 🔀 | Split into multiple files |
| 🔗 | Merged with another file |

---

## QUICK TIPS

### Finding Migrated Files

1. **Check this index** - Search by REF-ID or old path
2. **Check REF-ID-Mapping.md** - Detailed path mappings
3. **Check domain index** - e.g., SUGA-Master-Index.md
4. **Use project_knowledge_search** - Claude can find files

### Understanding New Structure

```
/sima/
├── entries/           # Universal patterns (generic)
├── platforms/         # Platform-specific (AWS, Azure, GCP)
├── languages/         # Language-specific (Python, JavaScript)
│   └── python/
│       └── architectures/  # Architecture patterns (SUGA, LMMS, etc.)
├── projects/          # Project implementations (LEE)
├── context/           # Mode activation files
└── redirects/         # This directory (backward compatibility)
```

### What if file isn't in this index?

1. **Check if it's new** - Not all files are migrated
2. **Check legacy structure** - Some files remain in old location
3. **Search knowledge base** - Use project_knowledge_search
4. **File may not exist yet** - Some planned for future

---

## VERSION HISTORY

**v1.0.0 (2025-11-08):**
- Initial redirect index
- By REF-ID section
- By old path section
- By category section
- Common searches
- Quick tips

---

**END OF REDIRECT INDEX**

**Purpose:** Navigate migrated files and maintain backward compatibility  
**Coverage:** All migrated REF-IDs and paths  
**Status:** Active, production-ready  
**See Also:** REF-ID-Mapping.md for detailed mappings
