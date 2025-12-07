<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{


/**
     * Action de test des modèles et de leurs relations.
     * Récupère l'internaute 'Fourmi' et affiche ses voyages et réservations.
     *
     * @return string
     */
    public function actionTestModele()
    {
        Yii::$app->session->setFlash('flashMessage', 'Chargement des données de test');
        // Utilisation de la nouvelle méthode pour récupérer l'internaute par son pseudo
        $internaute = \app\models\Internaute::getUserByIdentifiant('Chien');

        $voyagesProposes = [];
        $reservationsEffectuees = [];
        if ($internaute) {
            // Si l'internaute a le permis, on cherche ses propositions de voyages
            if ($internaute->permis) {
                $voyagesProposes = $internaute->getVoyages()->with('trajet0', 'marqueVehicule', 'typeVehicule')->all();
            }
            // On cherche les réservations de cet internaute
            $reservationsEffectuees = $internaute->getReservations()->with('voyage0.trajet0', 'voyage0.conducteur0')->all();
        }

        $testTrajet = \app\models\Trajet::getTrajet('Paris', 'Lyon');   // a tj
        $testVoyagesByTrajet = null;                                          // a tj
        if ($testTrajet) {
            $testVoyagesByTrajet = \app\models\Voyage::getVoyagesByTrajetId($testTrajet->id);
        }
        $testReservations = null;                                             // a tej
        if ($testVoyagesByTrajet) {
            $testReservations = \app\models\Reservation::getReservationsByVoyageId(
            2
            );
        }

        $voyagesTrajetTest = \app\models\Voyage::getVoyagesByTrajetId($trajetTest->id ?? null);
        if (!empty($voyagesProposes)) {
            $resaTest = \app\models\Reservation::getReservationsByVoyageId($voyagesProposes[0]->id);
        }

        return $this->render('test-modele', [
            'internaute' => $internaute,
            'voyagesProposes' => $voyagesProposes,
            'reservationsEffectuees' => $reservationsEffectuees,

            // le teste des 4 fonction demandé
            'testTrajet'               => $testTrajet,
            'testVoyagesByTrajet'      => $testVoyagesByTrajet,
            'testReservations'         => $testReservations,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        Yii::$app->session->setFlash('flashMessage', 'Bienvenue sur CERICar');
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        Yii::$app->session->setFlash('flashMessage', 'Connexion requise pour acceder au fonctionnalites.');
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            Yii::$app->session->setFlash('flashMessage', 'Connexion réussie');
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->session->setFlash('flashMessage', 'Vous êtes maintenant déconnecté.');
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        Yii::$app->session->setFlash('flashMessage', 'Vous pouvez envoyer un message via le formulaire.');

        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('flashMessage', 'Votre message a été envoyé.');
            
            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        Yii::$app->session->setFlash('flashMessage', 'Voici les informations sur l’application.');
        return $this->render('about');
    }

    /**
     * Affiche le formulaire de recherche de voyages.
     *
     * @return string
     */
    public function actionRecherche(){
        
        $model = new \app\models\SearchForm();
    
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // Effectuer la recherche
            $results = \app\models\Voyage::find()
                ->joinWith('trajet0')
                ->where(['depart' => $model->depart, 'arrivee' => $model->arrivee])
                ->andWhere(['>=', 'nbplacedispo', $model->nbPersonnes])
                ->all();

                if (Yii::$app->request->isAjax) {
                    return $this->renderAjax('resultats', [
                        'results' => $results, 
                        'searchModel' => $model,
                    ]);
                }
                return $this->render('recherche', [
                    'results' => $results,
                    'model' => $model,
                ]);
        }

        return $this->render('recherche', [
            'model' => $model,
        ]);
    }

    /**
     * Affiche les résultats de la recherche de voyages.
     *
     * @return string
     */
    public function actionResultats()
    {
        $searchModel = new \app\models\SearchForm();
        $carpoolingResults = [];
        $tgvResults = [];
        $walkingRoute = [];

        if ($model->load(Yii::$app->request->get()) && $model->validate()) {
            // Recherche de covoiturages
            $carpoolingResults = \app\models\Voyage::find()
                ->joinWith('trajet0')
                ->where([
                    'fredouil.trajet.depart' => $searchModel->depart,
                    'fredouil.trajet.arrivee' => $searchModel->arrivee,

                ])
                ->all();

            // Appel des services externes (faux)
            $tgvResults = \app\components\TGVService::getTGV($searchModel->depart, $searchModel->arrivee);
            $walkingRoute = \app\components\MarcheService::getWalkingRoute($searchModel->depart, $searchModel->arrivee);
        }

        if (Yii::$app->request->isAjax) {
        // Si la requête est Ajax, renvoyer les résultats partiels
        return $this->renderPartial('resultats', [
            'searchModel' => $searchModel,
            'carpoolingResults' => $carpoolingResults,
            'tgvResults' => $tgvResults,
            'walkingRoute' => $walkingRoute,
        ]);
    }
        return $this->render('resultats', [
            'searchModel' => $searchModel,
            'carpoolingResults' => $carpoolingResults,
            'tgvResults' => $tgvResults,
            'walkingRoute' => $walkingRoute,
        ]);
    }

    /**
     * Gère la réservation d'un voyage.
     *
     * @param int $id L'ID du voyage à réserver.
     * @return Response|string
     * @throws \yii\web\NotFoundHttpException si le voyage n'est pas trouvé.
     */
    public function actionReserver($id)
    {
        Yii::$app->session->setFlash('flashMessage', 'Vous êtes sur la page de réservation.');

        $voyage = \app\models\Voyage::findOne($id);
        if ($voyage === null) {
            throw new \yii\web\NotFoundHttpException('Le voyage demandé n\'existe pas.');
        }

        $model = new \app\models\ReservationForm();
        $model->voyage_id = $id; // Pré-remplir l'ID du voyage

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // Vérifier si l'utilisateur est connecté
            if (Yii::$app->user->isGuest) {
                Yii::$app->session->setFlash('error', 'Vous devez être connecté pour effectuer une réservation.');
                return $this->redirect(['site/login']);
            }

            $transaction = Yii::$app->db->beginTransaction();
            try {
                $reservation = new \app\models\Reservation();
                $reservation->voyage = $voyage->id;
                $reservation->voyageur = Yii::$app->user->identity->id; // L'ID de l'internaute connecté
                $reservation->nbplaceresa = $model->nbplaceresa;

                if ($reservation->save()) {
                    // Mettre à jour le nombre de places disponibles dans le voyage
                    $voyage->nbplacedispo -= $model->nbplaceresa;
                    if ($voyage->save()) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'Votre réservation a été effectuée avec succès !');
                        return $this->redirect(['site/resultats']); // Rediriger vers les résultats ou une page de confirmation
                    } else {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', 'Erreur lors de la mise à jour du voyage.');
                    }
                } else {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Erreur lors de la création de la réservation.');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Une erreur inattendue est survenue lors de la réservation.');
                Yii::error($e->getMessage());
            }
        }

        return $this->render('reserver', [
            'model' => $model,
            'voyage' => $voyage,
        ]);
    }

    /**
     * Gère l'ajout d'un avis pour un voyage.
     *
     * @param int $id L'ID du voyage pour lequel ajouter un avis.
     * @return Response|string
     * @throws \yii\web\NotFoundHttpException si le voyage n'est pas trouvé.
     */
    public function actionAjouterAvis($id)
    {
        Yii::$app->session->setFlash('flashMessage', 'Ajoutez un avis pour ce voyage.');

        $voyage = \app\models\Voyage::findOne($id);
        if ($voyage === null) {
            throw new \yii\web\NotFoundHttpException('Le voyage demandé n\'existe pas.');
        }

        $model = new \app\models\AvisForm();
        $model->voyage_id = $id; // Pré-remplir l'ID du voyage

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // Vérifier si l'utilisateur est connecté
            if (Yii::$app->user->isGuest) {
                Yii::$app->session->setFlash('error', 'Vous devez être connecté pour ajouter un avis.');
                return $this->redirect(['site/login']);
            }

            $avis = new \app\models\Avis();
            $avis->id_voyage = $voyage->id;
            $avis->id_internaute = Yii::$app->user->identity->id; // L'ID de l'internaute connecté
            $avis->note = $model->note;
            $avis->commentaire = $model->commentaire;
            $avis->dateavis = date('Y-m-d H:i:s'); // Date actuelle

            if ($avis->save()) {
                Yii::$app->session->setFlash('success', 'Votre avis a été ajouté avec succès !');
                return $this->redirect(['site/resultats']); // Rediriger vers les résultats ou la page du voyage
            } else {
                Yii::$app->session->setFlash('error', 'Erreur lors de l\'ajout de votre avis.');
            }
        }

        return $this->render('ajouter-avis', [
            'model' => $model,
            'voyage' => $voyage,
        ]);
    }

    public function actionResultatsAjax()
    {
        Yii::$app->session->setFlash('flashMessage', 'resultat de votre recherche.');

        return $this->renderPartial('resultats', [
            'covs' => $covs,
            'tgv' => $tgv,
            'itineraire' => $itineraire,
        ]);
    }

}
