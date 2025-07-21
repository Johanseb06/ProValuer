<nav class="navbar navbar-expand-lg bg-white shadow rounded px-4 py-2 m-3">
    <div class="container-fluid">
        <!-- Mostrar el nombre de la página o "ProValuer" si no está definida -->
        <a class="navbar-brand fw-bold" href="#">
            <?php echo isset($nombre_page) ? $nombre_page : 'ProValuer'; ?>
        </a>

        <!-- Botón de hamburguesa para móviles -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
            aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Contenido del navbar -->
        <div class="collapse navbar-collapse justify-content-end" id="navbarContent">
            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item dropdown d-flex align-items-center">
                    <a href="#" class="nav-link text-body font-weight-bold p-0" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="display: inline-block; line-height: 1;">
                        <i class="material-symbols-rounded fs-2 icon-opacity">account_circle</i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end custom-dropdown" aria-labelledby="userDropdown">
                        <li>
                            <a class="dropdown-item d-flex align-items-center icon-opacity" href="/provaluer/user_roles/pages/profile.php">
                                <i class="material-symbols-rounded">person</i>
                                <span class="ms-1">Perfil</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center icon-opacity" href="/provaluer/user_roles/pages/settings.php">
                                <i class="material-symbols-rounded">settings</i>
                                <span class="ms-1">Ajustes</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center icon-opacity" href="/provaluer/auth/logout.php">
                                <i class="material-symbols-rounded">logout</i>
                                <span class="ms-1">Cerrar sesión</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>