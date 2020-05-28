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
class m181016_101613_modify_network_node extends Migration
{
    const TABLE = "een_expr_of_interest";
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->update('een_network_node', ['name' => 'Lombardia / Emilia Romagna (SIMPLER)'], ['id' => 1]);
        $this->update('een_network_node', ['name' => "Piemonte / Liguria / Val d'Aosta (ALPS)"], ['id' => 2]);
        $this->update('een_network_node', ['name' => "Veneto / Friuli / Trentino (FRIENDEUROPE)"], ['id' => 3]);
        $this->update('een_network_node', ['name' => "Toscana / Umbria / Marche (SME2EU)"], ['id' => 4]);
        $this->update('een_network_node', ['name' => "Lazio / Sardegna (ELSE)"], ['id' => 5]);
        $this->update('een_network_node', ['name' => "Abruzzo / Molise / Puglia / Basilicata / Calabria / Sicilia (BRIDG€CONOMIES)"], ['id' => 6]);
        $this->update('een_network_node', ['name' => "Other Country"], ['id' => 7]);







    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {

        return true;
    }
}
