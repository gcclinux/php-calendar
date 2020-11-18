<?php
setlocale(LC_ALL, "en_US.utf8");
require_once(__DIR__ . '/dbconf.php');

class run{
  private $conn;

	public function __construct() {
		$database = new Database();
		$db = $database->dbConnection();
    		$db->exec("SET NAMES 'UTF8'");
		$this->conn = $db;
  	}

	public function runQuery($sql) {
		$stmt = $this->conn->prepare($sql);
		return $stmt;
	}

  function validateDate($date, $format = 'd-m-Y') {
      $d = DateTime::createFromFormat($format, $date);
      return $d && $d->format($format) === $date;
  }

  public function count_days($d1,$d2){
    $date1 = date_create($d1);
    $date2 = date_create($d2);

    $diff = date_diff($date1,$date2);
    return $diff->format("%a");
  }

  	public function get_today() {
		$DATE = date('Y-m-d');
		return $DATE;
	}

  function searchString($search){
    try{
      $config = include(__DIR__ . '/config.php');
      $DATA = array();
      $SQL = $this->runQuery("SELECT cid,start_date,start_time,end_time,subject,category,uid FROM ".$config['tcalendar']." WHERE subject LIKE '%$search%' OR details LIKE '%$search%' ORDER by cid DESC");
      $SQL->execute();
      $RESULT = $SQL->fetchAll(PDO::FETCH_ASSOC);

        foreach ($RESULT as $row){
            $cid = $row["cid"];
            $startD = $row["start_date"];
            $startT = $row["start_time"];
            $endT = $row["end_time"];
            $subject = $row["subject"];
            $category = $row["category"];
            $uid = $row["uid"];
            $DATA[] = "$cid,$startD,$startT,$endT,$subject,$category,$uid";
        }
        return $DATA;
    } catch(PDOException $e){
        echo $e->getMessage();
      }
  }

  function getDateEvent($event) {
    try{
        $config = include(__DIR__ . '/config.php');
        $OUTPUT = array();
        $SQL = $this->runQuery("SELECT cid,start_date,start_time,end_time,subject,details,category,uid FROM ".$config['tcalendar']." WHERE start_date = '$event'");
        $SQL->execute();
        $RESULT = $SQL->fetchAll(PDO::FETCH_ASSOC);

        foreach ($RESULT as $row){
            $cid = $row["cid"];
            $startD = $row["start_date"];
            $startT = $row["start_time"];
            $endT = $row["end_time"];
            $subject = $row["subject"];
            $details = $row["details"];
            $category = $row["category"];
            $uid = $row["uid"];
            $OUTPUT[] = "$cid,$startD,$startT,$endT,$subject,$details,$category,$uid";
        }
        return $OUTPUT;
      } catch(PDOException $e){
        echo $e->getMessage();
      }
  }

  function getUniqueEvent($cid) {
    try{
        $config = include(__DIR__ . '/config.php');
        $OUTPUT = array();
        $SQL = $this->runQuery("SELECT cid,start_date,start_time,end_date,end_time,subject,details,category,multi,uid FROM ".$config['tcalendar']." WHERE cid = '$cid'");
        $SQL->execute();
        $RESULT = $SQL->fetchAll(PDO::FETCH_ASSOC);

        foreach ($RESULT as $row){
            $cid = $row["cid"];
            $startD = $row["start_date"];
            $startT = $row["start_time"];
            $endD = $row["end_date"];
            $endT = $row["end_time"];
            $subject = $row["subject"];
            $details = $row["details"];
            $category = $row["category"];
            $multi = $row["multi"];
            $uid = $row["uid"];
            $OUTPUT[] = "$cid,$startD,$startT,$endD,$endT,$subject,$details,$category,$multi,$uid";
        }
        return $OUTPUT;
      } catch(PDOException $e){
        echo $e->getMessage();
      }
  }

  function get_schedule($searchDay){
    try{
        $config = include(__DIR__ . '/config.php');
        $OUTPUT = array();
        $SQL = $this->runQuery("SELECT cid,start_date,start_time,end_time,subject,details,category,uid FROM ".$config['tcalendar']." WHERE start_date = '$searchDay'");
        $SQL->execute();
        $RESULT = $SQL->fetchAll(PDO::FETCH_ASSOC);

        foreach ($RESULT as $row){
            $cid = $row["cid"];
            $startD = $row["start_date"];
            $startT = $row["start_time"];
            $endT = $row["end_time"];
            $subject = $row["subject"];
            $details = $row["details"];
            $category = $row["category"];
            $uid = $row["uid"];
            $OUTPUT[] = "$cid,$startD,$startT,$endT,$subject,$details,$category,$uid";
        }
        return $OUTPUT;
      } catch(PDOException $e){
        echo $e->getMessage();
      }
  }

  function get_attachment($uid){
    try{
        $config = include(__DIR__ . '/config.php');
        $OUTPUT = array();
        $SQL = $this->runQuery("SELECT aid,uid,attachment from ".$config['tattach']." WHERE uid = '$uid'");
        $SQL->execute();
        $RESULT = $SQL->fetchAll(PDO::FETCH_ASSOC);

        foreach ($RESULT as $row){
            $aid = $row["aid"];
            $uid = $row["uid"];
            $file = $row["attachment"];
            $OUTPUT[] = "$aid,$uid,$file";
        }
        return $OUTPUT;
      } catch(PDOException $e){
        echo $e->getMessage();
      }
  }

  function get_multi_count($uniqueid){
    try{
        $config = include(__DIR__ . '/config.php');
        $SQL = $this->runQuery("SELECT COUNT(*) FROM ".$config['tcalendar']." WHERE uid = '$uniqueid'");
        $SQL->execute();
        $RESULT = $SQL->fetchAll(PDO::FETCH_ASSOC);
        return $RESULT;
    } catch(PDOException $e){
      echo $e->getMessage();
    }
  }

  function new_entry($subject,$details,$start_day,$start_month,$start_year,$start_hour,$start_min,$end_day,$end_month,$end_year,$end_hour,$end_min,$category,$recurrence,$uniq_id){
    try{
      $config = include(__DIR__ . '/config.php');
      $start_date = $start_day."-".$start_month."-".$start_year;
      $end_date = $end_day."-".$end_month."-".$end_year;
      $start_time = $start_hour.":".$start_min;
      $end_time = $end_hour.":".$end_min;
      $details = str_replace('\'', '', $details);
      $details = str_replace(',', '', $details);
      $details = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $details);
      $subject = str_replace(',', '', $subject);
      $subject = str_replace('\'', '', $subject);
      $subject = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $subject);

      $SQL = $this->runQuery("INSERT INTO ".$config['tcalendar']." (start_date,end_date,start_time,end_time,subject,details,category,multi,uid)
      VALUES ('$start_date','$end_date','$start_time','$end_time','$subject','$details','$category','$recurrence','$uniq_id')");

      if (!$SQL->execute()){
        $msg[] = " ---- Error inserting new Data ---- <br><br>";
        return $msg;
      }
    return;
    } catch(PDOException $e){
      echo $e->getMessage();
    }
  }

  function update_entry($cid,$subject,$details,$start_day,$start_month,$start_year,$start_hour,$start_min,$end_day,$end_month,$end_year,$end_hour,$end_min,$category){
    try {
      $config = include(__DIR__ . '/config.php');
      $start_date = $start_day."-".$start_month."-".$start_year;
      $end_date = $end_day."-".$end_month."-".$end_year;
      $start_time = $start_hour.":".$start_min;
      $end_time = $end_hour.":".$end_min;
      $details = str_replace('\'', '', $details);
      $details = str_replace(',', '', $details);
      $details = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $details);
      $subject = str_replace(',', '', $subject);
      $subject = str_replace('\'', '', $subject);
      $subject = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $subject);

      $SQL = $this->runQuery("UPDATE ".$config['tcalendar']." SET
        start_date = '$start_date',
        end_date = '$end_date',
        start_time = '$start_time',
        end_time = '$end_time',
        subject = '$subject',
        details = '$details',
        category = '$category'
        WHERE cid = '$cid'");

      if (!$SQL->execute()){
        $msg[] = " ---- Error updating Data ---- <br><br>";
        return $msg;
      }
    return;
    } catch(PDOException $e){
      echo $e->getMessage();
    }
  }

function delete_single($cid){
  try {
    $config = include(__DIR__ . '/config.php');
    $SQL = $this->runQuery("DELETE FROM ".$config['tcalendar']." WHERE cid = '$cid'");
    if (!$SQL->execute()){
      $msg[] = "---- Error delete single entry! ---- <br><br>";
      return $msg;
    }
    return;
  } catch(PDOException $e){
    echo $e->getMessage();
  }
}

function delete_multi($uniqueid){
  try{
    $config = include(__DIR__ . '/config.php');
    $SQL = $this->runQuery("DELETE FROM ".$config['tcalendar']." WHERE uid = '$uniqueid'");
    if (!$SQL->execute()){
      $msg[] = "---- Error delete multiple entries! ---- <br><br>";
      return;
    }
    return;
  }catch(PDOException $e){
    echo $e->getMessage();
  }
}

function delete_single_att($cid,$uniqueid){
  try {
    $count = $this->get_multi_count($uniqueid);
    $value = $count['0']['COUNT(*)'];
    $config = include(__DIR__ . '/config.php');

    if ($value == 1){
      $attach =  $this->get_attachment($uniqueid);
      foreach($attach	as $key) {
        $files = str_getcsv($key);
        if (!unlink('../'.$files[2])) {
          $msg[] = "".$files[2]." cannot be deleted due to an error";
          return $msg;
        }
      }
      $SQL = $this->runQuery("DELETE FROM ".$config['tcalendar']." WHERE uid = '$uniqueid'");
      if (!$SQL->execute()){
        $msg[] = "---- Error deleting files from DB! ---- <br><br>";
        return $msg;
      }
    }
    $SQL = $this->runQuery("DELETE FROM ".$config['tcalendar']." WHERE cid = '$cid'");
    if (!$SQL->execute()){
      $msg[] = "---- Error delete single entry! ---- <br><br>";
      return $msg;
    }
    return;
  }catch(PDOException $e){
    echo $e->getMessage();
  }
}

function delete_multi_att($uniqueid){
  try {
    $config = include(__DIR__ . '/config.php');
    $count = $this->get_multi_count($uniqueid);
    $attach =  $this->get_attachment($uniqueid);
    foreach($attach	as $key) {
      $files = str_getcsv($key);
      if (!unlink('../'.$files[2])) {
        $msg[] = "".$files[2]." cannot be deleted due to an error";
        return $msg;
      }
    }

    $SQL = $this->runQuery("DELETE ".$config['tcalendar'].", ".$config['tattach']." FROM ".$config['tcalendar']." INNER JOIN ".$config['tattach']." ON ".$config['tcalendar'].".uid = ".$config['tattach'].".uid  WHERE ".$config['tcalendar'].".uid = '$uniqueid'");
    if (!$SQL->execute()){
      $msg[] = "---- Error delete multiple entries! ---- <br><br>";
      return $msg;
    }
    return;

  }catch(PDOException $e){
    echo $e->getMessage();
  }
}

function new_attachment($uniq_id,$real_file){
  try{
    $config = include(__DIR__ . '/config.php');
    $SQL = $this->runQuery("INSERT INTO ".$config['tattach']."(uid,attachment)
    VALUES ('$uniq_id','$real_file')");
    if (!$SQL->execute()){
      $msg[] = " ---- Error inserting new Data ---- <br><br>";
      return $msg;
    }
  return;
  } catch(PDOException $e){
    echo $e->getMessage();
  }
}

function weather(){
    $config = include(__DIR__ . '/config.php');
    $city = $config['location'];
    $json = '';

    if ($city == '' ){
      $json = $this->geolocation();
      $city = $json['city'];
    }
    $url = "http://api.openweathermap.org/data/2.5/weather?q=".$city."&lang=en&units=metric&APPID=".$config['openweather'];
    $session = curl_init($url);
    curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
    $json = curl_exec($session);
    $phpObj =  json_decode($json);
    return $phpObj;
}

function geolocation(){
    $config = include(__DIR__ . '/config.php');
    $agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_VERBOSE, false);
    curl_setopt($ch, CURLOPT_FAILONERROR,1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    curl_setopt($ch, CURLOPT_URL,"https://geolocation-db.com/json/".$config['geolocation']);
    $data = curl_exec($ch);
    $json = json_decode($data, true);
    curl_close($ch);
    return $json;
}

function getRepeatPeriod($uniq_id){
      try{
        $config = include(__DIR__ . '/config.php');
        $OUTPUT = array();
        $SQL = $this->runQuery("SELECT (SELECT start_date FROM ".$config['tcalendar']." WHERE uid = '$uniq_id' ORDER BY cid ASC LIMIT 1) as start, (SELECT start_date FROM ".$config['tcalendar']." WHERE uid = '$uniq_id' ORDER BY cid DESC LIMIT 1) as end");
        $SQL->execute();
        $RESULT = $SQL->fetchAll(PDO::FETCH_ASSOC);

        foreach ($RESULT as $row){
          $START = $row["start"];
          $END = $row["end"];
          $OUTPUT[] = "$START,$END";
        }
        return $OUTPUT;
      } catch(PDOException $e){
                echo $e->getMessage();
      }
  }
}
?>
