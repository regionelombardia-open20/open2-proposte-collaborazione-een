widget<?php
use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;


/**
* Class m180330_120718_add_amos_widgets_een_expr_of_interest*/
class m180330_120718_add_amos_widgets_een_expr_of_interest extends AmosMigrationWidgets
{
    const MODULE_NAME = 'een';

    /**
    * @inheritdoc
    */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' =>\open20\amos\een\widgets\icons\WidgetIconEenExprOfInterestDashboard::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 1,
                
            ]
        ];
    }
}
