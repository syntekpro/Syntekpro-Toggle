<?php
/**
 * Plugin Name: SyntekPro Toggle
 * Plugin URI: https://plugins.syntekpro.com/toggle
 * Description: A lightweight Dark/Light mode toggle that respects OS preferences and remembers user choices.
 * Version: 1.6.2
 * Requires at least: 5.0
 * Requires PHP: 7.2
 * Author: Syntekpro
 * Author URI: https://syntekpro.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: syntekpro-toggle
 * Domain Path: /languages
 * 
 * @package Syntekpro_Toggle
 * @version 1.6.2
 * @author Syntekpro <development@syntekpro.com>
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('SYNTEKPRO_TOGGLE_VERSION', '1.6.2');
define('SYNTEKPRO_TOGGLE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SYNTEKPRO_TOGGLE_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Load plugin translations.
 */
function syntekpro_toggle_load_textdomain() {
    load_plugin_textdomain(
        'syntekpro-toggle',
        false,
        dirname(plugin_basename(__FILE__)) . '/languages'
    );
}
add_action('plugins_loaded', 'syntekpro_toggle_load_textdomain');

/**
 * Declare plugin consent category for the WordPress Consent API.
 *
 * Uses a runtime function check so the plugin remains fully backward compatible
 * when the Consent API is not available.
 */
function syntekpro_toggle_register_consent_api_support() {
    if (function_exists('wp_set_consent_type')) {
        wp_set_consent_type('preferences');
    }
}
add_action('init', 'syntekpro_toggle_register_consent_api_support', 1);

// Include admin functionality
if (is_admin()) {
    require_once SYNTEKPRO_TOGGLE_PLUGIN_DIR . 'admin/admin.php';
}

// GitHub update notifier (runs on both admin and cron contexts)
require_once SYNTEKPRO_TOGGLE_PLUGIN_DIR . 'includes/class-github-updater.php';
new Syntekpro_Toggle_GitHub_Updater(
    __FILE__,
    'syntekpro',       // GitHub organisation / username
    'Syntekpro-Toggle' // GitHub repository name (exact, case-sensitive)
);

/**
 * Get plugin options (available on both frontend and backend)
 */
function syntekpro_toggle_get_frontend_options() {
    $defaults = array(
        'default_mode' => 'auto',
        'enable_toggle' => '1',
        'button_position' => 'bottom-right',
        'button_size' => '50',
        'toggle_theme' => 'default',
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
        'debug_mode' => '0'
    );
    
    $options = get_option('syntekpro_toggle_options', array());
    return wp_parse_args($options, $defaults);
}

function syntekpro_toggle_parse_id_list($value) {
    if (empty($value)) {
        return array();
    }

    $parts = array_map('trim', explode(',', $value));
    $ids = array();
    foreach ($parts as $part) {
        $id = absint($part);
        if ($id > 0) {
            $ids[] = $id;
        }
    }

    return array_values(array_unique($ids));
}

function syntekpro_toggle_is_special_page() {
    if (!empty($GLOBALS['pagenow']) && in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'), true)) {
        return true;
    }

    if (function_exists('is_checkout') && is_checkout()) {
        return true;
    }

    if (function_exists('is_cart') && is_cart()) {
        return true;
    }

    if (function_exists('is_account_page') && is_account_page()) {
        return true;
    }

    if (is_page(array('checkout', 'cart', 'my-account', 'login', 'register'))) {
        return true;
    }

    return false;
}

function syntekpro_toggle_is_within_time_range($start, $end, $timestamp) {
    $start_parts = explode(':', $start);
    $end_parts = explode(':', $end);

    if (count($start_parts) < 2 || count($end_parts) < 2) {
        return true;
    }

    $start_minutes = absint($start_parts[0]) * 60 + absint($start_parts[1]);
    $end_minutes = absint($end_parts[0]) * 60 + absint($end_parts[1]);
    $now_minutes = absint(date('G', $timestamp)) * 60 + absint(date('i', $timestamp));

    if ($start_minutes <= $end_minutes) {
        return $now_minutes >= $start_minutes && $now_minutes <= $end_minutes;
    }

    return $now_minutes >= $start_minutes || $now_minutes <= $end_minutes;
}

function syntekpro_toggle_is_within_schedule($options) {
    if (!isset($options['schedule_enabled']) || $options['schedule_enabled'] !== '1') {
        return true;
    }

    $timestamp = current_time('timestamp');
    $day_map = array(
        'Mon' => 'mon',
        'Tue' => 'tue',
        'Wed' => 'wed',
        'Thu' => 'thu',
        'Fri' => 'fri',
        'Sat' => 'sat',
        'Sun' => 'sun'
    );
    $day_key = isset($day_map[date('D', $timestamp)]) ? $day_map[date('D', $timestamp)] : '';
    $days = isset($options['schedule_days']) && is_array($options['schedule_days']) ? $options['schedule_days'] : array();

    if ($day_key && !in_array($day_key, $days, true)) {
        return false;
    }

    return syntekpro_toggle_is_within_time_range($options['schedule_start'], $options['schedule_end'], $timestamp);
}

function syntekpro_toggle_is_user_allowed($options) {
    $visibility = isset($options['user_visibility']) ? $options['user_visibility'] : 'all';

    if ($visibility === 'all') {
        return true;
    }

    if ($visibility === 'logged_in') {
        return is_user_logged_in();
    }

    if ($visibility === 'guests') {
        return !is_user_logged_in();
    }

    if ($visibility === 'roles') {
        if (!is_user_logged_in()) {
            return false;
        }

        $user = wp_get_current_user();
        $allowed_roles = isset($options['user_roles']) && is_array($options['user_roles']) ? $options['user_roles'] : array();
        foreach ($user->roles as $role) {
            if (in_array($role, $allowed_roles, true)) {
                return true;
            }
        }
        return false;
    }

    return true;
}

function syntekpro_toggle_is_allowed_by_display_rules($options) {
    $mode = isset($options['display_mode']) ? $options['display_mode'] : 'all';
    if ($mode === 'all') {
        return true;
    }

    $post_types = isset($options['display_post_types']) && is_array($options['display_post_types']) ? $options['display_post_types'] : array();
    $page_ids = syntekpro_toggle_parse_id_list($options['display_pages']);
    $category_ids = syntekpro_toggle_parse_id_list($options['display_categories']);
    $tag_ids = syntekpro_toggle_parse_id_list($options['display_tags']);

    $is_match = false;

    if (!empty($post_types) && is_singular($post_types)) {
        $is_match = true;
    }

    if (!empty($page_ids) && is_page($page_ids)) {
        $is_match = true;
    }

    if (!empty($category_ids)) {
        if (is_category($category_ids)) {
            $is_match = true;
        } elseif (is_single() && has_category($category_ids)) {
            $is_match = true;
        }
    }

    if (!empty($tag_ids)) {
        if (is_tag($tag_ids)) {
            $is_match = true;
        } elseif (is_single() && has_tag($tag_ids)) {
            $is_match = true;
        }
    }

    if ($mode === 'include') {
        return $is_match;
    }

    if ($mode === 'exclude') {
        return !$is_match;
    }

    return true;
}

function syntekpro_toggle_is_theme_excluded($options) {
    if (empty($options['excluded_themes'])) {
        return false;
    }

    $themes = array_map('trim', explode(',', $options['excluded_themes']));
    $themes = array_filter($themes);
    if (empty($themes)) {
        return false;
    }

    $theme = wp_get_theme();
    $current = array($theme->get_stylesheet(), $theme->get_template());

    foreach ($current as $slug) {
        if (in_array($slug, $themes, true)) {
            return true;
        }
    }

    return false;
}

function syntekpro_toggle_get_storage_key($options) {
    $version = isset($options['storage_version']) ? absint($options['storage_version']) : 1;
    return 'syntekpro-dark-mode-v' . max(1, $version);
}

function syntekpro_toggle_should_render_on_page($options) {
    if (syntekpro_toggle_is_theme_excluded($options)) {
        return false;
    }

    if (isset($options['exclude_special_pages']) && $options['exclude_special_pages'] === '1' && syntekpro_toggle_is_special_page()) {
        return false;
    }

    if (!syntekpro_toggle_is_user_allowed($options)) {
        return false;
    }

    if (!syntekpro_toggle_is_within_schedule($options)) {
        return false;
    }

    if (!syntekpro_toggle_is_allowed_by_display_rules($options)) {
        return false;
    }

    return true;
}

function syntekpro_toggle_should_render_toggle($options) {
    if (isset($options['enable_toggle']) && $options['enable_toggle'] !== '1') {
        return false;
    }

    return syntekpro_toggle_should_render_on_page($options);
}

/**
 * Enqueue scripts and styles
 */
function syntekpro_toggle_enqueue_assets() {
    $options = syntekpro_toggle_get_frontend_options();
    if (syntekpro_toggle_is_theme_excluded($options)) {
        return;
    }

    // Enqueue CSS
    wp_enqueue_style(
        'syntekpro-toggle-style',
        SYNTEKPRO_TOGGLE_PLUGIN_URL . 'public/css/style.css',
        array(),
        SYNTEKPRO_TOGGLE_VERSION,
        'all'
    );
    
    // Enqueue JavaScript in HEAD (critical for preventing FOUC)
    wp_enqueue_script(
        'syntekpro-toggle-script',
        SYNTEKPRO_TOGGLE_PLUGIN_URL . 'public/js/script.js',
        array(),
        SYNTEKPRO_TOGGLE_VERSION,
        false // false = load in head, true = load in footer
    );
    
    // Get options for media settings
    // Prepare media settings
    $media_settings = array(
        'enableImageFilter' => isset($options['enable_image_filter']) && $options['enable_image_filter'] === '1',
        'imageBrightness' => isset($options['image_brightness']) ? intval($options['image_brightness']) : 100,
        'imageContrast' => isset($options['image_contrast']) ? intval($options['image_contrast']) : 100,
        'enableVideoFilter' => isset($options['enable_video_filter']) && $options['enable_video_filter'] === '1',
        'videoBrightness' => isset($options['video_brightness']) ? intval($options['video_brightness']) : 100,
        'videoContrast' => isset($options['video_contrast']) ? intval($options['video_contrast']) : 100,
        'enableSlideFilter' => isset($options['enable_slide_filter']) && $options['enable_slide_filter'] === '1',
        'slideBrightness' => isset($options['slide_brightness']) ? intval($options['slide_brightness']) : 100,
        'slideInvert' => isset($options['slide_invert']) && $options['slide_invert'] === '1'
    );
    
    // Localize script with AJAX data and media settings
    wp_localize_script('syntekpro-toggle-script', 'syntekproToggleAjax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('syntekpro_analytics_nonce')
    ));
    
    // Localize script with media settings
    wp_localize_script('syntekpro-toggle-script', 'syntekproToggleSettings', array(
        'defaultMode' => isset($options['default_mode']) ? $options['default_mode'] : 'auto',
        'enableToggle' => isset($options['enable_toggle']) ? $options['enable_toggle'] === '1' : true,
        'storageMode' => isset($options['storage_mode']) ? $options['storage_mode'] : 'local',
        'storageDays' => isset($options['storage_days']) ? intval($options['storage_days']) : 365,
        'storageKey' => syntekpro_toggle_get_storage_key($options),
        'autoModeSource' => isset($options['auto_mode_source']) ? $options['auto_mode_source'] : 'os',
        'autoTimeStart' => isset($options['auto_time_start']) ? $options['auto_time_start'] : '19:00',
        'autoTimeEnd' => isset($options['auto_time_end']) ? $options['auto_time_end'] : '07:00',
        'autoApplyOnLoad' => isset($options['auto_apply_on_load']) ? $options['auto_apply_on_load'] === '1' : true,
        'autoListenOs' => isset($options['auto_listen_os']) ? $options['auto_listen_os'] === '1' : true,
        'enableAnimations' => isset($options['enable_animations']) ? $options['enable_animations'] === '1' : true,
        'toggleAnimationSpeed' => isset($options['toggle_animation_speed']) ? floatval($options['toggle_animation_speed']) : 0.3,
        'respectReducedMotion' => isset($options['respect_reduced_motion']) ? $options['respect_reduced_motion'] === '1' : true,
        'forceHighContrast' => isset($options['force_high_contrast']) ? $options['force_high_contrast'] === '1' : false,
        'focusRingStyle' => isset($options['focus_ring_style']) ? $options['focus_ring_style'] : 'default',
        'analyticsDebounceMs' => isset($options['analytics_debounce_ms']) ? intval($options['analytics_debounce_ms']) : 500,
        'analyticsBatch' => isset($options['analytics_batch']) ? $options['analytics_batch'] === '1' : false,
        'analyticsBatchInterval' => isset($options['analytics_batch_interval']) ? intval($options['analytics_batch_interval']) : 5000,
        'analyticsBatchMax' => isset($options['analytics_batch_max']) ? intval($options['analytics_batch_max']) : 10,
        'analyticsPageviewOnceSession' => isset($options['analytics_pageview_once_session']) ? $options['analytics_pageview_once_session'] === '1' : true,
        'debugMode' => isset($options['debug_mode']) ? $options['debug_mode'] === '1' : false,
        'mediaSettings' => $media_settings
    ));
}
add_action('wp_enqueue_scripts', 'syntekpro_toggle_enqueue_assets');

/**
 * Add inline script in head to prevent FOUC
 * This runs BEFORE the page renders
 */
function syntekpro_toggle_inline_script() {
    $options = syntekpro_toggle_get_frontend_options();
    if (syntekpro_toggle_is_theme_excluded($options)) {
        return;
    }
    $default_mode = isset($options['default_mode']) ? $options['default_mode'] : 'auto';
    
    // Media settings for inline application
    $image_brightness = isset($options['image_brightness']) ? intval($options['image_brightness']) / 100 : 1;
    $image_contrast = isset($options['image_contrast']) ? intval($options['image_contrast']) / 100 : 1;
    $video_brightness = isset($options['video_brightness']) ? intval($options['video_brightness']) / 100 : 1;
    $video_contrast = isset($options['video_contrast']) ? intval($options['video_contrast']) / 100 : 1;
    $slide_brightness = isset($options['slide_brightness']) ? intval($options['slide_brightness']) / 100 : 1;
    $slide_invert = isset($options['slide_invert']) && $options['slide_invert'] === '1' ? 1 : 0;
    
    $enable_image_filter = isset($options['enable_image_filter']) && $options['enable_image_filter'] === '1' ? 'true' : 'false';
    $enable_video_filter = isset($options['enable_video_filter']) && $options['enable_video_filter'] === '1' ? 'true' : 'false';
    $enable_slide_filter = isset($options['enable_slide_filter']) && $options['enable_slide_filter'] === '1' ? 'true' : 'false';
    ?>
    <script>
        // Check localStorage or admin settings BEFORE page renders
        (function() {
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const defaultMode = '<?php echo esc_js($default_mode); ?>';
            const storageMode = '<?php echo esc_js($options['storage_mode']); ?>';
            const storageKey = '<?php echo esc_js(syntekpro_toggle_get_storage_key($options)); ?>';
            const autoModeSource = '<?php echo esc_js($options['auto_mode_source']); ?>';
            const autoApplyOnLoad = <?php echo esc_js($options['auto_apply_on_load'] === '1' ? 'true' : 'false'); ?>;
            const autoTimeStart = '<?php echo esc_js($options['auto_time_start']); ?>';
            const autoTimeEnd = '<?php echo esc_js($options['auto_time_end']); ?>';
            const enableAnimations = <?php echo esc_js($options['enable_animations'] === '1' ? 'true' : 'false'); ?>;
            const respectReducedMotion = <?php echo esc_js($options['respect_reduced_motion'] === '1' ? 'true' : 'false'); ?>;
            const forceHighContrast = <?php echo esc_js($options['force_high_contrast'] === '1' ? 'true' : 'false'); ?>;
            const focusRingStyle = '<?php echo esc_js($options['focus_ring_style']); ?>';
            const root = document.documentElement;

            if (!enableAnimations) {
                root.classList.add('syntekpro-animations-disabled');
            }

            if (respectReducedMotion && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                root.classList.add('syntekpro-reduced-motion');
            }

            if (forceHighContrast) {
                root.classList.add('syntekpro-high-contrast');
            }

            if (focusRingStyle) {
                root.classList.add('syntekpro-focus-' + focusRingStyle);
            }

            function getCookie(name) {
                const value = '; ' + document.cookie;
                const parts = value.split('; ' + name + '=');
                if (parts.length === 2) {
                    return parts.pop().split(';').shift();
                }
                return null;
            }

            function getStoredMode() {
                let value = null;
                if (storageMode === 'local' || storageMode === 'both') {
                    try {
                        value = localStorage.getItem(storageKey);
                    } catch (e) {
                        value = null;
                    }
                }
                if ((value === null || value === '') && (storageMode === 'cookie' || storageMode === 'both')) {
                    value = getCookie(storageKey);
                }
                return value;
            }

            function isWithinTimeRange(start, end) {
                if (!start || !end || start.indexOf(':') === -1 || end.indexOf(':') === -1) {
                    return false;
                }
                const now = new Date();
                const startParts = start.split(':');
                const endParts = end.split(':');
                const startMinutes = parseInt(startParts[0], 10) * 60 + parseInt(startParts[1], 10);
                const endMinutes = parseInt(endParts[0], 10) * 60 + parseInt(endParts[1], 10);
                const nowMinutes = now.getHours() * 60 + now.getMinutes();

                if (startMinutes <= endMinutes) {
                    return nowMinutes >= startMinutes && nowMinutes <= endMinutes;
                }
                return nowMinutes >= startMinutes || nowMinutes <= endMinutes;
            }

            const savedMode = getStoredMode();
            
            let shouldEnableDark = false;
            
            // Priority: localStorage > admin setting > OS preference
            if (savedMode !== null) {
                shouldEnableDark = (savedMode === 'true');
            } else {
                switch(defaultMode) {
                    case 'dark':
                        shouldEnableDark = true;
                        break;
                    case 'light':
                        shouldEnableDark = false;
                        break;
                    case 'auto':
                        if (!autoApplyOnLoad) {
                            shouldEnableDark = false;
                        } else if (autoModeSource === 'time') {
                            shouldEnableDark = isWithinTimeRange(autoTimeStart, autoTimeEnd);
                        } else {
                            shouldEnableDark = prefersDark;
                        }
                        break;
                    case 'manual':
                        shouldEnableDark = false;
                        break;
                }
            }
            
            if (shouldEnableDark) {
                document.documentElement.classList.add('dark-mode');
                
                // Apply media filters immediately
                const root = document.documentElement;
                
                // Image filters
                if (<?php echo esc_js($enable_image_filter); ?>) {
                    root.style.setProperty('--syntekpro-image-brightness', <?php echo esc_js($image_brightness); ?>);
                    root.style.setProperty('--syntekpro-image-contrast', <?php echo esc_js($image_contrast); ?>);
                } else {
                    root.style.setProperty('--syntekpro-image-brightness', '1');
                    root.style.setProperty('--syntekpro-image-contrast', '1');
                }
                
                // Video filters
                if (<?php echo esc_js($enable_video_filter); ?>) {
                    root.style.setProperty('--syntekpro-video-brightness', <?php echo esc_js($video_brightness); ?>);
                    root.style.setProperty('--syntekpro-video-contrast', <?php echo esc_js($video_contrast); ?>);
                } else {
                    root.style.setProperty('--syntekpro-video-brightness', '1');
                    root.style.setProperty('--syntekpro-video-contrast', '1');
                }
                
                // Slide filters
                if (<?php echo esc_js($enable_slide_filter); ?>) {
                    root.style.setProperty('--syntekpro-slide-brightness', <?php echo esc_js($slide_brightness); ?>);
                    root.style.setProperty('--syntekpro-slide-invert', <?php echo esc_js($slide_invert); ?>);
                } else {
                    root.style.setProperty('--syntekpro-slide-brightness', '1');
                    root.style.setProperty('--syntekpro-slide-invert', '0');
                }
            }
        })();
    </script>
    <?php
}
add_action('wp_head', 'syntekpro_toggle_inline_script', 1);

/**
 * Output custom CSS based on settings  
 */
function syntekpro_toggle_custom_css() {
    $options = syntekpro_toggle_get_frontend_options();
    if (syntekpro_toggle_is_theme_excluded($options)) {
        return;
    }
    
    // Default preset definitions
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
    
    // Get colors based on mode
    $bg_color = $options['bg_color'];
    $text_color = $options['text_color'];
    $link_color = $options['link_color'];
    $secondary_bg_color = $options['secondary_bg_color'];
    
    // Apply preset colors if preset mode is selected
    if ($options['color_scheme_mode'] === 'preset' && !empty($options['color_preset'])) {
        $preset_name = sanitize_text_field($options['color_preset']);
        
        if (isset($presets[$preset_name])) {
            $preset = $presets[$preset_name];
            $bg_color = $preset['bg'];
            $text_color = $preset['text'];
            $link_color = $preset['link'];
            $secondary_bg_color = $preset['secondary'];
        } else {
            // Fallback to default preset if selected preset is not found
            $bg_color = $presets['default']['bg'];
            $text_color = $presets['default']['text'];
            $link_color = $presets['default']['link'];
            $secondary_bg_color = $presets['default']['secondary'];
        }
    }
    
    $enable_animations = isset($options['enable_animations']) && $options['enable_animations'] === '1';
    $transition_speed = $enable_animations ? $options['transition_speed'] : '0';
    $toggle_animation_speed = $enable_animations ? $options['toggle_animation_speed'] : '0';

    // Build filter string for color adjustments
    $filters = array();
    if ($options['brightness'] != 100) {
        $filters[] = 'brightness(' . ($options['brightness'] / 100) . ')';
    }
    if ($options['contrast'] != 100) {
        $filters[] = 'contrast(' . ($options['contrast'] / 100) . ')';
    }
    if (!empty($options['force_high_contrast']) && $options['force_high_contrast'] === '1') {
        $filters[] = 'contrast(1.15)';
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
            --syntekpro-transition-speed: <?php echo esc_attr($transition_speed); ?>s;
            --syntekpro-transition-duration: <?php echo esc_attr($transition_speed); ?>s;
            --syntekpro-toggle-animation-duration: <?php echo esc_attr($toggle_animation_speed); ?>s;
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
        
        <?php if (!empty($options['custom_css'])) : ?>
        html.dark-mode {
            <?php echo wp_strip_all_tags($options['custom_css']); ?>
        }
        <?php endif; ?>

        <?php if (!empty($options['exclude_selectors'])) : ?>
            <?php
            $lines = preg_split('/\r\n|\r|\n/', $options['exclude_selectors']);
            foreach ($lines as $line) {
                $selector = trim($line);
                if ($selector === '') {
                    continue;
                }
                $selector = sanitize_text_field($selector);
                echo 'html.dark-mode ' . $selector . ' { filter: none !important; }';
            }
            ?>
        <?php endif; ?>
    </style>
    <?php
}
add_action('wp_head', 'syntekpro_toggle_custom_css', 100);

/**
 * Add toggle button to frontend
 */
function syntekpro_toggle_render_button($options, $args = array()) {
    $theme = isset($options['toggle_theme']) ? $options['toggle_theme'] : 'default';
    $position = isset($options['button_position']) ? $options['button_position'] : 'bottom-right';
    $size = isset($options['button_size']) ? intval($options['button_size']) : 50;

    $position_parts = explode('-', $position);
    $vertical = $position_parts[0];
    $horizontal = $position_parts[1];

    $position_style = $vertical . ': 30px; ' . $horizontal . ': 30px;';
    $size_style = 'width: ' . $size . 'px; height: ' . $size . 'px;';
    if ($theme === 'pill') {
        $pill_width = intval($size * 1.4);
        $size_style = 'width: ' . $pill_width . 'px; height: ' . $size . 'px;';
    }

    $icon_size = intval($size * 0.48);
    $button_shape = isset($options['button_shape']) ? $options['button_shape'] : 'default';
    $button_animation = isset($options['button_animation']) ? $options['button_animation'] : 'none';
    $button_bg_style = isset($options['button_bg_style']) ? $options['button_bg_style'] : 'solid';
    $inline = !empty($args['inline']);

    $button_classes = 'syntekpro-toggle-btn syntekpro-dark-mode-toggle theme-' . esc_attr($theme);
    if ($button_shape !== 'default') {
        $button_classes .= ' ' . esc_attr($button_shape);
    }
    if ($button_animation !== 'none') {
        $button_classes .= ' ' . esc_attr($button_animation);
    }
    if ($button_bg_style !== 'solid') {
        $button_classes .= ' ' . esc_attr($button_bg_style);
    }
    if ($inline) {
        $button_classes .= ' syntekpro-toggle-inline';
    }

    $id_attr = isset($args['id']) ? ' id="' . esc_attr($args['id']) . '"' : '';
    $style_attr = $inline ? $size_style : $position_style . $size_style;
    ?>
    <button<?php echo $id_attr; ?> class="<?php echo esc_attr($button_classes); ?>" aria-label="Toggle Dark Mode" style="<?php echo esc_attr($style_attr); ?>">
        <span class="syntekpro-icon-sun" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="<?php echo esc_attr($icon_size); ?>" height="<?php echo esc_attr($icon_size); ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="5"></circle>
                <line x1="12" y1="1" x2="12" y2="3"></line>
                <line x1="12" y1="21" x2="12" y2="23"></line>
                <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                <line x1="1" y1="12" x2="3" y2="12"></line>
                <line x1="21" y1="12" x2="23" y2="12"></line>
                <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
            </svg>
        </span>
        <span class="syntekpro-icon-moon" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="<?php echo esc_attr($icon_size); ?>" height="<?php echo esc_attr($icon_size); ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
            </svg>
        </span>
    </button>
    <?php
}

function syntekpro_toggle_button() {
    $options = syntekpro_toggle_get_frontend_options();

    if (!syntekpro_toggle_should_render_toggle($options)) {
        return;
    }

    syntekpro_toggle_render_button($options, array('id' => 'syntekpro-dark-mode-toggle'));
}
add_action('wp_footer', 'syntekpro_toggle_button');

function syntekpro_toggle_shortcode($atts) {
    $options = syntekpro_toggle_get_frontend_options();

    if (isset($options['enable_shortcode']) && $options['enable_shortcode'] !== '1') {
        return '';
    }

    if (!syntekpro_toggle_should_render_on_page($options)) {
        return '';
    }

    ob_start();
    syntekpro_toggle_render_button($options, array('inline' => true));
    return ob_get_clean();
}
add_shortcode('syntekpro_toggle', 'syntekpro_toggle_shortcode');

class Syntekpro_Toggle_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'syntekpro_toggle_widget',
            __('Syntekpro Toggle', 'syntekpro-toggle'),
            array('description' => __('Display the dark mode toggle button.', 'syntekpro-toggle'))
        );
    }

    public function widget($args, $instance) {
        $options = syntekpro_toggle_get_frontend_options();
        if (isset($options['enable_widget']) && $options['enable_widget'] !== '1') {
            return;
        }

        if (!syntekpro_toggle_should_render_on_page($options)) {
            return;
        }

        echo $args['before_widget'];
        syntekpro_toggle_render_button($options, array('inline' => true));
        echo $args['after_widget'];
    }
}

function syntekpro_toggle_register_widget() {
    $options = syntekpro_toggle_get_frontend_options();
    if (isset($options['enable_widget']) && $options['enable_widget'] !== '1') {
        return;
    }
    register_widget('Syntekpro_Toggle_Widget');
}
add_action('widgets_init', 'syntekpro_toggle_register_widget');

/**
 * AJAX Handler for Analytics Tracking
 */
function syntekpro_toggle_ajax_track_analytics() {
    // Verify nonce.
    if (false === check_ajax_referer('syntekpro_analytics_nonce', 'nonce', false)) {
        wp_send_json_error(__('Invalid nonce.', 'syntekpro-toggle'));
        return;
    }

    if (isset($_POST['events'])) {
        $events = json_decode(wp_unslash($_POST['events']), true);
        if (!is_array($events)) {
            wp_send_json_error(__('Invalid events payload.', 'syntekpro-toggle'));
            return;
        }

        if (!function_exists('syntekpro_toggle_track_event')) {
            wp_send_json_error(__('Tracking function not available.', 'syntekpro-toggle'));
            return;
        }

        foreach ($events as $event) {
            if (!is_array($event) || empty($event['type'])) {
                continue;
            }
            $event_type = sanitize_text_field($event['type']);
            $event_data = isset($event['data']) && is_array($event['data']) ? $event['data'] : array();
            syntekpro_toggle_track_event($event_type, $event_data);
        }

        wp_send_json_success(__('Events tracked.', 'syntekpro-toggle'));
        return;
    }
    
    // Get event type and data
    $event_type = isset($_POST['event_type']) ? sanitize_text_field(wp_unslash($_POST['event_type'])) : '';
    $event_data = isset($_POST['event_data']) ? json_decode(wp_unslash($_POST['event_data']), true) : array();
    if (!is_array($event_data)) {
        $event_data = array();
    }
    
    if (empty($event_type)) {
        wp_send_json_error(__('No event type provided.', 'syntekpro-toggle'));
        return;
    }
    
    // Track the event using the function from admin.php
    if (function_exists('syntekpro_toggle_track_event')) {
        syntekpro_toggle_track_event($event_type, $event_data);
        wp_send_json_success(__('Event tracked.', 'syntekpro-toggle'));
    } else {
        wp_send_json_error(__('Tracking function not available.', 'syntekpro-toggle'));
    }
}
add_action('wp_ajax_syntekpro_track_analytics', 'syntekpro_toggle_ajax_track_analytics');
add_action('wp_ajax_nopriv_syntekpro_track_analytics', 'syntekpro_toggle_ajax_track_analytics');
