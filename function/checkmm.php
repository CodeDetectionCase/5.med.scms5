<?php
require '../function/conn.php';
require '../function/function.php';

$action=$_GET["action"];
$files='|admin/ajax.php|admin/chat_test.php|admin/data.php|admin/dbm.php|admin/demo.php|admin/download.php|admin/fontpicker.php|admin/help.php|admin/index.php|admin/test.php|admin/tpl.php|admin/update2.php|amp.php|api/index.php|api/notify.php|bbs/bbs.php|bbs/index.php|bbs/item.php|bbs/list.php|data/ajax.function|data/api.function|data/core.php|data/data.function|feed.php|function/booksave.php|function/buy.php|function/c.php|function/checkmm.php|function/code_1.php|function/code_2.php|function/form.php|function/function.php|function/like.php|function/mapload.php|function/mobile.php|function/pic.php|function/qqlogin.php|function/reg.php|function/scms.php|function/search.php|function/update.php|function/upload.php|function/weixin.php|index.php|install/index.php|member/auto.php|member/foot.php|member/index.php|member/invoice_add.php|member/invoice_list.php|member/left.php|member/member_check.php|member/member_edit.php|member/member_email.php|member/member_fenlist.php|member/member_form.php|member/member_found.php|member/member_gift.php|member/member_login.php|member/member_mobile.php|member/member_moneylist.php|member/member_news.php|member/member_newsinfo.php|member/member_order.php|member/member_pay.php|member/member_pay2.php|member/member_pwdedit.php|member/member_reg.php|member/member_role.php|member/member_setpwd.php|member/member_wuliu.php|member/OK.php|member/post.php|member/sendmail.php|member/sendmobile.php|member/top.php|member/unlogin.php|mip.php|pay/alipay/alipay.config.php|pay/alipay/alipayapi.php|pay/alipay/index.php|pay/alipay/lib/alipay_core.function.php|pay/alipay/lib/alipay_md5.function.php|pay/alipay/lib/alipay_notify.class.php|pay/alipay/lib/alipay_submit.class.php|pay/alipay/notify_url.php|pay/alipay/return_url.php|pay/bank/api.php|pay/bank/callback1.php|pay/bank/callback2.php|pay/bank/callback3.php|pay/paypal/notify_url.php|pay/paypal/notify_url2.php|pay/wxpay/jsapi.php|pay/wxpay/login.php|pay/wxpay/native.php|pay/wxpay/notify_url.php|ueditor/php/action_crawler.php|ueditor/php/action_list.php|ueditor/php/action_upload.php|ueditor/php/controller.php|ueditor/php/Uploader.class.php|wap_index.php|';

function gl($name){
	$name=str_replace("\\","/",$name);
	return str_replace("..//","",$name);
}

function checkmm($a){
    global $files,$C_admin;
    if(strpos(strtolower($files),strtolower("|".str_replace($C_admin."/","admin/",$a)."|"))!==false){
        echo "<p style='color:#009900'>????????????".$a."????????????</p>";
    }else{
        echo "<p style='color:#ff0000'>????????????".$a."????????????</p>";
    }
}

function delmm($a){
    global $files,$C_admin;
    if(strpos(strtolower($files),strtolower("|".str_replace($C_admin."/","admin/",$a)."|"))!==false){
        file_put_contents("../".$a,file_get_contents("http://scms5.oss-cn-shenzhen.aliyuncs.com/php/".str_replace($C_admin."/","admin/",$a).".txt"));
        echo "<p style='color:#009900'>????????????".$a."???????????????</p>";
    }else{
        unlink("../".$a);
        echo "<p style='color:#ff9900'>????????????".$a."???????????????</p>";
    }
}

function check($dir){
    if(!is_dir($dir)) return false;
    $handle = opendir($dir);
    if($handle){
        while(($fl = readdir($handle)) !== false){
            $temp = $dir.DIRECTORY_SEPARATOR.$fl;
            if(is_dir($temp) && $fl!='.' && $fl != '..'){
                check($temp);
            }else{
                if($fl!='.' && $fl != '..' && (substr($fl,-3)=="php" || substr($fl,-3)=="asp" || substr($fl,-8)=="function") && substr($fl,-8)!="conn.php"){
                    checkmm(gl($temp));
                }
            }
        }
    }
}

function del($dir){
    if(!is_dir($dir)) return false;
    $handle = opendir($dir);
    if($handle){
        while(($fl = readdir($handle)) !== false){
            $temp = $dir.DIRECTORY_SEPARATOR.$fl;
            if(is_dir($temp) && $fl!='.' && $fl != '..'){
                del($temp);
            }else{
                if($fl!='.' && $fl != '..' && (substr($fl,-3)=="php" || substr($fl,-3)=="asp" || substr($fl,-8)=="function") && substr($fl,-8)!="conn.php"){
                    delmm(gl($temp));
                }
            }
        }
    }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>????????????</title>
 <link rel="stylesheet" href="css/css/font-awesome.min.css" type="text/css" />
 <link rel="stylesheet" href="css/sweetalert.css" type="text/css" />
 <script src="js/jquery.min.js"></script>
<script src="js/sweetalert.min.js"></script>
<style>
*{font-size: 12px;line-height: 170%;}
a {
  color: #363f44;
  text-decoration: none;
  cursor: pointer;
}
.add{background:#0099ff; color:#FFFFFF; border:#0099ff solid 1px; padding:2px 5px;-moz-border-radius: 5px; -webkit-border-radius: 5px; border-radius:5px; margin-top:5px;white-space:nowrap;}
.add:hover{background:#ffffff; color:#0099ff; }

.del{background:#ff9900; color:#FFFFFF; border:#ff9900 solid 1px; padding:2px 5px;-moz-border-radius: 5px; -webkit-border-radius: 5px; border-radius:5px; margin-top:5px;white-space:nowrap;}
.del:hover{background:#ffffff; color:#ff9900; }

p{font-size: 12px;margin:0px}
</style>
</head>
<body>
<?php 
if($action!="check" && $action!="del"){
?>
<p>?????????????????????????????????????????????????????????PHP????????????PHP????????????????????????????????????????????????????????????</p>
<a href="?action=check" onClick="swal({title: '',text: '????????????????????????????????????',imageUrl: '<?php echo $C_admin?>/img/loading.gif',html: true,showConfirmButton: false});" class='add'><i class='fa fa-refresh'></i> ????????????</a>
<?php }

if ($action=="check"){
echo "???????????????????????????????????????<div style='height:500px; overflow:auto;background-color:#EEEEEE;padding:5px;margin-bottom:10px'>";
check('../');
echo "</div><p style='color:#ff0000;margin-bottom:10px;'>????????????????????????????????????????????????????????????FTP???????????????????????????</p>";
echo "<p><a href=\"?action=del\" onClick=\"swal({title: '',text: '????????????????????????????????????',imageUrl: '".$C_admin."/img/loading.gif',html: true,showConfirmButton: false});\" class=\"add\"><i class=\"fa fa-refresh\"></i> ????????????</a></p>";
}

if ($action=="del"){
echo "<div style='height:500px;overflow:auto;background-color:#EEEEEE;padding:5px;margin-bottom:10px'>";
del('../');
echo "</div><p style='color:#ff0000;margin-bottom:10px;'>??????????????????????????????????????????????????????????????????????????????</p>";
echo "<p><a href=\"index.php\" target=\"_blank\" class=\"add\"><i class=\"fa fa-send\"></i> ????????????</a> <a href=\"?action=check\" onClick=\"swal({title: '',text: '????????????????????????????????????',imageUrl: '".$C_admin."/img/loading.gif',html: true,showConfirmButton: false});\" class=\"del\"><i class=\"fa fa-refresh\"></i> ????????????</a></p>";
}

?>
</body>
</html>