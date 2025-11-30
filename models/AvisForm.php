<?php

namespace app\models;

use yii\base\Model;

/**
 * AvisForm is the model behind the review form.
 */
class AvisForm extends Model
{
    public $note;
    public $commentaire;
    public $voyage_id; // Pour stocker l'ID du voyage à noter

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['note', 'commentaire', 'voyage_id'], 'required'],
            [['note', 'voyage_id'], 'integer'],
            [['commentaire'], 'string', 'max' => 1000],
            [['note'], 'integer', 'min' => 1, 'max' => 5],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'note' => 'Note (sur 5)',
            'commentaire' => 'Votre commentaire',
        ];
    }
}
