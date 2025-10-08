<?php
require_once __DIR__ . '/utils/boot.php';

// Nettoyage session + cookie de session (bonne pratique)
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params['path'], $params['domain'],
        $params['secure'], $params['httponly']
    );
}
session_destroy();

setFlash("Déconnexion réussie. À bientôt !", 'success');
redirect('login.php');
