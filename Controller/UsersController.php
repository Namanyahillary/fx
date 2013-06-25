<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AppController {
	
	public $components = array(
        'RequestHandler',
        'Rest.Rest' => array(
            'catchredir' => true, // Recommended unless you implement something yourself
            'debug' => 2,
            'actions' => array(
                'fox_login' => array(
                    'extract' => array('response'),
                ),
            ),
        ),
    );
	
	function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('reset_password','my_actions','register');
		
        if ($this->action == 'add') {
			if($this->Auth->user('role')!='super_admin'){
				$this->Session->setFlash(__('Access Denied!!'));
				$this->redirect($this->Auth->logout());
			}
        }
    } 
	
	public function pong(){}
	
	public function fox_login(){
		$response['resp_string']='Access Denied...';
		if ($this->Auth->login()) {
			$u=$this->User->find('all',array(
				'limit'=>1,
				'recursive'=>-1,
				'fields'=>array(
					'User.name','User.id'
				),
				'conditions'=>array(
					'username'=>$this->Rest->credentials('username')
				)
			));
			if(count($u))
				$response['resp_string']='OK '.strlen($u[0]['User']['id']).' '.($u[0]['User']['id']).' '.(($u[0]['User']['name']));
		}
		$this->set(compact('response'));
	}
	
	public function login() {
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				$fox=$this->Fox->find('first');
				$this->Session->write('fox',$fox);
				
				date_default_timezone_set('Africa/Nairobi');
				
				//Date validation
				$ts1 = strtotime(date('Y-m-d'));//today's system date
				$ts2 = strtotime($fox['Fox']['prev_d']);
				$seconds_diff = $ts2 - $ts1;
				if($seconds_diff>0){
					$this->Session->setFlash(__('Invalid system date. Correct it to continue. Thanks.'));
					$this->redirect($this->Auth->logout());
				}
				
				//Validate for weekends
				$weekends=explode(',',$fox['Fox']['weekends']);
				foreach($weekends as $weekend){
					if($ts1==strtotime($weekend)){
						$this->Session->setFlash(__('Its a weekend.'));
						$this->redirect($this->Auth->logout());
					}
				}
				
				//update current date
				$this->Fox->id=$fox['Fox']['id'];
				$this->Fox->set('prev_d',date('Y-m-d'));
				$this->Fox->save();
				
				//$this->User->Notification->msg(AuthComponent::user('id'), "You logged in!");
				$this->redirect($this->Auth->redirect());
			} else {
				$this->Session->setFlash(__('Invalid username or password, try again'));
			}
		}
	}
	
	public function logout() {
		$this->redirect($this->Auth->logout());
	}
	
	public function reset_password(){
	
	}
	
	public function register(){
	
	}
	
	public function settings($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		
		if($id!=$this->Auth->User('id') and $this->Auth->User('role')!='super_admin'){
        	$this->Session->setFlash(__('Invalid request', true));
            $this->redirect(array('action' => 'view',$this->Auth->User('id')));
        }
		
		$this->set('user', $this->User->read(null, $id));
		
		if (empty($this->data)) {
            	$this->data = $this->User->read(null, $id);
        }
	}
	


/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		
		if($id!=$this->Auth->User('id') and $this->Auth->User('role')!='super_admin'){
        	$this->Session->setFlash(__('Invalid request', true));
            $this->redirect(array('action' => 'view',$this->Auth->User('id')));
        }
		
		$this->set('user', $this->User->read(null, $id));
		
		if (empty($this->data)) {
            	$this->data = $this->User->read(null, $id);
        }
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			date_default_timezone_set('Africa/Nairobi');
			$this->request->data['User']['date']=date('Y-m-d H:i:s');
			$this->User->create();			
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'settings',$id));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
				$this->redirect(array('action' => 'settings'));
			}
		} else {
			$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
			$this->request->data = $this->User->find('first', $options);
		}
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
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		
		if($this->Auth->user('role')!='admin'){
			$this->request->onlyAllow('post', 'delete');
		}
		
		if ($this->User->delete()) {
			$this->Session->setFlash(__('User deleted'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('User was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
