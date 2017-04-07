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
	
	var ln ='<?php echo $_GET['ln']?>';
	
	var right_title = document.getElementById("right_title");
	var right_content = document.getElementById('right_content');
	var right_page = document.getElementById('right_page');
	var url_value = "instruction=IndexMachine&machine_number=<?php echo $_GET['machine_number']?>";
	var table='';
	var table_cn='';
	var table_en='';
	
	ajax('post','indexphp.php',url_value,
		function fun(value){
		var data = JSON.parse(value);
		var count_in='';
		var count_out=0;
		var str_content='';
		var str_content_cn='';
		var str_content_en='';
		var acc_content='';
		var acc_content_cn='';
		var acc_content_en='';
		var count_content='';
		var count_content_cn='';
		var count_content_en='';
		var preset_content='';
		var preset_content_cn='';
		var preset_content_en='';
		var CM_CB='';
		if(data['Information'][0]==''){data['Information'][0]='無';}
		
		//機台基本資料 CM=兌幣機 不是CM=娃娃機  competence=4 等於遊戲機
		if(data['Information'][0].substr(0,2)!="CM"){
			if(<?php echo @$_SESSION['competence']?>=='4'){
				str_content += '<tr>'+
						'<td style ="background-color:#D4C2AB;height:32px;">'+data['Information'][0]+'</td>'+
						'<td>'+data['Information'][1]+'</td>'+
						'<td style ="background-color:#D4C2AB;height:32px;">'+data['Information'][2]+'</td>'+
						'<td>'+data['Information'][3]+'</td>'+
						'<td style ="background-color:#D4C2AB;height:32px;">'+data['Information'][4]+'</td>'+
						'<td>'+data['Information'][5]+'</td>'+
						'<td style ="background-color:#D4C2AB;height:32px;">'+data['Information'][10]+'</td>'+
						'<td style ="background-color:#D4C2AB;"><input type = "button" name = "update" value ="修改" style="height:32px;width:60px;"></td>'+
						'</tr>';
				var str = '<table border ="1" cellpadding="0" cellspacing="0" class = "mar" align="left" style="border-color:#d0d0d0"><tr>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:120px;height:35px;" align="center">機台編號</td>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:120px;" align="center">機台名稱</td>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:120px;" align="center">投幣</td>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:120px;" align="center">退幣</td>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:120px;" align="center">Key coin</td>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:120px;" align="center">Key out</td>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:120px;" align="center">分成比</td>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:60px;" align="center">修改</td>'+
						str_content+
					'</table>';
			}else{
				str_content += '<tr>'+
						'<td style ="background-color:#D4C2AB;height:32px;">'+data['Information'][0]+'</td>'+
						'<td>'+data['Information'][1]+'</td>'+
						'<td style ="background-color:#D4C2AB;height:32px;">'+data['Information'][2]+'</td>'+
						'<td>'+data['Information'][3]+'</td>'+
						'<td style ="background-color:#D4C2AB;"><input type = "button" name = "update" value ="修改" style="height:32px;width:60px;"></td>'+
						'</tr>';
				var str = '<table border ="1" cellpadding="0" cellspacing="0" class = "mar" align="left" style="border-color:#d0d0d0"><tr>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:120px;height:35px;" align="center">機台編號</td>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:120px;" align="center">機台名稱</td>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:120px;height:35px;" align="center">預設值(入)</td>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:120px;" align="center">預設值(出)</td>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:60px;" align="center">修改</td>'+
						str_content+
					'</table>';
			}
		
		str_content_cn += '<tr>'+
						'<td style ="background-color:#D4C2AB;height:32px;">'+data['Information'][0]+'</td>'+
						'<td>'+data['Information'][1]+'</td>'+
						'<td style ="background-color:#D4C2AB;height:32px;">'+data['Information'][2]+'</td>'+
						'<td>'+data['Information'][3]+'</td>'+
						'<td style ="background-color:#D4C2AB;"><input type = "button" name = "update" value ="修改" style="height:32px;width:60px;"></td>'+
						'</tr>';
		var str_cn = '<table border ="1" cellpadding="0" cellspacing="0" class = "mar" align="left" style="border-color:#d0d0d0"><tr>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:120px;height:35px;" align="center">机台编号</td>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:120px;" align="center">机台名称</td>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:120px;height:35px;" align="center">预设值(入)</td>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:120px;" align="center">预设值(出)</td>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:60px;" align="center">修改</td>'+
						str_content_cn+
					'</table>';
		str_content_en += '<tr>'+
						'<td style ="background-color:#D4C2AB;height:32px;">'+data['Information'][0]+'</td>'+
						'<td>'+data['Information'][1]+'</td>'+
						'<td style ="background-color:#D4C2AB;height:32px;">'+data['Information'][2]+'</td>'+
						'<td>'+data['Information'][3]+'</td>'+
						'<td style ="background-color:#D4C2AB;"><input type = "button" name = "update" value ="Edit" style="height:32px;width:60px;"></td>'+
						'</tr>';
		var str_en = '<table border ="1" cellpadding="0" cellspacing="0" class = "mar" align="left" style="border-color:#d0d0d0"><tr>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:120px;height:35px;" align="center">Machine number</td>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:120px;" align="center">Machine name</td>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:120px;height:35px;" align="center">Default value(in)</td>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:120px;" align="center">Default value(out)</td>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:60px;" align="center">Edit</td>'+
						str_content_en+
					'</table>';
		}else{
			str_content += '<tr>'+
						'<td style ="background-color:#D4C2AB;height:32px;">'+data['Information'][0]+'</td>'+
						'<td>'+data['Information'][1]+'</td>'+
						'<td style ="background-color:#D4C2AB;height:32px;">'+data['Information'][2]+'</td>'+
						'<td>'+data['Information'][3]+'</td>'+
						'<td style ="background-color:#D4C2AB;"><input type = "button" name = "update" value ="修改" style="height:32px;width:60px;"></td>'+
						'</tr>';
		var str = '<table border ="1" cellpadding="0" cellspacing="0" class = "mar" align="left" style="border-color:#d0d0d0"><tr>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:120px;height:35px;" align="center">機台編號</td>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:120px;" align="center">機台名稱</td>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:120px;height:35px;" align="center">紙鈔(出)</td>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:120px;height:35px;" align="center">硬幣(出)</td>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:60px;" align="center">修改</td>'+
						str_content+
					'</table>';
			str_content_cn += '<tr>'+
						'<td style ="background-color:#D4C2AB;height:32px;">'+data['Information'][0]+'</td>'+
						'<td>'+data['Information'][1]+'</td>'+
						'<td style ="background-color:#D4C2AB;height:32px;">'+data['Information'][2]+'</td>'+
						'<td>'+data['Information'][3]+'</td>'+
						'<td style ="background-color:#D4C2AB;"><input type = "button" name = "update" value ="修改" style="height:32px;width:60px;"></td>'+
						'</tr>';
		var str_cn = '<table border ="1" cellpadding="0" cellspacing="0" class = "mar" align="left" style="border-color:#d0d0d0"><tr>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:120px;height:35px;" align="center">机台编号</td>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:120px;" align="center">机台名称</td>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:120px;height:35px;" align="center">纸钞(出)</td>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:120px;height:35px;" align="center">硬币(出)</td>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:60px;" align="center">修改</td>'+
						str_content_cn+
					'</table>';
			str_content_en += '<tr>'+
						'<td style ="background-color:#D4C2AB;height:32px;">'+data['Information'][0]+'</td>'+
						'<td>'+data['Information'][1]+'</td>'+
						'<td style ="background-color:#D4C2AB;height:32px;">'+data['Information'][2]+'</td>'+
						'<td>'+data['Information'][3]+'</td>'+
						'<td style ="background-color:#D4C2AB;"><input type = "button" name = "update" value ="Edit" style="height:32px;width:60px;"></td>'+
						'</tr>';
		var str_en = '<table border ="1" cellpadding="0" cellspacing="0" class = "mar" align="left" style="border-color:#d0d0d0"><tr>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:120px;height:35px;" align="center">Machine number</td>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:120px;" align="center">Machine name</td>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:120px;height:35px;" align="center">Banknote(out)</td>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:120px;height:35px;" align="center">Coin(out)</td>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:60px;" align="center">Edit</td>'+
						str_content_en+
					'</table>';
		}
					count_in=parseInt(data['Information'][2])+parseInt(data['Information'][4]);
					
		//計數器  competence=4 等於遊戲機
		if(data['Information'][0].substr(0,2)!="CM"){
			count_out=parseInt(data['Information'][3])+parseInt(data['Information'][5]);
			
			if(<?php echo @$_SESSION['competence']?>=='4'){
				count_in=parseInt(data['Information'][2])+parseInt(data['Information'][6]);
				count_out=parseInt(data['Information'][3])+parseInt(data['Information'][7]);
				key_in=parseInt(data['Information'][4])+parseInt(data['Information'][8]);
				key_out=parseInt(data['Information'][5])+parseInt(data['Information'][9]);
				count_content += '<tr>'+
					'<td style ="background-color:#DEA1A4;height:32px;">'+count_in+'</td>'+
						'<td>'+count_out+'</td>'+
						'<td>'+key_in+'</td>'+
						'<td>'+key_out+'</td>'+
						'</tr>';
				var count = '<table border ="1" cellpadding="0" cellspacing="0" class = "mar" align="left" style="border-color:#d0d0d0"><tr>'+
						'<td style ="background-color:#b53f45;color:#FFF;width:120px;height:35px;" align="center">投幣</td>'+
						'<td style ="background-color:#b53f45;color:#FFF;width:120px;" align="center">退幣</td>'+
						'<td style ="background-color:#b53f45;color:#FFF;width:120px;" align="center">Key coin</td>'+
						'<td style ="background-color:#b53f45;color:#FFF;width:120px;" align="center">Key out</td>'+
						count_content+
					'</table>';
			}else{
				count_content += '<tr>'+
					'<td style ="background-color:#DEA1A4;height:32px;">'+count_in+'</td>'+
						'<td>'+count_out+'</td>'+
						'</tr>';
				var count = '<table border ="1" cellpadding="0" cellspacing="0" class = "mar" align="left" style="border-color:#d0d0d0"><tr>'+
						'<td style ="background-color:#b53f45;color:#FFF;width:120px;height:35px;" align="center">目前計數器(入)</td>'+
						'<td style ="background-color:#b53f45;color:#FFF;width:120px;" align="center">目前計數器(出)</td>'+
						count_content+
					'</table>';
			}
		
		count_content_cn += '<tr>'+
						'<td style ="background-color:#DEA1A4;height:32px;">'+count_in+'</td>'+
						'<td>'+count_out+'</td>'+
						'</tr>';
		var count_cn = '<table border ="1" cellpadding="0" cellspacing="0" class = "mar" align="left" style="border-color:#d0d0d0"><tr>'+
						'<td style ="background-color:#b53f45;color:#FFF;width:120px;height:35px;" align="center">目前计数器(入)</td>'+
						'<td style ="background-color:#b53f45;color:#FFF;width:120px;" align="center">目前计数器(出)</td>'+
						count_content_cn+
					'</table>';
		count_content_en += '<tr>'+
						'<td style ="background-color:#DEA1A4;height:32px;">'+count_in+'</td>'+
						'<td>'+count_out+'</td>'+
						'</tr>';
		var count_en = '<table border ="1" cellpadding="0" cellspacing="0" class = "mar" align="left" style="border-color:#d0d0d0"><tr>'+
						'<td style ="background-color:#b53f45;color:#FFF;width:120px;height:35px;" align="center">Counter(in)</td>'+
						'<td style ="background-color:#b53f45;color:#FFF;width:120px;" align="center">Counter(out)</td>'+
						count_content_en+
					'</table>';
		}else{
			count_out=parseInt(data['Information'][3])+parseInt(data['Information'][5]);
			count_content += '<tr>'+
						'<td style ="background-color:#DEA1A4;height:32px;">'+count_in+'</td>'+
						'<td>'+count_out+'</td>'+
						'</tr>';
		var count = '<table border ="1" cellpadding="0" cellspacing="0" class = "mar" align="left" style="border-color:#d0d0d0"><tr>'+
						'<td style ="background-color:#b53f45;color:#FFF;width:120px;height:35px;" align="center">紙鈔(出)</td>'+
						'<td style ="background-color:#b53f45;color:#FFF;width:120px;height:35px;" align="center">硬幣(出)</td>'+
						count_content+
					'</table>';
			count_content_cn += '<tr>'+
						'<td style ="background-color:#DEA1A4;height:32px;">'+count_in+'</td>'+
						'<td>'+count_out+'</td>'+
						'</tr>';
		var count_cn = '<table border ="1" cellpadding="0" cellspacing="0" class = "mar" align="left" style="border-color:#d0d0d0"><tr>'+
						'<td style ="background-color:#b53f45;color:#FFF;width:120px;height:35px;" align="center">纸钞(出)</td>'+
						'<td style ="background-color:#b53f45;color:#FFF;width:120px;height:35px;" align="center">硬币(出)</td>'+
						count_content_cn+
					'</table>';
			count_content_en += '<tr>'+
						'<td style ="background-color:#DEA1A4;height:32px;">'+count_in+'</td>'+
						'<td>'+count_out+'</td>'+
						'</tr>';
		var count_en = '<table border ="1" cellpadding="0" cellspacing="0" class = "mar" align="left" style="border-color:#d0d0d0"><tr>'+
						'<td style ="background-color:#b53f45;color:#FFF;width:120px;height:35px;" align="center">Banknote(out)</td>'+
						'<td style ="background-color:#b53f45;color:#FFF;width:120px;height:35px;" align="center">Coin(out)</td>'+
						count_content_en+
					'</table>';
		}
		acc_content	+= '<tr>'+
					'<td style ="background-color:#BFC7DF;height:32px;" align="center">';
			if(data['Account']==''){
				acc_content += '無';
			}else{
				acc_content += data['Account'];
			}
					acc_content +='</td>';
					
					acc_content+='<td><input type = "button" name = "update_acc" value ="更換" style="height:32px;width:60px;"></td>'+
					'</tr>';
		acc_content_cn	+= '<tr>'+
					'<td style ="background-color:#BFC7DF;height:32px;" align="center">';
			if(data['Account']==''){
				acc_content_cn += '無';
			}else{
				acc_content_cn += data['Account'];
			}
					acc_content_cn +='</td>';
					
					acc_content_cn+='<td><input type = "button" name = "update_acc" value ="更换" style="height:32px;width:60px;"></td>'+
					'</tr>';
		acc_content_en	+= '<tr>'+
					'<td style ="background-color:#BFC7DF;height:32px;" align="center">';
			if(data['Account']==''){
				acc_content_en += 'no';
			}else{
				acc_content_en += data['Account'];
			}
					acc_content_en +='</td>';
					
					acc_content_en+='<td><input type = "button" name = "update_acc" value ="Replace" style="height:32px;width:60px;"></td>'+
					'</tr>';
		//是否承租
		if('<?php echo $_GET['store_class']?>' =='rent'){
		var acc = '<table border ="1" cellpadding="0" cellspacing="0" class = "mar" align="left" style="border-color:#d0d0d0"><tr>'+
						'<td style="background-color:#556AAA;color:#FFF;height:35px;width:100px;" align="center">承租者</td>'+
						'<td style="background-color:#556AAA;color:#FFF;width:60px;" align="center">更換</td></tr>'+
						acc_content+
				  '</table>';
		var acc_cn = '<table border ="1" cellpadding="0" cellspacing="0" class = "mar" align="left" style="border-color:#d0d0d0"><tr>'+
						'<td style="background-color:#556AAA;color:#FFF;height:35px;width:100px;" align="center">承租者</td>'+
						'<td style="background-color:#556AAA;color:#FFF;width:60px;" align="center">更换</td></tr>'+
						acc_content_cn+
					 '</table>';
		var acc_en = '<table border ="1" cellpadding="0" cellspacing="0" class = "mar" align="left" style="border-color:#d0d0d0"><tr>'+
						'<td style="background-color:#556AAA;color:#FFF;height:35px;width:100px;" align="center">Tenant</td>'+
						'<td style="background-color:#556AAA;color:#FFF;width:60px;" align="center">Replace</td></tr>'+
						acc_content_en+
					 '</table>';
		}else{
			var acc="";
			var acc_cn="";
			var acc_en="";
		}
		
		//兌幣機提醒數量&比值
		if(data['Information'][0].substr(0,2)=="CM"){
		
			preset_content += '<tr>'+
						'<td style ="background-color:#D4C2AB;height:32px;" align="center">'+data['GCMQuantity']+'</td>'+
						'<td style ="background-color:#D4C2AB;"><input type = "button" name = "reminder_update" value ="修改" style="height:32px;width:60px;"></td>'+
						'</tr>';
		var preset = '<table border ="1" cellpadding="0" cellspacing="0" class = "mar" align="left" style="border-color:#d0d0d0"><tr>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:90px;height:35px;" align="center">提醒數量</td>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:60px;" align="center">修改</td>'+
						preset_content+
					'</table>';
			preset_content_cn += '<tr>'+
						'<td style ="background-color:#D4C2AB;height:32px;" align="center">'+data['GCMQuantity']+'</td>'+
						'<td style ="background-color:#D4C2AB;"><input type = "button" name = "reminder_update" value ="修改" style="height:32px;width:60px;"></td>'+
						'</tr>';
		var preset_cn = '<table border ="1" cellpadding="0" cellspacing="0" class = "mar" align="left" style="border-color:#d0d0d0"><tr>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:120px;height:35px;" align="center">提醒数量</td>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:60px;" align="center">修改</td>'+
						preset_content_cn+
					'</table>';
			preset_content_en += '<tr>'+
						'<td style ="background-color:#D4C2AB;height:32px;" align="center">'+data['GCMQuantity']+'</td>'+
						'<td style ="background-color:#D4C2AB;"><input type = "button" name = "reminder_update" value ="Edit" style="height:32px;width:60px;"></td>'+
						'</tr>';
		var preset_en = '<table border ="1" cellpadding="0" cellspacing="0" class = "mar" align="left" style="border-color:#d0d0d0"><tr>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:120px;height:35px;" align="center">Reminders number</td>'+
						'<td style ="background-color:#6D5736;color:#FFF;width:60px;" align="center">Edit</td>'+
						preset_content_en+
					'</table>';
			CM_CB += '<tr>'+
						'<td style ="background-color:#DEA1A4;height:32px;" align="center">'+data['CMCB'][0]+'</td>'+
						'<td style ="background-color:#FFF;height:32px;" align="center">'+data['CMCB'][1]+'</td>'+
						'<td style ="background-color:#DEA1A4;"><input type = "button" name = "CB_update" value ="修改" style="height:32px;width:60px;"></td>'+
						'</tr>';
		var CMCB = '<table border ="1" cellpadding="0" cellspacing="0" class = "mar" align="left" style="border-color:#d0d0d0"><tr>'+
						'<td style ="background-color:#b53f45;color:#FFF;width:90px;height:35px;" align="center">硬幣(比值)</td>'+
						'<td style ="background-color:#b53f45;color:#FFF;width:90px;height:35px;" align="center">鈔票(比值)</td>'+
						'<td style ="background-color:#b53f45;color:#FFF;width:60px;" align="center">修改</td>'+
						CM_CB+
					'</table>';
		}else{preset='';}
		
		if(ln=='cn'){
			if(preset==''){
				table =str_cn+'<br></br><br></br><p></p>'+count_cn+'<br></br><br></br><p></p>'+acc_cn+'<br></br><br></br><p></p>';
			}else{
				table =str_cn+'<br></br><br></br><p></p>'+count_cn+'<br></br><br></br><p></p>'+preset_cn+'<br></br><br></br><p></p>'+acc_cn+'<br></br><br></br><p></p>';
			}
		}else if(ln=='en'){
			if(preset==''){
				table =str_en+'<br></br><br></br><p></p>'+count_en+'<br></br><br></br><p></p>'+acc_en+'<br></br><br></br><p></p>';
			}else{
				table =str_en+'<br></br><br></br><p></p>'+count_en+'<br></br><br></br><p></p>'+preset_en+'<br></br><br></br><p></p>'+acc_en+'<br></br><br></br><p></p>';
			}
		}else{
			if(preset==''){
				table =str+'<br></br><br></br><p></p>'+count+'<br></br><br></br><p></p>'+acc+'<br></br><br></br><p></p>';
			}else{
				table =str+'<br></br><br></br><p></p>'+count+'<br></br><br></br><p></p>'+preset+'<br></br><br></br><p></p>'+CMCB+'<br></br><br></br><p></p>'+acc+'<br></br><br></br><p></p>';
			}
		}
		var title='<p style="background-color:#F2E85F"><span>'+data['Information'][1]+'</span></p>';
		
		right_title.innerHTML =title;
		
		//權限判斷
		if(<?php echo @$_SESSION['competence']?> =='3'){
			if('<?php echo @$_GET['change_account']?>'=='on'){
				right_content.innerHTML =table;
			}else{
				right_content.innerHTML= '<font color="red">權限尚未開通</font>';
			}
		}else{
			right_content.innerHTML =table;
		}
		
		right_page.innerHTML = '';
		
		
		var input = document.getElementsByTagName('input');
		var sel = document.getElementById('account');
		var update = document.getElementById('update');
		var clear = document.getElementById('clear');
		var show_revenue = document.getElementById('show_revenue');
		var show_pay = document.getElementById('show_pay');
		var show_cm = document.getElementById('show_cm');
		var span = document.getElementsByTagName('span');
		
		if(ln=='cn'){
			span[0].innerHTML=data['Information'][1];
		}else if(ln=='en'){
			span[0].innerHTML=data['Information'][1];
		}
		
		if(data['Account']!=''){
			/* span[2].style.display="none";
			span[3].style.display="none";
			span[4].style.display="none"; */
		}else{
			/* span[4].style.display="none"; */
			
			//權限判斷
			if('<?php echo $_GET['store_class']?>' =='rent'){
				if(<?php echo @$_SESSION['competence']?> =='3'){
					if('<?php echo @$_GET['change_account']?>'=='on'){
				if(ln=='cn'){
					right_content.innerHTML +='<a href="insert_account.php?store_number=<?php echo $_GET['store_number']?>&machine_number=<?php echo $_GET['machine_number']?>&ln=<?php echo $_GET['ln']?>"	style="text-decoration:none"><font color="red">新增承租者</font></a>';
				}else if(ln=='en'){
					right_content.innerHTML +='<a href="insert_account.php?store_number=<?php echo $_GET['store_number']?>&machine_number=<?php echo $_GET['machine_number']?>&ln=<?php echo $_GET['ln']?>" style="text-decoration:none"><font color="red">Add tenant</font></a>';
				}else{
					right_content.innerHTML +='<a href="insert_account.php?store_number=<?php echo $_GET['store_number']?>&machine_number=<?php echo $_GET['machine_number']?>&ln=<?php echo $_GET['ln']?>" style="text-decoration:none"><font color="red">新增承租者</font></a>';
				}
					}
				}else{
					if(ln=='cn'){
						right_content.innerHTML +='<a href="insert_account.php?store_number=<?php echo $_GET['store_number']?>&machine_number=<?php echo $_GET['machine_number']?>&ln=<?php echo $_GET['ln']?>"	style="text-decoration:none"><font color="red">新增承租者</font></a>';
					}else if(ln=='en'){
						right_content.innerHTML +='<a href="insert_account.php?store_number=<?php echo $_GET['store_number']?>&machine_number=<?php echo $_GET['machine_number']?>&ln=<?php echo $_GET['ln']?>" style="text-decoration:none"><font color="red">Add tenant</font></a>';
					}else{
						right_content.innerHTML +='<a href="insert_account.php?store_number=<?php echo $_GET['store_number']?>&machine_number=<?php echo $_GET['machine_number']?>&ln=<?php echo $_GET['ln']?>" style="text-decoration:none"><font color="red">新增承租者</font></a>';
					}
				}
			}else{
				span[1]='';
			}
		}
		
		for(var i=0;i<input.length;i++){
			input[i].onclick = function(){
				if(this.name == "update"){
					operating_static = 0;
					update_account(right_content);
				}else if(this.name == "update_acc"){
					update_acc(right_content);
				}else if(this.name == "reminder_update"){
					reminder_update(right_content);
				}else{
					CB_update(right_content);
				}
			}
		}
		
		//修改機台資料
		function update_account(right_content){
		
		if(data['Information'][0].substr(0,2)!="CM"){
			
			if(<?php echo @$_SESSION['competence']?>=='4'){
				var table = '<p>機台編號：<font color="red">'+data['Information'][0]+'</font></p>'+
					'<p>機台名稱：<input type = "text" value = '+data['Information'][1]+' style = "width:400px;"></p>'+
					'<p>投幣：<input type = "text" value = '+data['Information'][2]+' id="revenue" style = "width:400px;"></p>'+
					'<p>退幣：<input type = "text" value = '+data['Information'][3]+' id="pay" style = "width:400px;"></p>'+
					'<p>Key coin：<input type = "text" value = '+data['Information'][4]+' id="pay" style = "width:400px;"></p>'+
					'<p>Key out：<input type = "text" value = '+data['Information'][5]+' id="pay" style = "width:400px;"></p>'+
					'<p>分成比：<input type = "text" value = '+data['Information'][10]+' id="pay" style = "width:400px;"></p>'+
					'<p><input type = "button" id = "update" value ="修改">'+
					'&nbsp;&nbsp;<input type = "button" id = "clear" value ="取消"></p>';
				var table_cn = '<p>机台编号：<font color="red">'+data['Information'][0]+'</font></p>'+
					'<p>机台名称：<input type = "text" value = '+data['Information'][1]+' style = "width:400px;"></p>'+
					'<p>Coin in：<input type = "text" value = '+data['Information'][2]+' id="revenue" style = "width:400px;"></p>'+
					'<p>Coin out：<input type = "text" value = '+data['Information'][3]+' id="pay" style = "width:400px;"></p>'+
					'<p>Key coin：<input type = "text" value = '+data['Information'][4]+' id="pay" style = "width:400px;"></p>'+
					'<p>Key out：<input type = "text" value = '+data['Information'][5]+' id="pay" style = "width:400px;"></p>'+
					'<p>分成比：<input type = "text" value = '+data['Information'][10]+' id="pay" style = "width:400px"></p>'+
					'<p><input type = "button" id = "update" value ="修改">'+
					'&nbsp;&nbsp;<input type = "button" id = "clear" value ="取消"></p>';
				var table_en = '<p>Machine number：<font color="red">'+data['Information'][0]+'</font></p>'+
					'<p>Machine name：<input type = "text" value = '+data['Information'][1]+' style = "width:400px;"></p>'+
					'<p>Coin in：<input type = "text" value = '+data['Information'][2]+' id="revenue" style = "width:400px;"></p>'+
					'<p>Coin out：<input type = "text" value = '+data['Information'][3]+' id="pay" style = "width:400px;"></p>'+
					'<p>Key coin：<input type = "text" value = '+data['Information'][4]+' id="pay" style = "width:400px;"></p>'+
					'<p>Key out：<input type = "text" value = '+data['Information'][5]+' id="pay" style = "width:400px;"></p>'+
					'<p>precent：<input type = "text" value = '+data['Information'][10]+' id="pay" style = "width:400px;"></p>'+
					'<p><input type = "button" id = "update" value ="Save">'+
					'&nbsp;&nbsp;<input type = "button" id = "clear" value ="Cancel"></p>';
			}else{
				var table = '<p>機台編號：<font color="red">'+data['Information'][0]+'</font></p>'+
					'<p>機台名稱：<input type = "text" value = '+data['Information'][1]+' style = "width:400px;"></p>'+
					'<p>預設值(入)：<input type = "text" value = '+data['Information'][2]+' id="revenue" style = "width:400px;"></p>'+
					'<p>預設值(出)：<input type = "text" value = '+data['Information'][3]+' id="pay" style = "width:400px;"></p>'+
					'<p><input type = "button" id = "update" value ="修改">'+
					'&nbsp;&nbsp;<input type = "button" id = "clear" value ="取消"></p>';
				var table_cn = '<p>机台编号：<font color="red">'+data['Information'][0]+'</font></p>'+
					'<p>机台名称：<input type = "text" value = '+data['Information'][1]+' style = "width:400px;"></p>'+
					'<p>预设值(入)：<input type = "text" value = '+data['Information'][2]+' id="revenue" style = "width:400px;"></p>'+
					'<p>预设值(出)：<input type = "text" value = '+data['Information'][3]+' id="pay" style = "width:400px;"></p>'+
					'<p><input type = "button" id = "update" value ="修改">'+
					'&nbsp;&nbsp;<input type = "button" id = "clear" value ="取消"></p>';
				var table_en = '<p>Machine number：<font color="red">'+data['Information'][0]+'</font></p>'+
					'<p>Machine name：<input type = "text" value = '+data['Information'][1]+' style = "width:400px;"></p>'+
					'<p>Default value(in)：<input type = "text" value = '+data['Information'][2]+' id="revenue" style = "width:400px;"></p>'+
					'<p>Default value(out)：<input type = "text" value = '+data['Information'][3]+' id="pay" style = "width:400px;"></p>'+
					'<p><input type = "button" id = "update" value ="Save">'+
					'&nbsp;&nbsp;<input type = "button" id = "clear" value ="Cancel"></p>';
			}
		}else{
			var table = '<p>機台編號：<font color="red">'+data['Information'][0]+'</font></p>'+
					'<p>機台名稱：<input type = "text" value = '+data['Information'][1]+' style = "width:400px;"></p>'+
					'<p>紙鈔(出)：<input type = "text" value = '+data['Information'][2]+' id="revenue" style = "width:400px;"></p>'+
					'<p>硬幣(出)：<input type = "text" value = '+data['Information'][3]+' id="pay" style = "width:400px;"></p>'+
					'<p><input type = "button" id = "update" value ="修改">'+
					'&nbsp;&nbsp;<input type = "button" id = "clear" value ="取消"></p>';
			var table_cn = '<p>机台编号：<font color="red">'+data['Information'][0]+'</font></p>'+
					'<p>机台名称：<input type = "text" value = '+data['Information'][1]+' style = "width:400px;"></p>'+
					'<p>纸钞(出)：<input type = "text" value = '+data['Information'][2]+' id="revenue" style = "width:400px;"></p>'+
					'<p>硬币(出)：<input type = "text" value = '+data['Information'][3]+' id="pay" style = "width:400px;"></p>'+
					'<p><input type = "button" id = "update" value ="修改">'+
					'&nbsp;&nbsp;<input type = "button" id = "clear" value ="取消"></p>';
			var table_en = '<p>Machine number：<font color="red">'+data['Information'][0]+'</font></p>'+
					'<p>Machine name：<input type = "text" value = '+data['Information'][1]+' style = "width:400px;"></p>'+
					'<p>Banknote(out)：<input type = "text" value = '+data['Information'][2]+' id="revenue" style = "width:400px;"></p>'+
					'<p>Coin(out)：<input type = "text" value = '+data['Information'][3]+' id="pay" style = "width:400px;"></p>'+
					'<p><input type = "button" id = "update" value ="Save">'+
					'&nbsp;&nbsp;<input type = "button" id = "clear" value ="Cancel"></p>';
		}
		right_title.innerHTML = '<p class = "mar" style="background-color:#F2E85F"><span>機台修改</span></p>';
		
		if(ln=='cn'){
			right_content.innerHTML = table_cn;
		}else if(ln=='en'){
			right_content.innerHTML = table_en;
		}else{
			right_content.innerHTML = table;
		}
		right_page.innerHTML = '';
		
		var input = right_content.getElementsByTagName('input');
		var update = document.getElementById('update');
		var clear = document.getElementById('clear');
		var revenue = document.getElementById('revenue');
		var pay = document.getElementById('pay');
		var span = document.getElementsByTagName('span');
		
		if(ln=='cn'){
			span[0].innerHTML='机台修改';
		}else if(ln=='en'){
			span[0].innerHTML='Machine Settings';
		}
		clear.onclick = function(){
			if(ln=='cn'){
				if(confirm("确定取消?")){
					operating_static = 1;
					show_mac();
				}
			}else if(ln=='en'){
				if(confirm("Really?")){
					operating_static = 1;
					show_mac();
				}
			}else{
				if(confirm("確定取消?")){
					operating_static = 1;
					show_mac();
				}
			}
		}
		
		update.onclick = function(){
			if(ln=='cn'){
			if(confirm("确定修改?")){
				operating_static = 1;
			if(data['Information'][0].substr(0,2)!="CM"){
				var obj = {
					instruction: 'UpdateMachine',
					machine:data['Information'][0],
					name:input[0].value,
					virtual_revenue:revenue.value,
					virtual_pay:pay.value
				};
			}else{
				var obj = {
					instruction: 'UpdateMachine',
					machine:data['Information'][0],
					name:input[0].value,
					virtual_revenue:revenue.value,
					virtual_pay:pay.value
				};
			}
				var SetJson = JSON.stringify(obj);
				
				ajax('post','indexphp.php',SetJson,function fun(value){
					var data = JSON.parse(value);
					if(data['data'] == '1' && data['select'] == '1'){
						alert(data['name'] + '修改成功');
						show_mac();
					}else{
						alert(data['name'] + '修改失败');
						show_mac();
					}
				});
			}
			}else if(ln=='en'){
				if(confirm("Really Save?")){
					operating_static = 1;
				if(data['Information'][0].substr(0,2)!="CM"){
					var obj = {
						instruction: 'UpdateMachine',
						machine:data['Information'][0],
						name:input[0].value,
						virtual_revenue:revenue.value,
						virtual_pay:pay.value
					};
				}else{
					var obj = {
						instruction: 'UpdateMachine',
						machine:data['Information'][0],
						name:input[0].value,
						virtual_revenue:revenue.value,
						virtual_pay:pay.value
					};
				}
				var SetJson = JSON.stringify(obj);
				
				ajax('post','indexphp.php',SetJson,function fun(value){
					var data = JSON.parse(value);
					if(data['data'] == '1' && data['select'] == '1'){
						alert(data['name'] + 'success');
						show_mac();
					}else{
						alert(data['name'] + 'failure');
						show_mac();
					}
				});
				}
			}else{
				if(confirm("確定修改?")){
					operating_static = 1;
					
				if(data['Information'][0].substr(0,2)!="CM"){
					if(<?php echo @$_SESSION['competence']?>=='4'){
						var obj = {
							instruction: 'UpdateMachine',
							machine:data['Information'][0],
							name:input[0].value,
							virtual_revenue:revenue.value,
							virtual_pay:pay.value,
							key_coin_meter:input[3].value,
							key_out_meter:input[4].value,
							percent:input[5].value
						};
					}else{
						var obj = {
							instruction: 'UpdateMachine',
							machine:data['Information'][0],
							name:input[0].value,
							virtual_revenue:revenue.value,
							virtual_pay:pay.value
						};
					}
					
				}else{
					var obj = {
						instruction: 'UpdateMachine',
						machine:data['Information'][0],
						name:input[0].value,
						virtual_revenue:revenue.value,
						virtual_pay:pay.value
					};
				}
				var SetJson = JSON.stringify(obj);
				
				ajax('post','indexphp.php',SetJson,function fun(value){
					var data = JSON.parse(value);
					if(data['data'] == '1' && data['select'] == '1'){
						alert(data['name'] + '修改成功');
						show_mac();
					}else{
						alert(data['name'] + '修改失敗');
						show_mac();
					}
				});
				}
			}
		}
		}
		
		//承租資料
		function update_acc(right_content){
		
		var table = '<p>承租者：<input type = "text" style = "width:100px;" value ='+data['Account']+'></p>'+
		'<p>所有承租者：<select id ="account">';
		var account='';
		for(var i=0;i<data['AllAccount'].length;i++){
			account +='<option value ='+data['AllAccount'][i]+'>'+data['AllAccount'][i]+'</option>';
		}
		table +=account+'</select></p>'+
		'<p><input type = "button" id = "update_acc" value ="更換承租者">&nbsp;&nbsp;<input type = "button" id = "delete_acc" value ="清除承租者"></p>';
		
		var table_cn = '<p>承租者：<input type = "text" style = "width:100px;" value ='+data['Account']+'></p>'+
		'<p>所有承租者：<select id ="account">';
		var account_cn='';
		for(var i=0;i<data['AllAccount'].length;i++){
			account_cn +='<option value ='+data['AllAccount'][i]+'>'+data['AllAccount'][i]+'</option>';
		}
		table_cn +=account_cn+'</select></p>'+
		'<p><input type = "button" id = "update_acc" value ="更换承租者">&nbsp;&nbsp;<input type = "button" id = "delete_acc" value ="清除承租者"></p>';
		
		var table_en = '<p>Tenant：<input type = "text" style = "width:100px;" value ='+data['Account']+'></p>'+
		'<p>All Tenant：<select id ="account">';
		var account_en='';
		for(var i=0;i<data['AllAccount'].length;i++){
			account_en +='<option value ='+data['AllAccount'][i]+'>'+data['AllAccount'][i]+'</option>';
		}
		table_en +=account_en+'</select></p>'+
		'<p><input type = "button" id = "update_acc" value ="Change Tenant">&nbsp;&nbsp;<input type = "button" id = "delete_acc" value ="Clear Tenant"></p>';
		
		var title1 ='<p class = "mar" style="background-color:#F2E85F"><span>'+data['Information'][1]+' 承租更換</span></p>';
		right_title.innerHTML = title1;
		
		
		var ln='<?php echo $_GET['ln']?>';
		
		if(ln=='cn'){
			right_content.innerHTML = table_cn;
		}else if(ln=='en'){
			right_content.innerHTML = table_en;
		}else{
			right_content.innerHTML = table;
		}
		
		var input = right_content.getElementsByTagName('input');
		var sel = document.getElementById('account');
		var update_acc = document.getElementById('update_acc');
		var delete_acc = document.getElementById('delete_acc');
		var span = document.getElementsByTagName('span');
		
		
		right_page.innerHTML = '';
		
		if(ln=='cn'){
			span[0].innerHTML=data['Information'][1];
		}else if(ln=='en'){
			span[0].innerHTML=data['Information'][1];
		}
		
		sel.onchange =function(){
			input[0].value = sel.value;
		}
		
		delete_acc.onclick = function(){
			if(ln=='cn'){
				if(confirm("确定清除?")){
					operating_static = 1;
					
					var url_value = 'instruction=DeleteRelationship&store_number=<?php echo $_GET['store_number']?>&machine_number='+data['Information'][0]+'&account='+input[0].value;
					ajax('post','indexphp.php',url_value,function fun(value){
						var data = JSON.parse(value);
						if(data['message'] == '1'){
							alert('删除成功');
							input[0].value="";
							show_mac();
						}else{
							alert('删除失败');
							show_mac();
						}
					});
				}
			}else if(ln=='en'){
				if(confirm("Really Clear?")){
					operating_static = 1;
					
					var url_value = 'instruction=DeleteRelationship&store_number=<?php echo $_GET['store_number']?>&machine_number='+data['Information'][0]+'&account='+input[0].value;
					ajax('post','indexphp.php',url_value,function fun(value){
						var data = JSON.parse(value);
						if(data['message'] == '1'){
							alert('Delete success');
							input[0].value="";
							show_mac();
						}else{
							alert('Delete failure');
							show_mac();
						}
					});
				}
			}else{
				if(confirm("確定清除?")){
					operating_static = 1;
					
					var url_value = 'instruction=DeleteRelationship&store_number=<?php echo $_GET['store_number']?>&machine_number='+data['Information'][0]+'&account='+input[0].value;
					ajax('post','indexphp.php',url_value,function fun(value){
						var data = JSON.parse(value);
						if(data['message'] == '1'){
							alert('刪除成功');
							input[0].value="";
							show_mac();
						}else{
							alert('刪除失敗');
							show_mac();
						}
					});
				}
			}
		}
		
		update_acc.onclick = function(){
			if(ln=='cn'){
				if(data['Account']!= ""){
					if(confirm("确定更换?")){
					operating_static = 1;
					
					var url_value = 'instruction=UpdateRelationship&store_number=<?php echo $_GET['store_number']?>&machine_number='+data['Information'][0]+
					'&account='+data['Account']+'&new_account='+input[0].value;
					
					ajax('post','indexphp.php',url_value,function fun(value){
						var data = JSON.parse(value);
						if(data['message'] == '1'){
							alert('更换成功');
							show_mac();
						}else{
							alert('更换失败');
							show_mac();
						}
					});
					}
				}else{
					if(confirm("确定更换?")){
					operating_static = 1;
					
					var url_value = 'instruction=InsertRelationship&store_number=<?php echo $_GET['store_number']?>&machine_number='+data['Information'][0]+'&account='+input[0].value;
					
					ajax('post','indexphp.php',url_value,function fun(value){
						var data = JSON.parse(value);
						if(data['message'] == '1'){
							alert('更换成功');
							show_mac();
						}else{
							alert('更换失败');
							show_mac();
						}
					});
					}
				}
			}else if(ln=='en'){
				if(data['Account']!= ""){
					if(confirm("Really Change?")){
					operating_static = 1;
					
					var url_value = 'instruction=UpdateRelationship&store_number=<?php echo $_GET['store_number']?>&machine_number='+data['Information'][0]+
					'&account='+data['Account']+'&new_account='+input[0].value;
					
					ajax('post','indexphp.php',url_value,function fun(value){
						var data = JSON.parse(value);
						if(data['message'] == '1'){
							alert('Change success');
							show_mac();
						}else{
							alert('Change failure');
							show_mac();
						}
					});
					}
				}else{
					if(confirm("Really Change?")){
					operating_static = 1;
					
					var url_value = 'instruction=InsertRelationship&store_number=<?php echo $_GET['store_number']?>&machine_number='+data['Information'][0]+'&account='+input[0].value;
					
					ajax('post','indexphp.php',url_value,function fun(value){
						var data = JSON.parse(value);
						if(data['message'] == '1'){
							alert('Change success');
							show_mac();
						}else{
							alert('Change failure');
							show_mac();
						}
					});
					}
				}
			}else{
				if(data['Account']!= ""){
					if(confirm("確定更換?")){
					operating_static = 1;
					
					var url_value = 'instruction=UpdateRelationship&store_number=<?php echo $_GET['store_number']?>&machine_number='+data['Information'][0]+
					'&account='+data['Account']+'&new_account='+input[0].value;
					
					ajax('post','indexphp.php',url_value,function fun(value){
						var data = JSON.parse(value);
						if(data['message'] == '1'){
							alert('更換成功');
							show_mac();
						}else{
							alert('更換失敗');
							show_mac();
						}
					});
					}
				}else{
					if(confirm("確定更換?")){
					operating_static = 1;
					
					var url_value = 'instruction=InsertRelationship&store_number=<?php echo $_GET['store_number']?>&machine_number='+data['Information'][0]+'&account='+input[0].value;
					
					ajax('post','indexphp.php',url_value,function fun(value){
						var data = JSON.parse(value);
						if(data['message'] == '1'){
							alert('更換成功');
							show_mac();
						}else{
							alert('更換失敗');
							show_mac();
						}
					});
					}
				}
			}
		}
	}
	
		//修改提醒數量
		function reminder_update(right_content){
		
		var table = 
				'<p>提醒數量：<input type = "text" value = '+data['GCMQuantity']+' style = "width:400px;"></p>'+
				'<p><input type = "button" id = "update" value ="修改">'+
				 '&nbsp;&nbsp;<input type = "button" id = "clear" value ="取消"></p>';
		var table_cn = 
				'<p>提醒数量：<input type = "text" value = '+data['GCMQuantity']+' style = "width:400px;"></p>'+
				'<p><input type = "button" id = "update" value ="修改">'+
				 '&nbsp;&nbsp;<input type = "button" id = "clear" value ="取消"></p>';
		var table_en = 
				'<p>Reminders number：<input type = "text" value = '+data['GCMQuantity']+' style = "width:400px;"></p>'+
				'<p><input type = "button" id = "update" value ="Save">'+
				'&nbsp;&nbsp;<input type = "button" id = "clear" value ="Cancel"></p>';
		
		right_title.innerHTML = '<p class = "mar" style="background-color:#F2E85F"><span>機台修改</span></p>';
		
		if(ln=='cn'){
			right_content.innerHTML = table_cn;
		}else if(ln=='en'){
			right_content.innerHTML = table_en;
		}else{
			right_content.innerHTML = table;
		}
		right_page.innerHTML = '';
		
		var input = right_content.getElementsByTagName('input');
		var update = document.getElementById('update');
		var clear = document.getElementById('clear');
		var span = document.getElementsByTagName('span');
		
		if(ln=='cn'){
			span[0].innerHTML='机台修改';
		}else if(ln=='en'){
			span[0].innerHTML='Machine Settings';
		}
		clear.onclick = function(){
			if(ln=='cn'){
				if(confirm("确定取消?")){
					operating_static = 1;
					show_mac();
				}
			}else if(ln=='en'){
				if(confirm("Really?")){
					operating_static = 1;
					show_mac();
				}
			}else{
				if(confirm("確定取消?")){
					operating_static = 1;
					show_mac();
				}
			}
		}
		
		update.onclick = function(){
			if(ln=='cn'){
			if(confirm("确定修改?")){
				operating_static = 1;
				
				var url_value = 'instruction=UpdateCoinQuantity&machine_number='+data['Information'][0]+'&quantity='+input[0].value;
				
				ajax('post','indexphp.php',url_value,function fun(value){
					var data = JSON.parse(value);
					if(data['message'] == 'success'){
						alert('修改成功');
						show_mac();
					}else{
						alert('修改失败');
						show_mac();
					}
				});
			}
			}else if(ln=='en'){
				if(confirm("Really Save?")){
					operating_static = 1;
					
				var url_value = 'instruction=UpdateCoinQuantity&machine_number='+data['Information'][0]+'&quantity='+input[0].value;
				
				ajax('post','indexphp.php',url_value,function fun(value){
					var data = JSON.parse(value);
					if(data['message'] == 'success'){
						alert('success');
						show_mac();
					}else{
						alert('failure');
						show_mac();
					}
				});
				}
			}else{
				if(confirm("確定修改?")){
					operating_static = 1;
				
				var url_value = 'instruction=UpdateCoinQuantity&machine_number='+data['Information'][0]+'&quantity='+input[0].value;
				ajax('post','indexphp.php',url_value,function fun(value){
					var data = JSON.parse(value);
					if(data['message'] == 'success'){
						alert('修改成功');
						show_mac();
					}else{
						alert('修改失敗');
						show_mac();
					}
				});
				}
			}
		}
		}
		
		//修改比值
		function CB_update(right_content){
		
		var table = 
				'<p>硬幣(比值)：<input type = "text" value = '+data['CMCB'][0]+' style = "width:400px;"></p>'+
				'<p>鈔票(比值)：<input type = "text" value = '+data['CMCB'][1]+' style = "width:400px;"></p>'+
				'<p><input type = "button" id = "update" value ="修改">'+
				 '&nbsp;&nbsp;<input type = "button" id = "clear" value ="取消"></p>';
		var table_cn = 
				'<p>硬幣(比值)：<input type = "text" value = '+data['CMCB'][0]+' style = "width:400px;"></p>'+
				'<p>鈔票(比值)：<input type = "text" value = '+data['CMCB'][1]+' style = "width:400px;"></p>'+
				'<p><input type = "button" id = "update" value ="修改">'+
				 '&nbsp;&nbsp;<input type = "button" id = "clear" value ="取消"></p>';
		var table_en = 
				'<p>硬幣(比值)：<input type = "text" value = '+data['CMCB'][0]+' style = "width:400px;"></p>'+
				'<p>鈔票(比值)：<input type = "text" value = '+data['CMCB'][1]+' style = "width:400px;"></p>'+
				'<p><input type = "button" id = "update" value ="修改">'+
				 '&nbsp;&nbsp;<input type = "button" id = "clear" value ="取消"></p>';
		
		right_title.innerHTML = '<p class = "mar" style="background-color:#F2E85F"><span>比值修改</span></p>';
		
		if(ln=='cn'){
			right_content.innerHTML = table_cn;
		}else if(ln=='en'){
			right_content.innerHTML = table_en;
		}else{
			right_content.innerHTML = table;
		}
		right_page.innerHTML = '';
		
		var input = right_content.getElementsByTagName('input');
		var update = document.getElementById('update');
		var clear = document.getElementById('clear');
		var span = document.getElementsByTagName('span');
		
		if(ln=='cn'){
			span[0].innerHTML='机台修改';
		}else if(ln=='en'){
			span[0].innerHTML='Machine Settings';
		}
		clear.onclick = function(){
			if(ln=='cn'){
				if(confirm("确定取消?")){
					operating_static = 1;
					show_mac();
				}
			}else if(ln=='en'){
				if(confirm("Really?")){
					operating_static = 1;
					show_mac();
				}
			}else{
				if(confirm("確定取消?")){
					operating_static = 1;
					show_mac();
				}
			}
		}
		
		update.onclick = function(){
			if(ln=='cn'){
			if(confirm("确定修改?")){
				operating_static = 1;
				
				var url_value = 'instruction=UpdateCB&machine_number='+data['Information'][0]+'&coin='+input[0].value+'&banknote='+input[1].value;
				
				ajax('post','indexphp.php',url_value,function fun(value){
					var data = JSON.parse(value);
					if(data['message'] == 'success'){
						alert('修改成功');
						show_mac();
					}else{
						alert('修改失败');
						show_mac();
					}
				});
			}
			}else if(ln=='en'){
				if(confirm("Really Save?")){
					operating_static = 1;
					
				var url_value = 'instruction=UpdateCB&machine_number='+data['Information'][0]+'&coin='+input[0].value+'&banknote='+input[1].value;
				
				ajax('post','indexphp.php',url_value,function fun(value){
					var data = JSON.parse(value);
					if(data['message'] == 'success'){
						alert('success');
						show_mac();
					}else{
						alert('failure');
						show_mac();
					}
				});
				}
			}else{
				if(confirm("確定修改?")){
					operating_static = 1;
				
				var url_value = 'instruction=UpdateCB&machine_number='+data['Information'][0]+'&coin='+input[0].value+'&banknote='+input[1].value;
				ajax('post','indexphp.php',url_value,function fun(value){
					var data = JSON.parse(value);
					if(data['message'] == 'success'){
						alert('修改成功');
						show_mac();
					}else{
						alert('修改失敗');
						show_mac();
					}
				});
				}
			}
		}
		}
		
	})
	
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
