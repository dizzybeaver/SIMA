# SIMA-Quick-Reference-Card.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Single-page quick reference for SIMA navigation  
**Category:** Quick Reference

---

## 🎯 NAVIGATION IN 30 SECONDS

### Start Points
- **Everything:** SIMA-Navigation-Hub.md
- **Relationships:** Master-Cross-Reference-Matrix.md
- **All Indexes:** Master-Index-of-Indexes.md

### By Intent
- **Learn:** Generic → Language → Platform → Project
- **Build:** Project → Language → Platform → Generic
- **Debug:** Bugs → Anti-Patterns → Lessons → Architecture
- **Research:** Cross-Reference → Deep Dives → Synthesis

---

## 📊 DOMAIN QUICK ACCESS

### Generic Knowledge
**Path:** `/sima/entries/`
- Anti-Patterns: Anti-Patterns-Master-Index.md
- Decisions: Decisions-Master-Index.md
- Lessons: Lessons-Master-Index.md
- Core: Core-Architecture-Quick-Index.md
- Gateways: Gateway-Quick-Index.md
- Interfaces: Interface-Quick-Index.md

### Platform Knowledge
**Path:** `/sima/platforms/aws/`
- Master: AWS-Master-Index.md
- Lambda: lambda/AWS-Lambda-Index.md
- API Gateway: api-gateway/AWS-APIGateway-Master-Index.md
- DynamoDB: dynamodb/indexes/AWS-DynamoDB-Master-Index.md

### Language Knowledge
**Path:** `/sima/languages/python/architectures/`
- SUGA: suga/indexes/suga-index-main.md (31 files)
- LMMS: lmms/indexes/lmms-index-main.md (17 files)
- ZAPH: zaph/Indexes/ZAPH-Decisions-Index.md (13 files)
- DD-1: dd-1/indexes/dd-1-index-main.md (8 files)
- DD-2: dd-2/indexes/dd-2-index-main.md (9 files)
- CR-1: cr-1/indexes/cr-1-index-main.md (6 files)

### Project Knowledge
**Path:** `/sima/projects/LEE/`
- Master: indexes/LEE-Index-Main.md
- NMP: nmp01/NMP00-LEE_Index.md
- Quick: nmp01/NMP01-LEE-Quick-Index.md
- Cross-Ref: nmp01/NMP01-LEE-Cross-Reference-Matrix.md

---

## 🔍 REF-ID QUICK LOOKUP

### Generic REF-IDs
- **ARCH-##** → /entries/core/ (4 files)
- **GATE-##** → /entries/gateways/ (5 files)
- **INT-##** → /entries/interfaces/ (12 files)
- **DEC-##** → /entries/decisions/ (24 files)
- **AP-##** → /entries/anti-patterns/ (28 files)
- **LESS-##** → /entries/lessons/ (50+ files)
- **BUG-##** → /entries/lessons/bugs/ (4 files)
- **WISD-##** → /entries/lessons/wisdom/ (6 files)

### Platform REF-IDs
- **AWS-Lambda-##** → /platforms/aws/lambda/
- **AWS-APIGateway-##** → /platforms/aws/api-gateway/
- **AWS-DynamoDB-##** → /platforms/aws/dynamodb/

### Architecture REF-IDs
- **SUGA-##** → /languages/python/architectures/suga/
- **LMMS-##** → /languages/python/architectures/lmms/
- **ZAPH-##** → /languages/python/architectures/zaph/
- **DD1-##** → /languages/python/architectures/dd-1/
- **DD2-##** → /languages/python/architectures/dd-2/
- **CR1-##** → /languages/python/architectures/cr-1/

### Project REF-IDs
- **LEE-##** → /projects/LEE/
- **NMP01-LEE-##** → /projects/LEE/nmp01/

---

## 🎨 COMMON LOOKUPS

### "How do I...?"
- Add feature → Workflow-01-Add-Feature.md
- Debug issue → Workflow-02-Debug-Issue.md
- Update interface → Workflow-03-Update-Interface.md
- Add gateway function → Workflow-04-Add-Gateway-Function.md
- Create NMP entry → Workflow-05-Create-NMP-Entry.md

### "Where's the...?"
- Architecture overview → SIMA-Navigation-Hub.md
- All indexes → Master-Index-of-Indexes.md
- Cross-references → Master-Cross-Reference-Matrix.md
- Anti-patterns → Anti-Patterns-Master-Index.md
- Decisions → Decisions-Master-Index.md
- Lessons → Lessons-Master-Index.md

### "What about...?"
- Threading → DEC-04, AP-08, AWS-Lambda-AP-01
- Cold start → LMMS (all 17 files)
- Memory → DEC-07, AWS-Lambda-DEC-02
- Performance → ZAPH (13 files), DD-1 (8 files)
- Dependencies → DD-2 (9 files), GATE-03
- Caching → INT-01, CR-1 (6 files)

### "Why can't I...?"
- Use threading → DEC-04, AP-08 (Lambda single-threaded)
- Direct import → AP-01, GATE-03 (Use gateway)
- Use subdirs → AP-05, DEC-08 (Flat structure)
- Skip verification → AP-27, LESS-15 (Always verify)

---

## 📚 ARCHITECTURE SUMMARY

### SUGA (Gateway Architecture)
**Purpose:** 3-layer gateway pattern  
**Files:** 31  
**Key:** Gateway → Interface → Core  
**Mandatory:** Always import via gateway

### LMMS (Lazy Module Management)
**Purpose:** Cold start optimization  
**Files:** 17  
**Key:** Function-level imports  
**Target:** < 3 second cold start

### ZAPH (Zone Access Priority)
**Purpose:** Performance tiering  
**Files:** 13  
**Key:** Tier 1 (hot) → Tier 2 → Tier 3 (cold)  
**Rule:** Measure before optimize

### DD-1 (Dictionary Dispatch)
**Purpose:** Function routing performance  
**Files:** 8  
**Key:** O(1) dict lookup vs O(n) if-else  
**Use:** 10+ routing targets

### DD-2 (Dependency Disciplines)
**Purpose:** Layer dependency management  
**Files:** 9  
**Key:** Higher → Lower only  
**Rule:** No circular dependencies

### CR-1 (Cache Registry)
**Purpose:** Consolidated gateway exports  
**Files:** 6  
**Key:** Central registry + wrappers  
**Benefit:** Single import point

---

## 🚀 WORKFLOW SHORTCUTS

### Development Cycle
1. **Plan:** Review architecture (SUGA, LMMS, etc.)
2. **Implement:** Follow templates (Workflow-01)
3. **Verify:** LESS-15 protocol
4. **Test:** Check anti-patterns
5. **Deploy:** Platform checklist (AWS)

### Debug Cycle
1. **Identify:** Error messages, symptoms
2. **Check:** BUG-01 through BUG-04
3. **Trace:** Through SUGA layers
4. **Fix:** Complete file, mark changes
5. **Verify:** Original failing case

### Learning Cycle
1. **Read:** Start with Quick Index
2. **Study:** Full entry
3. **Cross-ref:** Related topics
4. **Apply:** In practice
5. **Reflect:** Lessons learned

---

## 🎯 TOP 20 MUST-KNOW

### Critical Rules
1. RULE-01: Always import via gateway
2. DEC-04: No threading in Lambda
3. LESS-01: Read complete files first
4. LESS-15: Always verify with protocol

### Critical Patterns
5. SUGA: 3-layer gateway architecture
6. LMMS: Lazy function-level imports
7. ZAPH: Performance tiering
8. DD-1: Dictionary dispatch for routing

### Critical Constraints
9. 128MB memory limit (Lambda)
10. 30 second timeout (Lambda)
11. Single-threaded (Lambda)
12. < 3 second cold start target

### Critical Anti-Patterns
13. AP-01: Direct core imports (never!)
14. AP-08: Threading primitives (never!)
15. AP-14: Bare except (never!)
16. AP-27: Skip verification (never!)

### Critical Files
17. gateway.py: Central import point
18. fast_path.py: Hot path optimization
19. interface_*.py: 12 interfaces
20. *_core.py: Implementation

---

## 📊 STATISTICS AT A GLANCE

### File Counts
- **Total Documentation:** 481+ files
- **Total Indexes:** 50+ files
- **Generic:** 228+ files
- **Platform:** 62 files
- **Language:** 92 files (Python)
- **Project:** 37+ files (LEE)

### Domain Coverage
- **Architectures:** 6 (SUGA, LMMS, ZAPH, DD-1, DD-2, CR-1)
- **Platforms:** 1 (AWS with 3 services)
- **Languages:** 1 (Python)
- **Projects:** 1 (LEE)

### Knowledge Types
- **Decisions:** 24 generic + architecture-specific
- **Anti-Patterns:** 28 generic + architecture-specific
- **Lessons:** 50+ generic + architecture-specific
- **Bugs:** 4 documented
- **Wisdom:** 6 profound insights

---

## 🔗 ESSENTIAL LINKS

### Navigation
- SIMA-Navigation-Hub.md (start here)
- Master-Cross-Reference-Matrix.md (relationships)
- Master-Index-of-Indexes.md (all indexes)

### Support
- Support-Master-Index.md (tools, workflows, templates)
- Workflow-Index.md (5 workflows)
- Tools-Index.md (10+ tools)

### Context
- MODE-SELECTOR.md (choose work mode)
- Custom Instructions (mode behaviors)
- File specifications (SPEC-* files)

---

## ⚡ ONE-LINE ANSWERS

**What's SIMA?** Structured Intelligence Memory Architecture - knowledge management system

**What's SUGA?** Single Universal Gateway Architecture - 3-layer pattern (Gateway → Interface → Core)

**What's LEE?** Lambda Execution Engine - Home Assistant integration on AWS Lambda

**Why fileserver.php?** Bypasses Anthropic's aggressive file caching (gets fresh content)

**Why ≤400 lines?** project_knowledge_search truncates at 200+200 lines

**Why no threading?** Lambda is single-threaded (DEC-04)

**Why lazy imports?** Cold start optimization (LMMS)

**Why tiering?** Performance optimization (ZAPH)

**Why dictionary dispatch?** O(1) vs O(n) performance (DD-1)

**Why dependency disciplines?** Prevents circular imports (DD-2)

**Why cache registry?** Consolidates 100+ exports in gateway.py (CR-1)

---

**END OF QUICK REFERENCE CARD**

**Purpose:** Single-page navigation aid  
**Coverage:** All domains, all patterns  
**Lines:** 295 (within limit)
