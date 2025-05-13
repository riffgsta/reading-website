
   
        // Dark/light toggle
        document.getElementById('toggleTheme').addEventListener('click', () => {
            const html = document.documentElement;
            const current = html.getAttribute('data-bs-theme');
            const newTheme = current === 'light' ? 'dark' : 'light';
            html.setAttribute('data-bs-theme', newTheme);
            console.log('Theme switched to:', newTheme); // Debugging
        });

        // Search filter
        document.getElementById('searchInput').addEventListener('input', function () {
            const query = this.value.toLowerCase();
            document.querySelectorAll('.book-item').forEach(card => {
                const text = card.querySelector('.card-title').innerText.toLowerCase();
                const quote = card.querySelector('.card-text').innerText.toLowerCase();
                if (text.includes(query) || quote.includes(query)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // Read more button - Scroll ke container buku
        document.getElementById("readMoreBtn").addEventListener("click", function () {
            const bookGrid = document.getElementById("bookGrid");
            bookGrid.scrollIntoView({ behavior: "smooth", block: "start" });
        });
