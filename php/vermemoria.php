<?php
session_start();
require("conecta.php");

$id = isset($_GET['id']) ? intval($_GET['id']) : null;
if (!$id) {
    echo "Memória não encontrada.";
    exit;
}

// Buscar memória e dono
$stmt = $mysqli->prepare("SELECT * FROM memorias WHERE id_memoria = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$memoria = $stmt->get_result()->fetch_assoc();

if (!$memoria) {
    echo "Memória não existe.";
    exit;
}

$is_owner = isset($_SESSION['id_user']) && ($_SESSION['id_user'] === (int)$memoria['id_user']);

// Define o link de voltar dinamicamente:
if ($is_owner) {
    $link_voltar = "perfil.php"; // seu próprio perfil
} else {
    // perfil de outro usuário
    $link_voltar = "perfil_user.php?id=" . $memoria['id_user'];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Continental</title>
    <link rel="stylesheet" href="../css/vermermoria.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <a class="voltar" href="<?= htmlspecialchars($link_voltar) ?>">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-box-arrow-in-left" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M10 3.5a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 1 1 0v2A1.5 1.5 0 0 1 9.5 14h-8A1.5 1.5 0 0 1 0 12.5v-9A1.5 1.5 0 0 1 1.5 2h8A1.5 1.5 0 0 1 11 3.5v2a.5.5 0 0 1-1 0z"/>
        <path fill-rule="evenodd" d="M4.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H14.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708z"/>
        </svg>
    </a>

    <div class="topo">
        <h2><?= htmlspecialchars($memoria['titulo']) ?></h2>
        <p><?= date("d/m/Y", strtotime($memoria['data_viagem'])) ?></p>

        <?php if ($is_owner): ?>
        <!-- FORMULÁRIO PARA APAGAR MEMÓRIA (apenas visível ao dono) -->
        <form action="deletar_memoria.php" method="POST" onsubmit="return confirm('Tem certeza que deseja apagar esta memória?');">
            <input type="hidden" name="id_memoria" value="<?= (int)$memoria['id_memoria'] ?>">
            <button type="submit" class="btn-apagar">Apagar Memória</button>
        </form>
        <?php endif; ?>
    </div>

    <div class="galeria">
        <?php 
        for ($i = 1; $i <= 14; $i++) {
            $img = $memoria["img$i"] ?? '';
            if (!empty($img)) {
                echo "<img src='" . htmlspecialchars($img) . "' alt='Imagem $i'>";
            }
        }
        ?>
    </div>

</body>
</html>
