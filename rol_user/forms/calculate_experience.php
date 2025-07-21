<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../db/experience_queries.php';
include "../../config/db_config.php";
include '../includes/init.php';

if (!isset($_SESSION["correo"]) || $_SESSION["rol_usuario_fk"] != 3) {
    header("Location: /provaluer/index.php");
    exit();
}
$nombre_page = 'Calcular experiencia';

if (!isset($_SESSION['formulario']['postulante']) || !isset($_SESSION['formulario']['perfil'])) {
    die("Faltan datos del postulante o perfil.");
}

if (!isset($_SESSION['formulario']['programa'])) {
    die("⚠️ No hay un programa seleccionado.");
}
$programa = $_SESSION['formulario']['programa'];


$postulante = $_SESSION['formulario']['postulante'];
$perfil = $_SESSION['formulario']['perfil'];
$id_perfil = $_SESSION['formulario']['perfil']['id_perfil'];


// Función para calcular días entre fechas con base 360
function dias360($fechaInicio, $fechaFin)
{
    $inicio = new DateTime($fechaInicio);
    $fin = new DateTime($fechaFin);
    $dias = (($fin->format('Y') - $inicio->format('Y')) * 360)
        + (($fin->format('m') - $inicio->format('m')) * 30)
        + ($fin->format('d') - $inicio->format('d'));
    return max(0, $dias); // asegurarse que no haya negativos
}

// Cargar puntajes desde base de datos
$puntajes = getSubcategoryScore($conn);
$ponderacion = getSubcategoriesByCategoryName($conn, 'Experiencia', $id_perfil);

// Recibir datos del formulario
$experiencia = $_POST['experiencia'] ?? [];
$total_puntos = 0;
$_SESSION['formulario']['experiencia'] = [
    'total' => round($total_puntos, 2),
    'subcategorias' => []
];

?>
<?php include('../../includes/panel/header.php'); ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-4">
            <div class="list-group">
                <div class="step">1. Postulante</div>
                <div class="step">2. Perfil</div>
                <div class="step">3. Programa</div>
                <div class="step active">4. Experiencia</div>
                <div class="step">5. Categorias</div>
                <div class="step">6. Resumen</div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h4>Resultados de la experiencia</h4>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 66%">66%</div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="dropdown mb-3">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Ver datos
                        </button>
                        <ul class="dropdown-menu">
                            <li class="dropdown-item selecciones">Nombre: <?php echo htmlspecialchars($postulante['nombre_postulante']); ?></li>
                            <li class="dropdown-item selecciones">Documento: <?php echo htmlspecialchars($postulante['documento']); ?></li>
                            <li class="dropdown-item selecciones">Perfil: <?php echo $perfil['nombre_perfil']; ?></li>
                            <li class="dropdown-item selecciones">Programa: <?php echo htmlspecialchars($programa['nombre_programa']); ?></li>
                        </ul>
                    </div>

                    <?php
                    function dividirDiasPorSubcategoria(array $experiencia, array $puntajes): array
                    {
                        $linea_tiempo = [];

                        // Recolectar todos los días y a qué subcategoría pertenecen
                        foreach ($experiencia as $id_subcat => $datos) {
                            $nombre = $datos['nombre'];
                            $inicio_fechas = $datos['fechas_inicio'];
                            $fin_fechas = $datos['fechas_fin'];
                            $puntos_anuales = $puntajes[$id_subcat] ?? 0;

                            for ($i = 0; $i < count($inicio_fechas); $i++) {
                                $inicio = new DateTime($inicio_fechas[$i]);
                                $fin = new DateTime($fin_fechas[$i]);
                                for ($fecha = clone $inicio; $fecha <= $fin; $fecha->modify('+1 day')) {
                                    $clave = $fecha->format('Y-m-d');
                                    $linea_tiempo[$clave][] = [
                                        'subcat' => $id_subcat,
                                        'nombre' => $nombre,
                                        'puntos' => $puntos_anuales,
                                    ];
                                }
                            }
                        }

                        // Asignar cada día a la subcategoría con mayor puntaje
                        $dias_por_subcat = [];
                        foreach ($linea_tiempo as $dia => $entradas) {
                            usort($entradas, fn($a, $b) => $b['puntos'] <=> $a['puntos']);
                            $mejor = $entradas[0];
                            $dias_por_subcat[$mejor['subcat']]['nombre'] = $mejor['nombre'];
                            $dias_por_subcat[$mejor['subcat']]['dias'][] = $dia;
                        }

                        // Reconstruir periodos contiguos y calcular puntaje
                        $resultado = [];
                        foreach ($dias_por_subcat as $id_subcat => $info) {
                            $dias = $info['dias'];
                            sort($dias);

                            $periodos = [];
                            $inicio = $fin = null;
                            foreach ($dias as $i => $dia) {
                                if (!$inicio) {
                                    $inicio = $fin = new DateTime($dia);
                                } else {
                                    $fecha_actual = new DateTime($dia);
                                    $previa = $fin ? clone $fin : null;
                                    if ($previa && $fecha_actual == $previa->modify('+1 day')) {
                                        $fin = $fecha_actual;
                                    } else {
                                        $periodos[] = ['inicio' => clone $inicio, 'fin' => clone $fin];
                                        $inicio = $fin = $fecha_actual;
                                    }
                                }
                            }

                            if ($inicio && $fin) {
                                $periodos[] = ['inicio' => clone $inicio, 'fin' => clone $fin];
                            }

                            // Calcular puntaje
                            $puntos_total = 0;
                            $puntos_anuales = $puntajes[$id_subcat];
                            foreach ($periodos as &$p) {
                                $dias = dias360($p['inicio']->format('Y-m-d'), $p['fin']->format('Y-m-d'));
                                $meses = $dias / 30;
                                $puntaje = $meses * ($puntos_anuales / 12);
                                $p['dias'] = $dias;
                                $p['puntaje'] = $puntaje;
                                $p['inicio_str'] = $p['inicio']->format('Y-m-d');
                                $p['fin_str'] = $p['fin']->format('Y-m-d');
                                $puntos_total += $puntaje;
                            }

                            $resultado[$id_subcat] = [
                                'nombre' => $info['nombre'],
                                'puntaje_total' => round($puntos_total, 2),
                                'periodos' => $periodos,
                            ];
                        }

                        return $resultado;
                    }
                    
                    $resultado = dividirDiasPorSubcategoria($experiencia, $puntajes);
                    $_SESSION['formulario']['experiencia']['subcategorias'] = $resultado;
                    
                    foreach ($resultado as $subcat): ?>
                        <?php if (empty($subcat['periodos']) || $subcat['puntaje_total'] == 0): continue;
                        endif; ?>

                        <h4 class="mt-4"><?= $subcat['nombre'] ?></h4>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle">
                                <thead class="table-success">
                                    <tr>
                                        <th>Inicio</th>
                                        <th>Fin</th>
                                        <th>Días</th>
                                        <th>Puntaje</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($subcat['periodos'] as $p): ?>
                                        <tr>
                                            <td><?= $p['inicio_str'] ?></td>
                                            <td><?= $p['fin_str'] ?></td>
                                            <td><?= $p['dias'] ?></td>
                                            <td><?= round($p['puntaje'], 2) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <p class="fw-bold">Subtotal: <span class="texto-verde"><?= round($subcat['puntaje_total'], 2) ?> pts</span></p>
                        <hr class="my-4">
                        <?php $total_puntos += $subcat['puntaje_total']; ?>
                    <?php endforeach; ?>

                    <?php
                    $row = $ponderacion->fetch_assoc();
                    $max = false;
                    $ponderacion_max = $row['ponderacion_categoria'];
                    if ($total_puntos > $ponderacion_max) {
                        $max = true;
                        $total_puntos = $ponderacion_max;
                    }
                    $_SESSION['formulario']['experiencia']['total'] = round($total_puntos, 2);
                    ?>

                    <h4 class="fw-bold mt-4">Puntaje total acumulado:
                        <span class="texto-verde"><?= round($total_puntos, 2) ?> pts</span>
                    </h4>

                    <?php if ($max): ?>
                        <p class="text-danger fst-italic">Ten en cuenta que la ponderación máxima de esta categoría son <?= $ponderacion_max ?> puntos*</p>
                    <?php endif; ?>

                    <div class="mt-4 d-flex justify-content-between">
                        <a href="../forms/experience.php" class="btn btn-outline-secondary">Volver</a>
                        <a href="../controllers/save_experience.php" class="btn botones">Guardar y continuar</a>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($mensaje)): ?>
            <?php echo $mensaje; ?>
        <?php endif; ?>
        <?php $conn->close(); ?>
    </div>
</div>

<?php include('../../includes/panel/footer.php'); ?>