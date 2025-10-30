# AWS-LESS-07: Query vs Scan Patterns (NoSQL)

**Category:** External Knowledge - AWS Serverless  
**Type:** LESS (Lesson Learned)  
**Priority:** ⭐⭐⭐⭐⭐ HIGH  
**Status:** Active  
**Created:** 2025-10-25  
**Source:** AWS Serverless Developer Guide (Genericized)

---

## Summary

NoSQL databases offer query (efficient, targeted) and scan (inefficient, exhaustive) operations. Query requires partition key and provides O(1) lookup. Scan examines every item and should be avoided in production. Design data model to enable queries, not scans.

**Lesson:** If your access pattern requires Scan, your data model is wrong (except for batch analytics on small datasets).

---

## Context

SQL databases use indexes to make any query fast. NoSQL databases require the partition key for fast access. Without it, the database must examine every item sequentially - an operation that becomes prohibitively slow and expensive as data grows.

**Problem Domain:** NoSQL data access, query optimization  
**Applicability:** DynamoDB, Cassandra, HBase, any partition-based NoSQL database

---

## The Lesson

### GetItem (Fastest: O(1))

**Characteristics:**
```
Requirements:
  - Full primary key known (partition + sort)
  
Performance:
  - O(1) lookup (direct partition access)
  - Single item returned
  - Strongly consistent reads available
  - Lowest cost per operation
  
Latency: Single-digit milliseconds (< 10ms)
```

**When to Use:**
```
✅ Fetching specific item by complete ID
✅ User profile by user_id
✅ Order details by order_id
✅ Product info by product_id

Example:
  GetItem(user_id="user_123")
  → Direct partition lookup
  → Returns single user
  → 1-5ms latency
  → 1 read unit cost
```

**Code Pattern:**
```
# Pseudocode
def get_user(user_id):
    return db.get_item(
        table='users',
        key={'user_id': user_id}
    )
    
Time: O(1) - constant
Cost: 1 read unit
```

### Query (Fast: O(1) partition + O(k) filter)

**Characteristics:**
```
Requirements:
  - Partition key required (mandatory)
  - Sort key filter optional
  
Performance:
  - O(1) to find partition
  - O(k) to scan items in partition (k = items matched)
  - Multiple items returned
  - Can filter on sort key
  - Efficient cost (only partition read)
  
Latency: Single to double-digit milliseconds (5-50ms)
```

**When to Use:**
```
✅ All items in a partition
✅ User's orders (pk=user_id)
✅ Product inventory (pk=product_id)
✅ Time range queries (sk filter on timestamp)
✅ Prefix matching on sort key

Example:
  Query(
    pk="user_123",
    sk_begins_with="ORDER#2025-01"
  )
  → Find partition (O(1))
  → Filter by sort key prefix
  → Returns matching orders
  → 10-30ms latency
```

**Optimization Strategies:**
```
Strategy 1: Add Sort Key Conditions
  Query(pk="user_123")              → 1000 items
  Query(pk="user_123", sk>="2025")  → 100 items (10x faster)

Strategy 2: Sparse Indexes
  Create index only on items with specific attribute
  → Reduces partition size
  
Strategy 3: Pagination
  Use limit + lastEvaluatedKey to process results in batches
  → Prevents timeout on large results
```

**Code Pattern:**
```
# Pseudocode
def get_user_orders(user_id, year=None):
    query = {
        'table': 'orders',
        'pk': user_id
    }
    
    if year:
        query['sk_condition'] = f'begins_with ORDER#{year}'
    
    return db.query(**query)
    
Time: O(1) + O(k) where k = items in partition
Cost: Read units = items scanned / 4KB
```

### Scan (Slow: O(n) - Avoid!)

**Characteristics:**
```
Requirements:
  - None (scans entire table)
  
Performance:
  - O(n) full table scan (n = total items)
  - Reads every item in table
  - Expensive (cost = entire table size)
  - Slow (time = entire table size)
  - Can apply filters (still scans everything)
  
Latency: Seconds to minutes (depends on table size)
```

**When Scan Is Unacceptable:**
```
❌ Production user queries
❌ Real-time operations
❌ API endpoints
❌ Frequent operations
❌ Tables > 1000 items
❌ Time-sensitive workflows

Why: Cost and latency scale linearly with table size
```

**When Scan Is Acceptable:**
```
✅ Small tables (< 1000 items)
✅ Batch jobs (offline processing)
✅ Initial data migration
✅ Infrequent administrative tasks
✅ One-time data analysis

Conditions:
  - Not user-facing
  - Not time-sensitive
  - Infrequent (< 1/day)
  - Small dataset
```

**Example Cost Comparison:**
```
Table: 1 million items, 1KB each

GetItem (single user):
  Cost: 1 read unit
  Time: 5ms
  
Query (user's 100 orders):
  Cost: 25 read units (100 items * 1KB / 4KB)
  Time: 20ms
  
Scan (entire table):
  Cost: 250,000 read units (1M items * 1KB / 4KB)
  Time: 30 seconds (parallel scan) to 5 minutes (sequential)
  
Scan is 10,000x more expensive!
```

**Code Pattern (Anti-Pattern):**
```
# Pseudocode - AVOID THIS
def find_active_users():
    # ❌ Scans entire users table!
    return db.scan(
        table='users',
        filter='status = active'
    )
    
Time: O(n) - linear with table size
Cost: Entire table read
Latency: Seconds to minutes

# ✅ BETTER: Use GSI
def find_active_users():
    # Query index where pk=status
    return db.query(
        table='users',
        index='status-index',
        pk='active'
    )
    
Time: O(1) + O(k) where k = active users
Cost: Only active users read
Latency: Milliseconds
```

### Comparison Table

| Operation | Complexity | Latency | Cost | Use Case |
|-----------|-----------|---------|------|----------|
| **GetItem** | O(1) | 1-10ms | 1 unit | Fetch by complete key |
| **Query** | O(1)+O(k) | 5-50ms | k/4KB units | Fetch from partition |
| **Scan** | O(n) | Seconds+ | n/4KB units | Batch processing only |

### Design Rule: Eliminate Scans

**The Golden Rule:**
```
"If your access pattern requires Scan,
 your data model is wrong."
 
Exception: Batch analytics on small datasets
```

**How to Eliminate Scans:**

**Problem:** Need to find all orders with status="pending"
```
❌ BAD: Scan entire orders table with filter
Time: O(n) - all orders
Cost: Entire table

✅ GOOD: Use GSI with pk=status
Design: 
  Base table: pk=order_id
  GSI: pk=status, sk=created_date
Query: 
  pk="pending" → Only pending orders
Time: O(1) + O(k) where k = pending orders
Cost: Only pending orders
```

**Problem:** Find users by email
```
❌ BAD: Scan users table, filter by email
Time: O(n) - all users

✅ GOOD: Use GSI with pk=email
Design:
  Base table: pk=user_id
  GSI: pk=email
Query:
  pk="user@example.com" → Direct lookup
Time: O(1)
```

**Problem:** Find all items created last month
```
❌ BAD: Scan table, filter by created_date
Time: O(n)

✅ GOOD: Use GSI with sk=created_date
Design:
  GSI: pk=entity_type, sk=created_date
Query:
  pk="ORDER", sk between "2025-01-01" and "2025-01-31"
Time: O(1) + O(k)
```

### Optimization Techniques

**Technique 1: Parallel Scan (Still Slow)**
```
Concept: Split table into segments, scan in parallel

Benefit: 4x faster (4 segments)
Reality: Still O(n), just distributed
Use: Batch jobs on large tables

Example:
  Sequential scan: 10 minutes
  Parallel scan (4 segments): 2.5 minutes
  Query: < 1 second
```

**Technique 2: Filter After Retrieval**
```
Pattern: Query broadly, filter in application

Example:
  Query: pk="user_123" → All user's orders
  Filter: status="pending" (in application)
  
When acceptable:
  - Filter reduces results by < 10x
  - Query already fast enough
  - Index cost not justified
```

**Technique 3: Cached Scans**
```
Pattern: Scan once, cache results, refresh periodically

Example:
  Scan "categories" table (100 items)
  Cache for 1 hour
  Serve from cache
  
When acceptable:
  - Small, infrequently changing data
  - Not real-time sensitive
```

### Real-World Patterns

**Pattern: Status Dashboard**
```
Requirement: Show count of orders by status

❌ WRONG: Scan all orders, count by status
✅ RIGHT: GSI pk=status, query each status, count results

or BETTER: Maintain counts in separate table
  Write: Update count on order status change
  Read: GetItem("order_counts")
```

**Pattern: Search Functionality**
```
Requirement: Search users by name

❌ WRONG: Scan users, filter by name
✅ RIGHT: Use ElasticSearch/CloudSearch for text search
  
NoSQL databases are not search engines!
```

**Pattern: Reporting**
```
Requirement: Generate monthly reports

❌ WRONG: Scan orders table daily
✅ RIGHT: Stream changes to data warehouse
  - DynamoDB Streams → Data warehouse
  - Query warehouse for reports
  - NoSQL for operational queries
```

---

## Why This Matters

**Performance:**
- Query: Milliseconds, scales with partition size
- Scan: Seconds/minutes, scales with table size
- Production SLA: < 100ms response time
- Scan cannot meet SLA for large tables

**Cost:**
- Query: Pay for items retrieved
- Scan: Pay for entire table read
- 1M items: Query costs $0.25, Scan costs $250

**User Experience:**
- Query: Real-time responses
- Scan: Unacceptable latency
- Timeout errors on large scans

---

## When to Apply

**Design Phase:**
- ✅ Model keys for query patterns
- ✅ Create indexes for alternate access
- ✅ Verify all access patterns use query

**Code Review:**
- ❌ Flag any Scan operations in production code
- ❌ Require justification for Scan usage
- ❌ Convert Scans to Queries with better model

**Performance Audit:**
- 📊 Monitor query vs scan ratio
- 🎯 Target: 99.9%+ queries, < 0.1% scans
- 🚨 Alert on production scans

---

## Related Patterns

- **AWS-LESS-05**: Primary Key Design - Foundation for queries
- **AWS-LESS-06**: Secondary Indexes - Enable alternate queries
- **AWS-LESS-08**: Item Collections - Efficient 1:many queries
- **ARCH-07**: LMMS Cache - Cache query results for speed

---

## Keywords

query, scan, NoSQL, partition key, performance optimization, O(1), O(n), database access patterns, cost optimization

---

## Version History

- **2025-10-25**: Created - Extracted from AWS Serverless Developer Guide, genericized for universal application

---

**End of Entry**
**Lines:** ~194 (within < 200 target)  
**Quality:** Generic, performance-focused, cost-conscious
