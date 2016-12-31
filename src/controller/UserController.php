<?php

namespace mgoku\apiserver\controller;

class UserController extends BaseController
{

    /* Buat user baru */
    public function add($request, $response, $args)
    {
        $user = $request->getQueryParams();
        $username = trim($user["username"]);
        $email = trim($user["email"]);
        $password = trim($user["password"]);
        $type = trim($user["type"]);

        if ( preg_match("/^[\w\d]+$/i", $username) && preg_match("/^[\w\d]+$/i", $password) && filter_var($email, FILTER_VALIDATE_EMAIL) )
        {
            $this->ci->database->insert("users", ["username" => $username, "email" => $email, "password" => password_hash($password, PASSWORD_DEFAULT), "type" => $type, "created_at" => date('c'), "updated_at" => time(), "updated_by" => 0]);
        }
    }

    /* Ambil data user */
    /* Jika kirim parameter id, maka hanya akan ambil data user dengan id tersebut */
    public function get($request, $response, $args)
    {
        $params = $request->getQueryParams();
        $filter = [];

        if ((isset($params["id"])) && (strlen(trim($params["id"])) > 0)) {
            $filter = ["id" => trim($params["id"])];
        }

        $result = $this->ci->database->select("users", ["id", "username", "type", "data", "created_at", "updated_at"], $filter);

        return $response
            ->withHeader('Content-type','application/json')
            ->withStatus(200)
            ->write(json_encode(array("data" => $result), JSON_NUMERIC_CHECK));
    }

    /* Delete user */
    public function delete($request, $response, $args)
    {

        /*
            Spesifikasi method DELETE tidak menyebutkan soal body, hanya saja, tes menggunakan postman
            menunjukkan bahwa body selalu berisi null. Aku tidak tahu apakah postman yang tidak mengirimkan
            body, ataukah Slim yang ignore body untuk request DELETE.

            Yang jelas jalan dan aman, data dikirim via params, dan bukan via body
        */


        // Ambil data id via body, tapi isinya null
        // $data = $request->getParsedBody();

        // Ambil data id via params, ini yang jalan
        $data = $request->getQueryParams();

        if ((isset($data["id"])) && (strlen(trim($data["id"])) > 0)) {
            /* Hanya boleh hapus jika kirim id */
            $result = $this->ci->database->delete("users", ["id" => trim($data["id"])]);

            if ($result) {
                $status = 200;
            } else {
                $status = 404;
            }
        } else {
            /* Jika tidak kirim id, tidak boleh hapus */
            $status = 400;
        }

        return $response
            ->withHeader('Content-type','application/json')
            ->withStatus($status)
            ->write(json_encode($data));
    }

}