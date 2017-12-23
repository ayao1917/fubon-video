<?php

include_once('inc/global.php');

include('inc/class_udb.php');


$v =  new udb();
$v->init();

$all_rank = array("AG ", "AI ", "CA ", "LA ", "STF", "NCT", "ST ", "SP ", "SI ", "CTT", "CI ", "IN ", "UM ", "AM ", "AS ", "CAO", "SS ", "CS ", "PT ", "SAS", "CT2", "CT1", "TA ", "CI1", "CSS", "MS ", "DM ", "BRC", "CTB", "SR ", "AG1", "RPC", "CT3", "CA2", "CI2", "MM ", "LB2", "CSM", "XC ", "MAM", "ACM", "SA ", "BRK", "IS ", "NS ", "CFM", "FM", "CUM", "SUM", "CTA", "LB3", "BA ", "CM ", "VRM", "AMS", "CBM", "PMS", "BRT", "MPT", "AMG", "RT ", "SFM", "ASM", "SMG", "CTU", "JMS", "CMG", "CRM", "CT4", "PFM", "PBS", "SPM", "STM", "CDM", "SCM", "AAS", "BAM", "CT6", "HOB", "SAM", "SM ", "SMM", "ASP", "DSP", "CCM", "CL ", "CT5", "DMA", "PSP", "ACA", "ARM", "BUD", "CAM", "CM1", "CM2", "CM3", "CT7", "SRA", "AAM", "ADM", "CP ", "CT ", "CTM", "CTS", "CVM", "DVM", "LB1", "MT ", "SCT", "VM ");


$rank_options = '';
foreach ($all_rank as $rank) {
    $rank_options .= "<option id='rank_$rank' value='$rank'>$rank</option>";
}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>X-1</title> 	
		<link rel="stylesheet" href="css/global.css"> 

		<link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.10.3.custom.css">

		<link rel="stylesheet" href="css/report.css"> 
                <link rel="stylesheet" href="css/jquery.multiselect.css">
                <link rel="stylesheet" href="css/jquery.multiselect.filter.css">

		<script type="text/javascript" src="js/jquery.min.js"></script> 
		<script type="text/javascript" src="js/jquery.form.js"></script> 
		<script type="text/javascript" src="js/jquery.ui.touch-punch.min.js"></script> 
		<script type="text/javascript" src="js/jquery-ui.min.js"></script> 
		
		<script type="text/javascript" src="js/json2.js"></script> 

                <script type="text/javascript" src="js/jquery.multiselect.min.js"></script>
                <script type="text/javascript" src="js/jquery.multiselect.filter.js"></script>
		<script type="text/javascript" src="js/X-1.js"></script> 

	</head>
	
	<body>

<form id="a">
<h1> 單位影片記錄查詢/統計 </h1>
		<div id="content">

                        <div style="margin-top:0px; margin-bottom: 10px; text-align:center">
                                <button id="LoadRecordsButton" class="myButton">單位影片記錄查詢 </button>
                                <button id="showUsageButton" class="myButton">使用普及率查詢 </button>
                                <button id="showCountButton" class="myButton">使用人次統計 </button>
                                <button id="showHeadCountButton" class="myButton">使用人數統計 </button>
                        </div>

                        <h3>過濾條件</h3>
                        <div id="selection">
				<ul>
					<li><a href="#date_selection">起始/結束日期</a> </li>
					<li><a href="#video_selection"> 選擇分類與影片 </a> </li>
					<li><a href="#unit_selection"> 業務員單位</a> </li>
					<li><a href="#rank_selection"> 業務員職級</a> </li>
					<li>

						<button id="ClearSearchButton">清除搜尋條件</button>
					</li>

				</ul>
                                <div id="date_selection">
					起始日期：<input type="text" id="from" name="from" /> <br/>
					結束日期：<input type="text" id="to" name="to" />
                                </div>
                                <div id="video_selection">
					<select id="video_level_1" name="video_level_1" multiple="multiple"> </select> <div class="clear"> </div>
					<select id="video_level_2" name="video_level_2" multiple="multiple"> </select>
                                </div>
                                <div id="unit_selection">

                                        <button id="expandAll">全部展開</button>
                                        <button id="collapseAll">全部收起</button>
                                        <button id="selectAll">全部選取</button>
                                        <button id="clearAll">全部清除</button>
                                        <input type="checkbox" id="showSelected" />只顯示選取

                                        搜尋<input id="txtSearch" />

                                        <div class="clear"> </div>

                                        <ul id="unit_area">
                                            <?php echo $v->getUnitHTML(); ?>
                                        </ul>
                                </div>
                                <div id="rank_selection">
					<h4>選擇形態：<input name="rank_type" id="rank_include" type="radio" value="0" checked="checked" ><label for="rank_include">符合(全部不選時視為全選)</label> <input name="rank_type" id="rank_exclude" type="radio" value="1" ><label for="rank_exclude">排除</label> </h4>
					<select id="rank_list" name="rank_list" class="multiselect"  multiple="multiple">
                                        <?php echo $rank_options; ?>
					</select>
                                </div>
                        </div>


		</div>
</form>

	</body>
</html>
