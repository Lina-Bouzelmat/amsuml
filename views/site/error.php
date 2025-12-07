<?php

/** @var yii\web\View $this */
/** @var string $name */
/** @var string $message */
/** @var Exception $exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-rose">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
        Une erreur est survenue veuillez recharger la page ou saisir quelque choes de  correcte
    </p>
    <p>
        Si l'erreur perciste: nous contacter via la page contact
    </p>

</div>
