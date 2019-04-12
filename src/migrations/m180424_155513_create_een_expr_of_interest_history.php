<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m180424_155513_create_een_expr_of_interest_history extends Migration
{
    const TABLE = "een_expr_of_interest_history";
    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true) === null)
        {
            $this->createTable(self::TABLE, [
                'id' => Schema::TYPE_PK,
                'een_expr_of_interest_id' => $this->integer()->notNull(),
                'start_status' => $this->string()->defaultValue(null)->comment('Start status'),
                'end_status' => $this->string()->defaultValue(null)->comment('End status'),
                'start_sub_status' => $this->string()->defaultValue(null)->comment('Start sub status'),
                'end_sub_status' => $this->string()->defaultValue(null)->comment('End sub status'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' =>  $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' =>  $this->integer()->comment('Created by'),
                'updated_by' =>  $this->integer()->comment('Updated at'),
                'deleted_by' =>  $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            $this->addForeignKey('fk_een_expr_of_interest_history_exprofint_id1', self::TABLE, 'een_expr_of_interest_id', 'een_expr_of_interest', 'id');
        }
        else
        {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }


    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        $this->dropTable(self::TABLE);
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
