<?php
use lispa\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m180327_162827_add_auth_item_een_archived*/
class m180424_165727_add_auth_item_een_expr_of_interest_history extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for Een expression of interest history ';

        return [
            [
                'name' =>  'EENEXPROFINTERESTHISTORY_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr ,
                'ruleName' => null,
                'parent' => ['EEN_READER', 'ADMINISTRATOR_EEN']
            ],
            [
                'name' =>  'EENEXPROFINTERESTHISTORY_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr ,
                'ruleName' => null,
                'parent' => ['EEN_READER','ADMINISTRATOR_EEN']
            ],
            [
                'name' =>  'EENEXPROFINTERESTHISTORY_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr ,
                'ruleName' => null,
                'parent' => ['EEN_READER','ADMINISTRATOR_EEN']
            ],
            [
                'name' =>  'EENEXPROFINTERESTHISTORY_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr ,
                'ruleName' => null,
                'parent' => ['ADMINISTRATOR_EEN']
            ],
        ];
    }
}
