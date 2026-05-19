<label>Nome da categoria
    <input type="text" name="name" required value="<?= e($category['name'] ?? '') ?>">
</label>
<label class="check-line">
    <input type="checkbox" name="status" value="1" <?= (int)($category['status'] ?? 1) === 1 ? 'checked' : '' ?>>
    Categoria ativa na loja
</label>
