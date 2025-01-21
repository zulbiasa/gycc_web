<div class="footer">
    <p>&copy; 2024 GYCC+. All rights reserved. <a href="#">Privacy Policy</a></p>
</div>
<script>
    let lastScrollTop = 0; // Store the last scroll position
    const footer = document.querySelector('.footer'); // Select the footer element

    window.addEventListener('scroll', function() {
        let currentScroll = window.pageYOffset || document.documentElement.scrollTop;

        if (currentScroll > lastScrollTop) {
            // Scroll Down: Hide Footer
            footer.style.transform = 'translateY(100%)'; // Move the footer out of view
        } else {
            // Scroll Up: Show Footer
            footer.style.transform = 'translateY(0)'; // Move the footer back into view
        }

        lastScrollTop = currentScroll <= 0 ? 0 : currentScroll; // Ensure scroll position doesn't go negative
    });
</script>