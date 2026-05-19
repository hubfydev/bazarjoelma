<?php
/**
 * Bazar Mix da Jô — Página pública (vitrine de produtos)
 *
 * Exibe produtos ativos de categorias ativas em cards estilo marketplace.
 * Botão "Mais Detalhes" redireciona para o WhatsApp com mensagem automática.
 */

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/helpers.php';

/* ---------- Filtros recebidos via GET ---------- */
$search     = trim($_GET['q'] ?? '');
$categoryId = (int)($_GET['category'] ?? 0);
$minPrice   = trim($_GET['min_price'] ?? '');
$maxPrice   = trim($_GET['max_price'] ?? '');

/* ---------- Categorias ativas (para filtros) ---------- */
$categories = db()->query("SELECT id, name FROM categories WHERE status = 1 ORDER BY name")->fetchAll();

/* ---------- Montagem da consulta com filtros ---------- */
$where  = ['p.is_active = 1', 'c.status = 1'];
$params = [];

if ($search !== '') {
    $where[]          = '(p.title LIKE :search OR p.description LIKE :search)';
    $params['search'] = '%' . $search . '%';
}

if ($categoryId > 0) {
    $where[]              = 'p.category_id = :category_id';
    $params['category_id'] = $categoryId;
}

if ($minPrice !== '' && is_numeric($minPrice)) {
    $where[]             = 'p.price >= :min_price';
    $params['min_price'] = number_format((float)$minPrice, 2, '.', '');
}

if ($maxPrice !== '' && is_numeric($maxPrice)) {
    $where[]             = 'p.price <= :max_price';
    $params['max_price'] = number_format((float)$maxPrice, 2, '.', '');
}

$sql = "SELECT p.*, c.name AS category_name
        FROM products p
        INNER JOIN categories c ON c.id = p.category_id
        WHERE " . implode(' AND ', $where) . "
        ORDER BY p.created_at DESC";
$stmt = db()->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

$hasFilters  = $search !== '' || $categoryId > 0 || $minPrice !== '' || $maxPrice !== '';
$totalResults = count($products);
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= e(STORE_NAME) ?> — Produtos selecionados com carinho. Roupas, bolsas, sapatos, acessórios e muito mais. Contato direto pelo WhatsApp!">
    <meta name="theme-color" content="#D7192D">
    <title><?= e(STORE_NAME) ?> — Produtos selecionados com carinho</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <!-- Preconnect para Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
</head>
<body>

    <!-- ===== Header ===== -->
    <header class="site-header">
        <div class="container header-inner">
            <a class="brand" href="/">🛍️ <?= e(STORE_NAME) ?></a>
            <a class="admin-link" href="/admin/login.php">Admin</a>
        </div>
    </header>

    <main>
        <!-- ===== Faixa de busca ===== -->
        <section class="search-band">
            <div class="container">
                <h1>Achadinhos selecionados com carinho <span class="emoji">💖</span></h1>
                <form id="filter-form" class="filter-form" method="get" action="/">
                    <input type="search" name="q" placeholder="🔍 Buscar por produto ou descrição..." value="<?= e($search) ?>">
                    <select name="category">
                        <option value="0">Todas as categorias</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= (int)$cat['id'] ?>" <?= $categoryId === (int)$cat['id'] ? 'selected' : '' ?>>
                                <?= e($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="number" name="min_price" min="0" step="0.01" placeholder="Preço mín." value="<?= e($minPrice) ?>">
                    <input type="number" name="max_price" min="0" step="0.01" placeholder="Preço máx." value="<?= e($maxPrice) ?>">
                    <button type="submit">Filtrar</button>
                    <?php if ($hasFilters): ?>
                        <a class="clear-filters" href="/">✕ Limpar</a>
                    <?php endif; ?>
                </form>
            </div>
        </section>

        <!-- ===== Filtros rápidos por categoria (pills) ===== -->
        <?php if (count($categories) > 0): ?>
        <section class="category-pills">
            <div class="container">
                <div class="category-pills-inner">
                    <a class="pill <?= $categoryId === 0 && $search === '' ? 'active' : '' ?>" href="/">Todos</a>
                    <?php foreach ($categories as $cat): ?>
                        <a class="pill <?= $categoryId === (int)$cat['id'] ? 'active' : '' ?>"
                           href="/?category=<?= (int)$cat['id'] ?>"><?= e($cat['name']) ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- ===== Grid de produtos ===== -->
        <section class="container product-section">
            <?php if ($totalResults > 0 && $hasFilters): ?>
                <p class="results-count"><strong><?= $totalResults ?></strong> produto<?= $totalResults !== 1 ? 's' : '' ?> encontrado<?= $totalResults !== 1 ? 's' : '' ?></p>
            <?php endif; ?>

            <?php if ($totalResults === 0): ?>
                <div class="empty-state">
                    <?= $hasFilters
                        ? 'Nenhum produto encontrado para sua busca.'
                        : 'Nenhum produto encontrado no momento.' ?>
                </div>
            <?php else: ?>
                <div class="product-grid">
                    <?php foreach ($products as $product): ?>
                        <?php
                        $images  = product_images($product);
                        $price   = format_price($product['price']);
                        $message = "Oi, bem vindo ao meu Bazar! Me chamo Joelma e você se interessou pelo produto {$product['title']} pelo preço de {$price}. Logo mais entro em contato com você!";
                        $waLink  = 'https://wa.me/' . WHATSAPP_NUMBER . '?text=' . rawurlencode($message);
                        ?>
                        <article class="product-card" data-gallery>
                            <!-- Título -->
                            <h2><?= e($product['title']) ?></h2>

                            <!-- Galeria de fotos -->
                            <div class="gallery">
                                <?php if (count($images) > 0): ?>
                                    <?php foreach ($images as $index => $image): ?>
                                        <img class="<?= $index === 0 ? 'active' : '' ?>"
                                             src="/uploads/products/<?= e($image) ?>"
                                             alt="<?= e($product['title']) ?>"
                                             loading="lazy"
                                             data-gallery-image>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="no-image">Sem foto</div>
                                <?php endif; ?>

                                <?php if (count($images) > 1): ?>
                                    <button class="gallery-arrow prev" type="button" aria-label="Foto anterior" data-gallery-prev>‹</button>
                                    <button class="gallery-arrow next" type="button" aria-label="Próxima foto" data-gallery-next>›</button>
                                <?php endif; ?>
                            </div>

                            <!-- Dots indicadores -->
                            <?php if (count($images) > 1): ?>
                                <div class="gallery-dots" aria-hidden="true">
                                    <?php foreach ($images as $index => $image): ?>
                                        <span class="<?= $index === 0 ? 'active' : '' ?>" data-gallery-dot></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <!-- Conteúdo do card -->
                            <div class="card-body">
                                <span class="category-tag"><?= e($product['category_name']) ?></span>
                                <p class="description"><?= e($product['description']) ?></p>
                            </div>

                            <!-- Rodapé do card: preço + botão WhatsApp -->
                            <div class="card-footer">
                                <div>
                                    <span class="price-label">Preço</span>
                                    <strong class="price"><?= e($price) ?></strong>
                                </div>
                                <a class="whatsapp-btn" target="_blank" rel="noopener noreferrer" href="<?= e($waLink) ?>">
                                    <!-- WhatsApp SVG icon -->
                                    <svg class="wa-icon" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                    </svg>
                                    Mais Detalhes
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
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
