<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Modele correspondant à la table "fredouil.typevehicule".
 *
 * @property int $id
 * @property string|null $typev
 *
 * @property Voyage[] $voyages
 */
class TypeVehicule extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fredouil.typevehicule';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['typev'], 'string'],
            [['typev'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'typev' => 'Type de véhicule',
        ];
    }

    /**
     * Gets query for [[Voyages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVoyages()
    {
        return $this->hasMany(Voyage::class, ['idtypev' => 'id']);
    }
}
