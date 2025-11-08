# AWS-DynamoDB-Master-Index.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Complete AWS DynamoDB knowledge index  
**Location:** `/sima/platforms/aws/dynamodb/indexes/`

---

## OVERVIEW

This index organizes all AWS DynamoDB knowledge across core concepts, decisions, lessons, and anti-patterns. DynamoDB is Amazon's fully managed NoSQL database service optimized for serverless applications requiring single-digit millisecond latency at any scale.

**Platform:** AWS  
**Service:** DynamoDB  
**Category:** Database / NoSQL  
**Use Cases:** Serverless applications, IoT, gaming, mobile backends, real-time analytics

---

## QUICK NAVIGATION

**Core Concepts:** [→ Jump](#core-concepts)  
**Decisions:** [→ Jump](#decisions)  
**Lessons Learned:** [→ Jump](#lessons-learned)  
**Anti-Patterns:** [→ Jump](#anti-patterns)  
**Related Knowledge:** [→ Jump](#related-knowledge)

---

## CORE CONCEPTS

### AWS-DynamoDB-Core-Concepts.md

**Location:** `/sima/platforms/aws/dynamodb/core/`  
**Version:** 1.0.0  
**Lines:** 393

**Purpose:** Complete DynamoDB fundamentals reference

**Key Topics:**
- 10 core concepts (tables, items, attributes, keys, indexes, consistency, capacity, partitions, streams, transactions)
- Data modeling principles (denormalization, access patterns first, one table design)
- Performance characteristics (sub-10ms latency, unlimited throughput scaling)
- Cost optimization strategies (on-demand vs provisioned, capacity planning)
- Security best practices (IAM, encryption, VPC endpoints)
- DynamoDB Streams (change data capture)
- Global tables (multi-region replication)
- Common anti-patterns

**When to Use:**
- Need comprehensive DynamoDB reference
- Learning DynamoDB fundamentals
- Understanding data modeling principles
- Evaluating DynamoDB for project

**Cross-References:**
- DEC-01: NoSQL choice decision
- LESS-01: Partition key design
- LESS-02: Access pattern first methodology
- AP-01: Scan anti-pattern

---

## DECISIONS

### DEC-01: NoSQL Choice (DynamoDB vs Relational)

**File:** AWS-DynamoDB-DEC-01-NoSQL-Choice.md  
**Location:** `/sima/platforms/aws/dynamodb/decisions/`  
**Version:** 1.0.0  
**Lines:** 399

**Decision:** Choose DynamoDB over relational databases for LEE project

**Context:** Home automation Lambda requiring:
- Simple access patterns (device lookups, event history)
- Predictable query patterns
- Millisecond latency requirements
- Serverless integration
- Unlimited concurrent connections

**Key Rationale:**
1. **Access Pattern Fit:** Simple key-value and time-series queries
2. **Scalability:** Unlimited concurrent Lambda connections vs connection pool limits
3. **Serverless Integration:** Native AWS integration, no connection management
4. **Operational Simplicity:** Fully managed, auto-scaling, no maintenance
5. **Cost Model:** Pay per request vs always-on database

**Impact Metrics:**
- 64% cost savings vs RDS ($210/month → $75/month)
- 56% faster cold starts (800ms vs 1,800ms)
- 95-98% faster latency (2-5ms vs 50-200ms)
- Unlimited concurrency (vs 40-connection pool)

**Alternatives Considered:**
- RDS MySQL/PostgreSQL
- Aurora Serverless
- MongoDB Atlas
- S3 with metadata

**When to Reference:**
- Choosing database for serverless project
- Comparing NoSQL vs relational
- Evaluating DynamoDB benefits
- Understanding cost trade-offs

**Cross-References:**
- Core Concepts: Data modeling principles
- LESS-02: Access pattern first methodology
- AWS Lambda integration patterns

---

## LESSONS LEARNED

### LESS-01: Partition Key Design

**File:** AWS-DynamoDB-LESS-01-Partition-Key-Design.md  
**Location:** `/sima/platforms/aws/dynamodb/lessons/`  
**Version:** 1.0.0  
**Lines:** 396

**Lesson:** Partition key design critical for performance and cost

**Problem:** Timestamp as partition key created hot partitions
- All writes concentrated on current timestamp partition
- 2,450 throttles/hour during peak load
- 500ms average latency (vs 5ms target)
- 41% cost premium due to throttling

**Solution:** Device ID as partition key
- High cardinality (unique per device)
- Uniform distribution across partitions
- Natural access pattern alignment

**Impact Metrics:**
- 10x throughput improvement (zero throttling)
- 100x faster latency (5ms vs 500ms)
- 41% cost reduction ($140/month vs $237/month)

**Design Principles:**
1. **High Cardinality:** Many unique values distribute load
2. **Uniform Distribution:** Avoid hot spots
3. **Avoid Sequential IDs:** Timestamps, auto-increment create hot partitions

**Advanced Techniques:**
- Composite keys (userId#deviceId)
- Calculated suffixes (userId#hash)
- Time-based sharding (deviceId#YYYY-MM)

**Anti-Patterns:**
- Status field as partition key (low cardinality)
- Timestamp as partition key (sequential writes)
- Composite with low-cardinality first element

**When to Reference:**
- Designing DynamoDB schema
- Experiencing throttling issues
- High write throughput requirements
- Performance optimization

**Cross-References:**
- Core Concepts: Partitions and hot keys
- LESS-02: Access pattern first
- AP-01: Using Scan

---

### LESS-02: Access Pattern First Methodology

**File:** AWS-DynamoDB-LESS-02-Access-Pattern-First.md  
**Location:** `/sima/platforms/aws/dynamodb/lessons/`  
**Version:** 1.0.0  
**Lines:** 400

**Lesson:** Design schema from access patterns, not entities

**Problem:** Entity-first design (normalized tables) failed
- Required Scan operations (read entire table)
- 5-11 operations per query
- 2-3 second response times
- $180/month query costs
- 3 tables + 6 GSIs complexity

**Solution:** Access-pattern-first methodology
1. **Document all queries first**
2. **Design keys to support queries**
3. **Use overloaded sort keys**
4. **Single table design**
5. **Denormalize strategically**

**Result:** Overloaded sort key pattern
- Single table + 1 GSI
- 1 operation per query (vs 5-11)
- 200ms response time (vs 2-3s)
- $35/month costs (vs $180)

**Impact Metrics:**
- 91% fewer operations
- 92% faster queries
- 81% cost reduction
- Simplified architecture

**Advanced Patterns:**
- Adjacency list pattern (relationships)
- Inverted index pattern (alternate lookups)
- Time series pattern (TTL expiration)

**Anti-Patterns:**
- Entity-first design (relational thinking)
- Excessive normalization
- GSI proliferation (6+ indexes)

**When to Reference:**
- Starting DynamoDB schema design
- Migrating from relational database
- Query performance issues
- Cost optimization

**Cross-References:**
- Core Concepts: Data modeling
- DEC-01: NoSQL choice rationale
- LESS-01: Partition key design

---

## ANTI-PATTERNS

### AP-01: Using Scan Anti-Pattern

**File:** AWS-DynamoDB-AP-01-Using-Scan.md  
**Location:** `/sima/platforms/aws/dynamodb/anti-patterns/`  
**Version:** 1.0.0  
**Lines:** 398

**Anti-Pattern:** Using Scan operations for queries

**Description:** Scan reads entire table sequentially, examining every item regardless of what you need. Never use Scan for production queries.

**Why Wrong:**
1. **Reads Entire Table:** Scans all items even if you need 1
2. **Linear Degradation:** Performance degrades as table grows
3. **Pagination Doesn't Help:** Still reads everything
4. **Parallel Scan Costs More:** Uses more RCUs, not faster for small results

**Production Example (LEE Project):**
```python
# WRONG: Scan to find user's devices
response = table.scan(
    FilterExpression=Attr('userId').eq(user_id)
)
# Result: 15-30 seconds, $2,400/month, timeouts
```

**Impact Metrics:**
- 15-30 second query times (vs 200ms target)
- $2,400/month costs (vs $45 with Query)
- Frequent Lambda timeouts
- Poor user experience

**When Scan is Acceptable:**
1. **Small Tables:** <100 items, rarely queried
2. **Analytics/Reports:** Batch processing, not user-facing
3. **Data Migration:** One-time operations
4. **Administrative Tasks:** Occasional full table reads

**Alternatives:**
1. **Query with Proper Keys:** Design schema for Query operations
2. **GSI:** Create index for alternate access patterns
3. **FilterExpression on Query:** Filter after Query (more efficient than Scan)
4. **DynamoDB Streams + Aggregation:** Pre-aggregate data

**Detection:**
- CloudWatch metrics: High ConsumedReadCapacityUnits
- Code audit: Search for `table.scan()`
- Performance monitoring: Slow query times

**Refactoring Guide:**
1. Identify all Scan operations
2. Analyze access patterns
3. Design proper partition/sort keys OR GSI
4. Replace Scan with Query
5. Test performance improvement

**When to Reference:**
- Code review checklist
- Performance troubleshooting
- Cost optimization
- Schema design validation

**Cross-References:**
- Core Concepts: Query vs Scan
- LESS-01: Partition key design
- LESS-02: Access pattern first

---

## RELATED KNOWLEDGE

### Platform Integration

**AWS Lambda Integration:**
- `/sima/platforms/aws/lambda/`
- Serverless database access patterns
- Connection management (DynamoDB has none!)
- IAM roles and permissions
- VPC endpoint configuration

**API Gateway Integration:**
- `/sima/platforms/aws/api-gateway/`
- Direct DynamoDB integration (proxy)
- Request/response transformation
- Error handling patterns

### Language-Specific Patterns

**Python with DynamoDB:**
- `/sima/languages/python/`
- boto3 client patterns
- Batch operations
- Error handling
- Retry strategies

### Project Implementations

**LEE Project:**
- `/sima/projects/lee/`
- Real-world DynamoDB usage
- Device event storage
- Time-series data
- Performance optimizations

---

## FILE ORGANIZATION

```
/sima/platforms/aws/dynamodb/
├── core/
│   └── AWS-DynamoDB-Core-Concepts.md ✅
├── decisions/
│   └── AWS-DynamoDB-DEC-01-NoSQL-Choice.md ✅
├── lessons/
│   ├── AWS-DynamoDB-LESS-01-Partition-Key-Design.md ✅
│   └── AWS-DynamoDB-LESS-02-Access-Pattern-First.md ✅
├── anti-patterns/
│   └── AWS-DynamoDB-AP-01-Using-Scan.md ✅
└── indexes/
    └── AWS-DynamoDB-Master-Index.md ✅ (this file)
```

**Total Files:** 6 (5 content + 1 index)  
**Status:** Foundation complete  
**Coverage:** Core concepts, critical patterns, major anti-patterns

---

## LEGACY FILES (Pre-Migration)

**Note:** These files exist in legacy structure and contain additional DynamoDB topics:

1. **AWS-DynamoDB-PrimaryKeyDesign_AWS-LESS-05.md**
   - Additional partition key strategies
   - Sort key design patterns
   
2. **AWS-DynamoDB-SecondaryIndexes_AWS-LESS-06.md**
   - GSI design best practices
   - LSI vs GSI comparison
   - Index projection strategies

3. **AWS-DynamoDB-QueryVsScan_AWS-LESS-07.md**
   - Detailed Query vs Scan comparison
   - Performance benchmarks
   - Cost analysis

4. **AWS-DynamoDB-ItemCollections_AWS-LESS-08.md**
   - Item collection strategies
   - Partition size limits
   - Collection best practices

**Migration Status:** Legacy files provide additional depth on topics referenced in foundation files.

---

## QUICK REFERENCE GUIDE

### When to Use Each File

**Learning DynamoDB:**
1. Start: Core Concepts (comprehensive reference)
2. Then: LESS-02 (access pattern methodology)
3. Then: LESS-01 (partition key design)
4. Finally: AP-01 (avoid Scan)

**Making Decision:**
1. DEC-01: NoSQL vs relational choice
2. Core Concepts: Data modeling section
3. LESS-02: Schema design from queries

**Troubleshooting Performance:**
1. LESS-01: Partition key causing hot spots?
2. AP-01: Using Scan operations?
3. Core Concepts: Capacity modes

**Cost Optimization:**
1. AP-01: Eliminate Scan operations
2. LESS-01: Proper partition key reduces throttling
3. DEC-01: On-demand vs provisioned analysis

---

## KEY METRICS SUMMARY

**From LEE Project Real-World Usage:**

**Performance Improvements:**
- Latency: 2-5ms (95-98% faster than RDS)
- Cold start: 800ms (56% faster than RDS)
- Throughput: Unlimited concurrent connections
- Query time: 200ms (92% faster with proper design)

**Cost Improvements:**
- Overall: $75/month (64% savings vs RDS)
- Partition key fix: 41% reduction
- Access pattern redesign: 81% reduction
- Scan elimination: 98% reduction

**Reliability Improvements:**
- Zero throttling (vs 2,450/hour with bad design)
- Zero Lambda timeouts (vs frequent with Scan)
- 100x scalability increase

---

## SEARCH KEYWORDS

**Core Topics:**
DynamoDB, NoSQL, serverless database, AWS database, key-value store, document database, DynamoDB tables, items, attributes, partition keys, sort keys, GSI, LSI, secondary indexes, capacity modes, on-demand, provisioned, auto-scaling, DynamoDB Streams, global tables, transactions, consistent reads, eventual consistency

**Access Patterns:**
Query, Scan, GetItem, PutItem, UpdateItem, DeleteItem, BatchGetItem, BatchWriteItem, TransactWriteItems, TransactGetItems, access patterns, data modeling, schema design, overloaded sort keys, single table design, adjacency list, inverted index, time series

**Performance:**
Hot partitions, throttling, partition key design, sort key design, read capacity units, write capacity units, RCU, WCU, latency, throughput, performance optimization, capacity planning, burst capacity

**Cost:**
Cost optimization, pricing, on-demand pricing, provisioned pricing, RCU costs, WCU costs, storage costs, backup costs, global table costs

**Anti-Patterns:**
Scan anti-pattern, entity-first design, over-normalization, GSI proliferation, sequential partition keys, timestamp partition keys, hot partitions, large items

**Use Cases:**
Serverless applications, IoT data, gaming leaderboards, mobile backends, session storage, user profiles, device events, time series data, real-time analytics

---

## VERSION HISTORY

**v1.0.0 (2025-11-08):**
- Initial master index creation
- 5 foundation files indexed
- Complete navigation structure
- Cross-reference matrix
- Legacy file references
- Search keywords
- Quick reference guide

---

**END OF INDEX**

**Purpose:** Navigate all AWS DynamoDB knowledge  
**Files Indexed:** 6 total (5 content + 1 index)  
**Coverage:** Foundation complete with core concepts, critical patterns, major anti-patterns  
**Status:** Production-ready reference
