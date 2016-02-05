<?php

// neprihlihláseného užívateľa presmeruje na prihlásenie
if (!is_logged_in()) redirect("/prihlasenie");

// získame id článku z url adresy
$post_id = segment(2);

// ak sa tam žiadne id článku nenachádzalo, zobrazíme 404 stránku
if (!$post_id) show_404();

// získame dáta o článku, bez formátovania
$post = get_post($post_id, false);

// ak sme nezískali žiadne dáta, článok neexistuje a zobrazíme 404 stránku
if (!$post) show_404();

// ak prihlásený užívateľ nie je majteľom článku, zobrazíme 404 stránku
if (!is_owner($post)) show_404();

// ak bol na túto stránku odoslaný formulár...
if (is_post()){
    // ... vymažeme článok ...
    if (edit_post($post_id)){
        // ... a ak sa úspešne aktualizoval skúsime pridať alebo odstrániť obrázok...
        if ($post["has_image"] && isset($_POST["delete_image"])){
            deleteImage($post_id);
        } else {
            addImage($post_id);
        }
        // ... a nakoniec presmerujeme na článok
        redirect("clanok/" . $post_id);
    }
}

$tags = get_tags($post["id"]);

include_header(array("title" => "Úprava článku"));

?>

    <h1 class="text-center">Úprava článku</h1>

    <form method="post" enctype="multipart/form-data">

        <label for="title"><strong>Názov článku:</strong></label>
        <input type="text" name="title" id="title" value="<?= (isset($_POST["title"]) ? $_POST["title"] : plain($post["title"])) ?>">

        <label for="text"><strong>Text článku:</strong></label>
        <textarea name="text" id="text"><?= (isset($_POST["text"]) ? $_POST["text"] : plain($post["text"])) ?></textarea>

        <?php if(!empty($tags)): ?>
            <div><strong>Tagy:</strong>
                <?php foreach($tags as $tag): ?>
                    <label><input type="checkbox" name="tags[]" value="<?= $tag["id"] ?>" <?= (isset($tag["checked"]) ? "checked" : "") ?>><?= $tag["tag"] ?></label>
                <?php endforeach ?>
            </div>
        <?php endif ?>

        <?php if (!$post["has_image"]): ?>
            <label for="image"><strong>Nahrať obrázok:</strong></label>
            <input type="file" name="image" id="image" accept="image/jpeg">
        <?php else: ?>
            <label for="image"><strong>Nahrať nový obrázok:</strong></label>
            <input type="file" name="image" id="image" accept="image/jpeg">

            <label for="delete_image"><strong>Odstrániť obrázok:</strong></label>
            <input type="checkbox" name="delete_image" id="delete_image">
        <?php endif ?>

        <input type="submit" value="Uložiť">
    </form>

<?php include_footer() ?>