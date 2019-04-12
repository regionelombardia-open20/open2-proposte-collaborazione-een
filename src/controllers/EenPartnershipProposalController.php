<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\een\controllers
 * @category   CategoryName
 */

namespace lispa\amos\een\controllers;

use lispa\amos\core\controllers\CrudController;
use lispa\amos\core\helpers\Html;
use lispa\amos\core\icons\AmosIcons;
use lispa\amos\core\utilities\Email;
use lispa\amos\cwh\AmosCwh;
use lispa\amos\dashboard\controllers\TabDashboardControllerTrait;
use lispa\amos\een\AmosEen;
use lispa\amos\een\assets\ProposteCollaborazioneEenAsset;
use lispa\amos\een\models\EenPartnershipProposal;
use lispa\amos\een\models\InfoReqModel;
use lispa\amos\een\models\search\EenPartnershipProposalSearch;
use lispa\amos\een\widgets\icons\WidgetIconEenDashboard;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Class EenPartnershipProposalController
 * @package lispa\amos\een\controllers
 */
class EenPartnershipProposalController extends CrudController
{
    /**
     * Trait used for initialize the news dashboard
     */
    use TabDashboardControllerTrait;

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
                            'own-interest',
                            'send-interest',
                            'archived'
                        ],
                        'roles' => ['EEN_READER']
                    ],
                ],

            ],
        ]);
        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->initDashboardTrait();

        $this->setModelObj(new EenPartnershipProposal());
        $this->setModelSearch(new EenPartnershipProposalSearch());

        ProposteCollaborazioneEenAsset::register(Yii::$app->view);

        $this->setAvailableViews([
            'list' => [
                'name' => 'list',
                'label' => AmosEen::t('amoseen',
                    '{iconaLista}' . Html::tag('p', AmosEen::tHtml('amoseen', 'Lista')), [
                        'iconaLista' => AmosIcons::show('view-list')
                    ]),
                'url' => '?currentView=list'
            ],
            'grid' => [
                'name' => 'grid',
                'label' => AmosEen::t('amoseen',
                    '{iconaTabella}' . Html::tag('p', AmosEen::tHtml('amoseen', 'Tabella')), [
                        'iconaTabella' => AmosIcons::show('view-list-alt')
                    ]),
                'url' => '?currentView=grid'
            ],
            /*'icon' => [
                'name' => 'icon',
                'label' => T::tApp('{iconaElenco}'.Html::tag('p','Icone'), [
                    'iconaElenco' => AmosIcons::show('grid')
                ]),
                'url' => '?currentView=icon'
            ],
            'map' => [
                'name' => 'map',
                'label' => T::tApp('{iconaMappa}'.Html::tag('p','Mappa'), [
                    'iconaMappa' => AmosIcons::show('map')
                ]),
                'url' => '?currentView=map'
            ],
            'calendar' => [
                'name' => 'calendar',
                'intestazione' => '', //codice HTML per l'intestazione che verrà caricato prima del calendario,
                                      //per esempio si può inserire una funzione $model->getHtmlIntestazione() creata ad hoc
                'label' => T::tApp('{iconaCalendario}'.Html::tag('p','Calendario'), [
                    'iconaMappa' => AmosIcons::show('calendar')
                ]),
                'url' => '?currentView=calendar'
            ],*/
        ]);

        parent::init();
    }

    /**
     * Lists all ProposteDiCollaborazioneEen models.
     * @return mixed
     */
    public function actionIndex($layout = null)
    {
        Url::remember();
        $this->layout = "@vendor/lispa/amos-core/views/layouts/list";
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        $this->setTitleAndBreadcrumbs(AmosEen::t('amoseen', 'Tutte le Proposte een'));

        $this->setIndexParams();

        $this->setDataProvider($this->getModelSearch()->searchAll(\Yii::$app->request->getQueryParams()));
        return parent::actionIndex();
    }

    /**
     * Displays a single ProposteDiCollaborazioneEen model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', ['model' => $this->findModel($id)]);
    }

    /**
     * Action for search all news.
     *
     * @return string
     */
    public function actionOwnInterest($currentView = null)
    {
        Url::remember();

        if (empty($currentView)) {
            $currentView = 'list';
        }
        $this->setDataProvider($this->getModelSearch()->searchOwnInterest(\Yii::$app->request->getQueryParams()));

        $this->setIndexParams();

        $this->setTitleAndBreadcrumbs(AmosEen::t('amoseen', 'Proposte een di mio interesse index'));

        $this->setCurrentView($this->getAvailableView($currentView));

        $this->layout = "@vendor/lispa/amos-core/views/layouts/list";
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        return $this->render('index', [
            'dataProvider' => $this->getDataProvider(),
            'model' => $this->getModelSearch(),
            'currentView' => $this->getCurrentView(),
            'availableViews' => $this->getAvailableViews(),
            'url' => ($this->url) ? $this->url : null,
            'parametro' => ($this->parametro) ? $this->parametro : null
        ]);
    }

    /**
     * Action for search archived proposal.
     *
     * @return string
     */
    public function actionArchived($currentView = null)
    {
        Url::remember();

        if (empty($currentView)) {
            $currentView = 'list';
        }
        $this->setDataProvider($this->getModelSearch()->searchArchived(\Yii::$app->request->getQueryParams()));

        $this->setIndexParams();

        $this->setTitleAndBreadcrumbs(AmosEen::t('amoseen', 'Proposte een archiviate'));

        $this->setCurrentView($this->getAvailableView($currentView));

        $this->layout = "@vendor/lispa/amos-core/views/layouts/list";
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        return $this->render('index', [
            'dataProvider' => $this->getDataProvider(),
            'model' => $this->getModelSearch(),
            'currentView' => $this->getCurrentView(),
            'availableViews' => $this->getAvailableViews(),
            'url' => ($this->url) ? $this->url : null,
            'parametro' => ($this->parametro) ? $this->parametro : null
        ]);
    }

    /**
     * Used for set page title and breadcrumbs.
     * @param string $eenPageTitle News page title (ie. Created by news, ...)
     */
    private function setTitleAndBreadcrumbs($eenPageTitle)
    {
        $this->setNetworkDashboardBreadcrumb();
        \Yii::$app->session->set('previousTitle', $eenPageTitle);
        \Yii::$app->session->set('previousUrl', Url::previous());
        \Yii::$app->view->title = $eenPageTitle;
        \Yii::$app->view->params['breadcrumbs'][] = ['label' => $eenPageTitle];
    }


    public function setNetworkDashboardBreadcrumb()
    {
        /** @var AmosCwh $moduleCwh */
        $moduleCwh = \Yii::$app->getModule('cwh');
        $scope = null;
        if (!empty($moduleCwh)) {
            $scope = $moduleCwh->getCwhScope();
        }
        if (!empty($scope)) {
            if (isset($scope['community'])) {
                $communityId = $scope['community'];
                $community = \lispa\amos\community\models\Community::findOne($communityId);
                $dashboardCommunityTitle = AmosEen::t('amosnews', "Dashboard") . ' ' . $community->name;
                $dasbboardCommunityUrl = \Yii::$app->urlManager->createUrl(['community/join', 'id' => $communityId]);
                \Yii::$app->view->params['breadcrumbs'][] = [
                    'label' => $dashboardCommunityTitle,
                    'url' => $dasbboardCommunityUrl
                ];
            }
        }
    }


    /**
     * @param null $currentView
     * @return string
     */
    public function actionSendInterest($currentView = null)
    {

        $post = Yii::$app->request->post();
        if ($post) {
            $model = new InfoReqModel($post['InfoReqModel']);
            $een = EenPartnershipProposal::find()->andWhere(['id' => $model->een])->one();
            $from = '';
            if (isset(Yii::$app->params['email-assistenza'])) {
                //use default platform email assistance
                $from = Yii::$app->params['email-assistenza'];
            }
            $bcc = [Yii::$app->user->getIdentity()->email];
            $module = AmosEen::instance();
            $to = [$module->mailToSendInterest];
            $content = $this->renderPartial("/email/inforeq", ['model' => $een, 'inforeq' => $model]);
            Email::sendMail(
                $from,
                $to,
                AmosEen::t('amoseen', '#mailsubject') . ' ' . $een->reference_external,
                $content, [], $bcc
            );
        }

        return $this->render('sendemail');
    }

    /**
     * This method is useful to set all common params for all list views.
     */
    protected function setIndexParams()
    {
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();
        $this->child_of = WidgetIconEenDashboard::className();

    }

}