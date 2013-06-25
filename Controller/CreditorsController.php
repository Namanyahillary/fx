<?php
App::uses('AppController', 'Controller');
/**
 * Creditors Controller
 *
 * @property Creditor $Creditor
 */
class CreditorsController extends AppController {
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
		$this->Creditor->recursive = 0;
		$this->paginate=array('Creditor.order'=>'date desc');
		if(	isset($_REQUEST['date_from']) &&
			isset($_REQUEST['date_to'])){
			$from	=($_REQUEST['date_from']);
			$to	=($_REQUEST['date_to']);
			
			$this->set('from', $from);
			$this->set('to', $to);
			
			$this->paginate=array(
				'conditions'=>array(
					'Creditor.date >='=>$from,
					'Creditor.date <='=>$to
				),
				'order'=>'Creditor.date desc',
				'limit'=>200
			);
		}
		$this->set('creditors', $this->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Creditor->exists($id)) {
			throw new NotFoundException(__('Invalid creditor'));
		}
		$options = array('conditions' => array('Creditor.' . $this->Creditor->primaryKey => $id));
		$this->set('creditor', $this->Creditor->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			if($this->Auth->User('role')!='super_admin'){
				$this->request->data['Creditor']['user_id']=$this->Auth->User('id');
			}
			$this->Creditor->create();
			if ($this->Creditor->save($this->request->data)) {
				$this->Session->setFlash(__('The creditor has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The creditor could not be saved. Please, try again.'));
			}
		}
		$users = $this->Creditor->User->find('list',array(
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
		if (!$this->Creditor->exists($id)) {
			throw new NotFoundException(__('Invalid creditor'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Creditor->save($this->request->data)) {
				$this->Session->setFlash(__('The creditor has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The creditor could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Creditor.' . $this->Creditor->primaryKey => $id));
			$this->request->data = $this->Creditor->find('first', $options);
		}
		$users = $this->Creditor->User->find('list',array(
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
		$this->Creditor->id = $id;
		if (!$this->Creditor->exists()) {
			throw new NotFoundException(__('Invalid creditor'));
		}
		if ($this->Creditor->delete()) {
			$this->Session->setFlash(__('Creditor deleted'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Creditor was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
