<?php
use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;


/**
* Class m180327_162827_add_amos_widgets_een_archived*/
class m191023_125327_add_widget_graphic_latest extends AmosMigrationWidgets
{
    const MODULE_NAME = 'een';

    /**
    * @inheritdoc
    */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\een\widgets\graphics\WidgetGraphicsLatestPartnershipProposalEen::className(),
                'type' => AmosWidgets::TYPE_GRAPHIC,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 1,
                'default_order' => 1,
            ]
        ];
    }
}
