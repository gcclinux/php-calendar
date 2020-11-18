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
  $uniq_id = date('ymdHis');
  $until_day = ($_POST['until_day']);
  $until_month = ($_POST['until_month']);
  $until_year = ($_POST['until_year']);

  $start_date = $start_day."-".$start_month."-".$start_year;
  $end_date = $end_day."-".$end_month."-".$end_year;
  $start_time = $start_hour.":".$start_min;
  $end_time = $end_hour.":".$end_min;
  $until_date = $until_day."-".$until_month."-".$until_year;

  $countfiles = count($_FILES['file']['name']);
   // Looping all files
  for($i=0;$i<$countfiles;$i++){
    $filename = $_FILES['file']['name'][$i];
    $size = strlen($filename);
        if ($size != 0){
          $real_file = '';
          if(file_exists('../files/'.$filename)) {
              $real_file = "files/".$uniq_id."_".$filename;
              move_uploaded_file($_FILES['file']['tmp_name'][$i],'../files/'.$uniq_id.'_'.$filename);
              $RUN->new_attachment($uniq_id,$real_file);
          } else {
              $real_file = "files/".$filename;
              move_uploaded_file($_FILES['file']['tmp_name'][$i],'../files/'.$filename);
              $RUN->new_attachment($uniq_id,$real_file);
          }
      }
  }

  /*START REPEAT PROCESS*/
  if ($recurrence == 0){
        $RUN->new_entry($subject,$details,$start_day,$start_month,$start_year,$start_hour,$start_min,$end_day,$end_month,$end_year,$end_hour,$end_min,$category,$recurrence,$uniq_id);
  } elseif ($recurrence == 7){ // Yearly
       for ($n=($end_year);$n<($until_year+1);$n++){
         $RUN->new_entry($subject,$details,$start_day,$start_month,$n,$start_hour,$start_min,$end_day,$end_month,$n,$end_hour,$end_min,$category,$recurrence,$uniq_id);
       }
   } elseif ($recurrence == 6){
       if ($until_year >= ($end_year + 10)){
             $until_year = ($end_year + 10);
             $until_year = ($end_year + 10);
             $until_date = $until_day."-".$until_month."-".$until_year;
       }
       $RUN->new_entry($subject,$details,$start_day,$start_month,$start_year,$start_hour,$start_min,$end_day,$end_month,$end_year,$end_hour,$end_min,$category,$recurrence,$uniq_id);
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
          $RUN->new_entry($subject,$details,$start_day,$start_month,$start_year,$start_hour,$start_min,$start_day,$start_month,$start_year,$end_hour,$end_min,$category,$recurrence,$uniq_id);
       }
   } elseif ($recurrence == 5){
       if ($until_year >= ($end_year + 10)){
             $until_year = ($end_year + 10);
             $until_year = ($end_year + 10);
             $until_date = $until_day."-".$until_month."-".$until_year;
       }
       $RUN->new_entry($subject,$details,$start_day,$start_month,$start_year,$start_hour,$start_min,$end_day,$end_month,$end_year,$end_hour,$end_min,$category,$recurrence,$uniq_id);
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
          $RUN->new_entry($subject,$details,$start_day,$start_month,$start_year,$start_hour,$start_min,$start_day,$start_month,$start_year,$end_hour,$end_min,$category,$recurrence,$uniq_id);
       }
   } elseif ($recurrence == 4){
       if ($until_year >= ($end_year + 10)){
             $until_year = ($end_year + 10);
             $until_year = ($end_year + 10);
             $until_date = $until_day."-".$until_month."-".$until_year;
       }
       $RUN->new_entry($subject,$details,$start_day,$start_month,$start_year,$start_hour,$start_min,$end_day,$end_month,$end_year,$end_hour,$end_min,$category,$recurrence,$uniq_id);
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
          $RUN->new_entry($subject,$details,$start_day,$start_month,$start_year,$start_hour,$start_min,$start_day,$start_month,$start_year,$end_hour,$end_min,$category,$recurrence,$uniq_id);
       }
   } elseif ($recurrence == 3){
       if ($until_year >= ($end_year + 10)){
             $until_year = ($end_year + 10);
             $until_year = ($end_year + 10);
             $until_date = $until_day."-".$until_month."-".$until_year;
       }
       $RUN->new_entry($subject,$details,$start_day,$start_month,$start_year,$start_hour,$start_min,$end_day,$end_month,$end_year,$end_hour,$end_min,$category,$recurrence,$uniq_id);
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
          $RUN->new_entry($subject,$details,$start_day,$start_month,$start_year,$start_hour,$start_min,$start_day,$start_month,$start_year,$end_hour,$end_min,$category,$recurrence,$uniq_id);
       }
   } elseif ($recurrence == 2){
       if ($until_year >= ($end_year + 10)){
             $until_year = ($end_year + 10);
             $until_year = ($end_year + 10);
             $until_date = $until_day."-".$until_month."-".$until_year;
       }
       $RUN->new_entry($subject,$details,$start_day,$start_month,$start_year,$start_hour,$start_min,$end_day,$end_month,$end_year,$end_hour,$end_min,$category,$recurrence,$uniq_id);
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
          $RUN->new_entry($subject,$details,$start_day,$start_month,$start_year,$start_hour,$start_min,$start_day,$start_month,$start_year,$end_hour,$end_min,$category,$recurrence,$uniq_id);
       }
   } elseif ($recurrence == 1){
       if ($until_year >= ($end_year + 10)){
             $until_year = ($end_year + 10);
             $until_year = ($end_year + 10);
             $until_date = $until_day."-".$until_month."-".$until_year;
       }
       $RUN->new_entry($subject,$details,$start_day,$start_month,$start_year,$start_hour,$start_min,$end_day,$end_month,$end_year,$end_hour,$end_min,$category,$recurrence,$uniq_id);
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
          $RUN->new_entry($subject,$details,$start_day,$start_month,$start_year,$start_hour,$start_min,$start_day,$start_month,$start_year,$end_hour,$end_min,$category,$recurrence,$uniq_id);
       }
   } else {
      $RUN->new_entry($subject,$details,$start_day,$start_month,$start_year,$start_hour,$start_min,$end_day,$end_month,$end_year,$end_hour,$end_min,$category,$recurrence,$uniq_id);
   }

 header('Location: '.$newURL.'main.php');
?>
