<?php
// Fichier utilitaires pour les utilisateurs
require_once __DIR__ . '/db.php';


// Trouver un utilisateur par email (insensible à la casse).
function findUserByEmail(string $email): ?array {
  $pdo  = get_pdo();
  $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->execute([trim($email)]);
  $row = $stmt->fetch();
  return $row ?: null;
}

/*Enregistrer un utilisateur (DB) + validations
Convention : renvoie ['ok'=>true] ou lève Exception sur erreur
 */
function registerUser(string $nom, string $email, string $password): array {
  $nom      = trim($nom);
  $email    = trim($email);
  $password = trim($password);

  // Validations
  if ($nom === '' || $email === '' || $password === '') {
    throw new Exception("Tous les champs sont obligatoires.");
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    throw new Exception("Email invalide.");
  }
  if (strlen($password) < 6) {
    throw new Exception("Mot de passe trop court (min. 6).");
  }

  // Unicité de l'email
  if (findUserByEmail($email)) {
    throw new Exception("Un utilisateur avec cet email existe déjà.");
  }

  // Insertion
  $hash = password_hash($password, PASSWORD_BCRYPT);
  $pdo  = get_pdo();
  $stmt = $pdo->prepare("INSERT INTO users (nom, email, password) VALUES (?, ?, ?)");
  $stmt->execute([$nom, $email, $hash]);

  return ['ok' => true];
}

//Lister les utilisateurs 

function listUsers(): array {
  $pdo  = get_pdo();
  $stmt = $pdo->query("SELECT id, nom, email, created_at FROM users ORDER BY id DESC");
  return $stmt->fetchAll();
}