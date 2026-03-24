## What's Fixed in v1.6.7

### Bug Fixes
- **Both icons showing simultaneously** — The sun and moon icons were both visible at the same time due to a CSS specificity conflict: `display: flex !important` on the icon container was overriding both the icon-hide CSS rules (lower specificity) and JavaScript's `element.style.display = 'none'` (stylesheet `!important` beats inline style). Fixed by removing `!important` from the container rule and using button-scoped selectors `.syntekpro-toggle-btn .syntekpro-icon-sun/moon` with `!important`.

- **Button jumping to page bottom on toggle** — Clicking the button caused it to move to the bottom of the page instead of staying fixed to the viewport. `position: fixed` was only set in the CSS class, so any theme CSS with higher specificity could override it, making `bottom: 30px` position relative to the document end. Fixed by adding `position: fixed` directly to the button's inline style.
