<?php
session_start();
require 'vendor/autoload.php';
include 'conn.php';

$idU = $_SESSION["idusuario"];
$nomeUsuario =$_SESSION["usuario"];


$sqlRelatorio = "SELECT * FROM tab_tarefas WHERE idUsuario='$idU'AND
statusTarefa = '1'
";
$result = mysqli_query($conn,$sqlRelatorio);
$quantidade = mysqli_num_rows($result);


$htmlRel ="<h1>RELATORIO DE TAREFAS FINALIZADAS</h1>
<br> <h2> NOME DO USUARIO : $nomeUsuario</h2><br>
<h3>Quantidade de tarefas :$quantidade</h3><br>";

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
    
        
   

    $htmlRel .="  <strong>  nome da Tarefa </strong> :$nome <br> -
    <strong> descrição :</strong>$desc <br> -
    <strong> prazo tarefa : </strong>  $praz -
    <strong> </strong>: $prior 
   
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

