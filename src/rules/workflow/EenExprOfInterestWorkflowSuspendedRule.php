<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\projectmanagement\rules\workflow
 * @category   CategoryName
 */

namespace open20\amos\een\rules\workflow;


use open20\amos\core\rules\BasicContentRule;
use open20\amos\een\models\EenExprOfInterest;
use yii\rbac\Rule;
use Yii;

class EenExprOfInterestWorkflowSuspendedRule extends BasicContentRule
{

    public $name = 'eenExprOfInterestWorkflowSuspended';

    /**
     * @param int|string $user
     * @param \yii\rbac\Item $item
     * @param array $params
     * @param EenExprOfInterest $model
     * @return bool
     * @throws \open20\amos\community\exceptions\CommunityException
     */
    public function ruleLogic($user, $item, $params, $model)
    {
        //se la manifestazione è in carico a qualcouno può modificare lo stoto solo chi l'ha incarico
        if($model->eenStaff){
            return $model->isLoggedUserInCharge();
        }

        return false;
    }

}