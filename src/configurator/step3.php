<?php
require_once __DIR__ . '/../config.php';

// Load previous config from session
$prevConfig = $_SESSION['config'] ?? [];
$productIds = $prevConfig['products'] ?? [];
$products = [];
if ($productIds) {
    $in = implode(',', array_map('intval', $productIds));
    $stmt = $pdo->query("SELECT id, name, price FROM products WHERE id IN ($in)");
    $products = $stmt->fetchAll();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['config'] = $_SESSION['config'] ?? [];
    $_SESSION['config']['message'] = $_POST['message'] ?? '';
    $_SESSION['config']['ribbon_color'] = $_POST['ribbon_color'] ?? 'Rot';
    header('Location: summary.php'); exit;
}

?>
<!doctype html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Step 3 - Personalisierung</title>
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
  <script>
    // Make previous products available to preview.js
    window.previousProducts = <?= json_encode(array_map(function($p) { return ['id' => (int)$p['id'], 'name' => $p['name'], 'price' => (float)$p['price']]; }, $products)) ?>;
  </script>
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-7">
        <div class="card p-4">
          <h2 class="mb-3">Schritt 3: Personalisierung</h2>
          <form method="post">
            <input type="hidden" name="box_size" value="<?=htmlspecialchars($_SESSION['config']['box_size'] ?? 'M')?>">
            <input type="hidden" name="box_style" value="<?=htmlspecialchars($_SESSION['config']['box_style'] ?? 'Neutral')?>">
            <div class="mb-3">
              <label class="form-label">Text für Grußkarte</label>
              <input name="message" class="form-control" value="<?=htmlspecialchars($_SESSION['config']['message'] ?? '')?>">
            </div>
            <div class="mb-3">
              <label class="form-label">Schleifenfarbe</label>
              <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                <button type="button" class="ribbon-color-btn" data-color="#e63946" style="background: #e63946;" title="Rot"></button>
                <button type="button" class="ribbon-color-btn" data-color="#3b82f6" style="background: #3b82f6;" title="Blau"></button>
                <button type="button" class="ribbon-color-btn" data-color="#10b981" style="background: #10b981;" title="Grün"></button>
                <button type="button" class="ribbon-color-btn" data-color="#f59e0b" style="background: #f59e0b;" title="Gold"></button>
                <button type="button" class="ribbon-color-btn" data-color="#ec4899" style="background: #ec4899;" title="Rosa"></button>
                <button type="button" class="ribbon-color-btn" data-color="#8b5cf6" style="background: #8b5cf6;" title="Violett"></button>
                <button type="button" class="ribbon-color-btn" data-color="#94a3b8" style="background: #94a3b8;" title="Silber"></button>
                <button type="button" class="ribbon-color-btn" data-color="#111827" style="background: #111827;" title="Schwarz"></button>
              </div>
              <input type="hidden" name="ribbon_color" id="ribbon_color_input" value="<?=htmlspecialchars($_SESSION['config']['ribbon_color'] ?? '#e63946')?>">
            </div>
            <div class="d-flex gap-2">
              <button class="btn btn-primary">Zusammenfassung</button>
              <a href="step2.php" class="btn btn-link">Zurück</a>
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
