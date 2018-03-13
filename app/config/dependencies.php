<?php

use Respect\Validation\Validator as V;

// DIC configuration
$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// Service factory for the ORM
$container['db'] = function ($c) {
    $capsule = new \Illuminate\Database\Capsule\Manager;
    $capsule->addConnection($c->get('settings')['db']);

    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
};

// Validator
$container['validator'] = function ($c) {
    return new App\Helper\Validation\Validator;
};

// Image Manager
$container['image_manager'] = function($c) {
    return new Intervention\Image\ImageManager(array('driver' => $c->get('settings')['images_manager']['image_driver']));
};

// Image Helper
$container['image_helper'] = function ($c) {
    return new App\Helper\Image($c->get('image_manager'), $c->get('settings')['images_manager'], $c->get('logger'));
};

$container[App\Controller\UserController::class] = function ($c) {
    return new App\Controller\UserController($c->get('db'), $c->get('renderer'), $c->get('image_helper'), $c->get('validator'), $c->get('logger'));
};

// adding custom rules for validator
V::with('App\\Helper\\Validation\\Rules');
