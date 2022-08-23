<?php
$body=$_GET["body"];

if (strpos($body,"|")!==false){
	echo "<script>window.location='../../member/index.php';</script>";
}else{
	echo "<script>window.location='../../member/member_order.php';</script>";
}

?>