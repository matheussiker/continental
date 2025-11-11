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
    <title>Cadastre-se</title> 
</head> 
<body class="body-cadastro">     
<header>         
    <a href="../index.php"><h3>Home</h3></a>         
    <a href="feed.php"><h3>Blog de viagens</h3></a>         
    <a href="/paginas/info.html"><h3>Sobre</h3></a>         
    <a href="login.php"><h3 class="btn-cadastro">Já tem uma conta</h3></a>     
</header>          

<div class="filtro"></div>     
<section>         
    <div>             
        <fieldset>                 
            <form action="" method="post">                     
                <label for="">                         
                    <h1>Cadastre-se</h1>                     
                </label>                                          
                <h4>Nome Completo:</h4><input type="text" maxlength="35" placeholder="Seu nome aqui" id="nome" name="nome" class="input-destino" required>                      
                <h4>Usuario:</h4><input type="text" maxlength="25" placeholder="Usuario" id="usuario" name="usuario" class="input-destino" required>                      
                <h4>Email:</h4><input type="email" maxlength="50" placeholder="seuemail@dominio.com" id="email" name="email" class="input-destino" required>                      
                <h4>Telefone:</h4><input type="text" maxlength="18" placeholder="Telefone" id="tell" name="fone" class="input-destino" required>                      
                <h4>Senha:</h4><input type="password" maxlength="12" minlength="8" placeholder="Senha (máximo de 12 digitos e minimo 8)" id="senha" name="senha" class="input-destino" required>                      
                <h4>Confirme a senha:</h4><input type="password" maxlength="12" minlength="8" placeholder="Senha" id="conf_senha" name="conf_senha" class="input-destino" required>                      
                <button type="submit" name="botao" class="btn-submit">Cadastrar</button>                 
            </form>              
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
if (isset($_POST["botao"])) {
    require("conecta.php");

    $nome       = trim($_POST["nome"]);
    $usuario    = trim($_POST["usuario"]);
    $email      = trim($_POST["email"]);
    $fone       = trim($_POST["fone"]);
    $senha      = trim($_POST["senha"]);
    $conf_senha = trim($_POST["conf_senha"]);

    if (empty($nome) || empty($usuario) || empty($email) || empty($fone) || empty($senha) || empty($conf_senha)) {
        echo "<p style='color:red;'>Erro: Preencha todos os campos!</p>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p style='color:red;'>Erro: E-mail inválido!</p>";
    } elseif ($senha !== $conf_senha) {
        echo "<p style='color:red;'>Erro: As senhas não coincidem!</p>";
    } elseif (strlen($senha) < 8 || strlen($senha) > 12) {
        echo "<p style='color:red;'>Erro: A senha deve ter entre 8 e 12 caracteres!</p>";
    } else {
        // Verifica duplicidade
        $check = $mysqli->prepare("SELECT id_user FROM tb_cadastrouser WHERE usuario = ? OR email = ?");
        $check->bind_param("ss", $usuario, $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            echo "<p style='color:red;'>Usuário ou e-mail já cadastrado!</p>";
        } else {
            // Criptografa senha
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

            $stmt = $mysqli->prepare("INSERT INTO tb_cadastrouser (nome, usuario, email, senha, telefone) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $nome, $usuario, $email, $senha_hash, $fone);

            if ($stmt->execute()) {
                header("Location: login.php");
                exit;
            } else {
                echo "<p style='color:red;'>Erro ao inserir: " . htmlspecialchars($stmt->error) . "</p>";
            }

            $stmt->close();
        }

        $check->close();
    }

    $mysqli->close();
}
?>
</body> 
</html>
