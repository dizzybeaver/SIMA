# REF-ID Complete Directory (SIMA v3)
# Alphabetical Quick Lookup - All 159+ References
# Version: 3.0.0 | Updated: 2025-10-24
# Purpose: Instant lookup of any REF-ID to exact file location (v3 paths)

---

## Purpose

When you see cross-references like "Related: BUG-01, DEC-05", use this directory to instantly find the exact v3 file location without searching.

**Time saved:** 10-20 seconds per cross-reference lookup

---

## A

### Architecture (ARCH-##)
**ARCH-01:** Gateway Trinity → NM01/NM01-Architecture-CoreArchitecture_Index.md  
**ARCH-02:** Gateway execution engine → NM01/NM01-Architecture-CoreArchitecture_Index.md  
**ARCH-03:** Router pattern → NM01/NM01-Architecture-CoreArchitecture_Index.md  
**ARCH-04:** Internal implementation pattern → NM01/NM01-Architecture-CoreArchitecture_Index.md  
**ARCH-05:** Extension architecture → NM01/NM01-Architecture-CoreArchitecture_Index.md  
**ARCH-06:** Lambda entry point → NM01/NM01-Architecture-CoreArchitecture_Index.md  
**ARCH-07:** LMMS System → NM01/NM01-ARCH-07-LMMS-Lambda Memory Management System.md  
**ARCH-08:** Future Experimental Architectures → NM01/NM01-ARCH-08_Future_Experimental Architectures.md  
**ARCH-09:** Module Size Limits → NM01/SUGA-Module-Size-Limits.md  

### Anti-Patterns (AP-##)

**Import Violations:**
**AP-01:** Direct cross-interface imports → NM05/NM05-AntiPatterns-Import_AP-01.md  
**AP-02:** Bypassing gateway layer → NM05/NM05-AntiPatterns-Import_AP-02.md  
**AP-03:** Circular dependencies → NM05/NM05-AntiPatterns-Import_AP-03.md  
**AP-04:** Breaking dependency layers → NM05/NM05-AntiPatterns-Import_AP-04.md  
**AP-05:** Subdirectories (except home_assistant/) → NM05/NM05-AntiPatterns-Import_AP-05.md  

**Implementation:**
**AP-06:** God objects → NM05/NM05-AntiPatterns-Implementation_AP-06.md  
**AP-07:** Large modules (>400 lines) → NM05/NM05-AntiPatterns-Implementation_AP-07.md  

**Concurrency:**
**AP-08:** Threading locks/primitives → NM05/NM05-AntiPatterns-Concurrency_AP-08.md  
**AP-11:** Race conditions → NM05/NM05-AntiPatterns-Concurrency_AP-11.md  
**AP-13:** Multiprocessing → NM05/NM05-AntiPatterns-Concurrency_AP-13.md  

**Dependencies:**
**AP-09:** Heavy dependencies without justification → NM05/NM05-AntiPatterns-Dependencies_AP-09.md  

**Critical:**
**AP-10:** Mutable default arguments → NM05/NM05-AntiPatterns-Critical_AP-10.md  

**Performance:**
**AP-12:** Premature optimization → NM05/NM05-AntiPatterns-Performance_AP-12.md  

**Error Handling:**
**AP-14:** Bare except clauses → NM05/NM05-AntiPatterns-ErrorHandling_AP-14.md  
**AP-15:** Swallowing exceptions → NM05/NM05-AntiPatterns-ErrorHandling_AP-15.md  
**AP-16:** No error context → NM05/NM05-AntiPatterns-ErrorHandling_AP-16.md  

**Security:**
**AP-17:** Hardcoded secrets → NM05/NM05-AntiPatterns-Security_AP-17.md  
**AP-18:** Logging sensitive data → NM05/NM05-AntiPatterns-Security_AP-18.md  
**AP-19:** Sentinel objects crossing boundaries → NM05/NM05-AntiPatterns-Security_AP-19.md  

**Quality:**
**AP-20:** Hardcoded configuration → NM05/NM05-AntiPatterns-Quality_AP-20.md  
**AP-21:** Magic numbers → NM05/NM05-AntiPatterns-Quality_AP-21.md  
**AP-22:** Copy-paste code → NM05/NM05-AntiPatterns-Quality_AP-22.md  

**Testing:**
**AP-23:** No unit tests → NM05/NM05-AntiPatterns-Testing_AP-23.md  
**AP-24:** Testing only success paths → NM05/NM05-AntiPatterns-Testing_AP-24.md  

**Documentation:**
**AP-25:** Undocumented decisions → NM05/NM05-AntiPatterns-Documentation_AP-25.md  
**AP-26:** Stale comments → NM05/NM05-AntiPatterns-Documentation_AP-26.md  

**Process:**
**AP-27:** Skipping verification protocol → NM05/NM05-AntiPatterns-Process_AP-27.md  
**AP-28:** Not reading complete files → NM05/NM05-AntiPatterns-Process_AP-28.md  

---

## B

### Bugs (BUG-##)
**BUG-01:** Sentinel leak (535ms penalty) → NM06/NM06-Bugs-Critical_BUG-01.md  
**BUG-02:** _CacheMiss sentinel validation → NM06/NM06-Bugs-Critical_BUG-02.md  
**BUG-03:** Cascading interface failures → NM06/NM06-Bugs-Critical_BUG-03.md  
**BUG-04:** Configuration parameter mismatch → NM06/NM06-Bugs-Critical_BUG-04.md  

---

## D

### Decisions (DEC-##)

**Architecture:**
**DEC-01:** SUGA pattern choice → NM04/NM04-Decisions-Architecture_DEC-01.md  
**DEC-02:** Gateway centralization → NM04/NM04-Decisions-Architecture_DEC-02.md  
**DEC-03:** Dispatch dictionary pattern → NM04/NM04-Decisions-Architecture_DEC-03.md  
**DEC-04:** No threading locks → NM04/NM04-Decisions-Architecture_DEC-04.md  
**DEC-05:** Sentinel sanitization → NM04/NM04-Decisions-Architecture_DEC-05.md  

**Technical:**
**DEC-12:** Memory management → NM04/NM04-Decisions-Technical_DEC-12.md  
**DEC-13:** Fast path caching → NM04/NM04-Decisions-Technical_DEC-13.md  
**DEC-14:** Lazy module loading → NM04/NM04-Decisions-Technical_DEC-14.md  
**DEC-15:** Router-level exceptions → NM04/NM04-Decisions-Technical_DEC-15.md  
**DEC-16:** Import error protection → NM04/NM04-Decisions-Technical_DEC-16.md  
**DEC-17:** Flat file structure → NM04/NM04-Decisions-Technical_DEC-17.md  
**DEC-18:** Standard library preference → NM04/NM04-Decisions-Technical_DEC
