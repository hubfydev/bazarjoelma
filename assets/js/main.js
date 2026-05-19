/**
 * Bazar Mix da Jô — JavaScript principal (loja pública)
 * Galeria de fotos, scroll-to-top, swipe touch
 */

document.addEventListener('DOMContentLoaded', () => {

  /* ===== Galeria de fotos nos cards ===== */
  document.querySelectorAll('[data-gallery]').forEach((card) => {
    const images = Array.from(card.querySelectorAll('[data-gallery-image]'));
    const dots   = Array.from(card.querySelectorAll('[data-gallery-dot]'));
    const prev   = card.querySelector('[data-gallery-prev]');
    const next   = card.querySelector('[data-gallery-next]');
    const gallery = card.querySelector('.gallery');
    let current  = 0;

    function show(index) {
      if (images.length === 0) return;
      current = (index + images.length) % images.length;
      images.forEach((img, i) => img.classList.toggle('active', i === current));
      dots.forEach((dot, i) => dot.classList.toggle('active', i === current));
    }

    // Setas
    if (prev) prev.addEventListener('click', () => show(current - 1));
    if (next) next.addEventListener('click', () => show(current + 1));

    // Dots clicáveis
    dots.forEach((dot, i) => {
      dot.addEventListener('click', () => show(i));
    });

    // Suporte a swipe touch na galeria
    if (gallery && images.length > 1) {
      let startX = 0;
      let startY = 0;
      let tracking = false;

      gallery.addEventListener('touchstart', (e) => {
        startX = e.touches[0].clientX;
        startY = e.touches[0].clientY;
        tracking = true;
      }, { passive: true });

      gallery.addEventListener('touchmove', (e) => {
        if (!tracking) return;
        const dx = e.touches[0].clientX - startX;
        const dy = e.touches[0].clientY - startY;

        // Se o swipe for mais horizontal que vertical, prevenir scroll
        if (Math.abs(dx) > Math.abs(dy) && Math.abs(dx) > 10) {
          e.preventDefault();
        }
      }, { passive: false });

      gallery.addEventListener('touchend', (e) => {
        if (!tracking) return;
        tracking = false;
        const dx = e.changedTouches[0].clientX - startX;

        // Mínimo de 40px para considerar swipe
        if (Math.abs(dx) > 40) {
          if (dx < 0) show(current + 1);
          else show(current - 1);
        }
      }, { passive: true });
    }
  });

  /* ===== Botão "voltar ao topo" ===== */
  const scrollBtn = document.querySelector('.scroll-top');
  if (scrollBtn) {
    const threshold = 400;

    window.addEventListener('scroll', () => {
      scrollBtn.classList.toggle('visible', window.scrollY > threshold);
    }, { passive: true });

    scrollBtn.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  /* ===== Animação de entrada dos cards ao scroll ===== */
  if ('IntersectionObserver' in window) {
    const cards = document.querySelectorAll('.product-card');

    // Remove animação CSS padrão, aplicar via observer
    cards.forEach((card) => {
      card.style.opacity = '0';
      card.style.transform = 'translateY(20px)';
      card.style.animation = 'none';
    });

    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const card = entry.target;
          card.style.transition = 'opacity 400ms ease, transform 400ms ease';
          card.style.opacity = '1';
          card.style.transform = 'translateY(0)';
          observer.unobserve(card);
        }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

    cards.forEach((card, index) => {
      // Delay escalonado
      card.style.transitionDelay = `${Math.min(index * 60, 400)}ms`;
      observer.observe(card);
    });
  }

});
