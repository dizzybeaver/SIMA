# PROJECT-MODE-Context.md

**Version:** 4.0.0  
**Date:** 2025-11-08  
**Purpose:** Project-agnostic development context  
**Activation:** "Start Project Work Mode for {PROJECT}"  
**Load time:** 20-30 seconds (loads base + project extension)  
**Updated:** Optimized with shared knowledge references

---

## WHAT THIS MODE IS

**Project Mode** enables active development for specific projects.

**Use for:**
- Building features
- Modifying code
- Creating implementations
- Extending functionality

**Not for:** Q&A (General Mode), Debugging (Debug Mode)

---

## FILE RETRIEVAL

**Session Start:**
1. User uploads File Server URLs.md
2. Claude fetches fileserver.php automatically
3. Receives ~412 cache-busted URLs (69ms)
4. Fresh files guaranteed

**Why:** Anthropic caches for weeks. fileserver.php bypasses with random ?v= parameters.

**REF:** WISD-06

---

## CRITICAL RULES

### Rule 1: Always Fetch First
**Before ANY modification:**
1. Use fileserver.php URLs
2. Fetch COMPLETE current file
3. Read ENTIRE file
4. THEN modify

**REF:** LESS-01, WISD-06

### Rule 2: Complete Files Only
**All code output:**
- âœ… Complete file artifacts
- âœ… ALL existing code included
- âœ… Changes marked with comments
- âœ… Immediately deployable
- âœ… Filename in header
- âŒ Code in chat
- âŒ Fragments or snippets
- âŒ "Add to line X" instructions

**Complete Standards:** `/sima/shared/Artifact-Standards.md`

### Rule 3: Follow Architecture
**Architecture depends on project. Common patterns:**
- SUGA: Gateway → Interface → Core
- LMMS: Lazy module loading
- DD-1: Dictionary dispatch (performance)
- DD-2: Dependency disciplines (structure)

**Project extension specifies which patterns apply.**

**Architecture Details:** `/sima/shared/SUGA-Architecture.md` (if using SUGA)

### Rule 4: Verify Before Output
**Pre-output checklist:**
```
[ ] Fetched current file? (fileserver.php)
[ ] Read complete file?
[ ] Including ALL existing code?
[ ] Changes marked?
[ ] Complete file (not fragment)?
[ ] Deployable immediately?
[ ] Creating artifact (not chat)?
[ ] Filename in header?
[ ] Chat minimal?
```

**REF:** LESS-15

### Rule 5: Follow File Standards
**All artifacts:**
- Header with filename, version, date, purpose
- UTF-8 encoding, LF line endings
- Proper naming conventions
- Within size limits (source code: no limit)
- No trailing whitespace
- Final newline

**Complete Standards:** `/sima/shared/File-Standards.md`

---

## ARTIFACT RULES

**MANDATORY for all code output:**

### When to Create Artifacts
```
Code > 20 lines → Artifact
Any file modification → Artifact
Configuration files → Artifact
```

### Artifact Requirements
```
[OK] Complete files always
[OK] Mark changes (# ADDED:, # MODIFIED:)
[OK] Filename in header
[OK] Immediately deployable
[OK] All existing code included
[X] Never code in chat
[X] Never fragments
[X] Never "add to line X"
```

### Marking Changes Example
```python
# ADDED: New feature for cold start optimization
def new_function():
    """New functionality."""
    pass

# MODIFIED: Enhanced error handling
def existing_function():
    # ADDED: Validation
    if not valid:
        raise ValueError()
    # Original logic continues
```

**Complete Standards:** `/sima/shared/Artifact-Standards.md`

---

## IMPLEMENTATION TEMPLATES

### Adding New Function (Generic)

**Step 1: Plan Implementation**
- Which file(s) need modification?
- What's the function signature?
- What's the logic?
- Any dependencies?

**Step 2: Fetch Current File**
```
Use fileserver.php URLs
Read complete file
Understand current structure
```

**Step 3: Implement**
```python
# ADDED: [Brief description of feature]
def new_function(param1, param2):
    """
    [Description]
    
    Args:
        param1: [Type and description]
        param2: [Type and description]
        
    Returns:
        [Type]: [Description]
        
    Raises:
        [Exception]: [When and why]
    """
    # Implementation
    try:
        result = process(param1, param2)
        return result
    except SpecificError as e:
        # Never bare except
        log_error(f"Error: {e}")
        raise
```

**Step 4: Output Complete File**
- Include ALL existing code
- Add your implementation
- Mark changes with comments
- Output as artifact

---

## RED FLAGS

**Never suggest:**

| Flag | Why | REF |
|------|-----|-----|
| Code in chat | Token waste | SIMAv4 |
| File fragments | Not deployable | SIMAv4 |
| Skip file fetch | Stale code | LESS-01 |
| Skip fileserver.php | Week-old files | WISD-06 |
| Bare except | Swallows errors | AP-14 |

**Project-specific RED FLAGS loaded from project extension.**

**Complete List:** `/sima/shared/RED-FLAGS.md`

---

## WORKFLOW

### Standard Implementation Flow

```
1. User describes task
   "Add feature X to file Y"

2. Claude fetches files
   "Fetching via fileserver.php..."
   Uses cache-busted URLs
   Reads complete files

3. Claude implements
   "Creating artifact..."
   Follows project architecture
   Applies project patterns
   Marks changes

4. Claude outputs
   Complete file artifact
   Filename in header
   All existing code + changes
   Deployable immediately
   "Artifact ready."

5. User reviews and deploys
```

---

## PROJECT EXTENSIONS

**Project Mode combines:**
1. This base file (project-agnostic)
2. Project extension (project-specific)

**Extension provides:**
- Project architecture (SUGA, LMMS, etc.)
- Project constraints (memory, timeout, etc.)
- Project patterns (specific implementations)
- Project RED FLAGS (additional warnings)
- Project examples (real-world cases)

**Example:**
```
"Start Project Work Mode for LEE"
→ Loads PROJECT-MODE-Context.md (this file)
→ Loads /projects/lee/modes/PROJECT-MODE-LEE.md
→ Combined context ready
```

---

## COMMON PATTERNS

**Import Patterns:**
```python
# Function-level lazy import
def my_function():
    import required_module
    return required_module.process()
```

**Error Handling:**
```python
# Specific exceptions only
try:
    result = operation()
except SpecificError as e:
    log_error(f"Specific error: {e}")
    raise
except AnotherError as e:
    log_error(f"Another error: {e}")
    raise
```

**Validation:**
```python
# Validate inputs
def process(data):
    if not validate(data):
        raise ValueError("Invalid data")
    return transform(data)
```

**Complete Patterns:** `/sima/shared/Common-Patterns.md`

---

## VERIFICATION CHECKLIST

**Before every implementation:**

1. âœ… fileserver.php fetched?
2. âœ… Current file fetched?
3. âœ… Complete file read?
4. âœ… Architecture understood?
5. âœ… RED FLAGS checked?
6. âœ… Changes marked?
7. âœ… Complete artifact?
8. âœ… Deployable code?
9. âœ… Header included?
10. âœ… Chat minimal?

**REF:** LESS-15

---

## READY

**Context loaded when you remember:**
- âœ… fileserver.php fetched (automatic)
- âœ… Complete files ONLY (never fragments)
- âœ… Always fetch first (fileserver.php URLs)
- âœ… Changes marked (# ADDED:, # MODIFIED:)
- âœ… Architecture from project extension
- âœ… RED FLAGS from base + project
- âœ… Code ALWAYS in artifacts
- âœ… Chat output minimal

**Now await project-specific extension load!**

---

**END OF PROJECT MODE CONTEXT (BASE)**

**Version:** 4.0.0 (Project-agnostic optimization)  
**Lines:** 250 (target achieved)  
**Reduction:** 396 → 250 lines (37% reduction)  
**Load time:** 20-30 seconds (plus project extension)  
**References:** Shared knowledge in `/sima/shared/`  
**Note:** Must be combined with project extension for full context
