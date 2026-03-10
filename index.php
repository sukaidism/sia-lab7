<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/database.php';
use App\Controllers\CatsController;
use App\Models\CatModel;
// require __DIR__ . '/src/Controllers/CatsController.php';
// require __DIR__ . '/src/Models/Cat.php';


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$connection = getConnection();
$catModel = new CatModel($connection);
$catsController = new CatsController($catModel);

$router = new \Bramus\Router\Router();

$router->get('/', function() {
    echo "Welcome to the AUF SIA API Platform";
});

// For now, im not gonna use the shorthand method of defining routes
$router->get('/cats', [$catsController, 'index']);
$router->get('/cats/(\d+)', [$catsController, 'show']);
$router->post('/cats', [$catsController, 'store']);
$router->put('/cats/(\d+)', [$catsController, 'update']);
$router->delete('/cats/(\d+)', [$catsController, 'destroy']);

$router->run();
?>