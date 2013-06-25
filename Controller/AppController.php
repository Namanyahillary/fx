<?php
App::uses('Controller', 'Controller');
CakePlugin::load('Rest');
class AppController extends Controller {
	public $uses = array('User','Notification.Notification','Fox');
	public $components = array(
		'Auth','Session',
		'RequestHandler',
		'Func','Resting',
        'Rest.Rest' => array(
            'catchredir' => true,
            'callbacks' => array(
                'cbRestlogBeforeSave' => 'restlogBeforeSave',
                'cbRestlogAfterSave' => 'restlogAfterSave',
                'cbRestlogBeforeFind' => 'restlogBeforeFind',
                'cbRestlogAfterFind' => array('Common', 'setCache'),
                'cbRestlogFilter' => 'restlogFilter',
                'cbRestRatelimitMax' => 'restRatelimitMax',
            ),
        ),
    );
	function beforeFilter(){
		
		if ($this->Rest->isActive()) {
			$this->Auth->autoRedirect = false;
			$data=$this->Auth->authenticate = array(
				'Basic' => array(
							'userModel' => 'User',
							'fields'=>array(
									'username'=>$this->Rest->credentials('username'),
									'password'=>AuthComponent::password($this->Rest->credentials('password'))
								)
							)
			);
			if($this->User->find('count',array('conditions'=>array('username'=>$this->Rest->credentials('username'),'password'=>AuthComponent::password($this->Rest->credentials('password'))),'limit 1'))>0){
				if (!$this->Auth->login($data)) {					
					$msg = sprintf('Unable to log you in with the supplied credentials. Data:');
					return $this->Rest->abort(array('status' => '403', 'error' => $msg));
				}
			}			
		}
		
		parent::beforeFilter();
		
		//Get Company details.
		if($this->Session->read('fox')==null){
			$this->Session->write('fox',$this->Fox->find('first'));
		}
		
		$this->Auth->authError = 'Please login to continue.';
        $this->Auth->loginError = 'Incorrect username/password combination';
        $this->Auth->loginRedirect = array('controller' => 'dashboards');
        $this->Auth->logoutRedirect = array('controller' => 'users', 'action' => 'login');
		
        $this->set('super_admin', $this->_is('super_admin'));
		$this->set('admin', $this->_isAdmin());
        $this->set('logged_in', $this->_loggedIn());
        $this->set('users_username', $this->setField('username'));
        $this->set('users_Id', $this->setField('id'));
        $this->set('approval_position', $this->setField('approval_position'));
        $this->set('name_of_user', $this->setField('name'));
        $this->set('role_of_user', $this->setField('role'));
        $this->set('other_role_of_user', $this->setField('other_role'));
        $this->set('email_of_user', $this->setField('email'));
        $this->set('profile_image', $this->setField('profile_image')); 
        $this->set('store', $this->_isStore()); 
	}
	
	function _is($role) {
        $fits_role = FALSE;
        if ($this->Auth->user('role') == $role) {
            $fits_role = TRUE;
        }
        return $fits_role;
    }
	
	function _isStore() {
        $admin = FALSE;
        if ($this->Auth->user('role') == 'store') {
            $this->Session->write('user_id', $this->Auth->user('id'));
            $admin = TRUE;
        }else
            $admin = FALSE;
        return $admin;
    }

    function setField($field) {
        return $this->Auth->User($field);
    }

    function _isAdmin() {
        $admin = FALSE;
        if ($this->Auth->user('role') == 'admin') {
            $admin = TRUE;
        }
        return $admin;
    }

    function _loggedIn() {
        $logged_in = FALSE;
        if ($this->Auth->user()) {
            $logged_in = TRUE;
        }
        return $logged_in;
    }
	
	public function restlogBeforeSave ($Rest) {}
    public function restlogAfterSave ($Rest) {}
    public function restlogBeforeFind ($Rest) {}
    public function restlogAfterFind ($Rest) {}
}
