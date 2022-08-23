<?php 
require '../function/conn.php';
require '../function/function.php';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo lang($C_description)?>">
    <meta name="author" content="s-cms">
    <title>论坛 - <?php echo lang($C_webtitle)?></title>
    <link href="<?php echo $C_dir.$C_ico?>" rel="shortcut icon" />
    <link href="../member/css/bootstrap.css" rel="stylesheet">
    <link href="../css/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="../member/css/style.css" rel="stylesheet" type="text/css">
    <script src="../member/js/jquery.min.js"></script>
    <script src="../member/js/bootstrap.min.js"></script>
    
</head>

<body style="background: rgba(255,255,255,0);">
<?php 
$sql="Select * from ".TABLE."bsort where S_del=0 and S_hide=0 order by S_order,S_id desc";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$S_sh=$row["S_sh"];
$S_lv=$row["S_lv"];

if($S_sh==1){
$sh_info="发帖限制：需要审核";
$sql2="Select count(*) as B_count from ".TABLE."bbs where B_del=0 and B_sort=".$row["S_id"]." and B_sh=1 and B_sub=0";
}else{
$sh_info="发帖限制：无需审核";
$sql2="Select count(*) as B_count from ".TABLE."bbs where B_del=0 and B_sort=".$row["S_id"]." and B_sub=0";
}
if($S_lv==0){
$lv_info="浏览权限：游客";
}else{
$lv_info="浏览权限：".getrx("select * from ".TABLE."lv where L_id=".$S_lv,"L_title");
}

$result2 = mysqli_query($conn, $sql2);
$row2 = mysqli_fetch_assoc($result2);
$B_count=$row2["B_count"];
if($S_sh==0){
$sql2="Select count(*) as B_count from ".TABLE."bbs where B_del=0 and B_sort=".$row["S_id"]." and year(B_time)=".date("Y",strtotime(date('Y-m-d H:i:s')))." and month(B_time)=".date("m",strtotime(date('Y-m-d H:i:s')))." and day(B_time)=".date("d",strtotime(date('Y-m-d H:i:s')))." and B_sub=0";
}else{
$sql2="Select count(*) as B_count from ".TABLE."bbs where B_del=0 and B_sort=".$row["S_id"]." and year(B_time)=".date("Y",strtotime(date('Y-m-d H:i:s')))." and month(B_time)=".date("m",strtotime(date('Y-m-d H:i:s')))." and day(B_time)=".date("d",strtotime(date('Y-m-d H:i:s')))." and B_sh=1 and B_sub=0";
}

$result2 = mysqli_query($conn, $sql2);
$row2 = mysqli_fetch_assoc($result2);
$B_count2=$row2["B_count"];
if($B_count==""){
$B_count=0;
}
if($B_count2==""){
$B_count2=0;
}

echo "<a href=\"list.php?id=".$row["S_id"]."\"><div class=\"col-xs-12 col-sm-6\"><div class=\"panel panel-primary\"><div class=\"panel-heading\"><h3 class=\"panel-title\">".lang($row["S_title"])."</h3></div><div class=\"panel-body\"><div style=\"display:inline-block\"><img src=\"../".$row["S_pic"]."\" style=\"width:70px;height:70px;border-radius:10px;margin-right:20px;margin-top:-60px;\"></div><div style=\"display:inline-block\"><p><b>".lang($row["S_content"])."</b></p><p>".$sh_info."</p><p>".$lv_info."</p><p>(<span style='color:#0099ff'>今日：".$B_count2."</span> / 总数：".$B_count.")</p></div></div></div></div></a>";
}
}
?>
</body>
</html>