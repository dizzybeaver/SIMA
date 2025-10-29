#!/usr/bin/env python3
"""
Scan for hardcoded file server URLs in documentation
Version: 1.0.0
Purpose: Identify all hardcoded claude.dizzybeaver.com references
"""

import os
import re
import sys
from pathlib import Path
from datetime import datetime
from typing import List, Dict

HARDCODED_PATTERNS = [
    r'https://claude\.dizzybeaver\.com',
    r'claude\.dizzybeaver\.com',
    r'dizzybeaver\.com/[^\s\)]*',
]

EXCLUDE_FILES = [
    'File-Server-URLs.md',  # This is the output file - OK to have URLs
    'File Server URLs.md',  # Alternative name
    'scan-hardcoded-urls.py',  # This file itself
    'url-audit-report.md',  # Report file
]

def scan_file(filepath: str) -> List[Dict]:
    """Scan single file for hardcoded URLs"""
    matches = []
    try:
        with open(filepath, 'r', encoding='utf-8', errors='ignore') as f:
            for line_num, line in enumerate(f, 1):
                for pattern in HARDCODED_PATTERNS:
                    if re.search(pattern, line, re.IGNORECASE):
                        matches.append({
                            'file': filepath,
                            'line': line_num,
                            'content': line.strip()[:100],
                            'pattern': pattern
                        })
                        break  # Only record first match per line
    except Exception as e:
        print(f"Error reading {filepath}: {e}", file=sys.stderr)
    return matches

def should_exclude(filename: str) -> bool:
    """Check if file should be excluded from scanning"""
    return any(exclude in filename for exclude in EXCLUDE_FILES)

def scan_directory(directory: str, extensions: List[str] = ['.md', '.txt', '.py']) -> List[Dict]:
    """Scan entire directory tree"""
    all_matches = []
    for root, dirs, files in os.walk(directory):
        # Skip hidden directories and __pycache__
        dirs[:] = [d for d in dirs if not d.startswith('.') and d != '__pycache__']
        
        for file in files:
            if any(file.endswith(ext) for ext in extensions):
                if should_exclude(file):
                    continue
                    
                filepath = os.path.join(root, file)
                matches = scan_file(filepath)
                all_matches.extend(matches)
    return all_matches

def classify_file(filepath: str) -> str:
    """Classify file by type for reporting"""
    if '/Context/' in filepath or '/Support/' in filepath:
        return 'Context/Support'
    elif '/NM0' in filepath:
        return 'Neural Maps'
    elif '/Testing/' in filepath:
        return 'Testing'
    elif '/Docs/' in filepath:
        return 'Documentation'
    elif '/src/' in filepath:
        return 'Source Code'
    elif '/AWS/' in filepath:
        return 'AWS Config'
    else:
        return 'Other'

def generate_report(matches: List[Dict]) -> str:
    """Generate markdown report"""
    report = []
    report.append("# Hardcoded URL Audit Report\n")
    report.append(f"**Date:** {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}\n")
    report.append(f"**Total Matches:** {len(matches)}\n")
    report.append(f"**Scan Directory:** nmap/\n\n")
    
    if not matches:
        report.append("✅ **No hardcoded URLs found!**\n")
        return ''.join(report)
    
    # Group by file
    by_file = {}
    for match in matches:
        if match['file'] not in by_file:
            by_file[match['file']] = []
        by_file[match['file']].append(match)
    
    # Group by classification
    by_category = {}
    for filepath in by_file.keys():
        category = classify_file(filepath)
        if category not in by_category:
            by_category[category] = []
        by_category[category].append(filepath)
    
    # Summary by category
    report.append("## Summary by Category\n\n")
    for category in sorted(by_category.keys()):
        files = by_category[category]
        total_matches = sum(len(by_file[f]) for f in files)
        report.append(f"- **{category}:** {len(files)} files, {total_matches} matches\n")
    report.append("\n---\n\n")
    
    # Detailed breakdown
    report.append("## Files Requiring Updates\n\n")
    for category in sorted(by_category.keys()):
        report.append(f"### {category}\n\n")
        for filepath in sorted(by_category[category]):
            file_matches = by_file[filepath]
            report.append(f"#### {filepath}\n")
            report.append(f"**Matches:** {len(file_matches)}\n\n")
            
            # Show first 5 matches
            for i, match in enumerate(file_matches[:5]):
                report.append(f"- Line {match['line']}: `{match['content']}`\n")
            
            if len(file_matches) > 5:
                report.append(f"- ... and {len(file_matches) - 5} more\n")
            
            report.append("\n")
    
    # Priority recommendations
    report.append("---\n\n")
    report.append("## Priority Recommendations\n\n")
    report.append("### High Priority (Must Fix)\n")
    report.append("- Context/Support files (used in every session)\n")
    report.append("- Workflow files (examples shown to users)\n")
    report.append("- Documentation files (training materials)\n\n")
    
    report.append("### Medium Priority (Should Fix)\n")
    report.append("- Testing files (example test cases)\n")
    report.append("- Neural map files (if they contain examples)\n\n")
    
    report.append("### Low Priority (Can Skip)\n")
    report.append("- Source code comments (not documentation)\n")
    report.append("- Historical/archived files\n\n")
    
    report.append("---\n\n")
    report.append("## Replacement Strategy\n\n")
    report.append("```markdown\n")
    report.append("# Before (Hardcoded):\n")
    report.append("web_fetch('https://claude.dizzybeaver.com/nmap/Support/SESSION-START.md')\n\n")
    report.append("# After (Generic):\n")
    report.append("web_fetch('[BASE_URL]/nmap/Support/SESSION-START.md')\n\n")
    report.append("# Or (Example with note):\n")
    report.append("# Example (replace with your server URL):\n")
    report.append("web_fetch('https://your-domain.com/nmap/Support/SESSION-START.md')\n")
    report.append("```\n")
    
    return ''.join(report)

def main():
    """Main execution"""
    import argparse
    
    parser = argparse.ArgumentParser(description='Scan for hardcoded file server URLs')
    parser.add_argument('--directory', default='nmap', help='Directory to scan (default: nmap)')
    parser.add_argument('--output', default='url-audit-report.md', help='Output file (default: url-audit-report.md)')
    parser.add_argument('--quiet', action='store_true', help='Suppress console output')
    parser.add_argument('--exit-code', action='store_true', help='Exit with code 1 if matches found')
    
    args = parser.parse_args()
    
    if not args.quiet:
        print(f"🔍 Scanning {args.directory} for hardcoded URLs...")
    
    matches = scan_directory(args.directory)
    
    if not args.quiet:
        print(f"📊 Found {len(matches)} hardcoded URL references")
    
    # Generate report
    report = generate_report(matches)
    
    # Write to file
    with open(args.output, 'w', encoding='utf-8') as f:
        f.write(report)
    
    if not args.quiet:
        print(f"📄 Report saved to: {args.output}")
    
    # Exit code for CI/CD
    if args.exit_code and len(matches) > 0:
        return 1
    
    return 0

if __name__ == '__main__':
    sys.exit(main())
