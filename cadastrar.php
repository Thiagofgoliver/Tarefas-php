<?php
include('conn.php');
include('funcoes.php');

if (isset($_POST["btnCadastrar"])) {
    $nUser = testar_valor($_POST["usuario"]);
    $senha = testar_valor($_POST["senha"]);
    $senhaC = testar_valor($_POST["senhaConfirm"]);

    if (!empty($nUser) && !empty($senha) && !empty($senhaC)) {
        if ($senha == $senhaC) {
            $sqlUser = "SELECT * FROM tab_usuarios
        WHERE usuario = '$nUser'";
            $result = mysqli_query($conn, $sqlUser);

            if (mysqli_num_rows($result) == 0) {
                $sqlInserir = "INSERT INTO tab_usuarios(usuario,senha)
            VALUES('$nUser','$senha')";
                if (mysqli_query($conn, $sqlInserir)) {
                    header('location:login.php?msg=cadok');
                } else {
                    header('location:cadastrar.php?msg=erro3');
                }
            } else {
                header('location:cadastrar.php?msg=erro2');
            }
        } else {
            header('location:cadastrar.php?msg=erro1');
        }
    } else {
        header('location:cadastrar.php?msg=erro0');
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Design by foolishdeveloper.com -->
    <title>Sistema tarefas</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <!--Stylesheet-->
    <style media="screen">
        *,
        *:before,
        *:after {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #080710;
        }

        .background {
            width: 430px;
            height: 520px;
            position: absolute;
            transform: translate(-50%, -50%);
            left: 50%;
            top: 50%;
        }

        .background .shape {
            height: 200px;
            width: 200px;
            position: absolute;
            border-radius: 50%;
        }

        .shape:first-child {
            background: linear-gradient(#1845ad,
                    #23a2f6);
            left: -80px;
            top: -80px;
        }

        .shape:last-child {
            background: linear-gradient(to right,
                    #ff512f,
                    #f09819);
            right: -30px;
            bottom: -80px;
        }

        form {
            height: 520px;
            width: 400px;
            background-color: rgba(255, 255, 255, 0.13);
            position: absolute;
            transform: translate(-50%, -50%);
            top: 50%;
            left: 50%;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 40px rgba(8, 7, 16, 0.6);
            padding: 50px 35px;
        }

        form * {
            font-family: 'Poppins', sans-serif;
            color: #ffffff;
            letter-spacing: 0.5px;
            outline: none;
            border: none;
        }

        form h3 {
            font-size: 32px;
            font-weight: 500;
            line-height: 42px;
            text-align: center;
        }

        label {
            display: block;
            margin-top: 30px;
            font-size: 16px;
            font-weight: 500;
        }

        input {
            display: block;
            height: 50px;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.07);
            border-radius: 3px;
            padding: 0 10px;
            margin-top: 8px;
            font-size: 14px;
            font-weight: 300;
        }

        ::placeholder {
            color: #e5e5e5;
        }

        button {
            margin-top: 50px;
            width: 100%;
            background-color: #ffffff;
            color: #080710;
            padding: 15px 0;
            font-size: 18px;
            font-weight: 600;
            border-radius: 5px;
            cursor: pointer;
        }

        .social {
            margin-top: 30px;
            display: flex;
        }

        .social div {
            background: red;
            width: 150px;
            border-radius: 3px;
            padding: 5px 10px 10px 5px;
            background-color: rgba(255, 255, 255, 0.27);
            color: #eaf0fb;
            text-align: center;
        }

        .social div:hover {
            background-color: rgba(255, 255, 255, 0.47);
        }

        .social .fb {
            margin-left: 25px;
        }

        .social i {
            margin-right: 4px;
        }
    </style>

</head>

<body>
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <form method="post">

        <h3>Novo usuario</h3>

        <?php if (isset($_GET["msg"]) && $_GET["msg"] == "erro0") { ?>
            <p style="color: #ff512f;text-align: center;">
                Preencha todos os campos !!!
            </p>
        <?php } ?>
        <?php if (isset($_GET["msg"]) && $_GET["msg"] == "erro1") { ?>
            <p style="color: #ff512f;text-align: center;">
                Senhas não conferem !!!
            </p>
        <?php } ?>
        <?php if (isset($_GET["msg"]) && $_GET["msg"] == "erro2") { ?>
            <p style="color: #ff512f;text-align: center;">
                Usuário ja existe !!!
            </p>
        <?php } ?>

        <?php if (isset($_GET["msg"]) && $_GET["msg"] == "erro3") { ?>
            <p style="color: #ff512f;text-align: center;">
                Erro ao cadastrar novo usuário !!!
            </p>
        <?php } ?>

        <label for="username">Usuario</label>
        <input type="text" placeholder="Usuario" id="username" name="usuario">

        <label for="password">Senha</label>
        <input type="password" placeholder="Senha" id="password" name="senha">

        <label for="password">Confirmação de Senha</label>
        <input type="password" placeholder="Senha" id="password" name="senhaConfirm">

        <button type="submit" name="btnCadastrar">Cadastrar Usuario</button>

    </form>
</body>

</html>