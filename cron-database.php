<?php
error_reporting(1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$pdo = new PDO('mysql:host=localhost;charset=utf8mb4;dbname=admin_p2',
    'admin_yann', 'WTKWNB1TAG');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
// Start trunc table
###< Customers ###
$stmtDeposits = $pdo->prepare("DELETE FROM deposit");
$stmtDeposits->execute();
echo 'Deposit Table -> OK !';
###< Equipments ###
$stmtEquipments = $pdo->prepare("DELETE FROM equipments");
$stmtEquipments->execute();
echo 'Equipments Table -> OK !';
###< Interventions ###
$stmtInterventions = $pdo->prepare("DELETE FROM interventions");
$stmtInterventions->execute();
echo 'Interventions Table -> OK !';
###< Customers ###
$stmtCustomers = $pdo->prepare("DELETE FROM customers");
$stmtCustomers->execute();
echo 'Customers Table -> OK !';

$dbh = '';






