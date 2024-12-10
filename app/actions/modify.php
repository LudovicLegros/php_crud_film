<!-- REQUEST UPDATE -->
<!-- ----------- -->
<?php
    include('../includes/function.php');
    // Creation d'une condition, si un "id" est trouvé on reste sur la page sinon on retourne sur l'index
    if(isset($_GET['id'])){
        $id = htmlspecialchars($_GET['id']);

            // ----------REQUEST READ-----------
            // On créer une requete READ pour mettre les valeurs existante dans les champs de formulaire
            $requestRead = $bdd->prepare('  SELECT *
                                            FROM fiche_film
                                            WHERE id = :id'
                );

            $requestRead->execute(array(
                    'id'    =>  $id
                ));
            // Pas besoin de bouche while parce qu'on a qu'une seule ligne a lire 
            $data = $requestRead->fetch();

         
            // On vérifie si l'utilisateur est bien celui qui a créé la fiche
            if($_SESSION['userid']!=$data['user_id']){
                header("location:/perigueux_php_full/index.php");
            }


    }else{
        header('location:/perigueux_php_full/index.php');
    }

    


        // RECUPERATION DES DONNEES POST (POUR l'UPDATE)
        if(isset($_POST['titre']) && isset($_POST['duree']) && isset($_POST['annee'])){
            $titre=sanitarize($_POST['titre']);
            $duree=sanitarize($_POST['duree']);
            $date=sanitarize($_POST['annee']);
            $id=htmlspecialchars($_POST['id']);

                // --------------TRAITEMENT DE L'IMAGE------------
                //    Si le champ image est vide on fait la requête update sans l'image
            if($_FILES['image']['error'] === UPLOAD_ERR_NO_FILE){

                            // REQUETE UPDATE SANS IMG
                            $request = $bdd->prepare('  UPDATE fiche_film
                            SET titre = :titre, date=:date,duree=:duree
                            WHERE id = :id'
                        );

                    $request->execute(array(
                    'titre' =>  $titre,
                    'date'  =>  $date,
                    'duree' =>  $duree,
                    'id'    =>  $id
                    ));

                header("location:/perigueux_php_full/index.php?success=2");

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


                    // -----------REQUETE UPDATE---------------
                    $request = $bdd->prepare('  UPDATE fiche_film
                    SET titre = :titre, date=:date,duree=:duree,img=:img
                    WHERE id = :id'
                    );

                $request->execute(array(
                'titre' =>  $titre,
                'date'  =>  $date,
                'duree' =>  $duree,
                'img'   =>  $img,
                'id'    =>  $id
                ));
                // Renvois de l'utilisateur sur l'index aprés validation de formulaire
                header("location:/perigueux_php_full/index.php?success=2");

            }

        }



  
?>

<?php include('../includes/head.php'); ?>
<body>
    <?php include('../includes/nav.php') ?>
    <form action="modify.php" method="POST" enctype="multipart/form-data">
        <label for="titre">Entrez un nouveau film</label>
        <input id="titre" type="text" name="titre" value="<?php echo $data['titre'];?>">
        <label for="duree">Entrez la duree en min</label>
        <input id="duree" type="number" name="duree" value="<?php echo $data['duree'];?>">
        <label for="annee">Entrez l'année de sortie</label>
        <input id="annee" type="number" name="annee" value="<?php echo $data['date'];?>">
        <input type="hidden" name="id" value="<?php echo $data['id'];?>">
        <label for="image">Choisissez une image</label>
        <input id="image" type="file" name="image">

        <button>modifier</button>
    </form>
    
</body>
</html>