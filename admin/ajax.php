<?php
require '../function/conn.php';
require '../function/function.php';
require '../function/mobile.php';

$action = $_GET["action"];
$typex = $_GET["type"];
$D_domain = splitx($_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"], $C_admin, 0);

if ($_GET["lang"] != "") {
    $_SESSION["i"] = $_GET["lang"];
}

switch($typex){
case "download":
Header("Location:http://www.s-cms.cn/scms/product.csv");
break;
case "login":
$username=Replace_Text($_POST["user"]);
$pwd=Replace_Text($_POST["password"]);
$emailcode=t($_POST["emailcode"]);

if($_SESSION["CmsCode"]!=xcode($_POST["vcode"],'DECODE',$_SESSION["CmsCode"],0) || $_POST["vcode"]=="" || $_SESSION["CmsCode"]==""){//滑动验证码不正确
    die("vcode_error");
}else{
    $_SESSION["CmsCode"]="refresh";
    $sql="select * from ".TABLE."log where L_action='登录后台成功' and L_user='".$username."' order by L_id desc limit 1";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if(mysqli_num_rows($result) > 0) {
        $L_ip=$row["L_ip"];//获取到该管理员上次登录成功的IP
    }else{
        $L_ip="0.0.0.0";//此次为该管理员首次登录
    }

    $sql="select * from ".TABLE."admin where A_del=0 and A_login='".$username."' and A_pwd='".strtoupper(md5($pwd))."'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if(mysqli_num_rows($result) > 0) {//帐号密码正确
        $A_email=$row["A_email"];

        if(($A_email=="XXXXX@qq.com" || $A_email=="" || strpos($A_email,"@")===false) && ($emailcode=="" || $emailcode=="undefined")){//管理员邮箱有误
            mysqli_query($conn, "update ".TABLE."config set C_test='123456'");
            die("ip_error2");
        }else{
            if(!checkip($L_ip) && $S_data[0]["S_login2"]==1){//IP验证未通过 && 开启了二次验证
                if($emailcode=="" || $emailcode=="undefined"){//没有传入邮箱验证码
                    $code=rand(111111, 999999);
                    mysqli_query($conn, "update ".TABLE."config set C_test='".$code."'");
                    sendmail("邮箱验证码[".$code."]", "您[IP：".getip()."]正在进行登录[".$_SERVER["HTTP_HOST"]."]后台的操作，邮箱验证码为：<b>".$code."</b>，请在后台登录界面填写。", $A_email);
                    die("ip_error|***".substr($A_email,3));
                }else{
                    $c=getrx("select * from ".TABLE."config","C_test");
                    if($c!=$emailcode){//邮箱验证码错误
                        die("emailcode_error");
                    }else{
                        $check="success";
                    }
                }
            }else{
                $check="success";
            }
            if($check=="success"){
                //sendmail("后台登录提醒", "您的管理员".$username."[IP：".getip()."]刚刚登录了[".$_SERVER["HTTP_HOST"]."]后台的。<br>如果非管理员本人操作，请检查您的后台路径[".$C_admin."]及账号密码是否已泄露，请尽快修改！", $C_email);
                setcookie("user",$username);
                setcookie("pass",md5("pass".strtoupper(md5($pwd))));
                $_SESSION["user"]=$username;
                $_SESSION["pass"]=md5("pass".strtoupper(md5($pwd)));
                setcookie("A_type",$row["A_type"]);
                setcookie("auth",$row["A_part"]);
                setcookie("newsauth",$row["A_newsauth"]);
                setcookie("productauth",$row["A_productauth"]);
                setcookie("textauth",$row["A_textauth"]);
                setcookie("formauth",$row["A_formauth"]);
                setcookie("bbsauth",$row["A_bbsauth"]);

                //清理数据库备份，仅保留10天
                $handler = opendir('../backup');
                while( ($FileName = readdir($handler)) !== false ) {
                    if(is_file("../backup/".$FileName)){  
                        if(time()-filemtime("../backup/".$FileName)>864000 && substr($FileName,-11)=="_backup.txt"){
                            unlink("../backup/".$FileName);
                        }
                    }
                }

                //备份数据库
                if($S_data[0]["S_backup"]==1 && is_dir("../backup")){
                    $q1 = mysqli_query($conn, "show tables");
                    while ($t = mysqli_fetch_array($q1)) {
                        $table = $t[0];
                        if(strpos(strtolower($table),strtolower(TABLE))!==false){
                        $q2 = mysqli_query($conn, "show create table `$table`");
                        $sql = mysqli_fetch_array($q2);
                        $mysql.= "DROP TABLE IF EXISTS `$table`" . ";\r\n" . $sql['Create Table'] . ";\r\n";
                        $q3 = mysqli_query($conn, "select * from `$table`");
                        while ($data = mysqli_fetch_assoc($q3)) {
                            $keys = array_keys($data);
                            $keys = array_map('addslashes', $keys);
                            $keys = join('`,`', $keys);
                            $keys = "`" . $keys . "`";
                            $vals = array_values($data);
                            $vals = array_map('addslashes', $vals);
                            $vals = join("','", $vals);
                            $vals = "'" . $vals . "'";
                            $mysql.= "insert into `$table`($keys) values($vals);\r\n";
                        }
                    }
                    }
                    $mysql = str_replace('CREATE TABLE', 'CREATE TABLE IF NOT EXISTS', $mysql);
                    $mysql = "-- 备份时间：" . date('Y-m-d H:i:s') . " 域名：" . $_SERVER["HTTP_HOST"] . " 备份者：" . $_SESSION["user"] . " 程序版本：" . file_get_contents("version.txt") . " 电脑端：" . $C_template . " 手机端：" . $C_wap . " --;\r\n" . $mysql;
                    @file_put_contents("../backup/" . gen_key(20) . "_backup.txt", str_replace("sl_","SL_",$mysql));
                    if(is_dir("../intall")){
                        @file_put_contents("../install/mysql.sql", str_replace("sl_","SL_",$mysql));
                    }
                }

                @removeDir("../data/plug");  //更新插件
                @mkdir("../data/plug",0755,true);
                //@removeDir("../data/file");  //更新模板
                //@mkdir("../data/file",0755,true);

                mysqli_query($conn,"insert into ".TABLE."log(L_user,L_time,L_action,L_ip,L_location) values('".$username."','".date('Y-m-d H:i:s')."','登录后台成功','".getip()."','".getlocation(getip())."')");
                die("success");
            }
        }
    }else{//帐号密码错误
        mysqli_query($conn,"insert into ".TABLE."log(L_user,L_time,L_action,L_ip,L_location) values('".$username."','".date('Y-m-d H:i:s')."','登录后台失败','".getip()."','".getlocation(getip())."')");
        die("error");
    }
}
break;

case "found":
if($C_email=="XXXXX@qq.com" || $C_email=="" || strpos($C_email,"@")===false){
    die("安全邮箱尚未设置，请通过其他方式找回密码");
}else{
    $sql="select * from ".TABLE."admin where A_email='".t($_REQUEST["email"])."'";
    $result = mysqli_query($conn,  $sql);
    $row = mysqli_fetch_assoc($result);
    if (mysqli_num_rows($result) > 0) {
        sendmail("找回密码邮件","<p>点击链接重置您的管理员密码<p><p><a href=\"http://".$_SERVER["HTTP_HOST"].$C_dir."function/scms.php?action=resetpwd&a=".$row["A_id"]."&p=".$row["A_pwd"]."\" target=\"_blank\">http://".$_SERVER["HTTP_HOST"].$C_dir."function/scms.php?action=resetpwd&a=".$row["A_id"]."&p=".$row["A_pwd"]."</a></p>",$row["A_email"]);
        mysqli_query($conn,"insert into ".TABLE."log(L_user,L_time,L_action,L_ip,L_location) values('','".date('Y-m-d H:i:s')."','通过邮件找回密码','".getip()."','".getlocation(getip())."')");
            die("success");
    }else{
        mysqli_query($conn,"insert into ".TABLE."log(L_user,L_time,L_action,L_ip,L_location) values('','".date('Y-m-d H:i:s')."','通过邮件找回密码（失败）','".getip()."','".getlocation(getip())."')");
            die("未找到该邮箱");
    }
}
break;

case "unlogin":
lg("退出登录后台");
$_SESSION["user"]="";
$_SESSION["pass"]="";
setcookie("user","");
setcookie("pass","");
setcookie("A_type","");
setcookie("auth","");
setcookie("newsauth","");
setcookie("productauth","");
setcookie("textauth","");
setcookie("formauth","");
setcookie("bbsauth","");

die("success|退出成功！");
break;

case "setlang":
switch($_REQUEST["setlang"]){
case 0:
case "0":
if(strpos($C_lang,"0")!==false){
    $_SESSION["i"]=0;
    echo "success|切换成功！";
}else{
    echo "error|请先到“基本设置”开启此语言！";
}
break;

case 1:
if(strpos($C_lang,"1")!==false){
    $_SESSION["i"]=1;
    echo "success|切换成功！";
}else{
    echo "error|请先到“基本设置”开启此语言！";
}
break;

case 2:
if(strpos($C_lang,"2")!==false){
    $_SESSION["i"]=0;
    echo "error|繁体由简体自动转换，无需单独设置！";
}else{
    echo "error|请先到“基本设置”开启此语言！";
}
break;

case 3:
    setlang($C_delang);
    mysqli_query($conn,"update ".TABLE."config set C_lang=".$C_delang);
    echo "success|切换成功！";
break;
default:
    setlang($C_delang);
    mysqli_query($conn,"update ".TABLE."config set C_lang=".$C_delang);
    echo "success|切换成功！";
}
}

if ($_SESSION["user"] != "") {
    $sql = "select * from ".TABLE."admin where A_del=0 and A_login='" . $_SESSION["user"] . "'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if (mysqli_num_rows($result) > 0) {
        $pass = $row["A_pwd"];
    } else {
        die("error|身份验证失效，请重新登陆后台！");
    }
    if (strtolower(md5("pass".strtoupper($pass))) != strtolower($_SESSION["pass"])) {
        setcookie("user", "");
        setcookie("pass", "");
        $_SESSION["user"]="";
        $_SESSION["pass"]="";
        setcookie("A_type", "");
        setcookie("auth", "");
        setcookie("newsauth", "");
        setcookie("productauth", "");
        setcookie("textauth", "");
        setcookie("formauth", "");
        setcookie("bbsauth", "");
        die("error|身份验证失效，请重新登陆后台！");
    }
}

if ($_SESSION["user"] =="" || $_SESSION["pass"] ==""){
    die("error|身份验证失效，请重新登陆后台！");
}else{

switch($typex."_".$action){
case "admin_add":
$parts=getrx("select * from ".TABLE."admin where A_login='".$_SESSION["user"]."'","A_part");
if (splitx($parts,"|",9)!="1"){
    die("error|需要顶级管理员权限！");
}
$A_login=$_POST["A_login"];
$A_pwd=$_POST["A_pwd"];
$A_type=$_POST["A_type"];
$A_email=$_POST["A_email"];

$A_t=$_POST["A_textauth"];
for($i=0;$i<count($A_t);$i++ ){
$A_textauth=$A_textauth.$A_t[$i].",";
}

$A_f=$_POST["A_formauth"];
for($i=0;$i<count($A_f);$i++ ){
$A_formauth=$A_formauth.$A_f[$i].",";
}

$A_n=$_POST["A_newsauth"];
for($i=0;$i<count($A_n);$i++ ){
$A_newsauth=$A_newsauth.$A_n[$i].",";
}

$A_b=$_POST["A_bbsauth"];
for($i=0;$i<count($A_b);$i++ ){
$A_bbsauth=$A_bbsauth.$A_b[$i].",";
}


$A_p=$_POST["A_productauth"];
for($i=0;$i<count($A_p);$i++ ){
$A_productauth=$A_productauth.$A_p[$i].",";
}

$sql="Select * from ".TABLE."admin Where A_login='".$A_login."'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
    die("error|该帐号名已被占用，请重新输入！");
}
$A_a0=$_POST["A_a0"];
if($A_a0!=1){
$A_a0=0;
}
$A_a1=$_POST["A_a1"];
if($A_a1!=1){
$A_a1=0;
}
$A_a2=$_POST["A_a2"];
if($A_a2!=1){
$A_a2=0;
}
$A_a3=$_POST["A_a3"];
if($A_a3!=1){
$A_a3=0;
}
$A_a4=$_POST["A_a4"];
if($A_a4!=1){
$A_a4=0;
}
$A_a5=$_POST["A_a5"];
if($A_a5!=1){
$A_a5=0;
}
$A_a6=$_POST["A_a6"];
if($A_a6!=1){
$A_a6=0;
}
$A_a7=$_POST["A_a7"];
if($A_a7!=1){
$A_a7=0;
}
$A_a8=$_POST["A_a8"];
if($A_a8!=1){
$A_a8=0;
}
$A_a9=$_POST["A_a9"];
if($A_a9!=1){
$A_a9=0;
}
$A_a10=$_POST["A_a10"];
if($A_a10!=1){
$A_a10=0;
}
$A_a11=$_POST["A_a11"];
if($A_a11!=1){
$A_a11=0;
}
$A_a12=$_POST["A_a12"];
if($A_a12!=1){
$A_a12=0;
}
$A_part=$A_a0."|".$A_a1."|".$A_a2."|".$A_a3."|".$A_a4."|".$A_a5."|".$A_a6."|".$A_a7."|".$A_a8."|".$A_a9."|".$A_a10."|".$A_a11."|".$A_a12;
if($A_login!="" && $A_pwd!=""){
$sql="Insert into ".TABLE."admin(A_login,A_pwd,A_type,A_part,A_newsauth,A_bbsauth,A_productauth,A_textauth,A_formauth,A_email) values('".$A_login."','".strtoupper(md5($A_pwd))."',".$A_type.",'".$A_part."','".$A_newsauth."','".$A_bbsauth."','".$A_productauth."','".$A_textauth."','".$A_formauth."','".$A_email."')";

mysqli_query($conn,$sql);

lg("新增管理员 ".$A_login);
die("success|添加成功！");
}else{
die("error|请填全信息！");
}
break;

case "admin_del":
$parts=getrx("select * from ".TABLE."admin where A_login='".$_SESSION["user"]."'","A_part");
if (splitx($parts,"|",9)!="1"){
    die("error|需要顶级管理员权限！");
}
$sql="select count(A_id) as A_count from ".TABLE."admin";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$A_count=$row["A_count"];
if($A_count>1){
    mysqli_query($conn,"update ".TABLE."admin set A_del=1 where A_id=".intval($_GET["A_id"]));
    lg("将管理员放入回收站（ID：".$_GET["A_id"]."）");
    die("success|放入回收站成功!|".$_GET["A_id"]);
}else{
    die("error|请至少保留一个管理员，否则无法登录后台！");
}
break;

case "index_clear":
@removeDir("../data/plug");
@mkdir("../data/plug",0755,true);
@removeDir("../data/file");
@mkdir("../data/file",0755,true);
die("success|清理成功！");
break;

case "admin_delall":
$parts=getrx("select * from ".TABLE."admin where A_login='".$_SESSION["user"]."'","A_part");
if (splitx($parts,"|",9)!="1"){
    die("error|需要顶级管理员权限！");
}
$sql="select count(A_id) as A_count from ".TABLE."admin";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$A_count=$row["A_count"];
$id=$_POST["id"];
if(count($id,",")+1==$A_count){
die("error|请至少保留一个管理员，否则无法登录后台！");
}else{
if(count($id)>0){

$shu=0 ;
for ($i=0 ;$i< count($id);$i++ ){
mysqli_query($conn,"update ".TABLE."admin set A_del=1 where A_id=".$id[$i]);
$shu=$shu+1 ;
$ids=$ids.$id[$i].",";
        }
        $ids= substr($ids,0,strlen($ids)-1);
if($shu>0){ ;
lg("批量将管理员放入回收站（ID：".$ids."）");
die("success|成功将".$shu."个管理员放入回收站|".$ids);
}else{ ;
die("error|删除失败");

}
}else{
die("error|未选择要删除的内容");
}
}
break;

case "admin_edit":
$parts=getrx("select * from ".TABLE."admin where A_login='".$_SESSION["user"]."'","A_part");


$A_id=intval($_GET["A_id"]);
$A_login=$_POST["A_login"];
$A_pwd=$_POST["A_pwd"];
$A_type=$_POST["A_type"];
$A_email=$_POST["A_email"];

$A_t=$_POST["A_textauth"];
for($i=0;$i<count($A_t);$i++ ){
$A_textauth=$A_textauth.$A_t[$i].",";
}

$A_f=$_POST["A_formauth"];
for($i=0;$i<count($A_f);$i++ ){
$A_formauth=$A_formauth.$A_f[$i].",";
}

$A_n=$_POST["A_newsauth"];
for($i=0;$i<count($A_n);$i++ ){
$A_newsauth=$A_newsauth.$A_n[$i].",";
}

$A_b=$_POST["A_bbsauth"];
for($i=0;$i<count($A_b);$i++ ){
$A_bbsauth=$A_bbsauth.$A_b[$i].",";
}



$A_p=$_POST["A_productauth"];
for($i=0;$i<count($A_p);$i++ ){
$A_productauth=$A_productauth.$A_p[$i].",";
}


$A_a0=$_POST["A_a0"];
if($A_a0!=1){
$A_a0=0;
}
$A_a1=$_POST["A_a1"];
if($A_a1!=1){
$A_a1=0;
}
$A_a2=$_POST["A_a2"];
if($A_a2!=1){
$A_a2=0;
}
$A_a3=$_POST["A_a3"];
if($A_a3!=1){
$A_a3=0;
}
$A_a4=$_POST["A_a4"];
if($A_a4!=1){
$A_a4=0;
}
$A_a5=$_POST["A_a5"];
if($A_a5!=1){
$A_a5=0;
}
$A_a6=$_POST["A_a6"];
if($A_a6!=1){
$A_a6=0;
}
$A_a7=$_POST["A_a7"];
if($A_a7!=1){
$A_a7=0;
}
$A_a8=$_POST["A_a8"];
if($A_a8!=1){
$A_a8=0;
}
$A_a9=$_POST["A_a9"];
if($A_a9!=1){
$A_a9=0;
}
$A_a10=$_POST["A_a10"];
if($A_a10!=1){
$A_a10=0;
}
$A_a11=$_POST["A_a11"];
if($A_a11!=1){
$A_a11=0;
}
$A_a12=$_POST["A_a12"];
if($A_a12!=1){
$A_a12=0;
}
$A_part=$A_a0."|".$A_a1."|".$A_a2."|".$A_a3."|".$A_a4."|".$A_a5."|".$A_a6."|".$A_a7."|".$A_a8."|".$A_a9."|".$A_a10."|".$A_a11."|".$A_a12;
if($A_login!=""){

if (splitx($parts,"|",9)==1){
    mysqli_query($conn,"
    update ".TABLE."admin set
    A_login='".$A_login."',
    A_type='".$A_type."',
    A_textauth='".$A_textauth."',
    A_formauth='".$A_formauth."',
    A_newsauth='".$A_newsauth."',
    A_bbsauth='".$A_bbsauth."',
    A_productauth='".$A_productauth."',
    A_part='".$A_part."',
    A_email='".$A_email."'
    where A_id=".$A_id
    );
}

if($A_pwd!=""){
    mysqli_query($conn,"update ".TABLE."admin set A_pwd='".strtoupper(md5($A_pwd))."' where A_id=".$A_id);
}

if($A_login==$_SESSION["user"]){
    setcookie("auth",$A_part);
    setcookie("newsauth",$A_newsauth);
    setcookie("bbsauth",$A_bbsauth);
    setcookie("productauth",$A_productauth);
    setcookie("textauth",$A_textauth);
    setcookie("formauth",$A_formauth);
}

lg("修改管理员（ID：".$A_id."）");
    die("success|修改成功!");
}else{
    die("error|请填全信息！");
}
break;

case "agreement_edit":
$agreement=$_POST["agreement"];
file_put_contents("../member/agreement.txt",str_Replace('\n',PHP_EOL,$agreement));
lg("修改会员协议");
die("success|修改会员协议成功!");
break;

case "bbs_add":
$B_title=$_POST["B_title"];
$B_view=$_POST["B_view"];
$B_time=$_POST["B_time"];
$B_content=str_Replace("\"".$C_dir,"\"{@SL_安装目录}",$_POST["B_content"]);
$B_sort=$_POST["B_sort"];
$B_sh=$_POST["B_sh"];

if(getrx("select * from ".TABLE."bbs where B_title like '%".$B_title."%' and B_time='".$B_time."'","B_id")==""){
if($B_title!="" && $B_content!="" && $B_sort!=""){
mysqli_query($conn,"insert into ".TABLE."bbs(B_title,B_view,B_content,B_sort,B_time,B_sh,B_mid) values(
    '".lang_add("",$B_title)."',
    ".$B_view.",
    '".lang_add("",$B_content)."',
    ".$B_sort.",
    '".$B_time."',
    ".$B_sh.",
    7
)");


lg("新增帖子");
die("success|添加帖子成功!");
}else{
die("error|请填全信息!");
}
}else{
    die("error|请勿重复添加内容！");
}
break;

case "bbs_del":
mysqli_query($conn,"update ".TABLE."bbs set B_del=1 where B_id=".intval($_GET["B_id"]));
lg("将BBS放入回收站（ID：".$_GET["B_id"]."）");
die("success|放入回收站成功!|".$_GET["B_id"]);
break;

case "bbs_delall":

$id=$_POST["id"];
if(count($id>0)){
$shu=0 ;
for ($i=0;$i< count($id);$i++ ){
mysqli_query($conn,"update ".TABLE."bbs set B_del=1 where B_id=".$id[$i]);
$shu=$shu+1;
$ids=$ids.$id[$i].",";
        }
$ids= substr($ids,0,strlen($ids)-1);
if($shu>0){ ;
lg("批量删除帖子（ID：".$ids."）");
die("success|成功删除".$shu."条帖子|".$ids);
}else{
die("error|删除失败");
}
}else{
die("error|未选择要删除的内容");
}
break;

case "bbs_edit":
$B_id=intval($_GET["B_id"]);
$B_title=$_POST["B_title"];
$B_view=$_POST["B_view"];
$B_time=$_POST["B_time"];
$B_content=str_Replace("\"".$C_dir,"\"{@SL_安装目录}",$_POST["B_content"]);
$B_sort=$_POST["B_sort"];
$B_sh=$_POST["B_sh"];
if($B_title!="" && $B_content!="" && $B_sort!=""){
mysqli_query($conn,"update ".TABLE."bbs set
B_title='".lang_add(getrx("select * from ".TABLE."bbs where B_id=".$B_id,"B_title"),$B_title)."',
B_view=".$B_view.",
B_content='".lang_add(getrx("select * from ".TABLE."bbs where B_id=".$B_id,"B_content"),$B_content)."',
B_sort=".$B_sort.",
B_time='".$B_time."',
B_sh=".$B_sh."
where B_id=".$B_id
);
lg("修改帖子（ID：".$B_id."）");
die("success|编辑帖子成功!");
}else{
die("error|请填全信息!");
}
break;

case "bbs_save":
foreach ($_POST as $x=>$value) {
if(splitx($x,"_",0)=="title"){
mysqli_query($conn,"update ".TABLE."bbs set B_title='".lang_add(getrx("select * from ".TABLE."bbs where B_id=".splitx($x,"_",1),"B_title"),$_POST[$x])."' where B_id=".splitx($x,"_",1));
}
} 
lg("保存bbs（在列表页）");
die("success|修改成功!");

break;

case "bsort_add":
$S_title=$_POST["S_title"];
$S_content=$_POST["S_content"];
$S_order=$_POST["S_order"];
$S_pic=$_POST["S_pic"];
$S_lv=$_POST["S_lv"];
$S_sh=$_POST["S_sh"];
$S_hide=$_POST["S_hide"];

if(!is_numeric($S_order) || $S_order==""){
$S_order=0;
}
if($S_title!=""){
mysqli_query($conn,"Insert into ".TABLE."bsort(S_title,S_content,S_order,S_pic,S_lv,S_hide,S_sh) values('".lang_add("",$S_title)."','".lang_add("",$S_content)."',".$S_order.",'".$S_pic."','".$S_lv."','".$S_hide."','".$S_sh."')");

$sql="Select  * from ".TABLE."bsort order by S_id desc limit 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$S_id=$row["S_id"];
}

lg("新增论坛版块[".lang_add("",$S_title)."]");
die("success|添加成功!");
}else{
die("error|请填全信息!");
}
break;

case "bsort_del":
mysqli_query($conn,"update ".TABLE."bsort set S_del=1 where S_id=".intval($_GET["S_id"]));
mysqli_query($conn,"update ".TABLE."bbs set B_del=1 where B_sort=".intval($_GET["S_id"]));
lg("将板块放入回收站（ID：".$_GET["S_id"]."）");
die("success|放入回收站成功!|".$_GET["S_id"]);
break;

case "bsort_delall":
$id=$_POST["id"];
if(count($id)>0){
$shu=0 ;
for ($i=0 ;$i< count($id);$i++){ 
mysqli_query($conn,"update ".TABLE."bsort set S_del=1 where S_id=".$id[$j]);
mysqli_query($conn,"update ".TABLE."bbs set B_del=1 where B_sort=".$id[$j]);
$shu=$shu+1 ;
$ids=$ids.$id[$i].",";
}
$ids= substr($ids,0,strlen($ids)-1);
if($shu>0){ ;

lg("批量删除论坛版块（ID：".$ids."）");
die("success|成功删除".$shu."个板块|".$ids);

}else{ 
die("error|删除失败");

} 
}else{
die("error|未选择要删除的内容");
}
break;

case "bsort_edit":

if(strpos($_COOKIE["bbsauth"],"all")!==false){
$auth_info="";
}else{
$bbsauth=explode(",",$_COOKIE["bbsauth"]);
for ($i=0 ; $i<count($bbsauth)-1;$i++){
$tj=$tj."or S_id=".$bbsauth[$i]." ";
}
if($tj==""){
$auth_info=" and S_id<0";
}else{
$tj= substr($tj,-(strlen($tj)-3));
$auth_info=" and (".$tj.")";
}
}
$S_id=intval($_GET["S_id"]);
$S_title=$_POST["S_title"];
$S_content=$_POST["S_content"];
$S_order=$_POST["S_order"];
$S_pic=$_POST["S_pic"];
$S_lv=$_POST["S_lv"];
$S_hide=$_POST["S_hide"];
$S_sh=$_POST["S_sh"];
if(!is_numeric($S_order) || $S_order==""){
$S_order=0;
}
if($S_title!=""){

mysqli_query($conn,"update ".TABLE."bsort set
S_title='".lang_add(getrx("select * from ".TABLE."bsort where S_id=".$S_id,"S_title"),$S_title)."',
S_content='".lang_add(getrx("select * from ".TABLE."bsort where S_id=".$S_id,"S_content"),$S_content)."',
S_order='".$S_order."',
S_pic='".$S_pic."',
S_lv='".$S_lv."',
S_sh='".$S_sh."',
S_hide='".$S_hide."'
where S_id=".$S_id
);

lg("修改论坛版块（ID：".$S_id."）");
die("success|修改成功!");
}else{
die("error|请填全信息!");
}
break;

case "bsort_save":

foreach ($_POST as $x=>$value) {
if(splitx($x,"_",0)=="title"){
mysqli_query($conn,"update ".TABLE."bsort set S_title='".lang_add(getrx("select * from ".TABLE."bsort where S_id=".splitx($x,"_",1),"S_title"),$_POST[$x])."' where S_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="order"){
mysqli_query($conn,"update ".TABLE."bsort set S_order=".$_POST[$x]." where S_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="pic"){
if(substr($_POST[$x],0,5)=="media" || substr($_POST[$x],0,6)=="images" || substr($_POST[$x],0,4)=="http"){
$pic=$_POST[$x];
}else{
$pic="media/".$_POST[$x];
}
mysqli_query($conn,"update ".TABLE."bsort set S_pic='".$pic."' where S_id=".splitx($x,"_",1));
}
} 

lg("保存论坛板块（在列表页）");
die("success|修改成功!");
break;

case "bug_":

$bug=$_POST["bug"];
$page=$_POST["page"];
$times=$_POST["times"];
$email=$_POST["email"];
$code=$_POST["code"];
$domain=$_SERVER["HTTP_HOST"];
if($_SESSION["CmsCode"]!=xcode($_POST["code"],'DECODE',$_SESSION["CmsCode"],0) || $_POST["code"]=="" || $_SESSION["CmsCode"]==""){
    die("error|验证码错误!");
}else{
    if(strpos($page,"http")===false){
        die("error|页面URL请以http开头!");
    }else{
        if($bug!="" && $page!="" && $times!="" && $email!=""){
            getbody("http://php.s-cms.cn/access.php?action=bug","bug=".$bug."&page=".$page."&times=".$times."&email=".$email."&domain=".$domain."&version=php版".file_get_contents("version.txt"));
            die("success|已成功提交，请等待回复");
        }else{
            die("error|请填全信息!");
        }
    }
}
break;

case "collection_add":
$C_title=$_POST["C_title"];
$C_start=$_POST["C_start"];
$C_end=$_POST["C_end"];
$C_titlestart=$_POST["C_titlestart"];
$C_titleend=$_POST["C_titleend"];
$C_contentstart=$_POST["C_contentstart"];
$C_contentend=$_POST["C_contentend"];
$C_url=$_POST["C_url"];
$C_pic=$_POST["C_pic"];
$C_nsort=$_POST["C_nsort"];
$C_code=$_POST["C_code"];
$C_pagestart=intval($_POST["C_pagestart"]);
$C_pageend=intval($_POST["C_pageend"]);
$C_timestart=$_POST["C_timestart"];
$C_timeend=$_POST["C_timeend"];
if($C_title!=""){

mysqli_query($conn,"insert into ".TABLE."collection(
    C_title,
    C_start,
    C_end,
    C_titlestart,
    C_titleend,
    C_contentstart,
    C_contentend,
    C_url,
    C_pic,
    C_nsort,
    C_pagestart,
    C_pageend,
    C_timestart,
    C_timeend,
    C_code) values(
    '".$C_title."',
    '".$C_start."',
    '".$C_end."',
    '".$C_titlestart."',
    '".$C_titleend."',
    '".$C_contentstart."',
    '".$C_contentend."',
    '".$C_url."',
    '".$C_pic."',
    ".$C_nsort.",
    ".$C_pagestart.",
    ".$C_pageend.",
    '".$C_timestart."',
    '".$C_timeend."',
    '".$C_code."'
    )");

lg("新增采集任务");
die("success|添加成功！");
}else{
die("error|请填全信息！");
}
break;

case "collection_all":
$url=$_GET["pageurl"];
$id=$_GET["id"];

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
    $C_timestart=$row["C_timestart"];
    $C_timeend=$row["C_timeend"];
}

$contentx = get_gb_to_utf8(getbody($url,"","GET"));
$contentx = str_Replace(PHP_EOL, "", $contentx);


if (strpos($contentx, $C_titlestart) !== false && strpos($contentx, $C_contentstart) !== false) {
    $page_title = trim(strip_tags(splitx(splitx($contentx, $C_titlestart, 1) , $C_titleend, 0)));

    if($C_timestart!="" && $C_timeend!=""){
    	$page_time = trim(strip_tags(splitx(splitx($contentx, $C_timestart, 1) , $C_timeend, 0)));
    	if(!is_Date($page_time)){
    		$page_time = date('Y-m-d H:i:s');
    	}
    }else{
    	$page_time = date('Y-m-d H:i:s');
    }
    
    $page_content = clearjscss(splitx(splitx($contentx, $C_contentstart, 1) , $C_contentend, 0));
    $page_content = str_Replace("<div", "<p", $page_content);
    $page_content = str_Replace("</div>", "</p>", $page_content);
    $path = str_replace(splitx($url, "/", count(explode("/", $url))) , "", $url);

    $sql2 = "select * from ".TABLE."news where N_title like '%" . $page_title . "%'";
    $result2 = mysqli_query($conn, $sql2);
    $row2 = mysqli_fetch_assoc($result2);
    if (mysqli_num_rows($result2) <= 0) {
        if (strpos($page_content, " src=\"") !== false) {
            $src = explode(" src=\"",$page_content);
            for ($jj = 1; $jj < count($src); $jj++) {

                if (substr(splitx($src[$jj], "\"", 0),0,4)=="http" || substr(splitx($src[$jj], "\"", 0),0,2)=="//") {
                    $srcx = splitx($src[$jj], "\"", 0);
                } else {
                    if(substr(splitx($src[$jj], "\"", 0),0,1)=="/"){
                        $srcx = "http://".splitx($url,"/",2).splitx($src[$jj], "\"", 0);
                    }else{
                        $srcx = str_replace(splitx($url,"/",count(explode("/",$url))-1),"",$url) . splitx($src[$jj], "\"", 0);
                    }
                }

                if ($C_pic == 1) {
                    $page_content = str_Replace(splitx($src[$jj], "\"", 0) , $C_dir . "media/" . downpic($srcx) , $page_content);
                } else {
                    $page_content = str_Replace(splitx($src[$jj], "\"", 0) , $srcx, $page_content);
                }
            }

            if (substr(splitx($src[1], "\"", 0),0,4)=="http" || substr(splitx($src[1], "\"", 0),0,2)=="//") {
                $picx = splitx($src[1], "\"", 0);
            } else {
                if(substr(splitx($src[1], "\"", 0),0,1)=="/"){
                    $picx = "http://".splitx($url,"/",2).splitx($src[1], "\"", 0);
                }else{
                    $picx = str_replace(splitx($url,"/",count(explode("/",$url))-1),"",$url) . splitx($src[1], "\"", 0);
                }
            }

            $N_pic = "media/" . downpic($picx);
        } else {
            $N_pic = "images/nopic.png";
        }

        mysqli_query($conn, "insert into ".TABLE."news(N_title,N_content,N_pagetitle,N_keywords,N_description,N_short,N_sort,N_pic,N_view,N_date,N_author) values('" . lang_add("", $page_title) . "','" . lang_add("", $page_content) . "','" . lang_add("", $page_title) . "','" . lang_add("", $page_title) . "','" . lang_add("", mb_substr(trim(strip_tags(clearjscss($page_content))) , 0, 100,"utf-8")) . "','" . lang_add("", mb_substr(trim(strip_tags(clearjscss($page_content))) , 0, 100,"utf-8")) . "'," . $C_nsort . ",'" . $N_pic . "',100,'" . $page_time . "','" . $_SESSION["user"] . "')");
    }
    echo "success";
}else{
    echo "error";
}

break;


case "collection_del":
mysqli_query($conn,"delete from ".TABLE."collection where C_id=".intval($_GET["C_id"]));
echo "success|删除成功!|".$_GET["C_id"];
lg("删除采集任务（ID：".$_GET["C_id"]."）");
die();
break;

case "collection_delall":
$id=$_POST["id"];
if(count($id)>0){
$shu=0 ;
for ($i=0;$i< count($id);$i++ ){
mysqli_query($conn,"delete from ".TABLE."collection where C_id=".$id[$i]);
$shu=$shu+1 ;
$ids=$ids.$id[$i].",";
}
$ids= substr($ids,0,strlen($ids)-1);
if($shu>0){ ;
lg("批量删除采集任务（ID：".$ids."）");
die("success|成功删除".$shu."个采集任务|".$ids);
}else{
die("error|删除失败");
}
}else{
die("error|未选择要删除的内容");
}
break;

case "collection_edit":
$C_id=intval($_GET["C_id"]);
$C_title=$_POST["C_title"];
$C_start=$_POST["C_start"];
$C_end=$_POST["C_end"];
$C_titlestart=$_POST["C_titlestart"];
$C_titleend=$_POST["C_titleend"];
$C_contentstart=$_POST["C_contentstart"];
$C_contentend=$_POST["C_contentend"];
$C_url=$_POST["C_url"];
$C_pic=$_POST["C_pic"];
$C_nsort=$_POST["C_nsort"];
$C_code=$_POST["C_code"];
$C_pagestart=intval($_POST["C_pagestart"]);
$C_pageend=intval($_POST["C_pageend"]);

$C_timestart=$_POST["C_timestart"];
$C_timeend=$_POST["C_timeend"];
if($C_title!=""){

mysqli_query($conn,"update ".TABLE."collection set
    C_title='".$C_title."',
    C_start='".$C_start."',
    C_end='".$C_end."',
    C_titlestart='".$C_titlestart."',
    C_titleend='".$C_titleend."',
    C_contentstart='".$C_contentstart."',
    C_contentend='".$C_contentend."',
    C_url='".$C_url."',
    C_pic='".$C_pic."',
    C_nsort=".$C_nsort.",
    C_pagestart=".$C_pagestart.",
    C_pageend=".$C_pageend.",
    C_code='".$C_code."',
    C_timestart='".$C_timestart."',
    C_timeend='".$C_timeend."'
    where C_id=".$C_id
    );
lg("编辑采集任务（ID：".$C_id."）");
die("success|修改成功！");
}else{
die("error|请填全信息！");
}
break;

case "collection_start":

    $C_id = intval($_GET["C_id"]);
    $sql = "select * from ".TABLE."collection where C_id=" . $C_id;
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if (mysqli_num_rows($result) > 0) {
        $C_url = $row["C_url"];
        $C_start = $row["C_start"];
        $C_end = $row["C_end"];
        $C_titlestart = $row["C_titlestart"];
        $C_titleend = $row["C_titleend"];
        $C_contentstart = $row["C_contentstart"];
        $C_contentend = $row["C_contentend"];
        $C_timestart = $row["C_timestart"];
        $C_timeend = $row["C_timeend"];
        $C_pic = $row["C_pic"];
        $C_nsort = $row["C_nsort"];
        $C_code = $row["C_code"];
    }
    $list = getbody($C_url,"","GET");
    $list = splitx($list, $C_start, 1);
    $list = splitx($list, $C_end, 0);

    $list = explode("href=\"", $list);
    for ($kk = 1; $kk < count($list); $kk++) {
        if (strpos(splitx($list[$kk], "\"", 0) , "http") === false) {

            if(substr(splitx($list[$kk],"\"",0),0,1)=="/"){
                $urlx="http://".splitx($C_url,"/",2).splitx($list[$kk],"\"",0);
            }else{
                $urlx=str_replace(splitx($C_url,"/",count(explode("/",$C_url))-1),"",$C_url).splitx($list[$kk],"\"",0);
            }

        } else {
            $urlx = splitx($list[$kk], "\"", 0);
        }
        $list_info = $list_info . $urlx . PHP_EOL;
    }

    $page = explode(PHP_EOL, $list_info);
    $news_count = 0;
    for ($ii = 0; $ii < count($page) - 1; $ii++) {
        $contentx = get_gb_to_utf8(getbody($page[$ii],"","GET"));
        $contentx = str_Replace(PHP_EOL, "", $contentx);

        if (strpos($contentx, $C_titlestart) !== false && strpos($contentx, $C_contentstart) !== false) {
            $page_title = trim(strip_tags(splitx(splitx($contentx, $C_titlestart, 1) , $C_titleend, 0)));

		    if($C_timestart!="" && $C_timeend!=""){
		    	$page_time = trim(strip_tags(splitx(splitx($contentx, $C_timestart, 1) , $C_timeend, 0)));
				if(!is_Date($page_time)){
		    		$page_time = date('Y-m-d H:i:s');
		    	}
		    }else{
		    	$page_time = date('Y-m-d H:i:s');
		    }

            $page_content = clearjscss(splitx(splitx($contentx, $C_contentstart, 1) , $C_contentend, 0));
            $page_content = str_Replace("<div", "<p", $page_content);
            $page_content = str_Replace("</div>", "</p>", $page_content);
            $path = str_replace(splitx($page[$ii], "/", count(explode("/", $page[$ii]))) , "", $page[$ii]);

            $sql2 = "select * from ".TABLE."news where N_title like '%" . $page_title . "%'";
            $result2 = mysqli_query($conn, $sql2);
            $row2 = mysqli_fetch_assoc($result2);
            if (mysqli_num_rows($result2) <= 0) {
                if (strpos($page_content, " src=\"") !== false) {
                    $src = explode(" src=\"",$page_content);
                    for ($jj = 1; $jj < count($src); $jj++) {

                        if (substr(splitx($src[$jj], "\"", 0),0,4)=="http" || substr(splitx($src[$jj], "\"", 0),0,2)=="//") {
                            $srcx = splitx($src[$jj], "\"", 0);
                        } else {
                            if(substr(splitx($src[$jj], "\"", 0),0,1)=="/"){
                                $srcx = "http://".splitx($page[$ii],"/",2).splitx($src[$jj], "\"", 0);
                            }else{
                                $srcx = str_replace(splitx($page[$ii],"/",count(explode("/",$page[$ii]))-1),"",$page[$ii]) . splitx($src[$jj], "\"", 0);
                            }
                        }

                        if ($C_pic == 1) {
                            $page_content = str_Replace(splitx($src[$jj], "\"", 0) , $C_dir . "media/" . downpic($srcx) , $page_content);
                        } else {
                            $page_content = str_Replace(splitx($src[$jj], "\"", 0) , $srcx, $page_content);
                        }
                    }

                    if (substr(splitx($src[1], "\"", 0),0,4)=="http" || substr(splitx($src[1], "\"", 0),0,2)=="//") {
                        $picx = splitx($src[1], "\"", 0);
                    } else {
                        if(substr(splitx($src[1], "\"", 0),0,1)=="/"){
                            $picx = "http://".splitx($page[$ii],"/",2).splitx($src[1], "\"", 0);
                        }else{
                            $picx = str_replace(splitx($page[$ii],"/",count(explode("/",$page[$ii]))-1),"",$page[$ii]) . splitx($src[1], "\"", 0);
                        }
                    }

                    $N_pic = "media/" . downpic($picx);
                } else {
                    $N_pic = "images/nopic.png";
                }

                mysqli_query($conn, "insert into ".TABLE."news(N_title,N_content,N_pagetitle,N_keywords,N_description,N_short,N_sort,N_pic,N_view,N_date,N_author) values('" . lang_add("", $page_title) . "','" . lang_add("", $page_content) . "','" . lang_add("", $page_title) . "','" . lang_add("", $page_title) . "','" . lang_add("", mb_substr(trim(strip_tags(clearjscss($page_content))) , 0, 100,"utf-8")) . "','" . lang_add("", mb_substr(trim(strip_tags(clearjscss($page_content))) , 0, 100,"utf-8")) . "'," . $C_nsort . ",'" . $N_pic . "',100,'" . $page_time . "','" . $_SESSION["user"] . "')");
            }
            
            $news_count = $news_count + 1;
        }
    }
    lg("采集文章" . $news_count . "篇");
    die("success|成功采集" . $news_count . "篇文章！");
break;


case "comment_del":
ready(plug("x11","4"));
break;

case "comment_sh1":
ready(plug("x11","5"));
break;

case "comment_sh2":
ready(plug("x11","6"));
break;

case "config2_edit":

if($_SESSION["i"]==0){
$xmls=file_get_contents("../pc/".$C_template."/config.xml");
}
if($_SESSION["i"]==1 && is_file("../pc/".$C_template."/config_e.xml")){
$xmls=file_get_contents("../pc/".$C_template."/config_e.xml");
}else{
$xmls=file_get_contents("../pc/".$C_template."/config.xml");
}

$xml =simplexml_load_string($xmls);

$config_data="<?xml version='1.0' encoding='utf-8'?><xml>".PHP_EOL;
for($i=0;$i<count($xml->page);$i++){

$config_data=$config_data."<page title='".$xml->page[$i]["title"]."'>".PHP_EOL;
    for($j=0;$j<count($xml->page[$i]->tag);$j++){

        $config_data=$config_data."<tag><title>".$xml->page[$i]->tag[$j]->title."</title><content><![CDATA[".stripslashes($_POST["C_".$i.$j])."]]></content><en><![CDATA[".stripslashes($_POST["E_".$i.$j])."]]></en><type>".$xml->page[$i]->tag[$j]->type."</type><url><![CDATA[".stripslashes($_POST["U_".$i.$j])."]]></url></tag>".PHP_EOL;

    }
    $config_data=$config_data."</page>".PHP_EOL;
}
$config_data=$config_data."</xml>";

if($_SESSION["i"]==0){
file_put_contents("../pc/".$C_template."/config.xml",$config_data);
}
if($_SESSION["i"]==1 && is_file("../pc/".$C_template."/config_e.xml")){
file_put_contents("../pc/".$C_template."/config_e.xml",$config_data);
}else{
file_put_contents("../pc/".$C_template."/config.xml",$config_data);
}
echo "success|保存成功!";
lg("修改自定义设置");
die();
break;

case "config_ts":
$html=$_GET["html"];

switch($html){
    case "mip.php":
    $h="mip";
    break;

    case "amp.php":
    $h="amp";
    break;

    case "index.php":
    $h="";
    break;
}
$urls = geturls($html);
$api = 'http://data.zz.baidu.com/urls?site='.$_SERVER["HTTP_HOST"].'&token='.$C_mip_token.'&type='.$h;
$ch = curl_init();
$options =  array(
    CURLOPT_URL => $api,
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POSTFIELDS => implode("\n", $urls),
    CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
);
curl_setopt_array($ch, $options);
$result = curl_exec($ch);

lg("推送MIP页面");
if(strpos($result,"success")!==false){
    die("success|成功向百度推送".json_decode($result)->success."个页面!".$result);
}else{
    die("error|推送失败!".$result);
}

break;

case "config_reupload":
mysqli_query($conn,"delete from ".TABLE."oss");
die("success");
break;

case "config_uppic":
require '../function/oss.php';
$file=str_replace("__"," ",$_GET["file"]);

if(tooss($file)){
    die("success");
}else{
    die("error");
}

break;

case "config_auth":

$code=trim($_POST["code"]);
$info=GetBody("http://cdn.s-cms.cn/access.php","domain=".$_SERVER["HTTP_HOST"]."|".$code."&action=get_authorization");
if($info==strtoupper(md5($_SERVER["HTTP_HOST"]))){
    lg("填写授权码并授权成功");
    mysqli_query($conn,"update ".TABLE."config set C_authcode='".$code."'");
    $_SESSION["a"]=encodex(GetBody("http://cdn.s-cms.cn/access.php","domain=".$_SERVER["HTTP_HOST"]."&action=authorization3x&code=".$code));
    @removeDir("../data/file");  //更新模板
    @mkdir("../data/file",0755,true);
    die("success|授权成功，请重启浏览器重新登录后台！");
}else{
    lg("填写授权码失败（原因：".$info."）");
    die("error|".$info);
}
break;

case "config_edit":

$C_titlez=$_POST["C_title"];
$C_footz=stripslashes($_POST["C_foot"]);
$C_codez=$_POST["C_code"];
$C_fenxiangz=stripslashes($_POST["C_fenxiang"]);
$C_logoxz=$_POST["C_logox"];
$C_logoyz=$_POST["C_logoy"];

if(substr($_POST["C_logo"],0,5)=="media" || substr($_POST["C_logo"],0,6)=="images" || substr($_POST["C_logo"],0,4)=="http"){
    $C_logoz=$_POST["C_logo"];
}else{
    $C_logoz="media/".$_POST["C_logo"];
}

if(substr($_POST["C_m_logo"],0,5)=="media" || substr($_POST["C_m_logo"],0,6)=="images" || substr($_POST["C_m_logo"],0,4)=="http"){
    $C_m_logoz=$_POST["C_m_logo"];
}else{
    $C_m_logoz="media/".$_POST["C_m_logo"];
}

if(substr($_POST["C_ico"],0,5)=="media" || substr($_POST["C_ico"],0,6)=="images" || substr($_POST["C_ico"],0,4)=="http"){
    $C_icoz=$_POST["C_ico"];
}else{
    $C_icoz="media/".$_POST["C_ico"];
}

if(substr($_POST["C_memberbg"],0,5)=="media" || substr($_POST["C_memberbg"],0,6)=="images" || substr($_POST["C_memberbg"],0,4)=="http"){
    $C_memberbgz=$_POST["C_memberbg"];
}else{
    $C_memberbgz="media/".$_POST["C_memberbg"];
}

if(substr($_POST["C_wcode"],0,5)=="media" || substr($_POST["C_wcode"],0,6)=="images" || substr($_POST["C_wcode"],0,4)=="http"){
    $C_wcodez=$_POST["C_wcode"];
}else{
    $C_wcodez="media/".$_POST["C_wcode"];
}


$C_qqz=str_replace('\r\n',",",str_replace(PHP_EOL,",",$_POST["C_qq"]));
$C_qqonz=intval($_POST["C_qqon"]);
$C_member=intval($_POST["C_member"]);
$C_top=intval($_POST["C_top"]);
$C_qq1z=intval($_POST["C_qq1"]);
$C_qq2z=intval($_POST["C_qq2"]);
$C_qq3z=intval($_POST["C_qq3"]);
$C_qq4z=intval($_POST["C_qq4"]);
$C_translatez=intval($_POST["C_translate"]);
$C_npagez=$_POST["C_npage"];
$C_ppagez=$_POST["C_ppage"];

$C_l=$_POST["C_lang"];
for($ii=0;$ii<count($C_l);$ii++){
$C_langz=$C_langz.$C_l[$ii].",";
}

$C_langz= substr($C_langz,0,strlen($C_langz)-1);
$C_delangz=$_POST["C_delang"];
$C_mobilez=str_replace('\r\n',",",str_replace(PHP_EOL,"|",$_POST["C_mobile"]));

$C_keywordsz=$_POST["C_keywords"];
$C_contentz=$_POST["C_content"];
$C_alipayonz=$_POST["C_alipayon"];
$C_wxpayonz=$_POST["C_wxpayon"];
$C_bankonz=$_POST["C_bankon"];
$C_balanceonz=$_POST["C_balanceon"];
$C_alipayz=$_POST["C_alipay"];
$C_alipayidz=$_POST["C_alipayid"];
$C_alipaykeyz=$_POST["C_alipaykey"];
$C_htmlz=intval($_POST["C_html"]);
$C_emailz=$_POST["C_email"];
$C_mailtypez=$_POST["C_mailtype"];
$C_mpwdz=$_POST["C_mpwd"];
$C_smtpz=$_POST["C_smtp"];
$C_qqidz=$_POST["C_qqid"];
$C_qqkeyz=$_POST["C_qqkey"];
$C_wx_appidz=$_POST["C_wx_appid"];
$C_wx_appsecretz=$_POST["C_wx_appsecret"];
$C_wx_appidz2=$_POST["C_wx_appid2"];
$C_wx_appsecretz2=$_POST["C_wx_appsecret2"];
$C_wx_mchidz=$_POST["C_wx_mchid"];
$C_wx_keyz=$_POST["C_wx_key"];
$C_domainz=str_replace("https://","",str_replace("http://","",$_POST["C_domain"]));

$C_psh=$_POST["C_psh"];
$C_tpz=intval($_POST["C_tp"]);
$C_npz=intval($_POST["C_np"]);
$C_ppz=intval($_POST["C_pp"]);
$C_tdz=intval($_POST["C_td"]);
$C_ndz=intval($_POST["C_nd"]);
$C_pdz=intval($_POST["C_pd"]);
$C_weiboz=$_POST["C_weibo"];
$C_qqkjz=intval($_POST["C_qqkj"]);
$C_wxkjz=intval($_POST["C_wxkj"]);
$C_pidz=$_POST["C_pid"];
$C_markz=$_POST["C_mark"];
$C_m_positionz=$_POST["C_m_position"];
$C_reg1z=intval($_POST["C_reg1"]);
$C_reg2z=intval($_POST["C_reg2"]);
$C_reg3z=intval($_POST["C_reg3"]);
$C_flag0z=$_POST["C_flag0"];
$C_flag1z=$_POST["C_flag1"];
$C_flag2z=$_POST["C_flag2"];
$C_paypalz=$_POST["C_paypal"];
$C_paypalonz=$_POST["C_paypalon"];
$C_m_textz=$_POST["C_m_text"];
$C_m_fontz=$_POST["C_m_font"];
$C_m_sizez=$_POST["C_m_size"];
$C_m_colorz=$_POST["C_m_color"];
$C_m_widthz=$_POST["C_m_width"];
$C_m_heightz=$_POST["C_m_height"];
$C_m_transparentz=$_POST["C_m_transparent"];
$C_7PIDz=$_POST["C_7PID"];
$C_7PKEYz=$_POST["C_7PKEY"];
$C_useridz=$_POST["C_userid"];
$C_codeidz=$_POST["C_codeid"];
$C_codekeyz=$_POST["C_codekey"];
$C_smssignz=$_POST["C_smssign"];
$C_hotwordsz=$_POST["C_hotwords"];
$C_langtitle0z=$_POST["C_langtitle0"];
$C_langtitle1z=$_POST["C_langtitle1"];
$C_langtitle2z=$_POST["C_langtitle2"];
$C_langtag0z=$_POST["C_langtag0"];
$C_langtag1z=$_POST["C_langtag1"];
$C_langtag2z=$_POST["C_langtag2"];
$C_ds1z=intval($_POST["C_ds1"]);
$C_ds2z=intval($_POST["C_ds2"]);
$C_ds3z=intval($_POST["C_ds3"]);
$C_kfonz=$_POST["C_kfon"];
$C_ossonz=intval($_POST["C_osson"]);
$C_oss_idz=$_POST["C_oss_id"];
$C_oss_keyz=$_POST["C_oss_key"];
$C_bucketz=$_POST["C_bucket"];
$C_regionz=$_POST["C_region"];
$C_regonz=$_POST["C_regon"];
$C_kefuyunz=$_POST["C_kefuyun"];
$C_beianz=stripslashes($_POST["C_beian"]);
$C_postagez=$_POST["C_postage"];
$C_baoyouz=$_POST["C_baoyou"];
$C_checkaddressz=intval($_POST["C_checkaddress"]);
$C_ratez=$_POST["C_rate"];
$C_tj_accountz=$_POST["C_tj_account"];
$C_tj_pwdz=$_POST["C_tj_pwd"];
$C_tj_idz=$_POST["C_tj_id"];
$C_tj_siteidz=$_POST["C_tj_siteid"];
$C_tj_tokenz=$_POST["C_tj_token"];
$C_dfonz=$_POST["C_dfon"];
$C_zzonz=$_POST["C_zzon"];
$C_shoukuanz=$_POST["C_shoukuan"];
$C_punloginz=intval($_POST["C_punlogin"]);

$iis='<rewrite>
            <rules>
                <rule name="text">
                    <match url="^html/about/([0-9]*).html$" />
                    <action type="Rewrite" url="index.php?type=text&amp;S_id={R:1}&amp;lang=cn" />
                </rule>
                <rule name="form">
                    <match url="^html/form/([0-9]*).html$" />
                    <action type="Rewrite" url="index.php?type=form&amp;S_id={R:1}&amp;lang=cn" />
                </rule>
                <rule name="contact">
                    <match url="^html/contact/index.html$" />
                    <action type="Rewrite" url="index.php?type=contact&amp;lang=cn" />
                </rule>
                <rule name="guestbook">
                    <match url="^html/guestbook/index.html$" />
                    <action type="Rewrite" url="index.php?type=guestbook&amp;lang=cn" />
                </rule>
                <rule name="contact2">
                    <match url="^html/contact/$" />
                    <action type="Rewrite" url="index.php?type=contact&amp;lang=cn" />
                </rule>
                <rule name="guestbook2">
                    <match url="^html/guestbook/$" />
                    <action type="Rewrite" url="index.php?type=guestbook&amp;lang=cn" />
                </rule>
                <rule name="newsinfo">
                    <match url="^html/news/([0-9]*).html$" />
                    <action type="Rewrite" url="index.php?type=newsinfo&amp;S_id={R:1}&amp;lang=cn" />
                </rule>
                <rule name="news">
                    <match url="^html/news/list-([0-9]*).html$" />
                    <action type="Rewrite" url="index.php?type=news&amp;S_id={R:1}&amp;lang=cn" />
                </rule>
                <rule name="news2">
                    <match url="^html/news/list-([0-9]*)-([0-9]*).html$" />
                    <action type="Rewrite" url="index.php?type=news&amp;S_id={R:1}&amp;page={R:2}&amp;lang=cn" />
                </rule>
                <rule name="productinfo">
                    <match url="^html/product/([0-9]*).html$" />
                    <action type="Rewrite" url="index.php?type=productinfo&amp;S_id={R:1}&amp;lang=cn" />
                </rule>
                <rule name="product">
                    <match url="^html/product/list-([0-9]*).html$" />
                    <action type="Rewrite" url="index.php?type=product&amp;S_id={R:1}&amp;lang=cn" />
                </rule>
                <rule name="product2">
                    <match url="^html/product/list-([0-9]*)-([0-9]*).html$" />
                    <action type="Rewrite" url="index.php?type=product&amp;S_id={R:1}&amp;page={R:2}&amp;lang=cn" />
                </rule>


                <rule name="text_en">
                    <match url="^ehtml/about/([0-9]*).html$" />
                    <action type="Rewrite" url="index.php?type=text&amp;S_id={R:1}&amp;lang=en" />
                </rule>
                <rule name="form_en">
                    <match url="^ehtml/form/([0-9]*).html$" />
                    <action type="Rewrite" url="index.php?type=form&amp;S_id={R:1}&amp;lang=en" />
                </rule>
                <rule name="contact_en">
                    <match url="^ehtml/contact/index.html$" />
                    <action type="Rewrite" url="index.php?type=contact&amp;lang=en" />
                </rule>
                <rule name="guestbook_en">
                    <match url="^ehtml/guestbook/index.html$" />
                    <action type="Rewrite" url="index.php?type=guestbook&amp;lang=en" />
                </rule>
                <rule name="contact2_en">
                    <match url="^ehtml/contact/$" />
                    <action type="Rewrite" url="index.php?type=contact&amp;lang=en" />
                </rule>
                <rule name="guestbook2_en">
                    <match url="^ehtml/guestbook/$" />
                    <action type="Rewrite" url="index.php?type=guestbook&amp;lang=en" />
                </rule>
                <rule name="newsinfo_en">
                    <match url="^ehtml/news/([0-9]*).html$" />
                    <action type="Rewrite" url="index.php?type=newsinfo&amp;S_id={R:1}&amp;lang=en" />
                </rule>
                <rule name="news_en">
                    <match url="^ehtml/news/list-([0-9]*).html$" />
                    <action type="Rewrite" url="index.php?type=news&amp;S_id={R:1}&amp;lang=en" />
                </rule>
                <rule name="news2_en">
                    <match url="^ehtml/news/list-([0-9]*)-([0-9]*).html$" />
                    <action type="Rewrite" url="index.php?type=news&amp;S_id={R:1}&amp;page={R:2}&amp;lang=en" />
                </rule>
                <rule name="productinfo_en">
                    <match url="^ehtml/product/([0-9]*).html$" />
                    <action type="Rewrite" url="index.php?type=productinfo&amp;S_id={R:1}&amp;lang=en" />
                </rule>
                <rule name="product_en">
                    <match url="^ehtml/product/list-([0-9]*).html$" />
                    <action type="Rewrite" url="index.php?type=product&amp;S_id={R:1}&amp;lang=en" />
                </rule>
                <rule name="product2_en">
                    <match url="^ehtml/product/list-([0-9]*)-([0-9]*).html$" />
                    <action type="Rewrite" url="index.php?type=product&amp;S_id={R:1}&amp;page={R:2}&amp;lang=en" />
                </rule>


                <rule name="text_cht">
                    <match url="^fhtml/about/([0-9]*).html$" />
                    <action type="Rewrite" url="index.php?type=text&amp;S_id={R:1}&amp;lang=cht" />
                </rule>
                <rule name="form_cht">
                    <match url="^fhtml/form/([0-9]*).html$" />
                    <action type="Rewrite" url="index.php?type=form&amp;S_id={R:1}&amp;lang=cht" />
                </rule>
                <rule name="contact_cht">
                    <match url="^fhtml/contact/index.html$" />
                    <action type="Rewrite" url="index.php?type=contact&amp;lang=cht" />
                </rule>
                <rule name="guestbook_cht">
                    <match url="^fhtml/guestbook/index.html$" />
                    <action type="Rewrite" url="index.php?type=guestbook&amp;lang=cht" />
                </rule>
                <rule name="contact2_cht">
                    <match url="^fhtml/contact/$" />
                    <action type="Rewrite" url="index.php?type=contact&amp;lang=cht" />
                </rule>
                <rule name="guestbook2_cht">
                    <match url="^fhtml/guestbook/$" />
                    <action type="Rewrite" url="index.php?type=guestbook&amp;lang=cht" />
                </rule>
                <rule name="newsinfo_cht">
                    <match url="^fhtml/news/([0-9]*).html$" />
                    <action type="Rewrite" url="index.php?type=newsinfo&amp;S_id={R:1}&amp;lang=cht" />
                </rule>
                <rule name="news_cht">
                    <match url="^fhtml/news/list-([0-9]*).html$" />
                    <action type="Rewrite" url="index.php?type=news&amp;S_id={R:1}&amp;lang=cht" />
                </rule>
                <rule name="news2_cht">
                    <match url="^fhtml/news/list-([0-9]*)-([0-9]*).html$" />
                    <action type="Rewrite" url="index.php?type=news&amp;S_id={R:1}&amp;page={R:2}&amp;lang=cht" />
                </rule>
                <rule name="productinfo_cht">
                    <match url="^fhtml/product/([0-9]*).html$" />
                    <action type="Rewrite" url="index.php?type=productinfo&amp;S_id={R:1}&amp;lang=cht" />
                </rule>
                <rule name="product_cht">
                    <match url="^fhtml/product/list-([0-9]*).html$" />
                    <action type="Rewrite" url="index.php?type=product&amp;S_id={R:1}&amp;lang=cht" />
                </rule>
                <rule name="product2_cht">
                    <match url="^fhtml/product/list-([0-9]*)-([0-9]*).html$" />
                    <action type="Rewrite" url="index.php?type=product&amp;S_id={R:1}&amp;page={R:2}&amp;lang=cht" />
                </rule>

            </rules>
        </rewrite>';

$apache='RewriteEngine On
RewriteRule ^html/about/([0-9]*).html$ index.php?type=text&S_id=$1&lang=cn
RewriteRule ^html/form/([0-9]*).html$ index.php?type=form&S_id=$1&lang=cn
RewriteRule ^html/contact/index.html$ index.php?type=contact&lang=cn
RewriteRule ^html/guestbook/index.html$ index.php?type=guestbook&lang=cn
RewriteRule ^html/contact/$ index.php?type=contact&lang=cn
RewriteRule ^html/guestbook/$ index.php?type=guestbook&lang=cn
RewriteRule ^html/news/([0-9]*).html$ index.php?type=newsinfo&S_id=$1&lang=cn
RewriteRule ^html/news/list-([0-9]*).html$ index.php?type=news&S_id=$1&lang=cn
RewriteRule ^html/news/list-([0-9]*)-([0-9]*).html$ index.php?type=news&S_id=$1&page=$2&lang=cn
RewriteRule ^html/product/([0-9]*).html$ index.php?type=productinfo&S_id=$1&lang=cn
RewriteRule ^html/product/list-([0-9]*).html$ index.php?type=product&S_id=$1&lang=cn
RewriteRule ^html/product/list-([0-9]*)-([0-9]*).html$ index.php?type=product&S_id=$1&page=$2&lang=cn
RewriteRule ^ehtml/about/([0-9]*).html$ index.php?type=text&S_id=$1&lang=en
RewriteRule ^ehtml/form/([0-9]*).html$ index.php?type=form&S_id=$1&lang=en
RewriteRule ^ehtml/contact/index.html$ index.php?type=contact&lang=en
RewriteRule ^ehtml/guestbook/index.html$ index.php?type=guestbook&lang=en
RewriteRule ^ehtml/contact/$ index.php?type=contact&lang=en
RewriteRule ^ehtml/guestbook/$ index.php?type=guestbook&lang=en
RewriteRule ^ehtml/news/([0-9]*).html$ index.php?type=newsinfo&S_id=$1&lang=en
RewriteRule ^ehtml/news/list-([0-9]*).html$ index.php?type=news&S_id=$1&lang=en
RewriteRule ^ehtml/news/list-([0-9]*)-([0-9]*).html$ index.php?type=news&S_id=$1&page=$2&lang=en
RewriteRule ^ehtml/product/([0-9]*).html$ index.php?type=productinfo&S_id=$1&lang=en
RewriteRule ^ehtml/product/list-([0-9]*).html$ index.php?type=product&S_id=$1&lang=en
RewriteRule ^ehtml/product/list-([0-9]*)-([0-9]*).html$ index.php?type=product&S_id=$1&page=$2&lang=en
RewriteRule ^fhtml/about/([0-9]*).html$ index.php?type=text&S_id=$1&lang=cht
RewriteRule ^fhtml/form/([0-9]*).html$ index.php?type=form&S_id=$1&lang=cht
RewriteRule ^fhtml/contact/index.html$ index.php?type=contact&lang=cht
RewriteRule ^fhtml/guestbook/index.html$ index.php?type=guestbook&lang=cht
RewriteRule ^fhtml/contact/$ index.php?type=contact&lang=cht
RewriteRule ^fhtml/guestbook/$ index.php?type=guestbook&lang=cht
RewriteRule ^fhtml/news/([0-9]*).html$ index.php?type=newsinfo&S_id=$1&lang=cht
RewriteRule ^fhtml/news/list-([0-9]*).html$ index.php?type=news&S_id=$1&lang=cht
RewriteRule ^fhtml/news/list-([0-9]*)-([0-9]*).html$ index.php?type=news&S_id=$1&page=$2&lang=cht
RewriteRule ^fhtml/product/([0-9]*).html$ index.php?type=productinfo&S_id=$1&lang=cht
RewriteRule ^fhtml/product/list-([0-9]*).html$ index.php?type=product&S_id=$1&lang=cht
RewriteRule ^fhtml/product/list-([0-9]*)-([0-9]*).html$ index.php?type=product&S_id=$1&page=$2&lang=cht';
if(!check_auth2("x2")){
    $C_qqkjz=0;
    $C_wxkjz=0;
}

if(!check_auth2("x1")){
    $C_htmlz=0;
}

if($C_htmlz==2){
    if(is_file("../web.config")){//存在web.config
        if(strpos(file_get_contents("../web.config"),"<rewrite>")===false){
            file_put_contents("../web.config",str_replace("<system.webServer>","<system.webServer>".$iis,file_get_contents("../web.config")));
        }
    }else{//不存在web.config
        file_put_contents("../web.config",file_get_contents("http://scms5.oss-cn-shenzhen.aliyuncs.com/php/data/web.config.txt"));
    }

    if(is_file("../.htaccess")){//.htaccess
        if(strpos(file_get_contents("../.htaccess"),"RewriteEngine On")===false){
            file_put_contents("../.htaccess",$apache.file_get_contents("../.htaccess"));
        }
    }else{//不存在.htaccess
        file_put_contents("../.htaccess",file_get_contents("http://scms5.oss-cn-shenzhen.aliyuncs.com/php/data/htaccess.txt"));
    }
    
}else{
    if(is_file("../web.config")){
        file_put_contents("../web.config",str_replace($iis,"",file_get_contents("../web.config")));
    }
    if(is_file("../.htaccess")){
        file_put_contents("../.htaccess",str_replace($apache,"",file_get_contents("../.htaccess")));
    }
}

if(!check_auth2("x3")){
    $C_alipayonz=="";
    $C_wxpayonz=="";
    $C_paypalonz=="";
    $C_bankonz=="";
    $C_balanceonz=="";
}

if(!check_auth2("x7") && strpos($C_langz,"1")!==false){
    die("error|尚未开通中英双语功能，请先购买");
}

if(!check_auth2("x11")){
    $C_tdz=0;
    $C_ndz=0;
    $C_pdz=0;
    $C_tpz=0;
    $C_npz=0;
    $C_ppz=0;
}

if($C_ossonz==1 && ($C_oss_idz=="" || $C_bucketz=="" || $C_regonz=="")){
    echo "error|云储存功能已开启但是未正确配置!";
    die();
}

$C_7moneyz=str_replace('\r\n',",",str_replace(PHP_EOL,"@",$_POST["C_7money"]));
$C_closez=intval($_POST["C_close"]);
$C_miponz=intval($_POST["C_mipon"]);
$C_mip_tokenz=$_POST["C_mip_token"];
if($C_titlez!=""){
mysqli_query($conn,"update ".TABLE."config set 

    C_title='".lang_add(getrx("select * from ".TABLE."config where C_id=1","C_title"),$C_titlez)."',
    C_foot='".lang_add(getrx("select * from ".TABLE."config where C_id=1","C_foot"),$C_footz)."',
    C_code='".$C_codez."',
    C_fenxiang='".$C_fenxiangz."',
    C_logo='".$C_logoz."',
    C_logox=".$C_logoxz.",
    C_logoy=".$C_logoyz.",
    C_ico='".$C_icoz."',
    C_memberbg='".$C_memberbgz."',
    C_qq='".lang_add(getrx("select * from ".TABLE."config where C_id=1","C_qq"),$C_qqz)."',
    C_qqon=".$C_qqonz.",
    C_weibo='".$C_weiboz."',
    C_member=".$C_member.",
    C_top=".$C_top.",
    C_npage=".$C_npagez.",
    C_ppage=".$C_ppagez.",
    C_reg1=".$C_reg1z.",
    C_reg2=".$C_reg2z.",
    C_reg3=".$C_reg3z.",
    C_qq1=".$C_qq1z.",
    C_qq2=".$C_qq2z.",
    C_qq3=".$C_qq3z.",
    C_qq4=".$C_qq4z.",
    C_flag='".$C_flag0z.",".$C_flag1z.",".$C_flag2z."',
    C_langtitle='".$C_langtitle0z.",".$C_langtitle1z.",".$C_langtitle2z."',
    C_langtag='".$C_langtag0z.",".$C_langtag1z.",".$C_langtag2z."',
    C_lang='".$C_langz."',
    C_delang=".$C_delangz.",
    C_mobile='".$C_mobilez."',
    C_psh=".$C_psh.",
    C_wcode='".$C_wcodez."',
    C_translate='".$C_translatez."',
    C_keywords='".lang_add(getrx("select * from ".TABLE."config where C_id=1","C_keywords"),$C_keywordsz)."',
    C_content='".lang_add(getrx("select * from ".TABLE."config where C_id=1","C_content"),$C_contentz)."',
    C_alipay='".$C_alipayz."',
    C_alipayon='".$C_alipayonz."',
    C_wxpayon='".$C_wxpayonz."',
    C_bankon='".$C_bankonz."',
    C_balanceon='".$C_balanceonz."',
    C_alipayid='".$C_alipayidz."',
    C_email='".$C_emailz."',
    C_mailtype=".$C_mailtypez.",
    C_smtp='".$C_smtpz."',
    C_qqid='".$C_qqidz."',
    C_paypal='".$C_paypalz."',
    C_paypalon='".$C_paypalonz."',
    C_wx_appid='".$C_wx_appidz."',
    C_wx_mchid='".$C_wx_mchidz."',
    C_html='".$C_htmlz."',
    C_domain='".$C_domainz."',
    C_close=".$C_closez.",
    C_tp=".$C_tpz.",
    C_np=".$C_npz.",
    C_pp=".$C_ppz.",
    C_td=".$C_tdz.",
    C_nd=".$C_ndz.",
    C_pd=".$C_pdz.",
    C_pid='".$C_pidz."',
    C_mark=".$C_markz.",
    C_m_position=".$C_m_positionz.",
    C_m_text='".$C_m_textz."',
    C_m_font='".$C_m_fontz."',
    C_m_size=".$C_m_sizez.",
    C_m_color='".$C_m_colorz."',
    C_m_logo='".$C_m_logoz."',
    C_m_width=".$C_m_widthz.",
    C_m_height=".$C_m_heightz.",
    C_m_transparent=".$C_m_transparentz.",
    C_7PID='".$C_7PIDz."',
    C_7PKEY='".$C_7PKEYz."',
    C_ds1=".$C_ds1z.",
    C_ds2=".$C_ds2z.",
    C_ds3=".$C_ds3z.",
    C_7money='".$C_7moneyz."',
    C_qqkj=".$C_qqkjz.",
    C_wxkj=".$C_wxkjz.",
    C_userid='".$C_useridz."',
    C_codeid='".$C_codeidz."',

    C_smssign='".$C_smssignz."',
    C_osson=".$C_ossonz.",
    C_kfon=".$C_kfonz.",
    C_oss_id='".$C_oss_idz."',

    C_bucket='".$C_bucketz."',
    C_region='".$C_regionz."',
    C_regon=".$C_regonz.",
    C_kefuyun='".$C_kefuyunz."',
    C_beian='".$C_beianz."',
    C_postage=".$C_postagez.",
    C_baoyou=".$C_baoyouz.",
    C_checkaddress=".$C_checkaddressz.",
    C_rate=".$C_ratez.",
    C_mipon=".$C_miponz.",
    C_mip_token='".$C_mip_tokenz."',
    C_tj_account='".$C_tj_accountz."',

    C_tj_id='".$C_tj_idz."',
    C_tj_siteid='".$C_tj_siteidz."',
    C_tj_token='".$C_tj_tokenz."',
    C_dfon='".$C_dfonz."',
    C_zzon='".$C_zzonz."',
    C_punlogin=".$C_punloginz.",
    C_shoukuan='".$C_shoukuanz."',
    C_hotwords='".lang_add(getrx("select * from ".TABLE."config where C_id=1","C_hotwords"),$C_hotwordsz)."'");

if($C_alipaykeyz!=""){
    mysqli_query($conn, "update ".TABLE."config set C_alipaykey='$C_alipaykeyz'");
}
if($C_wx_appsecretz!=""){
    mysqli_query($conn, "update ".TABLE."config set C_wx_appsecret='$C_wx_appsecretz'");
}
if($C_wx_keyz!=""){
    mysqli_query($conn, "update ".TABLE."config set C_wx_key='$C_wx_keyz'");
}
if($C_qqkeyz!=""){
    mysqli_query($conn, "update ".TABLE."config set C_qqkey='$C_qqkeyz'");
}
if($C_mpwdz!=""){
    mysqli_query($conn, "update ".TABLE."config set C_mpwd='$C_mpwdz'");
}
if($C_codekeyz!=""){
    mysqli_query($conn, "update ".TABLE."config set C_codekey='$C_codekeyz'");
}
if($C_oss_keyz!=""){
    mysqli_query($conn, "update ".TABLE."config set C_oss_key='$C_oss_keyz'");
}
if($C_tj_pwdz!=""){
    mysqli_query($conn, "update ".TABLE."config set C_tj_pwd='$C_tj_pwdz'");
}

a("index", 1,"template");

echo "success|修改网站设置成功!";
lg("修改基本设置");
die();
}else{
echo "error|请填全信息!";
die();
}
break;

case "contact_edit":

if($_POST["C_title"]!=""){
mysqli_query($conn, "update ".TABLE."contact set
C_address='".lang_add(getrx("select * from ".TABLE."contact","C_address"),$_POST["C_address"])."',
C_zb='".$_POST["C_zb"]."',
C_title='".lang_add(getrx("select * from ".TABLE."contact","C_title"),$_POST["C_title"])."',
C_entitle='".lang_add(getrx("select * from ".TABLE."contact","C_entitle"),$_POST["C_entitle"])."',
C_content='".lang_add(getrx("select * from ".TABLE."contact","C_content"),str_Replace("\"".$C_dir,"\"{@SL_安装目录}",$_POST["C_content"]))."',
C_map='".$_POST["C_map"]."'");
mysqli_query($conn, "update ".TABLE."wap set
W_phone='".$_POST["W_phone"]."',
W_email='".$_POST["W_email"]."'
");

if($C_html==1){
creat_index(langtonum());
creat_contact(langtonum());
creat_guestbook(langtonum());
}
echo "success|修改成功!";
lg("修改联系方式");
die();
}else{
echo "error|请填全信息！";
die();
}
break;

case "content_add":

$C_title=$_POST["C_title"];
$C_content=$_POST["C_content"];
$C_type=$_POST["C_type"];
$C_bz=$_POST["C_bz"];
$C_fid=$_POST["C_fid"];
$C_order=$_POST["C_order"];
$C_required=$_POST["C_required"];
if(!is_numeric($C_order) || $C_order==""){
$C_order=0;
}
if($C_title!=""){
$sql="Insert into ".TABLE."content(C_title,C_content,C_type,C_bz,C_fid,C_order,C_required) values('".lang_add("",$C_title)."','".lang_add("",$C_content)."','".$C_type."','".lang_add("",$C_bz)."',".$C_fid.",".$C_order.",".$C_required.")";
mysqli_query($conn,$sql);
echo "success|添加条目成功!";
lg("新增表单条目");
die();
}else{
echo "error|请填全信息！";
die();
}
break;

case "content_del":
$C_id=intval($_GET["C_id"]);
mysqli_query($conn,"update ".TABLE."content set C_del=1 where C_id=".$C_id);
mysqli_query($conn,"delete from ".TABLE."response where R_cid=".$C_id);
echo "success|放入回收站成功!|".$C_id;
lg("将表单条目放入回收站（ID：".$C_id."）");
die();
break;

case "content_delall":
$id=$_POST["id"];
if(count($id)>0){
$shu=0 ;
for ($i=0 ;$i< count($id);$i++ ){
mysqli_query($conn,"update ".TABLE."content set C_del=1 where C_id=".$id[$i]);
mysqli_query($conn,"delete from ".TABLE."response where R_cid=".$id[$i]);
$C_ids=$C_ids."c".$id[$i].",";
$shu=$shu+1;
}
if($shu>0){ ;
echo "success|成功删除".$shu."个条目|".$C_ids;
lg("批量删除表单条目（ID：".$C_ids."）");
die();
}else{ ;
echo "error|删除失败";
die();
} ;
}else{
echo "error|未选择要删除的内容";
die();
}
break;

case "content_edit":

$C_id=intval($_GET["C_id"]);
$C_title=$_POST["C_title"];
$C_content=$_POST["C_content"];
$C_type=$_POST["C_type"];
$C_bz=$_POST["C_bz"];
$C_fid=$_POST["C_fid"];
$C_order=$_POST["C_order"];
$C_required=$_POST["C_required"];
if(!is_numeric($C_order) || $C_order==""){
$C_order=0;
}
if($C_title!=""){

mysqli_query($conn,"
update ".TABLE."content set
C_title='".lang_add(getrx("select * from ".TABLE."content where C_id=".$C_id,"C_title"),$C_title)."',
C_content='".lang_add(getrx("select * from ".TABLE."content where C_id=".$C_id,"C_content"),$C_content)."',
C_type='".$C_type."',
C_bz='".lang_add(getrx("select * from ".TABLE."content where C_id=".$C_id,"C_bz"),$C_bz)."',
C_fid=".$C_fid.",
C_order=".$C_order.",
C_required=".$C_required."
where C_id=".$C_id
);

echo "success|修改条目成功!";
lg("修改表单条目（ID：".$C_id."）");
die();
}else{
echo "error|请填全信息！";
die();
}
break;

case "data_datadown":
ready(plug("x13","1"));
break;

case "data_dataup":
ready(plug("x13","2"));
break;

case "data_del":
if(strpos($_GET["id"],"..")!==false){
	die("error|路径错误");
}else{
	unlink("../backup/".$_GET["id"].".txt");
	lg("删除数据库备份文件（ID：".$_GET["id"]."）");
	die("success|删除成功!|".$_GET["id"]);
}
break;

case "data_move":
if(check_auth2("x14")){
    ready(plug("x14","1"));
}else{
    die("error");
}

break;

case "editadmin_":
$adminpath=$_POST["adminpath"];
$path=$_POST["path"];
if(validate($adminpath)){
Header("Location: ../index.php?action=editadminx&path=".$path."&adminpath=".$adminpath);
}else{
echo "error|仅限英文大小写及数字";
}
die();
break;

case "file_del":
if(strpos($_GET["name"],"..")!==false){
	die("error|路径错误");
}else{
	unlink("../media/".$_GET["name"]);
	lg("删除文件（ID：".$_GET["id"]."）");
	die("success|删除成功|".$_GET["id"]);
}
break;

case "file_delall":
$id=$_POST["id"];
if(count($id)>0){
$shu=0 ;
for($i=0 ;$i< count($id) ;$i++){
if(strpos($id[$i],"..")!==false){
	die("error|路径错误");
}else{
	unlink("../media/".splitx($id[$i],"__",1));
}

$C_ids=$C_ids.splitx($id[$i],"__",0).",";
$shu=$shu+1 ;
}
if($shu>0){ ;
echo "success|成功删除".$shu."个文件|".$C_ids;
lg("批量删除文件（ID：".$C_ids."）");
die();
}else{ ;
echo "error|删除失败";
die();
} ;
}else{
echo "error|未选择要删除的内容";
die();
}
break;

case "form_add":

$F_title=$_POST["F_title"];
$F_entitle=$_POST["F_entitle"];
$F_bz=$_POST["F_bz"];
$F_yz=intval($_POST["F_yz"]);
$F_yzm=intval($_POST["F_yzm"]);
$F_show=intval($_POST["F_show"]);
$F_ip=intval($_POST["F_ip"]);
$F_iptype=intval($_POST["F_iptype"]);
$F_day=intval($_POST["F_day"]);
$F_pic=$_POST["F_pic"];
$F_type=$_POST["F_type"];
$F_qsort=$_POST["F_qsort"];
$F_cq=$_POST["F_cq"];
$F_time=$_POST["F_time"];
$F_pagetitle=$_POST["F_pagetitle"];
$F_keywords=$_POST["F_keywords"];
$F_desription=$_POST["F_description"];
$F_limit=$_POST["F_limit"];
$U_sub=$_POST["U_sub"];
if(substr($F_pic,0,5)=="media" || substr($F_pic,0,6)=="images" || substr($F_pic,0,4)=="http"){
$F_pic=$F_pic;
}else{
$F_pic="media/".$F_pic;
}
if($F_title!=""){
mysqli_query($conn,"Insert into ".TABLE."form(F_title,F_entitle,F_bz,F_yz,F_yzm,F_show,F_pic,F_type,F_qsort,F_cq,F_pagetitle,F_keywords,F_description,F_ip,F_iptype,F_day,F_time,F_limit) values('".lang_add("",$F_title)."','".lang_add("",$F_entitle)."','".lang_add("",$F_bz)."',".$F_yz.",".$F_yzm.",".$F_show.",'".$F_pic."',".$F_type.",".$F_qsort.",".$F_cq.",'".lang_add("",$F_pagetitle)."','".lang_add("",$F_keywords)."','".lang_add("",$F_description)."',".$F_ip.",".$F_iptype.",".$F_day.",'".$F_time."',".$F_limit.")");

$sql="Select * from ".TABLE."form order by F_id desc limit 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
    $F_id=$row["F_id"];
}

a("form",$F_id,"template");

if($U_sub!="x"){
mysqli_query($conn,"insert into ".TABLE."menu(U_title,U_entitle,U_order,U_sub,U_ico,U_type,U_typeid) values('".$F_title."','".$F_entitle."',99,".$U_sub.",'bars','form',".$F_id.")");
}
if($C_html==1){
creat_index(langtonum());
creat_form(langtonum(),$F_id);
}
echo "success|添加成功!";
lg("新增表单");
die();
}else{
echo "error|请填全信息！";
die();
}
break;

case "form_addmenu":

$sql="select * from ".TABLE."menu where U_sub=0 order by U_order desc limit 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$U_order=$row["U_order"];
}
$sql="select * from ".TABLE."form where F_id=".intval($_GET["F_id"]);
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$F_title=$row["F_title"];
$F_entitle=$row["F_entitle"];
}
mysqli_query($conn,"insert into ".TABLE."menu(U_title,U_entitle,U_order,U_sub,U_ico,U_type,U_typeid) values('".$F_title."','".$F_entitle."',".($U_order+1).",0,'bars','form',".$_REQUEST["F_id"].")");
echo "success|添加成功!";
lg("新增表单到主菜单（ID：".$_GET["F_id"]."）");
die();
break;

case "form_creat":

$id=intval($_GET["F_id"]);
$sql="select * from ".TABLE."form where F_del=0 and F_id=".$id." order by F_id desc";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
	$F_title=$row["F_title"];
}
$tjlist=$tjlist."<table class='table m-b-none table-hover'><thead><tr><th>编号</th><th>会员</th>";
$sql="select * from ".TABLE."content where C_del=0 and C_Fid=".$id." order by C_order";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
		$tjlist=$tjlist. "<th>".lang($row["C_title"])."</th>";
	}
}
$tjlist=$tjlist. "<th>提交时间</th></tr></thead><tbody>";
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
			$R_content="<a href='".gethttp().$D_domain."/".$row1["R_content"]."' target='_blank'><img src='".gethttp().$D_domain."/".$row1["R_content"]."' height='30' width='30'> 点击查看大图</a>";
		}else{
			if(substr($row1["R_content"],-3)=="doc" || substr($row1["R_content"],-4)=="docx" || substr($row1["R_content"],-3)=="xls" || substr($row1["R_content"],-4)=="xlsx" || substr($row1["R_content"],-3)=="ppt" || substr($row1["R_content"],-4)=="pptx" || substr($row1["R_content"],-3)=="pdf" || substr($row1["R_content"],-3)=="ppf" || substr($row1["R_content"],-3)=="rar" || substr($row1["R_content"],-3)=="swf"){
				$R_content="<a href='".gethttp().$D_domain."/".$row1["R_content"]."?1' target='_blank'><img src='".gethttp().$D_domain."/".$C_admin."/img/file.gif'> 点击查看附件</a>";
			}else{
				$R_content=$row1["R_content"];
			}
		}
        if(is_numeric($R_content)){
            $R_content="'".$R_content;
        }
		$line=$line. "<td style='word-wrap:break-word;'>".$R_content."</td>";
	}
}

$line=$line. "<td>".$row["R_time"]."</td></tr>|";
}
}
$line=@MoveR($line);
$tjlist=$tjlist. str_Replace("|","",$line);
$tjlist=$tjlist. "</tbody></table>";


header("Content-type:application/vnd.ms-excel");    
header("Content-Disposition:filename=".lang($F_title).".xls");    
$tjlist=iconv('UTF-8',"GB2312//IGNORE",$tjlist);    
exit($tjlist);
break;

case "form_del":
$F_id=intval($_GET["F_id"]);
$sql="select * from ".TABLE."content where C_fid=".$F_id;
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$fid=$fid."c".$row["C_id"].",";
}
}
mysqli_query($conn,"update ".TABLE."form set F_del=1 where F_id=".$F_id);
mysqli_query($conn,"update ".TABLE."menu set U_del=1 where U_type='form' and U_typeid=".$F_id);
mysqli_query($conn,"update ".TABLE."content set C_del=1 where C_fid=".$F_id);
echo "success|放入回收站成功!|f".$F_id.",".$fid;
lg("将表单放入回收站（ID：".$F_id."）");
die();
break;

case "form_edit":

if(strpos($_COOKIE["formauth"],"all")!==false){
$auth_info="";
}else{
$formauth=explode(",",$_COOKIE["formauth"]);
for ($i=0 ;$i< count($formauth)-1;$i++){
$tj=$tj."or F_id=".$formauth[$i]." ";
}
if($tj==""){
$auth_info=" and F_id<0";
}else{
$tj= substr($tj,-(strlen($tj)-3));
$auth_info=" and (".$tj.")";
}
}
$F_id=intval($_GET["F_id"]);
$F_title=$_POST["F_title"];
$F_entitle=$_POST["F_entitle"];
$F_bz=$_POST["F_bz"];
$F_yz=intval($_POST["F_yz"]);
$F_yzm=intval($_POST["F_yzm"]);
$F_show=intval($_POST["F_show"]);
$F_ip=intval($_POST["F_ip"]);
$F_iptype=intval($_POST["F_iptype"]);
$F_day=intval($_POST["F_day"]);
$F_pic=$_POST["F_pic"];
$F_type=$_POST["F_type"];
$F_qsort=$_POST["F_qsort"];
$F_cq=$_POST["F_cq"];
$F_time=$_POST["F_time"];
$F_pagetitle=$_POST["F_pagetitle"];
$F_description=$_POST["F_description"];
$F_keywords=$_POST["F_keywords"];
$F_limit=$_POST["F_limit"];
if(substr($F_pic,0,5)=="media" || substr($F_pic,0,6)=="images" || substr($F_pic,0,4)=="http"){
$F_pic=$F_pic;
}else{
$F_pic="media/".$F_pic;
}
if($F_title!=""){

mysqli_query($conn, "update ".TABLE."form set
F_title='".lang_add(getrx("select * from ".TABLE."form where F_id=".$F_id,"F_title"),$F_title)."',
F_entitle='".lang_add(getrx("select * from ".TABLE."form where F_id=".$F_id,"F_entitle"),$F_entitle)."',
F_bz='".lang_add(getrx("select * from ".TABLE."form where F_id=".$F_id,"F_bz"),$F_bz)."',
F_yz=".$F_yz.",
F_ip=".$F_ip.",
F_iptype=".$F_iptype.",
F_day=".$F_day.",
F_yzm=".$F_yzm.",
F_show=".$F_show.",
F_pic='".$F_pic."',
F_type=".$F_type.",
F_qsort=".$F_qsort.",
F_cq=".$F_cq.",
F_time='".$F_time."',
F_limit=".$F_limit.",
F_pagetitle='".lang_add(getrx("select * from ".TABLE."form where F_id=".$F_id,"F_pagetitle"),$F_pagetitle)."',
F_keywords='".lang_add(getrx("select * from ".TABLE."form where F_id=".$F_id,"F_keywords"),$F_keywords)."',
F_description='".lang_add(getrx("select * from ".TABLE."form where F_id=".$F_id,"F_description"),$F_description)."'
where F_id=".$F_id
);

if($C_html==1){
creat_index(langtonum());
creat_form(langtonum(),$F_id);
}

a("form", $F_id,"template");

echo "success|修改成功！";
lg("修改表单（ID：".$F_id."）");
die();
}else{
echo "error|请填全信息！";
die();
}
break;

case "form_save":

foreach ($_POST as $x=>$value) {
if(splitx($x,"_",0)=="Ctitle"){
mysqli_query($conn,"update ".TABLE."content set C_title='".lang_add(getrx("select * from ".TABLE."content where C_id=".splitx($x,"_",1),"C_title"),$_POST[$x])."' where C_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="Ftitle"){
mysqli_query($conn,"update ".TABLE."form set F_title='".lang_add(getrx("select * from ".TABLE."form where F_id=".splitx($x,"_",1),"F_title"),$_POST[$x])."' where F_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="Fentitle"){
mysqli_query($conn,"update ".TABLE."form set F_entitle='".lang_add(getrx("select * from ".TABLE."form where F_id=".splitx($x,"_",1),"F_entitle"),$_POST[$x])."' where F_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="order"){
mysqli_query($conn,"update ".TABLE."content set C_order=".$_POST[$x]." where C_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="module"){
mysqli_query($conn,"update ".TABLE."form set F_module='".$_POST[$x]."' where F_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="pic"){
if(substr($_POST[$x],0,5)=="media" || substr($_POST[$x],0,6)=="images" || substr($_POST[$x],0,4)=="http"){
$pic=$_POST[$x];
}else{
$pic="media/".$_POST[$x];
}
mysqli_query($conn,"update ".TABLE."form set F_pic='".$pic."' where F_id=".splitx($x,"_",1));
}
} ;
if($C_html==1){
creat_index(langtonum());
creat_form(langtonum(),"");
}
echo "success|修改成功";
lg("保存表单（在列表页）");
die();
break;

case "getorder_":

$idx=$_GET["idx"];
$typex=$_GET["typex"];
switch($typex){
case "menu":
$sql="select count(U_id) as U_count from ".TABLE."menu where U_sub=".$idx;

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$count=$row["U_count"];
break;
case "nsort":
$sql="select count(S_id) as S_count from ".TABLE."nsort where S_sub=".$idx;

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$count=$row["S_count"];
break;
case "psort":
$sql="select count(S_id) as S_count from ".TABLE."psort where S_sub=".$idx;

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$count=$row["S_count"];
}
echo $count+1;
die();
break;

case "guestbook_del":

mysqli_query($conn,"delete from ".TABLE."guestbook where G_id=".intval($_GET["G_id"]));
lg("删除留言（ID：".$_GET["G_id"]."）");
echo "success|删除成功!|".$_GET["G_id"];
die();
break;

case "guestbook_delall":

$id=$_POST["id"];

if(count($id)>0){
$shu=0 ;
for( $i=0;$i<  count($id);$i++ ){
mysqli_query($conn,"delete from ".TABLE."guestbook where G_id=".$id[$i]);
$shu=$shu+1 ;
$ids=$ids.$id[$i].",";
}
$ids= substr($ids,0,strlen($ids)-1);
if($shu>0){
echo "success|成功删除".$shu."条留言|".$ids;
lg("批量删除留言（ID：".$ids."）");
die();
}else{ 
echo "error|删除失败";
die();
} 
}else{
echo "error|未选择要删除的内容";
die();
}
break;

case "guestbook_reply":

$G_id=intval($_GET["G_id"]);
$G_sh=intval($_POST["G_sh"]);
$G_reply=$_POST["G_reply"];
$G_name=$_POST["G_name"];
$G_title=$_POST["G_title"];
$G_phone=$_POST["G_phone"];
$G_msg=$_POST["G_msg"];
$G_email=$_POST["G_email"];
if($G_reply!=""){

mysqli_query($conn, "update ".TABLE."guestbook set
G_name='".$G_name."',
G_title='".$G_title."',
G_phone='".$G_phone."',
G_msg='".$G_msg."',
G_email='".$G_email."',
G_sh=".$G_sh.",
G_reply='".$G_reply."'
where G_id=".$G_id
);

sendmail("您的留言有回复，来自".$C_webtitle,"原留言：".$G_msg."<br>回复内容：".$G_reply,$G_email);
echo "success|回复留言成功!";
lg("回复留言（ID：".$G_id."）");
die();
}else{
echo "error|请填全信息！";
die();
}
break;

case "invoice_sh1":

mysqli_query($conn,"update ".TABLE."invoice set I_sh=1 where I_id=".intval($_GET["I_id"]));
lg("发票审核为已通过（ID：".$_GET["I_id"]."）");
echo "success|发票审核状态已修改为“已通过”!";
die();
break;

case "invoice_sh2":

mysqli_query($conn,"update ".TABLE."invoice set I_sh=2 where I_id=".intval($_GET["I_id"]));
lg("发票审核为未通过（ID：".$_GET["I_id"]."）");
echo "success|发票审核状态已修改为“未通过”!";
die();
break;

case "like_":

$type=substr($_GET["id"],0,1);
$typeid=intval(substr($_GET["id"],1));

switch($type){
    case "t":
    $like_num=getrx("select * from ".TABLE."text where T_id=".$typeid,"T_like");
    break;
    case "n":
    $like_num=getrx("select * from ".TABLE."news where N_id=".$typeid,"N_like");
    break;
    case "p":
    $like_num=getrx("select * from ".TABLE."product where P_id=".$typeid,"P_like");
    break;
}
break;

case "link_add":

$L_title=$_POST["L_title"];
$L_pic=$_POST["L_pic"];
$L_url=$_POST["L_url"];
$L_sort=$_POST["L_sort"];
if(substr($L_pic,0,5)=="media" || substr($L_pic,0,6)=="images" || substr($L_pic,0,4)=="http"){
$L_pic=$L_pic;
}else{
$L_pic="media/".$L_pic;
}
if($L_title!="" && $L_pic!="" && $L_url!=""){
$sql="insert into ".TABLE."link(L_title,L_pic,L_url,L_sort) values('".lang_add("",$L_title)."','".$L_pic."','".$L_url."',".$L_sort.")";
mysqli_query($conn,$sql);
echo "success|添加成功!";
lg("新增友链");
die();
}else{
echo "error|请填全信息!";
die();
}
break;

case "link_del":
mysqli_query($conn,"update ".TABLE."link set L_del=1 where L_id=".intval($_GET["L_id"]));
echo "success|放入回收站成功!|".$_GET["L_id"];
lg("将友链放入回收站（ID：".$_GET["L_id"]."）");
die();
break;

case "link_delall":
$id=$_POST["id"];
if(count($id)>0){
$shu=0 ;
for ($i=0;$i< count($id) ;$i++){
mysqli_query($conn,"update ".TABLE."link set L_del=1 where L_id=".$id[$i]);
$shu=$shu+1 ;
$ids=$ids.$id[$i].",";
        }
        $ids= substr($ids,0,strlen($ids)-1);
if($shu>0){ ;
echo "success|成功删除".$shu."个友链|".$ids;
lg("批量删除友链（ID：".$ids."）");
die();
}else{ ;
echo "error|删除失败";
die();
} ;
}else{
echo "error|未选择要删除的内容";
die();
}
break;

case "link_edit":

$L_id=intval($_GET["L_id"]);
$L_title=$_POST["L_title"];
$L_pic=$_POST["L_pic"];
$L_url=$_POST["L_url"];
$L_sort=$_POST["L_sort"];
if(substr($L_pic,0,5)=="media" || substr($L_pic,0,6)=="images" || substr($L_pic,0,4)=="http"){
$L_pic=$L_pic;
}else{
$L_pic="media/".$L_pic;
}
if($L_title!="" && $L_pic!="" && $L_url!=""){
mysqli_query($conn,"update ".TABLE."link set L_title='".lang_add(getrx("select * from ".TABLE."link where L_id=".$L_id,"L_title"),$L_title)."',L_pic='".$L_pic."',L_url='".$L_url."',L_sort=".$L_sort." where L_id=".$L_id);
echo "success|修改成功!";
lg("编辑友链（ID：".$L_id."）");
die();
}else{
echo "error|请填全信息!";
die();
}
break;

case "link_save":

foreach ($_POST as $x=>$value) {
if(splitx($x,"_",0)=="title"){
mysqli_query($conn,"update ".TABLE."link set L_title='".lang_add(getrx("select * from ".TABLE."link where L_id=".splitx($x,"_",1),"L_title"),$_POST[$x])."' where L_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="sort"){
mysqli_query($conn,"update ".TABLE."link set L_sort=".$_POST[$x]." where L_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="url"){
mysqli_query($conn,"update ".TABLE."link set L_url='".$_POST[$x]."' where L_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="order"){
mysqli_query($conn,"update ".TABLE."link set L_order=".$_POST[$x]." where L_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="pic"){
if(substr($_POST[$x],0,5)=="media" || substr($_POST[$x],0,6)=="images" || substr($_POST[$x],0,4)=="http"){
$pic=$_POST[$x];
}else{
$pic="media/".$_POST[$x];
}
mysqli_query($conn,"update ".TABLE."link set L_pic='".$pic."' where L_id=".splitx($x,"_",1));
}
} 
echo "success|修改成功!";
lg("保存友链（在列表页）");
die();
break;

case "log_clear":

if($_COOKIE["A_type"]==1){
mysqli_query($conn,"delete from ".TABLE."log");
lg("清空日志");
echo "success|清空日志成功!";
}else{
echo "error|仅顶级管理员可删除日志!";
}
die();
break;

case "log_del":

if($_COOKIE["A_type"]==1){
mysqli_query($conn,"delete from ".TABLE."log where L_id=".intval($_GET["L_id"]));
lg("删除日志（ID：".$_GET["L_id"]."）");
echo "success|删除成功!|".$_GET["L_id"];
}else{
echo "error|仅顶级管理员可删除日志!";
}
die();
break;

case "log_delall":

if($_COOKIE["A_type"]==1){
$id=$_POST["id"];

if(count($id)>0){
$shu=0;
for ($i=0 ;$i< count($id);$i++){
mysqli_query($conn,"delete from ".TABLE."log where L_id=".$id[$i]);
$shu=$shu+1 ;
$ids=$ids.$id[$i].",";
        }
        $ids= substr($ids,0,strlen($ids)-1);
if($shu>0){ ;
echo "success|成功删除".$shu."条日志|".$ids;
lg("批量删除日志（ID：".$ids."）");
}else{ ;
echo "error|删除失败";
} ;
}else{
echo "error|未选择要删除的内容";
}
}else{
echo "error|仅顶级管理员可删除日志!";
}
die();
break;

case "lsort_add":

$S_title=$_POST["S_title"];
$S_order=$_POST["S_order"];
if(!is_numeric($S_order) || $S_order==""){
$S_order=0;
}
if($S_title!=""){
mysqli_query($conn,"insert into ".TABLE."lsort(S_title,S_order) values('".lang_add("",$S_title)."',".$S_order.")");
$sql="Select * from ".TABLE."lsort order by S_id desc limit 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$S_id=$row["S_id"];
}
echo "success|添加成功!";
lg("新增友链分类");
die();
}else{
echo "error|请填全信息!";
die();
}
break;

case "lsort_del":

mysqli_query($conn,"delete from ".TABLE."lsort where S_id=".intval($_GET["S_id"]));
mysqli_query($conn,"delete from ".TABLE."link where L_sort=".intval($_GET["S_id"]));
echo "success|删除成功!|".$_GET["S_id"];
lg("删除友链分类（ID：".$_GET["S_id"]."）");
die();
break;

case "lsort_delall":

$id=$_POST["id"];
if(count($id)>0){
$shu=0;
for ($i=0;$i< count($id);$i++ ){
mysqli_query($conn,"delete from ".TABLE."lsort where S_id=".$id[$i]);
mysqli_query($conn,"delete from ".TABLE."link where L_sort=".$id[$i]);
$shu=$shu+1 ;
$ids=$ids.$id[$i].",";
        }
        $ids= substr($ids,0,strlen($ids)-1);
if($shu>0){ ;
echo "success|成功删除".$shu."个分类|".$ids;
lg("批量删除友链分类（ID：".$ids."）");
die();
}else{ ;
echo "error|删除失败";
die();
} ;
}else{
echo "error|未选择要删除的内容";
die();
}
break;

case "lsort_edit":

$S_id=intval($_GET["S_id"]);
$S_title=$_POST["S_title"];
$S_order=$_POST["S_order"];
if(!is_numeric($S_order) || $S_order==""){
$S_order=0;
}
if($S_title!=""){

mysqli_query($conn,"update ".TABLE."lsort set S_title='".lang_add(getrx("select * from ".TABLE."lsort where S_id=".$S_id,"S_title"),$S_title)."',S_order=".$S_order." where S_id=".$S_id);
echo "success|修改成功!";
lg("修改友链分类（ID：".$S_id."）");
die();
}else{
echo "error|请填全信息!";
die();
}
break;

case "lsort_save":

foreach ($_POST as $x=>$value) {
if(splitx($x,"_",0)=="title"){
mysqli_query($conn,"update ".TABLE."lsort set S_title='".lang_add(getrx("select * from ".TABLE."lsort where S_id=".splitx($x,"_",1),"S_title"),$_POST[$x])."' where S_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="order"){
mysqli_query($conn,"update ".TABLE."lsort set S_order=".$_POST[$x]." where S_id=".splitx($x,"_",1));
}
} 
echo "success|修改成功!";
lg("保存友链分类（在列表页）");
die();
break;

case "lv_add":

$L_discount=$_POST["L_discount"];
$L_title=$_POST["L_title"];
$L_fen=$_POST["L_fen"];
$L_order=$_POST["L_order"];
if($L_title!=""){
$sql="Insert into ".TABLE."lv(L_discount,L_title,L_fen,L_order) values(".$L_discount.",'".lang_add("",$L_title)."',".$L_fen.",".$L_order.")";
mysqli_query($conn,$sql);
echo "success|添加成功!";
lg("新增会员等级");
die();
}else{
echo "error|请填全信息！";
die();
}
break;

case "lv_del":

$sql="Select * from ".TABLE."member where M_lv=".intval($_GET["L_id"]);
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
echo "error|该等级下尚有会员帐号，无法删除该等级!";
die();
}else{
if(getrx("select * from ".TABLE."lv where L_id=".intval($_GET["L_id"]),"L_fen")==0){
echo "error|等级列表中必须保留一个所需积分为0的等级!";
die();
}else{
mysqli_query($conn,"delete from ".TABLE."lv where L_id=".intval($_GET["L_id"]));
echo "success|删除成功!|".$_GET["L_id"];
lg("删除会员等级（ID：".$_GET["L_id"]."）");
die();
}
}
break;

case "lv_delall":

$id=$_POST["id"];
if(count($id)>0){
$shu=0 ;
for ($i=0 ;$i< count($id) ;$i++){
mysqli_query($conn,"delete from ".TABLE."lv where L_id=".$id[$i]);
$shu=$shu+1 ;
$ids=$ids.$id[$i].",";
        }
        $ids= substr($ids,0,strlen($ids)-1);
if($shu>0){ ;
echo "success|成功删除".$shu."个等级|".$ids;
lg("批量删除会员等级（ID：".$ids."）");
die();
}else{ 
echo "error|删除失败";
die();
} 
}else{
echo "error|未选择要删除的内容";
die();
}
break;

case "lv_edit":

$L_id=intval($_GET["L_id"]);
$L_discount=$_POST["L_discount"];
$L_title=$_POST["L_title"];
$L_fen=$_POST["L_fen"];
$L_order=$_POST["L_order"];
if($L_title!=""){
mysqli_query($conn,"
update ".TABLE."lv set
L_title='".lang_add(getrx("select * from ".TABLE."lv where L_id=".$id,"L_title"),$L_title)."',
L_discount='".$L_discount."',
L_fen='".$L_fen."',
L_order='".$L_order."',
where L_id=".$L_id
    );
echo "success|修改成功!";
lg("修改会员等级（ID：".$L_id."）");
die();
}else{
echo "error|请填全信息！";
die();
}
break;

case "lv_save":

foreach ($_POST as $x=>$value) {
if(splitx($x,"_",0)=="title"){
mysqli_query($conn,"update ".TABLE."lv set L_title='".lang_add(getrx("select * from ".TABLE."lv where L_id=".splitx($x,"_",1),"L_title"),$_POST[$x])."' where L_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="order"){
mysqli_query($conn,"update ".TABLE."lv set L_order=".$_POST[$x]." where L_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="fen"){
mysqli_query($conn,"update ".TABLE."lv set L_fen=".$_POST[$x]." where L_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="discount"){
mysqli_query($conn,"update ".TABLE."lv set L_discount=".$_POST[$x]." where L_id=".splitx($x,"_",1));
}
} ;
echo "success|修改成功!";
lg("保存会员等级（在列表页）");
die();
break;

case "lv_save2":
mysqli_query($conn,"update ".TABLE."config set
C_1yuan=".intval($_POST["C_1yuan"]).",
C_1yuan2=".intval($_POST["C_1yuan2"]).",
C_sign=".intval($_POST["C_sign"]).",
C_Invitation=".intval($_POST["C_invitation"]).",
C_read=".intval($_POST["C_read"]).",
C_data=".intval($_POST["C_data"]));
echo "success|修改成功!";
lg("修改营销设置");
die();
break;

case "lv_save3":

foreach ($_POST as $x=>$value) {
if(splitx($x,"_",0)=="xoindex"){
if($_POST[$x]!="" && $_POST[$x."x"]!=""){
$aa=$aa.$_POST[$x]."@".$_POST[$x."x"].",";
}
}
}
$aa=substr($aa,0,strlen($aa)-1);
mysqli_query($conn,"update ".TABLE."config set C_gift='".$aa."'");
$C_gifton=$_POST["C_gifton"];
if($C_gifton!=1){
$C_gifton=0;
}
mysqli_query($conn,"update ".TABLE."config set C_gifton=".$C_gifton);
echo "success|修改成功!";
die();
break;

case "lv_save4":

mysqli_query($conn,"
update ".TABLE."config set
C_tofen='".$_POST["C_tofen"]."',
C_tomoney='".$_POST["C_tomoney"]."',
C_tx='".$_POST["C_tx"]."',
C_tofen_rate='".$_POST["C_tofen_rate"]."',
C_tomoney_rate='".$_POST["C_tomoney_rate"]."',
C_tx_rate='".$_POST["C_tx_rate"]."'
    ");
echo "success|修改成功!";
lg("修改营销设置");
die();
break;

case "lv_sh1":

mysqli_query($conn,"update ".TABLE."list set L_sh=0 where L_id=".intval($_GET["L_id"]));
lg("审核交易明细为已通过（ID：".$_GET["L_id"]."）");
echo "success|状态已修改为“已通过”!";
die();
break;

case "lv_sh2":

mysqli_query($conn,"update ".TABLE."list set L_sh=2 where L_id=".intval($_GET["L_id"]));
lg("审核交易明细为未通过（ID：".$_GET["L_id"]."）");
echo "success|状态已修改为“未通过”!";
die();
break;

case "member_add":
$M_id=intval($_GET["M_id"]);
$M_login=$_POST["M_login"];
$M_pwd=$_POST["M_pwd"];
$M_email=$_POST["M_email"];
$M_qq=$_POST["M_qq"];
$M_mobile=$_POST["M_mobile"];
$M_add=$_POST["M_add"];
$M_name=$_POST["M_name"];
$M_code=$_POST["M_code"];
$M_fen=$_POST["M_fen"];
$M_money=$_POST["M_money"];
$M_info=$_POST["M_info"];
$M_type=intval($_POST["M_type"]);

if($M_type==0){
	die("error|请设置会员类型！");
}

if($M_login!=""){
mysqli_query($conn,"insert into ".TABLE."member(
M_login,
M_pwd,
M_type,
M_email,
M_qq,
M_mobile,
M_add,
M_name,
M_code,
M_fen,
M_money,
M_info,
M_pic) values(
'".$M_login."',
'".strtoupper(md5($M_pwd))."',
".$M_type.",
'".$M_email."',
'".$M_qq."',
'".$M_mobile."',
'".$M_add."',
'".$M_name."',
'".$M_code."',
".$M_fen.",
".$M_money.",
'".$M_info."',
'member.jpg'
)");

$sql="Select * from ".TABLE."member order by M_id desc limit 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$M_id=$row["M_id"];
}
uplevel($M_id);
echo "success|新增会员成功!";
lg("新增会员信息（会员ID：".$M_id."）");
die();
}else{
echo "error|请填全信息！";
die();
}
break;

case "member_del":
mysqli_query($conn,"update ".TABLE."member set M_del=1 where M_id=".intval($_GET["M_id"]));
mysqli_query($conn,"delete from ".TABLE."orders where O_member=".intval($_GET["M_id"]));
mysqli_query($conn,"delete from ".TABLE."response where R_member=".intval($_GET["M_id"]));
echo "success|放入回收站成功!|".$_GET["M_id"];
lg("将会员放入回收站（ID：".$_GET["M_id"]."）");
die();
break;

case "member_delall":
$id=$_POST["id"];
if(count($id)>0){
$shu=0 ;
for ($i=0;$i< count($id);$i++ ){
mysqli_query($conn,"update ".TABLE."member set M_del=1 where M_id=".$id[$i]);
$shu=$shu+1 ;
$ids=$ids.$id[$i].",";
}
$ids= substr($ids,0,strlen($ids)-1);
if($shu>0){ ;
echo "success|成功删除".$shu."个会员|".$ids;
lg("批量删除会员（ID：".$ids."）");
die();
}else{ 
echo "error|删除失败";
die();
} 
}else{
echo "error|未选择要删除的内容";
die();
}
break;

case "member_edit":

$M_id=intval($_GET["M_id"]);
$M_login=$_POST["M_login"];
$M_pwd=$_POST["M_pwd"];
$M_email=$_POST["M_email"];
$M_qq=$_POST["M_qq"];
$M_mobile=$_POST["M_mobile"];
$M_add=$_POST["M_add"];
$M_name=$_POST["M_name"];
$M_code=$_POST["M_code"];
$M_fen=$_POST["M_fen"];
$M_money=$_POST["M_money"];
$M_info=$_POST["M_info"];
$M_type=$_POST["M_type"];
if($M_login!=""){
mysqli_query($conn,"update ".TABLE."member set 
M_login='".$M_login."',
M_email='".$M_email."',
M_qq='".$M_qq."',
M_mobile='".$M_mobile."',
M_add='".$M_add."',
M_name='".$M_name."',
M_code='".$M_code."',
M_fen=".$M_fen.",
M_money=".$M_money.",
M_info='".$M_info."',
M_type='".$M_type."'
where M_id=".$M_id
);

if($M_pwd!=""){
    mysqli_query($conn,"update ".TABLE."member set M_pwd='".strtoupper(md5($M_pwd))."' where M_id=".$M_id);
}

uplevel($M_id);
echo "success|修改会员成功!";
lg("修改会员信息（ID：".$M_id."）");
die();
}else{
echo "error|请填全信息！";
die();
}
break;

case "menu_add":

$U_title=$_POST["U_title"];
$U_entitle=$_POST["U_entitle"];
$U_sub=$_POST["U_sub"];
$U_ico=$_POST["U_ico"];
$U_color=$_POST["U_color"];
$U_order=$_POST["U_order"];
$U_path=$_POST["U_path"];
$U_hide=intval($_POST["U_hide"]);
$U_url=$_POST["U_url"];
$U_target=$_POST["U_target"];
$U_bg=$_POST["U_bg"];
$U_template=$_POST["U_template"];
$U_wap=$_POST["U_wap"];

if(!is_Numeric($U_order) || $U_order==""){
$U_order=0;
}
if($U_url!="" && $U_target!=""){
    $U_link=$U_url."|".$U_target;
}else{
    $U_link="";
}
if($U_title!="" && $U_order!="" && strpos($U_path,"/")!==false){
mysqli_query($conn, "insert into ".TABLE."menu(U_title,U_entitle,U_order,U_sub,U_type,U_typeid,U_hide,U_url,U_ico,U_color,U_bg,U_template,U_wap) values('".lang_add("",$U_title)."','".lang_add("",$U_entitle)."',".$U_order.",".$U_sub.",'".splitx($U_path,"/",0)."',".splitx($U_path,"/",1).",".$U_hide.",'".$U_link."','".$U_ico."','".$U_color."','".$U_bg."','".$U_template."','".$U_wap."')");
echo "success|添加菜单成功!";
lg("新增菜单");
die();
}else{
echo "error|请填全信息！";
die();
}
break;

case "menu_del":
$sql="select * from ".TABLE."menu where U_sub=".intval($_GET["U_id"]);
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$uid=$uid.$row["U_id"].",";
}
}
mysqli_query($conn, "update ".TABLE."menu set U_del=1 where U_id=".intval($_GET["U_id"]));
mysqli_query($conn, "update ".TABLE."menu set U_del=1 where U_sub=".intval($_GET["U_id"]));
echo "success|放入回收站成功!|".$_GET["U_id"].",".$uid;
lg("将菜单放入回收站（ID：".$_GET["U_id"]."）");
die();
break;

case "menu_delall":

$id=$_POST["id"];
if(count($id)>0){
$shu=0 ;
for ($i=0;$i< count($id) ;$i++){
$sql="select * from ".TABLE."menu where U_sub=".$id[$i];
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$uid=$uid.$row["U_id"].",";
}
}
mysqli_query($conn,"update ".TABLE."menu set U_del=1 where U_id=".$id[$i]);
mysqli_query($conn,"update ".TABLE."menu set U_del=1 where U_sub=".$id[$i]);
$shu=$shu+1 ;
$ids=$ids.$id[$i].",";
        }
        $ids= substr($ids,0,strlen($ids)-1);
if($shu>0){ 
echo "success|成功删除".$shu."个菜单|".$ids.",".$uid;
lg("批量删除菜单（ID：".$ids."）");
die();
}else{ 
echo "error|删除失败";
die();
} 
}else{
echo "error|未选择要删除的内容";
die();
}
break;

case "menu_edit":

$U_id=intval($_GET["U_id"]);
$U_title=$_POST["U_title"];
$U_entitle=$_POST["U_entitle"];
$U_sub=$_POST["U_sub"];
$U_ico=$_POST["U_ico"];
$U_color=$_POST["U_color"];
$U_order=$_POST["U_order"];
$U_path=$_POST["U_path"];
$U_hide=intval($_POST["U_hide"]);
$U_url=$_POST["U_url"];
$U_target=$_POST["U_target"];
$U_bg=$_POST["U_bg"];
$U_template=$_POST["U_template"];
$U_wap=$_POST["U_wap"];
if(!is_numeric($U_order) || $U_order==""){
$U_order=0;
}
if($U_url!="" && $U_target!=""){
    $U_link=$U_url."|".$U_target;
}else{
    $U_link="";
}
$sql="select * from ".TABLE."menu where U_id=".$U_id;
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$U_type=$row["U_type"];
$U_typeid=$row["U_typeid"];
if($U_title!="" && $U_order!="" && strpos($U_path,"/")!==false){
mysqli_query($conn, "update ".TABLE."menu set
U_title='".lang_add(getrx("select * from ".TABLE."menu where U_id=".$U_id,"U_title"),$U_title)."',
U_entitle='".lang_add(getrx("select * from ".TABLE."menu where U_id=".$U_id,"U_entitle"),$U_entitle)."',
U_order=".$U_order.",
U_sub=".$U_sub.",
U_ico='".$U_ico."',
U_color='".$U_color."',
U_type='".splitx($U_path,"/",0)."',
U_typeid=".splitx($U_path,"/",1).",
U_hide=".$U_hide.",
U_url='".$U_link."',
U_bg='".$U_bg."',
U_template='".$U_template."',
U_wap='".$U_wap."'
where U_id=".$U_id
);
echo "success|修改菜单成功！";
lg("修改菜单（ID：".$U_id."）");
die();
}else{
echo "error|请填全信息！";
die();
}
break;

case "menu_save":

foreach ($_POST as $x=>$value) {
if(splitx($x,"_",0)=="order"){
mysqli_query($conn, "update ".TABLE."menu set U_order=".$_POST[$x]." where U_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="title"){
mysqli_query($conn, "update ".TABLE."menu set U_title='".lang_add(getrx("select * from ".TABLE."menu where U_id=".splitx($x,"_",1),"U_title"),$_POST[$x])."' where U_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="entitle"){
mysqli_query($conn, "update ".TABLE."menu set U_entitle='".lang_add(getrx("select * from ".TABLE."menu where U_id=".splitx($x,"_",1),"U_entitle"),$_POST[$x])."' where U_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="hide"){
mysqli_query($conn, "update ".TABLE."menu set U_hide=".$_POST[$x][0]." where U_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="sub"){
mysqli_query($conn, "update ".TABLE."menu set U_sub=".$_POST[$x]." where U_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="url"){
mysqli_query($conn, "update ".TABLE."menu set U_url='".$_POST[$x]."' where U_id=".splitx($x,"_",1));
}
} 
echo "success|修改成功！";
lg("保存菜单（在列表页）");
die();
break;

case "m_config2_edit":

if($_SESSION["i"]==0){
$xmls=file_get_contents("../wap/".$C_wap."/config.xml");
}
if($_SESSION["i"]==1 && is_file("../wap/".$C_wap."/config_e.xml")){
$xmls=file_get_contents("../wap/".$C_wap."/config_e.xml");
}else{
$xmls=file_get_contents("../wap/".$C_wap."/config.xml");
}

$xml =simplexml_load_string($xmls);

$config_data="<?xml version='1.0' encoding='utf-8'?><xml>".PHP_EOL;
for($i=0;$i<count($xml->page);$i++){

$config_data=$config_data."<page title='".$xml->page[$i]["title"]."'>".PHP_EOL;
    for($j=0;$j<count($xml->page[$i]->tag);$j++){

        $config_data=$config_data."<tag><title>".$xml->page[$i]->tag[$j]->title."</title><content><![CDATA[".stripslashes($_POST["C_".$i.$j])."]]></content><en><![CDATA[".stripslashes($_POST["E_".$i.$j])."]]></en><type>".$xml->page[$i]->tag[$j]->type."</type><url><![CDATA[".stripslashes($_POST["U_".$i.$j])."]]></url></tag>".PHP_EOL;
    }
    $config_data=$config_data."</page>".PHP_EOL;
}
$config_data=$config_data."</xml>";

if($_SESSION["i"]==0){
file_put_contents("../wap/".$C_wap."/config.xml",$config_data);
}
if($_SESSION["i"]==1 && is_file("../wap/".$C_wap."/config_e.xml")){
file_put_contents("../wap/".$C_wap."/config_e.xml",$config_data);
}else{
file_put_contents("../wap/".$C_wap."/config.xml",$config_data);
}
echo "success|保存成功!";
lg("修改自定义设置");
die();
break;

case "m_config_edit":

$W_show=$_POST["W_show"];
$W_phone=$_POST["W_phone"];
$W_email=$_POST["W_email"];
$W_msg=$_POST["W_msg"];
$W_logo=$_POST["W_logo"];
$W_template=$_POST["W_template"];
if($W_show!=""){

mysqli_query($conn, "update ".TABLE."wap set
W_show=".$W_show.",
W_phone='".$W_phone."',
W_email='".$W_email."',
W_msg=".$W_msg.",
W_logo='".$W_logo."',
W_template='".$W_template."'
");

echo "success|修改网站设置成功!";
lg("修改手机版设置");
die();
}else{
echo "error|请填全信息！";
die();
}
break;

case "m_slide_add":

$S_title=$_POST["S_title"];
$S_pic=$_POST["S_pic"];
$S_link=$_POST["S_link"];
$S_content=$_POST["S_content"];
if(substr($S_pic,0,5)=="media" || substr($S_pic,0,6)=="images" || substr($S_pic,0,4)=="http"){
$S_pic=$S_pic;
}else{
$S_pic="media/".$S_pic;
}
if($S_title!="" && $S_pic!="" && $S_content!=""){
$sql="Insert into ".TABLE."wapslide(S_title,S_pic,S_content,S_link) values('".lang_add("",$S_title)."','".$S_pic."','".lang_add("",$S_content)."','".$S_link."')";
mysqli_query($conn,$sql);
echo "success|添加成功!";
lg("新增焦点图");
die();
}else{
echo "error|请填全信息!";
die();
}
break;

case "m_slide_del":

mysqli_query($conn,"delete from ".TABLE."wapslide where S_id=".intval($_GET["S_id"]));
echo "success|删除成功!|".$_GET["S_id"];
die();
break;

case "m_slide_delall":

$id=$_POST["id"];
if(count($id)>0){
$shu=0 ;
for ($i=0; $i< count($id);$i++ ){
mysqli_query($conn,"delete from ".TABLE."wapslide where S_id=".$id[$i]);
$shu=$shu+1 ;
$ids=$ids.$id[$i].",";
        }
        $ids= substr($ids,0,strlen($ids)-1);
if($shu>0){ ;
echo "success|成功删除".$shu."个焦点图|".$ids;
die();
}else{ 
echo "error|删除失败";
die();
} 
}else{
echo "error|未选择要删除的内容";
die();
}
break;

case "m_slide_edit":

$S_id=intval($_GET["S_id"]);
$S_title=$_POST["S_title"];
$S_pic=$_POST["S_pic"];
$S_link=$_POST["S_link"];
$S_content=$_POST["S_content"];
if(substr($S_pic,0,5)=="media" || substr($S_pic,0,6)=="images" || substr($S_pic,0,4)=="http"){
$S_pic=$S_pic;
}else{
$S_pic="media/".$S_pic;
}
if(substr($S_thumb,0,5)=="media" || substr($S_thumb,0,6)=="images" || substr($S_thumb,0,4)=="http"){
$S_thumb=$S_thumb;
}else{
$S_thumb="media/".$S_thumb;
}
if($S_title!="" && $S_pic!="" && $S_content!=""){

mysqli_query($conn,"update ".TABLE."wapslide set 
    S_title='".lang_add(getrx("select * from ".TABLE."wapslide where S_id=".$S_id,"S_title"),$S_title)."',
    S_pic='".$S_pic."',
    S_link='".$S_link."',
    S_content='".lang_add(getrx("select * from ".TABLE."wapslide where S_id=".$S_id,"S_content"),$S_content)."'
    where S_id=".$S_id
    );

echo "success|修改成功!";
lg("修改焦点图（ID：".$S_id."）");
die();
}else{
echo "error|请填全信息!";
die();
}
break;

case "m_slide_save":

foreach ($_POST as $x=>$value) {
if(splitx($x,"_",0)=="title"){
mysqli_query($conn,"update ".TABLE."wapslide set S_title='".lang_add(getrx("select * from ".TABLE."wapslide where S_id=".splitx($x,"_",1),"S_title"),$_POST[$x])."' where S_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="order"){
mysqli_query($conn,"update ".TABLE."wapslide set S_order=".$_POST[$x]." where S_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="pic"){
if(substr($_POST[$x],0,5)=="media" || substr($_POST[$x],0,6)=="images" || substr($_POST[$x],0,4)=="http"){
$pic=$_POST[$x];
}else{
$pic="media/".$_POST[$x];
}
mysqli_query($conn,"update ".TABLE."wapslide set S_pic='".$pic."' where S_id=".splitx($x,"_",1));
}
} 
echo "success|修改成功!";
lg("保存焦点图（在列表页）");
die();
break;

case "need_edit":

$need=$_POST["need"];
mysqli_query($conn,"update ".TABLE."config set C_need='".$need."'");
echo "success|修改业务需求成功!";
lg("修改业务需求");
die();
break;

case "news_add":

$N_title=$_POST["N_title"];
$N_author=$_POST["N_author"];
$N_view=$_POST["N_view"];
$N_date=$_POST["N_date"];
$N_content=str_Replace("\"".$C_dir,"\"{@SL_安装目录}",$_POST["N_content"]);
$N_pic=$_POST["N_pic"];
$N_sort=$_POST["N_sort"];
$N_top=$_POST["N_top"];
$N_sh=$_POST["N_sh"];
$N_lv=$_POST["N_lv"];
$N_form=intval($_POST["N_form"]);
$N_link=$_POST["N_link"];
$N_color=$_POST["N_color"];
$N_strong=intval($_POST["N_strong"]);
$tag=$_POST["N_tag"];
for($i=0;$i<count($tag);$i++){
    $N_tag=$N_tag.$tag[$i].",";
}
$N_tag= substr($N_tag,0,strlen($N_tag)-1);
$N_pagetitle=$_POST["N_pagetitle"];
$N_keywords=$_POST["N_keywords"];
$N_type=$_POST["N_type"];
$N_hide=$_POST["N_hide"];
$N_hideon=0;
$N_hidetype=$_POST["N_hidetype"];
$N_hideintro=$_POST["N_hideintro"];
$N_price=intval($_POST["N_price"]);
$N_video=$_POST["N_video"];
$N_teaminfo=$_POST["N_teaminfo"];
$N_teamid=$_POST["N_teamid"];
$N_job=$_POST["N_job1"]."|".$_POST["N_job2"]."|".$_POST["N_job3"]."|".$_POST["N_job4"]."|".$_POST["N_job5"]."|".$_POST["N_job6"]."|".$_POST["N_job7"]."|".$_POST["N_job8"]."|".$_POST["N_job9"];
$N_jobname=$_POST["N_jobname1"]."@".$_POST["N_jobname2"]."@".$_POST["N_jobname3"]."@".$_POST["N_jobname4"]."@".$_POST["N_jobname5"]."@".$_POST["N_jobname6"]."@".$_POST["N_jobname7"]."@".$_POST["N_jobname8"]."@".$_POST["N_jobname9"];
$N_file=$_POST["N_file1"]."|".$_POST["N_file2"]."|".$_POST["N_file3"]."|".$_POST["N_file4"]."|".$_POST["N_file5"]."|".$_POST["N_file6"]."|".$_POST["N_file7"];
$N_team=$_POST["N_team1"]."|".$_POST["N_team2"]."|".$_POST["N_team3"]."|".$_POST["N_team4"];
if($_POST["N_description"]==""){
$N_description=mb_substr(strip_tags($N_content),0,200,"utf-8");
}else{
$N_description=$_POST["N_description"];
}
if(substr($N_pic,0,5)=="media" || substr($N_pic,0,6)=="images" || substr($N_pic,0,4)=="http"){
$N_pic=$N_pic;
}else{
$N_pic="media/".$N_pic;
}
if($N_view=="" || !is_numeric($N_view)){
$N_view=0;
}
if($N_top==""){
$N_top=0;
}
if($N_teaminfo==""){
$N_teaminfo=0;
}
if($N_teamid==""){
$N_teamid=0;
}
if($N_teaminfo==1 && $N_teamid==""){
echo "error|请选择一个会员，调用会员信息!";
die();
}

if(getrx("select * from ".TABLE."news where N_title like '%".$N_title."%' and N_date='".$N_date."'","N_id")==""){
if($N_title!="" && $N_content!="" && $N_pic!="" && $N_sort!="" && $N_date!=""){
if(strpos($_COOKIE["newsauth"],"all")===false){
$N_sh=2;
}

mysqli_query($conn,"insert into ".TABLE."news(
N_title,
N_author,
N_view,
N_content,
N_short,
N_pic,
N_sort,
N_date,
N_top,
N_teaminfo,
N_sh,
N_type,
N_job,
N_jobname,
N_file,
N_team,
N_teamid,
N_lv,
N_form,
N_color,
N_hide,
N_hideon,
N_hideintro,
N_hidetype,
N_price,
N_video,
N_strong,
N_tag,
N_link,
N_pagetitle,
N_keywords,
N_description
) values(
'".lang_add("",$N_title)."',
'".$N_author."',
".$N_view.",
'".lang_add("",$N_content)."',
'".lang_add("",$N_description)."',
'".$N_pic."',
".$N_sort.",
'".$N_date."',
".$N_top.",
".$N_teaminfo.",
".$N_sh.",
".$N_type.",
'".$N_job."',
'".$N_jobname."',
'".$N_file."',
'".$N_team."',
".$N_teamid.",
".$N_lv.",
".$N_form.",
'".$N_color."',
'".$N_hide."',
".$N_hideon.",
'".$N_hideintro."',
'".$N_hidetype."',
".$N_price.",
'".$N_video."',
".$N_strong.",
'".",".$N_tag.","."',
'".$N_link."',
'".lang_add("",$N_pagetitle)."',
'".lang_add("",$N_keywords)."',
'".lang_add("",$N_description)."'
)");


$sql="Select * from ".TABLE."news order by N_id desc limit 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$N_id=$row["N_id"];
$N_sort=$row["N_sort"];
}

a("newsinfo", $N_id,"template");

if($C_html==1){
creat_index(langtonum());
creat_news_list(langtonum(),$N_sort);
creat_news_info(langtonum(),$N_id);
}
lg("新增新闻");
echo "success|添加新闻成功!|insert into ".TABLE."news(
N_title,
N_author,
N_view,
N_content,
N_short,
N_pic,
N_sort,
N_date,
N_top,
N_teaminfo,
N_sh,
N_type,
N_job,
N_jobname,
N_file,
N_team,
N_teamid,
N_lv,
N_color,
N_hide,
N_hideon,
N_hideintro,
N_hidetype,
N_price,
N_video,
N_strong,
N_tag,
N_link,
N_pagetitle,
N_keywords,
N_description
) values(
'".lang_add("",$N_title)."',
'".$N_author."',
".$N_view.",
'".lang_add("",$N_content)."',
'".lang_add("",$N_description)."',
'".$N_pic."',
".$N_sort.",
'".$N_date."',
".$N_top.",
".$N_teaminfo.",
".$N_sh.",
".$N_type.",
'".$N_job."',
'".$N_jobname."',
'".$N_file."',
'".$N_team."',
".$N_teamid.",
".$N_lv.",
'".$N_color."',
'".$N_hide."',
".$N_hideon.",
'".$N_hideintro."',
'".$N_hidetype."',
".$N_price.",
'".$N_video."',
".$N_strong.",
'".",".$N_tag.","."',
'".$N_link."',
'".lang_add("",$N_pagetitle)."',
'".lang_add("",$N_keywords)."',
'".lang_add("",$N_description)."'
)";
die();
}else{
die("error|请填全信息!");
}
}else{
die("error|请勿重复添加内容!");
}
break;

case "news_del":
mysqli_query($conn,"update ".TABLE."news set N_del=1 where N_id=".intval($_GET["N_id"]));
echo "success|放入回收站成功!|".$_GET["N_id"];
lg("将新闻放入回收站（ID：".$_GET["N_id"]."）");
die();
break;

case "news_delall":

$id=$_POST["id"];
if(count($id)>0){
$shu=0 ;
for ($i=0;$i< count($id);$i++ ){
mysqli_query($conn,"update ".TABLE."news set N_del=1 where N_id=".$id[$i]);
$shu=$shu+1 ;
$ids=$ids.$id[$i].",";
        }
        $ids= substr($ids,0,strlen($ids)-1);
if($shu>0){ ;
echo "success|成功删除".$shu."条新闻|".$ids;
lg("批量删除新闻（ID：".$ids."）");
die();
}else{ ;
echo "error|删除失败";
die();
} 
}else{
echo "error|未选择要删除的内容";
die();
}
break;

case "news_edit":

if(strpos($_COOKIE["newsauth"],"all")!==false){
$auth_info="";
}else{
$newsauth=explode(",",$_COOKIE["newsauth"]);
for ($i=0 ;$i<count($newsauth)-1;$i++){
$tj=$tj."or N_sort=".$newsauth[$i]." ";
}
if($tj==""){
$auth_info=" and N_id<0";
}else{
$tj= substr($tj,-(strlen($tj)-3));
$auth_info=" and (".$tj.")";
}
}
$N_id=intval($_GET["N_id"]);
$N_title=$_POST["N_title"];
$N_author=$_POST["N_author"];
$N_view=$_POST["N_view"];
$N_date=$_POST["N_date"];
$N_content=str_Replace("\"".$C_dir,"\"{@SL_安装目录}",$_POST["N_content"]);
$N_pic=$_POST["N_pic"];
$N_sort=$_POST["N_sort"];
$N_top=$_POST["N_top"];
$N_sh=$_POST["N_sh"];
$N_lv=$_POST["N_lv"];
$N_form=intval($_POST["N_form"]);
$N_link=$_POST["N_link"];
$N_color=$_POST["N_color"];
$N_video=$_POST["N_video"];
$N_hide=$_POST["N_hide"];
$N_hideon=0;
$N_hidetype=$_POST["N_hidetype"];
$N_hideintro=$_POST["N_hideintro"];
$N_price=intval($_POST["N_price"]);
$N_strong=intval($_POST["N_strong"]);

$tag=$_POST["N_tag"];
for($i=0;$i<count($tag);$i++){
    $N_tag=$N_tag.$tag[$i].",";
}
$N_tag= substr($N_tag,0,strlen($N_tag)-1);

$N_pagetitle=$_POST["N_pagetitle"];
$N_keywords=$_POST["N_keywords"];
$N_type=$_POST["N_type"];
$N_teaminfo=$_POST["N_teaminfo"];
$N_teamid=$_POST["N_teamid"];
$N_job=$_POST["N_job1"]."|".$_POST["N_job2"]."|".$_POST["N_job3"]."|".$_POST["N_job4"]."|".$_POST["N_job5"]."|".$_POST["N_job6"]."|".$_POST["N_job7"]."|".$_POST["N_job8"]."|".$_POST["N_job9"];
$N_jobname=$_POST["N_jobname1"]."@".$_POST["N_jobname2"]."@".$_POST["N_jobname3"]."@".$_POST["N_jobname4"]."@".$_POST["N_jobname5"]."@".$_POST["N_jobname6"]."@".$_POST["N_jobname7"]."@".$_POST["N_jobname8"]."@".$_POST["N_jobname9"];
$N_file=$_POST["N_file1"]."|".$_POST["N_file2"]."|".$_POST["N_file3"]."|".$_POST["N_file4"]."|".$_POST["N_file5"]."|".$_POST["N_file6"]."|".$_POST["N_file7"];
$N_team=$_POST["N_team1"]."|".$_POST["N_team2"]."|".$_POST["N_team3"]."|".$_POST["N_team4"];
if($_POST["N_description"]==""){
$N_description=mb_substr(strip_tags($N_content),0,200,"utf-8");
}else{
$N_description=$_POST["N_description"];
}
if(substr($N_pic,0,5)=="media" || substr($N_pic,0,6)=="images" || substr($N_pic,0,4)=="http"){
$N_pic=$N_pic;
}else{
$N_pic="media/".$N_pic;
}
if($N_view=="" || !is_numeric($N_view)){
$N_view=0;
}
if($N_top==""){
$N_top=0;
}
if($N_teaminfo==""){
$N_teaminfo=0;
}
if($N_teamid==""){
$N_teamid=0;
}
if($N_teaminfo==1 and $N_teamid==""){
echo "error|请选择一个会员，调用会员信息!";
die();
}

if($N_title!="" && $N_content!="" && $N_pic!="" && $N_sort!="" && $N_date!=""){

if(strpos($_COOKIE["newsauth"],"all")<0){
$N_sh=2;
}

mysqli_query($conn,"update ".TABLE."news set
N_title='".lang_add(getrx("select * from ".TABLE."news where N_id=".$N_id,"N_title"),$N_title)."',
N_author='".$N_author."',
N_view=".$N_view.",
N_content='".lang_add(getrx("select * from ".TABLE."news where N_id=".$N_id,"N_content"),$N_content)."',
N_short='".lang_add(getrx("select * from ".TABLE."news where N_id=".$N_id,"N_description"),$N_description)."',
N_pic='".$N_pic."',
N_sort=".$N_sort.",
N_date='".$N_date."',
N_top=".$N_top.",
N_teaminfo=".$N_teaminfo.",
N_sh=".$N_sh.",
N_type=".$N_type.",
N_job='".$N_job."',
N_jobname='".$N_jobname."',
N_file='".$N_file."',
N_team='".$N_team."',
N_teamid=".$N_teamid.",
N_lv=".$N_lv.",
N_form=".$N_form.",
N_color='".$N_color."',
N_hide='".$N_hide."',
N_hideon=".$N_hideon.",
N_hideintro='".$N_hideintro."',
N_hidetype='".$N_hidetype."',
N_price=".$N_price.",
N_video='".$N_video."',
N_strong=".$N_strong.",
N_tag='".",".$N_tag.","."',
N_link='".$N_link."',
N_pagetitle='".lang_add(getrx("select * from ".TABLE."news where N_id=".$N_id,"N_pagetitle"),$N_pagetitle)."',
N_keywords='".lang_add(getrx("select * from ".TABLE."news where N_id=".$N_id,"N_keywords"),$N_keywords)."',
N_description='".lang_add(getrx("select * from ".TABLE."news where N_id=".$N_id,"N_description"),$N_description)."'
where N_id=".$N_id
);

if($C_html==1){
creat_index(langtonum());
creat_news_list(langtonum(),$N_sort);
creat_news_info(langtonum(),$N_id);
}
lg("修改新闻（ID：".$N_id."）");
a("newsinfo", $N_id,"template");
echo "success|修改新闻成功!";
die();

}else{
echo "error|请填全信息!";
die();
}
break;

case "news_save":

foreach ($_POST as $x=>$value) {
if(splitx($x,"_",0)=="title"){
mysqli_query($conn,"update ".TABLE."news set N_title='".lang_add(getrx("select * from ".TABLE."news where N_id=".splitx($x,"_",1),"N_title"),$_POST[$x])."' where N_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="order"){
mysqli_query($conn,"update ".TABLE."news set N_order=".$_POST[$x]." where N_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="sort"){
mysqli_query($conn,"update ".TABLE."news set N_sort=".$_POST[$x]." where N_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="top"){
mysqli_query($conn,"update ".TABLE."news set N_top=".$_POST[$x]." where N_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="pic"){
if(substr($_POST[$x],0,5)=="media" || substr($_POST[$x],0,6)=="images" || substr($_POST[$x],0,4)=="http"){
$pic=$_POST[$x];
}else{
$pic="media/".$_POST[$x];
}
mysqli_query($conn,"update ".TABLE."news set N_pic='".$pic."' where N_id=".splitx($x,"_",1));
}
} 
if($C_html==1){
creat_index(langtonum());
creat_news_list(langtonum(),"");
creat_news_info(langtonum(),"");
}
echo "success|修改成功!";
lg("保存新闻（在列表页）");
die();
break;

case "nsort_add":
$S_title=$_POST["S_title"];
$S_entitle=$_POST["S_entitle"];
$S_description=$_POST["S_description"];
$S_keywords=$_POST["S_keywords"];
$S_pagetitle=$_POST["S_pagetitle"];
$U_sub=$_POST["U_sub"];
$S_order=intval($_POST["S_order"]);
$S_sub=$_POST["S_sub"];
$S_pic=$_POST["S_pic"];
$S_url=$_POST["S_url"];
$S_show=intval($_POST["S_show"]);
$S_tg=intval($_POST["S_tg"]);

if($S_title!=""){
    mysqli_query($conn,"insert into ".TABLE."nsort(S_title,S_entitle,S_description,S_keywords,S_pagetitle,S_order,S_sub,S_pic,S_show,S_tg,S_url) values('".lang_add("",$S_title)."','".lang_add("",$S_entitle)."','".lang_add("",$S_description)."','".lang_add("",$S_keywords)."','".lang_add("",$S_pagetitle)."',".$S_order.",".$S_sub.",'".$S_pic."',".$S_show.",".$S_tg.",'".$S_url."')");

    $sql="Select * from ".TABLE."nsort order by S_id desc limit 1";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if(mysqli_num_rows($result) > 0) {
        $S_id=$row["S_id"];
    }
    a("news",$S_id,"template");
    if($U_sub!="x"){
        mysqli_query($conn,"insert into ".TABLE."menu(U_title,U_entitle,$U_order,$U_sub,U_ico,U_type,U_typeid) values('".$S_title."','".$S_entitle."',99,".$U_sub.",'bars','news',".$S_id.")");
    }
    if($C_html==1){
        creat_index(langtonum());
        creat_news_list(langtonum(),$S_id);
    }
    echo "success|添加成功!";
    lg("新增新闻分类");
    die();
}else{
    echo "error|请填全信息!";
    die();
}
break;

case "nsort_addmenu":

$sql="select * from ".TABLE."menu where U_sub=0 order by U_order desc limit 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$U_order=$row["U_order"];
}
$sql="select * from ".TABLE."nsort where S_id=".intval($_GET["S_id"]);
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$S_title=$row["S_title"];
$S_entitle=$row["S_entitle"];
$S_sub=$row["S_sub"];
}
mysqli_query($conn,"insert into ".TABLE."menu(U_title,U_entitle,$U_order,$U_sub,U_ico,U_type,U_typeid) values('".$S_title."','".$S_entitle."',".($U_order+1).",0,'bars','news',".$_GET["S_id"].")");
if($S_sub==0){
$sql="select * from ".TABLE."menu order by U_id desc limit 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$U_id=$row["U_id"];
}
$sql="select * from ".TABLE."nsort where S_sub=".intval($_GET["S_id"]);

$result = mysqli_query($conn,  $sql);
if (mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
mysqli_query($conn,"insert into ".TABLE."menu(U_title,U_entitle,$U_order,$U_sub,U_ico,U_type,U_typeid) values('".$row["S_title"]."','".$row["S_entitle"]."',".$row["S_order"].",".$U_id.",'bars','news',".$row["S_id"].")");
}
}
}
echo "success|添加成功！";
lg("新增新闻分类到菜单（ID：".$_GET["S_id"]."）");
die();
break;

case "nsort_del":
mysqli_query($conn,"update ".TABLE."nsort set S_del=1 where S_id=".intval($_GET["S_id"]));
mysqli_query($conn,"update ".TABLE."nsort set S_del=1 where S_sub=".intval($_GET["S_id"]));
mysqli_query($conn,"update ".TABLE."news set N_del=1 where N_sort=".intval($_GET["S_id"]));
echo "success|放入回收站成功!|".$_GET["S_id"];
lg("将新闻分类放入回收站（ID：".$_GET["S_id"]."）");
die();
break;

case "nsort_delall":

$id=$_POST["id"];
if(count($id)>0){
$shu=0 ;
for ($i=0;$i< count($id);$i++ ){
mysqli_query($conn,"update ".TABLE."nsort set S_del=1 where S_id=".$id[$i]);
mysqli_query($conn,"update ".TABLE."nsort set S_del=1 where S_sub=".$id[$i]);
mysqli_query($conn,"update ".TABLE."news set N_del=1 where N_sort=".$id[$i]);
$shu=$shu+1 ;
$ids=$ids.$id[$i].",";
        }
        $ids= substr($ids,0,strlen($ids)-1);
if($shu>0){ ;
echo "success|成功删除".$shu."个分类|".$ids;
lg("批量删除新闻分类（ID：".$ids."）");
die();
}else{ ;
echo "error|删除失败";
die();
} ;
}else{
echo "error|未选择要删除的内容";
die();
}
break;

case "nsort_edit":

if(strpos($_COOKIE["newsauth"],"all")!==false){
$auth_info="";
}else{
$newsauth=explode(",",$_COOKIE["newsauth"]);
for ($i=0 ;$i< count($newsauth)-1;$i++){
$tj=$tj."or S_id=".$newsauth[$i]." ";
}
if($tj==""){
$auth_info=" and S_id<0";
}else{
$tj= substr($tj,-(strlen($tj)-3));
$auth_info=" and (".$tj.")";
}
}
$S_id=intval($_GET["S_id"]);
$S_title=$_POST["S_title"];
$S_entitle=$_POST["S_entitle"];
$S_description=$_POST["S_description"];
$S_keywords=$_POST["S_keywords"];
$S_pagetitle=$_POST["S_pagetitle"];
$S_type=intval($_POST["S_type"]);
$S_order=intval($_POST["S_order"]);
$S_sub=$_POST["S_sub"];
$S_pic=$_POST["S_pic"];
$S_url=$_POST["S_url"];
$S_show=intval($_POST["S_show"]);
$S_tg=intval($_POST["S_tg"]);
$sql="Select * from ".TABLE."nsort where S_sub=".$S_id;
$result = mysqli_query($conn,  $sql);
if (mysqli_num_rows($result) > 0 && $S_sub>0) {
    echo "error|该主分类下有子分类，只能归属到根分类下!";
    die();
}

if($S_title!=""){
mysqli_query($conn,"update ".TABLE."nsort set
S_title='".lang_add(getrx("select * from ".TABLE."nsort where S_id=".$S_id,"S_title"),$S_title)."',
S_entitle='".lang_add(getrx("select * from ".TABLE."nsort where S_id=".$S_id,"S_entitle"),$S_entitle)."',
S_description='".lang_add(getrx("select * from ".TABLE."nsort where S_id=".$S_id,"S_description"),$S_description)."',
S_keywords='".lang_add(getrx("select * from ".TABLE."nsort where S_id=".$S_id,"S_keywords"),$S_keywords)."',
S_pagetitle='".lang_add(getrx("select * from ".TABLE."nsort where S_id=".$S_id,"S_pagetitle"),$S_pagetitle)."',
S_type=".$S_type.",
S_order=".$S_order.",
S_sub=".$S_sub.",
S_pic='".$S_pic."',
S_url='".$S_url."',
S_show=".$S_show.",
S_tg=".$S_tg."
where S_id=".$S_id
);

if($C_html==1){
creat_index(langtonum());
creat_news_list(langtonum(),$S_id);
}

a("news", $S_id,"template");

echo "success|修改成功!";
lg("修改新闻分类（ID：".$S_id."）");
die();
}else{
echo "error|请填全信息!";
die();
}
break;

case "nsort_save":

foreach ($_POST as $x=>$value) {
if(splitx($x,"_",0)=="title"){
mysqli_query($conn,"update ".TABLE."nsort set S_title='".lang_add(getrx("select * from ".TABLE."nsort where S_id=".splitx($x,"_",1),"S_title"),$_POST[$x])."' where S_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="entitle"){
mysqli_query($conn,"update ".TABLE."nsort set S_entitle='".lang_add(getrx("select * from ".TABLE."nsort where S_id=".splitx($x,"_",1),"S_entitle"),$_POST[$x])."' where S_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="order"){
mysqli_query($conn,"update ".TABLE."nsort set S_order=".$_POST[$x]." where S_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="sort"){
mysqli_query($conn,"update ".TABLE."nsort set S_sub=".$_POST[$x]." where S_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="pic"){
if(substr($_POST[$x],0,5)=="media" || substr($_POST[$x],0,6)=="images" || substr($_POST[$x],0,4)=="http"){
$pic=$_POST[$x];
}else{
$pic="media/".$_POST[$x];
}
mysqli_query($conn,"update ".TABLE."nsort set S_pic='".$pic."' where S_id=".splitx($x,"_",1));
}
} 
echo "success|修改成功!";
lg("保存新闻分类（在列表页）");
die();
break;

case "nsort_savex":

$C_nsorttitle=$_POST["C_nsorttitle"];
$C_nsortentitle=$_POST["C_nsortentitle"];
if($C_nsorttitle!=""){
mysqli_query($conn,"update ".TABLE."config set C_nsorttitle='".lang_add(getrx("select * from ".TABLE."config where C_id=1","C_nsorttitle"),$C_nsorttitle)."',C_nsortentitle='".lang_add(getrx("select * from ".TABLE."config where C_id=1","C_nsortentitle"),$C_nsortentitle)."'");
}
echo "success|保存成功！";
break;

case "order_agree":

$O_id=intval($_GET["O_id"]);
$sql="select * from ".TABLE."orders,".TABLE."product,".TABLE."member where O_pid=P_id and O_member=M_id and O_no='".$O_id."'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$M_email=$row["M_email"];
$O_tradeno=$row["O_tradeno"];
}
switch (splitx($O_tradeno,"（",1)){
    case "支付宝付款":
    $tk="请到支付宝完成退款处理";
    $paytype="alipay";
    break;

    case "微信付款":
    $tk="请到微信完成退款处理";
    $paytype="wxpay";
    break;

    case "余额支付":
    $tk="余额已返回会员账户";
    $paytype="balance";
    break;

    case "PAYPAL付款":
    $tk="请到PAYPAL完成退款处理";
    $paytype="paypal";
    break;

    default:
    $tk="余额已返回会员账户";
    $paytype="balance";
}
if($O_id!=""){
mysqli_query($conn,"update ".TABLE."orders set O_state=5 where O_no='".$O_id."'");
$sql="select * from ".TABLE."orders,".TABLE."product,".TABLE."member,".TABLE."lv where O_pid=P_id and O_member=M_id and M_lv=L_id and O_no='".$O_id."'";
$result = mysqli_query($conn,  $sql);
if (mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
    $M_id=$row["M_id"];
    $total_fee=$row["O_num"]*$row["O_price"]*$row["L_discount"]*0.01;

    if($paytype=="balance"){
        mysqli_query($conn,"update ".TABLE."member set M_money=M_money+" . $total_fee . " where M_id=" . $M_id);
    }
    mysqli_query($conn,"update ".TABLE."member set M_fen=M_fen-" . $total_fee * $C_1yuan . " where M_id=" . $M_id);
    mysqli_query($conn,"insert into ".TABLE."list(L_title,L_mid,L_change,L_time,L_type,L_no) values('商品退款',".$M_id.",-".$total_fee*$C_1yuan.",'".date('Y-m-d H:i:s')."',1,'".$O_id."')");
    mysqli_query($conn,"insert into ".TABLE."list(L_title,L_mid,L_change,L_time,L_type,L_no) values('商品退款',".$M_id.",".$total_fee.",'".date('Y-m-d H:i:s')."',0,'".$O_id."')");
    }
} 

sendmail("商品退款处理","<h2>您在网站“".lang($C_webtitle)."”的退款处理</h2><hr>订单号：".$O_id."<br>状态：卖家同意退款",$M_email);
echo "success|退款成功!".$tk;
lg("完成退款（ID：".$O_id."）");
die();
}
break;

case "order_del":

mysqli_query($conn,"delete from ".TABLE."orders where O_no='".intval($_GET["O_id"])."'");
echo "success|删除成功!|".$_GET["O_id"];
lg("删除订单（ID：".$_GET["O_id"]."）");
die();
break;

case "order_delall":

$id=$_POST["id"];
if(count($id)>0){
$shu=0 ;
for ($i=0 ;$i< count($id) ;$i++){
mysqli_query($conn,"delete from ".TABLE."orders where O_no='".$id[$i]."'");
$shu=$shu+1 ;
$ids=$ids.$id[$i].",";
        }
        $ids= substr($ids,0,strlen($ids)-1);
if($shu>0){ ;
echo "success|成功删除".$shu."个订单|".$ids;
lg("批量删除订单（ID：".$ids."）");
die();
}else{ ;
echo "error|删除失败";
die();
} ;
}else{
echo "error|未选择要删除的内容";
die();
}
break;

case "order_refuse":

$O_id=intval($_GET["O_id"]);
$sql="select * from ".TABLE."orders,".TABLE."product,".TABLE."member where O_pid=P_id and O_member=M_id and O_no='".$O_id."'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$M_email=$row["M_email"];
}

if($O_id!=""){
mysqli_query($conn,"update ".TABLE."orders set O_state=3 where O_no='".$O_id."'");
sendmail("商品退款处理","<h2>您在网站“".lang($C_webtitle)."”的退款处理</h2><hr>订单号：".$O_id."<br>状态：卖家拒绝退款",$M_email);
echo "success|拒绝退款，请与买家联系商议!";
lg("拒绝退款（ID：".$O_id."）");
die();
}
break;

case "order_save":

foreach ($_POST as $x=>$value) {
if(splitx($x,"_",0)=="state"){
mysqli_query($conn,"update ".TABLE."orders set O_state=".$_POST[$x]." where O_no='".splitx($x,"_",1)."'");
}
}
echo "success|修改成功!";
lg("保存订单（在列表页）");
die();
break;

case "order_send":

$O_id=intval($_POST["O_id"]);
$O_wl=$_POST["O_wl"];
$O_wlid=$_POST["O_wlid"];
$O_tradeno=splitx($_POST["O_tradeno"],"（",0);

$sql="select * from ".TABLE."orders,".TABLE."product,".TABLE."member where O_pid=P_id and O_member=M_id and O_no='".$O_id."'";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$M_email=$row["M_email"];
$O_state=$row["O_state"];
$O_num=$row["O_num"];
$P_id=$row["P_id"];
}

if($O_state!=1){
    die("error|用户付款后方可发货!");
}

if($O_id!=""){

mysqli_query($conn,"update ".TABLE."orders set O_wl='".$O_wl."',O_wlid='".$O_wlid."',O_state=2 where O_no='".$O_id."'");
mysqli_query($conn,"update ".TABLE."product set P_rest=P_rest-".$O_num." where P_id=".$P_id);
sendmail("商品发货通知","<h2>您在网站“".lang($C_webtitle)."”的发货通知</h2><hr>订单号：".$O_id."<hr>状态：卖家已发货<br>物流公司：".$O_wl."<br>运单号：".$O_wlid,$M_email);
echo "success|发货成功!";
lg("发货成功（ID：".$O_id."）");
die();
}
break;

case "product_add":
foreach ($_POST as $x=>$value) {
if(splitx($x,"_",0)=="sctitle"){
foreach ($_POST as $y=>$value) {
if(splitx($y,"_",0)=="scvvvv".splitx($x,"_",1)){
if($_POST[$y]==""){
echo "error|属性值不可留空!";
die();
}else{
$sc=$sc.lang_add($_POST["x".$y],$_POST[$y])."|";
}
}
}
$sc=substr($sc,0,strlen($sc)-1);
foreach ($_POST as $y=>$value) {
if(splitx($y,"_",0)=="spvvvv".splitx($x,"_",1)){
if($_POST[$y]==""){
echo "error|加价不可留空!";
die();
}else{
$sp=$sp.$_POST[$y]."|";
}
}
}
$sp=substr($sp,0,strlen($sp)-1);
if($_POST[$x]==""){
echo "error|属性名称不可留空!";
die();
}else{
$shuxing=$shuxing.lang_add($_POST["x".$x],$_POST[$x])."_".$sc."_".$sp."@";
}
}
$sc="";
$sp="";
} 
if($shuxing!=""){
$shuxing=substr($shuxing,0,strlen($shuxing)-1);
}
foreach ($_POST as $x=>$value) {
if(splitx($x,"_",0)=="picpic1"){
if(substr($_POST[$x],0,5)!="media" && substr($_POST[$x],0,6)!="images" && substr($_POST[$x],0,4)!="http"){
$pic=$pic."media/".$_POST[$x]."__".$_POST["itrpic1_".splitx($x,"_",1)]."|";
}else{
$pic=$pic.$_POST[$x]."__".$_POST["itrpic1_".splitx($x,"_",1)]."|";
}
}
}
$P_path=substr($pic,0,strlen($pic)-1);
$P_title=$_POST["P_title"];
$P_price=intval($_POST["P_price"]);
$P_rest=intval($_POST["P_rest"]);
$P_time=$_POST["P_time"];
$P_buy=intval($_POST["P_buy"]);
$P_unlogin=0;
$P_top=intval($_POST["P_top"]);
$P_content=str_Replace("\"".$C_dir,"\"{@SL_安装目录}",$_POST["P_content"]);
$P_sort=intval($_POST["P_sort"]);
$P_brand=intval($_POST["P_brand"]);
$P_name=intval($_POST["P_name"]);
$P_email=intval($_POST["P_email"]);
$P_mobile=intval($_POST["P_mobile"]);
$P_address=intval($_POST["P_address"]);
$P_postcode=intval($_POST["P_postcode"]);
$P_shuxingt=intval($_POST["P_shuxingt"]);
$P_qq=intval($_POST["P_qq"]);
$P_remark=intval($_POST["P_remark"]);
$P_sell=$_POST["P_sell"];
$P_sence=intval($_POST["P_sence"]);
$P_link=$_POST["P_link"];

if($P_brand==0){
	die("error|请选择一个产品品牌");
}

if($P_rest=="" || !is_numeric($P_rest)){
$P_rest=100;
}
if($_POST["P_description"]==""){
    $P_description=mb_substr(strip_tags($P_content),0,200,"utf-8");
}else{
    $P_description=$_POST["P_description"];
}
$P_pagetitle=$_POST["P_pagetitle"];
$P_keywords=$_POST["P_keywords"];


if(getrx("select * from ".TABLE."product where P_title like '%".$P_title."%' and P_time='".$P_time."'","P_id")==""){
if($P_title!="" && $P_content!="" && $P_path!="" && $P_sort!="" && $P_time!=""){
mysqli_query($conn, "insert into ".TABLE."product(P_title,
P_price,
P_rest,
P_time,
P_buy,
P_unlogin,
P_top,
P_content,
P_short,
P_path,
P_sort,
P_brand,
P_shuxing,
P_shuxingt,
P_order,
P_pagetitle,
P_keywords,
P_description,
P_name,
P_email,
P_mobile,
P_address,
P_postcode,
P_qq,
P_remark,
P_sell,
P_sence,
P_link) values('".lang_add("",$P_title)."',
".$P_price.",
".$P_rest.",
'".$P_time."',
".$P_buy.",
".$P_unlogin.",
".$P_top.",
'".lang_add("",$P_content)."',
'".lang_add("",$P_description)."',
'".$P_path."',
".$P_sort.",
".$P_brand.",
'".$shuxing."',
".$P_shuxingt.",
0,
'".lang_add("",$P_pagetitle)."',
'".lang_add("",$P_keywords)."',
'".lang_add("",$P_description)."',
".$P_name.",
".$P_email.",
".$P_mobile.",
".$P_address.",
".$P_postcode.",
".$P_qq.",
".$P_remark.",
'".$P_sell."',
".$P_sence.",
'".$P_link."')");

$sql="Select * from ".TABLE."product order by P_id desc limit 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$P_id=$row["P_id"];
$P_sort=$row["P_sort"];
}

a("productinfo",$P_id,"template");

if($C_html==1){
creat_index(langtonum());
creat_product_list(langtonum(),$P_sort);
creat_product_info(langtonum(),$P_id);
}
lg("新增产品/案例");

echo "success|新增成功！";
die();

}else{
die("error|请填全信息！");
}
}else{
die("error|请勿重复添加内容！");
}
break;

case "product_del":
mysqli_query($conn,"update ".TABLE."product set P_del=1 where P_id=".intval($_GET["P_id"]));
echo "success|放入回收站成功!|".$_GET["P_id"];
lg("将产品放入回收站（ID：".$_GET["P_id"]."）");
die();
break;

case "product_delall":

$id=$_POST["id"];
if(count($id)>0){
$shu=0 ;
for ($i=0 ;$i< count($id) ;$i++){
mysqli_query($conn,"update ".TABLE."product set P_del=1 where P_id=".$id[$i]);
$shu=$shu+1 ;
$ids=$ids.$id[$i].",";
        }
        $ids= substr($ids,0,strlen($ids)-1);
if($shu>0){ ;
echo "success|成功删除".$shu."个产品|".$ids;
lg("批量删除产品（ID：".$ids."）");
die();
}else{ ;
echo "error|删除失败";
die();
} ;
}else{
echo "error|未选择要删除的内容";
die();
}
break;

case "product_edit":

if(strpos($_COOKIE["productauth"],"all")!==false){
$auth_info="";
}else{
$productauth=explode(",",$_COOKIE["productauth"]);
for ($i=0 ;$i< count($productauth)-1;$i++){
$tj=$tj."or P_sort=".$productauth[$i]." ";
}
if($tj==""){
$auth_info=" and P_id<0";
}else{
$tj= substr($tj,-(strlen($tj)-3));
$auth_info=" and (".$tj.")";
}
}
foreach ($_POST as $x=>$value) {
if(splitx($x,"_",0)=="sctitle"){
foreach ($_POST as $y=>$value) {
if(splitx($y,"_",0)=="scvvvv".splitx($x,"_",1)){
if($_POST[$y]==""){
echo "error|属性值不可留空!";
die();
}else{
$sc=$sc.lang_add($_POST["x".$y],$_POST[$y])."|";
}
}
}
$sc=substr($sc,0,strlen($sc)-1);
foreach ($_POST as $y=>$value) {
if(splitx($y,"_",0)=="spvvvv".splitx($x,"_",1)){
if($_POST[$y]==""){
echo "error|对价格的影响不可留空!";
die();
}else{
$sp=$sp.$_POST[$y]."|";
}
}
}
$sp=substr($sp,0,strlen($sp)-1);
if($_POST[$x]==""){
echo "error|属性名称不可留空!";
die();
}else{
$shuxing=$shuxing.lang_add($_POST["x".$x],$_POST[$x])."_".$sc."_".$sp."@";
}
}
$sc="";
$sp="";
}
if($shuxing!=""){
$shuxing=substr($shuxing,0,strlen($shuxing)-1);
}
foreach ($_POST as $x=>$value) {
if(splitx($x,"_",0)=="picpic1"){
if(substr($_POST[$x],0,5)!="media" && substr($_POST[$x],0,6)!="images" && substr($_POST[$x],0,4)!="http"){
$pic=$pic."media/".$_POST[$x]."__".$_POST["itrpic1_".splitx($x,"_",1)]."|";
}else{
$pic=$pic.$_POST[$x]."__".$_POST["itrpic1_".splitx($x,"_",1)]."|";
}
}
}
$P_path=substr($pic,0,strlen($pic)-1);
$P_id=intval($_GET["P_id"]);
$P_title=$_POST["P_title"];
$P_price=$_POST["P_price"];
$P_rest=$_POST["P_rest"];
$P_time=$_POST["P_time"];
$P_buy=$_POST["P_buy"];
$P_unlogin=intval($_POST["P_unlogin"]);
$P_top=$_POST["P_top"];
$P_content=str_Replace("\"".$C_dir,"\"{@SL_安装目录}",$_POST["P_content"]);
$P_sort=$_POST["P_sort"];
$P_brand=$_POST["P_brand"];
$P_name=intval($_POST["P_name"]);
$P_email=intval($_POST["P_email"]);
$P_mobile=intval($_POST["P_mobile"]);
$P_address=intval($_POST["P_address"]);
$P_postcode=intval($_POST["P_postcode"]);
$P_shuxingt=intval($_POST["P_shuxingt"]);
$P_qq=intval($_POST["P_qq"]);
$P_remark=intval($_POST["P_remark"]);
$P_sell=$_POST["P_sell"];
$P_sence=intval($_POST["P_sence"]);
$P_link=$_POST["P_link"];
if($P_top==""){
$P_top=0;
}
if($P_price=="" || !is_numeric($P_price)){
$P_price=0;
}
if($P_rest=="" || !is_numeric($P_rest)){
$P_rest=100;
}
if($_POST["P_description"]==""){
$P_description=mb_substr(strip_tags($P_content),0,200,"utf-8");
}else{
$P_description=$_POST["P_description"];
}
$P_pagetitle=$_POST["P_pagetitle"];
$P_keywords=$_POST["P_keywords"];

if($P_title!="" && $P_content!="" && $P_path!="" && $P_sort!="" && $P_time!=""){

mysqli_query($conn,"update ".TABLE."product set
P_title='".lang_add(getrx("select * from ".TABLE."product where P_id=".$P_id,"P_title"),$P_title)."',
P_price=".$P_price.",
P_rest=".$P_rest.",
P_time='".$P_time."',
P_buy=".$P_buy.",
P_unlogin=".$P_unlogin.",
P_top=".$P_top.",
P_content='".lang_add(getrx("select * from ".TABLE."product where P_id=".$P_id,"P_content"),$P_content)."',
P_short='".lang_add(getrx("select * from ".TABLE."product where P_id=".$P_id,"P_description"),$P_description)."',
P_path='".$P_path."',
P_sort=".$P_sort.",
P_brand=".$P_brand.",
P_shuxing='".$shuxing."',
P_shuxingt=".$P_shuxingt.",
P_name=".$P_name.",
P_email=".$P_email.",
P_mobile=".$P_mobile.",
P_address=".$P_address.",
P_postcode=".$P_postcode.",
P_qq=".$P_qq.",
P_remark=".$P_remark.",
P_sell='".$P_sell."',
P_sence=".$P_sence.",
P_link='".$P_link."',
P_pagetitle='".lang_add(getrx("select * from ".TABLE."product where P_id=".$P_id,"P_pagetitle"),$P_pagetitle)."',
P_keywords='".lang_add(getrx("select * from ".TABLE."product where P_id=".$P_id,"P_keywords"),$P_keywords)."',
P_description='".lang_add(getrx("select * from ".TABLE."product where P_id=".$P_id,"P_description"),$P_description)."'
where P_id=".$P_id
);

if($C_html==1){
creat_index(langtonum());
creat_product_list(langtonum(),$P_sort);
creat_product_info(langtonum(),$P_id);
}
a("productinfo", $P_id,"template");
lg("修改产品/案例（ID：".$P_id."）");
echo "success|修改成功！";
die();
}else{
echo "error|请填全信息！";
die();
}
break;

case "product_excel":

$excel = $_POST["excel"];
    $file = fopen("../" . $excel, "r");
    while (!feof($file)) {
        $p = fgetcsv($file);
        if ($p[0] != "图片") {
            mysqli_query($conn, "insert into ".TABLE."product(P_title,P_price,P_rest,P_time,P_buy,P_unlogin,P_top,P_content,P_short,P_path,P_sort,P_shuxing,P_shuxingt,P_order,P_pagetitle,P_keywords,P_description,P_name,P_email,P_mobile,P_address,P_postcode,P_qq,P_remark,P_sell,P_sence,P_link) values('" . lang_add("", $p[1]) . "'," . $p[5] . ",100,'" . date('Y-m-d H:i:s') . "',1,0,0,'" . lang_add("", $p[2]) . "','" . lang_add("", $p[10]) . "','" . $p[0] . "'," . $p[3] . ",'" . $p[7] . "',0,0,'" . lang_add("", $p[1]) . "','" . lang_add("", $p[1]) . "','" . lang_add("", $p[10]) . "',0,0,0,0,0,0,0,'',0,'')");
        }
    }
    fclose($file);
    lg("批量导入产品/案例");
    die("success|导入成功!");
break;

case "product_save":

foreach ($_POST as $x=>$value) {
if(splitx($x,"_",0)=="buy"){
mysqli_query($conn,"update ".TABLE."product set P_buy=".$_POST[$x]." where P_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="title"){
mysqli_query($conn,"update ".TABLE."product set P_title='".lang_add(getrx("select * from ".TABLE."product where P_id=".splitx($x,"_",1),"P_title"),$_POST[$x])."' where P_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="price"){
mysqli_query($conn,"update ".TABLE."product set P_price=".str_Replace(",","",$_POST[$x])." where P_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="order"){
mysqli_query($conn,"update ".TABLE."product set P_order=".$_POST[$x]." where P_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="sort"){
mysqli_query($conn,"update ".TABLE."product set P_sort=".$_POST[$x]." where P_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="top"){
mysqli_query($conn,"update ".TABLE."product set P_top=".$_POST[$x]." where P_id=".splitx($x,"_",1));
}
}
if($C_html==1){
creat_index(langtonum());
creat_product_list(langtonum(),"");
creat_product_info(langtonum(),"");
}
echo "success|修改成功!";
lg("保存产品（在列表页）");
die();
break;

case "psort_add":

$S_pic=$_POST["S_pic"];
$S_title=$_POST["S_title"];
$S_entitle=$_POST["S_entitle"];
$S_sub=$_POST["S_sub"];
$S_order=$_POST["S_order"];
$S_description=$_POST["S_description"];
$S_keywords=$_POST["S_keywords"];
$S_pagetitle=$_POST["S_pagetitle"];
$S_type=$_POST["S_type"];
$S_show=$_POST["S_show"];
$S_url=$_POST["S_url"];
$U_sub=$_POST["U_sub"];
if(substr($S_pic,0,5)=="media" || substr($S_pic,0,6)=="images" || substr($S_pic,0,4)=="http"){
$S_pic=$S_pic;
}else{
$S_pic="media/".$S_pic;
}
if(!is_numeric($S_order) || $S_order==""){
$S_order=0;
}
if($S_title!=""){
mysqli_query($conn,"Insert into ".TABLE."psort(S_title,S_entitle,S_sub,S_order,S_pic,S_description,S_keywords,S_pagetitle,S_type,S_show,S_url) values('".lang_add("",$S_title)."','".lang_add("",$S_entitle)."',".$S_sub.",".$S_order.",'".$S_pic."','".lang_add("",$S_description)."','".lang_add("",$S_keywords)."','".lang_add("",$S_pagetitle)."',".$S_type.",".$S_show.",'".$S_url."')");

$sql="Select * from ".TABLE."psort order by S_id desc limit 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$S_id=$row["S_id"];
}
a("product",$S_id,"template");
if($U_sub!="x"){
mysqli_query($conn,"insert into ".TABLE."menu(U_title,U_entitle,U_order,U_sub,U_ico,U_type,U_typeid) values('".$S_title."','".$S_entitle."',99,".$U_sub.",'bars','product',".$S_id.")");
}
if($C_html==1){
creat_index(langtonum());
creat_product_list(langtonum(),$S_id);
}
echo "success|添加成功!";
lg("新增产品分类");
die();
}else{
echo "error|请填全信息!";
die();
}
break;

case "psort_addmenu":

$sql="select * from ".TABLE."menu where U_sub=0 order by U_order desc limit 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$U_order=$row["U_order"];
}
$sql="select * from ".TABLE."psort where S_id=".intval($_GET["S_id"])." order by S_id desc";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$S_title=$row["S_title"];
$S_entitle=$row["S_entitle"];
$S_sub=$row["S_sub"];
}
mysqli_query($conn,"insert into ".TABLE."menu(U_title,U_entitle,U_order,U_sub,U_ico,U_type,U_typeid) values('".$S_title."','".$S_entitle."',".($U_order+1).",0,'bars','product',".$_GET["S_id"].")");
if($S_sub==0){
$sql="select * from ".TABLE."menu order by U_id desc limit 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$U_id=$row["U_id"];
}
$sql="select * from ".TABLE."psort where S_sub=".intval($_GET["S_id"]);
$result = mysqli_query($conn,  $sql);
if (mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
mysqli_query($conn,"insert into ".TABLE."menu(U_title,U_entitle,U_order,U_sub,U_ico,U_type,U_typeid) values('".$row["S_title"]."','".$row["S_entitle"]."',".$row["S_order"].",".$U_id.",'bars','product',".$row["S_id"].")");
}
}
}
echo "success|添加成功!";
lg("添加产品分类到主菜单（ID：".$_GET["S_id"]."）");
die();
break;

case "psort_del":

$sql="select * from ".TABLE."psort where S_sub=".intval($_GET["S_id"]);
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$sid=$sid.$row["S_id"].",";
}
}
mysqli_query($conn,"update ".TABLE."psort set S_del=1 where S_id=".intval($_GET["S_id"]));
mysqli_query($conn,"update ".TABLE."psort set S_del=1 where S_sub=".intval($_GET["S_id"]));
mysqli_query($conn,"update ".TABLE."product set P_del=1 where P_sort=".intval($_GET["S_id"]));
echo "success|放入回收站成功!|".$_GET["S_id"].",".$sid;
lg("将产品分类放入回收站（ID：".$_GET["S_id"]."）");
die();
break;

case "psort_delall":

$id=$_POST["id"];
if(count($id)>0){
$shu=0 ;
for ($i=0;$i< count($id);$i++ ){
$sql="select * from ".TABLE."psort where S_sub=".$id[$i];
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$sid=$sid.$row["S_id"].",";
}
}
mysqli_query($conn,"update ".TABLE."psort set S_del=1 where S_id=".$id[$i]);
mysqli_query($conn,"update ".TABLE."psort set S_del=1 where S_sub=".$id[$i]);
mysqli_query($conn,"update ".TABLE."product set P_del=1 where P_sort=".$id[$i]);
$shu=$shu+1 ;
$ids=$ids.$id[$i].",";
        }
        $ids= substr($ids,0,strlen($ids)-1);
if($shu>0){ ;
echo "success|成功删除".$shu."个分类|".$ids.",".$sid;
lg("批量删除产品分类（ID：".$ids."）");
die();
}else{ ;
echo "error|删除失败";
die();
} ;
}else{
echo "error|未选择要删除的内容";
die();
}
break;

case "psort_edit":

if(strpos($_COOKIE["productauth"],"all")!==false){
$auth_info="";
}else{
$productauth=explode(",",$_COOKIE["productauth"]);
for ($i=0;$i< count($productauth)-1;$i++){
$tj=$tj."or S_id=".$productauth[$i]." ";
}
if($tj==""){
$auth_info=" and S_id<0";
}else{
$tj= substr($tj,-(strlen($tj)-3));
$auth_info=" and (".$tj.")";
}
}
$S_id=intval($_GET["S_id"]);
$S_pic=$_POST["S_pic"];
$S_title=$_POST["S_title"];
$S_entitle=$_POST["S_entitle"];
$S_sub=$_POST["S_sub"];
$S_order=$_POST["S_order"];
$S_description=$_POST["S_description"];
$S_keywords=$_POST["S_keywords"];
$S_pagetitle=$_POST["S_pagetitle"];
$S_type=$_POST["S_type"];
$S_show=$_POST["S_show"];
$S_url=$_POST["S_url"];

$sql="Select * from ".TABLE."psort Where S_sub=".$S_id;
$result = mysqli_query($conn,  $sql);
if (mysqli_num_rows($result) > 0 && $S_sub>0) {
echo "error|该主分类下有子分类，只能归属到根分类下!";
die();
}
if(substr($S_pic,0,5)=="media" || substr($S_pic,0,6)=="images" || substr($S_pic,0,4)=="http"){
$S_pic=$S_pic;
}else{
$S_pic="media/".$S_pic;
}
if(!is_Numeric($S_order) || $S_order==""){
$S_order=0;
}
if($S_title!=""){

mysqli_query($conn,"
update ".TABLE."psort set
S_pic='".$S_pic."',
S_title='".lang_add(getrx("select * from ".TABLE."psort where S_id=".$S_id,"S_title"),$S_title)."',
S_entitle='".lang_add(getrx("select * from ".TABLE."psort where S_id=".$S_id,"S_entitle"),$S_entitle)."',
S_sub=".$S_sub.",
S_order=".$S_order.",
S_description='".lang_add(getrx("select * from ".TABLE."psort where S_id=".$S_id,"S_description"),$S_description)."',
S_keywords='".lang_add(getrx("select * from ".TABLE."psort where S_id=".$S_id,"S_keywords"),$S_keywords)."',
S_pagetitle='".lang_add(getrx("select * from ".TABLE."psort where S_id=".$S_id,"S_pagetitle"),$S_pagetitle)."',
S_type=".$S_type.",
S_url='".$S_url."',
S_show=".$S_show."
where S_id=".$S_id
);

if($C_html==1){
creat_index(langtonum());
creat_product_list(langtonum(),$S_id);
}
a("product", $S_id,"template");
echo "success|修改成功!";
lg("修改产品分类（ID：".$S_id."）");
die();
}else{
echo "error|请填全信息!";
die();
}
break;

case "psort_save":

foreach ($_POST as $x=>$value) {
if(splitx($x,"_",0)=="title"){
mysqli_query($conn,"update ".TABLE."psort set S_title='".lang_add(getrx("select * from ".TABLE."psort where S_id=".splitx($x,"_",1),"S_title"),$_POST[$x])."' where S_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="entitle"){
mysqli_query($conn,"update ".TABLE."psort set S_entitle='".lang_add(getrx("select * from ".TABLE."psort where S_id=".splitx($x,"_",1),"S_entitle"),$_POST[$x])."' where S_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="order"){
mysqli_query($conn,"update ".TABLE."psort set S_order=".$_POST[$x]." where S_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="sort"){
mysqli_query($conn,"update ".TABLE."psort set S_sub=".$_POST[$x]." where S_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="pic"){
if(substr($_POST[$x],0,5)=="media" || substr($_POST[$x],0,6)=="images" || substr($_POST[$x],0,4)=="http"){
$pic=$_POST[$x];
}else{
$pic="media/".$_POST[$x];
}
mysqli_query($conn,"update ".TABLE."psort set S_pic='".$pic."' where S_id=".splitx($x,"_",1));
}
} 
echo "success|修改成功!";
lg("修改产品分类（在列表页）");
die();
break;

case "psort_savex":

$C_psorttitle=$_POST["C_psorttitle"];
$C_psortentitle=$_POST["C_psortentitle"];
if($C_psorttitle!=""){

mysqli_query($conn,"update ".TABLE."config set C_psorttitle='".lang_add(getrx("select * from ".TABLE."config where C_id=1","C_psorttitle"),$C_psorttitle)."',C_psortentitle='".lang_add(getrx("select * from ".TABLE."config where C_id=1","C_psortentitle"),$C_psortentitle)."'");
}
echo "success|保存成功！";
break;

case "qsort_add":

$S_title=$_POST["S_title"];
$S_content=$_POST["S_content"];
if($S_title!=""){
mysqli_query($conn,"Insert into ".TABLE."qsort(S_title,S_content) values('".$S_title."','".$S_content."')");
$sql="Select * from ".TABLE."qsort order by S_id desc limit 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$S_id=$row["S_id"];
}
echo "success|添加成功!";
die();
}else{
echo "error|请填全信息!";
die();
}
break;

case "qsort_del":

mysqli_query($conn,"delete from ".TABLE."qsort where S_id=".intval($_GET["S_id"]));
mysqli_query($conn,"delete from ".TABLE."query where Q_sort=".intval($_GET["S_id"]));
echo "success|删除成功!|".$_GET["S_id"];
lg("删除查询分类（ID：".$_GET["S_id"]."）");
die();
break;

case "qsort_delall":

$id=$_POST["id"];
if(count($id>0)){
$shu=0 ;
for ($i=0 ;$i< count($id);$i++ ){
mysqli_query($conn,"delete from ".TABLE."qsort where S_id=".$id[$i]);
mysqli_query($conn,"delete from ".TABLE."query where Q_sort=".$id[$i]);
$shu=$shu+1 ;
$ids=$ids.$id[$i].",";
        }
        $ids= substr($ids,0,strlen($ids)-1);
if($shu>0){ ;
echo "success|成功删除".$shu."个分类|".$ids;
lg("批量删除查询分类（ID：".$ids."）");
die();
}else{ 
echo "error|删除失败";
die();
} 
}else{
echo "error|未选择要删除的内容";
die();
}
break;

case "qsort_edit":

$S_id=intval($_GET["S_id"]);
$S_title=$_POST["S_title"];
$S_content=$_POST["S_content"];
if($S_title!=""){
mysqli_query($conn,"update ".TABLE."qsort set
    S_title='".$S_title."',
    S_content='".$S_content."'
    where S_id=".$S_id
    );
echo "success|修改成功!";
die();
}else{
echo "error|请填全信息!";
die();
}
break;

case "qsort_save":

foreach ($_POST as $x=>$value) {
if(splitx($x,"_",0)=="title"){
mysqli_query($conn,"update ".TABLE."qsort set S_title='".$_POST[$x]."' where S_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="content"){
mysqli_query($conn,"update ".TABLE."qsort set S_content='".$_POST[$x]."' where S_id=".splitx($x,"_",1));
}
} 
echo "success|修改成功!";
lg("保存查询分类（在列表页）");
die();
break;

case "query_add":

$Q_code=$_POST["Q_code"];
$Q_sort=$_POST["Q_sort"];
foreach ($_POST as $x=>$value) {
    if(splitx($x,"_",0)=="picpic1"){
        $content=$content.$_POST[$x]."__".$_POST["itrpic1_".splitx($x,"_",1)]."|";
    }
}
$content=substr($content,0,strlen($content)-1);
if($Q_code!="" && $Q_sort!="0"){
$Q_codes=explode(PHP_EOL,$Q_code);
for ($i=0 ;$i<count($Q_codes);$i++){
mysqli_query($conn,"insert into ".TABLE."query(Q_code,Q_content,Q_sort) values('".$Q_codes[$i]."','".$content."',".$Q_sort.")");
}
echo "success|添加成功!";
lg("新增查询码");
die();
}else{
echo "error|请填全信息!";
die();
}
break;

case "query_add2":

$Q_code=$_POST["Q_code"];
$Q_sort=$_POST["Q_sort"];
$Q_content=$_POST["Q_content"];
$Q_co=explode(PHP_EOL,$Q_code);
for ($i=0;$i<count($Q_co);$i++){
mysqli_query($conn,"insert into ".TABLE."query(Q_code,Q_content,Q_sort) values('".$Q_co[$i]."','".$Q_content."',".$Q_sort.")");
}
echo "success|添加成功!";
lg("批量新增查询码");
die();
break;

case "query_del":

mysqli_query($conn,"delete from ".TABLE."query where Q_id=".intval($_GET["Q_id"]));
echo "success|删除成功!|".$_GET["Q_id"];
lg("删除查询（ID：".$_GET["Q_id"]."）");
die();
break;

case "query_delall":

$id=$_POST["id"];

if(count($id)>0){
$shu=0 ;
for ($i=0 ;$i<count($id);$i++ ){
mysqli_query($conn,"delete from ".TABLE."query where Q_id=".$id[$i]);
$shu=$shu+1 ;
$ids=$ids.$id[$i].",";
}
$ids= substr($ids,0,strlen($ids)-1);
if($shu>0){ ;
echo "success|成功删除".$shu."个防伪码|".$ids;
lg("批量删除查询（ID：".$ids."）");
die();
}else{ ;
echo "error|删除失败";
die();
} ;
}else{
echo "error|未选择要删除的内容";
die();
}
break;

case "query_edit":

$Q_id=intval($_GET["Q_id"]);
$Q_code=$_POST["Q_code"];
$Q_sort=$_POST["Q_sort"];
foreach ($_POST as $x=>$value) {
    if(splitx($x,"_",0)=="picpic1"){
        $content=$content.$_POST[$x]."__".$_POST["itrpic1_".splitx($x,"_",1)]."|";
    }
}
$content=substr($content,0,strlen($content)-1);
if($Q_code!="" && $Q_sort!="0"){
mysqli_query($conn,"
update ".TABLE."query set 
Q_code='".$Q_code."',
Q_content='".$content."',
Q_sort='".$Q_sort."'
where Q_id=".$Q_id);
echo "success|修改成功!";
lg("编辑查询（ID：".$Q_id."）");
die();
}else{
echo "error|请填全信息!";
die();
}
break;

case "query_save":

foreach ($_POST as $x=>$value) {
if(splitx($x,"_",0)=="code"){
mysqli_query($conn,"update ".TABLE."query set Q_code='".$_POST[$x]."' where Q_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="content"){
mysqli_query($conn,"update ".TABLE."query set Q_content='".$_POST[$x]."' where Q_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="sort"){
mysqli_query($conn,"update ".TABLE."query set Q_sort=".$_POST[$x]." where Q_id=".splitx($x,"_",1));
}
} 
echo "success|修改成功!";
lg("保存查询（在列表页）");
die();
break;

case "recycle_delall":
ready(plug("x15","1"));
break;

case "recycle_delete":
ready(plug("x15","2"));
break;

case "recycle_recovery":
ready(plug("x15","3"));
break;

case "recycle_recoveryall":
ready(plug("x15","4"));
break;

case "response_del":
mysqli_query($conn,"delete from ".TABLE."response where R_rid='".$_GET["R_rid"]."'");
echo "success|删除成功!|".$_GET["R_rid"];
lg("删除表单回应（ID：".$_GET["R_rid"]."）");
die();
break;

case "response_read":
$reply=t($_POST["reply"]);

mysqli_query($conn,"update ".TABLE."response set R_read=1,R_reply='$reply' where R_rid='".$_GET["R_rid"]."'");
$M_login=getrx("select * from ".TABLE."response,".TABLE."member where R_member=M_id and R_rid='".$_GET["R_rid"]."'","M_login");

if($M_login=="未提供"){
    $M_email=getrx("select * from ".TABLE."response where R_rid='".$_GET["R_rid"]."' and R_content like '%@%' and R_content like '%.%'","R_content");
}else{
    $M_email=getrx("select * from ".TABLE."response,".TABLE."member where R_member=M_id and R_rid='".$_GET["R_rid"]."'","M_email");
}

$F_title=getrx("select * from ".TABLE."form,".TABLE."content,".TABLE."response where F_id=C_fid and C_id=R_cid and R_rid='".$_GET["R_rid"]."'","F_title");
sendmail("您提交的表单已审核通过","您在 ".lang($C_webtitle)." 提交的表单已审核通过<br>表单名称：".lang($F_title)."<br>表单编号：".$_GET["R_rid"]."<br>回复内容：".$reply."<br>审核时间：".date('Y-m-d H:i:s')."",$M_email) ;
echo "success|审核成功!|";
lg("审核表单回复（ID：".$_GET["R_rid"]."）");
die();
break;

case "response_ready":
$reply=$_POST["reply"];
mysqli_query($conn,"update ".TABLE."response set R_read=2 where R_rid='".$_GET["R_rid"]."'");
$M_login=getrx("select * from ".TABLE."response,".TABLE."member where R_member=M_id and R_rid='".$_GET["R_rid"]."'","M_login");
if($M_login=="未提供"){
    $M_email=getrx("select * from ".TABLE."response where R_rid='".$_GET["R_rid"]."' and R_content like '%@%' and R_content like '%.%'","R_content");
}else{
    $M_email=getrx("select * from ".TABLE."response,".TABLE."member where R_member=M_id and R_rid='".$_GET["R_rid"]."'","M_email");
}
$F_title=getrx("select * from ".TABLE."form,".TABLE."content,".TABLE."response where F_id=C_fid and C_id=R_cid and R_rid='".$_GET["R_rid"]."'","F_title");
sendmail("您提交的表单未审核通过","您在 ".lang($C_webtitle)." 提交的表单未审核通过<br>表单名称：".lang($F_title)."<br>表单编号：".$_GET["R_rid"]."<br>回复内容：".$reply."<br>审核时间：".date('Y-m-d H:i:s')."",$M_email);
echo "success|审核成功!|".$reply;
lg("审核表单回复（ID：".$_GET["R_rid"]."）");
die();
break;

case "safe_edit":
$S_filesize=$_POST["S_filesize"];
$S_filetype=$_POST["S_filetype"];
$S_ip=$_POST["S_ip"];
$S_word=$_POST["S_word"];
$S_email=$_POST["S_email"];
$S_uncopy=$_POST["S_uncopy"];
$S_login2=$_POST["S_login2"];
$S_backup=$_POST["S_backup"];

if(preg_match('/asp|php|apsx|asax|phar|phtml|ascx|cdx|cer|cgi|jsp/i', $S_filetype)){
    die("error|上传文件格式设置不合理，请重新检查！");
}else{
    if($S_filesize!="" && $S_word!=""){
        mysqli_query($conn,"update ".TABLE."safe set
        S_filesize=".$S_filesize.",
        S_filetype='".$S_filetype."',
        S_ip='".$S_ip."',
        S_uncopy=".$S_uncopy.",
        S_login2=".$S_login2.",
        S_backup=".$S_backup.",
        S_word='".$S_word."'");

        mysqli_query($conn,"update ".TABLE."config set C_email='".$S_email."'");
        lg("修改安全设置");
        die("success|修改安全设置成功!");
    }else{
        die("error|请填全信息！");

    }
}
break;

case "savetag_":
$txt=$_POST["tag"];
mysqli_query($conn,"update ".TABLE."config set C_tag='".$txt."'");
echo "success|保存成功!";
lg("保存文章tag");
die();
break;

case "plug_refresh":
removeDir("../data/plug");
mkdir("../data/plug",0755,true);
lg("更新插件");
die("success|更新成功");
break;

case "tag_save":
$txt=$_POST["C_tag"];
mysqli_query($conn,"update ".TABLE."config set C_tag='".$txt."'");
echo "success|保存成功!";
lg("保存文章tag");
die();
break;

case "savetxt_":
$path=$_SERVER['DOCUMENT_ROOT'].$_GET["path"];
$kname = strtolower(substr($_GET["path"],strrpos($_GET["path"],'.')+1));
$txt=stripslashes($_POST["txt"]);
if(is_file($path)){
    if (preg_match('/asp|php|apsx|asax|ascx|cdx|cer|cgi|json|jsp/i', $kname)) {
        die("error|不支持保存该格式文件!");
    }else{
        file_put_contents($path,$txt);
        lg("保存文件（路径：".$_GET["path"]."）");
        die("success|保存成功!");
    }
}else{
    die("error|文件不存在!");
}
break;

case "sitemap_creat":
$T_lang=$_POST["T_lang"];
for($i=0;$i<count($T_lang);$i++){
    $lang=$lang.$T_lang[$i];
}

$sitemap=$sitemap."<?xml version=\"1.0\" encoding=\"UTF-8\"?>".PHP_EOL."<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">".PHP_EOL;

$sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."]]></loc><priority>1.00</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
$sql="select * from ".TABLE."text where T_del=0 order by T_id desc";

$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
if($C_html==0){
if(strpos($lang,"0")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."?type=text&S_id=".$row["T_id"]."&lang=cn]]></loc><priority>0.5</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
if(strpos($lang,"1")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."?type=text&S_id=".$row["T_id"]."&lang=en]]></loc><priority>0.5</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
if(strpos($lang,"2")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."?type=text&S_id=".$row["T_id"]."&lang=cht]]></loc><priority>0.5</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
}else{
if(strpos($lang,"0")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."html/about/".$row["T_id"].".html]]></loc><priority>0.5</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
if(strpos($lang,"1")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."ehtml/about/".$row["T_id"].".html]]></loc><priority>0.5</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
if(strpos($lang,"2")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."fhtml/about/".$row["T_id"].".html]]></loc><priority>0.5</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
}
}
}
$sql="select * from ".TABLE."form where F_del=0 order by F_id desc";

$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
if($C_html==0){
if(strpos($lang,"0")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."?type=form&S_id=".$row["F_id"]."&lang=cn]]></loc><priority>0.5</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
if(strpos($lang,"1")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."?type=form&S_id=".$row["F_id"]."&lang=en]]></loc><priority>0.5</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
if(strpos($lang,"2")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."?type=form&S_id=".$row["F_id"]."&lang=cht]]></loc><priority>0.5</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
}else{
if(strpos($lang,"0")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."html/form/".$row["F_id"].".html]]></loc><priority>0.5</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
if(strpos($lang,"1")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."ehtml/form/".$row["F_id"].".html]]></loc><priority>0.5</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
if(strpos($lang,"2")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."fhtml/form/".$row["F_id"].".html]]></loc><priority>0.5</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
}
}
}
$sql="select * from ".TABLE."nsort where S_del=0 order by S_order,S_id desc";

$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {

$sql2="select count(N_id) as N_count from ".TABLE."news where N_sort=".$row["S_id"]." and N_del=0";
$result2 = mysqli_query($conn, $sql2);
$row2 = mysqli_fetch_assoc($result2);
$N_count=$row2["N_count"];
$page_num=floor($N_count/$C_npage)+1;
if($N_count % $C_npage ==0){
$page_num=$page_num-1;
}
if($C_html==0){
for($q=1;$q<= $page_num;$q++){
if(strpos($lang,"0")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."?type=news&S_id=".$row["S_id"]."&page=".$q."&lang=cn]]></loc><priority>0.5</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
if(strpos($lang,"1")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."?type=news&S_id=".$row["S_id"]."&page=".$q."&lang=en]]></loc><priority>0.5</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
if(strpos($lang,"2")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."?type=news&S_id=".$row["S_id"]."&page=".$q."&lang=cht]]></loc><priority>0.5</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
}
}else{
if(strpos($lang,"0")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."html/news/list-".$row["S_id"].".html]]></loc><priority>0.5</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
if(strpos($lang,"1")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."ehtml/news/list-".$row["S_id"].".html]]></loc><priority>0.5</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
if(strpos($lang,"2")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."fhtml/news/list-".$row["S_id"].".html]]></loc><priority>0.5</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
for($q=1;$q<= $page_num;$q++){
if(strpos($lang,"0")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."html/news/list-".$row["S_id"]."-".$q.".html]]></loc><priority>0.5</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
if(strpos($lang,"1")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."ehtml/news/list-".$row["S_id"]."-".$q.".html]]></loc><priority>0.5</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
if(strpos($lang,"2")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."fhtml/news/list-".$row["S_id"]."-".$q.".html]]></loc><priority>0.5</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
}
}
}
}
$sql="select * from ".TABLE."news where N_del=0 order by N_id desc";

$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
if($C_html==0){
if(strpos($lang,"0")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."?type=newsinfo&S_id=".$row["N_id"]."&lang=cn]]></loc><priority>0.5</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
if(strpos($lang,"1")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."?type=newsinfo&S_id=".$row["N_id"]."&lang=en]]></loc><priority>0.5</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
if(strpos($lang,"2")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."?type=newsinfo&S_id=".$row["N_id"]."&lang=cht]]></loc><priority>0.5</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
}else{
if(strpos($lang,"0")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."html/news/".$row["N_id"].".html]]></loc><priority>0.5</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
if(strpos($lang,"1")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."ehtml/news/".$row["N_id"].".html]]></loc><priority>0.5</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
if(strpos($lang,"2")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."fhtml/news/".$row["N_id"].".html]]></loc><priority>0.5</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
}
}
}
$sql="select * from ".TABLE."psort where S_del=0 order by S_id desc";

$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
if($S_sub!==0){
$sql2="select count(P_id) as P_count from ".TABLE."product where P_del=0 and P_sort=".$row["S_id"];
}else{
$sql2="select count(P_id) as P_count from ".TABLE."product,".TABLE."psort where P_del=0 and P_sort=S_id and S_del=0 and S_sub=".$row["S_id"];
}
$result2 = mysqli_query($conn, $sql2);
$row2 = mysqli_fetch_assoc($result2);
$P_count=$row2["P_count"];
$page_num=floor($P_count/$C_ppage)+1;
if($P_count % $C_ppage ==0){
$page_num=$page_num-1;
}
if($C_html==0){
for($q=1;$q<= $page_num;$q++){
if(strpos($lang,"0")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."?type=product&S_id=".$row["S_id"]."&page=".$q."&lang=cn]]></loc><priority>0.5</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
if(strpos($lang,"1")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."?type=product&S_id=".$row["S_id"]."&page=".$q."&lang=en]]></loc><priority>0.5</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
if(strpos($lang,"2")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."?type=product&S_id=".$row["S_id"]."&page=".$q."&lang=cht]]></loc><priority>0.5</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
}
}else{
if(strpos($lang,"0")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."html/product/list-".$row["S_id"].".html]]></loc><priority>0.5</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
if(strpos($lang,"1")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."ehtml/product/list-".$row["S_id"].".html]]></loc><priority>0.5</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
if(strpos($lang,"2")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."fhtml/product/list-".$row["S_id"].".html]]></loc><priority>0.5</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
for($q=1;$q<= $page_num;$q++){
if(strpos($lang,"0")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."html/product/list-".$row["S_id"]."-".$q.".html]]></loc><priority>0.5</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
if(strpos($lang,"1")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."ehtml/product/list-".$row["S_id"]."-".$q.".html]]></loc><priority>0.5</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
if(strpos($lang,"2")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."fhtml/product/list-".$row["S_id"]."-".$q.".html]]></loc><priority>0.5</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
}
}
}
}
$sql="select * from ".TABLE."product where P_del=0 order by P_id desc";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
if($C_html==0){
if(strpos($lang,"0")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."?type=productinfo&S_id=".$row["P_id"]."&lang=cn]]></loc><priority>0.5</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
if(strpos($lang,"1")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."?type=productinfo&S_id=".$row["P_id"]."&lang=en]]></loc><priority>0.5</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
if(strpos($lang,"2")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."?type=productinfo&S_id=".$row["P_id"]."&lang=cht]]></loc><priority>0.5</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
}else{
if(strpos($lang,"0")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."html/product/".$row["P_id"].".html]]></loc><priority>0.5</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
if(strpos($lang,"1")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."ehtml/product/".$row["P_id"].".html]]></loc><priority>0.5</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
if(strpos($lang,"2")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."fhtml/product/".$row["P_id"].".html]]></loc><priority>0.5</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
}
}
}
if($C_html==0){
if(strpos($lang,"0")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."?type=contact&lang=cn]]></loc><priority>1.00</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."?type=guestbook&lang=cn]]></loc><priority>1.00</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
if(strpos($lang,"1")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."?type=contact&lang=en]]></loc><priority>1.00</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."?type=guestbook&lang=en]]></loc><priority>1.00</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
if(strpos($lang,"2")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."?type=contact&lang=cht]]></loc><priority>1.00</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."?type=guestbook&lang=cht]]></loc><priority>1.00</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
}else{
if(strpos($lang,"0")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."html/contact/index.html]]></loc><priority>1.00</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."html/guestbook/index.html]]></loc><priority>1.00</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
if(strpos($lang,"1")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."ehtml/contact/index.html]]></loc><priority>1.00</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."ehtml/guestbook/index.html]]></loc><priority>1.00</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
if(strpos($lang,"2")!==false){
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."fhtml/contact/index.html]]></loc><priority>1.00</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
    $sitemap=$sitemap."<url><loc><![CDATA[".gethttp().$C_domain.$C_dir."fhtml/guestbook/index.html]]></loc><priority>1.00</priority><lastmod>".date("Y-m-d",time())."</lastmod><changefreq>weekly</changefreq></url>".PHP_EOL;
}
}
$sitemap=$sitemap."</urlset>";

file_put_contents("../sitemap.xml",$sitemap);
echo "success|地图已生成 http://".$C_domain.$C_dir."sitemap.xml";
lg("生成网站地图");
die();
break;

case "slide_add":

$S_title=$_POST["S_title"];
$S_pic=$_POST["S_pic"];
$S_thumb=$_POST["S_thumb"];
$S_link=$_POST["S_link"];
$S_content=$_POST["S_content"];
if(substr($S_pic,0,5)=="media" || substr($S_pic,0,6)=="images" || substr($S_pic,0,4)=="http"){
$S_pic=$S_pic;
}else{
$S_pic="media/".$S_pic;
}
if($S_pic!=""){
$sql="Insert into ".TABLE."slide(S_title,S_pic,S_content,S_link) values('".lang_add("",$S_title)."','".$S_pic."','".lang_add("",$S_content)."','".$S_link."')";
mysqli_query($conn,$sql);
echo "success|添加成功!";
lg("新增焦点图");
die();
}else{
echo "error|请填全信息!";
die();
}
break;

case "mtype_add":
$T_name=$_POST["T_name"];
$T_content=$_POST["T_content"];
if($T_name!=""){
$sql="Insert into ".TABLE."mtype(T_name,T_content) values('".$T_name."','".$T_content."')";
mysqli_query($conn,$sql);
echo "success|添加成功!";
lg("新增会员类型");
die();
}else{
echo "error|请填全信息!";
die();
}
break;

case "brand_add":
$B_title=$_POST["B_title"];
$B_content=$_POST["B_content"];
$B_pic=$_POST["B_pic"];
if($B_title!=""){
$sql="Insert into ".TABLE."brand(B_title,B_content,B_pic) values('".$B_title."','".$B_content."','".$B_pic."')";
mysqli_query($conn,$sql);
echo "success|添加成功!";
lg("新增品牌");
die();
}else{
echo "error|请填全信息!";
die();
}
break;

case "slide_del":
mysqli_query($conn,"update ".TABLE."slide set S_del=1 where S_id=".intval($_GET["S_id"]));
lg("将焦点图放入回收站（ID:".$_GET["S_id"]."）");
echo "success|放入回收站成功!|".$_GETT["S_id"];
die();
break;

case "slide_delall":

$id=$_POST["id"];
if(count($id)>0){
$shu=0 ;
for ($i=0; $i< count($id);$i++ ){
mysqli_query($conn,"update ".TABLE."slide set S_del=1 where S_id=".$id[$i]);
$shu=$shu+1;
$ids=$ids.$id[$i].",";
        }
$ids= substr($ids,0,strlen($ids)-1);
if($shu>0){ ;
echo "success|成功删除".$shu."个焦点图|".$ids;
die();
}else{ ;
echo "error|删除失败";
die();
} 
}else{
echo "error|未选择要删除的内容";
die();
}
break;

case "slide_edit":

$S_id=intval($_GET["S_id"]);
$S_title=$_POST["S_title"];
$S_pic=$_POST["S_pic"];
$S_thumb=$_POST["S_thumb"];
$S_link=$_POST["S_link"];
$S_content=$_POST["S_content"];
if(substr($S_pic,0,5)=="media" || substr($S_pic,0,6)=="images" || substr($S_pic,0,4)=="http"){
    $S_pic=$S_pic;
}else{
    $S_pic="media/".$S_pic;
}
if(substr($S_thumb,0,5)=="media" || substr($S_thumb,0,6)=="images" || substr($S_thumb,0,4)=="http"){
    $S_thumb=$S_thumb;
}else{
    $S_thumb="media/".$S_thumb;
}
if($S_pic!=""){

mysqli_query($conn,"update ".TABLE."slide set 
    S_title='".lang_add(getrx("select * from ".TABLE."slide where S_id=".$S_id,"S_title"),$S_title)."',
    S_pic='".$S_pic."',
    S_thumb='".$S_thumb."',
    S_link='".$S_link."',
    S_content='".lang_add(getrx("select * from ".TABLE."slide where S_id=".$S_id,"S_content"),$S_content)."'
    where S_id=".$S_id
    );

echo "success|修改成功!";
lg("修改焦点图（ID：".$S_id."）");
die();
}else{
echo "error|请填全信息!";
die();
}
break;

case "mtype_edit":
$T_id=intval($_GET["T_id"]);
$T_name=$_POST["T_name"];
$T_content=$_POST["T_content"];
if($T_name!=""){
mysqli_query($conn,"update ".TABLE."mtype set 
    T_name='".$T_name."',
    T_content='".$T_content."'
    where T_id=".$T_id
    );
echo "success|修改成功!";
lg("修改会员类型（ID：".$T_id."）");
die();
}else{
echo "error|请填全信息!";
die();
}
break;

case "brand_edit":
$B_id=intval($_GET["B_id"]);
$B_title=$_POST["B_title"];
$B_content=$_POST["B_content"];
$B_pic=$_POST["B_pic"];
if($B_title!=""){
mysqli_query($conn,"update ".TABLE."brand set 
    B_title='".$B_title."',
    B_pic='".$B_pic."',
    B_content='".$B_content."'
    where B_id=".$B_id
    );
echo "success|修改成功!";
lg("修改品牌（ID：".$B_id."）");
die();
}else{
echo "error|请填全信息!";
die();
}
break;

case "slide_save":

foreach ($_POST as $x=>$value) {
if(splitx($x,"_",0)=="title"){
mysqli_query($conn,"update ".TABLE."slide set S_title='".lang_add(getrx("select * from ".TABLE."slide where S_id=".splitx($x,"_",1),"S_title"),$_POST[$x])."' where S_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="order"){
mysqli_query($conn,"update ".TABLE."slide set S_order=".$_POST[$x]." where S_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="pic"){
if(substr($_POST[$x],0,5)=="media" || substr($_POST[$x],0,6)=="images" || substr($_POST[$x],0,4)=="http"){
$pic=$_POST[$x];
}else{
$pic="media/".$_POST[$x];
}
mysqli_query($conn,"update ".TABLE."slide set S_pic='".$pic."' where S_id=".splitx($x,"_",1));
}
} 
echo "success|修改成功!";
lg("保存焦点图（在列表页）");
die();
break;

case "zmt_article":
$zmt=$_POST["zmt"];
$N_id=intval($_POST["N_id"]);

for($i=0;$i<count($zmt);$i++){
    if($zmt[$i]=="0"){
        $info1=ts("0",$N_id);
    }

    if($zmt[$i]=="1"){
        $info2=ts("1",$N_id);
    }

    if($zmt[$i]=="2"){
        $info3=ts("2",$N_id);
    }
}

echo "success|推送成功!".$info1.$info2.$info3;
lg("自媒体推送文章");
die();
break;
case "zmt_edit":
$C_wx_appid=$_POST["C_wx_appid"];
$C_wx_appsecret=$_POST["C_wx_appsecret"];
$C_qe_id=$_POST["C_qe_id"];
$C_qe_key=$_POST["C_qe_key"];
$C_bj_id=$_POST["C_bj_id"];
$C_bj_key=$_POST["C_bj_key"];

mysqli_query($conn,"update ".TABLE."config set C_wx_appid='".$C_wx_appid."',C_qe_id='".$C_qe_id."',C_bj_id='".$C_bj_id."'");

if($C_wx_appsecret!=""){
    mysqli_query($conn,"update ".TABLE."config set C_wx_appsecret='".$C_wx_appsecret."'");
}
if($C_qe_key!=""){
    mysqli_query($conn,"update ".TABLE."config set C_qe_key='".$C_qe_key."'");
}
if($C_bj_key!=""){
    mysqli_query($conn,"update ".TABLE."config set C_bj_key='".$C_bj_key."'");
}

lg("编辑自媒体API");
echo "success|修改成功";
break;


case "mtype_del":
mysqli_query($conn,"delete from ".TABLE."mtype where T_id=".intval($_GET["T_id"]));
lg("删除会员类型（ID:".$_GET["T_id"]."）");
echo "success|删除成功!|".$_GET["T_id"];
die();
break;

case "mtype_delall":

$id=$_POST["id"];
if(count($id)>0){
$shu=0 ;
for ($i=0; $i< count($id);$i++ ){
mysqli_query($conn,"delete from ".TABLE."mtype where T_id=".$id[$i]);
$shu=$shu+1;
$ids=$ids.$id[$i].",";
        }
$ids= substr($ids,0,strlen($ids)-1);
if($shu>0){ ;
echo "success|成功删除".$shu."个会员类型|".$ids;
die();
}else{ ;
echo "error|删除失败";
die();
} 
}else{
echo "error|未选择要删除的内容";
die();
}
break;
case "mtype_save":

foreach ($_POST as $x=>$value) {
if(splitx($x,"_",0)=="name"){
mysqli_query($conn,"update ".TABLE."mtype set T_name='".$_POST[$x]."' where T_id=".splitx($x,"_",1));
}

} 
echo "success|修改成功!";
lg("保存会员类型（在列表页）");
die();
break;

case "brand_del":
mysqli_query($conn,"delete from ".TABLE."brand where B_id=".intval($_GET["B_id"]));
lg("删除品牌（ID:".$_GET["B_id"]."）");
echo "success|删除成功!|".$_GET["B_id"];
die();
break;

case "brand_delall":

$id=$_POST["id"];
if(count($id)>0){
$shu=0 ;
for ($i=0; $i< count($id);$i++ ){
mysqli_query($conn,"delete from ".TABLE."brand where B_id=".$id[$i]);
$shu=$shu+1;
$ids=$ids.$id[$i].",";
        }
$ids= substr($ids,0,strlen($ids)-1);
if($shu>0){ ;
echo "success|成功删除".$shu."个品牌|".$ids;
die();
}else{ ;
echo "error|删除失败";
die();
} 
}else{
echo "error|未选择要删除的内容";
die();
}
break;
case "brand_save":

foreach ($_POST as $x=>$value) {
if(splitx($x,"_",0)=="name"){
    mysqli_query($conn,"update ".TABLE."brand set B_title='".$_POST[$x]."' where B_id=".splitx($x,"_",1));
}
if(splitx($x,"_",0)=="pic"){
    mysqli_query($conn,"update ".TABLE."brand set B_pic='".$_POST[$x]."' where B_id=".splitx($x,"_",1));
}
} 
echo "success|修改成功!";
lg("保存品牌（在列表页）");
die();
break;


case "sql_clear":

$sql="select * from ".TABLE."product";
$result = mysqli_query($conn,  $sql);
if (mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
    if (getrx("select * from ".TABLE."psort where S_id=".$row["P_sort"],"S_id")==""){
        mysqli_query($conn,"delete from ".TABLE."product where P_sort=".$row["P_sort"]);
    }
   }
}
$sql="select * from ".TABLE."news";
$result = mysqli_query($conn,  $sql);
if (mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
    if (getrx("select * from ".TABLE."nsort where S_id=".$row["N_sort"],"S_id")==""){
        mysqli_query($conn,"delete from ".TABLE."news where N_sort=".$row["N_sort"]);
    }
   }
}
$sql="select * from ".TABLE."link";
$result = mysqli_query($conn,  $sql);
if (mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
    if (getrx("select * from ".TABLE."lsort where S_id=".$row["L_sort"],"S_id")==""){
        mysqli_query($conn,"delete from ".TABLE."link where L_sort=".$row["L_sort"]);
    }
   }
}
$sql="select * from ".TABLE."bbs";
$result = mysqli_query($conn,  $sql);
if (mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
    if (getrx("select * from ".TABLE."bsort where S_id=".$row["B_sort"],"S_id")==""){
        mysqli_query($conn,"delete from ".TABLE."bbs where B_sort=".$row["B_sort"]);
    }
   }
}
$sql="select * from ".TABLE."query";
$result = mysqli_query($conn,  $sql);
if (mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
    if (getrx("select * from ".TABLE."qsort where S_id=".$row["Q_nort"],"S_id")==""){
        mysqli_query($conn,"delete from ".TABLE."query where Q_sort=".$row["Q_sort"]);
    }
   }
}
echo "success|执行成功!";
lg("清除冗余数据");
die();
break;

case "sql_sql":

mysqli_query($conn,$_POST["sql"]);
echo "success|执行成功!";
lg("执行SQL");
die();
break;

case "template_del":

$D_id=$_GET["T_id"];
if($D_id==$C_template || $D_id==$C_wap){
echo "error|该模板正在应用中，无法删除!";
die();
}
removeDir("../".$_REQUEST["typex"]."/".$D_id);
echo "success|删除模板成功!|".$_REQUEST["T_id"];
lg("删除模板（ID：".$_REQUEST["T_id"]."）");
die();
break;

case "template_down":
$T_id=$_REQUEST["T_id"];
if(substr($T_id,0,2)=="pc"){
    $T_type="pc";
}else{
    $T_type="wap";
}

if(check_auth2($T_id)){
download($T_type,$T_id);

echo "success|模板安装成功！";
lg("安装模板成功（ID：".$T_id."）");
die();
}else{
echo "error|您还未购买此模板！";
die();
}
break;

case "template_edit":

$T_content=stripslashes($_POST["T_description"]);
$T_id=$_POST["no"];
$T_type=$_POST["name"];
file_put_contents("../pc/".$T_id."/".$T_type.".tpl",$T_content);
lg("修改模板文件 ".$T_id."/".$T_type);
die("success|模板修改成功！");
break;

case "template_refresh":

$T_id = $_GET["T_id"];

if(substr($T_id,0,1)=="p"){
    $t="pc";
}else{
    $t="wap";
}

if (check_auth2($T_id)) {
    rename("../".$t."/" . $T_id, "../".$t."/" . $T_id . "x");
    download($t, $T_id);
    removeDir("../".$t."/" . $T_id . "/" . "images");
    if(strpos($t,"..")!==false && strpos($T_id,"..")!==false){
    	unlink("../".$t."/" . $T_id . "/config.xml");
    	unlink("../".$t."/" . $T_id . "/config_e.xml");
    }
    dir_mkdir("../".$t."/" . $T_id . "/images");
    copyF("../".$t."/" . $T_id . "x/images", "../".$t."/" . $T_id . "/images");
    copy("../".$t."/" . $T_id . "x/config.xml", "../".$t."/" . $T_id . "/config.xml");
    copy("../".$t."/" . $T_id . "x/config_e.xml", "../".$t."/" . $T_id . "/config_e.xml");
    removeDir("../".$t."/" . $T_id . "x");
    
    echo "success|模板更新成功！";
    lg("更新模板成功（ID：" . $T_id . "）");
    die();
}else{
    die("error|您未购买此模板");
}
break;

case "template_save":
$template = $_POST["C_template"];
if(check_auth2($template)){
    $tt=true;
}else{
	$tt=false;
    die("error|尚未购买此套模板");
}

if($_REQUEST["typex"]=="pc"){
	$typex="template";
}else{
	$typex="wap";
}
if($tt){
    mysqli_query($conn, "update ".TABLE."config set C_" . $typex . "='" . $template . "'");
    if (is_file("../" . $_REQUEST["typex"] . "/" . $template . "/sort.txt")) {
        $T_sort = trim(file_get_contents("../" . $_REQUEST["typex"] . "/" . $template . "/sort.txt") , "\xEF\xBB\xBF");
    } else {
        $T_sort = 1;
    }
    
    switch ($T_sort) {
        case 1:
            $sort_info = "企业";
            break;
        case 2:
            $sort_info = "门户/博客/文章";
            break;
        case 3:
            $sort_info = "政府";
            break;
        case 4:
            $sort_info = "学校";
            break;
        case 5:
            $sort_info = "医院";
            break;
    }

    if ($_REQUEST["typex"] == "pc") {
        mysqli_query($conn, "update ".TABLE."config set C_sort=" . $T_sort);
    }

    echo "success|更换模板成功！后台切换为模式[" . $sort_info . "]";
    lg("更换模板（ID：" . $template . "）");
    die();

} else {
    echo "error|未选择模板！";
    die();
}
break;

case "template_sql":

$q1 = mysqli_query($conn, "show tables");
while ($t = mysqli_fetch_array($q1)) {
    $table = $t[0];
    if(strpos(strtolower($table),strtolower(TABLE))!==false){
    $q2 = mysqli_query($conn, "show create table `$table`");
    $sql = mysqli_fetch_array($q2);
    $mysql.= "DROP TABLE IF EXISTS `$table`" . ";\r\n" . $sql['Create Table'] . ";\r\n";
    $q3 = mysqli_query($conn, "select * from `$table`");
    while ($data = mysqli_fetch_assoc($q3)) {
        $keys = array_keys($data);
        $keys = array_map('addslashes', $keys);
        $keys = join('`,`', $keys);
        $keys = "`" . $keys . "`";
        $vals = array_values($data);
        $vals = array_map('addslashes', $vals);
        $vals = join("','", $vals);
        $vals = "'" . $vals . "'";
        $mysql.= "insert into `$table`($keys) values($vals);\r\n";
    }
}
}
$mysql = str_replace('CREATE TABLE', 'CREATE TABLE IF NOT EXISTS', $mysql);
$mysql = "-- 备份时间：" . date('Y-m-d H:i:s') . " 域名：" . $_SERVER["HTTP_HOST"] . " 备份者：" . $_SESSION["user"] . " 程序版本：" . file_get_contents("version.txt") . " 电脑端：" . $C_template . " 手机端：" . $C_wap . " --;\r\n" . $mysql;
if(is_dir("../backup/")){
	file_put_contents("../backup/" . gen_key(20) . "_backup.txt", str_replace("sl_","SL_",$mysql));
}else{
	die("error|backup文件夹不存在，请检查根目录!");
}


$T_id=$_GET["T_id"];
    if(is_file("../pc/".$T_id."/php.sql")==false){
        $strLocalPath="../pc/".$T_id."/";
        $GLOBALS['xml']=GetHttpContent('http://scms5.oss-cn-shenzhen.aliyuncs.com/data/data_'.$T_id.'.xml');
        if ($GLOBALS['xml']) {
            $xml = simplexml_load_string($GLOBALS['xml'],'SimpleXMLElement');
            $old = umask(0);
            foreach ($xml->file as $f) {
                $filename=$strLocalPath.$f->path;
                $filename=str_replace('\\','/',$filename);
                $dirname= dirname($filename);
                if(!is_dir($dirname)){
                    mkdir($dirname,0755,true);
                }
                $fn=$filename;
                file_put_contents($fn,base64_decode($f->stream));
            }

            umask($old);
        } else {
            echo "success|该套模板无需导入!";
            die();
        }

    }

    recurse_copy("../pc/".$T_id."/media","../media");

    $sql=file_get_contents("../pc/".$T_id."/php.sql");
    $sql=str_replace("Text default ''","Text",$sql);

    if(strpos($sql,";\r\n")!==false){
        $sql=explode(";\r\n",trim($sql,"\xEF\xBB\xBF"));
    }else{
        $sql=explode(";\n",trim($sql,"\xEF\xBB\xBF"));
    }

    for ($r=0 ;$r< count($sql);$r++){
        @mysqli_query($conn,str_replace("SL_",TABLE,$sql[$r]));
    }

    $update=file_get_contents("http://scms5.oss-cn-shenzhen.aliyuncs.com/php/update.txt");
    $update=str_replace(PHP_EOL,"",$update);
    $update=trim($update,"\xEF\xBB\xBF");
    @ready(trim(splitx($update,"|",4),"\xEF\xBB\xBF"));

    echo "success|备份原数据并导入新数据成功!";
    lg("导入模板数据");
    die();
break;

case "testmail_":

$data1=$_POST["data1"];
$data2=$_POST["data2"];
$data3=$_POST["data3"];

$info=sendmail($data2,$data3,$data1) ;
echo "success|邮件已发送，请登录邮箱检查邮件是否收到".$info;
die();
break;

case "testmobile_":

$data1=$_POST["data1"];
sendmobile("【".$C_smssign."】您的验证码为123456；1分钟内有效,请尽快验证！",$data1);
echo "success|短信已发送，请检查是否收到";
die();
break;

case "text_add":

$T_title=$_POST["T_title"];
$T_entitle=$_POST["T_entitle"];
$T_content=str_Replace("\"".$C_dir,"\"{@SL_安装目录}",$_POST["T_content"]);
$T_pic=$_POST["T_pic"];
$T_pagetitle=$_POST["T_pagetitle"];
$T_keywords=$_POST["T_keywords"];
$T_description=$_POST["T_description"];
$T_link=$_POST["T_link"];
$U_sub=$_POST["U_sub"];
if($T_description==""){
    $T_description=mb_substr(strip_tags($T_content),0,100,"utf-8");
}
if(substr($T_pic,0,5)=="media" || substr($T_pic,0,6)=="images" || substr($T_pic,0,4)=="http"){
$T_pic=$T_pic;
}else{
$T_pic="media/".$T_pic;
}
if($T_title!="" and $T_content!="" and $T_pic!=""){
mysqli_query($conn,"insert into ".TABLE."text(
    T_title,
    T_entitle,
    T_content,
    T_pic,
    T_link,
    T_pagetitle,
    T_keywords,
    T_description
    )
    values(
        '".lang_add("",$T_title)."',
        '".lang_add("",$T_entitle)."',
        '".lang_add("",$T_content)."',
        '".$T_pic."',
        '".$T_link."',
        '".lang_add("",$T_pagetitle)."',
        '".lang_add("",$T_keywords)."',
        '".lang_add("",$T_description)."'
    )");

$sql="Select * from ".TABLE."text order by T_id desc limit 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if (mysqli_num_rows($result) > 0) {
    $T_id=$row["T_id"];
}

if($U_sub!="x"){
    mysqli_query($conn,"insert into ".TABLE."menu(U_title,U_entitle,$U_order,$U_sub,U_ico,U_type,U_typeid) values('".$T_title."','".$T_entitle."',99,".$U_sub.",'bars','text',".$T_id.")");
}

if($C_html==1){
    creat_index(langtonum());
    creat_text(langtonum(),$T_id);
}

a("text", $T_id,"template");

lg("新增简介");
echo "success|添加成功！";
die();

}else{
echo "error|请填全信息！";
die();
}
break;

case "text_addmenu":

$sql="select * from ".TABLE."menu where U_sub=0 order by U_order desc limit 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$U_order=$row["U_order"];
}
$sql="select * from ".TABLE."text where T_id=".intval($_GET["T_id"]);
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows($result) > 0) {
$T_title=$row["T_title"];
$T_entitle=$row["T_entitle"];
}
mysqli_query($conn,"insert into ".TABLE."menu(U_title,U_entitle,$U_order,$U_sub,U_ico,U_type,U_typeid) values('".$T_title."','".$T_entitle."',".($U_order+1).",0,'bars','text',".$_GET["T_id"].")");
echo "success|添加成功！";
lg("将简介添加到主菜单（ID：".$_GET["T_id"]."）");
die();
break;

case "text_del":
mysqli_query($conn,"update ".TABLE."text set T_del=1 where T_id=".intval($_GET["T_id"])); 
echo "success|放入回收站成功!|".$_GET["T_id"];
lg("将简介放入回收站（ID：".$_GET["T_id"]."）");
die();
break;

case "text_delall":

$id=$_POST["id"];
    if(count($id)>0){
        $shu=0;
        for ($i=0;$i<count($id);$i++){
            mysqli_query($conn,"update ".TABLE."text set T_del=1 where T_id=".$id[$i]);
            $shu=$shu+1;
            $ids=$ids.$id[$i].",";
        }
        $ids= substr($ids,0,strlen($ids)-1);
        if($shu>0){
            echo "success|成功删除".$shu."个简介|".$ids;
            lg("批量删除简介");
            die();
        }else{ 
            echo "error|删除失败";
            die();
        } 
    }else{
        echo "error|未选择要删除的内容";
        die();
    }
break;

case "data_edittable":
$table=$_POST["table"];

if(strpos($table,"_")===false){
    die("error|表前缀需带有下划线（_）！");
}else{
    mysqli_query($conn,"alter table ".TABLE."admin rename ".$table."admin;");
    mysqli_query($conn,"alter table ".TABLE."bbs rename ".$table."bbs;");
    mysqli_query($conn,"alter table ".TABLE."bsort rename ".$table."bsort;");
    mysqli_query($conn,"alter table ".TABLE."collection rename ".$table."collection;");
    mysqli_query($conn,"alter table ".TABLE."comment rename ".$table."comment;");
    mysqli_query($conn,"alter table ".TABLE."config rename ".$table."config;");
    mysqli_query($conn,"alter table ".TABLE."contact rename ".$table."contact;");
    mysqli_query($conn,"alter table ".TABLE."content rename ".$table."content;");
    mysqli_query($conn,"alter table ".TABLE."event rename ".$table."event;");
    mysqli_query($conn,"alter table ".TABLE."form rename ".$table."form;");
    mysqli_query($conn,"alter table ".TABLE."guestbook rename ".$table."guestbook;");
    mysqli_query($conn,"alter table ".TABLE."invoice rename ".$table."invoice;");
    mysqli_query($conn,"alter table ".TABLE."link rename ".$table."link;");
    mysqli_query($conn,"alter table ".TABLE."list rename ".$table."list;");
    mysqli_query($conn,"alter table ".TABLE."log rename ".$table."log;");
    mysqli_query($conn,"alter table ".TABLE."lsort rename ".$table."lsort;");
    mysqli_query($conn,"alter table ".TABLE."lv rename ".$table."lv;");
    mysqli_query($conn,"alter table ".TABLE."member rename ".$table."member;");
    mysqli_query($conn,"alter table ".TABLE."menu rename ".$table."menu;");
    mysqli_query($conn,"alter table ".TABLE."news rename ".$table."news;");
    mysqli_query($conn,"alter table ".TABLE."nsort rename ".$table."nsort;");
    mysqli_query($conn,"alter table ".TABLE."orders rename ".$table."orders;");
    mysqli_query($conn,"alter table ".TABLE."oss rename ".$table."oss;");
    mysqli_query($conn,"alter table ".TABLE."product rename ".$table."product;");
    mysqli_query($conn,"alter table ".TABLE."psort rename ".$table."psort;");
    mysqli_query($conn,"alter table ".TABLE."qsort rename ".$table."qsort;");
    mysqli_query($conn,"alter table ".TABLE."query rename ".$table."query;");
    mysqli_query($conn,"alter table ".TABLE."reply rename ".$table."reply;");
    mysqli_query($conn,"alter table ".TABLE."response rename ".$table."response;");
    mysqli_query($conn,"alter table ".TABLE."safe rename ".$table."safe;");
    mysqli_query($conn,"alter table ".TABLE."slide rename ".$table."slide;");
    mysqli_query($conn,"alter table ".TABLE."text rename ".$table."text;");
    mysqli_query($conn,"alter table ".TABLE."wap rename ".$table."wap;");
    mysqli_query($conn,"alter table ".TABLE."wapslide rename ".$table."wapslide;");
    mysqli_query($conn,"alter table ".TABLE."wmenu rename ".$table."wmenu;");
    mysqli_query($conn,"alter table ".TABLE."brand rename ".$table."brand;");
    mysqli_query($conn,"alter table ".TABLE."mtype rename ".$table."mtype;");

	$json=json_decode(file_get_contents("../data/config.json"),true);
	$json["table"]=$table;
	file_put_contents("../data/config.json",json_encode($json));

    lg("修改数据表前缀为".$table);
    die("success|修改成功！");
}

break;

case "text_edit":

if(strpos($_COOKIE["textauth"],"all")!==false){
$auth_info="";
}else{
$textauth=explode(",",$_COOKIE["textauth"]);
for ($i=0 ;$i<count($textauth)-1;$i++){
$tj=$tj."or T_id=".$textauth[$i]." ";
}
if($tj==""){
$auth_info=" and T_id<0";
}else{
$tj= substr($tj,-(strlen($tj)-3));
$auth_info=" and (".$tj.")";
}
}
$T_id=intval($_GET["T_id"]);
$T_title=$_POST["T_title"];
$T_entitle=$_POST["T_entitle"];
$T_content=str_Replace("\"".$C_dir,"\"{@SL_安装目录}",$_POST["T_content"]);
$T_pic=$_POST["T_pic"];
$T_pagetitle=$_POST["T_pagetitle"];
$T_keywords=$_POST["T_keywords"];
$T_description=$_POST["T_description"];
$T_link=$_POST["T_link"];
if($T_description==""){
$T_description=mb_substr(strip_tags($T_content),0,100,"utf-8");
}
if(substr($T_pic,0,5)=="media" || substr($T_pic,0,6)=="images" || substr($T_pic,0,4)=="http"){
$T_pic=$T_pic;
}else{
$T_pic="media/".$T_pic;
}
if($T_title!="" && $T_content!="" && $T_pic!="" ){

mysqli_query($conn,"update ".TABLE."text set
    T_title='".lang_add(getrx("select * from ".TABLE."text where T_id=".$T_id,"T_title"),$T_title)."',
    T_entitle='".lang_add(getrx("select * from ".TABLE."text where T_id=".$T_id,"T_entitle"),$T_entitle)."',
    T_content='".lang_add(getrx("select * from ".TABLE."text where T_id=".$T_id,"T_content"),$T_content)."',
    T_pic='".$T_pic."',
    T_link='".$T_link."',
    T_pagetitle='".lang_add(getrx("select * from ".TABLE."text where T_id=".$T_id,"T_pagetitle"),$T_pagetitle)."',
    T_keywords='".lang_add(getrx("select * from ".TABLE."text where T_id=".$T_id,"T_keywords"),$T_keywords)."',
    T_description='".lang_add(getrx("select * from ".TABLE."text where T_id=".$T_id,"T_description"),$T_description)."'
    where T_id=".$T_id.$auth_info
);

if($C_html==1){
	creat_index(langtonum());
	creat_text(langtonum(),$T_id);
}
lg("编辑简介（ID：".$T_id."）");
a("text", $T_id,"template");
echo "success|修改成功！|"."update ".TABLE."text set
    T_title='".lang_add(getrx("select * from ".TABLE."text where T_id=".$T_id,"T_title"),$T_title)."',
    T_entitle='".lang_add(getrx("select * from ".TABLE."text where T_id=".$T_id,"T_entitle"),$T_entitle)."',
    T_content='".lang_add(getrx("select * from ".TABLE."text where T_id=".$T_id,"T_content"),$T_content)."',
    T_pic='".$T_pic."',
    T_link='".$T_link."',
    T_pagetitle='".lang_add(getrx("select * from ".TABLE."text where T_id=".$T_id,"T_pagetitle"),$T_pagetitle)."',
    T_keywords='".lang_add(getrx("select * from ".TABLE."text where T_id=".$T_id,"T_keywords"),$T_keywords)."',
    T_description='".lang_add(getrx("select * from ".TABLE."text where T_id=".$T_id,"T_description"),$T_description)."'
    where T_id=".$T_id.$auth_info;
die();
}else{
echo "error|请填全信息！";
die();
}
break;

case "text_save":

foreach ($_POST as $x=>$value) {
        if(splitx($x,"_",0)=="title"){
            mysqli_query($conn,"update ".TABLE."text set T_title='".lang_add(getrx("select * from ".TABLE."text where T_id=".splitx($x,"_",1),"T_title"),$_POST[$x])."' where T_id=".splitx($x,"_",1));
        }
        if(splitx($x,"_",0)=="entitle"){
            mysqli_query($conn,"update ".TABLE."text set T_entitle='".lang_add(getrx("select * from ".TABLE."text where T_id=".splitx($x,"_",1),"T_entitle"),$_POST[$x])."' where T_id=".splitx($x,"_",1));
        }
        if(splitx($x,"_",0)=="order"){
            mysqli_query($conn,"update ".TABLE."text set T_order=".$_POST[$x]." where T_id=".splitx($x,"_",1));
        }
        if(splitx($x,"_",0)=="pic"){
            if(substr($_POST[$x],0,5)=="media" || substr($_POST[$x],0,6)=="images" || substr($_POST[$x],0,4)=="http"){
                $pic=$_POST[$x];
            }else{
                $pic="media/".$_POST[$x];
            }
            mysqli_query($conn,"update ".TABLE."text set T_pic='".$pic."' where T_id=".splitx($x,"_",1));
        }
    }
    if($C_html==1){
        creat_index(langtonum());
        creat_text(langtonum(),"");
    }
    lg("保存简介（在列表页操作）");
    echo "success|修改成功!";
    die();
break;

case "tohtml_creat":
ready(plug("x1","2"));
break;

case "wxapp_creat":
ready(plug("wxapp","1"));
break;

case "wxapp_edit":
ready(plug("wxapp","2"));
break;

case "w_config_edit":
ready(plug("x5","1"));
break;

case "w_event_add":
ready(plug("x5","2"));
break;

case "w_event_del":
ready(plug("x5","3"));
break;

case "w_event_edit":
ready(plug("x5","4"));
break;

case "w_menu_add":
ready(plug("x5","5"));
break;

case "w_menu_del":
ready(plug("x5","6"));
break;

case "w_menu_edit":
ready(plug("x5","7"));
break;

case "w_menu_refresh":
ready(plug("x5","8"));
break;

case "w_menu_save":
ready(plug("x5","9"));
break;

case "w_menu_send":
ready(plug("x5","10"));
break;

case "w_msg_send":
ready(plug("x5","11"));
break;

case "w_reply_add":
ready(plug("x5","12"));
break;

case "w_reply_del":
ready(plug("x5","13"));
break;

case "w_reply_edit":
ready(plug("x5","14"));
break;

case "w_reply_sub":
ready(plug("x5","15"));
break;
    }
}

function validate($temp){
$pattern = "/[^a-zA-Z0-9]/";
if (preg_match($pattern, $temp)){
	return false;
}else{
	return true;
}
}

function lg($str){
	global $conn;
mysqli_query($conn,"insert into ".TABLE."log(L_user,L_time,L_action,L_ip,L_location) values('".$_SESSION["user"]."','".date('Y-m-d H:i:s')."','".$str."','".getip()."','".getlocation(getip())."')");
}
function gl_type($str){
$str=str_Replace("asp","",$str);
$str=str_Replace("asa","",$str);
$str=str_Replace("php","",$str);
$str=str_Replace("aspx","",$str);
$str=str_Replace("jsp","",$str);
return $str;
}


function getmemberlist($tokenx,$next_openid){
$member_list=GetBody("https://api.weixin.qq.com/cgi-bin/user/get?access_token=".$tokenx."&next_openid=".$next_openid,"");
$M_total=json_decode($member_list)->total;
$M_count=json_decode($member_list)->count;
$M_openid=json_decode($member_list)->next_openid;

$member_list=splitx(splitx($member_list,":[",1),"]",0);
$member_list=str_replace("\"","",$member_list);

if($M_count==10000){
$member_list=$member_list.$getmemberlist($tokenx,$M_openid).",";
}
$getmemberlist=$member_list;
return $getmemberlist;
}

function getmembernum($tokenx,$next_openid){
$member_list=GetBody("https://api.weixin.qq.com/cgi-bin/user/get?access_token=".$tokenx."&next_openid=".$next_openid,"");
$M_total=splitx(splitx($member_list,"\"total\":",1),",",0);
$getmembernum=$M_total;
return $getmembernum;
}

function getjsonlist($member_info,$info){
$memberinfo=explode("\"".$info."\":",$member_info);
for ($z=1 ;$z< count($memberinfo);$z++){
$memberinfos=$memberinfos.splitx($memberinfo[$z],",",0).",";
}
$memberinfos=str_Replace("\"","",$memberinfos);
$memberinfos=substr($memberinfos,0,strlen($memberinfos)-1);
$getjsonlist=$memberinfos;
$memberinfos="";
return $getjsonlist;
}

function addFileToZip($path,$zip){
    $handler=opendir($path); //打开当前文件夹由$path指定。
    while(($filename=readdir($handler))!==false){
        if($filename != "." && $filename != ".."){//文件夹文件名字为'.'和‘..’，不要对他们进行操作
            if(is_dir($path."/".$filename)){// 如果读取的某个对象是文件夹，则递归
                addFileToZip($path."/".$filename, $zip);
            }else{ //将文件加入zip对象
                $zip->addFile($path."/".$filename);
            }
        }
    }
    @closedir($path);
}

function sendmsg($member, $tui) {
    global $conn, $C_wx_appid, $C_wx_appsecret, $D_domain, $C_webtitle, $C_ico, $C_admin;
    $D_domain = splitx($_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"], $C_admin, 0);
    $E_type = getrx("select * from ".TABLE."event where E_id=" . $tui,"E_type");
    $E_content = getrx("select * from ".TABLE."event where E_id=" . $tui,"E_content");
    $access_token = json_decode(GetBody("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $C_wx_appid . "&secret=" . $C_wx_appsecret, ""))->access_token;
    switch ($E_type) {
        case "text":
            $sendmsg = GetBody("https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=" . $access_token, "{ \"touser\":\"" . $member . "\",\"msgtype\":\"text\",\"text\": {\"content\":\"" . $E_content . "\" }}");
            break;

        case "article":
            $idx = substr($E_content, -(strlen($E_content) - 1));
            switch (substr($E_content, 0, 1)) {
                case "T":
                    if (getrx("select * from ".TABLE."text where T_id=" . $idx,"T_id") != "") {
                        $E_contents = "{ 
	\"title\":\"" . lang(getrx("select * from ".TABLE."text where T_id=" . $idx,"T_title")) . "\", 
	\"description\":\"" . lang(getrx("select * from ".TABLE."text where T_id=" . $idx,"T_title")) . "\",
	\"url\":\"http://" . $D_domain . "/wap_index.php?type=text&S_id=" . $idx . "\",
	\"picurl\":\"http://" . $D_domain . "/" . getrx("select * from ".TABLE."text where T_id=" . $idx,"T_pic") . "\"
}";
                    }
                    break;

                case "N":
                    if (getrx("select * from ".TABLE."news where N_id=" . $idx,"N_id") != "") {
                        $E_contents = "{ 
	\"title\":\"" . lang(getrx("select * from ".TABLE."news where N_id=" . $idx,"N_title")) . "\", 
	\"description\":\"" . lang(getrx("select * from ".TABLE."news where N_id=" . $idx,"N_title")) . "\",
	\"url\":\"http://" . $D_domain . "/wap_index.php?type=newsinfo&S_id=" . $idx . "\",
	\"picurl\":\"http://" . $D_domain . "/" . getrx("select * from ".TABLE."news where N_id=" . $idx,"N_pic") . "\"
}";
                    }
                    break;

                case "P":
                    if (getrx("select * from ".TABLE."product where P_id=" . $idx,"P_id") != "") {
                        $E_contents = "{ 
	\"title\":\"" . lang(getrx("select * from ".TABLE."product where P_id=" . $idx,"P_title")) . "\", 
	\"description\":\"" . lang(getrx("select * from ".TABLE."product where P_id=" . $idx,"P_title")) . "\",
	\"url\":\"http://" . $D_domain . "/wap_index.php?type=productinfo&S_id=" . $idx . "\",
	\"picurl\":\"http://" . $D_domain . "/" . splitx(splitx(getrx("select * from ".TABLE."product where P_id=" . $idx,"P_path") , "|", 0) , "_", 0) . "\"
}";
                    }
                    break;

                case "F":
                    if (getrx("select * from ".TABLE."form where F_id=" . $idx,"F_id") != "") {
                        $E_contents = "{ 
	\"title\":\"" . lang(getrx("select * from ".TABLE."form where F_id=" . $idx,"F_title")) . "\", 
	\"description\":\"" . lang(getrx("select * from ".TABLE."form where F_id=" . $idx,"F_title")) . "\",
	\"url\":\"http://" . $D_domain . "/wap_index.php?type=form&S_id=" . $idx . "\",
	\"picurl\":\"http://" . $D_domain . "/" . getrx("select * from ".TABLE."form where F_id=" . $idx,"F_pic") . "\"
}";
                    }
                    break;

                case "C":
                    $E_contents = "{ \"title\":\"联系我们\", \"description\":\"联系我们\",\"url\":\"http://" . $D_domain . "/wap_index.php?type=contact&S_id=1\",\"picurl\":\"http://" . $D_domain . "/" . $C_ico . "\"}";
                    break;

                case "G":
                    $E_contents = "{ \"title\":\"在线留言\", \"description\":\"在线留言\",\"url\":\"http://" . $D_domain . "/wap_index.php?type=guestbook&S_id=1\",\"picurl\":\"http://" . $D_domain . "/" . $C_ico . "\"}";
            }
            $sendmsg = GetBody("https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=" . $access_token, "{ \"touser\":\"" . $member . "\",\"msgtype\":\"news\",\"news\": {\"articles\":[" . $E_contents . "]}}");
            break;

        case "articles":
            if ($E_content == "推送网站目录") {
                $sql2 = "select * from ".TABLE."slide order by S_id desc limit 1";
                $result2 = mysqli_query($conn, $sql2);
                $row2 = mysqli_fetch_assoc($result2);
                if (mysqli_num_rows($result2) > 0) {
                    $S_pic = $row2["S_pic"];
                }
                $sql2 = "select count(*) as U_count from ".TABLE."menu where U_del=0 and U_sub=0";
                $result2 = mysqli_query($conn, $sql2);
                $row2 = mysqli_fetch_assoc($result2);
                $U_count = $row2["U_count"];
                if ($U_count > 8) {
                    $U_count = 8;
                }
                $E_contents = $E_contents . "{ \"title\":\"" . lang($C_webtitle) . "\", \"description\":\"" . lang($C_webtitle) . "\",\"url\":\"http://" . $D_domain . "\",\"picurl\":\"http://" . $D_domain . "/" . $S_pic . "\"},";
                $sql2 = "select * from ".TABLE."menu where U_del=0 and U_sub=0 and not U_type='index' order by U_order limit " . ($U_count - 1);
                $result2 = mysqli_query($conn, $sql2);
                if (mysqli_num_rows($result2) > 0) {
                    while ($row2 = mysqli_fetch_assoc($result2)) {
                        if ($row2["U_type"] != "sub" && $row2["U_type"] != "link") {
                            $link = "wap_index.php?type=" . $row2["U_type"] . "&S_id=" . $row2["U_typeid"];
                        } else {
                            $link = $row2["U_link"];
                        }
                        $E_contents = $E_contents . "{ 
	\"title\":\"" . lang($row2["U_title"]) . "/" . lang($row2["U_entitle"]) . "\", 
	\"description\":\"" . lang($row2["U_title"]) . "/" . lang($row2["U_entitle"]) . "\",
	\"url\":\"http://" . $D_domain . "/" . $link . "\",
	\"picurl\":\"http://" . $D_domain . "/" . $C_ico . "\"
},";
                    }
                }
                $E_contents = substr($E_contents, 0, strlen($E_contents) - 1);
            } else {
                $E_content = explode(",", $E_content);
                for ($j = 0; $j < count($E_content); $j++) {
                    $idy = substr($E_content[$j], -(strlen($E_content[$j]) - 1));
                    switch (substr($E_content[$j], 0, 1)) {
                        case "T":
                            if (getrx("select * from ".TABLE."text where T_id=" . $idy,"T_id") != "") {
                                $E_contents = $E_contents . "{ 
	\"title\":\"" . lang(getrx("select * from ".TABLE."text where T_id=" . $idy,"T_title")) . "\", 
	\"description\":\"" . lang(getrx("select * from ".TABLE."text where T_id=" . $idy,"T_title")) . "\",
	\"url\":\"http://" . $D_domain . "/wap_index.php?type=text&S_id=" . $idy . "\",
	\"picurl\":\"http://" . $D_domain . "/" . getrx("select * from ".TABLE."text where T_id=" . $idy,"T_pic") . "\"},";
                            }
                            break;

                        case "N":
                            if (getrx("select * from ".TABLE."news where N_id=" . $idy,"N_id") != "") {
                                $E_contents = $E_contents . "{ 
	\"title\":\"" . lang(getrx("select * from ".TABLE."news where N_id=" . $idy,"N_title")) . "\", 
	\"description\":\"" . lang(getrx("select * from ".TABLE."news where N_id=" . $idy,"N_title")) . "\",
	\"url\":\"http://" . $D_domain . "/wap_index.php?type=newsinfo&S_id=" . $idy . "\",
	\"picurl\":\"http://" . $D_domain . "/" . getrx("select * from ".TABLE."news where N_id=" . $idy,"N_pic") . "\"},";
                            }
                            break;

                        case "P":
                            if (getrx("select * from ".TABLE."product where P_id=" . $idy,"P_id") != "") {
                                $E_contents = $E_contents . "{ 
	\"title\":\"" . lang(getrx("select * from ".TABLE."product where P_id=" . $idy,"P_title")) . "\", 
	\"description\":\"" . lang(getrx("select * from ".TABLE."product where P_id=" . $idy,"P_title")) . "\",
	\"url\":\"http://" . $D_domain . "/wap_index.php?type=productinfo&S_id=" . $idy . "\",
	\"picurl\":\"http://" . $D_domain . "/" . splitx(splitx(getrx("select * from ".TABLE."product where P_id=" . $idy,"P_path") , "|", 0) , "_", 0) . "\"},";
                            }
                            break;

                        case "F":
                            if (getrx("select * from ".TABLE."form where F_id=" . $idy,"F_id") != "") {
                                $E_contents = $E_contents . "{ 
	\"title\":\"" . lang(getrx("select * from ".TABLE."form where F_id=" . $idy,"F_title")) . "\", 
	\"description\":\"" . lang(getrx("select * from ".TABLE."form where F_id=" . $idy,"F_title")) . "\",
	\"url\":\"http://" . $D_domain . "/wap_index.php?type=form&S_id=" . $idy . "\",
	\"picurl\":\"http://" . $D_domain . "/" . getrx("select * from ".TABLE."form where F_id=" . $idy,"F_pic") . "\"},";
                            }
                            break;

                        case "C":
                            $E_contents = $E_contents . "{ \"title\":\"联系我们\", \"description\":\"联系我们\",\"url\":\"http://" . $D_domain . "/wap_index.php?type=contact&S_id=1\",\"picurl\":\"http://" . $D_domain . "/" . $C_ico . "\"},";
                            break;

                        case "G":
                            $E_contents = $E_contents . "{ \"title\":\"在线留言\", \"description\":\"在线留言\",\"url\":\"http://" . $D_domain . "/wap_index.php?type=guestbook&S_id=1\",\"picurl\":\"http://" . $D_domain . "/" . $C_ico . "\"},";
                    }
                }
                $E_contents = substr($E_contents, 0, strlen($E_contents) - 1);
            }
            $sendmsg = GetBody("https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=" . $access_token, "{ \"touser\":\"" . $member . "\",\"msgtype\":\"news\",\"news\": {\"articles\":[" . $E_contents . "]}}");

    }
    return $sendmsg;
}

function ts($zmt,$N_id){
    global $conn,$C_dir,$C_wx_appid,$C_wx_appsecret,$C_bj_id,$C_bj_key,$C_qe_id,$C_qe_key;

    $sql="select * from SL_news where N_id=".$N_id;
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $N_title=lang($row["N_title"]);
    $N_pic=$row["N_pic"];
    $N_author=$row["N_author"];
    $N_content = str_Replace("{@SL_安装目录}", "http://" . $_SERVER["HTTP_HOST"] . $C_dir, lang($row["N_content"]));
    $N_content = str_Replace("class","alt",$N_content);

    switch($zmt){
        case "0":
        $token=getbody("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$C_wx_appid."&secret=".$C_wx_appsecret,"");
        $token=json_decode($token,true);
        $token=$token["access_token"];
        
        $info=getbody("https://api.weixin.qq.com/cgi-bin/media/uploadnews?access_token=".$token,'{
            "articles": [{
                        "thumb_media_id":"'.getpic($N_pic).'",
                        "author":"'.$N_author.'",
                        "title":"'.$N_title.'",
                        "content_source_url":"http://'.$_SERVER["HTTP_HOST"].$C_dir.'?type=newsinfo&S_id='.$N_id.',
                        "content":"'.$N_content.'",
                        "digest":"",
                        "show_cover_pic":0,
                        "need_open_comment":1,
                        "only_fans_can_comment":1
                        }
                    ]
                }');
        $info=json_decode($info,true);
        $news_id=$info["media_id"];

        $info=getbody("https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=".$token,'{
   "filter":{
      "is_to_all":true,
      "tag_id":2
   },
   "mpnews":{
      "media_id":"'.$news_id.'"
   },
    "msgtype":"mpnews",
    "send_ignore_reprint":1
}');
        break;

        case "1":
            $info=getbody("http://baijiahao.baidu.com/builderinner/open/resource/article/publish",'{
              "app_id" : "'.$C_bj_id.'",
              "app_token" : "'.$C_bj_key.'",
              "title": "'.$N_title.'",
              "cover_images": "[{\"src\":\"http:\/\/'.$_SERVER["HTTP_HOST"].str_replace("/","\/",$C_dir).'\/'.str_replace("/","\/",$N_pic).'\"}]",
              "origin_url":"http://'.$_SERVER["HTTP_HOST"].$C_dir.'/?type=newsinfo&S_id='.$N_id.'",
              "is_original" : 0,
              "content": "'.gljson($N_content).'"
            }');
            
        break;

        case "2":
        $token=getbody("https://auth.om.qq.com/omoauth2/accesstoken","grant_type=clientcredentials&client_id=".$C_qe_id."&client_secret=".$C_qe_key);
        $token=json_decode($token,true);
        $token=$token["data"]["access_token"];
        $info=getbody("https://api.om.qq.com/articlev2/clientpubpic","access_token=".$token."&title=".$N_title."&content=".$N_content."&cover_pic=http://".$_SERVER["HTTP_HOST"].$C_dir.$N_pic);
        break;
    }
    return $info;
}


function getpic($path){
    global $conn,$C_dir,$C_wx_appid,$C_wx_appsecret,$C_bj_id,$C_bj_key,$C_qe_id,$C_qe_key,$C_dirx;

    $token=getbody("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$C_wx_appid."&secret=".$C_wx_appsecret,"");
    $token=json_decode($token,true);
    $token=$token["access_token"];

    $type = "image";  //声明上传的素材类型，这里为image

    //$url = "https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=".$token."&type=".$type;
    $url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token=".$token."&type=".$type;

            //这里是请求地址，token和素材类型通过get方式传递
    $file_path = $C_dirx.$path;
            //这里声明文件的路径，使用绝对路径
    $file_data = array('media'  => '@'.$file_path);
            //传递的数组，方式一：使用'@'符号加上文件的绝对路径来指引文件。这种方式适合PHP5.5之前的版本，
    //$file_data = array("media"  => new \CURLFile($file_path));
            //传递的数组，方式二：从PHP5.5版本以后，引入了新的CURLFile 类来指向文件，参数传入的也是绝对路径
    $ch = curl_init();
            //初始化一个新的会话，返回一个cURL句柄，供curl_setopt(), curl_exec()和curl_close() 函数使用。
    curl_setopt($ch , CURLOPT_URL , $url);
            //需要获取的URL地址，也可以在curl_init()函数中设置。
    curl_setopt($ch , CURLOPT_RETURNTRANSFER, 1);
            //使用PHP curl获取页面内容或提交数据，有时候希望返回的内容作为变量储存，
            //而不是直接输出。这个时候就必需设置curl的CURLOPT_RETURNTRANSFER选项为1或true
    curl_setopt($ch , CURLOPT_POST, 1);
            //发送一个POST请求
    curl_setopt($ch , CURLOPT_POSTFIELDS, $file_data);
            //传递一个关联数组，生成multipart/form-data的POST请求
    $output = curl_exec($ch);//发送请求获取结果
    curl_close($ch);//关闭会话

    $output=json_decode($output,true);

    return $output["media_id"];
}
?>