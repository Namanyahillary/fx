<?php
App::uses('AppController', 'Controller');
/**
 * CashAtBankUgxes Controller
 *
 * @property CashAtBankUgx $CashAtBankUgx
 */
class CashAtBankUgxesController extends AppController {
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
		$this->CashAtBankUgx->recursive = 0;
		$this->paginate=array('order'=>'CashAtBankUgx.date desc');
		if(	isset($_REQUEST['date_from']) &&
			isset($_REQUEST['date_to'])){
			$from	=($_REQUEST['date_from']);
			$to	=($_REQUEST['date_to']);
			
			$this->set('from', $from);
			$this->set('to', $to);
			
			$this->paginate=array(
				'conditions'=>array(
					'CashAtBankUgx.date >='=>$from,
					'CashAtBankUgx.date <='=>$to
				),
				'order'=>'CashAtBankUgx.date desc'
			);
		}
		$this->set('cashAtBankUgxes', $this->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->CashAtBankUgx->exists($id)) {
			throw new NotFoundException(__('Invalid cash at bank ugx'));
		}
		$options = array('conditions' => array('CashAtBankUgx.' . $this->CashAtBankUgx->primaryKey => $id));
		$this->set('cashAtBankUgx', $this->CashAtBankUgx->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			if($this->Auth->User('role')!='super_admin'){
				$this->request->data['CashAtBankUgx']['user_id']=$this->Auth->User('id');
			}
			$this->CashAtBankUgx->create();
			if ($this->CashAtBankUgx->save($this->request->data)) {
				$this->Session->setFlash(__('The cash at bank ugx has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The cash at bank ugx could not be saved. Please, try again.'));
			}
		}
		$users = $this->CashAtBankUgx->User->find('list',array(
			'conditions'=>array(
				'NOT'=>array(
					'User.role'=>array(
						'super_admin'
					)
				)
			),
			'recursive'=>-1
		));
		$this->set(compact('users'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->CashAtBankUgx->exists($id)) {
			throw new NotFoundException(__('Invalid cash at bank ugx'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if($this->Auth->User('role')!='super_admin'){
				$this->request->data['CashAtBankUgx']['user_id']=$this->Auth->User('id');
			}
			if ($this->CashAtBankUgx->save($this->request->data)) {
				$this->Session->setFlash(__('The cash at bank ugx has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The cash at bank ugx could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('CashAtBankUgx.' . $this->CashAtBankUgx->primaryKey => $id));
			$this->request->data = $this->CashAtBankUgx->find('first', $options);
		}
		$users = $this->CashAtBankUgx->User->find('list',array(
			'conditions'=>array(
				'NOT'=>array(
					'User.role'=>array(
						'super_admin'
					)
				)
			),
			'recursive'=>-1
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
		$this->CashAtBankUgx->id = $id;
		if (!$this->CashAtBankUgx->exists()) {
			throw new NotFoundException(__('Invalid cash at bank ugx'));
		}
		if ($this->CashAtBankUgx->delete()) {
			$this->Session->setFlash(__('Cash at bank ugx deleted'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Cash at bank ugx was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
