=== Syntekpro Toggle ===
Contributors: syntekpro
Tags: dark mode, light mode, accessibility, toggle, block themes
Requires at least: 5.0
Tested up to: 6.7
Requires PHP: 7.2
Stable tag: 1.6.7
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A lightweight dark/light mode toggle for WordPress with OS preference detection, user preference persistence, and admin customization.

== Description ==

Syntekpro Toggle adds a frontend dark mode toggle with accessibility-focused behavior and broad block-theme compatibility.

Features include:
- Dark/light mode toggle button
- OS preference support (`prefers-color-scheme`)
- User preference persistence via local storage and/or cookies
- Configurable button theme, position, and behavior
- Optional analytics stored locally in WordPress options
- Admin-side customization controls
- WordPress Consent API compatibility (`wp_set_consent_type('preferences')`) with backward-safe fallback

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/`.
2. Activate the plugin from the Plugins screen.
3. Go to **Toggle** in the admin menu.
4. Configure mode, display, and accessibility settings.

== Frequently Asked Questions ==

= Does this plugin send analytics data to external services? =

No. Analytics data is stored locally in WordPress options.

= Does it work with block themes? =

Yes. The plugin is designed to work with modern WordPress block themes.

= Can I disable toggle output on selected pages? =

Yes. Use the Display Rules and targeting options in plugin settings.

= Is this plugin translation-ready? =

Yes. The plugin loads a text domain and includes a POT template in the `languages` directory.

== Changelog ==

= 1.6.7 =
- Fixed: Both sun and moon icons showing simultaneously due to CSS specificity conflict with display:flex !important on the icon container span.
- Fixed: Toggle button jumping to page bottom on click when a theme overrides position with higher CSS specificity. position:fixed is now also set inline.

= 1.6.6 =
- Added SyntekPro Chat to the "Other Plugins" section in About page.
- Replaced emoji plugin icons with real PNG images in the Other Plugins grid.
- Used SyntekPro Plugins Logo image in the "Other Plugins" section heading.
- Fully redesigned License/Toggle+ page to sidebar-nav layout with collapsible sections.
- Corrected all plugin card URLs (docs, support, Forms, Animations, License Server).
- Version badge now shown in red for higher visibility.
- "Get Toggle+" button repositioned to the far right in the admin header.
- Standardised expand/collapse arrows across all admin pages.
- Fixed: Double "Analytics Settings" heading no longer appears.
- Fixed: Double expand/collapse arrows on section headings with nested h2 elements.

= 1.6.5 =
- Added three dedicated settings pages (Frontend, Admin Panel, Media).
- Added freemium gating with free/Toggle+ labels on settings.
- Added Advanced Media Settings fields.
- Collapsible About page with accordion layout and User Guide.
- SyntekPro Forms, Animations, and License Server moved into About page.
- Menu icon resized; sidebar label updated to "SyntekPro Toggle".

= 1.6.3 =
- Added GitHub update notifier so every site running the plugin receives WordPress-style update prompts when a new GitHub Release is published.
- Moved admin bar dark-mode toggle icon to the right side of the top bar (`top-secondary` group).
- Renamed plugin display name from "Syntekpro-Toggle" to "SyntekPro Toggle" (hyphen removed).
- Fixed: "Show toggle button on frontend" checkbox not saving when unchecked (sentinel hidden field approach).
- Fixed: Toggle button not visible on some sites due to theme CSS overriding fixed positioning, z-index and display properties.

= 1.6.2 =
- Added custom frontend toggle icon upload option via media uploader.
- Added custom admin floating toggle icon upload option via media uploader.
- Restored default sun/moon icon behavior when no custom icon is set.
- Increased WordPress admin menu icon size to 25x25px for better visibility.

= 1.6.1 =
- Added full i18n coverage for admin interface strings and regenerated translation template.
- Improved admin/AJAX request handling and sanitization paths.
- Added uninstall cleanup routine and WordPress.org-style readme packaging updates.

= 1.6.0 =
- Analytics navigation updates
- License key input flow
- Admin UX and tab-switching fixes

= 1.0.0 =
- Initial release
