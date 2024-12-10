<!-- REQUEST READ -->
<!-- ----------- -->
<?php
    include('app/includes/function.php');

    $request = $bdd->prepare('  SELECT f.titre,f.duree,f.date,f.id as film_id,f.user_id,u.id,u.username,f.img
                                FROM fiche_film as f
                                LEFT JOIN users as u
                                ON user_id = u.id'
                            );

    $request->execute(array());
?>

<?php 
include('app/includes/head.php')
?>

<body>
    <h1>Récuperation de requetes</h1>
    <?php include('app/includes/nav.php') ?>

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
        // var_dump($data) 
    ?>
        <article>
            <p><?= $data['titre']  ?></p>
            <p><?php 
            $min= $data['duree']%60;
            $heure= ($data['duree']-($min))/60;
            echo $heure . "h" . $min . "min";
            
            ?></p>
            <p><?= $data['date']  ?></p>
            <p>par : <?= $data['username']  ?></p>

            <?php if($data['img'] == NULL ): ?>
                <img src="assets/img/noimage.png" alt="">
            <?php else: ?>
                <img src="assets/img/<?php echo $data['img'] ?>" alt="">
                <?php var_dump($data['img']) ?>
            <?php endif ?>

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