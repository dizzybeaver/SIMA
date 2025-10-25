# AWS-LESS-05: NoSQL Primary Key Design Patterns

**Category:** External Knowledge - AWS Serverless  
**Type:** LESS (Lesson Learned)  
**Priority:** ⭐⭐⭐⭐⭐ HIGH  
**Status:** Active  
**Created:** 2025-10-25  
**Source:** AWS Serverless Developer Guide (Genericized)

---

## Summary

NoSQL databases use partition keys to distribute data and sort keys to organize related items. Well-designed keys enable efficient queries and prevent hot partitions. Poor key design causes performance bottlenecks and high costs.

**Lesson:** Key design is a modeling decision, not an afterthought. Choose keys based on access patterns, not data structure.

---

## Context

Unlike SQL databases with normalized tables and joins, NoSQL databases denormalize data and use keys for both storage location (partition) and query optimization (sort). A single bad key choice can make common queries impossible or prohibitively expensive.

**Problem Domain:** NoSQL data modeling, query optimization, distributed systems  
**Applicability:** DynamoDB, Cassandra, HBase, any partition-based NoSQL database

---

## The Lesson

### Partition Key Selection

**Goal:** Distribute load evenly across partitions

**Design Criteria:**
```
✅ High cardinality (many unique values)
✅ Even distribution (no hot keys)
✅ Predictable access patterns

Good Examples:
  ✅ user_id (evenly distributed, high cardinality)
  ✅ product_id (many products, balanced access)
  ✅ device_id (IoT: many devices)

Bad Examples:
  ❌ status (few values: "active", "inactive" → hot partition)
  ❌ date (time-based access creates hot spots)
  ❌ category (popular categories become bottlenecks)
  ❌ country_code (uneven: US has 1000x more users than small countries)
```

### Sort Key Selection

**Goal:** Enable efficient range queries within partition

**Design Criteria:**
```
✅ Supports access patterns
✅ Hierarchical structure possible
✅ Prefix queries work
✅ Chronological ordering useful

Good Examples:
  ✅ timestamp (chronological queries)
  ✅ category#subcategory (hierarchy: "inventory::armor::helmet_123")
  ✅ type#id (grouped by type: "ORDER#2025-01-15")

Bad Examples:
  ❌ random_uuid (no meaningful ordering)
  ❌ unstructured strings (can't filter efficiently)
```

### Common Key Patterns

**Pattern 1: Simple Key (partition only)**
```
Structure:
  pk: user_id
  (no sort key)

Use Case: Direct lookup by user
Query: Get user by ID → O(1) lookup

Example:
  pk: "user_12345"
  Retrieves: Single user record
```

**Pattern 2: Composite Key (partition + sort)**
```
Structure:
  pk: user_id
  sk: order_timestamp

Use Case: Get user's orders chronologically
Query: Get user's orders → Returns sorted list

Example:
  pk: "user_12345"
  sk: "2025-01-15T10:30:00"
  Retrieves: User's orders in time order
```

**Pattern 3: Hierarchical Sort Key**
```
Structure:
  pk: account_id
  sk: "type::category::item_id"

Use Case: Query all items, specific types, or individual items
Query: Prefix matching on sort key

Examples:
  pk: "account_123"
  sk: "inventory::armor::item_456"
  
  Queries:
    "inventory::" → All inventory
    "inventory::armor::" → All armor
    "inventory::armor::item_456" → Specific item
```

**Pattern 4: Generic Partition, Typed Sort**
```
Structure:
  pk: entity_id
  sk: "ENTITY_TYPE#identifier"

Use Case: Store different entity types in one table
Query: Query by type prefix

Examples:
  pk: "user_123"
  sk: "PROFILE#metadata"     → User profile
  sk: "ADDRESS#home"          → Home address
  sk: "ADDRESS#work"          → Work address
  sk: "ORDER#2025-01-15"      → Order
  sk: "PAYMENT#card_001"      → Payment method
  
  Query: sk starts_with "ADDRESS#" → All addresses
```

### Anti-Patterns

**Anti-Pattern 1: Single Hot Partition**
```
❌ WRONG:
  pk: "USER" (shared by all users)
  sk: user_id
  
Problem: All users in one partition = hot spot
Fix: Use user_id as partition key
```

**Anti-Pattern 2: Sequential IDs**
```
❌ WRONG:
  pk: auto_increment_id
  
Problem: Recent IDs clustered → hot partition
Fix: Use hash or random distribution
```

**Anti-Pattern 3: Time-Based Partition Key**
```
❌ WRONG:
  pk: date (e.g., "2025-01-15")
  
Problem: Today's date is hot partition
Fix: Use date as sort key, entity as partition
```

**Anti-Pattern 4: Low Cardinality Partition**
```
❌ WRONG:
  pk: country_code
  
Problem: Popular countries overwhelm partitions
Fix: Composite key: country_code#user_id
```

### Real-World Trade-offs

**Trade-off 1: Granularity vs Hot Spots**
```
Too Coarse:
  pk: "all_users" → One partition (hot spot)

Too Fine:
  pk: user_id#timestamp → Too many partitions

Sweet Spot:
  pk: user_id
  sk: timestamp
```

**Trade-off 2: Flexibility vs Performance**
```
Flexible (slower):
  pk: user_id
  sk: random_id
  (Requires scan to find items)

Optimized (faster):
  pk: user_id
  sk: "ORDER#" + timestamp
  (Can query by prefix)
```

**Trade-off 3: Single Table vs Multiple Tables**
```
Single Table Design:
  pk: entity_id
  sk: entity_type#id
  (Complex queries, single table)

Multi-Table:
  users_table: pk=user_id
  orders_table: pk=order_id
  (Simple queries, multiple tables)
```

---

## Why This Matters

**Performance Impact:**
- Good keys: O(1) query performance
- Bad keys: O(n) scan required
- Hot partitions: 10-100x slower, throttling errors

**Cost Impact:**
- Queries: Charged per item read
- Scans: Charged for entire table
- Hot partitions: Provisioned capacity wasted

**Scalability Impact:**
- Well-distributed keys: Linear scaling
- Hot partitions: Cannot scale past bottleneck

---

## When to Apply

**Design Time:**
- ✅ Before creating tables
- ✅ During access pattern analysis
- ✅ When modeling entity relationships

**Problem Indicators:**
- ❌ Queries taking > 100ms
- ❌ Throttling errors
- ❌ Needing to scan entire table
- ❌ Unable to filter results efficiently

---

## Related Patterns

- **AWS-LESS-06**: Secondary Index Strategies - Alternative query paths
- **AWS-LESS-07**: Query vs Scan Patterns - Efficient data access
- **AWS-LESS-08**: Item Collection Patterns - 1:many relationships
- **AP-17**: No Input Validation - Validate keys before querying

---

## Keywords

NoSQL, partition key, sort key, composite key, data modeling, distributed database, access patterns, query optimization

---

## Version History

- **2025-10-25**: Created - Extracted from AWS Serverless Developer Guide, genericized for universal application

---

**End of Entry**
**Lines:** ~195 (within < 200 target)  
**Quality:** Generic, actionable, concise
