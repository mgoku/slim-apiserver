<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);

/****************************************************************/
/*  Middleware untuk cek apakah user yang login adalah type ADMIN
/****************************************************************/
$checkAdmin = function ($request, $response, $next) {

    $token = $request->getAttribute("token");

    if ($token->type === 'ADMIN') {
        return $next($request, $response);
    } else {
        return $response
            ->withHeader('Content-type','application/json')
            ->withStatus(401)
            ->write(json_encode(array(
              "msg" => "Unauthorized access"
            ), JSON_NUMERIC_CHECK));
    }
};
