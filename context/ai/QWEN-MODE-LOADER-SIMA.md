# Qwen-MODE-LOADER-SIMA.md (Updated for Shared Instructions)
Version: 1.1.0 → v1.2  
Date: 2025-11-14  
Purpose: Centralized mode loader with mandatory pre-load of AI assistant instructions  
Project: SIMA (Structured Intelligence Memory Architecture)  
Type: Operational Utility  

## 📋 AVAILABLE MODES  
| Mode Name                  | Activation Command                  | Context File Path                                |
|---------------------------|-------------------------------------|--------------------------------------------------|
| Project Mode (SIMA)       | Load Project Mode for SIMA          | `/sima/context/PROJECT-MODE-SIMA.md`             |
| Debug Mode (SIMA)         | Load Debug Mode for SIMA            | `/sima/context/DEBUG-MODE-SIMA.md`               |
| Learning Mode (SIMA)      | Load SIMA Learning Mode             | `/sima/context/SIMA-LEARNING-MODE-Context.md`    |
| Maintenance Mode (SIMA)   | Load SIMA Maintenance Mode          | `/sima/context/SIMA-MAINTENANCE-MODE-Context.md` |
| General Mode (Base)       | Load General Mode                   | `/sima/context/PROJECT-MODE-Context.md`          |
| Custom Instructions       | Load SIMA Custom Instructions       | `/sima/docs/Custom-Instructions-SIMA.md`         |
| LEE Project Mode          | Load LEE Project Mode               | `/sima/context/LEE-PROJECT-MODE.md`              |

---

## ⚙️ REQUIRED PRE-LOAD STEP

Before processing **any activation command**, the following must occur:

1. After detecting `File Server URLs.md`, fetch:  
   → `https://claude.dizzybeaver.com/fileserver.php?v=0068`
2. From the response, locate:  
   → `/sima/context/shared/Custom-Instructions-for-AI-assistant.md`
3. Fetch and apply its content as **session-wide behavioral foundation**.
4. Proceed only after successful application.

Failure to load this file should trigger a non-blocking warning:
> ⚠️ Shared AI instructions not found. Using default reasoning profile.

---

## 💡 Usage
Upload this file + `File Server URLs.md`.  
Say: “Load LEE Project Mode”  

The system will:
1. Pre-load `/sima/context/shared/Custom-Instructions-for-AI-assistant.md`
2. Activate requested mode

🔁 One-step activation enabled.

⚠️ Requirements: Same as v2.2 above.

REF: CUSTOM-INSTR-SIMA-01, PROJECT-MODE-SIMA, SHARED-INSTR-V1  
Keywords: mode, loader, context, SIMA, activation, LEE, shared-instructions
