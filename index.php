<?php
session_start();
include('conn.php');
// echo "<script>alert('ok')</script>";
//inclusão das funções
include('funcoes.php');

if (!isset($_SESSION["usuario"])) {
  header('location:login.php?msg=userna');
}

//Alterar senha do usuario - Configurações
if(isset($_GET["btnsalvarconfig"])){
  $idU = $_GET["idUser"];
  $userN = $_GET["usuario"];
  $senhaA = $_GET["senhaAtual"];
  $novaSenha = $_GET["novaSenha"];

  if(!empty($senhaA) && !empty($novaSenha)){
    if($senhaA != $novaSenha){
        $sqlVerificarSenha = "SELECT * FROM tab_usuarios 
        WHERE senha='$senhaA' and idUser='$idU'";
        $result = mysqli_query($conn,$sqlVerificarSenha);

        if(mysqli_num_rows($result) > 0){
          $sqlAltSenha = "UPDATE tab_usuarios 
          SET senha='$novaSenha'
          WHERE idUser='$idU'";
          if(mysqli_query($conn,$sqlAltSenha)){
            header('location:index.php?msg=altsenhaok');
          }else{
            header('location:index.php?msg=erroaltsenha4');
          }

        }else{
          header('location:index.php?msg=erroaltsenha3');
        }

    }else{-
      header('location:index.php?msg=erroaltsenha2');
    }
  }else{
    header('location:index.php?msg=erroaltsenha1');
  }
}


//Finalizar tarefa
if (isset($_GET["idfinalizar"])) {
  $idfinalizar = $_GET["idfinalizar"];

  $sqlFinalizar = "UPDATE tab_tarefas SET
  statusTarefa='1' WHERE id='$idfinalizar'";

  if (mysqli_query($conn, $sqlFinalizar)) {
    header('Location:index.php?msg=finalizarok');
  } else {
    header('Location:index.php?msg=finalizarerro');
  }
}

//Atualizar tarefa
if (isset($_GET["btnAlterar"])) {
  if (
    !empty($_GET["nometarefa"])
    && !empty($_GET["descricao"])
    && !empty($_GET["datatarefa"])
    && !empty($_GET["prioridade"])
  ) {

    $nomeT = testar_valor($_GET["nometarefa"]);
    $descT = testar_valor($_GET["descricao"]);
    $dataT = testar_valor($_GET["datatarefa"]);
    $priorT = testar_valor($_GET["prioridade"]);
    $idT = $_GET["idtarefa"];

    $sqlUpdate = "UPDATE tab_tarefas SET 
  nomeTarefa='$nomeT', descTarefa='$descT',
  prazoTarefa='$dataT',priorTarefa='$priorT'
  WHERE id='$idT'";

    if (mysqli_query($conn, $sqlUpdate)) {
      header('location:index.php?msg=upok');
    } else {
      header('location:index.php?msg=uperro2');
    }
  } else {
    header('location:index.php?msg=uperro1');
  }
}

//Deletar tarefa
if (isset($_GET["idtarefaexc"])) {
  $idT = $_GET["idtarefaexc"];
  $sqlDelete = "DELETE FROM tab_tarefas WHERE id='$idT'";
  if (mysqli_query($conn, $sqlDelete)) {
    header('location:index.php?msg=delok');
  } else {
    header('location:index.php?msg=delerro');
  }
}

//id do usuario pego na sessão do login
$id = $_SESSION["idusuario"];

$statusT = (isset($_GET["tfin"]) && $_GET["tfin"] == "1") ? 1 : 0;

//Buscar Tarefa pela data
if (isset($_GET["btnBuscar"])) {
  $dataT = $_GET["dataBuscar"];
  $sqlBuscar = "SELECT * FROM tab_tarefas 
  WHERE prazoTarefa LIKE '$dataT%' 
  and idUsuario='$id' and statusTarefa='0'";
  $result = mysqli_query($conn, $sqlBuscar);
} else {
  //Selecionar tarefas do banco - php
  $sqlSelect = "SELECT * FROM tab_tarefas 
  WHERE idUsuario='$id' and statusTarefa='$statusT' ";
  $result = mysqli_query($conn, $sqlSelect);
}
//Paginação
$pag = (isset($_GET["pagina"]) ? $_GET["pagina"] : 1);
$quantReg = mysqli_num_rows($result);
$quant_p_pag = 6;
$quant_pag = ceil($quantReg / $quant_p_pag);
$inicio = ($quant_p_pag * $pag) - $quant_p_pag;
$sqlPag = "SELECT * FROM tab_tarefas 
  WHERE idUsuario='$id' and statusTarefa='$statusT'
  LIMIT $inicio,$quant_p_pag";
$result = mysqli_query($conn, $sqlPag);



?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <title>Tarefas - Senac</title>
  <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
  <link href="css/styles.css" rel="stylesheet" />
  <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
  <?php include("estrutura/menu_superior.php") ?>

  <div id="layoutSidenav">
    <div id="layoutSidenav_nav">
      <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
          <div class="nav">
            <div class="sb-sidenav-menu-heading">Menu</div>
            <a class="nav-link" href="index.php">
              <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
              Home
            </a>
            <div class="sb-sidenav-menu-heading"></div>
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
              <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
              Sistema
              <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
              <nav class="sb-sidenav-menu-nested nav">
                <a class="nav-link" href="">Cadastro de Tarefas</a>
                <a class="nav-link" href="index.php?tfin=1">Tarefas concluídas</a>
              </nav>
            </div>
          </div>
        </div>
      </nav>
    </div>


    <div id="layoutSidenav_content">
      <main>
        <div class="container-fluid px-4">
          <h1 class="mt-4">Gerenciador de tarefas <?php if ($statusT == 1) {
                                                    echo "- Tarefas Finalizadas";
                                                  } ?></h1>

          <?php if (isset($_GET["msg"]) && $_GET["msg"] == "cadok") { ?>
            <div class="alert alert-success" role="alert">
              Cadastro Realizado com sucesso!!!
            </div>
          <?php } ?>

          <?php if (isset($_GET["msg"]) && $_GET["msg"] == "caderro1") { ?>
            <div class="alert alert-danger" role="alert">
              Preencha todos os campos!!!
            </div>
          <?php } ?>

          <?php if (isset($_GET["msg"]) && $_GET["msg"] == "caderro2") { ?>
            <div class="alert alert-danger" role="alert">
              Erro ao tentar nserir informações no banco!!!
            </div>
          <?php } ?>

          <?php if (isset($_GET["msg"]) && $_GET["msg"] == "upok") { ?>
            <div class="alert alert-success" role="alert">
              Atualização realizada com sucesso!!!
            </div>
          <?php } ?>

          <?php if (isset($_GET["msg"]) && $_GET["msg"] == "uperro1") { ?>
            <div class="alert alert-danger" role="alert">
              Preencha todos os campos!!!
            </div>
          <?php } ?>

          <?php if (isset($_GET["msg"]) && $_GET["msg"] == "uperro2") { ?>
            <div class="alert alert-danger" role="alert">
              Erro ao tentar atualizar informações no banco!!!
            </div>
          <?php } ?>

          <?php if (isset($_GET["msg"]) && $_GET["msg"] == "delok") { ?>
            <div class="alert alert-success" role="alert">
              Tarefa Excluida !!!
            </div>
          <?php } ?>

          <?php if (isset($_GET["msg"]) && $_GET["msg"] == "delerro") { ?>
            <div class="alert alert-danger" role="alert">
              Erro ao excluir Tarefa !!!
            </div>
          <?php } ?>

          <?php if (isset($_GET["msg"]) && $_GET["msg"] == "erroaltsenha1") { ?>
            <div class="alert alert-danger" role="alert">
              Preencha todos os campos para alterar a senha !!!
            </div>
          <?php } ?>

          <?php if (isset($_GET["msg"]) && $_GET["msg"] == "erroaltsenha2") { ?>
            <div class="alert alert-danger" role="alert">
             A nova senha não pode ser igual a senha atual !!!
            </div>
          <?php } ?>

          <?php if (isset($_GET["msg"]) && $_GET["msg"] == "erroaltsenha3") { ?>
            <div class="alert alert-danger" role="alert">
             A senha atual está incorreta !!!
            </div>
          <?php } ?>

          <?php if (isset($_GET["msg"]) && $_GET["msg"] == "erroaltsenha4") { ?>
            <div class="alert alert-danger" role="alert">
             Erro ao efetuar atualização no banco !!!
            </div>
          <?php } ?>

          <?php if (isset($_GET["msg"]) && $_GET["msg"] == "altsenhaok") { ?>
            <div class="alert alert-success" role="alert">
              Senha alterada com sucesso !!!
            </div>
          <?php } ?>

          <ol class="breadcrumb mb-4">
          </ol>

          <div class="row">
            <div class="col-xl-2 col-md-3">
              <div class="card bg-primary text-white mb-4">
                <button type="button" class="btn btn-success" id="btn_entrada" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@mdo">Incluir <i class="fa fa-plus"></i></button>
              </div>
            </div>
          </div>
          <table class="table table-hover">
            <thead>
              <tr class="col-12">
                <th scope="col-2">Editar</th>
                <th scope="col-2">Excluir</th>
                <th scope="col-2">Nome</th>
                <th scope="col-4">Descrição</th>
                <th scope="col-4">Prazo</th>
                <th scope="col-4">Prioridade</th>
                <th scope="col-2">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($linha = mysqli_fetch_assoc($result)) {
                $modalAtualizar = "modalAtualizar" . $linha["id"];
                $modalDeletar = "modalDeletar" . $linha["id"];
                $modalFinalizar = "modalFinalizar" . $linha["id"];
              ?>
                <tr class="<?php if ($statusT == 1) {
                              echo "table-success";
                            } ?>">
                  <th style="cursor:pointer" data-bs-toggle="modal" data-bs-target="#<?php echo $modalAtualizar ?>">
                    <i class="fa-solid fa-pen" style="color: #236c1e;"></i>
                  </th>
                  <th style="cursor:pointer" data-bs-toggle="modal" data-bs-target="#<?php echo $modalDeletar ?>">
                    <i class="fa-solid fa-trash" style="color: #f01414;"></i>
                  </th>
                  <td><?php echo $linha["nomeTarefa"] ?></td>
                  <td><?php echo $linha["descTarefa"] ?></td>
                  <td><?php echo $linha["prazoTarefa"] ?></td>
                  <td><?php
                      if ($linha["priorTarefa"] == 1) {
                        echo "Baixa";
                      } else if ($linha["priorTarefa"] == 2) {
                        echo "Média";
                      } else {
                        echo "Alta";
                      }
                      ?></td>
                  <td>
                    <div class="form-check form-switch">
                      <?php if ($statusT == 0) { ?>
                        <input class="form-check-input" type="checkbox" data-bs-toggle="modal" data-bs-target="#<?= $modalFinalizar ?>" role="switch" id="flexSwitchCheckChecked">
                        <label class="form-check-label" for="flexSwitchCheckChecked">Finalizar</label>
                      <?php } else { ?>
                        <label class="form-check-label" for="flexSwitchCheckChecked">Finalizada</label>
                      <?php } ?>
                    </div>
                  </td>
                </tr>

                <div class="modal fade text-dark" id="<?php echo $modalAtualizar ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog text-dark">
                    <div class="modal-content text-dark">
                      <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Alteração de Tarefa</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body text-dark">
                        <form class="form-group text-white">
                          <div class="mb-3">
                            <label class="form-label text-dark">Id da Tarefa</label>
                            <input type="text" class="form-control" name="idtarefa" readonly value="<?php echo $linha["id"] ?>">
                          </div>
                          <div class="mb-3">
                            <label class="form-label text-dark">Nome da Tarefa</label>
                            <input type="text" class="form-control" name="nometarefa" value="<?php echo $linha["nomeTarefa"] ?>">
                          </div>
                          <div class="mb-3">
                            <label class="form-label text-dark">Descrição da tarefa</label> <textarea class="form-control" name="descricao" rows="3"> <?php echo $linha["descTarefa"] ?></textarea>
                          </div>
                          <div class="row">
                            <div class="mb-3 col-6">
                              <label class="form-label text-dark">Data / Prazo</label>
                              <input type="datetime-local" value="<?php echo $linha["prazoTarefa"] ?>" class="form-control" name="datatarefa">
                            </div>
                            <div class="mb-3 col-6">
                              <label class="form-label text-dark">Prioridade</label>
                              <select name="prioridade" class="form-select">
                                <option value="1" <?php if ($linha["priorTarefa"] == 1) {
                                                    echo "selected";
                                                  } ?>>baixa</option>
                                <option value="2" <?php if ($linha["priorTarefa"] == 2) {
                                                    echo "selected";
                                                  } ?>>média</option>
                                <option value="3" <?php if ($linha["priorTarefa"] == 3) {
                                                    echo "selected";
                                                  } ?>>alta</option>
                              </select>
                            </div>
                          </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" name="btnAlterar" value="Atualizar Tarefa" class="btn btn-primary">Atualizar</button>
                      </div>
                      </form>
                    </div>
                  </div>
                </div>


                <div class="modal fade text-dark" id="<?= $modalDeletar ?>" tabindex="-1">
                  <div class="modal-dialog text-dark">
                    <div class="modal-content text-dark">
                      <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Deletar de Tarefa</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body text-dark">
                        <p>Deseja excluir esta tarefa?</p>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
                        <a href="index.php?idtarefaexc=<?php echo $linha["id"] ?>">
                          <button type="submit" class="btn btn-danger">Sim</button>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>


                <div class="modal fade" id="<?= $modalFinalizar ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Finalizar Tarefa</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        Deseja finalizar esta tarefa?<?= $modalFinalizar ?>
                      </div>
                      <div class="modal-footer">
                        <button type="button" onclick="ZerarCheck()" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
                        <a href="index.php?idfinalizar=<?= $linha["id"] ?>">
                          <button type="button" class="btn btn-primary">Sim</button>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>


              <?php } ?>
            </tbody>
          </table>

          <div class="container mt-5">
            <nav aria-label="Page navigation example">
              <ul class="pagination justify-content-center">

                <?php
                $pagAnterior = $pag - 1;

                if ($pagAnterior != 0) {
                ?>
                  <li class="page-item">
                    <a class="page-link" href="index.php?pagina=<?= $pagAnterior ?>&tfin=<?= $statusT ?>">
                      <span aria-hidden="true">&laquo;</span>
                    </a>
                  </li>
                <?php } else { ?>
                  <li class="page-item">
                    <a class="page-link">
                      <span aria-hidden="true">&laquo;</span>
                    </a>
                  </li>
                <?php } ?>

                <?php for ($i = 1; $i <= $quant_pag; $i++) { ?>
                  <li class="page-item <?php if ($pag == $i) echo "active"  ?>">
                    <a class="page-link" href="index.php?pagina=<?= $i ?>&tfin=<?= $statusT ?>">
                      <?= $i ?>
                    </a>
                  </li>
                <?php }
                $pagPost = $pag + 1;

                if ($pagPost <= $quant_pag) {
                ?>
                  <li class="page-item">
                    <a class="page-link" href="index.php?pagina=<?= $pagPost ?>&tfin=<?= $statusT ?>">
                      <span aria-hidden="true">&raquo;</span>
                    </a>
                  </li>
                <?php } else { ?>
                  <li class="page-item">
                    <a class="page-link" href="#">
                      <span aria-hidden="true">&raquo;</span>
                    </a>
                  </li>
                <?php  } ?>
              </ul>
            </nav>
          </div>





      </main>
      <footer class="py-4 bg-light mt-auto">
        <div class="container-fluid px-4">
          <div class="d-flex align-items-center justify-content-between small">
            <div class="text-muted">Copyright &copy; Website 2023</div>

          </div>
        </div>
      </footer>



      <div class="modal fade text-dark" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog text-dark">
          <div class="modal-content text-dark">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalLabel">Nova Tarefa</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-dark">
              <form class="form-group text-white" action="model.php">
                <div class="mb-3">
                  <label class="form-label text-dark">Nome da Tarefa</label>
                  <input type="text" class="form-control" name="nometarefa">
                </div>
                <div class="mb-3">
                  <label class="form-label text-dark">Descrição da tarefa</label> <textarea class="form-control" name="descricao" rows="3"></textarea>
                </div>
                <div class="row">
                  <div class="mb-3 col-6">
                    <label class="form-label text-dark">Data / Prazo</label>
                    <input type="datetime-local" value="<?= date("Y-m-d\TH:i:s") ?>" class="form-control" name="datatarefa">
                  </div>
                  <div class="mb-3 col-6">
                    <label class="form-label text-dark">Prioridade</label>
                    <select name="prioridade" class="form-select">
                      <option value="1">baixa</option>
                      <option value="2">média</option>
                      <option value="3">alta</option>
                    </select>
                  </div>

                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
              <button type="submit" name="btncadastrar" value="Cadastrar Tarefa" class="btn btn-primary">Cadastrar</button>
            </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade text-dark" id="modalConfiguracoes" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog text-dark">
      <div class="modal-content text-dark">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Configurações do usuário</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-dark">
          <form class="form-group text-white">
            <div class="mb-3">
              <label class="form-label text-dark">ID do Usuario</label>
              <input type="text" class="form-control" name="idUser" value="<?= $_SESSION["idusuario"] ?>" readonly>
            </div>
            <div class="mb-3">
              <label class="form-label text-dark">Nome do Usuario</label>
              <input type="text" class="form-control" name="usuario" readonly value="<?= $_SESSION["usuario"] ?>">
            </div>
            <div class="mb-3">
              <label class="form-label text-dark">Senha Atual</label>
              <input type="text" class="form-control" name="senhaAtual">
            </div>
            <div class="mb-3">
              <label class="form-label text-dark">Nova senha</label>
              <input type="text" class="form-control" name="novaSenha">
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          <button type="submit" name="btnsalvarconfig" class="btn btn-primary">Salvar</button>
        </div>
        </form>
      </div>
    </div>
  </div>





  <script>
    function ZerarCheck() {
      let checkFinalizar = document.getElementsByClassName('form-check-input')
      for (let i = 0; i < checkFinalizar.length; i++) {
        checkFinalizar[i].checked = false
      }
    }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script src="js/scripts.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
  <script src="assets/demo/chart-area-demo.js"></script>
  <script src="assets/demo/chart-bar-demo.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
  <script src="js/datatables-simple-demo.js"></script>
</body>

</html>