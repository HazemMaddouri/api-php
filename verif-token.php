<?php
require_once "config.php";
$tokenError = false;
//echo $_SERVER['REQUEST_METHOD'];

if ($_SERVER['REQUEST_METHOD'] != 'GET') :
    $headers = getallheaders();
    $token = (isset($headers['Token'])) ? $headers['Token'] : null;
    //echo $token;
    if ( !isset($_SESSION['token']) or !$token ):
    $tokenError = true;
    else :
    $now = time();
        if ($_SESSION['expiration'] < $now):
        $tokenError = true;
        elseif ($_SESSION['token'] != $token):
        $tokenError = true;
        endif;
    endif;
endif;


    if ($tokenError) :
        $response['message'] = "Access denied";
        echo json_encode($response);
        http_response_code(403);
        die();
    else :
        $_SESSION['expiration'] = time() + 1 * 300;
    endif;