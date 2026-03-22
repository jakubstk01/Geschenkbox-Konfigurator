<?php require_once __DIR__ . '/config.php'; ?>
<!doctype html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Geschenkbox-Konfigurator</title>
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
        <a href="logout.php" class="btn btn-outline-primary me-2">Logout</a>
      <?php else: ?>
        <a href="login.php" class="btn btn-outline-primary me-2">Login</a>
        <a href="register.php" class="btn btn-primary">Registrieren</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<div class="container py-5">
  <div class="row align-items-center g-4">
    <div class="col-lg-6">
      <h1 style="margin-bottom: 1.5rem;">Geschenkbox-Konfigurator</h1>
      <p style="font-size: 1.125rem; line-height: 1.6; color: var(--gray-600); margin-bottom: 2rem;">
        Kreiere die perfekte Geschenkbox nach deinen Wünschen. Wähle die Größe, die Produkte und personalisiere deine Box mit einer persönlichen Nachricht.
      </p>
      <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <a href="configurator/step1.php" class="btn btn-primary" style="padding: 0.875rem 2rem; font-size: 1.125rem;">Jetzt erstellen</a>
        <?php if (is_logged_in()): ?>
          <a href="my_configurations.php" class="btn btn-outline-primary" style="padding: 0.875rem 2rem; font-size: 1.125rem;">Meine Konfigurationen</a>
        <?php else: ?>
          <a href="register.php" class="btn btn-outline-primary" style="padding: 0.875rem 2rem; font-size: 1.125rem;">Registrieren</a>
        <?php endif; ?>
      </div>
    </div>
    <div class="col-lg-6 text-center">
      <img src="/assets/images/box.svg" alt="Geschenkbox" class="img-fluid" style="width: 100%; max-width: 500px; drop-shadow: 0 10px 30px rgba(0,0,0,0.1);">
    </div>
  </div>

  <div class="row row-cols-1 row-cols-lg-3 gap-4 mt-5">
    <div class="col">
      <div class="card p-4" style="border: none; text-align: center; background: linear-gradient(135deg, rgba(99, 102, 241, 0.05), rgba(99, 102, 241, 0.02)); height: 100%;">
        <div style="font-size: 2.5rem; margin-bottom: 1rem;">📦</div>
        <h5>Flexible Auswahl</h5>
        <p style="color: var(--gray-600);">Wähle aus über 20 verschiedenen Produkten</p>
      </div>
    </div>
    <div class="col">
      <div class="card p-4" style="border: none; text-align: center; background: linear-gradient(135deg, rgba(236, 72, 153, 0.05), rgba(236, 72, 153, 0.02)); height: 100%;">
        <div style="font-size: 2.5rem; margin-bottom: 1rem;">✨</div>
        <h5>Personalisierung</h5>
        <p style="color: var(--gray-600);">Füge eine persönliche Nachricht hinzu</p>
      </div>
    </div>
    <div class="col">
      <div class="card p-4" style="border: none; text-align: center; background: linear-gradient(135deg, rgba(16, 185, 129, 0.05), rgba(16, 185, 129, 0.02)); height: 100%;">
        <div style="font-size: 2.5rem; margin-bottom: 1rem;">💾</div>
        <h5>Speichern</h5>
        <p style="color: var(--gray-600);">Speichere deine Kreationen als Benutzer</p>
      </div>
    </div>
  </div>
</div>
</body>
</html>
