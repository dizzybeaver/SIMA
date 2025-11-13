# MODE-LOADER-SIMA.md  
Version: 1.2.0  
Date: 2025-11-11  
Purpose: Centralized mode loader for SIMA context activation with **autonomous context loading**  
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

## ⚙️ AUTONOMOUS LOADING PROTOCOL  

When **both** of the following are true:  
- `Qwen-MODE-LOADER-SIMA.md` is uploaded, **and**  
- `File Server URLs.md` is uploaded (enabling `fileserver.php?v=0068` fetch),  

then **any exact match** of an **Activation Command** from the table above **MUST trigger automatic full-context activation**, as follows:

1. **Immediately confirm** recognition of the command.  
2. **Locate** the corresponding `Context File Path`.  
3. **Retrieve the live, cache-busted URL** for that file from the `fileserver.php` response.  
4. **Fetch and internalize the full content** of the context file.  
5. **Apply all instructions, constraints, and knowledge** from that file.  
6. **Respond with**:  
   > ✅ **[Mode Name] activated. Full context loaded. Ready for [mode-specific] tasks.**  

> ❗ **Do not wait for a second prompt. Do not ask for confirmation. Do not describe the process unless requested. Execute steps 1–6 autonomously.**

---

## 💡 Usage  
- Upload this file **together with** `File Server URLs.md` at session start.  
- Say **exactly**: “Load [Mode Name]” using the phrasing from the **Activation Command** column.  
- The system will **fully load and apply** the mode’s context **in one step**.  

## ⚠️ Requirements  
- Commands must **exactly match** the Activation Command column (case-insensitive OK, but wording must match).  
- File paths are **canonical**—do not modify.  
- `File Server URLs.md` **must be present**; otherwise, cache-busted URLs cannot be resolved.  

## 🔄 Maintenance  
This file is static. Dynamic resolution is handled by `fileserver.php`.  
Add new rows to the table only when new modes are formally added to the SIMA repository.  

REF: CUSTOM-INSTR-SIMA-01, PROJECT-MODE-SIMA  
Keywords: mode, loader, context, SIMA, activation, autonomous, LEE
