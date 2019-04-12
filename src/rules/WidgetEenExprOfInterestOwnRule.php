<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\news
 * @category   CategoryName
 */

namespace lispa\amos\een\rules;

use lispa\amos\core\rules\DefaultOwnContentRule;
use lispa\amos\news\models\News;
use Yii;

class WidgetEenExprOfInterestOwnRule extends DefaultOwnContentRule
{
    public $name = 'widgetEenExprOfInterestOwn';

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        $controllerId = \Yii::$app->controller->id;
//        if($controllerId != 'dashboard'){
            if(\Yii::$app->user->can('STAFF_EEN')){
                return false;
            }
            else {
                return true;
            }
//        }

//      return true;
    }

}
