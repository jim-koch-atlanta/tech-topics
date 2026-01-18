# Calendar App Implementation TODO

## Phase 1: Project Setup
- [x] Create index.html with semantic structure
- [x] Create css/styles.css with base reset
- [x] Create js/ module files (app.js, calendar.js, events.js, modal.js, storage.js, validation.js)
- [x] Link all files in index.html

## Phase 2: Calendar Grid (Static)
- [x] Add month/year header with nav buttons
- [x] Add 7-column day headers (Sun-Sat)
- [x] Add placeholder 6-row grid for day cells
- [x] Implement CSS Grid layout

## Phase 3: Calendar Grid (Dynamic)
- [x] Calculate days for any month (first day, days in month)
- [x] Render current month with prev/next month trailing days
- [x] Implement navigation between months
- [x] Highlight today's date

## Phase 4: localStorage Layer
- [x] Implement getEvents() in storage.js
- [x] Implement saveEvent() in storage.js
- [x] Implement deleteEvent() in storage.js
- [x] Handle empty/corrupted data gracefully
- [x] Generate unique IDs with crypto.randomUUID()

## Phase 5: Modal UI
- [x] Create overlay + centered modal card HTML
- [x] Create form: title, date, time, description fields
- [x] Add Save/Cancel buttons
- [x] Implement close on backdrop click
- [x] Implement close on Escape key

## Phase 6: Add Event
- [x] Capture form submission
- [x] Save to localStorage
- [x] Re-render calendar after save
- [x] Pre-fill date when clicking a day cell

## Phase 7: Display Events
- [x] Show event indicators on calendar days
- [x] Click day to see event list
- [x] Show title and time for each event

## Phase 8: Edit Event
- [x] Click event to open modal in edit mode
- [x] Pre-fill form with event data
- [x] Update existing event on save

## Phase 9: Delete Event
- [x] Add delete button (edit mode only)
- [x] Remove event from localStorage
- [x] Re-render calendar after delete

## Phase 10: Validation
- [x] Title: required, max 100 chars
- [x] Date: required, valid format
- [x] Time: valid format if provided
- [x] Display inline error messages

## Phase 11: Responsive Design
- [x] Mobile-first base styles
- [x] Tablet breakpoint (480px)
- [x] Desktop breakpoint (768px)
- [x] Touch-friendly tap targets (44px min)
- [x] Modal adjustments for small screens

## Phase 12: Polish & Testing
- [x] Add hover/focus states
- [ ] End-to-end testing
- [ ] Bug fixes

---

## Phase 13: Accessibility Audit & Fixes

### Audit Summary

The calendar app has a solid foundation with several accessibility features already in place. However, important improvements are needed for WCAG 2.1 AA compliance.

### Current Accessibility Features (Good)
- [x] `lang="en"` on HTML element
- [x] ARIA labels on navigation buttons
- [x] Modal has `role="dialog"` and `aria-labelledby`
- [x] Form labels properly associated with inputs
- [x] Keyboard support (Tab, Enter, Space, Escape)
- [x] Focus outlines on interactive elements
- [x] Minimum 44px touch targets

### Issues Found

| Priority | Issue | Location |
|----------|-------|----------|
| CRITICAL | Modal missing `aria-modal="true"` | index.html:35 |
| CRITICAL | Day cells missing accessible labels | js/calendar.js:59-61 |
| HIGH | Event items not keyboard accessible | js/calendar.js:137-151 |
| HIGH | Error messages not linked to inputs via `aria-describedby` | index.html:40-56 |
| HIGH | No live region for dynamic announcements | New addition needed |
| MEDIUM | Color contrast fails for muted text (#6b7280 = 4.48:1, needs 4.5:1) | css/styles.css:14 |
| MEDIUM | Color contrast fails for other-month (#9ca3af = 2.68:1) | css/styles.css:19 |
| MEDIUM | Day headers missing semantic role | index.html:18-26 |
| MEDIUM | Modal focus trap missing | js/modal.js |
| LOW | Delete button could have clearer aria-label | js/modal.js:129 |

### Fixes Checklist

- [ ] 1. Add `aria-modal="true"` to modal dialog
- [ ] 2. Add accessible labels to day cells (full date + event count)
- [ ] 3. Make event items keyboard accessible (tabindex + handlers)
- [ ] 4. Add `aria-describedby` to form inputs for error messages
- [ ] 5. Add `aria-live` region for dynamic announcements
- [ ] 6. Fix color contrast for muted text (`#6b7280` → `#4b5563`)
- [ ] 7. Fix color contrast for other-month days (`#9ca3af` → `#6b7280`)
- [ ] 8. Add ARIA grid semantics or `role="columnheader"` to day headers
- [ ] 9. Implement focus trap in modal
- [ ] 10. Add descriptive `aria-label` to delete button

---

## Phase 14: TailwindCSS Migration

### Overview
Migrate from custom CSS to TailwindCSS v3 utility classes. Using the Tailwind Play CDN for simplicity (no build step required).

### Checklist

- [x] 1. Add Tailwind Play CDN script to `index.html`
- [x] 2. Convert base body styles to Tailwind classes
- [x] 3. Convert app container styles
- [x] 4. Convert calendar header (flexbox, spacing, typography)
- [x] 5. Convert navigation buttons
- [x] 6. Convert calendar grid and day headers
- [x] 7. Convert day cells (grid, borders, hover states)
- [x] 8. Convert event items styling
- [x] 9. Convert modal overlay and card
- [x] 10. Convert form groups, inputs, and labels
- [x] 11. Convert buttons (save, cancel, delete)
- [x] 12. Convert error message styles
- [x] 13. Add responsive breakpoints using Tailwind's `sm:` and `md:` prefixes
- [x] 14. Update JS files that dynamically add CSS classes
- [x] 15. Remove or minimize custom CSS file
- [ ] 16. Test all functionality

### Approach
- Use Tailwind CDN for quick setup (production apps should use CLI build)
- Apply utility classes directly to HTML elements
- Use Tailwind's color palette (blue-500, red-500, gray colors)
- Leverage `@apply` directive sparingly if needed via inline styles

### Phase 14 Review

**Files Modified:**
- `index.html` - Added Tailwind CDN, replaced all custom CSS classes with Tailwind utilities
- `js/calendar.js` - Updated dynamically created elements (day-cell, day-number, event-list, event-item, other-month, today) with Tailwind classes
- `js/validation.js` - Updated error styling to use `border-red-500` class
- `js/modal.js` - Changed from `hidden` attribute to `classList.add/remove('hidden')` for Tailwind compatibility
- `css/styles.css` - Cleared and left as placeholder (all styles migrated)

**Key TailwindCSS Features Used:**
- Utility-first classes for layout (`flex`, `grid`, `grid-cols-7`)
- Spacing utilities (`p-4`, `m-4`, `gap-2`, `mt-6`)
- Color utilities (`bg-blue-500`, `text-gray-800`, `border-gray-200`)
- Responsive prefixes (`sm:`, `md:` for breakpoints)
- State variants (`hover:bg-gray-50`, `focus:outline-2`)
- Arbitrary values (`max-h-[90vh]`, `bg-black/50`)
- `@apply` directive for `:nth-child` selectors that can't be inline

---

## Review

### Summary of Changes

**Files Created:**
- `index.html` - Main HTML structure with calendar container, day headers, and modal dialog
- `css/styles.css` - Complete styling with CSS Grid layout, CSS custom properties, and responsive breakpoints
- `js/storage.js` - localStorage CRUD operations with error handling
- `js/validation.js` - Form validation with inline error display
- `js/calendar.js` - Calendar rendering with month navigation and event display
- `js/events.js` - Event business logic layer
- `js/modal.js` - Modal management for add/edit modes
- `js/app.js` - Application entry point

**Key Implementation Details:**

1. **Calendar Grid**: Uses CSS Grid with 7 columns and 42 cells (6 rows) to display a full month view including trailing days from adjacent months.

2. **Data Model**: Events stored in localStorage as JSON array with id, title, date, time, description, createdAt, and updatedAt fields.

3. **Module Pattern**: Each JS file exposes a single object (Storage, Validation, Calendar, Events, Modal) for clear separation of concerns.

4. **Accessibility**:
   - Proper ARIA labels on navigation buttons
   - Keyboard navigation (Tab, Enter, Space, Escape)
   - Focus management in modal
   - Minimum 44px touch targets

5. **Responsive Design**:
   - Mobile-first with breakpoints at 480px and 768px
   - Day cells grow from 80px to 120px height
   - Modal scrolls on small screens

**Testing Needed:**
- Open index.html in browser
- Navigate between months
- Add, edit, and delete events
- Refresh to verify persistence
- Test on mobile viewport
