<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m180327_162827_add_auth_item_een_archived*/
class m191023_125727_permission_widget_graphic extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';

        return [
            [
                'name' =>  \open20\amos\een\widgets\graphics\WidgetGraphicsLatestPartnershipProposalEen::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconEenExprOfInterest GRAPHIC',
                'ruleName' => null,
                'parent' => ['ADMINISTRATOR_EEN','EEN_READER','STAFF_EEN']
           ]
        ];
    }
}
