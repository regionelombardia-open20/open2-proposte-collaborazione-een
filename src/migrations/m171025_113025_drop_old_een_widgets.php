<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\news
 * @category   CategoryName
 */

use yii\db\Migration;

class m171025_113025_drop_old_een_widgets extends Migration
{


    public function safeUp()
    {

        \open20\amos\dashboard\models\AmosUserDashboardsWidgetMm::deleteAll([
            'like',
            'amos_widgets_classname',
            'proposte_collaborazione_een',
        ]);

        \open20\amos\dashboard\models\AmosWidgets::deleteAll([
            'like',
            'classname',
            'proposte_collaborazione_een',
        ]);

        return true;
    }

    public function safeDown()
    {
        return true;
    }


}
