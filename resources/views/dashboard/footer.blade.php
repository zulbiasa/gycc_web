<div class="footer">
    <p>© 2024 GYCC+. All rights reserved. <a href="#">Privacy Policy</a></p>
</div>
<script>
    let lastScrollTop = 0; // Store the last scroll position
    const footer = document.querySelector('.footer'); // Select the footer element
    let timeoutId; // Variable to store the timeout ID

    function hideFooter() {
       footer.style.transform = 'translateY(100%)'; // Move the footer out of view
    }

    function showFooter() {
       footer.style.transform = 'translateY(0)'; // Move the footer back into view
    }

     function resetTimeout(){
        clearTimeout(timeoutId);
        timeoutId = setTimeout(hideFooter, 1500); // Set a 2 second timer
    }


    window.addEventListener('scroll', function() {
        let currentScroll = window.pageYOffset || document.documentElement.scrollTop;

        if (currentScroll > lastScrollTop) {
            // Scroll Down: Hide Footer
            hideFooter();
        } else {
            // Scroll Up: Show Footer
             showFooter();
              resetTimeout();
        }
          lastScrollTop = currentScroll <= 0 ? 0 : currentScroll; // Ensure scroll position doesn't go negative
    });

    // Initial show and timeout
    showFooter();
    resetTimeout();
</script>