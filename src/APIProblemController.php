<?php

namespace PanthorApplication;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use QL\Panthor\ControllerInterface;
use QL\Panthor\HTTPProblem\HTTPProblem;
use QL\Panthor\HTTPProblem\ProblemRendererInterface;
use QL\Panthor\HTTPProblem\ProblemRenderingTrait;

class APIProblemController implements ControllerInterface
{
    use ProblemRenderingTrait;

    /**
     * @var ProblemRendererInterface
     */
    private $renderer;

    /**
     * @param ProblemRendererInterface $renderer
     */
    public function __construct(ProblemRendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        $problem = new HTTPProblem(418, 'Something bad happened!', [
            'extra_context' => 'data1',
            'test_extension' => 'data2',
            'see_for_more_info' => 'https://tools.ietf.org/html/draft-ietf-appsawg-http-problem-03'
        ]);

        return $this->renderProblem($response, $this->renderer, $problem);
    }
}
