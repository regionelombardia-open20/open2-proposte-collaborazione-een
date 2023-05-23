<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m180405_101347_een_staff_permissions*/
class m180405_101347_een_staff_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                [
                    'name' =>  'EENSTAFF_CREATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di CREATE sul model EenStaff',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                ],
                [
                    'name' =>  'EENSTAFF_READ',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di READ sul model EenStaff',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                    ],
                [
                    'name' =>  'EENSTAFF_UPDATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di UPDATE sul model EenStaff',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                ],
                [
                    'name' =>  'EENSTAFF_DELETE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di DELETE sul model EenStaff',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                ],

            ];
    }
}
