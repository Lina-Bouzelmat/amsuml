<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "fredouil.avis".
 *
 * @property int $id
 * @property int|null $id_internaute
 * @property int|null $id_voyage
 * @property int|null $note
 * @property string|null $commentaire
 * @property string|null $dateavis
 *
 * @property Internaute $internaute
 * @property Voyage $voyage
 */
class Avis extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fredouil.avis';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_internaute', 'id_voyage', 'note'], 'integer'],
            [['commentaire'], 'string'],
            [['dateavis'], 'safe'], // Pour les dates
            [['id_internaute', 'id_voyage', 'note', 'commentaire'], 'required'],
            [['note'], 'integer', 'min' => 1, 'max' => 5], // Note entre 1 et 5
            [['id_internaute'], 'exist', 'skipOnError' => true, 'targetClass' => Internaute::class, 'targetAttribute' => ['id_internaute' => 'id']],
            [['id_voyage'], 'exist', 'skipOnError' => true, 'targetClass' => Voyage::class, 'targetAttribute' => ['id_voyage' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_internaute' => 'Internaute',
            'id_voyage' => 'Voyage',
            'note' => 'Note',
            'commentaire' => 'Commentaire',
            'dateavis' => 'Date de l\'avis',
        ];
    }

    /**
     * Gets query for [[Internaute]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInternaute()
    {
        return $this->hasOne(Internaute::class, ['id' => 'id_internaute']);
    }

    /**
     * Gets query for [[Voyage]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVoyage()
    {
        return $this->hasOne(Voyage::class, ['id' => 'id_voyage']);
    }
}
