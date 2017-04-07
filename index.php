<?php
session_start();
header("Content-Type: text/html; charset=utf-8");
require('..\Mysql\Mysql_Connect.php');
	
	//繫結登入會員資料

	$store_number =null;
	$store_name =null;
	$machine_name =null;
	$machine_name1 =null;
	$machine_number =null;
	$machine_number1 =null;
	$Account =null;
	
	//判斷權限
	if(@$_SESSION['competence'] =='3'){
		$query_store = "select store_data.store_number,name from data_match,store_data where data_match.store_number = store_data.store_number and account = '".@$_SESSION['father']."' group by data_match.store_number order by id";
	}else{
		$query_store = "select store_data.store_number,name from data_match,store_data where data_match.store_number = store_data.store_number and account = '".@$_SESSION['account']."' group by data_match.store_number order by id";
	}
$store = mysql_query($query_store);

//抓取商店名
while($row_store=mysql_fetch_array($store)){
	$store_number[]=$row_store["store_number"];
	$store_name[]=$row_store["name"];
	//echo $row_store["name"];
}

for($i=0;$i<count($store_name);$i++){
$query_machine ="select machine_data.machine_number,machine_name
					from data_match,machine_data
					where data_match.machine_number = machine_data.machine_number
					and store_number = '$store_number[$i]'
					group by machine_number";

$machine = mysql_query($query_machine);

//抓取機台名
while($row_machine=mysql_fetch_array($machine)){
	
	$machine_number[]=$row_machine["machine_number"];
	$machine_name[]=$row_machine["machine_name"];
	$machine_name1[]=$row_machine["machine_name"];
	$machine_number1[]=$row_machine["machine_number"];
	//echo $row_machine["machine_name"];
}
	$machine_name[] =$store_name[$i];
	$machine_number[]=$store_number[$i];
}

for($i=0;$i<count($machine_number1);$i++){
	$query_account ="select account_other_information.account
					   from machine_data,data_match,account_other_information
					   where data_match.account = account_other_information.account
					   and machine_data.machine_number = data_match.machine_number
					   and machine_data.machine_number = '$machine_number1[$i]'
					   and competence = '2'";
	
$account = mysql_query($query_account);
$row_account=mysql_fetch_array($account);
if($row_account["account"]==''){$row_account["account"]='尚未承租';}
	$Account[]=$machine_name1[$i];
	$Account[]=$row_account["account"];
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>首頁</title>
<link href="../all.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="tree.js"></script>
<script language="JavaScript" src="fun.js"></script>
</head>

<body>
<?php
	if(@$_SESSION['account'] != null){
?>

<div class="container">
  <div id="header2" align="left">
	<table  class="user" align="left">
	<tr>
		<td><font color="#545454" style="font-family:'微軟正黑體 Light'"><span style="font-size:20px">夾娃娃機管理系統</span></font></td>
	</tr>
	</table>
	
	<table align="right" class='user'>
	<tr>
		<td><font color="#0000CC">姓名：<span><?php echo @$_COOKIE['name']?></span></font></td>
	</tr>
    <tr>
		<td><font color="#0000CC">帳戶：<span title="<?php echo @$_SESSION['account']?>"><?php echo @$_SESSION['account']?></span></font><a href = "login.html" id = "logout">登出</a></td>
	</tr>
	</table>
    
    <!-- end .header --></div>
	<div id="menu1">
	<ul id="menu">
		<li><a href="index.php?ln=<?php echo $_GET['ln']?>" style="font-size:24px">首頁</a></li>
        <li><a href="management.php?ln=<?php echo $_GET['ln']?>" style="font-size:24px">管理</a></li>
		<li><a href="import.php?ln=<?php echo $_GET['ln']?>" style="font-size:24px">匯入</a></li>
	</ul>
	</div>
    <div id="marquee" ><marquee bgcolor="#FD3F35">歡迎使用</marquee></div>
  <div class="content">
    <div class = 'left_div' align="left" style="overflow:auto">
		<div style ="height:350px;">
		
			<script type="text/javascript">
			var font =document.getElementsByTagName('font');
			var a =document.getElementsByTagName('a');
			var marquee =document.getElementsByTagName('marquee');
			var logout = document.getElementById("logout");
			var ln='<?php echo $_GET['ln']?>';
			
			//語言更換
			if(ln=='cn'){
				font[0].innerHTML='<span style="font-size:20px">夹娃娃机管理系统</span>';
				font[1].innerHTML='姓名：<span><?php echo @$_COOKIE['name']?></span>';
				font[2].innerHTML='帐户：<span title="<?php echo @$_SESSION['account']?>"><?php echo @$_SESSION['account']?></span>';
				a[0].innerHTML='登出';
				a[1].innerHTML='首页';
				a[2].innerHTML='管理';
				marquee[0].innerHTML='欢迎使用';
			}else if(ln=='en'){
				font[0].innerHTML='<span style="font-size:20px">Claw Machine Management System</span>';
				font[1].innerHTML='Name：<span><?php echo @$_COOKIE['name']?></span>';
				font[2].innerHTML='Account：<span title="<?php echo @$_SESSION['account']?>"><?php echo @$_SESSION['account']?></span>';
				a[0].innerHTML='Sign out';
				a[1].innerHTML='Home';
				a[2].innerHTML='Manage';
				marquee[0].innerHTML='Welcome';
			}
			
			//樹狀目錄
			if(<?php echo @$_SESSION['competence']?>=='3'){
				foldersTree = gFld("<?php echo @$_SESSION['father']?>",'home.php?ln='+ln);
			}else{
				foldersTree = gFld("<?php echo @$_SESSION['account']?>",'home.php?ln='+ln);
			}
			var store_name = <?php echo json_encode($store_name); ?>;
			var store_number = <?php echo json_encode($store_number);?>;
			var store_count = <?php echo(count($store_name)); ?>;
			var machine_name = <?php echo json_encode($machine_name);?>;
			var machine_number = <?php echo json_encode($machine_number);?>;
			var machine_count = <?php echo(count($machine_name)); ?>;
			var machine_name1 = <?php echo json_encode($machine_name1);?>;
			var account = <?php echo json_encode($Account); ?>;
			var account_count = <?php echo(count($Account)); ?>;
			
			var aux='';
			var j=0;
			for(var i=0;i<store_count;i++){
				var show_store = "show_store.php?store_number="+store_number[i]+'&ln='+ln;
				auxi = insFld(foldersTree, gFld(store_name[i],show_store));
			
				for(j;j<machine_count;j++){
					if(store_name[i]!=machine_name[j]){
						if(machine_name[j]!='沒有機台'){
							var show_mac = "show_mac.php?store_number="+store_number[i]+'&machine_number='+machine_number[j]+'&ln='+ln;
							insDoc(auxi, gLnk(0,machine_name[j],show_mac));
						}
						
						//aux1j = insFld(auxi, gFld(machine_name[j],show_mac));
						
						/* for(var k=0;k<account_count;k++){
							if(account[k]==machine_name[j]){
								if(account[k+1]=='尚未承租'){
									var post_mac = "insert_account.php?store_number="+store_number[i]+'&machine_number='+machine_number[j];
									insDoc(aux1j, gLnk(0, "新增承租", post_mac));
								}else{
									var show_account ="show_account.php?account="+account[k+1];
									insDoc(aux1j, gLnk(0,account[k+1],show_account));
								}
							}
							
						} */
						
					}else{
						j=j+1;
						break;
					}
				}
			}
				//aux12 = insFld(aux1, gFld("機台1","機台資料"));
				//insDoc(aux22, gLnk(0, "子帳號1", "連結網址"));
				
			initializeDocument();
			logout.onclick = function (){
				var url_value = "instruction=Logout";
				ajax('post','indexphp.php',url_value,'');
			}
			</script>
		</div>
	</div>
    <div class = 'right_div' align="right">
		<iframe name="right" frameborder="0" class="room_select_show_iframe" scrolling="yes" style="height:700px;width:100%;" align="left" src="home.php?ln=<?php echo $_GET['ln']?>">
		</iframe>
	</div>
    <!-- end .content --></div>
  <!-- end .container --></div>
<?php
	}else{
		echo "<script>alert('請重新登入');</script>";
		echo "<meta http-equiv=REFRESH CONTENT=0;url=login.html>";
	}
?>
</body>
</html>