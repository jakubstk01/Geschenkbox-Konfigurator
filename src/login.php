<?php
require_once __DIR__ . '/config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    if ($email && $password) {
        $stmt = $pdo->prepare('SELECT id, password FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            header('Location: index.php'); exit;
        } else {
            $error = 'Ungültige Anmeldedaten';
        }
    }
}
?>
<!doctype html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
    <div class="col-md-5">
      <h2 class="mb-4">Login</h2>
      <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?=htmlspecialchars($error)?></div>
      <?php endif; ?>
      <form method="post">
        <div class="mb-3">
          <label class="form-label">E-Mail</label>
          <input name="email" type="email" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Passwort</label>
          <input name="password" type="password" class="form-control" required>
        </div>
        <button class="btn btn-primary">Login</button>
        <a href="index.php" class="btn btn-link">Zurück</a>
      </form>
    </div>
  </div>
</div>
</body>
</html>
