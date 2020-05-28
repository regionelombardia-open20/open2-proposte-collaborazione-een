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

class UpdateOwnEenExprOfInterestRule extends DefaultOwnContentRule
{
    public $name = 'UpdateOwnEenExprOfInterest';

    public function execute($user, $item, $params)
    {
        return false;
//        if (isset($params['model'])) {
//            /** @var Record $model */
//            $model = $params['model'];
//            if (!$model->id) {
//                $post = \Yii::$app->getRequest()->post();
//                $get = \Yii::$app->getRequest()->get();
//                if (isset($get['id'])) {
//                    $model = $this->instanceModel($model, $get['id']);
//                } elseif (isset($post['id'])) {
//                    $model = $this->instanceModel($model, $post['id']);
//                }
//            }
//            return ($model->created_by == $user);
//        } else {
//            return false;
//        }
    }
}
