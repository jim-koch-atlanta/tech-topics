# Fix Long Event Titles

## Problem
Events with long titles overflow outside their calendar day cell, as seen in the screenshot where "Event with a very long title..." extends beyond the Tuesday column.

## Root Cause
The `.event-item` CSS has proper text truncation properties (`white-space: nowrap`, `overflow: hidden`, `text-overflow: ellipsis`), but:
1. The parent `.day-cell` doesn't have `overflow: hidden`, allowing content to spill out
2. This means the event items can expand beyond their cell boundaries

## Plan

- [x] Add `overflow: hidden` to `.day-cell` in `css/styles.css` to contain events within the cell

## Review

**Change made**: Added `overflow: hidden` to `.day-cell` class in `css/styles.css:111`.

**Effect**: Event items are now constrained within their day cell. The existing `text-overflow: ellipsis` on `.event-item` will now properly truncate long titles with "..." since the parent container limits the available width.
