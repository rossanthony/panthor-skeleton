# MCP Panthor Skeleton

[MCP Panthor](https://github.com/quickenloans-mcp/mcp-panthor) is a thin PHP microframework built on Slim and Symfony.
It takes the simplicity of Slim and provides a bit more structure for applications with a few Symfony components.

This is a sample skeleton app used to showcase some simple example controllers and pages, and get users up and running
more quickly.

How to install:

1. Clone this repository.
2. Setup an NGINX virtual host and point it to `public/index.php`.
3. Load the site and check out some of the sample pages.

Please see [ql/mcp-panthor](https://github.com/quickenloans-mcp/mcp-panthor) for full documentation.

## Example Application

![Example](/public/example.png?raw=true)

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

        include                   /usr/local/etc/nginx/fastcgi_params;
        fastcgi_param             SCRIPT_FILENAME $document_root$fastcgi_script_name;

        fastcgi_pass              127.0.0.1:9000;
        fastcgi_index             index.php;
    }
}
```
