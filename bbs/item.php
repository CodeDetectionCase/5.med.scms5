<?php 
require '../function/conn.php';
require '../function/function.php';

$id=intval($_REQUEST["id"]);

$action=$_GET["action"];
$_SESSION["from"]=$C_dir."bbs/item.php?id=".$id;
if($id==""){
$id=0;
}


    mysqli_query($conn,"update ".TABLE."bbs set B_view=B_view+1 where B_id=".$id);
    $sql="Select * from ".TABLE."bbs,".TABLE."bsort,".TABLE."member,".TABLE."lv where B_del=0 and B_sort=S_id and B_mid=M_id and M_lv=L_id and B_id=".$id;

    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if (mysqli_num_rows($result) > 0) {
    $B_title=lang($row["B_title"]);
    $B_content=str_replace("{@SL_安装目录}",$C_dir,lang($row["B_content"]));
    $B_time=$row["B_time"];
    $B_sort=$row["B_sort"];
    $S_title=lang($row["S_title"]);
    $B_view=$row["B_view"];
    $M_login=$row["M_login"];
    $M_pic=$row["M_pic"];
    $L_title=$row["L_title"];
    }
if(substr($M_pic,0,4)!="http"){
$M_pic="../media/".$M_pic;
}
$sql2="Select count(*) as B_count from ".TABLE."bbs where B_del=0 and B_sub=".$id;

$result2 = mysqli_query($conn, $sql2);
$row2 = mysqli_fetch_assoc($result2);
$B_count=$row2["B_count"];

if($action=="reply"){
    if(xcode($_POST["code"],'DECODE',$_SESSION["CmsCode"],0)!=$_SESSION["CmsCode"] || $_POST["code"]=="" || $_SESSION["CmsCode"]==""){
        box("请重新拖动滑块进行验证！", "back", "error");
    }else{
    $_SESSION["CmsCode"]="refresh";
    $B_contentx=RemoveXSS($_POST["B_content"]);
    if($B_contentx==""){
        box("请填全内容后提交！", "back", "error");
    }else{
        
        mysqli_query($conn,"insert into ".TABLE."bbs(B_title,B_content,B_time,B_mid,B_sub,B_sort) values('[回复]".$B_title."','".$B_contentx."','".date('Y-m-d H:i:s')."',".$_SESSION["M_id"].",".$id.",".intval($B_sort).")");
        box("回复成功！","item.php?id=".$id,"success");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo lang($C_description)?>">
    <meta name="author" content="s-cms">
    <title><?php echo $B_title?> - <?php echo lang($C_webtitle)?></title>
    <link href="<?php echo $C_dir.$C_ico?>" rel="shortcut icon" />
    <link href="../member/css/bootstrap.css" rel="stylesheet">
    <link href="../css/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="../member/css/style.css" rel="stylesheet" type="text/css">
    <script src="../member/js/jquery.min.js"></script>
    <script src="../member/js/bootstrap.min.js"></script>
 
</head>

<body style="background: rgba(255,255,255,0);">
<div class="search_area">
<div class="row">
    <ul class="list-group">
<li class="list-group-item">
导航：<a href="index.php">论坛首页</a> - <a href="list.php?id=<?php echo $B_sort?>"><?php echo $S_title?></a> - <?php echo $B_title?>
</li>
    <li class="list-group-item" style="padding: 0px;">
    <div class="col-md-3" style="padding: 0px;">
        <div class="panel panel-primary" style="border: none">
    <div class="panel-heading" style="height: 38px;">

<span class="col-xs-6">
    浏览：<?php echo $B_view?>
</span>
<span class="col-xs-6">
    回复：<?php echo $B_count?>
</span>
<div style="font: 0px/0px sans-serif;clear: both;display: block"> </div>
    </div>
    <div class="panel-body">
        <div style="text-align: center;padding: 10px;height: 100%">
            <div style="font-weight: bold;margin: 20px;"><?php echo $M_login?></div>
            <img src="<?php echo $M_pic?>" style="width:120px;height:120px;border-radius:10px; ">
            <p style="margin: 10px;">等级：<?php echo $L_title?></p>
        </div>
    </div>
</div>
    </div>

<div class="col-md-9" style="padding: 0px;border-left: solid 1px #dddddd;min-height: 330px;">
<div class="panel panel-primary" style="border: none">
    <div class="panel-heading">
        <h3 class="panel-title"><?php echo $B_title?></h3>
        <span style="float: right;margin-top:-17px;"><?php echo $B_time?></span>
    </div>
    <div class="panel-body">
        <?php echo $B_content?>
    </div>
</div>
</div>
<div style="font: 0px/0px sans-serif;clear: both;display: block"> </div>
</li>
<?php 

$i=1;
$sql="select * from ".TABLE."bbs,".TABLE."member,".TABLE."lv where B_mid=M_id and M_lv=L_id and B_sub=".$id." order by B_id asc";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$B_title=lang($row["B_title"]);
    $B_content=str_replace("{@SL_安装目录}",$C_dir,lang($row["B_content"]));
    $B_time=$row["B_time"];
    $B_view=$row["B_view"];
    $M_login=$row["M_login"];
    $M_pic=$row["M_pic"];
    $L_title=$row["L_title"];

if(substr($M_pic,0,4)!="http"){
$M_pic="../media/".$M_pic;
}

?>
    <li class="list-group-item" style="padding: 0px;">
    <div class="col-md-3" style="padding: 0px;">
        <div style="text-align: center;padding: 10px;height: 100%">
            <div style="font-weight: bold;margin: 20px;"><?php echo $M_login?></div>
            <img src="<?php echo $M_pic?>" style="width:120px;height:120px;border-radius:10px; ">
            <p style="margin: 10px;">等级：<?php echo $L_title?></p>
        </div>
    </div>

<div class="col-md-9" style="padding: 0px;border-left: solid 1px #dddddd;min-height: 240px;">
<div class="panel panel-primary" style="border: none">
    <div class="panel-heading">
        <h3 class="panel-title"><?php echo $i?>楼 发表于<?php echo $B_time?></h3>
        
    </div>
    <div class="panel-body">
        <?php echo $B_content?>
    </div>
</div>
</div>
<div style="font: 0px/0px sans-serif;clear: both;display: block"> </div>
</li>
<?php 
$i=$i+1;
}
}

?>

<li class="list-group-item" style="padding: 0px;">
     <a href="list.php?id=<?php echo $B_sort?>" class=" btn btn-info pull-right" style="margin:10px; ">返回板块</a> <a class="btn btn-primary pull-right" style="margin:10px; " href="bbs.php">发帖</a>
     <div style="font: 0px/0px sans-serif;clear: both;display: block"> </div>
</li>


<li class="list-group-item" style="padding: 0px;">
    <div class="col-md-3" style="padding: 0px;">
        <div style="text-align: center;padding: 10px;height: 100%">
            <div style="font-weight: bold;margin: 20px;"><?php 
            if ($_SESSION["M_id"]!=""){
            echo $_SESSION["M_login"];
            }else{
            echo "尚未登录";
        }
                    ?></div>
            <img src="<?php 
            if ($_SESSION["M_id"]!=""){
$M_pic=getrx("select * from ".TABLE."member where M_id=".$_SESSION["M_id"],"M_pic");
if(substr($M_pic,0,4)!=="http"){
    $M_pic="../media/".$M_pic;
}
            echo $M_pic;
            }else{
            echo "../media/member.jpg";
        }
                    ?>" style="width:120px;height:120px;border-radius:10px; ">
            <p style="margin: 10px;"><a href="../member/member_login.php?action=unlogin">退出登录</a></p>
        </div>
    </div>

<div class="col-md-9" style="padding: 10px;border-left: solid 1px #dddddd;">
<?php if ($_SESSION["M_id"]!=""){?>
<form action="?action=reply&id=<?php echo $id?>" method="post">

<textarea name='B_content' style='width:100%;height:200px;' id='content'></textarea>
<script type="text/javascript" charset="utf-8" src="../ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="../ueditor/ueditor.all.min.js"> </script>
<script>
var ue = UE.getEditor('content',{
    toolbars: [
        ['bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', 'simpleupload', 'insertimage', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc']
    ],
    autoHeightEnabled: true,
    autoFloatEnabled: true
});
</script>

<iframe src="../function/code_1.php?name=code" scrolling="no" frameborder="0" width="300" height="40" style="vertical-align:top;margin-top:10px;"></iframe>
<button type="submit" class="btn btn-info" style="margin:13px 0 0 5px;display: inline-block;vertical-align:top;">回复帖子</button>
</form>

<?php }else{?>

<div style="text-align: center;padding:100px 0;border-bottom: solid 1px #dddddd;">
您目前还是游客，请 <a href="../member/member_login.php?from=<?php echo urlencode("//".$_SERVER["HTTP_HOST"].$C_dir."bbs/item.php?id=".$id)?>">登录</a> 或 <a href="../member/member_reg.php">注册</a>
</div>
<a class="btn btn-info" style="margin:10px; ">回复</a>
<?php }?>
</div>
<div style="font: 0px/0px sans-serif;clear: both;display: block"> </div>
</li>
</ul>
</div>
</div>
</body>

</html>