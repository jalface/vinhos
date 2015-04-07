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
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/global.css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

  <!-- SCRIPT -->
  <script type="text/javascript"></script>

</head>

<body class="full" data-spy="scroll" data-target="#main-navbar"  style="padding-top:50px;">

    <!-- NAVBAR -->
    <nav class="navbar navbar-inverse navbar-fixed-top" id="main-navbar" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>

          <a href="index.html" class="navbar-brand">Vinhos!</a>
        </div> <!-- end navbar-header -->

        <div class="collapse navbar-collapse" id="navbar-collapse">
          <a href="#" class="btn btn-danger navbar-btn navbar-right">Logout</a>

          <ul class="nav navbar-nav">
            <li><a href="#section-testimonials">Vinhos</a></li>
            <li><a href="#section-features">Produtores</a></li>
            <li><a href="#section-gallery">Castas</a></li>
            <li><a href="#section-faq">WishList</a></li>
            <li><a href="#section-faq">Noticias</a></li>
            <li><a href="#section-faq">Eventos</a></li>
            <li><a href="#section-contact">Contactos</a></li>
          </ul>
        </div> <!-- end collapse -->
      </div> <!-- end container -->
    </nav>

    <!-- BANNER -->
<!--     <div class="jumbotron">
        <div class="container">
              <h1>Vinhos</h1>
              <span class="text-muted"> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequuntur, velit?</a></span>
        </div>
    </div> -->


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
<!--     <div class="footer navbar-fixed-bottom">
      <hr>
      <p class="text-center"> footer....fixed bottom</p>
    </div> -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>