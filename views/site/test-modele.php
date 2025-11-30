<?php
/** @var yii\web\View $this */
/** @var app\models\Internaute|null $internaute */
/** @var app\models\Voyage[] $voyagesProposes */
/** @var app\models\Reservation[] $reservationsEffectuees */

use yii\helpers\Html;

$this->title = 'Test du Modèle de Données';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-test-modele">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        Cette page teste le modele de donnees en recuperant les info de l'internaute "Fourmi" et ses données associées (étape 2).
    </p>

    <?php if ($internaute): ?>
        <h2>Informations de l'internaute "<?= Html::encode($internaute->pseudo) ?>"</h2>
        <ul>
            <li><strong>ID :</strong> <?= Html::encode($internaute->id) ?></li>
            <li><strong>Nom :</strong> <?= Html::encode($internaute->prenom) ?> <?= Html::encode($internaute->nom) ?></li>
            <li><strong>Email :</strong> <?= Html::encode($internaute->mail) ?></li>
            <li><strong>Permis de conduire :</strong> <?= $internaute->permis ? 'Oui' : 'Non' ?></li>
            
        </ul>

        <hr>

        <h3>Voyages proposés par "<?= Html::encode($internaute->pseudo) ?>"</h3>
        <?php if ($internaute->permis && !empty($voyagesProposes)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Départ</th>
                        <th>Arrivée</th>
                        <th>Heure départ</th>
                        <th>Tarif</th>
                        <th>Places</th>
                        <th>Véhicule</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($voyagesProposes as $voyage): ?>
                        <tr>
                            <td><?= Html::encode($voyage->trajet0->depart ?? 'N/A') ?></td>
                            <td><?= Html::encode($voyage->trajet0->arrivee ?? 'N/A') ?></td>
                            <td><?= Html::encode($voyage->heuredepart) ?></td>
                            <td><?= Html::encode($voyage->tarif) ?> €</td>
                            <td><?= Html::encode($voyage->nbplacedispo) ?></td>
                            <td><?= Html::encode($voyage->marqueVehicule->marquev ?? 'N/A') ?> (<?= Html::encode($voyage->typeVehicule->typev ?? 'N/A') ?>)</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif (!$internaute->permis): ?>
            <p>Cet internaute n'a pas le permis de conduire et ne peut donc pas proposer de voyages.</p>
        <?php else: ?>
            <p>Cet internaute n'a proposé aucun voyage.</p>
        <?php endif; ?>

        <hr>

        <h3>Réservations effectuées par "<?= Html::encode($internaute->pseudo) ?>"</h3>
        <?php if (!empty($reservationsEffectuees)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Départ</th>
                        <th>distance</th>
                        <th>Arrivée</th>
                        <th>Heure départ</th>
                        <th>Conducteur</th>
                        <th>Places réservées</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservationsEffectuees as $reservation): ?>
                        <tr>
                            <td><?= Html::encode($reservation->voyage0->trajet0->depart ?? 'N/A') ?></td>
                            <td><?= Html::encode($reservation->voyage0->trajet0->distance ?? 'N/A') ?></td>
                            <td><?= Html::encode($reservation->voyage0->trajet0->arrivee ?? 'N/A') ?></td>
                            <td><?= Html::encode($reservation->voyage0->heuredepart ?? 'N/A') ?></td>
                            <td><?= Html::encode($reservation->voyage0->conducteur0->pseudo ?? 'N/A') ?></td>
                            <td><?= Html::encode($reservation->nbplaceresa) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Cet internaute n'a effectué aucune réservation.</p>
        <?php endif; ?>

    <?php else: ?>
        <div class="alert alert-danger">
            L'internaute avec le pseudo "Fourmi" n'a pas été trouvé dans la base de données. Veuillez vous assurer qu'il existe.
        </div>
    <?php endif; ?>

</div>
