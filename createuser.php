<?php 
    require "config/config.php";

    if (isset($_POST["createuser"])) {
        $pseudo = $_POST['user'];
        $password = $_POST['password'];

        $query = $conn->prepare("INSERT INTO users (pseudo, password) VALUES(?, ?)");

        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        // Lier les valeurs aux paramètres en respectant lee type (s pour string)
        $query->bind_param('ss', $pseudo, $password_hash);


        // Exécuter la requeeeête
        $query->execute();



    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/reset.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Document</title>
</head>
<body>
    <form action="" method="post">
        <label for="user">Nom d'utilisateur</label>
        <input type="text" name="user">
        <label for="password">Mot de passe</label>
        <input type="text" name="password">

        <button type="submit" name="createuser">Créer utilisateur</button>
    </form>
</body>
</html>