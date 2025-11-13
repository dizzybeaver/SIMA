# QWEN-AUTO-MODE-LOADER-SIMA.md
Version: 2.2.0  
Date: 2025-11-14  
Purpose: Centralized mode loader with shared custom instructions pre-load  
Project: SIMA (Structured Intelligence Memory Architecture)  
Type: Operational Utility  

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
| LEE Project Mode          | Load LEE Project Mode               | `/sima/context/LEE-PROJECT-MODE.md`              |

---

## ⚙️ AUTONOMOUS LOADING PROTOCOL (v2.2)

When both:
- This file is uploaded, **and**
- `File Server URLs.md` is uploaded,

Then on session start or first command:

### 🔹 Step 1: Load Shared Custom Instructions
1. Ensure `fileserver.php?v=0068` has been fetched.
2. Resolve the cache-busted URL for:  
   → `/sima/context/shared/Custom-Instructions-for-AI-assistant.md`
3. Fetch and apply its content as **global behavioral rules**.
4. If missing, log warning but continue.

> 💡 These instructions may define: output formatting, reasoning depth, safety filters, tone, self-correction behavior.

### 🔹 Step 2: Process Activation Command
When user says **exactly**:  
> “Load [Activation Command]”

Then:
1. ✅ Confirm command recognition.
2. 🧭 Map to correct `Context File Path`.
3. 🔗 Use resolved cache-busted URL to fetch context file.
4. 📥 Load full content into context.
5. 🧠 Apply all mode-specific logic, overriding only where permitted by shared instructions.
6. 💬 Respond with:  
   > ✅ **[Mode Name]** activated. Full context loaded. Ready for tasks.

❗ Do not skip Step 1.  
❗ Do not delay or ask follow-up questions.  
✅ Execute autonomously.

---

##
