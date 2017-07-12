<?php
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::plugin(
    'UserAuthentication',
    ['path' => '/user-authentication'],
    function (RouteBuilder $routes) {
        $routes->fallbacks(DashedRoute::class);
    }
);

Router::connect('/login', ['plugin' => 'UserAuthentication', 'controller' => 'Users', 'action' => 'login']);