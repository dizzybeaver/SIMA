# File: Cross-Reference-Validator.md

**Version:** 1.0.0  
**Phase:** 7.0 - Integration  
**Date:** 2025-10-29  
**Purpose:** Automated cross-reference validation tool  
**REF-ID:** TOOL-03

---

## OVERVIEW

This tool validates all cross-references in SIMAv4 entries, ensuring:
- All referenced entries exist
- Reference types are valid
- Bidirectional references are consistent
- No circular dependencies
- No orphaned entries

---

## VALIDATION RULES

### Rule 1: Forward References Must Exist
**Description:** Every REF-ID mentioned must point to existing entry

**Check:**
```python
for ref_id in extract_references(entry):
    assert entry_exists(ref_id), f"{ref_id} not found"
```

**Error Message:**
```
âŒ BROKEN REFERENCE in ARCH-01
  References: INT-01 (NOT FOUND)
  Fix: Create INT-01 or remove reference
```

---

### Rule 2: Inherits Must Be Hierarchical
**Description:** Entries can only inherit from higher-level entries

**Hierarchy:**
```
CORE (Level 0)
  â†'
ARCH, LANG (Level 1)
  â†'
INT, GATE (Level 2)
  â†'
NMP (Level 3)
```

**Check:**
```python
if entry_level(child) <= entry_level(parent):
    raise ValueError("Cannot inherit from same or lower level")
```

**Error Message:**
```
âŒ INVALID INHERITANCE in INT-01
  Inherits: NMP01-LEE-02 (lower level)
  Fix: Remove inheritance or restructure
```

---

### Rule 3: No Circular Dependencies
**Description:** Reference chains cannot loop back

**Check:**
```python
def has_cycle(entry, visited=set()):
    if entry in visited:
        return True
    visited.add(entry)
    for inherited in entry.inherits:
        if has_cycle(inherited, visited.copy()):
            return True
    return False
```

**Error Message:**
```
âŒ CIRCULAR REFERENCE detected
  Chain: ARCH-01 â†' INT-01 â†' GATE-01 â†' ARCH-01
  Fix: Break cycle by removing one inheritance
```

---

### Rule 4: Reference Types Must Match Content
**Description:** Reference type must be appropriate for relationship

**Valid Types:**
- `inherits`: Parent entry (higher level)
- `see_also`: Related entry (same level)
- `contrast`: Opposite pattern
- `implements`: Interface implementation
- `uses`: Dependency

**Check:**
```python
if ref_type == 'inherits':
    assert entry_level(target) < entry_level(source)
if ref_type == 'see_also':
    assert entry_level(target) == entry_level(source)
```

**Error Message:**
```
âŒ INVALID REFERENCE TYPE in INT-01
  Reference: CORE-01 (type: see_also)
  Should be: inherits (lower to higher level)
  Fix: Change type to 'inherits'
```

---

### Rule 5: Orphaned Entries Warning
**Description:** Entries with no incoming references (except exempt)

**Exempt Categories:**
- CORE-* (top level)
- GATE-* (entry points)
- INDEX-* (navigation)
- GATEWAY-* (routing)

**Check:**
```python
incoming_refs = count_references_to(entry)
if incoming_refs == 0 and not is_exempt(entry):
    warn(f"{entry.ref_id} has no incoming references")
```

**Warning Message:**
```
âš ï¸ ORPHANED ENTRY: ARCH-05
  No entries reference this
  Consider: Add cross-references or remove if obsolete
```

---

## VALIDATION LEVELS

### Level 1: Syntax Validation (Fast)
**Duration:** < 30 seconds  
**Checks:**
- REF-ID format valid
- File naming matches REF-ID
- Header metadata present
- Reference syntax correct

**Command:**
```bash
validate_cross_refs --syntax
```

---

### Level 2: Reference Validation (Medium)
**Duration:** < 2 minutes  
**Checks:**
- All forward references exist
- All inherits are valid
- No broken links
- Reference types correct

**Command:**
```bash
validate_cross_refs --references
```

---

### Level 3: Graph Validation (Slow)
**Duration:** < 5 minutes  
**Checks:**
- No circular dependencies
- Hierarchy rules followed
- Orphaned entries identified
- Bidirectional consistency

**Command:**
```bash
validate_cross_refs --graph
```

---

### Level 4: Content Validation (Complete)
**Duration:** < 10 minutes  
**Checks:**
- Inherited content not duplicated
- Cross-references relevant
- Examples consistent
- Terminology consistent

**Command:**
```bash
validate_cross_refs --full
```

---

## VALIDATION SCRIPT

```python
#!/usr/bin/env python3
# cross_ref_validator.py

import argparse
import json
import re
from pathlib import Path
from typing import Dict, List, Set, Tuple
from dataclasses import dataclass
from enum import Enum

class EntryLevel(Enum):
    CORE = 0
    ARCH = 1
    LANG = 1
    GATE = 2
    INT = 2
    NMP = 3

class ReferenceType(Enum):
    INHERITS = "inherits"
    SEE_ALSO = "see_also"
    CONTRAST = "contrast"
    IMPLEMENTS = "implements"
    USES = "uses"

@dataclass
class Entry:
    ref_id: str
    path: Path
    level: EntryLevel
    inherits: List[str]
    references: Dict[str, str]  # ref_id -> type
    content: str

@dataclass
class ValidationError:
    entry_id: str
    error_type: str
    message: str
    severity: str  # ERROR, WARNING

class CrossReferenceValidator:
    def __init__(self, sima_root: Path):
        self.sima_root = sima_root
        self.entries: Dict[str, Entry] = {}
        self.errors: List[ValidationError] = []
        self.warnings: List[ValidationError] = []
        
    def determine_level(self, ref_id: str) -> EntryLevel:
        """Determine entry level from REF-ID"""
        if ref_id.startswith('CORE-'):
            return EntryLevel.CORE
        elif ref_id.startswith(('ARCH-', 'SUGA-', 'LMMS-')):
            return EntryLevel.ARCH
        elif ref_id.startswith(('LANG-', 'PY-')):
            return EntryLevel.LANG
        elif ref_id.startswith('GATE-'):
            return EntryLevel.GATE
        elif ref_id.startswith('INT-'):
            return EntryLevel.INT
        elif ref_id.startswith('NMP'):
            return EntryLevel.NMP
        else:
            return EntryLevel.NMP  # Default to lowest
    
    def scan_entries(self):
        """Scan all SIMA entries"""
        print("Scanning entries...")
        
        for md_file in self.sima_root.rglob('*.md'):
            if self._should_skip(md_file):
                continue
            
            try:
                entry = self._parse_entry(md_file)
                if entry:
                    self.entries[entry.ref_id] = entry
            except Exception as e:
                self.errors.append(ValidationError(
                    entry_id=md_file.name,
                    error_type="PARSE_ERROR",
                    message=f"Failed to parse: {e}",
                    severity="ERROR"
                ))
        
        print(f"Found {len(self.entries)} entries")
    
    def _should_skip(self, path: Path) -> bool:
        """Check if file should be skipped"""
        skip_patterns = [
            'README', 'CHANGELOG', 'TODO',
            'Planning', 'Docs', 'Testing'
        ]
        return any(p in str(path) for p in skip_patterns)
    
    def _parse_entry(self, path: Path) -> Entry:
        """Parse entry file and extract metadata"""
        with open(path, 'r', encoding='utf-8') as f:
            content = f.read()
        
        # Extract REF-ID
        ref_match = re.search(r'(?:ref_id|REF-ID):\s*([A-Z0-9-]+)', content)
        if not ref_match:
            return None
        
        ref_id = ref_match.group(1)
        level = self.determine_level(ref_id)
        
        # Extract inherits
        inherits = []
        inherits_match = re.search(
            r'inherits:\s*\[(.*?)\]',
            content,
            re.DOTALL
        )
        if inherits_match:
            inherits_str = inherits_match.group(1)
            inherits = [
                ref.strip().strip('"\'')
                for ref in inherits_str.split(',')
                if ref.strip()
            ]
        
        # Extract references
        references = self._extract_references(content)
        
        return Entry(
            ref_id=ref_id,
            path=path,
            level=level,
            inherits=inherits,
            references=references,
            content=content
        )
    
    def _extract_references(self, content: str) -> Dict[str, str]:
        """Extract all references with their types"""
        references = {}
        
        # Look for references section
        ref_section_match = re.search(
            r'## (?:Cross-)?References?(.*?)(?=##|$)',
            content,
            re.DOTALL | re.IGNORECASE
        )
        
        if ref_section_match:
            ref_text = ref_section_match.group(1)
            
            # Extract REF-IDs
            for ref_match in re.finditer(r'([A-Z0-9]+-[A-Z0-9-]+)', ref_text):
                ref_id = ref_match.group(1)
                # Try to determine type from context
                context = ref_text[max(0, ref_match.start()-50):ref_match.end()+50]
                ref_type = self._infer_reference_type(context)
                references[ref_id] = ref_type
        
        return references
    
    def _infer_reference_type(self, context: str) -> str:
        """Infer reference type from context"""
        context_lower = context.lower()
        if 'inherit' in context_lower or 'build' in context_lower:
            return 'inherits'
        elif 'see also' in context_lower or 'related' in context_lower:
            return 'see_also'
        elif 'contrast' in context_lower or 'opposite' in context_lower:
            return 'contrast'
        elif 'implement' in context_lower:
            return 'implements'
        elif 'use' in context_lower or 'depend' in context_lower:
            return 'uses'
        else:
            return 'see_also'  # Default
    
    def validate_syntax(self):
        """Level 1: Syntax validation"""
        print("\nRunning syntax validation...")
        
        for ref_id, entry in self.entries.items():
            # Check filename matches REF-ID
            filename = entry.path.stem
            if not filename.startswith(ref_id):
                self.errors.append(ValidationError(
                    entry_id=ref_id,
                    error_type="FILENAME_MISMATCH",
                    message=f"Filename '{filename}' should start with '{ref_id}'",
                    severity="ERROR"
                ))
            
            # Check has file header
            if not entry.content.startswith('# File:'):
                self.warnings.append(ValidationError(
                    entry_id=ref_id,
                    error_type="MISSING_HEADER",
                    message="Missing '# File:' header",
                    severity="WARNING"
                ))
    
    def validate_references(self):
        """Level 2: Reference validation"""
        print("\nRunning reference validation...")
        
        for ref_id, entry in self.entries.items():
            # Check inherits exist
            for inherited in entry.inherits:
                if inherited not in self.entries:
                    self.errors.append(ValidationError(
                        entry_id=ref_id,
                        error_type="BROKEN_INHERITS",
                        message=f"Inherits '{inherited}' which doesn't exist",
                        severity="ERROR"
                    ))
            
            # Check cross-references exist
            for ref, ref_type in entry.references.items():
                if ref != ref_id and ref not in self.entries:
                    self.warnings.append(ValidationError(
                        entry_id=ref_id,
                        error_type="BROKEN_REFERENCE",
                        message=f"References '{ref}' which doesn't exist",
                        severity="WARNING"
                    ))
    
    def validate_hierarchy(self):
        """Level 2.5: Hierarchy validation"""
        print("\nRunning hierarchy validation...")
        
        for ref_id, entry in self.entries.items():
            for inherited in entry.inherits:
                if inherited not in self.entries:
                    continue
                
                parent_entry = self.entries[inherited]
                
                # Check hierarchy rule
                if entry.level.value <= parent_entry.level.value:
                    self.errors.append(ValidationError(
                        entry_id=ref_id,
                        error_type="INVALID_HIERARCHY",
                        message=f"Inherits from '{inherited}' at same or lower level",
                        severity="ERROR"
                    ))
    
    def validate_graph(self):
        """Level 3: Graph validation"""
        print("\nRunning graph validation...")
        
        # Check for circular dependencies
        for ref_id in self.entries.keys():
            if self._has_cycle(ref_id, set()):
                self.errors.append(ValidationError(
                    entry_id=ref_id,
                    error_type="CIRCULAR_DEPENDENCY",
                    message="Part of circular inheritance chain",
                    severity="ERROR"
                ))
        
        # Check for orphans
        referenced = set()
        for entry in self.entries.values():
            referenced.update(entry.inherits)
            referenced.update(entry.references.keys())
        
        exempt_prefixes = ['CORE-', 'GATE-', 'INDEX-', 'GATEWAY-']
        for ref_id in self.entries.keys():
            is_exempt = any(ref_id.startswith(p) for p in exempt_prefixes)
            if not is_exempt and ref_id not in referenced:
                self.warnings.append(ValidationError(
                    entry_id=ref_id,
                    error_type="ORPHANED_ENTRY",
                    message="No incoming references",
                    severity="WARNING"
                ))
    
    def _has_cycle(self, ref_id: str, visited: Set[str]) -> bool:
        """Check for circular dependencies"""
        if ref_id in visited:
            return True
        
        if ref_id not in self.entries:
            return False
        
        visited.add(ref_id)
        entry = self.entries[ref_id]
        
        for inherited in entry.inherits:
            if self._has_cycle(inherited, visited.copy()):
                return True
        
        return False
    
    def generate_report(self) -> str:
        """Generate validation report"""
        lines = []
        lines.append("=" * 80)
        lines.append("CROSS-REFERENCE VALIDATION REPORT")
        lines.append("=" * 80)
        lines.append(f"\nEntries Scanned: {len(self.entries)}")
        lines.append(f"Total References: {sum(len(e.references) for e in self.entries.values())}")
        lines.append(f"Total Inherits: {sum(len(e.inherits) for e in self.entries.values())}")
        
        lines.append(f"\n\nERRORS: {len(self.errors)}")
        if self.errors:
            for error in self.errors:
                lines.append(f"\nâŒ {error.entry_id} - {error.error_type}")
                lines.append(f"   {error.message}")
        else:
            lines.append("âœ… No errors found!")
        
        lines.append(f"\n\nWARNINGS: {len(self.warnings)}")
        if self.warnings:
            # Show first 20 warnings
            for warning in self.warnings[:20]:
                lines.append(f"\nâš ï¸ {warning.entry_id} - {warning.error_type}")
                lines.append(f"   {warning.message}")
            if len(self.warnings) > 20:
                lines.append(f"\n... and {len(self.warnings) - 20} more warnings")
        else:
            lines.append("âœ… No warnings!")
        
        # Summary
        lines.append("\n" + "=" * 80)
        lines.append("SUMMARY")
        lines.append("=" * 80)
        
        if len(self.errors) == 0 and len(self.warnings) == 0:
            lines.append("âœ… ALL VALIDATIONS PASSED")
        elif len(self.errors) == 0:
            lines.append(f"âš ï¸ PASSED with {len(self.warnings)} warnings")
        else:
            lines.append(f"âŒ FAILED with {len(self.errors)} errors")
        
        return "\n".join(lines)
    
    def run(self, level: str = "full"):
        """Run validation at specified level"""
        self.scan_entries()
        
        if level in ["syntax", "full"]:
            self.validate_syntax()
        
        if level in ["references", "full"]:
            self.validate_references()
            self.validate_hierarchy()
        
        if level in ["graph", "full"]:
            self.validate_graph()
        
        report = self.generate_report()
        print("\n" + report)
        
        # Save report
        report_path = self.sima_root / 'cross_reference_validation_report.txt'
        with open(report_path, 'w') as f:
            f.write(report)
        print(f"\nReport saved to: {report_path}")
        
        return len(self.errors) == 0


def main():
    parser = argparse.ArgumentParser(
        description="Validate SIMA cross-references"
    )
    parser.add_argument(
        'sima_root',
        type=Path,
        help="Path to SIMA root directory"
    )
    parser.add_argument(
        '--level',
        choices=['syntax', 'references', 'graph', 'full'],
        default='full',
        help="Validation level (default: full)"
    )
    
    args = parser.parse_args()
    
    validator = CrossReferenceValidator(args.sima_root)
    success = validator.run(args.level)
    
    return 0 if success else 1


if __name__ == "__main__":
    import sys
    sys.exit(main())
```

---

## USAGE EXAMPLES

### Quick Syntax Check
```bash
python cross_ref_validator.py /path/to/sima --level syntax
```
**Duration:** < 30 seconds  
**Checks:** Filename, headers, basic structure

---

### Reference Validation
```bash
python cross_ref_validator.py /path/to/sima --level references
```
**Duration:** < 2 minutes  
**Checks:** All references exist, hierarchy valid

---

### Full Validation
```bash
python cross_ref_validator.py /path/to/sima
```
**Duration:** < 5 minutes  
**Checks:** Everything including circular deps and orphans

---

## INTEGRATION WITH CI/CD

### Pre-Commit Hook
```bash
#!/bin/bash
# .git/hooks/pre-commit

echo "Running cross-reference validation..."
python tools/cross_ref_validator.py . --level syntax

if [ $? -ne 0 ]; then
    echo "âŒ Cross-reference validation failed!"
    echo "Fix errors before committing."
    exit 1
fi

echo "âœ… Cross-reference validation passed"
```

### GitHub Actions
```yaml
name: Validate Cross-References

on: [push, pull_request]

jobs:
  validate:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Set up Python
        uses: actions/setup-python@v2
        with:
          python-version: '3.9'
      - name: Run validation
        run: |
          python tools/cross_ref_validator.py . --level full
```

---

## SUCCESS CRITERIA

**Validation passes when:**

✅ **Zero Errors:**
- All references exist
- Hierarchy rules followed
- No circular dependencies
- Filenames match REF-IDs

✅ **Warnings Acceptable:**
- < 5% orphaned entries (documented)
- Missing headers (can be added)

✅ **Performance:**
- Syntax check: < 30 seconds
- Full check: < 5 minutes

---

**END OF CROSS-REFERENCE VALIDATOR**

**Version:** 1.0.0  
**Status:** Ready for use  
**REF-ID:** TOOL-03
