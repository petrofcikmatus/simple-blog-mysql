<?php

// ak je človek prihlásený
if (is_logged_in()){
    // presmeruj ho na úvodnú stránku, pretože sa už nemusí prihlasovať
    redirect();
}

// ak bol odoslaný prihlasovací formulár, existujú premenné email a password
if (is_post()){
    // prihlás užívateľa
    if (do_login()){
        redirect();
    }
}

include_header(array("title" => "Prihlásenie"))

?>

<h1 class="text-center">Prihlásenie</h1>

<div class="max-width-300px">
    <form method="post">

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?= (isset($_POST["email"]) ? plain($_POST["email"]) : "") ?>">

        <label for="password">Heslo:</label>
        <input type="password" name="password" id="password">

        <input type="submit" value="Prihlásiť">
    </form>
</div>

<div class="max-width-300px text-center">
    Alebo sa <a href="<?= url() ?>/registracia"><strong>zaregistrujte</strong></a>.
</div>

<?php include_footer() ?>