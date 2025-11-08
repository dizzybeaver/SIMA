# AWS-DynamoDB-AP-02-Over-Indexing.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Category:** AWS Platform > DynamoDB > Anti-Patterns  
**Purpose:** Anti-pattern of creating too many Global Secondary Indexes

---

## ANTI-PATTERN: Creating GSIs for Every Query Pattern

**Description:** Creating a Global Secondary Index (GSI) for every possible query pattern without considering alternatives.

**Why It's Wrong:**
- GSIs double storage cost (copy of data)
- GSIs double write cost (update base + GSI)
- Maintenance overhead (20 GSI limit per table)
- Complexity (managing multiple indexes)
- Slower writes (update all indexes)

**When Encountered:** During schema design when identifying multiple access patterns.

---

## THE PROBLEM

### Scenario: Device Management

**Requirements:**
```
1. Get device by deviceId (primary key)
2. List devices by userId
3. List devices by room
4. List devices by type
5. List devices by status
6. List devices by manufacturer
7. List offline devices
8. List recently updated devices
```

**Naive Approach (8 GSIs!):**
```python
# Table + 8 GSIs

# Base table
Primary Key: deviceId

# GSI-1: userId-index
GSI-1 PK: userId

# GSI-2: room-index
GSI-2 PK: room

# GSI-3: type-index
GSI-3 PK: deviceType

# GSI-4: status-index
GSI-4 PK: status

# GSI-5: manufacturer-index
GSI-5 PK: manufacturer

# GSI-6: offline-index
GSI-6 PK: isOffline

# GSI-7: recent-index
GSI-7 PK: lastUpdateDate

# GSI-8: combo-index
GSI-8 PK: userId, SK: room
```

**Impact:**
- Storage: 9x (base + 8 GSIs)
- Write cost: 9x (base + 8 GSIs)
- Monthly cost: $45/month (for 500 MB table)
- Complexity: 9 indexes to manage
- Write latency: Increased (9 updates per write)

---

## WHY IT'S WRONG

### 1. Storage Bloat

**Each GSI stores complete copy of attributes:**
```
Base table: 500 MB
GSI-1: 500 MB (if ProjectionType='ALL')
GSI-2: 500 MB
...
GSI-8: 500 MB
Total: 4.5 GB (9x storage)
```

**Cost Impact:**
- Storage: 4.5 GB × $0.25 = $1.13/month
- vs Base only: 0.5 GB × $0.25 = $0.13/month
- **9x storage cost**

---

### 2. Write Cost Multiplication

**Every write updates all GSIs:**
```python
# Single put_item with 8 GSIs
dynamodb.put_item(
    TableName='devices',
    Item={...}
)
# Actually performs:
# 1 write to base table = 1 WCU
# 8 writes to GSIs = 8 WCU
# Total: 9 WCU per item write
```

**Cost Impact:**
- 10,000 writes/month
- Without GSIs: 10,000 WCU
- With 8 GSIs: 90,000 WCU
- On-demand: $0.0125 → $0.11/month
- **9x write cost**

---

### 3. Write Latency

**Sequential GSI updates slow writes:**
```
Without GSIs: 5-10ms write latency
With 8 GSIs: 15-40ms write latency
Impact: 3-4x slower writes
```

---

### 4. Maintenance Complexity

**Managing 8+ GSIs:**
- Monitor 9 different indexes
- 9 sets of capacity metrics
- 9 potential throttling points
- Schema changes affect 9 indexes
- Backup/restore complexity

---

## THE RIGHT WAY

### Strategy 1: Overloaded Sort Key

**Concept:** Use sort key for multiple query patterns.

```python
# Single table + 1 GSI

# Base table
PK: userId
SK: device#{deviceId}

# GSI-1: Overloaded access patterns
GSI-1 PK: GSI1PK
GSI-1 SK: GSI1SK

# Store multiple patterns in GSI attributes
Item = {
    'userId': 'user-123',
    'deviceId': 'device-456',
    'SK': 'device#device-456',
    
    # Overloaded GSI-1 for multiple queries
    'GSI1PK': 'room#living-room',     # Query by room
    'GSI1SK': 'type#light#device-456', # Query by type within room
    
    # Attributes
    'room': 'living-room',
    'type': 'light',
    'status': 'online',
    'manufacturer': 'Philips'
}
```

**Query Patterns Supported:**
```python
# 1. List devices by userId (base table)
query(
    KeyConditionExpression='userId = :user'
)

# 2. List devices by room (GSI-1)
query(
    IndexName='GSI-1',
    KeyConditionExpression='GSI1PK = :room',
    ExpressionAttributeValues={':room': 'room#living-room'}
)

# 3. List devices by type in room (GSI-1)
query(
    IndexName='GSI-1',
    KeyConditionExpression='GSI1PK = :room AND begins_with(GSI1SK, :type)',
    ExpressionAttributeValues={
        ':room': 'room#living-room',
        ':type': 'type#light'
    }
)

# 4. List devices by manufacturer (filter on query)
query(
    IndexName='GSI-1',
    KeyConditionExpression='GSI1PK = :room',
    FilterExpression='manufacturer = :mfr',
    ExpressionAttributeValues={
        ':room': 'room#living-room',
        ':mfr': 'Philips'
    }
)
```

**Benefit:**
- Storage: 2x (base + 1 GSI) vs 9x (base + 8 GSIs)
- Write cost: 2 WCU vs 9 WCU (78% reduction)
- Complexity: 2 indexes vs 9 indexes

---

### Strategy 2: FilterExpression on Query

**Concept:** Query with broad key, filter in application.

**When Appropriate:**
- Low-cardinality attributes (status: online/offline)
- Rare queries (< 100/day)
- Small result sets (< 100 items)

```python
# No additional GSI needed

# Query by userId, filter by status
response = dynamodb.query(
    TableName='devices',
    KeyConditionExpression='userId = :user',
    FilterExpression='#status = :status',
    ExpressionAttributeNames={'#status': 'status'},
    ExpressionAttributeValues={
        ':user': 'user-123',
        ':status': 'offline'
    }
)
```

**Caveat:** Consumes RCUs for all items scanned, not just filtered results.

**Cost Analysis:**
```
User has 50 devices, 5 offline
Without filter: Query 50 items = 50 KB / 4 KB = 13 RCUs
With filter: Query 50 items = 13 RCUs (same)
Result: 5 items returned

Trade-off: Pay RCU for 50, get 5 (acceptable if query infrequent)
vs: Add GSI (doubles storage/writes for occasional query)
```

---

### Strategy 3: Composite Key Patterns

**Concept:** Combine attributes in sort key.

```python
# Item structure
Item = {
    'userId': 'user-123',
    'SK': 'room#living-room#type#light#device-456',
    # Enables hierarchical queries
}

# Query all devices in room
query(KeyConditionExpression='userId = :user AND begins_with(SK, :room)')

# Query devices by type in room
query(KeyConditionExpression='userId = :user AND begins_with(SK, :room_type)')
```

**Benefit:**
- Zero additional GSIs
- Hierarchical queries
- Fast and efficient

---

### Strategy 4: Accept Scan for Rare Queries

**Concept:** Use Scan for rarely-executed admin queries.

**When Appropriate:**
- Admin/analytics queries (< 10/day)
- Acceptable latency (seconds OK)
- Low total item count (< 10,000 items)

```python
# Scan for manufacturer (admin query, 5x/day)
response = dynamodb.scan(
    TableName='devices',
    FilterExpression='manufacturer = :mfr',
    ExpressionAttributeValues={':mfr': 'Philips'}
)
```

**Cost:**
- Scan 500 items (500 KB) = 125 RCUs
- 5 queries/day = 625 RCUs/day = ~20,000 RCUs/month
- On-demand: $0.005/month

vs GSI:
- Storage: +500 MB × $0.25 = $0.125/month
- Writes: +10,000/month × $1.25/million = $0.0125/month
- **Total GSI cost: $0.14/month**

**GSI costs 28x more than occasional Scan!**

---

## DECISION FRAMEWORK

### When to Create GSI

**Create GSI if:**
- Query executed frequently (> 100/day)
- Query spans many items (> 1000)
- Query needs low latency (< 50ms)
- Cannot use composite keys
- Write:read ratio < 1:10

**Example:** `userId-index` for user's device list (100+ queries/user/day)

### When NOT to Create GSI

**Avoid GSI if:**
- Query infrequent (< 10/day)
- Can use FilterExpression acceptably
- Can restructure with composite keys
- Scan acceptable for use case
- Write:read ratio > 1:1

**Example:** `manufacturer-index` for admin analytics (5 queries/day)

---

## REFACTORING APPROACH

### Before: 8 GSIs

```python
# 8 GSIs for 8 query patterns
# Storage: 4.5 GB
# Write cost: 9 WCU per item
# Monthly cost: ~$1.50
```

### After: 1 GSI + Composite Keys + Occasional Scan

```python
# Base table with composite SK
PK: userId
SK: room#X#type#Y#deviceId

# GSI-1 for inverted access
GSI-1 PK: room
GSI-1 SK: type#deviceId

# Rare queries use Scan
# - Manufacturer (admin, 5x/day)
# - Recently updated (dashboard, 10x/day)
```

**Result:**
- Storage: 1 GB (2x vs 9x) → 78% reduction
- Write cost: 2 WCU (vs 9 WCU) → 78% reduction
- Monthly cost: $0.30 (vs $1.50) → 80% savings
- Simpler: 2 indexes vs 9 indexes

---

## REAL-WORLD EXAMPLE: LEE Project

### Initial Design (Over-Indexed)

**6 GSIs created:**
1. userId-index (frequent)
2. room-index (frequent)
3. type-index (moderate)
4. status-index (infrequent)
5. lastUpdate-index (infrequent)
6. combined-index (redundant)

**Cost:**
- Storage: 3 GB (base 500 MB × 7)
- Write: 7 WCU per item
- Monthly: $0.85

### Refactored Design

**1 GSI + optimized structure:**
1. Base: userId + composite SK
2. GSI-1: room + composite SK
3. Removed: status, lastUpdate, combined (use filters/scans)

**Cost:**
- Storage: 1 GB (base 500 MB × 2)
- Write: 2 WCU per item
- Monthly: $0.30

**Savings: $0.55/month (65%) + reduced complexity**

---

## MONITORING

### Track GSI Usage

```python
def analyze_gsi_usage():
    """Identify underutilized GSIs."""
    import interface_metrics
    
    # Track queries per index
    gsi_queries = {
        'userId-index': 15000,     # High usage
        'room-index': 8000,        # High usage
        'type-index': 450,         # Medium usage
        'status-index': 12,        # LOW USAGE â†' candidate for removal
        'lastUpdate-index': 8,     # LOW USAGE â†' candidate for removal
        'combined-index': 3        # LOW USAGE â†' redundant
    }
    
    for gsi, count in gsi_queries.items():
        if count < 100:  # Less than 100 queries/month
            print(f"⚠️  {gsi} underutilized: {count} queries/month")
            print(f"   Consider removal (use Scan or FilterExpression)")
```

---

## BEST PRACTICES

### 1. Start with Minimal GSIs

**Approach:**
- Design table without GSIs
- Add GSIs as actual needs emerge
- Easier to add than remove

### 2. Measure Before Adding

**Questions before creating GSI:**
- How often is this query executed?
- What's current query cost (Scan/FilterExpression)?
- What would GSI cost (storage + writes)?
- Is GSI worth the cost?

### 3. Use Sparse Indexes

**Concept:** GSI only indexes items with specific attribute.

```python
# Sparse index: Only devices with issues
Item = {
    'deviceId': 'device-123',
    'status': 'online',
    # No 'hasIssue' attribute
}

Item_with_issue = {
    'deviceId': 'device-456',
    'status': 'offline',
    'hasIssue': 'yes',  # Only items with issues have this
}

# GSI on hasIssue only indexes items with attribute
# Smaller index, lower cost
```

---

## SUMMARY

**Anti-Pattern:** Creating GSI for every possible query pattern.

**Impact:**
- 9x storage cost
- 9x write cost
- 3-4x write latency
- Increased complexity

**Right Approach:**
- Use composite keys for multiple patterns
- Use FilterExpression for infrequent queries
- Accept Scan for rare admin queries
- Create GSI only for high-frequency, high-volume queries

**LEE Project Results:**
- Reduced 6 GSIs → 1 GSI
- 65% cost reduction ($0.85 → $0.30/month)
- Simpler architecture (2 indexes vs 7)
- Same functionality maintained

**Related:**
- AWS-DynamoDB-LESS-02 (Access pattern first methodology)
- AWS-DynamoDB-DEC-01 (NoSQL choice decision)

---

**END OF ANTI-PATTERN**

**Category:** Platform > AWS > DynamoDB  
**Anti-Pattern:** Over-indexing with too many GSIs  
**Impact:** 9x cost + unnecessary complexity  
**Status:** Production-validated (LEE project refactoring)
