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
        'Toggle - Dark Mode Settings',
        'Toggle',
        'manage_options',
        'syntekpro-toggle',
        'syntekpro_toggle_settings_page',
        SYNTEKPRO_TOGGLE_PLUGIN_URL . 'assets/img/toggle-icon.svg',
        30
    );
    
    // Add submenu items
    add_submenu_page(
        'syntekpro-toggle',
        'Settings',
        'Settings',
        'manage_options',
        'syntekpro-toggle',
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
}
add_action('admin_menu', 'syntekpro_toggle_admin_menu');

/**
 * Register settings
 */
function syntekpro_toggle_register_settings() {
    register_setting('syntekpro_toggle_settings', 'syntekpro_toggle_options', 'syntekpro_toggle_sanitize_options');
    
    // General Settings Section
    add_settings_section(
        'syntekpro_toggle_general_section',
        'General Settings',
        'syntekpro_toggle_general_section_callback',
        'syntekpro-toggle'
    );
    
    // Default Mode
    add_settings_field(
        'default_mode',
        'Default Mode',
        'syntekpro_toggle_default_mode_callback',
        'syntekpro-toggle',
        'syntekpro_toggle_general_section'
    );
    
    // Enable/Disable Toggle Button
    add_settings_field(
        'enable_toggle',
        'Toggle Button',
        'syntekpro_toggle_enable_toggle_callback',
        'syntekpro-toggle',
        'syntekpro_toggle_general_section'
    );
    
    // Button Position
    add_settings_field(
        'button_position',
        'Button Position',
        'syntekpro_toggle_button_position_callback',
        'syntekpro-toggle',
        'syntekpro_toggle_general_section'
    );
    
    // Button Size
    add_settings_field(
        'button_size',
        'Button Size',
        'syntekpro_toggle_button_size_callback',
        'syntekpro-toggle',
        'syntekpro_toggle_general_section'
    );
    
    // Appearance Settings Section
    add_settings_section(
        'syntekpro_toggle_appearance_section',
        'Dark Mode Colors',
        'syntekpro_toggle_appearance_section_callback',
        'syntekpro-toggle'
    );
    
    // Background Color
    add_settings_field(
        'bg_color',
        'Background Color',
        'syntekpro_toggle_bg_color_callback',
        'syntekpro-toggle',
        'syntekpro_toggle_appearance_section'
    );
    
    // Text Color
    add_settings_field(
        'text_color',
        'Text Color',
        'syntekpro_toggle_text_color_callback',
        'syntekpro-toggle',
        'syntekpro_toggle_appearance_section'
    );
    
    // Link Color
    add_settings_field(
        'link_color',
        'Link Color',
        'syntekpro_toggle_link_color_callback',
        'syntekpro-toggle',
        'syntekpro_toggle_appearance_section'
    );
    
    // Secondary Background
    add_settings_field(
        'secondary_bg_color',
        'Secondary Background',
        'syntekpro_toggle_secondary_bg_callback',
        'syntekpro-toggle',
        'syntekpro_toggle_appearance_section'
    );
    
    // Advanced Settings Section
    add_settings_section(
        'syntekpro_toggle_advanced_section',
        'Advanced Settings',
        'syntekpro_toggle_advanced_section_callback',
        'syntekpro-toggle'
    );
    
    // Custom CSS
    add_settings_field(
        'custom_css',
        'Custom CSS',
        'syntekpro_toggle_custom_css_callback',
        'syntekpro-toggle',
        'syntekpro_toggle_advanced_section'
    );
    
    // Transition Speed
    add_settings_field(
        'transition_speed',
        'Transition Speed',
        'syntekpro_toggle_transition_speed_callback',
        'syntekpro-toggle',
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
        'transition_speed' => '0.3'
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

/**
 * Settings page HTML
 */
function syntekpro_toggle_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    // Check if settings were saved
    if (isset($_GET['settings-updated'])) {
        add_settings_error('syntekpro_toggle_messages', 'syntekpro_toggle_message', 'Settings Saved', 'updated');
    }
    
    settings_errors('syntekpro_toggle_messages');
    
    // Get current tab
    $active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'settings';
    ?>
    <div class="wrap syntekpro-toggle-admin">
        <!-- Header -->
        <div class="syntekpro-header">
            <img src="<?php echo esc_url(SYNTEKPRO_TOGGLE_PLUGIN_URL . 'assets/img/syntekpro-toggle-logo.svg'); ?>" alt="Syntekpro Toggle" class="syntekpro-header-logo">
            <div class="syntekpro-header-version">Version <?php echo esc_html(SYNTEKPRO_TOGGLE_VERSION); ?></div>
        </div>
        
        <!-- Tabs -->
        <h2 class="nav-tab-wrapper">
            <a href="?page=syntekpro-toggle&tab=settings" class="nav-tab <?php echo $active_tab === 'settings' ? 'nav-tab-active' : ''; ?>">
                <span class="dashicons dashicons-admin-settings"></span> Settings
            </a>
            <a href="?page=syntekpro-toggle-options" class="nav-tab <?php echo $active_tab === 'options' ? 'nav-tab-active' : ''; ?>">
                <span class="dashicons dashicons-admin-generic"></span> Options
            </a>
        </h2>
        
        <div class="syntekpro-content-wrapper">
            <div class="syntekpro-main-content">
                <form action="options.php" method="post">
                    <?php
                    settings_fields('syntekpro_toggle_settings');
                    do_settings_sections('syntekpro-toggle');
                    submit_button('Save Settings');
                    ?>
                </form>
            </div>
            
            <div class="syntekpro-sidebar">
                <div class="syntekpro-admin-box">
                    <h3>🌓 Quick Tips</h3>
                    <ul>
                        <li><strong>Auto Mode:</strong> Respects user's OS preference</li>
                        <li><strong>Manual Mode:</strong> Only toggles when user clicks button</li>
                        <li><strong>Custom Colors:</strong> Works with all block themes</li>
                        <li><strong>Custom CSS:</strong> Add theme-specific overrides</li>
                    </ul>
                </div>
                
                <div class="syntekpro-admin-box">
                    <h3>🎨 Theme Compatibility</h3>
                    <p>This plugin works with all WordPress block themes including:</p>
                    <ul>
                        <li>Twenty Twenty-Five</li>
                        <li>Twenty Twenty-Four</li>
                        <li>Twenty Twenty-Three</li>
   Add dashboard widget
 */
function syntekpro_toggle_dashboard_widget() {
    wp_add_dashboard_widget(
        'syntekpro_toggle_widget',
        '<img src="' . esc_url(SYNTEKPRO_TOGGLE_PLUGIN_URL . 'assets/img/toggle-icon.svg') . '" style="width:16px;height:16px;vertical-align:middle;margin-right:5px;"> Toggle - Dark Mode',
        'syntekpro_toggle_dashboard_widget_content'
    );
}
add_action('wp_dashboard_setup', 'syntekpro_toggle_dashboard_widget');

/**
 * Dashboard widget content
 */
function syntekpro_toggle_dashboard_widget_content() {
    $options = syntekpro_toggle_get_options();
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
    if ($hook !== 'toplevel_page_syntekpro-toggle' && $hook !== 'toggle_page_syntekpro-toggle-options') {
        return;
    }
    
    // WordPress color picker
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');
    
    // Admin CSS
    wp_enqueue_style(
        'syntekpro-toggle-admin',
        SYNTEKPRO_TOGGLE_PLUGIN_URL . 'admin.css',
        array(),
        SYNTEKPRO_TOGGLE_VERSION
    );
    
    // Admin JS
    wp_enqueue_script(
        'syntekpro-toggle-admin',
        SYNTEKPRO_TOGGLE_PLUGIN_URL . 'admin.js',
        array('jquery', 'wp-color-picker'),
        SYNTEKPRO_TOGGLE_VERSION,
        true
    );
}
add_action('admin_enqueue_scripts', 'syntekpro_toggle_admin_enqueue_scripts');

/**
 * Options page HTML
 */
function syntekpro_toggle_options_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    $active_tab = 'options';
    $options = syntekpro_toggle_get_options();
    ?>
    <div class="wrap syntekpro-toggle-admin">
        <!-- Header -->
        <div class="syntekpro-header">
            <img src="<?php echo esc_url(SYNTEKPRO_TOGGLE_PLUGIN_URL . 'assets/img/syntekpro-toggle-logo.svg'); ?>" alt="Syntekpro Toggle" class="syntekpro-header-logo">
            <div class="syntekpro-header-version">Version <?php echo esc_html(SYNTEKPRO_TOGGLE_VERSION); ?></div>
        </div>
        
        <!-- Tabs -->
        <h2 class="nav-tab-wrapper">
            <a href="?page=syntekpro-toggle&tab=settings" class="nav-tab <?php echo $active_tab === 'settings' ? 'nav-tab-active' : ''; ?>">
                <span class="dashicons dashicons-admin-settings"></span> Settings
            </a>
            <a href="?page=syntekpro-toggle-options" class="nav-tab <?php echo $active_tab === 'options' ? 'nav-tab-active' : ''; ?>">
                <span class="dashicons dashicons-admin-generic"></span> Options
            </a>
        </h2>
        
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
                        <span class="dashicons dashicons-edit"></span> Edit Settings
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
                    <p><a href="?page=syntekpro-toggle" class="button button-secondary" style="width:100%;margin-bottom:8px;">Settings</a></p>
                    <p><a href="<?php echo esc_url(admin_url('plugins.php')); ?>" class="button button-secondary" style="width:100%;margin-bottom:8px;">All Plugins</a></p>
                    <p><a href="<?php echo esc_url(admin_url('themes.php')); ?>" class="button button-secondary" style="width:100%;">Themes</a></p>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="syntekpro-footer">
            <div class="syntekpro-footer-content">
                <span>Powered by</span>
                <img src="<?php echo esc_url(SYNTEKPRO_TOGGLE_PLUGIN_URL . 'assets/img/syntekpro-logo.svg'); ?>" alt="SyntekPro" class="syntekpro-footer-logo">
            </div>
        </div>
    </div>
    <?php
}

/**
 * Enqueue admin scripts and styles
 */
function syntekpro_toggle_admin_enqueue_scripts($hook) {
    if ($hook !== 'settings_page_syntekpro-toggle') {
        return;
    }
    
    // WordPress color picker
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');
    
    // Admin CSS
    wp_enqueue_style(
        'syntekpro-toggle-admin',
        SYNTEKPRO_TOGGLE_PLUGIN_URL . 'admin.css',
        array(),
        SYNTEKPRO_TOGGLE_VERSION
    );
    
    // Admin JS
    wp_enqueue_script(
        'syntekpro-toggle-admin',
        SYNTEKPRO_TOGGLE_PLUGIN_URL . 'admin.js',
        array('jquery', 'wp-color-picker'),
        SYNTEKPRO_TOGGLE_VERSION,
        true
    );
}
add_action('admin_enqueue_scripts', 'syntekpro_toggle_admin_enqueue_scripts');

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
