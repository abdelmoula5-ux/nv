<?php 
    require "config/config.php";

    $pollId = $_POST["id"];
    // Requête pour récupérer le nombre de votes pour 'Nike', 'Adidas' et le total des votes
    $query = "
        SELECT options.* , count(answer.optionId) as nbReponse, (SELECT count(*) from answer where pollId = $pollId) as totalvotes 
        FROM options
        LEFT JOIN answer
        ON answer.optionId = id  
        WHERE options.pollId = $pollId
        GROUP by options.id
        ORDER BY options.id;
    ";
    
    $result = mysqli_query($conn, $query);

    
    // Vérifier s'il y a des résultats
    while ($row = $result->fetch_assoc()) {
        if ($row["totalvotes"] != 0) {
            echo $row["nbReponse"]/$row["totalvotes"]*100 . ',';
        } else {
            echo "0,";
        }
        
    }
    
    // Fermer la connexion
    mysqli_close($conn); 
?>
