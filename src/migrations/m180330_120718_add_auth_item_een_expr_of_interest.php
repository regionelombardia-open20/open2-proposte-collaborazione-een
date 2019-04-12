<?php
use lispa\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m180330_120718_add_auth_item_een_expr_of_interest*/
class m180330_120718_add_auth_item_een_expr_of_interest extends AmosMigrationPermissions
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
                ]

            ];
    }
}
