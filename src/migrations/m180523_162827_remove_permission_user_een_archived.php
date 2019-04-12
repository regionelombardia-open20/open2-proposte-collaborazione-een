<?php
use lispa\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m180327_162827_add_auth_item_een_archived*/
class m180523_162827_remove_permission_user_een_archived extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';

        return [
                [
                    'name' => \lispa\amos\een\widgets\icons\WidgetIconEenArchived::className(),
                    'update' => true,
                    'newValues' => [
                        'removeParents' => [
                            'EEN_READER'
                        ],
                        'addParents' => [
                            'STAFF_EEN'
                        ]
                    ]

                ]

            ];
    }
}
