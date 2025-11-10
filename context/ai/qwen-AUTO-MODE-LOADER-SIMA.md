# AUTO-MODE-LOADER-SIMA.md
**Version:** 1.0.0  
**Date:** 2025-11-11  
**Purpose:** Auto-detect and load SIMA context mode from user’s first message  
**Project:** SIMA (Structured Intelligence Memory Architecture)  
**Type:** Operational Utility  

## 🧠 AUTO-MODE DETECTION LOGIC

When this file and `File Server URLs (1).md` are uploaded, I will **automatically detect** your desired mode from your **first message** using keyword matching. No explicit command needed.

### 🔍 Detection Rules (Case-Insensitive)

| User Message Contains        | → Loads Mode                  | Context File Path                              |
|-----------------------------|-------------------------------|-----------------------------------------------|
| `project mode`              | Project Mode (SIMA)           | `/sima/context/PROJECT-MODE-SIMA.md`          |
| `debug mode`                | Debug Mode (SIMA)             | `/sima/context/DEBUG-MODE-SIMA.md`            |
| `learning mode`             | Learning Mode (SIMA)          | `/sima/context/SIMA-LEARNING-MODE-Context.md` |
| `maintenance mode`          | Maintenance Mode (SIMA)       | `/sima/context/SIMA-MAINTENANCE-MODE-Context.md` |
| `custom instructions`       | SIMA Custom Instructions      | `/sima/docs/Custom-Instructions-SIMA.md`      |
| `general mode` OR no match  | General Mode (Base)           | `/sima/context/PROJECT-MODE-Context.md`       |

> ✅ **Examples of auto-triggering messages:**  
> - “Start project mode for SIMA” → loads **Project Mode**  
> - “Debug this SIMA entry” → loads **Debug Mode**  
> - “I need to learn about neural maps” → loads **Learning Mode**  
> - “Update the index” → loads **Maintenance Mode**  
> - “Show me custom instructions” → loads **Custom Instructions**  
> - “Hello” → defaults to **General Mode**

## 🔄 How It Works
1. You upload **this file** + **`File Server URLs (1).md`**
2. You send **any message** (no special phrasing required)
3. I scan your message for mode keywords
4. I fetch the correct context file using the cache-busted URL from `fileserver.php?v=0022`
5. Mode is activated silently — you’re ready to work

## ⚠️ Requirements
- **Both files must be uploaded** before your first message
- Detection runs **once**, on the **first user message only**
- Fallback: **General Mode** if no keywords match

## 🔗 Dependencies
- Requires dynamic URL inventory from:  
  `https://claude.dizzybeaver.com/fileserver.php?v=0022`
- Paths must match SIMA repository structure exactly

## 📌 Notes
- This loader **replaces** manual mode commands
- Designed for seamless session startup
- Fully compatible with SIMA’s cache-busting architecture

---
**REF:** CUSTOM-INSTR-SIMA-01, PROJECT-MODE-SIMA  
**Keywords:** auto-mode, loader, SIMA, context, detection  
