<?php
App::uses('AppController', 'Controller');
App::uses('CakeSchema', 'Model');
App::uses('ConnectionManager', 'Model');
App::uses('Inflector', 'Utility');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
/**
 * Openings Controller
 *
 * @property Opening $Opening
 */
class BalancingsController extends AppController {
	public $uses=array('Opening','Currency','OtherCurrency','PurchasedReceipt','SoldReceipt','Expense','Item','CashAtBankForeign','CashAtBankUgx','Debtors','Creditors','Fox');
	
	function beforeFilter() {
        parent::beforeFilter();		
        if ($this->action == 'show_cash_flow' || 
			$this->action == 'show_generally') {
			if($this->Auth->user('role')!='super_admin'){
				$this->Session->setFlash(__('Access Denied!!'));
				$this->redirect($this->Auth->logout());
			}
        }
    } 
	
	function backup(){
		$dataSourceName = 'default';
		date_default_timezone_set('Africa/Nairobi');
		$path = APP_DIR . DS .'Backups' . DS;

		$Folder = new Folder($path, true);
		
		$fileSufix = date('Ymd\_His') . '.sql';
		$file = $path . $fileSufix;
		if (!is_writable($path)) {
			trigger_error('The path "' . $path . '" isn\'t writable!', E_USER_ERROR);
		}
		
		//$this->out("Backuping...\n");
		$File = new File($file);

		$db = ConnectionManager::getDataSource($dataSourceName);

		$config = $db->config;
		$this->connection = "default";
		
		foreach ($db->listSources() as $table) {
		
			$table = str_replace($config['prefix'], '', $table);
			// $table = str_replace($config['prefix'], '', 'dinings');
			$ModelName = Inflector::classify($table);
			$Model = ClassRegistry::init($ModelName);
			$DataSource = $Model->getDataSource();
			$this->Schema = new CakeSchema(array('connection' => $this->connection));
			
			$cakeSchema = $db->describe($table);
			// $CakeSchema = new CakeSchema();
			$this->Schema->tables = array($table => $cakeSchema);
			
			$File->write("\n/* Drop statement for {$table} */\n");
			$File->write("SET foreign_key_checks = 0;");
			// $File->write($DataSource->dropSchema($this->Schema, $table) . "\n");
			$File->write($DataSource->dropSchema($this->Schema, $table));
			$File->write("SET foreign_key_checks = 1;\n");

			$File->write("\n/* Backuping table schema {$table} */\n");

			$File->write($DataSource->createSchema($this->Schema, $table) . "\n");

			$File->write("\n/* Backuping table data {$table} */\n");

		
			unset($valueInsert, $fieldInsert);

			$rows = $Model->find('all', array('recursive' => -1));
			$quantity = 0;
			
			if (sizeOf($rows) > 0) {
				$fields = array_keys($rows[0][$ModelName]);
				$values = array_values($rows);	
				$count = count($fields);

				for ($i = 0; $i < $count; $i++) {
					$fieldInsert[] = $DataSource->name($fields[$i]);
				}
				$fieldsInsertComma = implode(', ', $fieldInsert);

				foreach ($rows as $k => $row) {
					unset($valueInsert);
					for ($i = 0; $i < $count; $i++) {
						$valueInsert[] = $DataSource->value(utf8_encode($row[$ModelName][$fields[$i]]), $Model->getColumnType($fields[$i]), false);
					}

					$query = array(
						'table' => $DataSource->fullTableName($table),
						'fields' => $fieldsInsertComma,
						'values' => implode(', ', $valueInsert)
					);		
					$File->write($DataSource->renderStatement('create', $query) . ";\n");
					$quantity++;
				}

			}
			
			//$this->out('Model "' . $ModelName . '" (' . $quantity . ')');
		}
		$File->close();
		//$this->out("\nFile \"" . $file . "\" saved (" . filesize($file) . " bytes)\n");

		if (class_exists('ZipArchive') && filesize($file) > 100) {
			//$this->out('Zipping...');
			$zip = new ZipArchive();
			$zip->open($file . '.zip', ZIPARCHIVE::CREATE);
			$zip->addFile($file, $fileSufix);
			$zip->close();
			//$this->out("Zip \"" . $file . ".zip\" Saved (" . filesize($file . '.zip') . " bytes)\n");
			//$this->out("Zipping Done!");
			if (file_exists($file . '.zip') && filesize($file) > 10) {
				unlink($file);
			}
			//$this->out("Database Backup Successful.\n");
		}
	}
	
	public function show_cash_flow(){
		if(isset($_REQUEST['date_from']) && isset($_REQUEST['date_to'])){
			$date_from	=($_REQUEST['date_from']);
			$date_to	=($_REQUEST['date_to']);
			
			if(strtotime($date_to) < strtotime($date_from)){
				//Invalid date range
			}
			
			//recursion
			$this->Expense->recursive=-1;
			$this->Item->recursive=-1;
			$this->Opening->recursive=-1;
			
			//Item
			$items=$this->Item->find('all');
			
			$result=array('CashFlow'=>array(),'Dates'=>array());
			
			$loop_date_from=$date_from;
			while(strtotime($loop_date_from) <= strtotime($date_to)){
				array_push($result['Dates'],$loop_date_from);
				$result['CashFlow'][''.$loop_date_from]['items']=array();
				$result['CashFlow'][''.$loop_date_from]['others']=array();
				
				$total_items_expenses=0;
				foreach($items as $item){
					////get total expense for item on loop_date_from
					$expense=$this->Expense->find('all',array(
							'fields'=>array(
								'SUM(amount) as total_amount'
							),
							'conditions'=>array(
								'Expense.item_id'=>$item['Item']['id'],
								'Expense.date'=>$loop_date_from
							)
						)
					);
					$total_items_expenses+=$expense[0][0]['total_amount'];
					array_push($result['CashFlow'][''.$loop_date_from]['items'],$expense[0][0]['total_amount']);
				}
				
				$opening=$this->Opening->find('all',array(
					'fields'=>array(
						'SUM(total_gross_profit) as total_gross_profit'
					),
					'conditions'=>array(
						'opening.date'=>$loop_date_from
					)
				));
				
				array_push($result['CashFlow'][''.$loop_date_from]['others'],$opening[0][0]['total_gross_profit']);
				array_push($result['CashFlow'][''.$loop_date_from]['others'],$total_items_expenses);
				date_default_timezone_set('Africa/Nairobi');
				$loop_date_from=date('Y-m-d',strtotime("+1 day",strtotime($loop_date_from)));//move to next date
			}
			$this->set(compact('result','items'));
		}
	}
	
	public function save_opening(){
		if ($this->request->is('post')) {			
			
			if(!isset($this->request->data['Opening']['date'])){
				$this->Session->setFlash(__('Please select when the next opening will occur. Thanks.'));
				$this->redirect(array('action' => 'show_individually'));
			}
			
			
			$total_expenses				=	$this->Session->read('total_expenses');			
			$currencies					=	$this->Session->read('currencies');
			$other_currencies			=	$this->Session->read('other_currencies');
			$purchases					=	$this->Session->read('purchases');
			$other_currencies_purchases	=	$this->Session->read('other_currencies_purchases');
			$openings					=	$this->Session->read('openings');
			$sales						=	$this->Session->read('sales');	
			$other_currencies_sales		=	$this->Session->read('other_currencies_sales');	
			$cash_at_bank_foreign		=	$this->Session->read('cash_at_bank_foreign');	
			$cash_at_bank_ugx			=	$this->Session->read('cash_at_bank_ugx');	
			$debtors					=	$this->Session->read('debtors');	
			$creditors					=	$this->Session->read('creditors');	
			
			//Date validation
			$ts1 = strtotime($openings[0]['Opening']['date']);
			$ts2 = strtotime($this->request->data['Opening']['date']);
			$seconds_diff = $ts2 - $ts1;
			
			if($seconds_diff<=0){
				$this->Session->setFlash(__('Please select a date greater than today for the next opening. Thanks.'));
				$this->redirect(array('action' => 'show_individually'));
			}
			
			//Check for weekends
			$fox=$this->Session->read('fox');
			$weekends=explode(',',$fox['Fox']['weekends']);
			foreach($weekends as $weekend){
				if($ts2==strtotime($weekend)){
					$this->Session->setFlash(__('Please select a working day.'));
					$this->redirect(array('action' => 'show_individually'));
				}
			}
			
			if($seconds_diff<=0){
				$this->Session->setFlash(__('Please select a date greater than today for the next opening. Thanks.'));
				$this->redirect(array('action' => 'show_individually'));
			}
			
			
			
			$func=$this->Func;
			$this->request->data['Opening']['id']=$func->getUID1();
			$this->request->data['Opening']['date']=$this->request->data['Opening']['date'];
			
			$total_purchases_ugx=0;
			$total_purchases=0;
			$total_sales_ugx=0;
			$total_sales=0;
			$total_profits=0;
			$total_gross_profit=0;
			$total_todays_close=0;
			$total_todays_close_ugx=0;
			$receivable_cash=0;//(M)
			$withdrawal_cash=0;//(M)
			$additional_profits=0;//(M)
			$expenses=0;//(C)
			
			$receivable_cash = $this->request->data['Opening_old']['receivable_cash'];
			$withdrawal_cash = $this->request->data['Opening_old']['withdrawal_cash'];
			$additional_profits = $this->request->data['Opening_old']['additional_profits'];
			
			if(isset($total_expenses[0][0]['total_expenses']))
				$expenses=(double)$total_expenses[0][0]['total_expenses'];
			$count=-1;
			foreach($currencies as $currency):
				$count++;
				
				if($currency['Currency']['id']=='c8'){
					$other_count=-1;
					$_data=json_decode($openings[0]['Opening']['other_currencies']);
					
					$arr['data']=array();
					foreach($other_currencies as $other_currency){
						$other_count++;
						$_amount=$_rate=0;
						
						foreach($_data as $_other_currencies){
							foreach($_other_currencies as $_other_currency){	
								if(isset($_other_currency->CID)){
									if($_other_currency->CID==$other_currency['OtherCurrency']['id']){
										$_amount=$_other_currency->CAMOUNT;
										$_rate=$_other_currency->CRATE;
									}
								}
							}
						}
						
						$av_ugx=($other_currencies_purchases[$other_count]['total_amount']*$other_currencies_purchases[$other_count]['av_rate'])+(($_amount)*($_rate));
						$av_rate=($other_currencies_purchases[$other_count]['total_amount'])+($_amount);
						
						//New Av rate
						$av_close_rate = ($av_rate!=0)?$av_ugx/$av_rate:0;						
						
						//New amount left
						$todays_close=(($_amount)+($other_currencies_purchases[$other_count]['total_amount']))-($other_currencies_sales[$other_count]['total_amount']);
						
						
						$GP = $other_currencies_sales[$other_count]['total_amount']*($other_currencies_sales[$other_count]['av_rate']-$av_close_rate);
						
						$NP=($GP);		
						$total_gross_profit+=$GP;
						$total_profits+=$NP;
						
						$total_purchases+=$other_currencies_purchases[$other_count]['total_amount'];
						$total_sales+=$other_currencies_sales[$other_count]['total_amount'];
						
						$total_purchases_ugx+=$other_currencies_purchases[$other_count]['total_amount']*$other_currencies_purchases[$other_count]['av_rate'];
						$total_sales_ugx+=$other_currencies_sales[$other_count]['total_amount']*$other_currencies_sales[$other_count]['av_rate'];
						
						array_push($arr['data'],array(
							'CID'=>$other_currency['OtherCurrency']['id'],
							''.($other_currency['OtherCurrency']['id'])=>$other_currency['OtherCurrency']['id'],
							'CRATE'=>$av_close_rate,
							'CAMOUNT'=>$todays_close,
							'CNAME'=>$other_currency['OtherCurrency']['name'],
						));
						
					}
					$this->request->data['Opening']['other_currencies']=json_encode($arr);
			
				}//don't put else clause to allow saving in the others(c8) currency table
				
				$av_ugx=($purchases[$count]['total_amount']*$purchases[$count]['av_rate'])+(($openings[0]['Opening'][$currency['Currency']['id'].'a'])*($openings[0]['Opening'][$currency['Currency']['id'].'r']));
				$av_rate=($purchases[$count]['total_amount'])+($openings[0]['Opening'][$currency['Currency']['id'].'a']);
				
				//New Av rate
				$av_close_rate = ($av_rate!=0)?$av_ugx/$av_rate:0;				
				//Set New Av rate for saving as closing rate
				$this->request->data['Opening'][$currency['Currency']['id'].'r']=$av_close_rate;
				
				
				//New amount left
				$todays_close=(($openings[0]['Opening'][$currency['Currency']['id'].'a'])+($purchases[$count]['total_amount']))-($sales[$count]['total_amount']);
				//Set New amount for saving as closing amount for the foreign currency
				$this->request->data['Opening'][$currency['Currency']['id'].'a']=$todays_close;
				
				$GP = $sales[$count]['total_amount']*($sales[$count]['av_rate']-$av_close_rate);
				
				$NP=($GP);		
				if($currency['Currency']['id']!='c8'){
					$total_gross_profit+=$GP;
					$total_profits+=$NP;
					
					$total_purchases+=$purchases[$count]['total_amount'];
					$total_sales+=$sales[$count]['total_amount'];
					
					$total_purchases_ugx+=$purchases[$count]['total_amount']*$purchases[$count]['av_rate'];
					$total_sales_ugx+=$sales[$count]['total_amount']*$sales[$count]['av_rate'];
				}
				
			endforeach;
			
			$total_profits+=$additional_profits;//Include additional_profits
			
			//New cash at hand to be the opening cash for the next day selected
			$cash_at_hand=(($total_sales_ugx-($expenses)+$openings[0]['Opening']['opening_ugx']+$receivable_cash+$additional_profits)-($total_purchases_ugx+$withdrawal_cash));
			
			//Final cash at hand
			$cash_at_hand=($cash_at_hand)-($total_cash_at_bank_foreign+$total_cash_at_bank_ugx);
			$cash_at_hand_b=$cash_at_hand+$total_creditors;
			$cash_at_hand_e=$cash_at_hand_b-$total_debtors;
			$cash_at_hand_f=$cash_at_hand_e-$total_creditors;
			$cash_at_hand_g=$cash_at_hand_f+$total_debtors;
			
			$cash_at_hand=$cash_at_hand_g;
			
			/*$cash_at_hand=($cash_at_hand+$creditors)-($cash_at_bank_foreign+$cash_at_bank_ugx+$debtors);
			*/
			$this->request->data['Opening']['opening_ugx']=$cash_at_hand;
			if($this->Auth->User('role')=='super_admin' and isset($this->request->data['Opening_old']['user_id'])){
				$this->request->data['Opening']['user_id']=$this->request->data['Opening_old']['user_id'];
			}else{
				$this->request->data['Opening']['user_id']=$this->Auth->User('id');
			}
			
			$this->request->data['Opening']['status']=0;
			
			if ($this->Opening->save($this->request->data)) {
				$this->Session->setFlash(__('Saved'));
				
				$this->Opening->read(null, $openings[0]['Opening']['id']);
				
				$this->Opening->set('status', 				1);
				$this->Opening->set('total_profit', 		$total_profits);
				$this->Opening->set('total_gross_profit', 	$total_gross_profit);
				$this->Opening->set('total_expenses', 		$expenses);
				$this->Opening->set('receivable_cash', 		$receivable_cash);
				$this->Opening->set('withdrawal_cash', 		$withdrawal_cash);
				$this->Opening->set('total_purchases_ugx', 	$total_purchases_ugx);
				$this->Opening->set('total_sales_ugx', 		$total_sales_ugx);
				$this->Opening->set('additional_profits', 	$additional_profits);
				$this->Opening->set('cash_at_bank_foreign', $cash_at_bank_foreign);
				$this->Opening->set('cash_at_bank_ugx', 	$cash_at_bank_ugx);
				$this->Opening->set('debtors', 				$debtors);
				$this->Opening->set('creditors', 			$creditors);
				if(isset($this->request->data['Opening_old']['total_todays_close_ugx'])){
					$this->Opening->set('close_ugx', $this->request->data['Opening_old']['total_todays_close_ugx']);
				}
				
				if(!($this->Opening->save())){					
					$this->Opening->id = $this->request->data['Opening']['id'];
					$this->Opening->delete();
					$this->Session->setFlash(__('Not saved. Please, try again.'));
				}else{
					$this->Session->setFlash(__('Saved.'));
				}
			} else {
				$this->Session->setFlash(__('Not saved. Please, try again.'));
			}			
			$this->redirect(array('action' => 'show_individually'));
			
		}
		$this->Session->setFlash(__('Opening not saved. Please try again.'));
		$this->redirect(array('action' => 'show_individually'));
	}
	
	public function show_individually($receivable_cash=0,$withdrawal_cash=0,$additional_profits=0,$user_id=null) {
		date_default_timezone_set('Africa/Nairobi');
		$date_today=date('Y-m-d');
		if(isset($_REQUEST['date_today'])){
			$date_today	=($_REQUEST['date_today']);
		}
		
		$total_expenses=0;
		
		$total_expenses=$this->Expense->find('all',array(
			'recursive'=>-1,
			'fields'=>array(
				'SUM(Expense.amount) as total_expenses'					
			),
			'conditions'=>array(
				'Expense.user_id'=>(($this->Auth->User('role')=='super_admin' and $user_id)?$user_id:$this->Auth->User('id')),
				'Expense.date'=>$date_today
			)
		));	
		
		$openings=$this->Opening->find('all',array(
			'recursive'=>-1,'Limit'=>1,
			'conditions'=>array(
				'Opening.user_id'=>(($this->Auth->User('role')=='super_admin' and $user_id)?$user_id:$this->Auth->User('id')),
				'Opening.date'=>$date_today,
				//'Opening.status'=>0,//fetch only new openings
			),
			'order'=>'date desc'
		));
		
		
		$currencies=$this->Currency->find('all',array(
			'recursive'=>-1,
			'conditions'=>array(
				'NOT'=>array(
					'Currency.id'=>'c00'
				)				
			)
		));
		
		$other_currencies=$this->OtherCurrency->find('all',array('recursive'=>-1,));
		
		$purchases=array();
		$sales=array();
		$cash_at_bank_foreign=0;
		$cash_at_bank_ugx=0;
		$debtors=0;
		$creditors=0;
		$other_currencies_sales=array();
		$other_currencies_purchases=array();
		
		
		
		foreach($currencies as $currency){
			//Get for all other currencies
			if($currency['Currency']['id']=='c8'){
				
				foreach($other_currencies as $other_currency){
					$_other_currencies_purchases=$this->PurchasedReceipt->find('all',array(
						'recursive'=>-1,
						'fields'=>array(
							'SUM(PurchasedReceipt.orig_amount) as total_amount',
							'AVG(PurchasedReceipt.orig_rate) as av_rate'
						),
						'conditions'=>array(
							'PurchasedReceipt.currency_id'=>$currency['Currency']['id']	,
							'PurchasedReceipt.user_id'=>(($this->Auth->User('role')=='super_admin' and $user_id)?$user_id:$this->Auth->User('id')),
							'PurchasedReceipt.date'=>$date_today,
							'PurchasedReceipt.other_currency_id'=>$other_currency['OtherCurrency']['id']
						)
					));
					
					$_other_currencies_sales=$this->SoldReceipt->find('all',array(
						'recursive'=>-1,
						'fields'=>array(
							'SUM(SoldReceipt.orig_amount) as total_amount',
							'AVG(SoldReceipt.orig_rate) as av_rate'
						),
						'conditions'=>array(
							'SoldReceipt.currency_id'=>$currency['Currency']['id']	,
							'SoldReceipt.user_id'=>(($this->Auth->User('role')=='super_admin' and $user_id)?$user_id:$this->Auth->User('id')),
							'SoldReceipt.date'=>$date_today,
							'SoldReceipt.other_currency_id'=>$other_currency['OtherCurrency']['id']
						)
					));
					
					array_push($other_currencies_purchases,array('total_amount'=>$_other_currencies_purchases[0][0]['total_amount'],'av_rate'=>$_other_currencies_purchases[0][0]['av_rate']));
					array_push($other_currencies_sales,array('total_amount'=>$_other_currencies_sales[0][0]['total_amount'],'av_rate'=>$_other_currencies_sales[0][0]['av_rate']));
				}
			}
			$_purchases=$this->PurchasedReceipt->find('all',array(
				'recursive'=>-1,
				'fields'=>array(
					'SUM(PurchasedReceipt.amount) as total_amount',
					'AVG(PurchasedReceipt.rate) as av_rate'
				),
				'conditions'=>array(
					'PurchasedReceipt.currency_id'=>$currency['Currency']['id']	,
					'PurchasedReceipt.user_id'=>(($this->Auth->User('role')=='super_admin' and $user_id)?$user_id:$this->Auth->User('id')),
					'PurchasedReceipt.date'=>$date_today
				)
			));
			
			$_sales=$this->SoldReceipt->find('all',array(
				'recursive'=>-1,
				'fields'=>array(
					'SUM(SoldReceipt.amount) as total_amount',
					'AVG(SoldReceipt.rate) as av_rate'
				),
				'conditions'=>array(
					'SoldReceipt.currency_id'=>$currency['Currency']['id']	,
					'SoldReceipt.user_id'=>(($this->Auth->User('role')=='super_admin' and $user_id)?$user_id:$this->Auth->User('id')),
					'SoldReceipt.date'=>$date_today
				)
			));
			
			$_cashAtBankForeign=$this->CashAtBankForeign->find('all',array(
				'recursive'=>-1,
				'conditions'=>array(
					'CashAtBankForeign.currency_id'=>$currency['Currency']['id']	,
					'CashAtBankForeign.user_id'=>(($this->Auth->User('role')=='super_admin' and $user_id)?$user_id:$this->Auth->User('id')),
					'CashAtBankForeign.date'=>$date_today
				),
				'fields'=>array(
					'SUM(CashAtBankForeign.amount) as total_amount'
				)
			));
			
			array_push($purchases,array('total_amount'=>$_purchases[0][0]['total_amount'],'av_rate'=>$_purchases[0][0]['av_rate']));
			array_push($sales,array('total_amount'=>$_sales[0][0]['total_amount'],'av_rate'=>$_sales[0][0]['av_rate']));
			
			if(isset($_cashAtBankForeign[0][0]['total_amount'])){
				//$cash_at_bank_foreign+=$_cashAtBankForeign[0][0]['total_amount']*$_purchases[0][0]['av_rate'];
				$av_ugx=($_purchases[0][0]['total_amount']*$purchases[0][0]['av_rate'])+(($openings[0]['Opening'][$currency['Currency']['id'].'a'])*($openings[0]['Opening'][$currency['Currency']['id'].'r']));
				$av_rate=($purchases[0][0]['total_amount'])+($openings[0]['Opening'][$currency['Currency']['id'].'a']);
				
				//New Av Closing rate
				$av_close_rate= ($av_rate!=0)?$av_ugx/$av_rate:0;			
				
				$cash_at_bank_foreign+=$_cashAtBankForeign[0][0]['total_amount']*$av_close_rate;
			}
		}
		
		$_cashAtBankUgx=$this->CashAtBankUgx->find('all',array(
			'recursive'=>-1,
			'conditions'=>array(
				'CashAtBankUgx.user_id'=>(($this->Auth->User('role')=='super_admin' and $user_id)?$user_id:$this->Auth->User('id')),
				'CashAtBankUgx.date'=>$date_today
			),
			'fields'=>array(
				'SUM(CashAtBankUgx.amount) as total_amount'
			)
		));
		
		$_debtors=$this->Debtors->find('all',array(
			'recursive'=>-1,
			'conditions'=>array(
				'Debtors.user_id'=>(($this->Auth->User('role')=='super_admin' and $user_id)?$user_id:$this->Auth->User('id')),
				'Debtors.date'=>$date_today
			),
			'fields'=>array(
				'SUM(Debtors.amount) as total_amount'
			)
		));
		
		$_creditors=$this->Creditors->find('all',array(
			'recursive'=>-1,
			'conditions'=>array(
				'Creditors.user_id'=>(($this->Auth->User('role')=='super_admin' and $user_id)?$user_id:$this->Auth->User('id')),
				'Creditors.date'=>$date_today
			),
			'fields'=>array(
				'SUM(Creditors.amount) as total_amount'
			)
		));
		
		if(isset($_cashAtBankUgx[0][0]['total_amount'])){
			$cash_at_bank_ugx=$_cashAtBankUgx[0][0]['total_amount'];
		}
		if(isset($_debtors[0][0]['total_amount'])){
			$debtors=$_debtors[0][0]['total_amount'];
		}
		if(isset($_creditors[0][0]['total_amount'])){
			$creditors=$_creditors[0][0]['total_amount'];
		}
		
		
		$this->Session->write('total_expenses',$total_expenses);
		$this->Session->write('openings',$openings);
		$this->Session->write('currencies',$currencies);
		$this->Session->write('other_currencies',$other_currencies);
		$this->Session->write('purchases',$purchases);
		$this->Session->write('other_currencies_purchases',$other_currencies_purchases);
		$this->Session->write('sales',$sales);	
		$this->Session->write('other_currencies_sales',$other_currencies_sales);
		$this->Session->write('cash_at_bank_foreign',$cash_at_bank_foreign);			
		$this->Session->write('cash_at_bank_ugx',$cash_at_bank_ugx);			
		$this->Session->write('debtors',$debtors);			
		$this->Session->write('creditors',$creditors);	
		
		$this->set(compact('user_id','date_today','openings','currencies','purchases','sales','total_expenses','receivable_cash','withdrawal_cash','additional_profits','cash_at_bank_foreign','cash_at_bank_ugx','debtors','creditors','other_currencies_purchases','other_currencies_sales','other_currencies'));
		
	}
	
	public function show_generally() {
		date_default_timezone_set('Africa/Nairobi');
		$date_today=date('Y-m-d');
		if(isset($_REQUEST['date_today'])){
			$date_today	=($_REQUEST['date_today']);
		}
		
		$openings=$this->Opening->find('all',array(
			'recursive'=>1,'Limit'=>1,
			'conditions'=>array(
				'Opening.date'=>$date_today
				//'Opening.status'=>0,//fetch only new openings
			),
			'order'=>'Opening.date desc',
			/*'fields'=>array(
				'Opening.total_profit',
				'Opening.total_gross_profit',
				'Opening.total_expenses',
				'Opening.receivable_cash',
				'Opening.withdrawal_cash',
				'Opening.additional_profits',
				'Opening.total_purchases_ugx',
				'Opening.total_sales_ugx',
				'Opening.opening_ugx',	
				'Opening.cash_at_bank_foreign',	
				'Opening.cash_at_bank_ugx',	
				'Opening.debtors',	
				'Opening.creditors',
				'Opening.close_ugx',				
				'User.name'
			)*/
		));
		$other_currencies=$this->OtherCurrency->find('all',array('recursive'=>-1,));
		$this->set('openings',$openings);
		$this->set('other_currencies',$other_currencies);
	}
	
	public function show_generally_final() {
		date_default_timezone_set('Africa/Nairobi');
		$date_today=date('Y-m-d');
		if(isset($_REQUEST['date_today'])){
			$date_today	=($_REQUEST['date_today']);
		}
		
		$openings=$this->Opening->find('all',array(
			'recursive'=>0,'Limit'=>1,
			'order'=>'Opening.date desc',
			'fields'=>array(
				'SUM(Opening.total_profit) as total_profits',
				'SUM(Opening.total_expenses) as total_expenses'
			)
		));
		
		$fox=$this->Fox->find('first',array(
			'recursive'=>0,'Limit'=>1,
			'fields'=>array(
				'Fox.initial_position'
			)
		));
		
		$this->set('openings',$openings);
		$this->set('fox',$fox);
	}
}
