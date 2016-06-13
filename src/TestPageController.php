<?php

namespace PanthorApplication;

use QL\Panthor\ControllerInterface;
use QL\Panthor\Utility\URI;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class TestPageController implements ControllerInterface
{
    const HTML = <<<'HTML'
<!DOCTYPE html>
<html>
    <head>
        <style>
            body { font-family: sans-serif; line-height: 1.25em; }
            h1 { color: #3d68bd; font-size: 2em; }
            h2 { font-size: 1.5em; }
            p { max-width: 40em; }

            table { border-collapse: collapse }
            table thead { border-bottom: 2px solid #999; color: #999; }
            table th { font-size: 1.2em; font-weight: normal; text-align: left; }

            table td { padding: .15em; padding: 1em 0; }
            table td:first-child { min-width: 200px; }

            table tr:not(:last-child) { border-bottom: 1px solid #999; }

            code, pre { background: #eee; color: red; padding: 2px; }
            small { padding-left: .75em; }
            li { margin-bottom: .5em; }
        </style>
    </head>
    <body>
        <h1>Hello World!</h1>

        <h2>Example Pages</h2>
        <table>
            <thead>
                <th>Page</th>
                <th>Source</th>
            </thead>
            {examples}
        </table>

        <h2>What's Next?</h2>

        <p>
            Check out the full docs at the main panthor repository:<br>
            <a href="https://github.com/quickenloans-mcp/mcp-panthor/tree/2.3.1/docs">github.com/quickenloans-mcp/mcp-panthor - docs/</a>
        </p>

        <ul>
            <li>Replace <code>@logger</code> with your own PSR-3 Logger so errors are logged somewhere!</li>
            <li>Use <b>Encrypted Cookies</b> to improve the security of your app.</li>
            <li>Add per-environment yaml config if you need to deploy to multiple environments.</li>
            <li>
                Run <code>bin/compile-di</code> to cache the DI container before deploying to production.
                <br><small>DI caching is disabled by default. Set <code>%symfony.debug%</code> to <code>false</code> when deploying your app.</small>
            </li>
            <li>
                Run <code>bin/compile-routes</code> to cache Routes before deploying to production.
                <br><small>Route caching is disabled by default. Set <code>%routes.cache_disabled%</code> to <code>false</code> when deploying your app.</small>
            </li>
            <li>
                Run <code>bin/compile-templates</code> to cache templates before deploying to production.
                <br><small>Template caching is disabled by default. Set <code>%twig.debug%</code> to <code>false</code> when deploying your app.</small>
            </li>
            <li>
                Set <code>%slim.settings.display_errors%</code> to <code>false</code> when deploying your app to prevent users from seeing sensitive error details.
            </li>
        </ul>

        <h4>Creating an HTML Application?</h4>
        <ul>
            <li>
                Set your own <code>@content_handler</code> to override the default content handler configuration.
                <br><small>(Or leave it, HTTP Problem will not be used unless the http client explicitly accepts that mediatype)</small>
                <br><small>See <a href="https://github.com/quickenloans-mcp/mcp-panthor/blob/master/docs/ERRORS.md#exception-handler">Panthor documentation - error handling</a> for more information about content handlers.</small>
            </li>
        </ul>

        <h4>Creating an API?</h4>
        <ul>
            <li>
                By default, html media types are handled through <b>Twig</b>. If you do not want Twig as a dependency of your API, you will need to redefine your Content Handler configuration.
                <br>Here is an example of the media types handled by the default content handler:
                <pre>panthor.content_handler:
    class: 'QL\Panthor\ErrorHandling\ContentHandler\NegotiatingContentHandler'
    arguments:
        -
            '*/*': '@panthor.content_handler.html'
            'text/html': '@panthor.content_handler.html'
            'application/problem': '@panthor.content_handler.problem'
            'application/json': '@panthor.content_handler.json'
            'text/plain': '@panthor.content_handler.text'</pre>
            </li>
            <li>
                Set the service <code>@content_handler</code> to <code>@panthor.content_handler.problem</code> to ensure errors are always rendered as HTTP Problem.
                <br><small>See <a href="https://github.com/quickenloans-mcp/mcp-panthor/blob/master/docs/ERRORS.md#exception-handler">Panthor documentation - error handling</a> for more information about content handlers.</small>
            </li>
        </ul>
    </body>
</html>
HTML;

    const PAGE_ROW = <<<'HTML'
<tr>
    <td><a href="%s">%s</a></td>
    <td><code>%s</code></td>
</tr>
HTML;

    /**
     * @var URI
     */
    private $uri;

    /**
     * @var array
     */
    private $toc;

    /**
     * @param URI $uri
     * @param array $toc
     */
    public function __construct(URI $uri, array $toc)
    {
        $this->uri = $uri;
        $this->toc = $toc;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        $formatted = [];
        foreach ($this->toc as $route => $page) {
            list($title, $src) = $page;
            $formatted[] = $this->formatPage($route, $title, $src);
        }

        $html = str_replace('{examples}', implode('', $formatted), self::HTML);

        $response->getBody()->write($html);
        return $response;
    }

    /**
     * @param string $route
     * @param string $title
     * @param string $src
     *
     * @return string
     */
    private function formatPage($route, $title, $src)
    {
        return sprintf(self::PAGE_ROW, $this->uri->uriFor($route, ['id' => 313]), $title, $src);
    }
}
