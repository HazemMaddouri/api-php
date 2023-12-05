<?php
include "headers.php";

define("DB_HOST", "localhost");
define("DB_NAME", "ingrwf11");
define("DB_USER", "root");
define("DB_PASS", "root");
define("DEBUG", true);

try {
    $connect = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8',
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    $connect->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (Exception $e) {
    echo json_encode("Api non disponible");
    http_response_code(500);
    die();
    //die('Erreur : ' . $e -> getMessage());
}

$routes_valides = ["pays", "contacts", "cities"];

include "function.php";

session_start();

