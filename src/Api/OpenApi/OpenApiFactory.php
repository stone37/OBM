<?php

namespace App\Api\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\Model\Operation;
use ApiPlatform\Core\OpenApi\Model\PathItem;
use ApiPlatform\Core\OpenApi\Model\RequestBody;
use ApiPlatform\Core\OpenApi\OpenApi;
use ArrayObject;

class OpenApiFactory implements OpenApiFactoryInterface
{
    private OpenApiFactoryInterface $decorated;

    public function __construct(OpenApiFactoryInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);

        /*** @var PathItem $path */
        foreach ($openApi->getPaths()->getPaths() as $key => $path) {
            if ($path->getGet() && $path->getGet()->getSummary() === 'hidden') {
                $openApi->getPaths()->addPath($key, $path->withGet(null));
            }
        }

        $schemas = $openApi->getComponents()->getSecuritySchemes();
        $schemas['bearerAuth'] = new ArrayObject([
            'type' => 'http',
            'scheme' => 'bearer',
            'bearerFormat' => 'JWT'
        ]);

        $schemas = $openApi->getComponents()->getSchemas();
        $schemas['Credentials'] = new ArrayObject([
            'type' => 'object',
            'properties' => [
                'username' => [
                    'type' => 'string',
                    'example' => 'contact@obm.com',
                ],
                'password' => [
                    'type' => 'string',
                    'example' => '0000',
                ]
            ]
        ]);

        $schemas['RefreshToken'] = new ArrayObject([
            'type' => 'object',
            'properties' => [
                'refresh_token' => [
                    'type' => 'string',
                    'example' => 'Le token ici',
                ],
            ]
        ]);

        $schemas['PasswordReset'] = new ArrayObject([
            'type' => 'object',
            'properties' => [
                'email' => [
                    'type' => 'string',
                    'example' => 'contact@obm.com',
                ],
            ]
        ]);

        $loginPathItem = new PathItem(null, null, null, null, null,
            new Operation(
                'postApiLogin',
                ['Auth'],
                [
                    '200' => [
                        'description' => 'Utilisateur connecté',
                        /*'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/User-read.user'
                                ]
                            ],
                        ]*/
                    ],
                ],
                'Connecte les utilisateurs',
                '', null,
                [],
                new RequestBody(
                    '',
                    new ArrayObject([
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/Credentials'
                            ]
                        ]
                    ])
                )
            )
        );

        $logoutPathItem = new PathItem(null, null, null, null, null,
            new Operation(
                'postApiLogout',
                ['Auth'],
                [
                    '204' => [
                        'description' => 'Utilisateur déconnecté',
                    ],
                ],
                'Déconnecte les utilisateurs',
                '', null,
                [],
                null
            )
        );

        $refreshTokenPathItem = new PathItem(null, null, null, null, null,
            new Operation(
                'postApiRefreshToken',
                ['Auth'],
                [
                    '200' => [
                        'description' => 'Token rafraichit',
                    ],
                ],
                'Rafraichit le token',
                '', null,
                [],
                new RequestBody(
                    '',
                    new ArrayObject([
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/RefreshToken'
                            ]
                        ]
                    ])
                )
            )
        );

        $passwordResetPathItem = new PathItem(null, null, null, null, null,
            new Operation(
                'postApiPasswordReset',
                ['Auth'],
                [
                    '200' => [
                        'description' => 'Mot de passe reinitialise',
                    ],
                ],
                'Reinitialise le mot de passe',
                '', null,
                [],
                new RequestBody(
                    '',
                    new ArrayObject([
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/PasswordReset'
                            ]
                        ]
                    ])
                )
            )
        );

        $openApi->getPaths()->addPath('/api/login', $loginPathItem);
        $openApi->getPaths()->addPath('/api/logout', $logoutPathItem);
        $openApi->getPaths()->addPath('/api/token/refresh', $refreshTokenPathItem);
        $openApi->getPaths()->addPath('/api/password-reset', $passwordResetPathItem);

        return $openApi;
    }
}

