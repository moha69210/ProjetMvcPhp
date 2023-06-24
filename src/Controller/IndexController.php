<?php

namespace App\Controller;

use PDO;

class IndexController extends AbstractController
{
  public function login()
  {
    return $this->twig->render('login.html.twig');
  }

  public function register($userName, $password)
  {
    var_dump($userName );
    // Exemple de requête utilisant la connexion à la base de données
    $query = "SELECT * FROM users where username = '$userName' and password = '$password";
    $statement = $this->pdo->query($query);
    $users = $statement->fetchAll(PDO::FETCH_ASSOC);
    
    var_dump($users);
    // // Utilisation de la connexion et du moteur de templating Twig
    // $content = $this->twig->render('users/index.twig', ['users' => $users]);

    // return $this->twig->render('login.html.twig');
  }

  public function logout()
  {
    return $this->twig->render('login.html.twig');
  }
}
