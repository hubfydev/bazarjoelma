<?php
/**
 * Bazar Mix da Jô — Página de detalhes do produto
 *
 * Exibe informações completas do produto em estilo Amazon:
 * - Foto principal grande com miniaturas abaixo
 * - Carousel de fotos com swipe
 * - Título em destaque (grande, preto)
 * - Preço em vermelho, negrito, grande
 * - Descrição completa à esquerda
 * - Botão "Tenho Interesse" (verde) para WhatsApp
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/helpers.php';

$productId = (int)($_GET['id'] ?? 0);

if ($productId <= 0) {
    http_response_code(404);
    header('Location: /');
    exit;
}

$stmt = db()->prepare("
    SELECT p.*, c.name AS category_name
    FROM products p
    LEFT JOIN categories c ON c.id = p.category_id
    WHERE p.id = :id AND p.is_active = 1
");
$stmt->execute(['id' => $productId]);
$product = $stmt->fetch();

if (!$product || !$product['category_id']) {
    http_response_code(404);
    header('Location: /');
    exit;
}

$images = product_images($product);
$price = format_price($product['price']);
$message = "Oi, bem vindo ao meu Bazar! Me chamo Joelma e você se interessou pelo produto {$product['title']} pelo preço de {$price}. Logo mais entro em contato com você!";
$waLink = 'https://wa.me/' . WHATSAPP_NUMBER . '?text=' . rawurlencode($message);
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= e($product['title']) ?> — <?= e($product['description']) ?>">
    <meta name="theme-color" content="#D7192D">
    <title><?= e($product['title']) ?> — <?= e(STORE_NAME) ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
</head>
<body>

    <!-- ===== Header ===== -->
    <header class="site-header">
        <div class="container header-inner">
            <a class="brand" href="/">🛍️ <?= e(STORE_NAME) ?></a>
        </div>
    </header>

    <main>
        <!-- ===== Breadcrumb ===== -->
        <nav class="breadcrumb">
            <div class="container">
                <a href="/">Início</a>
                <span class="separator">/</span>
                <a href="/?category=<?= (int)$product['category_id'] ?>"><?= e($product['category_name']) ?></a>
                <span class="separator">/</span>
                <span class="current"><?= e($product['title']) ?></span>
            </div>
        </nav>

        <!-- ===== Conteúdo do produto ===== -->
        <section class="product-detail-section">
            <div class="container">
                <div class="detail-layout">

                    <!-- LEFT: Galeria de fotos -->
                    <div class="detail-gallery-wrapper">
                        <div class="detail-gallery" data-detail-gallery>
                            <?php if (count($images) > 0): ?>
                                <?php foreach ($images as $index => $image): ?>
                                    <img class="<?= $index === 0 ? 'active' : '' ?>"
                                         src="/uploads/products/<?= e($image) ?>"
                                         alt="<?= e($product['title']) ?>"
                                         data-detail-gallery-image>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="no-image-large">
                                    <div>📷</div>
                                    <p>Sem foto disponível</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Miniaturas -->
                        <?php if (count($images) > 0): ?>
                            <div class="detail-thumbnails">
                                <?php foreach ($images as $index => $image): ?>
                                    <button class="thumbnail <?= $index === 0 ? 'active' : '' ?>"
                                            type="button"
                                            data-detail-thumbnail
                                            data-index="<?= $index ?>"
                                            aria-label="Ver foto <?= $index + 1 ?>">
                                        <img src="/uploads/products/<?= e($image) ?>"
                                             alt="Miniatura <?= $index + 1 ?>">
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- RIGHT: Informações do produto -->
                    <div class="detail-info">
                        <span class="detail-category"><?= e($product['category_name']) ?></span>
                        <h1 class="detail-title"><?= e($product['title']) ?></h1>

                        <div class="detail-price-box">
                            <span class="detail-price-label">Preço</span>
                            <span class="detail-price"><?= e($price) ?></span>
                        </div>

                        <div class="detail-description">
                            <h2 class="detail-desc-title">Descrição do Produto</h2>
                            <p class="detail-desc-text"><?= nl2br(e($product['description'])) ?></p>
                        </div>

                        <div class="detail-actions">
                            <a class="detail-interest-btn" href="<?= e($waLink) ?>" target="_blank" rel="noopener noreferrer">
                                <svg class="wa-icon" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                                Tenho Interesse
                            </a>

                            <a class="detail-back-btn" href="/">← Voltar para a loja</a>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </main>

    <!-- ===== Footer ===== -->
    <footer class="site-footer">
        <?= e(STORE_NAME) ?> — Produtos selecionados com <span class="footer-heart">❤️</span> carinho
    </footer>

    <!-- Botão voltar ao topo -->
    <button class="scroll-top" type="button" aria-label="Voltar ao topo">↑</button>

    <script src="/assets/js/main.js"></script>
</body>
</html>
