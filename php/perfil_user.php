<?php
require("conecta.php");

$id_user = $_GET['id'] ?? null;

if (!$id_user) {
    echo "Usuário não encontrado.";
    exit;
}

// Busca informações do usuário
$stmt = $mysqli->prepare("SELECT nome, bio, foto_perfil FROM tb_cadastrouser WHERE id_user = ?");
$stmt->bind_param("i", $id_user);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();

if (!$usuario) {
    echo "Usuário não encontrado.";
    exit;
}

$caminhoServidor = $_SERVER['DOCUMENT_ROOT'] . "/Continental-main/uploads/" . basename($usuario['foto_perfil']);

// Caminho que o navegador pode acessar
$caminhoNavegador = "../uploads/" . basename($usuario['foto_perfil']);

if (!empty($usuario['foto_perfil']) && file_exists($caminhoServidor)) {
    $fotoPerfilUrl = $caminhoNavegador;
} else {
    $fotoPerfilUrl = '/Continental-main/php/uploads/perfis/default.jpg';
}

// Busca as memórias do usuário
$stmt2 = $mysqli->prepare("SELECT id_memoria, titulo, imagem_capa, data_viagem FROM memorias WHERE id_user = ? ORDER BY data_viagem DESC");
$stmt2->bind_param("i", $id_user);
$stmt2->execute();
$memorias = $stmt2->get_result();
?>  

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <title>Continental</title>
    <link rel="stylesheet" href="../css/perfil.css">
</head>
<body>
    <header>
        <h2 id="logo">CONTINENTAL</h2>
        <ul>
            <li><a href="../index.php">Home</a></li>
            <li><a href="feed.php">Blog de Viagens</a></li>
            <li><a href="explorar.php">Explorar</a></li>
            <li><a href="../paginas/info.html">Sobre</a></li>
        </ul>
    </header>

    <section class="perfil">
        <div class="Imagem">
            <div class="fundo-perfil"></div>
            <img src="<?= $fotoPerfilUrl ?>" alt="Foto de perfil" id="img_perfil">
        </div>

        <div class="Dados">
            <h2>Memórias de <br><span id="nome"><?= htmlspecialchars($usuario['nome']) ?></span></h2>
            <p><?= htmlspecialchars($usuario['bio']) ?></p>
        </div>
    </section>

    <section class="title_memorias">
        <h3>Memórias de <?= htmlspecialchars($usuario['nome']) ?></h3>
    </section>

    <section class="memorias">
    <?php if ($memorias->num_rows > 0): ?>
        <?php while($memoria = $memorias->fetch_assoc()): ?>
            <div class="card_memoria">
                <a href="vermemoria.php?id=<?= $memoria['id_memoria'] ?>">
                    <div class="card">
                        <img src="<?= htmlspecialchars($memoria['imagem_capa']) ?>" alt="<?= htmlspecialchars($memoria['titulo']) ?>">
                        <h4><?= htmlspecialchars($memoria['titulo']) ?></h4>
                        <p><?= date("d/m/Y", strtotime($memoria['data_viagem'])) ?></p>
                    </div>
                </a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align:center;">Nenhuma memória publicada ainda.</p>
    <?php endif; ?>
    </section>
</body>
</html>
