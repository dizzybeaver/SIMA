# SERVER-CONFIG.md

**Version:** 2.0.0  
**Date:** 2025-10-28  
**Purpose:** File server configuration (URL + file paths + scanning tools)  
**Usage:** Source file for generating File-Server-URLs.md

---

## ðŸŽ¯ WHAT THIS FILE IS

This file contains:
1. **Base URL** for the file server
2. **File paths** for all accessible files
3. **Environment profiles** for different deployments
4. **Scanning tools** for validation
5. **Separated data** for easy customization

**Do NOT upload this file directly to Claude sessions.**  
Instead, use this to generate `File-Server-URLs.md` with the URL generator.

---

## ðŸŒ SERVER CONFIGURATION

### Base URL

```
BASE_URL: https://claude.dizzybeaver.com
```

### Environment Profiles

**Development:**
```
BASE_URL: http://localhost:8000
Purpose: Local testing
Auth: None required
```

**Staging:**
```
BASE_URL: https://staging.dizzybeaver.com
Purpose: Pre-production validation
Auth: Basic auth required
```

**Production:**
```
BASE_URL: https://claude.dizzybeaver.com
Purpose: Live deployment
Auth: Public access (read-only)
```

**Public Release:**
```
BASE_URL: https://your-domain.com
BASE_URL: https://github.com/user/repo/raw/main
BASE_URL: https://cdn.example.com/suga-isp
Purpose: Generic placeholder for distribution
Auth: User-configured
```

---

## ðŸ" SCANNING FOR HARDCODED URLS

### Automated Scan

Run the scanning tool to find hardcoded URLs:

```bash
cd /nmap/Support/tools/
python3 scan-hardcoded-urls.py
```

**Output:** `url-audit-report.md` with all hardcoded URL locations

### What the Scanner Checks

**Patterns Detected:**
```python
# Direct URL references
"https://claude.dizzybeaver.com/..."

# web_fetch calls with hardcoded URLs
web_fetch("https://claude.dizzybeaver.com/...")

# HTML href attributes
href="https://claude.dizzybeaver.com/..."

# Markdown links
[text](https://claude.dizzybeaver.com/...)
```

**Excluded from Scan:**
- File-Server-URLs.md (output file, expected to have URLs)
- url-audit-report.md (report file)
- Example sections with `# Example URL` comment
- Documentation explaining URL patterns

### Manual Verification

**Critical files to check:**
1. All Workflow-*.md files (examples may have hardcoded URLs)
2. All Phase-*.md files (test instructions may reference URLs)
3. Documentation files (guides may show URL examples)
4. Mode context files (SESSION-START, etc.)
5. Testing files (test cases with URL examples)

**Replace patterns:**

```markdown
# WRONG - Hardcoded
web_fetch("https://claude.dizzybeaver.com/nmap/Support/SESSION-START-Quick-Context.md")

# RIGHT - Generic placeholder
web_fetch("[BASE_URL]/nmap/Support/SESSION-START-Quick-Context.md")

# OR - Example with explanation
# Example (replace BASE_URL with your server):
web_fetch("https://your-domain.com/nmap/Support/SESSION-START-Quick-Context.md")
```

### Maintenance Schedule

**Weekly:** Run `scan-hardcoded-urls.py` to check for regressions  
**Before Release:** Run full scan + manual verification  
**After New Files:** Scan new files before committing  
**Pre-Deployment:** Verify zero violations before deploying

### CI/CD Integration

**Pre-commit Hook:**
```bash
#!/bin/bash
# .git/hooks/pre-commit

# Run URL scan
python3 nmap/Support/tools/scan-hardcoded-urls.py

# Check for violations (excluding known files)
VIOLATIONS=$(grep -c "hardcoded URL found" url-audit-report.md)

if [ $VIOLATIONS -gt 0 ]; then
    echo "âŒ Hardcoded URLs detected! Run scan-hardcoded-urls.py"
    exit 1
fi

echo "âœ… No hardcoded URLs found"
exit 0
```

**GitHub Actions:**
```yaml
name: URL Audit
on: [push, pull_request]

jobs:
  scan-urls:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - name: Scan for hardcoded URLs
        run: python3 nmap/Support/tools/scan-hardcoded-urls.py
      - name: Check violations
        run: |
          if grep -q "hardcoded URL found" url-audit-report.md; then
            echo "Hardcoded URLs detected"
            cat url-audit-report.md
            exit 1
          fi
```

---

## ðŸ" DIRECTORY STRUCTURE

### Root Directories

```
/src
/nmap
/nmap/NM00
/nmap/NM01
/nmap/NM02
/nmap/NM03
/nmap/NM04
/nmap/NM05
/nmap/NM06
/nmap/NM07
/nmap/Support
/nmap/Docs
/nmap/Testing
```

---

## ðŸ"„ FILE PATHS

### Source Files (/src)

```
src/__init__.py
src/cache_core.py
src/circuit_breaker_core.py
src/cloudformation_generator.py
src/config_core.py
src/config_loader.py
src/config_param_store.py
src/config_state.py
src/config_validator.py
src/debug_aws.py
src/debug_core.py
src/debug_diagnostics.py
src/debug_discovery.py
src/debug_health.py
src/debug_performance.py
src/debug_stats.py
src/debug_validation.py
src/debug_verification.py
src/deploy_automation.py
src/fast_path.py
src/gateway.py
src/gateway_core.py
src/gateway_wrappers.py
src/github_actions_deploy.yml
src/ha_alexa.py
src/ha_build_config.py
src/ha_common.py
src/ha_config.py
src/ha_core.py
src/ha_features.py
src/ha_managers.py
src/ha_tests.py
src/ha_websocket.py
src/homeassistant_extension.py
src/http_client.py
src/http_client_core.py
src/http_client_state.py
src/http_client_transformation.py
src/http_client_utilities.py
src/http_client_validation.py
src/initialization_core.py
src/interface_cache.py
src/interface_circuit_breaker.py
src/interface_config.py
src/interface_debug.py
src/interface_http.py
src/interface_initialization.py
src/interface_logging.py
src/interface_metrics.py
src/interface_security.py
src/interface_singleton.py
src/interface_utility.py
src/interface_websocket.py
src/lambda_diagnostic.py
src/lambda_emergency.py
src/lambda_failsafe.py
src/lambda_function.py
src/lambda_function_old.py
src/lambda_ha_connection.py
src/lambda_preload.py
src/logging_core.py
src/logging_manager.py
src/logging_operations.py
src/logging_types.py
src/metrics_core.py
src/metrics_helper.py
src/metrics_operations.py
src/metrics_types.py
src/performance_benchmark.py
src/security_core.py
src/security_crypto.py
src/security_types.py
src/security_validation.py
src/singleton_conversation.py
src/singleton_core.py
src/singleton_factory.py
src/singleton_registry.py
src/utility_core.py
src/utility_general.py
src/utility_types.py
src/utility_validation.py
src/websocket_client.py
src/websocket_connection.py
src/websocket_core.py
src/websocket_manager.py
src/websocket_message_handler.py
src/websocket_reconnect.py
src/websocket_subscription.py
```

### Neural Maps - Gateway Layer (NM00)

```
nmap/NM00/NM00-Quick_Index.md
nmap/NM00/NM00A-Master_Index.md
nmap/NM00/NM00B-TOP-10-Instant_Answers.md
nmap/NM00/NM00C-Workflow_Router.md
```

### Neural Maps - Category Layer (NM01)

```
nmap/NM01/NM01-SUGA_Architecture_Overview.md
nmap/NM01/NM01A-Lambda_Entry_Points.md
nmap/NM01/NM01B-Gateway_Layer.md
nmap/NM01/NM01C-Interface_Layer.md
nmap/NM01/NM01D-Core_Layer.md
```

### Neural Maps - Topic Layer (NM02)

```
nmap/NM02/NM02-Implementation_Patterns.md
nmap/NM02/NM02A-Interfaces.md
nmap/NM02/NM02B-Core_Modules.md
nmap/NM02/NM02C-Gateway_Functions.md
```

### Neural Maps - Detail Layer (NM03)

```
nmap/NM03/NM03-Interface_Specifications.md
nmap/NM03/NM03A-INT-01-Initialization.md
nmap/NM03/NM03B-INT-02-Configuration.md
nmap/NM03/NM03C-INT-03-Logging.md
nmap/NM03/NM03D-INT-04-HTTP_Client.md
nmap/NM03/NM03E-INT-05-WebSocket_Client.md
nmap/NM03/NM03F-INT-06-Metrics.md
nmap/NM03/NM03G-INT-07-Cache.md
nmap/NM03/NM03H-INT-08-Circuit_Breaker.md
nmap/NM03/NM03I-INT-09-Security.md
nmap/NM03/NM03J-INT-10-Singleton_Manager.md
nmap/NM03/NM03K-INT-11-Utility.md
nmap/NM03/NM03L-INT-12-Debug.md
```

### Neural Maps - Individual Layer (NM04)

```
nmap/NM04/LESS-01-Always-Fetch-Current-Code.md
nmap/NM04/LESS-02-Never-Assume-Three-Layer-Entry.md
nmap/NM04/LESS-03-State-Crosses-Boundaries.md
nmap/NM04/LESS-04-Sanitize-At-Gateway-Router.md
nmap/NM04/LESS-05-Router-Blocks-Invalid-Paths.md
nmap/NM04/LESS-06-Async-Breaks-Alexa-Response.md
nmap/NM04/LESS-07-No-await-In-Lambda-Handler.md
nmap/NM04/LESS-08-Import-Rules-By-Layer.md
nmap/NM04/LESS-09-Verify-Before-Suggesting-Code.md
nmap/NM04/LESS-10-Test-In-Lambda-Not-Local.md
nmap/NM04/LESS-11-Monitor-Deployment-Size.md
nmap/NM04/LESS-12-Balance-Features-vs-Limits.md
nmap/NM04/LESS-13-Lambda-vs-Container-Tradeoffs.md
nmap/NM04/LESS-14-Correct-Context-Equals-Better-Response.md
nmap/NM04/LESS-15-Read-Not-Skim.md
nmap/NM04/BUG-01-import-gateway-lowercase.md
nmap/NM04/BUG-02-gateway-alexa-dict-error.md
nmap/NM04/BUG-03-lambda-exceeds-size.md
nmap/NM04/BUG-04-config-not-found.md
nmap/NM04/DEC-01-Lambda-Single-Threaded-No-Locks.md
nmap/NM04/DEC-02-Flat-Structure-No-Subdirs.md
nmap/NM04/DEC-03-Gateway-Interface-Core-Pattern.md
nmap/NM04/DEC-04-No-Direct-Core-Imports.md
nmap/NM04/DEC-05-WebSocket-Reconnect-Strategy.md
nmap/NM04/DEC-06-Home-Assistant-Subdirectory.md
nmap/NM04/DEC-07-128MB-Size-Limit.md
nmap/NM04/DEC-08-Cache-Key-Strategy.md
nmap/NM04/DEC-09-Circuit-Breaker-Thresholds.md
nmap/NM04/DEC-10-Metrics-Collection-Strategy.md
nmap/NM04/WISD-01-Read-Complete-Sections.md
nmap/NM04/WISD-02-Follow-Cross-References.md
nmap/NM04/WISD-03-Verify-Assumptions-With-Code.md
nmap/NM04/WISD-04-Search-Before-Asking.md
nmap/NM04/WISD-05-Check-Anti-Patterns-First.md
```

### Neural Maps - Cross-Reference (NM05)

```
nmap/NM05/NM05-Cross_Reference_Index.md
nmap/NM05/NM05A-By_Interface.md
nmap/NM05/NM05B-By_Module.md
nmap/NM05/NM05C-By_Pattern.md
```

### Neural Maps - Search Optimization (NM06)

```
nmap/NM06/NM06-Search_Keywords.md
nmap/NM06/NM06A-Common_Questions.md
nmap/NM06/NM06B-Error_Messages.md
```

### Neural Maps - Examples (NM07)

```
nmap/NM07/NM07-Code_Examples.md
nmap/NM07/NM07A-Gateway_Examples.md
nmap/NM07/NM07B-Interface_Examples.md
nmap/NM07/NM07C-Core_Examples.md
```

### Support Files

```
nmap/Support/SESSION-START-Quick-Context.md
nmap/Support/SIMA-LEARNING-SESSION-START-Quick-Context.md
nmap/Support/PROJECT-MODE-Context.md
nmap/Support/DEBUG-MODE-Context.md
nmap/Support/MODE-SELECTOR.md
nmap/Support/File Server URLs.md
nmap/Support/SERVER-CONFIG.md
nmap/Support/URL-GENERATOR-Template.md
nmap/Support/Custom Instructions for SUGA-ISP Development.md
nmap/Support/Deployment Guide - SIMA Mode System.md
nmap/Support/ANTI-PATTERNS CHECKLIST.md
nmap/Support/REF-ID-Directory.md
nmap/Support/Unified Mode System - Complete Summary.md
```

### Support Tools

```
nmap/Support/tools/file-server-config-ui.html
nmap/Support/tools/scan-hardcoded-urls.py
nmap/Support/tools/generate-urls.py
```

### Documentation Files

```
nmap/Docs/Workflow-01-AddFeature.md
nmap/Docs/Workflow-02-ModifyExisting.md
nmap/Docs/Workflow-03-Debug.md
nmap/Docs/Workflow-04-Refactor.md
nmap/Docs/Workflow-05-Documentation.md
nmap/Docs/Workflow-06-Testing.md
nmap/Docs/Workflow-07-Deployment.md
nmap/Docs/Workflow-08-Monitoring.md
nmap/Docs/Workflow-09-Optimization.md
nmap/Docs/Workflow-10-Learning.md
nmap/Docs/Workflow-11-Architecture-Questions.md
```

### Testing Files

```
nmap/Testing/Phase-0-File-Server-Config-Tests.md
nmap/Testing/Phase-1-Categorization-Tests.md
nmap/Testing/Phase-2-Reference-Tests.md
nmap/Testing/SIMAv4-Master-Control-Implementation.md
nmap/Testing/SIMAv4-Phase-0-File-Server-Config.md
nmap/Testing/SIMAv4-Phase-0-Suggestions.md
nmap/Testing/SIMAv4-Implementation-Phase-Breakdown.md
```

---

## ðŸ"Š FILE STATISTICS

**Total Files:** ~300  
**Source Files:** 80  
**Neural Map Files:** 150  
**Support Files:** 20  
**Documentation Files:** 30  
**Testing Files:** 20

**Total Directories:** 15  
**Max Depth:** 3 levels  
**Largest Category:** NM04 (Individual entries)

---

## ðŸ"§ USAGE INSTRUCTIONS

### Step 1: Configure BASE_URL

Edit this file and set BASE_URL to your server:

```markdown
BASE_URL: https://your-domain.com
```

### Step 2: Add/Remove File Paths

Update the FILE PATHS section as needed:

```markdown
# Add new file
src/new_module.py

# Remove file (delete line)
# src/old_module.py
```

### Step 3: Generate File-Server-URLs.md

Use one of three methods:

**Method A: Web Interface**
1. Open `file-server-config-ui.html` in browser
2. Paste BASE_URL
3. Paste file paths
4. Click "Generate"
5. Copy output

**Method B: Claude-Assisted**
1. Upload this file to Claude
2. Upload URL-GENERATOR-Template.md
3. Say: "Generate File-Server-URLs.md"
4. Claude creates artifact
5. Save artifact

**Method C: Python Script**
1. Run `python3 generate-urls.py`
2. File-Server-URLs.md created automatically
3. Verify output

### Step 4: Validate Output

Run validation scan:

```bash
python3 scan-hardcoded-urls.py
```

Expected result: **0 violations** (except File-Server-URLs.md)

### Step 5: Deploy

1. Commit File-Server-URLs.md to repository
2. Upload to Claude sessions
3. Test web_fetch with sample URLs
4. Verify accessibility

---

## âš ï¸ IMPORTANT NOTES

### Security

- **Public servers:** Ensure files are read-only
- **Private servers:** Configure authentication
- **Sensitive data:** Exclude from file paths
- **API keys:** Never in file paths or URLs

### Performance

- **File count:** Keep under 500 files
- **URL length:** Keep paths under 200 chars
- **Directory depth:** Keep under 4 levels
- **File size:** Monitor total deployment size

### Maintenance

- **Weekly scans:** Check for hardcoded URLs
- **Before release:** Full audit + validation
- **After changes:** Regenerate File-Server-URLs.md
- **Version control:** Track all changes

---

## ðŸ"„ VERSION HISTORY

**v2.0.0 (2025-10-28):** # ADDED: Scanning section, environment profiles, CI/CD integration  
**v1.0.0 (2025-10-25):** Initial version with BASE_URL and file paths

---

**END OF SERVER-CONFIG.md**
