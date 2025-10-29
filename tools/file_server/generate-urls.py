#!/usr/bin/env python3
"""
Generate File-Server-URLs.md from SERVER-CONFIG.md
Version: 1.0.0
Purpose: Automate URL generation for file server configuration
"""

import re
import sys
from datetime import datetime
from urllib.parse import quote
from pathlib import Path
from typing import Tuple, List, Dict

def load_server_config(config_file: str = 'SERVER-CONFIG.md') -> Tuple[str, List[str]]:
    """
    Load BASE_URL and file paths from SERVER-CONFIG.md
    
    Returns:
        Tuple of (base_url, file_paths)
    
    Raises:
        FileNotFoundError: If config file doesn't exist
        ValueError: If required sections missing
    """
    config_path = Path(config_file)
    
    if not config_path.exists():
        raise FileNotFoundError(f"Configuration file not found: {config_file}")
    
    with open(config_path, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Extract BASE_URL
    base_url_match = re.search(r'BASE_URL:\s*(.+)', content)
    if not base_url_match:
        raise ValueError("BASE_URL not found in SERVER-CONFIG.md")
    base_url = base_url_match.group(1).strip()
    
    # Extract file paths from FILE PATHS section
    # Look for code block after FILE PATHS header
    paths_match = re.search(
        r'##\s+FILE PATHS.*?```\s*(.+?)```',
        content,
        re.DOTALL | re.IGNORECASE
    )
    
    if not paths_match:
        raise ValueError("FILE PATHS section not found in SERVER-CONFIG.md")
    
    # Parse paths
    paths_text = paths_match.group(1)
    paths = []
    for line in paths_text.split('\n'):
        line = line.strip()
        # Skip empty lines and comments
        if line and not line.startswith('#'):
            paths.append(line)
    
    if not paths:
        raise ValueError("No file paths found in SERVER-CONFIG.md")
    
    return base_url, paths

def group_paths_by_directory(paths: List[str]) -> Dict[str, List[str]]:
    """
    Group file paths by their parent directory
    
    Args:
        paths: List of file paths
        
    Returns:
        Dictionary mapping directory names to list of file paths
    """
    grouped = {}
    
    for path in paths:
        if '/' in path:
            dir_name = path.rsplit('/', 1)[0]
        else:
            dir_name = 'root'
        
        if dir_name not in grouped:
            grouped[dir_name] = []
        grouped[dir_name].append(path)
    
    return grouped

def generate_urls(base_url: str, paths: List[str]) -> str:
    """
    Generate File-Server-URLs.md content
    
    Args:
        base_url: Base URL for file server
        paths: List of file paths
        
    Returns:
        Complete markdown content for File-Server-URLs.md
    """
    # Clean base URL (remove trailing slash)
    base_url = base_url.rstrip('/')
    
    # Group by directory
    grouped = group_paths_by_directory(paths)
    
    # Generate output
    output = []
    output.append("# SUGA-ISP Lambda - File Server URLs\n\n")
    output.append(f"**Generated:** {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}\n")
    output.append(f"**Base URL:** {base_url}\n")
    output.append(f"**Total Files:** {len(paths)}\n")
    output.append(f"**Purpose:** URL inventory for web_fetch access\n\n")
    output.append("---\n\n")
    
    # Add grouped URLs
    for directory in sorted(grouped.keys()):
        file_list = grouped[directory]
        output.append(f"## 📂 {directory} ({len(file_list)} files)\n\n")
        
        for path in sorted(file_list):
            # Encode spaces as %20, keep forward slashes
            encoded_path = quote(path, safe='/:')
            output.append(f"{base_url}/{encoded_path}\n")
        
        output.append("\n")
    
    output.append("---\n\n")
    output.append("**END OF FILE SERVER URLS**\n")
    
    return ''.join(output)

def write_output(content: str, output_file: str = 'File-Server-URLs.md') -> None:
    """
    Write generated content to output file
    
    Args:
        content: Generated markdown content
        output_file: Path to output file
    """
    with open(output_file, 'w', encoding='utf-8') as f:
        f.write(content)

def get_stats(content: str, paths: List[str]) -> Dict[str, any]:
    """
    Calculate statistics for generated file
    
    Args:
        content: Generated content
        paths: List of file paths
        
    Returns:
        Dictionary with statistics
    """
    grouped = group_paths_by_directory(paths)
    
    return {
        'total_files': len(paths),
        'total_directories': len(grouped),
        'total_chars': len(content),
        'total_lines': content.count('\n'),
        'file_size_kb': len(content.encode('utf-8')) / 1024
    }

def main():
    """Main execution"""
    import argparse
    
    parser = argparse.ArgumentParser(
        description='Generate File-Server-URLs.md from SERVER-CONFIG.md'
    )
    parser.add_argument(
        '--config',
        default='SERVER-CONFIG.md',
        help='Path to SERVER-CONFIG.md (default: SERVER-CONFIG.md)'
    )
    parser.add_argument(
        '--output',
        default='File-Server-URLs.md',
        help='Output file path (default: File-Server-URLs.md)'
    )
    parser.add_argument(
        '--quiet',
        action='store_true',
        help='Suppress console output'
    )
    parser.add_argument(
        '--stats',
        action='store_true',
        help='Show detailed statistics'
    )
    
    args = parser.parse_args()
    
    try:
        # Load configuration
        if not args.quiet:
            print(f"📂 Loading {args.config}...")
        
        base_url, paths = load_server_config(args.config)
        
        if not args.quiet:
            print(f"✅ Found BASE_URL: {base_url}")
            print(f"✅ Found {len(paths)} file paths")
        
        # Generate URLs
        if not args.quiet:
            print(f"⚡ Generating {args.output}...")
        
        content = generate_urls(base_url, paths)
        
        # Write output
        write_output(content, args.output)
        
        if not args.quiet:
            stats = get_stats(content, paths)
            print(f"✅ Generated {args.output}")
            print(f"📄 File size: {stats['file_size_kb']:.1f} KB")
            
            if args.stats:
                print(f"\n📊 Statistics:")
                print(f"   Total files: {stats['total_files']}")
                print(f"   Total directories: {stats['total_directories']}")
                print(f"   Total characters: {stats['total_chars']:,}")
                print(f"   Total lines: {stats['total_lines']}")
        
        return 0
        
    except FileNotFoundError as e:
        print(f"❌ Error: {e}", file=sys.stderr)
        print(f"   Make sure {args.config} exists in current directory", file=sys.stderr)
        return 1
        
    except ValueError as e:
        print(f"❌ Configuration Error: {e}", file=sys.stderr)
        print(f"   Check {args.config} format:", file=sys.stderr)
        print(f"   - Must have 'BASE_URL: <url>' line", file=sys.stderr)
        print(f"   - Must have '## FILE PATHS' section with code block", file=sys.stderr)
        return 1
        
    except Exception as e:
        print(f"❌ Unexpected Error: {e}", file=sys.stderr)
        return 1

if __name__ == '__main__':
    sys.exit(main())
