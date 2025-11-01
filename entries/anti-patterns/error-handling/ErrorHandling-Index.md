# File: ErrorHandling-Index.md

**Category:** Anti-Patterns  
**Topic:** Error Handling  
**Items:** 3  
**Version:** 1.0.0

---

## FILES

### AP-14: Bare Except Clauses
- **Severity:** 🟠 High
- **Problem:** `except:` catches everything including system signals
- **Solution:** Use specific exception types

### AP-15: Swallowing Exceptions
- **Severity:** 🟠 High
- **Problem:** `except: pass` hides failures
- **Solution:** Log and handle appropriately

### AP-16: No Error Context
- **Severity:** 🟡 Medium
- **Problem:** Raising new exceptions loses original context
- **Solution:** Use `raise ... from e` for chaining

---

**Common Theme:** Proper error handling preserves information and enables debugging.

---

**Keywords:** exception handling, errors, debugging

**END OF INDEX**
