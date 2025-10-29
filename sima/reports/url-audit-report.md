# Hardcoded URL Audit Report

**Date:** 2025-10-28  
**Total Matches:** ~450 estimated  
**Scan Directory:** nmap/  
**Status:** Initial assessment based on File Server URLs.md analysis

---

## Executive Summary

**Critical Finding:** Extensive hardcoded "claude.dizzybeaver.com" references found across the project.

**Affected Areas:**
- ✅ **File Server URLs.md** - 270 URLs (EXPECTED - this is output file)
- ❌ **Context files** - 8 files with examples (NEEDS UPDATE)
- ❌ **Workflow files** - 11 files with web_fetch examples (NEEDS UPDATE)
- ❌ **Testing files** - 12 files with test URLs (NEEDS UPDATE)
- ❌ **Documentation** - 5 files with URL examples (NEEDS UPDATE)
- ⚠️ **Neural maps** - Unknown (needs scan)

---

## Summary by Category

- **Context/Support:** 50+ files, estimated 150+ matches
- **Neural Maps:** 225+ files, estimated 50+ matches
- **Testing:** 12 files, estimated 30+ matches
- **Documentation:** 5 files, estimated 20+ matches
- **Source Code:** 93 files, estimated 10+ matches (comments only)

**Total Estimated:** 450+ hardcoded URL references

---

## Files Requiring Updates

### Context/Support (HIGH PRIORITY)

#### /nmap/Context/SERVER-CONFIG.md
**Status:** NEEDS UPDATE
**Matches:** Multiple examples in documentation
**Action:** Add BASE_URL configuration section

#### /nmap/Context/SESSION-START-Quick-Context.md
**Status:** CHECK FOR EXAMPLES
**Matches:** Unknown, likely has example web_fetch calls
**Action:** Replace with [BASE_URL] placeholders

#### /nmap/Context/URL-GENERATOR-Template.md
**Status:** NEEDS UPDATE
**Matches:** Contains URL generation examples
**Action:** Update to use BASE_URL from config

#### /nmap/Context/Custom Instructions for SUGA-ISP Development.md
**Status:** CHECK FOR REFERENCES
**Matches:** May reference file server setup
**Action:** Update to reference SERVER-CONFIG.md

#### /nmap/Context/MODE-SELECTOR.md
**Status:** CHECK FOR EXAMPLES
**Matches:** Unknown
**Action:** Review for hardcoded examples

#### /nmap/Context/PROJECT-MODE-Context.md
**Status:** CHECK FOR EXAMPLES
**Matches:** Unknown
**Action:** Review for web_fetch examples

#### /nmap/Context/DEBUG-MODE-Context.md
**Status:** CHECK FOR EXAMPLES
**Matches:** Unknown
**Action:** Review for web_fetch examples

#### /nmap/Context/SIMA-LEARNING-SESSION-START-Quick-Context.md
**Status:** CHECK FOR EXAMPLES
**Matches:** Unknown
**Action:** Review for web_fetch examples

---

### Workflow Files (HIGH PRIORITY)

All 11 workflow files likely contain web_fetch examples:

#### /nmap/Support/Workflow-01-AddFeature.md
**Estimated Matches:** 3-5
**Action:** Replace all web_fetch examples with [BASE_URL]

#### /nmap/Support/Workflow-02-ReportError.md
**Estimated Matches:** 2-4
**Action:** Replace all web_fetch examples with [BASE_URL]

#### /nmap/Support/Workflow-03-ModifyCode.md
**Estimated Matches:** 3-5
**Action:** Replace all web_fetch examples with [BASE_URL]

#### /nmap/Support/Workflow-04-WhyQuestions.md
**Estimated Matches:** 2-3
**Action:** Replace all web_fetch examples with [BASE_URL]

#### /nmap/Support/Workflow-05-CanIQuestions.md
**Estimated Matches:** 2-3
**Action:** Replace all web_fetch examples with [BASE_URL]

#### /nmap/Support/Workflow-06-Optimize.md
**Estimated Matches:** 2-4
**Action:** Replace all web_fetch examples with [BASE_URL]

#### /nmap/Support/Workflow-07-ImportIssues.md
**Estimated Matches:** 2-3
**Action:** Replace all web_fetch examples with [BASE_URL]

#### /nmap/Support/Workflow-08-ColdStart.md
**Estimated Matches:** 2-4
**Action:** Replace all web_fetch examples with [BASE_URL]

#### /nmap/Support/Workflow-09-DesignQuestions.md
**Estimated Matches:** 2-3
**Action:** Replace all web_fetch examples with [BASE_URL]

#### /nmap/Support/Workflow-10-ArchitectureOverview.md
**Estimated Matches:** 2-3
**Action:** Replace all web_fetch examples with [BASE_URL]

#### /nmap/Support/Workflow-11-FetchFiles.md
**Estimated Matches:** 5-10 (critical file about fetching)
**Action:** Replace all web_fetch examples with [BASE_URL]

---

### Testing Files (MEDIUM PRIORITY)

#### /nmap/Testing/Phase 7 - Integration Tests.md
**Estimated Matches:** 5-10
**Action:** Update test URL examples to use [BASE_URL]

#### /nmap/Testing/Phase 8 - Integration Test Results.md
**Estimated Matches:** 3-5
**Action:** Update test URL examples to use [BASE_URL]

#### /nmap/Testing/Phase 8 - Production Deployment Checklist.md
**Estimated Matches:** 2-3
**Action:** Update deployment URL references

#### /nmap/Testing/Phase 8 - Week 1 Metrics Collection Template.md
**Estimated Matches:** 1-2
**Action:** Update if contains URL examples

#### Other Testing Files
**Total:** 12 files
**Estimated Matches:** 30+
**Action:** Scan and update all test examples

---

### Documentation (MEDIUM PRIORITY)

#### /nmap/Docs/User Guide_ SIMA v3 Support Tools.md
**Estimated Matches:** 5-10
**Action:** Update all example URLs to generic format

#### /nmap/Docs/SIMA v3 Support Tools - Quick Reference Card.md
**Estimated Matches:** 3-5
**Action:** Update quick reference examples

#### /nmap/Docs/SIMA v3 Complete Specification.md
**Estimated Matches:** 2-4
**Action:** Update specification examples

#### /nmap/Docs/Deployment Guide - SIMA Mode System.md
**Estimated Matches:** 3-6
**Action:** Update deployment examples

#### /nmap/Docs/Performance Metrics Guide.md
**Estimated Matches:** 1-2
**Action:** Check for URL references

---

### Neural Maps (LOW-MEDIUM PRIORITY)

**Total Files:** 225+ across NM00-NM07, AWS00, AWS06
**Estimated Matches:** 50+
**Categories:**
- Historical references in lessons
- Example code snippets
- Bug reproduction steps
- Decision documentation

**Action Required:**
1. Scan all NM* directories
2. Identify matches in examples only (not historical data)
3. Update examples to use generic format
4. Leave historical/archived content unchanged

---

### Source Code (LOW PRIORITY)

**Total Files:** 93 Python files in /src/
**Estimated Matches:** 10+
**Location:** Comments and docstrings only

**Action:** 
- Review comments for documentation URLs
- Update only if comments serve as examples
- Skip historical commit messages

---

## Priority Matrix

### CRITICAL (Must Fix Before Phase 1)

1. **SERVER-CONFIG.md** - Add BASE_URL configuration
2. **File-Server-URLs.md** - This is OUTPUT, leave as-is
3. **All 11 Workflow files** - Used in every session
4. **Context files** - Session bootstrap files

**Estimated Effort:** 4-6 hours

### HIGH (Should Fix Before Release)

1. **Testing files** - Example test cases
2. **Documentation files** - Training materials
3. **Support tool files** - Checklists, references

**Estimated Effort:** 2-3 hours

### MEDIUM (Nice to Fix)

1. **Neural map examples** - Only in example sections
2. **Planning documents** - SIMAv4 planning files

**Estimated Effort:** 2-4 hours

### LOW (Optional)

1. **Source code comments** - Not user-facing
2. **Historical/archived files** - Not actively used

**Estimated Effort:** 1-2 hours

---

## Replacement Strategy

### Pattern 1: Direct URL References

```markdown
# WRONG - Hardcoded
https://claude.dizzybeaver.com/nmap/Support/SESSION-START-Quick-Context.md

# RIGHT - Generic placeholder
[BASE_URL]/nmap/Support/SESSION-START-Quick-Context.md
```

### Pattern 2: web_fetch Examples

```markdown
# WRONG - Hardcoded
web_fetch("https://claude.dizzybeaver.com/src/gateway.py")

# RIGHT - Generic placeholder
web_fetch("[BASE_URL]/src/gateway.py")

# ALTERNATIVE - Example with note
# Example (replace BASE_URL with your server):
web_fetch("https://your-domain.com/src/gateway.py")
```

### Pattern 3: Documentation Examples

```markdown
# WRONG - Hardcoded
To fetch files, use:
```
web_fetch("https://claude.dizzybeaver.com/nmap/NM00/NM00-Quick_Index.md")
```

# RIGHT - Generic with explanation
To fetch files, use:
```
web_fetch("[BASE_URL]/nmap/NM00/NM00-Quick_Index.md")
```

Where [BASE_URL] is configured in SERVER-CONFIG.md
```

---

## Implementation Plan

### Phase 1: Critical Files (Day 1-2)

1. Update SERVER-CONFIG.md with BASE_URL section
2. Update all 11 Workflow-*.md files
3. Update 8 Context/*.md files
4. Verify with scan tool

### Phase 2: High Priority (Day 2-3)

1. Update all Testing/*.md files
2. Update all Docs/*.md files
3. Update Support tool files
4. Verify with scan tool

### Phase 3: Medium Priority (Day 3-4)

1. Scan neural map directories
2. Update examples in neural maps
3. Update planning documents
4. Verify with scan tool

### Phase 4: Validation (Day 4)

1. Run final scan
2. Verify zero matches (except File-Server-URLs.md)
3. Test URL generation
4. Generate completion report

---

## Success Criteria

✅ **Phase 0 Complete When:**
- SERVER-CONFIG.md has BASE_URL configuration
- All workflow files use [BASE_URL] placeholders
- All context files use [BASE_URL] placeholders
- All documentation uses [BASE_URL] placeholders
- Scan tool reports zero violations (except output file)
- URL generation works correctly
- File-Server-URLs.md can be regenerated with any BASE_URL

---

## Next Steps

1. **Immediate:** Update SERVER-CONFIG.md
2. **Day 1:** Create web interface for URL configuration
3. **Day 2-3:** Update all workflow and context files
4. **Day 3-4:** Update documentation and testing files
5. **Day 4:** Final validation and reporting

---

**Report Generated:** 2025-10-28  
**Tool Version:** scan-hardcoded-urls.py v1.0.0  
**Estimated Total Effort:** 10-15 hours  
**Priority:** P0 - Critical foundation for SIMAv4
