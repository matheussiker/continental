<?php
session_start();
require("conecta.php");

if (!isset($_SESSION['id_user'])) {
    die("Acesso negado.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_memoria'])) {
    $id_memoria = intval($_POST['id_memoria']);
    $id_user = $_SESSION['id_user'];

    // Verifica se a memória pertence ao usuário
    $stmt = $mysqli->prepare("SELECT * FROM memorias WHERE id_memoria = ? AND id_user = ?");
    $stmt->bind_param("ii", $id_memoria, $id_user);
    $stmt->execute();
    $memoria = $stmt->get_result()->fetch_assoc();

    if (!$memoria) {
        die("Memória não encontrada ou sem permissão.");
    }

    // Apaga imagens da memória (se armazenadas em arquivo)
    for ($i = 1; $i <= 14; $i++) {
        if (!empty($memoria["img$i"])) {
            $caminho = '../uploads/' . $memoria["img$i"]; // ajuste o caminho se necessário
            if (file_exists($caminho)) {
                unlink($caminho);
            }
        }
    }

    // Deleta a memória
    $stmt2 = $mysqli->prepare("DELETE FROM memorias WHERE id_memoria = ?");
    $stmt2->bind_param("i", $id_memoria);
    $stmt2->execute();

    header("Location: perfil.php"); // redireciona para o perfil, por exemplo
    exit;
}
?>
