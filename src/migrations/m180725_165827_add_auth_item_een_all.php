<?php
use lispa\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m180327_162827_add_auth_item_een_archived*/
class m180725_165827_add_auth_item_een_all extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';

        return [
                [
                    'name' => \lispa\amos\een\widgets\icons\WidgetIconEenExprOfInterestAll::className(),
                    'type' => Permission::TYPE_PERMISSION,
                    'status' => \lispa\amos\dashboard\models\AmosWidgets::STATUS_ENABLED,
                    'description' => $prefixStr . 'WidgetIconEenExprOfInterestAll',
                    'ruleName' => null,
                    'parent' => ['STAFF_EEN'],
                    'default_order' => 40

                ]

            ];
    }
}
