# SERVER-CONFIG.md

**Version:** 1.0.0  
**Date:** 2025-10-25  
**Purpose:** File server configuration (URL + file paths)  
**Usage:** Source file for generating File-Server-URLs.md

---

## 🎯 WHAT THIS FILE IS

This file contains:
1. **Base URL** for the file server
2. **File paths** for all accessible files
3. **Separated data** for easy customization

**Do NOT upload this file directly to Claude sessions.**  
Instead, use this to generate `File-Server-URLs.md` with the URL generator.

---

## 🌐 SERVER CONFIGURATION

### Base URL

```
BASE_URL: https://claude.dizzybeaver.com
```

**For public release, change to:**
```
BASE_URL: https://your-domain.com
BASE_URL: https://github.com/user/repo/raw/main
BASE_URL: https://cdn.example.com/suga-isp
```

---

## 📁 DIRECTORY STRUCTURE

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

## 📄 FILE PATHS

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
src/singleton_convenience.py
src/singleton_core.py
src/singleton_memory.py
src/test_config_gateway.py
src/test_config_integration.py
src/test_config_performance.py
src/test_config_unit.py
src/test_presets.py
src/user_config.py
src/utility_core.py
src/utility_cross_interface.py
src/utility_response.py
src/utility_types.py
src/utility_validation_advanced.py
src/utility_validation_core.py
src/variables.py
src/variables_utils.py
src/websocket_core.py
```

### Neural Maps - Gateway Layer (/nmap/NM00)

```
nmap/NM00/NM00-Quick_Index.md
nmap/NM00/NM00A-Master_Index.md
nmap/NM00/NM00B - ZAPH Reorganization.md
nmap/NM00/NM00B-ZAPH-Tier1.md
nmap/NM00/NM00B-ZAPH-Tier2.md
nmap/NM00/NM00B-ZAPH-Tier3.md
nmap/NM00/NM00B-ZAPH.md
```

### Neural Maps - Architecture (/nmap/NM01)

```
nmap/NM01/NM01-Architecture-CoreArchitecture_Index.md
nmap/NM01/NM01-Architecture-InterfacesAdvanced_Index.md
nmap/NM01/NM01-Architecture-InterfacesAdvanced_INT-07.md
nmap/NM01/NM01-Architecture-InterfacesAdvanced_INT-08.md
nmap/NM01/NM01-Architecture-InterfacesAdvanced_INT-09.md
nmap/NM01/NM01-Architecture-InterfacesAdvanced_INT-10.md
nmap/NM01/NM01-Architecture-InterfacesAdvanced_INT-11.md
nmap/NM01/NM01-Architecture-InterfacesAdvanced_INT-12.md
nmap/NM01/NM01-Architecture-InterfacesCore_Index.md
nmap/NM01/NM01-Architecture-InterfacesCore_INT-01.md
nmap/NM01/NM01-Architecture-InterfacesCore_INT-02.md
nmap/NM01/NM01-Architecture-InterfacesCore_INT-03.md
nmap/NM01/NM01-Architecture-InterfacesCore_INT-04.md
nmap/NM01/NM01-Architecture-InterfacesCore_INT-05.md
nmap/NM01/NM01-Architecture-InterfacesCore_INT-06.md
nmap/NM01/NM01-Architecture_ARCH-09.md
nmap/NM01/NM01-INDEX-Architecture.md
nmap/NM01/SUGA-Module-Size-Limits.md
```

### Neural Maps - Dependencies (/nmap/NM02)

```
nmap/NM02/NM02-Dependencies-ImportRules_Index.md
nmap/NM02/NM02-Dependencies-ImportRules_RULE-02.md
nmap/NM02/NM02-Dependencies-ImportRules_RULE-03.md
nmap/NM02/NM02-Dependencies-ImportRules_RULE-04.md
nmap/NM02/NM02-Dependencies-InterfaceDetail_CACHE-DEP.md
nmap/NM02/NM02-Dependencies-InterfaceDetail_CONFIG-DEP.md
nmap/NM02/NM02-Dependencies-InterfaceDetail_HTTP-DEP.md
nmap/NM02/NM02-Dependencies-InterfaceDetail_Index.md
nmap/NM02/NM02-Dependencies-Layers_DEP-01.md
nmap/NM02/NM02-Dependencies-Layers_DEP-02.md
nmap/NM02/NM02-Dependencies-Layers_DEP-03.md
nmap/NM02/NM02-Dependencies-Layers_DEP-04.md
nmap/NM02/NM02-Dependencies-Layers_DEP-05.md
nmap/NM02/NM02-Dependencies-Layers_Index.md
nmap/NM02/NM02-Dependencies_Index.md
nmap/NM02/NM02-RULES-Import_RULE-01.md
```

### Neural Maps - Operations (/nmap/NM03)

```
nmap/NM03/NM03-Operations-ErrorHandling.md
nmap/NM03/NM03-Operations-Flows.md
nmap/NM03/NM03-Operations-Pathways.md
nmap/NM03/NM03-Operations-Tracing.md
nmap/NM03/NM03-Operations_Index.md
```

### Neural Maps - Decisions (/nmap/NM04)

```
nmap/NM04/NM04-Decisions-Architecture_DEC-01.md
nmap/NM04/NM04-Decisions-Architecture_DEC-02.md
nmap/NM04/NM04-Decisions-Architecture_DEC-03.md
nmap/NM04/NM04-Decisions-Architecture_DEC-04.md
nmap/NM04/NM04-Decisions-Architecture_DEC-05.md
nmap/NM04/NM04-Decisions-Architecture_Index.md
nmap/NM04/NM04-Decisions-Operational_DEC-20.md
nmap/NM04/NM04-Decisions-Operational_DEC-21.md
nmap/NM04/NM04-Decisions-Operational_DEC-22.md
nmap/NM04/NM04-Decisions-Operational_DEC-23.md
nmap/NM04/NM04-Decisions-Operational_Index.md
nmap/NM04/NM04-Decisions-Technical_DEC-12.md
nmap/NM04/NM04-Decisions-Technical_DEC-13.md
nmap/NM04/NM04-Decisions-Technical_DEC-14.md
nmap/NM04/NM04-Decisions-Technical_DEC-15.md
nmap/NM04/NM04-Decisions-Technical_DEC-16.md
nmap/NM04/NM04-Decisions-Technical_DEC-17.md
nmap/NM04/NM04-Decisions-Technical_DEC-18.md
nmap/NM04/NM04-Decisions-Technical_DEC-19.md
nmap/NM04/NM04-Decisions-Technical_Index.md
nmap/NM04/NM04-Decisions_Index.md
```

### Neural Maps - Anti-Patterns (/nmap/NM05)

```
nmap/NM05/NM05-AntiPatterns-Concurrency_AP-08.md
nmap/NM05/NM05-AntiPatterns-Concurrency_AP-11.md
nmap/NM05/NM05-AntiPatterns-Concurrency_AP-13.md
nmap/NM05/NM05-AntiPatterns-Concurrency_Index.md
nmap/NM05/NM05-AntiPatterns-Critical_AP-10.md
nmap/NM05/NM05-AntiPatterns-Critical_Index.md
nmap/NM05/NM05-AntiPatterns-Dependencies_AP-09.md
nmap/NM05/NM05-AntiPatterns-Dependencies_Index.md
nmap/NM05/NM05-AntiPatterns-Documentation_AP-25.md
nmap/NM05/NM05-AntiPatterns-Documentation_AP-26.md
nmap/NM05/NM05-AntiPatterns-Documentation_Index.md
nmap/NM05/NM05-AntiPatterns-ErrorHandling_AP-14.md
nmap/NM05/NM05-AntiPatterns-ErrorHandling_AP-15.md
nmap/NM05/NM05-AntiPatterns-ErrorHandling_AP-16.md
nmap/NM05/NM05-AntiPatterns-ErrorHandling_Index.md
nmap/NM05/NM05-AntiPatterns-Implementation_AP-06.md
nmap/NM05/NM05-AntiPatterns-Implementation_AP-07.md
nmap/NM05/NM05-AntiPatterns-Implementation_Index.md
nmap/NM05/NM05-AntiPatterns-Import_AP-01.md
nmap/NM05/NM05-AntiPatterns-Import_AP-02.md
nmap/NM05/NM05-AntiPatterns-Import_AP-03.md
nmap/NM05/NM05-AntiPatterns-Import_AP-04.md
nmap/NM05/NM05-AntiPatterns-Import_AP-05.md
nmap/NM05/NM05-AntiPatterns-Import_Index.md
nmap/NM05/NM05-AntiPatterns-Performance_AP-12.md
nmap/NM05/NM05-AntiPatterns-Performance_Index.md
nmap/NM05/NM05-AntiPatterns-Process_AP-27.md
nmap/NM05/NM05-AntiPatterns-Process_AP-28.md
nmap/NM05/NM05-AntiPatterns-Process_Index.md
nmap/NM05/NM05-AntiPatterns-Quality_AP-20.md
nmap/NM05/NM05-AntiPatterns-Quality_AP-21.md
nmap/NM05/NM05-AntiPatterns-Quality_AP-22.md
nmap/NM05/NM05-AntiPatterns-Quality_Index.md
nmap/NM05/NM05-AntiPatterns-Security_AP-17.md
nmap/NM05/NM05-AntiPatterns-Security_AP-18.md
nmap/NM05/NM05-AntiPatterns-Security_AP-19.md
nmap/NM05/NM05-AntiPatterns-Security_Index.md
nmap/NM05/NM05-AntiPatterns-Testing_AP-23.md
nmap/NM05/NM05-AntiPatterns-Testing_AP-24.md
nmap/NM05/NM05-AntiPatterns-Testing_Index.md
nmap/NM05/NM05-AntiPatterns_Index.md
```

### Neural Maps - Lessons (/nmap/NM06)

```
nmap/NM06/NM06-Bugs-Critical_BUG-01.md
nmap/NM06/NM06-Bugs-Critical_BUG-02.md
nmap/NM06/NM06-Bugs-Critical_BUG-03.md
nmap/NM06/NM06-Bugs-Critical_BUG-04.md
nmap/NM06/NM06-Bugs-Critical_Index.md
nmap/NM06/NM06-Lessons-CoreArchitecture_Index.md
nmap/NM06/NM06-Lessons-CoreArchitecture_LESS-01.md
nmap/NM06/NM06-Lessons-CoreArchitecture_LESS-03.md
nmap/NM06/NM06-Lessons-CoreArchitecture_LESS-04.md
nmap/NM06/NM06-Lessons-CoreArchitecture_LESS-05.md
nmap/NM06/NM06-Lessons-CoreArchitecture_LESS-06.md
nmap/NM06/NM06-Lessons-CoreArchitecture_LESS-07.md
nmap/NM06/NM06-Lessons-CoreArchitecture_LESS-08.md
nmap/NM06/NM06-Lessons-Documentation_Index.md
nmap/NM06/NM06-Lessons-Documentation_LESS-11.md
nmap/NM06/NM06-Lessons-Documentation_LESS-12.md
nmap/NM06/NM06-Lessons-Documentation_LESS-13.md
nmap/NM06/NM06-Lessons-Evolution_Index.md
nmap/NM06/NM06-Lessons-Evolution_LESS-14.md
nmap/NM06/NM06-Lessons-Evolution_LESS-16.md
nmap/NM06/NM06-Lessons-Evolution_LESS-18.md
nmap/NM06/NM06-Lessons-Operations_Index.md
nmap/NM06/NM06-Lessons-Operations_LESS-09.md
nmap/NM06/NM06-Lessons-Operations_LESS-10.md
nmap/NM06/NM06-Lessons-Operations_LESS-15.md
nmap/NM06/NM06-Lessons-Operations_LESS-19.md
nmap/NM06/NM06-Lessons-Performance_Index.md
nmap/NM06/NM06-Lessons-Performance_LESS-02.md
nmap/NM06/NM06-Lessons-Performance_LESS-17.md
nmap/NM06/NM06-Lessons-Performance_LESS-20.md
nmap/NM06/NM06-Lessons-Performance_LESS-21.md
nmap/NM06/NM06-Lessons_Index.md
nmap/NM06/NM06-Wisdom-Synthesized_Index.md
nmap/NM06/NM06-Wisdom-Synthesized_WISD-01.md
nmap/NM06/NM06-Wisdom-Synthesized_WISD-02.md
nmap/NM06/NM06-Wisdom-Synthesized_WISD-03.md
nmap/NM06/NM06-Wisdom-Synthesized_WISD-04.md
nmap/NM06/NM06-Wisdom-Synthesized_WISD-05.md
```

### Neural Maps - Decision Logic (/nmap/NM07)

```
nmap/NM07/NM07-DecisionLogic-Architecture_DT-13.md
nmap/NM07/NM07-DecisionLogic-Architecture_Index.md
nmap/NM07/NM07-DecisionLogic-Deployment_DT-12.md
nmap/NM07/NM07-DecisionLogic-Deployment_Index.md
nmap/NM07/NM07-DecisionLogic-ErrorHandling_DT-05.md
nmap/NM07/NM07-DecisionLogic-ErrorHandling_DT-06.md
nmap/NM07/NM07-DecisionLogic-ErrorHandling_Index.md
nmap/NM07/NM07-DecisionLogic-FeatureAddition_DT-03.md
nmap/NM07/NM07-DecisionLogic-FeatureAddition_DT-04.md
nmap/NM07/NM07-DecisionLogic-FeatureAddition_Index.md
nmap/NM07/NM07-DecisionLogic-Import_DT-01.md
nmap/NM07/NM07-DecisionLogic-Import_DT-02.md
nmap/NM07/NM07-DecisionLogic-Import_Index.md
nmap/NM07/NM07-DecisionLogic-Meta_Index.md
nmap/NM07/NM07-DecisionLogic-Meta_META-01.md
nmap/NM07/NM07-DecisionLogic-Optimization_DT-07.md
nmap/NM07/NM07-DecisionLogic-Optimization_FW-01.md
nmap/NM07/NM07-DecisionLogic-Optimization_FW-02.md
nmap/NM07/NM07-DecisionLogic-Optimization_Index.md
nmap/NM07/NM07-DecisionLogic-Refactoring_DT-10.md
nmap/NM07/NM07-DecisionLogic-Refactoring_DT-11.md
nmap/NM07/NM07-DecisionLogic-Refactoring_Index.md
nmap/NM07/NM07-DecisionLogic-Testing_DT-08.md
nmap/NM07/NM07-DecisionLogic-Testing_DT-09.md
nmap/NM07/NM07-DecisionLogic-Testing_Index.md
nmap/NM07/NM07-DecisionLogic_Index.md
```

### Support Files (/nmap/Support)

```
nmap/Support/ANTI-PATTERNS CHECKLIST-HUB.md
nmap/Support/ANTI-PATTERNS CHECKLIST.md
nmap/Support/AP-Checklist-ByCategory.md
nmap/Support/AP-Checklist-Critical.md
nmap/Support/AP-Checklist-Scenarios.md
nmap/Support/DEBUG-MODE-Context.md
nmap/Support/File Server URLs.md
nmap/Support/MODE-SELECTOR.md
nmap/Support/PROJECT-MODE-Context.md
nmap/Support/REF-ID Complete Directory.md
nmap/Support/REF-ID-DIRECTORY-HUB.md
nmap/Support/REF-ID-Directory-AP-BUG.md
nmap/Support/REF-ID-Directory-ARCH-INT.md
nmap/Support/REF-ID-Directory-DEC.md
nmap/Support/REF-ID-Directory-LESS-WISD.md
nmap/Support/REF-ID-Directory-Others.md
nmap/Support/SERVER-CONFIG.md
nmap/Support/SESSION-START-Quick-Context.md
nmap/Support/SIMA v3 Complete Specification.md
nmap/Support/SIMA-LEARNING-SESSION-START-Quick-Context.md
nmap/Support/URL-GENERATOR-Template.md
nmap/Support/WORKFLOWS-PLAYBOOK-HUB.md
nmap/Support/Workflow-01-AddFeature.md
nmap/Support/Workflow-02-ReportError.md
nmap/Support/Workflow-03-ModifyCode.md
nmap/Support/Workflow-04-WhyQuestions.md
nmap/Support/Workflow-05-CanIQuestions.md
nmap/Support/Workflow-06-Optimize.md
nmap/Support/Workflow-07-ImportIssues.md
nmap/Support/Workflow-08-ColdStart.md
nmap/Support/Workflow-09-DesignQuestions.md
nmap/Support/Workflow-10-ArchitectureOverview.md
nmap/Support/Workflow-11-FetchFiles.md
```

### Documentation Files (/nmap/Docs)

```
nmap/Docs/Performance Metrics Guide.md
nmap/Docs/SIMA v3 Complete Specification.md
nmap/Docs/SIMA v3 Support Tools - Quick Reference Card.md
nmap/Docs/User Guide_ SIMA v3 Support Tools.md
```

### Testing Files (/nmap/Testing)

```
nmap/Testing/Next Session Prompt - Phase 8 Continuation.md
nmap/Testing/Next Session Prompt - Phase 8.md
nmap/Testing/PHASE 7 COMPLETION CERTIFICATE.md
nmap/Testing/Phase 6 Complete - Final Status.md
nmap/Testing/Phase 7 - Final Transition Document.md
nmap/Testing/Phase 7 - Integration Tests.md
nmap/Testing/Phase 8 - Integration Test Results.md
nmap/Testing/Phase 8 - Production Deployment Checklist.md
nmap/Testing/Phase 8 - Production Deployment Status Report.md
nmap/Testing/Phase 8 - Week 1 Metrics Collection Template.md
nmap/Testing/SIMA v3 Migration - Final Status Report.md
nmap/Testing/Session Summary - Phase 8 Kickoff.md
```

---

## 📝 USAGE INSTRUCTIONS

### To Generate File Server URLs.md:

1. **Update BASE_URL** in this file if needed
2. **Run URL Generator** (see URL-GENERATOR-Template.md)
3. **Generator produces** File-Server-URLs.md with all complete URLs
4. **Upload** generated File-Server-URLs.md to Claude sessions

### For Public Release:

1. **Change BASE_URL** to public server
2. **Remove private files** from file paths (if any)
3. **Regenerate** File-Server-URLs.md
4. **Test** URLs are accessible
5. **Distribute** generated file

### To Add New Files:

1. **Add file path** to appropriate section
2. **Regenerate** File-Server-URLs.md
3. **Update count** in generator metadata

---

## 🔒 SECURITY NOTES

**This file should:**
- ✅ Be kept in project repository
- ✅ Be version controlled
- ✅ Have customizable BASE_URL
- ✅ Separate paths from URL

**This file should NOT:**
- ❌ Be uploaded directly to Claude
- ❌ Contain sensitive credentials
- ❌ Include private file paths for public release
- ❌ Have hardcoded private URLs

---

## 📊 FILE STATISTICS

**Total Directories:** 12  
**Total Files:** 230+  
**Source Files:** 93  
**Neural Map Files:** 177  
**Support Files:** 35+  
**Documentation Files:** 4  
**Testing Files:** 12

---

**END OF SERVER CONFIG**

**Use this with URL-GENERATOR-Template.md to create File-Server-URLs.md**  
**Change BASE_URL for different deployments**  
**Add new files to appropriate sections**
