# WPMCP - WordPress Model Context Protocol

WPMCP is a WordPress plugin that implements the Model Context Protocol (MCP) for integration with Claude Desktop.

## Installation

1. Download or clone this repository into your WordPress plugins directory:
   ```bash
   cd wp-content/plugins
   git clone https://github.com/emzimmer/wpmcp.git
   ```
2. Activate the plugin from WordPress admin panel
3. Go to Settings > WPMCP to configure your API key

## Claude Desktop Configuration

1. Locate your Claude Desktop configuration directory
2. Create or modify `claude_desktop_config.json`
3. Add the configuration from the example file in this repository
4. Update the URL and API key to match your WordPress installation

## Testing

Test the endpoint using cURL:

```bash
curl -X POST https://your-wordpress-site.com/wp-json/wpmcp/v1/test \
  -H "X-API-Key: YOUR_API_KEY_HERE"
```

## Security

- Always use HTTPS for your WordPress site
- Generate a secure API key
- Keep your API key private
- Regularly update the plugin and WordPress

## License

GPL v2 or later