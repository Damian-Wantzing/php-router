TODOS:
Middleware (all routes):
router->use(MIDDLEWAREFUNC);

Daisy chaining:
$middlewares = new Middlewares(MIDDLEWAREFIRST, MIDDLEWARESECOND, MIDDLEWARETHIRD);
router->get("PATH", $middlewares(HANDLERFUNC));
