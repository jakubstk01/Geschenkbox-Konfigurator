<?php
require_once __DIR__ . '/../config.php';
// Load products
$stmt = $pdo->query('SELECT id, name, price FROM products ORDER BY id');
$products = $stmt->fetchAll();

// Get previously selected products
$selectedProductIds = $_SESSION['config']['products'] ?? [];

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedProducts = array_map('intval', $_POST['products'] ?? []);
    
    // Validate: at least 3 products
    if (count($selectedProducts) < 3) {
        $error = 'Bitte wähle mindestens 3 Produkte aus.';
        $selectedProductIds = $selectedProducts;
    } else {
        $_SESSION['config'] = $_SESSION['config'] ?? [];
        $_SESSION['config']['products'] = $selectedProducts;
        header('Location: step3.php'); exit;
    }
}
?>
<!doctype html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Step 2 - Produkte</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="/assets/js/configurator.js" defer></script>
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
          <h2 class="mb-3">Schritt 2: Produkte auswählen</h2>
          <?php if (!empty($error)): ?>
            <div class="alert alert-danger" style="background: #fee2e2; border: 1px solid #fecaca; color: #991b1b; border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem;">
              ⚠️ <?=htmlspecialchars($error)?>
            </div>
          <?php endif; ?>
          <p style="color: #6b7280; font-size: 0.95rem; margin-bottom: 1.5rem;">
            <strong>Mindestens 3 Produkte erforderlich</strong> (<?=count($selectedProductIds)?>/3 ausgewählt)
          </p>
          <form method="post" id="products-form">
            <input type="hidden" name="box_size" value="<?=htmlspecialchars($_SESSION['config']['box_size'] ?? 'M')?>">
            <input type="hidden" name="box_style" value="<?=htmlspecialchars($_SESSION['config']['box_style'] ?? 'Neutral')?>">
            <div class="row">
              <?php foreach ($products as $p): 
                $isSelected = in_array((int)$p['id'], $selectedProductIds);
              ?>
                <div class="col-md-6 mb-2">
                  <div class="form-check">
                    <input class="form-check-input product-checkbox" type="checkbox" name="products[]" value="<?= $p['id'] ?>" id="p<?=$p['id']?>" data-price="<?=$p['price']?>" data-name="<?=htmlspecialchars($p['name'],ENT_QUOTES)?>" <?= $isSelected ? 'checked' : '' ?>>
                    <label class="form-check-label" for="p<?=$p['id']?>"><?=htmlspecialchars($p['name'])?> — €<?=$p['price']?></label>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
            <hr>
            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary" id="submit-btn" <?=count($selectedProductIds) < 3 ? 'disabled' : ''?>>Weiter</button>
              <a href="step1.php" class="btn btn-link">Zurück</a>
            </div>
          </form>
          <script>
            const form = document.getElementById('products-form');
            const submitBtn = document.getElementById('submit-btn');
            const checkboxes = document.querySelectorAll('.product-checkbox');
            const productCountSpan = document.querySelector('p strong').parentElement;
            
            function updateButtonState() {
              const checkedCount = document.querySelectorAll('.product-checkbox:checked').length;
              submitBtn.disabled = checkedCount < 3;
              
              // Update product count display
              const displayText = document.querySelector('p strong').parentElement;
              const currentCount = document.querySelectorAll('.product-checkbox:checked').length;
              displayText.innerHTML = '<strong>Mindestens 3 Produkte erforderlich</strong> (' + currentCount + '/3 ausgewählt)';
            }
            
            checkboxes.forEach(checkbox => {
              checkbox.addEventListener('change', updateButtonState);
            });
            
            form.addEventListener('submit', function(e) {
              const checkedCount = document.querySelectorAll('.product-checkbox:checked').length;
              if (checkedCount < 3) {
                e.preventDefault();
              }
            });
          </script>
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
    <script src="/assets/js/configurator.js" defer></script>
    <script src="/assets/js/preview.js" defer></script>
  </div>
</body>
</html>
