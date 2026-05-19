/**
 * Bazar Mix da Jô — JavaScript administrativo
 * Validação de upload, preview de imagens e utilitários do painel
 */

document.addEventListener('DOMContentLoaded', () => {

  /* ===== Validação e preview de imagens no upload ===== */
  document.querySelectorAll('input[type="file"]').forEach((input) => {
    input.addEventListener('change', () => {
      const file = input.files[0];
      if (!file) return;

      // Validar tamanho (máximo 2 MB)
      if (file.size > 2 * 1024 * 1024) {
        alert('Cada imagem deve ter no máximo 2 MB.');
        input.value = '';
        return;
      }

      // Validar tipo
      const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
      if (!allowedTypes.includes(file.type)) {
        alert('Formatos permitidos: JPG, JPEG, PNG e WEBP.');
        input.value = '';
        return;
      }

      // Preview da imagem selecionada
      const container = input.closest('.image-field');
      if (!container) return;

      let preview = container.querySelector('.upload-preview');
      if (!preview) {
        preview = document.createElement('img');
        preview.className = 'upload-preview';
        preview.style.cssText = 'width:96px;height:96px;object-fit:cover;border-radius:8px;border:2px solid #F0D8DC;margin-top:8px;';
        container.appendChild(preview);
      }

      const reader = new FileReader();
      reader.onload = (e) => { preview.src = e.target.result; };
      reader.readAsDataURL(file);
    });
  });

  /* ===== Confirmação ao marcar "remover foto" ===== */
  document.querySelectorAll('input[name^="remove_image_"]').forEach((checkbox) => {
    checkbox.addEventListener('change', function () {
      if (this.checked && !confirm('Deseja remover esta foto?')) {
        this.checked = false;
      }
    });
  });

  /* ===== Toggle de ativo/desativado com feedback visual ===== */
  document.querySelectorAll('.check-line input[type="checkbox"]').forEach((checkbox) => {
    const label = checkbox.closest('.check-line');
    if (label) {
      checkbox.addEventListener('change', () => {
        label.style.opacity = checkbox.checked ? '1' : '0.6';
      });
      // Estado inicial
      label.style.opacity = checkbox.checked ? '1' : '0.6';
    }
  });

});
