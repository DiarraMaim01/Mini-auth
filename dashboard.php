<?php
require_once __DIR__ . '/utils/boot.php';

// Protection d'accès
if (empty($_SESSION['user'])) {
    setFlash("Veuillez vous connecter pour accéder au tableau de bord.", 'error');
    redirect('login.php');
    exit;
}

$user = $_SESSION['user'];
$nomSafe   = htmlspecialchars($user['nom']   ?? '');
$emailSafe = htmlspecialchars($user['email'] ?? '');

// Messages flash éventuels (success après login)
$flashSuccess = getFlash('success');
$flashError   = getFlash('error');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Dashboard</title>
  <style>
    body { font-family: system-ui, Arial, sans-serif; max-width: 720px; margin: 40px auto; }
    .flash { padding: 10px 14px; border-radius: 6px; margin-bottom: 12px; }
    .success { background: #e7f7ed; color: #116d32; border: 1px solid #bfe6c9; }
    .error   { background: #fde8e8; color: #8b1c1c; border: 1px solid #f7c6c5; }
    .card { border: 1px solid #e5e7eb; border-radius: 10px; padding: 16px 18px; background: #fff; }
    .row { display: flex; gap: 10px; align-items: center; }
    form { display: inline; }
    button { padding: 8px 12px; border: 0; background: #ef4444; color: #fff; border-radius: 6px; cursor: pointer; }
    button:hover { background: #dc2626; }
  </style>
</head>
<body>
  <h1>Tableau de bord</h1>

  <?php if ($flashSuccess): ?>
    <p class="flash success"><?= htmlspecialchars($flashSuccess) ?></p>
  <?php endif; ?>
  <?php if ($flashError): ?>
    <p class="flash error"><?= htmlspecialchars($flashError) ?></p>
  <?php endif; ?>

  <div class="card">
    <h2>Bonjour <?= $nomSafe !== '' ? $nomSafe : $emailSafe ?> 👋</h2>
    <p>Vous êtes connecté(e) avec l’adresse <strong><?= $emailSafe ?></strong>.</p>

    <div class="row" style="margin-top:12px;">
      <form method="POST" action="logout.php">
        <button type="submit">Se déconnecter</button>
      </form>
    </div>
  </div>
</body>
</html>
