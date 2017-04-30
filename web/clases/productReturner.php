<?php

include_once  $_SERVER["DOCUMENT_ROOT"] . '/web/DB/Connection.php';

function returnProducts(){
    $dbb = new Connection();
    $dbb->enableForeignKeys();
    $db = $dbb->getPDO();

    $query = $db->prepare('SELECT nombre  FROM articulos;');
    $query->execute();
    return $query->fetchAll(PDO::FETCH_COLUMN);
}
