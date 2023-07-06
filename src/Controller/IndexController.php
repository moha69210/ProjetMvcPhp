<?php

namespace App\Controller;

use App\Model\User;
use App\Routing\Attribute\Authorize;
use PDO;
use App\Routing\Attribute\Route;

class IndexController extends AbstractController
{

  #[Route("/", name: "loginPage", httpMethod: "GET")]
  public function login()
  {
    return $this->twig->render('login.html.twig');
  }

  #[Route("/home", name: "homepage", httpMethod: "GET")]
  public function home()
  {
    return $this->twig->render('index.html.twig');
  }

  #[Route('/registerPage', name: "registerPage", httpMethod: "GET")]
  public function registerPage()
  {
    return $this->twig->render('register.html.twig');
  }

  #[Route('/logout', name: "logout", httpMethod: "GET")]
  public function logout()
  {
    session_destroy();
    return $this->twig->render('login.html.twig');
  }

  #[Authorize("users")]
  #[Route(path: '/testIsAuthWork', name: 'testIsAuthWork', httpMethod: "GET")]
  public function testIsAuthWork()
  {
    return $this->twig->render('testAuth.html.twig');
  }

  #[Route(path: '/register', name: 'register', httpMethod: "POST")]
  public function register()
  {
    $userName = $_POST['_username'];
    $password = $_POST['_password'];
    $role = User::userRole;

    // Hashage du mot de passe
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    /* Préparation de la requête */
    $query = "INSERT INTO users (username, password, role) VALUES (:username, :password, :role)";
    $statement = $this->pdo->prepare($query);

    $statement->bindParam(':username', $userName);
    $statement->bindParam(':password', $passwordHash);
    $statement->bindParam(':role', $role);

    $statement->execute();

    return $this->twig->render('login.html.twig');
    exit();
  }

  #[Route(path: '/signIn', name: 'signIn', httpMethod: "POST")]
  public function signIn()
  {
    $userName = $_POST['_username'];
    $password = $_POST['_password'];

    /* Préparation de la requête */
    $query = "SELECT * FROM users WHERE username = :username";
    $statement = $this->pdo->prepare($query);

    $statement->bindParam(':username', $userName);

    $statement->execute();

    // Fetch the results
    $user = $statement->fetch(PDO::FETCH_OBJ);

    if (empty($user) || !password_verify($password, $user->password)) {
      $this->redirectToLogin();
    } else {
      /* On stocke en session les infos du user connecté */
      unset($user->password);
      $_SESSION['user'] = $user;

      return $this->twig->render('index.html.twig', ['username' => $user->username]);
    }
  }

  public function redirectToLogin()
  {
    $error = 'Le mot de passe ou le pseudo indiqué est erronée';
    return $this->twig->render('login.html.twig', ['error' => $error]);
  }
}
