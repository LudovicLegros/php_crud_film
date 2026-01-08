<?php
    include('app/includes/function.php');

    if(!isset($_GET['id'])){
        header('location:index.php');
    }

    $id = sanitarize($_GET['id']);

    $request = $bdd->prepare('  SELECT f.titre,f.duree,f.date,f.id,f.synopsis,f.img,r.last_name,r.first_name
                                FROM fiche_film as f
                                LEFT JOIN realisateur as r
                                ON r.id = f.id_realisateur
                                WHERE f.id = :id'
                            );

    $request->execute([':id' => $id]);

    $data = $request->fetch();

    $requestGenre = $bdd->prepare('  SELECT genre.label
                                FROM genre 
                                LEFT JOIN film_genre 
                                ON film_genre.id_genre = genre.id
                                WHERE film_genre.id_film = :id'
                            );
     $requestGenre->execute([':id' => $id]);

     $genres = $requestGenre->fetchAll();
?>

<?php 
    $title = "Focus de page";
    include('app/includes/head.php');
?>

<body>
<?php include('app/includes/nav.php') ?>
<section class="detail-page">
    <header class="detail-header">
        <h1><?= $data['titre'] ?></h1>
        <p>réalisé par: <?= $data['first_name'] ?> <?= $data['last_name'] ?> </p>
        <p class="detail-year"><?= $data['date'] ?> · 
            <?php 
                $min = $data['duree'] % 60;
                $heure = ($data['duree'] - $min) / 60;
                echo $heure . "h" . $min . "min";
            ?>
        </p>
    </header>

    <main class="detail-content">
        <div class="detail-image">
            <img src="assets/img/<?= $data['img'] ?>" alt="<?= $data['titre'] ?>">
        </div>

        <div class="detail-text">
            <h2>Synopsis</h2>
            <p><?= $data['synopsis'] ?></p>

            <div class="detail-genre">
                <?php foreach($genres as $genre): ?>
                    <span><?= $genre['label'] ?></span>
                <?php endforeach; ?>
            </div>
        </div>

    </main>
</section>



</body>

</html>