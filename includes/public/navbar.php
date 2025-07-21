<script>
    document.addEventListener("DOMContentLoaded", function () {
        const currentPage = window.location.pathname;
        document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
            if (link.getAttribute('href') === currentPage) {
                link.classList.add('active');
            }
        });
    });
</script>
<style>
    .nav-link {
    position: relative;
    transition: color 0.3s;
}

.nav-link.active::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: -4px;
    width: 100%;
    height: 3px;
    background-color: #006A71; /* verde Bootstrap */
    border-radius: 2px;
}
</style>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow fixed-top p-3">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="/provaluer/index.php">
            <img src="/provaluer/assets/img/logo.png" alt="" height="40">
            <span class="ms-3 text-white fw-bold">ProValuer</span>
        </a>
        <button class="navbar-toggler bg-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto gap-3">
                <li class="nav-item"><a href="/provaluer/index.php" class="nav-link text-white py-2 px-3">Inicio</a></li>
                <li class="nav-item"><a href="/provaluer/pages/signin.php" class="nav-link text-white py-2 px-3">Iniciar sesión</a></li>
                <li class="nav-item"><a href="/provaluer/pages/signup.php" class="nav-link text-white py-2 px-3">Registrarse</a></li>
                <li class="nav-item"><a href="/provaluer/pages/about.php" class="nav-link text-white py-2 px-3">Acerca de</a></li>
            </ul>
        </div>
    </div>
</nav>