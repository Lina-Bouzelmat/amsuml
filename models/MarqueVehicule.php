<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "fredouil.marquevehicule".
 *
 * @property int $id
 * @property string|null $marquev
 *
 * @property Voyage[] $voyages
 */
class MarqueVehicule extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fredouil.marquevehicule';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['marquev'], 'string'],
            [['marquev'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'marquev' => 'Marque du véhicule',
        ];
    }

    /**
     * Gets query for [[Voyages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVoyages()
    {
        return $this->hasMany(Voyage::class, ['idmarquev' => 'id']);
    }
}
