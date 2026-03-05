<?php 
    require "config/config.php";
    session_start();
    $session_duration = 600;

    // Vérifier si un horodatage de session existe
    if (isset($_SESSION['last_activity'])) {
        // Vérifier si la session a expiré
        if (time() - $_SESSION['last_activity'] > $session_duration) {
            // La session a expiré, on la détruit
            session_unset();
            session_destroy();
            header('Location: login.php'); // Rediriger vers la page de login
            exit();
        }
    }

    // Mettre à jour l'horodatage de la dernière activité
    $_SESSION['last_activity'] = time();
    if(!isset($_SESSION['user_statut'])) header('Location: login.php');
    $page = isset($_GET['page']) ? $_GET['page'] : 'home';
    
    if (isset($_POST["create"])) {

        $subject = $_POST["subject"];
        $options = $_POST["options"];
        
        $color = $_POST["color"];

        $startdate = (new DateTime("now", new DateTimeZone("America/Martinique")))->format('Y-m-d H:i');;


        if (!isset($_POST["illimitedduration"])) {
            $datetime = new DateTime($startdate);
            $enddate = ($_POST["date"] != "") ? $_POST["date"] : $datetime->modify("+".($_POST["day"] * 24 * 60) + ($_POST["hour"] * 60) + $_POST["minutes"]." minutes")->format("Y-m-d H:i");
        }

        $secret = isset($_POST["secret"]) ? $_POST["secret"] : 0;
        $private = isset($_POST["private"]) ? $_POST["private"] : 0;

        if ($private == 1) {
            $code = random_int(100000, 999999);
            $fileName = $code . ".php"; 
        }


        $sql = "INSERT INTO poll (sujet, auteur, datedebut" . (isset($enddate) ? ", datefin" : "") . ", secret, privee" . ($private == 1 ? ", randomcode" : "") . ", color) 
        VALUES (?, ?, ?, " . (isset($enddate) ? "?," : "") . "?, ?," . ($private == 1 ? "?, " : "") ." ?)";

        
        $query = $conn->prepare($sql);
        
        if (isset($enddate)) {
            if ($private == 1) {
                $query->bind_param("ssssiiss", $subject, $_SESSION['name'], $startdate, $enddate, $secret, $private, $code, $color);
            } else {
                $query->bind_param("ssssiis", $subject, $_SESSION['name'], $startdate, $enddate, $secret, $private, $color);
            }
            
        } else {
            if ($private == 1) {
                $query->bind_param("sssiiss", $subject, $_SESSION['name'], $startdate, $secret, $private, $code, $color);
            } else {
                $query->bind_param("sssiis", $subject, $_SESSION['name'], $startdate, $secret, $private, $color);
            }  
        }

        $query->execute();
       
        $sondage_id = $conn->insert_id;

        $query = $conn->prepare("INSERT INTO options (pollid, reponse) VALUES (?, ?)");

        // Lier les paramètres pour chaque option de réponse
        foreach ($options as $option) {
            $query->bind_param("is", $sondage_id, $option); // 'i' pour l'ID, 's' pour la réponse
            $query->execute(); // Exécuter la requête pour chaque réponse
        }
        $query->close();

        

        if ($private == 1) {

            $sql = 'SELECT color, datefin FROM poll WHERE id = ?';

            // Préparation de la requête
            $query = $conn->prepare($sql);
        
            $query->bind_param('i', $sondage_id);

            // Exécution de la requête
            $query->execute();

            // Récupération du résultat
            $result = $query->get_result();
            $row = $result->fetch_assoc();
            $datenow = new DateTime("now", new DateTimeZone("America/Martinique"));
            
            $url = "http://localhost/devweb/poll/fetchpoll.php";
            $start = '<!DOCTYPE html>
                        <html lang="en">
                        <head>
                            <meta charset="UTF-8">
                            <meta name="viewport" content="width=device-width, initial-scale=1.0">
                            <link rel="stylesheet" href="assets/css/reset.css">
                            <link rel="stylesheet" href="assets/css/style.css">
                            <link rel="stylesheet" href="assets/css/create-poll-style.css">
                            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                            <script src="assets/js/script.js"></script>
                            <script> showPoll('.$sondage_id.', \''.$row['color'].'\', '.$bool = (isset($row['datefin']) && $row['datefin'] < $datenow ? '\'yes\'' : '\'no\'') .') </script>
                            <title>Document</title>
                        </head>
                        <body>
                        <main>';
        
            $end = '</main>
                    </body>
                    </html>';
        
            
            file_put_contents($fileName, $start); // Écrit contenu1 dans le fichier
            file_put_contents($fileName, $end, FILE_APPEND); // Ajoute contenu2 au fichier
            
        }

        
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/reset.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/create-poll-style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js"></script>
    
    <title>Document</title>
</head>
<body>
    <nav>
        <img id="main-logo" src="assets\images\White logo.png" alt="Logo">
        <button onclick="showpopup()" id="button-append-poll"><img src="assets\images\Poll Icon.png" alt="">Créer un sondage</button>
        <p id="home" onclick="showContent('home')">Home</p>
        <p id="mypolls" onclick="showContent('mypolls')">Mes sondages</p>
    </nav>

    <main id="main-content"> 
        
    </main>
    <div id="popup-new-poll" class="popup-new-poll">
            <div onclick="closepopup()" class="popup-overlay"></div>
            <div class="new-poll-content">
                <aside>
                    <h2><img src="assets\images\Poll Icon Black.png" alt="Poll Icon">Créer un sondage</h2>
                    <div id="stagesselectors">
                        <div class="stageselector1 selectorfocus" onclick="showStage(this)"><img src="assets\images\Text Icon.png" alt="Text Icon"><p>Sujet & Réponses</p></div>
                        <div class="stageselector2" onclick="showStage(this)"><img src="assets\images\Calendar Icon.png" alt="Calendar Icon"><p>Durée du Sondage</p></div>
                        <div class="stageselector3" onclick="showStage(this)"><img src="assets\images\Advenced Settings Icon.png" alt="Advenced Settings Icon"><p>Paramètres Avancés</p></div>
                    </div>
                    
                    <div id="stageprogression">
                        <p>Étape 1 sur 3</p>
                        <div id="stageprogression-bar">
                            <div id="stagecompletion-bar"></div>
                        </div>
                    </div>
                </aside>
                <div id="poll-content">
                    <form action="" method="POST">
                        <div id="stage-1">
                            <h2>Sujet & Réponses</h2>

                            <div id="inputpollsubject">
                                <label for="subject">Sujet du sondage </label>
                                <input type="text" name="subject" placeholder="Entrez le titre ou la question" required>
                            </div>
                            

                            <div id="options-list">
                                <label for="options">Options </label>
                                
                                <div id="options">
                                    <div id="option">
                                        <input type="text" name="options[]" placeholder="Entrez une option" required>
                                        <img id="optionremove" src="assets\images\Cross Icon.png" alt="Cross Icon" onclick="removeinput(this)">
                                    </div>
                                    <div id="option">
                                        <input type="text" name="options[]" placeholder="Entrez une option" required>
                                        <img id="optionremove" src="assets\images\Cross Icon.png" alt="Cross Icon" onclick="removeinput(this)">
                                    </div>
                                    <p id="plus" type="button" onclick="createinput(this)"> + Ajouter une option</p>
                                </div>
                                
                            </div>

                            <div class="buttons">
                                <button class="button cancel" onclick="cancelNewPoll(this)"type="button">Annuler</button>
                                <button class="button continue" onclick="nextStage(this)" type="button">Continuer</button>
                            </div>
                            
                        </div>
                        <div id="stage-2">
                            <h2>Durée du Sondage</h2>
                            <p>Durée</p>
                            <div id="duration-option3">
                                    <p>Durée illimitée</p> <label class="switch"><input name="illimitedduration" type="checkbox" id="illimitedduration" checked /><span class="slider"></span></label>
                            </div>
                            <p>Options de durée</p>
                            <div id="mask"></div>
                            <div id="duration-options">
                                <div id="focusedoption"></div>
                                <p onclick="showDurationOption(this)" id="duration-option1">Définir une date de fin</p>
                                <p onclick="showDurationOption(this)" id="duration-option2">Définir une durée</p>
                            </div>
                            <div id="duration-option">
                                <div id="duration-input1">
                                    <label for="date">Selectionnez une date</label>
                                    <input type="date" name="date" id="date">
                                </div>
                                <div id="duration-input2">
                                    <select name="day">
                                        <option value="0">0 jour</option>
                                        <option value="1">1 jour</option>
                                        <?php 
                                        for ($i = 2; $i < 32; $i ++) {
                                            echo '<option value="'.$i.'">'.$i.' jours</option>';
                                        }
                                        ?>
                                    </select>
                                    <select name="hour">
                                        <option value="0">0 heure</option>
                                        <option value="1">1 heure</option>
                                        <?php 
                                        for ($i = 2; $i < 24; $i ++) {
                                            echo '<option value="'.$i.'">'.$i.' heures</option>';
                                        }
                                        ?>
                                    </select>
                                    <select name="minutes">
                                        <option value="0">0 minute</option>
                                        <option value="1">1 minute</option>
                                        <?php 
                                        for ($i = 2; $i < 60; $i ++) {
                                            echo '<option value="'.$i.'">'.$i.' minutes</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="buttons">
                                <button class="button cancel" onclick="cancelNewPoll(this)"type="button">Annuler</button>
                                <button class="button continue" onclick="nextStage(this)" type="button">Continuer</button>
                            </div>
                        </div>
                        <div id="stage-3">
                            <h2>Paramètres Avancés</h2>
                            <p>Apparence</p>
                            <div id="appearence">
                                <div id="colorpoll">
                                    <p>Couleur du sondage</p>
                                    <div id="colorpalet">
                                        <label class="color one"><input type="radio" name="color" value="rgb(132, 255, 202)"></label>
                                        <label class="color two"><input type="radio" name="color" value="rgb(62, 181, 255)"></label>
                                        <label class="color three colorfocus"><input type="radio" name="color" value="rgb(123, 141, 255)" checked></label>
                                        <label class="color four"><input type="radio" name="color" value="rgb(210, 141, 255)"></label>
                                        <label class="color five"><input type="radio" name="color" value="rgb(255, 108, 110)"></label>
                                        <label class="color six"><input type="radio" name="color" value="rgb(255, 152, 62)"></label>
                                        <label class="color seven"><input type="radio" name="color" value="rgb(0, 0, 0)"></label>
                                    </div>
                                </div>
                                <p>Aperçu</p>
                                <div id="minipoll">
                                    <p>Votre question ici...</p>
                                    <div id="minioptions">
                                        <div class="minioption option1"><img src="assets\images\Rectangle Icon.png" alt="Rectangle Icon"></div>
                                        <div class="minioption option2"><img src="assets\images\Circle Icon.png" alt="Circle Icon"></div>
                                        <div class="minioption option3"><img src="assets\images\Triangle Icon.png" alt="Triangle Icon"></div>
                                        <div class="minioption option4"><img src="assets\images\Star Icon.png" alt="Star Icon"></div>
                                    </div>
                                </div>
                            </div>
                            
                            
                            <p id="confidentiality">Confidentialité</p>
                            <div id="confidentialityoptions">
                                <div id="confidentialityoption1"><p>Sondage Secret</p> <label class="switch"><input name="secret" value="1" type="checkbox"><span class="slider"></span></label></div>
                                <div id="confidentialityoption2"><p>Sondage Privée</p> <label class="switch"><input name="private" value="1" type="checkbox"><span class="slider"></span></label></div>
                            </div>
                            <div class="buttons">
                                <button class="button cancel" onclick="cancelNewPoll(this)"type="button">Annuler</button>
                                <button class="button continue" type="submit" name="create">Créer</button>
                            </div>
                        </div>
                    </form>
            
        </div>  
 
    
        <script>
            const page = "<?php echo $page; ?>";
            showContent(page)
        </script>
</body>
</html>