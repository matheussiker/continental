<?php
session_start();
include 'conecta.php';

// Busca só as imagens dos posts
$query = "SELECT img, idpost FROM tb_posts WHERE img IS NOT NULL AND img != '' ORDER BY data DESC";
$result = $mysqli->query($query);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Continental</title>
<link rel="stylesheet" href="../css/explorar.css">
<!-- FontAwesome para os ícones (se quiser adicionar) -->
<script src="https://kit.fontawesome.com/b0fbce6f8a.js" crossorigin="anonymous"></script>

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

    <main class="main-content">

        <div class="grid">
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $imgPath = '../uploads/' . htmlspecialchars($row['img']);
                    if (file_exists($imgPath)) {
                        echo '<a href="feed.php?id=' . $row['idpost'] . '" class="grid-item">';
                        echo '<img src="' . $imgPath . '" alt="Foto do post">';
                        echo '</a>';
                    }
                }
            } else {
                echo "<p>Nenhuma foto para mostrar.</p>";
            }
            ?>
        </div>
    </main>

</body>
</html>
