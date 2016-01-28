# MCP Panthor Skeleton

This is a sample skeleton app using [MCP Panthor](https://github.com/quickenloans-mcp/mcp-panthor).

How to install:

1. Clone this repository.
2. Setup an NGINX virtual host and point it to `public/index.php`.
3. Load the site and check out some of the sample pages.

Please see [ql/mcp-panthor](https://github.com/quickenloans-mcp/mcp-panthor) for full documentation.

## Example NGINX Configuration

This example assumes you cloned this repository to `/var/www/panthor-skeleton`.

```
server {
    server_name             panthor.local;
    listen                  80;

    root                    /var/www/panthor-skeleton/public;

    location / {
        try_files   $uri    /index.php?$query_string;
    }

    location ~* \.php$ {
        try_files   $uri          /index.php?$query_string;

        fastcgi_pass              127.0.0.1:9000;
        fastcgi_index             index.php;
    }
}
```
