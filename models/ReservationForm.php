<?php

namespace app\models;

use yii\base\Model;

/**
 * ReservationForm is the model behind the reservation form.
 */
class ReservationForm extends Model
{
    public $nbplaceresa;
    public $voyage_id; // Pour stocker l'ID du voyage à réserver

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['nbplaceresa', 'voyage_id'], 'required'],
            [['nbplaceresa', 'voyage_id'], 'integer'],
            [['nbplaceresa'], 'compare', 'compareValue' => 1, 'operator' => '>=', 'type' => 'number'],
            [['nbplaceresa'], 'validatePlacesAvailable'],
        ];
    }

    /**
     * Valide que le nombre de places demandées est disponible.
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePlacesAvailable($attribute, $params)
    {
        $voyage = Voyage::findOne($this->voyage_id);
        if (!$voyage) {
            $this->addError($attribute, 'Le voyage spécifié n\'existe pas.');
            return;
        }
        if ($this->nbplaceresa > $voyage->nbplacedispo) {
            $this->addError($attribute, 'Il n\'y a pas assez de places disponibles pour ce voyage. Places disponibles: ' . $voyage->nbplacedispo);
        }
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'nbplaceresa' => 'Nombre de places à réserver',
        ];
    }
}
