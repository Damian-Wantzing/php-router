TODOS:
create router structure:
router->get("PATH/", HANDLERFUNC);
router->post("PATH/", HANDLERFUNC);
router->put("PATH/", HANDLERFUNC);
router->delete("PATH/", HANDLERFUNC);

HANDLERFUNC:
request as a pointer if middleware wants to add anything to the params
function (HTTPREQUEST &$request, HTTPRESPONSE $response)
{
    $id = request.params("id");
}

Actually handling incoming requests:
index.php($router->handle())->router->handlerfunc->response (as returned from the HANDLERFUNC)

allow url params e.g.
router->get("/user/{:id}", HANDLERFUNC)

Middleware (all routes):
router->use(MIDDLEWAREFUNC);

Middleware (route specific):
router->get("PATH", MIDDLEWAREFUNC(HANDLERFUNC));

Daisy chaining:
$middlewares = new Middlewares(MIDDLEWAREFIRST, MIDDLEWARESECOND, MIDDLEWARETHIRD);
router->get("PATH", $middlewares(HANDLERFUNC));
