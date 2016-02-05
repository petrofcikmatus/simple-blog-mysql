<?php

// vráti true ak je užívateľ prihlásený
function is_logged_in(){
    return isset($_SESSION["user"]);
}

// vráti true ak prihlásený užívateľ je adminom
function is_admin(){
    return (is_logged_in() && $_SESSION["user"]["is_admin"]);
}

// vráti hash hesla, pretože do databázy sa nesmie heslo ukladať tak ako ho užívateľ zadal
function get_hash($password){
    return hash("sha512", $password);
}

// prihlási užívateľa ak zadal správny email a heslo
function do_login(){
    $email    = isset($_POST["email"]) ? $_POST["email"] : "";

    $password = isset($_POST["password"]) ? $_POST["password"] : "";

    if (trim($email) == ""){
        add_message("Nezadali ste email.");
        return false;
    }

    if (trim($password) == ""){
        add_message("Nezadali ste heslo.");
        return false;
    }

    global $db;

    try {
        $query = $db->prepare("SELECT id, name, email, is_admin FROM users WHERE email = :email AND password = :password");
        $query->execute(array("email" => $email, "password" => get_hash($password)));
    } catch (PDOException $e){
        add_message("Nepodarilo sa prihlásiť užívateľa (chyba db?).");
        return false;
    }

    if ($query->rowCount() !== 1){
        add_message("Zlý email alebo heslo.");
        return false;
    }

    $_SESSION["user"] = $query->fetch(PDO::FETCH_ASSOC);
    add_message("Vitajte!");
    return true;
}

// zaregistruje nového užívateľa
function do_register(){
    $name            = isset($_POST["name"]) ? $_POST["name"] : "";
    $email           = isset($_POST["email"]) ? $_POST["email"] : "";
    $password        = isset($_POST["password"]) ? $_POST["password"] : "";
    $password_repeat = isset($_POST["password_repeat"]) ? $_POST["password_repeat"] : "";

    if (trim($name) == ""){
        add_message("Nezadali ste meno.");
        return false;
    }

    if (trim($email) == ""){
        add_message("Nezadali ste email.");
        return false;
    }

    if (trim($password) == ""){
        add_message("Nezadali ste heslo.");
        return false;
    }

    if ($password != $password_repeat){
        add_message("Heslá sa nezhodujú.");
        return false;
    }

    global $db;

    $query = $db->prepare("SELECT COUNT(id) FROM users WHERE email = :email");
    $query->execute(array("email" => $email));
    $row = $query->fetch(PDO::FETCH_NUM);
    if ($row[0] > 0){
        // niekoho už s takým emailom máme
        add_message("Taký email už niekto používa.");
        return false;
    }

    try {
        $query = $db->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $query->execute(array("name" => $name, "email" => $email, "password" => get_hash($password)));
    } catch (PDOException $e){
        add_message("Nepodarilo sa zaregistrovať užívateľa (chyba db?).");
        return false;
    }

    if ($query->rowCount() !== 1){
        // niečo iné sa nepodarilo
        add_message("Niečo sa nepodarilo.");
        return false;
    }

    add_message("Gratulujem, teraz sa môžete prihlásiť.");
    return true;
}

// odhlási užívateľa
function do_logout(){
    unset($_SESSION["user"]);
    add_message("Dovidenia.");
    return true;
}

// vráti id prihláseného užívateľa
function get_user_id(){
    if (!is_logged_in()) return false;
    return $_SESSION["user"]["id"];
}

function get_user_name(){
    if (!is_logged_in()) return false;
    return $_SESSION["user"]["name"];
}

// vráti pole s informáciami o užívateľovi
function get_user($user_id){
    global $db;

    try {
        $query = $db->prepare("SELECT id, name, email FROM users WHERE id = :id");
        $query->execute(array("id" => $user_id));
    } catch (PDOException $e){
        return array();
    }

    return $query->fetch(PDO::FETCH_ASSOC);
}
