# AWS-DynamoDB-ItemCollections_AWS-LESS-08.md

**Version:** 1.0.0  
**Date:** 2025-11-01  
**Purpose:** Item collection patterns for one-to-many relationships in NoSQL

**REF-ID:** AWS-LESS-08  
**Category:** AWS DynamoDB  
**Type:** LESS (Lesson Learned)  
**Priority:** 🟠 High  
**Status:** Active  
**Source:** AWS Serverless Developer Guide (Genericized)

---

## Summary

NoSQL databases can model one-to-many relationships using item collections - groups of items sharing the same partition key. This enables efficient retrieval of related items with a single query, but requires careful key design to prevent partition hot spots and size limitations.

**Core Principle:** Item collections are powerful for 1:many relationships, but partition size and access patterns must be carefully managed to avoid bottlenecks.

---

## What Is an Item Collection?

**Definition:**
```
Item Collection = All items sharing the same partition key

Physical Reality:
  - Stored on same partition (physical server)
  - Retrieved together in single query
  - Fast access (local to partition)
  - Limited size (typically 10GB per partition)
```

**Structure:**
```
Partition Key: user_123 (shared)
├─ Sort Key: "PROFILE#metadata"    → User profile
├─ Sort Key: "ADDRESS#home"        → Home address
├─ Sort Key: "ADDRESS#work"        → Work address  
├─ Sort Key: "PAYMENT#card_001"    → Credit card
├─ Sort Key: "PAYMENT#card_002"    → Debit card
└─ Sort Key: "PREFERENCE#theme"    → UI preferences

Query: pk="user_123" → Returns ALL items (entire collection)
```

---

## Pattern: 1:Many Relationship Modeling

**Concept:** Store related entities together using shared partition key and typed sort keys.

**Example 1: User Profile System**
```
Entity Relationship:
  User (1) → Addresses (many)
  User (1) → Payment Methods (many)
  User (1) → Preferences (many)

Item Collection Design:
  pk: user_id (same for all related items)
  sk: entity_type#entity_id (different per item)

Data:
  {pk: "user_123", sk: "PROFILE#main", data: {name, email...}}
  {pk: "user_123", sk: "ADDRESS#home", data: {street, city...}}
  {pk: "user_123", sk: "ADDRESS#work", data: {street, city...}}
  {pk: "user_123", sk: "PAYMENT#card_001", data: {last4, exp...}}
  {pk: "user_123", sk: "PAYMENT#card_002", data: {last4, exp...}}

Query Examples:
  1. Get entire user profile:
     Query(pk="user_123")
     → Returns all 5 items
  
  2. Get only addresses:
     Query(pk="user_123", sk begins_with "ADDRESS#")
     → Returns 2 address items
  
  3. Get specific address:
     Query(pk="user_123", sk="ADDRESS#home")
     → Returns 1 item
```

**Example 2: Order Management**
```
Entity Relationship:
  Order (1) → Line Items (many)
  Order (1) → Shipments (many)
  Order (1) → Payments (many)

Item Collection Design:
  pk: order_id
  sk: entity_type#entity_id

Data:
  {pk: "order_789", sk: "METADATA#order", data: {total, status...}}
  {pk: "order_789", sk: "ITEM#001", data: {product, qty, price...}}
  {pk: "order_789", sk: "ITEM#002", data: {product, qty, price...}}
  {pk: "order_789", sk: "ITEM#003", data: {product, qty, price...}}
  {pk: "order_789", sk: "SHIPMENT#ship_01", data: {tracking...}}
  {pk: "order_789", sk: "PAYMENT#pay_01", data: {amount, status...}}

Query Examples:
  1. Get complete order:
     Query(pk="order_789")
     → 6 items (metadata + items + shipment + payment)
  
  2. Get only line items:
     Query(pk="order_789", sk begins_with "ITEM#")
     → 3 line item records
  
  3. Calculate order total:
     Query(pk="order_789", sk begins_with "ITEM#")
     Sum(item.price * item.qty)
```

---

## Benefits of Item Collections

**1. Single Query Retrieval**
```
Traditional SQL (3 queries):
  SELECT * FROM users WHERE user_id = 123;
  SELECT * FROM addresses WHERE user_id = 123;
  SELECT * FROM payments WHERE user_id = 123;

NoSQL Item Collection (1 query):
  Query(pk="user_123")
  → Returns all related data in one operation
  
Performance: 3x faster (1 query vs 3)
Cost: 3x cheaper (1 query cost vs 3)
```

**2. Strong Data Locality**
```
Physical Storage:
  All items stored on same partition
  No network hops between items
  Sequential read optimization
  
Latency: 5-10ms (single partition access)
vs SQL joins: 20-50ms (multiple tables, indexes)
```

**3. Atomic Operations**
```
Transactions within partition:
  - Update multiple items atomically
  - Read consistent view of related data
  - Strong consistency available
  
Example:
  TransactWriteItems([
    Update(pk="user_123", sk="PROFILE#main", ...),
    Update(pk="user_123", sk="ADDRESS#home", ...),
    Update(pk="user_123", sk="PAYMENT#card_001", ...)
  ])
  → All updates succeed or all fail
```

---

## Constraints and Trade-offs

**Constraint 1: Partition Size Limit (10GB)**
```
Limit: 10GB per partition key value

Calculation Example:
  Average item size: 10KB
  Items per partition: 10GB / 10KB = ~1 million items
  
If exceeded:
  - Write operations throttled
  - Read operations slower
  - Hot partition issues

Prevention:
  ✅ Monitor partition size
  ✅ Archive old data
  ✅ Split large collections
```

**Constraint 2: Hot Partition Risk**
```
Problem: High access frequency on single partition

Example - Bad:
  pk="PRODUCT" (shared by all products!)
  sk=product_id
  → All product queries hit ONE partition

Example - Good:
  pk=product_id (unique per product)
  sk="METADATA#product"
  → Load distributed across partitions

Detection:
  - Throttling errors
  - High latency on specific keys
  - Uneven throughput distribution

Solution:
  - Use high-cardinality partition keys
  - Distribute load across partitions
  - Add random shard suffix if needed
```

**Constraint 3: Relationship Direction Matters**
```
Item collection only works in ONE direction

User (1) → Orders (many):
  ✅ pk=user_id, sk=order_id
  ✅ Query: Get user's orders → FAST
  ❌ Query: Get order's user → SLOW (must know user_id)

Solution: Duplicate data or use GSI
  Base table: pk=user_id, sk=order_id
  GSI: pk=order_id, sk=user_id
  → Both directions fast
```

---

## Design Patterns

**Pattern 1: Entity-Attribute-Value (EAV)**
```
Flexible schema for sparse data

Structure:
  pk: entity_id
  sk: attribute_name
  value: attribute_value

Example - Product catalog:
  {pk: "product_123", sk: "name", value: "Laptop"}
  {pk: "product_123", sk: "price", value: 999.99}
  {pk: "product_123", sk: "color", value: "silver"}
  {pk: "product_456", sk: "name", value: "Mouse"}
  {pk: "product_456", sk: "price", value: 29.99}
  (No "color" attribute for mouse)

Benefits:
  ✅ Flexible schema
  ✅ Sparse attributes OK
  ✅ Easy to add attributes

Drawbacks:
  ❌ More items per entity
  ❌ Harder to query across attributes
```

**Pattern 2: Composite Sort Key (Type#ID)**
```
Typed entities with unique IDs

Structure:
  pk: parent_id
  sk: "TYPE#unique_id"

Example:
  {pk: "user_123", sk: "ADDRESS#uuid_1", ...}
  {pk: "user_123", sk: "ADDRESS#uuid_2", ...}
  {pk: "user_123", sk: "PAYMENT#uuid_3", ...}

Benefits:
  ✅ Clear entity types
  ✅ Prefix queries work
  ✅ Unique IDs prevent conflicts

Query patterns:
  - All addresses: sk begins_with "ADDRESS#"
  - Specific address: sk = "ADDRESS#uuid_1"
```

**Pattern 3: Hierarchical Path (Type::Subtype::ID)**
```
Multi-level relationships

Structure:
  pk: root_id
  sk: "level1::level2::level3"

Example - Game inventory:
  {pk: "character_789", sk: "inventory::armor::helmet_01"}
  {pk: "character_789", sk: "inventory::armor::chest_02"}
  {pk: "character_789", sk: "inventory::weapons::sword_03"}
  {pk: "character_789", sk: "inventory::weapons::bow_04"}

Query patterns:
  - All inventory: sk begins_with "inventory::"
  - All armor: sk begins_with "inventory::armor::"
  - Specific item: sk = "inventory::armor::helmet_01"
```

---

## Anti-Patterns

**Anti-Pattern 1: Shared Partition Key Across Entities**
```
❌ WRONG:
  All users share pk="USER"
  {pk: "USER", sk: "user_123", ...}
  {pk: "USER", sk: "user_456", ...}
  (Single hot partition!)

✅ RIGHT:
  Each user has unique partition key
  {pk: "user_123", sk: "PROFILE#main", ...}
  {pk: "user_456", sk: "PROFILE#main", ...}
```

**Anti-Pattern 2: Uncontrolled Collection Growth**
```
❌ WRONG:
  No limit on items per partition
  User posts: pk=user_id, sk=post_id
  → Power user has 1M posts → 10GB limit exceeded!

✅ RIGHT:
  Time-based partitioning
  pk=user_id#year_month, sk=post_id
  → Distributes load across time partitions
```

---

## When NOT to Use Item Collections

**Use Cases Where Collections Don't Fit:**
```
❌ Many-to-many relationships
   → Use link table or GSI

❌ Unbounded growth
   → Use time-based partitioning

❌ Cross-partition queries
   → Use GSI or separate table

❌ Equal importance relationships
   → No clear "parent" entity

❌ Frequently accessed individual items
   → Use item-per-partition instead
```

---

## Why This Matters

**Performance:**
- Item collections: 1 query for related data
- SQL joins: Multiple queries or expensive joins
- 3-10x faster retrieval for related data

**Cost:**
- Single query = lower cost
- Reduced network overhead
- Efficient storage (data locality)

**Data Consistency:**
- Atomic operations within partition
- Strong consistency for related items
- Transactional updates across collection

---

## Cross-References

**AWS Patterns:**
- AWS-DynamoDB-PrimaryKeyDesign_AWS-LESS-05.md - Foundation for collections
- AWS-DynamoDB-SecondaryIndexes_AWS-LESS-06.md - Alternative to collections
- AWS-DynamoDB-QueryVsScan_AWS-LESS-07.md - Efficient collection access

**Project Maps:**
- /sima/entries/decisions/architecture/DEC-01.md - Similar hierarchical structure in SUGA

---

## Keywords

item collection, 1:many relationships, NoSQL, partition key, data locality, hierarchical data, composite keys, denormalization

---

**Location:** `/sima/aws/dynamodb/`  
**Total Lines:** 392 (≤400 for SIMAv4)  
**SIMAv4 Compliant:** ✅
