<?php
require_once __DIR__ . '/config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vorname = $_POST['vorname'] ?? '';
    $nachname = $_POST['nachname'] ?? '';
    $adresse = $_POST['adresse'] ?? '';
    $postleitzahl = $_POST['postleitzahl'] ?? '';
    $ort = $_POST['ort'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($vorname && $nachname && $adresse && $postleitzahl && $ort && $email && $password) {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = 'E-Mail bereits registriert';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $ins = $pdo->prepare('INSERT INTO users (vorname, nachname, adresse, postleitzahl, ort, email, password) VALUES (?,?,?,?,?,?,?)');
            $ins->execute([$vorname, $nachname, $adresse, $postleitzahl, $ort, $email, $hash]);
            header('Location: login.php'); exit;
        }
    } else {
        $error = 'Bitte alle Pflichtfelder ausfüllen';
    }
}
?>
<!doctype html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registrierung</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand navbar-light" style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(236, 72, 153, 0.05)); border-bottom: 1px solid rgba(99, 102, 241, 0.1);">
  <div class="container">
    <a class="navbar-brand" href="/" style="font-weight: 700; color: var(--primary);">🎁 Geschenkbox-Konfigurator</a>
    <div class="ms-auto">
      <?php if (is_logged_in()): ?>
        <span class="me-3">Willkommen!</span>
        <a href="/logout.php" class="btn btn-outline-primary me-2">Logout</a>
      <?php else: ?>
        <a href="/login.php" class="btn btn-outline-primary me-2">Login</a>
        <a href="/register.php" class="btn btn-primary">Registrieren</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <h2 class="mb-4">Registrierung</h2>
      <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?=htmlspecialchars($error)?></div>
      <?php endif; ?>
      <form method="post">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Vorname</label>
            <input name="vorname" type="text" class="form-control" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Nachname</label>
            <input name="nachname" type="text" class="form-control" required>
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label">Adresse</label>
          <input name="adresse" type="text" class="form-control" required>
        </div>
        <div class="row">
          <div class="col-md-4 mb-3">
            <label class="form-label">Postleitzahl</label>
            <input name="postleitzahl" type="text" class="form-control" required>
          </div>
          <div class="col-md-8 mb-3">
            <label class="form-label">Ort</label>
            <input name="ort" type="text" class="form-control" required>
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label">E-Mail</label>
          <input name="email" type="email" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Passwort</label>
          <input name="password" type="password" class="form-control" required>
        </div>
        <button class="btn btn-primary">Registrieren</button>
        <a href="index.php" class="btn btn-link">Abbrechen</a>
      </form>
    </div>
  </div>
</div>
</body>
</html>
