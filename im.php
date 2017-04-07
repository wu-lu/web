<?php
	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>匯入</title>
<script src="fun.js"></script>
</head>
<?php
require('..\Mysql\Mysql_Connect.php');
require_once('Excel/reader.php');
header("Content-Type:text/html; charset=utf-8");

	//接收資料
	if($_POST){
		if($_SESSION['competence']=='3'){
			echo "<script>alert('權限尚未開通');</script>";
		}else{
		$uploadExcel = $_FILES['file']['tmp_name'];

		$data = new Spreadsheet_Excel_Reader();
		$data->setOutputEncoding('UTF-8');
		$data->read($uploadExcel);
		
		$readData =array();
		
		//讀取檔案中的每一格，並把它存至陣列
		for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++ ) {
			for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++ ) {
				$readData[$i][$j] = $data->sheets[0]['cells'][$i][$j];
			}
		}
		sava_data($readData);
		}
	}

    function sava_data($readData){
		$count =0;
		foreach( $readData as $key => $tmp){
			/*-----開始匯入資料-----*/
			if( $key==1 ){
				//$sql_ex = "INSERT INTO `aa`(number ,name ,quantity) "; //資料庫的table欄位要記得更改.
				continue;
			}else{
				$sql = "UPDATE `commodity` SET total='$tmp[3]' where name ='$tmp[2]' and commodity_number='$tmp[1]'";
			$result = mysql_query($sql) or die("無法送出" . mysql_error( ));
			$count++;
			}
		}
		echo "<script>alert('共加入".$count."筆資料');</script>";
    }
	
	
?>
<script>

//檢查格式是否正確
function import_check(){
	var f_content = form1.file.value;
	var fileext=f_content.substring(f_content.lastIndexOf("."),f_content.length)
	fileext=fileext.toLowerCase()
	if (fileext!='.xls'){
		alert("對不起，導入資料格式必須是xls格式文件哦，請您調整格式後重新上傳，謝謝！");
		return false;
	}
}

function example(){
	var url_value = "instruction=ShowCommodity&store_number=<?php echo $_GET['store_number']?>";
	ajax('post','indexphp.php',url_value,
		function fun(value){
			var data = JSON.parse(value);
			var example_content='';
			for(var a=0;a<data['commodity'].length;a++){
				example_content += '<tr>'+'<td>'+data['commodity'][a][0]+'</td>'
										 +'<td align="right">'+data['commodity'][a][1]+'</td>'
										 +'<td></td>'
								 +'</tr>';
			}
			var example= '<div id="div_excel"><table border ="1" cellpadding="0" cellspacing="0" class = "mar" align="left" id="store" style="border-color:#d0d0d0;"><tr><td style="width:140px;">編號</td><td>名稱</td><td  style="width:70px;">數量</td></tr>'+example_content+'</div>';
			right_content.innerHTML='範例：<br></br>'+example;
			right_content1.innerHTML='<br><button type="button" onclick="exportExcel()">下載範例</button>';
		}
	)
}

//轉換excel
function exportExcel(){

  var html = '<!DOCTYPE html><html><head><meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8" /><title>Excel</title></head>';
  html += '<body>';
  html += document.getElementById('div_excel').innerHTML + '</body></html>';
  
  window.open('data:application/vnd.ms-excel,'+ encodeURIComponent(html));
  
}
</script>
<table width="100%" border="0" align="center" style="margin-top:20px; border:1px solid #9abcde;">
	<form id="form1" name="form1" enctype="multipart/form-data" method="POST" action="">
		<label>選擇Excel資料表</label>
		<input name="file" type="file" id="file" size="50" />
		<input name="submit"  type="submit" value="匯入資料" onclick="import_check();"/>
	</form>
</table>
<div id = "right_content"  style="overflow:auto;">
</div>
<div id = "right_content1"  style="overflow:auto;">
</div>
<div>
<?php //<br><img src="explanation.jpg" width="800" height="360">?>
</div>
<?php echo "<script type='text/javascript'>example();</script>";?>