<?php 
    require "config/config.php";
    session_start();
?>

<html>
    
    <div id="title">
        <h1>Sondage récents</h1>
        <h4>Nouveaux sondages auxquels vous n'avez pas encore participé.</h4>
    </div>
    
    
        <?php 
            $query = "SELECT p.id, p.sujet, p.auteur, p.datedebut, p.datefin, p.secret, p.color, p.privee,
                    (SELECT COUNT(*) FROM answer WHERE pollId = p.id) AS nb_votes
                        FROM poll p
                        LEFT JOIN answer a ON p.id = a.pollId AND a.userId = '" . $_SESSION['name'] . "'
                        WHERE (a.userId IS NULL AND p.privee = 0) 
                            AND (p.datefin IS NULL OR p.datefin > NOW())
                        ORDER BY (p.datefin IS NOT NULL) DESC, 
                                p.datefin ASC,
                                p.datedebut DESC;";
                                
                    
            $result1 = $conn->query($query);
            if ($result1 && $result1->num_rows > 0) {
                while ($row = $result1->fetch_assoc()) {
                    $pollId = $row["id"];
                    $date1 = new DateTime("now", new DateTimeZone("America/Martinique"));
                    $interval = isset($row["datefin"]) ? (new DateTime($row["datefin"], new DateTimeZone("America/Martinique")))->diff($date1) : null;

                    
    
                    echo "<div class='poll' onclick='showPoll(".$pollId.", \"".$row["color"]."\")'>";
                    echo "<img src='assets\\images\\".$row["color"] ."logo.png' alt='logo' id='poll-logo'>";
                    echo "<div class='votes'>
                            <p id='nb-votes'>".$row["nb_votes"]."</p><p>votes</p>
                        </div>";
                    echo '<p id="subject">'.$row["sujet"].$hourglass = (isset($row["datefin"])) ? '<img src="assets\images\Hourglass Icon.png" alt="Hourglass Icon">' : '' .'</p>';
                    echo '<div id="details">';
                    echo '<p id="autor">Par '. $row["auteur"].'</p>';
                    echo '<p id="date">Le ' . date("d/m/Y", strtotime($row["datedebut"])) . '</p>';
                    if (isset($row["datefin"])) {
                        echo "<p id='datefin'>Temps restant: ".$interval->days." jour(s) ".$interval->h ." heure(s) et ".$interval->i." minute(s)</p>";
                    }
                    echo '</div>';
                    if ($row["secret"] > 0) {
                        echo "<p id='privacy'>Secret<img src='assets\images\Secret Icon.png' alt='Secret Icon'></p>";
                    } else {
                        echo "<p id='privacy'>Publique<img src='assets\images\Public Icon.png' alt='Public Icon'></p>";
                    }
                    
                    
                    echo "</div>";
                    
                }
            } else {
                echo "<p id='info'>Aucun nouveau sondage.</p>";
            }
            
            
        ?>
        <h1>Sondages</h1>
        <h4>Liste des sondages</h4>
        <?php 
            $query = "SELECT a.optionId, p.id, p.sujet, p.auteur, p.datedebut, p.datefin, p.secret, p.color, p.privee, 
                    (SELECT COUNT(*) FROM answer WHERE pollId = p.id) as nb_votes, 
                    CASE 
                            WHEN EXISTS (
                                SELECT 1 
                                FROM answer 
                                WHERE pollId = p.id AND userId = '". $_SESSION['name']."'
                            ) THEN 'true'
                            ELSE 'false'
                        END AS has_answered
                        FROM poll p
                        LEFT JOIN answer a ON p.id = a.pollId AND a.userId = '". $_SESSION['name']."'
                        WHERE p.privee = 0 
                        AND (
                            (p.datefin IS NULL AND a.optionId IS NOT NULL) 
                            OR (p.datefin < NOW())
                            OR (p.datefin IS NOT NULL AND a.optionId IS NOT NULL)
                        )
                        ORDER BY COALESCE(p.datefin, p.datedebut) DESC;";
                    

                    

            $result1 = $conn->query($query);
            if ($result1 && $result1->num_rows > 0) {
                while ($row = $result1->fetch_assoc()) {
                    $pollId = $row["id"];
                    $datenow = new DateTime("now", new DateTimeZone("America/Martinique"));
                    $datefin = isset($row["datefin"]) ? (new DateTime($row["datefin"], new DateTimeZone("America/Martinique"))) : null;
                    $interval = isset($row["datefin"]) ? (new DateTime($row["datefin"], new DateTimeZone("America/Martinique")))->diff($datenow) : null;


                    echo "<div class='poll' onclick='showPoll(".$pollId.", \"".$row["color"]."\", ".$bool = ((isset($row["datefin"]) && $row["datefin"] > $datenow) || $row["has_answered"] == "true" ? "\"yes\"" : "\"no\"") .")'>";
                    echo "<img src='assets\\images\\".$row["color"] ."logo.png' alt='logo' id='poll-logo'>";
                    echo "<div class='votes'>
                            <p id='nb-votes'>".$row["nb_votes"]."</p><p>votes</p>
                        </div>";
                    echo '<p id="subject">'.$row["sujet"].'  <img src="assets\images\Check Icon.png" alt="Hourglass Icon"></p>';
                    echo '<div id="details">';
                    echo '<p id="autor">Par '. $row["auteur"].'</p>';
                    echo '<p id="date">Le ' . date("d/m/Y", strtotime($row["datedebut"])) . '</p>';

                    if (isset($row["datefin"])) {
                        if ($datefin < $datenow) {
                            echo "<p id='datefin'>Sondage terminé le ". date("d/m/Y", strtotime($row["datefin"])) ."</p>";
                        } else {
                            echo "<p id='datefin'>Temps restant: ".$interval->days." jour(s) ".$interval->h ." heure(s) et ".$interval->i." minute(s)</p>";
                        }
                        
                    }
                    echo '</div>';
                    if ($row["secret"] > 0) {
                        echo "<p id='privacy'>Secret<img src='assets\images\Secret Icon.png' alt='Secret Icon'></p>";
                    } else {
                        echo "<p id='privacy'>Publique<img src='assets\images\Public Icon.png' alt='Public Icon'></p>";
                    }

                    echo "</div>";
                    
                }
            } else {
                echo "<p id='info'>Aucun sondage.</p>";
            }
            
            
        ?>
</html>



    

    
    
