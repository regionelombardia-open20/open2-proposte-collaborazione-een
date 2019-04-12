<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\news
 * @category   CategoryName
 */

use lispa\amos\een\models\EenPartnershipProposal;
use yii\db\Migration;

class m171112_085025_create_een_workflow extends Migration
{
    const TABLE_WORKFLOW = '{{%sw_workflow}}';
    const TABLE_WORKFLOW_STATUS = '{{%sw_status}}';
    const TABLE_WORKFLOW_TRANSITIONS = '{{%sw_transition}}';

    private $fieldsToSkip = ['tableName', 'fieldsToCheck'];
    private $eenConf;

    public function safeUp()
    {
        $this->setNewConfigurations();
        return $this->addConfs();
    }

    /**
     * Metodo in cui settare tutte le nuove configurazioni da inserire a db.
     */
    private function setNewConfigurations()
    {
        $this->eenConf = [
            [
                'id' => EenPartnershipProposal::EEN_WORKFLOW,
                'initial_status_id' => 'DRAFT',
                'tableName' => self::TABLE_WORKFLOW,
                'fieldsToCheck' => ['id']
            ],
            [
                'id' => 'DRAFT',
                'workflow_id' => EenPartnershipProposal::EEN_WORKFLOW,
                'label' => 'Modifica in corso',
                'sort_order' => '0',
                'tableName' => self::TABLE_WORKFLOW_STATUS,
                'fieldsToCheck' => ['id', 'workflow_id']
            ],
            [
                'id' => 'TOVALIDATE',
                'workflow_id' => EenPartnershipProposal::EEN_WORKFLOW,
                'label' => 'Da validare',
                'sort_order' => '1',
                'tableName' => self::TABLE_WORKFLOW_STATUS,
                'fieldsToCheck' => ['id', 'workflow_id']
            ],
            [
                'id' => 'VALIDATED',
                'workflow_id' => EenPartnershipProposal::EEN_WORKFLOW,
                'label' => 'Validata',
                'sort_order' => '2',
                'tableName' => self::TABLE_WORKFLOW_STATUS,
                'fieldsToCheck' => ['id', 'workflow_id']
            ],


            [
                'workflow_id' => EenPartnershipProposal::EEN_WORKFLOW,
                'start_status_id' => 'DRAFT',
                'end_status_id' => 'TOVALIDATE',
                'tableName' => self::TABLE_WORKFLOW_TRANSITIONS,
                'fieldsToCheck' => ['workflow_id', 'start_status_id', 'end_status_id']
            ],
            [
                'workflow_id' => EenPartnershipProposal::EEN_WORKFLOW,
                'start_status_id' => 'DRAFT',
                'end_status_id' => 'VALIDATED',
                'tableName' => self::TABLE_WORKFLOW_TRANSITIONS,
                'fieldsToCheck' => ['workflow_id', 'start_status_id', 'end_status_id']
            ],
            [
                'workflow_id' => EenPartnershipProposal::EEN_WORKFLOW,
                'start_status_id' => 'TOVALIDATE',
                'end_status_id' => 'DRAFT',
                'tableName' => self::TABLE_WORKFLOW_TRANSITIONS,
                'fieldsToCheck' => ['workflow_id', 'start_status_id', 'end_status_id']
            ],
            [
                'workflow_id' => EenPartnershipProposal::EEN_WORKFLOW,
                'start_status_id' => 'TOVALIDATE',
                'end_status_id' => 'VALIDATED',
                'tableName' => self::TABLE_WORKFLOW_TRANSITIONS,
                'fieldsToCheck' => ['workflow_id', 'start_status_id', 'end_status_id']
            ],
            [
                'workflow_id' => EenPartnershipProposal::EEN_WORKFLOW,
                'start_status_id' => 'VALIDATED',
                'end_status_id' => 'DRAFT',
                'tableName' => self::TABLE_WORKFLOW_TRANSITIONS,
                'fieldsToCheck' => ['workflow_id', 'start_status_id', 'end_status_id']
            ],
            [
                'workflow_id' => EenPartnershipProposal::EEN_WORKFLOW,
                'start_status_id' => 'VALIDATED',
                'end_status_id' => 'TOVALIDATE',
                'tableName' => self::TABLE_WORKFLOW_TRANSITIONS,
                'fieldsToCheck' => ['workflow_id', 'start_status_id', 'end_status_id']
            ],
        ];
    }

    /**
     * Metodo che aggiunge tutte le configurazioni settate nell'array. Verifica la presenza di ciascuna e la crea se
     * non esiste già, altrimenti passa oltre.
     *
     * @return  boolean
     */
    private function addConfs()
    {
        foreach ($this->eenConf as $newCompleteConf) {
            $newConf = [];

            if (isset($newCompleteConf['withTime'])) {
                $now = time();
                $newConf['created_at'] = $now;
                $newConf['updated_at'] = $now;
            }

            foreach ($newCompleteConf as $fieldName => $fieldValue) {
                if (!in_array($fieldName, $this->fieldsToSkip)) {
                    $newConf[$fieldName] = $fieldValue;
                }
            }

            $this->createConf($newCompleteConf['tableName'], $newCompleteConf['fieldsToCheck'], $newConf);
        }

        return true;
    }

    /**
     * Metodo privato per la creazione di un singolo permesso
     *
     * @param string $tablename Nome tabella
     * @param array $fieldsToCheck Campi da verificare sulla tabella
     * @param array $newConf Array chiave => valore contenente i dati da inserire nella tabella.
     */
    private function createConf($tablename, $fieldsToCheck, $newConf)
    {
        $confName = $this->composeConsoleConfName($fieldsToCheck, $newConf);

        if ($this->checkConfExist($tablename, $fieldsToCheck, $newConf)) {
            echo "Configurazione $confName per il modulo een esistente. Skippo...\n";
        } else {
            $this->insert($tablename, $newConf);
            echo "Configurazione $confName per il modulo een creata.\n";
        }
    }

    /**
     * Ritorna il nome della configurazione da visualizzare nel messaggio in console.
     *
     * @param   array $fieldsToCheck Array contenente i campi da verificare.
     * @param   array $newConf Array contenente i valori da inserire nella tabella.
     *
     * @return  string
     */
    private function composeConsoleConfName($fieldsToCheck, $newConf)
    {
        $confName = '';
        foreach ($fieldsToCheck as $fieldName) {
            if (strlen($confName) > 0) {
                $confName .= ' - ';
            }
            $confName .= $newConf[$fieldName];
        }
        return $confName;
    }

    /**
     * Metodo per la verifica dell'esistenza del permesso in base al nome, che è univoco.
     *
     * @param   string $tablename Nome tabella
     * @param   array $fieldsToCheck Campi da verificare sulla tabella
     * @param   array $fieldsValues Valori della configurazione da verificare
     *
     * @return bool
     */
    private function checkConfExist($tableName, $fieldsToCheck, $fieldsValues)
    {
        $whereCondition = "";
        foreach ($fieldsToCheck as $fieldName) {
            $whereCondition .= ((strlen($whereCondition) > 0) ? " AND " : "") . $fieldName . " LIKE '" . $fieldsValues[$fieldName] . "'";
        }
        $sql = "SELECT COUNT(*) FROM " . $tableName . " WHERE " . $whereCondition;

        $cmd = $this->db->createCommand();
        $cmd->setSql($sql);
        $valuesCount = $cmd->queryScalar();
        return ($valuesCount > 0);
    }

    public function safeDown()
    {
        $this->setNewConfigurations();
        return $this->removeConfs();
    }

    /**
     * Metodo per la rimozione di tutte le configurazioni inserite in precedenza.
     *
     * @return  boolean
     */
    private function removeConfs()
    {
        foreach ($this->eenConf as $newCompleteConf) {
            $where = "";
            foreach ($newCompleteConf['fieldsToCheck'] as $fieldName) {
                $where .= ((strlen($where) > 0) ? " AND " : "") . $fieldName . " LIKE '" . $newCompleteConf[$fieldName] . "'";
            }
            $this->delete($newCompleteConf['tableName'], $where);
        }

        return true;
    }
}
