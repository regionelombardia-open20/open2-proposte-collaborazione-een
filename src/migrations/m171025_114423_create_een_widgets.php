<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\een\migrations
 * @category   CategoryName
 */

use open20\amos\dashboard\models\AmosWidgets;

/**
 * Class m171025_114423_create_een_widgets
 */
class m171025_114423_create_een_widgets extends \open20\amos\core\migration\AmosMigrationWidgets
{
    const MODULE_NAME = 'een';
    
    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\een\widgets\icons\WidgetIconEenDashboard::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => null,
                'dashboard_visible' => 1,
                'default_order' => 100
            ],
            [
                'classname' => \open20\amos\een\widgets\icons\WidgetIconEen::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\een\widgets\icons\WidgetIconEenDashboard::className(),
                'dashboard_visible' => 0,
                'default_order' => 10
            ],
            [
                'classname' => \open20\amos\een\widgets\icons\WidgetIconEenAll::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\een\widgets\icons\WidgetIconEenDashboard::className(),
                'dashboard_visible' => 0,
                'default_order' => 20
            ]
        ];
    }
}
