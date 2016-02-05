<?php

$tag = segment(2);

if (!$tag) show_404();

$tag = urldecode($tag);

$posts = get_posts_by_tag($tag);


include_header(array("title" => "Tag " . $tag));
?>

    <h1 class="text-center">Články s tagom <?= plain($tag) ?></h1>

    <?php if(count($posts)): foreach($posts as $post): ?>
        <div class="post">

            <h2><a href="<?= $post["link"] ?>"><?= $post["title"] ?></a></h2>

            <div class="post-info">
                Napísal <a href="<?= $post["user_link"] ?>"><strong><?= $post["user_name"] ?></strong></a> dňa <?= $post["created_at"] ?>.
            </div>

            <div class="post-teaser">
                <?= $post["teaser"] ?>
            </div>

        </div>
    <?php endforeach; else: ?>
        <div class="message">Žiaľ v databáze k tagu <strong><?= plain($tag) ?></strong> nemáme žiadne príspevky.</div>
    <?php endif ?>

<?php

include_footer();