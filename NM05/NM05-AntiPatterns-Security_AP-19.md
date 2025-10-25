# NM05-AntiPatterns-Security_AP-19.md - AP-19

# AP-19: SQL Injection Patterns

**Category:** NM05 - Anti-Patterns
**Topic:** Security
**Priority:** 🔴 CRITICAL
**Status:** Active
**Created:** 2025-10-24
**Last Updated:** 2025-10-24

---

## Summary

Building SQL queries using string formatting or concatenation with user input enables SQL injection attacks that can read, modify, or delete any data in the database.

---

## Context

SQL injection is one of the oldest and most dangerous web vulnerabilities. It occurs when untrusted input is directly inserted into SQL queries without proper sanitization or parameterization.

**Problem:** Complete database compromise, data theft, data destruction, unauthorized access.

---

## Content

### The Anti-Pattern

```python
# ❌ CRITICAL SQL INJECTION VULNERABILITY
def get_user(username):
    # String formatting with user input!
    query = f"SELECT * FROM users WHERE username = '{username}'"
    results = db.execute(query)
    return results

def delete_user(user_id):
    # String concatenation with user input!
    query = "DELETE FROM users WHERE id = " + str(user_id)
    db.execute(query)

def search_products(search_term):
    # .format() with user input!
    query = "SELECT * FROM products WHERE name LIKE '%{}%'".format(search_term)
    return db.execute(query)
```

**All of these are vulnerable to injection!**

### Real Attack Examples

**Attack 1: Authentication Bypass**
```python
# Vulnerable code:
def login(username, password):
    query = f"SELECT * FROM users WHERE username = '{username}' AND password = '{password}'"
    result = db.execute(query)
    return result is not None

# Attacker sends:
username = "admin' --"
password = "anything"

# Resulting query:
# SELECT * FROM users WHERE username = 'admin' --' AND password = 'anything'
# The -- comments out the rest, bypassing password check!
# Result: Logged in as admin without password!
```

**Attack 2: Data Exfiltration**
```python
# Vulnerable code:
def get_user(username):
    query = f"SELECT * FROM users WHERE username = '{username}'"
    return db.execute(query)

# Attacker sends:
username = "admin' UNION SELECT credit_card_number, ssn, password FROM sensitive_data --"

# Resulting query:
# SELECT * FROM users WHERE username = 'admin' 
# UNION SELECT credit_card_number, ssn, password FROM sensitive_data --'
# Result: Returns all credit cards and SSNs!
```

**Attack 3: Data Destruction**
```python
# Vulnerable code:
def delete_user(user_id):
    query = f"DELETE FROM users WHERE id = {user_id}"
    db.execute(query)

# Attacker sends:
user_id = "1; DROP TABLE users; --"

# Resulting query:
# DELETE FROM users WHERE id = 1; DROP TABLE users; --
# Result: Entire users table deleted!
```

**Attack 4: Privilege Escalation**
```python
# Attacker sends:
username = "regular_user'; UPDATE users SET role='admin' WHERE username='regular_user'; --"

# Result: Regular user becomes admin!
```

### Why This Is Critical

**1. Complete Database Access**
- Read any data
- Modify any data
- Delete any data
- Access other tables
- Call stored procedures

**2. Data Breach**
- Customer data stolen
- Credentials exposed
- Financial information leaked
- GDPR/PCI violations

**3. Data Destruction**
- Tables dropped
- Data deleted
- Database corrupted
- Unrecoverable loss

**4. System Compromise**
- Can escalate to OS commands
- Can read files (LOAD_FILE in MySQL)
- Can write files (INTO OUTFILE in MySQL)
- Complete server compromise

### Correct Approach

**Option 1: Parameterized Queries (BEST)**
```python
# ✅ CORRECT - Parameterized query
def get_user(username):
    # Use placeholders, not string formatting
    query = "SELECT * FROM users WHERE username = ?"
    results = db.execute(query, (username,))  # Parameters separate!
    return results

def delete_user(user_id):
    query = "DELETE FROM users WHERE id = ?"
    db.execute(query, (user_id,))

def search_products(search_term):
    query = "SELECT * FROM products WHERE name LIKE ?"
    return db.execute(query, (f"%{search_term}%",))  # Safe!
```

**Benefits:**
- ✅ Database driver handles escaping
- ✅ Impossible to inject SQL
- ✅ Works for all data types
- ✅ Better performance (query plan caching)

**Option 2: ORM (Object-Relational Mapping)**
```python
# ✅ CORRECT - Using SQLAlchemy ORM
from sqlalchemy import select
from models import User

def get_user(username):
    # ORM automatically parameterizes
    stmt = select(User).where(User.username == username)
    return session.execute(stmt).scalar_one_or_none()

def delete_user(user_id):
    user = session.query(User).filter(User.id == user_id).first()
    if user:
        session.delete(user)
        session.commit()
```

**Option 3: Query Builder**
```python
# ✅ CORRECT - Using query builder
from pypika import Query, Table

users = Table('users')

def get_user(username):
    query = Query.from_(users).select('*').where(users.username == username)
    # Query builder handles parameterization
    return db.execute(str(query), query.get_parameters())
```

### Parameter Syntax by Database

**SQLite:**
```python
cursor.execute("SELECT * FROM users WHERE id = ?", (user_id,))
```

**PostgreSQL (psycopg2):**
```python
cursor.execute("SELECT * FROM users WHERE id = %s", (user_id,))
```

**MySQL (mysql-connector):**
```python
cursor.execute("SELECT * FROM users WHERE id = %s", (user_id,))
```

**SQL Server (pyodbc):**
```python
cursor.execute("SELECT * FROM users WHERE id = ?", (user_id,))
```

### Common Mistakes

**Mistake 1: Parameterizing Values But Not Table/Column Names**
```python
# ❌ Still vulnerable!
table_name = user_input  # From user!
query = f"SELECT * FROM {table_name} WHERE id = ?"
db.execute(query, (user_id,))

# ✅ CORRECT - Whitelist table names
ALLOWED_TABLES = ['users', 'products', 'orders']
if table_name not in ALLOWED_TABLES:
    raise ValueError("Invalid table")
query = f"SELECT * FROM {table_name} WHERE id = ?"
```

**Mistake 2: Parameterizing String But Building LIKE Pattern Unsafely**
```python
# ❌ Still vulnerable!
search = f"%{user_input}%"  # If user_input contains %, _ 
query = "SELECT * FROM products WHERE name LIKE ?"
db.execute(query, (search,))

# ✅ CORRECT - Escape wildcards
def escape_like(value):
    return value.replace('%', '\\%').replace('_', '\\_')

search = f"%{escape_like(user_input)}%"
query = "SELECT * FROM products WHERE name LIKE ? ESCAPE '\\'"
```

**Mistake 3: Using Parameterization With IN Clause**
```python
# ❌ WRONG - Can't pass list directly
ids = [1, 2, 3]
query = f"SELECT * FROM users WHERE id IN (?)"
db.execute(query, (ids,))  # Doesn't work!

# ✅ CORRECT - Build placeholders dynamically
placeholders = ','.join('?' * len(ids))
query = f"SELECT * FROM users WHERE id IN ({placeholders})"
db.execute(query, ids)
```

### Detection

**Code Review Red Flags:**
```python
# All of these are dangerous:
f"SELECT * FROM table WHERE {column} = '{value}'"
"SELECT * FROM table WHERE col = " + str(value)
"SELECT * FROM table WHERE col = {}".format(value)
"SELECT * FROM table WHERE col = '%s'" % value
```

**Automated Detection:**
```bash
# Find potential SQL injection
grep -r "f\".*SELECT" *.py
grep -r "\.format(" *.py | grep -i "SELECT"
grep -r "SELECT.*+" *.py
```

### Defense in Depth

**Layer 1: Parameterized Queries (This anti-pattern)**
```python
# Always use parameterized queries
query = "SELECT * FROM users WHERE id = ?"
db.execute(query, (user_id,))
```

**Layer 2: Input Validation**
```python
# Validate input type and format
if not isinstance(user_id, int):
    raise TypeError("user_id must be int")
```

**Layer 3: Least Privilege**
```sql
-- Database user has minimal permissions
GRANT SELECT, INSERT, UPDATE ON app_database.* TO 'app_user'@'localhost';
-- NO DROP, CREATE, ALTER permissions
```

**Layer 4: Web Application Firewall (WAF)**
```
# Detect and block SQL injection attempts
# at network layer before reaching application
```

### SUGA-ISP Note

**SUGA-ISP doesn't use SQL databases,** but if you were to add database support:

```python
# ✅ CORRECT pattern for SUGA-ISP
import gateway

def query_database(user_id):
    # Gateway handles parameterization
    return gateway.db_query(
        "SELECT * FROM users WHERE id = ?",
        params=(user_id,)
    )
```

---

## Related Topics

- **AP-17**: No Input Validation - Validate before querying
- **AP-18**: Hardcoded Secrets - Database credentials security
- **DEC-11**: Security-First Design - Why security is built-in
- **INT-03**: SECURITY Interface - Security validation functions

---

## Keywords

SQL injection, parameterized queries, database security, string formatting, query injection, prepared statements, ORM, SQLAlchemy

---

## Version History

- **2025-10-24**: Created - Extracted from Part 2, added attack examples and defense layers

---

**File:** `NM05-AntiPatterns-Security_AP-19.md`
**End of Document**
