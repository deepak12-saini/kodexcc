<?php
declare(strict_types=1);

$path = dirname(__DIR__) . '/src/Controller/Admin/UsersController.php';
$f = file_get_contents($path);

$f = preg_replace('/function\s+admin_/', 'function ', $f);
$f = preg_replace("/\\\$this->layout='([^']+)';/", '$this->viewBuilder()->setLayout(\'$1\');', $f);

// Whole-body assignments (before generic request->data replacement)
$f = str_replace('$this->request->data = $user;', '$this->setRequestData(is_array($user) ? $user : []);', $f);
$f = str_replace('$this->request->data = $Config;', '$this->setRequestData(is_array($Config) ? $Config : []);', $f);
$f = str_replace('$this->request->data = $PaymentSetup;', '$this->setRequestData(is_array($PaymentSetup) ? $PaymentSetup : []);', $f);
$f = str_replace('$this->request->data = $staffArr;', '$this->setRequestData(is_array($staffArr) ? $staffArr : []);', $f);
$f = str_replace("\$this->request->data='';", '$this->setRequestData([]);', $f);
$f = str_replace('$this->request->data=\'\';', '$this->setRequestData([]);', $f);

$f = str_replace('$this->request->data', '$this->requestData()', $f);

// admin_login used $this->data
$f = str_replace('!empty($this->data)', '!empty($this->requestData())', $f);
$f = str_replace('$this->data[', '$this->requestData()[', $f);

$actions = [
    'dashboard', 'staff', 'customer', 'salemeet', 'payment_setting', 'web_setting',
    'profile', 'labfile', 'contact', 'permission', 'subscriber_list', 'contactus',
    'loginhisotry', 'quizz', 'change_password', 'add_staff', 'uploadprodcut',
    'customerpermission', 'natspecPresentationStatus', 'cpdPresentationStatus',
    'accesstouser', 'forgot_password', 'edit_staff', 'edit_new_staff', 'updatelabfile',
];
foreach ($actions as $a) {
    $f = str_replace("\$this->redirect('$a');", "\$this->redirect(['action' => '$a']);", $f);
}

$f = preg_replace(
    "/\\\$this->redirect\\('access\\/'.(.*?)\\);/",
    '$this->redirect([\'action\' => \'access\', $1]);',
    $f
);

file_put_contents($path, $f);
echo "Updated $path\n";
