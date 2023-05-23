<?php

namespace open20\amos\een;

use open20\amos\core\interfaces\CmsModuleInterface;
use open20\amos\core\interfaces\SearchModuleInterface;
use open20\amos\core\module\AmosModule;
use open20\amos\core\module\ModuleInterface;
use yii\console\Application;
use yii\helpers\FileHelper;
use open20\amos\core\interfaces\BreadcrumbInterface;



/**
 * Class AmosEen
 * @package open20\amos\een
 */
class AmosEen extends AmosModule implements ModuleInterface, SearchModuleInterface, CmsModuleInterface, BreadcrumbInterface
{

    const MAX_LAST_PARTNERSHIP_ON_DASHBOARD = 3;
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'open20\amos\een\controllers';
    public $name = 'een';
    public $wsdl = null;//'http://een.ec.europa.eu/tools/services/podv6/QueryService.svc?wsdl';
    public $findAllAccessPoint = null;//'GetProfilesSOAP';
    public $findAllAccessPointRequest = null;
    public $mailToSendInterest = null;

    //inserisci un form nella modale _modal_expr_of_interest
    // $expofintintoform['emailtosend'];
    public $expofintintoform = null;
    /**
     * @var array
     */
    public $viewPathEmailContentSubtitle = [
        'open20\amos\een\models\EenPartnershipProposal' => '@vendor/open20/amos-proposte-collaborazione-een/src/views/email/notify_email_content'
    ];

    /**
     * @var array
     */
    public $viewPathEmailSummary = [
        'open20\amos\een\models\EenPartnershipProposal' => '@vendor/open20/amos-proposte-collaborazione-een/src/views/email/notify_summary'
    ];

    public $viewPathEmailSummaryNetwork = [];
    /**
     * @return string
     */
    public static function getModuleName()
    {
        return 'een';
    }
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        \Yii::setAlias('@open20/amos/' . static::getModuleName() . '/commands/controllers', __DIR__ . '/commands/controllers');
        // custom initialization code goes here
        \Yii::configure($this, require(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php'));
        
        if (\Yii::$app instanceof Application) {
            $this->controllerNamespace = 'open20\amos\een\commands\controllers';
            if (!defined('LOG_DIR')) {
                define('LOG_DIR', \Yii::getAlias("@runtime") . DIRECTORY_SEPARATOR . "een" . DIRECTORY_SEPARATOR . "calls" . DIRECTORY_SEPARATOR );
            }
            FileHelper::createDirectory(LOG_DIR, 0777);
        }
    }
    
    /**
     * @inheritdoc
     */
    public function getWidgetGraphics()
    {
        return null;
    }
    
    /**
     * @inheritdoc
     */
    public function getWidgetIcons()
    {
        return [
            \open20\amos\een\widgets\icons\WidgetIconEenDashboard::className(),
            \open20\amos\een\widgets\icons\WidgetIconEen::className(),
            \open20\amos\een\widgets\icons\WidgetIconEenAll::className(),
            \open20\amos\een\widgets\icons\WidgetIconEenArchived::className()

        ];
    }
    
    /**
     * @inheritdoc
     */
    protected function getDefaultModels()
    {
        return [];
    }


    /**
     * @return string
     */
    public static function getModelSearchClassName()
    {
        return __NAMESPACE__ . '\models\search\EenPartnershipProposalSearch';
    }

    /**
     * @return string
     */
    public static function getModuleIconName()
    {
            return 'proposte-een';

    }

    /**
     */
    public static function getModelClassName()
    {
        return __NAMESPACE__ . '\models\EenPartnershipProposal';
    }

    /**
     * @return array
     */
    public function getIndexActions(){
        return [
            'een-partnership-proposal/index',
            'een-partnership-proposal/own-interest',
            'een-partnership-proposal/archived',

            'een-expr-of-interest/index',
            'een-expr-of-interest/index-all',
            'een-expr-of-interest/index-own',
            'een-expr-of-interest/index-received',
            'een-expr-of-interest/index-own',
            'een-expr-of-interest/staff-een',

        ];
    }

    /**
     * @return array
     */
    public function defaultControllerIndexRoute()
    {
        return [
            'een-partnership-proposal' => '/een/een-partnership-proposal/own-interest',

        ];
    }

    /**
     * @return array
     */
    public function defaultControllerIndexRouteSlogged()
    {
        return [
            'een-partnership-proposal' => '/een/een-partnership-proposal/index',
        ];
    }


    /**
     * @return array
     */
    public function getControllerNames(){
        $names =  [
            'een-partnership-proposal' => self::t('amoseen', 'Proposte di collaborazione dal mondo'),
            'een-expr-of-interest'  => self::t('amoseen', 'Manifestazione di interesse dal mondo'),
        ];

        return $names;
    }
}
