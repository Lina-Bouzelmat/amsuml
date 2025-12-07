<?php

/** @var yii\web\View $this */
/** @var app\models\ReservationForm $model */
/** @var app\models\Voyage $voyage */

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\widgets\DetailView;

$this->title = 'Réserver un Voyage';
$this->params['breadcrumbs'][] = ['label' => 'Résultats de Recherche', 'url' => ['resultats']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-reserver">
<?php // <h1><?= Html::encode($this->title) ?></h1> 


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

    <div class="bubble">
    <h2>Détails du Voyage</h2>
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
                'nbplacedispo',
                'nbbaggage',
                'contraintes',
            ],
        ]) ?>
    <?php else: ?>
        <p class="alert alert-warning">Voyage non trouvé.</p>
    <?php endif; ?>
    </div>

    <?php if ($voyage && $voyage->nbplacedispo > 0): ?>
        <h3>Effectuer votre réservation</h3>
        <div class="row">
            <div class="col-lg-5">
                <?php $form = ActiveForm::begin(['id' => 'reservation-form']); ?>

                    <?= $form->field($model, 'nbplaceresa')->textInput(['type' => 'number', 'min' => 1, 'max' => $voyage->nbplacedispo]) ?>

                    <div class="form-group">
                        <?= Html::submitButton('Confirmer la réservation', ['class' => 'btn-rose', 'name' => 'reserve-button']) ?>
                    </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    <?php elseif ($voyage && $voyage->nbplacedispo <= 0): ?>
        <p class="alert alert-warning">Désolé, il n'y a plus de places disponibles pour ce voyage.</p>
    <?php endif; ?>

</div>
