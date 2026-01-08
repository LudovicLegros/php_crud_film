<nav>
    <ul>
        <li><a href="<?= BASE_URL ?>/index.php">accueil</a></li>
        
        <!-- On affiche differente partie de la navbar en fonction de  utilisateur est connecté ou pas -->
        <?php if(isset($_SESSION['userid'])): ?>
            <li><a href="<?= BASE_URL ?>/app/actions/add.php">ajouter</a></li>
            <li><a href="<?= BASE_URL ?>/app/auth/logout.php">se déconnecter</a></li>
        <?php else: ?>
            <li><a href="<?= BASE_URL ?>/app/auth/login.php">se connecter</a></li>
            <li><a href="<?= BASE_URL ?>/app/auth/subscribe.php">s'inscrire</a></li>
        <?php endif ?>
    </ul>
</nav>