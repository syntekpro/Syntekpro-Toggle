# SyntekPro Toggle

A lightweight WordPress dark/light mode toggle plugin with admin controls, block-theme compatibility, accessibility-focused behavior, and local preference persistence.

![Version](https://img.shields.io/badge/version-1.6.2-blue.svg)
![WordPress](https://img.shields.io/badge/wordpress-5.0+-green.svg)
![PHP](https://img.shields.io/badge/php-7.2+-purple.svg)
![License](https://img.shields.io/badge/license-GPL--2.0+-orange.svg)

## Features

- Frontend dark/light mode toggle with OS preference detection (`prefers-color-scheme`)
- User preference storage via local storage and/or cookie modes
- Configurable button theme, shape, position, size, and animation
- Color scheme controls with presets and custom colors
- Optional filters for images, videos, and slide embeds
- Display rules, user targeting, schedule controls, and theme exclusions
- Optional local analytics dashboard (stored in WordPress options)
- Admin UI mode and dashboard widget controls
- WordPress Consent API compatibility via `wp_set_consent_type('preferences')` when available
- Translation-ready text domain with POT file in `languages/`
- **Automatic update notifications via GitHub Releases** — bump the version and push a tagged release to have every site prompt the one-click WordPress update

## Requirements

- WordPress 5.0+
- PHP 7.2+

## Installation

### Manual (ZIP)

1. In WordPress admin, go to **Plugins → Add New → Upload Plugin**.
2. Upload the plugin ZIP and activate it.
3. Open **Toggle** from the admin sidebar.
4. Configure settings as needed.

### Development (Git)

1. Clone this repository:
   ```bash
   git clone https://github.com/syntekpro/Syntekpro-Toggle.git
   ```
2. Place the plugin folder in `/wp-content/plugins/`.
3. Activate from **Plugins**.
4. Open **Toggle** from the admin sidebar.

## Usage Notes

- Default mode options: `Auto`, `Light`, `Dark`, `Manual`.
- If `Auto` is enabled, OS preference can be used as source.
- Display and targeting rules can restrict where the toggle renders.
- Analytics data remains local to your WordPress site.
- Consent category is declared as `preferences` for compatible WordPress versions, with safe fallback when the Consent API function is unavailable.

## Developer Notes

- Main plugin file: `syntekpro-toggle.php`
- Admin logic: `admin/admin.php`
- Frontend CSS/JS: `public/css/style.css`, `public/js/script.js`
- Uninstall cleanup: `uninstall.php`
- Translation template: `languages/syntekpro-toggle.pot`
- GitHub updater: `includes/class-github-updater.php`

## Releasing a New Version

1. Bump `Version:` in the plugin header (`syntekpro-toggle.php`) and the `SYNTEKPRO_TOGGLE_VERSION` constant.
2. Add an entry to `CHANGELOG.md`.
3. Commit and push to `main`.
4. Create and push a tag matching the version (prefix `v`), e.g. `git tag v1.6.2 && git push origin v1.6.2`.
5. Create a GitHub Release for that tag (attach a plugin ZIP as a release asset).
6. Every site running the plugin will see the standard WordPress update notice within 12 hours (or on the next manual check).

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for full release history.

## License

GPL v2 or later. See [LICENSE](LICENSE).

## Author

Syntekpro

- Website: https://syntekpro.com
- Plugin Page: https://plugins.syntekpro.com/toggle
- Support: development@syntekpro.com
