<?php 
require '../function/conn.php';
require '../function/function.php';
$action=$_REQUEST["action"];
switch($action) {
  case "resetpwd":
  $a=intval($_GET["a"]);
  $p=t($_GET["p"]);
  $genkey=gen_key(6);
  $sql="select * from ".TABLE."admin where A_id=$a and A_pwd='$p'";
  $result = mysqli_query($conn,$sql);
  $row = mysqli_fetch_assoc($result);
  if (mysqli_num_rows($result) > 0) {
    mysqli_query($conn,"update ".TABLE."admin set A_pwd='".strtoupper(md5($genkey))."' where A_id=$a and A_pwd='$p'");
    box("您的管理员密码已重置为".$genkey."，请牢记！","../index.php","success");
  } else {
    box("重置链接已失效，请检查！","back","error");
  }
  break;
  case "codex":
  $str=t($_GET["str"]);
  $_SESSION["CmsCode"]=$str;
  die(xcode($str,'ENCODE',$str,0));
  break;
  case "like":
  ready(plug("x11","2"));
  break;
  case "unlike":
  ready(plug("x11","3"));
  break;
  case "download":
  $N_id=intval($_GET["N_id"]);
  $N_file=getrx("select * from ".TABLE."news where N_id=".$N_id,"N_file")."|0|0|0|0|0|0|0|0|0|0|0|0";
  $file_auth=intval(splitx($N_file,"|",6));
  $file_down=splitx($N_file,"|",5);
  if($file_auth=="0") {
    if(strpos($file_down,"http://")!==false || strpos($file_down,"https://")!==false) {
      Header("Location:".$file_down);
    } else {
      Header("Location:".gethttp().$_SERVER["HTTP_HOST"].$C_dir.$file_down);
    }
  } else {
    if($_SESSION["M_id"]=="") {
      echo "<script>alert('请先登录会员帐号！');location.href='../member/member_login.php?from=".urlencode(gethttp().$SERVER["HTTP_HOST"].$C_dir."?type=newsinfo&S_id=".$N_id)."';</script>";
      die();
    } else {
      $file_fen=getrx("select * from ".TABLE."lv where L_id=".$file_auth,"L_fen");
      $L_title=getrx("select * from ".TABLE."lv where L_id=".$file_auth,"L_title");
      $M_fen=getrx("select * from ".TABLE."member where M_id=".$_SESSION["M_id"],"M_fen");
      if($M_fen-$file_fen>=0) {
        Header("Location:".gethttp().$_SERVER["HTTP_HOST"].$C_dir.$file_down);
      } else {
        echo "<script>alert('下载该资源需要达到\"".$L_title."\"级别，您的账户等级不足，请先升级！');window.close();</script>";
        die();
      }
    }
  }
  break;
  case "api":
  $words=$_REQUEST["words"];
  if($words!="") {
    $sql="select * from ".TABLE."config";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $word=splitx($words,",");
    for ($i=0 ;$i< count($word);$i++) {
      $rsword=$rsword.$row[$word[$i]].",";
    }
    echo substr($rsword,0,strlen($rsword)-1);
  }
  break;
  case "hide":
  $N_id=intval($_GET["N_id"]);
  $sql="select * from ".TABLE."news where N_id=".$N_id;
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  if(mysqli_num_rows($result) > 0) {
    $N_hide=str_Replace(PHP_EOL,"<br>",$row["N_hide"]);
  }
  if($_SESSION["M_vip"]==1) {
    echo "\$(\"#hide_content\").html(\"".$N_hide."\");\$(\"#hide_content\").css(\"text-align\",\"center\");";
  }
  break;
  case "lang":
  $_SESSION["i"]=$_GET["lang"];
  break;
  case "news_lv":
  $N_id=intval($_GET["N_id"]);
  $N_type=urlencode($_GET["N_type"]);
  $domain=$_GET["domain"];
  if($N_type=="newsinfo") {
    $sql="select * from ".TABLE."news where N_id=".$N_id;
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if(mysqli_num_rows($result) > 0) {
      $N_lv=$row["N_lv"];
    }
    if($N_lv==0) {
    } else {
      $N_fen=getrx("select * from ".TABLE."lv where L_id=".$N_lv,"L_fen");
      $L_title=getrx("select * from ".TABLE."lv where L_id=".$N_lv,"L_title");
      if($_SESSION["M_id"]=="") {
        echo "alert(\"请先登陆会员账户！\");window.location.href=\"//".$domain.$C_dir."member\";";
      } else {
        if(getrx("select * from ".TABLE."member where M_id=".$_SESSION["M_id"],"M_fen")-$N_fen>=0) {
        } else {
          echo "alert(\"本文章阅读等级限制为“".lang($L_title)."”，请先升级！\");window.location.href=\"//".$domain.$C_dir."member/member_role.php\";";
        }
      }
    }
  }
  break;
  case "P_rest":
  $P_id=intval($_GET["P_id"]);
  $sql="select * from ".TABLE."product where P_id=".$P_id;
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  if(mysqli_num_rows($result) > 0) {
    $P_rest=$row["P_rest"];
  }
  echo ("document.write(".$P_rest.")");
  break;
  case "newsview":
  $N_id=intval($_GET["N_id"]);
  mysqli_query($conn,"update ".TABLE."news set N_view=N_view+1 where N_id=".$N_id);
  if(intval($_SESSION["M_id"])>0 && $C_read>0) {
    if(getrx("select * from ".TABLE."list where L_mid=".$_SESSION["M_id"]." and L_title='阅读文章(ID：".$N_id.")'","L_id")=="") {
      mysqli_query($conn,"update ".TABLE."member set M_fen=M_fen+$C_read where M_id=".intval($_SESSION["M_id"]));
      mysqli_query($conn, "insert into ".TABLE."list(L_title,L_mid,L_change,L_time,L_type,L_no) values('阅读文章(ID：".$N_id.")'," . $_SESSION["M_id"] . "," . $C_read . ",'" . date('Y-m-d H:i:s') . "',1,'" . gen_key(20) . "')");
    }
  }
  $sql="select * from ".TABLE."news where N_id=".$N_id;
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  if(mysqli_num_rows($result) > 0) {
    $N_view=$row["N_view"];
  }
  echo ("function view_add(){document.getElementById('view').innerHTML='".$N_view."';}");
  break;
  case "member":
  $str=urldecode(base64_decode($_GET["str"]));
  $style=str_replace("__"," ",splitx($str,"|",0));
  $style2=str_replace("__"," ",splitx($str,"|",1));
  if($_SESSION["M_id"]=="") {
    $style=str_Replace("%注册链接%",$C_dir."member/member_reg.php",$style);
    $style=str_Replace("%登录链接%",$C_dir."member/member_login.php",$style);
    $style=str_Replace("%购物车链接%",$C_dir."member/member_order.php?type=0",$style);
    $memberx=$style;
  } else {
    $m=getrs("select * from ".TABLE."member where M_id=".$_SESSION["M_id"]);
    $style2=str_Replace("%用户ID%",$_SESSION["M_id"],$style2);
    $style2=str_Replace("%用户名%",$m["M_login"],$style2);
    $style2=str_Replace("%电子邮箱%",$m["M_email"],$style2);
    $style2=str_Replace("%QQ号码%",$m["M_qq"],$style2);
    $style2=str_Replace("%用户余额%",$m["M_money"],$style2);
    $style2=str_Replace("%用户等级%",$m["M_lv"],$style2);
    $style2=str_Replace("%用户手机%",$m["M_mobile"],$style2);
    $style2=str_Replace("%用户头像%",$m["M_pic"],$style2);
    $style2=str_Replace("%用户积分%",$m["M_fen"],$style2);
    $style2=str_Replace("%用户姓名%",$m["M_name"],$style2);
    $style2=str_Replace("%用户邮编%",$m["M_code"],$style2);
    $style2=str_Replace("%购物车链接%",$C_dir."member/member_order.php?type=0",$style2);
    $style2=str_Replace("%退出链接%",$C_dir."member/member_login.php?action=unlogin",$style2);
    $style2=str_Replace("%会员中心%",$C_dir."member/",$style2);
    $memberx=$style2;
  }
  $memberx=str_replace("\r\n","",$memberx);
  $memberx=str_replace(PHP_EOL,"",$memberx);
  echo "document.write(\"".str_replace("\"","'",$memberx)."\");\$(\"[href='".$C_dir."member/member_login.php']\").attr(\"href\",\$(\"[href='".$C_dir."member/member_login.php']\").attr(\"href\")+\"?from=\"+encodeURIComponent(window.location.href));\$(\"[href='".$C_dir."member/member_login.php?action=unlogin']\").attr(\"href\",\$(\"[href='".$C_dir."member/member_login.php?action=unlogin']\").attr(\"href\")+\"&from=\"+encodeURIComponent(window.location.href));";
  break;
  case "comment":
  if (!defined('TABLE')) {
    define("TABLE","SL_");
  }
  $page=t($_REQUEST["page"]);
  if($C_psh==1) {
    $sql="select count(C_id) as C_count from ".TABLE."comment where C_page='".$page."' and C_sh=1";
  } else {
    $sql="select count(C_id) as C_count from ".TABLE."comment where C_page='".$page."'";
  }
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  if (mysqli_num_rows($result) > 0) {
    $C_count=$row["C_count"];
  }
  if($C_count>0) {
    $comment="<b>".lang("网友评论<font color='#ff9900'>".$C_count."</font>条/l/".$C_count." comments")."</b>".$comment.creat(0,0,$page)."<hr>";
  } else {
    $comment="<b>".lang("暂无评论，快来抢沙发吧！/l/No comment, grab the sofa！")."</b>".$comment.creat(0,0,$page)."<hr>";
  }
  if($_SESSION["M_id"]!="" || $C_punlogin==1) {
    $M_id=intval($_SESSION["M_id"]);
    if($M_id==0) {
      $M_id=getrx("select M_id from ".TABLE."member where M_login='未提供'","M_id");
      $login="<a href=\"".$C_dir."member/member_login.php\">[".lang("登录/l/Login")."]</a>";
    }else{
      $login="<a href=\"".$C_dir."member/member_login.php?action=unlogin\">[".lang("退出/l/Logout")."]</a>";
    }
    $comment=$comment."<p style=\"font-weight:bold;\">".lang("发表评论/l/Comment")."<p><p>".lang("当前登录/l/user")."：".getrx("select * from ".TABLE."member where M_id=".$M_id,"M_login")." $login</p><div class='comment_input_'><form id='comment_form' onsubmit='submitx();return false;'><div class='comment_textarea'><textarea name='comment' style='outline:none' spellcheck='false' placeholder='".lang("请在这里发表您的评论/l/Please give your comments here")."' id='comment_content'></textarea><input type='hidden' name='page' value='".$page."'/><input type='hidden' name='sub' id='sub' value='0'/></div><div class='reply_info'></div><button type='submit'>".lang("发布/l/Submit")."</button><div class='yzm'><iframe src='".$C_dir."function/code_1.php?name=code' scrolling='no' frameborder='0' width='100%' height='40'></iframe></div></form></div>";
  } else {
    $comment=$comment."<p style=\"font-weight:bold;\">".lang("发表评论/l/Comment")."<p><p>".lang("请登录帐号后发表评论/l/Please login and comment")." <a href=\"".$C_dir."member/member_login.php\">[".lang("登录/l/sign in")."]</a></p>";
  }
  $comment=$comment."<link rel='stylesheet' href='".$C_dir."css/comment.css' type='text/css'/>";
  $comment=str_Replace("\"","'",$comment);
  echo "\$(\"#comments_box\").html(\"".$comment."\");\$(\"[href='".$C_dir."member/member_login.php']\").attr(\"href\",\$(\"[href='".$C_dir."member/member_login.php']\").attr(\"href\")+\"?from=\"+encodeURIComponent(window.location.href));function reply(C_id){\$('#sub').val(C_id);\$('#comment_content').focus();\$('.reply_info').html('<b>".lang("回复给/l/Reply to")."</b> '+\$('#list_'+C_id+' .M_login').html()+'：'+\$('#list_'+C_id+' .C_content').html() + ' <a href=\"javascript:;\" onclick=\"cancel()\" class=\"reply\">[".lang("取消/l/quit")."]</a>');};function cancel(){\$('#sub').val(0);\$('.reply_info').html('');}function refresh1(){ var vcode=document.getElementById('vcode'); vcode.src ='".$C_dir."function/code_1.php?nocache='+new Date().getTime();}function submitx(){\$.ajax({url: '".$C_dir."function/scms.php?action=submit',type: 'POST', data: \$('#comment_form').serialize(),success: function (msg) {if(msg.indexOf('success')>=0){\$('.reply_info').html('<font color=\"#009900\">'+msg.split('|')[1]+'</font>');\$('#code').val('');\$('#comment_content').val('');}else{\$('.reply_info').html('<font color=\"#ff9900\">'+msg.split('|')[1]+'</font>');}},error: function (msg) {console.log(msg);}});}";
  break;
  case "jssdk":
  $APPID = $C_wx_appid;
  $APPSECRET = $C_wx_appsecret;
  $info=getbody("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$APPID."&secret=".$APPSECRET,"");
  $access_token=json_decode($info)->access_token;
  $info=getbody("https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$access_token."&type=jsapi","");
  $ticket=json_decode($info)->ticket;
  $url=$_POST["url"];
  $noncestr=gen_key(20);
  $timestamp=time();
  $pageid=$_POST["pageid"];
  if($pageid=="") {
    $pageid=1;
  } else {
    $pageid=intval($pageid);
  }
  switch($_POST["pagetype"]) {
    case "index":
    $img=$C_ico;
    break;
    case "text":
    $img=getrx("select * from ".TABLE."text where T_id=".$pageid,"T_pic");
    break;
    case "product":
    $img=getrx("select * from ".TABLE."psort where S_id=".$pageid,"S_pic");
    break;
    case "productinfo":
    $img=splitx(getrx("select * from ".TABLE."product where P_id=".$pageid,"P_path"),"__",0);
    break;
    case "news":
    $img=getrx("select * from ".TABLE."nsort where S_id=".$pageid,"S_pic");
    break;
    case "newsinfo":
    $img=getrx("select * from ".TABLE."news where N_id=".$pageid,"N_pic");
    break;
    case "form":
    $img=getrx("select * from ".TABLE."form where F_id=".$pageid,"F_pic");
    break;
    case "contact":
    $img=$C_ico;
    break;
    case "guestbook":
    $img=$C_ico;
    break;
  }
  $sign=sha1("jsapi_ticket=".$ticket."&noncestr=".$noncestr."&timestamp=".$timestamp."&url=".$url);
  echo "{\"nonceStr\":\"".$noncestr."\",\"timestamp\":\"".$timestamp."\",\"signature\":\"".$sign."\",\"appid\":\"".$APPID."\",\"img\":\"http://".$_SERVER["HTTP_HOST"].$C_dir.$img."\",\"ticket\":\"".$ticket."\"}";
  break;
  case "wxjs":
  ?>$.ajax( {
    url: "<?php echo $C_dir?>function/scms.php",
        type: 'post',
        data: {
      url: location.href.split('#')[0],action:"jssdk",pagetype:"<?php echo $_GET["pagetype"]?>",pageid:"<?php echo $_GET["pageid"]?>"
    }
    ,
        success:function(res) {
      res=JSON.parse(res);
      wx.config( {
        debug: false,
                appId: res.appid,
                timestamp: res.timestamp,
                nonceStr: res.nonceStr,
                signature: res.signature,
                jsApiList: [
                  'checkJsApi',
                  'onMenuShareTimeline',
                  'onMenuShareAppMessage',
                  'onMenuShareQQ'
                ]
      }
      );
      wx.ready(function () {
        var shareData = {
          title: document.title,
                    desc: getDesc(),
                    link: res.url,
                    imgUrl: res.img
        }
        ;
        wx.onMenuShareAppMessage(shareData);
        wx.onMenuShareTimeline(shareData);
        wx.onMenuShareQQ(shareData);
      }
      );
      wx.error(function (res) {
      }
      );
    }
  }
  );
  function getDesc() {
    var meta = document.getElementsByTagName("meta");
    for (var i=0;i<meta.length;i++) {
      if(typeof meta[i].name!="undefined"&&meta[i].name.toLowerCase()=="description") {
        return meta[i].content;
      }
    }
  }
  ;
  <?php
  break;
  case "submit":
  $C_comment=htmlspecialchars($_POST["comment"]);
  $C_page=escape($_POST["page"]);
  $C_sub=intval($_POST["sub"]);
  $C_code=$_POST["code"];
  $M_id=intval($_SESSION["M_id"]);
  if($M_id==0 && $C_punlogin==0) {
    echo "error|".lang("请先登录会员帐号！/l/Please login to member account first");
    die();
  }
  if(xcode($_POST["code"],'DECODE',$_SESSION["CmsCode"],0)!=$_SESSION["CmsCode"] || $_POST["code"]=="" || $_SESSION["CmsCode"]=="") {
    echo "error|".lang("验证码错误！/l/Verification code error");
    die();
  } else {
    if($C_comment!="") {
      if($M_id==0) {
        $M_id=getrx("select M_id from ".TABLE."member where M_login='未提供'","M_id");
      }
      mysqli_query($conn,"insert into ".TABLE."comment(C_content,C_mid,C_sub,C_page,C_time,C_sh) values('".escape($C_comment)."',".$M_id.",".$C_sub.",'".$C_page."','".date('Y-m-d H:i:s')."',0)");
      if($C_psh==1) {
        echo "success|".lang("评论成功！请等待管理员审核/l/Critical success! Please wait for the administrator to audit");
      } else {
        echo "success|".lang("评论成功！请刷新页面/l/Critical success! Please refresh the page");
      }
      die();
    } else {
      echo "error|".lang("请输入您要评论的内容！/l/Please input what you want to comment on");
      die();
    }
  }
}
function creat($C_id,$i,$page) {
  global $C_psh,$conn,$C_dir;
  if($C_psh==1) {
    $sqlx="select * from ".TABLE."comment,".TABLE."member where C_mid=M_id and C_sub=".intval($C_id)." and C_page='".t($page)."' and C_sh=1 order by C_time";
  } else {
    $sqlx="select * from ".TABLE."comment,".TABLE."member where C_mid=M_id and C_sub=".intval($C_id)." and C_page='".t($page)."' order by C_time";
  }
  $resultx = mysqli_query($conn,  $sqlx);
  if (mysqli_num_rows($resultx) > 0) {
    while($rowx = mysqli_fetch_assoc($resultx)) {
      $M_pic=$rowx["M_pic"];
      if (substr($M_pic,0,4)!="http") {
        $M_pic=$C_dir."media/".$M_pic;
      }
      $M_login=$rowx["M_login"];
      $C_time=$rowx["C_time"];
      $C_content=$rowx["C_content"];
      $C_sub=$rowx["C_sub"];
      $C_id=$rowx["C_id"];
      if ($C_sub==0) {
        $j=$j+1;
        $floor_info="<div style='position:absolute;top:20px;right:20px;color:#AAAAAA;'>".$j."#</div>";
      }
      $creat=$creat."<li style=\"margin-left:".($i*15)."px;position:relative;\" id=\"list_".$C_id."\"><img src=\"".$M_pic."\" class=\"comment_head\"><div class='C_right'><b class=\"M_login\">".$M_login."</b><br><span class='C_content'>".$C_content."</span><br><span class='C_time'>".$C_time." <a href='javascript:;' onclick='reply(".$C_id.")' class='reply'>[".lang("回复/l/Reply")."]</a></span></div>".$floor_info."</li>".creat($rowx["C_id"],$i+1,$page);
    }
  } else {
    $creat="";
  }
  return $creat;
}
?>