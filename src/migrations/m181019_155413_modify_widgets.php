<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m181019_155413_modify_widgets extends Migration
{
    const TABLE = "amos_widgets";
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->update(self::TABLE, ['child_of' => 'lispa\amos\een\widgets\icons\WidgetIconEenDashboardGeneral'], ['classname' => 'lispa\amos\een\widgets\icons\WidgetIconEenDashboard', 'module' => 'een']);
        $this->update(self::TABLE, ['child_of' => 'lispa\amos\een\widgets\icons\WidgetIconEenDashboardGeneral'], ['classname' => 'lispa\amos\een\widgets\icons\WidgetIconEenExprOfInterestDashboard', 'module' => 'een']);
        $this->update(self::TABLE, ['child_of' => 'lispa\amos\een\widgets\icons\WidgetIconEenDashboardGeneral'], ['classname' => 'lispa\amos\een\widgets\icons\WidgetIconEenStaff', 'module' => 'een']);

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->update(self::TABLE, ['child_of' => null], ['classname' => 'lispa\amos\een\widgets\icons\WidgetIconEenDashboard', 'module' => 'een']);
        $this->update(self::TABLE, ['child_of' => null], ['classname' => 'lispa\amos\een\widgets\icons\WidgetIconEenExprOfInterestDashboard', 'module' => 'een']);
        $this->update(self::TABLE, ['child_of' => null], ['classname' => 'lispa\amos\een\widgets\icons\WidgetIconEenStaff', 'module' => 'een']);

    }
}
