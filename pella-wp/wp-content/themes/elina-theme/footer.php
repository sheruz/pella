<footer class="site-footer">
    <div class="container">
        <div class="footer-left">
            &copy; <?php echo date('Y'); ?> Elina Szmrtyka
        </div>
        <div class="footer-center">
            Designed by PellaNova
        </div>
        <div class="footer-right">
            All rights reserved
        </div>
    </div>
</footer>

<script>
(function () {
  const MOBILE_MAX = 767;

  function ensureNameSpans(h1) {
    const has = h1.querySelector(".hero-first, .hero-last");
    if (has) return;

    let raw = (h1.textContent || "").trim();
    if (!raw) return;

    const parts = raw.split(/\s+/);
    if (parts.length < 2) return;

    const first = parts.shift();
    const last = parts.join(" ");

    h1.innerHTML = `
      <span class="hero-first">${first}</span>
      <span> </span>
      <span class="hero-last">${last}</span>
    `;
  }

  function getNameText(h1) {
    const first = h1.querySelector(".hero-first")?.textContent?.trim() || "";
    const last  = h1.querySelector(".hero-last")?.textContent?.trim() || "";
    const full = (first + " " + last).trim();
    return full;
  }

  function buildOverlay(h1) {
    const old = h1.querySelector(".fw-overlay");
    if (old) old.remove();

    const nameText = getNameText(h1);
    if (!nameText) return;

    const overlay = document.createElement("div");
    overlay.className = "fw-overlay";

    nameText.split("").forEach(ch => {
      const s = document.createElement("span");
      s.innerHTML = (ch === " ") ? "&nbsp;" : ch;
      overlay.appendChild(s);
    });

    h1.style.position = "relative";
    h1.appendChild(overlay);
  }

  function applyAll() {
    const isMobile = window.innerWidth <= MOBILE_MAX;

    document.querySelectorAll(".full-width-spacing").forEach(widget => {
      const h1 = widget.querySelector(".elementor-heading-title");
      if (!h1) return;

      if (isMobile) {
        widget.classList.remove("has-fw-overlay");
        h1.querySelector(".fw-overlay")?.remove();
        return;
      }

      ensureNameSpans(h1);
      buildOverlay(h1);

      if (h1.querySelector(".fw-overlay")) widget.classList.add("has-fw-overlay");
    });
  }

  document.addEventListener("DOMContentLoaded", applyAll);
  window.addEventListener("load", applyAll);
  window.addEventListener("resize", applyAll);
})();
</script>

<script>
// Scroll Animation for Certificates and Media Items
(function() {
  function isElementInViewport(el) {
    const rect = el.getBoundingClientRect();
    return (
      rect.top >= 0 &&
      rect.left >= 0 &&
      rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
      rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
  }

  function isElementPartiallyVisible(el) {
    const rect = el.getBoundingClientRect();
    const windowHeight = window.innerHeight || document.documentElement.clientHeight;
    return rect.top < windowHeight && rect.bottom > 0;
  }

  function handleScrollAnimation() {
    // Animate certificate items
    document.querySelectorAll('.certificate-item').forEach((item, index) => {
      if (isElementPartiallyVisible(item) && !item.classList.contains('visible')) {
        setTimeout(() => {
          item.classList.add('visible');
        }, index * 150); // Stagger animation
      }
    });

    // Animate media items
    document.querySelectorAll('.scroll-fade-in-item').forEach((item, index) => {
      if (isElementPartiallyVisible(item) && !item.classList.contains('visible')) {
        setTimeout(() => {
          item.classList.add('visible');
        }, index * 100); // Stagger animation
      }
    });
  }

  // Run on scroll and on load
  window.addEventListener('scroll', handleScrollAnimation);
  window.addEventListener('load', handleScrollAnimation);
  document.addEventListener('DOMContentLoaded', handleScrollAnimation);
})();
</script>

<script>
// Video Modal Functionality
(function() {
  const videoModal = document.getElementById('video-modal');
  const modalVideo = document.getElementById('modal-video');
  const modalClose = document.querySelector('.video-modal-close');
  const modalOverlay = document.querySelector('.video-modal-overlay');
  const videoItems = document.querySelectorAll('.video-item');

  function openVideoModal(videoUrl) {
    modalVideo.src = videoUrl;
    videoModal.classList.add('active');
    document.body.style.overflow = 'hidden'; // Prevent background scrolling
    modalVideo.play();
  }

  function closeVideoModal() {
    videoModal.classList.remove('active');
    modalVideo.pause();
    modalVideo.src = '';
    document.body.style.overflow = ''; // Restore scrolling
  }

  // Add click event to video items
  videoItems.forEach(item => {
    item.addEventListener('click', function(e) {
      e.preventDefault();
      const videoUrl = this.getAttribute('data-video-url');
      if (videoUrl) {
        openVideoModal(videoUrl);
      }
    });
  });

  // Close modal on close button click
  if (modalClose) {
    modalClose.addEventListener('click', function(e) {
      e.stopPropagation();
      closeVideoModal();
    });
  }

  // Close modal on overlay click
  if (modalOverlay) {
    modalOverlay.addEventListener('click', function(e) {
      if (e.target === modalOverlay) {
        closeVideoModal();
      }
    });
  }

  // Close modal on Escape key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && videoModal.classList.contains('active')) {
      closeVideoModal();
    }
  });
})();
</script>

<?php wp_footer(); ?>
</body>
</html>
