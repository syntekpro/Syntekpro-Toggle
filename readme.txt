=== Syntekpro Toggle ===
Contributors: syntekpro
Tags: dark mode, light mode, accessibility, toggle, block themes
Requires at least: 5.0
Tested up to: 6.7
Requires PHP: 7.2
Stable tag: 1.6.2
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

= Unreleased =
- Added WordPress Consent API compatibility declaration using `wp_set_consent_type('preferences')` on `init` when available.
- Added backward compatibility guard with `function_exists('wp_set_consent_type')` for older WordPress environments.

= 1.6.2 =
- Added optional frontend custom toggle icon upload setting with media picker.
- Added optional admin floating toggle icon upload setting with media picker.
- Restored default sun/moon toggle icons as fallback when custom icons are not set.
- Increased admin menu icon sizing to 25x25px for better visibility.

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
