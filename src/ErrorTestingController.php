<?php

namespace PanthorApplication;

use Exception;
use QL\Panthor\ControllerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class ErrorTestingController implements ControllerInterface
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
            .alert {padding: 4px; background: red; color: white;}
        </style>
    </head>
    <body>
        <h1>Error Handling Test Page</h1>
        <p>
            Test how Panthor handles various types of errors.
            By default, <b>PSR-3 NullLogger</b> is used, which consumes all logs silently.
            <code>E_DEPRECATED</code> are logged but do not trigger the error page.
        </p>

        {triggered}

        <h2>Error Types</h2>
        <table>
            <thead>
                <th>Type</th>
                <th>Trigger Error?</th>
            </thead>
            <tr>
                <td><code>E_NOTICE</code></td>
                <td><a href="?type=E_NOTICE">Trigger</a></td>
            </tr>
            <tr>
                <td><code>E_DEPRECATED</code></td>
                <td><a href="?type=E_DEPRECATED">Trigger</a></td>
            </tr>
            <tr>
                <td><code>E_STRICT</code></td>
                <td><a href="?type=E_STRICT">Trigger</a></td>
            </tr>
            <tr>
                <td><code>E_WARNING</code></td>
                <td><a href="?type=E_WARNING">Trigger</a></td>
            </tr>
            <tr>
                <td><code>E_NOTICE,E_WARNING</code></td>
                <td><a href="?type=E_NOTICE,E_WARNING">Trigger</a></td>
            </tr>
            <tr>
                <td><code>E_ERROR</code></td>
                <td><a href="?type=E_ERROR">Trigger</a></td>
            </tr>
            <tr>
                <td><code>E_COMPILE_ERROR</code></td>
                <td><a href="?type=E_COMPILE_ERROR">Trigger</a></td>
            </tr>
            <tr>
                <td><code>E_PARSE</code></td>
                <td><a href="?type=E_PARSE">Trigger</a></td>
            </tr>
            <tr>
                <td><code>Exception</code></td>
                <td><a href="?type=EXCEPTION">Trigger</a></td>
            </tr>
        </table>

        <a href="/">Back to Home</a>
    </body>
</html>
HTML;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Response
     */
    private $response;

    /**
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function __invoke()
    {
        $alert = '';

        if ($error = $this->request->get('type')) {
            $isValidError = $this->throwError($error);
            if ($isValidError) {
                $alert = sprintf('<p class="alert">Error triggered: %s</p>', $error);
            }
        }

        $rendered = str_replace('{triggered}', $alert, self::HTML);
        $this->response->setBody($rendered);
    }

    /**
     * @param string $error
     *
     * @return bool
     */
    private function throwError($error)
    {
        switch($error) {

            case 'E_NOTICE':
                $d = $a;
                return true;

            case 'E_DEPRECATED':
                $test = 'asdasd';
                $test2 = 'asdasd';
                $test = split($test, $test2);
                return true;

            case 'E_STRICT':
                array_pop(explode('-', 'hello-world'));
                return true;

            case 'E_WARNING':
                constant('undefined_constant');
                return true;

            case 'E_NOTICE,E_WARNING':
                if ($undefined) {}
                constant('undefined_constant');
                return true;

            case 'E_ERROR':
                new noclass();
                return true;

            case 'E_COMPILE_ERROR':
                eval('const what = array();');
                return true;

            case 'E_PARSE':
                include __DIR__ . '/ErrorTestingControllerParseError.php';
                return true;

            case 'EXCEPTION':
                throw new Exception('Exception Handling Test');
                return true;

            default:
                return false;
        }
    }
}
