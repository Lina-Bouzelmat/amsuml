<?php

namespace app\models;

use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * SearchForm modele du formulaire de recherche.
 */
class SearchForm extends Model
{
    public $depart;
    public $arrivee;
    public $nbPersonnes;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['depart', 'arrivee'], 'required'],
            [['depart', 'arrivee'], 'string', 'max' => 255],
            [['nbPersonnes'], 'integer', 'min' => 1],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'depart' => 'Ville de départ',
            'arrivee' => 'Ville d\'arrivée',
            'nbPersonnes' => 'Nombre de personnes',
        ];
    }
}
