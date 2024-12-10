<!-- REQUEST ADD -->
<!-- ----------- -->
<?php
    include('../includes/function.php');

    // RECUPERATION DES DONNEES POST
    if(isset($_POST['titre']) && isset($_POST['duree']) && isset($_POST['annee'])){
       $titre=sanitarize($_POST['titre']);
       $duree=sanitarize($_POST['duree']);
       $date=sanitarize($_POST['annee']);

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
                // echo 'location:/perigueux_php_full/index.php?success=1';
            }
       }

    

    $bdd = new PDO('mysql:host=localhost;dbname=perigueux_fiche_film;charset=utf8', 'root', '');

    $request = $bdd->prepare('  INSERT INTO fiche_film (titre,date,duree,user_id,img)
                                VALUE (:titre,:date,:duree,:user_id,:img)'
                            );

    $request->execute(array(
        'titre' =>  $titre,
        'date'  =>  $date,
        'duree' =>  $duree,
        'user_id'=> $_SESSION['userid'],
        'img'   => $img,
    ));

    header('location:/perigueux_php_full/index.php?success=1');

}
?>

<?php include('../includes/head.php'); ?>
<body>
    <?php include('../includes/nav.php') ?>
    <form action="add.php" method="POST" enctype="multipart/form-data">
        <label for="titre">Entrez un nouveau film</label>
        <input id="titre" type="text" name="titre">
        <label for="duree">Entrez la duree en min</label>
        <input id="duree" type="number" name="duree">
        <label for="annee">Entrez l'année de sortie</label>
        <input id="annee" type="number" name="annee">
        <label for="image">Choisissez une image</label>
        <input id="image" type="file" name="image">
        <button>Ajouter</button>
    </form>
    
</body>
</html>