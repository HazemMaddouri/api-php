<?php
require_once "config.php";
require "verif-token.php";

//myPrint_r($_GET);

$route = (isset($_GET['route'])) ? $_GET['route'] : "";
$response = [];
if ($route == ""):
    $response['message'] = "documentation";
    $response['contenu']['routes'] = [
        "/cities" => "GET",
        "/cities/:id" => "GET, POST, PATCH, DELETE",
        "/contacts" => "GET, POST",
        "/contacts/:id" => "GET, PATCH, DELETE",
        "/pays" => "GET, POST",
        "/pays/:id" => "GET, PATCH, DELETE",
        "/auth" => "POST",
    ];
    echo json_encode($response);
    die();
endif;

if (!in_array($route, $routes_valides)):
    $response['message'] = "route non valide";
    http_response_code(404);
    echo json_encode($response);
    die();
endif;

switch ($_SERVER['REQUEST_METHOD']):
case "GET":
    if (isset($_GET['id'])):
        $sql = "SELECT * FROM $route WHERE id = :id";
        $rq = $connect->prepare($sql);
        $rq->execute([
            "id" => $_GET['id'],
        ]);
    else:
        $sql = "SELECT * FROM $route";
        $rq = $connect->prepare($sql);
        $rq->execute();
    endif;

    $rows = $rq->fetchAll();
    break;

case "DELETE":
    if (isset($_GET['id'])):
        $sql = "DELETE FROM $route WHERE id = :id";
        $rq = $connect->prepare($sql);
        $rq->execute([
            "id" => $_GET['id'],
        ]);
        echo json_encode("{$route} avec {$_GET['id']} supprimé");
        die();
    else:
        echo json_encode("Il manque un id");
        http_response_code(503);
        die();
    endif;
    break;
case "POST":
    $data = json_decode(file_get_contents('php://input'), true);
    $sql = "INSERT INTO $route SET ";
    $args = [];
    foreach ($data as $field => $value):
        $sql .= "$field = :$field,";
        $args[$field] = $value;
    endforeach;
    $sql = substr($sql, 0, -1);
    $rq = $connect->prepare($sql);
    $rq->execute($args);
    echo json_encode("{$connect->lastInsertID()} Inséré");
    die();
    break;
case "PATCH":
    if (isset($_GET['id'])):
        $data = json_decode(file_get_contents('php://input'), true);
        $sql = "UPDATE $route SET ";
        $args = [];
        foreach ($data as $field => $value):
            $sql .= "$field = :$field,";
            $args[$field] = $value;
        endforeach;
        $sql = substr($sql, 0, -1);
        //echo json_encode($sql);
        $rq = $connect->prepare($sql);
        $sql .= " WHERE 'id' => :id";
        $rq->execute($args);
        if ($rq->rowCount() > 0):
            echo json_encode("{$_GET['id']} a bien été update");
        else:
            echo json_encode("Cet ID n'existe pas ou aucun changement n'a été fait");
        endif;
        die();
    endif;
    break;
default:
    echo json_encode("Méthode non autorisée");
    http_response_code(403);
    die();
    endswitch;
    echo json_encode($rows);
