<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 * Front / customer Users controller (non-admin actions).
 */
class UsersController extends AppController
{
    public function __get(string $name): mixed
    {
        if (preg_match('/^[A-Z][A-Za-z0-9_]*$/', $name) === 1) {
            $adapter = $this->legacyModel($name);
            if ($adapter !== null) {
                return $adapter;
            }
        }

        return parent::__get($name);
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->callConstants();
    }
	function register(){
		
		$this->viewBuilder()->setLayout('admin_login');
		$this->set('title_for_layout',SITENAME.' Customer Register Page');

		if(!empty($this->requestData())){
			$data = $this->requestData();
			$email = $data['NappUser']['email'];
			$cus_arr = $this->NappUser->find('first',array('conditions'=>array('email'=>$email)));
			if(empty($cus_arr)){
				
				$empid = 'DT-'.rand(000000,999999);
				
				$password = $data['NappUser']['password'];
				$autopassword =  md5($data['NappUser']['password']);
				$data['NappUser']['insert_date'] = date('Y-m-d H;i:s');
				$data['NappUser']['emp_id'] =$empid;
				$data['NappUser']['password'] =$autopassword;
				$data['NappUser']['address_5'] = $password;
				$this->setRequestData($data);
				$this->NappUser->save($data);
				
								
				$to = $data['NappUser']['email'];				
				$subject = SITENAME." :: Welcome To kggc.com.au Activate Your Account.";				
				$template_name = 'activation';
				$variables = array('password'=>base64_encode($to) ,'name'=>$data['NappUser']['name'],'email'=>$data['NappUser']['email'],'type'=>'activation');		
				try{
					$this->sendemail($to , $subject ,$template_name,$variables);
					$this->Session->setFlash('Register successfully. Please check your mail and activate your account.','default',array('class' => 'alert alert-success'));
					$this->redirect('/login');
				}catch (\Exception $e){
				
					$this->Session->setFlash('Register successfully.','default',array('class' => 'alert alert-success'));
					$this->redirect('/login');
				}				
			}else{
				$this->Session->setFlash('Email id already exist.','default',array('class' => 'alert alert-danger'));
					
			}
			
		}	
	}

	function activateaccount($code=null){

		$this->autoRender = false;
		if(!empty($code)){
			$code = base64_decode($code);
			$cus_arr = $this->NappUser->find('first',array('conditions'=>array('email'=>$code)));
		
			if(!empty($cus_arr)){		
				if($cus_arr['NappUser']['is_active'] == 0){	
					$password = $cus_arr['NappUser']['address_5'];					
					$save = $this->requestData();
					$save['NappUser']['id'] = $cus_arr['NappUser']['id'];
					$save['NappUser']['is_active'] = 1;
					$save['NappUser']['address_5'] = '';
					$this->setRequestData($save);
					$this->NappUser->save($save);
					
					
					$to = $cus_arr['NappUser']['email'];				
					$subject = SITENAME." :: Your account is activated.";				
					$template_name = 'message';
					$variables = array('password'=>$password ,'name'=>$cus_arr['NappUser']['name'],'email'=>$cus_arr['NappUser']['email'],'type'=>'signup');
					try{
						$this->sendemail($to , $subject ,$template_name,$variables);								
					}catch (\Exception $e){				
						$this->Session->setFlash('Register successfully.','default',array('class' => 'alert alert-success'));
						$this->redirect('/login');
					}
					
					if($cus_arr['NappUser']['is_staff_id'] == 0){	
						$this->Session->setFlash('You account has been activated successfully.','default',array('class' => 'alert alert-success'));	
						$this->Session->write('Customer', $cus_arr['NappUser']);
						$this->Session->write('is_customer', 1);			
						$this->redirect(['action' => 'dashboard']);
					}else{
						$semployeeid =  base64_encode(base64_encode(base64_encode($cus_arr['NappUser']['email'])));
						$staff_to = 'kal@durotechindustries.com.au';				
						#$staff_to = 'web@xoroglobal.com';				
						$staff_subject = SITENAME." :: New Employee Registered.";				
						$staff_template_name = 'staffmessage';
						$staff_variables = array('emp_id'=>$semployeeid,'name'=>$cus_arr['NappUser']['name'].' '.$cus_arr['NappUser']['lname'],'email'=>$cus_arr['NappUser']['email'],'type'=>'register');
						try{
							$this->sendemail($staff_to , $staff_subject ,$staff_template_name,$staff_variables);
						}catch (\Exception $e){	
						
						}	
						$this->Session->setFlash('You account has been activated successfully.','default',array('class' => 'alert alert-success'));		
						$this->Session->write('Customer', $cus_arr['NappUser']);
						$this->Session->write('is_staff', 1);			
						$this->redirect('/staffs/dashboard');
					}	
					
					
				}else{
					$this->Session->setFlash('your account is already activated.','default',array('class' => 'alert alert-danger'));
					 $this->redirect('/login');		
				} 
			}else{
				$this->Session->setFlash('wrong activation code.','default',array('class' => 'alert alert-danger'));
				$this->redirect('/login');		
			}		
		}else{
			$this->Session->setFlash('activateion code is wrong.','default',array('class' => 'alert alert-danger'));
			$this->redirect('/login');
		}	
	}	
	
	public function autologin($email=null) {
		
		$this->viewBuilder()->setLayout('admin_login');
		$this->set('title_for_layout',SITENAME.' Customer Login Page');		
		$is_customer=$this->Session->read('is_customer');
		$is_staff=$this->Session->read('is_staff');
		
		if(!empty($is_customer)){
			$this->redirect(['action' => 'dashboard']);
		}else if(!empty($is_staff)){
			$this->redirect('/staffs/dashboard');
		}		
		if(!empty($email)){
			
			$cus_arr = $this->NappUser->find('first',array('conditions'=>array('email'=>$email)));
			// echo '<pre>';
			// print_r($cus_arr);
			// die();			
			if(!empty($cus_arr)){	
				if($cus_arr['NappUser']['is_active'] == 0){
					$this->Session->setFlash('You account is not active','default',array('class' => 'alert alert-danger'));
				}else{
					
				$insert['LoginHistory']['user_id'] = $cus_arr['NappUser']['id'];
				$insert['LoginHistory']['role'] = 'Customer';
				$insert['LoginHistory']['logintime'] = date('Y-m-d H:i:s');
				$this->LoginHistory->save($insert);
				
				if($cus_arr['NappUser']['is_staff_id'] == 0){					
					$this->Session->write('Customer', $cus_arr['NappUser']);
					$this->Session->write('is_customer', 1);			
					$this->redirect(['action' => 'dashboard']);
				}else{
					$this->Session->write('Customer', $cus_arr['NappUser']);
					$this->Session->write('is_staff', 1);			
					$this->redirect('/staffs/dashboard');
				}	
				
				}
			}else{
				//$this->Session->setFlash(__('Wrong username/password', true));
				$this->Session->setFlash('Wrong username/password','default',array('class' => 'alert alert-danger'));
			}
		}	
	}
	
	public function login() {
		
		$this->viewBuilder()->setLayout('admin_login');
		$this->set('title_for_layout',SITENAME.' Customer Login Page');		
		$is_customer=$this->Session->read('is_customer');
		$is_staff=$this->Session->read('is_staff');
		
		if(!empty($is_customer)){
			$this->redirect(['action' => 'dashboard']);
		}else if(!empty($is_staff)){
			$this->redirect('/staffs/dashboard');
		}		
		if(!empty($this->requestData())){
			$password = md5($this->requestData()['NappUser']['password']);	
			$cus_arr = $this->NappUser->find('first',array('conditions'=>array('email'=>$this->requestData()['NappUser']['email'],'password'=>$password)));
			// echo '<pre>';
			// print_r($cus_arr);
			// die();			
			if(!empty($cus_arr)){	
			
			
				if($cus_arr['NappUser']['is_active'] == 0){
					$this->Session->setFlash('You account is not active','default',array('class' => 'alert alert-danger'));
				}else{
					
					
					
				if($cus_arr['NappUser']['is_staff_id'] == 0){
					$insert['LoginHistory']['user_id'] = $cus_arr['NappUser']['id'];
					$insert['LoginHistory']['role'] = 'Customer';
					$insert['LoginHistory']['logintime'] = date('Y-m-d H:i:s');
					$this->LoginHistory->save($insert);	
					$this->Session->write('Customer', $cus_arr['NappUser']);
					$this->Session->write('is_customer', 1);			
					$this->redirect(['action' => 'dashboard']);
				}else{
					$insert['LoginHistory']['user_id'] = $cus_arr['NappUser']['id'];
					$insert['LoginHistory']['role'] = 'Staff';
					$insert['LoginHistory']['logintime'] = date('Y-m-d H:i:s');
					$this->LoginHistory->save($insert);
					$this->Session->write('Customer', $cus_arr['NappUser']);
					$this->Session->write('is_staff', 1);			
					$this->redirect('/staffs/dashboard');
				}	
				
				}
			}else{
				//$this->Session->setFlash(__('Wrong username/password', true));
				$this->Session->setFlash('Wrong username/password','default',array('class' => 'alert alert-danger'));
			}
		}	
	}
	
	/***
	/*Author  :Ranjit,
	/*Comment : User Logout page
****/	
	function logout()
	{
		
		$userId = $this->Session->read('Customer.id');
		$LoginHistoryArr = $this->LoginHistory->find('first',array('conditions'=>array('LoginHistory.logouttime'=>'0000-00-00 00:00:00','LoginHistory.user_id'=>$userId),'order'=>array('LoginHistory.id'=>'DESC')));
		if(!empty($LoginHistoryArr)){			
			$insert['LoginHistory']['id'] = $LoginHistoryArr['LoginHistory']['id'];
			$insert['LoginHistory']['user_id'] = $userId;
			$insert['LoginHistory']['logouttime'] = date('Y-m-d H:i:s');
			$this->LoginHistory->save($insert);
		}
		
		$this->Session->destroy();
		$this->redirect('/login');

	}
	
	public function dashboard() {
		$this->viewBuilder()->setLayout('inner_layout');
		$this->set('title_for_layout',SITENAME.' Customer Dashboard Page');
		$this->checkCustomerSession(); 
		
	}
	
	public function questionlist($type=null){
		
		$is_staff_id = $this->Session->read('Customer.is_staff_id');
		if($is_staff_id == 1){
			$this->viewBuilder()->setLayout('staff_inner_layout');	
		}else{
			$this->viewBuilder()->setLayout('inner_layout');
		}	
		$this->checkcommonSession();
		
		$user_id=$this->Session->read('Customer.id');
		$cpdquestionaceess=$this->Session->read('cpdquestionaceess');		
		if(!empty($this->requestData()['otp'])){
			$uotp = $this->requestData()['otp'];
			$userArr = $this->NappUser->find('first',array('conditions'=>array('NappUser.otp'=>$uotp,'NappUser.id'=>$user_id)));
			
			if(!empty($userArr)){
				$update['NappUser']['id'] = $user_id;
				$update['NappUser']['otp'] = '';
				$this->NappUser->save($update);	
				$this->Session->write('cpdquestionaceess',2);
				$this->Session->delete('opt');
				$this->Session->setFlash('Thank for verifying your account.','default',array('class' => 'alert alert-success'));
				$this->redirect('questionlist');
				
			}else{
				$this->Session->setFlash('Wrong otp password. Please try again.','default',array('class' => 'alert alert-danger'));
				$this->redirect('questionlist');
			}	
		}else if(!empty($cpdquestionaceess)){
			$this->set('is_access',$cpdquestionaceess);
		}else if(empty($cpdquestionaceess) && !empty($type)){	
		
			$rand = rand(000000,999999);			
			$update['NappUser']['id'] = $user_id;
			$update['NappUser']['otp'] = $rand;
			$this->NappUser->save($update);	
			
			$userArr = $this->NappUser->find('first',array('conditions'=>array('NappUser.id'=>$user_id)));				
			$name = $userArr['NappUser']['name'].' '.$userArr['NappUser']['lname'];			
			$to = $userArr['NappUser']['email'];								
			$subject= SITENAME." :: Secure One-Time-Password (OTP) for  Architect CPD Questionnaire. OTP is ".$rand;				
			$template_name = 'durolabotp';										
			$variables = array('email'=>$to,'name'=>$name,'otp'=>$rand);			
			try{
				$this->sendemail($to,$subject,$template_name,$variables);
			}catch (\Exception $e){
				
			}
			if(!empty($userArr['NappUser']['mobile_number']) && ($userArr['NappUser']['is_active_otp'] == 1)){
				if($userArr['NappUser']['country']  != 'india'){
					$phones = str_replace(' ','',$userArr['NappUser']['mobile_number']);
					$phone = ltrim($phones,'+');
					$to = '+'.$phone;
					$sid = TSID;	
					$token = TOKEN;							
					$from = FROM_NUMBER;	
					$body = "Hi, Secure OTP for  Architect CPD Questionnaire Access. OTP is ".$rand;						
					$this->Twilio->AccountSid = TSID;   
					$this->Twilio->AccountToken = TOKEN;						
					$response = $this->Twilio->sendsms($to,$from,$body);
				}
			}
			$this->Session->write('cpdquestionaceess',1);
			$this->redirect('questionlist');			
		}else{
			$this->set('is_access',0);
		}
		
		
		//$this->viewBuilder()->setLayout('inner_layout');
		$this->set('title_for_layout',SITENAME.' Customer questionlist');	
		$customer_id=$this->Session->read('Customer.id');
		$user = $this->NappUser->find('first',array('conditions'=>array('NappUser.id'=>$customer_id)));
		$this->set('user',$user);
		
		$this->Question->bindModel(
			array('hasMany' => array('QuestionOption' => array(
			'className' => 'QuestionOption',    
			'foreignKey' => 'question_id',    
			'conditions' => array(),
			'fields' => array(),
			'order' => array(),
			))));	
	
		
		$question = $this->Question->find('all');
		$this->set('question',$question);
		
		$user_answers = $this->UserAnswer->find('all',array('conditions'=>array('UserAnswer.user_id'=>$customer_id)));
		$this->set('question',$question);
		
		if(!empty($this->requestData())){
					
			$name = $this->requestData()['name'];
			$lname = $this->requestData()['lname'];
			$email = $this->requestData()['email'];
			$phone = $this->requestData()['mobile'];
			$landlineno = $this->requestData()['landlineno'];
			$company = $this->requestData()['company'];
			$company_address = $this->requestData()['company_address'];
			
			$QuestionSubmit['QuestionSubmit']['user_id'] = $customer_id;
			$QuestionSubmit['QuestionSubmit']['name'] = $name;
			$QuestionSubmit['QuestionSubmit']['lname'] = $lname;
			$QuestionSubmit['QuestionSubmit']['phone'] = $phone;
			$QuestionSubmit['QuestionSubmit']['email'] = $email;
			$QuestionSubmit['QuestionSubmit']['landlineno'] = $landlineno;
			$QuestionSubmit['QuestionSubmit']['company'] = $company;
			$QuestionSubmit['QuestionSubmit']['company_address'] = $company_address;
			$QuestionSubmit['QuestionSubmit']['created'] = date('Y-m-d H:i:s');
			$this->QuestionSubmit->save($QuestionSubmit);
			
			$url = SITEURL.'finalsubmit.php?user_id='.$customer_id;			
			$res = file_get_contents($url);
			$custname  = base64_encode(base64_encode($name));
			$this->redirect('thanks/'.$custname);		
		}	
		
	}
	
	
	function welcomemailer(){
		
		$this->viewBuilder()->setLayout('staff_inner_layout');	
		$this->checkSatffSession();
		$user_id=$this->Session->read('Customer.id');
		if(!empty($this->requestData())){
			
			$name = $this->requestData()['name'];
			$lname = $this->requestData()['lname'];
			$email = $this->requestData()['email'];
			$phone = $this->requestData()['phone'];
			
			$QuestionSubmit['CpdMailer']['user_id'] = $user_id;
			$QuestionSubmit['CpdMailer']['name'] = $name;
			$QuestionSubmit['CpdMailer']['lname'] = $lname;
			$QuestionSubmit['CpdMailer']['phone'] = $phone;
			$QuestionSubmit['CpdMailer']['email'] = $email;
			
			$QuestionSubmit['CpdMailer']['created'] = date('Y-m-d H:i:s');
			$this->CpdMailer->save($QuestionSubmit);
			$QuestionSubmitId = $this->CpdMailer->id; 			
			$url = SITEURL.'welcomecertifcatie.php?user_id='.$QuestionSubmitId;		
			$res = file_get_contents($url);
			$custname  = base64_encode(base64_encode($name));
			$this->Session->setFlash('CPD Presentation Mailer sent successfully ','default',array('class' => 'alert alert-success'));			
			$this->redirect('welcomemailer');		
		}
		
		$this->paginate = array('conditions'=>array('CpdMailer.user_id'=>$user_id),'page' => 1, 'limit' => 10,'order'=>array('CpdMailer.id'=>'desc'));		
		$this->CpdMailer->recursive = 2;
		$CpdMailerArr = $this->Paginator->paginate('CpdMailer');					 
		$this->set('CpdMailer',$CpdMailerArr);	
		
	}	
	
	function cert(){
		
		$this->viewBuilder()->setLayout('staff_inner_layout');	
		$this->checkSatffSession();
		
		if(!empty($this->requestData())){
								
			$name = $this->requestData()['name'];
			$lname = $this->requestData()['lname'];
			$email = $this->requestData()['email'];
			$phone = $this->requestData()['phone'];
			$landlineno = $this->requestData()['landlineno'];
			$company = $this->requestData()['company'];
			$company_address = $this->requestData()['company_address'];
			
			$QuestionSubmit['QuestionSubmit']['user_id'] = 0;
			$QuestionSubmit['QuestionSubmit']['name'] = $name;
			$QuestionSubmit['QuestionSubmit']['lname'] = $lname;
			$QuestionSubmit['QuestionSubmit']['phone'] = $phone;
			$QuestionSubmit['QuestionSubmit']['email'] = $email;
			$QuestionSubmit['QuestionSubmit']['landlineno'] = $landlineno;
			$QuestionSubmit['QuestionSubmit']['company'] = $company;
			$QuestionSubmit['QuestionSubmit']['company_address'] = $company_address;
			$QuestionSubmit['QuestionSubmit']['created'] = date('Y-m-d H:i:s');
			$this->QuestionSubmit->save($QuestionSubmit);
			$QuestionSubmitId = $this->QuestionSubmit->id; 
			
			$url = SITEURL.'finalcertifcatie.php?user_id='.$QuestionSubmitId;			
			$res = file_get_contents($url);
			$custname  = base64_encode(base64_encode($name));
			
			$this->Session->setFlash('Cpd certification sent successfully','default',array('class' => 'alert alert-success'));
			$this->redirect('cert');
		}
		
		$this->paginate = array('conditions'=>array(),'page' => 1, 'limit' => 10,'order'=>array('QuestionSubmit.id'=>'desc'));		
		$this->QuestionSubmit->recursive = 2;
		$QuestionSubmitArr = $this->Paginator->paginate('QuestionSubmit');					 
		$this->set('QuestionSubmitArr',$QuestionSubmitArr);	
		
	}	
	public function thanks($custname=null) {
		
		$this->set('title_for_layout',SITENAME.' Customer Dashboard Page');		
		$is_staff_id = $this->Session->read('Customer.is_staff_id');
		if($is_staff_id == 1){
			$this->viewBuilder()->setLayout('staff_inner_layout');	
		}else{
			$this->viewBuilder()->setLayout('inner_layout');
		}	
		$this->checkcommonSession();
		
		$this->set('custname',$custname);
		
	}
	
	
	function profile()
	{
		$this->viewBuilder()->setLayout('inner_layout');
		$this->set('title_for_layout',SITENAME.' Profile Page');
		$this->checkCustomerSession();
		$customer_id=$this->Session->read('Customer.id');
			
		$user = $this->NappUser->find('first',array('conditions'=>array('NappUser.id'=>$customer_id)));
		if(!empty($this->requestData())){			
			if(!empty($user)){
				$data = $this->requestData();
				$data['NappUser']['id']=$customer_id;
				$this->setRequestData($data);
				
				if ($this->NappUser->save($data)) {
				$this->Session->setFlash('Your profile has been updated','default',array('class' => 'alert alert-success'));
				$this->redirect('profile');
				}else{
				$this->Session->setFlash('Your profile has not updated','default',array('class' => 'alert alert-danger'));
					
				}
			}
		}
	
		$this->setRequestData(is_array($user) ? $user : []);
	}

	function reminder(){
		
		$this->autoRender = false;
		$startdate = date('Y-m-d',strtotime('-1 days'));
		$enddate = date('Y-m-d');
		
		$userarray = array('Mukesh','Amandeep');
		if(!empty($userarray)){
			foreach($userarray as $user){
				
				$summary = $this->Salemeet->find('count',array('conditions'=>array('addedby'=>$user,'DATE(Salemeet.created) >='=>$startdate,'DATE(Salemeet.created) <'=>$enddate)));
				if($summary  == 0){
					if($user == 'Amandeep'){
						$to = 'amandeep@kodexglobalcc.com';	
							
					//$to = 'web@xoroglobal.com';						
					$subject= SITENAME.":- Yesterday's Sale-meet Reminder";				
					$template_name = 'reminder';										
					$variables = array('user'=>$user);	
					$this->sendemail($to,$subject,$template_name,$variables);
					}
				}
			}
		}		
	}	
	public function salemeet($day=null) {
		$this->autoRender = false;
		if($day == 'today'){
			$startdate = date('Y-m-d',strtotime('-1 days'));
			$enddate = date('Y-m-d');
		}else{
			$startdate = date('Y-m-d',strtotime('-7 days'));
			$enddate = date('Y-m-d');
		}	
		
		$summary= $this->Salemeet->find('all',array('conditions'=>array('DATE(Salemeet.created) >='=>$startdate,'DATE(Salemeet.created) <'=>$enddate)));
			
		
		$to = 'rsb@kodexglobalcc.com';								
		$subject= SITENAME.":- Sale Meet Report";				
		$template_name = 'salereport';										
		$variables = array('startdate'=>$startdate,'enddate'=>$enddate,'name'=>'','summary'=>$summary);	
		if($day != 'today'){
			try{
				$this->sendemail($to,$subject,$template_name,$variables);
			}catch (\Exception $e){
				//echo '<pre>';print_r($e);die;
			}
		}
		$tos = 'web@xoroglobal.com';	
		try{
			$this->sendemail($tos,$subject,$template_name,$variables);
		}catch (\Exception $e){
			//echo '<pre>';print_r($e);die;
		}
		echo 'success';
	}	
	
	function orderreminder(){
		$this->autoRender = false;
		
		$hour = date('H');	
		if(($hour > 8) && ($hour < 22)){
			
			$this->DuroOrder->bindModel(
			array('belongsTo' => array('NappUser' => array(
				'className' => 'NappUser',			 
				'foreignKey' => 'user_id',				
				'conditions' => array(),
				'fields' => '',
				'order' => array(),
			))));
			$DuroOrderArrs = $this->DuroOrder->find('all',array('conditions'=>array('DuroOrder.status'=>0)));	
			
			if(!empty($DuroOrderArrs)){
				foreach($DuroOrderArrs as $DuroOrderArr){
					$customer_order_no = $DuroOrderArr['DuroOrder']['customer_order_no'];
					$date_of_order = $DuroOrderArr['DuroOrder']['date_of_order'];
					$order_id = $DuroOrderArr['DuroOrder']['id'];
					
					$to = 'rsb@kodexglobalcc.com';						
					$subject = "Reminder: New Order #".$customer_order_no;				
					$template_name = 'reminderorder';
					$name =  $DuroOrderArr['NappUser']['name'].' '.$DuroOrderArr['NappUser']['lname'];				
					$variables = array('name'=>$name,'customer_order_no'=>$customer_order_no);		
					try{
						$this->sendemail($to,$subject,$template_name,$variables);				
					}catch (\Exception $e){
						
					}
					echo 'success';
				}
			}	 
		}	 
	}
	
	function rawmaterial_reminder(){
		
		$this->autoRender = false;
		
		$this->Purchase->bindModel(
			array('belongsTo' => array('NappUser' => array(
				'className' => 'NappUser',			 
				'foreignKey' => 'prepared_by',				
				'conditions' => array(),
				'fields' => array('name','lname','email'),
				'order' => array(),
			))));
		$PurchaseOrderArrs = $this->Purchase->find('all',array('conditions'=>array('Purchase.status'=>0,'ignore_id'=>0)));
		if(!empty($PurchaseOrderArrs)){
			
			foreach($PurchaseOrderArrs as $PurchaseOrders){
				$unique_id = $PurchaseOrders['Purchase']['unique_id'];
				$date = $PurchaseOrders['Purchase']['date'];
				$item_details = $PurchaseOrders['Purchase']['item_details'];
				$requisitioner_name = $PurchaseOrders['Purchase']['requisitioner_name'];
				
				$email = $PurchaseOrders['NappUser']['email'];

				$to = 'rsb@kodexglobalcc.com';	
				$adminurl = SITEURL.'admin/purchases/autologin';	
				$subject = "Reminder: New Raw Material Order #".$unique_id;				
				$template_name = 'rawmaterial_reminder';
				$name =  $PurchaseOrders['NappUser']['name'].' '.$PurchaseOrders['NappUser']['lname'];				
				$variables = array('url'=>$adminurl,'name'=>$name,'unique_id'=>$unique_id,'date'=>$date,'item_details'=>$item_details,'requisitioner_name'=>$requisitioner_name);		
				try{
					$this->sendemail($to,$subject,$template_name,$variables);				
				}catch (\Exception $e){
					
				}	
					
			}
		}

		$this->Purchase->bindModel(
			array('belongsTo' => array('NappUser' => array(
				'className' => 'NappUser',			 
				'foreignKey' => 'prepared_by',				
				'conditions' => array(),
				'fields' => array('name','lname','email'),
				'order' => array(),
			))));
		$PurchaseOrderArrss = $this->Purchase->find('all',array('conditions'=>array('Purchase.status'=>1,'ignore_id'=>0)));
		if(!empty($PurchaseOrderArrss)){
			
			foreach($PurchaseOrderArrss as $PurchaseOrders){
				$unique_id = $PurchaseOrders['Purchase']['unique_id'];
				$date = $PurchaseOrders['Purchase']['date'];
				$item_details = $PurchaseOrders['Purchase']['item_details'];
				$requisitioner_name = $PurchaseOrders['Purchase']['requisitioner_name'];
				
				$email = $PurchaseOrders['NappUser']['email'];

				$to = 'alan@xoroglobal.com';						
				$subject = "Reminder: Is Process order #".$unique_id;				
				$template_name = 'user_rawmaterial_reminder';
				$name =  $PurchaseOrders['NappUser']['name'].' '.$PurchaseOrders['NappUser']['lname'];				
				$variables = array('name'=>$name,'unique_id'=>$unique_id,'date'=>$date,'item_details'=>$item_details,'requisitioner_name'=>$requisitioner_name);		
				try{
					$this->sendemail($to,$subject,$template_name,$variables);				
				}catch (\Exception $e){
					
				}	
					
			}
		}
		$this->Purchase->bindModel(
			array('belongsTo' => array('NappUser' => array(
				'className' => 'NappUser',			 
				'foreignKey' => 'prepared_by',				
				'conditions' => array(),
				'fields' => array('name','lname','email'),
				'order' => array(),
			))));
		$PurchaseOrderArrss = $this->Purchase->find('all',array('conditions'=>array('Purchase.status'=>2,'ignore_id'=>0)));
		if(!empty($PurchaseOrderArrss)){
			
			foreach($PurchaseOrderArrss as $PurchaseOrders){
				$unique_id = $PurchaseOrders['Purchase']['unique_id'];
				$date = $PurchaseOrders['Purchase']['date'];
				$item_details = $PurchaseOrders['Purchase']['item_details'];
				$requisitioner_name = $PurchaseOrders['Purchase']['requisitioner_name'];
				
				//$email = $PurchaseOrders['NappUser']['email'];

				$to = 'alan@xoroglobal.com';						
				$subject = "Reminder: Did you received RM #".$unique_id.'?';				
				$template_name = 'user_rawmaterial_reminder';
				$name =  $PurchaseOrders['NappUser']['name'].' '.$PurchaseOrders['NappUser']['lname'];				
				$variables = array('name'=>$name,'unique_id'=>$unique_id,'date'=>$date,'item_details'=>$item_details,'requisitioner_name'=>$requisitioner_name);		
				try{
					$this->sendemail($to,$subject,$template_name,$variables);				
				}catch (\Exception $e){
					
				}	
					
			}
		}
		echo 'success';
	}
	

}
