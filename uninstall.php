<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package Syntekpro_Toggle
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

$option_keys = array(
    'syntekpro_toggle_options',
    'syntekpro_toggle_analytics',
);

foreach ($option_keys as $option_key) {
    delete_option($option_key);
    delete_site_option($option_key);
}
