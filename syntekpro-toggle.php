<?php
/**
 * Plugin Name: Syntekpro-Toggle
 * Plugin URI: https://plugins.syntekpro.com/toggle
 * Description: A lightweight Dark/Light mode toggle that respects OS preferences and remembers user choices.
 * Version: 1.5.0
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
 * @version 1.5.0
 * @author Syntekpro <development@syntekpro.com>
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('SYNTEKPRO_TOGGLE_VERSION', '1.5.0');
define('SYNTEKPRO_TOGGLE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SYNTEKPRO_TOGGLE_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include admin functionality
if (is_admin()) {
    require_once SYNTEKPRO_TOGGLE_PLUGIN_DIR . 'admin/admin.php';
}

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
        'transition_speed' => '0.3'
    );
    
    $options = get_option('syntekpro_toggle_options', array());
    return wp_parse_args($options, $defaults);
}

/**
 * Enqueue scripts and styles
 */
function syntekpro_toggle_enqueue_assets() {
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
    $options = syntekpro_toggle_get_frontend_options();
    
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
            const savedMode = localStorage.getItem('syntekpro-dark-mode');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const defaultMode = '<?php echo esc_js($default_mode); ?>';
            
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
                        shouldEnableDark = prefersDark;
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
 * Add toggle button to frontend
 */
function syntekpro_toggle_button() {
    $options = syntekpro_toggle_get_frontend_options();
    
    // Don't show button if disabled in settings
    if (isset($options['enable_toggle']) && $options['enable_toggle'] !== '1') {
        return;
    }
    
    // Get theme, position, and size
    $theme = isset($options['toggle_theme']) ? $options['toggle_theme'] : 'default';
    $position = isset($options['button_position']) ? $options['button_position'] : 'bottom-right';
    $size = isset($options['button_size']) ? intval($options['button_size']) : 50;
    
    // Parse position
    $position_parts = explode('-', $position);
    $vertical = $position_parts[0]; // top or bottom
    $horizontal = $position_parts[1]; // left or right
    
    // Generate position styles
    $position_style = $vertical . ': 30px; ' . $horizontal . ': 30px;';
    
    // Generate size styles (adjust pill width proportionally)
    $size_style = 'width: ' . $size . 'px; height: ' . $size . 'px;';
    if ($theme === 'pill') {
        $pill_width = intval($size * 1.4);
        $size_style = 'width: ' . $pill_width . 'px; height: ' . $size . 'px;';
    }
    
    // Calculate icon size (80% of button size)
    $icon_size = intval($size * 0.48);
    ?>
    <button id="syntekpro-dark-mode-toggle" class="syntekpro-toggle-btn theme-<?php echo esc_attr($theme); ?>" aria-label="Toggle Dark Mode" style="<?php echo esc_attr($position_style . $size_style); ?>">
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
add_action('wp_footer', 'syntekpro_toggle_button');

/**
 * AJAX Handler for Analytics Tracking
 */
function syntekpro_toggle_ajax_track_analytics() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'syntekpro_analytics_nonce')) {
        wp_send_json_error('Invalid nonce');
        return;
    }
    
    // Get event type and data
    $event_type = isset($_POST['event_type']) ? sanitize_text_field($_POST['event_type']) : '';
    $event_data = isset($_POST['event_data']) ? json_decode(stripslashes($_POST['event_data']), true) : array();
    
    if (empty($event_type)) {
        wp_send_json_error('No event type provided');
        return;
    }
    
    // Track the event using the function from admin.php
    if (function_exists('syntekpro_toggle_track_event')) {
        syntekpro_toggle_track_event($event_type, $event_data);
        wp_send_json_success('Event tracked');
    } else {
        wp_send_json_error('Tracking function not available');
    }
}
add_action('wp_ajax_syntekpro_track_analytics', 'syntekpro_toggle_ajax_track_analytics');
add_action('wp_ajax_nopriv_syntekpro_track_analytics', 'syntekpro_toggle_ajax_track_analytics');
