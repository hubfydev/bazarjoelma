<?php
/**
 * Bazar Mix da Jô — Criar novo produto
 */

require_once __DIR__ . '/auth.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $title       = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price       = $_POST['price'] ?? '';
        $categoryId  = (int)($_POST['category_id'] ?? 0);
        $isActive    = isset($_POST['is_active']) ? 1 : 0;

        if ($title === '' || $description === '' || $price === '' || !is_numeric($price) || $categoryId <= 0) {
            throw new RuntimeException('Preencha todos os campos obrigatórios corretamente.');
        }

        $images = [];
        for ($i = 1; $i <= 4; $i++) {
            $images[$i] = upload_product_image($_FILES["image_$i"] ?? []);
        }

        $stmt = db()->prepare("INSERT INTO products
            (category_id, title, description, price, image_1, image_2, image_3, image_4, is_active)
            VALUES (:category_id, :title, :description, :price, :image_1, :image_2, :image_3, :image_4, :is_active)");
        $stmt->execute([
            'category_id' => $categoryId,
            'title'       => $title,
            'description' => $description,
            'price'       => number_format((float)$price, 2, '.', ''),
            'image_1'     => $images[1],
            'image_2'     => $images[2],
            'image_3'     => $images[3],
            'image_4'     => $images[4],
            'is_active'   => $isActive,
        ]);

        flash('success', 'Produto cadastrado com sucesso.');
        redirect('/admin/products.php');
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Novo produto - <?= e(STORE_NAME) ?></title>
    <link rel="stylesheet" href="/assets/css/admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
</head>
<body>
<?php require __DIR__ . '/partials_nav.php'; ?>
<main class="admin-main form-page">
    <h1>📦 Novo produto</h1>
    <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
    <form method="post" enctype="multipart/form-data" class="admin-form">
        <?php require __DIR__ . '/product_form.php'; ?>
        <div class="form-actions">
            <button type="submit">Salvar produto</button>
            <a href="/admin/products.php">Cancelar</a>
        </div>
    </form>
</main>
<script src="/assets/js/admin.js"></script>
</body>
</html>
