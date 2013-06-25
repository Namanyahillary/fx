<?php
App::uses('AppController', 'Controller');
/**
 * Currencies Controller
 *
 * @property Currency $Currency
 */
class CurrenciesController extends AppController {
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Currency->recursive = 0;
		$this->set('currencies', $this->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Currency->exists($id)) {
			throw new NotFoundException(__('Invalid currency'));
		}
		$options = array('conditions' => array('Currency.' . $this->Currency->primaryKey => $id));
		$this->set('currency', $this->Currency->find('first', $options));
	}
}
