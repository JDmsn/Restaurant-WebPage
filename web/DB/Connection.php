<?php

class Connection {

    private $db;

    function __construct()
    {
        $this->db= new PDO('sqlite:'. $_SERVER["DOCUMENT_ROOT"] . '/datos.db');
    }

    function enableForeignKeys(){
        $this->db->exec('PRAGMA foreign_keys = ON;');
    }

    function getPDO(){
        return $this->db;
    }
}