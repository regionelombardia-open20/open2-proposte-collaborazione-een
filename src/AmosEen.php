<?php

namespace lispa\amos\een;

use lispa\amos\core\module\AmosModule;
use lispa\amos\core\module\ModuleInterface;
use yii\console\Application;
use yii\helpers\FileHelper;


/**
 * Class AmosEen
 * @package lispa\amos\een
 */
class AmosEen extends AmosModule implements ModuleInterface
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'lispa\amos\een\controllers';
    public $name = 'een';
    public $wsdl = null;//'http://een.ec.europa.eu/tools/services/podv6/QueryService.svc?wsdl';
    public $findAllAccessPoint = null;//'GetProfilesSOAP';
    public $findAllAccessPointRequest = null;
    public $mailToSendInterest = null;
    
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
        
        \Yii::setAlias('@lispa/amos/' . static::getModuleName() . '/commands/controllers', __DIR__ . '/commands/controllers');
        // custom initialization code goes here
        \Yii::configure($this, require(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php'));
        
        if (\Yii::$app instanceof Application) {
            $this->controllerNamespace = 'lispa\amos\een\commands\controllers';
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
            \lispa\amos\een\widgets\icons\WidgetIconEenDashboard::className(),
            \lispa\amos\een\widgets\icons\WidgetIconEen::className(),
            \lispa\amos\een\widgets\icons\WidgetIconEenAll::className(),
            \lispa\amos\een\widgets\icons\WidgetIconEenArchived::className()

        ];
    }
    
    /**
     * @inheritdoc
     */
    protected function getDefaultModels()
    {
        return [];
    }
}
