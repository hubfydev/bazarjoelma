<?php
require_once __DIR__ . '/auth.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = db()->prepare('DELETE FROM categories WHERE id = :id');
$stmt->execute(['id' => $id]);
flash('success', 'Categoria excluída com sucesso.');

redirect('/admin/categories.php');
