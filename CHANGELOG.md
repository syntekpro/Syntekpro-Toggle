# Changelog

All notable changes to Syntekpro-Toggle will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

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
