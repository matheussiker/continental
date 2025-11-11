<?php
session_start(); // inicia a sessão para poder destruí-la
session_unset(); // remove todas as variáveis de sessão
session_destroy(); // destrói a sessão

// redireciona para a página de login
header("Location: login.php");
exit;
?>