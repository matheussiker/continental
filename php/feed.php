<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style3.css">
    <script src="https://kit.fontawesome.com/b0fbce6f8a.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <title>Continental</title>
</head>
<body>
    <div class="sidebar">
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
    </div>

    <div class="postar">
        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
            <a href="postar.php"><i class="fa-solid fa-plus"></i> Postar</a>
        <?php else: ?>
            <a href="login.php">Faça login para postar.</a>
        <?php endif; ?>
    </div>

    <div class="grande">
        <?php
        include 'conecta.php';

        // Busca os posts mais recentes primeiro
        $query = "SELECT p.idpost, p.iduser, p.descricao, p.local, p.data, p.img, p.curtida, 
                         u.foto_perfil, u.nome 
                  FROM tb_posts p
                  JOIN tb_cadastrouser u ON p.iduser = u.id_user
                  ORDER BY p.data DESC";

        $result = $mysqli->query($query);

        if ($result && $result->num_rows > 0) {
            $idUsuarioLogado = $_SESSION['id_user'] ?? null;

            while ($row = $result->fetch_assoc()) {
                $meuPost = ($idUsuarioLogado && $idUsuarioLogado == $row['iduser']);
                $classePost = $meuPost ? 'post meu-post' : 'post';

                echo '<div class="' . $classePost . '">';
                echo '<div class="post-header">';

                // Define URL do perfil
                $perfilUrl = $meuPost ? 'perfil.php' : 'perfil_user.php?id=' . urlencode($row['iduser']);

                // Corrige o caminho da foto de perfil
                $nomeArquivoPerfil = basename($row['foto_perfil']);
                $caminhoPerfilServidor = $_SERVER['DOCUMENT_ROOT'] . '/Continental-main/uploads/' . $nomeArquivoPerfil;
                $fotoPerfil = '/Continental-main/uploads/' . $nomeArquivoPerfil;
                if (empty($row['foto_perfil']) || !file_exists($caminhoPerfilServidor)) {
                    $fotoPerfil = '/Continental-main/php/uploads/perfis/default.jpg';
                }

                echo '<a href="' . $perfilUrl . '"><img src="' . htmlspecialchars($fotoPerfil) . '" alt="Foto de perfil" class="foto-perfil"></a>';
                echo '<a href="' . $perfilUrl . '" class="nome-autor">' . htmlspecialchars($row['nome']) . '</a>';

                // Se for o dono do post, exibe botão de exclusão
                if ($meuPost) {
                    echo '<form method="POST" action="excluir_post.php" class="form-excluir" onsubmit="return confirm(\'Tem certeza que deseja excluir este post?\');">';
                    echo '<input type="hidden" name="id_post" value="' . htmlspecialchars($row['idpost']) . '">';
                    echo '<button type="submit" class="btn-excluir"><i class="fa-solid fa-trash"></i></button>';
                    echo '</form>';
                }

                echo '</div>'; // fim do cabeçalho

                // Local e descrição
                echo '<p class="desc"><strong>Local:</strong> ' . htmlspecialchars($row['local']) . '</p>';
                echo '<p>' . nl2br(htmlspecialchars($row['descricao'])) . '</p>';

                // Imagem do post (corrigida)
                if (!empty($row['img'])) {
                    $nomeArquivoImg = basename($row['img']);
                    $imgPathServidor = $_SERVER['DOCUMENT_ROOT'] . '/Continental-main/uploads/' . $nomeArquivoImg;
                    $imgUrl = '/Continental-main/uploads/' . $nomeArquivoImg;

                    if (file_exists($imgPathServidor)) {
                        echo '<img src="' . htmlspecialchars($imgUrl) . '" alt="Imagem do post" class="post-img">';
                    } else {
                        echo '<p>Imagem não encontrada.</p>';
                    }
                }

                // Mostra curtidas e data formatada
                $dataFormatada = date("d/m/Y H:i", strtotime($row['data']));
                echo '<div class="likeData">';
                echo '<p><strong>Curtidas:</strong> ' . htmlspecialchars($row['curtida']) . '</p>';
                echo '<p><strong>Data:</strong> ' . $dataFormatada . '</p>';
                echo '</div>';

                // Botão curtir
                echo '<form method="POST" action="curtir.php" class="form-curtir">';
                echo '<input type="hidden" name="id_post" value="' . htmlspecialchars($row['idpost']) . '">';
                echo '<button type="submit" class="btn-curtir"><i class="fa-solid fa-heart"></i></button>';
                echo '</form>';

                echo '</div>'; // fim do post
            }
        } else {
            echo '<p>Nenhum post encontrado.</p>';
        }

        $result->free();
        $mysqli->close();
        ?>
    </div>
</body>
</html>
