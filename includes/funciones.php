<?php

function debuguear($variable){
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

//escapa y saniza el html
function s($html): string {
    $s = htmlspecialchars($html);
    return $s;
}

//funcion que revisa que el usuario este autenticado
function inicioSesion() : void {
    if (!isset($_SESSION['login'])) {
        header('Location: /');
    }
}