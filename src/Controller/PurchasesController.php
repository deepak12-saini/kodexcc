<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Concerns\PurchaseLegacyTrait;
use Cake\Event\EventInterface;

class PurchasesController extends AppController
{
    use PurchaseLegacyTrait;

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
	
	public function add(): void {
		
		$this->viewBuilder()->setLayout('staff_inner_layout');
		$this->set('title_for_layout',SITENAME.' Add Purchase Request');		
		$this->checkSatffSession(); 
		$userid = $this->Session->read('Customer.id');		
		$name = $this->Session->read('Customer.name').' '.$this->Session->read('Customer.lname');		
		$sendername = ucfirst($name);	
		
		$data = $this->requestData();
		if(!empty($data)){
									
			$item_details = $data['Purchase']['item_details'];
			$unique_id = 1000;	
			$PurchaseArr = 	$this->Purchase->find('first',array('order'=>array('Purchase.id'=>'DESC')));
			if(!empty($PurchaseArr)){
				$unique_id = 1000 + $PurchaseArr['Purchase']['id'];
			}	
			$unique_id = 'Rev-'.$unique_id;		
			$data['Purchase']['unique_id'] = $unique_id;
			$data['Purchase']['prepared_by'] = $userid;
			$data['Purchase']['created'] = date('Y-m-d H:i:s');
			
			if($this->Purchase->save($data)){
				$purchase_id = $this->Purchase->id;
				if(!empty($data['item_name'])){
					$i=0;				
					foreach($data['item_name'] as $item_name){
						
						$PurchaseRequirement['PurchaseRequirement']['id'] = ''; 
						$PurchaseRequirement['PurchaseRequirement']['purchase_id'] = $purchase_id; 
						$PurchaseRequirement['PurchaseRequirement']['item_name'] = $item_name; 
						$PurchaseRequirement['PurchaseRequirement']['comments'] = $data['comments'][$i]; 
						$PurchaseRequirement['PurchaseRequirement']['quantity'] = $data['quantity'][$i]; 
						$PurchaseRequirement['PurchaseRequirement']['description_item'] = $data['description_item'][$i]; 
						$this->PurchaseRequirement->save($PurchaseRequirement);									
						$i++;
					}	
				}									
				$permitted_by = $data['Purchase']['permitted_by'];
				$employeArr = $this->NappUser->find('first',array('conditions'=>array('NappUser.id'=>$permitted_by)));	
				if(!empty($employeArr)){
					$receivername = $employeArr['NappUser']['name'].' '.$employeArr['NappUser']['lname']; 
					$receiveremail = $employeArr['NappUser']['email'];			
					$url = SITEURL.'purchases/autologin/'.base64_encode($employeArr['NappUser']['id']);
					$subject = SITENAME.':- '.$sendername.' has made request ('.$unique_id.')';
					$template_name = 'sendrequest';					
					$variables = array('unique_id'=>$unique_id,'sendername'=>$sendername,'receivername'=>$receivername,'url'=>$url,'item_details'=>$item_details);
					
					try{
						$this->sendemail($receiveremail,$subject,$template_name,$variables);
					}catch (\Exception $e){
						
					}
				}
				
				$adminurl = SITEURL.'admin/purchases/autologin';
				$to = 'rsb@kodexglobalcc.com';				
				$adminvariables = array('unique_id'=>$unique_id,'sendername'=>$sendername,'receivername'=>'Admin','url'=>$adminurl,'item_details'=>$item_details);		
				try{
					$this->sendemail($to,$subject,$template_name,$adminvariables);
					
				}catch (\Exception $e){
					
				}
				$to = 'web@xoroglobal.com';				
				$adminvariables = array('unique_id'=>$unique_id,'sendername'=>$sendername,'receivername'=>'Admin','url'=>$adminurl,'item_details'=>$item_details);		
				try{
					$this->sendemail($to,$subject,$template_name,$adminvariables);
					
				}catch (\Exception $e){
					
				}
				
				$to = 'alan@xoroglobal.com';				
				$adminvariables = array('unique_id'=>$unique_id,'sendername'=>$sendername,'receivername'=>'Admin','url'=>$adminurl,'item_details'=>$item_details);		
				try{
					$this->sendemail($to,$subject,$template_name,$adminvariables);
					
				}catch (\Exception $e){
					
				}
				$this->Session->setFlash('Request has been created successfully.','default',array('class' => 'alert alert-success'));
				$this->redirect(['action' => 'index']);	
			}	
		}
		// $this->UserPermission->bindModel(
				// array('belongsTo' => array('NappUser' => array(
			// 'className' => 'NappUser',    
			// 'foreignKey' => 'user_id',    
			// 'conditions' => array(),
			// 'fields' => array('name','lname','email'),
			// 'order' => array(),
		// ))));
				
		$user_id = $this->UserPermission->find('list',array('conditions'=>array('UserPermission.permssion_id'=>30),'fields'=>array('user_id')));
	
		$employeArr = $this->NappUser->find('all',array('conditions'=>array('NappUser.is_staff_id'=>1,'id'=>$user_id)));		
		$this->set('employeArr',$employeArr);	
				
	}
	
	public function delete(?string $id = null): void {
		
		$this->autoRender = false;
		$this->checkSatffSession(); 
		$this->Purchase->id = $id;
		if($this->Purchase->delete()){
			$this->Session->setFlash('Request has been deleted successfully.','default',array('class' => 'alert alert-success'));
			$this->redirect(['action' => 'index']);	
		}	
	}	
	
	public function autologin(?string $id = null): void {
		$this->autoRender = false;
		
		if(!empty($id)){
			$uid = base64_decode($id);
			$userArr = $this->NappUser->find('first',array('conditions'=>array('NappUser.id'=>$uid,'NappUser.is_staff_id'=>1)));
			if(!empty($userArr)){
				if($userArr['NappUser']['is_active'] == 0){
					$this->Session->setFlash('You account is not active','default',array('class' => 'alert alert-danger'));
					$this->redirect('/login');	
				}else{								
					$this->Session->write('Customer', $userArr['NappUser']);
					$this->Session->write('is_customer', 1);			
					$this->redirect('/purchases');
				
				}
			}else{
				$this->redirect('/login');	
			}
		}else{
			$this->redirect('/login');	
		}	
	}	
	
	public function received(?string $id = null): void {
		
		$this->checkSatffSession(); 
		$this->autoRender =false;
		
		$userid = $this->Session->read('Customer.id');		
		$name = $this->Session->read('Customer.name').' '.$this->Session->read('Customer.lname');		
		$sendername = ucfirst($name);	
		
		$this->Purchase->bindModel(
		array('hasMany' => array('PurchaseRequirement' => array(
			'className' => 'PurchaseRequirement',			 
			'foreignKey' => 'purchase_id',				
			'conditions' => array(),
			'fields' => array(),
			'order' => array(),
		))));
		$this->Purchase->bindModel(
		array('belongsTo' => array('NappUser_1' => array(
			'className' => 'NappUser',			 
			'foreignKey' => 'prepared_by',				
			'conditions' => array(),
			'fields' => array('id','name','lname','email'),
			'order' => array(),
		))));
		$this->Purchase->bindModel(
		array('belongsTo' => array('NappUser' => array(
			'className' => 'NappUser',			 
			'foreignKey' => 'permitted_by',				
			'conditions' => array(),
			'fields' => array('id','name','lname','email'),
			'order' => array(),
		))));
		$this->Purchase->bindModel(
		array('belongsTo' => array('NappUser_2' => array(
			'className' => 'NappUser',			 
			'foreignKey' => 'authorized_by',				
			'conditions' => array(),
			'fields' => array('id','name','lname','email'),
			'order' => array(),
		))));
		
		$PurchaseArr = $this->purchaseFindFirstLegacy($id);
		if(!empty($PurchaseArr)){
			
			$update['Purchase']['id'] = $id;
			$update['Purchase']['status'] = 3;
			$update['Purchase']['name_of_receiver'] = $sendername;
			$this->Purchase->save($update);
			
			$authorized_by = $PurchaseArr['Purchase']['authorized_by'];
			$unique_id = $PurchaseArr['Purchase']['unique_id'];
			$item_details = $PurchaseArr['Purchase']['item_details'];
			// send email to completion this task
			
			
					
				
					$PurchaseArr = $this->purchaseFindFirstLegacy($id);
					$unique_id = $PurchaseArr['Purchase']['unique_id'];
					$item_details = $PurchaseArr['Purchase']['item_details'];					
					$subject = SITENAME.' :- Purchase Request('.$unique_id.') has been closed by '.$sendername;	
					
					$this->UserPermission->bindModel(
						array('belongsTo' => array('NappUser' => array(
						'className' => 'NappUser',    
						'foreignKey' => 'user_id',    
						'conditions' => array(),
						'fields' => array('name','lname','email'),
						'order' => array(),
					))));
					$StockReturnkArr = $this->UserPermission->find('all',array('conditions'=>array('UserPermission.permssion_id'=>30)));
					
					if(!empty($StockReturnkArr)){
						foreach($StockReturnkArr as $StockReturnkArrs){	
							if(!empty($StockReturnkArrs['NappUser']['id'])){
								$id_2 = $StockReturnkArrs['NappUser']['id'];										
								$email_2 = $StockReturnkArrs['NappUser']['email'];						
								$name_2 = $StockReturnkArrs['NappUser']['name'].' '.$StockReturnkArrs['NappUser']['lname'];					
								$url = SITEURL.'purchases/autologin/'.base64_encode($id_2);
								$template_name = 'requestapproved';					
								$variables = array('unique_id'=>$unique_id,'sendername'=>$sendername,'receivername'=>$name_2,'url'=>$url,'item_details'=>$item_details);
								try{
									$this->sendemail($email_2,$subject,$template_name,$variables);
								}catch (\Exception $e){
									
								}						
							}						
						}
					}					
					$adminurl = SITEURL.'admin/purchases/autologin';
					$to = 'rsb@kodexglobalcc.com';				
					$adminvariables = array('unique_id'=>$unique_id,'sendername'=>$sendername,'receivername'=>'Admin','url'=>$adminurl,'item_details'=>$item_details);	
					$template_name = 'inprocessorder';		
					try{
						$this->sendemail($to,$subject,$template_name,$adminvariables);						
					}catch (\Exception $e){
						
					}
				$this->Session->setFlash('Received successfully.','default',array('class' => 'alert alert-success'));
				$this->redirect(['action' => 'index']);
		}else{
			 $this->redirect(['action' => 'index']);
		}	
		
	}
	
	public function process(?string $id = null): void {
		
		$this->viewBuilder()->setLayout('staff_inner_layout');
		$this->set('title_for_layout',SITENAME.' Add Purchase Request');		
		$this->checkSatffSession(); 
		$userid = $this->Session->read('Customer.id');		
		$name = $this->Session->read('Customer.name').' '.$this->Session->read('Customer.lname');		
		$sendername = ucfirst($name);	
		
		$this->Purchase->bindModel(
		array('hasMany' => array('PurchaseRequirement' => array(
			'className' => 'PurchaseRequirement',			 
			'foreignKey' => 'purchase_id',				
			'conditions' => array(),
			'fields' => array(),
			'order' => array(),
		))));
		$this->Purchase->bindModel(
		array('belongsTo' => array('NappUser_1' => array(
			'className' => 'NappUser',			 
			'foreignKey' => 'prepared_by',				
			'conditions' => array(),
			'fields' => array('id','name','lname','email'),
			'order' => array(),
		))));
		$this->Purchase->bindModel(
		array('belongsTo' => array('NappUser' => array(
			'className' => 'NappUser',			 
			'foreignKey' => 'permitted_by',				
			'conditions' => array(),
			'fields' => array('id','name','lname','email'),
			'order' => array(),
		))));
		$this->Purchase->bindModel(
		array('belongsTo' => array('NappUser_2' => array(
			'className' => 'NappUser',			 
			'foreignKey' => 'authorized_by',				
			'conditions' => array(),
			'fields' => array('id','name','lname','email'),
			'order' => array(),
		))));
		
		$PurchaseArr = $this->purchaseFindFirstLegacy($id);		
		$this->set('PurchaseArr',$PurchaseArr);
		$unique_id = $PurchaseArr['Purchase']['unique_id'];
		$item_details = $PurchaseArr['Purchase']['item_details'];
		$data = $this->requestData();
		if(!empty($data)){
			
			$status = $data['Purchase']['status'];
			$authorized_by = $data['Purchase']['authorized_by'];
			$final_result = $data['Purchase']['final_result'];	 
			$data['Purchase']['id'] = $id;
			if($this->Purchase->save($data)){
				if($status == 2){
					$this->UserPermission->bindModel(
						array('belongsTo' => array('NappUser' => array(
						'className' => 'NappUser',    
						'foreignKey' => 'user_id',    
						'conditions' => array(),
						'fields' => array('name','lname','email'),
						'order' => array(),
					))));
				
					$PurchaseArr = $this->purchaseFindFirstLegacy($id);
					$unique_id = $PurchaseArr['Purchase']['unique_id'];
					$item_details = $PurchaseArr['Purchase']['item_details'];					
					$subject = SITENAME.' :- Purchase Request('.$unique_id.') has been In-Process by '.$sendername;			
					
					$StockReturnkArr = $this->UserPermission->find('all',array('conditions'=>array('UserPermission.permssion_id'=>30)));
					
					if(!empty($StockReturnkArr)){
						foreach($StockReturnkArr as $StockReturnkArrs){	
							$id_2 = $StockReturnkArrs['NappUser']['id'];										
							$email_2 = $StockReturnkArrs['NappUser']['email'];						
							$name_2 = $StockReturnkArrs['NappUser']['name'].' '.$StockReturnkArrs['NappUser']['lname'];					
							$url = SITEURL.'purchases/autologin/'.base64_encode($id_2);
							$template_name = 'requestapproved';					
							$variables = array('unique_id'=>$unique_id,'sendername'=>$sendername,'receivername'=>$name_2,'url'=>$url,'item_details'=>$item_details);
							try{
								$this->sendemail($email_2,$subject,$template_name,$variables);
							}catch (\Exception $e){
								
							}						
						}
					}					
					$adminurl = SITEURL.'admin/purchases/autologin';
					$to = 'web@xoroglobal.com';				
					$adminvariables = array('unique_id'=>$unique_id,'sendername'=>$sendername,'receivername'=>'Admin','url'=>$adminurl,'item_details'=>$item_details);	
					$template_name = 'inprocessorder';		
					try{
						$this->sendemail($to,$subject,$template_name,$adminvariables);						
					}catch (\Exception $e){
						
					}
					$to = 'rsb@kodexglobalcc.com';				
					$adminvariables = array('unique_id'=>$unique_id,'sendername'=>$sendername,'receivername'=>'Admin','url'=>$adminurl,'item_details'=>$item_details);	
					$template_name = 'inprocessorder';		
					try{
						$this->sendemail($to,$subject,$template_name,$adminvariables);						
					}catch (\Exception $e){
						
					}
					
				}	
			}
			$this->Session->setFlash('Request updated successfully.','default',array('class' => 'alert alert-success'));
			$this->redirect(['action' => 'index']);	
		}
		
		$user_id = $this->UserPermission->find('list',array('conditions'=>array('UserPermission.permssion_id'=>30),'fields'=>array('user_id')));	
		$employeArr = $this->NappUser->find('all',array('conditions'=>array('NappUser.is_staff_id'=>1,'id'=>$user_id)));		
		$this->set('employeArr',$employeArr);
		
		$this->setRequestData($PurchaseArr);  	
		
	}	
	
	public function edit(?string $id = null): void {
		
		$this->viewBuilder()->setLayout('staff_inner_layout');
		$this->set('title_for_layout',SITENAME.' Add Purchase Request');		
		$this->checkSatffSession(); 
		$userid = $this->Session->read('Customer.id');		
		$name = $this->Session->read('Customer.name').' '.$this->Session->read('Customer.lname');		
		$sendername = ucfirst($name);	
		
		$data = $this->requestData();
		if(!empty($data)){
			
			$item_details = $data['Purchase']['item_details'];
		
			$data['Purchase']['prepared_by'] = $userid;
			$data['Purchase']['id'] = $id;
			$data['Purchase']['created'] = date('Y-m-d H:i:s');
			if($this->Purchase->save($data)){
				$this->PurchaseRequirement->query('delete from purchase_requirements where purchase_id = '.$id.' ');
				if(!empty($data['item_name'])){
					$i=0;				
					foreach($data['item_name'] as $item_name){
						
						$PurchaseRequirement['PurchaseRequirement']['id'] = ''; 
						$PurchaseRequirement['PurchaseRequirement']['purchase_id'] = $id; 
						$PurchaseRequirement['PurchaseRequirement']['item_name'] = $item_name; 
						$PurchaseRequirement['PurchaseRequirement']['comments'] = $data['comments'][$i]; 
						$PurchaseRequirement['PurchaseRequirement']['quantity'] = $data['quantity'][$i]; 
						$PurchaseRequirement['PurchaseRequirement']['description_item'] = $data['description_item'][$i]; 
						$this->PurchaseRequirement->save($PurchaseRequirement);									
						$i++;
					}	
				}	
					
				$this->Session->setFlash('Request has been created successfully.','default',array('class' => 'alert alert-success'));
				$this->redirect(['action' => 'index']);	
			}	
		}
		
		$user_id = $this->UserPermission->find('list',array('conditions'=>array('UserPermission.permssion_id'=>30),'fields'=>array('user_id')));
	
		$employeArr = $this->NappUser->find('all',array('conditions'=>array('NappUser.is_staff_id'=>1,'id'=>$user_id)));		
		$this->set('employeArr',$employeArr);	
		
		$this->Purchase->bindModel(
		array('hasMany' => array('PurchaseRequirement' => array(
			'className' => 'PurchaseRequirement',			 
			'foreignKey' => 'purchase_id',				
			'conditions' => array(),
			'fields' => array(),
			'order' => array(),
		))));
		
		$PurchaseArr = $this->purchaseFindFirstLegacy($id);
		// echo '<pre>';
		// print_r($PurchaseArr);
		// die();
		$this->set('PurchaseArr',$PurchaseArr);
		$this->setRequestData($PurchaseArr);	
	}	
	
	public function index(): void
	{
		$this->viewBuilder()->setLayout('staff_inner_layout');
		$this->set('title_for_layout', SITENAME . ' Purchases List');
		$this->checkSatffSession();

		$permList = $this->UserPermission->find('list', [
			'conditions' => ['UserPermission.permssion_id' => 30],
			'fields' => ['user_id'],
		])->toArray();
		$ids = array_values(array_unique(array_map('intval', $permList)));
		if ($ids === []) {
			$ids = [-1];
		}

		$contain = ['NappUser', 'NappUser1', 'NappUser2', 'PurchaseRequirements'];
		$query = $this->fetchTable('Purchase')->find()
			->contain($contain)
			->where(['Purchase.purchase_type' => 0, 'Purchase.permitted_by IN' => $ids])
			->orderBy(['Purchase.id' => 'DESC']);

		$this->paginate = [
			'limit' => 25,
			'order' => ['Purchase.id' => 'DESC'],
			'sortableFields' => [
				'Purchase.unique_id',
				'Purchase.item_details',
				'Purchase.requisitioner_name',
				'Purchase.date',
				'Purchase.prepared_by',
				'Purchase.permitted_by',
				'Purchase.authorized_by',
				'Purchase.name_of_receiver',
				'Purchase.status',
				'Purchase.created',
			],
		];
		$page = $this->paginate($query);
		$this->set('purchaseArr', $this->mapPurchasePageToLegacy($page));
		$this->set('purchasesPaginated', $page);
	}
	public function resource_process(?string $id = null): void {
		
		$this->viewBuilder()->setLayout('staff_inner_layout');
		$this->set('title_for_layout',SITENAME.' Approved / Descline Request');		
		$this->checkSatffSession(); 
		$userid = $this->Session->read('Customer.id');		
		$name = $this->Session->read('Customer.name').' '.$this->Session->read('Customer.lname');		
		$sendername = ucfirst($name);	
		
		$this->Purchase->bindModel(
		array('hasMany' => array('PurchaseRequirement' => array(
			'className' => 'PurchaseRequirement',			 
			'foreignKey' => 'purchase_id',				
			'conditions' => array(),
			'fields' => array(),
			'order' => array(),
		))));
		$this->Purchase->bindModel(
		array('belongsTo' => array('NappUser_1' => array(
			'className' => 'NappUser',			 
			'foreignKey' => 'prepared_by',				
			'conditions' => array(),
			'fields' => array('id','name','lname','email'),
			'order' => array(),
		))));
		$this->Purchase->bindModel(
		array('belongsTo' => array('NappUser' => array(
			'className' => 'NappUser',			 
			'foreignKey' => 'permitted_by',				
			'conditions' => array(),
			'fields' => array('id','name','lname','email'),
			'order' => array(),
		))));
		$this->Purchase->bindModel(
		array('belongsTo' => array('NappUser_2' => array(
			'className' => 'NappUser',			 
			'foreignKey' => 'authorized_by',				
			'conditions' => array(),
			'fields' => array('id','name','lname','email'),
			'order' => array(),
		))));
		
		$PurchaseArr = $this->purchaseFindFirstLegacy($id);		
		$this->set('PurchaseArr',$PurchaseArr);
		$unique_id = $PurchaseArr['Purchase']['unique_id'];
		$item_details = $PurchaseArr['Purchase']['item_details'];
		$data = $this->requestData();
		if(!empty($data)){
			if($data['Purchase']['status'] == 1){
				$data['Purchase']['purchase_type'] = 0;
			}	
			$data['Purchase']['id'] = $id;
			if($this->Purchase->save($data)){
				if($data['Purchase']['status'] == 0){
					$deleteid = '';
					if(!empty($data['status'])){
						foreach($data['status'] as $key=>$ids){
							$deleteid  .=  $key.',';					
							$PurchaseRequirement['PurchaseRequirement']['id'] = $key; 
							$PurchaseRequirement['PurchaseRequirement']['quantity'] = $data['quantity'][$key][0]; 
							$this->PurchaseRequirement->save($PurchaseRequirement);						
						}
						$deleteid = rtrim($deleteid,',');		
						if(!empty($deleteid)){	
							$this->PurchaseRequirement->query('delete from purchase_requirements where purchase_id = '.$id.' and id NOT IN ('.$deleteid.')');
						}
					}	
				}else{
					$deleteid = '';
					if(!empty($data['status'])){
						foreach($data['status'] as $key=>$ids){
							$PurchaseRequirementArr = $this->PurchaseRequirement->find('first',array('conditions'=>array('PurchaseRequirement.id'=>$key)));
														
							$deleteid  .=  $key.',';					
							$PurchaseRequirement['PurchaseRequirement']['id'] = $key; 
							$PurchaseRequirement['PurchaseRequirement']['item_name'] = $PurchaseRequirementArr['PurchaseRequirement']['resource_requirement']; 
							$PurchaseRequirement['PurchaseRequirement']['quantity'] = $data['quantity'][$key][0]; 
							$this->PurchaseRequirement->save($PurchaseRequirement);						
						}
						$deleteid = rtrim($deleteid,',');		
						if(!empty($deleteid)){	
							$this->PurchaseRequirement->query('delete from purchase_requirements where purchase_id = '.$id.' and id NOT IN ('.$deleteid.')');
						} 
					}
				}	
				// echo '<pre>';
				// print_r($data);
				// die();
			}

			$this->Session->setFlash('Request updated successfully.','default',array('class' => 'alert alert-success'));
			if($data['Purchase']['status'] == 1){
				$this->redirect(['action' => 'index']);	
			}else{
				$this->redirect(['action' => 'resource_requirement']);	
			}	
		}
		
		$this->setRequestData($PurchaseArr);  	
		
		$user_id = $this->UserPermission->find('list',array('conditions'=>array('UserPermission.permssion_id'=>30),'fields'=>array('user_id')));	
		$employeArr = $this->NappUser->find('all',array('conditions'=>array('NappUser.is_staff_id'=>1,'id'=>$user_id)));		
		$this->set('employeArr',$employeArr);
	}	
	
	public function resource_delete(?string $id = null): void {
		
		$this->autoRender = false;
		$this->checkSatffSession(); 
		$this->Purchase->id = $id;
		if($this->Purchase->delete()){
			$this->Session->setFlash('Request has been deleted successfully.','default',array('class' => 'alert alert-success'));
			$this->redirect(['action' => 'resource_requirement']);	
		}	
	}	
	
	public function resource_edit(?string $id = null): void {
		
		$this->viewBuilder()->setLayout('staff_inner_layout');
		$this->set('title_for_layout',SITENAME.' Add Purchase Request');		
		$this->checkSatffSession(); 
		$userid = $this->Session->read('Customer.id');		
		$name = $this->Session->read('Customer.name').' '.$this->Session->read('Customer.lname');		
		$sendername = ucfirst($name);	
		
		$data = $this->requestData();
		if(!empty($data)){
			
			$item_details = $data['Purchase']['item_details'];
		
			$data['Purchase']['prepared_by'] = $userid;
			$data['Purchase']['id'] = $id;
			$data['Purchase']['created'] = date('Y-m-d H:i:s');
			if($this->Purchase->save($data)){
				$this->PurchaseRequirement->query('delete from purchase_requirements where purchase_id = '.$id.' ');
				if(!empty($data['resource_requirement'])){
					$i=0;				
					foreach($data['resource_requirement'] as $resource_requirement){
						
						$PurchaseRequirement['PurchaseRequirement']['id'] = ''; 
						$PurchaseRequirement['PurchaseRequirement']['purchase_id'] = $id; 
						$PurchaseRequirement['PurchaseRequirement']['resource_requirement'] = $resource_requirement; 
						$PurchaseRequirement['PurchaseRequirement']['purpose_project'] = $data['purpose_project'][$i]; 
						$PurchaseRequirement['PurchaseRequirement']['quantity'] = $data['quantity'][$i]; 
						$PurchaseRequirement['PurchaseRequirement']['time'] = $data['time'][$i]; 
						$PurchaseRequirement['PurchaseRequirement']['budget'] = $data['budget'][$i]; 
						$PurchaseRequirement['PurchaseRequirement']['remark'] = $data['remark'][$i]; 
						// echo '<pre>';
						// print_r($PurchaseRequirement);
						// die();
						$this->PurchaseRequirement->save($PurchaseRequirement);									
						$i++;
					}	
				}	
					
				$this->Session->setFlash('Request has been created successfully.','default',array('class' => 'alert alert-success'));
				$this->redirect(['action' => 'resource_requirement']);	
			}	
		}
		
		$user_id = $this->UserPermission->find('list',array('conditions'=>array('UserPermission.permssion_id'=>30),'fields'=>array('user_id')));	
		$employeArr = $this->NappUser->find('all',array('conditions'=>array('NappUser.is_staff_id'=>1,'id'=>$user_id)));		
		$this->set('employeArr',$employeArr);
		
		$this->Purchase->bindModel(
		array('hasMany' => array('PurchaseRequirement' => array(
			'className' => 'PurchaseRequirement',			 
			'foreignKey' => 'purchase_id',				
			'conditions' => array(),
			'fields' => array(),
			'order' => array(),
		))));
		
		$PurchaseArr = $this->purchaseFindFirstLegacy($id);
		// echo '<pre>';
		// print_r($PurchaseArr);
		// die();
		$this->set('PurchaseArr',$PurchaseArr);
		$this->setRequestData($PurchaseArr);	
	}
	
	// resource requirement
	
	public function resource_add(): void {
		
		$this->viewBuilder()->setLayout('staff_inner_layout');
		$this->set('title_for_layout',SITENAME.' Add Purchase Request');		
		$this->checkSatffSession(); 
		$userid = $this->Session->read('Customer.id');		
		$name = $this->Session->read('Customer.name').' '.$this->Session->read('Customer.lname');		
		$sendername = ucfirst($name);	
		
		$data = $this->requestData();
		if(!empty($data)){
			
			
						
			$item_details = $data['Purchase']['item_details'];
			$unique_id = 1000;	
			$PurchaseArr = 	$this->Purchase->find('first',array('order'=>array('Purchase.id'=>'DESC')));
			if(!empty($PurchaseArr)){
				$unique_id = 1000 + $PurchaseArr['Purchase']['id'];
			}	
			$unique_id = 'Rev-'.$unique_id;		
			$data['Purchase']['unique_id'] = $unique_id;
			$data['Purchase']['prepared_by'] = $userid;
			$data['Purchase']['purchase_type'] = 1;
			$data['Purchase']['created'] = date('Y-m-d H:i:s');
		
			if($this->Purchase->save($data)){
				$purchase_id = $this->Purchase->id;
				if(!empty($data['resource_requirement'])){
					$i=0;				
					foreach($data['resource_requirement'] as $resource_requirement){
						
						$PurchaseRequirement['PurchaseRequirement']['id'] = ''; 
						$PurchaseRequirement['PurchaseRequirement']['purchase_id'] = $purchase_id; 
						$PurchaseRequirement['PurchaseRequirement']['resource_requirement'] = $resource_requirement; 
						$PurchaseRequirement['PurchaseRequirement']['purpose_project'] = $data['purpose_project'][$i]; 
						$PurchaseRequirement['PurchaseRequirement']['quantity'] = $data['quantity'][$i]; 
						$PurchaseRequirement['PurchaseRequirement']['time'] = $data['time'][$i]; 
						$PurchaseRequirement['PurchaseRequirement']['budget'] = $data['budget'][$i]; 
						$PurchaseRequirement['PurchaseRequirement']['remark'] = $data['remark'][$i]; 
						// echo '<pre>';
						// print_r($PurchaseRequirement);
						// die();
						$this->PurchaseRequirement->save($PurchaseRequirement);									
						$i++;
					}	
				}									
				$permitted_by = $data['Purchase']['permitted_by'];
				$employeArr = $this->NappUser->find('first',array('conditions'=>array('NappUser.id'=>$permitted_by)));	
				$receivername = $employeArr['NappUser']['name'].' '.$employeArr['NappUser']['lname']; 
				$receiveremail = $employeArr['NappUser']['email'];			
				$url = SITEURL.'purchases/autologin/'.base64_encode($employeArr['NappUser']['id']);
				$subject = SITENAME.':- '.$sendername.' has made request ('.$unique_id.') for resources';
				$template_name = 'sendrequest';					
				$variables = array('unique_id'=>$unique_id,'sendername'=>$sendername,'receivername'=>$receivername,'url'=>$url,'item_details'=>$item_details);
				try{
					$this->sendemail($receiveremail,$subject,$template_name,$variables);
				}catch (\Exception $e){
					
				}
				
				$this->Session->setFlash('Resource requirement request has been created successfully.','default',array('class' => 'alert alert-success'));
				$this->redirect(['action' => 'resource_requirement']);	
			}	
		}
		
		$user_id = $this->UserPermission->find('list',array('conditions'=>array('UserPermission.permssion_id'=>30),'fields'=>array('user_id')));	
		$employeArr = $this->NappUser->find('all',array('conditions'=>array('NappUser.is_staff_id'=>1,'id'=>$user_id)));		
		$this->set('employeArr',$employeArr);
				
	}
	public function resource_requirement(): void
	{
		$this->viewBuilder()->setLayout('staff_inner_layout');
		$this->set('title_for_layout', SITENAME . ' Resource Requirement List');
		$this->checkSatffSession();
		$userid = $this->Session->read('Customer.id');

		$contain = ['NappUser', 'NappUser1', 'NappUser2', 'PurchaseRequirements'];
		$query = $this->fetchTable('Purchase')->find()
			->contain($contain)
			->where([
				'Purchase.purchase_type' => 1,
				'OR' => [
					['Purchase.permitted_by' => $userid],
					['Purchase.prepared_by' => $userid],
					['Purchase.authorized_by' => $userid],
				],
			])
			->orderBy(['Purchase.id' => 'DESC']);

		$this->paginate = [
			'limit' => 25,
			'order' => ['Purchase.id' => 'DESC'],
			'sortableFields' => [
				'Purchase.unique_id',
				'Purchase.item_details',
				'Purchase.status',
				'Purchase.created',
			],
		];
		$page = $this->paginate($query);
		$this->set('purchaseArr', $this->mapPurchasePageToLegacy($page));
		$this->set('purchasesPaginated', $page);
	}
}
