<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= (isset($title) ? $title . " - " : "") ?>Blog</title>

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?= url() ?>/assets/css/style.css">
</head>
<body>

<!-- navigácia -->
<nav>
    <div class="wrapper group">
        <div class="left">
            <ul class="group">
                <li><a href="<?= url() ?>">Domov</a></li>
                <li><a href="<?= url() ?>/o-stranke">O stránke</a></li>
                <li><a href="<?= url() ?>/tag/Tipy">Tipy</a></li>
                <li><a href="<?= url() ?>/tag/Triky">Triky</a></li>
                <li><a href="<?= url() ?>/tag/Šach">Šach</a></li>
                <li><a href="<?= url() ?>/tag/Zdravie">Zdravie</a></li>
                <li><a href="<?= url() ?>/tag/Autá">Autá</a></li>
                <li><a href="<?= url() ?>/vyhladavanie">Vyhľadávanie</a></li>
            </ul>
        </div>

        <div class="right">
            <ul class="group">
                <?php if(!is_logged_in()): ?>
                    <li><a href="<?= url() ?>/prihlasenie">Prihlásenie</a></li>
                <?php else: ?>
                    <li><a href="<?= url() ?>/novy-clanok">Nový článok</a></li>
                    <li><a href="<?= url() ?>/odhlasenie" onclick="return confirm('Naozaj sa chcete odhlásiť?')">Odhlásenie</a></li>
                <?php endif ?>
            </ul>
        </div>
    </div>
</nav>

<?php if (has_messages()): ?>
    <div class="wrapper">
        <?php foreach (get_messages() as $message): ?>
            <div class="message info"><?= $message ?></div>
        <?php endforeach ?>
    </div>
<?php endif; ?>

<!-- obsah stránky -->
<div class="wrapper">
    <main class="content">
