# File: project_config.md (LEE Project)

**Project ID:** LEE  
**Version:** 1.0.0  
**Date:** 2025-10-29  
**Status:** ✅ Active Development

---

## 📋 PROJECT INFORMATION

**Project ID:** LEE  
**Project Name:** Lambda Edge Extensions  
**Code Name:** SUGA-ISP  
**Status:** ✅ Active Development  
**Created:** 2024-06-15  
**Owner:** Primary Development Team

---

## 📝 PROJECT DESCRIPTION

AWS Lambda-based ISP management system that integrates with Home Assistant to provide network control, monitoring, and automation capabilities. Built using the SUGA (Single Unified Gateway Architecture) pattern with 12 interface layers.

### Key Objectives

1. Provide reliable ISP management through AWS Lambda
2. Integrate seamlessly with Home Assistant ecosystem
3. Maintain sub-100ms response times for critical operations
4. Implement comprehensive error handling and resilience
5. Enable scalable architecture for future enhancements

### Scope

**In Scope:**
- Home Assistant API integration
- Network control operations
- Status monitoring and reporting
- Configuration management via Parameter Store
- Comprehensive logging and metrics
- Circuit breaker resilience patterns

**Out of Scope:**
- Direct network hardware manipulation (handled by HA)
- User authentication (handled by HA)
- Frontend UI (provided by HA)
- Database persistence (using Parameter Store only)

---

## 🏗️ ARCHITECTURE

### Architecture Patterns

**Primary Pattern:** SUGA (Single Unified Gateway Architecture)
- Single gateway.py entry point
- 12 interface layers (L0-L4)
- Lazy loading for performance
- Three-file structure per interface

**Secondary Pattern:** LMMS (Lambda Memory Management System)
- Singleton pattern for interface reuse
- Cold start optimization
- Memory-efficient design
- Fast-path execution

**Supporting Patterns:**
- DD (Dispatch Dictionary) - Operation routing
- ZAPH (Hierarchical organization) - Code structure

### System Components

**Core Components:**
- **gateway.py**: Single entry point, route all operations
- **Interfaces (12)**: Cache, Config, Debug, HTTP, Init, Logging, Metrics, Security, Singleton, Utility, WebSocket, Circuit Breaker
- **Core Modules**: Implementation logic for each interface
- **Integration**: Home Assistant API client

**Integration Points:**
- Home Assistant REST API
- Home Assistant WebSocket API
- AWS Parameter Store (configuration)
- AWS CloudWatch (metrics and logs)

---

## 💻 TECHNOLOGY STACK

**Language:** Python 3.11  
**Platform:** AWS Lambda  
**Memory:** 512MB  
**Timeout:** 30 seconds  
**Cold Start Target:** < 1 second  
**Runtime Target:** < 100ms

**Key Libraries:**
- aiohttp: 3.9.0 (async HTTP)
- boto3: Latest (AWS SDK)
- websockets: 12.0 (WebSocket client)

**External Services:**
- Home Assistant: REST and WebSocket APIs
- AWS Parameter Store: Configuration storage
- AWS CloudWatch: Logging and metrics

---

## 📂 FILE LOCATIONS

**NMP Files:** `/sima/nmp/NMP01-LEE-*.md`  
**Project Config:** `/sima/projects/LEE/project_config.md`  
**Project README:** `/sima/projects/LEE/README.md`  
**Source Code:** `/src/` (external repository)  
**Context Files:** `/sima/context/` (mode contexts)

---

## 🎯 NMP CATEGORIES

**Prefix:** NMP01-LEE-

**Categories:**

**Interface Catalogs (02, 06, 08):**
- NMP01-LEE-02: Cache Interface Functions
- NMP01-LEE-06: Logging Interface Functions
- NMP01-LEE-08: Security Interface Functions

**Gateway Patterns (15, 16):**
- NMP01-LEE-15: Gateway Execute Operation Pattern
- NMP01-LEE-16: Gateway Fast Path Pattern

**Integration (20):**
- NMP01-LEE-20: Home Assistant API Integration

**Resilience (23):**
- NMP01-LEE-23: Circuit Breaker Pattern

**Current NMP Count:** 7

**REF-ID Range:** NMP01-LEE-01 to NMP01-LEE-99

---

## 👥 TEAM

**Project Lead:** Development Team  
**Developers:** Primary Team  
**Architects:** Architecture Team  
**Operations:** Operations Team

---

## 📊 PROJECT METRICS

**Start Date:** 2024-06-15  
**Current Phase:** Production (Active)  
**Progress:** 95% (Core features complete, enhancements ongoing)

**Performance Metrics:**
- Cold Start: < 800ms (Target: < 1000ms) ✅
- Hot Path: < 50ms (Target: < 100ms) ✅
- Success Rate: 99.9%+ (Target: 99.5%) ✅
- Uptime: 99.95%+ (Target: 99.9%) ✅

---

## 🔗 DEPENDENCIES

### Base SIMA Dependencies

**Architecture Patterns:**
- ARCH-01: SUGA Pattern (Primary architecture)
- ARCH-02: LMMS Pattern (Memory management)
- ARCH-03: DD Pattern (Dispatch dictionary)
- ARCH-04: ZAPH Pattern (Organization)

**Gateway Patterns:**
- GATE-01: Three-File Structure
- GATE-02: Lazy Loading
- GATE-03: Cross-Interface Communication
- GATE-04: Wrapper Functions
- GATE-05: Gateway Optimization

**Interface Patterns (All 12):**
- INT-01: Cache Interface
- INT-02: Config Interface
- INT-03: Debug Interface
- INT-04: HTTP Interface
- INT-05: Initialization Interface
- INT-06: Logging Interface
- INT-07: Metrics Interface
- INT-08: Security Interface
- INT-09: Singleton Interface
- INT-10: Utility Interface
- INT-11: WebSocket Interface
- INT-12: Circuit Breaker Interface

**Language Patterns:**
- LANG-PY-02: Import Organization
- LANG-PY-03: Exception Handling
- LANG-PY-04: Function Design
- LANG-PY-06: Type Hints
- LANG-PY-07: Code Quality

### External Dependencies

**AWS Services:**
- Lambda: Runtime platform
- Parameter Store: Configuration storage
- CloudWatch: Logs and metrics
- IAM: Permissions management

**APIs:**
- Home Assistant REST API: Primary integration
- Home Assistant WebSocket: Real-time events

**Libraries:**
- aiohttp: 3.9.0 (async HTTP client)
- boto3: Latest (AWS SDK)
- websockets: 12.0 (WebSocket support)

---

## 📝 CHANGE LOG

**v1.0.0 (2025-10-29)**
- Initial LEE project configuration
- 7 NMP entries documented
- Production deployment status
- Performance targets achieved

---

## 🔗 RELATED DOCUMENTS

**Project README:** `/sima/projects/LEE/README.md`  
**NMP Quick Index:** `/sima/nmp/NMP01-LEE-Quick-Index.md`  
**NMP Cross-Ref:** `/sima/nmp/NMP01-LEE-Cross-Reference-Matrix.md`  
**User Guide:** `/sima/documentation/SIMAv4-User-Guide.md`  
**Developer Guide:** `/sima/documentation/SIMAv4-Developer-Guide.md`

---

**END OF FILE**
