<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m180724_145413_alter_table_een_expr_of_interest extends Migration
{
    const TABLE = "een_expr_of_interest";
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(self::TABLE, 'org_name_inserted_manually', $this->integer(1)->defaultValue(0)->comment('Organization name inserted manually')->after('company_organization'));
        $this->addColumn(self::TABLE, 'note', $this->text()->comment('Note')->after('sub_status'));

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(self::TABLE, 'org_name_inserted_manually');
        $this->dropColumn(self::TABLE, 'note');
    }
}
