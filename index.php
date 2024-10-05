<?php session_start();

// include_once("includes/cas.php");
// $promoted = array('serviere', 'v_lasser');
// $admin = array('serviere', 'v_lasser');
// $username = 'serviere'; #phpCAS::getUser();
include_once("includes/fcts/db.php");
include_once("includes/fcts/time.php");
$db = dbConnect();

include_once("includes/parts/head.php");

include_once("includes/modules/errorHandler.php");
include_once("includes/parts/welcome.php");
include_once("includes/parts/bottom.php")
?>

</html>