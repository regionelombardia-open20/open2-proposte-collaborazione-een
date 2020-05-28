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

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m171024_161213_drop_old_een_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->execute("SET foreign_key_checks = 0;");


        if ($this->db->schema->getTableSchema('een_tipologia_proposte_een_mm', true) !== null) {
            $this->dropForeignKey('fk_een_tipologia_proposte_een_mm10',
                'een_tipologia_proposte_een_mm');
            $this->dropForeignKey('fk_tipologia_proposte_een_proposte_di_collaborazione_een_mm10',
                'een_tipologia_proposte_een_mm');
        }

        if ($this->db->schema->getTableSchema('een', true) !== null) {
            $this->dropTable('een');
        }

        if ($this->db->schema->getTableSchema('tipologia_proposte_een', true) !== null) {
            $this->dropTable('tipologia_proposte_een');
        }

        if ($this->db->schema->getTableSchema('een_tipologia_proposte_een_mm', true) !== null) {
            $this->dropTable('een_tipologia_proposte_een_mm');
        }

        if ($this->db->schema->getTableSchema('een_tipologia_proposte_een_mm', true) !== null) {
            $this->dropTable('een_tipologia_proposte_een_mm');
        }
        $this->execute("SET foreign_key_checks = 1;");
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        return true;
    }
}
