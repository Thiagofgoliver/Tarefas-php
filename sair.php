<?php
session_start();

session_unset(); //remove as variaveis de sessão

session_destroy(); //acaba com sessão


header('location:login.php');

