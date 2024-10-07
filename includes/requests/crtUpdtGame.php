<?php
if (isset($_POST["hNameInput"], $_POST["hCodeInput"], $_POST["privateGame"], $_POST["hostTextToCopy"])) {
    if (isset($_POST["updateGame"])) {

    } else if (isset($_POST["createGame"])) {
        $sqlQuery = 'INSERT INTO tpvpGames(host, name, visibility, code) VALUES (:host, :name, :visibility, :code)';

        $insertCPoi = $db->prepare($sqlQuery);
        $insertCPoi->execute([
            'host' => $username,
            'type' => $type,
            'value' => $content,
            'code' => $codeVal
        ]);
    }
}
header("Location: ./host");
exit();
