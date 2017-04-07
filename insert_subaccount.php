<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>新增子帳號</title>
<script src="fun.js"></script>
<style type="text/css">
.success_sp{
	display:block;
	width:20px;
	height:20px;
	background-image:url('../image/success.png');
	background-repeat:no-repeat;
}

.failure_sp{
	display:block;
	width:20px;
	height:20px;
	background-image:url('../image/failure.png');
	background-repeat:no-repeat;
}

.sp{
	display:block;
	width:20px;
	height:20px;
}
</style>
</head>
<body>
<?php
	session_start();
?>
<script type="text/javascript">
	function insert_subaccount(){
	
	var ln = '<?php echo $_GET['ln']?>';
	
	var right_title = document.getElementById("right_title");
	var right_content = document.getElementById('right_content');
	var right_page = document.getElementById('right_page');
	var title = '<p><span style="font-size:15px;"><font color="red">'+'新增子帳號'+'</font></span></p>'+
		'新增數量：<input type ="text" required="required">&nbsp;'+
		'<input type ="submit" value = "送出">'+
				'</p>';
	var title_cn='<p><span style="font-size:15px;"><font color="red">'+'新增子帐号'+'</font></span></p>'+
		'新增数量：<input type ="text" required="required">&nbsp;'+
		'<input type ="submit" value = "送出">'+
				'</p>';
	var title_en='<p><span style="font-size:15px;"><font color="red">'+'Add subaccount'+'</font></span></p>'+
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
			 content += '<tr><td><span class = "sp"></span></td>'+
							'<td><input type = "text" style ="width:108px;" pattern = "[A-Za-z0-9]{3,}"></td>'+
							'<td><input type = "text" style ="width:80px;"></td>'+
							'<td><input type = "text" style ="width:113px;"></td>'+
							'<td><input type = "text" style ="width:100px;"></td>'+
							'<td><input type = "text" style ="width:160px;"></td>'+
							'<td><input type = "text" style ="width:220px;"></td></tr>';
			content_cn += '<tr><td><span class = "sp"></span></td>'+
							'<td><input type = "text" style ="width:108px;" pattern = "[A-Za-z0-9]{3,}"></td>'+
							'<td><input type = "text" style ="width:80px;"></td>'+
							'<td><input type = "text" style ="width:113px;"></td>'+
							'<td><input type = "text" style ="width:100px;"></td>'+
							'<td><input type = "text" style ="width:160px;"></td>'+
							'<td><input type = "text" style ="width:220px;"></td></tr>';
			content_en += '<tr><td><span class = "sp"></span></td>'+
							'<td><input type = "text" style ="width:108px;" pattern = "[A-Za-z0-9]{3,}"></td>'+
							'<td><input type = "text" style ="width:80px;"></td>'+
							'<td><input type = "text" style ="width:113px;"></td>'+
							'<td><input type = "text" style ="width:100px;"></td>'+
							'<td><input type = "text" style ="width:160px;"></td>'+
							'<td><input type = "text" style ="width:220px;"></td></tr>';
				i++;
		}
		//以表格方式產生整個頁面
		content = '<table class = "mar" align="left">'+
					'<tr style = "text-align:center;"><td></td>'+
					  '<td>帳號</td>'+
					  '<td>姓名</td>'+
					  '<td>密碼</td>'+
					  '<td>手機</td>'+
					  '<td>信箱</td>'+
					  '<td>地址</td></tr>'+
				  content+
				  '<tr><td></td><td colspan="6" style = "text-align:center;"><input type ="button" value="儲存" id = "output">&nbsp;'+
				  '<input type ="button" value="取消" id = "cancel"></td></tr>'+
				  '</table>';
		content_cn = '<table class = "mar" align="left">'+
					'<tr style = "text-align:center;"><td></td>'+
					  '<td>帳號</td>'+
					  '<td>姓名</td>'+
					  '<td>密碼</td>'+
					  '<td>手機</td>'+
					  '<td>信箱</td>'+
					  '<td>地址</td></tr>'+
				  content_cn+
				  '<tr><td></td><td colspan="6" style = "text-align:center;"><input type ="button" value="儲存" id = "output">&nbsp;'+
				  '<input type ="button" value="取消" id = "cancel"></td></tr>'+
				  '</table>';
		content_en = '<table class = "mar" align="left">'+
					'<tr style = "text-align:center;"><td></td>'+
					  '<td>Acoount</td>'+
					  '<td>Name</td>'+
					  '<td>Password</td>'+
					  '<td>Phone</td>'+
					  '<td>E-mail</td>'+
					  '<td>Address</td></tr>'+
				  content_en+
				  '<tr><td></td><td colspan="6" style = "text-align:center;"><input type ="button" value="Send" id = "output">&nbsp;'+
				  '<input type ="button" value="Cencel" id = "cancel"></td></tr>'+
				  '</table>';
		if(ln=='cn'){
			right_content.innerHTML = content_cn;
		}else if(ln=='en'){
			right_content.innerHTML = content_en;
		}else{
			right_content.innerHTML = content;
		}
		var output = document.getElementById('output');					//儲存按鈕
		var cancel = document.getElementById('cancel');					//取消按鈕
		var span = right_content.getElementsByTagName('span');			//顯示帳號檢查結果
		var input_table = right_content.getElementsByTagName('input');	//所有的輸入框資料
		
		//檢查帳號是否使用過
		check_number(input_table,span,6,1,null);
		
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
				
				var account_arr = Array();
				var name_arr = Array();
				var password__arr = Array();
				var phone_arr = Array();
				var email_arr = Array();
				var address_arr = Array();
				var error = 0;			//檢查是否有錯誤存在
				
				
				for(var i=0;i<input_table.length-2;i++){
					/*判斷如果有空值就跳出迴圈
					  沒有則繼續把資料存入陣列*/
					if(input_table[i].value != ''){
						if(i%6 == 0) account_arr.push(input_table[i].value);	 //儲存帳號欄
						if(i%6 == 1) name_arr.push(input_table[i].value);		 //儲存名稱欄
						if(i%6 == 2) password__arr.push(input_table[i].value);   //儲存密碼欄
						if(i%6 == 3) phone_arr.push(input_table[i].value);		 //儲存手機欄
						if(i%6 == 4) email_arr.push(input_table[i].value);		 //儲存信箱欄
						if(i%6 == 5) address_arr.push(input_table[i].value);	 //儲存地址欄
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
								instruction:'InsertSubAccount',
								subaccount:account_arr,
								subname:name_arr,
								subpassword:password__arr,
								subphone:phone_arr,
								subaddress:address_arr,
								subemail:email_arr
							  };
							
					var SetJson	 = JSON.stringify(obj);	  	//把物件轉成可以傳送的JSON
					
					//用非同步方式傳送資料到後端
					ajax('post','indexphp.php',SetJson,
						 function fun(value){
							var total = '';
							var data = JSON.parse(value);
							
								for(var i=0;i<data['EchoDB'].length;i++){
									if(data['EchoDB'][i] == '1' ){
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
				var account_arr = Array();
				var name_arr = Array();
				var password__arr = Array();
				var phone_arr = Array();
				var email_arr = Array();
				var address_arr = Array();
				
				var error = 0;			//檢查是否有錯誤存在
				
				
				for(var i=0;i<input_table.length-2;i++){
					/*判斷如果有空值就跳出迴圈
					  沒有則繼續把資料存入陣列*/
					if(input_table[i].value != ''){
						if(i%6 == 0) account_arr.push(input_table[i].value);	 //儲存帳號欄
						if(i%6 == 1) name_arr.push(input_table[i].value);		 //儲存名稱欄
						if(i%6 == 2) password__arr.push(input_table[i].value);   //儲存密碼欄
						if(i%6 == 3) phone_arr.push(input_table[i].value);		 //儲存手機欄
						if(i%6 == 4) email_arr.push(input_table[i].value);		 //儲存信箱欄
						if(i%6 == 5) address_arr.push(input_table[i].value);	 //儲存地址欄
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
								instruction:'InsertSubAccount',
								subaccount:account_arr,
								subname:name_arr,
								subpassword:password__arr,
								subphone:phone_arr,
								subaddress:address_arr,
								subemail:email_arr
							  };
							
					var SetJson	 = JSON.stringify(obj);	  	//把物件轉成可以傳送的JSON
					
					//用非同步方式傳送資料到後端
					ajax('post','indexphp.php',SetJson,
						 function fun(value){
							var total = '';
							var data = JSON.parse(value);
							
								for(var i=0;i<data['EchoDB'].length;i++){
									if(data['EchoDB'][i] == '1' ){
										total += '新增成功' + '<br>';
									}else{
										total += '新增失敗' + '<br>';
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
				var account_arr = Array();
				var name_arr = Array();
				var password__arr = Array();
				var phone_arr = Array();
				var email_arr = Array();
				var address_arr = Array();
				
				var error = 0;			//檢查是否有錯誤存在
				
				
				for(var i=0;i<input_table.length-2;i++){
					/*判斷如果有空值就跳出迴圈
					  沒有則繼續把資料存入陣列*/
					if(input_table[i].value != ''){
						if(i%6 == 0) account_arr.push(input_table[i].value);	 //儲存帳號欄
						if(i%6 == 1) name_arr.push(input_table[i].value);		 //儲存名稱欄
						if(i%6 == 2) password__arr.push(input_table[i].value);   //儲存密碼欄
						if(i%6 == 3) phone_arr.push(input_table[i].value);		 //儲存手機欄
						if(i%6 == 4) email_arr.push(input_table[i].value);		 //儲存信箱欄
						if(i%6 == 5) address_arr.push(input_table[i].value);	 //儲存地址欄
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
								instruction:'InsertSubAccount',
								subaccount:account_arr,
								subname:name_arr,
								subpassword:password__arr,
								subphone:phone_arr,
								subaddress:address_arr,
								subemail:email_arr
							  };
							
					var SetJson	 = JSON.stringify(obj);	  	//把物件轉成可以傳送的JSON
					
					//用非同步方式傳送資料到後端
					ajax('post','indexphp.php',SetJson,
						 function fun(value){
							var total = '';
							var data = JSON.parse(value);
							
								for(var i=0;i<data['EchoDB'].length;i++){
									if(data['EchoDB'][i] == '1' ){
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

//查詢編號或帳號
function check_number(input_table,span,number,php_table,check_type,table_sel){
	
	var j = 0;			   //確認圖示的索引值
	var condition_1 = '';  //條件1
	var condition_2 = '';  //條件2
	/*如果check_type型態是數字表示確認的是出租狀況
	  否則就是字串型態表示確認新增的機台碼確認*/
	
	if(typeof(check_type) != 'number'){
		for(var i=0;i<input_table.length-2;i++){
			/*要確認的是第2個位置的欄位值*/
			if(i%number == 0){
				span[j].index = j;
				input_table[i].index = i;
				j++;
				
				input_table[i].onblur = check;
			}
		}
	}else{
		check();
	}
	
	function check(){
		var num = this.index/number;
		var add_value=Array();
	 	
		if(typeof(check_type) != 'number'){
			
			if(table_sel !=undefined){
			
			for(var i=0;i<table_sel.length;i++){
				if(table_sel[i].value == '2'){add_value[i]='CM_';}
				else{add_value[i]='';}
				//alert(table_sel[i].value);
				//alert(add_value[i]);
			}
			
			condition_1 = add_value[this.index/2]+input_table[this.index].value;
			}else{
				condition_1 = input_table[this.index].value;
			}
			
		}else{
			this.index = check_type;
			condition_1 = input_table[this.index-1].value;
			condition_2 = input_table[this.index].value;
			num = (this.index-1)/number;
		}
		
		var url_value = 'instruction=CheckNumber&table='+php_table+'&condition_1='+condition_1+'&condition_2='+condition_2;
		//alert('input:'+input_table[this.index].value+'span:'+span[num].innerHTML);
		
		if(input_table[this.index].value != ''){
			ajax('post','indexphp.php',url_value,
				function fun(value){
					var data = JSON.parse(value);
					//alert(data['EchoDB']);
					if(data['EchoDB'] == '1'){
						span[num].className = 'success_sp';
					}else{
						span[num].className = 'failure_sp';
					}
				}
			);
		}else{
			span[num].className = 'failure_sp';
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
<?php echo "<script type='text/javascript'>insert_subaccount();</script>";?>
</body>
</html>
