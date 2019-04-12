<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m180328_125713_create_table_een_staff extends Migration
{
    const TABLE = "een_staff";
    const TABLE_NETWORK = "een_network_node";

    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->schema->getTableSchema(self::TABLE, true) === null)
        {
            $this->createTable(self::TABLE_NETWORK, [
                'id' => Schema::TYPE_PK,
                'name' => $this->string()->notNull(),
                'description' => $this->integer()->defaultValue(null),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' =>  $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' =>  $this->integer()->comment('Created by'),
                'updated_by' =>  $this->integer()->comment('Updated at'),
                'deleted_by' =>  $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            $this->insert(self::TABLE_NETWORK, ['id' => 1 ,'name' => 'Lombardia / Emilia Romagna']);
            $this->insert(self::TABLE_NETWORK, ['id' => 2 ,'name' => "Piemonte / Val d'Aosta / Liguria"]);
            $this->insert(self::TABLE_NETWORK, ['id' => 3 ,'name' => 'Veneto / Friuli Venezia Giulia / Trentino Alto Adige']);
            $this->insert(self::TABLE_NETWORK, ['id' => 4 ,'name' => 'Toscana / Umbria / Marche']);
            $this->insert(self::TABLE_NETWORK, ['id' => 5 ,'name' => 'Lazio / Sardegna']);
            $this->insert(self::TABLE_NETWORK, ['id' => 6 ,'name' => 'Abruzzo / Molise / Campagna / Puglia / Basilicata / Calabria / Sicilia']);
            $this->insert(self::TABLE_NETWORK, ['id' => 7 ,'name' => 'Altri paesi']);
        }
        else
        {
            echo "Nessuna creazione eseguita in quanto la tabella esiste gia'";
        }

        if ($this->db->schema->getTableSchema(self::TABLE, true) === null)
        {
            $this->createTable(self::TABLE, [
                'id' => Schema::TYPE_PK,
                'user_id' => $this->integer()->notNull()->comment('User'),
                'een_network_node_id' => $this->integer()->notNull()->comment('Network node'),
                'staff_default' => $this->integer(1)->defaultValue(0)->comment('Staff default'),
                'created_at' => $this->dateTime()->comment('Created at'),
                'updated_at' =>  $this->dateTime()->comment('Updated at'),
                'deleted_at' => $this->dateTime()->comment('Deleted at'),
                'created_by' =>  $this->integer()->comment('Created by'),
                'updated_by' =>  $this->integer()->comment('Updated at'),
                'deleted_by' =>  $this->integer()->comment('Deleted at'),
            ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB AUTO_INCREMENT=1' : null);
            $this->addForeignKey('fk_een_staff_userd_id1', self::TABLE, 'user_id', 'user', 'id');
            $this->addForeignKey('fk_een_staff_network_node_id1', self::TABLE, 'een_network_node_id', 'een_network_node', 'id');
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
        $this->dropTable(self::TABLE_NETWORK);

        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');

    }
}
