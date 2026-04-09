<?php 
App::uses('AppController','Controller');
class TasksController extends AppController{
	public $helpers = array('Html','Form','Session');
	public $components = array('Session','Paginator','Twilio');	
	public $uses = array('User','Task','Employee','TaskAssign','TaskDocument','NappUser','TaskComment','UserPermission');
	/***
	/*Author  :Ranjit,
	/*Comment : Check before user is login or not
	****/
	function beforeFilter()
    {
		$this->callConstants();
	}
	
	public function admin_type($data=null){		
		$this->layout='admin_layout';
		$this->set('title_for_layout',SITENAME.' ::  DuroLab Access');		
		$this->checkAdminSession(); 				

		$user_id  = $this->Session->read('User.id');
		if($data == 'product'){
			$this->Session->write('durolab_type','product');
			$this->redirect('index');
		}else if($data == 'technical'){
			$this->Session->write('durolab_type','technical');
			$this->redirect('index');
		}else if($data == 'project_enquiry'){
			$this->Session->write('durolab_type','project_enquiry');
			$this->redirect('index');
		}			
	}	
	
	public function type($data=null){		
		$this->layout='staff_inner_layout';
		$this->set('title_for_layout',SITENAME.' ::  DuroLab Access');		
		$this->checkSatffSession(); 				
		
		$user_id  = $this->Session->read('Customer.id');
		if($data == 'product'){
			$this->Session->write('durolab_type','product');
			$this->redirect('index');
		}else if($data == 'technical'){
			$this->Session->write('durolab_type','technical');
			$this->redirect('index');
		}else if($data == 'project_enquiry'){
			$this->Session->write('durolab_type','project_enquiry');
			$this->redirect('index');
		}		
		
		$userArr = $this->UserPermission->find('list',array('conditions'=>array('UserPermission.user_id'=>$user_id),'fields' => array('permssion_id'),));
		$this->set('userArr',$userArr);
		
		
	}	
	
	function cronsnew(){
		
		$this->autoRender = false;
			
		// fetch task only that is in-process		
		$this->Task->bindModel(
		array('hasMany' => array('TaskAssign' => array(
			'className' => 'TaskAssign',			 
			'foreignKey' => 'task_id',				
			'conditions' => array(),
			'fields' => '',
			'order' => array(),
		))));
		$this->TaskAssign->bindModel(
		array('belongsTo' => array('NappUser' => array(
			'className' => 'NappUser',			 
			'foreignKey' => 'emp_id',				
			'conditions' => array(),
			'fields' => array('name','lname','email','mobile_number','id','country'),
			'order' => array(),
		))));
		$this->Task->bindModel(
		array('belongsTo' => array('NappUser' => array(
			'className' => 'NappUser',			 
			'foreignKey' => 'emp_id',				
			'conditions' => array(),
			'fields' => array('name','lname','email','mobile_number','id',''),
			'order' => array(),
		))));
		$this->Task->recursive = 2;
		$TaskArr = $this->Task->find('all',array('conditions'=>array('Task.task_status >='=>1,'Task.task_status <'=>3)));
		if(!empty($TaskArr)){	
			foreach($TaskArr as $TaskArrs){
				// sender user detail 
				
				$sendername = $TaskArrs['NappUser']['name'].' '. $TaskArrs['NappUser']['lname'];	
				$period = $TaskArrs['Task']['period'];				
				$task_type = $TaskArrs['Task']['task_type'];
				$task_id = $TaskArrs['Task']['task_id'];
				$last_update = $TaskArrs['Task']['last_update'];
				if($TaskArrs['Task']['is_reminder'] == 1){
					
					$udpateArrs['Task']['last_update'] = date('Y-m-d H:i:s');
					$udpateArrs['Task']['id'] = $TaskArrs['Task']['id'];
					$this->Task->save($udpateArrs);
					
					if($TaskArrs['Task']['is_both'] == 1){
						if(!empty($TaskArrs['TaskAssign'])){
							foreach($TaskArrs['TaskAssign'] as $NappUser){
								$country = $NappUser['NappUser']['country'];
								if($country  == 'india'){
									date_default_timezone_set('Asia/Kolkata');
								}	
								$todate = strtotime($last_update); 
								$fromdate = time();
								$diff = $fromdate - $todate;
								if($diff > 0){		
								
								$min = intval($diff / 60);	 					
								$hour = date('H'); 	
													
								$s_flag = 0;
								if($period == '30 min'){
									if($min > 30){
										$s_flag = 1;
									}	
								}else if($period == 'hour'){
									if($min > 60){
										$s_flag = 1;
									}
								}else if($period == 'day'){
									if($min > 1440){
										$s_flag = 1;
									}
								}else if($period == 'week'){
									if($min > 10080){
										$s_flag = 1;
									}
								}
								if($TaskArrs['Task']['send_to'] == 1){
									// Office Work Hour
									if($hour >= 09 && $hour <= 17){
										$flag =1;	
									}else{
										$flag = 0;	
									}		
								}else{
									// 24 hour
									$flag =1;
								}
							
								if(($flag == 1) && ($s_flag == 1)){
									$udpateArrs['Task']['last_update'] = date('Y-m-d H:i:s');
									$udpateArrs['Task']['id'] = $TaskArrs['Task']['id'];
									$this->Task->save($udpateArrs);
									
									$receivername = $NappUser['NappUser']['name'].' '.$NappUser['NappUser']['lname'];
									$email = $NappUser['NappUser']['email'];
									if(!empty($email)){
										$title = $TaskArrs['Task']['title'];
										$subject = SITENAME.' :: Any update on task ('.$task_id.')';							
										$template_name = 'reminder_task';										
										
										$url = SITEURL.'tasks/autologin/'.base64_encode($NappUser['NappUser']['id']).'/task/'.$task_type;
										
										$variables = array('sendername'=>$sendername,'receivername'=>$receivername,'task_type'=>$task_type,'task_task_id'=>$task_id,'task_title'=>$TaskArrs['Task']['title'],'user_id'=>$NappUser['NappUser']['id'],'url'=>$url);	
										echo $email.'<br>';		
										try{
											$this->sendemail($email ,$subject,$template_name,$variables);
										}catch (Exception $e){
											
										} 										
									}										
										
									if(!empty($NappUser['NappUser']['mobile_number'])){
										if($country  != 'india'){	
											$phones = str_replace(' ','',$NappUser['NappUser']['mobile_number']);
											$phone = ltrim($phones,'+');
											$to = '+'.$phone;
											$sid = TSID;	
											$token = TOKEN;							
											$from = FROM_NUMBER;	
											$body = "Hi ".$receivername.' Any update task ('.$task_id.')';						
											$this->Twilio->AccountSid = TSID;   
											$this->Twilio->AccountToken = TOKEN;						
											$response = $this->Twilio->sendsms($to,$from,$body);
										}							
									}							
								}
							}	
						}	
						}						
					}
					if($TaskArrs['Task']['is_email'] == 1){
						
						if(!empty($TaskArrs['TaskAssign'])){
							foreach($TaskArrs['TaskAssign'] as $NappUser){
								$country = $NappUser['NappUser']['country'];
								if($country  == 'india'){
									date_default_timezone_set('Asia/Kolkata');
								}	
								$todate = strtotime($last_update); 
								$fromdate = time();
								$diff = $fromdate - $todate;
								if($diff > 0){		
					
								$min = intval($diff / 60);								
								$hour = date('H'); 	
													
								$s_flag = 0;
								if($period == '30 min'){
									if($min > 30){
										$s_flag = 1;
									}	
								}else if($period == 'hour'){
									if($min > 60){
										$s_flag = 1;
									}
								}else if($period == 'day'){
									if($min > 1440){
										$s_flag = 1;
									}
								}else if($period == 'week'){
									if($min > 10080){
										$s_flag = 1;
									}
								}
								if($TaskArrs['Task']['send_to'] == 1){
									// Office Work Hour
									if($hour >= 09 && $hour <= 17){
										$flag =1;	
									}else{
										$flag = 0;	
									}		
								}else{
									// 24 hour
									$flag =1;
								}
							
								if(($flag == 1) && ($s_flag == 1)){
									$udpateArrs['Task']['last_update'] = date('Y-m-d H:i:s');
									$udpateArrs['Task']['id'] = $TaskArrs['Task']['id'];
									$this->Task->save($udpateArrs);
									$receivername = $NappUser['NappUser']['name'].' '.$NappUser['NappUser']['lname'];
									$email = $NappUser['NappUser']['email'];
									if(!empty($email)){
										$title = $TaskArrs['Task']['title'];
										$subject = SITENAME.' :: Any update on '.$task_id;							
										$template_name = 'reminder_task';										
										
										$url = SITEURL.'tasks/autologin/'.base64_encode($NappUser['NappUser']['id']).'/task/'.$task_type;
										
										$variables = array('sendername'=>$sendername,'receivername'=>$receivername,'task_type'=>$task_type,'task_task_id'=>$task_id,'task_title'=>$TaskArrs['Task']['title'],'user_id'=>$NappUser['NappUser']['id'],'url'=>$url);	
										echo $email.'<br>';		
										try{
											$this->sendemail($email ,$subject,$template_name,$variables);
										}catch (Exception $e){
											
										} 										
									}										
																	
								}
							}	
						}	
						}
					}

					if($TaskArrs['Task']['is_sms'] == 1){
						if(!empty($TaskArrs['TaskAssign'])){
							foreach($TaskArrs['TaskAssign'] as $NappUser){
								$country = $NappUser['NappUser']['country'];
								if($country  == 'india'){
									date_default_timezone_set('Asia/Kolkata');
								}	
								$todate = strtotime($last_update); 
								$fromdate = time();
								$diff = $fromdate - $todate;
								if($diff > 0){		
					
								$min = intval($diff / 60);								
								$hour = date('H'); 	
													
								$s_flag = 0;
								if($period == '30 min'){
									if($min > 30){
										$s_flag = 1;
									}	
								}else if($period == 'hour'){
									if($min > 60){
										$s_flag = 1;
									}
								}else if($period == 'day'){
									if($min > 1440){
										$s_flag = 1;
									}
								}else if($period == 'week'){
									if($min > 10080){
										$s_flag = 1;
									}
								}
								if($TaskArrs['Task']['send_to'] == 1){
									// Office Work Hour
									if($hour >= 09 && $hour <= 17){
										$flag =1;	
									}else{
										$flag = 0;	
									}		
								}else{
									// 24 hour
									$flag =1;
								}
							
								if(($flag == 1) && ($s_flag == 1)){
										
									$udpateArrs['Task']['last_update'] = date('Y-m-d H:i:s');
									$udpateArrs['Task']['id'] = $TaskArrs['Task']['id'];
									$this->Task->save($udpateArrs);
									$receivername = $NappUser['NappUser']['name'].' '.$NappUser['NappUser']['lname'];
									$email = $NappUser['NappUser']['email'];															
									//$mobile_number = $NappUser['NappUser']['mobile_number'];
									if(!empty($NappUser['NappUser']['mobile_number'])){
										if($country  != 'india'){
											$phones = str_replace(' ','',$NappUser['NappUser']['mobile_number']);
											$phone = ltrim($phones,'+');
											echo $phone;
											$to = '+'.$phone;
											$sid = TSID;	
											$token = TOKEN;							
											$from = FROM_NUMBER;	
											$body = "Hi ".$receivername.' Any update task ('.$task_id.')';						
											$this->Twilio->AccountSid = TSID;   
											$this->Twilio->AccountToken = TOKEN;						
											$response = $this->Twilio->sendsms($to,$from,$body);
										}							
									}							
								}
								}	
							}	
						}
						
					}	
				}
					
			}
		}
	}		

	// complete task
	function crons(){
		
		$this->autoRender = false;
			
		// fetch task only that is in-process		
		$this->Task->bindModel(
		array('hasMany' => array('TaskAssign' => array(
			'className' => 'TaskAssign',			 
			'foreignKey' => 'task_id',				
			'conditions' => array(),
			'fields' => '',
			'order' => array(),
		))));
		$this->TaskAssign->bindModel(
		array('belongsTo' => array('NappUser' => array(
			'className' => 'NappUser',			 
			'foreignKey' => 'emp_id',				
			'conditions' => array(),
			'fields' => array('name','lname','email','mobile_number','id','country'),
			'order' => array(),
		))));
		$this->Task->bindModel(
		array('belongsTo' => array('NappUser' => array(
			'className' => 'NappUser',			 
			'foreignKey' => 'emp_id',				
			'conditions' => array(),
			'fields' => array('name','lname','email','mobile_number','id',''),
			'order' => array(),
		))));
		$this->Task->recursive = 2;
		$TaskArr = $this->Task->find('all',array('conditions'=>array('Task.task_status'=>1)));
		
		 		
		if(!empty($TaskArr)){	
			foreach($TaskArr as $TaskArrs){
					
				$sendername = $TaskArrs['NappUser']['name'].' '. $TaskArrs['NappUser']['lname'];	
				$period = $TaskArrs['Task']['period'];
				
				$task_type = $TaskArrs['Task']['task_type'];
				$task_id = $TaskArrs['Task']['task_id'];
				$last_update = $TaskArrs['Task']['last_update'];
				
				$country = $TaskArrs['NappUser']['country'];
				/* if($country  == 'india'){
					date_default_timezone_set('Asia/Kolkata');
				} */					
				$hour = date('H');
				
				if($TaskArrs['Task']['is_reminder'] == 1){
					
					
					$then = new DateTime($last_update);
					$now = new DateTime(); 
					$sinceThen = $then->diff($now);
					
					$s_flag = 0;
					if($period == '30 min'){
						if($sinceThen->i > 30){
							$s_flag = 1;
						}	
					}else if($period == 'hour'){
						if($sinceThen->h > 0){
							$s_flag = 1;
						}
					}else if($period == 'day'){
						if($sinceThen->d > 0){
							$s_flag = 1;
						}
					}else if($period == 'week'){
						if($sinceThen->d > 7){
							$s_flag = 1;
						}
					}						
					
					if($TaskArrs['Task']['send_to'] == 1){
						// Office Work Hour
						if($hour >= 09 && $hour <= 17){
							$flag =1;	
						}else{
							$flag = 0;	
						}		
					}else{
						// 24 hour
						$flag =1;
					}	
					if(($flag == 1) && ($s_flag == 1)){						
						if($TaskArrs['Task']['is_both'] == 1){
							if(!empty($TaskArrs['TaskAssign'])){
								foreach($TaskArrs['TaskAssign'] as $NappUser){
									
									$receivername = $NappUser['NappUser']['name'].' '.$NappUser['NappUser']['lname'];
									$email = $NappUser['NappUser']['email'];
									if(!empty($email)){
										$title = $TaskArrs['Task']['title'];
										$subject = SITENAME.' :: Any update on '.$task_id;							
										$template_name = 'reminder_task';										
										
										$url = SITEURL.'tasks/autologin/'.base64_encode($NappUser['NappUser']['id']).'/task/'.$task_type;
										
										$variables = array('sendername'=>$sendername,'receivername'=>$receivername,'task_type'=>$task_type,'task_task_id'=>$task_id,'task_title'=>$TaskArrs['Task']['title'],'user_id'=>$NappUser['NappUser']['id'],'url'=>$url);	
										echo $email.'<br>';		
										try{
											//$this->sendemail($email ,$subject,$template_name,$variables);
										}catch (Exception $e){
											
										} 										
									}										
									$mobile_number = $NappUser['NappUser']['mobile_number'];
									if(!empty($mobile_number)){
										
									}								
								}	
							}
														
						}else if($TaskArrs['Task']['is_email'] == 1){
								if(!empty($TaskArrs['TaskAssign'])){
									foreach($TaskArrs['TaskAssign'] as $NappUser){
										$receivername = $NappUser['NappUser']['name'].' '.$NappUser['NappUser']['lname'];
										$email = $NappUser['NappUser']['email'];
										if(!empty($email)){
											$title = $TaskArrs['Task']['title'];
											$subject = SITENAME.' :: Any update on '.$task_id;							
											$template_name = 'reminder_task';										
											
											$url = SITEURL.'tasks/autologin/'.base64_encode($NappUser['NappUser']['id']).'/task/'.$task_type;
											
											$variables = array('sendername'=>$sendername,'receivername'=>$receivername,'task_type'=>$task_type,'task_task_id'=>$task_id,'task_title'=>$TaskArrs['Task']['title'],'user_id'=>$NappUser['NappUser']['id'],'url'=>$url);				
											try{
												//$this->sendemail($email ,$subject,$template_name,$variables);
											}catch (Exception $e){
												
											} 
											
										}
									}	
								}	
							}else if($TaskArrs['Task']['is_sms'] == 1){
								foreach($TaskArrs['TaskAssign'] as $NappUser){
									$name = $NappUser['NappUser']['name'].' '.$NappUser['NappUser']['lname'];
									$mobile_number = $NappUser['NappUser']['mobile_number'];
									if(!empty($mobile_number)){
										
									}
								}
							}
							// update last date date
							$last_update['Task']['id'] = $TaskArrs['Task']['id'];
							$last_update['Task']['last_update'] = date('Y-m-d H:i:s');									
							$this->Task->save($last_update); 
					}
					
				}
			}			
		}		
	}		
	public function admin_index(){		
		$this->layout='admin_layout';
		$this->set('title_for_layout',SITENAME.' :: Task List');		
		$this->checkAdminSession(); 
				
		$user_id=$this->Session->read('User.id');		
		
		$keyowrd = '';
		$this->set('is_access',0);
		$opt = $this->Session->read('opt');
		$pdfaceess = $this->Session->read('pdfaceess');
		  
		if(!empty($this->request->data['keyowrd'])){
			$keyowrd = rtrim($this->request->data['keyowrd'],' ');
			$keyowrd = ltrim($keyowrd,' ');	
			$this->set('is_access',2);			
		}else if(!empty($this->request->data)){
			$uotp = str_replace(' ','',$this->request->data['otp']);
			
			$userArr = $this->User->find('first',array('conditions'=>array('User.otp'=>$uotp,'User.id'=>$user_id)));
			
			if(!empty($userArr)){
				$update['User']['id'] = $user_id;
				$update['User']['otp'] = '';
				$this->User->save($update);	
				$this->Session->write('pdfaceess',1);
				$this->Session->delete('opt');
				$this->Session->setFlash('Thank for verifying your account.','default',array('class' => 'alert alert-success'));
				$this->redirect('index');
				
			}else{
				$this->Session->setFlash('Wrong otp password. Please try again.','default',array('class' => 'alert alert-danger'));
				$this->redirect('index');
			}	
		}else if(!empty($opt)){
			$this->set('is_access',1);
		}else if(!empty($pdfaceess)){
			$this->set('is_access',2);
		}else if(empty($pdfaceess)){			
			$rand = rand(000000,999999);			
			$update['User']['id'] = $user_id;
			$update['User']['otp'] = $rand;
			$this->User->save($update);	
			
			$userArr = $this->User->find('first',array('conditions'=>array('User.id'=>$user_id)));				
			$name = $userArr['User']['name'];			
			$to = $userArr['User']['email'];								
			$subject= SITENAME." :: Secure One-Time-Password (OTP) for DuroLab Access. OTP is ".$rand;				
			$template_name = 'durolabotp';										
			$variables = array('email'=>$to,'name'=>$name,'otp'=>$rand);			
			try{
				$this->sendemail($to,$subject,$template_name,$variables);
			}catch (Exception $e){
				
			}
			if(!empty($userArr['User']['phone'])){
				$phones = str_replace(' ','',$userArr['User']['phone']);
				$phone = ltrim($phones,'+');
				$to = '+'.$phone;
				$sid = TSID;	
				$token = TOKEN;							
				$from = FROM_NUMBER;	
				$body = "Hi, Secure OTP for DuroLab Access. OTP is ".$rand;						
				$this->Twilio->AccountSid = TSID;   
				$this->Twilio->AccountToken = TOKEN;						
				$response = $this->Twilio->sendsms($to,$from,$body);
			}
			
			$this->Session->write('opt',$rand);			
			$this->Session->write('name',$name);			
			$this->Session->setFlash('OTP sent at email. Please check your mail and verify.','default',array('class' => 'alert alert-success'));
			$this->redirect('index');
		}

		$this->Task->bindModel(
		array('hasMany' => array('TaskAssign' => array(
			'className' => 'TaskAssign',			 
			'foreignKey' => 'task_id',				
			'conditions' => array(),
			'fields' => '',
			'order' => array(),
		))));
		$this->TaskAssign->bindModel(
		array('belongsTo' => array('NappUser' => array(
			'className' => 'NappUser',			 
			'foreignKey' => 'emp_id',				
			'conditions' => array(),
			'fields' => array('name','lname'),
			'order' => array(),
		))));
		$this->Task->bindModel(
		array('belongsTo' => array('NappUser' => array(
			'className' => 'NappUser',			 
			'foreignKey' => 'emp_id',				
			'conditions' => array(),
			'fields' => array('name','lname'),
			'order' => array(),
		)))); 
		$this->Task->bindModel(
		array('belongsTo' => array('User' => array(
			'className' => 'User',			 
			'foreignKey' => 'admin_id',				
			'conditions' => array(),
			'fields' => array('name'),
			'order' => array(),
		)))); 
		$this->Task->recursive = 2;
		$keyowrd = '';
		$durolab_type = $this->Session->read('durolab_type');
		if(!empty($this->request->data['keyowrd'])){
			$keyowrd = rtrim($this->request->data['keyowrd'],' ');
			$keyowrd = ltrim($keyowrd,' ');	
			$this->paginate = array('conditions'=>array('OR'=>array('Task.title LIKE'=>'%'.$keyowrd.'%','Task.task_id LIKE'=>'%'.$keyowrd.'%'),'Task.task_type'=>$durolab_type),'order'=>array('Task.id'=>'DESC'),'page' => 1, 'limit' => 25);
		}else{			
			$this->paginate = array('conditions'=>array('Task.task_type'=>$durolab_type),'order'=>array('Task.id'=>'DESC'),'page' => 1, 'limit' => 25);
		}	
		// echo '<pre>';
		// print_r($this->paginate);
		// die();
		$task = $this->Paginator->paginate('Task');		
		$this->set('task', $task);		
		$this->set('keyowrd', $keyowrd);		

		$usernewArr = $this->User->find('first',array('conditions'=>array('User.id'=>$user_id)));
		$this->set('usernewArr', $usernewArr);
		
	}
	
	/***
	/*Author  :Ranjit,
	/*Comment :Admin Category list
	****/
	public function index(){		
		$this->layout='staff_inner_layout';
		$this->set('title_for_layout',SITENAME.' :: Task List');		
		$this->checkSatffSession(); 				
		$user_id=$this->Session->read('Customer.id');
	
		$keyowrd = '';
		$this->set('is_access',0);
		$opt = $this->Session->read('opt');
		$pdfaceess = $this->Session->read('pdfaceess');
		if(!empty($this->request->data['keyowrd'])){
			$keyowrd = rtrim($this->request->data['keyowrd'],' ');
			$keyowrd = ltrim($keyowrd,' ');	
			$this->set('is_access',2);			
		}else if(!empty($this->request->data)){
			$uotp = $this->request->data['otp'];
			$userArr = $this->NappUser->find('first',array('conditions'=>array('NappUser.otp'=>$uotp,'NappUser.id'=>$user_id)));
			
			if(!empty($userArr)){
				$update['NappUser']['id'] = $user_id;
				$update['NappUser']['otp'] = '';
				$this->NappUser->save($update);	
				$this->Session->write('pdfaceess',1);
				$this->Session->delete('opt');
				$this->Session->setFlash('Thank for verifying your account.','default',array('class' => 'alert alert-success'));
				$this->redirect('index');
				
			}else{
				$this->Session->setFlash('Wrong otp password. Please try again.','default',array('class' => 'alert alert-danger'));
				$this->redirect('index');
			}	
		}else if(!empty($opt)){
			$this->set('is_access',1);
		}else if(!empty($pdfaceess)){
			$this->set('is_access',2);
		}else if(empty($pdfaceess)){			
			$rand = rand(000000,999999);			
			$update['NappUser']['id'] = $user_id;
			$update['NappUser']['otp'] = $rand;
			$this->NappUser->save($update);	
			
			$userArr = $this->NappUser->find('first',array('conditions'=>array('NappUser.id'=>$user_id)));				
			$name = $userArr['NappUser']['name'].' '.$userArr['NappUser']['lname'];			
			$to = $userArr['NappUser']['email'];								
			$subject= SITENAME." :: Secure One-Time-Password (OTP) for DuroLab Access. OTP is ".$rand;				
			$template_name = 'durolabotp';										
			$variables = array('email'=>$to,'name'=>$name,'otp'=>$rand);			
			try{
				$this->sendemail($to,$subject,$template_name,$variables);
			}catch (Exception $e){
				
			}
			if(!empty($userArr['NappUser']['mobile_number']) && ($userArr['NappUser']['is_active_otp'] == 1)){
				$phones = str_replace(' ','',$userArr['NappUser']['mobile_number']);
				$phone = ltrim($phones,'+');
				$to = '+'.$phone;
				$sid = TSID;	
				$token = TOKEN;							
				$from = FROM_NUMBER;	
				$body = "Hi, Secure OTP for DuroLab Access. OTP is ".$rand;						
				$this->Twilio->AccountSid = TSID;   
				$this->Twilio->AccountToken = TOKEN;						
				$response = $this->Twilio->sendsms($to,$from,$body);
			}
			
			$this->Session->write('opt',$rand);			
			$this->Session->write('name',$name);			
			$this->Session->setFlash('OTP sent at email. Please check your mail and verify.','default',array('class' => 'alert alert-success'));
			$this->redirect('index');
		}	

		$task_id = $this->TaskAssign->find('list',array('conditions'=>array('TaskAssign.emp_id'=>$user_id),'fields'=>array('TaskAssign.task_id')));	
		$this->Task->bindModel(
		array('hasMany' => array('TaskAssign' => array(
			'className' => 'TaskAssign',			 
			'foreignKey' => 'task_id',				
			'conditions' => array(),
			'fields' => '',
			'order' => array(),
		))));
		$this->TaskAssign->bindModel(
		array('belongsTo' => array('NappUser' => array(
			'className' => 'NappUser',			 
			'foreignKey' => 'emp_id',				
			'conditions' => array(),
			'fields' => array('name','lname'),
			'order' => array(),
		))));
		$this->Task->bindModel(
		array('belongsTo' => array('NappUser' => array(
			'className' => 'NappUser',			 
			'foreignKey' => 'emp_id',				
			'conditions' => array(),
			'fields' => array('name','lname'),
			'order' => array(),
		)))); 
		$this->Task->bindModel(
		array('belongsTo' => array('User' => array(
			'className' => 'User',			 
			'foreignKey' => 'admin_id',				
			'conditions' => array(),
			'fields' => array('name'),
			'order' => array(),
		)))); 
		$this->Task->recursive = 2;
		
		$durolab_type = $this->Session->read('durolab_type');
		
		if(!empty($keyowrd)){
			#$this->paginate = array('conditions'=>array('OR'=>array('Task.emp_id'=>$user_id),'Task.task_id LIKE'=>'%'.$keyowrd.'%','Task.task_type'=>$durolab_type),'order'=>array('Task.id'=>'DESC'),'page' => 1, 'limit' => 25);
			$this->paginate = array('conditions'=>array('OR'=>array('Task.title LIKE'=>'%'.$keyowrd.'%','Task.task_id LIKE'=>'%'.$keyowrd.'%'),'Task.emp_id'=>$user_id,'Task.task_type'=>$durolab_type),'order'=>array('Task.id'=>'DESC'),'page' => 1, 'limit' => 25);
		}else{
			$this->paginate = array('conditions'=>array('OR'=>array('Task.emp_id'=>$user_id,'Task.id'=>$task_id),'Task.task_type'=>$durolab_type),'order'=>array('Task.id'=>'DESC'),'page' => 1, 'limit' => 25);
		}	
		
		
		
		$task = $this->Paginator->paginate('Task');		
		$this->set('task', $task);		
		$this->set('keyowrd', $keyowrd);		
		// echo '<pre>';
		// print_r($task);
		// die();
		#NappUser
		
		$UserPermission = $this->UserPermission->find('first',array('conditions'=>array('UserPermission.user_id'=>$user_id,'UserPermission.permssion_id'=>5)));
		$this->set('UserPermission', $UserPermission);
		
		$usernewArr = $this->NappUser->find('first',array('conditions'=>array('NappUser.id'=>$user_id)));
		$this->set('usernewArr', $usernewArr);
	}
	
	
	
	function download($id){
		
		$this->autoRender = false ;
		$this->checkSatffSession(); 
		
		$TaskDocument = $this->TaskDocument->find('first',array('conditions'=>array('TaskDocument.id'=>$id)));
		// echo '<pre>';
		// print_r($TaskDocument);
		// die();	
	
		$paths = WWW_ROOT.'document/'.$TaskDocument['TaskDocument']['document'].'/';
		echo $path =  $paths.$TaskDocument['TaskDocument']['filename'];
		if (file_exists($path)) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename='.basename($path));
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($path));
			ob_clean();
			flush();
			readfile($path);
			exit;
		}else{

		}	
		
	}
	function admin_completetask($task_id=null,$status=null){
		
		$this->layout='admin_layout';
		$this->set('title_for_layout',SITENAME.' :: Completed Task');		
		$this->checkAdminSession(); 
		
		$user_id= $this->Session->read('User.id');
		if(!empty($this->request->data)){
		if(!empty($task_id)){
			
			$this->Task->bindModel(
			array('hasMany' => array('TaskAssign' => array(
				'className' => 'TaskAssign',			 
				'foreignKey' => 'task_id',				
				'conditions' => array(),
				'fields' => '',
				'order' => array(),
			))));
			$this->TaskAssign->bindModel(
			array('belongsTo' => array('NappUser' => array(
				'className' => 'NappUser',			 
				'foreignKey' => 'emp_id',				
				'conditions' => array(),
				'fields' => array('name','lname','email'),
				'order' => array(),
			))));
			$this->Task->recursive = 2;
			$taskArr = $this->Task->find('first',array('conditions'=>array('Task.task_id'=>$task_id,'Task.task_status <'=>3)));
			if(!empty($taskArr) && !empty($status)){
				$title = $taskArr['Task']['title'];
				$results_summary = $this->request->data['Task']['results_summary'];
				if($status == 'completed'){
					$update['Task']['id'] = $taskArr['Task']['id'];
					$update['Task']['task_status'] = 3;
					$update['Task']['results_summary'] = $results_summary;
					$update['Task']['taskcompletetime'] = date('Y-m-d H:i:s');
					$this->Task->save($update);					
					// echo '<pre>';
					// print_r($taskArr);
					// die();
					if(!empty($taskArr)){
						foreach($taskArr['TaskAssign'] as $TaskAssigns){
							if(!empty($TaskAssigns['NappUser']['email'])){
								$sendername = $this->Session->read('User.name');					
								$receivername = $TaskAssigns['NappUser']['name'].' '.$TaskAssigns['NappUser']['lname'];						
								$to = $TaskAssigns['NappUser']['email'];															
								$subject= SITENAME." :: Project Number(#".$task_id.") completed by ".$sendername;				
								$template_name = 'task_completed';		
								
								$variables = array('results_summary'=>$results_summary,'sendername'=>$sendername,'receivername'=>$receivername,'task_task_id'=>$task_id,'task_title'=>$title);
								try{
									//$this->sendemail($to,$subject,$template_name,$variables);
								}catch (Exception $e){
									
								}							
							}	
						}

						$userArr = $this->User->find('all',array('conditions'=>array('User.user_id !='=>$user_id)));
						if(!empty($userArr)){
							foreach($userArr as $userArrs){
								$sendername = $this->Session->read('User.name');					
								$receivername = $userArrs['User']['name'];						
								$to = $userArrs['User']['email'];																		
								$subject= SITENAME." :: Project Number(#".$task_id.") completed by ".$sendername;				
								$template_name = 'task_completed';		
								
								$variables = array('results_summary'=>$results_summary,'sendername'=>$sendername,'receivername'=>$receivername,'task_task_id'=>$task_id,'task_title'=>$title);
								try{
									//$this->sendemail($to,$subject,$template_name,$variables);
								}catch (Exception $e){
									
								}	
							}	
						}	
					}
				} 	
			}	
		}	
		$this->Session->setFlash('Task completed successfully.','default',array('class' => 'alert alert-success'));
		$this->redirect('index');
		}
	}	
	function completetask($task_id=null,$status=null){
				
		$this->layout='staff_inner_layout';
		$this->set('title_for_layout',SITENAME.' :: Completed Task');		
		$this->checkSatffSession(); 				
		$user_id=$this->Session->read('Customer.id');
	
		if(!empty($this->request->data)){
					
			if(!empty($task_id)){
				
				$this->Task->bindModel(
				array('hasMany' => array('TaskAssign' => array(
					'className' => 'TaskAssign',			 
					'foreignKey' => 'task_id',				
					'conditions' => array(),
					'fields' => '',
					'order' => array(),
				))));
				$this->TaskAssign->bindModel(
				array('belongsTo' => array('NappUser' => array(
					'className' => 'NappUser',			 
					'foreignKey' => 'emp_id',				
					'conditions' => array(),
					'fields' => array('name','lname','email'),
					'order' => array(),
				))));
				$this->Task->recursive = 2;
				$taskArr = $this->Task->find('first',array('conditions'=>array('Task.task_id'=>$task_id,'Task.task_status <'=>3)));
				if(!empty($taskArr) && !empty($status)){
					$title = $taskArr['Task']['title'];
					$results_summary = $this->request->data['Task']['results_summary'];
					if($status == 'completed'){
						$update['Task']['id'] = $taskArr['Task']['id'];
						$update['Task']['task_status'] = 3;
						$update['Task']['results_summary'] = $results_summary;
						$update['Task']['taskcompletetime'] = date('Y-m-d H:i:s');
						$this->Task->save($update);					
						
						if(!empty($taskArr)){
							foreach($taskArr['TaskAssign'] as $TaskAssigns){
								if(!empty($TaskAssigns['NappUser']['email'])){
									$sendername = $this->Session->read('Customer.name');					
									$receivername = $TaskAssigns['NappUser']['name'].' '.$TaskAssigns['NappUser']['lname'];						
									$to = $TaskAssigns['NappUser']['email'];															
									$subject= SITENAME." :: Project Number(#".$task_id.") completed by ".$sendername;				
									$template_name = 'task_completed';		
									
									$variables = array('results_summary'=>$results_summary,'sendername'=>$sendername,'receivername'=>$receivername,'task_task_id'=>$task_id,'task_title'=>$title);
									try{
										$this->sendemail($to,$subject,$template_name,$variables);
									}catch (Exception $e){
										
									}							
								}	
							}

							$userArr = $this->User->find('all');
							if(!empty($userArr)){
								foreach($userArr as $userArrs){
									$sendername = $this->Session->read('Customer.name');					
									$receivername = $userArrs['User']['name'];						
									$to = $userArrs['User']['email'];																		
									$subject= SITENAME." :: Project Number(#".$task_id.") completed by ".$sendername;				
									$template_name = 'task_completed';		
									
									$variables = array('results_summary'=>$results_summary,'sendername'=>$sendername,'receivername'=>$receivername,'task_task_id'=>$task_id,'task_title'=>$title);
									try{
										$this->sendemail($to,$subject,$template_name,$variables);
									}catch (Exception $e){
										
									}	
								}	
							}	
						}
					} 	
				}	
			}
			$this->Session->setFlash('Task completed successfully.','default',array('class' => 'alert alert-success'));
			$this->redirect('index');
		}
		$options = array('conditions' => array('Task.task_id' => $task_id));
		$task = $this->Task->find('first', $options);			
		$this->request->data = $task;			
		$this->set('task',$task);
	
	}	
	function taskdocument($task_id=null){
		
		$this->layout='staff_inner_layout';
		$this->set('title_for_layout',SITENAME.' Add Task');
		$this->checkSatffSession(); 
		
		$this->Task->bindModel(
		array('hasMany' => array('TaskDocument' => array(
			'className' => 'TaskDocument',			 
			'foreignKey' => 'task_id',				
			'conditions' => array(),
			'fields' => '',
			'order' => array('TaskDocument.id'=>'DESC'),
		))));
		$this->TaskDocument->bindModel(
		array('belongsTo' => array('NappUser' => array(
			'className' => 'NappUser',			 
			'foreignKey' => 'employe_id',				
			'conditions' => array(),
			'fields' => array('name','lname'),
			'order' => array(),
		))));
		$this->TaskDocument->bindModel(
		array('belongsTo' => array('User' => array(
			'className' => 'User',			 
			'foreignKey' => 'admin_id',				
			'conditions' => array(),
			'fields' => array('name'),
			'order' => array(),
		))));
		$this->Task->recursive = 2;
		$taskArr = $this->Task->find('first',array('conditions'=>array('Task.task_id'=>$task_id)));
		$this->set('taskArr',$taskArr);	
		
		/* echo '<pre>';
		print_r($taskArr);
		die(); */
		
	}	
	
	function uploaddoc($task_id=null){
	
		$this->layout='staff_inner_layout';
		$this->set('title_for_layout',SITENAME.' Add Task');
		$this->checkSatffSession(); 
		$user_id=$this->Session->read('Customer.id');
		
		$taskArr = $this->Task->find('first',array('conditions'=>array('Task.task_id'=>$task_id)));
		$this->set('taskArr',$taskArr);	
		if(empty($taskArr)){
			$this->redirect('index');	
		}
		
		$foldername = $taskArr['Task']['task_id'];	
		if ($this->request->is('post')) {
			$path  = WWW_ROOT.'document/'.$foldername;	
			
		
			if(!empty($_FILES['document']['name'])){
				$i=0;
				foreach($_FILES['document']['name'] as $name){					
					$ext = pathinfo($name, PATHINFO_EXTENSION);
					$filename =  time().'_'.$name;
					$tempname = $_FILES['document']['tmp_name'][$i];						
					move_uploaded_file($tempname,$path.'/'.$filename);
					
					$TaskDocument['TaskDocument']['id'] = '';
					$TaskDocument['TaskDocument']['title'] =  $this->request->data['title'];
					$TaskDocument['TaskDocument']['employe_id'] =  $user_id;
					$TaskDocument['TaskDocument']['task_id'] =  $taskArr['Task']['id'];	
					$TaskDocument['TaskDocument']['document'] =  $foldername;
					$TaskDocument['TaskDocument']['filename'] =  $filename;
					$TaskDocument['TaskDocument']['ext'] =  $ext;
					$TaskDocument['TaskDocument']['created'] =  date('Y-m-d H:i:s');
					$TaskDocument['TaskDocument']['is_upload'] =  0; // admin uploaded
					
					$this->TaskDocument->save($TaskDocument);					
					$i++;					
				}
			}
			$this->redirect('taskdocument/'.$task_id);
		}	
	}	
	
	public function project_enquiry_add() {
		
		$this->layout='staff_inner_layout';
		$this->set('title_for_layout',SITENAME.' Add Task');
		$this->checkSatffSession(); 
		$user_id=$this->Session->read('Customer.id');
		
		$UserPermission = $this->UserPermission->find('first',array('conditions'=>array('UserPermission.user_id'=>$user_id,'UserPermission.permssion_id'=>5)));
		if(empty($UserPermission)){ 
			$this->Session->setFlash('Sorry you have no permission to access','default',array('class' => 'alert alert-danger'));
			return $this->redirect(array('action' => 'index'));
		}
		
		$durolab_type = $this->Session->read('durolab_type');
		if ($this->request->is('post')) {
			
			// echo '<pre>';
			// print_r($this->request->data);
			// die();
			
			$foldername = $this->request->data['Task']['task_id'];
			$path  = WWW_ROOT.'document/'.$foldername;						
			$this->request->data['Task']['task_type'] = $durolab_type;
			$this->request->data['Task']['emp_id'] = $user_id;
			$this->request->data['Task']['task_id'] = $foldername;
			$this->request->data['Task']['created'] = date('Y-m-d H:i:s');
			$this->request->data['Task']['last_update'] = date('Y-m-d H:i:s');
			$this->Task->create();			
			if ($this->Task->save($this->request->data)) {
				
				$userArr = $this->User->find('all');
				if(!empty($userArr)){
					foreach($userArr as $userArrs){
						// sender name
						$sendername = $this->Session->read('Customer.name').' '.$this->Session->read('Customer.lname');					
						$receivername = $userArrs['User']['name'];							
						$to = $userArrs['User']['email'];															
						$subject= SITENAME." :: New task created by ".$sendername;				
						$template_name = 'assign_task';
						$url = SITEURL.'tasks/adminautologin/'.base64_encode($userArrs['User']['id']).'/task/'.$durolab_type;	
						$variables = array('sendername'=>$sendername,'receivername'=>$receivername,'task_type'=>$durolab_type,'task_task_id'=>$foldername,'task_title'=>$this->request->data['Task']['title'],'user_id'=>$userArrs['User']['id'],'url'=>$url);	
					
						try{
							$this->sendemail($to,$subject,$template_name,$variables);
						}catch (Exception $e){
							
						}
							
					}								
				}			
				
				$task_id = $this->Task->id;
				if (!file_exists($path)) {
					mkdir($path, 0777, true);
				}
				
				if(!empty($_FILES['document']['name'])){
					$i=0;
					foreach($_FILES['document']['name'] as $name){
						
						$ext = pathinfo($name, PATHINFO_EXTENSION);
						$filename =  time().'_'.$name;
						$tempname = $_FILES['document']['tmp_name'][$i];						
						move_uploaded_file($tempname,$path.'/'.$filename);
						
						$TaskDocument['TaskDocument']['id'] = '';
						$TaskDocument['TaskDocument']['employe_id'] =  $user_id;
						$TaskDocument['TaskDocument']['task_id'] =  $task_id;
						$TaskDocument['TaskDocument']['document'] =  $foldername;
						$TaskDocument['TaskDocument']['filename'] =  $filename;
						$TaskDocument['TaskDocument']['ext'] =  $ext;
						$TaskDocument['TaskDocument']['created'] =  date('Y-m-d H:i:s');
						$TaskDocument['TaskDocument']['is_upload'] =  0; // admin uploaded
						$this->TaskDocument->save($TaskDocument);
						
						$i++;					
					}	
				}
				
				if(!empty($this->request->data['employe_id'])){
					$Tasks = $this->Task->find('first',array('conditions'=>array('Task.id'=>$task_id),'fields'=>array('Task.task_type','Task.title','Task.task_id')));
					
					$task_type = $Tasks['Task']['task_type'];
					$task_task_id = $Tasks['Task']['task_id'];
					$task_title = $Tasks['Task']['title'];
										
					$this->TaskAssign->query('delete from task_assigns where task_id='.$task_id.'');
					// echo 'delete from task_assigns where task_id='.$task_id.'';
					// die();
					foreach($this->request->data['employe_id'] as $employe_ids){
						
						$userArr = $this->NappUser->find('first',array('conditions'=>array('NappUser.id'=>$employe_ids),'fields'=>array('NappUser.id','NappUser.name','NappUser.lname','NappUser.email')));
							if(!empty($userArr)){
								// sender name
								$sendername = $this->Session->read('Customer.name').' '.$this->Session->read('Customer.lname');					
								$receivername = $userArr['NappUser']['name'].' '.$userArr['NappUser']['lname'];							
								$to = $userArr['NappUser']['email'];								
								//$to = 'web@xoroglobal.com';								
								$subject= SITENAME." :: New task assigned by ".$sendername;				
								$template_name = 'assign_task';		
								$url = SITEURL.'tasks/autologin/'.base64_encode($userArr['NappUser']['id']).'/task/'.$task_type;	
								
								$variables = array('sendername'=>$sendername,'receivername'=>$receivername,'task_type'=>$task_type,'task_task_id'=>$task_task_id,'task_title'=>$task_title,'user_id'=>$userArr['NappUser']['id'],'url'=>$url);	
							
								try{
									$this->sendemail($to,$subject,$template_name,$variables);
								}catch (Exception $e){
									
								}								
							}
											
						$task_assigns['TaskAssign']['id'] = '';
						$task_assigns['TaskAssign']['emp_id'] = $employe_ids;
						$task_assigns['TaskAssign']['task_id'] = $task_id;
						$this->TaskAssign->save($task_assigns);
					}	
				}
				
				$this->Session->setFlash('The task has been added','default',array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('The task could not be saved. Please, try again.','default',array('class' => 'alert alert-danger'));
			}
		}
		//$employeArr = $this->Employee->find('all',array('conditions'=>array('Employee.status'=>1)));
		//$this->set('employeArr',$employeArr);
		$Task = $this->Task->find('first',array('order'=>array('Task.id'=>'DESC')));
		$durolab_type = $this->Session->read('durolab_type');
		$foldername = 10000 + $Task['Task']['id'];
		if($durolab_type == 'project_enquiry'){
			$foldername = 'PE-'.$foldername;
		}else if($durolab_type == 'product'){
			$foldername = 'TP-'.$foldername;
		}else{
			$foldername = 'TS-'.$foldername;
		}	
		$this->set('foldername',$foldername);
		
		$empids = $this->UserPermission->find('list',array('conditions'=>array('UserPermission.permssion_id'=>array(6,7)),'fields'=>array('UserPermission.user_id')));	
			
		$user_id=$this->Session->read('Customer.id');			
		$employeArr = $this->NappUser->find('all',array('conditions'=>array('NappUser.is_staff_id'=>1,'NappUser.id'=>$empids,'NappUser.id !='=>$user_id)));		
		$this->set('employeArr',$employeArr);
	}
	/**
	* Author:Ranjit
	* Discription:Add Category
	* @return void
*/
	public function add() {
		
		$this->layout='staff_inner_layout';
		$this->set('title_for_layout',SITENAME.' Add Task');
		$this->checkSatffSession(); 
		$user_id=$this->Session->read('Customer.id');
		
		$UserPermission = $this->UserPermission->find('first',array('conditions'=>array('UserPermission.user_id'=>$user_id,'UserPermission.permssion_id'=>5)));
		if(empty($UserPermission)){ 
			$this->Session->setFlash('Sorry you have no permission to access','default',array('class' => 'alert alert-danger'));
			return $this->redirect(array('action' => 'index'));
		}
		
		$durolab_type = $this->Session->read('durolab_type');
		if ($this->request->is('post')) {
				
			$foldername = $this->request->data['Task']['task_id'];
			$path  = WWW_ROOT.'document/'.$foldername;						
			$this->request->data['Task']['task_type'] = $durolab_type;
			$this->request->data['Task']['emp_id'] = $user_id;
			$this->request->data['Task']['task_id'] = $foldername;
			$this->request->data['Task']['created'] = date('Y-m-d H:i:s');
			$this->request->data['Task']['last_update'] = date('Y-m-d H:i:s');
			
			$this->Task->create();			
			if ($this->Task->save($this->request->data)) {
				
				$userArr = $this->User->find('all');
				if(!empty($userArr)){
					foreach($userArr as $userArrs){
						// sender name
						$sendername = $this->Session->read('Customer.name').' '.$this->Session->read('Customer.lname');					
						$receivername = $userArrs['User']['name'];							
						$to = $userArrs['User']['email'];															
						$subject= SITENAME." :: New task created by ".$sendername;				
						$template_name = 'assign_task';
						$url = SITEURL.'tasks/adminautologin/'.base64_encode($userArrs['User']['id']).'/task/'.$durolab_type;	
						$variables = array('sendername'=>$sendername,'receivername'=>$receivername,'task_type'=>$durolab_type,'task_task_id'=>$foldername,'task_title'=>$this->request->data['Task']['title'],'user_id'=>$userArrs['User']['id'],'url'=>$url);	
					
						try{
							$this->sendemail($to,$subject,$template_name,$variables);
						}catch (Exception $e){
							
						}
							
					}								
				}			
				
				$task_id = $this->Task->id;
				if (!file_exists($path)) {
					mkdir($path, 0777, true);
				}
				
				if(!empty($_FILES['document']['name'])){
					$i=0;
					foreach($_FILES['document']['name'] as $name){						
						$ext = pathinfo($name, PATHINFO_EXTENSION);
						$filename =  time().'_'.$name;
						$tempname = $_FILES['document']['tmp_name'][$i];						
						move_uploaded_file($tempname,$path.'/'.$filename);
						
						$TaskDocument['TaskDocument']['id'] = '';
						$TaskDocument['TaskDocument']['employe_id'] =  $user_id;
						$TaskDocument['TaskDocument']['task_id'] =  $task_id;
						$TaskDocument['TaskDocument']['document'] =  $foldername;
						$TaskDocument['TaskDocument']['filename'] =  $filename;
						$TaskDocument['TaskDocument']['ext'] =  $ext;
						$TaskDocument['TaskDocument']['created'] =  date('Y-m-d H:i:s');
						$TaskDocument['TaskDocument']['is_upload'] =  0; // admin uploaded
						$this->TaskDocument->save($TaskDocument);						
						$i++;					
					}	
				}
				
				if(!empty($this->request->data['employe_id'])){
					$Tasks = $this->Task->find('first',array('conditions'=>array('Task.id'=>$task_id),'fields'=>array('Task.task_type','Task.title','Task.task_id')));
					
					$task_type = $Tasks['Task']['task_type'];
					$task_task_id = $Tasks['Task']['task_id'];
					$task_title = $Tasks['Task']['title'];
										
					$this->TaskAssign->query('delete from task_assigns where task_id='.$task_id.'');
					// echo 'delete from task_assigns where task_id='.$task_id.'';
					// die();
					foreach($this->request->data['employe_id'] as $employe_ids){
						
						$userArr = $this->NappUser->find('first',array('conditions'=>array('NappUser.id'=>$employe_ids),'fields'=>array('NappUser.id','NappUser.name','NappUser.lname','NappUser.email')));
							if(!empty($userArr)){
								// sender name
								$sendername = $this->Session->read('Customer.name').' '.$this->Session->read('Customer.lname');					
								$receivername = $userArr['NappUser']['name'].' '.$userArr['NappUser']['lname'];							
								$to = $userArr['NappUser']['email'];								
								//$to = 'web@xoroglobal.com';								
								$subject= SITENAME." :: New task assigned by ".$sendername;				
								$template_name = 'assign_task';		
								$url = SITEURL.'tasks/autologin/'.base64_encode($userArr['NappUser']['id']).'/task/'.$task_type;	
								
								$variables = array('sendername'=>$sendername,'receivername'=>$receivername,'task_type'=>$task_type,'task_task_id'=>$task_task_id,'task_title'=>$task_title,'user_id'=>$userArr['NappUser']['id'],'url'=>$url);	
							
								try{
									$this->sendemail($to,$subject,$template_name,$variables);
								}catch (Exception $e){
									
								}								
							}
											
						$task_assigns['TaskAssign']['id'] = '';
						$task_assigns['TaskAssign']['emp_id'] = $employe_ids;
						$task_assigns['TaskAssign']['task_id'] = $task_id;
						$this->TaskAssign->save($task_assigns);
					}	
				}
				
				$this->Session->setFlash('The task has been added','default',array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('The task could not be saved. Please, try again.','default',array('class' => 'alert alert-danger'));
			}
		}
		//$employeArr = $this->Employee->find('all',array('conditions'=>array('Employee.status'=>1)));
		//$this->set('employeArr',$employeArr);
		$Task = $this->Task->find('first',array('order'=>array('Task.id'=>'DESC')));
		$durolab_type = $this->Session->read('durolab_type');
		$foldername = 10000 + $Task['Task']['id'];
		
		if($durolab_type == 'project_enquiry'){
			$foldername = 'PE-'.$foldername;
		}else if($durolab_type == 'product'){
			$foldername = 'TP-'.$foldername;
		}else{
			$foldername = 'TS-'.$foldername;
		}	
		$this->set('foldername',$foldername);
		if($durolab_type != 'project_enquiry'){
			$empids = $this->UserPermission->find('list',array('conditions'=>array('UserPermission.permssion_id'=>array(6,7)),'fields'=>array('UserPermission.user_id')));	
		}else{
			$empids = $this->UserPermission->find('list',array('conditions'=>array('UserPermission.permssion_id'=>array(9)),'fields'=>array('UserPermission.user_id')));	
		}	
		$user_id=$this->Session->read('Customer.id');			
		$employeArr = $this->NappUser->find('all',array('conditions'=>array('NappUser.is_staff_id'=>1,'NappUser.id'=>$empids,'NappUser.id !='=>$user_id)));		
		$this->set('employeArr',$employeArr);
	}
	
	function movetoproduct($id){
		
		$this->checkSatffSession(); 
		$this->autoRender = false;	
		
		$sendername = $this->Session->read('Customer.name');
		$this->Task->bindModel(
		array('hasMany' => array('TaskAssign' => array(
			'className' => 'TaskAssign',			 
			'foreignKey' => 'task_id',				
			'conditions' => array(),
			'fields' => '',
			'order' => array(),
		))));
		$this->TaskAssign->bindModel(
		array('belongsTo' => array('NappUser' => array(
			'className' => 'NappUser',			 
			'foreignKey' => 'emp_id',				
			'conditions' => array(),
			'fields' => array('name','lname','email'),
			'order' => array(),
		))));
		$this->Task->recursive = 2;
		
		$task = $this->Task->find('first', array('conditions'=>array('Task.id'=>$id)));
		if(!empty($task)){
			$oldtask = $task['Task']['task_id'];
			$task_id = str_replace('PE','PR',$task['Task']['task_id']);
			$updateArr['Task']['task_type'] = 'product';
			$updateArr['Task']['task_id'] = $task_id;
			$updateArr['Task']['task_status'] = 2;
			$this->Task->save($updateArr);
			
			if(!empty($task['TaskAssign'])){
				foreach($task['TaskAssign'] as $TaskAssigns){
					
					$name = $TaskAssigns['NappUser']['name'].' '.$TaskAssigns['NappUser']['lname'];										
					$email = $TaskAssigns['NappUser']['email'];						
					$subject= SITENAME." :: Task Moved To Product Development by ".$sendername;				
					$template_name = 'move_task';				
					$variables = array('name'=>$name,'sendername'=>$sendername,'task_id'=>$task_id,'oldtask'=>$oldtask);			
					try{
						$this->sendemail($email,$subject,$template_name,$variables);
					}catch (Exception $e){
						
					}					
				}	
			}	
			
		}			
	}	
	
	/**
	* Author:Ranjit
	* Discription:Edit The Category
	* @throws NotFoundException
	* @param string $id
	* @return void
 */
	public function edit($id = null) {
		$this->layout='staff_inner_layout';
		$this->set('title_for_layout',SITENAME.' Edit Task');
		$this->checkSatffSession(); 
		if (!$this->Task->exists($id)) {
			throw new NotFoundException(__('Invalid sevice task'));
		}
		if ($this->request->is(array('post', 'put'))) {
			
			if(!empty($this->request->data['Task']['assigned_date'])){
				$this->request->data['Task']['assigned_date'] = date('Y-m-d',strtotime($this->request->data['Task']['assigned_date']));
			}	
			if(!empty($this->request->data['Task']['task_completion_date'])){
				$this->request->data['Task']['task_completion_date'] = date('Y-m-d',strtotime($this->request->data['Task']['task_completion_date']));
			}	
			$this->request->data['Task']['id'] = $id;
			if ($this->Task->save($this->request->data)) {
				$this->Session->setFlash('The task has been updated','default',array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('The task could not be saved. Please, try again.','default',array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('Task.' . $this->Task->primaryKey => $id));
			$task = $this->Task->find('first', $options);			
			$this->request->data = $task;			
			$this->set('task',$task);
			$user_id=$this->Session->read('Customer.id');			
			if($user_id != $task['Task']['emp_id']){
				$this->Session->setFlash('Sorry, you have no permission to edit task.','default',array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			}				
		}
	}

	/**
 	* Author:Ranjit
	* Discription:Delete The Category
	* @throws NotFoundException
	* @param string $id
	* @return void
	*/
	public function delete($id = null) {
		$this->autoRender  = false;
		$this->set('title_for_layout',SITENAME.' Task Delete');
		$this->checkSatffSession(); 
		$this->Task->id = $id;
		if (!$this->Task->exists()) {
			throw new NotFoundException(__('Invalid Task'));
		}
		//$this->request->allowMethod('post', 'delete');
		if ($this->Task->delete()) {
			$this->Session->setFlash('The task has been deleted.','default',array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash('The task could not be deleted.Please, try again.','default',array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
	
	function commentdownload($id){
		
		$this->autoRender = false ;
		$this->checkSatffSession(); 
		$id = base64_decode(base64_decode($id));
		
		$this->TaskComment->bindModel(
		array('belongsTo' => array('Task' => array(
			'className' => 'Task',			 
			'foreignKey' => 'task_ids',				
			'conditions' => array(),
			'fields' => array(),
			'order' => array(),
		))));
		
		
		$TaskDocument = $this->TaskComment->find('first',array('conditions'=>array('TaskComment.id'=>$id)));
		// echo '<pre>';
		// print_r($TaskDocument);
		// die();	
	
		$paths = WWW_ROOT.'document/'.$TaskDocument['Task']['task_id'].'/';
		$path =  $paths.$TaskDocument['TaskComment']['documents']; 
		if (file_exists($path)) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename='.basename($path));
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($path));
			ob_clean();
			flush();
			readfile($path);
			exit;
		}else{

		}	
		
	}
	
	function comment($task_id=null){
		
		$this->layout='staff_inner_layout';
		$this->set('title_for_layout',SITENAME.' Assign Task');
		$this->checkSatffSession(); 
		$user_id=$this->Session->read('Customer.id');
		$sendername = $this->Session->read('Customer.name');
		
		$this->Task->bindModel(
		array('hasMany' => array('TaskAssign' => array(
		'className' => 'TaskAssign',    
		'foreignKey' => 'task_id',    
		'conditions' => array(),
		'fields' => array(),
		'order' => array(),
		))));	
		
		
		$this->TaskAssign->bindModel(
		array('belongsTo' => array('NappUser' => array(
			'className' => 'NappUser',			 
			'foreignKey' => 'emp_id',				
			'conditions' => array(),
			'fields' => array('name','lname','email'),
			'order' => array(),
		))));
		
		#TaskComment
		$this->Task->bindModel(
		array('hasMany' => array('TaskComment' => array(
		'className' => 'TaskComment',    
		'foreignKey' => 'task_ids',    
		'conditions' => array(),
		'fields' => array(),
		'order' => array('TaskComment.id'=>'DESC'),
		))));	
		$this->TaskComment->bindModel(
		array('belongsTo' => array('NappUser' => array(
			'className' => 'NappUser',			 
			'foreignKey' => 'emp_id',				
			'conditions' => array(),
			'fields' => array('name','lname'),
			'order' => array(),
		))));
		$this->TaskComment->bindModel(
		array('belongsTo' => array('User' => array(
			'className' => 'User',			 
			'foreignKey' => 'admin_id',				
			'conditions' => array(),
			'fields' => array('name'),
			'order' => array(),
		))));
		
		$this->Task->bindModel(
		array('hasMany' => array('TaskDocument' => array(
			'className' => 'TaskDocument',			 
			'foreignKey' => 'task_id',				
			'conditions' => array(),
			'fields' => '',
			'order' => array('TaskDocument.id'=>'DESC'),
		))));
		$this->TaskDocument->bindModel(
		array('belongsTo' => array('NappUser' => array(
			'className' => 'NappUser',			 
			'foreignKey' => 'employe_id',				
			'conditions' => array(),
			'fields' => array('name','lname'),
			'order' => array(),
		))));
		$this->TaskDocument->bindModel(
		array('belongsTo' => array('User' => array(
			'className' => 'User',			 
			'foreignKey' => 'admin_id',				
			'conditions' => array(),
			'fields' => array('name'),
			'order' => array(),
		))));
		$this->Task->recursive = 2;	
		$TaskAssignArr = $this->Task->find('first',array('conditions'=>array('Task.task_id'=>$task_id)));
		$this->set('TaskAssign',$TaskAssignArr);
		
		// echo '<pre>';
		// print_r($TaskAssignArr);
		// die();
	
		
		if(!empty($this->request->data)){
			
			if(!empty($_FILES['documents']['name'])){
				$name = $_FILES['documents']['name'];
				$tempname = $_FILES['documents']['tmp_name'];
				$path  = WWW_ROOT.'document/'.$task_id;
				$ext = pathinfo($name, PATHINFO_EXTENSION);
				$filename =  time().'_'.$name;										
				move_uploaded_file($tempname,$path.'/'.$filename);
				$insert['TaskComment']['documents'] = $filename;
			}	
			$insert['TaskComment']['comment'] = $this->request->data['comment'];
			$insert['TaskComment']['task_ids'] =$TaskAssignArr['Task']['id'];
			$insert['TaskComment']['emp_id'] =$TaskAssignArr['Task']['emp_id'];
			$insert['TaskComment']['type'] =0; // employee
			$insert['TaskComment']['created'] = date('Y-m-d H:i:s');
			$this->TaskComment->save($insert);
			
			 if($TaskAssignArr['Task']['is_notify'] == 1){
					
					
				if(!empty($TaskAssignArr['TaskAssign'])){
				
					foreach($TaskAssignArr['TaskAssign'] as $taskdoc){
						$receivername = $taskdoc['NappUser']['name'].' '.$taskdoc['NappUser']['lname'];
						$email = $taskdoc['NappUser']['email'];
						
						$subject = SITENAME.":- ".ucfirst($sendername)." comment on ".$task_id;
						$url = SITEURL.'tasks/comment/'.$task_id;
						
						$template_name = 'sendnotificaion';					
						$variables = array('task_id'=>$task_id,'name'=>$sendername,'receivername'=>$receivername,'url'=>$url,'comment'=>$this->request->data['comment']);	
						
						try{
							$this->sendemail($email,$subject,$template_name,$variables);
						}catch (Exception $e){
							
						}
					}	
				}	
			}
			
			$this->Session->setFlash('Comment posted successfully','default',array('class' => 'alert alert-success'));
			$this->redirect('comment/'.$task_id);	
		}	
		$this->set('task_id',$task_id);		
	}	
	
	
	public function assign($task_id=null) {
		
		$this->layout='staff_inner_layout';
		$this->set('title_for_layout',SITENAME.' Assign Task');
		$this->checkSatffSession(); 
		if ($this->request->is('post')) {
			
			
			isset($this->request->data['is_reminder'])? $is_reminder = $this->request->data['is_reminder'] : $is_reminder =0;
			isset($this->request->data['is_email'])? $is_email = $this->request->data['is_email'] : $is_email =0;
			isset($this->request->data['is_sms'])? $is_sms = $this->request->data['is_sms'] : $is_sms =0;
			isset($this->request->data['send_to'])? $send_to = $this->request->data['send_to'] : $send_to =0;
			isset($this->request->data['is_both'])? $is_both = $this->request->data['is_both'] : $is_both =0;
			isset($this->request->data['period'])? $period = $this->request->data['period'] : $period = '';
				
			$insertdata['Task']['is_reminder'] = $is_reminder;
			if($is_reminder == 1){
				$insertdata['Task']['is_email'] = $is_email;
				$insertdata['Task']['is_sms'] = $is_sms;
				$insertdata['Task']['is_both'] = $is_both;
				$insertdata['Task']['send_to'] = $send_to;
				$insertdata['Task']['period'] = $period;
			}else{
				$insertdata['Task']['is_email'] = 0;
				$insertdata['Task']['is_sms'] = 0;
				$insertdata['Task']['is_both'] = 0;
				$insertdata['Task']['send_to'] = 0;
				$insertdata['Task']['period'] = '';
			}		
			$insertdata['Task']['id'] = $task_id;
			$insertdata['Task']['task_status'] = 1;
			$insertdata['Task']['assigned_date'] = date('Y-m-d',strtotime($this->request->data['Task']['assigned_date']));
			
			$TaskAssignArrnew = $this->Task->find('first',array('conditions'=>array('Task.id'=>$task_id)));
			if($TaskAssignArrnew['Task']['assigndatetime'] == '0000-00-00 00:00:00'){
				$insertdata['Task']['assigndatetime'] = date('Y-m-d H:i:s');
			}				
			$insertdata['Task']['task_completion_date'] = date('Y-m-d',strtotime($this->request->data['Task']['task_completion_date']));
			
				
			if ($this->Task->save($insertdata)) {
									
				if(!empty($this->request->data['employe_id'])){
					$Tasks = $this->Task->find('first',array('conditions'=>array('Task.id'=>$task_id),'fields'=>array('Task.task_type','Task.title','Task.task_id')));
					
					$task_type = $Tasks['Task']['task_type'];
					$task_task_id = $Tasks['Task']['task_id'];
					$task_title = $Tasks['Task']['title'];
										
					$this->TaskAssign->query('delete from task_assigns where task_id='.$task_id.'');
					// echo 'delete from task_assigns where task_id='.$task_id.'';
					// die();
					foreach($this->request->data['employe_id'] as $employe_ids){
						isset($this->request->data['is_send']) ? $is_send =  $this->request->data['is_send'] : $is_send = 0;
						if($is_send == 1){
						$userArr = $this->NappUser->find('first',array('conditions'=>array('NappUser.id'=>$employe_ids),'fields'=>array('NappUser.id','NappUser.name','NappUser.lname','NappUser.email')));
							if(!empty($userArr)){
								// sender name
								$sendername = $this->Session->read('Customer.name').' '.$this->Session->read('Customer.lname');					
								$receivername = $userArr['NappUser']['name'].' '.$userArr['NappUser']['lname'];							
								$to = $userArr['NappUser']['email'];								
								//$to = 'web@xoroglobal.com';								
								$subject= SITENAME." :: New task assigned by ".$sendername;				
								$template_name = 'assign_task';		
								$url = SITEURL.'tasks/autologin/'.base64_encode($userArr['NappUser']['id']).'/task/'.$task_type;	
								
								$variables = array('sendername'=>$sendername,'receivername'=>$receivername,'task_type'=>$task_type,'task_task_id'=>$task_task_id,'task_title'=>$task_title,'user_id'=>$userArr['NappUser']['id'],'url'=>$url);	
							
								try{
									$this->sendemail($to,$subject,$template_name,$variables);
								}catch (Exception $e){
									
								}								
							}
						}						
						$task_assigns['TaskAssign']['id'] = '';
						$task_assigns['TaskAssign']['emp_id'] = $employe_ids;
						$task_assigns['TaskAssign']['task_id'] = $task_id;
						$this->TaskAssign->save($task_assigns);
					}	
				}	
				$this->Session->setFlash('Task assign successfully','default',array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Not assigned successfully. Please, try again.','default',array('class' => 'alert alert-danger'));
			}
		}
		$this->Task->bindModel(
		array('hasMany' => array('TaskAssign' => array(
		'className' => 'TaskAssign',    
		'foreignKey' => 'task_id',    
		'conditions' => array(),
		'fields' => array(),
		'order' => array(),
		))));		
		$TaskAssignArr = $this->Task->find('first',array('conditions'=>array('Task.id'=>$task_id)));
		$this->set('TaskAssign',$TaskAssignArr);
		
		$empids = $this->UserPermission->find('list',array('conditions'=>array('UserPermission.permssion_id'=>array(6,7)),'fields'=>array('UserPermission.user_id')));
		
		$user_id=$this->Session->read('Customer.id');			
		$employeArr = $this->NappUser->find('all',array('conditions'=>array('NappUser.is_staff_id'=>1,'NappUser.id'=>$empids,'NappUser.id !='=>$user_id)));		
		$this->set('employeArr',$employeArr);
	}
	
	function time_elapsed_string($datetime = null, $full = null) {
		$this->autoRender = false;
		$datetime = base64_decode($datetime);
		$now = new DateTime;
		$ago = new DateTime($datetime);
		$diff = $now->diff($ago);

		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;
		
		

		$string = array(
			'y' => 'year',
			'm' => 'month',
			'w' => 'week',
			'd' => 'day',
			'h' => 'hour',
			'i' => 'minute',
			's' => 'second',
		);
		foreach ($string as $k => &$v) {
			if ($diff->$k) {
				$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
			} else {
				unset($string[$k]);
			}
		}
		if (!$full) $string = array_slice($string, 0, 1);
		
		if(isset($string['y'])){
			$msg = $string['y']; 
		}else if(isset($string['m'])){
			$msg = $string['m']; 
		}else if(isset($string['w'])){
			$msg = $string['w']; 
		}else if(isset($string['d'])){
			$msg = $string['d']; 
		}else if(isset($string['h'])){
			$msg = $string['h']; 
		}else if(isset($string['m'])){
			$msg = $string['m']; 
		}else if(isset($string['1'])){
			$msg = $string['1']; 
		}
		
		echo $msg.' ago';
		//$string ? implode(', ', $string) . ' ago' : 'just now';
		
		//return $string ? implode(', ', $string) . ' ago' : 'just now';
	}
	
	public function admin_add() {
		
		$this->layout='admin_layout';
		$this->set('title_for_layout',SITENAME.' Add Task');
		$this->checkAdminSession(); 
		
		$user_id=$this->Session->read('User.id');	
		
		if ($this->request->is('post')) {
			
			
			
			
			/* $Task = $this->Task->find('first',array('order'=>array('Task.id'=>'DESC')));			
			$foldername = 10000 + $Task['Task']['id'];
			$durolab_type = $this->request->data['Task']['task_type'];
			if($durolab_type == 'product'){
				$foldername = 'PR-'.$foldername;
			}else{
				$foldername = 'TS-'.$foldername;
			}	 */			
			$durolab_type = $this->Session->read('durolab_type');						
			$this->request->data['Task']['task_type'] = $durolab_type;			
			$foldername =  $this->request->data['Task']['task_id'];
			$path  = WWW_ROOT.'document/'.$foldername;					
			$this->request->data['Task']['admin_id'] = $user_id;
			$this->request->data['Task']['task_id'] = $foldername;
			$this->request->data['Task']['created'] = date('Y-m-d H:i:s');
			$this->request->data['Task']['last_update'] = date('Y-m-d H:i:s');
			$this->Task->create();			
			// echo '<pre>';
			// print_r($this->request->data);
			// die();
			if ($this->Task->save($this->request->data)) {
				$task_id = $this->Task->id;
				if (!file_exists($path)) {
					mkdir($path, 0777, true);
				}				
				
				if(!empty($_FILES['document']['name'])){
					$i=0;
					foreach($_FILES['document']['name'] as $name){
						
						$ext = pathinfo($name, PATHINFO_EXTENSION);
						$filename =  time().'_'.$name;
						$tempname = $_FILES['document']['tmp_name'][$i];						
						move_uploaded_file($tempname,$path.'/'.$filename);
						
						$TaskDocument['TaskDocument']['id'] = '';
						$TaskDocument['TaskDocument']['admin_id'] =  $user_id;
						$TaskDocument['TaskDocument']['task_id'] =  $task_id;
						$TaskDocument['TaskDocument']['document'] =  $foldername;
						$TaskDocument['TaskDocument']['filename'] =  $filename;
						$TaskDocument['TaskDocument']['ext'] =  $ext;
						$TaskDocument['TaskDocument']['created'] =  date('Y-m-d H:i:s');
						$TaskDocument['TaskDocument']['is_upload'] =  0; // admin uploaded
						$this->TaskDocument->save($TaskDocument);						
						$i++;					
					}	
				}
				
				if(!empty($this->request->data['employe_id'])){
					$Tasks = $this->Task->find('first',array('conditions'=>array('Task.id'=>$task_id),'fields'=>array('Task.task_type','Task.title','Task.task_id')));
					
					$task_type = $Tasks['Task']['task_type'];
					$task_task_id = $Tasks['Task']['task_id'];
					$task_title = $Tasks['Task']['title'];
										
					$this->TaskAssign->query('delete from task_assigns where task_id='.$task_id.'');
					// echo 'delete from task_assigns where task_id='.$task_id.'';
					// die();
					foreach($this->request->data['employe_id'] as $employe_ids){
						
						$userArr = $this->NappUser->find('first',array('conditions'=>array('NappUser.id'=>$employe_ids),'fields'=>array('NappUser.id','NappUser.name','NappUser.lname','NappUser.email')));
							if(!empty($userArr)){
								// sender name
								$sendername = $this->Session->read('User.name');					
								$receivername = $userArr['NappUser']['name'].' '.$userArr['NappUser']['lname'];							
								$to = $userArr['NappUser']['email'];																
								$subject= SITENAME." :: New task assigned by ".$sendername;				
								$template_name = 'assign_task';		
								$url = SITEURL.'tasks/autologin/'.base64_encode($userArr['NappUser']['id']).'/task/'.$task_type;	
								
								$variables = array('sendername'=>$sendername,'receivername'=>$receivername,'task_type'=>$task_type,'task_task_id'=>$task_task_id,'task_title'=>$task_title,'user_id'=>$userArr['NappUser']['id'],'url'=>$url);	
							
								try{
									$this->sendemail($to,$subject,$template_name,$variables);
								}catch (Exception $e){
									
								}								
							}
											
						$task_assigns['TaskAssign']['id'] = '';
						$task_assigns['TaskAssign']['emp_id'] = $employe_ids;
						$task_assigns['TaskAssign']['task_id'] = $task_id;
						$this->TaskAssign->save($task_assigns);
					}	
				}
				$this->Session->setFlash('The task has been added','default',array('class' => 'alert alert-success'));
				$this->redirect('index');
			} else {
				$this->Session->setFlash('The task could not be saved. Please, try again.','default',array('class' => 'alert alert-danger'));
			}
		}
		$Task = $this->Task->find('first',array('order'=>array('Task.id'=>'DESC')));
		$durolab_type = $this->Session->read('durolab_type');
		$foldername = 10000 + $Task['Task']['id'];
		
		if($durolab_type == 'project_enquiry'){
			$foldername = 'PE-'.$foldername;
		}else if($durolab_type == 'product'){
			$foldername = 'TP-'.$foldername;
		}else{
			$foldername = 'TS-'.$foldername;
		}	
		//$employeArr = $this->Employee->find('all',array('conditions'=>array('Employee.status'=>1)));
		$this->set('foldername',$foldername);
		
		if($durolab_type != 'project_enquiry'){
			$empids = $this->UserPermission->find('list',array('conditions'=>array('UserPermission.permssion_id'=>array(6,7)),'fields'=>array('UserPermission.user_id')));	
		}else{
			$empids = $this->UserPermission->find('list',array('conditions'=>array('UserPermission.permssion_id'=>array(9)),'fields'=>array('UserPermission.user_id')));	
		}	
		
		$employeArr = $this->NappUser->find('all',array('conditions'=>array('NappUser.is_staff_id'=>1,'NappUser.id'=>$empids)));		
		$this->set('employeArr',$employeArr);
	}
	
	
	function admin_taskdocument($task_id=null){
		
		$this->layout='admin_layout';
		$this->set('title_for_layout',SITENAME.' Add Task');
		$this->checkAdminSession(); 
		
		$this->Task->bindModel(
		array('hasMany' => array('TaskDocument' => array(
			'className' => 'TaskDocument',			 
			'foreignKey' => 'task_id',				
			'conditions' => array(),
			'fields' => '',
			'order' => array('TaskDocument.id'=>'DESC'),
		))));
		$this->TaskDocument->bindModel(
		array('belongsTo' => array('NappUser' => array(
			'className' => 'NappUser',			 
			'foreignKey' => 'employe_id',				
			'conditions' => array(),
			'fields' => array('name','lname'),
			'order' => array(),
		))));
		$this->TaskDocument->bindModel(
		array('belongsTo' => array('User' => array(
			'className' => 'User',			 
			'foreignKey' => 'admin_id',				
			'conditions' => array(),
			'fields' => array('name'),
			'order' => array(),
		)))); 
		$this->Task->recursive = 2;
		$taskArr = $this->Task->find('first',array('conditions'=>array('Task.task_id'=>$task_id)));
		$this->set('taskArr',$taskArr);	
		
	 	// echo '<pre>';
		// print_r($taskArr);
		// die(); 
		 
	}
	function admin_uploaddoc($task_id=null){
	
		$this->layout='admin_layout';
		$this->set('title_for_layout',SITENAME.' Add Task');
		$this->checkAdminSession(); 
		$user_id=$this->Session->read('User.id');
		
		$taskArr = $this->Task->find('first',array('conditions'=>array('Task.task_id'=>$task_id)));
		$this->set('taskArr',$taskArr);	
		if(empty($taskArr)){
			$this->redirect('index');	
		}
		
		$foldername = $taskArr['Task']['task_id'];	
		if ($this->request->is('post')) {
			$path  = WWW_ROOT.'document/'.$foldername;	
			
		
			if(!empty($_FILES['document']['name'])){
				$i=0;
				foreach($_FILES['document']['name'] as $name){					
					$ext = pathinfo($name, PATHINFO_EXTENSION);
					$filename =  time().'_'.$name;
					$tempname = $_FILES['document']['tmp_name'][$i];						
					move_uploaded_file($tempname,$path.'/'.$filename);
					
					$TaskDocument['TaskDocument']['id'] = '';
					$TaskDocument['TaskDocument']['title'] =  $this->request->data['title'];
					$TaskDocument['TaskDocument']['admin_id'] =  $user_id;
					$TaskDocument['TaskDocument']['task_id'] =  $taskArr['Task']['id'];	
					$TaskDocument['TaskDocument']['document'] =  $foldername;
					$TaskDocument['TaskDocument']['filename'] =  $filename;
					$TaskDocument['TaskDocument']['ext'] =  $ext;
					$TaskDocument['TaskDocument']['created'] =  date('Y-m-d H:i:s');
					$TaskDocument['TaskDocument']['is_upload'] =  0; // admin uploaded
					
					$this->TaskDocument->save($TaskDocument);					
					$i++;					
				}
			}
			$this->redirect('taskdocument/'.$task_id);
		}	
	}	
	
	function admin_download($id){
		
		$this->autoRender = false ;
		$this->checkAdminSession(); 
		
		$TaskDocument = $this->TaskDocument->find('first',array('conditions'=>array('TaskDocument.id'=>$id)));
		// echo '<pre>';
		// print_r($TaskDocument);
		// die();	
	
		$paths = WWW_ROOT.'document/'.$TaskDocument['TaskDocument']['document'].'/';
		echo $path =  $paths.$TaskDocument['TaskDocument']['filename'];
		if (file_exists($path)) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename='.basename($path));
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($path));
			ob_clean();
			flush();
			readfile($path);
			exit;
		}else{

		}	
		
	}	
	
	function admin_comment($task_id=null){
		
		$this->layout='admin_layout';
		$this->set('title_for_layout',SITENAME.' Assign Task');
		$this->checkAdminSession(); 
		$user_id=$this->Session->read('User.id');
		$sendername=$this->Session->read('User.name');
		
		#TaskComment
		$this->Task->bindModel(
		array('hasMany' => array('TaskAssign' => array(
		'className' => 'TaskAssign',    
		'foreignKey' => 'task_id',    
		'conditions' => array(),
		'fields' => array(),
		'order' => array(),
		))));	
		
		
		$this->TaskAssign->bindModel(
		array('belongsTo' => array('NappUser' => array(
			'className' => 'NappUser',			 
			'foreignKey' => 'emp_id',				
			'conditions' => array(),
			'fields' => array('name','lname','email'),
			'order' => array(),
		))));
		
		$this->Task->bindModel(
		array('hasMany' => array('TaskComment' => array(
		'className' => 'TaskComment',    
		'foreignKey' => 'task_ids',    
		'conditions' => array(),
		'fields' => array(),
		'order' => array('TaskComment.id'=>'DESC'),
		))));	
		
		$this->TaskComment->bindModel(
		array('belongsTo' => array('NappUser' => array(
			'className' => 'NappUser',			 
			'foreignKey' => 'emp_id',				
			'conditions' => array(),
			'fields' => array('name','lname'),
			'order' => array(),
		))));
		$this->TaskComment->bindModel(
		array('belongsTo' => array('User' => array(
			'className' => 'User',			 
			'foreignKey' => 'admin_id',				
			'conditions' => array(),
			'fields' => array('name'),
			'order' => array(),
		))));
		
		
		
		$this->Task->bindModel(
		array('hasMany' => array('TaskDocument' => array(
			'className' => 'TaskDocument',			 
			'foreignKey' => 'task_id',				
			'conditions' => array(),
			'fields' => '',
			'order' => array('TaskDocument.id'=>'DESC'),
		))));
		$this->TaskDocument->bindModel(
		array('belongsTo' => array('NappUser' => array(
			'className' => 'NappUser',			 
			'foreignKey' => 'employe_id',				
			'conditions' => array(),
			'fields' => array('name','lname','email'),
			'order' => array(),
		))));
		$this->TaskDocument->bindModel(
		array('belongsTo' => array('User' => array(
			'className' => 'User',			 
			'foreignKey' => 'admin_id',				
			'conditions' => array(),
			'fields' => array('name','email'),
			'order' => array(),
		))));
		$this->Task->recursive = 2;	
		$TaskAssignArr = $this->Task->find('first',array('conditions'=>array('Task.task_id'=>$task_id)));
		$this->set('TaskAssign',$TaskAssignArr);
		if(!empty($this->request->data)){
			
			if(!empty($_FILES['documents']['name'])){
				$name = $_FILES['documents']['name'];
				$tempname = $_FILES['documents']['tmp_name'];
				$path  = WWW_ROOT.'document/'.$task_id;
				$ext = pathinfo($name, PATHINFO_EXTENSION);
				$filename =  time().'_'.$name;										
				move_uploaded_file($tempname,$path.'/'.$filename);
				$insert['TaskComment']['documents'] = $filename;
			}	
			$insert['TaskComment']['comment'] = $this->request->data['comment'];
			$insert['TaskComment']['task_ids'] =$TaskAssignArr['Task']['id'];
			$insert['TaskComment']['admin_id'] =$user_id;
			$insert['TaskComment']['type'] =1; // employee
			$insert['TaskComment']['created'] = date('Y-m-d H:i:s');
			//$this->TaskComment->save($insert);
		
			if($TaskAssignArr['Task']['is_notify'] == 1){
					
					
				if(!empty($TaskAssignArr['TaskAssign'])){
				
					foreach($TaskAssignArr['TaskAssign'] as $taskdoc){
						$receivername = $taskdoc['NappUser']['name'].' '.$taskdoc['NappUser']['lname'];
						$email = $taskdoc['NappUser']['email'];
						
						$subject = SITENAME.":- ".ucfirst($sendername)." comment on ".$task_id;
						$url = SITEURL.'tasks/comment/'.$task_id;
						
						$template_name = 'sendnotificaion';					
						$variables = array('task_id'=>$task_id,'name'=>$sendername,'receivername'=>$receivername,'url'=>$url,'comment'=>$this->request->data['comment']);	
						
						try{
							$this->sendemail($email,$subject,$template_name,$variables);
						}catch (Exception $e){
							
						}
					}	
				}	
			}	
			
			$this->Session->setFlash('Comment posted successfully','default',array('class' => 'alert alert-success'));
			$this->redirect('comment/'.$task_id);	
		}	
		$this->set('task_id',$task_id);		
	}
	
	function admin_commentdownload($id){
		
		$this->autoRender = false ;
		$this->checkAdminSession(); 
		$id = base64_decode(base64_decode($id));
		
		$this->TaskComment->bindModel(
		array('belongsTo' => array('Task' => array(
			'className' => 'Task',			 
			'foreignKey' => 'task_ids',				
			'conditions' => array(),
			'fields' => array(),
			'order' => array(),
		))));
		
		
		$TaskDocument = $this->TaskComment->find('first',array('conditions'=>array('TaskComment.id'=>$id)));
		// echo '<pre>';
		// print_r($TaskDocument);
		// die();	
	
		$paths = WWW_ROOT.'document/'.$TaskDocument['Task']['task_id'].'/';
		$path =  $paths.$TaskDocument['TaskComment']['documents']; 
		if (file_exists($path)) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename='.basename($path));
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($path));
			ob_clean();
			flush();
			readfile($path);
			exit;
		}else{

		}	
		
	}
	

	/**
	* Author:Ranjit
	* Discription:Edit The Category
	* @throws NotFoundException
	* @param string $id
	* @return void
 */
	public function admin_edit($id = null) {
		$this->layout='admin_layout';
		$this->set('title_for_layout',SITENAME.' Edit Task');
		$this->checkAdminSession(); 
		if (!$this->Task->exists($id)) {
			throw new NotFoundException(__('Invalid sevice task'));
		}
		if ($this->request->is(array('post', 'put'))) {
			
			$this->request->data['Task']['id'] = $id;
			if(!empty($this->request->data['Task']['assigned_date'])){
				$this->request->data['Task']['assigned_date'] = date('Y-m-d',strtotime($this->request->data['Task']['assigned_date']));
			}	
			if(!empty($this->request->data['Task']['task_completion_date'])){
				$this->request->data['Task']['task_completion_date'] = date('Y-m-d',strtotime($this->request->data['Task']['task_completion_date']));
			}	
			
			if ($this->Task->save($this->request->data)) {
				$this->Session->setFlash('The task has been updated','default',array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('The task could not be saved. Please, try again.','default',array('class' => 'alert alert-danger'));
			}
		} else {
			$options = array('conditions' => array('Task.' . $this->Task->primaryKey => $id));
			$task = $this->Task->find('first', $options);			
			$this->request->data = $task;			
			$this->set('task',$task);
			// $user_id=$this->Session->read('Customer.id');			
			// if($user_id != $tasks['Task']['emp_id']){
				// $this->Session->setFlash('Sorry, you have no permission to edit task.','default',array('class' => 'alert alert-success'));
				// return $this->redirect(array('action' => 'index'));
			// }				
		}
	}

	/**
 	* Author:Ranjit
	* Discription:Delete The Category
	* @throws NotFoundException
	* @param string $id
	* @return void
	*/
	public function admin_delete($id = null) {
		$this->autoRender  = false;
		$this->set('title_for_layout',SITENAME.' Task Delete');
		$this->checkAdminSession(); 
		$this->Task->id = $id;
		if (!$this->Task->exists()) {
			throw new NotFoundException(__('Invalid Task'));
		}
		//$this->request->allowMethod('post', 'delete');
		if ($this->Task->delete()) {
			$this->Session->setFlash('The task has been deleted.','default',array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash('The task could not be deleted.Please, try again.','default',array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
	
	public function admin_assign($task_id=null) {
		
		$this->layout='admin_layout';
		$this->set('title_for_layout',SITENAME.' Assign Task');
		$this->checkAdminSession(); 
		if ($this->request->is('post')) {
			
			
			isset($this->request->data['is_reminder'])? $is_reminder = $this->request->data['is_reminder'] : $is_reminder =0;
			isset($this->request->data['is_email'])? $is_email = $this->request->data['is_email'] : $is_email =0;
			isset($this->request->data['is_sms'])? $is_sms = $this->request->data['is_sms'] : $is_sms =0;
			isset($this->request->data['send_to'])? $send_to = $this->request->data['send_to'] : $send_to =0;
			isset($this->request->data['is_both'])? $is_both = $this->request->data['is_both'] : $is_both =0;
			isset($this->request->data['period'])? $period = $this->request->data['period'] : $period = '';
				
			$insertdata['Task']['is_reminder'] = $is_reminder;
			if($is_reminder == 1){
				$insertdata['Task']['is_email'] = $is_email;
				$insertdata['Task']['is_sms'] = $is_sms;
				$insertdata['Task']['is_both'] = $is_both;
				$insertdata['Task']['send_to'] = $send_to;
				$insertdata['Task']['period'] = $period;
			}else{
				$insertdata['Task']['is_email'] = 0;
				$insertdata['Task']['is_sms'] = 0;
				$insertdata['Task']['is_both'] = 0;
				$insertdata['Task']['send_to'] = 0;
				$insertdata['Task']['period'] = '';
			}		
			
			$insertdata['Task']['id'] = $task_id;
			$insertdata['Task']['task_status'] = 1;
			$insertdata['Task']['assigned_date'] = date('Y-m-d',strtotime($this->request->data['Task']['assigned_date']));
			$insertdata['Task']['task_completion_date'] = date('Y-m-d',strtotime($this->request->data['Task']['task_completion_date']));
			$TaskAssignArrnew = $this->Task->find('first',array('conditions'=>array('Task.id'=>$task_id)));
			if($TaskAssignArrnew['Task']['assigndatetime'] == '0000-00-00 00:00:00'){
				$insertdata['Task']['assigndatetime'] = date('Y-m-d H:i:s');
			}			
			if ($this->Task->save($insertdata)) {
				
				$Tasks = $this->Task->find('first',array('conditions'=>array('Task.id'=>$task_id),'fields'=>array('Task.task_type','Task.title','Task.task_id')));					
				$task_type = $Tasks['Task']['task_type'];
				$task_task_id = $Tasks['Task']['task_id'];
				$task_title = $Tasks['Task']['title'];
								
				if(!empty($this->request->data['employe_id'])){
					
					$this->TaskAssign->query('delete from task_assigns where task_id='.$task_id.'');
					// echo 'delete from task_assigns where task_id='.$task_id.'';
					// die();
					foreach($this->request->data['employe_id'] as $employe_ids){
						
						
						isset($this->request->data['is_send']) ? $is_send =  $this->request->data['is_send'] : $is_send = 0;
						if($is_send == 1){
						$userArr = $this->NappUser->find('first',array('conditions'=>array('NappUser.id'=>$employe_ids),'fields'=>array('NappUser.id','NappUser.name','NappUser.lname','NappUser.email')));
							if(!empty($userArr)){
								// sender name
								$sendername = $this->Session->read('User.name');					
								$receivername = $userArr['NappUser']['name'].' '.$userArr['NappUser']['lname'];							
								$to = $userArr['NappUser']['email'];								
								//$to = 'web@xoroglobal.com';								
								$subject= SITENAME." :: New task assigned by ".$sendername;				
								$template_name = 'assign_task';		
								$url = SITEURL.'tasks/autologin/'.base64_encode($userArr['NappUser']['id']).'/task/'.$task_type;	
								
								$variables = array('sendername'=>$sendername,'receivername'=>$receivername,'task_type'=>$task_type,'task_task_id'=>$task_task_id,'task_title'=>$task_title,'user_id'=>$userArr['NappUser']['id'],'url'=>$url);	
							
								try{
									$this->sendemail($to,$subject,$template_name,$variables);
								}catch (Exception $e){
									
								}								
							}
						}
						
						$task_assigns['TaskAssign']['id'] = '';
						$task_assigns['TaskAssign']['emp_id'] = $employe_ids;
						$task_assigns['TaskAssign']['task_id'] = $task_id;
						$this->TaskAssign->save($task_assigns);
					}	
				}	
				$this->Session->setFlash('Task assign successfully','default',array('class' => 'alert alert-success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('Not assigned successfully. Please, try again.','default',array('class' => 'alert alert-danger'));
			}
		}
		$this->Task->bindModel(
		array('hasMany' => array('TaskAssign' => array(
		'className' => 'TaskAssign',    
		'foreignKey' => 'task_id',    
		'conditions' => array(),
		'fields' => array(),
		'order' => array(),
		))));		
		$TaskAssignArr = $this->Task->find('first',array('conditions'=>array('Task.id'=>$task_id)));
		$this->set('TaskAssign',$TaskAssignArr);
		
		$empids = $this->UserPermission->find('list',array('conditions'=>array('UserPermission.permssion_id'=>array(6,7)),'fields'=>array('UserPermission.user_id')));
			
		$employeArr = $this->NappUser->find('all',array('conditions'=>array('NappUser.is_staff_id'=>1,'NappUser.id'=>$empids)));		
		$this->set('employeArr',$employeArr);
	
	}
	
	function adminautologin($user_id=null,$type=null,$param=null ){
		$this->autoRender = false;
		
		if(!empty($user_id)){
			
			$admin_arr = $this->User->find('first',array('conditions'=>array('User.id'=>base64_decode($user_id))));						
			if(!empty($admin_arr)){	
				$this->Session->write('User', $admin_arr['User']);
					$this->Session->write('is_admin', 1);
				if(isset($type) && ($type == 'task')){
					
					if(isset($param) && ($param == 'product')){
						$this->Session->write('durolab_type','product');
						$this->redirect('/admin/tasks');
					}else if(isset($param) && ($param == 'technical')){
						$this->Session->write('durolab_type','technical');
						$this->redirect('/admin/tasks');
					}	
				}else{
					$this->redirect('/admin/users/dashboard');
				}	
			}else{
				$this->Session->setFlash('Sorry, Sorry you have no access.','default',array('class' => 'alert alert-danger'));
				$this->redirect('/admin');
			}	
		}else{
			$this->Session->setFlash('Sorry, Sorry you have no access.','default',array('class' => 'alert alert-danger'));
			$this->redirect('/admin');
		}			
	}	
	
	function autologin($user_id=null,$type=null,$param=null ){
	
		$this->autoRender = false;
		
		if(!empty($user_id)){
			$employeArr = $this->NappUser->find('first',array('conditions'=>array('NappUser.id'=>base64_decode($user_id))));
			if(!empty($employeArr)){
				
				if($employeArr['NappUser']['is_active'] == 1){
					if(isset($type) && ($type == 'task')){
						if(isset($param) && ($param == 'product')){
							$this->Session->write('durolab_type','product');
							$this->redirect('index');
						}else if(isset($param) && ($param == 'technical')){
							$this->Session->write('durolab_type','technical');
							$this->redirect('index');
						}							
					}else{
						$this->redirect('/staffs/dashboard');
					}						
				}else{
					$this->Session->setFlash('Sorry, your account is not active. Please contact to admin.','default',array('class' => 'alert alert-danger'));
					$this->redirect('/login');
				}	
			}else{
				$this->Session->setFlash('Sorry, Sorry you have no access.','default',array('class' => 'alert alert-danger'));
				$this->redirect('/login');	
			}		
		}else{
			$this->Session->setFlash('Sorry, Sorry you have no access.','default',array('class' => 'alert alert-danger'));
			$this->redirect('/login');
		}	
		
		
	}	
	
}
?>