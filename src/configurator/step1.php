<?php
require_once __DIR__ . '/../config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['config'] = $_SESSION['config'] ?? [];
    $_SESSION['config']['box_size'] = $_POST['box_size'] ?? 'M';
    $_SESSION['config']['box_style'] = $_POST['box_style'] ?? 'Neutral';
    header('Location: step2.php'); exit;
}
?>
<!doctype html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Step 1 - Box auswählen</title>
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
<div class="container p-4">
    <div class="row g-4">
      <div class="col-lg-7">
        <div class="card p-4">
          <h2 class="mb-3">Schritt 1: Box auswählen</h2>
          <form method="post" id="step1-form">
            <div class="mb-3">
              <label class="form-label">Boxgröße</label>
              <select name="box_size" class="form-select">
                <option value="S" <?=($_SESSION['config']['box_size'] ?? 'M') === 'S' ? 'selected' : ''?>>S - Klein (ca. 15x15x15cm)</option>
                <option value="M" <?=($_SESSION['config']['box_size'] ?? 'M') === 'M' ? 'selected' : ''?>>M - Mittel (ca. 25x25x25cm)</option>
                <option value="L" <?=($_SESSION['config']['box_size'] ?? 'M') === 'L' ? 'selected' : ''?>>L - Groß (ca. 35x35x35cm)</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Boxstil</label>
              <select name="box_style" class="form-select">
                <option <?=($_SESSION['config']['box_style'] ?? 'Neutral') === 'Neutral' ? 'selected' : ''?>>Neutral</option>
                <option <?=($_SESSION['config']['box_style'] ?? 'Neutral') === 'Geburtstag' ? 'selected' : ''?>>Geburtstag</option>
                <option <?=($_SESSION['config']['box_style'] ?? 'Neutral') === 'Weihnachten' ? 'selected' : ''?>>Weihnachten</option>
              </select>
            </div>
            <div class="d-flex gap-2">
              <button class="btn btn-primary">Weiter</button>
              <a href="/index.php" class="btn btn-link">Abbrechen</a>
            </div>
          </form>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="preview-card">
          <h5>Live-Vorschau</h5>
          <div id="preview-svg-container" style="background: white; border-radius: 0.75rem; padding: 1rem; border: 1px solid var(--gray-200);">
            <svg width="200" height="200" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" style="max-width: 300px; margin: 0 auto; display: block; width: 100%; height: auto;">
              <!-- Box -->
              <rect id="box" x="30" y="70" width="140" height="100" rx="8" fill="#f3f4f6" stroke="#9ca3af"></rect>
              <!-- Lid -->
              <rect id="lid" x="20" y="50" width="160" height="30" rx="6" fill="#e5e7eb"></rect>
              <!-- Vertical ribbon -->
              <rect id="ribbon-vertical" x="95" y="50" width="10" height="120" fill="#ff4d6d"></rect>
              <!-- Horizontal ribbon -->
              <rect id="ribbon-horizontal" x="30" y="80" width="140" height="10" fill="#ff4d6d"></rect>
              <!-- Bow left -->
              <path id="bow-left" d="M100 50 C70 20, 40 60, 90 65 Z" fill="#ff4d6d"></path>
              <!-- Bow right -->
              <path id="bow-right" d="M100 50 C130 20, 160 60, 110 65 Z" fill="#ff4d6d"></path>
              <!-- Knot -->
              <circle id="knot" cx="100" cy="55" r="6" fill="#ff4d6d"></circle>
            </svg>
          </div>
          <div class="mt-2"><strong>Größe:</strong> <span id="preview-box-size">-</span></div>
          <div><strong>Stil:</strong> <span id="preview-box-style">-</span></div>
          <hr>
          <div class="preview-items"><strong>Produkte:</strong>
            <div id="preview-list">–</div>
          </div>
          <div class="mt-3"><span class="price">€<span id="preview-price">0.00</span></span></div>
        </div>
      </div>
    </div>
    <script src="/assets/js/preview.js" defer></script>
    </div>
</body>
</html>
