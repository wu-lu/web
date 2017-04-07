window.onload = function(){
	var div = document.getElementsByTagName('div');
	var input = div[0].getElementsByTagName('input');
	var span = div[0].getElementsByTagName('span');
	var name =document.getElementsByName('ln');
	var acc_error = 0;
	var pw_error = 0;
	var ln= 'tw';
	
	name[0].onclick =function(){
		ln= 'en';
		input[0].placeholder="Account";
		input[1].placeholder="Password";
		input[2].className='login_en';
		input[3].className='cancel_en';
	}
	name[1].onclick =function(){
		ln= 'tw';
		input[0].placeholder="請輸入帳號";
		input[1].placeholder="請輸入密碼";
		input[2].className='login_bt';
		input[3].className='cancel_bt';
	}
	name[2].onclick =function(){
		ln= 'cn';
		input[0].placeholder="请输入帐号";
		input[1].placeholder="请输入密码";
		input[2].className='login_bt';
		input[3].className='cancel_bt';
	}
	
	input[2].onclick = function(){
		difference(input[0]);
		difference(input[1]);
	    
		if(acc_error == 0 && pw_error == 0){
			ajax('post','../Connect.php','instruction=WebLogin&account='+input[0].value+'&password='+input[1].value,
				function fun(value){
					var date = JSON.parse(value);
					if(date['user'] == '1'){
						//window.location.href = "http://127.0.0.1/luhaoweb2/DirectSuppliers/index.php?ln="+ln;
						window.location.href = "http://www.luhao.com.tw/luhaoweb/DirectSuppliers/index.php?ln="+ln;
					}else if(date['user'] == '3'){
						//window.location.href = "http://127.0.0.1/luhaoweb2/DirectSuppliers/index.php?ln="+ln;
						window.location.href = "http://www.luhao.com.tw/luhaoweb/DirectSuppliers/index.php?ln="+ln;
					}else if(date['user'] == '4'){
						//window.location.href = "http://127.0.0.1/luhaoweb2/DirectSuppliers/index.php?ln="+ln;
						window.location.href = "http://www.luhao.com.tw/luhaoweb/DirectSuppliers/index.php?ln="+ln;
					}else{
						span[0].innerHTML = '帳號或密碼輸入錯誤';
					}
				});
		}else if(acc_error == 1){
			span[0].innerHTML = '帳號不能為空值';
		}else if(pw_error == 1){
			span[0].innerHTML = '密碼不能為空值';
		}else if(acc_error == 2){
			span[0].innerHTML = '帳號請輸入英文或數字';
		}else if(pw_error == 2){
			span[0].innerHTML = '密碼請輸入英文或數字';
		}else{
			span[0].innerHTML = '登入失敗';
		}
	}
	
	input[3].onclick = function() {
		input[0].value = '';
		input[1].value = '';
		span[0].innerHTML = '';
	}
	
	input[4].onclick = function(){
		window.location.href = "http://127.0.0.1/luhaoweb2/DirectSuppliers/register_info.php";
		//window.location.href = "http://www.luhao.com.tw/luhaoweb/DirectSuppliers/register_info.php";
	}
	
	function difference(text){
		
		if(text.value == ''){
			 if(text.name == 'account') acc_error = 1;
			 if(text.name == 'password') pw_error = 1;
		}else{
			for(var i=0;i<input[0].value.length;i++){
				//alert(text.value.charCodeAt(i));
				if((text.value.charCodeAt(i)<47 || text.value.charCodeAt(i)>57) && (text.value.charCodeAt(i)<64 || text.value.charCodeAt(i)>90) && (text.value.charCodeAt(i)<96 || text.value.charCodeAt(i)>122) && text.value.charCodeAt(i) != 64 && text.value.charCodeAt(i) !=46){
					if(text.name == 'account') acc_error = 2;
					if(text.name == 'password') pw_error = 2;
					break;
				}else{
					if(text.name == 'account') acc_error = 0;
					if(text.name == 'password') pw_error = 0;
				}
			}
		}
	}
}
