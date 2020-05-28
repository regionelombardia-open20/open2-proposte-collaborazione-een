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
class m180725_181927_add_permission_rules_workflow extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for Workflow Een Expr of interest';

        return [
                [
                    'name' => \open20\amos\een\rules\workflow\EenExprOfInterestWorkflowSuspendedRule::className(),
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso Workflow een eoi suspended',
                    'ruleName' => \open20\amos\een\rules\workflow\EenExprOfInterestWorkflowSuspendedRule::className(),
                    'parent' => ['STAFF_EEN'],
                    'children' => ['EenExpressionOfInterestWorkflow/SUSPENDED']
                ],
                [
                    'name' => \open20\amos\een\rules\workflow\EenExprOfInterestWorkflowTakenOverRule::className(),
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso Workflow een eoi suspended',
                    'ruleName' => \open20\amos\een\rules\workflow\EenExprOfInterestWorkflowTakenOverRule::className(),
                    'parent' => ['STAFF_EEN'],
                    'children' => ['EenExpressionOfInterestWorkflow/TAKENOVER']
                ],
                [
                    'name' => \open20\amos\een\rules\WidgetEenExprOfInterestOwnRule::className(),
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso',
                    'ruleName' =>  \open20\amos\een\rules\WidgetEenExprOfInterestOwnRule::className(),
                    'parent' => ['EEN_READER'],
                    'children' => [\open20\amos\een\widgets\icons\WidgetIconEenExprOfInterest::className()]

                ],
            [
                'name' => \open20\amos\een\rules\EenExprOfInterestWorkflowClosedRule::className(),
                'update' =>true,
                'newValues' => [
                    'addParents' => ['STAFF_EEN']
                ]
            ],
            [
                'name' => \open20\amos\een\widgets\icons\WidgetIconEenExprOfInterest::className(),
                'update' =>true,
                'newValues' => [
                    'removeParents' => ['EEN_READER','ADMINISTRATOR_EEN']
                ]
            ],
            [
                'name' => 'EenExpressionOfInterestWorkflow/TAKENOVER',
                'update' =>true,
                'newValues' => [
                    'removeParents' => ['EEN_READER','EEN_VALIDATOR','STAFF_EEN']
                ]
            ],
            [
                'name' => 'EenExpressionOfInterestWorkflow/SUSPENDED',
                'update' =>true,
                'newValues' => [
                    'removeParents' => ['EEN_READER','EEN_VALIDATOR','STAFF_EEN']
                ]
            ],
            [
                'name' => 'EenExpressionOfInterestWorkflow/CLOSED',
                'update' =>true,
                'newValues' => [
                    'removeParents' => ['EEN_READER','EEN_VALIDATOR','STAFF_EEN']
                ]
            ],

        ];
    }
}
