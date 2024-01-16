TODOS:
HANDLERFUNC:
request as a pointer if middleware wants to add anything to the params
function (HTTPREQUEST &$request, HTTPRESPONSE $response)
{
    $id = request.params("id");
}

Actually handling incoming requests:
index.php($router->handle())->router->handlerfunc->response (as returned from the HANDLERFUNC)

Middleware (all routes):
router->use(MIDDLEWAREFUNC);

Middleware (route specific):
router->get("PATH", MIDDLEWAREFUNC(HANDLERFUNC));

Daisy chaining:
$middlewares = new Middlewares(MIDDLEWAREFIRST, MIDDLEWARESECOND, MIDDLEWARETHIRD);
router->get("PATH", $middlewares(HANDLERFUNC));
