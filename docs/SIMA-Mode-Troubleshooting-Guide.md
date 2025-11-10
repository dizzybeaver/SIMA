# SIMA-Mode-Troubleshooting-Guide.md

**Version:** 1.0.0  
**Purpose:** Solutions for common mode system issues  
**Format:** Problem → Diagnosis → Solution

---

## 🆘 QUICK TRIAGE

### Symptoms → Section

```
Mode won't activate          → Section 1
Wrong context loaded         → Section 2
Files are outdated           → Section 3
Can't switch modes           → Section 4
Getting wrong responses      → Section 5
Performance issues           → Section 6
```

---

## ❌ SECTION 1: MODE WON'T ACTIVATE

### Problem 1.1: Nothing happens after activation phrase

**Symptoms:**
- Said "Please load context" but no response
- Said "Start X Mode" but nothing loads
- No confirmation message

**Diagnosis:**
```
Check:
1. Did you upload File Server URLs.md?
2. Is the activation phrase exact?
3. Did you wait long enough?
```

**Solutions:**

**Solution A: File not uploaded**
```
Issue: File Server URLs.md not uploaded
Fix:
1. Upload File Server URLs.md
2. Say activation phrase again
3. Wait for confirmation
```

**Solution B: Wrong phrase**
```
Issue: Activation phrase not exact
Wrong: "Load context please"
Right: "Please load context"

Wrong: "Start project mode"
Right: "Start Project Mode for LEE"

Wrong: "Debug LEE"
Right: "Start Debug Mode for LEE"
```

**Solution C: Not waiting**
```
Issue: Didn't wait for completion
Expected: 20-60 seconds depending on mode
Action: Wait for "ready" or confirmation message
```

### Problem 1.2: Error message about missing file

**Symptoms:**
- "Cannot fetch fileserver.php"
- "File not found"
- "URL not accessible"

**Diagnosis:**
```
Check:
1. Is File Server URLs.md formatted correctly?
2. Does it contain the fileserver.php URL?
3. Is the URL complete (with ?v= parameter)?
```

**Solutions:**

**Solution A: Check file format**
```
File Server URLs.md should contain:

https://claude.dizzybeaver.com/fileserver.php?v=0053

✓ Must include ?v= parameter
✓ Must be exact URL from file
✓ No extra spaces or characters
```

**Solution B: Re-download File Server URLs.md**
```
1. Get latest version of File Server URLs.md
2. Upload to Claude
3. Try activation again
```

### Problem 1.3: Mode loads but seems incomplete

**Symptoms:**
- Mode activates but responses are generic
- Missing project-specific knowledge
- Claude doesn't seem to know project details

**Diagnosis:**
```
Check:
1. Did you specify project name?
2. Does project extension exist?
3. Is context load complete?
```

**Solutions:**

**Solution A: Specify project**
```
Wrong:
"Start Project Mode"

Right:
"Start Project Mode for LEE"
"Start Project Mode for SIMA"
```

**Solution B: Verify project exists**
```
Check that project has extension files:
/projects/lee/modes/PROJECT-MODE-LEE.md
/projects/sima/modes/PROJECT-MODE-SIMA.md

If missing, use New Project Mode to create
```

**Solution C: Wait for full load**
```
Mode loads in stages:
1. Base mode context (20-30s)
2. Project extension (5-10s)
3. fileserver.php fetch (5-10s)

Total: 30-50 seconds
Wait for "ready" confirmation
```

---

## ❌ SECTION 2: WRONG CONTEXT LOADED

### Problem 2.1: Got LEE context when wanted SIMA

**Symptoms:**
- Asked for SIMA but getting Lambda advice
- References to Home Assistant
- Wrong project constraints

**Diagnosis:**
```
Check:
1. Did you specify correct project name?
2. Did previous mode bleed through?
```

**Solutions:**

**Solution A: Explicit project switch**
```
Clear instruction:
"Start Project Mode for SIMA"

Wait for confirmation:
"Project Mode for SIMA activated"
"SIMA-specific context loaded"

Then proceed
```

**Solution B: Verify context**
```
Ask Claude to confirm:
"What project context is currently loaded?"

Claude should respond:
"SIMA project context" or "LEE project context"
```

### Problem 2.2: Generic responses when need project-specific

**Symptoms:**
- Getting generic SUGA advice for LEE-specific question
- No mention of Lambda constraints
- Missing project patterns

**Diagnosis:**
```
Check:
1. Are you in correct mode?
2. Is project extension loaded?
```

**Solutions:**

**Solution A: Use correct mode**
```
For LEE development:
"Start Project Mode for LEE"

For SIMA documentation:
"Start Project Mode for SIMA"

For general questions:
"Please load context"
```

**Solution B: Re-activate with project**
```
1. Say activation phrase with project
2. Wait for confirmation
3. Verify project context loaded
```

---

## ❌ SECTION 3: FILES ARE OUTDATED

### Problem 3.1: Changes I made aren't showing

**Symptoms:**
- Modified gateway.py but Claude sees old version
- Updated documentation but Claude references old content
- Recent changes not visible

**Diagnosis:**
```
Check:
1. Was File Server URLs.md uploaded?
2. Did fileserver.php fetch complete?
3. Is Claude using fileserver.php URLs?
```

**Solutions:**

**Solution A: Re-upload File Server URLs.md**
```
CRITICAL: Upload at start of EVERY session

1. Upload File Server URLs.md
2. Verify fileserver.php fetched
3. Check for random ?v= parameters in URLs
4. Proceed with work
```

**Solution B: Verify fresh fetch**
```
Ask Claude:
"Did you fetch via fileserver.php URLs?"

Claude should confirm:
"Yes, fetched via fileserver.php URLs with cache-busting"
```

**Solution C: Manual file specification**
```
If automatic fetch didn't work:
"Please fetch gateway.py using fileserver.php URL"

Claude will:
1. Find URL in fileserver.php output
2. Use cache-busted URL
3. Get fresh content
```

### Problem 3.2: Documentation references don't exist

**Symptoms:**
- REF-ID not found
- File path 404
- Entry doesn't exist

**Diagnosis:**
```
Check:
1. Has knowledge base been reorganized?
2. Is REF-ID deprecated?
3. Was file moved?
```

**Solutions:**

**Solution A: Use Debug Mode for SIMA**
```
"Start Debug Mode for SIMA"
"Can't find ARCH-DD reference"

Claude will:
1. Explain ARCH-DD deprecated
2. Point to DD-1 or DD-2
3. Provide correct reference
```

**Solution B: Search by topic**
```
"Please load context"
"Where can I find information about [topic]?"

Claude will:
1. Search current structure
2. Provide correct location
3. Give updated REF-ID
```

---

## ❌ SECTION 4: CAN'T SWITCH MODES

### Problem 4.1: Stuck in one mode

**Symptoms:**
- Said new activation phrase but still in old mode
- Context didn't update
- Getting responses from wrong mode

**Diagnosis:**
```
Check:
1. Did you use complete activation phrase?
2. Did you wait for new mode confirmation?
3. Is new mode trying to load?
```

**Solutions:**

**Solution A: Complete activation phrase**
```
Wrong:
"Switch to Debug Mode"
"Change mode to Learning"

Right:
"Start Debug Mode for LEE"
"Start SIMA Learning Mode"
```

**Solution B: Wait for transition**
```
Mode switching takes time:
1. Old context unloads (5s)
2. New context loads (30-50s)
3. Confirmation message

Be patient, don't interrupt
```

**Solution C: Restart if needed**
```
If truly stuck:
1. End current conversation
2. Start new conversation
3. Upload File Server URLs.md
4. Activate desired mode
```

### Problem 4.2: Mode mixing (getting behaviors from multiple modes)

**Symptoms:**
- Getting both Q&A and code artifacts
- Responses seem confused
- Multiple mode behaviors in one response

**Diagnosis:**
```
Check:
1. Did mode switch complete?
2. Are you using correct activation phrase?
3. Is request ambiguous?
```

**Solutions:**

**Solution A: Clear mode activation**
```
Explicitly switch:
"Start Project Mode for LEE"

Wait for:
"Project Mode for LEE activated"

Then make request
```

**Solution B: Clarify your intent**
```
Instead of:
"Tell me about caching and add a cache function"

Do:
1. "Please load context" → Learn about caching
2. "Start Project Mode for LEE" → Add function
```

---

## ❌ SECTION 5: GETTING WRONG RESPONSES

### Problem 5.1: Claude not following SUGA pattern

**Symptoms:**
- Code doesn't use gateway
- Missing layers
- Direct imports suggested

**Diagnosis:**
```
Check:
1. Are you in Project Mode?
2. Is project LEE (SUGA-based)?
3. Did context load correctly?
```

**Solutions:**

**Solution A: Use Project Mode**
```
For SUGA-compliant code:
"Start Project Mode for LEE"

Claude will:
✓ Implement all 3 layers
✓ Use gateway imports
✓ Follow SUGA rules
```

**Solution B: Verify RED FLAGS loaded**
```
Ask Claude:
"What RED FLAGS apply to this project?"

For LEE should mention:
- No direct core imports
- No threading
- All via gateway
```

### Problem 5.2: Code in chat instead of artifacts

**Symptoms:**
- Getting code snippets in chat
- Partial code fragments
- "Add this to line X" instructions

**Diagnosis:**
```
Check:
1. Are you in correct mode?
2. Did you request code?
3. Is artifact system working?
```

**Solutions:**

**Solution A: Mode should auto-artifact**
```
Project Mode and Debug Mode automatically:
✓ Create complete file artifacts
✓ Never output code in chat
✓ Mark changes with comments

If not happening, context didn't load correctly
```

**Solution B: Re-activate mode**
```
1. "Start Project Mode for [PROJECT]"
2. Wait for full load
3. Request code again
```

### Problem 5.3: Not checking for duplicates in Learning Mode

**Symptoms:**
- Creating duplicate entries
- Similar entries already exist
- No mention of checking duplicates

**Diagnosis:**
```
Check:
1. Is Learning Mode active?
2. Did fileserver.php fetch complete?
3. Is duplicate checking working?
```

**Solutions:**

**Solution A: Verify Learning Mode**
```
"Start SIMA Learning Mode"

Should explicitly mention:
"Checking for duplicates..."
"Fetching via fileserver.php URLs..."
```

**Solution B: Request duplicate check**
```
"Before creating this entry, check for duplicates"

Claude will:
1. Search existing entries
2. Use fileserver.php URLs (fresh content)
3. Report if found
4. Update existing or create new
```

---

## ❌ SECTION 6: PERFORMANCE ISSUES

### Problem 6.1: Mode takes too long to load

**Symptoms:**
- Wait time > 90 seconds
- Seems stuck
- No progress indication

**Diagnosis:**
```
Check:
1. Network connectivity
2. fileserver.php performance
3. Mode complexity
```

**Solutions:**

**Solution A: Normal load times**
```
Expected:
- General Mode: 20-30s
- Learning Mode: 45-60s
- Maintenance Mode: 30-45s
- Project Mode: 35-50s
- Debug Mode: 35-50s
- New Project Mode: 30-45s

Wait up to 2x expected before concern
```

**Solution B: Server issues**
```
If > 2 minutes:
1. Check if fileserver.php accessible
2. Try in new conversation
3. Wait a moment and retry
```

### Problem 6.2: Responses are slow

**Symptoms:**
- Long pauses between responses
- Thinking for extended periods
- Timeouts

**Diagnosis:**
```
Check:
1. Request complexity
2. Files being fetched
3. Knowledge base size
```

**Solutions:**

**Solution A: Break down requests**
```
Instead of:
"Add 5 features, debug 3 issues, update all indexes"

Do:
"Add feature 1"
[Complete]
"Add feature 2"
[Complete]
etc.
```

**Solution B: Use appropriate mode**
```
Complex tasks may need different mode:
- Building → Project Mode
- Fixing → Debug Mode
- Organizing → Maintenance Mode

Don't mix in one request
```

---

## ✅ PREVENTION CHECKLIST

### Session Start

```
[ ] Upload File Server URLs.md
[ ] Use exact activation phrase
[ ] Specify project name (if needed)
[ ] Wait for confirmation
[ ] Verify context loaded
```

### During Work

```
[ ] One mode = one task type
[ ] Switch modes when task changes
[ ] Wait for switch confirmation
[ ] Check responses match mode
[ ] Request re-activation if confused
```

### Common Mistakes to Avoid

```
❌ Forgetting File Server URLs.md
❌ Approximate activation phrases
❌ Not specifying project
❌ Not waiting for confirmation
❌ Mixing mode purposes
❌ Assuming context persists
```

---

## 🔍 DIAGNOSTIC QUESTIONS

### If something seems wrong, ask:

**About mode:**
```
"What mode are we in?"
"What project context is loaded?"
"Did fileserver.php fetch complete?"
```

**About files:**
```
"Are you using fileserver.php URLs?"
"What version of [file] do you see?"
"Can you fetch fresh [file]?"
```

**About behavior:**
```
"Why did you output code in chat?"
"Why no duplicate check?"
"Why not all 3 SUGA layers?"
```

**Claude will diagnose and explain**

---

## 🆘 WHEN ALL ELSE FAILS

### Nuclear Option: Complete Reset

```
1. End current conversation
2. Start completely new conversation
3. Upload File Server URLs.md
4. Use exact activation phrase
5. Wait for full confirmation
6. Verify correct context
7. Proceed carefully
```

### Get Help

```
If issues persist:
1. Document exact steps
2. Note error messages
3. Describe expected vs actual
4. Check documentation:
   - SIMA-Context-System-Usage-Examples.md
   - SIMA-Mode-Selection-Guide.md
   - MODE-SELECTOR.md
```

---

## 📊 ISSUE FREQUENCY

### Most Common Issues (Fix These First)

1. **Forgot File Server URLs.md** (40%)
   - Fix: Always upload first

2. **Wrong activation phrase** (25%)
   - Fix: Use exact phrases

3. **Didn't specify project** (15%)
   - Fix: "for [PROJECT]"

4. **Didn't wait for confirmation** (10%)
   - Fix: Wait 30-60 seconds

5. **Mixed mode purposes** (10%)
   - Fix: One mode per task

### Rare Issues (Advanced)

- fileserver.php timeout
- Network connectivity
- Context file corruption
- Version mismatches

---

## 🎯 SUCCESS INDICATORS

### You'll know it's working when:

```
✓ Mode activates in < 60 seconds
✓ Confirmation message appears
✓ Responses match mode purpose
✓ Project context is correct
✓ Files are fresh (recent changes visible)
✓ Artifacts for code (not chat)
✓ Duplicate checks in Learning Mode
✓ SUGA pattern in Project Mode
✓ Can switch modes easily
✓ No confusion or errors
```

---

## 📞 QUICK REFERENCE

### Emergency Commands

```
"What mode are we in?"
"Did you fetch fileserver.php?"
"Are you using fresh files?"
"What project context is loaded?"
"Start [MODE] for [PROJECT]"
```

### Fixes That Solve 90% of Issues

1. Upload File Server URLs.md
2. Use exact activation phrase
3. Specify project name
4. Wait for confirmation
5. Switch modes when task changes

---

**REMEMBER:**
Most issues are simple:
- Forgot to upload file
- Wrong activation phrase
- Didn't wait long enough

**Check these three first!**

---

**END OF TROUBLESHOOTING GUIDE**

**Version:** 1.0.0  
**Coverage:** Common issues and solutions  
**Next:** Refer to usage examples for correct patterns
