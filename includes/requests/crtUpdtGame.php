<?php
if (isset($_POST["hNameInput"], $_POST["hostTextToCopy"])) {

    $name = substr(htmlspecialchars($_POST["hNameInput"]), 0, 50);
    $code = isset($_POST["hCodeInput"]) ? substr(htmlspecialchars($_POST["hCodeInput"]), 0, 30) : "";
    $visibility = isset($_POST["privateGame"]) ? (((bool) $_POST["privateGame"]) ? 0 : 1) : 1;

    if (isset($_POST["updateGame"])) {
        $sqlQuery = 'UPDATE tpvpGames SET code = :code, visibility = :visibility WHERE name = :name AND host = :host';

        $updateGame = $db->prepare($sqlQuery);
        $updateGame->execute([
            'code' => $code,
            'visibility' => $visibility,
            'name' => $name,
            'host' => $username
        ]);
        if ($updateGame->rowCount() == 1) {
            if (!isset($_SESSION["info"])) $_SESSION["info"] = "Partie mise à jour";
            else $_SESSION["info"] .= "\nPartie mise à jour";
        } else $_SESSION["error"] = "Impossible de mettre la partie à jour !";
    } else if (isset($_POST["createGame"])) {

        // check if game with name already exists
        $gameStatement = $db->prepare('SELECT gID FROM tpvpGames WHERE name = :name AND visibility != :visibility');
        $gameStatement->execute([
            'name' => $name,
            'visibility' => -1
        ]);
        $codes = $gameStatement->fetchAll();
        if (sizeof($codes) > 0) {
            $_SESSION["tempName"] = "";
            $_SESSION["tempCode"] = $code;
            $_SESSION["tempPrivate"] = $visibility == 1 ? false : true;
            $_SESSION["tempText"] = htmlspecialchars($_POST["hostTextToCopy"]);
            $_SESSION["error"] = "Nom de partie déjà pris !";
            $_SESSION["tempPreviousForm"] = true;

            header("Location: ./host");
            exit();
        }

        $sqlQuery = 'INSERT INTO tpvpGames(host, name, visibility, code) VALUES (:host, :name, :visibility, :code)';

        $insertGame = $db->prepare($sqlQuery);
        $insertGame->execute([
            'host' => $username,
            'name' => $name,
            'visibility' => $visibility,
            'code' => $code
        ]);

        // get gID
        $gameStatement = $db->prepare('SELECT gID FROM tpvpGames WHERE name = :name AND host = :host');
        $gameStatement->execute([
            'name' => $name,
            'host' => $username
        ]);

        $codes = $gameStatement->fetchAll();
        if (sizeof($codes) == 0) {
            $_SESSION["tempName"] = $name;
            $_SESSION["tempCode"] = $code;
            $_SESSION["tempPrivate"] = $visibility == 1 ? false : true;
            $_SESSION["tempText"] = htmlspecialchars($_POST["hostTextToCopy"]);
            $_SESSION["error"] = "Une erreur est survenue !";
            $_SESSION["tempPreviousForm"] = true;

            header("Location: ./host");
            exit();
        }
        $currentGID = $codes[0]["gID"];

        // insert words
        $textStr = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $_POST["hostTextToCopy"]));
        $sanitzedStr = preg_replace("/[^a-zA-Z\ ]+/", "", $textStr);
        $allWords = explode(" ", $sanitzedStr);
        foreach ($allWords as $key => $value) {
            $sqlQuery = 'INSERT INTO tpvpWords(game, word, flag) VALUES (:game, :word, :flag)';

            $insertGame = $db->prepare($sqlQuery);
            $insertGame->execute([
                'game' => $currentGID,
                'word' => $value,
                'flag' => 0
            ]);
        }
        $_SESSION["info"] = "Partie créée avec succès !";
    } else {
        if (!isset($_SESSION["error"])) $_SESSION["error"] = "Action inconnue";
    }
    header("Location: ./host");
    exit();
} else if (isset($_POST["stopGame"], $_POST["gameToStop"])) {

    $gameStatement = $db->prepare('SELECT gID FROM tpvpGames WHERE name = :name AND host = :host');
    $gameStatement->execute([
        'name' => htmlspecialchars($_POST["gameToStop"]),
        'host' => $username
    ]);

    $games = $gameStatement->fetchAll();
    if (sizeof($games) == 0) {
        $_SESSION["error"] = "Impossible d'arrêter la partie !";
        header("Location: ./host");
        exit();
    }
    $currentGID = $games[0]["gID"];

    $sqlQuery = 'UPDATE tpvpGames SET visibility = :visibility WHERE name = :name AND host = :host';

    $updateGame = $db->prepare($sqlQuery);
    $updateGame->execute([
        'visibility' => -1,
        'name' => htmlspecialchars($_POST["gameToStop"]),
        'host' => $username
    ]);

    $gameStatement = $db->prepare('DELETE FROM tpvpWords WHERE game = :game');
    $gameStatement->execute([
        'game' => $currentGID
    ]);

    if ($updateGame->rowCount() == 1) {
        if (!isset($_SESSION["info"])) $_SESSION["info"] = "Partie arrêtée";
        else $_SESSION["info"] .= "\nPartie arrêtée";
    } else $_SESSION["error"] = "Impossible de terminer la partie !";
    header("Location: ./host");
    exit();
}
