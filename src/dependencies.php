<?php

/* Load Firebase JWT */
use \Firebase\JWT\JWT;

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

// medoo
$container['database'] = function ($c) {
    $settings = $c->get('settings')['medoo'];
    return new medoo($settings);
};

// JWT Token generator with admin key
$container['jwtadmin'] = function ($c) {

    return function ($username, $type) use ($c) {
        $settings = $c->get('settings')['jwt'];

        $data = array(
            "iss" => "eguru-server",
            "aud" => "eguru-admin",
            "exp" => time() + 14400,
            "uid" => substr( md5(rand()), 0, 10),
            "username" => $username,
            "type" => $type
        );

        $token = JWT::encode($data, $settings['admin']);
        return $token;
    };
};

// JWT Token generator with mobile key
$container['jwtmobile'] = function ($c) {

    $settings = $c->get('settings')['jwt'];
    $data = array(
        "iss" => "eguru-server",
        "aud" => "eguru-mobile",
        "exp" => time() + 31536000,
        "uid" => substr( md5(rand()), 0, 10),
        "type" => "IS_MOBILE"
    );

    $token = JWT::encode($data, $settings['mobile']);
    return $token;
};


/* Buat DIC untuk controller */
$container[AuthController] = function ($c) {
    return new mgoku\apiserver\controller\AuthController($c);
};
