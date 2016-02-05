<?php

$user_id = segment(2);

$user = get_user($user_id);

if (empty($user)) show_404();

$posts = get_posts_by_user($user_id);

include_header(array("title" => "Autor " . $user["name"]));

?>

    <h1 class="text-center">Články autora <?= $user["name"] ?></h1>

    <?php if(count($posts)): foreach($posts as $post): ?>
        <div class="post">

            <h2><a href="<?= $post["link"] ?>"><?= $post["title"] ?></a></h2>

            <div class="post-info">
                Napísané dňa <?= $post["created_at"] ?>.
            </div>

            <div class="post-teaser">
                <?= $post["teaser"] ?>
            </div>

        </div>
    <?php endforeach; else: ?>
        <div class="message">Žiaľ v databáze autor <strong><?= plain($user["name"]) ?></strong> nemá žiadne príspevky.</div>
    <?php endif ?>

<?php

include_footer();