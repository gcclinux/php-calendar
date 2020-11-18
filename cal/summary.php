<?php
session_start();

if (! isset($_SESSION['valid'])){
  header("Location: ./index.php");
}

require_once "conf/dbconf.php";
require_once "conf/functions.php";
$config = include(__DIR__ . '/conf/config.php');

$RUN = new run;
$today = date("l, d F");
$search = date("d-m-Y");
$events = $RUN->getDateEvent($search);

$tomorrow = date('l, d F',strtotime($today . "+1 days"));
$search_tomorrow = date('d-m-Y',strtotime($today . "+1 days"));
$events_tomorrow = $RUN->getDateEvent($search_tomorrow);

$third_day = date('l, d F',strtotime($today . "+2 days"));
$search_third_day = date('d-m-Y',strtotime($today . "+2 days"));
$events_third_day = $RUN->getDateEvent($search_third_day);

$weather = $RUN->weather();  // if 43/44/45 don't work
$temp_cur=$weather->main->temp;
$feels_like=$weather->main->feels_like;
$temp_min=$weather->main->temp_min;
$temp_max=$weather->main->temp_max;
$icon=$weather->weather[0]->icon.".png";

$city = $config['location'];
$json = '';

if ($city == '' ){
  $json = $RUN->geolocation();
  $city = $json['city'];
}

?>

<html>
<head>
      <title>Calendar Summary</title>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
      <script src="js/time.js"></script>
</head>
<body onload="startTime()">
   <a href="main.php"><img src="img/header.jpg" alt="Header" style="width:100%;height:100px;"></a>
   <h1 style="text-align: center;">Calendar Summary</h1>
   <center>
   <br><font size="4"><strong><?php echo "$city"; ?></font></strong>
   <br><?php echo "$today"; ?><br>
   <div style="font-size:100%;font-weight:bold;" id="time"></div>

   <img style="filter: brightness(70%);" src="https://openweathermap.org/img/wn/<?php echo "$icon"; ?>" width="91" height="91" ><br>
   <font size="6"><?php echo "$temp_cur"; ?>&deg;C<br></font>
   <I>Feels: <?php echo "$feels_like"; ?> &deg;C
     Min: <?php echo "$temp_min"; ?> &deg;C
     Max: <?php echo "$temp_max"; ?> &deg;C</I><br><BR>
          <a href="main.php"><img src="img/cal-black.png" alt="Calendar" style="width:40px;height:40px;"></a>
     <h1 style="text-align: center;">Reminder</h1>
     <?php
      echo "<div style=\"width:50%\"><hr>";
      //TODAY
      echo "<B><U>".$today."</B></U><BR><BR>";
      if (sizeof($events) == 0){
        echo "&#8608; No Events Recorded for today &#8606;";
      } else {
         foreach($events as $sub) {
           $count = 1;
           $list = str_getcsv($sub);

           echo "<B>SUBJECT:&emsp;</B>".ucfirst($list[4])."<br>";
             echo "<B>TODAY</B> &emsp;".$list[1]."&emsp;&emsp; <B>FROM</B>&emsp;" .$list[2]."&emsp; &emsp;<B>UNTIL</B> ".$list[3]."&emsp;&emsp;<br>";
             echo "<BR>".($list[5])."<BR>";
             echo "</p>";
             echo "";
         }
       }
       // TOMORROW
       echo "<hr>";
       echo "<B><U>".$tomorrow."</B></U><BR><BR>";
       if (sizeof($events_tomorrow) == 0){
         echo "<p>&#8608; No Events in Calendar &#8606; </p>";
       } else {
          foreach($events_tomorrow as $sub) {
            $count = 1;
            $list = str_getcsv($sub);

            echo "<B>SUBJECT:&emsp;</B>".ucfirst($list[4])."<br>";
              echo "<B>TODAY</B> &emsp;".$list[1]."&emsp;&emsp; <B>FROM</B>&emsp;" .$list[2]."&emsp; &emsp;<B>UNTIL</B> ".$list[3]."&emsp;&emsp;<br>";
              echo "<BR>".($list[5])."<BR>";
              echo "</p>";
              echo "";
          }
        }
        // THIRD days
        echo "<hr>";
        echo "<B><U>". $third_day."</B></U><BR><BR>";
        if (sizeof($events_third_day) == 0){
          echo "<p>&#8608; No Events in Calendar &#8606; </p>";
        } else {
           foreach($events_third_day as $sub) {
             $count = 1;
             $list = str_getcsv($sub);

             echo "<B>SUBJECT:&emsp;</B>".ucfirst($list[4])."<br>";
               echo "<B>TODAY</B> &emsp;".$list[1]."&emsp;&emsp; <B>FROM</B>&emsp;" .$list[2]."&emsp; &emsp;<B>UNTIL</B> ".$list[3]."&emsp;&emsp;<br>";
               echo "<BR>".($list[5])."<BR>";
               echo "</p>";
               echo "";
           }
         }
     ?>
</body>
</html>
