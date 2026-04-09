<?php
declare(strict_types=1);

$path = dirname(__DIR__) . '/src/Controller/UsersController.php';
$f = file_get_contents($path);

$f = preg_replace("/\\\$this->layout='([^']+)';/", '$this->viewBuilder()->setLayout(\'$1\');', $f);

$f = str_replace('$this->request->data = $user;', '$this->setRequestData(is_array($user) ? $user : []);', $f);
$f = str_replace("\$this->request->data='';", '$this->setRequestData([]);', $f);

$f = str_replace('$this->request->data', '$this->requestData()', $f);

$f = str_replace('!empty($this->data)', '!empty($this->requestData())', $f);
$f = str_replace('$this->data[', '$this->requestData()[', $f);

file_put_contents($path, $f);
echo "Updated $path\n";
