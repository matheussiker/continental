<?php
session_start();
include 'conecta.php';

if (!isset($_SESSION['id_user'])) {
    die("Acesso negado.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_post'])) {
    $id_post = intval($_POST['id_post']);
    $id_user = $_SESSION['id_user'];

    // Verifica se o post é do usuário logado
    $stmt = $mysqli->prepare("SELECT iduser, img FROM tb_posts WHERE idpost = ?");
    $stmt->bind_param("i", $id_post);
    $stmt->execute();
    $resultado = $stmt->get_result()->fetch_assoc();

    if ($resultado && $resultado['iduser'] == $id_user) {
        // Apaga imagem se existir
        if (!empty($resultado['img'])) {
            $caminho = '../uploads/' . $resultado['img'];
            if (file_exists($caminho)) {
                unlink($caminho);
            }
        }

        // Apaga o post
        $stmt2 = $mysqli->prepare("DELETE FROM tb_posts WHERE idpost = ?");
        $stmt2->bind_param("i", $id_post);
        $stmt2->execute();

        header("Location: feed.php");
        exit;
    } else {
        echo "Você não tem permissão para excluir este post.";
    }
}
?>