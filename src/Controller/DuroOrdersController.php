 <?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class DuroOrdersController extends AppController {

/**
 * @var array
 */
	public $uses = array('User','CircularRelation','CircularManagementReview','NappUser','Agenda','Department','OrderProduct','DuroOrder','UserPermission','LoginHistory','Product','RewardProduct','RewardPoint','Feedback');
	public $components = array('Session','Paginator');	
	function beforeFilter()
    {
		$this->callConstants();
	}
		
	function admin_feedback(){
		
				
		$this->layout='admin_layout';
		$this->set('title_for_layout',SITENAME.' Feedback List');
		$this->checkAdminSession();
			
		$this->paginate = array('conditions'=>array(),'page' => 1, 'limit' => 10,'order'=>array('Feedback.id'=>'desc'));		
		$this->Feedback->recursive = 2;
		$FeedbackArr = $this->Paginator->paginate('Feedback');	
		 
		$this->set('FeedbackArr',$FeedbackArr);	
		
	}
	function admin_userreward(){
		
				
		$this->layout='admin_layout';
		$this->set('title_for_layout',SITENAME.' User Reward Point');
		$this->checkAdminSession();
			
		$this->paginate = array('conditions'=>array(),'page' => 1, 'limit' => 10,'order'=>array('RewardPoint.id'=>'desc'));		
		$this->RewardPoint->recursive = 2;
		$DuroOrderArr = $this->Paginator->paginate('RewardPoint');	
		 
		$this->set('DuroOrderArr',$DuroOrderArr);	
		
	}
	
		
	function admin_redeem($id){
		
		$this->layout='admin_layout';
		$this->set('title_for_layout',SITENAME.' Redeem Points');
		$this->checkAdminSession();
		
		if(!empty($this->request->data)){
			
		}
		
		$RewardPoint = $this->RewardPoint->find('first',array('conditions'=>array('RewardPoint.id'=>$id)));
		$this->set('RewardPoint',$RewardPoint);
	}
	function admin_index(){
		
				
		$this->layout='admin_layout';
		$this->set('title_for_layout',SITENAME.' KodexGlobal Order List');
		$this->checkAdminSession();
		
		$this->DuroOrder->bindModel(
		array('belongsTo' => array('NappUser' => array(
			'className' => 'NappUser',			 
			'foreignKey' => 'user_id',				
			'conditions' => array(),
			'fields' => array('name','lname'),
			'order' => array(),
		))));
		$this->DuroOrder->bindModel(
		array('hasMany' => array('OrderProduct' => array(
			'className' => 'OrderProduct',			 
			'foreignKey' => 'order_id',				
			'conditions' => array(),
			'fields' => array(),
			'order' => array(),
		))));		
		$this->paginate = array('conditions'=>array(),'page' => 1, 'limit' => 10,'order'=>array('DuroOrder.id'=>'desc'));		
		$this->DuroOrder->recursive = 2;
		$DuroOrderArr = $this->Paginator->paginate('DuroOrder');	
		
		/* echo '<pre>';
		print_r($DuroOrderArr);
		die(); */
		 
		$this->set('DuroOrderArr',$DuroOrderArr);	
		
	}	
	
	public function admin_edit($id=null) {

		$this->layout='admin_layout';
		$this->set('title_for_layout',SITENAME.' Add Order');
		$this->checkAdminSession();
		
		
		$this->UserPermission->bindModel(
			array('belongsTo' => array('NappUser' => array(
			'className' => 'NappUser',    
			'foreignKey' => 'user_id',    
			'conditions' => array(),
			'fields' => array('id','name','lname','email'),
			'order' => array(),
		))));
				
		$StockReturnkArr = $this->UserPermission->find('all',array('conditions'=>array('UserPermission.permssion_id'=>13)));
		
		$this->DuroOrder->bindModel(
		array('belongsTo' => array('NappUser' => array(
			'className' => 'NappUser',			 
			'foreignKey' => 'user_id',				
			'conditions' => array(),
			'fields' => array('id','name','lname','email'),
			'order' => array(),
		))));
		
		$this->DuroOrder->bindModel(
		array('hasMany' => array('OrderProduct' => array(
			'className' => 'OrderProduct',			 
			'foreignKey' => 'order_id',				
			'conditions' => array(),
			'fields' => array(),
			'order' => array(),
		))));	
		$docArr = $this->DuroOrder->find('first',array('conditions'=>array('DuroOrder.id'=>$id)));		
		
		if(!empty($this->request->data)){
					
			$this->request->data['DuroOrder']['id'] = $id;
			if($this->DuroOrder->save($this->request->data)){
				$this->OrderProduct->query("delete from order_products where order_id = ".$id."");
				
			 	$i=0;
				if(!empty($this->request->data['product_name'])){
					for($i=0; $i < count($this->request->data['product_name']); $i++){
						
						$product_id = $this->request->data['product_name'][$i];
						$ProductArr = $this->Product->find('first',array('conditions'=>array('Product.id'=>$product_id)));
						
						$InsertDuroOrder['OrderProduct']['id'] = '';
						$InsertDuroOrder['OrderProduct']['order_id'] = $id;
						$InsertDuroOrder['OrderProduct']['product_name'] = $ProductArr['Product']['title'];
						$InsertDuroOrder['OrderProduct']['product_id'] = $product_id;
						$InsertDuroOrder['OrderProduct']['color'] = $this->request->data['color'][$i];
						$InsertDuroOrder['OrderProduct']['size'] = $this->request->data['size'][$i];
						$InsertDuroOrder['OrderProduct']['qty'] = $this->request->data['qty'][$i];
						$this->OrderProduct->save($InsertDuroOrder);
					}	
				} 
				
				if($this->request->data['DuroOrder']['status'] == 1){
				
					$this->request->data['DuroOrder']['id'] = $id;
					$this->request->data['DuroOrder']['accepted_order'] = date('Y-m-d H:i:s');
					$this->DuroOrder->save($this->request->data);
				
					$sale_rep = $docArr['DuroOrder']['sale_rep'];
					$customer_order_no = $this->request->data['DuroOrder']['customer_order_no'];
					if(!empty($StockReturnkArr)){
						foreach($StockReturnkArr  as $StockReturnkArrs){
							$url = SITEURL.'duro_orders/autologin/'.base64_encode($StockReturnkArrs['NappUser']['id']);
							$to = $StockReturnkArrs['NappUser']['email'];				
							#$to = 'rsb@xoroglobal.com';				
							$subject = SITENAME." ::  Order #".$customer_order_no.' Accepted';				
							$template_name = 'acceptedorder';
							$name =  $StockReturnkArrs['NappUser']['name'].' '.$StockReturnkArrs['NappUser']['name'];				
							$variables = array('name'=>$name,'customer_order_no'=>$customer_order_no,'type'=>'Accepted','url'=>$url,'sale_rep'=>$sale_rep);		
							try{
								$this->sendemail($to,$subject,$template_name,$variables);
							}catch (Exception $e){
								
							}
						}
					}
					
				}else if($this->request->data['DuroOrder']['status'] == 3){
				
					$this->request->data['DuroOrder']['id'] = $id;
					$this->request->data['DuroOrder']['order_dispatched'] = date('Y-m-d H:i:s');
					$this->DuroOrder->save($this->request->data);
				
					$sale_rep = $docArr['DuroOrder']['sale_rep'];
					$customer_order_no = $this->request->data['DuroOrder']['customer_order_no'];
					if(!empty($StockReturnkArr)){
						foreach($StockReturnkArr  as $StockReturnkArrs){
							$url = SITEURL.'duro_orders/autologin/'.base64_encode($StockReturnkArrs['NappUser']['id']);
							$to = $StockReturnkArrs['NappUser']['email'];				
							#$to = 'rsb@xoroglobal.com';				
							$subject = SITENAME." ::  Order #".$customer_order_no.' Dispatched';				
							$template_name = 'acceptedorder';
							$name =  $StockReturnkArrs['NappUser']['name'].' '.$StockReturnkArrs['NappUser']['name'];				
							$variables = array('name'=>$name,'customer_order_no'=>$customer_order_no,'type'=>'Accepted','url'=>$url,'sale_rep'=>$sale_rep);		
							try{
								$this->sendemail($to,$subject,$template_name,$variables);
							}catch (Exception $e){
								
							}
						}
					}
					
				}else if($this->request->data['DuroOrder']['status'] == 6){
						
					$this->request->data['DuroOrder']['id'] = $id;
					$this->request->data['DuroOrder']['completed_order'] = date('Y-m-d H:i:s');
					$this->DuroOrder->save($this->request->data);	
					
					$sale_rep = $docArr['DuroOrder']['sale_rep'];
					$customer_order_no = $this->request->data['DuroOrder']['customer_order_no'];
					if(!empty($StockReturnkArr)){
						foreach($StockReturnkArr  as $StockReturnkArrs){
							$url = SITEURL.'duro_orders/autologin/'.base64_encode($StockReturnkArrs['NappUser']['id']);
							$to = $StockReturnkArrs['NappUser']['email'];				
							#$to = 'web@xoroglobal.com';				
							$subject = SITENAME." ::  Order #".$customer_order_no.' Completed';				
							$template_name = 'acceptedorder';
							$name =  $StockReturnkArrs['NappUser']['name'].' '.$StockReturnkArrs['NappUser']['name'];				
							$variables = array('name'=>$name,'customer_order_no'=>$customer_order_no,'type'=>'Completed','url'=>$url,'sale_rep'=>$sale_rep);		
							try{
								$this->sendemail($to,$subject,$template_name,$variables);
							}catch (Exception $e){
								
							}
						}
					}
				}else if($this->request->data['DuroOrder']['status'] == 5){	

					
					$url = SITEURL.'duro_orders/autologin/'.base64_encode($docArr['NappUser']['id']);
					$sale_rep = $docArr['DuroOrder']['sale_rep'];			
					$customer_order_no = $this->request->data['DuroOrder']['customer_order_no'];					
					$to = $docArr['NappUser']['email'];				
					#$to = 'web@xoroglobal.com';				
					$subject = SITENAME." ::  Order #".$customer_order_no.' Canceled';				
					$template_name = 'acceptedorder';
					$name =  $docArr['NappUser']['name'].' '.$docArr['NappUser']['name'];				
					$variables = array('name'=>$name,'customer_order_no'=>$customer_order_no,'type'=>'Canceled','sale_rep'=>$sale_rep);		
					try{
						$this->sendemail($to,$subject,$template_name,$variables);
					}catch (Exception $e){
						
					}
				}	
								
				$this->Session->setFlash('Product added successfully.','default',array('class' => 'alert alert-success'));
				$this->redirect('index');		
			}			
		}
		
		
		$this->set('docArr', $docArr);
		$this->request->data = $docArr;
		
		$RewardPoint = $this->RewardPoint->find('all');	
		$this->set('RewardPoint', $RewardPoint);
		
		$ProductArr = $this->Product->find('all',array('order'=>array('Product.title'=>'asc')));	
		$this->set('ProductArr', $ProductArr);
	
	}	
	
	public function admin_status($id=null,$status=null) {

		$this->autoRender = false;
		$this->set('title_for_layout',SITENAME.' Update status');
		$this->checkAdminSession();		
		
		$this->UserPermission->bindModel(
			array('belongsTo' => array('NappUser' => array(
			'className' => 'NappUser',    
			'foreignKey' => 'user_id',    
			'conditions' => array(),
			'fields' => array('id','name','lname','email'),
			'order' => array(),
		))));
				
		$StockReturnkArr = $this->UserPermission->find('all',array('conditions'=>array('UserPermission.permssion_id'=>13)));
		
		$this->DuroOrder->bindModel(
		array('belongsTo' => array('NappUser' => array(
			'className' => 'NappUser',			 
			'foreignKey' => 'user_id',				
			'conditions' => array(),
			'fields' => array('id','name','lname','email'),
			'order' => array(),
		))));
		
		$this->DuroOrder->bindModel(
		array('hasMany' => array('OrderProduct' => array(
			'className' => 'OrderProduct',			 
			'foreignKey' => 'order_id',				
			'conditions' => array(),
			'fields' => array(),
			'order' => array(),
		))));	
		$docArr = $this->DuroOrder->find('first',array('conditions'=>array('DuroOrder.id'=>$id)));		
		
		if(!empty($docArr)){
			
			$this->request->data['DuroOrder']['status'] = $status;
			$this->request->data['DuroOrder']['id'] = $id;
			if($this->DuroOrder->save($this->request->data)){
				
				$opd = array();
				if(!empty($docArr['OrderProduct'])){
					foreach($docArr['OrderProduct'] as $OrderProducts){
						$opds['product_name'] = $OrderProducts['product_name'];
						$opds['size'] = $OrderProducts['size'];
						$opds['color'] = $OrderProducts['color'];
						$opds['qty'] = $OrderProducts['qty'];
						$opd[] = $opds;
					}
				}	
				
				if($status == 1){
					
					$this->request->data['DuroOrder']['id'] = $id;
					$this->request->data['DuroOrder']['accepted_order'] = date('Y-m-d H:i:s');
					$this->DuroOrder->save($this->request->data);
					
					$customer_order_no = $docArr['DuroOrder']['customer_order_no'];
					$sale_rep = $docArr['DuroOrder']['sale_rep'];
					if(!empty($StockReturnkArr)){
						foreach($StockReturnkArr  as $StockReturnkArrs){
							$url = SITEURL.'duro_orders/autologin/'.base64_encode($StockReturnkArrs['NappUser']['id']);
							$to = $StockReturnkArrs['NappUser']['email'];
							$subject = SITENAME." ::  Order #".$customer_order_no.' Accepted';				
							$template_name = 'acceptedorder';
							$name =  $StockReturnkArrs['NappUser']['name'].' '.$StockReturnkArrs['NappUser']['name'];				
							$variables = array('name'=>$name,'customer_order_no'=>$customer_order_no,'type'=>'Accepted','url'=>$url,'opd'=>$opd,'sale_rep'=>$sale_rep);		
							try{
								$this->sendemail($to,$subject,$template_name,$variables);
							}catch (Exception $e){									
							}
						}
					}
					
				}else if($status == 4){
					

					$this->request->data['DuroOrder']['id'] = $id;
					$this->request->data['DuroOrder']['completed_order'] = date('Y-m-d H:i:s');
					$this->DuroOrder->save($this->request->data);					
						
					$sale_rep = $docArr['DuroOrder']['sale_rep'];
					$customer_order_no = $docArr['DuroOrder']['customer_order_no'];
					if(!empty($StockReturnkArr)){
						foreach($StockReturnkArr  as $StockReturnkArrs){
							$url = SITEURL.'duro_orders/autologin/'.base64_encode($StockReturnkArrs['NappUser']['id']);
							$to = $StockReturnkArrs['NappUser']['email'];				
							#$to = 'web@xoroglobal.com';				
							$subject = SITENAME." ::  Order #".$customer_order_no.' Completed';				
							$template_name = 'acceptedorder';
							$name =  $StockReturnkArrs['NappUser']['name'].' '.$StockReturnkArrs['NappUser']['name'];				
							$variables = array('name'=>$name,'customer_order_no'=>$customer_order_no,'type'=>'Completed','url'=>$url,'opd'=>$opd,'sale_rep'=>$sale_rep);		
							try{
								$this->sendemail($to,$subject,$template_name,$variables);
							}catch (Exception $e){
								
							}
						}
					}
				}else if($status == 5){			
					$sale_rep = $docArr['DuroOrder']['sale_rep'];
					$url = SITEURL.'duro_orders/autologin/'.base64_encode($docArr['NappUser']['id']);
					$customer_order_no = $docArr['DuroOrder']['customer_order_no'];
					$to = $docArr['NappUser']['email'];		
					$subject = SITENAME." ::  Order #".$customer_order_no.' Canceled';				
					$template_name = 'acceptedorder';
					$name =  $docArr['NappUser']['name'].' '.$docArr['NappUser']['name'];				
					$variables = array('name'=>$name,'customer_order_no'=>$customer_order_no,'type'=>'Canceled','opd'=>$opd,'sale_rep'=>$sale_rep);		
					try{
						$this->sendemail($to,$subject,$template_name,$variables);
					}catch (Exception $e){
						
					}
				}									
				$this->Session->setFlash('Status updated successfully.','default',array('class' => 'alert alert-success'));
				$this->redirect('index');		
			}	
			
		}
		
		
		$this->set('docArr', $docArr);
		$this->request->data = $docArr;
		
	}
	public function status($id=null,$status=null) {

		$this->autoRender = false;
		$this->checkSatffSession();
		
		$this->UserPermission->bindModel(
			array('belongsTo' => array('NappUser' => array(
			'className' => 'NappUser',    
			'foreignKey' => 'user_id',    
			'conditions' => array(),
			'fields' => array('id','name','lname','email'),
			'order' => array(),
		))));
				
		$StockReturnkArr = $this->UserPermission->find('all',array('conditions'=>array('UserPermission.permssion_id'=>13)));
		
		$this->DuroOrder->bindModel(
		array('belongsTo' => array('NappUser' => array(
			'className' => 'NappUser',			 
			'foreignKey' => 'user_id',				
			'conditions' => array(),
			'fields' => array('id','name','lname','email'),
			'order' => array(),
		))));
		
		$this->DuroOrder->bindModel(
		array('hasMany' => array('OrderProduct' => array(
			'className' => 'OrderProduct',			 
			'foreignKey' => 'order_id',				
			'conditions' => array(),
			'fields' => array(),
			'order' => array(),
		))));	
		$docArr = $this->DuroOrder->find('first',array('conditions'=>array('DuroOrder.id'=>$id)));		
		
		if(!empty($docArr)){
			
			$this->request->data['DuroOrder']['status'] = $status;
			$this->request->data['DuroOrder']['id'] = $id;
			if($this->DuroOrder->save($this->request->data)){
				
				$opd = array();
				if(!empty($docArr['OrderProduct'])){
					foreach($docArr['OrderProduct'] as $OrderProducts){
						$opds['product_name'] = $OrderProducts['product_name'];
						$opds['size'] = $OrderProducts['size'];
						$opds['color'] = $OrderProducts['color'];
						$opds['qty'] = $OrderProducts['qty'];
						$opd[] = $opds;
					}
				}	
				
				if($status == 2){
					$this->request->data['DuroOrder']['id'] = $id;
					$this->request->data['DuroOrder']['ready_order'] = date('Y-m-d H:i:s');
					$this->DuroOrder->save($this->request->data);
					
					$sale_rep = $docArr['DuroOrder']['sale_rep'];
					$customer_order_no = $docArr['DuroOrder']['customer_order_no'];
					if(!empty($StockReturnkArr)){
						foreach($StockReturnkArr  as $StockReturnkArrs){
							$url = SITEURL.'duro_orders/autologin/'.base64_encode($StockReturnkArrs['NappUser']['id']);
							$to = $StockReturnkArrs['NappUser']['email'];
							$subject = SITENAME." ::  Order #".$customer_order_no.' Order Ready';				
							$template_name = 'acceptedorder';
							$name =  $StockReturnkArrs['NappUser']['name'].' '.$StockReturnkArrs['NappUser']['name'];				
							$variables = array('name'=>$name,'customer_order_no'=>$customer_order_no,'type'=>'Order Ready','url'=>$url,'opd'=>$opd,'sale_rep'=>$sale_rep);		
							try{
								$this->sendemail($to,$subject,$template_name,$variables);
							}catch (Exception $e){									
							}
						}
					}
					
					
					// send email to admin
					$url = SITEURL.'duro_orders/autologinAdmin';
					$adminto = 'rsb@durotechindustries.com.au';
					$subject = SITENAME." ::  Order #".$customer_order_no.' Order Ready';				
					$template_name = 'acceptedorder';
					$name =  'Raghujit';				
					$variables = array('name'=>$name,'customer_order_no'=>$customer_order_no,'type'=>'Order Ready','url'=>$url,'sale_rep'=>$sale_rep);		
					try{
						$this->sendemail($adminto,$subject,$template_name,$variables);
					}catch (Exception $e){									
					}
					
				}else if($status == 3){
					$this->request->data['DuroOrder']['id'] = $id;
					$this->request->data['DuroOrder']['order_dispatched'] = date('Y-m-d H:i:s');
					$this->DuroOrder->save($this->request->data);
					
					$customer_order_no = $docArr['DuroOrder']['customer_order_no'];
					$sale_rep = $docArr['DuroOrder']['sale_rep'];
					if(!empty($StockReturnkArr)){
						foreach($StockReturnkArr  as $StockReturnkArrs){
							$url = SITEURL.'duro_orders/autologin/'.base64_encode($StockReturnkArrs['NappUser']['id']);
							$to = $StockReturnkArrs['NappUser']['email'];				
							#$to = 'web@xoroglobal.com';				
							$subject = SITENAME." ::  Order #".$customer_order_no.' Dispatched Order';				
							$template_name = 'acceptedorder';
							$name =  $StockReturnkArrs['NappUser']['name'].' '.$StockReturnkArrs['NappUser']['name'];				
							$variables = array('name'=>$name,'customer_order_no'=>$customer_order_no,'type'=>'Dispatched Order','url'=>$url,'opd'=>$opd,'sale_rep'=>$sale_rep);		
							try{
								$this->sendemail($to,$subject,$template_name,$variables);
							}catch (Exception $e){
								
							}
						}
					}
					
					// send email to admin
					$url = SITEURL.'duro_orders/autologinAdmin';
					$adminto = 'rsb@durotechindustries.com.au';
					$subject = SITENAME." ::  Order #".$customer_order_no.' Dispatched Ready';				
					$template_name = 'acceptedorder';
					$name =  'Raghujit';				
					$variables = array('name'=>$name,'customer_order_no'=>$customer_order_no,'type'=>'Dispatched Ready','url'=>$url,'opd'=>$opd,'sale_rep'=>$sale_rep);		
					try{
						$this->sendemail($adminto,$subject,$template_name,$variables);
					}catch (Exception $e){									
					}
				}else if($status == 6){
					
					$this->request->data['DuroOrder']['id'] = $id;
					$this->request->data['DuroOrder']['order_deliverd'] = date('Y-m-d H:i:s');
					$this->DuroOrder->save($this->request->data);
					
					
					$customer_order_no = $docArr['DuroOrder']['customer_order_no'];
					$sale_rep = $docArr['DuroOrder']['sale_rep'];
					if(!empty($StockReturnkArr)){
						foreach($StockReturnkArr  as $StockReturnkArrs){
							$url = SITEURL.'duro_orders/autologin/'.base64_encode($StockReturnkArrs['NappUser']['id']);
							$to = $StockReturnkArrs['NappUser']['email'];				
							#$to = 'web@xoroglobal.com';				
							$subject = SITENAME." ::  Order #".$customer_order_no.' Order Delivered';				
							$template_name = 'acceptedorder';
							$name =  $StockReturnkArrs['NappUser']['name'].' '.$StockReturnkArrs['NappUser']['name'];				
							$variables = array('name'=>$name,'customer_order_no'=>$customer_order_no,'type'=>'Dispatched Order','url'=>$url,'sale_rep'=>$sale_rep);		
							try{
								$this->sendemail($to,$subject,$template_name,$variables);
							}catch (Exception $e){
								
							}
						}
					}
					
					// send email to admin
					$url = SITEURL.'duro_orders/autologinAdmin';
					$adminto = 'rsb@durotechindustries.com.au';
					$subject = SITENAME." ::  Order #".$customer_order_no.' Order Delivered';				
					$template_name = 'acceptedorder';
					$name =  'Raghujit';				
					$variables = array('name'=>$name,'customer_order_no'=>$customer_order_no,'type'=>'Delivered','url'=>$url,'opd'=>$opd,'sale_rep'=>$sale_rep);		
					try{
						$this->sendemail($adminto,$subject,$template_name,$variables);
					}catch (Exception $e){									
					}
				}								
				$this->Session->setFlash('Status updated successfully.','default',array('class' => 'alert alert-success'));
				$this->redirect('index');		
			}	
			
		}
		
		$this->set('docArr', $docArr);
		$this->request->data = $docArr;
		
	}
	
	function autologin($id=null){
		$this->autoRender = false;
		
		if(!empty($id)){
			$id = base64_decode($id);		
			$napuserArr = $this->NappUser->find('first',array('conditions'=>array('id'=>$id)));			
			if(!empty($napuserArr)){
				$insert['LoginHistory']['user_id'] = $napuserArr['NappUser']['id'];
				$insert['LoginHistory']['role'] = 'Customer';
				$insert['LoginHistory']['logintime'] = date('Y-m-d H:i:s');
				$this->LoginHistory->save($insert);
				
				$this->Session->write('Customer', $napuserArr['NappUser']);
				$this->Session->write('is_staff', 1);			
				$this->redirect('index');
			}else{
				$this->redirect('/login');	
			}
		}else{
			$this->redirect('/login');	
		}
	}
	
	function index(){	
				
		$this->layout='staff_inner_layout';
		$this->set('title_for_layout',SITENAME.' Durotech Order List');
		$this->checkSatffSession();
		$user_id=$this->Session->read('Customer.id');
		
		$this->DuroOrder->bindModel(
		array('belongsTo' => array('NappUser' => array(
			'className' => 'NappUser',			 
			'foreignKey' => 'user_id',				
			'conditions' => array(),
			'fields' => array('name','lname'),
			'order' => array(),
		))));
		$this->DuroOrder->bindModel(
		array('hasMany' => array('OrderProduct' => array(
			'className' => 'OrderProduct',			 
			'foreignKey' => 'order_id',				
			'conditions' => array(),
			'fields' => array(),
			'order' => array(),
		))));		
			
		$this->DuroOrder->recursive = 2;
		$DuroOrderArr = $this->DuroOrder->find('all',array('order'=>array('DuroOrder.id'=>'desc')));	
		
		/* echo '<pre>';
		print_r($DuroOrderArr);
		die(); */
		 
		$this->set('DuroOrderArr',$DuroOrderArr);	
		$this->set('user_id',$user_id);	
		
	}	
	
	public function add() {

		$this->layout='staff_inner_layout';
		$this->set('title_for_layout',SITENAME.' Add Order');
		$this->checkSatffSession();
		$user_id=$this->Session->read('Customer.id');
		
		if(!empty($this->request->data)){
			
			$this->request->data['DuroOrder']['status'] = 0;
			$this->request->data['DuroOrder']['user_id'] = $user_id;
			$this->request->data['DuroOrder']['created'] = date('Y-m-d H:i:s');
			if($this->DuroOrder->save($this->request->data)){
				$lastInsertId = $this->DuroOrder->id;				
				
				$i=0;
				if(!empty($this->request->data['product_name'])){
					for($i=0; $i < count($this->request->data['product_name']); $i++){
						
						$product_id = $this->request->data['product_name'][$i];
						$ProductArr = $this->Product->find('first',array('conditions'=>array('Product.id'=>$product_id)));
						
						$InsertDuroOrder['OrderProduct']['id'] = '';
						$InsertDuroOrder['OrderProduct']['order_id'] = $lastInsertId;
						$InsertDuroOrder['OrderProduct']['product_name'] = $ProductArr['Product']['title'];
						$InsertDuroOrder['OrderProduct']['product_id'] = $product_id;
						$InsertDuroOrder['OrderProduct']['color'] = $this->request->data['color'][$i];
						$InsertDuroOrder['OrderProduct']['size'] = $this->request->data['size'][$i];
						$InsertDuroOrder['OrderProduct']['qty'] = $this->request->data['qty'][$i];
						$this->OrderProduct->save($InsertDuroOrder);
					}	
				}
								
				$this->DuroOrder->bindModel(
					array('hasMany' => array('OrderProduct' => array(
					'className' => 'OrderProduct',    
					'foreignKey' => 'order_id',    
					'conditions' => array(),
					'fields' => array(),
					'order' => array(),
				))));
				$DuroOrder = $this->DuroOrder->find('first',array('conditions'=>array('DuroOrder.id'=>$lastInsertId)));		
				$opd = array();
				if(!empty($DuroOrder['OrderProduct'])){
					foreach($DuroOrder['OrderProduct'] as $OrderProducts){
						$opds['product_name'] = $OrderProducts['product_name'];
						$opds['size'] = $OrderProducts['size'];
						$opds['color'] = $OrderProducts['color'];
						$opds['qty'] = $OrderProducts['qty'];
						$opd[] = $opds;
					}
				}				
				
				$customer_order_no = $DuroOrder['DuroOrder']['customer_order_no'];
				$sale_rep = $DuroOrder['DuroOrder']['sale_rep'];
				$to = 'rsb@durotechindustries.com.au';		
				$subject = SITENAME." :: New Order #".$customer_order_no;				
				$template_name = 'neworder_test';
				$name =  $this->Session->read('Customer.name').' '.$this->Session->read('Customer.lname');				
				$variables = array('name'=>$name,'customer_order_no'=>$customer_order_no,'opd'=>$opd,'sale_rep'=>$sale_rep);		
				try{			
					$this->sendemail($to,$subject,$template_name,$variables);
					$this->sendemail('jaswinder@kodexglobalcc.com',$subject,$template_name,$variables);
					$this->sendemail('web@xoroglobal.com',$subject,$template_name,$variables);
					$this->sendemail('rajan@xoroglobal.com',$subject,$template_name,$variables);
				}catch (Exception $e){
					
				}
				
				$this->Session->setFlash('Product added successfully.','default',array('class' => 'alert alert-success'));
				$this->redirect('index');		
			}	
			
		}
		
		$cuser = $this->NappUser->find('all',array('conditions'=>array('NappUser.is_staff_id'=>1),'fields'=>array('id','name','lname')));
		$this->set('cuser', $cuser);	

		$docArr = $this->DuroOrder->find('first',array('order'=>array('DuroOrder.id'=>'DESC'),'fields'=>array('DuroOrder.id')));
		if(!empty($docArr['DuroOrder']['id'])){
			$docId = 'KD-ORDER-'. (1000+$docArr['DuroOrder']['id'] + 1);
		}else{
			$docId = 'KD-ORDER-1001';
		}	
		$this->set('docId', $docId);
		
		$ProductArr = $this->Product->find('all',array('order'=>array('Product.title'=>'asc')));	
		$this->set('ProductArr', $ProductArr);
	}
	
	public function admin_autologin() {
		$this->autoRender = false;
		
		$admin_arr = $this->User->find('first');
		if(!empty($admin_arr)){				
			$this->Session->write('User', $admin_arr['User']);
			$this->Session->write('is_admin', 1);			
			$this->redirect('/duro_orders');
		}
	}

	public function edit($id=null) {

		$this->layout='staff_inner_layout';
		$this->set('title_for_layout',SITENAME.' Add Order');
		$this->checkSatffSession();
		$user_id=$this->Session->read('Customer.id');
		
		if(!empty($this->request->data)){
			
			$this->request->data['DuroOrder']['id'] = $id;
			if($this->DuroOrder->save($this->request->data)){
				$this->OrderProduct->query("delete from order_products where order_id = ".$id."");
				
				$i=0;
				if(!empty($this->request->data['product_name'])){
					for($i=0; $i < count($this->request->data['product_name']); $i++){
						
						$product_id = $this->request->data['product_name'][$i];
						$ProductArr = $this->Product->find('first',array('conditions'=>array('Product.id'=>$product_id)));
						
						$InsertDuroOrder['OrderProduct']['id'] = '';
						$InsertDuroOrder['OrderProduct']['order_id'] = $id;
						$InsertDuroOrder['OrderProduct']['product_name'] = $ProductArr['Product']['title'];
						$InsertDuroOrder['OrderProduct']['product_id'] = $product_id;
						$InsertDuroOrder['OrderProduct']['color'] = $this->request->data['color'][$i];
						$InsertDuroOrder['OrderProduct']['size'] = $this->request->data['size'][$i];
						$InsertDuroOrder['OrderProduct']['qty'] = $this->request->data['qty'][$i];
						$this->OrderProduct->save($InsertDuroOrder);
					}	
				}
				$this->Session->setFlash('Product added successfully.','default',array('class' => 'alert alert-success'));
				$this->redirect('index');		
			}	
			
		}
		
		$cuser = $this->NappUser->find('all',array('conditions'=>array('NappUser.is_staff_id'=>1),'fields'=>array('id','name','lname')));
		$this->set('cuser', $cuser);	
		
		$this->DuroOrder->bindModel(
		array('hasMany' => array('OrderProduct' => array(
			'className' => 'OrderProduct',			 
			'foreignKey' => 'order_id',				
			'conditions' => array(),
			'fields' => array(),
			'order' => array(),
		))));	
		$docArr = $this->DuroOrder->find('first',array('conditions'=>array('DuroOrder.id'=>$id)));		
		$this->set('docArr', $docArr);
		$this->request->data = $docArr;
		
		$ProductArr = $this->Product->find('all',array('order'=>array('Product.title'=>'asc')));	
		$this->set('ProductArr', $ProductArr);
	}	
	public function feedback($id=null) {

		$this->layout='staff_inner_layout';
		$this->set('title_for_layout',SITENAME.' Feedback');
		$this->checkSatffSession();
		$user_id=$this->Session->read('Customer.id');
		
		if(!empty($this->request->data)){
			
			$this->request->data['DuroOrder']['id'] = $id;
			if($this->DuroOrder->save($this->request->data)){
				
				$this->Session->setFlash('Feedback added successfully.','default',array('class' => 'alert alert-success'));
				$this->redirect('index');		
			}	
			
		}
		
		$cuser = $this->NappUser->find('all',array('conditions'=>array('NappUser.is_staff_id'=>1),'fields'=>array('id','name','lname')));
		$this->set('cuser', $cuser);	
		
		$this->DuroOrder->bindModel(
		array('hasMany' => array('OrderProduct' => array(
			'className' => 'OrderProduct',			 
			'foreignKey' => 'order_id',				
			'conditions' => array(),
			'fields' => array(),
			'order' => array(),
		))));	
		$docArr = $this->DuroOrder->find('first',array('conditions'=>array('DuroOrder.id'=>$id)));		
		$this->set('docArr', $docArr);
		$this->request->data = $docArr;
		
		$ProductArr = $this->Product->find('all',array('order'=>array('Product.title'=>'asc')));	
		$this->set('ProductArr', $ProductArr);
	}	
	function accepted($id=null){
		$this->autoRender = false;
		
		$this->checkSatffSession();
		
		if(!empty($id)){
			$this->DuroOrder->bindModel(
			array('hasMany' => array('OrderProduct' => array(
				'className' => 'OrderProduct',			 
				'foreignKey' => 'order_id',				
				'conditions' => array(),
				'fields' => array(),
				'order' => array(),
			))));
			$durorder =$this->DuroOrder->find('first',array('conditions'=>array('DuroOrder.id'=>$id)));
			$customer_order_no = $durorder['DuroOrder']['customer_order_no'];
			
			$this->request->data['DuroOrder']['status'] = 1;
			$this->request->data['DuroOrder']['id'] = $id;
			$this->request->data['DuroOrder']['accepted_order'] = date('Y-m-d H:i:s');
			
			if($this->DuroOrder->save($this->request->data)){	
				
				$opd = array();
				if(!empty($durorder['OrderProduct'])){
					foreach($durorder['OrderProduct'] as $OrderProducts){
						$opds['product_name'] = $OrderProducts['product_name'];
						$opds['size'] = $OrderProducts['size'];
						$opds['color'] = $OrderProducts['color'];
						$opds['qty'] = $OrderProducts['qty'];
						$opd[] = $opds;
					}
				}	
	
				$this->UserPermission->bindModel(
					array('belongsTo' => array('NappUser' => array(
					'className' => 'NappUser',    
					'foreignKey' => 'user_id',    
					'conditions' => array(),
					'fields' => array('name','lname','email','id'),
					'order' => array(),
				))));
						
				$StockReturnkArr = $this->UserPermission->find('all',array('conditions'=>array('UserPermission.permssion_id'=>13)));
				if(!empty($StockReturnkArr)){
					foreach($StockReturnkArr as $StockReturnkArrs){
						$toname = $StockReturnkArrs['NappUser']['name'].' '.$StockReturnkArrs['NappUser']['lname'];
						$email = $StockReturnkArrs['NappUser']['email'];
						$user_id = $StockReturnkArrs['NappUser']['id'];
						$url = SITEURL.'duro_orders/autologin/'.$user_id;
						$subject = SITENAME." :: Accepted Order #".$customer_order_no;				
						$template_name = 'orderstatus';
						$name =  $this->Session->read('Customer.name').' '.$this->Session->read('Customer.lname');				
						$variables = array('toname'=>$toname,'name'=>$name,'customer_order_no'=>$customer_order_no,'orderstatus'=>'Accepted','opd'=>$opd,'url'=>$url);		
						try{
							$this->sendemail($email,$subject,$template_name,$variables);
						}catch (Exception $e){
							
						}
					}	
				}	
				
				$this->Session->setFlash('Order accepted.','default',array('class' => 'alert alert-success'));
				$this->redirect('index');		
			}	
			
		}		
	}	
	function orderready($id=null){
		$this->autoRender = false;
		
		$this->checkSatffSession();
		
		if(!empty($id)){
			
			$this->DuroOrder->bindModel(
			array('hasMany' => array('OrderProduct' => array(
				'className' => 'OrderProduct',			 
				'foreignKey' => 'order_id',				
				'conditions' => array(),
				'fields' => array(),
				'order' => array(),
			))));	
			$durorder =$this->DuroOrder->find('first',array('conditions'=>array('DuroOrder.id'=>$id)));
			$customer_order_no = $durorder['DuroOrder']['customer_order_no'];
			
			$this->request->data['DuroOrder']['status'] = 2;
			$this->request->data['DuroOrder']['id'] = $id;
			$this->request->data['DuroOrder']['ready_order'] = date('Y-m-d H:i:s');
			if($this->DuroOrder->save($this->request->data)){	
			
				$opd = array();
				if(!empty($durorder['OrderProduct'])){
					foreach($durorder['OrderProduct'] as $OrderProducts){
						$opds['product_name'] = $OrderProducts['product_name'];
						$opds['size'] = $OrderProducts['size'];
						$opds['color'] = $OrderProducts['color'];
						$opds['qty'] = $OrderProducts['qty'];
						$opd[] = $opds;
					}
				}	

				$this->UserPermission->bindModel(
					array('belongsTo' => array('NappUser' => array(
					'className' => 'NappUser',    
					'foreignKey' => 'user_id',    
					'conditions' => array(),
					'fields' => array('name','lname','email','id'),
					'order' => array(),
				))));
						
				$StockReturnkArr = $this->UserPermission->find('all',array('conditions'=>array('UserPermission.permssion_id'=>13)));
				if(!empty($StockReturnkArr)){
					foreach($StockReturnkArr as $StockReturnkArrs){
						$toname = $StockReturnkArrs['NappUser']['name'].' '.$StockReturnkArrs['NappUser']['lname'];
						$email = $StockReturnkArrs['NappUser']['email'];
						
						$user_id = $StockReturnkArrs['NappUser']['id'];
						$url = SITEURL.'duro_orders/autologin/'.$user_id;
						$subject = SITENAME." :: Ready Order #".$customer_order_no;				
						$template_name = 'orderstatus';
						$name =  $this->Session->read('Customer.name').' '.$this->Session->read('Customer.lname');				
						$variables = array('toname'=>$toname,'name'=>$name,'customer_order_no'=>$customer_order_no,'orderstatus'=>'Ready','opd'=>$opd,'url'=>$url);		
						try{
							$this->sendemail($email,$subject,$template_name,$variables);
						}catch (Exception $e){
							
						}
					}	
				}	
				
				$this->Session->setFlash('Order ready.','default',array('class' => 'alert alert-success'));
				$this->redirect('index');		
			}	
			
		}		
	}
	function deliver($id=null){
		$this->autoRender = false;		
		$this->checkSatffSession();		
		if(!empty($id)){
			$this->DuroOrder->bindModel(
			array('hasMany' => array('OrderProduct' => array(
				'className' => 'OrderProduct',			 
				'foreignKey' => 'order_id',				
				'conditions' => array(),
				'fields' => array(),
				'order' => array(),
			))));
			$durorder =$this->DuroOrder->find('first',array('conditions'=>array('DuroOrder.id'=>$id)));
			$customer_order_no = $durorder['DuroOrder']['customer_order_no'];
			
			$this->request->data['DuroOrder']['status'] = 3;
			$this->request->data['DuroOrder']['id'] = $id;
			$this->request->data['DuroOrder']['order_dispatched'] = date('Y-m-d H:i:s');
			if($this->DuroOrder->save($this->request->data)){
				
				$opd = array();
				if(!empty($durorder['OrderProduct'])){
					foreach($durorder['OrderProduct'] as $OrderProducts){
						$opds['product_name'] = $OrderProducts['product_name'];
						$opds['size'] = $OrderProducts['size'];
						$opds['color'] = $OrderProducts['color'];
						$opds['qty'] = $OrderProducts['qty'];
						$opd[] = $opds;
					}
				}	

				$this->UserPermission->bindModel(
					array('belongsTo' => array('NappUser' => array(
					'className' => 'NappUser',    
					'foreignKey' => 'user_id',    
					'conditions' => array(),
					'fields' => array('name','lname','email','id'),
					'order' => array(),
				))));
						
				$StockReturnkArr = $this->UserPermission->find('all',array('conditions'=>array('UserPermission.permssion_id'=>13)));
				if(!empty($StockReturnkArr)){
					foreach($StockReturnkArr as $StockReturnkArrs){
						$toname = $StockReturnkArrs['NappUser']['name'].' '.$StockReturnkArrs['NappUser']['lname'];
						$email = $StockReturnkArrs['NappUser']['email'];
						
						$user_id = $StockReturnkArrs['NappUser']['id'];
						$url = SITEURL.'duro_orders/autologin/'.$user_id;
						
						$subject = SITENAME." :: Dispatch Order #".$customer_order_no;				
						$template_name = 'orderstatus';
						$name =  $this->Session->read('Customer.name').' '.$this->Session->read('Customer.lname');				
						$variables = array('toname'=>$toname,'name'=>$name,'customer_order_no'=>$customer_order_no,'orderstatus'=>'Dispatch','opd'=>$opd,'url'=>$url);		
						try{
							$this->sendemail($email,$subject,$template_name,$variables);
						}catch (Exception $e){
							
						}
					}	
				}
				
				$this->Session->setFlash('Order Deliver.','default',array('class' => 'alert alert-success'));
				$this->redirect('index');		
			}				
		}		
	}
	function cancelled($id=null){
		$this->autoRender = false;
		
		$this->checkSatffSession();
		
		if(!empty($id)){
			
			$this->DuroOrder->bindModel(
			array('hasMany' => array('OrderProduct' => array(
				'className' => 'OrderProduct',			 
				'foreignKey' => 'order_id',				
				'conditions' => array(),
				'fields' => array(),
				'order' => array(),
			))));
			
			$durorder =$this->DuroOrder->find('first',array('conditions'=>array('DuroOrder.id'=>$id)));
			$customer_order_no = $durorder['DuroOrder']['customer_order_no'];
			
			$this->request->data['DuroOrder']['status'] = 5;
			$this->request->data['DuroOrder']['id'] = $id;
			if($this->DuroOrder->save($this->request->data)){
				
				
				$opd = array();
				if(!empty($durorder['OrderProduct'])){
					foreach($durorder['OrderProduct'] as $OrderProducts){
						$opds['product_name'] = $OrderProducts['product_name'];
						$opds['size'] = $OrderProducts['size'];
						$opds['color'] = $OrderProducts['color'];
						$opds['qty'] = $OrderProducts['qty'];
						$opd[] = $opds;
					}
				}	


				$this->UserPermission->bindModel(
					array('belongsTo' => array('NappUser' => array(
					'className' => 'NappUser',    
					'foreignKey' => 'user_id',    
					'conditions' => array(),
					'fields' => array('name','lname','email','id'),
					'order' => array(),
				))));
						
				$StockReturnkArr = $this->UserPermission->find('all',array('conditions'=>array('UserPermission.permssion_id'=>13)));
				if(!empty($StockReturnkArr)){
					foreach($StockReturnkArr as $StockReturnkArrs){
						$toname = $StockReturnkArrs['NappUser']['name'].' '.$StockReturnkArrs['NappUser']['lname'];
						$email = $StockReturnkArrs['NappUser']['email'];
						
						$user_id = $StockReturnkArrs['NappUser']['id'];
						$url = SITEURL.'duro_orders/autologin/'.$user_id;
						
						$subject = SITENAME." :: Cancelled Order #".$customer_order_no;				
						$template_name = 'orderstatus';
						$name =  $this->Session->read('Customer.name').' '.$this->Session->read('Customer.lname');				
						$variables = array('toname'=>$toname,'name'=>$name,'customer_order_no'=>$customer_order_no,'orderstatus'=>'Cancelled','opd'=>$opd,'url'=>$url);		
						try{
							$this->sendemail($email,$subject,$template_name,$variables);
						}catch (Exception $e){
							
						}
					}	
				}
				
				$this->Session->setFlash('Order cancelled.','default',array('class' => 'alert alert-success'));
				$this->redirect('index');		
			}	
			
		}		
	}
	function autologinAdmin(){
		$this->autoRender = false;
		
		$admin_arr = $this->User->find('first');	
		if(!empty($admin_arr)){				
			$insert['LoginHistory']['admin_id'] = $admin_arr['User']['id'];
			$insert['LoginHistory']['role'] = 'Admin';
			$insert['LoginHistory']['logintime'] = date('Y-m-d H:i:s');
			$this->LoginHistory->save($insert);
			
			$this->Session->write('User', $admin_arr['User']);
			$this->Session->write('is_admin', 1);					
			$this->redirect('/admin/duro_orders');
		}else{
			//$this->Session->setFlash(__('Wrong username/password', true));
			$this->Session->setFlash('Wrong username/password','default',array('class' => 'alert alert-danger'));
		}
	}	
	
	function admin_calculatepoints($order_id=null){
		
		$this->autoRender = false;
		$this->checkAdminSession();		
		$this->DuroOrder->bindModel(
		array('hasMany' => array('OrderProduct' => array(
			'className' => 'OrderProduct',			 
			'foreignKey' => 'order_id',				
			'conditions' => array(),
			'fields' => array(),
			'order' => array(),
		))));	
		$DuroOrder = $this->DuroOrder->find('first',array('conditions'=>array('DuroOrder.id'=>$order_id)));	
		if(!empty($DuroOrder['OrderProduct'])){
			$applicatorPoints = 0;
			$dealerPoint = 0;
			foreach($DuroOrder['OrderProduct'] as $OrderProducts){
				
				$product_name = $OrderProducts['product_name'];
				$product_id = $OrderProducts['product_id'];
				$size = $OrderProducts['size'];
				$qty = trim($OrderProducts['qty']);
				
				$size = strtoupper($size);
				$size = str_replace('KG','',$size);
				$size = str_replace('LT','',$size);
				$size = str_replace('KIT','',$size);
				$size = str_replace('ML','',$size);
				$size = trim($size);
				$totalqtnty = $size * $qty;
				
				
				$productArr = $this->Product->find('first',array('conditions'=>array('Product.id'=>$product_id)));				
				
				if(!empty($productArr)){
					$product_id = $productArr['Product']['id'];
					
					$RewardProduct = $this->RewardProduct->find('first',array('conditions'=>array('RewardProduct.product_id'=>$product_id)));
					if(!empty($RewardProduct)){
						$applicator_points = $RewardProduct['RewardProduct']['applicator_points'];
						$dealer_point = $RewardProduct['RewardProduct']['dealer_point'];
						
						$applicatorPoints = $applicatorPoints + ($applicator_points * $totalqtnty);
						$dealerPoint = $dealerPoint + ($dealer_point * $totalqtnty);
					}
				}				
			}
			$customer_id = $DuroOrder['DuroOrder']['customer_id'];
			if(!empty($customer_id)){
				$RewardPointArr = $this->RewardPoint->find('first',array('conditions'=>array('RewardPoint.id'=>$customer_id)));
				
				$insert['RewardPoint']['id'] = $customer_id;	
				$insert['RewardPoint']['order_id']	= $DuroOrder['DuroOrder']['id'];
				$insert['RewardPoint']['contact_name']	= $DuroOrder['DuroOrder']['contact_name'];
				$insert['RewardPoint']['contact_phone']	= $DuroOrder['DuroOrder']['contact_phone'];
				$insert['RewardPoint']['address']	= $DuroOrder['DuroOrder']['deliver_address'];
				if($DuroOrder['DuroOrder']['is_applicator'] == 0){
					$insert['RewardPoint']['points']	= $RewardPointArr['RewardPoint']['points'] + $dealerPoint;
				}else{
					$insert['RewardPoint']['points']	=  $RewardPointArr['RewardPoint']['points'] +  $applicatorPoints;
				}
				$this->RewardPoint->save($insert);
			}else{
				$insert['RewardPoint']['id'] = '';	
				$insert['RewardPoint']['order_id']	= $DuroOrder['DuroOrder']['id'];
				$insert['RewardPoint']['contact_name']	= $DuroOrder['DuroOrder']['contact_name'];
				$insert['RewardPoint']['contact_phone']	= $DuroOrder['DuroOrder']['contact_phone'];
				$insert['RewardPoint']['address']	= $DuroOrder['DuroOrder']['deliver_address'];
				if($DuroOrder['DuroOrder']['is_applicator'] == 0){
					$insert['RewardPoint']['points']	= $dealerPoint;
				}else{
					$insert['RewardPoint']['points']	= $applicatorPoints;
				}
				
				$this->RewardPoint->save($insert);
			}
			$update['DuroOrder']['id'] = $order_id;
			$update['DuroOrder']['is_point_added'] = 1;
			$this->DuroOrder->save($update);	

			$this->Session->setFlash('Points added successfully.','default',array('class' => 'alert alert-success'));	
			$this->redirect('/admin/duro_orders');	
		}		
	}
	
	function updatordertemp(){
		
		$this->autoRender = false;
		
		
		$this->DuroOrder->bindModel(
			array('hasMany' => array('OrderProduct' => array(
			'className' => 'OrderProduct',    
			'foreignKey' => 'order_id',    
			'conditions' => array(),
			'fields' => array(),
			'order' => array(),
		))));
		$DuroOrder = $this->DuroOrder->find('first',array('order'=>array('DuroOrder.id'=>'DESC')));
		
		$opd = array();
		if(!empty($DuroOrder['OrderProduct'])){
			foreach($DuroOrder['OrderProduct'] as $OrderProducts){
				$opds['product_name'] = $OrderProducts['product_name'];
				$opds['size'] = $OrderProducts['size'];
				$opds['color'] = $OrderProducts['color'];
				$opds['qty'] = $OrderProducts['qty'];
				$opd[] = $opds;
			}
		}
		
		$customer_order_no = $DuroOrder['DuroOrder']['customer_order_no'];
		$to = 'rsb@durotechindustries.com.au';		
		$subject = SITENAME." :: New Order #".$customer_order_no;				
		$template_name = 'neworder_test';
		$name =  $this->Session->read('Customer.name').' '.$this->Session->read('Customer.lname');				
		$variables = array('name'=>$name,'customer_order_no'=>$customer_order_no,'opd'=>$opd);		
		try{			
			$this->sendemail($to,$subject,$template_name,$variables);
			//$this->sendemail('duroraj@gmail.com',$subject,$template_name,$variables);
		//	$this->sendemail('web@xoroglobal.com',$subject,$template_name,$variables);
		}catch (Exception $e){
			
		}
		echo 'success';
	}

}
