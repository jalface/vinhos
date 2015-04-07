<?php
function __autoload($class_name) {
    if(file_exists('../class/class.'.$class_name . '.php')) {
        require_once('../class/class.'.$class_name.'.php');
    } else {
        throw new Exception("Unable to load $class_name.");
    }
}

$caption = $_REQUEST['caption'];

$cati = new cat_ingrediente($caption,"","","");
$catid = $cati->catid();

$con = new dbconnection();

$query = "SELECT DISTINCT caption FROM ingredientes WHERE id_cati = '$catid' ";
$result = $con->createSelectBox($query,"","recipe-ingredient","recipe-ingredient[]","caption","caption","","enabled");

echo $result;

?>