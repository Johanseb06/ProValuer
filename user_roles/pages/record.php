<?php
session_start();
if (!isset($_SESSION["correo"]) || ($_SESSION["rol_usuario_fk"] != 1 && $_SESSION["rol_usuario_fk"] != 2)) {
    header("Location: /provaluer/index.php");
    exit();
}
$nombre_page = 'Historial de acciones';
include '../../config/db_config.php';
include '../../includes/panel/header.php';

$registros_por_pagina = 5;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_actual - 1) * $registros_por_pagina;

$busqueda = "";
if (isset($_GET['buscar']) && !empty($_GET['buscar'])) {
    $busqueda = $conn->real_escape_string($_GET['buscar']);
    $sql_total = "SELECT COUNT(*) as total FROM historiales WHERE 
        usuario_responsable LIKE '%$busqueda%' 
        OR tipo_de_accion LIKE '%$busqueda%' 
        OR accion LIKE '%$busqueda%'";

    $sql = "SELECT * FROM historiales WHERE 
        usuario_responsable LIKE '%$busqueda%' 
        OR tipo_de_accion LIKE '%$busqueda%' 
        OR accion LIKE '%$busqueda%'
        ORDER BY fecha_hora DESC
        LIMIT $registros_por_pagina OFFSET $offset";
} else {
    $sql_total = "SELECT COUNT(*) as total FROM historiales";
    $sql = "SELECT * FROM historiales ORDER BY fecha_hora DESC LIMIT $registros_por_pagina OFFSET $offset";
}

$result_total = $conn->query($sql_total);
$total_registros = $result_total->fetch_assoc()['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);

$result = $conn->query($sql);
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card mt-4 shadow">
                <div class="color_fondo card-header">
                    <h4 class="text-white">Historial de Acciones</h4>
                </div>
                <div class="card-body">
                    <form method="GET" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="buscar" class="form-control" placeholder="Buscar..." autocomplete="off" value="<?php echo htmlspecialchars($busqueda); ?>">
                            <button type="submit" class="btn botones">Buscar</button>
                        </div>
                    </form>

                    <table class="table border-dark table-hover text-center">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Fecha</th>
                                <th>Usuario Responsable</th>
                                <th>Tipo de Acción</th>
                                <th>Detalle</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            <?php if ($result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $row["id_historial"]; ?></td>
                                        <td><?php echo $row["fecha_hora"]; ?></td>
                                        <td><?php echo $row["usuario_responsable"]; ?></td>
                                        <td><?php echo $row["tipo_de_accion"]; ?></td>
                                        <td><?php echo $row["accion"]; ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">No se encontraron resultados</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <?php if ($total_paginas > 1): ?>
                        <nav>
                            <ul class="pagination justify-content-center">
                                <?php if ($pagina_actual > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link text-success" href="?pagina=1&buscar=<?= $busqueda ?>">&laquo;&laquo;</a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link text-success" href="?pagina=<?= $pagina_actual - 1 ?>&buscar=<?= $busqueda ?>">&laquo;</a>
                                    </li>
                                <?php endif; ?>

                                <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                                    <li class="page-item <?= ($i == $pagina_actual) ? 'active' : '' ?>">
                                        <a class="page-link <?= ($i == $pagina_actual) ? 'bg-success text-white border-success' : 'text-success' ?>" href="?pagina=<?= $i ?>&buscar=<?= $busqueda ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($pagina_actual < $total_paginas): ?>
                                    <li class="page-item">
                                        <a class="page-link text-success" href="?pagina=<?= $pagina_actual + 1 ?>&buscar=<?= $busqueda ?>">&raquo;</a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link text-success" href="?pagina=<?= $total_paginas ?>&buscar=<?= $busqueda ?>">&raquo;&raquo;</a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../../includes/panel/footer.php'); ?>
<?php $conn->close(); ?>
