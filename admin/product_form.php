<?php
/**
 * Formulário compartilhado para criar/editar produto.
 * Usa variável $product (se existir) para preencher campos na edição.
 */

$isEdit = isset($product);
$categories = db()->query('SELECT id, name FROM categories WHERE status = 1 ORDER BY name')->fetchAll();
?>
<label>Título do produto
    <input type="text" name="title" required value="<?= e($product['title'] ?? '') ?>" placeholder="Ex: Bolsa Vermelha Estilosa">
</label>

<label>Descrição
    <textarea name="description" rows="4" required placeholder="Descreva o produto: material, tamanho, cor..."><?= e($product['description'] ?? '') ?></textarea>
</label>

<div class="form-grid">
    <label>Preço em dólar (USD)
        <input type="number" name="price" min="0" step="0.01" required value="<?= e($product['price'] ?? '') ?>" placeholder="25.00">
    </label>
    <label>Categoria
        <select name="category_id" required>
            <option value="">Selecione uma categoria</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= (int)$category['id'] ?>" <?= (int)($product['category_id'] ?? 0) === (int)$category['id'] ? 'selected' : '' ?>>
                    <?= e($category['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>
</div>

<label class="check-line">
    <input type="checkbox" name="is_active" value="1" <?= (int)($product['is_active'] ?? 1) === 1 ? 'checked' : '' ?>>
    Produto ativo na loja (visível para clientes)
</label>

<fieldset>
    <legend>📸 Fotos do produto</legend>
    <p class="hint">Envie até 4 fotos nos formatos JPG, PNG ou WEBP. Máximo de 2 MB por foto. A primeira foto será a imagem principal.</p>
    <?php for ($i = 1; $i <= 4; $i++): ?>
        <?php $image = $product["image_$i"] ?? null; ?>
        <div class="image-field">
            <label>Foto <?= $i ?><?= $i === 1 ? ' (principal)' : '' ?>
                <input type="file" name="image_<?= $i ?>" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
            </label>
            <?php if ($image): ?>
                <div class="current-image">
                    <img src="/uploads/products/<?= e($image) ?>" alt="Foto atual <?= $i ?>">
                    <label class="check-line">
                        <input type="checkbox" name="remove_image_<?= $i ?>" value="1">
                        Remover esta foto
                    </label>
                </div>
            <?php endif; ?>
        </div>
    <?php endfor; ?>
</fieldset>
