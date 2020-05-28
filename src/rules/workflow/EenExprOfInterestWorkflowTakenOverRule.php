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

use open20\amos\een\models\EenStaff;
use Yii;

class EenExprOfInterestWorkflowTakenOverRule extends BasicContentRule
{

    public $name = 'eenExprOfInterestWorkflowTakenOver';

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
            if($model->isLoggedUserInCharge()) {
                return true;
            }
        }else {
            // se non è stata assegnata a nessuno tutti quello dello staff een possono prendere  in carico
            $staff = EenStaff::findOne(['user_id' => \Yii::$app->user->id ]);
            if(!empty($staff)){
                return true;
            }
        }
        return false;
    }

}