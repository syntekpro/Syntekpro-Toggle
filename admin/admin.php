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
        SYNTEKPRO_TOGGLE_PLUGIN_URL . 'assets/img/Syntekpro Toggle  icon Grey.png',
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
            width: 30px;
            height: 30px;
            margin: 0;
            display: block;
        }
        #wpadminbar #wp-admin-bar-syntekpro-toggle-admin .ab-icon img {
            width: 30px;
            height: 30px;
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

        /* Floating admin toggle button */
        .syntekpro-admin-fab {
            position: fixed;
            bottom: 24px;
            right: 24px;
            width: 62px;
            height: 34px;
            border-radius: 999px;
            border: 1px solid var(--syntekpro-admin-border, #2a3243);
            background: var(--syntekpro-admin-surface, #191e2a);
            color: var(--syntekpro-admin-text, #e7e9ee);
            display: inline-flex;
            align-items: center;
            justify-content: space-between;
            padding: 4px 6px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.35);
            cursor: pointer;
            z-index: 9999;
            transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.2s ease;
            gap: 6px;
        }
        .syntekpro-admin-fab:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(0,0,0,0.4);
        }
        .syntekpro-admin-fab .syntekpro-fab-icon {
            font-size: 16px;
            line-height: 1;
        }
        .syntekpro-admin-fab .syntekpro-fab-thumb {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #ffffff;
            box-shadow: 0 1px 4px rgba(0,0,0,0.25);
            transition: transform 0.2s ease, background 0.2s ease;
            transform: translateX(0);
        }
        .syntekpro-admin-fab.is-dark {
            background: var(--syntekpro-admin-accent, #2563eb);
            color: #ffffff;
            border-color: var(--syntekpro-admin-border, #1e4fc1);
        }
        .syntekpro-admin-fab.is-dark .syntekpro-fab-thumb {
            transform: translateX(18px);
            background: #f8fafc;
        }
        .syntekpro-admin-fab.is-dark .syntekpro-fab-icon {
            color: #ffffff;
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

    $icon_url = SYNTEKPRO_TOGGLE_PLUGIN_URL . 'assets/img/Syntekpro%20Toggle%20%20icon%20Grey.png';
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
                    const icon = fab.querySelector('.syntekpro-fab-icon');
                    if (icon) {
                        icon.classList.toggle('dashicons-visibility', !state);
                        icon.classList.toggle('dashicons-hidden', state);
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
            if (allowDark && !document.querySelector('.syntekpro-admin-fab')) {
                const fab = document.createElement('button');
                fab.type = 'button';
                fab.className = 'syntekpro-admin-fab';
                fab.setAttribute('aria-label', 'Toggle admin dark mode');
                fab.innerHTML = '<span class="syntekpro-fab-icon dashicons dashicons-visibility"></span><span class="syntekpro-fab-thumb"></span>';
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
    register_setting('syntekpro_toggle_settings', 'syntekpro_toggle_options', 'syntekpro_toggle_sanitize_options');
    
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
    
    // Frontend Page - Appearance Settings Section
    add_settings_section(
        'syntekpro_toggle_appearance_section',
        'Dark Mode Colors',
        'syntekpro_toggle_appearance_section_callback',
        'syntekpro-toggle-frontend'
    );
    
    add_settings_field(
        'bg_color',
        'Background Color',
        'syntekpro_toggle_bg_color_callback',
        'syntekpro-toggle-frontend',
        'syntekpro_toggle_appearance_section'
    );
    
    add_settings_field(
        'text_color',
        'Text Color',
        'syntekpro_toggle_text_color_callback',
        'syntekpro-toggle-frontend',
        'syntekpro_toggle_appearance_section'
    );
    
    add_settings_field(
        'link_color',
        'Link Color',
        'syntekpro_toggle_link_color_callback',
        'syntekpro-toggle-frontend',
        'syntekpro_toggle_appearance_section'
    );
    
    add_settings_field(
        'secondary_bg_color',
        'Secondary Background',
        'syntekpro_toggle_secondary_bg_callback',
        'syntekpro-toggle-frontend',
        'syntekpro_toggle_appearance_section'
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
        'bg_color' => '#1a1a1a',
        'text_color' => '#ffffff',
        'link_color' => '#6ea8fe',
        'secondary_bg_color' => '#2d2d2d',
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
    $sanitized['bg_color'] = isset($input['bg_color']) ? sanitize_hex_color($input['bg_color']) : '#1a1a1a';
    $sanitized['text_color'] = isset($input['text_color']) ? sanitize_hex_color($input['text_color']) : '#ffffff';
    $sanitized['link_color'] = isset($input['link_color']) ? sanitize_hex_color($input['link_color']) : '#6ea8fe';
    $sanitized['secondary_bg_color'] = isset($input['secondary_bg_color']) ? sanitize_hex_color($input['secondary_bg_color']) : '#2d2d2d';
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

function syntekpro_toggle_appearance_section_callback() {
    echo '<p>Customize the colors used in dark mode. These settings work with all block themes.</p>';
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
            <img src="<?php echo esc_url(SYNTEKPRO_TOGGLE_PLUGIN_URL . 'assets/img/syntekpro-toggle-logo.png'); ?>" alt="Syntekpro Toggle" class="syntekpro-header-logo">
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
        <!-- Footer -->
        <div class="syntekpro-footer">
            <div class="syntekpro-footer-content">
                <span>Powered by</span>
                <a href="https://syntekpro.com" target="_blank" rel="noopener noreferrer" class="syntekpro-footer-link">
                    <img src="<?php echo esc_url(SYNTEKPRO_TOGGLE_PLUGIN_URL . 'assets/img/syntekpro-logo.png'); ?>" alt="SyntekPro" class="syntekpro-footer-logo">
                </a>
            </div>
        </div>
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
        '<img src="' . esc_url(SYNTEKPRO_TOGGLE_PLUGIN_URL . 'assets/img/toggle-icon.png') . '" style="width:16px;height:16px;vertical-align:middle;margin-right:5px;"> Toggle - Dark Mode',
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
    ?>
    <style id="syntekpro-toggle-custom-css">
        :root {
            --syntekpro-transition-speed: <?php echo esc_attr($options['transition_speed']); ?>s;
        }
        
        html.dark-mode,
        html.dark-mode body {
            --wp--preset--color--base: <?php echo esc_attr($options['bg_color']); ?> !important;
            --wp--preset--color--contrast: <?php echo esc_attr($options['text_color']); ?> !important;
            --wp--preset--color--primary: <?php echo esc_attr($options['text_color']); ?> !important;
            background-color: <?php echo esc_attr($options['bg_color']); ?> !important;
            color: <?php echo esc_attr($options['text_color']); ?> !important;
        }
        
        html.dark-mode a {
            color: <?php echo esc_attr($options['link_color']); ?>;
        }
        
        html.dark-mode header,
        html.dark-mode nav,
        html.dark-mode footer,
        html.dark-mode aside,
        html.dark-mode .widget {
            background-color: <?php echo esc_attr($options['secondary_bg_color']); ?> !important;
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
