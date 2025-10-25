# SIMA v3 Support Tools - Quick Reference Card

**Print this page and keep it handy!**

---

## ⚡ SESSION START (Every Session - MANDATORY)

```
1. Upload: File-Server-URLs.md
2. Search: "SESSION-START-Quick-Context"
3. Wait: 30-45 seconds (load complete file)
4. Verify: Ask "What are the RED FLAGS?"
✅ Ready to work!
```

---

## 🚫 RED FLAGS (Never Suggest!)

| ❌ Never Do | Why | REF-ID |
|------------|-----|--------|
| Threading locks | Lambda single-threaded | DEC-04, AP-08 |
| Direct imports | Violates SIMA | RULE-01, AP-01 |
| Bare except | Swallows errors | AP-14 |
| Sentinel leaks | JSON fails | DEC-05, AP-19 |
| Subdirectories | Keep flat | DEC-08, AP-05 |
| Skip verification | Causes mistakes | AP-27, LESS-15 |

---

## 🎯 INSTANT ANSWERS (No Search Needed)

| Question | Answer | REF-ID |
|----------|--------|--------|
| Can I use threading? | NO | DEC-04 |
| Can I direct import? | NO | RULE-01 |
| Memory limit? | 128MB | DEC-07 |
| Cold start target? | < 3 seconds | ARCH-07 |
| Use bare except? | NO | AP-14 |
| Add subdirectories? | NO | DEC-08 |

---

## 🗺️ QUERY ROUTING MAP

| User Says | Route To | Time |
|-----------|----------|------|
| "Can I [X]?" | Workflow-05 | 15s |
| "Add [feature]" | Workflow-01 | 30s |
| "Error: [X]" | Workflow-02 | 30s |
| "Why [X]?" | Workflow-04 | 30s |
| "Cold start slow" | Workflow-08 | 30s |
| "Import error" | Workflow-07 | 30s |
| "Optimize [X]" | Workflow-06 | 30s |

---

## 📂 FILE QUICK ACCESS

### Anti-Pattern Check (5s)
```
/nmap/Support/AP-Checklist-Critical.md
```

### REF-ID Lookup (10s)
```
/nmap/Support/REF-ID-Directory-[PREFIX].md

Prefixes:
- DEC: Decisions
- AP: Anti-Patterns
- BUG: Bugs
- LESS: Lessons
- ARCH: Architecture
- INT: Interfaces
```

### Workflow Access (15s)
```
/nmap/Support/Workflow-[##]-[Name].md

Most used:
- 05: CanIQuestions
- 01: AddFeature
- 11: FetchFiles
- 02: ReportError
```

---

## 🔄 SIMA PATTERN (Always Follow)

```python
# ✅ CORRECT Pattern
import gateway
result = gateway.cache_get(key)

# ❌ WRONG Pattern
from cache_core import get_value
```

**3 Layers:**
```
Gateway → Interface → Core
(gateway_wrappers.py → interface_*.py → *_core.py)
```

---

## 🎯 12 CORE INTERFACES

| INT-## | Purpose | Example |
|--------|---------|---------|
| INT-01 | CACHE | cache_set, cache_get |
| INT-02 | LOGGING | log_info, log_error |
| INT-03 | SECURITY | encrypt, validate_token |
| INT-04 | METRICS | track_time, count |
| INT-05 | CONFIG | config_get, get_parameter |
| INT-06 | VALIDATION | validate_input |
| INT-07 | PERSISTENCE | save, load |
| INT-08 | COMMUNICATION | http_get, websocket |
| INT-09 | TRANSFORMATION | transform, parse |
| INT-10 | SCHEDULING | schedule, defer |
| INT-11 | MONITORING | health_check |
| INT-12 | ERROR_HANDLING | handle_error |

---

## ✅ VERIFICATION CHECKLIST (LESS-15)

Before suggesting any code:

```
☐ 1. Read complete file (LESS-01)
☐ 2. Verify SIMA pattern
   ☐ Gateway exists
   ☐ Interface follows pattern
   ☐ Implementation in core
☐ 3. Check anti-patterns
   ☐ No direct imports (AP-01)
   ☐ No threading (AP-08)
   ☐ No bare except (AP-14)
   ☐ No sentinel leaks (AP-19)
☐ 4. Verify dependencies
   ☐ No circular imports
   ☐ Follows dependency layers
   ☐ Total size < 128MB
☐ 5. Cite sources
   ☐ REF-IDs referenced
   ☐ File locations included
   ☐ Rationale explained
```

---

## 📊 TIME EXPECTATIONS

| Task | Time Target |
|------|-------------|
| SESSION-START load | 30-45s (one time) |
| Instant answer | 5s |
| Anti-pattern check | 5-10s |
| REF-ID lookup | 10s |
| Workflow routing | 10-15s |
| Simple query | 20-30s |
| Complex query | 30-60s |
| Complete feature | 10-20 min |

**Session savings:** 4-6 minutes per 10 queries

---

## 🔍 REF-ID PREFIX GUIDE

| Prefix | Meaning | Count | Example |
|--------|---------|-------|---------|
| ARCH | Architecture | 9 | ARCH-01 |
| RULE | Rules | 4 | RULE-01 |
| INT | Interface | 12 | INT-01 |
| DEC | Decision | 23+ | DEC-04 |
| AP | Anti-Pattern | 28 | AP-08 |
| BUG | Bug | 4+ | BUG-01 |
| LESS | Lesson | 21+ | LESS-15 |
| WISD | Wisdom | 5+ | WISD-01 |
| DEP | Dependency | 8 | DEP-01 |
| PATH | Pathway | 5 | PATH-01 |
| ERR | Error | 3 | ERR-02 |

---

## 🎯 TOP 20 REF-IDs (Keep in Memory)

**Critical:**
1. **RULE-01** - Gateway-only imports
2. **DEC-04** - No threading locks
3. **AP-01** - Direct imports prohibited
4. **AP-08** - Threading anti-pattern
5. **AP-14** - Bare except prohibited

**Essential:**
6. **DEC-05** - Sentinel sanitization
7. **DEC-08** - Flat file structure
8. **BUG-01** - Sentinel leak (535ms)
9. **LESS-01** - Read complete files
10. **LESS-15** - 5-step verification

**Important:**
11. **ARCH-01** - Gateway trinity
12. **ARCH-07** - LMMS system
13. **INT-01** - CACHE interface
14. **PATH-01** - Cold start pathway
15. **DEC-21** - SSM token-only

**Reference:**
16. **AP-19** - Sentinel crossing
17. **AP-27** - Skip verification
18. **BUG-02** - _CacheMiss validation
19. **LESS-02** - Measure first
20. **ERR-02** - Error propagation

---

## 💡 COMMON SCENARIOS

### "Can I do X?"
```
1. Check instant answers (SESSION-START)
2. If not there → Workflow-05
3. Check AP-Checklist-Critical
4. Answer with REF-ID citation
Time: 15-30s
```

### Add New Feature
```
1. Follow Workflow-01
2. Choose interface (INT-01 to INT-12)
3. Implement all 3 layers
4. Verify with LESS-15
Time: 10-20 min
```

### Debug Error
```
1. Follow Workflow-02
2. Check known bugs (NM06)
3. Trace through layers
4. Provide solution with citations
Time: 5-15 min
```

### Modify Code
```
1. Follow Workflow-11 (fetch files)
2. Read complete file (LESS-01)
3. Follow Workflow-03 (modify)
4. Verify with LESS-15
Time: 10-20 min
```

---

## 🚨 TROUBLESHOOTING

| Problem | Solution |
|---------|----------|
| SESSION-START won't load | Upload File-Server-URLs.md first |
| Slow responses | Verify SESSION-START loaded |
| RED FLAGS violated | Re-load SESSION-START |
| Can't find REF-ID | Use REF-ID-Directory-[PREFIX].md |
| Cross-refs broken | Check v3 paths (NM##/) |
| Workflow incomplete | Verify workflow file < 300 lines |

---

## 📱 EMERGENCY CONTACTS

**Key Files:**
- Session: `SESSION-START-Quick-Context.md`
- Critical: `AP-Checklist-Critical.md`
- Lookup: `REF-ID-DIRECTORY-HUB.md`
- Workflows: `WORKFLOWS-PLAYBOOK-HUB.md`

**Key Workflows:**
- Can I: `Workflow-05-CanIQuestions.md`
- Add: `Workflow-01-AddFeature.md`
- Error: `Workflow-02-ReportError.md`
- Fetch: `Workflow-11-FetchFiles.md`

**Key Directories:**
- Neural Maps: `/nmap/NM##/`
- Support Tools: `/nmap/Support/`
- Source Code: `/src/`

---

## 🎓 GOLDEN RULES (Never Forget)

1. **Load SESSION-START** every session
2. **Check RED FLAGS** before every solution
3. **Follow SIMA pattern** always (Gateway → Interface → Core)
4. **Read complete files** before modifying (LESS-01)
5. **Verify with LESS-15** before suggesting code
6. **Cite REF-IDs** in every response
7. **Use workflows** for common scenarios
8. **Respect constraints** (128MB, 3s cold start)

---

**END OF QUICK REFERENCE CARD**

**Version:** 1.0.0  
**Print & Keep Handy!**  
**Updated:** 2025-10-24
