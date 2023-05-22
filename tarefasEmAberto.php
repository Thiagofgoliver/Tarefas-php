<?php
session_start();
require 'vendor/autoload.php';
include 'conn.php';

$idU = $_SESSION["idusuario"];

$htmlRel ="<h1>TAREFAS EM ABERTO</h1>";
$sqlRelatorio = "SELECT * FROM tab_tarefas WHERE idUsuario='$idU'";
$result = mysqli_query($conn,$sqlRelatorio);
while($linha = mysqli_fetch_assoc($result)){
    $nome = $linha["nomeTarefa"];
    $desc = $linha["descTarefa"];
    $praz = $linha ["prazoTarefa"];
    if ($linha["priorTarefa"] ==1){
        $prior ="Baixa";

    }else if ($linha["priorTarefa"] ==2){
        $prior ="Média";


    }else {
        $prior ="Alta";


    }
    if($linha ["statusTarefa"]==1){
        $status = "Tarefa Finalizada";

    }else{
        $status = "tarefa em aberto";
    }

        
   

    $htmlRel .="  <strong>  nome da Tarefa </strong> :$nome <br> -
    <strong> descrição :</strong>$desc <br> -
    <strong> prazo tarefa : </strong>  $praz -
    <strong> :</strong>: $prior -
    <strong> status : </strong>  $status
     <br>"; 





     
}

// Instancia a classe
use Dompdf\Dompdf;
$dompdf = new Dompdf();

$dompdf->loadHtml($htmlRel);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Gera o PDF
//$dompdf->stream();
$dompdf->stream(
    "saida.pdf", // Nome do arquivo de saída 
    array(
        "Attachment" => false // Para download, altere para true 
    )
);

?>

