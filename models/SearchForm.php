<?php

namespace app\models;

use yii\base\Model;

/**
 * SearchForm is the model behind the search form.
 */
class SearchForm extends Model
{
    public $depart;
    public $arrivee;
    public $date; // Ajout d'un champ date pour une recherche plus réaliste

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['depart', 'arrivee'], 'required'],
            [['depart', 'arrivee'], 'string', 'max' => 255],
            [['date'], 'date', 'format' => 'yyyy-MM-dd'],
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
            'date' => 'Date du voyage',
        ];
    }
}
