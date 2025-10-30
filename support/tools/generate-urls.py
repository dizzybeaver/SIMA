#!/usr/bin/env python3
"""
generate-urls.py

Generate File-Server-URLs.md from SERVER-CONFIG.md

Version: 1.0.0
Date: 2025-10-28
Purpose: Automated URL generation for file server configuration
"""

import re
from datetime import datetime
from urllib.parse import quote
import sys

def load_server_config(config_file='SERVER-CONFIG.md'):
    """Load BASE_URL and file paths from config"""
    try:
        with open(config_file, 'r', encoding='utf-8') as f:
            content = f.read()
    except FileNotFoundError:
        print(f"âŒ Error: {config_file} not found")
        print(f"   Current directory: {sys.path[0]}")
        return None, None
    
    # Extract BASE_URL
    base_url_match = re.search(r'BASE_URL:\s*(.+)', content)
    if not base_url_match:
        print("âŒ Error: BASE_URL not found in SERVER-CONFIG.md")
        print("   Expected format: BASE_URL: https://your-domain.com")
        return None, None
    base_url = base_url_match.group(1).strip()
    
    # Extract file paths
    paths_section = re.search(r'## FILE PATHS.*?```(.+?)```', content, re.DOTALL)
    if not paths_section:
        print("âŒ Error: FILE PATHS section not found")
        print("   Expected: ## FILE PATHS\\n```\\npath1\\npath2\\n```")
        return None, None
    
    paths = [line.strip() for line in paths_section.group(1).split('\n') 
             if line.strip() and not line.startswith('#')]
    
    return base_url, paths

def generate_urls(base_url, paths):
    """Generate complete URLs from paths"""
    # Clean base URL (remove trailing slash)
    base_url = base_url.rstrip('/')
    
    # Group by directory
    grouped = {}
    for path in paths:
        dir_name = path.rsplit('/', 1)[0] if '/' in path else 'root'
        if dir_name not in grouped:
            grouped[dir_name] = []
        grouped[dir_name].append(path)
    
    # Generate output
    output = []
    output.append("# File-Server-URLs.md\n")
    output.append("# SUGA-ISP Lambda - File Server URLs\n\n")
    output.append(f"**Generated:** {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}\n")
    output.append(f"**Base URL:** {base_url}\n")
    output.append(f"**Total Files:** {len(paths)}\n")
    output.append(f"**Total Directories:** {len(grouped)}\n")
    output.append(f"**Purpose:** URL inventory for web_fetch access\n")
    output.append("\n---\n\n")
    
    # Add grouped URLs
    for directory in sorted(grouped.keys()):
        output.append(f"## ðŸ"‚ {directory} ({len(grouped[directory])} files)\n\n")
        for path in sorted(grouped[directory]):
            # Encode spaces as %20
            encoded_path = quote(path, safe='/:')
            output.append(f"{base_url}/{encoded_path}\n")
        output.append("\n")
    
    output.append("---\n\n")
    output.append("**END OF FILE SERVER URLS**\n")
    
    return ''.join(output)

def main():
    print("ðŸš€ File Server URL Generator")
    print("=" * 50)
    
    try:
        # Load configuration
        print("\nðŸ"‚ Loading SERVER-CONFIG.md...")
        base_url, paths = load_server_config()
        
        if base_url is None or paths is None:
            return 1
        
        print(f"âœ… Found BASE_URL: {base_url}")
        print(f"âœ… Found {len(paths)} file paths")
        
        # Generate URLs
        print("\nâš¡ Generating File-Server-URLs.md...")
        output = generate_urls(base_url, paths)
        
        # Write output
        with open('File-Server-URLs.md', 'w', encoding='utf-8') as f:
            f.write(output)
        
        print(f"âœ… Generated File-Server-URLs.md")
        print(f"   Size: {len(output):,} characters")
        print(f"   Lines: {len(output.splitlines()):,}")
        print(f"\nðŸ"„ File saved to: File-Server-URLs.md")
        print("\nðŸŽ‰ Generation complete!")
        
    except Exception as e:
        print(f"\nâŒ Error: {e}")
        import traceback
        traceback.print_exc()
        return 1
    
    return 0

if __name__ == '__main__':
    exit(main())
