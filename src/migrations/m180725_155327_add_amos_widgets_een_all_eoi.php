<?php
use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;


/**
* Class m180327_162827_add_amos_widgets_een_archived*/
class m180725_155327_add_amos_widgets_een_all_eoi extends AmosMigrationWidgets
{
    const MODULE_NAME = 'een';

    /**
    * @inheritdoc
    */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\een\widgets\icons\WidgetIconEenExprOfInterestAll::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 0,
                'default_order' => 30,
                'child_of' => \open20\amos\een\widgets\icons\WidgetIconEenExprOfInterestDashboard::className(),
            ]
        ];
    }
}
