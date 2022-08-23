<?php 
require '../function/conn.php';
require '../function/function.php';

$action = $_GET["action"];
$S_id = $_GET["S_id"];


if ($action == "add") {
    $B_title = htmlspecialchars($_POST["B_title"]);
    $B_sort = intval($_POST["B_sort"]);
    $B_content = removexss($_POST["B_content"]);

if(xcode($_POST["code"],'DECODE',$_SESSION["CmsCode"],0)!=$_SESSION["CmsCode"] || $_POST["code"]=="" || $_SESSION["CmsCode"]==""){
    box("请重新拖动滑块进行验证！", "back", "error");
}else{
    $_SESSION["CmsCode"]="refresh";
    if($B_title=="" || $B_content==""){
        box("请填全内容后提交！", "back", "error");
    }else{

    if (stripos($B_content, "<script") !== false) {
        box("不支持加入javascript", "back", "error");
    }

    $S_sh = getrx("select * from ".TABLE."bsort where S_id=" . $B_sort,"S_sh");
    if ($S_sh == 1) {
        $B_sh = 0;
    } else {
        $B_sh = 1;
    }

    mysqli_query($conn, "insert into ".TABLE."bbs(B_title,B_content,B_time,B_mid,B_sort,B_sh) values('" . $B_title . "','" . $B_content . "','" . date('Y-m-d H:i:s') . "'," . $_SESSION["M_id"] . "," . $B_sort . "," . $B_sh . ")");
    $sql = "Select * from ".TABLE."bbs where B_del=0 order by B_id desc limit 1";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if (mysqli_num_rows($result) > 0) {
        $B_id = $row["B_id"];
    }
    if ($B_sh == 1) {
        box("发布成功！", "item.php?id=" . $B_id, "success");
    } else {
        box("发布成功！请等待审核", "./", "success");
    }

}
}
}


$_SESSION["from"] = $C_dir . "bbs/bbs.php?S_id=" . $S_id;
$sql = "Select * from ".TABLE."slide order by S_id desc limit 1";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if (mysqli_num_rows($result) > 0) {
    if ($C_memberbg == "" || is_null($C_memberbg)) {
        $S_pic = $row["S_pic"];
    } else {
        $S_pic = $C_memberbg;
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
    <title>发帖 - <?php echo lang($C_webtitle)?></title>
    <link href="<?php echo $C_dir.$C_ico?>" rel="shortcut icon" />
    <link href="../member/css/bootstrap.css" rel="stylesheet">
    <link href="../css/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="../member/css/style.css" rel="stylesheet" type="text/css">
    <script src="../member/js/jquery.min.js"></script>
    <script src="../member/js/bootstrap.min.js"></script>

</head>

<body style="background: rgba(255,255,255,0);">
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">发帖</h3>
    </div>
    <div class="panel-body">
        
<?php 
if($_SESSION["M_id"]!=""){
?>
<form class="form-horizontal" action="?action=add" method="post">
    <div class="form-group">
        <label for="inputPassword" class="col-sm-2 control-label">标题</label>
        <div class="col-sm-10">
            <input type="text" name="B_title" class="form-control" placeholder="请输入标题">
        </div>
    </div>

    <div class="form-group">
        <label for="inputPassword" class="col-sm-2 control-label">板块</label>
        <div class="col-sm-10">
            <select class="form-control" name="B_sort">
                <?php 
$sql="Select * from ".TABLE."bsort where S_del=0 order by S_order,S_id desc";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        if(floor($row["S_id"])==floor($S_id)){
            $select_info="selected=\"selected\"";
        }else{
            $select_info="";
        }
        echo "<option value=\"".$row["S_id"]."\" ".$select_info.">".lang($row["S_title"])."</option>";
    }
}
?>
               
            </select>
        </div>
    </div>

    <div class="form-group">
        <label for="inputPassword" class="col-sm-2 control-label">内容</label>
        <div class="col-sm-10">

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

        </div>
    </div>

    <div class="form-group">
        <label for="inputPassword" class="col-sm-2 control-label"></label>
        <div class="col-sm-10">
            <iframe src="../function/code_1.php?name=code" scrolling="no" frameborder="0" style="width: 100%;height: 40px;max-width: 400px;"></iframe>
        </div>
    </div>

    <div class="form-group">
        <label for="inputPassword" class="col-sm-2 control-label"></label>
        <div class="col-sm-10">
            <button class="btn btn-primary" type="submit">发布</button>
        </div>
    </div>
</form>
<?php }else{?>


<div style="text-align: center;padding:100px 0;">
您目前还是游客，请 <a href="../member/member_login.php?from=<?php echo urlencode("//".$_SERVER["HTTP_HOST"].$C_dir."bbs/bbs.php?S_id=".$S_id)?>">登录</a> 或 <a href="../member/member_reg.php">注册</a>
</div>

<?php }?>

</div>

</div>
</body>

</html>