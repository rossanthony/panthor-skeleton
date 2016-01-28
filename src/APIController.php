<?php

namespace PanthorApplication;

use QL\Panthor\ControllerInterface;
use QL\Panthor\Utility\Json;
use Slim\Http\Request;
use Slim\Http\Response;

class APIController implements ControllerInterface
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
     * @var Json
     */
    private $json;

    /**
     * @param Request $request
     * @param Response $response
     * @param Json $json
     */
    public function __construct(Request $request, Response $response, Json $json)
    {
        $this->request = $request;
        $this->response = $response;

        $this->json = $json;
    }

    public function __invoke()
    {
        // Example from HAL Hypermedia spec at http://stateless.co/hal_specification.html
        $data = [
            '_links' => [
                'self' => ['href' => '/api-example'],
                "admin" => [
                    [
                        'href' => '/admins/2',
                        'title' => 'Fred'
                    ],
                    [
                        'href' => '/admins/5',
                        'title' => 'Kate'
                    ]
                ]
            ],

            '_embedded' => [
                'order' => [
                    [
                        '_links' => [
                            'self' => ['href' => '/orders/123'],
                            'customer' => ['href' => '/customers/21']
                        ],
                        'total' => 30.00,
                        'currency' => 'USD',
                        'status' => 'shipped'
                    ],
                    [
                        '_links' => [
                            'self' => ['href' => '/orders/456'],
                            'customer' => ['href' => '/customers/22']
                        ],
                        'total' => 20.00,
                        'currency' => 'USD',
                        'status' => 'processing'
                    ],
                ]
            ],

            'currentlyProcessing' => 14,
            'shippedToday' => 20,
        ];

        $rendered = $this->json->encode($data);

        $this->response->setBody($rendered);
        $this->response->headers->set('Content-Type', 'application/hal+json');
    }
}
