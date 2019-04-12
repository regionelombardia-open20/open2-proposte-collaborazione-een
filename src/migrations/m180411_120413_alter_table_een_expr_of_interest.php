<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m180411_120413_alter_table_een_expr_of_interest extends Migration
{
    const TABLE = "een_expr_of_interest";
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn(self::TABLE, 'sub_status', $this->string()->comment('Status'));

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {

    }
}
