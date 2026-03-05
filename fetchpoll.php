<?php 
    require "config/config.php";
    $pollId = $_POST["id"];
?>

<html>
    <div class="poll-card" id="poll-card">
        
        <div onclick="closePoll()" class="popup-overlay"></div>
        <?php 
            
             
            $query = "SELECT * FROM poll WHERE id = ".$pollId.";";

            $result1 = $conn->query($query);
            $row = $result1->fetch_assoc();
                echo '<div class="poll-content">
                <img id="close-poll" onclick="closePoll()" src="assets\images\Cross Icon.png" alt="Cross Icon">';
                $pollId = $row["id"];

                $datenow = new DateTime("now", new DateTimeZone("America/Martinique"));
                $datefin = isset($row["datefin"]) ? (new DateTime($row["datefin"], new DateTimeZone("America/Martinique"))) : null;

                $query = "SELECT count(*) as nbvotes FROM answer where pollId =".$pollId.";";

                $result3 = $conn->query($query);
                $row3 = $result3->fetch_assoc();

                echo "<p id='subject'> " .$row["sujet"]. " <span>(".$row3["nbvotes"]." votes)</span></p>";
                
                echo '<div class="options">';

                $query = "SELECT * FROM options WHERE pollId = $pollId order by id;";
                $result2 = $conn->query($query);

                $options = "";
                $optionsId = "";
                while ($row2 = $result2->fetch_assoc()) {
                    $optionsId .= $row2["id"].",";
                    $options .= $row2["reponse"].",";
                }
                

                $options_list = explode(",", $options);
                $options_list = array_filter($options_list);
                $options = implode("','", $options_list);
                

                $optionsId_list = explode(",", $optionsId);
                $optionsId_list = array_filter($optionsId_list);


                for ($i = 1; $i < count($options_list)+1; $i++) {
                    
                    echo '<div id="option-'.$i.'-poll-'.$pollId .'" class="option-'.$i.'" '. (isset($row["datefin"]) && $row["datefin"] > $datenow ? '' : 'onclick="sendAnswer(\'option-'.$i.'-poll-'.$pollId .'\', '.$pollId.', '.$optionsId_list[$i-1].', '.count($options_list).')"') . '>';

                    echo '<div id="progression-'.$i.'-poll-'.$pollId .'" class="option"></div>';
                    echo '<p id="percents-poll-'.$pollId.'-option-'.$i.'"</p>';
                    echo '<p id="poll-'.$pollId .'-option-'.$i.'">'.$options_list[$i-1].'</p>

                    </div>';

                    $query = "SELECT userId from answer where pollId =" . $pollId . " AND optionId = " . $optionsId_list[$i-1] . ";";
                    $result4 = $conn->query($query);
                    echo "<div id='container-answer'>";

                    echo "<div id='answer-option-" . $i . "-poll-" . $pollId . "'>";
                    $voteCount = 0;  // Compteur pour savoir quand afficher "Afficher plus"
                    while ($row4 = $result4->fetch_assoc()) {
                        $voteCount++;
                        // Ajoutez une classe pour masquer les éléments après les deux premiers
                        $class = ($voteCount > 2) ? "hidden-vote" : "showvote";
                        echo "<p class='$class'> <img src='assets/images/User Icon.png' alt='User Icon'>" . $row4["userId"] . "</p>";
                    }

                    // Affiche "Aucun vote" si aucun utilisateur n'a voté
                    if ($result4 && $result4->num_rows == 0) {
                        echo "<p>Aucun vote</p>";
                    }
                    echo "</div>";
                    // Affiche "Afficher plus" si plus de deux votes
                    if ($result4 && $result4->num_rows > 2) {
                            echo "<p id='showmore' onclick='showMore(this)'>Afficher plus</p>";
                            echo "<p id='showless' onclick='showLess(this)'>Afficher moins</p>";
                    }
                    echo "</div>";
                }
              
                echo "</div>";
                echo "<div id='details'>";
                echo '<p id="autor"> Par '. $row["auteur"].'</p>';
                echo '<p id="date">Le '.substr($row["datedebut"], 8, 2).'/'.substr($row["datedebut"], 5, 2).'/'.substr($row["datedebut"], 0, 4).'</p>';
                if ($row["secret"] > 0) {
                    echo "<p>Secret<img src='assets\images\Secret Icon.png' alt='Secret Icon'></p>";
                } else {
                    echo "<p>Publique<img src='assets\images\Public Icon Gray.png' alt='Public Icon'></p>";
                }
                echo "</div>";
                
                if ($row["secret"] < 1) {
                    echo "<div id='line'></div>";
                    echo "<div id='see-vote'>";
                    echo "<p id='seevote' onclick='showVote(this)'>Voir les votes</p>";
                    echo "<p id='hidevote' onclick='hideVote(this)'>Masquer les votes</p>";
                    echo"</div>";
                }
                
                echo "</div>";
                echo "</div>";

            
        ?>   
        </div>  
</html>
    
    
