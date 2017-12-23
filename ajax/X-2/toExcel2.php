<?php
/*
include_once('../../inc/config.php');
include_once('../../inc/global.php');
include_once('../../inc/class_log.php');
include_once('../../inc/class_book.php');
*/
include_once('../../inc/utils.php');
include_once('../../inc/class_user.php');
include_once('../../inc/report_config.php');
include_once('../../inc/PHPExcel.php');
require_once('../../inc/PHPExcel/Writer/Excel2007.php');


$from = isset($_REQUEST['from'])?$_REQUEST['from']:'';
$to = isset($_REQUEST['to'])?$_REQUEST['to']:'';
$units = isset($_REQUEST['units'])?$_REQUEST['units']:'';
$ranks = isset($_REQUEST['ranks'])?$_REQUEST['ranks']:'';
$rank_select = isset($_REQUEST['rank_select'])?$_REQUEST['rank_select']:'';


$objPHPExcel = new PHPExcel();

$objPHPExcel->getProperties()->setCreator("Moker");
$objPHPExcel->getProperties()->setLastModifiedBy("Moker");
//$objPHPExcel->getProperties()->setVersion(8);
$objPHPExcel->getProperties()->setTitle("");
$objPHPExcel->getProperties()->setSubject("");
$objPHPExcel->getProperties()->setDescription("");
$objPHPExcel->getProperties()->setKeywords("");
$objPHPExcel->getProperties()->setCategory("");


$select = 1;


$objPHPExcel->setActiveSheetIndex(0);

$sheet = $objPHPExcel->getActiveSheet();

$sheet->setTitle('查詢條件');

$sheet->getStyle('B2:F2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$sheet->getStyle('B2:F2')->getFill()->getStartColor()->setARGB('002D507A'); 
$sheet->getStyle('B2:F2')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
$sheet->getColumnDimension('B:F')->setAutoSize(true);

$sheet->setCellValue('B2', '起始日期');
$sheet->setCellValue('B3', ($from=='')?'未指定':$from);
$sheet->setCellValue('C2', '結束日期');
$sheet->setCellValue('C3', ($to=='')?'未指定':$to);

$sheet->setCellValue('D2', '單位');

$userdb = new User();
$userdb->init();
$unit_array=array();
if ($units=='') {
    $sheet->setCellValue('D3', '未指定');
} else {
    $unit_array = explode(",", $units);

    for ($i=0; $i<sizeof($unit_array); $i++) {
        $row = $i+3;
        $id = $unit_array[$i];
        $info = $userdb->loadUnit("$id");

        $sheet->setCellValue('D'.$row, $id . " ". $info[0]["UnitName"]);
    }
}

$sheet->setCellValue('E2', ($rank_select=='0')?'包含職級':'排除職級');
$rank_array = array();
if ($ranks=='') {
    $sheet->setCellValue('E3', '未指定');
} else {
    $rank_array = explode(",", $ranks);

    for ($i=0; $i<sizeof($rank_array); $i++) {
        $row = $i+3;
        $sheet->setCellValue('E'.$row, $rank_array[$i]);
    }   
}


$sheet->setCellValue('F2', '製表時間');
$sheet->setCellValue('F3', getTime());

    $sheet->getColumnDimension('B')->setWidth(20);
    $sheet->getColumnDimension('C')->setWidth(20);
    $sheet->getColumnDimension('D')->setWidth(40);
    $sheet->getColumnDimension('E')->setWidth(10);
    $sheet->getColumnDimension('F')->setWidth(20);

    $sheet->setCellValue('B1', '查詢條件');
    $sheet->getStyle('B1')->getFont()->setSize(20);
    $sheet->getStyle('B1')->getFont()->getColor()->setARGB('004DAAAB');
    $sheet->getRowDimension('1')->setRowHeight(40);

    $row_id = (sizeof($unit_array)>sizeof($book_array))?sizeof($unit_array):sizeof($book_array);
    $row_id += 3;
    $sheet->getPageSetup()->setPrintArea('A1:G'.$row_id);
    $sheet->getPageSetup()->setFitToWidth(1);
    $sheet->getPageSetup()->setFitToHeight(0);



$activeSheet=0;



    $sheet_name = '單位登入率';
    $title = '單位登入率';
/*

    $q = "select ag2.UnitInfo.UnitName as PUN, ag1.UnitInfo.UnitCode AS UC, ag1.UnitInfo.UnitName AS UN, count(distinct USER) as NUM, ag1.UnitInfo.Headcount as HEADCOUNT, round((count(distinct USER)*100.0/ag1.UnitInfo.Headcount), 2) as USAGE  from LOGIN inner join ag1.AgentInfo inner join ag2.UnitInfo inner join ag1.UnitInfo inner join ag1.UNITS where ag1.AgentInfo.UnitCode = ag1.UnitInfo.UnitCode and ag1.AgentInfo.AgentID=upper(USER) and ag1.UnitInfo.UnitCode= ag1.UNITS.CODE and ag1.UNITS.PARENT=ag2.UnitInfo.UnitCode ";




    if ($from!='')  { $q .= " AND (DATE>='$from') "; }
    if ($to!='')  { $q .= " AND (DATE<='$to') "; }
    if ($units!='') {
        $units1 = "'" . str_replace(",", "','", $units) . "'";
        $q .= " AND ag1.UnitInfo.UnitCode IN ($units1) ";
    }

    $q .= " GROUP by UC ";
    $q .= " ORDER by USAGE DESC ";
*/
$order_modifier = " ORDER BY USAGE DESC";
$time_constraint = "";
$unit_constraint = "";
$rank_constraint = ""; 
$rank_constraint1 = ""; 
if ($from!='')  {
    $time_constraint .= " AND (DATE>='$from') ";
}
if ($to!='')  {
    $time_constraint .= " AND (DATE<='$to') ";
}

if ($units!='') {
    $units = "'" . str_replace(",", "','", $units) . "'";
    $unit_constraint .= " AND ag1.UnitInfo.UnitCode IN ($units) ";
}
$ranks1 = "'" . str_replace(",", "','", $ranks) . "'";
if ($rank_select=='0') {
    if ($ranks!='') {
        $rank_constraint .= " AND ag1.AgentInfo.Rank IN ($ranks1) ";
        $rank_constraint1 .= " AND Rank IN ($ranks1) ";
    }
} else if ($ranks!='') {
    $rank_constraint .= " AND ag1.AgentInfo.Rank NOT IN ($ranks1) ";
    $rank_constraint1 .= " AND Rank NOT IN ($ranks1) ";
}

//$qs = " SELECT Ifnull(ag2.UnitInfo.UnitName, ag1.UNITS.PARENT) as PUN, UC, UN, NUM, RUN.HEADCOUNT, USAGE FROM (SELECT ag1.UnitInfo.UnitCode AS UC, ag1.UnitInfo.UnitName AS UN, count(LOG.USER) as NUM, ag1.UnitInfo.Headcount as HEADCOUNT, round((count(LOG.USER)*100.0/ag1.UnitInfo.Headcount), 2) as USAGE  FROM ag1.AgentInfo JOIN ag1.UnitInfo LEFT JOIN  (SELECT USER FROM LOGIN WHERE 1 $time_constraint GROUP by USER) as LOG ON ag1.AgentInfo.AgentID=LOG.USER WHERE ag1.AgentInfo.UnitCode = ag1.UnitInfo.UnitCode and ag1.AgentInfo.CurStatus<'90' $rank_constraint $unit_constraint GROUP by UC) as RUN LEFT join ag1.UNITS ON RUN.UC = ag1.UNITS.CODE LEFT join ag2.UnitInfo ON ag1.UNITS.PARENT=ag2.UnitInfo.UnitCode ORDER BY USAGE DESC";
//$qs = " SELECT Ifnull(ag2.UnitInfo.UnitName, ag1.UNITS.PARENT) as PUN, UC, UN, NUM, RUN.HEADCOUNT, USAGE FROM (SELECT ag1.UnitInfo.UnitCode AS UC, ag1.UnitInfo.UnitName AS UN, count(LOG.USER) as NUM, ag1.UnitInfo.Headcount_AG as HEADCOUNT, round((count(LOG.USER)*100.0/ag1.UnitInfo.Headcount_AG), 2) as USAGE  FROM ag1.AgentInfo JOIN ag1.UnitInfo LEFT JOIN  (SELECT USER FROM LOGIN WHERE 1 $time_constraint GROUP by USER) as LOG ON ag1.AgentInfo.AgentID=LOG.USER WHERE ag1.AgentInfo.UnitCode = ag1.UnitInfo.UnitCode and ag1.AgentInfo.CurStatus<'90' $rank_constraint $unit_constraint GROUP by UC) as RUN LEFT join ag1.UNITS ON RUN.UC = ag1.UNITS.CODE LEFT join ag2.UnitInfo ON ag1.UNITS.PARENT=ag2.UnitInfo.UnitCode ORDER BY USAGE DESC";
$qs =  "select RegionName as PUN, unitcode as UC, unitname as UN, ifnull(NUM,0) as NUM, Headcount_AG as HEADCOUNT, round((ifnull(NUM,0)*100.0/ifnull(Headcount_AG,1)), 2) as USAGE from (select * from ag1.unitinfo where 1 $unit_constraint) left join (select Unitcode uu, count(*)  NUM from  (SELECT distinct USER FROM     LOGIN where result=1 $time_constraint) as log join ag1.Agentinfo on log.user= agentinfo.AgentID WHERE curStatus<'90' $rank_constraint group by Unitcode) on uu    =unitcode  where headcount!=0 ";
$qs =  "select RegionName as PUN, unitcode as UC, unitname as UN, ifnull(NUM,0) as NUM, HC as HEADCOUNT, round((ifnull(NUM,0)*100.0/ifnull(HC ,1)), 2) as USAGE from (select * from ag1.unitinfo where 1 $unit_constraint) left join (select unitcode au, count(*) as HC from agentinfo where 1 $rank_constraint1 group by au) on unitcode = au left join (select Unitcode uu, count(*)  NUM from  (SELECT distinct USER FROM LOGIN where result=1 $time_constraint) as log join ag1.Agentinfo on log.user= agentinfo.AgentID WHERE curStatus<'90' $rank_constraint group by Unitcode) on uu=unitcode  where headcount!=0 ";

$command = $qs.$order_modifier.$range_modifier;
#$sth = $dbh->prepare($command);
#$sth->execute();
#$rows = $sth->fetchAll(PDO::FETCH_ASSOC);

#$command = $qs;
$sth = $dbh->prepare($command);
$sth->execute();
$result = $sth->fetchAll(PDO::FETCH_ASSOC);


    $myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, $sheet_name);
    $myWorkSheet->setCellValue('B1', $title);


    $sheet_data = array();
    $row = array('', '區部名稱' , '單位代號' , '單位名稱' , '單位閱讀人數' , '單位在職人數' , '單位登入率');
    array_push($sheet_data, $row);

    $row_id=3;
    for ($j=0; $j < count($result); $j++) {
        $row_id = $j+3;

        $row = array($j+1, $result[$j]["PUN"], $result[$j]["UC"], $result[$j]["UN"], $result[$j]["NUM"], $result[$j]["HEADCOUNT"], $result[$j]["NUM"]/$result[$j]["HEADCOUNT"]);
        array_push($sheet_data, $row);
    }


    $myWorkSheet->fromArray($sheet_data, NULL, 'A2');


    $myWorkSheet->getColumnDimension('B')->setWidth(40);
    $myWorkSheet->getColumnDimension('C')->setWidth(15);
    $myWorkSheet->getColumnDimension('D')->setWidth(40);
    $myWorkSheet->getColumnDimension('E')->setWidth(15);
    $myWorkSheet->getColumnDimension('F')->setWidth(10);
    $myWorkSheet->getColumnDimension('G')->setWidth(10);
    $myWorkSheet->setAutoFilter('B2:G'.$row_id);
    $myWorkSheet->getPageSetup()->setPrintArea('A1:H'.$row_id);
    $myWorkSheet->getPageSetup()->setFitToWidth(1);
    $myWorkSheet->getPageSetup()->setFitToHeight(0);
    $objPHPExcel->addSheet($myWorkSheet);
    $activeSheet++;
    $objPHPExcel->setActiveSheetIndex($activeSheet);


    $myWorkSheet->getStyle('G2:G'.$row_id)->getNumberFormat()->applyFromArray( 
        array( 
            'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00
        )
    );

    $myWorkSheet->getStyle('B2:G'.$row_id)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

    $myWorkSheet->getStyle('B2:G2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $myWorkSheet->getStyle('B2:G2')->getFill()->getStartColor()->setARGB('002D507A'); 
    $myWorkSheet->getStyle('B2:G2')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);

    $myWorkSheet->getStyle('B1')->getFont()->setSize(20);
    $myWorkSheet->getStyle('B1')->getFont()->getColor()->setARGB('004DAAAB');
    $myWorkSheet->getRowDimension('1')->setRowHeight(40);
    $myWorkSheet->getStyle('B1')->getAlignment()->setVertical("center");




$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);



PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
//$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);


header("Pragma: public");
header("Expires: 0");
header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
header("Content-Type:application/force-download");
header("Content-Type:application/octet-stream");
header("Content-Type:application/download");;
header("Content-Transfer-Encoding:binary");
header("Content-Type:application/vnd.ms-execl;charset=utf-8");
header('Content-Disposition:attachment;filename="report.xls"');
ob_get_clean();
$objWriter->save('php://output');
ob_end_flush();



mlog("單位登入率查詢", $USER_ID, "匯出", "", $result);


function error() {
    header("HTTP/1.0 404 Not Found"); 
    die();
}
?>
