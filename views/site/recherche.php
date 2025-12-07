<?php

/** @var yii\web\View $this */
/** @var app\models\SearchForm $model */
/** @var array $results */

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;

$this->title = 'Recherche de Voyages';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-recherche">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Veuillez remplir les champs ci-dessous pour rechercher un voyage :</p>

    <?php $form = ActiveForm::begin([
        'method' => 'post',
        'options' => ['id' => 'search-form']
    ]); ?>

    <div class="row">
        <div class="col-lg-3">
            <?= $form->field($model, 'depart')->textInput(['autofocus' => true]) ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'arrivee') ?>
        </div>
        <div class="col-lg-3">
            <?= $form->field($model, 'nbPersonnes')->textInput(['autofocus' => true]) ?>
        </div>
        <div class="form-group">
            <?= Html::submitButton('Rechercher', ['class' => 'btn-rose', 'name' => 'search-button']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
    <?php if (isset($results) && !empty($results)): ?>
        <div id="resultats-container">
            <h3>Résultats de la recherche :</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Départ</th>
                        <th>Arrivée</th>
                        <th>Heure départ</th>
                        <th>Tarif</th>
                        <th>Places disponibles</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $voyage): ?>
                        <tr>
                            <td><?= Html::encode($voyage->trajet0->depart) ?></td>
                            <td><?= Html::encode($voyage->trajet0->arrivee) ?></td>
                            <td><?= Html::encode($voyage->heuredepart) ?></td>
                            <td><?= Html::encode($voyage->tarif) ?> €</td>
                            <td><?= Html::encode($voyage->nbplacedispo) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>Aucun voyage trouvé pour votre recherche.</p>
    <?php endif; ?>
</div>