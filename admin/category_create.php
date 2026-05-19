<?php
/**
 * Bazar Mix da Jô — Criar nova categoria
 */

require_once __DIR__ . '/auth.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name   = trim($_POST['name'] ?? '');
    $status = isset($_POST['status']) ? 1 : 0;

    if ($name === '') {
        $error = 'Informe o nome da categoria.';
    } else {
        $stmt = db()->prepare('INSERT INTO categories (name, status) VALUES (:name, :status)');
        $stmt->execute(['name' => $name, 'status' => $status]);
        flash('success', 'Categoria cadastrada com sucesso.');
        redirect('/admin/categories.php');
    }
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Nova categoria - <?= e(STORE_NAME) ?></title>
    <link rel="stylesheet" href="/assets/css/admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
</head>
<body>
<?php require __DIR__ . '/partials_nav.php'; ?>
<main class="admin-main form-page">
    <h1>🏷️ Nova categoria</h1>
    <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
    <form method="post" class="admin-form">
        <?php require __DIR__ . '/category_form.php'; ?>
        <div class="form-actions">
            <button type="submit">Salvar categoria</button>
            <a href="/admin/categories.php">Cancelar</a>
        </div>
    </form>
</main>
</body>
</html>
