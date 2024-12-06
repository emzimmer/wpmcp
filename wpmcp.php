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
        register_rest_route('claude-mcp/v1', '/data', array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_mcp_request'),
            'permission_callback' => '__return_true'  // We'll handle auth in the handler
        ));
    }

    public function handle_mcp_request($request) {
        // Get the raw POST data
        $json_str = file_get_contents('php://input');
        $data = json_decode($json_str, true);

        // Basic validation
        if (!$data || !isset($data['type'])) {
            return new WP_Error('invalid_request', 'Invalid request format', array('status' => 400));
        }

        // Handle different MCP request types
        switch ($data['type']) {
            case 'invoke':
                return $this->handle_invoke($data);
            case 'describe':
                return $this->handle_describe();
            default:
                return new WP_Error('invalid_type', 'Invalid request type', array('status' => 400));
        }
    }

    private function handle_invoke($data) {
        // For this proof of concept, we'll just return a success message
        return array(
            'type' => 'success',
            'data' => array(
                'message' => 'it worked!',
                'timestamp' => current_time('c')
            )
        );
    }

    private function handle_describe() {
        // Return the tool description
        return array(
            'type' => 'description',
            'data' => array(
                'name' => 'wordpress',
                'version' => '1.0.0',
                'description' => 'WordPress integration for Claude Desktop',
                'functions' => array(
                    array(
                        'name' => 'test',
                        'description' => 'Test the WordPress connection',
                        'parameters' => array(
                            'type' => 'object',
                            'properties' => array(),
                            'required' => array()
                        )
                    )
                )
            )
        );
    }
}

// Initialize the plugin
new WPMCP_Plugin();