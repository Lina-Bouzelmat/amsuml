<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "fredouil.internaute".
 *
 * @property int $id
 * @property string|null $pseudo
 * @property string|null $pass
 * @property string|null $nom
 * @property string|null $prenom
 * @property string|null $mail
 * @property string|null $photo
 * @property bool|null $permis
 *
 * @property Avis[] $avis
 * @property Reservation[] $reservations
 * @property Voyage[] $voyages
 */
class Internaute extends ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fredouil.internaute';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pseudo', 'pass', 'nom', 'prenom', 'mail', 'photo'], 'string'],
            [['mail'], 'email'],
            [['pseudo'], 'unique'],
            [['permis'], 'boolean'],
            [['pseudo', 'pass', 'nom', 'prenom', 'mail'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pseudo' => 'Pseudo',
            'pass' => 'Mot de passe',
            'nom' => 'Nom',
            'prenom' => 'Prénom',
            'mail' => 'Mail',
            'photo' => 'Photo',
            'permis' => 'Permis',
        ];
    }

    /**
     * Gets query for [[Avis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAvis()
    {
        return $this->hasMany(Avis::class, ['id_internaute' => 'id']);
    }

    /**
     * Gets query for [[Reservations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReservations()
    {
        return $this->hasMany(Reservation::class, ['voyageur' => 'id']);
    }

    /**
     * Gets query for [[Voyages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVoyages()
    {
        return $this->hasMany(Voyage::class, ['conducteur' => 'id']);
    }
    
    // Méthodes utiles pour IdentityInterface (authentification)

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // pour la secu token
        return null;
    }

    public static function findByUsername($username)
    {
        return static::findOne(['pseudo' => $username]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        // Sera utilisé pour les cookies de con
        return null;
    }

    public function validateAuthKey($authKey)
    {
        // Sera utilisé pour les cookies de con
        return false;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->pass);
    }

    /**
     * Récupère un internaute par son pseudo.
     * @param string $pseudo
     * @return static|null
     */
    public static function getUserByIdentifiant($pseudo)
    {
        return static::findOne(['pseudo' => $pseudo]);
    }
}
