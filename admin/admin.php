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
        'Mode Settings - Dark Mode',
        'Mode Settings',
        'manage_options',
        'syntekpro-toggle',
        'syntekpro_toggle_settings_combined_page',
        SYNTEKPRO_TOGGLE_PLUGIN_URL . 'assets/img/Syntekpro%20Toggle%20%20icon%20Grey%20New.png',
        30
    );
    
    // Add submenu pages
    add_submenu_page(
        'syntekpro-toggle',
        'Toggle+',
        'Toggle+',
        'manage_options',
        'syntekpro-toggle-plus',
        'syntekpro_toggle_plus_page'
    );
    
    add_submenu_page(
        'syntekpro-toggle',
        'About',
        'About',
        'manage_options',
        'syntekpro-toggle-about',
        'syntekpro_toggle_about_page'
    );
    
    add_submenu_page(
        'syntekpro-toggle',
        'Other Plugins',
        'Other Plugins',
        'manage_options',
        'syntekpro-toggle-plugins',
        'syntekpro_toggle_plugins_page'
    );
}
add_action('admin_menu', 'syntekpro_toggle_admin_menu');

/**
 * Replace WordPress default footer text with custom message
 */
function syntekpro_toggle_admin_footer_text($footer_text) {
    // Only replace on Syntekpro Toggle pages
    if (isset($_GET['page']) && strpos($_GET['page'], 'syntekpro-toggle') === 0) {
        return 'Thanks For Using <a href="https://plugins.syntekpro.com/toggle" target="_blank" rel="noopener noreferrer">SyntekPro Toggle</a>';
    }
    return $footer_text;
}
add_filter('admin_footer_text', 'syntekpro_toggle_admin_footer_text');

/**
 * Replace WordPress version with plugin version on the right side
 */
function syntekpro_toggle_admin_footer_version($version_text) {
    // Only replace on Syntekpro Toggle pages
    if (isset($_GET['page']) && strpos($_GET['page'], 'syntekpro-toggle') === 0) {
        return '<a href="https://plugins.syntekpro.com/toggle" target="_blank" rel="noopener noreferrer">Version ' . esc_html(SYNTEKPRO_TOGGLE_VERSION) . '</a>';
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
            width: 18px !important;
            height: 18px !important;
            max-width: 18px !important;
            max-height: 18px !important;
            min-width: 18px !important;
            min-height: 18px !important;
            object-fit: contain !important;
            padding: 0 !important;
            margin: 0 !important;
            opacity: 0.7;
            position: relative;
            top: -2px;
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
            if (allowDark && !document.querySelector('.syntekpro-admin-fab')) {
                const fab = document.createElement('button');
                fab.type = 'button';
                fab.className = 'syntekpro-admin-fab theme-<?php echo esc_js(isset($options['admin_toggle_theme']) ? $options['admin_toggle_theme'] : 'default'); ?>';
                fab.setAttribute('aria-label', 'Toggle admin dark mode');
                fab.innerHTML = '<span class="syntekpro-icon-sun" style="display:none"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg></span><span class="syntekpro-icon-moon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg></span>';
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
    if (!isset($_GET['page']) || strpos($_GET['page'], 'syntekpro-toggle') !== 0) {
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
        'General Settings',
        'syntekpro_toggle_general_section_callback',
        'syntekpro-toggle-frontend-general'
    );
    
    add_settings_field(
        'default_mode',
        'Default Mode',
        'syntekpro_toggle_default_mode_callback',
        'syntekpro-toggle-frontend-general',
        'syntekpro_toggle_general_section'
    );
    
    add_settings_field(
        'enable_toggle',
        'Toggle Button',
        'syntekpro_toggle_enable_toggle_callback',
        'syntekpro-toggle-frontend-general',
        'syntekpro_toggle_general_section'
    );
    
    add_settings_field(
        'button_position',
        'Button Position',
        'syntekpro_toggle_button_position_callback',
        'syntekpro-toggle-frontend-general',
        'syntekpro_toggle_general_section'
    );
    
    add_settings_field(
        'button_size',
        'Button Size',
        'syntekpro_toggle_button_size_callback',
        'syntekpro-toggle-frontend-general',
        'syntekpro_toggle_general_section'
    );
    
    add_settings_field(
        'toggle_theme',
        'Toggle Button Theme',
        'syntekpro_toggle_theme_callback',
        'syntekpro-toggle-frontend-general',
        'syntekpro_toggle_general_section'
    );
    
    add_settings_field(
        'button_shape',
        'Button Shape',
        'syntekpro_toggle_button_shape_callback',
        'syntekpro-toggle-frontend-general',
        'syntekpro_toggle_general_section'
    );
    
    add_settings_field(
        'button_animation',
        'Button Animation',
        'syntekpro_toggle_button_animation_callback',
        'syntekpro-toggle-frontend-general',
        'syntekpro_toggle_general_section'
    );
    
    add_settings_field(
        'button_bg_style',
        'Button Background Style',
        'syntekpro_toggle_button_bg_style_callback',
        'syntekpro-toggle-frontend-general',
        'syntekpro_toggle_general_section'
    );
    
    // Frontend Page - Color Scheme Section
    add_settings_section(
        'syntekpro_toggle_color_scheme_section',
        '🎨 Dark Mode Color Scheme',
        'syntekpro_toggle_color_scheme_section_callback',
        'syntekpro-toggle-frontend-colors'
    );
    
    add_settings_field(
        'color_scheme_mode',
        'Color Scheme Mode',
        'syntekpro_toggle_color_scheme_mode_callback',
        'syntekpro-toggle-frontend-colors',
        'syntekpro_toggle_color_scheme_section'
    );
    
    add_settings_field(
        'color_preset',
        'Color Preset',
        'syntekpro_toggle_color_preset_callback',
        'syntekpro-toggle-frontend-colors',
        'syntekpro_toggle_color_scheme_section'
    );
    
    add_settings_field(
        'bg_color',
        'Background Color',
        'syntekpro_toggle_bg_color_callback',
        'syntekpro-toggle-frontend-colors',
        'syntekpro_toggle_color_scheme_section'
    );
    
    add_settings_field(
        'text_color',
        'Text Color',
        'syntekpro_toggle_text_color_callback',
        'syntekpro-toggle-frontend-colors',
        'syntekpro_toggle_color_scheme_section'
    );
    
    add_settings_field(
        'link_color',
        'Link Color',
        'syntekpro_toggle_link_color_callback',
        'syntekpro-toggle-frontend-colors',
        'syntekpro_toggle_color_scheme_section'
    );
    
    add_settings_field(
        'secondary_bg_color',
        'Secondary Background',
        'syntekpro_toggle_secondary_bg_callback',
        'syntekpro-toggle-frontend-colors',
        'syntekpro_toggle_color_scheme_section'
    );
    
    // Frontend Page - Color Adjustments Section
    add_settings_section(
        'syntekpro_toggle_color_adjustments_section',
        '🎚️ Color Adjustments',
        'syntekpro_toggle_color_adjustments_section_callback',
        'syntekpro-toggle-frontend-adjustments'
    );
    
    add_settings_field(
        'brightness',
        '☀️ Brightness',
        'syntekpro_toggle_brightness_callback',
        'syntekpro-toggle-frontend-adjustments',
        'syntekpro_toggle_color_adjustments_section'
    );
    
    add_settings_field(
        'contrast',
        '🔲 Contrast',
        'syntekpro_toggle_contrast_callback',
        'syntekpro-toggle-frontend-adjustments',
        'syntekpro_toggle_color_adjustments_section'
    );
    
    add_settings_field(
        'sepia',
        '📜 Sepia',
        'syntekpro_toggle_sepia_callback',
        'syntekpro-toggle-frontend-adjustments',
        'syntekpro_toggle_color_adjustments_section'
    );
    
    add_settings_field(
        'grayscale',
        '⚫ Grayscale',
        'syntekpro_toggle_grayscale_callback',
        'syntekpro-toggle-frontend-adjustments',
        'syntekpro_toggle_color_adjustments_section'
    );
    
    // Images Settings Section
    add_settings_section(
        'syntekpro_toggle_images_section',
        '🖼️ Images Settings',
        'syntekpro_toggle_images_section_callback',
        'syntekpro-toggle-frontend-images'
    );
    
    add_settings_field(
        'enable_image_filter',
        'Enable Image Filters',
        'syntekpro_toggle_enable_image_filter_callback',
        'syntekpro-toggle-frontend-images',
        'syntekpro_toggle_images_section'
    );
    
    add_settings_field(
        'image_brightness',
        'Image Brightness',
        'syntekpro_toggle_image_brightness_callback',
        'syntekpro-toggle-frontend-images',
        'syntekpro_toggle_images_section'
    );
    
    add_settings_field(
        'image_contrast',
        'Image Contrast',
        'syntekpro_toggle_image_contrast_callback',
        'syntekpro-toggle-frontend-images',
        'syntekpro_toggle_images_section'
    );
    
    // Videos Settings Section
    add_settings_section(
        'syntekpro_toggle_videos_section',
        '🎬 Videos Settings',
        'syntekpro_toggle_videos_section_callback',
        'syntekpro-toggle-frontend-videos'
    );
    
    add_settings_field(
        'enable_video_filter',
        'Enable Video Filters',
        'syntekpro_toggle_enable_video_filter_callback',
        'syntekpro-toggle-frontend-videos',
        'syntekpro_toggle_videos_section'
    );
    
    add_settings_field(
        'video_brightness',
        'Video Brightness',
        'syntekpro_toggle_video_brightness_callback',
        'syntekpro-toggle-frontend-videos',
        'syntekpro_toggle_videos_section'
    );
    
    add_settings_field(
        'video_contrast',
        'Video Contrast',
        'syntekpro_toggle_video_contrast_callback',
        'syntekpro-toggle-frontend-videos',
        'syntekpro_toggle_videos_section'
    );
    
    // Slides Settings Section
    add_settings_section(
        'syntekpro_toggle_slides_section',
        '📊 Slides Settings',
        'syntekpro_toggle_slides_section_callback',
        'syntekpro-toggle-frontend-slides'
    );
    
    add_settings_field(
        'enable_slide_filter',
        'Enable Slide Filters',
        'syntekpro_toggle_enable_slide_filter_callback',
        'syntekpro-toggle-frontend-slides',
        'syntekpro_toggle_slides_section'
    );
    
    add_settings_field(
        'slide_brightness',
        'Slide Brightness',
        'syntekpro_toggle_slide_brightness_callback',
        'syntekpro-toggle-frontend-slides',
        'syntekpro_toggle_slides_section'
    );
    
    add_settings_field(
        'slide_invert',
        'Slide Invert Colors',
        'syntekpro_toggle_slide_invert_callback',
        'syntekpro-toggle-frontend-slides',
        'syntekpro_toggle_slides_section'
    );
    
    // Admin UI Page - Admin UI Section
    add_settings_section(
        'syntekpro_toggle_admin_ui_section',
        'Admin UI Settings',
        'syntekpro_toggle_admin_ui_section_callback',
        'syntekpro-toggle-admin-ui'
    );

    add_settings_field(
        'enable_admin_dark_mode',
        'Admin Dark Mode',
        'syntekpro_toggle_admin_dark_mode_callback',
        'syntekpro-toggle-admin-ui',
        'syntekpro_toggle_admin_ui_section'
    );

    add_settings_field(
        'enable_admin_bar_icon',
        'Top Bar Icon',
        'syntekpro_toggle_admin_bar_icon_callback',
        'syntekpro-toggle-admin-ui',
        'syntekpro_toggle_admin_ui_section'
    );

    add_settings_field(
        'enable_dashboard_widget',
        'Dashboard Widget',
        'syntekpro_toggle_admin_dashboard_widget_callback',
        'syntekpro-toggle-admin-ui',
        'syntekpro_toggle_admin_ui_section'
    );
    
    add_settings_field(
        'admin_toggle_theme',
        'Admin Toggle Theme',
        'syntekpro_toggle_admin_theme_callback',
        'syntekpro-toggle-admin-ui',
        'syntekpro_toggle_admin_ui_section'
    );
    
    // Admin UI Page - Color Scheme Section
    add_settings_section(
        'syntekpro_toggle_admin_color_section',
        '🎨 Admin UI Color Scheme',
        'syntekpro_toggle_admin_color_section_callback',
        'syntekpro-toggle-admin-color'
    );
    
    add_settings_field(
        'admin_color_scheme_mode',
        'Color Mode',
        'syntekpro_toggle_admin_color_scheme_mode_callback',
        'syntekpro-toggle-admin-color',
        'syntekpro_toggle_admin_color_section'
    );
    
    add_settings_field(
        'admin_color_preset',
        'Color Presets',
        'syntekpro_toggle_admin_color_preset_callback',
        'syntekpro-toggle-admin-color',
        'syntekpro_toggle_admin_color_section'
    );
    
    add_settings_field(
        'admin_bg_color',
        'Admin Background',
        'syntekpro_toggle_admin_bg_color_callback',
        'syntekpro-toggle-admin-color',
        'syntekpro_toggle_admin_color_section'
    );

    add_settings_field(
        'admin_text_color',
        'Admin Text',
        'syntekpro_toggle_admin_text_color_callback',
        'syntekpro-toggle-admin-color',
        'syntekpro_toggle_admin_color_section'
    );

    add_settings_field(
        'admin_accent_color',
        'Admin Accent',
        'syntekpro_toggle_admin_accent_color_callback',
        'syntekpro-toggle-admin-color',
        'syntekpro_toggle_admin_color_section'
    );

    add_settings_field(
        'admin_surface_color',
        'Admin Surface',
        'syntekpro_toggle_admin_surface_color_callback',
        'syntekpro-toggle-admin-color',
        'syntekpro_toggle_admin_color_section'
    );

    add_settings_field(
        'admin_border_color',
        'Admin Border',
        'syntekpro_toggle_admin_border_color_callback',
        'syntekpro-toggle-admin-color',
        'syntekpro_toggle_admin_color_section'
    );

    add_settings_field(
        'admin_link_color',
        'Admin Link',
        'syntekpro_toggle_admin_link_color_callback',
        'syntekpro-toggle-admin-color',
        'syntekpro_toggle_admin_color_section'
    );

    add_settings_field(
        'admin_link_hover_color',
        'Admin Link Hover',
        'syntekpro_toggle_admin_link_hover_color_callback',
        'syntekpro-toggle-admin-color',
        'syntekpro_toggle_admin_color_section'
    );
    
    // Advanced Settings Section - Now MERGED INTO FRONTEND PAGE
    add_settings_section(
        'syntekpro_toggle_advanced_section',
        'Advanced Settings',
        'syntekpro_toggle_advanced_section_callback',
        'syntekpro-toggle-frontend-advanced'
    );
    
    add_settings_field(
        'custom_css',
        'Custom CSS',
        'syntekpro_toggle_custom_css_callback',
        'syntekpro-toggle-frontend-advanced',
        'syntekpro_toggle_advanced_section'
    );
    
    add_settings_field(
        'transition_speed',
        'Transition Speed',
        'syntekpro_toggle_transition_speed_callback',
        'syntekpro-toggle-frontend-advanced',
        'syntekpro_toggle_advanced_section'
    );
    
    // Analytics Page - Analytics Settings Section
    add_settings_section(
        'syntekpro_toggle_analytics_section',
        '📊 Analytics Settings',
        'syntekpro_toggle_analytics_section_callback',
        'syntekpro-toggle-analytics-settings'
    );
    
    add_settings_field(
        'enable_analytics',
        'Enable Analytics',
        'syntekpro_toggle_enable_analytics_callback',
        'syntekpro-toggle-analytics-settings',
        'syntekpro_toggle_analytics_section'
    );
    
    add_settings_field(
        'analytics_track_toggles',
        'Track Toggle Clicks',
        'syntekpro_toggle_analytics_track_toggles_callback',
        'syntekpro-toggle-analytics-settings',
        'syntekpro_toggle_analytics_section'
    );
    
    add_settings_field(
        'analytics_track_pageviews',
        'Track Page Views',
        'syntekpro_toggle_analytics_track_pageviews_callback',
        'syntekpro-toggle-analytics-settings',
        'syntekpro_toggle_analytics_section'
    );
    
    add_settings_field(
        'analytics_track_modes',
        'Track Mode Changes',
        'syntekpro_toggle_analytics_track_modes_callback',
        'syntekpro-toggle-analytics-settings',
        'syntekpro_toggle_analytics_section'
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
        'enable_admin_bar_icon' => '1',
        'enable_dashboard_widget' => '1',
        'enable_admin_dark_mode' => '1',
        'admin_toggle_theme' => 'default',
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
        'analytics_track_modes' => '1'
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
    
    // Checkboxes need special handling - they're only present when checked
    // We need to check if the form containing this field was submitted
    if (array_key_exists('enable_toggle', $input)) {
        $sanitized['enable_toggle'] = isset($input['enable_toggle']) ? '1' : '0';
    }
    
    if (isset($input['button_position'])) {
        $sanitized['button_position'] = sanitize_text_field($input['button_position']);
    }
    
    if (isset($input['button_size'])) {
        $sanitized['button_size'] = absint($input['button_size']);
    }
    
    if (isset($input['toggle_theme'])) {
        $sanitized['toggle_theme'] = sanitize_text_field($input['toggle_theme']);
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
    
    if (array_key_exists('enable_admin_bar_icon', $input)) {
        $sanitized['enable_admin_bar_icon'] = isset($input['enable_admin_bar_icon']) ? '1' : '0';
    }
    
    if (array_key_exists('enable_dashboard_widget', $input)) {
        $sanitized['enable_dashboard_widget'] = isset($input['enable_dashboard_widget']) ? '1' : '0';
    }
    
    if (array_key_exists('enable_admin_dark_mode', $input)) {
        $sanitized['enable_admin_dark_mode'] = isset($input['enable_admin_dark_mode']) ? '1' : '0';
    }
    
    if (isset($input['admin_toggle_theme'])) {
        $sanitized['admin_toggle_theme'] = sanitize_text_field($input['admin_toggle_theme']);
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
    
    return $sanitized;
}

/**
 * Section callbacks
 */
function syntekpro_toggle_general_section_callback() {
    echo '<p>Configure the general behavior of the dark mode toggle.</p>';
}

function syntekpro_toggle_color_scheme_section_callback() {
    echo '<p>Choose how dark mode colors are applied: Dynamic (smart auto-adjust), Presets (curated color schemes), or Custom (manual control).</p>';
}

function syntekpro_toggle_color_adjustments_section_callback() {
    echo '<p>Fine-tune the visual appearance with brightness, contrast, sepia, and grayscale filters.</p>';
}

function syntekpro_toggle_images_section_callback() {
    echo '<p>Apply filters and adjustments to images in dark mode for better visibility and consistency.</p>';
}

function syntekpro_toggle_videos_section_callback() {
    echo '<p>Apply filters and adjustments to videos in dark mode for improved viewing experience.</p>';
}

function syntekpro_toggle_slides_section_callback() {
    echo '<p>Apply filters and adjustments to presentation slides (SlideShare, Impress, etc.) in dark mode.</p>';
}

function syntekpro_toggle_advanced_section_callback() {
    echo '<p>Advanced customization options for power users.</p>';
}

/**
 * Field callbacks
 */
function syntekpro_toggle_default_mode_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <select name="syntekpro_toggle_options[default_mode]" id="default_mode">
        <option value="auto" <?php selected($options['default_mode'], 'auto'); ?>>Auto (Follow OS Preference)</option>
        <option value="light" <?php selected($options['default_mode'], 'light'); ?>>Light Mode</option>
        <option value="dark" <?php selected($options['default_mode'], 'dark'); ?>>Dark Mode</option>
        <option value="manual" <?php selected($options['default_mode'], 'manual'); ?>>Manual Only (User Chooses)</option>
    </select>
    <p class="description">Set the default mode when users first visit your site.</p>
    <?php
}

function syntekpro_toggle_enable_toggle_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <label>
        <input type="checkbox" name="syntekpro_toggle_options[enable_toggle]" value="1" <?php checked($options['enable_toggle'], '1'); ?>>
        Show toggle button on frontend
    </label>
    <p class="description">Uncheck to hide the toggle button (useful if using shortcode or widget).</p>
    <?php
}

function syntekpro_toggle_button_position_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <select name="syntekpro_toggle_options[button_position]" id="button_position">
        <option value="bottom-right" <?php selected($options['button_position'], 'bottom-right'); ?>>Bottom Right</option>
        <option value="bottom-left" <?php selected($options['button_position'], 'bottom-left'); ?>>Bottom Left</option>
        <option value="top-right" <?php selected($options['button_position'], 'top-right'); ?>>Top Right</option>
        <option value="top-left" <?php selected($options['button_position'], 'top-left'); ?>>Top Left</option>
    </select>
    <p class="description">Choose where to display the toggle button.</p>
    <?php
}

function syntekpro_toggle_button_size_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="number" name="syntekpro_toggle_options[button_size]" value="<?php echo esc_attr($options['button_size']); ?>" min="30" max="100" step="5">
    <span>px</span>
    <p class="description">Button size in pixels (30-100).</p>
    <?php
}

/**
 * Check if a feature is premium (requires Toggle+)
 */
function syntekpro_toggle_is_premium_feature($feature_type, $feature_id) {
    // Features 1-5 are free (indices 0-4)
    if ($feature_type === 'theme') {
        $free_themes = array(
            'default', 'minimal', 'neumorphic', 'glassmorphic', 'neon'
        );
        return !in_array($feature_id, $free_themes, true);
    }
    
    // Only 'default' preset is free
    if ($feature_type === 'preset') {
        return $feature_id !== 'default';
    }
    
    return false;
}

function syntekpro_toggle_theme_callback() {
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
    );
    ?>
    <div class="syntekpro-toggle-themes-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 15px; margin-top: 10px;">
        <?php foreach ($themes as $key => $theme):
            $is_premium = syntekpro_toggle_is_premium_feature('theme', $key);
        ?>
            <label class="theme-option <?php echo $is_premium ? 'premium-locked' : ''; ?>" style="cursor: <?php echo $is_premium ? 'not-allowed' : 'pointer'; ?>; border: 2px solid <?php echo $is_premium ? '#f0ad4e' : '#ddd'; ?>; border-radius: 8px; padding: 15px; transition: all 0.3s; text-align: center; position: relative; <?php echo $is_premium ? 'opacity: 0.7; background: #fffbf0;' : ''; ?>" title="<?php echo $is_premium ? 'Premium - Toggle+ Required' : 'Free'; ?>">
                <input type="radio" name="syntekpro_toggle_options[toggle_theme]" value="<?php echo esc_attr($key); ?>" <?php checked($options['toggle_theme'], $key); echo $is_premium ? ' disabled' : ''; ?> style="margin-bottom: 10px;">
                
                <!-- Premium Lock Badge -->
                <?php if ($is_premium): ?>
                <div style="position: absolute; top: 8px; right: 8px; background: #f0ad4e; color: white; width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: bold; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                    🔒
                </div>
                <?php endif; ?>
                
                <div class="theme-preview syntekpro-theme-<?php echo esc_attr($key); ?>" style="width: 50px; height: 50px; margin: 0 auto 10px; position: relative; display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                    </svg>
                </div>
                <strong style="display: block; margin-bottom: 3px;"><?php echo esc_html($theme['name']); ?></strong>
                <span style="font-size: 11px; color: <?php echo $is_premium ? '#f0ad4e' : '#666'; ?>; display: block;">
                    <?php echo esc_html($theme['desc']); ?>
                    <?php echo $is_premium ? '<br><strong style="color: #f0ad4e;">🔒 Premium</strong>' : ''; ?>
                </span>
            </label>
        <?php endforeach; ?>
    </div>
    <div style="background: #fff3cd; border: 1px solid #ffc107; padding: 12px; border-radius: 6px; margin-top: 15px; font-size: 13px; color: #856404;">
        <strong>💡 Tip:</strong> Lock icons indicate premium features. <a href="?page=syntekpro-toggle-plus" style="color: #0066cc; text-decoration: none; font-weight: 600;">Unlock all features with Toggle+ →</a>
    </div>
    <style>
        .theme-option:hover:not(.premium-locked) { border-color: #2271b1; background: #f0f6fc; transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .theme-option:has(input[type="radio"]:checked) { border-color: #2271b1; border-width: 3px; background: #f0f6fc; }
        .premium-locked:hover { border-color: #f0ad4e; opacity: 0.7; }
        
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
    <p class="description">Choose a visual style for your toggle button.</p>
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
    <p class="description">Choose the button shape or size variation. Pill and Vertical shapes will display the moon/sun icon next to text.</p>
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
    <p class="description">Choose a continuous animation for your button. Animations pause on click and respond to hover states.</p>
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
    <p class="description">Choose a background pattern style for your button.</p>
    <?php
}

function syntekpro_toggle_bg_color_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="text" name="syntekpro_toggle_options[bg_color]" value="<?php echo esc_attr($options['bg_color']); ?>" class="syntekpro-color-picker">
    <p class="description">Main background color for dark mode.</p>
    <?php
}

function syntekpro_toggle_text_color_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="text" name="syntekpro_toggle_options[text_color]" value="<?php echo esc_attr($options['text_color']); ?>" class="syntekpro-color-picker">
    <p class="description">Text color for dark mode.</p>
    <?php
}

function syntekpro_toggle_link_color_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="text" name="syntekpro_toggle_options[link_color]" value="<?php echo esc_attr($options['link_color']); ?>" class="syntekpro-color-picker">
    <p class="description">Link color for dark mode.</p>
    <?php
}

function syntekpro_toggle_secondary_bg_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="text" name="syntekpro_toggle_options[secondary_bg_color]" value="<?php echo esc_attr($options['secondary_bg_color']); ?>" class="syntekpro-color-picker">
    <p class="description">Secondary background color (headers, sidebars, widgets).</p>
    <?php
}

function syntekpro_toggle_color_scheme_mode_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <div class="syntekpro-color-scheme-modes">
        <label class="syntekpro-mode-option">
            <input type="radio" name="syntekpro_toggle_options[color_scheme_mode]" value="preset" <?php checked($options['color_scheme_mode'], 'preset'); ?>>
            <span class="mode-icon">🎨</span>
            <strong>Presets</strong>
            <p class="description">Choose from curated color schemes</p>
        </label>
        <label class="syntekpro-mode-option">
            <input type="radio" name="syntekpro_toggle_options[color_scheme_mode]" value="custom" <?php checked($options['color_scheme_mode'], 'custom'); ?>>
            <span class="mode-icon">🎛️</span>
            <strong>Custom</strong>
            <p class="description">Manually configure all colors</p>
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
        <strong>Debug Info:</strong><br>
        Color Scheme Mode: <code><?php echo esc_html($current_mode); ?></code> | 
        Color Preset: <code><?php echo esc_html($current_preset); ?></code>
    </div>
    <div id="preset-container" style="<?php echo $options['color_scheme_mode'] !== 'preset' ? 'display:none;' : ''; ?>">
        <div class="syntekpro-preset-grid">
            <?php foreach ($presets as $key => $preset):
                $is_premium = syntekpro_toggle_is_premium_feature('preset', $key);
            ?>
                <label class="syntekpro-preset-card <?php echo $is_premium ? 'premium-locked' : ''; ?>" style="<?php echo $is_premium ? 'opacity: 0.7; background: #fffbf0; border-color: #f0ad4e;' : ''; ?>">
                    <input type="radio" name="syntekpro_toggle_options[color_preset]" value="<?php echo esc_attr($key); ?>" <?php checked($options['color_preset'], $key); echo $is_premium ? ' disabled' : ''; ?>>
                    
                    <?php if ($is_premium): ?>
                    <div style="position: absolute; top: 8px; right: 8px; background: #f0ad4e; color: white; width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: bold; box-shadow: 0 2px 4px rgba(0,0,0,0.2); z-index: 5;">
                        🔒
                    </div>
                    <?php endif; ?>
                    
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
                            <?php 
                            echo esc_html($preset['name']);
                            if ($is_premium) {
                                echo '<div style="font-size: 9px; margin-top: 2px; color: #f0ad4e;">🔒 PREMIUM</div>';
                            }
                            ?>
                        </div>
                        
                        <!-- Selected Label -->
                        <div class="preset-selected-label" style="display: none; position: absolute; bottom: 5px; left: 50%; transform: translateX(-50%); background: rgba(34, 113, 177, 0.9); color: white; padding: 3px 8px; border-radius: 4px; font-size: 10px; font-weight: bold; white-space: nowrap;">
                            SELECTED
                        </div>
                    </div>
                </label>
            <?php endforeach; ?>
        </div>
    </div>
    <div style="background: #fff3cd; border: 1px solid #ffc107; padding: 12px; border-radius: 6px; margin-top: 15px; font-size: 13px; color: #856404; display: <?php echo $options['color_scheme_mode'] !== 'preset' ? 'none' : 'block'; ?>">
        <strong>💡 Tip:</strong> Lock icons indicate premium presets. <a href="?page=syntekpro-toggle-plus" style="color: #0066cc; text-decoration: none; font-weight: 600;">Get Toggle+ for all presets →</a>
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
        
        .syntekpro-preset-card.premium-locked {
            cursor: not-allowed;
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
        
        .syntekpro-preset-card.premium-locked:hover {
            border-color: #f0ad4e;
            transform: none;
            box-shadow: 0 4px 8px rgba(240, 173, 78, 0.2);
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
    <p class="description">Adjust overall brightness (0-200%, default: 100%)</p>
    <?php
}

function syntekpro_toggle_contrast_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="range" name="syntekpro_toggle_options[contrast]" value="<?php echo esc_attr($options['contrast']); ?>" min="0" max="200" step="1" oninput="this.nextElementSibling.value = this.value + '%'">
    <output><?php echo esc_attr($options['contrast']); ?>%</output>
    <p class="description">Adjust contrast between colors (0-200%, default: 100%)</p>
    <?php
}

function syntekpro_toggle_sepia_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="range" name="syntekpro_toggle_options[sepia]" value="<?php echo esc_attr($options['sepia']); ?>" min="0" max="100" step="1" oninput="this.nextElementSibling.value = this.value + '%'">
    <output><?php echo esc_attr($options['sepia']); ?>%</output>
    <p class="description">Apply sepia filter for a vintage look (0-100%, default: 0%)</p>
    <?php
}

function syntekpro_toggle_grayscale_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="range" name="syntekpro_toggle_options[grayscale]" value="<?php echo esc_attr($options['grayscale']); ?>" min="0" max="100" step="1" oninput="this.nextElementSibling.value = this.value + '%'">
    <output><?php echo esc_attr($options['grayscale']); ?>%</output>
    <p class="description">Convert to grayscale (0-100%, default: 0%)</p>
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
        Apply filters to images in dark mode
    </label>
    <p class="description">Enable automatic filter adjustments for images when dark mode is active.</p>
    <?php
}

function syntekpro_toggle_image_brightness_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="range" name="syntekpro_toggle_options[image_brightness]" value="<?php echo esc_attr($options['image_brightness']); ?>" min="0" max="150" step="1" oninput="this.nextElementSibling.value = this.value + '%'">
    <output><?php echo esc_attr($options['image_brightness']); ?>%</output>
    <p class="description">Adjust image brightness in dark mode (50-150%, default: 100% = normal)</p>
    <?php
}

function syntekpro_toggle_image_contrast_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="range" name="syntekpro_toggle_options[image_contrast]" value="<?php echo esc_attr($options['image_contrast']); ?>" min="0" max="200" step="1" oninput="this.nextElementSibling.value = this.value + '%'">
    <output><?php echo esc_attr($options['image_contrast']); ?>%</output>
    <p class="description">Adjust image contrast in dark mode (50-200%, default: 100% = normal)</p>
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
        Apply filters to videos in dark mode
    </label>
    <p class="description">Enable automatic filter adjustments for embedded videos when dark mode is active.</p>
    <?php
}

function syntekpro_toggle_video_brightness_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="range" name="syntekpro_toggle_options[video_brightness]" value="<?php echo esc_attr($options['video_brightness']); ?>" min="0" max="150" step="1" oninput="this.nextElementSibling.value = this.value + '%'">
    <output><?php echo esc_attr($options['video_brightness']); ?>%</output>
    <p class="description">Adjust video brightness in dark mode (50-150%, default: 100% = normal)</p>
    <?php
}

function syntekpro_toggle_video_contrast_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="range" name="syntekpro_toggle_options[video_contrast]" value="<?php echo esc_attr($options['video_contrast']); ?>" min="0" max="200" step="1" oninput="this.nextElementSibling.value = this.value + '%'">
    <output><?php echo esc_attr($options['video_contrast']); ?>%</output>
    <p class="description">Adjust video contrast in dark mode (50-200%, default: 100% = normal)</p>
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
        Apply filters to slides in dark mode
    </label>
    <p class="description">Enable automatic filter adjustments for presentation slides when dark mode is active.</p>
    <?php
}

function syntekpro_toggle_slide_brightness_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="range" name="syntekpro_toggle_options[slide_brightness]" value="<?php echo esc_attr($options['slide_brightness']); ?>" min="0" max="150" step="1" oninput="this.nextElementSibling.value = this.value + '%'">
    <output><?php echo esc_attr($options['slide_brightness']); ?>%</output>
    <p class="description">Adjust slide brightness in dark mode (50-150%, default: 100% = normal)</p>
    <?php
}

function syntekpro_toggle_slide_invert_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <label>
        <input type="checkbox" name="syntekpro_toggle_options[slide_invert]" value="1" <?php checked($options['slide_invert'], '1'); ?>>
        Invert slide colors
    </label>
    <p class="description">Invert colors on slides for better visibility in dark mode.</p>
    <?php
}

function syntekpro_toggle_custom_css_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <textarea name="syntekpro_toggle_options[custom_css]" rows="8" cols="50" class="large-text code"><?php echo esc_textarea($options['custom_css']); ?></textarea>
    <p class="description">Add custom CSS for dark mode. Will be wrapped in <code>html.dark-mode { }</code></p>
    <?php
}

function syntekpro_toggle_transition_speed_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="number" name="syntekpro_toggle_options[transition_speed]" value="<?php echo esc_attr($options['transition_speed']); ?>" min="0" max="2" step="0.1">
    <span>seconds</span>
    <p class="description">Color transition speed (0-2 seconds, 0 = instant).</p>
    <?php
}

function syntekpro_toggle_analytics_section_callback() {
    echo '<p>Configure what analytics data to track about dark mode usage on your site.</p>';
}

function syntekpro_toggle_enable_analytics_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <label>
        <input type="checkbox" name="syntekpro_toggle_options[enable_analytics]" value="1" <?php checked($options['enable_analytics'], '1'); ?>>
        Enable analytics tracking
    </label>
    <p class="description">Master switch for all analytics tracking. When disabled, no data is collected.</p>
    <?php
}

function syntekpro_toggle_analytics_track_toggles_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <label>
        <input type="checkbox" name="syntekpro_toggle_options[analytics_track_toggles]" value="1" <?php checked($options['analytics_track_toggles'], '1'); ?>>
        Track toggle button clicks
    </label>
    <p class="description">Count how many times users click the dark/light mode toggle button.</p>
    <?php
}

function syntekpro_toggle_analytics_track_pageviews_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <label>
        <input type="checkbox" name="syntekpro_toggle_options[analytics_track_pageviews]" value="1" <?php checked($options['analytics_track_pageviews'], '1'); ?>>
        Track page views
    </label>
    <p class="description">Count total page views where the toggle button is displayed.</p>
    <?php
}

function syntekpro_toggle_analytics_track_modes_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <label>
        <input type="checkbox" name="syntekpro_toggle_options[analytics_track_modes]" value="1" <?php checked($options['analytics_track_modes'], '1'); ?>>
        Track mode preferences
    </label>
    <p class="description">Track whether users prefer dark or light mode.</p>
    <?php
}

function syntekpro_toggle_admin_ui_section_callback() {
    echo '<p>Control admin UI helpers like dark mode, top bar icon, and dashboard widget.</p>';
}

function syntekpro_toggle_admin_dark_mode_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <label>
        <input type="checkbox" name="syntekpro_toggle_options[enable_admin_dark_mode]" value="1" <?php checked($options['enable_admin_dark_mode'], '1'); ?>>
        Enable admin dark mode toggle (per browser)
    </label>
    <p class="description">Adds a dark/light toggle for the WordPress admin UI (state saved in localStorage).</p>
    <?php
}

function syntekpro_toggle_admin_bar_icon_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <label>
        <input type="checkbox" name="syntekpro_toggle_options[enable_admin_bar_icon]" value="1" <?php checked($options['enable_admin_bar_icon'], '1'); ?>>
        Show top bar icon (also triggers admin dark toggle)
    </label>
    <p class="description">Adds a small icon to the WordPress admin bar for quick access.</p>
    <?php
}

function syntekpro_toggle_admin_dashboard_widget_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <label>
        <input type="checkbox" name="syntekpro_toggle_options[enable_dashboard_widget]" value="1" <?php checked($options['enable_dashboard_widget'], '1'); ?>>
        Show dashboard widget
    </label>
    <p class="description">Displays the Syntekpro Toggle status widget on the WordPress Dashboard.</p>
    <?php
}

function syntekpro_toggle_admin_bg_color_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="text" name="syntekpro_toggle_options[admin_bg_color]" value="<?php echo esc_attr($options['admin_bg_color']); ?>" class="syntekpro-color-picker">
    <p class="description">Background color for admin dark mode.</p>
    <?php
}

function syntekpro_toggle_admin_text_color_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="text" name="syntekpro_toggle_options[admin_text_color]" value="<?php echo esc_attr($options['admin_text_color']); ?>" class="syntekpro-color-picker">
    <p class="description">Primary text color for admin dark mode.</p>
    <?php
}

function syntekpro_toggle_admin_accent_color_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="text" name="syntekpro_toggle_options[admin_accent_color]" value="<?php echo esc_attr($options['admin_accent_color']); ?>" class="syntekpro-color-picker">
    <p class="description">Accent color for buttons and highlights.</p>
    <?php
}

function syntekpro_toggle_admin_surface_color_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="text" name="syntekpro_toggle_options[admin_surface_color]" value="<?php echo esc_attr($options['admin_surface_color']); ?>" class="syntekpro-color-picker">
    <p class="description">Surface color for cards, boxes, and panels.</p>
    <?php
}

function syntekpro_toggle_admin_border_color_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="text" name="syntekpro_toggle_options[admin_border_color]" value="<?php echo esc_attr($options['admin_border_color']); ?>" class="syntekpro-color-picker">
    <p class="description">Border color for admin elements.</p>
    <?php
}

function syntekpro_toggle_admin_link_color_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="text" name="syntekpro_toggle_options[admin_link_color]" value="<?php echo esc_attr($options['admin_link_color']); ?>" class="syntekpro-color-picker">
    <p class="description">Link color for admin dark mode.</p>
    <?php
}

function syntekpro_toggle_admin_link_hover_color_callback() {
    $options = syntekpro_toggle_get_options();
    ?>
    <input type="text" name="syntekpro_toggle_options[admin_link_hover_color]" value="<?php echo esc_attr($options['admin_link_hover_color']); ?>" class="syntekpro-color-picker">
    <p class="description">Link hover color for admin dark mode.</p>
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
    <p class="description">Choose theme for the admin floating toggle button.</p>
    <?php
}

function syntekpro_toggle_admin_color_section_callback() {
    echo '<p>Choose color presets for admin dark mode or customize manually.</p>';
}

function syntekpro_toggle_admin_color_scheme_mode_callback() {
    $options = syntekpro_toggle_get_options();
    $admin_mode = isset($options['admin_color_scheme_mode']) ? $options['admin_color_scheme_mode'] : 'preset';
    ?>
    <div class="syntekpro-color-scheme-modes">
        <label class="syntekpro-mode-option">
            <input type="radio" name="syntekpro_toggle_options[admin_color_scheme_mode]" value="preset" <?php checked($admin_mode, 'preset'); ?>>
            <span class="mode-icon">🎨</span>
            <strong>Presets</strong>
            <p class="description">Choose from curated admin themes</p>
        </label>
        <label class="syntekpro-mode-option">
            <input type="radio" name="syntekpro_toggle_options[admin_color_scheme_mode]" value="custom" <?php checked($admin_mode, 'custom'); ?>>
            <span class="mode-icon">🎛️</span>
            <strong>Custom</strong>
            <p class="description">Manually configure admin colors</p>
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
function syntekpro_toggle_page_header($page_title = 'Toggle Settings') {
    $options = syntekpro_toggle_get_options();
    
    // Determine which logo to use
    $use_plugins_logo = (strpos($page_title, 'Other Plugins') !== false);
    $logo_path = $use_plugins_logo 
        ? 'assets/img/SyntekPro%20Plugins%20Logo.png'
        : 'assets/img/syntekpro-toggle-logo%20New.png';
    $logo_alt = $use_plugins_logo ? 'SyntekPro Plugins' : 'Syntekpro Toggle';
    ?>
    <div class="wrap syntekpro-toggle-admin">
        <!-- Header -->
        <div class="syntekpro-header">
            <img src="<?php echo esc_url(SYNTEKPRO_TOGGLE_PLUGIN_URL . $logo_path); ?>" alt="<?php echo esc_attr($logo_alt); ?>" class="syntekpro-header-logo">
            <?php if (!$use_plugins_logo): ?>
                <div class="syntekpro-header-version">Version <?php echo esc_html(SYNTEKPRO_TOGGLE_VERSION); ?></div>
            <?php endif; ?>
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

/**
 * Admin UI Settings Page - DEPRECATED
 * Kept for backward compatibility - functionality moved to Mode Settings
 */
function syntekpro_toggle_admin_ui_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    if (isset($_GET['settings-updated'])) {
        add_settings_error('syntekpro_toggle_messages', 'syntekpro_toggle_message', '✓ Settings saved successfully! Your changes have been applied.', 'updated');
    }
    
    settings_errors('syntekpro_toggle_messages');
    
    syntekpro_toggle_page_header('Admin UI Settings');
    ?>
    <div class="syntekpro-content-wrapper" style="display: flex; gap: 20px; margin-top: 20px;">
        <!-- Left Sidebar Navigation -->
        <div class="syntekpro-sidebar-nav" style="width: 220px; flex-shrink: 0;">
            <div class="syntekpro-nav-section">
                <a href="#" class="syntekpro-nav-item active" data-section="settings">
                    <span class="dashicons dashicons-admin-generic"></span> Settings
                </a>
                <a href="#" class="syntekpro-nav-item" data-section="colors">
                    <span class="dashicons dashicons-art"></span> Colors
                </a>
            </div>
        </div>
        
        <!-- Main Content Area -->
        <div class="syntekpro-main-content" style="flex: 1;">
            <!-- Admin UI Settings Section -->
            <div class="syntekpro-section-panel active" id="section-settings">
                <h2>Admin UI Settings</h2>
                <form action="options.php" method="post">
                    <?php
                    settings_fields('syntekpro_toggle_settings');
                    ?>
                    <div style="background: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 20px;">
                        <?php do_settings_sections('syntekpro-toggle-admin-ui'); ?>
                    </div>
                    <?php submit_button('Save Admin UI Settings'); ?>
                </form>
            </div>
            
            <!-- Admin Colors Section -->
            <div class="syntekpro-section-panel" id="section-colors">
                <h2>Admin UI Color Scheme</h2>
                <form action="options.php" method="post">
                    <?php
                    settings_fields('syntekpro_toggle_settings');
                    ?>
                    <div style="background: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 20px;">
                        <?php do_settings_sections('syntekpro-toggle-admin-color'); ?>
                    </div>
                    <?php submit_button('Save Settings'); ?>
                </form>
            </div>
        </div>
    </div>
    
    <style>
        .syntekpro-sidebar-nav {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            height: fit-content;
            position: sticky;
            top: 32px;
        }
        
        .syntekpro-nav-section {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        
        .syntekpro-nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 15px;
            text-decoration: none;
            color: #333;
            border-radius: 6px;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            cursor: pointer;
            font-size: 15px;
            font-weight: 500;
        }
        
        .syntekpro-nav-item:hover {
            background: #e8e8e8;
            color: #667eea;
        }
        
        .syntekpro-nav-item.active {
            background: #e8e8f5;
            color: #667eea;
            border-left-color: #667eea;
            font-weight: 600;
        }
        
        .syntekpro-nav-item .dashicons {
            font-size: 24px;
            width: 24px;
            height: 24px;
            flex-shrink: 0;
        }
        
        .syntekpro-section-panel {
            display: none;
        }
        
        .syntekpro-section-panel.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    
    <script>
    jQuery(document).ready(function($) {
        $('.syntekpro-nav-item').on('click', function(e) {
            e.preventDefault();
            const section = $(this).data('section');
            
            // Remove active class from all nav items and panels
            $('.syntekpro-nav-item').removeClass('active');
            $('.syntekpro-section-panel').removeClass('active');
            
            // Add active class to clicked item and corresponding panel
            $(this).addClass('active');
            $('#section-' + section).addClass('active');
        });
    });
    </script>
    <?php
    syntekpro_toggle_page_footer();
}

/**
 * Settings Page - DEPRECATED (functionality moved to Frontend Settings)

 * Kept for backward compatibility but no longer used in menu
 */
function syntekpro_toggle_settings_page() {
    // Redirect to Frontend Settings page
    wp_safe_remote_post(admin_url('admin.php?page=syntekpro-toggle'));
    return;
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
                <strong>Mode:</strong> <?php echo esc_html(ucfirst($options['default_mode'])); ?>
            </div>
            <div class="syntekpro-widget-stat">
                <span class="dashicons dashicons-visibility"></span>
                <strong>Status:</strong> <?php echo $options['enable_toggle'] === '1' ? '<span style="color:#46b450;">Active</span>' : '<span style="color:#dc3232;">Inactive</span>'; ?>
            </div>
            <div class="syntekpro-widget-stat">
                <span class="dashicons dashicons-location-alt"></span>
                <strong>Position:</strong> <?php echo esc_html(ucwords(str_replace('-', ' ', $options['button_position']))); ?>
            </div>
        </div>
        
        <?php if ($options['enable_analytics'] === '1'): ?>
        <div class="syntekpro-widget-analytics">
            <h4 style="margin: 15px 0 10px 0; padding-top: 15px; border-top: 1px solid #f0f0f1;">📊 Analytics Overview</h4>
            <div class="syntekpro-widget-analytics-grid">
                <div class="analytics-mini-card">
                    <span class="dashicons dashicons-chart-line" style="color: #667eea;"></span>
                    <div>
                        <strong><?php echo number_format($analytics['total_toggles']); ?></strong>
                        <small>Toggle Clicks</small>
                    </div>
                </div>
                <div class="analytics-mini-card">
                    <span class="dashicons dashicons-visibility" style="color: #f59e0b;"></span>
                    <div>
                        <strong><?php echo number_format($analytics['page_views']); ?></strong>
                        <small>Page Views</small>
                    </div>
                </div>
                <div class="analytics-mini-card">
                    <span class="dashicons dashicons-admin-appearance" style="color: #667eea;"></span>
                    <div>
                        <strong><?php echo number_format($analytics['dark_mode_count']); ?></strong>
                        <small>Dark Mode</small>
                    </div>
                </div>
                <div class="analytics-mini-card">
                    <span class="dashicons dashicons-lightbulb" style="color: #f59e0b;"></span>
                    <div>
                        <strong><?php echo number_format($analytics['light_mode_count']); ?></strong>
                        <small>Light Mode</small>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="syntekpro-widget-actions">
            <a href="<?php echo esc_url(admin_url('admin.php?page=syntekpro-toggle')); ?>" class="button button-primary">
                <span class="dashicons dashicons-admin-settings"></span> Settings
            </a>
            <?php if ($options['enable_analytics'] === '1'): ?>
            <a href="<?php echo esc_url(admin_url('admin.php?page=syntekpro-toggle-analytics')); ?>" class="button button-secondary">
                <span class="dashicons dashicons-chart-bar"></span> Analytics
            </a>
            <?php endif; ?>
            <a href="<?php echo esc_url(admin_url('admin.php?page=syntekpro-toggle-options')); ?>" class="button button-secondary">
                <span class="dashicons dashicons-admin-generic"></span> Options
            </a>
            <a href="<?php echo esc_url(home_url()); ?>" class="button button-secondary" target="_blank">
                <span class="dashicons dashicons-external"></span> View Site
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
    // Check if we're on any of the Syntekpro Toggle admin pages
    $allowed_hooks = array(
        'toplevel_page_syntekpro-toggle',               // Frontend page (now includes Dashboard & Advanced)
        'toggle_page_syntekpro-toggle-admin-ui',        // Admin UI page
        'toggle_page_syntekpro-toggle-about'            // About page
    );
    
    if (!in_array($hook, $allowed_hooks, true)) {
        return;
    }
    
    // WordPress color picker
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');
    
    // Admin CSS
    wp_enqueue_style(
        'syntekpro-toggle-admin',
        SYNTEKPRO_TOGGLE_PLUGIN_URL . 'admin/css/admin.css',
        array(),
        SYNTEKPRO_TOGGLE_VERSION
    );
    
    // Admin About & Additional Styles
    wp_enqueue_style(
        'syntekpro-toggle-admin-about',
        SYNTEKPRO_TOGGLE_PLUGIN_URL . 'admin/css/admin-about.css',
        array(),
        SYNTEKPRO_TOGGLE_VERSION
    );
    
    // Admin JS
    wp_enqueue_script(
        'syntekpro-toggle-admin',
        SYNTEKPRO_TOGGLE_PLUGIN_URL . 'admin/js/admin.js',
        array('jquery', 'wp-color-picker'),
        SYNTEKPRO_TOGGLE_VERSION,
        true
    );
}
add_action('admin_enqueue_scripts', 'syntekpro_toggle_admin_enqueue_scripts');

/**
 * Settings Combined Page - Merges Frontend Settings + Admin UI Settings
 */
function syntekpro_toggle_settings_combined_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    if (isset($_GET['settings-updated'])) {
        add_settings_error('syntekpro_toggle_messages', 'syntekpro_toggle_message', '✓ Settings saved successfully! Your changes have been applied.', 'updated');
    }
    
    settings_errors('syntekpro_toggle_messages');
    
    $options = syntekpro_toggle_get_options();
    
    syntekpro_toggle_page_header('Mode Settings');
    ?>
    <div class="syntekpro-content-wrapper" style="display: flex; gap: 20px; margin-top: 20px;">
        <!-- Left Sidebar Navigation -->
        <div class="syntekpro-sidebar-nav" style="width: 220px; flex-shrink: 0;">
            <div class="syntekpro-nav-section">
                <!-- Frontend Section -->
                <div style="padding: 10px 0; border-bottom: 1px solid #ddd;">
                    <p style="margin: 5px 15px; font-size: 12px; font-weight: 700; color: #667eea; text-transform: uppercase;">Frontend</p>
                    <a href="#" class="syntekpro-nav-item active" data-section="frontend-settings">
                        <span class="dashicons dashicons-admin-appearance"></span> Frontend Settings
                    </a>
                    <a href="#" class="syntekpro-nav-item" data-section="frontend-presets">
                        <span class="dashicons dashicons-smiley"></span> Frontend Presets
                    </a>
                </div>
                
                <!-- Admin Section -->
                <div style="padding: 10px 0; border-bottom: 1px solid #ddd;">
                    <p style="margin: 5px 15px; font-size: 12px; font-weight: 700; color: #667eea; text-transform: uppercase;">Admin</p>
                    <a href="#" class="syntekpro-nav-item" data-section="admin-settings">
                        <span class="dashicons dashicons-admin-generic"></span> Admin Settings
                    </a>
                    <a href="#" class="syntekpro-nav-item" data-section="admin-presets">
                        <span class="dashicons dashicons-art"></span> Admin Presets
                    </a>
                </div>
                
                <!-- Media Section -->
                <div style="padding: 10px 0; border-bottom: 1px solid #ddd;">
                    <p style="margin: 5px 15px; font-size: 12px; font-weight: 700; color: #667eea; text-transform: uppercase;">Media</p>
                    <a href="#" class="syntekpro-nav-item" data-section="images">
                        <span class="dashicons dashicons-format-image"></span> Images Settings
                    </a>
                    <a href="#" class="syntekpro-nav-item" data-section="videos">
                        <span class="dashicons dashicons-format-video"></span> Videos Settings
                    </a>
                    <a href="#" class="syntekpro-nav-item" data-section="slides">
                        <span class="dashicons dashicons-slides"></span> Slides Settings
                    </a>
                </div>
                
                <!-- Additional Section -->
                <div style="padding: 10px 0; border-bottom: 1px solid #ddd;">
                    <p style="margin: 5px 15px; font-size: 12px; font-weight: 700; color: #667eea; text-transform: uppercase;">Additional</p>
                    <a href="#" class="syntekpro-nav-item" data-section="misc">
                        <span class="dashicons dashicons-admin-tools"></span> Misc Settings
                    </a>
                    <a href="#" class="syntekpro-nav-item" data-section="advanced">
                        <span class="dashicons dashicons-admin-tools"></span> Advanced Settings
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Main Content Area -->
        <div class="syntekpro-main-content" style="flex: 1;">
            <!-- Frontend Settings Section -->
            <div class="syntekpro-section-panel active" id="section-frontend-settings">
                <h2>Frontend Settings</h2>
                <form action="options.php" method="post">
                    <?php
                    settings_fields('syntekpro_toggle_settings');
                    ?>
                    <div style="background: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 20px;">
                        <?php do_settings_sections('syntekpro-toggle-frontend-general'); ?>
                    </div>
                    <?php submit_button('Save Settings'); ?>
                </form>
            </div>
            
            <!-- Frontend Presets Section -->
            <div class="syntekpro-section-panel" id="section-frontend-presets">
                <h2>Frontend Presets</h2>
                <form action="options.php" method="post">
                    <?php
                    settings_fields('syntekpro_toggle_settings');
                    ?>
                    <div style="background: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 20px;">
                        <?php do_settings_sections('syntekpro-toggle-frontend-colors'); ?>
                        <?php do_settings_sections('syntekpro-toggle-frontend-adjustments'); ?>
                    </div>
                    <?php submit_button('Save Presets'); ?>
                </form>
            </div>
            
            <!-- Admin Settings Section -->
            <div class="syntekpro-section-panel" id="section-admin-settings">
                <h2>Admin Settings</h2>
                <form action="options.php" method="post">
                    <?php
                    settings_fields('syntekpro_toggle_settings');
                    ?>
                    <div style="background: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 20px;">
                        <?php do_settings_sections('syntekpro-toggle-admin-ui'); ?>
                    </div>
                    <?php submit_button('Save Settings'); ?>
                </form>
            </div>
            
            <!-- Admin Presets Section -->
            <div class="syntekpro-section-panel" id="section-admin-presets">
                <h2>Admin Presets</h2>
                <form action="options.php" method="post">
                    <?php
                    settings_fields('syntekpro_toggle_settings');
                    ?>
                    <div style="background: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 20px;">
                        <?php do_settings_sections('syntekpro-toggle-admin-color'); ?>
                    </div>
                    <?php submit_button('Save Presets'); ?>
                </form>
            </div>
            
            <!-- Images Settings Section -->
            <div class="syntekpro-section-panel" id="section-images">
                <h2>🖼️ Images Settings</h2>
                <form action="options.php" method="post">
                    <?php
                    settings_fields('syntekpro_toggle_settings');
                    ?>
                    <div style="background: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 20px;">
                        <?php do_settings_sections('syntekpro-toggle-frontend-images'); ?>
                    </div>
                    <?php submit_button('Save Settings'); ?>
                </form>
            </div>
            
            <!-- Videos Settings Section -->
            <div class="syntekpro-section-panel" id="section-videos">
                <h2>🎬 Videos Settings</h2>
                <form action="options.php" method="post">
                    <?php
                    settings_fields('syntekpro_toggle_settings');
                    ?>
                    <div style="background: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 20px;">
                        <?php do_settings_sections('syntekpro-toggle-frontend-videos'); ?>
                    </div>
                    <?php submit_button('Save Settings'); ?>
                </form>
            </div>
            
            <!-- Slides Settings Section -->
            <div class="syntekpro-section-panel" id="section-slides">
                <h2>📊 Slides Settings</h2>
                <form action="options.php" method="post">
                    <?php
                    settings_fields('syntekpro_toggle_settings');
                    ?>
                    <div style="background: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 20px;">
                        <?php do_settings_sections('syntekpro-toggle-frontend-slides'); ?>
                    </div>
                    <?php submit_button('Save Settings'); ?>
                </form>
            </div>
            
            <!-- Misc Settings Section -->
            <div class="syntekpro-section-panel" id="section-misc">
                <h2>Misc Settings</h2>
                <form action="options.php" method="post">
                    <?php
                    settings_fields('syntekpro_toggle_settings');
                    ?>
                    <div style="background: #fff; border: 1px solid #ccc; border-radius: 8px; padding: 20px;">
                        <h3 style="margin-top: 0; padding-bottom: 10px; border-bottom: 2px solid #667eea;">📊 Dashboard Widget</h3>
                        <table class="form-table">
                            <tr>
                                <th scope="row">
                                    <label for="enable_dashboard_widget">Enable Dashboard Widget</label>
                                </th>
                                <td>
                                    <input type="checkbox" id="enable_dashboard_widget" name="syntekpro_toggle[enable_dashboard_widget]" value="1" <?php checked($options['enable_dashboard_widget'], '1'); ?> />
                                    <label for="enable_dashboard_widget" style="margin-left: 5px;">Display plugin status on WordPress admin dashboard</label>
                                </td>
                            </tr>
                        </table>
                        
                        <h3 style="padding-top: 20px; padding-bottom: 10px; border-top: 2px solid #f0f0f1; border-bottom: 2px solid #667eea;">⚙️ Admin Bar</h3>
                        <table class="form-table">
                            <tr>
                                <th scope="row">
                                    <label for="enable_admin_bar_icon">Show Admin Bar Icon</label>
                                </th>
                                <td>
                                    <input type="checkbox" id="enable_admin_bar_icon" name="syntekpro_toggle[enable_admin_bar_icon]" value="1" <?php checked($options['enable_admin_bar_icon'], '1'); ?> />
                                    <label for="enable_admin_bar_icon" style="margin-left: 5px;">Display plugin icon in WordPress admin bar</label>
                                </td>
                            </tr>
                        </table>
                        
                        <h3 style="padding-top: 20px; padding-bottom: 10px; border-top: 2px solid #f0f0f1; border-bottom: 2px solid #667eea;">📈 Analytics</h3>
                        <table class="form-table">
                            <tr>
                                <th scope="row">
                                    <label for="enable_analytics">Enable Analytics Tracking</label>
                                </th>
                                <td>
                                    <input type="checkbox" id="enable_analytics" name="syntekpro_toggle[enable_analytics]" value="1" <?php checked($options['enable_analytics'], '1'); ?> />
                                    <label for="enable_analytics" style="margin-left: 5px;">Track user toggle behavior and mode preferences</label>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <?php submit_button('Save Settings'); ?>
                </form>
            </div>
            
            <!-- Advanced Settings Section -->
            <div class="syntekpro-section-panel" id="section-advanced">
                <h2>Advanced Settings</h2>
                <form action="options.php" method="post">
                    <?php
                    settings_fields('syntekpro_toggle_settings');
                    do_settings_sections('syntekpro-toggle-frontend-advanced');
                    submit_button('Save All Settings');
                    ?>
                </form>
            </div>
        </div>
    </div>
    
    <style>
        /* Settings Updated Message Styling */
        .wrap .notice {
            margin: 20px 0;
            padding: 12px 20px;
            border-left: 4px solid #00a32a;
            background: #f0f6fc;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .wrap .notice.notice-success {
            border-left-color: #00a32a;
            background: #edfaef;
        }
        
        .wrap .notice.notice-error {
            border-left-color: #d63638;
            background: #fcf0f1;
        }
        
        .wrap .notice p {
            margin: 0.5em 0;
            font-size: 14px;
            font-weight: 500;
        }
        
        /* Submit Button Styling */
        .syntekpro-section-panel .submit {
            margin-top: 20px;
            padding: 0;
        }
        
        .syntekpro-section-panel .button-primary {
            background: #667eea;
            border-color: #667eea;
            color: #fff;
            padding: 10px 20px;
            height: auto;
            font-size: 14px;
            font-weight: 500;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        
        .syntekpro-section-panel .button-primary:hover {
            background: #5568d3;
            border-color: #5568d3;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
        }
        
        .syntekpro-section-panel .button-primary:active {
            transform: translateY(0);
        }
        
        .syntekpro-sidebar-nav {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            height: fit-content;
            position: sticky;
            top: 32px;
        }
        
        .syntekpro-nav-section {
            display: flex;
            flex-direction: column;
            gap: 0;
        }
        
        .syntekpro-nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 15px;
            text-decoration: none;
            color: #333;
            border-radius: 6px;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            cursor: pointer;
            font-size: 15px;
            font-weight: 500;
        }
        
        .syntekpro-nav-item:hover {
            background: #e8e8e8;
            color: #667eea;
        }
        
        .syntekpro-nav-item.active {
            background: #e8e8f5;
            color: #667eea;
            border-left-color: #667eea;
            font-weight: 600;
        }
        
        .syntekpro-nav-item .dashicons {
            font-size: 24px;
            width: 24px;
            height: 24px;
            flex-shrink: 0;
        }
        
        .syntekpro-section-panel {
            display: none;
        }
        
        .syntekpro-section-panel.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    
    <script>
    jQuery(document).ready(function($) {
        $('.syntekpro-nav-item').on('click', function(e) {
            e.preventDefault();
            const section = $(this).data('section');
            
            // Remove active class from all nav items and panels
            $('.syntekpro-nav-item').removeClass('active');
            $('.syntekpro-section-panel').removeClass('active');
            
            // Add active class to clicked item and corresponding panel
            $(this).addClass('active');
            $('#section-' + section).addClass('active');
        });
    });
    </script>
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
    
    syntekpro_toggle_page_header('About Syntekpro Toggle');
    ?>
    <div class="syntekpro-content-wrapper" style="display: flex; gap: 30px; margin-top: 20px;">
        <!-- Main Content -->
        <div class="syntekpro-about-main" style="flex: 1;">
            <!-- Welcome Section -->
            <div class="syntekpro-admin-box" style="background: #ffffff; padding: 40px; border-radius: 12px; margin-bottom: 30px; text-align: center; border: 1px solid #e0e0e0;">
                <h2 style="color: #1a1a1a; margin-top: 0; font-size: 2em;">🌓 Syntekpro Toggle</h2>
                <p style="font-size: 1.1em; margin-bottom: 0; color: #555;">The Ultimate Dark Mode Solution for WordPress</p>
            </div>
            
            <!-- About Description -->
            <div class="syntekpro-admin-box">
                <h3 style="font-size: 1.3em; color: #1a1a1a; margin-top: 0; font-weight: 700;">Welcome to Syntekpro Toggle</h3>
                <p style="font-size: 1em; line-height: 1.8; color: #555;">
                    <strong>Syntekpro Toggle</strong> is a powerful, lightweight dark mode plugin for WordPress that puts the user experience first. 
                    Whether your visitors prefer dark mode for comfort or accessibility, our plugin seamlessly handles the transition with 
                    smooth animations, customizable colors, and intelligent theme detection.
                </p>
            </div>
            
            <!-- Key Features -->
            <div class="syntekpro-admin-box">
                <h3 style="font-size: 1.2em; color: #1a1a1a; margin-top: 0; padding-bottom: 10px; border-bottom: 2px solid #667eea; font-weight: 700;">✨ Key Features</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
                    <div style="padding: 15px; background: #f8f9ff; border-left: 4px solid #667eea; border-radius: 4px;">
                        <h4 style="margin: 0 0 8px 0; color: #667eea;">🎨 Full Customization</h4>
                        <p style="margin: 0; font-size: 0.95em; color: #555;">Complete control over dark mode colors, themes, and animations</p>
                    </div>
                    <div style="padding: 15px; background: #f8f9ff; border-left: 4px solid #667eea; border-radius: 4px;">
                        <h4 style="margin: 0 0 8px 0; color: #667eea;">🔄 Smart Auto Mode</h4>
                        <p style="margin: 0; font-size: 0.95em; color: #555;">Automatically respects users' OS dark mode preferences</p>
                    </div>
                    <div style="padding: 15px; background: #f8f9ff; border-left: 4px solid #667eea; border-radius: 4px;">
                        <h4 style="margin: 0 0 8px 0; color: #667eea;">🎯 Block Theme Compatible</h4>
                        <p style="margin: 0; font-size: 0.95em; color: #555;">Works seamlessly with all modern WordPress block themes</p>
                    </div>
                    <div style="padding: 15px; background: #f8f9ff; border-left: 4px solid #667eea; border-radius: 4px;">
                        <h4 style="margin: 0 0 8px 0; color: #667eea;">⚡ Admin Dark Mode</h4>
                        <p style="margin: 0; font-size: 0.95em; color: #555;">Beautiful dark mode for the WordPress admin dashboard</p>
                    </div>
                    <div style="padding: 15px; background: #f8f9ff; border-left: 4px solid #667eea; border-radius: 4px;">
                        <h4 style="margin: 0 0 8px 0; color: #667eea;">📊 Analytics Tracking</h4>
                        <p style="margin: 0; font-size: 0.95em; color: #555;">Track user preferences and toggle behavior with built-in analytics</p>
                    </div>
                    <div style="padding: 15px; background: #f8f9ff; border-left: 4px solid #667eea; border-radius: 4px;">
                        <h4 style="margin: 0 0 8px 0; color: #667eea;">⚙️ Advanced Options</h4>
                        <p style="margin: 0; font-size: 0.95em; color: #555;">Media filters, custom CSS, transition controls, and more</p>
                    </div>
                </div>
            </div>
            
            <!-- Version Info -->
            <div class="syntekpro-admin-box" style="background: #f0f6ff; border: 1px solid #cce5ff;">
                <h3 style="font-size: 1.1em; color: #333; margin-top: 0;">📦 Plugin Information</h3>
                <p style="margin: 5px 0;"><strong>Current Version:</strong> <?php echo esc_html(SYNTEKPRO_TOGGLE_VERSION); ?></p>
                <p style="margin: 5px 0;"><strong>Requires:</strong> WordPress 5.0+ | PHP 7.2+</p>
                <p style="margin: 5px 0;"><strong>License:</strong> GPL v2 or later</p>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="syntekpro-about-sidebar" style="width: 300px; flex-shrink: 0;">
            <!-- Quick Actions -->
            <div class="syntekpro-admin-box" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; margin-bottom: 20px;">
                <h3 style="color: white; margin-top: 0; margin-bottom: 15px;">⚙️ Quick Links</h3>
                <a href="?page=syntekpro-toggle" class="button button-light" style="width: 100%; margin-bottom: 8px; display: block; text-align: center; background: rgba(255,255,255,0.2); border-color: rgba(255,255,255,0.3); color: white; text-decoration: none;">
                    <- Frontend Settings
                </a>
                <a href="?page=syntekpro-toggle-admin-ui" class="button button-light" style="width: 100%; display: block; text-align: center; background: rgba(255,255,255,0.2); border-color: rgba(255,255,255,0.3); color: white; text-decoration: none;">
                    <- Admin UI Settings
                </a>
            </div>
            
            <!-- Support Box -->
            <div class="syntekpro-admin-box" style="border: 2px solid #667eea;">
                <h3 style="color: #667eea; margin-top: 0; text-align: center;">💬 Need Help?</h3>
                <p style="text-align: center; font-size: 0.95em; margin-bottom: 15px;">We're here to support you!</p>
                <a href="https://docs.syntekpro.com/toggle" target="_blank" rel="noopener noreferrer" class="button button-secondary" style="width: 100%; text-align: center; margin-bottom: 8px; display: block;">
                    📚 View Docs
                </a>
                <a href="mailto:support@syntekpro.com" class="button button-secondary" style="width: 100%; text-align: center; margin-bottom: 8px; display: block;">
                    📧 Email Support
                </a>
                <a href="https://github.com/syntekpro/toggle/issues" target="_blank" rel="noopener noreferrer" class="button button-secondary" style="width: 100%; text-align: center; display: block;">
                    🐛 Report Issue
                </a>
            </div>
            
            <!-- Rate Box -->
            <div class="syntekpro-admin-box" style="background: #fff9e6; border: 2px solid #ffc107;">
                <h3 style="color: #ff9800; margin-top: 0; text-align: center;">⭐ Love This Plugin?</h3>
                <p style="text-align: center; font-size: 0.95em; margin-bottom: 15px;">Help us grow with a 5-star review!</p>
                <a href="https://wordpress.org/support/plugin/syntekpro-toggle/reviews/#new-post" target="_blank" rel="noopener noreferrer" class="button button-primary" style="width: 100%; text-align: center; display: block; background: #ff9800; border-color: #ff9800;">
                    ⭐ Write a Review
                </a>
            </div>
            
            <!-- Social Links -->
            <div class="syntekpro-admin-box" style="text-align: center;">
                <h3 style="color: #333; margin-top: 0; margin-bottom: 15px;">🌐 Follow Us</h3>
                <a href="https://twitter.com/syntekpro" target="_blank" rel="noopener noreferrer" style="display: inline-block; margin: 0 8px; color: #667eea; text-decoration: none; font-size: 1.5em;" title="Twitter">𝕏</a>
                <a href="https://facebook.com/syntekpro" target="_blank" rel="noopener noreferrer" style="display: inline-block; margin: 0 8px; color: #667eea; text-decoration: none; font-size: 1.5em;" title="Facebook">f</a>
                <a href="https://github.com/syntekpro" target="_blank" rel="noopener noreferrer" style="display: inline-block; margin: 0 8px; color: #667eea; text-decoration: none; font-size: 1.5em;" title="GitHub">◎</a>
            </div>
        </div>
    </div>
    <?php
    syntekpro_toggle_page_footer();
}

/**
 * Toggle+ Premium Features Page
 */
function syntekpro_toggle_plus_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    syntekpro_toggle_page_header('Toggle+ - Premium Features');
    ?>
    <div class="syntekpro-content-wrapper">
        <div class="syntekpro-main-content">
            
            <!-- Hero Section -->
            <div class="syntekpro-premium-hero" style="background: #ffffff; border: 2px solid #e0e0e0; color: #333; padding: 60px 40px; border-radius: 12px; margin-bottom: 40px; text-align: center;">
                <h2 style="color: #333; margin: 0 0 15px 0; font-size: 2.5em; font-weight: 700;">Toggle+</h2>
                <p style="font-size: 1.3em; margin: 0 0 25px 0; color: #555;">Unlock Premium Features & Take Your Dark Mode to the Next Level</p>
                <a href="https://plugins.syntekpro.com/toggle-plus" target="_blank" rel="noopener noreferrer" class="button button-light" style="background: #667eea; color: white; border-color: #667eea; font-size: 16px; padding: 12px 30px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block;">
                    🚀 Upgrade to Toggle+
                </a>
            </div>
            
            <!-- Feature Comparison -->
            <h2 style="text-align: center; margin-top: 50px; margin-bottom: 30px;">🎯 Features Comparison</h2>
            <div class="syntekpro-comparison-table" style="overflow-x: auto; margin-bottom: 40px;">
                <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <thead>
                        <tr style="background: #f8f9ff; border-bottom: 2px solid #e0e0e0;">
                            <th style="padding: 20px; text-align: left; font-weight: 600; color: #333;">Feature</th>
                            <th style="padding: 20px; text-align: center; font-weight: 600; color: #333;">Free Edition</th>
                            <th style="padding: 20px; text-align: center; font-weight: 600; color: #667eea;">Toggle+</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="border-bottom: 1px solid #e0e0e0;">
                            <td style="padding: 18px 20px; color: #555;">Basic Dark Mode Toggle</td>
                            <td style="padding: 18px 20px; text-align: center;">✅</td>
                            <td style="padding: 18px 20px; text-align: center;✅</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #e0e0e0; background: #fafafa;">
                            <td style="padding: 18px 20px; color: #555;">36 Button Themes</td>
                            <td style="padding: 18px 20px; text-align: center;">✅ (5 Free)</td>
                            <td style="padding: 18px 20px; text-align: center; color: #667eea; font-weight: 600;">✅ All 36</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #e0e0e0;">
                            <td style="padding: 18px 20px; color: #555;">20 Color Presets</td>
                            <td style="padding: 18px 20px; text-align: center;">✅ (1 Free)</td>
                            <td style="padding: 18px 20px; text-align: center; color: #667eea; font-weight: 600;">✅ All 20</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #e0e0e0; background: #fafafa;">
                            <td style="padding: 18px 20px; color: #555;">Shape & Animation Options</td>
                            <td style="padding: 18px 20px; text-align: center;">✅</td>
                            <td style="padding: 18px 20px; text-align: center;✅</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #e0e0e0;">
                            <td style="padding: 18px 20px; color: #555;">Admin Dark Mode</td>
                            <td style="padding: 18px 20px; text-align: center;">✅</td>
                            <td style="padding: 18px 20px; text-align: center;✅</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #e0e0e0; background: #fafafa;">
                            <td style="padding: 18px 20px; color: #555;">Analytics Dashboard</td>
                            <td style="padding: 18px 20px; text-align: center;">✅ (Basic)</td>
                            <td style="padding: 18px 20px; text-align: center; color: #667eea; font-weight: 600;">✅ Advanced</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #e0e0e0;">
                            <td style="padding: 18px 20px; color: #555;">Media Filters (Images, Videos)</td>
                            <td style="padding: 18px 20px; text-align: center;">✅</td>
                            <td style="padding: 18px 20px; text-align: center;✅</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #e0e0e0; background: #fafafa;">
                            <td style="padding: 18px 20px; color: #555;">Premium Support</td>
                            <td style="padding: 18px 20px; text-align: center;"></td>
                            <td style="padding: 18px 20px; text-align: center; color: #667eea; font-weight: 600;">✅ Priority</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #e0e0e0;">
                            <td style="padding: 18px 20px; color: #555;">Beta Features Access</td>
                            <td style="padding: 18px 20px; text-align: center;"></td>
                            <td style="padding: 18px 20px; text-align: center; color: #667eea; font-weight: 600;">✅</td>
                        </tr>
                        <tr style="background: #fafafa;">
                            <td style="padding: 18px 20px; color: #555;">Advanced Custom CSS</td>
                            <td style="padding: 18px 20px; text-align: center;"></td>
                            <td style="padding: 18px 20px; text-align: center; color: #667eea; font-weight: 600;">✅</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Why Upgrade -->
            <div style="background: #f0f6ff; border-left: 4px solid #667eea; padding: 30px; border-radius: 8px; margin-bottom: 40px;">
                <h3 style="color: #667eea; margin-top: 0;">💡 Why Upgrade to Toggle+?</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <h4 style="color: #333; margin-bottom: 8px;">🎨 Unlimited Customization</h4>
                        <p style="margin: 0; color: #555; font-size: 0.95em;">Access all 36 premium button themes and 20 carefully curated color schemes</p>
                    </div>
                    <div>
                        <h4 style="color: #333; margin-bottom: 8px;">📊 Advanced Analytics</h4>
                        <p style="margin: 0; color: #555; font-size: 0.95em;">Get detailed insights into user behavior and dark mode preferences</p>
                    </div>
                    <div>
                        <h4 style="color: #333; margin-bottom: 8px;">🚀 Early Access</h4>
                        <p style="margin: 0; color: #555; font-size: 0.95em;">Be the first to access new features and beta testing opportunities</p>
                    </div>
                    <div>
                        <h4 style="color: #333; margin-bottom: 8px;">💬 Priority Support</h4>
                        <p style="margin: 0; color: #555; font-size: 0.95em;">Get faster response times and dedicated support from our team</p>
                    </div>
                </div>
            </div>
            
            <!-- Upgrade CTA -->
            <div style="text-align: center; padding: 40px; background: #ffffff; border: 2px solid #667eea; border-radius: 12px; color: #333;">
                <h2 style="color: #333; margin-top: 0;">Ready to Unlock Premium Features?</h2>
                <p style="font-size: 1.1em; margin-bottom: 25px; color: #555;">Join thousands of WordPress users enjoying the full power of Syntekpro Toggle</p>
                <a href="https://plugins.syntekpro.com/toggle-plus" target="_blank" rel="noopener noreferrer" class="button button-light" style="background: #667eea; color: white; border-color: #667eea; font-size: 16px; padding: 14px 40px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block;">
                    🚀 Upgrade Now - Get Toggle+
                </a>
                <p style="margin: 20px 0 0 0; color: #666; font-size: 0.95em;">30-day money-back guarantee • No questions asked</p>
            </div>
        </div>
    </div>
    <?php
    syntekpro_toggle_page_footer();
}

/**
 * Other Plugins Showcase Page
 */
function syntekpro_toggle_plugins_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    syntekpro_toggle_page_header('Other Plugins - By SyntekPro');
    
    // Plugin data
    $plugins = array(
        array(
            'name' => 'SyntekPro Forms',
            'icon' => '📝',
            'tagline' => 'Advanced WordPress Form Builder',
            'description' => 'Create powerful forms with conditional logic, email notifications, payment integration, and advanced routing. Perfect for lead capture, surveys, contact forms, and more.',
            'features' => array('30+ Field Types', 'Conditional Logic', 'Payment Processing', 'Email Routing', 'Multi-page Forms'),
            'url' => 'https://plugins.syntekpro.com/syntekpro-forms',
            'color' => '#FF6B6B'
        ),
        array(
            'name' => 'SyntekPro Animations',
            'icon' => '✨',
            'tagline' => 'Pure CSS & JavaScript Animations',
            'description' => 'Add stunning animations and transitions to any WordPress element without jQuery. Smooth scroll effects, fade-ins, slide animations, and more with incredible performance.',
            'features' => array('50+ Animations', 'Scroll Triggers', 'Entry Effects', 'Loop Animation', '60 FPS Performance'),
            'url' => 'https://plugins.syntekpro.com/syntekpro-animations',
            'color' => '#4ECDC4'
        ),
        array(
            'name' => 'SyntekPro License Server',
            'icon' => '🔐',
            'tagline' => 'License Management & Product Activation',
            'description' => 'Manage software licenses, product keys, and user activations. Perfect for selling plugins, themes, software, or digital products with built-in security and analytics.',
            'features' => array('License Generation', 'Activation Tracking', 'API Integration', 'License Revocation', 'Sales Analytics'),
            'url' => 'https://plugins.syntekpro.com/syntekpro-license-server',
            'color' => '#9B59B6'
        )
    );
    ?>
    <div class="syntekpro-content-wrapper">
        <div class="syntekpro-main-content">
            
            <!-- Hero Section -->
            <div style="background: #ffffff; border: 2px solid #e0e0e0; color: #333; padding: 50px 40px; border-radius: 12px; margin-bottom: 40px; text-align: center;">
                <h2 style="color: #333; margin: 0 0 10px 0; font-size: 2.2em; font-weight: 700;">🚀 SyntekPro Plugins Suite</h2>
                <p style="font-size: 1.1em; margin: 0; color: #555;">Powerful WordPress plugins built for professionals</p>
            </div>
            
            <!-- Plugin Cards -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; margin-bottom: 40px;">
                <?php foreach ($plugins as $plugin): ?>
                <div class="syntekpro-plugin-card" style="background: white; border: 2px solid #e0e0e0; border-radius: 12px; padding: 30px; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                    <div style="font-size: 3em; margin-bottom: 15px; text-align: center; line-height: 1;">
                        <?php echo $plugin['icon']; ?>
                    </div>
                    <h3 style="color: #333; margin: 0 0 5px 0; font-size: 1.3em; text-align: center;">
                        <?php echo esc_html($plugin['name']); ?>
                    </h3>
                    <p style="color: #667eea; margin: 0 0 15px 0; font-size: 0.95em; text-align: center; font-weight: 600;">
                        <?php echo esc_html($plugin['tagline']); ?>
                    </p>
                    <div style="height: 2px; background: <?php echo esc_attr($plugin['color']); ?>; margin: 15px 0; border-radius: 1px;"></div>
                    <p style="color: #555; margin: 15px 0; line-height: 1.6; font-size: 0.95em;">
                        <?php echo esc_html($plugin['description']); ?>
                    </p>
                    
                    <!-- Features Badges -->
                    <div style="margin: 20px 0;">
                        <?php foreach ($plugin['features'] as $feature): ?>
                        <span style="display: inline-block; background: #f0f6ff; color: #333; padding: 6px 12px; border-radius: 20px; font-size: 0.85em; margin-right: 8px; margin-bottom: 8px; border-left: 3px solid <?php echo esc_attr($plugin['color']); ?>;">
                            ✓ <?php echo esc_html($feature); ?>
                        </span>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- CTA Button -->
                    <a href="<?php echo esc_url($plugin['url']); ?>" target="_blank" rel="noopener noreferrer" class="button button-primary" style="width: 100%; text-align: center; display: block; background: <?php echo esc_attr($plugin['color']); ?>; border-color: <?php echo esc_attr($plugin['color']); ?>; color: white; font-weight: 600; padding: 12px; border-radius: 6px; text-decoration: none; margin-top: 20px; cursor: pointer; transition: all 0.3s;">
                        Learn More →
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Why SyntekPro -->
            <div style="background: #f8f9ff; border-left: 4px solid #667eea; padding: 40px; border-radius: 12px; margin-bottom: 40px;">
                <h2 style="color: #333; margin-top: 0; text-align: center;">💼 Why Choose SyntekPro?</h2>
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 25px; margin-top: 25px;">
                    <div style="text-align: center;">
                        <h3 style="color: #667eea; margin: 0 0 10px 0;">🏆 Professional</h3>
                        <p style="margin: 0; color: #555; font-size: 0.95em;">Built by experienced WordPress developers for enterprise-grade performance</p>
                    </div>
                    <div style="text-align: center;">
                        <h3 style="color: #667eea; margin: 0 0 10px 0;">🔒 Secure</h3>
                        <p style="margin: 0; color: #555; font-size: 0.95em;">Security-first approach with regular updates and professional support</p>
                    </div>
                    <div style="text-align: center;">
                        <h3 style="color: #667eea; margin: 0 0 10px 0;">⚡ Fast</h3>
                        <p style="margin: 0; color: #555; font-size: 0.95em;">Optimized for performance with minimal overhead and smart caching</p>
                    </div>
                    <div style="text-align: center; padding-top: 15px; border-top: 1px solid #e0e0e0; margin-top: 15px;">
                        <h3 style="color: #667eea; margin: 0 0 10px 0;">📞 Support</h3>
                        <p style="margin: 0; color: #555; font-size: 0.95em;">Dedicated support team ready to help you succeed</p>
                    </div>
                    <div style="text-align: center; padding-top: 15px; border-top: 1px solid #e0e0e0; margin-top: 15px;">
                        <h3 style="color: #667eea; margin: 0 0 10px 0;">🎓 Documentation</h3>
                        <p style="margin: 0; color: #555; font-size: 0.95em;">Comprehensive tutorials and guides for easy implementation</p>
                    </div>
                    <div style="text-align: center; padding-top: 15px; border-top: 1px solid #e0e0e0; margin-top: 15px;">
                        <h3 style="color: #667eea; margin: 0 0 10px 0;">🔄 Updates</h3>
                        <p style="margin: 0; color: #555; font-size: 0.95em;">Regular updates with new features and improvement</p>
                    </div>
                </div>
            </div>
            
            <!-- Connect CTA -->
            <div style="text-align: center; padding: 40px; background: #ffffff; border: 2px solid #667eea; border-radius: 12px; color: #333;">
                <h2 style="color: #333; margin-top: 0;">Want to Explore More?</h2>
                <p style="font-size: 1em; margin-bottom: 25px; color: #555;">Visit all our plugins and extensions on SyntekPro.com</p>
                <a href="https://plugins.syntekpro.com" target="_blank" rel="noopener noreferrer" class="button button-light" style="background: #667eea; color: white; border-color: #667eea; font-size: 15px; padding: 12px 35px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block;">
                    🌐 Visit Our Store
                </a>
            </div>
        </div>
    </div>
    <?php
    syntekpro_toggle_page_footer();
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
    if (isset($_GET['settings-updated'])) {
        add_settings_error('syntekpro_toggle_messages', 'syntekpro_toggle_message', 'Analytics Settings Saved', 'updated');
    }
    
    // Handle reset analytics
    if (isset($_POST['reset_analytics']) && check_admin_referer('syntekpro_analytics_reset', 'analytics_nonce')) {
        syntekpro_toggle_reset_analytics();
        add_settings_error('syntekpro_toggle_messages', 'syntekpro_toggle_message', 'Analytics Data Reset Successfully', 'updated');
        $analytics = syntekpro_toggle_get_analytics(); // Refresh data
    }
    
    settings_errors('syntekpro_toggle_messages');
    
    syntekpro_toggle_page_header('Analytics Dashboard');
    ?>
    <div class="syntekpro-content-wrapper">
        <div class="syntekpro-main-content">
            
            <!-- Analytics Settings Form -->
            <div class="syntekpro-admin-box" style="margin-bottom: 20px;">
                <h2>📊 Analytics Settings</h2>
                <form action="options.php" method="post">
                    <?php
                    settings_fields('syntekpro_toggle_settings');
                    do_settings_sections('syntekpro-toggle-analytics-settings');
                    submit_button('Save Analytics Settings');
                    ?>
                </form>
            </div>
            
            <!-- Analytics Overview Cards -->
            <h2>📈 Usage Statistics</h2>
            <div class="syntekpro-analytics-grid">
                <div class="syntekpro-analytics-card">
                    <div class="analytics-icon">
                        <span class="dashicons dashicons-chart-line"></span>
                    </div>
                    <div class="analytics-data">
                        <h3><?php echo number_format($analytics['total_toggles']); ?></h3>
                        <p>Total Toggle Clicks</p>
                    </div>
                </div>
                
                <div class="syntekpro-analytics-card">
                    <div class="analytics-icon">
                        <span class="dashicons dashicons-visibility"></span>
                    </div>
                    <div class="analytics-data">
                        <h3><?php echo number_format($analytics['page_views']); ?></h3>
                        <p>Total Page Views</p>
                    </div>
                </div>
                
                <div class="syntekpro-analytics-card">
                    <div class="analytics-icon">
                        <span class="dashicons dashicons-clock"></span>
                    </div>
                    <div class="analytics-data">
                        <h3><?php echo esc_html($analytics['most_active_time']); ?></h3>
                        <p>Most Active Time</p>
                    </div>
                </div>
                
                <div class="syntekpro-analytics-card">
                    <div class="analytics-icon">
                        <span class="dashicons dashicons-calendar-alt"></span>
                    </div>
                    <div class="analytics-data">
                        <h3><?php echo esc_html($analytics['tracking_since']); ?></h3>
                        <p>Tracking Since</p>
                    </div>
                </div>
            </div>
            
            <!-- Mode Preference Statistics -->
            <div class="syntekpro-admin-box" style="margin-top: 20px;">
                <h2>🌓 Mode Preferences</h2>
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
            
            <!-- Toggle Theme Statistics -->
            <div class="syntekpro-admin-box" style="margin-top: 20px;">
                <h2>🎨 Popular Themes</h2>
                <div class="syntekpro-theme-stats">
                    <?php if (!empty($analytics['theme_usage']) && is_array($analytics['theme_usage'])): ?>
                        <?php foreach (array_slice($analytics['theme_usage'], 0, 5) as $theme => $count): ?>
                            <div class="theme-stat-row">
                                <span class="theme-name"><?php echo esc_html(ucwords(str_replace('-', ' ', $theme))); ?></span>
                                <span class="theme-count"><?php echo number_format($count); ?> views</span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No theme usage data available yet.</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Activity Timeline -->
            <div class="syntekpro-admin-box" style="margin-top: 20px;">
                <h2>⏱️ Recent Activity</h2>
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
                        <p>No recent activity recorded.</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Reset Analytics -->
            <div class="syntekpro-admin-box" style="margin-top: 20px; border-left: 4px solid #dc3232;">
                <h2>🗑️ Reset Analytics</h2>
                <p>Clear all analytics data and start fresh. This action cannot be undone.</p>
                <form method="post">
                    <?php wp_nonce_field('syntekpro_analytics_reset', 'analytics_nonce'); ?>
                    <button type="submit" name="reset_analytics" class="button button-secondary" onclick="return confirm('Are you sure you want to reset all analytics data? This cannot be undone.');">
                        <span class="dashicons dashicons-trash"></span> Reset All Analytics Data
                    </button>
                </form>
            </div>
        </div>
        
        <div class="syntekpro-sidebar">
            <div class="syntekpro-admin-box">
                <h3>📊 Analytics Info</h3>
                <p>Track how users interact with your dark mode toggle.</p>
                <ul class="syntekpro-stats-list">
                    <li><strong>Status:</strong> <?php echo $options['enable_analytics'] === '1' ? '<span style="color:#46b450;">Active</span>' : '<span style="color:#dc3232;">Inactive</span>'; ?></li>
                    <li><strong>Total Events:</strong> <?php echo number_format($analytics['total_events']); ?></li>
                    <li><strong>Tracking Started:</strong> <?php echo esc_html($analytics['tracking_since']); ?></li>
                </ul>
            </div>
            
            <div class="syntekpro-admin-box">
                <h3>📈 What We Track</h3>
                <ul class="syntekpro-stats-list">
                    <li>Toggle button clicks</li>
                    <li>Mode switches (Dark/Light)</li>
                    <li>Theme usage</li>
                    <li>Page load events</li>
                    <li>User preference changes</li>
                </ul>
            </div>
            
            <div class="syntekpro-admin-box">
                <h3>🔒 Privacy Notice</h3>
                <p>All analytics data is stored locally on your server. No data is sent to external services.</p>
                <p>We track usage patterns to help you understand how visitors use dark mode on your site.</p>
            </div>
        </div>
    </div>
    
    <style>
        .syntekpro-analytics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .syntekpro-analytics-card {
            background: #ffffff;
            border: 1px solid #e0e0e0;
            color: #333;
            padding: 20px;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            text-align: center;
        }
        
        .syntekpro-analytics-card:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-color: #667eea;
        }
        
        .analytics-icon {
            font-size: 40px;
            line-height: 1;
            color: #667eea;
        }
        
        .analytics-icon .dashicons {
            width: 40px;
            height: 40px;
            font-size: 40px;
        }
        
        .analytics-data h3 {
            margin: 5px 0 0 0;
            font-size: 28px;
            font-weight: 700;
            color: #333;
        }
        
        .analytics-data p {
            margin: 0;
            font-size: 13px;
            color: #666;
        }
        
        .syntekpro-mode-stats {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .mode-stat-item {
            padding: 15px;
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
        }
        
        .mode-stat-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .mode-stat-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            color: #333;
        }
        
        .progress-bar {
            height: 16px;
            background: #e8e8e8;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 8px;
        }
        
        .progress-fill {
            height: 100%;
            transition: width 0.5s ease;
        }
        
        .mode-stat-percentage {
            text-align: right;
            font-weight: 600;
            color: #666;
        }
        
        .syntekpro-theme-stats {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .theme-stat-row {
            display: flex;
            justify-content: space-between;
            padding: 12px;
            background: #f9f9f9;
            border-radius: 6px;
            border-left: 3px solid #667eea;
        }
        
        .theme-name {
            font-weight: 600;
        }
        
        .theme-count {
            color: #666;
        }
        
        .syntekpro-activity-timeline {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        
        .activity-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 6px;
        }
        
        .activity-icon {
            color: #667eea;
            font-size: 20px;
        }
        
        .activity-text {
            flex: 1;
        }
        
        .activity-time {
            color: #666;
            font-size: 12px;
        }
    </style>
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
            $analytics['total_toggles']++;
            $analytics['total_events']++;
            syntekpro_toggle_add_activity($analytics, 'Toggle button clicked', 'dashicons-update');
            break;
            
        case 'mode_change':
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
