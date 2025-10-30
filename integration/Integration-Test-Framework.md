# File: Integration-Test-Framework.md

**Version:** 1.0.0  
**Phase:** 7.0 - Integration  
**Date:** 2025-10-29  
**Purpose:** Comprehensive integration testing framework for SIMAv4

---

## OVERVIEW

This framework validates that all SIMAv4 components work together correctly:
- Core architecture entries integrate with gateway entries
- Interface entries reference correct patterns
- Language entries properly inherit from core
- Project NMPs correctly combine all layers
- Support tools function with all entry types
- Cross-references are valid and bidirectional
- Search and navigation work seamlessly

---

## TEST CATEGORIES

### Category 1: Entry Integration Tests
**Purpose:** Verify entries reference each other correctly

**Test 1.1: Core to Architecture Flow**
```
Given: ARCH-01 (SUGA Pattern)
When: Check inherits field
Then: Must reference relevant CORE entries
Verify: Referenced CORE entries exist and are accessible
```

**Test 1.2: Architecture to Interface Flow**
```
Given: INT-01 (Cache Interface)
When: Check cross-references
Then: Must reference ARCH entries it implements
Verify: Bidirectional references (ARCH also mentions INT)
```

**Test 1.3: Language to Core Flow**
```
Given: LANG-PY-01 (Python Idioms)
When: Check inherits field
Then: Must reference relevant CORE patterns
Verify: Adds value beyond obvious translation
```

**Test 1.4: Project NMP to All Layers**
```
Given: NMP01-LEE-02 (Cache Interface)
When: Check inherits field
Then: Must reference CORE + ARCH + LANG (if applicable)
Verify: Contains project-specific delta only
```

**Success Criteria:**
- âœ… All inherits fields have valid REF-IDs
- âœ… All referenced entries exist
- âœ… No orphaned entries (no incoming references)
- âœ… No circular references

---

### Category 2: Cross-Reference Validation
**Purpose:** Verify all cross-references are valid

**Test 2.1: Forward Reference Check**
```
For each entry:
  For each REF-ID in cross-references section:
    Verify: Referenced entry exists
    Verify: Referenced entry is accessible
    Verify: Reference is appropriate (same category or related)
```

**Test 2.2: Backward Reference Check**
```
For each entry A that references entry B:
  Verify: Entry B exists
  Optional: Entry B mentions A in "Referenced By" section
```

**Test 2.3: Reference Type Validation**
```
For each reference:
  Verify: Type is valid (inherits, see_also, related, contrast)
  Verify: Type usage is appropriate
  Verify: No conflicting references (inherit + contrast same entry)
```

**Success Criteria:**
- âœ… 100% of forward references valid
- âœ… 0% broken references
- âœ… All reference types used correctly

---

### Category 3: Gateway Routing Tests
**Purpose:** Verify gateway entries route correctly

**Test 3.1: Gateway to Category Routing**
```
Given: GATEWAY-CORE.md
When: User asks about caching pattern
Then: Should route to ARCH-01 (SUGA pattern)
Verify: Routing logic correct
Verify: Alternative routes documented
```

**Test 3.2: Gateway to Interface Routing**
```
Given: GATEWAY-ARCH.md
When: User asks about cache interface
Then: Should route to INT-01 (Cache Interface)
Verify: Correct interface selected
Verify: Dependencies documented
```

**Test 3.3: Gateway to Language Routing**
```
Given: GATEWAY-LANG.md
When: User asks about Python caching
Then: Should route to LANG-PY-03 (Exception Handling)
Verify: Language-specific guidance provided
```

**Success Criteria:**
- âœ… All routing paths work
- âœ… No dead-end routes
- âœ… Alternative routes provided

---

### Category 4: Support Tool Integration
**Purpose:** Verify support tools work with entries

**Test 4.1: Workflow Integration**
```
Given: WF-01 (Add Feature Workflow)
When: Following workflow steps
Then: All referenced tools exist
Verify: All referenced entries accessible
Verify: Workflow completes successfully
```

**Test 4.2: Checklist Integration**
```
Given: CHK-01 (Code Review Checklist)
When: Using checklist
Then: All referenced patterns exist
Verify: All anti-patterns referenced exist
Verify: Checklist items actionable
```

**Test 4.3: QRC Integration**
```
Given: QRC-01 (Interfaces Overview)
When: Using quick reference
Then: All interfaces listed exist
Verify: All REF-IDs valid
Verify: Information accurate
```

**Success Criteria:**
- âœ… All workflows executable
- âœ… All checklists complete
- âœ… All QRCs accurate

---

### Category 5: Search and Navigation
**Purpose:** Verify search finds correct entries

**Test 5.1: Keyword Search**
```
Search: "caching"
Expected Results:
  - CORE entries about caching
  - ARCH entries about caching in SUGA
  - INT entries (Cache Interface)
  - LANG entries (Python caching)
  - NMP entries (project-specific caching)
Verify: All relevant entries found
Verify: Results ordered by relevance
```

**Test 5.2: REF-ID Lookup**
```
Given: REF-ID "ARCH-01"
When: Using TOOL-01 (REF-ID Lookup)
Then: Direct link to entry
Verify: Entry loads correctly
Verify: Cross-references work
```

**Test 5.3: Category Navigation**
```
Given: "Show me all interface entries"
When: Navigate to interface index
Then: All 12 interfaces listed
Verify: Links work
Verify: Descriptions accurate
```

**Success Criteria:**
- âœ… Keyword search finds relevant entries
- âœ… REF-ID lookup works instantly
- âœ… Category navigation complete

---

### Category 6: Consistency Validation
**Purpose:** Verify consistency across entries

**Test 6.1: Naming Convention Check**
```
For each entry:
  Verify: Filename matches REF-ID
  Verify: REF-ID follows naming convention
  Verify: Title matches REF-ID description
```

**Test 6.2: Format Consistency Check**
```
For each entry:
  Verify: Has filename header (# File: ...)
  Verify: Has REF-ID in header metadata
  Verify: Has version number
  Verify: Has cross-references section
```

**Test 6.3: Content Quality Check**
```
For each entry:
  Verify: No duplicate content with inherited entries
  Verify: Adds unique value
  Verify: Length appropriate (< 200 lines)
  Verify: Examples provided where needed
```

**Success Criteria:**
- âœ… 100% naming convention compliance
- âœ… 100% format compliance
- âœ… 0% unnecessary duplication

---

### Category 7: End-to-End Scenarios
**Purpose:** Test complete user journeys

**Test 7.1: New Developer Onboarding**
```
Scenario: New developer needs to understand SUGA architecture
Steps:
  1. Load SESSION-START context
  2. Ask "What is SUGA?"
  3. Follow to ARCH-01 (SUGA Pattern)
  4. Follow to INT-01 through INT-12
  5. Check gateway implementation examples
Expected: Complete understanding with < 30 min
```

**Test 7.2: Feature Implementation**
```
Scenario: Developer adding new caching feature
Steps:
  1. Use WF-01 (Add Feature Workflow)
  2. Follow to ARCH-01 (SUGA Pattern)
  3. Check INT-01 (Cache Interface)
  4. Verify LANG-PY patterns
  5. Check NMP01-LEE-02 (project specifics)
  6. Use CHK-01 before commit
Expected: Feature implemented correctly
```

**Test 7.3: Bug Investigation**
```
Scenario: Developer debugging import error
Steps:
  1. Use WF-02 (Debug Issue Workflow)
  2. Check GATE-03 (Cross-Interface Communication)
  3. Verify dependency layers
  4. Check for anti-patterns
  5. Find solution
Expected: Bug root cause identified < 15 min
```

**Success Criteria:**
- âœ… All scenarios completable
- âœ… No missing information
- âœ… Time targets met

---

## AUTOMATED VALIDATION SCRIPT

```python
# integration_validator.py
# Run this to validate all integrations

import os
import re
from pathlib import Path
from typing import Dict, List, Set

class IntegrationValidator:
    def __init__(self, sima_root: Path):
        self.sima_root = sima_root
        self.entries = {}
        self.references = {}
        self.errors = []
        self.warnings = []
        
    def scan_all_entries(self):
        """Scan all SIMA entries and build reference map"""
        entry_dirs = [
            'entries/core',
            'entries/architectures',
            'entries/gateways', 
            'entries/interfaces',
            'entries/languages',
            'nmp'
        ]
        
        for dir_path in entry_dirs:
            full_path = self.sima_root / dir_path
            if full_path.exists():
                self._scan_directory(full_path)
    
    def _scan_directory(self, dir_path: Path):
        """Recursively scan directory for entries"""
        for file_path in dir_path.rglob('*.md'):
            self._parse_entry(file_path)
    
    def _parse_entry(self, file_path: Path):
        """Parse entry and extract metadata"""
        with open(file_path, 'r') as f:
            content = f.read()
        
        # Extract REF-ID
        ref_id_match = re.search(r'ref_id:\s*([A-Z0-9-]+)', content)
        if not ref_id_match:
            self.errors.append(f"No REF-ID in {file_path}")
            return
        
        ref_id = ref_id_match.group(1)
        
        # Extract inherits
        inherits = []
        inherits_match = re.search(r'inherits:\s*\[(.*?)\]', content, re.DOTALL)
        if inherits_match:
            inherits_str = inherits_match.group(1)
            inherits = [i.strip() for i in inherits_str.split(',')]
        
        # Extract cross-references
        cross_refs = self._extract_cross_refs(content)
        
        # Store entry data
        self.entries[ref_id] = {
            'path': file_path,
            'inherits': inherits,
            'cross_refs': cross_refs
        }
    
    def _extract_cross_refs(self, content: str) -> List[str]:
        """Extract all REF-ID references from content"""
        # Pattern matches REF-IDs like ARCH-01, INT-01, etc.
        pattern = r'\b([A-Z]{2,10}-(?:PY-)?[A-Z0-9]{2,10})\b'
        return list(set(re.findall(pattern, content)))
    
    def validate_references(self):
        """Validate all references are valid"""
        print("Validating references...")
        
        for ref_id, data in self.entries.items():
            # Check inherits
            for inherited in data['inherits']:
                if inherited not in self.entries:
                    self.errors.append(
                        f"{ref_id} inherits {inherited} which doesn't exist"
                    )
            
            # Check cross-refs
            for cross_ref in data['cross_refs']:
                if cross_ref != ref_id and cross_ref not in self.entries:
                    self.warnings.append(
                        f"{ref_id} references {cross_ref} which doesn't exist"
                    )
    
    def check_orphans(self):
        """Find entries with no incoming references"""
        print("Checking for orphaned entries...")
        
        referenced = set()
        for data in self.entries.values():
            referenced.update(data['inherits'])
            referenced.update(data['cross_refs'])
        
        # Core, Gateway, and Index entries don't need incoming refs
        exempt_patterns = ['CORE-', 'GATE-', 'INDEX-', 'GATEWAY-']
        
        for ref_id in self.entries.keys():
            is_exempt = any(ref_id.startswith(p) for p in exempt_patterns)
            if not is_exempt and ref_id not in referenced:
                self.warnings.append(f"{ref_id} has no incoming references")
    
    def check_circular_refs(self):
        """Detect circular reference chains"""
        print("Checking for circular references...")
        
        def has_cycle(ref_id: str, visited: Set[str]) -> bool:
            if ref_id in visited:
                return True
            visited.add(ref_id)
            
            if ref_id in self.entries:
                for inherited in self.entries[ref_id]['inherits']:
                    if has_cycle(inherited, visited.copy()):
                        return True
            return False
        
        for ref_id in self.entries.keys():
            if has_cycle(ref_id, set()):
                self.errors.append(f"Circular reference detected in {ref_id}")
    
    def check_file_naming(self):
        """Verify filenames match REF-IDs"""
        print("Checking file naming conventions...")
        
        for ref_id, data in self.entries.items():
            filename = data['path'].stem
            # Expected format: REF-ID-description.md
            if not filename.startswith(ref_id):
                self.errors.append(
                    f"Filename mismatch: {filename} should start with {ref_id}"
                )
    
    def generate_report(self) -> str:
        """Generate validation report"""
        report = []
        report.append("=" * 80)
        report.append("SIMA INTEGRATION VALIDATION REPORT")
        report.append("=" * 80)
        report.append(f"\nTotal Entries Scanned: {len(self.entries)}")
        report.append(f"Total References: {sum(len(d['cross_refs']) for d in self.entries.values())}")
        
        report.append(f"\n\nERRORS: {len(self.errors)}")
        if self.errors:
            for error in self.errors:
                report.append(f"  âŒ {error}")
        else:
            report.append("  âœ… No errors found!")
        
        report.append(f"\n\nWARNINGS: {len(self.warnings)}")
        if self.warnings:
            for warning in self.warnings[:10]:  # Show first 10
                report.append(f"  âš ï¸ {warning}")
            if len(self.warnings) > 10:
                report.append(f"  ... and {len(self.warnings) - 10} more warnings")
        else:
            report.append("  âœ… No warnings!")
        
        report.append("\n" + "=" * 80)
        report.append("VALIDATION COMPLETE")
        report.append("=" * 80)
        
        return "\n".join(report)
    
    def run_all_validations(self):
        """Run complete validation suite"""
        print("Starting SIMA Integration Validation...\n")
        
        self.scan_all_entries()
        self.validate_references()
        self.check_orphans()
        self.check_circular_refs()
        self.check_file_naming()
        
        report = self.generate_report()
        print(report)
        
        # Save report
        report_path = self.sima_root / 'validation_report.txt'
        with open(report_path, 'w') as f:
            f.write(report)
        print(f"\nReport saved to: {report_path}")
        
        return len(self.errors) == 0


# Usage
if __name__ == "__main__":
    import sys
    
    if len(sys.argv) < 2:
        print("Usage: python integration_validator.py /path/to/sima")
        sys.exit(1)
    
    sima_root = Path(sys.argv[1])
    validator = IntegrationValidator(sima_root)
    
    success = validator.run_all_validations()
    sys.exit(0 if success else 1)
```

---

## MANUAL VALIDATION CHECKLIST

Use this for manual spot-checks:

### Entry Quality Checks
- [ ] Pick 5 random ARCH entries
- [ ] Verify each has filename header
- [ ] Verify each has valid inherits field
- [ ] Verify each adds unique value
- [ ] Verify cross-references work

### Navigation Checks
- [ ] Load GATEWAY-CORE.md
- [ ] Follow 3 random links
- [ ] Verify all destinations exist
- [ ] Verify navigation is logical
- [ ] Verify no dead ends

### Tool Integration Checks
- [ ] Open WF-01 (Add Feature)
- [ ] Follow workflow steps
- [ ] Verify all referenced files exist
- [ ] Verify workflow completable
- [ ] Time to complete < 30 min

### Search Checks
- [ ] Search for "caching"
- [ ] Verify 5+ relevant results
- [ ] Verify results span all layers
- [ ] Verify results ordered logically

---

## SUCCESS CRITERIA

**Integration validation passes when:**

✅ **Zero Errors:**
- No broken references
- No missing entries
- No circular dependencies
- No naming violations

✅ **Minimal Warnings:**
- < 5% orphaned entries
- All warnings documented with reason

✅ **Performance:**
- Validation completes in < 5 minutes
- No timeout errors

✅ **Functionality:**
- All 7 test categories pass
- All manual checks pass
- End-to-end scenarios work

---

## CONTINUOUS INTEGRATION

**Run validation:**
- Before each commit (quick check)
- Before each release (full check)
- Weekly (automated)
- After adding new entries (targeted check)

**Quick Check (< 30 seconds):**
```bash
python integration_validator.py /path/to/sima --quick
```

**Full Check (< 5 minutes):**
```bash
python integration_validator.py /path/to/sima --full
```

**Targeted Check (< 1 minute):**
```bash
python integration_validator.py /path/to/sima --entry ARCH-01
```

---

**END OF INTEGRATION TEST FRAMEWORK**

**Version:** 1.0.0  
**Status:** Ready for use  
**REF-ID:** TEST-FRAMEWORK-01
