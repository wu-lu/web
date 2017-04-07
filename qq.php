<!doctype html>
<?php
	session_start();
	header("Content-Type: text/html; charset=utf-8");
	require('..\Mysql\Mysql_Connect.php');
	
	/* $total_money =0;
	
	$sql = "select * from claw_revenue_day 
			where machine_number = '0040'
			and insert_time >= '2016-05-01'
			and insert_time < '2016-06-01'
			order by auto,insert_time desc";
	$results = mysql_query($sql);
	while($row = mysql_fetch_row($results)){
		$total_money += $row[2] * 10;
	//echo $row[2].'<br>';
	} */
	$machine_class = new machine();
	$machine_class -> index_store('T104113001_s1','2016-05');
	
class machine{
	function index_store($store_number,$month){
		$machine_number_arr = $this->return_data('StoreMatchMachine','T104113001_s1');//搜尋該店家所有的機台
		if(count($machine_number_arr) != 0){
			//每間店家下的所有機台都要做資料統計
			foreach($machine_number_arr as $machine_number){
				$machine = Array();					//儲存一台機台的顯示資料
				$machine[] = $machine_number[0];	//機台編號
				$machine[] = $machine_number[1];	//機台名稱
				$pay_deteil = Array();				//機台當月總支出
				$game_total_arr = Array();			//收入+支出+分成比(遊戲機)
				$game_pay_arr = Array();			//支出+分成比(遊戲機)
				
					$revenue = 0;				//機台當月收入
					$revenue = $this->show_revenue_pay('internal',$machine_number[0],'','revenue',$month);
					$machine[] = $revenue;
					//支出有很多品項，所以要先搜尋該店家所有品項代碼，搜尋完再做數量及總額統計
					$select_commodity = "select claw_pay_day.commodity_number 
										from claw_pay_day 
										where machine_number = '$machine_number[0]'
										group by claw_pay_day.commodity_number";
					$result_commodity = mysql_query($select_commodity);
					while($commodity_number_arr = mysql_fetch_row($result_commodity)){
						$pay_quantity_arr = $this->show_revenue_pay('internal',$machine_number[0],$commodity_number_arr[0],'pay',$month);
						//如果沒有資料，型態會是數字型態
						if(gettype($pay_quantity_arr) != 'integer') $pay_deteil[] = $pay_quantity_arr; //數量統計
						
					}
					$machine[] = $pay_deteil;
								
				//echo 'XXXXXXXXXXXXX';
				$machine_total[] = $machine;
			}
		}else{
			$machine = Array();				//儲存一台機台的顯示資料
			$machine_total[] = $machine;
		}
		
		$EchoJS['machine_total'] = $machine_total;
		
		$Y_value =json_encode($EchoJS);
		//print $Y_value;

	}
	

	
	//回傳使用者、店家、機台的資料、兌幣機推播數量、子帳號與權限(陣列儲存)
	function return_data($condition,$content){
		$EchoDB = Array();
		$EchoJS = Array();
		$month = date('Y-m');
		switch($condition){
			
			
			//透過店家代碼查詢機台資料
			case 'StoreMatchMachine' :
				$select = "select machine_data.machine_number,machine_name
						   from data_match,machine_data
						   where data_match.machine_number = machine_data.machine_number
						   and store_number = '$content'
						   group by machine_number";
			break;
			
		}
		
		$select_results = mysql_query($select);
		//echo $condition.':'.$select.chr(0X0A).chr(0X0A);
		if($condition == 'Account' || $condition == 'Machine' || $condition == 'Store' || $condition == 'MachineMatchAccount' || $condition == 'CurrencyExchangeGCM' || $condition == 'Tenant'){
			$select_row = mysql_fetch_row($select_results);
			//如果是搜尋機台又有資料，型態就是陣列，才要搜尋動態coin的資料
			if($condition == 'Machine' && is_array($select_row)){
				if(preg_match("/CM_/i",$content)){
					$select_row[] = $this->show_revenue_pay('quantity',$content,'','banknote',$month);
					$select_row[] = $this->show_revenue_pay('quantity',$content,'','coin',$month);
				}else if(preg_match("/GM_/i",$content)){
					$select_row_revenue = Array();
					$select_row_pay = Array();
					$select_row_revenue = $this->show_revenue_pay('quantity',$content,'','revenue',$month);
					$select_row_pay = $this->show_revenue_pay('quantity',$content,'','pay',$month);
					$select_row[] = $select_row_pay[3];				//key_coin虛擬錶預設
					$select_row[] = $select_row_pay[4];				//key_out虛擬錶預設
					$select_row[] = $select_row_revenue[0];			//投幣枚數
					$select_row[] = $select_row_pay[0];				//退幣枚數
					$select_row[] = $select_row_revenue[1];			//key_coin數量
					$select_row[] = $select_row_pay[1];				//key_out數量
					$select_row[] = $select_row_pay[2];				//百分比
				}else{
					$select_row[] = $this->show_revenue_pay('quantity',$content,'','revenue',$month);
					$select_row[] = $this->show_revenue_pay('quantity',$content,'','pay',$month);
				}
			}
			if(is_bool($select_row)) $select_row = Array(); //如果搜尋到空的就會是布林值，所以要變成陣列輸出
			$EchoDB = $select_row;
			
		}else if($condition == 'AccountMatchStore' || $condition == 'StoreMatchCommodity'){
			while($select_row = mysql_fetch_row($select_results)){
				$EchoDB[] = $select_row;
			}
			//預設的商品沒有關連任何商店，所以最後要補存進去
			if($condition == 'StoreMatchCommodity'){
				$EchoDB[] = ['no_commodity','無預設商品'];
			}
		}else{
			while($select_row = mysql_fetch_row($select_results)){
				if($select_row[0] != 'no_machine'){
					if(count($select_row) == 2){
							$select_num = "select count(account)
										   from data_match,machine_data
										   where data_match.machine_number = machine_data.machine_number
										   and data_match.machine_number = '$select_row[0]'
										   group by data_match.machine_number";
							$result_num = mysql_query($select_num);
							$row_num = mysql_fetch_row($result_num);
							$select_row[] = $row_num[0];
					}

					$EchoDB[] = $select_row;
				}
				
			}
		}
		
		return $EchoDB;
	}
	
	//顯示收入
	function show_revenue_pay($application,$machine_number,$commodity_number,$condition,$month){
		$EchoDB = Array();
		$EchoJS = Array();
		$total_arr = Array();
		$month_begin = $month.'-01';
		$splip_arr = explode('-',$month);
		$month_end = $splip_arr[0].'-'.($splip_arr[1]+1).'-01';
		$commodity_name = '';
		$commodity_name_number = '';
		$total_money = 0;
		$total_quantity = 0;

			/*如果$condition等於revenue則顯示營收
			  如果$condition等於pay則顯示支出*/
			if($condition == 'revenue'){
				$sql = "select * from claw_revenue_day 
							 where machine_number = '$machine_number'
							 and insert_time >= '$month_begin'
							 and insert_time < '$month_end'
							 order by auto,insert_time desc";
				$results = mysql_query($sql);
				$rate = $this->check_rate($machine_number,'machine_number');						//幣值
				/*如果$application等於internal則是內部使用營收金額
				  如果$application等於quantity則是內部使用營收枚數
				  否則就是外部使用營收金額*/
				if($application == 'internal'){
					while($row = mysql_fetch_row($results)){
						$total_money += $row[2] * $rate[0];
					}
					 
					return $total_money;
				}else if($application == 'quantity'){
					//每日營業額
					$sql_d = "select sum(quantity) 
							  from claw_revenue_day 
							  where machine_number = '$machine_number'";
					//每月營業額
					$sql_m = "select sum(quantity) 
							  from claw_revenue_month 
							  where machine_number = '$machine_number'";
					//每年營業額
					$sql_y = "select sum(quantity) 
							  from claw_revenue_year 
							  where machine_number = '$machine_number'";
					$result_d = mysql_query($sql_d);
					$result_m = mysql_query($sql_m);
					$result_y = mysql_query($sql_y);
					$sum_d = mysql_fetch_row($result_d);
					$sum_m = mysql_fetch_row($result_m);
					$sum_y = mysql_fetch_row($result_y);
					return $sum_d[0]+$sum_m[0]+$sum_y[0];
				}else{
					while($row = mysql_fetch_row($results)){
						$detailed = Array();
						$detailed[] = $row[0];
						$detailed[] = $row[1];
						$detailed[] = $row[2] * $rate[0];
						$detailed[] = $row[3];
						$detailed[] = $row[4];
						$EchoDB[] = $detailed;
					}
					$EchoJS['EchoDB'] = $EchoDB;
					echo json_encode($EchoJS);
				}
			}else{
				if($application == 'internal'){
					$sql = "select machine_number,claw_commodity.commodity_number,name,quantity,unit,insert_time 
									 from claw_pay_day,claw_commodity 
									 where claw_pay_day.commodity_number = claw_commodity.commodity_number 
									 and machine_number = '$machine_number'
									 and claw_commodity.commodity_number = '$commodity_number'
									 and insert_time >= '$month_begin'
									 and insert_time < '$month_end'
									 order by insert_time desc";
					$results = mysql_query($sql);
					$row_num = mysql_num_rows($results);
					if($row_num != 0){
						while($row = mysql_fetch_row($results)){
							$commodity_name_number = $row[1];
							$commodity_name = $row[2];
							$total_money += $row[3]*$row[4];
							$total_quantity += $row[3];
						}
						$total_arr[] = $commodity_name_number;
						$total_arr[] = $commodity_name;
						$total_arr[] = $total_quantity;
						$total_arr[] = $total_money;
						return $total_arr;
					}else{
						return 0;
					}
				}else if($application == 'quantity'){
					//每日支出
					$sql_d = "select sum(quantity) 
							  from claw_pay_day 
							  where machine_number = '$machine_number'";
					//每月支出
					$sql_m = "select sum(quantity) 
							  from claw_pay_month 
							  where machine_number = '$machine_number'";
					//每年支出
					$sql_y = "select sum(quantity) 
							  from claw_pay_year 
							  where machine_number = '$machine_number'";
					$result_d = mysql_query($sql_d);
					$result_m = mysql_query($sql_m);
					$result_y = mysql_query($sql_y);
					$sum_d = mysql_fetch_row($result_d);
					$sum_m = mysql_fetch_row($result_m);
					$sum_y = mysql_fetch_row($result_y);
					return $sum_d[0]+$sum_m[0]+$sum_y[0];
				}else{
					$sql = "select machine_number,claw_commodity.commodity_number,name,quantity,unit,insert_time 
							 from claw_pay_day,claw_commodity 
							 where claw_pay_day.commodity_number = claw_commodity.commodity_number 
							 and machine_number = '$machine_number'
							 and insert_time >= '$month_begin'
							 and insert_time < '$month_end'
							 order by insert_time desc";
					$results = mysql_query($sql);
					while($row = mysql_fetch_row($results)){
						$row[] = $row[3]*$row[4];
						$EchoDB[] = $row;
					}
					$EchoJS['EchoDB'] = $EchoDB;
					echo json_encode($EchoJS);
				}
							 
			}
		
	}
	//查詢幣值
	function check_rate($content,$condition){
		if($condition == 'account'){
			$select_rate = "select rate,store_data.store_number from store_data,data_match
										where store_data.store_number = data_match.store_number
										and account = '$content'
										group by store_data.store_number";	
		}else if($condition == 'machine_number'){
			$select_rate = "select rate from data_match,store_data
											where data_match.store_number = store_data.store_number 
											and machine_number = '$content'
											group by data_match.store_number";	
		}else{
			$select_rate = "select key_coin,pay,key_out,percent,key_coin_meter,key_out_meter 
							from game_ratio_meter
							where machine_number = '$content'";
		}
		
		$results_rate = mysql_query($select_rate);
		$row_rate = mysql_fetch_row($results_rate);
		
		return $row_rate;
	}
}
?>
<html>
<head>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="fun.js"></script>
</head>
<body>
  <div id="container" style="min-width:700px;height:400px"></div>
</body>
</html>
<script>
<?php
	//echo $Y_value;
?>
$(function () {
	var url_value = "instruction=IndexStore&store_number=T104113001_s1&month=2016-05;";
	ajax('post','indexphp.php',url_value,
		function fun(value){
		var data = JSON.parse(value);
		//alert (data['machine_total'][0][2]);
		
	for(var i=0;i<data['machine_total'].length;i++){
		
	
    $('#container').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Monthly Average Rainfall'
        },
        subtitle: {
            text: 'Source: WorldClimate.com'
        },
        xAxis: {
            categories: [
                'Jan',
                'Feb',
                'Mar',
                'Apr',
                'May',
                'Jun',
                'Jul',
                'Aug',
                'Sep',
                'Oct',
                'Nov',
                'Dec'
            ],
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Rainfall (mm)'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [
		
		 /* {
			name:data['machine_total'][i][1],
			data:[data['machine_total'][i][2],0,0,0,0,0,0,0,0,0,0,0]
		},  */
		
		{
            name: 'Tokyo',
            data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]

        }, {
            name: 'New York',
            data: [83.6, 78.8, 98.5, 93.4, 106.0, 84.5, 105.0, 104.3, 91.2, 83.5, 106.6, 92.3]

        }, {
            name: 'London',
            data: [48.9, 38.8, 39.3, 41.4, 47.0, 48.3, 59.0, 59.6, 52.4, 65.2, 59.3, 51.2]

        }, {
            name: 'Berlin',
            data: [42.4, 33.2, 34.5, 39.7, 52.6, 75.5, 57.4, 60.4, 47.6, 39.1, 46.8, 51.1]

        }
		]
    });
	}
	})
});
</script>