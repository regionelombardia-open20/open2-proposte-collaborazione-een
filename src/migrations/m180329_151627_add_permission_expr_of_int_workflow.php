<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m180327_162827_add_auth_item_een_archived*/
class m180329_151627_add_permission_expr_of_int_workflow extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for Workflow Een Expr of interest';

        return [
                [
                    'name' => 'EenExpressionOfInterestWorkflow/SUSPENDED',
                    'type' => Permission::TYPE_PERMISSION,
                    'status' => \open20\amos\dashboard\models\AmosWidgets::STATUS_ENABLED,
                    'description' => $prefixStr . '',
                    'ruleName' => null,
                    'parent' => ['ADMINISTRATOR_EEN','EEN_READER','STAFF_EEN', 'EEN_VALIDATOR'],
                ],
                [
                    'name' => 'EenExpressionOfInterestWorkflow/TAKENOVER',
                    'type' => Permission::TYPE_PERMISSION,
                    'status' => \open20\amos\dashboard\models\AmosWidgets::STATUS_ENABLED,
                    'description' => $prefixStr . '',
                    'ruleName' => null,
                    'parent' => ['ADMINISTRATOR_EEN','STAFF_EEN', 'EEN_VALIDATOR'],
                ],
                [
                    'name' => 'EenExpressionOfInterestWorkflow/CLOSED',
                    'type' => Permission::TYPE_PERMISSION,
                    'status' => \open20\amos\dashboard\models\AmosWidgets::STATUS_ENABLED,
                    'description' => $prefixStr . '',
                    'ruleName' => null,
                    'parent' => ['ADMINISTRATOR_EEN', 'STAFF_EEN', 'EEN_VALIDATOR'],
                ],

            ];
    }
}
