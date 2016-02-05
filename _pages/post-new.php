<?php

// neprihláseným vstup zakázaný
if (!is_logged_in()){
    add_message("Musíte sa prihlásiť.");
    redirect("/prihlasenie");
}

if (is_post()){
    $post_id = add_post();

    // ak sa nám podarilo pridať článok
    if ($post_id){
        // skusíme pridať aj obrázok
        addImage($post_id);

        // a presmerujeme
        redirect("clanok/" . $post_id);
    }
}

$tags = get_tags();

include_header(array("title" => "Pridanie článku")) ?>

    <h1 class="text-center">Nový článok</h1>

    <form method="post" enctype="multipart/form-data">

        <label for="title"><strong>Názov článku:</strong></label>
        <input type="text" name="title" id="title" value="<?= (isset($_POST["title"]) ? $_POST["title"] : "") ?>">

        <label for="text"><strong>Text článku:</strong></label>
        <textarea name="text" id="text"><?= (isset($_POST["text"]) ? $_POST["text"] : "") ?></textarea>

        <?php if(!empty($tags)): ?>
            <div><strong>Tagy:</strong>
                <?php foreach($tags as $tag): ?>
                    <label><input type="checkbox" name="tags[]" value="<?= $tag["id"] ?>" <?= ((isset($_POST["tags"]) && in_array($tag["id"], $_POST["tags"])) ? "checked" : "") ?>><?= $tag["tag"] ?></label>
                <?php endforeach ?>
            </div>
        <?php endif ?>

        <label for="image"><strong>Nahrať obrázok:</strong></label>
        <input type="file" name="image" id="image" accept="image/jpeg">

        <input type="submit" value="Uložiť">
    </form>

<?php include_footer() ?>