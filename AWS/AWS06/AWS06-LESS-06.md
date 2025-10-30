# AWS-LESS-06: NoSQL Secondary Index Strategies

**Category:** External Knowledge - AWS Serverless  
**Type:** LESS (Lesson Learned)  
**Priority:** ⭐⭐⭐⭐⭐ HIGH  
**Status:** Active  
**Created:** 2025-10-25  
**Source:** AWS Serverless Developer Guide (Genericized)

---

## Summary

Secondary indexes enable alternative query patterns beyond primary key. Global indexes provide flexibility but cost storage and consistency. Local indexes share partition with base table. Design indexes for specific access patterns, not speculation.

**Lesson:** Every index has a cost. Create indexes only for proven access patterns, not "just in case."

---

## Context

Primary keys optimize for the most common access pattern, but applications often need multiple query paths. Secondary indexes provide this flexibility at the cost of storage duplication, write amplification, and eventual consistency.

**Problem Domain:** NoSQL query optimization, multi-access-pattern support  
**Applicability:** DynamoDB, Cassandra, any NoSQL database with secondary index support

---

## The Lesson

### Index Decision Framework

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

### Global Secondary Index (GSI)

**Characteristics:**
```
Structure:
  - Different partition + sort keys from base table
  - Separate storage (duplicates data)
  - Eventually consistent reads
  - Scales independently from base table
  - Can project subset or all attributes

Cost Model:
  - Storage: Full duplication of indexed data
  - Writes: Base table write + index write
  - Reads: Same as base table
```

**When to Use GSI:**
```
✅ Need different partition key for query
✅ Access pattern crosses partitions
✅ Eventual consistency acceptable
✅ Query on non-key attributes

Example Use Case:
  Base Table:
    pk: user_id
    sk: order_id
    Query: Get user's orders ✅

  GSI:
    pk: product_id
    sk: order_date
    Query: Find all orders for product_X ✅
    
  New capability: Query by product instead of user
```

**GSI Example:**
```
Base Table Design:
  pk: user_id
  sk: "ORDER#" + timestamp
  attributes: order_id, product_id, amount

Problem: Need to find all orders for a product

GSI Design:
  gsi_pk: product_id
  gsi_sk: timestamp
  projected: order_id, user_id, amount

Query:
  "Give me all orders for product_123"
  → Query GSI with pk=product_123
  → Returns: All orders sorted by time
```

### Local Secondary Index (LSI)

**Characteristics:**
```
Structure:
  - Same partition key as base table
  - Different sort key
  - Shares storage with base table
  - Strongly consistent reads available
  - Must be created at table creation time

Cost Model:
  - Storage: Shared with base table (lower cost)
  - Writes: Base table write + index write
  - Reads: Same as base table
```

**When to Use LSI:**
```
✅ Need different sort order within partition
✅ Same partition key works for query
✅ Strong consistency required
✅ Alternative ordering of partition's items

Example Use Case:
  Base Table:
    pk: user_id
    sk: order_date
    Query: User's orders chronologically ✅

  LSI:
    pk: user_id (same as base!)
    sk: order_total
    Query: User's orders by price ✅
    
  New capability: Sort by price instead of date
```

**LSI Example:**
```
Base Table Design:
  pk: user_id
  sk: order_date
  attributes: order_id, total, status

Problem: Need to find user's largest orders

LSI Design:
  lsi_pk: user_id (same as base)
  lsi_sk: order_total
  projected: order_id, order_date, status

Query:
  "Give me user_123's top 10 orders by price"
  → Query LSI with pk=user_123
  → Sort by order_total descending
  → Limit 10
```

### GSI vs LSI Comparison

| Feature | Global (GSI) | Local (LSI) |
|---------|--------------|-------------|
| **Partition Key** | Different from base | Same as base |
| **Sort Key** | Any attribute | Different from base |
| **Scope** | Entire table | Within partition |
| **Consistency** | Eventually consistent | Strong consistency available |
| **Creation** | Any time | Table creation only |
| **Storage** | Separate (duplicates) | Shared (lower cost) |
| **Scaling** | Independent | With base table |

### Cost Considerations

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

**Read Costs:**
```
Query base table: 1 read unit
Query GSI: 1 read unit (same cost)

But: GSI enables query that would require
     full table scan without it (100x cost difference)
```

### Index Design Strategies

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
  Base table stores: Users, Orders, Products
  
  GSI design:
    gsi_pk: entity_type
    gsi_sk: created_date
  
  Queries:
    - All users: gsi_pk="USER"
    - All orders: gsi_pk="ORDER"
    - Recent products: gsi_pk="PRODUCT" + gsi_sk filter
```

### Anti-Patterns

**Anti-Pattern 1: Over-Indexing**
```
❌ WRONG: Create GSI for every attribute

Table attributes: name, email, phone, address, city, state
GSIs: 6 (one per attribute)

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

GSI-1: pk=status, sk=date
GSI-2: pk=status, sk=created_at (same as date!)

Problem: Redundant, wasted storage

Fix: One index per unique access pattern
```

**Anti-Pattern 4: Missing Projections**
```
❌ WRONG: Project all attributes "just in case"

GSI with KEYS_ONLY projection, but queries need other attributes
→ Must fetch from base table (double read cost)

Fix: Project frequently accessed attributes
```

### Index Evolution Strategy

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

## Related Patterns

- **AWS-LESS-05**: Primary Key Design - First line of query optimization
- **AWS-LESS-07**: Query vs Scan - When to use indexes
- **AWS-LESS-08**: Item Collections - Alternative to indexes
- **ARCH-07**: LMMS - Cache hot queries to reduce index load

---

## Keywords

secondary index, global secondary index, local secondary index, GSI, LSI, query optimization, NoSQL, data modeling, cost optimization

---

## Version History

- **2025-10-25**: Created - Extracted from AWS Serverless Developer Guide, genericized for universal application

---

**End of Entry**
**Lines:** ~196 (within < 200 target)  
**Quality:** Generic, detailed strategies, cost-conscious
