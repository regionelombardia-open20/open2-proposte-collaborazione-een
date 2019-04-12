<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m180726_113213_alter_table_een_expr_of_interest_history extends Migration
{
    const TABLE = "een_expr_of_interest_history";
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->addColumn(self::TABLE, 'end_in_charge', $this->integer()->defaultValue(null)->comment('User in charge of eoi end')->after('end_sub_status'));
        $this->addColumn(self::TABLE, 'start_in_charge', $this->integer()->defaultValue(null)->comment('User in charge of eoi start')->after('end_sub_status'));
        $this->addForeignKey('fk_een_expr_of_interest_start_in_charge', self::TABLE, 'start_in_charge', 'user', 'id');
        $this->addForeignKey('fk_een_expr_of_interest_end_in_charge', self::TABLE, 'end_in_charge', 'user', 'id');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');

        $this->dropForeignKey('fk_een_expr_of_interest_start_in_charge', self::TABLE);
        $this->dropForeignKey('fk_een_expr_of_interest_end_in_charge', self::TABLE);
        $this->dropColumn(self::TABLE, 'end_in_charge');
        $this->dropColumn(self::TABLE, 'start_in_charge');
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
