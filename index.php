<?php
  require_once __DIR__ . '/vendor/autoload.php';

if (isset($_SESSION['user'])) {
    session_start();
    session_regenerate_id(true);
    $option = 'vinho';
} else {
    $option = 'login';
}

?>

<!DOCTYPE html>
<html>
<head>

  <!-- TITLE -->
  <title></title>

  <!-- META -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no">
  <meta name="product" content="">
  <meta name="description" content="">
  <meta name="author" content="">

  <!-- CSS & JS -->

  <!-- SCRIPT -->
  <script type="text/javascript"></script>

</head>

<body class="">
    <div class="container">

        <!-- BANNER -->
        <header></header>

        <!-- NAV -->
        <nav></nav>

        <!-- FORMS \ CONTENT PLACEHOLDER -->
            <?php
            if (isset($option)) {
                switch ($option) {
                    case 'login':
                        include('login.php');
                        break;
                    case 'vinho':
                        include('vinho.php');
                        break;
                    case 'regiao':
                        include('regiao.php');
                        break;
                    default:
                        # code ...
                        break;
                }
            }
            ?>

        <!-- FOOTER -->
        <footer></footer>

    </div>
</body>
</html>