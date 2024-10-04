<?php
$errors = [];

function customErrorHandler($errno, $errstr, $errfile, $errline)
{
    global $errors;
    $errors[] = "Error [$errno]: $errstr in $errfile on line $errline";
    return true; // prevent the PHP default error handler from running
}

set_error_handler("customErrorHandler");

?>
