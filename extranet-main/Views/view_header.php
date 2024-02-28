<!-- Vue stockant le header personnalisé pour chaque fonction -->
<header>
    <nav class="header-navbar">
        <div class="logo">Perform Vision</div>
        <?php if (isset($menu)): ?>
            <ul class="menu-list">
                <?php foreach ($menu as $m): ?>
                    <li><a href=<?= $m['link'] ?>><?= $m['name'] ?></li></a>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <ul class="header-infos">
            <li>
                <a class='right-elt' href="?controller=<?= $_GET['controller'] ?>&action=infos" id="username"
                    class='right-elt'>
                    <i class="fa fa-user-circle" aria-hidden="true"></i>
                    <?php if (isset($_SESSION)):
                        ;
                        echo '&nbsp;' . '<p>' . $_SESSION['nom'];
                        echo '&nbsp;' . $_SESSION['prenom'] . '</p>';
                    endif; ?>
                </a>
                <span class="header-role">
                    <?php echo $_SESSION['role'] ?>
                </span>
            </li>
        </ul>
        <ul class="header-exit">
            <li><a href="?controller=login" class='right-elt'><i class="fa fa-sign-out" aria-hidden="true"></i></a></li>
        </ul>
    </nav>
</header>