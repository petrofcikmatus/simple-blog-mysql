<?php

// ak je človek prihlásený
if (is_logged_in()){
    // presmeruj ho na úvodnú stránku, pretože sa už nemusí prihlasovať
    redirect();
}

if (is_post()){
    if (do_register()){
        redirect('/prihlasenie');
    }
}

include_header(array("title", "Registrácia"));

?>

<h1 class="text-center">Registrácia</h1>

<div class="max-width-300px">
    <form method="post">

        <label for="name">Meno:</label>
        <input type="text" name="name" id="name" value="<?= (isset($_POST["name"]) ? plain($_POST["name"]) : "") ?>">

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?= (isset($_POST["email"]) ? plain($_POST["email"]) : "") ?>">

        <label for="password">Heslo:</label>
        <input type="password" name="password" id="password">

        <label for="password_repeat">Heslo znovu:</label>
        <input type="password" name="password_repeat" id="password_repeat">

        <input type="submit" value="Zaregistrovať">

    </form>
</div>

<div class="max-width-300px text-center">
    Alebo sa <a href="<?= url() ?>/prihlasenie"><strong>prihláste</strong></a>.
</div>

<?php include_footer() ?>
