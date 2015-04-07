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
<p class="description">
    <?php echo $erro; ?>
</p>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" accept-charset="utf-8">
    <fieldset>
        <legend>Login</legend>
        <label>Username</label>
        <div class="input-control text" data-role="input-control">
            <input type="text" placeholder="type text" id="username" name="username" required>
            <button class="btn-clear" tabindex="-1"></button>
        </div>
        <label>Password</label>
        <div class="input-control password" data-role="input-control">
            <input type="password" placeholder="type password" id="password" name="password" autofocus required>
            <button class="btn-reveal" tabindex="-1"></button>
        </div>
        <input type="submit" value="login" name="login">
    </fieldset>
</form>

<!-- FORM SCRIPT -->
<script type="text/javascript">

</script>