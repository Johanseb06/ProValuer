<?php session_start();

if (!isset($_SESSION["id_usuario"]) || $_SESSION["rol_usuario_fk"] != 3) {
    header("Location: /provaluer/index.php");
    exit();
}

?>
<?php include('../../includes/panel/header.php'); ?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h3> Buscar registros</h3>
            <form id="filtroForm" class="mb-4">
                <select name="programa" id="programa" class="form-select mb-2">
                    <option value="">Seleccione un programa</option>
                </select>

                <select name="perfil" id="perfil" class="form-select mb-2">
                    <option value="">Seleccione un perfil</option>
                </select>

                <select name="postulante" id="postulante" class="form-select mb-3">
                    <option value="">Seleccione un postulante</option>
                </select>

                <button type="submit" class="btn botones">Buscar</button>
            </form>

            <div id="resultados"></div>

        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
  cargarSelect('programa', 'programas');
  cargarSelect('perfil', 'perfiles');
  cargarSelect('postulante', 'postulantes');

  document.getElementById('filtroForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch('../controllers/search_form.php', {
      method: 'POST',
      body: formData
    })
    .then(res => res.text())
    .then(html => {
      document.getElementById('resultados').innerHTML = html;
    });
  });

  function cargarSelect(id, tabla) {
    fetch(`../ajax/cargar_opciones.php?tabla=${tabla}`)
      .then(res => res.json())
      .then(data => {
        const select = document.getElementById(id);
        data.forEach(item => {
          const option = document.createElement('option');
          option.value = item.id;
          option.textContent = item.nombre;
          select.appendChild(option);
        });
      });
  }
});
</script>

<?php include('../../includes/panel/footer.php'); ?>