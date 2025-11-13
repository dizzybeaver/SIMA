# MODE-LOADER-SIMA.md (Qwen Edition – LEE Default)

Version: 1.1.1  
Date: 2025-11-11  
Purpose: Centralized mode loader for SIMA context activation with **LEE Project Mode as default**  
Project: SIMA (Structured Intelligence Memory Architecture)  
Type: Operational Utility  

> 💡 **AUTO-ACTIVATION INSTRUCTION**  
> When this file and `File Server URLs.md` are both present at session start, **automatically load LEE Project Mode** using the path `/sima/context/LEE-PROJECT-MODE.md`.  
> Only skip auto-activation if the user explicitly requests a different mode.

---

## 📋 AVAILABLE MODES

Use this file with `File Server URLs.md` to auto-load fresh mode contexts.

| Mode Name                  | Activation Command                  | Context File Path                                |
|---------------------------|-------------------------------------|--------------------------------------------------|
| Project Mode (SIMA)       | Load Project Mode for SIMA          | `/sima/context/PROJECT-MODE-SIMA.md`             |
| Debug Mode (SIMA)         | Load Debug Mode for SIMA            | `/sima/context/DEBUG-MODE-SIMA.md`               |
| Learning Mode (SIMA)      | Load SIMA Learning Mode             | `/sima/context/SIMA-LEARNING-MODE-Context.md`    |
| Maintenance Mode (SIMA)   | Load SIMA Maintenance Mode          | `/sima/context/SIMA-MAINTENANCE-MODE-Context.md` |
| General Mode (Base)       | Load General Mode                   | `/sima/context/PROJECT-MODE-Context.md`          |
| Custom Instructions       | Load SIMA Custom Instructions       | `/sima/docs/Custom-Instructions-SIMA.md`         |
| **LEE Project Mode**      | **Load LEE Project Mode**           | **`/sima/context/LEE-PROJECT-MODE.md`**          |
