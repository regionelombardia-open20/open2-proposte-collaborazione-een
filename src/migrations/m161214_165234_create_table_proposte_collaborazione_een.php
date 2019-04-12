<?php

use lispa\amos\core\migration\AmosMigrationTableCreation;

/**
 * Class m161214_165234_create_table_proposte_collaborazione_een
 */
class m161214_165234_create_table_proposte_collaborazione_een extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%proposte_collaborazione_een}}';
    }
    
    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'status' => $this->string(255)->null()->comment('Status'),
            'titolo' => $this->string(255)->notNull()->comment('Titolo'),
            'descrizione_sintetica' => $this->text()->null()->comment('Descrizione sintetica'),
            'descrizione_estesa' => $this->text()->null()->comment('Descrizione estesa'),
            'identificativo_della_proposta' => $this->string(255)->null()->comment('Identificativo della Proposta'),
            'presentazione' => $this->date()->notNull()->comment('Presentazione'),
            'scadenza' => $this->date()->notNull()->comment('Scadenza'),
            'stadio_di_sviluppo' => $this->string(255)->null()->comment('Stadio di sviluppo'),
            'commenti_sullo_stadio_di_svilu' => $this->text()->null()->comment('Commenti sullo stadio di sviluppo'),
            'stato_diritti_proprieta_ipr' => $this->string(255)->null()->comment('Stato Diritti proprietà (IPR)'),
            'commenti_sullo_stato_dei_dirit' => $this->text()->null()->comment('Commenti sullo stato dei diritti'),
            'area_di_collaborazione' => $this->text()->null()->comment('Area di collaborazione'),
            'collaborazione_richiesta' => $this->text()->null()->comment('Collaborazione richiesta'),
            'tipo_e_dimensione' => $this->string(255)->null()->comment('Tipo e Dimensione'),
            'fatturato' => $this->string(255)->null()->comment('Fatturato'),
            'anno_inizio_attivita' => $this->string(255)->null()->comment('Anno inizio attività'),
            'esperienza_transanazionale' => $this->text()->null()->comment('Esperienza transanazionale'),
            'consorzio' => $this->string(255)->null()->comment('Consorzio'),
            'paese_consorzio' => $this->string(255)->null()->comment('Paese consorzio'),
            'organizzazione' => $this->string(255)->null()->comment('Organizzazione'),
            'paese_organizzazione' => $this->string(255)->null()->comment('Paese organizzazione'),
            'email' => $this->string(255)->null()->comment('Email'),
            'telefono' => $this->string(255)->null()->comment('Telefono'),
            'identificativo' => $this->string(255)->null()->comment('Identificativo'),
            'paese' => $this->string(255)->null()->comment('Paese'),
        ];
    }
    
    /**
     * @inheritdoc
     */
    protected function beforeTableCreation()
    {
        parent::beforeTableCreation();
        $this->setAddCreatedUpdatedFields(true);
    }
}
