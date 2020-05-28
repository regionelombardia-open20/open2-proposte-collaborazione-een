<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\een\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m180516_113000_add_ws_een_permissions
 */
class m180516_113000_add_ws_een_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'EEN_ENABLE_READ_WS',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to call and read data from EEN PROPOSAL WebService',
                'parent' => ['ADMIN']
            ]
        ];
    }
}
