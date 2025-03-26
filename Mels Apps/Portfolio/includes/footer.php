        </main>
    </div>

    <script>
        const hamburger = document.querySelector('.hamburger');
        const mobileNav = document.querySelector('.mobile-nav');

        hamburger.addEventListener('click', () => {
            mobileNav.classList.toggle('active');
        });

        document.addEventListener('click', (e) => {
            if (!mobileNav.contains(e.target) && !hamburger.contains(e.target)) {
                mobileNav.classList.remove('active');
            }
        });
    </script>
</body>
</html>