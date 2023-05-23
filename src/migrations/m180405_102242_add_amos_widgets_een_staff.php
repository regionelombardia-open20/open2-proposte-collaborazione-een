<?php
use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;


/**
* Class m180405_102242_add_amos_widgets_een_staff*/
class m180405_102242_add_amos_widgets_een_staff extends AmosMigrationWidgets
{
    const MODULE_NAME = 'een';

    /**
    * @inheritdoc
    */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\een\widgets\icons\WidgetIconEenStaff::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 1,
                
            ]
        ];
    }
}
