# LEE-Index-Main.md

**Version:** 1.0.0  
**Date:** 2025-11-08  
**Purpose:** Main index for LEE (Lambda Execution Engine) project documentation  
**Category:** Project - LEE Index

---

## Overview

LEE (Lambda Execution Engine) is a serverless home automation gateway running on AWS Lambda, integrating with Home Assistant via WebSocket API.

**Project Status:** Production  
**AWS Region:** us-east-1  
**Memory:** 128MB  
**Timeout:** 4 seconds  
**Invocations:** ~50,000/month

---

## Quick Links

### Architecture
- [LEE Architecture Overview](LEE-Architecture-Overview.md)
- [LEE Integration Patterns](LEE-Architecture-Integration-Patterns.md)

### Configuration
- [Knowledge Configuration](config/knowledge-config.yaml)
- [Project README](README.md)

### Key Decisions
- [LEE-DEC-01: Home Assistant Choice](decisions/LEE-DEC-01-Home-Assistant-Choice.md)
- [LEE-DEC-02: WebSocket Protocol](decisions/LEE-DEC-02-WebSocket-Protocol.md)
- [LEE-DEC-03: Token Management](decisions/LEE-DEC-03-Token-Management.md)

### Key Lessons
- [LEE-LESS-01: WebSocket Reliability](lessons/LEE-LESS-01-WebSocket-Reliability.md)
- [LEE-LESS-02: WebSocket Connection Patterns](lessons/LEE-LESS-02-WebSocket-Connection-Patterns.md)

---

## Architecture Patterns Used

### Python Architectures

| Pattern | Purpose | Files |
|---------|---------|-------|
| **SUGA** | Gateway architecture | `/sima/languages/python/architectures/suga/` |
| **LMMS** | Lazy module loading | `/sima/languages/python/architectures/lmms/` |
| **ZAPH** | Hot path optimization | `/sima/languages/python/architectures/zaph/` |
| **DD-1** | Dictionary dispatch | `/sima/languages/python/architectures/dd-1/` |
| **DD-2** | Dependency disciplines | `/sima/languages/python/architectures/dd-2/` |
| **CR-1** | Cache registry | `/sima/languages/python/architectures/cr-1/` |

### Platform Constraints

**AWS Lambda:**
- Single-threaded execution
- Stateless design
- Memory limit: 128MB
- Timeout: 4 seconds
- Cold start: <3 seconds target

**Documentation:** `/sima/platforms/aws/lambda/`

---

## Project Structure

```
/sima/projects/LEE/
├── README.md                           # Project overview
├── LEE-Architecture-Overview.md        # System architecture
├── LEE-Architecture-Integration-Patterns.md  # Pattern integration
├── config/
│   └── knowledge-config.yaml          # Knowledge configuration
├── decisions/
│   ├── LEE-DEC-01-Home-Assistant-Choice.md
│   ├── LEE-DEC-02-WebSocket-Protocol.md
│   └── LEE-DEC-03-Token-Management.md
├── lessons/
│   ├── LEE-LESS-01-WebSocket-Reliability.md
│   └── LEE-LESS-02-WebSocket-Connection-Patterns.md
└── indexes/
    └── LEE-Index-Main.md              # This file
```

---

## Decisions (DEC)

### Architecture Decisions

**LEE-DEC-01: Home Assistant Choice**
- Decision to use Home Assistant as smart home platform
- Rationale: Local control, privacy, flexibility
- Status: Accepted

**LEE-DEC-02: WebSocket Protocol**
- Decision to use WebSocket API for HA communication
- Rationale: Real-time, bidirectional, lower latency
- Status: Accepted

**LEE-DEC-03: Token Management**
- Decision to store token in SSM Parameter Store
- Rationale: Security, access control, cost-effective
- Status: Accepted

### Future Decisions

**Planned:**
- LEE-DEC-04: Error Handling Strategy
- LEE-DEC-05: Security Strategy
- LEE-DEC-06: Monitoring Strategy
- LEE-DEC-07: Scaling Strategy

---

## Lessons Learned (LESS)

### WebSocket & Communication

**LEE-LESS-01: WebSocket Reliability**
- Lesson: WebSocket connections need robust error handling
- Key insight: Per-invocation connections reliable in Lambda
- Impact: 99.2% success rate

**LEE-LESS-02: WebSocket Connection Patterns**
- Lesson: Connection lifecycle needs careful management
- Key insight: Retry, timeout, fallback patterns essential
- Impact: 15x fewer failures, 21% faster

### Future Lessons

**Planned topics:**
- Token refresh strategies
- Device discovery optimization
- Cold start optimization
- Cache warming strategies
- Error recovery patterns

---

## Performance Metrics

### Latency

```
Cold Start:    2.1 seconds (target: <3s)
Warm Start:    0.8 seconds
P50 Duration:  750ms
P95 Duration:  1400ms
P99 Duration:  2200ms
```

### Reliability

```
Success Rate:       99.2%
Timeout Rate:       0.5%
Auth Failure Rate:  0.2%
Connection Errors:  0.1%
```

### Cost

```
Monthly Invocations: 50,000
Memory Configuration: 128MB
Average Duration: 750ms
Monthly Cost: $0.155
```

---

## Technology Stack

### AWS Services

- **Lambda:** Serverless compute
- **API Gateway:** HTTP/WebSocket endpoints
- **SSM Parameter Store:** Token storage
- **CloudWatch:** Logging and monitoring
- **IAM:** Access control

### Home Assistant

- **Version:** 2024.6+
- **Protocol:** WebSocket API (primary), REST API (fallback)
- **Authentication:** Long-lived access token
- **Network:** Local network access

### Python

- **Version:** 3.11
- **Key Libraries:**
  - websocket-client (WebSocket)
  - boto3 (AWS SDK)
  - Built-in only (no external dependencies)

---

## Key Files Reference

### Source Code (Production)

Located in `/src/` directory:

**Gateway & Routing:**
- `gateway.py` - Main gateway exports
- `gateway_core.py` - Gateway logic
- `gateway_wrappers.py` - Wrapper functions
- `fast_path.py` - Hot path routing

**Home Assistant Integration:**
- `ha_interface_alexa.py` - Alexa integration
- `ha_interface_devices.py` - Device management
- `ha_interface_assist.py` - Assistant integration
- `ha_websocket.py` - WebSocket handling
- `ha_devices_core.py` - Device logic

**Core Services:**
- `interface_websocket.py` - WebSocket interface
- `interface_cache.py` - Caching interface
- `interface_config.py` - Configuration interface
- `cache_core.py` - Cache implementation
- `config_core.py` - Configuration management

**Lambda Entry:**
- `lambda_function.py` - Main handler
- `lambda_preload.py` - Preload optimizations

---

## Development Workflow

### Adding New Feature

1. Review architecture patterns (SUGA, LMMS, ZAPH)
2. Implement in appropriate layer (Gateway → Interface → Core)
3. Add to CR-1 registry if needed
4. Test locally
5. Deploy via GitHub Actions
6. Monitor CloudWatch metrics

### Debugging Issues

1. Check CloudWatch logs
2. Review known lessons (LEE-LESS-*)
3. Check known patterns in AWS Lambda docs
4. Use systematic investigation (LESS-09)
5. Document new lessons learned

### Updating Documentation

1. Use Learning Mode for extraction
2. Create DEC-* for decisions
3. Create LESS-* for lessons
4. Update indexes
5. Cross-reference related docs

---

## Monitoring & Alerting

### CloudWatch Dashboards

**LEE Overview:**
- Invocation count
- Duration (P50, P95, P99)
- Error rate
- Cold start frequency

**LEE Performance:**
- Latency distribution
- Cache hit/miss ratio
- WebSocket connection time
- HA API latency

### Alarms

**Critical:**
- Error rate >2%
- WebSocket failures >5%
- Authentication failures >1%

**Warning:**
- P95 latency >2000ms
- Cold start ratio >10%
- Memory usage >90%

---

## Related Documentation

### Architecture Patterns

**Python:**
- [SUGA](/sima/languages/python/architectures/suga/)
- [LMMS](/sima/languages/python/architectures/lmms/)
- [ZAPH](/sima/languages/python/architectures/zaph/)
- [DD-1](/sima/languages/python/architectures/dd-1/)
- [DD-2](/sima/languages/python/architectures/dd-2/)
- [CR-1](/sima/languages/python/architectures/cr-1/)

### Platform

**AWS Lambda:**
- [Core Concepts](/sima/platforms/aws/lambda/core/)
- [Decisions](/sima/platforms/aws/lambda/decisions/)
- [Lessons](/sima/platforms/aws/lambda/lessons/)
- [Anti-Patterns](/sima/platforms/aws/lambda/anti-patterns/)

### Generic Knowledge

**Universal Patterns:**
- [Core Architecture](/sima/entries/core/)
- [Gateway Patterns](/sima/entries/gateways/)
- [Decisions](/sima/entries/decisions/)
- [Anti-Patterns](/sima/entries/anti-patterns/)
- [Lessons](/sima/entries/lessons/)

---

## Version History

**v1.0.0 (2025-11-08):**
- Initial index creation
- Architecture overview
- Decision tracking
- Lesson tracking
- Performance metrics
- Related documentation

---

## Contact & Support

**Project Maintainer:** Joe  
**Repository:** /src directory (source code)  
**Documentation:** /sima/projects/LEE/ (this directory)  
**Monitoring:** CloudWatch (AWS Console)

---

**END OF FILE**

**Index Version:** 1.0.0  
**Last Updated:** 2025-11-08  
**Total Sections:** 12  
**Total Documents Indexed:** 10+
