<?php
require '../function/conn.php';
require '../function/function.php';

$action=$_GET["action"];
$keyword=str_replace("__"," ",t($_REQUEST["keyword"]));
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo lang($C_description)?>">
    <meta name="author" content="s-cms">
    <title><?php echo lang("搜索页面/l/search")?> - <?php echo lang($C_webtitle)?></title>
    <link href="<?php echo $C_dir.$C_ico?>" rel="shortcut icon" />
    <link href="../member/css/bootstrap.css" rel="stylesheet">
    <link href="../css/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="../member/css/style.css" rel="stylesheet" type="text/css">
    <script src="../member/js/jquery.min.js"></script>
    <script src="../member/js/bootstrap.min.js"></script>
 <style>
.search_pic{padding:5px;border:#CCCCCC solid 1px;width:100%;max-width:150px;min-width:100px;}
    table{width: 100%;}
    table tr td{padding-right: 10px;}
    .search_area{background: #FFFFFF;margin: 0px 0 10px 0;font-size: 14px;}
    .search_area .list{padding: 10px;}
    .search_area td{padding: 10px;}
</style>
</head>

<body>
<div class="search_area">
<form id="userinfo_save" method="POST" action="?action=search" class="form-horizontal" style="padding: 10px;">
<div class="input-group">
                    <input type="text" name="keyword" class="form-control" placeholder="<?php echo lang("输入关键词/l/Input your Keywords")?>" value="<?php echo $keyword?>">
                    <span class="input-group-btn">
                        <button class="btn btn-info" type="submit"><?php echo lang("搜索/l/Search")?></button>
                    </span>
                </div>
</form>
<?php
if ($action=="search" && $keyword!=""){

$sql="select * from ".TABLE."text where T_del=0 and (T_title like '%".$keyword."%' or T_content like '%".$keyword."%' ) order by T_id desc";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
if($C_html==1){
$search_info=$search_info."<div class='list'><table><tr><td colspan='2' ><a href='".$C_dir."html/about/".$row["T_id"].".html' target='_blank'><font size='+1' color='#0066ff'><u>".str_Replace($keyword,"<font color='red'>".$keyword."</font>",lang($row["T_title"]))."</u></font></a></td></tr><tr><td width='20%' align='center' valign='middle'><img src='../".$row["T_pic"]."' class='search_pic'></td><td width='80%'>".str_replace($keyword,"<font color='red'>".$keyword."</font>",mb_substr(strip_tags(lang($row["T_content"])),0,100,"utf-8"))."...<br><font color='#006600'>".$_SERVER["HTTP_HOST"].$C_dir."html/about/".$row["T_id"].".html</font><br> <font color='#777777'>位置：<a href='".$C_dir."index.php'>".lang($C_webtitle)."</a> - <a href='".$C_dir."html/about/".$row["T_id"].".html'>".lang($row["T_title"],lang($row["T_title"]))."</a></font></td></tr></table></div>";
}else{
$search_info=$search_info."<div class='list'><table><tr><td colspan='2' ><a href='../index.php?type=text&S_id=".$row["T_id"]."' target='_blank'><font size='+1' color='#0066ff'><u>".str_Replace($keyword,"<font color='red'>".$keyword."</font>",lang($row["T_title"]))."</u></font></a></td></tr><tr><td width='20%' align='center' valign='middle'><img src='../".$row["T_pic"]."' class='search_pic'></td><td width='80%'>".str_replace($keyword,"<font color='red'>".$keyword."</font>",mb_substr(strip_tags(lang($row["T_content"])),0,100,"utf-8"))."...<br><font color='#006600'>".$_SERVER["HTTP_HOST"].$C_dir."index.php?type=text&S_id=".$row["T_id"]."</font><br> <font color='#777777'>位置：<a href='".$C_dir."../index.php'>".lang($C_webtitle)."</a> - <a href='../index.php?type=text&S_id=".$row["T_id"]."'>".lang($row["T_title"],lang($row["T_title"]))."</a></font></td></tr></table></a></div>";
}
        }
$search1=1;
}else{
$search1=0;
    }

$sql="select * from ".TABLE."news,".TABLE."nsort where N_del=0 and S_del=0 and (N_title like '%".$keyword."%' or N_content like '%".$keyword."%' ) and N_sort=S_id order by N_id desc";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
if($C_html==1){
$search_info=$search_info."<div class='list'><table><tr><td colspan='2' ><a href='".$C_dir."html/news/".$row["N_id"].".html' target='_blank'><font size='+1' color='#0066ff'><u>".str_Replace($keyword,"<font color='red'>".$keyword."</font>",lang($row["N_title"]))."</u></font></a></td></tr><tr><td width='20%' align='center' valign='middle' ><img src='../".$row["N_pic"]."' class='search_pic'></td><td width='80%'>".str_replace($keyword,"<font color='red'>".$keyword."</font>",mb_substr(strip_tags(lang($row["N_content"])),0,100,"utf-8"))."...<br><font color='#006600'>".$_SERVER["HTTP_HOST"].$C_dir."html/news/".$row["N_id"].".html</font><br> <font color='#777777'>位置：<a href='".$C_dir."index.php'>".lang($C_webtitle)."</a> - <a href='".$C_dir."html/news/list-".$row["S_id"].".html'>".lang($row["S_title"])."</a> - <a href='".$C_dir."html/news/".$row["N_id"].".html'>".lang($row["N_title"],lang($row["N_title"]))."</a></font></td></tr></table></div>";
}else{
$search_info=$search_info."<div class='list'><table><tr><td colspan='2' ><a href='../index.php?type=newsinfo&S_id=".$row["N_id"]."' target='_blank'><font size='+1' color='#0066ff'><u>".str_Replace($keyword,"<font color='red'>".$keyword."</font>",lang($row["N_title"]))."</u></font></a></td></tr><tr><td width='20%' align='center' valign='middle' ><img src='../".$row["N_pic"]."' class='search_pic'></td><td width='80%'>".str_replace($keyword,"<font color='red'>".$keyword."</font>",mb_substr(strip_tags(lang($row["N_content"])),0,100,"utf-8"))."...<br><font color='#006600'>".$_SERVER["HTTP_HOST"].$C_dir."index.php?type=newsinfo&S_id=".$row["N_id"]."</font><br> <font color='#777777'>位置：<a href='".$C_dir."index.php'>".lang($C_webtitle)."</a> - <a href='../index.php?type=news&S_id=".$row["S_id"]."'>".lang($row["S_title"])."</a> - <a href='../index.php?type=newsinfo&S_id=".$row["N_id"]."'>".lang($row["N_title"],lang($row["N_title"]))."</a></font></td></tr></table></a></div>";
}
        }
$search2=1;
        }else{
$search2=0;
}

$sql="select * from ".TABLE."product,".TABLE."psort where P_del=0 and S_del=0 and (P_title like '%".$keyword."%' or P_content like '%".$keyword."%' ) and P_sort=S_id order by P_id desc";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
if($C_html==1){
$search_info=$search_info."<div class='list'><table><tr><td colspan='2' ><a href='".$C_dir."html/product/".$row["P_id"].".html' target='_blank'><font size='+1' color='#0066ff'><u>".str_Replace($keyword,"<font color='red'>".$keyword."</font>",lang($row["P_title"]))."</u></font></a></td></tr><tr><td width='20%' align='center' valign='middle' ><img src='../".splitx(splitx($row["P_path"],"|",0),"__",0)."' class='search_pic'></td><td width='80%'>".str_replace($keyword,"<font color='red'>".$keyword."</font>",mb_substr(strip_tags(lang($row["P_content"])),0,100,"utf-8"))."...<br><font color='#006600'>".$_SERVER["HTTP_HOST"].$C_dir."html/productsinfo_".$row["P_id"].".html</font><br> <font color='#777777'>位置：<a href='".$C_dir."index.php'>".lang($C_webtitle)."</a> - <a href='".$C_dir."html/product/list-".$row["S_id"].".html'>".lang($row["S_title"])."</a> - <a href='".$C_dir."html/product/".$row["P_id"].".html'>".lang($row["P_title"],lang($row["P_title"]))."</a></font></td></tr></table></div>";
}else{
$search_info=$search_info."<div class='list'><table><tr><td colspan='2' ><a href='../index.php?type=productinfo&S_id=".$row["P_id"]."' target='_blank'><font size='+1' color='#0066ff'><u>".str_Replace($keyword,"<font color='red'>".$keyword."</font>",lang($row["P_title"]))."</u></font></a></td></tr><tr><td width='20%' align='center' valign='middle' ><img src='../".splitx(splitx($row["P_path"],"|",0),"__",0)."' class='search_pic'></td><td width='80%'>".str_replace($keyword,"<font color='red'>".$keyword."</font>",mb_substr(strip_tags(lang($row["P_content"])),0,100,"utf-8"))."...<br><font color='#006600'>".$_SERVER["HTTP_HOST"].$C_dir."index.php?type=productinfo&S_id=".$row["P_id"]."</font><br> <font color='#777777'>位置：<a href='".$C_dir."index.php'>".lang($C_webtitle)."</a> - <a href='../index.php?type=product&S_id=".$row["S_id"]."'>".lang($row["S_title"])."</a> - <a href='../index.php?type=productinfo&S_id=".$row["P_id"]."'>".lang($row["P_title"],lang($row["P_title"]))."</a></font></td></tr></table></div>";
}
        }
$search3=1;
        }else{
$search3=0;
    }
}

if($search1+$search2+$search3==0 && $keyword!=""){
echo lang("很抱歉，没有找到与\"".$keyword."\"相关的内容！/l/sorry, couldn't find a \"".$keyword."\" related ");
}
if($_SESSION["f"]==1){
echo cnfont($search_info,"f");
}else{
echo cnfont($search_info,"j");
}

?>


</div>
</body>

</html>