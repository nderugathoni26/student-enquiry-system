document.addEventListener('DOMContentLoaded', () => {
    const getStartedLinks = document.querySelectorAll('.btn-role');

    getStartedLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault(); // Prevent the default action of the link

            // Show the loader
            document.getElementById('loader').style.display = 'block';

            // Simulate a delay (e.g., 3 seconds) before redirecting
            setTimeout(() => {
                // Redirect to the target page after delay
                window.location.href = this.href;
            }, 3000); // 3 seconds delay
        });
    });
});