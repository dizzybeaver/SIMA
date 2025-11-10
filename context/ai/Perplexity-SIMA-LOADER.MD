# SIMA and LEE Mode Context Loader

**Version:** 1.0.1  
**Purpose:** Load SIMA and LEE project modes via file server URLs with project-specific context  
**Usage:** Upload this loader and File Server URLs file, then request:  
`start [mode] for [project]` to activate the relevant context

---

## Supported Projects and Modes

- **Projects:** SIMA, LEE  
- **Modes:** Project Mode, Debug Mode, Learning Mode, Maintenance Mode, Session Start

---

## Mode Context File Mapping by Project

| Project | Mode              | File Path                              |
|---------|-------------------|---------------------------------------|
| SIMA    | Project Mode      | /sima/context/PROJECT-MODE-SIMA.md    |
| SIMA    | Debug Mode        | /sima/context/DEBUG-MODE-SIMA.md      |
| SIMA    | Learning Mode     | /sima/context/SIMA-LEARNING-MODE-Context.md |
| SIMA    | Maintenance Mode  | /sima/context/SIMA-MAINTENANCE-MODE-Context.md |
| SIMA    | Session Start     | /sima/context/SESSION-START-Quick-Context.md  |
| LEE     | Project Mode      | /sima/context/PROJECT-MODE-LEE.md     |
| LEE     | Debug Mode        | /sima/context/DEBUG-MODE-LEE.md       |
| LEE     | Learning Mode     | /sima/context/LEE-LEARNING-MODE-Context.md (if exists) |
| LEE     | Maintenance Mode  | /sima/context/LEE-MAINTENANCE-MODE-Context.md (if exists) |
| LEE     | Session Start     | /sima/context/LEE-SESSION-START-Quick-Context.md (if exists) |

> All URLs require session-specific cache-busting parameters from your File Server URLs file.

---

## Activation Examples

- To start Project Mode for SIMA:  
  `start project mode for sima`  
  Loads `/sima/context/PROJECT-MODE-SIMA.md`.

- To start Debug Mode for LEE:  
  `start debug mode for lee`  
  Loads `/sima/context/DEBUG-MODE-LEE.md`.

---

Uploading the File Server URLs and this loader enables simply requesting any mode for either SIMA or LEE projects, ensuring precise context loading and project separation.
