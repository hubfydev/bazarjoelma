<?php
/**
 * Bazar Mix da Jô — Listagem de categorias
 */

require_once __DIR__ . '/auth.php';

$categories = db()->query('SELECT * FROM categories ORDER BY name')->fetchAll();
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Categorias - <?= e(STORE_NAME) ?></title>
    <link rel="stylesheet" href="/assets/css/admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
</head>
<body>
<?php require __DIR__ . '/partials_nav.php'; ?>
<main class="admin-main">
    <div class="page-title">
        <h1>🏷️ Categorias</h1>
        <a class="button" href="/admin/category_create.php">+ Nova categoria</a>
    </div>

    <?php if (count($categories) === 0): ?>
        <p style="color: var(--muted);">Nenhuma categoria cadastrada.</p>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?= e($category['name']) ?></td>
                        <td>
                            <span class="badge <?= $category['status'] ? 'on' : 'off' ?>">
                                <?= $category['status'] ? 'Ativa' : 'Desativada' ?>
                            </span>
                        </td>
                        <td class="actions">
                            <a href="/admin/category_edit.php?id=<?= (int)$category['id'] ?>">Editar</a>
                            <a class="danger" href="/admin/category_delete.php?id=<?= (int)$category['id'] ?>"
                               onclick="return confirm('Excluir esta categoria? Produtos vinculados ficarão sem categoria.')">Excluir</a>
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
