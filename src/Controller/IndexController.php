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

  #[Authorize("users")]
  #[Route("/home", name: "homepage", httpMethod: "GET")]
  public function home()
  {
    return $this->twig->render('index.html.twig', ['username' => $_SESSION['user']->username]);
  }

  #[Authorize("users")]
  #[Route('/registerPage', name: "registerPage", httpMethod: "GET")]
  public function registerPage()
  {
    return $this->twig->render('register.html.twig');
  }

  #[Authorize("users")]
  #[Route('/logout', name: "logout", httpMethod: "GET")]
  public function logout()
  {
    session_destroy();
    return $this->twig->render('login.html.twig');
  }

  // Seul les admin ont le droit d'accéder a cet page
  #[Authorize("admin")]
  #[Route(path: '/testIsAuthWork', name: 'testIsAuthWork', httpMethod: "GET")]
  public function testIsAuthWork()
  {
    return $this->twig->render('testAuth.html.twig',['username' => $_SESSION['user']->username]);
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

    $inscription = 'Votre inscription à bien été enregistré, vous pouvez désormais vous connecter';

    return $this->twig->render('login.html.twig', ['inscription' => $inscription ]);
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
      $error = 'Le mot de passe ou le pseudo indiqué est erronée';
      return $this->twig->render('login.html.twig', ['error' => $error]);

    } else {
      /* On stocke en session les infos du user connecté */
      unset($user->password);
      $_SESSION['user'] = $user;

      return $this->twig->render('index.html.twig', ['username' => $_SESSION['user']->username]);
    }
  }

}
