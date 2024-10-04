<?php session_start();

// include_once("includes/cas.php");
$promoted = array('serviere', 'v_lasser');
$admin = array('serviere', 'v_lasser');
$username = 'serviere'; #phpCAS::getUser();
include_once("includes/fcts/db.php");
include_once("includes/fcts/time.php");
$db = dbConnect();

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
    <meta name="author" content="MagicTINTIN, Atsuyo64">
    <meta name="description" content="Testez vos skills de dactylographie en équipe">

    <link rel="icon" type="image/x-icon" href="images/favicon.png">

    <meta property="og:type" content="website" />
    <meta property="og:title" content="TypingPVP">
    <meta property="og:description" content="Testez vos skills de dactylographie en équipe">

    <meta property="og:image" content="https://etud.insa-toulouse.fr/~serviere/typingPVP/images/favicon.png">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:alt" content="Logo of TypingPVP">

    <meta property="og:url" content="https://etud.insa-toulouse.fr/~serviere/typingPVP" />
    <meta data-react-helmet="true" name="theme-color" content="#43ceed" />
</head>

<body class="semiTilesBG">
    <?php include_once("./includes/modules/nojs.php"); ?>
    <?php include_once("./includes/modules/infoanderror.php"); ?>
    <main>
        <h1>TypingPVP</h1>
        <form method="post">
            <input type="text" class="input authorDateInput authorDateCommon gameInput" name="gameInput" id="gameInput" required maxlength="250" placeholder="Nom de la partie">
                

            <div class='zone3'>
                <input type="submit" class="input citationSubmit" id="enterGame" value="Rejoindre la partie" name="enterGame">
            </div>

        </form>
    </main>
    <script src="./scripts/common.js"></script>
</body>

</html>