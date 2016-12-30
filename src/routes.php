<?php
// Routes

/* Contoh route untuk login, dan mendapatkan token JWT yang dibuat menggunakan key admin */
/* Parameter yang harus dikirim adalah username dan password */
/* Jika login berhasil, akan memberi response dengan status 200 dan data token JWT */
/* Jika login gagal, akan memberi response dengan status 403 */
$app->post('/login', "AuthController:login");


/*
    Route berikut, hanya bisa diakses jika sudah login dan
    mendapat token JWT yang digenerate dengan key admin.

    Settingan ini diatur di file src/middleware.php
*/
$app->group('/admin', function () use ($app, $checkAdmin) {


    /* Route ini bisa diakses oleh user yang sudah login, meskipun type dia bukan ADMIN */
    $app->get('/user', function ($request, $response, $args) {

        $params = $request->getQueryParams();
        $filter = [];

        if ((isset($params["id"])) && (strlen(trim($params["id"])) > 0)) {
            $filter = ["id" => trim($params["id"])];
        }

        $result = $this->database->select("users", ["id", "username", "type", "data", "created_at", "updated_at"], $filter);

        return $response
            ->withHeader('Content-type','application/json')
            ->withStatus(200)
            ->write(json_encode(array("data" => $result), JSON_NUMERIC_CHECK));
    });


    /* Route ini hanya bisa diakses oleh user yang sudah login dan type ADMIN */
    $app->delete('/user', function ($request, $response, $args) {

        $data = $request->getParsedBody();

        if ((isset($data["id"])) && (strlen(trim($data["id"])) > 0)) {
            /* Hanya boleh hapus jika kirim id */
            $result = $this->database->delete("users", ["id" => trim($data["id"])]);

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

    })->add($checkAdmin);

});

/*
    Route berikut, hanya bisa diakses jika sudah login dan mendapat
    token JWT yang digenerate dengan key mobile.

    Settingan ini diatur di file src/middleware.php
*/
$app->group('/mobile', function () use ($app, $checkAdmin) {


    /* Route ini bisa diakses oleh user yang sudah login, meskipun type dia bukan ADMIN */
    $app->get('/user', function ($request, $response, $args) {

        $params = $request->getQueryParams();
        $filter = [];

        if ((isset($params["id"])) && (strlen(trim($params["id"])) > 0)) {
            $filter = ["id" => trim($params["id"])];
        }

        $result = $this->database->select("users", ["id", "username", "type", "data", "created_at", "updated_at"], $filter);

        return $response
            ->withHeader('Content-type','application/json')
            ->withStatus(200)
            ->write(json_encode(array("data" => $result), JSON_NUMERIC_CHECK));
    });

});




/*****************************************************************************************/
/*    Route - route berikut digunakan sebagai tool development.
/*    Enable dan disable mana yang perlu dan mana yang tidak perlu.
/*****************************************************************************************/

/*    Just say OK      */
$app->get('/', function ($request, $response, $args) {
    return $response
            ->withHeader('Content-type','application/json')
            ->withStatus(200)
            ->write(json_encode("Ok"));
});

/*    Check setting Timezone aplikasi     */
$app->get('/tz', function ($request, $response, $args) {
    return $response
            ->withHeader('Content-type','application/json')
            ->withStatus(200)
            ->write(date_default_timezone_get());
});

/*****************************************************************************************/
/*    Route untuk seed data user.
/*    PASTIKAN DISABLE ROUTE INI ini di production server
/*    Parameter yang harus dikirimkan : username, email, password, type ---> semua text
/*****************************************************************************************/
$app->get('/adduser', function ($request, $response, $args) {
    $user = $request->getQueryParams();
    $username = trim($user["username"]);
    $email = trim($user["email"]);
    $password = trim($user["password"]);
    $type = trim($user["type"]);

    if ((!empty($username)) && (!empty($password)))
    {
        $this->database->insert("users", ["username" => $username, "email" => $email, "password" => password_hash($password, PASSWORD_DEFAULT), "type" => $type, "created_at" => date('c'), "updated_at" => time(), "updated_by" => 0]);
    }
});

/*****************************************************************************************/
/*    Default route dari slim.
/*    Sebaiknya disable route ini di production, agar tidak ketahuan pakai slim.
/*****************************************************************************************/
$app->get('/info-slim', function ($request, $response, $args) {
    $this->logger->info("Slim-Skeleton '/' route");
    return $this->renderer->render($response, 'index.phtml', $args);
});
