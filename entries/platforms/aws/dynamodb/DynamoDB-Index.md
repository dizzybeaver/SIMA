# DynamoDB-Index.md

**Version:** 4.0.0  
**Date:** 2025-11-01  
**Purpose:** Category index for DynamoDB/NoSQL patterns

**Category:** AWS DynamoDB  
**Patterns:** 4 (AWS-LESS-05, 06, 07, 08)  
**Priority:** Critical for NoSQL data modeling  
**Location:** `/sima/aws/dynamodb/`

---

## Overview

DynamoDB and NoSQL data modeling patterns covering primary key design, secondary indexes, query optimization, and relationship modeling. These patterns form a complete methodology for NoSQL database design, from initial key selection through advanced query optimization.

**Read in Order:** 05 → 07 → 06 → 08 (Primary keys → Queries → Indexes → Collections)

---

## Patterns in This Category

### AWS-LESS-05: Primary Key Design Patterns

**File:** `AWS-DynamoDB-PrimaryKeyDesign_AWS-LESS-05.md`  
**Priority:** 🟠 High  
**Status:** Active

**Summary:** NoSQL databases use partition keys to distribute data and sort keys to organize related items. Well-designed keys enable efficient queries and prevent hot partitions. Poor key design causes performance bottlenecks and high costs.

**Core Principle:** Key design is a modeling decision, not an afterthought. Choose keys based on access patterns, not data structure.

**Use When:**
- Designing new tables
- Modeling data relationships
- Experiencing hot partitions
- Query performance issues

**Key Design Criteria:**
- **Partition Key:** High cardinality, even distribution, predictable access
- **Sort Key:** Supports range queries, hierarchical structure, prefix matching

**Common Patterns:**
- Simple Key: pk only (direct lookups)
- Composite Key: pk + sk (chronological queries)
- Hierarchical Sort: "type::category::id" (prefix matching)
- Generic Partition: pk = entity_id, sk = "TYPE#id" (multi-entity tables)

**Anti-Patterns:**
- ❌ Single hot partition (shared pk)
- ❌ Sequential IDs (recent clustering)
- ❌ Time-based partition key (hot spot on current time)
- ❌ Low cardinality (few unique values)

**Cross-Reference:**
- AWS: AWS-LESS-07 (Enables query optimization)
- AWS: AWS-LESS-06 (When primary key insufficient)
- AWS: AWS-LESS-08 (Foundation for collections)

---

### AWS-LESS-06: Secondary Index Strategies

**File:** `AWS-DynamoDB-SecondaryIndexes_AWS-LESS-06.md`  
**Priority:** 🟡 Medium  
**Status:** Active

**Summary:** Secondary indexes enable alternative query patterns beyond primary key. Global indexes (GSI) provide flexibility but cost storage and consistency. Local indexes (LSI) share partition with base table. Design indexes for specific access patterns, not speculation.

**Core Principle:** Every index has a cost. Create indexes only for proven access patterns, not "just in case."

**Use When:**
- Need alternate query paths
- Primary key can't serve access pattern
- Query on non-key attributes
- Multiple access patterns required

**Index Types:**

**Global Secondary Index (GSI):**
- Different partition + sort keys from base
- Eventually consistent
- Separate storage (duplicates data)
- Can be added anytime
- **Cost:** 2x storage + 2x writes

**Local Secondary Index (LSI):**
- Same partition key as base
- Different sort key
- Strongly consistent available
- Shared storage
- Must create at table creation
- **Cost:** Lower storage, same writes

**Decision Framework:**
```
Need different partition key? → GSI
Need different sort order within partition? → LSI
Need strong consistency? → LSI
Cross-partition query? → GSI
```

**Cost Considerations:**
- Each GSI = full data duplication = 2x cost
- Write amplification: 1 write → N index writes
- Unused indexes waste money
- Monitor usage, remove if < 1% queries

**Anti-Patterns:**
- ❌ Over-indexing (GSI per attribute)
- ❌ Speculative indexes ("might need someday")
- ❌ Duplicate indexes (overlapping coverage)
- ❌ Project all attributes (when only keys needed)

**Cross-Reference:**
- AWS: AWS-LESS-05 (Primary key design first)
- AWS: AWS-LESS-07 (Enables query patterns)
- AWS: AWS-LESS-08 (Alternative to indexes)

---

### AWS-LESS-07: Query vs Scan Patterns

**File:** `AWS-DynamoDB-QueryVsScan_AWS-LESS-07.md`  
**Priority:** 🔴 Critical  
**Status:** Active

**Summary:** NoSQL databases offer query (efficient, targeted) and scan (inefficient, exhaustive) operations. Query requires partition key and provides O(1) lookup. Scan examines every item and should be avoided in production. Design data model to enable queries, not scans.

**Core Principle:** If your access pattern requires Scan, your data model is wrong (except batch analytics on small datasets).

**Golden Rule:** "Design to avoid scans at all costs."

**Use When:**
- Understanding query performance
- Optimizing data access
- Debugging slow queries
- Validating data model

**Operation Comparison:**

| Operation | Complexity | Latency | Cost | Use Case |
|-----------|-----------|---------|------|----------|
| **GetItem** | O(1) | 1-10ms | 1 unit | Fetch by complete key |
| **Query** | O(1)+O(k) | 5-50ms | k/4KB | Fetch from partition |
| **Scan** | O(n) | Seconds+ | n/4KB | Batch only |

**GetItem (Fastest):**
- Requires: Complete primary key
- Performance: O(1) direct lookup
- Cost: 1 read unit
- Latency: < 10ms

**Query (Fast):**
- Requires: Partition key (mandatory), sort key filter (optional)
- Performance: O(1) partition + O(k) filter
- Cost: Read units = items scanned / 4KB
- Latency: 5-50ms

**Scan (Slow - AVOID):**
- Requires: None
- Performance: O(n) full table scan
- Cost: Entire table read
- Latency: Seconds to minutes
- **ONLY acceptable:** Batch jobs on small tables (< 1000 items)

**Cost Example (1M items):**
```
GetItem: $0.000001 (1 item)
Query: $0.00025 (100 items)
Scan: $250 (entire table)

Scan is 10,000x more expensive!
```

**How to Eliminate Scans:**
```
Problem: Find all pending orders

❌ Bad: Scan orders table, filter status="pending"
✅ Good: GSI with pk=status
  Query: pk="pending"
  Result: O(1) + O(k) instead of O(n)
```

**Anti-Patterns:**
- ❌ Production user queries using Scan
- ❌ Real-time operations using Scan
- ❌ API endpoints using Scan
- ❌ Tables > 1000 items using Scan

**Cross-Reference:**
- AWS: AWS-LESS-05 (Key design enables queries)
- AWS: AWS-LESS-06 (Indexes enable queries)
- Project: NM06/LESS-02 (Measure don't guess)

---

### AWS-LESS-08: Item Collection Patterns

**File:** `AWS-DynamoDB-ItemCollections_AWS-LESS-08.md`  
**Priority:** 🟡 Medium  
**Status:** Active

**Summary:** NoSQL databases model one-to-many relationships using item collections - groups of items sharing the same partition key. Enables efficient retrieval of related items with single query, but requires careful key design to prevent partition hot spots and size limitations.

**Core Principle:** Item collections are powerful for 1:many relationships, but partition size and access patterns must be carefully managed.

**Use When:**
- Modeling 1:many relationships
- Need to retrieve related items together
- Strong data locality valuable
- Atomic operations across related items

**What Is an Item Collection:**
```
Definition: All items sharing same partition key

Physical Reality:
- Stored on same partition (server)
- Retrieved together in single query
- Fast access (local to partition)
- Limited size (10GB per partition)
```

**Structure Example:**
```
Partition Key: user_123 (shared)
├─ Sort Key: "PROFILE#metadata"    → User profile
├─ Sort Key: "ADDRESS#home"        → Home address
├─ Sort Key: "ADDRESS#work"        → Work address  
├─ Sort Key: "PAYMENT#card_001"    → Credit card
└─ Sort Key: "PAYMENT#card_002"    → Debit card

Query: pk="user_123" → Returns ALL items
```

**Benefits:**
- ✅ Single query retrieval (1 query vs 3)
- ✅ Strong data locality (no network hops)
- ✅ Atomic operations (transact within partition)
- ✅ Efficient filtering (sort key prefixes)

**Constraints:**
- ⚠️ 10GB limit per partition
- ⚠️ Hot partition risk (high access frequency)
- ⚠️ Relationship direction matters (one direction only)

**Common Patterns:**
1. **Entity-Attribute-Value:** pk=entity, sk=attribute_name
2. **Composite Sort Key:** pk=parent, sk="TYPE#id"
3. **Hierarchical Path:** pk=root, sk="level1::level2::level3"

**Real Example: User Profile System:**
```
Entity Relationship:
  User (1) → Addresses (many)
  User (1) → Payment Methods (many)

Item Collection:
  pk: user_id
  sk: entity_type#entity_id

Query Examples:
  Get entire profile: Query(pk="user_123")
  Get only addresses: Query(pk="user_123", sk begins_with "ADDRESS#")
  Get specific: Query(pk="user_123", sk="ADDRESS#home")
```

**Anti-Patterns:**
- ❌ Shared partition key across entities
- ❌ Uncontrolled collection growth (no limits)
- ❌ Complex many-to-many as collection
- ❌ No clear parent entity

**Cross-Reference:**
- AWS: AWS-LESS-05 (Primary key foundation)
- AWS: AWS-LESS-06 (Alternative: secondary indexes)
- AWS: AWS-LESS-07 (Query pattern for collections)

---

## Complete Data Modeling Workflow

### Phase 1: Identify Access Patterns (Start Here)

```
1. List all queries needed:
   - Get user profile
   - Get user's orders
   - Find orders by product
   - Get pending orders

2. For each query, determine:
   - What data needed?
   - How often accessed?
   - What filters applied?
   - What sort order required?
```

### Phase 2: Design Primary Keys (AWS-LESS-05)

```
1. Choose partition key:
   - High cardinality? (many unique values)
   - Even distribution? (no hot spots)
   - Matches primary access? (main query path)

2. Choose sort key:
   - Range queries needed?
   - Chronological ordering?
   - Hierarchical structure?

3. Validate:
   - Does this enable main queries?
   - Are partitions balanced?
   - Is there a hot spot risk?
```

### Phase 3: Optimize Queries (AWS-LESS-07)

```
1. For each access pattern:
   - Can use primary key? → GetItem or Query
   - Need different key? → Consider GSI (Phase 4)
   - No key works? → Data model needs redesign

2. Verify:
   - All queries use Query or GetItem (not Scan)
   - No O(n) operations in production
   - Query performance acceptable
```

### Phase 4: Add Secondary Indexes if Needed (AWS-LESS-06)

```
1. Only if primary key insufficient:
   - Proven access pattern exists (not speculation)
   - Performance unacceptable without index
   - Cost justified by query frequency

2. Choose index type:
   - Different partition key? → GSI
   - Different sort within partition? → LSI
   - Strong consistency needed? → LSI

3. Cost-benefit analysis:
   - Storage cost (2x for GSI)
   - Write amplification
   - Query frequency
   - Performance gain
```

### Phase 5: Model Relationships (AWS-LESS-08)

```
1. For 1:many relationships:
   - Use item collections (shared partition key)
   - Design typed sort keys
   - Plan for growth (10GB limit)

2. For many-to-many:
   - Duplicate data both directions
   - Or use GSI for reverse lookup
   - Not item collections

3. Validate:
   - Can retrieve related items in one query?
   - Is data locality valuable?
   - Are partitions balanced?
```

---

## Common Scenarios

### Scenario 1: E-commerce Order System

**Requirements:**
- Get order details
- Get user's orders
- Find orders by product
- Get pending orders

**Design:**
```
Base Table:
  pk: order_id
  sk: "ORDER#metadata"
  Get order: Query(pk=order_id)

GSI-1 (User's Orders):
  pk: user_id
  sk: order_date
  Get user orders: Query(pk=user_id)

GSI-2 (Product Orders):
  pk: product_id
  sk: order_date
  Find by product: Query(pk=product_id)

GSI-3 (Status Index):
  pk: status
  sk: order_date
  Get pending: Query(pk="pending")
```

### Scenario 2: User Profile with Related Entities

**Requirements:**
- Get complete user profile (profile + addresses + payments)
- Get user addresses only
- Get specific payment method

**Design:**
```
Item Collection:
  pk: user_id
  sk: entity_type#entity_id

Items:
  {pk: "user_123", sk: "PROFILE#main", ...}
  {pk: "user_123", sk: "ADDRESS#home", ...}
  {pk: "user_123", sk: "ADDRESS#work", ...}
  {pk: "user_123", sk: "PAYMENT#card_1", ...}

Queries:
  Complete profile: Query(pk="user_123")
  Addresses only: Query(pk="user_123", sk begins_with "ADDRESS#")
  Specific payment: Query(pk="user_123", sk="PAYMENT#card_1")
```

---

## Quick Reference

### Key Selection Checklist (AWS-LESS-05)
- [✅] High cardinality partition key
- [✅] Even distribution (no hot spots)
- [✅] Supports primary access pattern
- [✅] Sort key enables range queries (if needed)
- [❌] Avoid: Low cardinality, time-based partition key

### Query Optimization Checklist (AWS-LESS-07)
- [✅] All queries use Query or GetItem (not Scan)
- [✅] Partition key in every query
- [✅] Sort key filters where applicable
- [✅] No O(n) operations in production
- [❌] Avoid: Scan in real-time operations

### Index Decision Checklist (AWS-LESS-06)
- [✅] Proven access pattern (not speculation)
- [✅] Cost justified (storage + writes)
- [✅] Primary key can't serve pattern
- [✅] Query frequency warrants cost
- [❌] Avoid: Speculative indexes, over-indexing

### Collection Design Checklist (AWS-LESS-08)
- [✅] Clear 1:many relationship
- [✅] Retrieve related items together
- [✅] Partition size < 10GB
- [✅] No hot partition risk
- [❌] Avoid: Many-to-many as collection

---

## Related Content

### Cross-References to Other AWS Categories

**aws/lambda/**
- AWS-LESS-03: Stateless execution (affects data fetching)

**aws/general/**
- AWS-LESS-01: Processing patterns (affects query patterns)

### Cross-References to Project Maps

**NM06 - Lessons:**
- LESS-02: Measure don't guess (validate query performance)

---

## Keywords

DynamoDB, NoSQL, partition key, sort key, primary key, query, scan, GSI, LSI, secondary index, item collection, data modeling, access patterns

---

## Navigation

- **Up:** AWS-Master-Index.md
- **Siblings:** 
  - aws/general/General-Index.md
  - aws/lambda/Lambda-Index.md
  - aws/api-gateway/APIGateway-Index.md
- **Quick Index:** AWS-Quick-Index.md

**Total Patterns:** 4  
**Read Order:** 05 → 07 → 06 → 08  
**SIMAv4 Compliant:** ✅

**End of Index**
