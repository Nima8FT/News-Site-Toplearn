<?php


require_once("database.php");

if(!isset($_POST['Table'])) {
    $_POST['Table'] = "";
}
if(!isset($_POST['Query'])) {
    $_POST['Query'] = "";
}
if(!isset($_POST['Fields'])) {
    $_POST['Fields'] = "";
}
if(!isset($_POST['Values'])) {
    $_POST['Values'] = "";
}
if(!isset($_POST['ID'])) {
    $_POST['ID'] = "";
}

$Mode = $_POST['Mode'];
$Table = $_POST['Table'];
$Query = $_POST['Query'];
$Fields = explode('<#>', $_POST['Fields']);
$Values = explode('<#>', $_POST['Values']);
$ID = $_POST['ID'];

// var_dump($_POST);

$DB = new database\Database();

if($Mode == "INSERT") {
    $DB->insert($Table,$Fields,$Values);
}
else if($Mode == "UPDATE") {
    $DB->update($Table,$Fields,$Values,$ID);
}
else if($Mode == "DELETE") {
    $DB->delete($Table,$ID);
}
else if($Mode == "QUERY") {
    $DB->query($Query);
}
