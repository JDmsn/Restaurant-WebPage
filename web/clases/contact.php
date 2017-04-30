<?php
include_once '../libs/View.php';
session_start();
View::header("Contacto", ["../../assets/css/estilo.css", "../../assets/css/place.css"]);
View::menuFor($_SESSION['userType'],"../../index.php","tabla.php","contact.php","login.php","logout.php","comand.php","kitchen.php","search.php");
View::contact();
View::end("contact.php");