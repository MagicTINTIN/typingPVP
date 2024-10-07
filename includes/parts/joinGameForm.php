<div class="joinGameForms">
    <h3 class="jgH3">Rejoindre une partie publique</h3>

    <section id="publicGames">
        <?php

        $db = dbConnect();
        $gamesStatement = $db->prepare('SELECT * FROM tpvpGames WHERE visibility=:visibility');
        $gamesStatement->execute([
            'visibility' => 1
        ]);
        $games = $gamesStatement->fetchAll();

        $num = 0;
        foreach (array_reverse($games) as $key => $value) {
            $num++;
            // $result = explode(";", $value["postStats"])
        ?>
            <div class="oldGame" id="gameN-<?php echo $num ?>" style="background: linear-gradient(90deg, var(--ov) 50%, var(--cv) 50%);">
                <h4 class="oldGameName"><?php echo $value["name"] ?></h4>
                <span class="oldGameDate"><?php echo $value["started"] ?></span>
                <p class="oldGameDescription"><?php echo "Partie en cours" ?></p>
            </div>
        <?php
        }
        if (sizeof($games) == 0) { ?>
            <span id="hostNoGamesFound">Aucune partie trouvée :/</span>
        <?php } ?>
    </section>
    </section>

    <h3 class="jgH3">Rejoindre une partie privée</h3>
    <form method="post" class="connectionForm saHostForm">
        <input type="text" class="input authorDateInput authorDateCommon gameInput" name="gameInput" id="gameInput" required maxlength="250" placeholder="Nom de la partie">
        <input type="submit" class="input joinSubmit" id="enterGame" value="Rejoindre la partie" name="enterGame">
    </form>

    <h3 class="jgH3">Créer une partie</h3>
    <a href="host" class="aSaButton">Créer une partie</a>
</div>