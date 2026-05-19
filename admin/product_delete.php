<?php
require_once __DIR__ . '/auth.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = db()->prepare('SELECT * FROM products WHERE id = :id');
$stmt->execute(['id' => $id]);
$product = $stmt->fetch();

if ($product) {
    foreach (product_images($product) as $image) {
        delete_product_image($image);
    }

    $delete = db()->prepare('DELETE FROM products WHERE id = :id');
    $delete->execute(['id' => $id]);
    flash('success', 'Produto excluído com sucesso.');
}

redirect('/admin/products.php');
