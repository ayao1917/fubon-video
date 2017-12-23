<?php
include_once('../../inc/config.php');
include_once('../../inc/global.php');
include_once('../../inc/utils.php');
include_once('../../inc/class_log.php');
include_once('../../inc/PHPExcel.php');
require_once('../../inc/PHPExcel/Writer/Excel2007.php');

$logdb = new Logs();
$logdb->init();

$from = isset($_REQUEST['from'])?$_REQUEST['from']:'';
$to = isset($_REQUEST['to'])?$_REQUEST['to']:'';
$select = isset($_REQUEST['select'])?$_REQUEST['select']:'';

$objPHPExcel = new PHPExcel();

$objPHPExcel->getProperties()->setCreator("Moker");
$objPHPExcel->getProperties()->setLastModifiedBy("Moker");
$objPHPExcel->getProperties()->setTitle("");
$objPHPExcel->getProperties()->setSubject("");
$objPHPExcel->getProperties()->setDescription("");
$objPHPExcel->getProperties()->setKeywords("");
$objPHPExcel->getProperties()->setCategory("");


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

$myWorkSheet->setCellValue('B5', '製表時間');
$myWorkSheet->setCellValue('C5', getTime());

    $myWorkSheet->getStyle('B2:C5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

    $myWorkSheet->setCellValue('B1', '記錄趨勢');
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
    $book_constraint='';
    $unit_constraint='';
    $rank_constraint='';


    if ($from!='')  {
        $time_constraint .= " AND (DATE>='$from') ";
    }

    if ($to!='')  {
        $time_constraint .= " AND (DATE<='$to') ";
    }

switch ($select) {
    case '0': $query = "select date as key, sum(1) as value from login where 1"; break;
    case '1': $query = "select date as key, count(distinct upper(user)) as value from login where 1"; break;
    case '2': $query = "select date as key, sum(1) as value from view"; break;
    case '3': $query = "select date as key, count(distinct upper(user)) as value from view"; break;
    case '4': $query = "select date as key, count(distinct upper(book||user)) as value from view"; break;
    case '5': $query = "select date as key, count(USER) as value FROM (select DATE, UPPER(USER) AS USER from LOGIN GROUP BY USER ORDER BY DATE) WHERE 1"; break;
    case '6': $query = "select date as key, count(USER) as value FROM (select substr(DATE,0,7) AS DATE, UPPER(USER) AS USER from LOGIN GROUP BY USER ORDER BY DATE) WHERE 1"; break;
} 

$query .= $time_constraint ." GROUP BY DATE";


    $result = $logdb->search($query);

    switch ($select) {
        case '0': $sheet_name='登入人次'; break;
        case '1': $sheet_name='登入人數'; break;
        case '2': $sheet_name='觀看人次'; break;
        case '3': $sheet_name='觀看人數'; break;
        case '5': $sheet_name='首次登入人數(日統計)'; break;
        case '5': $sheet_name='首次登入人數(月統計)'; break;
    }
    $myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, $sheet_name);
    $objPHPExcel->addSheet($myWorkSheet);
    $activeSheet++;

    $objPHPExcel->setActiveSheetIndex($activeSheet);

    $myWorkSheet->setCellValue('B1', $sheet_name);

    $myWorkSheet->setCellValue('B2', '日期');
    $myWorkSheet->setCellValue('C2', '次數');

    for ($j=0; $j < count($result); $j++) {
        $row_id = $j+3;
        $myWorkSheet->setCellValue('A'.$row_id, $j+1);
        $myWorkSheet->setCellValue('B'.$row_id, $result[$j]["key"]);
        $myWorkSheet->setCellValue('C'.$row_id, $result[$j]["value"]);
    }
    $myWorkSheet->setCellValue('B1'.$row_id, $sheet_name);

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
mlog("基本報表", $USER_ID, "匯出", "", $result);


function error() {
    header("HTTP/1.0 404 Not Found"); 
    die();
}
?>
