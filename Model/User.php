<?php
App::uses('AppModel', 'Model');
/**
 * User Model
 *
 */
class User extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'username' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			),
			'unique' =>array(
				'rule' => array('isUnique'),
				'message' => 'Username already taken',
			)
		),
		'password' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'email' => array(
			'email' => array(
				'rule' => array('email'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'password' => array(
			'The password must be between 5 and 15 characters.'=>array(
				'rule'=>array('between',5,15),
				'message'=>'The password must be between 5 and 15 characters.'
			),
			'The passwords do not match'=>array(
				'rule'=>'matchPasswords',
				'message'=>'The passwords do not match.'
			)
		),
	);
	
	function matchPasswords($data){
		if($data['password'] == $this->data['User']['password_confirmation']){
			return TRUE;
		}
		$this->invalidate('password_confirmation','The passwords do not match');
	}
	function hashPasswords($data){
		if (isset($this->data['User']['password'])){
			$this->data['User']['password']=Security::hash($this->data['User']['password'],NULL,TRUE);
			return $data;
		}
		return $data;
	}
	function beforeSave(){
		$this->hashPasswords(NULL,TRUE);
		return TRUE;
	}
	
	public $hasMany = array(
		'Notification' => array(
			'className' => 'Notifications.Notification',
			'foreignKey' => 'user_id',
		),
	);
}
