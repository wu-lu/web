<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>新增品項</title>
<script src="fun.js"></script>
</head>
<body>
<?php
	session_start();
?>
<script type="text/javascript">
	function insert_commodity(){
	
	var ln = '<?php echo $_GET['ln']?>';
	
	var right_title = document.getElementById("right_title");
	var right_content = document.getElementById('right_content');
	var right_page = document.getElementById('right_page');
	var title = '<p><span style="font-size:15px;"><font color="red">'+'新增品項'+'</font></span></p>'+
		'新增數量：<input type ="text" required="required">&nbsp;'+
		'<input type ="submit" value = "送出">'+
				'</p>';
	var title_cn='<p><span style="font-size:15px;"><font color="red">'+'新增品项'+'</font></span></p>'+
		'新增数量：<input type ="text" required="required">&nbsp;'+
		'<input type ="submit" value = "送出">'+
				'</p>';
	var title_en='<p><span style="font-size:15px;"><font color="red">'+'Add product'+'</font></span></p>'+
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
						'</tr>';
			content_cn += '<tr>'+
							'<td><input type = "text" style ="width:130px;"></td>'+
							'<td><input type = "text" style ="width:120px;"></td>'+
						'</tr>';
			content_en += '<tr>'+
							'<td><input type = "text" style ="width:130px;"></td>'+
							'<td><input type = "text" style ="width:120px;"></td>'+
						'</tr>';
			i++;
		}
		//以表格方式產生整個頁面
		content = '<table class = "mar">'+
					'<tr style = "text-align:center;">'+
						'<td>品項名稱</td>'+
						'<td>品項價格</td>'+
					'</tr>'+
				  content+
				  '<tr><td colspan="2" style = "text-align:center;" ><input type ="button" value="儲存" id = "output">&nbsp;'+
				  '<input type ="button" value="取消" id = "cancel"></td></tr>'+
				  '</table>';
		content_cn = '<table class = "mar">'+
					'<tr style = "text-align:center;">'+
						'<td>品项名称</td>'+
						'<td>品项价格</td>'+
					'</tr>'+
				  content_cn+
				  '<tr><td colspan="2" style = "text-align:center;" ><input type ="button" value="储存" id = "output">&nbsp;'+
				  '<input type ="button" value="取消" id = "cancel"></td></tr>'+
				  '</table>';
		content_en = '<table class = "mar">'+
					'<tr style = "text-align:center;">'+
						'<td>Product name</td>'+
						'<td>Product price</td>'+
					'</tr>'+
				  content_en+
				  '<tr><td colspan="2" style = "text-align:center;" ><input type ="button" value="Save" id = "output">&nbsp;'+
				  '<input type ="button" value="Cancel" id = "cancel"></td></tr>'+
				  '</table>';
		if(ln=='cn'){
			right_content.innerHTML = content_cn;
		}else if(ln=='en'){
			right_content.innerHTML = content_en;
		}else{
			right_content.innerHTML = content;
		}
		var output = document.getElementById('output');    			//儲存按鈕
		var cancel = document.getElementById('cancel');	   			//取消按鈕
		
		
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
				var name = Array();
				var unit= Array();
				
				var error = 0;			//檢查是否有錯誤存在
				
				
				for(var i=0;i<input_table.length-2;i++){
					/*判斷如果有空值就跳出迴圈
					  沒有則繼續把資料存入陣列*/
					if(input_table[i].value != ''){
						if(i%2 == 0) name.push(input_table[i].value);	 //儲存店家名稱欄
						if(i%2 == 1) unit.push(input_table[i].value);	 //儲存店家電話欄
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
								instruction:'InsertCommodity',
								store_number:'<?php echo @$_GET['store_number']?>',
								name:name,
								unit:unit
							  };
							
					var SetJson	 = JSON.stringify(obj);	  	//把物件轉成可以傳送的JSON
					
					//用非同步方式傳送資料到後端
					ajax('post','indexphp.php',SetJson,
						 function fun(value){
							var total = '';
							var data = JSON.parse(value);
							
								for(var i=0;i<data['EchoDB'].length;i++){
									if(data['EchoDB'][i] == '1'){
										total += '新增成功' + '<br>';
									}else{
										total += '新增失败' + '<br>';
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
				var name = Array();
				var unit= Array();
				
				var error = 0;			//檢查是否有錯誤存在
				
				
				for(var i=0;i<input_table.length-2;i++){
					/*判斷如果有空值就跳出迴圈
					  沒有則繼續把資料存入陣列*/
					if(input_table[i].value != ''){
						if(i%2 == 0) name.push(input_table[i].value);	 //儲存店家名稱欄
						if(i%2 == 1) unit.push(input_table[i].value);	 //儲存店家電話欄
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
								instruction:'InsertCommodity',
								store_number:'<?php echo @$_GET['store_number']?>',
								name:name,
								unit:unit
							  };
							
					var SetJson	 = JSON.stringify(obj);	  	//把物件轉成可以傳送的JSON
					
					//用非同步方式傳送資料到後端
					ajax('post','indexphp.php',SetJson,
						 function fun(value){
							var total = '';
							var data = JSON.parse(value);
							
								for(var i=0;i<data['EchoDB'].length;i++){
									if(data['EchoDB'][i] == '1'){
										total += 'success' + '<br>';
									}else{
										total += 'failed' + '<br>';
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
				var name = Array();
				var unit= Array();
				
				var error = 0;			//檢查是否有錯誤存在
				
				
				for(var i=0;i<input_table.length-2;i++){
					/*判斷如果有空值就跳出迴圈
					  沒有則繼續把資料存入陣列*/
					if(input_table[i].value != ''){
						if(i%2 == 0) name.push(input_table[i].value);	 //儲存店家名稱欄
						if(i%2 == 1) unit.push(input_table[i].value);	 //儲存店家電話欄
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
								instruction:'InsertCommodity',
								store_number:'<?php echo @$_GET['store_number']?>',
								name:name,
								unit:unit
							  };
							
					var SetJson	 = JSON.stringify(obj);	  	//把物件轉成可以傳送的JSON
					
					//用非同步方式傳送資料到後端
					ajax('post','indexphp.php',SetJson,
						 function fun(value){
							
							var total = '';
							var data = JSON.parse(value);
							
								for(var i=0;i<data['EchoDB'].length;i++){
									if(data['EchoDB'][i] == '1'){
										total += '新增成功' + '<br>';
									}else{
										total += '新增失败' + '<br>';
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
<?php echo "<script type='text/javascript'>insert_commodity();</script>";?>
</body>
</html>
