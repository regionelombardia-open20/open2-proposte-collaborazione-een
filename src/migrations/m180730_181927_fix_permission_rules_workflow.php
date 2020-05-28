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
class m180730_181927_fix_permission_rules_workflow extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for Workflow Een Expr of interest';

        return [
            [
                'name' => \open20\amos\een\rules\EenExprOfInterestWorkflowClosedRule::className(),
                'update' =>true,
                'newValues' => [
                    'addParents' => ['ADMINISTRATOR_EEN']
                ]
            ],
            [
                'name' => \open20\amos\een\rules\workflow\EenExprOfInterestWorkflowTakenOverRule::className(),
                'update' =>true,
                'newValues' => [
                    'addParents' => [ 'ADMINISTRATOR_EEN']
                ]
            ],
            [
                'name' => \open20\amos\een\rules\workflow\EenExprOfInterestWorkflowSuspendedRule::className(),
                'update' =>true,
                'newValues' => [
                    'addParents' => ['ADMINISTRATOR_EEN']
                ]
            ],
            ///----------------------
            [
                'name' => 'EenExpressionOfInterestWorkflow/TAKENOVER',
                'update' =>true,
                'newValues' => [
                    'removeParents' => ['ADMINISTRATOR_EEN']
                ]
            ],
            [
                'name' => 'EenExpressionOfInterestWorkflow/SUSPENDED',
                'update' =>true,
                'newValues' => [
                    'removeParents' => ['ADMINISTRATOR_EEN']
                ]
            ],
            [
                'name' => 'EenExpressionOfInterestWorkflow/CLOSED',
                'update' =>true,
                'newValues' => [
                    'removeParents' => ['ADMINISTRATOR_EEN'],
                ]
            ],

        ];
    }
}
