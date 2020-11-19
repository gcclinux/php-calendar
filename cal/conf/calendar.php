<?php

require_once(__DIR__ . '/dbconf.php');

class Calendar {
    /**
     * Constructor
     */
     private $conn;

     public function __construct() {
       $this->naviHref = htmlentities($_SERVER['PHP_SELF']);
       $database = new Database();
       $db = $database->dbConnection();
       $this->conn = $db;
       }

     public function runQuery($sql) {
       $stmt = $this->conn->prepare($sql);
       return $stmt;
     }

    function getBetween($content,$block_start,$block_end){
        $r = explode($block_start, $content);
        if (isset($r[1])){
            $r = explode($block_end, $r[1]);
            return $r[0];
        }
        return '';
    }

    function check_schedule($searchDay){
	$config = include(__DIR__ . '/config.php');
	$ctable = $config['tcalendar'];
        $value = '';
	$SQL = $this->runQuery("SELECT * FROM ".$ctable." WHERE start_date = '$searchDay'");
        $SQL->execute();
        $RESULT = $SQL->fetchAll(PDO::FETCH_ASSOC);

        if ($SQL->rowCount() == 0){
          $value = false;
        } else {
          $value = true;
        }
        return $value;
    }

    /********************* PROPERTY ********************/
    private $dayLabels = array("Mon","Tue","Wed","Thu","Fri","Sat","Sun");

    private $currentYear=0;
    private $currentMonth=0;
    private $currentDay=0;
    private $currentDate=null;
    private $daysInMonth=0;
    private $naviHref= null;

    /********************* PUBLIC **********************/

    /**
    * print out the calendar
    */
    public function show() {
        $year  = null;
        $month = null;

        if(null==$year&&isset($_GET['year'])){

            $year = $_GET['year'];

        }else if(null==$year){

            $year = date("Y",time());

        }

        if(null==$month&&isset($_GET['month'])){

            $month = $_GET['month'];

        }else if(null==$month){

            $month = date("m",time());

        }

        $this->currentYear=$year;
        $this->currentMonth=$month;

        $this->daysInMonth=$this->_daysInMonth($month,$year);

        $content='<div id="calendar">'.
                        '<div class="box">'.
                        $this->_createNavi().
                        '</div>'.
                        '<div class="box-content" >'.
                                '<ul class="label">'.$this->_createLabels().'</ul>';
                                $content.='<div class="clear"></div>';
                                $content.='<ul class="dates">';
                                $weeksInMonth = $this->_weeksInMonth($month,$year);
                                // Create weeks in a month
                                for( $i=0; $i<$weeksInMonth; $i++ ){
                                    //Create days in a week
                                    for($j=1;$j<=7;$j++){
                                        $content.=$this->_showDay($i*7+$j);
                                    }
                                }

                                $content.='</ul>';
                                $content.='<div class="clear"></div>';
                        $content.='</div>';

        $content.='</div>';
        return $content;
    }

    /********************* PRIVATE **********************/
    /**
    * create the li element for ul
    */
    private function _showDay($cellNumber){

        $today = date("j");
        $month = date("m");
        $result = '';

        $thisMonth = $this->currentMonth==13?1:intval($this->currentMonth);
        $thisYear = $this->currentMonth==12?intval($this->currentYear):$this->currentYear;

        if($this->currentDay==0){

            $firstDayOfTheWeek = date('N',strtotime($this->currentYear.'-'.$this->currentMonth.'-01'));

            if(intval($cellNumber) == intval($firstDayOfTheWeek)){

                $this->currentDay=1;

            }
        }

        if( ($this->currentDay!=0)&&($this->currentDay<=$this->daysInMonth) ){

            $this->currentDate = date('d-m-Y',strtotime($this->currentYear.'-'.$this->currentMonth.'-'.($this->currentDay)));

            $cellContent = $this->currentDay;

            $this->currentDay++;

        }else{

            $this->currentDate =null;

            $cellContent=null;
        }

        // Buid date string for database search
        if (strlen($cellContent) == 1){
          $cellContent = "0".$cellContent;
        }

        if (strlen($thisMonth) == 1){
          $thisMonth = "0".$thisMonth;
        }
        $searchDay = $cellContent."-".$thisMonth."-".$thisYear;

        /* Check current date n matches set new style */

        $start = '<li id="li-'.$this->currentDate.'" class="'.($cellNumber%7==1?' start ':($cellNumber%7==0?' end ':' ')).($cellContent==null?'mask':'').'"';

        /* MUST CHANGE ON CLICK SHOW DETAILS OF SELECTED DATE, NEW ENTRY WILL BE AVAILABLE ON RIGHT HAND SIDE BELOW THE SELECTED DATE DETAILS*/
        $not_today = '><a class="cal_click_no_event" href="main.php?events='.$searchDay.'&month='.$thisMonth.'&year='.$thisYear.'">'.$cellContent.'</a></li>';
        $not_today_action = '><a class="cal_click_event" href="main.php?events='.$searchDay.'&month='.$thisMonth.'&year='.$thisYear.'">'.$cellContent.'</a></li>';
        $today_no_action = '><a class="cal_click_today" href="main.php?events='.$searchDay.'&month='.$thisMonth.'&year='.$thisYear.'">'.$cellContent.'</a></li>';
        $today_action = '><a class="cal_click_today_event" href="main.php?events='.$searchDay.'&month='.$thisMonth.'&year='.$thisYear.'">'.$cellContent.'</a></li>';

        $action = $this->check_schedule($searchDay);

        if ($cellContent != $today && $action == true){
          $result = $start.$not_today_action;
        } elseif ($cellContent != $today && $action == false){
          $result = $start.$not_today;
        } elseif ($cellContent == $today && $month == $thisMonth && $action == true){
          $result = $start.$today_action;
        } elseif ($cellContent == $today && $month == $thisMonth && $action == false ){
          $result = $start.$today_no_action;
        } else {
          $result = $start.$not_today;
        }

        return $result;
    }

    /**
    * create navigation
    */
    private function _createNavi(){

        $nextMonth = $this->currentMonth==12?1:intval($this->currentMonth)+1;
        $nextYear = $this->currentMonth==12?intval($this->currentYear)+1:$this->currentYear;
        $preMonth = $this->currentMonth==1?12:intval($this->currentMonth)-1;
        $preYear = $this->currentMonth==1?intval($this->currentYear)-1:$this->currentYear;

        return
            '<div class="header">'.
                '<a class="prev" href="'.$this->naviHref.'?month='.sprintf('%02d',$preMonth).'&year='.$preYear.'">'.date('F', mktime(0, 0, 0, $preMonth, 10)).'</a>'.
                    '<span class="title">'.date('M Y',strtotime($this->currentYear.'-'.$this->currentMonth)).'</span>'.
                '<a class="next" href="'.$this->naviHref.'?month='.sprintf("%02d", $nextMonth).'&year='.$nextYear.'">'.date('F', mktime(0, 0, 0, $nextMonth, 10)).'</a>'.
            '</div>';
    }

    /**
    * create calendar week labels
    */
    private function _createLabels(){
        $content='';
        foreach($this->dayLabels as $index=>$label){
            $content.='<li class="'.($label==6?'end title':'start title').' title">'.$label.'</li>';
        }
        return $content;
    }



    /**
    * calculate number of weeks in a particular month
    */
    private function _weeksInMonth($month=null,$year=null){

        if( null==($year) ) {
            $year =  date("Y",time());
        }

        if(null==($month)) {
            $month = date("m",time());
        }

        // find number of days in this month
        $daysInMonths = $this->_daysInMonth($month,$year);

        $numOfweeks = ($daysInMonths%7==0?0:1) + intval($daysInMonths/7);

        $monthEndingDay= date('N',strtotime($year.'-'.$month.'-'.$daysInMonths));

        $monthStartDay = date('N',strtotime($year.'-'.$month.'-01'));

        if($monthEndingDay<$monthStartDay){

            $numOfweeks++;

        }

        return $numOfweeks;
    }

    /**
    * calculate number of days in a particular month
    */
    private function _daysInMonth($month=null,$year=null){

        if(null==($year))
            $year =  date("Y",time());

        if(null==($month))
            $month = date("m",time());

        return date('t',strtotime($year.'-'.$month.'-01'));
    }

}
