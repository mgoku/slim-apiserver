<?php
// Routes









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
/*****************************************************************************************/
$app->get('/adduser', function ($request, $response, $args) {
    $user = $request->getQueryParams();
    $username = trim($user["username"]);
    $password = trim($user["password"]);
    $type = trim($user["type"]);

    if ((!empty($username)) && (!empty($password)))
    {
        $this->database->insert("users", ["username" => $username, "password" => password_hash($password, PASSWORD_DEFAULT), "type" => $type, "created_at" => date('c'), "updated_at" => time()]);
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
