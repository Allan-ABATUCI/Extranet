<!-- Vue stockant le header personnalisé pour chaque fonction -->
<header>
    <nav class="header-navbar">
        <div class="logo">Perform Vision</div>
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
                        <?php foreach ($_SESSION['roles'] as $r) : ?>
                            <div>
                                <li><a href="?controller=<?= $r ?>&action=default">

                                        <?= $r ?>

                                    </a></li>
                            </div>


                        <?php endforeach; ?>
                    </ul>
                </nav>

            </li>
        </ul>
        <ul class="header-exit">
            <li><a href="?controller=login" class='right-elt'><i class="fa fa-sign-out" aria-hidden="true"></i></a></li>
        </ul>
    </nav>
</header>
<script src="Content/js/prestataire.js "></script>