<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Initialisation de certaines choses
use App\Controller\ContactController;
use App\Controller\IndexController;
use App\Routing\RouteNotFoundException;
use App\Routing\Router;
use Symfony\Component\Dotenv\Dotenv;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__ . '/../.env');

// Twig
$loader = new FilesystemLoader(__DIR__ . '/../templates/');
$twig = new Environment($loader, [
  'debug' => $_ENV['APP_ENV'] === 'dev',
  'cache' => __DIR__ . '/../var/twig/',
]);

// Appeler un routeur pour lui transférer la requête
$router = new Router($twig);

/* Page Login */
$router->addRoute(
  'homepage',
  '/',
  'GET',
  IndexController::class,
  'login'
);

/* Register du form login */
$router->addRoute(
  'registerPage',
  '/registerPage',
  'GET',
  IndexController::class,
  'registerPage'
);

/* Register du form login */
$router->addRoute(
  'register',
  '/register',
  'POST',
  IndexController::class,
  'register'
);

/* SignIn page Login */
$router->addRoute(
  'signIn',
  '/signIn',
  'POST',
  IndexController::class,
  'signIn'
);

/* Register du form testAuth */
$router->addRoute(
  'testIsAuthWork',
  '/testIsAuthWork',
  'GET',
  IndexController::class,
  'testIsAuthWork'
);

/* Register du form testAuth */
$router->addRoute(
  'logout',
  '/logout',
  'GET',
  IndexController::class,
  'logout'
);

// /* Page D'accueil */
// $router->addRoute(
//   'homepage',
//   '/home',
//   'POST',
//   IndexController::class,
//   'home'
// );

// /* Page Contact */
// $router->addRoute(
//   'homepage',
//   '/',
//   'GET',
//   IndexController::class,
//   'home'
// );

// /* Page Gestion */
// $router->addRoute(
//   'homepage',
//   '/',
//   'GET',
//   IndexController::class,
//   'home'
// );

try {
  $router->execute($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
} catch (RouteNotFoundException $ex) {
  http_response_code(404);
  echo "Page not found";
}
