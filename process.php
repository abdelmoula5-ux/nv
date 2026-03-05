<?php 
    session_start();
    require "config/config.php";
    $optionId = $_POST["option_id"];
    $pollId = $_POST["poll_id"];
    $userId = $_SESSION['name'];
    $date = date('Y-m-d H:i:s');


    $query = $conn->prepare("INSERT INTO answer (pollId, optionId, userId, date)
                            VALUES (?, ?, ?, ?)
                            ON DUPLICATE KEY UPDATE optionId = VALUES(optionId);");

    // Lier les valeurs aux paramètres en respectant le type (s pour string)
    $query->bind_param('iiss', $pollId, $optionId, $userId, $date);

    // Exécuter la requeeeête
    $query->execute();

    echo "Requetes effectué";

?>