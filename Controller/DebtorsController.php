<?php
App::uses('AppController', 'Controller');
/**
 * Debtors Controller
 *
 * @property Debtor $Debtor
 */
class DebtorsController extends AppController {

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
		$this->Debtor->recursive = 0;
		$this->paginate=array('Debtor.order'=>'date desc');
		if(	isset($_REQUEST['date_from']) &&
			isset($_REQUEST['date_to'])){
			$from	=($_REQUEST['date_from']);
			$to	=($_REQUEST['date_to']);
			
			$this->set('from', $from);
			$this->set('to', $to);
			
			$this->paginate=array(
				'conditions'=>array(
					'Debtor.date >='=>$from,
					'Debtor.date <='=>$to
				),
				'order'=>'Debtor.date desc',
				'limit'=>200
			);
		}
		$this->set('debtors', $this->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Debtor->exists($id)) {
			throw new NotFoundException(__('Invalid debtor'));
		}
		$options = array('conditions' => array('Debtor.' . $this->Debtor->primaryKey => $id));
		$this->set('debtor', $this->Debtor->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			if($this->Auth->User('role')!='super_admin'){
				$this->request->data['Debtor']['user_id']=$this->Auth->User('id');
			}
			$this->Debtor->create();
			if ($this->Debtor->save($this->request->data)) {
				$this->Session->setFlash(__('The debtor has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The debtor could not be saved. Please, try again.'));
			}
		}
		$users = $this->Debtor->User->find('list',array(
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
		if (!$this->Debtor->exists($id)) {
			throw new NotFoundException(__('Invalid debtor'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Debtor->save($this->request->data)) {
				$this->Session->setFlash(__('The debtor has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The debtor could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Debtor.' . $this->Debtor->primaryKey => $id));
			$this->request->data = $this->Debtor->find('first', $options);
		}
		$users = $this->Expense->User->find('list',array(
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
		$this->Debtor->id = $id;
		if (!$this->Debtor->exists()) {
			throw new NotFoundException(__('Invalid debtor'));
		}
		if ($this->Debtor->delete()) {
			$this->Session->setFlash(__('Debtor deleted'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Debtor was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
