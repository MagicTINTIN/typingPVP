<?php session_start();
function safeStr($input) : string
{
    return str_replace("\n","\\n",str_replace("\\","\\\\",$input));
}

if (isset($_GET["json"])) {

    include_once("includes/db.php");
    $db = dbConnect();
    $db = dbConnect();
    $citationsStatement = $db->prepare('SELECT * FROM citations');
    $citationsStatement->execute();
    $citations = $citationsStatement->fetchAll();
    $i = 0;
    echo "[";
    foreach ($citations as $key => $value) {
        if ($value["status"] < 1) continue;
        if ($i > 0)
            echo ",";
        echo "{\"ID\":\"" . safeStr($value["ID"]) . "\"\"author\":\"" . safeStr($value["author"]) . "\",\"citation\":\"" . safeStr($value["citation"]) . "\",\"date\":\"" . $value["date"] . "\"}";
        $i++;
    }
    echo "]";
    exit();
}
// include_once("includes/cas.php");
$promoted = array('serviere', 'v_lasser');
$admin = array('serviere');
$username = 'serviere'; #phpCAS::getUser();
include_once("includes/db.php");
include_once("includes/time.php");
$db = dbConnect();

if (isset($_POST["citationInput"]) && isset($_POST["authorInput"]) && isset($_POST["dateInput"]) && isset($_POST["citationInput"]) && isset($_POST["newCitationSubmit"])) {

    if (strlen(htmlspecialchars($_POST["citationInput"])) >= 4096 || strlen(htmlspecialchars($_POST["authorInput"])) >= 255) {
        header("Refresh:0");
        exit();
    }

    $sqlQuery = 'INSERT INTO citations(date, citation, author, username) VALUES (:date, :citation, :author, :username)';

    $insertCitation = $db->prepare($sqlQuery);
    $insertCitation->execute([
        'date' => htmlspecialchars($_POST["dateInput"]),
        'citation' => htmlspecialchars($_POST["citationInput"]),
        'author' => htmlspecialchars($_POST["authorInput"]),
        'username' => $username
    ]);
    header("Refresh:0");
    exit();
} else if (isset($_POST["deletemsg"]) && isset($_POST["delID"])) {
    $db = dbConnect();
    $citationsStatement = $db->prepare('SELECT username FROM citations WHERE ID = :ID');
    $citationsStatement->execute([
        'ID' => htmlspecialchars($_POST["delID"])
    ]);
    $citations = $citationsStatement->fetchAll();
    if (sizeof($citations) > 0 && in_array($username, $promoted) || $citations[0]["username"] == $username) {
        $sqlQuery = 'UPDATE citations SET status = :status WHERE ID = :ID';

        $updatePlates = $db->prepare($sqlQuery);
        $updatePlates->execute([
            'ID' => htmlspecialchars($_POST["delID"]),
            'status' => 0
        ]);
    }

    header("Refresh:0");
    exit();
} else if (isset($_POST["ultradeletemsg"]) && isset($_POST["udelID"]) && in_array($username, $admin)) {
    $sqlQuery = 'UPDATE citations SET status = :status WHERE ID = :ID';

    $updatePlates = $db->prepare($sqlQuery);
    $updatePlates->execute([
        'ID' => htmlspecialchars($_POST["udelID"]),
        'status' => -1
    ]);

    header("Refresh:0");
    exit();
} else if (isset($_POST["restoremsg"]) && isset($_POST["resID"]) && in_array($username, $promoted)) {
    $sqlQuery = 'UPDATE citations SET status = :status WHERE ID = :ID';

    $updatePlates = $db->prepare($sqlQuery);
    $updatePlates->execute([
        'ID' => htmlspecialchars($_POST["resID"]),
        'status' => 1
    ]);

    header("Refresh:0");
    exit();
} else if (isset($_POST["ultrarestoremsg"]) && isset($_POST["uresID"]) && in_array($username, $admin)) {
    $sqlQuery = 'UPDATE citations SET status = :status WHERE ID = :ID';

    $updatePlates = $db->prepare($sqlQuery);
    $updatePlates->execute([
        'ID' => htmlspecialchars($_POST["uresID"]),
        'status' => 0
    ]);

    header("Refresh:0");
    exit();
} else if (isset($_POST["verifymsg"]) && isset($_POST["verID"]) && in_array($username, $promoted)) {
    $sqlQuery = 'UPDATE citations SET status = :status WHERE ID = :ID';

    $updatePlates = $db->prepare($sqlQuery);
    $updatePlates->execute([
        'ID' => htmlspecialchars($_POST["verID"]),
        'status' => 2
    ]);

    header("Refresh:0");
    exit();
} else if (isset($_POST["unverifymsg"]) && isset($_POST["unverID"]) && in_array($username, $promoted)) {
    $sqlQuery = 'UPDATE citations SET status = :status WHERE ID = :ID';

    $updatePlates = $db->prepare($sqlQuery);
    $updatePlates->execute([
        'ID' => htmlspecialchars($_POST["unverID"]),
        'status' => 1
    ]);

    header("Refresh:0");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi" />
    <title>TypingPVP</title>

    <script src="./scripts/commonhead.js"></script>
    <link href="./styles/animations.css" rel="stylesheet">
    <link href="./styles/vars.css" rel="stylesheet">
    <link href="./styles/issue.css" rel="stylesheet">
    <link href="./styles/common.css" rel="stylesheet">
    <link href="./styles/citation.css" rel="stylesheet">
    <meta name="author" content="MagicTINTIN">
    <meta name="description" content="Un site pour recenser les pépites entendues en CM">

    <link rel="icon" type="image/x-icon" href="images/favicon.png">

    <meta property="og:type" content="website" />
    <meta property="og:title" content="TypingPVP">
    <meta property="og:description" content="Un site pour recenser les pépites entendues en CM">

    <meta property="og:image" content="https://etud.insa-toulouse.fr/~serviere/citations/images/favicon.png">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:alt" content="Logo of TypingPVP">

    <meta property="og:url" content="https://etud.insa-toulouse.fr/~serviere/citations" />
    <meta data-react-helmet="true" name="theme-color" content="#43ceed" />
</head>

<body>
    <?php include_once("./includes/nojs.php"); ?>
    <?php include_once("./includes/infoanderror.php"); ?>
    <main>
        <h1>TypingPVP</h1>
        <p class="underH1" title="Tant que ça ne porte pas atteinte à l'intégrité de la personne... bien évidemment">Enregistrez les pépites entendues en CM</p>
        <form method="post" class="citationForm">
            <div class='citationZone zone'><span class='citationCommon'>"</span><textarea oninput="autoGrow(this)" class="citationInput citationCommon" name="citationInput" id="citationInput" required maxlength="1024" placeholder="La citation"></textarea><span class='citationCommon closingInput'>"</span></div>

            <div class='authorDateZone authorDateZoneInput zone2'><input type="text" class="input authorDateInput authorDateCommon authorInput" name="authorInput" id="authorInput" required maxlength="250" placeholder="Quelqu'un">
                <input type="date" class="input authorDateInput authorDateCommon dateInput" id="dateInput" name="dateInput" value="<?php echo date('Y-m-d') ?>" required>
            </div>

            <div class='zone3'>
                <input type="submit" class="input citationSubmit" id="newCitationSubmit" value="Ajouter la citation" name="newCitationSubmit">
            </div>

        </form>
        <ul>
            <?php
            $db = dbConnect();
            $citationsStatement = $db->prepare('SELECT * FROM citations');
            $citationsStatement->execute();
            $citations = $citationsStatement->fetchAll();

            foreach (array_reverse($citations) as $key => $value) {
                if (in_array($username, $promoted) && (isset($_GET["mod"])  || isset($_GET["deleted"]) || isset($_GET["unverified"]))) {
                    if (in_array($username, $admin) && $value["status"] < 0 && (isset($_GET["mod"])  || isset($_GET["deleted"]))) {
                        echo "<li class='ultradeletedCitation'>";
                    }
                    if ($value["status"] == 0 && (isset($_GET["mod"])  || isset($_GET["deleted"]))) {
                        echo "<li class='deletedCitation'>";
                    } else if ($value["status"] == 1 && (isset($_GET["mod"])  || isset($_GET["unverified"]))) {
                        echo "<li class='unverifiedCitation'>";
                    } else if ($value["status"] > 1 && (isset($_GET["mod"]))) {
                        echo "<li class='verifiedCitation'>";
                    }
                    if ((isset($_GET["mod"]) && $value["status"] >= 0) || (isset($_GET["ultradeleted"]) && $value["status"] < 0 && in_array($username, $admin)) || (isset($_GET["deleted"]) && $value["status"] == 0) || (isset($_GET["unverified"]) && $value["status"] == 1)) {
                        echo "<div class='citationZone zone'><span class='citation citationCommon'>\"" . $value["citation"] . "\"</div>
                        <div class='authorDateZone zone adzCitation'><span class='authorDate authorDateCommon'>" . $value["author"] . " - " . $value["date"] . "";
                        if (in_array($username, $promoted) || $username == $value["username"]) {
                            if (isset($_GET["ultradeleted"]) && $value["status"] < 0 && in_array($username, $admin)) { ?>
                                <div class="delMsgDiv">
                                    <span onclick="createMessage('confirm', 'Unultradelete citation ?', 'Are you sure you want to unultradelete this citation?', 'ultrarestoremsg', 'Unultradelete', 'uresID', '<?php echo $value['ID']; ?>')" class="delMsgSpan">Unultradelete</span>
                                </div>
                            <?php } else if ($value["status"] == 0 && (isset($_GET["mod"])  || isset($_GET["deleted"]))) {
                            ?>
                                <div class="delMsgDiv">
                                    <span onclick="createMessage('confirm', 'Restore citation ?', 'Are you sure you want to restore this citation?', 'restoremsg', 'Restore', 'resID', '<?php echo $value['ID']; ?>')" class="delMsgSpan">Restore</span>
                                </div>
                                <?php
                                if (in_array($username, $admin)) {
                                ?>
                                    <div class="delMsgDiv">
                                        <span onclick="createMessage('confirm', 'Ultradelete citation ?', 'Are you sure you want to ultradelete this citation?', 'ultradeletemsg', 'Ultradelete', 'udelID', '<?php echo $value['ID']; ?>')" class="delMsgSpan">Ultradelete</span>
                                    </div>
                                <?php }
                            } else {
                                ?>
                                <div class="delMsgDiv">
                                    <span onclick="createMessage('confirm', 'Delete citation ?', 'Are you sure you want to delete this citation?', 'deletemsg', 'Delete', 'delID', '<?php echo $value['ID']; ?>')" class="delMsgSpan">Delete</span>
                                </div>
                            <?php
                            }
                            if ($value["status"] == 1 && (isset($_GET["mod"])  || isset($_GET["unverified"]))) {
                            ?>
                                <div class="delMsgDiv">
                                    <span onclick="createMessage('confirm', 'Verify citation ?', 'Are you sure you want to verify this citation?', 'verifymsg', 'Verify', 'verID', '<?php echo $value['ID']; ?>')" class="verMsgSpan">Verify</span>
                                </div>
                            <?php
                            } else if ($value["status"] > 1 && isset($_GET["mod"])) {
                            ?>
                                <div class="delMsgDiv">
                                    <span onclick="createMessage('confirm', 'Unverify citation ?', 'Are you sure you want to unverify this citation?', 'unverifymsg', 'Unverify', 'unverID', '<?php echo $value['ID']; ?>')" class="unverMsgSpan">Unverify</span>
                                </div>
                        <?php
                            }
                        }
                    }
                    echo "</div></li>";
                } else if ($value["status"] >= 1) {
                    echo "<li>
                <div class='citationZone zone'><span class='citation citationCommon'>\"" . $value["citation"] . "\"</div>
                <div class='authorDateZone zone adzCitation'><span class='authorDate authorDateCommon'>" . $value["author"] . " - " . $value["date"] . "";
                    if (in_array($username, $promoted) || $username == $value["username"]) {
                        ?>
                        <div class="delMsgDiv">
                            <span onclick="createMessage('confirm', 'Delete citation ?', 'Are you sure you want to delete this citation?', 'deletemsg', 'Delete', 'delID', '<?php echo $value['ID']; ?>')" class="delMsgSpan">Delete</span>
                        </div>
            <?php
                    }
                    echo "</div></li>";
                }
            }
            ?>
        </ul>
    </main>
    <script src="./scripts/common.js"></script>
</body>

</html>