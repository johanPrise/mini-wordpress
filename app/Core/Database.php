<?php
namespace App\Core;

use App\Config\Database as DatabaseConfig;
use PDO;
use Exception;

class Database{

    private static $instance = null;

    private function __construct(){}

    public static function getInstance(){
        if( self::$instance === null ){
            try{
                self::$instance = new PDO('mysql:host=' . DatabaseConfig::DB_HOST .'; dbname=' . DatabaseConfig::DB_NAME, DatabaseConfig::DB_USER, DatabaseConfig::DB_PASSWORD);
                self::$instance->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            } catch (Exception $e){
                die('Erreur de connection à la base de données : ' . $e->getMessage());
            }
        }
        return self::$instance;
    }
}