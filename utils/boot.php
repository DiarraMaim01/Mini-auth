<?php 
session_start();
 
// enregistrer un message dans la session
function setFlash(string $message, String $type = 'info') : void {
    $_SESSION['flash'][$type] = $message;
    
}

// recuperer un message flash pour le supprimer
function getFlash(string $type = 'info') : ?string {
    $message = $_SESSION['flash'][$type] ?? null;
    unset($_SESSION['flash'][$type]);
    return $message;
}

// rediriger vers une autre page
function redirect(string $url) : void {
    header("Location: $url");
    exit();
}


?>