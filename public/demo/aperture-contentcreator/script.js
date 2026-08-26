/* Small, dependency-free motion layer: reveal-on-scroll, hero aura, and magnetic CTAs. */
(() => {
  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const revealItems = document.querySelectorAll('.reveal');

  if (reduceMotion) {
    revealItems.forEach((item) => item.classList.add('is-visible'));
  } else {
    const revealObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
      });
    }, { threshold: 0.14, rootMargin: '0px 0px -4% 0px' });

    revealItems.forEach((item) => revealObserver.observe(item));
  }

  const hero = document.querySelector('.hero');
  const glow = document.querySelector('.hero-glow');
  if (hero && glow && !reduceMotion) {
    hero.addEventListener('pointermove', (event) => {
      const bounds = hero.getBoundingClientRect();
      const x = ((event.clientX - bounds.left) / bounds.width - 0.5) * 26;
      const y = ((event.clientY - bounds.top) / bounds.height - 0.5) * 20;
      glow.style.transform = `translate3d(${x}px, ${y}px, 0) rotate(-18deg)`;
    }, { passive: true });

    hero.addEventListener('pointerleave', () => {
      glow.style.transform = 'translate3d(0, 0, 0) rotate(-18deg)';
    });
  }

  if (!reduceMotion) {
    document.querySelectorAll('.magnetic').forEach((button) => {
      button.addEventListener('pointermove', (event) => {
        const bounds = button.getBoundingClientRect();
        const x = (event.clientX - bounds.left - bounds.width / 2) * 0.12;
        const y = (event.clientY - bounds.top - bounds.height / 2) * 0.12;
        button.style.transform = `translate3d(${x}px, ${y}px, 0)`;
      }, { passive: true });

      button.addEventListener('pointerleave', () => {
        button.style.transform = 'translate3d(0, 0, 0)';
      });
    });
  }

  const mediaKitLink = document.querySelector('a[download]');
  if (mediaKitLink) {
    mediaKitLink.addEventListener('click', (event) => {
      event.preventDefault();
      const pageContent = 'BT /F1 24 Tf 72 700 Td (ARI VALE STUDIO) Tj /F1 12 Tf 0 -42 Td (Visual storyteller / filmmaker / culture chronicler) Tj 0 -32 Td (Partnerships: dummy@example.invalid) Tj 0 -32 Td (Based in NYC / Everywhere) Tj ET';
      const objects = [
        '<< /Type /Catalog /Pages 2 0 R >>',
        '<< /Type /Pages /Kids [3 0 R] /Count 1 >>',
        '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Resources << /Font << /F1 5 0 R >> >> /Contents 4 0 R >>',
        `<< /Length ${pageContent.length} >>\nstream\n${pageContent}\nendstream`,
        '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>'
      ];
      let pdf = '%PDF-1.4\n';
      const offsets = [0];
      objects.forEach((object, index) => {
        offsets.push(pdf.length);
        pdf += `${index + 1} 0 obj\n${object}\nendobj\n`;
      });
      const xrefOffset = pdf.length;
      pdf += `xref\n0 ${objects.length + 1}\n0000000000 65535 f \n`;
      offsets.slice(1).forEach((offset) => {
        pdf += `${String(offset).padStart(10, '0')} 00000 n \n`;
      });
      pdf += `trailer\n<< /Size ${objects.length + 1} /Root 1 0 R >>\nstartxref\n${xrefOffset}\n%%EOF`;
      const blobUrl = URL.createObjectURL(new Blob([pdf], { type: 'application/pdf' }));
      const downloadLink = document.createElement('a');
      downloadLink.href = blobUrl;
      downloadLink.download = 'ari-vale-media-kit.pdf';
      downloadLink.click();
      URL.revokeObjectURL(blobUrl);
    });
  }
})();
