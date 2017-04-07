<?php
session_start();
require('..\Mysql\Mysql_Connect.php');
//$key = file_get_contents('C:\xampp\htdocs\luhao\Backup\BackupKey.txt'); //讀取BackupKey裡的文字檔
//$key = trim($key); 														//刪除左右兩邊的符號

//如果key不是Notkey表示在備份或是在做其他事情，所以不能對資料庫做存取的動作
/*if($key == 'NotKey'){*/
	$getjson = json_decode(file_get_contents('php://input'));     //接收串流訊息(json)

	/*判斷指令是直接用post傳
	或是用json透過post傳*/
	if(@$_POST['instruction'] != null){
		$instruction = $_POST['instruction'];
	}elseif(@$_POST['instruction'] == null && $getjson != null){
		foreach($getjson as $k=>$v){
			if($k == 'instruction'){
				$instruction = $v;
				break;
			}
		}
	}else{
		$instruction = null;
	}
	
	//判斷權限3為子帳號，所以要用父帳號取代子帳號才能顯示內容
	if($_SESSION['competence']== '1' || $_SESSION['competence']== '4'){
		$account = $_SESSION['account'];
	}else if($_SESSION['competence']== '3'){
		$account = $_SESSION['father'];
	}else{
		$account = null;
	}
	
	if($account !=null && $instruction != null){
		$machine_class = new machine();
		$post_account = @$_POST['account'];
		$store_number = @$_POST['store_number'];
		$machine_number = @$_POST['machine_number'];
		$month = @$_POST['month'];
		
		switch($instruction){
			//首頁
			case 'Index':
				$machine_class -> index($account,$month);
			break;
			//查詢帳號資料
			case 'Tenant':
				$tenant = $_POST['tenant'];
				$EchoJS['Information'] = $machine_class -> return_data('Account',$tenant);
				echo json_encode($EchoJS);
			break;
			//點擊商店顯示的資料
			case 'IndexStore':
				$machine_class -> index_store($store_number,$month);
			break;
			//顯示收入
			case 'ShowRevenue':
				$machine_class -> show_revenue_pay('external',$machine_number,'','revenue',$month);
			break;
			//顯示支出
			case 'ShowPay':
				$machine_class -> show_revenue_pay('external',$machine_number,'','pay',$month);
			break;
			//顯示品項
			case 'ShowCommodity':
				$EchoJS['commodity'] = $machine_class -> return_data('StoreMatchCommodity',$store_number);
				array_pop($EchoJS['commodity']);
				echo json_encode($EchoJS);
			break;
			//點擊機台顯示資料
			case 'IndexMachine':
				$machine_class -> index_machine($machine_number);
			break;
			//新增商店
			case 'InsertStore':
				$machine_class -> insert_update_store($getjson,'insert',$account);
			break;
			//新增機台
			case 'InsertMachine':
				$machine_class -> insert_update_machine($getjson,'insert');
			break;
			//新增租用者帳號
			case 'InsertAccount':
				$machine_class -> insert_update_account($getjson,'insert');
			break;
			//新增承租關係
			case 'InsertRelationship':
				$machine_class -> account_relationship('insert',$store_number,$machine_number,$post_accountt,'');
			break;
			//新增品項	
			case 'InsertCommodity': 				
				$machine_class -> commodity('insert',$getjson);
			break;
			//新增子帳號		
			case 'InsertSubAccount':
				$machine_class -> sub_account('insert',$getjson,$account);
			break;
			//修改店家
			case 'UpdateStore':
				$machine_class -> insert_update_store($getjson,'update',null);
			break;
			//修改租用者資料
			case 'UpdateAccount':
				$machine_class -> insert_update_account($getjson,'update');
			break;
			//修改機台
			case 'UpdateMachine':
				$machine_class -> insert_update_machine($getjson,'update');
			break;
			//修改承租關係
			case 'UpdateRelationship':
				$new_account = $_POST['new_account'];
				$machine_class -> account_relationship('update',$store_number,$machine_number,$post_account,$new_account);
			break;
			//修改兌幣機預警數量
			case 'UpdateCoinQuantity':
			    $machine_number = $_POST['machine_number'];
				$value_arr['quantity'] = $_POST['quantity'];
				$machine_class -> update_currency_exchange_preset($instruction,$machine_number,$value_arr);
			break;
			//修改兌幣機硬幣與紙鈔的比值
			case 'UpdateCB':
			    $machine_number = $_POST['machine_number'];
				$value_arr['coin'] = $_POST['coin'];
				$value_arr['banknote'] = $_POST['banknote'];
				$machine_class -> update_currency_exchange_preset($instruction,$machine_number,$value_arr);
			break;
			
			//修改品項	
			case 'UpdateCommodity':
				$machine_class -> commodity('update',$getjson);
			break;
			//修改子帳號		
			case 'UpdateSubAccount':
				$machine_class -> sub_account('update',$getjson,$account);
			break;
			//刪除承租關係
			case 'DeleteRelationship':
				$machine_class -> account_relationship('delete',$store_number,$machine_number,$post_account,'');
			break;
			//刪除品項	
			case 'DeleteCommodity':
				$machine_class -> commodity('delete',$getjson);
			break;
			//刪除子帳號		
			case 'DeleteSubAccount':
				$machine_class -> sub_account('delete',$getjson,$account);
			break;
			//檢查編號、帳號、租用狀況
			case 'CheckNumber':
				$condition_1 = @$_POST['condition_1'];
				$condition_2 = @$_POST['condition_2'];
				$table = @$_POST['table'];
				$machine_class -> check_number($condition_1,$condition_2,$table);
			break;
			case 'Logout':
				$machine_class -> logout();
			break;
			
		}
	}else{
		$EchoAccount['data'] = '登入失敗請重新登入';
		echo json_encode($EchoAccount);
	}
/*}else{
	$EchoStore['EchoDB'] = '系統維護中';
	echo json_encode($EchoStore);
}*/

class machine{
		
	//首頁
	function index($account,$month){
		$EchoJS = Array();				//儲存全部的資料
		$store_number_arr = Array();	//儲存帳號裡全部店家的資料
		$machine_number_arr = Array();	//儲存店家裡的全部的機台資料
		
		$store_number_arr = $this->return_data('AccountMatchStore',$account); //搜尋該帳號所有的店家
		if(count($store_number_arr) != 0){
			//每間店家都要做資料統計
			foreach($store_number_arr as $store_number){
				$machine_number_arr = $this->return_data('StoreMatchMachine',$store_number[0]);//搜尋該店家所有的機台
				$store = Array();				//儲存一間店家的顯示資料	
				$store[] = $store_number[0];	//店家編號
				$store[] = $store_number[1];	//店家名稱
				$chage_money_total = 0;			//店家當月總兌幣量(兌幣機)
				$revenue_total = 0;				//店家當月總營收(娃娃機)
				$pay_total = 0;					//店家當月總支出(娃娃機)
				$pay_deteil_arr = Array();		//店家當月總支出數量(合併所有店品項一樣的數量)(娃娃機)
				$pay_deteil_buffer = Array();	//店家當月全部店的總支出(娃娃機)
				$game_revenue_arr = Array();	//收入(遊戲機)
				$game_pay_arr = Array();		//支出+分成比(遊戲機)
				$game_total = 0;			    //小計金額(遊戲機)
				
				//每間店家下的所有機台都要做資料統計
				foreach($machine_number_arr as $machine_number){
					/*先判斷該店是否有兩個人擁有，兩個人擁有表示有租用者，有租用者就不用加入資料統計
					  再來判斷機種是兌幣或娃娃機*/
					if($this->machine_account_quantity($machine_number[0]) != 2){
						//判斷機種
						if(preg_match("/CM_/i",$machine_number[0])){
							$chage_money = $this->show_revenue_pay('internal',$machine_number[0],'','pay',$month);
							$chage_money_total += $chage_money; //兌幣統計
						}else if(preg_match("/GM_/i",$machine_number[0])){
							$game_revenue_arr = $this->show_revenue_pay('internal',$machine_number[0],'','revenue',$month);
							$game_pay_arr = $this->show_revenue_pay('internal',$machine_number[0],'','pay',$month);
							//計算小計
							$game_total += (($game_revenue_arr[0]+$game_revenue_arr[1])-($game_pay_arr[0]+$game_pay_arr[1]))*($game_pay_arr[2]/100);
						}else{
							
							$revenue = $this->show_revenue_pay('internal',$machine_number[0],'','revenue',$month);
							$revenue_total += $revenue;	//收入統計
							//支出有很多品項，所以要先搜尋該店家所有品項代碼，搜尋完再做數量及總額統計		
							$select_commodity = "select claw_pay_day.commodity_number 
												from claw_pay_day 
												where machine_number = '$machine_number[0]'
												group by claw_pay_day.commodity_number";
							$result_commodity = mysql_query($select_commodity);
							while($commodity_number_arr = mysql_fetch_row($result_commodity)){
								$pay_quantity_arr = $this->show_revenue_pay('internal',$machine_number[0],$commodity_number_arr[0],'pay',$month);
								//如果沒有資料，型態會是數字型態
								if(gettype($pay_quantity_arr) != 'integer'){
									$pay_total += $pay_quantity_arr[3]; //支出統計
									//array_pop($pay_quantity_arr);
									$pay_deteil_buffer[] = $pay_quantity_arr; //數量統計
								}
							}
						}
					}
				}
				//權限為1或父權限為1表示是娃娃機，否則為遊戲機
				if($_SESSION['competence'] == '1' || @$_SESSION['father_competence'] == '1'){
					$store[] = $revenue_total;
					$store[] = $pay_total;
					$store[] = $chage_money_total;
					//所有機台裡可能有重複的品項要做合併，要用另一個陣列
					//var_dump($pay_deteil_buffer);
					foreach($pay_deteil_buffer as $v){
						
						if(count($pay_deteil_arr) != 0 ){
							$match = 0; 
							//兩個陣列做比較，有一樣的合併，無則直接存入另一個陣列
							foreach($pay_deteil_arr as $k=>$arr){
								if($v[0] == $arr[0]){
									$pay_deteil_arr[$k][2] = $arr[2] + $v[2];
									$match = 1;
									break;
								}
							}
							//沒有一樣就要存入
							if($match == 0){
								$pay_deteil_arr[] = $v;
							} 
						}else{
							$pay_deteil_arr[] = $v;
						}
					}
					$store[] = $pay_deteil_arr;
					$commodity[] = $this->return_data('StoreMatchCommodity',$store_number[0]);					
				}else if($_SESSION['competence'] == '4' ||$_SESSION['father_competence'] == '4'){	
					$store[] = $game_total;
					$store[] = $chage_money_total;
				}
				
				$store_total[] = $store;
			}
		}else{
			$store = Array();
			$store_total[] = $store;
			$commodity = Array();
		}
		
		$EchoJS['Information'] = $this->return_data('Account',$account);												  //帳號詳細
		$EchoJS['store_total'] = $store_total;																			  //所有商店資訊
		if($_SESSION['competence'] == '1' || @$_SESSION['father_competence'] == '1') $EchoJS['commodity'] = $commodity;	  //娃娃機才有的品項詳細
		if($_SESSION['competence'] != '3')$EchoJS['sub_account'] = $this->return_data('AccountMatchSubAccount',$account); //該帳號底下所有子帳號
		
		echo json_encode($EchoJS);
	}

	//點擊商店顯示的資料
	function index_store($store_number,$month){
		$machine_number_arr = $this->return_data('StoreMatchMachine',$store_number);//搜尋該店家所有的機台
		if(count($machine_number_arr) != 0){
			//每間店家下的所有機台都要做資料統計
			foreach($machine_number_arr as $machine_number){
				$machine = Array();					//儲存一台機台的顯示資料
				$machine[] = $machine_number[0];	//機台編號
				$machine[] = $machine_number[1];	//機台名稱
				$pay_deteil = Array();				//機台當月總支出
				$game_total_arr = Array();			//收入+支出+分成比(遊戲機)
				$game_pay_arr = Array();			//支出+分成比(遊戲機)
				
				/*先判斷該店是否有兩個人擁有，兩個人擁有表示有租用者，有租用者就不用加入資料統計
				  再來判斷機種是兌幣或娃娃機*/
				if($this->machine_account_quantity($machine_number[0]) != 2){		
					//判斷機種
					if(preg_match("/CM_/i",$machine_number[0])){
						$chage_money = 0;			//機台當月總兌幣量
						$chage_money = $this->show_revenue_pay('internal',$machine_number[0],'','',$month);
						$machine[] = $chage_money;
					}else if(preg_match("/GM_/i",$machine_number[0])){
						$game_total_arr = $this->show_revenue_pay('internal',$machine_number[0],'','revenue',$month);
						$game_pay_arr = $this->show_revenue_pay('internal',$machine_number[0],'','pay',$month);
						$game_total_arr[] = $game_pay_arr[0];
						$game_total_arr[] = $game_pay_arr[1];
						$game_total_arr[] = $game_pay_arr[2];
						$machine[] = $game_total_arr;
					}else{
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
					}
					
				}else{
					$machine[] = '已出租';
					$machine[] = $pay_deteil;
				}
				//echo 'XXXXXXXXXXXXX';
				$machine_total[] = $machine;
			}
		}else{
			$machine = Array();				//儲存一台機台的顯示資料
			$machine_total[] = $machine;
		}
		
		$EchoJS['Information'] = $this->return_data('Store',$store_number);
		$EchoJS['machine_total'] = $machine_total;
		$EchoJS['commodity'] = $this->return_data('StoreMatchCommodity',$store_number);
		echo json_encode($EchoJS);
	}

	//點擊機台顯示資料
	function index_machine($machine_number){
		$account_arr = Array();
		$EchoJS = Array();
		$account = $this->return_data('MachineMatchAccount',$machine_number);
		
		$select_store = "select store_number 
						 from data_match 
						 where  machine_number = '$machine_number'
						 group by store_number";
		$result_store = mysql_query($select_store);
		$row_store = mysql_fetch_row($result_store);
		
		$select_account = "select data_match.account 
						   from data_match,account_other_information
						   where data_match.account = account_other_information.account
						   and store_number = '$row_store[0]'
						   and competence = '2'
						   group by data_match.account";
		$results_account = mysql_query($select_account);
		
		while($row_account = mysql_fetch_row($results_account)){
			
			$account_arr[] = $row_account[0];
		}	
		
		$EchoJS['Information'] = $this->return_data('Machine',$machine_number);
		//echo $EchoJS['Information'][2];
		count($account) != 0 ? $EchoJS['Account'] = $account[0] : $EchoJS['Account'] = '';
		$EchoJS['AllAccount'] = $account_arr;
		if(preg_match("/CM_/i",$machine_number)) $EchoJS['GCMQuantity'] = $this->return_data('CurrencyExchangeGCM',$machine_number);
		$EchoJS['CMCB'] = $this->return_data('CurrencyCB',$machine_number);
		echo json_encode($EchoJS);
	}

	//回傳使用者、店家、機台的資料、兌幣機推播數量、子帳號與權限(陣列儲存)
	function return_data($condition,$content){
		$EchoDB = Array();
		$EchoJS = Array();
		$month = date('Y-m');
		switch($condition){
			//透過帳號查詢帳號資料
			case 'Account' :
				$select = "select account_basic_information.account,name,password,address,phone,email,competence
						   from account_basic_information,account_other_information
						   where account_basic_information.account = account_other_information.account
						   and account_basic_information.account = '$content'";
				break;
			//透過機台代碼查詢帳號	
			case 'MachineMatchAccount' :
				$select = "select account_other_information.account 
						   from machine_data,data_match,account_other_information 
						   where data_match.account = account_other_information.account 
						   and machine_data.machine_number = data_match.machine_number
						   and machine_data.machine_number = '$content' 
						   and competence = '2'";
				break;
			//透過父帳號查詢子帳號與權限	
			case 'AccountMatchSubAccount' :
				$select = "select sub_account_competence.account,password,name,address,phone,email,
								  insert_store,insert_machine,insert_commodity,import_commodity,change_account 
							   from sub_account_competence,account_basic_information,account_other_information
							   where sub_account_competence.account = account_basic_information.account
							   and account_basic_information.account = account_other_information.account
							   and father = '$content'";
				break;			
			//透過店家代碼查詢店家資料		
			case 'Store' :
				$select = "select store_number,name,tel,address,store_class
					   from store_data
					   where store_data.store_number = '$content'";					
				break;
			//透過帳號查詢店家資料
			case 'AccountMatchStore' :
				$select = "select store_data.store_number,name,tel,address,ip_address
						   from data_match,store_data
						   where data_match.store_number = store_data.store_number
						   and account = '$content'
						   group by data_match.store_number
						   order by id";
				break;
			//透過店家查詢店家品項資料
			case 'StoreMatchCommodity' :
				$select = "select commodity_number,name,unit
						   from claw_commodity
						   where  store_number = '$content'";
				break;	
			//透過機台代碼查詢機台資料
			case 'Machine' :
				$select = "select machine_number,machine_name,virtual_revenue,virtual_pay
						   from machine_data
						   where machine_number = '$content'";					
				break;
			//透過店家代碼查詢機台資料
			case 'StoreMatchMachine' :
				$select = "select machine_data.machine_number,machine_name
						   from data_match,machine_data
						   where data_match.machine_number = machine_data.machine_number
						   and store_number = '$content'
						   group by machine_number";
				break;
			//查詢兌幣機預警數量
			case 'CurrencyExchangeGCM' :
				$select = "select quantity
						   from currency_exchange_gcm
						   where machine_number = '$content'
						   and quantity != '0'";
				break;
			case 'CurrencyCB' :
				$select = "select coin,banknote
						   from currency_exchange_preset
						   where machine_number = '$content'";
		}
		
		$select_results = mysql_query($select);
		//echo $condition.':'.$select.chr(0X0A).chr(0X0A);
		if($condition == 'Account' || $condition == 'Machine' || $condition == 'Store' || $condition == 'MachineMatchAccount' || $condition == 'CurrencyExchangeGCM' || $condition == 'Tenant' || $condition == 'CurrencyCB'){
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
		}else if($condition == 'AccountMatchSubAccount'){
			while($select_row = mysql_fetch_assoc($select_results)){
				$EchoDB[] = $select_row;
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
		
		//先判斷機種
		if(preg_match("/CM_/i",$machine_number)){
			$sql = "select * 
					from currency_exchange_change_quantity 
					where machine_number = '$machine_number'
					and insert_time >= '$month_begin'
					and insert_time < '$month_end'
					and status != '2'
					order by insert_time desc";
			$select_ratio = "select banknote,coin from currency_exchange_preset where machine_number = '$machine_number'";		
			$result_ratio = mysql_query($select_ratio);
			$results = mysql_query($sql);
			$row_ratio = mysql_fetch_row($result_ratio);
			/*如果$application等於internal則是內部使用營收金額
			  如果$application等於quantity則是內部使用營收枚數
			  否則就是外部使用營收金額*/
			if($application == 'internal'){
				while($row = mysql_fetch_row($results)){
					if($row[2] == 'banknote'){
						$total_quantity += $row[3] * $row_ratio[0];
					}else{
						$total_quantity += $row[3] * $row_ratio[1];
					}
				}			
				return $total_quantity;
			}else if($application == 'quantity'){
				if($condition == 'banknote'){
					$sql_change_money = "select sum(quantity) from currency_exchange_change_quantity
														  where machine_number = '$machine_number'
														  and ratio = 'banknote'";
				}else{
					$sql_change_money = "select sum(quantity) from currency_exchange_change_quantity
														  where machine_number = '$machine_number'
														  and ratio = 'coin'";
				}
				
				$result_change_money = mysql_query($sql_change_money);
				$row_change_money = mysql_fetch_row($result_change_money);
				return $row_change_money[0];
			}else{
				while($row = mysql_fetch_row($results)){
					$reset_data = array();
					$reset_data[] = $row[2];
					if($row[2] == 'banknote'){
						$reset_data[] = $row[3] * $row_ratio[0];
					}else{
						$reset_data[] = $row[3] * $row_ratio[1];
					}
					$reset_data[] = $row[4];
					$reset_data[] = $row[5];
					$EchoDB[] = $reset_data;
				}
				$EchoJS['EchoDB'] = $EchoDB;
				echo json_encode($EchoJS);
			}
		
		}else if(preg_match("/GM_/i",$machine_number)){
			$other_rate = $this->check_rate($machine_number,'game');								//key_coin,pay,key_out幣值,百分比,key_coin,key_out虛擬錶預設值
			if($condition == 'revenue'){
				$revenue_arr = Array();
				$rate_total = 0;
				$key_coin_total = 0;
				$select_revenue = "select * from game_revenue
											where machine_number = '$machine_number'
											and insert_time >= '$month_begin'
											and insert_time < '$month_end'
											order by insert_time desc";
				$results_revenue = mysql_query($select_revenue);
				$rate = $this->check_rate($machine_number,'machine_number');						//投幣幣值
				
				if($application == 'internal'){
					while($row_revenue = mysql_fetch_row($results_revenue)){
						if($row_revenue[2] == 'rate'){
							$rate_total += $row_revenue[3] * $rate[0];
						}else{
							$key_coin_total += $row_revenue[3] * $other_rate[0];
						}
					}
					$revenue_arr[] = $rate_total;
					$revenue_arr[] = $key_coin_total;
					return $revenue_arr;
				}else if($application == 'quantity'){
					while($row_revenue = mysql_fetch_row($results_revenue)){
						if($row_revenue[2] == 'rate'){
							$rate_total += $row_revenue[3];
						}else{
							$key_coin_total += $row_revenue[3];
						}
					}
					$revenue_arr[] = $rate_total;
					$revenue_arr[] = $key_coin_total;
					return $revenue_arr;
				}else{
					while($row_revenue = mysql_fetch_row($results_revenue)){
						$reset_data = Array();
						$reset_data[] = $row_revenue[2];
						if($row_revenue[2] == 'rate'){
							$reset_data[] = $row_revenue[3] * $rate[0];
						}else{
							$reset_data[] = $row_revenue[3] * $other_rate[0];
						}
						$reset_data[] = $row_revenue[4];
						$reset_data[] = $row_revenue[5];
						$EchoDB[] = $reset_data;
					}
					
					$EchoJS['EchoDB'] = $EchoDB;
					echo json_encode($EchoJS);
				}
			}else{
				$pay_arr = Array();
				$pay_total = 0;
				$key_out_total = 0;
				$select_pay = "select * from game_pay
										where machine_number = '$machine_number'
										and insert_time >= '$month_begin'
										and insert_time < '$month_end'
										order by insert_time desc";
				$results_pay = mysql_query($select_pay);						
				if($application == 'internal'){
					while($row_pay = mysql_fetch_row($results_pay)){
						if($row_pay[2] == 'pay'){
							$pay_total += $row_pay[3] * $other_rate[1];
						}else{
							$key_out_total += $row_pay[3] * $other_rate[2];
						}
					}
					$pay_arr[] = $pay_total;
					$pay_arr[] = $key_out_total;
					$pay_arr[] = $other_rate[3];
					return $pay_arr;
				}else if($application == 'quantity'){
					while($row_pay = mysql_fetch_row($results_pay)){
						if($row_pay[2] == 'pay'){
							$pay_total += $row_pay[3];
						}else{
							$key_out_total += $row_pay[3];
						}
					}
					$pay_arr[] = $pay_total;
					$pay_arr[] = $key_out_total;
					$pay_arr[] = $other_rate[3];
					$pay_arr[] = $other_rate[4];
					$pay_arr[] = $other_rate[5];
					return $pay_arr;
				}else{
					while($row_pay = mysql_fetch_row($results_pay)){
						$reset_data = Array();
						$reset_data[] = $row_pay[2];
						if($row_pay[2] == 'quantity'){
							$reset_data[] = $row_pay[3] * $rate[0];
						}else{
							$reset_data[] = $row_pay[3] * $other_rate[0];
						}
						$reset_data[] = $row_pay[4];
						$reset_data[] = $row_pay[5];
						$EchoDB[] = $reset_data;
					}
					
					$EchoJS['EchoDB'] = $EchoDB;
					echo json_encode($EchoJS);
				}
			}
		}else{
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
	}

	//查詢機台擁有者數量
	function machine_account_quantity($machine_number){
		$sql = "select count(account) 
				from data_match 
				where machine_number = '$machine_number'";
		$results = mysql_query($sql);
		$row_count = mysql_fetch_row($results);
		return $row_count[0];
	}

	//檢查編號、帳號、租用狀況
	function check_number($condition_1,$condition_2,$table){

			switch($table){
				//查詢帳號
				case '1':
					$sql_num = "select *
							from account_basic_information
							where account = '$condition_1'";
				break;
				
				//查詢機台代碼
				case '2':
					$sql_num = "select *
							from machine_data
							where binary machine_number = '$condition_1'";
				break;
				
				//查詢出租狀況
				case '3':
					$sql_num = "select *
							from data_match
							where store_number = '$condition_1'
							and binary machine_number = '$condition_2'";
				break;
				
				//查詢email
				case '4':
					$sql_num = "select *
							from account_basic_information
							where email = '$condition_1'";
				break;
			}
			
			$result_num = mysql_query($sql_num);
			$row_num = mysql_num_rows($result_num);
			//var_dump($condition_1);
			//var_dump($condition_2);
			/*有第二個條件 而且是no_machine表示該商店沒有機台
			  table = 3 表示要查詢的是出租狀況除了1筆是媒人租過以外其他都是錯的意思
			  table等於其他表示是查詢是否有重複的唯一碼*/
			if($condition_2 == 'no_machine'){
				$EchoDB[] = 0;
			}else if($table == '3'){
				$row_num == 1 ?  $EchoDB[] = 1 : $EchoDB[] = 0;
			}else if($table == '2'){
				$account = $_SESSION['account'];
				//查詢該帳號與機台代碼是否有配對，無則不能新增
				$check_machine = "select * from account_machine_check 
										   where account = '$account'
										   and binary machine_number = '$condition_1'";
				$result_check_machine = mysql_query($check_machine);
				$check_machine_num = mysql_num_rows($result_check_machine);
				if($row_num == 0 && $check_machine_num == 1){
					$EchoDB[] = 1;
				}else{
					$EchoDB[] = 0;
				}
				//$row_num == 0 ?  $EchoDB[] = 1 : $EchoDB[] = 0;	
			}else{
				$row_num == 0 ?  $EchoDB[] = 1 : $EchoDB[] = 0;	
			}
			
			$EchoJS['EchoDB'] = $EchoDB;
			echo json_encode($EchoJS);
			
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

	//新增與修改店家
	function insert_update_store($getjson,$InorUp,$account){
		$EchoJS = array();
		foreach($getjson as $k=>$v){
			if($k == 'AccOrSto') $AccOrSto = $v;
			if($k == 'name') $name = $v;
			if($k == 'store_class') $store_class = $v;
			if($k == 'tel') $tel = $v;
			if($k == 'address') $address = $v;
		}
		
		if($InorUp == 'insert'){
			foreach($name as $k=>$v){
				/*搜尋店家編號，
				  編碼用*/
				$account_add = $AccOrSto.'_s';
				//echo $account_add.'<br>';
				$sql_num = "select store_number
							from store_data
							where store_number like '$account_add%'
							order by id ";
				$result_num = mysql_query($sql_num);
				/*編碼用，搜尋出該帳戶的店家資料表裡面的所有店家編碼，然後取出編碼最後的數字
				  一個一個配對，當最後的數字與逐一配對的數字不一樣時表示沒用過所以可以用*/				
				$count = 1;
				while($row_num = mysql_fetch_row($result_num)){
				//echo $row_num[0].'<br>';
					$store_arr = explode('_s',$row_num[0]);
					//var_dump($store_arr);
					if($count != $store_arr[1]) break;
					$count++;
				}
				$store_number = $AccOrSto.'_s'.$count;
				//echo $store_number.'XXX';			
				$rate = $this->check_rate($account,'account');						//幣值
				$insert_data = "insert into store_data(id,store_number,name,address,tel,store_class,rate)
												values('$count','$store_number','$v','$address[$k]','$tel[$k]','$store_class','$rate[0]')";
				$insert_select = "insert into data_match(account,store_number,machine_number)
											  values('$AccOrSto','$store_number','no_machine')";
				
				//echo $insert_data;
				if(mysql_query($insert_data)){
						$data[] = 1;
					if(mysql_query($insert_select)){
						$select[] = 1; 
					}else{
						$select[] = 0;
						echo mysql_error();
					}
				}else{
					$data[] = 0;
					$select[] = 0;
					echo mysql_error();
				}
				
			}	
		}else if($InorUp == 'update'){
			$update = "update store_data set name = '$name',address = '$address',tel = '$tel'
										 where store_number = '$AccOrSto'";
			if(mysql_query($update)){
				$data[] = 1;
				$select[] = 1;
			}else{
				$data[] = 0;
				$select[] = 0;
			}							 
		}else{
			$data[] = 0;
			$select[] = 0;
		}
		
		$EchoJS['name'] = $name;
		$EchoJS['data'] = $data;
		$EchoJS['select'] = $select;
		//var_dump($EchoJS);
		echo json_encode($EchoJS);
	}

	//新增與修改機台
	function insert_update_machine($getjson,$InorUp){
		$EchoJS = array();
		foreach($getjson as $k=>$v){
			if($k == 'account') $account = $v;
			if($k == 'store') $store_number = $v;
			if($k == 'name') $machine_name = $v;
			if($k == 'machine') $end_number = $v;
			if($k == 'title_number') $title_number = $v;
			if($k == 'virtual_revenue') $virtual_revenue = $v;
			if($k == 'virtual_pay') $virtual_pay = $v;
			if($k == 'key_coin') $key_coin = $v;
			if($k == 'key_out') $key_out = $v;
			if($k == 'pay') $pay = $v;
			if($k == 'percent') $percent = $v;
			if($k == 'key_coin_meter') $key_coin_meter = $v;
			if($k == 'key_out_meter') $key_out_meter = $v;
		}
		
		if($InorUp == 'insert'){
			foreach($end_number as $k=>$v){
			  $machine_number=@$title_number[$k].$v;
				$sql_select = "select *
							   from data_match 
							   where account = '$account'
							   and store_number = '$store_number'
							   and machine_number = 'no_machine'";
		
				$sql_results = mysql_query($sql_select);
				$sql_row = mysql_num_rows($sql_results);
				
				if($sql_row == 1){
					$IorU_select = "update data_match set machine_number = '$machine_number'
														  where account = '$account'
														  and store_number = '$store_number'";
				}else{
					$IorU_select = "insert into data_match(account,store_number,machine_number)
												values('$account','$store_number','$machine_number')";
											  
				}
				$insert_data = "insert into machine_data(machine_number,machine_name,virtual_revenue,virtual_pay)
											value('$machine_number','$machine_name[$k]','$virtual_revenue[$k]','$virtual_pay[$k]')";
				if(mysql_query($insert_data)){
					$data[] = 1;
					if(mysql_query($IorU_select)){
						$select[] = 1;
						if(@$title_number[$k] == 'CM_'){
							$insert_preset = "insert into currency_exchange_preset(machine_number)value('$machine_number')";
							$insert_gcm_quantity = "insert into currency_exchange_gcm(machine_number,quantity)
																value('$machine_number','0'),
																	 ('$machine_number','500')";
							if(! mysql_query($insert_preset)) $select[] = 0;
							if(! mysql_query($insert_gcm_quantity)) $select[] = 0;
						}else if((@$title_number[$k] == 'GM_')){
							$insert_game_ratio_meter = "insert into game_ratio_meter(machine_number,key_coin,pay,key_out,percent,key_coin_meter,key_out_meter)
																	values('$machine_number','$key_coin[$k]','$pay[$k]','$key_out[$k]','$percent[$k]','$key_coin_meter[$k]','$key_out_meter[$k]')";
							if(! mysql_query($insert_game_ratio_meter)) $select[] = 0;
						}
					}else{
						$select[] = 0;
						echo mysql_error();
					}
					
				}else{
					$data[] = 0;
					$select[] = 0;
					echo mysql_error();
				}
				$name[] = $machine_name[$k];
			}
		}else if($InorUp == 'update'){
			$update_machine = "update machine_data set machine_name = '$machine_name',virtual_revenue = '$virtual_revenue', virtual_pay = '$virtual_pay'
												   where machine_number = '$end_number'";
			if(mysql_query($update_machine)){
				$name = $end_number;
				$data = 1;
				$select = 1;
				if(preg_match("/GM_/i",$end_number)){
					$update_game_ratio_meter = "update game_ratio_meter set percent = '$percent',key_coin_meter = '$key_coin_meter', key_out_meter = '$key_out_meter'";
					if(! mysql_query($update_game_ratio_meter)){
						$data = 0;
						$select = 0;
					}
				}
			}else{
				$name = $end_number;
				$data = 0;
				$select = 0;
			}
		}else{
			$name = 'null';
			$data = 0;
			$select = 0;
			
		}	
		
		$EchoJS['name'] = $name;
		$EchoJS['data'] = $data;
		$EchoJS['select'] = $select;
		//var_dump($EchoJS);
		echo json_encode($EchoJS);
	}

	//新增與修改帳號
	function insert_update_account($getjson,$InorUp){
		$EchoJS = Array();
		foreach($getjson as $k=>$v){
			if($k == 'account') $account = $v;
			if($k == 'name') $name = $v;
			if($k == 'phone') $phone = $v;
			if($k == 'email') $email = $v;
			if($k == 'address') $address = $v;
			if($k == 'pw') $password = $v;
			if($k == 'store_number') $store_number = $v;
			if($k == 'machine_number') $machine_number = $v;
		}
		
		if($InorUp == 'insert'){
			$account_basic_information = "insert into account_basic_information(account,name,address,phone,email)
										 values('$account','$name','$address','$phone','$email')";
			$account_other_information = "insert into account_other_information(account,password,competence)
										 values('$account','$password','2')";
			$data_match = "insert into data_match(account,store_number,machine_number)
										 values('$account','$store_number','$machine_number')";
			if(mysql_query($account_basic_information)){
				if(mysql_query($account_other_information)){
					if(mysql_query($data_match)){
						$message = 1;
					}else{
						$message = 'insert data_match failure'.mysql_error();
					}
				}else{
					$message = 'insert account_other_information failure'.mysql_error();
				}
			}else{
				$message = 'insert account_basic_information failure'.mysql_error();
			}	
		}else{
			$account_basic_information = "update account_basic_information 
							 set name = '$name',address = '$address',phone = '$phone',email = '$email'
							 where account = '$account'";
			$account_other_information = "update account_other_information
								 set password = '$password'
								 where account = '$account'";
			if(mysql_query($account_basic_information)){
				if(mysql_query($account_other_information)){
					$message = 1;
				}else{
					$message = 'update account_other_information failure'.mysql_error();
				}
			}else{
				$message = 'update account_basic_information failure'.mysql_error();
			}
		}
		
		$EchoJS['name'] = $name;
		$EchoJS['message'] = $message;
		echo json_encode($EchoJS);
	}

	//新增、修改、刪除品項
	function commodity($method,$getjson){
		$EchoJSON = array();
		$EchoDB = array();		
		
		foreach($getjson as $k=>$v){
			if($k == 'store_number') $store_number = $v;    	  //接收商店代碼
			if($k == 'name') $name = $v;               			  //接收品項名稱
			if($k == 'commodity_number') $commodity_number = $v;  //接收品項代碼
			if($k == 'unit') $unit = $v;			   			  //接收品項單價
		}
		switch($method){
			case 'insert':
			
				foreach($name as $k=>$v){
					/*搜尋品項編號，
					  編碼用*/
					$sql_num = "select commodity_number
								from claw_commodity
								where store_number = '$store_number'
								order by id";
					$result_num = mysql_query($sql_num);
					/*編碼用，搜尋出該店家的品項資料表裡所有品項編碼，然後取出編碼最後的數字
					  一個一個配對，當最後的數字與逐一配對的數字不一樣時表示沒用過所以可以用*/				
					$count = 1;
					while($row_num = mysql_fetch_row($result_num)){
						$commodity_arr = explode('_c',$row_num[0]);
						if($count != $commodity_arr[1]) break;
						$count++;
					}
					$commodity_number = $store_number.'_c'.$count;		//可用的品項代碼
					
					$sql = "insert into claw_commodity(id,store_number,commodity_number,name,unit)
										values('$count','$store_number','$commodity_number','$v','$unit[$k]')";
					mysql_query($sql) ? $EchoDB[] = 1 : $EchoDB[] = 0;
				}
				
				break;
			case 'update':
				$sql = "update claw_commodity set name = '$name', unit ='$unit'
						where store_number = '$store_number'
						and commodity_number = '$commodity_number'";
				break;
			case 'delete':
				$sql = "delete from claw_commodity
						where commodity_number = '$commodity_number'
						and store_number = '$store_number'";
				break;	   
		}
		if($method != 'insert') mysql_query($sql) ? $EchoDB[] = 1 : $EchoDB[] = 0;
		 
		$EchoJSON['EchoDB'] = $EchoDB;
		echo json_encode($EchoJSON);
	}

	//新增、修改、刪除子帳號
	function sub_account($method,$getjson,$account){
		$register = date('Y-m-d H:i:s');
		$EchoJSON = array();
		$EchoDB = array();
		
		foreach($getjson as $k=>$v){
			if($k == 'subaccount') $subaccount = $v;    //接收帳號
			if($k == 'subname') $subname = $v;          //接收姓名
			if($k == 'subpassword') $subpassword = $v;  //接收密碼
			if($k == 'subphone') $subphone = $v;		//接收手機
			if($k == 'subaddress') $subaddress = $v;    //接收地址
			if($k == 'subemail') $subemail = $v;        //接收信箱
		}
		
		switch($method){
			case 'insert' :
				foreach($subaccount as $k => $v){
					$insert_account_data = "insert into account_basic_information(account,name,phone,address,email,register)
																values('$v','$subname[$k]','$subphone[$k]','$subaddress[$k]','$subemail[$k]','$register')";
					$insert_account_password = "insert into account_other_information(account,password,competence)
															values('$v','$subpassword[$k]','3')";
					$insert_sub_account = "insert into sub_account_competence(account,father)
															values('$v','$account')";										
					if(mysql_query($insert_account_data)){
						if(mysql_query($insert_account_password)){
							if(mysql_query($insert_sub_account)){
								$EchoDB[] = 1;
							}else{
								$EchoDB[] = 'insert sub_account_competence failure'.mysql_error();
							}
						}else{
							$EchoDB[] = 'insert account_other_information failure'.mysql_error();
						}
					}else{
						$EchoDB[] = 'insert account_basic_information failure:'.mysql_error();
					}			
				}
			break;
			
			case 'update' :
				$update_data = "update account_basic_information set name = '$subname', phone = '$subphone', address = '$subaddress',
														email = '$subemail', register = '$register'
													where account = '$subaccount'";
				$update_password = "update account_other_information set password = '$subpassword'
															where account = '$subaccount'";
				if(mysql_query($update_password)){
					if(mysql_query($update_data)){
						$EchoDB[] = 1;
					}else{
						$EchoDB[] = 'update account_basic_information failure'.mysql_error();
					}
				}else{
					$EchoDB[] = 'update account_other_information failure'.mysql_error();
				}
			break;
			
			case 'delete' :
				$delete_data = "delete from account_basic_information where account = '$subaccount'";
				$delete_password = "delete from account_other_information where account = '$subaccount'";
				$delete_sub_account = "delete from sub_account_competence where account = '$subaccount'";
				if(mysql_query($delete_sub_account)){
					if(mysql_query($delete_password)){
						if(mysql_query($delete_data)){
							$EchoDB[] = 1;
						}else{
							$EchoDB[] = 'delete account_basic_information failure'.mysql_error();
						}
					}else{
						$EchoDB[] = 'delete account_other_information failure'.mysql_error();
					}
				}else{
					$EchoDB[] = 'delete sub_account_competence failure'.mysql_error();
				}
			break;
		}
		
		$EchoJSON['EchoDB'] = $EchoDB;
		echo json_encode($EchoJSON);
	}
		
	//修改兌幣機預警推播數量、訊好與數量的比例
	function update_currency_exchange_preset($instruction,$machine_number,$value_arr){
		$EchoJS = array();
		if($instruction == 'UpdateCoinQuantity'){
			$sql = "update currency_exchange_gcm set quantity = '$value_arr[quantity]' 
												 where machine_number = '$machine_number' 
												 and quantity != '0'";	
		}else if($instruction == 'UpdateCB'){
			$sql = "update currency_exchange_preset set coin = '$value_arr[coin]' , banknote = '$value_arr[banknote]'
													where machine_number = '$machine_number'";
		}
		
		
		if(mysql_query($sql)){
			$message = 'success';
		}else{
			$message = 'failure';
		}
		$EchoJS['message'] = $message;
		echo  json_encode($EchoJS);
	}

	//刪除或新增承租關係
	function account_relationship($condition,$store_number,$machine_number,$account,$new_account){
		if($condition == 'delete'){
			$sql = "delete from data_match 
						   where store_number = '$store_number'
						   and machine_number = '$machine_number'
						   and account = '$account'";
		}else if($condition == 'insert'){
			$sql = "insert into data_match(store_number,machine_number,account)
								values('$store_number','$machine_number','$account')";
		}else{
			$sql = "update data_match set account = '$new_account'
										  where account = '$account'
										  and store_number = '$store_number'
										  and machine_number = '$machine_number'";
		}
		
		if(mysql_query($sql)){
			$message = 1;
			//$message = $sql;
		}else{
			$message = $condition.'failure:'.mysql_error();
		}
		$EchoJS['message'] = $message;
		echo json_encode($EchoJS);
	}

	//登出
	function logout(){
		setcookie("name","",time()-24*60*60);
		setcookie("user","",time()-24*60*60);
		unset($_SESSION['account']);
		unset($_SESSION['competence']);
		unset($_SESSION['father']);
		session_destroy();
		
	}	

}

?>