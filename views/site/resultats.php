<?php

/** @var yii\web\View $this */
/** @var app\models\SearchForm $searchModel */
/** @var app\models\Voyage[] $carpoolingResults */
/** @var array $tgvResults */
/** @var array $walkingRoute */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Résultats de Recherche';
$this->params['breadcrumbs'][] = ['label' => 'Recherche', 'url' => ['recherche']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-resultats">

    <div class="bubble">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

<div class="bubble">
    <h2>Covoiturages disponibles</h2>
    <?php if (!empty($carpoolingResults)): ?>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Départ</th>
                    <th>Arrivée</th>
                    <th>Heure</th>
                    <th>Conducteur</th>
                    <th>Places dispo</th>
                    <th>Tarif</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($carpoolingResults as $voyage): ?>
                    <tr>
                        <td><?= Html::encode($voyage->trajet0->depart ?? 'N/A') ?></td>
                        <td><?= Html::encode($voyage->trajet0->arrivee ?? 'N/A') ?></td>
                        <td><?= Html::encode($voyage->heuredepart) ?></td>
                        <td><?= Html::encode($voyage->conducteur0->pseudo ?? 'N/A') ?></td>
                        <td><?= Html::encode($voyage->nbplacedispo) ?></td>
                        <td><?= Html::encode($voyage->tarif) ?> €</td>
                        <td>
                            <?= Html::a('Réserver', ['site/reserver', 'id' => $voyage->id], ['class' => 'btn btn-success btn-sm']) ?>
                            <?= Html::a('Ajouter Avis', ['site/ajouter-avis', 'id' => $voyage->id], ['class' => 'btn btn-info btn-sm']) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="alert alert-info">Aucun covoiturage trouvé pour votre recherche.</p>
    <?php endif; ?>
</div>

<div class="bubble">
    <h2>Trajets TGV</h2>
    <?php if (!empty($tgvResults)): ?>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Compagnie</th>
                    <th>Numéro</th>
                    <th>Départ</th>
                    <th>Arrivée</th>
                    <th>Heure</th>
                    <th>Durée</th>
                    <th>Prix</th>
                </tr>
            </thead>
            <tbody>
                <div class="bubble">
                <?php foreach ($tgvResults as $tgv): ?>
                    <tr>
                        <td><?= Html::encode($tgv['compagnie']) ?></td>
                        <td><?= Html::encode($tgv['numero']) ?></td>
                        <td><?= Html::encode($tgv['depart']) ?></td>
                        <td><?= Html::encode($tgv['arrivee']) ?></td>
                        <td><?= Html::encode($tgv['heure']) ?></td>
                        <td><?= Html::encode($tgv['duree']) ?></td>
                        <td><?= Html::encode($tgv['prix']) ?> €</td>
                    </tr>
                <?php endforeach; ?>
                </div>
            </tbody>
        </table>
    <?php else: ?>
        <p class="alert alert-info">Aucun trajet TGV trouvé pour votre recherche.</p>
    <?php endif; ?>
</div>
<div class="bubble">
    
    <h2>Itinéraire de marche</h2>
    <?php if (!empty($walkingRoute)): ?>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">De <?= Html::encode($walkingRoute['depart']) ?> à <?= Html::encode($walkingRoute['arrivee']) ?></h5>
                <p class="card-text">Distance estimée : <?= Html::encode($walkingRoute['distance']) ?></p>
                <p class="card-text">Durée estimée : <?= Html::encode($walkingRoute['duree']) ?></p>
                <h6>Instructions :</h6>
                <ul>
                    <?php foreach ($walkingRoute['instructions'] as $instruction): ?>
                        <li><?= Html::encode($instruction) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php else: ?>
        <p class="alert alert-info">Aucun itinéraire de marche trouvé pour votre recherche.</p>
    <?php endif; ?>

</div>
