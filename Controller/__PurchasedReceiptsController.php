<?php
App::uses('AppController', 'Controller');
/**
 * PurchasedReceipts Controller
 *
 * @property PurchasedReceipt $PurchasedReceipt
 */
class PurchasedReceiptsController extends AppController {

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->PurchasedReceipt->recursive = 0;
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
			$this->PurchasedReceipt->create();
			if ($this->PurchasedReceipt->save($this->request->data)) {
				$this->Session->setFlash(__('The purchased receipt has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The purchased receipt could not be saved. Please, try again.'));
			}
		}
		$purchasedPurposes = $this->PurchasedReceipt->PurchasedPurpose->find('list');
		$currencies = $this->PurchasedReceipt->Currency->find('list');
		$this->set(compact('purchasedPurposes', 'currencies'));
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
			throw new NotFoundException(__('Invalid purchased receipt'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->PurchasedReceipt->save($this->request->data)) {
				$this->Session->setFlash(__('The purchased receipt has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The purchased receipt could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('PurchasedReceipt.' . $this->PurchasedReceipt->primaryKey => $id));
			$this->request->data = $this->PurchasedReceipt->find('first', $options);
		}
		$purchasedPurposes = $this->PurchasedReceipt->PurchasedPurpose->find('list');
		$currencies = $this->PurchasedReceipt->Currency->find('list');
		$this->set(compact('purchasedPurposes', 'currencies'));
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
			throw new NotFoundException(__('Invalid purchased receipt'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->PurchasedReceipt->delete()) {
			$this->Session->setFlash(__('Purchased receipt deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Purchased receipt was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
