<!-- Vue stockant le header personnalisé pour chaque fonction -->
<header>
    <nav class="header-navbar">
        <div class="logo">Perform Vision</div>
        <?php
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if user is logged in, redirect to login page if not
        if (!isset($_SESSION['id'])) {
            header("Location: index.php");
            exit();
        }

        if (isset($_POST['disconnect'])) {
            // Destroy the session
            session_unset();
            session_destroy();

            // Redirect to login page
            header("Location: index.php");
            exit();
        }
        ?>
        <?php if (isset($menu)) : ?>
            <ul class="menu-list">
                <?php foreach ($menu as $c => $m) : ?>
                    <li id="link<?= $c ?>" onclick=" setActiveLink('link<?= $c ?>')"><a href=<?= $m['link'] ?>><?= $m['name'] ?></li></a>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <ul class="header-infos">
            <li>
                <a class='right-elt' href="?controller=<?= $_GET['controller'] ?>&action=infos" id="username">
                    <i class="fa fa-user-circle" aria-hidden="true"></i>
                    <?php if (isset($_SESSION)) :;
                        echo '&nbsp;' . '<p>' . $_SESSION['nom'];
                        echo '&nbsp;' . $_SESSION['prenom'] . '</p>';
                    endif; ?>
                </a>

                <button id="menu-button">
                    <img src="<?= $rolemanagment ?>" class="fa role">
                    <span>
                        <?php echo $_SESSION['role'] ?>
                    </span>
                </button>

                <nav id="sliding-menu">
                    <ul>
                        <?php
                        if (isset($_SESSION['roles'])) :
                            foreach ($_SESSION['roles'] as $r) : ?>
                                <div>
                                    <li><a href="?controller=<?= $r ?>&action=default">

                                            <?= $r ?>

                                        </a></li>
                                </div>


                        <?php endforeach;
                        endif; ?>
                    </ul>
                </nav>

            </li>
        </ul>
        <ul class="header-exit">
            <form method="post">
                <button type="submit" name="disconnect"><i class="fa fa-sign-out" aria-hidden="true"></i></button></a></li>
            </form>
        </ul>
    </nav>
</header>
<script src="Content/js/prestataire.js "></script>