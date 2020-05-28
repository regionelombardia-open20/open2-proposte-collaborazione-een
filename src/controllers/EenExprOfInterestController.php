<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    Open20Package
 * @category   CategoryName
 */

namespace open20\amos\een\controllers;

use open20\amos\admin\models\search\UserProfileSearch;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\user\User;
use open20\amos\dashboard\controllers\TabDashboardControllerTrait;
use open20\amos\een\AmosEen;
use open20\amos\een\models\EenExprOfInterestHistory;
use open20\amos\een\models\EenPartnershipProposal;
use open20\amos\een\models\EenStaff;
use open20\amos\een\utility\EenMailUtility;
use open20\amos\een\utility\EenUtility;
use open20\amos\een\widgets\icons\WidgetIconEenExprOfInterestDashboard;
use kartik\mpdf\Pdf;
use openinnovation\organizations\models\Organizations;
use Yii;
use open20\amos\een\models\EenExprOfInterest;
use open20\amos\een\models\search\EenExprOfInterestSearch;
use open20\amos\core\controllers\CrudController;
use yii\base\Exception;
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
use yii\helpers\Url;
use open20\amos\een\assets\ProposteCollaborazioneEenAsset;

/**
 * EenExprOfInteresttController implements the CRUD actions for EenExprOfInterest model.
 */
class EenExprOfInterestController extends CrudController
{
    use TabDashboardControllerTrait;

    public function init()
    {
        $this->setModelObj(new EenExprOfInterest());
        $this->setModelSearch(new EenExprOfInterestSearch());

        ProposteCollaborazioneEenAsset::register(Yii::$app->view);

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => Yii::t('amoscore', '{iconaTabella}' . Html::tag('p', Yii::t('amoscore', 'Table')), [
                    'iconaTabella' => AmosIcons::show('view-list-alt')
                ]),
                'url' => '?currentView=grid'
            ],
            /*'list' => [
                'name' => 'list',
                'label' => Yii::t('amoscore', '{iconaLista}'.Html::tag('p',Yii::t('amoscore', 'List')), [
                    'iconaLista' => AmosIcons::show('view-list')
                ]),           
                'url' => '?currentView=list'
            ],
            'icon' => [
                'name' => 'icon',
                'label' => Yii::t('amoscore', '{iconaElenco}'.Html::tag('p',Yii::t('amoscore', 'Icons')), [
                    'iconaElenco' => AmosIcons::show('grid')
                ]),           
                'url' => '?currentView=icon'
            ],
            'map' => [
                'name' => 'map',
                'label' => Yii::t('amoscore', '{iconaMappa}'.Html::tag('p',Yii::t('amoscore', 'Map')), [
                    'iconaMappa' => AmosIcons::show('map')
                ]),       
                'url' => '?currentView=map'
            ],
            'calendar' => [
                'name' => 'calendar',
                'intestazione' => '', //codice HTML per l'intestazione che verrà caricato prima del calendario,
                                      //per esempio si può inserire una funzione $model->getHtmlIntestazione() creata ad hoc
                'label' => Yii::t('amoscore', '{iconaCalendario}'.Html::tag('p',Yii::t('amoscore', 'Calendar')), [
                    'iconaMappa' => AmosIcons::show('calendar')
                ]),       
                'url' => '?currentView=calendar'
            ],*/
        ]);

        parent::init();
        $this->initDashboardTrait();

    }


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'not-interested',
                            'staff-by-area',
                            'pdf',
                            'index',
                            'index-own',
                            'get-organization-selected-ajax'
                        ],
                        'roles' => ['EEN_READER']
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'staff-een',
                            'staff-by-area',
                            'transfer-expr-of-interest',
                            'disassociate-staff-een',
                            'index-all'
                        ],
                        'roles' => ['ADMIN']
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'transfer-expr-of-interest',
                            'index-received',
                            'index-all',
                            'take-over',
                        ],
                        'roles' => ['STAFF_EEN'],
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

    /**
     * Lists all EenExprOfInterest models.
     * @return mixed
     */
    public function actionIndex($layout = NULL)
    {
        if(\Yii::$app->user->can('STAFF_EEN')){
            return $this->redirect('/een/een-expr-of-interest/index-received');
        }
        return $this->redirect('/een/een-expr-of-interest/index-own');
    }

    /**
     * Lists all EenExprOfInterest models.
     * @return mixed
     */
    public function actionIndexOwn($layout = NULL)
    {
        Url::remember();
        $this->setIndexParams();

        $this->setDataProvider($this->getModelSearch()->searchOwnExprOfInterest(Yii::$app->request->getQueryParams()));
        $this->setUpLayout('list');

        //se il layout di default non dovesse andar bene si può aggiuntere il layout desiderato
        //in questo modo nel controller "return parent::actionIndex($this->layout);"
        if ($layout) {
            $this->setUpLayout($layout);
        }

        return $this->render('index', [
            'dataProvider' => $this->getDataProvider(),
            'model' => $this->getModelSearch(),
            'currentView' => $this->getCurrentView(),
            'availableViews' => $this->getAvailableViews(),
            'url' => ($this->url) ? $this->url : NULL,
            'parametro' => ($this->parametro) ? $this->parametro : NULL
        ]);
    }

    /**
     * Lists all EenExprOfInterest models.
     * @return mixed
     */
    public function actionIndexReceived($layout = NULL)
    {
        Url::remember();
        $this->setIndexParams();
        $dataProviderReceived = $this->getModelSearch()->searchReceived(Yii::$app->request->getQueryParams());
        $this->setUpLayout('list');

        //se il layout di default non dovesse andar bene si può aggiuntere il layout desiderato
        //in questo modo nel controller "return parent::actionIndex($this->layout);"
        if ($layout) {
            $this->setUpLayout($layout);
        }

        return $this->render('index_received', [
            'dataProviderReceived' => $dataProviderReceived,
            'model' => $this->getModelSearch(),
            'currentView' => $this->getCurrentView(),
            'availableViews' => $this->getAvailableViews(),
            'url' => ($this->url) ? $this->url : NULL,
            'parametro' => ($this->parametro) ? $this->parametro : NULL
        ]);
    }

    /**
     * Lists all EenExprOfInterest models.
     * @return mixed
     */
    public function actionIndexAll($layout = NULL)
    {
        Url::remember();
        $this->setIndexParams();

        $this->setDataProvider($this->getModelSearch()->searchAll(Yii::$app->request->getQueryParams()));
        $this->setUpLayout('list');

        //se il layout di default non dovesse andar bene si può aggiuntere il layout desiderato
        //in questo modo nel controller "return parent::actionIndex($this->layout);"
        if ($layout) {
            $this->setUpLayout($layout);
        }

        return $this->render('index_received', [
            'dataProviderReceived' => $this->getDataProvider(),
            'model' => $this->getModelSearch(),
            'currentView' => $this->getCurrentView(),
            'availableViews' => $this->getAvailableViews(),
            'url' => ($this->url) ? $this->url : NULL,
            'parametro' => ($this->parametro) ? $this->parametro : NULL
        ]);
    }



    /**
     * Displays a single EenExprOfInterest model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        /** @var  $model EenExprOfInterest*/
        $model = $this->findModel($id);
        $old = clone $model;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->saveHistory($model, $old);

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('view', ['model' => $model]);
        }
    }

    /**
     * @param $idPartnershipProposal
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionCreate($idPartnershipProposal, $request_more_info = 0)
    {
        $this->layout = "@vendor/open20/amos-core/views/layouts/form";

        $modelEenPartenership  = EenPartnershipProposal::findOne($idPartnershipProposal);
        if(empty($modelEenPartenership)){
            throw new NotFoundHttpException();
        }
        if(!\open20\amos\een\models\EenExprOfInterest::canCreateExpressionOfInterest($idPartnershipProposal)){
            throw new ForbiddenHttpException(AmosEen::t('amoseen', 'Non è permessso effettuare questa manifestazione di interesse.'));
        }
        /** @var  $model EenExprOfInterest*/
        $model = new EenExprOfInterest();
        $userLogged = User::findOne(\Yii::$app->user->id);
        $model->een_partnership_proposal_id = $modelEenPartenership->id;
        $model->user_id = \Yii::$app->user->id;
        $model->is_request_more_info = $request_more_info;
        if($userLogged) {
            $model->email = $userLogged->email;
            $model->contact_person = $userLogged->userProfile->getNomeCognome();
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->organization_id = is_numeric($model->company_organization) ? $model->company_organization : null;
            $model->company_organization = $this->formatFieldCompanyOrganizaition($model->company_organization);
            if ($model->save()) {
                $this->saveHistory($model);
                $path = null;
                if($model->is_request_more_info == 0 && $model->een_network_node_id == 1 ) {
                    $path = "uploads/Expression_of_interest_een_" . $model->id . '_' . time() . ".pdf";
                    $this->savePdf($model->id, $path);
                }
                EenMailUtility::sendEmailExprOfInterest($model, $model->is_request_more_info, $path);
                if(!empty($path)) {
                    unlink($path);
                }
                if($model->is_request_more_info == 0) {
                    Yii::$app->getSession()->addFlash('success', AmosEen::t('amoseen', 'Expression of interest created correctly'));
                } else {
                    Yii::$app->getSession()->addFlash('success', AmosEen::t('amoseen', 'Information request created correctly'));
                }
                return $this->redirect('index');
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not created, check data'));
                return $this->render('create', [
                    'model' => $model,
                    'modelEenPartenership' => $modelEenPartenership
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'modelEenPartenership' => $modelEenPartenership
            ]);
        }
    }

    /**
     * Updates an existing EenExprOfInterest model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->layout = "@vendor/open20/amos-core/views/layouts/form";
        /** @var  $model EenExprOfInterest*/
        $model = $this->findModel($id);
        $old  = clone $model;
        $modelEenPartenership  = $model->eenPartnershipProposal;

//        if(\Yii::$app->user->can('STAFF_EEN')) {
//            $model->setScenario(EenExprOfInterest::SCENARIO_STAFF_EEN);
//        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->company_organization = $this->formatFieldCompanyOrganizaition($model->company_organization);
            $this->saveHistory($model, $old);
            if ($model->save()) {
                Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item updated'));
                return $this->redirect(['index']);
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not updated, check data'));
                return $this->render('update', [
                    'model' => $model,
                    'modelEenPartenership' => $modelEenPartenership
                ]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'modelEenPartenership' => $modelEenPartenership
            ]);
        }
    }

    /**
     * Deletes an existing EenExprOfInterest model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model) {
            if ($model->delete()) {
                Yii::$app->getSession()->addFlash('success', Yii::t('amoscore', 'Item deleted'));
            } else {
                Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not deleted because of dependency'));
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', Yii::t('amoscore', 'Item not found'));
        }
        return $this->redirect(['index']);
    }

    /**
     * @param $id
     */
    public function actionNotInterested($id){
        /** @var EenExprOfInterest $model */
        $model = $this->findModel($id);
        $old = clone $model;
        $model->status = EenExprOfInterest::EEN_EXPR_WORKFLOW_STATUS_CLOSED;
        $model->sub_status = EenExprOfInterest::EEN_SUB_STATUS_USER_NOT_INTERESTED;
//        if(!\Yii::$app->user->can('EENEXPROFINTEREST_UPDATE')){
//            throw new NotFoundHttpException();
//        }
        if($model->save(false)){
            $this->saveHistory($model, $old);
            \Yii::$app->session->addFlash('success', 'Passaggio di stato effettuato');
        }else {
            \Yii::$app->session->addFlash('success', 'Errore nel passaggio di stato');
        }
        return $this->redirect(['index']);
    }

    /**
     * @param $id
     */
    public function actionStaffEen(){
        $userStaffEenWithEOI = EenUtility::getUserStaffEenWithEOI();
        $this->setModelObj(new EenStaff());
        $userProfilesStaff = EenUtility::getProfilesStaffEen();
        $userProfileStaffIds = [];
        foreach ($userProfilesStaff as $profile){
            $userProfileStaffIds []= $profile->id;
        }

        // all user profiles except the user already selected
        $modelSearch = new UserProfileSearch();
        $dataProvider = $modelSearch->search(\Yii::$app->request->get());

        $userProfileStaffIds = $this->reAddDeletedElementToAllUser($userProfileStaffIds);
        $dataProvider = $this->searchAllUser($dataProvider, $userProfileStaffIds);


        // staff memebers
        $dataProviderProfilesStaffEen = new ArrayDataProvider([
            'allModels' => UserProfile::find()->andWhere(['id' => $userProfileStaffIds])->orderBy('cognome ASC')->all(),
            'key' => 'id'
        ]);

        //add to the dataprovider the user inserted with pjax
        $dataProviderProfilesStaffEen = $this->addNewStaffToDataProvider($dataProviderProfilesStaffEen);

        //set the current default een staff
        $currentStaffDefaultId = null;
        $currentStaffDefault = EenUtility::getStaffDefault();
        if($currentStaffDefault){
            $currentStaffDefaultId = $currentStaffDefault->id;
        }


        if(\Yii::$app->request->post() && !\Yii::$app->request->isPjax){
            $profileDeletedIds = Yii::$app->request->post('staffEenToDelete');
            $this->deleteStaffMmembers($profileDeletedIds);

            if(!empty(Yii::$app->request->post('EenStaff'))){
                foreach (Yii::$app->request->post('EenStaff') as $member){
                    $model = EenStaff::find()->andWhere(['user_id' => $member['user_id']])->one();
                    $sendEmailNewStaff = false;
                    if(empty($model)){
                        $model = new EenStaff();
                        $model->user_id = $member['user_id'];
                        $sendEmailNewStaff = true;
                    };
                    $model->een_network_node_id = $member['een_network_node_id'];
                    $model->staff_default = $member['staff_default'];
                    if($model->save()){
                        EenUtility::setPermissionStaff($model->user_id);
                        if($sendEmailNewStaff){
                            EenMailUtility::sendEmailNewStaffMember($model);
                        }
                        if($model->staff_default == 1) {
                            if ($model->id != $currentStaffDefaultId) {
                                EenMailUtility::sendEmailChangeStaffDefault($model);
                            }
                        }
                    }
                }
                \Yii::$app->session->addFlash('success', 'Elementi salvato con successo');
                return $this->redirect('staff-een');
            }
        }

        return $this->render('staff_een', [
            'dataProviderProfiles' => $dataProviderProfilesStaffEen,
            'dataProvider' => $dataProvider,
            'userStaffEenWithEOI' => $userStaffEenWithEOI,
        ]);
    }

    /**
     *
     */
    public function actionStaffByArea() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $network_node_id = $parents[0];
                $out = EenStaff::find()->select(new Expression('een_staff.id, CONCAT(user_profile.nome, " ", user_profile.cognome, "", if (organizations.id, CONCAT(" (", organizations.name, ")" ), "")) as name'))
                    ->andWhere(['een_network_node_id' => $network_node_id])
                    ->innerJoin('user_profile', 'user_profile.user_id = een_staff.user_id')
                    ->leftJoin('organizations', 'user_profile.prevalent_partnership_id = organizations.id')->asArray()->all();
                return Json::encode(['output'=> $out, 'selected'=>'']);
            }
        }
        return Json::encode(['output'=>'', 'selected'=>'']);
    }


    /**
     * @param $id
     * @return string
     */
    public function actionTransferExprOfInterest($id){
        $model = EenExprOfInterest::findOne($id);
        $old = clone $model;
        if(\Yii::$app->request->isPost){
            if($model->load(\Yii::$app->request->post()) && $model->save(false)){
                $this->saveHistory($model, $old);
                $path = "uploads/Expression_of_interest_een_" . $model->id . '_' . time() . ".pdf";
                $this->savePdf($model->id, $path);
                EenMailUtility::sendEmailTransferEoi($model, $old, $path);
                if(!empty($path)) {
                    unlink($path);
                }
                \Yii::$app->session->addFlash('success', 'Trasferimento effettuato');
                return($this->redirect(Url::previous()));
            }
            else {
//                pr($model->getErrors());
                \Yii::$app->session->addFlash('danger', 'Errore nel traferimento');
            }

        }
        return $this->renderAjax('_transfer_expr_of_int_modal', ['model' => $model]);
    }


    /**
     * @param $id
     * @param bool $save
     * @return mixed
     */
    public function actionPdf($id, $save = false) {
        // $this->layout = '@frontend/views/layouts/layout_pdf';
        $eenExpr = EenExprOfInterest::findOne(['id' => $id]);
        $profile = $eenExpr->user->userProfile;

        $content = $this->renderPartial('@vendor/open20/amos-proposte-collaborazione-een/src/views/een-expr-of-interest/_view_pdf', ['model' => $eenExpr, 'profile' => $profile]);
        $footer = $this->renderPartial('@vendor/open20/amos-proposte-collaborazione-een/src/views/een-expr-of-interest/_pdf_footer', ['model' => $eenExpr]);
//        $header = $this->renderPartial('@frontend/views/layouts/pdf_footer');
        $pdf = new Pdf([
            'mode' => Pdf::MODE_BLANK,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'content' => $content,
            'cssInline' => '',
            'options' => ['title' => ''],
            'methods' => [
                'SetFooter' => ['{PAGENO}']
            ] ,
        ]);

//        $pdf->getApi()->SetHTMLHeader($header);
        $pdf->getApi()->SetHTMLFooter($footer);

        $pdf->getApi()->SetMargins(0, 0, 20);
//        $pdf->getApi()->SetAutoPageBreak(TRUE, 25);
        $pdf->getApi()->margin_header = '6px';
        $pdf->getApi()->margin_footer = '10px';
        if($save){
            return $pdf->output($content, \Yii::$app->basePath."/runtime/mpdf/tmp/Expression_of_interest_een.pdf", Pdf::DEST_FILE);
        }
        else {
            return $pdf->output($content, "Expression_of_interest_een.pdf", Pdf::DEST_DOWNLOAD);
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function savePdf($id, $path){
        $eenExpr = EenExprOfInterest::findOne(['id' => $id]);
        $profile = $eenExpr->user->userProfile;
        $content = $this->renderPartial('@vendor/open20/amos-proposte-collaborazione-een/src/views/een-expr-of-interest/_view_pdf', ['model' => $eenExpr, 'profile' => $profile]);
        $footer = $this->renderPartial('@vendor/open20/amos-proposte-collaborazione-een/src/views/een-expr-of-interest/_pdf_footer', ['model' => $eenExpr]);


        $pdf = new Pdf([
            'mode' => Pdf::MODE_BLANK,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'content' => $content,
            'cssInline' => '',
            'options' => ['title' => ''],
            'methods' => [
                'SetFooter' => ['{PAGENO}']
            ] ,
        ]);

        $pdf->getApi()->SetHTMLFooter($footer);
        $pdf->getApi()->SetMargins(0, 0, 20);
//        $pdf->getApi()->SetAutoPageBreak(TRUE, 25);
        $pdf->getApi()->margin_header = '6px';
        $pdf->getApi()->margin_footer = '10px';

        return $pdf->output($content, $path, Pdf::DEST_FILE);
    }


    /**
     * @param $user_id
     */
    public function actionDisassociateStaffEen($user_id){
        $user = User::findOne($user_id);
        $staff = EenStaff::find()->andWhere(['user_id' => $user_id])->one();
        if($staff) {
            $exprOfInt = EenExprOfInterest::find()->andWhere(['een_staff_id' => $staff->id])->all();
            foreach ($exprOfInt as $expr){
                $expr->een_staff_id = null;
                $expr->save();
            }
            $staff->delete();
        }
        if($user) {
            if (\Yii::$app->authManager->checkAccess($user_id, 'STAFF_EEN')) {
                $role = \Yii::$app->authManager->getRole('STAFF_EEN');
                \Yii::$app->authManager->revoke($role, $user_id);
            }
        }
        $this->redirect('staff-een');
    }



    /**
     * This method is useful to set all common params for all list views.
     */
    protected function setIndexParams()
    {
        \Yii::$app->view->params['createNewBtnParams'] =  ['layout' => ''];
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();
        $this->child_of = WidgetIconEenExprOfInterestDashboard::className();

    }

    /**
     * @param $model EenExprOfInterest
     * @param null $old EenExprOfInterest
     * @return bool
     */
    protected function saveHistory($model, $old = null){
        $modelstaffEen = $model->eenStaff;
        if($old) {
            $oldstaffEen = $old->eenStaff;
        }

        if(empty($old)){
           $history = new EenExprOfInterestHistory();
            $history->een_expr_of_interest_id = $model->id;
            $history->end_status = $model->status;
            if($model->eenStaff){
                $history->start_in_charge = $model->eenStaff->user_id;
            }
           $history->save();
           return true;
        } else {
            if($model->status != $old->status || $model->sub_status != $old->sub_status || $model->een_staff_id != $old->een_staff_id) {
                $history = new EenExprOfInterestHistory();
                $history->een_expr_of_interest_id = $old->id;
                $history->start_status = $old->status;
                $history->end_status = $model->status;
                $history->start_sub_status = $old->sub_status;
                $history->end_sub_status = $model->sub_status;
                if($modelstaffEen){
                    $history->end_in_charge = $modelstaffEen->user_id;
                }
                if($oldstaffEen){
                    $history->start_in_charge = $oldstaffEen->user_id;
                }
                $history->save();
                return true;
            }
            if($modelstaffEen)
            return true;
        }
    }

    /**
     * @param $id
     * @return array
     */
    public function actionGetOrganizationSelectedAjax($id){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $data = [];
        $model = Organizations::findOne($id);
        $place = $model->operationalHeadquartersPlace;
        if($place){
            $data['address'] = $place->address;
            $data['city'] = $place->city;
            $data['postal_code'] = $place->postal_code;
        }
        $data['web_site'] = $model->web_site;
        return $data;
    }

    /**
     * The field $company_organization could be the id of an organization or the name o the organization, this funcition return always the name
     * @param $company_organization
     * @return string
     */
    public function formatFieldCompanyOrganizaition($company_organization){
        if(is_numeric($company_organization)){
            $org = Organizations::findOne($company_organization);
            if($org){
                $company_organization = $org->name;
            }

        }
        return $company_organization;
    }

    /**
     * @param $dataProviderProfilesStaffEen
     * @return ArrayDataProvider
     */
    public function addNewStaffToDataProvider($dataProviderProfilesStaffEen){
        if(\Yii::$app->request->isPjax){
            $models = $dataProviderProfilesStaffEen->models;
            $profileSelectedIds = Yii::$app->request->post('new_records');

            if(!empty($profileSelectedIds) || !empty($profileDeletedIds)) {
                if(!empty($profileSelectedIds)) {
                    foreach ($profileSelectedIds as $userProfileid) {
                        $profile = UserProfile::findOne($userProfileid);
                        if ($profile) {
                            $models [] = $profile;
                        }
                    }
                }
                array_unique($models);
                $dataProviderProfilesStaffEen = new ArrayDataProvider([
                    'allModels' => $models,
                    'key' => 'id'
                ]);
            }
        }
        return $dataProviderProfilesStaffEen;
    }

    /**
     * @param $userProfileStaffIds
     * @return mixed
     */
    public function reAddDeletedElementToAllUser($userProfileStaffIds){
        $profileDeletedIds = Yii::$app->request->post('deleted_records');

        if(!empty($profileDeletedIds) && !empty($userProfileStaffIds)) {
            foreach ($userProfileStaffIds as $key => $userProfileStaffid) {
                if(in_array($userProfileStaffid, $profileDeletedIds)){
                    unset($userProfileStaffIds[$key]);
                }
            }
            array_values($userProfileStaffIds);
        }

        return $userProfileStaffIds;
    }

    /**
     * @param $profileDeletedIds
     */
    public function deleteStaffMmembers($profileDeletedIds){
        if(!empty($profileDeletedIds)){
            foreach ($profileDeletedIds as $idToDeleteProfile){
                $model = EenStaff::find()
                    ->innerJoin('user_profile','een_staff.user_id=user_profile.user_id')
                    ->andWhere(['user_profile.id' => $idToDeleteProfile])->one();
                if($model) {
                    EenUtility::deletePermissionStaff($model->user_id);
                    $model->delete();
                }
            }
        }
    }

    /**
     * @param $dataProvider
     * @param $userProfileStaffIds
     * @return mixed
     */
    public function searchAllUser($dataProvider, $userProfileStaffIds){
        $profileNewIds = Yii::$app->request->post('new_records');
        $dataProvider->query
            ->innerJoinWith('user')
            ->andWhere(['NOT IN', 'user_profile.id', $userProfileStaffIds])
            ->andFilterWhere(['NOT IN', 'user_profile.id', $profileNewIds]);

        if(\Yii::$app->request->post('searchName')){
            $searchName = \Yii::$app->request->post('searchName');
            $dataProvider->query->andWhere(['or',
                ['like', 'cognome', $searchName],
                ['like', 'nome', $searchName],
                ['like', "CONCAT( nome , ' ', cognome )", $searchName],
                ['like', "CONCAT( cognome , ' ', nome )", $searchName],
            ]);
        }
        return $dataProvider;
    }


    /**
     * @param $id exp of interest
     */
    public function actionTakeOver($id){
        /** @var  $eoi EenExprOfInterest */
        $eoi = EenExprOfInterest::findOne($id);
        if($eoi){
            $userId = Yii::$app->user->id;
            $staff = EenStaff::findOne(['user_id' => $userId]);
            if($staff) {
                $eoi->status = EenExprOfInterest::EEN_EXPR_WORKFLOW_STATUS_TAKENOVER;
                $eoi->een_staff_id = $staff->id;
                $eoi->save(false);
                \Yii::$app->session->addFlash('success', 'Manifestazione presa in carico');
            }
        }
        return $this->redirect(Url::previous());
    }
}
