<?php

session_start();
session_destroy(); #<- finaliza uma sessão, apagando todas as suas variáveis de sessão de uma vez (na prática, um logout).
header('Location: ./index.php');
exit();