<?php
App::uses('AppController', 'Controller');
class DashboardsController extends AppController {
	public $name = 'Dashboards';
	public function index() {
		if($this->Auth->User('role')=='super_admin'){
			$resting=new $this->Resting;			
			$_fox=($this->Session->read('fox'));
			$resting->api_username=$_fox['Fox']['un'];
			$resting->api_password=$_fox['Fox']['pwd'];
			$resting->authorisation_key=$_fox['Fox']['k'];
			$resting->url = $_fox['Fox']['url'];
			$response=$resting->XML_fetch_data('/notifs/my_notifications.json','<Notifications></Notifications>');
			if($resting->has_response){
				$response_array=json_decode($response);
				if(isset($response_array->data->response->notifications)){
					$notifications=(json_decode($response_array->data->response->notifications[0]));
					foreach($notifications as $notification){
						$this->User->Notification->msg($this->Auth->User('id'), $notification->Notification->message);
					}
				}
			}
		}
	}
}
