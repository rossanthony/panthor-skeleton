<?php

namespace PanthorApplication;

use QL\Panthor\ControllerInterface;
use QL\Panthor\HTTP\CookieHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class EncryptedCookieTestPageController implements ControllerInterface
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
                This test uses <b>CookieHandler</b> to handle cookies, which requires PECL Libsodium and EncryptedCookiesMiddleware.<br><br>
            </p>

            <p>
                Enter the following code in your <code>public/index.php</code>:
            </p>
            <pre>// Add the following two lines before Slim is run
$encryptedCookies = $container->get('panthor.middleware.encrypted_cookies');
$app->add($encryptedCookies);

// Start app
$app->run();</pre>
        </section>
    </body>
</html>
HTML;

    /**
     * @var CookieHandler
     */
    private $cookie;

    /**
     * @param CookieHandler $cookie
     */
    public function __construct(CookieHandler $cookie)
    {
        $this->cookie = $cookie;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        $cookieName = 'encrypted-cookie-test';

        $cookie = $this->cookie->getCookie($request, $cookieName) ?: 'Not Found (Are encrypted cookies enabled?)';

        $cookieTest = "\nCookie test: $cookie";

        $contents = str_replace('{cookie}', $cookieTest, self::HTML);
        $response->getBody()->write($contents);

        return $this->cookie->withCookie($response, $cookieName, 'testing-' . \random_int(100, 200));
    }
}
