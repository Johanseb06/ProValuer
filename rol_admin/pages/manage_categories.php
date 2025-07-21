<?php
session_start();
if (!isset($_SESSION["correo"]) || $_SESSION["rol_usuario_fk"] != 1) {
    header("Location: /provaluer/index.php");
    exit();
}

$nombre_page = 'Categorias'; // Aquí va el nombre de la sección actual, como 'Categoria', 'Contacto', etc.

include("../../config/db_config.php");

// usuario responsable
mysqli_query($conn, "SET @usuario_actual := '".mysqli_real_escape_string($conn, $_SESSION['nombre_usuario'])."'");


// Función para mostrar número con o sin decimales según sea necesario
function mostrarNumero($numero) {
    return rtrim(rtrim(number_format((float)$numero, 2, '.', ''), '0'), '.');
}

// Actualizar ponderación de categoría
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["editar_ponderacion"])) {
        $id_categoria = intval($_POST["id_categoria"]);
        $nueva_ponderacion = floatval($_POST["ponderacion_categoria"]);

        $sqlUpdate = "UPDATE categorias SET ponderacion_categoria = ? WHERE id_categoria = ?";
        $stmt = $conn->prepare($sqlUpdate);
        $stmt->bind_param("di", $nueva_ponderacion, $id_categoria);
        $stmt->execute();
        $stmt->close();
    }

    // Actualizar ponderación de subcategoría
    if (isset($_POST["editar_subcategoria"])) {
        $id_subcategoria = intval($_POST["id_subcategoria"]);
        $nuevos_puntos = floatval($_POST["ponderacion_puntos"]);
        $nuevo_porcentaje = floatval($_POST["ponderacion_porcentaje"]);

        $sqlUpdateSub = "UPDATE subcategorias SET ponderacion_subcategoria_puntos = ?, ponderacion_subcategoria_porcentaje = ? WHERE id_subcategoria = ?";
        $stmt = $conn->prepare($sqlUpdateSub);
        $stmt->bind_param("ddi", $nuevos_puntos, $nuevo_porcentaje, $id_subcategoria);
        $stmt->execute();
        $stmt->close();
    }
}

// Obtener categorías con nombre del perfil
$sqlCategorias = "
    SELECT c.*, p.nombre_perfil
    FROM categorias c
    JOIN perfiles p ON c.perfil_categoria_fk = p.id_perfil
    ORDER BY c.id_categoria
";
$resultCategorias = $conn->query($sqlCategorias);
?>

<?php include('../../includes/panel/header.php'); ?>
<div class="container mt-5">
    <?php while ($cat = $resultCategorias->fetch_assoc()): ?>
        <?php $totalCategoria = $cat['ponderacion_categoria']; ?>
        <div class="card mb-4 shadow">
            <div class="card-header color_fondo text-white d-flex justify-content-between align-items-center">
                <span class="fs-5 d-flex flex-wrap gap-2 w-100">
                    <span class="flex-grow-1"><strong>Categoría:</strong> <?= htmlspecialchars($cat['nombre_categoria']) ?></span>
                    <span class="flex-grow-1"><strong>Perfil:</strong> <?= htmlspecialchars($cat['nombre_perfil']) ?></span>
                    <span class="flex-grow-1"><strong>Ponderación:</strong> <?= mostrarNumero($cat['ponderacion_categoria']) ?></span>
                </span>
                <button class="btn btn-sm btn-outline-light" data-bs-toggle="modal" data-bs-target="#editarModal<?= $cat['id_categoria'] ?>">Editar</button>
            </div>

            <div class="card-body">
                <table class="table border-dark table-hover text-center">
                    <thead>
                        <tr>
                            <th>Nombre Subcaregoria</th>
                            <th>Ponderacion en Puntos</th>
                            <th>Ponderacion en Porcentaje</th>
                            <th>Editar Ponderacion</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        <?php
                        $idCat = $cat['id_categoria'];
                        $sqlSubcats = "SELECT * FROM subcategorias WHERE categoria_subcategoria_fk = $idCat";
                        $resultSubcats = $conn->query($sqlSubcats);

                        if ($resultSubcats->num_rows > 0):
                            while ($subcat = $resultSubcats->fetch_assoc()):
                        ?>
                                <tr>
                                    <td><?= htmlspecialchars($subcat['nombre_subcategoria']) ?></td>
                                    <td><?= mostrarNumero($subcat['ponderacion_subcategoria_puntos']) ?></td>
                                    <td><?= mostrarNumero($subcat['ponderacion_subcategoria_porcentaje']) ?>%</td>
                                    <td>
                                        <button class="btn btn-sm botones" data-bs-toggle="modal" data-bs-target="#editarSub<?= $subcat['id_subcategoria'] ?>">Editar</button>
                                    </td>
                                </tr>

                                <!-- Modal editar subcategoría -->
                                <div class="modal fade" id="editarSub<?= $subcat['id_subcategoria'] ?>" tabindex="-1" aria-labelledby="editarSubLabel<?= $subcat['id_subcategoria'] ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form method="post">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editarSubLabel<?= $subcat['id_subcategoria'] ?>">Editar Subcategoría</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="id_subcategoria" value="<?= $subcat['id_subcategoria'] ?>">
                                                    <div class="mb-3">
                                                        <label class="form-label">Puntos:</label>
                                                        <input type="number" step="0.01" name="ponderacion_puntos" class="form-control" value="<?= $subcat['ponderacion_subcategoria_puntos'] ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Porcentaje:</label>
                                                        <input type="number" step="0.01" name="ponderacion_porcentaje" class="form-control" value="<?= $subcat['ponderacion_subcategoria_porcentaje'] ?>" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" name="editar_subcategoria" class="btn botones">Guardar</button>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!-- Script para sincronizar puntos y porcentaje -->
                                <script>
                                    document.addEventListener("DOMContentLoaded", function() {
                                        const modalId = "editarSub<?= $subcat['id_subcategoria'] ?>";
                                        const modal = document.getElementById(modalId);
                                        if (!modal) return;

                                        const puntosInput = modal.querySelector('input[name="ponderacion_puntos"]');
                                        const porcentajeInput = modal.querySelector('input[name="ponderacion_porcentaje"]');
                                        const total = <?= floatval($totalCategoria) ?>;
                                        let updating = false;

                                        puntosInput.addEventListener("input", () => {
                                            if (updating || total === 0) return;
                                            updating = true;
                                            const puntos = parseFloat(puntosInput.value);
                                            if (!isNaN(puntos)) {
                                                porcentajeInput.value = ((puntos / total) * 100).toFixed(2);
                                            }
                                            updating = false;
                                        });

                                        porcentajeInput.addEventListener("input", () => {
                                            if (updating) return;
                                            updating = true;
                                            const porcentaje = parseFloat(porcentajeInput.value);
                                            if (!isNaN(porcentaje)) {
                                                puntosInput.value = ((porcentaje / 100) * total).toFixed(2);
                                            }
                                            updating = false;
                                        });
                                    });
                                </script>
                            <?php endwhile;
                        else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No hay subcategorías para esta categoría.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal editar categoría -->
        <div class="modal fade" id="editarModal<?= $cat['id_categoria'] ?>" tabindex="-1" aria-labelledby="editarModalLabel<?= $cat['id_categoria'] ?>" aria-hidden="true">
            <div class="modal-dialog">
                <form method="post">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editarModalLabel<?= $cat['id_categoria'] ?>">Editar Ponderación de Categoría</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id_categoria" value="<?= $cat['id_categoria'] ?>">
                            <div class="mb-3">
                                <label class="form-label">Ponderación:</label>
                                <input type="number" step="0.01" name="ponderacion_categoria" class="form-control" value="<?= $cat['ponderacion_categoria'] ?>" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="editar_ponderacion" class="btn botones">Guardar</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php endwhile; ?>
</div>
<?php include('../../includes/panel/footer.php'); ?>
<?php $conn->close(); ?>
