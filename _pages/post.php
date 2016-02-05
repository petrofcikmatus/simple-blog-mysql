<?php

$post_id = segment(2);

$post = get_post($post_id);

if (empty($post)) show_404();

include_header(array("title" => $post["title"]));

?>

    <div class="post">

        <h1 class="text-center"><?= $post["title"] ?></h1>

        <?php if ($post["has_image"]): ?>
            <div class="post-image">
                <img src="<?= image_url($post["id"]) ?>" alt="">
            </div>
        <?php endif ?>

        <div class="post-info">
            Napísal <a href="<?= $post["user_link"] ?>"><strong><?= $post["user_name"] ?></strong></a> dňa <?= $post["created_at"] ?>.
            <?php if(!empty($post["tags"])): ?>
                Článok má tagy:
                <?php foreach($post["tags"] as $name => $link): ?>
                    <a class="link link-tag" href="<?= $link ?>"><strong><?= $name ?></strong></a>
                <?php endforeach ?>
            <?php endif ?>
        </div>

        <?php if(is_owner($post)): ?>
            <div class="post-action">
                <a class="link link-edit" href="<?= $post["link_edit"] ?>"><strong>Editovať</strong></a>
                <a class="link link-delete" href="<?= $post["link_delete"] ?>"><strong>Vymazať</strong></a>
            </div>
        <?php endif ?>

        <div class="post-text">
            <?= $post["text"] ?>
        </div>

    </div>


<?php include_footer() ?>