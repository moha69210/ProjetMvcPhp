<?php

namespace App\Controller;

use PDO;

class IndexController extends AbstractController
{
  public function login()
  {
    return $this->twig->render('login.html.twig');
  }

  public function register()
  {
    $userName = $_POST['_username'];
    $password = $_POST['_password'];

    /* Préparation de la requête */
    $query = "SELECT * FROM users WHERE username = :username AND password = :password";
    $statement = $this->pdo->prepare($query);

    $statement->bindParam(':username', $userName);
    $statement->bindParam(':password', $password);

    $statement->execute();

    // Fetch the results
    $users = $statement->fetch(PDO::FETCH_OBJ);

    if (empty($users)) {
      $error = 'Le mot de passe ou le pseudo indiqué est erronée';
      return $this->twig->render('login.html.twig', ['error' => $error]);
    } else {
      /* On stocke en session les infos du users connecté */
      $_SESSION['user'] = $users;

      return $this->twig->render('index.html.twig', ['username' => $users->username]);
    }

    // $content = $this->twig->render('users/index.twig', ['users' => $users]);

    // return $this->twig->render('login.html.twig');
  }

  public function logout()
  {
    return $this->twig->render('login.html.twig');
  }
}
