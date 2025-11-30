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
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
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
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

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
        return $this->render('about');
    }

    /**
     * Action de test des modèles et de leurs relations.
     * Récupère l'internaute 'Fourmi' et affiche ses voyages et réservations.
     *
     * @return string
     */
    public function actionTestModele()
    {
        // Utilisation de la nouvelle méthode pour récupérer l'internaute par son pseudo
        $internaute = \app\models\Internaute::getUserByIdentifiant('Loup');

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

        return $this->render('test-modele', [
            'internaute' => $internaute,
            'voyagesProposes' => $voyagesProposes,
            'reservationsEffectuees' => $reservationsEffectuees,
        ]);
    }

    /**
     * Affiche le formulaire de recherche de voyages.
     *
     * @return string
     */
    public function actionRecherche()
    {
        $model = new \app\models\SearchForm();
        Yii::$app->session->setFlash('rose', "Aucun voyage correspondant à la saisie");
        Yii::$app->session->setFlash('rose', "Votre demande a été envoyée !");
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

        if ($searchModel->load(Yii::$app->request->post()) && $searchModel->validate()) {
            // Recherche de covoiturages
            $carpoolingResults = \app\models\Voyage::find()
                ->joinWith('trajet0')
                ->where([
                    'fredouil.trajet.depart' => $searchModel->depart,
                    'fredouil.trajet.arrivee' => $searchModel->arrivee,
                ])
                // Ajoutez une condition pour la date si nécessaire, en fonction de votre schéma de base de données
                // ->andWhere(['DATE(heuredepart)' => $searchModel->date])
                ->all();

            // Appel des services externes (faux)
            $tgvResults = \app\components\TGVService::getTGV($searchModel->depart, $searchModel->arrivee);
            $walkingRoute = \app\components\MarcheService::getWalkingRoute($searchModel->depart, $searchModel->arrivee);
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
}
