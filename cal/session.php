<?php
session_start();

$config = include(__DIR__ . '/conf/config.php');

if (isset($_POST["code"])){
  $code = $_POST["code"];
  $login = $config['login'];
  if ($code != $login){
    header("Location: index.php");
  } else {
    $_SESSION['valid'] = true;
    $_SESSION['timeout'] = time();
    header("Location: summary.php");
  }
}
?>
