<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Mailer\Mailer;

class FrontsController extends AppController{
	public $helpers = array('Html','Form','Session');
	public $components = array('Session','Paginator','Email');	
	public $uses = array('Category','Product','Subscriber','Customer','Notification','Device','Contact','SsSheetColumn','SsSheetCategory','SheetTask','NappUser','User','SheetTaskCreate','Salemeet','Analytic','Feedback');
	/***
	/*Author  :Paramjit,
	/*Comment : Check before user is login or not
	****/
	public function beforeFilter(EventInterface $event)
    {	
        parent::beforeFilter($event);
		$this->callConstants();
		$this->getCategories();
		$this->getCartList();
		//define('SITEURL', 'https://kodexglobalcc.com/kd/');
	}

	/***
	/*Author  :Ranjit,
	/*Comment :Home Page
	****/
	function isMobileDevice() 
	{
		return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
	}
	
	public function home(){
		$this->viewBuilder()->setLayout('customkggc_layout');
		$this->set('title_for_layout',SITENAME.' | Home Page');	
		
		
		$productArr = $this->Product->find('all',array('conditions'=>array('is_dashboard'=>1)));
		$this->set('product',$productArr);
		
		$res = $this->isMobileDevice();
		
		$this->set('ismobile',$res);
		
		$this->set('meta_title','Top Sealants and Waterproofing Solutions in Australia - Kodexcc');
		$this->set('meta_description','Discover premium sealants and waterproofing solutions in Australia at Kodexcc. Trusted and effective products. Call 1800 418 495 for assistance.');
	
	} 
	
	public function about(){
		$this->viewBuilder()->setLayout('customkggc_layout');
		
		$this->set('meta_title','About Us | Expert Sealants and Waterproofing Solutions for the Australian Market');
		$this->set('meta_description','Based Kodex specializing in providing professional sealant and waterproofing solutions for residential and commercial properties. Trust us to keep your space dry and protected.');
		$this->set('meta_keyword','company specializing in construction materials. Sealants, Primer, Waterproofing Solutions, Australian, Construction Materials, Building Supplies');		
	}
	
	
	public function bathroom_floor_waterproofing(){
		$this->viewBuilder()->setLayout('customkggc_layout');
		
		$this->set('meta_title','Bathroom Floor Waterproofing:  Keep Your Bathroom Safe and Dry');
		$this->set('meta_description','Bathroom floor waterproofing is essential for protecting your home from water damage. The various waterproofing options available.');
		$this->set('meta_keyword','Shower waterproofing,Wet area waterproofing, Australian waterproofing ');	
		$this->set('page','bathroom-floor-waterproofing');	
		$this->render('/Fronts/service');
	}

    public function bathroomFloorWaterproofing()
    {
        return $this->bathroom_floor_waterproofing();
    }
	
	public function bathroom_waterproofing(){
		$this->viewBuilder()->setLayout('customkggc_layout');
		
		$this->set('meta_title','Professional Bathroom Waterproofing Services in Sydney');
		$this->set('meta_description','Bathroom waterproofing is an essential aspect of any bathroom renovation or construction project in Australia.');
		$this->set('meta_keyword','Bathroom waterproofing, Waterproofing solutions,  Wet area waterproofing, Waterproofing membranes,  Shower waterproofing,   Bathroom renovation,  Bathroom tiles, Bathroom flooring, Australian waterproofing');	
		$this->set('page','bathroom-waterproofing');
		$this->render('/Fronts/service');		
	}

    public function bathroomWaterproofing()
    {
        return $this->bathroom_waterproofing();
    }
	
	public function shower_waterproofing()
	{
		$this->viewBuilder()->setLayout('customkggc_layout');
		
		$this->set('meta_title','Expert Guide to Shower Waterproofing: Tips and Products');
		$this->set('meta_description','Shower waterproofing is an essential process to prevent water damage and leakage in your bathroom.');
		$this->set('meta_keyword','Shower waterproofing,Wet area waterproofing, Australian waterproofing ');			
		$this->set('page','shower-waterproofing');	
		$this->render('/Fronts/service');
	}

    public function showerWaterproofing()
    {
        return $this->shower_waterproofing();
    }
	public function contact()
	{
		$this->viewBuilder()->setLayout('customkggc_layout');
		$this->set('title_for_layout',SITENAME.' Contact');	
		$host = (string)$this->getRequest()->getEnv('HTTP_HOST');
		$isLocalEnv = str_contains($host, 'localhost') || str_contains($host, '127.0.0.1');
		$this->set('isLocalEnv', $isLocalEnv);
		
		if(!empty($this->request->data))
		{	
			
			$ipaddr = $this->ip_info();			
			$isCaptchaValid = false;
			if ($isLocalEnv) {
				$isCaptchaValid = true;
			} else {
				$post = [
					'secret' => '6LdQkrYjAAAAAKV5TsNv6t772tIozc8JdzI6wy_n',
					'response' => $_REQUEST['g-recaptcha-response'] ?? '',
				];
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify");
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$server_output = curl_exec($ch);
				curl_close ($ch);
				$server_output = json_decode($server_output,true);
				$isCaptchaValid = isset($server_output['success']) && ($server_output['success'] == true) && (($server_output['hostname'] ?? '') == 'kodexcc.com');
			}
			
			if($isCaptchaValid)
			{
				if((strtolower($ipaddr['country']) == 'Australia') || (strtolower($ipaddr['country_code']) == 'in'))
				{	
						
							
						$name =  $this->request->data['name'];
						$lname =  $this->request->data['lname'];
						$email =  $this->request->data['email'];
						$phone =  $this->request->data['phone'];
						$body =  $this->request->data['message'];
						
						$contact = array();
						$contact['Contact']['name'] = $name.' '.$lname;
						$contact['Contact']['email'] = $email;
						$contact['Contact']['phone'] = $phone;
						$contact['Contact']['message'] = $body;
						$contact['Contact']['created'] = date('Y-m-d H:i:s');			
						$this->Contact->save($contact);
						
						$html = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta name="viewport" content="width=device-width"/> <style type="text/css">* {  margin: 0;  padding: 0;  font-size: 100%;  font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;  line-height: 1.65; }img {  max-width: 100%;  margin: 0 auto;  display: block; }body,.body-wrap {  width: 100% !important;  height: 100%;  background: #efefef;  -webkit-font-smoothing: antialiased;  -webkit-text-size-adjust: none; }a {  color: #71bc37;  text-decoration: none; }.text-center {  text-align: center; }.text-right {  text-align: right; }.text-left {  text-align: left; }.button {  display: inline-block;  color: white;  background: #71bc37;  border: solid #71bc37;  border-width: 10px 20px 8px;
							font-weight: bold;  border-radius: 4px; }h1, h2, h3, h4, h5, h6 {  margin-bottom: 20px;  line-height: 1.25; }h1 {  font-size: 32px; }h2 {  font-size: 28px; }h3 {  font-size: 24px; }h4 {  font-size: 20px; }h5 {  font-size: 16px; }p, ul, ol {  font-size: 16px;  font-weight: normal;  margin-bottom: 20px; }.container {  display: block !important;  clear: both !important;  margin: 0 auto !important;  max-width: 580px !important; }.container table {    width: 100% !important;    border-collapse: collapse; }.container .masthead {  padding: 80px 0;    background: #e2e2e2;    color: white; }.container .masthead h1 {      margin: 0 auto !important;      max-width: 90%;      text-transform: uppercase; }.container .content {    background: white;    padding: 30px 35px; }.container .content.footer {      background: none; }.container .content.footer p {    margin-bottom: 0;        color: #888;        text-align: center; font-size: 14px; }.container .content.footer a {        color: #888;        text-decoration: none;        font-weight: bold; }</style></head><body><table class="body-wrap"><tr><td class="container"><table><tr><td align="center" class="masthead"><img src="https://kodexcc.com/wp-content/themes/enviro/img/kodex-blue-logo.png"></td></tr>
							<tr><td class="content">
							<h2>Hi Kal,</h2>
							<p>Here is the contact us detail: .</p>						
							<p>Name: '.$name.' '.$lname.'</p>
							<p>Email: '.$email.'</p>
							<p>Phone: '.$phone.'</p>                      
							<p></p>
							<p>Message: '.$body.'</p>   
							<p>Thanks.</p>
							<p>KodexGlobal</p>
							</td>
							</tr>
							</table>
							</td>
							</tr>   
							</table>
							</body>
						</html>';		

						try{
							$subject = SITENAME.' :: Contact Us';
							$to = 'info@kodexcc.com';
                            (new Mailer())
                                ->setTransport('default')
                                ->setFrom(['info@kodexcc.com' => 'Kodexcc'])
                                ->setTo($to)
                                ->setSubject($subject)
                                ->setEmailFormat('html')
                                ->deliver($html);
						} catch (\Throwable $e) {
                            // Do not block form submission if email sending fails.
						}
						$this->Session->setFlash('Your mesage has been sent successfully.','default',array('class' => 'alert alert-success'));
						$this->redirect('/contact-us');						
					
				}else{
					
					$this->Session->setFlash('Your mesage has been sent successfully.','default',array('class' => 'alert alert-error'));
					$this->redirect('/contact-us');
				} 
			}else{
				
				$this->Session->setFlash('Please verify captcha','default',array('class' => 'alert alert-error'));
				$this->redirect('/contact-us');
			} 
		}
		
		$ranStr = md5(microtime());
		$ranStr = substr($ranStr, 0, 6);
		$this->Session->write('cap_code',$ranStr);
		
		$this->Session->write('session',1);
		
		$this->set('meta_title','Contact Us for Professional Sealants & Waterproofing Solutions in Australia. Call us opening hours 1800 418 495');
		$this->set('meta_description','Based company providing professional services for waterproofing and sealing solutions. Contact us for more information and a free quote.');
		$this->set('meta_keyword','Sealants, Primer, Waterproofing Solutions, Contact Us, Australia');
		
		
		
	}
	
		
	public function documents_brochures()
	{
		$this->viewBuilder()->setLayout('customkggc_layout');
	
		$this->set('meta_title','High-Quality Sealants, Primers, and Waterproofing Solutions: The Ultimate Brochures for Australia');
		$this->set('meta_description','based kodex specializing in high-quality sealants, primers, and waterproofing solutions for all your building needs. Our products are designed to provide long-lasting protection and durability. Browse our range of products and order online today.');
		$this->set('meta_keyword','Sealants, Primer, Waterproofing Solutions, Australia, Documents, Brochures, Building Materials, Construction, Home Improvement');
	}	

    // CakePHP 5 maps dashed routes to camelCase actions.
    public function documentsBrochures()
    {
        return $this->documents_brochures();
    }
	
	public function product_data_sheets()
	{
		$this->viewBuilder()->setLayout('customkggc_layout');
	
		$this->set('meta_title','Product Data Sheets - Kodex');
		$this->set('meta_description','');
		$this->set('meta_keyword','');
		
		$rows = $this->Category->find()
            ->where(['Category.status' => 1])
            ->contain(['Product' => function ($q) {
                return $q->where(['Product.status' => 1]);
            }])
            ->orderBy(['Category.category_name' => 'ASC'])
            ->enableHydration(false)
            ->toArray();
        $category = array_map(function ($row) {
            return [
                'Category' => $row,
                'Product' => $row['product'] ?? [],
            ];
        }, $rows);
		$this->set('category', $category);
		
		$this->set('meta_title','Sealants and Waterproofing Solutions - Product Data Sheets for Australian Customers');
		$this->set('meta_description','kodex specializing in the manufacture and supply of high-quality sealants and waterproofing solutions for various applications. Our product data sheets provide comprehensive information on product specifications, performance, and usage instructions. Trust us to protect your buildings and structures from the elements.');
		$this->set('meta_keyword','Sealants and Waterproofing Solutions, Australian kodex, High-Quality, Effective, Wide Range of Applications.');
		
	}

    public function productDataSheets()
    {
        return $this->product_data_sheets();
    }
	
	
	public function product_msds()
	{
		$this->viewBuilder()->setLayout('customkggc_layout');
	
		$this->set('meta_title','Product MSDS - Kodex');
		$this->set('meta_description','');
		$this->set('meta_keyword','');
		
		$rows = $this->Category->find()
            ->where(['Category.status' => 1])
            ->contain(['Product' => function ($q) {
                return $q->where(['Product.status' => 1]);
            }])
            ->orderBy(['Category.category_name' => 'ASC'])
            ->enableHydration(false)
            ->toArray();
        $category = array_map(function ($row) {
            return [
                'Category' => $row,
                'Product' => $row['product'] ?? [],
            ];
        }, $rows);
		$this->set('category', $category);
		
		$this->set('meta_title','Sealants and Waterproofing Solutions - Product Data Sheets for Australian Customers');
		$this->set('meta_description','kodex specializing in the manufacture and supply of high-quality sealants and waterproofing solutions for various applications. Our product data sheets provide comprehensive information on product specifications, performance, and usage instructions. Trust us to protect your buildings and structures from the elements.');
		$this->set('meta_keyword','Sealants and Waterproofing Solutions, Australian kodex, High-Quality, Effective, Wide Range of Applications.');
		
	}

    public function productMsds()
    {
        return $this->product_msds();
    }
		
	/***
	/*Author  :Ranjit,
	/*Comment :Contact Page
	****/
	public function change_currency($type=null){
		$this->autoRender=false;
		if($type=='Euro €')
		{
			$this->Session->write('currency','euro');
		}else{
			$this->Session->write('currency','dollar');
		}
		
	}

    public function changeCurrency($type = null)
    {
        return $this->change_currency($type);
    }
	
	/***
	/*Author  :Ranjit,
	/*Comment :Login/Signup Page
	****/
	public function login()
	{
		$this->viewBuilder()->setLayout('front_layout');
		$this->set('title_for_layout',SITENAME.' Login');
		if(!empty($this->request->data)){
			
			//signup process
			if(isset($this->request->data['signup']))
			{
				//check email if already exist
				$customerEmail = $this->Customer->find('first',array('conditions'=>array('Customer.email'=>$this->request->data['Customer']['email'])));
				if(!empty($customerEmail))
				{
					$this->Session->setFlash('Email id is already exist','default',array('class' => 'alert alert-danger'));
					$this->redirect('/login');
				}
				$this->request->data['Customer']['unique_id']= $this->random_password(10);
				$this->request->data['Customer']['status']=1;
				if($this->Customer->save($this->request->data))
				{
					$to=$this->request->data['Customer']['email'];
					$name=$this->request->data['Customer']['fname'];
					$password=$this->request->data['Customer']['password'];
					$subject = SITENAME.' :: Account Registered';	
					$template_name='message';
					$top_content = 'Your account has been created successfully.Here is your login account detail :';
					$variables=array('password'=>$password,'top_content'=>$top_content,'name'=>$name,'email'=>$to,'type'=>'signup');
					$this->sendemail($to,$subject,$template_name,$variables);
					$this->Session->setFlash('Register successfully.','default',array('class' => 'alert alert-success'));
					$this->redirect('/login');
				}
				
			}
			
			//login process
			if(isset($this->request->data['login']))
			{
				$email = $this->request->data['Customer']['email'];
				$password = $this->request->data['Customer']['password'];
			
				$customer = $this->Customer->find('first',array('conditions'=>array('Customer.email'=>$email,'Customer.password'=>$password)));
				if(!empty($customer)){
					$this->Session->write('customer',$customer['Customer']);   
					$this->Session->write('is_customer',1);   
					$this->redirect('/profile'); 
				}else{
					$this->Session->setFlash('Wrong email or password.','default',array('class' => 'alert alert-danger'));
				} 
			}
			
		}			
	}
	
	function send_subscriber()
	{
		$data = (array)$this->getRequest()->getData();
        if (!empty($data)) {
            $email = $data['Subscriber']['email'] ?? null;
            if ($email) {
                $existing = $this->Subscriber->find()
                    ->where(['Subscriber.email' => $email])
                    ->enableHydration(false)
                    ->first();
                if (empty($existing)) {
                    $entity = $this->Subscriber->newEntity($data['Subscriber'] ?? []);
                    $this->Subscriber->save($entity);
                }
            }
            return $this->redirect('index');
        }
	}
	
	/***
	/*Author  :Ranjit,
	/*Comment :Admin Notification list
	****/
	public function admin_index(){		
		$this->viewBuilder()->setLayout('admin_layout');
		$this->set('title_for_layout',SITENAME.' Notification');		
		$this->checkAdminSession(); 
		
		$this->Notification->bindModel(
		array('belongsTo' => array('Product' => array(
			'className' => 'Product',			 
			'foreignKey' => 'product_id',				
			'conditions' => array(),
			'fields' => '',
			'order' => array(),
		))));
				
		$this->Notification->recursive = 0;
		$this->paginate = array('page' => 1, 'limit' => 25);
		$notification = $this->Paginator->paginate('Notification');
		// echo '<pre>';	
		// print_r($notification);
		// die();	
		$this->set('notification', $notification);		
		
		
	}
	
	public function admin_send(){		
		$this->viewBuilder()->setLayout('admin_layout');
		$this->set('title_for_layout',SITENAME.' Notification');		
		$this->checkAdminSession(); 
	
		if(!empty($this->request->data)){
			
			$type = $this->request->data['type'];
			$title = $this->request->data['title'];
			$description = $this->request->data['description'];
			$product_id = $this->request->data['product_id'];
			
			if(!empty($_FILES['image']['name'])){
			
				$file = $_FILES['image'];
				$filename = $file['name'];
				$tmp_name = $file['tmp_name'];			
				$ext = pathinfo($filename, PATHINFO_EXTENSION);			
				$oringinalfilename = time().'.'.$ext;
				move_uploaded_file($tmp_name,'product_image/'.$oringinalfilename);					
			}else{
				$oringinalfilename = '';
			}

			$created = date('Y-m-d H:i:s');
			$notificationArr['Notification']['type'] = $type;
			$notificationArr['Notification']['product_id'] = $product_id;
			$notificationArr['Notification']['title'] = $title;
			$notificationArr['Notification']['description'] = $description;
			$notificationArr['Notification']['image'] = $oringinalfilename;
			$notificationArr['Notification']['created'] = $created;
			$this->Notification->save($notificationArr);
			
			$deviceArr =  $this->Device->find('all');
			
			if(!empty($deviceArr)){
				foreach($deviceArr as $deviceArrs){
					$device_token = $deviceArrs['Device']['device_token'];
					$device_type = $deviceArrs['Device']['device_type'];
					if($device_type == 'android'){					
					//send a notifiation				
						$result = $this->send_android_notification($device_token, $title);					
					}else if($device_type == 'IOS'){						
						//$result =  $this->send_ios_notification($device_token, $title);	
						//$device_token = 'abaeaa3f0ef16abda5593d1bd2b4674bf9d33d912d80d2210d2bee2d732b402f';							
						$url = SITEURL."sendpush.php";						
						$fields = array(
							'deviceToken' => urlencode($device_token),
							'message' => urlencode($title),							
						);
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
					}	
				}	
			}
			$this->Session->setFlash('Notification has been sent.','default',array('class' => 'alert alert-success'));	
			return $this->redirect(array('action' => 'index'));
		}	
		$Product = $this->Product->find('all',array('conditions'=>array('Product.status'=>1)));
		$this->set('product', $Product);
	}
	
	public function admin_delete($id = null) {
		$this->autoRender = false;
		
		$this->checkAdminSession(); 
		$this->Notification->id = $id;
		if (!$this->Notification->exists()) {
			throw new NotFoundException(__('Invalid Notification'));
		}
		//$this->request->allowMethod('post', 'delete');
		if ($this->Notification->delete()) {
			$this->Session->setFlash('The Notification has been deleted.','default',array('class' => 'alert alert-success'));
		} else {
			$this->Session->setFlash('The Notification could not be deleted.Please, try again.','default',array('class' => 'alert alert-danger'));
		}
		return $this->redirect(array('action' => 'index'));
	}
	function send_android_notification($registration_ids, $message) {
		
		$dat2  =array("message"=>$message);
		$fields = array(
		'registration_ids' => array($registration_ids),
		'data'=> $dat2,
		);

		$headers = array(
		'Authorization: key=AAAABZjbTLg:APA91bG1bzcB06uwvOzNR7-_YJGbJWpKXG7AgcuSeLlbP4YG0C8wz_7L6YYTrh1eYK0V331ESf-2gaxOw4Mnrp3hGQzj3dKepWfKJOmDvIkq1_QjuElsfuyhRiy7RS8Vw_fqwdStEdan', // FIREBASE_API_KEY_FOR_ANDROID_NOTIFICATION
		'Content-Type: application/json'
		);
		// Open connection
		$ch = curl_init();
		// Set the url, number of POST vars, POST data
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		// Disabling SSL Certificate support temporarly
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		// Execute post
		$result = curl_exec($ch );
		if($result === false){
		die('Curl failed:' .curl_errno($ch));
		}
		// Close connection
		curl_close( $ch );
		return $result;
	}	
	
	function send_ios_notification($deviceToken,$message){
		
		
		$filepath = $_SERVER['DOCUMENT_ROOT'].'/proPush.pem'; 
		$deviceToken = 'abaeaa3f0ef16abda5593d1bd2b4674bf9d33d912d80d2210d2bee2d732b402f';	
				
		$passphrase = '12345';
		//$filepath  = SITEURL.'proPush.pem';
		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', $filepath);
		stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
		// Open a connection to the APNS server
		$fp = stream_socket_client(
		// 'ssl://gateway.push.apple.com:2195', $err,  // For development
		'ssl://gateway.push.apple.com:2195', $err, // for production
		$errstr, 1000, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

		if (!$fp)
		exit("Failed to connect: $err $errstr" . PHP_EOL);
		//echo 'Connected to APNS' . PHP_EOL;
		// Create the payload body
		$body['aps'] = array(
		'alert' => trim($message),
		'sound' => 'default'
		);
		// Encode the payload as JSON
		$payload = json_encode($body);
		// Build the binary notification
		$msg = chr(0) . pack('n', 32) . pack('H*', trim($deviceToken)) . pack('n', strlen($payload)) . $payload;
		// Send it to the server
		$result = fwrite($fp, $msg, strlen($msg));	
	
		// Close the connection to the server
		fclose($fp);
		return 'success';
	}
	
	
	public function newlead(){
		$this->viewBuilder()->setLayout('admin_login');
		$this->set('title_for_layout',SITENAME.':- New Lead');			
		
		if(!empty($this->request->data)){
			
			$callername = $this->request->data['callername'];
			$calltime = $this->request->data['calltime'];
			$phone = $this->request->data['phone'];
			$message = $this->request->data['message'];
			
			$this->SsSheetCategory->bindModel(
			array('hasMany' => array('SsSheetColumn' => array(
				'className' => 'SsSheetColumn',			  
				'foreignKey' => 'sheet_id',				
				'conditions' => array(),
				'fields' => '',
				'order' => array(),
			))));	
			$this->SsSheetCategory->bindModel(
			array('hasMany' => array('SsSheetShare' => array(
				'className' => 'SsSheetShare',			  
				'foreignKey' => 'sheet_id',				
				'conditions' => array(),
				'fields' => '',
				'order' => array(),
			))));

			
			
			$this->SsSheetCategory->recursive = 3;
			$SsSheetArr = $this->SsSheetCategory->find('first',array('conditions'=>array('SsSheetCategory.name'=>'TMC Leads')));
			if(!empty($SsSheetArr)){
				$sheetname = $SsSheetArr['SsSheetCategory']['name'];
				$taskId = rand(0000,9999);
				
				$SheetTaskCreateArr = $this->SheetTaskCreate->find('first',array('conditions'=>array('SheetTaskCreate.sheet_id'=>$sheet_id,'SheetTaskCreate.task_id'=>$taskId)));
				if(empty($SheetTaskCreateArr)){
					$insert['SheetTaskCreate']['sheet_id'] = $SsSheetArr['SsSheetCategory']['id'];
					$insert['SheetTaskCreate']['task_id'] = $taskId;
					$this->SheetTaskCreate->save($insert);
				}
				
				foreach($SsSheetArr['SsSheetColumn'] as $SsSheetColumn){
					
					$insert['SheetTask']['id'] = '';
					$insert['SheetTask']['sheet_cate_id'] = $SsSheetArr['SsSheetCategory']['id'];
					$insert['SheetTask']['task_id'] = $taskId;
					$insert['SheetTask']['created'] = date('Y-m-d H:i:s');
					
					if(isset($SsSheetColumn['column']) && ($SsSheetColumn['column'] == 'Caller Name')){
						$insert['SheetTask']['column_id'] = $SsSheetColumn['id'];
						$insert['SheetTask']['value'] = $callername;
					}
					if(isset($SsSheetColumn['column']) && ($SsSheetColumn['column'] == 'Calltime')){
						$insert['SheetTask']['column_id'] = $SsSheetColumn['id'];
						$insert['SheetTask']['value'] = $calltime;
					}	
					if(isset($SsSheetColumn['column']) && ($SsSheetColumn['column'] == 'Message')){
						$insert['SheetTask']['column_id'] = $SsSheetColumn['id'];
						$insert['SheetTask']['value'] = $message;
					}
					if(isset($SsSheetColumn['filed_type']) && ($SsSheetColumn['filed_type'] == 'is_reminder')){
						$insert['SheetTask']['column_id'] = $SsSheetColumn['id'];
						$insert['SheetTask']['value'] = 1;
					}
					if(isset($SsSheetColumn['filed_type']) && ($SsSheetColumn['filed_type'] == 'Dropdown')){
						$insert['SheetTask']['column_id'] = $SsSheetColumn['id'];
						$insert['SheetTask']['value'] = 'Empty';
					}
					if(isset($SsSheetColumn['filed_type']) && ($SsSheetColumn['filed_type'] == 'mobile')){
						$insert['SheetTask']['column_id'] = $SsSheetColumn['id'];
						$insert['SheetTask']['value'] = $phone;
					}	
					if(isset($SsSheetColumn['filed_type']) && ($SsSheetColumn['filed_type'] == 'assigned')){
						$insert['SheetTask']['column_id'] = $SsSheetColumn['id'];
						isset($SsSheetArr['SsSheetShare'][0]['emp_id'])? $emp_id = $SsSheetArr['SsSheetShare'][0]['emp_id'] :  $emp_id = 0;
						$insert['SheetTask']['value'] = $emp_id;
					}	
					$this->SheetTask->save($insert);
				}	
				
				
				
				if(!empty($SsSheetArr['SsSheetShare'])){
					foreach($SsSheetArr['SsSheetShare'] as $SsSheetShares){
						$emp_id = $SsSheetShares['emp_id'];
						$NappUserArr = $this->NappUser->find('first',array('conditions'=>array('NappUser.id'=>$emp_id),'fields'=>array('id','name','lname','email')));
						if(!empty($NappUserArr)){
							$to_name = $NappUserArr['NappUser']['name'].' '.$NappUserArr['NappUser']['lname'];
							$to_email = $NappUserArr['NappUser']['email'];
							
							$NappUserId = base64_encode(base64_encode($NappUserArr['NappUser']['id']));
							$sheetId = base64_encode(base64_encode($SsSheetArr['SsSheetCategory']['id']));
							$url = SITEURL.'sheets/sheetdetail/'.$sheetId.'/'.$NappUserId.'/'.$taskId; 
							$subject = SITENAME.':- New Lead (#'.$taskId.') From '.$sheetname;	
							$template_name = 'tmclead';									
							$variables = array('sheetname'=>$sheetname,'to_name'=>$to_name,'task_id'=>$taskId,'subject'=>$subject,'url'=>$url);								
							try{
								$this->sendemail($to_email,$subject,$template_name,$variables);
							}catch (Exception $e){
								
							}							
						}	
					}	
				}
				
				$userArr = $this->User->find('all',array('conditions'=>array('User.is_receive_smartsheet'=>1)));
				if(!empty($userArr)){	
					foreach($userArr as $userArrs){						
						$userid = $userArrs['User']['id'];	
						$sheet_cate_id = $SsSheetArr['SsSheetCategory']['id'];						
						$url = SITEURL.'admin/sheets/sheetdetail/'.$userid.'/'.$sheet_cate_id.'/'.$taskId;	
						$to_name = $userArrs['User']['name'];
						$to_email = $userArrs['User']['email'];			 					
						$subject = SITENAME.':- New Lead (#'.$taskId.') From '.$sheetname;						
						$template_name = 'sheetattach';									
						$variables = array('sheetname'=>$sheetname,'to_name'=>$to_name,'task_id'=>$taskId,'subject'=>$subject,'url'=>$url);		
						try{
							$this->sendemail($to_email,$subject,$template_name,$variables);
						}catch (Exception $e){
							
						}						
					}	
				}
				
				$this->Session->setFlash('This Lead saved successfully.','default',array('class' => 'alert alert-success'));		
				return $this->redirect('/tmclead');
			}
		}	
	}
	
	
	
	public function feedback(){
		
		$this->viewBuilder()->setLayout('admin_login');
		$this->set('title_for_layout',SITENAME.' | Sample Feedback Form');
			
		if(!empty($this->request->data)){
			$insert['Feedback']['addedby'] = $this->request->data['Feedback']['addedby'];			
			$insert['Feedback']['customer_name'] = $this->request->data['Feedback']['customer_name'];			
			$insert['Feedback']['company_name'] = $this->request->data['Feedback']['company_name'];			
			$insert['Feedback']['contact'] = $this->request->data['Feedback']['contact'];			
			$insert['Feedback']['sample_given'] = $this->request->data['Feedback']['sample_given'];			
			$insert['Feedback']['feedback'] = $this->request->data['Feedback']['feedback'];			
			$insert['Feedback']['created'] = date('Y-m-d H:i:s');
			
			$this->Feedback->save($insert);
			$this->Session->setFlash('Successfully saved in database.','default',array('class' => 'alert alert-success'));		
			return $this->redirect('/feedback');
		}
	}
	public function questionary(){
		
		$this->viewBuilder()->setLayout('admin_login');
		$this->set('title_for_layout',SITENAME.' | Questionnaire Page');
			
		if(!empty($this->request->data)){
			$insert['Salemeet']['addedby'] = $this->request->data['Front']['addedby'];
			$insert['Salemeet']['company_name'] = $this->request->data['Front']['company_name'];
			$insert['Salemeet']['name'] = $this->request->data['Front']['name'];
			$insert['Salemeet']['phone'] = $this->request->data['Front']['phone'];
			$insert['Salemeet']['email'] = $this->request->data['Front']['email'];
			$insert['Salemeet']['occupation'] = $this->request->data['Front']['occupation'];
			$insert['Salemeet']['existing'] = $this->request->data['Front']['existing'];
			$insert['Salemeet']['interest'] = $this->request->data['Front']['interest'];
			$insert['Salemeet']['location'] = $this->request->data['Front']['location'];
			$insert['Salemeet']['created'] = date('Y-m-d H:i:s');
			
			$this->Salemeet->save($insert);
			$this->Session->setFlash('Successfully saved in database.','default',array('class' => 'alert alert-success'));		
			return $this->redirect('/questionary');
		}		
	}
		
	
	function analytics(){
		$this->autoRender = false;
		
		isset($_REQUEST['ip'])? $ip = $_REQUEST['ip']:$ip='';
		isset($_REQUEST['city'])? $city = $_REQUEST['city']:$city='';
		isset($_REQUEST['state'])? $region = $_REQUEST['state']:$region='';
		isset($_REQUEST['country'])? $country = $_REQUEST['country']:$country='';
		isset($_REQUEST['loc'])? $loc = $_REQUEST['loc']:$loc='';
		isset($_REQUEST['postal'])? $postal = $_REQUEST['postal']:$postal='';
		
		$insert['Analytic']['id'] =  ''; 
		$insert['Analytic']['ip'] =  $ip; 
		$insert['Analytic']['city'] =  $city; 
		$insert['Analytic']['region'] =  $region; 
		$insert['Analytic']['country'] =  $country; 
		$insert['Analytic']['loc'] =  $loc; 
		$insert['Analytic']['postal'] =  $postal; 
		$insert['Analytic']['created'] =  date('Y-m-d H:i:s'); 
		$this->Analytic->save($insert);
		echo 'success';
	}
	
	function getcountry()
	{
		$this->autoRender = false;
		$country = $this->ip_info($_SERVER['REMOTE_ADDR'], "Country");
		if(strtolower($country) == 'australia')
		{
			
		}
	}
	
	function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
		$output = NULL;
		if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
			$ip = $_SERVER["REMOTE_ADDR"];
			if ($deep_detect) {
				if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
					$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
				if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
					$ip = $_SERVER['HTTP_CLIENT_IP'];
			}
		}
		$purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
		$support    = array("country", "countrycode", "state", "region", "city", "location", "address");
		$continents = array(
			"AF" => "Africa",
			"AN" => "Antarctica",
			"AS" => "Asia",
			"EU" => "Europe",
			"OC" => "Australia (Oceania)",
			"NA" => "North America",
			"SA" => "South America"
		);
		if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
			$ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
			if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
				switch ($purpose) {
					case "location":
						$output = array(
							"city"           => @$ipdat->geoplugin_city,
							"state"          => @$ipdat->geoplugin_regionName,
							"country"        => @$ipdat->geoplugin_countryName,
							"country_code"   => @$ipdat->geoplugin_countryCode,
							"continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
							"continent_code" => @$ipdat->geoplugin_continentCode
						);
						break;
					case "address":
						$address = array($ipdat->geoplugin_countryName);
						if (@strlen($ipdat->geoplugin_regionName) >= 1)
							$address[] = $ipdat->geoplugin_regionName;
						if (@strlen($ipdat->geoplugin_city) >= 1)
							$address[] = $ipdat->geoplugin_city;
						$output = implode(", ", array_reverse($address));
						break;
					case "city":
						$output = @$ipdat->geoplugin_city;
						break;
					case "state":
						$output = @$ipdat->geoplugin_regionName;
						break;
					case "region":
						$output = @$ipdat->geoplugin_regionName;
						break;
					case "country":
						$output = @$ipdat->geoplugin_countryName;
						break;
					case "countrycode":
						$output = @$ipdat->geoplugin_countryCode;
						break;
				}
			}
		}
		return $output;
	}
	
	
}