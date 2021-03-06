<?php
include_once '../libs/View.php';
include_once '../DB/Connection.php';
session_start();
View::header("Platos", ["../../assets/css/estilo.css", "../../assets/css/foodTable.css"]);
View::menuFor($_SESSION['userType'],"../../index.php","tabla.php","contact.php","login.php","logout.php","comand.php","kitchen.php","search.php");

$dbb = new Connection();
$dbb->enableForeignKeys();
$db = $dbb->getPDO();

if($_SESSION['userType'] == CLIENT || $_SESSION['userType'] == -1)
    $query = $db->prepare('SELECT nombre, tipo, PVP  FROM articulos;');
else
    $query = $db->prepare('SELECT nombre, tipo, PVP, stock  FROM articulos;');

$query->execute();
$db=null;

$items = $query->fetchAll(PDO::FETCH_ASSOC);
$keys = [];
$values= [];
$array = [];
if(count($items)>0){
    foreach($items as $item){
        $keys = [];
        $values= [];
        foreach($item as $key=>$value){
            array_push($keys, $key);
            array_push($values,$value);
        }

        if($values[1]==0){
            $values[1] = 'Importada';
        } elseif($values[1] == 1){
            $values[1] = 'Hecho por cocinero';
        }

        $values[2] = '<div class="price">'.$values[2].'</div>';

        array_push($array,$values);
    }
} else {
    echo 'Items not found';
}

View::tabla($keys,$array);

View::end("contact.php");