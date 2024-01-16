TODOS:
Middleware (all routes):
router->use(MIDDLEWAREFUNC);

Middleware (route specific):
router->get("PATH", MIDDLEWAREFUNC(HANDLERFUNC));

Daisy chaining:
$middlewares = new Middlewares(MIDDLEWAREFIRST, MIDDLEWARESECOND, MIDDLEWARETHIRD);
router->get("PATH", $middlewares(HANDLERFUNC));
