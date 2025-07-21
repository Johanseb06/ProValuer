<style>
    #sidebar-nav .nav-link {
        position: relative;
        transition: color 0.3s ease, background-color 0.3s ease, opacity 0.3s ease;
        padding: 10px;
        border-radius: 5px;
        opacity: 0.7;
        font-size: 0.95rem;
    }

    #sidebar-nav .nav-link::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: 4px;
        width: 0%;
        height: 3px;
        background-color: #006A71;
        border-radius: 2px;
        transition: width 0.3s ease-in-out;
    }

    #sidebar-nav .nav-link:hover::after {
        width: 100%;
    }

    #sidebar-nav .nav-link.active::after {
        width: 100%;
    }

    #sidebar-nav .nav-link.active {
        font-weight: bold;
        opacity: 1;
        background-color: transparent;
    }

    /* NUEVO: hover en el <li> afecta opacidad del link */
    #sidebar-nav li:hover .nav-link {
        opacity: 1;
    }
</style>
<aside style="height: 100vh; position: fixed;">
    <div class="shadow d-flex flex-column flex-shrink-0 p-4 bg-white rounded-4 mx-3 my-2" style="width: 260px; height: calc(100vh - 1rem);">
        <!-- Logo con ruta dinámica según rol -->
        <div>
            <?php
            $rol = $_SESSION["rol_usuario_fk"];
            $homeUrls = [
                1 => "/provaluer/rol_admin/home.php",
                2 => "/provaluer/rol_main_user/home.php",
                3 => "/provaluer/rol_user/home.php"
            ];
            if (isset($homeUrls[$rol])) :
            ?>
            <div class="d-flex justify-content-center w-100">
                <a class="navbar-brand p-0 m-0 d-flex align-items-center" href="/provaluer/user_roles/pages/profile.php">
                    <img src="/provaluer/assets/img/logo.png" class="navbar-brand-img" width="30" height="auto" alt="main_logo">
                    <span class="ms-1 fs-5 text-dark"><strong>ProValuer</strong></span>
                </a>
            </div>
            <?php endif; ?>
        </div>

        <hr>

        <ul class="nav nav-pills flex-column mb-auto" id="sidebar-nav">
            <!-- <li class="nav-item ">
                    <a class="nav-link text-dark d-flex align-items-center" href="<?= $homeUrls[$rol] ?>">
                        <i class="material-symbols-rounded fs-5">home</i>
                        <span class="ms-1">Inicio</span>
                    </a>
                </li> -->
            <li class="nav-item ">
                <a class="nav-link text-dark d-flex align-items-center" href="/provaluer/user_roles/pages/profile.php">
                    <i class="material-symbols-rounded fs-5">person</i>
                    <span class="ms-1">Perfil</span>
                </a>
            </li>
            <?php if ($_SESSION["rol_usuario_fk"] == 1) : ?>
                <li class="nav-item">
                    <a class="nav-link text-dark d-flex align-items-center" href="/provaluer/rol_admin/pages/user_manager.php">
                        <i class="material-symbols-rounded fs-5">admin_panel_settings</i>
                        <span class="ms-1">Gestión de usuarios</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark d-flex align-items-center" href="/provaluer/rol_admin/pages/manage_categories.php">
                        <i class="material-symbols-rounded fs-5">dashboard</i>
                        <span class="ms-1">Categorías</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if (in_array($_SESSION["rol_usuario_fk"], [1, 2])) : ?>
                <li class="nav-item">
                    <a class="nav-link text-dark d-flex align-items-center" href="/provaluer/user_roles/pages/record.php">
                        <i class="material-symbols-rounded fs-5">history</i>
                        <span class="ms-1">Historial</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($_SESSION["rol_usuario_fk"] == 2) : ?>
                <li class="nav-item">
                    <a class="nav-link text-dark d-flex align-items-center" href="/provaluer/rol_main_user/pages/program.php">
                        <i class="material-symbols-rounded fs-5">featured_play_list</i>
                        <span class="ms-1">Registrpos</span>
                    </a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link text-dark d-flex align-items-center" href="#">
                        <i class="material-symbols-rounded fs-5">table</i>
                        <span class="ms-1">Test</span>
                    </a>
                </li> -->
            <?php endif; ?>

            <?php if ($_SESSION["rol_usuario_fk"] == 3) : ?>
                <li class="nav-item">
                    <a class="nav-link text-dark d-flex align-items-center" href="/provaluer/rol_user/pages/programs.php">
                        <i class="material-symbols-rounded fs-5">featured_play_list</i>
                        <span class="ms-1">Registros</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark d-flex align-items-center" href="/provaluer/rol_user/forms/postulant.php">
                        <i class="material-symbols-rounded fs-5">assignment</i>
                        <span class="ms-1">Formulario</span>
                    </a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link text-dark d-flex align-items-center" href="/provaluer/rol_user/forms/postulant.php">
                        <i class="material-symbols-rounded fs-5">person_add</i>
                        <span class="ms-1">Registrar Postulante</span>
                    </a>
                </li> -->
                <!-- <li class="nav-item">
                    <a class="nav-link text-dark d-flex align-items-center" href="/provaluer/rol_user/pages/tables.php">
                        <i class="material-symbols-rounded fs-5">table</i>
                        <span class="ms-1">Tablas</span>
                    </a>
                </li> -->
            <?php endif; ?>
            <hr>

            <li class="nav-item">
                <a class="nav-link text-dark d-flex align-items-center" href="/provaluer/auth/logout.php">
                    <i class="material-symbols-rounded fs-5">Logout</i>
                    <span class="ms-1">Cerrar sesión</span>
                </a>
            </li>
        </ul>
    </div>
</aside>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const currentPage = window.location.pathname;
        document.querySelectorAll('#sidebar-nav .nav-link').forEach(link => {
            if (link.getAttribute('href') === currentPage) {
                link.classList.add('active');
            }
        });
    });
</script>