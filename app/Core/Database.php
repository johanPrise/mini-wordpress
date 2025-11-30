<?php
class Database{
    private static $instance = null;

    private function __construct(){}

    public static function getInstance(){
        if( self::$instance === null ){
            try{
                self::$instance = new PDO('mysql:host=' . DB_HOST .'; dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
                self::$instance->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            } catch (Exception $e){
                die('Erreur de connection à la base de données : ' . $e->getMessage());
            }
        }
        return self::$instance;
    }
}