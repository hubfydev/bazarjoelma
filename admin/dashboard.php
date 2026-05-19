<?php
/**
 * Bazar Mix da Jô — Dashboard administrativo
 *
 * Resumo geral: totais de produtos, ativos, desativados e categorias.
 */

require_once __DIR__ . '/auth.php';

$summary = [
    'total_products'    => (int)db()->query('SELECT COUNT(*) FROM products')->fetchColumn(),
    'active_products'   => (int)db()->query('SELECT COUNT(*) FROM products WHERE is_active = 1')->fetchColumn(),
    'inactive_products' => (int)db()->query('SELECT COUNT(*) FROM products WHERE is_active = 0')->fetchColumn(),
    'total_categories'  => (int)db()->query('SELECT COUNT(*) FROM categories')->fetchColumn(),
];
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Dashboard - <?= e(STORE_NAME) ?></title>
    <link rel="stylesheet" href="/assets/css/admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
</head>
<body>
<?php require __DIR__ . '/partials_nav.php'; ?>
<main class="admin-main">
    <h1>📊 Dashboard</h1>
    <p style="margin:-12px 0 20px; color: var(--muted); font-size: 0.95rem;">
        Olá, <strong><?= e($_SESSION['admin_name'] ?? 'Admin') ?></strong>! Aqui está o resumo da sua loja.
    </p>
    <div class="stats-grid">
        <div class="stat">
            <span><?= $summary['total_products'] ?></span>
            <p>📦 Produtos cadastrados</p>
        </div>
        <div class="stat">
            <span><?= $summary['active_products'] ?></span>
            <p>✅ Produtos ativos</p>
        </div>
        <div class="stat">
            <span><?= $summary['inactive_products'] ?></span>
            <p>⏸️ Produtos desativados</p>
        </div>
        <div class="stat">
            <span><?= $summary['total_categories'] ?></span>
            <p>🏷️ Categorias</p>
        </div>
    </div>
</main>
</body>
</html>
