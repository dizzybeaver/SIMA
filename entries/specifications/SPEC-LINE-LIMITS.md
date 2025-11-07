# SPEC-LINE-LIMITS.md

**Version:** 1.0.0  
**Date:** 2025-11-06  
**Purpose:** File size limits for all SIMA content  
**Category:** Specifications

---

## PURPOSE

Define maximum line limits for different file types to ensure readability, maintainability, and efficient token usage.

---

## UNIVERSAL LIMITS

### Default Limit
**400 lines** for all documentation files

**Rationale:**
- Readable in single session
- Fits in context windows
- Encourages focused content
- Easy to navigate

### When to Split
If content exceeds 400 lines:
1. Identify logical boundaries
2. Create multiple focused files
3. Link files with cross-references
4. Update indexes

---

## FILE TYPE LIMITS

### Neural Map Entries (400 lines)
- Lessons (LESS-##): 400 lines max
- Decisions (DEC-##): 400 lines max
- Anti-Patterns (AP-##): 400 lines max
- Bugs (BUG-##): 400 lines max
- Wisdom (WISD-##): 400 lines max

**Exception:** None - split into multiple entries if needed

### Specifications (400 lines)
- All SPEC-## files: 400 lines max
- Focus on single standard
- Reference related specs

### Context Files (500 lines)
- Mode context files: 500 lines max
- Custom instructions: 500 lines max
- Bootstrap files: 500 lines max

**Rationale:** Need comprehensive guidelines for session setup

### Support Files (400 lines)
- Workflows: 400 lines max
- Tools: 400 lines max
- Templates: 400 lines max
- Checklists: 400 lines max

### Project Files (400 lines)
- Project docs: 400 lines max
- Configuration: 400 lines max
- README files: 400 lines max

### Source Code (No Limit)
- Python files: No limit
- Must be complete and deployable
- Functional requirements take priority

**Rationale:** Code must work; splitting breaks functionality

### Index Files (No Limit)
- Master indexes: No limit
- Generated automatically
- Comprehensive listings needed

---

## CONTENT OPTIMIZATION

### Strategies for Staying Under Limit

**1. Brevity First**
- Every word must earn its place
- Remove filler phrases
- Direct, concise language
- Active voice preferred

**2. Examples Minimal**
- 2-3 line code examples
- Single-line explanations
- Reference full examples elsewhere

**3. Cross-Reference Heavy**
- Link to related content
- Don't duplicate information
- Build knowledge graph

**4. Section Focus**
- One topic per file
- Clear boundaries
- Logical separation

---

## MEASUREMENT

### Line Counting Rules
- Count all lines including blank
- Headers count as lines
- Code blocks count all lines
- Exclude front matter (if any)

### Verification
```bash
wc -l filename.md
```

### Automated Checks
- Pre-commit hooks
- CI/CD validation
- fileserver.php warnings

---

## EXCEEDING LIMITS

### Process
1. Attempt to condense first
2. If impossible, split content
3. Create Part 1, Part 2, etc.
4. Update cross-references
5. Update indexes

### Example Split
```
Original: LESS-50-Complete.md (600 lines)

Split into:
- LESS-50-Part1-Overview.md (300 lines)
- LESS-50-Part2-Implementation.md (300 lines)

Or better:
- LESS-50-Pattern.md (300 lines)
- LESS-51-Implementation.md (300 lines)
(Two distinct lessons)
```

---

## BENEFITS

**400-line limit provides:**
- Focused content (one topic)
- Better organization (clear boundaries)
- Faster discovery (smaller files)
- Easier updates (contained changes)
- Token efficiency (Claude can load full file)
- Better readability (not overwhelming)

**Trade-offs:**
- More files to manage
- Need good indexes
- Cross-referencing essential

---

## QUALITY CHECKLIST

- [ ] File under 400 lines (or appropriate limit)
- [ ] Content focused on single topic
- [ ] No unnecessary verbosity
- [ ] Examples concise (2-3 lines)
- [ ] Cross-references instead of duplication
- [ ] If over limit, split or condense

---

**Related:**
- SPEC-FILE-STANDARDS.md
- SPEC-MARKDOWN.md
- SPEC-STRUCTURE.md

**Version History:**
- v1.0.0 (2025-11-06): Initial specification with 400-line universal limit

---

**END OF FILE**
