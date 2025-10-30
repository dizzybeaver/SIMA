# DynamoDB Knowledge Extraction Plan

**Source 1:** AWS Prescriptive Guidance - Modeling data with Amazon DynamoDB  
**Source 2:** Amazon DynamoDB Research Paper (2022) - Architectural Evolution  
**Date:** 2025-10-27  
**Purpose:** Identify generic, transferable knowledge worth capturing in SIMA neural maps  
**Status:** Ready for extraction - TWO COMPLEMENTARY SOURCES

---

## 🎯 EXTRACTION OVERVIEW

**Total Identified Entries:** 30-35 entries across 7 categories  
**Sources:** 2 documents (design guide + operational paper)  
**Estimated Value:** VERY HIGH - Design principles + Real-world operational wisdom  
**Genericization Level:** HIGH - Most concepts apply to any distributed database  
**Brevity Target:** Each entry < 150 lines

**Document Synergy:**
- Source 1 (Modeling Guide): Design patterns, methodology, data modeling process
- Source 2 (Research Paper): Operational lessons, architectural evolution, failure modes
- Overlap: Minimal - complementary perspectives (design vs operations)

---

## 📋 CATEGORY 1: DESIGN DECISIONS (DEC)

### DEC-## Access Pattern-First vs Structure-First Design

**Priority:** HIGH  
**Source:** Modeling Guide  
**Concept:** NoSQL modeling inverts traditional RDBMS approach  
**Generic Principle:**
- RDBMS: Design normalized structure → Add access patterns later
- NoSQL: Identify access patterns → Design structure to support them
- Query operations drive storage decisions, not relationships

**Why Extract:**
- Fundamental paradigm shift in database design
- Applies to any NoSQL database (MongoDB, Cassandra, etc.)
- Critical decision point for architects

**Brevity:** ~80 lines (explanation + comparison table + when to use each)

---

### DEC-## Distributed vs Centralized Admission Control

**Priority:** HIGH  
**Source:** Research Paper (Section 4.2)  
**Concept:** Where to enforce throughput limits - at partition level or globally  
**Generic Principle:**
- Distributed: Each node enforces limits independently (simple, but inflexible)
- Centralized: Global service tracks consumption (complex, but handles skew)
- Trade-off: Operational complexity vs handling non-uniform workloads
- Evolution: DynamoDB moved from distributed → global admission control (GAC)

**Why Extract:**
- Fundamental architectural decision for any multi-tenant system
- Shows evolution from simple to sophisticated approach
- Applies to rate limiting, quotas, resource allocation in any distributed system

**Brevity:** ~90 lines (both approaches + trade-offs + when each works)

---

### DEC-## Static Allocation vs Dynamic Bursting

**Priority:** HIGH  
**Source:** Research Paper (Section 4.1.1)  
**Concept:** Should resources be strictly partitioned or allow sharing?  
**Generic Principle:**
- Static: Guaranteed capacity, simple isolation, but wasteful
- Bursting: Use neighbor's unused capacity, efficient, but risks interference
- Requires: Token buckets, node-level monitoring, workload isolation
- Best: Hybrid approach with defense-in-depth

**Why Extract:**
- Universal resource allocation problem
- Applies to CPU, memory, network, storage in any multi-tenant system
- Balance between guarantee and efficiency

**Brevity:** ~85 lines (concept + mechanisms + trade-offs)

---

### DEC-## On-Demand vs Provisioned Capacity

**Priority:** MEDIUM  
**Concept:** Two capacity models with different cost/scaling characteristics  
**Generic Principle:**
- On-demand: Pay per request, automatic scaling, unpredictable workloads
- Provisioned: Reserve capacity, predictable costs, steady workloads
- Hybrid approaches possible

**Why Extract:**
- Applies to any cloud database service with capacity options
- Financial decision with technical implications
- Common pattern across AWS services

**Brevity:** ~70 lines (comparison + decision criteria)

---

### DEC-## Cache-Based vs Cache-Independent Scaling

**Priority:** MEDIUM  
**Source:** Research Paper (Section 6.6)  
**Concept:** Should system scale based on cache hit ratio or independently?  
**Generic Principle:**
- Cache-dependent: Scale backend based on cache misses (efficient but fragile)
- Cache-independent: Constant backend load regardless of cache (robust but "wasteful")
- Problem: Bi-modal behavior during cold starts with cache-dependent
- Solution: Asynchronous refresh on cache hits maintains constant load

**Why Extract:**
- Common scaling failure mode
- Prevents cascading failures
- Applies to any cached system (CDNs, APIs, databases)

**Brevity:** ~80 lines (both approaches + failure scenario + solution)

---

---

### DEC-## Single Table vs Multiple Tables

**Priority:** HIGH  
**Concept:** NoSQL best practice favors single table designs  
**Generic Principle:**
- Single table: Related entities in one table, accessed via different indexes
- Multiple tables: Traditional separation, requires joins/multiple queries
- Trade-offs: Complexity vs query efficiency

**Why Extract:**
- Counter-intuitive for RDBMS developers
- Significant performance implications
- Design decision affects entire architecture

**Brevity:** ~90 lines (rationale + examples + trade-offs)

---

## 📋 CATEGORY 2: LESSONS LEARNED (LESS)

### LESS-## Partition Key Determines Physical Distribution

**Priority:** HIGH  
**Concept:** Partition key hash determines data location and query performance  
**Generic Principle:**
- High cardinality = better distribution
- Low cardinality = hot partitions
- Composite keys can improve distribution
- Write sharding prevents hotspots

**Why Extract:**
- Universal to all partitioned/sharded databases
- Performance bottleneck if done wrong
- Applies to Cassandra, MongoDB sharding, etc.

**Brevity:** ~100 lines (explanation + anti-patterns + solutions)

---

### LESS-## Sort Keys Enable Hierarchical Queries

**Priority:** MEDIUM  
**Concept:** Sort keys allow querying at any level of hierarchy  
**Generic Principle:**
- Begin_with operator enables prefix matching
- Path-based keys encode tree structures
- Single query retrieves subtrees
- Example: "CM1|CM2|CM4" path structure

**Why Extract:**
- Elegant solution to common problem
- Applies beyond databases (file systems, URLs, etc.)
- Alternative to recursive queries

**Brevity:** ~110 lines (pattern + example + variations)

---

### LESS-## Sparse Indexes Save Storage and Cost

**Priority:** MEDIUM  
**Concept:** Secondary indexes only contain items with index key present  
**Generic Principle:**
- Not all items need to be in every index
- Conditional presence = natural filtering
- Reduces storage and improves query speed
- Pattern: "Status" field only on active items

**Why Extract:**
- Optimization technique applicable to any indexed system
- Cost reduction strategy
- Performance improvement

**Brevity:** ~85 lines (concept + use cases + example)

---

### LESS-## GSI Overloading Maximizes Index Utility

**Priority:** MEDIUM  
**Concept:** Single index serves multiple entity types  
**Generic Principle:**
- Generic attribute names (GSI1PK, GSI1SK)
- Different entities map different data to same columns
- Reduces index count and cost
- Requires careful planning

**Why Extract:**
- Advanced optimization pattern
- Applies to any database with limited indexes
- Trade-off between simplicity and efficiency

**Brevity:** ~95 lines (technique + examples + cautions)

---

### LESS-## Validate with Representative Data Volume

**Priority:** HIGH  
**Concept:** Testing with small datasets hides distribution problems  
**Generic Principle:**
- Small data: Everything fits in memory, queries fast
- Production scale: Distribution matters, hotspots emerge
- Test with >= 10x expected volume
- Monitor partition metrics

**Why Extract:**
- Common failure mode in database projects
- Applies to any distributed database
- Critical for performance validation

**Brevity:** ~75 lines (problem + solution + metrics to monitor)

---

### LESS-## Continuous Verification Catches Silent Corruption

**Priority:** HIGH  
**Source:** Research Paper (Section 5.3)  
**Concept:** Continuously verify data at rest, not just at write time  
**Generic Principle:**
- Write-time checks: Not sufficient (bit rot, hardware bugs, software bugs)
- Continuous verification: Compare live data vs archived logs periodically
- "Scrub" process: Rebuilds replicas from logs, compares checksums
- Catches: Silent data errors, divergence, corruption over time

**Why Extract:**
- Critical for durability claims ("11 nines")
- Protects against unexpected failure modes
- Applies to any system making durability guarantees

**Brevity:** ~95 lines (problem + scrub mechanism + benefits)

---

### LESS-## Gray Failures Require Consensus-Based Detection

**Priority:** HIGH  
**Source:** Research Paper (Section 6.2)  
**Concept:** Network failures aren't always binary (working or failed)  
**Generic Principle:**
- Gray failure: Partial connectivity (A→B works, B→A fails)
- Naive detection: Single node decides leader is down → spurious failovers
- Better detection: Follower asks peers "Can you reach leader?" before triggering election
- Result: Eliminates false positives, maintains availability

**Why Extract:**
- Common distributed systems failure mode
- Causes unnecessary disruption if not handled
- Applies to any system with leader election

**Brevity:** ~100 lines (gray failure concept + detection algorithm + impact)

---

### LESS-## Log Replicas Enable Fast Healing

**Priority:** MEDIUM  
**Source:** Research Paper (Section 5.1, 6.1)  
**Concept:** Add lightweight replicas for durability, not full replicas  
**Generic Principle:**
- Full replica: B-tree + logs (takes minutes to restore)
- Log replica: Recent logs only (takes seconds to add)
- Use case: When storage replica fails, quickly add log replica for write quorum
- Result: Maintains durability without long repair windows

**Why Extract:**
- Clever optimization for availability
- Trade-off: Storage vs restore time
- Applicable to any multi-primary or Paxos-based system

**Brevity:** ~90 lines (concept + use case + benefits)

---

### LESS-## Token Buckets Enable Flexible Rate Limiting

**Priority:** MEDIUM  
**Source:** Research Paper (Section 4.1.1, 4.2)  
**Concept:** Use token bucket algorithm for admission control  
**Generic Principle:**
- Token bucket: Accumulates capacity tokens, consumes on requests
- Hierarchy: Partition-level buckets + node-level bucket + global bucket
- Bursting: Use lower-level unused capacity when available
- Defense-in-depth: Multiple levels prevent noisy neighbors

**Why Extract:**
- Standard rate limiting pattern
- Shows how to implement multi-level quotas
- Applies to APIs, databases, networks, any rate-limited resource

**Brevity:** ~85 lines (algorithm + hierarchy + example)

---

### LESS-## Formal Methods Catch Subtle Distributed Bugs

**Priority:** MEDIUM  
**Source:** Research Paper (Section 5.4)  
**Concept:** Model check protocols before implementation  
**Generic Principle:**
- TLA+ specification: Formally model replication protocol
- Model checking: Exhaustively test all states
- Catches: Race conditions, edge cases, protocol violations
- Before production: No customers impacted by subtle bugs

**Why Extract:**
- Increasingly common practice for critical systems
- Valuable for consensus protocols, distributed algorithms
- Referenced by S3, other AWS services

**Brevity:** ~75 lines (approach + benefits + when to use)

---

### LESS-## Checksums at Every Layer Prevent Error Propagation

**Priority:** MEDIUM  
**Source:** Research Paper (Section 5.2)  
**Concept:** Verify data integrity at every transformation  
**Generic Principle:**
- Compute checksum: At every message, log entry, file archive
- Verify checksum: Before processing, after transfer, on storage
- Guards: Against CPU errors, memory errors, network corruption
- Fail fast: Don't propagate corrupted data to rest of system

**Why Extract:**
- Defense-in-depth strategy
- Applies to any data pipeline or storage system
- Small overhead, huge reliability benefit

**Brevity:** ~70 lines (strategy + where to apply + example)

---

### LESS-## Upgrade/Downgrade Testing Prevents Rollback Failures

**Priority:** HIGH  
**Source:** Research Paper (Section 6.4)  
**Concept:** Test not just deployment, but rollback paths too  
**Generic Principle:**
- Tested: New version works
- Untested: Rollback to old version works (common oversight)
- Process: Deploy new → run tests → rollback → run tests again
- Catches: Incompatibilities, one-way migrations, protocol changes

**Why Extract:**
- Often-missed failure mode
- Critical for production systems
- Applies to any system requiring rollbacks

**Brevity:** ~80 lines (problem + solution + process)

---

### LESS-## Read-Write Deployments Handle Protocol Changes

**Priority:** MEDIUM  
**Source:** Research Paper (Section 6.4)  
**Concept:** Multi-phase deployments for backward compatibility  
**Generic Principle:**
- Phase 1: Deploy code that can READ new message format
- Phase 2: Deploy code that WRITES new message format
- Result: Old and new code coexist during deployment
- Enables: Safe rollbacks at any point

**Why Extract:**
- Standard pattern for protocol evolution
- Prevents deployment-time failures
- Applies to any distributed system with versioned protocols

**Brevity:** ~85 lines (approach + phases + example)

---

## 📋 CATEGORY 3: ANTI-PATTERNS (AP)

### AP-## Scanning Instead of Querying

**Priority:** HIGH  
**Concept:** Scan reads entire table, query reads partition  
**Anti-Pattern:**
- Using Scan for data retrieval
- "I'll filter in application code"
- Ignoring partition key design

**Why Extract:**
- Common mistake by RDBMS developers
- Severe performance and cost implications
- Applies to any NoSQL database

**Brevity:** ~70 lines (problem + why it's bad + correct approach)

---

### AP-## Low-Cardinality Partition Keys

**Priority:** HIGH  
**Concept:** Partition keys with few unique values create hotspots  
**Anti-Pattern Examples:**
- Status (active/inactive)
- Type (user/admin)
- Date (same day)
- Boolean flags

**Why Extract:**
- Classic performance bottleneck
- Easy to make this mistake
- Universal to all partitioned systems

**Brevity:** ~80 lines (examples + impact + solutions)

---

### AP-## Normalizing NoSQL Data

**Priority:** MEDIUM  
**Concept:** Applying RDBMS normalization to NoSQL  
**Anti-Pattern:**
- Breaking data into multiple tables for "normalization"
- Requiring joins to retrieve complete objects
- Optimizing for write consistency over read performance

**Why Extract:**
- Mental model mismatch
- Defeats NoSQL advantages
- Common among RDBMS-experienced developers

**Brevity:** ~85 lines (problem + why wrong + correct approach)

---

### AP-## Tight Coupling Partition Capacity to Partition Size

**Priority:** HIGH  
**Source:** Research Paper (Section 4)  
**Anti-Pattern:** Splitting partition for size equally divides throughput  
**Problem:**
- Partition splits for storage: 10GB → two 5GB partitions
- Throughput also splits: 1000 WCU → two 500 WCU partitions
- But workload isn't split evenly: Hot data might be in one partition
- Result: "Throughput dilution" - available capacity decreases after split

**Why Extract:**
- Classic distributed system design flaw
- Shows coupling of orthogonal concerns (size vs performance)
- Led to major DynamoDB redesign

**Brevity:** ~90 lines (problem + example + why it fails + solution)

---

### AP-## Relying on Caches to Hide Scaling Problems

**Priority:** HIGH  
**Source:** Research Paper (Section 6.6, Key Lessons)  
**Anti-Pattern:** Design system to work with cache, breaks without it  
**Problem:**
- Cache hides: Backend can't handle full load
- Works until: Cache miss storm, cold start, cache invalidation
- Bi-modal behavior: 99% fast (cache hit), 1% fails (cache miss overwhelms backend)
- Cascading failure: Cache ineffective → backend overload → system failure

**Why Extract:**
- Fundamental system design principle
- "Design for predictability over absolute efficiency"
- Caches should be optimization, not requirement

**Brevity:** ~95 lines (anti-pattern + failure scenario + correct approach)

---

### AP-## False Positives in Failure Detection

**Priority:** MEDIUM  
**Source:** Research Paper (Section 6.2)  
**Anti-Pattern:** Single node decides on leader failure without consensus  
**Problem:**
- Network partition: Follower can't reach leader
- Assumption: Leader must be down
- Reality: Other followers can reach leader fine (gray failure)
- Result: Spurious leader election, availability disruption

**Why Extract:**
- Applies to any distributed consensus system
- Common source of unnecessary failovers
- Shows importance of multi-node agreement

**Brevity:** ~85 lines (anti-pattern + scenarios + solution)

---

### AP-## Ignoring Access Patterns During Design

**Priority:** HIGH  
**Concept:** Designing schema before understanding queries  
**Anti-Pattern:**
- "We'll figure out queries later"
- Designing based on entity relationships alone
- Assuming flexibility like RDBMS

**Why Extract:**
- Fundamental methodology error
- Leads to redesign and migration
- Opposite of NoSQL best practice

**Brevity:** ~75 lines (problem + consequences + correct process)

---

## 📋 CATEGORY 4: WISDOM (WISD)

### WISD-## Design for Your Queries, Not Your Entities

**Priority:** HIGH  
**Wisdom:** "NoSQL schema design is not about data structure, it's about access patterns"  
**Core Insight:**
- RDBMS: Model reality → Query it
- NoSQL: Model queries → Store accordingly
- Denormalization is a feature, not a bug
- Pre-compute joins at write time

**Why Extract:**
- Philosophical shift in thinking
- Guides all design decisions
- Hard-won industry wisdom

**Brevity:** ~90 lines (principle + examples + implications)

---

### WISD-## Preliminary Cost Estimation Prevents Late Surprises

**Priority:** MEDIUM  
**Wisdom:** Calculate costs early and refine throughout process  
**Core Insight:**
- Step 2: Preliminary estimate (high-level)
- Step 8: Refined estimate (detailed)
- Get approval at both stages
- Cost model influences design choices

**Why Extract:**
- Project management best practice
- Prevents "too expensive to deploy" discoveries
- Applicable to any cloud database project

**Brevity:** ~70 lines (approach + two estimation points + benefits)

---

### WISD-## One Table Per Application (When Possible)

**Priority:** MEDIUM  
**Wisdom:** "Maintain as few tables as possible in a DynamoDB application"  
**Core Insight:**
- Single table simplifies operations
- Secondary indexes provide multiple views
- Reduces management overhead
- Better for most use cases

**Why Extract:**
- Counter-intuitive guideline
- Significant operational impact
- Debated topic in community

**Brevity:** ~80 lines (rationale + when to violate + trade-offs)

---

## 📋 CATEGORY 5: PROCESSES (PROC) - NEW CATEGORY?

### PROC-## Nine-Step Data Modeling Process

**Priority:** HIGH  
**Process Steps:**
1. Identify use cases and logical model
2. Create preliminary cost estimation
3. Identify access patterns
4. Identify technical requirements
5. Create data model
6. Create data queries
7. Validate data model
8. Review cost estimation
9. Deploy data model

**Why Extract:**
- Systematic approach to database design
- RACI matrix for stakeholder involvement
- Repeatable methodology
- Applicable to any database project (genericized)

**Brevity:** ~150 lines (process overview + key activities + RACI + when to use)

**Note:** This might warrant a new category "PROC" for documented processes, or could fit in WISD as methodology wisdom.

---

### PROC-## Access Pattern Documentation Template

**Priority:** MEDIUM  
**Template Fields:**
- Access pattern name
- Description
- Priority (High/Medium/Low)
- Read or write
- Type (single/multiple/all items)
- Key attributes
- Filters
- Result ordering

**Why Extract:**
- Standardized documentation approach
- Communication tool for team
- Ensures completeness
- Applicable to any database design

**Brevity:** ~60 lines (template + field explanations + example)

---

## 📋 CATEGORY 6: DESIGN PATTERNS (PATT) - NEW CATEGORY?

### PATT-## Hierarchical Path Encoding

**Priority:** HIGH  
**Pattern:** Encode tree structures using delimited paths  
**Implementation:**
- Path: "Root|Parent|Child|Grandchild"
- Delimiter: "|" (or any non-data character)
- Query: BEGINS_WITH(path, "Root|Parent|")
- Returns: All descendants

**Why Extract:**
- Elegant solution for hierarchical data
- Avoids recursive queries
- Applicable beyond databases (file systems, URLs, categories)
- Demonstrated with automotive component example

**Brevity:** ~120 lines (pattern + full example + variations + limitations)

---

### PATT-## Adjacency List for Relationships

**Priority:** MEDIUM  
**Pattern:** Model graph relationships in key-value stores  
**Implementation:**
- Each item stores its own ID and parent ID
- Secondary index on parent ID
- Query: "Find children of X"
- Multiple GSIs for different relationship types

**Why Extract:**
- Classic NoSQL pattern
- Handles many-to-many relationships
- Referenced but not detailed in document
- Worth capturing as standalone pattern

**Brevity:** ~85 lines (pattern + use cases + example)

---

### PATT-## Write Sharding for Hot Partitions

**Priority:** MEDIUM  
**Pattern:** Append random suffix to partition key  
**Implementation:**
- Key: "{baseKey}_{randomSuffix}"
- Suffix range: 0-9 (10 shards) or 0-99 (100 shards)
- Reads: Query all shards and merge
- Writes: Distribute evenly

**Why Extract:**
- Solution to common performance problem
- Trade-off: Write scalability vs read complexity
- Applicable to any partitioned system

**Brevity:** ~90 lines (pattern + when to use + example + trade-offs)

---

## 🎯 EXTRACTION PRIORITY MATRIX

### Must Extract (Priority 1 - Core Knowledge) - 18 entries

**From Modeling Guide:**
1. DEC-## Access Pattern-First vs Structure-First Design
2. DEC-## Single Table vs Multiple Tables
3. LESS-## Partition Key Determines Physical Distribution
4. LESS-## Validate with Representative Data Volume
5. AP-## Scanning Instead of Querying
6. AP-## Low-Cardinality Partition Keys
7. AP-## Ignoring Access Patterns During Design
8. WISD-## Design for Your Queries, Not Your Entities
9. PROC-## Nine-Step Data Modeling Process
10. PATT-## Hierarchical Path Encoding

**From Research Paper (NEW):**
11. DEC-## Distributed vs Centralized Admission Control ⭐
12. DEC-## Static Allocation vs Dynamic Bursting ⭐
13. LESS-## Continuous Verification Catches Silent Corruption ⭐
14. LESS-## Gray Failures Require Consensus-Based Detection ⭐
15. LESS-## Upgrade/Downgrade Testing Prevents Rollback Failures ⭐
16. AP-## Tight Coupling Partition Capacity to Partition Size ⭐
17. AP-## Relying on Caches to Hide Scaling Problems ⭐
18. WISD-## Design for Predictability Over Absolute Efficiency ⭐

**Rationale:** These capture fundamental paradigm shifts, critical operational lessons, and common failure modes from production experience.

---

### Should Extract (Priority 2 - Valuable Patterns) - 14 entries

**From Modeling Guide:**
19. LESS-## Sort Keys Enable Hierarchical Queries
20. LESS-## Sparse Indexes Save Storage and Cost
21. LESS-## GSI Overloading Maximizes Index Utility
22. AP-## Normalizing NoSQL Data
23. WISD-## Preliminary Cost Estimation Prevents Late Surprises
24. WISD-## One Table Per Application
25. PROC-## Access Pattern Documentation Template
26. PATT-## Write Sharding for Hot Partitions

**From Research Paper (NEW):**
27. DEC-## Cache-Based vs Cache-Independent Scaling ⭐
28. LESS-## Log Replicas Enable Fast Healing ⭐
29. LESS-## Token Buckets Enable Flexible Rate Limiting ⭐
30. LESS-## Formal Methods Catch Subtle Distributed Bugs ⭐
31. LESS-## Read-Write Deployments Handle Protocol Changes ⭐
32. WISD-## Multi-Tenant Requires Defense-in-Depth Isolation ⭐

**Rationale:** Important optimization techniques, operational best practices, and architectural patterns.

---

### Consider Extracting (Priority 3 - Nice to Have) - 5 entries

**From Modeling Guide:**
33. DEC-## On-Demand vs Provisioned Capacity
34. PATT-## Adjacency List for Relationships (if not already in NM06)

**From Research Paper (NEW):**
35. LESS-## Checksums at Every Layer Prevent Error Propagation ⭐
36. AP-## False Positives in Failure Detection ⭐
37. WISD-## Operational Discipline Enables Safe Evolution ⭐

**Rationale:** Useful but more specific, or potentially already captured elsewhere.

---

## 📊 ENTRIES BY SOURCE

### Source 1: Modeling Guide (20 entries)
- **Focus:** Design patterns, data modeling, access patterns
- **Perspective:** How to design tables and queries
- **Audience:** Application developers, database designers
- **Strength:** Concrete patterns and templates

### Source 2: Research Paper (17 entries) ⭐ NEW
- **Focus:** Operational experience, architectural evolution, failure handling
- **Perspective:** How systems behave at scale over time
- **Audience:** System architects, SREs, distributed systems engineers  
- **Strength:** Real-world lessons from 10 years of production operation

### Combined Value
- **Design + Operations:** Complete picture from conception to production
- **Minimal Overlap:** Complementary perspectives
- **High Genericization:** Both sources contain universal principles

---

## 🔍 DUPLICATE CHECK REQUIREMENTS

Before creating each entry, search existing neural maps for:

### For DEC entries:
- Search: "NoSQL", "access pattern", "partition key", "capacity model"
- Check: NM04 (Decisions)

### For LESS entries:
- Search: "partition", "distribution", "hotspot", "sparse", "index", "validation"
- Check: NM06 (Lessons Learned)

### For AP entries:
- Search: "scan", "query", "cardinality", "normalize", "normalization"
- Check: NM05 (Anti-Patterns)

### For WISD entries:
- Search: "design principle", "cost estimation", "single table"
- Check: NM04 (Wisdom) - if exists, or NM06

### For PROC entries:
- Search: "modeling process", "RACI", "access pattern template"
- Check: NM03 (Operations) or create new PROC category

### For PATT entries:
- Search: "hierarchical", "tree", "path", "adjacency", "sharding"
- Check: NM06 or create new PATT category

---

## 📊 GENERICIZATION GUIDELINES

### What to Strip (AWS/DynamoDB-Specific):
- ❌ "DynamoDB" → ✅ "NoSQL database"
- ❌ "GSI (Global Secondary Index)" → ✅ "Secondary index"
- ❌ "AWS Pricing Calculator" → ✅ "Cost estimation tools"
- ❌ "CloudFormation template" → ✅ "Infrastructure as code"
- ❌ "DynamoDB Local" → ✅ "Local database emulator"

### What to Keep (Generic Concepts):
- ✅ Partition key, sort key concepts
- ✅ Access pattern methodology
- ✅ BEGINS_WITH operator concept (rename to "prefix matching")
- ✅ Path encoding pattern ("Root|Parent|Child")
- ✅ RACI matrix
- ✅ Validation methodology

### Genericization Examples:

**From Modeling Guide:**

**Before (Too Specific):**
> "Use AWS NoSQL Workbench to model your DynamoDB tables with GSIs and LSIs."

**After (Properly Generic):**
> "Use visual data modeling tools to design NoSQL tables with secondary indexes before implementation."

**From Research Paper:**

**Before (Too Specific):**
> "DynamoDB uses Multi-Paxos for replication with log replicas and storage replicas."

**After (Properly Generic):**
> "Consensus-based replication systems can use lightweight log-only replicas alongside full storage replicas for faster recovery."

**Before (Too Specific):**
> "DynamoDB's autoadmin service monitors storage nodes and triggers replica replacement."

**After (Properly Generic):**
> "Distributed databases require automated health monitoring and self-healing mechanisms for replica management."

**Before (Too Specific):**
> "DynamoDB partitions data based on the hash of the partition key to distribute load across servers."

**After (Properly Generic):**
> "Distributed NoSQL databases use hash-based partitioning to distribute data across nodes for parallel processing."

---

## 📏 BREVITY STANDARDS

Each entry should target:
- **Concept explanation:** 30-50 lines
- **Example or pattern:** 20-40 lines
- **Anti-pattern or caution:** 15-25 lines
- **Related topics:** 5-10 lines
- **Keywords:** 4-8 terms
- **Total:** < 150 lines (strict), aim for < 120 lines

### Brevity Techniques:
1. **No background stories** - Start with the principle
2. **One good example** - Not three variations
3. **Bullet points** - Not paragraphs
4. **Tables** - For comparisons
5. **Omit obvious** - Don't explain basics

---

## 🎯 SUCCESS CRITERIA

Extraction is successful when:
- ✅ Each entry is < 150 lines
- ✅ No AWS-specific terminology unless unavoidable
- ✅ Principles apply to 3+ different databases/systems
- ✅ No duplicates created (all searches performed)
- ✅ Entries cite each other for related concepts
- ✅ REF-IDs assigned correctly
- ✅ Keywords enable discovery
- ✅ Examples are minimal but clear

---

## 📦 ESTIMATED OUTPUT

**Total Entries:** 30-37 entries (was 15-18 with single source)  
**Sources:** 2 complementary documents  
**New Categories:** Possibly PROC (Processes) and PATT (Patterns)  
**Total Lines:** ~3,000-3,500 lines across all entries (averaged ~100 lines each)  
**Time to Extract:** 4-6 hours with proper duplicate checking  
**Value:** VERY HIGH - Captures both design principles AND operational wisdom

**Breakdown by Category:**
- DEC (Decisions): 6 entries
- LESS (Lessons): 15 entries (⭐ 9 from operations paper)
- AP (Anti-Patterns): 7 entries (⭐ 3 from operations paper)
- WISD (Wisdom): 7 entries (⭐ 4 from operations paper)
- PROC (Processes): 2 entries
- PATT (Patterns): 3 entries

**Value Proposition:**
- **Design Patterns:** How to model data for NoSQL (Source 1)
- **Operational Wisdom:** How to run NoSQL at scale (Source 2) ⭐ NEW
- **Complementary:** Design-time decisions + Runtime lessons
- **Highly Generic:** Applies to any NoSQL/distributed database

---

## 🚀 NEXT STEPS

**Decision Point:** Two sources with 30-37 potential entries

### Option 1: Extract Priority 1 Only (RECOMMENDED)
- **Scope:** 18 must-have entries (10 from modeling + 8 from operations)
- **Time:** 2-3 hours
- **Value:** Maximum impact entries covering both design and operations
- **Rationale:** Core paradigm shifts and critical lessons

### Option 2: Extract by Source
- **Phase A:** All 20 modeling guide entries (design patterns) - 2.5 hours
- **Phase B:** All 17 operations paper entries (runtime lessons) - 2.5 hours
- **Total:** 5 hours, complete coverage

### Option 3: Extract Everything
- **Scope:** All 37 entries across both sources
- **Time:** 5-6 hours
- **Value:** Comprehensive NoSQL knowledge base
- **Rationale:** Create authoritative reference

### Option 4: Selective Extraction
- **User Choice:** Tell me which specific entries you want
- **Flexible:** Pick and choose by topic/priority

---

**Recommendation:** Start with **Option 1** (Priority 1 entries only)
- Gets highest value content first
- Balanced between design and operations
- Can always add Priority 2/3 later
- Tests the extraction process with 18 diverse entries

---

1. **Confirm extraction scope** - User approves which option
2. **Search for duplicates** - Check each concept against existing NM (CRITICAL)
3. **Create entries in priority order** - Priority 1 first
4. **Cross-reference** - Link related entries as we create them
5. **Update indexes** - Add new REF-IDs to directories
6. **Quality check** - Verify brevity and genericization for each entry

---

**CRITICAL DUPLICATE CHECK TASKS:**
Before creating ANY entry, search existing neural maps for:
- Similar concepts (different words, same idea)
- Existing patterns (might be named differently)
- Related lessons (might contain subset of new lesson)
- Overlapping wisdom (might already be captured)

Use queries like:
- "partition" "distributed" "admission control" "rate limiting"
- "failure detection" "leader election" "gray failure"  
- "cache" "bi-modal" "cold start"
- "verification" "checksum" "corruption"
- "deployment" "upgrade" "rollback"

---

**END OF EXTRACTION PLAN**

**Status:** Ready for execution - TWO SOURCES ANALYZED  
**Sources:** 
- AWS Prescriptive Guidance (Design/Modeling)
- DynamoDB Research Paper (Operations/Architecture)
**Approval Required:** Yes - User should confirm scope (Options 1-4)  
**Estimated Value:** Extremely High - Comprehensive NoSQL knowledge from design through operations

**Key Insights:**
1. **Complementary Sources:** Design guide + operational paper = complete picture
2. **Minimal Overlap:** Each source contributes unique perspective
3. **High Genericization Potential:** ~90% of content applies beyond DynamoDB
4. **Production-Proven:** 10+ years of lessons from massive scale (89M requests/sec)
5. **Universal Lessons:** Apply to any NoSQL, distributed database, or multi-tenant system

**Recommended Next Step:** Approve Option 1 (18 Priority 1 entries) to start with highest-impact content.
