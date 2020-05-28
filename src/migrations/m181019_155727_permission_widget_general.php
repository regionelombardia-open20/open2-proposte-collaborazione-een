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
* Class m180327_162827_add_auth_item_een_archived*/
class m181019_155727_permission_widget_general extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';

        return [
            [
                'name' =>  \open20\amos\een\widgets\icons\WidgetIconEenDashboardGeneral::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconEenExprOfInterest',
                'ruleName' => null,
                'parent' => ['ADMINISTRATOR_EEN','EEN_READER','STAFF_EEN']
           ]
        ];
    }
}
