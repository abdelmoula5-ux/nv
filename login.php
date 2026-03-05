<?php
require "config/config.php";


session_start();

if (isset($_POST["connexion"])) {
    // Stocker les informations de session
    $identifier = $_POST['Pseudo'];
    $password = $_POST['Mot_de_passe'];
    
    $query = "SELECT * FROM users WHERE pseudo = '$identifier'";
    
    $result = $conn->query($query);
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        // Redirection vers home.php
        $_SESSION["name"] = $identifier;
        $_SESSION['user_statut'] = 'connected';
        header('Location: index.php');
        exit();
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/reset.css">
    <link rel="stylesheet" href="assets/css/login-page-style.css">
    <title>Document</title>
</head>
<body>
<div class="login-left">
    <img src="assets\images\SALE__1_-removebg-preview 1.png" alt="">
</div>
<div class="login-right">
    <form id="login-form" action="" method="POST">
        <h1>Connexion</h1>
        <div class="inputs">
        <div>
            <label for="Pseudo">Identifiant*</label>
            <input type="text" name="Pseudo" id="username" required="required" placeholder="Entrez votre identifiant">
        </div>

        <div>
            <label for="Mot de passe">Mot de passe*</label>
            <input type="password" name="Mot_de_passe" id="password" required="required" placeholder="Entrez votre mot de passe">
        </div>
        </div>
        
        
        
        <button name="connexion" type="submit">Connexion</button>
    </form>
</div>



</body>
</html>