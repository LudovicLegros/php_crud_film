<!-- REQUEST ADD -->
<!-- ----------- -->
<?php
    include('../includes/function.php');

    // RECUPERATION DES DONNEES POST
    if(isset($_POST['titre']) && isset($_POST['duree']) && isset($_POST['annee'])){
       $titre       =sanitarize($_POST['titre']);
       $duree       =sanitarize($_POST['duree']);
       $date        =sanitarize($_POST['annee']);
       $realisateur =sanitarize($_POST['realisateur']);
       $synopsis    =sanitarize($_POST['synopsis']);
       $dataGenres       =$_POST['genres'] ?? [];
      

    //    Si le champ image est vide on lui attribut une valeur NULL
       if(empty($_FILES['image'])){
            $img = NULL;
       }else{
            $imageName = sanitarize($_FILES['image']['name']);
            $imageInfo = pathinfo($imageName);
            $imageExt = $imageInfo['extension'];
            // Tableau qui va permettre de spécifier les extensions autorisées
            $autorizedExt = ['png','jpeg','jpg','webp','bmp','svg'];

            // Verification de l'extention du fichier

            if(in_array($imageExt,$autorizedExt)){
                $img = time() . rand(1,1000) . "." . $imageExt;
                move_uploaded_file($_FILES['image']['tmp_name'],"../../assets/img/".$img);
            
            }else{
                echo 'location:'. BASE_URL .'/index.php?success=1';
            }
       }

    

    $bdd = new PDO('mysql:host=localhost;dbname=perigueux_fiche_film;charset=utf8', 'root', '');

    $request = $bdd->prepare('  INSERT INTO fiche_film (titre,date,duree,user_id,img,id_realisateur,synopsis)
                                VALUE (:titre,:date,:duree,:user_id,:img,:realisateur,:synopsis)'
                            );

    $request->execute(array(
        'titre'         => $titre,
        'date'          => $date,
        'duree'         => $duree,
        'user_id'       => $_SESSION['userid'],
        'img'           => $img,
        'realisateur'   => $realisateur,
        'synopsis'      => $synopsis
    ));

    // AJOUT DES GENRES

        // var_dump($dataGenres);
        $filmId = $bdd->lastInsertId();

        $requestAddGenre = $bdd->prepare('  INSERT INTO film_genre (id_genre,id_film)
                                            VALUE (:id_genre,:id_film)');
        
        foreach($dataGenres as $dataGenre){
            $requestAddGenre->execute([
                'id_film'  => $filmId,
                'id_genre' => $dataGenre,
            ]);
        }
  

    header('location:'. BASE_URL .'/index.php?success=1');

}
// RECUPERATION DES REALISATEURS
$requestRealisateur = $bdd->prepare('   SELECT id, last_name, first_name
                                        FROM realisateur');
$requestRealisateur->execute([]);
$realisateurs = $requestRealisateur->fetchAll();

// RECUPERATION DES GENRES

$requestGenre = $bdd->prepare(' SELECT id, label
                                FROM genre');
$requestGenre->execute([]);
$genres = $requestGenre->fetchAll();
?>

<?php include('../includes/head.php'); ?>
<body>
   
    <?php include('../includes/nav.php') ?>
    <section>
        <form action="add.php" method="POST" enctype="multipart/form-data">
            <label for="titre">Entrez un nouveau film</label>
            <input id="titre" type="text" name="titre">
            <label for="duree">Entrez la duree en min</label>
            <input id="duree" type="number" name="duree">
            <label for="annee">Entrez l'année de sortie</label>
            <input id="annee" type="number" name="annee">
            <label for="image">Choisissez une image</label>
            <input id="image" type="file" name="image">
            <label for="synopsis">Ecrivez le synopsis</label>
            <textarea name="synopsis" id="synopsis"></textarea>
            <label for="realisateur">Choisissez le réalisateur</label>
            <!-- CHAMP POUR ONE TO MANY -->
            <select name="realisateur" id="realisateur">
                <?php foreach($realisateurs as $realisateur):?>
                <option value="<?=$realisateur['id'] ?>"><?= $realisateur['first_name']. " ". $realisateur['last_name'] ?></option>
                <?php endforeach; ?>
            </select>
            <!-- CHAMP POUR MANY TO MANY -->
            <?php foreach($genres as $genre):?>
                 <div>
                    <input type="checkbox" id="<?=$genre['label'] ?>" name="genres[]" value=<?=$genre['id'] ?> />
                    <label for="<?=$genre['label'] ?>"><?=$genre['label'] ?></label>
                </div>
            <?php endforeach; ?>
            <button>Ajouter</button>
        </form>

    </section>
   
    
</body>
</html>