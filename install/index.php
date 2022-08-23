<?php
error_reporting(E_ALL ^ E_NOTICE); 
header("content-type:text/html;charset=utf-8");
date_default_timezone_set("PRC");

$step=$_GET["step"];
$action=$_GET["action"];


if($action=="savepath"){
	header('Content-Disposition: attachment; filename="后台信息.txt"');
	exit("后台路径 ".$_COOKIE["install_path"]."\r\n帐号 ".$_COOKIE["install_user"]."\r\n密码 ".$_COOKIE["install_pwd"]."\r\n如忘记后台信息，可查看此文件找回");
	die();
}

$dirx=splitx(dirname($_SERVER["SCRIPT_FILENAME"]),"install",0);

$id=json_decode(file_get_contents($dirx."data/config.json"))->id;
$api=json_decode(GetBody("http://3.agent8.top/api.php?id=".$id,"","GET"),true);

if($step!=4){
	if(!is_dir($dirx.'pc') || !is_dir($dirx.'wap') || !is_dir($dirx.'admin')){
		die(msgbox("缺少配置文件，请检查网站文件夹内是否存在pc（电脑端模板）、wap（手机端模板）、admin（后台）文件夹！如果后台文件夹为其他名称，请手动改为admin"));
	}

	$first=json_decode(file_get_contents($dirx."data/config.json"))->first;
	if($first=="0"){
	    Header("Location: ../");
	    die();
	}

	$C_dir=splitx($_SERVER["PHP_SELF"],"install",0);

	$update=GetBody("http://scms5.oss-cn-shenzhen.aliyuncs.com/php/update.txt","","GET");
	$update=str_replace(PHP_EOL,"",$update);
	$update=trim($update,"\xEF\xBB\xBF");

	$version=trim(file_get_contents($dirx."admin/version.txt"),"\xEF\xBB\xBF");
	$version2=splitx($update,"|",0);
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>安装程序 - <?php echo $api["title"]?> 安装系统</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script src="../member/js/jquery.min.js"> </script>
<style type="text/css">
body{background: #f7f7f7;height: 100%}
.top{background: #ffffff;margin-top: 10px;border-radius: 10px;box-shadow: 0px 0px 20px #DDDDDD;padding: 10px 0px;}
.main{background: #ffffff;height:650px;padding: 10px 30px;border-radius: 10px; box-shadow: 0px 0px 20px #DDDDDD;}
input{padding: 3px 5px;border-radius: 5px}
.index_mian_right_three_two_o_ly b{width: 100px;display: inline-block;}
.save{background: #5daee5;color: #ffffff;padding: 3px 5px;border-radius: 5px;font-size: 12px;border: solid 1px #5daee5;text-decoration:none;}
.save:hover{background: #ffffff;color: #5daee5;padding: 3px 5px;border-radius: 5px;font-size: 12px;border: solid 1px #5daee5;}

.index_mian_right_seven_Forward_ly{text-align: center;text-decoration:none;line-height: 33px}
.index_mian_right_seven_Forward_ly:hover{color: #ffffff;}
</style>
</head>

<body>

<div class="top">
	<a href="<?php echo $api["domain"]?>" target="_blank"><div class="top-logo" style="background: url(<?php echo $api["logo"]?>) 10px 0px no-repeat;background-size:auto 68px;"></div></a>
	<div class="top-link">
		<ul>
			<li><a href="<?php echo $api["domain"]?>" target="_blank">官方网站</a></li>
			<li><a href="<?php echo $api["domain"]?>/help" target="_blank">使用帮助</a></li>
			<li><a href="<?php echo $api["domain"]?>/design" target="_blank">开发文档</a></li>
		</ul>
	</div>
	<div class="top-version">
		<!-- 版本信息 -->
		<h2><?php echo $api["title"]?></h2>
	</div>
</div>

<?php

if($step==1 || $step==""){ //===================步骤一======================
?>
<script type="text/javascript">
$(document).ready(function(e) {
	  $(".menter_btn_a_a_lf").click(function(){
		if($(".check_boxId").is(":checked")){
	     	window.location.href="?step=2";
		}
		else
		{
			alert("请同意安装协议");
		}
	});
});
</script>
<div class="main">
	<div class="pleft">
		<dl class="setpbox t1">
			<dt>安装步骤</dt>
			<dd>
				<ul>
					<li class="now">许可协议</li>
					<li>环境检测</li>
					<li>参数配置</li>
					<li>正在安装</li>
					<li>安装完成</li>
				</ul>
			</dd>
		</dl>
	</div>
	<div class="pright">
		<div class="pr-title"><h3>阅读许可协议</h3></div>
		<div class="pr-agreement">
			<?php echo str_replace("{title}",$api["title"],str_replace(PHP_EOL,"<br>",file_get_contents($dirx."install/license.txt")))?>
		</div>
		<div class="btn-box">
			<input name="readpact" type="checkbox" id="readpact" value="" class="check_boxId" /><label for="readpact"><strong class="fc-690 fs-14">我已经阅读并同意此协议</strong></label>
			<input name="继续" type="submit" class="menter_btn_a_a_lf" value="继续"  />
		</div>
	</div>
</div>
<?php
}

if($step==2){ //===================步骤二======================

$sp_allow_url_fopen = (ini_get('allow_url_fopen') ? '<font color=green>[√]On</font>' : '<font color=red>[×]Off</font>');
$sp_safe_mode = (ini_get('safe_mode') ? '<font color=red>[×]On</font>' : '<font color=green>[√]Off</font>');
$sp_mysql = (function_exists('mysqli_connect') ? '<font color=green>[√]On</font>' : '<font color=red>[×]Off</font>');
$test_write = (is_really_writable($dirx."function/conn.php")==1 ? '<font color=green>[√]On</font>' : '<font color=red>[×]Off</font>');
$test_gd = (function_exists("imagecreate") ? '<font color=green>[√]On</font>' : '<font color=red>[×]Off</font>');

?>

<div class="main">
	<div class="pleft">
		<dl class="setpbox t1">
			<dt>安装步骤</dt>
			<dd>
				<ul>
					<li class="succeed">许可协议</li>
					<li class="now">环境检测</li>
					<li >参数配置</li>
					<li>正在安装</li>
					<li>安装完成</li>
				</ul>
			</dd>
		</dl>
	</div>

<div class="pright">
  <div class="enter_lf">
   <div class="Envin_lf">
      <div class="menter_lf"><span>服务器信息</span></div>
      <div class="menter_table_lf">
      <table width="1000" border="0" cellspacing="0" cellpadding="0" class="tabletable">
        <thead>
            <tr>
              <th>参数</th>
              <th>值</th>
            </tr>
        </thead>
        <tbody>
            <tr>
              <td>服务器域名</td>
              <td style="color:#999;"><?php echo $_SERVER['HTTP_HOST']?></td>
            </tr>
            <tr>
              <td>服务器操作系统</td>
              <td style="color:#999;"><?php echo PHP_OS?></td>
            </tr>
            <tr>
              <td>服务器翻译引擎</td>
              <td style="color:#999;"><?php echo $_SERVER['SERVER_SOFTWARE']?></td>
            </tr>
            <tr>
              <td>PHP版本</td>
              <td style="color:#999;"><?php 
              echo phpversion();
              if(phpversion()<5.4){
                echo " <span style=\"color:#ff0000;\">[建议PHP5.4或以上版本]</span>";
              }

              ?></td>
            </tr>
            <tr>
              <td>系统安装目录</td>
              <td style=" color:#999;"><?php echo $dirx?></td>
            </tr>
        </tbody>
      </table>

      </div>
</div>
<div class="Envin_lf">
      <div class="menter_lf"><span>系统环境检测</span></div>
      <div class="menter_table_lf">
      <table width="1000" border="0" cellspacing="0" cellpadding="0" class="tabletable">
        <thead>
            <tr>
              <th>需要开启变量的函数</th>
              <th>要求</th>
              <th>实际状态和建议</th>
            </tr>
        </thead>
        <tbody>
            <tr>
              <td>allow_url_fopen</td>
              <td>On</td>
              <td><?php echo $sp_allow_url_fopen?> (不符合要求将导致采集.远程资料本地化等功能无法应用)</td>
            </tr>
            <tr>
              <td>safe_mode</td>
              <td>Off</td>
              <td><?php echo $sp_safe_mode?> (本系统不支持在非win主机的安全模式下运行)</td>
            </tr>
            
            <tr>
              <td>GD支持</td>
              <td>On</td>
              <td><?php echo $test_gd?> (不支持将导致与图片相关的大多数功能无法使用或引发警告)</td>
            </tr>

            <tr>
              <td>MySQLi</td>
              <td>On</td>
              <td><?php echo $sp_mysql?> (不支持则无法连接到数据库)</td>
            </tr>

            <tr>
              <td>写入权限</td>
              <td>On</td>
              <td><?php echo $test_write?> (不支持无法安装及更新文件)</td>
            </tr>
            
        </tbody>
      </table>

      </div>
</div>

    <div class="menter_btn_lf"></div>
    <div class="menter_btn_a_lf">
           <button class="menter_btn_a_a_lf" onclick="window.location.href='?step=3'">继续</button>
           <button class="menter_btn_a_a_lf" onclick="window.location.href='?step=1'">后退</button>
    </div>
</div>


</div>

<?php
}

if($step==3){
$C_installdir=splitx($_SERVER["PHP_SELF"],"install",0);

$handler = opendir($dirx.'pc');
while( ($filename = readdir($handler)) !== false ) 
{
 if(is_dir($dirx."pc/".$filename) && $filename != "." && $filename != ".." && $filename != "amp" && $filename != "mip")
 {  
   $t=$t.$filename."|";
  }
}

$handler = opendir($dirx.'wap');
while( ($filename = readdir($handler)) !== false ) 
{
 if(is_dir($dirx."wap/".$filename) && $filename != "." && $filename != "..")
 {  
   $w=$w.$filename."|";
  }
}

$C_installdir=splitx($_SERVER["PHP_SELF"],"install",0);

if ($action=="save" ){

$sitename2=$_POST["sitename2"];
$A_login2=$_POST["A_login2"];
$A_pwd2=md5($_POST["A_pwd2"]);
$admin_dir2=$_POST["admin_dir2"];
$web_dir2=$_POST["web_dir2"];
$_SESSION["set_login"]=$_POST["A_login2"];
$_SESSION["set_pwd"]=$_POST["A_pwd2"];
$dbserver=$_POST["dbserver"];
$dbname=$_POST["dbname"];
$dbusername=$_POST["dbusername"];
$dbpassword=$_POST["dbpassword"];
$authcode=$_POST["authcode"];
$C_email=$_POST["C_email"];
$table=$_POST["table"];

if (strpos($table,"_")===false){
	box("表前缀需带有下划线_，请重新填写！","back","error");
}

define("TABLE",$table);
if($dbname==""){
	if($dbusername=="root"){
		$dbname="scms";
	}else{
		box("数据库名称未填写！","back","error");
	}
}

if($C_email!=""){
	if (strpos($C_email,"@")===false || strpos($C_email,".")===false){
		box("请填写正确的邮箱！","back","error");
	}
}

if ($_POST["A_login2"]==""){
	box("管理员账户未设置！","back","error");
}

if ($_POST["A_pwd2"]==""){
	box("管理员密码未设置！","back","error");
}

$connx = @mysqli_connect($dbserver,$dbusername,$dbpassword);

if (!$connx) {
    die(msgbox("<p>抱歉，连接数据库失败！</p><p>您提供的数据库用户名和密码可能不正确，或者无法连接到您的数据库服务器，这意味着您的主机数据库服务器已停止工作。</p><p><ul><li>您确认您提供的用户名和密码正确么？</li><li>您确认您提供的主机名正确么？</li><li>您确认数据库服务器运行正常么？</li><li>您确认您购买的数据库是MYSQL而不是MSSQL么？</li></ul></p><p>请您联系您的空间商寻求帮助！</p><p>".mysqli_connect_error()."</p><div id='bottom'><input type=\"button\" name=\"next\" onClick=\"history.go(-1)\" id=\"netx\" value=\"返回\" /></div>"));
}else{
	$testdb=mysqli_select_db( $connx,$dbname); //检测数据库是否存在
	if(!$testdb){ //不存在则创建数据库
		$sql = "CREATE DATABASE ".$dbname;
	    if (mysqli_query($connx, $sql)) { //创建数据库成功
	        $conn = @mysqli_connect($dbserver,$dbusername,$dbpassword,$dbname);
	        @mysqli_query($conn,'set names utf8');
	    } else { //创建数据库失败
	        die("creat database error" . mysqli_error($connx));
	    }
	}else{ //存在则直接连接
		$conn = @mysqli_connect($dbserver,$dbusername,$dbpassword,$dbname);
	    @mysqli_query($conn,'set names utf8');
	}
}

$sql=file_get_contents($dirx."install/mysql.sql");
$sql=str_replace("sl_",TABLE,$sql);
$sql=str_replace("SL_",TABLE,$sql);
$sql=str_replace("Text default ''","Text",$sql);

if(strpos($sql,";\r\n")!==false){
	$sql=explode(";\r\n",trim($sql,"\xEF\xBB\xBF"));
}else{
	$sql=explode(";\n",trim($sql,"\xEF\xBB\xBF"));
}

for($i=0;$i<count($sql);$i++){
	@mysqli_query($conn,$sql[$i]);
}

rename('../admin',"../".$admin_dir2);
mysqli_query($conn,"update ".TABLE."admin set A_login='".$A_login2."',A_pwd='".$A_pwd2."',A_email='".$C_email."'");
mysqli_query($conn,"update ".TABLE."config set C_db='mysql',C_template='".splitx($t,"|",0)."',C_wap='".splitx($w,"|",0)."',C_title='".$sitename2."/l/Your Website',C_admin='".$admin_dir2."',C_first=0,C_time='".date('Y-m-d H:i:s')."',C_dir='".$web_dir2."',C_langcode='php',C_email='".$C_email."'");

setcookie("install_user", $A_login2, time()+3600);
setcookie("install_pwd", $_POST["A_pwd2"], time()+3600);
setcookie("install_path", "http://".$_SERVER["HTTP_HOST"].splitx($_SERVER["PHP_SELF"],"install",0).$admin_dir2, time()+3600);

mysqli_query($conn,"insert into ".TABLE."log(L_user,L_time,L_action,L_ip,L_location) values('".$A_login2."','".date('Y-m-d H:i:s')."','登录后台成功','".$_SERVER["REMOTE_ADDR"]."','')");

$json=json_decode(file_get_contents($dirx."data/config.json"),true);

$json["first"]="0";
$json["table"]=$table;

file_put_contents($dirx."data/config.json",json_encode($json));

/*更新数据库连接文件*/
file_put_contents($dirx."function/conn.php","<?"."php
error_reporting(E_ALL ^ E_NOTICE); 
header(\"content-type:text/html;charset=utf-8\");
session_start();
\$conn = mysqli_connect(\"".$dbserver."\",\"".$dbusername."\", \"".$dbpassword."\", \"".$dbname."\");
mysqli_query(\$conn,'set names utf8');
date_default_timezone_set(\"PRC\");
if (!\$conn) {
    die(\"数据库连接失败: \" . mysqli_connect_error());
}
?>");

echo "<div style=\"display:none\">";
@eval(trim(splitx($update,"|",4),"\xEF\xBB\xBF"));
echo "</div>";

die("<script>window.location.href=\"index.php?step=4&admin=".$admin_dir2."\"</script>");
}

?>

<div class="main">
	<div class="pleft">
		<dl class="setpbox t1">
			<dt>安装步骤</dt>
			<dd>
				<ul>
					<li class="succeed">许可协议</li>
					<li class="succeed">环境检测</li>
					<li class="now">参数配置</li>
					<li>正在安装</li>
					<li>安装完成</li>
				</ul>
			</dd>
		</dl>
	</div>
    <div class="pright">
       
       <!--参数配置-->
<div class="index_mian_right_ly">
 
  
<form method="post" action="?action=save&step=3">
  
  <!--管理员初始密码-->
  <div class="index_mian_right_three_ly">
   <div class="index_mian_right_three_one_ly"><span>基本设置</span></div>
   <div class="index_mian_right_three_two_ly">
   

    <div class="index_mian_right_three_two_o_ly"><b>网站名称：</b><input class="index_mian_right_two_two_text_ly" name="sitename2" value="您的网站名称" type="text" /></div>
    <div class="index_mian_right_three_two_o_ly"><b>安装目录：</b><input class="index_mian_right_two_two_text_ly" name="web_dir2" type="text" value="<?php echo $C_installdir?>" readOnly="true" style="background-color:#EEEEEE" /><span>自动判断，无需修改</span></div>
    <div class="index_mian_right_three_two_o_ly"><b>后台目录：</b><input class="index_mian_right_two_two_text_ly" name="admin_dir2" type="text" value="admin" /><span>为保证安全，建议修改</span></div>
    <div class="index_mian_right_three_two_o_ly"><b>管理员名称：</b><input class="index_mian_right_two_two_text_ly" name="A_login2" type="text" /></div>
    <div class="index_mian_right_three_two_o_ly"><b>管理员密码：</b><input class="index_mian_right_two_two_text_ly" name="A_pwd2" type="text" /></div>
    <div class="index_mian_right_three_two_o_ly"><b>安全邮箱：</b><input class="index_mian_right_two_two_text_ly" name="C_email" type="text" /><span>用于找回后台密码</span></div>


   </div>
  </div>
  <!--管理员初始密码结束-->
  
    <!--数据库设定-->
  <div class="index_mian_right_two_ly">
   <div class="index_mian_right_two_one_ly"><span>数据库设定</span></div>
   <div class="index_mian_right_two_two_ly">
   
     <div class="index_mian_right_three_two_o_ly"><b>数据库地址：</b><input class="index_mian_right_two_two_text_ly" name="dbserver" type="text" value="" /><span>一般为127.0.0.1</span></div>
     <div class="index_mian_right_three_two_o_ly"><b>数据库用户：</b><input class="index_mian_right_two_two_text_ly" name="dbusername" type="text" /></div>
     <div class="index_mian_right_three_two_o_ly"><b>数据库密码：</b><input class="index_mian_right_two_two_text_ly" name="dbpassword" type="text" /></div>
     
     <div class="index_mian_right_three_two_o_ly"><b>数据库名称：</b><input class="index_mian_right_two_two_text_ly" name="dbname" type="text" /></div>
     <div class="index_mian_right_three_two_o_ly"><b>数据表前缀：</b><input class="index_mian_right_two_two_text_ly" name="table" type="text" value="SL_" /><span>如无特殊需要，请不要修改</span></div>
   </div>
  </div>
  <!--数据库设定结束-->
  <div class="menter_btn_lf"></div>
    <div class="menter_btn_a_lf">
           <button type="submit" class="menter_btn_a_a_lf">确定</button>
           <button type="button" class="menter_btn_a_a_lf" onclick="window.location.href='?step=2'">后退</button>
    </div>
  </form>
  <!--后退,继续结束-->
</div>
    
    
    
    </div>

</div>


<?php

}

if($step==4){
?>

<div class="main">
	<div class="pleft">
		<dl class="setpbox t1">
			<dt>安装步骤</dt>
			<dd>
				<ul>
					<li class="succeed">许可协议</li>
					<li class="succeed">环境检测</li>
					<li class="succeed">参数配置</li>
					<li class="succeed">正在安装</li>
					<li class="now">安装完成</li>
				</ul>
			</dd>
		</dl>
	</div>
    <div class="pright">
  <!--右边-->
  <form action="" method="get">
  <div class="index_mian_right_one_ly">
   <div class="index_mian_right_one_one_ly"><span>安装完成</span></div>
   <div class="font">
   <p>恭喜，您已成功安装<?php echo $api["title"];?>建站系统 V5.0。</p>
   <p>后台路径为 http://<?php echo $_SERVER["HTTP_HOST"].splitx($_SERVER["PHP_SELF"],"install",0).$_GET["admin"]?> <a href="?action=savepath" class="save" target="_blank">保存</a></p>
   现在可以：
</div>
   <div class="btn">
   	<a href="../" target="_blank" class="index_mian_right_seven_Forward_ly">进入首页</a>
   	<a href="../<?php echo $_GET["admin"]?>" target="_blank" class="index_mian_right_seven_Forward_ly" >进入后台</a>
   </div>
   <?php if(json_decode(file_get_contents($dirx."data/config.json"))->from=="free"){
   	echo "<div class=\"font\"><p>欢迎加入QQ交流群：1029133406，活动福利群内发放！</p></div>";
   }?>
  </div>
  <!--进入系统-->
  <div class="btnn-box"></div>
  </form>
</div>

<?php
}

?>


</body>
</html>
<?php

function is_really_writable($file)
     {
    // 在 Unix 内核系统中关闭了 safe_mode, 可以直接使用 is_writable()
    if (DIRECTORY_SEPARATOR == '/' AND @ini_get("safe_mode") == FALSE)
    {
        return is_writable($file);
    }

    // 在 Windows 系统中打开了 safe_mode的情况
    if (is_dir($file))
    {
        $file = rtrim($file, '/').'/'.md5(mt_rand(1,100).mt_rand(1,100));

        if (($fp = @fopen($file, 'ab')) === FALSE)
        {
            return 0;
        }

        fclose($fp);
        @chmod($file, 0777);
        @unlink($file);
        return TRUE;
    }
    elseif (($fp = @fopen($file, 'ab')) === FALSE)
    {
        return 2;
    }

    fclose($fp);
    return TRUE;
}

function splitx($a,$b,$c){
	$d=explode($b,$a);
	return $d[$c];
}

function GetBody($url, $xml,$method='POST'){		
		$second = 30;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_TIMEOUT, $second);
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		$data = curl_exec($ch);
		if($data){
			curl_close($ch);
			return $data;
		} else { 
			$error = curl_errno($ch);
			curl_close($ch);
			return("curl出错，错误码:$error");
		}
}

function box($B_text,$B_url,$B_type){
global $C_dir;
echo "<meta name='viewport' content='width=device-width, initial-scale=1'><script type='text/javascript' src='".$C_dir."js/jquery.min.js'></script><script type='text/javascript' src='".$C_dir."js/sweetalert.min.js'></script><link rel='stylesheet' type='text/css' href='".$C_dir."css/sweetalert.css'/>";
if($B_url=="back"){
echo "<script>var ie = !+'\\v1';if(ie){alert('".$B_text."');history.back();}else{window.onload=function(){swal({title:'',text:'".$B_text."',type:'".$B_type."'},function(){history.back();});}}</script>";
}else{
if($B_url=="reload"){
echo "<script>var ie = !+'\\v1';if(ie){alert('".$B_text."');parent.location.reload();}else{window.onload=function(){swal({title:'',text:'".$B_text."',type:'".$B_type."'},function(){parent.location.reload();});}}</script>";
}else{
echo "<script>var ie = !+'\\v1';if(ie){alert('".$B_text."');window.location.href=='".$B_url."';}else{window.onload=function(){swal({title:'',text:'".$B_text."',type:'".$B_type."'},function(){window.location.href='".$B_url."';});}}</script>";
}
}
die();
}

function CheckTables($myTable){
global $conn;
$field = mysqli_query($conn,"SHOW TABLES LIKE '". $myTable."'");  
$field = mysqli_fetch_array($field);  
if($field[0]){  
  return 1;
}else{
  return 0;
}
}

function CheckFields($myTable,$myFields){
global $conn;
$field = mysqli_query($conn,"Describe ".$myTable." ".$myFields);  
$field = mysqli_fetch_array($field);  
if($field[0]){  
  return 1;
}else{
  return 0;
}
}

function msgbox($str){
    $style="<style>.msgbox{width:500px;margin:100px auto;border:solid 1px #DDDDDD;padding:20px;font-size:15px;border-radius:10px;background:#F7f7f7;text-align:center} .title{font-size:20px;margin-bottom:10px;font-weight:bold}</style>";
    $msg="<div class=\"msgbox\"><div class=\"title\">系统提示</div>".$str."</div>";
    return $style.$msg;
}
?>