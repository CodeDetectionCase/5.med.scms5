<?php 
require '../function/conn.php';
require '../function/function.php';
require 'auto.php';
require 'member_check.php';
require 'left.php';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta name="description" content="<?php echo lang("会员中心/l/Member Center")?>">
  <title><?php echo lang("会员中心/l/Member Center")?></title>
<link href="../<?php echo $C_ico?>" rel="shortcut icon" />

  <!-- Stylesheets -->
    <link rel="stylesheet" href="../css/css/font-awesome.min.css">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/site.min.css">
  <!-- css plugins -->
  <link rel="stylesheet" href="css/icheck.min.css">
 
  <!--[if lt IE 9]>
    <script src="/assets/js/plugins/html5shiv/html5shiv.min.js"></script>
    <![endif]-->
  <!--[if lt IE 10]>
    <link rel="stylesheet" href="/assets/css/ie8.min.css">
    <script src="/assets/js/plugins/respond/respond.min.js"></script>
    <![endif]-->
	<script>
		var _ctxPath='';
	</script>    
</head>

<body class="body-index">

		<?php require 'top.php';?>
		
		
		
		
		
		<div class="container m_top_10">
		<ol class="breadcrumb">
				<li><i class="icon fa-home" aria-hidden="true"></i><a href="../">首页</a></li>
				<li>我的财富</li>
				<li class="active">
				余额明细
				</li>
			</ol>
			<div class="yto-box">
		<div class="row">
	 <div class="col-sm-2 hidden-xs">
	 <div class="my-avatar center-block p_bottom_10">
							<span class="avatar"> 
							  
							    
							      <img alt="..." src="<?php echo $M_pic?>"> 
							    
							    
							  
							</span>
	</div>
	<h5 class="text-center p_bottom_10">您好！<?php echo $M_login?></h5>
	     <ul class="nav nav-pills nav-stacked">
		 <li class="active" ><a href="member_moneylist.php">余额明细</a></li>
		  <li ><a href="member_fenlist.php">积分明细</a></li>
		   <li ><a href="member_role.php">奖励规则</a></li>
<?php if ($C_gifton==1){?><li ><a href="member_gift.php">兑换礼品</a></li><?php }?>
            
	     </ul>
	 </div>
	 <div class="col-sm-10 b-left">
					<div class="yto-box">
						
						<div class="panel panel-default">
							<div class="panel-heading">余额明细</div>
							<div class="table-responsive">
								<table class="table table-bordered">
								 <thead>
									<tr>
										<th>事件</th>
										<th>余额变动</th>
										<th>时间</th>
										<th>审核</th>
									</tr>
									</thead>
									<tbody>
									<?php 

									$sql="select * from ".TABLE."list where L_mid=".$_SESSION["M_id"]." and L_type=0  order by L_id desc";

		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
		if (round($row["L_change"],2)>0){
		$C_info="+";
	}else{
	$C_info="";
		}
		switch($row["L_sh"]){
		case 0:
		$sh_info="<span class='btn btn-xs btn-success'>已通过</span>";
		break;
		case 1:
		$sh_info="<span class='btn btn-xs btn-warning'>未审核</span>";
		break;
		case 2:
		$sh_info="<span class='btn btn-xs btn-danger'>未通过</span>";
		break;
		}
		echo "<tr ><td>".$row["L_title"]."</td><td>".$C_info.round($row["L_change"],2)." 元</td><td>".$row["L_time"]." </td><td>".$sh_info."</td></tr>";
				}
	}

									?>
									</tbody>
								</table>
					</div>
				</div>
				
				
			</div>
		</div>

	</div>
	</div>
	</div>
	</div>

	
		<?php require 'foot.php';?>

	<!-- js plugins  -->
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/icheck.min.js"></script>
	<script src="js/page.js"></script>
	
</body>
</html>