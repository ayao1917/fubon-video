<?php
include_once('../../inc/config.php');
include_once('../../inc/global.php');
include_once('../../inc/utils.php');
include_once('../../inc/class_video.php');
include_once('../../inc/class_user.php');
include_once('../../inc/class_log.php');
include_once('../../inc/report_config.php');
include_once('../../inc/PHPExcel.php');
require_once('../../inc/PHPExcel/Writer/Excel2007.php');


$from = isset($_REQUEST['from'])?$_REQUEST['from']:'';
$to = isset($_REQUEST['to'])?$_REQUEST['to']:'';
$videos = isset($_REQUEST['videos'])?$_REQUEST['videos']:'';
// $units = isset($_REQUEST['units'])?$_REQUEST['units']:'';
//$select = isset($_REQUEST['select'])?$_REQUEST['select']:'';
$uid = isset($_REQUEST['uid'])?$_REQUEST['uid']:'';


$objPHPExcel = new PHPExcel();

$objPHPExcel->getProperties()->setCreator("Moker");
$objPHPExcel->getProperties()->setLastModifiedBy("Moker");
$objPHPExcel->getProperties()->setTitle("");
$objPHPExcel->getProperties()->setSubject("");
$objPHPExcel->getProperties()->setDescription("");
$objPHPExcel->getProperties()->setKeywords("");
$objPHPExcel->getProperties()->setCategory("");

$videoDB = new Video();
$videoDB->init();

$userdb = new User();
$userdb->init();

$logdb = new Logs();
$logdb->init();

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

$userinfo = $userdb->loadAgent("$uid");
$unitinfo = $userdb->loadUnit($userinfo[0]["UnitCode"]);

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('個人影片記錄查詢結果');

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
$objPHPExcel->getActiveSheet()->setCellValue('C5', ($videos=='')?'未指定':$videonames);
$objPHPExcel->getActiveSheet()->getStyle('C5')->getAlignment()->setWrapText(true);

$objPHPExcel->getActiveSheet()->setCellValue('B6', '單位名稱');
$objPHPExcel->getActiveSheet()->setCellValue('C6', $unitinfo[0]["UnitName"]);

$objPHPExcel->getActiveSheet()->setCellValue('B7', '單位代號');
$objPHPExcel->getActiveSheet()->setCellValue('C7', $unitinfo[0]["UnitCode"]);

$objPHPExcel->getActiveSheet()->setCellValue('B8', '業務員');
$objPHPExcel->getActiveSheet()->setCellValue('C8', $uid);

$objPHPExcel->getActiveSheet()->setCellValue('B9', '業務員姓名');
$objPHPExcel->getActiveSheet()->setCellValue('C9', $userinfo[0]["AgentName"]);

$objPHPExcel->getActiveSheet()->setCellValue('B10', '職稱');
$objPHPExcel->getActiveSheet()->setCellValue('C10', $userinfo[0]["Rank"]);

$code = $userinfo[0]["CurStatus"];
$objPHPExcel->getActiveSheet()->setCellValue('B11', '在職狀態');
$objPHPExcel->getActiveSheet()->setCellValue('C11', ($code<90)?'在職':$code);

$objPHPExcel->getActiveSheet()->setCellValue('B12', '製表時間');
$objPHPExcel->getActiveSheet()->setCellValue('C12', getTime());

    $objPHPExcel->getActiveSheet()->getStyle('B2:C12')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);


    $objPHPExcel->getActiveSheet()->setCellValue('B1', '個人影片觀看記錄查詢結果');
    $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(20);
    $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->getColor()->setARGB('004DAAAB');
    $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
    $objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setVertical('center');

    $objPHPExcel->getActiveSheet()->setCellValue('B15', '影片名稱');
    $objPHPExcel->getActiveSheet()->setCellValue('C15', '最後觀看日期');
    $objPHPExcel->getActiveSheet()->setCellValue('D15', '次數');


    $qs = "select DATE, TIME, upper(USER) as USER, VIDEO, count(*) as NUM from view WHERE 1 ";

    $qs .= ($from == '')? '' : " AND (DATE>='$from') ";
    $qs .= ($to == '')? '' : " AND (DATE<='$to') ";
    $qs .= ($videos =='') ? '' : " AND ViDEO IN ($videos) ";
    $qs .= ($uid == '') ? '' : " AND ( upper(USER) = '" .$uid . "') ";

    $qs .= "GROUP BY VIDEO";
    $result = $logdb->search($qs);
    for ($i=0; $i< count($result); $i++){

        $k = $videoDB->loadVideo(intval($result[$i]['VIDEO']));
        $result[$i]['TITLE'] = addslashes($k['TITLE']);
    }


    $row_id = 15;

    for ($j=0; $j < count($result); $j++) {
        $row_id = $j+16;
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$row_id, $j+1);
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$row_id, $result[$j]["TITLE"]);
        $objPHPExcel->getActiveSheet()->setCellValue('C'.$row_id, $result[$j]["DATE"]);
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$row_id, $result[$j]["NUM"]);
    }
    $objPHPExcel->getActiveSheet()->setCellValue('B1', "觀看記錄查詢結果");

    $objPHPExcel->getActiveSheet()->getStyle('B15:D'.$row_id)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

    $objPHPExcel->getActiveSheet()->getStyle('B15:D15')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $objPHPExcel->getActiveSheet()->getStyle('B15:D15')->getFill()->getStartColor()->setARGB('002D507A'); 
    $objPHPExcel->getActiveSheet()->getStyle('B15:D15')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);

    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
    $objPHPExcel->getActiveSheet()->setAutoFilter('B15:D'.$row_id);
    $objPHPExcel->getActiveSheet()->getPageSetup()->setPrintArea('A1:E'.$row_id);
    $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
    $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);

    $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(20);
    $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->getColor()->setARGB('004DAAAB');
    $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
    $objPHPExcel->getActiveSheet()->setCellValue('A1', '');



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
mlog("觀看記錄查詢", $USER_ID, "匯出", "", $result);


function error() {
    header("HTTP/1.0 404 Not Found"); 
    die();
}
?>
