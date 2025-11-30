<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "fredouil.reservation".
 *
 * @property int $id
 * @property int|null $voyage
 * @property int|null $voyageur
 * @property int|null $nbplaceresa
 *
 * @property Internaute $voyageur0
 * @property Voyage $voyage0
 */
class Reservation extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fredouil.reservation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['voyage', 'voyageur', 'nbplaceresa'], 'integer'],
            [['voyage', 'voyageur', 'nbplaceresa'], 'required'],
            [['voyage'], 'exist', 'skipOnError' => true, 'targetClass' => Voyage::class, 'targetAttribute' => ['voyage' => 'id']],
            [['voyageur'], 'exist', 'skipOnError' => true, 'targetClass' => Internaute::class, 'targetAttribute' => ['voyageur' => 'id']],
            [['nbplaceresa'], 'compare', 'compareValue' => 1, 'operator' => '>=', 'type' => 'number'], // Au moins 1 place
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'voyage' => 'Voyage',
            'voyageur' => 'Voyageur',
            'nbplaceresa' => 'Nombre de places réservées',
        ];
    }

    /**
     * Gets query for [[Voyageur0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVoyageur0()
    {
        return $this->hasOne(Internaute::class, ['id' => 'voyageur']);
    }

    /**
     * Gets query for [[Voyage0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVoyage0()
    {
        return $this->hasOne(Voyage::class, ['id' => 'voyage']);
    }

    /**
     * Collecte l'ensemble des réservations correspondant à un id de voyage.
     * @param int $voyageId
     * @return static[]
     */
    public static function getReservationsByVoyageId($voyageId)
    {
        return static::findAll(['voyage' => $voyageId]);
    }
}
