# Syntekpro-Toggle

A lightweight WordPress plugin that adds an automatic and manual Dark/Light mode toggle with localStorage persistence and OS preference detection.

![Version](https://img.shields.io/badge/version-1.0.0-blue.svg)
![WordPress](https://img.shields.io/badge/wordpress-5.0+-green.svg)
![PHP](https://img.shields.io/badge/php-7.2+-purple.svg)
![License](https://img.shields.io/badge/license-GPL--2.0+-orange.svg)

## 🌟 Features

- **🌓 Automatic OS Detection**: Respects user's system dark/light mode preference
- **💾 LocalStorage Persistence**: Remembers user's manual toggle choice across sessions
- **⚡ Zero FOUC**: JavaScript loads in header to prevent Flash of Unstyled Content
- **🎨 Twenty Twenty-Five Compatible**: Specifically targets TT5 theme CSS variables
- **♿ Accessible**: ARIA labels, keyboard navigation, and reduced motion support
- **📱 Responsive**: Beautiful floating toggle button that adapts to all screen sizes
- **🎯 Lightweight**: Minimal footprint with no dependencies
- **🔄 Smooth Transitions**: 0.3s CSS transitions for seamless color changes

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

3. Activate the plugin through the 'Plugins' menu in WordPress

4. That's it! The toggle button will automatically appear on your site

### Manual Installation

1. Download the ZIP file from the [releases page](https://github.com/syntekpro/Syntekpro-Toggle/releases)
2. Go to WordPress Admin → Plugins → Add New → Upload Plugin
3. Choose the ZIP file and click "Install Now"
4. Activate the plugin

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

### Changing Button Position

Edit `style.css`:

```css
.syntekpro-toggle-btn {
    bottom: 30px;  /* Change vertical position */
    right: 30px;   /* Change horizontal position */
}
```

### Customizing Colors

Dark mode colors can be adjusted in `style.css`:

```css
html.dark-mode body {
    --wp--preset--color--base: #1a1a1a; /* Background */
    --wp--preset--color--contrast: #ffffff; /* Text */
}
```

### Button Appearance

Modify button styles in `style.css`:

```css
.syntekpro-toggle-btn {
    width: 50px;
    height: 50px;
    border-radius: 50%; /* Make it square: 8px */
    background-color: #333333;
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

Your theme may use different CSS variables. Inspect elements and update `style.css` accordingly:

```css
html.dark-mode {
    --your-theme-bg-var: #1a1a1a;
    --your-theme-text-var: #ffffff;
}
```

## 📚 File Structure

```
Syntekpro-Toggle/
├── syntekpro-toggle.php    # Main plugin file
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

## 🔮 Roadmap

- [ ] WordPress.org repository submission
- [ ] Custom color picker in admin settings
- [ ] Multiple theme presets (blue, purple, green)
- [ ] Toggle animation options
- [ ] Scheduled auto-switching (day/night times)
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
