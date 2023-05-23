<?php

namespace open20\amos\een\controllers;


use open20\amos\admin\models\UserProfile;
use open20\amos\core\user\User;
use open20\amos\een\AmosEen;
use open20\amos\een\utility\EenMailUtility;
use open20\amos\een\utility\EenUtility;
use Yii;
use open20\amos\core\controllers\CrudController;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\helpers\Html;
use open20\amos\core\helpers\T;
use open20\amos\core\controllers\AmosController;
use yii\helpers\Url;


/**
 * EenExprOfInterestFormController implements the CRUD actions for EenExprOfInterestForm model.
 */
class EenExprOfInterestFormController extends AmosController
{



    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = ArrayHelper::merge(parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => [
                                'send-info-request',
                            ],
                            'roles' => ['@']
                        ],

                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['post', 'get']
                    ]
                ]
            ]);
        return $behaviors;
    }


    public function actionSendInfoRequest()
    {
        $model=New \open20\amos\een\models\EenExprOfInterestForm;

        if ($model->load(Yii::$app->request->post()) ) {
            $een= \open20\amos\een\models\EenPartnershipProposal::findOne(['id'=>$model->een_partnership_proposal_id]);
            $body= "
                 Maifestazione d'interesse per la proposta di collaborazione dal mondo \"".$een->content_title."\": 
                 ID Utente: ".$model->userprofile_id."
                 Nome: ".$model->name."
                 Mail: ".$model->email."
                 Telefono: ".$model->phone."
                 Indirizzo: ".$model->address."
                 Tipologie di utenti interessati a partecipare alla piattaforma: ".$model->user_type."
                 Note: ".$model->note;

            \Yii::$app->mailer->compose()
                ->setTo(\Yii::$app->controller->module->expofintintoform['emailtosend'])
                ->setFrom(['notifiche_openinnovation@regione.lombardia.it' => 'Open Innovation Lombardia informa'])
                ->setSubject('Manifestazione d\'interesse EEN:"'.$een->content_title.'"')
                ->setTextBody($body)
                ->send();
            Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Richiesta inviata correttamente'));
        }
        return $this->redirect(Url::previous());
    }



}