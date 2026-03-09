// Animated Marquee JS logic (Optional if CSS isn't enough, but CSS handles it well here)
document.addEventListener('DOMContentLoaded', () => {
    // Basic interaction for video placeholders
    const videoCards = document.querySelectorAll('.pella-video-placeholder');
    videoCards.forEach(card => {
        card.addEventListener('click', () => {
            alert('Video player modal would open here.');
        });
    });
});