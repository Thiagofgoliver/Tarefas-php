<?php
$nomeServidor = "localhost";
$userBanco = "root";
$senhaBanco = "";
$nomeBanco = "dbtarefas";

$conn = mysqli_connect($nomeServidor,$userBanco,$senhaBanco,$nomeBanco);

// if($conn){
//     echo "conectado";
// }