<?php
require_once __DIR__ . '/utils/boot.php';
require_once __DIR__ . '/utils/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom =trim( $_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $res = registerUser($nom, $email, $password);

    if ($res['ok']) {
        setFlash("Inscription réussie ! Vous pouvez maintenant vous connecter.", 'success');
        redirect('login.php');
    } else {
        setFlash("Une erreur est survenue lors de l'inscription.", 'danger');
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Inscription</h1>
        <?php if ($m = getFlash('success')): ?>
             <p class="flash success"><?= htmlspecialchars($m) ?></p>
        <?php endif; ?>

        <?php if ($m = getFlash('error')): ?>
              <p class="flash error"><?= htmlspecialchars($m) ?></p>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" class="form-control" required value="<?= htmlspecialchars($nom ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control"  required value="<?= htmlspecialchars($email ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">S'inscrire</button>
        </form>
    </div>
</body>
</html>