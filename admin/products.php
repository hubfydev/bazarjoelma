<?php
/**
 * Bazar Mix da Jô — Listagem de produtos (admin)
 */

require_once __DIR__ . '/auth.php';

$stmt     = db()->query("SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON c.id = p.category_id ORDER BY p.created_at DESC");
$products = $stmt->fetchAll();
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Produtos - <?= e(STORE_NAME) ?></title>
    <link rel="stylesheet" href="/assets/css/admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
</head>
<body>
<?php require __DIR__ . '/partials_nav.php'; ?>
<main class="admin-main">
    <div class="page-title">
        <h1>📦 Produtos</h1>
        <a class="button" href="/admin/product_create.php">+ Novo produto</a>
    </div>

    <?php if (count($products) === 0): ?>
        <p style="color: var(--muted);">Nenhum produto cadastrado. <a href="/admin/product_create.php">Cadastre o primeiro!</a></p>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Categoria</th>
                        <th>Preço</th>
                        <th>Fotos</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><strong><?= e($product['title']) ?></strong></td>
                        <td><?= e($product['category_name'] ?? 'Sem categoria') ?></td>
                        <td><?= e(format_price($product['price'])) ?></td>
                        <td><?= count(product_images($product)) ?> / 4</td>
                        <td>
                            <span class="badge <?= $product['is_active'] ? 'on' : 'off' ?>">
                                <?= $product['is_active'] ? 'Ativo' : 'Desativado' ?>
                            </span>
                        </td>
                        <td class="actions">
                            <a href="/admin/product_edit.php?id=<?= (int)$product['id'] ?>">Editar</a>
                            <a class="danger" href="/admin/product_delete.php?id=<?= (int)$product['id'] ?>"
                               onclick="return confirm('Excluir este produto e suas fotos?')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</main>
</body>
</html>
