<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 */
class ReturnsController extends AppController {
	var $uses = array('Purpose','PurchasedPurpose','Currency');
	
	function returns_weekly(){
		$d=$purposes=$currencies=array();
		if(	isset($_REQUEST['date_from']) &&
			isset($_REQUEST['date_to'])){
			$from	=($_REQUEST['date_from']);
			$to	=($_REQUEST['date_to']);
			
			$this->set('from', $from);
			$this->set('to', $to);
			
			$purposes=$this->Purpose->find('all',array('recursive'=>-1,'conditions'=>array('id !='=>'p000'),'order'=>'id ASC'));
			$currencies=$this->Currency->find('all',array('recursive'=>-1,'conditions'=>array('id !='=>'c00'),'order'=>'id ASC'));
			$d=array();
			$currency_details=array();
			foreach($purposes as $purpose){
					$d[$purpose['Purpose']['id']]=array();
				foreach($currencies as $currency){
						$amount=$this->Currency->query("SELECT SUM(amount) as amount from sold_receipts
												WHERE purpose_id='".$purpose['Purpose']['id']."'
												and currency_id='".$currency['Currency']['id']."'
												and date >= '$from'
												and	date <= '$to'
												");
						array_push($d[$purpose['Purpose']['id']],array($currency['Currency']['id']=>$amount[0][0]['amount']));
				}
			}
			
			foreach($currencies as $currency){
					$details=$this->Currency->query("SELECT SUM(amount) as amount, AVG(rate) as av_rate from sold_receipts
												WHERE currency_id='".$currency['Currency']['id']."'
												and date >= '$from'
												and	date <= '$to'
												");
					array_push($currency_details,array(array('amount'=>$details[0][0]['amount'],'av_rate'=>$details[0][0]['av_rate'])));
			}
			
		}else{
			pr('Date range required.');exit();
		}
		
		$this->set('my_data',$d);
		$this->set('purposes',$purposes);
		$this->set('currencies',$currencies);
		$this->set('currency_details',$currency_details);
	}
	
	function returns_weekly_purchases(){
		$d=$purposes=$currencies=array();
		if(	isset($_REQUEST['date_from']) &&
			isset($_REQUEST['date_to'])){
			$from	=($_REQUEST['date_from']);
			$to	=($_REQUEST['date_to']);
			
			$this->set('from', $from);
			$this->set('to', $to);
			
			$purposes=$this->PurchasedPurpose->find('all',array('recursive'=>-1,'conditions'=>array('id !='=>'p000')));
			$currencies=$this->Currency->find('all',array('recursive'=>-1,'conditions'=>array('id !='=>'c00')));
			$d=array();
			$currency_details=array();
			foreach($purposes as $purpose){
					$d[$purpose['PurchasedPurpose']['id']]=array();
				foreach($currencies as $currency){
						$amount=$this->Currency->query("SELECT SUM(amount) as amount from purchased_receipts
												WHERE purchased_purpose_id='".$purpose['PurchasedPurpose']['id']."'
												and currency_id='".$currency['Currency']['id']."'
												and date >= '$from'
												and	date <= '$to'
												");
						array_push($d[$purpose['PurchasedPurpose']['id']],array($currency['Currency']['id']=>$amount[0][0]['amount']));
				}
			}
			
			foreach($currencies as $currency){
					$details=$this->Currency->query("SELECT SUM(amount) as amount, AVG(rate) as av_rate from purchased_receipts
												WHERE currency_id='".$currency['Currency']['id']."'
												and date >= '$from'
												and	date <= '$to'
												");
					array_push($currency_details,array(array('amount'=>$details[0][0]['amount'],'av_rate'=>$details[0][0]['av_rate'])));
			}
			
		}else{
			pr('Date range required.');exit();
		}
		
		$this->set('my_data',$d);
		$this->set('purposes',$purposes);
		$this->set('currencies',$currencies);
		$this->set('currency_details',$currency_details);
	}
}
