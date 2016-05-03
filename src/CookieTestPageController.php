<?php

namespace PanthorApplication;

use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookie;
use QL\Panthor\ControllerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CookieTestPageController implements ControllerInterface
{
    const HTML = <<<'HTML'
<!DOCTYPE html>
<html>
    <head>
        <style>
            body { font-family: sans-serif; line-height: 1.25em; }
            h1 { color: #3d68bd; font-size: 2em; }
            p { max-width: 40em; }
            code, pre { background: #eee; color: red; padding: 2px; }
            pre { max-width: 60em; }
        </style>
    </head>
    <body>
        <h1>Cookie Test</h1>
        <p>{cookie}</p>

        <p><a href="/">Home</a></p>

        <section>
            <p>
                This test uses <a href="https://github.com/dflydev/dflydev-fig-cookies">Dflydev\FigCookies</a> to handle unencrypted cookies.
                <br>If encrypted cookies are enabled, this will output an encrypted hash.
            </p>
        </section>
    </body>
</html>
HTML;

    /**
     * {@inheritdoc}
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        $cookieName = 'cookie-test';

        $cookie = FigRequestCookies::get($request, $cookieName);
        $cookie = $cookie->getValue() ?: 'Not Found';

        $cookieTest = "\nCookie test: $cookie";

        $contents = str_replace('{cookie}', $cookieTest, self::HTML);
        $response->getBody()->write($contents);

        $responseCookie = SetCookie::create($cookieName, 'testing-' . \random_int(100, 200));
        return FigResponseCookies::set($response, $responseCookie);
    }
}
