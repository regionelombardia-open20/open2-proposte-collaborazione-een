<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news
 * @category   CategoryName
 */

namespace open20\amos\een\rules;

use open20\amos\core\rules\DefaultOwnContentRule;
use open20\amos\news\models\News;
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
