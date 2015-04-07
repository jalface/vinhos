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
<p class="description"><?php echo $erro; ?></p>

<div class="container">
<div class="col-lg-6 col-lg-offset-3 centered">
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
          <button type="submit" class="btn btn-success">Login</button>
           <span class="text-muted"> Ainda não é um usuário? <a href="#">Cadastre-se no Vinhos.</a></span>

        </div>

    </form>
</div>
</div>
<!-- FORM SCRIPT -->
<script type="text/javascript">

</script>