<?php 
require '../function/conn.php';
require '../function/function.php';

$qqid = $C_qqid;
$qqkey = $C_qqkey;
?>
<!DOCTYPE HTML>
<html>
<head>
<title>会员登录</title>
<meta charset="utf-8" />
<meta content="width=device-width, initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" name="viewport" />
<meta content="yes" name="apple-mobile-web-app-capable" />
<meta content="black" name="apple-mobile-web-app-status-bar-style" />
<meta content="telephone=yes" name="format-detection" />
<meta content="email=no" name="format-detection" />
<meta name="keywords" content=""  />
<meta name="description" content="" />

</head>
<body >
<?php 

if ($_SESSION["from"]==""){
$from="../member/index.php";
}else{
$from=$_SESSION["from"];
}

$D_domain=splitx($_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"],"/function",0);

$accdata=getbody("https://graph.qq.com/oauth2.0/token","grant_type=authorization_code&client_id=".$qqid."&client_secret=".$qqkey."&code=".$_GET["code"]."&state=200&redirect_uri=".gethttp().$D_domain."/function/reg.php");

$access_token=substr($accdata,13,32);

$openid=getbody("https://graph.qq.com/oauth2.0/me",$accdata);


$openid=str_Replace("callback(","",$openid);
$openid=str_Replace(");","",$openid);

$openid=json_decode($openid)->openid;

$info2=GetBody("https://graph.qq.com/user/get_user_info","access_token=".$access_token."&openid=".$openid."&oauth_consumer_key=".$qqid);

$info2=json_decode($info2);
$nickname=$info2->nickname;
$figureurl_qq_2=$info2->figureurl_qq_2;

if($nickname==""){
box("获取帐号信息失败，请重试！","../member/member_login.php","error");
}else{

	$sql="select * from ".TABLE."member where M_qqid='".$openid."'";
	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_assoc($result);
	if (mysqli_num_rows($result) > 0) {
	$M_login=$row["M_login"];
	$M_id=$row["M_id"];
$_SESSION["M_login"]=$M_login;
$_SESSION["M_id"]=$M_id;
$_SESSION["M_pwd"]=$openid;
box("欢迎回来！".$M_login,URLdecode($from),"success");
}else{


$sql="insert into ".TABLE."member(M_login,M_pwd,M_email,M_fen,M_pic,M_regtime,M_qqid,M_type) values('Q_".$nickname."','".$openid."','@qq.com',0,'".$figureurl_qq_2."','".date('Y-m-d H:i:s')."','".$openid."',1)";

mysqli_query($conn,$sql);
$_SESSION["M_login"]="Q_".$nickname;
$_SESSION["M_pwd"]=$openid;

	$sql="select * from ".TABLE."member order by M_id desc limit 1";
	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_assoc($result);
	if (mysqli_num_rows($result) > 0) {
	$_SESSION["M_id"]=$row["M_id"];
	uplevel($row["M_id"]);
	}
	if($_COOKIE["uid"]!=""){
mysqli_query($conn,"update ".TABLE."member set M_fen=M_fen+".$C_Invitation." where M_id=".$_COOKIE["uid"]);
mysqli_query($conn,"insert into ".TABLE."list(L_title,L_mid,L_change,L_time,L_type) values('邀请好友',".$_COOKIE["uid"].",".$C_Invitation.",'".date('Y-m-d H:i:s')."',1)");
}
box("登录成功！".$nickname,URLdecode($from),"success");
}
	}
?>
</body>
</html>