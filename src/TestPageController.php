<?php

namespace PanthorApplication;

use QL\Panthor\ControllerInterface;
use QL\Panthor\Utility\Url;
use Slim\Http\Response;

class TestPageController implements ControllerInterface
{
    const HTML = <<<'HTML'
<!DOCTYPE html>
<html>
    <head>
        <style>
            body { font-family: sans-serif; }
            h1 { color: #3d68bd; font-size: 2em; }
            h2 { font-size: 1.5em; }
            p { max-width: 40em; }

            table { border-collapse: collapse }
            table thead { border-bottom: 2px solid #999; color: #999; }
            table th { font-size: 1.2em; font-weight: normal; text-align: left; }

            table td { padding: .15em; padding: 1em 0; }
            table td:first-child { min-width: 200px; }

            table tr:not(:last-child) { border-bottom: 1px solid #999; }

            code { background: #eee; color: red; padding: 2px; }
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
            <li>Add your own logger so errors are logged somewhere!</li>
            <li>Use <b>Encrypted Cookies</b> to improve the security of your app.</li>
            <li>Add per-environment yaml config if you need to deploy to multiple environments.</li>
            <li>Run <code>bin/compile-di</code> to cache the DI container before deploying to a production environment.</li>
            <li>
                Run <code>bin/compile-templates</code> to cache templates before deploying to a production environment.
                <br><small>Template caching is disabled by default. Set <code>%twig.debug%</code> to <code>false</code> when deploying your app.</small>
            </li>
        </ul>

        <h4>Creating an HTML Application?</h4>
        <ul>
            <li>
                Remove the HTTP Problem Renderer to ensure all errors render through the twig template.
                <br><small>(Or leave it, it will not fire unless an HTTP Problem is specifically thrown)</small>
            </li>
        </ul>

        <h4>Creating an API?</h4>
        <ul>
            <li>
                Remove the HTML Renderer to ensure all errors render as HTTP Problem.
                <br><small>(If you don't like HTTP Problem, create your own error renderer)</small>
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
     * @var Response
     */
    private $response;

    /**
     * @var Url
     */
    private $url;

    /**
     * @var array
     */
    private $toc;

    /**
     * @var array
     */
    private $formatted;

    /**
     * @param Response $response
     * @param Url $url
     * @param array $toc
     */
    public function __construct(Response $response, Url $url, array $toc)
    {
        $this->response = $response;
        $this->url = $url;

        $this->toc = $toc;
        $this->formatted = [];
    }

    public function __invoke()
    {
        foreach ($this->toc as $route => $page) {
            list($title, $src) = $page;
            $this->formatted[] = $this->formatPage($route, $title, $src);
        }

        $html = str_replace('{examples}', implode('', $this->formatted), self::HTML);
        $this->response->setBody($html);
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
        return sprintf(self::PAGE_ROW, $this->url->urlFor($route), $title, $src);
    }
}
