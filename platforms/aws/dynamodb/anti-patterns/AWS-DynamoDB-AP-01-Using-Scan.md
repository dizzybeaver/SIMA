# AWS-DynamoDB-AP-01-Using-Scan.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Anti-pattern of using Scan operations for queries  
**Location:** `/sima/platforms/aws/dynamodb/anti-patterns/AWS-DynamoDB-AP-01-Using-Scan.md`

---

## ANTI-PATTERN

**Using Scan operations to retrieve data instead of designing proper keys and indexes for Query operations.**

**Severity:** Critical  
**Frequency:** Very common in applications migrated from relational databases  
**Impact:** 10-100x higher cost, 10-100x slower response times, poor scalability

---

## DESCRIPTION

**What It Is:**

Scan operation reads every item in table or index, optionally filtering results:

```python
# ANTI-PATTERN: Scanning entire table
response = table.scan(
    FilterExpression='userId = :userId',
    ExpressionAttributeValues={':userId': 'USER123'}
)
```

**Why It's Tempting:**

- Works like SQL `SELECT * FROM table WHERE condition`
- No schema design needed
- Flexible (can filter on any attribute)
- Quick to implement

**Why It's Wrong:**

- Reads ALL items (consumes RCUs for entire table)
- Filters after reading (wastes capacity)
- Slow for large tables (seconds vs milliseconds)
- Doesn't scale (gets slower as table grows)
- Expensive (pays for all items read, not just returned)

---

## EXAMPLE FROM PRODUCTION

### Scenario: User Device List

**Requirement:** Display list of user's devices

**Table:**
```
Partition Key: deviceId
Attributes: deviceName, userId, status, type
Size: 50,000 devices
```

---

### Wrong Implementation (SCAN)

```python
def get_user_devices_wrong(user_id):
    """
    ANTI-PATTERN: Scans entire table
    """
    response = table.scan(
        FilterExpression='userId = :userId',
        ExpressionAttributeValues={
            ':userId': user_id
        }
    )
    return response['Items']
```

**Performance Metrics:**

**First Call (50,000 devices in table, user has 10):**
- Items read: 50,000
- Items returned: 10
- RCUs consumed: 2,500 (50,000 items × 0.5 KB average ÷ 4 KB per RCU)
- Latency: 3,200ms
- Cost per call: $0.000625 (2,500 RCU × $0.25/million)

**After Growth (500,000 devices):**
- Items read: 500,000
- Items returned: 10
- RCUs consumed: 25,000
- Latency: 32,000ms (32 seconds!)
- Cost per call: $0.00625

**Problems:**
- 99.98% waste (10 items needed, 50,000 read)
- Linear degradation (10x data = 10x slower)
- Throttling risk (table provisioned for 1000 RCU/sec)
- User-facing timeout (32 seconds unacceptable)

---

### Right Implementation (QUERY with GSI)

**Schema Design:**
```
Base Table:
  Partition Key: deviceId
  
GSI (UserDevicesIndex):
  Partition Key: userId
  Sort Key: deviceId
  Projection: ALL
```

**Code:**
```python
def get_user_devices_right(user_id):
    """
    CORRECT: Queries GSI by userId
    """
    response = table.query(
        IndexName='UserDevicesIndex',
        KeyConditionExpression='userId = :userId',
        ExpressionAttributeValues={
            ':userId': user_id
        }
    )
    return response['Items']
```

**Performance Metrics:**

**With 500,000 devices in table (user has 10):**
- Items read: 10
- Items returned: 10
- RCUs consumed: 5 (10 items × 0.5 KB ÷ 4 KB per RCU)
- Latency: 5ms
- Cost per call: $0.00000125

**Improvements:**
- ✅ 5,000x fewer RCUs (5 vs 25,000)
- ✅ 6,400x faster (5ms vs 32,000ms)
- ✅ 5,000x cheaper ($0.00000125 vs $0.00625)
- ✅ Scales independently of table size

---

## REAL-WORLD IMPACT

### Case Study: LEE Project

**Initial Implementation:**
Used Scan to retrieve user devices and events.

**Symptoms:**
- Dashboard loading 15-30 seconds
- Frequent timeouts (Lambda 30s limit)
- $2,400/month DynamoDB costs (small application!)
- Cannot handle > 10 concurrent users

**Diagnosis:**
```
CloudWatch Metrics:
- ConsumedReadCapacityUnits: 15,000/sec (spike)
- ProvisionedReadCapacityUnits: 1,000/sec
- ReadThrottleEvents: 3,200/hour
- SuccessfulRequestLatency (Scan): 8,500ms avg
```

**Root Cause:**
- 12 Scan operations per dashboard load
- Each Scan read 20,000+ items
- Total: 240,000 items read to display 50 items

---

**Redesign:**
Implemented access-pattern-first design with proper keys.

**After Fix:**
- Dashboard loading: 200ms
- Zero timeouts
- $45/month DynamoDB costs
- Handles 1,000+ concurrent users
- Zero throttling

**Improvements:**
- ✅ 98% faster (200ms vs 15-30s)
- ✅ 98% cost reduction ($45 vs $2,400)
- ✅ 100x scalability increase

---

## WHY SCAN IS WRONG

### Reason 1: Reads Entire Table

**Scan Operation:**
```
1. DynamoDB reads item 1 → Check filter → Discard (wrong user)
2. Read item 2 → Check filter → Discard
...
49,990. Read item 49,990 → Check filter → Discard
49,991. Read item 49,991 → Check filter → Match! Return
...
50,000. Read item 50,000 → Check filter → Match! Return
```

**Charges:**
- You pay for ALL 50,000 items read
- Get back only 10 items
- 99.98% waste

**Query Operation (with proper key):**
```
1. DynamoDB uses key to locate partition
2. Reads only items in that partition (10 items)
3. Returns all 10 immediately
```

**Charges:**
- Pay for 10 items only
- 0% waste

---

### Reason 2: Performance Degrades Linearly

**Scan Performance vs Table Size:**

| Table Size | Scan Time | Query Time |
|------------|-----------|------------|
| 1,000 items | 100ms | 2ms |
| 10,000 items | 1,000ms | 2ms |
| 100,000 items | 10,000ms | 2ms |
| 1,000,000 items | 100,000ms | 2ms |

**Result:** Query scales, Scan doesn't

---

### Reason 3: Pagination Doesn't Help

**Common Misconception:**
"I'll paginate Scan to improve performance"

```python
# STILL WRONG: Paginated Scan
response = table.scan(
    FilterExpression='userId = :userId',
    Limit=100,  # Return max 100 items
    ExpressionAttributeValues={':userId': user_id}
)
```

**Reality:**
- `Limit` controls items evaluated, not returned
- Still reads all items until 100 matching items found
- Still pays for ALL items read
- Multiple round trips = even slower

**Better:**
- Proper key design + Query
- No pagination needed (reads only what matches)

---

### Reason 4: Parallel Scan Costs More

**Another Misconception:**
"I'll use parallel Scan for speed"

```python
# WRONG: Parallel Scan (multiple workers)
workers = 4
for segment in range(workers):
    scan_segment(table, segment, workers)
```

**Reality:**
- Reads entire table 1x (not 4x) but in parallel
- Still processes ALL items
- Faster but 1x cost (not 0.25x)
- Consumes 4x throughput simultaneously (throttling risk)

**Use Cases for Parallel Scan:**
- Backups (need every item anyway)
- Data export (need everything)
- Table migrations (one-time operation)

**Never Use For:**
- User-facing queries
- Real-time operations
- Frequent operations

---

## WHEN SCAN IS ACCEPTABLE

### Acceptable Use Case 1: Small Tables

**Criteria:**
- Table size < 100 items
- Growth limited (won't exceed 1,000 items)
- Infrequent access (< 10/hour)

**Example:** Application configuration table
```
Items: 50 config entries
Access: Once per Lambda cold start
Result: 25 RCU total (negligible)
```

---

### Acceptable Use Case 2: Analytics/Reports

**Criteria:**
- Runs infrequently (daily/weekly)
- Not user-facing (background job)
- Actually needs all items

**Example:** Daily device count report
```python
# Acceptable: Batch job runs once per day
response = table.scan(
    ProjectionExpression='#status',
    ExpressionAttributeNames={'#status': 'status'}
)
status_counts = count_by_status(response['Items'])
```

**Why Acceptable:**
- Runs 1x per day (not per user request)
- Actually needs all items (count by status)
- No alternative (Query won't work for global count)

---

### Acceptable Use Case 3: Data Migration

**Criteria:**
- One-time operation
- Offline/maintenance window
- Full table export needed

**Example:** Migrate table to new schema
```python
# Acceptable: One-time migration script
for page in paginate_scan(table):
    for item in page:
        new_item = transform(item)
        new_table.put_item(Item=new_item)
```

**Why Acceptable:**
- One-time operation (not recurring)
- Need every item (full migration)
- Performance not critical (offline)

---

## ALTERNATIVES TO SCAN

### Alternative 1: Query with Proper Keys

**When:** Access pattern is known

**Design:**
```
Identify access patterns first
Design keys to support patterns
Use Query instead of Scan
```

**Example:** See "Right Implementation" above

**Cost Reduction:** 100-5,000x

---

### Alternative 2: GSI for Alternative Access

**When:** Need to query by different attribute

**Design:**
```
Create GSI with target attribute as key
Query GSI instead of Scan base table
```

**Example:**
```
Base: (deviceId, ...)
GSI: (userId, deviceId)

Query GSI by userId → Get user's devices
```

**Cost Reduction:** 10-1,000x

---

### Alternative 3: FilterExpression on Query

**When:** Need additional filtering after Query

**Design:**
```
Query by key (gets subset)
Apply FilterExpression (further reduces)
```

**Example:**
```python
response = table.query(
    KeyConditionExpression='userId = :userId',
    FilterExpression='#status = :status',
    ExpressionAttributeNames={'#status': 'status'},
    ExpressionAttributeValues={
        ':userId': 'USER123',
        ':status': 'active'
    }
)
```

**Performance:** Much better than Scan (starts with key-based subset)

---

### Alternative 4: DynamoDB Streams + Aggregation

**When:** Need global statistics/counts

**Design:**
```
Process all changes via DynamoDB Streams
Maintain aggregate table
Query aggregate instead of Scan
```

**Example:**
```
Stream Handler:
  On device status change → Update status count in AggregateTable

Query:
  Get status counts from AggregateTable (1 Query)
  Instead of Scan entire Devices table
```

**Cost Reduction:** 1,000x+ for frequent queries

---

## DETECTION

### CloudWatch Metrics

**Alarm When:**
```
Metric: ConsumedReadCapacityUnits
Operation: Scan
Threshold: > 100/min

Alert: "Scan operation consuming excessive RCUs"
```

**Diagnosis:**
```
Check:
- SuccessfulRequestLatency (Scan) → High?
- UserErrors (Scan) → High?
- ConsumedReadCapacity (Scan) vs (Query)
```

---

### Code Audit

**Search For:**
```python
# Danger patterns in code
table.scan(
.scan(
FilterExpression=
```

**Review Each:**
- Is this user-facing?
- How large is the table?
- Could Query work instead?
- Is GSI needed?

---

## REFACTORING GUIDE

### Step 1: Identify All Scans

**Audit codebase:**
```bash
# Find all Scan operations
grep -r "\.scan(" src/
grep -r "scan(" src/
```

**Categorize:**
- Critical (user-facing, frequent)
- Important (less frequent)
- Acceptable (analytics, small tables)

---

### Step 2: Document Access Patterns

**For Each Scan:**
```markdown
## Scan Analysis

**Location:** `src/devices.py:45`
**Purpose:** Get user's devices
**Frequency:** 1000/day
**Latency:** 2-5 seconds
**Filter:** userId = X
**Items Read:** 50,000
**Items Returned:** 10
**Cost:** $0.000625 per call × 1000 = $0.625/day
```

---

### Step 3: Design Alternative

**Options:**

**A. Redesign Keys:**
- Add GSI with userId as partition key
- Query GSI instead of Scan

**B. Denormalize:**
- Store userId in sort key (composite)
- Enable Query on base table

**C. Stream Aggregation:**
- Maintain user→devices mapping in separate table
- Query mapping instead of Scan

---

### Step 4: Implement and Test

**Process:**
1. Create new GSI (if using that approach)
2. Backfill GSI (one-time Scan acceptable)
3. Update code to Query instead of Scan
4. Test with production-like data
5. Measure performance improvement
6. Deploy

---

### Step 5: Monitor Results

**Metrics to Track:**
- ConsumedReadCapacityUnits (should drop dramatically)
- SuccessfulRequestLatency (should improve 10-100x)
- Cost (should drop 90-99%)
- User satisfaction (faster page loads)

---

## LESSONS LEARNED

### Lesson 1: Scan Cost Compounds

**One Scan:** $0.000625 per 50k-item scan  
**1000 users/day:** $0.625/day = $19/month  
**10,000 users/day:** $6.25/day = $190/month  
**100,000 users/day:** $62.50/day = $1,900/month

**Proper Query:** $0.00000125 per call × 100,000 = $0.125/month

**Lesson:** Scan costs scale poorly, Query costs stay flat

---

### Lesson 2: Performance Degrades Over Time

**Initial Development (1,000 items):**
- Scan takes 50ms (acceptable)
- Developer doesn't notice problem

**6 Months Later (50,000 items):**
- Scan takes 2,500ms (unacceptable)
- Users complain
- Must redesign under pressure

**Lesson:** Design for Query from start, avoid painful redesign

---

### Lesson 3: "Temporary" Scans Become Permanent

**Pattern Observed:**
```python
# TODO: Replace with proper query when schema finalized
# TEMPORARY: Using scan for now
response = table.scan(...)
```

**Reality:**
- "Temporary" becomes permanent
- Schema "finalization" never happens
- Scan remains in production for years

**Lesson:** No temporary Scans, always design properly

---

## CROSS-REFERENCES

**DynamoDB Design:**
- AWS-DynamoDB-LESS-01: Partition Key Design (enables Query)
- AWS-DynamoDB-LESS-02: Access-Pattern-First (prevents Scan anti-pattern)
- AWS-DynamoDB-LESS-06: Secondary Index Strategies (GSI for Query)

**Performance:**
- AWS-Lambda-LESS-10: Performance Tuning (Scan causes slow Lambda)
- AWS-DynamoDB-Core-Concepts: Query vs Scan explained

---

## VERSION HISTORY

**v1.0.0 (2025-11-08):**
- Initial anti-pattern documentation
- LEE project case study included
- Performance metrics from production
- Refactoring guide created
- Detection methods documented

---

**END OF FILE**

**File:** AWS-DynamoDB-AP-01-Using-Scan.md  
**Lines:** 398 (within SIMAv4 limit)  
**Impact:** Prevented 98% cost increase, 100x performance improvement when avoided  
**Status:** Production-validated anti-pattern
