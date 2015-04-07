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



  <!-- CSS -->
  <link href="css/metro-bootstrap.css" rel="stylesheet">
  <link href="css/metro-bootstrap-responsive.css" rel="stylesheet">
  <link href="css/iconFont.css" rel="stylesheet">
  <link href="css/docs.css" rel="stylesheet">
  <link href="js/prettify/prettify.css" rel="stylesheet">

  <!-- Load JavaScript Libraries -->
  <script src="js/jquery/jquery.min.js"></script>
  <script src="js/jquery/jquery.widget.min.js"></script>
  <script src="js/jquery/jquery.mousewheel.js"></script>
  <script src="js/prettify/prettify.js"></script>

  <!-- Metro UI CSS JavaScript plugins -->
  <script src="js/load-metro.js"></script>

  <!-- Local JavaScript -->
  <script src="js/docs.js"></script>

  <!-- SCRIPT -->
  <script type="text/javascript">

  </script>

</head>

<body class="metro">

    <!-- INCLUDE BODY -->
    <?php include('body.php'); ?>

    <script src="js/hitua.js"></script>
</body>

</html>