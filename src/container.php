<?php
/*
 * This file is part of the proto package.
 *
 * Copyright (c) Jason Coward <jason@opengeek.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Interop\Container\ContainerInterface;

use function DI\object;
use function DI\get;

return [
    'settings.determineRouteBeforeAppMiddleware' => false,
    'settings.displayErrorDetails' => true,

    'uri' => function(ContainerInterface $c) {
        return $c->get('request')->getUri();
    },

    'config' => function(ContainerInterface $c) {
        $configuration = [
            'appName' => 'Proto'
        ];
        if (is_readable(__DIR__ . '/../config.php')) {
            $configuration = array_replace_recursive($configuration, require __DIR__ . '/../config.php');
        }

        return new \Slim\Collection($configuration);
    },

    \Slim\Views\Twig::class => function (ContainerInterface $c) {
        $twig = new \Slim\Views\Twig(__DIR__ . '/../templates', [
            'cache' => __DIR__ . '/../cache'
        ]);

        $twig->addExtension(new \Slim\Views\TwigExtension(
            $c->get('router'),
            $c->get('request')->getUri()
        ));

        return $twig;
    },

    \OpenGeek\Proto\Controller\View::class => object()
        ->constructor(get(\Slim\Views\Twig::class), get('uri'), get('config'))
];
