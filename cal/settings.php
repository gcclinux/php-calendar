<?php
session_start();

if (! isset($_SESSION['valid'])){
  header("Location: ./index.php");
}

$config = include(__DIR__ . '/conf/config.php');

$updateMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["dbtype"])) {
    $updateMessage = "Database type is required";
  } elseif (($_POST["dbtype"]) == "mysql") {
    $config[dbtype] = "mysql";
    file_put_contents('conf/config.php', "<?php\nreturn " . var_export($config, true) . "\n?>");
    $updateMessage = "Configuration Successfully Updated";
  } elseif (($_POST["dbtype"]) == "pgsql") {
    $config[dbtype] = "pgsql";
    file_put_contents('conf/config.php', "<?php\nreturn " . var_export($config, true) . "\n?>");
    $updateMessage = "Configuration Successfully Updated";
  }
  if (empty($_POST["dbhost"])) {
    $updateMessage = "Database hostname required";
  } else {
    $config[dbhost] = $_POST["dbhost"];
    file_put_contents('conf/config.php', "<?php\nreturn " . var_export($config, true) . "\n?>");
    $updateMessage = "Configuration Successfully Updated";
  }
  if (empty($_POST["dbport"])) {
    $updateMessage = "Database Port required";
  } else {
    $config[dbport] = $_POST["dbport"];
    file_put_contents('conf/config.php', "<?php\nreturn " . var_export($config, true) . "\n?>");
    $updateMessage = "Configuration Successfully Updated";
  }
  if (empty($_POST["dbname"])) {
    $updateMessage = "Database Name required";
  } else {
    $config[dbname] = $_POST["dbname"];
    file_put_contents('conf/config.php', "<?php\nreturn " . var_export($config, true) . "\n?>");
    $updateMessage = "Configuration Successfully Updated";
  }
  if (empty($_POST["dbuser"])) {
    $updateMessage = "Database User required";
  } else {
    $config[dbuser] = $_POST["dbuser"];
    file_put_contents('conf/config.php', "<?php\nreturn " . var_export($config, true) . "\n?>");
    $updateMessage = "Configuration Successfully Updated";
  }
  if (empty($_POST["dbpass"])) {
    $updateMessage = "Database Password required";
  } else {
    $config[dbpass] = $_POST["dbpass"];
    file_put_contents('conf/config.php', "<?php\nreturn " . var_export($config, true) . "\n?>");
    $updateMessage = "Configuration Successfully Updated";
  }
  if (empty($_POST["tcalendar"])) {
    $updateMessage = "Database table for calendar is required";
  } else {
    $config[tcalendar] = $_POST["tcalendar"];
    file_put_contents('conf/config.php', "<?php\nreturn " . var_export($config, true) . "\n?>");
    $updateMessage = "Configuration Successfully Updated";
  }
  if (empty($_POST["tattach"])) {
    $updateMessage = "Database table for attachments is required";
  } else {
    $config[tattach] = $_POST["tattach"];
    file_put_contents('conf/config.php', "<?php\nreturn " . var_export($config, true) . "\n?>");
    $updateMessage = "Configuration Successfully Updated";
  }
  if (empty($_POST["ucalendar"])) {
    $updateMessage = "Calendar URL required";
  } else {
    $config[ucalendar] = $_POST["ucalendar"];
    file_put_contents('conf/config.php', "<?php\nreturn " . var_export($config, true) . "\n?>");
    $updateMessage = "Configuration Successfully Updated";
  }
  if (empty($_POST["login"])) {
    $updateMessage = "Site access login is required";
  } else {
    $config[login] = $_POST["login"];
    file_put_contents('conf/config.php', "<?php\nreturn " . var_export($config, true) . "\n?>");
    $updateMessage = "Configuration Successfully Updated";
  }
  if (empty($_POST["mailu"])) {
    $updateMessage = "Email User required to send notification";
  } else {
    $config[mailu] = $_POST["mailu"];
    file_put_contents('conf/config.php', "<?php\nreturn " . var_export($config, true) . "\n?>");
    $updateMessage = "Configuration Successfully Updated";
  }
  if (empty($_POST["mailp"])) {
    $updateMessage = "Password for email User required to send notification";
  } else {
    $config[mailp] = $_POST["mailp"];
    file_put_contents('conf/config.php', "<?php\nreturn " . var_export($config, true) . "\n?>");
    $updateMessage = "Configuration Successfully Updated";
  }
  if (empty($_POST["smtp"])) {
    $updateMessage = "SMTP address required to send notification";
  } else {
    $config[smtp] = $_POST["smtp"];
    file_put_contents('conf/config.php', "<?php\nreturn " . var_export($config, true) . "\n?>");
    $updateMessage = "Configuration Successfully Updated";
  }
  if (empty($_POST["mport"])) {
    $updateMessage = "TLS/SSL port required to send notification";
  } else {
    $config[mport] = $_POST["mport"];
    file_put_contents('conf/config.php', "<?php\nreturn " . var_export($config, true) . "\n?>");
    $updateMessage = "Configuration Successfully Updated";
  }
  if (empty($_POST["mails"])) {
    $updateMessage = "Mail Sender Displayed name required to send notification";
  } else {
    $config[mails] = $_POST["mails"];
    file_put_contents('conf/config.php', "<?php\nreturn " . var_export($config, true) . "\n?>");
    $updateMessage = "Configuration Successfully Updated";
  }
  if (empty($_POST["mailr"])) {
    $updateMessage = "At least one recipient required to receive notification";
  } else {
    $config[mailr] = $_POST["mailr"];
    file_put_contents('conf/config.php', "<?php\nreturn " . var_export($config, true) . "\n?>");
    $updateMessage = "Configuration Successfully Updated";
  }
  if (empty($_POST["location"])) {
    $updateMessage = "Location for Weather";
  } else {
    $config[location] = $_POST["location"];
    file_put_contents('conf/config.php', "<?php\nreturn " . var_export($config, true) . "\n?>");
    $updateMessage = "Configuration Successfully Updated";
  }
  if (empty($_POST["openweather"])) {
    $updateMessage = "OpenWeather API required for Weather";
  } else {
    $config[openweather] = $_POST["openweather"];
    file_put_contents('conf/config.php', "<?php\nreturn " . var_export($config, true) . "\n?>");
    $updateMessage = "Configuration Successfully Updated";
  }
  if (empty($_POST["geolocation"])) {
    $updateMessage = "geoLocation API required for Weather if no location configured";
  } else {
    $config[geolocation] = $_POST["geolocation"];
    file_put_contents('conf/config.php', "<?php\nreturn " . var_export($config, true) . "\n?>");
    $updateMessage = "Configuration Successfully Updated";
  }

  echo "<center><span class=\"error\" style=\"text-align: center;\">$updateMessage</span>";
}

?>
<html>
<head>
      <title>Personal Calendar</title>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
      <link href="style/calendar.css" type="text/css" rel="stylesheet" />
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <script src="js/time.js"></script>
      <style>
        .error {color: #FF0000;}
      </style>
</head>
<body onload="startTime()">
      <a href="main.php"><img src="img/header.jpg" alt="Header" style="width:100%;height:100px;"></a>
      <h1 style="text-align: center;">Calendar Settings</h1>
      <center>
      <a href="main.php"><img src="img/cal-black.png" alt="Calendar" style="text-align:center;width:40px;height:40px;"></a>
      <BR><BR>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <B>Database Type</B><BR>
            <select style="width: 30%" autocomplete="off" name="dbtype" id="dbtype">
                <option value="<?php echo $config['dbtype'];?>">Configured: <?php echo strtoupper($config['dbtype']);?></option>
                <option value="none">--------</option>
                <option value="mysql">MySQL</option>
                <option value="pgsql">Postgres</option>
            </select>
            <BR>
              <a><B>Database Host</B></a><BR>
                <input style="width: 30%" type="text" name="dbhost" value="<?php echo $config['dbhost'];?>">
            <BR>
              <B>Database Port</B><BR>
                <input style="width: 30%" type="text" name="dbport" value="<?php echo $config['dbport'];?>">
            <BR>
              <B>Database Name</B><BR>
                <input style="width: 30%" type="text" name="dbname" value="<?php echo $config['dbname'];?>">
            <BR>
              <B>Database User</B><BR>
                <input style="width: 30%" type="text" name="dbuser" value="<?php echo $config['dbuser'];?>">
            <BR>
              <B>Database Password</B><BR>
                <input style="width: 30%" type="text" name="dbpass" value="<?php echo $config['dbpass'];?>">
            <BR>
              <B>Calendar Table (readonly)</B><BR>
                <input style="width: 30%" type="text" name="tcalendar" value="<?php echo $config['tcalendar'];?>" readonly>
            <BR>
              <B>Attachment Table (readonly)</B><BR>
                <input style="width: 30%" type="text" name="tattach" value="<?php echo $config['tattach'];?>" readonly>
            <BR>
              <B>Calendar URL</B><BR>
                <input style="width: 30%" type="text" name="ucalendar" value="<?php echo $config['ucalendar'];?>">
            <BR>
              <B>Calendar Password</B><BR>
                <input style="width: 30%" type="text" name="login" value="<?php echo $config['login'];?>">
            <BR>
              <B>Email User</B><BR>
                <input style="width: 30%" type="text" name="mailu" value="<?php echo $config['mailu'];?>">
            <BR>
              <B>Email User Password</B><BR>
                <input style="width: 30%" type="text" name="mailp" value="<?php echo $config['mailp'];?>">
            <BR>
              <B>SMTP Address</B> <I>Example: ssl://smtp.gmail.com</I><BR>
                <input style="width: 30%" type="text" name="smtp" value="<?php echo $config['smtp'];?>">
            <BR>
              <B>SSL MAIL PORT: </B><I>Example: 465</I><BR>
                <input style="width: 30%" type="text" name="mport" value="<?php echo $config['mport'];?>">
            <BR>
              <B>Mail Sender Name</B><BR>
                <input style="width: 30%" type="text" name="mails" value="<?php echo $config['mails'];?>">
            <BR>
              <B>Recipient</B> <I>(multi separated by comman ',')</I><BR>
                <input style="width: 30%" type="text" name="mailr" value="<?php echo $config['mailr'];?>">
            <BR>
              <B>Location for Weather</B><BR>
                <input style="width: 30%" type="text" name="location" value="<?php echo $config['location'];?>">
            <BR>
              <B>OpenWeather API</B><BR>
                <input style="width: 30%" type="text" name="openweather" value="<?php echo $config['openweather'];?>">
            <BR>
              <B>geoLocation API</B><BR>
                <input style="width: 30%" type="text" name="geolocation" value="<?php echo $config['geolocation'];?>">
              <br><br>
              <input type="submit" name="submit" value="Submit">
            </form>
</body>
</html>
