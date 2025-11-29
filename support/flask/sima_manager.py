"""
sima_manager.py

Version: 1.0.0
Date: 2025-11-29
Purpose: Flask application for SIMA knowledge management
Project: SIMA

ADDED: Complete SIMA management system with export/import/archive
ADDED: Language detection from code blocks
ADDED: JSON format support with MD conversion
ADDED: Index generation and updates
"""

from flask import Flask, request, jsonify, render_template_string, send_file
from pathlib import Path
import json
import re
import yaml
from datetime import datetime
from typing import Dict, List, Set, Optional
import hashlib
import zipfile
import io

app = Flask(__name__)

# ADDED: Configuration
class Config:
    SIMA_ROOT = Path("./sima")
    EXPORT_DIR = Path("./exports")
    ARCHIVE_DIR = Path("./archives")
    MAX_FILE_LINES = 350
    SUPPORTED_FORMATS = ["md", "json"]
    
Config.EXPORT_DIR.mkdir(exist_ok=True)
Config.ARCHIVE_DIR.mkdir(exist_ok=True)

# ADDED: Language detection patterns
LANGUAGE_PATTERNS = {
    'python': r'```python\n',
    'javascript': r'```(?:javascript|js)\n',
    'typescript': r'```(?:typescript|ts)\n',
    'java': r'```java\n',
    'go': r'```go\n',
    'rust': r'```rust\n',
    'c': r'```c\n',
    'cpp': r'```(?:cpp|c\+\+)\n',
    'csharp': r'```(?:csharp|cs)\n',
    'ruby': r'```ruby\n',
    'php': r'```php\n',
    'swift': r'```swift\n',
    'kotlin': r'```kotlin\n',
    'sql': r'```sql\n',
    'bash': r'```(?:bash|sh)\n',
    'yaml': r'```ya?ml\n',
}

# ADDED: Knowledge file parser
class KnowledgeFile:
    def __init__(self, path: Path):
        self.path = path
        self.content = path.read_text(encoding='utf-8')
        self.metadata = {}
        self.languages = set()
        self.parse()
    
    def parse(self):
        """Parse MD file and extract metadata"""
        lines = self.content.split('\n')
        
        # Extract metadata from header
        for line in lines[:20]:
            if line.startswith('**Version:**'):
                self.metadata['version'] = line.split('**Version:**')[1].strip()
            elif line.startswith('**Date:**'):
                self.metadata['date'] = line.split('**Date:**')[1].strip()
            elif line.startswith('**Purpose:**'):
                self.metadata['purpose'] = line.split('**Purpose:**')[1].strip()
            elif line.startswith('**Category:**'):
                self.metadata['category'] = line.split('**Category:**')[1].strip()
            elif line.startswith('**REF-ID:**'):
                self.metadata['ref_id'] = line.split('**REF-ID:**')[1].strip()
        
        # Detect languages
        self.languages = self.detect_languages()
    
    def detect_languages(self) -> Set[str]:
        """Detect programming languages from code blocks"""
        detected = set()
        for lang, pattern in LANGUAGE_PATTERNS.items():
            if re.search(pattern, self.content):
                detected.add(lang)
        return detected
    
    def to_json(self) -> Dict:
        """Convert to JSON format"""
        # ADDED: Extract title from first heading
        title_match = re.search(r'^# (.+)$', self.content, re.MULTILINE)
        title = title_match.group(1) if title_match else self.path.stem
        
        # ADDED: Extract keywords if present
        keywords_match = re.search(r'\*\*Keywords:\*\* (.+)$', self.content, re.MULTILINE)
        keywords = []
        if keywords_match:
            keywords = [k.strip() for k in keywords_match.group(1).split(',')]
        
        # ADDED: Extract related refs
        related_match = re.search(r'\*\*Related:\*\* (.+)$', self.content, re.MULTILINE)
        related = []
        if related_match:
            related = [r.strip() for r in related_match.group(1).split(',')]
        
        return {
            "format_version": "1.0.0",
            "sima_version": "4.2.2",
            "title": title,
            "ref_id": self.metadata.get('ref_id', ''),
            "metadata": {
                "version": self.metadata.get('version', '1.0.0'),
                "date": self.metadata.get('date', datetime.now().strftime('%Y-%m-%d')),
                "purpose": self.metadata.get('purpose', ''),
                "category": self.metadata.get('category', ''),
                "created": datetime.now().isoformat(),
                "modified": datetime.now().isoformat()
            },
            "languages": sorted(list(self.languages)),
            "keywords": keywords,
            "related": related,
            "content": {
                "markdown": self.content,
                "sections": self.extract_sections()
            },
            "flags": {
                "has_code": len(self.languages) > 0,
                "line_count": len(self.content.split('\n')),
                "exceeds_limit": len(self.content.split('\n')) > Config.MAX_FILE_LINES
            }
        }
    
    def extract_sections(self) -> List[Dict]:
        """Extract markdown sections"""
        sections = []
        current_section = None
        current_content = []
        
        for line in self.content.split('\n'):
            if line.startswith('## '):
                if current_section:
                    sections.append({
                        "heading": current_section,
                        "content": '\n'.join(current_content)
                    })
                current_section = line[3:].strip()
                current_content = []
            elif current_section:
                current_content.append(line)
        
        if current_section:
            sections.append({
                "heading": current_section,
                "content": '\n'.join(current_content)
            })
        
        return sections

# ADDED: JSON to MD converter
class JSONToMD:
    @staticmethod
    def convert(data: Dict) -> str:
        """Convert JSON format to MD"""
        md_lines = []
        
        # Title
        md_lines.append(f"# {data['title']}")
        md_lines.append("")
        
        # Metadata
        meta = data['metadata']
        md_lines.append(f"**Version:** {meta.get('version', '1.0.0')}")
        md_lines.append(f"**Date:** {meta.get('date', datetime.now().strftime('%Y-%m-%d'))}")
        md_lines.append(f"**Purpose:** {meta.get('purpose', '')}")
        
        if meta.get('category'):
            md_lines.append(f"**Category:** {meta['category']}")
        
        if data.get('ref_id'):
            md_lines.append(f"**REF-ID:** {data['ref_id']}")
        
        md_lines.append("")
        md_lines.append("---")
        md_lines.append("")
        
        # Content sections
        if 'sections' in data.get('content', {}):
            for section in data['content']['sections']:
                md_lines.append(f"## {section['heading']}")
                md_lines.append("")
                md_lines.append(section['content'])
                md_lines.append("")
        elif 'markdown' in data.get('content', {}):
            # Extract body (skip header)
            content = data['content']['markdown']
            lines = content.split('\n')
            in_header = True
            for line in lines:
                if in_header and line == '---':
                    in_header = False
                    continue
                if not in_header:
                    md_lines.append(line)
        
        # Footer metadata
        if data.get('keywords'):
            md_lines.append("")
            md_lines.append(f"**Keywords:** {', '.join(data['keywords'])}")
        
        if data.get('related'):
            md_lines.append(f"**Related:** {', '.join(data['related'])}")
        
        if data.get('languages'):
            md_lines.append(f"**Languages:** {', '.join(data['languages'])}")
        
        return '\n'.join(md_lines)

# ADDED: Index generator
class IndexGenerator:
    @staticmethod
    def generate(directory: Path, title: str = "Index") -> str:
        """Generate index MD file for directory"""
        md_lines = [
            f"# {title}",
            "",
            f"**Version:** 1.0.0",
            f"**Date:** {datetime.now().strftime('%Y-%m-%d')}",
            f"**Purpose:** Auto-generated index",
            "",
            "---",
            ""
        ]
        
        # Group by category
        entries = {}
        for file_path in sorted(directory.rglob("*.md")):
            if file_path.name.endswith('-Index.md'):
                continue
            
            try:
                kf = KnowledgeFile(file_path)
                category = kf.metadata.get('category', 'Uncategorized')
                
                if category not in entries:
                    entries[category] = []
                
                rel_path = file_path.relative_to(directory)
                ref_id = kf.metadata.get('ref_id', '')
                purpose = kf.metadata.get('purpose', '')
                langs = ', '.join(sorted(kf.languages)) if kf.languages else ''
                
                entry = {
                    'path': rel_path,
                    'ref_id': ref_id,
                    'purpose': purpose,
                    'languages': langs
                }
                entries[category].append(entry)
            except Exception as e:
                print(f"Error processing {file_path}: {e}")
        
        # Write entries
        for category in sorted(entries.keys()):
            md_lines.append(f"## {category}")
            md_lines.append("")
            
            for entry in entries[category]:
                ref_id = f"[{entry['ref_id']}] " if entry['ref_id'] else ""
                langs = f" `{entry['languages']}`" if entry['languages'] else ""
                md_lines.append(f"- {ref_id}[{entry['path'].stem}]({entry['path']}){langs} - {entry['purpose']}")
            
            md_lines.append("")
        
        md_lines.append("---")
        md_lines.append(f"**Total Files:** {sum(len(e) for e in entries.values())}")
        
        return '\n'.join(md_lines)

# ADDED: Export manager
class ExportManager:
    @staticmethod
    def export_to_json(source_dir: Path, output_file: Path):
        """Export knowledge files to JSON archive"""
        export_data = {
            "manifest": {
                "version": "1.0.0",
                "sima_version": "4.2.2",
                "created": datetime.now().isoformat(),
                "source": str(source_dir),
                "file_count": 0
            },
            "files": []
        }
        
        for file_path in source_dir.rglob("*.md"):
            try:
                kf = KnowledgeFile(file_path)
                json_data = kf.to_json()
                json_data['path'] = str(file_path.relative_to(source_dir))
                export_data['files'].append(json_data)
            except Exception as e:
                print(f"Error exporting {file_path}: {e}")
        
        export_data['manifest']['file_count'] = len(export_data['files'])
        
        output_file.write_text(json.dumps(export_data, indent=2), encoding='utf-8')
        return export_data['manifest']
    
    @staticmethod
    def import_from_json(json_file: Path, target_dir: Path):
        """Import JSON archive to MD files"""
        data = json.loads(json_file.read_text(encoding='utf-8'))
        
        imported = []
        for file_data in data['files']:
            try:
                md_content = JSONToMD.convert(file_data)
                target_path = target_dir / file_data['path']
                target_path.parent.mkdir(parents=True, exist_ok=True)
                target_path.write_text(md_content, encoding='utf-8')
                imported.append(str(target_path))
            except Exception as e:
                print(f"Error importing {file_data.get('path', 'unknown')}: {e}")
        
        return imported

# ADDED: Flask routes
@app.route('/')
def index():
    """Main dashboard"""
    return render_template_string('''
<!DOCTYPE html>
<html>
<head>
    <title>SIMA Knowledge Manager</title>
    <style>
        body { font-family: Arial; margin: 40px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; }
        h1 { color: #333; }
        .section { margin: 30px 0; padding: 20px; background: #f9f9f9; border-radius: 4px; }
        .button { 
            background: #007bff; color: white; padding: 10px 20px; 
            border: none; border-radius: 4px; cursor: pointer; margin: 5px;
        }
        .button:hover { background: #0056b3; }
        input[type="text"], input[type="file"] { padding: 8px; margin: 5px; width: 300px; }
        .result { margin-top: 15px; padding: 10px; background: #e7f3ff; border-radius: 4px; }
        .language-tag { 
            display: inline-block; padding: 3px 8px; margin: 2px; 
            background: #28a745; color: white; border-radius: 3px; font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🗂️ SIMA Knowledge Manager</h1>
        
        <div class="section">
            <h2>Export to JSON</h2>
            <p>Export knowledge files to universal JSON format</p>
            <input type="text" id="export-path" placeholder="Directory path (e.g., ./sima/generic)" />
            <button class="button" onclick="exportToJSON()">Export</button>
            <div id="export-result" class="result" style="display:none;"></div>
        </div>
        
        <div class="section">
            <h2>Import from JSON</h2>
            <p>Import JSON archive and convert to MD files</p>
            <input type="file" id="import-file" accept=".json" />
            <input type="text" id="import-target" placeholder="Target directory" />
            <button class="button" onclick="importFromJSON()">Import</button>
            <div id="import-result" class="result" style="display:none;"></div>
        </div>
        
        <div class="section">
            <h2>Generate Index</h2>
            <p>Auto-generate index file for directory</p>
            <input type="text" id="index-path" placeholder="Directory path" />
            <input type="text" id="index-title" placeholder="Index title" value="Index" />
            <button class="button" onclick="generateIndex()">Generate</button>
            <div id="index-result" class="result" style="display:none;"></div>
        </div>
        
        <div class="section">
            <h2>Analyze File</h2>
            <p>Detect languages and extract metadata</p>
            <input type="text" id="analyze-path" placeholder="File path" />
            <button class="button" onclick="analyzeFile()">Analyze</button>
            <div id="analyze-result" class="result" style="display:none;"></div>
        </div>
    </div>
    
    <script>
        async function exportToJSON() {
            const path = document.getElementById('export-path').value;
            const response = await fetch('/api/export', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({path: path})
            });
            const result = await response.json();
            const div = document.getElementById('export-result');
            div.style.display = 'block';
            div.innerHTML = `<strong>✅ Exported:</strong> ${result.file_count} files<br>
                            <strong>Output:</strong> ${result.output_file}<br>
                            <a href="/download/${result.filename}" class="button">Download</a>`;
        }
        
        async function importFromJSON() {
            const file = document.getElementById('import-file').files[0];
            const target = document.getElementById('import-target').value;
            
            const formData = new FormData();
            formData.append('file', file);
            formData.append('target', target);
            
            const response = await fetch('/api/import', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            const div = document.getElementById('import-result');
            div.style.display = 'block';
            div.innerHTML = `<strong>✅ Imported:</strong> ${result.imported_count} files`;
        }
        
        async function generateIndex() {
            const path = document.getElementById('index-path').value;
            const title = document.getElementById('index-title').value;
            const response = await fetch('/api/index', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({path: path, title: title})
            });
            const result = await response.json();
            const div = document.getElementById('index-result');
            div.style.display = 'block';
            div.innerHTML = `<strong>✅ Generated:</strong> ${result.output_file}<br>
                            <strong>Entries:</strong> ${result.entry_count}`;
        }
        
        async function analyzeFile() {
            const path = document.getElementById('analyze-path').value;
            const response = await fetch('/api/analyze', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({path: path})
            });
            const result = await response.json();
            const div = document.getElementById('analyze-result');
            div.style.display = 'block';
            
            const langs = result.languages.map(l => 
                `<span class="language-tag">${l}</span>`
            ).join('');
            
            div.innerHTML = `
                <strong>File:</strong> ${result.path}<br>
                <strong>REF-ID:</strong> ${result.ref_id || 'None'}<br>
                <strong>Languages:</strong> ${langs || 'None detected'}<br>
                <strong>Lines:</strong> ${result.line_count}<br>
                <strong>Exceeds Limit:</strong> ${result.exceeds_limit ? '⚠️ Yes' : '✅ No'}
            `;
        }
    </script>
</body>
</html>
    ''')

@app.route('/api/export', methods=['POST'])
def api_export():
    """Export directory to JSON"""
    data = request.json
    source_dir = Path(data['path'])
    
    timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
    output_file = Config.EXPORT_DIR / f"sima_export_{timestamp}.json"
    
    manifest = ExportManager.export_to_json(source_dir, output_file)
    
    return jsonify({
        'status': 'success',
        'file_count': manifest['file_count'],
        'output_file': str(output_file),
        'filename': output_file.name
    })

@app.route('/api/import', methods=['POST'])
def api_import():
    """Import JSON file"""
    file = request.files['file']
    target = Path(request.form['target'])
    
    json_file = Config.EXPORT_DIR / file.filename
    file.save(json_file)
    
    imported = ExportManager.import_from_json(json_file, target)
    
    return jsonify({
        'status': 'success',
        'imported_count': len(imported),
        'files': imported
    })

@app.route('/api/index', methods=['POST'])
def api_index():
    """Generate index"""
    data = request.json
    directory = Path(data['path'])
    title = data.get('title', 'Index')
    
    index_content = IndexGenerator.generate(directory, title)
    output_file = directory / f"{directory.name}-Index.md"
    output_file.write_text(index_content, encoding='utf-8')
    
    entry_count = index_content.count('\n- ')
    
    return jsonify({
        'status': 'success',
        'output_file': str(output_file),
        'entry_count': entry_count
    })

@app.route('/api/analyze', methods=['POST'])
def api_analyze():
    """Analyze file"""
    data = request.json
    file_path = Path(data['path'])
    
    kf = KnowledgeFile(file_path)
    
    return jsonify({
        'path': str(file_path),
        'ref_id': kf.metadata.get('ref_id', ''),
        'languages': sorted(list(kf.languages)),
        'line_count': len(kf.content.split('\n')),
        'exceeds_limit': len(kf.content.split('\n')) > Config.MAX_FILE_LINES,
        'metadata': kf.metadata
    })

@app.route('/download/<filename>')
def download(filename):
    """Download exported file"""
    file_path = Config.EXPORT_DIR / filename
    return send_file(file_path, as_attachment=True)

if __name__ == '__main__':
    app.run(debug=True, port=5000)
