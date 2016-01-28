<?php

namespace PanthorApplication;

use QL\Panthor\ControllerInterface;
use QL\Panthor\Exception\HTTPProblemException;

class APIProblemController implements ControllerInterface
{
    public function __invoke()
    {
        throw new HTTPProblemException(418, 'Something bad happened!', [
            'extra_context' => 'data1',
            'test_extension' => 'data2',
            'see_for_more_info' => 'https://tools.ietf.org/html/draft-ietf-appsawg-http-problem-03'
        ]);
    }
}
