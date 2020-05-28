<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    Open20Package
 * @category   CategoryName
 */
use open20\amos\core\migration\AmosMigrationPermissions;
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
                    'name' =>  \open20\amos\een\widgets\icons\WidgetIconEenStaff::className(),
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => $prefixStr . 'WidgetIconEenStaff',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                ]

            ];
    }
}
