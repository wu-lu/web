<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>新增承租者</title>
<script src="fun.js"></script>
</head>

<body>
<?php
	session_start();
?>
<script type="text/javascript">
	function insert_account(){
	
	var ln ='<?php echo $_GET['ln']?>';
	
	var right_title = document.getElementById("right_title");
	var right_content = document.getElementById('right_content');
	var right_page = document.getElementById('right_page');
	var content ='';
	var content_cn ='';
	var content_en ='';
	
		
		//以表格方式產生整個頁面
		content = 
				'<p>帳號：<input type = "text"  pattern = "[A-Za-z0-9]{3,}" style = "width:300px;"></p>'+
				'<p>姓名：<input type = "text" style = "width:300px;"></p>'+
				'<p>密碼：<input type = "text" style = "width:300px;"></p>'+
				'<p>手機：<input type = "text" style = "width:300px;"></p>'+
				'<p>地址：<input type = "text" style = "width:300px;"></p>'+
				'<p>信箱：<input type = "text" style = "width:300px;"></p>'+
				'<p><input type ="button" value="儲存" id = "output">&nbsp;'+
				'<input type ="button" value="取消" id = "cancel"></p>';
		content_cn = 
				'<p>帐号：<input type = "text"  pattern = "[A-Za-z0-9]{3,}" style = "width:300px;"></p>'+
				'<p>姓名：<input type = "text" style = "width:300px;"></p>'+
				'<p>密码：<input type = "text" style = "width:300px;"></p>'+
				'<p>手机：<input type = "text" style = "width:300px;"></p>'+
				'<p>地址：<input type = "text" style = "width:300px;"></p>'+
				'<p>信箱：<input type = "text" style = "width:300px;"></p>'+
				'<p><input type ="button" value="储存" id = "output">&nbsp;'+
				'<input type ="button" value="取消" id = "cancel"></p>';
		content_en = 
				'<p>Account：<input type = "text"  pattern = "[A-Za-z0-9]{3,}" style = "width:300px;"></p>'+
				'<p>Name：<input type = "text" style = "width:300px;"></p>'+
				'<p>Password：<input type = "text" style = "width:300px;"></p>'+
				'<p>Phone：<input type = "text" style = "width:300px;"></p>'+
				'<p>Address：<input type = "text" style = "width:300px;"></p>'+
				'<p>E-mail：<input type = "text" style = "width:300px;"></p>'+
				'<p><input type ="button" value="Save" id = "output">&nbsp;'+
				'<input type ="button" value="Cancel" id = "cancel"></p>';
		if(ln=='cn'){
			right_content.innerHTML = content_cn;
		}else if(ln=='en'){
			right_content.innerHTML = content_en;
		}else{
			right_content.innerHTML = content;
		}
		right_title.innerHTML = '<p><span style="font-size:15px;"><font color="red">新增承租者</font></span></p>';
		var output = document.getElementById('output');    				//儲存按鈕
		var cancel = document.getElementById('cancel');	   				//取消按鈕
		var input_table = right_content.getElementsByTagName('input');	//所有的輸入框資料
		var span = document.getElementsByTagName('span');
		
		if(ln=='cn'){
			span[0].innerHTML='<font color="red">新增承租者</font>';
		}else if(ln=='en'){
			span[0].innerHTML='<font color="red">Add tenant</font>';
		}
		//點擊取消事件
		cancel.onclick = function(){
			if(ln=='cn'){
				if(confirm("确定取消?")){
					operating_static = 1;
					insert_account();
				}
			}else if(ln=='en'){
				if(confirm("Really?")){
					operating_static = 1;
					insert_account();
				}
			}else{
				if(confirm("確定取消?")){
					operating_static = 1;
					insert_account();
				}
			}
		}
		
		//點擊儲存事件
		output.onclick = function(){
			if(ln=='cn'){
				if(confirm("确定送出?")){
					var error = 0;			//檢查是否有錯誤存在
					operating_static = 1;
					for(var i=0;i<input_table.length-2;i++){
						if(input_table[i].value == ''){
							error = 1;
							break;
						}
					}
					/*判斷如果有空值就跳出警告框
					沒有則繼續把資料傳到後端*/
					if(error == 0){
						//轉成物件
						var obj = {
									instruction:'InsertAccount',
									account:input_table[0].value,
									name:input_table[1].value,
									pw:input_table[2].value,
									phone:input_table[3].value,
									address:input_table[4].value,
									email:input_table[5].value,
									store_number:'<?php echo $_GET['store_number']?>',
									machine_number:'<?php echo $_GET['machine_number']?>'
								};
							
						var SetJson	 = JSON.stringify(obj);	  	//把物件轉成可以傳送的JSON
						
						//用非同步方式傳送資料到後端
						ajax('post','indexphp.php',SetJson,
							function fun(value){
								var total = '';
								var data = JSON.parse(value)
									if(data['message'] == '1'){
										total += data['name'] + '新增成功' + '<br>';
									}else{
										total += data['name'] + '新增失败' + '<br>';
									}
									//alert('total:'+total);
									right_content.innerHTML = total;
							});
					}else if( error == 1){
						alert('不能有空值存在!');
					}
				}
			}else if(ln=='en'){
				if(confirm("Really Save?")){
					var error = 0;			//檢查是否有錯誤存在
					operating_static = 1;
					for(var i=0;i<input_table.length-2;i++){
						if(input_table[i].value == ''){
							error = 1;
							break;
						}
					}
					/*判斷如果有空值就跳出警告框
					沒有則繼續把資料傳到後端*/
					if(error == 0){
						//轉成物件
						var obj = {
									instruction:'InsertAccount',
									account:input_table[0].value,
									name:input_table[1].value,
									pw:input_table[2].value,
									phone:input_table[3].value,
									address:input_table[4].value,
									email:input_table[5].value,
									store_number:'<?php echo $_GET['store_number']?>',
									machine_number:'<?php echo $_GET['machine_number']?>'
								};
							
						var SetJson	 = JSON.stringify(obj);	  	//把物件轉成可以傳送的JSON
						
						//用非同步方式傳送資料到後端
						ajax('post','indexphp.php',SetJson,
							function fun(value){
								var total = '';
								var data = JSON.parse(value)
									if(data['message'] == '1'){
										total += data['name'] + 'Add success' + '<br>';
									}else{
										total += data['name'] + 'Add failure' + '<br>';
									}
									//alert('total:'+total);
									right_content.innerHTML = total;
							});
					}else if(error == 1){
						alert('dont null values exist!');
					}
				}
			}else{
				if(confirm("確定送出?")){
					var error = 0;			//檢查是否有錯誤存在
					operating_static = 1;
					for(var i=0;i<input_table.length-2;i++){
						if(input_table[i].value == ''){
							error = 1;
							break;
						}
					}
					/*判斷如果有空值就跳出警告框
					沒有則繼續把資料傳到後端*/
					if(error == 0){
						//轉成物件
						var obj = {
									instruction:'InsertAccount',
									account:input_table[0].value,
									name:input_table[1].value,
									pw:input_table[2].value,
									phone:input_table[3].value,
									address:input_table[4].value,
									email:input_table[5].value,
									store_number:'<?php echo $_GET['store_number']?>',
									machine_number:'<?php echo $_GET['machine_number']?>'
								};
							
						var SetJson	 = JSON.stringify(obj);	  	//把物件轉成可以傳送的JSON
						
						//用非同步方式傳送資料到後端
						ajax('post','indexphp.php',SetJson,
							function fun(value){
								var total = '';
								var data = JSON.parse(value)
									if(data['message'] == '1'){
										total += data['name'] + '新增成功' + '<br>';
									}else{
										total += data['name'] + '新增失敗' + '<br>';
									}
										//alert('total:'+total);
									right_content.innerHTML = total;
							});
					}else if( error == 1){
						alert('不能有空值存在!');
					}
				}
			}
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
<?php echo "<script type='text/javascript'>insert_account();</script>";?>
</body>
</html>
