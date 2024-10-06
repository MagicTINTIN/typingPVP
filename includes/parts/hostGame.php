<body> <!-- class="semiTilesBG" -->
    <?php include_once("includes/parts/bodyBegin.php"); ?>
    <!-- <nav class="hostNav"></nav> -->
    <main id="hostMain">
        <aside id="hostAside">
            <div id="hostUsrName">
                <?php echo $username ?>
            </div>
            <?php if (in_array($username, $admin)) { ?>
                <section id="hAdminActions">
                    <h4 class="hAdminPanelTitle">ADMIN PANEL</h4>
                    <span onclick="createMessage('confirm', 'Arrêter toutes les parties ?', 'Êtes-vous sûr de vouloir stopper toutes les parties en cours ? Toutes les données associées seront supprimées', 'stopAll', 'Arrêter')" class="hostAdminActionButton">Arrêter toutes les parties</span>
                    <hr class="adminPanelHR">
                    <span onclick="createMessage('confirm', 'Suppimer toutes les données ?', 'Êtes-vous sûr de vouloir supprimer toutes les données de toutes les bdd ?', 'deleteAll', 'Supprimer')" class="hostAdminActionButton">Supprimer toutes les données</span>
                </section>
            <?php } ?>
        </aside>
        <section id="hostMainSection">
            <h1 class="hostTitle">Parties créées</h1>
            <section id="currentGame" class="hostSection">
                <?php
                $gamesStatement = $db->prepare('SELECT * FROM tpvpGames WHERE host=:host AND visibility=:visibility');
                $gamesStatement->execute([
                    'host' => $username,
                    'visibility' => -1
                ]);

                $games = $gamesStatement->fetchAll();
                $runningGame = false;
                if (sizeof($games) == 0) {
                } else {
                    $runningGame = true;
                };
                ?>
                <h2 class="hostSectionTitle"><?php echo $runningGame ? "Modifier la partie" : "Créer une partie" ?></h2>
                <div class="hostSectionContent">
                    <form method="post" class="saHostForm <?php echo $runningGame ? "update" : "create" ?>GameForm">
                        <input class="hostInput" type="text" value="<?php echo $runningGame ? $games[0]["name"] : "Partie de " . $username ?>" <?php echo $runningGame ? "readonly" : "" ?> required name="hNameInput" id="hNameInput" minlength="3" maxlength="50" placeholder="Nom de la partie">

                        <input class="hostInput" type="text" name="hCodeInput" id="hCodeInput" minlength="3" maxlength="50" placeholder="Code pour accéder à la partie (facultatif)">

                        <label class="container privateGame">Partie privée <span class="smaller">(nécessite d'avoir le nom de la partie pour rejoindre)</span>
                            <input name="privateGame" id="privateGame" type="checkbox">
                            <span class="checkmark"></span>
                        </label>

                        <textarea class="hostWords" name="hostTextToCopy" id="hostTextToCopy" required placeholder="Entrez le texte à faire écrire" <?php echo $runningGame ? "readonly" : "" ?>></textarea>

                        <input type="submit" class="input joinSubmit" id="<?php echo $runningGame ? "update" : "create" ?>Game" value="<?php echo $runningGame ? "Mettre à jour" : "Créer" ?> la partie" name="<?php echo $runningGame ? "update" : "create" ?>Game">
                    </form>
                    <?php if (sizeof($games) == 1) { ?>
                        <form method="post" class="stopGameForm">
                            <input type="submit" class="input stopSubmit" id="stopGame" value="Arrêter la partie" name="stopGame">
                        </form>
                </div>
            <?php } ?>
            </section>
            <section id="pastGames" class="hostSection">
                <h2 class="hostSectionTitle">Parties précédemment créées</h2>
                <?php

                $db = dbConnect();
                $oldgamesStatement = $db->prepare('SELECT * FROM tpvpGames WHERE host=:host AND visibility=:visibility');
                $oldgamesStatement->execute([
                    'host' => $username,
                    'visibility' => -1
                ]);
                $oldgames = $oldgamesStatement->fetchAll();

                $num = 0;
                foreach (array_reverse($oldgames) as $key => $value) {
                    $num++;
                    $result = explode(";", $value["postStats"])
                ?>
                    <div class="oldGame" id="gameN-<?php echo $num ?>" style="background: linear-gradient(90deg, var(--ov) <?php echo $result[0] ?>, var(--cv) <?php echo $result[0] ?>);">
                        <h4 class="oldGameName"><?php echo $value["name"] ?></h4>
                        <span class="oldGameDate"><?php echo $value["started"] ?></span>
                        <p class="oldGameDescription"><?php echo $result[1] ?></p>
                    </div>
                <?php
                }
                if (sizeof($oldgames) == 0) { ?>
                    <span id="hostNoGamesFound">Aucune partie trouvée :/</span>
                <?php } ?>
            </section>
        </section>
    </main>