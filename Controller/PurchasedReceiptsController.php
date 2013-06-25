<?php
App::uses('AppController', 'Controller');
/**
 * PurchasedReceipts Controller
 *
 * @property PurchasedReceipt $PurchasedReceipt
 */
class PurchasedReceiptsController extends AppController {
	
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
		if (!$this->PurchasedReceipt->exists($id)) {
			throw new NotFoundException(__('Invalid purchased receipt'));
		}
		
		$this->request->data['PurchasedReceipt']['id']=$id;
		$this->request->data['PurchasedReceipt']['status']=0;
		if ($this->PurchasedReceipt->save($this->request->data)) {
				$this->set('resp','Sent for printing.');
		} else {
			$this->set('resp','Not Sent for printing.');
		}
	}
	
	function should_upload($id,$indicator){
		if (!$this->PurchasedReceipt->exists($id)) {
			throw new NotFoundException(__('Invalid sales receipt'));
		}
		
		$this->request->data['PurchasedReceipt']['id']=$id;
		if($indicator==0 || $indicator==1){
			$this->request->data['PurchasedReceipt']['is_uploaded']=$indicator;
		}
		$this->PurchasedReceipt->save($this->request->data);		
		$this->redirect(array('action'=>'index'));
	}

	public function upload(){
		$options = array('conditions' => array('PurchasedReceipt.is_uploaded'=>0));
		$this->set('receipt_count', $this->PurchasedReceipt->find('count', $options));
	}
	
	public function get_new_receipts_count(){
		$this->set('count_new_receipts',$this->PurchasedReceipt->find('count',array('conditions'=>array('PurchasedReceipt.is_uploaded'=>0))));
	} 
	
	public function send_new_receipts(){	
		$PurchasedReceipts=$this->PurchasedReceipt->find('all',array('recursive'=>-1,'limit'=>100,'conditions'=>array('PurchasedReceipt.is_uploaded'=>0)));
		$resting=new $this->Resting;
		$_fox=($this->Session->read('fox'));
		$resting->api_username=$_fox['Fox']['un'];
		$resting->api_password=$_fox['Fox']['pwd'];
		$resting->authorisation_key=$_fox['Fox']['k'];
		$resting->url = $_fox['Fox']['url'];
		$response=$resting->XML_fetch_data('/purchased_receipts/fox_add.json','<Receipts>'.(json_encode($PurchasedReceipts)).'</Receipts>');
		echo ($response);
		if($resting->has_response){
			$response_array=json_decode($response);
			if(isset($response_array->data->response->saved_string)){
				if(strlen($response_array->data->response->saved_string)){
					@$this->PurchasedReceipt->query('UPDATE purchased_receipts set is_uploaded=1 where id in ('.($response_array->data->response->saved_string).')');
				}
			}else{
				//echo "Error:Receipt could not be saved online! Access denied";
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
		$this->PurchasedReceipt->recursive = 0;
		$this->paginate=array('order'=>'PurchasedReceipt.date desc');
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
							'PurchasedReceipt.id LIKE' => '%' . $_REQUEST['search_query_string'] . '%',
							'PurchasedReceipt.customer_name LIKE' => '%' . $_REQUEST['search_query_string'] . '%'
						)
					),
					'order' => array('PurchasedReceipt.date' => 'desc'),
					'limit'=>200
				);
				
			}else{
				$this->paginate=array('conditions'=>array('PurchasedReceipt.date >='=>$from,'PurchasedReceipt.date <='=>$to),'order'=>'PurchasedReceipt.date desc','limit'=>200);
			}
			
			if($large_cash){
				
				//get Average Rate for Dollar
				$dollar_av_rate=$this->PurchasedReceipt->find('all',array(
					'recursive'=>-1,
					'conditions'=>array(
						'PurchasedReceipt.currency_id'=>'c1',
						'PurchasedReceipt.date >='=>$from,
						'PurchasedReceipt.date <='=>$to
					),
					'fields'=>array(
						'AVG(PurchasedReceipt.rate) as dollar_av_rate'
					)
				));
				
				$dollar_av_rate=$dollar_av_rate[0][0]['dollar_av_rate'];
				if(!$dollar_av_rate){
					$dollar_av_rate=2400;
				}
				
				$max_dollar_ugx=5000*$dollar_av_rate;
				
				$this->paginate=array(
					'conditions'=>array(
						'PurchasedReceipt.date >='=>$from,
						'PurchasedReceipt.date <='=>$to,
						'PurchasedReceipt.amount_ugx >='=>$max_dollar_ugx					
					),
					'order'=>'PurchasedReceipt.date desc',
					'limit'=>0
				);
				$this->set('large_cash', $large_cash);
				$this->set('dollar_av_rate', $dollar_av_rate);
			}
		}
		$this->set('purchasedReceipts', $this->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->PurchasedReceipt->exists($id)) {
			throw new NotFoundException(__('Invalid purchased receipt'));
		}
		$options = array('conditions' => array('PurchasedReceipt.' . $this->PurchasedReceipt->primaryKey => $id));
		$this->set('purchasedReceipt', $this->PurchasedReceipt->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			
			if(strlen($this->request->data['PurchasedReceipt']['id'])<4){
				$this->Session->setFlash(__('Invalid Receipt number.'));
				$this->redirect(array('action' => 'add'));
			}
			
			if(($this->request->data['PurchasedReceipt']['currency_id'])=='c00'){
				$this->Session->setFlash(__('Invalid Currency selected.'));
				$this->redirect(array('action' => 'add'));
			}
			
			if(($this->request->data['PurchasedReceipt']['purchased_purpose_id'])=='p000'){
				$this->Session->setFlash(__('Invalid Receipt Source of funds selected.'));
				$this->redirect(array('action' => 'add'));
			}
			
			if($this->request->data['PurchasedReceipt']['currency_id']=='c8'){
				$other_currency=$this->PurchasedReceipt->OtherCurrency->find('first',array(
					'conditions'=>array(
						'OtherCurrency.id'=>$this->request->data['PurchasedReceipt']['other_currency_id']
					)
				));
				
				if(isset($other_currency['OtherCurrency']['name'])){					
					$this->request->data['PurchasedReceipt']['other_name']=$other_currency['OtherCurrency']['name'];
				}else{
					$this->Session->setFlash(__('Other currency not found.'));
					$this->redirect(array('action' => 'add'));
				}
			}else{
				unset($this->request->data['PurchasedReceipt']['other_currency_id']);
				unset($this->request->data['PurchasedReceipt']['other_name']);
			}
			
			if($this->request->data['PurchasedReceipt']['print']=='dont_print'){
				$this->request->data['PurchasedReceipt']['status']=1;
			}else{
				$this->request->data['PurchasedReceipt']['status']=0;
			}
			
			if($this->request->data['PurchasedReceipt']['currency_id']=='c8'){
				$_amount=$this->request->data['PurchasedReceipt']['amount'];
				$_rate=$this->request->data['PurchasedReceipt']['rate'];
				
				$rate=Configure::read('others');
				$amount=0;
				@$amount=($_amount*$_rate)/$rate;
				
				$this->request->data['PurchasedReceipt']['amount']=$amount;
				$this->request->data['PurchasedReceipt']['rate']=$rate;
				$this->request->data['PurchasedReceipt']['orig_amount']=$_amount;
				$this->request->data['PurchasedReceipt']['orig_rate']=$_rate;
				
			}
			
			if($this->Auth->User('role')!='super_admin'){
				$this->request->data['PurchasedReceipt']['user_id']=$this->Auth->User('id');
				$this->request->data['PurchasedReceipt']['name']=$this->Auth->User('name');
			}else{
				$user=$this->PurchasedReceipt->User->find('first',array(
					'conditions'=>array(
						'User.id'=>$this->request->data['PurchasedReceipt']['user_id']
					),
					'fields'=>array(
						'name'
					)
				));
				$this->request->data['PurchasedReceipt']['name']=$user['User']['name'];
			}			
			$this->request->data['PurchasedReceipt']['fox_id']=Configure::read('foxId');
			if($this->Auth->User('role')!='super_admin'){
				$this->request->data['PurchasedReceipt']['date']=date('Y-m-d');
			}
			$this->PurchasedReceipt->create();
			if ($this->PurchasedReceipt->save($this->request->data)) {
				$this->Session->setFlash(__('The purchase receipt has been saved'));
				$this->redirect(array('action' => 'view',$this->PurchasedReceipt->getInsertID()));
			} else {
				$this->Session->setFlash(__('The purchase receipt could not be saved. Please, try again.'));
			}
		}
		$purchasedPurposes = $this->PurchasedReceipt->PurchasedPurpose->find('list');
		$users = $this->PurchasedReceipt->User->find('list',array(
			'conditions'=>array(
				'NOT'=>array(
					'User.role'=>array(
						'super_admin'
					)
				)
			),
			'recursive'=>-1
		));
		$currencies = $this->PurchasedReceipt->Currency->find('list');
		$other_currencies = $this->PurchasedReceipt->OtherCurrency->find('list');
		$this->set(compact('purchasedPurposes', 'currencies','users','other_currencies'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->PurchasedReceipt->exists($id)) {
			throw new NotFoundException(__('Invalid purchase receipt'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			
			if(strlen($this->request->data['PurchasedReceipt']['id'])<4){
				$this->Session->setFlash(__('Invalid Receipt number.'));
				$this->redirect(array('action' => 'edit',$id));
			}
			
			if(($this->request->data['PurchasedReceipt']['currency_id'])=='c00'){
				$this->Session->setFlash(__('Invalid Currency selected.'));
				$this->redirect(array('action' => 'edit',$id));
			}
			
			if(($this->request->data['PurchasedReceipt']['purchased_purpose_id'])=='p000'){
				$this->Session->setFlash(__('Invalid Receipt Source of funds selected.'));
				$this->redirect(array('action' => 'edit',$id));
			}
			
			if($this->request->data['PurchasedReceipt']['currency_id']=='c8'){
				$other_currency=$this->PurchasedReceipt->OtherCurrency->find('first',array(
					'conditions'=>array(
						'OtherCurrency.id'=>$this->request->data['PurchasedReceipt']['other_currency_id']
					)
				));
				
				if(isset($other_currency['OtherCurrency']['name'])){					
					$this->request->data['PurchasedReceipt']['other_name']=$other_currency['OtherCurrency']['name'];
				}else{
					$this->Session->setFlash(__('Other currency not found.'));
					$this->redirect(array('action' => 'add'));
				}
			}else{
				unset($this->request->data['PurchasedReceipt']['other_currency_id']);
				unset($this->request->data['PurchasedReceipt']['other_name']);
			}
			
			if($this->request->data['PurchasedReceipt']['currency_id']!='c8'){
				unset($this->request->data['PurchasedReceipt']['other_name']);
				$this->request->data['PurchasedReceipt']['orig_amount']=0;
				$this->request->data['PurchasedReceipt']['orig_rate']=0;
			}
			
			if($this->request->data['PurchasedReceipt']['currency_id']=='c8'){
				$_amount=$this->request->data['PurchasedReceipt']['amount'];
				$_rate=$this->request->data['PurchasedReceipt']['rate'];
				
				$rate=Configure::read('others');
				$amount=0;
				@$amount=($_amount*$_rate)/$rate;
				
				$this->request->data['PurchasedReceipt']['amount']=$amount;
				$this->request->data['PurchasedReceipt']['rate']=$rate;
				$this->request->data['PurchasedReceipt']['orig_amount']=$_amount;
				$this->request->data['PurchasedReceipt']['orig_rate']=$_rate;
				
			}
			
			if ($this->PurchasedReceipt->save($this->request->data)) {
				$this->Session->setFlash(__('The purchase receipt has been saved'));
				$this->redirect(array('action' => 'view',$id));
			} else {
				$this->Session->setFlash(__('The purchase receipt could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('PurchasedReceipt.' . $this->PurchasedReceipt->primaryKey => $id));
			$this->request->data = $this->PurchasedReceipt->find('first', $options);
		}
		$purchasedPurposes = $this->PurchasedReceipt->PurchasedPurpose->find('list');
		$users = $this->PurchasedReceipt->User->find('list');
		$currencies = $this->PurchasedReceipt->Currency->find('list');		
		$other_currencies = $this->PurchasedReceipt->OtherCurrency->find('list');
		$this->set(compact('purchasedPurposes', 'currencies','users','other_currencies'));
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
		$this->PurchasedReceipt->id = $id;
		if (!$this->PurchasedReceipt->exists()) {
			throw new NotFoundException(__('Invalid purchase receipt'));
		}
		if ($this->PurchasedReceipt->delete()) {
			$this->Session->setFlash(__('Purchase receipt deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Purchase receipt was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
