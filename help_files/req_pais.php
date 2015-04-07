<?php
require_once __DIR__ . '/vendor/autoload.php';

$id = $_REQUEST['id'];

$result = classes\Regiao::listar(array('id_regiao','regiao'), array("id_pais"=>$id), 'select');
echo $result;
