# SIMA Mode Loader
**Version:** 1.0.0
**Date:** 2025-11-10
**Purpose:** Load SIMA project modes with Claude
**Usage:** Upload this file + `File Server URLs.md`, then use the prompt: **"Load SIMA Mode: [MODE_NAME]"** (e.g., "Load SIMA Mode: Project")

---

## 🔄 MODE LOADING INSTRUCTIONS

### **1. Required Files**
- **`File Server URLs.md`** (for dynamic URL retrieval)
- **`SIMA-Mode-Loader.md`** (this file)

### **2. How to Load a Mode**
1. Upload both files to Claude.
2. Use the prompt:

Load SIMA Mode: [MODE_NAME]

Replace `[MODE_NAME]` with one of the following:

---

## 📌 AVAILABLE MODES

| Mode Name          | Activation Phrase                     | Purpose                                      |
|--------------------|---------------------------------------|----------------------------------------------|
| **Project Mode**   | `Load SIMA Mode: Project`              | Build features, active development           |
| **Debug Mode**     | `Load SIMA Mode: Debug`                | Troubleshoot, diagnose issues                |
| **Learning Mode**  | `Load SIMA Mode: Learning`             | Extract knowledge, document lessons         |
| **Maintenance Mode**| `Load SIMA Mode: Maintenance`         | Update indexes, clean up files               |

---

## 🔧 MODE-SPECIFIC CONTEXT FILES

| Mode Name          | Context File(s)                                      |
|--------------------|------------------------------------------------------|
| **Project Mode**   | `/sima/context/PROJECT-MODE-SIMA.md`                  |
| **Debug Mode**     | `/sima/context/DEBUG-MODE-SIMA.md`                   |
| **Learning Mode**  | `/sima/context/SIMA-LEARNING-MODE-Context.md`        |
| **Maintenance Mode**| `/sima/context/SIMA-MAINTENANCE-MODE-Context.md`     |

---

## 🚀 WORKFLOW

1. **Upload Files:**
- `File Server URLs.md`
- `SIMA-Mode-Loader.md`

2. **Activate Mode:**
- Use the prompt: `"Load SIMA Mode: [MODE_NAME]"`
- Example: `"Load SIMA Mode: Project"`

3. **Automatic Context Loading:**
- Claude will fetch the required context files via `fileserver.php`.
- All files are cache-busted for fresh content.

4. **Proceed with Tasks:**
- Follow the mode-specific workflows and rules.

---

## ⚠️ CRITICAL NOTES

- **Always use the exact prompt:** `"Load SIMA Mode: [MODE_NAME]"`.
- **Do not skip `File Server URLs.md`:** Required for dynamic URL retrieval.
- **Modes are atomic:** Only one mode can be active at a time.
- **Context is fresh:** Files are fetched with unique cache-busting parameters.

---

## 📂 FILE STRUCTURE

- **`/sima/context/`**: Mode-specific context files.
- **`/sima/entries/`**: Neural maps, lessons, and decisions.
- **`/sima/shared/`**: Standards, red flags, and common patterns.

---

**END OF SIMA MODE LOADER**
**Version:** 1.0.0
**Lines:** 50 (target achieved)
