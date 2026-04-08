   <?php 
App::uses('AppController','Controller');
class VendhqsController extends AppController{
	public $helpers = array('Html','Form','Session');
	public $components = array('Session','Paginator');	
	public $uses = array('User','Task','Employee','TaskAssign','TaskDocument','NappUser','TaskComment','UserPermission','Department','SaleRep','SaleQuestion','SaleNotification','AssignClient','Client','BatchCountSheet','BatchRegister','StaffClient','ClientType','Log');
	
	/***
	/*Author  :Ranjit,
	/*Comment : Check before user is login or not
	****/
	
	
	function beforeFilter()
    {
		$this->callConstants();
		define('VENDHQ','https://kodexglobalconstructionchemicals.vendhq.com/api/');
		define('CLIENT_ID','YimWWhGJFu3pRMqBl4eqsqtiBb3Ett1v');
		define('CLIENT_SECRET','mIw0fHEIK23ibbjoZ632R3mweE2klvph');
	}
	
	
	
	
	function productlist(){
		
		$this->autoRender = false;		
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,VENDHQ."products");
		#curl_setopt($ch, CURLOPT_POST, 1);		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$header = array(
			'Accept: application/json',
			'Content-Type: application/x-www-form-urlencoded',
			'Authorization: Bearer '. ACCESS_TOKEN
		);	 
		// pass header variable in curl method
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		$server_output = curl_exec($ch);
		curl_close ($ch);
				
		
		$serveroutput  = json_decode($server_output, true);
		
		echo '<pre>';
		print_r($serveroutput);
		die();
	}	
	
	
	function productDetial(){
		
		$this->autoRender = false;		
		
		isset($_REQUEST['product_id'])?  $product_id =  $_REQUEST['product_id'] : $product_id =  '';
		
		if(!empty($product_id)){
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,VENDHQ."products/".$product_id);
			#curl_setopt($ch, CURLOPT_POST, 1);		
			// curl_setopt($ch, CURLOPT_POST, 1);
			// curl_setopt($ch, CURLOPT_POSTFIELDS,$param);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$header = array(
				'Accept: application/json',
				'Content-Type: application/x-www-form-urlencoded',
				'Authorization: Bearer '. ACCESS_TOKEN
			);	
			
			// pass header variable in curl method
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			$server_output = curl_exec($ch);
			curl_close ($ch);
					
			
			$serveroutput  = json_decode($server_output, true);
			
			echo '<pre>';
			print_r($serveroutput);
			die();
		}else{
			$data['status'] = false;
			$data['msg'] = 'Product Id missing';
		}		
	}	
	
	function productUpdate(){
	
		$this->autoRender = false;		
		
		isset($_REQUEST['product_id'])?  $product_id =  $_REQUEST['product_id'] : $product_id =  'fbfca04b-8140-97df-5633-eb921c73cdf8';
		isset($_REQUEST['handle'])?  $handle =  $_REQUEST['handle'] : $handle =  'P15 Black';
		isset($_REQUEST['name'])?  $name =  $_REQUEST['name'] : $name =  'p15';
		isset($_REQUEST['sku'])?  $sku =  $_REQUEST['sku'] : $sku =  '10000';
		isset($_REQUEST['retail_price'])?  $retail_price =  $_REQUEST['retail_price'] : $retail_price =  '112';
		
		$param = 'id='.$product_id.'&handle='.$handle.'&name='.$name.'&sku='.$sku.'&retail_price='.$retail_price;
		try{
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL,VENDHQ."products");
		curl_setopt($ch, CURLOPT_POST, 1);
		#curl_setopt($ch, CURLOPT_POSTFIELDS,$param);
		
		$header = array(
			'Accept: application/json',
			'Content-Type: application/x-www-form-urlencoded',
			'Authorization: Bearer '. ACCESS_TOKEN
		);	 
		// pass header variable in curl method
		curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 1000 );
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		 curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$param);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec($ch);
		curl_close ($ch);
				
		$serveroutput  = json_decode($server_output, true);
		}catch(Exception $e){
			echo '<pre>';
			print_r($e);
			echo '</pre>';
		}	
		echo '<pre>';
		print_r($server_output);
		print_r($serveroutput);
		die();
		
	
	}
	
	
	function productDelete(){
	
		$this->autoRender = false;		
		 		
		isset($_REQUEST['product_id'])?  $product_id =  $_REQUEST['product_id'] : $product_id =  '1a195fc5-7407-5089-c953-da0cfebc7058';		
		if(!empty($product_id)){
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,VENDHQ."products/".$product_id);
			#curl_setopt($ch, CURLOPT_POST, 1);		
			// curl_setopt($ch, CURLOPT_POST, 1);
			// curl_setopt($ch, CURLOPT_POSTFIELDS,$param);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$header = array(
				'Accept: application/json',
				'Content-Type: application/x-www-form-urlencoded',
				'Authorization: Bearer '. ACCESS_TOKEN
			);	
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
			// pass header variable in curl method
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			$server_output = curl_exec($ch);
			curl_close ($ch);
					
			
			$serveroutput  = json_decode($server_output, true);
			
			echo '<pre>';
			print_r($serveroutput);
			die();
		}else{
			$data['status'] = false;
			$data['msg'] = 'Product Id missing';
		}	
	}	
	
	function customerslist(){
		
		$this->autoRender = false;		
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,VENDHQ."customers");
		#curl_setopt($ch, CURLOPT_POST, 1);		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$header = array(
			'Accept: application/json',
			'Content-Type: application/x-www-form-urlencoded',
			'Authorization: Bearer '. ACCESS_TOKEN
		);	 
		// pass header variable in curl method
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		$server_output = curl_exec($ch);
		curl_close ($ch);
				
		
		$serveroutput  = json_decode($server_output, true);
		
		echo '<pre>';
		print_r($serveroutput);
		die();
	}

	
	function createcustomer(){
		
		$this->autoRender = false;		
	}	
}
?>