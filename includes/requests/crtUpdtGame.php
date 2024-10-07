<?php
if (isset($_POST["hNameInput"], $_POST["hostTextToCopy"])) {

    $name = substr(htmlspecialchars($_POST["hNameInput"]), 0, 50);
    $code = isset($_POST["hCodeInput"]) ? substr(htmlspecialchars($_POST["hCodeInput"]), 0, 30) : "";
    $visibility = isset($_POST["privateGame"]) ? (((bool) $_POST["privateGame"]) ? 0 : 1):1;

    if (isset($_POST["updateGame"])) {
        if (!isset($_SESSION["info"])) $_SESSION["info"] = "Game pas update";
    } else if (isset($_POST["createGame"])) {

        // check if game with name already exists
        $cpoiStatement = $db->prepare('SELECT gID FROM tpvpGames WHERE name = :name');
        $cpoiStatement->execute([
            'name' => $name
        ]);
        $codes = $cpoiStatement->fetchAll();
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
        $cpoiStatement = $db->prepare('SELECT gID FROM tpvpGames WHERE name = :name AND host = :host');
        $cpoiStatement->execute([
            'name' => $name,
            'host' => $username
        ]);

        $codes = $cpoiStatement->fetchAll();
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
}
