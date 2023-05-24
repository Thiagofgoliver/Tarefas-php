<?php
//definindo fuso horario
// date_default_timezone_set("America/Sao_Paulo");
// echo date('d-m')."<br>";
// echo date('H:i');

//criar função de data com mktime
//
// $datamk = mktime(06,30,00,12,30,1992);

// echo "Data criada com o MKTime : ".date("d-m-Y",$datamk)."<br>";

// echo ($datamk-10800);
//


//
// $data1 = date_create("2023-01-01");
// $data2 = date_create("2023-02-01");
// $diff = date_diff($data1,$data2);
// echo $diff -> format("Total da diferenca em dias= %a. ");
//

//exemplo para pegar os proximos 10 sabados
//
$dataInicial =strtotime("Saturday");
$dataFinal =strtotime ("+10 weeks",$dataInicial);

while($dataInicial <$dataFinal){
    echo date("d-m",$dataInicial)."<br>";
    $dataInicial = strtotime("+1 week",$dataInicial);
}

//