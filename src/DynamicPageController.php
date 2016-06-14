<?php

namespace PanthorApplication;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use QL\Panthor\ControllerInterface;
use QL\Panthor\TemplateInterface;

class DynamicPageController implements ControllerInterface
{
    /**
     * @var TemplateInterface
     */
    private $template;

    /**
     * @param TemplateInterface $template
     */
    public function __construct(TemplateInterface $template)
    {
        $this->template = $template;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        $route = $request->getAttribute('route');

        $context = [
            'id' => $route->getArgument('id'),
            'name' => $route->getArgument('name')
        ];

        $rendered = $this->template->render($context);

        $response->getBody()->write($rendered);
        return $response;
    }
}
