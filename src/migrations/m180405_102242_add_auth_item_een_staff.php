<?php
use lispa\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m180405_102242_add_auth_item_een_staff*/
class m180405_102242_add_auth_item_een_staff extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';

        return [
                [
                    'name' =>  \lispa\amos\een\widgets\icons\WidgetIconEenStaff::className(),
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => $prefixStr . 'WidgetIconEenStaff',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                ]

            ];
    }
}
