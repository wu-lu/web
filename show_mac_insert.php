<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>store</title>

<style type="text/css">
 #com tr:nth-child(even) {background: #FFF}
 #com tr:nth-child(odd) {background: #BFC7DF}
</style>
<script src="fun.js"></script>
</head>

<body>
<?php
	session_start();
?>
<script type="text/javascript">
	function show_store(){
	
	var right_title = document.getElementById("right_title");
	var right_content = document.getElementById('right_content');
	var right_content1 = document.getElementById('right_content1');
	var right_page = document.getElementById('right_page');
	var url_value = "instruction=IndexStore&store_number=<?php echo $_GET['store_number']?>&month=<?php echo date("Y-m")?>";
	
	ajax('post','indexphp.php',url_value,
		function fun(value){
		var str_content='';
		var data = JSON.parse(value);
		var str = '';
		var sto_content='';
		
		sto_content += '<tr>'+
						'<td style ="background-color:#D4C2AB;height:32px;" align="center">'+data['Information'][0]+'</td>'+
						'<td align="center">'+data['Information'][1]+'</td>'+
						'<td style ="background-color:#D4C2AB;" align="center">'+data['Information'][2]+'</td>'+
						'<td>'+data['Information'][3]+'</td>'+
						'<td style ="background-color:#D4C2AB;"><span><input type = "button" name = "update" style="height:32px;width:60px;" value ="修改"></span></td>'+
					   '</tr>';
		
		var sto = '<table border ="1" cellpadding="0" cellspacing="0" class = "mar" align="left" style="border-color:#d0d0d0"><tr>'+
					'<td style ="background-color:#6D5736;color:#FFF;width:110px;height:35px;" align="center"><span>店家編號</span></td>'+
					'<td style ="background-color:#6D5736;color:#FFF;width:90px" align="center"><span>店家名稱</span></td>'+
					'<td style ="background-color:#6D5736;color:#FFF;width:90px;" align="center"><span>電話</span></td>'+
					'<td style ="background-color:#6D5736;color:#FFF" align="center"><span>地址</span></td>'+
					'<td style ="background-color:#6D5736;color:#FFF;width:60px;" align="center"><span>修改</span></td></tr>'+
					sto_content+
				  '</table>';
						
						var title= '<p style="background-color:#F2E85F"><span>'+data['Information'][1]+'</span></p>';
						
						var url_value = "instruction=ShowCommodity&store_number=<?php echo $_GET['store_number']?>";
						ajax('post','indexphp.php',url_value,
							function fun(value){
								var data = JSON.parse(value);
								var example_content='';
								for(var a=0;a<data['commodity'].length;a++){
									example_content += '<tr>'+'<td>'+data['commodity'][a][0]+'</td>'
										 				+'<td align="right">'+data['commodity'][a][1]+'</td>'
										 				+'<td align="right">'+data['commodity'][a][2]+'</td>'
														+'<td><input type = "button" name = "update_com" value ="修改"></td>'
														+'<td><input type = "button" name = "de" value ="刪除"></td>'
												      +'</tr>';
								}
								
								//品項修改
								var example='<table border ="1" cellpadding="0" cellspacing="0" class = "mar" align="left" id="com" style="border-color:#d0d0d0;"><tr><td style="background-color:#556AAA;color:#FFF;width:140px;height:25px">編號</td><td style="background-color:#556AAA;color:#FFF;">名稱</td><td style="background-color:#556AAA;color:#FFF;width:60px;">價格</td>'+
								'<td style="background-color:#556AAA;color:#FFF;">修改</td>'+'<td style="background-color:#556AAA;color:#FFF;">刪除</td>'
								+'</tr>'+
								example_content;
								if(<?php echo @$_SESSION['competence']?> =='3'){
									right_content1.innerHTML='';
								}else{
									right_content1.innerHTML='<br>'+example;
								}
								var update = document.getElementsByName('update_com');
								var de = document.getElementsByName('de');
								
								for(var i=0;i<de.length;i++){
									de[i].index=i;
									de[i].onclick =function(){
									if(confirm('確定刪除?')){
										var obj = {
													instruction: 'DeleteCommodity',
													store_number:'<?php echo $_GET['store_number']?>',
													commodity_number:data['commodity'][this.index][0]
													};
												
										var SetJson = JSON.stringify(obj);
										ajax('post','indexphp.php',SetJson,function fun(value){
												var data = JSON.parse(value);
												if(data['EchoDB'] == '1'){
													alert('刪除成功');
													show_store();
												}else{
													alert('刪除失败');
													show_store();
												}
										});
									}
									}
								}
								for(var i=0;i<update.length;i++){
									update[i].index=i;
									update[i].onclick =function(){
										var table = '<p><span>品項編號</span>：<font color="red">'+data['commodity'][this.index][0]+'</font></p>'+
													'<p><span>名稱</span>：<input type = "text" value = '+data['commodity'][this.index][1]+' style = "width:400px;"></p>'+
													'<p><span>價格</span>：<input type = "text" value ='+data['commodity'][this.index][2]+' style = "width:400px;"></p>'+
													'<p><span><input type = "button" id = "update" value ="修改"></span>'+
													'&nbsp;&nbsp;<span><input type = "button" id = "cancel" value ="取消"></span></p>';
										right_title.innerHTML = '<p class = "mar" style="background-color:#F2E85F"><span>品項修改</span></p>';
										right_content.innerHTML = table;
										right_content1.innerHTML = '';
										right_content2.innerHTML = '';
										right_page.innerHTML = '';
										
										var up =document.getElementById('update');
										var cancel = document.getElementById('cancel');
										var input = document.getElementsByTagName('input');
										var font = document.getElementsByTagName('font');
										
										//點擊取消
										cancel.onclick = function(){
											if(confirm('確定取消?')){
												show_store();
											}
										}
										
										//點擊修改
										up.onclick =function(){
											if(confirm('確定修改?')){
											var obj = {
														instruction: 'UpdateCommodity',
														store_number:'<?php echo $_GET['store_number']?>',
														commodity_number:font[0].innerHTML,
														name:input[0].value,
														unit:input[1].value
													  };
											
											var SetJson = JSON.stringify(obj);
											
											ajax('post','indexphp.php',SetJson,function fun(value){
												var data = JSON.parse(value);
												if(data['EchoDB'] == '1'){
													alert('修改成功');
													show_store();
												}else{
													alert('修改失败');
													show_store();
												}
											});
											}
										}
									}
								}
							})
						
						//權限判斷
						if(<?php echo @$_SESSION['competence']?> =='3'){
							if('<?php echo @$_GET['insert_machine']?>'=='on'){
								right_content.innerHTML = '<br>'+sto+'</br>';
								right_content2.innerHTML = '<br><a href="insert_mac.php?store_number=<?php echo $_GET['store_number']?>&ln=<?php echo $_GET['ln']?>" style="text-decoration:none"><font color="red"><span>新增機台</span></font></a></br>';
							}else{
								right_content.innerHTML ='<font color="red">權限尚未開通</font>';
							}
							if('<?php echo @$_GET['insert_commodity']?>'=='on'){
								right_content.innerHTML = '<br>'+sto+'</br>';
								right_content2.innerHTML += '<br><a href="insert_commodity.php?store_number=<?php echo $_GET['store_number']?>&ln=<?php echo $_GET['ln']?>" style="text-decoration:none"><font color="red"><span>新增品項</span></font></a></br>';
							}
						}else if(<?php echo @$_SESSION['competence']?> == '4'){
							right_content.innerHTML = '<br>'+sto+'</br>';
							right_content2.innerHTML = '<br><a href="insert_mac.php?store_number=<?php echo $_GET['store_number']?>&ln=<?php echo $_GET['ln']?>" style="text-decoration:none"><font color="red"><span>新增機台</span></font></a></br>';
						}else{
							right_content.innerHTML = '<br>'+sto+'</br>';
							right_content2.innerHTML = '<br><a href="insert_mac.php?store_number=<?php echo $_GET['store_number']?>&ln=<?php echo $_GET['ln']?>" style="text-decoration:none"><font color="red"><span>新增機台</span></font></a></br>';
							right_content2.innerHTML += '<br><a href="insert_commodity.php?store_number=<?php echo $_GET['store_number']?>&ln=<?php echo $_GET['ln']?>" style="text-decoration:none"><font color="red"><span>新增品項</span></font></a></br>';
						}
						right_title.innerHTML = title;
						right_page.innerHTML = '';
						
						var ln='<?php echo $_GET['ln']?>';
						var up_del_input = document.getElementsByTagName('input');
						var span = document.getElementsByTagName('span');
						if(ln=='cn'){
							span[0].innerHTML=data['Information'][1];
							span[1].innerHTML='店家编号';
							span[2].innerHTML='店家名称';
							span[3].innerHTML='电话';
							span[4].innerHTML='地址';
							span[5].innerHTML='修改';
							span[6].innerHTML='<input type = "button" name = "update" style="height:32px;width:60px;" value ="修改">';
							span[7].innerHTML='新增机台';
						}else if(ln=='en'){
							span[0].innerHTML=data['Information'][1];
							span[1].innerHTML='Store No.';
							span[2].innerHTML='Store name';
							span[3].innerHTML='Phone';
							span[4].innerHTML='Address';
							span[5].innerHTML='Edit';
							span[6].innerHTML='<input type = "button" name = "update" style="height:32px;width:60px;" value ="Edit">';
							span[7].innerHTML='Add machine';
						}
						for(var i=0;i<up_del_input.length;i++){
							up_del_input[i].onclick = function(){
								if(this.name == "update"){
									operating_static = 0;
									update_store(right_content);
								}
							}
						}
	
	//店資料修改
	function update_store(right_content){
		
		var table = '<p><span>店家編號</span>：<font color="red">'+data['Information'][0]+'</font></p>'+
						'<p><span>店家名稱</span>：<input type = "text" value = '+data['Information'][1]+' style = "width:400px;"></p>'+
						'<p><span>電話</span>：<input type = "text" value ='+data['Information'][2]+' style = "width:400px;"></p>'+
						'<p><span>地址</span>：<input type = "text" value ='+data['Information'][3]+' style = "width:400px;"></p>'+
						'<p><span><input type = "button" id = "update" value ="修改"></span>'+
						'&nbsp;&nbsp;<span><input type = "button" id = "clear" value ="取消"></span></p>';
		right_title.innerHTML = '<p class = "mar" style="background-color:#F2E85F"><span>店家修改</span></p>';
		right_content.innerHTML = table;
		right_content1.innerHTML = '';
		right_content2.innerHTML = '';
		right_page.innerHTML = '';
		
		var span = document.getElementsByTagName('span');
		if(ln=='cn'){
			span[0].innerHTML='店家修改';
			span[1].innerHTML='店家编号';
			span[2].innerHTML='店家名称';
			span[3].innerHTML='电话';
			span[4].innerHTML='地址';
			span[5].innerHTML='<input type = "button" id = "update" value ="修改">';
			span[6].innerHTML='<input type = "button" id = "clear" value ="取消">';
		}else if(ln=='en'){
			span[0].innerHTML='Store Settings';
			span[1].innerHTML='Store No.';
			span[2].innerHTML='Store name';
			span[3].innerHTML='Phone';
			span[4].innerHTML='Address';
			span[5].innerHTML='<input type = "button" id = "update" value ="Save Changes">';
			span[6].innerHTML='<input type = "button" id = "clear" value ="Cancel">';
		}
		var up_del_input = right_content.getElementsByTagName('input');
		var update = document.getElementById('update');
		var clear = document.getElementById('clear');
		
		//點擊取消
		clear.onclick = function(){
			if(ln=='cn'){
				if(confirm("确定取消?")){
					operating_static = 1;
					show_store();
				}
			}else if(ln=='en'){
				if(confirm("Really?")){
					operating_static = 1;
					show_store();
				}
			}else{
				if(confirm("確定取消?")){
					operating_static = 1;
					show_store();
				}
			}
		}
		
		//點擊修改
		update.onclick = function(){
			if(ln=='cn'){
				if(confirm("确定修改?")){
					operating_static = 1;
					var obj = {
						instruction: 'UpdateStore',
						AccOrSto:data['Information'][0],
						name:up_del_input[0].value,
						tel:up_del_input[1].value,
						address:up_del_input[2].value,
					};
					
					var SetJson = JSON.stringify(obj);
					
					ajax('post','indexphp.php',SetJson,function fun(value){
						var data = JSON.parse(value);
						if(data['data'] == '1' && data['select'] == '1'){
							alert(data['name'] + '修改成功');
							show_store();
						}else{
							alert(data['name'] + '修改失败');
							show_store();
						}
					});
				}
			}else if(ln=='en'){
				if(confirm("Really Save?")){
					operating_static = 1;
					var obj = {
						instruction: 'UpdateStore',
						AccOrSto:data['Information'][0],
						name:up_del_input[0].value,
						tel:up_del_input[1].value,
						address:up_del_input[2].value,
					};
					
					var SetJson = JSON.stringify(obj);
					
					ajax('post','indexphp.php',SetJson,function fun(value){
						var data = JSON.parse(value);
						if(data['data'] == '1' && data['select'] == '1'){
							alert(data['name'] + 'success');
							show_store();
						}else{
							alert(data['name'] + 'failure');
							show_store();
						}
					});
				}
			}else{
				if(confirm("確定修改?")){
					operating_static = 1;
					var obj = {
						instruction: 'UpdateStore',
						AccOrSto:data['Information'][0],
						name:up_del_input[0].value,
						tel:up_del_input[1].value,
						address:up_del_input[2].value,
					};
					
					var SetJson = JSON.stringify(obj);
					
					ajax('post','indexphp.php',SetJson,function fun(value){
						var data = JSON.parse(value);
						if(data['data'] == '1' && data['select'] == '1'){
							alert(data['name'] + '修改成功');
							show_store();
						}else{
							alert(data['name'] + '修改失敗');
							show_store();
						}
					});
				}
			}
		}
	}
	
	});
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
<?php echo "<script type='text/javascript'>show_store();</script>";?>
</body>
</html>
