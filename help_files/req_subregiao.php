<?php
require_once __DIR__ . '/vendor/autoload.php';

$id = $_REQUEST['id'];

$result = classes\SubRegiao::listar(array('id_subregiao','subregiao'), array("id_regiao"=>$id), 'select');
echo $result;
