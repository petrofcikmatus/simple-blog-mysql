<?php

// ak je človek prihlásený
if (is_logged_in()){
    // odhlás ho
    do_logout();
}

// a presmeruj na úvodnú stránku
redirect();
