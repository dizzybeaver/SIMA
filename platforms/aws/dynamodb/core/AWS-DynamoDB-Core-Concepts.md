# AWS-DynamoDB-Core-Concepts.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Core AWS DynamoDB concepts and patterns  
**Category:** Platform/AWS/DynamoDB

---

## OVERVIEW

AWS DynamoDB is a fully managed NoSQL database service providing:
- Single-digit millisecond latency at any scale
- Automatic scaling and replication
- Built-in security and backup
- Point-in-time recovery
- Global tables for multi-region replication

**Key Characteristics:**
- Serverless (pay per request or provisioned capacity)
- Schemaless (flexible data model)
- Horizontally scalable
- Eventually consistent by default (strongly consistent available)

---

## CORE DATA MODEL

### Tables

**Table:** Top-level container for data
- No predefined schema required
- Identified by table name
- Contains items (records)
- Has primary key definition

**Capacity Modes:**
- **On-Demand:** Pay per request (unpredictable workloads)
- **Provisioned:** Define RCU/WCU (predictable workloads)

### Items

**Item:** Single record in table (equivalent to row)
- Collection of attributes
- Must have primary key
- Max size: 400 KB
- No limit on number of items per table

**Example:**
```json
{
  "UserId": "user123",        // Partition key
  "Timestamp": 1699459200,    // Sort key
  "Action": "login",
  "IPAddress": "192.168.1.1",
  "Duration": 45
}
```

### Attributes

**Attribute:** Key-value pair (equivalent to column)
- Name: String identifier
- Value: Typed data

**Supported Types:**
- **Scalar:** String, Number, Binary, Boolean, Null
- **Set:** String Set, Number Set, Binary Set
- **Document:** List (array), Map (object)

---

## PRIMARY KEYS

### Partition Key (Hash Key)

**Simple Primary Key:**
- Single attribute
- Determines item's partition (physical storage)
- Must be unique across table
- Used for `GetItem`, `PutItem`, `DeleteItem`

**Example:**
```
Primary Key: UserId
+----------+
| UserId   | (Partition Key)
+----------+
| user123  |
| user456  |
| user789  |
+----------+
```

**Characteristics:**
- Fast single-item lookups
- Cannot query range of values
- Distribution determines performance

### Composite Key (Partition + Sort Key)

**Composite Primary Key:**
- Two attributes (partition key + sort key)
- Partition key groups related items
- Sort key orders items within partition
- Combination must be unique

**Example:**
```
Primary Key: UserId + Timestamp
+----------+-------------+
| UserId   | Timestamp   | (Sort Key)
+----------+-------------+
| user123  | 1699459200  |
| user123  | 1699459260  |
| user123  | 1699459320  |
+----------+-------------+
```

**Characteristics:**
- Query ranges within partition
- One-to-many relationships
- Efficient sorted retrieval

---

## SECONDARY INDEXES

### Global Secondary Index (GSI)

**Definition:** Alternative partition/sort keys
- Queries on non-primary-key attributes
- Different partition key than base table
- Optional different sort key
- Eventual consistency only
- Separate throughput capacity

**Use Cases:**
- Query by different attributes
- Multiple access patterns
- Aggregate queries

**Example:**
```
Base Table: UserId (PK) + Timestamp (SK)
GSI: OrderStatus (PK) + OrderDate (SK)

Query all "pending" orders sorted by date
```

**Limits:**
- Max 20 GSIs per table
- Sparse indexes (only items with index attributes)
- Additional storage cost

### Local Secondary Index (LSI)

**Definition:** Alternative sort key, same partition key
- Same partition key as base table
- Different sort key
- Strong or eventual consistency
- Shares throughput with base table
- Must be created at table creation

**Use Cases:**
- Multiple sort orders for same partition
- Alternative query patterns within partition

**Example:**
```
Base Table: UserId (PK) + Timestamp (SK)
LSI: UserId (PK) + Email (SK)

Query user's items sorted by email instead of timestamp
```

**Limits:**
- Max 5 LSIs per table
- Cannot add/remove after table creation
- 10 GB limit per partition key value (base + LSIs)

---

## CONSISTENCY MODELS

### Eventually Consistent Reads (Default)

**Characteristics:**
- Reads may not reflect recent write
- Typically consistent within 1 second
- Higher throughput (50% cheaper RCU)
- Best for most workloads

**Use When:**
- Data can tolerate slight staleness
- Optimizing for cost/performance
- Reading frequently updated data

### Strongly Consistent Reads

**Characteristics:**
- Returns most recent successful write
- Higher latency
- Higher cost (double RCU consumption)
- Not available for GSI queries

**Use When:**
- Critical data accuracy required
- Immediate read-after-write needed
- Transactional workflows

---

## CAPACITY UNITS

### Read Capacity Units (RCU)

**1 RCU =**
- 1 strongly consistent read per second
- 2 eventually consistent reads per second
- Item size up to 4 KB

**Calculation:**
```
RCU = (Item Size / 4 KB) × Reads per second

Eventually Consistent: RCU / 2

Example:
- 100 items/sec × 3 KB each
- (3 KB / 4 KB) = 0.75 → rounds to 1
- Strongly: 100 × 1 = 100 RCU
- Eventually: 100 × 1 / 2 = 50 RCU
```

### Write Capacity Units (WCU)

**1 WCU =**
- 1 write per second
- Item size up to 1 KB

**Calculation:**
```
WCU = (Item Size / 1 KB) × Writes per second

Example:
- 50 items/sec × 2 KB each
- (2 KB / 1 KB) = 2
- WCU = 50 × 2 = 100 WCU
```

---

## QUERY VS SCAN

### Query Operation

**Characteristics:**
- Requires partition key
- Optionally filters by sort key
- Returns sorted results
- Efficient (only reads matching items)
- Supports pagination

**Use Cases:**
- Known partition key
- Range queries on sort key
- Most common operation

**Example:**
```python
response = table.query(
    KeyConditionExpression=Key('UserId').eq('user123') & 
                          Key('Timestamp').between(start, end)
)
```

**Performance:** O(log n) lookup + O(k) where k = items returned

### Scan Operation

**Characteristics:**
- Reads entire table
- Applies filter after reading
- Returns unsorted results
- Expensive (reads all items)
- Supports parallel scans

**Use Cases:**
- No alternative access pattern
- Full table operations
- Data export/backup
- Should be avoided in production

**Example:**
```python
response = table.scan(
    FilterExpression=Attr('Status').eq('active')
)
```

**Performance:** O(n) where n = total items in table

**Cost Impact:**
```
Query: Consumes RCU only for matching items
Scan: Consumes RCU for ALL items (even filtered out)

Example:
- 1,000,000 items in table
- 100 match filter
- Query: ~100 RCU consumed
- Scan: ~1,000,000 RCU consumed (10,000x worse!)
```

---

## ITEM COLLECTIONS

**Definition:** All items with same partition key value

**Characteristics:**
- Max 10 GB per item collection (with LSIs)
- No limit without LSIs
- All items retrieved together by partition key

**Use Cases:**
- One-to-many relationships
- Time-series data
- Hierarchical data

**Example:**
```
Partition Key: CustomerId
Sort Key: OrderId

Customer "cust123" has 500 orders = 1 item collection
```

**Limits:**
```
Without LSI: No limit on item collection size
With LSI: 10 GB limit per partition key value
```

---

## BEST PRACTICES

### Design for Access Patterns

❌ **Wrong:** Design schema first, queries second  
✅ **Correct:** Design queries first, schema second

**Steps:**
1. Identify all access patterns
2. Design primary key for most common pattern
3. Add GSIs for additional patterns
4. Minimize number of queries

### Distribute Partition Keys

❌ **Wrong:** Sequential or predictable keys  
✅ **Correct:** High-cardinality, well-distributed keys

**Examples:**
```
Bad: CustomerId = "1", "2", "3" (hot partitions)
Good: CustomerId = UUID (distributed)

Bad: Date = "2024-11-08" (all writes to one partition)
Good: Date + ShardId = "2024-11-08#001" (distributed)
```

### Use Composite Keys for Relationships

❌ **Wrong:** Separate tables for relationships  
✅ **Correct:** Composite keys with hierarchical sort keys

**Example:**
```
One table for users + posts + comments:

PK: UserId#POST#PostId     SK: Timestamp
PK: UserId#POST#PostId     SK: COMMENT#CommentId
```

### Project Only Needed Attributes

❌ **Wrong:** Read entire item when only need few attributes  
✅ **Correct:** Use `ProjectionExpression` to limit data

**Savings:**
```
Item size: 10 KB
Need: 2 attributes = 1 KB

Without projection: 3 RCU (10 KB / 4 KB = 2.5 → 3)
With projection: 1 RCU (1 KB / 4 KB = 0.25 → 1)

Savings: 67% fewer RCU
```

### Batch Operations

❌ **Wrong:** Individual `GetItem`/`PutItem` calls  
✅ **Correct:** Use `BatchGetItem`/`BatchWriteItem`

**Efficiency:**
```
100 individual GetItem calls: 100 HTTP requests
1 BatchGetItem call: 1 HTTP request

Latency reduction: ~90%
```

---

## COST OPTIMIZATION

### On-Demand vs Provisioned

**On-Demand:**
- Pros: No capacity planning, auto-scaling, pay per request
- Cons: 5-7x more expensive per request
- Use for: Unpredictable, spiky, or low-volume workloads

**Provisioned:**
- Pros: Predictable cost, 80%+ cheaper at scale
- Cons: Requires capacity planning, throttling risk
- Use for: Steady, predictable workloads

**Break-even:**
```
On-Demand WCU cost: $1.25 per million
Provisioned WCU cost: $0.00065 per hour per WCU

Break-even: ~8 WCU sustained 24/7
If using > 8 WCU continuously, provisioned is cheaper
```

### GSI Efficiency

**Sparse Indexes:**
- Only items with index attributes consume storage
- Reduce GSI size by using conditional attributes

**Projected Attributes:**
- KEYS_ONLY: Cheapest (only keys)
- INCLUDE: Medium (keys + selected attributes)
- ALL: Expensive (duplicates all data)

---

## LIMITATIONS

### Item Level
- Max item size: 400 KB
- Max attribute value size: 400 KB (except Number/Binary)
- Max attribute name length: 64 KB
- Max nested depth: 32 levels

### Table Level
- Max tables per region: 2,500 (can request increase)
- Max GSIs: 20 per table
- Max LSIs: 5 per table (cannot change after creation)
- Max item collections with LSI: 10 GB

### Request Level
- BatchGetItem: 100 items, 16 MB
- BatchWriteItem: 25 items, 16 MB
- Query/Scan: 1 MB per call (use pagination)
- TransactWriteItems: 100 items, 4 MB

---

## RELATED PATTERNS

**REF:**
- AWS-Lambda-StatelessExecution (DynamoDB for Lambda state)
- AWS-DynamoDB-PrimaryKeyDesign (key design patterns)
- AWS-DynamoDB-SecondaryIndexes (GSI/LSI usage)
- AWS-DynamoDB-QueryVsScan (query optimization)
- AWS-DynamoDB-ItemCollections (partition management)

**Cross-Platform:**
- LESS-02: Measure don't guess (capacity planning)
- DEC-07: Resource constraints (memory limits)

---

**END OF FILE**

**Key Takeaways:**
- DynamoDB is schemaless NoSQL database
- Primary key design is critical (partition + optional sort)
- GSIs enable additional access patterns
- Query > Scan (always)
- Design for access patterns, not entities
- Capacity planning determines cost

**Impact:** Understanding these concepts prevents 80% of common DynamoDB mistakes
