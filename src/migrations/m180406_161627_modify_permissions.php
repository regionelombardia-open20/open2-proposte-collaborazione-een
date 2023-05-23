<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m180327_162827_add_auth_item_een_archived*/
class m180406_161627_modify_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for Workflow Een Expr of interest';

        return [
            [
                'name' => 'EENEXPROFINTEREST_UPDATE',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['EEN_READER']
                ]
            ],
            [
                'name' => 'EENEXPROFINTEREST_READ',
                'update' => true,
                'newValues' => [
                    'removeParents' => ['EEN_READER']
                ]
            ],

            ];
    }
}
