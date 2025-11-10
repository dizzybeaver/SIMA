# MODE-LOADER-SIMA.md
**Version:** 1.0.0  
**Date:** 2025-11-11  
**Purpose:** Centralized mode loader for SIMA context activation  
**Project:** SIMA (Structured Intelligence Memory Architecture)  
**Type:** Operational Utility  

## 📋 AVAILABLE MODES

Use this file with `File Server URLs (1).md` to auto-load fresh mode contexts.

| Mode Name                     | Activation Command                              | Context File Path                              |
|------------------------------|--------------------------------------------------|-----------------------------------------------|
| **Project Mode (SIMA)**      | `Load Project Mode for SIMA`                    | `/sima/context/PROJECT-MODE-SIMA.md`          |
| **Debug Mode (SIMA)**        | `Load Debug Mode for SIMA`                      | `/sima/context/DEBUG-MODE-SIMA.md`            |
| **Learning Mode (SIMA)**     | `Load SIMA Learning Mode`                       | `/sima/context/SIMA-LEARNING-MODE-Context.md` |
| **Maintenance Mode (SIMA)**  | `Load SIMA Maintenance Mode`                    | `/sima/context/SIMA-MAINTENANCE-MODE-Context.md` |
| **General Mode (Base)**      | `Load General Mode`                             | `/sima/context/PROJECT-MODE-Context.md`       |
| **Custom Instructions**      | `Load SIMA Custom Instructions`                 | `/sima/docs/Custom-Instructions-SIMA.md`      |

> 💡 **Usage**:  
> 1. Upload **this file** + **`File Server URLs.md`** at session start.  
> 2. Say: _“Load [Mode Name] using MODE-LOADER-SIMA.md”_  
> 3. I will fetch the correct context file via the cache-busted URL from `fileserver.php`.  

## 🔁 How It Works
- `File Server URLs (1).md` triggers automatic fetch of `fileserver.php?v=0022`  
- That returns fresh, cache-busted URLs for all 412 files (including context modes)  
- This loader maps mode names to their canonical paths  
- I retrieve the exact context file using its dynamic URL  

## ⚠️ Requirements
- Always upload **both** this file **and** `File Server URLs.md`  
- Use **exact activation commands** from the table above  
- Do **not** modify file paths—paths must match SIMA repository structure  

## 🔄 Maintenance
This file is static. All dynamic resolution is handled via `fileserver.php`.  
No updates needed unless new modes are added to SIMA.

---
**REF:** CUSTOM-INSTR-SIMA-01, PROJECT-MODE-SIMA  
**Keywords:** mode, loader, context, SIMA, activation  
