<?php

include_once '../libs/View.php';
include_once '../DB/Connection.php';
session_start();

function displayTables($db, $charge) {

    $query = $db->prepare("SELECT id as 'id', nombre as 'name' FROM mesas");
    $query->execute();

    $tables = $query->fetchAll(PDO::FETCH_ASSOC);

    $comands = [];

    foreach ($tables as $table) {

        $query = $db->prepare("SELECT id FROM comandas WHERE mesa = :table AND horacierre = 0");
        $query->execute(array(
            ':table' => $table['id']));

        $orders = $query->fetchAll(PDO::FETCH_ASSOC);

        $index = array_push($comands, new TableComands($table['name'], $table['id'])) - 1;

        foreach ($orders as $order) {

            $tableOrder = queryOrder($db, $order);

            array_push($comands[$index]->comand, new Comand($order['id'], $tableOrder));

        }

    }

    View::waiterComand($comands, $charge);
}

function queryOrder($db, $order) {
    $query = $db->prepare("
        SELECT l.id, l.articulo as 'articleId', a.nombre as 'articleName', a.pvp as 'articlePrice',
         a.tipo as 'articleType', l.horainicio as 'cookStart',
         l.horafinalizacion as 'cookEnd', l.horaservicio as 'serveHour'

        FROM lineascomanda l
        LEFT JOIN articulos a ON a.id = l.articulo

        WHERE l.comanda = :order");

    if($query == false){
        print_r(array_values($db->errorInfo()));
    }

    $query->execute(array(
        ':order' => $order['id']));

    return $query->fetchAll(PDO::FETCH_ASSOC);
}

function newOrder($table, $articles, $db){
    $query = $db->prepare("INSERT INTO 'comandas' (mesa, camareroapertura, horaapertura) VALUES
          (:table, :waiter, :hour)
        ;");

    if ($query == false) {
        print_r(array_values($db->errorInfo()));


    }
    $query->execute(array(
        ':waiter' => $_SESSION['userId'],
        ':hour' => time(),
        ':table' => $table));

    $id = $db->lastInsertId();

    addOrder($id, $articles, $db);
}

function addOrder($id, $articles, $db){
    foreach($articles as $article) {

        $query = $db->prepare("SELECT id, tipo as type FROM 'articulos'
              WHERE nombre = :name AND stock > 0
             ;");

        if ($query == false) {
            print_r(array_values($db->errorInfo()));
        }
        $query->execute(array(
            ':name' => $article
        ));

        $article = $query->fetch(PDO::FETCH_ASSOC);

        if($article['id']== null){
            echo 'Articulo no encontrado<br />';
            continue;
        }

        $query = $db->prepare("UPDATE 'articulos'
              SET stock = stock - 1
              WHERE :id AND stock > 0
             ;");

        if ($query == false) {
            print_r(array_values($db->errorInfo()));
        }
        $query->execute(array(
            ':id' => $article['id']
        ));

        $query = $db->prepare("INSERT INTO 'lineascomanda'
              (comanda, articulo, camareropeticion, horapeticion, tipo) VALUES
              (:orderId, :article, :waiter, :hour, :type)
             ;");

        if ($query == false) {
            print_r(array_values($db->errorInfo()));
        }
        $query->execute(array(
            'orderId' => $id,
            ':article' => $article['id'],
            ':type' => $article['type'],
            ':waiter' => $_SESSION['userId'],
            ':hour' => time()));
    }
}

function removeOrder($idLine, $db){
    $query = $db->prepare("DELETE FROM 'lineascomanda'
          WHERE id = :id
        ;");

    if ($query == false) {
        print_r(array_values($db->errorInfo()));
    }
    $query->execute(array(
        ':id' => $idLine
    ));
}

function serveOrder($idLine, $db){
    $query = $db->prepare("UPDATE 'lineascomanda'
          SET camareroservicio = :waiter, horaservicio = :hour
          WHERE id=:id
        ;");

    if ($query == false) {
        print_r(array_values($db->errorInfo()));
    }
    $query->execute(array(
        ':id' => $idLine,
        ':waiter' => $_SESSION['userId'],
        ':hour' => time()
    ));
}

function chargeOrder($id, $db){
    $query = $db->prepare("SELECT SUM(PVP) as price FROM 'articulos'
          WHERE id IN (SELECT articulo
                      FROM 'lineascomanda'
                      WHERE comanda = :id)
        ;");

    if ($query == false) {
        print_r(array_values($db->errorInfo()));
    }
    $query->execute(array(
        ':id' => $id,
    ));

    $pvp = $query->fetch(PDO::FETCH_ASSOC)['price'];

    $query = $db->prepare("UPDATE 'comandas'
          SET camarerocierre = :waiter, horacierre = :hour, PVP = :pvp
          WHERE id = :id
        ;");

    if ($query == false) {
        print_r(array_values($db->errorInfo()));
    }
    $query->execute(array(
        ':id' => $id,
        ':waiter' => $_SESSION['userId'],
        ':hour' => time(),
        ':pvp' => $pvp
    ));

    return $pvp;
}

if(isset($_SESSION['userType']) || $_SESSION['userType'] == WAITER){
    $dbb = new Connection();
    $dbb->enableForeignKeys();
    $db = $dbb->getPDO();

    $charge = null;

    if (isset($_POST['mode'])) {

        switch ($_POST['mode']) {
            case 'add':
                $articles = explode(',',$_POST['data']);
                addOrder($_POST['id'], $articles,$db);
                break;
            case 'serve':
                serveOrder($_POST['id'], $db);
                break;
            case 'remove':
                removeOrder($_POST['id'], $db);
                break;
            case 'charge':
                $charge = array('id' => $_POST['id'], 'charge' => chargeOrder($_POST['id'], $db));
                break;
            case 'addOrder':
                newOrder($_POST['id'], [],  $db);
                break;

        }
    }else{
        View::header("Comandas", ["../../assets/css/estilo.css", "../../assets/css/foodTable.css"]);
    }
    View::menuFor($_SESSION['userType'],"../../index.php","tabla.php","contact.php","login.php","logout.php","comand.php","kitchen.php","search.php");

    displayTables($db, $charge);
    $db = null;

    if(!isset($_POST['mode'])) {
        View::end("contact.php");
    }
}else{
    header("HTTP/1.0 404 Not Found");
    exit();
}
