<?php
	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>首頁</title>
<style type="text/css">
 #store tr:nth-child(odd) {background: #FFF}
 #store tr:nth-child(even) {background: #FC9}
	
	table
	{
		margin: 0;
		border-collapse: collapse;
	}
	.container { width: 100%; }
	td, th
	{
		padding: 10px 10px;
		border: 1px solid #999;
		word-break: keep-all;
	}
	.table-container
	{
		width: 100%;
		overflow-y: auto;
		_overflow: auto;
		margin: 0 0 1em;
	}
	
	.table-container::-webkit-scrollbar
	{
		-webkit-appearance: none;
		width: 14px;
		height: 14px;
	}
	
	.table-container::-webkit-scrollbar-thumb
	{
		border-radius: 8px;
		border: 3px solid #fff;
		background-color: rgba(0, 0, 0, .3);
	}
</style>
<script src="fun.js"></script>
</head>

<body>
<div class="container">

<script>
function show_index() {

	//判斷是否為子帳號
    operating_static = 1;
    if(<?php echo @$_SESSION['competence']?> == '3') {
        var url_value = "instruction=Index&account=<?php echo @$_SESSION['father']?>&month=<?php echo date("Y-m");?>";
    } else {
        var url_value = "instruction=Index&account=<?php echo @$_SESSION['account']?>&month=<?php echo date("Y-m");?>";
    }
	
    ajax('post', 'indexphp.php', url_value,
        function fun(value) {
            var total = '';
            var str_content = '';
            var title = '';
            var data = JSON.parse(value);
            var commodity2 = '';

            var all_commodity = Array(); //儲存所有商店的品項代碼(用來計算所有枚重複的品項長度)
            var same = 0; //判斷是否有重複值有為1
            var quantity_arr = Array(); //儲存所有品項的數量
            var total_quantity_arr = Array(); //儲存所有品項的加總數量
            var dd = 0; //計算支出數量
            //對所有品項作處理
			if(<?php echo @$_SESSION['competence']?> == '4'){
				
			}else{
				for(var store_index = 0; store_index < data['commodity'].length; store_index++) {
					for(var commodity_index = 0; commodity_index < data['commodity'][store_index].length; commodity_index++) {
						same = 0;
						/*如果儲存品項陣列為0時，直接儲存品項，不為0另外做判斷重複的處理*/
						if(all_commodity.length != 0) {
							for(var all_index = 0; all_index < all_commodity.length; all_index++) {
								if(all_commodity[all_index] == data['commodity'][store_index][commodity_index][0]) {
									same = 1;
									break;
								}
							}
							//如果沒有重複就為0，直接儲存品項
							if(same == 0) {
								all_commodity.push(data['commodity'][store_index][commodity_index][0]);
								commodity2 += '<td style="background-color: #600;color:#FFF;height:35px;" align="center">' + data['commodity'][store_index][commodity_index][1] + '</td>';
								quantity_arr[data['commodity'][store_index][commodity_index][0]] = 0;
								total_quantity_arr[data['commodity'][store_index][commodity_index][0]] = 0;
							}
						} else {
							all_commodity.push(data['commodity'][store_index][commodity_index][0]); //存品項
							commodity2 += '<td style="background-color: #600;color:#FFF;height:35px;" align="center">' + data['commodity'][store_index][commodity_index][1] + '</td>'; //顯示品項名稱的標籤語言
							quantity_arr[data['commodity'][store_index][commodity_index][0]] = 0; //存品項的數量
							total_quantity_arr[data['commodity'][store_index][commodity_index][0]] = 0; //存品項的加總數量
						}
					}
				}
			}
			
					str_content += '<input type = "button" id = "month" value ="<?php echo date("m")-1?>月份" style="height:30px;width:60px;"><br></br>';
			//判斷遊戲機表格
			if(<?php echo @$_SESSION['competence']?> == '4'){
				//遊戲機基本資料
				for(var i=0;i<data['store_total'].length;i++){
					str_content += '<tr>' +
                    '<td style = "height:30px;">' + data['store_total'][i][0] + '</td>' +
                    '<td>' + data['store_total'][i][1] + '</td>' +
                    '<td>' + data['store_total'][i][2] + '</td>' +
                    '<td>' + data['store_total'][i][3] + '</td>';
					var tt = 0;
					var coin = 0;
					tt += data['store_total'][i][2];   //總收入
					coin += data['store_total'][i][3]; //硬幣總出
				}
			}else{
				//娃娃機基本資料
				for(var i = 0; i < data['store_total'].length; i++) {
					var quantity = '';
					str_content += '<tr>' +
						'<td style = "height:30px;">' + data['store_total'][i][0] + '</td>' +
						'<td>' + data['store_total'][i][1] + '</td>' +
						'<td>' + data['store_total'][i][2] + '</td>' +
						'<td>' + data['store_total'][i][3] + '</td>';
					var detail = 0; //支出數量
					var aa = '';
					var b = '';
					var t = 0;
					var tt = 0;
					var coin = 0;
					var total_q = '';
					for(var a = 0; a < data['store_total'].length; a++) {
						t += data['store_total'][a][3];   //總支出
						tt += data['store_total'][a][2];  //總收入
						coin += data['store_total'][a][4]; //硬幣總出
					}

					for(var a = 0; a < data['store_total'][i][5].length; a++) {
						detail += data['store_total'][i][5][a][2]; //支出數量
						aa += '<td>' + data['store_total'][i][5][a][1] + '</td>';
						b += '<td>' + data['store_total'][i][5][a][2] + '</td>';
						//判斷店家的品項與所有品項是否有相同的，有則改變品項的數量
						for(var quantity_arr_index in quantity_arr) {
							if(quantity_arr_index == data['store_total'][i][5][a][0] && quantity_arr[quantity_arr_index] == 0) {
								quantity_arr[quantity_arr_index] = data['store_total'][i][5][a][2];
								total_quantity_arr[quantity_arr_index] += data['store_total'][i][5][a][2];
							}
						}
					}
					//將品項數量顯示出來，並讓品項數量初始化為0
					for(var quantity_arr_index in quantity_arr) {
						quantity += '<td>' + quantity_arr[quantity_arr_index] + '</td>';  //各個禮品數量
						quantity_arr[quantity_arr_index] = 0;
					}
					dd += detail;  //總支出數量
					if(<?php echo @$_SESSION['competence']?> == '4') {
						
					} else {
						str_content += '<td>' + detail + '</td>' +
							'<td>' + data['store_total'][i][4] + '</td>' + quantity +
							'</tr>';
					}
				}
				//將品項總數量顯示出來
				for(var total_quantity_arr_index in total_quantity_arr) {
					//alert(total_quantity_arr_index+'--'+total_quantity_arr[total_quantity_arr_index]);
					total_q += '<td>' + total_quantity_arr[total_quantity_arr_index] + '</td>';  //各個禮品總數量
				}
			
			}

            
            if(<?php echo @$_SESSION['competence']?> == '4') {
                total = '<tr><td colspan="2" align="center"><strong>合計</strong></td><td>' + tt + '</td>' + '<td>' + coin + '</td>' + '</tr>';
                var store = '<table border ="1" cellpadding="0" cellspacing="0" class = "mar" align="left" id="store" style="border-color:#d0d0d0;width:100%"><tr>' +
                    '<td style="background-color: #600;color:#FFF;height:35px;" align="center"><span>店家編號</span></td>' +
                    '<td style="background-color: #600;color:#FFF;height:35px;" align="center"><span>店家名稱</span></td>' +
                    '<td style="background-color: #600;color:#FFF;height:35px;" align="center"><span><?php echo date("m"); ?>月小計</span></td>' +
                    '<td style="background-color: #600;color:#FFF;height:35px;" align="center"><span>兌幣總出數</span></td>' +
                    '</tr>' +
                    str_content + total +
                    '</table>';
            } else {
				total += '<tr><td colspan="2" align="center"><strong>合計</strong></td><td>' + tt + '</td><td>' + t + '</td><td>' + dd + '</td><td>' + coin + '</td>' + total_q + '</tr>';
                var store = '<table border ="1" cellpadding="0" cellspacing="0" class = "mar" align="left" id="store" style="border-color:#d0d0d0;width:100%"><tr>' +
                    '<td style="background-color: #600;color:#FFF;height:35px;" align="center"><span>店家編號</span></td>' +
                    '<td style="background-color: #600;color:#FFF;height:35px;" align="center"><span>店家名稱</span></td>' +
                    '<td style="background-color: #600;color:#FFF;height:35px;" align="center"><span><?php echo date("m"); ?>月收入</span></td>' +
                    '<td style="background-color: #600;color:#FFF;height:35px;" align="center"><span><?php echo date("m"); ?>月支出</span></td>' +
                    '<td style="background-color: #600;color:#FFF;height:35px;" align="center"><span><?php echo date("m"); ?>月支出數量</span></td>' +
                    '<td style="background-color: #600;color:#FFF;height:35px;" align="center"><span>兌幣總出數</span></td>' +
                    commodity2 +
                    '</tr>' +
                    str_content + total +
                    '</table>';
            }
            title = '<p style="background-color:#F2E85F"><span>' + data['Information'][0] + '</span></p>';
            var ln = '<?php echo $_GET['ln']?>';
            //alert (ln);
            right_content.innerHTML = '<div class="table-container"><div id="div_excel">'  + store +  '</div></div>';
            right_content1.innerHTML = '<br><button type="button" onclick="exportExcel()">匯出Excel</button>';
            right_title.innerHTML = title;
            right_page.innerHTML = '';
            var span = document.getElementsByTagName('span');
			var month =document.getElementById('month');
			
			month.onclick=function(){
				if(<?php echo @$_SESSION['competence']?> == '3') {
					var url_value = "instruction=Index&account=<?php echo @$_SESSION['father']?>&month=<?php echo date("Y-");echo date("m")-1;?>";
				} else {
					var url_value = "instruction=Index&account=<?php echo @$_SESSION['account']?>&month=<?php echo date("Y-");echo date("m")-1;?>";
				}
				ajax('post', 'indexphp.php', url_value,
					function fun(value) {
						var total = '';
						var str_content = '';
						var title = '';
						var data = JSON.parse(value);
						var commodity2 = '';

						var all_commodity = Array(); //儲存所有商店的品項代碼(用來計算所有枚重複的品項長度)
						var same = 0; //判斷是否有重複值有為1
						var quantity_arr = Array(); //儲存所有品項的數量
						var total_quantity_arr = Array(); //儲存所有品項的加總數量
						var dd = 0; //計算支出數量
            //對所有品項作處理
			if(<?php echo @$_SESSION['competence']?> == '4'){
				
			}else{
				for(var store_index = 0; store_index < data['commodity'].length; store_index++) {
					for(var commodity_index = 0; commodity_index < data['commodity'][store_index].length; commodity_index++) {
						same = 0;
						/*如果儲存品項陣列為0時，直接儲存品項，不為0另外做判斷重複的處理*/
						if(all_commodity.length != 0) {
							for(var all_index = 0; all_index < all_commodity.length; all_index++) {
								if(all_commodity[all_index] == data['commodity'][store_index][commodity_index][0]) {
									same = 1;
									break;
								}
							}
							//如果沒有重複就為0，直接儲存品項
							if(same == 0) {
								all_commodity.push(data['commodity'][store_index][commodity_index][0]);
								commodity2 += '<td style="background-color: #600;color:#FFF;height:35px;" align="center">' + data['commodity'][store_index][commodity_index][1] + '</td>';
								quantity_arr[data['commodity'][store_index][commodity_index][0]] = 0;
								total_quantity_arr[data['commodity'][store_index][commodity_index][0]] = 0;
							}
						} else {
							all_commodity.push(data['commodity'][store_index][commodity_index][0]); //存品項
							commodity2 += '<td style="background-color: #600;color:#FFF;height:35px;" align="center">' + data['commodity'][store_index][commodity_index][1] + '</td>'; //顯示品項名稱的標籤語言
							quantity_arr[data['commodity'][store_index][commodity_index][0]] = 0; //存品項的數量
							total_quantity_arr[data['commodity'][store_index][commodity_index][0]] = 0; //存品項的加總數量
						}
					}
				}
			}
					str_content += '<input type = "button" id ="test" value ="當月份" style="height:30px;width:60px;"><br></br>';
			//判斷遊戲機表格
			if(<?php echo @$_SESSION['competence']?> == '4'){
				//遊戲機基本資料
				for(var i=0;i<data['store_total'].length;i++){
					str_content += '<tr>' +
                    '<td style = "height:30px;">' + data['store_total'][i][0] + '</td>' +
                    '<td>' + data['store_total'][i][1] + '</td>' +
                    '<td>' + data['store_total'][i][2] + '</td>' +
                    '<td>' + data['store_total'][i][3] + '</td>';
					var tt = 0;
					var coin = 0;
					tt += data['store_total'][i][2];   //總收入
					coin += data['store_total'][i][3]; //硬幣總出
				}
			}else{
				//娃娃機基本資料
				for(var i = 0; i < data['store_total'].length; i++) {
					var quantity = '';
					str_content += '<tr>' +
						'<td style = "height:30px;">' + data['store_total'][i][0] + '</td>' +
						'<td>' + data['store_total'][i][1] + '</td>' +
						'<td>' + data['store_total'][i][2] + '</td>' +
						'<td>' + data['store_total'][i][3] + '</td>';
					var detail = 0; //支出數量
					var aa = '';
					var b = '';
					var t = 0;
					var tt = 0;
					var coin = 0;
					var total_q = '';
					for(var a = 0; a < data['store_total'].length; a++) {
						t += data['store_total'][a][3];   //總支出
						tt += data['store_total'][a][2];  //總收入
						coin += data['store_total'][a][4]; //硬幣總出
					}

					for(var a = 0; a < data['store_total'][i][5].length; a++) {
						detail += data['store_total'][i][5][a][2]; //支出數量
						aa += '<td>' + data['store_total'][i][5][a][1] + '</td>';
						b += '<td>' + data['store_total'][i][5][a][2] + '</td>';
						//判斷店家的品項與所有品項是否有相同的，有則改變品項的數量
						for(var quantity_arr_index in quantity_arr) {
							if(quantity_arr_index == data['store_total'][i][5][a][0] && quantity_arr[quantity_arr_index] == 0) {
								quantity_arr[quantity_arr_index] = data['store_total'][i][5][a][2];
								total_quantity_arr[quantity_arr_index] += data['store_total'][i][5][a][2];
							}
						}
					}
					//將品項數量顯示出來，並讓品項數量初始化為0
					for(var quantity_arr_index in quantity_arr) {
						quantity += '<td>' + quantity_arr[quantity_arr_index] + '</td>';  //各個禮品數量
						quantity_arr[quantity_arr_index] = 0;
					}
					dd += detail;  //總支出數量
					if(<?php echo @$_SESSION['competence']?> == '4') {
						
					} else {
						str_content += '<td>' + detail + '</td>' +
							'<td>' + data['store_total'][i][4] + '</td>' + quantity +
							'</tr>';
					}
				}
				//將品項總數量顯示出來
				for(var total_quantity_arr_index in total_quantity_arr) {
					//alert(total_quantity_arr_index+'--'+total_quantity_arr[total_quantity_arr_index]);
					total_q += '<td>' + total_quantity_arr[total_quantity_arr_index] + '</td>';  //各個禮品總數量
				}
			
			}
            if(<?php echo @$_SESSION['competence']?> == '4') {
                total = '<tr><td colspan="2" align="center"><strong>合計</strong></td><td>' + tt + '</td>' + '<td>' + coin + '</td>' + '</tr>';
                var store = '<table border ="1" cellpadding="0" cellspacing="0" class = "mar" align="left" id="store" style="border-color:#d0d0d0;width:100%"><tr>' +
                    '<td style="background-color: #600;color:#FFF;height:35px;" align="center"><span>店家編號</span></td>' +
                    '<td style="background-color: #600;color:#FFF;height:35px;" align="center"><span>店家名稱</span></td>' +
                    '<td style="background-color: #600;color:#FFF;height:35px;" align="center"><span><?php echo date("m",strtotime("last month")) ?>月小計</span></td>' +
                    '<td style="background-color: #600;color:#FFF;height:35px;" align="center"><span>兌幣總出數</span></td>' +
                    '</tr>' +
                    str_content + total +
                    '</table>';
            } else {
				total += '<tr><td colspan="2" align="center"><strong>合計</strong></td><td>' + tt + '</td><td>' + t + '</td><td>' + dd + '</td><td>' + coin + '</td>' + total_q + '</tr>';
                var store = '<table border ="1" cellpadding="0" cellspacing="0" class = "mar" align="left" id="store" style="border-color:#d0d0d0;width:100%"><tr>' +
                    '<td style="background-color: #600;color:#FFF;height:35px;" align="center"><span>店家編號</span></td>' +
                    '<td style="background-color: #600;color:#FFF;height:35px;" align="center"><span>店家名稱</span></td>' +
                    '<td style="background-color: #600;color:#FFF;height:35px;" align="center"><span><?php echo date("m",strtotime("last month")) ?>月收入</span></td>' +
                    '<td style="background-color: #600;color:#FFF;height:35px;" align="center"><span><?php echo date("m",strtotime("last month")) ?>月支出</span></td>' +
                    '<td style="background-color: #600;color:#FFF;height:35px;" align="center"><span><?php echo date("m",strtotime("last month")) ?>月支出數量</span></td>' +
                    '<td style="background-color: #600;color:#FFF;height:35px;" align="center"><span>兌幣總出數</span></td>' +
                    commodity2 +
                    '</tr>' +
                    str_content + total +
                    '</table>';
            }
			title = '<p style="background-color:#F2E85F"><span>' + data['Information'][0] + '</span></p>';
            var ln = '<?php echo $_GET['ln']?>';
			var now =document.getElementsByTagName('input');
            //alert (ln);
            right_content.innerHTML = '<div class="table-container"><div id="div_excel">' + store +  '</div></div>';
            right_content1.innerHTML = '<br><button type="button" onclick="exportExcel()">匯出Excel</button>';
            right_title.innerHTML = title;
            right_page.innerHTML = '';
			
			now[0].onclick=function(){
				show_index();
			}
					})

			}
            //語言更換
            if(ln == 'cn') {
                span[0].innerHTML = data['Information'][0];
                span[1].innerHTML = '店家编号';
                span[2].innerHTML = '店家名称';
                span[3].innerHTML = '<?php echo date("m"); ?>月收入金额';
                span[4].innerHTML = '<?php echo date("m"); ?>月支出金额';
                span[5].innerHTML = '<?php echo date("m"); ?>月支出数量';
                span[6].innerHTML = '兑币机总出数';
            } else if(ln == 'en') {
                span[0].innerHTML = data['Information'][0];
                span[1].innerHTML = 'Store No.';
                span[2].innerHTML = 'Store name';
                span[3].innerHTML = '<?php echo date("M"); ?> Income amount';
                span[4].innerHTML = '<?php echo date("M"); ?> Expenditures amount';
                span[5].innerHTML = '<?php echo date("M"); ?> Expenditures quantity';
                span[6].innerHTML = 'Coin change quantity';
            }
        })

}

//匯出Excel
function exportExcel() {

    var html = '<!DOCTYPE html><html><head><meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8" /><title>Excel</title></head>';
    html += '<body>';
    html += document.getElementById('div_excel').innerHTML + '</body></html>';

    window.open('data:application/vnd.ms-excel,' + encodeURIComponent(html));

}
</script>

<div id = "right_title">
	<p class = "mar"></p>
</div>
<div id = "right_content"  style="overflow:auto;">
</div>
<div id = "right_content1" style="overflow:auto;">
</div>
<div id = "right_page">
</div>
<?php echo "<script type='text/javascript'>show_index();</script>";?>
</div>
</body>
</html>
