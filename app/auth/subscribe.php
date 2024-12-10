<?php 
include('../includes/function.php');
// Methode alternative au isset qui permet de vérifier si des requêtes de type POST ont bien été envoyé
if($_SERVER['REQUEST_METHOD']==='POST'){
    $username = sanitarize($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $passwordConfirm = htmlspecialchars($_POST['passwordConfirm']);

    // Cryptage du mot de passe
    if($password == $passwordConfirm){
        $passwordCrypt = password_hash($password,PASSWORD_BCRYPT);

        // Préparation de la requête
        $request = $bdd->prepare('INSERT INTO users (username,password)
        VALUE (:username,:password)'
    );

        // Exécution de la requête
        $request->execute(array(
        'username' =>  $username,
        'password'  =>  $passwordCrypt,
        ));

        header('location:/perigueux_php_full/index.php?success=4');
    }else{
        header('location:subscribe.php?error=1');
    }
}

?>

<?php include('../includes/head.php'); ?>
<body>
    <h1>Inscription</h1>
    <?php include('../includes/nav.php') ?>
    <?php if(isset($_GET['error'])):?>
        <p class="error">Vos mots de passe ne correspondent pas</p>
    <?php endif?>
    <form action="subscribe.php" method="post">
        <label for="username">Votre nom d'utilisateur</label>
        <input type="text" name="username" id="username">
        <label for="password">Votre mot de passe</label>
        <input type="password" name="password" id="password">
        <label for="passwordConfirm">Confirmez votre mot de passe</label>
        <input type="password" name="passwordConfirm" id="passwordConfirm">
        <button>s'inscrire</button>
    </form>
</body>
</html>