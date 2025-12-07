<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Modele correspondant à la table "fredouil.voyage".
 *
 * @property int $id
 * @property int|null $conducteur
 * @property int|null $trajet
 * @property int|null $idtypev
 * @property int|null $idmarquev
 * @property float|null $tarif
 * @property int|null $nbplacedispo
 * @property int|null $nbbaggage
 * @property string|null $heuredepart
 * @property string|null $contraintes
 *
 * @property Avis[] $avis
 * @property Internaute $conducteur0
 * @property marqueVehicule $marqueVehicule
 * @property TypeVehicule $idtypev0
 * @property Reservation[] $reservations
 * @property Trajet $trajet0
 */
class Voyage extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fredouil.voyage';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['conducteur', 'trajet', 'idtypev', 'idmarquev', 'nbplacedispo', 'nbbaggage'], 'integer'],
            [['tarif'], 'number'],
            [['heuredepart'], 'safe'], // Pour les dates/heures
            [['contraintes'], 'string'],
            [['conducteur', 'trajet', 'idtypev', 'idmarquev', 'tarif', 'nbplacedispo', 'heuredepart'], 'required'],
            [['conducteur'], 'exist', 'skipOnError' => true, 'targetClass' => Internaute::class, 'targetAttribute' => ['conducteur' => 'id']],
            [['idmarquev'], 'exist', 'skipOnError' => true, 'targetClass' => MarqueVehicule::class, 'targetAttribute' => ['idmarquev' => 'id']],
            [['idtypev'], 'exist', 'skipOnError' => true, 'targetClass' => TypeVehicule::class, 'targetAttribute' => ['idtypev' => 'id']],
            [['trajet'], 'exist', 'skipOnError' => true, 'targetClass' => Trajet::class, 'targetAttribute' => ['trajet' => 'id']],
            [['nbplacedispo'], 'compare', 'compareValue' => 0, 'operator' => '>=', 'type' => 'number'],
            [['nbbaggage'], 'compare', 'compareValue' => 0, 'operator' => '>=', 'type' => 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'conducteur' => 'Conducteur',
            'trajet' => 'Trajet',
            'idtypev' => 'Type de véhicule',
            'idmarquev' => 'Marque de véhicule',
            'tarif' => 'Tarif',
            'nbplacedispo' => 'Places disponibles',
            'nbbaggage' => 'Nombre de bagages',
            'heuredepart' => 'Heure de départ',
            'contraintes' => 'Contraintes',
        ];
    }

    /**
     * Gets query for [[Avis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAvis()
    {
        return $this->hasMany(Avis::class, ['id_voyage' => 'id']);
    }

    /**
     * Gets query for [[Conducteur0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConducteur0()
    {
        return $this->hasOne(Internaute::class, ['id' => 'conducteur']);
    }

    /**
     * Gets query for [[Idmarquev0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMarqueVehicule()
    {
        return $this->hasOne(MarqueVehicule::class, ['id' => 'idmarquev']);
    }

    /**
     * Gets query for [[Idtypev0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTypeVehicule()
    {
        return $this->hasOne(TypeVehicule::class, ['id' => 'idtypev']);
    }

    /**
     * Gets query for [[Reservations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReservations()
    {
        return $this->hasMany(Reservation::class, ['voyage' => 'id']);
    }

    /**
     * Gets query for [[Trajet0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTrajet0()
    {
        return $this->hasOne(Trajet::class, ['id' => 'trajet']);
    }

    /**
     * Collecte l'ensemble des voyages correspondant à un id de trajet.
     * @param int $trajetId
     * @return static[]
     */
    public static function getVoyagesByTrajetId($trajetId)
    {
        return static::findAll(['trajet' => $trajetId]);
    }
}
