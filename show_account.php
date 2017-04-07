<?php
	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>無標題文件</title>
<script src="fun.js"></script>
</head>

<body>

<script>
	function show_account(){
	var right_title = document.getElementById("right_title");
	var right_content = document.getElementById('right_content');
	var right_page = document.getElementById('right_page');
	operating_static =1;
	var url_value = "instruction=Tenant&tenant=<?php echo $_GET['account']?>";
	var ln ='<?php echo $_GET['ln']?>';
	
	ajax('post','indexphp.php',url_value,
		function fun(value){
		var str_content='';
		var data = JSON.parse(value);
		
			//基本資料
			var table = 
					'<p>帳號：<font color="red">'+data['Information'][0]+'</font></p>'+
					'<p>姓名：<input type = "text" value = '+data['Information'][1]+' style = "width:400px;"></p>'+
					'<p>密碼：<input type = "text" value ='+data['Information'][2]+' style = "width:400px;"></p>'+
					'<p>地址：<input type = "text" value ='+data['Information'][3]+' style = "width:400px;"></p>'+
					'<p>電話：<input type = "text" value ='+data['Information'][4]+' style = "width:400px;"></p>'+
					'<p>信箱：<input type = "text" value ='+data['Information'][5]+' style = "width:400px;"></p>'+
					'<p><input type = "button" id = "update" value ="修改">'+
					'&nbsp;<input type = "button" id = "clear" value ="取消">&nbsp;&nbsp;&nbsp;<input type = "button" id = "show_password" value ="顯示密碼"></p>';
			var table_cn = 
					'<p>帐号：<font color="red">'+data['Information'][0]+'</font></p>'+
					'<p>姓名：<input type = "text" value = '+data['Information'][1]+' style = "width:400px;"></p>'+
					'<p>密码：<input type = "text" value ='+data['Information'][2]+' style = "width:400px;"></p>'+
					'<p>地址：<input type = "text" value ='+data['Information'][3]+' style = "width:400px;"></p>'+
					'<p>电话：<input type = "text" value ='+data['Information'][4]+' style = "width:400px;"></p>'+
					'<p>信箱：<input type = "text" value ='+data['Information'][5]+' style = "width:400px;"></p>'+
					'<p><input type = "button" id = "update" value ="修改">'+
					'&nbsp;<input type = "button" id = "clear" value ="取消">&nbsp;&nbsp;&nbsp;<input type = "button" id = "show_password" value ="显示密码"></p>';
			var table_en = 
					'<p>Account：<font color="red">'+data['Information'][0]+'</font></p>'+
					'<p>Name：<input type = "text" value = '+data['Information'][1]+' style = "width:400px;"></p>'+
					'<p>Password：<input type = "text" value ='+data['Information'][2]+' style = "width:400px;"></p>'+
					'<p>Address：<input type = "text" value ='+data['Information'][3]+' style = "width:400px;"></p>'+
					'<p>Phone：<input type = "text" value ='+data['Information'][4]+' style = "width:400px;"></p>'+
					'<p>E-mail：<input type = "text" value ='+data['Information'][5]+' style = "width:400px;"></p>'+
					'<p><input type = "button" id = "update" value ="Save">'+
					'&nbsp;<input type = "button" id = "clear" value ="Cancel">&nbsp;&nbsp;&nbsp;<input type = "button" id = "show_password" value ="Show password"></p>';
					
			if(<?php echo @$_SESSION['competence']?> =='3'){
				if('<?php echo @$_GET['change_account']?>'=='on'){
					if(ln=='cn'){
						right_content.innerHTML =table_cn;
					}else if(ln=='en'){
						right_content.innerHTML =table_en;
					}else{
						right_content.innerHTML = table;
					}
				}else{
					right_content.innerHTML ='<font color="red">權限尚未開通</font>';
				}
			}else{
				if(ln=='cn'){
					right_content.innerHTML =table_cn;
				}else if(ln=='en'){
					right_content.innerHTML =table_en;
				}else{
					right_content.innerHTML = table;
				}
			}
			right_title.innerHTML = '<p style="background-color:#F2E85F"><span>基本資料</span></p>';
			right_page.innerHTML = '';
			var p = document.getElementsByTagName('p');
			p[3].style.display="none";
			var update = document.getElementById('update');
			var clear = document.getElementById('clear');
			var up_del_input = document.getElementsByTagName('input');
			var span =document.getElementsByTagName('span');
			
			if(ln=='cn'){
				span[0].innerHTML='基本资料';
			}else if(ln=='en'){
				span[0].innerHTML='Basic information';
			}
			//點擊取消
			clear.onclick = function(){
				if(ln=='cn'){
					if(confirm("确定取消?")){
						operating_static = 1;
						show_account();
					}
				}else if(ln=='en'){
					if(confirm("Really?")){
						operating_static = 1;
						show_account();
					}
				}else{
					if(confirm("確定取消?")){
						operating_static = 1;
						show_account();
					}
				}
			}
			
			//點擊修改
			update.onclick = function(){
				if(ln=='cn'){
					if(confirm("确定修改?")){
						operating_static = 1;
						var obj = {
							instruction: 'UpdateAccount',
							account:data['Information'][0],
							name:up_del_input[0].value,
							pw:up_del_input[1].value,
							address:up_del_input[2].value,
							phone:up_del_input[3].value,
							email:up_del_input[4].value
						};
						
						var SetJson = JSON.stringify(obj);
						
						ajax('post','indexphp.php',SetJson,function fun(value){
							var data = JSON.parse(value);
							var total = '';
							if(data['message'] == '1'){
								alert(data['name'] + '修改成功');
							}else{
								alert(data['name'] + '修改失败');
							}
						});
					}
				}else if(ln=='en'){
					if(confirm("Really Save?")){
						operating_static = 1;
						var obj = {
							instruction: 'UpdateAccount',
							account:data['Information'][0],
							name:up_del_input[0].value,
							pw:up_del_input[1].value,
							address:up_del_input[2].value,
							phone:up_del_input[3].value,
							email:up_del_input[4].value
						};
						
						var SetJson = JSON.stringify(obj);
						
						ajax('post','indexphp.php',SetJson,function fun(value){
							var data = JSON.parse(value);
							var total = '';
							if(data['message'] == '1'){
								alert(data['name'] + 'Save success');
							}else{
								alert(data['name'] + 'Save failure');
							}
						});
					}
				}else{
					if(confirm("確定修改?")){
						operating_static = 1;
						var obj = {
							instruction: 'UpdateAccount',
							account:data['Information'][0],
							name:up_del_input[0].value,
							pw:up_del_input[1].value,
							address:up_del_input[2].value,
							phone:up_del_input[3].value,
							email:up_del_input[4].value
						};
						
						var SetJson = JSON.stringify(obj);
						
						ajax('post','indexphp.php',SetJson,function fun(value){
							var data = JSON.parse(value);
							var total = '';
							if(data['message'] == '1'){
								alert(data['name'] + '修改成功');
							}else{
								alert(data['name'] + '修改失敗');
							}
						});
					}
				}
			}
			
			//密碼顯示
			up_del_input[7].onclick = function(){
				if(ln=='cn'){
					if(up_del_input[7].value!='隐藏密码'){
						p[3].style.display="block";
						up_del_input[7].value='隐藏密码';
					}else{
						p[3].style.display="none";
						up_del_input[7].value='显示密码';
					}
				}else if(ln=='en'){
					if(up_del_input[7].value!='Hide password'){
						p[3].style.display="block";
						up_del_input[7].value='Hide password';
					}else{
						p[3].style.display="none";
						up_del_input[7].value='Show password';
					}
				}else{
					if(up_del_input[7].value!='隱藏密碼'){
						p[3].style.display="block";
						up_del_input[7].value='隱藏密碼';
					}else{
						p[3].style.display="none";
						up_del_input[7].value='顯示密碼';
					}
				}
			}
		
		})
		
}

</script>

<div id = "right_title">
	<p class = "mar"></p>
</div>
<div id = "right_content" style="overflow:auto">
</div>
<div id = "right_page">
</div>
<?php echo "<script type='text/javascript'>show_account();</script>";?>
</body>
</html>
