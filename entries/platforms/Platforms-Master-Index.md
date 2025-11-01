# Platforms-Master-Index.md

**Version:** 1.0.0  
**Date:** 2025-11-01  
**Purpose:** Master index for platform-specific patterns and knowledge  
**Location:** `/sima/entries/platforms/`

---

## OVERVIEW

Platform-specific patterns, best practices, and lessons learned from working with specific cloud platforms, infrastructure providers, and service ecosystems.

**Focus:** Platform-specific implementation details, constraints, and optimization patterns that aren't fully genericizable.

---

## PLATFORMS CATALOG

### Cloud Platforms

#### AWS (Amazon Web Services)
**Location:** `/sima/entries/platforms/aws/`  
**Index:** [AWS-Index.md](aws/AWS-Index.md)  
**Coverage:** Lambda, DynamoDB, API Gateway, CloudWatch, Parameter Store, IAM  
**Entries:** 0 (new)

---

## USAGE GUIDELINES

### When to Create Platform Entry

**Create when:**
- [OK] Platform-specific constraint discovered
- [OK] Service-specific optimization pattern
- [OK] Platform API quirks documented
- [OK] Service integration lessons
- [OK] Platform-specific anti-patterns

**Don't create when:**
- [X] Pattern is fully generic (use core lessons instead)
- [X] Applies to any platform (genericize it)
- [X] Already documented in generic entries

### Genericization Balance

**Keep platform-specific when:**
- Tied to specific service limitations
- Service API behavior unique to platform
- Platform pricing/quota constraints
- Service-specific optimization techniques

**Genericize when:**
- Pattern applies across platforms
- Architectural principle is universal
- Can be abstracted from platform

---

## CROSS-REFERENCES

### Related Categories
- **Core Architecture:** [ARCH-SUGA](../core/ARCH-SUGA_Single-Universal-Gateway-Architecture.md), [ARCH-ZAPH](../core/ARCH-ZAPH_Zero-Abstraction-Path-for-Hot-Operations.md)
- **Lessons:** [Operations-Index](../lessons/operations/Operations-Index.md), [Optimization-Index](../lessons/optimization/Optimization-Index.md)
- **Anti-Patterns:** [Performance-Index](../anti-patterns/performance/Performance-Index.md)

### Interfaces Using Platform Services
- [INT-04 HTTP](../interfaces/INT-04-HTTP-Interface.md) - API calls to platform services
- [INT-06 Config](../interfaces/INT-06-Config-Interface.md) - Platform parameter stores
- [INT-07 Metrics](../interfaces/INT-07-Metrics-Interface.md) - Platform monitoring

---

## STATISTICS

**Total Platforms:** 1  
**Total Entries:** 0  
**Last Updated:** 2025-11-01

---

## MAINTENANCE

### Adding New Platform

1. Create directory: `/sima/entries/platforms/[platform]/`
2. Create index: `[Platform]-Index.md`
3. Add to this master index
4. Follow platform-specific naming (PLAT-[ABBR]-##)

### Entry Naming Convention

Format: `PLAT-[ABBR]-##_[Topic].md`

Examples:
- `PLAT-AWS-01_Lambda-Cold-Start-Optimization.md`
- `PLAT-AWS-02_DynamoDB-Single-Table-Design.md`
- `PLAT-GCP-01_Cloud-Functions-Memory-Management.md`

---

**END OF INDEX**
