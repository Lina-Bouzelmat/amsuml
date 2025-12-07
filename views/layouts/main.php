<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);

$this->registerJsFile('@web/js/recherche.js', [
    'depends' => [\yii\web\JqueryAsset::class],
]);

$this->registerCssFile('@web/css/site.css');
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<div id="notification-banner" class="notification-hidden">
    <span id="notification-message"></span>
</div>

<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-md bg-white shadow-sm fixed-top',
        ],
    ]);

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav ms-auto'],
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index'], 'linkOptions' => ['class' => 'nav-link']],
            ['label' => 'Modele', 'url' => ['/site/test-modele'], 'linkOptions' => ['class' => 'nav-link']],
            ['label' => 'Recherche', 'url' => ['/site/recherche'], 'linkOptions' => ['class' => 'nav-link']],
            ['label' => 'Contact', 'url' => ['/site/contact'], 'linkOptions' => ['class' => 'nav-link']],
            ['label' => 'A propos', 'url' => ['/site/about'], 'linkOptions' => ['class' => 'nav-link']],
            ['label' => 'Erreur', 'url' => ['/site/error'], 'linkOptions' => ['class' => 'nav-link']],
            
            Yii::$app->user->isGuest
                ? ['label' => 'Login', 'url' => ['/site/login'], 'linkOptions' => ['class' => 'nav-link']]
                : '<li class="nav-item">'
                    . Html::beginForm(['/site/logout'])
                    . Html::submitButton(
                        'Logout (' . Yii::$app->user->identity->username . ')',
                        ['class' => 'nav-link btn btn-link logout']
                    )
                    . Html::endForm()
                    . '</li>'
        ]
    ]);

    NavBar::end();
    ?>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="page-wrapper">
        <div class="page-card">
            <?php if (!empty($this->params['breadcrumbs'])): ?>
                <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
            <?php endif ?>

            <?php if (Yii::$app->session->hasFlash('flashMessage')): ?>
                <div class="alert-rose">
                    <?= Yii::$app->session->getFlash('flashMessage') ?>
                </div>
            <?php endif; ?>

            <?= $content ?>
        </div>
    </div>
</main>

<footer id="footer" class="mt-auto py-3 bg-light">
    <div class="container">
        <div class="row text-muted">
            <div class="col-md-6 text-center text-md-start">&copy; CERICar <?= date('Y') ?></div>
            <div class="col-md-6 text-center text-md-end"><?= Yii::powered() ?></div>
        </div>
    </div>
</footer>



<!-- Auto-affichage des Flash Yii2 -->
<?php if (Yii::$app->session->hasFlash('success')): ?>
<script>
    showNotification("<?= Yii::$app->session->getFlash('success') ?>", "success");
</script>
<?php endif; ?>

<?php if (Yii::$app->session->hasFlash('error')): ?>
<script>
    showNotification("<?= Yii::$app->session->getFlash('error') ?>", "error");
</script>
<?php endif; ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>