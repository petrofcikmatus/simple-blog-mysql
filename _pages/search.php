<?php

if (!empty($_GET["search"])){
    $posts = get_posts_like($_GET["search"]);
} else {
    $posts = array();
}


include_header(array("title" => "Vyhľadávanie")); ?>

<h1 class="text-center">Vyhľadávanie</h1>

<form method="get">

    <label for="search"><strong>Čo hľadáte?</strong></label>
    <input type="text" name="search" id="search" value="<?= (isset($_GET["search"]) ? $_GET["search"] : plain($post["search"])) ?>" autofocus>

    <input type="submit" value="Hľadať">
</form>

<?php if(!empty($_GET["search"])): if(count($posts)): foreach($posts as $post): ?>
    <div class="post extra-padding">

        <h2><a href="<?= $post["link"] ?>"><?= $post["title"] ?></a></h2>

        <div class="post-info">
            Napísal <a href="<?= $post["user_link"] ?>"><strong><?= $post["user_name"] ?></strong></a> dňa <?= $post["created_at"] ?>
        </div>

        <div class="post-teaser">
            <?= $post["teaser"] ?>
        </div>

        <?php if(!empty($post["tags"])): ?>
            <div class="post-tags">
                Tagy:
                <?php foreach($post["tags"] as $name => $link): ?>
                    <a class="link link-tag" href="<?= $link ?>"><strong><?= $name ?></strong></a>
                <?php endforeach ?>
            </div>
        <?php endif ?>

    </div>
<?php endforeach; else: ?>
    <div class="message">Pre zadaný výraz sme nenašli žiadny článok.</div>
<?php endif; endif ?>

<?php include_footer(); ?>
