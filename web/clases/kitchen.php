<?php

include_once '../libs/View.php';
include_once '../DB/Connection.php';
session_start();

if(isset($_SESSION['userType']) || $_SESSION['userType'] == CHEF){
    $dbb = new Connection();
    $dbb->enableForeignKeys();
    $db = $dbb->getPDO();
    if(!isset($_POST['mode'])) {
        View::header("Linea de comandas", ["../../assets/css/estilo.css", "../../assets/css/foodTable.css"]);

    }else if($_POST['mode'] == "cook"){
        startCooking($_POST['id'], $db);
    }else if($_POST['mode'] == "endCook"){
        endCooking($_POST['id'], $db);
    }

    View::menuFor($_SESSION['userType'],"../../index.php","tabla.php","contact.php","login.php","logout.php","comand.php","kitchen.php","search.php");


    //View::kitchen();


    displayTables($db);
    $db = null;
    if(!isset($_POST['mode'])) {
        View::end("contact.php");
    }
}else{
    header("HTTP/1.0 404 Not Found");
    exit();
}

function displayTables($db) {

    $query = $db->prepare("SELECT c.id as 'id', c.comanda as 'order', a.nombre as 'article', u.nombre as 'waiter', c.horapeticion as 'orderhour'
        FROM lineascomanda c

        LEFT JOIN articulos a ON a.id = c.articulo
        LEFT JOIN usuarios u ON u.id= c.camareropeticion

        WHERE c.tipo = 1 AND c.cocinero is null");

    $query->execute();


    $waitingComand = $query->fetchAll(PDO::FETCH_ASSOC);

    $query = $db->prepare("SELECT c.id as 'id', c.comanda as 'order', a.nombre as 'article', u.nombre as 'waiter', c.horainicio as 'cookStartHour', o.mesa as 'table'
        FROM lineascomanda c

        LEFT JOIN articulos a ON a.id = c.articulo
        LEFT JOIN usuarios u ON u.id= c.camareropeticion
        LEFT JOIN comandas o ON o.id= c.comanda

        WHERE c.cocinero = " . $_SESSION['userId'] . " AND c.horafinalizacion = 0;");

    $query->execute();

    $cookingComand = $query->fetchAll(PDO::FETCH_ASSOC);

    View::kitchen($waitingComand, $cookingComand);


}

function startCooking($commandLineId, $db){

    $query = $db->prepare("UPDATE 'lineascomanda' SET
      cocinero=:chef, horainicio=:hour
      WHERE id=:id
      ;");

    if($query == false){
        print_r(array_values($db->errorInfo()));
    }
    $query->execute(array(
        ':chef' => $_SESSION['userId'],
        ':hour' => time(),
        ':id' => $commandLineId ));
}

function endCooking($commandLineId, $db){

    $query = $db->prepare("UPDATE 'lineascomanda' SET
      horafinalizacion=:hour
      WHERE id=:id
      ;");

    if($query == false){
        print_r(array_values($db->errorInfo()));
    }
    $query->execute(array(
        ':hour' => time(),
        ':id' => $commandLineId ));
}




