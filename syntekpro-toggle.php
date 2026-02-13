<?php
/**
 * Plugin Name: Syntekpro-Toggle
 * Plugin URI: https://plugins.syntekpro.com/toggle
 * Description: A lightweight Dark/Light mode toggle that respects OS preferences and remembers user choices.
 * Version: 1.3.9
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
 * @version 1.3.9
 * @author Syntekpro <development@syntekpro.com>
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('SYNTEKPRO_TOGGLE_VERSION', '1.3.9');
define('SYNTEKPRO_TOGGLE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SYNTEKPRO_TOGGLE_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include admin functionality
if (is_admin()) {
    require_once SYNTEKPRO_TOGGLE_PLUGIN_DIR . 'admin/admin.php';
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
}
add_action('wp_enqueue_scripts', 'syntekpro_toggle_enqueue_assets');

/**
 * Add inline script in head to prevent FOUC
 * This runs BEFORE the page renders
 */
function syntekpro_toggle_inline_script() {
    $options = function_exists('syntekpro_toggle_get_options') ? syntekpro_toggle_get_options() : array('default_mode' => 'auto');
    $default_mode = isset($options['default_mode']) ? $options['default_mode'] : 'auto';
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
            }
        })();
    </script>
    <?php
}
add_action('wp_head', 'syntekpro_toggle_inline_script', 1);

/**
 * Add toggle button to frontend
 */
function syntekpro_toggle_button() {
    $options = function_exists('syntekpro_toggle_get_options') ? syntekpro_toggle_get_options() : array('enable_toggle' => '1');
    
    // Don't show button if disabled in settings
    if (isset($options['enable_toggle']) && $options['enable_toggle'] !== '1') {
        return;
    }
    ?>
    <button id="syntekpro-dark-mode-toggle" class="syntekpro-toggle-btn" aria-label="Toggle Dark Mode">
        <span class="syntekpro-icon-sun" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
            </svg>
        </span>
    </button>
    <?php
}
add_action('wp_footer', 'syntekpro_toggle_button');
