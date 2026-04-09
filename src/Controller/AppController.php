<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use App\Model\LegacyModelAdapter;
use Cake\Controller\Controller;

class LegacySessionAdapter
{
    private Controller $controller;

    public function __construct(Controller $controller)
    {
        $this->controller = $controller;
    }

    public function read(string $key): mixed
    {
        return $this->controller->getRequest()->getSession()->read($key);
    }

    public function write(string $key, mixed $value): void
    {
        $this->controller->getRequest()->getSession()->write($key, $value);
    }

    public function delete(string $key): void
    {
        $this->controller->getRequest()->getSession()->delete($key);
    }

    public function destroy(): void
    {
        $this->controller->getRequest()->getSession()->destroy();
    }

    public function setFlash(string $message, string $element = 'default', array $options = []): void
    {
        $this->controller->Flash->set($message, ['element' => $element] + $options);
    }
}

class LegacyPaginatorAdapter
{
    private Controller $controller;

    public function __construct(Controller $controller)
    {
        $this->controller = $controller;
    }

    public function paginate(string|object $target): mixed
    {
        if (is_string($target)) {
            $table = $this->controller->fetchTable($target);
            return $this->controller->paginate($table);
        }

        return $this->controller->paginate($target);
    }
}

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/5/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    public LegacySessionAdapter $Session;
    public LegacyPaginatorAdapter $Paginator;
    private array $legacyTables = [];

    /**
     * @var array<string, LegacyModelAdapter>
     */
    private array $legacyModelAdapters = [];

    /**
     * Reused legacy adapter per alias so state (e.g. $model->id) survives across property reads.
     */
    protected function legacyModel(string $name): ?LegacyModelAdapter
    {
        if (isset($this->legacyModelAdapters[$name])) {
            return $this->legacyModelAdapters[$name];
        }
        try {
            $this->legacyModelAdapters[$name] = new LegacyModelAdapter($this->fetchTable($name));
        } catch (\Throwable) {
            return null;
        }

        return $this->legacyModelAdapters[$name];
    }

    public function __get(string $name): mixed
    {
        if (isset($this->legacyTables[$name])) {
            return $this->legacyTables[$name];
        }

        if ($this->components()->has($name)) {
            return $this->components()->get($name);
        }

        if (preg_match('/^[A-Z][A-Za-z0-9_]*$/', $name) === 1) {
            try {
                $this->legacyTables[$name] = $this->fetchTable($name);

                return $this->legacyTables[$name];
            } catch (\Throwable) {
                // Fall through — not a table; may be other parent magic props.
            }
        }

        return parent::__get($name);
    }

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('FormProtection');`
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Flash');
        $this->Session = new LegacySessionAdapter($this);
        $this->Paginator = new LegacyPaginatorAdapter($this);

        /*
         * Enable the following component for recommended CakePHP form protection settings.
         * see https://book.cakephp.org/5/en/controllers/components/form-protection.html
         */
        //$this->loadComponent('FormProtection');
    }

    //Function 'checkAdminSession' for admin check in controller
	function checkAdminSession() {
		// if the admin session hasn't been set
		if(!$this->Session->read('is_admin')){
			// set flash message and redirect
			$this->Session->setFlash('You need to be logged in to access this area.','default',array('class' => 'alert alert-danger'));
			$this->redirect('/admin');
			exit();
		}
	}
	
	//Function 'checkCustomerSession' for admin check in controller
	function checkcommonSession() {
		// if the admin session hasn't been set
		if(!$this->Session->read('Customer')){
			// set flash message and redirect
			$this->Session->setFlash('Sorry, you have no access. Please login with user account.','default',array('class' => 'alert alert-danger'));
			$this->redirect('/login');
			exit();
		}
	}
	
	//Function 'checkCustomerSession' for admin check in controller
	function checkCustomerSession() {
		// if the admin session hasn't been set
		if(!$this->Session->read('is_customer')){
			// set flash message and redirect
			$this->Session->setFlash('Sorry, you have no user account access. Please login with user account.','default',array('class' => 'alert alert-danger'));
			$this->redirect('/login');
			exit();
		}
	}
	//Function 'checkStaffession' for admin check in controller
	function checkSatffSession() {
		// if the admin session hasn't been set
		if(!$this->Session->read('is_staff')){
			// set flash message and redirect
			$this->Session->setFlash('Sorry, you have no staff account access. Please login with staff account.','default',array('class' => 'alert alert-danger'));
			$this->redirect('/login');
			exit();
		}
	}
	
	function chkuserpermission(){
		$user_id = $this->Session->read('Customer.id');
		$userPermissions = $this->fetchTable('UserPermission');
		return $userPermissions->find('list', [
            'conditions' => ['UserPermission.user_id' => $user_id],
            'valueField' => 'permssion_id',
        ])->toArray();
	}
	function getproducttype(){
		$products = $this->fetchTable('Product');
		return $products->find('all', ['conditions' => ['Product.product_type' => 1], 'select' => ['title', 'slug']])->all();
	}
	function getproductbyuse(){
		$products = $this->fetchTable('Product');
		return $products->find('all', ['conditions' => ['Product.product_type' => 2], 'select' => ['title', 'slug']])->all();
	}
	function getCartList(){
		$sessionId = $this->Session->read('cart');
		$items_list=array();
		if(!empty($sessionId)){
			
			$shopCart = $this->fetchTable('ShopCart');
			$items_list = $shopCart->find('all', [
                'conditions' => ['ShopCart.session_id' => $sessionId],
                'contain' => ['Product'],
            ])->all();
		}		
		$this->set('sessionItems',$items_list);
	}
	/**
	 * Mutable POST data (replaces legacy $this->request->data reads).
	 *
	 * @return array<string, mixed>
	 */
	protected function requestData(): array
	{
		$d = $this->getRequest()->getData();
		return is_array($d) ? $d : [];
	}

	/**
	 * Replace parsed body (for legacy code that assigned to $this->request->data).
	 *
	 * @param array<string, mixed> $data
	 */
	protected function setRequestData(array $data): void
	{
		$this->setRequest($this->getRequest()->withParsedBody($data));
	}

	//Function 'callConstants' to define constants
	function callConstants()	{
		$configTable = $this->fetchTable('Config');
		$configs = $configTable->find()->enableHydration(false)->first() ?: [];
        $configRows = $configs['config'] ?? $configs['Config'] ?? [];
		foreach($configRows as $key => $value){
			if(!defined(strtoupper($key))) 
				define(strtoupper($key), $value);
		}

        // Legacy code expects these constants even when config table has no seed data yet.
        if (!defined('SITENAME')) {
            define('SITENAME', 'Kodexcc');
        }
        if (!defined('SITEURL')) {
            $webroot = (string)($this->getRequest()->getAttribute('webroot') ?? '/');
            if ($webroot === '') {
                $webroot = '/';
            }
            if (!str_ends_with($webroot, '/')) {
                $webroot .= '/';
            }
            define('SITEURL', $webroot);
        }
        if (!defined('META_TITLE')) {
            define('META_TITLE', 'Kodexcc');
        }
        if (!defined('META_DESCRIPTION')) {
            define('META_DESCRIPTION', 'Kodexcc waterproofing and sealants');
        }
        if (!defined('META_KEYWORD')) {
            define('META_KEYWORD', 'kodexcc, waterproofing, sealants');
        }
		
	}	
	public function getcate()
	{		
		$categories = $this->fetchTable('Category');
		return $categories->find('all', [
            'conditions' => ['Category.status' => 1],
            'contain' => ['Product' => function ($q) {
                return $q->where(['Product.status' => 1]);
            }],
        ])->all();
	}
	
	/***
	/*Author  :Ranjit,
	/*Comment :get Categories on menu
	****/
	public function getCategories(){
		$categoryTable = $this->fetchTable('Category');
		$categories = $categoryTable->find('all',['conditions'=>['Category.status'=>1],'order'=>['Category.category_name'=>'asc']])->all();
		$this->set('categories',$categories);
	}
	
	public function getCategorienew(){
		$categoryTable = $this->fetchTable('Category');
		$categories = $categoryTable->find('all',['conditions'=>['Category.status'=>1],'order'=>['Category.sort'=>'asc']])->all();
		return $categories;
	}
	public function getcarttotal(){
		$cartTable = $this->fetchTable('Cart');
		$cart_session_id = $this->Session->read('cart_session_id');
		
		$totalcount = 0;
		if(!empty($cart_session_id)){
			$totalcount = $cartTable->find()->where(['Cart.cart_session_id'=>$cart_session_id])->count();
		}		
		return $totalcount;
	}
	
	public function getproduct(){
		$productTable = $this->fetchTable('Product');
		$ProductArr = $productTable->find('all',['select'=>['title']])->all();
		return $ProductArr;
	}	
	
	public function random_password( $length = 8 ) {
		//$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
		//$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%?";
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789";
		$password = substr( str_shuffle( $chars ), 0, $length );
		return $password;
	}
	
		// send message email to client
	function sendemail($to,$subject,$template_name,$variables){
		$this->autoRender = false;
        // TODO: migrate to CakePHP 5 Mailer. Kept as non-fatal placeholder to avoid runtime crash.
        return true;
	}
}
