<?php
App::uses('AppModel', 'Model');
/**
 * DailyReturn Model
 *
 * @property Fox $Fox
 * @property DailyBuyingReturn $DailyBuyingReturn
 * @property DailySellingReturn $DailySellingReturn
 * @property DailyBuyingReturn $DailyBuyingReturn
 * @property DailySellingReturn $DailySellingReturn
 */
class DailyReturn extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'fox_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'date' => array(
			'date' => array(
				'rule' => array('date'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Fox' => array(
			'className' => 'Fox',
			'foreignKey' => 'fox_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'DailyBuyingReturn' => array(
			'className' => 'DailyBuyingReturn',
			'foreignKey' => 'daily_buying_return_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'DailySellingReturn' => array(
			'className' => 'DailySellingReturn',
			'foreignKey' => 'daily_selling_return_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'DailyBuyingReturn' => array(
			'className' => 'DailyBuyingReturn',
			'foreignKey' => 'daily_return_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'DailySellingReturn' => array(
			'className' => 'DailySellingReturn',
			'foreignKey' => 'daily_return_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

}
