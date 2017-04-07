<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>mac</title>

<style type="text/css">
#pay tr:nth-child(even) {background: #FC9}
#pay tr:nth-child(odd) {background: #FFF}

#revenue tr:nth-child(even) {background: #FC9}
#revenue tr:nth-child(odd) {background: #FFF}
</style>
<script src="fun.js"></script>
</head>

<body>
<?php
	session_start();
?>
<script type="text/javascript">
	function show_mac(){
	
	var right_title = document.getElementById("right_title");
	var right_content = document.getElementById('right_content');
	var right_page = document.getElementById('right_page');
	var url_value = "instruction=IndexMachine&machine_number=<?php echo $_GET['machine_number']?>&month=<?php echo date("Y-m")?>;";
	var table='';
	var ln='<?php echo $_GET['ln']?>';
	
	ajax('post','indexphp.php',url_value,
		function fun(value){
		var data = JSON.parse(value);
		var str_content='';
		var acc_content='';
		
		if(data['Information'][0]==''){data['Information'][0]='無';}
		
		table ='<p><span></span></p>'+'<p><span><input type = "button" id = "show_revenue" value ="查看當月營收"></span><span><input type = "button" id = "show_pay" value ="查看當月支出"></span>'+
		'<span><input type = "button" id = "show_cm" value ="查看當月支出"></span></p>';
		var title ='<p style="background-color:#F2E85F"><span>'+data['Information'][1]+'</span></p>';
		right_title.innerHTML =title;
		right_content.innerHTML =table;
		right_page.innerHTML = '';
		
		var span = document.getElementsByTagName('span');
		if(ln=='cn'){
			span[0].innerHTML=data['Information'][1];
			span[2].innerHTML='<input type = "button" id = "show_revenue" value ="查看当月营收">';
			span[3].innerHTML='<input type = "button" id = "show_pay" value ="查看当月支出">';
			span[4].innerHTML='<input type = "button" id = "show_cm" value ="查看当月支出">';
		}else if(ln=='en'){
			span[0].innerHTML=data['Information'][1];
			span[2].innerHTML='<input type = "button" id = "show_revenue" value ="View month revenue">';
			span[3].innerHTML='<input type = "button" id = "show_pay" value ="View month expenditure">';
			span[4].innerHTML='<input type = "button" id = "show_cm" value ="View month expenditure">';
		}
		var input = document.getElementsByTagName('input');
		var sel = document.getElementById('account');
		var show_revenue = document.getElementById('show_revenue');
		var show_pay = document.getElementById('show_pay');
		var show_cm = document.getElementById('show_cm');
		
		
		if(data['Information'][0].substr(0,2)=="CM"){
			span[1].style.display="none";
			span[2].style.display="none";
			span[3].style.display="none";
			
			//判斷是否出租
		}else if(data['Account']!=''){
			span[2].style.display="none";
			span[3].style.display="none";
			span[4].style.display="none";
			span[1].innerHTML='<font color="red">已出租</font>';
			if(ln=='cn'){
				span[1].innerHTML='<font color="red">已出租</font>';
			}else if(ln=='en'){
				span[1].innerHTML='<font color="red">Leased</font>';
			}
		}else{
			span[1].style.display="none";
			span[4].style.display="none";
		}
		
		//查看收入
		show_revenue.onclick = function(){
			var url_value = 'instruction=ShowRevenue&machine_number='+data['Information'][0]+'&month=<?php echo date("Y-m");?>';
			show_page_content(url_value,6,25);
		}
		
		//查看支出
		show_pay.onclick = function(){
			var url_value = "instruction=ShowPay&machine_number="+data['Information'][0]+'&month=<?php echo date("Y-m");?>';
			show_page_content(url_value,7,25);
		}
		
		//查看兌幣量
		show_cm.onclick = function(){
			var url_value = "instruction=ShowPay&machine_number="+data['Information'][0]+'&month=<?php echo date("Y-m");?>';
			show_page_content(url_value,8,25);
		}
		
		})
}

//顯示頁碼及內容
function show_page_content(url_value,condition,num){
	
	ajax('post','indexphp.php',url_value,
		function fun(value){
			var data = JSON.parse(value);
			var total_json = data['EchoDB'];						//搜尋到的所有資料
			var ln ='<?php echo $_GET['ln']?>';
			var con='';
			if(total_json.length > 0){
				//var num = 8;										//控制每頁顯示的筆數
				var page_quantity = 10;								//顯示頁碼數量
				var total_page = Math.ceil(total_json.length/num);	//總頁數
				var str_a = '';										//頁碼連結標籤
				str_a = page_change(1,'down');
				page_content_change();
				
				function page_content_change(){
					//如果總頁數不到1頁就不用顯示頁碼
					if(total_page > 1){
						/*先在網頁底部生成頁碼按鈕
						  再讓第一個頁碼有樣式
						  然後顯示第一個頁碼的內容*/
						right_page.innerHTML = str_a;
						var a = right_page.getElementsByTagName('a');
						a[0].className = 'touch_a';
						content(1);
						/*當按了其中一個頁碼就先把其他頁碼的樣式都刪掉
						  再將按了的頁碼加上樣式並顯示內容*/
						for(var i=0;i<a.length;i++){
							a[i].index = i; 			//記錄每一個新的頁碼
							a[i].onclick = function(){
								/*點擊最後一個頁碼，id可以整除顯示頁碼數量表示是下一頁按鈕
								  點擊第一個頁碼，但是id不能等於1，等於1表示是第1頁的資料
								  由於第一個判斷式id不能等於總頁碼就會跑到else，所以第二個也要不能等於總頁碼*/
								if(this.id%page_quantity == 0 ){
									//alert('down'+this.id);
									str_a = page_change(this.id,'down');
									page_content_change();
								}else if(this.index == 0 && this.id != 1){
									str_a = page_change(this.id,'up');
									page_content_change();
								}
								//將所有頁碼的樣式全部清空
								for(var i=0;i<a.length;i++){
									a[i].className = '';
								}
								//alert('index:'+this.index+'a:'+a.length+'id:'+this.id+'total:'+total_page);
								
								/*點擊最後一個頁碼，第一次的下一頁頁碼index不會超過10，所以如果-10會變負的
								  點擊第一個頁碼，第一次的頁碼按鈕不會有12個*/
								if(this.id%page_quantity == 0 && this.index != 1 ){
									this.index % page_quantity == 1 ? a[this.index-page_quantity].className = 'touch_a' : a[this.index-(page_quantity-2)].className = 'touch_a';
								}else if(this.index == 0 && this.id != 1){
									a.length == (page_quantity+2) ? a[this.index+page_quantity].className = 'touch_a' : a[this.index+(page_quantity-2)].className = 'touch_a';
								}else{
									a[this.index].className = 'touch_a';
								}
								content(this.id);
							}
						}
					}else{
						content(1);
						right_page.innerHTML = '';
					}
				}
			}else{
				if(ln=='cn'){
					con='查无纪录...';
				}else if(ln=='en'){
					con='No data...';
				}else{
					con='查無紀錄...';
				}
				right_title.innerHTML = ' ';
				right_content.innerHTML = con;
				right_page.innerHTML = ' ';
				
				if(condition == 3){
					setTimeout(function(){show_store_data(right_title,right_content,right_page);},1000);
				}else if(condition == 6 || condition == 7){
					//setTimeout(function(){show_machine_data(right_title,right_content,right_page);},1000);
				}else if(condition == 5){
					setTimeout(function(){window.location.href = "http://127.0.0.1/luhaoweb/index.php";},1000);
				}
			}
			
			//產生頁碼，每次由第一頁開始，每當按一次下一頁或是上一頁重新創出頁碼按鈕
			function page_change(page_begin,UandD){
				//alert('page_begin:'+page_begin);
				/*第一次創建頁碼按鈕時不需要有上一頁
				  第二次開時頁碼得開始頁一定會大於或等於頁碼的顯示數量
				  當傳進來的頁碼餘數是0時表示按下一頁
				  當傳進來的頁碼餘數是顯示(頁數-1)時表示按了上一頁*/
				if(page_begin%page_quantity == 0 && page_begin >= page_quantity){
					str_a = '<a href = "#" id='+(page_begin-1)+'>上'+page_quantity+'頁</a>';
					str_a += '<a href = "#" id='+page_begin+'>'+page_begin+'</a>';
					page_begin++;
				}else if(page_begin%page_quantity == (page_quantity-1) && page_begin >= page_quantity){
					str_a = '<a href = "#" id='+(page_begin-page_quantity)+'>上'+page_quantity+'頁</a>';
					str_a += '<a href = "#" id='+(page_begin-(page_quantity-1))+'>'+(page_begin-(page_quantity-1))+'</a>';
					page_begin++;
				}
				
				if(UandD == 'down'){
					for(var i=page_begin;i <= total_page;i++){
						if(i%page_quantity == 0){
							str_a += '<a href = "#" id='+i+'>下'+page_quantity+'頁</a>';
							break;
						}else{
							str_a += '<a href = "#" id='+i+'>'+i+'</a>';
						}
					}
				}else{
					for(var i=page_begin-(page_quantity-1);i <= total_page;i++){
						if(i%page_quantity == 0 && i != 0){
							str_a += '<a href = "#" id='+i+'>下'+page_quantity+'頁</a>';
							break;
						}else{
							if(page_begin == (page_quantity-1) && i == 1 ) str_a = '';
							str_a += '<a href = "#" id='+i+'>'+i+'</a>';
						}
					}
				}
				return str_a;
			}
			
			//顯示操作內容
			function content(page){
				var begin = (page-1)*num;  //頁碼內的開始索引值
				var end = 0;			   //頁碼內的結束索引值
				
				/*當全部內容小於顯示在該頁碼內的數量時
				  結束索引值要開始索引值+頁碼內的數量
				  否則就等於內容的長度*/
				if((total_json.length-begin)>num){
					end = begin + num;
				}else{
					end = total_json.length;
				}

				var str_content = '';
				if(condition == 1){
					show_page_account(begin,end,str_content,total_json);
				}else if(condition == 2){
					show_page_store(begin,end,str_content,total_json);
				}else if(condition == 3){
					show_page_store_delete(begin,end,str_content,total_json);
				}else if(condition == 4){
					show_page_machine(begin,end,str_content,total_json);
				}else if(condition == 5){
					show_page_error(begin,end,str_content,total_json);
				}else if(condition == 6){
					show_page_revenue(begin,end,str_content,total_json);
				}else if(condition == 7){
					show_page_pay(begin,end,str_content,total_json);
				}else if(condition == 8){
					show_page_cm(begin,end,str_content,total_json);
				}else if(condition == 9){
					show_page_lastrevenue(begin,end,str_content,total_json);
				}else if(condition == 10){
					show_page_lastpay(begin,end,str_content,total_json);
				}
			}
	});
}

//頁碼內操作收入
function show_page_revenue(begin,end,str_content,total_json){
	var auto = '';
	if(<?php echo @$_SESSION['competence']?> =='4'){
		for(var i=begin;i<end;i++){
			total_json[i][3] == '0' ? auto = '自動' : auto = '手動';
			str_content += '<tr>'+
							'<td style = "width:50px;">'+total_json[i][0]+'</td>'+
							'<td style = "width:50px;">'+total_json[i][1]+'</td>'+
							'<td style = "width:160px">'+total_json[i][2]+'</td>'+
							'<td style = "width:50px;">'+auto+'</td>'+
						'</tr>';
		}
		right_title.innerHTML = '<p class = "revenue_pay">機台編號'+total_json[0][1]+'的<span style="color:red"><?php echo date("m"); ?></span>月詳細<span style = "color:blue;">收入</span></p>';
		right_content.innerHTML = '<input type="button" id="month" value="<?php echo date("m")-1?>月份"><br></br>'+
							'<table border = "1" cellpadding="0" cellspacing="0" class = "mar" id="revenue"><tr>'+
								'<td style ="background-color:#600;color:#FFF">類型</td>'+
								'<td style ="background-color:#600;color:#FFF">金額</td>'+
								'<td style ="background-color:#600;color:#FFF">新增日期</td>'+
								'<td style ="background-color:#600;color:#FFF">動作</td></tr>'+
								str_content+
							'</table>';	
	}else{
		for(var i=begin;i<end;i++){
			total_json[i][4] == '0' ? auto = '自動' : auto = '手動';
			str_content += '<tr>'+
							'<td style = "width:50px;">'+total_json[i][2]+'</td>'+
							'<td style = "width:160px">'+total_json[i][3]+'</td>'+
							'<td style = "width:50px;">'+auto+'</td>'+
						'</tr>';
		}
		right_title.innerHTML = '<p class = "revenue_pay">機台編號'+total_json[0][1]+'的<span style="color:red"><?php echo date("m"); ?></span>月詳細<span style = "color:blue;">收入</span></p>';
		right_content.innerHTML = '<input type="button" id="month" value="<?php echo date("m")-1?>月份"><br></br>'+
							'<table border = "1" cellpadding="0" cellspacing="0" class = "mar" id="revenue"><tr>'+
								'<td style ="background-color:#600;color:#FFF">金額</td>'+
								'<td style ="background-color:#600;color:#FFF">新增日期</td>'+
								'<td style ="background-color:#600;color:#FFF">動作</td></tr>'+
								str_content+
							'</table>';		
	}

	var month =document.getElementById("month");
	month.onclick=function(){
		var url_value = 'instruction=ShowRevenue&machine_number=<?php echo $_GET['machine_number']?>&month=<?php echo date("Y-");echo date("m")-1?>';
		show_page_content(url_value,9,25);
	}
}

//頁碼內上月收入
function show_page_lastrevenue(begin,end,str_content,total_json){
	var auto = '';
	if(<?php echo @$_SESSION['competence']?> == '4'){
		for(var i=begin;i<end;i++){
			total_json[i][3] == '0' ? auto = '自動' : auto = '手動';
			str_content += '<tr>'+
							'<td style = "width:50px;">'+total_json[i][0]+'</td>'+
							'<td style = "width:50px;">'+total_json[i][1]+'</td>'+
							'<td style = "width:160px">'+total_json[i][2]+'</td>'+
							'<td style = "width:50px;">'+auto+'</td>'+
						'</tr>';
		}
		right_title.innerHTML = '<p class = "revenue_pay">機台編號'+total_json[0][1]+'的<span style="color:red"><?php echo date("m",strtotime("last month")) ?></span>月詳細<span style = "color:blue;">收入</span></p>';
		right_content.innerHTML = '<input type="button" id="month" value="當月份"><br></br>'+
							'<table border = "1" cellpadding="0" cellspacing="0" class = "mar" id="revenue"><tr>'+
								'<td style ="background-color:#600;color:#FFF">類型</td>'+
								'<td style ="background-color:#600;color:#FFF">金額</td>'+
								'<td style ="background-color:#600;color:#FFF">新增日期</td>'+
								'<td style ="background-color:#600;color:#FFF">動作</td></tr>'+
								str_content+
							'</table>';	
	}else{
		for(var i=begin;i<end;i++){
			total_json[i][4] == '0' ? auto = '自動' : auto = '手動';
			str_content += '<tr>'+
							'<td style = "width:50px;">'+total_json[i][2]+'</td>'+
							'<td style = "width:160px">'+total_json[i][3]+'</td>'+
							'<td style = "width:50px;">'+auto+'</td>'+
						'</tr>';
		}
		right_title.innerHTML = '<p class = "revenue_pay">機台編號'+total_json[0][1]+'的<span style="color:red"><?php echo date("m",strtotime("last month")) ?></span>月詳細<span style = "color:blue;">收入</span></p>';
		right_content.innerHTML = '<input type="button" id="month" value="當月份"><br></br>'+'<table border = "1" cellpadding="0" cellspacing="0" class = "mar" id="revenue"><tr>'+
								'<td style ="background-color:#600;color:#FFF">金額</td>'+
								'<td style ="background-color:#600;color:#FFF">新增日期</td>'+
								'<td style ="background-color:#600;color:#FFF">動作</td></tr>'+
								str_content+
							  '</table>';
	}

	var month =document.getElementById("month");
	month.onclick=function(){
		var url_value = 'instruction=ShowRevenue&machine_number=<?php echo $_GET['machine_number']?>&month=<?php echo date("Y-m");?>';
		show_page_content(url_value,6,25);
	}
}

//頁碼內操作支出
function show_page_pay(begin,end,str_content,total_json){
	if(<?php echo @$_SESSION['competence']?> == '4'){
		for(var i=begin;i<end;i++){
			total_json[i][3] == '0' ? auto = '自動' : auto = '手動';
			str_content += '<tr>'+
							'<td style = "width:50px;">'+total_json[i][0]+'</td>'+
							'<td style = "width:50px;">'+total_json[i][1]+'</td>'+
							'<td style = "width:160px">'+total_json[i][2]+'</td>'+
							'<td style = "width:50px;">'+auto+'</td>'+
						'</tr>';
		}
		right_title.innerHTML = '<p class = "revenue_pay">機台編號'+total_json[0][1]+'的<span style="color:red"><?php echo date("m",strtotime("last month")) ?></span>月詳細<span style = "color:blue;">收入</span></p>';
		right_content.innerHTML = '<input type="button" id="month" value="當月份"><br></br>'+
							'<table border = "1" cellpadding="0" cellspacing="0" class = "mar" id="revenue"><tr>'+
								'<td style ="background-color:#600;color:#FFF">類型</td>'+
								'<td style ="background-color:#600;color:#FFF">金額</td>'+
								'<td style ="background-color:#600;color:#FFF">新增日期</td>'+
								'<td style ="background-color:#600;color:#FFF">動作</td></tr>'+
								str_content+
							'</table>';
	}else{
		for(var i=begin;i<end;i++){
			str_content += '<tr>'+
							'<td style = "width:50px;">'+total_json[i][1]+'</td>'+
							'<td style = "width:160px;">'+total_json[i][2]+'</td>'+
							'<td style = "width:160px;">'+total_json[i][3]+'</td>'+
							'<td style = "width:160px;">'+total_json[i][4]+'</td>'+
							'<td style = "width:160px;">'+total_json[i][6]+'</td>'+
							'<td style = "width:160px;">'+total_json[i][5]+'</td>'+
						'</tr>';
		}
		right_title.innerHTML = '<p class = "revenue_pay">機台編號'+total_json[0][0]+'的<span style="color:red"><?php echo date("m"); ?></span>月詳細<span style = "color:red;">支出</span></p>';
		right_content.innerHTML = '<input type="button" id="month" value="<?php echo date("m")-1?>月份"><br></br>'+'<table border = "1" cellpadding="0" cellspacing="0" class = "mar" id="pay"><tr>'+
								'<td style ="background-color:#600;color:#FFF">商品代碼</td>'+
								'<td style ="background-color:#600;color:#FFF">商品名稱</td>'+
								'<td style ="background-color:#600;color:#FFF">數量</td>'+
								'<td style ="background-color:#600;color:#FFF">單價</td>'+
								'<td style ="background-color:#600;color:#FFF">總額</td>'+
								'<td style ="background-color:#600;color:#FFF">新增日期</td></tr>'+
								str_content+
							  '</table>';
	}

		var month =document.getElementById('month');
		
		month.onclick=function(){
			var url_value ='instruction=ShowPay&machine_number=<?php echo $_GET['machine_number']?>&month=<?php echo date("Y-"); echo date("m")-1;?>';
			show_page_content(url_value,10,25);
		}
}

//頁碼內上月支出
function show_page_lastpay(begin,end,str_content,total_json){
	if(<?php echo @$_SESSION['competence']?> == '4'){
		for(var i=begin;i<end;i++){
			total_json[i][3] == '0' ? auto = '自動' : auto = '手動';
			str_content += '<tr>'+
							'<td style = "width:50px;">'+total_json[i][0]+'</td>'+
							'<td style = "width:50px;">'+total_json[i][1]+'</td>'+
							'<td style = "width:160px">'+total_json[i][2]+'</td>'+
							'<td style = "width:50px;">'+auto+'</td>'+
						'</tr>';
		}
		right_title.innerHTML = '<p class = "revenue_pay">機台編號'+total_json[0][1]+'的<span style="color:red"><?php echo date("m",strtotime("last month")) ?></span>月詳細<span style = "color:blue;">收入</span></p>';
		right_content.innerHTML = '<input type="button" id="month" value="當月份"><br></br>'+
							'<table border = "1" cellpadding="0" cellspacing="0" class = "mar" id="revenue"><tr>'+
								'<td style ="background-color:#600;color:#FFF">類型</td>'+
								'<td style ="background-color:#600;color:#FFF">金額</td>'+
								'<td style ="background-color:#600;color:#FFF">新增日期</td>'+
								'<td style ="background-color:#600;color:#FFF">動作</td></tr>'+
								str_content+
							'</table>';
	}else{
		for(var i=begin;i<end;i++){
			str_content += '<tr>'+
							'<td style = "width:50px;">'+total_json[i][1]+'</td>'+
							'<td style = "width:160px;">'+total_json[i][2]+'</td>'+
							'<td style = "width:160px;">'+total_json[i][3]+'</td>'+
							'<td style = "width:160px;">'+total_json[i][4]+'</td>'+
							'<td style = "width:160px;">'+total_json[i][6]+'</td>'+
							'<td style = "width:160px;">'+total_json[i][5]+'</td>'+
						'</tr>';
		}
		right_title.innerHTML = '<p class = "revenue_pay">機台編號'+total_json[0][0]+'的<span style="color:red"><?php echo date("m",strtotime("last month")); ?></span>月詳細<span style = "color:red;">支出</span></p>';
		right_content.innerHTML = '<input type="button" id="month" value="當月份"><br></br>'+'<table border = "1" cellpadding="0" cellspacing="0" class = "mar" id="pay"><tr>'+
								'<td style ="background-color:#600;color:#FFF">商品代碼</td>'+
								'<td style ="background-color:#600;color:#FFF">商品名稱</td>'+
								'<td style ="background-color:#600;color:#FFF">數量</td>'+
								'<td style ="background-color:#600;color:#FFF">單價</td>'+
								'<td style ="background-color:#600;color:#FFF">總額</td>'+
								'<td style ="background-color:#600;color:#FFF">新增日期</td></tr>'+
								str_content+
							  '</table>';		
	}

		var month =document.getElementById('month');
		
		month.onclick=function(){
			var url_value ='instruction=ShowPay&machine_number=<?php echo $_GET['machine_number']?>&month=<?php echo date("Y-m");?>';
			show_page_content(url_value,7,25);
		}
}

//頁碼內兌幣支出
function show_page_cm(begin,end,str_content,total_json){
	var auto = '';
	var type = '';
	for(var i=begin;i<end;i++){
		total_json[i][0] == 'coin' ? type = '硬幣' : type = '紙鈔';
		total_json[i][3] == '0' ? auto = '自動' : auto = '手動';
		str_content += '<tr>'+
						'<td style = "width:50px;">'+type+'</td>'+
						'<td style = "width:50px;">'+total_json[i][1]+'</td>'+
						'<td style = "width:160px;">'+total_json[i][2]+'</td>'+
						'<td style = "width:160px;">'+auto+'</td>'+
					   '</tr>';
	}
	right_title.innerHTML = '<p class = "revenue_pay">機台編號'+total_json[0][1]+'的<span style="color:red"><?php echo date("m"); ?></span>月詳細<span style = "color:red;">支出</span></p>';
	right_content.innerHTML = '<table border = "1" cellpadding="0" cellspacing="0" class = "mar" id="pay"><tr>'+
								'<td style ="background-color:#600;color:#FFF">類型</td>'+
								'<td style ="background-color:#600;color:#FFF">數量</td>'+
								'<td style ="background-color:#600;color:#FFF">新增日期</td>'+
								'<td style ="background-color:#600;color:#FFF">動作</td>'+
								str_content+
							  '</table>';
}

</script>

<div id = "right_title">
	<p class = "mar"></p>
</div>
<div id = "right_content" style="overflow:auto">
	
</div>
<div id = "right_page" align="center">
	
</div>
<?php echo "<script type='text/javascript'>show_mac();</script>";?>
</body>
</html>
