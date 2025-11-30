<?php

/** @var yii\web\View $this */
/** @var app\models\AvisForm $model */
/** @var app\models\Voyage $voyage */

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\widgets\DetailView;

$this->title = 'Ajouter un Avis';
$this->params['breadcrumbs'][] = ['label' => 'Résultats de Recherche', 'url' => ['resultats']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-ajouter-avis">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php endif; ?>

    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger">
            <?= Yii::$app->session->getFlash('error') ?>
        </div>
    <?php endif; ?>

    <h2>Avis pour le Voyage</h2>
    <?php if ($voyage): ?>
        <?= DetailView::widget([
            'model' => $voyage,
            'attributes' => [
                [
                    'label' => 'Conducteur',
                    'value' => $voyage->conducteur0->pseudo ?? 'N/A',
                ],
                [
                    'label' => 'Départ',
                    'value' => $voyage->trajet0->depart ?? 'N/A',
                ],
                [
                    'label' => 'Arrivée',
                    'value' => $voyage->trajet0->arrivee ?? 'N/A',
                ],
                'heuredepart',
                'tarif',
            ],
        ]) ?>
    <?php else: ?>
        <p class="alert alert-warning">Voyage non trouvé.</p>
    <?php endif; ?>

    <h3>Soumettre votre avis</h3>
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'avis-form']); ?>

                <?= $form->field($model, 'note')->textInput(['type' => 'number', 'min' => 1, 'max' => 5]) ?>

                <?= $form->field($model, 'commentaire')->textarea(['rows' => 6]) ?>

                <div class="form-group">
                    <?= Html::submitButton('Ajouter l\'avis', ['class' => 'btn btn-primary', 'name' => 'avis-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
