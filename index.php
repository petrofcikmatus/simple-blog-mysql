<?php

// pridáme si konfiguračný súbor, v máme nejaké nastavenia a pripojenie na databázu
require_once("_include/config.php");

// pridáme si súbori s funkciami, ktoré budeme používať
require_once("_include/functions-general.php");
require_once("_include/functions-message.php");
require_once("_include/functions-auth.php");
require_once("_include/functions-post.php");

// podstránky ktoré sú dostupné
$routes = array(
//    ""               => "home.php", // zobrazí úvodnú stránku
    ""               => "posts.php", // zobrazí úvodnú stránku
    "o-stranke"      => "about.php", // zobrazí statickú stránku o stránke
    "kontakt"        => "contact.php", // zobrazí statickú stránku s kontaktom

    "clanky"         => "posts.php", // zobrazí stránku s príspevkami
    "clanok"         => "post.php", // zobrazí príspevok s id
    "autor"          => "author.php", // zobrazí príspevky autora
    "tag"            => "tag.php", // zobrazí príspevky s tagom

    "prihlasenie"    => "login.php", // stránka na prihlásenie
    "registracia"    => "register.php", // stránka na prihlásenie
    "odhlasenie"     => "logout.php", // stránka na odhlásenie

    "novy-clanok"    => "post-new.php", // stránka na pridanie nového článku
    "upravit-clanok" => "post-edit.php", // stránka na úpravu článku
    "vymazat-clanok" => "post-delete.php", // stránka na vymazanie článku

    "vyhladavanie"   => "search.php", // stránka pre vyhľadávanie
);

// v prvom segmente url máme podstránku
$page = segment(1);

// ak taká podstránka neexistuje v našom poli dostupných podstránok, tak zobrazíme 404 stránku
if (!array_key_exists($page, $routes)) {
    show_404();
}

// inak ju zobrazíme
include_once("_pages/" . $routes[$page]);
