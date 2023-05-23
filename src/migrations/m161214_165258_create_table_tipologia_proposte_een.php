<?php

use open20\amos\core\migration\AmosMigrationTableCreation;

class m161214_165258_create_table_tipologia_proposte_een extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%tipologia_proposte_een}}';
    }
    
    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'denominazione' => $this->string(255)->notNull()->comment('Denominazione'),
            'descrizione' => $this->text()->null()->comment('Descrizione')
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
