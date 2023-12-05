<?php
require_once "config.php";

    if( isset($_GET['deconnexion']) ):
      //session_destroy();
      unset($_SESSION['expiration']);
      unset($_SESSION['token']);
      unset($_SESSION['id_user']);
      echo json_encode("Déconnexion");
      die();
    endif;

    if($_SERVER['REQUEST_METHOD'] == "POST") :
      $data = json_decode(file_get_contents('php://input'), true);
      $sql = "SELECT id, login FROM users WHERE login = :login AND password = :password";
      $rq = $connect->prepare($sql);
      $rq->execute([
        "login" => $data['login'],
        "password" => $data['password']
      ]);
      $nb_users = $rq->rowCount();
      if($nb_users > 0):
        $user = $rq->fetchObject();
        $_SESSION['id_user'] = $user->id;
        $_SESSION['token'] = md5(date("DMYHis"));
        $_SESSION['expiration'] = time() + 1 * 300;
        $response['token'] = $_SESSION['token'];
        $response['message'] = "User {$user->login} connecté";
        $response['user'] = $user->login;
        echo json_encode($response);
        die();
      else:
        echo json_encode("Error log/pass");
        http_response_code(403);
      endif;
      //echo json_encode($rq->rowCount());
    else :
      echo json_encode("Methode non autorisée");
      http_response_code(400);
    endif;