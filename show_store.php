<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>store</title>

<style>
#mac tr:nth-child(even) {background: #FC9}
#mac tr:nth-child(odd) {background: #FFF}
#cm tr:nth-child(even) {background: #FC9}
#cm tr:nth-child(odd) {background: #FFF}

</style>
<script src="fun.js"></script>
</head>

<body>

<?php
	session_start();
?>
<script type="text/javascript">
	function show_store(){
	
	var right_title = document.getElementById("right_title");
	var right_content = document.getElementById('right_content');
	var right_content1 = document.getElementById('right_content1');
	var right_page = document.getElementById('right_page');
	var url_value = "instruction=IndexStore&store_number=<?php echo $_GET['store_number']?>&month=<?php echo date("Y-m")?>;";
	
	ajax('post','indexphp.php',url_value,
		function fun(value){
		var total='';
		var str_content='';
		var str_cm='';
		var data = JSON.parse(value);
		var str = '';
		var ln='<?php echo $_GET['ln']?>';
		var commodity2='';
		var on_off = Array(); //控制開關(控制此品項是否已配對成功，成功則為true)
		var total_quantity_arr = Array(); 	//儲存所有品項的加總數量
		
		for(var total_index=0;total_index < data['commodity'].length;total_index++){
			commodity2 += '<td style ="color:#FFF;width:90px;" align="center">'+data['commodity'][total_index][1]+'</td>';
			total_quantity_arr[data['commodity'][total_index][0]] = 0;
			on_off.push(false);
			//alert(data['commodity'][total_index][0]);
		}
		
		var t=0;
		var tt=0;
		var m=0;
		
		if(<?php echo @$_SESSION['competence']?> =='4'){
		//店家基本資料(遊戲機)
		for(var i = 0;i<data['machine_total'].length;i++){
			var money=0;
			var aa=0; //Key coin總數
			var bb=0; //投幣總數
			var cc=0; //Key out總數
			var dd=0; //退幣總數
			if(data['machine_total'][i][2]=='已出租'){
				if(ln=='cn'){
					money ='已出租';
				}else if(ln=='en'){
					money ='Leased';
					data['machine_total'][i][2]='Leased';
				}else{
					money ='已出租';
				}
			}
			
			//alert('machine_total:'+data['machine_total'][i]+'\n');
			if(data['machine_total'][i]!=''){
			if(data['machine_total'][i][0].substr(0,2)!="CM"){
				str_content += '<tr>'+
				'<td  style = "height:30px;">'+data['machine_total'][i][0]+'</td>'+
				'<td>'+data['machine_total'][i][1]+'</td>';
				
				for(var a=0;a<data['machine_total'][i][2].length;a++){
					str_content +='<td>'+data['machine_total'][i][2][a]+'</td>';
				}
				
				aa +=data['machine_total'][i][2][0];
				bb +=data['machine_total'][i][2][1];
				cc +=data['machine_total'][i][2][2];
				dd +=data['machine_total'][i][2][3];
				
			}else{
				str_cm += '<tr>'+
				'<td style = "width:80px;height:32px;"><span style="font-size:18px">'+data['machine_total'][i][0]+'</span></td>'+
				'<td>'+data['machine_total'][i][1]+'</td>'+
				'<td>'+data['machine_total'][i][2]+'</td>'+
				'</tr>';
			}}
		}
		}else{
		for(var b = 0;b<data['machine_total'].length;b++){
			if(data['machine_total'][b]!=''){
			if(data['machine_total'][b][0].substr(0,2)!="CM"){
				if(data['machine_total'][b][2]!='已出租'){
					t+=data['machine_total'][b][2];
					for(var k=0;k<data['machine_total'][b][3].length;k++){
						tt+=data['machine_total'][b][3][k][3];
						m+=data['machine_total'][b][3][k][2];
					}
				}
			}
			}
		}
		
		//店家基本資料
		for(var i = 0;i<data['machine_total'].length;i++){
			var money=0;
			var commodity=0;
			var commodity1='';
			var quantity='';
			var quantity_arr = Array();
			var aa='';
			var sign='';
			if(data['machine_total'][i][2]=='已出租'){
				if(ln=='cn'){
					money ='已出租';
				}else if(ln=='en'){
					money ='Leased';
					data['machine_total'][i][2]='Leased';
				}else{
					money ='已出租';
				}
			}
			
			//alert('machine_total:'+data['machine_total'][i]+'\n');
			if(data['machine_total'][i]!=''){
			if(data['machine_total'][i][0].substr(0,2)!="CM"){
			str_content += '<tr>'+
			'<td  style = "height:30px;">'+data['machine_total'][i][0]+'</td>'+
			'<td>'+data['machine_total'][i][1]+'</td>'+
			'<td align="center">'+data['machine_total'][i][2]+'</td>';
			
			for(a=0;a<data['machine_total'][i][3].length;a++){
				money+=data['machine_total'][i][3][a][3];
				commodity += data['machine_total'][i][3][a][2];
				//commodity1+='<td>'+data['machine_total'][i][3][a][1]+'</td>';
				/*將有數量的品項與所有品項做配對
				  當配對成功時，控制開關為false則表此品項未前一次配對過，控制開關為true則表示已經配對過了*/
				//alert('machine'+data['machine_total'][i][3]);
				for(var total_index=0;total_index < data['commodity'].length;total_index++){
					if(data['commodity'][total_index][0] == data['machine_total'][i][3][a][0] && !on_off[total_index]){
						on_off[total_index] = true;											//控制開關開啟
						quantity_arr[total_index] = data['machine_total'][i][3][a][2];		//紀錄數量
						total_quantity_arr[data['commodity'][total_index][0]] += data['machine_total'][i][3][a][2];
					}else{
						if(!on_off[total_index]) quantity_arr[total_index] = 0;
					}
				}
			}
				
				/*將控制開關為true顯示數量並將其開關設回false給下一台機台用，其餘則顯示0*/
				for(var total_index=0;total_index < data['commodity'].length;total_index++){
					if(on_off[total_index]){
						quantity +='<td align="center">'+quantity_arr[total_index]+'</td>';
						on_off[total_index] = false;
					}else{
						quantity +='<td align="center">0</td>';
					}
				}
				if(<?php echo @$_SESSION['competence']?> =='4'){
					str_content+='<td align="center">'+money+'</td>'+
								 '</tr>';
				}else{
					str_content+='<td align="center">'+money+'</td>'+
					'<td align="center">'+commodity+'</td>'+quantity+
					'</tr>';
				}
			}else{
				str_cm += '<tr>'+
				'<td style = "width:80px;height:32px;"><span style="font-size:18px">'+data['machine_total'][i][0]+'</span></td>'+
				'<td>'+data['machine_total'][i][1]+'</td>'+
				'<td>'+data['machine_total'][i][2]+'</td>'+
				'</tr>';
			}}
		}
		for(var total_quantity_arr_index in total_quantity_arr){
		//alert(total_quantity_arr_index+'--'+total_quantity_arr[total_quantity_arr_index]);
			aa+='<td align="center">'+total_quantity_arr[total_quantity_arr_index]+'</td>';
		}
		}

	
	if(<?php echo @$_SESSION['competence']?> =='4'){
		total ='<tr><td colspan="2" align="center"><strong>合計</strong></td><td align="center">'+aa+'</td><td align="center">'+bb+'</td><td align="center">'+cc+'</td><td align="center">'+dd+'</td><td></td></tr>';
		//遊戲機台
	var store = '<table border ="1" cellpadding="0" cellspacing="0" class = "mar" align="left" id="mac" style="border-color:#d0d0d0"><tr style ="background-color:#600;">'+
					'<td style ="color:#FFF;width:110px;height:35px;" align="center"><a>機台編號</a></td>'+
					'<td style ="color:#FFF;width:150px;" align="center"><a>機台名稱</a></td>'+
					'<td style ="color:#FFF;width:120px;" align="center"><a><?php echo date("m"); ?>月投幣</a></td>'+
					'<td style ="color:#FFF;width:120px;" align="center"><a><?php echo date("m"); ?>月Key coin</a></td>'+
					'<td style ="color:#FFF;width:120px;" align="center"><a><?php echo date("m"); ?>月退幣</a></td>'+
					'<td style ="color:#FFF;width:120px;" align="center"><a><?php echo date("m"); ?>月Key out</a></td>'+
					'<td style ="color:#FFF;width:120px;" align="center"><a>分成比</a></td>'+
					'</tr>'+str_content+total+
				'</table>';
	}else{
		total+='<tr><td colspan="2" align="center"><strong>合計</strong></td><td align="center">'+t+'</td><td align="center">'+tt+'</td><td align="center">'+m+'</td>'+aa+'</tr>';
		//娃娃機台
	var store = '<table border ="1" cellpadding="0" cellspacing="0" class = "mar" align="left" id="mac" style="border-color:#d0d0d0"><tr style ="background-color:#600;">'+
					'<td style ="color:#FFF;width:110px;height:35px;" align="center"><a>機台編號</a></td>'+
					'<td style ="color:#FFF;width:150px;" align="center"><a>機台名稱</a></td>'+
					'<td style ="color:#FFF;width:120px;" align="center"><a><?php echo date("m"); ?>月收入</a></td>'+
					'<td style ="color:#FFF;width:120px;" align="center"><a><?php echo date("m"); ?>月支出</a></td>'+
					'<td style ="color:#FFF;width:130px;" align="center"><a><?php echo date("m"); ?>月支出數量</a></td>'+
					commodity2+'</tr>'+str_content+total+
				'</table>';
	}
		//兌幣機台
	var cm = '<table border ="1" cellpadding="0" cellspacing="0" class = "mar" align="left" id="cm" style="border-color:#d0d0d0"><tr>'+
				'<td style ="background-color:#600;color:#FFF;width:140px;height:35px;" align="center"><a>兌幣機編號</a></td>'+
				'<td style ="background-color:#600;color:#FFF;width:140px;" align="center"><a>兌幣機名稱</a></td>'+
				'<td style ="background-color:#600;color:#FFF;width:140px;" align="center"><a><?php echo date("m");?>月總兌量</a></td>'+
				'</tr>'+str_cm+
			'</table>';
			var title = '<p style="background-color:#F2E85F"><a>'+data['Information'][1]+'</a></p>';
			right_content.innerHTML = '<div id="div_excel"><input type = "button" id = "month" value ="<?php echo date("m")-1?>月份" style="height:30px;width:60px;"><br></br>'+store+'</div>';
			right_content1.innerHTML = '<br>'+cm+'</br>';
			right_title.innerHTML = title;
			right_page.innerHTML = '';
			var a = document.getElementsByTagName('a');
			var up_del_input = document.getElementsByTagName('input');
			var month =document.getElementById('month');
			
			month.onclick=function(){
				var url_value = "instruction=IndexStore&store_number=<?php echo $_GET['store_number']?>&month=<?php echo date("Y-");echo date("m")-1?>;";
				ajax('post','indexphp.php',url_value,
					function fun(value){
						var total='';
						var str_content='';
						var str_cm='';
						var data = JSON.parse(value);
						var str = '';
						var ln='<?php echo $_GET['ln']?>';
						var commodity2='';
						var on_off = Array(); //控制開關(控制此品項是否已配對成功，成功則為true)
						var total_quantity_arr = Array(); 	//儲存所有品項的加總數量
						for(var total_index=0;total_index < data['commodity'].length;total_index++){
							commodity2 += '<td style ="color:#FFF;width:90px;" align="center">'+data['commodity'][total_index][1]+'</td>';
							total_quantity_arr[data['commodity'][total_index][0]] = 0;
							on_off.push(false);
							//alert(data['commodity'][total_index][0]);
						}
						
						var t=0;
						var tt=0;
						var m=0;
						
						if(<?php echo @$_SESSION['competence']?> =='4'){
						//店家基本資料(遊戲機)
						for(var i = 0;i<data['machine_total'].length;i++){
							var money=0;
							var aa=0; //Key coin總數
							var bb=0; //投幣總數
							var cc=0; //Key out總數
							var dd=0; //退幣總數
							if(data['machine_total'][i][2]=='已出租'){
								if(ln=='cn'){
									money ='已出租';
								}else if(ln=='en'){
									money ='Leased';
									data['machine_total'][i][2]='Leased';
								}else{
									money ='已出租';
								}
							}
							
							//alert('machine_total:'+data['machine_total'][i]+'\n');
							if(data['machine_total'][i]!=''){
							if(data['machine_total'][i][0].substr(0,2)!="CM"){
								str_content += '<tr>'+
								'<td  style = "height:30px;">'+data['machine_total'][i][0]+'</td>'+
								'<td>'+data['machine_total'][i][1]+'</td>';
								
								for(var a=0;a<data['machine_total'][i][2].length;a++){
									str_content +='<td>'+data['machine_total'][i][2][a]+'</td>';
								}
				
								aa +=data['machine_total'][i][2][0];
								bb +=data['machine_total'][i][2][1];
								cc +=data['machine_total'][i][2][2];
								dd +=data['machine_total'][i][2][3];
								
							}else{
								str_cm += '<tr>'+
								'<td style = "width:80px;height:32px;"><span style="font-size:18px">'+data['machine_total'][i][0]+'</span></td>'+
								'<td>'+data['machine_total'][i][1]+'</td>'+
								'<td>'+data['machine_total'][i][2]+'</td>'+
								'</tr>';
							}}
						}
						}else{
						for(var b = 0;b<data['machine_total'].length;b++){
							if(data['machine_total'][b]!=''){
							if(data['machine_total'][b][0].substr(0,2)!="CM"){
								if(data['machine_total'][b][2]!='已出租'){
									t+=data['machine_total'][b][2];
									for(var k=0;k<data['machine_total'][b][3].length;k++){
										tt+=data['machine_total'][b][3][k][3];
										m+=data['machine_total'][b][3][k][2];
										t+=data['machine_total'][b][3][k][3];
									}
								}
							}
							}
						}
						
						//店家基本資料
						for(var i = 0;i<data['machine_total'].length;i++){
							var money=0;
							var commodity=0;
							var commodity1='';
							var quantity='';
							var quantity_arr = Array();
							var aa='';
							var sign='';
							if(data['machine_total'][i][2]=='已出租'){
								if(ln=='cn'){
									money ='已出租';
								}else if(ln=='en'){
									money ='Leased';
									data['machine_total'][i][2]='Leased';
								}else{
									money ='已出租';
								}
							}
							
							//alert('machine_total:'+data['machine_total'][i]+'\n');
							if(data['machine_total'][i]!=''){
							if(data['machine_total'][i][0].substr(0,2)!="CM"){
							str_content += '<tr>'+
							'<td  style = "height:30px;">'+data['machine_total'][i][0]+'</td>'+
							'<td>'+data['machine_total'][i][1]+'</td>'+
							'<td align="center">'+data['machine_total'][i][2]+'</td>';
							
							for(a=0;a<data['machine_total'][i][3].length;a++){
								money+=data['machine_total'][i][3][a][3];
								commodity += data['machine_total'][i][3][a][2];
								//commodity1+='<td>'+data['machine_total'][i][3][a][1]+'</td>';
								/*將有數量的品項與所有品項做配對
								當配對成功時，控制開關為false則表此品項未前一次配對過，控制開關為true則表示已經配對過了*/
								//alert('machine'+data['machine_total'][i][3]);
								for(var total_index=0;total_index < data['commodity'].length;total_index++){
									if(data['commodity'][total_index][0] == data['machine_total'][i][3][a][0] && !on_off[total_index]){
										on_off[total_index] = true;											//控制開關開啟
										quantity_arr[total_index] = data['machine_total'][i][3][a][2];		//紀錄數量
										total_quantity_arr[data['commodity'][total_index][0]] += data['machine_total'][i][3][a][2];
									}else{
										if(!on_off[total_index]) quantity_arr[total_index] = 0;
									}
								}
							}
								
								/*將控制開關為true顯示數量並將其開關設回false給下一台機台用，其餘則顯示0*/
								for(var total_index=0;total_index < data['commodity'].length;total_index++){
									if(on_off[total_index]){
										quantity +='<td align="center">'+quantity_arr[total_index]+'</td>';
										on_off[total_index] = false;
									}else{
										quantity +='<td align="center">0</td>';
									}
								}
								if(<?php echo @$_SESSION['competence']?> =='4'){
									str_content+='<td align="center">'+money+'</td>'+
												'</tr>';
								}else{
									str_content+='<td align="center">'+money+'</td>'+
									'<td align="center">'+commodity+'</td>'+quantity+
									'</tr>';
								}
							}else{
								str_cm += '<tr>'+
								'<td style = "width:80px;height:32px;"><span style="font-size:18px">'+data['machine_total'][i][0]+'</span></td>'+
								'<td>'+data['machine_total'][i][1]+'</td>'+
								'<td>'+data['machine_total'][i][2]+'</td>'+
								'</tr>';
							}}
						}
						for(var total_quantity_arr_index in total_quantity_arr){
						//alert(total_quantity_arr_index+'--'+total_quantity_arr[total_quantity_arr_index]);
							aa+='<td align="center">'+total_quantity_arr[total_quantity_arr_index]+'</td>';
						}
						}
					if(<?php echo @$_SESSION['competence']?> =='4'){
						total ='<tr><td colspan="2" align="center"><strong>合計</strong></td><td align="center">'+aa+'</td><td align="center">'+bb+'</td><td align="center">'+cc+'</td><td align="center">'+dd+'</td><td></td></tr>';
						//遊戲機台
					var store = '<table border ="1" cellpadding="0" cellspacing="0" class = "mar" align="left" id="mac" style="border-color:#d0d0d0"><tr style ="background-color:#600;">'+
						'<td style ="color:#FFF;width:110px;height:35px;" align="center"><a>機台編號</a></td>'+
						'<td style ="color:#FFF;width:150px;" align="center"><a>機台名稱</a></td>'+
						'<td style ="color:#FFF;width:120px;" align="center"><a><?php echo date("m"); ?>月投幣</a></td>'+
						'<td style ="color:#FFF;width:120px;" align="center"><a><?php echo date("m"); ?>月Key coin</a></td>'+
						'<td style ="color:#FFF;width:120px;" align="center"><a><?php echo date("m"); ?>月退幣</a></td>'+
						'<td style ="color:#FFF;width:120px;" align="center"><a><?php echo date("m"); ?>月Key out</a></td>'+
						'<td style ="color:#FFF;width:120px;" align="center"><a>分成比</a></td>'+
						'</tr>'+str_content+total+
							'</table>';
					}else{
						total+='<tr><td colspan="2" align="center"><strong>合計</strong></td><td align="center">'+t+'</td><td align="center">'+tt+'</td><td align="center">'+m+'</td>'+aa+'</tr>';
						//娃娃機台
					var store = '<table border ="1" cellpadding="0" cellspacing="0" class = "mar" align="left" id="mac" style="border-color:#d0d0d0"><tr style ="background-color:#600;">'+
						'<td style ="color:#FFF;width:110px;height:35px;" align="center"><a>機台編號</a></td>'+
						'<td style ="color:#FFF;width:150px;" align="center"><a>機台名稱</a></td>'+
						'<td style ="color:#FFF;width:120px;" align="center"><a><?php echo date("m"); ?>月收入</a></td>'+
						'<td style ="color:#FFF;width:120px;" align="center"><a><?php echo date("m"); ?>月支出</a></td>'+
						'<td style ="color:#FFF;width:130px;" align="center"><a><?php echo date("m"); ?>月支出數量</a></td>'+
						commodity2+'</tr>'+str_content+total+
							'</table>';
					}
					var cm = '<table border ="1" cellpadding="0" cellspacing="0" class = "mar" align="left" id="cm" style="border-color:#d0d0d0"><tr>'+
								'<td style ="background-color:#600;color:#FFF;width:140px;height:35px;" align="center"><a>兌幣機編號</a></td>'+
								'<td style ="background-color:#600;color:#FFF;width:140px;" align="center"><a>兌幣機名稱</a></td>'+
								'<td style ="background-color:#600;color:#FFF;width:140px;" align="center"><a><?php echo date("m");?>月總兌量</a></td>'+
								'</tr>'+str_cm+
							 '</table>';
							var title = '<p style="background-color:#F2E85F"><a>'+data['Information'][1]+'</a></p>';
							right_content.innerHTML = '<div id="div_excel"><input type = "button" id = "month" value ="當月份" style="height:30px;width:60px;"><br></br>'+store+'</div>';
							right_content1.innerHTML = '<br>'+cm+'</br>';
							right_title.innerHTML = title;
							right_page.innerHTML = '';
							var up_del_input = document.getElementsByTagName('input');
							var month = document.getElementById('month');
							
							month.onclick=function(){
								show_store();
							}
					})
			}
			
			if(ln=='cn'){
				a[0].innerHTML= data['Information'][1];
				a[1].innerHTML= '机台编号';
				a[2].innerHTML= '机台名称';
				a[3].innerHTML= '<?php echo date("m"); ?>月收入金额';
				a[4].innerHTML= '<?php echo date("m"); ?>月支出金额';
				a[5].innerHTML= '<?php echo date("m"); ?>月支出数量';
				a[6].innerHTML= '兑币机编号';
				a[7].innerHTML= '兑币机名称';
				a[8].innerHTML= '<?php echo date("m"); ?>月总兑量';
			}else if(ln=='en'){
				a[0].innerHTML= data['Information'][1];
				a[1].innerHTML= 'Machine No.';
				a[2].innerHTML= 'Machine name';
				a[3].innerHTML= '<?php echo date("M"); ?> Income amount';
				a[4].innerHTML= '<?php echo date("M"); ?> Expenditures amount';
				a[5].innerHTML= '<?php echo date("M"); ?> Expenditures quantity';
				a[6].innerHTML= 'Coin change No.';
				a[7].innerHTML= 'Coin change name';
				a[8].innerHTML= 'Coin change quantity';
			}
	});
	
}

</script>

<div id = "right_title">
	<p class = "mar"></p>
</div>
<div id = "right_content" style="overflow:auto">
	
</div>
<div id = "right_content1" style="overflow:auto">
	
</div>
<div id = "right_content2" style="overflow:auto">
	
</div>
<div id = "right_page">
	
</div>
<?php echo "<script type='text/javascript'>show_store();</script>";?>
</body>
</html>
