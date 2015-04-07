<!-- CONTAINER -->
<div class="container">

    <!-- BANNER -->
    <header>

    </header>

    <!-- NAV -->
    <nav>

<!-- $(document).ready(function() {
      $("#solTitle a").click(function() {
        //Do stuff when clicked
          });
      });
 -->
    </nav>

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

    </footer>

</div>