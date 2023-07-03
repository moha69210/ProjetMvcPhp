<?php

namespace App\Routing;

use App\Middleware\Authenticated;
use Twig\Environment;
use ReflectionMethod;

class Router
{
  public function __construct(
    private Environment $twig
  ) {
  }

  private array $routes = [];

  public function addRoute(
    string $name,
    string $url,
    string $httpMethod,
    string $controllerClass,
    string $controllerMethod
  ) {
    $this->routes[] = [
      'name' => $name,
      'url' => $url,
      'http_method' => $httpMethod,
      'controller' => $controllerClass,
      'method' => $controllerMethod
    ];
  }

  public function getRoute(string $uri, string $httpMethod): ?array
  {
    foreach ($this->routes as $route) {
      if ($route['url'] === $uri && $route['http_method'] === $httpMethod) {
        return $route;
      }
    }

    return null;
  }

  /**
   * @param string $requestUri
   * @param string $httpMethod
   * @return void
   * @throws RouteNotFoundException
   */
  public function execute(string $requestUri, string $httpMethod)
  {
    session_start();

    $route = $this->getRoute($requestUri, $httpMethod);

    if ($route === null) {
      throw new RouteNotFoundException($requestUri, $httpMethod);
    }

    $controller = $route['controller'];
    $method = $route['method'];

    $controllerInstance = new $controller($this->twig);

    // Création d'un objet de réflexion pour la méthode du contrôleur
    $reflection = new ReflectionMethod($controller, $method);

    // Récupération des attributs Authenticated sur la méthode du contrôleur
    $attributes = $reflection->getAttributes(Authenticated::class);

    if (!empty($attributes)) {
      // $authAttribute = $attributes[0]->newInstance();

      // Si un attribut Authenticated est présent et que l'utilisateur n'est pas authentifié, redirection vers la page de connexion
      // if (!isset($_SESSION['user']) || $_SESSION['user']->role !== $authAttribute->getRole()) {
      if (!isset($_SESSION['user'])) {
        header('Location: /');
        exit();
      }
    }

    echo $controllerInstance->$method();
  }

  public function registerRoutes(): void
  {
    // Explorer le dossier des classes de contrôleurs
    // Pour chacun d'eux, enregistrer les méthodes
    // donc les contrôleurs portant un attribut Route
    $classNames = Filesystem::getClassNames(self::CONTROLLERS_GLOB_PATH);

    foreach ($classNames as $class) {
      $fqcn = "App\\Controller\\" . $class;
      $classInfos = new ReflectionClass($fqcn);

      if ($classInfos->isAbstract()) {
        continue;
      }

      $methods = $classInfos->getMethods(ReflectionMethod::IS_PUBLIC);

      foreach ($methods as $method) {
        if ($method->isConstructor()) {
          continue;
        }

        $attributes = $method->getAttributes(Route::class);

        if (!empty($attributes)) {
          $routeAttribute = $attributes[0];
          /** @var Route */
          $routeInstance = $routeAttribute->newInstance();
          $this->addRoute(
            $routeInstance->getName(),
            $routeInstance->getPath(),
            $routeInstance->getHttpMethod(),
            $fqcn,
            $method->getName()
          );
        }
      }
    }
  }
}
