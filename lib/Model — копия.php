<?php

class Model {

    const DB_ADAPTER = 'mysql';
    const DB_HOST = 'mysql13.000webhost.com';
    const DB_NAME = 'a5617888_far';
    const DB_USERNAME = 'a5617888_far';
    const DB_PASSWORD = 'fargys11';


    public static $db;

    public static function connect() {
        self::$db = new PDO(Model::DB_ADAPTER . ':host=' . Model::DB_HOST . ';dbname=' . Model::DB_NAME, Model::DB_USERNAME, Model::DB_PASSWORD);
        self::$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        self::$db->exec('SET NAMES utf8');
    }
    public function __construct() {

    }
} 