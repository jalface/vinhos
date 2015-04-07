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
  <link rel="stylesheet" href="./css/global.css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

  <!-- SCRIPT -->
  <script type="text/javascript"></script>

</head>

<body class="">

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
    <div class="jumbotron">
        <div class="container">
              <h1>Vinhos.com!</h1>
              <p>...</p>
        </div>
    </div>

    <div class="container">
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
        <footer>
          <hr>

          <div class="container text-center">
            <h3>Subscribe now for a 30-day free trial!</h3>
            <p>Enter your name and email below</p>

            <form action="#" class="form-inline" role="form">
              <div class="form-group">
                <label for="subscribe-name" class="sr-only">Name</label>
                <input type="text" class="form-control" id="subscribe-name" placeholder="Name">
              </div> <!-- end form-group -->
              <div class="form-group">
                <label for="subscribe-email" class="sr-only">Email address</label>
                <input type="text" class="form-control" id="subscribe-email" placeholder="Email address">
              </div> <!-- end form-group -->

              <button type="submit" class="btn btn-default">Subscribe</button>

              <p><small><em>We will only use your email address for sending the download link.</em></small></p>
            </form>

            <hr>

            <ul class="list-inline">
              <li><a href="#">Twitter</a></li>
              <li><a href="#">Facebook</a></li>
            </ul>

            <p>&copy; Copyright 2014.</p>
          </div> <!-- end container -->

        </footer>

    </div>




    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>