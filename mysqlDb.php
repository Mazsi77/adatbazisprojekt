<?php
session_start();

/*
MySql-t kezelő osztály
A $_SESSION['mysql'] jelzi a kapcsolat állapotát:
    -Nem létezik: nem volt kapcsolódási kérés
    -False: sikertelen kapcsolat (hiba)
    -True: sikeres kapcsolódás
*/

class MySqlDb
{
    //Adatbázis kapcsolódási adatok
    private $server = 'localhost';
    private $user = 'root';
    private $password = '';

    private $conn;

    //beállítja autómatikusan a kapcsolatot, ha már meg volt nyomva a kapcsolódás
    public function __construct()
    {

        if(!isset($_SESSION['mysql'])){
            return;
        }

        else{
           $this->connect();
        }
    }

    //Megszakítja a kapcsolatot
    public function __destruct()
    {
        if($this->conn){
            $this->conn->close();
        }
    }

    //kapcsolat létrehozása, hibaüzenetek visszaküldése
    public function connect()
    {
        //kapcsolódás a szerverhez
        $_SESSION['mysql'] = false;
        $this->conn = new mysqli($this->server, $this->user, $this->password);

        if($this->conn->connect_error){
           return "Kapcsolódási hiba";
        }

        //Adatbázis kiválasztása
        $db = mysqli_select_db($this->conn, 'verseny');

        if(!$db){
            //táblák létrehozása
            $sql = 'CREATE DATABASE verseny';

            if($this->conn->query($sql)){
                $db = mysqli_select_db($this->conn, 'verseny');
                $sql = "CREATE TABLE csapat (
                    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    nev VARCHAR(30) NOT NULL)
                ";
                
                if(!mysqli_query($this->conn, $sql)){
                    return "Csapat tábla létrehozása sikertelen!";
                }

                $sql = "CREATE TABLE versenyzo (
                    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    csapat_id INT(6) UNSIGNED NOT NULL,
                    nev VARCHAR(30) NOT NULL,
                    verseny_szam INT(2) UNSIGNED UNIQUE NOT NULL,
                    FOREIGN KEY (`csapat_id`)
                    REFERENCES `csapat` (`id`))
                ";

                if(!mysqli_query($this->conn, $sql)){
                    return "Versenyző tábla létrehozása sikertelen!";
                }
            }else {
                return "Adatbázis létrehosása sikertelen";
            }
        }

        $_SESSION['mysql'] = true;
        return "Sikeres csatlakozás";
    }

    public function getCsapatok()
    {
        if($this->conn){
            $sql = "SELECT * FROM csapat";

            return $this->conn->query($sql);
        }

        return null;
    }

    public function getVersenyzok()
    {
        if($this->conn){
            $sql = "SELECT * FROM versenyzo";

            return $this->conn->query($sql);
        }

        return null;
    }


    public function insertCsapat($data)
    {
        if($this->conn){
            $sql = $this->conn->prepare("INSERT INTO csapat (id, nev) VALUES (?, ?)");
            $sql->bind_param('is', $data['id'], $data['name']);

            return $sql->execute();
        }
    }

    public function insertVerrsenyzo($data)
    {
        if($this->conn){
            $sql = $this->conn->prepare("INSERT INTO versenyzo (id, csapat_id, nev, verseny_szam) VALUES (?, ?, ?, ?)");
            $sql->bind_param('iisi', $data['id'], $data['csapat_id'], $data['name'], $data['verseny_szam']);

            return $sql->execute();
        }
    }
}