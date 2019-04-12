<?php
namespace lispa\amos\een\events;


use lispa\amos\admin\models\UserProfile;
use lispa\amos\een\models\EenExprOfInterest;
use lispa\amos\een\utility\EenMailUtility;
use lispa\amos\een\utility\EenUtility;
use Yii;
use yii\base\Event;
use yii\base\Exception;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * Class CorsiRichiesteRettificaWorkflowEvent
 * @package backend\modules\corsi\events\worflow
 */
class EenExprOfInterestWorkflowEvent
{

    /**
     * @param Event $event
     */
    public function afterEnterStatus(Event $event)
    {
        /** @var  $exprOfInterest EenExprOfInterest*/
        $exprOfInterest = $event->data;
        if($exprOfInterest->status != EenExprOfInterest::EEN_EXPR_WORKFLOW_STATUS_CLOSED) {
            $exprOfInterest->sub_status = null;
        }
        $exprOfInterest->save(false);

        $status = $exprOfInterest->status;
        if($status == EenExprOfInterest::EEN_EXPR_WORKFLOW_STATUS_TAKENOVER){
            EenMailUtility::sendEmailWorkflowTakeOver($exprOfInterest);

        }else if($status == EenExprOfInterest::EEN_EXPR_WORKFLOW_STATUS_CLOSED){
            if($exprOfInterest->sub_status == EenExprOfInterest::EEN_SUB_STATUS_USER_NOT_INTERESTED){
                EenMailUtility::sendEmailNotInterested($exprOfInterest);
            }else {
                EenMailUtility::sendEmailWorkflowClosed($exprOfInterest);
            }
        }
        else if($status == EenExprOfInterest::EEN_EXPR_WORKFLOW_STATUS_SUSPENDED){
            // at the creation of the eoi don't send the notification for the passing of status
            if($exprOfInterest->getEenExprOfInterestHistory()->count() > 0) {
                EenMailUtility::sendEmailWorkflowSuspended($exprOfInterest);
            }
        }

    }





}