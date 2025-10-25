# NM05-AntiPatterns-Process_Index.md

# Anti-Patterns - Process Index

**Category:** NM05 - Anti-Patterns
**Topic:** Process
**Items:** 2
**Last Updated:** 2025-10-24

---

## Topic Overview

**Description:** Critical anti-patterns in development process and deployment workflow. These patterns represent the most dangerous procedural failures: developing without version control and deploying code without testing. Both patterns create catastrophic risks where a single mistake can cause irreversible damage or cascading production failures.

**Keywords:** process, version control, deployment, testing, workflow, safety, verification

**Priority Distribution:** 2 Critical

---

## Individual Files

### AP-27: No Version Control
- **File:** `NM05-AntiPatterns-Process_AP-27.md`
- **Summary:** Always use Git - commit frequently, push regularly, never work untracked
- **Priority:** 🔴 Critical
- **Impact:** Work loss, no rollback, no collaboration, no history, career risk

### AP-28: Deploying Untested Code
- **File:** `NM05-AntiPatterns-Process_AP-28.md`
- **Summary:** Never deploy without testing - run all tests, verify locally, check critical paths
- **Priority:** 🔴 Critical
- **Impact:** Production failures, data corruption, service outages, customer impact, emergency rollbacks

---

## Common Themes

Both process anti-patterns represent **catastrophic risk acceptance**. They're not about code quality or maintainability - they're about **professional survival** and **production stability**.

**The "Works on My Machine" Cascade:**

```
No Version Control + No Testing:
    â†"
Deploy to production
    â†"
Production breaks
    â†"
Can't rollback (no version control)
    â†"
Don't know what changed (no version control)
    â†"
Can't reproduce locally (no tests)
    â†"
Career-limiting event
```

**Version Control: Your Professional Safety Net**

AP-27 (No Version Control) is not about collaboration or process - it's about having an undo button for your career. Without version control:
- Laptop dies → Months of work gone
- Bad change → Can't undo
- Need to prove what changed → No evidence
- Deploy breaks → Can't rollback

**Testing: Your Production Safety Net**

AP-28 (Deploying Untested Code) is gambling with production stability. Every untested deployment is Russian roulette:
- 5 successful untested deploys → Confidence builds
- 1 failed untested deploy → Service outage, customer impact, emergency response

**Integration with Verification Protocol:**

These process anti-patterns are addressed systematically by **LESS-15** (5-step verification protocol):

1. Read Complete File ✅
2. Verify SIMA Pattern ✅
3. Check Anti-Patterns ✅  
4. Verify Dependencies ✅
5. **Run Tests Before Deploy** ← AP-28 enforced here

The verification protocol makes proper process mandatory, not optional.

---

## Related Topics

**Within NM05 (Anti-Patterns):**
- Testing patterns (AP-23, AP-24) - Tests enable safe deployment
- All anti-patterns - Process is where other patterns get enforced or ignored

**Other Categories:**
- **NM06-Lessons** - LESS-15 (5-step verification protocol)
- **NM04-Decisions** - Deployment and operational decisions
- **NM03-Operations** - Deployment pathways and validation
- **NM00-Guidelines** - Documentation and workflow standards

**SUGA-ISP Deployment Infrastructure:**
The project demonstrates proper process with:
- Git version control (GitHub)
- Comprehensive test suite (test_*.py files)
- Automated deployment (deploy_automation.py)
- Pre-deployment validation (LESS-15 protocol)
- Deployment verification (debug tools)

**Real-World Impact:**
These aren't theoretical concerns:
- BUG-01: Sentinel leak took significant debugging (would have been caught with proper testing)
- All design decisions (DEC-##) tracked in version control
- Every lesson learned (LESS-##) documented for future reference

The project's success depends on following these process patterns religiously.

---

**Navigation:**
- **Up:** [Category Index - NM05-AntiPatterns_Index.md]
- **Siblings:** [Import_Index, Implementation_Index, Dependencies_Index, Critical_Index, Concurrency_Index, Performance_Index, ErrorHandling_Index, Security_Index, Quality_Index, Testing_Index, Documentation_Index]

---

**End of Index**
