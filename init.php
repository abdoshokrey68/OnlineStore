<?php
    

    ini_set('display_errors', 'On');
    error_reporting(E_ALL);
    include     'Admin/connect.php';
    
    $temp       = 'inc/temp/';

    $js         = 'layout/js/';

    $css        = 'layout/css/';

    $lang       = "inc/lang/";

    $func       = 'inc/function/';


    include     $lang .     "en.php";

    include     $func .     'func.php';
    
    include     $temp .     "header.php";

    include     $temp .     "nav.php";
    