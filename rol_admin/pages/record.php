<?php
session_start();
if (!isset($_SESSION["correo"]) || $_SESSION["rol_usuario_fk"] != 1) {
    header("Location: /provaluer/index.php");
    exit();
}
// Establece el nombre del usuario actual en la sesión para su uso posterior en la base de datos.
// $nombre = $_SESSION['name'];   // o cualquier identificador 
// mysqli_query($conexion,
//     "SET @usuario_actual := '".mysqli_real_escape_string($conexion,$nombre)."'");

include("../../config/db_config.php");

$sql = "SELECT * FROM historiales ORDER BY fecha_hora DESC";
$resultado = mysqli_query($conn, $sql);
?>

<?php include('../../includes/panel/header.php'); ?>

<!-- Si no tienes estos en tu header.php, agrégalos aquí -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Historial de acciones</h4>
        </div>
        <div class="card-body">
            <table id="tablaHistorial" class="table table table-striped table-hover border-dark text-center">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Fecha y hora</th>
                        <th>Usuario responsable</th>
                        <th>Tipo de acción</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    <?php while ($fila = mysqli_fetch_assoc($resultado)) { ?>
                        <tr>
                            <td><?= $fila['id_historial'] ?></td>
                            <td><?= $fila['fecha_hora'] ?></td>
                            <td><?= htmlspecialchars($fila['usuario_responsable']) ?></td>
                            <td><?= htmlspecialchars($fila['tipo_de_accion']) ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- idiona -->
<script>
    $(document).ready(function() {
        $('#tablaHistorial').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            }
        });
    });
</script>

<?php include('../../includes/panel/footer.php'); ?>
