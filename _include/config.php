<?php

// zobrazenie errorov
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

// naštartujeme session potrebné pre prihlásenie
if (!session_id()) @session_start();

// nastavenie časovej zóny, doma mi to píše chyby
date_default_timezone_set("Europe/Bratislava");

// nejaké konštanty ktoré využijeme v aplikácii, napr. vo funkcii url()
define('BASE_URL', 'http://localhost/simple-blog');
define('IMAGE_DIRECTORY', 'assets/images');

// nastavenia pre databázu
$database = array(
    "host"     => "localhost",
    "port"     => "5432",
    "dbname"   => "test",
    "user"     => "postgres",
    "password" => "postgres",
    "settings" => array(
        PDO::ATTR_ERRMODE          => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false
    )
);

// pripojenie na databázu http://php.net/manual/en/pdo.connections.php
try {
    $db = new PDO(
        "pgsql:host=${database["host"]};port=${database["port"]};dbname=${database["dbname"]};",
        $database["user"],
        $database["password"],
        $database["settings"]
    );
} catch (Exception $e){
    exit("Nepodarilo sa pripojit na databazu. " . $e->getMessage());
}
