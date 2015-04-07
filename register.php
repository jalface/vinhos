<!-- PHP -->
<?php

if (isset($_POST['register'])) {
    $nome = $_POST['nome'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    //Validate Email
    $email = $_POST['email'];
    if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)) {
        $erro = 'Email inválido!';
    } else {
        //Build user
        $user = new classes\User($username, $password, $nome, $email, '');

        //Check if Username avaiable
        if ($user->idUser()) {
            $erro = 'Username Já existe!';
            die;
        } else {
            //Register
            if ($user->register()) {
                //User Registado
                //Send confirmation email
                $erro = 'User Registado, Verificar email!';
            } else {
                $erro = $user->getErro();
                die;
            }
        }
    }
}
?>

<!-- FORM -->
<div class="span6">
    <p class="description">
        <?php echo $erro; ?>
    </p>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" accept-charset="utf-8">
            <fieldset>
                <legend>Login</legend>
                <label>Nome</label>
                <div class="input-control text" data-role="input-control">
                    <input type="text" placeholder="type text" id="nome" name="nome" required>
                    <button class="btn-clear" tabindex="-1"></button>
                </div>
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
                <label>Email</label>
                <div class="input-control text" data-role="input-control">
                    <input type="text" placeholder="type text" id="email" name="email" required>
                    <button class="btn-clear" tabindex="-1"></button>
                </div>
                <input type="submit" value="register" name="register">
            </fieldset>
        </form>
</div>

<!-- FORM SCRIPT -->
<script type="text/javascript">

</script>