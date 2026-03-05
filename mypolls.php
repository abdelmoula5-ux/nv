<?php 
    require "config/config.php";
    session_start();
?>

<html>
    
    <div id="title">
        <h1>Mes sondages</h1>
        <h4></h4>
    </div>
    
    
        <?php 
            $query = "SELECT *,  (SELECT count(*) FROM answer WHERE pollId = p.id) as nb_votes
                     from poll p where auteur = '". $_SESSION['name']."'
                    ORDER BY datedebut DESC;";
                    
                    
            $result1 = $conn->query($query);
            if ($result1 && $result1->num_rows > 0) {
                while ($row = $result1->fetch_assoc()) {
                    $pollId = $row["id"];

    
                    echo "<div class='poll' onclick='showPoll(".$pollId.", \"".$row["color"]."\")'>";
                    echo "<img src='assets\\images\\".$row["color"] ."logo.png' alt='logo' id='poll-logo'>";
                    echo "<div class='votes'>
                            <p id='nb-votes'>".$row["nb_votes"]."</p><p>votes</p>
                        </div>";
                    echo '<p id="subject">'.$row["sujet"].'  <img src="assets\images\Hourglass Icon.png" alt="Hourglass Icon"></p>';
                    echo '<div id="details">';
                    echo '<p id="autor">Par '. $row["auteur"].'</p>';
                    echo '<p id="date">Le '.substr($row["datedebut"], 8, 2).'/'.substr($row["datedebut"], 5, 2).'/'.substr($row["datedebut"], 0, 4).'</p>';
                    echo '</div>';
                    if ($row["secret"] > 0) {
                        echo "<p id='privacy'>Secret<img src='assets\images\Secret Icon.png' alt='Secret Icon'></p>";
                    } else {
                        echo "<p id='privacy'>Publique<img src='assets\images\Public Icon.png' alt='Public Icon'></p>";
                    }

                    if ($row["privee"] != 0) {
                        
                        echo '<p id="private"><img src="assets\images\Lock Icon.png" alt="Lock Icon">Sondage privée</p>';

                        echo '<p id="share" onclick="copyLink('.$row["randomcode"].', event)"><img src="assets\images\Icon\Share Icon.png" alt="Share Icon">Partager le liens</p>';
                    }
    
                    echo "</div>";
                    
                }
            } else {
                echo "<p id='info'>Aucun sondage.</p>";
            }
            
            
        ?>
</html>