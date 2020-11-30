<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
  if (! isset($_SESSION['valid'])){
    header("Location: ./index.php");
  }
?>
<html>
<head>
      <title>Personal Calendar</title>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
      <link href="style/main.css" type="text/css" rel="stylesheet" />
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <script src="js/time.js"></script>
</head>
<body onload="startTime()">
  <a href="logout.php"><img src="img/header.jpg" alt="Header" style="width:100%;height:100px;"></a>
  <script>
    function myFunction() {
      var w = window.innerWidth;
      var h = window.innerHeight;
      document.getElementById("demo").innerHTML = "Width: " + w + "<br>Height: " + h;
    }
  </script>
  <script>
    function openForm() {
      document.getElementById("myForm").style.display = "block";
    }
    function closeForm() {
      document.getElementById("myForm").style.display = "none";
    }
    function openEvent() {
      document.getElementById("myEvent").style.display = "block";
    }
    function closeEvent() {
      document.getElementById("myEvent").style.display = "none";
    }
  </script>
  <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include (__DIR__ . '/conf/calendar.php');
    require_once(__DIR__ . '/conf/dbconf.php');
    require(__DIR__ . '/conf/functions.php');
    $config = include(__DIR__ . '/conf/config.php');

    $RUN = new run;
    $today = date("l, d F");
  ?>

<div id="wrapper">

  <?php
    include (__DIR__ . '/main/newEvent.html');
    include (__DIR__ . '/main/editEvent.php');
  ?>

  <div class="topLeft">
    <?php
      include (__DIR__ . '/main/left.php');
    ?>
    <BR /><BR />
  </div>


  <div class="topMiddle">
    <?php
      $calendar = new Calendar();
      echo $calendar->show();
    ?>
  </div>

  <div class="topRight">
    <?php
      include (__DIR__ . '/main/right.php');
    ?>
  </div>
</div>


</body>
</html>
