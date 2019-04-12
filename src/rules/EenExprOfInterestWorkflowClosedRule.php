<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\community\rules
 * @category   CategoryName
 */

namespace lispa\amos\een\rules;


use lispa\amos\core\rules\BasicContentRule;
use yii\helpers\Url;

/**
 * Class CreateSubcommunitiesRule
 * @package lispa\amos\community\rules
 */
class EenExprOfInterestWorkflowClosedRule extends BasicContentRule
{
    /**
     * @inheritdoc
     */
    public $name = 'eenExprOfInterestWorkflowClosed';

    /**
     * @inheritdoc
     */
    public function ruleLogic($user, $item, $params, $model)
    {
        if(\Yii::$app->user->can('STAFF_EEN')){
            $currentUrl = explode("?", Url::current());
            if($currentUrl[0] == '/een/een-expr-of-interest/not-interested') {
                return true;
            }
            //se la manifestazione è in carico a qualcouno può modificare lo stoto solo chi l'ha incarico
            if($model->eenStaff){
                return $model->isLoggedUserInCharge();
            }
        }
        else {
            $currentUrl = explode("?", Url::current());
            if($currentUrl[0] == '/een/een-expr-of-interest/not-interested') {
                return true;
            }
            if($currentUrl[0]=='/een/een-expr-of-interest' || $currentUrl[0]=='/een/een-expr-of-interest/index'){
                return true;
            }
            else {
                return false;
            }
        }
        return false;
    }

}
