# AWS-DynamoDB-Master-Index.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Category:** AWS Platform > DynamoDB  
**Purpose:** Complete index of DynamoDB platform knowledge

---

## OVERVIEW

This index provides complete navigation of DynamoDB platform knowledge for the LEE project and general AWS serverless applications.

**Total Files:** 13 (1 core + 2 decisions + 5 lessons + 3 anti-patterns + 2 legacy)

**Coverage:**
- âœ… Core concepts and fundamentals
- âœ… Design patterns and best practices
- âœ… Operational decisions
- âœ… Common anti-patterns
- âœ… Production-validated lessons
- âœ… Cost optimization strategies
- âœ… Performance tuning

---

## QUICK START

### New to DynamoDB?

**Start Here:**
1. [Core Concepts](#core-concepts) - Understand fundamentals
2. [Key Lessons](#key-lessons) - Learn critical patterns
3. [Decisions](#decisions) - Understand trade-offs
4. [Anti-Patterns](#anti-patterns) - Avoid common mistakes

### Experienced with DynamoDB?

**Jump To:**
- [Design Patterns](#design-patterns) - Access pattern first, partition key design
- [Performance](#performance-optimization) - Batch operations, conditional writes
- [Cost Optimization](#cost-optimization) - Capacity mode, TTL strategies
- [Troubleshooting](#troubleshooting) - Common issues and solutions

---

## CORE CONCEPTS

### AWS-DynamoDB-Core-Concepts.md

**Purpose:** Complete reference for DynamoDB fundamentals

**Topics Covered:**
- Tables, items, attributes
- Primary keys (partition + sort)
- Data types and structures
- Global Secondary Indexes (GSIs)
- Local Secondary Indexes (LSIs)
- Consistency models (strong vs eventual)
- Capacity modes (provisioned vs on-demand)
- Read/Write capacity units
- Partitions and distribution
- DynamoDB Streams
- Transactions
- Performance characteristics
- Cost model
- Security and encryption

**When to Use:**
- Learning DynamoDB basics
- Understanding data modeling
- Reference for concepts
- Team training

**File:** `/sima/platforms/aws/dynamodb/core/AWS-DynamoDB-Core-Concepts.md`

**Lines:** 393  
**Status:** âœ… Complete

---

## DECISIONS

### DEC-01: NoSQL Choice

**Decision:** Use DynamoDB instead of relational database for LEE project

**Key Factors:**
- Access pattern fit (key-value, document-based)
- Scalability (unlimited, automatic)
- Serverless integration (zero administration)
- Cost model (pay-per-request)
- Performance (2-5ms queries)

**Impact:**
- 64% cost savings vs RDS
- 56% faster cold starts
- 95-98% faster queries
- Unlimited concurrency
- Zero database administration

**File:** `/sima/platforms/aws/dynamodb/decisions/AWS-DynamoDB-DEC-01-NoSQL-Choice.md`

**Lines:** 399  
**Status:** âœ… Complete

---

### DEC-02: Capacity Mode

**Decision:** Use on-demand capacity mode for LEE project

**Key Factors:**
- Unpredictable traffic (5x variance)
- Low-medium volume (< 200M requests/month)
- Serverless architecture fit
- Operational simplicity
- Cost effectiveness at current scale

**Impact:**
- 99% cost savings vs provisioned ($0.30 vs $54/month)
- Zero throttling events
- Instant scalability
- Zero capacity management

**File:** `/sima/platforms/aws/dynamodb/decisions/AWS-DynamoDB-DEC-02-Capacity-Mode.md`

**Lines:** 394  
**Status:** âœ… Complete

---

### DEC-03: Data Protection

**Decision:** Use point-in-time recovery instead of manual backups

**Key Factors:**
- Continuous protection (no gaps)
- Second-level granularity
- Faster restore (35 min vs 4 hours)
- Lower cost ($0.10 vs $0.35/month)
- Zero operational overhead

**Impact:**
- 35-minute recovery time (vs 4 hours)
- Zero data loss in actual incident
- Lower cost (PITR stores single copy vs multiple backups)
- Automatic (no maintenance)

**File:** `/sima/platforms/aws/dynamodb/decisions/AWS-DynamoDB-DEC-03-Data-Protection.md`

**Lines:** 378  
**Status:** âœ… Complete

---

## KEY LESSONS

### LESS-01: Partition Key Design

**Lesson:** Choose high-cardinality partition keys for uniform distribution

**Problem:** timestamp as partition key created hot partitions, causing throttling

**Solution:** deviceId as partition key distributed load evenly

**Impact:**
- 10x throughput improvement
- 100x faster queries (5ms vs 500ms)
- Zero throttling (vs 2,450/hour)
- 41% cost reduction

**Principles:**
- High cardinality (many unique values)
- Uniform distribution (avoid hot partitions)
- Avoid sequential IDs as sole partition key

**File:** `/sima/platforms/aws/dynamodb/lessons/AWS-DynamoDB-LESS-01-Partition-Key-Design.md`

**Lines:** 396  
**Status:** âœ… Complete

---

### LESS-02: Access Pattern First

**Lesson:** Design schema based on access patterns, not entities

**Problem:** Entity-first design required scans, was slow and expensive

**Solution:** Access-pattern-first with overloaded sort keys

**Impact:**
- 91% fewer operations (1 vs 5-11)
- 92% faster (200ms vs 2-3s)
- 81% cost reduction
- Simplified architecture (1 table vs 3)

**Methodology:**
1. Document ALL access patterns first
2. Design keys to support patterns
3. Use composite keys for multiple patterns
4. Denormalize for query efficiency

**File:** `/sima/platforms/aws/dynamodb/lessons/AWS-DynamoDB-LESS-02-Access-Pattern-First.md`

**Lines:** 400  
**Status:** âœ… Complete

---

### LESS-03: Conditional Writes

**Lesson:** Use conditional writes to prevent data loss in concurrent scenarios

**Problem:** Concurrent updates overwrote each other, causing data corruption

**Solution:** Version-based optimistic locking with conditional expressions

**Impact:**
- Zero data loss from concurrency
- 99.2% retry success rate
- Average 0.3 retries per update
- <5ms latency overhead

**Pattern:**
```python
ConditionExpression='version = :expected'
UpdateExpression='SET value = :new, version = :next'
```

**File:** `/sima/platforms/aws/dynamodb/lessons/AWS-DynamoDB-LESS-03-Conditional-Writes.md`

**Lines:** 396  
**Status:** âœ… Complete

---

### LESS-04: TTL Strategies

**Lesson:** Use time-to-live for automatic data expiration

**Problem:** Temporal data accumulated indefinitely, increasing costs

**Solution:** Enable TTL for automatic deletion after expiration

**Impact:**
- 97% storage reduction (1.5 GB → 45 MB)
- Zero manual cleanup needed
- Free deletion (no WCU cost)
- Stable storage costs

**Use Cases:**
- Session tokens (24-hour expiration)
- Event logs (7-day retention)
- Cache entries (1-hour expiration)
- Temporary data

**File:** `/sima/platforms/aws/dynamodb/lessons/AWS-DynamoDB-LESS-04-TTL-Strategies.md`

**Lines:** 399  
**Status:** âœ… Complete

---

### LESS-05: Batch Operations

**Lesson:** Use batch operations for multi-item efficiency

**Problem:** Individual operations for 50 devices took 2-3 seconds

**Solution:** BatchGetItem reduced to 200-300ms (10x faster)

**Impact:**
- 10x latency improvement
- 50% RCU savings (25 vs 50 RCUs)
- 50x fewer API calls
- 50% cost reduction

**Operations:**
- BatchGetItem: Read up to 100 items
- BatchWriteItem: Write/delete up to 25 items
- TransactWriteItems: Atomic multi-item updates

**File:** `/sima/platforms/aws/dynamodb/lessons/AWS-DynamoDB-LESS-05-Batch-Operations.md`

**Lines:** 398  
**Status:** âœ… Complete

---

## ANTI-PATTERNS

### AP-01: Using Scan

**Anti-Pattern:** Using Scan operations for queries instead of Query with keys

**Problem:** Scan reads entire table, degrades with scale

**Impact (LEE Project):**
- 98% slower (15-30s vs 200ms)
- 98% more expensive ($2,400 vs $45/month)
- 100x worse scalability
- Frequent timeouts

**Right Approach:**
- Query with partition/sort keys
- Use GSI for alternate access patterns
- FilterExpression on Query (not Scan)
- Scan only for admin/analytics (< 10x/day)

**File:** `/sima/platforms/aws/dynamodb/anti-patterns/AWS-DynamoDB-AP-01-Using-Scan.md`

**Lines:** 398  
**Status:** âœ… Complete

---

### AP-02: Over-Indexing

**Anti-Pattern:** Creating GSI for every query pattern without considering alternatives

**Problem:** GSIs multiply storage and write costs

**Impact:**
- 9x storage cost (base + 8 GSIs)
- 9x write cost (update all indexes)
- 3-4x write latency
- Maintenance complexity

**Right Approach:**
- Use composite keys for multiple patterns
- FilterExpression for infrequent queries
- Accept Scan for rare admin queries
- Create GSI only for high-frequency queries

**LEE Project Results:**
- Reduced 6 GSIs → 1 GSI
- 65% cost reduction
- Simpler architecture

**File:** `/sima/platforms/aws/dynamodb/anti-patterns/AWS-DynamoDB-AP-02-Over-Indexing.md`

**Lines:** 390  
**Status:** âœ… Complete

---

### AP-03: Large Items

**Anti-Pattern:** Storing items approaching 400 KB limit

**Problem:** High costs, poor performance, risk of hitting limit

**Impact:**
- 88x higher RCU/WCU costs
- 30-50x slower queries
- Wasted capacity on rarely-accessed data
- Emergency refactoring when limit hit

**Right Approach:**
- Keep items < 10 KB ideal
- Store large data in S3 (reference in DynamoDB)
- Split items across multiple records
- Use ProjectionExpression for selective reads
- Compress data if needed

**LEE Project Results:**
- 92% cost reduction (350 KB → 1 KB + S3)
- 30x faster queries
- Zero size limit concerns

**File:** `/sima/platforms/aws/dynamodb/anti-patterns/AWS-DynamoDB-AP-03-Large-Items.md`

**Lines:** 380  
**Status:** âœ… Complete

---

## LEGACY FILES

### Additional Resources

**Note:** The following legacy files exist and can be referenced for additional detail on specific topics. They complement (do not replace) the comprehensive files above.

#### AWS-DynamoDB-PrimaryKeyDesign_AWS-LESS-05.md
- Topic: Primary key design strategies
- Complements: LESS-01 (Partition Key Design)
- Location: `/sima/entries/platforms/aws/dynamodb/`

#### AWS-DynamoDB-SecondaryIndexes_AWS-LESS-06.md
- Topic: GSI and LSI design patterns
- Complements: AP-02 (Over-Indexing), Core Concepts
- Location: `/sima/entries/platforms/aws/dynamodb/`

#### AWS-DynamoDB-QueryVsScan_AWS-LESS-07.md
- Topic: Query vs Scan performance comparison
- Complements: AP-01 (Using Scan)
- Location: `/sima/entries/platforms/aws/dynamodb/`

#### AWS-DynamoDB-ItemCollections_AWS-LESS-08.md
- Topic: Item collection strategies
- Complements: LESS-02 (Access Pattern First)
- Location: `/sima/entries/platforms/aws/dynamodb/`

---

## CROSS-REFERENCES

### By Topic

**Data Modeling:**
- Core Concepts → Foundation
- LESS-01 → Partition key design
- LESS-02 → Access pattern methodology
- AP-02 → GSI strategy

**Performance:**
- LESS-01 → Partition key for distribution
- LESS-05 → Batch operations for efficiency
- AP-01 → Avoid Scan for speed
- AP-03 → Keep items small

**Cost Optimization:**
- DEC-02 → Capacity mode selection
- LESS-04 → TTL for storage reduction
- LESS-05 → Batch operations for RCU savings
- AP-02 → Minimize GSIs
- AP-03 → Avoid large items

**Reliability:**
- LESS-03 → Conditional writes for consistency
- DEC-03 → Point-in-time recovery for protection

**Operational:**
- DEC-02 → On-demand for simplicity
- DEC-03 → PITR for automation
- LESS-04 → TTL for automatic cleanup

---

## DESIGN PATTERNS

### Pattern 1: Access Pattern First
**File:** LESS-02  
**Use:** Data modeling methodology  
**Impact:** 92% faster, 81% cost reduction

### Pattern 2: Overloaded Sort Key
**File:** LESS-02, AP-02  
**Use:** Multiple query patterns with minimal GSIs  
**Impact:** 78% cost reduction (2x vs 9x storage)

### Pattern 3: Composite Keys
**File:** LESS-01, LESS-02  
**Use:** Hierarchical queries, multiple patterns  
**Impact:** Zero additional GSIs needed

### Pattern 4: Optimistic Locking
**File:** LESS-03  
**Use:** Concurrent update safety  
**Impact:** 100% data loss prevention

### Pattern 5: S3 Hybrid
**File:** AP-03  
**Use:** Large data storage  
**Impact:** 92% cost reduction, no size limits

---

## PERFORMANCE OPTIMIZATION

### Query Performance
- **Partition Key Design** (LESS-01): 100x faster queries
- **Avoid Scan** (AP-01): 98% latency reduction
- **Batch Operations** (LESS-05): 10x latency improvement
- **Small Items** (AP-03): 30x faster queries

### Write Performance
- **Minimal GSIs** (AP-02): 78% write cost reduction
- **Batch Writes** (LESS-05): 50x throughput increase
- **Conditional Writes** (LESS-03): <5ms overhead

### Cost Performance
- **On-Demand Mode** (DEC-02): 99% savings at low traffic
- **TTL Cleanup** (LESS-04): 97% storage reduction
- **Batch Operations** (LESS-05): 50% RCU savings
- **S3 Hybrid** (AP-03): 92% cost reduction

---

## TROUBLESHOOTING

### Throttling
**Symptoms:** ConditionalCheckFailedException, ProvisionedThroughputExceededException  
**Causes:** Hot partitions, insufficient capacity  
**Solutions:** LESS-01 (better partition keys), DEC-02 (on-demand mode)

### Slow Queries
**Symptoms:** Query latency > 100ms  
**Causes:** Scan usage, large items, no indexes  
**Solutions:** AP-01 (avoid scan), AP-03 (smaller items), LESS-02 (proper indexing)

### High Costs
**Symptoms:** Unexpected DynamoDB bills  
**Causes:** Too many GSIs, large items, scan operations  
**Solutions:** AP-02 (reduce GSIs), AP-03 (S3 hybrid), DEC-02 (on-demand mode)

### Data Loss
**Symptoms:** Concurrent updates overwriting each other  
**Causes:** No optimistic locking  
**Solutions:** LESS-03 (conditional writes)

### Storage Growth
**Symptoms:** Unbounded table size  
**Causes:** No data expiration  
**Solutions:** LESS-04 (TTL strategies)

---

## PRODUCTION CHECKLIST

### Schema Design
- [ ] Partition key has high cardinality (LESS-01)
- [ ] Access patterns documented (LESS-02)
- [ ] Sort key supports multiple patterns (LESS-02)
- [ ] GSIs only for frequent queries (AP-02)
- [ ] Items < 10 KB ideal (AP-03)

### Capacity Planning
- [ ] Capacity mode chosen (DEC-02)
- [ ] On-demand if unpredictable traffic
- [ ] Provisioned if consistent high volume

### Data Protection
- [ ] Point-in-time recovery enabled (DEC-03)
- [ ] Backup strategy defined
- [ ] Recovery procedures tested

### Operations
- [ ] TTL enabled for temporal data (LESS-04)
- [ ] Batch operations used where applicable (LESS-05)
- [ ] Conditional writes for concurrent access (LESS-03)
- [ ] Monitoring and alerts configured

---

## SUMMARY STATISTICS

### Knowledge Base
- **Total Files:** 13 (8 new + 4 legacy + 1 index)
- **Core Concepts:** 1 comprehensive file
- **Decisions:** 3 production-validated
- **Lessons:** 5 LEE project learnings
- **Anti-Patterns:** 3 common mistakes
- **Legacy Files:** 4 additional references

### Production Impact (LEE Project)
- **Cost Reduction:** 64-99% across various optimizations
- **Performance:** 10-100x improvements
- **Reliability:** Zero data loss incidents
- **Scalability:** Unlimited (on-demand + proper keys)

### Coverage
- âœ… Data modeling methodology
- âœ… Performance optimization
- âœ… Cost optimization
- âœ… Operational best practices
- âœ… Common anti-patterns
- âœ… Production-validated patterns

---

**END OF INDEX**

**Version:** 1.0.0  
**Last Updated:** 2025-11-08  
**Maintained By:** LEE Project Team  
**Status:** Complete and production-validated
