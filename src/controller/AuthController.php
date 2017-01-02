<?php

namespace mgoku\apiserver\controller;

class AuthController extends BaseController
{

    /***************************************************************************************
    ** Login user
    ** Data yang harus dikirim : username (bisa berisi usernane atau email), password
    ** Jika sukses, status : 200 + kirim token
    ** Jika gagal, status : 403
    ****************************************************************************************/
    public function login($request, $response, $args)
    {
        $user = $request->getParsedBody();

        /* Sanitasi data hanya dengan ditrim. Butuh yang lebih canggih lagi kalau ada */
        $username = trim($user["username"]);
        $password = trim($user["password"]);

        $valid_username = preg_match("/^[\w\d]+$/i", $username) || filter_var($username, FILTER_VALIDATE_EMAIL);

        if ($valid_username && preg_match("/^[\w\d]+$/i", $password)) {

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