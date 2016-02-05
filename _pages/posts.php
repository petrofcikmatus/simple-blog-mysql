<?php

$posts = get_posts();

include_header(array("title" => "Články"));

?>

    <h1 class="text-center">Články</h1>

    <?php if(count($posts)): foreach($posts as $post): ?>
        <div class="post extra-padding">

            <h2><a href="<?= $post["link"] ?>"><?= $post["title"] ?></a></h2>

            <div class="post-info">
                Napísal <a href="<?= $post["user_link"] ?>"><strong><?= $post["user_name"] ?></strong></a> dňa <?= $post["created_at"] ?>.
                <?php if(!empty($post["tags"])): ?>
                    Článok má tagy:
                    <?php foreach($post["tags"] as $name => $link): ?>
                        <a class="link link-tag" href="<?= $link ?>"><strong><?= $name ?></strong></a>
                    <?php endforeach ?>
                <?php endif ?>
            </div>

            <div class="post-teaser">
                <?= $post["teaser"] ?>
            </div>

            <?php if(is_owner($post)): ?>
                <div class="post-action">
                    <a class="link link-edit" href="<?= $post["link_edit"] ?>"><strong>Editovať</strong></a>
                    <a class="link link-delete" href="<?= $post["link_delete"] ?>"><strong>Vymazať</strong></a>
                </div>
            <?php endif ?>

        </div>
    <?php endforeach; else: ?>
        <div class="message">Žiaľ v databáze nemám žiadne príspevky.</div>
    <?php endif ?>

<?php

include_footer();