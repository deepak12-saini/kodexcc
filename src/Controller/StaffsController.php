<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 * Staff area (non-admin): dashboard, profile, conformance, datasheets.
 */
class StaffsController extends AppController
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
	
	function activeaccount($semployeeid=null){
		
		$this->autoRender = false;
		
		if(!empty($semployeeid)){
			$email = base64_decode(base64_decode(base64_decode($semployeeid)));
			//$email = 'web@xoroglobal.com';
			$cus_arr = $this->NappUser->find('first',array('conditions'=>array('email'=>$email)));
			if(!empty($cus_arr)){
				$admin_arr = $this->User->find('first');	
				$insert['LoginHistory']['admin_id'] = $admin_arr['User']['id'];
				$insert['LoginHistory']['role'] = 'Admin';
				$insert['LoginHistory']['logintime'] = date('Y-m-d H:i:s');
				$this->LoginHistory->save($insert);
				
				$this->Session->write('User', $admin_arr['User']);
				$this->Session->write('is_admin', 1);
				$this->redirect('/admin/users/edit_staff/'.$cus_arr['NappUser']['id']);				
				
			}else{
				$this->Session->setFlash('Sorry staff id missing','default',array('class' => 'alert alert-success'));
				$this->redirect('/admin');
			}
		}else{
			$this->Session->setFlash('Sorry staff id missing','default',array('class' => 'alert alert-success'));
			$this->redirect('/admin');
		}	
		
	}	
	
	public function dashboard() {
		$this->layout='staff_inner_layout';
		$this->set('title_for_layout',SITENAME.' Staff Dashboard Page');
		$this->checkSatffSession(); 
				
		$customer_id=$this->Session->read('Customer.id');
		$cus_arr = $this->NappUser->find('first',array('conditions'=>array('id'=>$customer_id)));
		$this->set('cus_arr',$cus_arr);
		
	}

	function profile()
	{
		$this->layout='staff_inner_layout';
		$this->set('title_for_layout',SITENAME.' Profile Page');
		$this->checkSatffSession();
		$customer_id=$this->Session->read('Customer.id');
			
		$user = $this->NappUser->find('first',array('conditions'=>array('NappUser.id'=>$customer_id)));
		if(!empty($this->requestData())){			
			if(!empty($user)){
				$data = $this->requestData();
				$data['NappUser']['id'] = $customer_id;
				$this->setRequestData($data);
				
				if ($this->NappUser->save($data)) {
				$this->Session->setFlash('Your profile has been updated','default',array('class' => 'alert alert-success'));
				$this->redirect('profile');
				}else{
				$this->Session->setFlash('Your profile has not updated','default',array('class' => 'alert alert-danger'));
					
				}
			}
		}	
		$this->setRequestData($user);
		
		$department = $this->Department->find('all');
		$this->set('user',$user);
		$this->set('departmentArr',$department);
	}
	
	function change_password()
	{
		$this->layout='staff_inner_layout';
		$this->set('title_for_layout',SITENAME.' Change Password Page');
		$this->checkSatffSession();
		if($this->getRequest()->is('post')){
			$customer_id=$this->Session->read('Customer.id');
			$user = $this->NappUser->find('first',array('conditions'=>array('NappUser.id'=>$customer_id)));
			if(!empty($user)){
				$this->NappUser->id=$user['NappUser']['id'];
				if($this->requestData()['NappUser']['new_password']==$this->requestData()['NappUser']['confirm_password'])
				{
						
					if($user['NappUser']['password']==$this->requestData()['NappUser']['current_password'])
					{
						
						$this->NappUser->saveField("password",$this->requestData()['NappUser']['new_password']);
						
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
	
	function datasheet(){
		
		$this->layout='staff_inner_layout';
		$this->set('title_for_layout',SITENAME.' Product Datasheet');
		$this->checkSatffSession();
		$user_id=$this->Session->read('Customer.id');
		
		$this->Product->bindModel(
		array('belongsTo' => array('Category' => array(
			'className' => 'Category',			 
			'foreignKey' => 'category_id',				
			'conditions' => array(),
			'fields' => '',
			'order' => array(),
		))));
		$name = '';
		$category_id = '';
		if(!empty($this->requestData())){
			$name = $this->requestData()['productname'];
			$category_id = $this->requestData()['category_id'];
			if(!empty($category_id) && !empty($name)){
				$this->paginate = array('conditions'=>array('Product.category_id'=>$category_id,'Product.status'=>1,'Product.is_deleted'=>0,'Product.datasheet !='=>'','Product.title LIKE'=>'%'.$name.'%'),'page' => 1,'page' => 1, 'limit' => 20);
			}else if(!empty($category_id)){
				$this->paginate = array('conditions'=>array('Product.category_id'=>$category_id,'Product.status'=>1,'Product.is_deleted'=>0),'page' => 1,'page' => 1, 'limit' => 20);
			}else if(!empty($name)){
				$this->paginate = array('conditions'=>array('Product.status'=>1,'Product.is_deleted'=>0,'Product.datasheet !='=>'','Product.title LIKE'=>'%'.$name.'%'),'page' => 1,'page' => 1, 'limit' => 20);
			}		
			
		}else{
			$this->paginate = array('conditions'=>array('Product.status'=>1,'Product.is_deleted'=>0,'Product.datasheet !='=>''),'page' => 1, 'limit' => 25,'order'=>array('Product.title'=>'asc'));
		}
		
		$productArr = $this->Paginator->paginate('Product');		
		$this->set('productArr',$productArr);
		$this->set('category_id',$category_id);
		$this->set('name',$name);
		
		$categoryArr = $this->Category->find('all',array('conditions'=>array('Category.is_deteled'=>0,'Category.status'=>1)));
		$this->set('categoryArr',$categoryArr);
		
		
		$upermission = $this->UserPermission->find('all',array('conditions'=>array('UserPermission.user_id'=>$user_id)));
		$this->set('upermission',$upermission);			
	
	}	
	
	function document($product_code=null,$type=null,$num=null){
		
		$this->layout='staff_inner_layout';
		$this->set('title_for_layout',SITENAME.' Product Datasheet');
		$this->checkSatffSession();
		$user_id=$this->Session->read('Customer.id');
		if(!empty($product_code) && !empty($type)){
			$product_code = base64_decode($product_code);
			$data = $this->Product->find('first',array('conditions'=>array('Product.product_code'=>$product_code)));
					
			if(empty($data)){
				$this->Session->setFlash('Something missing','default',array('class' => 'alert alert-success'));
				$this->redirect('datasheet');
			}	
			$this->set('data',$data);			
			$this->set('type',$type);			
			$this->set('num',$num);			
		}else{
			$this->Session->setFlash('Something missing','default',array('class' => 'alert alert-success'));
			$this->redirect('datasheet');
		}	
	}
	
	function autologin($conformance_id=null){
		
		$this->autoRender = false;
		
		$ConformanceArr = $this->Conformance->find('first',array('conditions'=>array('Conformance.id'=>base64_decode($conformance_id))));
		if(!empty($ConformanceArr)){
			
			$userArr  = $this->User->find('first',array('conditions'=>array('User.id'=>2)));
			$insert['LoginHistory']['admin_id'] = $userArr['User']['id'];
			$insert['LoginHistory']['role'] = 'Admin';
			$insert['LoginHistory']['logintime'] = date('Y-m-d H:i:s');
			$this->LoginHistory->save($insert);
			if(!empty($userArr)){				
				$this->Session->write('User', $userArr['User']);
				$this->Session->write('is_admin', 1);			
				$this->redirect('/admin/staffs/replyto/'.$conformance_id);
			}			
		}else{
			$this->redirect('/admin');
		}	
			
	}	
	
	function replyto($conformance_id=null,$emp_id=null){
		$this->layout='staff_inner_layout';
		$this->set('title_for_layout',SITENAME.' Conformance Reply');
		$this->checkSatffSession();
	
		$user_id=$this->Session->read('Customer.id');
		$dept_id=$this->Session->read('Customer.dept_id');
		
		$Department = $this->Department->find('first',array('conditions'=>array('Department.id'=>$dept_id)));
		
		$name = $this->Session->read('Customer.name').' '.$this->Session->read('Customer.lname').' ('.$Department['Department']['department_title'].')';
				
		$this->ConformanceRelation->bindModel(
		array('belongsTo' => array('Conformance' => array(
			'className' => 'Conformance',			 
			'foreignKey' => 'conformance_id',				
			'conditions' => array(),
			'fields' => array(),
			'order' => array(),
		))));
		$ConformanceArr = $this->ConformanceRelation->find('first',array('conditions'=>array('ConformanceRelation.emp_id'=>base64_decode($emp_id),'ConformanceRelation.conformance_id'=>base64_decode($conformance_id))));	
				
		if(!empty($this->requestData())){
						
			$conformance_id = $ConformanceArr['Conformance']['id'];					
			$ConformanceRelation_id = $ConformanceArr['ConformanceRelation']['id'];						
			$updaterelation['ConformanceRelation']['id'] =  $ConformanceRelation_id;		
			
			$updaterelation['ConformanceRelation']['corrective_action_taken'] =  $this->requestData()['Conformance']['corrective_action_taken'];
			//$updaterelation['ConformanceRelation']['preventive_action'] =  $this->requestData()['Conformance']['preventive_action'];
			$updaterelation['ConformanceRelation']['created'] =  date('Y-m-d H:i:s');
			$this->ConformanceRelation->save($updaterelation);
			
			// send email to intiative
			$compaint_by = $ConformanceArr['Conformance']['compaint_by'];			
			$NappUserArr = $this->NappUser->find('first',array('conditions'=>array('NappUser.id'=>$compaint_by)));			
			$to_email = $NappUserArr['NappUser']['email'];
			//$to_email = 'web@xoroglobal.com';
			$to_name  = $NappUserArr['NappUser']['name'];	
			$subject = SITENAME." :: Non Conformance ".$ConformanceArr['Conformance']['nc_number']." By ".$this->Session->read('Customer.name');				
			$template_name = 'replyconformance';	
			$non_conforance = $ConformanceArr['Conformance']['non_conforance'];			
			$variables = array('name'=>$this->Session->read('Customer.name'),'to_name'=>$to_name,'title'=>$non_conforance,'nc_number'=>$ConformanceArr['Conformance']['nc_number']);		
			try{
				$this->sendemail($to_email,$subject,$template_name,$variables);
			}catch (Exception $e){
				
			}	
			
			$admin_email = 'kal@durotechindustries.com.au';
			#$admin_email = 'web@xoroglobal.com';
			$admin_subject = SITENAME." :: Non Conformance ".$ConformanceArr['Conformance']['nc_number']." By ".$this->Session->read('Customer.name');

			$autologinurl = SITEURL.'staffs/autologin/'.base64_encode($ConformanceArr['Conformance']['id']);	
			$admin_template_name = 'autoreplyconformance';	
			$non_conforance = $ConformanceArr['Conformance']['non_conforance'];			
			$admin_variables = array('autologinurl'=>$autologinurl,'name'=>$this->Session->read('Customer.name'),'to_name'=>'Kal','title'=>$non_conforance,'nc_number'=>$ConformanceArr['Conformance']['nc_number']);		
			try{
				$this->sendemail($admin_email,$admin_subject,$admin_template_name,$admin_variables);
			}catch (Exception $e){
				
			}
			
			$updateConformance['Conformance']['id'] =  $conformance_id;			
		 	if(!empty($this->requestData()['Conformance']['corrective_action_taken'])){
				$updateConformance['Conformance']['corrective_action_taken'] =  $this->requestData()['Conformance']['corrective_action_taken'];
				$updateConformance['Conformance']['is_corrective'] =  $name;
			}
		/*	
			if(!empty($this->requestData()['Conformance']['preventive_action'])){
				$updateConformance['Conformance']['preventive_action'] =  $this->requestData()['Conformance']['preventive_action'];
				$updateConformance['Conformance']['is_preventive'] =  $name;
			}	 */
			$updateConformance['Conformance']['why_1'] =  $this->requestData()['Conformance']['why_1'];	
			$updateConformance['Conformance']['why_2'] =  $this->requestData()['Conformance']['why_2'];	
			$updateConformance['Conformance']['why_3'] =  $this->requestData()['Conformance']['why_3'];	
			$updateConformance['Conformance']['why_4'] =  $this->requestData()['Conformance']['why_4'];	
			$updateConformance['Conformance']['why_5'] =  $this->requestData()['Conformance']['why_5'];	
			
			if($this->requestData()['Conformance']['why_1'] !=  '' || $this->requestData()['Conformance']['why_2'] !=  '' || $this->requestData()['Conformance']['why_3'] !=  '' || $this->requestData()['Conformance']['why_4'] !=  '' || $this->requestData()['Conformance']['why_5'] !=  ''){
				$updateConformance['Conformance']['is_corrective'] =  $name;
			}
			$updateConformance['Conformance']['status'] = 2;	
			$updateConformance['Conformance']['created'] =  date('Y-m-d H:i:s');
			$this->Conformance->save($updateConformance);
			
			if(!empty($this->requestData()['ConformanceRelationnew']['emp_id'])){
				
				$emp_id = $this->requestData()['ConformanceRelationnew']['emp_id'];
				$insertrelation['ConformanceRelation']['id'] =  '';		
				$insertrelation['ConformanceRelation']['conformance_id'] =  $conformance_id;		
				$insertrelation['ConformanceRelation']['emp_id'] = $emp_id;					
				$this->ConformanceRelation->save($insertrelation);
				
				
				// $template_name = 'replyconformance';	
				// $non_conforance = $ConformanceArr['Conformance']['non_conforance'];			
				// $variables = array('name'=>$this->Session->read('Customer.name'),'to_name'=>$to_name,'title'=>$non_conforance,'nc_number'=>$ConformanceArr['Conformance']['nc_number']);		
				// try{
					// $this->sendemail($to_email,$subject,$template_name,$variables);
				// }catch (Exception $e){
					
				// }	
				
			}	
		
			$this->Session->setFlash('Replied successfully','default',array('class' => 'alert alert-success'));
			$this->redirect('conformancelist');
		}	
		$this->set('conformanceArr',$ConformanceArr);
		
		$confrel = $this->ConformanceRelation->find('list',array('conditions'=>array('ConformanceRelation.conformance_id'=>base64_decode($conformance_id)),'fields'=>array('emp_id')));	
		
		$NappUser = $this->NappUser->find('all',array('conditions'=>array('NappUser.id !='=>$confrel,'NappUser.is_staff_id'=>1)));
		$this->set('NappUser',$NappUser);
		$this->set('user_id',$user_id);
	
	}	
	function conformance($complaint_id=null){
		
		$this->layout='staff_inner_layout';
		$this->set('title_for_layout',SITENAME.' Conformance List');
		$this->checkSatffSession();
		
		if(!empty($complaint_id)){
			$complaint_id = base64_decode($complaint_id);
			
			$complaintArr = $this->Conformance->find('first',array('conditions'=>array('Conformance.complaintid'=>$complaint_id)));
			if(!empty($complaintArr)){
				$this->redirect('index');
			}	
		}else{
			$complaint_id = 0;
		}	
		
		
		
		$user_id=$this->Session->read('Customer.id');			
		$name=$this->Session->read('Customer.name');	
		if(!empty($this->requestData())){
			
			$data = $this->requestData();
			$confirm = $this->Conformance->find('first',array('order'=>array('Conformance.id'=>'DESC')));
			$lastId = (int)($confirm['Conformance']['id'] ?? 0);
			$randnumber = 1000 + $lastId;		
		
			$data['Conformance']['nc_number'] = 'DT-NCR-'.$randnumber;				
			$data['Conformance']['compaint_by'] = $user_id;				
			$data['Conformance']['status'] = 1;	
					
			$data['Conformance']['complaintid'] = $complaint_id;				
			$data['Conformance']['created'] = date('Y-m-d H:i:s');	
			$this->setRequestData($data);
			
			//ConformanceRelation
			$this->Conformance->save($data);	


			$compaint_to = $data['Conformance']['compaint_to'];
			$NappUserArr = $this->NappUser->find('first',array('conditions'=>array('NappUser.id'=>$compaint_to)));			
			$to_email = $NappUserArr['NappUser']['email'];
			$to_name  = $NappUserArr['NappUser']['name'];	
			$subject = SITENAME." :: Non Conformance DT-NC-".$randnumber." By ".ucfirst($name);				
			$template_name = 'conformance';	
			$non_conforance = $data['Conformance']['non_conforance'];			
			$variables = array('name'=>$name,'to_name'=>$to_name,'title'=>$non_conforance);		
			try{
				$this->sendemail($to_email,$subject,$template_name,$variables);
			}catch (Exception $e){
				
			}		
			
			$conformance_id = $this->Conformance->id;
				// assign to
			$instConformanceRelation['ConformanceRelation']['id'] = '';
			$instConformanceRelation['ConformanceRelation']['conformance_id'] = $conformance_id;
			$instConformanceRelation['ConformanceRelation']['emp_id'] = $user_id;
			$this->ConformanceRelation->save($instConformanceRelation);
				// assig by
			$instConformanceRelation['ConformanceRelation']['id'] = '';
			$instConformanceRelation['ConformanceRelation']['conformance_id']  = $conformance_id;
			$instConformanceRelation['ConformanceRelation']['emp_id'] = $data['Conformance']['compaint_to'];
			$this->ConformanceRelation->save($instConformanceRelation);
			
			$this->Session->setFlash('Generated successfully','default',array('class' => 'alert alert-success'));
			$this->redirect('conformancelist');
		}
		$department = $this->Department->find('all');
		$this->set('departmentArr',$department);

		$NappUser = $this->NappUser->find('all',array('conditions'=>array('NappUser.id !='=>$user_id,'NappUser.is_staff_id'=>1)));
		$this->set('NappUser',$NappUser);	
		
	}	
	
	// function detail($complaint_id=null){
		
		// $this->layout='staff_inner_layout';
		// $this->set('title_for_layout',SITENAME.' Conformance List');
		// $this->checkSatffSession();
		// $user_id=$this->Session->read('Customer.id');
		
		// $this->Conformance->find('first',array('conditions'=>array('Conformance.id')));
	// }	
	
	function conformancelist(){
		
		$this->layout='staff_inner_layout';
		$this->set('title_for_layout',SITENAME.' Conformance List');
		$this->checkSatffSession();
		 $user_id=$this->Session->read('Customer.id');
		
		$confrel = $this->ConformanceRelation->find('list',array('conditions'=>array('ConformanceRelation.emp_id'=>$user_id),'fields'=>array('conformance_id')));
		
		
		$this->Conformance->bindModel(
		array('belongsTo' => array('NappUser1' => array(
			'className' => 'NappUser',			 
			'foreignKey' => 'compaint_by',				
			'conditions' => array(),
			'fields' => array('name','lname','id'),
			'order' => array(),
		))));

		
		$this->Conformance->bindModel(
		array('belongsTo' => array('Department' => array(
			'className' => 'Department',			 
			'foreignKey' => 'dept_id',				
			'conditions' => array(),
			'fields' => '',
			'order' => array(),
		))));
		
		$this->Conformance->bindModel(
		array('hasMany' => array('ConformanceRelation' => array(
			'className' => 'ConformanceRelation',			 
			'foreignKey' => 'conformance_id',				
			'conditions' => array(),
			'fields' => array(),
			'order' => array(),
		))));
		$this->ConformanceRelation->bindModel(
		array('belongsTo' => array('NappUser' => array(
			'className' => 'NappUser',			 
			'foreignKey' => 'emp_id',				
			'conditions' => array(),
			'fields' => array('name','lname','id'),
			'order' => array(),
		))));
		$this->Conformance->bindModel(
		array('belongsTo' => array('User' => array(
			'className' => 'User',			 
			'foreignKey' => 'admin_id',				
			'conditions' => array(),
			'fields' => array('name'),
			'order' => array(),
		))));
		$this->Conformance->bindModel(
		array('belongsTo' => array('Complaint' => array(
			'className' => 'Complaint',			 
			'foreignKey' => 'complaintid',				
			'conditions' => array('complaintid'),
			'fields' => '',
			'order' => array(),
		))));
		$this->Conformance->bindModel(
		array('hasMany' => array('TaskDocument'=>array(
			'className' => 'TaskDocument',			 
			'foreignKey' => 'con_id',				
			'conditions' => array(),
			'fields' => array(),
			'order' => array(),
		))));
		
		$this->Conformance->recursive = 3;
		#$this->paginate = array('conditions'=>array('OR'=>array('Conformance.compaint_by'=>$user_id,'Conformance.compaint_to'=>$user_id)),'page' => 1, 'limit' => 25,'order'=>array('Conformance.id DESC'));
		$this->paginate = array('conditions'=>array('Conformance.id'=>$confrel),'page' => 1, 'limit' => 25,'order'=>array('Conformance.id'=>'DESC'));
		$ConformanceArr = $this->Paginator->paginate('Conformance');
		$this->set('ConformanceArr',$ConformanceArr);
		$this->set('user_id',$user_id);	
		
		$UserArr = $this->User->find('all',array('conditions'=>array('User.is_investigate'=>1),'fields'=>array('name')));
		$this->set('UserArr',$UserArr);	
			
		
	}
	
	function detail($nc_number=null,$type=null){
		
		$this->layout='staff_inner_layout';
		$this->set('title_for_layout',SITENAME.' Conformance Detail');
		$this->checkSatffSession();
		 $user_id=$this->Session->read('Customer.id');
		
		$confrel = $this->ConformanceRelation->find('list',array('conditions'=>array('ConformanceRelation.emp_id'=>$user_id),'fields'=>array('conformance_id')));	
		
		$this->Conformance->bindModel(
		array('belongsTo' => array('NappUser1' => array(
			'className' => 'NappUser',			 
			'foreignKey' => 'compaint_by',				
			'conditions' => array(),
			'fields' => array('name','lname','id'),
			'order' => array(),
		))));

		
		$this->Conformance->bindModel(
		array('belongsTo' => array('Department' => array(
			'className' => 'Department',			 
			'foreignKey' => 'dept_id',				
			'conditions' => array(),
			'fields' => '',
			'order' => array(),
		))));
		
		$this->Conformance->bindModel(
		array('hasMany' => array('ConformanceRelation' => array(
			'className' => 'ConformanceRelation',			 
			'foreignKey' => 'conformance_id',				
			'conditions' => array(),
			'fields' => array(),
			'order' => array(),
		))));
		$this->ConformanceRelation->bindModel(
		array('belongsTo' => array('NappUser' => array(
			'className' => 'NappUser',			 
			'foreignKey' => 'emp_id',				
			'conditions' => array(),
			'fields' => array('name','lname','id'),
			'order' => array(),
		))));
		$this->Conformance->bindModel(
		array('belongsTo' => array('User' => array(
			'className' => 'User',			 
			'foreignKey' => 'admin_id',				
			'conditions' => array(),
			'fields' => array('name'),
			'order' => array(),
		))));
		$this->Conformance->bindModel(
		array('belongsTo' => array('Complaint' => array(
			'className' => 'Complaint',			 
			'foreignKey' => 'complaintid',				
			'conditions' => array('complaintid'),
			'fields' => '',
			'order' => array(),
		))));
		$this->Conformance->bindModel(
		array('hasMany' => array('TaskDocument'=>array(
			'className' => 'TaskDocument',			 
			'foreignKey' => 'con_id',				
			'conditions' => array(),
			'fields' => array(),
			'order' => array(),
		))));
		$nc_number = base64_decode($nc_number);
		$this->Conformance->recursive = 3;
		$ConformanceArr = $this->Conformance->find('first',array('conditions'=>array('Conformance.nc_number'=>$nc_number)));
		
		$this->set('ComplaintArr',$ConformanceArr);
		$this->set('type',$type);
		$this->set('user_id',$user_id);	
		
		$UserArr = $this->User->find('all',array('conditions'=>array('User.is_investigate'=>1),'fields'=>array('name')));
		$this->set('UserArr',$UserArr);		
		
	}

	function admin_detail($nc_number=null,$type=null){
		
		$this->layout='admin_layout';
		$this->set('title_for_layout',SITENAME.' Conformance Detail');
		$this->checkAdminSession();
		
		$this->Conformance->bindModel(
		array('belongsTo' => array('NappUser1' => array(
			'className' => 'NappUser',			 
			'foreignKey' => 'compaint_by',				
			'conditions' => array(),
			'fields' => array('name','lname','id'),
			'order' => array(),
		))));

		
		$this->Conformance->bindModel(
		array('belongsTo' => array('Department' => array(
			'className' => 'Department',			 
			'foreignKey' => 'dept_id',				
			'conditions' => array(),
			'fields' => '',
			'order' => array(),
		))));
		
		$this->Conformance->bindModel(
		array('hasMany' => array('ConformanceRelation' => array(
			'className' => 'ConformanceRelation',			 
			'foreignKey' => 'conformance_id',				
			'conditions' => array(),
			'fields' => array(),
			'order' => array(),
		))));
		$this->ConformanceRelation->bindModel(
		array('belongsTo' => array('NappUser' => array(
			'className' => 'NappUser',			 
			'foreignKey' => 'emp_id',				
			'conditions' => array(),
			'fields' => array('name','lname','id'),
			'order' => array(),
		))));
		$this->Conformance->bindModel(
		array('belongsTo' => array('User' => array(
			'className' => 'User',			 
			'foreignKey' => 'admin_id',				
			'conditions' => array(),
			'fields' => array('name'),
			'order' => array(),
		))));
		$this->Conformance->bindModel(
		array('belongsTo' => array('Complaint' => array(
			'className' => 'Complaint',			 
			'foreignKey' => 'complaintid',				
			'conditions' => array('complaintid'),
			'fields' => '',
			'order' => array(),
		))));
		$this->Conformance->bindModel(
		array('hasMany' => array('TaskDocument'=>array(
			'className' => 'TaskDocument',			 
			'foreignKey' => 'con_id',				
			'conditions' => array(),
			'fields' => array(),
			'order' => array(),
		))));
		$nc_number = base64_decode($nc_number);
		$this->Conformance->recursive = 3;
		$ConformanceArr = $this->Conformance->find('first',array('conditions'=>array('Conformance.nc_number'=>$nc_number)));
		/* echo '<pre>';
		print_r($ConformanceArr);
		die(); */
		$this->set('ComplaintArr',$ConformanceArr);
		$this->set('type',$type);
		
		$UserArr = $this->User->find('all',array('conditions'=>array('User.is_investigate'=>1),'fields'=>array('name')));
		$this->set('UserArr',$UserArr);	
	
	}
	function admin_replyto($conformance_id=null){
		
		$this->layout='admin_layout';
		$this->set('title_for_layout',SITENAME.' Conformance Reply');
		$this->checkAdminSession();
		$admin_id = $this->Session->read('User.id');
					
		$ConformanceArr = $this->Conformance->find('first',array('conditions'=>array('Conformance.id'=>base64_decode($conformance_id))));					
		if(!empty($this->requestData())){		
			
			
			$compaint_by = $ConformanceArr['Conformance']['compaint_by'];			
			$NappUserArr = $this->NappUser->find('first',array('conditions'=>array('NappUser.id'=>$compaint_by)));			
			$to_email = $NappUserArr['NappUser']['email'];
			#$to_email = 'web@xoroglobal.com';
			$to_name  = $NappUserArr['NappUser']['name'];	
			$subject = SITENAME." :: Non Conformance ".$ConformanceArr['Conformance']['nc_number']." By ".$this->Session->read('User.name');				
			$template_name = 'replyconformance';	
			$non_conforance = $ConformanceArr['Conformance']['non_conforance'];			
			$variables = array('name'=>$this->Session->read('User.name'),'to_name'=>$to_name,'title'=>$non_conforance,'nc_number'=>$ConformanceArr['Conformance']['nc_number']);		
			try{
				if($this->requestData()['Conformance']['status'] > 1){
					//$this->sendemail($to_email,$subject,$template_name,$variables);
				}
			}catch (Exception $e){
				
			}
			
			$conformance_id = $ConformanceArr['Conformance']['id'];			
			$updaterelation['Conformance']['id'] =  $conformance_id;	
			$updaterelation['Conformance']['admin_id'] =  $admin_id;	
			$updaterelation['Conformance']['short_term'] =  $this->requestData()['Conformance']['short_term'];	
			$updaterelation['Conformance']['follow_up'] =  $this->requestData()['Conformance']['follow_up'];	
			$updaterelation['Conformance']['corrective_action_successfull'] =  $this->requestData()['Conformance']['corrective_action_successfull'];	
			$updaterelation['Conformance']['support_document'] =  $this->requestData()['Conformance']['support_document'];	
			$updaterelation['Conformance']['ncr_closed_date'] =  $this->requestData()['Conformance']['ncr_closed_date'];	
			$updaterelation['Conformance']['status'] =  $this->requestData()['Conformance']['status'];	
			if($this->requestData()['Conformance']['status'] == 3){
				$updaterelation['Conformance']['complete_date'] = date('Y-m-d H:i:s');
			}				
			$this->Conformance->save($updaterelation);	
			
			$this->Session->setFlash('Replied successfully','default',array('class' => 'alert alert-success'));
			$this->redirect('conformancelist');
		}	
		$this->set('conformanceArr',$ConformanceArr);
		
		
	}	
	function admin_conformancelist(){
		
		$this->layout='admin_layout';
		$this->set('title_for_layout',SITENAME.' Conformance List');
		$this->checkAdminSession();
		$user_id=$this->Session->read('Customer.id');
	
		$this->Conformance->bindModel(
		array('belongsTo' => array('NappUser1' => array(
			'className' => 'NappUser',			 
			'foreignKey' => 'compaint_by',				
			'conditions' => array(),
			'fields' => array('name','lname','id'),
			'order' => array(),
		))));
		
		$this->Conformance->bindModel(
		array('belongsTo' => array('Department' => array(
			'className' => 'Department',			 
			'foreignKey' => 'dept_id',				
			'conditions' => array(),
			'fields' => '',
			'order' => array(),
		))));
		
		$this->Conformance->bindModel(
		array('hasMany' => array('ConformanceRelation' => array(
			'className' => 'ConformanceRelation',			 
			'foreignKey' => 'conformance_id',				
			'conditions' => array(),
			'fields' => array(),
			'order' => array(),
		))));
		$this->ConformanceRelation->bindModel(
		array('belongsTo' => array('NappUser' => array(
			'className' => 'NappUser',			 
			'foreignKey' => 'emp_id',				
			'conditions' => array(),
			'fields' => array('name','lname','id','dept_id'),
			'order' => array(),
		))));
		$this->NappUser->bindModel(
		array('belongsTo' => array('Department' => array(
			'className' => 'Department',			 
			'foreignKey' => 'dept_id',				
			'conditions' => array(),
			'fields' => array('department_title'),
			'order' => array(),
		))));
		
		$this->Conformance->bindModel(
		array('belongsTo' => array('User' => array(
			'className' => 'User',			 
			'foreignKey' => 'admin_id',				
			'conditions' => array(),
			'fields' => array('name'),
			'order' => array(),
		))));
		$this->Conformance->bindModel(
		array('hasMany' => array('TaskDocument'=>array(
			'className' => 'TaskDocument',			 
			'foreignKey' => 'con_id',				
			'conditions' => array(),
			'fields' => array(),
			'order' => array(),
		))));
		$nc_number = '';		
		$this->Conformance->recursive = 3;
		#$this->paginate = array('conditions'=>array('OR'=>array('Conformance.compaint_by'=>$user_id,'Conformance.compaint_to'=>$user_id)),'page' => 1, 'limit' => 25,'order'=>array('Conformance.id DESC'));
		if(!empty($this->requestData()['nc_number'])){
			$nc_number = $this->requestData()['nc_number'];	
			$this->paginate = array('conditions'=>array('Conformance.nc_number'=>$nc_number),'page' => 1, 'limit' => 25,'order'=>array('Conformance.id'=>'DESC'));
		}else{
			$this->paginate = array('conditions'=>array(),'page' => 1, 'limit' => 25,'order'=>array('Conformance.id'=>'DESC'));
		}	
		
		$ConformanceArr = $this->Paginator->paginate('Conformance');
		
		
		// echo '<pre>';
		// print_r($ConformanceArr);
		// die();
		
		$this->set('ConformanceArr',$ConformanceArr);
		$this->set('user_id',$user_id);	
		$this->set('nc_number',$nc_number);	
				
		$UserArr = $this->User->find('all',array('conditions'=>array('User.is_investigate'=>1),'fields'=>array('name')));
		$this->set('UserArr',$UserArr);	
	}	
	function sendsms(){
		
		$this->autoRender = false;
		
		$confirgArr =$this->Config->find('first');
				
		$sid = TSID;	
		$token = TOKEN;	
		$to = '+61402963688';	
		$from = FROM_NUMBER;	
		$message = 'Test Message';	
		
		$url = SITEURL."twilio/index.php";						
		$fields = array(
			'sid' => urlencode($sid),
			'token' => urlencode($token),							
			'to' => urlencode($to),							
			'from' => urlencode($from),							
			'message' => urlencode($message),							
		);	
		$fields_string = '';
		//url-ify the data for the POST
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string, '&');
		//open connection
		$ch = curl_init();
		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		//execute post
		$result = curl_exec($ch);
		//close connection
		curl_close($ch);
			
		echo '<pre>';
		print_r($result);
		die();	
	}	
	
	function cronsnew(){
		$this->autoRender = false;
		
		$this->Conformance->bindModel(
		array('hasMany' => array('ConformanceRelation' => array(
			'className' => 'ConformanceRelation',			 
			'foreignKey' => 'conformance_id',				
			'conditions' => array(),
			'fields' => array(),
			'order' => array(),
		))));
		$this->ConformanceRelation->bindModel(
		array('belongsTo' => array('NappUser' => array(
			'className' => 'NappUser',			 
			'foreignKey' => 'emp_id',				
			'conditions' => array(),
			'fields' => array('name','lname','email','mobile_number','id','country'),
			'order' => array(),
		))));
		$this->Conformance->bindModel(
		array('belongsTo' => array('User' => array(
			'className' => 'User',			 
			'foreignKey' => 'admin_id',				
			'conditions' => array(),
			'fields' => array('name','email'),
			'order' => array(),
		))));
		$this->Conformance->recursive = 2;
		$ConformanceArr = $this->Conformance->find('all',array('conditions'=>array('Conformance.is_reminder'=>1,'Conformance.status <'=>3),'order'=>array('Conformance.id'=>'DESC')));
		// echo '<pre>';
		// print_r($ConformanceArr);
		// die();
	
		if(!empty($ConformanceArr)){
			foreach($ConformanceArr as $Conformances){
				$last_update = $Conformances['Conformance']['lastupdated'];
				
				$nc_number = $Conformances['Conformance']['nc_number'];
				$is_reminder = $Conformances['Conformance']['is_reminder'];
				$is_email = $Conformances['Conformance']['is_email'];
				$is_sms = $Conformances['Conformance']['is_sms'];
				$is_both = $Conformances['Conformance']['is_both'];
				$send_to = $Conformances['Conformance']['send_to'];
				$period = $Conformances['Conformance']['period'];
				if($is_reminder == 1){					
					if($is_both == 1){
						 if(!empty($Conformances['ConformanceRelation'])){
							foreach($Conformances['ConformanceRelation'] as $ConformancesArr){
								if($Conformances['Conformance']['compaint_by'] != $ConformancesArr['NappUser']['id']){
									$name = $ConformancesArr['NappUser']['name'].' '.$ConformancesArr['NappUser']['lname'];
									$email = $ConformancesArr['NappUser']['email'];
									$country = $ConformancesArr['NappUser']['country'];
									
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
									}
									if($Conformances['Conformance']['send_to'] == 1){
										// Office Work Hour
										if($hour >= 9 && $hour <= 17){
											$flag =1;	
										}else{
											$flag = 0;	
										}		
									}else{
										// 24 hour
										$flag =1;
									}
								
									if(($flag == 1) && ($s_flag == 1)){	
										$udpateArrs['Conformance']['lastupdated'] = date('Y-m-d H:i:s');
										$udpateArrs['Conformance']['id'] = $Conformances['Conformance']['id'];
										$this->Conformance->save($udpateArrs);
										
										$receivername = $ConformancesArr['NappUser']['name'].' '.$ConformancesArr['NappUser']['lname'];
										$email = $ConformancesArr['NappUser']['email'];
										if(!empty($email)){											
											$subject = SITENAME.' :: Any update on non conformance('.$nc_number.')';				
											$template_name = 'nc_reminder';										
											$variables = array('to_name'=>$receivername,'nc_number'=>$nc_number);				
											try{
												$this->sendemail($email ,$subject,$template_name,$variables);
											}catch (Exception $e){
												
											} 										
										}
										
										if(!empty($ConformancesArr['NappUser']['mobile_number'])){
											if($country  != 'india'){
												$phones = str_replace(' ','',$NappUser['NappUser']['mobile_number']);
												$phone = ltrim($phones,'+');
												
												$to = '+'.$phone;
												$sid = TSID;	
												$token = TOKEN;							
												$from = FROM_NUMBER;	
												$body = "Hi ".$receivername.' Any update on non conformance('.$nc_number.')';				
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
					if($is_email == 1){
						if(!empty($Conformances['ConformanceRelation'])){
							foreach($Conformances['ConformanceRelation'] as $ConformancesArr){
								if($Conformances['Conformance']['compaint_by'] != $ConformancesArr['NappUser']['id']){
									$name = $ConformancesArr['NappUser']['name'].' '.$ConformancesArr['NappUser']['lname'];
									$email = $ConformancesArr['NappUser']['email'];
									$country = $ConformancesArr['NappUser']['country'];
									
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
									}
									if($Conformances['Conformance']['send_to'] == 1){
										// Office Work Hour
										if($hour >= 9 && $hour <= 17){
											$flag =1;	
										}else{
											$flag = 0;	
										}		
									}else{
										// 24 hour
										$flag =1;
									}
									
									if(($flag == 1) && ($s_flag == 1)){	
									
							
										$udpateArrs['Conformance']['lastupdated'] = date('Y-m-d H:i:s');
										$udpateArrs['Conformance']['id'] = $Conformances['Conformance']['id'];
										$this->Conformance->save($udpateArrs);										
										$receivername = $ConformancesArr['NappUser']['name'].' '.$ConformancesArr['NappUser']['lname'];
										$email = $ConformancesArr['NappUser']['email'];
										
										if(!empty($email)){			
											
											$subject = SITENAME.' :: Any update on non conformance('.$nc_number.')';				
											$template_name = 'nc_reminder';										
											$variables = array('to_name'=>$receivername,'nc_number'=>$nc_number);				
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
					}
					if($is_sms == 1){
						if(!empty($Conformances['ConformanceRelation'])){
							foreach($Conformances['ConformanceRelation'] as $ConformancesArr){
								if($Conformances['Conformance']['compaint_by'] != $ConformancesArr['NappUser']['id']){
									$name = $ConformancesArr['NappUser']['name'].' '.$ConformancesArr['NappUser']['lname'];
									$email = $ConformancesArr['NappUser']['email'];
									$country = $ConformancesArr['NappUser']['country'];
									
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
									}
									if($Conformances['Conformance']['send_to'] == 1){
										// Office Work Hour
										if($hour >= 9 && $hour <= 17){
											$flag =1;	
										}else{
											$flag = 0;	
										}		
									}else{
										// 24 hour
										$flag =1;
									}
											
									if(($flag == 1) && ($s_flag == 1)){
											if($country  != 'india'){
												$udpateArrs['Conformance']['lastupdated'] = date('Y-m-d H:i:s');
												$udpateArrs['Conformance']['id'] = $Conformances['Conformance']['id'];
												$this->Conformance->save($udpateArrs);											
												$receivername = $ConformancesArr['NappUser']['name'].' '.$ConformancesArr['NappUser']['lname'];																				
												if(!empty($ConformancesArr['NappUser']['mobile_number'])){
													$phones = str_replace(' ','',$NappUser['NappUser']['mobile_number']);
													$phone = ltrim($phones,'+');
													
													$to = '+'.$phone;
													$sid = TSID;	
													$token = TOKEN;							
													$from = FROM_NUMBER;	
													$body = "Hi ".$receivername.' Any update on non conformance('.$nc_number.')';				
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
		
	}
	function admin_reminder($conformance_id=null,$emp_id=null){
		$this->layout='admin_layout';
		$this->set('title_for_layout',SITENAME.' Conformance Edit');
		$this->checkAdminSession();
		$user_id=$this->Session->read('User.id');
		$ConformanceArr = $this->Conformance->find('first',array('conditions'=>array('Conformance.id'=>base64_decode($conformance_id))));	
		
		if(!empty($this->requestData())){
			
			isset($this->requestData()['is_reminder'])?	 $is_reminder = $this->requestData()['is_reminder'] : $is_reminder = 0;
			isset($this->requestData()['is_email'])?	 $is_email = $this->requestData()['is_email'] : $is_email = 0;
			isset($this->requestData()['is_sms'])?	 $is_sms = $this->requestData()['is_sms'] : $is_sms = 0;
			isset($this->requestData()['is_both'])?	 $is_both = $this->requestData()['is_both'] : $is_both = 0;
			isset($this->requestData()['send_to'])?	 $send_to = $this->requestData()['send_to'] : $send_to = 0;
			isset($this->requestData()['period'])?	 $period = $this->requestData()['period'] : $period = '';
			
			$update['Conformance']['id'] = $ConformanceArr['Conformance']['id'];
			$update['Conformance']['is_reminder'] = $is_reminder;
			$update['Conformance']['is_email'] = $is_email;
			$update['Conformance']['is_sms'] = $is_sms;
			$update['Conformance']['is_both'] = $is_both;
			$update['Conformance']['send_to'] = $send_to;
			$update['Conformance']['period'] = $period;
			$update['Conformance']['lastupdated'] = date('Y-m-d H:i:s');
			if($this->Conformance->save($update)){
				$this->Session->setFlash('Reminder set successfully','default',array('class' => 'alert alert-success'));
				$this->redirect('conformancelist');
			}
		}	
		
		$this->set('user_id',$user_id);
		$this->set('ConformanceArr',$ConformanceArr);
	}	
	
	function admin_edit($conformance_id=null,$emp_id=null){
		$this->layout='admin_layout';
		$this->set('title_for_layout',SITENAME.' Conformance Edit');
		$this->checkAdminSession();
	
		$user_id=$this->Session->read('User.id');
		
		$this->Conformance->bindModel(
		array('hasMany' => array('TaskDocument'=>array(
			'className' => 'TaskDocument',			 
			'foreignKey' => 'con_id',				
			'conditions' => array(),
			'fields' => array(),
			'order' => array(),
		))));
		
		$ConformanceArr = $this->Conformance->find('first',array('conditions'=>array('Conformance.id'=>base64_decode($conformance_id))));	
		
		if(!empty($this->requestData())){
			
			$conformance_ids = base64_decode($conformance_id);
			$path  = WWW_ROOT.'document/conformance';	
			
			if(!empty($_FILES['document']['name'][0])){
				$i=0;
				
				foreach($_FILES['document']['name'] as $name){					
					$ext = pathinfo($name, PATHINFO_EXTENSION);
					$filename =  time().'_'.$name;
					$tempname = $_FILES['document']['tmp_name'][$i];						
					move_uploaded_file($tempname,$path.'/'.$filename);
					
					$TaskDocument['TaskDocument']['id'] = '';
					$TaskDocument['TaskDocument']['title'] =  '';
					$TaskDocument['TaskDocument']['admin_id'] =  $user_id;
					$TaskDocument['TaskDocument']['con_id'] =  $conformance_ids;	
					$TaskDocument['TaskDocument']['document'] =  'conformance';
					$TaskDocument['TaskDocument']['filename'] =  $filename;
					$TaskDocument['TaskDocument']['ext'] =  $ext;
					$TaskDocument['TaskDocument']['created'] =  date('Y-m-d H:i:s');
					$TaskDocument['TaskDocument']['is_upload'] =  1; 			
					$this->TaskDocument->save($TaskDocument);					
					$i++;					
				}
			}
			
			$updateConformance['Conformance']['non_conforance'] = $this->requestData()['Conformance']['non_conforance'];				
			#$updateConformance['Conformance']['non_conforance_raised'] = $this->requestData()['Conformance']['non_conforance_raised'];				
			$updateConformance['Conformance']['customer_name'] = $this->requestData()['Conformance']['customer_name'];				
			$updateConformance['Conformance']['client_detail'] = $this->requestData()['Conformance']['client_detail'];				
			$updateConformance['Conformance']['other_detail'] = $this->requestData()['Conformance']['other_detail'];				
			$updateConformance['Conformance']['status'] = 2;		
			$updateConformance['Conformance']['id'] = $conformance_ids;		
				
			$this->Conformance->save($updateConformance);		
		
			$this->Session->setFlash('updated successfully','default',array('class' => 'alert alert-success'));
			$this->redirect('conformancelist');
		}	
		$this->set('ConformanceArr',$ConformanceArr);	
		$this->set('user_id',$user_id);
	
	}
	function edit($conformance_id=null,$emp_id=null){
		$this->layout='staff_inner_layout';
		$this->set('title_for_layout',SITENAME.' Conformance Reply');
		$this->checkSatffSession();
	
		$user_id=$this->Session->read('Customer.id');
		$dept_id=$this->Session->read('Customer.dept_id');
		
		$Department = $this->Department->find('first',array('conditions'=>array('Department.id'=>$dept_id)));
		
		$name = $this->Session->read('Customer.name').' '.$this->Session->read('Customer.lname').' ('.$Department['Department']['department_title'].')';
				
		$this->Conformance->bindModel(
		array('hasMany' => array('TaskDocument'=>array(
			'className' => 'TaskDocument',			 
			'foreignKey' => 'con_id',				
			'conditions' => array(),
			'fields' => array(),
			'order' => array(),
		))));
		
		$ConformanceArr = $this->Conformance->find('first',array('conditions'=>array('Conformance.id'=>base64_decode($conformance_id))));	
		
		if(!empty($this->requestData())){
			
			$conformance_ids = base64_decode($conformance_id);
			$path  = WWW_ROOT.'document/conformance';	
			
			if(!empty($_FILES['document']['name'][0])){
				
				$i=0;
				foreach($_FILES['document']['name'] as $name){					
					$ext = pathinfo($name, PATHINFO_EXTENSION);
					$filename =  time().'_'.$name;
					$tempname = $_FILES['document']['tmp_name'][$i];						
					move_uploaded_file($tempname,$path.'/'.$filename);
					
					$TaskDocument['TaskDocument']['id'] = '';
					$TaskDocument['TaskDocument']['title'] =  '';
					$TaskDocument['TaskDocument']['employe_id'] =  $user_id;
					$TaskDocument['TaskDocument']['con_id'] =  $conformance_ids;	
					$TaskDocument['TaskDocument']['document'] =  'conformance';
					$TaskDocument['TaskDocument']['filename'] =  $filename;
					$TaskDocument['TaskDocument']['ext'] =  $ext;
					$TaskDocument['TaskDocument']['created'] =  date('Y-m-d H:i:s');
					$TaskDocument['TaskDocument']['is_upload'] =  1; 			
					$this->TaskDocument->save($TaskDocument);					
					$i++;					
				}
			}
			
			/* // send email to intiative
			$compaint_by = $ConformanceArr['Conformance']['compaint_by'];			
			$NappUserArr = $this->NappUser->find('first',array('conditions'=>array('NappUser.id'=>$compaint_by)));			
			$to_email = $NappUserArr['NappUser']['email'];
			//$to_email = 'web@xoroglobal.com';
			$to_name  = $NappUserArr['NappUser']['name'];	
			$subject = SITENAME." :: Non Conformance ".$ConformanceArr['Conformance']['nc_number']." By ".$this->Session->read('Customer.name');				
			$template_name = 'replyconformance';	
			$non_conforance = $ConformanceArr['Conformance']['non_conforance'];			
			$variables = array('name'=>$this->Session->read('Customer.name'),'to_name'=>$to_name,'title'=>$non_conforance,'nc_number'=>$ConformanceArr['Conformance']['nc_number']);		
			try{
				$this->sendemail($to_email,$subject,$template_name,$variables);
			}catch (Exception $e){
				
			}	
			
			$admin_email = 'kal@durotechindustries.com.au';
			#$admin_email = 'web@xoroglobal.com';
			$admin_subject = SITENAME." :: Non Conformance ".$ConformanceArr['Conformance']['nc_number']." By ".$this->Session->read('Customer.name');

			$autologinurl = SITEURL.'staffs/autologin/'.base64_encode($ConformanceArr['Conformance']['id']);	
			$admin_template_name = 'autoreplyconformance';	
			$non_conforance = $ConformanceArr['Conformance']['non_conforance'];			
			$admin_variables = array('autologinurl'=>$autologinurl,'name'=>$this->Session->read('Customer.name'),'to_name'=>'Kal','title'=>$non_conforance,'nc_number'=>$ConformanceArr['Conformance']['nc_number']);		
			try{
				$this->sendemail($admin_email,$admin_subject,$admin_template_name,$admin_variables);
			}catch (Exception $e){
				
			} */
			
			$updateConformance['Conformance']['non_conforance'] = $this->requestData()['Conformance']['non_conforance'];$updateConformance['Conformance']['customer_name'] = $this->requestData()['Conformance']['customer_name'];				
			#$updateConformance['Conformance']['non_conforance_raised'] = $this->requestData()['Conformance']['non_conforance_raised'];				
			$updateConformance['Conformance']['client_detail'] = $this->requestData()['Conformance']['client_detail'];				
			$updateConformance['Conformance']['other_detail'] = $this->requestData()['Conformance']['other_detail'];				
			$updateConformance['Conformance']['status'] = 2;		
			$updateConformance['Conformance']['id'] = $conformance_ids;		
				
			$this->Conformance->save($updateConformance);			
		
			$this->Session->setFlash('updated successfully','default',array('class' => 'alert alert-success'));
			$this->redirect('conformancelist');
		}	
		$this->set('ConformanceArr',$ConformanceArr);
		
		
		$this->set('user_id',$user_id);
	
	}
	
}
