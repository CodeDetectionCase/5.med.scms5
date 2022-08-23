<?php
require '../function/conn.php';
require '../function/function.php';

$action=$_REQUEST["action"];
$id=intval($_REQUEST["id"]);
if($id==""){
	$id="0";
}

$page=$_REQUEST["page"];
switch($action){
case "check":
$info=getbody("http://cdn.s-cms.cn/check_server.php","domain=".$_SERVER["HTTP_HOST"]."&code=".$C_authcode);
die($info.PHP_EOL."PHP版本：".phpversion().PHP_EOL."本机IP2：".$_SERVER['SERVER_ADDR'].PHP_EOL.var_dump($_SESSION));
break;
	
case "cookie":
die($_COOKIE[$_GET["name"]]);
break;

case "configx":

$sql="select count(*) as G_num from ".TABLE."guestbook where G_sh=0";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$G_num=$row["G_num"];

$sql="select count(*) as P_num from ".TABLE."comment where C_sh=0";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$P_num=$row["P_num"];

$sql="select count(*) as O_num from ".TABLE."orders where O_state=1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$O_num=$row["O_num"];


$sql3="select distinct(R_rid) from ".TABLE."response,".TABLE."member,".TABLE."content where R_cid=C_id and M_del=0 and R_member=M_id and R_read=0";
$F_num=0;
$result3 = mysqli_query($conn,  $sql3);
if (mysqli_num_rows($result3) > 0) {
	while($row3 = mysqli_fetch_assoc($result3)) {
		$F_num+=1;
	}
}

$sql="select count(*) as N_num from ".TABLE."news where N_sh=2 and N_del=0";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$N_num=$row["N_num"];

$sql="select count(*) as fen_num from ".TABLE."list where L_type=1 and L_sh=1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$fen_num=$row["fen_num"];

$sql="select count(*) as money_num from ".TABLE."list where L_type=0 and L_sh=1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$money_num=$row["money_num"];

if(datediffx("d",$C_time,date('Y-m-d H:i:s'))>7){
	$auth_close="false";
}else{
	$auth_close="true";
}

$C_from=$config->from;
$C_t_show=$config->template;
$C_x_show=$config->plug;

$C_version=trim(file_get_contents("version.txt"),"\xEF\xBB\xBF");
$C_port=$_SERVER["SERVER_PORT"];
$count_all=$G_num+$P_num+$O_num+$F_num+$N_num+$fen_num+$money_num;

setcookie("count_all",$count_all);

//die("{\"C_ico\":\"".$C_ico."\",\"C_lang\":\"".$C_lang."\",\"C_langcode\":\"".$C_langcode."\",\"C_delang\":\"".$C_delang."\",\"lang\":\"".$_SESSION["i"]."\",\"gnum\":\"".$G_num."\",\"pnum\":\"".$P_num."\",\"onum\":\"".$O_num."\",\"fnum\":\"".$F_num."\",\"nnum\":\"".$N_num."\",\"fen_num\":\"".$fen_num."\",\"money_num\":\"".$money_num."\",\"anum\":\"".$count_all."\",\"auth\":\"".auth2("atp")."\",\"domain\":\"".$C_domain."\",\"url\":\"".$C_domain.$C_dir.$C_admin."\",\"wxapp\":\"".auth2("wxapp")."\",\"app\":\"".auth2("app")."\",\"x1\":\"".auth2("x1")."\",\"x2\":\"".auth2("x2")."\",\"x3\":\"".auth2("x3")."\",\"x4\":\"".auth2("x4")."\",\"x5\":\"".auth2("x5")."\",\"x6\":\"".auth2("x6")."\",\"x7\":\"".auth2("x7")."\",\"x8\":\"".auth2("x8")."\",\"x9\":\"".auth2("x9")."\",\"x10\":\"".auth2("x10")."\",\"x11\":\"".auth2("x11")."\",\"x12\":\"".auth2("x12")."\",\"x13\":\"".auth2("x13")."\",\"x14\":\"".auth2("x14")."\",\"x15\":\"".auth2("x15")."\",\"C_template\":\"".$C_template."\",\"C_wap\":\"".$C_wap."\",\"C_auth_close\":\"".$auth_close."\",\"C_from\":\"".$C_from."\",\"C_t_show\":\"".$C_t_show."\",\"C_x_show\":\"".$C_x_show."\",\"C_sort\":\"".$C_sort."\",\"C_tag\":\"".gljson($C_tag)."\",\"C_db\":\"".$C_db."\",\"C_dir\":\"".gljson($C_dir)."\",\"C_version\":\"".$C_version."\",\"C_port\":\"".$C_port."\",\"C_langtitle0\":\"".splitx($C_langtitle,",",0)."\",\"C_langtitle1\":\"".splitx($C_langtitle,",",1)."\",\"C_langtitle2\":\"".splitx($C_langtitle,",",2)."\",\"https\":\"".gethttp()."\"}");


$arr=json_decode("{\"C_ico\":\"".$C_ico."\",\"C_lang\":\"".$C_lang."\",\"C_langcode\":\"".$C_langcode."\",\"C_delang\":\"".$C_delang."\",\"lang\":\"".$_SESSION["i"]."\",\"gnum\":\"".$G_num."\",\"pnum\":\"".$P_num."\",\"onum\":\"".$O_num."\",\"fnum\":\"".$F_num."\",\"nnum\":\"".$N_num."\",\"fen_num\":\"".$fen_num."\",\"money_num\":\"".$money_num."\",\"anum\":\"".$count_all."\",\"auth\":\"".auth2("atp")."\",\"domain\":\"".$C_domain."\",\"url\":\"".$C_domain.$C_dir.$C_admin."\",\"wxapp\":\"".auth2("wxapp")."\",\"app\":\"".auth2("app")."\",\"x1\":\"".auth2("x1")."\",\"x2\":\"".auth2("x2")."\",\"x3\":\"".auth2("x3")."\",\"x4\":\"".auth2("x4")."\",\"x5\":\"".auth2("x5")."\",\"x6\":\"".auth2("x6")."\",\"x7\":\"".auth2("x7")."\",\"x8\":\"".auth2("x8")."\",\"x9\":\"".auth2("x9")."\",\"x10\":\"".auth2("x10")."\",\"x11\":\"".auth2("x11")."\",\"x12\":\"".auth2("x12")."\",\"x13\":\"".auth2("x13")."\",\"x14\":\"".auth2("x14")."\",\"x15\":\"".auth2("x15")."\",\"C_template\":\"".$C_template."\",\"C_wap\":\"".$C_wap."\",\"C_auth_close\":\"".$auth_close."\",\"C_from\":\"".$C_from."\",\"C_t_show\":\"".$C_t_show."\",\"C_x_show\":\"".$C_x_show."\",\"C_sort\":\"".$C_sort."\",\"C_db\":\"".$C_db."\",\"C_dir\":\"".gljson($C_dir)."\",\"C_version\":\"".$C_version."\",\"C_port\":\"".$C_port."\",\"C_langtitle0\":\"".splitx($C_langtitle,",",0)."\",\"C_langtitle1\":\"".splitx($C_langtitle,",",1)."\",\"C_langtitle2\":\"".splitx($C_langtitle,",",2)."\",\"https\":\"".gethttp()."\"}",true);
$arr["C_tag"]=$C_tag;

die(json_encode($arr));
break;
}

if($_SESSION["user"]!=""){

	$sql="select * from ".TABLE."admin where A_del=0 and A_login='".$_SESSION["user"]."' and A_del=0";
	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		$pass=$row["A_pwd"];
	}else{
		die("error0");
	}

	if(strtolower(md5("pass".strtoupper($pass)))!=strtolower($_SESSION["pass"])){
		setcookie("user","");
		setcookie("pass","");
		setcookie("auth","");
		setcookie("newsauth","");
		setcookie("productauth","");
		setcookie("textauth","");
		setcookie("formauth","");
		setcookie("bbsauth","");
		die("error1");
	}

}else{
	die("error2");
}


if ($_SESSION["user"] =="" || $_SESSION["pass"] ==""){
}else{
if($_REQUEST["lang"]=="" || $_REQUEST["lang"]=="undefined"){
}else{
$_SESSION["i"]=tol($_REQUEST["lang"]);
}

switch($action){
	case "config":
	$sql="select * from ".TABLE."config";
	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_assoc($result);
	if(mysqli_num_rows($result) > 0) {
		$C_qq1=$row["C_qq1"];
		$C_qq2=$row["C_qq2"];
		$C_qq3=$row["C_qq3"];
		$C_qq4=$row["C_qq4"];
	}

	$C_jpeg=0;

	$C_version=trim(file_get_contents("version.txt"),"\xEF\xBB\xBF");
	$C_flag0=splitx($C_flag,",",0);
	$C_flag1=splitx($C_flag,",",1);
	$C_flag2=splitx($C_flag,",",2);
	
if($C_userid=="" || $C_codeid=="" || $C_codekey==""){
	$sms_rest="<font color='#ff0000'>接口错误（请检查企业ID/帐号/密码是否填写正确）</font>";
}else{
	$info=GetBody("http://dx.10691.net:8888/sms.aspx?action=overage&userid=".$C_userid."&account=".$C_codeid."&password=".$C_codekey,"");
	$xml =simplexml_load_string($info);

	if ($xml->returnstatus=="Sucess"){
		$sms_rest=$xml->overage;
	}else{
		$sms_rest="<font color='#ff0000'>接口错误（请检查企业ID/帐号/密码是否填写正确）</font>";
	}
}

echo "{\"C_webtitle\":\"".gljson(lang($C_webtitle))."\",\"C_keywords\":\"".gljson(lang($C_keywords))."\",\"C_description\":\"".gljson(lang($C_description))."\",\"C_logo\":\"".$C_logo."\",\"C_code\":\"".gljson($C_code)."\",\"C_admin\":\"".$C_admin."\",\"C_foot\":\"".gljson(lang($C_foot))."\",\"C_logox\":\"".$C_logox."\",\"C_logoy\":\"".$C_logoy."\",\"C_ico\":\"".$C_ico."\",\"C_qq\":\"".str_replace(",",'\r\n',gljson(lang($C_qq)))."\",\"C_qqon\":\"".$C_qqon."\",\"C_member\":\"".$C_member."\",\"C_top\":\"".$C_top."\",\"C_qq1\":\"".$C_qq1."\",\"C_qq2\":\"".$C_qq2."\",\"C_qq3\":\"".$C_qq3."\",\"C_qq4\":\"".$C_qq4."\",\"C_mobile\":\"".str_replace("|",'\r\n',gljson(lang($C_mobile)))."\",\"C_wcode\":\"".$C_wcode."\",\"C_wtitle\":\"".gljson($C_wtitle)."\",\"C_lang\":\"".$C_lang."\",\"C_delang\":\"".$C_delang."\",\"C_fenxiang\":\"".gljson($C_fenxiang)."\",\"C_smtp\":\"".$C_smtp."\",\"C_html\":\"".$C_html."\",\"C_dir\":\"".$C_dir."\",\"C_paypal\":\"".$C_paypal."\",\"C_paypalon\":\"".$C_paypalon."\",\"C_alipayon\":\"".$C_alipayon."\",\"C_wxpayon\":\"".$C_wxpayon."\",\"C_bankon\":\"".$C_bankon."\",\"C_balanceon\":\"".$C_balanceon."\",\"C_alipay\":\"".$C_alipay."\",\"C_alipaykey\":\"".$C_alipaykey."\",\"C_alipayid\":\"".$C_alipayid."\",\"C_userid\":\"".$C_userid."\",\"C_codeid\":\"".$C_codeid."\",\"C_codekey\":\"".$C_codekey."\",\"C_smssign\":\"".$C_smssign."\",\"C_need\":\"".gljson($C_need)."\",\"C_weibo\":\"".$C_weibo."\",\"C_qqid\":\"".$C_qqid."\",\"C_qqkey\":\"".$C_qqkey."\",\"C_wx_appid\":\"".$C_wx_appid."\",\"C_wx_mchid\":\"".$C_wx_mchid."\",\"C_wx_key\":\"".$C_wx_key."\",\"C_wx_appsecret\":\"".$C_wx_appsecret."\",\"C_domain\":\"".$C_domain."\",\"C_todomain\":\"".$C_todomain."\",\"C_alipayon\":\"".$C_alipayon."\",\"C_wxpayon\":\"".$C_wxpayon."\",\"C_bankon\":\"".$C_bankon."\",\"C_email\":\"".$C_email."\",\"C_mailtype\":\"".$C_mailtype."\",\"C_mpwd\":\"".$C_mpwd."\",\"C_qqid\":\"".$C_qqid."\",\"C_qqkey\":\"".$C_qqkey."\",\"C_pid\":\"".$C_pid."\",\"C_tp\":\"".$C_tp."\",\"C_np\":\"".$C_np."\",\"C_pp\":\"".$C_pp."\",\"C_td\":\"".$C_td."\",\"C_nd\":\"".$C_nd."\",\"C_pd\":\"".$C_pd."\",\"C_version\":\"".$C_version."\",\"lang\":\"".$_SESSION["i"]."\",\"C_template\":\"".$C_template."\",\"C_wap\":\"".$C_wap."\",\"C_npage\":\"".$C_npage."\",\"C_ppage\":\"".$C_ppage."\",\"C_mark\":\"".$C_mark."\",\"C_m_position\":\"".$C_m_position."\",\"C_m_text\":\"".$C_m_text."\",\"C_m_font\":\"".$C_m_font."\",\"C_m_size\":\"".$C_m_size."\",\"C_m_color\":\"".$C_m_color."\",\"C_m_logo\":\"".$C_m_logo."\",\"C_m_width\":\"".$C_m_width."\",\"C_m_height\":\"".$C_m_height."\",\"C_m_transparent\":\"".$C_m_transparent."\",\"C_jpeg\":\"".$C_jpeg."\",\"C_7PID\":\"".$C_7PID."\",\"C_7PKEY\":\"".$C_7PKEY."\",\"C_ds1\":\"".$C_ds1."\",\"C_ds2\":\"".$C_ds2."\",\"C_ds3\":\"".$C_ds3."\",\"C_7money\":\"".str_replace("@",'\r\n',gljson($C_7money))."\",\"C_qqkj\":\"".$C_qqkj."\",\"C_wxkj\":\"".$C_wxkj."\",\"C_psh\":\"".$C_psh."\",\"C_translate\":\"".$C_translate."\",\"C_memberbg\":\"".$C_memberbg."\",\"C_flag0\":\"".$C_flag0."\",\"C_flag1\":\"".$C_flag1."\",\"C_flag2\":\"".$C_flag2."\",\"C_hotwords\":\"".gljson(lang($C_hotwords))."\",\"C_nsorttitle\":\"".gljson(lang($C_nsorttitle))."\",\"C_nsortentitle\":\"".gljson(lang($C_nsortentitle))."\",\"C_psorttitle\":\"".gljson(lang($C_psorttitle))."\",\"C_psortentitle\":\"".gljson(lang($C_psortentitle))."\",\"C_wxapplogo\":\"".$C_wxapplogo."\",\"C_wxappno\":\"".$C_wxappno."\",\"C_wxcolor\":\"".$C_wxcolor."\",\"C_wxapptitle\":\"".$C_wxapptitle."\",\"C_wxappID\":\"".$C_wxappID."\",\"C_wxappSecret\":\"".$C_wxappSecret."\",\"C_wxapptabbar\":\"".$C_wxapptabbar."\",\"sms_rest\":\"".$sms_rest."\",\"C_langtitle0\":\"".splitx($C_langtitle,",",0)."\",\"C_langtitle1\":\"".splitx($C_langtitle,",",1)."\",\"C_langtitle2\":\"".splitx($C_langtitle,",",2)."\",\"C_langtag0\":\"".splitx($C_langtag,",",0)."\",\"C_langtag1\":\"".splitx($C_langtag,",",1)."\",\"C_langtag2\":\"".splitx($C_langtag,",",2)."\",\"C_reg1\":\"".$C_reg1."\",\"C_reg2\":\"".$C_reg2."\",\"C_reg3\":\"".$C_reg3."\",\"C_kfon\":\"".$C_kfon."\",\"C_osson\":\"".$C_osson."\",\"C_oss_id\":\"".gljson($C_oss_id)."\",\"C_oss_key\":\"".gljson($C_oss_key)."\",\"C_bucket\":\"".gljson($C_bucket)."\",\"C_region\":\"".gljson($C_region,gljson(lang($C_qq)))."\",\"C_regon\":\"".$C_regon."\",\"C_kefuyun\":\"".gljson($C_kefuyun)."\",\"C_close\":\"".$C_close."\",\"C_beian\":\"".gljson($C_beian)."\",\"C_postage\":\"".$C_postage."\",\"C_baoyou\":\"".$C_baoyou."\",\"C_checkaddress\":\"".$C_checkaddress."\",\"C_rate\":\"".$C_rate."\",\"C_https\":\"".$C_https."\",\"C_mipon\":\"".$C_mipon."\",\"C_mip_token\":\"".$C_mip_token."\",\"C_tj_account\":\"".gljson($C_tj_account)."\",\"C_tj_pwd\":\"".gljson($C_tj_pwd)."\",\"C_tj_id\":\"".gljson($C_tj_id)."\",\"C_tj_siteid\":\"".gljson($C_tj_siteid)."\",\"C_tj_token\":\"".gljson($C_tj_token)."\",\"C_qe_id\":\"".gljson($C_qe_id)."\",\"C_qe_key\":\"".gljson($C_qe_key)."\",\"C_bj_id\":\"".gljson($C_bj_id)."\",\"C_bj_key\":\"".gljson($C_bj_key)."\",\"C_dfon\":\"".gljson($C_dfon)."\",\"C_zzon\":\"".gljson($C_zzon)."\",\"C_shoukuan\":\"".gljson($C_shoukuan)."\",\"C_punlogin\":\"".$C_punlogin."\"}";

	break;

	case "index_tj":
	$data=getbody("https://api.baidu.com/json/tongji/v1/ReportService/getData",'{
	"header": {
		"username": "'.$C_tj_account.'",
		"password": "'.$C_tj_pwd.'",
		"token": "'.$C_tj_token.'",
		"account_type": 1
	},
	"body": {
		"site_id": "'.$C_tj_siteid.'",
		"start_date": "'.date("Ymd",time()).'",
		"end_date": "'.date("Ymd",time()).'",
		"metrics": "pv_count,ip_count",
		"method": "overview/getTimeTrendRpt"
	}
}');

	$data=str_replace("--","0",$data);
	$data=json_decode($data,true);

	$data=$data["body"]["data"][0]["result"]["items"][1];
if($data){

	for($i=0;$i<count($data);$i++){
		$a=$a.$data[$i][0].",";
		$b=$b.$data[$i][1].",";

		$c=$c+$data[$i][0];
		$d=$d+$data[$i][1];
	}

	$a= substr($a,0,strlen($a)-1);
	$b= substr($b,0,strlen($b)-1);

	echo "{\"a\":[".$a."],\"b\":[".$b."],\"c\":".$c.",\"d\":".$d."}";
}else{
	echo "{\"a\":[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],\"b\":[0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0],\"c\":0,\"d\":0}";
}
	break;
	case "index_message":
	$sql="Select * from ".TABLE."guestbook order by G_id desc limit 5";
	$result = mysqli_query($conn, $sql);

	if(mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
		$G_sh=$row["G_sh"];
			if($G_sh==0){
				$G_shinfo="<font color='#FF0000'>未审核</font>";
			}else{
				$G_shinfo="<font color='#009900'>已审核</font>";
			}

			$message=$message."{\"G_title\":\"".gljson($row["G_title"])."\",\"G_time\":\"".$row["G_time"]."\",\"G_sh\":\"".$G_shinfo."\",\"G_id\":\"".$row["G_id"]."\"},";
		}
		$message= substr($message,0,strlen($message)-1);
	}
		echo "{\"message\":[".$message."]}";

	break;

	case "zmt_article":
	$sql="select N_pic,N_title,N_id from ".TABLE."news";
	$result = mysqli_query($conn, $sql);
	$arr = array();  
	while($row = mysqli_fetch_array($result)) {
	$count=count($row);
	  for($i=0;$i<$count;$i++){ 
	    unset($row[$i]);
	  }   
	$row["N_title"]=lang($row["N_title"]);
    array_push($arr,$row);
	} 
	echo json_encode($arr);
	break;

	case "index_orders":
	$sql="select * from ".TABLE."orders,".TABLE."product,".TABLE."member where M_del=0 and P_del=0 and O_member=M_id and O_pid=P_id order by O_id desc limit 5";

$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {

	if($row["O_state"]==0){
	$O_state="未付款";

	}

	if($row["O_state"]==1){
	$O_state="已付款";

	}

	if($row["O_state"]==2){
	$O_state="已发货";

	}

	if($row["O_state"]==3){
	$O_state="已确认";

	}

	if($row["O_state"]==4){
	$O_state="申请退款";

	}

	if($row["O_state"]==5){
	$O_state="已退款";

	}

	if($row["O_state"]==6){
	$O_state="货到付款";

	}

	if($row["O_state"]==7){
	$O_state="转账汇款";

	}

	$orders=$orders."{\"P_title\":\"".gljson(lang($row["P_title"]))."\",\"O_state\":\"".$O_state."\",\"O_all\":\"".$row["O_num"]*$row["O_price"]."\",\"M_id\":\"".$row["M_id"]."\",\"M_login\":\"".gljson($row["M_login"])."\",\"O_time\":\"".$row["O_time"]."\"},";

}
$orders= substr($orders,0,strlen($orders)-1);
}

echo "{\"orders\":[".$orders."]}";

break;


case "index_member";
$sql="select * from ".TABLE."member where M_del=0 and not M_login='未提供' and not M_login='admin' order by M_id desc limit 20";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
if(substr($row["M_pic"],0,4)!="http"){
$M_pic=$C_dir."media/".$row["M_pic"];
}else{
$M_pic=$row["M_pic"];
}
$memberx=$memberx."{\"M_id\":\"".$row["M_id"]."\",\"M_pic\":\"".$M_pic."\",\"M_login\":\"".gljson($row["M_login"])."\",\"M_regtime\":\"".date_format(date_create($row["M_regtime"]),"Y-m-d")."\"},";
}

$memberx= substr($memberx,0,strlen($memberx)-1);

}
echo "{\"member\":[".$memberx."]}";
break;

case "index_file":
$handler = opendir('../');

while( ($filename = readdir($handler)) !== false ) 
{
 if(is_dir("../".$filename) && $filename != "." && $filename != "..")
 {  
   $files=$files."{\"F_name\":\"".$filename."\",\"F_size\":\"".ShowSpaceInfo(getDirSize("../".$filename))."\"},";
  }
}

$files= substr($files,0,strlen($files)-1);

echo "{\"files\":[".$files."]}";
break;

case "getip":
$info=GetBody("http://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$C_wx_appid."&secret=".$C_wx_appsecret,"");

if(strpos($info,"40164")!==false){
	echo "您的源IP为：".splitx(splitx(json_decode($info)->errmsg,"ip ",1),",",0);
}else{
	echo "您的IP已经加入了微信白名单";
}
break;

case "agreement":
echo "{\"agreement\":\"".str_Replace(PHP_EOL,'\n',file_get_contents("../member/agreement.txt"))."\"}";
break;


case "pagelist":
$sql="select * from ".TABLE."collection where C_id=".$id;
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
	$C_url=$row["C_url"];
	$C_start=$row["C_start"];
	$C_end=$row["C_end"];
	$C_titlestart=$row["C_titlestart"];
	$C_titleend=$row["C_titleend"];
	$C_contentstart=$row["C_contentstart"];
	$C_contentend=$row["C_contentend"];
	$C_pic=$row["C_pic"];
	$C_nsort=$row["C_nsort"];
	$C_code=$row["C_code"];
	$C_pagestart=$row["C_pagestart"];
	$C_pageend=$row["C_pageend"];
}


for($j=$C_pagestart;$j<=$C_pageend;$j++){
	$list=getbody(str_replace("{page}",$j,$C_url),"","GET");
	$list=splitx($list,$C_start,1);
	$list=splitx($list,$C_end,0);
	$list=str_replace("'","\"",$list);
	$list=explode("href=\"",$list);

	for ($i=1;$i<= count($list);$i++){
		if(strpos(splitx($list[$i],"\"",0),"http")===false){ //如果网址中不包含http
			if(substr(splitx($list[$i],"\"",0),0,2)=="//"){
				$urlx="http:".splitx($list[$i],"\"",0);
			}else{
				if(substr(splitx($list[$i],"\"",0),0,1)=="/"){
					$urlx="http://".splitx($C_url,"/",2).splitx($list[$i],"\"",0);
				}else{
					$urlx=str_replace(splitx($C_url,"/",count(explode("/",$C_url))-1),"",$C_url).splitx($list[$i],"\"",0);
				}
			}
		}else{
			$urlx=splitx($list[$i],"\"",0);
		}
		$list_info=$list_info.$urlx."|";
	}
}

$list_info= substr($list_info,0,strlen($list_info)-1);

echo $list_info;
break;

case "testcontent":
$sql="select * from ".TABLE."collection where C_id=".$id;
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
	$C_url=$row["C_url"];
	$C_start=$row["C_start"];
	$C_end=$row["C_end"];
	$C_titlestart=$row["C_titlestart"];
	$C_titleend=$row["C_titleend"];
	$C_contentstart=$row["C_contentstart"];
	$C_contentend=$row["C_contentend"];
	$C_timestart=$row["C_timestart"];
	$C_timeend=$row["C_timeend"];
	$C_pic=$row["C_pic"];
	$C_nsort=$row["C_nsort"];
	$C_code=$row["C_code"];
}

$list=getbody(str_replace("{page}","1",$C_url),"","GET");
$list=splitx($list,$C_start,1);
$list=splitx($list,$C_end,0);
$list=str_replace("'","\"",$list);
$list=explode("href=\"",$list);

for ($i=1;$i< count($list);$i++){

	if(strpos(splitx($list[$i],"\"",0),"http")===false){
		if(substr(splitx($list[$i],"\"",0),0,1)=="/"){
			$urlx="http://".splitx($C_url,"/",2).splitx($list[$i],"\"",0);
		}else{
			$urlx=str_replace(splitx($C_url,"/",count(explode("/",$C_url))-1),"",$C_url).splitx($list[$i],"\"",0);
		}
	}else{
		$urlx=splitx($list[$i],"\"",0);
	}
	$list_info=$list_info.$urlx.PHP_EOL;
}
$page=explode(PHP_EOL,$list_info);

for($i=0;$i<= 0;$i++){
	$content=get_gb_to_utf8(getbody($page[$i],"","GET"));
	$content=str_Replace(PHP_EOL,"",$content);
	if(strpos($content,$C_titlestart)!==false && strpos($content,$C_contentstart)!==false){
		$page_title=strip_tags(splitx(splitx($content,$C_titlestart,1),$C_titleend,0));
		if($C_timestart!="" && $C_timeend!=""){
			$page_time=splitx(splitx($content,$C_timestart,1),$C_timeend,0);
		}else{
			$page_time = date('Y-m-d H:i:s');
		}

		$page_content=splitx(splitx($content,$C_contentstart,1),$C_contentend,0);
		$src=explode(" src=\"",$page_content);
		for ($j=1;$j<count($src);$j++){

			$path=str_replace(splitx($page[$i],"/",count(explode("/",$page[$i]))),"",$page[$i]);
			if(substr(splitx($src[$j],"\"",0),0,4)=="http" || substr(splitx($src[$j],"\"",0),0,2)=="//"){
				$srcx=splitx($src[$j],"\"",0);
			}else{

				if(substr(splitx($src[$j], "\"", 0),0,1)=="/"){
                    $srcx = "http://".splitx($page[$i],"/",2).splitx($src[$j], "\"", 0);
                }else{
                    $srcx = str_replace(splitx($page[$i],"/",count(explode("/",$page[$i]))-1),"",$page[$i]) . splitx($src[$j], "\"", 0);
                }

			}
			$page_content=str_Replace(splitx($src[$j],"\"",0),$srcx,$page_content);
		}
	}
}
echo "<h3>".$page_title."</h3><p>".$page_time."</p><p>".$page_content."</p>";
die();
break;

case "getcontent":
echo getbody(str_replace("{page}","1",$_REQUEST["url"]),"","GET");
break;

case "collection":

$sql="select * from ".TABLE."collection,".TABLE."nsort where C_nsort=S_id and S_del=0 order by C_id desc";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
	if($row["C_pic"]==1){
		$C_pic="开启";
	}else{
		$C_pic="关闭";
	}

	$textlist=$textlist."{\"C_id\":\"".$row["C_id"]."\",\"C_title\":\"".$row["C_title"]."\",\"C_url\":\"".$row["C_url"]."\",\"C_pagestart\":\"".$row["C_pagestart"]."\",\"C_pageend\":\"".$row["C_pageend"]."\",\"C_pic\":\"".$C_pic."\",\"C_code\":\"".$row["C_code"]."\",\"S_title\":\"".lang($row["S_title"])."\"},";
}

$textlist= substr($textlist,0,strlen($textlist)-1);

}

$sql="select count(C_id) as C_count from ".TABLE."collection";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$C_count=$row["C_count"];

$sql="select * from ".TABLE."collection,".TABLE."nsort where C_nsort=S_id and S_del=0 order by C_id desc limit 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$C_first=$row["C_id"];

echo "{\"collection\":[".$textlist."],\"count\":\"".$C_count."\",\"first\":\"".$C_first."\"}";
break;
case "collection_add":
$sql="select * from ".TABLE."collection where C_id=".$id;
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if (mysqli_num_rows($result) > 0) {
	echo json_encode($row);
}else{
	echo '{"C_id":"","C_title":"","C_url":"","C_titlestart":"","C_titleend":"","C_contentstart":"","C_contentend":"","C_start":"","C_end":"","C_pic":"0","C_nsort":"1","C_code":"utf-8","C_pagestart":"","C_pageend":"","C_timestart":"","C_timeend":""}';
}
break;

case "text_list";
if(strpos($_COOKIE["textauth"],"all")!==false){
$auth_info="";
}else{
$textauth=explode(",",$_COOKIE["textauth"]);
for ($i=0;$i<count($textauth)-1;$i++){
$tj=$tj."or T_id=".intval($textauth[$i])." ";
}
if($tj==""){
$auth_info=" and T_id<0";
}else{
$tj= substr($tj,-(strlen($tj)-3));
$auth_info=" and (".$tj.")";
}
}
$sql="select * from ".TABLE."text where T_id<>0 ".$auth_info." and T_del=0 order by T_order,T_id desc";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {

$textlist=$textlist."{\"T_id\":\"".$row["T_id"]."\",\"T_title\":\"".gljson(lang($row["T_title"]))."\",\"T_entitle\":\"".gljson(lang($row["T_entitle"]))."\",\"T_pic\":\"".$row["T_pic"]."\",\"T_order\":\"".$row["T_order"]."\"},";

}

$textlist= substr($textlist,0,strlen($textlist)-1);

}
echo "{\"text_list\":[".$textlist."]}";

break;
case "bbs_add":
if(strpos($_COOKIE["bbsauth"],"all")!==false){
$auth_info="";
}else{
$bbsauth=explode(",",$_COOKIE["bbsauth"]);
for ($i=0;$i<count($bbsauth)-1;$i++){
$tj=$tj."or T_id=".intval($bbsauth[$i])." ";
}
if($tj==""){
$auth_info=" and B_id<0";
}else{
$tj= substr($tj,-(strlen($tj)-3));
$auth_info=" and (".$tj.")";
}
}
if($id!="" && $id!="0"){
$sql="select * from ".TABLE."bbs,".TABLE."bsort where S_del=0 and B_del=0 and B_sort=S_id and B_id=".$id.$auth_info;
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$B_title=$row["B_title"];
$B_time=$row["B_time"];
$B_view=$row["B_view"];
$B_sort=$row["B_sort"];
$B_mid=$row["B_mid"];
$B_sh=$row["B_sh"];
$S_title=lang($row["S_title"]);
$B_member=getrx("select * from ".TABLE."member where M_id=".$row["B_mid"],"M_login");
$B_content=str_Replace("{@SL_安装目录}",$C_dir,lang($row["B_content"]));
$sort_info=" and B_sort=".$row["B_sort"];
}
}else{
$B_member="管理员";
$sort_info="";
$B_time=date('Y-m-d H:i:s');
$B_view=100;
$B_sh=1;
}
$sql="select * from ".TABLE."bbs where B_del=0 and B_id>0 ".$sort_info." order by B_id desc";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$bbslist=$bbslist."{\"B_id\":\"".$row["B_id"]."\",\"B_title\":\"".lang($row["B_title"])."\"},";
}

$bbslist= substr($bbslist,0,strlen($bbslist)-1);

}
echo "{\"B_id\":\"".$id."\",\"B_time\":\"".$B_time."\",\"B_view\":\"".$B_view."\",\"B_sort\":\"".$B_sort."\",\"B_sh\":\"".$B_sh."\",\"B_member\":\"".$B_member."\",\"B_title\":\"".gljson(lang($B_title))."\",\"B_content\":\"".gljson($B_content)."\",\"S_title\":\"".$S_title."\",\"bbs_list\":[".$bbslist."]}";
break;
case "bbs_list":
if(strpos($_COOKIE["bbsauth"],"all")!==false){
$auth_info="";
}else{
$bbsauth=explode(",",$_COOKIE["bbsauth"]);
for ($i=0;$i<count($bbsauth)-1;$i++){
$tj=$tj."or S_id=".intval($bbsauth[$i])." ";
}
if($tj==""){
$auth_info=" and S_id<0";
}else{
$tj= substr($tj,-(strlen($tj)-3));
$auth_info=" and (".$tj.")";
}
}
if($id=="" || $id=="0"){
$aa="";
}else{
$aa="and S_id=".$id;
}
if($page==""){
$page=1;
}
if($page=="all"){
$sql="select * from ".TABLE."bbs,".TABLE."bsort where S_del=0 and B_del=0 and B_sort=S_id ".$aa.$auth_info." order by B_id desc";
}else{
if($page==1){
$sql="select * from ".TABLE."bbs,".TABLE."bsort where S_del=0 and B_del=0 and B_sort=S_id ".$aa.$auth_info." order by B_id desc limit 10";
}else{
$sql="select * from ".TABLE."bbs,".TABLE."bsort where S_del=0 and B_del=0 and B_sort=S_id ".$aa.$auth_info." order by B_id desc limit ".($page*10-10).",10";
}
}

$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$bbslist=$bbslist."{\"B_id\":\"".$row["B_id"]."\",\"B_sort\":\"".lang($row["S_title"])."\",\"B_time\":\"".$row["B_time"]."\",\"B_sh\":\"".$row["B_sh"]."\",\"B_title\":\"".lang($row["B_title"])."\",\"B_mid\":\"".$row["B_mid"]."\",\"B_member\":\"".getrx("select * from ".TABLE."member where M_id=".$row["B_mid"],"M_login")."\"},";
}
$bbslist= substr($bbslist,0,strlen($bbslist)-1);

}
$sql="select count(B_id) as B_count from ".TABLE."bbs,".TABLE."bsort where S_del=0 and B_del=0 and B_sort=S_id ".$aa.$auth_info;

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$B_count=$row["B_count"];
echo "{\"bbs_list\":[".$bbslist."],\"count\":\"".$B_count."\"}";
break;
case "bsort_list":
if(strpos($_COOKIE["bbsauth"],"all")!==false){
$auth_info="";
}else{
$bbsauth=explode(",",$_COOKIE["bbsauth"]);
for ($i=0;$i<count($bbsauth)-1;$i++){
$tj=$tj."or S_id=".intval($bbsauth[$i])." ";
}
if($tj==""){
$auth_info=" and S_id<0";
}else{
$tj= substr($tj,-(strlen($tj)-3));
$auth_info=" and (".$tj.")";
}
}
$sql="Select * from ".TABLE."bsort where S_del=0 and S_id>0 ".$auth_info." order by S_order,S_id desc";

$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
if($row["S_lv"]==0){
$lv_info="无限制";
}else{
$lv_info=getrx("select * from ".TABLE."lv where L_id=".$row["S_lv"],"L_title");
}

$sql3="select count(*) as S_count from ".TABLE."bbs where B_del=0 and B_sort=".$row["S_id"];
$result3 = mysqli_query($conn, $sql3);
$row3 = mysqli_fetch_assoc($result3);
$S_count=$row3["S_count"];

$bsortlist=$bsortlist. "{\"S_id\":".$row["S_id"].",\"S_order\":".$row["S_order"].",\"S_count\":".$S_count.",\"S_title\":\"".lang($row["S_title"])."\",\"S_pic\":\"".$row["S_pic"]."\",\"S_lv\":\"".$lv_info."\"},";
}
$bsortlist= substr($bsortlist,0,strlen($bsortlist)-1);

}
echo "{\"bsort_list\":[".$bsortlist."]}";
break;
case "wxapp":
$tab=explode(",",$C_wxapptabbar);
for ($i=0;$i<count($tab);$i++) {
if(is_numeric($tab[$i])){
$tabs=$tabs."{\"U_title\":\"".gljson(lang(getrx("select * from ".TABLE."menu where U_del=0 and U_id=".$tab[$i],"U_title")))."\",\"U_ico\":\"".gljson(getrx("select * from ".TABLE."menu where U_id=".$tab[$i],"U_ico"))."\"},";
}else{
switch($tab[$i]){

case "m":
$tabs=$tabs."{\"U_title\":\"会员中心\",\"U_ico\":\"user\"},";
break;
case "s":
$tabs=$tabs."{\"U_title\":\"搜索内容\",\"U_ico\":\"search\"},";
break;
case "c":
$tabs=$tabs."{\"U_title\":\"购物车\",\"U_ico\":\"shopping-cart\"},";
break;
}
}
}

$tabs= substr($tabs,0,strlen($tabs)-1);

echo "{\"tabs\":[".$tabs."]}";
break;
case "text_add":
if(strpos($_COOKIE["textauth"],"all")!==false){
$auth_info="";
}else{
$textauth=explode(",",$_COOKIE["textauth"]);
for ($i=0;$i<count($textauth)-1;$i++){
$tj=$tj."or T_id=".intval($textauth[$i])." ";
}
if($tj==""){
$auth_info=" and T_id<0";
}else{
$tj= substr($tj,-(strlen($tj)-3));
$auth_info=" and (".$tj.")";
}
}
if($id!="" && $id!="0"){
$sql="select * from ".TABLE."text where T_del=0 and T_id=".$id.$auth_info;
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$T_title=$row["T_title"];
$T_entitle=$row["T_entitle"];
$T_pagetitle=$row["T_pagetitle"];
$T_keywords=$row["T_keywords"];
$T_description=$row["T_description"];
$T_pic=$row["T_pic"];
$T_link=$row["T_link"];
$T_content=str_Replace("{@SL_安装目录}",$C_dir,lang($row["T_content"]));
}
}else{
$T_pic="images/nopic.png";
}
echo "{\"T_id\":\"".$id."\",\"T_title\":\"".gljson(lang($T_title))."\",\"T_entitle\":\"".gljson(lang($T_entitle))."\",\"T_pagetitle\":\"".gljson(lang($T_pagetitle))."\",\"T_keywords\":\"".gljson(lang($T_keywords))."\",\"T_description\":\"".gljson(lang($T_description))."\",\"T_pic\":\"".$T_pic."\",\"T_link\":\"".$T_link."\",\"T_content\":\"".gljson($T_content)."\"}";

break;


case "slide_list";
$sql="select * from ".TABLE."slide where S_del=0 order by S_id desc";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$slidelist=$slidelist."{\"S_id\":".$row["S_id"].",\"S_title\":\"".gljson(lang($row["S_title"]))."\",\"S_order\":".$row["S_order"].",\"S_pic\":\"".$row["S_pic"]."\"},";
}

$slidelist= substr($slidelist,0,strlen($slidelist)-1);

}
echo "{\"slide_list\":[".$slidelist."]}";
break;

case "slide_add":
$sql="select * from ".TABLE."slide where S_del=0 and S_id=".$id;
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$S_title=$row["S_title"];
$S_order=$row["S_order"];
$S_link=$row["S_link"];
$S_pic=$row["S_pic"];
$S_content=$row["S_content"];
}
echo "{\"S_id\":\"".$id."\",\"S_order\":\"".$S_order."\",\"S_title\":\"".lang($S_title)."\",\"S_link\":\"".$S_link."\",\"S_pic\":\"".$S_pic."\",\"S_content\":\"".gljson(lang($S_content))."\"}";
break;

case "mtype_add":
$sql="select * from ".TABLE."mtype where T_id=".$id;
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

echo json_encode($row);
break;

case "brand_add":
$sql="select * from ".TABLE."brand where B_id=".$id;
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
echo json_encode($row);
break;

case "m_slide_list";
$sql="select * from ".TABLE."wapslide order by S_id desc";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$slidelist=$slidelist."{\"S_id\":".$row["S_id"].",\"S_title\":\"".gljson(lang($row["S_title"]))."\",\"S_order\":".$row["S_order"].",\"S_pic\":\"".$row["S_pic"]."\"},";
}

$slidelist= substr($slidelist,0,strlen($slidelist)-1);

}
echo "{\"slide_list\":[".$slidelist."]}";
break;

case "m_slide_add":
$sql="select * from ".TABLE."wapslide where S_id=".$id;
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$S_title=$row["S_title"];
$S_order=$row["S_order"];
$S_link=$row["S_link"];
$S_pic=$row["S_pic"];
$S_content=$row["S_content"];
}
echo "{\"S_id\":\"".$id."\",\"S_order\":\"".$S_order."\",\"S_title\":\"".lang($S_title)."\",\"S_link\":\"".$S_link."\",\"S_pic\":\"".$S_pic."\",\"S_content\":\"".gljson(lang($S_content))."\"}";
break;


case "lsort_list":
$sql="Select * from ".TABLE."lsort order by S_order";
$result = mysqli_query($conn,  $sql);
if (mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$lsortlists=$lsortlists. "{\"S_id\":".$row["S_id"].",\"S_title\":\"".lang($row["S_title"])."\",\"S_order\":".$row["S_order"]."},";
}
$lsortlists= substr($lsortlists,0,strlen($lsortlists)-1);
}
echo "{\"lsort_lists\":[".$lsortlists."]}";
break;

case "lsort_add":
$sql="select * from ".TABLE."lsort where S_id=".$id;
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$S_title=lang($row["S_title"]);
$S_order=$row["S_order"];
}
echo "{\"S_title\":\"".$S_title."\",\"S_order\":\"".$S_order."\"}";
break;
case "qsort_add":
$sql="select * from ".TABLE."qsort where S_id=".$id;
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$S_title=lang($row["S_title"]);
$S_content=$row["S_content"];
}
echo "{\"S_title\":\"".$S_title."\",\"S_content\":\"".$S_content."\"}";
break;

case "query_list":
if($page==""){
	$page="1";
}
if($id=="" || $id=="0"){
$aa="";
}else{
$aa="and Q_sort=".$id;
}

if($page=="1"){
	$sql="select * from ".TABLE."query where Q_id>0 ".$aa." order by Q_id desc limit 10";
}else{
	$sql="select * from ".TABLE."query where Q_id>0 ".$aa." order by Q_id desc limit ".($page*10-10).",10";
}

$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$news_sort="<select name='sort_".$row["Q_id"]."'>";
$sql4="Select * from ".TABLE."qsort order by S_id desc";
$result4 = mysqli_query($conn,  $sql4);
if (mysqli_num_rows($result4) > 0) {
while($row4 = mysqli_fetch_assoc($result4)) {
if($row["Q_sort"]==$row4["S_id"]){
$sort_select="selected='selected'";
}else{
$sort_select="";
}
$news_sort=$news_sort."<option value='".$row4["S_id"]."' ".$sort_select.">".lang($row4["S_title"])."</option>";

}
}

$news_sort=$news_sort."</optgroup>";
$news_sort=$news_sort."</select>";

$Q_content=str_replace("__"," ",str_replace("|","<br>",$row["Q_content"]));

$sql2="select * from ".TABLE."response where R_rid='".$row["Q_code"]."'";
	$result2 = mysqli_query($conn, $sql2);
	if(mysqli_num_rows($result2) > 0) {
		while($row2 = mysqli_fetch_assoc($result2)) {
			$res=$res.lang(getrx("select * from ".TABLE."content where C_del=0 and C_id=".$row2["R_cid"],"C_title"))."：".$row2["R_content"]."<br>";

		}
	}

	$res= substr($res,0,strlen($res)-4);


$linklist=$linklist."{\"Q_id\":".$row["Q_id"].",\"Q_code\":\"".gljson($row["Q_code"])."\",\"Q_content\":\"".gljson($Q_content)."\",\"Q_c\":\"".gljson($res)."\",\"Q_times\":\"".$row["Q_times"]."\",\"Q_first\":\"".$row["Q_first"]."\",\"Q_sort\":\"".$news_sort."\"},";
$res="";
}

$linklist= substr($linklist,0,strlen($linklist)-1);
}

$sql="select count(Q_id) as Q_count from ".TABLE."query where Q_id>0 ".$aa." order by Q_id desc";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$Q_count=$row["Q_count"];

echo "{\"query_list\":[".$linklist."],\"count\":\"".$Q_count."\"}";
break;

case "link_list":
if($id=="" || $id=="0"){
$aa="";
}else{
$aa="and L_sort=".$id;
}
$sql="select * from ".TABLE."link where L_del=0 and L_id>0 ".$aa." order by L_order asc,L_id desc";

$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$news_sort="<select name='sort_".$row["L_id"]."'>";
$sql4="Select * from ".TABLE."lsort order by S_order";

$result4 = mysqli_query($conn, $sql4);

if (mysqli_num_rows($result4) > 0) {
while($row4 = mysqli_fetch_assoc($result4)) {
if($row["L_sort"]==$row4["S_id"]){
$sort_select="selected='selected'";
}else{
$sort_select="";
}
$news_sort=$news_sort."<option value='".$row4["S_id"]."' ".$sort_select.">".lang($row4["S_title"])."</option>";

}
}

$news_sort=$news_sort."</optgroup>";
$news_sort=$news_sort."</select>";
$linklist=$linklist."{\"L_id\":".$row["L_id"].",\"L_order\":".$row["L_order"].",\"L_title\":\"".lang($row["L_title"])."\",\"L_url\":\"".gljson($row["L_url"])."\",\"L_pic\":\"".$row["L_pic"]."\",\"L_sort\":\"".$news_sort."\"},";
}

$linklist= substr($linklist,0,strlen($linklist)-1);


}
echo "{\"link_list\":[".$linklist."]}";
break;
case "link_add":
$sql="select * from ".TABLE."link where L_del=0 and L_id=".$id;
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$L_title=$row["L_title"];
$L_url=$row["L_url"];
$L_pic=$row["L_pic"];
$L_sort=$row["L_sort"];
}
echo "{\"L_id\":\"".$id."\",\"L_title\":\"".lang($L_title)."\",\"L_url\":\"".gljson($L_url)."\",\"L_pic\":\"".$L_pic."\",\"L_sort\":\"".$L_sort."\"}";
break;

case "query_add":

if($id==""){
	$id=0;
}
$sql="select * from ".TABLE."query where Q_id=".$id;
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);


	$Q_code=$row["Q_code"];
	$Q_content=$row["Q_content"];
	$Q_sort=$row["Q_sort"];
	$qcode=$qcode."<table class=\"table table-hover\" id=\"pic1\">";
	if($Q_content!=""){

		$code=explode("|",$Q_content);

		for($j = 0; $j<count($code);$j++){

			if(strpos($code[$j],"__")!==false){
				$Q_time=splitx($code[$j],"__",0);
				$Q_result=splitx($code[$j],"__",1);
			}else{
				$Q_time="";
				$Q_result=splitx($code[$j],"__",0);
			}

			$qcode=$qcode."<tr id='pp".$j."' ><td ><div class=\"input-group\"><span class=\"input-group-addon\">时间</span><input name=\"picpic1_".$j."\" id=\"picpic1_".$j."\" class=\"form-control\" value=\"".$Q_time."\"></div></td>";
			$qcode=$qcode."<td><div class=\"input-group\"><span class=\"input-group-addon\">结果</span><input name=\"itrpic1_".$j."\" id=\"itrpic1_".$j."\" class=\"form-control\" value=\"".$Q_result."\"></div>";
			$qcode=$qcode. "</td><td><input type='button' value='- 删掉该行' class='add' onclick='DelPic(".$j.")' style='margin:5px;'/></td></tr>";
		}
	}else{
		$qcode=$qcode. "<tr id='pp0'><td ><div class=\"input-group\"><span class=\"input-group-addon\">时间</span><input name=\"picpic1_0\" id=\"picpic1_0\" class=\"form-control\" value=\"\"></div></td>";
		$qcode=$qcode."<td><div class=\"input-group\"><span class=\"input-group-addon\">结果</span><input name=\"itrpic1_0\" id=\"itrpic1_0\" class=\"form-control\" value=\"\"></div>";
		$qcode=$qcode."</td><td><input type='button' value='- 删掉该行' class='add' onclick='DelPic(0)' style='margin:5px;'/></td></tr>";
	}

	$qcode=$qcode."</table>";

	$sql2="select * from ".TABLE."response where R_rid='".$Q_code."'";
	$result2 = mysqli_query($conn, $sql2);
	if(mysqli_num_rows($result2) > 0) {
		while($row2 = mysqli_fetch_assoc($result2)) {
			$res=$res."{\"R_ctitle\":\"".lang(getrx("select * from ".TABLE."content where C_del=0 and C_id=".$row2["R_cid"],"C_title"))."\",\"R_response\":\"".$row2["R_content"]."\"},";
		}
	}

	$res= substr($res,0,strlen($res)-1);



echo "{\"Q_id\":\"".$id."\",\"Q_code\":\"".gljson($Q_code)."\",\"Q_content\":\"".gljson($qcode)."\",\"Q_sort\":\"".$Q_sort."\",\"Q_res\":[".$res."]}";

break;


case "news_search":

$S_key=$_GET["S_key"];

if(strpos($_COOKIE["newsauth"],"all")!==false){
	$auth_info="";
}else{

$newsauth=explode(",",$_COOKIE["newsauth"]);

for($i=0;$i<count($newsauth)-1;$i++){
	$tj=$tj."or N_sort=".intval($newsauth[$i])." ";
}

if($tj==""){
		$auth_info=" and N_id<0";
	}else{
		$tj= substr($tj,-(strlen($tj)-3));
		$auth_info=" and (".$tj.")";
	}

}

if($id=="" || $id=="0"){
	$aa="";
}else{
	$aa="and S_id=".$id;
}


if($page==""){
	$page="1";
}

if($page=="all"){
	$sql="select * from ".TABLE."news,".TABLE."nsort where N_title like '%".$S_key."%' and N_sort=S_id ".$aa.$auth_info." and S_del=0 and N_del=0 order by N_order,N_id desc";
}else{
	if($page=="1"){
		$sql="select * from ".TABLE."news,".TABLE."nsort where N_title like '%".$S_key."%' and N_sort=S_id ".$aa.$auth_info." and S_del=0 and N_del=0 order by N_order,N_id desc limit 10";
	}else{
		$sql="select * from ".TABLE."news,".TABLE."nsort where N_title like '%".$S_key."%' and N_sort=S_id ".$aa.$auth_info." and S_del=0 and N_del=0 order by N_order,N_id desc limit ".($page*10-10).",10";
	}
}

$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$news_sort="<select name='sort_".$row["N_id"]."'>";

$sql3="Select * from ".TABLE."nsort where S_sub=0 and S_del=0 order by S_order,S_id desc";
$result3 = mysqli_query($conn,  $sql3);
if (mysqli_num_rows($result3) > 0) {
while($row3 = mysqli_fetch_assoc($result3)) {
$news_sort=$news_sort."<optgroup label=\"".lang($row3["S_title"])."\">";
$sql4="Select * from ".TABLE."nsort where S_sub=".$row3["S_id"]." and S_del=0 order by S_order,S_id desc";

$result4 = mysqli_query($conn,  $sql4);
if (mysqli_num_rows($result4) > 0) {
while($row4 = mysqli_fetch_assoc($result4)) {
	if($row["N_sort"]==$row4["S_id"]){
		$sort_select="selected='selected'";
	}else{
		$sort_select="";
	}
$news_sort=$news_sort."<option value='".$row4["S_id"]."' ".$sort_select.">".lang($row4["S_title"])."</option>";

}
}

$news_sort=$news_sort."</optgroup>";

}
}

$news_sort=$news_sort."</select>";
if($row["N_top"]==1){
	$sort_select1="selected=\"selected\"";
	$sort_select0="";
}else{
	$sort_select0="selected=\"selected\"";
	$sort_select1="";
}
$news_top="<select name=\"top_".$row["N_id"]."\">";
$news_top=$news_top."<option value=\"1\" ".$sort_select1.">置顶</option>";
$news_top=$news_top."<option value=\"0\" ".$sort_select0.">取消</option>";
$news_top=$news_top."</select>";
if($row["N_sh"]==0){
	$sh_info="<font color=\"#009900\">已通过</font>";
	$bg="#ffffff";
}
if($row["N_sh"]==1){
	$sh_info="<font color=\"#ff0000\">未通过</font>";
	$bg="#FFEEEE";
}
if($row["N_sh"]==2){
	$sh_info="<font color=\"#ff9900\">未审核</font>";
	$bg="#FFEED5";
}

if($row["N_lv"]==0){
	$lv_info="无限制";
}else{
	$lv_info="<b>".lang(getrx("select * from ".TABLE."lv where L_id=".$row["N_lv"],"L_title"))."</b>";
}

switch($row["N_type"]){
	case 0:
	$N_type="普通新闻";
	break;
	case 1:
	$N_type="招聘信息";
	break;
	case 2:
	$N_type="文件下载";
	break;
	case 3:
	$N_type="视频播放";
	break;
	case 4:
	$N_type="通知公告";
	break;
	default:
	$N_type="普通新闻";
}


$newslist=$newslist."{\"N_id\":".$row["N_id"].",\"N_order\":".$row["N_order"].",\"N_title\":\"".gljson(lang($row["N_title"]))."\",\"N_sh\":\"".gljson($sh_info)."\",\"N_lv\":\"".gljson($lv_info)."\",\"N_top\":\"".gljson($news_top)."\",\"N_type\":\"".$N_type."\",\"N_pic\":\"".$row["N_pic"]."\",\"N_sort\":\"".gljson($news_sort)."\"},";
}

$newslist= substr($newslist,0,strlen($newslist)-1);
}

$sql="select count(N_id) as N_count from ".TABLE."news,".TABLE."nsort where N_title like '%".$S_key."%' and S_del=0 and N_del=0 and N_sort=S_id ".$aa.$auth_info;
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$N_count=$row["N_count"];

echo "{\"news_list\":[".$newslist."],\"count\":\"".$N_count."\"}";

break;


case "news_list";

if(strpos($_COOKIE["newsauth"],"all")!==false){
	$auth_info="";
}else{

$newsauth=explode(",",$_COOKIE["newsauth"]);

for($i=0;$i<count($newsauth)-1;$i++){
	$tj=$tj."or N_sort=".intval($newsauth[$i])." ";
}

if($tj==""){
		$auth_info=" and N_id<0";
	}else{
		$tj= substr($tj,-(strlen($tj)-3));
		$auth_info=" and (".$tj.")";
	}

}

if($id=="" || $id=="0"){
	$aa="";
}else{
	$aa="and S_id=".$id;
}


if($page==""){
	$page="1";
}

if($page=="all"){
	$sql="select * from ".TABLE."news,".TABLE."nsort where N_sort=S_id ".$aa.$auth_info." and S_del=0 and N_del=0 order by N_order,N_id desc";
}else{
	if($page=="1"){
		$sql="select * from ".TABLE."news,".TABLE."nsort where N_sort=S_id ".$aa.$auth_info." and S_del=0 and N_del=0 order by N_order,N_id desc limit 10";
	}else{
		$sql="select * from ".TABLE."news,".TABLE."nsort where N_sort=S_id ".$aa.$auth_info." and S_del=0 and N_del=0 order by N_order,N_id desc limit ".($page*10-10).",10";
	}
}


$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$news_sort="<select name='sort_".$row["N_id"]."'>";

$sql3="Select * from ".TABLE."nsort where S_sub=0 and S_del=0 order by S_order,S_id desc";
$result3 = mysqli_query($conn,  $sql3);
if (mysqli_num_rows($result3) > 0) {
while($row3 = mysqli_fetch_assoc($result3)) {
$news_sort=$news_sort."<optgroup label=\"".lang($row3["S_title"])."\">";
$sql4="Select * from ".TABLE."nsort where S_sub=".$row3["S_id"]." and S_del=0 order by S_order,S_id desc";

$result4 = mysqli_query($conn,  $sql4);
if (mysqli_num_rows($result4) > 0) {
while($row4 = mysqli_fetch_assoc($result4)) {
	if($row["N_sort"]==$row4["S_id"]){
		$sort_select="selected='selected'";
	}else{
		$sort_select="";
	}
$news_sort=$news_sort."<option value='".$row4["S_id"]."' ".$sort_select.">".lang($row4["S_title"])."</option>";

}
}

$news_sort=$news_sort."</optgroup>";

}
}

$news_sort=$news_sort."</select>";
if($row["N_top"]==1){
	$sort_select1="selected=\"selected\"";
	$sort_select0="";
}else{
	$sort_select0="selected=\"selected\"";
	$sort_select1="";
}
$news_top="<select name=\"top_".$row["N_id"]."\">";
$news_top=$news_top."<option value=\"1\" ".$sort_select1.">置顶</option>";
$news_top=$news_top."<option value=\"0\" ".$sort_select0.">取消</option>";
$news_top=$news_top."</select>";
if($row["N_sh"]==0){
	$sh_info="<font color=\"#009900\">已通过</font>";
	$bg="#ffffff";
}
if($row["N_sh"]==1){
	$sh_info="<font color=\"#ff0000\">未通过</font>";
	$bg="#FFEEEE";
}
if($row["N_sh"]==2){
	$sh_info="<font color=\"#ff9900\">未审核</font>";
	$bg="#FFEED5";
}

if($row["N_lv"]==0){
	$lv_info="无限制";
}else{
	$lv_info="<b>".lang(getrx("select * from ".TABLE."lv where L_id=".$row["N_lv"],"L_title"))."</b>";
}

switch($row["N_type"]){
	case 0:
	$N_type="普通新闻";
	break;
	case 1:
	$N_type="招聘信息";
	break;
	case 2:
	$N_type="文件下载";
	break;
	case 3:
	$N_type="视频播放";
	break;
	case 4:
	$N_type="通知公告";
	break;
	default:
	$N_type="普通新闻";
}


$newslist=$newslist."{\"N_id\":".$row["N_id"].",\"N_order\":".$row["N_order"].",\"N_title\":\"".gljson(lang($row["N_title"]))."\",\"N_sh\":\"".gljson($sh_info)."\",\"N_lv\":\"".gljson($lv_info)."\",\"N_top\":\"".gljson($news_top)."\",\"N_type\":\"".$N_type."\",\"N_pic\":\"".gljson($row["N_pic"])."\",\"N_sort\":\"".gljson($news_sort)."\"},";
}

$newslist= substr($newslist,0,strlen($newslist)-1);
}

$sql="select count(N_id) as N_count from ".TABLE."news,".TABLE."nsort where S_del=0 and N_del=0 and N_sort=S_id ".$aa.$auth_info;
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$N_count=$row["N_count"];

echo "{\"news_list\":[".$newslist."],\"count\":\"".$N_count."\"}";

break;

case "news_add";
if(strpos($_COOKIE["newsauth"],"all")!==false){
	$auth_info="";
}else{
	$newsauth=explode(",",$_COOKIE["newsauth"]);
	for ($i=0; $i<count($newsauth)-1;$i++){
		$tj=$tj."or N_sort=".intval($newsauth[$i])." ";
	}
	if($tj==""){
		$auth_info=" and N_id<0";
	}else{
		$tj= substr($tj,-(strlen($tj)-3));
		$auth_info=" and (".$tj.")";
	}
}

if($id!="" && $id!="0"){
$sql="select * from ".TABLE."news,".TABLE."nsort where S_del=0 and N_del=0 and N_sort=S_id and N_id=".$id.$auth_info;
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$N_title=lang($row["N_title"]);
$N_author=$row["N_author"];
$N_view=$row["N_view"];
$N_content=str_Replace("{@SL_安装目录}",$C_dir,lang($row["N_content"]));
$N_pic=$row["N_pic"];
$N_link=$row["N_link"];
$N_sort=$row["N_sort"];
$N_date=$row["N_date"];
$N_top=$row["N_top"];
$N_sh=$row["N_sh"];
$N_type=$row["N_type"];
$N_file=$row["N_file"]."|||||||||";
$N_job=$row["N_job"]."|||||||||";
$N_jobname=$row["N_jobname"];
$N_team=$row["N_team"]."|||||||||";
$N_teaminfo=$row["N_teaminfo"];
$N_teamid=$row["N_teamid"];
$N_video=$row["N_video"];
$N_lv=$row["N_lv"];
$N_form=$row["N_form"];
$N_tag=$row["N_tag"];
$N_price=$row["N_price"];
$N_hide=$row["N_hide"];
$N_hideon=$row["N_hideon"];
$N_hidetype=$row["N_hidetype"];
$N_hideintro=$row["N_hideintro"];
$N_color=$row["N_color"];
$N_strong=$row["N_strong"];
$N_pagetitle=lang($row["N_pagetitle"]);
$N_keywords=lang($row["N_keywords"]);
$N_description=lang($row["N_description"]);
$S_title=lang($row["S_title"]);
$sort_info=" and N_sort=".$row["N_sort"];
}
}else{
$N_date=date('Y-m-d H:i:s');
$N_author=$_COOKIE["user"];
$N_job="|||||||||||";
$N_jobname="招聘职位@招聘人数@工作地点@薪资水平@学历要求@经验要求@年龄要求@性别要求@语言要求";
$N_file="|||||||||";
$N_team="|||||||||";
$N_pic="images/nopic.png";
$N_hidetype="div";
$N_view=100;
$sort_info="";
}

$tag=explode(",",$C_tag);
for ($i=0;$i< count($tag);$i++){
	if(strpos($N_tag,",".$tag[$i].",")!==false){
		$check_info="checked=\"checked\"";
	}
	$tag_info=$tag_info."<label class='i-checks' style='margin-right:10px;'><input type='checkbox' name='N_tag[]' value='".$tag[$i]."' ".$check_info."><i></i>".$tag[$i]."</label>";
	$check_info="";
}

$job=explode("|",$N_job);
$jobname=explode("@",$N_jobname);
$file=explode("|",$N_file);
$team=explode("|",$N_team);

$sql="select  * from ".TABLE."news where N_id>0 ".$sort_info.$auth_info." and N_del=0 order by N_order,N_id desc limit 50";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$newslist=$newslist."{\"N_id\":".$row["N_id"].",\"N_title\":\"".gljson(lang($row["N_title"]))."\"},";
}

$newslist= substr($newslist,0,strlen($newslist)-1);
}

$sql="select * from ".TABLE."member,".TABLE."lv where M_del=0 and M_lv=L_id and not M_login='未提供' and not M_login='admin' and M_subscribe=0 order by M_id desc";

$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
if(substr($row["M_pic"],0,4)!="http"){
$M_pic="../media/".$row["M_pic"];
}else{
$M_pic=$row["M_pic"];
}
$mlist=$mlist."{\"M_id\":".$row["M_id"].",\"M_login\":\"".gljson($row["M_login"])."\",\"M_pic\":\"".gljson($M_pic)."\"},";
}
$mlist= substr($mlist,0,strlen($mlist)-1);
}

echo "{\"N_id\":\"".$id."\",\"N_title\":\"".gljson($N_title)."\",\"N_author\":\"".$N_author."\",\"N_view\":\"".$N_view."\",\"N_teaminfo\":\"".$N_teaminfo."\",\"N_teamid\":\"".$N_teamid."\",\"N_content\":\"".gljson($N_content)."\",\"N_date\":\"".$N_date."\",\"N_pagetitle\":\"".gljson($N_pagetitle)."\",\"N_keywords\":\"".gljson($N_keywords)."\",\"N_description\":\"".gljson($N_description)."\",\"N_pic\":\"".$N_pic."\",\"N_link\":\"".$N_link."\",\"N_sort\":\"".$N_sort."\",\"N_top\":\"".$N_top."\",\"N_sh\":\"".$N_sh."\",\"N_type\":\"".$N_type."\",\"N_lv\":\"".$N_lv."\",\"N_form\":\"".$N_form."\",\"tag\":\"".gljson($tag_info)."\",\"N_color\":\"".gljson($N_color)."\",\"N_strong\":\"".$N_strong."\",\"N_video\":\"".gljson($N_video)."\",\"N_job1\":\"".$job[0]."\",\"N_job2\":\"".$job[1]."\",\"N_job3\":\"".$job[2]."\",\"N_job4\":\"".$job[3]."\",\"N_job5\":\"".$job[4]."\",\"N_job6\":\"".$job[5]."\",\"N_job7\":\"".$job[6]."\",\"N_job8\":\"".$job[7]."\",\"N_job9\":\"".$job[8]."\",\"N_jobname1\":\"".$jobname[0]."\",\"N_jobname2\":\"".$jobname[1]."\",\"N_jobname3\":\"".$jobname[2]."\",\"N_jobname4\":\"".$jobname[3]."\",\"N_jobname5\":\"".$jobname[4]."\",\"N_jobname6\":\"".$jobname[5]."\",\"N_jobname7\":\"".$jobname[6]."\",\"N_jobname8\":\"".$jobname[7]."\",\"N_jobname9\":\"".$jobname[8]."\",\"N_file1\":\"".$file[0]."\",\"N_file2\":\"".$file[1]."\",\"N_file3\":\"".$file[2]."\",\"N_file4\":\"".$file[3]."\",\"N_file5\":\"".$file[4]."\",\"N_file6\":\"".$file[5]."\",\"N_file7\":\"".$file[6]."\",\"N_team1\":\"".$team[0]."\",\"N_team2\":\"".$team[1]."\",\"N_team3\":\"".$team[2]."\",\"N_team4\":\"".$team[3]."\",\"N_hide\":\"".gljson($N_hide)."\",\"N_hideon\":\"".$N_hideon."\",\"N_price\":\"".$N_price."\",\"N_hidetype\":\"".$N_hidetype."\",\"N_hideintro\":\"".gljson($N_hideintro)."\",\"S_title\":\"".$S_title."\",\"news_list\":[".$newslist."],\"member_list\":[".$mlist."]}";
break;

case "nsort_list";
if(strpos($_COOKIE["newsauth"],"all")!==false){
	$auth_info="";
}else{
	$newsauth=explode(",",$_COOKIE["newsauth"]);
	for ($i=0; $i<count($newsauth)-1;$i++){
		$tj=$tj."or S_id=".intval($newsauth[$i])." ";
	}

	if($tj==""){
		$auth_info=" and S_id<0";
	}else{
		$tj= substr($tj,-(strlen($tj)-3));
		$auth_info=" and (".$tj.")";
	}
}

$sql="Select * from ".TABLE."nsort where S_sub=0 and S_del=0 order by S_order,S_id desc";

$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {

	$sql2="Select * from ".TABLE."nsort where S_sub=".$row["S_id"].$auth_info." and S_del=0 order by S_order,S_id desc";
	$result2 = mysqli_query($conn, $sql2);
	if(mysqli_num_rows($result2) > 0) {
		while($row2 = mysqli_fetch_assoc($result2)) {

			$sql3="select count(*) as S_count from ".TABLE."news where N_sort=".$row2["S_id"]." and N_del=0";
			$result3 = mysqli_query($conn, $sql3);
			$row3 = mysqli_fetch_assoc($result3);

			$S_count=intval($row3["S_count"]);

			if($row2["S_show"]==1){
				$S_show="<font color='#009900'>√</font>";
			}else{
				$S_show="<font color='#ff9900'>×</font>";
			}

			$nsortlist=$nsortlist."{\"S_id\":".$row2["S_id"].",\"S_title\":\"".gljson(lang($row2["S_title"]))."\",\"S_entitle\":\"".gljson(lang($row2["S_entitle"]))."\",\"S_count\":".$S_count.",\"S_show\":\"".$S_show."\",\"S_sub\":\"".$row2["S_sub"]."\",\"S_order\":".$row2["S_order"].",\"S_pic\":\"".$row2["S_pic"]."\"},";
		}
	}

	$nsortlist= substr($nsortlist,0,strlen($nsortlist)-1);

	if($row["S_show"]==1){
		$S_show="<font color='#009900'>√</font>";
	}else{
		$S_show="<font color='#ff9900'>×</font>";
	}


	$nsortlists=$nsortlists. "{\"S_id\":".$row["S_id"].",\"S_title\":\"".lang($row["S_title"])."\",\"S_entitle\":\"".lang($row["S_entitle"])."\",\"S_show\":\"".$S_show."\",\"S_sub\":\"".$row["S_sub"]."\",\"S_order\":".$row["S_order"].",\"S_pic\":\"".$row["S_pic"]."\",\"nsort_list\":[".$nsortlist."]},";
	$nsortlist="";
}

}

$nsortlists= substr($nsortlists,0,strlen($nsortlists)-1);

echo "{\"nsort_lists\":[".$nsortlists."]}";


break;
case "nsort_list2":
if(strpos($_COOKIE["newsauth"],"all")!==false){
	$auth_info="";
}else{
	$newsauth=explode(",",$_COOKIE["newsauth"]);
	for ($i=0 ;$i<count($newsauth)-1;$i++){
		$tj=$tj."or S_id=".intval($newsauth[$i])." ";
	}

	if($tj==""){
		$auth_info=" and S_id<0";
	}else{
		$tj= substr($tj,-(strlen($tj)-3));
		$auth_info=" and (".$tj.")";
	}
	
}

$sql="Select * from ".TABLE."nsort where S_del=0 and S_sub=0 order by S_order,S_id desc";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {

	$sql2="Select * from ".TABLE."nsort where S_sub=".$row["S_id"].$auth_info." and S_del=0 order by S_order,S_id desc";
	$result2 = mysqli_query($conn, $sql2);
	if(mysqli_num_rows($result2) > 0) {
		while($row2 = mysqli_fetch_assoc($result2)) {

			$sql3="select count(*) as S_count from ".TABLE."news where N_sort=".$row2["S_id"]." and N_del=0";
			$result3 = mysqli_query($conn, $sql3);
			$row3 = mysqli_fetch_assoc($result3);

			$S_count=$row3["S_count"];
			$nsortlist=$nsortlist."{\"S_id\":".$row2["S_id"].",\"S_title\":\"".lang($row2["S_title"])."\",\"S_entitle\":\"".lang($row2["S_entitle"])."\",\"S_count\":".$S_count.",\"S_type\":\"".$S_type."\",\"S_sub\":\"".$row2["S_sub"]."\",\"S_order\":".$row2["S_order"].",\"S_pic\":\"".$row2["S_pic"]."\"},";

		}
		$nsortlist= substr($nsortlist,0,strlen($nsortlist)-1);
	}else{
		$nsortlist="{\"S_id\":\"\",\"S_title\":\"暂无子分类，请新建\",\"S_entitle\":\"null\",\"S_count\":0,\"S_type\":\"\",\"S_sub\":\"".$row["S_id"]."\",\"S_order\":0,\"S_pic\":\"\"}";
	}

	$nsortlists=$nsortlists. "{\"S_id\":".$row["S_id"].",\"S_title\":\"".lang($row["S_title"])."\",\"S_entitle\":\"".lang($row["S_entitle"])."\",\"S_type\":\"\",\"S_sub\":\"".$row["S_sub"]."\",\"S_order\":".$row["S_order"].",\"S_pic\":\"".$row["S_pic"]."\",\"nsort_list\":[".$nsortlist."]},";
	$nsortlist="";
}
}

$nsortlists= substr($nsortlists,0,strlen($nsortlists)-1);

echo "{\"nsort_lists\":[".$nsortlists."]}";
break;

case "lv_list";
$sql1="Select * from ".TABLE."lv";
$result1 = mysqli_query($conn, $sql1);
if(mysqli_num_rows($result1) > 0) {
while($row1 = mysqli_fetch_assoc($result1)) {

$lvlist=$lvlist."{\"L_id\":".$row1["L_id"].",\"L_title\":\"".lang($row1["L_title"])."\",\"L_order\":\"".$row1["L_order"]."\",\"L_fen\":\"".$row1["L_fen"]."\",\"L_discount\":\"".$row1["L_discount"]."\"},";

}
$lvlist= substr($lvlist,0,strlen($lvlist)-1);
}

echo "{\"lv_list\":[".$lvlist."]}";
break;

case "bsort_add";
if(strpos($_COOKIE["bbsauth"],"all")!==false){
	$auth_info="";
}else{
	$bbsauth=explode(",",$_COOKIE["bbsauth"]);
	for ($i=0;$i<count($bbsauth)-1;$i++){
		$tj=$tj."or S_id=".intval($bbsauth[$i])." ";
	}
	if($tj==""){
		$auth_info=" and S_id<0";
	}else{
		$tj= substr($tj,-(strlen($tj)-3));
		$auth_info=" and (".$tj.")";
	}
}


if($id!="" && $id!="0"){
$sql="select * from ".TABLE."bsort where S_del=0 and S_id=".$id.$auth_info." order by S_order,S_id desc";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$S_title=lang($row["S_title"]);
$S_content=lang($row["S_content"]);
$S_order=$row["S_order"];
$S_pic=$row["S_pic"];
$S_lv=$row["S_lv"];
$S_sh=$row["S_sh"];
$S_hide=$row["S_hide"];
}
}else{
$S_pic="images/nopic.png";
}
echo "{\"S_id\":\"".$id."\",\"S_title\":\"".$S_title."\",\"S_content\":\"".$S_content."\",\"S_order\":\"".$S_order."\",\"S_pic\":\"".$S_pic."\",\"S_lv\":\"".$S_lv."\",\"S_sh\":\"".$S_sh."\",\"S_hide\":\"".$S_hide."\"}";

break;

case "nsort_add";
if(strpos($_COOKIE["newsauth"],"all")!==false){
	$auth_info="";
}else{
	$newsauth=explode(",",$_COOKIE["newsauth"]);
	for ($i=0;$i<count($newsauth)-1;$i++){
		$tj=$tj."or S_id=".$newsauth[$i]." ";
	}
	if($tj==""){
		$auth_info=" and S_id<0";
	}else{
		$tj= substr($tj,-(strlen($tj)-3));
		$auth_info=" and (".$tj.")";
	}
}

if($id!="" && $id!="0"){
$sql="select * from ".TABLE."nsort where S_id=".$id.$auth_info." and S_del=0 order by S_order,S_id desc";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$S_title=lang($row["S_title"]);
$S_entitle=lang($row["S_entitle"]);
$S_description=lang($row["S_description"]);
$S_keywords=lang($row["S_keywords"]);
$S_pagetitle=lang($row["S_pagetitle"]);
$S_type=$row["S_type"];
$S_order=$row["S_order"];
$S_sub=$row["S_sub"];
$S_pic=$row["S_pic"];
$S_show=$row["S_show"];
$S_tg=$row["S_tg"];
$S_url=$row["S_url"];
}
}else{
$S_pic="images/nopic.png";
$S_show=1;
}
echo "{\"S_id\":\"".$id."\",\"S_title\":\"".$S_title."\",\"S_entitle\":\"".$S_entitle."\",\"S_description\":\"".$S_description."\",\"S_keywords\":\"".$S_keywords."\",\"S_pagetitle\":\"".$S_pagetitle."\",\"S_type\":\"".$S_type."\",\"S_order\":\"".$S_order."\",\"S_sub\":\"".$S_sub."\",\"S_pic\":\"".$S_pic."\",\"S_show\":\"".$S_show."\",\"S_tg\":\"".$S_tg."\",\"S_url\":\"".$S_url."\"}";

break;

case "product_list";
if(strpos($_COOKIE["productauth"],"all")!==false){
	$auth_info="";
}else{
	$productauth=explode(",",$_COOKIE["productauth"]);
	for ($i=0; $i<count($productauth)-1;$i++){
		$tj=$tj."or P_sort=".intval($productauth[$i])." ";
	}
	if($tj==""){
		$auth_info=" and P_id<0";
	}else{
		$tj= substr($tj,-(strlen($tj)-3));
		$auth_info=" and (".$tj.")";
	}
}

if($id=="" || $id=="0"){
$aa="";
}else{
$aa="and S_id=".$id;
}

if($page==""){
$page="1";
}

if($page=="all"){
$sql="select * from ".TABLE."product,".TABLE."psort where P_del=0 and P_sort=S_id ".$aa.$auth_info." and S_del=0 order by P_order,P_id desc";
}else{
if($page==1){
$sql="select * from ".TABLE."product,".TABLE."psort where P_del=0 and P_sort=S_id ".$aa.$auth_info." and S_del=0 order by P_order,P_id desc limit 10";
}else{
$sql="select * from ".TABLE."product,".TABLE."psort where P_del=0 and P_sort=S_id ".$aa.$auth_info." and S_del=0 order by P_order,P_id desc limit ".($page*10-10).",10";
}
}

$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$product_sort="<select name='sort_".$row["P_id"]."'>";


$sql3="Select * from ".TABLE."psort where S_sub=0 and S_del=0 order by S_id";
$result3 = mysqli_query($conn,  $sql3);
if (mysqli_num_rows($result3) > 0) {
while($row3 = mysqli_fetch_assoc($result3)) {


$product_sort=$product_sort."<optgroup label=\"".lang($row3["S_title"])."\">";
$sql4="Select * from ".TABLE."psort where S_sub=".$row3["S_id"]." and S_del=0 order by S_id";
$result4 = mysqli_query($conn,  $sql4);
if (mysqli_num_rows($result4) > 0) {
while($row4 = mysqli_fetch_assoc($result4)) {


if($row["P_sort"]==$row4["S_id"]){
$sort_select="selected='selected'";
}else{
$sort_select="";
}
$product_sort=$product_sort."<option value='".$row4["S_id"]."' ".$sort_select.">".lang($row4["S_title"])."</option>";

}
}

$product_sort=$product_sort."</optgroup>";

}
}

$product_sort=$product_sort."</select>";
if($row["P_top"]==1){
$sort_select1="selected='selected'";
$sort_select0="";
}else{
$sort_select0="selected='selected'";
$sort_select1="";
}
$news_top="<select name='top_".$row["P_id"]."'>";
$news_top=$news_top."<option value='1' ".$sort_select1.">置顶</option>";
$news_top=$news_top."<option value='0' ".$sort_select0.">取消</option>";
$news_top=$news_top."</select>";
if($row["P_buy"]==1){
$buy_select1="selected='selected'";
$buy_select0="";
}else{
$buy_select0="selected='selected'";
$biu_select1="";
}
$buy_info="<select name='buy_".$row["P_id"]."'>";
$buy_info=$buy_info."<option value='1' ".$buy_select1.">开启</option>";
$buy_info=$buy_info."<option value='0' ".$buy_select0.">关闭</option>";
$buy_info=$buy_info."</select>";
if($row["P_path"]=="" || is_null($row["P_path"])){
$P_path="media/";
}else{
$P_path=$row["P_path"];
}
$productlist=$productlist."{\"P_id\":".$row["P_id"].",\"P_order\":".$row["P_order"].",\"P_price\":\"".$row["P_price"]."\",\"P_buy\":\"".gljson($buy_info)."\",\"P_title\":\"".gljson(lang($row["P_title"]))."\",\"P_top\":\"".gljson($news_top)."\",\"P_pic\":\"".splitx(splitx($P_path,"|",0),"__",0)."\",\"P_sort\":\"".gljson($product_sort)."\"},";
}


$productlist= substr($productlist,0,strlen($productlist)-1);

}

$sql="select count(P_id) as P_count from ".TABLE."product,".TABLE."psort where P_del=0 and P_sort=S_id and S_del=0 ".$aa.$auth_info;

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$P_count=$row["P_count"];

echo "{\"product_list\":[".$productlist."],\"count\":\"".$P_count."\"}";

break;



case "product_search";

$S_key=$_GET["S_key"];

if(strpos($_COOKIE["productauth"],"all")!==false){
	$auth_info="";
}else{
	$productauth=explode(",",$_COOKIE["productauth"]);
	for ($i=0; $i<count($productauth)-1;$i++){
		$tj=$tj."or P_sort=".intval($productauth[$i])." ";
	}
	if($tj==""){
		$auth_info=" and P_id<0";
	}else{
		$tj= substr($tj,-(strlen($tj)-3));
		$auth_info=" and (".$tj.")";
	}
}

if($id=="" || $id=="0"){
$aa="";
}else{
$aa="and S_id=".$id;
}

if($page==""){
$page="1";
}

if($page=="all"){
$sql="select * from ".TABLE."product,".TABLE."psort where P_title like '%".$S_key."%' and P_del=0 and P_sort=S_id ".$aa.$auth_info." and S_del=0 order by P_order,P_id desc";
}else{
if($page==1){
$sql="select * from ".TABLE."product,".TABLE."psort where P_title like '%".$S_key."%' and P_del=0 and P_sort=S_id ".$aa.$auth_info." and S_del=0 order by P_order,P_id desc limit 10";
}else{
$sql="select * from ".TABLE."product,".TABLE."psort where P_title like '%".$S_key."%' and P_del=0 and P_sort=S_id ".$aa.$auth_info." and S_del=0 order by P_order,P_id desc limit ".($page*10-10).",10";
}
}

$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$product_sort="<select name='sort_".$row["P_id"]."'>";


$sql3="Select * from ".TABLE."psort where S_sub=0 and S_del=0 order by S_id";
$result3 = mysqli_query($conn,  $sql3);
if (mysqli_num_rows($result3) > 0) {
while($row3 = mysqli_fetch_assoc($result3)) {


$product_sort=$product_sort."<optgroup label=\"".lang($row3["S_title"])."\">";
$sql4="Select * from ".TABLE."psort where S_sub=".$row3["S_id"]." and S_del=0 order by S_id";
$result4 = mysqli_query($conn,  $sql4);
if (mysqli_num_rows($result4) > 0) {
while($row4 = mysqli_fetch_assoc($result4)) {


if($row["P_sort"]==$row4["S_id"]){
$sort_select="selected='selected'";
}else{
$sort_select="";
}
$product_sort=$product_sort."<option value='".$row4["S_id"]."' ".$sort_select.">".lang($row4["S_title"])."</option>";

}
}

$product_sort=$product_sort."</optgroup>";

}
}

$product_sort=$product_sort."</select>";
if($row["P_top"]==1){
$sort_select1="selected='selected'";
$sort_select0="";
}else{
$sort_select0="selected='selected'";
$sort_select1="";
}
$news_top="<select name='top_".$row["P_id"]."'>";
$news_top=$news_top."<option value='1' ".$sort_select1.">置顶</option>";
$news_top=$news_top."<option value='0' ".$sort_select0.">取消</option>";
$news_top=$news_top."</select>";
if($row["P_buy"]==1){
$buy_select1="selected='selected'";
$buy_select0="";
}else{
$buy_select0="selected='selected'";
$biu_select1="";
}
$buy_info="<select name='buy_".$row["P_id"]."'>";
$buy_info=$buy_info."<option value='1' ".$buy_select1.">开启</option>";
$buy_info=$buy_info."<option value='0' ".$buy_select0.">关闭</option>";
$buy_info=$buy_info."</select>";
if($row["P_path"]=="" || is_null($row["P_path"])){
$P_path="media/";
}else{
$P_path=$row["P_path"];
}
$productlist=$productlist."{\"P_id\":".$row["P_id"].",\"P_order\":".$row["P_order"].",\"P_price\":\"".$row["P_price"]."\",\"P_buy\":\"".gljson($buy_info)."\",\"P_title\":\"".gljson(lang($row["P_title"]))."\",\"P_top\":\"".gljson($news_top)."\",\"P_pic\":\"".splitx(splitx($P_path,"|",0),"__",0)."\",\"P_sort\":\"".gljson($product_sort)."\"},";
}


$productlist= substr($productlist,0,strlen($productlist)-1);

}

$sql="select count(P_id) as P_count from ".TABLE."product,".TABLE."psort where P_title like '%".$S_key."%' and P_del=0 and P_sort=S_id and S_del=0 ".$aa.$auth_info;

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$P_count=$row["P_count"];

echo "{\"product_list\":[".$productlist."],\"count\":\"".$P_count."\"}";

break;


case "product_add";
if(strpos($_COOKIE["productauth"],"all")!==false){
	$auth_info="";
}else{
	$productauth=explode(",",$_COOKIE["productauth"]);
	for ($i=0;$i< count($productauth)-1;$i++){
		$tj=$tj."or P_sort=".intval($productauth[$i])." ";
	}
	if($tj==""){
		$auth_info=" and P_id<0";
	}else{
		$tj= substr($tj,-(strlen($tj)-3));
		$auth_info=" and (".$tj.")";
	}
}
if($id!="" && $id!="0"){
$sql="Select * from ".TABLE."product,".TABLE."psort where P_del=0 and P_sort=S_id and S_del=0 and P_id=".$id.$auth_info;
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$P_title=lang($row["P_title"]);
$P_price=$row["P_price"];
$P_rest=$row["P_rest"];
$P_time=$row["P_time"];
$P_buy=$row["P_buy"];
$P_unlogin=$row["P_unlogin"];
$P_top=$row["P_top"];
$P_content=str_Replace("{@SL_安装目录}",$C_dir,lang($row["P_content"]));
$P_short=lang($row["P_short"]);
$P_path=$row["P_path"];
$P_sort=$row["P_sort"];
$P_brand=$row["P_brand"];
$P_shuxing=$row["P_shuxing"];
$P_shuxingt=$row["P_shuxingt"];
$P_pagetitle=lang($row["P_pagetitle"]);
$P_keywords=lang($row["P_keywords"]);
$P_description=lang($row["P_description"]);
$P_name=$row["P_name"];
$P_email=$row["P_email"];
$P_address=$row["P_address"];
$P_mobile=$row["P_mobile"];
$P_postcode=$row["P_postcode"];
$P_qq=$row["P_qq"];
$P_remark=$row["P_remark"];
$P_sell=$row["P_sell"];
$P_sence=$row["P_sence"];
$P_link=$row["P_link"];
$S_title=lang($row["S_title"]);
$sort_info=" and P_sort=".$row["P_sort"];
}
}else{
$sort_info="";
$P_time=date('Y-m-d H:i:s');
$P_buy=0;
$P_path="";
}

$P_pic=$P_pic."<table width=\"100%\" id=\"pic1\">";
$pic=explode("|",$P_path);
if($P_path!=""){
	for($j = 0 ;$j<count($pic);$j++){
		if(strpos($pic[$j],"__")!==false){
			$img_path=splitx($pic[$j],"__",0);
			$img_str=splitx($pic[$j],"__",1);
		}else{
			$img_path=splitx($pic[$j],"__",0);
			$img_str="";
		}
		if(substr($img_path,0,5)=="media" || substr($img_path,0,6)=="images"){
			$img_pathx="../".$img_path;
		}else{
			$img_pathx=$img_path;
		}
		$P_pic=$P_pic."<tr id='pp".$j."' onmouseout='this.style.backgroundColor=\"\"' onmouseover='this.style.backgroundColor=\"#F7F7F7\"' bgcolor='#FFFFFF'><td width='110'><img src='".$img_pathx."' class='p_pic' alt='<img src=".$img_pathx." width=300>' id='picpic1_".$j."x' onclick='showUpload(\"picpic1_".$j."\");'></td><td>";

		$P_pic=$P_pic."<div class=\"input-group\"><input name=\"picpic1_".$j."\" id=\"picpic1_".$j."\" class=\"form-control\" value=\"".$img_path."\"><span class=\"input-group-btn\"><button class=\"btn btn-info\" type=\"button\" onclick='showUpload(\"picpic1_".$j."\");'>上传文件</button></span></div><br><input name=\"itrpic1_".$j."\" id=\"itrpic1_".$j."\" class=\"form-control\" value=\"".$img_str."\" placeholder=\"文字描述\">";

		$P_pic=$P_pic. "</td><td><input type='button' value='- 删掉该图' class='add' onclick='DelPic(".$j.")' style='margin:5px;'/></td></tr>";
	}
}
if($P_path==""){
$P_pic=$P_pic. "<tr id='pp0' onmouseout='this.style.backgroundColor=\"\"' onmouseover='this.style.backgroundColor=\"#F7F7F7\"' bgcolor='#FFFFFF'><td width='110'><img  class='p_pic'  id='picpic1_0x' onclick='showUpload(\"picpic1_0\");'></td><td>";
$P_pic=$P_pic."<div class=\"input-group\"><input name=\"picpic1_0\" id=\"picpic1_0\" class=\"form-control\" value=\"\"><span class=\"input-group-btn\"><button class=\"btn btn-info\" type=\"button\" onclick='showUpload(\"picpic1_0\");'>上传文件</button></span></div><br><input name=\"itrpic1_0\" id=\"itrpic1_0\" class=\"form-control\" value=\"\" placeholder=\"文字描述\">";
$P_pic=$P_pic. "</td><td><input type='button' value='- 删掉该图' class='add' onclick='DelPic(0)' style='margin:5px;'/></td></tr>";
}
$P_pic=$P_pic."</table>";


$P_sx=$P_sx."<table width=\"100%\" bgcolor='#eeeeee' id=\"tab1\" style=\"min-width:850px\"><tr bgcolor='#F7F7F7'><td>属性名称</td><td>属性值 / 对价格的影响</td><td>删除该属性</td></tr>";

if($P_shuxing!="" && !is_null($P_shuxing)){
$shuxing=explode("@",$P_shuxing);
for($j = 0;$j<count($shuxing);$j++){

$P_sx=$P_sx. "<tr id='pd".($j+1)."' onmouseout='this.style.backgroundColor=\"\"' onmouseover='this.style.backgroundColor=\"#F7F7F7\"' bgcolor='#FFFFFF'><td><div class='input-group m-b'><span class='input-group-addon'>属性名称</span><input type='text' name='sctitle_".$j."' value='".lang(splitx($shuxing[$j],"_",0))."' class='form-control'/><input type='hidden' name='xsctitle_".$j."' value='".splitx($shuxing[$j],"_",0)."'/></div></td><td>";
$P_sx=$P_sx. "<table width='100%' bgcolor='#FFFFFF' id='table".$j."'>";


$sc=explode("|",splitx($shuxing[$j],"_",1));
$sp=explode("|",splitx($shuxing[$j],"_",2));

for($i = 0 ;$i<count($sc);$i++){
$P_sx=$P_sx. "<tr id='pd".$j."_".$i."' bgcolor='#eeeeee' onmouseout='this.style.backgroundColor=\"\"' onmouseover='this.style.backgroundColor=\"#FFFFFF\"'><td><div class='input-group m-b'><span class='input-group-addon'>属性值</span><input type='text' name='scvvvv".$j."_".$i."' value='".lang($sc[$i])."'  class='form-control'/><input type='hidden' name='xscvvvv".$j."_".$i."' value='".$sc[$i]."'/></div></td><td><div class='input-group m-b'><span class='input-group-addon'>加价</span><input type='text' name='spvvvv".$j."_".$i."' value='".$sp[$i]."'  class='form-control'/><span class='input-group-addon'>元</span></div></td><td><input type='button' value='- 删掉该行' onclick='DelRow2(\"table".$j."\",\"".$j."_".$i."\")' class='add' style='margin:5px;'/></td></tr>";
}
$P_sx=$P_sx. "</table>";

$P_sx=$P_sx. "<div><input type='button' value='＋ 新增一行' class='add' onclick='AddRow2(\"table".$j."\",".$j.")' style='margin:5px;'/></div></td><td><input type='button' value='- 删掉该属性' onclick='DelRow(".($j+1).")' style='margin:5px;' class='add'/></td></tr>";
}
}
$P_sx=$P_sx."</table>";
$sql="select * from ".TABLE."product where P_del=0 and P_id>0 ".$sort_info.$auth_info." order by P_order,P_id desc limit 50";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$productlist=$productlist."{\"P_id\":".$row["P_id"].",\"P_title\":\"".gljson(lang($row["P_title"]))."\"},";
}
$productlist= substr($productlist,0,strlen($productlist)-1);
}

$sql="select * from ".TABLE."brand";
	$result = mysqli_query($conn, $sql);
	$arr = array();  
	while($row = mysqli_fetch_array($result)) {
	$count=count($row);
	  for($i=0;$i<$count;$i++){ 
	    unset($row[$i]);
	  }   
    array_push($arr,$row);
}

$brandtlist=json_encode($arr);

echo "{\"P_id\":\"".$id."\",\"P_title\":\"".gljson($P_title)."\",\"P_price\":\"".$P_price."\",\"P_rest\":\"".$P_rest."\",\"P_time\":\"".$P_time."\",\"P_buy\":\"".$P_buy."\",\"P_unlogin\":\"".$P_unlogin."\",\"P_top\":\"".$P_top."\",\"P_content\":\"".gljson($P_content)."\",\"P_pic\":\"".gljson($P_pic)."\",\"P_sort\":\"".$P_sort."\",\"P_brand\":\"".$P_brand."\",\"P_shuxing\":\"".gljson($P_sx)."\",\"P_shuxingt\":\"".$P_shuxingt."\",\"P_pagetitle\":\"".$P_pagetitle."\",\"P_keywords\":\"".$P_keywords."\",\"P_description\":\"".gljson($P_description)."\",\"P_name\":\"".$P_name."\",\"P_email\":\"".$P_email."\",\"P_mobile\":\"".$P_mobile."\",\"P_address\":\"".$P_address."\",\"P_postcode\":\"".$P_postcode."\",\"P_qq\":\"".$P_qq."\",\"P_remark\":\"".$P_remark."\",\"P_sell\":\"".$P_sell."\",\"P_sence\":\"".$P_sence."\",\"P_link\":\"".$P_link."\",\"S_title\":\"".$S_title."\",\"product_list\":[".$productlist."],\"brand_list\":".$brandtlist."}";

break;

case "psort_list";
if(strpos($_COOKIE["productauth"],"all")!==false){
$auth_info="";
}else{
$productauth=explode(",",$_COOKIE["productauth"]);
for ($i=0;$i<count($productauth)-1;$i++){
$tj=$tj."or S_id=".intval($productauth[$i])." ";
}
if($tj==""){
$auth_info=" and S_id<0";
}else{
$tj= substr($tj,-(strlen($tj)-3));
$auth_info=" and (".$tj.")";
}
}
$sql="Select * from ".TABLE."psort where S_sub=0 and S_del=0";

$result = mysqli_query($conn,  $sql);
if (mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {

$sql2="Select * from ".TABLE."psort where S_del=0 and S_sub=".$row["S_id"].$auth_info;
$result2 = mysqli_query($conn,  $sql2);
if (mysqli_num_rows($result2) > 0) {
while($row2 = mysqli_fetch_assoc($result2)) {

$sql3="select count(*) as S_count from ".TABLE."product where P_del=0 and P_sort=".$row2["S_id"];
$result3 = mysqli_query($conn, $sql3);
$row3 = mysqli_fetch_assoc($result3);

$S_count=$row3["S_count"];

if($row2["S_type"]==0){
$S_type="产品";
}else{
$S_type="案例";
}
if($row2["S_show"]==1){
$S_show="<font color='#009900'>√</font>";
}else{
$S_show="<font color='#ff9900'>×</font>";
}
$psortlist=$psortlist."{\"S_id\":".$row2["S_id"].",\"S_show\":\"".gljson($S_show)."\",\"S_title\":\"".gljson(lang($row2["S_title"]))."\",\"S_entitle\":\"".gljson(lang($row2["S_entitle"]))."\",\"S_count\":".$S_count.",\"S_type\":\"".$S_type."\",\"S_pic\":\"".$row2["S_pic"]."\",\"S_sub\":\"".$row2["S_sub"]."\",\"S_order\":".$row2["S_order"]."},";
}

$psortlist= substr($psortlist,0,strlen($psortlist)-1);

}
if($row["S_type"]==0){
$S_type="产品";
}else{
$S_type="案例";
}
if($row["S_show"]==1){
$S_show="<font color='#009900'>√</font>";
}else{
$S_show="<font color='#ff9900'>×</font>";
}
$psortlists=$psortlists. "{\"S_id\":".$row["S_id"].",\"S_show\":\"".gljson($S_show)."\",\"S_title\":\"".gljson(lang($row["S_title"]))."\",\"S_entitle\":\"".gljson(lang($row["S_entitle"]))."\",\"S_type\":\"".$S_type."\",\"S_pic\":\"".$row["S_pic"]."\",\"S_sub\":\"".$row["S_sub"]."\",\"S_order\":".$row["S_order"].",\"psort_list\":[".$psortlist."]},";
$psortlist="";
}

$psortlists= substr($psortlists,0,strlen($psortlists)-1);

}
echo "{\"psort_lists\":[".$psortlists."]}";
break;

case "brand_list":
$sql="select * from ".TABLE."brand";
	$result = mysqli_query($conn, $sql);
	$arr = array();  
	if(mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			$count=count($row);
		      for($i=0;$i<$count;$i++){ 
		        unset($row[$i]);
		      }
		    array_push($arr,$row);
		}
	}
	echo json_encode($arr);
break;

case "dy";
$P_id=$_REQUEST["P_id"];
$P_shuxing=getrx("select * from ".TABLE."product where P_del=0 and P_id=".$P_id,"P_shuxing");
$P_sx=$P_sx."<table width=\"100%\" bgcolor='#eeeeee' id=\"tab1\" style=\"min-width:850px\"><tr bgcolor='#F7F7F7'><td>属性名称</td><td>属性值 / 对价格的影响</td><td>删除该属性</td></tr>";
if($P_shuxing!="" and !is_null($P_shuxing)){
$shuxing=explode("@",$P_shuxing);
for($j = 0;$j< count($shuxing);$j++){
$P_sx=$P_sx. "<tr id='pd".($j+1)."' onmouseout='this.style.backgroundColor=\"\"' onmouseover='this.style.backgroundColor=\"#F7F7F7\"' bgcolor='#FFFFFF'><td><div class='input-group m-b'><span class='input-group-addon'>属性名称</span><input type='text' name='sctitle_".$j."' value='".lang(splitx($shuxing[$j],"_",0))."' class='form-control'/><input type='hidden' name='xsctitle_".$j."' value='".splitx($shuxing[$j],"_",0)."'/></div></td><td>";
$P_sx=$P_sx. "<table width='100%' bgcolor='#FFFFFF' id='table".$j."'>";
$sc=explode("|",splitx($shuxing[$j],"_",1));
$sp=explode("|",splitx($shuxing[$j],"_",2));
For($i = 0 ;$i< count($sc);$i++){
$P_sx=$P_sx. "<tr id='pd".$j."_".$i."' bgcolor='#eeeeee' onmouseout='this.style.backgroundColor=\"\"' onmouseover='this.style.backgroundColor=\"#FFFFFF\"'><td><div class='input-group m-b'><span class='input-group-addon'>属性值</span><input type='text' name='scvvvv".$j."_".$i."' value='".lang($sc[$i])."'  class='form-control'/><input type='hidden' name='xscvvvv".$j."_".$i."' value='".$sc[$i]."'/></div></td><td><div class='input-group m-b'><span class='input-group-addon'>加价</span><input type='text' name='spvvvv".$j."_".$i."' value='".$sp[$i]."'  class='form-control'/><span class='input-group-addon'>元</span></div></td><td><input type='button' value='- 删掉该行' onclick='DelRow2(\"table".$j."\",\"".$j."_".$i."\")' class='add' style='margin:5px;'/></td></tr>";
}
$P_sx=$P_sx. "</table>";
$P_sx=$P_sx. "<div><input type='button' value='＋ 新增一行' class='add' onclick='AddRow2(\"table".$j."\",".$j.")' style='margin:5px;'/></div></td><td><input type='button' value='- 删掉该属性' onclick='DelRow(".($j+1).")' style='margin:5px;' class='add'/></td></tr>";
}
}
$P_sx=$P_sx."</table>";
echo "{\"P_shuxing\":\"".gljson($P_sx)."\"}";

break;


case "sx_list";
$sql="Select * from ".TABLE."product where P_del=0 and not P_shuxing='' and P_shuxingt=1";

$result = mysqli_query($conn,  $sql);
if (mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$P_id=$row["P_id"];
$P_title=lang($row["P_title"]);
$P_shuxing=$row["P_shuxing"];
$sx1=explode("@",$P_shuxing);
for ($j=0;$j<count($sx1);$j++){
$shuxing=$shuxing.lang(splitx($sx1[$j],"_",0))." (";

$sx2=explode("|",splitx($sx1[$j],"_",1));
for ($k=0;$k<count($sx2);$k++){
$shuxing=$shuxing.lang($sx2[$k])." ";
}
$shuxing=$shuxing.") ";
}
$sxlists=$sxlists."{\"P_id\":\"".$P_id."\",\"P_title\":\"".$P_title."\",\"P_shuxing\":\"".$shuxing."\"},";
$shuxing="";
}
$sxlists= substr($sxlists,0,strlen($sxlists)-1);
}
echo "{\"sx_list\":[".$sxlists."]}";
break;

case "psort_list2";
if(strpos($_COOKIE["productauth"],"all")!==false){
$auth_info="";
}else{
$productauth=explode(",",$_COOKIE["productauth"]);
for ($i=0; $i<count($productauth)-1;$i++){
$tj=$tj."or S_id=".intval($productauth[$i])." ";
}
if($tj==""){
$auth_info=" and S_id<0";
}else{
$tj= substr($tj,-(strlen($tj)-3));
$auth_info=" and (".$tj.")";
}
}

$sql="Select * from ".TABLE."psort where S_sub=0 and S_del=0";
$result = mysqli_query($conn,  $sql);
if (mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {

$sql2="Select * from ".TABLE."psort where S_del=0 and S_sub=".$row["S_id"].$auth_info;
$result2 = mysqli_query($conn,  $sql2);
if (mysqli_num_rows($result2) > 0) {
while($row2 = mysqli_fetch_assoc($result2)) {

$sql3="select count(*) as S_count from ".TABLE."product where P_del=0 and P_sort=".$row2["S_id"];
$result3 = mysqli_query($conn, $sql3);
$row3 = mysqli_fetch_assoc($result3);
$S_count=$row3["S_count"];

if($row2["S_type"]==0){
$S_type="产品";
}else{
$S_type="案例";
}
$psortlist=$psortlist."{\"S_id\":".$row2["S_id"].",\"S_title\":\"└ ".gljson(lang($row2["S_title"]))."\",\"S_entitle\":\"".gljson(lang($row2["S_entitle"]))."\",\"S_count\":".$S_count.",\"S_type\":\"".$S_type."\",\"S_pic\":\"".$row2["S_pic"]."\",\"S_sub\":\"".$row2["S_sub"]."\",\"S_order\":".$row2["S_order"]."},";
}

$psortlist= substr($psortlist,0,strlen($psortlist)-1);

}else{
$psortlist="{\"S_id\":\"\",\"S_title\":\"└ 暂无子分类，请新建\",\"S_entitle\":\"null\",\"S_count\":0,\"S_type\":\"产品\",\"S_pic\":\"images/nopic.png\",\"S_sub\":\"".$row["S_id"]."\",\"S_order\":0}";
}
if($row["S_type"]==0){
$S_type="产品";
}else{
$S_type="案例";
}
$psortlists=$psortlists. "{\"S_id\":".$row["S_id"].",\"S_title\":\"".gljson(lang($row["S_title"]))."\",\"S_entitle\":\"".gljson(lang($row["S_entitle"]))."\",\"S_type\":\"".$S_type."\",\"S_pic\":\"".$row["S_pic"]."\",\"S_sub\":\"".$row["S_sub"]."\",\"S_order\":".$row["S_order"].",\"psort_list\":[".$psortlist."]},";
$psortlist="";
}

$psortlists= substr($psortlists,0,strlen($psortlists)-1);
}
echo "{\"psort_lists\":[".$psortlists."]}";
break;

case "psort_add";
if(strpos($_COOKIE["productauth"],"all")!==false){
$auth_info="";
}else{
$productauth=explode(",",$_COOKIE["productauth"]);
for ($i=0 ;$i< count($productauth)-1;$i++){
$tj=$tj."or S_id=".intval($productauth[$i])." ";
}
if($tj==""){
$auth_info=" and S_id<0";
}else{
$tj= substr($tj,-(strlen($tj)-3));
$auth_info=" and (".$tj.")";
}
}
if($id!="" && $id!="0"){
$sql="select * from ".TABLE."psort where S_del=0 and S_id=".$id.$auth_info;
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$S_title=lang($row["S_title"]);
$S_entitle=lang($row["S_entitle"]);
$S_description=lang($row["S_description"]);
$S_keywords=lang($row["S_keywords"]);
$S_pagetitle=lang($row["S_pagetitle"]);
$S_type=$row["S_type"];
$S_pic=$row["S_pic"];
$S_show=$row["S_show"];
$S_order=$row["S_order"];
$S_sub=$row["S_sub"];
$S_url=$row["S_url"];
}
}else{
$S_pic="images/nopic.png";
$S_show=1;
}
echo "{\"S_id\":\"".$id."\",\"S_title\":\"".gljson($S_title)."\",\"S_entitle\":\"".gljson($S_entitle)."\",\"S_description\":\"".gljson($S_description)."\",\"S_keywords\":\"".gljson($S_keywords)."\",\"S_pagetitle\":\"".gljson($S_pagetitle)."\",\"S_type\":\"".$S_type."\",\"S_pic\":\"".$S_pic."\",\"S_url\":\"".$S_url."\",\"S_order\":\"".$S_order."\",\"S_show\":\"".$S_show."\",\"S_sub\":\"".$S_sub."\"}";
break;

case "order_list";
$sql="select M_pic,M_id,O_no,O_state,O_postage,O_tradeno,P_sell,P_sence from (select * from ".TABLE."orders,".TABLE."product,".TABLE."lv,".TABLE."member where M_lv=L_id and O_member=M_id and O_pid=P_id order by O_id desc)a group by O_no,O_state,O_postage,O_tradeno,P_sell,P_sence,M_id,M_pic";

$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {

$all=getrx("select sum(O_price*O_num) as O_all from ".TABLE."orders where O_no='".$row["O_no"]."'","O_all");

if($row["O_state"]==0){
$O_state="未付款";
$O_pay="等待买家付款";
}
if($row["O_state"]==1){
$O_state="<span style='color:#ff6600'>已付款未发货</span>";
$O_pay="<a href='#/app/send/".$row["O_no"]."' class='add'><i class='fa fa-send'></i> 发货</a>";
}
if($row["O_state"]==2){
$O_state="已发货";
$O_pay="已发货";
}
if($row["O_state"]==3){
$O_state="已确认";
$O_pay="已确认";
}
if($row["O_state"]==4){
$O_state="申请退款";
$O_pay="<a href='#/app/tk/".$row["O_no"]."' class='add'><i class='fa fa-sign-out'></i> 处理退款</a>";
}
if($row["O_state"]==5){
$O_state="已退款";
$O_pay="交易关闭";
}
if($row["O_state"]==6){
$O_state="货到付款";
$O_pay="";
}
if($row["O_state"]==7){
$O_state="转账汇款";
$O_pay="";
}

if($row["O_tradeno"]!="" and !is_null($row["O_tradeno"])){
$O_tradeno=splitx($row["O_tradeno"],"（",0);
if(strpos($row["O_tradeno"],"支付宝付款")!==false){
$paytype="<img src='../member/img/alipay_m.png' height='16'>";
}
if(strpos($row["O_tradeno"],"微信付款")!==false){
$paytype="<img src='../member/img/weixin_m.png' height='16'>";
}

if(strpos($row["O_tradeno"],"余额")!==false){
$paytype="<img src='../member/img/money_m.png' height='16'>";
}
if(strpos($row["O_tradeno"],"PAYPAL")!==false){
$paytype="<img src='../member/img/paypal_m.png' height='16'>";
}

}else{
$O_tradeno="";
$paytype="";
}

$sql2="select * from ".TABLE."orders,".TABLE."product,".TABLE."member,".TABLE."lv where M_del=0 and P_del=0 and O_member=M_id and O_pid=P_id and M_lv=L_id and O_no='".$row["O_no"]."' order by O_id desc";
$result2 = mysqli_query($conn, $sql2);
if(mysqli_num_rows($result2) > 0) {
while($row2 = mysqli_fetch_assoc($result2)) {
	if($row2["O_shuxing"]==""){
		$O_shuxings="标配";
	}else{
		$O_shuxings=$row2["O_shuxing"];
	}
	$O_shuxing=explode("|",$O_shuxings);
	for ($j=0;$j< count($O_shuxing);$j++){
		$shuxing=$shuxing.lang($O_shuxing[$j])." ";
	}
	$O_shuxing=$shuxing;

	$p_list=$p_list."{\"P_title\":\"".lang($row2["P_title"])."\",\"P_id\":".$row2["P_id"].",\"P_pic\":\"".splitx(splitx($row2["P_path"],"|",0),"__",0)."\",\"O_price\":\"".$row2["O_price"]."\",\"O_num\":".$row2["O_num"].",\"O_shuxing\":\"".$O_shuxing."\"},";
	$shuxing="";
}
}
$p_list= substr($p_list,0,strlen($p_list)-1);

if(substr($row["M_pic"],0,4)=="http"){
	$M_pic=$row["M_pic"];
}else{
	$M_pic="../media/".$row["M_pic"];
}
$orderlist=$orderlist. "{\"O_nox\":\"".$row["O_no"]."\",\"M_id\":".$row["M_id"].",\"O_statex\":".$row["O_state"].",\"O_postage\":".$row["O_postage"].",\"O_all\":\"".$all."\",\"O_all2\":\"".($all*$row["L_discount"]*0.01)."\",\"O_state\":\"".$O_state."\",\"menu_sub\":\"".$row["O_state"]."\",\"O_pay\":\"".$O_pay."\",\"O_no\":\"".$paytype." ".$O_tradeno."\",\"M_id\":".$row["M_id"].",\"M_login\":\"".$row["M_login"]."\",\"M_pic\":\"".$M_pic."\",\"O_time\":\"".$row["O_time"]."\",\"p_list\":[".$p_list."]},";
$p_list="";
}

$orderlist= substr($orderlist,0,strlen($orderlist)-1);

}
echo "{\"order_list\":[".$orderlist."]}";
break;


case "send";
$sql="select * from ".TABLE."orders,".TABLE."product,".TABLE."member where M_del=0 and P_del=0 and O_pid=P_id and O_member=M_id and O_no='".intval($_GET["id"])."' order by O_id desc";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$all=getrx("select sum(O_price*O_num) as O_all from ".TABLE."orders where O_no='".$row["O_no"]."'","O_all");

$M_login=$row["M_login"];
$M_name=$row["M_name"];
$M_add=$row["M_add"];
$M_mobile=$row["M_mobile"];
$M_code=$row["M_code"];
$M_email=$row["M_email"];
$P_title=lang($row["P_title"]);
$O_num=$row["O_num"];
$O_price=$row["O_price"];
$O_all=$row["O_price"]*$O_num;
$O_time=$row["O_time"];
$O_id=$row["O_id"];
$O_statex=$row["O_state"];
$O_tradeno=$row["O_tradeno"];
$O_remark=$row["O_remark"];
$O_postage=$row["O_postage"];

$O_wl=$row["O_wl"];
$O_wlid=$row["O_wlid"];
}

$sql="select * from ".TABLE."orders,".TABLE."product,".TABLE."member,".TABLE."lv where M_del=0 and P_del=0 and O_member=M_id and O_pid=P_id and M_lv=L_id and O_no='".intval($_GET["id"])."' order by O_id desc";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {

if($row["O_state"]==0){
$O_state="未付款";
$O_pay="等待买家付款";
}
if($row["O_state"]==1){
$O_state="<span style='color:#ff6600'>已付款未发货</span>";
$O_pay="<a href='#/app/send/".$row["O_id"]."' class='add'><i class='fa fa-send'></i> 发货</a>";
}
if($row["O_state"]==2){
$O_state="已发货";
$O_pay="已发货";
}
if($row["O_state"]==3){
$O_state="已确认";
$O_pay="已确认";
}
if($row["O_state"]==4){
$O_state="申请退款";
$O_pay="<a href='#/app/tk/".$row["O_id"]."' class='add'><i class='fa fa-sign-out'></i> 处理退款</a>";
}
if($row["O_state"]==5){
$O_state="已退款";
$O_pay="交易关闭";
}
if($row["O_state"]==6){
$O_state="货到付款";
$O_pay="";
}
if($row["O_state"]==7){
$O_state="转账汇款";
$O_pay="";
}
if($row["O_shuxing"]==""){
$O_shuxings="标配";
}else{
$O_shuxings=$row["O_shuxing"];
}
$O_shuxing=explode("|",$O_shuxings);
for ($j=0;$j< count($O_shuxing);$j++){
$shuxing=$shuxing.lang($O_shuxing[$j])." ";
}
$O_shuxing=$shuxing;
if($row["O_tradeno"]!="" and !is_null($row["O_tradeno"])){
$O_tradeno=splitx($row["O_tradeno"],"（",0);
if(strpos($row["O_tradeno"],"支付宝付款")!==false){
$paytype="<img src='img/alipay.ico' height='16'>";
}
if(strpos($row["O_tradeno"],"微信付款")!==false){
$paytype="<img src='img/wxpay.ico' height='16'>";
}
if(strpos($row["O_tradeno"],"7支付")!==false){
$paytype="<img src='img/bank.png' height='16'>";
}
}else{
$O_tradeno="";
$paytype="";
}

$orderlist=$orderlist. "{\"O_id\":".$row["O_id"].",\"O_nox\":\"".$row["O_no"]."\",\"P_title\":\"".lang($row["P_title"])."\",\"P_id\":".$row["P_id"].",\"M_id\":".$row["M_id"].",\"P_pic\":\"".splitx(splitx($row["P_path"],"|",0),"__",0)."\",\"O_price\":\"".$row["O_price"]."\",\"O_num\":".$row["O_num"].",\"O_statex\":".$row["O_state"].",\"O_postage\":".$row["O_postage"].",\"O_all\":\"".($row["O_price"]*$row["O_num"])."\",\"O_all2\":\"".($row["O_price"]*$row["O_num"]*$row["L_discount"]*0.01)."\",\"O_shuxing\":\"".$O_shuxing."\",\"O_state\":\"".$O_state."\",\"menu_sub\":\"".$row["O_state"]."\",\"O_pay\":\"".$O_pay."\",\"O_no\":\"".$paytype." ".$O_tradeno."\",\"M_id\":".$row["M_id"].",\"M_login\":\"".$row["M_login"]."\",\"M_pic\":\"".$row["M_pic"]."\",\"O_time\":\"".$row["O_time"]."\"},";
}
$orderlist= substr($orderlist,0,strlen($orderlist)-1);
}

$wl_list=GetBody("http://www.kuaidi100.com/query?type=".splitx($O_wl,"|",1)."&postid=".$O_wlid,"");

echo "{\"M_login\":\"".$M_login."\",\"M_name\":\"".$M_name."\",\"M_add\":\"".$M_add."\",\"M_mobile\":\"".$M_mobile."\",\"M_code\":\"".$M_code."\",\"M_email\":\"".$M_email."\",\"O_no\":\"".intval($_GET["id"])."\",\"O_tradeno\":\"".$O_tradeno."\",\"O_remark\":\"".$O_remark."\",\"O_wl\":\"".splitx($O_wl,"|",0)."\",\"O_wlid\":\"".$O_wlid."\",\"O_postage\":\"".$O_postage."\",\"O_state\":\"".$O_statex."\",\"all\":".$all.",\"order_list\":[".$orderlist."],\"O_wllist\":[".$wl_list."]}";
break;

case "wuliu";
$sql="select * from ".TABLE."orders where O_no='".intval($_GET["id"])."'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$O_wl=$row["O_wl"];
$O_wlid=$row["O_wlid"];
}
$wl_list=GetBody("http://www.kuaidi100.com/query?type=".splitx($O_wl,"|",1)."&postid=".$O_wlid,"");
echo "{\"O_wl\":\"".splitx($O_wl,"|",0)."\",\"O_wlid\":\"".$O_wlid."\",\"O_wllist\":[".$wl_list."]}";


break;
case "sitemap";

echo "{\"sitemap\":\"".gljson($sitemap)."\",\"path\":\"".gethttp().$C_domain.$C_dir."sitemap.xml\"}";

break;
case "form_list";
if(strpos($_COOKIE["formauth"],"all")!==false){
	$auth_info="";
}else{
	$formauth=explode(",",$_COOKIE["formauth"]);
	for ($i=0;$i<count($formauth)-1;$i++){
		$tj=$tj."or F_id=".intval($formauth[$i])." ";
	}
	if($tj==""){
		$auth_info=" and F_id<0";
	}else{
		$tj= substr($tj,-(strlen($tj)-3));
		$auth_info=" and (".$tj.")";
	}
}


$sql="select * from ".TABLE."form where F_del=0 and F_id<>0 ".$auth_info." order by F_id desc";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {

if($row["F_type"]!=1){
$sql2="select * from ".TABLE."content where C_del=0 and C_fid=".$row["F_id"]." order by C_order";
$result2 = mysqli_query($conn, $sql2);
if(mysqli_num_rows($result2) > 0) {
while($row2 = mysqli_fetch_assoc($result2)) {
if($row2["C_type"]=="text"){
$C_type="文本框";
}
if($row2["C_type"]=="area"){
$C_type="文本域";
}
if($row2["C_type"]=="radio"){
$C_type="单选按钮";
}
if($row2["C_type"]=="checkbox"){
$C_type="多选按钮";
}
if($row2["C_type"]=="option"){
$C_type="下拉列表";
}
if($row2["C_type"]=="pic"){
$C_type="上传图片/文件";
}
if($row2["C_type"]=="date"){
$C_type="选择日期";
}
$contentlist=$contentlist."{\"C_id\":".$row2["C_id"].",\"C_order\":".$row2["C_order"].",\"C_title\":\"".lang($row2["C_title"])."\",\"C_type\":\"".$C_type."\"},";
}
$contentlist= substr($contentlist,0,strlen($contentlist)-1);
}
}

$sql3="select distinct(R_rid) from ".TABLE."response,".TABLE."content,".TABLE."member where M_del=0 and C_del=0 and R_cid=C_id and R_member=M_id and C_fid=".$row["F_id"]." and R_read=0";
$F_num=0;
$result3 = mysqli_query($conn,  $sql3);
if (mysqli_num_rows($result3) > 0) {
	while($row3 = mysqli_fetch_assoc($result3)) {
		$F_num+=1;
	}
}

if($row["F_type"]==0){
	$F_type="信息收集";
}
if($row["F_type"]==1){
	$F_type="信息查询";
}
if($row["F_type"]==2){
	$F_type="投票系统";
}

$formlist=$formlist."{\"F_id\":".$row["F_id"].",\"F_count\":\"".$F_num."\",\"F_title\":\"".lang($row["F_title"])."\",\"F_entitle\":\"".lang($row["F_entitle"])."\",\"F_pic\":\"".$row["F_pic"]."\",\"F_type\":\"".$F_type."\",\"content_list\":[".$contentlist."]},";
$contentlist="";
$R_rid="";
}

$formlist= substr($formlist,0,strlen($formlist)-1);
}
echo "{\"form_list\":[".$formlist."]}";
break;

case "content_list";
$F_id=getrx("select * from ".TABLE."content where C_del=0 and C_id=".$id,"C_fid");
$F_title=lang(getrx("select * from ".TABLE."form where F_del=0 and F_id=".$F_id,"F_title"));

$sql2="select * from ".TABLE."content where C_del=0 and C_fid=".$F_id." order by C_order";
$result2 = mysqli_query($conn, $sql2);

if(mysqli_num_rows($result2) > 0) {
while($row2 = mysqli_fetch_assoc($result2)) {
$contentlist=$contentlist."{\"C_id\":".$row2["C_id"].",\"C_order\":".$row2["C_order"].",\"C_title\":\"".lang($row2["C_title"])."\"},";
}

$contentlist= substr($contentlist,0,strlen($contentlist)-1);
}
echo "{\"F_title\":\"".$F_title."\",\"content_list\":[".$contentlist."]}";
break;

case "qsort_list";
$sql="select * from ".TABLE."qsort order by S_id desc";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {

$sql2="Select count(*) as S_count from ".TABLE."query where Q_sort=".$row["S_id"];
$result2 = mysqli_query($conn, $sql2);
$row2 = mysqli_fetch_assoc($result2);
if(mysqli_num_rows($result2) > 0) {
$S_count=$row2["S_count"];
}

$qsortlist=$qsortlist."{\"S_id\":".$row["S_id"].",\"S_title\":\"".$row["S_title"]."\",\"S_content\":\"".gljson($row["S_content"])."\",\"S_count\":\"".$S_count."\"},";
}

$qsortlist= substr($qsortlist,0,strlen($qsortlist)-1);


}
echo "{\"qsort_list\":[".$qsortlist."]}";
break;


case "form_add";
if(strpos($_COOKIE["formauth"],"all")!==false){
$auth_info="";
}else{
$formauth=explode(",",$_COOKIE["formauth"]);
for ($i=0 ;$i<count($formauth)-1;$i++){
$tj=$tj."or F_id=".intval($formauth[$i])." ";
}
if($tj==""){
$auth_info=" and F_id<0";
}else{
$tj= substr($tj,-(strlen($tj)-3));
$auth_info=" and (".$tj.")";
}
}
if($id!="" && $id!="0"){
$sql="Select * from ".TABLE."form where F_del=0 and F_id=".$id.$auth_info;

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$F_title=lang($row["F_title"]);
$F_entitle=lang($row["F_entitle"]);
$F_bz=lang($row["F_bz"]);
$F_yz=$row["F_yz"];
$F_ip=$row["F_ip"];
$F_iptype=$row["F_iptype"];
$F_day=$row["F_day"];
$F_yzm=$row["F_yzm"];
$F_show=$row["F_show"];
$F_pic=$row["F_pic"];
$F_type=$row["F_type"];
$F_qsort=$row["F_qsort"];
$F_cq=$row["F_cq"];
$F_time=$row["F_time"];
$F_pagetitle=lang($row["F_pagetitle"]);
$F_keywords=lang($row["F_keywords"]);
$F_description=lang($row["F_description"]);
$F_limit=$row["F_limit"];
}else{
$F_type=0;
}
}else{
$F_time=date('Y-m-d H:i:s');
$F_pic="images/nopic.png";
$F_limit=0;
}

echo "{\"F_id\":\"".$id."\",\"F_title\":\"".$F_title."\",\"F_entitle\":\"".$F_entitle."\",\"F_bz\":\"".$F_bz."\",\"F_ip\":\"".$F_ip."\",\"F_iptype\":\"".$F_iptype."\",\"F_day\":\"".$F_day."\",\"F_time\":\"".$F_time."\",\"F_yz\":\"".$F_yz."\",\"F_show\":\"".$F_show."\",\"F_yzm\":\"".$F_yzm."\",\"F_pic\":\"".$F_pic."\",\"F_qsort\":\"".$F_qsort."\",\"F_cq\":\"".$F_cq."\",\"F_type\":\"".$F_type."\",\"F_pagetitle\":\"".$F_pagetitle."\",\"F_keywords\":\"".$F_keywords."\",\"F_description\":\"".$F_description."\",\"F_limit\":\"".$F_limit."\"}";

break;
case "content_add";
$sql="Select * from ".TABLE."content where C_del=0 and C_id=".$id;
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

if(mysqli_num_rows($result) > 0) {
	$C_title2=lang($row["C_title"]);
	$C_content2=lang($row["C_content"]);
	$C_type=$row["C_type"];
	$C_bz=lang($row["C_bz"]);
	$C_fid=$row["C_fid"];
	$C_order=$row["C_order"];
	$C_required=$row["C_required"];
}

echo "{\"C_id\":\"".$id."\",\"C_title\":\"".$C_title2."\",\"C_content\":\"".$C_content2."\",\"C_type\":\"".$C_type."\",\"C_bz\":\"".$C_bz."\",\"C_fid\":\"".$C_fid."\",\"C_order\":\"".$C_order."\",\"C_required\":\"".$C_required."\"}";

break;
case "response_list":
$sql="select * from ".TABLE."form where F_del=0 and F_id=".$id." order by F_id desc";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
	$F_title=$row["F_title"];
	$F_type=$row["F_type"];
}

if($F_type==0){
$tjlist=$tjlist."<table class='table m-b-none table-hover'><thead><tr><th>编号</th><th>会员</th>";
$sql="select * from ".TABLE."content where C_del=0 and C_Fid=".$id." order by C_order";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
		$tjlist=$tjlist. "<th>".lang($row["C_title"])."</th>";
	}
}
$tjlist=$tjlist. "<th>提交时间</th><th>审核</th><th>删除</th></tr></thead><tbody>";
$i=1;
$sql="select distinct(R_rid),R_time,R_member,R_read,M_login,M_pic,M_name,M_email,M_info,M_fen,M_QQ,M_add,M_mobile,M_id from ".TABLE."response,".TABLE."content,".TABLE."member where M_del=0 and R_cid=C_id and R_member=M_id and C_fid=".$id." order by R_time desc";

$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {

if($row["M_login"]=="未提供"){
	$line=$line."<tr id=\"item_".$row["R_rid"]."\"><td>".$i."</td><td>".$row["M_login"]."</td>";
}else{
	$line=$line."<tr id=\"item_".$row["R_rid"]."\"><td>".$i."</td><td><p><a href=\"#/app/member_add/".$row["M_id"]."\" title=\"<p><img src=\'../media/".$row["M_pic"]."\' width=\'120\'></p><p>帐号：".$row["M_login"]."</p><p>姓名：".$row["M_name"]."</p><p>邮箱：".$row["M_email"]."</p><p>QQ：".$row["M_qq"]."</p><p>手机：".$row["M_mobile"]."</p><p>地址：".$row["M_add"]."</p><p>积分：".$row["M_fen"]."</p><p>信息：".str_replace("|"," ",$row["M_info"])."</p>\"><i class=\"fa fa-user\"></i> ".$row["M_login"]."</a><p><p></p></td>";
}
$i=$i+1;

$sql1="select * from ".TABLE."response,".TABLE."content where C_del=0 and R_cid=C_id and R_rid='".$row["R_rid"]."' order by R_time desc,C_order";

$result1 = mysqli_query($conn, $sql1);
if(mysqli_num_rows($result1) > 0) {
	while($row1 = mysqli_fetch_assoc($result1)) {
		if(substr($row1["R_content"],-3)=="jpg" || substr($row1["R_content"],-3)=="png" || substr($row1["R_content"],-3)=="gif"){
			$R_content="<a href='../".$row1["R_content"]."' target='_blank'><img src='../".$row1["R_content"]."' height='30' width='30'> 点击查看大图</a>";
		}else{
			if(substr($row1["R_content"],-3)=="doc" || substr($row1["R_content"],-4)=="docx" || substr($row1["R_content"],-3)=="xls" || substr($row1["R_content"],-4)=="xlsx" || substr($row1["R_content"],-3)=="ppt" || substr($row1["R_content"],-4)=="pptx" || substr($row1["R_content"],-3)=="pdf" || substr($row1["R_content"],-3)=="ppf" || substr($row1["R_content"],-3)=="rar" || substr($row1["R_content"],-3)=="swf"){
				$R_content="<a href='../".$row1["R_content"]."?1' target='_blank'><img src='img/file.gif'> 点击查看附件</a>";
			}else{
				$R_content=$row1["R_content"];
			}
		}
		$line=$line. "<td style='word-wrap:break-word;'>".$R_content."</td>";
	}
}

switch($row["R_read"]){
	case 1:
	$read_info="已通过";
	break;

	case 2:
	$read_info="不通过";
	break;

	default:
	$read_info="<form id=\"form_".$row["R_rid"]."\"><textarea placeholder=\"回复内容\" name=\"reply\" style=\"width:100%\"></textarea><button onclick=\"readx('".$row["R_rid"]."')\" class='btn btn-xs btn-success'><i class='fa fa-check'></i> 通过</button> <button onclick=\"ready('".$row["R_rid"]."')\" class='btn btn-xs btn-danger'><i class='fa fa-close'></i> 不通过</button></form>";
	break;

}

$line=$line. "<td>".$row["R_time"]."</td><td id='".$row["R_rid"]."' style=\"width:150px;\">".$read_info."</td><td><a href='javascript:;' onclick=\"delx('".$row["R_rid"]."')\" class='btn btn-xs btn-warning'><i class='fa fa-times-circle'></i> 删除</a></td></tr>|";
}
}
$line=@MoveR($line);
$tjlist=$tjlist. str_Replace("|","",$line);
$tjlist=$tjlist. "</tbody></table>";
}

if($F_type==2){

	$sql="select * from ".TABLE."content where C_del=0 and C_Fid=".$id." order by C_order";
	$result = mysqli_query($conn, $sql);
	if(mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			$tjlist=$tjlist. "<div class=\"col-md-6\"><div class=\"panel panel-default\">
    <div class=\"panel-heading\">
        ".lang($row["C_title"])."
    </div>
    <div class=\"panel-body\">";
    $i=0;
    $count_all=getrx("select count(R_content) as R_count,R_content from ".TABLE."response where R_cid=".$row["C_id"]." group by R_content order by R_count desc","R_count");
	$sql2="select count(R_content) as R_count,R_content from ".TABLE."response where R_cid=".$row["C_id"]." group by R_content order by R_count desc";
	$result2 = mysqli_query($conn, $sql2);
	if(mysqli_num_rows($result2) > 0) {
		while($row2 = mysqli_fetch_assoc($result2)) {
			switch($i%5){
				case 0:
				$color="success";
				break;
				case 1:
				$color="info";
				break;
				case 2:
				$color="primary";
				break;
				case 3:
				$color="warning";
				break;
				case 4:
				$color="danger";
				break;
			}
			$tjlist=$tjlist."<p><div class=\"col-xs-2\">".$row2["R_content"]."</div><div class=\"col-xs-10\"><div class=\"progress progress-striped active\">
	<div class=\"progress-bar progress-bar-".$color."\" role=\"progressbar\"
		 aria-valuenow=\"60\" aria-valuemin=\"0\" aria-valuemax=\"100\"
		 style=\"width: ".($row2["R_count"]/$count_all*100)."%;\">
		".$row2["R_count"]."票
	</div>
</div></div></p>";
$i=$i+1;
		}
	}

    $tjlist=$tjlist."</div>
</div>
</div>";
		}
	}

$tjlist="<div class=\"panel-body\">".$tjlist."</div>";
}


echo "{\"response_list\":\"".gljson($tjlist)."\",\"F_title\":\"".lang($F_title)."\"}";

break;
case "template_sort";
$typex=$_REQUEST["typex"];
$id=$_REQUEST["id"];
if(is_file("../".$typex."/".$id."/sort.txt")){
$C_sort=file_get_contents("../".$typex."/".$id."/sort.txt");
}else{
$C_sort=1;
}
echo "{\"template_sort\":\"".$C_sort."\"}";

break;
case "template_list":

$handler = opendir('../pc');
while( ($filename = readdir($handler)) !== false ) {
	if(is_dir("../pc/".$filename) && $filename != "." && $filename != ".." && $filename != "amp" && $filename != "mip"){  

		if(is_file("../pc/".$filename."/sort.txt")){
			$T_sort=trim(file_get_contents("../pc/".$filename."/sort.txt"),"\xEF\xBB\xBF");
		}else{
			$T_sort=1;
		}

		switch(intval($T_sort)){
		case 1:
		$sort_info="企业类";
		break;
		case 2:
		$sort_info="博客类";
		break;
		case 3:
		$sort_info="政府类";
		break;
		case 4:
		$sort_info="学校类";
		break;
		case 5:
		$sort_info="医院类";
		break;
		case 6:
		$sort_info="门户类";
		break;
		default:
		$sort_info="企业类";
		}

		$folderlist=$folderlist. "{\"folername\":\"".$filename."\",\"sort\":\"".$sort_info."\",\"T_sort\":\"".$T_sort."\"},";
	}
}

$folderlist= substr($folderlist,0,strlen($folderlist)-1);

echo "{\"template_list\":[".$folderlist."]}";
break;


case "wap_list":

$handler = opendir('../wap');

while( ($filename = readdir($handler)) !== false ) {
	if(is_dir("../wap/".$filename) && $filename != "." && $filename != ".."){  


		$folderlist=$folderlist. "{\"folername\":\"".$filename."\"},";
	}
}

$folderlist= substr($folderlist,0,strlen($folderlist)-1);

echo "{\"wap_list\":[".$folderlist."]}";
break;


case "readme";
$readme=get_gb_to_utf8(file_get_contents ("../pc/".$_REQUEST["id"]."/readme.txt"));
$readme=str_Replace(PHP_EOL,"<br>",$readme);
echo $readme;
break;

case "readsql":
$readme=file_get_contents ("../backup/".$_REQUEST["id"].".txt");
echo $readme;
break;

case "readtxt":
$readme=file_get_contents($C_dirx.str_replace(".","/",$_REQUEST["folder"]).$_REQUEST["file"]);
echo $readme;
break;

case "template_all":
echo "{\"domain\":\"".$C_domain."\",\"C_authcode\":\"".gljson($C_authcode)."\",\"url\":\"".$C_domain.$C_dir.$C_admin."\",\"t_folder\":\"".getfolder("../pc")."\",\"w_folder\":\"".getfolder("../wap")."\"}";
break;

case "plug":
echo "{\"domain\":\"".$C_domain."\",\"C_authcode\":\"".gljson($C_authcode)."\",\"url\":\"".$C_domain.$C_dir.$C_admin."\"}";
break;


case "contact";
$sql="Select * from ".TABLE."contact";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$C_address=lang($row["C_address"]);
$C_zb=$row["C_zb"];
$C_title=lang($row["C_title"]);
$C_entitle=lang($row["C_entitle"]);
$C_content=str_Replace("{@SL_安装目录}",$C_dir,lang($row["C_content"]));
$C_map=$row["C_map"];
}
echo "{\"C_title\":\"".$C_title."\",\"C_entitle\":\"".$C_entitle."\",\"C_address\":\"".$C_address."\",\"C_zb\":\"".$C_zb."\",\"C_map\":\"".$C_map."\",\"C_content\":\"".gljson($C_content)."\"}";
break;
case "guestbook":

$i=1;
$G_time=date("Y-m-d H:i:s");

$sql="Select * from ".TABLE."guestbook order by G_id asc";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {

if (date("y-m-d",strtotime($G_time))!=date("y-m-d",strtotime($row["G_time"])) ){
$i=1;
}

$G_title=$row["G_title"];
$G_msg=$row["G_msg"];
$G_sh=$row["G_sh"];
$G_time=$row["G_time"];

if($G_sh==1){
	$G_shinfo="<font color='#009900'>已审核</font>";
}else{
	$G_shinfo="<font color='#FF0000'>未审核</font>";
}

$book=$book."{\"G_id\":".$row["G_id"].",\"G_i\":".$i.",\"G_title\":\"".gljson(htmlspecialchars($row["G_title"]))."\",\"G_msg\":\"".gljson(htmlspecialchars($row["G_Msg"]))."\",\"G_shinfo\":\"".gljson($G_shinfo)."\",\"G_time\":\"".$row["G_time"]."\",\"G_ip\":\"".$row["G_ip"]."\",\"G_add\":\"".$row["G_add"]."\"},";

$i=$i+1;

}
$book= substr($book,0,strlen($book)-1);

}
echo "{\"guestbook\":[".$book."]}";
break;
case "reply":
$sql="Select * from ".TABLE."guestbook Where G_id=".$id;
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$G_title=$row["G_title"];
$G_name=$row["G_name"];
$G_phone=$row["G_phone"];
$G_msg=$row["G_Msg"];
$G_email=$row["G_email"];
$G_sh=$row["G_sh"];
$G_reply=$row["G_reply"];
}
echo "{\"G_title\":\"".gljson($G_title)."\",\"G_name\":\"".gljson($G_name)."\",\"G_phone\":\"".gljson($G_phone)."\",\"G_msg\":\"".gljson($G_msg)."\",\"G_email\":\"".gljson($G_email)."\",\"G_sh\":\"".$G_sh."\",\"G_reply\":\"".gljson($G_reply)."\"}";

break;

case "m_config";
$sql="select * from ".TABLE."wap";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$W_show=$row["W_show"];
$W_phone=$row["W_phone"];
$W_email=$row["W_email"];
$W_msg=$row["W_msg"];
$W_logo=$row["W_logo"];
$W_template=$row["W_template"];
}
echo "{\"W_show\":\"".$W_show."\",\"W_phone\":\"".$W_phone."\",\"W_email\":\"".$W_email."\",\"W_msg\":\"".$W_msg."\",\"W_logo\":\"".$W_logo."\",\"W_template\":\"".$W_template."\"}";

break;

case "config2":

if($_SESSION["i"]==0){
$xmls=file_get_contents("../pc/".$C_template."/config.xml");

}
if($_SESSION["i"]==1 and is_file("../pc/".$C_template."/config_e.xml")){
$xmls=file_get_contents("../pc/".$C_template."/config_e.xml");
}else{
$xmls=file_get_contents("../pc/".$C_template."/config.xml");
}

$xml =simplexml_load_string($xmls);
for($i=0;$i<count($xml->page);$i++){

	$config2=$config2. "<tr><td rowspan='".count($xml->page[$i])."' scope='col' bgcolor='#F7F7F7' align='center'><b>".$xml->page[$i]["title"]."</b></td>";

	for($j=0;$j<count($xml->page[$i]->tag);$j++){

switch($xml->page[$i]->tag[$j]->type){

case "text":

if($j==0){
$config2=$config2. "<td scope='col' align='center'>".$xml->page[$i]->tag[$j]->title."</td><td scope='col'><textarea class='form-control' name='C_".$i.$j."' cols='40' rows='3'>".$xml->page[$i]->tag[$j]->content."</textarea></td><td scope='col'><textarea class='form-control' name='E_".$i.$j."' cols='40' rows='3'>".$xml->page[$i]->tag[$j]->en."</textarea></td><td>".$xml->page[$i]->tag[$j]->type."</td><td scope='col'><input type='text' value='".$xml->page[$i]->tag[$j]->url."' name='U_".$i.$j."' id='U_".$i.$j."' class='form-control'></td></tr>";
}else{
$config2=$config2. "<tr><td align='center'>".$xml->page[$i]->tag[$j]->title."</td><td><textarea class='form-control' name='C_".$i.$j."' cols='40' rows='3'>".$xml->page[$i]->tag[$j]->content."</textarea></td><td><textarea class='form-control' name='E_".$i.$j."' cols='40' rows='3'>".$xml->page[$i]->tag[$j]->en."</textarea></td><td>".$xml->page[$i]->tag[$j]->type."</td><td><input type='text' class='form-control' value='".$xml->page[$i]->tag[$j]->url."' name='U_".$i.$j."' id='U_".$i.$j."' class='form-control'></td></tr>";
}
break;
case "img":

if($j==0){
$config2=$config2. "<td scope='col' align='center'>".$xml->page[$i]->tag[$j]->title."</td><td scope='col'><img class='L_pic' src='../pc/".$C_template."/images/".$xml->page[$i]->tag[$j]->content."' width='100' id='C_".$i.$j."x' alt='<img src=../pc/".$C_template."/images/".$xml->page[$i]->tag[$j]->content." width=400>'><div class='input-group'><input type='text' value='".$xml->page[$i]->tag[$j]->content."' name='C_".$i.$j."' id='C_".$i.$j."' class='form-control'> <span class='input-group-btn'><button class='btn btn-info' type='button' onclick=\"showUpload('C_".$i.$j."','../pc/".$C_template."/images');\">上传文件</button></span></div></td><td scope='col'><input type='text' value='".$xml->page[$i]->tag[$j]->en."' name='E_".$i.$j."' id='E_".$i.$j."' class='form-control'></td><td scope='col'>".$xml->page[$i]->tag[$j]->type."</td><td scope='col'><input type='text' value='".$xml->page[$i]->tag[$j]->url."' name='U_".$i.$j."' id='U_".$i.$j."' class='form-control'></td></tr>";
}else{
$config2=$config2. "<tr><td align='center'>".$xml->page[$i]->tag[$j]->title."</td><td><img class='L_pic' src='../pc/".$C_template."/images/".$xml->page[$i]->tag[$j]->content."' width='100' id='C_".$i.$j."x' alt='<img src=../pc/".$C_template."/images/".$xml->page[$i]->tag[$j]->content." width=400>'><div class='input-group'><input type='text' value='".$xml->page[$i]->tag[$j]->content."' name='C_".$i.$j."' id='C_".$i.$j."' class='form-control'> <span class='input-group-btn'><button class='btn btn-info' type='button' onclick=\"showUpload('C_".$i.$j."','../pc/".$C_template."/images');\">上传文件</button></span></div></td><td><input type='text' value='".$xml->page[$i]->tag[$j]->en."' name='E_".$i.$j."' id='E_".$i.$j."' class='form-control'></td><td>".$xml->page[$i]->tag[$j]->type."</td><td><input type='text' value='".$xml->page[$i]->tag[$j]->url."' name='U_".$i.$j."' id='U_".$i.$j."' class='form-control'></td></tr>";
}

break;
}

	}
}


if($config2!=""){
$config2="<table class='table table-striped' style='min-width:1000px;'><tr><td width='10%'>页面</td><td width='10%'>标签名称</td><td width='30%'>内容</td><td width='25%'>内容（第二语言）</td><td width='5%'>类型</td><td width='20%'>链接</td></tr>".$config2."<tr><td></td><td></td><td><button type='submit' class='btn btn-info'>确认</button></td><td></td><td></td><td></td></tr></table>";
}else{
$config2="<div style='margin:20px;'>该套模板不需要自定义设置</div>";
}
echo "{\"config2\":\"".gljson($config2)."\"}";
break;



case "m_config2":

if($_SESSION["i"]==0){
$xmls=file_get_contents("../wap/".$C_wap."/config.xml");

}
if($_SESSION["i"]==1 and is_file("../wap/".$C_wap."/config_e.xml")){
$xmls=file_get_contents("../wap/".$C_wap."/config_e.xml");
}else{
$xmls=file_get_contents("../wap/".$C_wap."/config.xml");
}

$xml =simplexml_load_string($xmls);
for($i=0;$i<count($xml->page);$i++){

	$config2=$config2. "<tr><td rowspan='".count($xml->page[$i])."' scope='col' bgcolor='#F7F7F7' align='center'><b>".$xml->page[$i]["title"]."</b></td>";

	for($j=0;$j<count($xml->page[$i]->tag);$j++){

switch($xml->page[$i]->tag[$j]->type){

case "text":

if($j==0){
$config2=$config2. "<td scope='col' align='center'>".$xml->page[$i]->tag[$j]->title."</td><td scope='col'><textarea class='form-control' name='C_".$i.$j."' cols='40' rows='3'>".$xml->page[$i]->tag[$j]->content."</textarea></td><td scope='col'><textarea class='form-control' name='E_".$i.$j."' cols='40' rows='3'>".$xml->page[$i]->tag[$j]->en."</textarea></td><td>".$xml->page[$i]->tag[$j]->type."</td><td scope='col'><input type='text' value='".$xml->page[$i]->tag[$j]->url."' name='U_".$i.$j."' id='U_".$i.$j."' class='form-control'></td></tr>";
}else{
$config2=$config2. "<tr><td align='center'>".$xml->page[$i]->tag[$j]->title."</td><td><textarea class='form-control' name='C_".$i.$j."' cols='40' rows='3'>".$xml->page[$i]->tag[$j]->content."</textarea></td><td><textarea class='form-control' name='E_".$i.$j."' cols='40' rows='3'>".$xml->page[$i]->tag[$j]->en."</textarea></td><td>".$xml->page[$i]->tag[$j]->type."</td><td><input type='text' class='form-control' value='".$xml->page[$i]->tag[$j]->url."' name='U_".$i.$j."' id='U_".$i.$j."' class='form-control'></td></tr>";
}
break;
case "img":

if($j==0){
$config2=$config2. "<td scope='col' align='center'>".$xml->page[$i]->tag[$j]->title."</td><td scope='col'><img class='L_pic' src='../wap/".$C_wap."/images/".$xml->page[$i]->tag[$j]->content."' width='100' id='C_".$i.$j."x' alt='<img src=../wap/".$C_wap."/images/".$xml->page[$i]->tag[$j]->content." width=400>'><div class='input-group'><input type='text' value='".$xml->page[$i]->tag[$j]->content."' name='C_".$i.$j."' id='C_".$i.$j."' class='form-control'> <span class='input-group-btn'><button class='btn btn-info' type='button' onclick=\"showUpload('C_".$i.$j."','../wap/".$C_wap."/images');\">上传文件</button></span></div></td><td scope='col'><input type='text' class='form-control' value='".$xml->page[$i]->tag[$j]->en."' name='E_".$i.$j."' id='E_".$i.$j."'></td><td scope='col'>".$xml->page[$i]->tag[$j]->type."</td><td scope='col'><input type='text' value='".$xml->page[$i]->tag[$j]->url."' name='U_".$i.$j."' id='U_".$i.$j."' class='form-control'></td></tr>";
}else{
$config2=$config2. "<tr><td align='center'>".$xml->page[$i]->tag[$j]->title."</td><td><img class='L_pic' src='../wap/".$C_wap."/images/".$xml->page[$i]->tag[$j]->content."' width='100' id='C_".$i.$j."x' alt='<img src=../wap/".$C_wap."/images/".$xml->page[$i]->tag[$j]->content." width=400>'><div class='input-group'><input type='text' value='".$xml->page[$i]->tag[$j]->content."' name='C_".$i.$j."' id='C_".$i.$j."' class='form-control'> <span class='input-group-btn'><button class='btn btn-info' type='button' onclick=\"showUpload('C_".$i.$j."','../wap/".$C_wap."/images');\">上传文件</button></span></div></td><td><input type='text' class='form-control' value='".$xml->page[$i]->tag[$j]->en."' name='E_".$i.$j."' id='E_".$i.$j."'></td><td>".$xml->page[$i]->tag[$j]->type."</td><td><input type='text' value='".$xml->page[$i]->tag[$j]->url."' name='U_".$i.$j."' id='U_".$i.$j."' class='form-control'></td></tr>";
}

break;
}

	}
}


if($config2!=""){
$config2="<table class='table table-striped' style='min-width:1000px;'><tr><td width='10%'>页面</td><td width='10%'>标签名称</td><td width='30%'>内容</td><td width='25%'>内容（第二语言）</td><td width='5%'>类型</td><td width='20%'>链接</td></tr>".$config2."<tr><td></td><td></td><td><button type='submit' class='btn btn-info'>确认</button></td><td></td><td></td><td></td></tr></table>";
}else{
$config2="<div style='margin:20px;'>该套模板不需要自定义设置</div>";
}
echo "{\"m_config2\":\"".gljson($config2)."\"}";
break;


case "menu_list";
if($_GET["from"]=="wxapp"){
	$sql="select * from ".TABLE."menu where U_del=0 and U_sub=0 and not U_type='link' order by U_order";
}else{
	$sql="select * from ".TABLE."menu where U_del=0 and U_sub=0 order by U_order";
}
$result = mysqli_query($conn,  $sql);
if (mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {

if($row["U_type"]=="index"){
$U_type="首页";
}
if($row["U_type"]=="text"){
$U_type="简介";
}
if($row["U_type"]=="product"){
$U_type="产品";
}
if($row["U_type"]=="news"){
$U_type="新闻";
}
if($row["U_type"]=="form"){
$U_type="表单";
}
if($row["U_type"]=="contact"){
$U_type="联系";
}
if($row["U_type"]=="guestbook"){
$U_type="留言";
}
if($row["U_type"]=="link"){
$U_type="链接";
}
if($row["U_hide"]==0){
$selected0="selected='selected'";
$selected1=" ";
}else{
$selected0=" ";
$selected1="selected='selected'";
}
$menu_sub="<select name='sub_".$row["U_id"]."'><option  value='0' selected='selected'>主菜单</option>";
if($row["U_type"]!=="sub"){
$sql3="Select * from ".TABLE."menu where U_del=0 and U_sub=0 and not U_id=".$row["U_id"]." order by U_order";
$result3 = mysqli_query($conn,  $sql3);
if (mysqli_num_rows($result3) > 0) {
while($row3 = mysqli_fetch_assoc($result3)) {
$menu_sub=$menu_sub."<option value='".$row3["U_id"]."'>".lang($row3["U_title"])."</option>";

}
}

}else{
$menu_sub=$menu_sub."</select>";
}
$sql1="select * from ".TABLE."menu where U_del=0 and U_sub=".$row["U_id"]." order by U_order";

$result1 = mysqli_query($conn,  $sql1);
if (mysqli_num_rows($result1) > 0) {
while($row1 = mysqli_fetch_assoc($result1)) {

if($row1["U_type"]=="index"){
$U_type1="首页";
}
if($row1["U_type"]=="text"){
$U_type1="简介";
}
if($row1["U_type"]=="product"){
$U_type1="产品";
}
if($row1["U_type"]=="news"){
$U_type1="新闻";
}
if($row1["U_type"]=="form"){
$U_type1="表单";
}
if($row1["U_type"]=="contact"){
$U_type1="联系";
}
if($row1["U_type"]=="guestbook"){
$U_type1="留言";
}
if($row1["U_type"]=="link"){
$U_type1="链接";
}
if($row1["U_hide"]==0){
$selected0="selected='selected'";
$selected1=" ";
}else{
$selected0=" ";
$selected1="selected='selected'";
}
$menu_sub2="<select name='sub_".$row1["U_id"]."'><option value='0'>主菜单</option>";
$sql3="Select * from ".TABLE."menu where U_del=0 and U_sub=0 order by U_order";

$result3 = mysqli_query($conn,  $sql3);
if (mysqli_num_rows($result3) > 0) {
while($row3 = mysqli_fetch_assoc($result3)) {

if($row1["U_sub"]==$row3["U_id"]){
$sub_select2="selected='selected'";
}else{
$sub_select2=" ";
}
$menu_sub2=$menu_sub2."<option value='".$row3["U_id"]."' ".$sub_select2.">".lang($row3["U_title"])."</option>";

}
}

$menu_sub2=$menu_sub2."</select>";
$submenu=$submenu."{\"U_id\":".$row1["U_id"].",\"U_order\":".$row1["U_order"].",\"U_type\":\"".$row1["U_type"]."\",\"U_typeid\":".$row1["U_typeid"].",\"U_title\":\"".gljson(lang($row1["U_title"]))."\",\"U_entitle\":\"".gljson(lang($row1["U_entitle"]))."\",\"U_typeinfo\":\"".$U_type1."\",\"U_ico\":\"".gljson($row1["U_ico"])."\",\"U_color\":\"".$row1["U_color"]."\",\"U_url\":\"".gljson($row1["U_url"])."\",\"U_hide\":\"".$row1["U_hide"]."\",\"U_sub\":\"".gljson($menu_sub2)."\"},";

}

$submenu= substr($submenu,0,strlen($submenu)-1);

}

$menu=$menu."{\"U_id\":".$row["U_id"].",\"U_order\":".$row["U_order"].",\"U_type\":\"".$row["U_type"]."\",\"U_typeid\":".$row["U_typeid"].",\"U_title\":\"".gljson(lang($row["U_title"]))."\",\"U_entitle\":\"".gljson(lang($row["U_entitle"]))."\",\"U_typeinfo\":\"".$U_type."\",\"U_ico\":\"".gljson($row["U_ico"])."\",\"U_color\":\"".$row["U_color"]."\",\"U_url\":\"".gljson($row["U_url"])."\",\"U_hide\":\"".$row["U_hide"]."\",\"U_sub\":\"".gljson($menu_sub)."\",\"sub_menu\":[".$submenu."]},";
$submenu="";
}

$menu= substr($menu,0,strlen($menu)-1);

}
echo "{\"main_menu\":[".$menu."]}";

break;

case "menu_add";
	if($id!="" && $id!="0"){
		$sql="Select * from ".TABLE."menu where U_del=0 and U_id=".$id;
		$result = mysqli_query($conn, $sql);
		$row = mysqli_fetch_assoc($result);
		if(mysqli_num_rows($result) > 0) {
			$U_title=lang($row["U_title"]);
			$U_entitle=lang($row["U_entitle"]);
			$U_order=$row["U_order"];
			$U_id=$row["U_id"];
			$U_sub=$row["U_sub"];
			$U_ico=$row["U_ico"];
			$U_color=$row["U_color"];
			$U_type=$row["U_type"];
			$U_template=$row["U_template"];
			$U_wap=$row["U_wap"];
			$U_typeid=$row["U_typeid"];
			$U_hide=$row["U_hide"];
			$U_bg=$row["U_bg"];
			$U_tabbar=$row["U_tabbar"];

			if(strpos($row["U_url"],"|")===false){
				$U_url=$row["U_url"];
				$U_target="blank";
			}else{
				$U_url=splitx($row["U_url"],"|",0);
				$U_target=splitx($row["U_url"],"|",1);
			}

		}
	}

	if($U_type=="link"){
		$U_link=1;
	}else{
		$U_link=0;
	}

	$handler = opendir("../pc/".$C_template);
	while( ($filename = readdir($handler)) !== false ){

	if(is_file("../pc/".$C_template."/".$filename) && @strpos($filename,$U_type)!==false && strpos($filename,"info")===false && strpos($filename,"_e")===false && strtolower(substr($filename,strrpos($filename,'.')+1))=="tpl"){

	 	$tall=$tall."{\"name\":\"".$filename."\",\"path\":\"pc/".$C_template."/".$filename."\"},";
	  }
	}

	if(strlen($tall)>1){
		$tall= substr($tall,0,strlen($tall)-1);
	}

	$handler = opendir("../wap/".$C_wap);
	while( ($filename = readdir($handler)) !== false ){

	if(is_file("../wap/".$C_wap."/".$filename) && @strpos($filename,$U_type)!==false && strpos($filename,"info")===false && strpos($filename,"_e")===false && strtolower(substr($filename,strrpos($filename,'.')+1))=="tpl"){

	 	$wall=$wall."{\"name\":\"".$filename."\",\"path\":\"wap/".$C_wap."/".$filename."\"},";
	  }
	}

	if(strlen($wall)>1){
		$wall= substr($wall,0,strlen($wall)-1);
	}


	if($id!="" && $id!="0"){
		$sql1="Select * from ".TABLE."menu where U_del=0 and U_sub=0 and not U_id=".$id." order by U_order";
	}else{
		$sql1="Select * from ".TABLE."menu where U_del=0 and U_sub=0 order by U_order";
	}

	$result1 = mysqli_query($conn, $sql1);
	if(mysqli_num_rows($result1) > 0) {
		while($row1 = mysqli_fetch_assoc($result1)) {
			$mall=$mall."{\"U_id\":\"".$row1["U_id"]."\",\"U_title\":\"".lang($row1["U_title"])."\"},";
		}
	}

	$mall= substr($mall,0,strlen($mall)-1);

echo "{\"U_id\":\"".$id."\",\"U_title\":\"".gljson($U_title)."\",\"U_entitle\":\"".gljson($U_entitle)."\",\"U_order\":\"".$U_order."\",\"U_sub\":\"".$U_sub."\",\"U_ico\":\"".gljson($U_ico)."\",\"U_color\":\"".gljson($U_color)."\",\"U_type\":\"".$U_type."\",\"U_link\":\"".gljson($U_link)."\",\"U_template\":\"".$U_template."\",\"U_wap\":\"".$U_wap."\",\"U_typeid\":\"".$U_typeid."\",\"U_hide\":\"".$U_hide."\",\"U_url\":\"".gljson($U_url)."\",\"U_target\":\"".$U_target."\",\"U_bg\":\"".gljson($U_bg)."\",\"U_tabbar\":\"".gljson($U_tabbar)."\",\"t_all\":[".$tall."],\"w_all\":[".$wall."],\"m_all\":[".$mall."]}";
break;

case "menu_html":
$handler = opendir("../pc/".$C_template);
while(($filename = readdir($handler)) !== false ){
 if(is_file("../pc/".$C_template."/".$filename) && strpos($filename,splitx($_GET["html"],"/",0))!==false && strpos($filename,"info")===false && strpos($filename,"_e")===false && strtolower(substr($filename,strrpos($filename,'.')+1))=="tpl"){
 	$tall=$tall."{\"name\":\"".$filename."\",\"path\":\"pc/".$C_template."/".$filename."\"},";
  }
}

if(strlen($tall)>1){
	$tall= substr($tall,0,strlen($tall)-1);
}


$handler = opendir("../wap/".$C_wap);
while(($filename = readdir($handler)) !== false ){
 if(is_file("../wap/".$C_wap."/".$filename) && strpos($filename,splitx($_GET["html"],"/",0))!==false && strpos($filename,"info")===false && strpos($filename,"_e")===false && strtolower(substr($filename,strrpos($filename,'.')+1))=="tpl"){
 	$wall=$wall."{\"name\":\"".$filename."\",\"path\":\"wap/".$C_wap."/".$filename."\"},";
  }
}

if(strlen($wall)>1){
	$wall= substr($wall,0,strlen($wall)-1);
}


if(splitx($_GET["html"],"/",1)>0){
	switch(splitx($_GET["html"],"/",0)){
		case "text":
		$title=lang(getrx("select * from ".TABLE."text where T_id=".splitx($_GET["html"],"/",1),"T_title"));
		$entitle=lang(getrx("select * from ".TABLE."text where T_id=".splitx($_GET["html"],"/",1),"T_entitle"));
		break;
		case "form":
		$title=lang(getrx("select * from ".TABLE."form where F_id=".splitx($_GET["html"],"/",1),"F_title"));
		$entitle=lang(getrx("select * from ".TABLE."form where F_id=".splitx($_GET["html"],"/",1),"F_entitle"));
		break;
		case "news":
		$title=lang(getrx("select * from ".TABLE."nsort where S_id=".splitx($_GET["html"],"/",1),"S_title"));
		$entitle=lang(getrx("select * from ".TABLE."nsort where S_id=".splitx($_GET["html"],"/",1),"S_entitle"));
		break;
		case "product":
		$title=lang(getrx("select * from ".TABLE."psort where S_id=".splitx($_GET["html"],"/",1),"S_title"));
		$entitle=lang(getrx("select * from ".TABLE."psort where S_id=".splitx($_GET["html"],"/",1),"S_entitle"));
		break;
		case "contact":
		$title="联系方式";
		$entitle="Contact";
		break;
		case "guestbook":
		$title="在线留言";
		$entitle="Guestbook";
		break;
		case "index":
		$title="网站首页";
		$entitle="Home";
		break;
	}
}else{
	$title="";
}

echo "{\"t_all\":[".$tall."],\"w_all\":[".$wall."],\"T_title\":\"".$title."\",\"T_entitle\":\"".$entitle."\"}";

break;


case "module";
$sql1="Select T_title,T_id from ".TABLE."text where T_del=0";

$result1 = mysqli_query($conn,  $sql1);
if (mysqli_num_rows($result1) > 0) {
while($row1 = mysqli_fetch_assoc($result1)) {
$module=$module. "{\"title\":\"└ ".gljson(lang($row1["T_title"]))."\",\"id\":\"".$row1["T_id"]."\",\"type\":\"text\"},";

}

$module= substr($module,0,strlen($module)-1);

}

$modules=$modules."{\"title\":\"简介模块\",\"module\":[".$module."]},";
$module="";
$sql1="Select S_title,S_id from ".TABLE."nsort where S_sub=0 and S_del=0 order by S_order,S_id desc";

$result1 = mysqli_query($conn,  $sql1);
if (mysqli_num_rows($result1) > 0) {
while($row1 = mysqli_fetch_assoc($result1)) {
$module=$module. "{\"title\":\"└ ".lang($row1["S_title"])."\",\"id\":\"".$row1["S_id"]."\",\"type\":\"news\"},";
$sql2="Select S_title,S_id from ".TABLE."nsort where S_sub=".$row1["S_id"]." and S_del=0 order by S_order,S_id desc";

$result2 = mysqli_query($conn,  $sql2);
if (mysqli_num_rows($result2) > 0) {
while($row2 = mysqli_fetch_assoc($result2)) {
$module=$module. "{\"title\":\"└─ ".lang($row2["S_title"])."\",\"id\":\"".$row2["S_id"]."\",\"type\":\"news\"},";
}
}

}
$module="{\"title\":\"所有新闻\",\"id\":\"0\",\"type\":\"news\"},".$module;

$module= substr($module,0,strlen($module)-1);
}

$modules=$modules."{\"title\":\"新闻模块\",\"module\":[".$module."]},";
$module="";

$sql1="Select S_title,S_id from ".TABLE."psort where S_sub=0 and S_del=0";
$result1 = mysqli_query($conn,  $sql1);
if (mysqli_num_rows($result1) > 0) {
while($row1 = mysqli_fetch_assoc($result1)) {
$module=$module. "{\"title\":\"└ ".lang($row1["S_title"])."\",\"id\":\"".$row1["S_id"]."\",\"type\":\"product\"},";
$sql2="Select S_title,S_id from ".TABLE."psort where S_del=0 and S_sub=".$row1["S_id"];

$result2 = mysqli_query($conn,  $sql2);
if (mysqli_num_rows($result2) > 0) {
while($row2 = mysqli_fetch_assoc($result2)) {
$module=$module. "{\"title\":\"└─ ".lang($row2["S_title"])."\",\"id\":\"".$row2["S_id"]."\",\"type\":\"product\"},";
}
}

}
$module="{\"title\":\"所有产品\",\"id\":\"0\",\"type\":\"product\"},".$module;
$module= substr($module,0,strlen($module)-1);
}

$modules=$modules."{\"title\":\"产品模块\",\"module\":[".$module."]},";
$module="";

$sql1="Select F_title,F_id from ".TABLE."form where F_del=0";
$result1 = mysqli_query($conn,  $sql1);
if (mysqli_num_rows($result1) > 0) {
while($row1 = mysqli_fetch_assoc($result1)) {
$module=$module. "{\"title\":\"└ ".lang($row1["F_title"])."\",\"id\":\"".$row1["F_id"]."\",\"type\":\"form\"},";

}
$module= substr($module,0,strlen($module)-1);
}

$modules=$modules."{\"title\":\"表单模块\",\"module\":[".$module."]},";
$module="";


$modules= substr($modules,0,strlen($modules)-1);
echo "{\"modules\":[".$modules."]}";

break;

case "admin_list";
if(getrx("select * from ".TABLE."admin where A_login='".$_SESSION["user"]."'","A_type")==0){
	$sql="select * from ".TABLE."admin where A_del=0 and A_login='".$_SESSION["user"]."'";
}else{
	$sql="select * from ".TABLE."admin where A_del=0";
}
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
if(splitx($row["A_part"],"|",0)!=1){
$CA_a0info="<font color='#ff0000'>×</font>";
}else{
$CA_a0info="<font color='#009900'>√</font>";
}
if(splitx($row["A_part"],"|",1)!=1){
$CA_a1info="<font color='#ff0000'>×</font>";
}else{
$CA_a1info="<font color='#009900'>√</font>";
}
if(splitx($row["A_part"],"|",2)!=1){
$CA_a2info="<font color='#ff0000'>×</font>";
}else{
$CA_a2info="<font color='#009900'>√</font>";
}
if(splitx($row["A_part"],"|",3)!=1){
$CA_a3info="<font color='#ff0000'>×</font>";
}else{
$CA_a3info="<font color='#009900'>√</font>";
}
if(splitx($row["A_part"],"|",4)!=1){
$CA_a4info="<font color='#ff0000'>×</font>";
}else{
$CA_a4info="<font color='#009900'>√</font>";
}
if(splitx($row["A_part"],"|",5)!=1){
$CA_a5info="<font color='#ff0000'>×</font>";
}else{
$CA_a5info="<font color='#009900'>√</font>";
}
if(splitx($row["A_part"],"|",6)!=1){
$CA_a6info="<font color='#ff0000'>×</font>";
}else{
$CA_a6info="<font color='#009900'>√</font>";
}
if(splitx($row["A_part"],"|",7)!=1){
$CA_a7info="<font color='#ff0000'>×</font>";
}else{
$CA_a7info="<font color='#009900'>√</font>";
}
if(splitx($row["A_part"],"|",8)!=1){
$CA_a8info="<font color='#ff0000'>×</font>";
}else{
$CA_a8info="<font color='#009900'>√</font>";
}
if(splitx($row["A_part"],"|",9)!=1){
$CA_a9info="<font color='#ff0000'>×</font>";
}else{
$CA_a9info="<font color='#009900'>√</font>";
}
if(splitx($row["A_part"],"|",10)!=1){
$CA_a10info="<font color='#ff0000'>×</font>";
}else{
$CA_a10info="<font color='#009900'>√</font>";
}
if(splitx($row["A_part"],"|",11)!=1){
$CA_a11info="<font color='#ff0000'>×</font>";
}else{
$CA_a11info="<font color='#009900'>√</font>";
}
if(splitx($row["A_part"],"|",12)!=1){
$CA_a12info="<font color='#ff0000'>×</font>";
}else{
$CA_a12info="<font color='#009900'>√</font>";
}
$adminlist=$adminlist."{\"A_id\":".$row["A_id"].",\"A_login\":\"".$row["A_login"]."\",\"A_type\":\"".$row["A_type"]."\",\"A_part\":\"后台首页 ".$CA_a0info."  基本设置 ".$CA_a1info."  简介管理 ".$CA_a2info."  新闻管理 ".$CA_a3info."  产品管理 ".$CA_a4info."   万能表单 ".$CA_a5info." 论坛管理 ".$CA_a12info." <br> 模板插件 ".$CA_a6info."  联系方式 ".$CA_a8info."  手机版本 ".$CA_a10info."  菜单管理 ".$CA_a7info."  账号管理 ".$CA_a9info." 网站安全 ".$CA_a11info."\"},";
}

$adminlist= substr($adminlist,0,strlen($adminlist)-1);

}
echo "{\"admin_list\":[".$adminlist."]}";

break;


case "admin_add";
$sql="Select * from ".TABLE."admin where A_id=".$id;
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
	$A_login=$row["A_login"];
	$A_pwd=$row["A_pwd"];
	$A_email=$row["A_email"];
	$A_type=$row["A_type"];
	$A_textauth=$row["A_textauth"];
	$A_newsauth=$row["A_newsauth"];
	$A_productauth=$row["A_productauth"];
	$A_formauth=$row["A_formauth"];
	$A_bbsauth=$row["A_bbsauth"];
	$A_part=$row["A_part"];
	$A_a0=splitx($A_part,"|",0);
	$A_a1=splitx($A_part,"|",1);
	$A_a2=splitx($A_part,"|",2);
	$A_a3=splitx($A_part,"|",3);
	$A_a4=splitx($A_part,"|",4);
	$A_a5=splitx($A_part,"|",5);
	$A_a6=splitx($A_part,"|",6);
	$A_a7=splitx($A_part,"|",7);
	$A_a8=splitx($A_part,"|",8);
	$A_a9=splitx($A_part,"|",9);
	$A_a10=splitx($A_part,"|",10);
	$A_a11=splitx($A_part,"|",11);
	$A_a12=splitx($A_part,"|",12);
}

$sql="select * from ".TABLE."text where T_del=0 order by T_id desc";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
		$text=$text.lang($row["T_title"]).",";
		$text_id=$text_id.$row["T_id"].",";
	}
}

$text=",".$text;
$text_id=",".$text_id;
$tt=explode(",",$text);
$tt_id=explode(",",$text_id);
for ($i=1 ;$i< count($tt)-1;$i++){
	if(strpos(",".$A_textauth.",",",".$tt_id[$i].",")!==false){
		$check_info="checked=\"checked\"";
	}
	$textauth=$textauth."<label class='i-checks' style='margin-right:10px;'><input type='checkbox' name='A_textauth[]' value='".$tt_id[$i]."' ".$check_info."><i></i>".$tt[$i]."</label>";
	$check_info="";
}
if(strpos($A_textauth,"all")!==false){
	$textauth=$textauth."<label class='i-checks' style='margin-right:10px;'><input type='checkbox' name='A_textauth[]' value='all' checked='checked'><i></i>全部</label>";
}else{
	$textauth=$textauth."<label class='i-checks' style='margin-right:10px;'><input type='checkbox' name='A_textauth[]' value='all'><i></i>全部</label>";
}

$sql="select * from ".TABLE."form where F_del=0 order by F_id desc";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
		$form=$form.lang($row["F_title"]).",";
		$form_id=$form_id.$row["F_id"].",";
	}
}

$form=",".$form;
$form_id=",".$form_id;
$ff=explode(",",$form);
$ff_id=explode(",",$form_id);
for ($i=1 ;$i<count($ff)-1;$i++){
	if(strpos(",".$A_formauth.",",",".$ff_id[$i].",")!==false){
		$check_info="checked=\"checked\"";
	}
	$formauth=$formauth."<label class='i-checks' style='margin-right:10px;'><input type='checkbox' name='A_formauth[]' value='".$ff_id[$i]."' ".$check_info."><i></i>".$ff[$i]."</label>";
	$check_info="";
}
if(strpos($A_formauth,"all")!==false){
	$formauth=$formauth."<label class='i-checks' style='margin-right:10px;'><input type='checkbox' name='A_formauth[]' value='all' checked='checked'><i></i>全部</label>";
}else{
	$formauth=$formauth."<label class='i-checks' style='margin-right:10px;'><input type='checkbox' name='A_formauth[]' value='all'><i></i>全部</label>";
}

$sql="select * from ".TABLE."bsort where S_del=0 order by S_order,S_id desc";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
		$bsort=$bsort.lang($row["S_title"]).",";
		$bsort_id=$bsort_id.$row["S_id"].",";
	}
}

$bsort=",".$bsort;
$bsort_id=",".$bsort_id;
$bbs=explode(",",$bsort);
$bbs_id=explode(",",$bsort_id);
for ($i=1; $i<count($bbs)-1;$i++){
	if(strpos(",".$A_bbsauth.",",",".$bbs_id[$i].",")!==false){
		$check_info="checked=\"checked\"";
	}
	$bbsauth=$bbsauth."<label class='i-checks' style='margin-right:10px;'><input type='checkbox' name='A_bbsauth[]' value='".$bbs_id[$i]."' ".$check_info."><i></i>".$bbs[$i]."</label>";
	$check_info="";
}
if(strpos($A_bbsauth,"all")!==false){
	$bbsauth=$bbsauth."<label class='i-checks' style='margin-right:10px;'><input type='checkbox' name='A_bbsauth[]' value='all' checked='checked'><i></i>全部</label>";
}else{
	$bbsauth=$bbsauth."<label class='i-checks' style='margin-right:10px;'><input type='checkbox' name='A_bbsauth[]' value='all'><i></i>全部</label>";
}

$sql="select * from ".TABLE."nsort where not S_sub=0 and S_del=0 order by S_order,S_id desc";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
		$nsort=$nsort.lang($row["S_title"]).",";
		$nsort_id=$nsort_id.$row["S_id"].",";
	}
}

$nsort=",".$nsort;
$nsort_id=",".$nsort_id;
$news=explode(",",$nsort);
$news_id=explode(",",$nsort_id);
for ($i=1 ;$i< count($news)-1;$i++){
if(strpos(",".$A_newsauth.",",",".$news_id[$i].",")!==false){
$check_info="checked=\"checked\"";
}
$newsauth=$newsauth."<label class='i-checks' style='margin-right:10px;'><input type='checkbox' name='A_newsauth[]' value='".$news_id[$i]."' ".$check_info."><i></i>".$news[$i]."</label>";
$check_info="";
}
if(strpos($A_newsauth,"all")!==false){
$newsauth=$newsauth."<label class='i-checks' style='margin-right:10px;'><input type='checkbox' name='A_newsauth[]' value='all' checked='checked'><i></i>全部</label>";
}else{
$newsauth=$newsauth."<label class='i-checks' style='margin-right:10px;'><input type='checkbox' name='A_newsauth[]' value='all'><i></i>全部</label>";
}

$sql="select * from ".TABLE."psort where S_sub<>0 and S_del=0 order by S_id desc";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$psort=$psort.lang($row["S_title"]).",";
$psort_id=$psort_id.$row["S_id"].",";
}
}

$psort=",".$psort;
$psort_id=",".$psort_id;
$product=explode(",",$psort);
$product_id=explode(",",$psort_id);
for ($i=1 ;$i< count($product)-1;$i++){
if(strpos(",".$A_productauth.",",",".$product_id[$i].",")!==false){
$check_info="checked=\"checked\"";
}
$productauth=$productauth."<label class='i-checks' style='margin-right:10px;'><input type='checkbox' name='A_productauth[]' value='".$product_id[$i]."' ".$check_info."><i></i>".$product[$i]."</label>";
$check_info="";
}
if(strpos($A_productauth,"all")!==false){
$productauth=$productauth."<label class='i-checks' style='margin-right:10px;'><input type='checkbox' name='A_productauth[]' value='all' checked='checked'><i></i>全部</label>";
}else{
$productauth=$productauth."<label class='i-checks' style='margin-right:10px;'><input type='checkbox' name='A_productauth[]' value='all'><i></i>全部</label>";
}

echo "{\"list\":[{\"A_login\":\"".$A_login."\",\"A_pwd\":\"".$A_pwd."\",\"A_email\":\"".$A_email."\",\"A_type\":\"".$A_type."\",\"newsauth\":\"".gljson($newsauth)."\",\"productauth\":\"".gljson($productauth)."\",\"textauth\":\"".gljson($textauth)."\",\"formauth\":\"".gljson($formauth)."\",\"bbsauth\":\"".gljson($bbsauth)."\",\"A_a0\":\"".$A_a0."\",\"A_a1\":\"".$A_a1."\",\"A_a2\":\"".$A_a2."\",\"A_a3\":\"".$A_a3."\",\"A_a4\":\"".$A_a4."\",\"A_a5\":\"".$A_a5."\",\"A_a6\":\"".$A_a6."\",\"A_a7\":\"".$A_a7."\",\"A_a8\":\"".$A_a8."\",\"A_a9\":\"".$A_a9."\",\"A_a10\":\"".$A_a10."\",\"A_a11\":\"".$A_a11."\",\"A_a12\":\"".$A_a12."\"}],\"A_type\":\"".getrx("select * from ".TABLE."admin where A_login='".$_SESSION["user"]."'","A_type")."\"}";

break;

case "fen_list";
$sql="select * from ".TABLE."list where L_type=1 order by L_id desc";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
if($row["L_change"]>0){
$c_info="+";
}else{
$c_info="";
}
$fenlist=$fenlist. "{\"L_id\":\"".$row["L_id"]."\",\"L_time\":\"".$row["L_time"]."\",\"L_member\":\"".getrx("select * from ".TABLE."member where M_id=".$row["L_mid"],"M_login")."\",\"L_mid\":\"".$row["L_mid"]."\",\"L_title\":\"".$row["L_title"]."\",\"L_change\":\"".$c_info.$row["L_change"]."\",\"L_no\":\"".$row["L_no"]."\",\"L_sh\":\"".$row["L_sh"]."\"},";
}

$fenlist= substr($fenlist,0,strlen($fenlist)-1);

}
echo "{\"fen_list\":[".$fenlist."]}";
break;

case "invoice_list";
$sql="select * from ".TABLE."invoice order by I_id desc";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$invoicelist=$invoicelist. "{\"I_id\":\"".$row["I_id"]."\",\"I_time\":\"".$row["I_time"]."\",\"I_member\":\"".getrx("select * from ".TABLE."member where M_id=".$row["I_mid"],"M_login")."\",\"I_mid\":\"".$row["I_mid"]."\",\"I_company\":\"".$row["I_company"]."\",\"I_money\":\"".$row["I_money"]."\",\"I_list\":\"".$row["I_list"]."\",\"I_no\":\"".$row["I_no"]."\",\"I_sh\":\"".$row["I_sh"]."\"},";
}

$invoicelist= substr($invoicelist,0,strlen($invoicelist)-1);

}
echo "{\"invoice_list\":[".$invoicelist."]}";

break;
case "money_list":
$sql="select * from ".TABLE."list where L_type=0 order by L_id desc";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
if($row["L_change"]>0){
$c_info="+";
}else{
$c_info="";
}
$fenlist=$fenlist. "{\"L_id\":\"".$row["L_id"]."\",\"L_time\":\"".$row["L_time"]."\",\"L_member\":\"".getrx("select * from ".TABLE."member where M_id=".$row["L_mid"],"M_login")."\",\"L_mid\":\"".$row["L_mid"]."\",\"L_title\":\"".$row["L_title"]."\",\"L_change\":\"".$c_info.$row["L_change"]."\",\"L_no\":\"".$row["L_no"]."\",\"L_sh\":\"".$row["L_sh"]."\"},";
}

$fenlist= substr($fenlist,0,strlen($fenlist)-1);

}
echo "{\"money_list\":[".$fenlist."]}";
break;


case "comment_list";
$sql="select * from ".TABLE."comment,".TABLE."member where M_del=0 and C_mid=M_id order by C_id desc";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
switch(splitx($row["C_page"],"_",0)){
case "text":
$page_title=lang(getrx("select * from ".TABLE."text where T_id=".splitx($row["C_page"],"_",1),"T_title"));
$page_url="#/app/text_add/".splitx($row["C_page"],"_",1);
break;
case "newsinfo":
$page_title=lang(getrx("select * from ".TABLE."news where N_id=".splitx($row["C_page"],"_",1),"N_title"));
$page_url="#/app/news_add/".splitx($row["C_page"],"_",1);
break;
case "productinfo":
$page_title=lang(getrx("select * from ".TABLE."product where P_id=".splitx($row["C_page"],"_",1),"P_title"));
$page_url="#/app/product_add/".splitx($row["C_page"],"_",1);
break;
}
if(substr($row["M_pic"],0,4)!="http"){
$M_pic="../media/".$row["M_pic"];
}else{
$M_pic=$row["M_pic"];
}
$fenlist=$fenlist. "{\"C_id\":\"".$row["C_id"]."\",\"C_time\":\"".$row["C_time"]."\",\"C_member\":\"".$row["M_login"]."\",\"C_mid\":\"".$row["C_mid"]."\",\"C_content\":\"".gljson($row["C_content"])."\",\"C_sh\":\"".$row["C_sh"]."\",\"C_pagetitle\":\"".gljson($page_title)."\",\"C_pageurl\":\"".gljson($page_url)."\",\"C_head\":\"".gljson($M_pic)."\"},";
}

$fenlist= substr($fenlist,0,strlen($fenlist)-1);

}
echo "{\"comment_list\":[".$fenlist."]}";
break;

case "member_list";

if($page==""){
	$page="1";
}

$sql="select * from ".TABLE."member,".TABLE."lv where M_del=0 and M_lv=L_id and not M_login='未提供' and not M_login='admin' order by M_id desc limit ".($page*10-10).",10";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$M_from=$row["M_from"];
if(getrx("select * from ".TABLE."member where M_id=".$M_from,"M_id")==""){
	$M_fname="";
}else{
	$M_fname=getrx("select * from ".TABLE."member where M_id=".$M_from,"M_login");
}

if(substr($row["M_pic"],0,4)!="http"){
	$M_pic="../media/".$row["M_pic"];
}else{
	$M_pic=$row["M_pic"];
}

if(getrx("select * from ".TABLE."mtype where T_id=".$row["M_type"],"T_id")==""){
	$M_type="";
}else{
	$M_type=getrx("select * from ".TABLE."mtype where T_id=".$row["M_type"],"T_name");
}

$memberlist=$memberlist. "{\"M_login\":\"".gljson($row["M_login"])."\",\"M_id\":\"".$row["M_id"]."\",\"M_pic\":\"".gljson($M_pic)."\",\"M_email\":\"".gljson($row["M_email"])."\",\"M_mobile\":\"".gljson($row["M_mobile"])."\",\"M_name\":\"".gljson($row["M_name"])."\",\"M_money\":\"".$row["M_money"]."\",\"M_fen\":\"".$row["M_fen"]."\",\"M_lv\":\"".lang($row["L_title"])."\",\"M_type\":\"".$M_type."\",\"M_from\":\"".$M_from."\",\"M_need\":\"".gljson($row["M_need"])."\",\"M_fname\":\"".gljson($M_fname)."\"},";
}

$memberlist= substr($memberlist,0,strlen($memberlist)-1);

}

$sql="select count(M_id) as M_count from ".TABLE."member where M_del=0";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$M_count=$row["M_count"];

echo "{\"member_list\":[".$memberlist."],\"count\":\"".$M_count."\"}";

break;


case "member_search";

if($page==""){
	$page="1";
}

$S_type=$_GET["S_type"];
$S_key=$_GET["S_key"];

$sql="select * from ".TABLE."member,".TABLE."lv,".TABLE."mtype where M_del=0 and M_lv=L_id and M_type=T_id and ".$S_type." like '%".$S_key."%' and not M_login='未提供' and not M_login='admin' order by M_id desc";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$M_from=$row["M_from"];
$M_fname=getrx("select * from ".TABLE."member where M_id=".$M_from,"M_login");
if(substr($row["M_pic"],0,4)!="http"){
$M_pic="../media/".$row["M_pic"];
}else{
$M_pic=$row["M_pic"];
}

$memberlist=$memberlist. "{\"M_login\":\"".gljson($row["M_login"])."\",\"M_id\":\"".$row["M_id"]."\",\"M_pic\":\"".gljson($M_pic)."\",\"M_email\":\"".gljson($row["M_email"])."\",\"M_mobile\":\"".gljson($row["M_mobile"])."\",\"M_money\":\"".$row["M_money"]."\",\"M_fen\":\"".$row["M_fen"]."\",\"M_lv\":\"".lang($row["L_title"])."\",\"M_type\":\"".$row["T_name"]."\",\"M_from\":\"".$M_from."\",\"M_need\":\"".gljson($row["M_need"])."\",\"M_fname\":\"".gljson($M_fname)."\"},";
}

$memberlist= substr($memberlist,0,strlen($memberlist)-1);

}

$sql="select count(M_id) as M_count from ".TABLE."member,".TABLE."lv,".TABLE."mtype where M_del=0 and M_lv=L_id and M_type=T_id and ".$S_type." like '%".$S_key."%' and not M_login='未提供' and not M_login='admin'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$M_count=$row["M_count"];

echo "{\"member_list\":[".$memberlist."],\"count\":\"".$M_count."\"}";

break;


case "member_add";
if($id!="" && $id!="0"){
	$sql="Select * from ".TABLE."member where M_id=".$id;
	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_assoc($result);
	if(mysqli_num_rows($result) > 0) {
		$M_data=$row;
		$M_pic=$row["M_pic"];
		$M_lv=$row["M_lv"];
		$M_fen=$row["M_fen"];
		$M_money=$row["M_money"];
		$lv_info=lang(getrx("select * from ".TABLE."lv where L_id=".$M_lv,"L_title"));
	}
}else{
	$M_fen=0;
	$M_money=0;
	$M_pic="member.jpg";
	$lv_info="";
}

if(substr($M_pic,0,4)!="http"){
	$M_pic="../media/".$M_pic;
}

$sql="Select * from ".TABLE."mtype";
	$result = mysqli_query($conn, $sql);
	$arr = array();  
	while($row = mysqli_fetch_array($result)) {
	$count=count($row);//不能在循环语句中，由于每次删除 row数组长度都减小 
	  for($i=0;$i<$count;$i++){ 
	    unset($row[$i]);//删除冗余数据 
	  }   
    array_push($arr,$row);
} 

$M_data["M_fen"]=$M_fen;
$M_data["M_money"]=$M_money;
$M_data["M_pic"]=$M_pic;
$M_data["M_lv"]=$lv_info;
$M_data["mtype_list"]=$arr;

echo json_encode($M_data);
break;

case "mtype_list":
$sql="Select * from ".TABLE."mtype";
	$result = mysqli_query($conn, $sql);
	$arr = array();  
	while($row = mysqli_fetch_array($result)) {
	$count=count($row);//不能在循环语句中，由于每次删除 row数组长度都减小 
	  for($i=0;$i<$count;$i++){ 
	    unset($row[$i]);//删除冗余数据 
	  }   
    array_push($arr,$row);
} 

echo "{\"mtype_list\":".json_encode($arr)."}";
break;


case "brand_list":
$sql="select * from ".TABLE."brand";
	$result = mysqli_query($conn, $sql);
	$arr = array();  
	while($row = mysqli_fetch_array($result)) {
	$count=count($row);//不能在循环语句中，由于每次删除 row数组长度都减小 
	  for($i=0;$i<$count;$i++){ 
	    unset($row[$i]);//删除冗余数据 
	  }   
    array_push($arr,$row);
} 

echo "{\"brand_list\":".json_encode($arr)."}";
break;


case "safe";
$S_data[0]["S_email"]=$C_email;
echo json_encode($S_data[0]);
break;

case "log";
if($page==""){
	$page=1;
}
if($page==1){
	$sql="select * from ".TABLE."log order by L_id desc limit 10";
}else{
	$sql="select * from ".TABLE."log order by L_id desc limit ".($page*10-10).",10";
}

$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$L_user=$row["L_user"];
$L_action=$row["L_action"];
$L_ip=$row["L_ip"];
$L_location=$row["L_location"];
$L_time=$row["L_time"];

$loglist=$loglist."{\"L_id\":\"".$row["L_id"]."\",\"L_user\":\"".$row["L_user"]."\",\"L_action\":\"".$row["L_action"]."\",\"L_ip\":\"".$L_ip."\",\"L_location\":\"".$row["L_location"]."\",\"L_time\":\"".$row["L_time"]."\"},";
}

$loglist=substr($loglist,0,strlen($loglist)-1);

}

$sql="select count(L_id) as L_count from ".TABLE."log";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$L_count=$row["L_count"];

echo "{\"log_list\":[".$loglist."],\"count\":\"".$L_count."\"}";

break;

case "lv_add";
$sql="Select * from ".TABLE."lv Where L_id=".$id;
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$L_id=$row["L_id"];
$L_discount=$row["L_discount"];
$L_title=lang($row["L_title"]);
$L_fen=$row["L_fen"];
$L_order=$row["L_order"];
}
echo "{\"L_id\":\"".$id."\",\"L_title\":\"".$L_title."\",\"L_fen\":\"".$L_fen."\",\"L_discount\":\"".$L_discount."\",\"L_order\":\"".$L_order."\"}";
break;
case "fen_add":
echo "{\"C_1yuan\":\"".$C_1yuan."\",\"C_1yuan2\":\"".$C_1yuan2."\",\"C_sign\":\"".$C_sign."\",\"C_read\":\"".$C_read."\",\"C_invitation\":\"".$C_Invitation."\",\"C_data\":\"".$C_data."\"}";
break;
case "money_use":
echo "{\"C_tomoney\":\"".$C_tomoney."\",\"C_tofen\":\"".$C_tofen."\",\"C_tx\":\"".$C_tx."\",\"C_tomoney_rate\":\"".$C_tomoney_rate."\",\"C_tofen_rate\":\"".$C_tofen_rate."\",\"C_tx_rate\":\"".$C_tx_rate."\"}";
break;

case "fen_use";
$fen_use=$fen_use."<table class=\"table table-striped m-b-none\" id=\"tab_index\">";
if($C_gift!=""){
$gift=explode(",",$C_gift);
for($j = 0 ;$j<count($gift);$j++){
$fen_use=$fen_use. "<tr id=\"tab_index_".$j."\"><td>礼品".($j+1)."</td><td><div class=\"input-group m-b\"><span class=\"input-group-addon\">消耗</span><input type=\"text\" value=\"".splitx($gift[$j],"@",0)."\" name=\"xoindex_".$j."\" class=\"form-control\"> <span class=\"input-group-addon\">积分</span></div></td><td>兑换</td><td><div class=\"input-group m-b\"><span class=\"input-group-addon\">礼品</span><input type=\"text\" value=\"".splitx($gift[$j],"@",1)."\" name=\"xoindex_".$j."x\" class=\"form-control\"></div></td><td><input type=\"button\" value=\"- 删掉该行\" onclick=\"DelRow('tab_index','tab_index_".$j."')\" class=\"add\" style=\"margin:5px;\"/></td></tr>";
}
}else{
$fen_use=$fen_use."<tr id=\"tab_index_0\"><td>礼品1</td><td><div class=\"input-group m-b\"><span class=\"input-group-addon\">消耗</span><input type=\"text\" name=\"oindex_0\" class=\"form-control\"> <span class=\"input-group-addon\">积分</span></div></td><td>兑换</td><td><div class=\"input-group m-b\"><span class=\"input-group-addon\">礼品</span><input type=\"text\" name=\"oindex_0x\" class=\"form-control\"></div></td><td><input type=\"button\" value=\"- 删掉该行\" onclick=\"DelRow('tab_index','tab_index_0')\" class=\"add\" style=\"margin:5px;\"/></td></tr>";
}
$fen_use=$fen_use."</table>";
echo "{\"fen_use\":\"".gljson($fen_use)."\",\"C_gifton\":\"".$C_gifton."\"}";
break;

case "oss":
check("../");
break;

case "file";
$p=$_GET["p"];
$str="|20151019213836856.jpg|product.xls|member.jpg|".$C_logo."|".$C_ico."|".$C_wcode."|".$W_logo."|".$C_memberbg."|";
$sql="select * from ".TABLE."slide";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$str=$str.$row["S_pic"]."|";
}
}
$sql="select * from ".TABLE."wapslide";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$str=$str.$row["S_pic"]."|";
}
}
$sql="select * from ".TABLE."link";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$str=$str.$row["L_pic"]."|";
}
}
$sql="select * from ".TABLE."text";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$str=$str.$row["T_pic"]."|";
}
}
$sql="select * from ".TABLE."news";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$str=$str.$row["N_pic"]."|".$row["N_file"]."|";
}
}
$sql="select * from ".TABLE."product";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$str=$str.$row["P_path"]."|";
}
}
$sql="select * from ".TABLE."psort";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$str=$str.$row["S_pic"]."|";
}
}
$sql="select * from ".TABLE."nsort";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$str=$str.$row["S_pic"]."|";
}
}
$sql="select * from ".TABLE."form";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$str=$str.$row["F_pic"]."|";
}
}
$sql="select * from ".TABLE."response";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$str=$str.$row["R_content"]."|";
}
}
$id=0;

$handler = opendir('../media');
while( ($FileName = readdir($handler)) !== false ) {
 if(is_file("../media/".$FileName)){  

	switch(strtolower(substr($FileName,-3))){
	case "jpg":
	case "jpeg":
	case "png":
	case "gif":
	case "bmp":
	case "ico":
	$Filepic="../media/".$FileName;
	break;

	case "txt":
	$Filepic="img/txt.png";
	break;

	case "xls":
	case "xlsx":
	$Filepic="img/xls.png";
	break;

	case "doc":
	case "docx":
	$Filepic="img/doc.png";
	break;

	default:
	$Filepic="img/file.gif";
	}

	if(strpos($str,$FileName)>0){
		$disabled=1;
	}else{
		$disabled=0;
	}

$files=$files. "{\"filename\":\"".$FileName."\",\"filepic\":\"".$Filepic."\",\"id\":\"".$id."\",\"disabled\":".$disabled.",\"size\":\"".filesize("../media/".$FileName)."\",\"time\":\"".filemtime("../media/".$FileName)."\",\"type\":\"".getExt("../media/".$FileName)."\"},";
$id=$id+1;
}
}

$files = substr($files,0,strlen($files)-1);
$filex = json_decode("[".$files."]",true);

switch($p){
	case "":
	case "p1":
	$files=my_sort($filex,'filename',SORT_ASC,SORT_STRING);
	break;
	case "p2":
	$files=my_sort($filex,'filename',SORT_DESC,SORT_STRING);
	break;
	case "p3":
	$files=my_sort($filex,'size',SORT_ASC,SORT_NUMERIC);
	break;
	case "p4":
	$files=my_sort($filex,'size',SORT_DESC,SORT_NUMERIC);
	break;
	case "p5":
	$files=my_sort($filex,'type',SORT_ASC,SORT_STRING);
	break;
	case "p6":
	$files=my_sort($filex,'type',SORT_DESC,SORT_STRING);
	break;
	case "p7":
	$files=my_sort($filex,'time',SORT_ASC,SORT_NUMERIC);
	break;
	case "p8":
	$files=my_sort($filex,'time',SORT_DESC,SORT_NUMERIC);
	break;
	default:
	$files=my_sort($filex,'filename',SORT_ASC,SORT_STRING);
}

$files=json_encode($files);
echo "{\"file_list\":".$files.",\"count\":".$id."}";
break;

case "tohtml":
$sql="select * from ".TABLE."text where T_del=0";
$result = mysqli_query($conn,  $sql);
if (mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
		$list=$list."text_".$row["T_id"].",";
    }
} 

$sql="select * from ".TABLE."form where F_del=0";
$result = mysqli_query($conn,  $sql);
if (mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
		$list=$list."form_".$row["F_id"].",";
    }
} 


$sql="select * from ".TABLE."nsort where S_del=0";
$result = mysqli_query($conn,  $sql);
if (mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
		$list=$list."nsort_".$row["S_id"].",";
    }
} 

$sql="select * from ".TABLE."news where N_del=0";
$result = mysqli_query($conn,  $sql);
if (mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
		$list=$list."news_".$row["N_id"].",";
    }
} 

$sql="select * from ".TABLE."psort where S_del=0";
$result = mysqli_query($conn,  $sql);
if (mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
		$list=$list."psort_".$row["S_id"].",";
    }
} 

$sql="select * from ".TABLE."product where P_del=0";
$result = mysqli_query($conn,  $sql);
if (mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
		$list=$list."product_".$row["P_id"].",";
    }
} 

$list=$list."contact_1,guestbook_1,index_1";
$count=count(explode(",",$list));

echo "{\"list\":\"".$list."\",\"count\":\"".$count."\"}";
break;

case "creatall":
ready(plug("x1","1"));
break;
case "sql_list";

$handler = opendir('../backup');
while( ($FileName = readdir($handler)) !== false ) 
{
 if(is_file("../backup/".$FileName))
 {  

	$size=round((filesize("../backup/".$FileName)/1024),2);
	$datetime=date("Y-m-d H:i:s",filemtime("../backup/".$FileName));

	if(substr($FileName,-4)==".txt"){
		$sql_version=splitx(splitx(file_get_contents("../backup/".$FileName),"程序版本：",1)," 电脑端",0);
		$files=$files. "{\"filename\":\"".substr($FileName,0,strlen($FileName)-4)."\",\"datetime\":\"".$datetime."\",\"size\":\"".$size."\",\"version\":\"".$sql_version."\"},";
	}
}
}
$files=substr($files,0,strlen($files)-1);
echo "{\"sql_list\":[".$files."],\"table\":\"".$config->table."\"}";
break;

case "recycle":
//简介 ".TABLE."text
$sql="select count(*) as T_count from ".TABLE."text where T_del=1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
	$textcount=$row["T_count"];
}

$sql="select * from ".TABLE."text where T_del=1 order by T_id desc";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
	$textlist=$textlist."{\"title\":\"".gljson(lang($row["T_title"]))."\",\"id\":".$row["T_id"].",\"pic\":\"".$row["T_pic"]."\",\"content\":\"".gljson(mb_substr(strip_tags(lang($row["T_content"])),0,100,"utf-8"))."\"},";
	}
	$textlist= substr($textlist,0,strlen($textlist)-1);
}
//新闻分类 ".TABLE."nsort
$sql="select count(*) as S_count from ".TABLE."nsort where S_del=1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
	$nsortcount=$row["S_count"];
}

$sql="select * from ".TABLE."nsort where S_del=1 order by S_id desc";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
	$nsortlist=$nsortlist."{\"title\":\"".gljson(lang($row["S_title"]))."\",\"id\":".$row["S_id"].",\"pic\":\"".$row["S_pic"]."\",\"content\":\"".gljson(mb_substr(strip_tags(lang($row["S_description"])),0,100,"utf-8"))."\"},";
	}
	$nsortlist= substr($nsortlist,0,strlen($nsortlist)-1);
}
//新闻 ".TABLE."news
$sql="select count(*) as N_count from ".TABLE."news where N_del=1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
	$newscount=$row["N_count"];
}

$sql="select * from ".TABLE."news where N_del=1 order by N_id desc";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
	$newslist=$newslist."{\"title\":\"".gljson(lang($row["N_title"]))."\",\"id\":".$row["N_id"].",\"pic\":\"".$row["N_pic"]."\",\"content\":\"".gljson(mb_substr(strip_tags(lang($row["N_content"])),0,100,"utf-8"))."\"},";
	}
	$newslist= substr($newslist,0,strlen($newslist)-1);
}
//产品分类 ".TABLE."psort
$sql="select count(*) as S_count from ".TABLE."psort where S_del=1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
	$psortcount=$row["S_count"];
}

$sql="select * from ".TABLE."psort where S_del=1 order by S_id desc";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
	$psortlist=$psortlist."{\"title\":\"".gljson(lang($row["S_title"]))."\",\"id\":".$row["S_id"].",\"pic\":\"".$row["S_pic"]."\",\"content\":\"".gljson(mb_substr(strip_tags(lang($row["S_description"])),0,100,"utf-8"))."\"},";
	}
	$psortlist= substr($psortlist,0,strlen($psortlist)-1);
}

//产品 ".TABLE."product
$sql="select count(*) as P_count from ".TABLE."product where P_del=1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
	$productcount=$row["P_count"];
}

$sql="select * from ".TABLE."product where P_del=1 order by P_id desc";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
	$productlist=$productlist."{\"title\":\"".gljson(lang($row["P_title"]))."\",\"id\":".$row["P_id"].",\"pic\":\"".splitx(splitx($row["P_path"],"|",0),"__",0)."\",\"content\":\"".gljson(mb_substr(strip_tags(lang($row["P_content"])),0,100,"utf-8"))."\"},";
	}
	$productlist= substr($productlist,0,strlen($productlist)-1);
}

//表单 ".TABLE."form
$sql="select count(*) as F_count from ".TABLE."form where F_del=1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
	$formcount=$row["F_count"];
}

$sql="select * from ".TABLE."form where F_del=1 order by F_id desc";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
		$formlist=$formlist."{\"title\":\"".gljson(lang($row["F_title"]))."\",\"id\":".$row["F_id"].",\"pic\":\"".$row["F_pic"]."\",\"content\":\"".gljson(mb_substr(strip_tags(lang($row["F_description"])),0,100,"utf-8"))."\"},";
	}
	$formlist= substr($formlist,0,strlen($formlist)-1);
}

//表单条目 ".TABLE."content
$sql="select count(*) as C_count from ".TABLE."content where C_del=1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
	$contentcount=$row["C_count"];
}

$sql="select * from ".TABLE."content where C_del=1 order by C_id desc";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
		$contentlist=$contentlist."{\"title\":\"".gljson(lang($row["C_title"]))."\",\"id\":".$row["C_id"].",\"pic\":\"".$row["C_pic"]."\"},";
	}
	$contentlist= substr($contentlist,0,strlen($contentlist)-1);
}

//菜单 ".TABLE."menu
$sql="select count(*) as U_count from ".TABLE."menu where U_del=1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
	$menucount=$row["U_count"];
}

$sql="select * from ".TABLE."menu where U_del=1 order by U_id desc";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
		$menulist=$menulist."{\"title\":\"".gljson(lang($row["U_title"]))."\",\"id\":".$row["U_id"].",\"pic\":\"".$row["U_pic"]."\"},";
	}
	$menulist= substr($menulist,0,strlen($menulist)-1);
}

//焦点图 ".TABLE."slide
$sql="select count(*) as S_count from ".TABLE."slide where S_del=1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
	$slidecount=$row["S_count"];
}

$sql="select * from ".TABLE."slide where S_del=1 order by S_id desc";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
		$slidelist=$slidelist."{\"title\":\"".gljson(lang($row["S_title"]))."\",\"id\":".$row["S_id"].",\"pic\":\"".$row["S_pic"]."\",\"content\":\"".gljson(mb_substr(strip_tags(lang($row["S_content"])),0,100,"utf-8"))."\"},";
	}
	$slidelist= substr($slidelist,0,strlen($slidelist)-1);
}


//友链 ".TABLE."link
$sql="select count(*) as L_count from ".TABLE."link where L_del=1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
	$linkcount=$row["L_count"];
}

$sql="select * from ".TABLE."link where L_del=1 order by L_id desc";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
		$linklist=$linklist."{\"title\":\"".gljson(lang($row["L_title"]))."\",\"id\":".$row["L_id"].",\"pic\":\"".$row["L_pic"]."\"},";
	}
	$linklist= substr($linklist,0,strlen($linklist)-1);
}

//帖子 ".TABLE."bbs
$sql="select count(*) as B_count from ".TABLE."bbs where B_del=1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
	$bbscount=$row["B_count"];
}

$sql="select * from ".TABLE."bbs where B_del=1 order by B_id desc";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
		$bbslist=$bbslist."{\"title\":\"".gljson(lang($row["B_title"]))."\",\"id\":".$row["B_id"].",\"pic\":\"".$row["B_pic"]."\",\"content\":\"".gljson(mb_substr(strip_tags(lang($row["B_content"])),0,100,"utf-8"))."\"},";
	}
	$bbslist= substr($bbslist,0,strlen($bbslist)-1);
}

//板块 ".TABLE."bsort
$sql="select count(*) as S_count from ".TABLE."bsort where S_del=1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
	$bsortcount=$row["S_count"];
}

$sql="select * from ".TABLE."bsort where S_del=1 order by S_id desc";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
		$bsortlist=$bsortlist."{\"title\":\"".gljson(lang($row["S_title"]))."\",\"id\":".$row["S_id"].",\"pic\":\"".$row["S_pic"]."\",\"content\":\"".gljson(mb_substr(strip_tags(lang($row["S_description"])),0,100,"utf-8"))."\"},";
	}
	$bsortlist= substr($bsortlist,0,strlen($bsortlist)-1);
}

//会员 ".TABLE."member
$sql="select count(*) as M_count from ".TABLE."member where M_del=1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
	$membercount=$row["M_count"];
}

$sql="select * from ".TABLE."member where M_del=1 order by M_id desc";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
		if(substr($row["M_pic"],0,4)=="http"){
			$M_pic=$row["M_pic"];
		}else{
			$M_pic="../media/".$row["M_pic"];
		}
		$memberlist=$memberlist."{\"title\":\"".gljson(lang($row["M_login"]))."\",\"id\":".$row["M_id"].",\"pic\":\"".$M_pic."\"},";
	}
	$memberlist= substr($memberlist,0,strlen($memberlist)-1);
}

//管理员 ".TABLE."admin
$sql="select count(*) as A_count from ".TABLE."admin where A_del=1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
	$admincount=$row["A_count"];
}

$sql="select * from ".TABLE."admin where A_del=1 order by A_id desc";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
		$adminlist=$adminlist."{\"title\":\"".gljson(lang($row["A_login"]))."\",\"id\":".$row["A_id"].",\"pic\":\"".$row["A_pic"]."\"},";
	}
	$adminlist= substr($adminlist,0,strlen($adminlist)-1);
}

echo "{\"slide_count\":\"".$slidecount."\",\"link_count\":\"".$linkcount."\",\"text_count\":\"".$textcount."\",\"news_count\":\"".$newscount."\",\"product_count\":\"".$productcount."\",\"form_count\":\"".$formcount."\",\"menu_count\":\"".$menucount."\",\"member_count\":\"".$membercount."\",\"nsort_count\":\"".$nsortcount."\",\"psort_count\":\"".$psortcount."\",\"bbs_count\":\"".$bbscount."\",\"bsort_count\":\"".$bsortcount."\",\"admin_count\":\"".$admincount."\",\"content_count\":\"".$contentcount."\",\"slide_list\":[".$slidelist."],\"link_list\":[".$linklist."],\"text_list\":[".$textlist."],\"news_list\":[".$newslist."],\"product_list\":[".$productlist."],\"form_list\":[".$formlist."],\"menu_list\":[".$menulist."],\"member_list\":[".$memberlist."],\"admin_list\":[".$adminlist."],\"nsort_list\":[".$nsortlist."],\"psort_list\":[".$psortlist."],\"bsort_list\":[".$bsortlist."],\"bbs_list\":[".$bbslist."],\"content_list\":[".$contentlist."]}";

break;

case "search";
$keyword=$_REQUEST["key"];
$sql="select count(*) as S_count from ".TABLE."slide where S_del=0 and (S_title like '%".$keyword."%' or S_content like '%".$keyword."%' )";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$slidecount=$row["S_count"];
}
$sql="select * from ".TABLE."slide where S_del=0 and (S_title like '%".$keyword."%' or S_content like '%".$keyword."%' ) order by S_id desc";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$slidelist=$slidelist."{\"title\":\"".search(lang($row["S_title"]),$keyword)."\",\"content\":\"".search(gljson(mb_substr(strip_tags(lang($row["S_content"])),0,100,"utf-8")),$keyword)."\",\"id\":".$row["S_id"].",\"pic\":\"".$row["S_pic"]."\"},";
}

$slidelist= substr($slidelist,0,strlen($slidelist)-1);
}

$sql="select count(*) as L_count from ".TABLE."link where L_del=0 and (L_title like '%".$keyword."%' or L_url like '%".$keyword."%')";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$linkcount=$row["L_count"];
}
$sql="select * from ".TABLE."link where L_del=0 and (L_title like '%".$keyword."%' or L_url like '%".$keyword."%') order by L_id desc";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$linklist=$linklist."{\"title\":\"".search(lang($row["L_title"]),$keyword)."\",\"content\":\"".search($row["L_url"],$keyword)."\",\"id\":".$row["L_id"].",\"pic\":\"".$row["L_pic"]."\"},";
}
$linklist= substr($linklist,0,strlen($linklist)-1);
}

$sql="select count(*) as T_count from ".TABLE."text where T_del=0 and (T_title like '%".$keyword."%' or T_content like '%".$keyword."%')";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$textcount=$row["T_count"];
}
$sql="select * from ".TABLE."text where T_del=0 and (T_title like '%".$keyword."%' or T_content like '%".$keyword."%' ) order by T_id desc";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {

$textlist=$textlist."{\"title\":\"".search(lang($row["T_title"]),$keyword)."\",\"content\":\"".search(gljson(mb_substr(strip_tags(lang($row["T_content"])),0,100,"utf-8")),$keyword)."\",\"id\":".$row["T_id"].",\"pic\":\"".$row["T_pic"]."\"},";
}

$textlist= substr($textlist,0,strlen($textlist)-1);
}

$sql="select count(*) as N_count from ".TABLE."news where (N_title like '%".$keyword."%' or N_content like '%".$keyword."%') and N_del=0";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$newscount=$row["N_count"];
}
$sql="select * from ".TABLE."news where (N_title like '%".$keyword."%' or N_content like '%".$keyword."%' ) and N_del=0 order by N_id desc";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$newslist=$newslist."{\"title\":\"".search(lang($row["N_title"]),$keyword)."\",\"content\":\"".search(gljson(mb_substr(strip_tags(lang($row["N_content"])),0,100,"utf-8")),$keyword)."\",\"id\":".$row["N_id"].",\"pic\":\"".$row["N_pic"]."\"},";
}

$newslist= substr($newslist,0,strlen($newslist)-1);
}

$sql="select count(*) as P_count from ".TABLE."product where (P_title like '%".$keyword."%' or P_content like '%".$keyword."%') and P_del=0";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$productcount=$row["P_count"];
}
$sql="select * from ".TABLE."product where (P_title like '%".$keyword."%' or P_content like '%".$keyword."%' ) and P_del=0 order by P_id desc";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$productlist=$productlist."{\"title\":\"".search(lang($row["P_title"]),$keyword)."\",\"content\":\"".search(gljson(mb_substr(strip_tags(lang($row["P_content"])),0,100,"utf-8")),$keyword)."\",\"id\":".$row["P_id"].",\"pic\":\"".splitx(splitx($row["P_path"],"|",0),"__",0)."\"},";
}

$productlist= substr($productlist,0,strlen($productlist)-1);
}

$sql="select count(*) as F_count from ".TABLE."form where (F_title like '%".$keyword."%' or F_bz like '%".$keyword."%') and F_del=0";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$formcount=$row["F_count"];
}
$sql="select * from ".TABLE."form where (F_title like '%".$keyword."%' or F_bz like '%".$keyword."%' ) and F_del=0 order by F_id desc";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$formlist=$formlist."{\"title\":\"".search(lang($row["F_title"]),$keyword)."\",\"content\":\"".search(gljson(mb_substr(strip_tags(lang($row["F_bz"])),0,100,"utf-8")),$keyword)."\",\"id\":".$row["F_id"].",\"pic\":\"".$row["F_pic"]."\"},";
}
$formlist= substr($formlist,0,strlen($formlist)-1);
}

$sql="select count(*) as M_count from ".TABLE."member where M_del=0 and (M_login like '%".$keyword."%' or M_email like '%".$keyword."%' or M_mobile like '%".$keyword."%')";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$membercount=$row["M_count"];
}
$sql="select * from ".TABLE."member where M_del=0 and (M_login like '%".$keyword."%' or M_email like '%".$keyword."%' or M_mobile like '%".$keyword."%') order by M_id desc";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$memberlist=$memberlist."{\"title\":\"".search($row["M_login"],$keyword)."\",\"content\":\"邮箱：".search($row["M_email"],$keyword)." 手机：".search($row["M_mobile"],$keyword)."\",\"id\":".$row["M_id"].",\"pic\":\"media/".$row["M_pic"]."\"},";
}

$memberlist= substr($memberlist,0,strlen($memberlist)-1);

}

$sql="select count(*) as U_count from ".TABLE."menu where U_del=0 and (U_title like '%".$keyword."%')";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$menucount=$row["U_count"];
}
$sql="select * from ".TABLE."menu where U_del=0 and (U_title like '%".$keyword."%' or U_url like '%".$keyword."%') order by U_id desc";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
switch($row["U_type"]){

case "text":
$U_pic=getrx("select * from ".TABLE."text where T_id=".$row["U_typeid"],"T_pic");
break;
case "form":
$U_pic=getrx("select * from ".TABLE."form where F_id=".$row["U_typeid"],"F_pic");
break;
case "product":
$U_pic=getrx("select * from ".TABLE."psort where S_id=".$row["U_typeid"],"S_pic");
default:
$U_pic=$C_logo;
}
$menulist=$menulist."{\"title\":\"".search(lang($row["U_title"]),$keyword)."\",\"content\":\"".search($row["U_url"],$keyword)."\",\"id\":".$row["U_id"].",\"pic\":\"".$U_pic."\"},";
}
$menulist= substr($menulist,0,strlen($menulist)-1);
}
echo "{\"slide_count\":\"".$slidecount."\",\"link_count\":\"".$linkcount."\",\"text_count\":\"".$textcount."\",\"news_count\":\"".$newscount."\",\"product_count\":\"".$productcount."\",\"form_count\":\"".$formcount."\",\"menu_count\":\"".$menucount."\",\"member_count\":\"".$membercount."\",\"slide_list\":[".$slidelist."],\"link_list\":[".$linklist."],\"text_list\":[".$textlist."],\"news_list\":[".$newslist."],\"product_list\":[".$productlist."],\"form_list\":[".$formlist."],\"menu_list\":[".$menulist."],\"member_list\":[".$memberlist."]}";

break;

case "template_html";

$handler = opendir('../pc/'.$_GET["id"]);

while(($FileName = readdir($handler)) !== false ) 
{
 if(is_file("../pc/".$_GET["id"]."/".$FileName))
 {  

switch(splitx($FileName,".",0)){
case "index":
$type_info="网站首页";
break;
case "index_e":
$type_info="网站首页（英文）";
break;
case "text":
$type_info="简介页面";
break;
case "text_e":
$type_info="简介页面（英文）";
break;
case "bbs":
$type_info="论坛页面";
break;
case "bbs_e":
$type_info="论坛页面（英文）";
break;
case "search":
$type_info="搜索页面";
break;
case "search_e":
$type_info="搜索页面（英文）";
break;
case "news":
$type_info="新闻列表页";
break;
case "news_e":
$type_info="新闻列表页（英文）";
break;
case "newsinfo":
$type_info="新闻内容页";
break;
case "newsinfo_e":
$type_info="新闻内容页（英文）";
break;
case "product":
$type_info="产品列表页";
break;
case "product_e":
$type_info="产品列表页（英文）";
break;
case "productinfo":
$type_info="产品内容页";
break;
case "productinfo_e":
$type_info="产品内容页（英文）";
break;
case "form":
$type_info="万能表单页";
break;
case "form_e":
$type_info="万能表单页（英文）";
break;
case "contact":
$type_info="联系方式页";
break;
case "contact_e":
$type_info="联系方式页（英文）";
break;
case "guestbook":
$type_info="留言页面";
break;
case "guestbook_e":
$type_info="留言页面（英文）";
break;
case "top":
$type_info="网页顶部";
break;
case "top_e":
$type_info="网页顶部（英文）";
break;
case "foot":
$type_info="网页底部";
break;
case "foot_e":
$type_info="网页底部（英文）";
break;
default:
$type_info="未知页面";
break;
}

if(splitx($FileName,".",1)=="tpl"){
	$list=$list."{\"filename\":\"".splitx($FileName,".",0)."\",\"typeinfo\":\"".$type_info."\"},";
}
}
}
$list=substr($list,0,strlen($list)-1);
echo "{\"no\":\"".$_GET["id"]."\",\"html_list\":[".$list."]}";

break;

case "template_edit";
echo "{\"no\":\"".$_REQUEST["T_id"]."\",\"name\":\"".$_REQUEST["name"]."\",\"T_html\":\"".gljson(file_get_contents("../pc/".$_REQUEST["T_id"]."/".$_REQUEST["name"].".tpl"))."\"}";
break;
case "w_config";

$C_url=gethttp().splitx($_SERVER["HTTP_HOST"].strtolower($_SERVER["PHP_SELF"]),"/".strtolower($C_admin),0)."/function/weixin.php";
echo "{\"C_wtitle\":\"".$C_wtitle."\",\"C_wtoken\":\"".$C_wtoken."\",\"C_wcode\":\"".$C_wcode."\",\"C_wx_appid\":\"".$C_wx_appid."\",\"C_wx_mchid\":\"".$C_wx_mchid."\",\"C_wx_key\":\"".$C_wx_key."\",\"C_wx_appsecret\":\"".$C_wx_appsecret."\",\"C_url\":\"".$C_url."\"}";
break;

case "w_menu";
$sql="select * from ".TABLE."wmenu where W_sub=0 order by W_order";

$result = mysqli_query($conn,  $sql);
if (mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {

if($row["W_type"]=="click"){
	$W_type="事件";
	$E_type=getrx("select * from ".TABLE."event where E_id=".intval($row["W_content"]),"E_type");
	if($E_type=="text"){
		$W_content="纯文本：".getrx("select * from ".TABLE."event where E_id=".intval($row["W_content"]),"E_content");
	}
	if($E_type=="article"){
		$W_content="单图文：".getrx("select * from ".TABLE."event where E_id=".intval($row["W_content"]),"E_content");
	}
	if($E_type=="articles"){
		$W_content="多图文：".getrx("select * from ".TABLE."event where E_id=".intval($row["W_content"]),"E_content");
	}
}
if($row["W_type"]=="view"){
	$W_type="链接";
	$W_content=$row["W_content"];
}
if($row["W_type"]=="miniprogram"){
	$W_type="小程序";
	$W_content="APPID：".$row["W_content"];
}
$menu_sub="<select name='sub_".$row["W_id"]."'><option  value='0' selected='selected'>主菜单</option>";
if($row["W_type"]!=="sub"){
$sql3="Select * from ".TABLE."wmenu where W_sub=0 and not W_id=".$row["W_id"]." order by W_order";
$result3 = mysqli_query($conn,  $sql3);
if (mysqli_num_rows($result3) > 0) {
while($row3 = mysqli_fetch_assoc($result3)) {
$menu_sub=$menu_sub."<option value='".$row3["W_id"]."'>".$row3["W_title"]."</option>";

}
}

}else{
$menu_sub=$menu_sub."</select>";
}
$sql1="select * from ".TABLE."wmenu where W_sub=".$row["W_id"]." order by W_order";

$result1 = mysqli_query($conn,  $sql1);
if (mysqli_num_rows($result1) > 0) {
while($row1 = mysqli_fetch_assoc($result1)) {

if($row1["W_type"]=="click"){
$W_type1="事件";
$E_type1=getrx("select * from ".TABLE."event where E_id=".intval($row1["W_content"]),"E_type");
if($E_type1=="text"){
$W_content1="纯文本：".getrx("select * from ".TABLE."event where E_id=".intval($row1["W_content"]),"E_content");
}
if($E_type1=="article"){
$W_content1="单图文：".getrx("select * from ".TABLE."event where E_id=".intval($row1["W_content"]),"E_content");
}
if($E_type1=="articles"){
$W_content1="多图文：".getrx("select * from ".TABLE."event where E_id=".intval($row1["W_content"]),"E_content");
}
}
if($row1["W_type"]=="view"){
$W_type1="链接";
$W_content1=$row1["W_content"];
}

if($row1["W_type"]=="miniprogram"){
	$W_type1="小程序";
	$W_content1="APPID：".$row1["W_content"];
}

$menu_sub1="<select name='sub_".$row1["W_id"]."'><option value='0'>主菜单</option>";
$sql3="Select * from ".TABLE."wmenu where W_sub=0 order by W_order";

$result3 = mysqli_query($conn,  $sql3);
if (mysqli_num_rows($result3) > 0) {
while($row3 = mysqli_fetch_assoc($result3)) {
if($row1["W_sub"]==$row3["W_id"]){
	$sub_select1="selected='selected'";
}else{
	$sub_select1="";
}

$menu_sub1=$menu_sub1."<option value='".$row3["W_id"]."' ".$sub_select1.">".$row3["W_title"]."</option>";
}
}

$menu_sub1=$menu_sub1."</select>";
$sublist=$sublist."{\"W_id\":\"".$row1["W_id"]."\",\"W_order\":\"".$row1["W_order"]."\",\"W_title\":\"".$row1["W_title"]."\",\"W_type\":\"".$W_type1."\",\"W_content\":\"".gljson($W_content1)."\",\"W_sub\":\"".$menu_sub1."\"},";

}
$sublist=substr($sublist,0,strlen($sublist)-1);
}

$mainlist=$mainlist."{\"W_id\":\"".$row["W_id"]."\",\"W_order\":\"".$row["W_order"]."\",\"W_title\":\"".$row["W_title"]."\",\"W_type\":\"".$W_type."\",\"W_content\":\"".gljson($W_content)."\",\"W_sub\":\"".$menu_sub."\",\"sub_list\":[".$sublist."]},";
$sublist="";
}
$mainlist=substr($mainlist,0,strlen($mainlist)-1);
}
echo "{\"menu_list\":[".$mainlist."]}";
break;


case "w_menu_add";
$sql="Select * from ".TABLE."wmenu Where W_id=".$id;
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$W_title=$row["W_title"];
$W_content=$row["W_content"];
$W_type=$row["W_type"];
$W_sub=$row["W_sub"];
}
echo "{\"W_title\":\"".$W_title."\",\"W_content\":\"".$W_content."\",\"W_type\":\"".$W_type."\",\"W_sub\":\"".$W_sub."\"}";
break;



case "w_msg";
$sql1="Select * from ".TABLE."member where M_del=0 and M_subscribe=1 order by M_id desc";
$result1 = mysqli_query($conn,  $sql1);
if (mysqli_num_rows($result1) > 0) {
while($row1 = mysqli_fetch_assoc($result1)) {

$memberlist=$memberlist."{\"M_id\":\"".$row1["M_qqid"]."\",\"M_login\":\"".gljson($row1["M_login"])."\",\"M_pic\":\"".$row1["M_pic"]."\",\"M_qqid\":\"".$row1["M_qqid"]."\"},";

}
$memberlist=substr($memberlist,0,strlen($memberlist)-1);
}

$sql1="Select * from ".TABLE."event  order by E_id";

$result1 = mysqli_query($conn,  $sql1);
if (mysqli_num_rows($result1) > 0) {
while($row1 = mysqli_fetch_assoc($result1)) {

if($row1["E_type"]=="text"){
$E_type="纯文本 - ".$row1["E_content"];
}
if($row1["E_type"]=="article"){
$E_type="单图文 - ".$row1["E_content"];
}
if($row1["E_type"]=="articles"){
$E_type="多图文 - ".$row1["E_content"];
}
$eventlist=$eventlist."{\"E_id\":\"".$row1["E_id"]."\",\"E_type\":\"".gljson($E_type)."\"},";

}
$eventlist=substr($eventlist,0,strlen($eventlist)-1);
}

$sql1="Select count(*) as M_count from ".TABLE."member where M_del=0 and M_subscribe=1";
$result1 = mysqli_query($conn, $sql1);
$row1 = mysqli_fetch_assoc($result1);
if(mysqli_num_rows($result1) > 0) {
$M_count=$row1["M_count"];
}

echo "{\"member\":[".$memberlist."],\"event\":[".$eventlist."],\"M_count\":\"".$M_count."\"}";
break;


case "w_event";
$sql="select * from ".TABLE."event where not E_title='未匹配到关键词' and not E_title='推送网站目录'  order by E_id desc";

$result = mysqli_query($conn,  $sql);
if (mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {

if($row["E_type"]=="text"){
$E_type="纯文本";
$E_contentx=substr($row["E_content"],0,50);
}
if($row["E_type"]=="article"){
$E_type="单图文";
$E_contentx="单图文：".$row["E_content"];
}
if($row["E_type"]=="articles"){
$E_type="多图文";
$E_contentx="多图文：".$row["E_content"];
}
$eventlist=$eventlist."{\"E_id\":\"".$row["E_id"]."\",\"E_title\":\"".$row["E_title"]."\",\"E_type\":\"".gljson($E_type)."\",\"E_content\":\"".gljson($E_contentx)."\"},";
}
$eventlist=substr($eventlist,0,strlen($eventlist)-1);
}
echo "{\"event\":[".$eventlist."]}";

break;


case "w_event_add";
$sql="Select * from ".TABLE."event Where E_id=".$id;

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$E_title=$row["E_title"];
$E_content=$row["E_content"];
$E_type=$row["E_type"];
}
$sql1="Select * from ".TABLE."text where T_del=0 order by T_id desc";

$result1 = mysqli_query($conn,  $sql1);
if (mysqli_num_rows($result1) > 0) {
while($row1 = mysqli_fetch_assoc($result1)) {
$E_all=$E_all."{\"id\":\"T".$row1["T_id"]."\",\"pic\":\"".$row1["T_pic"]."\",\"title\":\"简介-".mb_substr(lang($row1["T_title"]),0,10,"utf-8")."\"},";

}
}

$sql1="Select * from ".TABLE."news where N_del=0 order by N_id desc";

$result1 = mysqli_query($conn,  $sql1);
if (mysqli_num_rows($result1) > 0) {
while($row1 = mysqli_fetch_assoc($result1)) {
$E_all=$E_all."{\"id\":\"N".$row1["N_id"]."\",\"pic\":\"".$row1["N_pic"]."\",\"title\":\"新闻-".mb_substr(lang($row1["N_title"]),0,10,"utf-8")."\"},";

}
}

$sql1="Select * from ".TABLE."product where P_del=0 order by P_id desc";

$result1 = mysqli_query($conn,  $sql1);
if (mysqli_num_rows($result1) > 0) {
while($row1 = mysqli_fetch_assoc($result1)) {
$E_all=$E_all."{\"id\":\"P".$row1["P_id"]."\",\"pic\":\"".splitx(splitx($row1["P_path"],"|",0),"__",0)."\",\"title\":\"产品-".mb_substr(lang($row1["P_title"]),0,10,"utf-8")."\"},";

}
}

$sql1="Select * from ".TABLE."form where F_del=0 order by F_id desc";

$result1 = mysqli_query($conn,  $sql1);
if (mysqli_num_rows($result1) > 0) {
while($row1 = mysqli_fetch_assoc($result1)) {
$E_all=$E_all."{\"id\":\"F".$row1["F_id"]."\",\"pic\":\"".$row1["F_pic"]."\",\"title\":\"表单-".mb_substr(lang($row1["F_title"]),0,20,"utf-8")."\"},";

}
}

$E_all=$E_all."{\"id\":\"C1\",\"pic\":\"".$C_ico."\",\"title\":\"联系方式\"},";
$E_all=$E_all."{\"id\":\"G1\",\"pic\":\"".$C_ico."\",\"title\":\"在线留言\"},";
$E_all=substr($E_all,0,strlen($E_all)-1);
echo "{\"E_title\":\"".$E_title."\",\"E_content\":\"".gljson($E_content)."\",\"E_type\":\"".$E_type."\",\"E_all\":[".$E_all."]}";
break;


case "w_reply";
$sql="select * from ".TABLE."reply,".TABLE."event where R_reply=E_id and not R_key='新用户关注' order by R_id desc";
$result = mysqli_query($conn,  $sql);
if (mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
if($row["E_type"]=="text"){
$E_type="纯文本";
$E_contentx=mb_substr($row["E_content"],0,50,"utf-8");
}
if($row["E_type"]=="article"){
$E_type="单图文";
$E_contentx="单图文：".$row["E_content"];
}
if($row["E_type"]=="articles"){
$E_type="多图文";
$E_contentx="多图文：".$row["E_content"];
}
$replylist=$replylist."{\"R_id\":\"".$row["R_id"]."\",\"R_key\":\"".$row["R_key"]."\",\"E_type\":\"".$E_type."\",\"E_content\":\"".gljson($E_contentx)."\"},";
}
$replylist=substr($replylist,0,strlen($replylist)-1);
}


$sql="select * from ".TABLE."event order by E_id desc";
$result = mysqli_query($conn,  $sql);
if (mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
if($row["E_type"]=="text"){
$E_type="纯文本";
$E_contentx=mb_substr($row["E_content"],0,50,"utf-8");
}
if($row["E_type"]=="article"){
$E_type="单图文";
$E_contentx="单图文：".$row["E_content"];
}
if($row["E_type"]=="articles"){
$E_type="多图文";
$E_contentx="多图文：".$row["E_content"];
}
$eventlist=$eventlist."{\"E_id\":\"".$row["E_id"]."\",\"E_title\":\"".$row["E_title"]."\",\"E_type\":\"".$E_type."\",\"E_content\":\"".gljson($E_contentx)."\"},";
}
$eventlist=substr($eventlist,0,strlen($eventlist)-1);
}
$sub_id=getrx("select * from ".TABLE."reply where R_key='新用户关注'","R_reply");
echo "{\"reply\":[".$replylist."],\"event\":[".$eventlist."],\"sub_id\":\"".$sub_id."\"}";
break;
case "w_reply_add":
$sql="Select * from ".TABLE."reply Where R_id=".$id;

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$R_key=$row["R_key"];
$R_reply=$row["R_reply"];
}
echo "{\"R_id\":\"".$id."\",\"R_key\":\"".$R_key."\",\"R_reply\":\"".$R_reply."\"}";

break;


case "wxapp_json";
$info=getbody("http://scms5.oss-cn-shenzhen.aliyuncs.com/json/wxapp.json","","GET");
echo $info;
break;

case "tsort_json";
$info=getbody("http://scms5.oss-cn-shenzhen.aliyuncs.com/json/tsort.json","","GET");
echo $info;
break;

case "plug_json";
$info=getbody("http://scms5.oss-cn-shenzhen.aliyuncs.com/json/plug.json","","GET");
foreach(json_decode($info)->list as $v) {
	if(check_auth2($v->P_no)){
		$P_state="1";
	}else{
		$P_state="0";
	}
	$plug=$plug."{\"P_title\":\"".$v->P_title."\",\"P_no\":\"".$v->P_no."\",\"P_content\":\"".$v->P_content."\",\"P_price\":\"".$v->P_price."\",\"P_state\":\"".$P_state."\"},";
}

$plug= substr($plug,0,strlen($plug)-1);
$plug= "{\"list\":[".$plug."]}";
echo $plug;
break;


case "pc_json";
$info=getbody("http://scms5.oss-cn-shenzhen.aliyuncs.com/json/pc.json","","GET");
foreach(json_decode($info)->list as $v) {

if(strpos("|".getfolder("../pc")."|","|".$v->T_name."|")!==false){
	$state=1;
}else{
	if(check_auth2($v->T_name)){
		$state=2;
	}else{
		$state=3;
	}
}
	$pc=$pc."{\"T_name\":\"".$v->T_name."\",\"T_price\":\"".$v->T_price."\",\"state\":\"".$state."\",\"T_tag\":\"".$v->T_tag."\"},";
}

$pc= substr($pc,0,strlen($pc)-1);
$pc= "{\"list\":[".$pc."]}";
echo $pc;
break;

case "wap_json";
$info=getbody("http://scms5.oss-cn-shenzhen.aliyuncs.com/json/wap.json","","GET");
foreach(json_decode($info)->list as $v) {

if(strpos("|".getfolder("../wap")."|","|".$v->T_name."|")!==false){
	$state=1;
}else{
	if(check_auth2($v->T_name)){
		$state=2;
	}else{
		$state=3;
	}
}
	$wap=$wap."{\"T_name\":\"".$v->T_name."\",\"T_price\":\"".$v->T_price."\",\"state\":\"".$state."\"},";
}

$wap= substr($wap,0,strlen($wap)-1);
$wap= "{\"list\":[".$wap."]}";
echo $wap;
break;

case "space";
if($_REQUEST["folder"]==""){
$folder="../";
}else{
$folder="../".str_Replace(".","/",$_REQUEST["folder"]);
}

$handler = opendir($folder);

while( ($filename = readdir($handler)) !== false ) {
 if(is_dir($folder.$filename) && $filename != "." && $filename != ".."){  
if($_REQUEST["folder"]==""){
switch($filename){
case $C_admin:
$folderinfo="后台目录";
break;
case "bbs":
$folderinfo="论坛";
break;
case "css":
$folderinfo="层叠样式表";
break;
case "data":
$folderinfo="数据库及核心文件";
break;
case "html":
$folderinfo="静态html页面存放（中文）";
break;
case "ehtml":
$folderinfo="静态html页面存放（英文）";
break;
case "fhtml":
$folderinfo="静态html页面存放（繁体）";
break;
case "function":
$folderinfo="常用函数";
break;
case "images":
$folderinfo="系统图片";
break;
case "install":
$folderinfo="安装文件";
break;
case "js":
$folderinfo="javascript脚本文件";
break;
case "ueditor":
$folderinfo="富文本编辑器";
break;
case "media":
$folderinfo="用户上传图片";
break;
case "member":
$folderinfo="会员系统";
break;
case "pay":
$folderinfo="支付接口";
break;
case "pc":
$folderinfo="模板文件夹";
break;
case "backup":
$folderinfo="数据库备份";
break;
case "wap":
$folderinfo="手机网站";
break;
default:
$folderinfo="未知文件夹";
}
if($folderinfo!="未知文件夹"){
$folderinfo=$folderinfo."（<font color='#ff0000'>不可删除</font>）";
}else{
$folderinfo=$folderinfo."（<font color='#ff9900'>谨慎操作</font>）";
}
}else{
$folderinfo="文件夹";
}
$file=$file. "{\"name\":\"".$filename."\",\"width\":\"".Drawbar($folder.$filename)."\",\"space\":\"".ShowSpaceInfo(getDirSize($folder.$filename))."\",\"info\":\"".$folderinfo."\",\"pic\":\"images/folder.png\",\"type\":\"folder\"},";
}
}


$handler = opendir($folder);

while( ($filename = readdir($handler)) !== false ) {
if(is_file($folder.$filename)){  

switch(substr(strrchr($filename, '.'), 1)){
case "jpg":
$f_type="img";
break;
case "png":
$f_type="img";
break;
case "gif":
$f_type="img";
break;
case "bmp":
$f_type="img";
break;
case "ico":
$f_type="img";
break;
case "txt":
$f_type="txt";
break;
case "xml":
case "tpl":
case "html":
case "php":
case "js":
case "css":
case "bas":
$f_type="txt";
break;

default:
$f_type="file";
}

$file=$file. "{\"name\":\"".gljson(get_gb_to_utf8($filename))."\",\"width\":\"\",\"space\":\"".getsize($folder.$filename)."\",\"info\":\"".gljson(getFileExt($filename))."文件\",\"pic\":\"img/file.gif\",\"type\":\"".$f_type."\"},";
}
}

if($folder!="../"){
$file="{\"name\":\"上一层..\",\"width\":\"\",\"space\":\"0\",\"info\":\"返回上一层目录\",\"pic\":\"images/folder.png\",\"type\":\"back\"},".$file;

}
$file=substr($file,0,strlen($file)-1);
echo "{\"size_all\":\"".Showspecialspaceinfo("All")."\",\"file_list\":[".$file."]}";
}//switch结尾
}//if 结尾


function getsize($str){
if(filesize($str)<1024){
return filesize($str)." Byte";
}else{
return round((filesize($str)/1024),0)." K";
}
}

function auth2($str){
	if (check_auth2($str)){
		return "1";
	}else{
		return "0";
	}
}

function search($str,$key){
	return str_replace($key,"<span style='color:#ff0000;'>".$key."</span>",$str);
}

function getfolder($filepath){

$handler = opendir($filepath);
$files="|";
while( ($filename = readdir($handler)) !== false ) 
{
 if(is_dir($filepath."/".$filename) && $filename != "." && $filename != "..")
 {  
   $files=$files.$filename."|";
  }
}

return $files;

}


function Drawbar($dir){

$handle = opendir($dir);
  while (false!==($FolderOrFile = readdir($handle)))
  { 
   if($FolderOrFile != "." && $FolderOrFile != "..") 
   { 
    if(is_dir("$dir/$FolderOrFile"))
    { 
     $sizeResult += getDirSize("$dir/$FolderOrFile"); 
    }
    else
    { 
     $sizeResult += filesize("$dir/$FolderOrFile"); 
    }
   } 
  }
  closedir($handle);
return $sizeResult/300000;
}

function ShowSpaceInfo($filesize){
$size =$filesize;
$showsize = $size . " Byte";
if($size > 1024){
$size = ($size / 1024);
$showsize = round($size,2) . " KB";
}
if($size > 1024){
$size = ($size / 1024);
$showsize = round($size,2) . " MB";
}
if($size > 1024){
$size = ($size / 1024);
$showsize = round($size,2) . " GB";
}
$ShowSpaceInfo=$showsize;
return $ShowSpaceInfo;
}

function getFileExt($a){
	return substr(strrchr($a, '.'), 1);;
}

function Showspecialspaceinfo($a){
	return 1;
}

function check($dir){
	global $C_dirx;
    if(!is_dir($dir)) return false;
    $handle = opendir($dir);
    if($handle){
        while(($fl = readdir($handle)) !== false){
            $temp = $dir.DIRECTORY_SEPARATOR.$fl;
            if(is_dir($temp) && $fl!='.' && $fl != '..'){
                check($temp);
            }else{
                if(preg_match('/\.jpg|\.jpeg|\.png|\.gif|\.bmp|\.css|\.js/i', glpath($temp)) &&  preg_match('/\.\.\/media\/|\.\.\/ueditor\/php\/upload\/|\.\.\/kindeditor\/attached\/|\.\.\/pc\/|\.\.\/wap\/|\.\.\/css|\.\.\/js|\.\.\/images/i', glpath($temp)) && strpos($temp,"#")===false){
                	$O_md5=getrx("select * from ".TABLE."oss where O_name='".glpath2($temp)."'","O_md5");
                	if($O_md5!=md5(file_get_contents($C_dirx.glpath2($temp)))){
                		echo glpath2($temp)."|";
                	}
                }
            }
        }
    }
}

function glpath($name){
	$name=str_replace("\\","/",$name);
	return str_replace("..//","../",$name);
}

function glpath2($name){
	$name=str_replace("\\","/",$name);
	return str_replace("..//","",$name);
}

function my_sort($arrays,$sort_key,$sort_order=SORT_ASC,$sort_type=SORT_NUMERIC ){
if(is_array($arrays)){
foreach ($arrays as $array){
if(is_array($array)){
$key_arrays[] = $array[$sort_key];
}else{
return false;
}
}
}else{
return false;
}
array_multisort($key_arrays,$sort_order,$sort_type,$arrays);
return $arrays;
}
?>