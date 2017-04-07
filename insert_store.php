<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>新增商店</title>
<script src="fun.js"></script>
</head>
<body>
<?php
	session_start();
?>
<script type="text/javascript">
	function insert_store(){
	
	var ln = '<?php echo $_GET['ln']?>';
	
	var right_title = document.getElementById("right_title");
	var right_content = document.getElementById('right_content');
	var right_page = document.getElementById('right_page');
	var title = '<p><span style="font-size:15px;"><font color="red">'+'新增店家'+'</font></span></p>'+
	'<p class = "mar">類別：<select id ="status" style ="width:100px;">'+
				'<option value ="direct">自營</option>'+
				'<option value ="rent">承租</option></select>&nbsp;'+
		'新增數量：<input type ="text" required="required">&nbsp;'+
		'<input type ="submit" value = "送出">'+
				'</p>';
	var title_cn='<p><span style="font-size:15px;"><font color="red">'+'新增店家'+'</font></span></p>'+
	'<p class = "mar">类别：<select id ="status" style ="width:100px;">'+
				'<option value ="direct">自营</option>'+
				'<option value ="rent">承租</option></select>&nbsp;'+
		'新增数量：<input type ="text" required="required">&nbsp;'+
		'<input type ="submit" value = "送出">'+
				'</p>';
	var title_en='<p><span style="font-size:15px;"><font color="red">'+'Add store'+'</font></span></p>'+
	'<p class = "mar">Type：<select id ="status" style ="width:100px;">'+
				'<option value ="direct">Direct</option>'+
				'<option value ="rent">Rent</option></select>&nbsp;'+
		'Quantity：<input type ="text" required="required">&nbsp;'+
		'<input type ="submit" value = "Send">'+
				'</p>';
	if(ln=='cn'){
		right_title.innerHTML = title_cn;
	}else if(ln=='en'){
		right_title.innerHTML = title_en;
	}else{
		right_title.innerHTML = title;
	}
	var content = '';
	var content_cn ='';
	var content_en ='';
	right_content.innerHTML = '';
	right_page.innerHTML = '';
	
	var input = document.getElementsByTagName('input');		//按鈕以及輸入框
	
	input[1].onclick = show;

	function show(){
	
		content = '';
		content_cn = '';
		content_en = '';
		operating_static = 0;
		var i = 0;
		while(i<input[0].value){
			 content += '<tr>'+
							'<td><input type = "text" style ="width:130px;"></td>'+
							'<td><input type = "text" style ="width:120px;"></td>'+
							'<td><input type = "text" style ="width:440px;"></td>'+
						'</tr>';
			content_cn += '<tr>'+
							'<td><input type = "text" style ="width:130px;"></td>'+
							'<td><input type = "text" style ="width:120px;"></td>'+
							'<td><input type = "text" style ="width:440px;"></td>'+
						'</tr>';
			content_en += '<tr>'+
							'<td><input type = "text" style ="width:130px;"></td>'+
							'<td><input type = "text" style ="width:120px;"></td>'+
							'<td><input type = "text" style ="width:440px;"></td>'+
						'</tr>';
				i++;
		}
		//以表格方式產生整個頁面
		content = '<table class = "mar">'+
					'<tr style = "text-align:center;">'+
						'<td>店家名稱</td>'+
						'<td>店家電話</td>'+
						'<td>店家地址</td></tr>'+
				  content+
				  '<tr><td colspan="3" style = "text-align:center;" ><input type ="button" value="儲存" id = "output">&nbsp;'+
				  '<input type ="button" value="取消" id = "cancel"></td></tr>'+
				  '</table>';
		content_cn = '<table class = "mar">'+
					'<tr style = "text-align:center;">'+
						'<td>店家名称</td>'+
						'<td>店家电话</td>'+
						'<td>店家地址</td></tr>'+
				  content_cn+
				  '<tr><td colspan="3" style = "text-align:center;" ><input type ="button" value="储存" id = "output">&nbsp;'+
				  '<input type ="button" value="取消" id = "cancel"></td></tr>'+
				  '</table>';
		content_en = '<table class = "mar">'+
					'<tr style = "text-align:center;">'+
						'<td>Store name</td>'+
						'<td>Store phone number</td>'+
						'<td>Store address</td></tr>'+
				  content_en+
				  '<tr><td colspan="3" style = "text-align:center;" ><input type ="button" value="Save" id = "output">&nbsp;'+
				  '<input type ="button" value="Cancel" id = "cancel"></td></tr>'+
				  '</table>';
		if(ln=='cn'){
			right_content.innerHTML = content_cn;
		}else if(ln=='en'){
			right_content.innerHTML = content_en;
		}else{
			right_content.innerHTML = content;
		}
		var output = document.getElementById('output');		//儲存按鈕
		var cancel = document.getElementById('cancel');		//取消按鈕
		var status = document.getElementById('status');
		
		
		//點擊取消事件
		cancel.onclick = function(){
			if(ln=='cn'){
				if(confirm("确定取消?")){
					operating_static = 1;
					fomat();
				}
			}else if(ln=='en'){
				if(confirm("Really?")){
					operating_static = 1;
					fomat();
				}
			}else{
				if(confirm("確定取消?")){
					operating_static = 1;
					fomat();
				}
			}
		}
		
		//點擊儲存事件
		output.onclick = function(){
			if(ln=='cn'){
			if(confirm("确定送出?")){
				operating_static = 1;
				
				var span = document.getElementsByTagName('span');
				//var acc=span.getAttribute('title');
				var input_table = right_content.getElementsByTagName('input');
				var name_arr = Array();
				var tel_arr= Array();
				var address_arr = Array();
				
				var error = 0;			//檢查是否有錯誤存在
				
				
				for(var i=0;i<input_table.length-2;i++){
					/*判斷如果有空值就跳出迴圈
					  沒有則繼續把資料存入陣列*/
					if(input_table[i].value != ''){
						if(i%3 == 0) name_arr.push(input_table[i].value);		//儲存店家名稱欄
						if(i%3 == 1) tel_arr.push(input_table[i].value);	 	//儲存店家電話欄
						if(i%3 == 2) address_arr.push(input_table[i].value);	//儲存店家地址欄
					}else{
						error = 1;
						break;
					}
				}
				/*判斷如果有空值就跳出警告框
				  沒有則繼續把資料傳到後端*/
				if(error == 0){
					//轉成物件
					var obj = {
								instruction:'InsertStore',
								AccOrSto:'<?php echo @$_SESSION['account']?>',
								store_class:status.value,
								name:name_arr,
								tel:tel_arr,
								address:address_arr
							  };
							  
					var SetJson	 = JSON.stringify(obj);	  	//把物件轉成可以傳送的JSON
					
					//用非同步方式傳送資料到後端
					ajax('post','indexphp.php',SetJson,
						 function fun(value){
							var total = '';
							var data = JSON.parse(value);
							
								for(var i=0;i<data['name'].length;i++){
									if(data['data'][i] == '1' && data['select'][i] == '1'){
										total += data['name'][i] + '新增成功' + '<br>';
									}else{
										total += data['name'][i] + '新增失败' + '<br>';
									}
									//alert('total:'+total);
								}
								right_content.innerHTML = total;
						});
						
					fomat();
				}else{
					alert('不能有空值存在!');
				}
			}
			}else if(ln=='en'){
				if(confirm("Really Send?")){
				operating_static = 1;
				
				var span = document.getElementsByTagName('span');
				//var acc=span.getAttribute('title');
				var input_table = right_content.getElementsByTagName('input');
				var name_arr = Array();
				var tel_arr= Array();
				var address_arr = Array();
				
				var error = 0;			//檢查是否有錯誤存在
				
				
				for(var i=0;i<input_table.length-2;i++){
					/*判斷如果有空值就跳出迴圈
					  沒有則繼續把資料存入陣列*/
					if(input_table[i].value != ''){
						if(i%3 == 0) name_arr.push(input_table[i].value);		//儲存店家名稱欄
						if(i%3 == 1) tel_arr.push(input_table[i].value);		//儲存店家電話欄
						if(i%3 == 2) address_arr.push(input_table[i].value);	//儲存店家地址欄
					}else{
						error = 1;
						break;
					}
				}
				/*判斷如果有空值就跳出警告框
				  沒有則繼續把資料傳到後端*/
				if(error == 0){
					//轉成物件
					var obj = {
								instruction:'InsertStore',
								AccOrSto:'<?php echo @$_SESSION['account']?>',
								store_class:status.value,
								name:name_arr,
								tel:tel_arr,
								address:address_arr
							  };
							
					var SetJson	 = JSON.stringify(obj);	  	//把物件轉成可以傳送的JSON
					
					//用非同步方式傳送資料到後端
					ajax('post','indexphp.php',SetJson,
						 function fun(value){
							var total = '';
							var data = JSON.parse(value);
							
								for(var i=0;i<data['name'].length;i++){
									if(data['data'][i] == '1' && data['select'][i] == '1'){
										total += data['name'][i] + '新增成功' + '<br>';
									}else{
										total += data['name'][i] + '新增失敗' + '<br>';
									}
									//alert('total:'+total);
								}
								right_content.innerHTML = total;
						 });
						
					fomat();
				}else{
					alert('dont null values exist!');
				}
			}
			}else{
				if(confirm("確定送出?")){
				operating_static = 1;
				
				var span = document.getElementsByTagName('span');
				//var acc=span.getAttribute('title');
				var input_table = right_content.getElementsByTagName('input');
				var name_arr = Array();
				var tel_arr= Array();
				var address_arr = Array();
				
				var error = 0;			//檢查是否有錯誤存在
				
				
				for(var i=0;i<input_table.length-2;i++){
					/*判斷如果有空值就跳出迴圈
					  沒有則繼續把資料存入陣列*/
					if(input_table[i].value != ''){
						if(i%3 == 0) name_arr.push(input_table[i].value);		//儲存店家名稱欄
						if(i%3 == 1) tel_arr.push(input_table[i].value);		//儲存店家電話欄
						if(i%3 == 2) address_arr.push(input_table[i].value);	//儲存店家地址欄
					}else{
						error = 1;
						break;
					}
				}
				
				if(<?php echo @$_SESSION['competence']?>=='3'){
					var account ='<?php echo @$_SESSION['father']?>';
				}else{
					var account ='<?php echo @$_SESSION['account']?>';
				}
				/*判斷如果有空值就跳出警告框
				  沒有則繼續把資料傳到後端*/
				if(error == 0){
					//轉成物件
					var obj = {
								instruction:'InsertStore',
								AccOrSto:account,
								store_class:status.value,
								name:name_arr,
								tel:tel_arr,
								address:address_arr
							  };
							
					var SetJson	 = JSON.stringify(obj);	  	//把物件轉成可以傳送的JSON
					
					//用非同步方式傳送資料到後端
					ajax('post','indexphp.php',SetJson,
						 function fun(value){
							var total = '';
							var data = JSON.parse(value);
							
								for(var i=0;i<data['name'].length;i++){
									if(data['data'][i] == '1' && data['select'][i] == '1'){
										total += data['name'][i] + '新增成功' + '<br>';
									}else{
										total += data['name'][i] + '新增失败' + '<br>';
									}
									//alert('total:'+total);
								}
								right_content.innerHTML = total;
						});
						
					fomat();
				}else{
					alert('不能有空值存在!');
				}
			}
			}
		}
	
	}
	//初始化
	function fomat(){
		if(ln=='cn'){
			right_title.innerHTML = title_cn;
		}else if(ln=='en'){
			right_title.innerHTML = title_en;
		}else{
			right_title.innerHTML = title;
		}
		right_content.innerHTML = '';
		content = '';
		right_page.innerHTML = '';
		input[1].onclick = show;
	}
}
	
</script>


<div id = "right_title">
	<p class = "mar"></p>
</div>
<div id = "right_content" style="overflow:auto">
	
</div>
<div id = "right_page">
	
</div>
<?php echo "<script type='text/javascript'>insert_store();</script>";?>
</body>
</html>
