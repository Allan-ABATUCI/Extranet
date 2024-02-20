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
        <ul>
            <li>
                <a class='right-elt' href="?controller=<?= $_GET['controller'] ?>&action=infos" id="username" class='right-elt'>
                    <i class="fa fa-user-circle" aria-hidden="true"></i>
                    <?php if (isset($_SESSION)): ; echo '&nbsp;' . $_SESSION['nom']; endif; ?>
                    <br> <?php if (isset($_SESSION)): echo '&nbsp;' . $_SESSION['prenom']; endif; ?>
                </a>
                    <?php echo $_SESSION['role']?></li>
            <li><a href="?controller=login" class='right-elt'><i class="fa fa-sign-out"
                                                                             aria-hidden="true"></i></a></li>
        </ul>
    </nav>
</header>
