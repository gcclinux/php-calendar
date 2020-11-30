<?php ?>
<!-- START UPDATE / DELETE ENTRY -->
<div class="form-popup" id="myEvent">
  <form action="admin/edit_entry.php" class="form-container" method="POST" enctype="multipart/form-data">
  <center><h2> Calendar Event </h2></center>
  <?php
  if (isset($_POST['id'])){
    $data = $RUN->getUniqueEvent($_POST['id']);
    foreach($data	as $key) {
      $entry = str_getcsv($key);
      $startD = substr("$entry[1]", 0, 2);
      $startMon = substr("$entry[1]", 3, -5);
      $startY = substr("$entry[1]", 6, 4);
      $startH = substr("$entry[2]", 0, 2);
      $startMin = substr("$entry[2]", 3, 2);

      $endD = substr("$entry[3]", 0, 2);
      $endMon = substr("$entry[3]", 3, -5);
      $endY = substr("$entry[3]", 6, 4);
      $endH = substr("$entry[4]", 0, 2);
      $endMin = substr("$entry[4]", 3, 2);

      $END = $RUN->getRepeatPeriod($entry[9]);
      foreach($END as $key) {
        $getDate = str_getcsv($key);

        //$initial_day = substr("$getDate[0]", 0, 2);
        $initial_month = substr("$getDate[0]", 3, -5);
        $initial_year = substr("$getDate[0]", 6, 4);

        $until_day = substr("$getDate[1]", 0, 2);
        $until_month = substr("$getDate[1]", 3, -5);
        $until_year = substr("$getDate[1]", 6, 4);
      }

      //$until_date = $until_day."-".$until_month."-".$until_year;

      echo "<input type=\"hidden\" name=\"id\" value=\"$entry[0]\">";
      echo "<input type=\"hidden\" name=\"uniqueid\" value=\"$entry[9]\">";
      echo "<input type=\"hidden\" name=\"initial_day\" value=\"$until_day\">";
      echo "<input type=\"hidden\" name=\"initial_month\" value=\"$initial_month\">";
      echo "<input type=\"hidden\" name=\"initial_year\" value=\"$initial_year\">";


      echo "<label for=\"subject\"><b>Subject</b></label>";
      echo "<input name=\"subject\" value=\"$entry[5]\" type=\"text\" />";

      echo "<label for=\"details\"><b>Details</b></label>";
      echo "<textarea name=\"details\" rows=\"5\" cols=\"92\" maxlength=\"20000\" required> $entry[6]</textarea><br>";
      echo "<br>";

      echo "<label for=\"category\"><b>Start Date</b></label><br>";

      echo "Day <select autocomplete=\"off\" name='start_day' required>";
      echo "<option selected value=\"$startD\">$startD</option>";
          for ($x = 1; $x <= 31; $x++) {
            if ($x >= 1 && $x < 10){
              $x = "0".$x;
            }
            echo "<option value=\"$x\">$x</option>";
          }
      echo "</select>";
      echo " Month <select autocomplete=\"off\" name='start_month' required>";
      echo "<option selected value=\"$startMon\">".date('F', mktime(0, 0, 0, $startMon, 10))."</option>";
          for ($x = 1; $x <= 12; $x++) {
            if ($x >= 1 && $x < 10){
              $x = "0".$x;
            }
            echo "<option value=".$x.">".date('F', mktime(0, 0, 0, $x, 10))."</option>";
          }
      echo "</select>";
      echo " Year <select autocomplete=\"off\" name='start_year' required>";
      echo "<option selected value=\"$startY\">$startY</option>";
      for ($x = 2020; $x <= 2100; $x++) {
        echo "<option value=".$x.">".$x."</option>";
      }
      echo "</select>";

      echo " Hour <select autocomplete=\"off\" name='start_hour' required>";
      echo "<option selected value=\"$startH\">$startH</option>";
      for ($x = 0; $x <= 23; $x++) {
        if ($x >= 0 && $x < 10){
          $x = "0".$x;
        }
        echo "<option value=".$x.">".$x."</option>";
      }
      echo "</select>";

      echo " Min <select autocomplete=\"off\" name='start_min' required>";
      echo "<option selected value=\"$startMin\">$startMin</option>";
      for ($x = 0; $x <= 59; $x++) {
        if ($x >= 0 && $x < 10){
          $x = "0".$x;
        }
        echo "<option value=".$x.">".$x."</option>";
      }
      echo "</select><br>";

      echo "<label for=\"category\"><b>End Date</b></label><br>";

      echo "Day <select autocomplete=\"off\" name='end_day' required>";
      echo "<option selected value=\"$endD\">$endD</option>";
          for ($x = 1; $x <= 31; $x++) {
            if ($x >= 1 && $x < 10){
              $x = "0".$x;
            }
            echo "<option value=\"$x\">$x</option>";
          }
      echo "</select>";
      echo " Month <select autocomplete=\"off\" name='end_month' required>";
      echo "<option selected value=\"$endMon\">".date('F', mktime(0, 0, 0, $endMon, 10))."</option>";
          for ($x = 1; $x <= 12; $x++) {
            if ($x >= 1 && $x < 10){
              $x = "0".$x;
            }
            echo "<option value=".$x.">".date('F', mktime(0, 0, 0, $x, 10))."</option>";
          }
      echo "</select>";
      echo " Year <select autocomplete=\"off\" name='end_year' required>";
      echo "<option selected value=\"$endY\">$endY</option>";
      for ($x = 2020; $x <= 2100; $x++) {
        echo "<option value=".$x.">".$x."</option>";
      }
      echo "</select>";

      echo " Hour <select autocomplete=\"off\" name='end_hour' required>";
      echo "<option selected value=\"$endH\">$endH</option>";
      for ($x = 0; $x <= 23; $x++) {
        if ($x >= 0 && $x < 10){
          $x = "0".$x;
        }
        echo "<option value=".$x.">".$x."</option>";
      }
      echo "</select>";

      echo " Min <select autocomplete=\"off\" name='end_min' required>";
      echo "<option selected value=\"$endMin\">$endMin</option>";
      for ($x = 0; $x <= 59; $x++) {
        if ($x >= 0 && $x < 10){
          $x = "0".$x;
        }
        echo "<option value=".$x.">".$x."</option>";
      }
      echo "</select>";

      echo "
      <br><br>
      <label for=\"category\"><b>Category</b></label>
      <select autocomplete=\"off\" name=\"category\" id=\"category\">
          <option value=\"general\">General</option>
          <option value=\"anniverssary\">Anniverssary</option>
          <option value=\"appointment\">Appointment</option>
          <option value=\"birthday\">Birthday</option>
          <option value=\"cancel\">Cancellation</option>
          <option value=\"insurance\">Insurance</option>
          <option value=\"renewal\">Renewall</option>
          <option value=\"school\">School</option>
          <option value=\"service\">Service</option>
          <option value=\"meeting\">Meetings</option>
          <option value=\"wedding\">Wedding</option>
          <option value=\"work\">Work</option>
          <option value=\"zoom\">Zoom</option>
      </select>
      <br><br>
      <label for=\"repeat\"><b>Repeat</b></label>
      <select autocomplete=\"off\" name=\"repeat\">";

      $multi = $entry[8];
      if ($multi == "0"){
        echo "<option value=\"0\">None</option>";
      } elseif ($multi == "1"){
        echo "<option value=\"1\">Every Day</option>";
      } elseif ($multi == "2"){
        echo "<option value=\"2\">Every Week</option>";
      } elseif ($multi == "3"){
        echo "<option value=\"3\">Every 2 Weeks</option>";
      } elseif ($multi == "4"){
        echo "<option value=\"4\">Every Month</option>";
      } elseif ($multi == "5"){
        echo "<option value=\"5\">Every 2 Month</option>";
      } elseif ($multi == "6"){
        echo "<option value=\"6\">Every 3 Months</option>";
      } elseif ($multi == "7"){
        echo "<option value=\"7\">Every Year</option>";
      }

      echo "
        <option value=\"0\">None</option>
        <option value=\"1\">Every Day</option>
        <option value=\"2\">Every Week</option>
        <option value=\"3\">Every 2 Weeks</option>
        <option value=\"4\">Every Month</option>
        <option value=\"5\">Every 2 Month</option>
        <option value=\"6\">Every 3 Months</option>
        <option value=\"7\">Every Year</option>
      </select>
      <label for=\"category\"><b>Until</b></label>
      <select autocomplete=\"off\" name='until_day' required>";

      echo "<option selected value=\"$until_day\">$until_day</option>";
        for ($x = 1; $x <= 31; $x++) {
          if ($x >= 1 && $x < 10){
            $x = "0".$x;
          }
          echo "<option value=".$x.">".$x."</option>";
        }
        echo "</select>";

        echo "<select autocomplete=\"off\" name='until_month' required>";
        echo "<option selected value=\"$until_month\">".date('F', mktime(0, 0, 0, $until_month, 10))."</option>";
          for ($x = 1; $x <= 12; $x++) {
            if ($x >= 1 && $x < 10){
              $x = "0".$x;
            }
            echo "<option value=".$x.">".date('F', mktime(0, 0, 0, $x, 10))."</option>";
          }
        echo "</select>";

        echo "<select autocomplete=\"off\" name='until_year' required>";
        echo "<option selected value=\"$until_year\">$until_year</option>";
          for ($x = 2020; $x <= 2100; $x++) {
            echo "<option value=".$x.">".$x."</option>";
          }
        echo "
              </select><br><br>
              <label for=\"file\"><B>Attach files or photos</B></label>
              <input type=\"file\" id=\"file\" name=\"file[]\" multiple>
              <br><br>";


      echo "<hr>";
      echo "<input type=\"checkbox\" id=\"multipletrue\" name=\"multipletrue\" value=\"yes\">";
      echo "<label for=\"multipletrue\"> Delete All Events</label>";
      echo "&emsp;&emsp;";
      echo "<input type=\"checkbox\" id=\"attachmentstrue\" name=\"attachmentstrue\" value=\"yes\">";
      echo "<label for=\"attachmentstrue\"> Delete Attachements</label>";
      echo "&emsp;&emsp;";
      echo "<input type=\"checkbox\" id=\"updatealltrue\" name=\"updatealltrue\" value=\"yes\">";
      echo "<label for=\"updatealltrue\"> Update All Events</label>";
      echo "<input type=\"hidden\" id=\"returndate\" name=\"returndate\" value=\"$entry[3]\">";
      echo "<hr>";
      }
      echo '<script type="text/javascript"> openEvent(); </script>';
    }
    ?>
    <button type="button" onclick="closeEvent()">Close</button>
    <input name="deletedata" value="Delete Entry" type="submit" />
    <input name="savedata" value="Update Entry" type="submit" />
    </form>
  </div>

  <!-- END UPDATE / DELETE ENTRY -->
<?php ?>
