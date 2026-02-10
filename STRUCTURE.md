# Plugin Structure

This document outlines the folder structure and organization of the Syntekpro-Toggle plugin.

## Directory Layout

```
Syntekpro-Toggle/
│
├── syntekpro-toggle.php          # Main plugin file (entry point)
│
├── admin/                         # Admin-related files
│   ├── admin.php                  # Admin functionality & settings pages
│   ├── css/
│   │   └── admin.css              # Admin panel styling
│   └── js/
│       └── admin.js               # Admin panel JavaScript
│
├── public/                        # Frontend-related files
│   ├── css/
│   │   └── style.css              # Frontend dark mode styles
│   └── js/
│       └── script.js              # Frontend toggle functionality
│
├── assets/                        # Media assets
│   └── img/                       # Images and icons
│       ├── toggle-icon.png        # WordPress menu icon
│       ├── syntekpro-toggle-logo.png  # Plugin header logo
│       └── syntekpro-logo.png     # Footer branding logo
│
├── README.md                      # Plugin documentation
├── CHANGELOG.md                   # Version history
├── LICENSE                        # GPL v2 license
└── .gitignore                     # Git ignore rules
```

## File Descriptions

### Root Level

- **syntekpro-toggle.php**: Main plugin file containing plugin header, constants, and hooks
  - Registers admin functionality
  - Enqueues public scripts and styles
  - Adds inline FOUC prevention script
  - Renders toggle button

### Admin Directory (`/admin/`)

Contains all WordPress admin panel related code:

- **admin.php**: Complete admin functionality
  - Settings page with tabs
  - Options overview page
  - Dashboard widget
  - WordPress Settings API integration
  - Color picker integration
  - Database option management

- **css/admin.css**: Admin panel styling
  - Professional header with branding
  - Footer styling
  - Card layouts for options page
  - Form styling
  - Color picker enhancements
  - Dashboard widget styling

- **js/admin.js**: Admin interactivity
  - WordPress Color Picker initialization
  - Form validation
  - Button size preview
  - Reset to defaults functionality
  - Tab persistence

### Public Directory (`/public/`)

Contains all frontend-facing code:

- **css/style.css**: Frontend dark mode styling
  - Dark mode color schemes
  - WordPress block theme compatibility
  - CSS variable overrides
  - Toggle button styling
  - Responsive design

- **js/script.js**: Frontend toggle functionality
  - Dark mode detection
  - LocalStorage management
  - OS preference detection
  - Button icon updates
  - Smooth transitions

### Assets Directory (`/assets/`)

Contains media files:

- **img/**: Images and branding
  - Menu icons
  - Logo files
  - Dashboard widget icons

## Architecture Benefits

### Separation of Concerns
- **Admin files** are isolated from **public files**
- Easy to identify what affects the backend vs frontend
- Cleaner namespace organization

### Maintainability
- Logical grouping of related files
- Easier to locate specific functionality
- Clear file naming conventions

### Scalability
- Easy to add new admin or public features
- Room for additional subdirectories as needed
- Modular structure supports growth

### WordPress Best Practices
- Follows WordPress plugin development standards
- Compatible with plugin review guidelines
- Professional structure used by premium plugins

## Version History

- **v1.3.0** - Implemented professional folder structure
- **v1.2.0** - Added admin panel and dashboard widget
- **v1.1.0** - Extended theme support and color customization
- **v1.0.0** - Initial release with basic toggle functionality

## Development

When adding new features:

1. **Admin features** → Add to `/admin/` directory
2. **Frontend features** → Add to `/public/` directory
3. **Assets** → Add to `/assets/` with appropriate subdirectory
4. **Documentation** → Update relevant markdown files in root

Always update paths in main plugin file when adding new assets or scripts.
