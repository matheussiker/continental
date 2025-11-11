<?php
session_start();
include 'conecta.php';

// Captura o termo de pesquisa, se existir
$busca = $_GET['busca'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/pesquisar.css">
    <script src="https://kit.fontawesome.com/b0fbce6f8a.js" crossorigin="anonymous"></script>
    <title>Continental</title>
</head>
<body>
    <nav class="sidebar">
        <h1>CONTINENTAL</h1>
        <ol>
            <a href="feed.php"><li><i class="fa-solid fa-house"></i> Blog de Viagem</li></a>
            <a href="pesquisar.php"><li><i class="fa-solid fa-magnifying-glass"></i> Pesquisar</li></a>
            <a href="explorar.php"><li><i class="fa-solid fa-plane"></i> Explorar</li></a>
            <a href="perfil.php"><li><i class="fa-solid fa-user"></i> Perfil</li></a>
            <a href="../index.php"><li><i class="fa-solid fa-house"></i> Home</li></a>
            <a href="../paginas/info.html"><li><i class="fa-solid fa-circle-info"></i> Sobre</li></a>
            <a href="logout.php"><li><i class="fa-solid fa-right-from-bracket"></i> Sair</li></a>
        </ol>
    </nav>

    <div class="grande">
        <div class="pesquisa-container">
            <form method="GET" action="pesquisar.php" class="form-pesquisa">
                <input type="text" name="busca" placeholder="Buscar usuário..." value="<?= htmlspecialchars($busca) ?>" required>
                <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
        </div>

        <div class="resultado-pesquisa">
            <?php
            if ($busca !== '') {
                $stmt = $mysqli->prepare("SELECT id_user, nome, foto_perfil FROM tb_cadastrouser WHERE nome LIKE ?");
                $likeBusca = '%' . $busca . '%';
                $stmt->bind_param("s", $likeBusca);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($busca !== '') {
    $stmt = $mysqli->prepare("SELECT id_user, nome, foto_perfil FROM tb_cadastrouser WHERE nome LIKE ?");
    $likeBusca = '%' . $busca . '%';
    $stmt->bind_param("s", $likeBusca);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($user = $result->fetch_assoc()) {
            $perfilUrl = ($_SESSION['id_user'] ?? 0) == $user['id_user']
                ? 'perfil.php'
                : 'perfil_user.php?id=' . urlencode($user['id_user']);

            // Corrige caminho da imagem
            $nomeArquivo = basename($user['foto_perfil']);
            $caminhoServidor = $_SERVER['DOCUMENT_ROOT'] . '/Continental-main/uploads/' . $nomeArquivo;
            $fotoUrl = '/Continental-main/uploads/' . $nomeArquivo;

            if (empty($user['foto_perfil']) || !file_exists($caminhoServidor)) {
                $fotoUrl = '/Continental-main/php/uploads/perfis/default.jpg';
            }

            echo '<div class="card-user">';
            echo '<a href="' . $perfilUrl . '"><img src="' . htmlspecialchars($fotoUrl) . '" class="foto-perfil" alt="Foto de perfil"></a>';
            echo '<a href="' . $perfilUrl . '" class="nome-autor">' . htmlspecialchars($user['nome']) . '</a>';
            echo '</div>';
        }
        } else {
            echo '<p>Nenhum usuário encontrado com esse nome.</p>';
        }
    }
            }
            ?>
        </div>
    </div>
</body>
</html>
