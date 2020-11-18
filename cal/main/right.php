<?php ?>
<button class="button_newEvent" onclick="openForm()"> &#8608; New Event &#8606;</button>
<?php
  if (isset($_GET['events'])) {
    $get_event = ($_GET['events']);
    $getEvents = $RUN->getDateEvent($get_event);
    echo "<h3>".date("d M Y", strtotime($get_event))."</h3>";
  } else {
    $thisDay = date("d-m-Y");
    $getEvents = $RUN->get_schedule($thisDay);
    echo "<h3>".date("d M Y", strtotime($thisDay))."</h3>";
  }
  $size = sizeof($getEvents);
  if ($size == 0){
      echo "<p>### No Events Recorded! ###</p>";
    } else {
      echo "<div class=\"eventSummary\">";
      $sizeCount = 1;
        foreach($getEvents as $sub) {
          $count = 1;
            $list = str_getcsv($sub);
            if (strlen($list[4]) > 32){
                echo "<a class=\"eventSummary\"><B>SUBJECT:</B>&emsp;&nbsp;&nbsp;".substr(ucfirst($list[4]), 0, 30)."...</a>";
            } else {
                echo "<a class=\"eventSummary\"><B>SUBJECT:</B>&emsp;&nbsp;&nbsp;".ucfirst($list[4])."</a>";
            }
            echo "<br>";

            if ($list[2] == "00:00" && $list[3] == "00:00"){
              echo "<a class=\"eventSummary\"><B>START:</B>&emsp;&emsp;&nbsp;&nbsp;&nbsp; All Day Event</a>";
            } else {
              echo "<a class=\"eventSummary\"><B>START:</B>&emsp;&emsp;&nbsp;&nbsp;&nbsp; ".$list[2]."-".$list[3]."</a>";
            }
            echo "<br>";

            $attach =  $RUN->get_attachment($list[7]);

            if (sizeof($attach) != 0){
              foreach($attach	as $key) {
                $files = str_getcsv($key);
                echo "<a class=\"eventSummary\" href=\"".$files[2]."\"><B>ATTACH:</B>&emsp;&emsp;&nbsp;<U>".$count."</U></a> ";
                $count++;
              }
            } else {
              echo "<a class=\"eventSummary\"><B>ATTACH:</B>&emsp;&nbsp;&nbsp;&nbsp; 0</a>";
            }
            echo "<br>";
            if (strlen($list[5]) > 34){
                echo "<a class=\"eventSummary\"><B>DETAILS:</B>&emsp;&nbsp;&nbsp;&nbsp;".substr(ucfirst($list[5]), 0, 36)."...</a>";
            } else {
                echo "<a class=\"eventSummary\"><B>DETAILS:</B>&emsp;&nbsp;&nbsp;&nbsp;".ucfirst($list[5])."</a>";
            }
            echo "<br>";
            echo "<a class=\"eventSummary\"><B>CATEGORY:</B>&nbsp;&nbsp;&nbsp;".ucfirst($list[6])."</a>";
            echo "<br>";
            echo "<form method=\"POST\" onsubmit=\"return openEvent()\">
                  <a class=\"eventSummary\"><B>ACTION:</B>&emsp;&nbsp;&nbsp;
                  <input type=\"hidden\" name=\"id\" value=\"".$list[0]."\">
                  <input type=\"hidden\" name=\"subject\" value=\"".$list[4]."\">
                  <input type=\"hidden\" name=\"uniqueid\" value=\"".$list[6]."\">
                  <input type=\"hidden\" name=\"returndate\" value=\"".$list[1]."\">
                  <input class=\"action\" type=\"submit\" value=\"[ View | Edit | Delete ]\"></a>
            </form>";
            if ($sizeCount != $size) {
              echo "<hr class=\"eventSummary\" style=\"width:80%;\"></hr></a></p>";
            } else {
              echo "<a class=\"eventSummary\"><br></a>";
            }
            $sizeCount++;
        }
      echo "</div>";
  }
?>
</div>
<?php ?>
