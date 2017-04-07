<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>新增機台</title>
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
<script src="fun.js"></script>
</head>

<body>
<?php
	session_start();
?>
<script type="text/javascript">
	function insert_store(){
	
	var ln ='<?php echo $_GET['ln']?>';
	
	var right_title = document.getElementById("right_title1");
	var right_content = document.getElementById('right_content1');
	var right_page = document.getElementById('right_page1');
	var title = '<p><span style="font-size:15px;"><font color="red">'+'新增機台'+'</font></span></p>'+
	'<p>'+
		'新增機台數量：<input type ="text" required="required">&nbsp;'+
		'<input type ="submit" value = "送出">'+
				'</p>';
	var title_cn = '<p><span style="font-size:15px;"><font color="red">'+'新增机台'+'</font></span></p>'+
	'<p>'+
		'新增机台数量：<input type ="text" required="required">&nbsp;'+
		'<input type ="submit" value = "送出">'+
				'</p>';
	var title_en = '<p><span style="font-size:15px;"><font color="red">'+'Add machine'+'</font></span></p>'+
	'<p>'+
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
	var content_cn = '';
	var content_en = '';
	var table_title = '';
	var table_title_cn = '';
	var table_title_en = '';
	var input = document.getElementsByTagName('input');		//right_title的所有input標籤
	var table_value = Array();								//right_content的所有input標籤
	var span = Array();										//顯示檢查結果的span標籤
	var table_value_length = 0;								//right_content動態產生的所有input標籤的個數
	var add_value =Array();									//機台編號的前面:帳號+商店編號
	input[1].onclick = show;
	
	//動態產生基本資料框
	function show(){
		operating_static = 0;
		content = '';
		content_cn = '';
		content_en = '';
		var n = 0;
		if(<?php echo @$_SESSION['competence']?>=='4'){
			table_title = '<tr style = "text-align:center;"><td></td>'+
							'<td>機台類型</td>'+
							'<td>主機板編號</td>'+
							'<td>機台名稱</td>'+
							'<td>Key-in/初(入)</td>'+
							'<td>pay/初(出)</td>'+
							'<td>Key out</td>'+
							'<td>初(入)</td>'+
							'<td>初(出)</td>'+
							'<td>Key(入)</td>'+
							'<td>Key(出)</td>'+
							'<td>分成比</td>'+
						  '</tr>';
			table_title_cn = '<tr style = "text-align:center;"><td></td>'+
							'<td>机台类型</td>'+
							'<td>机台编号</td>'+
							'<td>机台名称</td>'+
							'<td>初始值(入)</td>'+
							'<td>初始值(出)</td>'+
							'</tr>';
			table_title_en = '<tr style = "text-align:center;"><td></td>'+
							'<td>Machine type</td>'+
							'<td>Machine number</td>'+
							'<td>Machine name</td>'+
							'<td>Initial value(in)</td>'+
							'<td>Initial value(out)</td>'+
							'</tr>';
		}else{
			table_title = '<tr style = "text-align:center;"><td></td>'+
							'<td>機台類型</td>'+
							'<td>主機板編號</td>'+
							'<td>機台名稱</td>'+
							'<td>初始值(入)</td>'+
							'<td>初始值(出)</td>'+
						  '</tr>';
			table_title_cn = '<tr style = "text-align:center;"><td></td>'+
							'<td>机台类型</td>'+
							'<td>机台编号</td>'+
							'<td>机台名称</td>'+
							'<td>初始值(入)</td>'+
							'<td>初始值(出)</td>'+
							'</tr>';
			table_title_en = '<tr style = "text-align:center;"><td></td>'+
							'<td>Machine type</td>'+
							'<td>Machine number</td>'+
							'<td>Machine name</td>'+
							'<td>Initial value(in)</td>'+
							'<td>Initial value(out)</td>'+
							'</tr>';
		}
			while(n<input[0].value){
				content += '<tr><td style="width:20px;"><span class="sp"></span></td>'+
								'<td><select style ="width:100px;">';
				//分辨帳號
				if(<?php echo @$_SESSION['competence']?>=='1'){
					content +='<option value ="1">娃娃機</option>';
				}else if(<?php echo @$_SESSION['competence']?>=='3'){
						content +='<option value ="1">娃娃機</option>';
				}else if(<?php echo @$_SESSION['competence']?>=='4'){
					content +='<option value ="1">遊戲機</option>';
				}
				if(<?php echo @$_SESSION['competence']?>=='4'){
					content +='<option value ="2">兌幣機</option></select></td>'+'<td><input type = "text" style ="width:90px;"></td>'+
																		'<td><input type = "text" style ="width:115px;"></td>'+
																		'<td><input type = "text" style ="width:100px;"></td>'+
																		'<td><input type = "text" style ="width:100px;"></td>'+
																		'<td><input type = "text" style ="width:70px;"></td>'+
																		'<td><input type = "text" style ="width:70px;"></td>'+
																		'<td><input type = "text" style ="width:70px;"></td>'+
																		'<td><input type = "text" style ="width:70px;"></td>'+
																		'<td><input type = "text" style ="width:70px;"></td>'+
																		'<td><input type = "text" style ="width:100px;"></td>'+
																		'</tr>';
				content_cn += '<tr><td style="width:20px;"><span class="sp"></span></td>'+
								'<td><select style ="width:100px;">';
				}else{
					content +='<option value ="2">兌幣機</option></select></td>'+'<td><input type = "text" style ="width:100px;"></td>'+
							   '<td><input type = "text" style ="width:150px;"></td><td><input type = "text" style ="width:100px;"></td><td><input type = "text" style ="width:100px;"></td></tr>';
				content_cn += '<tr><td style="width:20px;"><span class="sp"></span></td>'+
								'<td><select style ="width:100px;">';
				}
				
				//分辨帳號
				if(<?php echo @$_SESSION['competence']?>=='1'){
					content_cn +='<option value ="1">娃娃机</option>';
				}else if(<?php echo @$_SESSION['competence']?>=='3'){
						content_cn +='<option value ="1">娃娃机</option>';
				}else if(<?php echo @$_SESSION['competence']?>=='4'){
					content_cn +='<option value ="1">游戏机</option>';
				}
				content_cn +='<option value ="2">兑币机</option></select></td>'+'<td><input type = "text" style ="width:100px;"></td>'+
							   '<td><input type = "text" style ="width:150px;"></td><td><input type = "text" style ="width:100px;"></td><td><input type = "text" style ="width:100px;"></td></tr>';
				content_en += '<tr><td style="width:20px;"><span class="sp"></span></td>'+
								'<td><select style ="width:100px;">';
				//分辨帳號
				if(<?php echo @$_SESSION['competence']?>=='1'){
					content_en +='<option value ="1">Doll machine</option>';
				}else if(<?php echo @$_SESSION['competence']?>=='3'){
						content_en +='<option value ="1">Doll machine</option>';
				}else if(<?php echo @$_SESSION['competence']?>=='4'){
					content_en +='<option value ="1">Game machine</option>';
				}
				content_en +='<option value ="2">Coin change machine</option></select></td>'+'<td><input type = "text" style ="width:100px;"></td>'+
							   '<td><input type = "text" style ="width:150px;"></td><td><input type = "text" style ="width:100px;"></td><td><input type = "text" style ="width:100px;"></td></tr>';
				n++;
			}
		
		//以表格方式產生整個頁面
		content = '<table class = "mar" align="left">'+
				  table_title+
				  content+
				  '<tr><td colspan="7" style = "text-align:center;"><input type ="button" value="儲存" id = "output">&nbsp;'+
				  '<input type ="button" value="取消" id = "cancel"></td></tr>'+
				  '</table>';
		content_cn = '<table class = "mar" align="left">'+
				  table_title_cn+
				  content_cn+
				  '<tr><td colspan="6" style = "text-align:center;"><input type ="button" value="储存" id = "output">&nbsp;'+
				  '<input type ="button" value="取消" id = "cancel"></td></tr>'+
				  '</table>';
		content_en = '<table class = "mar" align="left">'+
				  table_title_en+
				  content_en+
				  '<tr><td colspan="6" style = "text-align:center;"><input type ="button" value="Save" id = "output">&nbsp;'+
				  '<input type ="button" value="Cancel" id = "cancel"></td></tr>'+
				  '</table>';
		if(ln=='cn'){
			right_content.innerHTML = content_cn;
		}else if(ln=='en'){
			right_content.innerHTML = content_en;
		}else{
			right_content.innerHTML = content;
		}
		var output = document.getElementById('output');  //儲存按鈕
		var cancel = document.getElementById('cancel');	 //取消按鈕
		var table_sel = right_content.getElementsByTagName('select');	//娃娃機下拉式選單
		span = right_content.getElementsByTagName('span');
		
		table_value = right_content.getElementsByTagName('input');		//全部都是輸入框
		table_value_length = table_value.length-2;
		
		if(<?php echo @$_SESSION['competence']?>=='4'){
			check_number(table_value,span,10,2,'string',table_sel);		//檢查機台編碼是否使用過
		}else{
			check_number(table_value,span,4,2,'string',table_sel);		//檢查機台編碼是否使用過
		}
		
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
			var name_arr = Array();
			var number_arr= Array();
			var key_in=Array();
			var pay=Array();
			var key_out=Array();
			var in_arr=Array();
			var out_arr=Array();
			var keyy_in=Array();
			var keyy_out=Array();
			var percent=Array();
			var title_number_arr= Array();
			
			var error = 0;			//檢查是否有錯誤存在
			if(confirm("确定送出?")){
			operating_static = 1;
			//判斷娃娃機or兌幣機
			for(var i=0;i<n;i++){
				if(table_sel[i].value == '2'){
					add_value[i]="CM_";
				}else{
					if(<?php echo @$_SESSION['competence']?>=='4'){
						add_value[i]="GM_";
					}else{
						add_value[i]="";
					}
				}
				//alert(table_sel[i].value);
				//alert(add_value[i]);
			}
			for(var i=0;i<table_value_length;i++){
				if(table_value[i].value != ''){
					if(<?php echo $_SESSION['competence']?>=='4'){
						if(i%10 == 0){
						
							if(span[i/10].className == 'failure_sp'){
								error = 2 ;									//帳號檢查錯誤
								break;
							}else{
								title_number_arr.push(add_value[i/10]);	//儲存娃娃機選項欄
								number_arr.push(table_value[i].value);	//儲存機台編號欄
								//alert(table_value[i].value);
									
								/*select1.value == '1' ? number_arr.push(add_value+table_value[i].value) : number_arr.push(table_value[i].value);*/	//儲存機台編號欄
							}
						}
							if(i%10 == 1) name_arr.push(table_value[i].value);	//儲存機台名稱欄
							if(i%10 == 2) key_in.push(table_value[i].value);	//儲存預設值(入)欄
							if(i%10 == 3) pay.push(table_value[i].value);		//儲存預設值(入)欄
							if(i%10 == 4) key_out.push(table_value[i].value);	//儲存預設值(出)欄
							if(i%10 == 5) in_arr.push(table_value[i].value);	//儲存預設值(出)欄
							if(i%10 == 6) out_arr.push(table_value[i].value);	//儲存預設值(出)欄
							if(i%10 == 7) keyy_in.push(table_value[i].value);	//儲存預設值(出)欄
							if(i%10 == 8) keyy_out.push(table_value[i].value);	//儲存預設值(出)欄
							if(i%10 == 9) percent.push(table_value[i].value);	//儲存預設值(出)欄
					}else{
						if(i%4 == 0){
						
							if(span[i/4].className == 'failure_sp'){
								error = 2 ;									//帳號檢查錯誤
								break;
							}else{
								title_number_arr.push(add_value[i/4]);	//儲存娃娃機選項欄
								number_arr.push(table_value[i].value);	//儲存機台編號欄
								//alert(table_value[i].value);
									
								/*select1.value == '1' ? number_arr.push(add_value+table_value[i].value) : number_arr.push(table_value[i].value);*/	//儲存機台編號欄
							}
						}
							if(i%4 == 1) name_arr.push(table_value[i].value);	//儲存機台名稱欄
							if(i%4 == 2) in_arr.push(table_value[i].value);		//儲存預設值(入)欄
							if(i%4 == 3) out_arr.push(table_value[i].value);	//儲存預設值(出)欄
					}
					
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
			
			if(error == 0){
				//轉成物件
				if(<?php echo @$_SESSION['competence']?>=='4'){
					var obj = {
						instruction:'InsertMachine',
						account:account,
						store:'<?php echo $_GET['store_number']?>',
						machine:number_arr,
						key_coin:key_in,
						pay:pay,
						key_out:key_out,
						percent:percent,
						key_coin_meter:keyy_in,
						key_out_meter:keyy_out,
						title_number:title_number_arr,
						virtual_revenue:in_arr,
						virtual_pay:out_arr,
						name:name_arr
					};
				}else{
					var obj = {
						instruction:'InsertMachine',
						account:account,
						store:'<?php echo $_GET['store_number']?>',
						machine:number_arr,
						title_number:title_number_arr,
						virtual_revenue:in_arr,
						virtual_pay:out_arr,
						name:name_arr
					};
				}
					
				//alert(obj);
				var SetJson	= JSON.stringify(obj); //把物件轉成可以傳送的JSON
				
				//用非同步方式傳送資料到後端
				ajax('post','indexphp.php',SetJson,
					 function fun(value){
						//alert (SetJson);
						var total = '';
						var data = JSON.parse(value);
							for(var i=0;i<data['name'].length;i++){
								if(data['data'][i] == '1' && data['select'][i] == '1'){
									total += data['name'][i] + '新增成功' + '<br>';
								}else{
									total += data['name'][i] + '新增失敗:'+data['data'][i]+ '<br>';
								}
								//alert('total:'+total);
							}
							right_content.innerHTML = total;
					});
					
				fomat();
			}else if(error == 2){
				if(ln=='cn'){
					alert('机台代码有误!');
				}else if(ln=='en'){
					alert('Machine number error!');
				}else{
					alert('機台代碼有誤!');
				}
			}else{
				if(ln=='cn'){
					alert('不能有空值存在!');
				}else if(ln=='en'){
					alert('dont null values exist!');
				}else{
					alert('不能有空值存在!');
				}
			}
		}}
	
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
		right_page.innerHTML = '';
		content = '';
		table_title = '';
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
				//分辨機台種類
				if(table_sel[i].value == '2'){
					add_value[i]="CM_";
				}else{
					if(<?php echo @$_SESSION['competence']?>=='4'){
						add_value[i]="GM_";
					}else{
						add_value[i]="";
					}
				}
				//alert(table_sel[i].value);
				//alert(add_value[i]);
				}
				if(<?php echo @$_SESSION['competence']?>=='4'){
					condition_1 = add_value[this.index/10]+input_table[this.index].value;
				}else{
					condition_1 = add_value[this.index/4]+input_table[this.index].value;
				}
			}else{
				condition_1 = input_table[this.index].value;
			}
			 //alert(condition_1);
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
						span[num].className ='failure_sp';
					}
			});
		}else{
			span[num].className = 'failure_sp';
			span[num].className = 'failure_sp';
		}
	}
}
</script>

<div id = "right_title1">
	<p class = "mar"></p>
</div>
<div id = "right_content1" style="overflow:auto">
	
</div>
<div id = "right_page1">
	
</div>
<?php echo "<script type='text/javascript'>insert_store();</script>";?>

</body>
</html>
