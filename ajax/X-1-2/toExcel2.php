<?php
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
$videos = isset($_REQUEST['videos'])?$_REQUEST['videos']:'';
$select = isset($_REQUEST['select'])?$_REQUEST['select']:'';
$units = isset($_REQUEST['units'])?$_REQUEST['units']:'';
$ranks = isset($_REQUEST['ranks'])?$_REQUEST['ranks']:'';
$rank_select = isset($_REQUEST['rank_select'])?$_REQUEST['rank_select']:'';


$objPHPExcel = new PHPExcel();

$objPHPExcel->getProperties()->setCreator("Moker");
$objPHPExcel->getProperties()->setLastModifiedBy("Moker");
$objPHPExcel->getProperties()->setTitle("");
$objPHPExcel->getProperties()->setSubject("");
$objPHPExcel->getProperties()->setDescription("");
$objPHPExcel->getProperties()->setKeywords("");
$objPHPExcel->getProperties()->setCategory("");


$logdb = new Logs();
$logdb->init();

$videodb = new Video();
$videodb->init();

$video_array = explode(",", $videos);

$video_names = array();

if ($videos!='') {
    for ($i=0; $i<sizeof($video_array); $i++) {
        $id = $video_array[$i];
        $info = $videodb->loadVideo($id);
        array_push($video_names, $info["TITLE"]);
    }
    $videonames = join("\n", $video_names);
}

$unitnames = str_replace(",", "\n",  $units);

$activeSheet=0;
$sheet_name='查詢條件';
$myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, $sheet_name);

$objPHPExcel->addSheet($myWorkSheet);

$objPHPExcel->setActiveSheetIndex($activeSheet);

$myWorkSheet->setCellValue('B2', '查詢條件');
$myWorkSheet->setCellValue('C2', '內容');
$myWorkSheet->setCellValue('B3', '起始日期');
$myWorkSheet->setCellValue('C3', ($from=='')?'未指定':$from);
$myWorkSheet->setCellValue('B4', '結束日期');
$myWorkSheet->setCellValue('C4', ($to=='')?'未指定':$to);

$myWorkSheet->setCellValue('B5', '書籍');
$myWorkSheet->getStyle('B5')->getAlignment()->setVertical("center");
$myWorkSheet->setCellValue('C5', ($videos=='')?'未指定':$videonames);
$myWorkSheet->getStyle('C5')->getAlignment()->setWrapText(true);

$myWorkSheet->setCellValue('B6', '單位');
$myWorkSheet->getStyle('B6')->getAlignment()->setVertical("center");
$myWorkSheet->setCellValue('C6', ($units=='')?'未指定':$unitnames);
$myWorkSheet->getStyle('C6')->getAlignment()->setWrapText(true);


$myWorkSheet->setCellValue('B7', '職級搜尋類型');
$myWorkSheet->setCellValue('C7', ($rank_select==0)?'符合職級':'排除職級');

$myWorkSheet->setCellValue('B8', '職級');
$myWorkSheet->setCellValue('C8', ($ranks=='')?'未指定':$ranks);

$myWorkSheet->setCellValue('B9', '統計對象');
$myWorkSheet->setCellValue('C9', ($select==0)?'閱讀人數':'閱讀人次');

$myWorkSheet->setCellValue('B10', '製表時間');
$myWorkSheet->setCellValue('C10', getTime());

    $myWorkSheet->getStyle('B2:C10')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

    $myWorkSheet->setCellValue('B1', '閱讀記錄統計');
    $myWorkSheet->getStyle('B1')->getFont()->setSize(20);
    $myWorkSheet->getStyle('B1')->getFont()->getColor()->setARGB('004DAAAB');
    $myWorkSheet->getRowDimension('1')->setRowHeight(40);
    $myWorkSheet->getStyle('B1')->getAlignment()->setVertical('center');
    $myWorkSheet->getColumnDimension('B')->setWidth(50);
    $myWorkSheet->getColumnDimension('C')->setWidth(50);

$myWorkSheet->getStyle('B2:C2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$myWorkSheet->getStyle('B2:C2')->getFill()->getStartColor()->setARGB('002D507A'); 
$myWorkSheet->getStyle('B2:C2')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);


//    $objPHPExcel->addSheet($myWorkSheet);



//-------------
    $time_constraint='';
    $video_constraint='';
    $unit_constraint='';
    $rank_constraint='';


    if ($from!='')  {
        $time_constraint .= " AND (DATE>='$from') ";
    }

    if ($to!='')  {
        $time_constraint .= " AND (DATE<='$to') ";
    }

    if ($videos!='') {
        $video_constraint .= " AND VIDEO IN ($videos) ";
    }
    if ($units!='') {
        $units = "'" . str_replace(",", "','", $units) . "'";
        $unit_constraint = "  AND ag1.AgentInfo.UnitCode IN ($units) ";
    }

    $ranks1 = "'" . str_replace(",", "','", $ranks) . "'";
    if ($rank_select=='0') {
        if ($ranks!='') {
            $rank_constraint .= " AND ag1.AgentInfo.Rank IN ($ranks1) ";
        }
    } else if ($ranks!='') {
        $rank_constraint .= " AND ag1.AgentInfo.Rank NOT IN ($ranks1) ";
    }


    $wanted = ($select==0)? "count(distinct USER) as USER_COUNT":"count(USER) as USER_COUNT";
    $qs = "select $wanted, VIDEO AS SERIAL_NUMBER, m.VIDEO.TITLE as NAME from (select upper(USER) as USER, VIDEO FROM view WHERE 1 $time_constraint $video_constraint) join (SELECT AgentID FROM ag1.AgentInfo WHERE 1 $unit_constraint $rank_constraint) as AG on AG.AgentID=USER join m.VIDEO on m.VIDEO.SERIAL_NUMBER=VIDEO GROUP BY SERIAL_NUMBER ORDER BY USER_COUNT DESC ";
 $qs = "select $wanted, VIDEO AS SERIAL_NUMBER,ifnull(m.VIDEO.TITLE, VIDEO) as NAME from (select upper(USER) as USER, VIDEO FROM view WHERE 1 $time_constraint $video_constraint) join (SELECT AgentID FROM ag1.AgentInfo WHERE 1 $unit_constraint $rank_constraint) as AG on AG.AgentID=USER left join m.VIDEO on m.VIDEO.SERIAL_NUMBER=VIDEO GROUP BY SERIAL_NUMBER ORDER BY USER_COUNT DESC ";

    $result = $logdb->search($qs);



    $sheet_name='閱讀記錄統計';
    $myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, $sheet_name);
    $objPHPExcel->addSheet($myWorkSheet);
    $activeSheet++;

    $objPHPExcel->setActiveSheetIndex($activeSheet);

    $myWorkSheet->setCellValue('B1', $sheet_name);

    $myWorkSheet->setCellValue('B2', '書名');
    $myWorkSheet->setCellValue('C2', ($select==0)?'閱讀人數':'閱讀人次');

    for ($j=0; $j < count($result); $j++) {
        $row_id = $j+3;
        $myWorkSheet->setCellValue('A'.$row_id, $j+1);
        $myWorkSheet->setCellValue('B'.$row_id, $result[$j]["NAME"]);
        $myWorkSheet->setCellValue('C'.$row_id, $result[$j]["USER_COUNT"]);
    }
    $myWorkSheet->setCellValue('B1'.$row_id, "閱讀記錄統計");

    $myWorkSheet->getStyle('B2:C'.$row_id)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

    $myWorkSheet->getStyle('B2:C2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $myWorkSheet->getStyle('B2:C2')->getFill()->getStartColor()->setARGB('002D507A'); 
    $myWorkSheet->getStyle('B2:C2')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);

    $myWorkSheet->getColumnDimension('B')->setWidth(50);
    $myWorkSheet->getColumnDimension('C')->setWidth(20);
    $myWorkSheet->setAutoFilter('B2:C'.$row_id);
    $myWorkSheet->getPageSetup()->setPrintArea('A1:D'.$row_id);
    $myWorkSheet->getPageSetup()->setFitToWidth(1);
    $myWorkSheet->getPageSetup()->setFitToHeight(0);

    $myWorkSheet->getStyle('B1')->getFont()->setSize(20);
    $myWorkSheet->getStyle('B1')->getFont()->getColor()->setARGB('004DAAAB');
    $myWorkSheet->getRowDimension('1')->setRowHeight(40);
    $myWorkSheet->setCellValue('A1', '');
    $myWorkSheet->getStyle('A1')->getFont()->setSize(20);
    $myWorkSheet->getStyle('A1')->getFont()->getColor()->setARGB('004DAAAB');



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
header('Content-Disposition:attachment;filename="stat.xls"');
ob_get_clean();
$objWriter->save('php://output');
ob_end_flush();
mlog("觀看統計", $USER_ID, "匯出", "", $result);


function error() {
    header("HTTP/1.0 404 Not Found"); 
    die();
}
?>
