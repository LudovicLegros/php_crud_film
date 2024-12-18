<?php
    include('../includes/function.php');
    
    if($_SERVER['REQUEST_METHOD']==='POST'){
        $username = $_POST['username'];
        $password = $_POST['password'];

        $request = $bdd->prepare('  SELECT * 
                                    FROM users
                                    WHERE username=:username'
                                );

        $request->execute(array( 'username' => $username ));

        $data = $request->fetch();
        
        if(password_verify($password,$data['password'])){
            $_SESSION['userid']=$data['id'];
            header('location:/perigueux_php_full/index.php?success=5');
        }else{
            header('location:login.php?error=1');
        }
    
    }
?>

<?php include('../includes/head.php'); ?>

<body>
    
    <?php include('../includes/nav.php') ?>
    <h1>Connexion</h1>
    <?php if(isset($_GET['error'])):?>
        <p class="error">Nom d'utilisateur ou mot de passe incorrect</p>
    <?php endif?>
    <form action="login.php" method="post">
        <label for="username">Votre nom d'utilisateur</label>
        <input type="text" name="username" id="username">
        <label for="password">Votre mot de passe</label>
        <input type="password" name="password" id="password">
        <button>se connecter</button>
    </form>
</body>
</html>