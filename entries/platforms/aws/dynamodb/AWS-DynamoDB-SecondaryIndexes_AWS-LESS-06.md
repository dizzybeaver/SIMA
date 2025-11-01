# AWS-DynamoDB-SecondaryIndexes_AWS-LESS-06.md

**Version:** 1.0.0  
**Date:** 2025-11-01  
**Purpose:** Secondary index strategies for NoSQL databases

**REF-ID:** AWS-LESS-06  
**Category:** AWS DynamoDB  
**Type:** LESS (Lesson Learned)  
**Priority:** 🔴 High  
**Status:** Active  
**Source:** AWS Serverless Developer Guide (Genericized)

---

## Summary

Secondary indexes enable alternative query patterns beyond primary key. Global indexes provide flexibility but cost storage and consistency. Local indexes share partition with base table. Design indexes for specific access patterns, not speculation.

**Core Principle:** Every index has a cost. Create indexes only for proven access patterns, not "just in case."

---

## Index Decision Framework

**When to Create Index:**
```
✅ Proven access pattern exists (not speculation)
✅ Query performance unacceptable without index
✅ Storage/write cost justified by query frequency
✅ Access pattern cannot be solved with better key design

❌ "Just in case" indexes (waste resources)
❌ Rarely used queries (optimize table scan instead)
❌ Queries that can use primary key with redesign
```

---

## Global Secondary Index (GSI)

**Characteristics:**
- Different partition + sort keys from base table
- Separate storage (duplicates data)
- Eventually consistent reads
- Scales independently from base table
- Can project subset or all attributes

**Cost Model:**
- Storage: Full duplication of indexed data
- Writes: Base table write + index write
- Reads: Same as base table

**When to Use GSI:**
```
✅ Need different partition key for query
✅ Access pattern crosses partitions
✅ Eventual consistency acceptable
✅ Query on non-key attributes

Example:
  Base Table:
    pk: user_id, sk: order_id
    Query: Get user's orders ✅

  GSI:
    pk: product_id, sk: order_date
    Query: Find all orders for product_X ✅
```

---

## Local Secondary Index (LSI)

**Characteristics:**
- Same partition key as base table
- Different sort key
- Shares storage with base table
- Strongly consistent reads available
- Must be created at table creation time

**Cost Model:**
- Storage: Shared with base table (lower cost)
- Writes: Base table write + index write
- Reads: Same as base table

**When to Use LSI:**
```
✅ Need different sort order within partition
✅ Same partition key works for query
✅ Strong consistency required
✅ Alternative ordering of partition's items

Example:
  Base Table:
    pk: user_id, sk: order_date
    Query: User's orders chronologically ✅

  LSI:
    pk: user_id (same!), sk: order_total
    Query: User's orders by price ✅
```

---

## GSI vs LSI Comparison

| Feature | Global (GSI) | Local (LSI) |
|---------|--------------|-------------|
| **Partition Key** | Different from base | Same as base |
| **Sort Key** | Any attribute | Different from base |
| **Scope** | Entire table | Within partition |
| **Consistency** | Eventually consistent | Strong consistency available |
| **Creation** | Any time | Table creation only |
| **Storage** | Separate (duplicates) | Shared (lower cost) |
| **Scaling** | Independent | With base table |

---

## Cost Considerations

**Storage Costs:**
```
Base Table: 10 GB
GSI 1: 10 GB (full duplication)
GSI 2: 10 GB (full duplication)
Total: 30 GB (3x base table size)

Each GSI doubles your storage cost!
```

**Write Amplification:**
```
One item write = N index writes
  1 base table write
  + 1 GSI-1 write
  + 1 GSI-2 write
  + 1 GSI-3 write
  = 4 total writes

Cost: 4x per write operation
```

---

## Index Design Strategies

**Strategy 1: Sparse Indexes**
```
Concept: Only index items with specific attribute

Example:
  Base: All orders (1M items)
  GSI: Only premium orders (10K items with premium=true)
  
Benefit:
  - 99% smaller index
  - Lower storage cost
  - Faster queries on premium orders
```

**Strategy 2: Composite Sort Keys**
```
Concept: Combine multiple attributes in sort key

Example:
  gsi_sk: status#date
  Values:
    "PENDING#2025-01-15"
    "SHIPPED#2025-01-14"
    "DELIVERED#2025-01-13"

Query Options:
  - All pending: sk begins_with "PENDING#"
  - Pending + date range: sk between "PENDING#2025-01-01" and "PENDING#2025-01-31"
```

**Strategy 3: Overloaded GSI**
```
Concept: One GSI serves multiple access patterns

Example:
  GSI design:
    gsi_pk: entity_type
    gsi_sk: created_date
  
  Queries:
    - All users: gsi_pk="USER"
    - All orders: gsi_pk="ORDER"
    - Recent products: gsi_pk="PRODUCT" + gsi_sk filter
```

---

## Anti-Patterns

**Anti-Pattern 1: Over-Indexing**
```
❌ WRONG: Create GSI for every attribute

Problem:
  - 7x storage cost (6 GSIs + base)
  - 7x write cost
  - Most indexes rarely used

Fix: Create indexes only for proven queries
```

**Anti-Pattern 2: Speculative Indexes**
```
❌ WRONG: "We might need to query by X someday"

Result:
  - Paying for unused index
  - Write amplification
  - Maintenance burden

Fix: Add index when query pattern proven
```

**Anti-Pattern 3: Duplicate Information**
```
❌ WRONG: Multiple GSIs with same query capability

Problem: Redundant, wasted storage

Fix: One index per unique access pattern
```

---

## Index Evolution Strategy

**Phase 1: Minimal Indexes (Launch)**
```
Base table only
Monitor query patterns
Identify bottlenecks
```

**Phase 2: Targeted Indexes (Growth)**
```
Add GSI for proven slow queries
Monitor usage and cost
Remove unused indexes
```

**Phase 3: Optimization (Maturity)**
```
Consolidate overlapping indexes
Use sparse indexes for efficiency
Balance cost vs performance
```

---

## Why This Matters

**Performance:**
- Indexes enable O(1) queries vs O(n) scans
- 10-100x faster queries
- Reduced latency

**Cost:**
- Each GSI = 2x cost (storage + writes)
- Unused indexes waste money
- Can double or triple total cost

**Scalability:**
- Indexes scale with base table
- Write amplification impacts throughput
- Hot index partitions cause throttling

---

## When to Apply

**Design Time:**
- ✅ After identifying all access patterns
- ✅ During capacity planning
- ✅ When primary key cannot serve query

**Problem Indicators:**
- ❌ Queries requiring full table scans
- ❌ Unable to filter results efficiently
- ❌ Query latency > 100ms
- ❌ Need to query on non-key attributes

**Maintenance:**
- 📊 Monitor index usage monthly
- 🗑️ Remove indexes with < 1% query usage
- 💰 Audit storage costs quarterly

---

## Cross-References

**AWS Patterns:**
- AWS-DynamoDB-PrimaryKeyDesign_AWS-LESS-05.md - First line of query optimization
- AWS-DynamoDB-QueryVsScan_AWS-LESS-07.md - When to use indexes
- AWS-DynamoDB-ItemCollections_AWS-LESS-08.md - Alternative to indexes

**Project Maps:**
- /sima/entries/core/ARCH-LMMS_Lambda Memory Management System.md - Cache hot queries

---

## Keywords

secondary index, global secondary index, local secondary index, GSI, LSI, query optimization, NoSQL, data modeling, cost optimization

---

**Location:** `/sima/aws/dynamodb/`  
**Total Lines:** 298 (≤400 for SIMAv4)  
**SIMAv4 Compliant:** ✅
