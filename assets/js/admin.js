/**
 * Bazar Mix da Jô — JavaScript administrativo
 * Validação de upload, preview de imagens e utilitários do painel.
 */

const MAX_IMAGE_SIZE_BYTES = 20 * 1024 * 1024; // 20 MB
const ALLOWED_TYPES = ['image/jpeg', 'image/png', 'image/webp'];

document.addEventListener('DOMContentLoaded', () => {

  /* ===== Botões Câmera / Galeria para cada slot de foto ===== */
  document.querySelectorAll('[data-upload-source]').forEach((button) => {
    button.addEventListener('click', () => {
      const slot = button.closest('.image-field');
      if (!slot) return;
      const input = slot.querySelector('input[type="file"][data-file-input]');
      if (!input) return;

      if (button.dataset.uploadSource === 'camera') {
        input.setAttribute('capture', 'environment');
      } else {
        input.removeAttribute('capture');
      }
      input.click();
    });
  });

  /* ===== Validação e preview de imagens no upload ===== */
  document.querySelectorAll('input[type="file"]').forEach((input) => {
    input.addEventListener('change', () => {
      const file = input.files[0];
      const slot = input.closest('.image-field');
      const filenameEl = slot ? slot.querySelector('[data-upload-filename]') : null;

      if (!file) {
        if (filenameEl) filenameEl.textContent = '';
        return;
      }

      if (file.size > MAX_IMAGE_SIZE_BYTES) {
        alert('Cada imagem deve ter no máximo 20 MB.');
        input.value = '';
        if (filenameEl) filenameEl.textContent = '';
        return;
      }

      if (!ALLOWED_TYPES.includes(file.type)) {
        alert('Formatos permitidos: JPG, JPEG, PNG e WEBP.');
        input.value = '';
        if (filenameEl) filenameEl.textContent = '';
        return;
      }

      if (filenameEl) {
        const sizeMB = (file.size / (1024 * 1024)).toFixed(1);
        filenameEl.textContent = `${file.name} (${sizeMB} MB)`;
      }

      if (!slot) return;
      let preview = slot.querySelector('.upload-preview');
      if (!preview) {
        preview = document.createElement('img');
        preview.className = 'upload-preview';
        preview.style.cssText = 'width:96px;height:96px;object-fit:cover;border-radius:8px;border:2px solid #F0D8DC;margin-top:8px;';
        slot.appendChild(preview);
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
      label.style.opacity = checkbox.checked ? '1' : '0.6';
    }
  });

});
