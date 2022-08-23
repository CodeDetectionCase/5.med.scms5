<?php 
require '../function/conn.php';
require '../function/function.php';

$id=floor($_REQUEST["id"]);

$_SESSION["from"]=$C_dir."bbs/list.php?id=".$id;
$sql="Select * from ".TABLE."bsort where S_del=0 and S_id=".$id;

    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if (mysqli_num_rows($result) > 0) {
    $S_title=lang($row["S_title"]);
    $S_content=lang($row["S_content"]);
    $S_picx=$row["S_pic"];
    $S_lv=$row["S_lv"];
    $S_sh=$row["S_sh"];
}

if($S_lv>0){
$S_fen=getrx("select * from ".TABLE."lv where L_id=".$S_lv,"L_fen");
$L_title=getrx("select * from ".TABLE."lv where L_id=".$S_lv,"L_title");
if($_SESSION["M_id"]==""){
box("该板块需要登录会员后浏览！",$C_dir."member","error");
}else{
if(getrx("select * from ".TABLE."member where M_id=".$_SESSION["M_id"],"M_fen")-$S_fen<0){
box("本板块浏览等级限制为“".$L_title."”，请先升级！",$C_dir."member/member_role.php","error");
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
    <title><?php echo $S_title?> - <?php echo lang($C_webtitle)?></title>
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
    <div class="col-xs-3" style="height: 100%">
        <div style="text-align: center;padding: 10px;height: 100%">
            <div style="font-weight: bold;margin: 20px;"><?php echo $S_title?></div>
            <img src="../<?php echo $S_picx?>" style="width:120px;height:120px;border-radius:10px; ">
            <p style="margin: 10px;"><?php echo $S_content?></p>
            <div><a href="bbs.php?S_id=<?php echo $id?>" class="btn btn-primary btn-sm">发帖</a> <a href="index.php" class="btn btn-info btn-sm">返回首页</a></div>
        </div>
    </div>
    <div class="col-xs-9">
<div class="list-group">
<a href="item.php?id=9" class="list-group-item active" style="height:55px;"> <span style="font-weight:bold">帖子</span><span style="float:right;font-size:12px;">作者<br>时间</span></a>

<?php 
if ($S_sh==0){
$sql="select * from ".TABLE."bbs where B_del=0 and B_sort=".$id." and B_sub=0 order by B_id desc";
}else{
$sql="select * from ".TABLE."bbs where B_del=0 and B_sort=".$id." and B_sub=0 and B_sh=1 order by B_id desc";
}


$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
echo "<a href=\"item.php?id=".$row["B_id"]."\" class=\"list-group-item\" style=\"height:55px;\"><span style=\"color:#AAAAAA;\">[".$S_title."]</span> <span style=\"font-weight:bold\">".lang($row["B_title"])."</span><span style=\"float:right;font-size:12px;\">".getrx("select * from ".TABLE."member where M_id=".$row["B_mid"],"M_login")."<br>".$row["B_time"]."</span></a>";
}
}
?>

</div>
    </div>
</div>
</div>
</body>
</html>