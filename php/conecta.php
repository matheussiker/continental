<?php  
	$domain = "localhost";   
	$user = "root";          
	$password = "";          
	$database = "bd_continental";

	
	$mysqli = new mysqli($domain, $user, $password, $database);

	
	if ($mysqli->connect_errno) {
    	exit("Erro na conexão com o banco de dados: " . $mysqli->connect_error);
	}


	$mysqli->set_charset("utf8");
?>