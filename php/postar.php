<?php
session_start();
require 'conecta.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Processa o formulário quando enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user = $_SESSION['id_user'];
    $descricao = $_POST['descricao'];
    $local = $_POST['local'];
    $data = date('Y-m-d H:i:s'); // Data e hora atual
    $img = null; // Define a imagem como nula inicialmente

    // Verifica se uma imagem foi enviada
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        $imgName = uniqid() . '_' . basename($_FILES['foto']['name']);
        $uploadFile = $uploadDir . $imgName;

        // Move o arquivo para o diretório de uploads
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadFile)) {
            $img = $imgName; // Define o nome da imagem se o upload for bem-sucedido
        } else {
            echo '<p>Erro ao fazer upload da imagem. O post será salvo sem imagem.</p>';
        }
    }

    // Insere o post no banco de dados
    $stmt = $mysqli->prepare("INSERT INTO tb_posts (iduser, descricao, local, data, img, curtida) VALUES (?, ?, ?, ?, ?, 0)");
    $stmt->bind_param("issss", $id_user, $descricao, $local, $data, $img);
    if ($stmt->execute()) {
        header('Location: feed.php');
        exit;
    } else {
        echo '<p>Erro ao salvar o post.</p>';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/postar.css">
    <title>Continental</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <h2 id="logo">CONTINENTAL</h2>
    </header>
    <div class="container">
        <h1>Fazer uma Postagem</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="descricao">Descrição:</label>
                <textarea name="descricao" id="descricao" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="local">Local:</label>
                <input type="text" name="local" id="local" required>
            </div>
            <div class="form-group">
                <label for="foto">Foto (opcional):</label>
                <input type="file" name="foto" id="foto" accept="image/*">
            </div>
            <button type="submit" class="btn-postar">Postar</button>
        </form>
    </div>
</body>
</html>