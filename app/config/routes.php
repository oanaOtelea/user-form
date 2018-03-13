<?php

// use Slim\Http\Request;
// use Slim\Http\Response;

// Routes
$app->group('/', function () {
    $this->get('', 'App\Controller\UserController:index')->setName('homepage');
    $this->post('', 'App\Controller\UserController:register')->setName('registration');
});