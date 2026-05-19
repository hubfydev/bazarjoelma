<!-- Navegação do painel administrativo -->
<header class="admin-header">
    <a class="admin-brand" href="/admin/dashboard.php">🛍️ <?= e(STORE_NAME) ?></a>
    <nav>
        <a href="/admin/dashboard.php">📊 Dashboard</a>
        <a href="/admin/products.php">📦 Produtos</a>
        <a href="/admin/categories.php">🏷️ Categorias</a>
        <a href="/" target="_blank">🌐 Loja</a>
        <a href="/admin/logout.php">🚪 Sair</a>
    </nav>
</header>
<?php if ($flash = get_flash()): ?>
    <div class="flash <?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
<?php endif; ?>
