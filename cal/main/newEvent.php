<?php ?>

<!-- HERE NEW ENTRY -->
    <div class="form-popup" id="myForm">
      <form action="admin/new_entry.php" class="form-container" method="POST" enctype="multipart/form-data">
        <center><h2> New Appointment </h2>
        <label for="subject"><b>Subject</b></label>
        <input type="text" placeholder="Subject" name="subject" required>

        <label for="details"><b>Details</b></label>
        <textarea name="details" rows="5" cols="92" maxlength="20000" required></textarea><br>
        <br>
        <label for="category"><b>Start Date</b></label><br>
        Day <select autocomplete="off" name='start_day' required>
          <?php
          //DAY
            if (isset($_GET['events'])){
              $day = substr($_GET['events'], 0, 2);
              echo "<option selected value=".$day.">".$day."</option>";
            } else {
              echo "<option selected value=".date(d).">".date(d)."</option>";
            }
            for ($x = 1; $x <= 31; $x++) {
              if ($x >= 1 && $x < 10){
                $x = "0".$x;
              }
              echo "<option value=".$x.">".$x."</option>";
            }
          ?>
        </select>
        Month <select autocomplete="off" name='start_month' required>
          <?php
          //MONTH
          if (isset($_GET['month'])){
            $month = substr($_GET['month'], 0, 2);
            echo "<option selected value=".$month.">".date('F', mktime(0, 0, 0, $month, 10))."</option>";
          } else {
            echo "<option selected value=".date(m).">".date(M)."</option>";
          }
            for ($x = 1; $x <= 12; $x++) {
              if ($x >= 1 && $x < 10){
                $x = "0".$x;
              }
              echo "<option value=".$x.">".date('F', mktime(0, 0, 0, $x, 10))."</option>";
            }
          ?>
        </select>
        Year <select autocomplete="off" name='start_year' required>
        <?php
        if (isset($_GET['year'])){
          $year = $_GET['year'];
          echo "<option selected value=".$year.">".$year."</option>";
        } else {
          echo "<option selected value=".date(Y).">".date(Y)."</option>";
        }

          for ($x = 2020; $x <= 2100; $x++) {
            echo "<option value=".$x.">".$x."</option>";
          }
        ?>
        </select>
        Hour <select autocomplete="off" name='start_hour' required>
        <?php
          for ($x = 0; $x <= 23; $x++) {
            if ($x >= 0 && $x < 10){
              $x = "0".$x;
            }
            echo "<option value=".$x.">".$x."</option>";
          }
        ?>
        </select>
        Min <select autocomplete="off" name='start_min' required>
        <?php
          for ($x = 0; $x <= 59; $x++) {
            if ($x >= 0 && $x < 10){
              $x = "0".$x;
            }
            echo "<option value=".$x.">".$x."</option>";
          }
        ?>
        </select>
        <br>
        <label for="category"><b>End Date</b></label><br>
        Day <select autocomplete="off" name='end_day' required>
          <?php
          if (isset($_GET['events'])){
            $day = substr($_GET['events'], 0, 2);
            echo "<option selected value=".$day.">".$day."</option>";
          } else {
            echo "<option selected value=".date(d).">".date(d)."</option>";
          }
            for ($x = 1; $x <= 31; $x++) {
              if ($x >= 1 && $x < 10){
                $x = "0".$x;
              }
              echo "<option value=".$x.">".$x."</option>";
            }
          ?>
        </select>
        Month <select autocomplete="off" name='end_month' required>
          <?php
          if (isset($_GET['month'])){
            $month = substr($_GET['month'], 0, 2);
            echo "<option selected value=".$month.">".date('F', mktime(0, 0, 0, $month, 10))."</option>";
          } else {
            echo "<option selected value=".date(m).">".date(M)."</option>";
          }
            for ($x = 1; $x <= 12; $x++) {
              if ($x >= 1 && $x < 10){
                $x = "0".$x;
              }
              echo "<option value=".$x.">".date('F', mktime(0, 0, 0, $x, 10))."</option>";
            }
          ?>
        </select>
        Year <select autocomplete="off" name='end_year' required>
        <?php
        if (isset($_GET['year'])){
          $year = $_GET['year'];
          echo "<option selected value=".$year.">".$year."</option>";
        } else {
          echo "<option selected value=".date(Y).">".date(Y)."</option>";
        }
          for ($x = 2020; $x <= 2100; $x++) {
            echo "<option value=".$x.">".$x."</option>";
          }
        ?>
        </select>
        Hour <select autocomplete="off" name='end_hour' required>
        <?php
          for ($x = 0; $x <= 23; $x++) {
            if ($x >= 0 && $x < 10){
              $x = "0".$x;
            }
            echo "<option value=".$x.">".$x."</option>";
          }
        ?>
        </select>
        Min <select autocomplete="off" name='end_min' required>
        <?php
          for ($x = 0; $x <= 59; $x++) {
            if ($x >= 0 && $x < 10){
              $x = "0".$x;
            }
            echo "<option value=".$x.">".$x."</option>";
          }
        ?>
        </select>
        <br><br>
        <label for="category"><b>Category</b></label>
        <select autocomplete="off" name="category" id="category">
            <option value="general">General</option>
            <option value="anniverssary">Anniverssary</option>
            <option value="birthday">Birthday</option>
            <option value="cancel">Cancellation</option>
            <option value="insurance">Insurance</option>
            <option value="renewal">Renewall</option>
            <option value="school">School</option>
            <option value="service">Service</option>
            <option value="wedding">Wedding</option>
        </select>
        <br><br>

        <label for="repeat"><b>Repeat</b></label>
        <select autocomplete="off" name="repeat">
          <option value="0">None</option>
          <option value="1">Every Day</option>
          <option value="2">Every Week</option>
          <option value="3">Every 2 Weeks</option>
          <option value="4">Every Month</option>
          <option value="5">Every 2 Month</option>
          <option value="6">Every 3 Months</option>
          <option value="7">Every Year</option>
        </select>
        <label for="category"><b>Until</b></label>
        <select autocomplete="off" name='until_day' required>
          <?php
          echo "<option selected value=".date(d).">".date(d)."</option>";
            for ($x = 1; $x <= 31; $x++) {
              if ($x >= 1 && $x < 10){
                $x = "0".$x;
              }
              echo "<option value=".$x.">".$x."</option>";
            }
          ?>
        </select>
        <select autocomplete="off" name='until_month' required>
          <?php
          echo "<option selected value=".date(m).">".date(M)."</option>";
            for ($x = 1; $x <= 12; $x++) {
              if ($x >= 1 && $x < 10){
                $x = "0".$x;
              }
              echo "<option value=".$x.">".date('F', mktime(0, 0, 0, $x, 10))."</option>";
            }
          ?>
        </select>
        <select autocomplete="off" name='until_year' required>
        <?php
        echo "<option selected value=".date(Y).">".date(Y)."</option>";
          for ($x = 2020; $x <= 2100; $x++) {
            echo "<option value=".$x.">".$x."</option>";
          }
        ?>
        </select>
        <br><br>

        <label for="file"><B>Attach files or photos</B></label>
        <input type="file" id="file" name="file[]" multiple>

        <br><br>
        <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
        <button type="submit" class="btn">Submit</button>
      <br><br><br>
      </form>
    </div>

<!-- END NEW ENTRY -->


<?php ?>
