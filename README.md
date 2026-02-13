# Syntekpro-Toggle

A professional WordPress plugin with a dedicated admin interface for Dark/Light mode toggle. Full-featured admin panel with custom branding, dashboard widget, and support for ALL WordPress block themes.

![Version](https://img.shields.io/badge/version-1.4.0-blue.svg)
![WordPress](https://img.shields.io/badge/wordpress-5.0+-green.svg)
![PHP](https://img.shields.io/badge/php-7.2+-purple.svg)
![License](https://img.shields.io/badge/license-GPL--2.0+-orange.svg)

## 🌟 Features

- **🎯 Dedicated Admin Page**: Full-fledged sidebar menu (not hidden in Settings)
- **🎨 Professional Interface**: Custom branded header and footer with logos
- **📊 Dashboard Widget**: Quick stats and actions on WordPress dashboard
- **📑 Tabbed Interface**: Organized Settings and Options pages
- **⚙️ Color Customization**: WordPress color picker for all dark mode colors
- **🌓 Multiple Modes**: Auto/Light/Dark/Manual default modes
- **📍 Flexible Positioning**: Place toggle button in any corner
- **💾 LocalStorage Persistence**: Remembers user's manual toggle choice
- **⚡ Zero FOUC**: JavaScript loads in header to prevent Flash of Unstyled Content
- **🎯 Universal Block Theme Support**: Works with ALL WordPress block themes
- **♿ Accessible**: ARIA labels, keyboard navigation, and reduced motion support
- **📱 Responsive**: Beautiful floating toggle button that adapts to all screen sizes
- **🎯 Lightweight**: Minimal footprint with no dependencies
- **🔄 Smooth Transitions**: Configurable CSS transitions (0-2 seconds)
- **✏️ Custom CSS**: Add your own CSS rules for advanced customization

## 📋 Requirements

- WordPress 5.0 or higher
- PHP 7.2 or higher
- Modern browser with CSS custom properties support

## 🚀 Installation

### From GitHub

1. Download the plugin files or clone this repository:
   ```bash
   git clone https://github.com/syntekpro/Syntekpro-Toggle.git
   ```

2. Upload the `Syntekpro-Toggle` folder to your `/wp-content/plugins/` directory
Access the plugin from **WordPress Admin → Toggle** (sidebar menu)

5. Customize colors, position, and behavior as needed!

6. Check the dashboard widget for quick stats
4. Go to **Settings → Dark Mode Toggle** to configure options

5. Customize colors, position, and behavior as needed!

### Manual Installation

1. Download the ZIP file from the [releases page](https://github.com/syntekpro/Syntekpro-Toggle/releases)
2. Go to WordPress Admin → Plugins → Add New → Upload Plugin
3. Choose the ZIPToggle** in the WordPress admin sidebar

## 🎨 Interface

### Admin Menu Location
Access the plugin directly from the WordPress sidebar menu as **"Toggle"** with a custom icon.

### Dashboard Widget
Quick overview widget on the WordPress dashboard showing:
- Current mode status
- Toggle button status (Active/Inactive)
- Button position
- Quick links to Settings and Options

### Settings Page
**Access: Toggle → Settings**

Comprehensive settings with a beautiful branded interface featuring:
- Custom Syntekpro-Toggle header with logo (light yellow background)
- Tabbed navigation for Settings and Options
- All configuration options with WordPress color pickers
- Professional sidebar with tips and support links
- Branded footer with "Powered by SyntekPro" and animated logo

### Options Page
**Access: Toggle → Options**

Visual overview of all current settings displayed in cards:
- Current mode, button status, position, size
- Color previews for all dark mode colors
- Transition speed
- Quick action buttons
4. Activate the plugin
5. Navigate to **Settings → Dark Mode Toggle**
Admin-Configurable Behavior

The plugin respects your admin settings with this priority order:

```
1. User's manual toggle (stored in localStorage) - highest priority
2. Admin panel default mode setting
3. OS/System preference (if Auto mode selected)
4. Light mode fallback
```

### All Block Themes Supported

Works out-of-the-box with:
- **Twenty Twenty-Five** (TT5)
- **Twenty Twenty-Four** (TT4)
- **Twenty Twenty-Three** (TT3)
- **All other WordPress block themes**

Automatically targets these CSS variables:
- `--wp--preset--color--base`
- `--wp--preset--color--contrast`
- `--wp--preset--color--primary`
- `--wp--preset--color--secondary`
- `--wp--preset--color--background`
- `--wp--preset--color--foreground`
- Plus custom fallback colors
#### Advanced Settings

- **Custom CSS**: Add your own CSS rules (no curly braces needed)
- **Transition Speed**: Control animation speed (0-2 seconds, 0.3s default)

## 💡 How It Works

### JavaScript-First Approach

The plugin uses a dual-script approach to prevent FOUC:

1. **Inline Head Script**: Runs immediately before page render to apply saved/OS preference
2. **Main Script**: Handles user interactions and localStorage management

### localStorage Logic

```javascript
// Priority order:
1. User's manual toggle preference (stored in localStorage)
2. OS/System preference (prefers-color-scheme media query)
3. Default light mode
```

### CSS Variables

The plugin overrides Twenty Twenty-Five theme variables:

```css
--wp--preset--color--base
--wp--preset--color--contrast
--wp--preset--color--primary
--wp--preset--color--secondary
--wp--preset--color--tertiary
```

## 🎨 Customization

### Using Admin Panel (Recommended)

1. Go to **Toggle → Settings** in WordPress admin
2. Adjust colors using the color pickers
3. Set button position and size
4. Add custom CSS if needed
5. Save settings

### View Current Configuration

1. Go to **Toggle → Options** for a visual overview
2. See all settings at a glance with color previews
3. Quick access to edit settings

### Changing Colors Programmatically

```php
// In your theme's functions.php
add_filter('option_syntekpro_toggle_options', function($options) {
    $options['bg_color'] = '#000000'; // Pure black
    $options['text_color'] = '#f0f0f0'; // Off-white
    return $options;
});
```

### Custom CSS Examples

In the **Admin Panel → Custom CSS** field:

```css
/* Make headers even darker */
header, nav, footer {
    background-color: #0a0a0a;
}

/* Adjust link hover color */
a:hover {
    color: #ffd700;
}

/* Style specific blocks */
.wp-block-quote {
    border-left-color: #6ea8fe;
}
```

## 🔧 Developer Hooks

### Filters

```php
// Modify toggle button HTML
add_filter('syntekpro_toggle_button_html', function($html) {
    // Your custom HTML
    return $html;
});
```

### Actions

```php
// Execute code when scripts are enqueued
add_action('syntekpro_toggle_enqueue_scripts', function() {
    // Your custom code
});
```

## 📱 Browser Support

- ✅ Chrome/Edge (latest)
- ✅ Firefox (latest)
- ✅ Safari (latest)
- ✅ Opera (latest)
- ⚠️ IE 11 (limited support - no CSS variables)

## 🐛 Troubleshooting

### Toggle doesn't work

1. Clear browser cache and localStorage
2. Check browser console for JavaScript errors
3. Ensure no theme conflicts with `.dark-mode` class
4. Verify WordPress 5.0+ and PHP 7.2+ requirements

### Flash of white on dark mode

The script must load in `<head>`. Verify in plugin file:

```php
wp_enqueue_script(..., false); // false = head, true = footer
```

### Theme colors don't change

1. Go to **Toggle → Settings** in WordPress admin
2. Use the color pickers to adjust colors
3. If specific elements aren't changing, add custom CSS:
   ```css
   .my-element {
       background-color: #2d2d2d;
       color: #ffffff;
   }
   ```
4. Check browser console for CSS specificity issues

## admin.php               # Admin settings page
├── admin.css              # Admin panel styles
├── admin.js               # Admin panel JavaScript
├── script.js              # Frontend toggle functionality
├── style.css              # Dark mode styles
├── README.md              # This file
├── CHANGELOG.md           # Version history
└── LICENSEo-toggle.php    # Main plugin file
├── script.js               # Toggle functionality
├── style.css               # Dark mode styles
├── README.md               # This file
├── CHANGELOG.md            # Version history
└── LICENSE                 # GPL-2.0+ license
```

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This plugin is licensed under the GPL v2 or later.

```
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
```

## 👨‍💻 Author

**Syntekpro**

- Website: [https://syntekpro.com](https://syntekpro.com)
- Plugin Page: [https://plugins.syntekpro.com/toggle](https://plugins.syntekpro.com/toggle)
- Email: development@syntekpro.com

## 🙏 Acknowledgments

- Inspired by modern dark mode implementations
- Built specifically for Twenty Twenty-Five theme
- SVG icons from Feather Icons

## 📊 Changelog

See [CHANGELOG.md](CHANGELOG.md) for a detailed version history.

## x] Admin settings panel with color pickers
- [x] Support for all WordPress block themes
- [x] Custom CSS field
- [x] Button positioning options
- [ ] WordPress.org repository submission
- [ ] Multiple color theme presets (blue, purple, green)
- [ ] Scheduled auto-switching (day/night times)
- [ ] Per-page toggle disable option
- [ ] Widget and shortcode support
- [ ] Import/export settings
- [ ] Multi-site network supportg (day/night times)
- [ ] Per-page toggle disable option
- [ ] Widget area integration

## 📞 Support

For support, please:

1. Check the [Troubleshooting](#-troubleshooting) section
2. Search existing [GitHub Issues](https://github.com/syntekpro/Syntekpro-Toggle/issues)
3. Open a new issue if needed
4. Email: development@syntekpro.com

---

Made with ❤️ by [Syntekpro](https://syntekpro.com)
