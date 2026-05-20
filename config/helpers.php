<?php
declare(strict_types=1);

session_start();

const STORE_NAME = 'Bazar Mix da Jô';
const WHATSAPP_NUMBER = '13529890272';
const MAX_IMAGE_SIZE = 20 * 1024 * 1024; // 20 MB por foto.

function e(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): never
{
    header('Location: ' . $path);
    exit;
}

function is_logged_in(): bool
{
    return isset($_SESSION['admin_id']);
}

function require_admin(): void
{
    if (!is_logged_in()) {
        redirect('/admin/login.php');
    }
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    return $flash;
}

function format_price(float|string $price): string
{
    return '$' . number_format((float)$price, 2, '.', ',');
}

function upload_product_image(array $file): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Erro ao enviar uma das imagens.');
    }

    if ($file['size'] > MAX_IMAGE_SIZE) {
        throw new RuntimeException('Cada imagem deve ter no máximo 20 MB.');
    }

    $tmpPath = $file['tmp_name'];
    $info = getimagesize($tmpPath);

    if ($info === false) {
        throw new RuntimeException('Envie apenas arquivos de imagem válidos.');
    }

    $allowed = [
        IMAGETYPE_JPEG => 'jpg',
        IMAGETYPE_PNG => 'png',
        IMAGETYPE_WEBP => 'webp',
    ];

    if (!isset($allowed[$info[2]])) {
        throw new RuntimeException('Formatos permitidos: JPG, JPEG, PNG e WEBP.');
    }

    $uploadDir = dirname(__DIR__) . '/uploads/products';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $filename = bin2hex(random_bytes(16)) . '.' . $allowed[$info[2]];
    $destination = $uploadDir . '/' . $filename;

    if (!move_uploaded_file($tmpPath, $destination)) {
        throw new RuntimeException('Não foi possível salvar a imagem enviada.');
    }

    return $filename;
}

function delete_product_image(?string $filename): void
{
    if (!$filename) {
        return;
    }

    $path = dirname(__DIR__) . '/uploads/products/' . basename($filename);
    if (is_file($path)) {
        unlink($path);
    }
}

function product_images(array $product): array
{
    return array_values(array_filter([
        $product['image_1'] ?? null,
        $product['image_2'] ?? null,
        $product['image_3'] ?? null,
        $product['image_4'] ?? null,
    ]));
}
