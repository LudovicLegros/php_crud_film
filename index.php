<!-- REQUEST READ -->
<!-- ----------- -->
<?php
    define('ROOT_PATH', __DIR__);
    include('app/includes/function.php');

    $request = $bdd->prepare('  SELECT f.titre,f.duree,f.date,f.id as film_id,f.user_id,u.id,u.username,f.img
                                FROM fiche_film as f
                                LEFT JOIN users as u
                                ON user_id = u.id'
                            );

    $request->execute(array());
?>

<?php 
$title = "Récuperation de requêtes";
include('app/includes/head.php');
?>

<body>
    <?php include('app/includes/nav.php') ?>
    <h1>Récuperation de requetes</h1>
    

    <!-- AFFICHAGE D'UN MESSAGE DE CONFIRMATION D'OPERATION -->
    <?php
        if(isset($_GET['success'])){
            // DIFFERENT MESSAGE EN FONCTION DE L'OPERATION EFFECTUé
            switch($_GET['success']){
                case 1:
                    echo "<p class='success'> Votre film à bien été ajouté</p>";
                    break;
                case 2:
                    echo "<p class='success'> Votre film à bien été modifié</p>";
                    break;
                case 3:
                    echo "<p class='success'> Votre film à bien été supprimé</p>";
                    break;
                case 4:
                    echo "<p class='success'> Vous avez bien été inscrit</p>";
                    break;
                case 5:
                    echo "<p class='success'> Bienvenue </p>";
                    break;
            }
        }
    ?>
    <section>
<!-- BOUCLE DE RECUPERATION DE LA REQUEST -->
    <?php
    while ($data = $request->fetch()) :
         
    ?>
        <article>

            <!-- Condition pour afficher l'image, ou l'image générique -->
            <?php if($data['img'] == NULL ): ?>
                <img src="assets/img/noimage.png" alt="">
            <?php else: ?>
                <img src="assets/img/<?php echo $data['img'] ?>" alt="">
            <?php endif ?>

            <p><?= $data['titre']  ?></p>

            <!-- formatage du temps -->
            <p><?php 
                $min= $data['duree']%60;
                $heure= ($data['duree']-($min))/60;
                echo $heure . "h" . $min . "min";
            ?></p>
            <p><?= $data['date']  ?></p>
            <p>par : <?= $data['username']  ?></p>

            <a href="voir_plus.php?id=<?php echo $data['film_id']?>">Voir plus</a>
            <?php if(isset($_SESSION['userid'])): ?>
                <?php if($_SESSION['userid']==$data['user_id']): ?>
                <a href="app/actions/modify.php?id=<?php echo $data['film_id']?>">modifier</a>
                <a href="app/actions/delete.php?id=<?php echo $data['film_id']?>">supprimer</a>
                <?php endif ?>
            <?php endif ?>
        </article>
    <?php endwhile; ?>
    </section>
</body>

</html>