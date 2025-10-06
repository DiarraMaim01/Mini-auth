<?php
// Fichier utilitaires pour les utilisateurs

// Chemin unique vers le fichier JSON des utilisateurs
const USERS_FILE = __DIR__ . '/../data/users.json';

/**
 * S'assurer que le dossier/fichier de stockage existe.
 * Crée le dossier data/ et un JSON vide [] si nécessaire.
 */
function ensureDataStore(): void {
    $dir = dirname(USERS_FILE);
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    if (!file_exists(USERS_FILE)) {
        file_put_contents(USERS_FILE, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
    }
}

// Charger tous les utilisateurs.
 
function loadUsers(): array {
    ensureDataStore();
    $json = file_get_contents(USERS_FILE);
    $data = json_decode($json, true);
    return is_array($data) ? $data : [];
}

// Sauvegarder tous les utilisateurs.

function saveUsers(array $users): void {
    ensureDataStore();
    $json = json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents(USERS_FILE, $json, LOCK_EX);
}

// Trouver un utilisateur par email (insensible à la casse).
function findUserByEmail(string $email): ?array {
    $email = strtolower(trim($email));
    foreach (loadUsers() as $user) {
        if (strtolower($user['email']) === $email) {
            return $user;
        }
    }
    return null;
}

/**
 * Enregistrer un nouvel utilisateur.
 * Retourne ['ok'=>true] en cas de succès,
 * sinon ['ok'=>false, 'error'=>'message'].
 */
function registerUser(string $nom, string $email, string $password): array {
    $nom = trim($nom);
    $email = strtolower(trim($email));
    $password = trim($password);

    // Validations basiques
    if ($nom === '' || $email === '' || $password === '') {
        return ['ok' => false, 'error' => 'Tous les champs sont obligatoires.'];
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['ok' => false, 'error' => "Adresse email invalide."];
    }
    if (strlen($password) < 6) {
        return ['ok' => false, 'error' => "Le mot de passe doit contenir au moins 6 caractères."];
    }
    if (findUserByEmail($email) !== null) {
        return ['ok' => false, 'error' => "Un utilisateur avec cet email existe déjà."];
    }

    // Hachage du mot de passe
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Ajout de l'utilisateur
    $users = loadUsers();
    $users[] = [
        'nom'        => $nom,
        'email'      => $email,
        'password'   => $hashedPassword,
        'created_at' => date('Y-m-d H:i:s'),
    ];
    saveUsers($users);

    return ['ok' => true];
}
