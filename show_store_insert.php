<?php
	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>首頁</title>

<style type="text/css">
 #store tr:nth-child(even) {background: #FFF}
 #store tr:nth-child(odd) {background: #FC9}
</style>
<script src="fun.js"></script>
</head>


<body>

<script>
	function show_index(){
	
	operating_static =1;
	if(<?php echo @$_SESSION['competence']?>=='3'){
		var url_value = "instruction=Index&account=<?php echo @$_SESSION['father']?>&month=<?php echo date("Y-m")?>";
	}else{
		var url_value = "instruction=Index&account=<?php echo @$_SESSION['account']?>&month=<?php echo date("Y-m")?>";
	}
	
	ajax('post','indexphp.php',url_value,
		function fun(value){
		var str_content='';
		var acc_content='';
		var data = JSON.parse(value);
		
	//基本資料
	
		acc_content += '<tr>'+
						'<td style ="height:32px;background-color:#D4C2AB;" align="center">'+data['Information'][0]+'</td>'+
						'<td align="center">'+data['Information'][1]+'</td>'+
						'<td style ="background-color:#D4C2AB;">'+data['Information'][3]+'</td>'+
						'<td align="center">'+data['Information'][4]+'</td>'+
						'<td style ="background-color:#D4C2AB;">'+data['Information'][5]+'</td>'+
						'<td><span><input type = "button" name = "update" value ="修改" style="height:32px;width:70px;"></span></td>'+
					   '</tr>';
	var acc = '<table border ="1" cellpadding="0" cellspacing="0" class = "mar" align="left" style="border-color:#d0d0d0"><tr>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:90px;height:35px;" align="center"><span style="font-size:18px">帳號</span></td>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:80px;" align="center"><span style="font-size:18px">姓名</span></td>'+
						'<td style ="background-color:#6D5736;color:#FFF" align="center"><span style="font-size:18px">地址</span></td>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:90px;" align="center"><span style="font-size:18px">電話</span></td>'+
						'<td style ="background-color:#6D5736;color:#FFF" align="center"><span style="font-size:18px">信箱</span></td>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:70px;" align="center"><span style="font-size:18px">修改</span></td></tr>'+
						acc_content+
			  '</table>';
						
		for(var a=0;a<data['sub_account'].length;a++){
			str_content += '<tr>'+
						'<td style ="height:32px;" align="center">'+data['sub_account'][a]['account']+'</td>'+
						'<td><button type="button" name = "set" style="height:32px;width:60px;" value="設定">設定</button></td>'+
						'<td><button type="button" name = "delete" style="height:32px;width:60px;" value="刪除">刪除</button></td>'+
						'</tr>';
		}
	
	var str = '<table border ="1" cellpadding="0" cellspacing="0" class = "mar" align="left" style="border-color:#d0d0d0"><tr>'+
				'<td style="background-color:#556AAA;color:#FFF;height:35px;width:95px;" align="center">子帳號</td>'+
				'<td style="background-color:#556AAA;color:#FFF;width:60px;" align="center">設定</td>'+
				'<td style="background-color:#556AAA;color:#FFF;width:60px;" align="center">刪除</td></tr>'+
				str_content+
			  '</table>';
			var table = '<p></p>'+acc+'<br></br><br></br><br></br>';
			var title ='<p style="background-color:#F2E85F"><span>'+data['Information'][0]+'</span></p>';
								
								if(<?php echo @$_SESSION['competence']?> =='3'){
									right_content1.innerHTML = '';
								}else{
									right_content1.innerHTML = '<br>'+str+'</br>';
								}
								if(<?php echo @$_SESSION['competence']?> =='3'){
									for(var a=0;a<data['sub_account'].length;a++){
										if(data['sub_account'][a]['account']=='<?php echo @$_SESSION['account']?>'){
											if(data['sub_account'][a]['insert_store']=='on'){
												right_content.innerHTML = '<br>'+acc+'</br>';
												right_content2.innerHTML = '<br><a href="insert_store.php?ln=<?php echo $_GET['ln']?>" style="text-decoration:none"><font color="red"><span>新增店家</span></font></a></br>';
											}else{
												right_content.innerHTML ='<font color="red">權限尚未開通</font>';
											}
										}
									}
								}else{
									right_content.innerHTML = '<br>'+acc+'</br>';
									right_content2.innerHTML = '<br><a href="insert_store.php?ln=<?php echo $_GET['ln']?>" style="text-decoration:none"><font color="red"><span>新增店家</span></font></a></br>';
									right_content2.innerHTML += '<br><a href="insert_subaccount.php?ln=<?php echo $_GET['ln']?>" style="text-decoration:none"><font color="red"><span>新增子帳號</span></font></a></br>';
								}
								right_title.innerHTML =title;
								right_page.innerHTML = '';
								var span = document.getElementsByTagName('span');
								var up_del_input = document.getElementsByTagName('input');
								var ln='<?php echo $_GET['ln']?>';
								var setting =document.getElementsByName('set');
								var de =document.getElementsByName('delete');
								//alert (de.length);
								
								if(ln=='cn'){
									span[0].innerHTML=data['Information'][0];
									span[1].innerHTML='帐号';
									span[2].innerHTML='姓名';
									span[3].innerHTML='地址';
									span[4].innerHTML='电话';
									span[5].innerHTML='信箱';
									span[6].innerHTML='修改';
									span[7].innerHTML='<input type = "button" name = "update" value ="修改" style="height:32px;width:70px;">';
									span[8].innerHTML='新增店家';
								}else if(ln=='en'){
									span[0].innerHTML=data['Information'][0];
									span[1].innerHTML='Account';
									span[2].innerHTML='Name';
									span[3].innerHTML='Address';
									span[4].innerHTML='Phone';
									span[5].innerHTML='E-mail';
									span[6].innerHTML='Edit';
									span[7].innerHTML='<input type = "button" name = "update" value ="Edit" style="height:32px;width:70px;">';
									span[8].innerHTML='Add store';
								}
								
								for(var i=0;i<up_del_input.length;i++){
									up_del_input[i].onclick = function(){
										if(this.name == "update"){
											operating_static = 0;
											update_account(right_content);
										}
									}
								}
								
								//設定權限
								for(var i=0;i<setting.length;i++){
									setting[i].index=i; //設立索引方便辨識
									
									setting[i].onclick =function(){
										
										if(data['sub_account'][this.index]['insert_store'] =='on'){
											var date='checked';
										}
										if(data['sub_account'][this.index]['insert_machine'] =='on'){
											var date1='checked';
										}
										if(data['sub_account'][this.index]['insert_commodity'] =='on'){
											var date2='checked';
										}
										if(data['sub_account'][this.index]['import_commodity'] =='on'){
											var date3='checked';
										}
										if(data['sub_account'][this.index]['change_account'] =='on'){
											var date4='checked';
										}
										var table = '<span>帳號</span>：<font color="red">'+data['sub_account'][this.index]['account']+'</font>'+
													'<p><span>姓名</span>：<input type = "text" value = '+data['sub_account'][this.index]['name']+' style = "width:400px;"></p>'+
													'<p><span>密碼</span>：<input type = "text" value ='+data['sub_account'][this.index]['password']+' style = "width:400px;"></p>'+
													'<p><span>地址</span>：<input type = "text" value ='+data['sub_account'][this.index]['address']+' style = "width:400px;"></p>'+
													'<p><span>電話</span>：<input type = "text" value ='+data['sub_account'][this.index]['phone']+' style = "width:400px;"></p>'+
													'<p><span>信箱</span>：<input type = "text" value ='+data['sub_account'][this.index]['email']+' style = "width:400px;"></p>';
										table +='<table border ="1" cellpadding="0" cellspacing="0" class = "mar" align="left" style="border-color:#d0d0d0"><tr>'+
													'<td style="background-color:#556AAA;color:#FFF;width:80px;" align="center">新增商店</td>'+
													'<td style="background-color:#556AAA;color:#FFF;width:80px;" align="center">新增機台</td>'+
													'<td style="background-color:#556AAA;color:#FFF;width:80px;" align="center">新增禮品</td>'+
													'<td style="background-color:#556AAA;color:#FFF;width:80px;" align="center">匯入禮品</td>'+
													'<td style="background-color:#556AAA;color:#FFF;width:80px;" align="center">更變承租</td>'+
													'</tr>'+
													'<tr>'+
													'<td style ="height:32px;" align="center"><input type="checkbox" value="on" name="insert_store"      '+date+' disabled></td>'+
													'<td style ="height:32px;" align="center"><input type="checkbox" value="on" name="insert_machine"    '+date1+' disabled></td>'+
													'<td style ="height:32px;" align="center"><input type="checkbox" value="on" name="insert_commodity"  '+date2+' disabled></td>'+
													'<td style ="height:32px;" align="center"><input type="checkbox" value="on" name="import_commodity"  '+date3+' disabled></td>'+
													'<td style ="height:32px;" align="center"><input type="checkbox" value="on" name="change_account"    '+date4+' disabled></td>'+
													'</tr>'+
												'</table>';
										
										table += '<br></br><br></br><input type="button" id="ok" value="送出">&nbsp;&nbsp;<input type="button" id="cancel" value="取消">';
										right_title.innerHTML = '<p class = "mar" style="background-color:#F2E85F"><span>權限設定</span></p>';
										right_content.innerHTML = '<br>'+table+'</br>';
										right_content1.innerHTML = '';
										right_content2.innerHTML = '';
										right_page.innerHTML = '';
										//alert (this.index);
										
										var input =document.getElementsByTagName('input');
										var ok =document.getElementById('ok');
										var cancel =document.getElementById('cancel');
										var font =document.getElementsByTagName('font');
										
										ok.onclick =function(index){
											if(confirm("確定送出?")){
												operating_static = 1;
												var obj = {
													instruction: 'UpdateSubAccount',
													subaccount:font[0].innerHTML,
													subname:input[0].value,
													subpassword:input[1].value,
													subaddress:input[2].value,
													subphone:input[3].value,
													subemail:input[4].value,
												};
												
												var SetJson = JSON.stringify(obj);
												
												ajax('post','indexphp.php',SetJson,function fun(value){
														var data = JSON.parse(value);
													if(data['EchoDB'] == '1'){
														alert('修改成功');
														show_index();
													}else{
														alert('修改失败');
														show_index();
													}
												});
											}
										}
										cancel.onclick =function(){
											if(confirm('確定取消?')){
												show_index();
											}
										}
									}
								}
								for(var i=0;i<de.length;i++){
									de[i].index=i; //設立索引方便辨識
									
									de[i].onclick = function(){
										if(confirm('確定刪除?')){
										var obj = {
													instruction: 'DeleteSubAccount',
													subaccount:data['sub_account'][this.index]['account'],
												};
										
										var SetJson = JSON.stringify(obj);
										
										ajax('post','indexphp.php',SetJson,function fun(value){
											var data = JSON.parse(value);
											if(data['EchoDB'] == '1'){
												alert('刪除成功');
												show_index();
											}else{
												alert('刪除失敗');
												show_index();
											}	
										});
										}
									}
								}
								
	function update_account(right_content){
		
		var table = '<p><span>帳號</span>：<font color="red">'+data['Information'][0]+'</font></p>'+
					'<p><span>姓名</span>：<input type = "text" value ='+data['Information'][1]+' style = "width:400px;"></p>'+
					'<p><span>密碼</span>：<input type = "text" value ='+data['Information'][2]+' style = "width:400px;"></p>'+
					'<p><span>地址</span>：<input type = "text" value ='+data['Information'][3]+' style = "width:400px;"></p>'+
					'<p><span>電話</span>：<input type = "text" value ='+data['Information'][4]+' style = "width:400px;"></p>'+
					'<p><span>信箱</span>：<input type = "text" value ='+data['Information'][5]+' style = "width:400px;"></p>'+
					'<p><span><input type = "button" id = "update" value ="修改"></span>'+
					 '&nbsp;&nbsp;<span><input type = "button" id = "clear" value ="取消"></span></p>';
		right_title.innerHTML = '<p class = "mar" style="background-color:#F2E85F"><span>帳戶修改</span></p>';
		right_content.innerHTML = table;
		right_content1.innerHTML = '';
		right_content2.innerHTML = '';
		right_page.innerHTML = '';
		
		var span = document.getElementsByTagName('span');
		if(ln=='cn'){
			span[0].innerHTML='帐户修改';
			span[1].innerHTML='帐号';
			span[2].innerHTML='姓名';
			span[3].innerHTML='密码';
			span[4].innerHTML='地址';
			span[5].innerHTML='电话';
			span[6].innerHTML='信箱';
			span[7].innerHTML='<input type = "button" id = "update" value ="修改">';
			span[8].innerHTML='<input type = "button" id = "clear" value ="取消">';
		}else if(ln=='en'){
			span[0].innerHTML='Account Settings';
			span[1].innerHTML='Account';
			span[2].innerHTML='Name';
			span[3].innerHTML='Password';
			span[4].innerHTML='Address';
			span[5].innerHTML='Phone';
			span[6].innerHTML='E-mail';
			span[7].innerHTML='<input type = "button" id = "update" value ="Save Changes">';
			span[8].innerHTML='<input type = "button" id = "clear" value ="Cancel">';
		}
		var up_del_input = right_content.getElementsByTagName('input');
		var update = document.getElementById('update');
		var clear = document.getElementById('clear');
		
		
		clear.onclick = function(){
			if(ln=='cn'){
				if(confirm("确定取消?")){
					operating_static = 1;
					show_index();
				}
			}else if(ln=='en'){
				if(confirm("Really?")){
					operating_static = 1;
					show_index();
				}
			}else{
				if(confirm("確定取消?")){
					operating_static = 1;
					show_index();
				}
			}
		}
		
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
						email:up_del_input[4].value,
					};
					
					var SetJson = JSON.stringify(obj);
					
					ajax('post','indexphp.php',SetJson,function fun(value){
							var data = JSON.parse(value);
						if(data['message'] == '1'){
							alert(data['name'] + '修改成功');
							show_index();
						}else{
							alert(data['name'] + '修改失败');
							show_index();
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
						email:up_del_input[4].value,
					};
					
					var SetJson = JSON.stringify(obj);
					
					ajax('post','indexphp.php',SetJson,function fun(value){
							var data = JSON.parse(value);
						if(data['message'] == '1'){
							alert(data['name'] + 'success');
							show_index();
						}else{
							alert(data['name'] + 'failure');
							show_index();
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
						email:up_del_input[4].value,
					};
					
					var SetJson = JSON.stringify(obj);
					
					ajax('post','indexphp.php',SetJson,function fun(value){
							var data = JSON.parse(value);
						if(data['message'] == '1'){
							alert(data['name'] + '修改成功');
							show_index();
						}else{
							alert(data['name'] + '修改失敗');
							show_index();
						}
					});
				}
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
<div id = "right_content1" style="overflow:auto">
</div>
<div id = "right_content2" style="overflow:auto">
</div>
<div id = "right_page">
</div>
<?php echo "<script type='text/javascript'>show_index();</script>";?>
</body>
</html>
