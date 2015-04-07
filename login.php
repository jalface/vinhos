<!-- PHP -->
<?php
$erro = '';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    //Build user
    $user = new classes\user($username, $password, '', '', '');

    if ($user->login()) {
        $_SESSION['user'] = $user->getUsername();
        $_SESSION['user_id'] = $user->getIdUser();
    } else {
        $erro = $user->getErro();
    }
}
?>

<!-- FORM -->
<div class="container" style="padding-top: 200px;">
    <p class="description"><?php echo $erro; ?></p>

    <div class="col-lg-4 col-lg-offset-4 centered" style="box-shadow: 0 0 30px black; padding:10px 15px 0 15px;border-radius:5px;background-color: #FFFFFF; opacity: 0.90;">
    <p class="text-center"><span class="glyphicon glyphicon-glass centered" aria-hidden="true" style="font-size: 50px;"></span></p>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" accept-charset="utf-8">

            <div class="form-group has-feedback">
              <label for="form-elem-1" class="control-label">Username</label>
              <input type="text" id="username" name="username" class="form-control" required>
            </div>

            <div class="form-group has-feedback">
              <label for="form-elem-2" class="control-label">Password</label>
              <input type="password" id="password" name="password" class="form-control" required>
              <p class="help-block">Password must be atleast X chars.</p>
            </div>

            <div class="form-group">
              <button type="submit" class="btn btn-success" style="display: block; width: 100%;">Login</button>
               <div class="text-center"><span class="text-muted text-center"> Ainda não é um usuário? <a href="#">Cadastre-se aqui.</a></span></div>
            </div>

        </form>
    </div>
</div>

<!-- FORM SCRIPT -->
<script type="text/javascript">

</script>