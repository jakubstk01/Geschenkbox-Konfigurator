<?php
require_once __DIR__ . '/../config.php';
$config = $_SESSION['config'] ?? [];
$productIds = $config['products'] ?? [];
$products = [];
$total = 0.0;
if ($productIds) {
    $in = implode(',', array_map('intval', $productIds));
    $stmt = $pdo->query("SELECT id, name, price FROM products WHERE id IN ($in)");
    $products = $stmt->fetchAll();
    foreach ($products as $p) { $total += $p['price']; }
}

// Color mapping
$colorNames = [
  '#e63946' => 'Rot',
  '#3b82f6' => 'Blau',
  '#10b981' => 'Grün',
  '#f59e0b' => 'Gold',
  '#ec4899' => 'Rosa',
  '#8b5cf6' => 'Violett',
  '#94a3b8' => 'Silber',
  '#111827' => 'Schwarz'
];

function getColorName($hex) {
  global $colorNames;
  return $colorNames[strtolower($hex)] ?? $hex;
}

// Apply coupon if submitted
$discount = 0;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['coupon'])) {
    $code = trim($_POST['coupon']);
    $c = $pdo->prepare('SELECT discount_percent FROM coupons WHERE code = ?');
    $c->execute([$code]);
    $row = $c->fetch();
    if ($row) { $discount = (int)$row['discount_percent']; }
}

$final = $total * (1 - $discount/100);

?>
<!doctype html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Zusammenfassung</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/style.css">
  <style>
    body { background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf4 100%); min-height: 100vh; }
    .summary-card { 
      background: white; 
      border-radius: 12px; 
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      padding: 2rem;
      margin-bottom: 2rem;
    }
    .summary-section { 
      margin-bottom: 2rem; 
      padding-bottom: 2rem; 
      border-bottom: 1px solid #e5e7eb;
    }
    .summary-section:last-child { border-bottom: none; }
    .summary-label { 
      font-weight: 600; 
      color: #4f46e5; 
      font-size: 0.875rem; 
      text-transform: uppercase; 
      letter-spacing: 0.5px;
      margin-bottom: 0.5rem;
    }
    .summary-value { 
      color: #1f2937; 
      font-size: 1.125rem;
      margin-bottom: 0.5rem;
    }
    .product-item {
      display: flex;
      justify-content: space-between;
      padding: 0.75rem 0;
      border-left: 3px solid #6366f1;
      padding-left: 1rem;
    }
    .product-name { color: #374151; font-weight: 500; }
    .product-price { color: #6366f1; font-weight: 600; }
    .price-summary {
      background: linear-gradient(135deg, #6366f1 0%, #7c3aed 100%);
      color: white;
      padding: 1.5rem;
      border-radius: 12px;
      margin-bottom: 2rem;
    }
    .price-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 0.75rem;
      font-size: 1rem;
    }
    .price-total {
      font-size: 1.75rem;
      font-weight: 700;
      border-top: 2px solid rgba(255,255,255,0.3);
      padding-top: 1rem;
      margin-top: 1rem;
    }
    .coupon-input {
      border: 2px solid #e5e7eb;
      border-radius: 8px;
      padding: 0.75rem 1rem;
      font-size: 1rem;
      transition: border-color 0.2s;
    }
    .coupon-input:focus {
      border-color: #6366f1;
      outline: none;
      box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
    .btn-primary-gradient {
      background: linear-gradient(135deg, #6366f1 0%, #7c3aed 100%);
      color: white;
      border: none;
      padding: 1rem 1.75rem;
      border-radius: 8px;
      font-weight: 600;
      transition: all 0.3s;
      cursor: pointer;
    }
    .btn-primary-gradient:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(99, 102, 241, 0.4);
      color: white;
      text-decoration: none;
    }
    .btn-secondary-outline {
      border: 2px solid #6366f1;
      color: #6366f1;
      padding: 0.85rem 1.5rem;
      border-radius: 8px;
      font-weight: 600;
      background: transparent;
      transition: all 0.3s;
      cursor: pointer;
    }
    .btn-secondary-outline:hover {
      background: #6366f1;
      color: white;
    }
    .discount-badge {
      background: #10b981;
      color: white;
      padding: 0.75rem 1rem;
      border-radius: 8px;
      margin-bottom: 1.5rem;
      font-weight: 600;
    }
    .btn-group-footer {
      display: flex;
      gap: 1rem;
      flex-wrap: wrap;
      margin-top: 2rem;
      padding-bottom: 1.5rem;
    }
  </style>
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
  <div class="container" style="max-width: 700px; margin: 2rem auto;">
    
    <!-- Header -->
    <div style="text-align: center; margin-bottom: 2rem;">
      <h1 style="color: #1f2937; font-weight: 700; font-size: 2rem; margin-bottom: 0.5rem;">✓ Zusammenfassung</h1>
      <p style="color: #6b7280; font-size: 1.05rem;">Deine Geschenkbox-Konfiguration</p>
    </div>

    <!-- Summary Card -->
    <div class="summary-card">
      
      <!-- Box Configuration -->
      <div class="summary-section">
        <div class="summary-label">📦 Boxkonfiguration</div>
        <div class="summary-value">
          Größe: <strong><?=htmlspecialchars($config['box_size'] ?? '-')?></strong> | 
          Stil: <strong><?=htmlspecialchars($config['box_style'] ?? '-')?></strong>
        </div>
      </div>

      <!-- Products -->
      <div class="summary-section">
        <div class="summary-label">🎁 Ausgewählte Produkte</div>
        <?php if (empty($products)): ?>
          <p style="color: #9ca3af;">Keine Produkte ausgewählt</p>
        <?php else: ?>
          <div>
            <?php foreach ($products as $p): ?>
              <div class="product-item">
                <span class="product-name"><?=htmlspecialchars($p['name'])?></span>
                <span class="product-price">€<?=number_format($p['price'],2,'.',',')?></span>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

      <!-- Personalization -->
      <div class="summary-section">
        <div class="summary-label">✨ Personalisierung</div>
        <div class="summary-value">
          <strong>Grußkarte:</strong><br>
          <span style="color: #6b7280; font-style: italic;">
            <?=htmlspecialchars($config['message'] ?? '(keine Nachricht)') ?: '(keine Nachricht)'?>
          </span>
        </div>
        <div style="margin-top: 1rem;">
          <strong>Schleifenfarbe:</strong><br>
          <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.5rem;">
            <span style="display: inline-block; width: 30px; height: 30px; border-radius: 50%; border: 2px solid #e5e7eb; background-color: <?=htmlspecialchars($config['ribbon_color'] ?? '#000000')?>; "></span>
            <span style="color: #374151;"><?=getColorName($config['ribbon_color'] ?? '#000000')?></span>
          </div>
        </div>
      </div>

      <!-- Price Summary -->
      <div class="price-summary">
        <div class="price-row">
          <span>Zwischensumme:</span>
          <span>€<?=number_format($total,2,'.',',')?></span>
        </div>
        <?php if ($discount): ?>
          <div class="price-row" style="color: #86efac;">
            <span><?=$discount?>% Rabatt:</span>
            <span>-€<?=number_format($total * $discount / 100, 2, '.', ',')?></span>
          </div>
        <?php endif; ?>
        <div class="price-total">
          Gesamtbetrag: €<?=number_format($final,2,'.',',')?><br>
        </div>
      </div>

      <!-- Coupon Form -->
      <form method="post" class="mb-3">
        <label style="display: block; margin-bottom: 0.75rem; font-weight: 600; color: #374151;">Gutscheincode</label>
        <div style="display: flex; gap: 0.5rem; margin-bottom: 1.5rem;">
          <input 
            type="text"
            name="coupon" 
            class="coupon-input" 
            style="flex: 1;"
            placeholder="z.B. SAVE10"
            value="<?=htmlspecialchars($_POST['coupon'] ?? '')?>"
          >
          <button type="submit" class="btn-secondary-outline">Anwenden</button>
        </div>
      </form>

      <?php if ($discount): ?>
        <div class="discount-badge">
          ✓ Gutschein angewendet: <?=$discount?>% Rabatt
        </div>
      <?php endif; ?>

      <a href="/index.php" class="btn-primary-gradient" style="display: inline-block; text-align: center;">
  🛒 Jetzt bestellen
</a>
    </div>

    <!-- Action Buttons -->
    <div class="btn-group-footer">
      <?php if (is_logged_in()): ?>
        <form method="post" action="/save_config.php" style="flex: 1;">
          <input type="hidden" name="coupon" value="<?=htmlspecialchars($_POST['coupon'] ?? '')?>">
          <button type="submit" class="btn-primary-gradient" style="width: 100%;">💾 Konfiguration speichern</button>
        </form>
      <?php else: ?>
        <a href="/login.php" class="btn-primary-gradient" style="text-align: center; flex: 1;">🔐 Einloggen zum Speichern</a>
      <?php endif; ?>
      <a href="/configurator/step3.php" class="btn-secondary-outline">← Zurück</a>
      <a href="/configurator/reset.php" class="btn-secondary-outline">↻ Neu konfigurieren</a>
    </div>

  </div>
</body>
</html>
