<?php

/*
Kérések feldolgozására használt fájl
Meghívja a Controller osztály megfelelő függvényeit
*/

    require_once ('./controller.php');

    if(isset($_POST['action'])){
        $contr = new Controller();
        switch($_POST['action']){
    
            case 'mysqlConnect': 
                $_SESSION['mysql'] = false;
                header('Location: ./index.php');
            break;

            case 'mongoConnect':
                $_SESSION['mongo'] = false;
                header('Location: ./index.php');
            break;

            case 'csapatBevitel':
                $contr->csapatBevitel($_POST);
                header('Location: ./index.php');
            break;

            case 'versenyzoBevitel':
                $contr->versenyzoBevitel($_POST);
                header('Location: ./index.php');
            break;
        }
    }
    