# File: Critical-Index.md

**Category:** Anti-Patterns  
**Topic:** Critical  
**Items:** 1  
**Version:** 1.0.0

---

## FILE

### AP-10: Mutable Default Arguments
- **Severity:** 🔴 Critical
- **Problem:** Using mutable defaults (lists, dicts, objects)
- **Solution:** Use None as default, create new object in function
- **Impact:** BUG-01 (535ms penalty from sentinel leak)

---

**Common Forms:**
```python
def func(x, items=[]):     # ❌
def func(x, config={}):    # ❌
def func(x, obj=MyObj()):  # ❌

def func(x, items=None):   # ✅
```

---

**Keywords:** mutable defaults, Python gotcha, critical bug

**END OF INDEX**
