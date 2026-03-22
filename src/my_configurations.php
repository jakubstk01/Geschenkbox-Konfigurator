<?php
require_once __DIR__ . '/config.php';
if (!is_logged_in()) {
    header('Location: login.php');
    exit;
}

$userId = current_user_id();
$stmt = $pdo->prepare('SELECT id, box_size, box_style, message, ribbon_color, total_price, created_at FROM configurations WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([$userId]);
$configs = $stmt->fetchAll();
?>
<!doctype html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Meine Konfigurationen</title>
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
  <div style="text-align: center; margin-bottom: 2rem;">
    <h1 style="color: #1f2937; font-weight: 700; font-size: 2rem; margin-bottom: 0.5rem;">📦 Meine Konfigurationen</h1>
    <p style="color: #6b7280; font-size: 1.05rem;">Deine gespeicherten Geschenkbox-Konfigurationen</p>
  </div>

  <?php if (empty($configs)): ?>
    <div style="text-align: center; padding: 3rem 1rem; background: white; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
      <p style="color: #9ca3af; font-size: 1.1rem; margin-bottom: 2rem;">Du hast noch keine Konfigurationen gespeichert.</p>
      <a href="/configurator/step1.php" class="btn btn-primary" style="padding: 0.875rem 2rem; font-size: 1.125rem;">Neue Konfiguration erstellen</a>
    </div>
  <?php else: ?>
    <div class="row g-4">
      <?php foreach ($configs as $config): ?>
        <div class="col-lg-6">
          <div style="background: white; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); padding: 1.5rem; height: 100%;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
              <div>
                <h5 style="color: #1f2937; font-weight: 600; margin-bottom: 0.5rem;">
                  <?=htmlspecialchars($config['box_style'] ?? 'Neutral')?>-Box (<?=htmlspecialchars($config['box_size'] ?? 'M')?>)
                </h5>
                <p style="color: #9ca3af; font-size: 0.875rem; margin: 0;">
                  Erstellt: <?=date('d.m.Y H:i', strtotime($config['created_at']))?> Uhr
                </p>
              </div>
              <a href="/checkout.php?id=<?=$config['id']?>" style="background: linear-gradient(135deg, #6366f1 0%, #7c3aed 100%); color: white; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; cursor: pointer; text-decoration: none; display: block; transition: all 0.3s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(99, 102, 241, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                €<?=number_format($config['total_price'], 2, '.', ',')?>
              </a>
            </div>

            <div style="background: #f9fafb; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
              <?php if (!empty($config['message'])): ?>
                <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.75rem;">
                  <strong>Nachricht:</strong> "<?=htmlspecialchars($config['message'])?>"
                </p>
              <?php endif; ?>
              <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0;">
                <strong>Schleife:</strong> 
                <span style="display: inline-block; width: 20px; height: 20px; border-radius: 50%; border: 2px solid #e5e7eb; background-color: <?=htmlspecialchars($config['ribbon_color'] ?? '#000000')?>; margin-left: 0.5rem; vertical-align: middle;"></span>
              </p>
            </div>

            <div style="display: flex; gap: 0.75rem;">
              <a href="/load_config.php?id=<?=$config['id']?>" class="btn btn-outline-primary" style="flex: 1; border: 2px solid #6366f1; color: #6366f1; padding: 0.65rem 1.4rem; border-radius: 8px; font-weight: 600; background: transparent; cursor: pointer; text-align: center; text-decoration: none; transition: all 0.3s;">
                ✏️ Bearbeiten
              </a>
              <form method="post" action="/delete_config.php" style="flex: 1; margin: 0;">
                <input type="hidden" name="config_id" value="<?=$config['id']?>">
                <button type="submit" class="btn btn-danger" style="width: 100%; border: 2px solid #ef4444; color: white; background: #ef4444; padding: 0.65rem 1.4rem; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s;" onclick="return confirm('Konfiguration wirklich löschen?');">
                  🗑️ Löschen
                </button>
              </form>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <div style="text-align: center; margin-top: 2rem;">
      <a href="/configurator/step1.php" class="btn btn-primary" style="padding: 0.875rem 2rem; font-size: 1.125rem;">+ Neue Konfiguration</a>
    </div>
  <?php endif; ?>
</div>
</body>
</html>
