<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        Bienvenue sur notre plateforme ! 
Ce site a été imaginé pour offrir une interface simple, efficace et évolutive. 
Développé avec Yii2, il pose les fondations d’un projet moderne qui pourra évoluer 
vers des fonctionnalités avancées et personnalisées.
    </p>

    <code><?= __FILE__ ?></code>
</div>
