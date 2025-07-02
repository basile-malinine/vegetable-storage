<?php

/** @var yii\web\View $this */
/** @var string $content */

use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Url;
use app\assets\AppAsset;
use app\widgets\Alert;

AppAsset::register($this);

$script = "$('ul.dropdown-menu [data-toggle=dropdown]').on('click', function(event) { event.preventDefault(); event.stopPropagation(); $(this).parent().siblings().removeClass('open'); $(this).parent().toggleClass('open'); });";
$this->registerJs($script, yii\web\View::POS_READY);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.png')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Yii::$app->name ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => 'АПК Технологии',
        'brandImage' => Url::to("/images/logo.png", true),
        'brandUrl' => Yii::$app->homeUrl,

        'options' => ['class' => 'navbar navbar-expand-md navbar-light bg-light fixed-top']
    ]);

    if (!Yii::$app->user->isGuest) {
        echo $this->render('menu');
    }

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav ms-auto'],
        'items' => [
            Yii::$app->user->isGuest
                ? ['label' => 'Вход', 'url' => ['/user/login'],]
                :
                [
                    'label' => \Yii::$app->user->identity->name,
                    'items' => [
                        [
                            'label' => 'Выйти',
                            'url' => ['/user/logout']
                            /*, 'linkOptions' => ['data-method' => 'post']*/
                        ],
                    ],
                ]
        ],
    ]);
    NavBar::end();
    ?>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer id="footer" class="mt-auto py-3 bg-light">
    <div class="container">
        <div class="row text-muted">
            <div class="col-md-6 text-center text-md-start">&copy; Basile's <?= date('Y') ?></div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
