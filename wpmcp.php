<?php
/**
 * Plugin Name: WPMCP
 * Plugin URI: https://github.com/emzimmer/wpmcp
 * Description: WordPress Model Context Protocol (MCP) integration for Claude Desktop
 * Version: 1.0.0
 * Author: Your Name
 * License: GPL v2 or later
 */

if (!defined('ABSPATH')) {
    exit;
}

class WPMCP_Plugin {
    private $api_key = '';
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('rest_api_init', array($this, 'register_rest_routes'));
    }

    public function add_admin_menu() {
        add_options_page(
            'WPMCP Settings',
            'WPMCP',
            'manage_options',
            'wpmcp-settings',
            array($this, 'settings_page')
        );
    }

    public function register_settings() {
        register_setting('wpmcp_settings', 'wpmcp_api_key', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => ''
        ));
    }

    public function settings_page() {
        ?>
        <div class="wrap">
            <h2>WPMCP Settings</h2>
            <form method="post" action="options.php">
                <?php
                settings_fields('wpmcp_settings');
                do_settings_sections('wpmcp_settings');
                ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">API Key</th>
                        <td>
                            <input type="text" name="wpmcp_api_key" value="<?php echo esc_attr(get_option('wpmcp_api_key')); ?>" class="regular-text">
                            <p class="description">Generated API key for Claude Desktop integration</p>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    public function register_rest_routes() {
        register_rest_route('wpmcp/v1', '/test', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_test_request'),
            'permission_callback' => array($this, 'check_api_key')
        ));
    }

    public function check_api_key($request) {
        $headers = $request->get_headers();
        $api_key = isset($headers['x-api-key']) ? $headers['x-api-key'][0] : '';
        return $api_key === get_option('wpmcp_api_key');
    }

    public function handle_test_request($request) {
        return new WP_REST_Response(array(
            'message' => 'it worked!',
            'status' => 'success'
        ), 200);
    }
}

// Initialize the plugin
new WPMCP_Plugin();