<?php
require_once __DIR__ . '/utils/boot.php';
require_once __DIR__ . '/utils/functions.php';

function wants_json(): bool {
    $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
    $xhr    = strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '');
    return str_contains($accept, 'application/json') || $xhr === 'xmlhttprequest';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom      = trim($_POST['nom'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    try {
        registerUser($nom, $email, $password);

        if (wants_json()) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'ok'       => true,
                'message'  => 'Inscription réussie ! Vous pouvez maintenant vous connecter.',
                'redirect' => 'login.php'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        setFlash("Inscription réussie ! Vous pouvez maintenant vous connecter.", 'success');
        redirect('login.php');

    } catch (Exception $e) {
        if (wants_json()) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'ok'      => false,
                'message' => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

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
        .invalid { border: 1px solid #ef4444; }
        .valid   { border: 1px solid #22c55e; }

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

        <form id ="register-form" method="POST" action="register.php">
            <div class="form-group">
                <label for="nom">Nom complet</label>
                <input type="text" id="nom" name="nom" required placeholder="Votre nom">
                <div id="err-nom" class="error"></div>

            </div>

            <div class="form-group">
                <label for="email">Adresse email</label>
                <input type="email" id="email" name="email" required placeholder="exemple@mail.com">
                <div id="err-mail" class="error"></div>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required placeholder="••••••••">
                <div id="err-pass" class="error"></div>
            </div>

            <button type="submit">S’inscrire</button>
        </form>
      
        <p class="link">Déjà inscrit ? <a href="login.php">Se connecter</a></p>
        <p id="result"></p>
    </div>

    <script>
  const form     = document.getElementById("register-form");
  const nom      = document.getElementById("nom");
  const email    = document.getElementById("email");
  const password = document.getElementById("password");
  const result   = document.getElementById("result");

  const eNom   = document.getElementById('err-nom');
  const eEmail = document.getElementById('err-mail');
  const ePass  = document.getElementById('err-pass');

  const emailRx = /^\S+@\S+\.\S+$/;
  const passRx  = /^(?=.*[A-Z])(?=.*\d).{6,}$/;

  function setState(input, errorBox, message) {
    if (message) {
      input.classList.add('invalid'); input.classList.remove('valid');
      errorBox.textContent = message;
    } else {
      input.classList.remove('invalid'); input.classList.add('valid');
      errorBox.textContent = '';
    }
  }

  function validate() {
    let ok = true;

    const vNom = nom.value.trim();
    if (!vNom) { setState(nom, eNom, "Le nom est obligatoire."); ok = false; }
    else       { setState(nom, eNom, ""); }

    const vEmail = email.value.trim();
    if (!emailRx.test(vEmail)) { setState(email, eEmail, "Email invalide."); ok = false; }
    else                       { setState(email, eEmail, ""); }

    const vPass = password.value;
    if (!passRx.test(vPass)) { setState(password, ePass, "Min 6, 1 majuscule, 1 chiffre."); ok = false; }
    else                     { setState(password, ePass, ""); }

    return ok;
  }

  [nom, email, password].forEach(el => el.addEventListener('input', validate));

  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    if (!validate()) {
      result.textContent = "❌ Corrige les erreurs";
      result.className = "error";
      return;
    }

    const submitBtn = form.querySelector("button[type='submit']");
    submitBtn.disabled = true;
    result.textContent = "⏳ Envoi en cours…";
    result.className = "";

    try {
      const fd = new FormData(form);
      const res = await fetch("register.php", {
        method: "POST",
        body: fd,
        headers: { "Accept": "application/json" } // <- je demande du JSON
      });

      const data = await res.json(); // { ok: true/false, message: "...", redirect: "login.php" }

      if (data.ok) {
        result.textContent = `✅ ${data.message}`;
        result.className = "success";
        // redirection après 700ms 
        if (data.redirect) {
          setTimeout(() => { window.location.href = data.redirect; }, 700);
        }
      } else {
        result.textContent = `❌ ${data.message || "Erreur inconnue"}`;
        result.className = "error";
      }
    } catch (err) {
      console.error(err);
      result.textContent = "❌ Erreur réseau. Réessaie.";
      result.className = "error";
    } finally {
      submitBtn.disabled = false;
    }
  });
</script>


</body>
</html>
