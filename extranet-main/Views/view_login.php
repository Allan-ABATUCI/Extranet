<!-- Vue permettant de voir la page de connection afin de se connecter et accéder au site -->
<?php
require 'view_begin.php';
?>

<div class="login-container" <?= (isset($data['response']['roles'])) ? "background-blur" : "" ?> >
    <div class="container">
        <h1>Connectez-vous !</h1>
        <h3>Accéder au site de Perform Vision</h3>

        <?php if (isset($data['response'])) :
            if (isset($data['response']['roles'])):
                require 'view_login_popup.php'; ?>
            <?php else: ?>
                <p class='alert'><?= $data['response'] ?></p>
            <?php endif; ?>
        <?php endif; ?>

        <form class="login-form" action="?controller=login&action=check_pswd" method="post">
            <div>
                <input class="input-login" type="text" name="mail" placeholder="Email">
                <a class="oublie" href="#">Adresse email oubliée ?</a>
            </div>
            <div>
                <input class="input-login" type="password" name="password" placeholder="Mot de passe">
                <a class="oublie" href="#">Mot de passe oublié ?</a>
            </div>
            <button class='button button-primary'
                    type="submit">Connexion
            </button>
        </form>
    </div>
</div>

<?php
require 'view_end.php';
?>
