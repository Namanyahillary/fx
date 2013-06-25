<?php
App::uses('AppController', 'Controller');
/**
 * Openings Controller
 *
 * @property Opening $Opening
 */
class OpeningsController extends AppController {
	public $uses=array('Opening','User','OtherCurrency');
	function beforeFilter() {
        parent::beforeFilter();		
        if ($this->action == 'edit' ||
			$this->action == 'add' ||
			$this->action == 'delete') {
			if($this->Auth->user('role')!='super_admin'){
				$this->Session->setFlash(__('Access Denied!!'));
				$this->redirect($this->Auth->logout());
			}
        }
    }
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Opening->recursive = 0;
		$this->paginate=array('order'=>'date desc');
		if(	isset($_REQUEST['date_from']) &&
			isset($_REQUEST['date_to'])){
			
			$from	=($_REQUEST['date_from']);
			$to	=($_REQUEST['date_to']);
			
			$this->set('from', $from);
			$this->set('to', $to);
			
			$this->paginate=array(
				'conditions'=>array(
					'Opening.date >='=>$from,
					'Opening.date <='=>$to
				),
				'order'=>'Opening.date desc'
			);
		}
		$other_currencies=$this->OtherCurrency->find('all',array(
			'recursive'=>-1
		));
		$this->set('other_currencies',$other_currencies);
		$this->set('openings', $this->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Opening->exists($id)) {
			throw new NotFoundException(__('Invalid opening'));
		}
		$options = array('conditions' => array('Opening.' . $this->Opening->primaryKey => $id));
		$this->set('opening', $this->Opening->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$func=$this->Func;
			$this->request->data['Opening']['id']=$func->getUID1();
			$this->request->data['Opening']['c8r']=Configure::read('others');
			if((int)$this->request->data['Opening']['c8a']!=0){
				$this->request->data['Opening']['c8a']/=Configure::read('others');
			}
			
			$other_currencies=$this->OtherCurrency->find('all',array(
				'recursive'=>-1
			));
			
			$arr['data']=array();
			foreach($other_currencies as $other_currency){
				array_push($arr['data'],array(
					'CID'=>$other_currency['OtherCurrency']['id'],
					''.($other_currency['OtherCurrency']['id'])=>$other_currency['OtherCurrency']['id'],
					'CRATE'=>$this->request->data['OtherCurrency'][($other_currency['OtherCurrency']['id']).'_r'],
					'CAMOUNT'=>$this->request->data['OtherCurrency'][($other_currency['OtherCurrency']['id']).'_a'],
					'CNAME'=>$other_currency['OtherCurrency']['name'],
				));
			}
			$this->request->data['Opening']['other_currencies']=json_encode($arr);
			
			$this->Opening->create();
			if ($this->Opening->save($this->request->data)) {
				$this->Session->setFlash(__('The opening has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The opening could not be saved. Please, try again.'));
			}
		}
		$users = $this->Opening->User->find('list',array(
			'conditions'=>array(
				'NOT'=>array(
					'User.role'=>'super_admin'
				)
			)
		));
		$other_currencies=$this->OtherCurrency->find('all',array(
			'recursive'=>-1
		));
		$this->set(compact('users','other_currencies'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id,$user_id) {
		if (!$this->Opening->exists($id)) {
			throw new NotFoundException(__('Invalid opening'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			
			$this->request->data['Opening']['c8r']=Configure::read('others');
			if((int)$this->request->data['Opening']['c8a']!=0){
				$this->request->data['Opening']['c8a']/=Configure::read('others');
			}
			
			$other_currencies=$this->OtherCurrency->find('all',array(
				'recursive'=>-1
			));
			
			$arr['data']=array();
			foreach($other_currencies as $other_currency){
				//skip other currencies that we not saved at the time of crating the record
				if(!isset($this->request->data['OtherCurrency'][($other_currency['OtherCurrency']['id']).'_r'])){
					continue;
				}
				
				array_push($arr['data'],array(
					'CID'=>$other_currency['OtherCurrency']['id'],
					''.($other_currency['OtherCurrency']['id'])=>$other_currency['OtherCurrency']['id'],
					'CRATE'=>$this->request->data['OtherCurrency'][($other_currency['OtherCurrency']['id']).'_r'],
					'CAMOUNT'=>$this->request->data['OtherCurrency'][($other_currency['OtherCurrency']['id']).'_a'],
					'CNAME'=>$other_currency['OtherCurrency']['name'],
				));
			}
			$this->request->data['Opening']['other_currencies']=json_encode($arr);
			
			
			if ($this->Opening->save($this->request->data)) {
				$this->Session->setFlash(__('The opening has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The opening could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Opening.' . $this->Opening->primaryKey => $id));
			$this->request->data = $this->Opening->find('first', $options);
		}
		$users = $this->Opening->User->find('list',array(
			'conditions'=>array(
				'User.id'=>$user_id
			),'limit'=>1
		));
		$this->set(compact('users'));
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
		$this->Opening->id = $id;
		if (!$this->Opening->exists()) {
			throw new NotFoundException(__('Invalid opening'));
		}
		//$this->request->onlyAllow('post', 'delete');
		if ($this->Opening->delete()) {
			$this->Session->setFlash(__('Opening deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Opening was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
