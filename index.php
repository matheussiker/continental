<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <title>Continental</title>
</head>
<body>
    <header>
        <a href="index.html"><h3>Home</h3></a>
        <a href="php/feed.php"><h3>Blog de viagens</h3></a>
        <a href="paginas/info.html"><h3>Sobre</h3></a>
        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
            <!-- Usuário logado: mostra botão sair -->
            <a href="php/logout.php"><h3 class="btn-cadastro">Sair</h3></a>
        <?php else: ?>
            <!-- Usuário não logado: mostra botão já tem uma conta -->
            <a href="php/login.php"><h3 class="btn-cadastro">Já tem uma conta</h3></a>
        <?php endif; ?>
    </header>
    <main>
        <h1 class="text-logo" id="sublogo">CONTINENTAL</h1>
        <h4 class="sublogo">Sua viagem começa aqui.</h4>
        <h4 class="sublogo">
            Explore destinos incríveis com praticidade! Pacotes, passagens e hospedagens em um só lugar!</h4>
    </main>

    <section class="pagina_principal">
        <div class="recomendacao">
            <h2 class="recomendacao-title">O que a Continental recomenda nacionalmente para você:</h2>

            <div class="carousel">
              <button class="btn prev">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                  <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z"/>
                </svg>
              </button>
              <div class="carousel-track">

                <div class="card inactive" id="img-carousel-1">
                  <img src="images/curitiba.jpg" alt="Curitiba">
                  <div class="info">
                    <h2>Curitiba,</h2>
                    <p> Paraná</p>
                  </div>
                </div>

                <div class="card inactive" id="img-carousel-2">
                  <img src="images/florianopolis.jpg" alt="2Florianópolis">
                  <div class="info">
                    <h2>Florianópolis,</h2>
                    <p> Santa Catarina</p>
                  </div>
                </div>
                
                <div class="card inactive" id="img-carousel-3">
                  <img src="images/salvador.jpg" alt="Salvador">
                  <div class="info">
                    <h2>Salvador,</h2>
                    <p> Bahia</p>
                  </div>
                </div>
                </div>

            <button class="btn next">▶</button>
            </div>

            <div class="text-recomendacao" id="recomendacao-texto">
              <p></p>
            </div>
        </div>
    </section>

    
</body>
<script src="js/slider.js"></script>
</html>