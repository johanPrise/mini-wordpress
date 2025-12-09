<?php
namespace App\Core;

use App\Config\Database as DatabaseConfig;
use PDO;
use PDOException;

class Database{

    private static $instance = null;

    private function __construct(){}

    public static function getInstance(){
        if( self::$instance === null ){
            try{
                $dsn = sprintf(
                    'pgsql:host=%s;port=%s;dbname=%s',
                    DatabaseConfig::DB_HOST,
                    DatabaseConfig::DB_PORT,
                    DatabaseConfig::DB_NAME
                );
                self::$instance = new PDO($dsn, DatabaseConfig::DB_USER, DatabaseConfig::DB_PASSWORD);
                self::$instance->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
                self::$instance->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC ); // Added this line
            } catch (PDOException $e){ // Changed from Exception to PDOException
                die('Erreur de connection à la base de données : ' . $e->getMessage());
            }
        }
        return self::$instance;
    }
}