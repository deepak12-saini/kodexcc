<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Event\EventInterface;

/**
 * Admin area Users controller (CakePHP 5 prefix: /admin/users/...).
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
	public function permission($id=null) {
		$this->viewBuilder()->setLayout('admin_layout');
		$this->set('title_for_layout',SITENAME.' Saff Permission');
		$this->checkAdminSession();
		
		if(!empty($this->requestData())){
			$this->UserPermission->query('delete from user_permissions where user_id='.$id.'');
			if(!empty($this->requestData()['permssion_id'])){
				
				foreach($this->requestData()['permssion_id'] as $permssion_id){
					$permssionId = explode('-',$permssion_id);
				
					$UserPermission['UserPermission']['id'] = '';
					$UserPermission['UserPermission']['user_id'] = $id;
					$UserPermission['UserPermission']['meta_val'] = $permssionId[1];
					$UserPermission['UserPermission']['permssion_id'] = $permssionId[0];
					$this->UserPermission->save($UserPermission);
				}	
			}	
			$this->Session->setFlash('updated successfully','default',array('class' => 'alert alert-success'));
			$this->redirect(['action' => 'staff']);	
		}			
		$permission = $this->Permission->find('all');
		$this->set('permission',$permission);	
	
		$upermission = $this->UserPermission->find('all',array('conditions'=>array('UserPermission.user_id'=>$id)));
		$this->set('upermission',$upermission);	
		
		$NappUser = $this->NappUser->find('first',array('conditions'=>array('NappUser.id'=>$id)));
		$this->set('NappUser',$NappUser);	
	}
	
	public function customerpermission($id=null) {
		$this->viewBuilder()->setLayout('admin_layout');
		$this->set('title_for_layout',SITENAME.' Customer Permission');
		$this->checkAdminSession();
		
		if(!empty($this->requestData())){
			
			$this->UserPermission->query('delete from user_permissions where user_id='.$id.'');
			if(!empty($this->requestData()['permssion_id'])){
				
				
				
				foreach($this->requestData()['permssion_id'] as $permssion_id){
					
					$permssionId = explode('-',$permssion_id);
				
					$UserPermission['UserPermission']['id'] = '';
					$UserPermission['UserPermission']['user_id'] = $id;
					$UserPermission['UserPermission']['meta_val'] = $permssionId[1];
					$UserPermission['UserPermission']['permssion_id'] = $permssionId[0];
					$this->UserPermission->save($UserPermission);
				}	
			}	
			$this->Session->setFlash('updated successfully','default',array('class' => 'alert alert-success'));
			$this->redirect(['action' => 'customer']);	
		}			
		$permission = $this->Permission->find('all',array('conditions'=>array('Permission.id'=>4)));
		$this->set('permission',$permission);	
	
		$upermission = $this->UserPermission->find('all',array('conditions'=>array('UserPermission.user_id'=>$id)));
		$this->set('upermission',$upermission);	
		
		$NappUser = $this->NappUser->find('first',array('conditions'=>array('NappUser.id'=>$id)));
		$this->set('NappUser',$NappUser);	
	}
	
	public function salemeet($all=null) {
		$this->viewBuilder()->setLayout('admin_layout');
		$this->set('title_for_layout',SITENAME.' Admin Sale Question');
		$this->checkAdminSession(); 
		if(!empty($all)){
			$this->Session->delete('search');
			$this->redirect(['action' => 'salemeet']);
		}
		$saletreps = '';
		$startdate = '';
		$enddate = '';
		
		if(!empty($this->requestData())){
			$saletreps = $this->requestData()['saletreps'];
			$startdate = $this->requestData()['startdate'];
			$enddate = $this->requestData()['enddate'];
			if(!empty($saletreps) && !empty($startdate) && !empty($enddate)){
				
				$sess['saletreps'] = $saletreps;
				$sess['startdate'] = $startdate;
				$sess['enddate'] = $enddate;
				$this->Session->delete('search');
				$this->Session->write('search',$sess);
				
				$Salemeets = $this->Salemeet->find('all',array('conditions'=>array('Salemeet.addedby LIKE'=>'%'.$saletreps.'%','DATE(Salemeet.created) >='=>$startdate,'DATE(Salemeet.created) <='=>$enddate),'order'=>'Salemeet.id desc'));
			}else if(!empty($startdate) && !empty($enddate)){				
				$sess['startdate'] = $startdate;
				$sess['enddate'] = $enddate;
				$this->Session->delete('search');
				$this->Session->write('search',$sess);
				
				$Salemeets = $this->Salemeet->find('all',array('conditions'=>array('DATE(Salemeet.created) >='=>$startdate,'DATE(Salemeet.created) <='=>$enddate),'order'=>'Salemeet.id desc'));
			}else if(!empty($saletreps)){
				$sess['saletreps'] = $saletreps;				
				$this->Session->delete('search');
				$this->Session->write('search',$sess);
				
				$Salemeets = $this->Salemeet->find('all',array('conditions'=>array('Salemeet.addedby LIKE'=>'%'.$saletreps.'%'),'order'=>'Salemeet.id desc'));
			}			
		}else{
			$Salemeets = $this->Salemeet->find('all',array('order'=>'Salemeet.id desc'));	
		}
		
		$this->set('Salemeets',$Salemeets);	
		
		$this->set('startdate',$startdate);	
		$this->set('enddate',$enddate);	
		$this->set('saletreps',$saletreps);	
	}	
	function export(){
		$this->checkAdminSession(); 
		$this->autoRender = false;
		
		$search = $this->Session->read('search');
		if(!empty($search)){
			$saletreps = $this->Session->read('search.saletreps');
			$startdate = $this->Session->read('search.startdate');
			$enddate = $this->Session->read('search.enddate');
			if(!empty($saletreps) && !empty($startdate) && !empty($enddate)){				
				$Salemeets = $this->Salemeet->find('all',array('conditions'=>array('Salemeet.addedby LIKE'=>'%'.$saletreps.'%','DATE(Salemeet.created) >='=>$startdate,'DATE(Salemeet.created) <='=>$enddate),'order'=>'Salemeet.id desc'));
			}else if(!empty($startdate) && !empty($enddate)){			
				$Salemeets = $this->Salemeet->find('all',array('conditions'=>array('DATE(Salemeet.created) >='=>$startdate,'DATE(Salemeet.created) <='=>$enddate),'order'=>'Salemeet.id desc'));
			}else if(!empty($saletreps)){
				$Salemeets = $this->Salemeet->find('all',array('conditions'=>array('Salemeet.addedby LIKE'=>'%'.$saletreps.'%'),'order'=>'Salemeet.id desc'));
			}			
		}else{
			$Salemeets = $this->Salemeet->find('all',array('order'=>'Salemeet.id desc'));	
		}
		$delimiter = ",";
		$filename = "salemeet_" . date('Y-m-d') . ".csv";
		
		//create a file pointer
		$f = fopen('php://memory', 'w');
	  //set column headers
		$fields = array('ID', 'Added By', 'Name', 'Phone', 'Email', 'occupation', 'Existing', 'interest', 'location', 'created');
		fputcsv($f, $fields, $delimiter);
		
		foreach($Salemeets as $sale){			
			$lineData = array($sale['Salemeet']['id'], $sale['Salemeet']['addedby'], $sale['Salemeet']['name'], $sale['Salemeet']['phone'], $sale['Salemeet']['email'], $sale['Salemeet']['occupation'], $sale['Salemeet']['existing'], $sale['Salemeet']['interest'], $sale['Salemeet']['location'], $sale['Salemeet']['created']);
			fputcsv($f, $lineData, $delimiter);
		}
		
		 //move back to beginning of file
		fseek($f, 0);
		
		//set headers to download file rather than displayed
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="' . $filename . '";');
		
		//output all remaining data on a file pointer
		fpassthru($f);
	}
	function sendmailtocustomer($id=null){
		$this->checkAdminSession(); 
		$this->autoRender = false;
		
		$Salemeets = $this->Salemeet->find('first',array('conditions'=>array('Salemeet.id'=>$id)));
		if(!empty($Salemeets['Salemeet']['email'])){
			
			$update['Salemeet']['id'] = $Salemeets['Salemeet']['id'];
			$update['Salemeet']['is_sent'] = 1;
			$this->Salemeet->save($update);
			
			$email = $Salemeets['Salemeet']['email'];
			$name = $Salemeets['Salemeet']['name'];								
			$subject= SITENAME.":- Thank You For Meeting";				
			$template_name = 'thankyoumailer';										
			$variables = array('email'=>$email,'name'=>$name);				
			try{
				$this->sendemail($email,$subject,$template_name,$variables);
			}catch (\Exception $e){
				
			}
			
			$this->Session->setFlash('Email sent successfully','default',array('class' => 'alert alert-success'));
			$this->redirect(['action' => 'salemeet']);	
		}else{
			$this->Session->setFlash('Email id is missing','default',array('class' => 'alert alert-danger'));
			$this->redirect(['action' => 'salemeet']);
		}	
		
			
	}	
/**
 * Dashboard a view
 *
 * @param mixed What page to display
 * @return void
 * @throws NotFoundException When the view file could not be found
 *	or MissingViewException in debug mode.
 */
	public function dashboard() {
		$this->viewBuilder()->setLayout('admin_layout');
		$this->set('title_for_layout',SITENAME.' Admin Dashboard Page');
		$this->checkAdminSession(); 
		
		$totalcate = $this->Category->find('count');
		$totalpro = $this->Product->find('count');
		$totalStaff = $this->Staff->find('count');
		$totalCustomer = $this->NappUser->find('count');
		
		$this->set('totalCustomer',$totalCustomer);
		$this->set('totalStaff',$totalStaff);
		$this->set('totalcate',$totalcate);
		$this->set('totalpro',$totalpro);
	}
	
/***
/*Author  :Ranjit,
/*Comment : admin Login page
****/
	public function login() {
		
		$this->viewBuilder()->setLayout('admin_login');
		$this->set('title_for_layout',SITENAME.' Admin Login Page');		
		$admin_id=$this->Session->read('User.id');
		if(!empty($admin_id)){
			$this->redirect(['action' => 'dashboard']);
		}		
		if(!empty($this->requestData())){		
			$admin_arr = $this->User->find('first',array('conditions'=>array('username'=>$this->requestData()['User']['username'],'password'=>$this->requestData()['User']['password'])));
			if(!empty($admin_arr)){				
				$insert['LoginHistory']['user_id'] = $admin_arr['User']['id'];
				$insert['LoginHistory']['role'] = 'Admin';
				$insert['LoginHistory']['logintime'] = date('Y-m-d H:i:s');
				$this->LoginHistory->save($insert);
				
				$this->Session->write('User', $admin_arr['User']);
				$this->Session->write('is_admin', 1);					
				$this->redirect(['action' => 'dashboard']);
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
		$this->autoRender = false;
			
		$userId = $this->Session->read('User.id');
		$LoginHistoryArr = $this->LoginHistory->find('first',array('conditions'=>array('LoginHistory.user_id'=>$userId,'LoginHistory.logouttime IS'=>null),'order'=>array('LoginHistory.id'=>'DESC')));
		if(!empty($LoginHistoryArr)){			
			$insert['LoginHistory']['id'] = $LoginHistoryArr['LoginHistory']['id'];
			$insert['LoginHistory']['user_id'] = $userId;
			$insert['LoginHistory']['logouttime'] = date('Y-m-d H:i:s');
			$this->LoginHistory->save($insert);
		}
		
		$this->Session->destroy();
		$this->redirect('/admin');

	}
	
	/***
	/*Author  :Ranjit,
	/*Comment : Admin profile
****/	
	function profile()
	{
		$this->viewBuilder()->setLayout('admin_layout');
		$this->set('title_for_layout',SITENAME.' Profile Page');
		$this->checkAdminSession();
		$admin_id=$this->Session->read('User.id');
		$user = $this->User->find('first',array('conditions'=>array('User.id'=>$admin_id)));
		if(!empty($this->requestData())){			
			if(!empty($user)){
				$data = $this->requestData();
				$data['User']['id']=$user['User']['id'];
				$this->setRequestData($data);
				
				if ($this->User->save($data)) {
				$this->Session->setFlash('Your profile has been updated','default',array('class' => 'alert alert-success'));
				$this->redirect(['action' => 'profile']);
				}else{
				$this->Session->setFlash('Your profile has not updated','default',array('class' => 'alert alert-danger'));
					
				}
			}
		}
	
		$this->setRequestData(is_array($user) ? $user : []);
	}	
	
	function subscriber_list()
	{
		$this->viewBuilder()->setLayout('admin_layout');
		$this->set('title_for_layout',SITENAME.' Subscriber List');
		$this->checkAdminSession();
		$this->paginate = array('conditions'=>array(),'page' => 1, 'limit' => 10,'order'=>'Subscriber.id desc');
		$this->Subscriber->recursive = 0;
		$Subscribers = $this->Paginator->paginate('Subscriber');	
		//echo '<pre>';print_r($Subscribers);die;
		$this->set('Subscribers',$Subscribers);	
	}
	function contactus()
	{
		$this->viewBuilder()->setLayout('admin_layout');
		$this->set('title_for_layout',SITENAME.' Contact List');
		$this->checkAdminSession();
		$this->paginate = array('conditions'=>array(),'page' => 1, 'limit' => 10,'order'=>'Contact.id desc');
		$this->Contact->recursive = 0;
		$contact = $this->Paginator->paginate('Contact');	
		//echo '<pre>'; print_r($contact ); die;
		$this->set('contact',$contact);	
	}
	
	

	
	function web_setting()
		{
			$this->viewBuilder()->setLayout('admin_layout');
			$this->set('title_for_layout',SITENAME.' Web setting');
			$this->checkAdminSession();
			$Config = $this->Config->find('first',array('conditions'=>array('Config.id'=>1)));
			$configRow = [];
			if (is_array($Config)) {
				$configRow = $Config['Config'] ?? $Config['config'] ?? $Config;
			}
			if (!is_array($configRow)) {
				$configRow = [];
			}
			
			if(!empty($this->requestData())){	
				$data = $this->requestData();
				if (!isset($data['Config']) || !is_array($data['Config'])) {
					$data['Config'] = [];
				}

				if (!empty($data['image']['name'])) {					
					$file = $data['image'];
					$ext = substr(strtolower(strrchr($file['name'], '.')), 1);
					$arr_ext = array('jpg', 'jpeg', 'gif','png');
					if (in_array($ext, $arr_ext)) {														
						move_uploaded_file($file['tmp_name'], WWW_ROOT . 'img/' . $file['name']);
						//prepare the filename for database entry
						$data['Config']['logo'] = $file['name'];							
					}else{
						$this->Session->setFlash('Please upload the valid image extension.','default',array('class' => 'alert alert-danger'));
						$this->redirect(['action' => 'web_setting']);
					}
				}		
			
				if (!empty($configRow['id'])) {
					$data['Config']['id'] = $configRow['id'];
				}
				$this->setRequestData($data);
				if ($this->Config->save($data)) {
				$this->Session->setFlash('Your web setting has been updated','default',array('class' => 'alert alert-success'));
				$this->redirect(['action' => 'web_setting']);
				}else{
				$this->Session->setFlash('Your web setting has not updated','default',array('class' => 'alert alert-danger'));
					
				}
				
			}
		
			$this->setRequestData(['Config' => $configRow]);
			$this->set('Config', ['Config' => $configRow]);
		}

	/**
	 * CakePHP 5 dashed-route compatibility (/admin/users/web-setting).
	 * Keep legacy implementation in web_setting().
	 */
	public function webSetting()
	{
		$this->web_setting();
	}

/***
	/*Author  :Ranjit,
	/*Comment : Admin change password
****/	
	function change_password()
	{
		$this->viewBuilder()->setLayout('admin_layout');
		$this->set('title_for_layout',SITENAME.' Change Password Page');
		$this->checkAdminSession();
		if($this->request->is('post')){
			$admin_id=$this->Session->read('User.id');
			$User = $this->User->find('first',array('conditions'=>array('User.id'=>$admin_id)));
			if(!empty($User)){
				$this->User->id=$User['User']['id'];
				if($this->requestData()['User']['new_password']==$this->requestData()['User']['confirm_password'])
				{
						
					if($User['User']['password']==$this->requestData()['User']['current_password'])
					{
						
						$this->User->saveField("password",$this->requestData()['User']['new_password']);
						$to=$User['User']['email'];
						$subject=SITENAME." Admin Password Change";
				
						/* // find template
						$emailTemplate = $this->EmailTemplate->find('first',array('conditions'=>array('EmailTemplate.tag'=>'change-password')));
						$name = $User['User']['name'];
						$email_template = $emailTemplate['EmailTemplate']['description'];						
						$email_template = str_replace('[NAME]',$name,$email_template);	
						$email_template = str_replace('[PASSWORD]',$this->requestData()['User']['new_password'],$email_template);	
					
						// SEND EMAIL
						// Always set content-type when sending HTML email
						$headers = "MIME-Version: 1.0" . "\r\n";
						$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
						// More headers
						$headers .= 'From: <no-reply@expressooh.com>' . "\r\n";	
						mail($to,$subject,$email_template,$headers); */
						$this->Session->setFlash('Your password has been changed successfully.','default',array('class' => 'alert alert-success'));
						$this->setRequestData([]);
					}else{
						$this->Session->setFlash('Current password not correct.Please, try again.','default',array('class' => 'alert alert-danger'));
					}
				}else{
					
					$this->Session->setFlash('New and confirm password not matched.Please, try again.','default',array('class' => 'alert alert-danger'));
				}
				
				
			}else{
				$this->Session->setFlash('User record not found.Please, try again.','default',array('class' => 'alert alert-danger'));
			}
			
		}
	}

	/**
	 * CakePHP 5 routes legacy URLs to camelCase (change-password → changePassword).
	 * Delegates to {@see change_password()}.
	 */
	public function changePassword(): void
	{
		$this->change_password();
	}
	
	/***
	/*Author  :Ranjit,
	/*Comment : Admin forgot password
****/
	function forgot_password()
	{
		if($this->request->is('post')){
			$User = $this->User->find('first',array('conditions'=>array('User.email'=>$this->requestData()['User']['email'])));
			if(!empty($User)){
				$this->User->id=$User['User']['id'];
				$password=$this->random_password(8);
				$this->User->saveField("password",$password);
				$to=$this->requestData()['User']['email'];
				$subject=SITENAME." Password reset";
				// find template
			/* 	$emailTemplate = $this->EmailTemplate->find('first',array('conditions'=>array('EmailTemplate.tag'=>'forgot-password')));
				$name = $User['User']['name'];
				$email_template = $emailTemplate['EmailTemplate']['description'];
				$email_template = str_replace('[NAME]',$name,$email_template);	
				$email_template = str_replace('[EMAIL]',$to,$email_template);	
				$email_template = str_replace('[PASSWORD]',$password,$email_template);	
				// SEND EMAIL
				// Always set content-type when sending HTML email
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
				// More headers
				$headers .= 'From: <no-reply@expressooh.com>' . "\r\n";	
				mail($to,$subject,$email_template,$headers); */
				$this->Session->setFlash('The mail has been sent to reset password.','default',array('class' => 'alert alert-success'));
			}else{
				$this->Session->setFlash('The email entered is not found.Please, try again.','default',array('class' => 'alert alert-danger'));
			}
			$this->redirect('/admin');
		}
	}
	
	/***
	/*Author  :Ranjit,
	/*Comment : Admin Payment Setup
	****/	
	function payment_setting()
	{
		$this->viewBuilder()->setLayout('admin_layout');
		$this->set('title_for_layout',SITENAME.' payment setting');
		$this->checkAdminSession();
		$admin_id= $this->Session->read('User.id');
		$PaymentSetup = $this->PaymentSetup->find('first',array('conditions'=>array('PaymentSetup.id'=>1)));
		if(!empty($this->requestData())){			
			if(!empty($PaymentSetup)){
				$data = $this->requestData();
				$data['PaymentSetup']['id']=$PaymentSetup['PaymentSetup']['id'];
				$this->setRequestData($data);
			
				if ($this->PaymentSetup->save($data)) {
				$this->Session->setFlash('Your Payment Setup has been updated','default',array('class' => 'alert alert-success'));
				$this->redirect(['action' => 'payment_setting']);
				}else{
				$this->Session->setFlash('Your Payment Setup has not updated','default',array('class' => 'alert alert-danger'));
					
				}
			}
		}
	
		$this->setRequestData(is_array($PaymentSetup) ? $PaymentSetup : []);
		$this->set('payment_setup',$PaymentSetup);
	}
	
	function uploadprodcut(){
		$this->viewBuilder()->setLayout('admin_layout');
		$this->set('title_for_layout',SITENAME.' payment setting');
		$this->checkAdminSession();
		
		if(!empty($this->requestData())){
			if(!empty($this->requestData()['file']['name'])){
				$filename = $this->requestData()['file']['name'];
				$tmp_name = $this->requestData()['file']['tmp_name'];
				$org  = time().$filename;
				move_uploaded_file($tmp_name,'uploadfile/'.$org);
				
				
				echo '<pre>';
				print_r($this->requestData());
				die();	
			}			
		}		
	}	
	
	public function random_password( $length = 8 ) {
		
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789";
		//$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%?";
		$password = substr( str_shuffle( $chars ), 0, $length );
		return $password;
	}
	
	
	
	function customer()
	{
		$this->viewBuilder()->setLayout('admin_layout');
		$this->set('title_for_layout',SITENAME.' Submited Quizz');
		$this->checkAdminSession();
		$name = '';
		$nappUsers = $this->fetchTable('NappUser');
		$query = $nappUsers->find()
			->where(['NappUser.is_staff_id' => 0])
			->contain(['LabAssign' => ['LabFile']])
			->order(['NappUser.id' => 'DESC']);

		if (!empty($this->requestData())) {
			$name = trim((string)($this->requestData()['name'] ?? ''));
			if ($name !== '') {
				$query->where([
					'OR' => [
						'NappUser.name LIKE' => '%' . $name . '%',
						'NappUser.lname LIKE' => '%' . $name . '%',
						'NappUser.email LIKE' => '%' . $name . '%',
					],
				]);
			}
		}

		$this->paginate = [
			'limit' => 25,
			'order' => ['NappUser.id' => 'DESC'],
			'sortableFields' => [
				'NappUser.name',
				'NappUser.email',
				'NappUser.company',
				'NappUser.insert_date',
			],
		];
		$paged = $this->paginate($query);

		$NappUser = [];
		foreach ($paged as $entity) {
			$napp = $entity->toArray();
			unset($napp['lab_assign']);
			$labAssign = [];
			foreach ($entity->lab_assign ?? [] as $la) {
				$laArr = $la->toArray();
				unset($laArr['lab_file']);
				$lfArr = $la->lab_file !== null ? $la->lab_file->toArray() : [];
				$labAssign[] = [
					'LabAssign' => $laArr,
					'LabFile' => $lfArr,
				];
			}
			$NappUser[] = [
				'NappUser' => $napp,
				'LabAssign' => $labAssign,
			];
		}

		$this->set('NappUser', $NappUser);
		$this->set('nappUsersPaginated', $paged);
		$this->set('name',$name);
	}
	
	function quizz()
	{
		$this->viewBuilder()->setLayout('admin_layout');
		$this->set('title_for_layout',SITENAME.' Submited Quizz');
		$this->checkAdminSession();
		
		$this->QuestionSubmit->bindModel(
		array('belongsTo' => array('NappUser' => array(
			'className' => 'NappUser',			 
			'foreignKey' => 'user_id',				
			'conditions' => array(),
			'fields' => '',
			'order' => array(),
		))));
		$this->paginate = array('conditions'=>array(),'page' => 1, 'limit' => 10,'order'=>'QuestionSubmit.id desc');
		$this->QuestionSubmit->recursive = 0;
		$QuestionSubmitArr = $this->Paginator->paginate('QuestionSubmit');	
		//echo '<pre>'; print_r($QuestionSubmitArr); die;
		$this->set('QuestionSubmitArr',$QuestionSubmitArr);	
	}
	
	function staff()
	{
		$this->viewBuilder()->setLayout('admin_layout');
		$this->set('title_for_layout',SITENAME.' Staff List');
		$this->checkAdminSession();

		$name = '';
		$nappUsers = $this->fetchTable('NappUser');
		$query = $nappUsers->find();
		$query->where(['NappUser.is_staff_id' => 1]);

		if (!empty($this->requestData())) {
			$name = trim((string)($this->requestData()['name'] ?? ''));
			if ($name !== '') {
				$query->where([
					'OR' => [
						'NappUser.name LIKE' => '%' . $name . '%',
						'NappUser.lname LIKE' => '%' . $name . '%',
						'NappUser.email LIKE' => '%' . $name . '%',
					],
				]);
			}
		}

		$query->order(['NappUser.id' => 'DESC']);
		$query->contain(['LabAssign' => ['LabFile']]);

		$paged = $this->paginate($query, ['limit' => 25]);

		$staffArr = [];
		foreach ($paged as $entity) {
			$napp = $entity->toArray();
			unset($napp['lab_assign']);
			$labAssign = [];
			foreach ($entity->lab_assign ?? [] as $la) {
				$laArr = $la->toArray();
				unset($laArr['lab_file']);
				$lfArr = $la->lab_file !== null ? $la->lab_file->toArray() : [];
				$labAssign[] = [
					'LabAssign' => $laArr,
					'LabFile' => $lfArr,
				];
			}
			$staffArr[] = [
				'NappUser' => $napp,
				'LabAssign' => $labAssign,
			];
		}

		$this->set('staffArr', $staffArr);
		// CakePHP 5 PaginatorHelper needs a PaginatedInterface on the view (see PaginatorHelper::paginated()).
		$this->set('staffPaginated', $paged);
		$this->set('name', $name);
	}
	
	function labfile()
	{
		$this->viewBuilder()->setLayout('admin_layout');
		$this->set('title_for_layout',SITENAME.' Label File List');
		$this->checkAdminSession();
		$this->paginate = array('conditions'=>array(),'page' => 1, 'limit' => 10);
		$this->LabFile->recursive = 0;
		$staffArrArr = $this->Paginator->paginate('LabFile');	
		//echo '<pre>'; print_r($staffArrArr); die;
		$this->set('staffArrArr',$staffArrArr);	
	}
	
	function updatelabfile($id=null)
	{
		$this->viewBuilder()->setLayout('admin_layout');
		$this->set('title_for_layout',SITENAME.' update Label File');
		$this->checkAdminSession();
		$labfile = $this->LabFile->find('first',array('conditions'=>array('LabFile.id'=>$id)));
		$this->set('labfile',$labfile);
		
		if(!empty($this->requestData())){
			
			$dir = $labfile['LabFile']['dir'];
			$password = $this->requestData()['User']['password'];
			if(!empty($_FILES['filename']['name'])){
				$filename = time().$_FILES['filename']['name'];
				$tmp_name = $_FILES['filename']['tmp_name'];
				move_uploaded_file($tmp_name,WWW_ROOT .'/'.$dir.'/'.$filename);
				$savedata['LabFile']['filename'] = $filename;
			}				
			$savedata['LabFile']['id'] = $id;		
			$savedata['LabFile']['password'] = $password;
			$this->LabFile->save($savedata);
			$this->Session->setFlash('File updated successfully','default',array('class' => 'alert alert-success'));
			$this->redirect(['action' => 'labfile']);
		}		
	}
	
	function access($id=null)
	{
		$this->viewBuilder()->setLayout('admin_layout');
		$this->set('title_for_layout',SITENAME.' update Label File');
		$this->checkAdminSession();
		$LabAssign = $this->LabAssign->find('all',array('conditions'=>array('LabAssign.customer_id'=>$id)));
		$this->set('LabAssign',$LabAssign);
		// echo '<pre>';
		// print_r($LabAssign);
		// die();
		
		$NappUser = $this->NappUser->find('first',array('conditions'=>array('NappUser.id'=>$id)));
		$this->set('NappUser',$NappUser);
		
		$labfile = $this->LabFile->find('all');
		$this->set('labfile',$labfile);
		
		if(!empty($this->requestData())){
			
			$name = ucfirst($NappUser['NappUser']['name']).' '.ucfirst($NappUser['NappUser']['lname']);
					
			$this->LabAssign->query('DELETE FROM lab_assign WHERE customer_id = ' . (int)$id);
			if(!empty($this->requestData()['lab_id'])){
				$label = '';
				foreach($this->requestData()['lab_id'] as $lab_id){
					
					$LabFile = $this->LabFile->find('first',array('conditions'=>array('LabFile.id'=>$lab_id)));
					$label .= $LabFile['LabFile']['label'].' , ';
									
					$lab['LabAssign']['id'] = '';
					$lab['LabAssign']['customer_id'] = $id;
					$lab['LabAssign']['lab_id'] = $lab_id;
					$this->LabAssign->save($lab);
				}

				$label = rtrim($label,' ,');	
				if(!empty($label)){
					
					$to = $NappUser['NappUser']['email'];								
					$subject= SITENAME." :: Access To Price List Approved";				
					$template_name = 'congrates_price_list';										
					$variables = array('name'=>$name,'label'=>$label,'type'=>$NappUser['NappUser']['is_staff_id']);	
			
					try{
						$this->sendemail($to,$subject,$template_name,$variables);
					}catch (\Exception $e){
						
					}
				}	
			}					
				
			$this->Session->setFlash('Labs Assigned','default',array('class' => 'alert alert-success'));
			if($NappUser['NappUser']['is_staff_id'] == 0){
				$this->redirect(['action' => 'customer']);
			}else{
				$this->redirect(['action' => 'staff']);
			}	
		}		
	}
	
	function add_staff()
	{
	$this->viewBuilder()->setLayout('admin_layout');
	$this->set('title_for_layout',SITENAME.' Add Staff Page');
	$this->checkAdminSession();
	
		if(!empty($this->requestData())){	
			// echo '<pre>';
			// print_r($this->requestData());
			// die();
			$email = $this->requestData()['Staff']['email'];
			$staffArr = $this->Staff->find('first',array('conditions'=>array('Staff.email'=>$email)));
			if(empty($staffArr)){
				if ($this->Staff->save($this->requestData())) {
					$this->Session->setFlash('Added successfully','default',array('class' => 'alert alert-success'));
					$this->redirect(['action' => 'contact']);
				}else{
					$this->Session->setFlash('Your profile has not updated','default',array('class' => 'alert alert-danger'));				
				}
			}else{
				$this->Session->setFlash('email id is already exist','default',array('class' => 'alert alert-danger'));				
			}
		}
		$department = $this->Department->find('all');
		$this->set('departmentArr',$department);
	}
	public function editStaff(?string $id = null): void
	{
		$this->viewBuilder()->setLayout('admin_layout');
		$this->set('title_for_layout', SITENAME . ' Edit Staff Page');
		$this->checkAdminSession();

		$staffArr = $this->NappUser->find('first', ['conditions' => ['NappUser.id' => $id]]);
		if (!empty($this->requestData())) {
			$data = $this->requestData();
			$email = $data['NappUser']['email'];
			$staffArrnew = $this->NappUser->find('first', [
				'conditions' => ['NappUser.id !=' => $id, 'NappUser.email' => $email],
			]);
			if (empty($staffArrnew)) {
				$data['NappUser']['id'] = $id;
				$this->setRequestData($data);

				if ($this->NappUser->save($data)) {
					$this->Session->setFlash('updated successfully', 'default', ['class' => 'alert alert-success']);
					$this->redirect(['action' => 'staff']);
				} else {
					$this->Session->setFlash('Your profile has not updated', 'default', ['class' => 'alert alert-danger']);
				}
			} else {
				$this->Session->setFlash('This employee id is already exist', 'default', ['class' => 'alert alert-danger']);
			}
		}

		$this->set('staffArr', $staffArr);
		$this->setRequestData(is_array($staffArr) ? $staffArr : []);

		$department = $this->Department->find('all');
		$this->set('departmentArr', $department);
	}
	
	function edit_new_staff($id=null)
	{
		$this->viewBuilder()->setLayout('admin_layout');
		$this->set('title_for_layout',SITENAME.' Edit Staff Page');
		$this->checkAdminSession();
		$admin_id=$this->Session->read('User.id');
	
		$staffArr = $this->Staff->find('first',array('conditions'=>array('Staff.id'=>$id)));
		if(!empty($this->requestData())){						
			$data = $this->requestData();
			$email = $data['Staff']['email'];		
			$staffArrnew = $this->Staff->find('first',array('conditions'=>array('Staff.id !='=>$id,'Staff.email'=>$email)));
			if(empty($staffArrnew)){				
				$data['Staff']['id'] = $id;
				$this->setRequestData($data);
				
				if ($this->Staff->save($data)) {
						
					$this->Session->setFlash('updated successfully','default',array('class' => 'alert alert-success'));
					$this->redirect(['action' => 'contact']);
				}else{
					$this->Session->setFlash('Your profile has not updated','default',array('class' => 'alert alert-danger'));				
				}
			}else{
				$this->Session->setFlash('This employee id is already exist','default',array('class' => 'alert alert-danger'));				
			}
		}
		
		
		$this->set('staffArr',$staffArr);
		$this->setRequestData(is_array($staffArr) ? $staffArr : []);
		
		$department = $this->Department->find('all');
		$this->set('departmentArr',$department);
	} 
	public function natspecPresentationStatus($user_id = null, $status = null, $type = null): void
	{
		$this->autoRender = false;

		$update['NappUser']['id'] = $user_id;
		$update['NappUser']['is_natspec_presentation'] = $status;
		$this->NappUser->save($update);
		$this->Session->setFlash('natspec presentation status has been updated', 'default', ['class' => 'alert alert-success']);
		if ($type == 1) {
			$this->redirect(['action' => 'staff']);
		} else {
			$this->redirect(['action' => 'customer']);
		}
	}

	public function cpdPresentationStatus($user_id = null, $status = null, $type = null): void
	{
		$this->autoRender = false;

		$update['NappUser']['id'] = $user_id;
		$update['NappUser']['is_cpd_presentation'] = $status;
		$this->NappUser->save($update);
		$this->Session->setFlash('cpd presentation status has been updated', 'default', ['class' => 'alert alert-success']);
		if ($type == 1) {
			$this->redirect(['action' => 'staff']);
		} else {
			$this->redirect(['action' => 'customer']);
		}
	}

	function accesstouser($custId=null){
		
		$this->autoRender = false;
		if(!empty($custId)){
			$custId = base64_decode(base64_decode($custId));
			$user = $this->NappUser->find('first',array('conditions'=>array('NappUser.id'=>$custId)));
			if(!empty($user)){						
				$admin_arr = $this->User->find('first');
				if(!empty($admin_arr)){				
				$this->Session->write('User', $admin_arr['User']);
				$this->Session->write('is_admin', 1);			
				$this->redirect(['action' => 'access', $custId]);
				}else{
					//$this->Session->setFlash(__('Wrong username/password', true));
					$this->Session->setFlash('Wrong username/password','default',array('class' => 'alert alert-danger'));
				}
			}else{
				$this->Session->setFlash('sorry this customer not exist.','default',array('class' => 'alert alert-success'));	
				$this->redirect('/admin');		
			}	
		}else{
			$this->Session->setFlash('customer id is missing.','default',array('class' => 'alert alert-success'));	
			$this->redirect('/admin');
		}	
	}	
	
	function contact()
	{
		$this->viewBuilder()->setLayout('admin_layout');
		$this->set('title_for_layout',SITENAME.' Staff List');
		$this->checkAdminSession();
	
		$this->Staff->bindModel(
		array('belongsTo' => array('Department' => array(
			'className' => 'Department',			 
			'foreignKey' => 'dept_id',				
			'conditions' => array(),
			'fields' => '',
			'order' => array(),
		))));
		
		// if(!empty($this->requestData())){
			// $name = rtrim($this->requestData()['name'],' ');
			// $name = ltrim($name,' ');
			// $this->paginate = array('conditions'=>array('NappUser.is_staff_id'=>1,'OR'=>array('NappUser.name LIKE'=>'%'.$name.'%','NappUser.lname LIKE'=>'%'.$name.'%','NappUser.email LIKE'=>'%'.$name.'%')),'page' => 1, 'limit' => 25,'order'=>array('NappUser.id'=>'desc'));
		// }else{
			// $this->paginate = array('conditions'=>array('NappUser.is_staff_id'=>1),'page' => 1, 'limit' => 25,'order'=>array('NappUser.id'=>'desc'));
		// }	
		$this->NappUser->recursive = 2;		
		$staffArr = $this->Paginator->paginate('Staff');	
		//echo '<pre>'; print_r($staffArr); die;
		$this->set('staffArr',$staffArr);	
		
		
	}
	
	public function loginhisotry(): void
	{
		$this->viewBuilder()->setLayout('admin_layout');
		$this->set('title_for_layout', SITENAME . ' Login History');
		$this->checkAdminSession();

		$loginHistory = $this->fetchTable('LoginHistory');
		$query = $loginHistory->find()
			->contain(['User', 'NappUser'])
			->orderDesc($loginHistory->aliasField('id'));

		$this->paginate = [
			'limit' => 10,
			'order' => ['LoginHistory.id' => 'DESC'],
			'sortableFields' => [
				'LoginHistory.id',
				'LoginHistory.role',
				'LoginHistory.logintime',
				'LoginHistory.logouttime',
			],
		];
		$paged = $this->paginate($query);

		$LoginHistoryArr = [];
		foreach ($paged as $entity) {
			$row = $entity->toArray();
			unset($row['user'], $row['napp_user']);
			$userRow = ($entity->user !== null) ? $entity->user->toArray() : [];
			$nappRow = ($entity->napp_user !== null) ? $entity->napp_user->toArray() : [];
			$LoginHistoryArr[] = [
				'LoginHistory' => $row,
				'User' => $userRow,
				'NappUser' => $nappRow,
			];
		}

		$this->set('LoginHistoryArr', $LoginHistoryArr);
		$this->set('loginHistoryPaginated', $paged);
	}
}
