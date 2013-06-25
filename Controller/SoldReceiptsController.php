<?php
App::uses('AppController', 'Controller');
/**
 * SoldReceipts Controller
 *
 * @property SoldReceipt $SoldReceipt
 */
class SoldReceiptsController extends AppController {
	
	function beforeFilter() {
        parent::beforeFilter();
        if ($this->action == 'edit' || $this->action == 'delete') {
			if($this->Auth->user('role')!='super_admin'){
				$this->Session->setFlash(__('Access Denied!!'));
				$this->redirect($this->Auth->logout());
			}
        }
    }

	function print_receipt($id){
		if (!$this->SoldReceipt->exists($id)) {
			throw new NotFoundException(__('Invalid sales receipt'));
		}
		
		$this->request->data['SoldReceipt']['id']=$id;
		$this->request->data['SoldReceipt']['status']=0;
		if ($this->SoldReceipt->save($this->request->data)) {
				$this->set('resp','Sent for printing.');
		} else {
			$this->set('resp','Not Sent for printing.');
		}
	}
	
	function should_upload($id,$indicator){
		if (!$this->SoldReceipt->exists($id)) {
			throw new NotFoundException(__('Invalid sales receipt'));
		}
		
		$this->request->data['SoldReceipt']['id']=$id;
		if($indicator==0 || $indicator==1){
			$this->request->data['SoldReceipt']['is_uploaded']=$indicator;
		}
		$this->SoldReceipt->save($this->request->data);		
		$this->redirect(array('action'=>'index'));
	}
	
	public function upload(){
		$options = array('conditions' => array('SoldReceipt.is_uploaded'=>0));
		$this->set('receipt_count', $this->SoldReceipt->find('count', $options));
	}
	
	public function get_new_receipts_count(){
		$this->set('count_new_receipts',$this->SoldReceipt->find('count',array('conditions'=>array('SoldReceipt.is_uploaded'=>0))));
	} 
	
	public function send_new_receipts(){
		$SoldReceipts=$this->SoldReceipt->find('all',array('recursive'=>-1,'limit'=>100,'conditions'=>array('SoldReceipt.is_uploaded'=>0)));
		$resting=new $this->Resting;
		$_fox=($this->Session->read('fox'));
		$resting->api_username=$_fox['Fox']['un'];
		$resting->api_password=$_fox['Fox']['pwd'];
		$resting->authorisation_key=$_fox['Fox']['k'];
		$resting->url = $_fox['Fox']['url'];
		$response=$resting->XML_fetch_data('/sold_receipts/fox_add.json','<Receipts>'.(json_encode($SoldReceipts)).'</Receipts>');
		if($resting->has_response){
			$response_array=json_decode($response);
			if(isset($response_array->data->response->saved_string)){
				if(strlen($response_array->data->response->saved_string)){
					@$this->SoldReceipt->query('UPDATE sold_receipts set is_uploaded=1 where id in ('.($response_array->data->response->saved_string).')');
				}
			}else{
				echo "Error:Receipt could not be saved online! Access denied";
			}
		}else{
			pr("could not communicate with BOU/ Check your internet connection");
		}
		sleep(1);
	} 

/**
 * index method
 *
 * @return void
 */
	public function index($large_cash=null) {
		$this->SoldReceipt->recursive = 0;
		$this->paginate=array('order'=>'SoldReceipt.date desc');
		if(	isset($_REQUEST['date_from']) &&
			isset($_REQUEST['date_to'])){
			$from	=($_REQUEST['date_from']);
			$to	=($_REQUEST['date_to']);
			
			$this->set('from', $from);
			$this->set('to', $to);
			
			if(isset($_REQUEST['search_query_string']) && !empty($_REQUEST['search_query_string'])){					
				$this->paginate = array(
					'conditions' => array(
						'OR' => array(
							'SoldReceipt.id LIKE' => '%' . $_REQUEST['search_query_string'] . '%',
							'SoldReceipt.customer_name LIKE' => '%' . $_REQUEST['search_query_string'] . '%'
						)
					),
					'order' => array('SoldReceipt.date' => 'desc'),
					'limit'=>200
				);
				
			}else{
				$this->paginate=array('conditions'=>array('SoldReceipt.date >='=>$from,'SoldReceipt.date <='=>$to),'order'=>'SoldReceipt.date desc','limit'=>200);
			}
			
			if($large_cash){
				
				//get Average Rate for Dollar
				$dollar_av_rate=$this->SoldReceipt->find('all',array(
					'recursive'=>-1,
					'conditions'=>array(
						'SoldReceipt.currency_id'=>'c1',
						'SoldReceipt.date >='=>$from,
						'SoldReceipt.date <='=>$to
					),
					'fields'=>array(
						'AVG(SoldReceipt.rate) as dollar_av_rate'
					)
				));
				
				$dollar_av_rate=$dollar_av_rate[0][0]['dollar_av_rate'];
				if(!$dollar_av_rate){
					$dollar_av_rate=2400;
				}
				
				$max_dollar_ugx=5000*$dollar_av_rate;
				
				$this->paginate=array(
					'conditions'=>array(
						'SoldReceipt.date >='=>$from,
						'SoldReceipt.date <='=>$to,
						'SoldReceipt.amount_ugx >='=>$max_dollar_ugx
					),
					'order'=>'SoldReceipt.date desc',
					'limit'=>0
				);
				$this->set('dollar_av_rate', $dollar_av_rate);
				$this->set('large_cash', $large_cash);
				
			}
		}
		$this->set('soldReceipts', $this->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->SoldReceipt->exists($id)) {
			throw new NotFoundException(__('Invalid sales receipt'));
		}
		$options = array('conditions' => array('SoldReceipt.' . $this->SoldReceipt->primaryKey => $id));
		$this->set('soldReceipt', $this->SoldReceipt->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {	
			
			if(strlen($this->request->data['SoldReceipt']['id'])<4){
				$this->Session->setFlash(__('Invalid Receipt number.'));
				$this->redirect(array('action' => 'add'));				
			}
			
			if(($this->request->data['SoldReceipt']['currency_id'])=='c00'){
				$this->Session->setFlash(__('Invalid Currency selected.'));
				$this->redirect(array('action' => 'add'));
			}
			
			if(($this->request->data['SoldReceipt']['purpose_id'])=='p000'){
				$this->Session->setFlash(__('Invalid Purpose of transaction selected.'));
				$this->redirect(array('action' => 'add'));
			}
			
			if($this->request->data['SoldReceipt']['currency_id']=='c8'){
				$other_currency=$this->SoldReceipt->OtherCurrency->find('first',array(
					'conditions'=>array(
						'OtherCurrency.id'=>$this->request->data['SoldReceipt']['other_currency_id']
					)
				));
				
				if(isset($other_currency['OtherCurrency']['name'])){					
					$this->request->data['SoldReceipt']['other_name']=$other_currency['OtherCurrency']['name'];
				}else{
					$this->Session->setFlash(__('Other currency not found.'));
					$this->redirect(array('action' => 'add'));
				}
			}else{
				unset($this->request->data['SoldReceipt']['other_currency_id']);
				unset($this->request->data['SoldReceipt']['other_name']);
			}
			
			if($this->request->data['SoldReceipt']['print']=='dont_print'){
				$this->request->data['SoldReceipt']['status']=1;
			}else{
				$this->request->data['SoldReceipt']['status']=0;
			}
			
			if($this->request->data['SoldReceipt']['currency_id']=='c8'){
				$_amount=$this->request->data['SoldReceipt']['amount'];
				$_rate=$this->request->data['SoldReceipt']['rate'];
				
				$rate=Configure::read('others');
				$amount=0;
				@$amount=($_amount*$_rate)/$rate;
				
				$this->request->data['SoldReceipt']['amount']=$amount;
				$this->request->data['SoldReceipt']['rate']=$rate;
				$this->request->data['SoldReceipt']['orig_amount']=$_amount;
				$this->request->data['SoldReceipt']['orig_rate']=$_rate;
				
			}
			
			if($this->Auth->User('role')!='super_admin'){
				$this->request->data['SoldReceipt']['user_id']=$this->Auth->User('id');
				$this->request->data['SoldReceipt']['name']=$this->Auth->User('name');
			}else{
				$user=$this->SoldReceipt->User->find('first',array(
					'conditions'=>array(
						'User.id'=>$this->request->data['SoldReceipt']['user_id']
					),
					'fields'=>array(
						'name'
					)
				));
				$this->request->data['SoldReceipt']['name']=$user['User']['name'];
			}	
			$this->request->data['SoldReceipt']['fox_id']=Configure::read('foxId');
			if($this->Auth->User('role')!='super_admin'){
				$this->request->data['SoldReceipt']['date']=date('Y-m-d');
			}
			$this->SoldReceipt->create();
			if ($this->SoldReceipt->save($this->request->data)) {
				$this->Session->setFlash(__('The sales receipt has been saved'));
				$this->redirect(array('action' => 'view',$this->SoldReceipt->getInsertID()));
			} else {
				$this->Session->setFlash(__('The sales receipt could not be saved. Please, try again.'));
			}
		}
		$purposes = $this->SoldReceipt->Purpose->find('list');
		$users = $this->SoldReceipt->User->find('list',array(
			'conditions'=>array(
				'NOT'=>array(
					'User.role'=>array(
						'super_admin'
					)
				)
			),
			'recursive'=>-1
		));
		$currencies = $this->SoldReceipt->Currency->find('list');
		$other_currencies = $this->SoldReceipt->OtherCurrency->find('list');
		$this->set(compact('purposes', 'currencies','users','other_currencies'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->SoldReceipt->exists($id)) {
			throw new NotFoundException(__('Invalid sales receipt'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if(strlen($this->request->data['SoldReceipt']['id'])<4){
				$this->Session->setFlash(__('Invalid Receipt number.'));
				$this->redirect(array('action' => 'edit',$id));
			}
			
			if(($this->request->data['SoldReceipt']['currency_id'])=='c00'){
				$this->Session->setFlash(__('Invalid Currency selected.'));
				$this->redirect(array('action' => 'edit',$id));
			}
			
			if(($this->request->data['SoldReceipt']['purpose_id'])=='p000'){
				$this->Session->setFlash(__('Invalid Purpose of transaction selected.'));
				$this->redirect(array('action' => 'edit',$id));
			}
			
			if($this->request->data['SoldReceipt']['currency_id']=='c8'){
				$other_currency=$this->SoldReceipt->OtherCurrency->find('first',array(
					'conditions'=>array(
						'OtherCurrency.id'=>$this->request->data['SoldReceipt']['other_currency_id']
					)
				));
				
				if(isset($other_currency['OtherCurrency']['name'])){					
					$this->request->data['SoldReceipt']['other_name']=$other_currency['OtherCurrency']['name'];
				}else{
					$this->Session->setFlash(__('Other currency not found.'));
					$this->redirect(array('action' => 'add'));
				}
			}else{
				unset($this->request->data['SoldReceipt']['other_currency_id']);
				unset($this->request->data['SoldReceipt']['other_name']);
			}
			
			if($this->request->data['SoldReceipt']['currency_id']!='c8'){
				unset($this->request->data['SoldReceipt']['other_name']);
				$this->request->data['SoldReceipt']['orig_amount']=0;
				$this->request->data['SoldReceipt']['orig_rate']=0;
			}
			
			if($this->request->data['SoldReceipt']['currency_id']=='c8'){
				$_amount=$this->request->data['SoldReceipt']['amount'];
				$_rate=$this->request->data['SoldReceipt']['rate'];
				
				$rate=Configure::read('others');
				$amount=0;
				@$amount=($_amount*$_rate)/$rate;
				
				$this->request->data['SoldReceipt']['amount']=$rate;
				$this->request->data['SoldReceipt']['rate']=$amount;
				$this->request->data['SoldReceipt']['orig_amount']=$_amount;
				$this->request->data['SoldReceipt']['orig_rate']=$_rate;
				
			}
			
			if ($this->SoldReceipt->save($this->request->data)) {
				$this->Session->setFlash(__('The sales receipt has been saved'));
				$this->redirect(array('action' => 'view',$id));
			} else {
				$this->Session->setFlash(__('The sales receipt could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('SoldReceipt.' . $this->SoldReceipt->primaryKey => $id));
			$this->request->data = $this->SoldReceipt->find('first', $options);
		}
		$purposes = $this->SoldReceipt->Purpose->find('list');
		$users = $this->SoldReceipt->User->find('list');
		$currencies = $this->SoldReceipt->Currency->find('list');		
		$other_currencies = $this->SoldReceipt->OtherCurrency->find('list');
		$this->set(compact('purposes', 'currencies','users','other_currencies'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @throws MethodNotAllowedException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->SoldReceipt->id = $id;
		if (!$this->SoldReceipt->exists()) {
			throw new NotFoundException(__('Invalid sales receipt'));
		}
		if ($this->SoldReceipt->delete()) {
			$this->Session->setFlash(__('Sales receipt deleted'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Sales receipt was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
