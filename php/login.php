<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style2.css">
    <script src="https://kit.fontawesome.com/b0fbce6f8a.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <title>Continental</title>
</head>
<body style="background: url(../images/login.background.png) no-repeat left top; background-size: cover;">
    <header>
        <a href="../index.php"><h3>Home</h3></a>
        <a href="feed.php"><h3>Blog de viagens</h3></a>
        <a href="/paginas/info.html"><h3>Sobre</h3></a>
        <a href="cadastro.php"><h3 class="btn-cadastro">Cadastrar-se</h3></a>
    </header>
    <div class="filtro" id="filtro2"></div>
    </header>
    <section>
        <div class="bemvindo">
            <h1>BEM-VINDO</h1>
            <p>Aproveite as melhores viagens com os<br>melhores preços na Continental</p>
        </div>
        <div>
        
            <fieldset>
                <form action="" method="post">
                    <label for="">
                        <h1>Login</h1>
                    </label>
                    <h4>Usuario:</h4> 
                    <input type="text" placeholder="Usuario" id="usuario" name="usuario"  maxlength="35" class="input-destino" required>
                    <h4>Senha:</h4>
                    <input type="password" placeholder="Senha" id="senha" name="senha" maxlength="8" class="input-destino" required>
                    </label>
                    <button type="submit" name="botao" class="btn-submit">Logar</button>
                    <a href="cadastro.php" class="btn-submit">Cadastrar-se</a>

                </form>

                    <label for="login-alt">
                        <h1>Logar com</h1>
                    </label>
                    <div class="social-login">
                        <button class="btn-google"><i class="fa-brands fa-google"></i></button>
                        <button class="btn-facebook"><i class="fa-brands fa-facebook"></i></button>
                        <button class="btn-instagram"><i class="fa-brands fa-instagram"></i></button>
                    </div>
            </fieldset>
        </div>
    </section>

    <footer>
        <h4>Sobre</h4>
        <h4>Contato</h4>
        <h4>Trabalhe Conosco</h4>
        <h4>Termos de Uso</h4>
        <h4>Política de Privacidade</h4>
        <h4>Ajuda</h4>
    </footer>

    <?php 
    session_start();
    
    if(isset($_POST["botao"])) {
        require("conecta.php");
        $usuario = trim($_POST["usuario"]);
        $senha  = trim($_POST["senha"]);

        $stmt = $mysqli->prepare("SELECT * FROM tb_cadastrouser  WHERE usuario = ? AND senha = ?");
        $stmt->bind_param("ss", $usuario, $senha);
        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $dados = $resultado->fetch_assoc();
            $_SESSION["id_user"] = $dados["id_user"];
            $_SESSION["logged_in"] = true; // define o login ativo

            header("Location: feed.php");
            exit;
    }
   
        else {
            echo "<div class='mensagem_erro'>
                    <a href='login.php'>
                        <svg id='voltar' xmlns='http://www.w3.org/2000/svg' width='24' height='24' fill='currentColor' class='bi bi-box-arrow-in-left' viewBox='0 0 16 16'>
                            <path fill-rule='evenodd' d='M10 3.5a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 1 1 0v2A1.5 1.5 0 0 1 9.5 14h-8A1.5 1.5 0 0 1 0 12.5v-9A1.5 1.5 0 0 1 1.5 2h8A1.5 1.5 0 0 1 11 3.5v2a.5.5 0 0 1-1 0z'/>
                            <path fill-rule='evenodd' d='M4.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H14.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708z'/>
                        </svg>
                    </a>
                    <svg id='emoji' xmlns='http://www.w3.org/2000/svg' width='70' height='70' fill='currentColor' class='bi bi-emoji-tear' viewBox='0 0 16 16'>
                        <path d='M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16'/>
                        <path d='M6.831 11.43A3.1 3.1 0 0 1 8 11.196c.916 0 1.607.408 2.25.826.212.138.424-.069.282-.277-.564-.83-1.558-2.049-2.532-2.049-.53 0-1.066.361-1.536.824q.126.27.232.535.069.174.135.373ZM6 11.333C6 12.253 5.328 13 4.5 13S3 12.254 3 11.333c0-.706.882-2.29 1.294-2.99a.238.238 0 0 1 .412 0c.412.7 1.294 2.284 1.294 2.99M7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5m4 0c0 .828-.448 1.5-1 1.5s-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5m-1.5-3A.5.5 0 0 1 10 3c1.162 0 2.35.584 2.947 1.776a.5.5 0 1 1-.894.448C11.649 4.416 10.838 4 10 4a.5.5 0 0 1-.5-.5M7 3.5a.5.5 0 0 0-.5-.5c-1.162 0-2.35.584-2.947 1.776a.5.5 0 1 0 .894.448C4.851 4.416 5.662 4 6.5 4a.5.5 0 0 0 .5-.5'/>
                    </svg>
                    <p id='erro'>Usuário ou senha inválidos!
                    <br>
                    <span>tente novamente</spa>
                    </p>
                    <br>
                    <br>
                    <h2>CONTINENTAL<h2>
                    
                  </div>";
        }
    }
    
    ?>
</body>
</html>