<?php

/*
Mongo db-t kezelő osztály
A $_SESSION['mongo'] jelzi a kapcsolat állapotát:
    -Nem létezik: nem volt kapcsolódási kérés
    -False: sikertelen kapcsolat (hiba)
    -True: sikeres kapcsolódás
*/
require_once './vendor/autoload.php';

class Mongo
{
    //Adatbázis kapcsolódási adatok, kollekció és adatbázis neve
    private $host = 'localhost';
    private $port = '27017';
    private $dbName = "verseny";
    private $collectionName = "csapatok";

    private $m;
    private $db;
    private $collection;

    //beállítja autómatikusan a kapcsolatot, ha már meg volt nyomva a kapcsolódás
    public function __construct()
    {
        if(!isset($_SESSION['mongo'])){
            return;
        }

        else{
           $this->connect();
        }
    }
    
    //kapcsolat létrehozása, hibaüzenetek visszaküldése
    public function connect()
    {
        //kapcsolódás a szerverhez
        $_SESSION['mongo'] = false;
        $this->m = new MongoDB\Client("mongodb://$this->host:$this->port");
        if(!$this->m){
            return "Kapcsolódási hiba!";
        }

        //Adatbázis kiválasztása
        $this->db = $this->m->selectDatabase($this->dbName);
        if(!$this->db){
            return "Adatbázis választási hiba!";
        }

        //Kollekció kiválasztása
        $this->collection = $this->db->selectCollection($this->collectionName);
        if(!$this->collection){
            return "Kollekció választási hiba!";
        }

        $_SESSION['mongo'] = true;
        return "Sikeres csatlakozás";
    }

    //Csapatok visszaküldése csak az id és nev oszlopokkal
    public function getCsapatok()
    {
        if($this->collection){
            return $this->collection->find([], [
                'projection' => [
                    '_id' => 1,
                    'nev' => 1
                ]
            ]);
        }
         
        return null;
    }

    //Csapatok visszaküldése versenyzőkkel
    public function getVersenyzok()
    {
        if($this->collection){
            return $this->collection->find();
        }
         
        return null;
    }
    
    //csapat beszúrása
    public function insertCsapat($data)
    {
        if($this->collection){
            $this->collection->insertOne(['_id' => $data['id'], 'nev' => $data['name']]);
        }
    }

    //csapat frissítése, új versenyző beszúrása a versenyzők tömbbe
    public function insertVersenyzo($data)
    {
        if($this->collection){
            $this->collection->updateOne(['_id' => $data['csapat_id']],
                ['$push' => ['versenyzok' =>
                    ['_id' => $data['id'], 'nev' => $data['name'], 'verseny_szam' => $data['verseny_szam']]
            ]]);
        }
    }

}