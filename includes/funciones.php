<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}
function pagina_actual($path){

    return str_contains($_SERVER['PATH_INFO'],$path)? true : false;
}

function isAuth(): bool{
    session_start();

    if(isset($_SESSION['nombre']) && !empty($_SESSION)){
        return true;
    }
    return false;
}

function isAdmin():bool{
    session_start();
    if(isset($_SESSION['admin']) && !empty($_SESSION['admin'])){
        return true;
    }
    return false;
}