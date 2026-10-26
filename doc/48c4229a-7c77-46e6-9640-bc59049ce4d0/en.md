---
# Root help of a module:  #[Help(locale: 'en', uuid: '48c4229a-7c77-46e6-9640-bc59049ce4d0')]  on your Ui5Module class
# Catalog-row help:         pass this uuid as the entry's helpUuid in the module's catalog attribute
# Context help in a view:   wrap controls in <core:Context uuid="48c4229a-7c77-46e6-9640-bc59049ce4d0"> (ui5-core-lib)
#   — a view binding alone does NOT compile the document: the uuid must be declared in PHP too.
#
id: 48c4229a-7c77-46e6-9640-bc59049ce4d0
title: Vendor
description: TBD
tags: []
---

# Principles of a Good Help Page (for Developers)

## 1. One Page = One Clear Purpose

Each help page must address **exactly one user task or one question** — nothing more.

Examples:

* *“How to Create a Timesheet”*
* *“Understanding Rates”*
* *“Filtering the Overview List”*

If two topics do not naturally belong together → create two pages.

## 2. Strong Metadata

Frontmatter fields `title`, `description`, and `tags` are **part of the UX**:

* shown on the page header,
* indexed in the search engine,
* used by the Shell for navigation panels.

Guidelines:

* `title` → short, descriptive, task-oriented
* `description` → one sentence, immediate value
* `tags` → 3–7 key terms (actions, components, business concepts)

## 3. Deliver Value in the First Three Lines

Users open help pages because they want to **solve something immediately**.

Bad:

> The idea of the Timesheet module is…

Good:

> To create a new Timesheet, open the **Overview**, click **New**, and fill out the required fields.

**Rule:**
The first paragraph must be directly actionable.

## 4. Chunking & Short Sections

Information is easier to absorb when split into small, structured blocks:

* short paragraphs
* small, focused sections
* clear subheadings
* no “wall of text”

**Rule:**
A user should understand the whole page in under 60 seconds.

## 5. Practical Examples Over Theory

Help content is not a concept document — it is a **task assistant**.

So start with:

* a real UI action,
* a concrete example,
* a screenshot,
* a short scenario.

Then follow with explanations.

**Pattern:**

1. **Show** (example)
2. **Explain** (reason)
3. **Apply** (next action)

## 6. Predictable Linking

Internal links (`[#UUID]`) must lead to pages that provide:

* broader context,
* deeper explanation,
* or the “next step”.

A help system is not a blog — do not create excessive cross-links.

**Rule:**
Every internal link must add meaningful value.

## 7. Consistent Visual Rhythm

Even in Markdown, aim for clean, readable structure:

Use:

* bullet points
* numbered steps
* small tables (when they simplify)
* short code samples
* captions for images

Avoid:

* nested lists
* long, unbroken paragraphs
* using inline code for UI labels

Consistency improves comprehension.

## 8. Images Only When They Add Value

Images must **show a real UI action** — not decorative screenshots.

Use images when:

* describing a form,
* pointing to a specific button,
* explaining a state change.

Avoid:

* massive module-overview screenshots,
* images that do not clarify an action.

## 9. Write for Real Users

Help pages target **business users**, not developers.

So:

* write plainly
* avoid technical jargon
* do not assume prior knowledge
* avoid references like “see the developer docs first”

You write for users who want to **complete a task**, not learn architecture.

## 10. Avoid Negative Knowledge

Help pages explain:

* what works,
* how to use it,
* what to do,
* how the UI behaves.

They should not contain:

* internal limitations,
* future features,
* workarounds,
* implementation notes.

This belongs in developer documentation or release notes — not user help.

## 11. One Source of Truth Per Module

A module may have multiple help pages, but it must have **one root page** describing:

* the module’s purpose
* its main tasks
* its navigation structure

If a topic doesn’t belong on the root page → create another help page and link it.

## 12. Minimal Cognitive Load

The Shell will resolve context automatically.

Still, each page must be **self-contained and understandable on its own** without:

* scrolling back and forth,
* searching,
* reading other pages first.

Avoid redundancy — but avoid gaps even more.

## 13. A Help Page is Not Technical Documentation

Most important principle:

> Help pages are for operations, not development.

Therefore, they explain:

* tasks
* workflows
* UI actions
* business meaning

They do **not** explain:

* source code
* architecture
* API details
* system design
* internal constraints

These belong to developer guides, not user help.
