<?php
function get_pdo(): PDO {
  static $pdo = null;
  if ($pdo === null) {
    $host = 'localhost';
    $dbname = 'mini_auth';
    $user = 'root';
    $pass = '';

    try {
      $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user,
        $pass,
        [
          PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
          PDO::ATTR_EMULATE_PREPARES   => false
        ]
      );
    } catch (PDOException $e) {
      die("Erreur de connexion : " . htmlspecialchars($e->getMessage()));
    }
  }
  return $pdo;
}
