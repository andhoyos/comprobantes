<?php 

// $servername = "localhost";
// $usuario = "root";
// $contraseña = "JQmXpphat6FJm9LZ";
// $dbname = "formularios";
$servername = "localhost";
$usuario = "root";
$contraseña = "";
$dbname = "formularios";

// Creando conexión
$connect = new mysqli($servername, $usuario, $contraseña, $dbname);

// Chequeando conexión
if(!$connect->connect_error){
	echo "";
}else{
	die("Error: " . $connect->connect_error);
}

 ?>