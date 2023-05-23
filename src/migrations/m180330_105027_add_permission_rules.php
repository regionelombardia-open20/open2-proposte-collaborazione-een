<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m180327_162827_add_auth_item_een_archived*/
class m180330_105027_add_permission_rules extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for Workflow Een Expr of interest';

        return [
                [
                    'name' => \open20\amos\een\rules\UpdateOwnEenExprOfInterestRule::className(),
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di modifica di una propria manifstazione di interesse',
                    'ruleName' => \open20\amos\een\rules\UpdateOwnEenExprOfInterestRule::className(),
                    'parent' => ['EEN_READER'],
                    'children' => ['EENEXPROFINTEREST_UPDATE']
                ],
                [
                    'name' => \open20\amos\een\rules\ReadOwnEenExprOfInterestRule::className(),
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di lettura di una propria manifstazione di interesse',
                    'ruleName' => \open20\amos\een\rules\ReadOwnEenExprOfInterestRule::className(),
                    'parent' => ['EEN_READER'],
                    'children' => ['EENEXPROFINTEREST_READ']
                ],

                [
                    'name' => \open20\amos\een\rules\EenExprOfInterestWorkflowClosedRule::className(),
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di lettura di una propria manifstazione di interesse',
                    'ruleName' => \open20\amos\een\rules\EenExprOfInterestWorkflowClosedRule::className(),
                    'parent' => ['EEN_READER'],
                    'children' => ['EenExpressionOfInterestWorkflow/CLOSED']
                ],


        ];
    }
}
