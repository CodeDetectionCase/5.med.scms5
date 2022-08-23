<?php

$first=json_decode(file_get_contents("data/config.json"))->first;
if($first=="1"){
    Header("Location: install");
    die();
}


require 'function/conn.php';
require 'function/function.php';

$dirx=dirname($_SERVER["SCRIPT_FILENAME"])."/";

$d="mip";



if ($_GET["action"] == "update_dir") {
    mysqli_query($conn, "update ".TABLE."config set C_dir='" . splitx(strtolower($_SERVER["PHP_SELF"]), "mip.php",0) . "'");
    box("更新成功！", "mip.php", "success");
}

if (substr(strtolower($_SERVER["PHP_SELF"]), -7) == "mip.php" && $C_dir != splitx( strtolower($_SERVER["PHP_SELF"]), "mip.php",0)) {
    die("系统检测到您移动了安装目录，是否更新数据库？（<a href='?action=update_dir'>是</a>/否）" . splitx( strtolower($_SERVER["PHP_SELF"]), "mip.php",0));
}


$S_page = $_GET["page"];

if ($_GET["type"] == "") {
    $U_type = "index";
} else {
    $U_type = $_GET["type"];
}

if(isset($_GET["S_id"])){
    $S_id = $_GET["S_id"];
}else{
	$S_id = "0";
}


if ($C_close == 1) {
    Header("Location: close.html");
    die();
}

switch ($U_type) {
    case "index":
        $page_info = e(d(CreateIndex(a($U_type, 1,"mip"))));
        break;

    case "contact":
        $page_info = e(d(CreateContact(a($U_type, 1,"mip"))));
        break;

    case "guestbook":
        $page_info = e(d(CreateGuestbook(a($U_type, 1,"mip"))));
        break;

    case "bbs":
        Header("location:bbs");
        break;

    case "member":
        Header("location:member");
        break;

    case "text":
        if (getrx("select * from ".TABLE."text where T_del=0 and T_id=" . intval($S_id),"T_id") == "") {
            box("菜单指向的简介已被删除，请到“菜单管理”重新编辑", "back", "error");
        } else {
            $page_info = e(d(CreateText(a($U_type, $S_id,"mip"),$S_id)));
        }
        break;

    case "form":
        if (getrx("select * from ".TABLE."form where F_del=0 and F_id=" . intval($S_id),"F_id") == "") {
            box("菜单指向的简介已被删除，请到“菜单管理”重新编辑", "back", "error");
        } else {
            $page_info = e(d(CreateForm(a($U_type, $S_id,"mip"), $S_id)));
        }
        break;

    case "news":
        if (is_numeric($S_id)) {
            if (getrx("select * from ".TABLE."nsort where S_del=0 and S_id=" . intval($S_id),"S_id") == "" && $S_id <> 0) {
                box("菜单指向的新闻分类已被删除，请到“菜单管理”重新编辑", "back", "error");
            } else {
                $page_info = e(d(CreateNewsList(a($U_type, $S_id,"mip"), $S_id, $S_page)));
            }
        } else {
            $page_info = e(d(CreateNewsList(a($U_type, $S_id,"mip"), $S_id, $S_page)));
        }
        break;

    case "newsinfo":
        if (getrx("select * from ".TABLE."news where N_del=0 and N_id=" . intval($S_id),"N_id") == "") {
            box("该新闻不存在或已被删除", "back", "error");
        } else {
            $page_info = e(d(CreateNewsInfo(a($U_type, $S_id,"mip"), $S_id)));
        }
        break;

    case "product":
        if (is_numeric($S_id)) {
            if (getrx("select * from ".TABLE."psort where S_del=0 and S_id=" . intval($S_id),"S_id") == "" && $S_id > 0) {
                box("菜单指向的产品分类已被删除，请到“菜单管理”重新编辑", "back", "error");
            } else {
                $page_info = e(d(CreateProductList(a($U_type, $S_id,"mip") , $S_id, $S_page)));
            }
        } else {
            $page_info = e(d(CreateProductList(a($U_type, $S_id,"mip"), $S_id, $S_page)));
        }
        break;

    case "productinfo":
        if (getrx("select * from ".TABLE."product where P_del=0 and P_id=" . intval($S_id),"P_id") == "") {
            box("该产品不存在或已被删除", "back", "error");
        } else {
            $page_info = e(d(CreateProductInfo(a($U_type, $S_id,"mip"), $S_id)));
        }
        break;

    default:
        box("type参数传入错误！", "back", "error");
}


if ($_SESSION["f"] == 1) {
    echo mip(cnfont($page_info, "f"));
} else {
    echo mip(cnfont($page_info, "j"));
}

?>