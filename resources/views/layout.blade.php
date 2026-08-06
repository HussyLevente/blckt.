<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>blckt. | Professzionális Weboldalak</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Afacad+Flux:wght@100;300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/layout.css') }}">
    @stack('styles')
</head>
<body>

    <header id="blckt-navbar" class="navbar">
        <div class="nav-container">
            <div class="logo">
                <a href="/">blckt.</a>
            </div>
            <nav class="nav-links">
                <a href="/clothing" class="active">clothing</a>
                <a href="/websites">websites</a>
                <a href="/contact">contact</a>
                <a href="/about">about</a>
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <a href="/contact" class="get-in-touch">Get in touch <span class="info-icon">&#9432;</span></a>

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-logo">
                <h2>blckt.</h2>
            </div>
            <div class="footer-columns">
                <div class="col">
                    <h3>clothing</h3>
                    <a href="#">all items</a>
                </div>
                <div class="col">
                    <h3>websites</h3>
                    <a href="#">all websites</a>
                </div>
                <div class="col">
                    <h3>contact</h3>
                    <a href="#">contact@blckt.com</a>
                    <a href="#">06 30 255 2426</a>
                </div>
                <div class="col">
                    <h3>about</h3>
                    <a href="#">blckt.</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let lastScrollY = window.scrollY;
            const navbar = document.getElementById('blckt-navbar');

            window.addEventListener('scroll', () => {
                if (window.scrollY > lastScrollY && window.scrollY > 80) {
                    // Lefele görgetés - eltűnik
                    navbar.style.transform = 'translateY(-100%)';
                } else {
                    // Felfele görgetés - megjelenik
                    navbar.style.transform = 'translateY(0)';
                }
                lastScrollY = window.scrollY;
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
