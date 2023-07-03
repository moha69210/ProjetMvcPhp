<?php

namespace App\Controller;

use App\Middleware\Authenticated;
use App\Model\User;
use PDO;

class IndexController extends AbstractController
{
  public function login()
  {
    return $this->twig->render('login.html.twig');
  }

  public function registerPage()
  {
    return $this->twig->render('register.html.twig');
  }

  public function logout()
  {
    return $this->twig->render('login.html.twig');
  }

  #[Authenticated("user")]
  public function testIsAuthWork()
  {
    return $this->twig->render('testAuth.html.twig');
  }

  public function register()
  {
    $userName = $_POST['_username'];
    $password = $_POST['_password'];
    $role = "users";

    // Hashage du mot de passe
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    /* Préparation de la requête */
    $query = "INSERT INTO users (username, password, role) VALUES (:username, :password, :role)";
    $statement = $this->pdo->prepare($query);

    $statement->bindParam(':username', $userName);
    $statement->bindParam(':password', $passwordHash);
    $statement->bindParam(':role', $role);

    $statement->execute();

    // Redirect to login page or wherever you want
    header('Location: /');
    exit();
  }

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

    if (!$user || !password_verify($password, $user->password)) {
      $error = 'Le mot de passe ou le pseudo indiqué est erronée';
      return $this->twig->render('login.html.twig', ['error' => $error]);
    } else {
      /* On stocke en session les infos du user connecté */
      unset($user->password);
      $_SESSION['user'] = $user;

      return $this->twig->render('index.html.twig', ['username' => $user->username]);
    }
  }
}
