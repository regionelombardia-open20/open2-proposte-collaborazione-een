<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m180912_115413_add_column_eoi extends Migration
{
    const TABLE = "een_expr_of_interest";
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
       $this->addColumn('een_expr_of_interest', 'know_een',$this->integer(1)->defaultValue(0)->after('note')->comment('Know EEN'));

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');

        $this->dropColumn('een_expr_of_interest', 'know_een');

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
