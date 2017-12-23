<?php
set_time_limit(0);
//error_reporting(0);
include_once('../../inc/config.php');
include_once('../../inc/global.php');
include_once('../../inc/utils.php');
include_once('../../inc/class_log.php');
include_once('../../inc/class_video.php');
include_once('../../inc/class_user.php');
include_once('../../inc/PHPExcel.php');
require_once('../../inc/PHPExcel/Writer/Excel2007.php');


$from = isset($_REQUEST['from'])?$_REQUEST['from']:'';
$to = isset($_REQUEST['to'])?$_REQUEST['to']:'';
$video = isset($_REQUEST['video'])?$_REQUEST['video']:'';
$videos = isset($_REQUEST['videos'])?$_REQUEST['videos']:'';
$units = isset($_REQUEST['units'])?$_REQUEST['units']:'';
$ranks = isset($_REQUEST['ranks'])?$_REQUEST['ranks']:'';
$rank_select = isset($_REQUEST['rank_select'])?$_REQUEST['rank_select']:'';

$logdb = new Logs();
$logdb->init();

$videodb = new Video();
$videodb->init();

$userdb = new User();
$userdb->init();

///////////////////////////////////////////////////////////////////////////////////

$objPHPExcel = new PHPExcel();

$objPHPExcel->getProperties()->setCreator("Moker");
$objPHPExcel->getProperties()->setLastModifiedBy("Moker");
//$objPHPExcel->getProperties()->setVersion(8);
$objPHPExcel->getProperties()->setTitle("");
$objPHPExcel->getProperties()->setSubject("");
$objPHPExcel->getProperties()->setDescription("");
$objPHPExcel->getProperties()->setKeywords("");
$objPHPExcel->getProperties()->setCategory("");

/*
$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp; 
$cacheSettings = array( 'memoryCacheSize' => '32MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);	
*/

$objPHPExcel->setActiveSheetIndex(0);

$sheet = $objPHPExcel->getActiveSheet();

$sheet->setTitle('查詢條件');

$sheet->setCellValue('B2', '起始日期');
$sheet->setCellValue('B3', ($from=='')?'未指定':$from);
$sheet->setCellValue('C2', '結束日期');
$sheet->setCellValue('C3', ($to=='')?'未指定':$to);
$sheet->setCellValue('D2', '書籍');

$video_array=array();

if (($video!= '')&& ($video!="0")) {

    $info = $videodb->loadVideo($video);
    $sheet->setCellValue('D3', $video ." " .$info["TITLE"] );

} else if ($videos=='') {
    $sheet->setCellValue('D3', '未指定');
} else {
    $video_array = explode(",", $videos);

    for ($i=0; $i<sizeof($video_array); $i++) {
        $row = $i+3;
        $id = $video_array[$i];
        $info = $videodb->loadVideo($id);
        $sheet->setCellValue('D'.$row, $id . " ". $info["TITLE"]);
    }
}

$sheet->setCellValue('E2', '單位');
$unit_array=array();
if ($units=='') {
    $sheet->setCellValue('E3', '未指定');
} else {
    $unit_array = explode(",", $units);

    for ($i=0; $i<sizeof($unit_array); $i++) {
        $row = $i+3;
        $id = $unit_array[$i];
        $info = $userdb->loadUnit("$id");

        if (count($info)>0) $sheet->setCellValue('E'.$row, $id . " ". $info[0]["UnitName"]);
    }
}

$sheet->setCellValue('F2', ($rank_select=='0')?'包含職級':'排除職級');
$rank_array = array();
if ($ranks=='') {
    $sheet->setCellValue('F3', '未指定');
} else {
    $rank_array = explode(",", $ranks);

    for ($i=0; $i<sizeof($rank_array); $i++) {
        $row = $i+3;
        $sheet->setCellValue('F'.$row, $rank_array[$i]);
    }
}

$sheet->setCellValue('G2', '製表時間');
$sheet->setCellValue('G3', getTime());

$sheet->getColumnDimension('B')->setWidth(20);
$sheet->getColumnDimension('C')->setWidth(20);
$sheet->getColumnDimension('D')->setWidth(40);
$sheet->getColumnDimension('E')->setWidth(30);
$sheet->getColumnDimension('F')->setWidth(10);
$sheet->getColumnDimension('G')->setWidth(20);

$sheet->getStyle('B2:G2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$sheet->getStyle('B2:G2')->getFill()->getStartColor()->setARGB('002D507A'); 
$sheet->getStyle('B2:G2')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
 //   $sheet->getColumnDimension('B:G')->setAutoSize(true);

$sheet->setCellValue('B1', '查詢條件');
$sheet->getStyle('B1')->getFont()->setSize(20);
$sheet->getStyle('B1')->getFont()->getColor()->setARGB('004DAAAB');
$sheet->getRowDimension('1')->setRowHeight(40);

$row_id = max(sizeof($unit_array), sizeof($video_array), sizeof($rank_array));
$row_id += 3;
$sheet->getPageSetup()->setPrintArea('A1:H'.$row_id);
$sheet->getPageSetup()->setFitToWidth(1);
$sheet->getPageSetup()->setFitToHeight(0);

///////////////////////////////////////////////////////////////////////////////////
$select = 1;
$time_constraint = '';
$unit_constraint = '';
$video_constraint= "";
$rank_constraint = '';

if ($from!='')  {
    $time_constraint .= " AND (DATE>='$from') ";
}
if ($to!='')  {
    $time_constraint .= " AND (DATE<='$to') ";
}

if ($units!='') {
    $units = "'" . str_replace(",", "','", $units) . "'";
    $unit_constraint .= " AND UC IN ($units) ";
}
if ($videos!='') {
    $b = "'" . str_replace(",", "','", $videos) . "'";
    $video_constraint .= " AND VIDEO IN ($b) ";
}

$ranks1 = "'" . str_replace(",", "','", $ranks) . "'";
if ($rank_select=='0') {
    if ($ranks!='') {
        $rank_constraint = " AND RANK IN ($ranks1) ";
    }
} else if ($ranks!='') {
    $rank_constraint = " AND RANK NOT IN ($ranks1) ";
}


//$order_modifier = " ORDER BY USAGE DESC";
$order_modifier = "";

$qs = "select VIDEO from VIEW where 1 $time_constraint $video_constraint GROUP BY VIDEO";

$rows = array();

$activeSheet=0;
$sheet_name_array = array('');


$start = -1;
if ($video=='') {   // run all reports
    $rows = $logdb->search($qs);
} else if ($video!='0') {
    $data['VIDEO'] = $video;
    array_push($rows, $data);
    $start = 0;
} 

for ($i=$start; $i < count($rows); $i++) {

    if ($i>=0) {
        $id = $rows[$i]['VIDEO'];

        $currentVideo = $videodb->loadVideo($id);
        $title = $id . " " . $currentVideo["TITLE"];

        $title =  $currentVideo["TITLE"];
        //$myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, utf8_substr($title, 0, 28));

        $sheet_name = (strlen($title)>52)?$id:$title;
        if (strlen($sheet_name)==0) $sheet_name = $id;

        $title = $id . " " . $currentVideo["TITLE"];

        $q = "select PUN, UC, UN, count(*) as HEADCOUNT, SUM(CASE WHEN U IS NULL THEN 0 ELSE 1 END) AS NUM, round(SUM(CASE WHEN U IS NULL THEN 0 ELSE 1 END)*1.0/count(*), 4) AS USAGE FROM (select PARENT.UnitName AS PUN, AG.UnitCode AS UC, AG.Rank as RANK, ag1.UnitInfo.UnitName AS UN, AgentID, LOG.USER as U from (select * FROM ag1.AgentInfo WHERE CurStatus<90) as AG join ag1.UnitInfo on AG.UnitCode = ag1.UnitInfo.UnitCode join ag1.UnitInfo as PARENT on ag1.UnitInfo.RegionCode=PARENT.UnitCode left join (select USER , count(*) as C from VIEW  WHERE 1 AND VIDEO='$id' $time_constraint GROUP BY USER) as LOG ON AG.AgentId =  LOG.USER) WHERE 1 $unit_constraint $rank_constraint GROUP BY UC ORDER BY USAGE DESC" ;
    } else {

        $sheet_name = '全部';
        $title = '單位觀看率(不分書別)';

        $q = "select PUN, UC, UN, count(*) as HEADCOUNT, SUM(CASE WHEN U IS NULL THEN 0 ELSE 1 END) AS NUM, round(SUM(CASE WHEN U IS NULL THEN 0 ELSE 1 END)*1.0/count(*), 4) AS USAGE FROM (select PARENT.UnitName AS PUN, AG.UnitCode AS UC, AG.Rank as RANK, ag1.UnitInfo.UnitName AS UN, AgentID, LOG.USER as U from (select * FROM ag1.AgentInfo WHERE CurStatus<90) as AG join ag1.UnitInfo on AG.UnitCode = ag1.UnitInfo.UnitCode join ag1.UnitInfo as PARENT on ag1.UnitInfo.RegionCode=PARENT.UnitCode left join (select USER , count(*) as C from VIEW  WHERE 1 $time_constraint GROUP BY USER) as LOG ON AG.AgentId =  LOG.USER) WHERE 1 $unit_constraint $rank_constraint GROUP BY UC ORDER BY USAGE DESC" ;


    }



/*
    if ($from!='')  { $q .= " AND (DATE>='$from') "; }
    if ($to!='')  { $q .= " AND (DATE<='$to') "; }
    if ($units!='') {
        $units1 = "'" . str_replace(",", "','", $units) . "'";
        $q .= " AND ag1.UnitInfo.UnitCode IN ($units1) ";
    }

    $q .= " GROUP by UC ";
    $q .= " ORDER by USAGE DESC ";
*/


    while (in_array($sheet_name, $sheet_name_array)) {
        $sheet_name .= "A";
    }
    array_push($sheet_name_array, $sheet_name);

    $myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, $sheet_name);


    $myWorkSheet->setCellValue('B1', $title);

file_put_contents("/tmp/aaa_excel.txt", $q);
    $result = $logdb->search($q);

    $sheet_data = array();
    $row = array('', '區部名稱' , '單位代號' , '單位名稱' , '單位觀看人數' , '單位在職人數' , '單位觀看率');
    array_push($sheet_data, $row);

    $row_id=3;
    for ($j=0; $j < count($result); $j++) {
        $row_id = $j+3;

/*
        if ($result[$j]["HEADCOUNT"]=='') $result[$j]["HEADCOUNT"]=0;
        if ($result[$j]["NUM"]=='') $result[$j]["NUM"]=0;

        $c = ($result[$j]["HEADCOUNT"]==0)?1:$result[$j]["HEADCOUNT"];
        $p = ($result[$j]["NUM"]==0)?0:$result[$j]["NUM"]/$c;

        $row = array($j+1, $result[$j]["PUN"], $result[$j]["UC"], $result[$j]["UN"], $result[$j]["NUM"], $result[$j]["HEADCOUNT"], $p);
*/
        $row = array($j+1, $result[$j]["PUN"], $result[$j]["UC"], $result[$j]["UN"], $result[$j]["NUM"], $result[$j]["HEADCOUNT"], $result[$j]["USAGE"]);
        array_push($sheet_data, $row);
    }


    $myWorkSheet->fromArray($sheet_data, NULL, 'A2');

    $myWorkSheet->getColumnDimension('B')->setWidth(40);
    $myWorkSheet->getColumnDimension('C')->setWidth(15);
    $myWorkSheet->getColumnDimension('D')->setWidth(40);
    $myWorkSheet->getColumnDimension('E')->setWidth(10);
    $myWorkSheet->getColumnDimension('F')->setWidth(10);
    $myWorkSheet->getColumnDimension('G')->setWidth(10);
    $myWorkSheet->setAutoFilter('B2:G'.$row_id);
    $myWorkSheet->getPageSetup()->setPrintArea('A1:M'.$row_id);
    $myWorkSheet->getPageSetup()->setFitToWidth(1);
    $myWorkSheet->getPageSetup()->setFitToHeight(0);


    $objPHPExcel->addSheet($myWorkSheet);

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

    $activeSheet++;
    $objPHPExcel->setActiveSheetIndex($activeSheet);

    unset($myWorkSheet);
    unset($sheet_data);


}

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);


PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
//$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
$objWriter->setPreCalculateFormulas(false);


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



mlog("單位觀看率查詢", $USER_ID, "匯出", "", $result);


function error() {
    header("HTTP/1.0 404 Not Found"); 
    die();
}
?>
