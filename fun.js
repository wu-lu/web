var operating_static;  //頁面操作控制


//顯示頁碼及內容
function show_page_content(url_value,condition,num){
	
	ajax('post','indexphp.php',url_value,
		function fun(value){
			var data = JSON.parse(value);
			var total_json = data['EchoDB'];						//搜尋到的所有資料
			
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
				right_title.innerHTML = ' ';
				right_content.innerHTML = '查無紀錄...';
				right_page.innerHTML = ' ';
				
				if(condition == 3){
					setTimeout(function(){show_store_data(right_title,right_content,right_page);},1000);
				}else if(condition == 6 || condition == 7){
					setTimeout(function(){show_machine_data(right_title,right_content,right_page);},1000);
				}else if(condition == 5){
					setTimeout(function(){window.location.href = "http://127.0.0.1/client.tree/index.php";},1000);
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
					show_home(begin,end,str_content,total_json);
				}
			
			}
	});
}

//頁碼內操作收入
function show_page_revenue(begin,end,str_content,total_json){
	var auto = '';
	for(var i=begin;i<end;i++){
		total_json[i][4] == '0' ? auto = '自動' : auto = '手動';
		str_content += '<tr>'+
						'<td style = "width:50px">'+total_json[i][2]+'</td>'+
						'<td style = "width:160px">'+total_json[i][3]+'</td>'+
						'<td style = "width:50px">'+auto+'</td>'+
					   '</tr>';
	}
	right_title.innerHTML = '<p class = "revenue_pay">機台編號'+total_json[0][1]+'的當月詳細<span style = "color:blue;">收入</span></p>';
	right_content.innerHTML = '<table border = "1" class = "mar"><tr>'+
								'<td>金額</td>'+
								'<td>新增日期</td>'+
								'<td>動作</td></tr>'+
								str_content+
							  '</table>'
}

//頁碼內操作支出
function show_page_pay(begin,end,str_content,total_json){
	for(var i=begin;i<end;i++){
		str_content += '<tr>'+
						'<td style = "width:50px">'+total_json[i][1]+'</td>'+
						'<td style = "width:160px">'+total_json[i][2]+'</td>'+
						'<td style = "width:160px">'+total_json[i][3]+'</td>'+
						'<td style = "width:160px">'+total_json[i][4]+'</td>'+
						'<td style = "width:160px">'+total_json[i][6]+'</td>'+
						'<td style = "width:160px">'+total_json[i][5]+'</td>'+
					   '</tr>';
	}
	right_title.innerHTML = '<p class = "revenue_pay">機台編號'+total_json[0][0]+'的當月詳細<span style = "color:red;">支出</span></p>';
	right_content.innerHTML = '<table border = "1" class = "mar"><tr>'+
								'<td>商品代碼</td>'+
								'<td>商品名稱</td>'+
								'<td>數量</td>'+
								'<td>單價</td>'+
								'<td>總額</td>'+
								'<td>新增日期</td></tr>'+
								str_content+
							  '</table>';
}

//ajax非同步處理
function ajax(method,url,value,fun){
	
	try{
	  //創建ajax對像
	  xhr = new XMLHttpRequest();
	}catch(e){
	  xhr = new ActiveXObject('Microsoft.XMLHTTP');
	}
	
	//等待ajax回應內容，onreadystatechange狀態值改變的時候觸發函數
	xhr.onreadystatechange = function(){
		//判斷ajax工作狀態值為4表示已經完成內容轉換成我們人可以看得懂的值了，狀態值有0~4
		if(xhr.readyState == 4){
		
		  //判斷HTTP狀態值為200表示請求成功，狀態值有分1~5種開頭的
		  if(xhr.status == 200){
		  
			//顯示responseText屬性儲存的內容，所有資料都存在這個屬性裡面，型態為string
			//假設接收的訊息是json格式，可以用JSON.parse()涵數轉換成陣列型態，如果不支援json可以去官網下載json2引用進來
			//var date = JSON.parse(xhr.responseText);
			//var date = xhr.responseText;
			//alert(xhr.responseText);
			if(fun) fun(xhr.responseText);
			
		  }
		}
	}
	
	if(method == 'get'){
		(value != '')? url += '?'+value : url;
		xhr.open(method,url,'true');
		xhr.send();
	}else{
		//初始設定，參數1為送出方式post或get，參數2為存取檔案得位置，參數3 true為異步 false同步
		//get方式如果有編碼問題可以用encodeURI('要編碼的字')來解決
		xhr.open(method,url,'true');
		//發送數據的類型
		//xhr.setRequestHeader("Content-Type","application/json; charset=utf-8");
		xhr.setRequestHeader('content-type','application/x-www-form-urlencoded');
		//發送出去，如果是post方式在裡面就要輸入值
		xhr.send(value);
	}
	
	
}

