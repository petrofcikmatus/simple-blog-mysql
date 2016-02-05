<?php

// funkcia ktorá pridá novú správu do session
function add_message($message){
    // ak ešte v session neexistuje pole pre správy...
    if (!has_messages()){
        // ... vytvoríme si jedno
        $_SESSION["messages"] = array();
    }

    // pridáme novú správu do poľa správ v session
    $_SESSION["messages"][] = $message;
}

// funkcia ktorá vyberie správy zo session
function get_messages(){
    // ak v session nemáme pole pre správy...
    if (!has_messages()){
        // ... vrátime prázdne pole
        return array();
    }

    // uložíme si pole správ zo session do pomocnej premennej
    $messages = $_SESSION["messages"];

    // vymažeme správy zo session
    unset($_SESSION["messages"]);

    // vrátime pole správ z pomocnej premennej
    return $messages;
}

// funkcia zisťuje či máme v session pole pre správy
function has_messages(){
    // ak v session existuje pole pre správy, a určite je to pole...
    if (isset($_SESSION["messages"]) && is_array($_SESSION["messages"])){
        // ... vrátime pravdu...
        return true;
    }

    // ... inak vrátime nepravdu
    return false;
}
