<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_STRICT);
set_include_path("." . PATH_SEPARATOR . ($UserDir = dirname($_SERVER['DOCUMENT_ROOT'])) . "mail/PEAR/" . PATH_SEPARATOR);

require_once(__DIR__ . '/mail/Mail.php');
require_once(__DIR__ . '/mail/Mine/Mail/mime.php');
require_once(__DIR__ . '/../conf/dbconf.php');
require_once(__DIR__ . '/../conf/functions.php');
$config = include(__DIR__ . '/../conf/config.php');

$URL = $config['ucalendar'];
$URL = rtrim($URL, '/');

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

$weather = $RUN->weather();
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

$mserver = $config['smtp'];
$mport = $config['mport'];
$mailu = $config['mailu'];
$mailp = $config['mailp'];
$mailr = $config['mailr'];
$mails = $config['mails'];

    if ((sizeof($events) == 0) && (sizeof($events_tomorrow) == 0) && (sizeof($events_third_day) == 0)) {
      echo "#### No Events Recorded for today ####\n";
    } else {
      $SUBJECT = "Personal Reminder on ".$today;
      $BODY = "
      <html>
      <head>
            <title>Calendar Summary</title>
            <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>
            <script src=".$URL."/js/time.js\"></script>
      </head>
      <body onload=\"startTime()\">
         <a href=\"".$URL."/main.php\"><img src=\"".$URL."/img/header.jpg\" alt=\"Header\" style=\"width:100%;height:100px;\"></a>
         <h1 style=\"text-align: center;\">Calendar Summary</h1>
         <center>
         <br><font size=\"4\"><strong>$city</font></strong>
         <br>$today<br>
         <div style=\"font-size:100%;font-weight:bold;\" id=\"time\"></div>

         <img style=\"filter: brightness(70%);\" src=\"https://openweathermap.org/img/wn/$icon\" width=\"91\" height=\"91\" ><br>
         <font size=\"6\">$temp_cur&deg;C<br></font>
         <I>Feels: $feels_like &deg;C
           Min: $temp_min &deg;C
           Max: $temp_max &deg;C</I><br><BR>
                <a href=\"".$URL."/main.php\"><img src=\"".$URL."/img/cal-black.png\" alt=\"Calendar\" style=\"width:40px;height:40px;\"></a>
           <h1 style=\"text-align: center;\">Reminder</h1>
           <div style=\"width:50%\"><hr>";

           //TODAY
            $BODY .= "<B><U>".$today."</U></B><BR><BR>";
           if (sizeof($events) == 0){
              $BODY .= "&#8608; No Events Recorded for today &#8606;";
           } else {
              foreach($events as $sub) {
                $count = 1;
                $list = str_getcsv($sub);

                 $BODY .= "<B>SUBJECT:&emsp;</B>".ucfirst($list[4])."<br>";
                   $BODY .= "<B>TODAY</B> &emsp;".$list[1]."&emsp;&emsp; <B>FROM</B>&emsp;" .$list[2]."&emsp; &emsp;<B>UNTIL</B> ".$list[3]."&emsp;&emsp;<br>";
                   $BODY .= "<BR>".($list[5])."<BR>";
                   $BODY .= "</p>";
                   $BODY .= "";
              }
            }
            // TOMORROW
             $BODY .= "<hr>";
             $BODY .= "<B><U>".$tomorrow."</U></B><BR><BR>";
            if (sizeof($events_tomorrow) == 0){
               $BODY .= "<p>&#8608; No Events in Calendar &#8606; </p>";
            } else {
               foreach($events_tomorrow as $sub) {
                 $count = 1;
                 $list = str_getcsv($sub);

                  $BODY .= "<B>SUBJECT:&emsp;</B>".ucfirst($list[4])."<br>";
                    $BODY .= "<B>TODAY</B> &emsp;".$list[1]."&emsp;&emsp; <B>FROM</B>&emsp;" .$list[2]."&emsp; &emsp;<B>UNTIL</B> ".$list[3]."&emsp;&emsp;<br>";
                    $BODY .= "<BR>".($list[5])."<BR>";
                    $BODY .= "</p>";
                    $BODY .= "";
               }
             }
             // THIRD days
              $BODY .= "<hr>";
              $BODY .= "<B><U>".$third_day."</U></B><BR><BR>";
             if (sizeof($events_third_day) == 0){
                $BODY .= "<p>&#8608; No Events in Calendar &#8606; </p>";
             } else {
                foreach($events_third_day as $sub) {
                  $count = 1;
                  $list = str_getcsv($sub);

                     $BODY .= "<B>SUBJECT:&emsp;</B>".ucfirst($list[4])."<br>";
                     $BODY .= "<B>TODAY</B> &emsp;".$list[1]."&emsp;&emsp; <B>FROM</B>&emsp;" .$list[2]."&emsp; &emsp;<B>UNTIL</B> ".$list[3]."&emsp;&emsp;<br>";
                     $BODY .= "<BR>".($list[5])."<BR>";
                     $BODY .= "</p>";
                     $BODY .= "";
                }
              }

          $BODY .= "</div></body></html>";

          $crlf = "\r\n";
          $mime = new Mail_mime($crlf);
          $mime->setHTMLBody($BODY);
          $BODY_PREP = $mime->get();
          $headers = array ('From' => $mails, 'To' => $mailr, 'Subject' => $SUBJECT, 'Reply-To' => $mails);
          $headers = $mime->headers($headers);

          $smtp = Mail::factory('smtp', array ('host' => $mserver, 'port' => $mport, 'auth' => true, 'username' => $mailu, 'password' => $mailp));
          $mail = $smtp->send($mailr, $headers, $BODY_PREP);

            if (PEAR::isError($mail)) {
              echo("-" . $mail->getMessage() . "\n");
            } else {
              echo "Email sent successfully on $today\n";
            }
    }
?>
