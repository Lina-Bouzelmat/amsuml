<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "fredouil.trajet".
 *
 * @property int $id
 * @property string|null $depart
 * @property string|null $arrivee
 * @property int|null $distance
 *
 * @property Voyage[] $voyages
 */
class Trajet extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fredouil.trajet';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['depart', 'arrivee'], 'string'],
            [['distance'], 'integer'],
            [['depart', 'arrivee', 'distance'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'depart' => 'Départ',
            'arrivee' => 'Arrivée',
            'distance' => 'Distance (km)',
        ];
    }

    /**
     * Gets query for [[Voyages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVoyages()
    {
        return $this->hasMany(Voyage::class, ['trajet' => 'id']);
    }

    /**
     * Récupère un objet trajet à partir de la ville de départ et d'arrivée.
     * @param string $depart
     * @param string $arrivee
     * @return static|null
     */
    public static function getTrajet($depart, $arrivee)
    {
        return static::findOne(['depart' => $depart, 'arrivee' => $arrivee]);
    }
}
