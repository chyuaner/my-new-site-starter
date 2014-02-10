<?php
/*
    +--------------------------------------------------------------------------
    |   iProber v0.024
    |   ========================================
    |   by Tahiti
    |   dEpoch Studio
    |   http://www.depoch.net
    |   ========================================
    |   Web: http://www.depoch.net
    |   Last Updated: 29th April 2006
    |   Email: depoch@gmail.com
    +---------------------------------------------------------------------------
    |
    |   > PHP PROBER
    |   > Script written by Tahiti
    |   > Date started: 27th June 2004
    |
    +--------------------------------------------------------------------------


/* Functions in this file */
/**************************/

    // bar($percent)
    // find_command($commandName)
    // getcon($varName)
    // get_key($keyName)
    // isfun($funName)
    // sys_freebsd()
    // sys_linux()
    // test_float()
    // test_int()
    // test_io()
	// do_command($commandName, $args)

	header("content-Type: text/html; charset=utf-8");
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
	ob_start();
     
    $valInt = (false == empty($_POST['pInt']))?$_POST['pInt']:"未測試";
    $valFloat = (false == empty($_POST['pFloat']))?$_POST['pFloat']:"未測試";
    $valIo = (false == empty($_POST['pIo']))?$_POST['pIo']:"未測試";
    $mysqlReShow = "none";
    $mailReShow = "none";
    $funReShow = "none";
    $opReShow = "none";
    $sysReShow = "none";
     
    define("YES", "<span class='resYes'>YES</span>");
    define("NO", "<span class='resNo'>NO</span>");
    define("ICON", "<span class='icon'>2</span>&nbsp;");
    $phpSelf = $_SERVER[PHP_SELF] ? $_SERVER[PHP_SELF] : $_SERVER[SCRIPT_NAME];
    define("PHPSELF", preg_replace("/(.{0,}?\/+)/", "", $phpSelf));
     
    if ($_GET['act'] == "phpinfo")
    {
        phpinfo();
        exit();
    }
    elseif($_POST['act'] == "TEST_1")
    {
        $valInt = test_int();
    }
    elseif($_POST['act'] == "TEST_2")
    {
        $valFloat = test_float();
    }
    elseif($_POST['act'] == "TEST_3")
    {
        $valIo = test_io();
    }
    elseif($_POST['act'] == "CONNECT")
    {
        $mysqlReShow = "show";
        $mysqlRe = "MYSQL連接測試結果：";
        $mysqlRe .= (false !== @mysql_connect($_POST['mysqlHost'], $_POST['mysqlUser'], $_POST['mysqlPassword']))?"MYSQL伺服器連接正常, ":
        "MYSQL伺服器連接失敗, ";
        $mysqlRe .= "資料庫 <b>".$_POST['mysqlDb']."</b> ";
        $mysqlRe .= (false != @mysql_select_db($_POST['mysqlDb']))?"連接正常":
        "連接失敗";
    }
    elseif($_POST['act'] == "SENDMAIL")
    {
        $mailReShow = "show";
        $mailRe = "MAIL郵件發送測試結果：發送";
        $mailRe .= (false !== @mail($_POST["mailReceiver"], "MAIL SERVER TEST", "This email is sent by iProber.\r\n\r\ndEpoch Studio\r\nhttp://depoch.net"))?"完成":"失敗";
    }
    elseif($_POST['act'] == "FUNCTION_CHECK")
    {
        $funReShow = "show";
        $funRe = "函數 <b>".$_POST['funName']."</b> 支持狀況檢測結果：".isfun($_POST['funName']);
    }
    elseif($_POST['act'] == "CONFIGURATION_CHECK")
    {
        $opReShow = "show";
        $opRe = "配置參數 <b>".$_POST['opName']."</b> 檢測結果：".getcon($_POST['opName']);
    }
     
     
    // 系統參數
     
     
    switch (PHP_OS)
    {
        case "Linux":
        $sysReShow = (false !== ($sysInfo = sys_linux()))?"show":"none";
        break;
        case "FreeBSD":
        $sysReShow = (false !== ($sysInfo = sys_freebsd()))?"show":"none";
        break;
        default:
        break;
    }
     
/*========================================================================*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PHP探針 iProber V0.024</title>
<meta name="keywords" content="php探針,探針程式,php探針程式,探針,iProber,dEpoch Studio" />
<style type="text/css">
<!--
/*******************************GENERAL**********************************/
body,div,p,ul,form,h1 { margin:0px; padding:0px; }
div,a,input { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #666666; }
div { margin-left:auto; margin-right:auto; }
li { list-style-type:none; }
input { border: 1px solid #999999; background:#f5f5f5; }
a { text-decoration:none; color:#5599ff; }
a.arrow { font-family:Webdings, sans-serif; font-size:10px; }
a.arrow:hover { color:#ff0000; }
.resYes { font-size: 9px; font-weight: bold; color: #33CC00; } 
.resNo { font-size: 9px; font-weight: bold; color: #FF0000; }
.myButton { font-size:10px; font-weight:bold;  background:#CCFFFF; }
.bar { border:1px solid #999999; height:8px; font-size:2px; }
.bar li {  background:#ccffff; height:8px; font-size:2px;}
.jump { height:20px; width:15px; float:right; line-height:10px; text-align:right; }
/****************************HEADER**************************************/
#top { width:720px; }
#top h1 { color:#7fc643; font-size:35px; width:300px; float:left; }
#top h1 b { color:#ffb300; font-size:50px; font-family: Webdings, sans-serif; font-weight:normal; }
#top h1 span { font-size:10px; padding-left:10px; color:#999999; }
#t1 { float:right; text-align:right; padding:15px 0px 30px 0px; }
#t1 a { color:#999999; line-height:18px; }

#t2 { border:1px solid #CCCCCC; border-bottom:none;  padding:2px; clear:both; height:30px; }
#t2 ul { background-color:#2359B1; height:30px; }
#t2 li {  padding:5px 0px; height:20px; line-height:20px; }
#t2 a { color:#FFFFFF; }
#t2 a:hover { color:#81B0FF; }
#t21 { float:left; }
#t21 a { padding:10px; border-right:1px solid #FFFFFF; }
#t22 { float:right; }

#t3 { border:1px solid #cccccc; border-top:3px solid #BFC1C0; }
#t3 p { border-top:1px solid #FFFFFF; height:22px; line-height:22px; padding-left:5px; background:#F5F5F5;  }
#t3 b { font-size:10px; color:#cc3300; }
#t3 a { color:#666666; }
#t3 a:hover { text-decoration:underline; }
/*****************************MAIN****************************************/
#main { width:720px; }
#main th { text-align:left; padding:5px 0px; }
#main table { clear:both; }
fieldset { border:3px double #cccccc; margin-top:15px; padding:10px; }
legend { background:#5599ff;	color:#FFFFFF; padding:5px 10px; }
fieldset td { border-bottom:1px dotted #dedede; padding:5px 0px; }

#m4 { background-color:#efefef; }
#m4 th,#m4 td { background:#ffffff; padding:3px; border-bottom:none; text-align:center; }
#m4 th { font-weight:normal; color:#444444; }
/****************************FOOTER***************************************/
#footer { width:720px; }
#footer td { text-align:center; padding:1px 3px; font-size:10px; }
#footer a { font-size:10px; }
#f1 { text-align:right; padding:15px; }

#f2 {float:left; border:1px solid #dddddd; }
#f2 td { background:#FF9900; }
#f2 a { color:#ffffff; }

#f3 { border: 1px solid #888888; float:right; }
#f3 a { color:#222222; }
#f31 { background:#2359B1; color:#FFFFFF; }
#f32 { background:#dddddd; }
-->
</style>
</head>
<body>
<form method="post" action="<?=PHPSELF."#bottom"?>"  id="main_form">
	<div id="top">
		<h1><i><b>i</b>Prober</i><span>V0.024</span></h1>
		<a name="top"></a>
		<p id="t1"><a href="http://depoch.net/download.htm" target="_blank">點擊下載 iProber 探針, 或查看最新版本 ■</a><br />
			<a href="mailto:depoch@gmail.com">報告BUGS, 或反饋意見給 dEpoch Studio ■</a></p>
		<div id="t2">
			<ul>
				<li id="t21"><a href="#sec1">伺服器特徵</a><a href="#sec2">PHP基本特徵</a><a href="#sec3">PHP元件支援狀況</a><a href="#sec4">伺服器性能檢測</a><a href="#sec5">自定義檢測</a></li>
				<li id="t22"><a href="<?=PHPSELF?>">刷新</a>&nbsp;&nbsp;<a href="#bottom" class="arrow">66</a>&nbsp;&nbsp;</li>
			</ul>
		</div>
		<div id="t3">
			<p><b>〖AD〗</b>
				<a href="http://www.egobiz.com" target="_blank">羿高域名商務網: </a>
				<a href="http://www.egobiz.com/domain/" target="_blank">域名註冊</a>
				<a href="http://www.egobiz.com/domain-sell/" target="_blank">域名交易</a>
				<a href="http://www.egobiz.com/deleting-domain/" target="_blank">過期域名|刪除域名查詢</a>
				<a href="http://www.egobiz.com/domain-tool/" target="_blank">域名工具集</a></p>
		</div>
	</div>
	<div id="main">
<!-- =============================================================
伺服器特性 
============================================================= -->
		<fieldset>
		<legend>伺服器特性<a name="sec1" id="sec1"></a></legend>
		<p class="jump"><a href="#top" class="arrow">5</a><br />
			<a href="#bottom" class="arrow">6</a></p>
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<?if("show"==$sysReShow){?>
			<tr>
				<td>伺服器處理器 CPU</td>
				<td>CPU個數：
					<?=$sysInfo['cpu']['num']?>
					<br />
					<?=$sysInfo['cpu']['detail']?></td>
			</tr>
			<?}?>
			<tr>
				<td>伺服器時間</td>
				<td><?=date("Y年n月j日 H:i:s")?>
&nbsp;北京時間：
					<?=gmdate("Y年n月j日 H:i:s",time()+8*3600)?></td>
			</tr>
			<?if("show"==$sysReShow){?>
			<tr>
				<td>伺服器運行時間</td>
				<td><?=$sysInfo['uptime']?></td>
			</tr>
			<?}?>
			<tr>
				<td>伺服器域名/IP位址</td>
				<td><?=$_SERVER['SERVER_NAME']?>
					(
					<?=@gethostbyname($_SERVER['SERVER_NAME'])?>
					)</td>
			</tr>
			<tr>
				<td>伺服器作業系統
					<?$os = explode(" ", php_uname());?></td>
				<td><?=$os[0];?>
&nbsp;內核版本：
					<?=$os[2]?></td>
			</tr>
			<tr>
				<td>主機名稱</td>
				<td><?=$os[1];?></td>
			</tr>
			<tr>
				<td>伺服器解譯引擎</td>
				<td><?=$_SERVER['SERVER_SOFTWARE']?></td>
			</tr>
			<tr>
				<td>Web服務埠</td>
				<td><?=$_SERVER['SERVER_PORT']?></td>
			</tr>
			<tr>
				<td>伺服器管理員</td>
				<td><a href="mailto:<?=$_SERVER['SERVER_ADMIN']?>">
					<?=$_SERVER['SERVER_ADMIN']?>
					</a></td>
			</tr>
			<tr>
				<td>本檔路徑</td>
				<td><?=$_SERVER['PATH_TRANSLATED']?></td>
			</tr>
			<tr>
				<td>目前還有空餘空間&nbsp;diskfreespace</td>
				<td><?=round((@disk_free_space(".")/(1024*1024)),2)?>
					M</td>
			</tr>
			<?if("show"==$sysReShow){?>
			<tr>
				<td>記憶體使用狀況</td>
				<td> 實體記憶體：共
					<?=$sysInfo['memTotal']?>M, 已使用
					<?=$sysInfo['memUsed']?>M, 空閒
					<?=$sysInfo['memFree']?>M, 使用率
					<?=$sysInfo['memPercent']?>%
					<?=bar($sysInfo['memPercent'])?>
					SWAP區：共
					<?=$sysInfo['swapTotal']?>M, 已使用
					<?=$sysInfo['swapUsed']?>M, 空閒
					<?=$sysInfo['swapFree']?>M, 使用率
					<?=$sysInfo['swapPercent']?>%
					<?=bar($sysInfo['swapPercent'])?>
				</td>
			</tr>
			<tr>
				<td>系統平均負載</td>
				<td><?=$sysInfo['loadAvg']?></td>
			</tr>
			<?}?>
		</table>
		</fieldset>
<!-- ============================================================= 
PHP基本特性 
============================================================== -->
		<fieldset>
		<legend>PHP基本特性<a name="sec2" id="sec2"></a></legend>
		<p class="jump"><a href="#top" class="arrow">5</a><br />
			<a href="#bottom" class="arrow">6</a></p>
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td width="49%">PHP運行方式</td>
				<td width="51%"><?=strtoupper(php_sapi_name())?></td>
			</tr>
			<tr>
				<td>PHP版本</td>
				<td><?=PHP_VERSION?></td>
			</tr>
			<tr>
				<td>運行于安全模式</td>
				<td><?=getcon("safe_mode")?></td>
			</tr>
			<tr>
				<td>支援ZEND編譯運行</td>
				<td><?=(get_cfg_var("zend_optimizer.optimization_level")||get_cfg_var("zend_extension_manager.optimizer_ts")||get_cfg_var("zend_extension_ts")) ?YES:NO?></td>
			</tr>
			<tr>
				<td>允許使用URL打開檔&nbsp;allow_url_fopen</td>
				<td><?=getcon("allow_url_fopen")?></td>
			</tr>
			<tr>
				<td>允許動態載入程式庫&nbsp;enable_dl</td>
				<td><?=getcon("enable_dl")?></td>
			</tr>
			<tr>
				<td>顯示錯誤資訊&nbsp;display_errors</td>
				<td><?=getcon("display_errors")?></td>
			</tr>
			<tr>
				<td>自動定義總體變數&nbsp;register_globals</td>
				<td><?=getcon("register_globals")?></td>
			</tr>
			<tr>
				<td>程式最多允許使用記憶體量&nbsp;memory_limit</td>
				<td><?=getcon("memory_limit")?></td>
			</tr>
			<tr>
				<td>POST最大位元組數&nbsp;post_max_size</td>
				<td><?=getcon("post_max_size")?></td>
			</tr>
			<tr>
				<td>允許最大上傳檔&nbsp;upload_max_filesize</td>
				<td><?=getcon("upload_max_filesize")?></td>
			</tr>
			<tr>
				<td>程式最長運行時間&nbsp;max_execution_time</td>
				<td><?=getcon("max_execution_time")?>
					秒</td>
			</tr>
			<tr>
				<td>magic_quotes_gpc</td>
				<td><?=(1===get_magic_quotes_gpc())?YES:NO?></td>
			</tr>
			<tr>
				<td>magic_quotes_runtime</td>
				<td><?=(1===get_magic_quotes_runtime())?YES:NO?></td>
			</tr>
			<tr>
				<td>被禁用的函數&nbsp;disable_functions</td>
				<td><?=(""==($disFuns=get_cfg_var("disable_functions")))?"無":str_replace(",","<br />",$disFuns)?></td>
			</tr>
			<tr>
				<td>PHP信息&nbsp;PHPINFO</td>
				<td><?=(false!==eregi("phpinfo",$disFuns))?NO:"<a href='$phpSelf?act=phpinfo' target='_blank' class='static'>PHPINFO</a>"?></td>
			</tr>
		</table>
		</fieldset>
<!-- ============================================================= 
PHP組件支援 
============================================================== -->
		<fieldset>
		<legend>PHP組件支援<a name="sec3" id="sec3"></a></legend>
		<p class="jump"><a href="#top" class="arrow">5</a><br />
			<a href="#bottom" class="arrow">6</a></p>
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td width="38%">拼寫檢查 ASpell Library</td>
				<td width="12%"><?=isfun("aspell_check_raw")?></td>
				<td width="38%">高精度數學運算 BCMath</td>
				<td width="12%"><?=isfun("bcadd")?></td>
			</tr>
			<tr>
				<td>曆法運算 Calendar</td>
				<td><?=isfun("cal_days_in_month")?></td>
				<td>DBA資料庫</td>
				<td><?=isfun("dba_close")?></td>
			</tr>
			<tr>
				<td>dBase資料庫</td>
				<td><?=isfun("dbase_close")?></td>
				<td>DBM資料庫</td>
				<td><?=isfun("dbmclose")?></td>
			</tr>
			<tr>
				<td>FDF表單資料格式</td>
				<td><?=isfun("fdf_get_ap")?></td>
				<td>FilePro資料庫</td>
				<td><?=isfun("filepro_fieldcount")?></td>
			</tr>
			<tr>
				<td>Hyperwave資料庫</td>
				<td><?=isfun("hw_close")?></td>
				<td>圖形處理 GD Library</td>
				<td><?=isfun("gd_info")?></td>
			</tr>
			<tr>
				<td>IMAP電子郵件系統</td>
				<td><?=isfun("imap_close")?></td>
				<td>Informix資料庫</td>
				<td><?=isfun("ifx_close")?></td>
			</tr>
			<tr>
				<td>LDAP目錄協定</td>
				<td><?=isfun("ldap_close")?></td>
				<td>MCrypt加密處理</td>
				<td><?=isfun("mcrypt_cbc")?></td>
			</tr>
			<tr>
				<td>哈稀計算 MHash</td>
				<td><?=isfun("mhash_count")?></td>
				<td>mSQL資料庫</td>
				<td><?=isfun("msql_close")?></td>
			</tr>
			<tr>
				<td>SQL Server資料庫</td>
				<td><?=isfun("mssql_close")?></td>
				<td>MySQL資料庫</td>
				<td><?=isfun("mysql_close")?></td>
			</tr>
			<tr>
				<td>SyBase資料庫</td>
				<td><?=isfun("sybase_close")?></td>
				<td>Yellow Page系統</td>
				<td><?=isfun("yp_match")?></td>
			</tr>
			<tr>
				<td>Oracle資料庫</td>
				<td><?=isfun("ora_close")?></td>
				<td>Oracle 8 資料庫</td>
				<td><?=isfun("OCILogOff")?></td>
			</tr>
			<tr>
				<td>PREL相容語法 PCRE</td>
				<td><?=isfun("preg_match")?></td>
				<td>PDF文檔支持</td>
				<td><?=isfun("pdf_close")?></td>
			</tr>
			<tr>
				<td>Postgre SQL資料庫</td>
				<td><?=isfun("pg_close")?></td>
				<td>SNMP網路管理協定</td>
				<td><?=isfun("snmpget")?></td>
			</tr>
			<tr>
				<td>VMailMgr郵件處理</td>
				<td><?=isfun("vm_adduser")?></td>
				<td>WDDX支持</td>
				<td><?=isfun("wddx_add_vars")?></td>
			</tr>
			<tr>
				<td>壓縮檔支援(Zlib)</td>
				<td><?=isfun("gzclose")?></td>
				<td>XML解析</td>
				<td><?=isfun("xml_set_object")?></td>
			</tr>
			<tr>
				<td>FTP</td>
				<td><?=isfun("ftp_login")?></td>
				<td>ODBC資料庫連接</td>
				<td><?=isfun("odbc_close")?></td>
			</tr>
			<tr>
				<td>Session支持</td>
				<td><?=isfun("session_start")?></td>
				<td>Socket支持</td>
				<td><?=isfun("socket_accept")?></td>
			</tr>
		</table>
		</fieldset>
<!-- ============================================================= 
伺服器性能檢測 
============================================================== -->
		<fieldset>
		<legend>伺服器性能檢測<a name="sec4" id="sec4"></a></legend>
		<p class="jump"><a href="#top" class="arrow">5</a><br />
			<a href="#bottom" class="arrow">6</a></p>
		<table width="100%" cellpadding="0" cellspacing="1" border="0" id="m4">
			<tr>
				<th>檢測對象</th>
				<th>整數運算能力測試<br />
					(1+1運算300萬次)</th>
				<th>浮點運算能力測試<br />
					(開平方300萬次)</th>
				<th>資料I/O能力測試<br />
					(讀取10K文件10000次)</th>
			</tr>
			<tr>
				<td>Tahiti 的電腦(P4 1.7G 256M WinXP)</td>
				<td> 1.421秒</td>
				<td> 1.358秒</td>
				<td> 0.177秒</td>
			</tr>
			<tr>
				<td>PIPNI免費空間(2004/06/28 02:08)</td>
				<td> 2.545秒</td>
				<td> 2.545秒</td>
				<td>0.171秒 </td>
			</tr>
			<tr>
				<td>神話科技風CGI型(2004/06/28 02:03)</td>
				<td> 0.797秒</td>
				<td> 0.729秒</td>
				<td>0.156秒</td>
			</tr>
			<tr>
				<td>您正在使用的這台伺服器</td>
				<td><b>
					<?=$valInt?>
					</b><br />
					<input type="submit" value="TEST_1" class="myButton"  name="act" /></td>
				<td><b>
					<?=$valFloat?>
					</b><br />
					<input type="submit" value="TEST_2" class="myButton"  name="act" /></td>
				<td><b>
					<?=$valIo?>
					</b><br />
					<input type="submit" value="TEST_3" class="myButton"  name="act" /></td>
			</tr>
		</table>
		</fieldset>
<!-- ============================================================= 
自定義檢測 
============================================================== -->
		<?php
    $isMysql = (false !== function_exists("mysql_query"))?"":" disabled";
    $isMail = (false !== function_exists("mail"))?"":" disabled";
?>
		<fieldset>
		<legend>自定義檢測<a name="sec5" id="sec5"></a></legend>
		<p class="jump"><a href="#top" class="arrow">5</a><br />
			<a href="#bottom" class="arrow">6</a></p>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<th colspan="4">MYSQL連接測試</th>
			</tr>
			<tr>
				<td>MYSQL伺服器</td>
				<td><input type="text" name="mysqlHost" value="localhost" <?=$isMysql?> /></td>
				<td> MYSQL用戶名 </td>
				<td><input type="text" name="mysqlUser" <?=$isMysql?> /></td>
			</tr>
			<tr>
				<td> MYSQL用戶密碼 </td>
				<td><input type="text" name="mysqlPassword" <?=$isMysql?> /></td>
				<td> MYSQL資料庫名稱 </td>
				<td><input type="text" name="mysqlDb" />
&nbsp;<input type="submit" class="myButton" value="CONNECT" <?=$isMysql?>  name="act" /></td>
			</tr>
			<?php if("show"==$mysqlReShow){?>
			<tr>
				<td colspan="4"><?=$mysqlRe?></td>
			</tr>
			<?}?>
			<tr>
				<th colspan="4">MAIL郵件發送測試</th>
			</tr>
			<tr>
				<td>收信地址</td>
				<td colspan="3"><input type="text" name="mailReceiver" size="50" <?=$isMail?> />
&nbsp;<input type="submit" class="myButton" value="SENDMAIL" <?=$isMail?>  name="act" /></td>
			</tr>
			<?php if("show"==$mailReShow){?>
			<tr>
				<td colspan="4"><?=$mailRe?></td>
			</tr>
			<?}?>
			<tr>
				<th colspan="4">函數支援狀況</th>
			</tr>
			<tr>
				<td>函數名稱</td>
				<td colspan="3"><input type="text" name="funName" size="50" />
&nbsp;<input type="submit" class="myButton" value="FUNCTION_CHECK" name="act" /></td>
				<?php if("show"==$funReShow){?>
			<tr>
				<td colspan="4"><?=$funRe?></td>
			</tr>
			<?}?>
			</tr>
			
			<tr>
				<th colspan="4">PHP配置參數狀況</th>
			</tr>
			<tr>
				<td>參數名稱</td>
				<td colspan="3"><input type="text" name="opName" size="40" />
&nbsp;<input type="submit" class="myButton" value="CONFIGURATION_CHECK" name="act" /></td>
			</tr>
			<?php if("show"==$opReShow){?>
			<tr>
				<td colspan="4"><?=$opRe?></td>
			</tr>
			<?}?>
		</table>
		</fieldset>
	</div>
<!-- ============================================================= 
頁腳  
============================================================== -->
	<div id="footer">
		<p id="f1"><a name="bottom"></a><a href="#top" class="arrow">55</a></p>
		<table  border="0" cellspacing="1" cellpadding="0" id="f2">
			<tr>
				<td><a href="http://validator.w3.org/check?uri=referer" target="_blank">XHTML</a></td>
				<td><a href="http://jigsaw.w3.org/css-validator/validator?uri=http://<?=$_SERVER['SERVER_NAME'].($_SERVER[PHP_SELF] ? $_SERVER[PHP_SELF] : $_SERVER[SCRIPT_NAME]);?>" target="_blank">CSS</a> </td>
			</tr>
		</table>
		<table  border="0" cellspacing="1" cellpadding="0" id="f3">
			<tr>
				<td id="f31">Powered by</td>
				<td id="f32"><a href="http://www.depoch.net"><b>dEpoch Studio</b></a> </td>
			</tr>
		</table>
	</div>
	<input type="hidden" name="pInt" value="<?=$valInt?>" />
	<input type="hidden" name="pFloat" value="<?=$valFloat?>" />
	<input type="hidden" name="pIo" value="<?=$valIo?>" />
</form>
</body>
</html>
<?php
/*=============================================================
    函數庫
=============================================================*/
/*-------------------------------------------------------------------------------------------------------------
    檢測函數支援
--------------------------------------------------------------------------------------------------------------*/
    function isfun($funName)
    {
        return (false !== function_exists($funName))?YES:NO;
    }
/*-------------------------------------------------------------------------------------------------------------
    檢測PHP設置參數
--------------------------------------------------------------------------------------------------------------*/
    function getcon($varName)
    {
        switch($res = get_cfg_var($varName))
        {
            case 0:
            return NO;
            break;
            case 1:
            return YES;
            break;
            default:
            return $res;
            break;
        }
         
    }
/*-------------------------------------------------------------------------------------------------------------
    整數運算能力測試
--------------------------------------------------------------------------------------------------------------*/
    function test_int()
    {
        $timeStart = gettimeofday();
        for($i = 0; $i < 3000000; $i++);
        {
            $t = 1+1;
        }
        $timeEnd = gettimeofday();
        $time = ($timeEnd["usec"]-$timeStart["usec"])/1000000+$timeEnd["sec"]-$timeStart["sec"];
        $time = round($time, 3)."秒";
        return $time;
    }
/*-------------------------------------------------------------------------------------------------------------
    浮點運算能力測試
--------------------------------------------------------------------------------------------------------------*/
    function test_float()
    {
        $t = pi();
        $timeStart = gettimeofday();
        for($i = 0; $i < 3000000; $i++);
        {
            sqrt($t);
        }
        $timeEnd = gettimeofday();
        $time = ($timeEnd["usec"]-$timeStart["usec"])/1000000+$timeEnd["sec"]-$timeStart["sec"];
        $time = round($time, 3)."秒";
        return $time;
    }
/*-------------------------------------------------------------------------------------------------------------
    資料IO能力測試
--------------------------------------------------------------------------------------------------------------*/
    function test_io()
    {
        $fp = fopen(PHPSELF, "r");
        $timeStart = gettimeofday();
        for($i = 0; $i < 10000; $i++)
        {
            fread($fp, 10240);
            rewind($fp);
        }
        $timeEnd = gettimeofday();
        fclose($fp);
        $time = ($timeEnd["usec"]-$timeStart["usec"])/1000000+$timeEnd["sec"]-$timeStart["sec"];
        $time = round($time, 3)."秒";
        return($time);
    }
/*-------------------------------------------------------------------------------------------------------------
    比例條
--------------------------------------------------------------------------------------------------------------*/
    function bar($percent)
    {
    ?>
<ul class="bar">
	<li style="width:<?=$percent?>%">&nbsp;</li>
</ul>
<?php
    }
/*-------------------------------------------------------------------------------------------------------------
    系統參數探測 LINUX
--------------------------------------------------------------------------------------------------------------*/
    function sys_linux()
    {
        // CPU
        if (false === ($str = @file("/proc/cpuinfo"))) return false;
        $str = implode("", $str);
        @preg_match_all("/model\s+name\s{0,}\:+\s{0,}([\w\s\)\(.]+)[\r\n]+/", $str, $model);
        //@preg_match_all("/cpu\s+MHz\s{0,}\:+\s{0,}([\d\.]+)[\r\n]+/", $str, $mhz);
        @preg_match_all("/cache\s+size\s{0,}\:+\s{0,}([\d\.]+\s{0,}[A-Z]+[\r\n]+)/", $str, $cache);
        if (false !== is_array($model[1]))
            {
            $res['cpu']['num'] = sizeof($model[1]);
            for($i = 0; $i < $res['cpu']['num']; $i++)
            {
                $res['cpu']['detail'][] = "類型：".$model[1][$i]." 緩存：".$cache[1][$i];
            }
            if (false !== is_array($res['cpu']['detail'])) $res['cpu']['detail'] = implode("<br />", $res['cpu']['detail']);
            }
         
         
        // UPTIME
        if (false === ($str = @file("/proc/uptime"))) return false;
        $str = explode(" ", implode("", $str));
        $str = trim($str[0]);
        $min = $str / 60;
        $hours = $min / 60;
        $days = floor($hours / 24);
        $hours = floor($hours - ($days * 24));
        $min = floor($min - ($days * 60 * 24) - ($hours * 60));
        if ($days !== 0) $res['uptime'] = $days."天";
        if ($hours !== 0) $res['uptime'] .= $hours."小時";
        $res['uptime'] .= $min."分鐘";
         
        // MEMORY
        if (false === ($str = @file("/proc/meminfo"))) return false;
        $str = implode("", $str);
        preg_match_all("/MemTotal\s{0,}\:+\s{0,}([\d\.]+).+?MemFree\s{0,}\:+\s{0,}([\d\.]+).+?SwapTotal\s{0,}\:+\s{0,}([\d\.]+).+?SwapFree\s{0,}\:+\s{0,}([\d\.]+)/s", $str, $buf);
         
        $res['memTotal'] = round($buf[1][0]/1024, 2);
        $res['memFree'] = round($buf[2][0]/1024, 2);
        $res['memUsed'] = ($res['memTotal']-$res['memFree']);
        $res['memPercent'] = (floatval($res['memTotal'])!=0)?round($res['memUsed']/$res['memTotal']*100,2):0;
         
        $res['swapTotal'] = round($buf[3][0]/1024, 2);
        $res['swapFree'] = round($buf[4][0]/1024, 2);
        $res['swapUsed'] = ($res['swapTotal']-$res['swapFree']);
        $res['swapPercent'] = (floatval($res['swapTotal'])!=0)?round($res['swapUsed']/$res['swapTotal']*100,2):0;
         
        // LOAD AVG
        if (false === ($str = @file("/proc/loadavg"))) return false;
        $str = explode(" ", implode("", $str));
        $str = array_chunk($str, 3);
        $res['loadAvg'] = implode(" ", $str[0]);
         
        return $res;
    }
/*-------------------------------------------------------------------------------------------------------------
    系統參數探測 FreeBSD
--------------------------------------------------------------------------------------------------------------*/
    function sys_freebsd()
    {
        //CPU
        if (false === ($res['cpu']['num'] = get_key("hw.ncpu"))) return false;
        $res['cpu']['detail'] = get_key("hw.model");
         
        //LOAD AVG
        if (false === ($res['loadAvg'] = get_key("vm.loadavg"))) return false;
        $res['loadAvg'] = str_replace("{", "", $res['loadAvg']);
        $res['loadAvg'] = str_replace("}", "", $res['loadAvg']);
         
        //UPTIME
        if (false === ($buf = get_key("kern.boottime"))) return false;
        $buf = explode(' ', $buf);
        $sys_ticks = time() - intval($buf[3]);
        $min = $sys_ticks / 60;
        $hours = $min / 60;
        $days = floor($hours / 24);
        $hours = floor($hours - ($days * 24));
        $min = floor($min - ($days * 60 * 24) - ($hours * 60));
        if ($days !== 0) $res['uptime'] = $days."天";
        if ($hours !== 0) $res['uptime'] .= $hours."小時";
        $res['uptime'] .= $min."分鐘";
         
        //MEMORY
        if (false === ($buf = get_key("hw.physmem"))) return false;
        $res['memTotal'] = round($buf/1024/1024, 2);
        $buf = explode("\n", do_command("vmstat", ""));
        $buf = explode(" ", trim($buf[2]));
         
        $res['memFree'] = round($buf[5]/1024, 2);
        $res['memUsed'] = ($res['memTotal']-$res['memFree']);
        $res['memPercent'] = (floatval($res['memTotal'])!=0)?round($res['memUsed']/$res['memTotal']*100,2):0;
		         
        $buf = explode("\n", do_command("swapinfo", "-k"));
        $buf = $buf[1];
        preg_match_all("/([0-9]+)\s+([0-9]+)\s+([0-9]+)/", $buf, $bufArr);
        $res['swapTotal'] = round($bufArr[1][0]/1024, 2);
        $res['swapUsed'] = round($bufArr[2][0]/1024, 2);
        $res['swapFree'] = round($bufArr[3][0]/1024, 2);
        $res['swapPercent'] = (floatval($res['swapTotal'])!=0)?round($res['swapUsed']/$res['swapTotal']*100,2):0;
         
        return $res;
    }
     
/*-------------------------------------------------------------------------------------------------------------
    取得參數值 FreeBSD
--------------------------------------------------------------------------------------------------------------*/
function get_key($keyName)
    {
        return do_command('sysctl', "-n $keyName");
    }
     
/*-------------------------------------------------------------------------------------------------------------
    確定執行檔位置 FreeBSD
--------------------------------------------------------------------------------------------------------------*/
    function find_command($commandName)
    {
        $path = array('/bin', '/sbin', '/usr/bin', '/usr/sbin', '/usr/local/bin', '/usr/local/sbin');
        foreach($path as $p)
        {
            if (@is_executable("$p/$commandName")) return "$p/$commandName";
        }
        return false;
    }
     
/*-------------------------------------------------------------------------------------------------------------
    執行系統命令 FreeBSD
--------------------------------------------------------------------------------------------------------------*/
    function do_command($commandName, $args)
    {
        $buffer = "";
        if (false === ($command = find_command($commandName))) return false;
        if ($fp = @popen("$command $args", 'r'))
            {
				while (!@feof($fp))
				{
					$buffer .= @fgets($fp, 4096);
				}
				return trim($buffer);
			}
        return false;
    }

?>
