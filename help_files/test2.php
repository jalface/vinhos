<?php
require_once __DIR__ . '/vendor/autoload.php';
session_start();

//phpinfo();
?>

<!-- LOGIN -->

<?php

if (isset($_SESSION['user'])) {
    //logged in
}

if (isset($_POST['btn_login'])) {
    # code...
    $username = $_POST['username'];
    $password = $_POST['password'];

    $User = new User($username, $password);
    if ($User->login()) {
        $_SESSION['user'] = $User->getusername();
        $_SESSION['permissoes'] = $User->getPermissoes();
        header('Location: '. __DIR__ . '/index.php');
        die;
    } else {
        echo "Invalid username or Password";
    }
}

if (isset($_POST['btn_register'])) {
    # code...
    $username = $_POST['username'];
    $password = $_POST['password'];

    $User = new User($username, $password);
    if ($User->register()) {
        $User->login();
        $_SESSION['user'] = $User->getusername();
        $_SESSION['permissoes'] = $User->getPermissoes();
        header('Location: '. __DIR__ . '/index.php');
        die;
    } else {
        echo "Register Failed";
    }
}

if (isset($_POST['btn_logout'])) {
    session_start();
    setcookie(session_name(), '', 100);
    session_unset();
    session_destroy();
    $_SESSION = array();
    header('Location: '. __DIR__ .'/index.php');
    die;
}
?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title></title>
  <link rel="stylesheet" href="">
</head>
<body>
  <div class="container">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" accept-charset="utf-8">
      <label>select: Pais</label>
      <select name="pais" id="pais_Box" onchange="getRegiao();">
          <option>Paises</option>
            <?php echo classes\Pais::listar(array('id_pais', 'pais'), array(), 'select'); ?>
      </select>
      <script type="text/javascript">
      var result = "";
          function getRegiao(){
              var e = document.getElementById("pais_Box");
              var id = e.options[e.selectedIndex].value;
              $.post("req_pais.php", {id: id}, function(data){
              document.getElementById("regiao_box").innerHTML = "<option> </option>" + data;
            });
        }

          function getSubRegiao(){
              var e = document.getElementById("regiao_box");
              var id = e.options[e.selectedIndex].value;
              $.post("req_subregiao.php", {id: id}, function(data){
              document.getElementById("subRegiao_Box").innerHTML =  "<option> </option>" + data;
            });
        }
      </script>

      <br>

      <label>select: Regiao</label>
      <select name="regiao" id="regiao_box" onchange="getSubRegiao();"></select>

      <br>

      <label>select: Sub Regiao</label>
      <select name="subRegiao" id="subRegiao_Box"></select>

    </form>
  </div>
  <script src="http://code.jquery.com/jquery-1.11.2.min.js"></script>
</body>
</html>