<?php

namespace PanthorApplication;

use QL\Panthor\ControllerInterface;
use QL\Panthor\Utility\Url;
use Slim\Http\Response;

class TestPageController implements ControllerInterface
{
    /**
     * @var Response
     */
    private $response;

    /**
     * @var Url
     */
    private $url;

    /**
     * @param Response $response
     * @param Url $url
     */
    public function __construct(Response $response, Url $url)
    {
        $this->response = $response;
        $this->url = $url;
    }

    public function __invoke()
    {
        $this->response->setBody('Hello World!');
    }
}
