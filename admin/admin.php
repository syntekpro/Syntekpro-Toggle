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
        'Toggle - Dark Mode',
        'Toggle',
        'manage_options',
        'syntekpro-toggle',
        'syntekpro_toggle_frontend_page',
        SYNTEKPRO_TOGGLE_PLUGIN_URL . 'assets/img/Syntekpro%20Toggle%20%20icon%20Grey%20New.png',
        30
    );
    
    // Add submenu pages
    add_submenu_page(
        'syntekpro-toggle',
        'Frontend Settings',
        'Frontend',
        'manage_options',
        'syntekpro-toggle',
        'syntekpro_toggle_frontend_page'
    );
    
    add_submenu_page(
        'syntekpro-toggle',
        'Admin UI Settings',
        'Admin UI',
        'manage_options',
        'syntekpro-toggle-admin-ui',
        'syntekpro_toggle_admin_ui_page'
    );
    
    add_submenu_page(
        'syntekpro-toggle',
        'Settings',
        'Settings',
        'manage_options',
        'syntekpro-toggle-settings',
        'syntekpro_toggle_settings_page'
    );
    
    add_submenu_page(
        'syntekpro-toggle',
        'Options',
        'Options',
        'manage_options',
        'syntekpro-toggle-options',
        'syntekpro_toggle_options_page'
    );
    
    add_submenu_page(
        'syntekpro-toggle',
        'About',
        'About',
        'manage_options',
        'syntekpro-toggle-about',
        'syntekpro_toggle_about_page'
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
                fab.className = 'syntekpro-admin-fab';
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
        'syntekpro-toggle-frontend'
    );
    
    add_settings_field(
        'default_mode',
        'Default Mode',
        'syntekpro_toggle_default_mode_callback',
        'syntekpro-toggle-frontend',
        'syntekpro_toggle_general_section'
    );
    
    add_settings_field(
        'enable_toggle',
        'Toggle Button',
        'syntekpro_toggle_enable_toggle_callback',
        'syntekpro-toggle-frontend',
        'syntekpro_toggle_general_section'
    );
    
    add_settings_field(
        'button_position',
        'Button Position',
        'syntekpro_toggle_button_position_callback',
        'syntekpro-toggle-frontend',
        'syntekpro_toggle_general_section'
    );
    
    add_settings_field(
        'button_size',
        'Button Size',
        'syntekpro_toggle_button_size_callback',
        'syntekpro-toggle-frontend',
        'syntekpro_toggle_general_section'
    );
    
    // Frontend Page - Color Scheme Section
    add_settings_section(
        'syntekpro_toggle_color_scheme_section',
        '🎨 Dark Mode Color Scheme',
        'syntekpro_toggle_color_scheme_section_callback',
        'syntekpro-toggle-frontend'
    );
    
    add_settings_field(
        'color_scheme_mode',
        'Color Scheme Mode',
        'syntekpro_toggle_color_scheme_mode_callback',
        'syntekpro-toggle-frontend',
        'syntekpro_toggle_color_scheme_section'
    );
    
    add_settings_field(
        'color_preset',
        'Color Preset',
        'syntekpro_toggle_color_preset_callback',
        'syntekpro-toggle-frontend',
        'syntekpro_toggle_color_scheme_section'
    );
    
    add_settings_field(
        'bg_color',
        'Background Color',
        'syntekpro_toggle_bg_color_callback',
        'syntekpro-toggle-frontend',
        'syntekpro_toggle_color_scheme_section'
    );
    
    add_settings_field(
        'text_color',
        'Text Color',
        'syntekpro_toggle_text_color_callback',
        'syntekpro-toggle-frontend',
        'syntekpro_toggle_color_scheme_section'
    );
    
    add_settings_field(
        'link_color',
        'Link Color',
        'syntekpro_toggle_link_color_callback',
        'syntekpro-toggle-frontend',
        'syntekpro_toggle_color_scheme_section'
    );
    
    add_settings_field(
        'secondary_bg_color',
        'Secondary Background',
        'syntekpro_toggle_secondary_bg_callback',
        'syntekpro-toggle-frontend',
        'syntekpro_toggle_color_scheme_section'
    );
    
    // Frontend Page - Color Adjustments Section
    add_settings_section(
        'syntekpro_toggle_color_adjustments_section',
        '🎚️ Color Adjustments',
        'syntekpro_toggle_color_adjustments_section_callback',
        'syntekpro-toggle-frontend'
    );
    
    add_settings_field(
        'brightness',
        '☀️ Brightness',
        'syntekpro_toggle_brightness_callback',
        'syntekpro-toggle-frontend',
        'syntekpro_toggle_color_adjustments_section'
    );
    
    add_settings_field(
        'contrast',
        '🔲 Contrast',
        'syntekpro_toggle_contrast_callback',
        'syntekpro-toggle-frontend',
        'syntekpro_toggle_color_adjustments_section'
    );
    
    add_settings_field(
        'sepia',
        '📜 Sepia',
        'syntekpro_toggle_sepia_callback',
        'syntekpro-toggle-frontend',
        'syntekpro_toggle_color_adjustments_section'
    );
    
    add_settings_field(
        'grayscale',
        '⚫ Grayscale',
        'syntekpro_toggle_grayscale_callback',
        'syntekpro-toggle-frontend',
        'syntekpro_toggle_color_adjustments_section'
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
        'admin_bg_color',
        'Admin Background',
        'syntekpro_toggle_admin_bg_color_callback',
        'syntekpro-toggle-admin-ui',
        'syntekpro_toggle_admin_ui_section'
    );

    add_settings_field(
        'admin_text_color',
        'Admin Text',
        'syntekpro_toggle_admin_text_color_callback',
        'syntekpro-toggle-admin-ui',
        'syntekpro_toggle_admin_ui_section'
    );

    add_settings_field(
        'admin_accent_color',
        'Admin Accent',
        'syntekpro_toggle_admin_accent_color_callback',
        'syntekpro-toggle-admin-ui',
        'syntekpro_toggle_admin_ui_section'
    );

    add_settings_field(
        'admin_surface_color',
        'Admin Surface',
        'syntekpro_toggle_admin_surface_color_callback',
        'syntekpro-toggle-admin-ui',
        'syntekpro_toggle_admin_ui_section'
    );

    add_settings_field(
        'admin_border_color',
        'Admin Border',
        'syntekpro_toggle_admin_border_color_callback',
        'syntekpro-toggle-admin-ui',
        'syntekpro_toggle_admin_ui_section'
    );

    add_settings_field(
        'admin_link_color',
        'Admin Link',
        'syntekpro_toggle_admin_link_color_callback',
        'syntekpro-toggle-admin-ui',
        'syntekpro_toggle_admin_ui_section'
    );

    add_settings_field(
        'admin_link_hover_color',
        'Admin Link Hover',
        'syntekpro_toggle_admin_link_hover_color_callback',
        'syntekpro-toggle-admin-ui',
        'syntekpro_toggle_admin_ui_section'
    );
    
    // Settings Page - Advanced Settings Section
    add_settings_section(
        'syntekpro_toggle_advanced_section',
        'Advanced Settings',
        'syntekpro_toggle_advanced_section_callback',
        'syntekpro-toggle-settings'
    );
    
    add_settings_field(
        'custom_css',
        'Custom CSS',
        'syntekpro_toggle_custom_css_callback',
        'syntekpro-toggle-settings',
        'syntekpro_toggle_advanced_section'
    );
    
    add_settings_field(
        'transition_speed',
        'Transition Speed',
        'syntekpro_toggle_transition_speed_callback',
        'syntekpro-toggle-settings',
        'syntekpro_toggle_advanced_section'
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
        'custom_css' => '',
        'transition_speed' => '0.3',
        'enable_admin_bar_icon' => '1',
        'enable_dashboard_widget' => '1',
        'enable_admin_dark_mode' => '1',
        'admin_bg_color' => '#0f1115',
        'admin_text_color' => '#e7e9ee',
        'admin_accent_color' => '#2563eb',
        'admin_surface_color' => '#191e2a',
        'admin_border_color' => '#2a3243',
        'admin_link_color' => '#9fc3ff',
        'admin_link_hover_color' => '#c8dcff'
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
    $sanitized = array();
    
    $sanitized['default_mode'] = isset($input['default_mode']) ? sanitize_text_field($input['default_mode']) : 'auto';
    $sanitized['enable_toggle'] = isset($input['enable_toggle']) ? '1' : '0';
    $sanitized['button_position'] = isset($input['button_position']) ? sanitize_text_field($input['button_position']) : 'bottom-right';
    $sanitized['button_size'] = isset($input['button_size']) ? absint($input['button_size']) : 50;
    $sanitized['color_scheme_mode'] = isset($input['color_scheme_mode']) ? sanitize_text_field($input['color_scheme_mode']) : 'preset';
    $sanitized['color_preset'] = isset($input['color_preset']) ? sanitize_text_field($input['color_preset']) : 'default';
    $sanitized['bg_color'] = isset($input['bg_color']) ? sanitize_hex_color($input['bg_color']) : '#1a1a1a';
    $sanitized['text_color'] = isset($input['text_color']) ? sanitize_hex_color($input['text_color']) : '#ffffff';
    $sanitized['link_color'] = isset($input['link_color']) ? sanitize_hex_color($input['link_color']) : '#6ea8fe';
    $sanitized['secondary_bg_color'] = isset($input['secondary_bg_color']) ? sanitize_hex_color($input['secondary_bg_color']) : '#2d2d2d';
    $sanitized['brightness'] = isset($input['brightness']) ? max(0, min(200, absint($input['brightness']))) : 100;
    $sanitized['contrast'] = isset($input['contrast']) ? max(0, min(200, absint($input['contrast']))) : 100;
    $sanitized['sepia'] = isset($input['sepia']) ? max(0, min(100, absint($input['sepia']))) : 0;
    $sanitized['grayscale'] = isset($input['grayscale']) ? max(0, min(100, absint($input['grayscale']))) : 0;
    $sanitized['custom_css'] = isset($input['custom_css']) ? wp_strip_all_tags($input['custom_css']) : '';
    $sanitized['transition_speed'] = isset($input['transition_speed']) ? floatval($input['transition_speed']) : 0.3;
    $sanitized['enable_admin_bar_icon'] = isset($input['enable_admin_bar_icon']) ? '1' : '0';
    $sanitized['enable_dashboard_widget'] = isset($input['enable_dashboard_widget']) ? '1' : '0';
    $sanitized['enable_admin_dark_mode'] = isset($input['enable_admin_dark_mode']) ? '1' : '0';
    $sanitized['admin_bg_color'] = isset($input['admin_bg_color']) ? sanitize_hex_color($input['admin_bg_color']) : '#0f1115';
    $sanitized['admin_text_color'] = isset($input['admin_text_color']) ? sanitize_hex_color($input['admin_text_color']) : '#e7e9ee';
    $sanitized['admin_accent_color'] = isset($input['admin_accent_color']) ? sanitize_hex_color($input['admin_accent_color']) : '#2563eb';
    $sanitized['admin_surface_color'] = isset($input['admin_surface_color']) ? sanitize_hex_color($input['admin_surface_color']) : '#191e2a';
    $sanitized['admin_border_color'] = isset($input['admin_border_color']) ? sanitize_hex_color($input['admin_border_color']) : '#2a3243';
    $sanitized['admin_link_color'] = isset($input['admin_link_color']) ? sanitize_hex_color($input['admin_link_color']) : '#9fc3ff';
    $sanitized['admin_link_hover_color'] = isset($input['admin_link_hover_color']) ? sanitize_hex_color($input['admin_link_hover_color']) : '#c8dcff';
    
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
    <div id="preset-container" style="<?php echo $options['color_scheme_mode'] !== 'preset' ? 'display:none;' : ''; ?>">
        <div class="syntekpro-preset-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 15px; margin-top: 10px;">
            <?php foreach ($presets as $key => $preset): ?>
                <label class="preset-option" style="cursor: pointer; border: 2px solid #ddd; border-radius: 8px; padding: 0; transition: all 0.3s; overflow: hidden;">
                    <input type="radio" name="syntekpro_toggle_options[color_preset]" value="<?php echo esc_attr($key); ?>" <?php checked($options['color_preset'], $key); ?> style="position: absolute; opacity: 0;">
                    
                    <!-- Mini Browser Window Preview -->
                    <div class="preset-window" style="background: <?php echo esc_attr($preset['bg']); ?>; padding: 0; position: relative;">
                        <!-- Browser Header -->
                        <div class="window-header" style="background: <?php echo esc_attr($preset['secondary']); ?>; padding: 6px 8px; border-bottom: 1px solid rgba(255,255,255,0.1);">
                            <div style="display: flex; gap: 3px;">
                                <span style="width: 6px; height: 6px; border-radius: 50%; background: <?php echo esc_attr($preset['link']); ?>; opacity: 0.7;"></span>
                                <span style="width: 6px; height: 6px; border-radius: 50%; background: <?php echo esc_attr($preset['link']); ?>; opacity: 0.5;"></span>
                                <span style="width: 6px; height: 6px; border-radius: 50%; background: <?php echo esc_attr($preset['link']); ?>; opacity: 0.3;"></span>
                            </div>
                        </div>
                        
                        <!-- Content Area -->
                        <div class="window-content" style="padding: 12px 10px; min-height: 100px;">
                            <!-- Header -->
                            <div style="background: <?php echo esc_attr($preset['secondary']); ?>; height: 8px; width: 80%; border-radius: 2px; margin-bottom: 8px;"></div>
                            
                            <!-- Text Lines -->
                            <div style="background: <?php echo esc_attr($preset['text']); ?>; height: 4px; width: 100%; border-radius: 2px; margin-bottom: 4px; opacity: 0.7;"></div>
                            <div style="background: <?php echo esc_attr($preset['text']); ?>; height: 4px; width: 90%; border-radius: 2px; margin-bottom: 4px; opacity: 0.6;"></div>
                            <div style="background: <?php echo esc_attr($preset['text']); ?>; height: 4px; width: 95%; border-radius: 2px; margin-bottom: 8px; opacity: 0.5;"></div>
                            
                            <!-- Link -->
                            <div style="background: <?php echo esc_attr($preset['link']); ?>; height: 4px; width: 50%; border-radius: 2px; margin-bottom: 8px;"></div>
                            
                            <!-- More Text -->
                            <div style="background: <?php echo esc_attr($preset['text']); ?>; height: 4px; width: 85%; border-radius: 2px; margin-bottom: 4px; opacity: 0.6;"></div>
                            <div style="background: <?php echo esc_attr($preset['text']); ?>; height: 4px; width: 75%; border-radius: 2px; opacity: 0.5;"></div>
                        </div>
                        
                        <!-- Theme Name Badge -->
                        <div style="background: rgba(0,0,0,0.3); color: <?php echo esc_attr($preset['text']); ?>; padding: 6px 10px; text-align: center; font-size: 11px; font-weight: 600; border-top: 1px solid rgba(255,255,255,0.1);">
                            <?php echo esc_html($preset['name']); ?>
                        </div>
                    </div>
                </label>
            <?php endforeach; ?>
        </div>
    </div>
    <style>
        .preset-option:hover { border-color: #2271b1; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
        .preset-option input[type="radio"]:checked ~ .preset-window { box-shadow: inset 0 0 0 2px #2271b1; }
        .preset-option:has(input[type="radio"]:checked) { border-color: #2271b1; border-width: 3px; }
    </style>
    <script>
        jQuery(document).ready(function($) {
            $('input[name="syntekpro_toggle_options[color_scheme_mode]"]').on('change', function() {
                if ($(this).val() === 'preset') {
                    $('#preset-container').slideDown();
                } else {
                    $('#preset-container').slideUp();
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

/**
 * Shared page header
 */
function syntekpro_toggle_page_header($page_title = 'Toggle Settings') {
    $options = syntekpro_toggle_get_options();
    ?>
    <div class="wrap syntekpro-toggle-admin">
        <!-- Header -->
        <div class="syntekpro-header">
            <img src="<?php echo esc_url(SYNTEKPRO_TOGGLE_PLUGIN_URL . 'assets/img/syntekpro-toggle-logo%20New.png'); ?>" alt="Syntekpro Toggle" class="syntekpro-header-logo">
            <div class="syntekpro-header-version">Version <?php echo esc_html(SYNTEKPRO_TOGGLE_VERSION); ?></div>
        </div>
        <h1><?php echo esc_html($page_title); ?></h1>
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
 * Frontend Settings Page
 */
function syntekpro_toggle_frontend_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    if (isset($_GET['settings-updated'])) {
        add_settings_error('syntekpro_toggle_messages', 'syntekpro_toggle_message', 'Settings Saved', 'updated');
    }
    
    settings_errors('syntekpro_toggle_messages');
    
    syntekpro_toggle_page_header('Frontend Settings');
    ?>
    <div class="syntekpro-content-wrapper">
        <div class="syntekpro-main-content">
            <form action="options.php" method="post">
                <?php
                settings_fields('syntekpro_toggle_settings');
                do_settings_sections('syntekpro-toggle-frontend');
                submit_button('Save Settings');
                ?>
            </form>
        </div>
        <div class="syntekpro-sidebar">
            <div class="syntekpro-admin-box">
                <h3>🎨 Frontend Dark Mode</h3>
                <p>Configure how dark mode works for your website visitors.</p>
                <ul>
                    <li><strong>Auto Mode:</strong> Respects user's OS preference</li>
                    <li><strong>Manual Mode:</strong> Only toggles when user clicks button</li>
                    <li><strong>Custom Colors:</strong> Works with all block themes</li>
                </ul>
            </div>
        </div>
    </div>
    <?php
    syntekpro_toggle_page_footer();
}

/**
 * Admin UI Settings Page
 */
function syntekpro_toggle_admin_ui_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    if (isset($_GET['settings-updated'])) {
        add_settings_error('syntekpro_toggle_messages', 'syntekpro_toggle_message', 'Settings Saved', 'updated');
    }
    
    settings_errors('syntekpro_toggle_messages');
    
    syntekpro_toggle_page_header('Admin UI Settings');
    ?>
    <div class="syntekpro-content-wrapper">
        <div class="syntekpro-main-content">
            <form action="options.php" method="post">
                <?php
                settings_fields('syntekpro_toggle_settings');
                do_settings_sections('syntekpro-toggle-admin-ui');
                submit_button('Save Settings');
                ?>
            </form>
        </div>
        <div class="syntekpro-sidebar">
            <div class="syntekpro-admin-box">
                <h3>🛠️ Admin Dark Mode</h3>
                <p>Customize the WordPress admin dashboard dark mode experience.</p>
                <ul>
                    <li><strong>Top Bar Icon:</strong> Quick toggle access</li>
                    <li><strong>Floating Button:</strong> Always available</li>
                    <li><strong>Custom Colors:</strong> Match your brand</li>
                </ul>
            </div>
        </div>
    </div>
    <?php
    syntekpro_toggle_page_footer();
}

/**
 * Settings Page
 */
function syntekpro_toggle_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    if (isset($_GET['settings-updated'])) {
        add_settings_error('syntekpro_toggle_messages', 'syntekpro_toggle_message', 'Settings Saved', 'updated');
    }
    
    settings_errors('syntekpro_toggle_messages');
    
    syntekpro_toggle_page_header('Advanced Settings');
    ?>
    <div class="syntekpro-content-wrapper">
        <div class="syntekpro-main-content">
            <form action="options.php" method="post">
                <?php
                settings_fields('syntekpro_toggle_settings');
                do_settings_sections('syntekpro-toggle-settings');
                submit_button('Save Settings');
                ?>
            </form>
        </div>
        <div class="syntekpro-sidebar">
            <div class="syntekpro-admin-box">
                <h3>⚙️ Advanced Options</h3>
                <p>Fine-tune your dark mode implementation with custom CSS and transition control.</p>
            </div>
        </div>
    </div>
    <?php
    syntekpro_toggle_page_footer();
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
        
        <div class="syntekpro-widget-actions">
            <a href="<?php echo esc_url(admin_url('admin.php?page=syntekpro-toggle')); ?>" class="button button-primary">
                <span class="dashicons dashicons-admin-settings"></span> Settings
            </a>
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
        'toplevel_page_syntekpro-toggle',               // Frontend page
        'toggle_page_syntekpro-toggle-admin-ui',        // Admin UI page
        'toggle_page_syntekpro-toggle-settings',        // Settings page
        'toggle_page_syntekpro-toggle-options',         // Options page
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
 * Options page HTML
 */
/**
 * Options Page
 */
function syntekpro_toggle_options_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    $options = syntekpro_toggle_get_options();
    syntekpro_toggle_page_header('Current Options');
    ?>
    <div class="syntekpro-content-wrapper">
        <div class="syntekpro-main-content">
            <div class="syntekpro-options-grid">
                <!-- Current Settings Overview -->
                <div class="syntekpro-option-card">
                    <div class="syntekpro-option-icon">
                        <span class="dashicons dashicons-admin-appearance"></span>
                    </div>
                    <h3>Current Mode</h3>
                    <p class="syntekpro-option-value"><?php echo esc_html(ucfirst($options['default_mode'])); ?></p>
                    <p class="description">Default mode when users visit your site</p>
                </div>
                
                <div class="syntekpro-option-card">
                    <div class="syntekpro-option-icon">
                        <span class="dashicons dashicons-visibility"></span>
                    </div>
                    <h3>Toggle Button</h3>
                    <p class="syntekpro-option-value"><?php echo $options['enable_toggle'] === '1' ? 'Enabled' : 'Disabled'; ?></p>
                    <p class="description">Toggle button visibility status</p>
                </div>
                
                <div class="syntekpro-option-card">
                    <div class="syntekpro-option-icon">
                        <span class="dashicons dashicons-location-alt"></span>
                    </div>
                    <h3>Button Position</h3>
                    <p class="syntekpro-option-value"><?php echo esc_html(ucwords(str_replace('-', ' ', $options['button_position']))); ?></p>
                    <p class="description">Current button placement</p>
                </div>
                
                <div class="syntekpro-option-card">
                    <div class="syntekpro-option-icon">
                        <span class="dashicons dashicons-image-crop"></span>
                    </div>
                    <h3>Button Size</h3>
                    <p class="syntekpro-option-value"><?php echo esc_html($options['button_size']); ?>px</p>
                    <p class="description">Toggle button dimensions</p>
                </div>
                
                <div class="syntekpro-option-card">
                    <div class="syntekpro-option-icon">
                        <span class="dashicons dashicons-art"></span>
                    </div>
                    <h3>Background Color</h3>
                    <p class="syntekpro-option-value">
                        <span class="color-preview" style="background-color: <?php echo esc_attr($options['bg_color']); ?>"></span>
                        <?php echo esc_html($options['bg_color']); ?>
                    </p>
                    <p class="description">Dark mode background</p>
                </div>
                
                <div class="syntekpro-option-card">
                    <div class="syntekpro-option-icon">
                        <span class="dashicons dashicons-editor-textcolor"></span>
                    </div>
                    <h3>Text Color</h3>
                    <p class="syntekpro-option-value">
                        <span class="color-preview" style="background-color: <?php echo esc_attr($options['text_color']); ?>"></span>
                        <?php echo esc_html($options['text_color']); ?>
                    </p>
                    <p class="description">Dark mode text color</p>
                </div>
                
                <div class="syntekpro-option-card">
                    <div class="syntekpro-option-icon">
                        <span class="dashicons dashicons-admin-links"></span>
                    </div>
                    <h3>Link Color</h3>
                    <p class="syntekpro-option-value">
                        <span class="color-preview" style="background-color: <?php echo esc_attr($options['link_color']); ?>"></span>
                        <?php echo esc_html($options['link_color']); ?>
                    </p>
                    <p class="description">Dark mode link color</p>
                </div>
                
                <div class="syntekpro-option-card">
                    <div class="syntekpro-option-icon">
                        <span class="dashicons dashicons-performance"></span>
                    </div>
                    <h3>Transition Speed</h3>
                    <p class="syntekpro-option-value"><?php echo esc_html($options['transition_speed']); ?>s</p>
                    <p class="description">Color change animation speed</p>
                </div>
            </div>
            
            <div class="syntekpro-options-actions">
                <a href="?page=syntekpro-toggle" class="button button-primary button-large">
                    <span class="dashicons dashicons-edit"></span> Frontend Settings
                </a>
                <a href="?page=syntekpro-toggle-admin-ui" class="button button-secondary button-large">
                    <span class="dashicons dashicons-admin-generic"></span> Admin UI Settings
                </a>
                <a href="<?php echo esc_url(home_url()); ?>" class="button button-secondary button-large" target="_blank">
                    <span class="dashicons dashicons-external"></span> View Site
                </a>
            </div>
        </div>
        
        <div class="syntekpro-sidebar">
            <div class="syntekpro-admin-box">
                <h3>📊 Plugin Stats</h3>
                <ul class="syntekpro-stats-list">
                    <li><strong>Version:</strong> <?php echo esc_html(SYNTEKPRO_TOGGLE_VERSION); ?></li>
                    <li><strong>Active:</strong> <?php echo $options['enable_toggle'] === '1' ? 'Yes' : 'No'; ?></li>
                    <li><strong>Theme Support:</strong> All Block Themes</li>
                </ul>
            </div>
            
            <div class="syntekpro-admin-box">
                <h3>🚀 Quick Actions</h3>
                <p><a href="?page=syntekpro-toggle" class="button button-secondary" style="width:100%;margin-bottom:8px;">Frontend Settings</a></p>
                <p><a href="?page=syntekpro-toggle-admin-ui" class="button button-secondary" style="width:100%;margin-bottom:8px;">Admin UI</a></p>
                <p><a href="?page=syntekpro-toggle-settings" class="button button-secondary" style="width:100%;">Advanced Settings</a></p>
            </div>
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
    
    syntekpro_toggle_page_header('About Syntekpro Toggle');
    ?>
    <div class="syntekpro-content-wrapper">
        <div class="syntekpro-main-content">
            <div class="syntekpro-admin-box">
                <h2>🌓 About Syntekpro Toggle</h2>
                <p><strong>Syntekpro Toggle</strong> is a lightweight and powerful dark mode plugin for WordPress. It provides an elegant solution for implementing dark/light mode toggling on your website with extensive customization options.</p>
                
                <h3>✨ Key Features</h3>
                <ul>
                    <li><strong>Auto Mode:</strong> Automatically respects users' OS dark mode preferences</li>
                    <li><strong>Manual Control:</strong> Let users toggle between dark and light modes</li>
                    <li><strong>Admin Dark Mode:</strong> Apply dark mode to the WordPress admin dashboard</li>
                    <li><strong>Customizable Colors:</strong> Full control over dark mode color schemes</li>
                    <li><strong>Block Theme Compatible:</strong> Works seamlessly with all WordPress block themes</li>
                    <li><strong>Transition Control:</strong> Adjust animation speed for smooth color transitions</li>
                    <li><strong>Custom CSS:</strong> Add your own CSS for advanced customizations</li>
                    <li><strong>FOUC Prevention:</strong> Prevents flash of unstyled content on page load</li>
                </ul>
                
                <h3>📦 Version Information</h3>
                <p><strong>Current Version:</strong> <?php echo esc_html(SYNTEKPRO_TOGGLE_VERSION); ?></p>
                <p><strong>Requires:</strong> WordPress 5.0+ | PHP 7.2+</p>
            </div>
            
            <div class="syntekpro-admin-box">
                <h2>🏢 About Syntekpro</h2>
                <p><strong>Syntekpro</strong> is dedicated to creating high-quality WordPress plugins and solutions that enhance website functionality and user experience.</p>
                <p>We focus on clean code, performance optimization, and user-friendly interfaces to help you build better websites.</p>
                
                <h3>🌐 Links</h3>
                <p>
                    <a href="https://syntekpro.com" target="_blank" rel="noopener noreferrer" class="button button-primary">
                        <span class="dashicons dashicons-admin-home"></span> Visit Syntekpro.com
                    </a>
                    <a href="https://plugins.syntekpro.com/toggle" target="_blank" rel="noopener noreferrer" class="button button-secondary">
                        <span class="dashicons dashicons-admin-plugins"></span> Plugin Page
                    </a>
                </p>
            </div>
            
            <div class="syntekpro-admin-box">
                <h2>💬 Help & Support</h2>
                <p>Need assistance? We're here to help!</p>
                
                <h3>📚 Documentation</h3>
                <p>Visit our comprehensive documentation for guides, tutorials, and best practices.</p>
                <p><a href="https://docs.syntekpro.com/toggle" target="_blank" rel="noopener noreferrer" class="button button-secondary">View Documentation</a></p>
                
                <h3>🎫 Support</h3>
                <p>Have a question or need technical support? Contact our support team.</p>
                <p><a href="mailto:support@syntekpro.com" class="button button-secondary">
                    <span class="dashicons dashicons-email"></span> Email Support
                </a></p>
                
                <h3>🐛 Report Issues</h3>
                <p>Found a bug? Let us know so we can fix it!</p>
                <p><a href="https://github.com/syntekpro/toggle/issues" target="_blank" rel="noopener noreferrer" class="button button-secondary">
                    <span class="dashicons dashicons-editor-code"></span> Report on GitHub
                </a></p>
                
                <h3>⭐ Rate Us</h3>
                <p>If you love Syntekpro Toggle, please consider leaving a 5-star review!</p>
                <p><a href="https://wordpress.org/support/plugin/syntekpro-toggle/reviews/#new-post" target="_blank" rel="noopener noreferrer" class="button button-secondary">
                    <span class="dashicons dashicons-star-filled"></span> Write a Review
                </a></p>
            </div>
        </div>
        
        <div class="syntekpro-sidebar">
            <div class="syntekpro-admin-box">
                <h3>🚀 Quick Links</h3>
                <ul class="syntekpro-stats-list">
                    <li><a href="?page=syntekpro-toggle">Frontend Settings</a></li>
                    <li><a href="?page=syntekpro-toggle-admin-ui">Admin UI Settings</a></li>
                    <li><a href="?page=syntekpro-toggle-settings">Advanced Settings</a></li>
                    <li><a href="?page=syntekpro-toggle-options">Current Options</a></li>
                </ul>
            </div>
            
            <div class="syntekpro-admin-box">
                <h3>📢 Stay Connected</h3>
                <p>Follow Syntekpro for updates and news:</p>
                <ul class="syntekpro-stats-list">
                    <li><a href="https://twitter.com/syntekpro" target="_blank" rel="noopener noreferrer">Twitter</a></li>
                    <li><a href="https://facebook.com/syntekpro" target="_blank" rel="noopener noreferrer">Facebook</a></li>
                    <li><a href="https://github.com/syntekpro" target="_blank" rel="noopener noreferrer">GitHub</a></li>
                </ul>
            </div>
        </div>
    </div>
    <?php
    syntekpro_toggle_page_footer();
}

/**
 * Output custom CSS based on settings
 */
function syntekpro_toggle_custom_css() {
    $options = syntekpro_toggle_get_options();
    
    // Get colors based on mode
    $bg_color = $options['bg_color'];
    $text_color = $options['text_color'];
    $link_color = $options['link_color'];
    $secondary_bg_color = $options['secondary_bg_color'];
    
    // Apply preset colors if preset mode is selected
    if ($options['color_scheme_mode'] === 'preset' && !empty($options['color_preset'])) {
        $presets = array(
            'default' => array('bg' => '#1a1a1a', 'text' => '#ffffff', 'link' => '#6ea8fe', 'secondary' => '#2d2d2d'),
            'midnight' => array('bg' => '#0f1419', 'text' => '#e6edf3', 'link' => '#58a6ff', 'secondary' => '#1c2128'),
            'carbon' => array('bg' => '#0d0d0d', 'text' => '#f0f0f0', 'link' => '#4a9eff', 'secondary' => '#1a1a1a'),
            'slate' => array('bg' => '#1e1e1e', 'text' => '#d4d4d4', 'link' => '#569cd6', 'secondary' => '#2d2d2d'),
            'ocean' => array('bg' => '#001f3f', 'text' => '#e8f4f8', 'link' => '#7fdbff', 'secondary' => '#002a52'),
            'forest' => array('bg' => '#0d1b0d', 'text' => '#e8f5e9', 'link' => '#81c784', 'secondary' => '#1b2f1b'),
            'purple' => array('bg' => '#1a0d2e', 'text' => '#f3e5f5', 'link' => '#ce93d8', 'secondary' => '#2e1a3e'),
            'dracula' => array('bg' => '#282a36', 'text' => '#f8f8f2', 'link' => '#8be9fd', 'secondary' => '#44475a'),
            'nord' => array('bg' => '#2e3440', 'text' => '#eceff4', 'link' => '#88c0d0', 'secondary' => '#3b4252'),
            'monokai' => array('bg' => '#272822', 'text' => '#f8f8f2', 'link' => '#66d9ef', 'secondary' => '#3e3d32'),
            'solarized' => array('bg' => '#002b36', 'text' => '#839496', 'link' => '#268bd2', 'secondary' => '#073642'),
            'gruvbox' => array('bg' => '#282828', 'text' => '#ebdbb2', 'link' => '#83a598', 'secondary' => '#3c3836'),
            'material' => array('bg' => '#263238', 'text' => '#eeffff', 'link' => '#82aaff', 'secondary' => '#37474f'),
            'one' => array('bg' => '#282c34', 'text' => '#abb2bf', 'link' => '#61afef', 'secondary' => '#21252b'),
            'tokyo' => array('bg' => '#1a1b26', 'text' => '#c0caf5', 'link' => '#7aa2f7', 'secondary' => '#24283b'),
            'ayu' => array('bg' => '#0f1419', 'text' => '#e6e1cf', 'link' => '#59c2ff', 'secondary' => '#191e2a'),
            'cobalt' => array('bg' => '#193549', 'text' => '#ffffff', 'link' => '#80ffbb', 'secondary' => '#234e6d'),
            'espresso' => array('bg' => '#2a211c', 'text' => '#bdae9d', 'link' => '#6c99bb', 'secondary' => '#392e28'),
            'synthwave' => array('bg' => '#262335', 'text' => '#f92aad', 'link' => '#72f1b8', 'secondary' => '#382e3c'),
            'rose' => array('bg' => '#191724', 'text' => '#e0def4', 'link' => '#c4a7e7', 'secondary' => '#1f1d2e'),
        );
        
        if (isset($presets[$options['color_preset']])) {
            $preset = $presets[$options['color_preset']];
            $bg_color = $preset['bg'];
            $text_color = $preset['text'];
            $link_color = $preset['link'];
            $secondary_bg_color = $preset['secondary'];
        }
    }
    
    // Build filter string for color adjustments
    $filters = array();
    if ($options['brightness'] != 100) {
        $filters[] = 'brightness(' . ($options['brightness'] / 100) . ')';
    }
    if ($options['contrast'] != 100) {
        $filters[] = 'contrast(' . ($options['contrast'] / 100) . ')';
    }
    if ($options['sepia'] > 0) {
        $filters[] = 'sepia(' . ($options['sepia'] / 100) . ')';
    }
    if ($options['grayscale'] > 0) {
        $filters[] = 'grayscale(' . ($options['grayscale'] / 100) . ')';
    }
    $filter_css = !empty($filters) ? 'filter: ' . implode(' ', $filters) . ';' : '';
    
    ?>
    <style id="syntekpro-toggle-custom-css">
        :root {
            --syntekpro-transition-speed: <?php echo esc_attr($options['transition_speed']); ?>s;
        }
        
        html.dark-mode,
        html.dark-mode body {
            --wp--preset--color--base: <?php echo esc_attr($bg_color); ?> !important;
            --wp--preset--color--contrast: <?php echo esc_attr($text_color); ?> !important;
            --wp--preset--color--primary: <?php echo esc_attr($text_color); ?> !important;
            background-color: <?php echo esc_attr($bg_color); ?> !important;
            color: <?php echo esc_attr($text_color); ?> !important;
            <?php echo $filter_css; ?>
        }
        
        html.dark-mode a {
            color: <?php echo esc_attr($link_color); ?>;
        }
        
        html.dark-mode header,
        html.dark-mode nav,
        html.dark-mode footer,
        html.dark-mode aside,
        html.dark-mode .widget {
            background-color: <?php echo esc_attr($secondary_bg_color); ?> !important;
        }
        
        <?php
        $position = $options['button_position'];
        $size = $options['button_size'];
        
        // Parse position
        $positions = array(
            'bottom-right' => array('bottom' => '30px', 'right' => '30px', 'top' => 'auto', 'left' => 'auto'),
            'bottom-left' => array('bottom' => '30px', 'left' => '30px', 'top' => 'auto', 'right' => 'auto'),
            'top-right' => array('top' => '30px', 'right' => '30px', 'bottom' => 'auto', 'left' => 'auto'),
            'top-left' => array('top' => '30px', 'left' => '30px', 'bottom' => 'auto', 'right' => 'auto'),
        );
        
        $pos = isset($positions[$position]) ? $positions[$position] : $positions['bottom-right'];
        ?>
        
        .syntekpro-toggle-btn {
            width: <?php echo esc_attr($size); ?>px;
            height: <?php echo esc_attr($size); ?>px;
            top: <?php echo esc_attr($pos['top']); ?>;
            bottom: <?php echo esc_attr($pos['bottom']); ?>;
            left: <?php echo esc_attr($pos['left']); ?>;
            right: <?php echo esc_attr($pos['right']); ?>;
            <?php if ($options['enable_toggle'] !== '1') : ?>
            display: none !important;
            <?php endif; ?>
        }
        
        <?php if (!empty($options['custom_css'])) : ?>
        html.dark-mode {
            <?php echo wp_strip_all_tags($options['custom_css']); ?>
        }
        <?php endif; ?>
    </style>
    <?php
}
add_action('wp_head', 'syntekpro_toggle_custom_css', 100);

/**
 * Pass settings to JavaScript
 */
function syntekpro_toggle_localize_script() {
    $options = syntekpro_toggle_get_options();
    ?>
    <script>
        window.syntekproToggleSettings = {
            defaultMode: '<?php echo esc_js($options['default_mode']); ?>',
            enableToggle: <?php echo $options['enable_toggle'] === '1' ? 'true' : 'false'; ?>
        };
    </script>
    <?php
}
add_action('wp_head', 'syntekpro_toggle_localize_script', 2);
