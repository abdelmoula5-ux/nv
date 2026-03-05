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