<?php
class Database{

private static $servername = 'localhost';
private static $username = 'root';
private static $password = '';
private static $BDD ='reservationsalles';
private static $conn = null;


public static function _construct(){
    die('Init function is not allowed');
}

public static function connect(){ //fonction de connexion à la BDD
    if (null == self::$conn){ //si la connexion est nulle
        try{ //on essaie de se connecter
            self::$conn = new PDO("mysql:host=".self::$servername.";"."dbname=".self::$BDD,self::$username,self::$password); //on se connecte à la BDD
        }catch(PDOException $e){
            die($e->getMessage());
        }
    }
    return self::$conn;
}

public static function disconnect(){
    self::$conn = null;
}

}
?>