<?php

/** @var yii\web\View $this */
/** @var app\models\SearchForm $model */

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Recherche de Voyages';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-recherche">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Veuillez remplir les champs ci-dessous pour rechercher un voyage :</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'search-form', 'action' => ['site/resultats']]); ?>

                <?= $form->field($model, 'depart')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'arrivee') ?>

                <?= $form->field($model, 'date')->input('date') ?>

                <div class="form-group">
                    <?= Html::submitButton('Rechercher', ['class' => 'btn btn-primary', 'name' => 'search-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
