<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    Open20Package
 * @category   CategoryName
 */

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m180328_150413_create_een_expr_of_interest extends Migration
{
    const TABLE = "een_expr_of_interest";
    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true) === null)
        {
            $this->createTable(self::TABLE, [
                'id' => Schema::TYPE_PK,
                'user_id' => $this->integer()->notNull(),
                'een_partnership_proposal_id' => $this->integer()->notNull(),
                'een_network_node_id' => $this->integer()->defaultValue(null)->comment('Network node'),
                'een_staff_id' => $this->integer()->defaultValue(null)->comment('Staff EEN'),
                'company_organization' => $this->string()->comment('Company organization'),
                'sector' => $this->string()->comment('Sector'),
                'address' => $this->string()->comment('Address'),
                'city' => $this->string()->comment('City'),
                'postal_code' => $this->string()->comment('Postal code'),
                'web_site' => $this->string()->comment('Web site'),
                'contact_person' => $this->string()->comment('Contact Person'),
                'phone' => $this->string()->comment('Phone'),
                'fax' => $this->string()->comment('Fax'),
                'email' => $this->string()->comment('Email'),
                'technology_interest' => $this->text()->comment('Technology interest'),
                'information_request' => $this->text()->comment('OInformation request'),
                'organization_presentation' => $this->text()->comment('Organization presentation'),
                'privacy' => $this->integer(1)->comment('Privacy'),
                'is_request_more_info' => $this->integer(1)->comment('Request more info'),
                'status' => $this->string()->comment('Status'),
                'sub_status' => $this->string()->comment('Sub-status'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' =>  $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' =>  $this->integer()->comment('Created by'),
                'updated_by' =>  $this->integer()->comment('Updated at'),
                'deleted_by' =>  $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            $this->addForeignKey('fk_een_expr_of_interest_userd_id1', self::TABLE, 'user_id', 'user', 'id');
            $this->addForeignKey('fk_een_expr_of_interest_proposal_id1', self::TABLE, 'een_partnership_proposal_id', 'een_partnership_proposal', 'id');
            $this->addForeignKey('fk_een_expr_of_interest_een_network_node_id1', self::TABLE, 'een_network_node_id', 'een_network_node', 'id');
            $this->addForeignKey('fk_een_expr_of_staff_een_id1', self::TABLE, 'een_staff_id', 'een_staff', 'id');

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
