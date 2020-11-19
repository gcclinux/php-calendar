<?php
header('Content-type: text/html; charset=UTF-8');
//date_default_timezone_set('UTC');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$config = include(__DIR__ . '/../conf/config.php');
require_once(__DIR__ . '/../conf/dbconf.php');
require(__DIR__ . '/../conf/functions.php');

$newURL = '../';
$hasSlash = substr($newURL, strlen($newURL)-1, 1);
if ($hasSlash != '/'){ $newURL .= '/';}

$RUN = new run;

//print_r($_POST);

if (isset($_POST['uniqueid'])){
  $uniqueid = ($_POST['uniqueid']);
} else {
  echo "Error 1";
  exit (1);
}
if (isset($_POST['id'])){
  $cid = ($_POST['id']);
} else {
  echo "Error 2";
  exit (1);
}
if (isset($_POST['returndate'])){
  $returndate = ($_POST['returndate']);
  $month = date("m", strtotime($returndate));
  $year = date("Y", strtotime($returndate));
} else {
  echo "Error 3";
  exit (1);
}

if (isset($_POST['deletedata'])){
  if (isset($_POST['multipletrue'])){
    if (isset($_POST['attachmentstrue'])){
      if (!$RUN->delete_multi_att($uniqueid)){
        $msg = "---- Successful ----";
        header('Location: '.$newURL."main.php?events=".$returndate."&month=".$month."&year=".$year."&msg=".$msg);
      }
      die();
    } else {
      if (!$RUN->delete_multi($uniqueid)){
        $msg = "---- Successful ----";
        header('Location: '.$newURL."main.php?events=".$returndate."&month=".$month."&year=".$year."&msg=".$msg);
      }
      die();
    }
  } else {
    if (isset($_POST['attachmentstrue'])){
      if (!$RUN->delete_single_att($cid,$uniqueid)){
        $msg = "---- Successful ----";
        header('Location: '.$newURL."main.php?events=".$returndate."&month=".$month."&year=".$year."&msg=".$msg);
      }
      die();
    } else {
      if (!$RUN->delete_single($cid)){
        $msg = "---- Successful ----";
        header('Location: '.$newURL."main.php?events=".$returndate."&month=".$month."&year=".$year."&msg=".$msg);
      }
      die();
    }
  }
} elseif (isset($_POST['savedata'])){

  $subject = ($_POST['subject']);
  $details = ($_POST["details"]);
  $start_day = ($_POST['start_day']);
  $start_month = ($_POST['start_month']);
  $start_year = ($_POST['start_year']);
  $start_hour = ($_POST['start_hour']);
  $start_min = ($_POST['start_min']);
  $end_day = ($_POST['end_day']);
  $end_month = ($_POST['end_month']);
  $end_year = ($_POST['end_year']);
  $end_hour = ($_POST['end_hour']);
  $end_min = ($_POST['end_min']);
  $category = ($_POST['category']);
  $recurrence = ($_POST['repeat']);

  $initial_day = ($_POST['initial_day']);
  $initial_month = ($_POST['initial_month']);
  $initial_year = ($_POST['initial_year']);

  $until_day = ($_POST['until_day']);
  $until_month = ($_POST['until_month']);
  $until_year = ($_POST['until_year']);

  $start_date = $start_day."-".$start_month."-".$start_year;
  $initial_date = $initial_day."-".$initial_month."-".$initial_year;
  $until_date = $until_day."-".$until_month."-".$until_year;

  $countfiles = count($_FILES['file']['name']);
   // Looping all files
  for($i=0;$i<$countfiles;$i++){
    $filename = $_FILES['file']['name'][$i];
    $size = strlen($filename);
        if ($size != 0){
          $real_file = '';
          if(file_exists('../files/'.$filename)) {
              $real_file = "files/".$uniqueid."_".$filename;
              move_uploaded_file($_FILES['file']['tmp_name'][$i],'../files/'.$uniqueid.'_'.$filename);
              $RUN->new_attachment($uniqueid,$real_file);
          } else {
              $real_file = "files/".$filename;
              move_uploaded_file($_FILES['file']['tmp_name'][$i],'../files/'.$filename);
              $RUN->new_attachment($uniqueid,$real_file);
          }
      }
  }

  if (isset($_POST['updatealltrue'])){
    if (!$RUN->delete_multi($uniqueid)){
      /*START REPEAT PROCESS*/
      if ($recurrence == 0){
            $RUN->new_entry($subject,$details,$start_day,$start_month,$start_year,$start_hour,$start_min,$end_day,$end_month,$end_year,$end_hour,$end_min,$category,$recurrence,$uniqueid);
      } elseif ($recurrence == 7){ // Yearly
           for ($n=($end_year);$n<($until_year+1);$n++){
             $RUN->new_entry($subject,$details,$start_day,$start_month,$n,$start_hour,$start_min,$end_day,$end_month,$n,$end_hour,$end_min,$category,$recurrence,$uniqueid);
           }
       } elseif ($recurrence == 6){
           if ($until_year >= ($end_year + 10)){
                 $until_year = ($end_year + 10);
                 $until_year = ($end_year + 10);
                 $until_date = $until_day."-".$until_month."-".$until_year;
           }
           $RUN->new_entry($subject,$details,$start_day,$start_month,$start_year,$start_hour,$start_min,$end_day,$end_month,$end_year,$end_hour,$end_min,$category,$recurrence,$uniqueid);
           // Start date
           $date = $start_date;
           $bits = explode('-',$date);
           $date = $bits[1].'/'.$bits[0].'/'.$bits[2];
           // End date
           $end = $until_date;
           $bits = explode('-',$end);
           $end = $bits[1].'/'.$bits[0].'/'.$bits[2];

           while (strtotime($date) < strtotime($end)) {
              $date = date ("d-m-Y", strtotime("+3 months", strtotime($date)));
              $start_day = date("d",strtotime($date));
              $start_month = date("m",strtotime($date));
              $start_year = date("Y",strtotime($date));
              $RUN->new_entry($subject,$details,$start_day,$start_month,$start_year,$start_hour,$start_min,$start_day,$start_month,$start_year,$end_hour,$end_min,$category,$recurrence,$uniqueid);
           }
       } elseif ($recurrence == 5){
           if ($until_year >= ($end_year + 10)){
                 $until_year = ($end_year + 10);
                 $until_year = ($end_year + 10);
                 $until_date = $until_day."-".$until_month."-".$until_year;
           }
           $RUN->new_entry($subject,$details,$start_day,$start_month,$start_year,$start_hour,$start_min,$end_day,$end_month,$end_year,$end_hour,$end_min,$category,$recurrence,$uniqueid);
           // Start date
           $date = $start_date;
           $bits = explode('-',$date);
           $date = $bits[1].'/'.$bits[0].'/'.$bits[2];
           // End date
           $end = $until_date;
           $bits = explode('-',$end);
           $end = $bits[1].'/'.$bits[0].'/'.$bits[2];

           while (strtotime($date) < strtotime($end)) {
              $date = date ("d-m-Y", strtotime("+2 months", strtotime($date)));
              $start_day = date("d",strtotime($date));
              $start_month = date("m",strtotime($date));
              $start_year = date("Y",strtotime($date));
              $RUN->new_entry($subject,$details,$start_day,$start_month,$start_year,$start_hour,$start_min,$start_day,$start_month,$start_year,$end_hour,$end_min,$category,$recurrence,$uniqueid);
           }
       } elseif ($recurrence == 4){
           if ($until_year >= ($end_year + 10)){
                 $until_year = ($end_year + 10);
                 $until_year = ($end_year + 10);
                 $until_date = $until_day."-".$until_month."-".$until_year;
           }
           $RUN->new_entry($subject,$details,$start_day,$start_month,$start_year,$start_hour,$start_min,$end_day,$end_month,$end_year,$end_hour,$end_min,$category,$recurrence,$uniqueid);
           // Start date
           $date = $start_date;
           $bits = explode('-',$date);
           $date = $bits[1].'/'.$bits[0].'/'.$bits[2];
           // End date
           $end = $until_date;
           $bits = explode('-',$end);
           $end = $bits[1].'/'.$bits[0].'/'.$bits[2];

           while (strtotime($date) < strtotime($end)) {
              $date = date ("d-m-Y", strtotime("+1 months", strtotime($date)));
              $start_day = date("d",strtotime($date));
              $start_month = date("m",strtotime($date));
              $start_year = date("Y",strtotime($date));
              $RUN->new_entry($subject,$details,$start_day,$start_month,$start_year,$start_hour,$start_min,$start_day,$start_month,$start_year,$end_hour,$end_min,$category,$recurrence,$uniqueid);
           }
       } elseif ($recurrence == 3){
           if ($until_year >= ($end_year + 10)){
                 $until_year = ($end_year + 10);
                 $until_year = ($end_year + 10);
                 $until_date = $until_day."-".$until_month."-".$until_year;
           }
           $RUN->new_entry($subject,$details,$start_day,$start_month,$start_year,$start_hour,$start_min,$end_day,$end_month,$end_year,$end_hour,$end_min,$category,$recurrence,$uniqueid);
           // Start date
           $date = $start_date;
           $bits = explode('-',$date);
           $date = $bits[1].'/'.$bits[0].'/'.$bits[2];
           // End date
           $end = $until_date;
           $bits = explode('-',$end);
           $end = $bits[1].'/'.$bits[0].'/'.$bits[2];

           while (strtotime($date) < strtotime($end)) {
              $date = date ("d-m-Y", strtotime("+2 weeks", strtotime($date)));
              $start_day = date("d",strtotime($date));
              $start_month = date("m",strtotime($date));
              $start_year = date("Y",strtotime($date));
              $RUN->new_entry($subject,$details,$start_day,$start_month,$start_year,$start_hour,$start_min,$start_day,$start_month,$start_year,$end_hour,$end_min,$category,$recurrence,$uniqueid);
           }
       } elseif ($recurrence == 2){
           if ($until_year >= ($end_year + 10)){
                 $until_year = ($end_year + 10);
                 $until_year = ($end_year + 10);
                 $until_date = $until_day."-".$until_month."-".$until_year;
           }
           $RUN->new_entry($subject,$details,$start_day,$start_month,$start_year,$start_hour,$start_min,$end_day,$end_month,$end_year,$end_hour,$end_min,$category,$recurrence,$uniqueid);
           // Start date
           $date = $start_date;
           $bits = explode('-',$date);
           $date = $bits[1].'/'.$bits[0].'/'.$bits[2];
           // End date
           $end = $until_date;
           $bits = explode('-',$end);
           $end = $bits[1].'/'.$bits[0].'/'.$bits[2];

           while (strtotime($date) < strtotime($end)) {
              $date = date ("d-m-Y", strtotime("+1 week", strtotime($date)));
              $start_day = date("d",strtotime($date));
              $start_month = date("m",strtotime($date));
              $start_year = date("Y",strtotime($date));
              $RUN->new_entry($subject,$details,$start_day,$start_month,$start_year,$start_hour,$start_min,$start_day,$start_month,$start_year,$end_hour,$end_min,$category,$recurrence,$uniqueid);
           }
       } elseif ($recurrence == 1){
           if ($until_year >= ($end_year + 10)){
                 $until_year = ($end_year + 10);
                 $until_year = ($end_year + 10);
                 $until_date = $until_day."-".$until_month."-".$until_year;
           }
           $RUN->new_entry($subject,$details,$start_day,$start_month,$start_year,$start_hour,$start_min,$end_day,$end_month,$end_year,$end_hour,$end_min,$category,$recurrence,$uniqueid);
           // Start date
           $date = $start_date;
           $bits = explode('-',$date);
           $date = $bits[1].'/'.$bits[0].'/'.$bits[2];
           // End date
           $end = $until_date;
           $bits = explode('-',$end);
           $end = $bits[1].'/'.$bits[0].'/'.$bits[2];

           while (strtotime($date) < strtotime($end)) {
              $date = date ("d-m-Y", strtotime("+1 day", strtotime($date)));
              $start_day = date("d",strtotime($date));
              $start_month = date("m",strtotime($date));
              $start_year = date("Y",strtotime($date));
              $RUN->new_entry($subject,$details,$start_day,$start_month,$start_year,$start_hour,$start_min,$start_day,$start_month,$start_year,$end_hour,$end_min,$category,$recurrence,$uniqueid);
           }
       } else {
          $RUN->new_entry($subject,$details,$start_day,$start_month,$start_year,$start_hour,$start_min,$end_day,$end_month,$end_year,$end_hour,$end_min,$category,$recurrence,$uniqueid);
       }
    }
  } else {
    $RUN->update_entry($cid,$subject,$details,$start_day,$start_month,$start_year,$start_hour,$start_min,$end_day,$end_month,$end_year,$end_hour,$end_min,$category);
  }
  header('Location: '.$newURL.'main.php');
}

?>
