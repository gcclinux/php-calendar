<?php
session_start();
  if (! isset($_SESSION['valid'])){
    header("Location: ./index.php");
  }

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  $today = date("l, d F");
  $search = "";
  $message = "";

  require_once(__DIR__ . '/conf/dbconf.php');
  require(__DIR__ . '/conf/functions.php');
  $RUN = new run;
  $config = include(__DIR__ . '/conf/config.php');

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
  <a href="main.php"><img src="img/header.jpg" alt="Header" style="width:100%;height:100px;"></a>
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

<div id="wrapper">

  <?php
    include (__DIR__ . '/main/newEvent.php');
    include (__DIR__ . '/main/editEvent.php');
  ?>

  <div class="topLeft">
    <?php
      include (__DIR__ . '/main/left.php');
    ?>
    <BR /><BR />
  </div>


  <div class="topMiddle">
    <center>
      <h1 style="text-align: center;">Calendar Search</h1>
      <?php echo "$message"; ?>
      <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <BR>
          <B>Search Criteria</B><BR>
            <input style="width: 50%" type="text" name="search" value="<?php echo $search;?>">
            <br><br>
            <input type="submit" name="submit" value="Submit">
      </form>
      <?php
      //main.php?events=10-09-2020&month=09&year=2020
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["search"])) {
          $message = "Unable to search empty string";
        } else {
            $data = $RUN->searchString($_POST['search']);
            foreach($data	as $key) {
              $entry = str_getcsv($key);
              echo "<B>  DATE: </B>".$entry[1]." &#x21F0; <B>START TIME: </B>".$entry[2]."<BR><B>SUBJECT: </B>".$entry[4].
              "<BR> <a href=\"main.php?events=".$entry[1]."&month=".substr($entry[1], 3, -5)."&year=".substr($entry[1], 6, 9)."\">
                <img width=\"40\" height=\"45\" src=\"img/summary.png\" alt=\"summary\" title=\"Summary\"></img>
              </a>".
              "<BR><BR>";
            }
        }
      }
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
