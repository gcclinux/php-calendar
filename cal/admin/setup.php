<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$config = include(__DIR__ . '/../conf/config.php');

$updateMessage = "";
$status = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["dbtype"])) {
     $updateMessage = "Database type is required";
     $status = "false";
  } elseif (($_POST["dbtype"]) == "mysql") {
          if (! extension_loaded('pdo_mysql')) {
                  $_POST["module"] = "mysql";
          }
    $config["dbtype"] = "mysql";
    file_put_contents('../conf/config.php', "<?php\nreturn " . var_export($config, true) . "\n?>");
    $updateMessage = "Attempting to create database!";
    $status = "true";
  } elseif (($_POST["dbtype"]) == "pgsql") {
          if (! extension_loaded('pgsql')) {
                  $_POST["module"] = "pgsql";
          }
    $config["dbtype"] = "pgsql";
    file_put_contents('../conf/config.php', "<?php\nreturn " . var_export($config, true) . "\n?>");
    $updateMessage = "Attempting to create database!";
    $status = "true";
  }
  if (empty($_POST["dbhost"])) {
    $updateMessage = "Database hostname required";
    $status = "false";
  } else {
    $config["dbhost"] = $_POST["dbhost"];
    file_put_contents('../conf/config.php', "<?php\nreturn " . var_export($config, true) . "\n?>");
    $updateMessage = "Attempting to create database!";
    $status = "true";
  }
  if (empty($_POST["dbport"])) {
    $updateMessage = "Database Port required";
    $status = "false";
  } else {
    $config["dbport"] = $_POST["dbport"];
    file_put_contents('../conf/config.php', "<?php\nreturn " . var_export($config, true) . "\n?>");
    $updateMessage = "Attempting to create database!";
    $status = "true";
  }
  if (empty($_POST["dbname"])) {
    $updateMessage = "Database Name required";
    $status = "false";
  } else {
    $config["dbname"] = strtolower($_POST["dbname"]);
    file_put_contents('../conf/config.php', "<?php\nreturn " . var_export($config, true) . "\n?>");
    $updateMessage = "Attempting to create database!";
    $status = "true";
  }
  if (empty($_POST["dbuser"])) {
    $updateMessage = "Database User required";
    $status = "false";
  } else {
    $config["dbuser"] = $_POST["dbuser"];
    file_put_contents('../conf/config.php', "<?php\nreturn " . var_export($config, true) . "\n?>");
    $updateMessage = "Attempting to create database!";
    $status = "true";
  }
  if (empty($_POST["dbpass"])) {
    $updateMessage = "Database Password required";
    $status = "false";
  } else {
    $config["dbpass"] = $_POST["dbpass"];
    file_put_contents('../conf/config.php', "<?php\nreturn " . var_export($config, true) . "\n?>");
    $updateMessage = "Attempting to create database!";
    $status = "true";
  }
  if (empty($_POST["tcalendar"])) {
    $updateMessage = "Database table for calendar is required";
    $status = "false";
  } else {
    $config["tcalendar"] = $_POST["tcalendar"];
    file_put_contents('../conf/config.php', "<?php\nreturn " . var_export($config, true) . "\n?>");
    $updateMessage = "Attempting to create database!";
    $status = "true";
  }
  if (empty($_POST["tattach"])) {
    $updateMessage = "Database table for attachments is required";
    $status = "false";
  } else {
    $config["tattach"] = $_POST["tattach"];
    file_put_contents('../conf/config.php', "<?php\nreturn " . var_export($config, true) . "\n?>");
    $updateMessage = "Attempting to create database!";
    $status = "true";
  }
  if (empty($_POST["ucalendar"])) {
    $updateMessage = "Calendar URL required";
    $status = "false";
  } else {
    $config["ucalendar"] = $_POST["ucalendar"];
    file_put_contents('../conf/config.php', "<?php\nreturn " . var_export($config, true) . "\n?>");
    $updateMessage = "Attempting to create database!";
    $status = "true";
  }
  if (empty($_POST["login"])) {
          $updateMessage = "Site access login is required";
          $status = "false";
  } elseif(strlen($_POST["login"]) != 8){
          $updateMessage = "PIN needs to be \"8\" digits";
          $status = "false";
  } else {
          $config["login"] = $_POST["login"];
          file_put_contents('../conf/config.php', "<?php\nreturn " . var_export($config, true) . "\n?>");
          $updateMessage = "Attempting to create database!";
          $status = "true";
  }

  if ($_POST["dbtype"] == "mysql" && ($_POST["module"] == "mysql" || $_POST["module"] == "curl" || $status != "true")) {
          if ($status != "true"){
                  echo "<center><span class=\"error\" style=\"text-align: center;\">&#9940; $updateMessage Modules</span>";
          } else {
                  echo "<center><span class=\"error\" style=\"text-align: center;\">&#9940; Missing PHP '".$_POST["module"]."' Modules</span>";
          }
          $config["setup"] = "failed";
          file_put_contents('../conf/config.php', "<?php\nreturn " . var_export($config, true) . "\n?>");
  } elseif ($_POST["dbtype"] == "pgsql" && ($_POST["module"] == "pgsql" || $_POST["module"] == "curl" || $status != "true")) {
          if ($status != "true"){
                  echo "<center><span class=\"error\" style=\"text-align: center;\">&#9940; $updateMessage Modules</span>";
          } else {
                  echo "<center><span class=\"error\" style=\"text-align: center;\">&#9940; Missing PHP '".$_POST["module"]."' Modules</span>";
          }
          $config["setup"] = "failed";
          file_put_contents('../conf/config.php', "<?php\nreturn " . var_export($config, true) . "\n?>");
  } else {
          // START to CREATE DB
          echo "<BR><center><span class=\"error\" style=\"text-align: center;\">&#9889; $updateMessage</span>";
          if ($config["dbtype"] == "pgsql") {
                  $conn = null;
                  $dsn = $config["dbtype"].':host='.$config["dbhost"].';port='.$config["dbport"].';user='.$config["dbuser"].';password='.$config["dbpass"];
                  try{
                          $conn = new PDO($dsn);
                          if($conn){
                                  $status = "true";
                                  echo '<br>&#128077; Connection to '.strtoupper($config["dbtype"]).' successfully!';
                                  $conn->exec("CREATE DATABASE ".$config["dbname"]);
                                  $SQL = $conn->query("SELECT datname from pg_database WHERE datname = '".$config["dbname"]."'");
                                  $RESULT = $SQL->fetchAll(PDO::FETCH_ASSOC);
                                  if (sizeof($RESULT) != 0){
                                        $status = "true";
                                        echo "<BR> &#128077; Database <B>".$config["dbname"]."</B> created successfully!";
                                        $conn = null;
                                        $ndsn = $config["dbtype"].':host='.$config["dbhost"].';port='.$config["dbport"].';dbname='.$config["dbname"].';user='.$config["dbuser"].';password='.$config["dbpass"];
                                        $conn = new PDO($ndsn);
                                        $conn->exec("CREATE TABLE IF NOT EXISTS ".$config["tcalendar"]." (cid serial PRIMARY KEY, modified timestamp(0) default current_timestamp, start_date varchar(10) NOT NULL, end_date varchar(10) NOT NULL, start_time varchar(8) NOT NULL, end_time varchar(8) NOT NULL, subject varchar(72) NOT NULL, details text, category varchar(24), multi smallint, uid bigint NOT NULL)");
                                        $T1SQL = $conn->query("SELECT tablename FROM pg_catalog.pg_tables WHERE tablename = '".$config["tcalendar"]."'");
                                        $T1OUT = $T1SQL->fetchAll(PDO::FETCH_ASSOC);
                                        if (sizeof($T1OUT) != 0){
                                                $status = "true";
                                                echo "<BR> &#128077; Table <B>".$config["tcalendar"]."</B> created successfully!";
                                                $conn->exec("CREATE TABLE IF NOT EXISTS ".$config["tattach"]." (aid serial PRIMARY KEY, modified timestamp(0) default current_timestamp, uid bigint NOT NULL, attachment varchar(1024) NOT NULL)");
                                                $T2SQL = $conn->query("SELECT tablename FROM pg_catalog.pg_tables WHERE tablename = '".$config["tattach"]."'");
                                                $T2OUT = $T2SQL->fetchAll(PDO::FETCH_ASSOC);
                                                if (sizeof($T2OUT) != 0){
                                                        $status = "true";
                                                        echo "<BR> &#128077; Table <B>".$config["tattach"]."</B> created successfully!<BR>";
                                                        echo "<BR>&#128077; SETUP STATUS SUCCESSFUL<BR>";
                                                        $config["setup"] = "complete";
                                                        file_put_contents('../conf/config.php', "<?php\nreturn " . var_export($config, true) . "\n?>");
                                                        echo "<p><a href=\"../\">Finish</a></p>";
                                                } else {
                                                        $status = "false";
                                                        echo "<BR> &#9940; Failed to create <B>".$config["tattach"]. "</B>table";
                                                        echo "&#128077; SETUP STATUS FAILED";
                                                        $config["setup"] = "failed";
                                                        file_put_contents('../conf/config.php', "<?php\nreturn " . var_export($config, true) . "\n?>");
                                                }
                                        } else {
                                                $status = "false";
                                                echo "<BR> &#9940; Failed to create <B>".$config["tcalendar"]. "</B>table";
                                                echo "&#128077; SETUP STATUS FAILED";
                                                $config["setup"] = "failed";
                                                file_put_contents('../conf/config.php', "<?php\nreturn " . var_export($config, true) . "\n?>");
                                        }
                                  } else {
                                          $status = "false";
                                          echo "<BR> &#9940; Failed to create <B>".$config["tattach"]. "</B>database";
                                          echo "&#128077; SETUP STATUS FAILED";
                                          $config["setup"] = "failed";
                                          file_put_contents('../conf/config.php', "<?php\nreturn " . var_export($config, true) . "\n?>");
                                  }
                          } else {
                                  $status = "false";
                                  echo '&#9940; Failed to connect to '.$config["dbtype"].'<BR>';
                                  echo "&#128077; SETUP STATUS FAILED";
                                  $config["setup"] = "failed";
                                  file_put_contents('../conf/config.php', "<?php\nreturn " . var_export($config, true) . "\n?>");
                          }
                  } catch (PDOException $e){
                        echo $e->getMessage();
                }
        } elseif ($config["dbtype"] == "mysql") {
                $conn = null;
                $dsn = $config["dbtype"].':host='.$config["dbhost"].';port='.$config["dbport"].';user='.$config["dbuser"].';password='.$config["dbpass"];
                try {
                        $conn = new PDO($dsn);
                        if($conn){
                                $status = "true";
                                echo '<br>&#128077; Connection to '.strtoupper($config["dbtype"]).' successfully!';
                                $conn->exec("CREATE DATABASE ".$config["dbname"]);
                                $SQL = $conn->query("SHOW DATABASES LIKE '".$config["dbname"]."'");
                                $RESULT = $SQL->fetchAll(PDO::FETCH_ASSOC);
                                if (sizeof($RESULT) != 0){
                                        $status = "true";
                                        echo "<BR> &#128077; Database <B>".$config["dbname"]."</B> created successfully!";
                                        $conn = null;
                                        $ndsn = $config["dbtype"].':host='.$config["dbhost"].';port='.$config["dbport"].';dbname='.$config["dbname"].';user='.$config["dbuser"].';password='.$config["dbpass"];
                                        $conn = new PDO($ndsn);
                                        $conn->exec("CREATE TABLE IF NOT EXISTS ".$config["tcalendar"]." (cid int(11) NOT NULL AUTO_INCREMENT, modified TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE current_timestamp(), start_date VARCHAR (10) NOT NULL, end_date VARCHAR (10) NOT NULL, start_time VARCHAR (8) NOT NULL, end_time VARCHAR (8) NOT NULL, subject VARCHAR(72) NOT NULL, details text, category VARCHAR (24) NOT NULL, multi SMALLINT UNSIGNED, uid BIGINT (12) NOT NULL, PRIMARY KEY (cid))");
                                        $T1SQL = $conn->query("SHOW TABLES LIKE '".$config["tcalendar"]."'");
                                        $T1OUT = $T1SQL->fetchAll(PDO::FETCH_ASSOC);
                                        if (sizeof($T1OUT) != 0){
                                                $status = "true";
                                                echo "<BR> &#128077; Table <B>".$config["tcalendar"]."</B> created successfully!";
                                                $conn->exec("CREATE TABLE IF NOT EXISTS ".$config["tattach"]." (aid int(11) NOT NULL AUTO_INCREMENT, modified TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE current_timestamp(), uid BIGINT (12) NOT NULL, attachment varchar (1024) NOT NULL, PRIMARY KEY (aid))");
                                                $T2SQL = $conn->query("SHOW TABLES LIKE '".$config["tattach"]."'");
                                                $T2OUT = $T2SQL->fetchAll(PDO::FETCH_ASSOC);
                                                if (sizeof($T2OUT) != 0){
                                                        $status = "true";
                                                        echo "<BR> &#128077; Table <B>".$config["tattach"]."</B> created successfully!<BR>";
                                                        echo "<BR>&#128077; SETUP STATUS SUCCESSFUL<BR>";
                                                        $config["setup"] = "complete";
                                                        file_put_contents('../conf/config.php', "<?php\nreturn " . var_export($config, true) . "\n?>");
                                                        echo "<p><a href=\"../\">Finish</a></p>";
                                                } else {
                                                        $status = "false";
                                                        echo "<BR> &#9940; Failed to create <B>".$config["tattach"]. "</B>table";
                                                        echo "&#128077; SETUP STATUS FAILED";
                                                        $config["setup"] = "failed";
                                                        file_put_contents('../conf/config.php', "<?php\nreturn " . var_export($config, true) . "\n?>");
                                                }
                                        } else {
                                                $status = "false";
                                                echo "<BR> &#9940; Failed to create <B>".$config["tcalendar"]. "</B>table";
                                                echo "&#128077; SETUP STATUS FAILED";
                                                $config["setup"] = "failed";
                                                file_put_contents('../conf/config.php', "<?php\nreturn " . var_export($config, true) . "\n?>");
                                        }
                                }
                        } else {
                                $status = "false";
                                echo '&#9940; Failed to connect to '.$config["dbtype"].'<BR>';
                                echo "&#128077; SETUP STATUS FAILED";
                                $config["setup"] = "failed";
                                file_put_contents('../conf/config.php', "<?php\nreturn " . var_export($config, true) . "\n?>");
                        }

                } catch (PDOException $em){
                      echo $em->getMessage();
              }
        }
  }
}

?>
<html>
<head>
      <title>Calendar Setup</title>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
      <link href="../style/main.css" type="text/css" rel="stylesheet" />
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <script src="../js/login.js"></script>

</head>

<body onLoad="emptyCode()">
	<a><img src="../img/header.jpg" alt="Header" style="width:100%;height:100px;"></a>
	<h1 style="text-align: center;">SETUP</h1>
	<CENTER>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <h2> Checking PHP installation </B></h2>
                <?php
                echo "<input type=\"hidden\" id=\"module\" name=\"module\" value=\"none\">";
                if (extension_loaded('core')) {
                        echo '&#128077; PHP version: ' . phpversion().'<BR>';
                        if (extension_loaded('curl')) {
                                echo '&#128077; CURL module: Installed<BR>';
                        } else {
                                echo '&#9940; CURL module: Missing<BR>';
                                echo "<input type=\"hidden\" id=\"module\" name=\"module\" value=\"curl\">";
                        }
                }
                ?>
                <BR><BR>
            <B>Database Type</B><BR>
            <select style="width: 30%" autocomplete="off" name="dbtype" id="dbtype">
                <option value="<?php echo $config['dbtype'];?>">Select: <?php echo strtoupper($config['dbtype']);?></option>
                <option value="none">--------</option>
                <option value="mysql">MySQL</option>
                <option value="pgsql">PostgreSQL</option>
            </select>
            <BR>
              <a><B>Database Host (ip address)</B></a><BR>
                <input style="width: 30%" type="text" name="dbhost" value="<?php echo $config['dbhost'];?>">
            <BR>
              <B>Database Port (3306, 5432)</B><BR>
                <input style="width: 30%" type="text" name="dbport" value="<?php echo $config['dbport'];?>">
            <BR>
              <B>Database Name (calendar)</B><BR>
                <input style="width: 30%" type="text" name="dbname" value="<?php echo $config['dbname'];?>">
            <BR>
              <B>DB Admin (root, postgres)</B><BR>
                <input style="width: 30%" type="text" name="dbuser" value="<?php echo $config['dbuser'];?>">
            <BR>
              <B>DB Password (admin)</B><BR>
                <input style="width: 30%" type="text" name="dbpass" value="<?php echo $config['dbpass'];?>">
            <BR>
              <B>Calendar URL (address)</B><BR>
                <input style="width: 30%" type="text" name="ucalendar" value="<?php echo $config['ucalendar'];?>">
            <BR>
              <B>Calendar PIN (8 digits)</B><BR>
                <input style="width: 30%" type="text" name="login" value="<?php echo $config['login'];?>">
              <br><br>
              <input type="submit" name="submit" value="Create">
            </form>
</body>
