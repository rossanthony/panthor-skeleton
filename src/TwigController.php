<?php

namespace PanthorApplication;

use QL\Panthor\ControllerInterface;
use QL\Panthor\TemplateInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class TwigController implements ControllerInterface
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var TemplateInterface
     */
    private $template;

    /**
     * @param Request $request
     * @param Response $response
     * @param TemplateInterface $template
     */
    public function __construct(Request $request, Response $response, TemplateInterface $template)
    {
        $this->request = $request;
        $this->response = $response;
        $this->template = $template;
    }

    public function __invoke()
    {
        $rendered = $this->template->render();
        $this->response->setBody($rendered);
    }
}
