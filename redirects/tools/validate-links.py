# validate-links.py

"""
Link Validation Tool for SIMA Knowledge Base

Purpose: Validate all internal links and REF-IDs in SIMA files
Version: 1.0.0
Date: 2025-11-08
Location: /sima/redirects/tools/

Usage:
    python validate-links.py [options]
    
Options:
    --dir <path>        Base directory to scan (default: /sima)
    --fix               Auto-fix broken links using REF-ID mappings
    --report <file>     Output report file (default: validation-report.md)
    --verbose           Verbose output
    
Examples:
    python validate-links.py --dir /sima
    python validate-links.py --fix --report broken-links.md
    python validate-links.py --dir /sima/platforms/aws --verbose
"""

import os
import re
import sys
import argparse
from pathlib import Path
from typing import Dict, List, Tuple, Set
from collections import defaultdict

# REF-ID patterns
REF_ID_PATTERN = re.compile(r'\b([A-Z]{2,10}-\d{2,3})\b')

# Markdown link patterns
MD_LINK_PATTERN = re.compile(r'\[([^\]]+)\]\(([^\)]+)\)')

# File path patterns
PATH_PATTERN = re.compile(r'`(/[^`]+\.md)`')


class LinkValidator:
    """Validates links and REF-IDs in SIMA knowledge base."""
    
    def __init__(self, base_dir: str = "/sima"):
        self.base_dir = Path(base_dir)
        self.all_files = set()
        self.all_ref_ids = {}  # REF-ID -> file path
        self.broken_links = []
        self.broken_ref_ids = []
        self.stats = defaultdict(int)
        
    def scan_files(self):
        """Scan all markdown files in base directory."""
        print(f"Scanning files in {self.base_dir}...")
        
        for file_path in self.base_dir.rglob("*.md"):
            if ".git" not in str(file_path):
                rel_path = file_path.relative_to(self.base_dir)
                self.all_files.add(str(rel_path))
                self.stats['total_files'] += 1
                
                # Extract REF-IDs from filename
                self._extract_ref_ids_from_file(file_path, str(rel_path))
        
        print(f"Found {self.stats['total_files']} markdown files")
        print(f"Found {len(self.all_ref_ids)} REF-IDs")
    
    def _extract_ref_ids_from_file(self, file_path: Path, rel_path: str):
        """Extract REF-IDs from a single file."""
        filename = file_path.name
        
        # Extract REF-ID from filename
        matches = REF_ID_PATTERN.findall(filename)
        for ref_id in matches:
            if ref_id not in self.all_ref_ids:
                self.all_ref_ids[ref_id] = []
            self.all_ref_ids[ref_id].append(rel_path)
    
    def validate_file(self, file_path: Path) -> List[Dict]:
        """Validate all links in a single file."""
        issues = []
        rel_path = file_path.relative_to(self.base_dir)
        
        try:
            with open(file_path, 'r', encoding='utf-8') as f:
                content = f.read()
                lines = content.split('\n')
        except Exception as e:
            issues.append({
                'file': str(rel_path),
                'line': 0,
                'type': 'read_error',
                'message': str(e)
            })
            return issues
        
        # Check markdown links
        for line_num, line in enumerate(lines, 1):
            # Check markdown links [text](path)
            for match in MD_LINK_PATTERN.finditer(line):
                link_text = match.group(1)
                link_path = match.group(2)
                
                # Skip external links
                if link_path.startswith(('http://', 'https://', '#')):
                    continue
                
                # Check if linked file exists
                target_path = self._resolve_link_path(file_path, link_path)
                if target_path and not target_path.exists():
                    issues.append({
                        'file': str(rel_path),
                        'line': line_num,
                        'type': 'broken_link',
                        'link': link_path,
                        'text': link_text,
                        'message': f"Broken link: {link_path}"
                    })
                    self.stats['broken_links'] += 1
            
            # Check REF-ID references
            for match in REF_ID_PATTERN.finditer(line):
                ref_id = match.group(1)
                
                # Check if REF-ID exists
                if ref_id not in self.all_ref_ids:
                    issues.append({
                        'file': str(rel_path),
                        'line': line_num,
                        'type': 'missing_ref_id',
                        'ref_id': ref_id,
                        'message': f"REF-ID not found: {ref_id}"
                    })
                    self.stats['missing_ref_ids'] += 1
            
            # Check file path references in backticks
            for match in PATH_PATTERN.finditer(line):
                path_str = match.group(1)
                
                # Convert to Path and check existence
                target_path = self.base_dir / path_str.lstrip('/')
                if not target_path.exists():
                    issues.append({
                        'file': str(rel_path),
                        'line': line_num,
                        'type': 'broken_path_reference',
                        'path': path_str,
                        'message': f"Path does not exist: {path_str}"
                    })
                    self.stats['broken_paths'] += 1
        
        return issues
    
    def _resolve_link_path(self, source_file: Path, link: str) -> Path:
        """Resolve a relative link path."""
        # Remove anchor
        link = link.split('#')[0]
        
        if not link:
            return None
        
        # Absolute path
        if link.startswith('/'):
            return self.base_dir / link.lstrip('/')
        
        # Relative path
        return (source_file.parent / link).resolve()
    
    def validate_all(self):
        """Validate all files."""
        print("\nValidating links...")
        
        all_issues = []
        for file_path in self.base_dir.rglob("*.md"):
            if ".git" not in str(file_path):
                issues = self.validate_file(file_path)
                all_issues.extend(issues)
                self.stats['files_checked'] += 1
        
        self.broken_links = [i for i in all_issues if i['type'] == 'broken_link']
        self.broken_ref_ids = [i for i in all_issues if i['type'] == 'missing_ref_id']
        
        return all_issues
    
    def generate_report(self, issues: List[Dict], output_file: str):
        """Generate markdown validation report."""
        print(f"\nGenerating report: {output_file}")
        
        with open(output_file, 'w', encoding='utf-8') as f:
            f.write("# Link Validation Report\n\n")
            f.write(f"**Date:** {self._get_date()}\n")
            f.write(f"**Files Scanned:** {self.stats['files_checked']}\n\n")
            
            # Summary
            f.write("## Summary\n\n")
            f.write(f"- Total Files: {self.stats['total_files']}\n")
            f.write(f"- Files Checked: {self.stats['files_checked']}\n")
            f.write(f"- REF-IDs Found: {len(self.all_ref_ids)}\n")
            f.write(f"- Broken Links: {self.stats['broken_links']}\n")
            f.write(f"- Missing REF-IDs: {self.stats['missing_ref_ids']}\n")
            f.write(f"- Broken Path References: {self.stats['broken_paths']}\n")
            f.write(f"- Total Issues: {len(issues)}\n\n")
            
            if not issues:
                f.write("✅ **No issues found!**\n\n")
                return
            
            # Group issues by type
            by_type = defaultdict(list)
            for issue in issues:
                by_type[issue['type']].append(issue)
            
            # Broken Links
            if by_type['broken_link']:
                f.write("## Broken Links\n\n")
                for issue in sorted(by_type['broken_link'], key=lambda x: x['file']):
                    f.write(f"- **{issue['file']}:{issue['line']}**\n")
                    f.write(f"  - Link: `{issue['link']}`\n")
                    f.write(f"  - Text: {issue['text']}\n\n")
            
            # Missing REF-IDs
            if by_type['missing_ref_id']:
                f.write("## Missing REF-IDs\n\n")
                
                # Group by REF-ID
                by_ref_id = defaultdict(list)
                for issue in by_type['missing_ref_id']:
                    by_ref_id[issue['ref_id']].append(issue)
                
                for ref_id, ref_issues in sorted(by_ref_id.items()):
                    f.write(f"### {ref_id}\n\n")
                    f.write(f"Referenced in {len(ref_issues)} locations:\n\n")
                    for issue in ref_issues:
                        f.write(f"- {issue['file']}:{issue['line']}\n")
                    f.write("\n")
            
            # Broken Path References
            if by_type['broken_path_reference']:
                f.write("## Broken Path References\n\n")
                for issue in sorted(by_type['broken_path_reference'], key=lambda x: x['file']):
                    f.write(f"- **{issue['file']}:{issue['line']}**\n")
                    f.write(f"  - Path: `{issue['path']}`\n\n")
            
            # Read Errors
            if by_type['read_error']:
                f.write("## File Read Errors\n\n")
                for issue in by_type['read_error']:
                    f.write(f"- **{issue['file']}**: {issue['message']}\n")
    
    def _get_date(self):
        """Get current date string."""
        from datetime import datetime
        return datetime.now().strftime("%Y-%m-%d")


def main():
    """Main entry point."""
    parser = argparse.ArgumentParser(description='Validate SIMA knowledge base links')
    parser.add_argument('--dir', default='/sima', help='Base directory to scan')
    parser.add_argument('--fix', action='store_true', help='Auto-fix broken links')
    parser.add_argument('--report', default='validation-report.md', help='Output report file')
    parser.add_argument('--verbose', action='store_true', help='Verbose output')
    
    args = parser.parse_args()
    
    # Create validator
    validator = LinkValidator(args.dir)
    
    # Scan files
    validator.scan_files()
    
    # Validate
    issues = validator.validate_all()
    
    # Generate report
    validator.generate_report(issues, args.report)
    
    # Print summary
    print("\n" + "="*60)
    print("VALIDATION SUMMARY")
    print("="*60)
    print(f"Files Checked: {validator.stats['files_checked']}")
    print(f"Broken Links: {validator.stats['broken_links']}")
    print(f"Missing REF-IDs: {validator.stats['missing_ref_ids']}")
    print(f"Broken Paths: {validator.stats['broken_paths']}")
    print(f"Total Issues: {len(issues)}")
    print("="*60)
    
    if issues:
        print(f"\n⚠️  Found {len(issues)} issues. See {args.report} for details.")
        return 1
    else:
        print("\n✅ No issues found!")
        return 0


if __name__ == '__main__':
    sys.exit(main())
