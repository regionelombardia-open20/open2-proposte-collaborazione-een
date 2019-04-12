<?php
use lispa\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m180327_162827_add_auth_item_een_archived*/
class m180412_105727_add_auth_item_een_expr_of_interest extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';

        return [
            [
                'name' =>  \lispa\amos\een\widgets\icons\WidgetIconEenExprOfInterestDashboard::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconEenExprOfInterest',
                'ruleName' => null,
                'parent' => ['ADMINISTRATOR_EEN','EEN_READER','STAFF_EEN', 'EEN_VALIDATOR']
            ],
            [
                    'name' => \lispa\amos\een\widgets\icons\WidgetIconEenExprOfInterestReceived::className(),
                    'type' => Permission::TYPE_PERMISSION,
                    'status' => \lispa\amos\dashboard\models\AmosWidgets::STATUS_ENABLED,
                    'description' => $prefixStr . 'WidgetIconEenExprOfInterestReceived',
                    'ruleName' => null,
                    'parent' => ['STAFF_EEN', 'ADMINISTRATOR_EEN'],
            ],
            [
                    'name' => \lispa\amos\een\widgets\icons\WidgetIconEenExprOfInterest::className(),
                    'type' => Permission::TYPE_PERMISSION,
                    'status' => \lispa\amos\dashboard\models\AmosWidgets::STATUS_ENABLED,
                    'description' => $prefixStr . 'WidgetIconEenExprOfInterest',
                    'ruleName' => null,
                    'parent' => ['EEN_READER','ADMINISTRATOR_EEN'],
            ]
        ];
    }
}
