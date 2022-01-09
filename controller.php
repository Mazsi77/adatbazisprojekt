<?php

/*
Kontroller osztály
Átadja a megjelenítendő adatokat az index.php-nek
Meghívja mindkét adatbázis megfelelő függvényeit
*/

require_once('./mysqlDb.php');
require_once('./mongo.php');

class Controller
{
    
    private $mysqlDb;
    private $mongoDb;

    //adatbázis osztályok példányosítása
    public function __construct()
    {
        $this->mysqlDb = new MySqlDb();
        $this->mongoDb = new Mongo();
    }

    //Visszaküldi az index.php-nek a megjelenítendő adatokat
    public function setUp()
    {
        $data = array();

        $data['mysql'] = isset($_SESSION['mysql']) ? $this->mysqlDb->connect() : "Nincs kapcsolat";
        $data['mysqlCsapatok'] = $this->mysqlDb->getCsapatok();
        $data['mysqlVersenyzok'] = $this->mysqlDb->getVersenyzok();

        $data['mongo'] = isset($_SESSION['mongo']) ? $this->mongoDb->connect() : "Nincs kapcsolat";
        $data['mongoCsapatok'] = $this->mongoDb->getCsapatok();
        $data['mongoVersenyzok'] = $this->mongoDb->getVersenyzok();


        return $data;
    }

    public function csapatBevitel($data)
    {
        $this->mysqlDb->insertCsapat($data);
        $this->mongoDb->insertCsapat($data);
    }

    public function versenyzoBevitel($data)
    {
        $this->mysqlDb->insertVerrsenyzo($data);
        $this->mongoDb->insertVersenyzo($data);
    }

}


?>