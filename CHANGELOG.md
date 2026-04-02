# Changelog

All notable changes to Syntekpro-Toggle will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.6.9] - 2026-04-03

### Added
- Added new SyntekPro PNG assets for Toggle, Themes, and Plugins Support branding in `assets/img/`.

### Changed
- Refreshed release metadata and rebuilt the distributable package for v1.6.9.

## [1.6.8] - 2026-03-30

### Added
- Added an Admin Panel option to show or hide the floating admin dark-mode toggle button.

### Fixed
- Fixed the frontend and admin toggle buttons so the sun icon displays correctly when dark mode is active.
- Fixed toggle placement conflicts by resetting all four positional offsets before applying the configured corner.
- Fixed auto/system dark mode initialization so late-rendered toggle buttons still appear and sync correctly.
- Fixed image and media filtering so regular images are no longer caught by overly broad slide selectors and rendered with an x-ray-like look.

---

## [1.6.7] - 2026-03-25

### 🐛 Fixed
- **Toggle button showing both sun and moon icons simultaneously** — CSS `display: flex !important` on the icon container was overriding icon-hide rules (lower specificity) and defeating JS `element.style.display = 'none'` (stylesheet `!important` beats inline style). Fixed by removing `!important` from the container, and scoping icon selectors to `.syntekpro-toggle-btn .syntekpro-icon-sun/moon` with `!important` for reliable visibility control.
- **Toggle button jumping to page bottom on click** — `position: fixed` was only in the CSS class; themes with higher-specificity rules overriding `position` would break fixed placement, making `bottom: 30px` relative to the document end instead of the viewport. Fixed by adding `position: fixed` directly to the button's inline style.

---

## [1.6.6] - 2026-03-25

### ✨ Added
- **SyntekPro Chat plugin card** — Added SyntekPro Chat to the "Other Plugins by SyntekPro" section in the About page.
- **Real PNG plugin icons** — Replaced emoji placeholders in the Other Plugins grid with actual PNG icon images from `assets/img/`.
- **SyntekPro Plugins Logo in heading** — "Other Plugins" section heading now displays the SyntekPro Plugins Logo image instead of a dashicon.
- **License page sidebar-nav layout** — Fully redesigned Toggle+ / License page to match the sidebar-nav + collapsible section-panel pattern used across all other admin pages.

### 🔧 Changed
- **All plugin card URLs corrected** — Docs link updated to `plugins.syntekpro.com/toggle/docs`; support link to `plugins.syntekpro.com/support`; Forms/Animations/License Server plugin URLs normalised.
- **Version badge** now displayed in red (`#dc3232`) for higher visibility.
- **"Get Toggle+" button** repositioned further from the logo using `position:absolute; right:30px` on the header wrapper.
- **Accordion arrows standardised** — All expand/collapse arrows across all admin pages now use the same `▼`/`▶` `::after` pseudo-element pattern.

### 🐛 Fixed
- **Double "Analytics Settings" heading** — Cleared the section title in `add_settings_section()` so `do_settings_sections()` no longer outputs a duplicate `<h2>`.
- **Double expand/collapse arrows** — Scoped all arrow CSS selectors to `> h2` (direct child combinator) to prevent nested `<h2>` elements from incorrectly receiving arrow styles.

---

## [1.6.5] - 2026-03-25

### ✨ Added
- **Three dedicated settings pages** — Frontend, Admin Panel, and Media Settings replace the previous single combined page for a cleaner, more focused workflow.
- **Free / Toggle+ labelling** — First three settings on Frontend and Admin Panel pages are free; remaining advanced options display a purple "Toggle+" badge.
- **Advanced Media Settings** — New fields: custom image selector, image exclude class, background-image filter, SVG invert filter, custom video selector, iframe brightness filter, custom slide selector, slider colour overlay (colour + opacity).
- **Collapsible About page** — Rich accordion layout with sections for Welcome/Features, User Guide (8-step walkthrough), Changelog, and Other Plugins.
- **Other Plugins section in About** — SyntekPro Forms, Animations, and License Server cards moved from their own menu page into the About accordion.
- **Detailed User Guide** — Step-by-step configuration guide built into the About page covering all major features.

### 🔧 Changed
- **Menu icon** resized from 25×25 px to 18×18 px for a cleaner sidebar appearance.
- **Sidebar label** updated from "Toggle" to "SyntekPro Toggle".
- **"Other Plugins" menu item removed** — content now lives inside About (redirect stub kept for back-compat).
- **`admin_enqueue_scripts`** now uses page-slug check instead of brittle hook-name comparison.
- **`syntekpro_toggle_page_header()`** logo logic simplified (plugins-page logo branch removed).

### 🐛 Fixed
- PHP 7.2 compat: replaced `??` null-coalescing on `sanitize_hex_color()` result with ternary.

## [1.6.3] - 2026-03-19

### ✨ Added
- **GitHub Update Notifier** - New `includes/class-github-updater.php` hooks into WordPress’s transient update system so every site running the plugin receives the standard “Update available” notice whenever a new GitHub Release is published. Remote version data is cached for 12 hours. Release notes are shown in the “View version details” dialog.
- **Admin Bar Toggle – Right Side** - The dark-mode toggle icon in the WordPress top admin bar is now placed in the right-side `top-secondary` group (next to user menu and other quick-access items).

### 🔧 Changed
- **Plugin Display Name** - Renamed from `Syntekpro-Toggle` to `SyntekPro Toggle` (hyphen removed) so the Plugins list and dashboard show a clean, readable name.

### 🐛 Fixed
- **"Show toggle button on frontend" not saving when unchecked** - HTML checkboxes are absent from POST data when unchecked, so the previous `array_key_exists` check could never detect an unchecked state. Fixed by adding a hidden sentinel field (`_enable_toggle_sentinel`) so the sanitiser can reliably set the value to `'0'` when the box is unchecked.
- **Toggle button invisible on some sites** - Aggressive theme or page-builder CSS could override `position`, `z-index`, `display`, `visibility`, or `pointer-events` on the floating button and its icon spans. Hardened with `!important` on layout-critical properties in `public/css/style.css`.


## [1.6.2] - 2026-03-08

### ✨ Added
- **Custom Frontend Toggle Icon Option** - Added a settings field with media uploader support to let admins upload a custom frontend toggle icon.
- **Custom Admin Toggle Icon Option** - Added a settings field with media uploader support to let admins upload a custom admin floating toggle icon.

### 🔧 Changed
- **Default Icon Behavior Restored** - Frontend and backend toggles now use default sun/moon icons by default and only switch when a custom icon URL is provided.
- **Admin Menu Icon Sizing** - Increased WordPress admin menu icon size to `25x25px` for better visibility.
## [1.6.1] - 2026-02-17

### ✨ Added
- **Localization Readiness** - Added/expanded translation wrappers across admin pages and generated an updated POT file in `languages/syntekpro-toggle.pot`.
- **Uninstall Cleanup** - Added `uninstall.php` to remove plugin options during uninstall.

### 🔧 Changed
- **Plugin Packaging Metadata** - Added WordPress.org-style `readme.txt` and aligned release metadata.
- **Version Consistency** - Updated plugin version metadata/constants to `1.6.1`.

### 🛡️ Security
- **Request Handling Hardening** - Improved nonce validation and sanitization flow for admin/AJAX request inputs.

## [1.6.0] - 2026-02-16

### ✨ Added
- **Analytics Sidebar Navigation** - Added side menu tab navigation on Analytics page with icons and section-based layout.
- **Toggle+ License Management** - Added license key input, save/remove actions, and status display on Toggle+ page.

### 🔧 Changed
- **Unified Admin Navigation Logic** - Centralized tab switching logic for Mode Settings, Options, and Analytics pages in `admin/js/admin.js`.
- **Admin Asset Cache Busting** - Admin CSS/JS now use file modification time for versioning to ensure latest assets are loaded.
- **Premium Unlock Behavior** - Saved Toggle+ license key now unlocks premium-gated themes and presets.

### 🐛 Fixed
- **Tab Switching Regression** - Fixed issue where tab navigation remained stuck on first tab across Mode Settings, Options, and Analytics.

## [1.3.0] - 2026-02-10

### 🏗️ Architecture Improvements

#### ✨ Changed
- **Professional Folder Structure** - Reorganized plugin with industry-standard architecture:
  - `/admin/` - All admin-related files
    - `admin.php` - Admin functionality
    - `/css/` - Admin stylesheets
    - `/js/` - Admin JavaScript
  - `/public/` - All frontend files
    - `/css/` - Public stylesheets
    - `/js/` - Public JavaScript
  - `/assets/` - Media assets (images, icons)
  - Root level documentation files (README, CHANGELOG, LICENSE)
- **Improved Code Organization** - Better separation of concerns between admin and public functionality
- **Enhanced Maintainability** - Easier to navigate and maintain codebase

#### 🐛 Fixed
- **Menu Icon Sizing** - Fixed oversized admin menu icon with proper CSS constraints (20x20px)
- **Asset Format Migration** - Converted SVG icons to PNG format for better compatibility

## [1.2.0] - 2026-02-10

### 🎉 Major UI Overhaul - Professional Admin Interface

#### ✨ Added
- **Dedicated Sidebar Menu** - Plugin now appears as "Toggle" in WordPress sidebar (not in Settings submenu)
- **Custom Menu Icon** - Branded toggle icon in WordPress admin sidebar and top bar
- **Professional Header** - Custom header with Syntekpro-Toggle logo and light yellow background
- **Branded Footer** - Footer with "Powered by SyntekPro" and logo that animates on hover
- **Dashboard Widget** - Quick stats widget on WordPress dashboard showing:
  - Current mode status
  - Toggle button status
  - Button position
  - Quick action links to Settings and Options
- **Tabbed Interface** - Clean tab navigation between Settings and Options pages
- **Options Page** - New visual overview page with cards displaying:
  - All current settings at a glance
  - Color previews for all colors
  - Current configuration stats
  - Quick edit access
- **Custom Branding Assets** - Added `/assets/img/` folder with:
  - Syntekpro-Toggle logo SVG
  - SyntekPro logo SVG
  - Toggle icon SVG

#### 🎨 UI/UX Improvements
- Light yellow gradient background (#fffbea to #fff9db) for header and footer
- Animated hover effect on footer logo (scales up and adds shadow)
- Card-based options display with hover animations
- Professional color-coded statistics
- Responsive grid layout for options cards
- Improved sidebar organization
- Better visual hierarchy throughout admin pages

#### 🔧 Technical Changes
- Changed from `add_options_page` to `add_menu_page` (top-level menu)
- Added submenu pages for Settings and Options
- Created dashboard widget function
- Updated admin enqueue hooks for new page slugs
- Enhanced CSS with new classes for header, footer, tabs, and cards
- Added visual color preview elements
- Improved responsive breakpoints

#### 📝 Documentation
- Updated README with new admin interface documentation
- Added dashboard widget description
- Updated screenshots references
- Added interface section explaining all new features

---

## [1.1.0] - 2026-02-10

### 🎉 Major Update - Admin Panel & Enhanced Theme Support

#### ✨ Added
- **Admin Settings Page** in WordPress dashboard (Settings → Dark Mode Toggle)
  - Default mode selection (Auto/Light/Dark/Manual)
  - Enable/disable toggle button
  - Button position options (4 corners)
  - Adjustable button size (30-100px)
  - Color customization with WordPress color picker:
    - Background color
    - Text color
    - Link color
    - Secondary background color
  - Custom CSS field for advanced styling
  - Transition speed control (0-2 seconds)
  - Reset to defaults button
  - Live preview indicators

#### 🎨 Enhanced Theme Support
- Extended CSS variable support for ALL WordPress block themes:
  - Twenty Twenty-Five
  - Twenty Twenty-Four
  - Twenty Twenty-Three
  - All other block themes
- Added specific styling for WordPress blocks:
  - Cover blocks
  - Group blocks
  - Columns blocks
  - Media & Text blocks
  - Navigation blocks
  - Button blocks
  - Search blocks

#### 🔧 Technical Improvements
- Admin settings stored in WordPress options table
- Settings sanitization and validation
- Inline script now respects admin settings
- JavaScript reads admin configuration
- Custom CSS injection system
- WordPress Color Picker API integration
- Form validation for all inputs
- Proper settings API implementation

#### 📝 Admin Features
- Professional admin interface with sidebar
- Quick tips and documentation links
- Theme compatibility information
- Visual color indicators
- Button size preview
- Transition speed descriptions
- Validation messages
- Success notifications

#### 🐛 Bug Fixes
- Fixed FOUC with configurable default modes
- Improved localStorage priority handling
- Better OS preference detection with settings

---

## [1.0.0] - 2026-02-10

### 🎉 Initial Release

#### ✨ Added
- Dark/Light mode toggle functionality
- Automatic OS preference detection using `prefers-color-scheme`
- localStorage persistence for manual toggle choices
- Floating toggle button with Sun/Moon SVG icons
- FOUC prevention with inline head script
- CSS custom properties for Twenty Twenty-Five theme compatibility
- Smooth 0.3s color transitions
- Responsive design for all screen sizes
- Accessibility features:
  - ARIA labels for screen readers
  - Keyboard navigation support
  - Focus indicators
  - Reduced motion support for users with vestibular disorders
- Print styles (forces light mode for printing)
- Comprehensive dark mode styles for:
  - Body and text elements
  - Links and navigation
  - Forms and input fields
  - Buttons and interactive elements
  - Code blocks and pre-formatted text
  - Tables and data structures
  - Sidebar and widgets

#### 🎨 Styling
- Twenty Twenty-Five theme CSS variables targeting:
  - `--wp--preset--color--base`
  - `--wp--preset--color--contrast`
  - `--wp--preset--color--primary`
  - `--wp--preset--color--secondary`
  - `--wp--preset--color--tertiary`
- Custom fallback colors for compatibility
- Hover and active states for toggle button
- Mobile-responsive button sizing (45px on screens < 768px)

#### 🔧 Technical
- Plugin constants for version control:
  - `SYNTEKPRO_TOGGLE_VERSION`
  - `SYNTEKPRO_TOGGLE_PLUGIN_DIR`
  - `SYNTEKPRO_TOGGLE_PLUGIN_URL`
- JavaScript enqueued in head (priority loading)
- CSS enqueued with proper versioning
- Inline script with priority 1 for instant application
- Real-time OS preference change listener

#### 📦 Requirements
- WordPress 5.0 or higher
- PHP 7.2 or higher
- Modern browser with CSS custom properties support

#### 📝 Documentation
- Comprehensive README.md
- Installation instructions
- Customization guide
- Troubleshooting section
- Browser compatibility list
- Developer hooks documentation

#### 🏗️ Project Structure
- `syntekpro-toggle.php` - Main plugin file
- `script.js` - Toggle functionality
- `style.css` - Dark mode styles
- `README.md` - Documentation
- `CHANGELOG.md` - Version history
- `LICENSE` - GPL-2.0+ license
- `.gitignore` - Git ignore rules

---

## [Unreleased]

### ✨ Added
- **WordPress Consent API Compatibility** - Declares plugin consent category using `wp_set_consent_type('preferences')` on `init` when available.

### 🔧 Changed
- **Backward Compatibility Guard** - Consent API registration now uses a `function_exists('wp_set_consent_type')` check to avoid issues on WordPress installs where the function is unavailable.

### 🔮 Planned Features
- WordPress.org repository submission
- Admin settings page with:
  - Custom color picker
  - Button position options
  - Enable/disable per page
- Multiple theme presets (blue, purple, green, red)
- Toggle animation options (fade, slide, flip)
- Scheduled auto-switching based on time of day
- Widget area integration
- Shortcode support for embedded toggles
- REST API endpoints for programmatic control
- Import/export settings
- Multi-site network support

### 🐛 Known Issues
- Limited Internet Explorer 11 support (no CSS custom properties)
- Some third-party page builders may require additional CSS targeting

---

## Version History

- **1.2.0** (2026-02-10) - Professional admin interface, dedicated menu, dashboard widget
- **1.1.0** (2026-02-10) - Admin panel, enhanced theme support, color customization
- **1.0.0** (2026-02-10) - Initial release

---

## Versioning Scheme

We use [Semantic Versioning](https://semver.org/):

- **MAJOR** version for incompatible API changes
- **MINOR** version for new functionality in a backwards compatible manner
- **PATCH** version for backwards compatible bug fixes

## Categories

- `Added` - New features
- `Changed` - Changes in existing functionality
- `Deprecated` - Soon-to-be removed features
- `Removed` - Removed features
- `Fixed` - Bug fixes
- `Security` - Vulnerability fixes

---

**[Compare versions](https://github.com/syntekpro/Syntekpro-Toggle/compare)**
