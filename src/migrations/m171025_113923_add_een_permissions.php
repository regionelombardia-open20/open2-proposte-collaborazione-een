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


class m171025_113923_add_een_permissions extends AmosMigrationPermissions
{
    /**
     * Use this function to map permissions, roles and associations between permissions and roles. If you don't need to
     * to add or remove any permissions or roles you have to delete this method.
     */
    protected function setAuthorizations()
    {
        $this->authorizations = array_merge(
            $this->setRolesRoles(),
            $this->setModelPermissions(),
            $this->setWidgetsPermissions()
        );
    }

    private function setRolesRoles()
    {
        return [
            [
                'name' => 'EEN_READER',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Lettore di proposte di collaborazione EEN',
                'ruleName' => null,
                'parent' => ['ADMIN', 'BASIC_USER']
            ],
            [
                'name' => 'EEN_VALIDATOR',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Lettore di proposte di collaborazione EEN',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
        ];
    }

    private function setModelPermissions()
    {
        return [

            [
                'name' => 'EENPARTNERSHIPPROPOSAL_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di lettura per il model EenPartnershipProposal',
                'ruleName' => null,
                'parent' => ['EEN_READER']
            ],

        ];
    }

    private function setWidgetsPermissions()
    {
        $prefixStr = 'Permesso per la dashboard per il widget ';
        return [
            [
                'name' => \open20\amos\een\widgets\icons\WidgetIconEenDashboard::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconEenDashboard',
                'ruleName' => null,
                'parent' => ['EEN_READER']
            ],
            [
                'name' => \open20\amos\een\widgets\icons\WidgetIconEenAll::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconEen',
                'ruleName' => null,
                'parent' => ['EEN_READER']
            ],
            [
                'name' => \open20\amos\een\widgets\icons\WidgetIconEen::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconEen',
                'ruleName' => null,
                'parent' => ['EEN_READER']
            ],

        ];
    }
}
