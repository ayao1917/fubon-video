<?php
include_once('../../inc/config.php');
include_once('../../inc/global.php');
include_once('../../inc/utils.php');
include_once('../../inc/class_video.php');
include_once('../../inc/class_log.php');
include_once('../../inc/report_config.php');
include_once('../../inc/PHPExcel.php');
require_once('../../inc/PHPExcel/Writer/Excel2007.php');


$from = isset($_REQUEST['from'])?$_REQUEST['from']:'';
$to = isset($_REQUEST['to'])?$_REQUEST['to']:'';
$videos = isset($_REQUEST['videos'])?$_REQUEST['videos']:'';
$select = isset($_REQUEST['select'])?$_REQUEST['select']:'';


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

$videoDB = new Video();
$videoDB->init();

$video_array = explode(",", $videos);

$video_names = array();

if ($videos!='') {
    for ($i=0; $i<sizeof($video_array); $i++) {
        $id = $video_array[$i];
        $info = $videoDB->loadVideo($id);
        array_push($video_names, $info["TITLE"]);
    }
    $videonames = join("\n", $video_names);
}


$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('影片排行統計');

$objPHPExcel->getActiveSheet()->getStyle('B2:C2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('B2:C2')->getFill()->getStartColor()->setARGB('002D507A'); 
$objPHPExcel->getActiveSheet()->getStyle('B2:C2')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);

$objPHPExcel->getActiveSheet()->setCellValue('B2', '查詢條件');
$objPHPExcel->getActiveSheet()->setCellValue('C2', '內容');
$objPHPExcel->getActiveSheet()->setCellValue('B3', '起始日期');
$objPHPExcel->getActiveSheet()->setCellValue('C3', ($from=='')?'未指定':$from);
$objPHPExcel->getActiveSheet()->setCellValue('B4', '結束日期');
$objPHPExcel->getActiveSheet()->setCellValue('C4', ($to=='')?'未指定':$to);

$objPHPExcel->getActiveSheet()->setCellValue('B5', '影片');
$objPHPExcel->getActiveSheet()->getStyle('B5')->getAlignment()->setVertical("center");
$objPHPExcel->getActiveSheet()->setCellValue('C5', ($books=='')?'未指定':$booknames);
$objPHPExcel->getActiveSheet()->getStyle('C5')->getAlignment()->setWrapText(true);

$objPHPExcel->getActiveSheet()->setCellValue('B6', '統計對象');
$objPHPExcel->getActiveSheet()->setCellValue('C6', ($select==0)?'觀看人數':'觀看人次');

$objPHPExcel->getActiveSheet()->setCellValue('B7', '製表時間');
$objPHPExcel->getActiveSheet()->setCellValue('C7', getTime());

    $objPHPExcel->getActiveSheet()->getStyle('B2:C7')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);


    $objPHPExcel->getActiveSheet()->setCellValue('B1', '影片排行統計');
    $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(20);
    $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->getColor()->setARGB('004DAAAB');
    $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
    $objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setVertical('center');


    $objPHPExcel->getActiveSheet()->setCellValue('B10', '影片名稱');
    $objPHPExcel->getActiveSheet()->setCellValue('C10', '發佈日期');
    $objPHPExcel->getActiveSheet()->setCellValue('D10', ($select==0)?'觀看人數':'觀看人次');


    $condition= '';

    if ($from!='')  {
        $condition .= " AND (DATE>='$from') ";
    }

    if ($to!='')  {
        $condition .= " AND (DATE<='$to') ";
    }

    if ($videos!='') {
        $condition .= " AND VIDEO IN ($videos) ";
    }

    $wanted = ($select==0)? "count(distinct upper(USER)) as USER_COUNT":"count(upper(USER)) as USER_COUNT";
    $qs = "select view.video as SERIAL_NUMBER, VIDEOINFO.publish_date AS PUBLISH_DATE, VIDEOINFO.title AS NAME, $wanted from view join m.video as VIDEOINFO on view.video=VIDEOINFO.serial_number where 1 $condition group by view.video order by USER_COUNT desc";


    $result = $logdb->search($qs);

    for ($j=0; $j < count($result); $j++) {
        $row_id = $j+11;
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$row_id, $j+1);
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$row_id, $result[$j]["NAME"]);
        $objPHPExcel->getActiveSheet()->setCellValue('C'.$row_id, $result[$j]["PUBLISH_DATE"]);
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$row_id, $result[$j]["USER_COUNT"]);
    }
    $objPHPExcel->getActiveSheet()->setCellValue('B1'.$row_id, "影片排行統計");

    $objPHPExcel->getActiveSheet()->getStyle('B10:D'.$row_id)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

    $objPHPExcel->getActiveSheet()->getStyle('B10:D10')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $objPHPExcel->getActiveSheet()->getStyle('B10:D10')->getFill()->getStartColor()->setARGB('002D507A'); 
    $objPHPExcel->getActiveSheet()->getStyle('B10:D10')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);

    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
    $objPHPExcel->getActiveSheet()->setAutoFilter('B10:D'.$row_id);
    $objPHPExcel->getActiveSheet()->getPageSetup()->setPrintArea('A1:E'.$row_id);
    $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
    $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);

    $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(20);
    $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->getColor()->setARGB('004DAAAB');
    $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
    $objPHPExcel->getActiveSheet()->setCellValue('A1', '');
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB('004DAAAB');



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
//mlog("閱讀統計", $USER_ID, "匯出", "", $result);


function error() {
    header("HTTP/1.0 404 Not Found"); 
    die();
}
?>
