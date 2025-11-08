# check-file-sizes.py

"""
File Size Validation Tool for SIMA Knowledge Base

Purpose: Validate that all SIMA files comply with ≤400 line limit
Version: 1.0.0
Date: 2025-11-08
Location: /sima/redirects/tools/

Enforces SIMAv4 standard: All files ≤400 lines

Usage:
    python check-file-sizes.py [options]
    
Options:
    --dir <path>        Base directory to scan (default: /sima)
    --limit <lines>     Line limit (default: 400)
    --exclude <pattern> Exclude files matching pattern
    --report <file>     Output report file (default: file-sizes.md)
    --verbose           Verbose output
    
Examples:
    python check-file-sizes.py --dir /sima
    python check-file-sizes.py --limit 400 --report oversized.md
    python check-file-sizes.py --exclude "*.py" --verbose
"""

import os
import sys
import argparse
from pathlib import Path
from typing import List, Dict, Tuple
from collections import defaultdict


class FileSizeChecker:
    """Validates file sizes in SIMA knowledge base."""
    
    def __init__(self, base_dir: str = "/sima", line_limit: int = 400):
        self.base_dir = Path(base_dir)
        self.line_limit = line_limit
        self.oversized_files = []
        self.stats = {
            'total_files': 0,
            'total_lines': 0,
            'compliant_files': 0,
            'oversized_files': 0,
            'avg_lines': 0,
            'max_lines': 0,
            'max_file': None
        }
        
    def check_file(self, file_path: Path) -> Dict:
        """Check a single file's line count."""
        try:
            with open(file_path, 'r', encoding='utf-8') as f:
                lines = f.readlines()
                line_count = len(lines)
            
            rel_path = file_path.relative_to(self.base_dir)
            
            return {
                'path': str(rel_path),
                'lines': line_count,
                'compliant': line_count <= self.line_limit,
                'overage': max(0, line_count - self.line_limit)
            }
        except Exception as e:
            return {
                'path': str(file_path),
                'lines': 0,
                'compliant': False,
                'error': str(e)
            }
    
    def check_all(self, exclude_patterns: List[str] = None) -> List[Dict]:
        """Check all markdown files in base directory."""
        print(f"Checking files in {self.base_dir}...")
        print(f"Line limit: {self.line_limit}")
        
        if exclude_patterns:
            print(f"Excluding patterns: {', '.join(exclude_patterns)}")
        
        results = []
        
        for file_path in self.base_dir.rglob("*.md"):
            # Skip git files
            if ".git" in str(file_path):
                continue
            
            # Skip excluded patterns
            if exclude_patterns:
                skip = False
                for pattern in exclude_patterns:
                    if pattern in str(file_path):
                        skip = True
                        break
                if skip:
                    continue
            
            result = self.check_file(file_path)
            results.append(result)
            
            self.stats['total_files'] += 1
            self.stats['total_lines'] += result['lines']
            
            if result['compliant']:
                self.stats['compliant_files'] += 1
            else:
                self.stats['oversized_files'] += 1
                self.oversized_files.append(result)
            
            # Track max
            if result['lines'] > self.stats['max_lines']:
                self.stats['max_lines'] = result['lines']
                self.stats['max_file'] = result['path']
        
        # Calculate average
        if self.stats['total_files'] > 0:
            self.stats['avg_lines'] = self.stats['total_lines'] / self.stats['total_files']
        
        return results
    
    def generate_report(self, results: List[Dict], output_file: str):
        """Generate markdown report."""
        print(f"\nGenerating report: {output_file}")
        
        with open(output_file, 'w', encoding='utf-8') as f:
            f.write("# File Size Validation Report\n\n")
            f.write(f"**Date:** {self._get_date()}\n")
            f.write(f"**Line Limit:** {self.line_limit} lines\n")
            f.write(f"**Standard:** SIMAv4\n\n")
            
            # Summary
            f.write("## Summary\n\n")
            f.write(f"- **Total Files:** {self.stats['total_files']}\n")
            f.write(f"- **Compliant Files:** {self.stats['compliant_files']} ({self._percent(self.stats['compliant_files'], self.stats['total_files'])}%)\n")
            f.write(f"- **Oversized Files:** {self.stats['oversized_files']} ({self._percent(self.stats['oversized_files'], self.stats['total_files'])}%)\n")
            f.write(f"- **Total Lines:** {self.stats['total_lines']:,}\n")
            f.write(f"- **Average Lines/File:** {self.stats['avg_lines']:.1f}\n")
            f.write(f"- **Largest File:** {self.stats['max_lines']} lines\n")
            f.write(f"  - Path: `{self.stats['max_file']}`\n\n")
            
            # Status
            if self.stats['oversized_files'] == 0:
                f.write("✅ **All files compliant!**\n\n")
            else:
                f.write(f"⚠️ **{self.stats['oversized_files']} files exceed limit**\n\n")
            
            # Distribution
            f.write("## Size Distribution\n\n")
            
            # Calculate distribution
            buckets = defaultdict(int)
            for result in results:
                lines = result['lines']
                if lines <= 100:
                    buckets['0-100'] += 1
                elif lines <= 200:
                    buckets['101-200'] += 1
                elif lines <= 300:
                    buckets['201-300'] += 1
                elif lines <= 400:
                    buckets['301-400'] += 1
                elif lines <= 500:
                    buckets['401-500'] += 1
                elif lines <= 600:
                    buckets['501-600'] += 1
                else:
                    buckets['601+'] += 1
            
            f.write("| Range | Count | Percentage |\n")
            f.write("|-------|-------|------------|\n")
            for bucket_range in ['0-100', '101-200', '201-300', '301-400', '401-500', '501-600', '601+']:
                count = buckets[bucket_range]
                pct = self._percent(count, self.stats['total_files'])
                status = "✅" if bucket_range in ['0-100', '101-200', '201-300', '301-400'] else "⚠️"
                f.write(f"| {status} {bucket_range} | {count} | {pct:.1f}% |\n")
            f.write("\n")
            
            # Oversized files
            if self.oversized_files:
                f.write("## Oversized Files\n\n")
                f.write("Files exceeding the 400-line limit:\n\n")
                
                # Sort by overage (worst first)
                sorted_files = sorted(self.oversized_files, key=lambda x: x['overage'], reverse=True)
                
                f.write("| File | Lines | Overage | Action Needed |\n")
                f.write("|------|-------|---------|---------------|\n")
                
                for file_info in sorted_files:
                    lines = file_info['lines']
                    overage = file_info['overage']
                    path = file_info['path']
                    
                    # Suggest action
                    if overage <= 50:
                        action = "Minor trim"
                    elif overage <= 100:
                        action = "Moderate refactor"
                    else:
                        action = "Split into multiple files"
                    
                    f.write(f"| `{path}` | {lines} | +{overage} | {action} |\n")
                f.write("\n")
                
                # Recommendations
                f.write("## Recommendations\n\n")
                f.write("### For Oversized Files:\n\n")
                f.write("1. **< 50 lines over:** Remove redundant content, tighten prose\n")
                f.write("2. **50-100 lines over:** Refactor into sections, extract examples\n")
                f.write("3. **> 100 lines over:** Split into multiple focused files\n\n")
                f.write("### Split Strategies:\n\n")
                f.write("- Separate core concepts from examples\n")
                f.write("- Extract detailed explanations to separate docs\n")
                f.write("- Create companion files for related topics\n")
                f.write("- Move lengthy code examples to separate files\n\n")
            
            # Top 10 largest compliant files
            compliant_results = [r for r in results if r['compliant']]
            if compliant_results:
                f.write("## Largest Compliant Files\n\n")
                f.write("Top 10 files approaching the limit:\n\n")
                
                sorted_compliant = sorted(compliant_results, key=lambda x: x['lines'], reverse=True)[:10]
                
                f.write("| File | Lines | Headroom |\n")
                f.write("|------|-------|-----------|\n")
                
                for file_info in sorted_compliant:
                    lines = file_info['lines']
                    headroom = self.line_limit - lines
                    path = file_info['path']
                    
                    if headroom < 50:
                        status = "⚠️"
                    else:
                        status = "âœ…"
                    
                    f.write(f"| {status} `{path}` | {lines} | {headroom} |\n")
                f.write("\n")
    
    def _percent(self, part: int, whole: int) -> float:
        """Calculate percentage."""
        if whole == 0:
            return 0.0
        return (part / whole) * 100
    
    def _get_date(self):
        """Get current date string."""
        from datetime import datetime
        return datetime.now().strftime("%Y-%m-%d")


def main():
    """Main entry point."""
    parser = argparse.ArgumentParser(description='Check SIMA file sizes')
    parser.add_argument('--dir', default='/sima', help='Base directory to scan')
    parser.add_argument('--limit', type=int, default=400, help='Line limit')
    parser.add_argument('--exclude', action='append', help='Exclude pattern (can be repeated)')
    parser.add_argument('--report', default='file-sizes.md', help='Output report file')
    parser.add_argument('--verbose', action='store_true', help='Verbose output')
    
    args = parser.parse_args()
    
    # Create checker
    checker = FileSizeChecker(args.dir, args.limit)
    
    # Check all files
    results = checker.check_all(args.exclude)
    
    # Generate report
    checker.generate_report(results, args.report)
    
    # Print summary
    print("\n" + "="*60)
    print("FILE SIZE SUMMARY")
    print("="*60)
    print(f"Total Files: {checker.stats['total_files']}")
    print(f"Compliant: {checker.stats['compliant_files']} ({checker._percent(checker.stats['compliant_files'], checker.stats['total_files']):.1f}%)")
    print(f"Oversized: {checker.stats['oversized_files']} ({checker._percent(checker.stats['oversized_files'], checker.stats['total_files']):.1f}%)")
    print(f"Average Lines: {checker.stats['avg_lines']:.1f}")
    print(f"Largest File: {checker.stats['max_lines']} lines")
    print("="*60)
    
    if checker.stats['oversized_files'] > 0:
        print(f"\n⚠️  {checker.stats['oversized_files']} files exceed {args.limit} lines. See {args.report} for details.")
        return 1
    else:
        print("\n✅ All files compliant!")
        return 0


if __name__ == '__main__':
    sys.exit(main())
