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
    <div class="bubble">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        Cette page teste le modele de donnees en recuperant les info de l'internaute "Fourmi" et ses données associées (étape 2).
    </p>
    </div>
    <?php if ($internaute): ?>
        <div class="bubble">
        <h2>Informations de l'internaute "<?= Html::encode($internaute->pseudo) ?>"</h2>
        <ul>

            <ul><strong>ID :</strong> <?= Html::encode($internaute->id) ?></ul>
            <ul><strong>Nom :</strong> <?= Html::encode($internaute->prenom) ?> <?= Html::encode($internaute->nom) ?></ul>
            <ul><strong>Email :</strong> <?= Html::encode($internaute->mail) ?></ul>
            <ul><strong>Permis de conduire :</strong> <?= $internaute->permis ? 'Oui' : 'Non' ?></ul>
 
        </ul>
        </div>

        <div class="bubble">
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
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($voyagesProposes as $voyage): ?>
                        <tr>
                            <td><?= Html::encode($voyage->trajet0->depart ?? 'N/A') ?></td>
                            <td><?= Html::encode($voyage->trajet0->arrivee ?? 'N/A') ?></td>

                            <td><?= $voyage->heuredepart ?></td>
                            <td><?= $voyage->tarif ?></td>
                            <td><?= Html::encode($voyage->nbplacedispo) ?></td>
                            <td><?= Html::encode($voyage->marqueVehicule->marquev) ?></td>
                            
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif (!$internaute->permis): ?>
            <p>Cet internaute n'a pas le permis de conduire et ne peut donc pas proposer de voyages.</p>
        <?php else: ?>
            <p>Cet internaute n'a proposé aucun voyage.</p>
        <?php endif; ?>
        </div>
        
        <div class="bubble">
        <h3>Réservations effectuées par "<?= Html::encode($internaute->pseudo) ?>"</h3>
        <?php if (!empty($reservationsEffectuees)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Départ</th>
                        <th>Arrivée</th>
                        <th>Heure départ</th>
                        <th>Conducteur</th>
                        <th>Places réservées</th>
                        <th>distance</th>
                        <th>marque du vehicule</tr>
                </thead>
                <tbody>
                    <?php foreach ($reservationsEffectuees as $r): ?>
                        <tr>
                            <td><?= Html::encode($r->voyage0->trajet0->depart ?? 'N/A') ?></td>
                            <td><?= Html::encode($r->voyage0->trajet0->arrivee ?? 'N/A') ?></td>
                            <td><?= Html::encode($r->voyage0->heuredepart ?? 'N/A') ?></td>
                            <td><?= Html::encode($r->voyage0->conducteur0->pseudo ?? 'N/A') ?></td>
                            <td><?= Html::encode($r->nbplaceresa) ?></td>
                            <td><?= Html::encode($r->voyage0->trajet0->distance ?? 'N/A') ?></td>
                            <td><?= Html::encode($r->voyage0->marqueVehicule->marquev) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Cet internaute n'a effectué aucune réservation.</p>
        <?php endif; ?>
        </div>

    <div class="bubble">
        <h2>Tests des méthodes demandées (Étape 2)</h2>
        <h3>Test : getTrajet</h3>
        <?php if ($testTrajet): ?>
            <p><strong>Trajet trouvé :</strong> 
                <?= Html::encode($testTrajet->depart) ?> → 
                <?= Html::encode($testTrajet->arrivee) ?>  
                (<?= Html::encode($testTrajet->distance) ?> km)
            </p>
        <?php else: ?>
            <p style="color:red;">Aucun trajet trouvé.</p>
        <?php endif; ?> 
    </div>

    <h3>Test : getVoyagesByTrajetId()</h3>
    <?php if ($testVoyagesByTrajet): ?>
        <ul>
        <?php foreach ($testVoyagesByTrajet as $v): ?>
            <li>
                Départ : <?= Html::encode($v->trajet0->depart) ?> /
                Arrivée : <?= Html::encode($v->trajet0->arrivee) ?> /
                Tarif : <?= Html::encode($v->tarif) ?>€
            </li>
        <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p style="color:red;">Aucun voyage trouvé.</p>
    <?php endif; ?>

    <h3>Test : getReservationsByVoyageId()</h3>
    <?php if ($testReservations): ?>
        <ul>
        <?php foreach ($testReservations as $r): ?>
            <li>
                Voyageur : <?= Html::encode($r->voyageur) ?> /
                Places : <?= Html::encode($r->nbplaceresa) ?>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p style="color:red;">Aucune réservation trouvée.</p>
    <?php endif; ?>

    <?php else: ?>
        <div class="alert alert-danger">
            L'internaute avec le pseudo fournis n'a pas été trouvé dans la base de données. Veuillez vous assurer qu'il existe.
        </div>
    <?php endif; ?>

</div>
