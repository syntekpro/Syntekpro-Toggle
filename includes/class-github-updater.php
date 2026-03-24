<?php
/**
 * GitHub Updater for Syntekpro Toggle
 *
 * Hooks into WordPress's transient-based update system so that whenever a new
 * release is published on GitHub the site admin sees the same "Update available"
 * notice and one-click update that any wordpress.org plugin provides.
 *
 * Usage (from the main plugin file):
 *   new Syntekpro_Toggle_GitHub_Updater( __FILE__, 'syntekpro', 'Syntekpro-Toggle' );
 *
 * @package Syntekpro_Toggle
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Syntekpro_Toggle_GitHub_Updater' ) ) :

class Syntekpro_Toggle_GitHub_Updater {

    /** @var string  Absolute path to the main plugin file. */
    private $plugin_file;

    /** @var string  Plugin slug derived from the main file (folder/file.php). */
    private $plugin_slug;

    /** @var string  GitHub username / organisation. */
    private $github_user;

    /** @var string  GitHub repository name (exact, case-sensitive). */
    private $github_repo;

    /** @var string  Transient key used to cache the remote release data. */
    private $transient_key;

    /** @var int  How many seconds to cache the remote version check result. */
    private $cache_ttl = 43200; // 12 hours

    /**
     * Constructor.
     *
     * @param string $plugin_file  Absolute path to the plugin's main PHP file.
     * @param string $github_user  GitHub user / org that owns the repo.
     * @param string $github_repo  GitHub repository name.
     */
    public function __construct( $plugin_file, $github_user, $github_repo ) {
        $this->plugin_file   = $plugin_file;
        $this->plugin_slug   = plugin_basename( $plugin_file );
        $this->github_user   = sanitize_text_field( $github_user );
        $this->github_repo   = sanitize_text_field( $github_repo );
        $this->transient_key = 'syntekpro_toggle_github_release_' . md5( $this->github_user . '/' . $this->github_repo );

        add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_for_update' ) );
        add_filter( 'plugins_api', array( $this, 'plugin_info' ), 10, 3 );
        add_filter( 'upgrader_post_install', array( $this, 'after_install' ), 10, 3 );
    }

    // -------------------------------------------------------------------------
    // Remote data
    // -------------------------------------------------------------------------

    /**
     * Fetch the latest release data from the GitHub Releases API.
     * Results are cached in a site transient to avoid hammering the API.
     *
     * @return object|false  Decoded JSON object or false on failure.
     */
    private function get_remote_release() {
        $cached = get_site_transient( $this->transient_key );
        if ( $cached !== false ) {
            return $cached;
        }

        $api_url  = 'https://api.github.com/repos/'
                    . rawurlencode( $this->github_user ) . '/'
                    . rawurlencode( $this->github_repo ) . '/releases/latest';

        $response = wp_remote_get(
            $api_url,
            array(
                'timeout'    => 10,
                'user-agent' => 'WordPress/' . get_bloginfo( 'version' ) . '; ' . get_bloginfo( 'url' ),
                'headers'    => array( 'Accept' => 'application/vnd.github.v3+json' ),
                'sslverify'  => true,
            )
        );

        if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {
            // Cache a short negative result so we don't retry every page load on failure.
            set_site_transient( $this->transient_key, false, 600 );
            return false;
        }

        $body    = wp_remote_retrieve_body( $response );
        $release = json_decode( $body );

        if ( ! is_object( $release ) || empty( $release->tag_name ) ) {
            set_site_transient( $this->transient_key, false, 600 );
            return false;
        }

        set_site_transient( $this->transient_key, $release, $this->cache_ttl );
        return $release;
    }

    /**
     * Strip a leading "v" from a tag name so it can be compared with the
     * version string in the plugin header (which never has a "v" prefix).
     *
     * @param string $tag  Raw GitHub tag name, e.g. "v1.7.0" or "1.7.0".
     * @return string      Clean version string, e.g. "1.7.0".
     */
    private function normalise_version( $tag ) {
        return ltrim( sanitize_text_field( (string) $tag ), 'vV' );
    }

    // -------------------------------------------------------------------------
    // WordPress hooks
    // -------------------------------------------------------------------------

    /**
     * Called when WordPress checks for plugin updates.
     * Injects our update data if a newer version is available on GitHub.
     *
     * @param  object $transient  The `update_plugins` transient object.
     * @return object             (Possibly) modified transient.
     */
    public function check_for_update( $transient ) {
        if ( empty( $transient->checked ) ) {
            return $transient;
        }

        $release = $this->get_remote_release();
        if ( ! $release ) {
            return $transient;
        }

        $remote_version  = $this->normalise_version( $release->tag_name );
        $current_version = isset( $transient->checked[ $this->plugin_slug ] )
                            ? $transient->checked[ $this->plugin_slug ]
                            : SYNTEKPRO_TOGGLE_VERSION;

        if ( version_compare( $remote_version, $current_version, '>' ) ) {
            // Prefer a zip asset explicitly attached to the release; fall back to
            // the auto-generated zipball tarball provided by GitHub.
            $zip_url = $this->get_release_zip_url( $release );

            $update_obj                  = new stdClass();
            $update_obj->slug            = dirname( $this->plugin_slug );
            $update_obj->plugin          = $this->plugin_slug;
            $update_obj->new_version     = $remote_version;
            $update_obj->url             = 'https://github.com/' . $this->github_user . '/' . $this->github_repo;
            $update_obj->package         = $zip_url;
            $update_obj->requires        = '5.0';
            $update_obj->requires_php    = '7.2';
            $update_obj->tested          = get_bloginfo( 'version' );
            $update_obj->icons           = array(
                '1x' => SYNTEKPRO_TOGGLE_PLUGIN_URL . 'assets/img/Syntekpro%20Toggle%20%20icon%20Grey%20New.png',
                '2x' => SYNTEKPRO_TOGGLE_PLUGIN_URL . 'assets/img/Syntekpro%20Toggle%20%20icon%20Grey%20New.png',
            );

            $transient->response[ $this->plugin_slug ] = $update_obj;
        }

        return $transient;
    }

    /**
     * Find the best download URL from a GitHub release.
     * Prefers a `.zip` asset attached to the release; falls back to the
     * auto-generated zipball URL.
     *
     * @param  object $release  Decoded GitHub release object.
     * @return string           URL of the zip package to install.
     */
    private function get_release_zip_url( $release ) {
        if ( ! empty( $release->assets ) && is_array( $release->assets ) ) {
            foreach ( $release->assets as $asset ) {
                if ( isset( $asset->browser_download_url )
                    && substr( $asset->name, -4 ) === '.zip' ) {
                    return $asset->browser_download_url;
                }
            }
        }

        return $release->zipball_url;
    }

    /**
     * Populate the plugin information dialog (the "View version X.X.X details"
     * link in the Plugins list).
     *
     * @param  false|object|array  $result  Result from previous filters.
     * @param  string              $action  The API action requested.
     * @param  object              $args    Arguments passed to the API call.
     * @return false|object                 Plugin info object or false.
     */
    public function plugin_info( $result, $action, $args ) {
        if ( $action !== 'plugin_information' ) {
            return $result;
        }

        if ( ! isset( $args->slug ) || $args->slug !== dirname( $this->plugin_slug ) ) {
            return $result;
        }

        $release = $this->get_remote_release();
        if ( ! $release ) {
            return $result;
        }

        $remote_version = $this->normalise_version( $release->tag_name );
        $changelog_body = isset( $release->body ) ? wp_kses_post( $release->body ) : '';

        $info                = new stdClass();
        $info->name          = 'SyntekPro Toggle';
        $info->slug          = dirname( $this->plugin_slug );
        $info->version       = $remote_version;
        $info->author        = '<a href="https://syntekpro.com">Syntekpro</a>';
        $info->homepage      = 'https://plugins.syntekpro.com/toggle';
        $info->requires      = '5.0';
        $info->requires_php  = '7.2';
        $info->tested        = get_bloginfo( 'version' );
        $info->last_updated  = isset( $release->published_at ) ? $release->published_at : '';
        $info->sections      = array(
            'description' => '<p>A lightweight Dark/Light mode toggle that respects OS preferences and remembers user choices.</p>',
            'changelog'   => nl2br( $changelog_body ),
        );
        $info->download_link = $this->get_release_zip_url( $release );

        return $info;
    }

    /**
     * After the plugin zip is extracted, rename the folder back to the expected
     * directory name (GitHub auto-generates folders like "user-repo-abc1234").
     *
     * Handles three scenarios:
     *   1. Automatic update via the GitHub updater (hook_extra['plugin'] is set).
     *   2. Manual ZIP upload of an existing plugin (WordPress passes hook_extra['plugin']).
     *   3. Manual ZIP upload treated as a fresh install (no hook_extra['plugin']) —
     *      detected by matching the destination folder name against our repo name.
     *
     * The activate_plugin() call has been intentionally removed: WordPress
     * re-enables the plugin automatically after an upgrade, and calling it again
     * in the same PHP request can trigger the activation hook twice.
     *
     * @param  bool  $response    Whether the install succeeded.
     * @param  array $hook_extra  Extra install info.
     * @param  array $result      Result from the installer.
     * @return array              (Possibly modified) result array.
     */
    public function after_install( $response, $hook_extra, $result ) {
        global $wp_filesystem;

        $plugin_folder  = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . dirname( $this->plugin_slug );
        $installed_into = $result['destination'];

        // Determine if this installation belongs to our plugin.
        // Case A: WordPress explicitly tells us via hook_extra (standard update flow).
        // Case B: Destination folder name matches our expected slug or GitHub repo
        //         name (handles manual ZIP uploads and GitHub zipball installs).
        $dest_basename  = strtolower( basename( untrailingslashit( $installed_into ) ) );
        $expected_base  = strtolower( dirname( $this->plugin_slug ) );
        $github_pattern = strtolower( $this->github_repo );

        $is_our_plugin = ( isset( $hook_extra['plugin'] ) && $hook_extra['plugin'] === $this->plugin_slug )
                         || $dest_basename === $expected_base
                         || strpos( $dest_basename, $github_pattern ) !== false;

        if ( ! $is_our_plugin ) {
            return $result;
        }

        // Rename the extracted folder to the canonical plugin directory if needed.
        // GitHub's auto-generated ZIPs produce folders like "syntekpro-Syntekpro-Toggle-abc123".
        if ( trailingslashit( $installed_into ) !== trailingslashit( $plugin_folder ) ) {
            $wp_filesystem->move( $installed_into, $plugin_folder, true );
            $result['destination'] = $plugin_folder;
        }

        return $result;
    }

    /**
     * Manually clear the cached release data (useful when testing).
     */
    public function clear_cache() {
        delete_site_transient( $this->transient_key );
    }
}

endif; // class_exists Syntekpro_Toggle_GitHub_Updater
