<?php include('includes/public/header.php'); ?>

<div class="container-fluid p-0 w-100 h-100 inicio">
  <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner position-relative">
      <div class="carousel-item active position-relative">
        <img src="./assets/img/carrusel1.jpeg" class="d-block w-100 object-fit-cover" style="height: 100vh;" alt="Image">
        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-75"></div>
        <div class="carousel-caption">
          <div class="container">
            <div class="row  d-flex justify-content-lg-end ">
              <div class="col-xl-7 text-center text-md-end">
                <h4 class="texto-verde text-uppercase fw-bold mb-4">Bienvenido a ProValuer</h4>
                <h1 class="fs-1 fw-bold text-uppercase text-white mb-4">Evalúa con precisión, decide con confianza</h1>
                <p class="mb-4 fs-5 d-none d-md-block">Nuestro sistema está diseñado para facilitar la evaluación técnica y formativa de los aspirantes. Todo en un solo lugar, con total transparencia.</p>
                <div class="d-flex justify-content-center justify-content-md-end flex-shrink-0 mb-4">
                  <a class="btn btn-light rounded-pill py-3 px-4 px-md-5 me-2" href="./pages/signin.php">Iniciar sesión</a>
                  <a class="btn boton rounded-pill py-3 px-4 px-md-5 ms-2" href="./pages/about.php">Conocer más</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="carousel-item position-relative">
        <img src="./assets/img/carrusel2.jpeg" class="d-block w-100 object-fit-cover" style="height: 100vh;" alt="Image">
        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-75"></div>
        <div class="carousel-caption">
          <div class="container">
            <div class="row gy-0 gx-5 d-flex justify-content-end align-items-start">
              <div class="col-xl-7 text-center text-md-end">
                <h4 class="texto-verde text-uppercase fw-bold mb-4">Bienvenido a ProValuer</h4>
                <h1 class="fs-1 fw-bold text-uppercase text-white mb-4">Tu herramienta para gestionar procesos de selección</h1>
                <p class="mb-4 fs-5">Automatiza la evaluación de postulantes, organiza perfiles y garantiza decisiones bien fundamentadas.</p>
                <div class="d-flex justify-content-center justify-content-md-end flex-shrink-0 mb-4">
                  <a class="btn btn-light rounded-pill py-3 px-4 px-md-5 me-2" href="./pages/signin.php">Iniciar sesión</a>
                  <a class="btn boton rounded-pill py-3 px-4 px-md-5 ms-2" href="./pages/about.php">Conocer más</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>
</div>

<?php include('includes/public/footer.php'); ?>
