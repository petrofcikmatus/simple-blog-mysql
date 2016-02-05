<?php

// neprihlihláseného užívateľa presmeruje na prihlásenie
if (!is_logged_in()) redirect("/prihlasenie");

// získame id článku z url adresy
$post_id = segment(2);

// ak sa tam žiadne id článku nenachádzalo, zobrazíme 404 stránku
if (!$post_id) show_404();

// získame dáta o článku
$post = get_post($post_id);

// ak sme nezískali žiadne dáta, článok neexistuje a zobrazíme 404 stránku
if (!$post) show_404();

// ak prihlásený užívateľ nie je majteľom článku, zobrazíme 404 stránku
if (!is_owner($post)) show_404();

// ak bol na túto stránku odoslaný formulár...
if (is_post()){
    // ... vymažeme článok ...
    if (delete_post($post_id)){
        // ... a ak sa úspešne vymazal, presmerujeme na úvodnú stránku
        redirect();
    }
}

include_header(array("title" => "Vymazanie článku"));

?>

    <h1 class="text-center">Vymazanie článku</h1>

    <h2><?= $post["title"] ?></h2>
    <p><?= $post["teaser"] ?></p>

    <form method="post">
        <input type="submit" value="Vymazať" onclick="return confirm('Naozaj chcete vymazať tento článok?')">
    </form>

<?php include_footer() ?>