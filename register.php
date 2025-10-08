<?php
require_once __DIR__ . '/utils/boot.php';
require_once __DIR__ . '/utils/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    try {
        registerUser($nom, $email, $password);
        setFlash("Inscription réussie ! Vous pouvez maintenant vous connecter.", 'success');
        redirect('login.php');
    } catch (Exception $e) {
        setFlash($e->getMessage(), 'error');
        redirect('register.php');
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <style>
       body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f5f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            padding: 32px 40px;
            width: 380px;
        }

        h1 {
            text-align: center;
            color: #1976d2;
            margin-bottom: 24px;
        }

        .flash {
            padding: 10px 14px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 0.95em;
        }

        .success {
            background: #e8f5e9;
            color: #1b5e20;
            border: 1px solid #a5d6a7;
        }

        .error {
            background: #ffebee;
            color: #b71c1c;
            border: 1px solid #ef9a9a;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        label {
            font-weight: 600;
            color: #333;
        }

        input {
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1em;
        }

        input:focus {
            border-color: #1976d2;
            outline: none;
            box-shadow: 0 0 3px rgba(25,118,210,0.3);
        }

        button {
            margin-top: 10px;
            background: #1976d2;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 6px;
            font-size: 1em;
            cursor: pointer;
            transition: background 0.25s;
        }

        button:hover {
            background: #125ca7;
        }

        .link {
            text-align: center;
            margin-top: 14px;
            font-size: 0.9em;
        }

        .link a {
            color: #1976d2;
            text-decoration: none;
        }

        .link a:hover {
            text-decoration: underline;
        } 
    </style>
</head>
<body>
    <div class="container">
        <h1>Créer un compte</h1>

        <?php if ($m = getFlash('success')): ?>
            <p class="flash success"><?= htmlspecialchars($m) ?></p>
        <?php endif; ?>

        <?php if ($m = getFlash('error')): ?>
            <p class="flash error"><?= htmlspecialchars($m) ?></p>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <div class="form-group">
                <label for="nom">Nom complet</label>
                <input type="text" id="nom" name="nom" required placeholder="Votre nom">
            </div>

            <div class="form-group">
                <label for="email">Adresse email</label>
                <input type="email" id="email" name="email" required placeholder="exemple@mail.com">
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required placeholder="••••••••">
            </div>

            <button type="submit">S’inscrire</button>
        </form>

        <p class="link">Déjà inscrit ? <a href="login.php">Se connecter</a></p>
    </div>
</body>
</html>
