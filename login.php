<?php
require_once __DIR__ . '/utils/boot.php';
require_once __DIR__ . '/utils/db.php';

require_once __DIR__ . '/utils/functions.php';

// Prépareration des variables d'affichage (pour garder l'email tapé en cas d'erreur)
$email  = '';
$errors = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        $errors = "Il faut remplir tous les champs.";
    } else {
        // Récupération utilisateur
        $user = findUserByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            // Connexion réussie : on garde en session les infos utiles
            $_SESSION['user'] = [
                'email' => $user['email'],
                'nom'   => $user['nom'] ?? ''
            ];
            setFlash("Connexion réussie. Bonjour " . ($user['nom'] ?? '👋') . " !", 'success');
            redirect('dashboard.php');
        } else {
            $errors = "Identifiants invalides.";
        }
    }
}

// Récupération d'éventuels messages flash (après logout par ex.)
$flashSuccess = getFlash('success');
$flashError   = getFlash('error');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Connexion</title>
  <style>
    body { font-family: system-ui, Arial, sans-serif; max-width: 520px; margin: 40px auto; }
    .flash { padding: 10px 14px; border-radius: 6px; margin-bottom: 12px; }
    .success { background: #e7f7ed; color: #116d32; border: 1px solid #bfe6c9; }
    .error   { background: #fde8e8; color: #8b1c1c; border: 1px solid #f7c6c5; }
    form { display: grid; gap: 10px; }
    label { font-weight: 600; }
    input[type="email"], input[type="password"] {
      padding: 8px; border: 1px solid #ccc; border-radius: 6px; width: 100%;
    }
    button { padding: 10px 14px; border: 0; background: #1976d2; color: #fff; border-radius: 6px; cursor: pointer; }
    button:hover { background: #125ca7; }
  </style>
</head>
<body>
  <h1>Connexion</h1>

  <?php if ($flashSuccess): ?>
    <p class="flash success"><?= htmlspecialchars($flashSuccess) ?></p>
  <?php endif; ?>

  <?php if ($flashError): ?>
    <p class="flash error"><?= htmlspecialchars($flashError) ?></p>
  <?php endif; ?>

  <?php if ($errors): ?>
    <p class="flash error"><?= htmlspecialchars($errors) ?></p>
  <?php endif; ?>

  <form method="POST" action="">
    <div>
      <label for="email">Email</label>
      <input type="email" id="email" name="email" required value="<?= htmlspecialchars($email) ?>" autocomplete="email">
    </div>

    <div>
      <label for="password">Mot de passe</label>
      <input type="password" id="password" name="password" required autocomplete="current-password">
    </div>

    <button type="submit">Se connecter</button>
  </form>
</body>
</html>
