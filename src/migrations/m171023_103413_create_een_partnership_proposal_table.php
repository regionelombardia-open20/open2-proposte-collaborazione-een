<?php

use yii\db\Migration;

/**
 * Handles the creation of table `een_partnership_proposal`.
 */
class m171023_103413_create_een_partnership_proposal_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('een_partnership_proposal', [
            'id' => $this->primaryKey(),
            'status' => $this->string(255),
            /**
             * COMPANY
             **/
            //certifications
            'company_certifications_list' => $this->text()->null(),
            //country
            'company_country_key' => $this->string(5)->null(),
            'company_country_label' => $this->string(255)->null(),
            //experience
            'company_experience' => $this->text()->null(),
            //kind
            'company_kind' => $this->string(255)->null(),
            //languages
            'company_languages_list' => $this->text()->null(),
            //since
            'company_since' => $this->string(10)->null(),
            //transnational
            'company_transnational' => $this->string(10)->null(),
            //turnover
            'company_turnover' => $this->string(10)->null(),

            /**
             * CONTACT
             **/

            //consortium
            'contact_consortium' => $this->string(255)->null(),
            //country
            'contact_consortiumcountry_key' => $this->string(5)->null(),
            'contact_consortiumcountry_label' => $this->string(255)->null(),
            //email
            'contact_email' => $this->text()->null(),
            //fullname
            'contact_fullname' => $this->text()->null(),
            //organization
            'contact_organization' => $this->text()->null(),
            //organizationcountry
            'contact_organizationcountry_key' => $this->string(5)->null(),
            'contact_organizationcountry_label' => $this->string(255)->null(),
            //partnerid
            'contact_partnerid' => $this->string(255)->null(),
            //phone
            'contact_phone' => $this->string(255)->null(),

            /**
             * CONTENT
             **/

            //certifications
            'content_description' => $this->text()->null(),
            //summary
            'content_summary' => $this->text()->null(),
            //title
            'content_title' => $this->text()->null(),

            /**
             * COOPERATION
             **/

            //cooperation
            'cooperation_exploitation_list' => $this->text()->null(),
            //ipr
            'cooperation_ipr_comment' => $this->text()->null(),
            'cooperation_ipr_status' => $this->text()->null(),
            //partner
            'cooperation_partner_area' => $this->text()->null(),
            'cooperation_partner_sought' => $this->text()->null(),
            'cooperation_partner_task' => $this->text()->null(),
            'cooperation_plusvalue' => $this->text()->null(),
            //stagedev
            'cooperation_stagedev_comment' => $this->text()->null(),
            'cooperation_stagedev_stage' => $this->text()->null(),
            //datum
            'datum_deadline' => $this->dateTime()->null(),
            'datum_submit' => $this->dateTime()->null(),
            'datum_update' => $this->dateTime()->null(),

            //dissemination?
            //'dissemination_export' => $this->text()->null(),
            //'dissemination_preferred_country_list' => $this->text()->null(),
            //'dissemination_publish_sectorgroup' => $this->text()->null(),

            //oei?

            //files ?

            //keyword?

            //partnerships?

            //program?

            //reference
            'reference_external' => $this->text()->null(),
            'reference_internal' => $this->text()->null(),
            'reference_type' => $this->string(5)->null(),
            'tags_not_found' => $this->text()->null(),
            'created_at' => $this->dateTime()->null()->defaultValue(null)->comment('Creato il'),
            'updated_at' => $this->dateTime()->null()->defaultValue(null)->comment('Aggiornato il'),
            'deleted_at' => $this->dateTime()->null()->defaultValue(null)->comment('Cancellato il'),
            'created_by' => $this->integer()->null()->defaultValue(null)->comment('Creato da'),
            'updated_by' => $this->integer()->null()->defaultValue(null)->comment('Aggiornato da'),
            'deleted_by' => $this->integer()->null()->defaultValue(null)->comment('Cancellato da')

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('een_partnership_proposal');
    }
}
