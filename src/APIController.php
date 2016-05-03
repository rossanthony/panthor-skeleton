<?php

namespace PanthorApplication;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use QL\Panthor\ControllerInterface;
use QL\Panthor\Utility\Json;

class APIController implements ControllerInterface
{
    /**
     * @var Json
     */
    private $json;

    /**
     * @param Json $json
     */
    public function __construct(Json $json)
    {
        $this->json = $json;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
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

        $response->getBody()->write($rendered);
        $response = $response->withHeader('Content-Type', 'application/hal+json');

        return $response;
    }
}
