<?php ?>
  <BR />
  <div class="icons">
    <a href="logout.php">
      <img class="lighter" width="40" height="40" src="img/logoff.png" alt="logoff" title="Logout"></img>
    </a>
  </div>
  <div class="icons">
    <a href="settings.php">
      <img width="40" height="40" src="img/admin.png" alt="settings" title="Settings"></img>
    </a>
  </div>
  <div class="icons">
    <a href="search.php">
      <img width="40" height="40" src="img/search.png" alt="search" title="Search"></img>
    </a>
  </div>
  <div class="icons">
    <a href="summary.php">
      <img class="lighter" width="40" height="40" src="img/summary.png" alt="summary" title="Summary"></img>
    </a>
  </div>
  <BR /><BR /><BR />
<?php
$weather = $RUN->weather();  // if 40/41/42 don't work
$temp_cur=$weather->main->temp;
$feels_like=$weather->main->feels_like;
$temp_min=$weather->main->temp_min;
$temp_max=$weather->main->temp_max;
$icon=$weather->weather[0]->icon.".png";

$config = include(__DIR__ . '/../conf/config.php');
$city = $config['location'];
$json = '';

if ($city == '' ){
  $json = $RUN->geolocation();
  $city = $json['city'];
}

  echo "<br><font size=\"4\"><strong>".$city."</font></strong>";
  echo "<br>".$today."<br>";
  echo "<div style=\"font-size:100%;font-weight:bold;\"; id=\"time\"></div><br>";

  echo "<img src=\"https://openweathermap.org/img/wn/".$icon."\" width=\"91\" height=\"91\" ><br>";
  echo "<font size=\"6\">".$temp_cur."&deg;C<br></font>";
  echo "<I>Feels: ".$feels_like."&deg;C
    <br>Min: ".$temp_min."&deg;C
    <br>Max: ".$temp_max."&deg;C</I>";
?>
