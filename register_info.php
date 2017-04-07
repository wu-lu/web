<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>註冊</title>
<link rel = "stylesheet" href = "../all.css" type = "text/css">
<script src="fun.js"></script>
</head>

<body>
<script type="text/javascript">
	function register(){
		
		var aa ='<table class="marg" align="center" border="0"><br></br><br></br><br></br><br></br>'+
							'<tr><td colspan="3" style="background-color:#47bdab;font-size:25px;width:400px;">會員註冊</td></tr>'+
							'<tr><td style="width:100px;" >信箱</td><td><span class = "sp"></span></td><td><input type="email" name="email" style="width:260px;font-size:20px;font-family:微軟正黑體 Light;"placeholder="請輸入信箱"></td></tr>'+
							'<tr><td align="center">密碼</td><td></td><td><input type="password" name="password" style="width:260px;font-size:20px;font-family:微軟正黑體 Light;"placeholder="請輸入密碼"></td></tr>'+
							'<tr><td  align="center">姓名</td><td></td><td><input type="text" name="name"  style="width:260px;font-size:20px;font-family:微軟正黑體 Light;"placeholder="請輸入姓名"></td></tr>'+
							'<tr><td align="center">APP帳號</td><td><span></span></td><td><input type="text" name="account"  style="width:260px;font-size:20px;font-family:微軟正黑體 Light;"placeholder="請輸入APP帳號"></td></tr>'+
							'<tr><td align="center">APP密碼</td><td></td><td><input type="text" name="password_app"  style="width:260px;font-size:20px;font-family:微軟正黑體 Light;"placeholder="請輸入APP密碼"></td></tr>'+
							'<tr><td align="center">手機</td><td></td><td><input type="text" name="phone" style="width:260px;font-size:20px;font-family:微軟正黑體 Light;"placeholder="請輸入手機"></td></tr>'+
							'<tr><td align="center">地址</td><td></td><td><input type="text" name="address"  style="width:260px;font-size:20px;font-family:微軟正黑體 Light;"placeholder="請輸入地址"></td></tr>'+
							'<tr><td colspan="3" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit"  class="send" value="送出">&nbsp;<input type="reset" class="cancel" value="清除">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="信箱驗證"></td></tr>'+
				'</table>';
		
		content.innerHTML=aa;
		
		var span = document.getElementsByTagName('span');
		var input = document.getElementsByTagName('input');
		var input_length =input.length-2;
		check_mail(input,span,7,4,null);	//檢查信箱是否使用過
		check_number(input,span,7,1,null);	//檢查帳號是否使用過
		input[7].onclick = function(){
			if(confirm("確定送出?")){
			var error = 0;
			
			for(var i=0;i<input_length;i++){
				if(input[i].value !=''){
					if(span[0].className == 'failure_sp'){
						error = 2;
						break;
					}else if(span[1].className == 'failure_sp'){
						error = 3;
						break;
					}
				}else{
					error =1;
					break;
				}
			}
			if(error == 0){
				//轉成物件
				var obj = {
							instruction:'Register',
							email:input[0].value,
							email_password:input[1].value,
							name:input[2].value,
							account:input[3].value,
							password:input[4].value,
							phone:input[5].value,
							address:input[6].value
						};
			
				var SetJson	 = JSON.stringify(obj);	  	//把物件轉成可以傳送的JSON
				//alert (SetJson);
				ajax('post','../Register.php',SetJson,
					function fun(value){
						var data = JSON.parse(value);
						if(data['message'] == 'success'){
							alert ('註冊成功！');
							alert ('請進行驗證！');
							content.innerHTML='<table class="marg" align="center" border="2"><br></br><br></br><br></br><br></br><tr><td colspan="2" align="center" style="background-color:#47bdab;font-size:25px;">程序驗證</td></tr><tr><td style="width:100px;">信箱</td><td style="width:230px;"><span style="float: left;">'+input[0].value+'</span></td></tr><tr><td style="width:100px;">驗證碼</td><td><input type="text"  style="width:230px;font-size:20px;font-family:微軟正黑體 Light;"placeholder="請輸入驗證碼"></td></tr><tr><td colspan="2" align="center"><input type="submit"  class="send" value="送出">&nbsp;<input type="reset" class="cancel" value="清除"></td></tr></table>';
							
							var input_a = document.getElementsByTagName('input');
							var span = document.getElementsByTagName('span');
							
							input_a[1].onclick = function(){
								if(confirm("確定送出?")){
									var error = 0;
								
									if(input_a[0].value == ''){
										error = 1;
									}
								if(error ==0){
									//轉成物件
									var obj = {
												instruction:'Verification',
												email:span[0].innerHTML,
												verification_password:input_a[0].value
											}
									var SetJson = JSON.stringify(obj);	//把物件轉成可以傳送的JSON
									//alert (SetJson);
									ajax('post','../Register.php',SetJson,
										function fun(value){
											var data = JSON.parse(value);
											if(data['message'] == 'success'){
												alert ('完成認證');
												//location.href = "http://127.0.0.1/luhaoweb2/DirectSuppliers/login.html";
												location.href = "http://www.luhao.com.tw/luhaoweb/DirectSuppliers/login.html";
											}else if(data['message'] == 'not_exist'){
												alert ('驗證碼不存在');
											}else{
												alert ('認證失敗');
											}
									})
								}else if(error ==1){
									alert ('請勿空白！！！');
								}
								}
							}
							
							input_a[2].onclick = function(){
								input_a[0].value = '';
							}
						}
						
					}
				)
			}else if(error == 1){
				alert ('請勿空白！！！');
			}else if(error == 2){
				alert ('信箱已使用');
			}else{
				alert ('帳號已使用');
			}
			
		}
		}
		//取消
		input[8].onclick = function(){
			input[0].value='';
			input[1].value='';
			input[2].value='';
			input[3].value='';
			input[4].value='';
			input[5].value='';
			input[6].value='';
		}
		//信箱驗證
		input[9].onclick = function(){
			content.innerHTML='<table class="marg" align="center" border="2"><br></br><br></br><br></br><br></br><tr><td colspan="2" align="center" style="background-color:#47bdab;font-size:25px;">信箱驗證</td></tr><tr><td style="width:100px;">信箱</td><td style="width:230px;"><input type="text"  style="width:260px;font-size:20px;font-family:微軟正黑體 Light;"placeholder="請輸入信箱"></td></tr><tr><td style="width:100px;">驗證碼</td><td><input type="text"  style="width:260px;font-size:20px;font-family:微軟正黑體 Light;"placeholder="請輸入驗證碼"></td></tr><tr><td colspan="2" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit"  class="send" value="送出">&nbsp;<input type="reset" class="cancel" value="清除">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="重寄驗證碼"></td></tr></table>';
			
			var input = document.getElementsByTagName('input');
			
			input[2].onclick = function(){
				if(confirm('確定送出?')){
					var error = 0;
				
				if(input[0].value == '' || input[1].value == ''){
					error = 1;
				}
				if(error == 0){
					//轉成物件
					var obj = {
								instruction:'Verification',
								email:input[0].value,
								verification_password:input[1].value
								}
					var SetJson = JSON.stringify(obj);	//把物件轉成可以傳送的JSON
					//alert (SetJson);
					ajax('post','../Register.php',SetJson,
						function fun(value){
							var data = JSON.parse(value);
							if(data['message'] == 'success'){
								alert ('完成認證');
								location.href = "http://127.0.0.1/luhaoweb2/DirectSuppliers/login.html";
								//location.href = "http://www.luhao.com.tw/luhaoweb/DirectSuppliers/login.html";
							}else if(data['message'] == 'not_exist'){
								alert ('驗證碼不存在');
							}else{
								alert ('認證失敗');
							}
					})
				}else{
					alert ('請問空白！！！');
				}
				}
			}
			//取消
			input[3].onclick = function(){
				input[0].value = '';
				input[1].value = '';
			}
			//補寄驗證碼
			input[4].onclick = function(){
				content.innerHTML='<table class="marg" align="center" border="2"><br></br><br></br><br></br><br></br><tr><td colspan="2" align="center" style="background-color:#47bdab;font-size:25px;">補寄驗證碼</td></tr><tr><td style="width:100px;">信箱</td><td style="width:230px;"><input type="text"  style="width:260px;font-size:20px;font-family:微軟正黑體 Light;"placeholder="請輸入信箱"></td></tr>><tr><td colspan="2" align="center"><input type="submit"  class="send" value="送出">&nbsp;<input type="reset" class="cancel" value="清除"></td></tr></table>';
				
				var input = document.getElementsByTagName('input');
				
				input[1].onclick = function(){
					if(confirm('確定送出?')){
						var error = 0;
						
						if(input[0].value == ''){
							error = 1;
						}
						if(error == 0){
							url_value = 'instruction=AgainSend&email='+input[0].value;
							//alert (url_value);
							ajax('post','../Register.php',url_value,
								function fun(value){
									var data = JSON.parse(value);
									if(data['message'] == 'success'){
										alert ('請至信箱收信~謝謝');
									}else{
										alert ('寄信失敗！');
									}
								}
							)
						}else{
							alert ('請勿空白！！！');
						}
					}
				}
				
				input[2].onclick = function(){
					input[0].value = '';
				}
			}
		}
	}
	
//查詢編號或帳號
function check_number(input_table,span,number,php_table,check_type){
	var j = 0;			   //確認圖示的索引值
	var condition_1 = '';  //條件1
	var condition_2 = '';  //條件2
	/*如果check_type型態是數字表示確認的是出租狀況
	  否則就是字串型態表示確認新增的機台碼確認*/
	  
	if(typeof(check_type) != 'number'){
		for(var i=0;i<input_table.length-2;i++){
			/*要確認的是第2個位置的欄位值*/
			if(i == 3){
				span[j].index = j;
				input_table[i].index = i;
				j++;
				
				input_table[i].onblur = check;
				number = 3;
			}
		}
	}else{
		check();
	}
	
	function check(){
		var num = this.index/number;
		var add_value=Array();
		if(typeof(check_type) != 'number'){
			condition_1 = input_table[this.index].value;
		}else{
			this.index = check_type;
			condition_1 = input_table[this.index-1].value;
			condition_2 = input_table[this.index].value;
			num = (this.index-1)/number;
		}
		var url_value = 'instruction=CheckNumber&table='+php_table+'&condition_1='+condition_1+'&condition_2='+condition_2;
		//alert('input:'+input_table[this.index].value+'span:'+span[num].innerHTML);
		if(input_table[this.index].value != ''){
			ajax('post','../Register.php',url_value,
				function fun(value){
					var data = JSON.parse(value);
					//alert(data['EchoDB']);
					if(data['EchoDB'] == '1'){
						span[num].className = 'success_sp';
					}else{
						span[num].className = 'failure_sp';
					}
				
			});
		}else{
			span[num].className = 'failure_sp';
		}
	}
}


//查詢信箱
function check_mail(input_table,span,number,php_table,check_type){
	var j = 0;			   //確認圖示的索引值
	var condition_1 = '';  //條件1
	var condition_2 = '';  //條件2
	/*如果check_type型態是數字表示確認的是出租狀況
	  否則就是字串型態表示確認新增的機台碼確認*/
	  
	if(typeof(check_type) != 'number'){
		for(var i=0;i<input_table.length-2;i++){
			/*要確認的是第2個位置的欄位值*/
			if(i == 0){
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
			condition_1 = input_table[this.index].value;
		}else{
			this.index = check_type;
			condition_1 = input_table[this.index-1].value;
			condition_2 = input_table[this.index].value;
			num = (this.index-1)/number;
		}
		var url_value = 'instruction=CheckNumber&table='+php_table+'&condition_1='+condition_1+'&condition_2='+condition_2;
		//alert('input:'+input_table[this.index].value+'span:'+span[num].innerHTML);
		if(input_table[this.index].value != ''){
			ajax('post','../Register.php',url_value,
				function fun(value){
					var data = JSON.parse(value);
					if(data['EchoDB'] == '1'){
						span[num].className = 'success_sp';
					}else{
						span[num].className = 'failure_sp';
					}
				
			});
		}else{
			span[num].className = 'failure_sp';
		}
	}
}
</script>


<div id = "title">
</div>
<div id = "content" class="login" style="overflow:auto">
</div>
<div id = "page">
</div>
<?php echo "<script type='text/javascript'>register();</script>";?>
</body>
</html>