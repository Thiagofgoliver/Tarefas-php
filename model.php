<?php
include('conn.php');
include('funcoes.php');

//Cadastrar tarefa no banco - php

    if (
      !empty($_GET["nometarefa"])
      && !empty($_GET["descricao"])
      && !empty($_GET["datatarefa"])
      && !empty($_GET["prioridade"])
    ) {
      $nomeT = testar_valor($_GET["nometarefa"]);
      $descT =testar_valor($_GET["descricao"]);
      $dataT =testar_valor($_GET["datatarefa"]);
      $priorT =testar_valor($_GET["prioridade"]);
  
      $sql = "INSERT INTO 
      tab_tarefas(nomeTarefa,descTarefa,prazoTarefa,priorTarefa)
      VALUES('$nomeT','$descT','$dataT','$priorT')";
  
      if (mysqli_query($conn, $sql)) {
        header('location:index.php?msg=cadok');
      } else {
        header('location:index.php?msg=caderro2');
      }
    } else {
      header('location:index.php?msg=caderro1');
    }

  