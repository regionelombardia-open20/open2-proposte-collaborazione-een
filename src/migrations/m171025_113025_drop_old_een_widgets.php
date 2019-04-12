<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\news
 * @category   CategoryName
 */

use yii\db\Migration;

class m171025_113025_drop_old_een_widgets extends Migration
{


    public function safeUp()
    {

        \lispa\amos\dashboard\models\AmosUserDashboardsWidgetMm::deleteAll([
            'like',
            'amos_widgets_classname',
            'proposte_collaborazione_een',
        ]);

        \lispa\amos\dashboard\models\AmosWidgets::deleteAll([
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
