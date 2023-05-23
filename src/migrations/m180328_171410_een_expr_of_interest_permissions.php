<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m180328_171410_een_expr_of_interest_permissions*/
class m180328_171410_een_expr_of_interest_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                [
                    'name' =>  'STAFF_EEN',
                    'type' => Permission::TYPE_ROLE,
                    'description' => 'Permesso Staff een',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                ],
                [
                    'name' =>  'ADMINISTRATOR_EEN',
                    'type' => Permission::TYPE_ROLE,
                    'description' => 'Permesso Staff een',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                ],
                [
                    'name' =>  'EENEXPROFINTEREST_CREATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di CREATE sul model EenExprOfInterest',
                    'ruleName' => null,
                    'parent' => ['ADMINISTRATOR_EEN', 'EEN_READER', 'EEN_VALIDATOR', 'STAFF_EEN']
                ],
                [
                    'name' =>  'EENEXPROFINTEREST_READ',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di READ sul model EenExprOfInterest',
                    'ruleName' => null,
                    'parent' => ['ADMINISTRATOR_EEN', 'EEN_READER','EEN_VALIDATOR','STAFF_EEN']
                    ],
                [
                    'name' =>  'EENEXPROFINTEREST_UPDATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di UPDATE sul model EenExprOfInterest',
                    'ruleName' => null,
                    'parent' => ['ADMINISTRATOR_EEN', 'EEN_READER','EEN_VALIDATOR', 'STAFF_EEN']
                ],
                [
                    'name' =>  'EENEXPROFINTEREST_DELETE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di DELETE sul model EenExprOfInterest',
                    'ruleName' => null,
                    'parent' => ['ADMINISTRATOR_EEN', 'EEN_READER','EEN_VALIDATOR','STAFF_EEN']
                ],

            ];
    }
}
