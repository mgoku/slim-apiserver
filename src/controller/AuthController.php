<?php

namespace mgoku\apiserver\controller;

class AuthController
{

    protected $ci;

    /* Init class dan inject container instance */
    public function __construct ($ci) {
        $this->ci = $ci;
    }

    public function login($request, $response, $args)
    {
        $user = $request->getParsedBody();

        /* Sanitasi data hanya dengan ditrim. Butuh yang lebih canggih lagi kalau ada */
        $username = trim($user["username"]);
        $password = trim($user["password"]);

        if ((!empty($username)) && (!empty($password))) {

            $loggedin_user = $this->ci->database->select("users", ["username", "email", "password", "type"], ["OR" => ["username" => $username, "email" => $username]]);

            if ($loggedin_user && password_verify($password, $loggedin_user[0]["password"])) {

                /* Inisialisasi generator token dengan key admin */
                $getToken = $this->ci->jwtadmin;

                /* Inisialisasi generator token dengan key mobile */
                // $getToken = $this->jwtmobile;

                return $response
                ->withHeader('Content-type','application/json')
                ->withStatus(200)
                ->write(json_encode(
                    [
                        "token" => $getToken($username, $loggedin_user[0]["type"]), /* Ini bikin token, dg param username dan type */
                        "username" => $loggedin_user[0]["username"],
                        "email" => $loggedin_user[0]["email"],
                        "type" => $loggedin_user[0]["type"]  /* Kirimkan juga sebagai response, siapa tahu dibutuhkan di client side */
                    ]
                ));
            } else {
                return $response
                ->withHeader('Content-type','application/json')
                ->withStatus(403)
                ->write(json_encode("Unauthorized"));
            }
        } else {
            return $response
                ->withHeader('Content-type','application/json')
                ->withStatus(403)
                ->write(json_encode("Unauthorized"));
        }
    }

}