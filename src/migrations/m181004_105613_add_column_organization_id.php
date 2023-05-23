<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m181004_105613_add_column_organization_id extends Migration
{
    const TABLE = "een_expr_of_interest";
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
       $this->addColumn('een_expr_of_interest', 'organization_id', $this->integer()->after('een_staff_id')->comment('Organization'));

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');

        $this->dropColumn('een_expr_of_interest', 'organization_id');

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
