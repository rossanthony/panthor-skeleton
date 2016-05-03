<?php

namespace PanthorApplication;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use QL\Panthor\MiddlewareInterface;

class TestMiddleware implements MiddlewareInterface
{
    /**
     * The primary action of this middleware.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param MiddlewareInterface|callable $next
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $contents = <<<HTML
<style>
    .banner { background: #F9CB78; border: .25em solid #F7A91B; padding: .5em; position:absolute; right: 1em; top: 0; width: 25em; text-align: center; }
</style>
<p class="banner">
    This banner message was injected by middleware!
</p>
HTML;

        $response = $next($request, $response);
        $response->getBody()->write($contents);

        return $response;
    }
}
