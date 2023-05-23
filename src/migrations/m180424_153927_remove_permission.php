<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m180327_162827_add_auth_item_een_archived*/
class m180424_153927_remove_permission extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';

        return [
            [
                'name' =>  'EenExpressionOfInterestWorkflow/SUSPENDED',
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconEenExprOfInterest',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['EEN_READER']
                ]
            ],
        ];
    }
}
