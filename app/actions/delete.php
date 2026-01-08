<?php
    include('../includes/function.php');

    if(isset($_GET['id'])){
        $id = htmlspecialchars($_GET['id']);

            // On créer une requete qui va nous permettre de vérifier si la fiche film appartien bien a l'utilisateur
            $requestRead = $bdd->prepare('  SELECT *
                                            FROM fiche_film
                                            WHERE id = :id'
                );

            $requestRead->execute(array(
                    'id'    =>  $id
                ));
            // Pas besoin de bouche while parce qu'on a qu'une seule ligne a lire 
            $data = $requestRead->fetch();
            
            // Verification de l'utilisateur
            if($_SESSION['userid']==$data['user_id']){
                // Suppression du fichier en local
                unlink('../../assets/img/' . $data['img']);

                //ON EXECUTE LA REQUETE DELETE SI CA CORRESPOND
                $request = $bdd->prepare('  DELETE FROM fiche_film
                WHERE id=:id');

                $request->execute(['id' =>$id]);

                // echo $id;
                header('location:'. BASE_URL .'/index.php?success=3');
                exit();
            }else{
                header('location:'. BASE_URL .'/perigueux_php_full/index.php');
            }

        }else{
            header('location:'. BASE_URL .'/perigueux_php_full/index.php');
        }

   

