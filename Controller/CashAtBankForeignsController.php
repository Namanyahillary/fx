<?php
App::uses('AppController', 'Controller');
/**
 * CashAtBankForeigns Controller
 *
 * @property CashAtBankForeign $CashAtBankForeign
 */
class CashAtBankForeignsController extends AppController {
	
	function beforeFilter() {
        parent::beforeFilter();		
        if($this->Auth->user('role')!='super_admin'){
			$this->Session->setFlash(__('Access Denied!!'));
			$this->redirect($this->Auth->logout());
		}
    } 
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->CashAtBankForeign->recursive = 0;
		$this->paginate=array('order'=>'CashAtBankForeign.date desc');
		if(	isset($_REQUEST['date_from']) &&
			isset($_REQUEST['date_to'])){
			$from	=($_REQUEST['date_from']);
			$to	=($_REQUEST['date_to']);
			
			$this->set('from', $from);
			$this->set('to', $to);
			
			$this->paginate=array(
				'conditions'=>array(
					'CashAtBankForeign.date >='=>$from,
					'CashAtBankForeign.date <='=>$to
				),
				'order'=>'CashAtBankForeign.date desc'
			);
		}
		$this->set('cashAtBankForeigns', $this->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->CashAtBankForeign->exists($id)) {
			throw new NotFoundException(__('Invalid cash at bank foreign'));
		}
		$options = array('conditions' => array('CashAtBankForeign.' . $this->CashAtBankForeign->primaryKey => $id));
		$this->set('cashAtBankForeign', $this->CashAtBankForeign->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			if($this->Auth->User('role')!='super_admin'){
				$this->request->data['CashAtBankForeign']['user_id']=$this->Auth->User('id');
			}
			
			$this->CashAtBankForeign->create();
			if ($this->CashAtBankForeign->save($this->request->data)) {
				$this->Session->setFlash(__('The cash at bank foreign has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The cash at bank foreign could not be saved. Please, try again.'));
			}
		}
		$users = $this->CashAtBankForeign->User->find('list',array(
			'conditions'=>array(
				'NOT'=>array(
					'User.role'=>array(
						'super_admin'
					)
				)
			),
			'recursive'=>-1
		));
		$currencies = $this->CashAtBankForeign->Currency->find('list');
		$this->set(compact('users','currencies'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->CashAtBankForeign->exists($id)) {
			throw new NotFoundException(__('Invalid cash at bank foreign'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if($this->Auth->User('role')!='super_admin'){
				$this->request->data['CashAtBankForeign']['user_id']=$this->Auth->User('id');
			}
			if ($this->CashAtBankForeign->save($this->request->data)) {
				$this->Session->setFlash(__('The cash at bank foreign has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The cash at bank foreign could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('CashAtBankForeign.' . $this->CashAtBankForeign->primaryKey => $id));
			$this->request->data = $this->CashAtBankForeign->find('first', $options);
		}
		$users = $this->CashAtBankForeign->User->find('list',array(
			'conditions'=>array(
				'NOT'=>array(
					'User.role'=>array(
						'super_admin'
					)
				)
			),
			'recursive'=>-1
		));
		$currencies = $this->CashAtBankForeign->Currency->find('list');
		$this->set(compact('users','currencies'));
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
		$this->CashAtBankForeign->id = $id;
		if (!$this->CashAtBankForeign->exists()) {
			throw new NotFoundException(__('Invalid cash at bank foreign'));
		}
		if ($this->CashAtBankForeign->delete()) {
			$this->Session->setFlash(__('Cash at bank foreign deleted'));
			$this->redirect(array('controller' => 'dashboards','action'=>'index'));
		}
		$this->Session->setFlash(__('Cash at bank foreign was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
