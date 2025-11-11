    <?php
session_start();
require("conecta.php");

$id_user = $_SESSION["id_user"];

// Atualiza os dados se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $bio = $_POST["bio"];

    // Verifica se uma nova imagem foi enviada
    if (!empty($_FILES["foto_perfil"]["name"])) {
        $extensao = pathinfo($_FILES["foto_perfil"]["name"], PATHINFO_EXTENSION);
        $nome_unico = uniqid("perfil_", true) . "." . $extensao;

        // Caminho físico onde será salvo (acima da pasta /php)
        $caminho_arquivo = "../uploads/" . $nome_unico;

        // Caminho que será salvo no banco e usado no HTML
        $foto_perfil = "/Continental-main/uploads/" . $nome_unico;

        // Faz o upload
        if (!move_uploaded_file($_FILES["foto_perfil"]["tmp_name"], $caminho_arquivo)) {
            echo "Erro ao fazer upload.";
            exit();
        }
    } else {
        // Nenhuma imagem enviada: mantém a imagem atual
        $stmt_img = $mysqli->prepare("SELECT foto_perfil FROM tb_cadastrouser WHERE id_user = ?");
        $stmt_img->bind_param("i", $id_user);
        $stmt_img->execute();
        $foto_perfil = $stmt_img->get_result()->fetch_assoc()["foto_perfil"];
        $stmt_img->close();
    }

    // Atualiza os dados no banco
    $stmt = $mysqli->prepare("UPDATE tb_cadastrouser SET nome = ?, bio = ?, foto_perfil = ? WHERE id_user = ?");
    $stmt->bind_param("sssi", $nome, $bio, $foto_perfil, $id_user);
    $stmt->execute();
    $stmt->close();

    header("Location: perfil.php");
    exit();
}

// Consulta os dados atuais para exibir no formulário
$stmt = $mysqli->prepare("SELECT nome, bio, foto_perfil FROM tb_cadastrouser WHERE id_user = ?");
$stmt->bind_param("i", $id_user);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Continental</title>
    <link rel="stylesheet" href="../css/editarPerfil.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <h2 id="logo">CONTINENTAL</h2>
    </header>

    <main>
        <section class="formulario-editar">
            <h2>Editar Perfil</h2>
            <form action="editar_perfil.php" method="post" enctype="multipart/form-data">
                <div class="dados">
                    <div class="nome">
                        <label for="nome">Nome:</label>
                        <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required>
                    </div>
                    <div class="foto-perfil">
                        <div class="preview">
                            <p>Foto atual:</p>
                            <img src="<?= htmlspecialchars($usuario['foto_perfil']) ?>" alt="Foto atual" width="150">
                        </div>
                        <label for="foto_perfil">Nova foto de Perfil:</label>
                        <label for="foto_perfil" class="custom-file-upload">
                            Selecionar nova foto
                        </label>
                        <input type="file" name="foto_perfil" id="foto_perfil">
                    </div>
                    <div class="bio">
                        <label for="bio">Bio:</label>
                        <textarea name="bio" id="bio" rows="4" maxlength="255"><?= htmlspecialchars($usuario['bio']) ?></textarea>
                    </div>
                </div>
                <div class="botoes">
                    <button type="submit">Salvar Alterações</button>
                    <a href="perfil.php">Voltar ao Perfil</a>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
