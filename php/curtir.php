<?php
session_start();
require 'conecta.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Obtém o ID do usuário e do post
$id_user = $_SESSION['id_user'];
$id_post = $_POST['id_post'];

// Verifica se o usuário já curtiu o post
$stmt = $mysqli->prepare("SELECT * FROM tb_curtidas WHERE id_user = ? AND id_post = ?");
$stmt->bind_param("ii", $id_user, $id_post);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // O usuário ainda não curtiu o post, então insere a curtida
    $stmt = $mysqli->prepare("INSERT INTO tb_curtidas (id_user, id_post) VALUES (?, ?)");
    $stmt->bind_param("ii", $id_user, $id_post);
    $stmt->execute();

    // Atualiza o número de curtidas na tabela de posts
    $stmt = $mysqli->prepare("UPDATE tb_posts SET curtida = curtida + 1 WHERE idpost = ?");
    $stmt->bind_param("i", $id_post);
    $stmt->execute();
}

// Redireciona de volta para o feed
header('Location: feed.php');
exit;
?>