<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    Open20Package
 * @category   CategoryName
 */

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m190808_153213_alter_table_een_proposal_popolate_notify_send_email extends Migration
{
    const TABLE = "notification_send_email";
    /**
     * @inheritdoc
     */
    public function up()
    {
       $p = new \open20\amos\een\models\base\EenPartnershipProposal();
       $classname = addslashes($p->classname());
       $eenProposal =  \open20\amos\een\models\EenPartnershipProposal::find()
            ->leftJoin('notification_send_email', "notification_send_email.classname = '$classname'")
            ->andWhere(['IS', 'notification_send_email.id', null])
			->andWhere(['>=', 'een_partnership_proposal.created_at', '2019-03-06'])
            ->all();
       foreach ($eenProposal as $proposal){
           echo $proposal->id ." , ";
           $proposal->saveNotificationSendEmail($proposal->classname(), \open20\amos\notificationmanager\models\NotificationChannels::CHANNEL_MAIL, $proposal->id, true);
       }
        return true;

    }

    /**
     * @inheritdoc
     */
    public function down()
    {

        return true;

    }
}
