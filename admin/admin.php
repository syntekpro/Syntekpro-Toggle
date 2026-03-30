<?php
/**
 * Admin Settings Page
 * 
 * @package Syntekpro_Toggle
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register admin menu
 */
function syntekpro_toggle_admin_menu() {
    // Add top-level menu page
    add_menu_page(
        __('SyntekPro Toggle', 'syntekpro-toggle'),
        __('SyntekPro Toggle', 'syntekpro-toggle'),
        'manage_options',
        'syntekpro-toggle',
        'syntekpro_toggle_frontend_settings_page',
        SYNTEKPRO_TOGGLE_PLUGIN_URL . 'assets/img/Syntekpro%20Toggle%20%20icon%20Grey%20New.png',
        30
    );
    
    // Add submenu pages
    add_submenu_page(
        'syntekpro-toggle',
        __('Frontend Settings', 'syntekpro-toggle'),
        __('Frontend', 'syntekpro-toggle'),
        'manage_options',
        'syntekpro-toggle',
        'syntekpro_toggle_frontend_settings_page'
    );

    add_submenu_page(
        'syntekpro-toggle',
        __('Admin Panel Settings', 'syntekpro-toggle'),
        __('Admin Panel', 'syntekpro-toggle'),
        'manage_options',
        'syntekpro-toggle-admin-panel',
        'syntekpro_toggle_admin_panel_page'
    );

    add_submenu_page(
        'syntekpro-toggle',
        __('Media Settings', 'syntekpro-toggle'),
        __('Media Settings', 'syntekpro-toggle'),
        'manage_options',
        'syntekpro-toggle-media',
        'syntekpro_toggle_media_settings_page'
    );

    add_submenu_page(
        'syntekpro-toggle',
        __('Analytics', 'syntekpro-toggle'),
        __('Analytics', 'syntekpro-toggle'),
        'manage_options',
        'syntekpro-toggle-analytics',
        'syntekpro_toggle_analytics_page'
    );

    add_submenu_page(
        'syntekpro-toggle',
        __('Options', 'syntekpro-toggle'),
        __('Options', 'syntekpro-toggle'),
        'manage_options',
        'syntekpro-toggle-options',
        'syntekpro_toggle_options_page'
    );

    add_submenu_page(
        'syntekpro-toggle',
        __('About', 'syntekpro-toggle'),
        __('About', 'syntekpro-toggle'),
        'manage_options',
        'syntekpro-toggle-about',
        'syntekpro_toggle_about_page'
    );

    add_submenu_page(
        'syntekpro-toggle',
        __('Toggle+ License', 'syntekpro-toggle'),
        __('⭐ Get Toggle+', 'syntekpro-toggle'),
        'manage_options',
        'syntekpro-toggle-license',
        'syntekpro_toggle_license_page'
    );
}
add_action('admin_menu', 'syntekpro_toggle_admin_menu');

/**
 * Style the "Get Toggle+" menu item with a highlight color.
 */
function syntekpro_toggle_menu_highlight_css() {
    ?>
    <style>
        #adminmenu a[href="admin.php?page=syntekpro-toggle-license"] {
            color: #f0b429 !important;
            font-weight: 700 !important;
        }
        #adminmenu a[href="admin.php?page=syntekpro-toggle-license"]:hover {
            color: #fff !important;
            background: linear-gradient(90deg,#667eea,#764ba2) !important;
        }
    </style>
    <?php
}
add_action('admin_head', 'syntekpro_toggle_menu_highlight_css');

/**
 * Get current plugin admin page slug safely.
 *
 * @return string
 */
function syntekpro_toggle_get_current_admin_page_slug() {
    if (!isset($_GET['page'])) {
        return '';
    }

    $page = wp_unslash($_GET['page']);
    if (!is_scalar($page)) {
        return '';
    }

    return sanitize_key((string) $page);
}

/**
 * Determine whether a settings-updated flag is present.
 *
 * @return bool
 */
function syntekpro_toggle_has_settings_updated_flag() {
    if (!isset($_GET['settings-updated'])) {
        return false;
    }

    $updated = wp_unslash($_GET['settings-updated']);
    if (!is_scalar($updated)) {
        return false;
    }

    return sanitize_text_field((string) $updated) !== '';
}

/**
 * Determine whether a POST action key is present.
 *
 * @param string $action_key POST key.
 * @return bool
 */
function syntekpro_toggle_has_post_action($action_key) {
    if (!is_string($action_key) || $action_key === '') {
        return false;
    }

    return isset($_POST[$action_key]);
}

/**
 * Replace WordPress default footer text with custom message
 */
function syntekpro_toggle_admin_footer_text($footer_text) {
    // Only replace on Syntekpro Toggle pages
    $current_page = syntekpro_toggle_get_current_admin_page_slug();
    if ($current_page && strpos($current_page, 'syntekpro-toggle') === 0) {
        return esc_html__('Thanks For Using', 'syntekpro-toggle') . ' <a href="https://plugins.syntekpro.com/toggle" target="_blank" rel="noopener noreferrer">SyntekPro Toggle</a>';
    }
    return $footer_text;
}
add_filter('admin_footer_text', 'syntekpro_toggle_admin_footer_text');

/**
 * Replace WordPress version with plugin version on the right side
 */
function syntekpro_toggle_admin_footer_version($version_text) {
    // Only replace on Syntekpro Toggle pages
    $current_page = syntekpro_toggle_get_current_admin_page_slug();
    if ($current_page && strpos($current_page, 'syntekpro-toggle') === 0) {
        return '<a href="https://plugins.syntekpro.com/toggle" target="_blank" rel="noopener noreferrer">' . esc_html__('Version', 'syntekpro-toggle') . ' ' . esc_html(SYNTEKPRO_TOGGLE_VERSION) . '</a>';
    }
    return $version_text;
}
add_filter('update_footer', 'syntekpro_toggle_admin_footer_version', 11);

/**
 * Force menu icon size globally (sidebar)
 */
function syntekpro_toggle_menu_icon_css() {
    $options = syntekpro_toggle_get_options();
    ?>
    <style>
        #adminmenu .toplevel_page_syntekpro-toggle .wp-menu-image {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        #adminmenu .toplevel_page_syntekpro-toggle .wp-menu-image img {
            width: 20px !important;
            height: 20px !important;
            max-width: 20px !important;
            max-height: 20px !important;
            min-width: 20px !important;
            min-height: 20px !important;
            object-fit: contain !important;
            padding: 0 !important;
            margin: 0 !important;
            opacity: 0.7;
            position: relative;
            top: -1px;
        }
        #adminmenu .toplevel_page_syntekpro-toggle:hover .wp-menu-image img,
        #adminmenu .toplevel_page_syntekpro-toggle.current .wp-menu-image img {
            opacity: 1;
        }
        #adminmenu .toplevel_page_syntekpro-toggle .wp-menu-name {
            line-height: 1.2;
        }

        <?php if (isset($options['enable_admin_bar_icon']) && $options['enable_admin_bar_icon'] === '1') : ?>
        #wpadminbar #wp-admin-bar-syntekpro-toggle-admin > .ab-item {
            display: inline-flex;
            align-items: center;
            gap: 0;
            padding-left: 2px;
            padding-right: 2px;
            line-height: 30px;
        }
        #wpadminbar #wp-admin-bar-syntekpro-toggle-admin .ab-icon {
            width: 24px;
            height: 24px;
            margin: 0;
            display: block;
        }
        #wpadminbar #wp-admin-bar-syntekpro-toggle-admin .ab-icon img {
            width: 24px;
            height: 24px;
            display: block;
            vertical-align: middle;
        }
        #wpadminbar #wp-admin-bar-syntekpro-toggle-admin .ab-icon:before {
            content: none;
        }
        #wpadminbar #wp-admin-bar-syntekpro-toggle-admin.syntekpro-dark-active .ab-icon:before {
            filter: brightness(1.2);
        }
        <?php endif; ?>

        <?php if (isset($options['enable_admin_dark_mode']) && $options['enable_admin_dark_mode'] === '1') : ?>
        :root {
            --syntekpro-admin-bg: <?php echo esc_attr($options['admin_bg_color']); ?>;
            --syntekpro-admin-text: <?php echo esc_attr($options['admin_text_color']); ?>;
            --syntekpro-admin-accent: <?php echo esc_attr($options['admin_accent_color']); ?>;
            --syntekpro-admin-surface: <?php echo esc_attr($options['admin_surface_color']); ?>;
            --syntekpro-admin-border: <?php echo esc_attr($options['admin_border_color']); ?>;
            --syntekpro-admin-link: <?php echo esc_attr($options['admin_link_color']); ?>;
            --syntekpro-admin-link-hover: <?php echo esc_attr($options['admin_link_hover_color']); ?>;
        }
        body.syntekpro-admin-dark,
        body.syntekpro-admin-dark #wpcontent,
        body.syntekpro-admin-dark #wpbody,
        body.syntekpro-admin-dark #wpwrap {
            background: var(--syntekpro-admin-bg);
            color: var(--syntekpro-admin-text);
        }

        body.syntekpro-admin-dark a { color: var(--syntekpro-admin-link); }
        body.syntekpro-admin-dark a:hover { color: var(--syntekpro-admin-link-hover); }

        body.syntekpro-admin-dark #adminmenu, body.syntekpro-admin-dark #adminmenu .wp-submenu {
            background: var(--syntekpro-admin-surface);
        }
        body.syntekpro-admin-dark #adminmenu a {
            color: var(--syntekpro-admin-text);
        }
        body.syntekpro-admin-dark #adminmenu .wp-submenu a:hover,
        body.syntekpro-admin-dark #adminmenu a:hover {
            color: #ffffff;
            background: var(--syntekpro-admin-border);
        }

        body.syntekpro-admin-dark #wpadminbar {
            background: var(--syntekpro-admin-bg);
        }
        body.syntekpro-admin-dark #wpadminbar .ab-item,
        body.syntekpro-admin-dark #wpadminbar a.ab-item {
            color: var(--syntekpro-admin-text);
        }

        body.syntekpro-admin-dark .wrap h1,
        body.syntekpro-admin-dark .wrap h2,
        body.syntekpro-admin-dark .wrap h3,
        body.syntekpro-admin-dark .wrap h4 {
            color: var(--syntekpro-admin-text);
        }
        body.syntekpro-admin-dark .postbox,
        body.syntekpro-admin-dark .stuffbox,
        body.syntekpro-admin-dark .notice,
        body.syntekpro-admin-dark .card {
            background: var(--syntekpro-admin-surface);
            color: var(--syntekpro-admin-text);
            border-color: var(--syntekpro-admin-border);
        }
        body.syntekpro-admin-dark .postbox .hndle,
        body.syntekpro-admin-dark .postbox .handlediv,
        body.syntekpro-admin-dark .postbox .inside {
            color: var(--syntekpro-admin-text);
        }
        body.syntekpro-admin-dark input,
        body.syntekpro-admin-dark select,
        body.syntekpro-admin-dark textarea {
            background: var(--syntekpro-admin-bg);
            color: var(--syntekpro-admin-text);
            border-color: var(--syntekpro-admin-border);
        }
        body.syntekpro-admin-dark .button-primary {
            background: var(--syntekpro-admin-accent);
            border-color: var(--syntekpro-admin_border, var(--syntekpro-admin-border));
            color: #ffffff;
        }
        body.syntekpro-admin-dark .button-secondary,
        body.syntekpro-admin-dark .button {
            background: var(--syntekpro-admin-surface);
            border-color: var(--syntekpro-admin-border);
            color: var(--syntekpro-admin-text);
        }

        /* Floating admin toggle button - Same design as frontend */
        .syntekpro-admin-fab {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #333333;
            color: #ffffff;
            border: 2px solid #555555;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }
        .syntekpro-admin-fab:hover {
            background-color: #444444;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.4);
            transform: scale(1.05);
        }
        .syntekpro-admin-fab:active {
            transform: scale(0.95);
        }
        .syntekpro-admin-fab .syntekpro-fab-icon {
            font-size: 24px;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Admin SVG Icons */
        .syntekpro-admin-fab .syntekpro-icon-sun,
        .syntekpro-admin-fab .syntekpro-icon-moon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
        }
        
        .syntekpro-admin-fab .syntekpro-icon-sun svg,
        .syntekpro-admin-fab .syntekpro-icon-moon svg {
            width: 24px;
            height: 24px;
            stroke: currentColor;
        }

        .syntekpro-admin-fab .syntekpro-icon-custom {
            display: none;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
        }

        .syntekpro-admin-fab .syntekpro-icon-custom img {
            width: 24px;
            height: 24px;
            object-fit: contain;
            transition: filter 0.3s ease;
            filter: brightness(0) saturate(100%) invert(99%) sepia(3%) saturate(164%) hue-rotate(200deg) brightness(95%) contrast(88%);
        }

        .syntekpro-admin-fab.has-custom-icon .syntekpro-icon-custom {
            display: flex;
        }

        .syntekpro-admin-fab.has-custom-icon .syntekpro-icon-sun,
        .syntekpro-admin-fab.has-custom-icon .syntekpro-icon-moon {
            display: none !important;
        }

        body.syntekpro-admin-dark .syntekpro-admin-fab.has-custom-icon .syntekpro-icon-custom img,
        .syntekpro-admin-fab.has-custom-icon.is-dark .syntekpro-icon-custom img {
            filter: brightness(0) saturate(100%) invert(100%) sepia(0%) saturate(1%) hue-rotate(183deg) brightness(104%) contrast(102%);
        }
        
        /* Show moon in light mode, hide sun */
        .syntekpro-admin-fab .syntekpro-icon-moon {
            display: flex;
        }
        .syntekpro-admin-fab .syntekpro-icon-sun {
            display: none;
        }
        
        /* Show sun in dark mode, hide moon */
        .syntekpro-admin-fab.is-dark .syntekpro-icon-sun {
            display: flex;
        }
        .syntekpro-admin-fab.is-dark .syntekpro-icon-moon {
            display: none;
        }
        .syntekpro-admin-fab.is-dark {
            background-color: #333333;
            color: #ffffff;
            border-color: #555555;
        }
        .syntekpro-admin-fab.is-dark .syntekpro-fab-thumb {
            display: none;
        }
        .syntekpro-admin-fab.is-dark .syntekpro-fab-icon {
            color: #ffffff;
        }
        
        /* Light mode button appearance */
        body:not(.syntekpro-admin-dark) .syntekpro-admin-fab {
            background-color: #f0f0f0;
            color: #333333;
            border-color: #cccccc;
        }
        body:not(.syntekpro-admin-dark) .syntekpro-admin-fab:hover {
            background-color: #e0e0e0;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .syntekpro-admin-fab {
                width: 45px;
                height: 45px;
                bottom: 20px;
                right: 20px;
            }
            .syntekpro-admin-fab .syntekpro-icon-sun svg,
            .syntekpro-admin-fab .syntekpro-icon-moon svg {
                width: 20px;
                height: 20px;
            }
        }
        
        /* Accessibility */
        .syntekpro-admin-fab:focus {
            outline: 2px solid #6ea8fe;
            outline-offset: 2px;
        }
        .syntekpro-admin-fab:focus:not(:focus-visible) {
            outline: none;
        }
        
        /* ===================================
           Admin FAB Theme Styles
           =================================== */
        
        /* Theme: Minimal */
        .syntekpro-admin-fab.theme-minimal {
            background-color: #f8f9fa;
            color: #212529;
            border: 1px solid #dee2e6;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        body.syntekpro-admin-dark .syntekpro-admin-fab.theme-minimal {
            background-color: #212529;
            color: #f8f9fa;
            border-color: #495057;
        }
        .syntekpro-admin-fab.theme-minimal:hover {
            background-color: #e9ecef;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        }
        
        /* Theme: Neumorphic */
        .syntekpro-admin-fab.theme-neumorphic {
            background: #e0e5ec;
            color: #333;
            border: none;
            box-shadow: 8px 8px 16px #a3b1c6, -8px -8px 16px #ffffff;
        }
        body.syntekpro-admin-dark .syntekpro-admin-fab.theme-neumorphic {
            background: #2c3e50;
            color: #ecf0f1;
            box-shadow: 8px 8px 16px #1a252f, -8px -8px 16px #3e5771;
        }
        .syntekpro-admin-fab.theme-neumorphic:hover {
            box-shadow: 4px 4px 8px #a3b1c6, -4px -4px 8px #ffffff;
        }
        
        /* Theme: Glassmorphic */
        .syntekpro-admin-fab.theme-glassmorphic {
            background: rgba(255, 255, 255, 0.25);
            color: #333;
            border: 1px solid rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }
        body.syntekpro-admin-dark .syntekpro-admin-fab.theme-glassmorphic {
            background: rgba(0, 0, 0, 0.25);
            color: #fff;
            border-color: rgba(255, 255, 255, 0.18);
        }
        
        /* Theme: Neon */
        .syntekpro-admin-fab.theme-neon {
            background: #0a0e27;
            color: #00ffff;
            border: 2px solid #00ffff;
            box-shadow: 0 0 20px #00ffff, inset 0 0 20px rgba(0, 255, 255, 0.2);
        }
        body.syntekpro-admin-dark .syntekpro-admin-fab.theme-neon {
            color: #ff00ff;
            border-color: #ff00ff;
            box-shadow: 0 0 20px #ff00ff, inset 0 0 20px rgba(255, 0, 255, 0.2);
        }
        .syntekpro-admin-fab.theme-neon:hover {
            box-shadow: 0 0 30px #00ffff, inset 0 0 30px rgba(0, 255, 255, 0.3);
        }
        
        /* Theme: Gradient */
        .syntekpro-admin-fab.theme-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        body.syntekpro-admin-dark .syntekpro-admin-fab.theme-gradient {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            box-shadow: 0 4px 15px rgba(245, 87, 108, 0.4);
        }
        
        /* Theme: Retro */
        .syntekpro-admin-fab.theme-retro {
            background: linear-gradient(45deg, #ff006e, #ffbe0b);
            color: #fff;
            border: 3px solid #000;
            border-radius: 8px;
            box-shadow: 4px 4px 0 rgba(0, 0, 0, 0.5);
        }
        body.syntekpro-admin-dark .syntekpro-admin-fab.theme-retro {
            background: linear-gradient(45deg, #00b4d8, #90e0ef);
            border-color: #023e8a;
        }
        .syntekpro-admin-fab.theme-retro:hover {
            transform: translate(2px, 2px);
            box-shadow: 2px 2px 0 rgba(0, 0, 0, 0.5);
        }
        
        /* Theme: Modern */
        .syntekpro-admin-fab.theme-modern {
            background: #000;
            color: #fff;
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        body.syntekpro-admin-dark .syntekpro-admin-fab.theme-modern {
            background: #fff;
            color: #000;
        }
        
        /* Theme: Flat */
        .syntekpro-admin-fab.theme-flat {
            background: #3498db;
            color: #fff;
            border: none;
            border-radius: 4px;
            box-shadow: none;
        }
        body.syntekpro-admin-dark .syntekpro-admin-fab.theme-flat {
            background: #e74c3c;
        }
        
        /* Theme: Material */
        .syntekpro-admin-fab.theme-material {
            background: #2196F3;
            color: #fff;
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2), 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        body.syntekpro-admin-dark .syntekpro-admin-fab.theme-material {
            background: #FF9800;
        }
        
        /* Theme: iOS Style */
        .syntekpro-admin-fab.theme-ios {
            background: linear-gradient(180deg, #f8f9fa 0%, #e9ecef 100%);
            color: #333;
            border: 1px solid rgba(0, 0, 0, 0.1);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12);
        }
        body.syntekpro-admin-dark .syntekpro-admin-fab.theme-ios {
            background: linear-gradient(180deg, #3a3a3c 0%, #2c2c2e 100%);
            color: #fff;
        }
        
        /* Theme: Cyberpunk */
        .syntekpro-admin-fab.theme-cyberpunk {
            background: linear-gradient(135deg, #f72585, #7209b7, #3a0ca3);
            color: #00ff41;
            border: 1px solid #00ff41;
            border-radius: 4px;
            box-shadow: 0 0 20px rgba(247, 37, 133, 0.6);
        }
        body.syntekpro-admin-dark .syntekpro-admin-fab.theme-cyberpunk {
            background: linear-gradient(135deg, #06ffa5, #0a84ff, #bf0eff);
            color: #000;
            border-color: #000;
        }
        
        /* Theme: Elegant */
        .syntekpro-admin-fab.theme-elegant {
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: #ecf0f1;
            border: 2px solid rgba(236, 240, 241, 0.1);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }
        body.syntekpro-admin-dark .syntekpro-admin-fab.theme-elegant {
            background: linear-gradient(135deg, #bdc3c7, #95a5a6);
            color: #2c3e50;
        }
        
        /* Theme: Playful */
        .syntekpro-admin-fab.theme-playful {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: #fff;
            border: none;
            box-shadow: 0 4px 15px rgba(240, 147, 251, 0.5);
            animation: playful-bounce 2s infinite;
        }
        body.syntekpro-admin-dark .syntekpro-admin-fab.theme-playful {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        @keyframes playful-bounce { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.05) translateY(-3px); } }
        
        /* Theme: Professional */
        .syntekpro-admin-fab.theme-professional {
            background: #1a1a2e;
            color: #eee;
            border: 1px solid #16213e;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }
        body.syntekpro-admin-dark .syntekpro-admin-fab.theme-professional {
            background: #f8f9fa;
            color: #1a1a2e;
        }
        
        /* Theme: Square */
        .syntekpro-admin-fab.theme-square {
            background: #444;
            color: #fff;
            border: none;
            border-radius: 4px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }
        body.syntekpro-admin-dark .syntekpro-admin-fab.theme-square {
            background: #ddd;
            color: #333;
        }
        
        /* Theme: Pill */
        .syntekpro-admin-fab.theme-pill {
            background: #5e60ce;
            color: #fff;
            border: none;
            border-radius: 25px;
            width: 70px;
            box-shadow: 0 4px 12px rgba(94, 96, 206, 0.4);
        }
        body.syntekpro-admin-dark .syntekpro-admin-fab.theme-pill {
            background: #80ed99;
            color: #333;
        }
        
        /* Theme: Hexagon */
        .syntekpro-admin-fab.theme-hexagon {
            background: #ff6b6b;
            color: #fff;
            border: none;
            clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
            box-shadow: 0 4px 12px rgba(255, 107, 107, 0.4);
        }
        body.syntekpro-admin-dark .syntekpro-admin-fab.theme-hexagon {
            background: #51cf66;
        }
        
        /* Theme: Diamond */
        .syntekpro-admin-fab.theme-diamond {
            background: #4ecdc4;
            color: #fff;
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(78, 205, 196, 0.4);
        }
        body.syntekpro-admin-dark .syntekpro-admin-fab.theme-diamond {
            background: #ffd93d;
            color: #333;
        }
        .syntekpro-admin-fab.theme-diamond:hover {
            transform: scale(1.1) rotate(45deg);
        }
        .syntekpro-admin-fab.theme-diamond span {
            transform: rotate(45deg);
        }
        .syntekpro-admin-fab.theme-diamond:hover span {
            transform: rotate(0deg);
        }
        
        /* Theme: Morphing */
        .syntekpro-admin-fab.theme-morphing {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            color: #fff;
            border: none;
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            box-shadow: 0 4px 15px rgba(250, 112, 154, 0.4);
            animation: morph-shape 3s infinite;
        }
        body.syntekpro-admin-dark .syntekpro-admin-fab.theme-morphing {
            background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);
        }
        @keyframes morph-shape {
            0%, 100% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; }
            25% { border-radius: 58% 42% 75% 25% / 76% 46% 54% 24%; }
            50% { border-radius: 50% 50% 33% 67% / 55% 27% 73% 45%; }
            75% { border-radius: 33% 67% 58% 42% / 63% 68% 32% 37%; }
        }
        
        /* Additional Admin FAB Themes */
        
        /* Theme: Soft Shadow */
        .syntekpro-admin-fab.theme-soft-shadow {
            background: #fff;
            color: #333;
            border: 1px solid #f0f0f0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        body.syntekpro-admin-dark .syntekpro-admin-fab.theme-soft-shadow {
            background: #27272a;
            color: #fff;
            border-color: #3f3f46;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }
        .syntekpro-admin-fab.theme-soft-shadow:hover {
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            transform: translateY(-3px);
        }
        
        /* Theme: Outline */
        .syntekpro-admin-fab.theme-outline {
            background: transparent;
            color: #333;
            border: 3px solid #333;
        }
        body.syntekpro-admin-dark .syntekpro-admin-fab.theme-outline {
            color: #fff;
            border-color: #fff;
        }
        .syntekpro-admin-fab.theme-outline:hover {
            background: rgba(51, 51, 51, 0.1);
            transform: scale(1.15);
        }
        body.syntekpro-admin-dark .syntekpro-admin-fab.theme-outline:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        /* Theme: Floating */
        .syntekpro-admin-fab.theme-floating {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3), 0 4px 10px rgba(0, 0, 0, 0.2);
            transform: translateY(-5px);
        }
        body.syntekpro-admin-dark .syntekpro-admin-fab.theme-floating {
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.5), 0 4px 10px rgba(0, 0, 0, 0.5);
        }
        .syntekpro-admin-fab.theme-floating:hover {
            box-shadow: 0 12px 25px rgba(102, 126, 234, 0.4), 0 6px 12px rgba(0, 0, 0, 0.3);
            transform: translateY(-8px);
        }
        
        /* Theme: Glow */
        .syntekpro-admin-fab.theme-glow {
            background: #00d4ff;
            color: #000;
            border: none;
            box-shadow: 0 0 20px #00d4ff, 0 0 40px #00d4ff, 0 0 60px #00d4ff;
        }
        body.syntekpro-admin-dark .syntekpro-admin-fab.theme-glow {
            background: #ff00ff;
            box-shadow: 0 0 20px #ff00ff, 0 0 40px #ff00ff, 0 0 60px #ff00ff;
        }
        .syntekpro-admin-fab.theme-glow:hover {
            box-shadow: 0 0 30px #00d4ff, 0 0 60px #00d4ff, 0 0 90px #00d4ff;
            transform: scale(1.1);
        }
        
        /* Theme: Brutalist */
        .syntekpro-admin-fab.theme-brutalist {
            background: #1a1a1a;
            color: #fff;
            border: 4px solid #fff;
            box-shadow: none;
            border-radius: 0;
        }
        body.syntekpro-admin-dark .syntekpro-admin-fab.theme-brutalist {
            background: #fff;
            color: #000;
            border-color: #000;
        }
        .syntekpro-admin-fab.theme-brutalist:hover {
            transform: translate(2px, 2px);
            background: #333;
        }
        
        /* Theme: 3D */
        .syntekpro-admin-fab.theme-3d {
            background: linear-gradient(180deg, #4facfe 0%, #00f2fe 100%);
            color: #fff;
            border: 3px solid #0284c7;
            box-shadow: 0 5px 0 #0284c7, 0 10px 20px rgba(0, 0, 0, 0.3);
        }
        body.syntekpro-admin-dark .syntekpro-admin-fab.theme-3d {
            background: linear-gradient(180deg, #ff6b9d 0%, #c44569 100%);
            border-color: #8b2d47;
            box-shadow: 0 5px 0 #8b2d47, 0 10px 20px rgba(0, 0, 0, 0.5);
        }
        .syntekpro-admin-fab.theme-3d:active {
            transform: translate(0, 5px);
            box-shadow: 0 0 0 #0284c7, 0 5px 10px rgba(0, 0, 0, 0.2);
        }
        
        /* Theme: Neon Pulse */
        .syntekpro-admin-fab.theme-neon-pulse {
            background: #0a0e27;
            color: #0ff;
            border: 2px solid #0ff;
            box-shadow: 0 0 10px #0ff;
            animation: admin-neon-pulse 2s infinite;
        }
        body.syntekpro-admin-dark .syntekpro-admin-fab.theme-neon-pulse {
            color: #f0f;
            border-color: #f0f;
            box-shadow: 0 0 10px #f0f;
        }
        .syntekpro-admin-fab.theme-neon-pulse:hover {
            animation-duration: 0.3s;
        }
        @keyframes admin-neon-pulse {
            0%, 100% { box-shadow: 0 0 10px rgba(0, 255, 255, 0.5); }
            50% { box-shadow: 0 0 20px rgba(0, 255, 255, 0.8); }
        }
        
        /* Theme: Aurora */
        .syntekpro-admin-fab.theme-aurora {
            background: linear-gradient(45deg, #00d4ff, #00ff87, #ff00d4, #00d4ff);
            background-size: 300% 300%;
            color: #fff;
            border: none;
            animation: admin-aurora-shift 6s ease infinite;
        }
        body.syntekpro-admin-dark .syntekpro-admin-fab.theme-aurora {
            background: linear-gradient(45deg, #ff006e, #ffbe0b, #06ffa5, #ff006e);
            background-size: 300% 300%;
            animation: admin-aurora-shift 6s ease infinite;
        }
        .syntekpro-admin-fab.theme-aurora:hover {
            animation-duration: 1s;
        }
        @keyframes admin-aurora-shift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        /* Theme: Hologram */
        .syntekpro-admin-fab.theme-hologram {
            background: rgba(0, 255, 255, 0.1);
            color: #0ff;
            border: 2px solid #0ff;
            box-shadow: 0 0 10px #0ff, inset 0 0 10px rgba(0, 255, 255, 0.3);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }
        body.syntekpro-admin-dark .syntekpro-admin-fab.theme-hologram {
            background: rgba(255, 0, 255, 0.1);
            color: #f0f;
            border-color: #f0f;
            box-shadow: 0 0 10px #f0f, inset 0 0 10px rgba(255, 0, 255, 0.3);
        }
        .syntekpro-admin-fab.theme-hologram:hover {
            background: rgba(0, 255, 255, 0.2);
            box-shadow: 0 0 20px #0ff, inset 0 0 20px rgba(0, 255, 255, 0.5);
        }
        
        /* Theme: Vaporwave */
        .syntekpro-admin-fab.theme-vaporwave {
            background: linear-gradient(135deg, #ff006e, #d62839);
            color: #00ff41;
            border: 2px solid #00ff41;
            box-shadow: 0 0 15px rgba(255, 0, 110, 0.5);
        }
        body.syntekpro-admin-dark .syntekpro-admin-fab.theme-vaporwave {
            background: linear-gradient(135deg, #00d9ff, #0a00d9);
            color: #ffff00;
            border-color: #ffff00;
            box-shadow: 0 0 15px rgba(0, 217, 255, 0.5);
        }
        .syntekpro-admin-fab.theme-vaporwave:hover {
            transform: scale(1.15);
            box-shadow: 0 0 25px rgba(255, 0, 110, 0.8);
        }
        
        /* Theme: Aquamorphic */
        .syntekpro-admin-fab.theme-aquamorphic {
            background: linear-gradient(135deg, #0084ff, #00d4ff);
            color: #fff;
            border: none;
            border-radius: 30%;
            box-shadow: 0 8px 30px rgba(0, 132, 255, 0.4);
        }
        body.syntekpro-admin-dark .syntekpro-admin-fab.theme-aquamorphic {
            background: linear-gradient(135deg, #ff1493, #ff69b4);
            box-shadow: 0 8px 30px rgba(255, 20, 147, 0.4);
        }
        .syntekpro-admin-fab.theme-aquamorphic:hover {
            border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%;
            box-shadow: 0 12px 40px rgba(0, 132, 255, 0.6);
        }
        
        /* Theme: Sunset */
        .syntekpro-admin-fab.theme-sunset {
            background: linear-gradient(135deg, #ff6b35, #f7931e, #fdb833);
            color: #fff;
            border: none;
            box-shadow: 0 5px 20px rgba(255, 107, 53, 0.4);
        }
        body.syntekpro-admin-dark .syntekpro-admin-fab.theme-sunset {
            background: linear-gradient(135deg, #1a1a6e, #0f3c5d, #0f5f8f);
            box-shadow: 0 5px 20px rgba(15, 95, 143, 0.5);
        }
        .syntekpro-admin-fab.theme-sunset:hover {
            box-shadow: 0 8px 30px rgba(255, 107, 53, 0.6);
        }
        
        /* Theme: Minimalist */
        .syntekpro-admin-fab.theme-minimalist {
            background: transparent;
            color: #333;
            border: 2px solid #333;
            box-shadow: none;
        }
        body.syntekpro-admin-dark .syntekpro-admin-fab.theme-minimalist {
            color: #fff;
            border-color: #fff;
        }
        .syntekpro-admin-fab.theme-minimalist:hover {
            background: #333;
            color: #fff;
        }
        body.syntekpro-admin-dark .syntekpro-admin-fab.theme-minimalist:hover {
            background: #fff;
            color: #333;
        }
        
        /* Theme: Cyber */
        .syntekpro-admin-fab.theme-cyber {
            background: #000;
            color: #0f0;
            border: 2px solid #0f0;
            box-shadow: 0 0 5px #0f0;
        }
        body.syntekpro-admin-dark .syntekpro-admin-fab.theme-cyber {
            color: #ff00ff;
            border-color: #ff00ff;
            box-shadow: 0 0 5px #ff00ff;
        }
        .syntekpro-admin-fab.theme-cyber:hover {
            box-shadow: 0 0 15px #0f0;
            transform: scale(1.08);
        }
        
        /* Theme: Gemstone */
        .syntekpro-admin-fab.theme-gemstone {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            color: #333;
            border: 3px solid #ff9a56;
            box-shadow: 0 4px 15px rgba(255, 154, 86, 0.3);
        }
        body.syntekpro-admin-dark .syntekpro-admin-fab.theme-gemstone {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border-color: #ffd89b;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.5);
        }
        .syntekpro-admin-fab.theme-gemstone:hover {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 6px 20px rgba(255, 154, 86, 0.4);
        }
        
        /* Theme: Monochrome */
        .syntekpro-admin-fab.theme-monochrome {
            background: #808080;
            color: #fff;
            border: 2px solid #404040;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }
        body.syntekpro-admin-dark .syntekpro-admin-fab.theme-monochrome {
            background: #b0b0b0;
            color: #000;
            border-color: #d0d0d0;
        }
        .syntekpro-admin-fab.theme-monochrome:hover {
            background: #707070;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.4);
        }
        
        /* Theme: Frosted Ice */
        .syntekpro-admin-fab.theme-frosted {
            background: rgba(255, 255, 255, 0.3);
            color: #fff;
            border: 2px solid rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }
        body.syntekpro-admin-dark .syntekpro-admin-fab.theme-frosted {
            background: rgba(0, 0, 0, 0.3);
            color: #fff;
            border-color: rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.5);
        }
        .syntekpro-admin-fab.theme-frosted:hover {
            background: rgba(255, 255, 255, 0.4);
            box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.45);
        }
        
        <?php endif; ?>
    </style>
    <?php
}
add_action('admin_head', 'syntekpro_toggle_menu_icon_css');

/**
 * Admin bar icon (top bar)
 */
function syntekpro_toggle_adminbar_icon($wp_admin_bar) {
    $options = syntekpro_toggle_get_options();
    if (!isset($options['enable_admin_bar_icon']) || $options['enable_admin_bar_icon'] !== '1') {
        return;
    }

    $icon_url = SYNTEKPRO_TOGGLE_PLUGIN_URL . 'assets/img/Syntekpro%20Toggle%20%20icon%20Grey%20New.png';
    $wp_admin_bar->add_node(array(
        'id'    => 'syntekpro-toggle-admin',
        'parent' => 'top-secondary',
        'title' => '<span class="ab-icon" aria-hidden="true"><img src="' . esc_url($icon_url) . '" alt=""/></span><span class="screen-reader-text">Toggle admin dark mode</span>',
        'href'  => '#',
        'meta'  => array(
            'title' => __('Toggle admin dark mode', 'syntekpro-toggle'),
            'class' => 'syntekpro-adminbar-toggle'
        )
    ));
}
add_action('admin_bar_menu', 'syntekpro_toggle_adminbar_icon', 80);

/**
 * Admin UI script (dark toggle + admin bar icon behavior)
 */
function syntekpro_toggle_admin_ui_script() {
    $options = syntekpro_toggle_get_options();
    $custom_admin_icon_url = isset($options['custom_admin_button_icon_url']) ? trim((string) $options['custom_admin_button_icon_url']) : '';
    $show_floating_toggle = isset($options['enable_admin_floating_toggle']) ? $options['enable_admin_floating_toggle'] === '1' : true;
    $should_output = (isset($options['enable_admin_bar_icon']) && $options['enable_admin_bar_icon'] === '1') || (isset($options['enable_admin_dark_mode']) && $options['enable_admin_dark_mode'] === '1');
    if (!$should_output) {
        return;
    }
    $prefers_dark = 'window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches';
    ?>
    <script>
        (function() {
            const storageKey = 'syntekpro-admin-dark-mode';
            const allowDark = <?php echo $options['enable_admin_dark_mode'] === '1' ? 'true' : 'false'; ?>;
            const allowFloatingToggle = <?php echo $show_floating_toggle ? 'true' : 'false'; ?>;
            const prefersDark = <?php echo $prefers_dark; ?>;
            const stored = localStorage.getItem(storageKey);
            let isDark = stored === null ? prefersDark : stored === 'true';

            function apply(state) {
                if (!allowDark) return;
                document.body.classList.toggle('syntekpro-admin-dark', state);
                document.documentElement.classList.toggle('syntekpro-admin-dark', state);
                const barNode = document.getElementById('wp-admin-bar-syntekpro-toggle-admin');
                if (barNode) {
                    barNode.classList.toggle('syntekpro-dark-active', state);
                }
                const fab = document.querySelector('.syntekpro-admin-fab');
                if (fab) {
                    fab.classList.toggle('is-dark', state);
                    fab.setAttribute('aria-pressed', state ? 'true' : 'false');
                    fab.setAttribute('aria-label', state ? 'Switch to Light Mode' : 'Switch to Dark Mode');
                    const sunIcon = fab.querySelector('.syntekpro-icon-sun');
                    const moonIcon = fab.querySelector('.syntekpro-icon-moon');
                    if (sunIcon) {
                        sunIcon.style.display = state ? 'flex' : 'none';
                    }
                    if (moonIcon) {
                        moonIcon.style.display = state ? 'none' : 'flex';
                    }
                }
                document.querySelectorAll('.syntekpro-admin-dark-toggle').forEach(btn => {
                    const textNode = btn.querySelector('.syntekpro-admin-dark-toggle-text');
                    if (!textNode) return;
                    const labelLight = btn.getAttribute('data-label-light') || 'Switch to Light';
                    const labelDark = btn.getAttribute('data-label-dark') || 'Switch to Dark';
                    textNode.textContent = state ? labelLight : labelDark;
                });
            }

            apply(isDark);

            const toggleNode = document.getElementById('wp-admin-bar-syntekpro-toggle-admin');
            if (toggleNode) {
                const link = toggleNode.querySelector('a.ab-item');
                if (link) {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        if (!allowDark) {
                            window.location = '<?php echo esc_js(admin_url('admin.php?page=syntekpro-toggle')); ?>';
                            return;
                        }
                        isDark = !isDark;
                        localStorage.setItem(storageKey, isDark);
                        apply(isDark);
                    });
                }
            }

            // Create floating admin toggle button (switch style)
            if (allowDark && allowFloatingToggle && !document.querySelector('.syntekpro-admin-fab')) {
                const fab = document.createElement('button');
                fab.type = 'button';
                fab.className = 'syntekpro-admin-fab <?php echo !empty($custom_admin_icon_url) ? 'has-custom-icon ' : ''; ?>theme-<?php echo esc_js(isset($options['admin_toggle_theme']) ? $options['admin_toggle_theme'] : 'default'); ?>';
                fab.setAttribute('aria-label', 'Toggle admin dark mode');
                fab.innerHTML = '<?php if (!empty($custom_admin_icon_url)) : ?><span class="syntekpro-icon-custom"><img src="<?php echo esc_js($custom_admin_icon_url); ?>" alt="" width="24" height="24" /></span><?php else : ?><span class="syntekpro-icon-sun"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg></span><span class="syntekpro-icon-moon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg></span><?php endif; ?>';
                fab.addEventListener('click', function(e) {
                    e.preventDefault();
                    isDark = !isDark;
                    localStorage.setItem(storageKey, isDark);
                    apply(isDark);
                });
                document.body.appendChild(fab);
            }

            document.querySelectorAll('.syntekpro-admin-dark-toggle').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (!allowDark) {
                        window.location = '<?php echo esc_js(admin_url('admin.php?page=syntekpro-toggle')); ?>';
                        return;
                    }
                    isDark = !isDark;
                    localStorage.setItem(storageKey, isDark);
                    apply(isDark);
                });
            });
        })();
    </script>
    <?php
}
add_action('admin_footer', 'syntekpro_toggle_admin_ui_script');

/**
 * Add collapsible sections functionality
 */
function syntekpro_toggle_collapsible_sections_script() {
    // Only load on Syntekpro Toggle pages
    $current_page = syntekpro_toggle_get_current_admin_page_slug();
    if (!$current_page || strpos($current_page, 'syntekpro-toggle') !== 0) {
        return;
    }
    ?>
    <style>
        /* Collapsible Sections Styling */
        .form-table th { width: 200px; }
        
        h2.syntekpro-section-title {
            cursor: pointer;
            user-select: none;
            padding: 12px 20px;
            background: #f5f5f5;
            color: #333;
            margin: 25px 0 15px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-left: 4px solid #667eea;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
        }
        
        h2.syntekpro-section-title:hover {
            background: #f0f0f0;
            box-shadow: 0 2px 6px rgba(102, 126, 234, 0.2);
            transform: translateX(2px);
        }
        
        h2.syntekpro-section-title .syntekpro-toggle-indicator {
            font-size: 14px;
            transition: transform 0.3s ease;
            opacity: 0.8;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-left: 8px;
            flex-shrink: 0;
            width: 20px;
            height: 20px;
        }
        
        h2.syntekpro-section-title.collapsed .syntekpro-toggle-indicator::before {
            content: '▸';
        }
        
        h2.syntekpro-section-title:not(.collapsed) .syntekpro-toggle-indicator::before {
            content: '▾';
        }
        
        h2.syntekpro-section-title::before {
            content: attr(data-icon);
            margin-right: 8px;
            font-size: 16px;
            flex-shrink: 0;
        }
        
        .syntekpro-section-content {
            overflow: hidden;
            transition: max-height 0.4s ease-in-out, opacity 0.3s ease;
            max-height: 5000px;
            opacity: 1;
        }
        
        .syntekpro-section-content.collapsed {
            max-height: 0;
            opacity: 0;
        }
        
        /* Emoji sections - style differently */
        h2[data-icon]::before {
            filter: drop-shadow(0 1px 2px rgba(0,0,0,0.2));
        }
    </style>
    <script>
        jQuery(document).ready(function($) {
            // Collapsible section headings - prevent duplicates with Set
            var processedHeadings = new Set();
            
            $('h2, h3').each(function() {
                var $heading = $(this);
                var headingText = $heading.text().trim();
                
                // Skip if already processed, empty, too short, or is nav/already styled
                if (processedHeadings.has(this) || 
                    !headingText || 
                    headingText.length < 4 || 
                    $heading.closest('.nav-tab-wrapper').length ||
                    $heading.hasClass('syntekpro-section-title') ||
                    $heading.hasClass('syntekpro-section-heading')) {
                    return;
                }
                
                processedHeadings.add(this);
                
                // Extract emoji if present at the start
                var emojiMatch = headingText.match(/^([\u{1F300}-\u{1F9FF}])/u);
                var icon = emojiMatch ? emojiMatch[1] + ' ' : '';
                var titleText = emojiMatch ? headingText.substring(emojiMatch[0].length).trim() : headingText;
                
                // Get the content after this heading (up to next heading or form end)
                var $content = $heading.nextUntil('h2, h3, .submit').wrapAll('<div class="syntekpro-section-content"/>').parent();
                
                // Only make it collapsible if there's content
                if ($content.length && $content.children().length > 0) {
                    $heading.addClass('syntekpro-section-title')
                            .attr('data-icon', icon)
                            .html(titleText + '<span class="syntekpro-toggle-indicator"></span>')
                            .css('cursor', 'pointer');
                    
                    // Add click handler - only toggle on indicator click to avoid conflict with links
                    $heading.on('click', function(e) {
                        if ($(e.target).closest('.syntekpro-toggle-indicator').length || e.target.tagName === 'SPAN') {
                            $(this).toggleClass('collapsed');
                            $content.toggleClass('collapsed');
                        }
                    });
                }
            });
            
            // Keyboard accessibility support
            $(document).on('keypress', '.syntekpro-section-title', function(e) {
                if (e.which === 13 || e.which === 32) { // Enter or Space
                    e.preventDefault();
                    $(this).toggleClass('collapsed');
                    $(this).nextUntil('h2, h3, .submit').parent('.syntekpro-section-content').toggleClass('collapsed');
                }
            });
        });
    </script>
    <?php
}
add_action('admin_footer', 'syntekpro_toggle_collapsible_sections_script', 20);

/**
 * Register settings
 */
function syntekpro_toggle_register_settings() {
    register_setting('syntekpro_toggle_settings', 'syntekpro_toggle_options', array(
        'sanitize_callback' => 'syntekpro_toggle_sanitize_options'
    ));
    
    // Frontend Page - General Settings Section
    add_settings_section(
        'syntekpro_toggle_general_section',
        __('General Settings', 'syntekpro-toggle'),
        'syntekpro_toggle_general_section_callback',
        'syntekpro-toggle-frontend-general'
    );
    
    add_settings_field(
        'default_mode',
        __('Default Mode', 'syntekpro-toggle'),
        'syntekpro_toggle_default_mode_callback',
        'syntekpro-toggle-frontend-general',
        'syntekpro_toggle_general_section'
    );
    
    add_settings_field(
        'enable_toggle',
        __('Toggle Button', 'syntekpro-toggle'),
        'syntekpro_toggle_enable_toggle_callback',
        'syntekpro-toggle-frontend-general',
        'syntekpro_toggle_general_section'
    );
    
    add_settings_field(
        'button_position',
        __('Button Position', 'syntekpro-toggle'),
        'syntekpro_toggle_button_position_callback',
        'syntekpro-toggle-frontend-general',
        'syntekpro_toggle_general_section'
    );
    
    add_settings_field(
        'button_size',
        __('Button Size', 'syntekpro-toggle'),
        'syntekpro_toggle_button_size_callback',
        'syntekpro-toggle-frontend-general',
        'syntekpro_toggle_general_section'
    );

    add_settings_field(
        'custom_button_icon_url',
        __('Custom Button Icon', 'syntekpro-toggle'),
        'syntekpro_toggle_custom_button_icon_callback',
        'syntekpro-toggle-frontend-general',
        'syntekpro_toggle_general_section'
    );
    
    add_settings_field(
        'toggle_theme',
        __('Toggle Button Theme', 'syntekpro-toggle'),
        'syntekpro_toggle_theme_callback',
        'syntekpro-toggle-frontend-general',
        'syntekpro_toggle_general_section'
    );
    
    add_settings_field(
        'button_shape',
        __('Button Shape', 'syntekpro-toggle'),
        'syntekpro_toggle_button_shape_callback',
        'syntekpro-toggle-frontend-general',
        'syntekpro_toggle_general_section'
    );
    
    add_settings_field(
        'button_animation',
        __('Button Animation', 'syntekpro-toggle'),
        'syntekpro_toggle_button_animation_callback',
        'syntekpro-toggle-frontend-general',
        'syntekpro_toggle_general_section'
    );
    
    add_settings_field(
        'button_bg_style',
        __('Button Background Style', 'syntekpro-toggle'),
        'syntekpro_toggle_button_bg_style_callback',
        'syntekpro-toggle-frontend-general',
        'syntekpro_toggle_general_section'
    );
    
    // Frontend Page - Color Scheme Section
    add_settings_section(
        'syntekpro_toggle_color_scheme_section',
        __('🎨 Dark Mode Color Scheme', 'syntekpro-toggle'),
        'syntekpro_toggle_color_scheme_section_callback',
        'syntekpro-toggle-frontend-colors'
    );
    
    add_settings_field(
        'color_scheme_mode',
        __('Color Scheme Mode', 'syntekpro-toggle'),
        'syntekpro_toggle_color_scheme_mode_callback',
        'syntekpro-toggle-frontend-colors',
        'syntekpro_toggle_color_scheme_section'
    );
    
    add_settings_field(
        'color_preset',
        __('Color Preset', 'syntekpro-toggle'),
        'syntekpro_toggle_color_preset_callback',
        'syntekpro-toggle-frontend-colors',
        'syntekpro_toggle_color_scheme_section'
    );
    
    add_settings_field(
        'bg_color',
        __('Background Color', 'syntekpro-toggle'),
        'syntekpro_toggle_bg_color_callback',
        'syntekpro-toggle-frontend-colors',
        'syntekpro_toggle_color_scheme_section'
    );
    
    add_settings_field(
        'text_color',
        __('Text Color', 'syntekpro-toggle'),
        'syntekpro_toggle_text_color_callback',
        'syntekpro-toggle-frontend-colors',
        'syntekpro_toggle_color_scheme_section'
    );
    
    add_settings_field(
        'link_color',
        __('Link Color', 'syntekpro-toggle'),
        'syntekpro_toggle_link_color_callback',
        'syntekpro-toggle-frontend-colors',
        'syntekpro_toggle_color_scheme_section'
    );
    
    add_settings_field(
        'secondary_bg_color',
        __('Secondary Background', 'syntekpro-toggle'),
        'syntekpro_toggle_secondary_bg_callback',
        'syntekpro-toggle-frontend-colors',
        'syntekpro_toggle_color_scheme_section'
    );
    
    // Frontend Page - Color Adjustments Section
    add_settings_section(
        'syntekpro_toggle_color_adjustments_section',
        __('🎚️ Color Adjustments', 'syntekpro-toggle'),
        'syntekpro_toggle_color_adjustments_section_callback',
        'syntekpro-toggle-frontend-adjustments'
    );
    
    add_settings_field(
        'brightness',
        __('☀️ Brightness', 'syntekpro-toggle'),
        'syntekpro_toggle_brightness_callback',
        'syntekpro-toggle-frontend-adjustments',
        'syntekpro_toggle_color_adjustments_section'
    );
    
    add_settings_field(
        'contrast',
        __('🔲 Contrast', 'syntekpro-toggle'),
        'syntekpro_toggle_contrast_callback',
        'syntekpro-toggle-frontend-adjustments',
        'syntekpro_toggle_color_adjustments_section'
    );
    
    add_settings_field(
        'sepia',
        __('📜 Sepia', 'syntekpro-toggle'),
        'syntekpro_toggle_sepia_callback',
        'syntekpro-toggle-frontend-adjustments',
        'syntekpro_toggle_color_adjustments_section'
    );
    
    add_settings_field(
        'grayscale',
        __('⚫ Grayscale', 'syntekpro-toggle'),
        'syntekpro_toggle_grayscale_callback',
        'syntekpro-toggle-frontend-adjustments',
        'syntekpro_toggle_color_adjustments_section'
    );
    
    // Images Settings Section
    add_settings_section(
        'syntekpro_toggle_images_section',
        __('🖼️ Images Settings', 'syntekpro-toggle'),
        'syntekpro_toggle_images_section_callback',
        'syntekpro-toggle-frontend-images'
    );
    
    add_settings_field(
        'enable_image_filter',
        __('Enable Image Filters', 'syntekpro-toggle'),
        'syntekpro_toggle_enable_image_filter_callback',
        'syntekpro-toggle-frontend-images',
        'syntekpro_toggle_images_section'
    );
    
    add_settings_field(
        'image_brightness',
        __('Image Brightness', 'syntekpro-toggle'),
        'syntekpro_toggle_image_brightness_callback',
        'syntekpro-toggle-frontend-images',
        'syntekpro_toggle_images_section'
    );
    
    add_settings_field(
        'image_contrast',
        __('Image Contrast', 'syntekpro-toggle'),
        'syntekpro_toggle_image_contrast_callback',
        'syntekpro-toggle-frontend-images',
        'syntekpro_toggle_images_section'
    );
    
    // Videos Settings Section
    add_settings_section(
        'syntekpro_toggle_videos_section',
        __('🎬 Videos Settings', 'syntekpro-toggle'),
        'syntekpro_toggle_videos_section_callback',
        'syntekpro-toggle-frontend-videos'
    );
    
    add_settings_field(
        'enable_video_filter',
        __('Enable Video Filters', 'syntekpro-toggle'),
        'syntekpro_toggle_enable_video_filter_callback',
        'syntekpro-toggle-frontend-videos',
        'syntekpro_toggle_videos_section'
    );
    
    add_settings_field(
        'video_brightness',
        __('Video Brightness', 'syntekpro-toggle'),
        'syntekpro_toggle_video_brightness_callback',
        'syntekpro-toggle-frontend-videos',
        'syntekpro_toggle_videos_section'
    );
    
    add_settings_field(
        'video_contrast',
        __('Video Contrast', 'syntekpro-toggle'),
        'syntekpro_toggle_video_contrast_callback',
        'syntekpro-toggle-frontend-videos',
        'syntekpro_toggle_videos_section'
    );
    
    // Slides Settings Section
    add_settings_section(
        'syntekpro_toggle_slides_section',
        __('📊 Slides Settings', 'syntekpro-toggle'),
        'syntekpro_toggle_slides_section_callback',
        'syntekpro-toggle-frontend-slides'
    );
    
    add_settings_field(
        'enable_slide_filter',
        __('Enable Slide Filters', 'syntekpro-toggle'),
        'syntekpro_toggle_enable_slide_filter_callback',
        'syntekpro-toggle-frontend-slides',
        'syntekpro_toggle_slides_section'
    );
    
    add_settings_field(
        'slide_brightness',
        __('Slide Brightness', 'syntekpro-toggle'),
        'syntekpro_toggle_slide_brightness_callback',
        'syntekpro-toggle-frontend-slides',
        'syntekpro_toggle_slides_section'
    );
    
    add_settings_field(
        'slide_invert',
        __('Slide Invert Colors', 'syntekpro-toggle'),
        'syntekpro_toggle_slide_invert_callback',
        'syntekpro-toggle-frontend-slides',
        'syntekpro_toggle_slides_section'
    );
    
    // Admin UI Page - Admin UI Section
    add_settings_section(
        'syntekpro_toggle_admin_ui_section',
        __('Admin UI Settings', 'syntekpro-toggle'),
        'syntekpro_toggle_admin_ui_section_callback',
        'syntekpro-toggle-admin-ui'
    );

    add_settings_field(
        'enable_admin_dark_mode',
        __('Admin Dark Mode', 'syntekpro-toggle'),
        'syntekpro_toggle_admin_dark_mode_callback',
        'syntekpro-toggle-admin-ui',
        'syntekpro_toggle_admin_ui_section'
    );

    add_settings_field(
        'enable_admin_bar_icon',
        __('Top Bar Icon', 'syntekpro-toggle'),
        'syntekpro_toggle_admin_bar_icon_callback',
        'syntekpro-toggle-admin-ui',
        'syntekpro_toggle_admin_ui_section'
    );

    add_settings_field(
        'enable_admin_floating_toggle',
        __('Floating Toggle Button', 'syntekpro-toggle'),
        'syntekpro_toggle_admin_floating_toggle_callback',
        'syntekpro-toggle-admin-ui',
        'syntekpro_toggle_admin_ui_section'
    );

    add_settings_field(
        'enable_dashboard_widget',
        __('Dashboard Widget', 'syntekpro-toggle'),
        'syntekpro_toggle_admin_dashboard_widget_callback',
        'syntekpro-toggle-admin-ui',
        'syntekpro_toggle_admin_ui_section'
    );
    
    add_settings_field(
        'admin_toggle_theme',
        __('Admin Toggle Theme', 'syntekpro-toggle'),
        'syntekpro_toggle_admin_theme_callback',
        'syntekpro-toggle-admin-ui',
        'syntekpro_toggle_admin_ui_section'
    );

    add_settings_field(
        'custom_admin_button_icon_url',
        __('Custom Admin Button Icon', 'syntekpro-toggle'),
        'syntekpro_toggle_custom_admin_button_icon_callback',
        'syntekpro-toggle-admin-ui',
        'syntekpro_toggle_admin_ui_section'
    );
    
    // Admin UI Page - Color Scheme Section
    add_settings_section(
        'syntekpro_toggle_admin_color_section',
        __('🎨 Admin UI Color Scheme', 'syntekpro-toggle'),
        'syntekpro_toggle_admin_color_section_callback',
        'syntekpro-toggle-admin-color'
    );
    
    add_settings_field(
        'admin_color_scheme_mode',
        __('Color Mode', 'syntekpro-toggle'),
        'syntekpro_toggle_admin_color_scheme_mode_callback',
        'syntekpro-toggle-admin-color',
        'syntekpro_toggle_admin_color_section'
    );
    
    add_settings_field(
        'admin_color_preset',
        __('Color Presets', 'syntekpro-toggle'),
        'syntekpro_toggle_admin_color_preset_callback',
        'syntekpro-toggle-admin-color',
        'syntekpro_toggle_admin_color_section'
    );
    
    add_settings_field(
        'admin_bg_color',
        __('Admin Background', 'syntekpro-toggle'),
        'syntekpro_toggle_admin_bg_color_callback',
        'syntekpro-toggle-admin-color',
        'syntekpro_toggle_admin_color_section'
    );

    add_settings_field(
        'admin_text_color',
        __('Admin Text', 'syntekpro-toggle'),
        'syntekpro_toggle_admin_text_color_callback',
        'syntekpro-toggle-admin-color',
        'syntekpro_toggle_admin_color_section'
    );

    add_settings_field(
        'admin_accent_color',
        __('Admin Accent', 'syntekpro-toggle'),
        'syntekpro_toggle_admin_accent_color_callback',
        'syntekpro-toggle-admin-color',
        'syntekpro_toggle_admin_color_section'
    );

    add_settings_field(
        'admin_surface_color',
        __('Admin Surface', 'syntekpro-toggle'),
        'syntekpro_toggle_admin_surface_color_callback',
        'syntekpro-toggle-admin-color',
        'syntekpro_toggle_admin_color_section'
    );

    add_settings_field(
        'admin_border_color',
        __('Admin Border', 'syntekpro-toggle'),
        'syntekpro_toggle_admin_border_color_callback',
        'syntekpro-toggle-admin-color',
        'syntekpro_toggle_admin_color_section'
    );

    add_settings_field(
        'admin_link_color',
        __('Admin Link', 'syntekpro-toggle'),
        'syntekpro_toggle_admin_link_color_callback',
        'syntekpro-toggle-admin-color',
        'syntekpro_toggle_admin_color_section'
    );

    add_settings_field(
        'admin_link_hover_color',
        __('Admin Link Hover', 'syntekpro-toggle'),
        'syntekpro_toggle_admin_link_hover_color_callback',
        'syntekpro-toggle-admin-color',
        'syntekpro_toggle_admin_color_section'
    );
    
    // Options Page - Display Rules Section
    add_settings_section(
        'syntekpro_toggle_display_section',
        __('Display Rules', 'syntekpro-toggle'),
        'syntekpro_toggle_display_section_callback',
        'syntekpro-toggle-frontend-advanced'
    );

    add_settings_field(
        'display_mode',
        __('Display Mode', 'syntekpro-toggle'),
        'syntekpro_toggle_display_mode_callback',
        'syntekpro-toggle-frontend-advanced',
        'syntekpro_toggle_display_section'
    );

    add_settings_field(
        'display_post_types',
        __('Post Types', 'syntekpro-toggle'),
        'syntekpro_toggle_display_post_types_callback',
        'syntekpro-toggle-frontend-advanced',
        'syntekpro_toggle_display_section'
    );

    add_settings_field(
        'display_pages',
        __('Page IDs', 'syntekpro-toggle'),
        'syntekpro_toggle_display_pages_callback',
        'syntekpro-toggle-frontend-advanced',
        'syntekpro_toggle_display_section'
    );

    add_settings_field(
        'display_categories',
        __('Category IDs', 'syntekpro-toggle'),
        'syntekpro_toggle_display_categories_callback',
        'syntekpro-toggle-frontend-advanced',
        'syntekpro_toggle_display_section'
    );

    add_settings_field(
        'display_tags',
        __('Tag IDs', 'syntekpro-toggle'),
        'syntekpro_toggle_display_tags_callback',
        'syntekpro-toggle-frontend-advanced',
        'syntekpro_toggle_display_section'
    );

    add_settings_field(
        'exclude_special_pages',
        __('Exclude Special Pages', 'syntekpro-toggle'),
        'syntekpro_toggle_exclude_special_pages_callback',
        'syntekpro-toggle-frontend-advanced',
        'syntekpro_toggle_display_section'
    );

    // Options Page - User Targeting Section
    add_settings_section(
        'syntekpro_toggle_user_targeting_section',
        __('User Targeting', 'syntekpro-toggle'),
        'syntekpro_toggle_user_targeting_section_callback',
        'syntekpro-toggle-frontend-advanced'
    );

    add_settings_field(
        'user_visibility',
        __('Visibility', 'syntekpro-toggle'),
        'syntekpro_toggle_user_visibility_callback',
        'syntekpro-toggle-frontend-advanced',
        'syntekpro_toggle_user_targeting_section'
    );

    add_settings_field(
        'user_roles',
        __('Allowed Roles', 'syntekpro-toggle'),
        'syntekpro_toggle_user_roles_callback',
        'syntekpro-toggle-frontend-advanced',
        'syntekpro_toggle_user_targeting_section'
    );

    // Options Page - Schedule Section
    add_settings_section(
        'syntekpro_toggle_schedule_section',
        __('Schedule', 'syntekpro-toggle'),
        'syntekpro_toggle_schedule_section_callback',
        'syntekpro-toggle-frontend-advanced'
    );

    add_settings_field(
        'schedule_enabled',
        __('Enable Schedule', 'syntekpro-toggle'),
        'syntekpro_toggle_schedule_enabled_callback',
        'syntekpro-toggle-frontend-advanced',
        'syntekpro_toggle_schedule_section'
    );

    add_settings_field(
        'schedule_days',
        __('Days', 'syntekpro-toggle'),
        'syntekpro_toggle_schedule_days_callback',
        'syntekpro-toggle-frontend-advanced',
        'syntekpro_toggle_schedule_section'
    );

    add_settings_field(
        'schedule_start',
        __('Start Time', 'syntekpro-toggle'),
        'syntekpro_toggle_schedule_start_callback',
        'syntekpro-toggle-frontend-advanced',
        'syntekpro_toggle_schedule_section'
    );

    add_settings_field(
        'schedule_end',
        __('End Time', 'syntekpro-toggle'),
        'syntekpro_toggle_schedule_end_callback',
        'syntekpro-toggle-frontend-advanced',
        'syntekpro_toggle_schedule_section'
    );

    // Options Page - Behavior Section
    add_settings_section(
        'syntekpro_toggle_behavior_section',
        __('Behavior', 'syntekpro-toggle'),
        'syntekpro_toggle_behavior_section_callback',
        'syntekpro-toggle-frontend-advanced'
    );

    add_settings_field(
        'auto_mode_source',
        __('Auto Mode Source', 'syntekpro-toggle'),
        'syntekpro_toggle_auto_mode_source_callback',
        'syntekpro-toggle-frontend-advanced',
        'syntekpro_toggle_behavior_section'
    );

    add_settings_field(
        'auto_time_start',
        __('Auto Start Time', 'syntekpro-toggle'),
        'syntekpro_toggle_auto_time_start_callback',
        'syntekpro-toggle-frontend-advanced',
        'syntekpro_toggle_behavior_section'
    );

    add_settings_field(
        'auto_time_end',
        __('Auto End Time', 'syntekpro-toggle'),
        'syntekpro_toggle_auto_time_end_callback',
        'syntekpro-toggle-frontend-advanced',
        'syntekpro_toggle_behavior_section'
    );

    add_settings_field(
        'auto_apply_on_load',
        __('Apply Auto On Load', 'syntekpro-toggle'),
        'syntekpro_toggle_auto_apply_on_load_callback',
        'syntekpro-toggle-frontend-advanced',
        'syntekpro_toggle_behavior_section'
    );

    add_settings_field(
        'auto_listen_os',
        __('Listen To OS Changes', 'syntekpro-toggle'),
        'syntekpro_toggle_auto_listen_os_callback',
        'syntekpro-toggle-frontend-advanced',
        'syntekpro_toggle_behavior_section'
    );

    // Options Page - Storage & Privacy Section
    add_settings_section(
        'syntekpro_toggle_storage_section',
        __('Storage & Privacy', 'syntekpro-toggle'),
        'syntekpro_toggle_storage_section_callback',
        'syntekpro-toggle-frontend-advanced'
    );

    add_settings_field(
        'storage_mode',
        __('Storage Mode', 'syntekpro-toggle'),
        'syntekpro_toggle_storage_mode_callback',
        'syntekpro-toggle-frontend-advanced',
        'syntekpro_toggle_storage_section'
    );

    add_settings_field(
        'storage_days',
        __('Cookie Lifetime (Days)', 'syntekpro-toggle'),
        'syntekpro_toggle_storage_days_callback',
        'syntekpro-toggle-frontend-advanced',
        'syntekpro_toggle_storage_section'
    );

    add_settings_field(
        'reset_storage',
        __('Reset Stored Preferences', 'syntekpro-toggle'),
        'syntekpro_toggle_reset_storage_callback',
        'syntekpro-toggle-frontend-advanced',
        'syntekpro_toggle_storage_section'
    );

    // Options Page - Animations Section
    add_settings_section(
        'syntekpro_toggle_animation_section',
        __('Animations', 'syntekpro-toggle'),
        'syntekpro_toggle_animation_section_callback',
        'syntekpro-toggle-frontend-advanced'
    );

    add_settings_field(
        'enable_animations',
        __('Enable Animations', 'syntekpro-toggle'),
        'syntekpro_toggle_enable_animations_callback',
        'syntekpro-toggle-frontend-advanced',
        'syntekpro_toggle_animation_section'
    );

    add_settings_field(
        'toggle_animation_speed',
        __('Toggle Animation Speed', 'syntekpro-toggle'),
        'syntekpro_toggle_toggle_animation_speed_callback',
        'syntekpro-toggle-frontend-advanced',
        'syntekpro_toggle_animation_section'
    );

    // Options Page - Accessibility Section
    add_settings_section(
        'syntekpro_toggle_accessibility_section',
        __('Accessibility', 'syntekpro-toggle'),
        'syntekpro_toggle_accessibility_section_callback',
        'syntekpro-toggle-frontend-advanced'
    );

    add_settings_field(
        'respect_reduced_motion',
        __('Respect Reduced Motion', 'syntekpro-toggle'),
        'syntekpro_toggle_respect_reduced_motion_callback',
        'syntekpro-toggle-frontend-advanced',
        'syntekpro_toggle_accessibility_section'
    );

    add_settings_field(
        'force_high_contrast',
        __('Force High Contrast', 'syntekpro-toggle'),
        'syntekpro_toggle_force_high_contrast_callback',
        'syntekpro-toggle-frontend-advanced',
        'syntekpro_toggle_accessibility_section'
    );

    add_settings_field(
        'focus_ring_style',
        __('Focus Ring Style', 'syntekpro-toggle'),
        'syntekpro_toggle_focus_ring_style_callback',
        'syntekpro-toggle-frontend-advanced',
        'syntekpro_toggle_accessibility_section'
    );

    // Options Page - Integrations Section
    add_settings_section(
        'syntekpro_toggle_integrations_section',
        __('Integrations', 'syntekpro-toggle'),
        'syntekpro_toggle_integrations_section_callback',
        'syntekpro-toggle-frontend-advanced'
    );

    add_settings_field(
        'enable_shortcode',
        __('Shortcode Output', 'syntekpro-toggle'),
        'syntekpro_toggle_enable_shortcode_callback',
        'syntekpro-toggle-frontend-advanced',
        'syntekpro_toggle_integrations_section'
    );

    add_settings_field(
        'enable_widget',
        __('Widget Output', 'syntekpro-toggle'),
        'syntekpro_toggle_enable_widget_callback',
        'syntekpro-toggle-frontend-advanced',
        'syntekpro_toggle_integrations_section'
    );

    // Options Page - Theme Overrides Section
    add_settings_section(
        'syntekpro_toggle_theme_override_section',
        __('Theme Overrides', 'syntekpro-toggle'),
        'syntekpro_toggle_theme_override_section_callback',
        'syntekpro-toggle-frontend-advanced'
    );

    add_settings_field(
        'excluded_themes',
        __('Disable On Themes', 'syntekpro-toggle'),
        'syntekpro_toggle_excluded_themes_callback',
        'syntekpro-toggle-frontend-advanced',
        'syntekpro_toggle_theme_override_section'
    );

    add_settings_field(
        'exclude_selectors',
        __('Exclude Selectors', 'syntekpro-toggle'),
        'syntekpro_toggle_exclude_selectors_callback',
        'syntekpro-toggle-frontend-advanced',
        'syntekpro_toggle_theme_override_section'
    );

    // Options Page - Performance Section
    add_settings_section(
        'syntekpro_toggle_performance_section',
        __('Performance', 'syntekpro-toggle'),
        'syntekpro_toggle_performance_section_callback',
        'syntekpro-toggle-frontend-advanced'
    );

    add_settings_field(
        'analytics_debounce_ms',
        __('Analytics Debounce (ms)', 'syntekpro-toggle'),
        'syntekpro_toggle_analytics_debounce_callback',
        'syntekpro-toggle-frontend-advanced',
        'syntekpro_toggle_performance_section'
    );

    add_settings_field(
        'analytics_batch',
        __('Batch Analytics', 'syntekpro-toggle'),
        'syntekpro_toggle_analytics_batch_callback',
        'syntekpro-toggle-frontend-advanced',
        'syntekpro_toggle_performance_section'
    );

    add_settings_field(
        'analytics_batch_interval',
        __('Batch Interval (ms)', 'syntekpro-toggle'),
        'syntekpro_toggle_analytics_batch_interval_callback',
        'syntekpro-toggle-frontend-advanced',
        'syntekpro_toggle_performance_section'
    );

    add_settings_field(
        'analytics_batch_max',
        __('Batch Size', 'syntekpro-toggle'),
        'syntekpro_toggle_analytics_batch_max_callback',
        'syntekpro-toggle-frontend-advanced',
        'syntekpro_toggle_performance_section'
    );

    add_settings_field(
        'analytics_pageview_once_session',
        __('Page View Once Per Session', 'syntekpro-toggle'),
        'syntekpro_toggle_analytics_pageview_once_session_callback',
        'syntekpro-toggle-frontend-advanced',
        'syntekpro_toggle_performance_section'
    );

    // Options Page - Debug Section
    add_settings_section(
        'syntekpro_toggle_debug_section',
        __('Debug', 'syntekpro-toggle'),
        'syntekpro_toggle_debug_section_callback',
        'syntekpro-toggle-frontend-advanced'
    );

    add_settings_field(
        'debug_mode',
        __('Debug Mode', 'syntekpro-toggle'),
        'syntekpro_toggle_debug_mode_callback',
        'syntekpro-toggle-frontend-advanced',
        'syntekpro_toggle_debug_section'
    );

    // Advanced Settings Section - Now MERGED INTO FRONTEND PAGE
    add_settings_section(
        'syntekpro_toggle_advanced_section',
        __('Advanced Settings', 'syntekpro-toggle'),
        'syntekpro_toggle_advanced_section_callback',
        'syntekpro-toggle-frontend-advanced'
    );
    
    add_settings_field(
        'custom_css',
        __('Custom CSS', 'syntekpro-toggle'),
        'syntekpro_toggle_custom_css_callback',
        'syntekpro-toggle-frontend-advanced',
        'syntekpro_toggle_advanced_section'
    );
    
    add_settings_field(
        'transition_speed',
        __('Transition Speed', 'syntekpro-toggle'),
        'syntekpro_toggle_transition_speed_callback',
        'syntekpro-toggle-frontend-advanced',
        'syntekpro_toggle_advanced_section'
    );
    
    // Analytics Page - Analytics Settings Section
    add_settings_section(
        'syntekpro_toggle_analytics_section',
        '',
        'syntekpro_toggle_analytics_section_callback',
        'syntekpro-toggle-analytics-settings'
    );
    
    add_settings_field(
        'enable_analytics',
        __('Enable Analytics', 'syntekpro-toggle'),
        'syntekpro_toggle_enable_analytics_callback',
        'syntekpro-toggle-analytics-settings',
        'syntekpro_toggle_analytics_section'
    );
    
    add_settings_field(
        'analytics_track_toggles',
        __('Track Toggle Clicks', 'syntekpro-toggle'),
        'syntekpro_toggle_analytics_track_toggles_callback',
        'syntekpro-toggle-analytics-settings',
        'syntekpro_toggle_analytics_section'
    );
    
    add_settings_field(
        'analytics_track_pageviews',
        __('Track Page Views', 'syntekpro-toggle'),
        'syntekpro_toggle_analytics_track_pageviews_callback',
        'syntekpro-toggle-analytics-settings',
        'syntekpro_toggle_analytics_section'
    );
    
    add_settings_field(
        'analytics_track_modes',
        __('Track Mode Changes', 'syntekpro-toggle'),
        'syntekpro_toggle_analytics_track_modes_callback',
        'syntekpro-toggle-analytics-settings',
        'syntekpro_toggle_analytics_section'
    );

    // -------------------------------------------------------------------------
    // Media Settings Page – Advanced Media Options
    // -------------------------------------------------------------------------
    add_settings_section(
        'syntekpro_toggle_media_advanced_section',
        __('Advanced Media Options', 'syntekpro-toggle'),
        'syntekpro_toggle_media_advanced_section_callback',
        'syntekpro-toggle-frontend-media-advanced'
    );

    add_settings_field(
        'image_selector',
        __('Custom Image Selector', 'syntekpro-toggle'),
        'syntekpro_toggle_image_selector_callback',
        'syntekpro-toggle-frontend-media-advanced',
        'syntekpro_toggle_media_advanced_section'
    );

    add_settings_field(
        'image_exclude_class',
        __('Exclude Image Classes', 'syntekpro-toggle'),
        'syntekpro_toggle_image_exclude_class_callback',
        'syntekpro-toggle-frontend-media-advanced',
        'syntekpro_toggle_media_advanced_section'
    );

    add_settings_field(
        'enable_bg_image_filter',
        __('Filter Background Images', 'syntekpro-toggle'),
        'syntekpro_toggle_enable_bg_image_filter_callback',
        'syntekpro-toggle-frontend-media-advanced',
        'syntekpro_toggle_media_advanced_section'
    );

    add_settings_field(
        'enable_svg_filter',
        __('Filter Inline SVGs', 'syntekpro-toggle'),
        'syntekpro_toggle_enable_svg_filter_callback',
        'syntekpro-toggle-frontend-media-advanced',
        'syntekpro_toggle_media_advanced_section'
    );

    add_settings_field(
        'video_selector',
        __('Custom Video Selector', 'syntekpro-toggle'),
        'syntekpro_toggle_video_selector_callback',
        'syntekpro-toggle-frontend-media-advanced',
        'syntekpro_toggle_media_advanced_section'
    );

    add_settings_field(
        'enable_iframe_filter',
        __('Filter Iframes / Embeds', 'syntekpro-toggle'),
        'syntekpro_toggle_enable_iframe_filter_callback',
        'syntekpro-toggle-frontend-media-advanced',
        'syntekpro_toggle_media_advanced_section'
    );

    add_settings_field(
        'iframe_brightness',
        __('Iframe Brightness', 'syntekpro-toggle'),
        'syntekpro_toggle_iframe_brightness_callback',
        'syntekpro-toggle-frontend-media-advanced',
        'syntekpro_toggle_media_advanced_section'
    );

    add_settings_field(
        'slide_selector',
        __('Custom Slide Selector', 'syntekpro-toggle'),
        'syntekpro_toggle_slide_selector_callback',
        'syntekpro-toggle-frontend-media-advanced',
        'syntekpro_toggle_media_advanced_section'
    );

    add_settings_field(
        'enable_slider_overlay',
        __('Enable Slider Dark Overlay', 'syntekpro-toggle'),
        'syntekpro_toggle_enable_slider_overlay_callback',
        'syntekpro-toggle-frontend-media-advanced',
        'syntekpro_toggle_media_advanced_section'
    );

    add_settings_field(
        'slider_overlay_color',
        __('Slider Overlay Color', 'syntekpro-toggle'),
        'syntekpro_toggle_slider_overlay_color_callback',
        'syntekpro-toggle-frontend-media-advanced',
        'syntekpro_toggle_media_advanced_section'
    );

    add_settings_field(
        'slider_overlay_opacity',
        __('Slider Overlay Opacity', 'syntekpro-toggle'),
        'syntekpro_toggle_slider_overlay_opacity_callback',
        'syntekpro-toggle-frontend-media-advanced',
        'syntekpro_toggle_media_advanced_section'
    );
}
add_action('admin_init', 'syntekpro_toggle_register_settings');

/**
 * Get default options
 */
function syntekpro_toggle_get_default_options() {
    return array(
        'default_mode' => 'auto',
        'enable_toggle' => '1',
        'button_position' => 'bottom-right',
        'button_size' => '50',
        'custom_button_icon_url' => '',
        'toggle_theme' => 'default',
        'button_shape' => 'default',
        'button_animation' => 'none',
        'button_bg_style' => 'solid',
        'color_scheme_mode' => 'preset',
        'color_preset' => 'default',
        'bg_color' => '#1a1a1a',
        'text_color' => '#ffffff',
        'link_color' => '#6ea8fe',
        'secondary_bg_color' => '#2d2d2d',
        'brightness' => '100',
        'contrast' => '100',
        'sepia' => '0',
        'grayscale' => '0',
        'enable_image_filter' => '1',
        'image_brightness' => '100',
        'image_contrast' => '100',
        'enable_video_filter' => '1',
        'video_brightness' => '100',
        'video_contrast' => '100',
        'enable_slide_filter' => '1',
        'slide_brightness' => '100',
        'slide_invert' => '0',
        'custom_css' => '',
        'transition_speed' => '0.3',
        'display_mode' => 'all',
        'display_post_types' => array(),
        'display_pages' => '',
        'display_categories' => '',
        'display_tags' => '',
        'exclude_special_pages' => '0',
        'user_visibility' => 'all',
        'user_roles' => array(),
        'schedule_enabled' => '0',
        'schedule_days' => array('mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'),
        'schedule_start' => '19:00',
        'schedule_end' => '07:00',
        'auto_mode_source' => 'os',
        'auto_time_start' => '19:00',
        'auto_time_end' => '07:00',
        'auto_apply_on_load' => '1',
        'auto_listen_os' => '1',
        'storage_mode' => 'local',
        'storage_days' => '365',
        'storage_version' => '1',
        'reset_storage' => '0',
        'enable_animations' => '1',
        'toggle_animation_speed' => '0.3',
        'respect_reduced_motion' => '1',
        'force_high_contrast' => '0',
        'focus_ring_style' => 'default',
        'enable_shortcode' => '1',
        'enable_widget' => '1',
        'excluded_themes' => '',
        'exclude_selectors' => '',
        'analytics_debounce_ms' => '500',
        'analytics_batch' => '0',
        'analytics_batch_interval' => '5000',
        'analytics_batch_max' => '10',
        'analytics_pageview_once_session' => '1',
        'debug_mode' => '0',
        'enable_admin_bar_icon' => '1',
        'enable_admin_floating_toggle' => '1',
        'enable_dashboard_widget' => '1',
        'enable_admin_dark_mode' => '1',
        'admin_toggle_theme' => 'default',
        'custom_admin_button_icon_url' => '',
        'admin_color_scheme_mode' => 'preset',
        'admin_color_preset' => 'default',
        'admin_bg_color' => '#0f1115',
        'admin_text_color' => '#e7e9ee',
        'admin_accent_color' => '#2563eb',
        'admin_surface_color' => '#191e2a',
        'admin_border_color' => '#2a3243',
        'admin_link_color' => '#9fc3ff',
        'admin_link_hover_color' => '#c8dcff',
        'enable_analytics' => '1',
        'analytics_track_toggles' => '1',
        'analytics_track_pageviews' => '1',
        'analytics_track_modes' => '1',
        // Advanced Media Options (new in 1.6.5)
        'image_selector' => '',
        'image_exclude_class' => '',
        'enable_bg_image_filter' => '0',
        'enable_svg_filter' => '0',
        'video_selector' => '',
        'enable_iframe_filter' => '0',
        'iframe_brightness' => '80',
        'slide_selector' => '',
        'enable_slider_overlay' => '0',
        'slider_overlay_color' => '#000000',
        'slider_overlay_opacity' => '0.2'
    );
}

/**
 * Get options with defaults
 */
function syntekpro_toggle_get_options() {
    $defaults = syntekpro_toggle_get_default_options();
    $options = get_option('syntekpro_toggle_options', array());
    return wp_parse_args($options, $defaults);
}

/**
 * Sanitize options
 */
function syntekpro_toggle_sanitize_options($input) {
    // Get existing options to preserve settings not in current form
    $existing = get_option('syntekpro_toggle_options', array());
    $defaults = syntekpro_toggle_get_default_options();
    $existing = wp_parse_args($existing, $defaults);
    
    // Start with existing options
    $sanitized = $existing;
    
    // Only update fields that are present in the input
    if (isset($input['default_mode'])) {
        $sanitized['default_mode'] = sanitize_text_field($input['default_mode']);
    }
    
    // Unchecked checkboxes are absent from POST data, so use the sentinel hidden field
    // to detect whether the general settings form (that contains enable_toggle) was submitted.
    if (isset($input['_enable_toggle_sentinel'])) {
        $sanitized['enable_toggle'] = isset($input['enable_toggle']) ? '1' : '0';
    }
    
    if (isset($input['button_position'])) {
        $sanitized['button_position'] = sanitize_text_field($input['button_position']);
    }
    
    if (isset($input['button_size'])) {
        $sanitized['button_size'] = absint($input['button_size']);
    }

    if (isset($input['custom_button_icon_url'])) {
        $sanitized['custom_button_icon_url'] = esc_url_raw($input['custom_button_icon_url']);
    }
    
    if (isset($input['toggle_theme'])) {
        $theme_value = sanitize_text_field($input['toggle_theme']);
        // Enforce free-tier theme restriction on the server side.
        $free_themes = array('default', 'minimal', 'modern');
        if (!syntekpro_toggle_is_plus() && !in_array($theme_value, $free_themes, true)) {
            $theme_value = 'default'; // Reset to default if user is not on Toggle+.
        }
        $sanitized['toggle_theme'] = $theme_value;
    }
    
    if (isset($input['button_shape'])) {
        $sanitized['button_shape'] = sanitize_text_field($input['button_shape']);
    }
    
    if (isset($input['button_animation'])) {
        $sanitized['button_animation'] = sanitize_text_field($input['button_animation']);
    }
    
    if (isset($input['button_bg_style'])) {
        $sanitized['button_bg_style'] = sanitize_text_field($input['button_bg_style']);
    }
    
    if (isset($input['color_scheme_mode'])) {
        $sanitized['color_scheme_mode'] = sanitize_text_field($input['color_scheme_mode']);
    }
    
    if (isset($input['color_preset'])) {
        $sanitized['color_preset'] = sanitize_text_field($input['color_preset']);
    }
    
    if (isset($input['bg_color'])) {
        $sanitized['bg_color'] = sanitize_hex_color($input['bg_color']);
    }
    
    if (isset($input['text_color'])) {
        $sanitized['text_color'] = sanitize_hex_color($input['text_color']);
    }
    
    if (isset($input['link_color'])) {
        $sanitized['link_color'] = sanitize_hex_color($input['link_color']);
    }
    
    if (isset($input['secondary_bg_color'])) {
        $sanitized['secondary_bg_color'] = sanitize_hex_color($input['secondary_bg_color']);
    }
    
    if (isset($input['brightness'])) {
        $sanitized['brightness'] = max(0, min(200, absint($input['brightness'])));
    }
    
    if (isset($input['contrast'])) {
        $sanitized['contrast'] = max(0, min(200, absint($input['contrast'])));
    }
    
    if (isset($input['sepia'])) {
        $sanitized['sepia'] = max(0, min(100, absint($input['sepia'])));
    }
    
    if (isset($input['grayscale'])) {
        $sanitized['grayscale'] = max(0, min(100, absint($input['grayscale'])));
    }
    
    // Media filter settings - check if form was submitted
    if (array_key_exists('enable_image_filter', $input)) {
        $sanitized['enable_image_filter'] = isset($input['enable_image_filter']) ? '1' : '0';
    }
    
    if (isset($input['image_brightness'])) {
        $sanitized['image_brightness'] = max(50, min(150, absint($input['image_brightness'])));
    }
    
    if (isset($input['image_contrast'])) {
        $sanitized['image_contrast'] = max(50, min(200, absint($input['image_contrast'])));
    }
    
    if (array_key_exists('enable_video_filter', $input)) {
        $sanitized['enable_video_filter'] = isset($input['enable_video_filter']) ? '1' : '0';
    }
    
    if (isset($input['video_brightness'])) {
        $sanitized['video_brightness'] = max(50, min(150, absint($input['video_brightness'])));
    }
    
    if (isset($input['video_contrast'])) {
        $sanitized['video_contrast'] = max(50, min(200, absint($input['video_contrast'])));
    }
    
    if (array_key_exists('enable_slide_filter', $input)) {
        $sanitized['enable_slide_filter'] = isset($input['enable_slide_filter']) ? '1' : '0';
    }
    
    if (isset($input['slide_brightness'])) {
        $sanitized['slide_brightness'] = max(50, min(150, absint($input['slide_brightness'])));
    }
    
    if (array_key_exists('slide_invert', $input)) {
        $sanitized['slide_invert'] = isset($input['slide_invert']) ? '1' : '0';
    }
    
    if (isset($input['custom_css'])) {
        $sanitized['custom_css'] = wp_strip_all_tags($input['custom_css']);
    }
    
    if (isset($input['transition_speed'])) {
        $sanitized['transition_speed'] = floatval($input['transition_speed']);
    }

    if (isset($input['display_mode'])) {
        $allowed_display_modes = array('all', 'include', 'exclude');
        $display_mode = sanitize_text_field($input['display_mode']);
        $sanitized['display_mode'] = in_array($display_mode, $allowed_display_modes, true) ? $display_mode : 'all';
    }

    if (isset($input['display_post_types']) && is_array($input['display_post_types'])) {
        $allowed_post_types = array_keys(get_post_types(array('public' => true), 'names'));
        $post_types = array_map('sanitize_text_field', $input['display_post_types']);
        $sanitized['display_post_types'] = array_values(array_intersect($post_types, $allowed_post_types));
    }

    if (isset($input['display_pages'])) {
        $sanitized['display_pages'] = sanitize_text_field($input['display_pages']);
    }

    if (isset($input['display_categories'])) {
        $sanitized['display_categories'] = sanitize_text_field($input['display_categories']);
    }

    if (isset($input['display_tags'])) {
        $sanitized['display_tags'] = sanitize_text_field($input['display_tags']);
    }

    if (array_key_exists('exclude_special_pages', $input)) {
        $sanitized['exclude_special_pages'] = isset($input['exclude_special_pages']) ? '1' : '0';
    }

    if (isset($input['user_visibility'])) {
        $allowed_visibility = array('all', 'logged_in', 'guests', 'roles');
        $visibility = sanitize_text_field($input['user_visibility']);
        $sanitized['user_visibility'] = in_array($visibility, $allowed_visibility, true) ? $visibility : 'all';
    }

    if (isset($input['user_roles']) && is_array($input['user_roles'])) {
        $allowed_roles = array_keys(get_editable_roles());
        $roles = array_map('sanitize_text_field', $input['user_roles']);
        $sanitized['user_roles'] = array_values(array_intersect($roles, $allowed_roles));
    }

    if (array_key_exists('schedule_enabled', $input)) {
        $sanitized['schedule_enabled'] = isset($input['schedule_enabled']) ? '1' : '0';
    }

    if (isset($input['schedule_days']) && is_array($input['schedule_days'])) {
        $allowed_days = array('mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun');
        $days = array_map('sanitize_text_field', $input['schedule_days']);
        $sanitized['schedule_days'] = array_values(array_intersect($days, $allowed_days));
    }

    if (isset($input['schedule_start'])) {
        $sanitized['schedule_start'] = sanitize_text_field($input['schedule_start']);
    }

    if (isset($input['schedule_end'])) {
        $sanitized['schedule_end'] = sanitize_text_field($input['schedule_end']);
    }

    if (isset($input['auto_mode_source'])) {
        $allowed_sources = array('os', 'time');
        $source = sanitize_text_field($input['auto_mode_source']);
        $sanitized['auto_mode_source'] = in_array($source, $allowed_sources, true) ? $source : 'os';
    }

    if (isset($input['auto_time_start'])) {
        $sanitized['auto_time_start'] = sanitize_text_field($input['auto_time_start']);
    }

    if (isset($input['auto_time_end'])) {
        $sanitized['auto_time_end'] = sanitize_text_field($input['auto_time_end']);
    }

    if (array_key_exists('auto_apply_on_load', $input)) {
        $sanitized['auto_apply_on_load'] = isset($input['auto_apply_on_load']) ? '1' : '0';
    }

    if (array_key_exists('auto_listen_os', $input)) {
        $sanitized['auto_listen_os'] = isset($input['auto_listen_os']) ? '1' : '0';
    }

    if (isset($input['storage_mode'])) {
        $allowed_storage = array('local', 'cookie', 'both');
        $storage_mode = sanitize_text_field($input['storage_mode']);
        $sanitized['storage_mode'] = in_array($storage_mode, $allowed_storage, true) ? $storage_mode : 'local';
    }

    if (isset($input['storage_days'])) {
        $sanitized['storage_days'] = max(1, min(3650, absint($input['storage_days'])));
    }

    if (isset($input['storage_version'])) {
        $sanitized['storage_version'] = max(1, absint($input['storage_version']));
    }

    if (array_key_exists('reset_storage', $input) && isset($input['reset_storage'])) {
        $sanitized['storage_version'] = max(1, absint($sanitized['storage_version'])) + 1;
        $sanitized['reset_storage'] = '0';
    }

    if (array_key_exists('enable_animations', $input)) {
        $sanitized['enable_animations'] = isset($input['enable_animations']) ? '1' : '0';
    }

    if (isset($input['toggle_animation_speed'])) {
        $sanitized['toggle_animation_speed'] = floatval($input['toggle_animation_speed']);
    }

    if (array_key_exists('respect_reduced_motion', $input)) {
        $sanitized['respect_reduced_motion'] = isset($input['respect_reduced_motion']) ? '1' : '0';
    }

    if (array_key_exists('force_high_contrast', $input)) {
        $sanitized['force_high_contrast'] = isset($input['force_high_contrast']) ? '1' : '0';
    }

    if (isset($input['focus_ring_style'])) {
        $allowed_focus = array('default', 'strong', 'minimal');
        $focus_style = sanitize_text_field($input['focus_ring_style']);
        $sanitized['focus_ring_style'] = in_array($focus_style, $allowed_focus, true) ? $focus_style : 'default';
    }

    if (array_key_exists('enable_shortcode', $input)) {
        $sanitized['enable_shortcode'] = isset($input['enable_shortcode']) ? '1' : '0';
    }

    if (array_key_exists('enable_widget', $input)) {
        $sanitized['enable_widget'] = isset($input['enable_widget']) ? '1' : '0';
    }

    if (isset($input['excluded_themes'])) {
        $sanitized['excluded_themes'] = sanitize_text_field($input['excluded_themes']);
    }

    if (isset($input['exclude_selectors'])) {
        $sanitized['exclude_selectors'] = sanitize_textarea_field($input['exclude_selectors']);
    }

    if (isset($input['analytics_debounce_ms'])) {
        $sanitized['analytics_debounce_ms'] = max(0, min(10000, absint($input['analytics_debounce_ms'])));
    }

    if (array_key_exists('analytics_batch', $input)) {
        $sanitized['analytics_batch'] = isset($input['analytics_batch']) ? '1' : '0';
    }

    if (isset($input['analytics_batch_interval'])) {
        $sanitized['analytics_batch_interval'] = max(500, min(60000, absint($input['analytics_batch_interval'])));
    }

    if (isset($input['analytics_batch_max'])) {
        $sanitized['analytics_batch_max'] = max(1, min(100, absint($input['analytics_batch_max'])));
    }

    if (array_key_exists('analytics_pageview_once_session', $input)) {
        $sanitized['analytics_pageview_once_session'] = isset($input['analytics_pageview_once_session']) ? '1' : '0';
    }

    if (array_key_exists('debug_mode', $input)) {
        $sanitized['debug_mode'] = isset($input['debug_mode']) ? '1' : '0';
    }
    
    if (isset($input['_admin_ui_sentinel'])) {
        $sanitized['enable_admin_bar_icon'] = isset($input['enable_admin_bar_icon']) ? '1' : '0';
        $sanitized['enable_admin_floating_toggle'] = isset($input['enable_admin_floating_toggle']) ? '1' : '0';
        $sanitized['enable_dashboard_widget'] = isset($input['enable_dashboard_widget']) ? '1' : '0';
        $sanitized['enable_admin_dark_mode'] = isset($input['enable_admin_dark_mode']) ? '1' : '0';
    }
    
    if (isset($input['admin_toggle_theme'])) {
        $sanitized['admin_toggle_theme'] = sanitize_text_field($input['admin_toggle_theme']);
    }

    if (isset($input['custom_admin_button_icon_url'])) {
        $sanitized['custom_admin_button_icon_url'] = esc_url_raw($input['custom_admin_button_icon_url']);
    }
    
    if (isset($input['admin_color_scheme_mode'])) {
        $sanitized['admin_color_scheme_mode'] = sanitize_text_field($input['admin_color_scheme_mode']);
    }
    
    if (isset($input['admin_color_preset'])) {
        $sanitized['admin_color_preset'] = sanitize_text_field($input['admin_color_preset']);
    }
    
    if (isset($input['admin_bg_color'])) {
        $sanitized['admin_bg_color'] = sanitize_hex_color($input['admin_bg_color']);
    }
    
    if (isset($input['admin_text_color'])) {
        $sanitized['admin_text_color'] = sanitize_hex_color($input['admin_text_color']);
    }
    
    if (isset($input['admin_accent_color'])) {
        $sanitized['admin_accent_color'] = sanitize_hex_color($input['admin_accent_color']);
    }
    
    if (isset($input['admin_surface_color'])) {
        $sanitized['admin_surface_color'] = sanitize_hex_color($input['admin_surface_color']);
    }
    
    if (isset($input['admin_border_color'])) {
        $sanitized['admin_border_color'] = sanitize_hex_color($input['admin_border_color']);
    }
    
    if (isset($input['admin_link_color'])) {
        $sanitized['admin_link_color'] = sanitize_hex_color($input['admin_link_color']);
    }
    
    if (isset($input['admin_link_hover_color'])) {
        $sanitized['admin_link_hover_color'] = sanitize_hex_color($input['admin_link_hover_color']);
    }
    
    if (array_key_exists('enable_analytics', $input)) {
        $sanitized['enable_analytics'] = isset($input['enable_analytics']) ? '1' : '0';
    }
    
    if (array_key_exists('analytics_track_toggles', $input)) {
        $sanitized['analytics_track_toggles'] = isset($input['analytics_track_toggles']) ? '1' : '0';
    }
    
    if (array_key_exists('analytics_track_pageviews', $input)) {
        $sanitized['analytics_track_pageviews'] = isset($input['analytics_track_pageviews']) ? '1' : '0';
    }
    
    if (array_key_exists('analytics_track_modes', $input)) {
        $sanitized['analytics_track_modes'] = isset($input['analytics_track_modes']) ? '1' : '0';
    }

    // Advanced Media Options (new in 1.6.5)
    if (isset($input['image_selector'])) {
        $sanitized['image_selector'] = sanitize_text_field($input['image_selector']);
    }
    if (isset($input['image_exclude_class'])) {
        $sanitized['image_exclude_class'] = sanitize_text_field($input['image_exclude_class']);
    }
    if (array_key_exists('enable_bg_image_filter', $input)) {
        $sanitized['enable_bg_image_filter'] = isset($input['enable_bg_image_filter']) && $input['enable_bg_image_filter'] === '1' ? '1' : '0';
    }
    if (array_key_exists('enable_svg_filter', $input)) {
        $sanitized['enable_svg_filter'] = isset($input['enable_svg_filter']) && $input['enable_svg_filter'] === '1' ? '1' : '0';
    }
    if (isset($input['video_selector'])) {
        $sanitized['video_selector'] = sanitize_text_field($input['video_selector']);
    }
    if (array_key_exists('enable_iframe_filter', $input)) {
        $sanitized['enable_iframe_filter'] = isset($input['enable_iframe_filter']) && $input['enable_iframe_filter'] === '1' ? '1' : '0';
    }
    if (isset($input['iframe_brightness'])) {
        $sanitized['iframe_brightness'] = (string) min(100, max(50, absint($input['iframe_brightness'])));
    }
    if (isset($input['slide_selector'])) {
        $sanitized['slide_selector'] = sanitize_text_field($input['slide_selector']);
    }
    if (array_key_exists('enable_slider_overlay', $input)) {
        $sanitized['enable_slider_overlay'] = isset($input['enable_slider_overlay']) && $input['enable_slider_overlay'] === '1' ? '1' : '0';
    }
    if (isset($input['slider_overlay_color'])) {
        $hex_val = sanitize_hex_color($input['slider_overlay_color']);
        $sanitized['slider_overlay_color'] = $hex_val ? $hex_val : '#000000';
    }
    if (isset($input['slider_overlay_opacity'])) {
        $sanitized['slider_overlay_opacity'] = (string) min(0.8, max(0.0, (float) $input['slider_overlay_opacity']));
    }

    return $sanitized;
}

/**
 * Section callbacks
 */
function syntekpro_toggle_general_section_callback() {
    echo '<p>' . esc_html__('Configure the general behavior of the dark mode toggle.', 'syntekpro-toggle') . '</p>';
}

function syntekpro_toggle_color_scheme_section_callback() {
    echo '<p>' . esc_html__('Choose how dark mode colors are applied: Dynamic (smart auto-adjust), Presets (curated color schemes), or Custom (manual control).', 'syntekpro-toggle') . '</p>';
}

function syntekpro_toggle_color_adjustments_section_callback() {
    echo '<p>' . esc_html__('Fine-tune the visual appearance with brightness, contrast, sepia, and grayscale filters.', 'syntekpro-toggle') . '</p>';
}

function syntekpro_toggle_images_section_callback() {
    echo '<p>' . esc_html__('Apply filters and adjustments to images in dark mode for better visibility and consistency.', 'syntekpro-toggle') . '</p>';
}

function syntekpro_toggle_videos_section_callback() {
    echo '<p>' . esc_html__('Apply filters and adjustments to videos in dark mode for improved viewing experience.', 'syntekpro-toggle') . '</p>';
}

function syntekpro_toggle_slides_section_callback() {
    echo '<p>' . esc_html__('Apply filters and adjustments to presentation slides (SlideShare, Impress, etc.) in dark mode.', 'syntekpro-toggle') . '</p>';
}

function syntekpro_toggle_media_advanced_section_callback() {
    echo '<p>' . esc_html__('Advanced selectors and options for image, video, and slider handling in dark mode.', 'syntekpro-toggle') . '</p>';
}

// ---- Advanced Media Field Callbacks ----

function syntekpro_toggle_image_selector_callback() {
    $options = syntekpro_toggle_get_options();
    $value = isset($options['image_selector']) ? $options['image_selector'] : '';
    echo '<input type="text" name="syntekpro_toggle_options[image_selector]" value="' . esc_attr($value) . '" class="large-text" placeholder="img, .wp-post-image">';
    echo '<p class="description">' . esc_html__('CSS selector for images to filter in dark mode. Leave blank to use the default (img).', 'syntekpro-toggle') . '</p>';
}

function syntekpro_toggle_image_exclude_class_callback() {
    $options = syntekpro_toggle_get_options();
    $value = isset($options['image_exclude_class']) ? $options['image_exclude_class'] : '';
    echo '<input type="text" name="syntekpro_toggle_options[image_exclude_class]" value="' . esc_attr($value) . '" class="large-text" placeholder="no-dark-filter, brand-logo">';
    echo '<p class="description">' . esc_html__('CSS class names (space-separated) of images to exclude from dark mode filtering.', 'syntekpro-toggle') . '</p>';
}

function syntekpro_toggle_enable_bg_image_filter_callback() {
    $options = syntekpro_toggle_get_options();
    $checked = !empty($options['enable_bg_image_filter']) && $options['enable_bg_image_filter'] === '1' ? 'checked' : '';
    echo '<input type="hidden" name="syntekpro_toggle_options[enable_bg_image_filter]" value="0">';
    echo '<label><input type="checkbox" name="syntekpro_toggle_options[enable_bg_image_filter]" value="1" ' . $checked . '> ' . esc_html__('Apply brightness filter to CSS background-image elements in dark mode.', 'syntekpro-toggle') . '</label>';
}

function syntekpro_toggle_enable_svg_filter_callback() {
    $options = syntekpro_toggle_get_options();
    $checked = !empty($options['enable_svg_filter']) && $options['enable_svg_filter'] === '1' ? 'checked' : '';
    echo '<input type="hidden" name="syntekpro_toggle_options[enable_svg_filter]" value="0">';
    echo '<label><input type="checkbox" name="syntekpro_toggle_options[enable_svg_filter]" value="1" ' . $checked . '> ' . esc_html__('Apply brightness filter to inline SVG elements in dark mode.', 'syntekpro-toggle') . '</label>';
}

function syntekpro_toggle_video_selector_callback() {
    $options = syntekpro_toggle_get_options();
    $value = isset($options['video_selector']) ? $options['video_selector'] : '';
    echo '<input type="text" name="syntekpro_toggle_options[video_selector]" value="' . esc_attr($value) . '" class="large-text" placeholder="video, .wp-video">';
    echo '<p class="description">' . esc_html__('CSS selector for video elements to filter. Leave blank to use the default (video).', 'syntekpro-toggle') . '</p>';
}

function syntekpro_toggle_enable_iframe_filter_callback() {
    $options = syntekpro_toggle_get_options();
    $checked = !empty($options['enable_iframe_filter']) && $options['enable_iframe_filter'] === '1' ? 'checked' : '';
    echo '<input type="hidden" name="syntekpro_toggle_options[enable_iframe_filter]" value="0">';
    echo '<label><input type="checkbox" name="syntekpro_toggle_options[enable_iframe_filter]" value="1" ' . $checked . '> ' . esc_html__('Apply brightness filter to iframe and embed elements (YouTube, Vimeo, etc.) in dark mode.', 'syntekpro-toggle') . '</label>';
}

function syntekpro_toggle_iframe_brightness_callback() {
    $options = syntekpro_toggle_get_options();
    $value = isset($options['iframe_brightness']) ? intval($options['iframe_brightness']) : 80;
    echo '<input type="range" name="syntekpro_toggle_options[iframe_brightness]" min="50" max="100" step="1" value="' . esc_attr($value) . '" oninput="this.nextElementSibling.textContent=this.value+\'%\'">';
    echo '<span style="margin-left:8px;font-weight:600;">' . esc_html($value) . '%</span>';
    echo '<p class="description">' . esc_html__('Sets the brightness level for filtered iframes/embeds in dark mode.', 'syntekpro-toggle') . '</p>';
}

function syntekpro_toggle_slide_selector_callback() {
    $options = syntekpro_toggle_get_options();
    $value = isset($options['slide_selector']) ? $options['slide_selector'] : '';
    echo '<input type="text" name="syntekpro_toggle_options[slide_selector]" value="' . esc_attr($value) . '" class="large-text" placeholder=".slide, .swiper-slide">';
    echo '<p class="description">' . esc_html__('CSS selector for slide elements to filter. Leave blank for the plugin default.', 'syntekpro-toggle') . '</p>';
}

function syntekpro_toggle_enable_slider_overlay_callback() {
    $options = syntekpro_toggle_get_options();
    $checked = !empty($options['enable_slider_overlay']) && $options['enable_slider_overlay'] === '1' ? 'checked' : '';
    echo '<input type="hidden" name="syntekpro_toggle_options[enable_slider_overlay]" value="0">';
    echo '<label><input type="checkbox" name="syntekpro_toggle_options[enable_slider_overlay]" value="1" ' . $checked . '> ' . esc_html__('Overlay a semi-transparent dark layer on sliders in dark mode.', 'syntekpro-toggle') . '</label>';
}

function syntekpro_toggle_slider_overlay_color_callback() {
    $options = syntekpro_toggle_get_options();
    $value = isset($options['slider_overlay_color']) ? $options['slider_overlay_color'] : '#000000';
    echo '<input type="color" name="syntekpro_toggle_options[slider_overlay_color]" value="' . esc_attr($value) . '" class="syntekpro-color-picker">';
    echo '<p class="description">' . esc_html__('Color of the dark overlay applied to sliders when dark mode is active.', 'syntekpro-toggle') . '</p>';
}

function syntekpro_toggle_slider_overlay_opacity_callback() {
    $options = syntekpro_toggle_get_options();
    $value = isset($options['slider_overlay_opacity']) ? floatval($options['slider_overlay_opacity']) : 0.2;
    echo '<input type="range" name="syntekpro_toggle_options[slider_overlay_opacity]" min="0" max="0.8" step="0.05" value="' . esc_attr($value) . '" oninput="this.nextElementSibling.textContent=Math.round(this.value*100)+\'%\'">';
    echo '<span style="margin-left:8px;font-weight:600;">' . esc_html(round($value * 100)) . '%</span>';
    echo '<p class="description">' . esc_html__('Opacity of the overlay. 0 = transparent (off), 0.8 = 80% opaque.', 'syntekpro-toggle') . '</p>';
}

function syntekpro_toggle_display_section_callback() {
    echo '<p>' . esc_html__('Control where the toggle is shown across your site.', 'syntekpro-toggle') . '</p>';
}

function syntekpro_toggle_user_targeting_section_callback() {
    echo '<p>' . esc_html__('Limit the toggle to specific visitors or user roles.', 'syntekpro-toggle') . '</p>';
}

function syntekpro_toggle_schedule_section_callback() {
    echo '<p>' . esc_html__('Show the toggle only during selected hours and days.', 'syntekpro-toggle') . '</p>';
}

function syntekpro_toggle_behavior_section_callback() {
    echo '<p>' . esc_html__('Configure how auto mode chooses dark or light.', 'syntekpro-toggle') . '</p>';
}

function syntekpro_toggle_storage_section_callback() {
    echo '<p>' . esc_html__('Choose how user preferences are stored and reset.', 'syntekpro-toggle') . '</p>';
}

function syntekpro_toggle_animation_section_callback() {
    echo '<p>' . esc_html__('Control animation behavior and speeds.', 'syntekpro-toggle') . '</p>';
}

function syntekpro_toggle_accessibility_section_callback() {
    echo '<p>' . esc_html__('Accessibility options for motion and contrast.', 'syntekpro-toggle') . '</p>';
}

function syntekpro_toggle_integrations_section_callback() {
    echo '<p>' . esc_html__('Enable shortcode or widget output.', 'syntekpro-toggle') . '</p>';
}

function syntekpro_toggle_theme_override_section_callback() {
    echo '<p>' . esc_html__('Disable features for specific themes or selectors.', 'syntekpro-toggle') . '</p>';
}

function syntekpro_toggle_performance_section_callback() {
    echo '<p>' . esc_html__('Tune tracking performance and page view behavior.', 'syntekpro-toggle') . '</p>';
}

function syntekpro_toggle_debug_section_callback() {
    echo '<p>' . esc_html__('Enable logging to help troubleshoot issues.', 'syntekpro-toggle') . '</p>';
}

function syntekpro_toggle_render_settings_section($page, $section_id) {
    global $wp_settings_sections;

    if (empty($wp_settings_sections[$page][$section_id])) {
        return;
    }

    $section = $wp_settings_sections[$page][$section_id];

    if (!empty($section['callback']) && is_callable($section['callback'])) {
        call_user_func($section['callback'], $section);
    }

    echo '<table class="form-table" role="presentation">';
    do_settings_fields($page, $section_id);
    echo '</table>';
}

function syntekpro_toggle_display_mode_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <select name="syntekpro_toggle_options[display_mode]" id="display_mode">
        <option value="all" <?php selected($options['display_mode'], 'all'); ?>><?php esc_html_e('Show everywhere', 'syntekpro-toggle'); ?></option>
        <option value="include" <?php selected($options['display_mode'], 'include'); ?>><?php esc_html_e('Show only on selected', 'syntekpro-toggle'); ?></option>
        <option value="exclude" <?php selected($options['display_mode'], 'exclude'); ?>><?php esc_html_e('Hide on selected', 'syntekpro-toggle'); ?></option>
    </select>
    <p class="description"><?php esc_html_e('Use the lists below to include or exclude specific content.', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_display_post_types_callback() {
    $options = syntekpro_toggle_get_options();
    $post_types = get_post_types(array('public' => true), 'objects');
    $selected = isset($options['display_post_types']) && is_array($options['display_post_types']) ? $options['display_post_types'] : array();
    ?>
    <div>
        <?php foreach ($post_types as $post_type): ?>
            <label style="display:block; margin-bottom:4px;">
                <input type="checkbox" name="syntekpro_toggle_options[display_post_types][]" value="<?php echo esc_attr($post_type->name); ?>" <?php checked(in_array($post_type->name, $selected, true)); ?>>
                <?php echo esc_html($post_type->labels->singular_name); ?>
            </label>
        <?php endforeach; ?>
    </div>
    <p class="description"><?php esc_html_e('Used when display mode is set to include or exclude.', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_display_pages_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="text" name="syntekpro_toggle_options[display_pages]" value="<?php echo esc_attr($options['display_pages']); ?>" placeholder="12, 34, 56" class="regular-text">
    <p class="description"><?php esc_html_e('Comma-separated page IDs.', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_display_categories_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="text" name="syntekpro_toggle_options[display_categories]" value="<?php echo esc_attr($options['display_categories']); ?>" placeholder="3, 9" class="regular-text">
    <p class="description"><?php esc_html_e('Comma-separated category IDs.', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_display_tags_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="text" name="syntekpro_toggle_options[display_tags]" value="<?php echo esc_attr($options['display_tags']); ?>" placeholder="5, 8" class="regular-text">
    <p class="description"><?php esc_html_e('Comma-separated tag IDs.', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_exclude_special_pages_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <label>
        <input type="checkbox" name="syntekpro_toggle_options[exclude_special_pages]" value="1" <?php checked($options['exclude_special_pages'], '1'); ?>>
        <?php esc_html_e('Hide on login, register, checkout, cart, and account pages', 'syntekpro-toggle'); ?>
    </label>
    <?php
}

function syntekpro_toggle_user_visibility_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <select name="syntekpro_toggle_options[user_visibility]" id="user_visibility">
        <option value="all" <?php selected($options['user_visibility'], 'all'); ?>><?php esc_html_e('All visitors', 'syntekpro-toggle'); ?></option>
        <option value="logged_in" <?php selected($options['user_visibility'], 'logged_in'); ?>><?php esc_html_e('Logged-in users only', 'syntekpro-toggle'); ?></option>
        <option value="guests" <?php selected($options['user_visibility'], 'guests'); ?>><?php esc_html_e('Guests only', 'syntekpro-toggle'); ?></option>
        <option value="roles" <?php selected($options['user_visibility'], 'roles'); ?>><?php esc_html_e('Specific roles', 'syntekpro-toggle'); ?></option>
    </select>
    <?php
}

function syntekpro_toggle_user_roles_callback() {
    $options = syntekpro_toggle_get_options();
    $roles = get_editable_roles();
    $selected = isset($options['user_roles']) && is_array($options['user_roles']) ? $options['user_roles'] : array();
    ?>
    <div>
        <?php foreach ($roles as $role_key => $role): ?>
            <label style="display:block; margin-bottom:4px;">
                <input type="checkbox" name="syntekpro_toggle_options[user_roles][]" value="<?php echo esc_attr($role_key); ?>" <?php checked(in_array($role_key, $selected, true)); ?>>
                <?php echo esc_html($role['name']); ?>
            </label>
        <?php endforeach; ?>
    </div>
    <p class="description"><?php esc_html_e('Used when visibility is set to specific roles.', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_schedule_enabled_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <label>
        <input type="checkbox" name="syntekpro_toggle_options[schedule_enabled]" value="1" <?php checked($options['schedule_enabled'], '1'); ?>>
        <?php esc_html_e('Enable schedule', 'syntekpro-toggle'); ?>
    </label>
    <?php
}

function syntekpro_toggle_schedule_days_callback() {
    $options = syntekpro_toggle_get_options();
    $days = array(
        'mon' => 'Mon',
        'tue' => 'Tue',
        'wed' => 'Wed',
        'thu' => 'Thu',
        'fri' => 'Fri',
        'sat' => 'Sat',
        'sun' => 'Sun'
    );
    $selected = isset($options['schedule_days']) && is_array($options['schedule_days']) ? $options['schedule_days'] : array();
    ?>
    <div>
        <?php foreach ($days as $key => $label): ?>
            <label style="display:inline-block; margin-right:10px;">
                <input type="checkbox" name="syntekpro_toggle_options[schedule_days][]" value="<?php echo esc_attr($key); ?>" <?php checked(in_array($key, $selected, true)); ?>>
                <?php echo esc_html($label); ?>
            </label>
        <?php endforeach; ?>
    </div>
    <?php
}

function syntekpro_toggle_schedule_start_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="time" name="syntekpro_toggle_options[schedule_start]" value="<?php echo esc_attr($options['schedule_start']); ?>">
    <?php
}

function syntekpro_toggle_schedule_end_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="time" name="syntekpro_toggle_options[schedule_end]" value="<?php echo esc_attr($options['schedule_end']); ?>">
    <?php
}

function syntekpro_toggle_auto_mode_source_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <select name="syntekpro_toggle_options[auto_mode_source]">
        <option value="os" <?php selected($options['auto_mode_source'], 'os'); ?>><?php esc_html_e('Follow OS', 'syntekpro-toggle'); ?></option>
        <option value="time" <?php selected($options['auto_mode_source'], 'time'); ?>><?php esc_html_e('Use time range', 'syntekpro-toggle'); ?></option>
    </select>
    <?php
}

function syntekpro_toggle_auto_time_start_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="time" name="syntekpro_toggle_options[auto_time_start]" value="<?php echo esc_attr($options['auto_time_start']); ?>">
    <?php
}

function syntekpro_toggle_auto_time_end_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="time" name="syntekpro_toggle_options[auto_time_end]" value="<?php echo esc_attr($options['auto_time_end']); ?>">
    <?php
}

function syntekpro_toggle_auto_apply_on_load_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <label>
        <input type="checkbox" name="syntekpro_toggle_options[auto_apply_on_load]" value="1" <?php checked($options['auto_apply_on_load'], '1'); ?>>
        <?php esc_html_e('Apply auto mode on page load', 'syntekpro-toggle'); ?>
    </label>
    <?php
}

function syntekpro_toggle_auto_listen_os_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <label>
        <input type="checkbox" name="syntekpro_toggle_options[auto_listen_os]" value="1" <?php checked($options['auto_listen_os'], '1'); ?>>
        <?php esc_html_e('Listen for OS theme changes', 'syntekpro-toggle'); ?>
    </label>
    <?php
}

function syntekpro_toggle_storage_mode_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <select name="syntekpro_toggle_options[storage_mode]">
        <option value="local" <?php selected($options['storage_mode'], 'local'); ?>><?php esc_html_e('Local storage', 'syntekpro-toggle'); ?></option>
        <option value="cookie" <?php selected($options['storage_mode'], 'cookie'); ?>><?php esc_html_e('Cookie only', 'syntekpro-toggle'); ?></option>
        <option value="both" <?php selected($options['storage_mode'], 'both'); ?>><?php esc_html_e('Local storage + cookie', 'syntekpro-toggle'); ?></option>
    </select>
    <?php
}

function syntekpro_toggle_storage_days_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="number" name="syntekpro_toggle_options[storage_days]" value="<?php echo esc_attr($options['storage_days']); ?>" min="1" max="3650">
    <p class="description"><?php esc_html_e('Used when cookie storage is enabled.', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_reset_storage_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <button type="submit" name="syntekpro_toggle_options[reset_storage]" value="1" class="button button-secondary">
        <?php esc_html_e('Reset stored preferences', 'syntekpro-toggle'); ?>
    </button>
    <p class="description"><?php esc_html_e('Current storage version:', 'syntekpro-toggle'); ?> <?php echo esc_html($options['storage_version']); ?>.</p>
    <?php
}

function syntekpro_toggle_enable_animations_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <label>
        <input type="checkbox" name="syntekpro_toggle_options[enable_animations]" value="1" <?php checked($options['enable_animations'], '1'); ?>>
        <?php esc_html_e('Enable animations and transitions', 'syntekpro-toggle'); ?>
    </label>
    <?php
}

function syntekpro_toggle_toggle_animation_speed_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="number" name="syntekpro_toggle_options[toggle_animation_speed]" value="<?php echo esc_attr($options['toggle_animation_speed']); ?>" min="0" max="5" step="0.05">
    <span>seconds</span>
    <?php
}

function syntekpro_toggle_respect_reduced_motion_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <label>
        <input type="checkbox" name="syntekpro_toggle_options[respect_reduced_motion]" value="1" <?php checked($options['respect_reduced_motion'], '1'); ?>>
        <?php esc_html_e('Reduce motion when user prefers reduced motion', 'syntekpro-toggle'); ?>
    </label>
    <?php
}

function syntekpro_toggle_force_high_contrast_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <label>
        <input type="checkbox" name="syntekpro_toggle_options[force_high_contrast]" value="1" <?php checked($options['force_high_contrast'], '1'); ?>>
        <?php esc_html_e('Force high contrast mode', 'syntekpro-toggle'); ?>
    </label>
    <?php
}

function syntekpro_toggle_focus_ring_style_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <select name="syntekpro_toggle_options[focus_ring_style]">
        <option value="default" <?php selected($options['focus_ring_style'], 'default'); ?>><?php esc_html_e('Default', 'syntekpro-toggle'); ?></option>
        <option value="strong" <?php selected($options['focus_ring_style'], 'strong'); ?>><?php esc_html_e('Strong', 'syntekpro-toggle'); ?></option>
        <option value="minimal" <?php selected($options['focus_ring_style'], 'minimal'); ?>><?php esc_html_e('Minimal', 'syntekpro-toggle'); ?></option>
    </select>
    <?php
}

function syntekpro_toggle_enable_shortcode_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <label>
        <input type="checkbox" name="syntekpro_toggle_options[enable_shortcode]" value="1" <?php checked($options['enable_shortcode'], '1'); ?>>
        <?php esc_html_e('Enable shortcode output', 'syntekpro-toggle'); ?>
    </label>
    <p class="description"><?php esc_html_e('Shortcode:', 'syntekpro-toggle'); ?> <code>[syntekpro_toggle]</code></p>
    <?php
}

function syntekpro_toggle_enable_widget_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <label>
        <input type="checkbox" name="syntekpro_toggle_options[enable_widget]" value="1" <?php checked($options['enable_widget'], '1'); ?>>
        <?php esc_html_e('Enable widget output', 'syntekpro-toggle'); ?>
    </label>
    <?php
}

function syntekpro_toggle_excluded_themes_callback() {
    $options = syntekpro_toggle_get_options();
    $theme = wp_get_theme();
    ?>
    <input type="text" name="syntekpro_toggle_options[excluded_themes]" value="<?php echo esc_attr($options['excluded_themes']); ?>" placeholder="theme-slug, child-theme" class="regular-text">
    <p class="description"><?php esc_html_e('Current theme:', 'syntekpro-toggle'); ?> <?php echo esc_html($theme->get_stylesheet()); ?>.</p>
    <?php
}

function syntekpro_toggle_exclude_selectors_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <textarea name="syntekpro_toggle_options[exclude_selectors]" rows="4" class="large-text" placeholder=".no-dark-mode\n#hero img"><?php echo esc_textarea($options['exclude_selectors']); ?></textarea>
    <p class="description"><?php esc_html_e('One selector per line to skip filters in dark mode.', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_analytics_debounce_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="number" name="syntekpro_toggle_options[analytics_debounce_ms]" value="<?php echo esc_attr($options['analytics_debounce_ms']); ?>" min="0" max="10000" step="50">
    <?php
}

function syntekpro_toggle_analytics_batch_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <label>
        <input type="checkbox" name="syntekpro_toggle_options[analytics_batch]" value="1" <?php checked($options['analytics_batch'], '1'); ?>>
        <?php esc_html_e('Send analytics in batches', 'syntekpro-toggle'); ?>
    </label>
    <?php
}

function syntekpro_toggle_analytics_batch_interval_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="number" name="syntekpro_toggle_options[analytics_batch_interval]" value="<?php echo esc_attr($options['analytics_batch_interval']); ?>" min="500" max="60000" step="250">
    <?php
}

function syntekpro_toggle_analytics_batch_max_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="number" name="syntekpro_toggle_options[analytics_batch_max]" value="<?php echo esc_attr($options['analytics_batch_max']); ?>" min="1" max="100">
    <?php
}

function syntekpro_toggle_analytics_pageview_once_session_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <label>
        <input type="checkbox" name="syntekpro_toggle_options[analytics_pageview_once_session]" value="1" <?php checked($options['analytics_pageview_once_session'], '1'); ?>>
        <?php esc_html_e('Count a page view once per session', 'syntekpro-toggle'); ?>
    </label>
    <?php
}

function syntekpro_toggle_debug_mode_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <label>
        <input type="checkbox" name="syntekpro_toggle_options[debug_mode]" value="1" <?php checked($options['debug_mode'], '1'); ?>>
        <?php esc_html_e('Enable debug logging', 'syntekpro-toggle'); ?>
    </label>
    <?php
}

/**
 * Field callbacks
 */
function syntekpro_toggle_default_mode_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <select name="syntekpro_toggle_options[default_mode]" id="default_mode">
        <option value="auto" <?php selected($options['default_mode'], 'auto'); ?>><?php esc_html_e('Auto (Follow OS Preference)', 'syntekpro-toggle'); ?></option>
        <option value="light" <?php selected($options['default_mode'], 'light'); ?>><?php esc_html_e('Light Mode', 'syntekpro-toggle'); ?></option>
        <option value="dark" <?php selected($options['default_mode'], 'dark'); ?>><?php esc_html_e('Dark Mode', 'syntekpro-toggle'); ?></option>
        <option value="manual" <?php selected($options['default_mode'], 'manual'); ?>><?php esc_html_e('Manual Only (User Chooses)', 'syntekpro-toggle'); ?></option>
    </select>
    <p class="description"><?php esc_html_e('Set the default mode when users first visit your site.', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_enable_toggle_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <label>
        <input type="hidden" name="syntekpro_toggle_options[_enable_toggle_sentinel]" value="1">
        <input type="checkbox" name="syntekpro_toggle_options[enable_toggle]" value="1" <?php checked($options['enable_toggle'], '1'); ?>>
        <?php esc_html_e('Show toggle button on frontend', 'syntekpro-toggle'); ?>
    </label>
    <p class="description"><?php esc_html_e('Uncheck to hide the toggle button (useful if using shortcode or widget).', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_button_position_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <select name="syntekpro_toggle_options[button_position]" id="button_position">
        <option value="bottom-right" <?php selected($options['button_position'], 'bottom-right'); ?>><?php esc_html_e('Bottom Right', 'syntekpro-toggle'); ?></option>
        <option value="bottom-left" <?php selected($options['button_position'], 'bottom-left'); ?>><?php esc_html_e('Bottom Left', 'syntekpro-toggle'); ?></option>
        <option value="top-right" <?php selected($options['button_position'], 'top-right'); ?>><?php esc_html_e('Top Right', 'syntekpro-toggle'); ?></option>
        <option value="top-left" <?php selected($options['button_position'], 'top-left'); ?>><?php esc_html_e('Top Left', 'syntekpro-toggle'); ?></option>
    </select>
    <p class="description"><?php esc_html_e('Choose where to display the toggle button.', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_button_size_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="number" name="syntekpro_toggle_options[button_size]" value="<?php echo esc_attr($options['button_size']); ?>" min="30" max="100" step="5">
    <span>px</span>
    <p class="description"><?php esc_html_e('Button size in pixels (30-100).', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_custom_button_icon_callback() {
    $options = syntekpro_toggle_get_options();
    $value = isset($options['custom_button_icon_url']) ? $options['custom_button_icon_url'] : '';
    ?>
    <input type="url" class="regular-text syntekpro-media-url" name="syntekpro_toggle_options[custom_button_icon_url]" value="<?php echo esc_attr($value); ?>" placeholder="https://example.com/icon.png" />
    <button type="button" class="button syntekpro-media-upload" data-target="syntekpro_toggle_options[custom_button_icon_url]"><?php esc_html_e('Upload', 'syntekpro-toggle'); ?></button>
    <button type="button" class="button syntekpro-media-remove" data-target="syntekpro_toggle_options[custom_button_icon_url]"><?php esc_html_e('Remove', 'syntekpro-toggle'); ?></button>
    <div class="syntekpro-media-preview" style="margin-top:8px;">
        <?php if (!empty($value)) : ?>
            <img src="<?php echo esc_url($value); ?>" alt="" style="width:32px;height:32px;object-fit:contain;border:1px solid #ddd;padding:4px;border-radius:4px;" />
        <?php endif; ?>
    </div>
    <p class="description"><?php esc_html_e('Optional. Upload your own frontend toggle icon. Leave empty to use the default sun/moon icons.', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_custom_admin_button_icon_callback() {
    $options = syntekpro_toggle_get_options();
    $value = isset($options['custom_admin_button_icon_url']) ? $options['custom_admin_button_icon_url'] : '';
    ?>
    <input type="url" class="regular-text syntekpro-media-url" name="syntekpro_toggle_options[custom_admin_button_icon_url]" value="<?php echo esc_attr($value); ?>" placeholder="https://example.com/icon.png" />
    <button type="button" class="button syntekpro-media-upload" data-target="syntekpro_toggle_options[custom_admin_button_icon_url]"><?php esc_html_e('Upload', 'syntekpro-toggle'); ?></button>
    <button type="button" class="button syntekpro-media-remove" data-target="syntekpro_toggle_options[custom_admin_button_icon_url]"><?php esc_html_e('Remove', 'syntekpro-toggle'); ?></button>
    <div class="syntekpro-media-preview" style="margin-top:8px;">
        <?php if (!empty($value)) : ?>
            <img src="<?php echo esc_url($value); ?>" alt="" style="width:32px;height:32px;object-fit:contain;border:1px solid #ddd;padding:4px;border-radius:4px;" />
        <?php endif; ?>
    </div>
    <p class="description"><?php esc_html_e('Optional. Upload your own admin floating toggle icon. Leave empty to use the default sun/moon icons.', 'syntekpro-toggle'); ?></p>
    <?php
}

/**
 * Legacy compatibility helper. All features are available in the free plugin.
 */
function syntekpro_toggle_is_premium_feature($feature_type, $feature_id) {
    return false;
}

function syntekpro_toggle_theme_callback() {
    $options  = syntekpro_toggle_get_options();
    $is_plus  = syntekpro_toggle_is_plus();

    // The first 3 themes are free; the rest require Toggle+.
    $free_themes = array( 'default', 'minimal', 'modern' );

    $themes = array(
        'default'       => array('name' => 'Default',       'desc' => 'Classic circular button'),
        'minimal'       => array('name' => 'Minimal',       'desc' => 'Clean and simple'),
        'modern'        => array('name' => 'Modern',        'desc' => 'Contemporary design'),
        'neumorphic'    => array('name' => 'Neumorphic',    'desc' => 'Soft UI design'),
        'glassmorphic'  => array('name' => 'Glassmorphic',  'desc' => 'Frosted glass effect'),
        'neon'          => array('name' => 'Neon',          'desc' => 'Glowing neon style'),
        'gradient'      => array('name' => 'Gradient',      'desc' => 'Colorful gradient'),
        'retro'         => array('name' => 'Retro',         'desc' => 'Vintage 80s style'),
        'flat'          => array('name' => 'Flat',          'desc' => 'Flat design style'),
        'material'      => array('name' => 'Material',      'desc' => 'Google Material Design'),
        'ios'           => array('name' => 'iOS Style',     'desc' => 'Apple iOS inspired'),
        'cyberpunk'     => array('name' => 'Cyberpunk',     'desc' => 'Futuristic tech'),
        'elegant'       => array('name' => 'Elegant',       'desc' => 'Refined and classy'),
        'playful'       => array('name' => 'Playful',       'desc' => 'Fun and bouncy'),
        'professional'  => array('name' => 'Professional',  'desc' => 'Business style'),
        'square'        => array('name' => 'Square',        'desc' => 'Sharp corners'),
        'pill'          => array('name' => 'Pill',          'desc' => 'Elongated rounded'),
        'hexagon'       => array('name' => 'Hexagon',       'desc' => 'Six-sided shape'),
        'diamond'       => array('name' => 'Diamond',       'desc' => 'Rotated square'),
        'morphing'      => array('name' => 'Morphing',      'desc' => 'Animated transitions'),
    );
    ?>
    <div class="syntekpro-toggle-themes-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 15px; margin-top: 10px;">
        <?php foreach ($themes as $key => $theme):
            $is_free_theme   = in_array( $key, $free_themes, true );
            $locked          = ! $is_plus && ! $is_free_theme;
            $wrapper_opacity = $locked ? 'opacity:0.55;' : '';
            $cursor_style    = $locked ? 'cursor:not-allowed;' : 'cursor:pointer;';
            $title_attr      = $locked ? esc_attr__( 'Requires Toggle+', 'syntekpro-toggle' ) : ( $is_free_theme ? esc_attr__( 'Free', 'syntekpro-toggle' ) : esc_attr__( 'Toggle+', 'syntekpro-toggle' ) );
        ?>
            <label class="theme-option" style="border: 2px solid #ddd; border-radius: 8px; padding: 15px; transition: all 0.3s; text-align: center; position: relative; <?php echo esc_attr( $cursor_style . $wrapper_opacity ); ?>" title="<?php echo $title_attr; ?>">
                <input type="radio"
                    name="syntekpro_toggle_options[toggle_theme]"
                    value="<?php echo esc_attr($key); ?>"
                    <?php checked($options['toggle_theme'], $key); ?>
                    <?php if ($locked) echo 'disabled'; ?>
                    style="margin-bottom: 10px;">

                <?php if ($locked): ?>
                    <a href="<?php echo esc_url( admin_url('admin.php?page=syntekpro-toggle-license') ); ?>" style="position:absolute;top:6px;right:6px;text-decoration:none;" title="<?php esc_attr_e('Requires Toggle+','syntekpro-toggle'); ?>">
                        <span style="display:inline-block;background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;font-size:9px;font-weight:700;padding:2px 5px;border-radius:3px;letter-spacing:0.3px;">Toggle+</span>
                    </a>
                <?php elseif ($is_free_theme): ?>
                    <span style="position:absolute;top:6px;right:6px;display:inline-block;background:#46b450;color:#fff;font-size:9px;font-weight:700;padding:2px 5px;border-radius:3px;letter-spacing:0.3px;">Free</span>
                <?php endif; ?>

                <div class="theme-preview syntekpro-theme-<?php echo esc_attr($key); ?>" style="width: 50px; height: 50px; margin: 0 auto 10px; position: relative; display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                    </svg>
                </div>
                <strong style="display: block; margin-bottom: 3px;"><?php echo esc_html($theme['name']); ?></strong>
                <span style="font-size: 11px; color: #666; display: block;">
                    <?php echo esc_html($theme['desc']); ?>
                </span>
            </label>
        <?php endforeach; ?>
    </div>

    <?php if ( ! $is_plus ): ?>
    <div style="background: #fff8e1; border: 1px solid #f0b429; padding: 14px 18px; border-radius: 8px; margin-top: 15px; font-size: 13px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
        <span style="color:#7c5a0b;">
            <strong><?php esc_html_e('🔒 Get full theme options in Toggle+', 'syntekpro-toggle'); ?></strong>
            &mdash; <?php esc_html_e('17 more button themes including Neon, Glassmorphic, Cyberpunk &amp; more.', 'syntekpro-toggle'); ?>
        </span>
        <a href="<?php echo esc_url( admin_url('admin.php?page=syntekpro-toggle-license') ); ?>" class="button" style="background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;border:none;padding:6px 18px;font-weight:700;border-radius:5px;text-decoration:none;white-space:nowrap;">
            <?php esc_html_e('Get Toggle+', 'syntekpro-toggle'); ?>
        </a>
    </div>
    <?php endif; ?>

    <style>
        .theme-option:not([style*="not-allowed"]):hover { border-color: #2271b1; background: #f0f6fc; transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .theme-option:has(input[type="radio"]:checked) { border-color: #2271b1; border-width: 3px; background: #f0f6fc; }
        
        /* Theme Preview Styles */
        .syntekpro-theme-default { background: #333; color: #fff; border-radius: 50%; box-shadow: 0 2px 8px rgba(0,0,0,0.3); }
        .syntekpro-theme-minimal { background: #f5f5f5; color: #333; border-radius: 50%; border: 1px solid #ddd; }
        .syntekpro-theme-neumorphic { background: #e0e5ec; color: #333; border-radius: 50%; box-shadow: 8px 8px 16px #a3b1c6, -8px -8px 16px #ffffff; }
        .syntekpro-theme-glassmorphic { background: rgba(255,255,255,0.1); color: #333; border-radius: 50%; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2); box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37); }
        .syntekpro-theme-neon { background: #0a0e27; color: #00ffff; border-radius: 50%; box-shadow: 0 0 20px #00ffff, inset 0 0 20px rgba(0,255,255,0.2); border: 2px solid #00ffff; }
        .syntekpro-theme-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; border-radius: 50%; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4); }
        .syntekpro-theme-retro { background: linear-gradient(45deg, #ff006e, #ffbe0b); color: #fff; border-radius: 8px; box-shadow: 4px 4px 0 rgba(0,0,0,0.3); border: 3px solid #000; }
        .syntekpro-theme-modern { background: #000; color: #fff; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
        .syntekpro-theme-flat { background: #3498db; color: #fff; border-radius: 4px; }
        .syntekpro-theme-material { background: #2196F3; color: #fff; border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,0.2), 0 4px 8px rgba(0,0,0,0.2); }
        .syntekpro-theme-ios { background: linear-gradient(180deg, #f8f9fa 0%, #e9ecef 100%); color: #333; border-radius: 50%; border: 1px solid rgba(0,0,0,0.1); box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24); }
        .syntekpro-theme-cyberpunk { background: linear-gradient(135deg, #f72585, #7209b7, #3a0ca3); color: #00ff41; border-radius: 4px; box-shadow: 0 0 20px rgba(247, 37, 133, 0.6); border: 1px solid #00ff41; }
        .syntekpro-theme-elegant { background: linear-gradient(135deg, #2c3e50, #34495e); color: #ecf0f1; border-radius: 50%; box-shadow: 0 4px 20px rgba(0,0,0,0.3); border: 2px solid rgba(236, 240, 241, 0.1); }
        .syntekpro-theme-playful { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: #fff; border-radius: 50%; animation: bounce 2s infinite; transform: scale(1.05); }
        .syntekpro-theme-professional { background: #1a1a2e; color: #eee; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.3); border: 1px solid #16213e; }
        .syntekpro-theme-square { background: #444; color: #fff; border-radius: 4px; box-shadow: 0 4px 8px rgba(0,0,0,0.3); }
        .syntekpro-theme-pill { background: #5e60ce; color: #fff; border-radius: 25px; width: 70px; box-shadow: 0 4px 12px rgba(94, 96, 206, 0.4); }
        .syntekpro-theme-hexagon { background: #ff6b6b; color: #fff; clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%); box-shadow: 0 4px 12px rgba(255, 107, 107, 0.4); }
        .syntekpro-theme-diamond { background: #4ecdc4; color: #fff; transform: rotate(45deg); border-radius: 8px; box-shadow: 0 4px 12px rgba(78, 205, 196, 0.4); }
        .syntekpro-theme-diamond svg { transform: rotate(-45deg); }
        .syntekpro-theme-morphing { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: #fff; border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; box-shadow: 0 4px 15px rgba(250, 112, 154, 0.4); animation: morph 3s infinite; }
        
        @keyframes bounce { 0%, 100% { transform: scale(1.05) translateY(0); } 50% { transform: scale(1.05) translateY(-3px); } }
        @keyframes morph { 0%, 100% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; } 50% { border-radius: 70% 30% 30% 70% / 70% 70% 30% 30%; } }
    </style>
    <p class="description"><?php esc_html_e('Choose a visual style for your toggle button.', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_button_shape_callback() {
    $options = syntekpro_toggle_get_options();
    $shapes = array(
        'default' => 'Circular (Default)',
        'shape-pill' => 'Pill - Elongated',
        'shape-square' => 'Square',
        'shape-rounded' => 'Rounded Square',
        'shape-stretched' => 'Stretched Horizontal',
        'shape-vertical' => 'Stretched Vertical',
    );
    ?>
    <select name="syntekpro_toggle_options[button_shape]" style="min-width: 300px; padding: 8px; border-radius: 4px; border: 1px solid #ddd;">
        <?php foreach ($shapes as $key => $label): ?>
            <option value="<?php echo esc_attr($key); ?>" <?php selected($options['button_shape'], $key); ?>>
                <?php echo esc_html($label); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <p class="description"><?php esc_html_e('Choose the button shape or size variation. Pill and Vertical shapes will display the moon/sun icon next to text.', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_button_animation_callback() {
    $options = syntekpro_toggle_get_options();
    $animations = array(
        'none' => 'No Animation',
        'anim-pulse' => 'Pulse - Glowing pulse effect',
        'anim-bounce' => 'Bounce - Continuous bouncing',
        'anim-float' => 'Float - Floating up and down',
        'anim-rotate' => 'Rotate - Spinning rotation',
        'anim-breath' => 'Breath - Breathing glow',
        'anim-shake' => 'Shake - Subtle shake effect',
        'anim-spin' => 'Spin - Fast spinning',
        'anim-swing' => 'Swing - Pendulum swing',
        'anim-oscillate' => 'Oscillate - Wave-like motion',
        'anim-pulse-circle' => 'Pulse Circle - Radial pulse',
    );
    ?>
    <select name="syntekpro_toggle_options[button_animation]" style="min-width: 300px; padding: 8px; border-radius: 4px; border: 1px solid #ddd;">
        <?php foreach ($animations as $key => $label): ?>
            <option value="<?php echo esc_attr($key); ?>" <?php selected($options['button_animation'], $key); ?>>
                <?php echo esc_html($label); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <p class="description"><?php esc_html_e('Choose a continuous animation for your button. Animations pause on click and respond to hover states.', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_button_bg_style_callback() {
    $options = syntekpro_toggle_get_options();
    $bg_styles = array(
        'solid' => 'Solid Color',
        'bg-striped' => 'Striped Pattern',
        'bg-checkered' => 'Checkered Pattern',
        'bg-dotted' => 'Dotted Pattern',
        'bg-wavy' => 'Wavy Pattern',
    );
    ?>
    <select name="syntekpro_toggle_options[button_bg_style]" style="min-width: 300px; padding: 8px; border-radius: 4px; border: 1px solid #ddd;">
        <?php foreach ($bg_styles as $key => $label): ?>
            <option value="<?php echo esc_attr($key); ?>" <?php selected($options['button_bg_style'], $key); ?>>
                <?php echo esc_html($label); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <p class="description"><?php esc_html_e('Choose a background pattern style for your button.', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_bg_color_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="text" name="syntekpro_toggle_options[bg_color]" value="<?php echo esc_attr($options['bg_color']); ?>" class="syntekpro-color-picker">
    <p class="description"><?php esc_html_e('Main background color for dark mode.', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_text_color_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="text" name="syntekpro_toggle_options[text_color]" value="<?php echo esc_attr($options['text_color']); ?>" class="syntekpro-color-picker">
    <p class="description"><?php esc_html_e('Text color for dark mode.', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_link_color_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="text" name="syntekpro_toggle_options[link_color]" value="<?php echo esc_attr($options['link_color']); ?>" class="syntekpro-color-picker">
    <p class="description"><?php esc_html_e('Link color for dark mode.', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_secondary_bg_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="text" name="syntekpro_toggle_options[secondary_bg_color]" value="<?php echo esc_attr($options['secondary_bg_color']); ?>" class="syntekpro-color-picker">
    <p class="description"><?php esc_html_e('Secondary background color (headers, sidebars, widgets).', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_color_scheme_mode_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <div class="syntekpro-color-scheme-modes">
        <label class="syntekpro-mode-option">
            <input type="radio" name="syntekpro_toggle_options[color_scheme_mode]" value="preset" <?php checked($options['color_scheme_mode'], 'preset'); ?>>
            <span class="mode-icon">🎨</span>
            <strong><?php esc_html_e('Presets', 'syntekpro-toggle'); ?></strong>
            <p class="description"><?php esc_html_e('Choose from curated color schemes', 'syntekpro-toggle'); ?></p>
        </label>
        <label class="syntekpro-mode-option">
            <input type="radio" name="syntekpro_toggle_options[color_scheme_mode]" value="custom" <?php checked($options['color_scheme_mode'], 'custom'); ?>>
            <span class="mode-icon">🎛️</span>
            <strong><?php esc_html_e('Custom', 'syntekpro-toggle'); ?></strong>
            <p class="description"><?php esc_html_e('Manually configure all colors', 'syntekpro-toggle'); ?></p>
        </label>
    </div>
    <style>
        .syntekpro-color-scheme-modes { display: flex; gap: 15px; flex-wrap: wrap; }
        .syntekpro-mode-option { border: 2px solid #ddd; padding: 15px; border-radius: 8px; cursor: pointer; flex: 1; min-width: 200px; transition: all 0.3s; }
        .syntekpro-mode-option:hover { border-color: #2271b1; background: #f0f6fc; }
        .syntekpro-mode-option input[type="radio"]:checked + .mode-icon { background: #2271b1; color: white; }
        .syntekpro-mode-option .mode-icon { font-size: 24px; display: inline-block; padding: 8px; border-radius: 4px; margin-bottom: 5px; }
        .syntekpro-mode-option strong { display: block; margin: 5px 0; }
        .syntekpro-mode-option .description { margin: 0; font-size: 12px; color: #666; }
    </style>
    <?php
}

function syntekpro_toggle_color_preset_callback() {
    $options = syntekpro_toggle_get_options();
    
    // DEBUG: Show current values
    $current_mode = isset($options['color_scheme_mode']) ? $options['color_scheme_mode'] : 'NOT SET';
    $current_preset = isset($options['color_preset']) ? $options['color_preset'] : 'NOT SET';
    
    $presets = array(
        'default' => array('name' => 'Default Dark', 'bg' => '#1a1a1a', 'text' => '#ffffff', 'link' => '#6ea8fe', 'secondary' => '#2d2d2d'),
        'midnight' => array('name' => 'Midnight Blue', 'bg' => '#0f1419', 'text' => '#e6edf3', 'link' => '#58a6ff', 'secondary' => '#1c2128'),
        'carbon' => array('name' => 'Carbon Black', 'bg' => '#0d0d0d', 'text' => '#f0f0f0', 'link' => '#4a9eff', 'secondary' => '#1a1a1a'),
        'slate' => array('name' => 'Modern Slate', 'bg' => '#1e1e1e', 'text' => '#d4d4d4', 'link' => '#569cd6', 'secondary' => '#2d2d2d'),
        'ocean' => array('name' => 'Deep Ocean', 'bg' => '#001f3f', 'text' => '#e8f4f8', 'link' => '#7fdbff', 'secondary' => '#002a52'),
        'forest' => array('name' => 'Dark Forest', 'bg' => '#0d1b0d', 'text' => '#e8f5e9', 'link' => '#81c784', 'secondary' => '#1b2f1b'),
        'purple' => array('name' => 'Purple Haze', 'bg' => '#1a0d2e', 'text' => '#f3e5f5', 'link' => '#ce93d8', 'secondary' => '#2e1a3e'),
        'dracula' => array('name' => 'Dracula', 'bg' => '#282a36', 'text' => '#f8f8f2', 'link' => '#8be9fd', 'secondary' => '#44475a'),
        'nord' => array('name' => 'Nord', 'bg' => '#2e3440', 'text' => '#eceff4', 'link' => '#88c0d0', 'secondary' => '#3b4252'),
        'monokai' => array('name' => 'Monokai', 'bg' => '#272822', 'text' => '#f8f8f2', 'link' => '#66d9ef', 'secondary' => '#3e3d32'),
        'solarized' => array('name' => 'Solarized Dark', 'bg' => '#002b36', 'text' => '#839496', 'link' => '#268bd2', 'secondary' => '#073642'),
        'gruvbox' => array('name' => 'Gruvbox', 'bg' => '#282828', 'text' => '#ebdbb2', 'link' => '#83a598', 'secondary' => '#3c3836'),
        'material' => array('name' => 'Material', 'bg' => '#263238', 'text' => '#eeffff', 'link' => '#82aaff', 'secondary' => '#37474f'),
        'one' => array('name' => 'One Dark', 'bg' => '#282c34', 'text' => '#abb2bf', 'link' => '#61afef', 'secondary' => '#21252b'),
        'tokyo' => array('name' => 'Tokyo Night', 'bg' => '#1a1b26', 'text' => '#c0caf5', 'link' => '#7aa2f7', 'secondary' => '#24283b'),
        'ayu' => array('name' => 'Ayu Dark', 'bg' => '#0f1419', 'text' => '#e6e1cf', 'link' => '#59c2ff', 'secondary' => '#191e2a'),
        'cobalt' => array('name' => 'Cobalt', 'bg' => '#193549', 'text' => '#ffffff', 'link' => '#80ffbb', 'secondary' => '#234e6d'),
        'espresso' => array('name' => 'Espresso', 'bg' => '#2a211c', 'text' => '#bdae9d', 'link' => '#6c99bb', 'secondary' => '#392e28'),
        'synthwave' => array('name' => 'Synthwave', 'bg' => '#262335', 'text' => '#f92aad', 'link' => '#72f1b8', 'secondary' => '#382e3c'),
        'rose' => array('name' => 'Rosé Pine', 'bg' => '#191724', 'text' => '#e0def4', 'link' => '#c4a7e7', 'secondary' => '#1f1d2e'),
    );
    ?>
    <div style="background: #f0f6fc; border: 1px solid #0073aa; padding: 10px; margin: 10px 0; border-radius: 4px; font-size: 12px;">
        <strong><?php esc_html_e('Debug Info:', 'syntekpro-toggle'); ?></strong><br>
        <?php esc_html_e('Color Scheme Mode:', 'syntekpro-toggle'); ?> <code><?php echo esc_html($current_mode); ?></code> | 
        <?php esc_html_e('Color Preset:', 'syntekpro-toggle'); ?> <code><?php echo esc_html($current_preset); ?></code>
    </div>
    <div id="preset-container" style="<?php echo $options['color_scheme_mode'] !== 'preset' ? 'display:none;' : ''; ?>">
        <div class="syntekpro-preset-grid">
            <?php foreach ($presets as $key => $preset): ?>
                <label class="syntekpro-preset-card">
                    <input type="radio" name="syntekpro_toggle_options[color_preset]" value="<?php echo esc_attr($key); ?>" <?php checked($options['color_preset'], $key); ?>>
                    
                    <!-- Mini Browser Window Preview -->
                    <div class="preset-window" style="background: <?php echo esc_attr($preset['bg']); ?>;">
                        <!-- Browser Header -->
                        <div class="window-header" style="background: <?php echo esc_attr($preset['secondary']); ?>;">
                            <div class="window-dots">
                                <span style="background: <?php echo esc_attr($preset['link']); ?>; opacity: 0.7;"></span>
                                <span style="background: <?php echo esc_attr($preset['link']); ?>; opacity: 0.5;"></span>
                                <span style="background: <?php echo esc_attr($preset['link']); ?>; opacity: 0.3;"></span>
                            </div>
                        </div>
                        
                        <!-- Content Area -->
                        <div class="window-content">
                            <div class="content-header" style="background: <?php echo esc_attr($preset['secondary']); ?>;"></div>
                            <div class="content-line" style="background: <?php echo esc_attr($preset['text']); ?>; opacity: 0.7;"></div>
                            <div class="content-line" style="background: <?php echo esc_attr($preset['text']); ?>; opacity: 0.6; width: 90%;"></div>
                            <div class="content-line" style="background: <?php echo esc_attr($preset['text']); ?>; opacity: 0.5; width: 95%;"></div>
                            <div class="content-link" style="background: <?php echo esc_attr($preset['link']); ?>;"></div>
                            <div class="content-line" style="background: <?php echo esc_attr($preset['text']); ?>; opacity: 0.6; width: 85%;"></div>
                            <div class="content-line" style="background: <?php echo esc_attr($preset['text']); ?>; opacity: 0.5; width: 75%;"></div>
                        </div>
                        
                        <!-- Theme Name Badge -->
                        <div class="window-footer" style="color: <?php echo esc_attr($preset['text']); ?>;">
                            <?php echo esc_html($preset['name']); ?>
                        </div>
                        
                        <!-- Selected Label -->
                        <div class="preset-selected-label" style="display: none; position: absolute; bottom: 5px; left: 50%; transform: translateX(-50%); background: rgba(34, 113, 177, 0.9); color: white; padding: 3px 8px; border-radius: 4px; font-size: 10px; font-weight: bold; white-space: nowrap;">
                            <?php esc_html_e('SELECTED', 'syntekpro-toggle'); ?>
                        </div>
                    </div>
                </label>
            <?php endforeach; ?>
        </div>
    </div>
    <div style="background: #ecfdf3; border: 1px solid #86efac; padding: 12px; border-radius: 6px; margin-top: 15px; font-size: 13px; color: #166534; display: <?php echo $options['color_scheme_mode'] !== 'preset' ? 'none' : 'block'; ?>">
        <?php if ( syntekpro_toggle_is_plus() ) : ?>
            <strong><?php esc_html_e('✓ Toggle+ Active: All color presets unlocked.', 'syntekpro-toggle'); ?></strong>
        <?php else : ?>
            <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
                <span style="color:#7c5a0b;"><strong><?php esc_html_e('🔒 Get full color preset options in Toggle+', 'syntekpro-toggle'); ?></strong></span>
                <a href="<?php echo esc_url( admin_url('admin.php?page=syntekpro-toggle-license') ); ?>" class="button" style="background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;border:none;padding:5px 14px;font-weight:700;border-radius:5px;text-decoration:none;"><?php esc_html_e('Get Toggle+', 'syntekpro-toggle'); ?></a>
            </div>
        <?php endif; ?>
    </div>
    <style>
        /* Preset Grid */
        .syntekpro-preset-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 12px;
            margin-top: 15px;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }
        
        /* Preset Card */
        .syntekpro-preset-card {
            cursor: pointer;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            background: #fff;
            position: relative;
        }
        
        .syntekpro-preset-card input[type="radio"] {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }
        
        .syntekpro-preset-card:hover {
            border-color: #2271b1;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(34, 113, 177, 0.2);
        }
        
        .syntekpro-preset-card:has(input:checked) {
            border-color: #2271b1;
            border-width: 3px;
            box-shadow: 0 0 0 3px rgba(34, 113, 177, 0.1);
        }
        
        /* Checkmark indicator */
        .syntekpro-preset-card:has(input:checked)::after {
            content: '✓';
            position: absolute;
            top: 8px;
            right: 8px;
            background: #2271b1;
            color: white;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 16px;
            z-index: 10;
            box-shadow: 0 2px 8px rgba(34, 113, 177, 0.4);
        }
        
        /* Selected label */
        .syntekpro-preset-card:has(input:checked) .preset-selected-label {
            display: flex;
        }
        
        /* Window Structure */
        .preset-window {
            padding: 0;
            position: relative;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .window-header {
            padding: 8px 10px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        
        .window-dots {
            display: flex;
            gap: 4px;
        }
        
        .window-dots span {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            display: block;
        }
        
        .window-content {
            padding: 12px 10px;
            min-height: 90px;
        }
        
        .content-header {
            height: 8px;
            width: 75%;
            border-radius: 2px;
            margin-bottom: 10px;
        }
        
        .content-line {
            height: 4px;
            width: 100%;
            border-radius: 2px;
            margin-bottom: 5px;
        }
        
        .content-link {
            height: 4px;
            width: 45%;
            border-radius: 2px;
            margin-bottom: 10px;
        }
        
        .window-footer {
            background: rgba(0,0,0,0.4);
            backdrop-filter: blur(5px);
            padding: 7px 10px;
            text-align: center;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.3px;
            border-top: 1px solid rgba(255,255,255,0.05);
        }
    </style>
    <script>
        jQuery(document).ready(function($) {
            $('input[name="syntekpro_toggle_options[color_scheme_mode]"]').on('change', function() {
                if ($(this).val() === 'preset') {
                    $('#preset-container').slideDown(300);
                } else {
                    $('#preset-container').slideUp(300);
                }
            });
        });
    </script>
    <?php
}

function syntekpro_toggle_brightness_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="range" name="syntekpro_toggle_options[brightness]" value="<?php echo esc_attr($options['brightness']); ?>" min="0" max="200" step="1" oninput="this.nextElementSibling.value = this.value + '%'">
    <output><?php echo esc_attr($options['brightness']); ?>%</output>
    <p class="description"><?php esc_html_e('Adjust overall brightness (0-200%, default: 100%)', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_contrast_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="range" name="syntekpro_toggle_options[contrast]" value="<?php echo esc_attr($options['contrast']); ?>" min="0" max="200" step="1" oninput="this.nextElementSibling.value = this.value + '%'">
    <output><?php echo esc_attr($options['contrast']); ?>%</output>
    <p class="description"><?php esc_html_e('Adjust contrast between colors (0-200%, default: 100%)', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_sepia_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="range" name="syntekpro_toggle_options[sepia]" value="<?php echo esc_attr($options['sepia']); ?>" min="0" max="100" step="1" oninput="this.nextElementSibling.value = this.value + '%'">
    <output><?php echo esc_attr($options['sepia']); ?>%</output>
    <p class="description"><?php esc_html_e('Apply sepia filter for a vintage look (0-100%, default: 0%)', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_grayscale_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="range" name="syntekpro_toggle_options[grayscale]" value="<?php echo esc_attr($options['grayscale']); ?>" min="0" max="100" step="1" oninput="this.nextElementSibling.value = this.value + '%'">
    <output><?php echo esc_attr($options['grayscale']); ?>%</output>
    <p class="description"><?php esc_html_e('Convert to grayscale (0-100%, default: 0%)', 'syntekpro-toggle'); ?></p>
    <?php
}

/**
 * Images Settings Callbacks
 */
function syntekpro_toggle_enable_image_filter_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <label>
        <input type="checkbox" name="syntekpro_toggle_options[enable_image_filter]" value="1" <?php checked($options['enable_image_filter'], '1'); ?>>
        <?php esc_html_e('Apply filters to images in dark mode', 'syntekpro-toggle'); ?>
    </label>
    <p class="description"><?php esc_html_e('Enable automatic filter adjustments for images when dark mode is active.', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_image_brightness_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="range" name="syntekpro_toggle_options[image_brightness]" value="<?php echo esc_attr($options['image_brightness']); ?>" min="0" max="150" step="1" oninput="this.nextElementSibling.value = this.value + '%'">
    <output><?php echo esc_attr($options['image_brightness']); ?>%</output>
    <p class="description"><?php esc_html_e('Adjust image brightness in dark mode (50-150%, default: 100% = normal)', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_image_contrast_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="range" name="syntekpro_toggle_options[image_contrast]" value="<?php echo esc_attr($options['image_contrast']); ?>" min="0" max="200" step="1" oninput="this.nextElementSibling.value = this.value + '%'">
    <output><?php echo esc_attr($options['image_contrast']); ?>%</output>
    <p class="description"><?php esc_html_e('Adjust image contrast in dark mode (50-200%, default: 100% = normal)', 'syntekpro-toggle'); ?></p>
    <?php
}

/**
 * Videos Settings Callbacks
 */
function syntekpro_toggle_enable_video_filter_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <label>
        <input type="checkbox" name="syntekpro_toggle_options[enable_video_filter]" value="1" <?php checked($options['enable_video_filter'], '1'); ?>>
        <?php esc_html_e('Apply filters to videos in dark mode', 'syntekpro-toggle'); ?>
    </label>
    <p class="description"><?php esc_html_e('Enable automatic filter adjustments for embedded videos when dark mode is active.', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_video_brightness_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="range" name="syntekpro_toggle_options[video_brightness]" value="<?php echo esc_attr($options['video_brightness']); ?>" min="0" max="150" step="1" oninput="this.nextElementSibling.value = this.value + '%'">
    <output><?php echo esc_attr($options['video_brightness']); ?>%</output>
    <p class="description"><?php esc_html_e('Adjust video brightness in dark mode (50-150%, default: 100% = normal)', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_video_contrast_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="range" name="syntekpro_toggle_options[video_contrast]" value="<?php echo esc_attr($options['video_contrast']); ?>" min="0" max="200" step="1" oninput="this.nextElementSibling.value = this.value + '%'">
    <output><?php echo esc_attr($options['video_contrast']); ?>%</output>
    <p class="description"><?php esc_html_e('Adjust video contrast in dark mode (50-200%, default: 100% = normal)', 'syntekpro-toggle'); ?></p>
    <?php
}

/**
 * Slides Settings Callbacks
 */
function syntekpro_toggle_enable_slide_filter_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <label>
        <input type="checkbox" name="syntekpro_toggle_options[enable_slide_filter]" value="1" <?php checked($options['enable_slide_filter'], '1'); ?>>
        <?php esc_html_e('Apply filters to slides in dark mode', 'syntekpro-toggle'); ?>
    </label>
    <p class="description"><?php esc_html_e('Enable automatic filter adjustments for presentation slides when dark mode is active.', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_slide_brightness_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="range" name="syntekpro_toggle_options[slide_brightness]" value="<?php echo esc_attr($options['slide_brightness']); ?>" min="0" max="150" step="1" oninput="this.nextElementSibling.value = this.value + '%'">
    <output><?php echo esc_attr($options['slide_brightness']); ?>%</output>
    <p class="description"><?php esc_html_e('Adjust slide brightness in dark mode (50-150%, default: 100% = normal)', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_slide_invert_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <label>
        <input type="checkbox" name="syntekpro_toggle_options[slide_invert]" value="1" <?php checked($options['slide_invert'], '1'); ?>>
        <?php esc_html_e('Invert slide colors', 'syntekpro-toggle'); ?>
    </label>
    <p class="description"><?php esc_html_e('Invert colors on slides for better visibility in dark mode.', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_custom_css_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <textarea name="syntekpro_toggle_options[custom_css]" rows="8" cols="50" class="large-text code"><?php echo esc_textarea($options['custom_css']); ?></textarea>
    <p class="description"><?php esc_html_e('Add custom CSS for dark mode. Will be wrapped in', 'syntekpro-toggle'); ?> <code>html.dark-mode { }</code></p>
    <?php
}

function syntekpro_toggle_transition_speed_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="number" name="syntekpro_toggle_options[transition_speed]" value="<?php echo esc_attr($options['transition_speed']); ?>" min="0" max="2" step="0.1">
    <span><?php esc_html_e('seconds', 'syntekpro-toggle'); ?></span>
    <p class="description"><?php esc_html_e('Color transition speed (0-2 seconds, 0 = instant).', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_analytics_section_callback() {
    echo '<p>' . esc_html__('Configure what analytics data to track about dark mode usage on your site.', 'syntekpro-toggle') . '</p>';
}

function syntekpro_toggle_enable_analytics_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <label>
        <input type="checkbox" name="syntekpro_toggle_options[enable_analytics]" value="1" <?php checked($options['enable_analytics'], '1'); ?>>
        <?php esc_html_e('Enable analytics tracking', 'syntekpro-toggle'); ?>
    </label>
    <p class="description"><?php esc_html_e('Master switch for all analytics tracking. When disabled, no data is collected.', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_analytics_track_toggles_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <label>
        <input type="checkbox" name="syntekpro_toggle_options[analytics_track_toggles]" value="1" <?php checked($options['analytics_track_toggles'], '1'); ?>>
        <?php esc_html_e('Track toggle button clicks', 'syntekpro-toggle'); ?>
    </label>
    <p class="description"><?php esc_html_e('Count how many times users click the dark/light mode toggle button.', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_analytics_track_pageviews_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <label>
        <input type="checkbox" name="syntekpro_toggle_options[analytics_track_pageviews]" value="1" <?php checked($options['analytics_track_pageviews'], '1'); ?>>
        <?php esc_html_e('Track page views', 'syntekpro-toggle'); ?>
    </label>
    <p class="description"><?php esc_html_e('Count total page views where the toggle button is displayed.', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_analytics_track_modes_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <label>
        <input type="checkbox" name="syntekpro_toggle_options[analytics_track_modes]" value="1" <?php checked($options['analytics_track_modes'], '1'); ?>>
        <?php esc_html_e('Track mode preferences', 'syntekpro-toggle'); ?>
    </label>
    <p class="description"><?php esc_html_e('Track whether users prefer dark or light mode.', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_admin_ui_section_callback() {
    echo '<p>' . esc_html__('Control admin UI helpers like dark mode, top bar icon, floating toggle button, and dashboard widget.', 'syntekpro-toggle') . '</p>';
}

function syntekpro_toggle_admin_dark_mode_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <label>
        <input type="checkbox" name="syntekpro_toggle_options[enable_admin_dark_mode]" value="1" <?php checked($options['enable_admin_dark_mode'], '1'); ?>>
        <?php esc_html_e('Enable admin dark mode toggle (per browser)', 'syntekpro-toggle'); ?>
    </label>
    <p class="description"><?php esc_html_e('Adds a dark/light toggle for the WordPress admin UI (state saved in localStorage).', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_admin_bar_icon_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <label>
        <input type="checkbox" name="syntekpro_toggle_options[enable_admin_bar_icon]" value="1" <?php checked($options['enable_admin_bar_icon'], '1'); ?>>
        <?php esc_html_e('Show top bar icon (also triggers admin dark toggle)', 'syntekpro-toggle'); ?>
    </label>
    <p class="description"><?php esc_html_e('Adds a small icon to the WordPress admin bar for quick access.', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_admin_floating_toggle_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <label>
        <input type="checkbox" name="syntekpro_toggle_options[enable_admin_floating_toggle]" value="1" <?php checked($options['enable_admin_floating_toggle'], '1'); ?>>
        <?php esc_html_e('Show floating toggle button in the admin panel', 'syntekpro-toggle'); ?>
    </label>
    <p class="description"><?php esc_html_e('Displays the floating dark mode toggle in the lower-right corner of the WordPress admin area.', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_admin_dashboard_widget_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <label>
        <input type="checkbox" name="syntekpro_toggle_options[enable_dashboard_widget]" value="1" <?php checked($options['enable_dashboard_widget'], '1'); ?>>
        <?php esc_html_e('Show dashboard widget', 'syntekpro-toggle'); ?>
    </label>
    <p class="description"><?php esc_html_e('Displays the Syntekpro Toggle status widget on the WordPress Dashboard.', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_admin_bg_color_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="text" name="syntekpro_toggle_options[admin_bg_color]" value="<?php echo esc_attr($options['admin_bg_color']); ?>" class="syntekpro-color-picker">
    <p class="description"><?php esc_html_e('Background color for admin dark mode.', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_admin_text_color_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="text" name="syntekpro_toggle_options[admin_text_color]" value="<?php echo esc_attr($options['admin_text_color']); ?>" class="syntekpro-color-picker">
    <p class="description"><?php esc_html_e('Primary text color for admin dark mode.', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_admin_accent_color_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="text" name="syntekpro_toggle_options[admin_accent_color]" value="<?php echo esc_attr($options['admin_accent_color']); ?>" class="syntekpro-color-picker">
    <p class="description"><?php esc_html_e('Accent color for buttons and highlights.', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_admin_surface_color_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="text" name="syntekpro_toggle_options[admin_surface_color]" value="<?php echo esc_attr($options['admin_surface_color']); ?>" class="syntekpro-color-picker">
    <p class="description"><?php esc_html_e('Surface color for cards, boxes, and panels.', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_admin_border_color_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="text" name="syntekpro_toggle_options[admin_border_color]" value="<?php echo esc_attr($options['admin_border_color']); ?>" class="syntekpro-color-picker">
    <p class="description"><?php esc_html_e('Border color for admin elements.', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_admin_link_color_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="text" name="syntekpro_toggle_options[admin_link_color]" value="<?php echo esc_attr($options['admin_link_color']); ?>" class="syntekpro-color-picker">
    <p class="description"><?php esc_html_e('Link color for admin dark mode.', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_admin_link_hover_color_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="text" name="syntekpro_toggle_options[admin_link_hover_color]" value="<?php echo esc_attr($options['admin_link_hover_color']); ?>" class="syntekpro-color-picker">
    <p class="description"><?php esc_html_e('Link hover color for admin dark mode.', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_admin_theme_callback() {
    $options = syntekpro_toggle_get_options();
    $themes = array(
        'default' => array('name' => 'Default', 'desc' => 'Classic circular button'),
        'minimal' => array('name' => 'Minimal', 'desc' => 'Clean and simple'),
        'neumorphic' => array('name' => 'Neumorphic', 'desc' => 'Soft UI design'),
        'glassmorphic' => array('name' => 'Glassmorphic', 'desc' => 'Frosted glass effect'),
        'neon' => array('name' => 'Neon', 'desc' => 'Glowing neon style'),
        'gradient' => array('name' => 'Gradient', 'desc' => 'Colorful gradient'),
        'retro' => array('name' => 'Retro', 'desc' => 'Vintage 80s style'),
        'modern' => array('name' => 'Modern', 'desc' => 'Contemporary design'),
        'flat' => array('name' => 'Flat', 'desc' => 'Flat design style'),
        'material' => array('name' => 'Material', 'desc' => 'Google Material Design'),
        'ios' => array('name' => 'iOS Style', 'desc' => 'Apple iOS inspired'),
        'cyberpunk' => array('name' => 'Cyberpunk', 'desc' => 'Futuristic tech'),
        'elegant' => array('name' => 'Elegant', 'desc' => 'Refined and classy'),
        'playful' => array('name' => 'Playful', 'desc' => 'Fun and bouncy'),
        'professional' => array('name' => 'Professional', 'desc' => 'Business style'),
        'square' => array('name' => 'Square', 'desc' => 'Sharp corners'),
        'pill' => array('name' => 'Pill', 'desc' => 'Elongated rounded'),
        'hexagon' => array('name' => 'Hexagon', 'desc' => 'Six-sided shape'),
        'diamond' => array('name' => 'Diamond', 'desc' => 'Rotated square'),
        'morphing' => array('name' => 'Morphing', 'desc' => 'Animated transitions'),
        'soft-shadow' => array('name' => 'Soft Shadow', 'desc' => 'Smoothly elevated'),
        'outline' => array('name' => 'Outline', 'desc' => 'Clean line style'),
        'floating' => array('name' => 'Floating', 'desc' => 'Levitated effect'),
        'glow' => array('name' => 'Glow', 'desc' => 'Pulsing luminance'),
        'brutalist' => array('name' => 'Brutalist', 'desc' => 'Raw and geometric'),
        '3d' => array('name' => '3D', 'desc' => 'Layered depth'),
        'neon-pulse' => array('name' => 'Neon Pulse', 'desc' => 'Animated glow'),
        'aurora' => array('name' => 'Aurora', 'desc' => 'Shifting colors'),
        'hologram' => array('name' => 'Hologram', 'desc' => 'Glass-like shimmer'),
        'vaporwave' => array('name' => 'Vaporwave', 'desc' => 'Retro-futuristic'),
        'aquamorphic' => array('name' => 'Aquamorphic', 'desc' => 'Organic flowing'),
        'sunset' => array('name' => 'Sunset', 'desc' => 'Warm gradients'),
        'minimalist' => array('name' => 'Minimalist', 'desc' => 'Stripped down'),
        'cyber' => array('name' => 'Cyber', 'desc' => 'Terminal style'),
        'gemstone' => array('name' => 'Gemstone', 'desc' => 'Jewel-like facets'),
        'monochrome' => array('name' => 'Monochrome', 'desc' => 'Grayscale tones'),
        'frosted' => array('name' => 'Frosted Ice', 'desc' => 'Frosted glass'),
    );
    ?>
    <div class="syntekpro-admin-theme-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 15px; margin-top: 10px;">
        <?php foreach ($themes as $key => $theme): ?>
            <label class="admin-theme-option" style="cursor: pointer; border: 2px solid #ddd; border-radius: 8px; padding: 15px; transition: all 0.3s; text-align: center;">
                <input type="radio" name="syntekpro_toggle_options[admin_toggle_theme]" value="<?php echo esc_attr($key); ?>" <?php checked($options['admin_toggle_theme'], $key); ?> style="margin-bottom: 10px;">
                <div class="theme-preview syntekpro-theme-<?php echo esc_attr($key); ?>" style="width: 50px; height: 50px; margin: 0 auto 10px; position: relative; display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                    </svg>
                </div>
                <strong style="display: block; margin-bottom: 3px;"><?php echo esc_html($theme['name']); ?></strong>
                <span style="font-size: 11px; color: #666; display: block;"><?php echo esc_html($theme['desc']); ?></span>
            </label>
        <?php endforeach; ?>
    </div>
    <style>
        .admin-theme-option:hover { border-color: #2271b1; background: #f0f6fc; transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .admin-theme-option:has(input[type="radio"]:checked) { border-color: #2271b1; border-width: 3px; background: #f0f6fc; }
        
        /* Theme Preview Styles */
        .syntekpro-theme-default { background: #333; color: #fff; border-radius: 50%; box-shadow: 0 2px 8px rgba(0,0,0,0.3); }
        .syntekpro-theme-minimal { background: #f5f5f5; color: #333; border-radius: 50%; border: 1px solid #ddd; }
        .syntekpro-theme-neumorphic { background: #e0e5ec; color: #333; border-radius: 50%; box-shadow: 8px 8px 16px #a3b1c6, -8px -8px 16px #ffffff; }
        .syntekpro-theme-glassmorphic { background: rgba(255,255,255,0.1); color: #333; border-radius: 50%; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2); box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37); }
        .syntekpro-theme-neon { background: #0a0e27; color: #00ffff; border-radius: 50%; box-shadow: 0 0 20px #00ffff, inset 0 0 20px rgba(0,255,255,0.2); border: 2px solid #00ffff; }
        .syntekpro-theme-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; border-radius: 50%; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4); }
        .syntekpro-theme-retro { background: linear-gradient(45deg, #ff006e, #ffbe0b); color: #fff; border-radius: 8px; box-shadow: 4px 4px 0 rgba(0,0,0,0.3); border: 3px solid #000; }
        .syntekpro-theme-modern { background: #000; color: #fff; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
        .syntekpro-theme-flat { background: #3498db; color: #fff; border-radius: 4px; }
        .syntekpro-theme-material { background: #2196F3; color: #fff; border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,0.2), 0 4px 8px rgba(0,0,0,0.2); }
        .syntekpro-theme-ios { background: linear-gradient(180deg, #f8f9fa 0%, #e9ecef 100%); color: #333; border-radius: 50%; border: 1px solid rgba(0,0,0,0.1); box-shadow: 0 1px 3px rgba(0,0,0,0.12); }
        .syntekpro-theme-cyberpunk { background: linear-gradient(135deg, #f72585, #7209b7, #3a0ca3); color: #00ff41; border-radius: 4px; box-shadow: 0 0 20px rgba(247, 37, 133, 0.6); border: 1px solid #00ff41; }
        .syntekpro-theme-elegant { background: linear-gradient(135deg, #2c3e50, #34495e); color: #ecf0f1; border-radius: 50%; box-shadow: 0 4px 20px rgba(0,0,0,0.3); border: 2px solid rgba(236, 240, 241, 0.1); }
        .syntekpro-theme-playful { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: #fff; border-radius: 50%; animation: playful-bounce 2s infinite; }
        .syntekpro-theme-professional { background: #1a1a2e; color: #eee; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.3); border: 1px solid #16213e; }
        .syntekpro-theme-square { background: #444; color: #fff; border-radius: 4px; box-shadow: 0 4px 8px rgba(0,0,0,0.3); }
        .syntekpro-theme-pill { background: #5e60ce; color: #fff; border-radius: 25px; width: 70px; box-shadow: 0 4px 12px rgba(94, 96, 206, 0.4); }
        .syntekpro-theme-hexagon { background: #ff6b6b; color: #fff; clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%); box-shadow: 0 4px 12px rgba(255, 107, 107, 0.4); }
        .syntekpro-theme-diamond { background: #4ecdc4; color: #fff; transform: rotate(45deg); border-radius: 8px; box-shadow: 0 4px 12px rgba(78, 205, 196, 0.4); }
        .syntekpro-theme-diamond svg { transform: rotate(-45deg); }
        .syntekpro-theme-morphing { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: #fff; border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; box-shadow: 0 4px 15px rgba(250, 112, 154, 0.4); animation: morph-shape 3s infinite; }
        
        /* New Theme Preview Styles */
        .syntekpro-theme-soft-shadow { background: #fff; color: #333; border: 1px solid #f0f0f0; border-radius: 50%; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); }
        .syntekpro-theme-outline { background: transparent; color: #333; border: 3px solid #333; border-radius: 50%; }
        .syntekpro-theme-floating { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; border-radius: 50%; box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3), 0 4px 10px rgba(0, 0, 0, 0.2); transform: translateY(-5px); }
        .syntekpro-theme-glow { background: #00d4ff; color: #000; border-radius: 50%; box-shadow: 0 0 20px #00d4ff, 0 0 40px #00d4ff; }
        .syntekpro-theme-brutalist { background: #1a1a1a; color: #fff; border: 4px solid #fff; border-radius: 0; box-shadow: none; }
        .syntekpro-theme-3d { background: linear-gradient(180deg, #4facfe 0%, #00f2fe 100%); color: #fff; border: 3px solid #0284c7; border-radius: 50%; box-shadow: 0 5px 0 #0284c7, 0 10px 20px rgba(0, 0, 0, 0.3); }
        .syntekpro-theme-neon-pulse { background: #0a0e27; color: #0ff; border: 2px solid #0ff; border-radius: 50%; box-shadow: 0 0 10px #0ff; animation: admin-neon-pulse 2s infinite; }
        .syntekpro-theme-aurora { background: linear-gradient(45deg, #00d4ff, #00ff87, #ff00d4, #00d4ff); background-size: 300% 300%; color: #fff; border-radius: 50%; animation: admin-aurora-shift 6s ease infinite; }
        .syntekpro-theme-hologram { background: rgba(0, 255, 255, 0.1); color: #0ff; border: 2px solid #0ff; border-radius: 50%; box-shadow: 0 0 10px #0ff, inset 0 0 10px rgba(0, 255, 255, 0.3); backdrop-filter: blur(8px); }
        .syntekpro-theme-vaporwave { background: linear-gradient(135deg, #ff006e, #d62839); color: #00ff41; border: 2px solid #00ff41; border-radius: 50%; box-shadow: 0 0 15px rgba(255, 0, 110, 0.5); }
        .syntekpro-theme-aquamorphic { background: linear-gradient(135deg, #0084ff, #00d4ff); color: #fff; border-radius: 30%; box-shadow: 0 8px 30px rgba(0, 132, 255, 0.4); }
        .syntekpro-theme-sunset { background: linear-gradient(135deg, #ff6b35, #f7931e, #fdb833); color: #fff; border-radius: 50%; box-shadow: 0 5px 20px rgba(255, 107, 53, 0.4); }
        .syntekpro-theme-minimalist { background: transparent; color: #333; border: 2px solid #333; border-radius: 50%; box-shadow: none; }
        .syntekpro-theme-cyber { background: #000; color: #0f0; border: 2px solid #0f0; border-radius: 50%; box-shadow: 0 0 5px #0f0; }
        .syntekpro-theme-gemstone { background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); color: #333; border: 3px solid #ff9a56; border-radius: 50%; box-shadow: 0 4px 15px rgba(255, 154, 86, 0.3); }
        .syntekpro-theme-monochrome { background: #808080; color: #fff; border: 2px solid #404040; border-radius: 50%; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); }
        .syntekpro-theme-frosted { background: rgba(255, 255, 255, 0.3); color: #fff; border: 2px solid rgba(255, 255, 255, 0.5); border-radius: 50%; backdrop-filter: blur(12px); box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37); }
        
        @keyframes playful-bounce { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.05) translateY(-3px); } }
        @keyframes morph-shape { 0%, 100% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; } 50% { border-radius: 70% 30% 30% 70% / 70% 70% 30% 30%; } }
        @keyframes admin-neon-pulse { 0%, 100% { box-shadow: 0 0 10px rgba(0, 255, 255, 0.5); } 50% { box-shadow: 0 0 20px rgba(0, 255, 255, 0.8); } }
        @keyframes admin-aurora-shift { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
    </style>
    <p class="description"><?php esc_html_e('Choose theme for the admin floating toggle button.', 'syntekpro-toggle'); ?></p>
    <?php
}

function syntekpro_toggle_admin_color_section_callback() {
    echo '<p>' . esc_html__('Choose color presets for admin dark mode or customize manually.', 'syntekpro-toggle') . '</p>';
}

function syntekpro_toggle_admin_color_scheme_mode_callback() {
    $options = syntekpro_toggle_get_options();
    $admin_mode = isset($options['admin_color_scheme_mode']) ? $options['admin_color_scheme_mode'] : 'preset';
    ?>
    <div class="syntekpro-color-scheme-modes">
        <label class="syntekpro-mode-option">
            <input type="radio" name="syntekpro_toggle_options[admin_color_scheme_mode]" value="preset" <?php checked($admin_mode, 'preset'); ?>>
            <span class="mode-icon">🎨</span>
            <strong><?php esc_html_e('Presets', 'syntekpro-toggle'); ?></strong>
            <p class="description"><?php esc_html_e('Choose from curated admin themes', 'syntekpro-toggle'); ?></p>
        </label>
        <label class="syntekpro-mode-option">
            <input type="radio" name="syntekpro_toggle_options[admin_color_scheme_mode]" value="custom" <?php checked($admin_mode, 'custom'); ?>>
            <span class="mode-icon">🎛️</span>
            <strong><?php esc_html_e('Custom', 'syntekpro-toggle'); ?></strong>
            <p class="description"><?php esc_html_e('Manually configure admin colors', 'syntekpro-toggle'); ?></p>
        </label>
    </div>
    <?php
}

function syntekpro_toggle_admin_color_preset_callback() {
    $options = syntekpro_toggle_get_options();
    $admin_mode = isset($options['admin_color_scheme_mode']) ? $options['admin_color_scheme_mode'] : 'preset';
    $admin_preset = isset($options['admin_color_preset']) ? $options['admin_color_preset'] : 'default';
    $presets = array(
        'default' => array('name' => 'Midnight Pro', 'bg' => '#0f1115', 'text' => '#e7e9ee', 'accent' => '#2563eb', 'surface' => '#191e2a'),
        'nord' => array('name' => 'Nord Admin', 'bg' => '#2e3440', 'text' => '#eceff4', 'accent' => '#88c0d0', 'surface' => '#3b4252'),
        'dracula' => array('name' => 'Dracula Pro', 'bg' => '#282a36', 'text' => '#f8f8f2', 'accent' => '#bd93f9', 'surface' => '#44475a'),
        'carbon' => array('name' => 'Carbon Dark', 'bg' => '#161616', 'text' => '#f4f4f4', 'accent' => '#0f62fe', 'surface' => '#262626'),
        'tokyo' => array('name' => 'Tokyo Night', 'bg' => '#1a1b26', 'text' => '#c0caf5', 'accent' => '#7aa2f7', 'surface' => '#24283b'),
        'monokai' => array('name' => 'Monokai Pro', 'bg' => '#272822', 'text' => '#f8f8f2', 'accent' => '#66d9ef', 'surface' => '#3e3d32'),
        'gruvbox' => array('name' => 'Gruvbox Dark', 'bg' => '#282828', 'text' => '#ebdbb2', 'accent' => '#83a598', 'surface' => '#3c3836'),
        'material' => array('name' => 'Material Dark', 'bg' => '#263238', 'text' => '#eeffff', 'accent' => '#82aaff', 'surface' => '#37474f'),
        'one' => array('name' => 'One Dark Pro', 'bg' => '#282c34', 'text' => '#abb2bf', 'accent' => '#61afef', 'surface' => '#21252b'),
        'ayu' => array('name' => 'Ayu Mirage', 'bg' => '#1f2430', 'text' => '#cbccc6', 'accent' => '#73d0ff', 'surface' => '#232834'),
        'solarized' => array('name' => 'Solarized Dark', 'bg' => '#002b36', 'text' => '#839496', 'accent' => '#268bd2', 'surface' => '#073642'),
        'ocean' => array('name' => 'Deep Ocean', 'bg' => '#001f3f', 'text' => '#e8f4f8', 'accent' => '#7fdbff', 'surface' => '#002a52'),
        'forest' => array('name' => 'Dark Forest', 'bg' => '#0d1b0d', 'text' => '#e8f5e9', 'accent' => '#81c784', 'surface' => '#1b2f1b'),
        'purple' => array('name' => 'Purple Haze', 'bg' => '#1a0d2e', 'text' => '#f3e5f5', 'accent' => '#ce93d8', 'surface' => '#2e1a3e'),
        'slate' => array('name' => 'Modern Slate', 'bg' => '#1e1e1e', 'text' => '#d4d4d4', 'accent' => '#569cd6', 'surface' => '#2d2d2d'),
    );
    ?>
    <div id="admin-preset-container" style="<?php echo $admin_mode !== 'preset' ? 'display:none;' : ''; ?>">
        <div class="syntekpro-admin-preset-grid">
            <?php foreach ($presets as $key => $preset): ?>
                <label class="syntekpro-admin-preset-card">
                    <input type="radio" name="syntekpro_toggle_options[admin_color_preset]" value="<?php echo esc_attr($key); ?>" <?php checked($admin_preset, $key); ?>>
                    
                    <!-- Admin Dashboard Preview -->
                    <div class="admin-preset-window" style="background: <?php echo esc_attr($preset['bg']); ?>;">
                        <!-- Dashboard Header -->
                        <div class="admin-window-header" style="background: <?php echo esc_attr($preset['surface']); ?>;">
                            <div class="admin-window-dots">
                                <span style="background: <?php echo esc_attr($preset['accent']); ?>;"></span>
                                <span style="background: <?php echo esc_attr($preset['accent']); ?>; opacity: 0.6;"></span>
                                <span style="background: <?php echo esc_attr($preset['text']); ?>; opacity: 0.4;"></span>
                            </div>
                        </div>
                        
                        <!-- Dashboard Content -->
                        <div class="admin-window-content">
                            <div class="admin-content-box" style="background: <?php echo esc_attr($preset['surface']); ?>;">
                                <div class="admin-content-header" style="background: <?php echo esc_attr($preset['accent']); ?>; opacity: 0.3;"></div>
                                <div class="admin-content-line" style="background: <?php echo esc_attr($preset['text']); ?>; opacity: 0.5;"></div>
                                <div class="admin-content-line" style="background: <?php echo esc_attr($preset['text']); ?>; opacity: 0.4; width: 85%;"></div>
                            </div>
                            <div class="admin-content-accent" style="background: <?php echo esc_attr($preset['accent']); ?>;"></div>
                        </div>
                        
                        <!-- Theme Name -->
                        <div class="admin-window-footer" style="color: <?php echo esc_attr($preset['text']); ?>;">
                            <?php echo esc_html($preset['name']); ?>
                        </div>
                        
                        <!-- Selected Label -->
                        <div class="admin-preset-selected-label" style="display: none; position: absolute; bottom: 5px; left: 50%; transform: translateX(-50%); background: rgba(37, 99, 235, 0.9); color: white; padding: 3px 8px; border-radius: 4px; font-size: 10px; font-weight: bold; white-space: nowrap;">
                            SELECTED
                        </div>
                    </div>
                </label>
            <?php endforeach; ?>
        </div>
    </div>
    <style>
        /* Admin Preset Grid */
        .syntekpro-admin-preset-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 12px;
            margin-top: 15px;
            padding: 15px;
            background: linear-gradient(135deg, #f5f7fa 0%, #f9fafb 100%);
            border-radius: 10px;
            border: 1px solid #e0e4e8;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        /* Admin Preset Card */
        .syntekpro-admin-preset-card {
            cursor: pointer;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            background: #fff;
            position: relative;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .syntekpro-admin-preset-card input[type="radio"] {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }
        
        .syntekpro-admin-preset-card:hover {
            border-color: #3b82f6;
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 12px 24px rgba(59, 130, 246, 0.25);
        }
        
        .syntekpro-admin-preset-card:has(input:checked) {
            border-color: #2563eb;
            border-width: 3px;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
        }
        
        /* Admin checkmark indicator */
        .syntekpro-admin-preset-card:has(input:checked)::after {
            content: '✓';
            position: absolute;
            top: 8px;
            right: 8px;
            background: #2563eb;
            color: white;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 15px;
            z-index: 10;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.4);
        }
        
        /* Admin selected label */
        .syntekpro-admin-preset-card:has(input:checked) .admin-preset-selected-label {
            display: flex;
        }
        
        /* Admin Window Structure */
        .admin-preset-window {
            padding: 0;
            position: relative;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .admin-window-header {
            padding: 8px 10px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            display: flex;
            align-items: center;
        }
        
        .admin-window-dots {
            display: flex;
            gap: 4px;
        }
        
        .admin-window-dots span {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            display: block;
        }
        
        .admin-window-content {
            padding: 10px;
            min-height: 75px;
        }
        
        .admin-content-box {
            padding: 8px;
            border-radius: 4px;
            margin-bottom: 8px;
        }
        
        .admin-content-header {
            height: 6px;
            width: 60%;
            border-radius: 2px;
            margin-bottom: 6px;
        }
        
        .admin-content-line {
            height: 3px;
            width: 100%;
            border-radius: 2px;
            margin-bottom: 4px;
        }
        
        .admin-content-accent {
            height: 6px;
            width: 40%;
            border-radius: 2px;
        }
        
        .admin-window-footer {
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(8px);
            padding: 6px 8px;
            text-align: center;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
    </style>
    <script>
        jQuery(document).ready(function($) {
            $('input[name="syntekpro_toggle_options[admin_color_scheme_mode]"]').on('change', function() {
                if ($(this).val() === 'preset') {
                    $('#admin-preset-container').slideDown(300);
                } else {
                    $('#admin-preset-container').slideUp(300);
                }
            });
        });
    </script>
    <?php
}

/**
 * Shared page header
 */
function syntekpro_toggle_page_header($page_title = '') {
    $options = syntekpro_toggle_get_options();
    if ($page_title === '') {
        $page_title = __('Toggle Settings', 'syntekpro-toggle');
    }
    
    // Logo.
    $logo_path  = 'assets/img/syntekpro-toggle-logo%20New.png';
    $logo_alt   = __('Syntekpro Toggle', 'syntekpro-toggle');
    $logo_class = 'syntekpro-header-logo';
    ?>
    <div class="wrap syntekpro-toggle-admin">
        <!-- Header -->
        <div class="syntekpro-header">
            <img src="<?php echo esc_url(SYNTEKPRO_TOGGLE_PLUGIN_URL . $logo_path); ?>" alt="<?php echo esc_attr($logo_alt); ?>" class="<?php echo esc_attr($logo_class); ?>">
            <div style="display:flex;align-items:center;gap:12px;position:absolute;right:30px;">
                <div class="syntekpro-header-version">Version <?php echo esc_html(SYNTEKPRO_TOGGLE_VERSION); ?></div>
                <?php if ( ! syntekpro_toggle_is_plus() ) : ?>
                <a href="<?php echo esc_url( admin_url('admin.php?page=syntekpro-toggle-license') ); ?>" style="display:inline-block;background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;font-size:12px;font-weight:700;padding:4px 14px;border-radius:5px;text-decoration:none;letter-spacing:0.3px;white-space:nowrap;">⭐ <?php esc_html_e('Get Toggle+', 'syntekpro-toggle'); ?></a>
                <?php else : ?>
                <span style="display:inline-block;background:#46b450;color:#fff;font-size:12px;font-weight:700;padding:4px 14px;border-radius:5px;letter-spacing:0.3px;">✓ <?php esc_html_e('Toggle+ Active', 'syntekpro-toggle'); ?></span>
                <?php endif; ?>
            </div>
        </div>
        <h1 style="text-align: center; margin: 30px 0; color: #000000; font-weight: 700;"><?php echo esc_html($page_title); ?></h1>
    <?php
}

/**
 * Shared page footer
 */
function syntekpro_toggle_page_footer() {
    ?>
    </div>
    <?php
}

/**
 * Frontend Settings Page - DEPRECATED
 * Kept for backward compatibility - functionality moved to Mode Settings
 */
function syntekpro_toggle_frontend_page() {
    wp_safe_redirect(admin_url('admin.php?page=syntekpro-toggle'));
    exit;
}

/**
 * Admin UI Settings Page - DEPRECATED
 * Kept for backward compatibility - functionality moved to Mode Settings
 */
function syntekpro_toggle_admin_ui_page() {
    wp_safe_redirect(admin_url('admin.php?page=syntekpro-toggle'));
    exit;
}
/**
 * Settings Page - DEPRECATED
 * Kept for backward compatibility but no longer used in menu
 */
function syntekpro_toggle_settings_page() {
    // Redirect to Mode Settings page
    wp_safe_redirect(admin_url('admin.php?page=syntekpro-toggle'));
    exit;
}

/**
 * Dashboard widget
 */
function syntekpro_toggle_dashboard_widget() {
    $options = syntekpro_toggle_get_options();
    if (!isset($options['enable_dashboard_widget']) || $options['enable_dashboard_widget'] !== '1') {
        return;
    }
    wp_add_dashboard_widget(
        'syntekpro_toggle_widget',
        '<img src="' . esc_url(SYNTEKPRO_TOGGLE_PLUGIN_URL . 'assets/img/Syntekpro%20Toggle%20%20icon%20Grey%20New.png') . '" style="width:16px;height:16px;vertical-align:middle;margin-right:5px;"> Toggle - Dark Mode',
        'syntekpro_toggle_dashboard_widget_content'
    );
}

add_action('wp_dashboard_setup', 'syntekpro_toggle_dashboard_widget');

/**
 * Dashboard widget content
 */
function syntekpro_toggle_dashboard_widget_content() {
    $options = syntekpro_toggle_get_options();
    if (!is_array($options)) {
        $options = syntekpro_toggle_get_default_options();
    }
    
    // Get analytics data
    $analytics = syntekpro_toggle_get_analytics() ;
    ?>
    <div class="syntekpro-dashboard-widget">
        <div class="syntekpro-widget-stats">
            <div class="syntekpro-widget-stat">
                <span class="dashicons dashicons-admin-appearance"></span>
                <strong><?php esc_html_e('Mode:', 'syntekpro-toggle'); ?></strong> <?php echo esc_html(ucfirst($options['default_mode'])); ?>
            </div>
            <div class="syntekpro-widget-stat">
                <span class="dashicons dashicons-visibility"></span>
                <strong><?php esc_html_e('Status:', 'syntekpro-toggle'); ?></strong> <?php echo $options['enable_toggle'] === '1' ? '<span style="color:#46b450;">' . esc_html__('Active', 'syntekpro-toggle') . '</span>' : '<span style="color:#dc3232;">' . esc_html__('Inactive', 'syntekpro-toggle') . '</span>'; ?>
            </div>
            <div class="syntekpro-widget-stat">
                <span class="dashicons dashicons-location-alt"></span>
                <strong><?php esc_html_e('Position:', 'syntekpro-toggle'); ?></strong> <?php echo esc_html(ucwords(str_replace('-', ' ', $options['button_position']))); ?>
            </div>
        </div>
        
        <?php if ($options['enable_analytics'] === '1'): ?>
        <div class="syntekpro-widget-analytics">
            <h4 style="margin: 15px 0 10px 0; padding-top: 15px; border-top: 1px solid #f0f0f1;"><?php esc_html_e('📊 Analytics Overview', 'syntekpro-toggle'); ?></h4>
            <div class="syntekpro-widget-analytics-grid">
                <div class="analytics-mini-card">
                    <span class="dashicons dashicons-chart-line" style="color: #667eea;"></span>
                    <div>
                        <strong><?php echo number_format($analytics['total_toggles']); ?></strong>
                        <small><?php esc_html_e('Toggle Clicks', 'syntekpro-toggle'); ?></small>
                    </div>
                </div>
                <div class="analytics-mini-card">
                    <span class="dashicons dashicons-visibility" style="color: #f59e0b;"></span>
                    <div>
                        <strong><?php echo number_format($analytics['page_views']); ?></strong>
                        <small><?php esc_html_e('Page Views', 'syntekpro-toggle'); ?></small>
                    </div>
                </div>
                <div class="analytics-mini-card">
                    <span class="dashicons dashicons-admin-appearance" style="color: #667eea;"></span>
                    <div>
                        <strong><?php echo number_format($analytics['dark_mode_count']); ?></strong>
                        <small><?php esc_html_e('Dark Mode', 'syntekpro-toggle'); ?></small>
                    </div>
                </div>
                <div class="analytics-mini-card">
                    <span class="dashicons dashicons-lightbulb" style="color: #f59e0b;"></span>
                    <div>
                        <strong><?php echo number_format($analytics['light_mode_count']); ?></strong>
                        <small><?php esc_html_e('Light Mode', 'syntekpro-toggle'); ?></small>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="syntekpro-widget-actions">
            <a href="<?php echo esc_url(admin_url('admin.php?page=syntekpro-toggle')); ?>" class="button button-primary">
                <span class="dashicons dashicons-admin-settings"></span> <?php esc_html_e('Settings', 'syntekpro-toggle'); ?>
            </a>
            <?php if ($options['enable_analytics'] === '1'): ?>
            <a href="<?php echo esc_url(admin_url('admin.php?page=syntekpro-toggle-analytics')); ?>" class="button button-secondary">
                <span class="dashicons dashicons-chart-bar"></span> <?php esc_html_e('Analytics', 'syntekpro-toggle'); ?>
            </a>
            <?php endif; ?>
            <a href="<?php echo esc_url(admin_url('admin.php?page=syntekpro-toggle-options')); ?>" class="button button-secondary">
                <span class="dashicons dashicons-admin-generic"></span> <?php esc_html_e('Options', 'syntekpro-toggle'); ?>
            </a>
            <a href="<?php echo esc_url(home_url()); ?>" class="button button-secondary" target="_blank">
                <span class="dashicons dashicons-external"></span> <?php esc_html_e('View Site', 'syntekpro-toggle'); ?>
            </a>
        </div>
        
        <style>
            .syntekpro-dashboard-widget {
                padding: 10px 0;
            }
            .syntekpro-widget-stats {
                margin-bottom: 15px;
            }
            .syntekpro-widget-stat {
                padding: 8px 0;
                border-bottom: 1px solid #f0f0f1;
            }
            .syntekpro-widget-stat:last-child {
                border-bottom: none;
            }
            .syntekpro-widget-stat .dashicons {
                color: #2271b1;
                margin-right: 5px;
            }
            .syntekpro-widget-analytics-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
                margin-bottom: 15px;
            }
            .analytics-mini-card {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 10px;
                background: #f9f9f9;
                border-radius: 6px;
                border-left: 3px solid #667eea;
            }
            .analytics-mini-card .dashicons {
                font-size: 24px;
                width: 24px;
                height: 24px;
            }
            .analytics-mini-card strong {
                display: block;
                font-size: 16px;
                line-height: 1.2;
            }
            .analytics-mini-card small {
                display: block;
                font-size: 11px;
                color: #666;
                line-height: 1.2;
            }
            .syntekpro-widget-actions {
                display: flex;
                gap: 8px;
                flex-wrap: wrap;
            }
            .syntekpro-widget-actions .button {
                flex: 1;
                text-align: center;
                min-width: 100px;
            }
            .syntekpro-widget-actions .dashicons {
                font-size: 14px;
                height: 14px;
                width: 14px;
                vertical-align: middle;
                margin-right: 3px;
            }
        </style>
    </div>
    <?php
}

/**
 * Enqueue admin scripts and styles
 */
function syntekpro_toggle_admin_enqueue_scripts($hook) {
    // Check if we're on any Syntekpro Toggle admin page by examining the page slug
    $page = isset($_GET['page']) ? sanitize_key((string) wp_unslash($_GET['page'])) : '';
    if (strpos($page, 'syntekpro-toggle') !== 0 && $hook !== 'toplevel_page_syntekpro-toggle') {
        return;
    }
    
    // WordPress color picker
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');
    wp_enqueue_media();

    $admin_css_version = file_exists(SYNTEKPRO_TOGGLE_PLUGIN_DIR . 'admin/css/admin.css')
        ? (string) filemtime(SYNTEKPRO_TOGGLE_PLUGIN_DIR . 'admin/css/admin.css')
        : SYNTEKPRO_TOGGLE_VERSION;

    $admin_about_css_version = file_exists(SYNTEKPRO_TOGGLE_PLUGIN_DIR . 'admin/css/admin-about.css')
        ? (string) filemtime(SYNTEKPRO_TOGGLE_PLUGIN_DIR . 'admin/css/admin-about.css')
        : SYNTEKPRO_TOGGLE_VERSION;

    $admin_js_version = file_exists(SYNTEKPRO_TOGGLE_PLUGIN_DIR . 'admin/js/admin.js')
        ? (string) filemtime(SYNTEKPRO_TOGGLE_PLUGIN_DIR . 'admin/js/admin.js')
        : SYNTEKPRO_TOGGLE_VERSION;
    
    // Admin CSS
    wp_enqueue_style(
        'syntekpro-toggle-admin',
        SYNTEKPRO_TOGGLE_PLUGIN_URL . 'admin/css/admin.css',
        array(),
        $admin_css_version
    );
    
    // Admin About & Additional Styles
    wp_enqueue_style(
        'syntekpro-toggle-admin-about',
        SYNTEKPRO_TOGGLE_PLUGIN_URL . 'admin/css/admin-about.css',
        array(),
        $admin_about_css_version
    );
    
    // Admin JS
    wp_enqueue_script(
        'syntekpro-toggle-admin',
        SYNTEKPRO_TOGGLE_PLUGIN_URL . 'admin/js/admin.js',
        array('jquery', 'wp-color-picker'),
        $admin_js_version,
        true
    );
}
add_action('admin_enqueue_scripts', 'syntekpro_toggle_admin_enqueue_scripts');

/**
 * Settings Combined Page - kept for backward redirect compatibility
 */
function syntekpro_toggle_settings_combined_page() {
    wp_safe_redirect(admin_url('admin.php?page=syntekpro-toggle'));
    exit;
}

/**
 * Helper: render a SyntekPro Toggle+ badge for premium features
 */
function syntekpro_toggle_plus_badge() {
    return '<a href="' . esc_url( admin_url( 'admin.php?page=syntekpro-toggle-license' ) ) . '" style="text-decoration:none;"><span style="display:inline-block;background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;font-size:10px;font-weight:700;padding:2px 7px;border-radius:4px;vertical-align:middle;margin-left:6px;letter-spacing:0.5px;cursor:pointer;">Toggle+</span></a>';
}

/**
 * Check if the current site has an active Toggle+ license.
 *
 * @return bool
 */
function syntekpro_toggle_is_plus() {
    return get_option( 'syntekpro_toggle_license_status', 'inactive' ) === 'active';
}

/**
 * Contact the Syntekpro license server and return the result array.
 *
 * @param string $license_key Raw license key (will be sanitized internally).
 * @param string $action      'activate' or 'deactivate'.
 * @return array { valid: bool, message: string, expiry?: string }
 */
function syntekpro_toggle_call_license_api( $license_key, $action = 'activate' ) {
    $license_key = sanitize_text_field( $license_key );

    $response = wp_remote_post(
        'https://license.syntekpro.com/api/v1/toggle/' . $action,
        array(
            'body'    => array(
                'license_key' => $license_key,
                'site_url'    => get_site_url(),
            ),
            'timeout'   => 15,
            'sslverify' => true,
        )
    );

    if ( is_wp_error( $response ) ) {
        return array( 'valid' => false, 'message' => $response->get_error_message() );
    }

    $http_code = wp_remote_retrieve_response_code( $response );
    $body      = json_decode( wp_remote_retrieve_body( $response ), true );

    if ( ! is_array( $body ) ) {
        return array( 'valid' => false, 'message' => __( 'Unexpected response from license server.', 'syntekpro-toggle' ) );
    }

    // Treat any 2xx with valid=true as success.
    $valid = ! empty( $body['valid'] ) && $http_code >= 200 && $http_code < 300;
    return array(
        'valid'   => $valid,
        'message' => isset( $body['message'] ) ? sanitize_text_field( $body['message'] ) : '',
        'expiry'  => isset( $body['expiry'] )  ? sanitize_text_field( $body['expiry'] )  : '',
    );
}

/* ── AJAX: Activate license ───────────────────────────────────────────── */
add_action( 'wp_ajax_syntekpro_toggle_activate_license', 'syntekpro_toggle_ajax_activate_license' );
function syntekpro_toggle_ajax_activate_license() {
    check_ajax_referer( 'syntekpro_license_nonce', 'nonce' );

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( __( 'You do not have permission to do this.', 'syntekpro-toggle' ) );
    }

    $key = isset( $_POST['license_key'] ) ? sanitize_text_field( wp_unslash( $_POST['license_key'] ) ) : '';
    if ( empty( $key ) ) {
        wp_send_json_error( __( 'Please enter a license key.', 'syntekpro-toggle' ) );
    }

    $result = syntekpro_toggle_call_license_api( $key, 'activate' );

    if ( ! empty( $result['valid'] ) ) {
        update_option( 'syntekpro_toggle_license_key',    $key );
        update_option( 'syntekpro_toggle_license_status', 'active' );
        update_option( 'syntekpro_toggle_license_expiry', $result['expiry'] );
        wp_send_json_success( array( 'message' => __( '✓ License activated! Enjoy Toggle+.', 'syntekpro-toggle' ) ) );
    } else {
        update_option( 'syntekpro_toggle_license_status', 'inactive' );
        wp_send_json_error( $result['message'] ?: __( 'Invalid license key. Please check and try again.', 'syntekpro-toggle' ) );
    }
}

/* ── AJAX: Deactivate license ─────────────────────────────────────────── */
add_action( 'wp_ajax_syntekpro_toggle_deactivate_license', 'syntekpro_toggle_ajax_deactivate_license' );
function syntekpro_toggle_ajax_deactivate_license() {
    check_ajax_referer( 'syntekpro_license_nonce', 'nonce' );

    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( __( 'You do not have permission to do this.', 'syntekpro-toggle' ) );
    }

    $key = get_option( 'syntekpro_toggle_license_key', '' );
    if ( ! empty( $key ) ) {
        syntekpro_toggle_call_license_api( $key, 'deactivate' );
    }

    delete_option( 'syntekpro_toggle_license_key' );
    update_option( 'syntekpro_toggle_license_status', 'inactive' );
    update_option( 'syntekpro_toggle_license_expiry', '' );

    wp_send_json_success( array( 'message' => __( 'License deactivated on this site.', 'syntekpro-toggle' ) ) );
}

/**
 * Frontend Settings Page
 * Contains: front-end theme settings + front-end button toggle options
 * First 3 fields are free; remaining fields are labelled SyntekPro Toggle+
 */
function syntekpro_toggle_frontend_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (syntekpro_toggle_has_settings_updated_flag()) {
        add_settings_error('syntekpro_toggle_messages', 'syntekpro_toggle_message', __('✓ Frontend settings saved successfully!', 'syntekpro-toggle'), 'updated');
    }
    settings_errors('syntekpro_toggle_messages');
    syntekpro_toggle_page_header(__('Frontend Settings', 'syntekpro-toggle'));
    ?>
    <style>
    .syntekpro-free-badge { display:inline-block; background:#46b450; color:#fff; font-size:10px; font-weight:700; padding:2px 7px; border-radius:4px; vertical-align:middle; margin-left:6px; letter-spacing:.5px; }
    </style>
    <div class="syntekpro-content-wrapper syntekpro-frontend-layout" style="display:flex; gap:24px; margin-top:20px;">
        <div class="syntekpro-sidebar-nav syntekpro-frontend-sidebar" style="width:260px; flex-shrink:0;">
            <div class="syntekpro-nav-section">
                <a href="#" class="syntekpro-nav-item active" data-section="frontend-toggle-button"><span class="dashicons dashicons-button"></span> <?php esc_html_e('Toggle Button', 'syntekpro-toggle'); ?> <span class="syntekpro-free-badge"><?php esc_html_e('FREE', 'syntekpro-toggle'); ?></span></a>
                <a href="#" class="syntekpro-nav-item" data-section="frontend-advanced-button"><span class="dashicons dashicons-admin-appearance"></span> <?php esc_html_e('Advanced Button', 'syntekpro-toggle'); ?> <?php echo wp_kses_post(syntekpro_toggle_plus_badge()); ?></a>
                <a href="#" class="syntekpro-nav-item" data-section="frontend-colors"><span class="dashicons dashicons-art"></span> <?php esc_html_e('Color Scheme', 'syntekpro-toggle'); ?> <?php echo wp_kses_post(syntekpro_toggle_plus_badge()); ?></a>
                <a href="#" class="syntekpro-nav-item" data-section="frontend-adjustments"><span class="dashicons dashicons-image-filter"></span> <?php esc_html_e('Adjustments', 'syntekpro-toggle'); ?> <?php echo wp_kses_post(syntekpro_toggle_plus_badge()); ?></a>
            </div>
        </div>
        <div class="syntekpro-main-content syntekpro-frontend-main" style="flex:1;">
            <form action="options.php" method="post">
                <?php settings_fields('syntekpro_toggle_settings'); ?>

                <div class="syntekpro-section-panel active" id="section-frontend-toggle-button" data-section="frontend-toggle-button">
                    <h2>🎛️ <?php esc_html_e('Toggle Button Options', 'syntekpro-toggle'); ?> <span class="syntekpro-free-badge"><?php esc_html_e('FREE', 'syntekpro-toggle'); ?></span></h2>
                    <div class="syntekpro-section-body" style="background:#fff; border:1px solid #ccc; border-radius:8px; padding:20px;">
                        <p style="color:#666;font-size:13px;margin-top:0;"><?php esc_html_e('Core toggle button controls — available to all users.', 'syntekpro-toggle'); ?></p>
                        <?php syntekpro_toggle_render_field('default_mode', 'syntekpro-toggle-frontend-general', 'syntekpro_toggle_general_section'); ?>
                        <?php syntekpro_toggle_render_field('enable_toggle', 'syntekpro-toggle-frontend-general', 'syntekpro_toggle_general_section'); ?>
                        <?php syntekpro_toggle_render_field('button_position', 'syntekpro-toggle-frontend-general', 'syntekpro_toggle_general_section'); ?>
                    </div>
                </div>

                <div class="syntekpro-section-panel" id="section-frontend-advanced-button" data-section="frontend-advanced-button">
                    <h2>🎨 <?php esc_html_e('Advanced Button Options', 'syntekpro-toggle'); ?> <?php echo wp_kses_post(syntekpro_toggle_plus_badge()); ?></h2>
                    <div class="syntekpro-section-body" style="background:#fff; border:1px solid #ccc; border-radius:8px; padding:20px;">
                        <p style="color:#666;font-size:13px;margin-top:0;"><?php esc_html_e('Customise button size, appearance, icon, shape, and animation style.', 'syntekpro-toggle'); ?></p>
                        <?php syntekpro_toggle_render_field('button_size', 'syntekpro-toggle-frontend-general', 'syntekpro_toggle_general_section'); ?>
                        <?php syntekpro_toggle_render_field('custom_button_icon_url', 'syntekpro-toggle-frontend-general', 'syntekpro_toggle_general_section'); ?>
                        <?php syntekpro_toggle_render_field('toggle_theme', 'syntekpro-toggle-frontend-general', 'syntekpro_toggle_general_section'); ?>
                        <?php syntekpro_toggle_render_field('button_shape', 'syntekpro-toggle-frontend-general', 'syntekpro_toggle_general_section'); ?>
                        <?php syntekpro_toggle_render_field('button_animation', 'syntekpro-toggle-frontend-general', 'syntekpro_toggle_general_section'); ?>
                        <?php syntekpro_toggle_render_field('button_bg_style', 'syntekpro-toggle-frontend-general', 'syntekpro_toggle_general_section'); ?>
                    </div>
                </div>

                <div class="syntekpro-section-panel" id="section-frontend-colors" data-section="frontend-colors">
                    <h2>🎨 <?php esc_html_e('Dark Mode Color Scheme', 'syntekpro-toggle'); ?> <?php echo wp_kses_post(syntekpro_toggle_plus_badge()); ?></h2>
                    <div class="syntekpro-section-body" style="background:#fff; border:1px solid #ccc; border-radius:8px; padding:20px;">
                        <p style="color:#666;font-size:13px;margin-top:0;"><?php esc_html_e('Select color presets or define custom dark mode colours.', 'syntekpro-toggle'); ?></p>
                        <?php do_settings_sections('syntekpro-toggle-frontend-colors'); ?>
                    </div>
                </div>

                <div class="syntekpro-section-panel" id="section-frontend-adjustments" data-section="frontend-adjustments">
                    <h2>🎚️ <?php esc_html_e('Color Adjustments', 'syntekpro-toggle'); ?> <?php echo wp_kses_post(syntekpro_toggle_plus_badge()); ?></h2>
                    <div class="syntekpro-section-body" style="background:#fff; border:1px solid #ccc; border-radius:8px; padding:20px;">
                        <p style="color:#666;font-size:13px;margin-top:0;"><?php esc_html_e('Fine-tune brightness, contrast, sepia and grayscale filters applied in dark mode.', 'syntekpro-toggle'); ?></p>
                        <?php do_settings_sections('syntekpro-toggle-frontend-adjustments'); ?>
                    </div>
                </div>

                <?php submit_button(__('Save Frontend Settings', 'syntekpro-toggle')); ?>
            </form>
        </div>
    </div>
    <?php
    syntekpro_toggle_page_footer();
}

/**
 * Render a single settings field by looking up global $wp_settings_fields.
 *
 * @param string $field_id  Field ID as registered with add_settings_field().
 * @param string $page      Settings page slug.
 * @param string $section   Section ID.
 */
function syntekpro_toggle_render_field($field_id, $page, $section) {
    global $wp_settings_fields;
    if (!isset($wp_settings_fields[$page][$section][$field_id])) {
        return;
    }
    $field = $wp_settings_fields[$page][$section][$field_id];
    echo '<table class="form-table" role="presentation"><tbody><tr>';
    echo '<th scope="row">';
    if (!empty($field['args']['label_for'])) {
        printf('<label for="%s">%s</label>', esc_attr($field['args']['label_for']), wp_kses_post($field['title']));
    } else {
        echo wp_kses_post($field['title']);
    }
    echo '</th><td>';
    call_user_func($field['callback'], $field['args']);
    echo '</td></tr></tbody></table>';
}

/**
 * Admin Panel Settings Page
 * Contains: backend theme settings + admin button toggle settings
 * First 3 fields are free; remaining fields are labelled SyntekPro Toggle+
 */
function syntekpro_toggle_admin_panel_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (syntekpro_toggle_has_settings_updated_flag()) {
        add_settings_error('syntekpro_toggle_messages', 'syntekpro_toggle_message', __('✓ Admin Panel settings saved successfully!', 'syntekpro-toggle'), 'updated');
    }
    settings_errors('syntekpro_toggle_messages');
    syntekpro_toggle_page_header(__('Admin Panel Settings', 'syntekpro-toggle'));
    ?>
    <div class="syntekpro-content-wrapper syntekpro-admin-panel-layout" style="display:flex; gap:24px; margin-top:20px;">
        <div class="syntekpro-sidebar-nav syntekpro-admin-panel-sidebar" style="width:260px; flex-shrink:0;">
            <div class="syntekpro-nav-section">
                <a href="#" class="syntekpro-nav-item active" data-section="admin-dark-mode"><span class="dashicons dashicons-moon"></span> <?php esc_html_e('Admin Dark Mode', 'syntekpro-toggle'); ?> <span class="syntekpro-free-badge"><?php esc_html_e('FREE', 'syntekpro-toggle'); ?></span></a>
                <a href="#" class="syntekpro-nav-item" data-section="admin-advanced-button"><span class="dashicons dashicons-admin-generic"></span> <?php esc_html_e('Advanced Toggle', 'syntekpro-toggle'); ?> <?php echo wp_kses_post(syntekpro_toggle_plus_badge()); ?></a>
                <a href="#" class="syntekpro-nav-item" data-section="admin-color-scheme"><span class="dashicons dashicons-art"></span> <?php esc_html_e('Admin Color Scheme', 'syntekpro-toggle'); ?> <?php echo wp_kses_post(syntekpro_toggle_plus_badge()); ?></a>
            </div>
        </div>
        <div class="syntekpro-main-content syntekpro-admin-panel-main" style="flex:1;">
            <form action="options.php" method="post">
                <?php settings_fields('syntekpro_toggle_settings'); ?>
                <input type="hidden" name="syntekpro_toggle_options[_admin_ui_sentinel]" value="1">

                <div class="syntekpro-section-panel active" id="section-admin-dark-mode" data-section="admin-dark-mode">
                    <h2>🖥️ <?php esc_html_e('Admin Dark Mode Controls', 'syntekpro-toggle'); ?> <span class="syntekpro-free-badge"><?php esc_html_e('FREE', 'syntekpro-toggle'); ?></span></h2>
                    <div class="syntekpro-section-body" style="background:#fff; border:1px solid #ccc; border-radius:8px; padding:20px;">
                        <p style="color:#666;font-size:13px;margin-top:0;"><?php esc_html_e('Core admin dark mode controls — available to all users.', 'syntekpro-toggle'); ?></p>
                        <?php syntekpro_toggle_render_field('enable_admin_dark_mode', 'syntekpro-toggle-admin-ui', 'syntekpro_toggle_admin_ui_section'); ?>
                        <?php syntekpro_toggle_render_field('enable_admin_bar_icon', 'syntekpro-toggle-admin-ui', 'syntekpro_toggle_admin_ui_section'); ?>
                        <?php syntekpro_toggle_render_field('enable_admin_floating_toggle', 'syntekpro-toggle-admin-ui', 'syntekpro_toggle_admin_ui_section'); ?>
                        <?php syntekpro_toggle_render_field('enable_dashboard_widget', 'syntekpro-toggle-admin-ui', 'syntekpro_toggle_admin_ui_section'); ?>
                    </div>
                </div>

                <div class="syntekpro-section-panel" id="section-admin-advanced-button" data-section="admin-advanced-button">
                    <h2>⚙️ <?php esc_html_e('Advanced Admin Toggle Options', 'syntekpro-toggle'); ?> <?php echo wp_kses_post(syntekpro_toggle_plus_badge()); ?></h2>
                    <div class="syntekpro-section-body" style="background:#fff; border:1px solid #ccc; border-radius:8px; padding:20px;">
                        <p style="color:#666;font-size:13px;margin-top:0;"><?php esc_html_e('Customise admin toggle button theme and icon.', 'syntekpro-toggle'); ?></p>
                        <?php syntekpro_toggle_render_field('admin_toggle_theme', 'syntekpro-toggle-admin-ui', 'syntekpro_toggle_admin_ui_section'); ?>
                        <?php syntekpro_toggle_render_field('custom_admin_button_icon_url', 'syntekpro-toggle-admin-ui', 'syntekpro_toggle_admin_ui_section'); ?>
                    </div>
                </div>

                <div class="syntekpro-section-panel" id="section-admin-color-scheme" data-section="admin-color-scheme">
                    <h2>🎨 <?php esc_html_e('Admin UI Color Scheme', 'syntekpro-toggle'); ?> <?php echo wp_kses_post(syntekpro_toggle_plus_badge()); ?></h2>
                    <div class="syntekpro-section-body" style="background:#fff; border:1px solid #ccc; border-radius:8px; padding:20px;">
                        <p style="color:#666;font-size:13px;margin-top:0;"><?php esc_html_e('Control admin dashboard dark mode colours, backgrounds, and links.', 'syntekpro-toggle'); ?></p>
                        <?php do_settings_sections('syntekpro-toggle-admin-color'); ?>
                    </div>
                </div>

                <?php submit_button(__('Save Admin Panel Settings', 'syntekpro-toggle')); ?>
            </form>
        </div>
    </div>
    <?php
    syntekpro_toggle_page_footer();
}

/**
 * Media Settings Page
 * Contains: image, video, slide filters + extra media options
 */
function syntekpro_toggle_media_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (syntekpro_toggle_has_settings_updated_flag()) {
        add_settings_error('syntekpro_toggle_messages', 'syntekpro_toggle_message', __('✓ Media settings saved successfully!', 'syntekpro-toggle'), 'updated');
    }
    settings_errors('syntekpro_toggle_messages');
    syntekpro_toggle_page_header(__('Media Settings', 'syntekpro-toggle'));
    ?>
    <div class="syntekpro-content-wrapper syntekpro-media-layout" style="display:flex; gap:24px; margin-top:20px;">
        <div class="syntekpro-sidebar-nav syntekpro-media-sidebar" style="width:260px; flex-shrink:0;">
            <div class="syntekpro-nav-section">
                <a href="#" class="syntekpro-nav-item active" data-section="media-images"><span class="dashicons dashicons-format-image"></span> <?php esc_html_e('Image Filters', 'syntekpro-toggle'); ?></a>
                <a href="#" class="syntekpro-nav-item" data-section="media-images-adv"><span class="dashicons dashicons-admin-media"></span> <?php esc_html_e('Advanced Image', 'syntekpro-toggle'); ?> <?php echo wp_kses_post(syntekpro_toggle_plus_badge()); ?></a>
                <a href="#" class="syntekpro-nav-item" data-section="media-videos"><span class="dashicons dashicons-video-alt3"></span> <?php esc_html_e('Video Filters', 'syntekpro-toggle'); ?></a>
                <a href="#" class="syntekpro-nav-item" data-section="media-videos-adv"><span class="dashicons dashicons-embed-video"></span> <?php esc_html_e('Advanced Video', 'syntekpro-toggle'); ?> <?php echo wp_kses_post(syntekpro_toggle_plus_badge()); ?></a>
                <a href="#" class="syntekpro-nav-item" data-section="media-slides"><span class="dashicons dashicons-slides"></span> <?php esc_html_e('Slide Filters', 'syntekpro-toggle'); ?></a>
                <a href="#" class="syntekpro-nav-item" data-section="media-slides-adv"><span class="dashicons dashicons-images-alt2"></span> <?php esc_html_e('Advanced Slides', 'syntekpro-toggle'); ?> <?php echo wp_kses_post(syntekpro_toggle_plus_badge()); ?></a>
            </div>
        </div>
        <div class="syntekpro-main-content syntekpro-media-main" style="flex:1;">
            <form action="options.php" method="post">
                <?php settings_fields('syntekpro_toggle_settings'); ?>

                <div class="syntekpro-section-panel active" id="section-media-images" data-section="media-images">
                    <h2>🖼️ <?php esc_html_e('Image Filters', 'syntekpro-toggle'); ?></h2>
                    <div class="syntekpro-section-body" style="background:#fff; border:1px solid #ccc; border-radius:8px; padding:20px;">
                        <p style="color:#666;font-size:13px;margin-top:0;"><?php esc_html_e('Apply brightness and contrast filters to images in dark mode to reduce eye strain.', 'syntekpro-toggle'); ?></p>
                        <?php do_settings_sections('syntekpro-toggle-frontend-images'); ?>
                    </div>
                </div>

                <div class="syntekpro-section-panel" id="section-media-images-adv" data-section="media-images-adv">
                    <h2>🖼️ <?php esc_html_e('Advanced Image Options', 'syntekpro-toggle'); ?> <?php echo wp_kses_post(syntekpro_toggle_plus_badge()); ?></h2>
                    <div class="syntekpro-section-body" style="background:#fff; border:1px solid #ccc; border-radius:8px; padding:20px;">
                        <p style="color:#666;font-size:13px;margin-top:0;"><?php esc_html_e('Target specific image elements, exclude images by class, and control background image handling.', 'syntekpro-toggle'); ?></p>
                        <?php syntekpro_toggle_render_field('image_selector', 'syntekpro-toggle-frontend-media-advanced', 'syntekpro_toggle_media_advanced_section'); ?>
                        <?php syntekpro_toggle_render_field('image_exclude_class', 'syntekpro-toggle-frontend-media-advanced', 'syntekpro_toggle_media_advanced_section'); ?>
                        <?php syntekpro_toggle_render_field('enable_bg_image_filter', 'syntekpro-toggle-frontend-media-advanced', 'syntekpro_toggle_media_advanced_section'); ?>
                        <?php syntekpro_toggle_render_field('enable_svg_filter', 'syntekpro-toggle-frontend-media-advanced', 'syntekpro_toggle_media_advanced_section'); ?>
                    </div>
                </div>

                <div class="syntekpro-section-panel" id="section-media-videos" data-section="media-videos">
                    <h2>🎬 <?php esc_html_e('Video Filters', 'syntekpro-toggle'); ?></h2>
                    <div class="syntekpro-section-body" style="background:#fff; border:1px solid #ccc; border-radius:8px; padding:20px;">
                        <p style="color:#666;font-size:13px;margin-top:0;"><?php esc_html_e('Apply filters to video elements in dark mode.', 'syntekpro-toggle'); ?></p>
                        <?php do_settings_sections('syntekpro-toggle-frontend-videos'); ?>
                    </div>
                </div>

                <div class="syntekpro-section-panel" id="section-media-videos-adv" data-section="media-videos-adv">
                    <h2>🎬 <?php esc_html_e('Advanced Video & Embed Options', 'syntekpro-toggle'); ?> <?php echo wp_kses_post(syntekpro_toggle_plus_badge()); ?></h2>
                    <div class="syntekpro-section-body" style="background:#fff; border:1px solid #ccc; border-radius:8px; padding:20px;">
                        <p style="color:#666;font-size:13px;margin-top:0;"><?php esc_html_e('Target specific video selectors and control iframe/embed handling.', 'syntekpro-toggle'); ?></p>
                        <?php syntekpro_toggle_render_field('video_selector', 'syntekpro-toggle-frontend-media-advanced', 'syntekpro_toggle_media_advanced_section'); ?>
                        <?php syntekpro_toggle_render_field('enable_iframe_filter', 'syntekpro-toggle-frontend-media-advanced', 'syntekpro_toggle_media_advanced_section'); ?>
                        <?php syntekpro_toggle_render_field('iframe_brightness', 'syntekpro-toggle-frontend-media-advanced', 'syntekpro_toggle_media_advanced_section'); ?>
                    </div>
                </div>

                <div class="syntekpro-section-panel" id="section-media-slides" data-section="media-slides">
                    <h2>📊 <?php esc_html_e('Slide / Carousel Filters', 'syntekpro-toggle'); ?></h2>
                    <div class="syntekpro-section-body" style="background:#fff; border:1px solid #ccc; border-radius:8px; padding:20px;">
                        <p style="color:#666;font-size:13px;margin-top:0;"><?php esc_html_e('Apply filters to slider and carousel elements in dark mode.', 'syntekpro-toggle'); ?></p>
                        <?php do_settings_sections('syntekpro-toggle-frontend-slides'); ?>
                    </div>
                </div>

                <div class="syntekpro-section-panel" id="section-media-slides-adv" data-section="media-slides-adv">
                    <h2>📊 <?php esc_html_e('Advanced Slide Options', 'syntekpro-toggle'); ?> <?php echo wp_kses_post(syntekpro_toggle_plus_badge()); ?></h2>
                    <div class="syntekpro-section-body" style="background:#fff; border:1px solid #ccc; border-radius:8px; padding:20px;">
                        <p style="color:#666;font-size:13px;margin-top:0;"><?php esc_html_e('Target specific slider selectors and control overlay handling.', 'syntekpro-toggle'); ?></p>
                        <?php syntekpro_toggle_render_field('slide_selector', 'syntekpro-toggle-frontend-media-advanced', 'syntekpro_toggle_media_advanced_section'); ?>
                        <?php syntekpro_toggle_render_field('enable_slider_overlay', 'syntekpro-toggle-frontend-media-advanced', 'syntekpro_toggle_media_advanced_section'); ?>
                        <?php syntekpro_toggle_render_field('slider_overlay_color', 'syntekpro-toggle-frontend-media-advanced', 'syntekpro_toggle_media_advanced_section'); ?>
                        <?php syntekpro_toggle_render_field('slider_overlay_opacity', 'syntekpro-toggle-frontend-media-advanced', 'syntekpro_toggle_media_advanced_section'); ?>
                    </div>
                </div>

                <?php submit_button(__('Save Media Settings', 'syntekpro-toggle')); ?>
            </form>
        </div>
    </div>
    <?php
    syntekpro_toggle_page_footer();
}


/**
 * Options Page
 */
function syntekpro_toggle_options_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (syntekpro_toggle_has_settings_updated_flag()) {
        add_settings_error('syntekpro_toggle_messages', 'syntekpro_toggle_message', __('✓ Options saved successfully.', 'syntekpro-toggle'), 'updated');
    }

    settings_errors('syntekpro_toggle_messages');

    syntekpro_toggle_page_header(__('Options', 'syntekpro-toggle'));
    ?>
    <div class="syntekpro-content-wrapper syntekpro-options-layout" style="display: flex; gap: 24px; margin-top: 20px;">
        <div class="syntekpro-sidebar-nav syntekpro-options-sidebar" style="width: 260px; flex-shrink: 0;">
            <div class="syntekpro-nav-section" style="gap: 6px;">
                <a href="#" class="syntekpro-nav-item active" data-section="options-display"><span class="dashicons dashicons-visibility"></span> Display Rules</a>
                <a href="#" class="syntekpro-nav-item" data-section="options-targeting"><span class="dashicons dashicons-admin-users"></span> User Targeting</a>
                <a href="#" class="syntekpro-nav-item" data-section="options-schedule"><span class="dashicons dashicons-clock"></span> Schedule</a>
                <a href="#" class="syntekpro-nav-item" data-section="options-behavior"><span class="dashicons dashicons-admin-generic"></span> Behavior</a>
                <a href="#" class="syntekpro-nav-item" data-section="options-storage"><span class="dashicons dashicons-database"></span> Storage & Privacy</a>
                <a href="#" class="syntekpro-nav-item" data-section="options-animations"><span class="dashicons dashicons-controls-play"></span> Animations</a>
                <a href="#" class="syntekpro-nav-item" data-section="options-accessibility"><span class="dashicons dashicons-universal-access"></span> Accessibility</a>
                <a href="#" class="syntekpro-nav-item" data-section="options-integrations"><span class="dashicons dashicons-networking"></span> Integrations</a>
                <a href="#" class="syntekpro-nav-item" data-section="options-overrides"><span class="dashicons dashicons-admin-appearance"></span> Theme Overrides</a>
                <a href="#" class="syntekpro-nav-item" data-section="options-performance"><span class="dashicons dashicons-performance"></span> Performance</a>
                <a href="#" class="syntekpro-nav-item" data-section="options-debug"><span class="dashicons dashicons-admin-tools"></span> Debug</a>
                <a href="#" class="syntekpro-nav-item" data-section="options-advanced"><span class="dashicons dashicons-admin-settings"></span> Advanced</a>
            </div>
        </div>

        <div class="syntekpro-main-content syntekpro-options-main" style="flex: 1;">
            <form action="options.php" method="post">
                <?php settings_fields('syntekpro_toggle_settings'); ?>

                <div class="syntekpro-section-panel active" id="section-options-display" data-section="options-display">
                    <h2><?php esc_html_e('Display Rules', 'syntekpro-toggle'); ?></h2>
                    <div class="syntekpro-section-body" style="background: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 20px;">
                        <?php syntekpro_toggle_render_settings_section('syntekpro-toggle-frontend-advanced', 'syntekpro_toggle_display_section'); ?>
                    </div>
                </div>

                <div class="syntekpro-section-panel" id="section-options-targeting" data-section="options-targeting">
                    <h2><?php esc_html_e('User Targeting', 'syntekpro-toggle'); ?></h2>
                    <div class="syntekpro-section-body" style="background: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 20px;">
                        <?php syntekpro_toggle_render_settings_section('syntekpro-toggle-frontend-advanced', 'syntekpro_toggle_user_targeting_section'); ?>
                    </div>
                </div>

                <div class="syntekpro-section-panel" id="section-options-schedule" data-section="options-schedule">
                    <h2><?php esc_html_e('Schedule', 'syntekpro-toggle'); ?></h2>
                    <div class="syntekpro-section-body" style="background: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 20px;">
                        <?php syntekpro_toggle_render_settings_section('syntekpro-toggle-frontend-advanced', 'syntekpro_toggle_schedule_section'); ?>
                    </div>
                </div>

                <div class="syntekpro-section-panel" id="section-options-behavior" data-section="options-behavior">
                    <h2><?php esc_html_e('Behavior', 'syntekpro-toggle'); ?></h2>
                    <div class="syntekpro-section-body" style="background: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 20px;">
                        <?php syntekpro_toggle_render_settings_section('syntekpro-toggle-frontend-advanced', 'syntekpro_toggle_behavior_section'); ?>
                    </div>
                </div>

                <div class="syntekpro-section-panel" id="section-options-storage" data-section="options-storage">
                    <h2><?php esc_html_e('Storage & Privacy', 'syntekpro-toggle'); ?></h2>
                    <div class="syntekpro-section-body" style="background: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 20px;">
                        <?php syntekpro_toggle_render_settings_section('syntekpro-toggle-frontend-advanced', 'syntekpro_toggle_storage_section'); ?>
                    </div>
                </div>

                <div class="syntekpro-section-panel" id="section-options-animations" data-section="options-animations">
                    <h2><?php esc_html_e('Animations', 'syntekpro-toggle'); ?></h2>
                    <div class="syntekpro-section-body" style="background: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 20px;">
                        <?php syntekpro_toggle_render_settings_section('syntekpro-toggle-frontend-advanced', 'syntekpro_toggle_animation_section'); ?>
                    </div>
                </div>

                <div class="syntekpro-section-panel" id="section-options-accessibility" data-section="options-accessibility">
                    <h2><?php esc_html_e('Accessibility', 'syntekpro-toggle'); ?></h2>
                    <div class="syntekpro-section-body" style="background: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 20px;">
                        <?php syntekpro_toggle_render_settings_section('syntekpro-toggle-frontend-advanced', 'syntekpro_toggle_accessibility_section'); ?>
                    </div>
                </div>

                <div class="syntekpro-section-panel" id="section-options-integrations" data-section="options-integrations">
                    <h2><?php esc_html_e('Integrations', 'syntekpro-toggle'); ?></h2>
                    <div class="syntekpro-section-body" style="background: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 20px;">
                        <?php syntekpro_toggle_render_settings_section('syntekpro-toggle-frontend-advanced', 'syntekpro_toggle_integrations_section'); ?>
                    </div>
                </div>

                <div class="syntekpro-section-panel" id="section-options-overrides" data-section="options-overrides">
                    <h2><?php esc_html_e('Theme Overrides', 'syntekpro-toggle'); ?></h2>
                    <div class="syntekpro-section-body" style="background: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 20px;">
                        <?php syntekpro_toggle_render_settings_section('syntekpro-toggle-frontend-advanced', 'syntekpro_toggle_theme_override_section'); ?>
                    </div>
                </div>

                <div class="syntekpro-section-panel" id="section-options-performance" data-section="options-performance">
                    <h2><?php esc_html_e('Performance', 'syntekpro-toggle'); ?></h2>
                    <div class="syntekpro-section-body" style="background: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 20px;">
                        <?php syntekpro_toggle_render_settings_section('syntekpro-toggle-frontend-advanced', 'syntekpro_toggle_performance_section'); ?>
                    </div>
                </div>

                <div class="syntekpro-section-panel" id="section-options-debug" data-section="options-debug">
                    <h2><?php esc_html_e('Debug', 'syntekpro-toggle'); ?></h2>
                    <div class="syntekpro-section-body" style="background: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 20px;">
                        <?php syntekpro_toggle_render_settings_section('syntekpro-toggle-frontend-advanced', 'syntekpro_toggle_debug_section'); ?>
                    </div>
                </div>

                <div class="syntekpro-section-panel" id="section-options-advanced" data-section="options-advanced">
                    <h2><?php esc_html_e('Advanced Options', 'syntekpro-toggle'); ?></h2>
                    <div class="syntekpro-section-body" style="background: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 20px;">
                        <?php syntekpro_toggle_render_settings_section('syntekpro-toggle-frontend-advanced', 'syntekpro_toggle_advanced_section'); ?>
                    </div>
                </div>

                <?php submit_button(__('Save Options', 'syntekpro-toggle')); ?>
            </form>
        </div>
    </div>

    <?php
    syntekpro_toggle_page_footer();
}



/**
 * About Page
 */
function syntekpro_toggle_about_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    syntekpro_toggle_page_header(__('About SyntekPro Toggle', 'syntekpro-toggle'));
    ?>
    <style>
    .sp-about-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 16px; }
    .sp-feature-card { padding: 14px 16px; background: #f8f9ff; border-left: 4px solid #667eea; border-radius: 6px; }
    .sp-feature-card h4 { margin: 0 0 6px; color: #667eea; font-size: 13px; }
    .sp-feature-card p { margin: 0; font-size: 13px; color: #555; line-height: 1.5; }
    .sp-guide-step { display: flex; gap: 14px; margin-bottom: 20px; align-items: flex-start; }
    .sp-guide-step-num { width: 32px; height: 32px; background: linear-gradient(135deg,#667eea,#764ba2); color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; flex-shrink: 0; }
    .sp-guide-step-body h4 { margin: 0 0 4px; font-size: 14px; color: #333; }
    .sp-guide-step-body p { margin: 0; font-size: 13px; color: #555; line-height: 1.6; }
    .sp-changelog-entry { border-left: 3px solid #667eea; padding: 12px 16px; margin-bottom: 16px; background: #f8f9ff; border-radius: 0 6px 6px 0; }
    .sp-changelog-entry h4 { margin: 0 0 8px; font-size: 14px; color: #333; }
    .sp-changelog-entry ul { margin: 0; padding-left: 16px; }
    .sp-changelog-entry li { font-size: 13px; color: #555; margin-bottom: 4px; line-height: 1.5; }
    .sp-changelog-badge { display: inline-block; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 12px; margin-left: 8px; }
    .sp-badge-added { background: #d4edda; color: #155724; }
    .sp-badge-changed { background: #cce5ff; color: #004085; }
    .sp-badge-fixed { background: #f8d7da; color: #721c24; }
    .sp-plugins-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-top: 8px; }
    .sp-plugin-card { background: #fff; border: 2px solid #e0e0e0; border-radius: 10px; padding: 22px; transition: box-shadow 0.2s; }
    .sp-plugin-card:hover { box-shadow: 0 4px 16px rgba(102,126,234,0.15); border-color: #667eea; }
    .sp-plugin-icon { font-size: 2.2em; text-align: center; margin-bottom: 10px; }
    .sp-plugin-card h4 { margin: 0 0 4px; font-size: 14px; text-align: center; color: #333; }
    .sp-plugin-tagline { text-align: center; font-size: 12px; color: #667eea; font-weight: 600; margin-bottom: 12px; }
    .sp-plugin-desc { font-size: 13px; color: #555; line-height: 1.6; margin-bottom: 14px; }
    .sp-plugin-link { display: block; text-align: center; padding: 8px 14px; background: #667eea; color: #fff; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: 600; transition: background 0.2s; }
    .sp-plugin-link:hover { background: #5a6fd6; color: #fff; }
    .sp-tip-box { background: #f0f6ff; border: 1px solid #cce5ff; border-radius: 8px; padding: 14px 18px; font-size: 13px; color: #004085; margin-bottom: 14px; line-height: 1.6; }
    .sp-about-upgrade-table { width: 100%; border-collapse: collapse; margin-top: 16px; font-size: 13px; }
    .sp-about-upgrade-table th { background: linear-gradient(135deg,#667eea,#764ba2); color: #fff; padding: 10px 14px; text-align: left; }
    .sp-about-upgrade-table td { padding: 9px 14px; border-bottom: 1px solid #eee; vertical-align: top; }
    .sp-about-upgrade-table tr:last-child td { border-bottom: none; }
    .sp-about-upgrade-table tr:nth-child(even) td { background: #f8f9ff; }
    .sp-check { color: #28a745; font-weight: 700; }
    .sp-lock { color: #aaa; }
    </style>

    <div class="syntekpro-content-wrapper syntekpro-about-layout" style="display: flex; gap: 24px; margin-top: 20px;">

        <!-- Sidebar Nav -->
        <div class="syntekpro-sidebar-nav syntekpro-about-sidebar" style="width: 260px; flex-shrink: 0;">
            <div class="syntekpro-nav-section" style="gap: 6px;">
                <a href="#" class="syntekpro-nav-item syntekpro-about-nav-item active" data-section="about-overview"><span class="dashicons dashicons-info-outline"></span> <?php esc_html_e('About', 'syntekpro-toggle'); ?></a>
                <a href="#" class="syntekpro-nav-item syntekpro-about-nav-item" data-section="about-guide"><span class="dashicons dashicons-book"></span> <?php esc_html_e('User Guide', 'syntekpro-toggle'); ?></a>
                <a href="#" class="syntekpro-nav-item syntekpro-about-nav-item" data-section="about-changelog"><span class="dashicons dashicons-list-view"></span> <?php esc_html_e('Changelog', 'syntekpro-toggle'); ?></a>
                <a href="#" class="syntekpro-nav-item syntekpro-about-nav-item" data-section="about-plugins"><span class="dashicons dashicons-plugins-checked"></span> <?php esc_html_e('Other Plugins', 'syntekpro-toggle'); ?></a>
                <a href="#" class="syntekpro-nav-item syntekpro-about-nav-item" data-section="about-support" style="margin-top:6px;"><span class="dashicons dashicons-sos"></span> <?php esc_html_e('Support', 'syntekpro-toggle'); ?></a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="syntekpro-main-content syntekpro-about-main" style="flex: 1;">

            <!-- About / Overview -->
            <div class="syntekpro-section-panel active" id="section-about-overview" data-section="about-overview">
                <h2><span class="dashicons dashicons-info-outline"></span> <?php esc_html_e('About SyntekPro Toggle', 'syntekpro-toggle'); ?></h2>
                <div class="syntekpro-section-body syntekpro-admin-box">
                    <p style="font-size:14px;color:#555;line-height:1.8;margin-top:0;">
                        <?php esc_html_e('SyntekPro Toggle is a lightweight, feature-rich dark/light mode plugin for WordPress. It respects OS preferences, remembers user choices, and gives you full control over colours, media filters, scheduling, and admin dark mode — all without slowing your site down.', 'syntekpro-toggle'); ?>
                    </p>
                    <div class="sp-about-grid">
                        <div class="sp-feature-card"><h4>🎨 <?php esc_html_e('Full Customization', 'syntekpro-toggle'); ?></h4><p><?php esc_html_e('Control dark mode colors, button themes, animations, and media filters.', 'syntekpro-toggle'); ?></p></div>
                        <div class="sp-feature-card"><h4>🔄 <?php esc_html_e('Smart Auto Mode', 'syntekpro-toggle'); ?></h4><p><?php esc_html_e('Automatically respects users\' OS dark mode preferences and remembers their choice.', 'syntekpro-toggle'); ?></p></div>
                        <div class="sp-feature-card"><h4>🎯 <?php esc_html_e('Block Theme Ready', 'syntekpro-toggle'); ?></h4><p><?php esc_html_e('Works seamlessly with WordPress block themes and all major page builders.', 'syntekpro-toggle'); ?></p></div>
                        <div class="sp-feature-card"><h4>⚡ <?php esc_html_e('Admin Dark Mode', 'syntekpro-toggle'); ?></h4><p><?php esc_html_e('Bring dark mode to the WordPress dashboard with configurable color schemes.', 'syntekpro-toggle'); ?></p></div>
                        <div class="sp-feature-card"><h4>📊 <?php esc_html_e('Built-in Analytics', 'syntekpro-toggle'); ?></h4><p><?php esc_html_e('Track toggle clicks, mode preferences, and page views with zero third-party dependencies.', 'syntekpro-toggle'); ?></p></div>
                        <div class="sp-feature-card"><h4>🔒 <?php esc_html_e('GDPR Friendly', 'syntekpro-toggle'); ?></h4><p><?php esc_html_e('No external requests. User preference is stored locally in the browser only.', 'syntekpro-toggle'); ?></p></div>
                    </div>
                    <div style="background:#f0f6ff;border:1px solid #cce5ff;border-radius:8px;padding:14px 18px;margin-top:18px;font-size:13px;">
                        <strong><?php esc_html_e('Version:', 'syntekpro-toggle'); ?></strong> <?php echo esc_html(SYNTEKPRO_TOGGLE_VERSION); ?> &nbsp;|&nbsp;
                        <strong><?php esc_html_e('Requires:', 'syntekpro-toggle'); ?></strong> WordPress 5.0+ · PHP 7.2+ &nbsp;|&nbsp;
                        <strong><?php esc_html_e('License:', 'syntekpro-toggle'); ?></strong> GPL v2 or later
                    </div>
                </div>
            </div>

            <!-- User Guide -->
            <div class="syntekpro-section-panel" id="section-about-guide" data-section="about-guide">
                <h2><span class="dashicons dashicons-book"></span> <?php esc_html_e('User Guide', 'syntekpro-toggle'); ?></h2>
                <div class="syntekpro-section-body syntekpro-admin-box">
                    <div class="sp-tip-box">💡 <?php esc_html_e('Follow the steps below to set up SyntekPro Toggle on your WordPress site. Most sites are up and running in under 5 minutes.', 'syntekpro-toggle'); ?></div>

                    <div class="sp-guide-step">
                        <div class="sp-guide-step-num">1</div>
                        <div class="sp-guide-step-body">
                            <h4><?php esc_html_e('Configure the Toggle Button (Frontend)', 'syntekpro-toggle'); ?></h4>
                            <p><?php esc_html_e('Go to SyntekPro Toggle → Frontend. Choose the Default Mode (Auto / Dark / Light), ensure "Toggle Button" is enabled, and select a button position (e.g. Bottom Right). Save. The floating button will now appear on your site.', 'syntekpro-toggle'); ?></p>
                        </div>
                    </div>
                    <div class="sp-guide-step">
                        <div class="sp-guide-step-num">2</div>
                        <div class="sp-guide-step-body">
                            <h4><?php esc_html_e('Customise Button Appearance', 'syntekpro-toggle'); ?> <?php echo wp_kses_post( syntekpro_toggle_plus_badge() ); ?></h4>
                            <p><?php esc_html_e('Still on the Frontend page, scroll down to Advanced Button Options to change button size, shape, theme, animation style, and upload a custom icon. The Color Scheme section lets you pick a preset palette or define custom dark-mode colours.', 'syntekpro-toggle'); ?>
                            <?php if ( ! syntekpro_toggle_is_plus() ) : ?>
                            <br><em style="color:#667eea;font-size:12px;"><?php esc_html_e('Unlock all 20 themes and custom colours with Toggle+.', 'syntekpro-toggle'); ?> <a href="<?php echo esc_url( admin_url( 'admin.php?page=syntekpro-toggle-license' ) ); ?>"><?php esc_html_e('Get Toggle+', 'syntekpro-toggle'); ?> →</a></em>
                            <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <div class="sp-guide-step">
                        <div class="sp-guide-step-num">3</div>
                        <div class="sp-guide-step-body">
                            <h4><?php esc_html_e('Enable Admin Dark Mode (Admin Panel)', 'syntekpro-toggle'); ?></h4>
                            <p><?php esc_html_e('Go to SyntekPro Toggle → Admin Panel. Toggle "Admin Dark Mode" on. You can also enable the top-bar quick-toggle icon and dashboard widget here. For custom admin colours, scroll to the Admin UI Color Scheme section.', 'syntekpro-toggle'); ?></p>
                        </div>
                    </div>
                    <div class="sp-guide-step">
                        <div class="sp-guide-step-num">4</div>
                        <div class="sp-guide-step-body">
                            <h4><?php esc_html_e('Configure Media Filters (Media Settings)', 'syntekpro-toggle'); ?></h4>
                            <p><?php esc_html_e('Go to SyntekPro Toggle → Media Settings. Configure image brightness/contrast, video filters, and slider overlays to reduce eye strain in dark mode. Advanced options let you target specific CSS selectors or exclude images by class.', 'syntekpro-toggle'); ?></p>
                        </div>
                    </div>
                    <div class="sp-guide-step">
                        <div class="sp-guide-step-num">5</div>
                        <div class="sp-guide-step-body">
                            <h4><?php esc_html_e('Set Display Rules & Scheduling (Options)', 'syntekpro-toggle'); ?></h4>
                            <p><?php esc_html_e('Go to SyntekPro Toggle → Options. Under Display Rules choose "All pages", specific post types, or exclude pages. Under Schedule you can activate dark mode only during certain hours. User Targeting limits the toggle to logged-in users, guests, or specific roles.', 'syntekpro-toggle'); ?></p>
                        </div>
                    </div>
                    <div class="sp-guide-step">
                        <div class="sp-guide-step-num">6</div>
                        <div class="sp-guide-step-body">
                            <h4><?php esc_html_e('Monitor Usage with Analytics', 'syntekpro-toggle'); ?> <?php echo wp_kses_post( syntekpro_toggle_plus_badge() ); ?></h4>
                            <p><?php esc_html_e('Go to SyntekPro Toggle → Analytics to see how many times the toggle was clicked, which mode is preferred, and page-view totals. You can reset analytics data at any time from the "Reset Analytics" tab.', 'syntekpro-toggle'); ?>
                            <?php if ( ! syntekpro_toggle_is_plus() ) : ?>
                            <br><em style="color:#667eea;font-size:12px;"><?php esc_html_e('Mode preferences, popular themes, and recent activity charts require Toggle+.', 'syntekpro-toggle'); ?> <a href="<?php echo esc_url( admin_url( 'admin.php?page=syntekpro-toggle-license' ) ); ?>"><?php esc_html_e('Upgrade', 'syntekpro-toggle'); ?> →</a></em>
                            <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <div class="sp-guide-step">
                        <div class="sp-guide-step-num">7</div>
                        <div class="sp-guide-step-body">
                            <h4><?php esc_html_e('Using the [toggle_button] Shortcode', 'syntekpro-toggle'); ?></h4>
                            <p><?php esc_html_e('Place the floating toggle anywhere in your content with the shortcode [toggle_button]. This is useful for adding it to a header, footer widget, or Gutenberg block. Enable shortcode output from Options → Integrations.', 'syntekpro-toggle'); ?></p>
                        </div>
                    </div>
                    <div class="sp-guide-step">
                        <div class="sp-guide-step-num">8</div>
                        <div class="sp-guide-step-body">
                            <h4><?php esc_html_e('Keeping the Plugin Up to Date', 'syntekpro-toggle'); ?></h4>
                            <p><?php esc_html_e('SyntekPro Toggle checks GitHub for new releases automatically. When an update is available you will see the standard WordPress update notification in the Plugins list. Click "Update Now" to install. Older versions are available as tagged releases on the GitHub repository.', 'syntekpro-toggle'); ?></p>
                        </div>
                    </div>

                    <div class="sp-tip-box" style="margin-top:8px;">
                        ⌨️ <strong><?php esc_html_e('Keyboard shortcut:', 'syntekpro-toggle'); ?></strong>
                        <?php esc_html_e('The floating admin toggle button is keyboard accessible. Tab to it and press Enter or Space to toggle dark mode.', 'syntekpro-toggle'); ?>
                    </div>
                </div>
            </div>

            <!-- Changelog -->
            <div class="syntekpro-section-panel" id="section-about-changelog" data-section="about-changelog">
                <h2><span class="dashicons dashicons-list-view"></span> <?php esc_html_e('Changelog', 'syntekpro-toggle'); ?></h2>
                <div class="syntekpro-section-body syntekpro-admin-box">

                    <div class="sp-changelog-entry">
                        <h4><?php echo esc_html(SYNTEKPRO_TOGGLE_VERSION); ?> <span style="color:#999;font-weight:400;font-size:12px;"><?php esc_html_e('— Current', 'syntekpro-toggle'); ?></span></h4>
                        <ul>
                            <li><span class="sp-changelog-badge sp-badge-added"><?php esc_html_e('Added', 'syntekpro-toggle'); ?></span> <?php esc_html_e('Toggle+ licensing system with freemium gating.', 'syntekpro-toggle'); ?></li>
                            <li><span class="sp-changelog-badge sp-badge-added"><?php esc_html_e('Added', 'syntekpro-toggle'); ?></span> <?php esc_html_e('Free / Toggle+ badge labelling on Frontend and Admin Panel pages (first 3 settings free).', 'syntekpro-toggle'); ?></li>
                            <li><span class="sp-changelog-badge sp-badge-changed"><?php esc_html_e('Changed', 'syntekpro-toggle'); ?></span> <?php esc_html_e('Button themes limited to 3 for free users; all 20 available with Toggle+.', 'syntekpro-toggle'); ?></li>
                            <li><span class="sp-changelog-badge sp-badge-added"><?php esc_html_e('Added', 'syntekpro-toggle'); ?></span> <?php esc_html_e('Analytics sections (Mode Preferences, Popular Themes, Recent Activity) gated for Toggle+ users.', 'syntekpro-toggle'); ?></li>
                            <li><span class="sp-changelog-badge sp-badge-changed"><?php esc_html_e('Changed', 'syntekpro-toggle'); ?></span> <?php esc_html_e('Menu icon resized from 25×25 px to 18×18 px for a cleaner sidebar appearance.', 'syntekpro-toggle'); ?></li>
                            <li><span class="sp-changelog-badge sp-badge-added"><?php esc_html_e('Added', 'syntekpro-toggle'); ?></span> <?php esc_html_e('Settings divided into three dedicated pages: Frontend, Admin Panel, and Media Settings.', 'syntekpro-toggle'); ?></li>
                            <li><span class="sp-changelog-badge sp-badge-added"><?php esc_html_e('Added', 'syntekpro-toggle'); ?></span> <?php esc_html_e('New Media Settings page with advanced image selector, background image filter, SVG filter, iframe filter, and slider overlay options.', 'syntekpro-toggle'); ?></li>
                            <li><span class="sp-changelog-badge sp-badge-fixed"><?php esc_html_e('Fixed', 'syntekpro-toggle'); ?></span> <?php esc_html_e('admin_enqueue_scripts hook now uses page slug check instead of brittle hook-name comparison.', 'syntekpro-toggle'); ?></li>
                        </ul>
                    </div>

                    <div class="sp-changelog-entry">
                        <h4>1.6.3 <span style="color:#999;font-weight:400;font-size:12px;">2026-03-19</span></h4>
                        <ul>
                            <li><span class="sp-changelog-badge sp-badge-added"><?php esc_html_e('Added', 'syntekpro-toggle'); ?></span> <?php esc_html_e('GitHub Update Notifier — administrators receive a standard WordPress update notice when a new release is published.', 'syntekpro-toggle'); ?></li>
                            <li><span class="sp-changelog-badge sp-badge-changed"><?php esc_html_e('Changed', 'syntekpro-toggle'); ?></span> <?php esc_html_e('Plugin display name unified to "SyntekPro Toggle".', 'syntekpro-toggle'); ?></li>
                            <li><span class="sp-changelog-badge sp-badge-fixed"><?php esc_html_e('Fixed', 'syntekpro-toggle'); ?></span> <?php esc_html_e('"Show toggle button" unchecked state not saving — resolved with hidden sentinel field.', 'syntekpro-toggle'); ?></li>
                            <li><span class="sp-changelog-badge sp-badge-fixed"><?php esc_html_e('Fixed', 'syntekpro-toggle'); ?></span> <?php esc_html_e('Toggle button invisible on some sites due to aggressive theme CSS overrides.', 'syntekpro-toggle'); ?></li>
                        </ul>
                    </div>

                    <div class="sp-changelog-entry">
                        <h4>1.6.2 <span style="color:#999;font-weight:400;font-size:12px;">2026-03-08</span></h4>
                        <ul>
                            <li><span class="sp-changelog-badge sp-badge-added"><?php esc_html_e('Added', 'syntekpro-toggle'); ?></span> <?php esc_html_e('Custom frontend toggle icon upload option.', 'syntekpro-toggle'); ?></li>
                            <li><span class="sp-changelog-badge sp-badge-added"><?php esc_html_e('Added', 'syntekpro-toggle'); ?></span> <?php esc_html_e('Custom admin floating toggle icon upload option.', 'syntekpro-toggle'); ?></li>
                            <li><span class="sp-changelog-badge sp-badge-changed"><?php esc_html_e('Changed', 'syntekpro-toggle'); ?></span> <?php esc_html_e('Reverted to default sun/moon icons unless a custom URL is provided.', 'syntekpro-toggle'); ?></li>
                        </ul>
                    </div>

                    <div class="sp-changelog-entry">
                        <h4>1.6.1 <span style="color:#999;font-weight:400;font-size:12px;">2026-02-17</span></h4>
                        <ul>
                            <li><span class="sp-changelog-badge sp-badge-added"><?php esc_html_e('Added', 'syntekpro-toggle'); ?></span> <?php esc_html_e('Localization readiness, updated POT file.', 'syntekpro-toggle'); ?></li>
                            <li><span class="sp-changelog-badge sp-badge-added"><?php esc_html_e('Added', 'syntekpro-toggle'); ?></span> <?php esc_html_e('uninstall.php to clean up plugin options on removal.', 'syntekpro-toggle'); ?></li>
                        </ul>
                    </div>

                    <div class="sp-changelog-entry">
                        <h4>1.6.0 <span style="color:#999;font-weight:400;font-size:12px;">2026-02-16</span></h4>
                        <ul>
                            <li><span class="sp-changelog-badge sp-badge-added"><?php esc_html_e('Added', 'syntekpro-toggle'); ?></span> <?php esc_html_e('Analytics sidebar navigation with section-based layout.', 'syntekpro-toggle'); ?></li>
                            <li><span class="sp-changelog-badge sp-badge-changed"><?php esc_html_e('Changed', 'syntekpro-toggle'); ?></span> <?php esc_html_e('Unified admin navigation logic across settings pages.', 'syntekpro-toggle'); ?></li>
                        </ul>
                    </div>

                    <p style="font-size:12px;color:#999;margin-top:10px;"><?php esc_html_e('For the full changelog see', 'syntekpro-toggle'); ?> <a href="https://github.com/syntekpro/Syntekpro-Toggle/blob/main/CHANGELOG.md" target="_blank" rel="noopener noreferrer">CHANGELOG.md on GitHub</a>.</p>
                </div>
            </div>

            <!-- Other Plugins -->
            <div class="syntekpro-section-panel" id="section-about-plugins" data-section="about-plugins">
                <h2>
                    <img src="<?php echo esc_url( SYNTEKPRO_TOGGLE_PLUGIN_URL . 'assets/img/SyntekPro%20Plugins%20Logo.png' ); ?>" alt="<?php esc_attr_e( 'SyntekPro Plugins', 'syntekpro-toggle' ); ?>" style="height:28px;width:auto;vertical-align:middle;margin-right:8px;">
                    <?php esc_html_e('Other Plugins by SyntekPro', 'syntekpro-toggle'); ?>
                </h2>
                <div class="syntekpro-section-body syntekpro-admin-box">
                    <div class="sp-plugins-grid">
                        <div class="sp-plugin-card">
                            <div class="sp-plugin-icon"><img src="<?php echo esc_url( SYNTEKPRO_TOGGLE_PLUGIN_URL . 'assets/img/Syntekpro%20Forms%20Icon.png' ); ?>" alt="<?php esc_attr_e( 'SyntekPro Forms', 'syntekpro-toggle' ); ?>" style="width:56px;height:56px;object-fit:contain;"></div>
                            <h4><?php esc_html_e('SyntekPro Forms', 'syntekpro-toggle'); ?></h4>
                            <p class="sp-plugin-tagline"><?php esc_html_e('Advanced WordPress Form Builder', 'syntekpro-toggle'); ?></p>
                            <p class="sp-plugin-desc"><?php esc_html_e('Build powerful forms with conditional logic, payment integration, and email routing.', 'syntekpro-toggle'); ?></p>
                            <a href="https://plugins.syntekpro.com/forms" target="_blank" rel="noopener noreferrer" class="sp-plugin-link"><?php esc_html_e('Learn More', 'syntekpro-toggle'); ?></a>
                        </div>
                        <div class="sp-plugin-card">
                            <div class="sp-plugin-icon"><img src="<?php echo esc_url( SYNTEKPRO_TOGGLE_PLUGIN_URL . 'assets/img/SyntekPro%20Animations%20Icon%20.png' ); ?>" alt="<?php esc_attr_e( 'SyntekPro Animations', 'syntekpro-toggle' ); ?>" style="width:56px;height:56px;object-fit:contain;"></div>
                            <h4><?php esc_html_e('SyntekPro Animations', 'syntekpro-toggle'); ?></h4>
                            <p class="sp-plugin-tagline"><?php esc_html_e('Pure CSS & JS Animations', 'syntekpro-toggle'); ?></p>
                            <p class="sp-plugin-desc"><?php esc_html_e('Add stunning scroll-triggered animations and transitions to any element without jQuery.', 'syntekpro-toggle'); ?></p>
                            <a href="https://plugins.syntekpro.com/animations" target="_blank" rel="noopener noreferrer" class="sp-plugin-link"><?php esc_html_e('Learn More', 'syntekpro-toggle'); ?></a>
                        </div>
                        <div class="sp-plugin-card">
                            <div class="sp-plugin-icon"><img src="<?php echo esc_url( SYNTEKPRO_TOGGLE_PLUGIN_URL . 'assets/img/SyntekPro%20Chat%20Icon.png' ); ?>" alt="<?php esc_attr_e( 'SyntekPro Chat', 'syntekpro-toggle' ); ?>" style="width:56px;height:56px;object-fit:contain;"></div>
                            <h4><?php esc_html_e('SyntekPro Chat', 'syntekpro-toggle'); ?></h4>
                            <p class="sp-plugin-tagline"><?php esc_html_e('WordPress Chat Hub', 'syntekpro-toggle'); ?></p>
                            <p class="sp-plugin-desc"><?php esc_html_e('SyntekPro Chat combines multiple messaging and support channels into one inbox inside your WP admin.', 'syntekpro-toggle'); ?></p>
                            <a href="https://plugins.syntekpro.com/chat" target="_blank" rel="noopener noreferrer" class="sp-plugin-link"><?php esc_html_e('Learn More', 'syntekpro-toggle'); ?></a>
                        </div>
                        <div class="sp-plugin-card">
                            <div class="sp-plugin-icon"><img src="<?php echo esc_url( SYNTEKPRO_TOGGLE_PLUGIN_URL . 'assets/img/SyntekPro%20License%20Server%20Icon.png' ); ?>" alt="<?php esc_attr_e( 'SyntekPro License Server', 'syntekpro-toggle' ); ?>" style="width:56px;height:56px;object-fit:contain;"></div>
                            <h4><?php esc_html_e('SyntekPro License Server', 'syntekpro-toggle'); ?></h4>
                            <p class="sp-plugin-tagline"><?php esc_html_e('License Management & Activation', 'syntekpro-toggle'); ?></p>
                            <p class="sp-plugin-desc"><?php esc_html_e('Manage product licenses, activations, and revocations for plugins, themes, and software.', 'syntekpro-toggle'); ?></p>
                            <a href="https://plugins.syntekpro.com/license-server" target="_blank" rel="noopener noreferrer" class="sp-plugin-link"><?php esc_html_e('Learn More', 'syntekpro-toggle'); ?></a>
                        </div>
                    </div>
                    <div style="text-align:center;margin-top:24px;">
                        <a href="https://plugins.syntekpro.com" target="_blank" rel="noopener noreferrer" style="display:inline-block;padding:10px 28px;background:#667eea;color:#fff;border-radius:6px;text-decoration:none;font-weight:600;font-size:14px;"><?php esc_html_e('🌐 View Full Plugin Catalog', 'syntekpro-toggle'); ?></a>
                    </div>
                </div>
            </div>

            <!-- Support -->
            <div class="syntekpro-section-panel" id="section-about-support" data-section="about-support">
                <h2><span class="dashicons dashicons-sos"></span> <?php esc_html_e('Help & Support', 'syntekpro-toggle'); ?></h2>
                <div class="syntekpro-section-body syntekpro-admin-box">

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:24px;">
                        <div style="background:#f8f9ff;border:1px solid #e0e0e0;border-radius:8px;padding:20px;">
                            <h4 style="margin:0 0 12px;color:#333;">⚙️ <?php esc_html_e('Quick Links', 'syntekpro-toggle'); ?></h4>
                            <a href="<?php echo esc_url( admin_url( 'admin.php?page=syntekpro-toggle' ) ); ?>" style="display:block;background:#667eea;color:#fff;text-decoration:none;padding:8px 14px;border-radius:6px;font-size:13px;margin-bottom:6px;">→ <?php esc_html_e('Frontend Settings', 'syntekpro-toggle'); ?></a>
                            <a href="<?php echo esc_url( admin_url( 'admin.php?page=syntekpro-toggle-admin-panel' ) ); ?>" style="display:block;background:#667eea;color:#fff;text-decoration:none;padding:8px 14px;border-radius:6px;font-size:13px;margin-bottom:6px;">→ <?php esc_html_e('Admin Panel', 'syntekpro-toggle'); ?></a>
                            <a href="<?php echo esc_url( admin_url( 'admin.php?page=syntekpro-toggle-media' ) ); ?>" style="display:block;background:#667eea;color:#fff;text-decoration:none;padding:8px 14px;border-radius:6px;font-size:13px;margin-bottom:6px;">→ <?php esc_html_e('Media Settings', 'syntekpro-toggle'); ?></a>
                            <a href="<?php echo esc_url( admin_url( 'admin.php?page=syntekpro-toggle-options' ) ); ?>" style="display:block;background:#667eea;color:#fff;text-decoration:none;padding:8px 14px;border-radius:6px;font-size:13px;">→ <?php esc_html_e('Options', 'syntekpro-toggle'); ?></a>
                        </div>

                        <div style="background:#f8f9ff;border:2px solid #667eea;border-radius:8px;padding:20px;">
                            <h4 style="margin:0 0 12px;color:#667eea;text-align:center;">💬 <?php esc_html_e('Contact Support', 'syntekpro-toggle'); ?></h4>
                            <a href="https://plugins.syntekpro.com/toggle/docs" target="_blank" rel="noopener noreferrer" class="button button-secondary" style="width:100%;text-align:center;display:block;margin-bottom:8px;"><?php esc_html_e('📚 Documentation', 'syntekpro-toggle'); ?></a>
                            <a href="https://plugins.syntekpro.com/support" target="_blank" rel="noopener noreferrer" class="button button-secondary" style="width:100%;text-align:center;display:block;margin-bottom:8px;"><?php esc_html_e('📧 Support', 'syntekpro-toggle'); ?></a>
                            <a href="https://github.com/syntekpro/Syntekpro-Toggle/issues" target="_blank" rel="noopener noreferrer" class="button button-secondary" style="width:100%;text-align:center;display:block;"><?php esc_html_e('🐛 Report a Bug', 'syntekpro-toggle'); ?></a>
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                        <div style="background:#fff9e6;border:2px solid #ffc107;border-radius:8px;padding:20px;text-align:center;">
                            <h4 style="color:#ff9800;margin:0 0 10px;">⭐ <?php esc_html_e('Enjoying the plugin?', 'syntekpro-toggle'); ?></h4>
                            <p style="font-size:12px;color:#555;margin:0 0 12px;"><?php esc_html_e('Leave a review and help others discover it.', 'syntekpro-toggle'); ?></p>
                            <a href="https://wordpress.org/support/plugin/syntekpro-toggle/reviews/#new-post" target="_blank" rel="noopener noreferrer" class="button button-primary" style="background:#ff9800;border-color:#ff9800;"><?php esc_html_e('⭐ Leave a Review', 'syntekpro-toggle'); ?></a>
                        </div>

                        <div style="background:#f8f9ff;border:1px solid #e0e0e0;border-radius:8px;padding:20px;">
                            <h4 style="margin:0 0 10px;color:#333;">🌐 <?php esc_html_e('Follow SyntekPro', 'syntekpro-toggle'); ?></h4>
                            <div style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:14px;">
                                <a href="https://twitter.com/syntekpro" target="_blank" rel="noopener noreferrer" style="color:#667eea;text-decoration:none;font-size:1.4em;" title="Twitter/X">𝕏</a>
                                <a href="https://facebook.com/syntekpro" target="_blank" rel="noopener noreferrer" style="color:#667eea;text-decoration:none;font-size:1.4em;" title="Facebook">f</a>
                                <a href="https://github.com/syntekpro" target="_blank" rel="noopener noreferrer" style="color:#667eea;text-decoration:none;font-size:1.4em;" title="GitHub">◎</a>
                            </div>
                            <div style="background:#f0f6ff;border:1px solid #cce5ff;border-radius:6px;padding:10px 14px;">
                                <strong style="color:#004085;font-size:12px;">🔄 <?php esc_html_e('Auto Updates', 'syntekpro-toggle'); ?></strong>
                                <p style="font-size:12px;color:#555;margin:4px 0 0;line-height:1.5;"><?php esc_html_e('This plugin checks GitHub for new releases automatically. When an update is available you will see the standard WordPress "Update Now" notice in the Plugins screen.', 'syntekpro-toggle'); ?></p>
                            </div>

                            <?php if ( ! syntekpro_toggle_is_plus() ) : ?>
                            <div style="margin-top:14px;padding:12px;background:linear-gradient(135deg,#667eea10,#764ba210);border:1px solid #667eea;border-radius:6px;text-align:center;">
                                <p style="font-size:12px;color:#333;margin:0 0 8px;font-weight:600;"><?php esc_html_e('Want priority support?', 'syntekpro-toggle'); ?></p>
                                <a href="<?php echo esc_url( admin_url( 'admin.php?page=syntekpro-toggle-license' ) ); ?>" class="button" style="background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;border:none;font-size:12px;padding:6px 16px;border-radius:4px;text-decoration:none;">⭐ <?php esc_html_e('Get Toggle+', 'syntekpro-toggle'); ?></a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- .syntekpro-main-content -->
    </div><!-- .syntekpro-content-wrapper -->
    <?php
    syntekpro_toggle_page_footer();
}

/**
 * Toggle+ Premium Features Page (kept for back-compat)
 */
function syntekpro_toggle_plus_page() {
    wp_safe_redirect(admin_url('admin.php?page=syntekpro-toggle-about'));
    exit;
}

/**
 * Other Plugins Page (redirect to About since it is now included there)
 */
function syntekpro_toggle_plugins_page() {
    wp_safe_redirect(admin_url('admin.php?page=syntekpro-toggle-about'));
    exit;
}


/**
 * Analytics Page
 */
function syntekpro_toggle_analytics_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    // Get analytics data
    $analytics = syntekpro_toggle_get_analytics();
    $options = syntekpro_toggle_get_options();
    
    // Handle settings update
    if (syntekpro_toggle_has_settings_updated_flag()) {
        add_settings_error('syntekpro_toggle_messages', 'syntekpro_toggle_message', __('Analytics settings saved.', 'syntekpro-toggle'), 'updated');
    }
    
    // Handle reset analytics
    if (syntekpro_toggle_has_post_action('reset_analytics') && check_admin_referer('syntekpro_analytics_reset', 'analytics_nonce')) {
        syntekpro_toggle_reset_analytics();
        add_settings_error('syntekpro_toggle_messages', 'syntekpro_toggle_message', __('Analytics data reset successfully.', 'syntekpro-toggle'), 'updated');
        $analytics = syntekpro_toggle_get_analytics(); // Refresh data
    }
    
    settings_errors('syntekpro_toggle_messages');
    
    syntekpro_toggle_page_header(__('Analytics Dashboard', 'syntekpro-toggle'));
    ?>
    <div class="syntekpro-content-wrapper syntekpro-analytics-layout" style="display: flex; gap: 24px; margin-top: 20px;">
        <div class="syntekpro-sidebar-nav syntekpro-analytics-sidebar" style="width: 260px; flex-shrink: 0;">
            <div class="syntekpro-nav-section" style="gap: 6px;">
                <a href="#" class="syntekpro-nav-item syntekpro-analytics-nav-item active" data-section="analytics-settings"><span class="dashicons dashicons-chart-bar"></span> <?php esc_html_e('Analytics Settings', 'syntekpro-toggle'); ?></a>
                <a href="#" class="syntekpro-nav-item syntekpro-analytics-nav-item" data-section="analytics-overview"><span class="dashicons dashicons-chart-line"></span> <?php esc_html_e('Usage Statistics', 'syntekpro-toggle'); ?></a>
                <a href="#" class="syntekpro-nav-item syntekpro-analytics-nav-item" data-section="analytics-modes"><span class="dashicons dashicons-visibility"></span> <?php esc_html_e('Mode Preferences', 'syntekpro-toggle'); ?> <?php if ( ! syntekpro_toggle_is_plus() ) echo wp_kses_post( syntekpro_toggle_plus_badge() ); ?></a>
                <a href="#" class="syntekpro-nav-item syntekpro-analytics-nav-item" data-section="analytics-themes"><span class="dashicons dashicons-art"></span> <?php esc_html_e('Popular Themes', 'syntekpro-toggle'); ?> <?php if ( ! syntekpro_toggle_is_plus() ) echo wp_kses_post( syntekpro_toggle_plus_badge() ); ?></a>
                <a href="#" class="syntekpro-nav-item syntekpro-analytics-nav-item" data-section="analytics-activity"><span class="dashicons dashicons-clock"></span> <?php esc_html_e('Recent Activity', 'syntekpro-toggle'); ?> <?php if ( ! syntekpro_toggle_is_plus() ) echo wp_kses_post( syntekpro_toggle_plus_badge() ); ?></a>
                <a href="#" class="syntekpro-nav-item syntekpro-analytics-nav-item" data-section="analytics-reset"><span class="dashicons dashicons-trash"></span> <?php esc_html_e('Reset Analytics', 'syntekpro-toggle'); ?></a>
                <a href="#" class="syntekpro-nav-item syntekpro-analytics-nav-item" data-section="analytics-info"><span class="dashicons dashicons-lock"></span> <?php esc_html_e('Info & Privacy', 'syntekpro-toggle'); ?></a>
            </div>
        </div>

        <div class="syntekpro-main-content syntekpro-analytics-main" style="flex: 1;">
            <div class="syntekpro-section-panel active" id="section-analytics-settings" data-section="analytics-settings">
                <h2><span class="dashicons dashicons-chart-bar"></span> <?php esc_html_e('Analytics Settings', 'syntekpro-toggle'); ?></h2>
                <div class="syntekpro-section-body syntekpro-admin-box">
                    <form action="options.php" method="post">
                        <?php
                        settings_fields('syntekpro_toggle_settings');
                        do_settings_sections('syntekpro-toggle-analytics-settings');
                        submit_button(__('Save Analytics Settings', 'syntekpro-toggle'));
                        ?>
                    </form>
                </div>
            </div>

            <div class="syntekpro-section-panel" id="section-analytics-overview" data-section="analytics-overview">
                <h2><span class="dashicons dashicons-chart-line"></span> <?php esc_html_e('Usage Statistics', 'syntekpro-toggle'); ?></h2>
                <div class="syntekpro-section-body">
                    <div class="syntekpro-analytics-grid">
                        <div class="syntekpro-analytics-card">
                            <div class="analytics-icon">
                                <span class="dashicons dashicons-chart-line"></span>
                            </div>
                            <div class="analytics-data">
                                <h3><?php echo number_format($analytics['total_toggles']); ?></h3>
                                <p><?php esc_html_e('Total Toggle Clicks', 'syntekpro-toggle'); ?></p>
                            </div>
                        </div>

                        <div class="syntekpro-analytics-card">
                            <div class="analytics-icon">
                                <span class="dashicons dashicons-visibility"></span>
                            </div>
                            <div class="analytics-data">
                                <h3><?php echo number_format($analytics['page_views']); ?></h3>
                                <p><?php esc_html_e('Total Page Views', 'syntekpro-toggle'); ?></p>
                            </div>
                        </div>

                        <div class="syntekpro-analytics-card">
                            <div class="analytics-icon">
                                <span class="dashicons dashicons-clock"></span>
                            </div>
                            <div class="analytics-data">
                                <h3><?php echo esc_html($analytics['most_active_time']); ?></h3>
                                <p><?php esc_html_e('Most Active Time', 'syntekpro-toggle'); ?></p>
                            </div>
                        </div>

                        <div class="syntekpro-analytics-card">
                            <div class="analytics-icon">
                                <span class="dashicons dashicons-calendar-alt"></span>
                            </div>
                            <div class="analytics-data">
                                <h3><?php echo esc_html($analytics['tracking_since']); ?></h3>
                                <p><?php esc_html_e('Tracking Since', 'syntekpro-toggle'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="syntekpro-section-panel" id="section-analytics-modes" data-section="analytics-modes">
                <h2><span class="dashicons dashicons-visibility"></span> <?php esc_html_e('Mode Preferences', 'syntekpro-toggle'); ?> <?php if ( ! syntekpro_toggle_is_plus() ) echo wp_kses_post( syntekpro_toggle_plus_badge() ); ?></h2>
                <?php if ( ! syntekpro_toggle_is_plus() ) : ?>
                    <?php syntekpro_toggle_render_plus_upsell( __( 'Detailed mode preference charts and breakdowns', 'syntekpro-toggle' ) ); ?>
                <?php else : ?>
                <div class="syntekpro-section-body syntekpro-admin-box">
                    <div class="syntekpro-mode-stats">
                        <div class="mode-stat-item">
                            <div class="mode-stat-bar">
                                <div class="mode-stat-label">
                                    <span class="dashicons dashicons-admin-appearance"></span>
                                    <strong>Dark Mode</strong>
                                </div>
                                <div class="mode-stat-value"><?php echo number_format($analytics['dark_mode_count']); ?> uses</div>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?php echo $analytics['dark_mode_percentage']; ?>%; background: #667eea;"></div>
                            </div>
                            <div class="mode-stat-percentage"><?php echo number_format($analytics['dark_mode_percentage'], 1); ?>%</div>
                        </div>

                        <div class="mode-stat-item">
                            <div class="mode-stat-bar">
                                <div class="mode-stat-label">
                                    <span class="dashicons dashicons-lightbulb"></span>
                                    <strong>Light Mode</strong>
                                </div>
                                <div class="mode-stat-value"><?php echo number_format($analytics['light_mode_count']); ?> uses</div>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?php echo $analytics['light_mode_percentage']; ?>%; background: #f59e0b;"></div>
                            </div>
                            <div class="mode-stat-percentage"><?php echo number_format($analytics['light_mode_percentage'], 1); ?>%</div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="syntekpro-section-panel" id="section-analytics-themes" data-section="analytics-themes">
                <h2><span class="dashicons dashicons-art"></span> <?php esc_html_e('Popular Themes', 'syntekpro-toggle'); ?> <?php if ( ! syntekpro_toggle_is_plus() ) echo wp_kses_post( syntekpro_toggle_plus_badge() ); ?></h2>
                <?php if ( ! syntekpro_toggle_is_plus() ) : ?>
                    <?php syntekpro_toggle_render_plus_upsell( __( 'Theme usage analytics and ranking', 'syntekpro-toggle' ) ); ?>
                <?php else : ?>
                <div class="syntekpro-section-body syntekpro-admin-box">
                    <div class="syntekpro-theme-stats">
                        <?php if (!empty($analytics['theme_usage']) && is_array($analytics['theme_usage'])): ?>
                            <?php foreach (array_slice($analytics['theme_usage'], 0, 5) as $theme => $count): ?>
                                <div class="theme-stat-row">
                                    <span class="theme-name"><?php echo esc_html(ucwords(str_replace('-', ' ', $theme))); ?></span>
                                    <span class="theme-count"><?php echo number_format($count); ?> views</span>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p><?php esc_html_e('No theme usage data available yet.', 'syntekpro-toggle'); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="syntekpro-section-panel" id="section-analytics-activity" data-section="analytics-activity">
                <h2><span class="dashicons dashicons-clock"></span> <?php esc_html_e('Recent Activity', 'syntekpro-toggle'); ?> <?php if ( ! syntekpro_toggle_is_plus() ) echo wp_kses_post( syntekpro_toggle_plus_badge() ); ?></h2>
                <?php if ( ! syntekpro_toggle_is_plus() ) : ?>
                    <?php syntekpro_toggle_render_plus_upsell( __( 'Full activity log and event timeline', 'syntekpro-toggle' ) ); ?>
                <?php else : ?>
                <div class="syntekpro-section-body syntekpro-admin-box">
                    <div class="syntekpro-activity-timeline">
                        <?php if (!empty($analytics['recent_activity']) && is_array($analytics['recent_activity'])): ?>
                            <?php foreach (array_slice($analytics['recent_activity'], 0, 10) as $activity): ?>
                                <div class="activity-item">
                                    <span class="activity-icon dashicons <?php echo esc_attr($activity['icon']); ?>"></span>
                                    <span class="activity-text"><?php echo esc_html($activity['text']); ?></span>
                                    <span class="activity-time"><?php echo esc_html($activity['time']); ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p><?php esc_html_e('No recent activity recorded.', 'syntekpro-toggle'); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="syntekpro-section-panel" id="section-analytics-reset" data-section="analytics-reset">
                <h2><span class="dashicons dashicons-trash"></span> <?php esc_html_e('Reset Analytics', 'syntekpro-toggle'); ?></h2>
                <div class="syntekpro-section-body syntekpro-admin-box" style="border-left: 4px solid #dc3232;">
                    <p><?php esc_html_e('Clear all analytics data and start fresh. This action cannot be undone.', 'syntekpro-toggle'); ?></p>
                    <form method="post">
                        <?php wp_nonce_field('syntekpro_analytics_reset', 'analytics_nonce'); ?>
                        <button type="submit" name="reset_analytics" class="button button-secondary" onclick="return confirm('Are you sure you want to reset all analytics data? This cannot be undone.');">
                            <span class="dashicons dashicons-trash"></span> Reset All Analytics Data
                        </button>
                    </form>
                </div>
            </div>

            <div class="syntekpro-section-panel" id="section-analytics-info" data-section="analytics-info">
                <h2><span class="dashicons dashicons-lock"></span> <?php esc_html_e('Info & Privacy', 'syntekpro-toggle'); ?></h2>
                <div class="syntekpro-section-body">
                    <div class="syntekpro-admin-box">
                        <h3><?php esc_html_e('Analytics Info', 'syntekpro-toggle'); ?></h3>
                        <p><?php esc_html_e('Track how users interact with your dark mode toggle.', 'syntekpro-toggle'); ?></p>
                        <ul class="syntekpro-stats-list">
                            <li><strong>Status:</strong> <?php echo (isset($options['enable_analytics']) && $options['enable_analytics'] === '1') ? '<span style="color:#46b450;">Active</span>' : '<span style="color:#dc3232;">Inactive</span>'; ?></li>
                            <li><strong>Total Events:</strong> <?php echo number_format($analytics['total_events']); ?></li>
                            <li><strong>Tracking Started:</strong> <?php echo esc_html($analytics['tracking_since']); ?></li>
                        </ul>
                    </div>

                    <div class="syntekpro-admin-box">
                        <h3><?php esc_html_e('What We Track', 'syntekpro-toggle'); ?></h3>
                        <ul class="syntekpro-stats-list">
                            <li>Toggle button clicks</li>
                            <li>Mode switches (Dark/Light)</li>
                            <li>Theme usage</li>
                            <li>Page load events</li>
                            <li>User preference changes</li>
                        </ul>
                    </div>

                    <div class="syntekpro-admin-box">
                        <h3><?php esc_html_e('Privacy Notice', 'syntekpro-toggle'); ?></h3>
                        <p><?php esc_html_e('All analytics data is stored locally on your server. No data is sent to external services.', 'syntekpro-toggle'); ?></p>
                        <p><?php esc_html_e('We track usage patterns to help you understand how visitors use dark mode on your site.', 'syntekpro-toggle'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    syntekpro_toggle_page_footer();
}

/**
 * Get analytics data
 */
function syntekpro_toggle_get_analytics() {
    $analytics = get_option('syntekpro_toggle_analytics', array());
    
    $defaults = array(
        'total_toggles' => 0,
        'page_views' => 0,
        'dark_mode_count' => 0,
        'light_mode_count' => 0,
        'theme_usage' => array(),
        'recent_activity' => array(),
        'total_events' => 0,
        'tracking_since' => current_time('Y-m-d'),
        'most_active_time' => 'N/A'
    );
    
    $analytics = wp_parse_args($analytics, $defaults);
    
    // Calculate percentages
    $total_modes = $analytics['dark_mode_count'] + $analytics['light_mode_count'];
    $analytics['dark_mode_percentage'] = $total_modes > 0 ? ($analytics['dark_mode_count'] / $total_modes) * 100 : 0;
    $analytics['light_mode_percentage'] = $total_modes > 0 ? ($analytics['light_mode_count'] / $total_modes) * 100 : 0;
    
    return $analytics;
}

/**
 * Track analytics event
 */
function syntekpro_toggle_track_event($event_type, $event_data = array()) {
    $options = syntekpro_toggle_get_options();
    
    // Check if analytics is enabled
    if (!isset($options['enable_analytics']) || $options['enable_analytics'] !== '1') {
        return;
    }

    $track_toggles = isset($options['analytics_track_toggles']) && $options['analytics_track_toggles'] === '1';
    $track_pageviews = isset($options['analytics_track_pageviews']) && $options['analytics_track_pageviews'] === '1';
    $track_modes = isset($options['analytics_track_modes']) && $options['analytics_track_modes'] === '1';
    
    $analytics = get_option('syntekpro_toggle_analytics', array());
    
    // Initialize if empty
    if (empty($analytics)) {
        $analytics = array(
            'total_toggles' => 0,
            'page_views' => 0,
            'dark_mode_count' => 0,
            'light_mode_count' => 0,
            'theme_usage' => array(),
            'recent_activity' => array(),
            'total_events' => 0,
            'tracking_since' => current_time('Y-m-d'),
            'most_active_time' => 'N/A'
        );
    }
    
    // Track specific events
    switch ($event_type) {
        case 'toggle_click':
            if (!$track_toggles) {
                return;
            }
            $analytics['total_toggles']++;
            $analytics['total_events']++;
            syntekpro_toggle_add_activity($analytics, 'Toggle button clicked', 'dashicons-update');
            break;
            
        case 'mode_change':
            if (!$track_modes) {
                return;
            }
            $mode = isset($event_data['mode']) ? $event_data['mode'] : 'unknown';
            if ($mode === 'dark') {
                $analytics['dark_mode_count']++;
                syntekpro_toggle_add_activity($analytics, 'Switched to Dark Mode', 'dashicons-admin-appearance');
            } elseif ($mode === 'light') {
                $analytics['light_mode_count']++;
                syntekpro_toggle_add_activity($analytics, 'Switched to Light Mode', 'dashicons-lightbulb');
            }
            $analytics['total_events']++;
            break;
            
        case 'page_view':
            if (!$track_pageviews) {
                return;
            }
            $analytics['page_views']++;
            $analytics['total_events']++;
            break;
            
        case 'theme_view':
            $theme = isset($event_data['theme']) ? $event_data['theme'] : 'default';
            if (!isset($analytics['theme_usage'][$theme])) {
                $analytics['theme_usage'][$theme] = 0;
            }
            $analytics['theme_usage'][$theme]++;
            break;
    }
    
    // Sort theme usage
    if (!empty($analytics['theme_usage'])) {
        arsort($analytics['theme_usage']);
    }
    
    update_option('syntekpro_toggle_analytics', $analytics);

    if (isset($options['debug_mode']) && $options['debug_mode'] === '1') {
        error_log('[Syntekpro Toggle] Tracked event: ' . $event_type);
    }
}

/**
 * Add activity to recent activity log
 */
function syntekpro_toggle_add_activity(&$analytics, $text, $icon) {
    if (!isset($analytics['recent_activity'])) {
        $analytics['recent_activity'] = array();
    }
    
    $analytics['recent_activity'][] = array(
        'text' => $text,
        'icon' => $icon,
        'time' => current_time('g:i A, M j')
    );
    
    // Keep only last 20 activities
    if (count($analytics['recent_activity']) > 20) {
        $analytics['recent_activity'] = array_slice($analytics['recent_activity'], -20);
    }
}

/**
 * Reset analytics data
 */
function syntekpro_toggle_reset_analytics() {
    delete_option('syntekpro_toggle_analytics');
}

/* ─────────────────────────────────────────────────────────────────────────── *
 *  Toggle+ License helpers
 * ─────────────────────────────────────────────────────────────────────────── */

/**
 * Render a "locked – requires Toggle+" upsell box.
 *
 * @param string $feature_description Short description of what the feature does.
 */
function syntekpro_toggle_render_plus_upsell( $feature_description = '' ) {
    ?>
    <div style="background:#f8f5ff;border:1px solid #c5b4f5;border-radius:10px;padding:28px 24px;text-align:center;margin:10px 0;">
        <div style="font-size:40px;margin-bottom:10px;">🔒</div>
        <h3 style="margin:0 0 8px;color:#4a2fa0;"><?php esc_html_e('Toggle+ Feature', 'syntekpro-toggle'); ?></h3>
        <?php if ( $feature_description ) : ?>
            <p style="color:#555;font-size:13px;margin:0 0 18px;"><?php echo esc_html( $feature_description ); ?> <?php esc_html_e('is available in Toggle+.', 'syntekpro-toggle'); ?></p>
        <?php else : ?>
            <p style="color:#555;font-size:13px;margin:0 0 18px;"><?php esc_html_e('This feature is available in Toggle+.', 'syntekpro-toggle'); ?></p>
        <?php endif; ?>
        <a href="<?php echo esc_url( admin_url('admin.php?page=syntekpro-toggle-license') ); ?>" class="button" style="background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;border:none;padding:9px 24px;font-weight:700;font-size:14px;border-radius:6px;text-decoration:none;display:inline-block;">
            ⭐ <?php esc_html_e('Get Toggle+', 'syntekpro-toggle'); ?>
        </a>
    </div>
    <?php
}

/**
 * Render the Toggle+ license management panel.
 * Used in both Options page (inline) and the dedicated License page.
 *
 * @param string $section_id data-section attribute value (e.g. 'options-license').
 */
function syntekpro_toggle_render_license_panel( $section_id = 'options-license' ) {
    $is_plus    = syntekpro_toggle_is_plus();
    $stored_key = get_option( 'syntekpro_toggle_license_key', '' );
    $expiry     = get_option( 'syntekpro_toggle_license_expiry', '' );
    $masked_key = $stored_key ? substr( $stored_key, 0, 8 ) . str_repeat( '•', max( 0, strlen( $stored_key ) - 8 ) ) : '';
    ?>
    <div class="syntekpro-section-panel" id="section-<?php echo esc_attr( $section_id ); ?>" data-section="<?php echo esc_attr( $section_id ); ?>">
        <h2>⭐ <?php esc_html_e('Toggle+ License', 'syntekpro-toggle'); ?></h2>

        <div style="background:#fff;border:1px solid #ddd;border-radius:10px;padding:28px;max-width:680px;">

            <?php if ( $is_plus ) : ?>
            <!-- ── Active state ── -->
            <div style="background:#ecfdf3;border:1px solid #86efac;border-radius:8px;padding:16px 20px;margin-bottom:24px;display:flex;align-items:center;gap:14px;">
                <span style="font-size:28px;">✅</span>
                <div>
                    <strong style="display:block;font-size:15px;color:#166534;"><?php esc_html_e('Toggle+ is Active', 'syntekpro-toggle'); ?></strong>
                    <span style="font-size:12px;color:#555;">
                        <?php if ( $expiry ) printf( esc_html__( 'License expires: %s', 'syntekpro-toggle' ), esc_html( $expiry ) ); else esc_html_e( 'License key stored.', 'syntekpro-toggle' ); ?>
                        <?php if ( $masked_key ) echo ' &nbsp;|&nbsp; Key: ' . esc_html( $masked_key ); ?>
                    </span>
                </div>
            </div>

            <h3 style="margin-top:0;"><?php esc_html_e('Deactivate License', 'syntekpro-toggle'); ?></h3>
            <p style="color:#555;font-size:13px;"><?php esc_html_e('Deactivating will remove Toggle+ features from this site and free your license seat.', 'syntekpro-toggle'); ?></p>
            <button type="button" id="stp-deactivate-btn" class="button button-secondary" style="border-color:#dc3232;color:#dc3232;">
                <span class="dashicons dashicons-no-alt" style="vertical-align:middle;margin-right:4px;"></span><?php esc_html_e('Deactivate License', 'syntekpro-toggle'); ?>
            </button>
            <span id="stp-license-msg" style="margin-left:14px;font-size:13px;display:none;"></span>

            <?php else : ?>
            <!-- ── Inactive / Enter key state ── -->
            <div style="text-align:center;padding-bottom:20px;border-bottom:1px solid #eee;margin-bottom:24px;">
                <div style="font-size:48px;margin-bottom:8px;">⭐</div>
                <h3 style="margin:0 0 6px;font-size:20px;"><?php esc_html_e('Upgrade to Toggle+', 'syntekpro-toggle'); ?></h3>
                <p style="color:#555;max-width:460px;margin:0 auto 16px;font-size:13px;line-height:1.6;">
                    <?php esc_html_e('Unlock all 20 button themes, full analytics, color presets, advanced settings and more.', 'syntekpro-toggle'); ?>
                </p>
                <a href="https://plugins.syntekpro.com/toggle-plus" target="_blank" rel="noopener noreferrer" class="button button-primary" style="background:linear-gradient(135deg,#667eea,#764ba2);border:none;color:#fff;padding:10px 28px;font-size:15px;font-weight:700;border-radius:8px;text-decoration:none;display:inline-block;">
                    🛒 <?php esc_html_e('Get Toggle+', 'syntekpro-toggle'); ?>
                </a>
            </div>

            <h3 style="margin-top:0;"><?php esc_html_e('Activate License Key', 'syntekpro-toggle'); ?></h3>
            <p style="color:#555;font-size:13px;"><?php esc_html_e('Already purchased? Enter your license key below to unlock all features on this site.', 'syntekpro-toggle'); ?></p>
            <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
                <input type="text" id="stp-license-key-input" placeholder="<?php esc_attr_e('XXXX-XXXX-XXXX-XXXX', 'syntekpro-toggle'); ?>" style="width:300px;padding:8px 12px;border:1px solid #ccc;border-radius:6px;font-size:14px;" autocomplete="off" />
                <button type="button" id="stp-activate-btn" class="button button-primary" style="background:linear-gradient(135deg,#667eea,#764ba2);border:none;color:#fff;padding:8px 20px;font-weight:700;border-radius:6px;">
                    <?php esc_html_e('Activate', 'syntekpro-toggle'); ?>
                </button>
            </div>
            <span id="stp-license-msg" style="display:block;margin-top:12px;font-size:13px;"></span>

            <hr style="margin:28px 0;">
            <h4><?php esc_html_e('What you get with Toggle+', 'syntekpro-toggle'); ?></h4>
            <ul style="margin:0;padding-left:18px;color:#333;font-size:13px;line-height:2;">
                <li>🎨 <?php esc_html_e('All 20 button themes (Neon, Glassmorphic, Cyberpunk & more)', 'syntekpro-toggle'); ?></li>
                <li>📊 <?php esc_html_e('Full analytics: Mode Preferences, Popular Themes, Activity Log', 'syntekpro-toggle'); ?></li>
                <li>🌈 <?php esc_html_e('All color presets and custom dark mode colors', 'syntekpro-toggle'); ?></li>
                <li>⚙️ <?php esc_html_e('Advanced button, admin panel, and media options', 'syntekpro-toggle'); ?></li>
                <li>🔄 <?php esc_html_e('Priority updates and dedicated support', 'syntekpro-toggle'); ?></li>
            </ul>
            <?php endif; ?>
        </div>

        <script>
        (function($) {
            var nonce = <?php echo wp_json_encode( wp_create_nonce('syntekpro_license_nonce') ); ?>;
            var ajaxUrl = <?php echo wp_json_encode( admin_url('admin-ajax.php') ); ?>;

            function showMsg(el, msg, success) {
                el.css({ display:'block', color: success ? '#166534' : '#991b1b',
                    background: success ? '#ecfdf3' : '#fef2f2',
                    border: '1px solid ' + (success ? '#86efac' : '#fca5a5'),
                    padding:'8px 12px', borderRadius:'6px', marginTop:'10px' })
                  .text(msg);
            }

            $('#stp-activate-btn').on('click', function() {
                var key = $('#stp-license-key-input').val().trim();
                var $btn = $(this);
                var $msg = $('#stp-license-msg');
                if (!key) { showMsg($msg, <?php echo wp_json_encode( __('Please enter your license key.', 'syntekpro-toggle') ); ?>, false); return; }
                $btn.prop('disabled', true).text(<?php echo wp_json_encode( __('Activating…', 'syntekpro-toggle') ); ?>);
                $.post(ajaxUrl, { action:'syntekpro_toggle_activate_license', nonce:nonce, license_key: key }, function(res) {
                    if (res.success) { showMsg($msg, res.data.message, true); setTimeout(function(){ location.reload(); }, 1200); }
                    else { showMsg($msg, res.data, false); $btn.prop('disabled', false).text(<?php echo wp_json_encode( __('Activate', 'syntekpro-toggle') ); ?>); }
                }).fail(function(){ showMsg($msg, <?php echo wp_json_encode( __('Connection error. Please try again.', 'syntekpro-toggle') ); ?>, false); $btn.prop('disabled', false).text(<?php echo wp_json_encode( __('Activate', 'syntekpro-toggle') ); ?>); });
            });

            $('#stp-deactivate-btn').on('click', function() {
                if (!confirm(<?php echo wp_json_encode( __('Deactivate Toggle+ on this site?', 'syntekpro-toggle') ); ?>)) return;
                var $btn = $(this);
                var $msg = $('#stp-license-msg');
                $btn.prop('disabled', true).text(<?php echo wp_json_encode( __('Deactivating…', 'syntekpro-toggle') ); ?>);
                $.post(ajaxUrl, { action:'syntekpro_toggle_deactivate_license', nonce:nonce }, function(res) {
                    if (res.success) { showMsg($msg, res.data.message, true); setTimeout(function(){ location.reload(); }, 1200); }
                    else { showMsg($msg, res.data, false); $btn.prop('disabled', false); }
                }).fail(function(){ showMsg($msg, <?php echo wp_json_encode( __('Connection error. Please try again.', 'syntekpro-toggle') ); ?>, false); $btn.prop('disabled', false); });
            });
        }(jQuery));
        </script>
    </div>
    <?php
}

/**
 * Toggle+ / License Page (dedicated menu page)
 */
function syntekpro_toggle_license_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $is_plus    = syntekpro_toggle_is_plus();
    $stored_key = get_option( 'syntekpro_toggle_license_key', '' );
    $expiry     = get_option( 'syntekpro_toggle_license_expiry', '' );
    $masked_key = $stored_key ? substr( $stored_key, 0, 8 ) . str_repeat( '•', max( 0, strlen( $stored_key ) - 8 ) ) : '';

    syntekpro_toggle_page_header( __( 'Toggle+ License', 'syntekpro-toggle' ) );
    ?>
    <style>
    .stp-status-card { border-radius: 12px; padding: 20px 24px; margin-bottom: 20px; display: flex; align-items: center; gap: 20px; }
    .stp-status-card.active  { background: #ecfdf3; border: 2px solid #86efac; }
    .stp-status-card.inactive { background: #fff7ed; border: 2px solid #fcd34d; }
    .stp-status-icon { font-size: 40px; flex-shrink: 0; }
    .stp-status-text strong { display: block; font-size: 16px; margin-bottom: 4px; }
    .stp-status-text span { font-size: 13px; color: #555; }
    .stp-key-row { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; margin-bottom: 12px; }
    .stp-key-input { flex: 1; min-width: 240px; max-width: 360px; padding: 10px 14px; border: 1px solid #ccc; border-radius: 7px; font-size: 14px; font-family: monospace; letter-spacing: .04em; }
    .stp-key-input:focus { outline: none; border-color: #667eea; box-shadow: 0 0 0 3px rgba(102,126,234,.18); }
    .stp-btn-activate { background: linear-gradient(135deg,#667eea,#764ba2); color: #fff; border: none; padding: 10px 26px; font-size: 14px; font-weight: 700; border-radius: 7px; cursor: pointer; transition: opacity .2s; }
    .stp-btn-activate:disabled { opacity: .55; cursor: not-allowed; }
    .stp-btn-activate:not(:disabled):hover { opacity: .88; }
    .stp-btn-deactivate { background: #fff; color: #dc3232; border: 1px solid #dc3232; padding: 9px 22px; font-size: 13px; font-weight: 600; border-radius: 7px; cursor: pointer; transition: background .2s; }
    .stp-btn-deactivate:hover { background: #fef2f2; }
    .stp-msg { display: none; margin-top: 12px; padding: 10px 14px; border-radius: 7px; font-size: 13px; line-height: 1.5; }
    .stp-msg.success { background: #ecfdf3; border: 1px solid #86efac; color: #166534; }
    .stp-msg.error   { background: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; }
    .stp-features-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 12px; }
    .stp-feature-item { display: flex; align-items: flex-start; gap: 10px; padding: 12px 14px; background: #f8f9ff; border-radius: 8px; font-size: 13px; color: #333; line-height: 1.5; }
    .stp-feature-item .stp-fi-icon { font-size: 18px; flex-shrink: 0; }
    .stp-compare-table { width: 100%; border-collapse: collapse; font-size: 13px; margin-top: 8px; }
    .stp-compare-table th { background: linear-gradient(135deg,#667eea,#764ba2); color: #fff; padding: 10px 16px; text-align: left; }
    .stp-compare-table th:not(:first-child) { text-align: center; }
    .stp-compare-table td { padding: 9px 16px; border-bottom: 1px solid #eee; }
    .stp-compare-table td:not(:first-child) { text-align: center; font-weight: 700; }
    .stp-compare-table tr:last-child td { border-bottom: none; }
    .stp-compare-table tr:nth-child(even) td { background: #f8f9ff; }
    .stp-check { color: #22c55e; }
    .stp-cross { color: #d1d5db; }
    .stp-cta-banner { background: linear-gradient(135deg,#667eea 0%,#764ba2 100%); border-radius: 12px; padding: 32px 28px; text-align: center; color: #fff; }
    .stp-cta-banner h3 { margin: 0 0 8px; font-size: 22px; color: #fff; border: none; padding: 0; }
    .stp-cta-banner p  { margin: 0 0 20px; font-size: 14px; opacity: .9; }
    .stp-cta-btn { display: inline-block; background: #fff; color: #667eea; font-weight: 700; font-size: 15px; padding: 12px 34px; border-radius: 8px; text-decoration: none; transition: box-shadow .2s; }
    .stp-cta-btn:hover { box-shadow: 0 4px 20px rgba(0,0,0,.25); color: #764ba2; }
    </style>

    <div class="syntekpro-content-wrapper syntekpro-license-layout" style="display:flex;gap:24px;margin-top:20px;">

        <!-- Sidebar Nav -->
        <div class="syntekpro-sidebar-nav syntekpro-license-sidebar" style="width:260px;flex-shrink:0;">
            <div class="syntekpro-nav-section" style="gap:6px;">
                <a href="#" class="syntekpro-nav-item active" data-section="license-status">
                    <span class="dashicons <?php echo $is_plus ? 'dashicons-yes-alt' : 'dashicons-admin-network'; ?>"></span>
                    <?php echo $is_plus ? esc_html__( 'License Active', 'syntekpro-toggle' ) : esc_html__( 'Activate License', 'syntekpro-toggle' ); ?>
                </a>
                <?php if ( ! $is_plus ) : ?>
                <a href="#" class="syntekpro-nav-item" data-section="license-buy">
                    <span class="dashicons dashicons-cart"></span>
                    <?php esc_html_e( 'Get Toggle+', 'syntekpro-toggle' ); ?>
                </a>
                <?php endif; ?>
                <a href="#" class="syntekpro-nav-item" data-section="license-features">
                    <span class="dashicons dashicons-star-filled"></span>
                    <?php esc_html_e( 'Features', 'syntekpro-toggle' ); ?>
                </a>
                <a href="#" class="syntekpro-nav-item" data-section="license-compare">
                    <span class="dashicons dashicons-list-view"></span>
                    <?php esc_html_e( 'Free vs Toggle+', 'syntekpro-toggle' ); ?>
                </a>
                <a href="#" class="syntekpro-nav-item" data-section="license-support">
                    <span class="dashicons dashicons-sos"></span>
                    <?php esc_html_e( 'Support', 'syntekpro-toggle' ); ?>
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="syntekpro-main-content syntekpro-license-main" style="flex:1;">

            <!-- ── Status / Activate ── -->
            <div class="syntekpro-section-panel active" id="section-license-status" data-section="license-status">
                <h2>
                    <span class="dashicons <?php echo $is_plus ? 'dashicons-yes-alt' : 'dashicons-admin-network'; ?>" style="color:<?php echo $is_plus ? '#22c55e' : '#667eea'; ?>;margin-right:6px;"></span>
                    <?php echo $is_plus ? esc_html__( 'License Active', 'syntekpro-toggle' ) : esc_html__( 'Activate License', 'syntekpro-toggle' ); ?>
                </h2>
                <div class="syntekpro-section-body syntekpro-admin-box">
                    <div class="stp-status-card <?php echo $is_plus ? 'active' : 'inactive'; ?>">
                        <div class="stp-status-icon"><?php echo $is_plus ? '✅' : '🔒'; ?></div>
                        <div class="stp-status-text">
                            <?php if ( $is_plus ) : ?>
                                <strong style="color:#166534;"><?php esc_html_e( 'Toggle+ is Active on this site', 'syntekpro-toggle' ); ?></strong>
                                <span>
                                    <?php if ( $expiry ) : ?>
                                        <?php printf( esc_html__( 'License expires: %s', 'syntekpro-toggle' ), '<strong>' . esc_html( $expiry ) . '</strong>' ); ?>
                                        &nbsp;|&nbsp;
                                    <?php endif; ?>
                                    <?php if ( $masked_key ) : ?>
                                        <?php printf( esc_html__( 'Key: %s', 'syntekpro-toggle' ), '<code>' . esc_html( $masked_key ) . '</code>' ); ?>
                                    <?php endif; ?>
                                </span>
                            <?php else : ?>
                                <strong style="color:#92400e;"><?php esc_html_e( 'Toggle+ is not activated', 'syntekpro-toggle' ); ?></strong>
                                <span><?php esc_html_e( 'Enter your license key below to unlock all premium features.', 'syntekpro-toggle' ); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if ( $is_plus ) : ?>
                    <p style="font-size:13px;color:#555;margin:0 0 16px;"><?php esc_html_e( 'Toggle+ is active and all premium features are unlocked. Deactivating will revoke access on this site and free up your license seat so it can be used elsewhere.', 'syntekpro-toggle' ); ?></p>
                    <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
                        <button type="button" id="stp-deactivate-btn" class="stp-btn-deactivate">
                            <span class="dashicons dashicons-no-alt" style="vertical-align:middle;margin-right:4px;font-size:15px;"></span>
                            <?php esc_html_e( 'Deactivate License', 'syntekpro-toggle' ); ?>
                        </button>
                        <span id="stp-license-msg" class="stp-msg"></span>
                    </div>
                    <?php else : ?>
                    <p style="font-size:13px;color:#555;margin:0 0 14px;"><?php esc_html_e( 'Already purchased? Paste your license key below and click Activate. Your key is emailed to you after purchase.', 'syntekpro-toggle' ); ?></p>
                    <div class="stp-key-row">
                        <input type="text" id="stp-license-key-input" class="stp-key-input"
                            placeholder="<?php esc_attr_e( 'XXXX-XXXX-XXXX-XXXX', 'syntekpro-toggle' ); ?>"
                            autocomplete="off" spellcheck="false" />
                        <button type="button" id="stp-activate-btn" class="stp-btn-activate">
                            <?php esc_html_e( 'Activate License', 'syntekpro-toggle' ); ?>
                        </button>
                    </div>
                    <div id="stp-license-msg" class="stp-msg"></div>
                    <p style="margin-top:12px;font-size:12px;color:#999;">
                        <?php esc_html_e( 'Need help?', 'syntekpro-toggle' ); ?>
                        <a href="https://plugins.syntekpro.com/toggle/docs" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'License activation guide', 'syntekpro-toggle' ); ?></a>
                        &nbsp;|&nbsp;
                        <a href="https://plugins.syntekpro.com/support" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Contact support', 'syntekpro-toggle' ); ?></a>
                    </p>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ( ! $is_plus ) : ?>
            <!-- ── Get Toggle+ CTA ── -->
            <div class="syntekpro-section-panel" id="section-license-buy" data-section="license-buy">
                <h2>
                    <span class="dashicons dashicons-cart" style="color:#667eea;margin-right:6px;"></span>
                    <?php esc_html_e( 'Get Toggle+', 'syntekpro-toggle' ); ?>
                </h2>
                <div class="syntekpro-section-body syntekpro-admin-box">
                    <div class="stp-cta-banner">
                        <h3>⭐ <?php esc_html_e( 'Upgrade to Toggle+', 'syntekpro-toggle' ); ?></h3>
                        <p><?php esc_html_e( 'Unlock all 20 button themes, full analytics dashboards, color presets, advanced media filters and priority support.', 'syntekpro-toggle' ); ?></p>
                        <a href="https://plugins.syntekpro.com/toggle-plus" target="_blank" rel="noopener noreferrer" class="stp-cta-btn">
                            🛒 <?php esc_html_e( 'Get Toggle+ — Buy Now', 'syntekpro-toggle' ); ?>
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- ── Features ── -->
            <div class="syntekpro-section-panel" id="section-license-features" data-section="license-features">
                <h2>
                    <span class="dashicons dashicons-star-filled" style="color:#667eea;margin-right:6px;"></span>
                    <?php echo $is_plus ? esc_html__( 'Your Unlocked Features', 'syntekpro-toggle' ) : esc_html__( 'What You Get with Toggle+', 'syntekpro-toggle' ); ?>
                </h2>
                <div class="syntekpro-section-body syntekpro-admin-box">
                    <div class="stp-features-grid">
                        <div class="stp-feature-item"><span class="stp-fi-icon">🎨</span><span><strong><?php esc_html_e( 'All 20 Button Themes', 'syntekpro-toggle' ); ?></strong><br><?php esc_html_e( 'Neon, Glassmorphic, Cyberpunk, Material, Retro and more.', 'syntekpro-toggle' ); ?></span></div>
                        <div class="stp-feature-item"><span class="stp-fi-icon">📊</span><span><strong><?php esc_html_e( 'Full Analytics Dashboards', 'syntekpro-toggle' ); ?></strong><br><?php esc_html_e( 'Mode preferences, theme popularity charts, and activity logs.', 'syntekpro-toggle' ); ?></span></div>
                        <div class="stp-feature-item"><span class="stp-fi-icon">🌈</span><span><strong><?php esc_html_e( 'Custom Color Control', 'syntekpro-toggle' ); ?></strong><br><?php esc_html_e( 'Unique color presets and fully custom dark-mode palettes.', 'syntekpro-toggle' ); ?></span></div>
                        <div class="stp-feature-item"><span class="stp-fi-icon">🎬</span><span><strong><?php esc_html_e( 'Advanced Media & Embed Filters', 'syntekpro-toggle' ); ?></strong><br><?php esc_html_e( 'Target video selectors and control iframe/embed brightness.', 'syntekpro-toggle' ); ?></span></div>
                        <div class="stp-feature-item"><span class="stp-fi-icon">⚙️</span><span><strong><?php esc_html_e( 'All Advanced Settings', 'syntekpro-toggle' ); ?></strong><br><?php esc_html_e( 'Button sizing, shapes, animations, admin panel depth controls.', 'syntekpro-toggle' ); ?></span></div>
                        <div class="stp-feature-item"><span class="stp-fi-icon">🚀</span><span><strong><?php esc_html_e( 'Priority Support', 'syntekpro-toggle' ); ?></strong><br><?php esc_html_e( 'Direct email support with faster response times.', 'syntekpro-toggle' ); ?></span></div>
                    </div>
                    <?php if ( ! $is_plus ) : ?>
                    <div style="text-align:center;margin-top:20px;">
                        <a href="https://plugins.syntekpro.com/toggle-plus" target="_blank" rel="noopener noreferrer" class="stp-cta-btn" style="background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;box-shadow:0 4px 16px rgba(102,126,234,.4);">
                            🛒 <?php esc_html_e( 'Get Toggle+ Now', 'syntekpro-toggle' ); ?>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ── Compare Table ── -->
            <div class="syntekpro-section-panel" id="section-license-compare" data-section="license-compare">
                <h2>
                    <span class="dashicons dashicons-list-view" style="color:#667eea;margin-right:6px;"></span>
                    <?php esc_html_e( 'Free vs Toggle+', 'syntekpro-toggle' ); ?>
                </h2>
                <div class="syntekpro-section-body syntekpro-admin-box">
                    <table class="stp-compare-table">
                        <thead>
                            <tr>
                                <th style="width:55%;"><?php esc_html_e( 'Feature', 'syntekpro-toggle' ); ?></th>
                                <th><?php esc_html_e( 'Free', 'syntekpro-toggle' ); ?></th>
                                <th><?php esc_html_e( 'Toggle+', 'syntekpro-toggle' ); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td><?php esc_html_e( 'Dark / Light mode toggle button', 'syntekpro-toggle' ); ?></td><td><span class="stp-check">✓</span></td><td><span class="stp-check">✓</span></td></tr>
                            <tr><td><?php esc_html_e( 'OS preference detection & memory', 'syntekpro-toggle' ); ?></td><td><span class="stp-check">✓</span></td><td><span class="stp-check">✓</span></td></tr>
                            <tr><td><?php esc_html_e( 'Admin dark mode', 'syntekpro-toggle' ); ?></td><td><span class="stp-check">✓</span></td><td><span class="stp-check">✓</span></td></tr>
                            <tr><td><?php esc_html_e( 'Image & media filters', 'syntekpro-toggle' ); ?></td><td><span class="stp-check">✓</span></td><td><span class="stp-check">✓</span></td></tr>
                            <tr><td><?php esc_html_e( 'Usage analytics (basic)', 'syntekpro-toggle' ); ?></td><td><span class="stp-check">✓</span></td><td><span class="stp-check">✓</span></td></tr>
                            <tr><td><?php esc_html_e( 'Button themes', 'syntekpro-toggle' ); ?></td><td style="color:#555;"><?php esc_html_e( '3 themes', 'syntekpro-toggle' ); ?></td><td><span class="stp-check">✓</span> <?php esc_html_e( 'All 20', 'syntekpro-toggle' ); ?></td></tr>
                            <tr><td><?php esc_html_e( 'Custom dark-mode color palettes', 'syntekpro-toggle' ); ?></td><td><span class="stp-cross">✗</span></td><td><span class="stp-check">✓</span></td></tr>
                            <tr><td><?php esc_html_e( 'Analytics: Mode preferences chart', 'syntekpro-toggle' ); ?></td><td><span class="stp-cross">✗</span></td><td><span class="stp-check">✓</span></td></tr>
                            <tr><td><?php esc_html_e( 'Analytics: Popular themes chart', 'syntekpro-toggle' ); ?></td><td><span class="stp-cross">✗</span></td><td><span class="stp-check">✓</span></td></tr>
                            <tr><td><?php esc_html_e( 'Analytics: Recent activity log', 'syntekpro-toggle' ); ?></td><td><span class="stp-cross">✗</span></td><td><span class="stp-check">✓</span></td></tr>
                            <tr><td><?php esc_html_e( 'Advanced video & embed filters', 'syntekpro-toggle' ); ?></td><td><span class="stp-cross">✗</span></td><td><span class="stp-check">✓</span></td></tr>
                            <tr><td><?php esc_html_e( 'Advanced button options', 'syntekpro-toggle' ); ?></td><td><span class="stp-cross">✗</span></td><td><span class="stp-check">✓</span></td></tr>
                            <tr><td><?php esc_html_e( 'Priority email support', 'syntekpro-toggle' ); ?></td><td><span class="stp-cross">✗</span></td><td><span class="stp-check">✓</span></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ── Support ── -->
            <div class="syntekpro-section-panel" id="section-license-support" data-section="license-support">
                <h2>
                    <span class="dashicons dashicons-sos" style="color:#667eea;margin-right:6px;"></span>
                    <?php esc_html_e( 'Help & Support', 'syntekpro-toggle' ); ?>
                </h2>
                <div class="syntekpro-section-body syntekpro-admin-box">
                    <p style="font-size:13px;color:#555;margin:0 0 12px;"><?php esc_html_e( 'Having trouble with your license or need help getting started?', 'syntekpro-toggle' ); ?></p>
                    <ul style="font-size:13px;line-height:2.2;margin:0 0 16px;padding-left:18px;color:#555;">
                        <li><a href="https://plugins.syntekpro.com/toggle/docs" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'License activation guide', 'syntekpro-toggle' ); ?></a></li>
                        <li><a href="https://plugins.syntekpro.com/toggle/docs" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Full documentation', 'syntekpro-toggle' ); ?></a></li>
                        <li><a href="https://plugins.syntekpro.com/support" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Support', 'syntekpro-toggle' ); ?></a></li>
                        <li><a href="https://plugins.syntekpro.com/toggle-plus" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Purchase Toggle+', 'syntekpro-toggle' ); ?></a></li>
                    </ul>
                    <p style="font-size:12px;color:#999;margin:0;"><?php esc_html_e( 'Toggle+ license holders receive priority support responses within 1 business day.', 'syntekpro-toggle' ); ?></p>
                </div>
            </div>

        </div><!-- .syntekpro-license-main -->
    </div><!-- .syntekpro-license-layout -->

    <script>
    (function($) {
        'use strict';
        var nonce   = <?php echo wp_json_encode( wp_create_nonce( 'syntekpro_license_nonce' ) ); ?>;
        var ajaxUrl = <?php echo wp_json_encode( admin_url( 'admin-ajax.php' ) ); ?>;

        function showMsg( type, text ) {
            var $msg = $('#stp-license-msg');
            $msg.removeClass('success error').addClass(type).text(text).show();
        }

        $('#stp-activate-btn').on('click', function() {
            var key  = $('#stp-license-key-input').val().trim();
            var $btn = $(this);
            if ( ! key ) {
                showMsg('error', <?php echo wp_json_encode( __( 'Please enter your license key.', 'syntekpro-toggle' ) ); ?>);
                return;
            }
            $btn.prop('disabled', true).text(<?php echo wp_json_encode( __( 'Activating…', 'syntekpro-toggle' ) ); ?>);
            $.post(ajaxUrl, { action: 'syntekpro_toggle_activate_license', nonce: nonce, license_key: key })
                .done(function(res) {
                    if ( res.success ) {
                        showMsg('success', res.data.message);
                        setTimeout(function() { location.reload(); }, 1400);
                    } else {
                        showMsg('error', res.data);
                        $btn.prop('disabled', false).text(<?php echo wp_json_encode( __( 'Activate License', 'syntekpro-toggle' ) ); ?>);
                    }
                })
                .fail(function() {
                    showMsg('error', <?php echo wp_json_encode( __( 'Connection error. Please try again.', 'syntekpro-toggle' ) ); ?>);
                    $btn.prop('disabled', false).text(<?php echo wp_json_encode( __( 'Activate License', 'syntekpro-toggle' ) ); ?>);
                });
        });

        $('#stp-deactivate-btn').on('click', function() {
            if ( ! confirm(<?php echo wp_json_encode( __( 'Deactivate Toggle+ on this site? You can re-activate at any time.', 'syntekpro-toggle' ) ); ?>) ) return;
            var $btn = $(this);
            $btn.prop('disabled', true).text(<?php echo wp_json_encode( __( 'Deactivating…', 'syntekpro-toggle' ) ); ?>);
            $.post(ajaxUrl, { action: 'syntekpro_toggle_deactivate_license', nonce: nonce })
                .done(function(res) {
                    if ( res.success ) {
                        showMsg('success', res.data.message);
                        setTimeout(function() { location.reload(); }, 1400);
                    } else {
                        showMsg('error', res.data);
                        $btn.prop('disabled', false);
                    }
                })
                .fail(function() {
                    showMsg('error', <?php echo wp_json_encode( __( 'Connection error. Please try again.', 'syntekpro-toggle' ) ); ?>);
                    $btn.prop('disabled', false);
                });
        });
    }(jQuery));
    </script>
    <?php
    syntekpro_toggle_page_footer();
}
